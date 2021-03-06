<?php

class Mod_incurrect_access_log extends CI_Model {

    public $CardNo;
    public $DateTime;
    public $Status;
    public $CreatedBy;
    public $CreatedOn;
    public $DelStatus;

    //Insert Query for Course================================================================
//    public function insert() {
//        $data = array(
//            'RewaredName' => $this->RewaredName,
//            'RewaredAmount' => $this->RewaredAmount,
//            'IsCalculatedOnBasic' => $this->IsCalculatedOnBasic,
//            'BasicXTime' => $this->BasicXTime,
//            'CompensatoryHolyday' => $this->CompensatoryHolyday
//        );
//
//        $this->db->insert('tbl_additional_reward_staff', $data);
//    }
    public function insert_batch_random_data($data){
        $this->db->insert_batch('access_log', $data); 
    }

    //Update Query in Course table ===========================================================
    //View Course Information ===================================================
    public function view() {
        $this->db->select('*');
        $this->db->from('access_log'); //incurrect_access_log**
$this->db->where('Status', 0);
        $query = $this->db->get();
        return $query->result();
    }

    public function view_by_id() {
        $this->db->select('*');
        $this->db->from('access_log'); //incurrect_access_log**
$this->db->where('Status', 0);
        $this->db->where('CardNo', $this->CardNo);
        $this->db->where('DateTime BETWEEN '.'"'.date("Y-m-d H:i:s", strtotime($this->DateTime . ' 00:00:01')).'"'.' AND '.'"'.date("Y-m-d H:i:s", strtotime($this->DateTime . ' 23:59:59')).'"',NULL,FALSE);
        $query = $this->db->get();
        return $query->result();
    }

//    public function update() {
//        $data = array(
//            'RewaredName' => $this->RewaredName,
//            'RewaredAmount' => $this->RewaredAmount,
//            'IsCalculatedOnBasic' => $this->IsCalculatedOnBasic,
//            'BasicXTime' => $this->BasicXTime,
//            'CompensatoryHolyday' => $this->CompensatoryHolyday
//        );
//        $this->db->where('ID', $this->ID);
//        if ($this->db->update('access_log', $data)) {
//            return TRUE;
//        }
//        return false;
//    }
    public function getLongData() {
        
        $this->db->select('*');
        $this->db->from('access_log'); //incurrect_access_log**
$this->db->where('Status', 0);
        $this->db->order_by('cardno asc, datetime asc'); 
        $query = $this->db->get();
        return $query->result();
       
    }
    public function EmptyTable($Month){
        echo $Month;
        //exit();
        $query = $this->db->query("DELETE FROM access_log where STATUS = 0 and  DateTime Like '%-".$Month."-%' and `DelStatus` = 'ACT'");
    }
    public function getLongDataArray() {
        
        $this->db->select('*');
        $this->db->from('access_log'); //incurrect_access_log**
$this->db->where('Status', 0);
        $this->db->order_by('cardno asc, datetime asc'); 
        $query = $this->db->get();
        return $query->result_array();
       
    }
    public function getGruoupedData($Month){
        $query = $this->db->query("SELECT * FROM access_log where STATUS = 0 and  DateTime Like '%-".$Month."-%' and DelStatus = 'ACT'   group by Cardno,DateTime");
        return $query->result_array();
    }

}
