<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start(); 

class IAHome extends CI_Controller {

 function __construct()
 {
   parent::__construct();
	$this->load->helper('url');
	$this->load->library(array('form_validation','session'));
 }

 function index()
 {
   if($this->session->userdata('logged_in'))
   {
     $session_data = $this->session->userdata('logged_in');
	 $data['uid'] = $session_data['uid'];
     $data['fname'] = $session_data['fname'];
	 $data['isadmin'] = $session_data['isadmin'];
	 $data['isapprover'] = $session_data['isapprover'];
     $this->load->view('iahome_view', $data);
   }
   else
   {
     redirect('login', 'refresh');
   }
 }

 function logout()
 {
   $this->session->unset_userdata('logged_in');
   session_destroy();
   redirect('ialogin', 'refresh');
 }

}

?>