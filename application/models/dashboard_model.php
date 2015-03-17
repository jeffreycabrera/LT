<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_Model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
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

    private function setUserApprovers($approvers, $user_id) {
    	$i=0;
    	foreach ($approvers as $approver) {
    		$i = ++$i;
    		$sql = $this->hasExistingApprover($user_id, $i) ?
                    "UPDATE tbl_approver SET ApproverID = ?, isActive=1 WHERE USERID = ? AND Heirarchy = ?" :
                    "INSERT INTO tbl_approver (ApproverID, USERID, Heirarchy, isActive) Values(?, ?, ?, 1)";

            $this->db->query($sql, array(
            	$approver, 
            	$user_id, 
            	$i));
            
            //  if ($this->db->affected_rows() <= 0){
        	//         return array("succes"=>false, "title"=>"DB ERROR OCCURED!", "description"=>"Managing approvers failed.");
            //  }
    	}
    	return true;
    }

    public function check_userExistance($user_data){
        $userID = userID_generator($user_data["firstName"], $user_data["middleName"], $user_data["lastName"]);

        $isExist = $this->db->query("SELECT UserID FROM tbl_user WHERE UserID = ? ", array($userID));
        if ($isExist->num_rows() > 0) {
            return array('isExist'=>true, 'hidUserID'=>$userID);
        }
        return array('isExist'=>false);
    }

    public function update_user($user_data){
    	if ($user_data['approver1'] == '0'){
    		return array("succes"=>false, "title"=>"DB ERROR OCCURED!", "description"=>"Approver 1 is required.");
    	}
    	$approvers = array("approver1"=>$user_data['approver1'], "approver2"=>$user_data['approver2']);

		$this->db->query("UPDATE tbl_user SET LastName=?, FirstName=?, MiddleName=?, Email=?, isDeleted=0 WHERE userID=? ", array(
            $user_data['lastName'], 
            $user_data['firstName'], 
            $user_data['middleName'], 
            $user_data['emailAddress'],
            $user_data['hidUserID']));

		// if ($this->db->affected_rows() <= 0) {
        //      return array("succes"=>false, "title"=>"DB ERROR OCCURED!", "description"=>"Failed updating user details.");
        // }
        $this->setUserApprovers($approvers, $user_data['hidUserID']);
        $this->db->query("INSERT INTO tbl_leave_credit_type (UserID, PTO, Date_Applied) VALUES(?, ?, ?)", array(
        	$user_data['hidUserID'],
        	$user_data['PTO'],
        	date('Y-m-d H:i:s')));

        // if ($this->db->affected_rows() <= 0) {
        //     return array("succes"=>false, "title"=>"DB ERROR OCCURED!", "description"=>"Failed updating leave credit.");
        // }
        return array("succes"=>true, "title"=>"SUCCESS!", "description"=>"User details has been successfuly updated.");
    }

    public function create_user($user_data){
        $userID = userID_generator($user_data["firstName"], $user_data["middleName"], $user_data["lastName"]);
        $temp_Passcode = substr(str_shuffle(sha1(microtime())), 0, 10);

        if ($user_data['approver1'] == '0'){
            return array("succes"=>false, "title"=>"DB ERROR OCCURED!", "description"=>"Approver 1 is required.");
        }
        $approvers = array("approver1"=>$user_data['approver1'], "approver2"=>$user_data['approver2']);

        $this->db->query("INSERT INTO tbl_user (userID, FirstName, LastName, MiddleName, Email, password) VALUES (?,?,?,?,?,?)", array(
            $userID, 
            $user_data["firstName"], 
            $user_data["lastName"], 
            $user_data["middleName"], 
            $user_data['emailAddress'], 
            sha1($temp_Passcode)
            ));

        // if ($this->db->affected_rows() <= 0){
        //      return array("succes"=>false, "title"=>"DB ERROR OCCURED!", "description"=>"Failed adding user details.");
        // }
        $this->setUserApprovers($approvers, $userID);
        $this->db->query("INSERT INTO tbl_leave_credit_type (userID, PTO, Date_Applied) VALUES (?,?,?)", array(
            $userID, 
            $user_data['PTO'], 
            date('Y-m-d H:i:s')));

        // if ($this->db->affected_rows() <= 0) {
        //     return array("succes"=>false, "title"=>"DB ERROR OCCURED!", "description"=>"Failed adding leave credit.");
        // }
        $newUser_data = array('userID'=>$userID, 'FirstName'=>$user_data["firstName"], 'temp_Passcode'=>$temp_Passcode, 'mailto'=>$user_data['emailAddress']);
        $this->session->set_flashdata('newUser_data', $newUser_data);
        return array("succes"=>true, "userID"=>$userID,"title"=>"SUCCESS!", 'act'=>'create', "description"=>"New user has been successfuly added.");
    }

    public function delete_user($userID){
        $this->db->query("UPDATE tbl_user SET IsDeleted=1 WHERE UserID = '". $userID ."'");
        $this->db->query("UPDATE tbl_approver SET isActive=0 WHERE approverID = '". $userID ."'");

        return array("succes"=>true, "title"=>"SUCCESS!", "description"=>"User has been successfuly deleted.");
    }

    public function resetPassword($userID, $temp_Passcode){
        $this->db->query("UPDATE tbl_user SET Password=?, isresetpassword=0 WHERE UserID=?", array($temp_Passcode, $userID));
        if ($this->db->affected_rows() < 1){
            return false;
        }
        return true;
    }
}