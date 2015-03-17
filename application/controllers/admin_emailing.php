<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_emailing extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('dashboard_model');

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
    }

    public function send_email($data, $header) {
        // $t = $this->session->flashdata('new');
        $this->data['newUser_data'] = $data;
        $this->data['header'] = $header;
        $this->email->set_newline("\r\n");
        $this->email->from('leave.admin@iainnovations.ph', 'IA Leave Administrator');
        $this->email->to($data['mailto']);
        $this->email->subject('Your IA Leave Tracker Login Details');

        $response['html_view'] = $this->load->view("inc/email_temp", $this->data, TRUE);

        $this->email->message($response['html_view']);
        if (!$this->email->send())
        {
            echo json_encode(array('success'=>false));
            return false;
        }
        echo json_encode(array('success'=>true));
    }

    public function resetPassword(){
        $user_data = $this->input->post();
        $userID = strtolower($user_data['hidUserID']);
        $firstName = ucwords(strtolower($user_data['firstName']));
        $emailAddress = $user_data['emailAddress'];
        $temp_Passcode = substr(str_shuffle(sha1(microtime())), 0, 10);

        $isSent = $this->dashboard_model->resetPassword($userID, $temp_Passcode);
        if($isSent){
            $data = array('userID'=>$userID, 'FirstName'=>$firstName, 'temp_Passcode'=>$temp_Passcode, 'mailto'=>$emailAddress);
            // $this->session->set_flashdata('newUser_data', $newUser_data);
            $header = 'user';
            $this->send_email($data, $header);
            return;
        }
        echo json_encode(array('success'=>false));
    }

    public function leaveApplication(){
        $user_data = $this->input->post();
        $approverName = ucwords(strtolower($user_data['approverName']));
        $approverEmail = $user_data['approverEmail'];
        $temp_email = 'jeffrey.cabrera@iainnovations.ph'; // for testing only
        $fullName = $user_data['fullName'];
        $header = 'leave';
        $data = array('fullName'=> $fullName, 'approverName'=> $approverName, 'mailto'=>$temp_email);
        $this->send_email($data, $header);
        return;
    }
}
?>