<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signin extends CI_Controller {

	public function index()
	{
		$is_Login = FALSE;
		$this->data['is_Login'] = $is_Login;
		$this->data['content'] = "view_login";
		$this->load->view('template_main', $this->data);
	}
    
}
?>