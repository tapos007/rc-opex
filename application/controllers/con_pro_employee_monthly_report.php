<?php
class Con_pro_employee_monthly_report extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('mod_pro_employee_monthly_report');
        $this->load->model('mod_access_log');
        $this->load->model('mod_leave_detail');
        $this->load->model('mod_pro_attn_mismatch_report');
        $this->load->model('mod_leave_type_allocation');
        $this->load->helper('date');
    }

    public function index() {
        $data['container'] = 'temp/employee_monthly_report/search';
        $this->load->view('main_page', $data);
    }

    public function search() {
        $CardNo = $this->input->post('CardNo');
        $Month = $this->input->post('Month');
        $data['tbl_employee_monthly_report'] = $this->mod_pro_employee_monthly_report->view_by_CardNo($CardNo, $Month);
        $data['tbl_employee_monthly_missmatch_report'] = $this->mod_pro_employee_monthly_report->view_by_CardNo_missmatch($CardNo, $Month);
        $data['tbl_employee_monthly_leave_report'] = $this->mod_leave_detail->view_by_CardNo($CardNo, $Month);
//$data['tbl_employee_leave'];
        $data['container'] = 'temp/employee_monthly_report/view';
        $this->load->view('main_page', $data);
    }

    public function delete_monthly_attandance_record() {
      $CardNo = $this->input->post('CardNo');
        $Date = $this->input->post('DateTime');

        if ($this->mod_pro_employee_monthly_report->delete_monthly_attandance($CardNo, $Date)) {
            echo json_encode(array("success" => "true"));
        } else{
             echo json_encode(array("success" => "false"));
        }
    }
    
    public function search_get($CardNo, $Month) {
        $data['tbl_employee_monthly_report'] = $this->mod_pro_employee_monthly_report->view_by_CardNo($CardNo, $Month);
        $data['tbl_employee_monthly_missmatch_report'] = $this->mod_pro_employee_monthly_report->view_by_CardNo_missmatch($CardNo, $Month);
        $data['tbl_employee_monthly_leave_report'] = $this->mod_leave_detail->view_by_CardNo($CardNo, $Month);
//$data['tbl_employee_leave'];
        $data['container'] = 'temp/employee_monthly_report/view';
        $this->load->view('main_page', $data);
    }

    public function update() {
        $CardNo = $this->input->post('CardNo');
        $DateTime = date('Y-m-d H:i:s', strtotime('-6 hours', strtotime($this->input->post('DateTime'))));
        $DateTimeOld = date('Y-m-d H:i:s', strtotime('-6 hours', strtotime($this->input->post('DateTimeOld'))));
        $this->mod_pro_employee_monthly_report->update_in_out_time($CardNo, $DateTime, $DateTimeOld);
    }

    public function generic_intime() {
        $cardNo = $this->input->post('CardNo');
        $Month = $this->input->post('Month');
        $InTime = $this->input->post('Intime');
        $data['tbl_mismatch_report'] = $this->mod_pro_employee_monthly_report->view_by_CardNo_missmatch($cardNo, $Month);
        foreach ($data['tbl_mismatch_report'] as $tbl_missmatch) {
            //echo date('H:i:s', strtotime($tbl_missmatch['DateTime'])) . "<br />"; //.date('H:i:s',  strtotime('03:00:00'))."<br />";
            if (date('H:i:s', strtotime($tbl_missmatch['DateTime'])) >= date('H:i:s', strtotime('06:00:00'))) {
                $InTimeSend = date('Y-m-d', strtotime($tbl_missmatch['DateTime'])) . " " . $InTime;
                $InTimeSend = date('Y-m-d H:i:s', strtotime('-6 hours', strtotime($InTimeSend)));
                $this->insert_generic_intime($cardNo, $InTimeSend, date('Y-m-d H:i:s', strtotime($tbl_missmatch['DateTime'])));
            } else {
                $OutTimeSend = date('Y-m-d', strtotime($tbl_missmatch['DateTime'])) . " " . '11:15:00';
                $OutTimeSend = date('Y-m-d H:i:s', strtotime($OutTimeSend));
                $this->insert_generic_outtime($cardNo, date('Y-m-d H:i:s', strtotime($tbl_missmatch['DateTime'])), $OutTimeSend);
            }
        }
        $this->search_get($cardNo, $Month);

        //$this->load->view('main_page', $data);
    }

    public function insert_generic_intime($cardno, $InTime, $OutTime) {

        $Indata = array(
            "CardNo" => $cardno,
            "DateTime" => date('Y-m-d H:i:s', strtotime($InTime)),
            "Status" => "IN",
            "CreatedBy" => $this->session->userdata('Email'),
            "DelStatus" => 'ACT'
        );
        $Outdata = array(
            "CardNo" => $cardno,
            "DateTime" => date('Y-m-d H:i:s', strtotime($OutTime)),
            "Status" => "OUT",
            "CreatedBy" => $this->session->userdata('Email'),
            "DelStatus" => 'ACT'
        );
        $this->mod_pro_attn_mismatch_report->UpdateIncurrenctAccessLog($cardno, $OutTime);
        $this->mod_access_log->insert($Indata);
        $this->mod_access_log->insert($Outdata);
    }

    public function insert_generic_outtime($cardno, $InTime, $OutTime) {

        $Indata = array(
            "CardNo" => $cardno,
            "DateTime" => date('Y-m-d H:i:s', strtotime($InTime)),
            "Status" => "IN",
            "CreatedBy" => $this->session->userdata('Email'),
            "DelStatus" => 'ACT'
        );
        $Outdata = array(
            "CardNo" => $cardno,
            "DateTime" => date('Y-m-d H:i:s', strtotime($OutTime)),
            "Status" => "OUT",
            "CreatedBy" => $this->session->userdata('Email'),
            "DelStatus" => 'ACT'
        );
        $this->mod_pro_attn_mismatch_report->UpdateIncurrenctAccessLog($cardno, $InTime);
        $this->mod_access_log->insert($Indata);
        $this->mod_access_log->insert($Outdata);
    }

    public function Leave_Details_Delete($cardNo, $date) {
        $leaveType = $this->mod_leave_detail->get_leave_type_name($cardNo, $date);
        $leaveType = $leaveType[0]['LeaveCategoryName'];
        $this->mod_leave_type_allocation->CardNo = $cardNo;
        $this->mod_leave_type_allocation->LeaveType = $leaveType;
        $this->mod_leave_type_allocation->Year = date('Y', strtotime($date));
        $card_specific_leave_data = $this->mod_leave_type_allocation->view_by_id();
        foreach ($card_specific_leave_data as $a_data) {
            if ($a_data->LeaveType == $leaveType) {
                $this->mod_leave_type_allocation->Days = $a_data->Days + 1;
                $this->mod_leave_type_allocation->update();
            }
        }
        $this->mod_leave_detail->LeaveEntryDelete($cardNo, $date);
        $this->search_get($cardNo, date('m', strtotime($date)));
    }
     public function insert1() {

        date_default_timezone_set('Asia/Dacca');

        $cardno = $this->input->post("icCard");
        $myintime = array();
        $Indata = array();
        $Outdata = array();
        $count = 0;
        $mm = $this->input->post('outime');
        foreach ($this->input->post('intime') as $value) {
            $InTime = date('Y-m-d H:i:s', strtotime('-6 hours', strtotime($value)));
            $OutTime = date('Y-m-d H:i:s', strtotime('-6 hours', strtotime($mm[$count])));
            if (date('H:i:s', strtotime($InTime)) < date('H:i:s', strtotime('04:59:59'))) {
                $status = 'IN';
            } else {
                $status = 'OUT';
            }
            $Indata[] = array(
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
            $Outdata[] = array(
                "CardNo" => $cardno,
                "DateTime" => date('Y-m-d H:i:s', strtotime($OutTime)),
                "CreatedBy" => $this->session->userdata('Email'),
                "Status" => $status,
                "DelStatus" => 'ACT'
            );
            $count++;
            $myintime[] = $InTime;
        }
        $this->mod_pro_employee_monthly_report->UpdateIncurrenctAccessLog11($cardno, $myintime);
        $this->mod_pro_employee_monthly_report->insert11($Indata);
        $this->mod_pro_employee_monthly_report->insert11($Outdata);

         echo json_encode(array("success" => "true"));
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

    public function excelExport($card_no, $month) {
        $tbl_employee_monthly_report = $this->mod_pro_employee_monthly_report->view_by_CardNo($card_no, $month);
//        $total_present = count($tbl_employee_monthly_report);
        echo '<pre>';
        print_r($tbl_employee_monthly_report);
        echo '</pre>';
        exit();


        $tbl_employee_monthly_leave_report = $this->mod_leave_detail->view_by_CardNo($card_no, $month);
//        $total_leave = count($tbl_employee_monthly_leave_report);

        $tbl_holidays = $this->mod_set_holiday_catagory->get_all_month_specific_holidays($month);


        for ($index = 0; $index < count($tbl_employee_monthly_leave_report); $index++) {
            $leave_array['CardNo'] = $tbl_employee_monthly_leave_report[$index]['CardNo'];
            $leave_array['Date(DateTime)'] = $tbl_employee_monthly_leave_report[$index]['Date'];
            $leave_array['min(inc.DateTime)'] = '';
            $leave_array['max(inc.DateTime)'] = '';
            $leave_array['CreatedBy'] = 'Leave';
            $leave_array['Name'] = $tbl_employee_monthly_report[0]['Name'];
            $leave_array['Department'] = $tbl_employee_monthly_report[0]['Department'];
            $leave_array['Line'] = $tbl_employee_monthly_report[0]['Line'];
            array_push($tbl_employee_monthly_report, $leave_array);
        }

        for ($index = 0; $index < count($tbl_holidays); $index++) {
            $leave_array['CardNo'] = $tbl_employee_monthly_report[0]['CardNo'];
            $leave_array['Date(DateTime)'] = $tbl_holidays[$index]['HolidayDate'];
            $leave_array['min(inc.DateTime)'] = '';
            $leave_array['max(inc.DateTime)'] = '';
            $leave_array['CreatedBy'] = 'Holiday';
            $leave_array['Name'] = $tbl_employee_monthly_report[0]['Name'];
            $leave_array['Department'] = $tbl_employee_monthly_report[0]['Department'];
            $leave_array['Line'] = $tbl_employee_monthly_report[0]['Line'];
            array_push($tbl_employee_monthly_report, $leave_array);
        }

        //$this->aasort($tbl_employee_monthly_report, "Date(DateTime)");

        for ($i = 0; $i < count($tbl_employee_monthly_report); $i++) {
            for ($j = $i + 1; $j < count($tbl_employee_monthly_report); $j++) {
                if (date('Y-m-d', strtotime($tbl_employee_monthly_report[$i]['Date(DateTime)'])) == date('Y-m-d', strtotime($tbl_employee_monthly_report[$j]['Date(DateTime)']))) {
                    
                    if ($tbl_employee_monthly_report[$j]['CreatedBy'] == 'Holiday') {
                        $tbl_employee_monthly_report[$i]['CreatedBy'] = 'Holiday Work';
                        $tbl_employee_monthly_report[$j]['CreatedBy'] = 'Garbage';
                    } else if ($tbl_employee_monthly_report[$j]['CreatedBy'] == 'Leave') {
                        $tbl_employee_monthly_report[$j]['CreatedBy'] = 'Garbage';
                    }
                }
            }
        }

        $this->populate_employee_monthly_sheet($tbl_employee_monthly_report, $month);
    }

    public function populate_employee_monthly_sheet($tbl_employee_monthly_report, $month) {
        $year = date('Y', now());
        $first_date = $year . '-' . $month . '-' . '01';
        $last_day = date("Y-m-t", strtotime($first_date));

        $card_no = $tbl_employee_monthly_report[0]['CardNo'];

        if ($tbl_employee_monthly_report[0]['Name'])
            $name = $tbl_employee_monthly_report[0]['Name'];
        else
            $name = 'Not Found';

        if ($tbl_employee_monthly_report[0]['Department'])
            $department = $tbl_employee_monthly_report[0]['Department'];
        else
            $department = 'Not Found';

        if ($tbl_employee_monthly_report[0]['Line'])
            $line = $tbl_employee_monthly_report[0]['Line'];
        else
            $line = 'Not Found';






        //echo $card_no.'<br/>'.$department.'<br/>'.$name;

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
                ->setCellValue('A1', 'তারিখ')
                ->setCellValue('B1', 'প্রবেশ সময়')
                ->setCellValue('C1', 'বাহির সময় ')
                ->setCellValue('D1', 'ওটি');

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
        $date = date('M, Y', strtotime($tbl_employee_monthly_report[0]['Date(DateTime)']));
        //echo $date.'<br/>';
        //exit();
//        //wrap context
        //$objPHPExcel->getActiveSheet(0)->getStyle('B1:B' . ($total + 1))->getAlignment()->setWrapText(true);


        $objPHPExcel->getActiveSheet(0)
                ->getHeaderFooter()->setOddHeader("&C&18&K000000&B&UOPEX GROUP, BUILDING NAME : WG4, FLOOR : SDL1\nEmplyee Monthly REPORT(" . $date . ")\n" . $name . ", " . $card_no . ", " . $department . ", " . $line);
        $objPHPExcel->getActiveSheet(0)
                ->getHeaderFooter()->setEvenHeader("&C&18&K000000&B&UOPEX GROUP, BUILDING NAME : WG4, FLOOR : SDL1\nEmplyee Monthly REPORT(" . $date . ")\n" . $name . ", " . $card_no . ", " . $department . ", " . $line);

        $objPHPExcel->getActiveSheet(0)
                ->getHeaderFooter()->setOddFooter("&ROIC\nName : .............\nSIGNATURE : .............\nDATE & TIME : ............. &CHREX\nName : .............\nSIGNATURE : .............\nDATE & TIME : .............&LTIMEKEEPER\nName : .............\nSIGNATURE : .............\nDATE & TIME : .............");
        $objPHPExcel->getActiveSheet(0)
                ->getHeaderFooter()->setEvenFooter("&ROIC\nName : .............\nSIGNATURE : .............\nDATE & TIME : ............. &CHREX\nName : .............\nSIGNATURE : .............\nDATE & TIME : .............&LTIMEKEEPER\nName : .............\nSIGNATURE : .............\nDATE & TIME : .............");

        $objPHPExcel->getActiveSheet(0)->getPageSetup()->setFitToPage(true);
        $objPHPExcel->getActiveSheet(0)->getPageSetup()->setFitToHeight(0);
        $objPHPExcel->getActiveSheet(0)->getPageSetup()->setFitToWidth(1);
        $total_present = 0;
        $total_absent = 0;
        $total_leave = 0;
        $total_ot = 0;
        $total_holiday_work = 0;
        $last_day = date("t", strtotime($first_date));
        $total = $last_day;
        for ($index = 1; $index <= $last_day; $index++) {
            $flag = 0;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . ($index + 1), date('Y-m-d', strtotime($first_date)));
            foreach ($tbl_employee_monthly_report as $a_report) {
                if (date('Y-m-d', strtotime($a_report['Date(DateTime)'])) == date('Y-m-d', strtotime($first_date))) {
                    $flag = 1;
                    break;
                }
            }
            if ($flag) {
                if ($a_report['min(inc.DateTime)']) {
                    //date('H:i:s', strtotime('+6 hours', strtotime($a_report['min(inc.DateTime)'])))
                    $in = strtotime($a_report['min(inc.DateTime)']);
                    $out = strtotime($a_report['max(inc.DateTime)']);
                    date_default_timezone_set('GMT');
                    $diff = abs($out - $in);
                    $hour_diff = date("H:i:s", $diff);
                    $hour_diff = date("H:i:s", strtotime("+15 minutes", strtotime($hour_diff)));

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . ($index + 1), date('H:i:s', strtotime('+6 hours', strtotime($a_report['min(inc.DateTime)']))));
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . ($index + 1), date('H:i:s', strtotime('+6 hours', strtotime($a_report['max(inc.DateTime)']))));
                    if ($a_report['CreatedBy'] == 'Holiday Work') {
                        //holiday work
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . ($index + 1), round(date('H', strtotime($hour_diff)), 0));
                        $total_ot += round(date('H', strtotime($hour_diff)), 0);
                        $total_holiday_work++;
                        cellColor('A'.$index.':D'.$index, 'FF0000');
                    } else {
                        //general day
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . ($index + 1), round(date('H', strtotime($hour_diff)), 0) - 9);
                        $total_ot += round(date('H', strtotime($hour_diff)), 0) - 9;
                        $total_present++;
                    }
                    
                } else {
                    if ($a_report['CreatedBy'] == 'Leave') {
                        $total_leave++;
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . ($index + 1), 'Leave');
                    } else {
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . ($index + 1), 'Holiday');
                    }
                }
            } else {
                $total_absent++;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . ($index + 1), 'Absent');
            }
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A33', 'Total Present');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A34', 'Total Absent');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A35', 'Total OT');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A36', 'Total Leave');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A37', 'HolyDay Work');

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B33', $total_present);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B34', $total_absent);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B35', $total_ot);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B36', $total_leave);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B37', $total_holiday_work);
            //echo $first_date.'<br/>';
            
            $first_date = date('Y-m-d', strtotime('+1 day', strtotime($first_date)));
        }
        //exit();

        $objPHPExcel->getActiveSheet(0)->getStyle('A1:D' . ($total + 1))->applyFromArray($styleArray);
        //$objPHPExcel->getActiveSheet(0)->setAutoFilter('C1:C' . ($total + 1));
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
