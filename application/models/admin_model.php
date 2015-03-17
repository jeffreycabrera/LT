<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function addUser($details, $userID, $password) {
        $sql = "INSERT INTO tbl_user (userID, FirstName, LastName, MiddleName, Email, password, isApprover) VALUES (?,?,?,?,?,?,?)";
        $this->db->query($sql, array(
            $userID, 
            ucwords(strtolower($details["firstName"])), 
            ucwords(strtolower($details["lastName"])), 
            ucwords(strtolower($details["middleName"])), 
            $details['emailAddress'], 
            $password, 
            (isset($details['isApprover'])? $details['isApprover'] : 0)));

        if ($this->db->affected_rows() > 0){
            $sql2 = "INSERT INTO tbl_leave_credit_type (userID, PTO, Date_Applied) VALUES (?,?,?)";
            $this->db->query($sql2, array(
                $userID, 
                $details['PTO'], 
                date('Y-m-d H:i:s')));

            if ($this->db->affected_rows() > 0){
                $approver1 = array("approver"=>$details["approver1"],"heirarchy"=>1);
                $approver2 = isset($details["approver2"]) ? array("approver"=>$details["approver2"],"heirarchy"=>2) : "";
                $approvers = $approver2=="" ? $approver1 : array_merge($approver1, $approver2);

                for($i = 0; $i < count($approvers); $i++) {
                    $sql3 = "INSERT INTO tbl_approver (ApproverID, userID, Heirarchy, isActive) Values(?,?,?, 1)";
                    $this->db->query($sql3, array(
                        $approvers["approver"],
                        $userID,
                        $approvers['heirarchy']));

                    if ($this->db->affected_rows() <= 0){
                        return array("succes"=>false, "title"=>"DB ERROR OCCURED!", "description"=>"Adding Approvers Failed.");
                    }
                }
                return array("succes"=>true, "title"=>"SUCCESS", "description"=>"New user has been succesfully added.");
            }else{
                return array("succes"=>false, "title"=>"DB ERROR OCCURED!", "description"=>"Adding PTO Failed.");
            }
        }
        return array("succes"=>false, "title"=>"DB ERROR OCCURED!", "description"=>"Adding User Failed.");
    }

    public function editUserDetails($details, $userid) {
        $sql = "UPDATE tbl_user a INNER JOIN tbl_leave_credit_type b ON (a.userid = b.userid ) SET a.LastName = ?, a.FirstName=?,a.MiddleName=?,a.Email=?,  a.isApprover = ? , b.pto = ? WHERE a.userid = '$userid' AND b.userid = '$userid' ";
        $this->db->query($sql, array(
            ucwords(strtolower($details["lastName"])), 
            ucwords(strtolower($details["firstName"])), 
            ucwords(strtolower($details["middleName"])), 
            $details['emailAddress'], 
            $details['isApprover'],
            $details['pto']));

        if ($this->db->affected_rows() <= 0) {
            return false;
        }
        $this->toggleApproverStatus($userid, $details['isApprover']);
        return true;
    }

    public function setUserApprovers($approvers, $userid) {
        foreach ($approvers as $approver) {
            $sql = $this->hasExistingApprover($userid, $approver["hierarchy"]) ?
                    "UPDATE tbl_approver SET ApproverID = ?, isActive=1 WHERE USERID = ? AND Heirarchy = ?" :
                    "INSERT INTO tbl_approver (ApproverID, USERID, Heirarchy,isActive) Values(?,?,?, 1)";

            $result = $this->db->query($sql, array($approver["approverID"], $userid, $approver["hierarchy"]));
            if ($result2->db->affected_rows() > 0){

            }
            return array("succes"=>false, "title"=>"DB ERROR OCCURED!", "description"=>"Setting Approvers Failed.");
        }
    }

    public function deleteUser($userID) {
        $this->db->query("UPDATE tbl_user SET IsDeleted=1 WHERE UserID = '" . $userID . "'");
        $this->toggleApproverStatus($userID, 0);
    }

    public function generatePassword($charlength = 6) {
        $letters = 'abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        return substr(str_shuffle($letters), 0, $charlength);
    }

    public function isExistingApproverOnUser($userID, $approverID) {
        $sql = "SELECT * from tbl_approver WHERE userid = ? and approverID = ?";
        $this->db->query($sql, array($userID, $approverID));

        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return array();
    }

    private function toggleApproverStatus($approverID, $isActive) {
        $sql = "UPDATE tbl_approver SET isActive = ? WHERE approverID = ?";

        $this->db->query($sql, array($isActive, $approverID));

        if ($this->db->affected_rows() <= 0) {
            return false;
        }
        return true;
    }

    /**
     * @param type $UserID UserID of user
     * @return array of approvers of the user
     */
    private function getUserApprovers($UserID) {
        $sql = "SELECT b.approverID, b.Heirarchy, CONCAT(a.FirstName, ' ',  a.Lastname) As ApproverName 
                FROM tbl_user a, tbl_approver b 
                WHERE a.UserID = b.ApproverID AND b.isActive =1 AND b.USERID = ? ORDER BY b.Heirarchy";

        $result = $this->db->query($sql, array($UserID));

        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return array();
    }

    /**
     * Function that prevents duplicate entry on approver_table
     */
    private function hasExistingApprover($userID, $heirarchy) {
        $sql = "SELECT * FROM `tbl_approver` WHERE USERID = ? and heirarchy = ?";
        $result = $this->db->query($sql, array($userID, $heirarchy));

        if ($result->num_rows() > 0) {
            return TRUE;
        }
        return FALSE;
    }

}
