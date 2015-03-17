<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');	
	
class Custom_gen_Model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	public function getAllLeaves() {
		$sql = "SELECT l.UserID, CONCAT(u.Lastname,', ', u.FirstName) As 'FullName', l.DateFiled, l.StartDate, l.EndDate, 
					if(l.HalfDay, 'YES', 'NO') As 'HalfDay', 
					l.Purpose, 
					if(l.LWOP, 'YES', 'NO') As 'LWOP', 
					case l.Status 
						when 0 then 'DECLINED' 
						when 1 then 'PROCESSING' 
						when 2 then 'APPROVED' 
						else 'PENDING' 
					end as 'Status', l.Comment 
				FROM tbl_leave l 
					INNER JOIN tbl_user u ON l.UserID=u.UserID 
				WHERE l.DateCancelled is NULL 
				ORDER BY `l`.`LWOP` ASC  ";

		$result = $this->db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return array();
	}
}