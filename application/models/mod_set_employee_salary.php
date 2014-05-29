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
            'OthAllowCal' => $this->OthAllowCal,
            'IsActive' => $this->IsActive
        );
        $this->db->insert('tbl_employee_salary', $data);
    }

}
