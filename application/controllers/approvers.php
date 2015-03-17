<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Approvers extends CI_Controller {
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('approvers_model');
    }

    /**
     * Creates HTML Table with the TableData
     * @return String HTML String of the generated HTML Table
     */
    private function display_Approvers() {
        $tmpl = array('table_open' => '<table id="approversList" class="table table-bordered">');
        $this->table->set_heading('Name', 'Heirarchy', 'Actions');
        $this->table->set_template($tmpl);

        $tableData = $this->user_model->getALLApprovers();
        $i = 1;

        foreach ($tableData as $row) {
            $actions = '<button type="button" id="delete-approver_'. $row['ApproverID'] .'" class="btn btn-danger pop-event"><i class="fa fa-trash fa-2"></i> remove</button>';

            $this->table->add_row(
                $row["ApproverName"], 
                $row["Heirarchy"], 
                $actions
            );
        }
        return $this->table->generate();
    }

    public function index() {
        if ($this->session->userdata('lt_isLogin')) {
            if ($this->session->userdata('lt_isAdmin')) {
                $this->data['tableData'] = $this->display_Approvers();
                $this->data['pageTitle'] = "Approvers";
                $this->data['buttonValue'] = "Approver";
                $this->data['content'] = "view_approvers";
                $this->data['jsscript'] = "view_approvers_script";
                $this->data['ajax_REQUESTED'] = $this->input->is_ajax_request();
                $this->load->view('template_dashboard', $this->data);
            } else {
                //note: Redirect to error404
                redirect(base_url('/lt_dashboard/'), 'refresh');//temp redirection.
            }
        } else {
            redirect(base_url('/login/'), 'refresh');
        }
    }

    public function load_view() {
        if ($this->session->userdata('lt_isLogin')) {
            if ($this->session->userdata('lt_isAdmin')) {
                $pop_data = $this->input->post();
                $data['action'] = array('action' => $pop_data["action"]);

                $userApproversList = $this->user_model->getALLApprovers();

                $approversList = array("0"=>"--Select Approver--");

                for ($i = 0; $i < count($userApproversList); $i++) {
                    $approver = $userApproversList[$i];
                    $approversList[$approver["ApproverID"]] = $approver["ApproverName"];
                }

                if (isset($pop_data["user_id"])) {
                    unset($approversList[$pop_data["user_id"]]);

                    $data['userData'] = $this->user_model->getUsers($pop_data["user_id"]);
                    $data['resetbtn'] = array(
                        'id'=>'reset_reset',
                        'class'=>'btn btn-link',
                        'onclick'=>'resetPassword(\'resetPassword\')',
                        'type'=>'button', 
                        'content'=>'Reset Password');
                } else {
                    $data['userData'] = array();
                }

                $data['approversList'] = $approversList;

                $response['html_view'] = $this->load->view("inc/inc_ApproverForm", $data, TRUE);
                echo json_encode($response);
            } else {
                redirect(base_url('/personal/'), 'refresh');
            }
        } else {
            redirect(base_url('/login/'), 'refresh');
        }
    }

    public function delete_view() {
        if ($this->session->userdata('lt_isLogin')) {
            if ($this->session->userdata('lt_isAdmin')) {
                $data['data'] = $pop_data = $this->input->post();
                $data['action'] = array('action' => $pop_data["action"]);
                $data['listType'] = 'Approvers';

                if (isset($pop_data["user_id"])) {
                    $data['userData'] = $this->user_model->getUsers($pop_data["user_id"]);
                }

                $response['html_view'] = $this->load->view("inc/inc_ApproverDelete", $data, TRUE);
                echo json_encode($response);
            } else {
                redirect(base_url('/personal/'), 'refresh');
            }
        } else {
            redirect(base_url('/login/'), 'refresh');
        }
    }

    public function delete_approver() {
        if ($this->session->userdata('lt_isLogin')) {
            if ($this->session->userdata('lt_isAdmin')) {
                $user_data = $this->input->post();
                echo json_encode($this->approvers_model->delete_approver($user_data['hidUserID']));
            } else {
                redirect(base_url('/personal/'), 'refresh');
            }
        } else {
            redirect(base_url('/login/'), 'refresh');
        }
    }

    public function autoCompleteAjax() {
        if ($this->session->userdata('lt_isLogin')) {
            if ($this->session->userdata('lt_isAdmin')) {
                $this->load->model('approver_model');
                $result = $this->approver_model->addApproverAutoComplete();
                $list = array();
                foreach ($result as $row) {
                    $list[] = array('id' => $row['UserID'], 'name' => $row['name'], 'Heirarchy' => $row['Heirarchy']);
                }
                echo json_encode($list);
            } else {
                redirect(base_url('/personal/'), 'refresh');
            }
        } else {
            redirect(base_url('/login/'), 'refresh');
        }
    }

    public function add_approver() {
        if ($this->session->userdata('lt_isLogin')) {
            if ($this->session->userdata('lt_isAdmin')) {
                $UserID = $this->input->post('hidUserID');
                if ($UserID == "") {
                    $error = array('success'=> false, 'msg' => 'empty userid');
                    echo json_encode($error);
                } else {
                    $result = $this->approvers_model->add_approver($UserID);
                    echo json_encode($result);    
                }
            } else {
                redirect(base_url('/personal/'), 'refresh');
            }
            
        } else {
            redirect(base_url('/login/'), 'refresh');
        }
        
    }
}