<?php

	if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
	class IALogin_Model extends CI_Model{
		
		public function __construct() {
			parent::__construct();
			$this->load->database();
		}
		
		public function login($uid, $password) {
			$this->db->select('userid, firstname, password, isadmin, isapprover, isdeleted');
			$this->db->from('tbl_user');
			$this->db->where('UserId', $uid);
			$this->db->where('Password', $password);
			$this->db->where('isdeleted', 0);
			$this->db->limit(1);
         
			
			$query = $this->db->get();
			
			if($query->num_rows() == 1) {
			    return $query->result(); 
			} else {
			    return false;
			}
		}
		
		public function isresetpass($uid){
			$this->db->select('isresetpassword');
			$this->db->from('tbl_user');
			$this->db->where('UserId', $uid);
			$this->db->limit(1);
			
			$query = $this->db->get();
			
			if($query->num_rows() == 1) {
			    return $query->result(); 
			} else {
			    return false; 
			}
		}
		
		public function resetpass($uid, $cpass){
			$resetpassquery = "UPDATE test_ci.tbl_user SET Password = '".$cpass."', IsResetPassword = '1' WHERE tbl_user.UserID = '".$uid."'";
			$sql = $this->db->query($resetpassquery);
			return true;
		}
	}
?>
