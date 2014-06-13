<?php

class Con_pro_daily_absent_report extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('mod_set_employee_info_detail');
        $this->load->model('mod_pro_daily_first_half_attn_log');
        $this->load->model('mod_monthly_wages_detail');
        $this->load->model('mod_access_log_raw');
        $this->load->model('mod_access_log');
        $this->load->model('mod_buil_sec_other');
        $this->load->model('mod_pro_attn_mismatch_report');
        $this->load->model('mod_set_work_hour_breakdown');
        $this->load->model('mod_leave_detail');
    }

    public function index() {
        date_default_timezone_set('Asia/Dacca');
        $StartDate1 = date('Y-m-d', now()); 
        $StartDate = date('Y-m-d', strtotime('-1 day', strtotime($StartDate1)));
        $BuildingName = $this->session->userdata('BuildingName');
        $data['floorInfo'] = $this->mod_buil_sec_other->getFloor($BuildingName);
        $Floor = $this->session->userdata('Floor');
        $data['floor'] = $Floor;
        $Department = $this->session->userdata('Department');
        if ($this->session->userdata('Role') == 'Admin') {
            $employee_details_for_absent_report = $this->mod_pro_attn_mismatch_report->employee_information_for_absent_report_admin($BuildingName, $StartDate);
        } else {
            $employee_details_for_absent_report = $this->mod_pro_attn_mismatch_report->employee_information_for_absent_report_operators($BuildingName, $Floor, $StartDate);
        }
        //714                
        //$attendance_list = $this->mod_access_log->GetDateSpecificCardNo($StartDate);

//        $limit1 = count($employee_details) - 1;
//        $absent_employee_list = array();
//        foreach ($employee_details as $an_employee_details) {
//            $absent_employee['CardNo'] = $an_employee_details->CardNo;
//            $absent_employee['Name'] = $an_employee_details->Name;
//            $absent_employee['BuildingName'] = $an_employee_details->BuildingName;
//            $absent_employee['Floor'] = $an_employee_details->Floor;
//            $absent_employee['Department'] = $an_employee_details->Department;
//            $absent_employee['Line'] = $an_employee_details->Line;
//            if (!($this->CheckAttendance($an_employee_details->CardNo, $attendance_list))) {
//                array_push($absent_employee_list, $absent_employee);
//            }
//        }
        $data['tbl_leave_category'] = $this->mod_leave_detail->get_leave_type_names();
        $data['tbl_work_hour_breakdown'] = $this->mod_set_work_hour_breakdown->view1();
        $data['showDate'] = date('d-m-Y', strtotime('-1 day', now()));
        $data['tbl_absent_report'] = $employee_details_for_absent_report;
        $data['container'] = 'temp/daily_absent_report/daily_absent_report_ui';
        $this->load->view('main_page', $data);
    }

    public function Search() {
        $mydate = $this->input->post('Date');
//        echo $mydate.'<br/>';
        $StartDate = date('Y-m-d', strtotime($mydate));
//        echo $StartDate.'<br/>';
//        exit();
        $BuildingName = $this->session->userdata('BuildingName');
        $data['floorInfo'] = $this->mod_buil_sec_other->getFloor($BuildingName);
        $Floor = $this->session->userdata('Floor');
        $data['floor'] = $Floor;
        $Department = $this->session->userdata('Department');
        if ($this->session->userdata('Role') == 'Admin') {
            $employee_details = $this->mod_pro_attn_mismatch_report->employee_information_for_absent_report_admin($BuildingName, $StartDate);
        } else {
            $employee_details = $this->mod_pro_attn_mismatch_report->employee_information_for_absent_report_operators($BuildingName, $Floor, $StartDate);
        }

//        $attendance_list = $this->mod_access_log->GetDateSpecificCardNo($StartDate);
//
//        $limit1 = count($employee_details) - 1;
//        $absent_employee_list = array();
//        foreach ($employee_details as $an_employee_details) {
//            $absent_employee['CardNo'] = $an_employee_details->CardNo;
//            $absent_employee['Name'] = $an_employee_details->Name;
//            $absent_employee['BuildingName'] = $an_employee_details->BuildingName;
//            $absent_employee['Floor'] = $an_employee_details->Floor;
//            $absent_employee['Department'] = $an_employee_details->Department;
//            $absent_employee['Line'] = $an_employee_details->Line;
//            if (!($this->CheckAttendance($an_employee_details->CardNo, $attendance_list))) {
//                array_push($absent_employee_list, $absent_employee);
//            }
//        }

        $data['showDate'] = $StartDate;
        $data['tbl_leave_category'] = $this->mod_leave_detail->get_leave_type_names();
        $data['tbl_work_hour_breakdown'] = $this->mod_set_work_hour_breakdown->view1();
        $data['tbl_absent_report'] = $employee_details_for_absent_report;
        $data['container'] = 'temp/daily_absent_report/daily_absent_report_ui';
        $this->load->view('main_page', $data);
    }
    
    public function insert_into_access_log_for_mismatch() {
        $CardNo = $this->input->post('CardNo');
        $Time = date('H:i:s', strtotime('-6 hours', strtotime($this->input->post('Time'))));
        $Date = date('Y-m-d', strtotime($this->input->post('Date')));
        $DateTime = $Date.' '.$Time;
//        
//        $value = array('myvalue' => $DateTime);
//        echo json_encode($value);
//        exit();
        
        if($this->mod_pro_attn_mismatch_report->insert_absent_data_into_access_log($CardNo, $DateTime)){
            $myvalue = array("success"=>"true");
        }else{
            $myvalue = array("success"=>"false");
        }
        echo json_encode($myvalue);
        
        
    }
