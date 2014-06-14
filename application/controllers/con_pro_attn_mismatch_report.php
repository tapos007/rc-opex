<?php

class Con_pro_attn_mismatch_report extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('mod_pro_attn_mismatch_report');
        $this->load->model('mod_access_log');
        $this->load->model('mod_incurrect_access_log');
        $this->load->model('mod_monthly_wages_detail');
        $this->load->model('mod_buil_sec_other');
        $this->load->model('mod_set_work_hour_breakdown');
    }

    public function insert() {
        date_default_timezone_set('Asia/Dacca');
        $cardno = $this->input->post("CardNo");
        $InTime = date('Y-m-d H:i:s', strtotime('-6 hours', strtotime($this->input->post('InTime'))));
        $OutTime = date('Y-m-d H:i:s', strtotime('-6 hours', strtotime($this->input->post('OutTime'))));
        if (date('H:i:s', strtotime($InTime)) < date('H:i:s', strtotime('04:59:59'))) {
            $status = 'IN';
        } else {
            $status = 'OUT';
        }
        $Indata = array(
            "CardNo" => $cardno,
            "DateTime" => date('Y-m-d H:i:s', strtotime($InTime)),
            "Status" => $status,
            "CreatedBy" => $this->session->userdata('Email'),
            "DelStatus" => 'ACT'
        );
        if (date('H:i:s', strtotime($OutTime)) > date('H:i:s', strtotime('04:59:59'))) {
            $status = 'OUT';
        } else {
            $status = 'IN';
        }
        $Outdata = array(
            "CardNo" => $cardno,
            "DateTime" => date('Y-m-d H:i:s', strtotime($OutTime)),
            "CreatedBy" => $this->session->userdata('Email'),
            "Status" => $status,
            "DelStatus" => 'ACT'
        );
        $this->mod_pro_attn_mismatch_report->UpdateIncurrenctAccessLog($cardno, $InTime);
        $this->mod_access_log->insert($Indata);
        $this->mod_access_log->insert($Outdata);

        redirect('con_pro_attn_mismatch_report/edit');
    }

    public function insert1() {
        date_default_timezone_set('Asia/Dacca');
        $cardno = $this->input->post("CardNo");
        $InTime = date('Y-m-d H:i:s', strtotime('-6 hours', strtotime($this->input->post('InTime'))));
        $OutTime = date('Y-m-d H:i:s', strtotime('-6 hours', strtotime($this->input->post('OutTime'))));
        if (date('H:i:s', strtotime($InTime)) < date('H:i:s', strtotime('04:59:59'))) {
            $status = 'IN';
        } else {
            $status = 'OUT';
        }
        $Indata = array(
            "CardNo" => $cardno,
            "DateTime" => date('Y-m-d H:i:s', strtotime($InTime)),
            "Status" => $status,
            "CreatedBy" => $this->session->userdata('Email'),
            "DelStatus" => 'ACT'
        );
        if (date('H:i:s', strtotime($OutTime)) > date('H:i:s', strtotime('04:59:59'))) {
            $status = 'OUT';
        } else {
            $status = 'IN';
        }
        $Outdata = array(
            "CardNo" => $cardno,
            "DateTime" => date('Y-m-d H:i:s', strtotime($OutTime)),
            "CreatedBy" => $this->session->userdata('Email'),
            "Status" => $status,
            "DelStatus" => 'ACT'
        );
        $this->mod_pro_attn_mismatch_report->UpdateIncurrenctAccessLog($cardno, $InTime);
        $this->mod_access_log->insert($Indata);
        $this->mod_access_log->insert($Outdata);

        redirect('con_pro_attn_mismatch_report/index', 'refresh');
    }

    public function index() {
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
        date_default_timezone_set('Asia/Dacca');
        if ($this->input->post('Date')) {
            $mydate = $this->input->post('Date');
            $now = date('Y-m-d', strtotime(str_replace('-', '/', $mydate)));
        } else {
            $now = date('Y-m-d', strtotime('-1 day', now()));
        }
        $StartDate = $now . ' 00:00:01';
        $EndDate = $now . ' 23:59:59';
        $StartDate = date('Y-m-d H:i:s', strtotime($StartDate));
        $EndDate = date('Y-m-d H:i:s', strtotime($EndDate));
        $incorrect_access_log = $this->mod_pro_attn_mismatch_report->incorrect_access_log($StartDate, $EndDate);
        $mismatch_information = array();
        $abc = array();

        foreach ($incorrect_access_log as $access_log) {
            $mismatch_information['PID'] = $access_log->ID;
            $mismatch_information['CardNo'] = $access_log->CardNo;
            $mismatch_information['DateTime'] = $access_log->DateTime;
            $mismatch_information['IP'] = $access_log->IP;
            $mismatch_information['Status'] = $access_log->Status;
            $data12 = $this->retrieve_employee_information($access_log->CardNo, $employee_details);

            if ($data12 != NULL) {
                $mismatch_information['Name'] = $data12['Name'];
                $mismatch_information['BuildingName'] = $data12['BuildingName'];
                $mismatch_information['Floor'] = $data12['Floor'];
                $mismatch_information['Department'] = $data12['Department'];
                $mismatch_information['Line'] = $data12['Line'];
                array_push($abc, $mismatch_information);
            }
        }
        $data['tbl_work_hour_breakdown'] = $this->mod_set_work_hour_breakdown->view1();
        $data['showDate'] = $now;
        $data['tbl_mismatch_report'] = $abc;
        $data['container'] = 'temp/prev_attn_mismatch_report/previous_attn_mismatch_report';
        $this->load->view('main_page', $data);
    }

    public function update_in_time() {
        $ID = $this->input->post('ID');
        $DateTime = date('Y-m-d H:i:s', strtotime('-6 hours', strtotime($this->input->post('DateTime'))));
        $this->mod_pro_attn_mismatch_report->update_in_time($ID, $DateTime);
    }

    public function editOuttimes() {
        $cardNo = $this->input->post('CardNo');
        $outtime = $this->input->post('DateTime');
        $totalOvertimeHour = $this->input->post('txtvalue');
        $checkDate = $this->input->post('incomeTime');
        $checkDate = date('Y-m-d', strtotime(str_replace('-', '/', $checkDate)));
        $IP = $this->input->post('IP');
        $this->load->model("mod_set_holiday_catagory");
        $accurateStartTime = $checkDate . " " . $this->session->userdata('ctime');
        $accurateEndTime = $checkDate . " " . $this->session->userdata('otime');
        $date = date('Y-m-d', strtotime(str_replace('-', '/', $checkDate)));
        if ($this->mod_set_holiday_catagory->checkThisDayHoliday($date)) {
            $DateTime = date('Y-m-d H:i:s', strtotime($totalOvertimeHour . ' hours', strtotime($accurateStartTime)));
        } else {
            $DateTime = date('Y-m-d H:i:s', strtotime($totalOvertimeHour . ' hours', strtotime($accurateEndTime)));
        }

        $finalAccourateTIme = date('Y-m-d H:i:s', strtotime('-6 hours', strtotime($DateTime)));

        $this->mod_access_log->insertOutTime($cardNo, $finalAccourateTIme, $IP);
        $this->mod_access_log->updateStarttime($cardNo, $date);
        $value = array('myinfo' => 'true');
        echo json_encode($value);
    }

    public function insert_in_time() {
        $CardNo = $this->input->post('CardNo');
        $DateTime = date('Y-m-d H:i:s', strtotime('-6 hours', strtotime($this->input->post('DateTime'))));
        $IP = $this->input->post('IP');
        $ID = $this->input->post('ID');
        $this->mod_pro_attn_mismatch_report->insert_in_time($CardNo, $DateTime, $IP, $ID);
        $value = array('myinfo' => 'true');
        echo json_encode($value);
    }

    public function get_department_name() {
        $BuildingName = $this->input->post('Building');
        $Floor = $this->input->post('Floor');
        $DepartmentName = $this->mod_monthly_wages_detail->get_department_by_name($BuildingName, $Floor);
        echo json_encode($DepartmentName);
    }

    public function get_line_name() {
        $BuildingName = $this->input->post('Building');
        $Floor = $this->input->post('Floor');
        $DepartmentName = $this->input->post('Department');
        $LineName = $this->mod_monthly_wages_detail->get_line_by_name($BuildingName, $Floor, $DepartmentName);
        echo json_encode($LineName);
    }

    public function search() {
        $BuildingName = $this->input->post('Building');
        $Floor = $this->input->post('Floor');
        $DepartmentName = $this->input->post('DepartmentSection');
        $Line = $this->input->post('LineUnit');
        $Date = $this->input->post('Date');

        $employee_details = $this->mod_pro_attn_mismatch_report->specific_employee_information_report($BuildingName, $Floor, $DepartmentName, $Line);
        $now = date('Y-m-d', strtotime($Date));
        $StartDate = $now . ' 00:00:01';
        $EndDate = $now . ' 23:59:59';
        $StartDate = date('Y-m-d H:i:s', strtotime($StartDate));
        $EndDate = date('Y-m-d H:i:s', strtotime($EndDate));
        $incorrect_access_log = $this->mod_pro_attn_mismatch_report->incorrect_access_log($StartDate, $EndDate);
        $mismatch_information = array();
        $abc = array();

        foreach ($incorrect_access_log as $access_log) {
            $mismatch_information['CardNo'] = $access_log->CardNo;
            $mismatch_information['DateTime'] = $access_log->DateTime;
            $data12 = $this->retrieve_employee_information($access_log->CardNo, $employee_details);
            if ($data12 != NULL) {
                $mismatch_information['Name'] = $data12['Name'];
                $mismatch_information['Department'] = $data12['Department'];
                $mismatch_information['Line'] = $data12['Line'];
                array_push($abc, $mismatch_information);
            }
        }
        $data['tbl_mismatch_report'] = $abc;
        $data['container'] = 'temp/prev_attn_mismatch_report/previous_attn_mismatch_report';
        $this->load->view('main_page', $data);
    }

    public function retrieve_employee_information($card_no, $myvalue) {
        $abc = array();
        foreach ($myvalue as $rec_employee_info) {
            if ($card_no == $rec_employee_info->CardNo) {
                $abc['Name'] = $rec_employee_info->Name;
                $abc['BuildingName'] = $rec_employee_info->BuildingName;
                $abc['Floor'] = $rec_employee_info->Floor;
                $abc['CardNo'] = $rec_employee_info->CardNo;
                $abc['Department'] = $rec_employee_info->Department;
                $abc['Line'] = $rec_employee_info->Line;
                return $abc;
            }
        }
    }

    public function edit() {
        $cardNo = $this->input->post('CardNo');
        $time = $this->input->post('Date');
        $data['tbl_mismatch_report'] = $this->mod_pro_attn_mismatch_report->view_by_CardNo($cardNo, $time);
        $data['container'] = 'temp/prev_attn_mismatch_report/edit';
        $this->load->view('main_page', $data);
    }

    public function edit1() {
        $cardNo = $this->uri->segment(3);
        $data['tbl_mismatch_report'] = $this->mod_pro_attn_mismatch_report->view_by_CardNo1($cardNo);
        $data['container'] = 'temp/prev_attn_mismatch_report/edit';
        $this->load->view('main_page', $data);
    }

    public function systemGeneratedCurrection($Month) {
        $email = 'AUTO';
        $all_mismacthes = $this->mod_incurrect_access_log->getGruoupedData($Month);
        $all_currect_data = array();
        $limit = count($all_mismacthes) - 1;
        $currect_data_index = 0;
        for ($index = 0; $index <= $limit; $index++) {
            $all_mismacthes[$index]['DelStatus'] = 'DEL';

            $all_currect_data[$currect_data_index]['CardNo'] = $all_mismacthes[$index]['CardNo'];
            $all_currect_data[$currect_data_index]['CreatedBy'] = $email;
            $all_currect_data[$currect_data_index]['DelStatus'] = 'ACT';

            $all_currect_data[$currect_data_index + 1]['CardNo'] = $all_mismacthes[$index]['CardNo'];
            $all_currect_data[$currect_data_index + 1]['CreatedBy'] = $email;
            $all_currect_data[$currect_data_index + 1]['DelStatus'] = 'ACT';

            if (date('H:i:s', strtotime($all_mismacthes[$index]['DateTime'])) >= date('H:i:s', strtotime('06:00:00'))) {
                $all_currect_data[$currect_data_index]['Status'] = 'IN';
                $all_currect_data[$currect_data_index + 1]['Status'] = 'OUT';
                $all_currect_data[$currect_data_index]['DateTime'] = date('Y-m-d H:i:s', strtotime(date('Y-m-d', strtotime($all_mismacthes[$index]['DateTime'])) . ' 02:15:00'));
                $all_currect_data[$currect_data_index + 1]['DateTime'] = $all_mismacthes[$index]['DateTime'];
            } else {
                $all_currect_data[$currect_data_index]['Status'] = 'IN';
                $all_currect_data[$currect_data_index + 1]['Status'] = 'OUT';
                $all_currect_data[$currect_data_index]['DateTime'] = $all_mismacthes[$index]['DateTime'];
                $all_currect_data[$currect_data_index + 1]['DateTime'] = date('Y-m-d H:i:s', strtotime(date('Y-m-d', strtotime($all_mismacthes[$index]['DateTime'])) . ' 11:15:00'));
            }
            $currect_data_index+=2;
        }
        echo 'Data Truncating.....';
        $this->mod_incurrect_access_log->EmptyTable($Month);
        echo 'Data Inserting .....';
        $this->mod_incurrect_access_log->insert_batch_random_data($all_mismacthes);
        $this->mod_access_log->insert_batch_random_data($all_currect_data);
        echo 'Data Updated & Inserted';
    }

    public function excelExport() {
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
        date_default_timezone_set('Asia/Dacca');
        if ($this->input->post('hDate')) {
            $mydate = $this->input->post('hDate');
            $now = date('Y-m-d', strtotime(str_replace('-', '/', $mydate)));
        } else {
            $now = date('Y-m-d', strtotime('-1 day', now()));
        }

        $StartDate = $now . ' 00:00:01';
        $EndDate = $now . ' 23:59:59';
        $StartDate = date('Y-m-d H:i:s', strtotime($StartDate));
        $EndDate = date('Y-m-d H:i:s', strtotime($EndDate));
        $incorrect_access_log = $this->mod_pro_attn_mismatch_report->incorrect_access_log($StartDate, $EndDate);
        $mismatch_information = array();
        $abc = array();

        foreach ($incorrect_access_log as $access_log) {
            $mismatch_information['CardNo'] = $access_log->CardNo;
            $mismatch_information['DateTime'] = $access_log->DateTime;
            $data12 = $this->retrieve_employee_information($access_log->CardNo, $employee_details);
            if ($data12 != NULL) {
                $mismatch_information['Name'] = $data12['Name'];
                $mismatch_information['BuildingName'] = $data12['BuildingName'];
                $mismatch_information['Floor'] = $data12['Floor'];
                $mismatch_information['Department'] = $data12['Department'];
                $mismatch_information['Line'] = $data12['Line'];
                array_push($abc, $mismatch_information);
            }
        }
        $this->PopulateSalarySheet($abc);
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

    public function test_excel($first_half_attendance) {
        $date = date('d M, Y', strtotime($first_half_attendance[0]['DateTime']));
        $total = count($first_half_attendance);


        $bn_digits = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');
        require_once APPPATH . "/third_party/PHPExcel.php";
        date_default_timezone_set('Asia/Dacca');
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);

        define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');



        /** Include PHPExcel */
// Create new PHPExcel object
        echo date('H:i:s'), " Create new PHPExcel object", EOL;
        $objPHPExcel = new PHPExcel();

//set margin
        $sheet = $objPHPExcel->getActiveSheet();
        $pageMargins = $sheet->getPageMargins();

// margin is set in inches (0.5cm)


        $pageMargins->setTop(1.397058824);
        $pageMargins->setBottom(1.166666667);

//set border style
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
                ),
            ),
        );


