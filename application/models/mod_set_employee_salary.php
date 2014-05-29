<?php

class Mod_set_employee_salary extends CI_Model {

    public $ID;
    public $CardNo;
    public $Designation;
    public $Grade;
    public $GrossSalary;
    public $LastIncrementDate;
    public $LastIncrementMoney;
    public $PromotionDate;
    public $OT;
    public $AttendanceBonus;
    public $OtherAllowance;
    public $MedicalAllowance;
    public $TravelAllowance;
    public $FoodAllowance;
    public $OthAllowCal;
    public $IsActive;

    public function insert() {
        $data = array(
            'CardNo' => $this->CardNo,
            'Designation' => $this->Designation,
            'Grade' => $this->Grade,
            'GrossSalary' => $this->GrossSalary,
            'LastIncrementDate' => $this->LastIncrementDate,
            'LastIncrementMoney' => $this->LastIncrementMoney,
            'PromotionDate' => $this->PromotionDate,
            'OT' => $this->OT,
            'AttendanceBonus' => $this->AttendanceBonus,
            'OtherAllowance' => $this->OtherAllowance,
            'MedicalAllowance' => $this->MedicalAllowance,
            'TravelAllowance' => $this->TravelAllowance,
            'FoodAllowance' => $this->FoodAllowance,
            'OthAllowCal' => $this->OthAllowCal,
            'IsActive' => $this->IsActive
        );
        $this->db->insert('tbl_employee_salary', $data);
    }
    
    public function update() {
        $data = array(
            'Designation' => $this->Designation,
            'Grade' => $this->Grade,
            'GrossSalary' => $this->GrossSalary,
            'LastIncrementDate' => $this->LastIncrementDate,
            'LastIncrementMoney' => $this->LastIncrementMoney,
            'PromotionDate' => $this->PromotionDate,
            'OT' => $this->OT,
            'AttendanceBonus' => $this->AttendanceBonus,
            'OtherAllowance' => $this->OtherAllowance,
            'MedicalAllowance' => $this->MedicalAllowance,
            'TravelAllowance' => $this->TravelAllowance,
            'FoodAllowance' => $this->FoodAllowance,
            'OthAllowCal' => $this->OthAllowCal,
            'IsActive' => $this->IsActive
        );
        $this->db->where('CardNo', $this->CardNo);
        $this->db->update('tbl_employee_salary', $data);
    }
    
    public function view_by_cardno() {
        $this->db->select('*');
        $this->db->from('tbl_employee_salary');
        $this->db->where('CardNo', $this->CardNo);
        $query = $this->db->get();
        return $query->row_array();
    }

}
