<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LT_Dashboard extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {

        if ($this->session->userdata('lt_isLogin')) {
            if ($this->session->userdata('lt_isAdmin') == 1 OR $this->session->userdata('lt_isApprover') == 1) {

                $data['content'] = "view_welcome";
                $data['jsscript'] = "view_login_script";


                $data['ajax_REQUESTED'] = $this->input->is_ajax_request();
                $this->load->view('template_dashboard', $data);

            } else {
                //note: Redirect to error404
                redirect(base_url('/personal/'), 'refresh');//temp redirection.
            }
        } else {
            redirect(base_url('/login/'), 'refresh');
        }
    }

    public function getDashboard() {

        if ($this->session->userdata('lt_isAdmin') == 1) {
            $html = '<hr>
            <ul class="nav nav-sidebar">
                <li><a class="main-link" href="employees">Employees</a></li>
                <li><a class="main-link" href="approvers">Approvers List</a></li>
                <li><a class="main-link" href="settings">Adjustments</a></li>
            </ul>
            <hr>';

            return $html;
        } else {
            
        }
    }
}