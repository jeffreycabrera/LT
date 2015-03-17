<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Summary_PTO_Model extends CI_Model {
	public function __construct(){
		parent::__construct();
		$this->load->database();
	}

	public function update_leave_balance($carryOver, $encash, $year, $userId) {
		$leaveBalance = array(
			'total_carry_over' => $carryOver,
			'total_encash' => $encash,
			'year' => $year
		);
		$result = $this->db->update('tbl_leave_balance', $leaveBalance, array('UserID', $userId));
		if ($result) {
			return true;
		}
		return false;
	}
}