// Set print headers
        $objPHPExcel->getActiveSheet()
                ->getHeaderFooter()->setOddHeader("&C&18&K000000&B&UOPEX GROUP, BUILDING NAME : WG4, FLOOR : SDL1\nMISSMATCH REPORT(" . $date . ") TOTAL MISSMATCH : " . $total);
        $objPHPExcel->getActiveSheet()
                ->getHeaderFooter()->setEvenHeader('&C&18&K000000&B&UOPEX GROUP, BUILDING NAME : WG4, FLOOR : SDL1\nMISSMATCH REPORT(".$date.") TOTAL MISSMATCH : ".$total');

// Set print footers
        $objPHPExcel->getActiveSheet()
                ->getHeaderFooter()->setOddFooter("&ROIC\nName : .............\nSIGNATURE : .............\nDATE & TIME : ............. &CHREX\nName : .............\nSIGNATURE : .............\nDATE & TIME : .............&LTIMEKEEPER\nName : .............\nSIGNATURE : .............\nDATE & TIME : .............");
        $objPHPExcel->getActiveSheet()
                ->getHeaderFooter()->setEvenFooter("&ROIC\nName : .............\nSIGNATURE : .............\nDATE & TIME : ............. &CHREX\nName : .............\nSIGNATURE : .............\nDATE & TIME : .............&LTIMEKEEPER\nName : .............\nSIGNATURE : .............\nDATE & TIME : .............");

        $objPHPExcel->getActiveSheet()->getStyle('A1:G' . $total)->applyFromArray($styleArray);
        //wrap context
        $objPHPExcel->getActiveSheet()->getStyle('A1:G' . $total)->getAlignment()->setWrapText(true);
