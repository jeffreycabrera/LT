<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Personal extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('personal_model');
		$this->load->helper('format');
		$this->load->helper('leaves');
	}

	public function index()
	{
		$is_Login = $this->session->userdata('lt_isLogin');
		if($is_Login == 1){
		$this->data['is_Login'] = $is_Login;
			$this->data['page'] = "personal";
			$user_id = $this->session->userdata('lt_logged_ID');

			$this->data['user_summary'] = $this->user_model->getUser_summary($user_id);
			$LeavesEarned = getPTO_earned(
				$this->data['user_summary']['PTO'], 
				$this->data['user_summary']['PTOBalance']
				);
			$this->data['user_summary']['LeavesEarned'] = $LeavesEarned;

			$AvailBal = $LeavesEarned - $this->data['user_summary']['LeavesTaken'];
			$this->data['user_summary']['AvailBal'] = $AvailBal;
			
			$this->data['leave_list'] = $this->personal_model->getRequestList($user_id);
			$LeaveRequest = getLeaveRequest($this->data['leave_list']);

			$this->data['user_approvers'] = $this->user_model->getUserApprovers($user_id);

			$this->data['content'] = "view_personal";
			$this->data['jsscript'] = "view_personal_script";
			$this->data['ajax_REQUESTED'] = $this->input->is_ajax_request();
			$this->load->view('template_dashboard', $this->data);
		}else{
			redirect(base_url('/login/'), 'refresh');
		}
	}

	public function load_leaveRequestForm() {
        if ($this->session->userdata('lt_isLogin')) {
        	$user_id = $this->session->userdata('lt_logged_ID');
        	$input = $this->input->post(); 
        	$approver = $this->user_model->getUserApprovers($user_id);
        	$userSummary = $this->user_model->getUser_summary($user_id);

        	$data['fullName'] = $userSummary['FirstName'] . ' ' . $userSummary['LastName'];
 
        	if (isset($approver[1])) {
        		$data['user_approver_email'] = $approver[1]['email'];
        		$data['user_approver_name'] = $approver[1]['FirstName'];		
        	}else {
        		$data['user_approver_email'] = $approver[0]['email'];
        		$data['user_approver_name'] = $approver[0]['FirstName'];	
        	}

        	if($input['action'] == 'requestLeave') {
        		$data['table_id'] = $input['table_id'];
        		$data['btn_name'] = 'Save';
        		$data['action'] = $input['action'];
        		$response['html_view'] = $this->load->view("inc/inc_LeaveForm", $data, TRUE);
        		echo json_encode($response); 
        	} else {
        		$user_id = $this->session->userdata('lt_logged_ID'); 
        		$leave_id = $this->input->post('table_id');
        		$fetched_data = $this->personal_model->edit_request($user_id, $leave_id);
        		$data['tableID'] = $leave_id;
        		$data['action'] = $input['action'];
        		$data['btn_name'] = 'Save Changes';
    			$data['StartDate'] = $fetched_data['StartDate'];
    			$data['EndDate'] = $fetched_data['EndDate'];
    			$data['HalfDay'] = $fetched_data['HalfDay'];
    			$data['Purpose'] = $fetched_data['Purpose'];
    			$data['LWOP'] = $fetched_data['LWOP'];
    			$data['Status'] = $fetched_data['Status'];
    			$data['Comment'] = $fetched_data['Comment'];

                $response['html_view'] = $this->load->view("inc/inc_LeaveForm", $data, TRUE);
                echo json_encode($response);
        	}  
        } else {
            redirect(base_url('/login/'), 'refresh');
        }
    }

    public function load_delete() {
        if ($this->session->userdata('lt_isLogin')) {    
        		$data['userID'] = $this->session->userdata('lt_logged_ID');
        		$data['action'] = $this->input->post('action');
        		$data['table_id'] = $this->input->post('table_id');
                $response['html_view'] = $this->load->view("inc/inc_DeleteLeaveRequest", $data, TRUE);
                echo json_encode($response);  
        } else {
            redirect(base_url('/login/'), 'refresh');
        }
    }

    public function load_details() {
        if ($this->session->userdata('lt_isLogin')) {   
        		$user_id = $this->session->userdata('lt_logged_ID'); 
        		$leave_id = $this->input->post('table_id');
        		$fetched_data = $this->personal_model->edit_request($user_id, $leave_id);

    			$data['StartDate'] = $fetched_data['StartDate'];
    			$data['EndDate'] = $fetched_data['EndDate'];
    			$data['HalfDay'] = $fetched_data['HalfDay'];
    			$data['Purpose'] = $fetched_data['Purpose'];
    			$data['LWOP'] = $fetched_data['LWOP'];
    			$data['Status'] = $this->status($fetched_data['Status']);
    			$data['Comment'] = $fetched_data['Comment'];

                $response['html_view'] = $this->load->view("inc/inc_LeaveDetail", $data, TRUE);
                echo json_encode($response);  
        } else {
            redirect(base_url('/login/'), 'refresh');
        }
    }

	public function process_leave()
	{
		if($this->session->userdata('lt_isLogin')){

			if ($this->form_validation->run() == FALSE){
				$errors = array("succes"=>FALSE, "err_msg"=>validation_errors());
				echo json_encode($errors);

			} else {
				// startDate, endDate, halfday, reason, LWOP
				$userID = $this->session->userdata('lt_logged_ID');
				$input = $this->input->post();
				$data = $input;
				$data["user_id"] = $userID;
				$data["reason"] = str_replace("'", "''", $input["reason"]);

				if (array_key_exists('halfday', $input)) {
					$data["halfday"] = settype($input["halfday"], "integer");// TRUE or FALSE
				} else {
					$data["halfday"] = FALSE;
				}

				if (array_key_exists('LWOP', $input)){
					$data["LWOP"] = settype($input["LWOP"], "integer");// TRUE or FALSE
				} else {
					$data["LWOP"] = FALSE;
				}

				if ($input["action"]=="requestLeave") {
					echo json_encode($this->personal_model->add_request($data));
				} elseif ($input["action"]=="edit") {
					echo json_encode($this->personal_model->update_request($data));
				}
			}

		} else {
			redirect(base_url('/login/'), 'refresh');
		}
	}

	public function cancel_leave()
	{
		if($this->session->userdata('lt_isLogin') == 1){
			$user_id = $this->session->userdata('lt_logged_ID');

			$request_data = $this->input->post();
			$request_data["user_id"] = $user_id;

			echo json_encode($this->personal_model->cancel_request($request_data));
		}else{
			redirect(base_url('/login/'), 'refresh');
		}
	}

	private function status($statusNo) {
		if ($statusNo == 2) return "Accepted";
		if ($statusNo == 1) return "Processing";
		if ($statusNo == 0) return "Declined"; 
	}
    
	public function edit_leave($leave_id=null)
	{
		if($this->session->userdata('lt_isLogin') == 1){
			$user_id = $this->session->userdata('lt_logged_ID');
			$table_id = $this->input->post('table_id');
			echo json_encode($this->personal_model->edit_request($user_id, $table_id));
		}else{
			redirect(base_url('/login/'), 'refresh');
		}
	}
}
