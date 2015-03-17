<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Approver extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('approver_model');
    }

    public function index() {
        $is_Login = $this->session->userdata('lt_isLogin');
        if($is_Login == 1){
            if($this->session->userdata('IsApprover')==1){
                $this->data['is_Login'] = $is_Login;
                $this->data['page'] = "approver";
                $user_id = $this->session->userdata('userID');

                $this->data['user_summary'] = $this->user_model->getUsers($user_id);

                $this->data['tableData'] = $this->pendingLeavesTable($user_id);
                $this->data['summaryTable'] = $this->approvedUsersTable($user_id);
                
                $this->data['content'] = "view_approver";
                $this->data['jsscript'] = "view_approver_script";
                $this->load->view('template_main', $this->data);
            }else{
                redirect(base_url('/personal/'), 'refresh');
            }
        }else{
            redirect(base_url('/login/'), 'refresh');
        }
    }

    public function pendingLeave($leaveID) {
        $user_id = $this->session->userdata('userID');
        $pendingLeaves = $this->approver_model->getLeavesToApprove($user_id, $leaveID);
        $response = isset($leaveID) && $leaveID != NULL ? $pendingLeaves[0] : $pendingLeaves;
        echo json_encode($response);
    }

    public function update() {
        if ($this->form_validation->run() == FALSE){
            $errors = array("succes"=>FALSE, "description"=>validation_errors());
            echo json_encode($errors);
            
        }else{
            $request_data = $this->input->post();

            $result = $this->approver_model->updateLeaveRequest($request_data);
            echo json_encode($result);
        }
    }

    private function pendingLeavesTable($approverID) {
        $tmpl = array('table_open' => '<table id="leaves" class="table table-bordered" cellspacing="0" width="100%">');
        $this->table->set_heading('#', 'Full Name', 'Date Filed', 'Start Date', 'End Date', 'Reason', 'LWOP', '');
        $this->table->set_template($tmpl);

        $tableData = $this->approver_model->getLeavesToApprove($approverID);

        $i = 1;
        foreach ($tableData as $row) {
            $actions = "<button type='button' id=" . $row['LeaveTableID'] . " class='btns btn-link pop-event'>Details</button>";

            $this->table->add_row(
                    $i++, 
                    ucwords(strtolower($row['LastName'] . ", " . $row['FirstName'])), 
                    $row['DateFiled'], 
                    $row['StartDate'], 
                    $row['EndDate'], 
                    $row['Purpose'], 
                    $row['LWOP'] == 0 ? "NO" : "YES", 
                    $actions
            );
        }

        return $this->table->generate();
    }

    private function approvedUsersTable($approverID) {
        $tmpl = array('table_open' => '<table id="summaryList" class="table table-bordered" cellspacing="0" width="100%">');
        $this->table->set_heading('#', 'Full Name', 'Total PTO Taken', 'PTO Balance');
        $this->table->set_template($tmpl);
        
        $userData = $this->approver_model->getApprovers_approvee($approverID);

        if($userData){
            $tableData = array('0'=>$this->user_model->getUser_summary($userData[0]['UserID']));

            for($i = 1; $i < count($userData); $i++) {
                $tableData2 = array($i=> $this->user_model->getUser_summary($userData[$i]['UserID']));

                $mergedDate = array_merge($tableData, $tableData2);
                $tableData = $mergedDate;
            }
        }else{
            $tableData = array();
        }

        $i = 1;
        foreach ($tableData as $row) {
            $rowData = $row;

            $this->table->add_row(
                    $i++, 
                    ucwords(strtolower($rowData['LastName'] . ", " . $rowData['FirstName'])), 
                    number_format($rowData['LeavesTaken'],2),
                    number_format($rowData['PTOBalance'],2)
);
        }

        return $this->table->generate();
    }
}

?>
