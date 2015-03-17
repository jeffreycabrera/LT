<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Policies extends CI_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index()
	{
		$this->data['content'] = "view_policies";
		$this->load->view("template_main");
	}
}