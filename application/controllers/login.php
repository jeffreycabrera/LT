<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('login_model');
		$this->load->helper(array('form', 'html', 'url'));
	}

	public function index()	{
		if($this->session->userdata('isLogin') == 1){
			redirect('/personal/', 'refresh');
		}else{
			if ($this->form_validation->run() == FALSE) {
				$this->data['content'] = "view_login";
	        }
	        else {
	        	$uid = $this->input->post('uid');
	        	$password = $this->input->post('password');

	        	$ispass_reset = $this->login_model->isresetpass($uid);
				$login_detail = $this->login_model->login($uid, $password);

				if ($ispass_reset == '1' && $login_detail != '0'){
	                $sess_array = array(
	                    'lt_logged_ID' => $login_detail->UserID,
						'lt_logged_FullName' => $login_detail->FirstName ." ". $login_detail->LastName,
						'lt_isApprover' => $login_detail->IsApprover,
						'lt_isAdmin' => $login_detail->IsAdmin,
						'lt_isLogin' => TRUE			
	                );

					$this->session->set_userdata($sess_array);
					if ($login_detail->IsAdmin==1 || $login_detail->IsApprover==1){
						redirect(base_url('/lt_dashboard/'), 'refresh');
					}else{
						redirect(base_url('/personal/'), 'refresh');
					}

				}elseif ($ispass_reset == '0' && $login_detail != '0') {
					$this->session->set_flashdata('uid', $uid);
					redirect(base_url('/login/reset/'), 'refresh');

				}else{
					$this->data['error_login'] = 'Invalid user ID or password.';
					$this->data['content'] = "view_login";
				}
	        }

	        $this->data['jsscript'] = "view_login_script";
	    	$this->load->view('template_main', $this->data);
		}
		
	}

	public function reset()
	{
		if($this->session->userdata('isLogin') == 1){
			redirect('/personal/', 'refresh');
		}else{
			$uid = $this->session->flashdata('uid');

			if ($this->form_validation->run() == FALSE) {;
				$this->session->set_flashdata('uid', $uid);

				$this->data['content'] = "view_loginreset";
				$this->data['jsscript'] = "view_login_script";
	        	$this->load->view('template_main', $this->data);

			}else{
				$cpass = $this->input->post("cpass");

				$this->login_model->resetpass($cpass, $uid);
				redirect(base_url('/login/'), 'refresh');
			}
		}
	}

	public function logout(){
		$this->session->sess_destroy();
		redirect(base_url('/login/'), 'refresh');
	}
}
?>