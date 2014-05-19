<?php

class Con_pro_first_half_attendance_log extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('mod_pro_daily_first_half_attn_log');
        $this->load->model('mod_monthly_wages_detail');
        $this->load->model('mod_buil_sec_other');
        $this->load->model('mod_pro_attn_mismatch_report');
    }

    public function index() {        
        date_default_timezone_set('Asia/Dacca');
        $now = date('Y-m-d', now());
        $StartDate = $now . ' 00:00:01';
        $EndDate = $now . ' 10:59:59';
        $StartDate = date('Y-m-d H:i:s', strtotime($StartDate));
        $EndDate = date('Y-m-d H:i:s', strtotime($EndDate));
        $access_log = $this->mod_pro_daily_first_half_attn_log->access_log($StartDate, $EndDate);                     
        $data['tbl_first_half_log_report'] = $access_log;
        $data['container'] = 'temp/daily_attendance_log_report/first_half_attendence_log_view';
        $this->load->view('main_page', $dasta);
    }

    public function get_department_name() {
        $BuildingName = $this->input->post('Building');
        $Floor = $this->input->post('Floor');
        $DepartmentName = $this->mod_pro_daily_first_half_attn_log->get_department_by_name($BuildingName, $Floor);
        echo json_encode($DepartmentName);
    }

    public function get_line_name() {
        $BuildingName = $this->input->post('Building');
        $Floor = $this->input->post('Floor');
        $DepartmentName = $this->input->post('DepartmentSection');
        $LineName = $this->mod_pro_daily_first_half_attn_log->get_line_by_name($BuildingName, $Floor, $DepartmentName);
        echo json_encode($LineName);
    }

    public function search() {                                             
        $mydate = $this->input->post('Date');
        $now = date('Y-m-d', strtotime(str_replace('-', '/', $mydate)));
        $StartDate = $now . ' 00:00:01';
        $EndDate = $now . ' 23:59:59';
        $StartDate = date('Y-m-d H:i:s', strtotime($StartDate));
        $EndDate = date('Y-m-d H:i:s', strtotime($EndDate));
        $access_log = $this->mod_pro_daily_first_half_attn_log->access_log_previous_date($StartDate, $EndDate);        
        $data['tbl_first_half_log_report'] = $access_log;
        
        $data['container'] = 'temp/daily_attendance_log_report/first_half_attendence_log_view';
        $this->load->view('main_page', $data);
    }

    public function serach_ajax() {
        
    }

    public function retrieve_employee_information($card_no, $myvalue) {
        //$count = 0;
        $first_half_access_report = array();
        foreach ($myvalue as $rec_employee_info) {
            if ($card_no == $rec_employee_info->CardNo) {
                $first_half_access_report['Name'] = $rec_employee_info->Name;
                $first_half_access_report['CardNo'] = $rec_employee_info->CardNo;
                $first_half_access_report['Department'] = $rec_employee_info->Department;
                $first_half_access_report['Line'] = $rec_employee_info->Line;
                return $first_half_access_report;
            }
        }
//        $first_half_access_report['Name'] = 'Not Found';
//        $first_half_access_report['CardNo'] = 'Not Found';        
//        $first_half_access_report['Department'] = 'Not Found';
//        $first_half_access_report['Line'] = 'Not Found';        
//        return $first_half_access_report;
    }

}
