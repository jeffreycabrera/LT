<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Personal_Model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function getRequestList($user_id){
		$sql = "SELECT LeaveTableID, DateFiled, StartDate, EndDate, Purpose, LWOP, Status, HalfDay   
				FROM tbl_leave 
				WHERE UserID=? AND DateCancelled is NULL
				ORDER BY StartDate desc";

		$leave_list = $this->db->query($sql, array($user_id));
		if ($leave_list->num_rows() > 0) {
            return $leave_list->result_array();
        }
		return array();
	}

	public function add_request($request_data){
		//{"startDate":"yyyy-mm-dd","endDate":"yyyy-mm-dd","LWOP":true/false,"reason":"reason","user_id":"Abc123","halfday":true/false}
		$datenow = date('Y-m-d H:i:s');
		$sql = "INSERT INTO tbl_leave(UserID, DateFiled, StartDate, EndDate, HalfDay, Purpose, LWOP) 
				VALUES(?,?,?,?,?,?,?)";
		
		$this->db->query($sql, array(
			$request_data["user_id"],
			$datenow,
			$request_data["startDate"],
			$request_data["endDate"],
			$request_data["halfday"],
			addslashes(mysql_real_escape_string($request_data["reason"])),
			$request_data["LWOP"]));

		$sql2 = "SELECT LeaveTableID, DateFiled from tbl_leave ORDER BY LeaveTableID DESC LIMIT 1";
		$query = $this->db->query($sql2);

		if ($this->db->affected_rows() <= 0){
			return array("succes"=>false, "description"=>"DB ERROR OCCURED!");
		}else{
			return array("succes"=>true, "table_id"=>$query->result() , 'dateFiled'=> date("Y-m-d"), strtotime($datenow));
		}
	}

	public function cancel_request($request_data){
		$datenow = date('Y-m-d H:i:s');
		$sql = "UPDATE tbl_leave 
				SET DateCancelled=? 
				WHERE UserID=? AND LeaveTableID=? AND DateCancelled is NULL";
		
		$this->db->query($sql, array(
			$datenow, 
			$request_data["user_id"], 
			$request_data["table_id"]));

		if ($this->db->affected_rows() <= 0){
			return array("succes"=>false, "description"=>"DB ERROR OCCURED!");
		}else{
			return array("succes"=>true);
		}
	}

	public function edit_request($user_id, $leave_id){
		$sql = "SELECT StartDate, EndDate, HalfDay, Purpose, LWOP, Status, Comment   
				FROM tbl_leave 
				WHERE UserID=? AND LeaveTableID=? AND DateCancelled is NULL";
		
		$requested_leave = $this->db->query($sql, array($user_id, $leave_id));
		$requested_leave = $requested_leave->result_array();
		
		if ($requested_leave){
			return array_pop($requested_leave);
		}
		return false;
	}

	public function update_request($request_data){
		$sql = "UPDATE tbl_leave 
				SET StartDate=?, EndDate=?, HalfDay=?, Purpose=?, LWOP=?
				WHERE UserID=? AND LeaveTableID=? AND Status is NULL AND DateCancelled is NULL";

		$this->db->query($sql, array(
			$request_data["startDate"],
			$request_data["endDate"],
			$request_data["halfday"],
			$request_data["reason"],
			$request_data["LWOP"],
			$request_data["user_id"],
			$request_data["table_id"],));

		$sql2 = "SELECT DateFiled, Status from tbl_leave WHERE LeaveTableID=?";
		$query = $this->db->query($sql2, array($request_data["table_id"]));

			$arr = array();
			foreach ($query->result_array() as $row) {
				$arr['Status'] = $row['Status'] == "" ? 'Pending' : 'Processing';
				$arr['DateFiled'] = date("Y-m-d", strtotime($row['DateFiled']));
			}

		if ($this->db->affected_rows() <= 0){
			return array("succes"=>false, "description"=>"DB ERROR OCCURED!");
		}else{
			return array("succes"=>true, 'dateFiled'=>$arr);
		}
	}
}