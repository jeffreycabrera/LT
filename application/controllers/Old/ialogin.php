<?php 

	if (!defined('BASEPATH')) exit('No direct script access allowed');

	class IALogin extends CI_Controller{
	
		public function __construct()
		{
			parent::__construct();
			$this->load->model('ialogin_model','login',TRUE);	
			$this->load->helper(array('form', 'html', 'url'));
			$this->load->library(array('form_validation', 'session'));
		}
		
		//Main page of log in page
		public function index() {
			$this->load->view('login/ialogin_view');
    	}
		
		public function verify() {
	        $this->form_validation->set_rules('uid', 'UserID', 'trim|required|xss_clean');
	        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_db');    
			$uid = $this->input->post('uid');
			if ($this->form_validation->run() == FALSE) {
	            $this->load->view('login/ialogin_view');
	        } 
			else {
				$this->session->set_flashdata('uid', $uid);
				redirect(base_url('ialogin/isresetpass'), 'refresh');
	        }   
    	}
		
		public function check_db($password) {
	        $uid = $this->input->post('uid');
			
	        $result = $this->login->login($uid, $password);
			
	        if($result) {
	            $sess_array = array();
	            foreach($result as $row) {
	                //Create the session
	                $sess_array = array(
	                    'uid'  => $row->userid,
						'fname'  => $row->firstname,
						'isapprover'  => $row->isapprover,
						'isadmin'  => $row->isadmin			
	                );
					
	                $this->session->set_userdata('logged_in', $sess_array);
					
	            }
	            return true;
	        } else {
	            $this->form_validation->set_message('check_db', 'Invalid user ID or password.');
	            return false;
	        }
    	}
		
		public function isresetpass() {
			$uid = $this->session->flashdata('uid');
			
			$this->load->model('ialogin_model');
			$result = $this->ialogin_model->isresetpass($uid);
			
			foreach($result as $row){
				$array = array(
					'isresetpassword' => $row->isresetpassword
				);
				$isresetpass = $array['isresetpassword'];
			}
						
			if ($isresetpass == '1'){
				redirect(base_url('iahome/'), 'refresh');
			} else {
				$this->session->set_flashdata('uid', $uid);
				redirect(base_url('ialogin/resetpass/'), 'refresh');
			}
		}
		
		public function resetpass() {
			$uid = $this->session->flashdata('uid');
			$this->session->set_flashdata('uid', $uid);
			$this->load->view('iaresetpass_view');
		}
		
		public function verifycpass(){
			$uid = $this->session->flashdata('uid');
			$this->form_validation->set_rules('npass', 'New Password', 'trim|required|xss_clean|max_length[8]|min_length[6]|alpha_numeric');
	        $this->form_validation->set_rules('cpass', 'Confirm Password', 'trim|required|xss_clean|matches[npass]|max_length[8]|min_length[6]|alpha_numeric'); 
			
			if ($this->form_validation->run() == FALSE) {
	            $this->load->view('iaresetpass_view');
	        } 
			else {
				$cpass = $this->input->post('cpass');
				$this->load->model('ialogin_model');
				$result = $this->ialogin_model->resetpass($uid, $cpass);
				echo '<script>alert("You have successfully reset your password. Welcome to IA Leave Tracker!");</script>';
	            redirect(base_url('iahome/'), 'refresh');
	        }   

		}
	}
?>