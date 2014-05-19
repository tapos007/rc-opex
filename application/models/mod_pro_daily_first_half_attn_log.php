<?php

class Mod_pro_daily_first_half_attn_log extends CI_Model {

    public function specific_employee_information($buildingName, $floor, $department) {
        $this->db->select('*');
        $this->db->from('tbl_employee_profile');
        $this->db->where('BuildingName', $buildingName);
        $this->db->where('Floor', $floor);
        $this->db->where('Department', $department);
        $query = $this->db->get();
        return $query->result();
    }
    
    public function SuperAdminEmployeeInfo() {
        $this->db->select('*');
        $this->db->from('tbl_employee_profile');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function specific_employee_information_report($buildingName, $floor, $department, $line) {
        $this->db->select('*');
        $this->db->from('tbl_employee_profile');
        $this->db->where('BuildingName', $buildingName);
        $this->db->where('Floor', $floor);
        $this->db->where('Department', $department);        
        $this->db->where('Line', $line);
        $query = $this->db->get();
        return $query->result();
    }

    
    //SELECT cardno, Intime FROM `tbl_access_log_raw_backup` WHERE intime BETWEEN '2014-03-03 00:00:01' AND '2014-03-03 11:59:59' GROUP BY cardno
//    public function incorrect_access_log($startdate, $enddate) {
//        $query = $this->db->query("SELECT CardNo, InTime FROM tbl_access_log_raw_backup WHERE InTime BETWEEN '" . $startdate . "' and '" . $enddate . "' GROUP BY CardNo");
//        return $query->result();
//    }
    
    public function access_log($startdate, $enddate) {
        $query = $this->db->query("SELECT CardNo, InTime FROM `tbl_access_log_raw` WHERE InTime BETWEEN '" . $startdate . "' and '" . $enddate . "' GROUP BY CardNo");
//        echo '<pre>';
//        print_r($query->result_array());
//        echo '</pre>';
//        exit();
        return $query->result();
    }

    public function get_line_by_name($building, $floor, $Department) {
        $this->db->distinct();
        $this->db->select('Line');
        $this->db->from('tbl_employee_profile');
        $this->db->where('BuildingName', $building);
        $this->db->where('Floor', $floor);
        $this->db->where('Department', $Department);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_department_by_name($building, $floor) {
        $this->db->distinct();
        $this->db->select('Department');
        $this->db->from('tbl_employee_profile');
        if (!empty($building)) {
            $this->db->where('BuildingName', $building);
        }
        $this->db->where('Floor', $floor);
        $query = $this->db->get();
        return $query->result();
    }

}
