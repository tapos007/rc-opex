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
        $now = date('Y-m-d', now());
        $StartDate = $now . ' 00:00:01';
        $EndDate = $now . ' 10:59:59';
        $StartDate = date('Y-m-d H:i:s', strtotime($StartDate));
        $EndDate = date('Y-m-d H:i:s', strtotime($EndDate));
        $access_log = $this->mod_pro_daily_first_half_attn_log->access_log($StartDate, $EndDate);

        $access_log_detail = array();
        $first_half_access_report = array();
        //$access_log_detail = $incorrect_access_log;

        foreach ($access_log as $an_access_log) {
            foreach ($employee_details as $an_employee_details) {
                if ($an_access_log->CardNo == $an_employee_details->CardNo) {
                    $access_log_detail['CardNo'] = $an_access_log->CardNo;
                    $access_log_detail['InTime'] = $an_access_log->InTime;
                    $access_log_detail['Name'] = $an_employee_details->Name;
                    $access_log_detail['BuildingName'] = $an_employee_details->BuildingName;
                    $access_log_detail['Floor'] = $an_employee_details->Floor;
                    $access_log_detail['Department'] = $an_employee_details->Department;
                    $access_log_detail['Line'] = $an_employee_details->Line;
                    array_push($first_half_access_report, $access_log_detail);
                }
            }
        }
        $data['tbl_first_half_log_report'] = $first_half_access_report;
        $data['container'] = 'temp/daily_attendance_log_report/first_half_attendence_log_view';
        $this->load->view('main_page', $data);
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


        $mydate = $this->input->post('Date');
        $now = date('Y-m-d', strtotime(str_replace('-', '/', $mydate)));
        $StartDate = $now . ' 00:00:01';
        $EndDate = $now . ' 23:59:59';
        $StartDate = date('Y-m-d H:i:s', strtotime($StartDate));
        $EndDate = date('Y-m-d H:i:s', strtotime($EndDate));
        $access_log = $this->mod_pro_daily_first_half_attn_log->access_log_previous_date($StartDate, $EndDate);

        $access_log_detail = array();
        $first_half_access_report = array();

        foreach ($access_log as $an_access_log) {
            foreach ($employee_details as $an_employee_details) {
                if ($an_access_log->CardNo == $an_employee_details->CardNo) {
                    $access_log_detail['CardNo'] = $an_access_log->CardNo;
                    $access_log_detail['InTime'] = $an_access_log->DateTime;
                    $access_log_detail['Name'] = $an_employee_details->Name;
                    $access_log_detail['BuildingName'] = $an_employee_details->BuildingName;
                    $access_log_detail['Floor'] = $an_employee_details->Floor;
                    $access_log_detail['Department'] = $an_employee_details->Department;
                    $access_log_detail['Line'] = $an_employee_details->Line;
                    array_push($first_half_access_report, $access_log_detail);
                }
            }
        }

        //Pagination section start

        $total_rows = count($first_half_access_report);
        $per_page = 15;
        $config['base_url'] = 'http://localhost/gurugreeho_localhost1/con_set_category/index';
        $config['total_rows'] = $this->mod_set_category->number_of_categories();
        $config['per_page'] = 10;
        $config['num_links'] = 10;
        $config['prev_link'] = '&laquo;';
        $config['next_link'] = '&raquo;';
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = ' </ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_open'] = '</li>';
        $config['last_link'] = FALSE;
        $config['first_link'] = FALSE;
        $this->pagination->initialize($config);
        if ($this->mod_set_category->number_of_categories() > 0) {
            $data['tbl_category'] = $this->mod_set_category->view_for_list($config['per_page'], $this->uri->segment(3));
        }

        //Pagination section end

        $data['tbl_first_half_log_report'] = $first_half_access_report;
        $data['container'] = 'temp/daily_attendance_log_report/first_half_attendence_log_view';
        $this->load->view('main_page', $data);
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
