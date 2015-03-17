<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faq extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function index() 
    {
        $this->data['is_Login'] = $this->session->userdata('isLogin');
        $this->data['page'] = "FAQ";
        $this->data['user_summary'] = $this->user_model->getUser_summary($this->session->userdata('userID'));

        $this->data['content'] = "view_FAQ";
        $this->data['jsscript'] = "view_FAQ_script";
        $this->load->view('template_main', $this->data);
    }
}
?>