// Set document properties
        echo date('H:i:s'), " Set document properties", EOL;
        $objPHPExcel->getProperties()->setCreator("RCIS")
                ->setLastModifiedBy("RCIS")
                ->setTitle("MISSMATCH REPORT SDL1")
                ->setSubject("MISSMATCH REPORT SDL1")
                ->setDescription("MISSMATCH REPORT SDL1")
                ->setKeywords("MISSMATCH REPORT SDL1")
                ->setCategory("MISSMATCH REPORT SDL1");


// Create a first sheet
        //echo date('H:i:s'), " Add data and page breaks", EOL;
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()
                ->setCellValue('A1', 'কার্ড নং')
                ->setCellValue('B1', 'নাম')
                ->setCellValue('C1', 'বিভাগ')
                ->setCellValue('D1', 'প্রবেশ সময়')
                ->setCellValue('E1', 'বাহির সময় ')
                ->setCellValue('F1', 'মন্তব্য')
                ->setCellValue('G1', 'স্বাক্ষর');


// Add data
        for ($index = 0; $index < $total; $index++) {
            if ($first_half_attendance[$index]['Department'] == $first_half_attendance[$index - 1]['Department']) {
                $objPHPExcel->getActiveSheet()->setCellValue('A' . ($index + 2), str_replace(range(0, 9), $bn_digits, $first_half_attendance[$index]['CardNo']));
                $objPHPExcel->getActiveSheet()->setCellValue('B' . ($index + 2), $first_half_attendance[$index]['Name']);
                $objPHPExcel->getActiveSheet()->setCellValue('C' . ($index + 2), $first_half_attendance[$index]['Department']);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . ($index + 2), str_replace(range(0, 9), $bn_digits, date('g:i:s', strtotime('+6 hours', strtotime($first_half_attendance[$index]['DateTime'])))));
                $objPHPExcel->getActiveSheet()->setCellValue('E' . ($index + 2), str_replace(range(0, 9), $bn_digits, date('g:i:s', strtotime('+6 hours', strtotime($first_half_attendance[$index]['DateTime'])))));
            } else {
                $objPHPExcel->getActiveSheet()->setBreak('A' . $index + 2, PHPExcel_Worksheet::BREAK_ROW);
                $objPHPExcel->getActiveSheet()->setCellValue('A' . ($index + 2), str_replace(range(0, 9), $bn_digits, $first_half_attendance[$index]['CardNo']));
                $objPHPExcel->getActiveSheet()->setCellValue('B' . ($index + 2), $first_half_attendance[$index]['Name']);
                $objPHPExcel->getActiveSheet()->setCellValue('C' . ($index + 2), $first_half_attendance[$index]['Department']);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . ($index + 2), str_replace(range(0, 9), $bn_digits, date('g:i:s', strtotime('+6 hours', strtotime($first_half_attendance[$index]['DateTime'])))));
                $objPHPExcel->getActiveSheet()->setCellValue('E' . ($index + 2), str_replace(range(0, 9), $bn_digits, date('g:i:s', strtotime('+6 hours', strtotime($first_half_attendance[$index]['DateTime'])))));
            }
        }

        $objPHPExcel->setActiveSheetIndex(0);


        date_default_timezone_set('Asia/Dacca');
        header('Content-Type: application/vnd.ms-excel');
        $fileName = date('d-m-Y_g:i_a', now());

        header("Content-Disposition: attachment;filename=Mismactch_Report_" . $fileName . ".xls ");
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(str_replace('.php', '.xlsx', __FILE__));

        $this->load->helper('download');
        $data = file_get_contents(base_url() . 'application/controller/con_pro_attn_mismatch_report.xlsx'); // Read the file's contents
        $name = 'mismatch.xlsx';
