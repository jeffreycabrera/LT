<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}


	"SELECT 
	a.UserID, 
	a.Heirarchy, 
	u.FirstName, 
	u.LastName, 
	l.LeaveTableID, 
	l.DateFiled, 
	l.StartDate, 
	l.EndDate, 
	l.Halfday, 
	l.Purpose, 
	l.LWOP, 
	l.comment, 
	(select approverTableID from tbl_approver where ApproverID=0 and UserID=a.UserID) as approverTableID, 
	(select CONCAT(Lastname, ', ',FirstName) from tbl_user where UserID=ap.ApproverID) as approver 
	FROM tbl_approver a 
	INNER JOIN tbl_user u ON u.UserID = a.UserID 
	LEFT JOIN tbl_approver ap ON ap.userID = u.UserID AND ap.Heirarchy=2 
	INNER JOIN tbl_leave l ON l.UserID = a.UserID AND l.DateCancelled is NULL 
	WHERE a.ApproverID = 0 AND l.LeaveTableID='81' AND l.status is NULL"
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */