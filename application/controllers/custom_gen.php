<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Custom_gen extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('custom_gen_model');
		$this->load->helper(array('form', 'html', 'url'));
	}

	public function index()	{
		if($this->session->userdata('isLogin') == 1){
			redirect('/personal/', 'refresh');
		}else{
			getAllLeaves
			
			$this->data['content'] = "view_login";
	        $this->data['jsscript'] = "view_login_script";
	    	$this->load->view('template_main', $this->data);
		}
		
	}
}
?>