<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adjustment extends CI_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('dashboard_model');
        $this->load->helper('format');
    }

    public function index() {

        $is_Login = $this->session->userdata('lt_isLogin');
        if($is_Login == 1){
            if($this->session->userdata('lt_isAdmin')==1){
                $this->data['is_Login'] = $is_Login;
                $this->data['tableData'] = $this->display_Employees();
                $this->data['content'] = "view_adjustment";
                $this->data['jsscript'] = "view_adjustment_script";
    			$this->data['ajax_REQUESTED'] = $this->input->is_ajax_request();
                $this->load->view('template_dashboard', $this->data);
            }else{
                redirect(base_url('/personal/'), 'refresh');
            }
        }else{
            redirect(base_url('/login/'), 'refresh');
        }
    }

    private function display_Employees() 
    {
        $tmpl = array('table_open' => '<table id="employeeList" class="table table-bordered">');
        $this->table->set_heading('Name', 'Activity');
        $this->table->set_template($tmpl);

        $tableData = $this->user_model->getUsers();
        $i = 1;

        foreach ($tableData as $row) 
        {
            $actions = '<button type="button" id="detail_'. $row['UserID'] .'" class="btn btn-link pop-event"><i class="fa fa-pencil-square-o fa-2"></i> Details</button>';
            $userApprovers = $row["approvers"];
            $this->table->add_row(
                    ucwords(strtolower( $row['LastName'] . ", " . $row['FirstName'] )), 
                    $actions
            );
        }
        return $this->table->generate();
    }

    public function load_view()
    {
        if ($this->session->userdata('lt_isLogin'))
        {
            if ($this->session->userdata('lt_isAdmin'))
            {
                $pop_data = $this->input->post();
                $data['pop_data'] = $pop_data;
                $data['user_id'] = $pop_data['user_id'];

                $response['html_view'] = $this->load->view("inc/inc_Adjustment", $data, TRUE);
                echo json_encode($response);
            }
            else
            {
                redirect(base_url('/personal/'), 'refresh');
            }
        }
        else
        {
            redirect(base_url('/login/'), 'refresh');
        }
    }
}
    