//
    public function CheckAttendance($card_no, $attendance_list) {
        $limit = count($attendance_list) - 1;
        for ($index = 0; $index <= $limit; $index++) {
            if ($card_no == $attendance_list[$index]['CardNo'])
                return TRUE;
        }
        return FALSE;
    }

    public function excelExport() {
        date_default_timezone_set('Asia/Dacca');
        $now = date('Y-m-d', strtotime($this->input->post('hDate')));
//        echo $now.'<br/>';
//        exit();
        $BuildingName = $this->session->userdata('BuildingName');
        $data['floorInfo'] = $this->mod_buil_sec_other->getFloor($BuildingName);
        $Floor = $this->session->userdata('Floor');
        $data['floor'] = $Floor;
        $Department = $this->session->userdata('Department');
        if ($this->session->userdata('Role') == 'Admin') {
            $employee_details = $this->mod_pro_attn_mismatch_report->specific_employee_information1($BuildingName);
        } else {
            $employee_details = $this->mod_pro_attn_mismatch_report->specific_employee_information2($BuildingName, $Floor);
        }
        $attendance_list = $this->mod_access_log->GetDateSpecificCardNo($now);
        $limit1 = count($employee_details) - 1;
        $absent_employee_list = array();
        foreach ($employee_details as $an_employee_details) {
            $absent_employee['CardNo'] = $an_employee_details->CardNo;
            $absent_employee['Name'] = $an_employee_details->Name;
            $absent_employee['BuildingName'] = $an_employee_details->BuildingName;
            $absent_employee['Floor'] = $an_employee_details->Floor;
            $absent_employee['Department'] = $an_employee_details->Department;
            $absent_employee['Line'] = $an_employee_details->Line;
            if (!($this->CheckAttendance($an_employee_details->CardNo, $attendance_list))) {
                array_push($absent_employee_list, $absent_employee);
            }
        }

        $this->PopulateSalarySheet($absent_employee_list, $now);
    }

    public function aasort(&$array, $key) {
        $sorter = array();
        $ret = array();
        reset($array);
        foreach ($array as $ii => $va) {
            $sorter[$ii] = $va[$key];
        }
        asort($sorter);
        foreach ($sorter as $ii => $va) {
            $ret[$ii] = $array[$ii];
        }
        $array = $ret;
    }

    public function PopulateSalarySheet($first_half_attendance, $date) {
        $this->aasort($first_half_attendance, "Department");
        $bn_digits = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');
        require_once APPPATH . "/third_party/PHPExcel.php";
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("RCIS")
                ->setLastModifiedBy("RCIS")
                ->setTitle("Absent Report")
                ->setSubject("CopyRight RCIS")
                ->setDescription("Absent Report")
                ->setKeywords("Absent Report")
                ->setCategory("Absent Report");
// Add some data
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'কার্ড নং')
                ->setCellValue('B1', 'নাম')
                ->setCellValue('C1', 'বিভাগ')
                ->setCellValue('D1', 'মন্তব্য')
                ->setCellValue('E1', 'স্বাক্ষর');
        //set margin
        $sheet = $objPHPExcel->getActiveSheet(0);
        $pageMargins = $sheet->getPageMargins();

// margin is set in inches (0.5cm)


        $pageMargins->setTop(1.397058824);
        $pageMargins->setBottom(1.166666667);


        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
                ),
            ),
        );
        $date = date('d M, Y', strtotime($date));
        $total = count($first_half_attendance);
        $objPHPExcel->getActiveSheet(0)->getStyle('A1:E' . ($total + 1))->applyFromArray($styleArray);
