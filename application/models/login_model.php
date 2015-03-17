<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');	
	
class Login_Model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	public function login($uid, $password) {
		$sql = "SELECT UserID, FirstName, LastName, IsAdmin, IsApprover
				FROM tbl_user 
				WHERE UserID=? AND Password=?";

		$result = $this->db->query($sql, array($uid, $password));
		$result = $result->result();
		if ($result){
			return array_pop($result);
		}
		return 0;
	}
	
	public function isresetpass($uid){
		$sql = "SELECT isresetpassword FROM tbl_user WHERE UserID = ?";
		$user = $this->db->query($sql, array($uid));
		$user = $user->result_array();
		if ($user) {
			return $user[0]['isresetpassword'];
		}
		return '-1';
	}
	
	public function resetpass($cpass, $uid){
		$sql = "UPDATE tbl_user 
				SET Password = ?, isresetpassword = ? 
				WHERE UserID = ? AND isresetpassword=0";
		
		$this->db->query($sql, array($cpass, 1, $uid));
		if ($this->db->affected_rows() <= 0){
			return array("succes"=>"0");
		}else{
			return array("succes"=>"1");
		}
	}
}
?>
