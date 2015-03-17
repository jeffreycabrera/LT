<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
define('APPROVER1', 'SSCu');

class Approver_Model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function getLeavesToApprove($approverID, $leaveID = -1){
        $sql = "SELECT a.UserID, a.Heirarchy, u.FirstName, u.LastName, l.LeaveTableID, l.DateFiled, l.StartDate, l.EndDate, l.Halfday, l.Purpose, l.LWOP, l.comment,
                    (select approverTableID from tbl_approver where ApproverID=? and UserID=a.UserID) as approverTableID,
                    (select CONCAT(Lastname, ', ',FirstName) from tbl_user where UserID=ap.ApproverID) as approver 
                FROM tbl_approver a
                    INNER JOIN tbl_user u ON u.UserID = a.UserID 
                    LEFT JOIN tbl_approver ap ON ap.userID = u.UserID AND ap.Heirarchy=2
                    INNER JOIN tbl_leave l ON l.UserID = a.UserID AND l.DateCancelled is NULL
                WHERE a.ApproverID = ? ";

        $sql .= ($leaveID == -1 || !isset($leaveID)) ? " " : " AND l.LeaveTableID=?";
        $sql .= ($approverID == APPROVER1)? " AND (l.status=1 OR (u.isApprover=1 AND l.status is NULL))" : " AND l.status is NULL";

