<?php

class Con_pro_employee_monthly_report extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('mod_pro_employee_monthly_report');
        $this->load->model('mod_access_log');
        $this->load->model('mod_leave_detail');
        $this->load->model('mod_pro_attn_mismatch_report');
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
        $Month = $this->input->post('Month');

        $DateTime = date('Y-m-d H:i:s', strtotime('-6 hours', strtotime($this->input->post('DateTime'))));
        $DateTimeOld = date('Y-m-d H:i:s', strtotime('-6 hours', strtotime($this->input->post('DateTimeOld'))));


        $this->mod_pro_employee_monthly_report->update_in_out_time($CardNo, $DateTime, $DateTimeOld);
        $data['tbl_employee_monthly_report'] = $this->mod_pro_employee_monthly_report->view_by_CardNo($CardNo, $Month);
        $data['tbl_employee_monthly_leave_report'] = $this->mod_leave_detail->view_by_CardNo($CardNo, $Month);
        $data['container'] = 'temp/employee_monthly_report/view';
        $data['tbl_employee_monthly_missmatch_report'] = $this->mod_pro_employee_monthly_report->view_by_CardNo_missmatch($CardNo, $Month);
        $this->load->view('main_page', $data);
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

    public function Leave_Details_Delete($cardNo,$date) {        
        $this->mod_leave_detail->LeaveEntryDelete($cardNo, $date);
        //update tbl_leave_type_allocation
        
        $this->search_get($cardNo, date('m',strtotime($date)));
    }

}
