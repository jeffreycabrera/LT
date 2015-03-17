<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class approvers_model extends CI_Model {

	public function add_approver($UserID) {
		$sql = "UPDATE tbl_user SET isApprover = 1 WHERE UserID = '{$UserID}'";
		$this->db->query($sql);
		return array("success"=>true, "title"=>"SUCCESS!", "description"=>"Approver has been successfuly added.");
	}

	public function delete_approver($UserID){
        $this->db->query("UPDATE tbl_user SET IsApprover=0 WHERE UserID = '{$UserID}'");
        return array("success"=>true, "title"=>"SUCCESS!", "description"=>"Approver has been successfuly deleted.");
    }
}




