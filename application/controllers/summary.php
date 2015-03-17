<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Summary extends CI_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('approver_model');
    }

    public function index() {
        // $this->ExcelExport();
        $is_Login = $this->session->userdata('lt_isLogin');
        if($is_Login == 1){
            if($this->session->userdata('lt_isApprover')==1){
                $this->data['is_Login'] = $is_Login;
                $this->data['page'] = "approver";
                $user_id = $this->session->userdata('lt_logged_ID');

                $this->data['tableData'] = $this->pendingLeavesTable($user_id);
                $this->data['user_summary'] = $this->user_model->getUsers($user_id);

                $this->data['summaryTable'] = $this->approvedUsersTable($user_id);
               
                $this->data['content'] = "view_summary";
                $this->data['jsscript'] = "view_summary_script";
    			$this->data['ajax_REQUESTED'] = $this->input->is_ajax_request();
                $this->load->view('template_dashboard', $this->data);
            }else{
                redirect(base_url('/personal/'), 'refresh');
            }
        }else{
            redirect(base_url('/login/'), 'refresh');
        }
    }

    private function pendingLeavesTable($approverID) {
        $tmpl = array('table_open' => '<table id="summaryLeaves" class="table table-bordered" cellspacing="0" width="100%">');
        $this->table->set_heading('#', 'Full Name', 'Date Filed', 'Duration', 'Start Date', 'End Date', 'Reason', 'LWOP', 'Action');
        $this->table->set_template($tmpl);

        $tableData = $this->approver_model->getApproved($approverID);

        $i = 1;
        foreach ($tableData as $row) {
            // $actions = "<button type='button' id=" . $row['LeaveTableID'] . " class='btns btn-link pop-event'>Select</button>";

            $this->table->add_row(
                    $i++, 
                    ucwords(strtolower($row['LastName'] . ", " . $row['FirstName'])), 
                    $row['DateFiled'], 
                    $this->getLeaveDuration($row['StartDate'], $row['EndDate'], $row['Halfday']), 
                    date("m/d/y D", strtotime($row['StartDate'])), 
                    date("m/d/y D", strtotime($row['EndDate'])), 
                    $row['Purpose'], 
                    $row['LWOP'] == 0 ? "<i class='fa fa-times'></i>" : "<i class='fa fa-check'></i>", 
                    $this->approvedOrDeclined($row['status'])
            );
        }
        return $this->table->generate();
    }

    private function approvedOrDeclined($status) {
        if ($status == 0) {
            return 'Declined';
        } else if ($status == 2) {
            return 'Approved';
        } else {
            return 'Processing';
        }
    }

    private function approvedUsersTable($approverID) {
        $tmpl = array('table_open' => '<table id="approvedLeaves" class="table table-bordered cellspacing="0" width="100%">');
        $this->table->set_heading('#', 'Full Name', '# of PTO','# Requested', 'Total PTO Taken', '# of LWOP', 'PTO Balance');
        //Full Name, # of PTO, # Requested, Total PTO Taken, # of LWP, PTO Balance
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
                    number_format($rowData['PTO'],2),
                    number_format($rowData['LeavesRequest'],2),
                    number_format($rowData['LeavesTaken'],2),
                    number_format($rowData['LWOP'],2),
                    number_format($rowData['PTOBalance'],2)
            );
        }

        return $this->table->generate();
    }

    private function approvedUsersTableForExcel($approverID) {
        $tmpl = array('table_open' => '<table id="approvedLeaves" class="table table-bordered cellspacing="0" width="100%">');
        $this->table->set_heading('#', 'Full Name', '# of PTO','# Requested', 'Total PTO Taken', '# of LWOP', 'PTO Balance');
        //Full Name, # of PTO, # Requested, Total PTO Taken, # of LWP, PTO Balance
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
        return $tableData;
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

    public function ExcelExport() {

        $is_Login = $this->session->userdata('lt_isLogin');
        if($is_Login == 1){
            if($this->session->userdata('lt_isApprover')==1){

                $user_id = $this->session->userdata('lt_logged_ID');
                $approvedData = $this->approver_model->getApproved($user_id);
                $ptoSummary = $this->approvedUsersTableForExcel($user_id);

                $this->load->library('excel');
            
                $this->excel->setActiveSheetIndex(0);
            
                $this->excel->getActiveSheet()->setTitle('PTO Summary');
         //#    Full Name   # of PTO    # Requested Total PTO Taken # of LWOP   PTO Balance
                $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(-1);
                $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
               
                $this->excel->getActiveSheet()->getStyle('A2:H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->setCellValue('A1', 'PTO Summary');
             
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
               
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A2:H2')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' =>'#66FFCC')));
            
                $this->excel->getActiveSheet()->mergeCells('A1:H1');
          
                $this->excel->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->setCellValue('A2', '#');
                $this->excel->getActiveSheet()->setCellValue('B2', 'Last Name');
                $this->excel->getActiveSheet()->setCellValue('C2', 'First Name');
                $this->excel->getActiveSheet()->setCellValue('D2', '# of PTO');
                $this->excel->getActiveSheet()->setCellValue('E2', '# Requested');
                $this->excel->getActiveSheet()->setCellValue('F2', 'Total PTO Taken');
                $this->excel->getActiveSheet()->setCellValue('G2', '# of LWOP');
                $this->excel->getActiveSheet()->setCellValue('H2', 'PTO Balance');
                $x = 3;
                for ($i=0; $i < count($ptoSummary); $i++) { 
          
                    $this->excel->getActiveSheet()->getStyle('G'.$x)->getAlignment()->setWrapText(true);

                    $this->excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

                    $this->excel->getActiveSheet()->getStyle('A'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('B'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('C'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('D'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('E'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('F'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('G'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('H'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                    $this->excel->getActiveSheet()->setCellValue('A'.$x, $i+1);
                    $this->excel->getActiveSheet()->setCellValue('B'.$x, $ptoSummary[$i]['LastName']);
                    $this->excel->getActiveSheet()->setCellValue('C'.$x, $ptoSummary[$i]['FirstName']);
                    $this->excel->getActiveSheet()->setCellValue('D'.$x, $ptoSummary[$i]['PTO']);
                    $this->excel->getActiveSheet()->setCellValue('E'.$x, $ptoSummary[$i]['LeavesRequest']);
                    $this->excel->getActiveSheet()->setCellValue('F'.$x, $ptoSummary[$i]['LeavesTaken']);
                    $this->excel->getActiveSheet()->setCellValue('G'.$x, $ptoSummary[$i]['LWOP']);
                    $this->excel->getActiveSheet()->setCellValue('H'.$x, $ptoSummary[$i]['PTOBalance']);
                $x ++;
                }

                $this->excel->createSheet();
                //sheet 2
                $this->excel->setActiveSheetIndex(1);

                $this->excel->getActiveSheet()->setTitle('Detailed Summary');
         
                $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(-1);
                $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(50);
                $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
                $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
               
                $this->excel->getActiveSheet()->getStyle('A2:J2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->setCellValue('A1', 'Detailed Summary');
             
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
               
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A2:J2')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' =>'#66FFCC')));
            
                $this->excel->getActiveSheet()->mergeCells('A1:J1');
          
                $this->excel->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->setCellValue('A2', '#');
                $this->excel->getActiveSheet()->setCellValue('B2', 'Last Name');
                $this->excel->getActiveSheet()->setCellValue('C2', 'First Name');
                $this->excel->getActiveSheet()->setCellValue('D2', 'Date Filed');
                $this->excel->getActiveSheet()->setCellValue('E2', 'Duration');
                $this->excel->getActiveSheet()->setCellValue('F2', 'Start Date');
                $this->excel->getActiveSheet()->setCellValue('G2', 'End Date');
                $this->excel->getActiveSheet()->setCellValue('H2', 'Purpose');
                $this->excel->getActiveSheet()->setCellValue('I2', 'LWOP');
                $this->excel->getActiveSheet()->setCellValue('J2', 'Status');
                $x = 3;
                for ($i=0; $i < count($approvedData); $i++) { 
          
                    $this->excel->getActiveSheet()->getStyle('H'.$x)->getAlignment()->setWrapText(true);

                    $this->excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

                    $this->excel->getActiveSheet()->getStyle('A'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('B'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('C'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('D'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('E'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('F'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('G'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('H'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('I'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('J'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                   

                    $this->excel->getActiveSheet()->setCellValue('A'.$x, $i+1);
                    $this->excel->getActiveSheet()->setCellValue('B'.$x, $approvedData[$i]['LastName']);
                    $this->excel->getActiveSheet()->setCellValue('C'.$x, $approvedData[$i]['FirstName']);
                    $this->excel->getActiveSheet()->setCellValue('D'.$x, $approvedData[$i]['DateFiled']);
                    $this->excel->getActiveSheet()->setCellValue('E'.$x, $this->getLeaveDuration($approvedData[$i]['StartDate'], $approvedData[$i]['EndDate'], $approvedData[$i]['Halfday']));
                    $this->excel->getActiveSheet()->setCellValue('F'.$x, $approvedData[$i]['StartDate']);
                    $this->excel->getActiveSheet()->setCellValue('G'.$x, $approvedData[$i]['EndDate']);
                    $this->excel->getActiveSheet()->setCellValue('H'.$x, $approvedData[$i]['Purpose']);
                    $this->excel->getActiveSheet()->setCellValue('I'.$x, $approvedData[$i]['LWOP'] == 0 ? 'No' : 'Yes');
                    $this->excel->getActiveSheet()->setCellValue('J'.$x, $this->approvedOrDeclined($approvedData[$i]['status']));
                    
                $x ++;
                }

                // $this->excel->createSheet();

                $dateNow = date("d.m.Y");
                $filename="PTO Summary Extract[".$dateNow."].xls"; //save our workbook as this file name
                header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                header('Cache-Control: max-age=0'); //no cache
                            
                //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
                //if you want to save it as .XLSX Excel 2007 format
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
                //force user to download the Excel file without writing it to server's HD
                $objWriter->save('php://output');
            }else{
                redirect(base_url('/lt_dashboard/'), 'refresh');
            }
        }else{
            redirect(base_url('/login/'), 'refresh');
        }
    }

    
}