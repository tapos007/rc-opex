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
        //714

        date_default_timezone_set('Asia/Dacca');
        $StartDate = date('Y-m-d', now()); //date('Y-m-d', strtotime(now()));

        $attendance_list = $this->mod_access_log->getDateSpecificLongData($StartDate);
        echo '<pre>';
        print_r($attendance_list);
        echo '</pre>';
        exit();
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

        $data['tbl_absent_report'] = $absent_employee_list;
        $data['container'] = 'temp/daily_absent_report/daily_absent_report_ui';
        $this->load->view('main_page', $data);
    }

    public function CheckAttendance($card_no, $attendance_list) {
        $limit = count($attendance_list) - 1;
        for ($index = 0; $index <= $limit; $index++) {
            if ($card_no == $attendance_list[$index]['CardNo'])
                return TRUE;
        }
        return FALSE;
    }

    public function search() {
        if ($this->session->userdata('Role') == 'Admin') {
            $employee_details = $this->mod_pro_daily_first_half_attn_log->SuperAdminEmployeeInfo();
        } else {
            //if ($this->session->userdata('Floor') == $this->input->post('Floor')) {
            $BuildingName = $this->input->post('Building');
            $Floor = $this->input->post('Floor');
            $DepartmentName = $this->input->post('DepartmentSection');
            $Line = $this->input->post('LineUnit');
            $Date = $this->input->post('Date');
            $employee_details = $this->mod_set_employee_info_detail->specific_employee_info_array($BuildingName, $Floor, $DepartmentName, $Line);
//                echo '<pre>';
//                print_r($employee_details);                
//                echo '<pre>';
//                exit();
            // }
        }
        $StartDate = date('Y-m-d', strtotime($Date));

//        echo $StartDate;
//        exit();
        $attendance_list = $this->mod_access_log_raw->getDateSpecificLongData($StartDate);
//                echo '<pre>';
//        print_r($attendance_list);
//        echo '</pre>';
//        exit();
        $limit1 = count($employee_details) - 1;
        $absent_employee_list = array();
        for ($index1 = 0; $index1 <= $limit1; $index1++) {
            $absent_employee['CardNo'] = $employee_details[$index1]['CardNo'];
            $absent_employee['Name'] = $employee_details[$index1]['Name'];
            $absent_employee['BuildingName'] = $employee_details[$index1]['BuildingName'];
            $absent_employee['Floor'] = $employee_details[$index1]['Floor'];
            $absent_employee['Department'] = $employee_details[$index1]['Department'];
            $absent_employee['Line'] = $employee_details[$index1]['Line'];
            if (!($this->CheckAttendance($employee_details[$index1]['CardNo'], $attendance_list))) {
                array_push($absent_employee_list, $absent_employee);
            }
        }


//        $StartDate = '2014-03-04'; //date('Y-m-d', strtotime(now()));
//        $CurrentMonth = '2014-03'; //date('Y-m', strtotime(now()));
//        $PreviousMonth = '2014-02'; //date('Y-m', (strtotime(now())));
        //$EndDate = date('Y-m-d H:i:s', strtotime('-1 day', now()));
        //$regular_employee_cardno_list = $this->mod_set_employee_info_detail->regular_employee_cardno_list($CurrentMonth, $PreviousMonth);
        //$attendance_list = $this->mod_set_employee_info_detail->attendance_log($StartDate);
//        $att_list = array();
//        $att_list_all = array();
//        foreach ($attendance_list as $att) {
//            $att_list['CardNo'] = $att->CardNo;
//            array_push($att_list_all, $att_list);
//        }
//        $absent_list = array();
//        $absent_array = array();
//        foreach ($regular_employee_cardno_list as $cardno_list) {
//            if ($this->check_array($cardno_list->CardNo, $att_list_all) == FALSE) {
//                $absent_list['CardNo'] = $cardno_list->CardNo;
//                $absent_list['Name'] = $cardno_list->Name;
//                $absent_list['Floor'] = $cardno_list->Floor;
//                $absent_list['Department'] = $cardno_list->Department;
//                $absent_list['Line'] = $cardno_list->Line;
//                $absent_list['DateTime'] = $cardno_list->DateTime;
//
//                array_push($absent_array, $absent_list);
//            }
//        }
//        echo '<pre>';
//        print_r($this->input->post());
//        print_r($employee_details);
//        echo '</pre>';
//        exit();

        if ($this->session->userdata('Role') == 'Admin') {
            $data['tbl_absent_report'] = $absent_employee_list;
            $data['container'] = 'temp/daily_absent_report/daily_absent_report_ui_admin';
            $this->load->view('main_page', $data);
        } else {
            $data['tbl_absent_report'] = $absent_employee_list;
            $data['container'] = 'temp/daily_absent_report/daily_absent_report_ui';
            $this->load->view('main_page', $data);
        }
//redirect('con_pro_attn_mismatch_report/index', 'refresh');
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
        //$accessLogRawData = array();
        $accessLogRawData['CardNo'] = $this->input->post('CardNo');
        $inTime = $this->input->post('InTime');
        $accessLogRawData['Ip'] = $this->session->userdata('Email');
        date_default_timezone_get('Asia/Dacca');
        $date = date('Y-m-d', now());
        $inTime = $date . ' ' . $inTime;
        $accessLogRawData['InTime'] = date('Y-m-d H:i:s', strtotime($inTime));
        $this->mod_access_log_raw->insert($accessLogRawData);
        redirect('con_pro_daily_absent_report/');
//        echo '<pre>';
//        print_r($inTime);
//        echo '</pre>';
//        exit();
    }

}
