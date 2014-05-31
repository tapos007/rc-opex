<?php

class Con_set_employee_info_detail extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('mod_set_employee_info_detail');
        $this->load->helper('alert');
        $this->load->library('pagination');
    }
    
    public function get_job_category() {
        $data['tbl_job_category'] = $this->mod_set_employee_info_detail->get_job_category_name();
        $data['container'] = 'temp/worker_profile/create';
        $this->load->view('main_page', $data);
    }

//    public function search() {
//        $data['container'] = 'temp/daily_report_generate/search';
//        $this->load->view('main_page', $data);
//    }

//    public function employee_information() {
//        
//        $data['tbl_employee_info'] = $this->mod_set_employee_info_detail->view();
//        $data['container'] = 'temp/employee_detail/employee_info_detail';
//        $this->load->view('main_page', $data);
//    }
    
    public function search() {
        $searchterm = $this->searchterm_handler($this->input->get_post('searchterm', TRUE));
        $limit = ($this->uri->segment(3) > 0) ? $this->uri->segment(3) : 0;

        $config['base_url'] = base_url() . 'con_set_employee_info_detail/search';
        $config['total_rows'] = $this->mod_set_employee_info_detail->search_record_count($searchterm);
        $config['per_page'] = 20;
        $config['uri_segment'] = 3;
        $choice = $config['total_rows'] / $config['per_page'];
        $config['num_links'] = round($choice);
        $this->pagination->initialize($config);

        $data['results'] = $this->mod_set_employee_info_detail->search($searchterm, $limit);
        $data['links'] = $this->pagination->create_links();
        $data['searchterm'] = $searchterm;
        $data['error_msg'] = '';
        $data['container'] = 'temp/employee_detail/employee_info_detail';
        $this->load->view('main_page', $data);
    }

    public function example() {
//        echo '<pre>';
//        print_r($this->session->userdata);
//        echo '</pre>';
//        exit();
        $this->benchmark->mark('code_start');
        $searchterm = $this->input->post('searchterm', TRUE);
        if(empty($searchterm)){$this->session->unset_userdata('searchterm');}
        $search_key = $this->searchterm_handler($searchterm);

//        $limit = ($this->uri->segment(3) > 0) ? $this->uri->segment(3) : 0;
//       
//
//        $config['base_url'] = base_url() . 'con_set_employee_info_detail/example';
//        $config['total_rows'] = $this->mod_set_employee_info_detail->all_record_count($search_key);
//        $config['per_page'] = 20;
//        $config['num_links'] = 5;
//        //bootstrap configaration
//        $config['full_tag_open'] = '<ul class="pagination pagination-sm" style="z-index: -1 !importent;">';
//        $config['full_tag_close'] = '</ul>';
//        $config['num_tag_open'] = '<li>';
//        $config['num_tag_close'] = '</li>';
//        $config['cur_tag_open'] = '<li class="active"><span>';
//        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></span></li>';
//        $config['prev_tag_open'] = '<li>';
//        $config['prev_tag_close'] = '</li>';
//        $config['next_tag_open'] = '<li>';
//        $config['next_tag_close'] = '</li>';
//        $config['first_link'] = '&laquo;';
//        $config['prev_link'] = '&lsaquo;';
//        $config['last_link'] = '&raquo;';
//        $config['next_link'] = '&rsaquo;';
//        $config['first_tag_open'] = '<li>';
//        $config['first_tag_close'] = '</li>';
//        $config['last_tag_open'] = '<li>';
//        $config['last_tag_close'] = '</li>';
//        $this->pagination->initialize($config);
//        $data['searchterm'] = $searchterm;
        $data['results'] = $this->mod_set_employee_info_detail->all_record();
//        $data['links'] = $this->pagination->create_links();
//        $this->benchmark->mark('code_end');
        $data['error_msg'] = '';
        $data['container'] = 'temp/employee_detail/employee_info_detail';
        $this->load->view('main_page', $data);
    }

    public function searchterm_handler($searchterm = null) {
        if ($searchterm) {
            $this->session->set_userdata('searchterm', $searchterm);
            return $searchterm;
        } elseif ($this->session->userdata('searchterm')) {
            $searchterm = $this->session->userdata('searchterm');
            return $searchterm;
        } else {
            $this->session->unset_userdata('searchterm');
            $searchterm = "";            
            return $searchterm;
        }
    }
    

//    public function generate_daily_report() {
//        for ($id = 1; $id <= 18000; $id++) {
//            for ($day = 1; $day <= 31; $day++) {
//                $date = '2014-03-' . $day;
////                echo $id . '->' . $date;
////                echo '</br>';
//                $this->mod_access_log->CardNo = '' . $id;
//                $this->mod_access_log->DateTime = date("Y-m-d ", strtotime($date));
//                $data['tbl_access_log'] = $this->mod_access_log->view_by_id();
//                $m = count($data['tbl_access_log']);
//            }
//        }
//        exit();
//        $data['tbl_daily_attendance_log'] = $this->mod_daily_attendance_log->view();
//        $data['container'] = 'temp/daily_report_generate/daily_attendence_view';
//        $this->load->view('main_page', $data);
//    }
//
//    public function view_by_id() {
//        if ($this->input->post('submit') == 'Search') {
//            $this->mod_access_log->CardNo = $this->input->post('CardNo');
//            $this->mod_access_log->DateTime = date("Y-m-d ", strtotime($this->input->post('DateTime')));
//            $data['tbl_access_log'] = $this->mod_access_log->view_by_id();
//            $m = count($data['tbl_access_log']);
//            if (!($m % 2) == 0)
//                $data['msg'] = 'This User has some problems with access';
//            $data['container'] = 'temp/daily_report_generate/view';
//            $this->load->view('main_page', $data);
//        }
//    }
//
//    public function edit() {
//        if ($this->input->post('submit') == 'edit') {
//            $this->mod_access_log->ID = $this->input->post('ID');
//            $data['tbl_log'] = $this->mod_access_log->view_by_id();
//            $data['container'] = 'temp/log/edit';
//            $this->load->view('temp/main_page', $data);
//        }
//        if ($this->input->post('submit') == 'delete') {
//            $this->delete();
//        }
//    }

}

?>