//        //wrap context
        //$objPHPExcel->getActiveSheet(0)->getStyle('B1:B' . ($total + 1))->getAlignment()->setWrapText(true);

        $objPHPExcel->getActiveSheet(0)
                ->getHeaderFooter()->setOddHeader("&C&18&K000000&B&UOPEX GROUP, BUILDING NAME : WG4, FLOOR : SDL1\nABSENT REPORT(" . $date . ") TOTAL ABSENT : " . $total);
        $objPHPExcel->getActiveSheet(0)
                ->getHeaderFooter()->setEvenHeader("&C&18&K000000&B&UOPEX GROUP, BUILDING NAME : WG4, FLOOR : SDL1\nABSENT REPORT(" . $date . ") TOTAL ABSENT : " . $total);

        $objPHPExcel->getActiveSheet(0)
                ->getHeaderFooter()->setOddFooter("&ROIC\nName : .............\nSIGNATURE : .............\nDATE & TIME : ............. &CHREX\nName : .............\nSIGNATURE : .............\nDATE & TIME : .............&LTIMEKEEPER\nName : .............\nSIGNATURE : .............\nDATE & TIME : .............");
        $objPHPExcel->getActiveSheet(0)
                ->getHeaderFooter()->setEvenFooter("&ROIC\nName : .............\nSIGNATURE : .............\nDATE & TIME : ............. &CHREX\nName : .............\nSIGNATURE : .............\nDATE & TIME : .............&LTIMEKEEPER\nName : .............\nSIGNATURE : .............\nDATE & TIME : .............");

        $objPHPExcel->getActiveSheet(0)->getPageSetup()->setFitToPage(true);
        $objPHPExcel->getActiveSheet(0)->getPageSetup()->setFitToHeight(0);
        $objPHPExcel->getActiveSheet(0)->getPageSetup()->setFitToWidth(1);

        $index = 2;
        $flag = 0;
        foreach ($first_half_attendance as $a) {
            if ($flag == 0) {
                $temp = $a;
                $flag = 1;
            }
            if ($a['Department'] != $temp['Department']) {
                $objPHPExcel->getActiveSheet()->setBreak('A' . ($index - 1), PHPExcel_Worksheet::BREAK_ROW);
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$index, 'কার্ড নং')
                ->setCellValue('B'.$index, 'নাম')
                ->setCellValue('C'.$index, 'বিভাগ')
                ->setCellValue('D'.$index, 'মন্তব্য')
                ->setCellValue('E'.$index, 'স্বাক্ষর');
                $index++;
                $total++;
                $temp = $a;
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $index, $a['CardNo'])
                    ->setCellValue('B' . $index, $a['Name'])
                    ->setCellValue('C' . $index, str_replace(range(0, 9), $bn_digits, $a['Department']));
            $index++;
        }
         $objPHPExcel->getActiveSheet(0)->getStyle('A1:E' . ($total + 1))->applyFromArray($styleArray);

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet(0)->setAutoFilter('C1:C' . ($total + 1));

// Redirect output to a client’s web browser (Excel5)
        date_default_timezone_set('Asia/Dacca');
        header('Content-Type: application/vnd.ms-excel');
        $fileName = date('d-m-Y_g:i_a', now());

        header("Content-Disposition: attachment;filename=Absent_Report_" . $fileName . ".xls ");
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function check_array($CardNo, $att_list_all) {
        foreach ($att_list_all as $att_list) {
            if ($CardNo == $att_list['CardNo'])
                return TRUE;
        }
        return FALSE;
    }

    public function attendanceRectifaction($cardNo) {
        $data['anEmployeeInfo'] = $this->mod_set_employee_info_detail->view_by_cardno_array($cardNo);
//        echo '<pre>';
//        print_r($anEmployeeInfo);
//        echo '</pre>';

        $data['container'] = 'temp/daily_absent_report/edit';
        $this->load->view('main_page', $data);
    }

    public function InsertAbsentEmployee() {
        $access_log = array();

        $in_array['CardNo'] = $this->input->post('CardNo');
        $in_array['DateTime'] = date('Y-m-d H:i:s', strtotime('-6 hours', strtotime($this->input->post('InTime'))));
        $in_array['Status'] = 'IN';
        $in_array['CreatedBy'] = $this->session->userdata('Email');
        $in_array['DelStatus'] = 'ACT';
        array_push($access_log, $in_array);

        $in_array['CardNo'] = $this->input->post('CardNo');
        $in_array['DateTime'] = date('Y-m-d H:i:s', strtotime('-6 hours', strtotime($this->input->post('OutTime'))));
        $in_array['Status'] = 'OUT';
        $in_array['CreatedBy'] = $this->session->userdata('Email');
        $in_array['DelStatus'] = 'ACT';
        array_push($access_log, $in_array);

        $this->mod_access_log->insert_batch_random_data($access_log);
        redirect('con_pro_daily_absent_report/');
    }

    function db_backup() {
        $this->load->dbutil();

        $backup = & $this->dbutil->backup();
        $this->load->helper('file');
        write_file('/path/to/sdl1_backup.zip', $backup);

        $this->load->helper('download');
        force_download('sdl1_backup.zip', $backup);
    }

    public function absent_report_new() {
        $data['container'] = 'temp/daily_absent_report/daily_absent_report';
        $this->load->view('main_page', $data);
    }

}
