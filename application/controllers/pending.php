<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pending extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('approver_model');
    }

    public function index() {
        $is_Login = $this->session->userdata('lt_isLogin');
        if ($is_Login == 1) {
            if ($this->session->userdata('lt_isApprover')==1) {
                $this->data['is_Login'] = $is_Login;
                $this->data['page'] = "pending";
                $user_id = $this->session->userdata('lt_logged_ID');
                $this->data['tableData'] = $this->pendingLeavesTable($user_id);       
                $this->data['content'] = "view_pending";
                $this->data['jsscript'] = "view_pending_script";
                $this->data['ajax_REQUESTED'] = $this->input->is_ajax_request();
                $this->load->view('template_dashboard', $this->data);
            } else {
                redirect(base_url('/personal/'), 'refresh');
            }
        } else {
            redirect(base_url('/login/'), 'refresh');
        }
    }

    // public function pendingLeave($leaveID) {
    //     $user_id = $this->session->userdata('lt_logged_ID');
    //     $pendingLeaves = $this->approver_model->getLeavesToApprove($user_id, $leaveID);
    //     $response = isset($leaveID) && $leaveID != NULL ? $pendingLeaves[0] : $pendingLeaves;
    //     echo json_encode($response);
    //     var_dump($response);
    // }

    public function pending_view() {
        if ($this->session->userdata('lt_isLogin')) {
            if ($this->session->userdata('lt_isApprover')) {
                $user_id = $this->session->userdata('lt_logged_ID');
                $leaveID = $this->input->post('leave_id');
                $data['leaveID'] = $leaveID;
                $userData = $this->approver_model->getLeavesToApprove($user_id, $leaveID);
                foreach ($userData as $row) {
                    $data['StartDate'] = $row['StartDate'];
                    $data['EndDate'] = $row['EndDate'];
                    $data['approver'] = $row['approver'];
                    $data['Purpose'] = $row['Purpose'];
                    $data['Heirarchy'] = $row['Heirarchy'];
                    $data['approverTableID'] = $row['approverTableID'];
                    $data['LWOP'] = $row['LWOP'];
                    $data['comment'] = $row['comment'];
                }
                $response['html_view'] = $this->load->view("inc/inc_UserPendingForm", $data, TRUE);
                echo json_encode($response);
            } else {
                redirect(base_url('/personal/'), 'refresh');
            }
        } else {
            redirect(base_url('/login/'), 'refresh');
        }
    }

    public function action() {     // Accept | decline and comment
        if ($this->session->userdata('lt_isLogin')) {
            if ($this->session->userdata('lt_isApprover')) {
                if ($this->form_validation->run() == FALSE) {
                    $errors = array("succes"=>FALSE, "field"=>"status", "description"=>validation_errors());
                    echo json_encode($errors);    
                } else {
                    $request_data = $this->input->post();
                    if ($request_data['status'] == 2 && $request_data['comment'] == "") {
                        $errors = array("succes" => FALSE, "field"=>"comment", "description"=>"Required Comment field");
                        echo json_encode($errors);
                    } else {
                        $result = $this->approver_model->updateLeaveRequest($request_data);
                        echo json_encode($result);    
                    }  
                }
            } else {
                redirect(base_url('/personal/'), 'refresh');
            }
        } else {
            redirect(base_url('/login/'), 'refresh');
        }
    }

    private function pendingLeavesTable($approverID) {
        $tmpl = array('table_open' => '<table id="leaves" class="table table-bordered" cellspacing="0" width="100%">');
        $this->table->set_heading('#', 'Full Name', 'Date Filed', 'Duration', 'Start Date', 'End Date', 'Reason', 'LWOP', 'Approve');
        $this->table->set_template($tmpl);

        $tableData = $this->approver_model->getLeavesToApprove($approverID);

        $i = 1;
        foreach ($tableData as $row) {
            $actions = "<button type='button' id=" . $row['LeaveTableID'] . " class='btns btn-link pop-event'>Select</button>";

            $this->table->add_row(
                    $i++, 
                    ucwords(strtolower($row['LastName'] . ", " . $row['FirstName'])), 
                    $row['DateFiled'], 
                    $this->getLeaveDuration($row['StartDate'], $row['EndDate'], $row['Halfday']), 
                    date("m/d/y D", strtotime($row['StartDate'])), 
                    date("m/d/y D", strtotime($row['EndDate'])), 
                    $row['Purpose'], 
                    $row['LWOP'] == 0 ? "<i class='fa fa-times'></i>" : "<i class='fa fa-check'></i>", 
                    $actions
            );
        }
        return $this->table->generate();
    }

    private function getLeaveDuration($startDate, $endDate, $Halfday) {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $end->modify('+1 day');
        $interval = $end->diff($start);
        $days = $interval->days;
        $period = new DatePeriod($start, new DateInterval('P1D'), $end);
        $holidays = array(); // array for holidays

        foreach($period as $dt) {
            $curr = $dt->format('D');

            if (in_array($dt->format('Y-m-d'), $holidays)) {
               $days--;
            }

            if ($curr == 'Sat' || $curr == 'Sun') {
                $days--;
            }
        }

        if ($Halfday == 1) {
            return '0.5 Day';
        } else if($days == 1) {
            return $days . " Day";
        } else {
            return $days . " Days";
        }
    }
}