        $result = ($leaveID == -1) ? $this->db->query($sql, array($approverID, $approverID)) : $this->db->query($sql, array($approverID, $approverID, $leaveID));
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return array();
    }

    public function getApproved($approverID, $leaveID = -1){
        $sql = "SELECT a.UserID, a.Heirarchy, u.FirstName, u.LastName, l.status, l.LeaveTableID, l.DateFiled, l.StartDate, l.EndDate, l.Halfday, l.Purpose, l.LWOP, l.comment,
                    (select approverTableID from tbl_approver where ApproverID=? and UserID=a.UserID) as approverTableID,
                    (select CONCAT(Lastname, ', ',FirstName) from tbl_user where UserID=ap.ApproverID) as approver 
                FROM tbl_approver a
                    INNER JOIN tbl_user u ON u.UserID = a.UserID 
                    LEFT JOIN tbl_approver ap ON ap.userID = u.UserID AND ap.Heirarchy=2
                    INNER JOIN tbl_leave l ON l.UserID = a.UserID AND l.DateCancelled is NULL 
                WHERE a.ApproverID = ? ";

        $sql .= ($leaveID == -1 || !isset($leaveID)) ? " " : " AND l.LeaveTableID=?";
        $sql .= ($approverID == APPROVER1)? " AND (l.status=2 OR l.status=0)" : " AND l.status IS NOT NULL";

        $result = ($leaveID == -1) ? $this->db->query($sql, array($approverID, $approverID)) : $this->db->query($sql, array($approverID, $approverID, $leaveID));
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return array();
    }

    public function getApprovedUsers($approverID){
        $sql = "SELECT a.UserID  
                FROM tbl_approver a
                    INNER JOIN tbl_leave l 
                WHERE a.approverID=? ";
        $sql .= ($approverID == APPROVER1)? " AND (l.status=2 OR l.status=0)" : " AND (l.status=1 OR l.status=0)";
        $sql .= " GROUP BY a.UserID ORDER BY a.ApproverTableID";

        $result = $this->db->query($sql, array($approverID));
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return FALSE;
    }

    public function getApprovers_approvee($approver_id){
        $sql = "SELECT a.UserID
                FROM tbl_approver a
                    LEFT JOIN tbl_user u ON u.UserID=a.ApproverID AND u.IsDeleted=0 ";

        $sql .=($approver_id == APPROVER1)? " ":" WHERE a.ApproverID=? ";
        $sql .=" GROUP BY a.UserID ORDER BY (select lastname from tbl_user where userID=a.userID)";

        $result = $this->db->query($sql, array($approver_id));
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return array();
    }
    
    public function getSummaryList(){
        $sql = "SELECT u.lastname, u.firstname,u.middlename,
            (select count(*) AS LeavesTaken from tbl_leave where startDate <= CURDATE() AND (STATUS IS NULL or STATUS >0) AND USERID = u.USERID) AS LeavesTaken,
            (c.PTO - (select count(*) AS LeavesTaken from tbl_leave where startDate <= CURDATE() AND (STATUS IS NULL or STATUS >0) AND USERID = u.USERID)) As PTOBalance
            from tbl_user u, tbl_leave_credit_type c 
            WHERE u.USERID = c.USERID";
         $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return array();
    }

    private function getPendingLeaves_WithoutApprover2($approverID){
       $sql = "SELECT l.leaveTableID, a.approverTableID, a.heirarchy, u.USERID, u.lastname, u.firstname, u.middlename, DATE_FORMAT(l.DateFiled, '%m/%d/%Y') as DateFiled, DATE_FORMAT(l.StartDate, '%m/%d/%Y') as StartDate, DATE_FORMAT(l.EndDate, '%m/%d/%Y') as EndDate, l.Purpose, l.LWOP , l.HalfDay 
                FROM tbl_user u, tbl_leave l, tbl_approver a            
                WHERE NOT EXISTS(
                        SELECT * FROM TBL_APPROVER a 
                            WHERE a.HEIRARCHY = 2 AND  CHAR_LENGTH(a.approverID) >0 AND l.userid = a.userid 
                ) 
                AND l.STATUS IS NULL 
                AND a.userid = l.userid  AND a.userid = u.userid
                AND a.heirarchy = 1  AND a.approverid = ?"; 
       $result = $this->db->query($sql, array($approverID));
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return array();
       
    }
    
    private function getPendingLeaves($approverID, $heirarchy, $userID = -1) {
        switch ($heirarchy) {
            case 2: $status = " IS NULL ";
                break;
            case 1: $status = " = 1 ";
                break;
        }

        $sql = "SELECT l.leaveTableID, a.approverTableID, a.heirarchy, u.USERID, u.lastname, u.firstname, u.middlename, 
                        DATE_FORMAT(l.DateFiled, '%m/%d/%Y') as DateFiled, 
                        DATE_FORMAT(l.StartDate, '%m/%d/%Y') as StartDate, 
                        DATE_FORMAT(l.EndDate, '%m/%d/%Y') as EndDate, l.Purpose, l.LWOP , l.HalfDay, l.status
                FROM tbl_user u, tbl_leave l, tbl_approver a 
                WHERE u.userid = l.userid AND a.userid = u.userid and STATUS " . $status . " AND a.heirarchy = ? AND a.approverID = ?";

        $sql .= ($userID == -1 || !isset($userID)) ? "" : "AND u.USERID = ?";
        $result = ($userID == -1 || !isset($userID)) ? $this->db->query($sql, array($heirarchy, $approverID)) : $this->db->query($sql, array($heirarchy, $approverID, $userID));

        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return array();
    }

    public function updateLeaveRequest($request_data){
        $sql = "UPDATE tbl_leave SET status=?, comment=?, LWOP=? WHERE leaveTableID=? ";

        if($request_data['heirarchy']==1){
            $this->db->query($sql, array(
                ($request_data['status']==1)?2:0, 
                $request_data['comment'], 
                array_key_exists('LWOP', $request_data)?$request_data['LWOP']:0, 
                $request_data['leaveID']));

            if ($this->db->affected_rows() <= 0){
                return array("succes"=>false, "description"=>"DB ERROR OCCURED!");
            }else{
                $sql2 = "INSERT INTO tbl_approval_leave (leavetableID,approvertableid,approvalDate) VALUES(?, ?, now()) ";
                $this->db->query($sql2, array($request_data['leaveID'], $request_data['approverTableID']));
                return array("succes"=>true);
            }

        }else{ 
            $this->db->query($sql, array(
                ($request_data['status']==1)?1:0, 
                $request_data['comment'], 
                array_key_exists('LWOP', $request_data)?$request_data['LWOP']:0, 
                $request_data['leaveID']));

            if ($this->db->affected_rows() <= 0){
                return array("succes"=>false, "description"=>"DB ERROR OCCURED!");
            }else{
                $sql2 = "INSERT INTO tbl_approval_leave (leavetableID,approvertableid,approvalDate) VALUES(?, ?, now()) ";
                $this->db->query($sql2, array($request_data['leaveID'], $request_data['approverTableID']));
                return array("succes"=>true);
            }
        }
    }

    public function addApproverAutoComplete() {
        // $sql = "SELECT UserID, CONCAT(Lastname,', ', FirstName) AS name FROM tbl_user WHERE isApprover = ? AND IsDeleted = ? ORDER BY ?";
        $sql = "SELECT u.UserID, CONCAT(u.Lastname,', ', u.FirstName) As name, a.Heirarchy, IsAdmin  
                                    FROM tbl_user u 
                                        LEFT JOIN tbl_approver a ON a.ApproverID = u.UserID 
                                    WHERE u.isApprover = ? AND u.IsDeleted = ? Group By u.UserID ORDER BY u.Lastname";
        $result = $this->db->query($sql, array(0, 0));
        return $result->result_array();
    }
}