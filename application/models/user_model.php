<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function getUsers($userid = -1) {
        $sql = "SELECT a.UserID, a.LastName, a.FirstName, a.MiddleName, a.Email, c.PTO, a.IsApprover, a.IsAdmin 
                FROM tbl_user a 
                    INNER JOIN tbl_leave_credit_type c ON a.UserID = c.UserID AND c.LCTID IN (SELECT MAX(LCTID) FROM tbl_leave_credit_type WHERE UserID=a.UserID)
                WHERE a.isDeleted = 0 ";
                
        $sql .= ($userid == -1 || !isset($userid)) ? " GROUP BY a.UserID ORDER BY a.Lastname" : " and a.UserID = ? GROUP BY a.UserID ORDER BY a.Lastname";
        $result = ($userid == -1 || !isset($userid)) ? $this->db->query($sql) : $this->db->query($sql, array($userid));

        $rows = array();
        $users = $result->result_array();
        if ($result->num_rows() > 0){
            foreach ($users as $row) {
                $approvers = $this->getUserApprovers($row['UserID']);
                $row["approvers"] = array();
                foreach ($approvers as $approver) {
                    $row["approvers"][$approver["Heirarchy"]] = $approver;
		}
                array_push($rows, $row);
            }

            return ($userid == -1 || !isset($userid)) ? $rows : $rows[0];
        }else{
            return $rows;
        }
    }

    public function getUser_summary($userid = -1){
        $sql = "SELECT a.FirstName, a.LastName, a.MiddleName, a.IsApprover, a.IsAdmin, coalesce(b.LeavesRequest,0) as LeavesRequest,
                coalesce(c.LeavesTaken,0) as LeavesTaken, coalesce(e.LWOP, 0) as LWOP, coalesce(d.PTO,0) as PTO, coalesce(d.PTO,0) - coalesce(c.LeavesTaken,0) + coalesce(e.LWOP, 0) as PTOBalance
                from tbl_user a left Outer join
                
                (SELECT UserID, SUM(workday) as LeavesRequest FROM
                    (SELECT UserID, SUM(TOTAL_WEEKDAYS(StartDate, EndDate)) as workday FROM tbl_leave WHERE startDate >= CURDATE() and year(enddate) = year(curdate())
                    AND STATUS is NOT FALSE AND DateCancelled is NULL AND halfday = 0 AND lwop = 0
                        Group By UserID
                    UNION ALL
                    SELECT UserID, SUM(TOTAL_WEEKDAYS(StartDate, EndDate))/2 as workday FROM tbl_leave WHERE startDate >= CURDATE() and year(enddate) = year(curdate())
                    AND STATUS is NOT FALSE AND DateCancelled is NULL AND halfday = 1 AND lwop = 0
                    Group by UserID
                )a Group By UserID) b on a.UserID = b.UserID Left Outer Join


                (SELECT UserID, SUM(workday) as LeavesTaken FROM
                (SELECT UserID, SUM(TOTAL_WEEKDAYS(StartDate, EndDate)) as workday FROM tbl_leave WHERE endDate < CURDATE() and year(enddate) = year(curdate())
                    AND STATUS = 2  AND DateCancelled is NULL AND halfday = 0 AND lwop = 0
                    Group By UserID
                    UNION ALL
                    SELECT UserID, SUM(TOTAL_WEEKDAYS(StartDate, EndDate))/2 as workday FROM tbl_leave WHERE endDate < CURDATE() and year(enddate) = year(curdate())
                    AND STATUS = 2  AND DateCancelled is NULL AND halfday = 1 AND lwop = 0
                    Group by UserID
                )a Group By UserID)c on a.UserID = c.userID Left Outer Join


                (SELECT UserID, SUM(workday) as LWOP FROM
                (SELECT UserID, SUM(TOTAL_WEEKDAYS(StartDate, EndDate)) as workday FROM tbl_leave WHERE endDate < CURDATE() and year(enddate) = year(curdate())
                    AND STATUS = 2  AND DateCancelled is NULL AND halfday = 0 and LWOP = 1
                    Group By UserID
                    UNION ALL
                    SELECT UserID, SUM(TOTAL_WEEKDAYS(StartDate, EndDate))/2 as workday FROM tbl_leave WHERE endDate < CURDATE() and year(enddate) = year(curdate())
                    AND STATUS = 2  AND DateCancelled is NULL AND halfday = 1 and LWOP = 1
                    Group by UserID
                )a Group By UserID)e on a.UserID = e.userID Left oUter Join 
                
                (Select a.UserID, a.PTO from tbl_leave_credit_type a inner join
                 (Select userID, max(LCTID) as LCTID from tbl_leave_credit_Type
                  group by userid) b on a.LCTID = b.LCTID) d on a.UserID = d.UserID ";

        $sql .= ($userid == -1) ? "  order by a.lastname desc" : " WHERE a.USERID=? " ;
        $result = ($userid == -1) ? $this->db->query($sql) : $this->db->query($sql, array($userid));

        $users = $result->num_rows() > 0 ? $result->result_array() : array();
        return ($userid == -1) ? $users : $users[0];
    }

    public function getUserApprovers($userid = -1) {
        $sql = "SELECT a.ApproverID, a.Heirarchy, u.email, u.FirstName, CONCAT(u.Lastname,', ', u.FirstName) As ApproverName 
				FROM tbl_user as u 
					INNER JOIN tbl_approver as a ON u.userID = a.ApproverID 
                WHERE a.isActive =1 ";
                
        $sql .= ($userid == -1 || !isset($userid)) ? " ": " AND a.USERID = ? ";
        $sql .= " GROUP BY a.ApproverID ORDER BY a.Heirarchy asc";
				
        $result = ($userid == -1 || !isset($userid)) ? $this->db->query($sql) : $this->db->query($sql, array($userid));
        if ($result->num_rows() > 0) {
            return $result->result_array();
		}
        return array();
	}

    public function getALLApprovers() {
        $result = $this->db->query("SELECT u.UserID as ApproverID, CONCAT(u.Lastname,', ', u.FirstName) As ApproverName, a.Heirarchy, IsAdmin  
                                    FROM tbl_user u 
                                        LEFT JOIN tbl_approver a ON a.ApproverID = u.UserID 
                                    WHERE u.isApprover = 1 AND u.IsDeleted =0 Group By u.UserID ORDER BY u.Lastname");
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return array();
    }
}