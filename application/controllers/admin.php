<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('admin_model');
        $this->load->helper('format');
    }

    public function index() 
    {
        $is_Login = $this->session->userdata('isLogin');
        if($is_Login == 1){
            $this->data['is_Login'] = $is_Login;
            $this->data['page'] = "admin";
            $user_id = $this->session->userdata('userID');
            
            $this->data['user_summary'] = $this->user_model->getUsers($user_id);

            $this->data['tableData'] = $this->display();
            $this->data['approversList'] = $this->user_model->getUserApprovers();

            $this->data['content'] = "view_admin";
            $this->data['jsscript'] = "view_admin_script";
            $this->load->view('template_main', $this->data);
        }else{
            redirect(base_url('/login/'), 'refresh');
        }
    }

    public function users($userID = null) {
        $result = $this->user_model->getUsers($userID);
        if (!isset($result["UserID"])){
            echo json_encode(array("succes"=>FALSE, "description"=>"Unexpected Error!"));
        }else{
            echo json_encode($this->user_model->getUsers($userID));
        }
    }

    public function adduser() {
        if($this->session->userdata('isLogin') == 1){
            if ($this->form_validation->run('admin_add_edit') == FALSE){
                $errors = array("succes"=>FALSE, "err_msg"=>validation_errors());
                echo json_encode($errors);
            }else{
                $user = $this->input->post();
                $userID = userID_generator($user["firstName"], $user["middleName"], $user["lastName"]);
                $password = substr(str_shuffle(sha1(microtime())), 0, 10);

                echo json_encode($this->admin_model->addUser($user, $userID, $password));
            }
        }else{
            redirect(base_url('/login/'), 'refresh');
        }
    }

    public function edit() {
        if($this->session->userdata('isLogin') == 1){
            if ($this->form_validation->run('admin_add_edit') == FALSE){
                $errors = array("succes"=>FALSE, "err_msg"=>validation_errors());
                echo json_encode($errors);
            }else{
                $user = $this->input->post();
                $userID = $user["userID"];



                $this->admin_model->editUserDetails($user, $userID);
                $this->admin_model->setUserApprovers($user["approvers"], $userID);
            }
        }else{
            redirect(base_url('/login/'), 'refresh');
        }
    }

    public function delete() {
        $this->admin_model->deleteUser($_POST['ID']);
    }

    private function send_email($emailto, $password, $username, $ln, $fn) {
        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'mail.iainnovations.ph',
            'smtp_port' => 587,
            'smtp_user' => 'leave.admin@iainnovations.ph',
            'smtp_pass' => 'p@ssw0rd12345',
            'smtp_timeout' => '4',
            'mailtype' => 'html',
            'charset' => 'iso-8859-1',
        );
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");

        $this->email->from('leave.admin@iainnovations.ph', 'IA Leave Administrator');
        $this->email->to($emailto);
        $this->email->subject('Your IA Leave Tracker Login Details');
        $this->email->message($this->message_body($fn, $ln, $username, $password));
        return $this->email->send();
    }
    
    /**
     * Creates HTML Table with the TableData
     * @return String HTML String of the generated HTML Table
     */
    private function display() {
        $tmpl = array('table_open' => '<table border="1" id=leaves class="table table-bordered" cellspacing="0" width="100%">');
        $this->table->set_heading('#', 'Name', 'Number of PTO', 'Approver 1', 'Approver 2', 'Actions');
        $this->table->set_template($tmpl);

        $tableData = $this->user_model->getUsers();
        $i = 1;

        foreach ($tableData as $row) {
            $actions = "<button type='button' id=" . $row['UserID'] . " class='btns btn-link'>Edit</button>";
            $userApprovers = $row["approvers"];
            $this->table->add_row(
                    $i++,
                    ucwords(strtolower( $row['LastName'] . ", " . $row['FirstName'] )), 
                    $row['PTO'], 
                    (array_key_exists(1, $userApprovers) ? $userApprovers[1]["ApproverName"] : "N/A"), 
                    (array_key_exists(2, $userApprovers) ? $userApprovers[2]["ApproverName"] : "N/A"), 
                    $actions
            );
        }
        return $this->table->generate();
    }

    //CUSTOM CALLBACK FOR FORM_ERROR
        function checkAgainstUserID($approvers) {
            $approver1 = $approvers[1]["approverID"];
            $approver2 = $approvers[2]["approverID"];
            $userID = $this->input->post("userID");

            if ($userID == $approver1 || $userID == $approver2) {
                $this->form_validation->set_message('checkAgainstUserID', 'Approver cannot be the user itself');
                return false;
            }
            return true;
        }
        function checkApprovers($approvers) {
            $approver1 = $approvers[1]["approverID"];
            $approver2 = $approvers[2]["approverID"];
            if (($approver1 == $approver2) && strlen($approver1) > 0) {
                $this->form_validation->set_message('checkApprovers', 'Duplicate approvers is not allowed.');
                return false;
            }
            return true;
        }

    /**
     * Construct the HTML body of email's body
     * @param type $fn First Name of Recipient
     * @param type $ln Last Name of Recipient
     * @param type $userid UserID of Recipient
     * @param type $userpassword 
     * @return String HTML String of Email Body
     */
    private function message_body($fn, $ln, $userid, $userpassword) {
        $body = "Hi $fn  $ln,
        <br/><br/>
          Good day!<br/><br/>
          Below are your IA Leave Tracker login details:
          <br/><br/>
          User ID: $userid
          <br/>
          Password: $userpassword    
        <br/><br/>
        Sincerely Yours,
        <br/>IA Leave Administrator ";

        return $body;
    }

}

?>