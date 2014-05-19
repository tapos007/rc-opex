<?php

class Mod_set_employee_info_detail extends CI_Model {

    //View Information ===================================================
    public function view() {
        $this->db->select('*');
        $this->db->from('tbl_employee_profile');
        $query = $this->db->get();
        return $query->result();
    }
    public function view_by_cardno_array($cardNo){
        $this->db->select('*');
        $this->db->from('tbl_employee_profile');
        $this->db->where('CardNo', $cardNo);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_job_category_name() {
        $this->db->select('*');
        $this->db->from('tbl_job_category');
        $query = $this->db->get();
        return $query->result();
    }

    public function EmployeeInfoArray() {
        $this->db->select('*');
        $this->db->from('tbl_employee_profile');
        $this->db->order_by('CardNo', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function search_record_count($searchterm) {
        $sql = "SELECT COUNT(*) As cnt FROM tbl_employee_profile WHERE CardNo  OR ContactNo LIKE '%" . $searchterm . "%'";
        $q = $this->db->query($sql);
        $row = $q->row();
        return $row->cnt;
    }

    public function all_record() {
        if ($this->session->userdata('Role') == 'Admin') {
            $sql = $this->db->query("SELECT * FROM tbl_employee_profile WHERE BuildingName = '" . $this->session->userdata('BuildingName') . "' ");
            return $sql->result();
        } else {
            $sql = $this->db->query("SELECT * FROM tbl_employee_profile  WHERE BuildingName = '" . $this->session->userdata('BuildingName') . "' AND Floor = '" . $this->session->userdata('Floor') . "' ");
            return $sql->result();
        }
    }

    public function all_record_count($searchterm) {
        if ($searchterm) {
            $sql = "SELECT count(*) FROM tbl_employee_profile WHERE CardNo='" . $searchterm . "' OR ContactNo='" . $searchterm . "'";
        } else {
            $sql = "SELECT * FROM tbl_employee_profile";
        }

        $result = $this->db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->num_rows();
        } else {
            return false;
        }
    }

    public function search($searchterm, $limit) {
        $sql = "SELECT * FROM tbl_employee_profile WHERE CardNo  OR ContactNo LIKE '%" . $searchterm . "%' LIMIT " . $limit . ",20";
        $q = $this->db->query($sql);
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        } else {
            return 0;
        }
    }

    public function specific_employee_information($buildingName, $floor) {
        $this->db->select('*');
        $this->db->from('tbl_employee_profile');
        $this->db->where('BuildingName', $buildingName);
        $this->db->where('Floor', $floor);
        $query = $this->db->get();
        return $query->result();
    }

    public function specific_employee_information_array($buildingName, $floor) {
        $this->db->select('*');
        $this->db->from('tbl_employee_profile');
        $this->db->where('BuildingName', $buildingName);
        $this->db->where('Floor', $floor);
        $this->db->order_by('CardNo', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function specific_employee_information_report($buildingName, $floor, $department, $line) {
        $this->db->select('*');
        $this->db->from('tbl_employee_profile');
        $this->db->like('BuildingName', $buildingName);
        $this->db->like('Floor', $floor);
        $this->db->like('Department', $department);
        $this->db->like('Line', $line);
        $query = $this->db->get();
        return $query->result();
    }

    public function specific_employee_information_report_array($buildingName, $floor, $department, $line) {
        $this->db->select('*');
        $this->db->from('tbl_employee_profile');
        $this->db->like('BuildingName', $buildingName);
        $this->db->like('Floor', $floor);
        $this->db->like('Department', $department);
        $this->db->like('Line', $line);
        $query = $this->db->get();
        return $query->result_array();
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

    public function regular_employee_cardno_list($CurrentMonth, $PreviousMonth) {
        $query = $this->db->query("SELECT al.CardNo, al.DateTime, pro.Name, pro.Line, pro.Department, pro.Floor FROM tbl_access_log as al INNER JOIN tbl_employee_profile AS pro ON pro.CardNo = al.CardNo WHERE al.DateTime LIKE '%" . $CurrentMonth . "%' OR al.DateTime LIKE '%" . $PreviousMonth . "%' GROUP BY al.CardNo ORDER BY pro.Floor ASC, pro.Department, pro.Line");
        return $query->result();
    }

    public function attendance_log($DateTime) {
        $query = $this->db->query("SELECT CardNo FROM tbl_access_log WHERE DateTime like '%" . $DateTime . "%' group by CardNo");
        return $query->result();
    }

    public function incorrect_access_log($startdate, $enddate) {
        $query = $this->db->query("SELECT CardNo, DateTime FROM tbl_incurrect_access_log_backup WHERE DateTime BETWEEN '" . $startdate . "' and '" . $enddate . "' GROUP BY CardNo");
        return $query->result();
    }

    public function get_department_name($buildingName, $floor) {
        $query = $this->db->query("SELECT section.Name 
                                    FROM `tbl_section` as section 
                                    inner join tbl_floor as floor
                                    on section.FloorID = floor.ID
                                    inner join tbl_building as building
                                    on floor.BuildingID = building.ID
                                    WHERE building.Name = '" . $buildingName . "' and floor.Name = '" . $floor . "' ");
        return $query->result();
    }
    public function getAllsection() {
        $query = $this->db->get('tbl_section');
        return $query->result();
    }
    

}
