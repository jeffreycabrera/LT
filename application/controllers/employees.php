<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employees extends CI_Controller {
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('dashboard_model');
        $this->load->helper('format');
    }

    /**
     * Creates HTML Table with the TableData
     * @return String HTML String of the generated HTML Table
     */
    private function display_Employees() 
    {
        $tmpl = array('table_open' => '<table id="employeeList" class="table table-bordered">');
        $this->table->set_heading('Name', 'Number of PTO', 'Approver 1', 'Approver 2', 'Actions');
        $this->table->set_template($tmpl);

        $tableData = $this->user_model->getUsers();
        $i = 1;

        foreach ($tableData as $row) 
        {
            $actions = '<button type="button" id="edit_'. $row['UserID'] .'" class="btn btn-primary pop-event"><i class="fa fa-pencil-square-o fa-2"></i> edit</button>
                        <button type="button" id="delete-user_'. $row['UserID'] .'" class="btn btn-danger pop-event"><i class="fa fa-trash fa-2"></i> delete</button>';
            $userApprovers = $row["approvers"];
            $this->table->add_row(
                    ucwords(strtolower( $row['LastName'] . ", " . $row['FirstName'] )), 
                    $row['PTO'], 
                    (array_key_exists(1, $userApprovers) ? ucwords(strtolower($userApprovers[1]["ApproverName"])) : "N/A"), 
                    (array_key_exists(2, $userApprovers) ? ucwords(strtolower($userApprovers[2]["ApproverName"])) : "N/A"), 
                    $actions
            );
        }
        return $this->table->generate();
    }

    public function index() 
    {
        if ($this->session->userdata('lt_isLogin'))
        {
            if ($this->session->userdata('lt_isAdmin'))
            {
                $this->data['tableData'] = $this->display_Employees();
                $this->data['pageTitle'] = "Employees";
                $this->data['buttonValue'] = "User";
                $this->data['content'] = "view_employees";
                $this->data['jsscript'] = "view_employees_script";

                $this->data['ajax_REQUESTED'] = $this->input->is_ajax_request();
                $this->load->view('template_dashboard', $this->data);
            }
            else
            {
                //note: Redirect to error404
                redirect(base_url('/lt_dashboard/'), 'refresh');//temp redirection.
            }
        }
        else
        {
            redirect(base_url('/login/'), 'refresh');
        }
    }

    public function load_view()
    {
        if ($this->session->userdata('lt_isLogin'))
        {
            if ($this->session->userdata('lt_isAdmin'))
            {
                $pop_data = $this->input->post();
                $data['action'] = array('action' => $pop_data["action"]);

                $userApproversList = $this->user_model->getALLApprovers();

                $approversList = array("0"=>"--Select Approver--");

                for ($i = 0; $i < count($userApproversList); $i++) 
                {
                    $approver = $userApproversList[$i];
                    $approversList[$approver["ApproverID"]] = $approver["ApproverName"];
                }

                if (isset($pop_data["user_id"]))
                {
                    unset($approversList[$pop_data["user_id"]]);

                    $data['userData'] = $this->user_model->getUsers($pop_data["user_id"]);
                    $data['resetbtn'] = array(
                        'id'=>'reset_reset',
                        'class'=>'btn btn-link',
                        'onclick'=>'resetPassword(\'resetPassword\')',
                        'type'=>'button', 
                        'content'=>'Reset Password');
                }
                else
                {
                    $data['userData'] = array();
                }

                $data['approversList'] = $approversList;

                $response['html_view'] = $this->load->view("inc/inc_UserForm", $data, TRUE);
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

    public function delete_view()
    {
        if ($this->session->userdata('lt_isLogin'))
        {
            if ($this->session->userdata('lt_isAdmin'))
            {
                $pop_data = $this->input->post();
                $data['action'] = array('action' => $pop_data["action"]);
                $data['listType'] = 'User';

                if (isset($pop_data["user_id"]))
                {
                    $data['userData'] = $this->user_model->getUsers($pop_data["user_id"]);
                }

                $response['html_view'] = $this->load->view("inc/inc_UserDelete", $data, TRUE);
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

    public function user_detailManager()
    {
        if ($this->session->userdata('lt_isLogin'))
        {
            if ($this->session->userdata('lt_isAdmin'))
            {
                if ($this->form_validation->run('admin_add_edit') == FALSE)
                {
                    $errors = array("succes"=>FALSE, "title"=>"VALIDATION ERROR!", "description"=>validation_errors());
                    echo json_encode($errors);
                    
                }
                else
                {
                    $user_data = $this->input->post();
                    if (!array_key_exists('isApprover', $user_data))
                    {
                        $user_data['isApprover'] = FALSE;
                    }
                    
                    $isExist = $this->dashboard_model->check_userExistance($user_data);
                    if ($isExist['isExist'])
                    {
                        $user_data['hidUserID'] = $isExist['hidUserID'];
                    }

                    echo ($user_data['action']=='edit' || $isExist['isExist'])? 
                        json_encode($this->dashboard_model->update_user($user_data)) : 
                        json_encode($this->dashboard_model->create_user($user_data)) ;
                }
            }
            else
            {
                redirect(base_url('/employees/'), 'refresh');
            }
        }
        else
        {
            redirect(base_url('/login/'), 'refresh');
        }
    }

    public function delete_user() {
        if ($this->session->userdata('lt_isLogin'))
        {
            if ($this->session->userdata('lt_isAdmin'))
            {
                $user_data = $this->input->post();
                echo json_encode($this->dashboard_model->delete_user($user_data['hidUserID']));
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