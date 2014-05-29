<?php
class Con_set_employee_salary extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('mod_grade_mapping');
        $this->load->helper('alert');
    }
    
    public function create() {
        $data['tbl_job_category'] = $this->mod_grade_mapping->get_job_category_name();
        $data['container'] = 'temp/employee_salary/create';
        $this->load->view('main_page', $data);
    }
}