//
        force_download($name, $data);

        //echo __FILE__;
    }

    public function PopulateSalarySheet($first_half_attendance) {
        $this->aasort($first_half_attendance, "Department");
        $bn_digits = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');
        require_once APPPATH . "/third_party/PHPExcel.php";
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("RCIS")
                ->setLastModifiedBy("RCIS")
                ->setTitle("Mismatch Report")
                ->setSubject("CopyRight RCIS")
                ->setDescription("Mismatch Report")
                ->setKeywords("Mismatch Report")
                ->setCategory("Mismatch Report");
// Add some data
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'কার্ড নং')
                ->setCellValue('B1', 'নাম')
                ->setCellValue('C1', 'বিভাগ')
                ->setCellValue('D1', 'প্রবেশ সময়')
                ->setCellValue('E1', 'বাহির সময় ')
                ->setCellValue('F1', 'মন্তব্য')
                ->setCellValue('G1', 'স্বাক্ষর');
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
        $date = date('d M, Y', strtotime($first_half_attendance[0]['DateTime']));
        $total = count($first_half_attendance);

//        //wrap context
        //$objPHPExcel->getActiveSheet(0)->getStyle('B1:B' . ($total + 1))->getAlignment()->setWrapText(true);

        $objPHPExcel->getActiveSheet(0)
                ->getHeaderFooter()->setOddHeader("&C&18&K000000&B&UOPEX GROUP, BUILDING NAME : WG4, FLOOR : SDL1\nMISSMATCH REPORT(" . $date . ") TOTAL MISSMATCH : " . $total);
        $objPHPExcel->getActiveSheet(0)
                ->getHeaderFooter()->setEvenHeader("&C&18&K000000&B&UOPEX GROUP, BUILDING NAME : WG4, FLOOR : SDL1\nMISSMATCH REPORT(" . $date . ") TOTAL MISSMATCH : " . $total);

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
                        ->setCellValue('A' . $index, 'কার্ড নং')
                        ->setCellValue('B' . $index, 'নাম')
                        ->setCellValue('C' . $index, 'বিভাগ')
                        ->setCellValue('D' . $index, 'প্রবেশ সময়')
                        ->setCellValue('E' . $index, 'বাহির সময় ')
                        ->setCellValue('F' . $index, 'মন্তব্য')
                        ->setCellValue('G' . $index, 'স্বাক্ষর');
                $temp = $a;
                $index++;
                $total++;
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $index, $a['CardNo'])
                    ->setCellValue('B' . $index, $a['Name'])
                    ->setCellValue('C' . $index, str_replace(range(0, 9), $bn_digits, $a['Department']));
            if (date('H:i:s', strtotime('+6 hours', strtotime($a['DateTime']))) < 11) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $index, str_replace(range(0, 9), $bn_digits, date('H:i:s', strtotime('+6 hours', strtotime($a['DateTime'])))));
            } else {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $index, str_replace(range(0, 9), $bn_digits, date('H:i:s', strtotime('+6 hours', strtotime($a['DateTime'])))));
            }
            $index++;
        }
        $objPHPExcel->getActiveSheet(0)->getStyle('A1:G' . ($total + 1))->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->setAutoFilter('C1:C' . ($total + 1));
        $objPHPExcel->setActiveSheetIndex(0);
        //$objPHPExcel->getActiveSheet(0)->setAutoFilter('C1:C' . ($total + 1));
// Redirect output to a client’s web browser (Excel5)
        date_default_timezone_set('Asia/Dacca');
        header('Content-Type: application/vnd.ms-excel');
        $fileName = date('d-m-Y_g:i_a', now());

        header("Content-Disposition: attachment;filename=Mismactch_Report_" . $fileName . ".xls ");
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

}
