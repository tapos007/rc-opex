<?php

ini_set('max_execution_time', 6000);
ini_set('memory_limit', '512M');

class Con_proc_daily_report_generate extends CI_Controller {

    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('mod_access_log');
        $this->load->model('mod_access_log_raw');
        $this->load->model('mod_access_log_raw_backup');
        $this->load->model('mod_access_log_backup');
        $this->load->model('mod_incurrect_access_log');
        $this->load->model('mod_incurrect_access_log_backup');
        $this->load->model('mod_daily_attendance_log');
        $this->load->model('mod_set_work_hour_breakdown');
//        $this->load->model('mod_monthly_wages_detail');
        $this->load->helper('alert');
        $this->load->helper('date');
    }
//
//    public function getInOutTimeFromTblAccessLog($date, $card_specific_tbl_access_log) {
////        echo $date;
////        echo '<pre>';
////        print_r($card_specific_tbl_access_log);
////        echo '</pre>';
//        $dates = array();
//        $limit = count($card_specific_tbl_access_log) - 1;
//        for ($index = 0; $index <= $limit; $index++) {
//            if (date('Y-m-d', strtotime($card_specific_tbl_access_log[$index]['DateTime'])) == date('Y-m-d', strtotime($date))) {
//                array_push($dates, $card_specific_tbl_access_log[$index]['DateTime']);
//                //echo 'Paisi';
//            }
//        }
//        return $dates;
//    }
//
//    public function DailyAccessLog() {
//        //SELECT * FROM `tbl_access_log_raw` where Intime between '2014-4-1 00:00:01' and '2014-4-30 23:59:59'
//        $cardNo = 8002;
//        $month = 4;
//        $this->mod_access_log_raw->TruncateInvalidData();
//        $days = $this->mod_access_log_raw->MonthSpecificGetDistinctDates($month);
//        $card_specific_tbl_access_log = $this->mod_access_log->CardSpecificMonthData($cardNo, $month);
//        $card_specific_tbl_incurrect_access_log = $this->mod_incurrect_access_log->CardSpecificMonthData($cardNo, $month);
//        $card_specific_daily_access_log = array();
//        $limit = count($days) - 1;
//        for ($index = 0; $index <= $limit; $index++) {
//            //echo $days[$index]['DATE(  `InTime` )'].'<br/>';
//            $a_card_specific_daily_access_log['Date'] = $days[$index]['DATE(  `InTime` )'];
//            $dates = $this->getInOutTimeFromTblAccessLog($days[$index]['DATE(  `InTime` )'], $card_specific_tbl_access_log);
//            echo '<pre>';
//            print_r($dates);
//            echo '</pre>';
//        }
//    }

    public function search() {
        $data['container'] = 'temp/daily_report_generate/search';
        $this->load->view('main_page', $data);
    }

//    public function generateData() {
//        $data_tbl_access_log_array = array();
//        for ($index = 1; $index <= 200; $index++) {
//            $data_tbl_access_log['CardNo'] = $index;
//            $data_tbl_access_log['DateTime'] = date("Y-m-d H:i:s", mt_rand(strtotime("2014-03-22 21:00:00"), strtotime("2014-03-22 21:30:00")));
//            $data_tbl_access_log['Status'] = "OUT";
//            $data_tbl_access_log['CreatedBy'] = "SYSTEM";
//            //$data_tbl_access_log['CreatedOn'];
//            $data_tbl_access_log['DelStatus'] = "ACT";
//            array_push($data_tbl_access_log_array, $data_tbl_access_log);
//        }
//        $this->mod_access_log->insert_batch_random_data($data_tbl_access_log_array);
//        echo 'Random Data Inserted Successfully';
//    }

    public function AttendaceSheet() {
        $con = mysqli_connect("localhost", "root", "", "wages_manegement");
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        $result = mysqli_query($con, "SELECT COUNT(distinct cardno ) as 'total' FROM tbl_access_log_raw ");

        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit();
        } else {

            echo 'Total employee present : ' . $result->fetch_object()->total . '<br/>';
        }
        mysqli_close($con);
    }

    public function SeperateValidData() {
        $this->mod_access_log_raw->TruncateInvalidData();
        $days = $this->mod_access_log_raw->GetDistinctDates();
        $limit = count($days) - 1;
        $tbl_access_log = array();
        $tbl_incurrect_access_log = array();
        for ($index = 0; $index <= $limit; $index++) {
            $rows = $this->mod_access_log_raw->getDateSpecificLongDataArray($days[$index]['DATE(  `InTime` )']);
            $limit1 = count($rows) - 1;
            //echo $limit1 . '<br/>';
            for ($index1 = 0; $index1 <= $limit1; $index1++) {
                $inTime = $outTime = $rows[$index1]['InTime'];
                while (($index1 != $limit1) && ($rows[$index1]['CardNo'] == $rows[$index1 + 1]['CardNo'])) {
                    if (date('Y-m-d H:i:s', strtotime($inTime)) > date('Y-m-d H:i:s', strtotime($rows[$index1 + 1]['InTime']))) {
                        $inTime = $rows[$index1 + 1]['InTime'];
                    }
                    if (date('Y-m-d H:i:s', strtotime($outTime)) < date('Y-m-d H:i:s', strtotime($rows[$index1 + 1]['InTime']))) {
                        $to_time = strtotime($inTime);
                        $from_time = strtotime($rows[$index1 + 1]['InTime']);
                        if ((round(abs($to_time - $from_time) / 60, 2)) > 5) {
                            $outTime = $rows[$index1 + 1]['InTime'];
                        }
                    }
                    $index1++;
                }
                if ($inTime == $outTime) {
                    $an_incurrect_access_log['CardNo'] = $rows[$index1]['CardNo'];
                    
                    $an_incurrect_access_log['DateTime'] = date('Y-m-d H:i:s', strtotime('-6 hours', strtotime($inTime)));
                    $an_incurrect_access_log['Status'] = 'IN';
                    $an_incurrect_access_log['CreatedBy'] = 'SYSTEM';
                    $an_incurrect_access_log['DelStatus'] = 'ACT';
                    array_push($tbl_incurrect_access_log, $an_incurrect_access_log);
                    //echo 'Invalid-->' . $rows[$index1]['CardNo'] . '<br/>';
                } else {
                    $an_access_log['CardNo'] = $rows[$index1]['CardNo'];
                    $an_access_log['DateTime'] = date('Y-m-d H:i:s', strtotime('-6 hours', strtotime($inTime)));
                    $an_access_log['Status'] = 'IN';
                    $an_access_log['CreatedBy'] = 'SYSTEM';
                    $an_access_log['DelStatus'] = 'ACT';
                    array_push($tbl_access_log, $an_access_log);
                    //echo 'Valid-->' . $rows[$index1]['CardNo'] . '<br/>';
                    $an_access_log['CardNo'] = $rows[$index1]['CardNo'];
                    $an_access_log['DateTime'] = date('Y-m-d H:i:s', strtotime('-6 hours', strtotime($outTime)));
                    $an_access_log['Status'] = 'OUT';
                    $an_access_log['CreatedBy'] = 'SYSTEM';
                    $an_access_log['DelStatus'] = 'ACT';
                    array_push($tbl_access_log, $an_access_log);
                }
            }
        }
        if (!empty($tbl_access_log))
            $this->mod_access_log->insert_batch_random_data($tbl_access_log);
        if (!empty($tbl_incurrect_access_log))
            $this->mod_incurrect_access_log->insert_batch_random_data($tbl_incurrect_access_log);
        echo 'Inserted';
//        echo 'Valid-->' . count($tbl_access_log) . '<br/>';
//        echo 'Invalid-->' . count($tbl_incurrect_access_log) . '<br/>';
    }

    public function view_daily_report() {
        $data['tbl_daily_attendance_log'] = $this->mod_daily_attendance_log->view();
        $data['container'] = 'temp/daily_report_generate/daily_attendence_view';
        $this->load->view('main_page', $data);
    }

    public function generate_daily_report() {
        $days = $this->mod_access_log->GetDistinctDates();
        $limt = count($days) - 1;
        for ($index = 0; $index <= $limt; $index++) {
            $data_tbl_daily_whole = array();
            $data['tbl_work_hour_breakdown'] = $this->mod_set_work_hour_breakdown->view1();
            $data['tbl_access_log'] = $this->mod_access_log->getDateSpecificLongData($days[$index]['DATE(`DateTime`)']);
            $flag = TRUE;
            $one_flag = TRUE;
            $total_hour_worked = date("H:i:s", 0);
            $data_tbl_daily['TotalWorkedHour'] = date("H:i:s", 0);
            $data_tbl_daily['GenarelWorkHour'] = date("H:i:s", 0);
            $data_tbl_daily['OverTimeHour'] = date("H:i:s", 0);
            $data_tbl_daily['AdditionalOverTimeHour'] = date("H:i:s", 0);
            $data_tbl_daily['NihgtShiftOverTimeHour'] = date("H:i:s", 0);
            $data_tbl_daily['InTime'] = 0;
            foreach ($data['tbl_access_log'] as $rec_access_log) {
                if ($one_flag) {
                    $previous = $rec_access_log;
                    $current = $rec_access_log;
                    $date = explode(' ', $previous->DateTime);
                    $in_time = $date[0] . ' ' . '02:20:00';
                    $night_start_time = $date[0] . ' ' . '17:59:59';
                    if (strtotime($previous->DateTime) < strtotime($in_time))
                        $data_tbl_daily['InTime'] = 1;
                    $one_flag = FALSE;
                } else {
                    if ($flag) {//even numbered row
                        $current = $rec_access_log;
                        $in = strtotime($previous->DateTime);
                        $out = strtotime($current->DateTime);
                        date_default_timezone_set('GMT');
                        $diff = abs($out - $in);
                        //echo $diff . '<br/>';
                        $hour_diff = date("H:i:s", $diff);
                        $total_hour_worked+=$hour_diff;
                        $flag = FALSE;
                    } else {//odd no row
                        $previous = $rec_access_log;

                        if (($total_hour_worked != 0) && ($previous->CardNo != $current->CardNo)) {
                            $data_tbl_daily['Date'] = date("Y-m-d ", strtotime($current->DateTime));
                            $data_tbl_daily['CardNo'] = $current->CardNo;
                            $data_tbl_daily['TotalWorkedHour'] = $total_hour_worked;

                            if ($total_hour_worked > date("H:i:s", 32400)) {
                                $data_tbl_daily['GenarelWorkHour'] = date("H:i:s", 32400);
                                $total_hour_worked = $total_hour_worked - date("H:i:s", 32400);
                            } else {
                                $data_tbl_daily['GenarelWorkHour'] = $total_hour_worked;
                                $total_hour_worked = date("H:i:s", 0);
                            }

                            if ($total_hour_worked > date("H:i:s", 10800)) {
                                $data_tbl_daily['OverTimeHour'] = date("H:i:s", 10800);
                                $data_tbl_daily['OT'] = TRUE;
                                $total_hour_worked = $total_hour_worked - date("H:i:s", 10800);
                            } else {
                                $data_tbl_daily['OverTimeHour'] = $total_hour_worked;
                                $total_hour_worked = date("H:i:s", 0);
                                if ($data_tbl_daily['OverTimeHour'] > 0)
                                    $data_tbl_daily['OT'] = TRUE;
                                else
                                    $data_tbl_daily['OT'] = FALSE;
                            }

                            if ($total_hour_worked > date("H:i:s", 10800)) {
                                $data_tbl_daily['AdditionalOverTimeHour'] = date("H:i:s", 10800);
                                $total_hour_worked = $total_hour_worked - date("H:i:s", 10800);
                                $data_tbl_daily['AOT'] = TRUE;
                            } else {
                                $data_tbl_daily['AdditionalOverTimeHour'] = $total_hour_worked;
                                $total_hour_worked = date("H:i:s", 0);
                                if ($data_tbl_daily['AdditionalOverTimeHour'] > 0)
                                    $data_tbl_daily['AOT'] = TRUE;
                                else
                                    $data_tbl_daily['AOT'] = FALSE;
                            }

                            if ($total_hour_worked > date("H:i:s", 7200)) {
                                $data_tbl_daily['NihgtShiftOverTimeHour'] = $total_hour_worked;
                                $total_hour_worked = $total_hour_worked - date("H:i:s", 7200);
                                $data_tbl_daily['Night'] = TRUE;
                            } else {
                                $data_tbl_daily['NihgtShiftOverTimeHour'] = $total_hour_worked;
                                $total_hour_worked = date("H:i:s", 0);
                                if ($data_tbl_daily['NihgtShiftOverTimeHour'] > 0)
                                    $data_tbl_daily['Night'] = TRUE;
                                else
                                    $data_tbl_daily['Night'] = FALSE;
                            }

                            if ($data_tbl_daily['NihgtShiftOverTimeHour'] == date("H:i:s", 0) && $out > strtotime($night_start_time)) {
                                $data_tbl_daily['NihgtShiftOverTimeHour'] = -1;
                                $data_tbl_daily['Night'] = TRUE;
                            }
                            array_push($data_tbl_daily_whole, $data_tbl_daily);

                            if (strtotime($previous->DateTime) < strtotime($in_time))
                                $data_tbl_daily['InTime'] = 1; //new card in time or not
                            else {
                                $data_tbl_daily['InTime'] = 0;
                            }
                            $data_tbl_daily['TotalWorkedHour'] = date("H:i:s", 0);
                            $data_tbl_daily['GenarelWorkHour'] = date("H:i:s", 0);
                            $data_tbl_daily['OverTimeHour'] = date("H:i:s", 0);
                            $data_tbl_daily['AdditionalOverTimeHour'] = date("H:i:s", 0);
                            $data_tbl_daily['NihgtShiftOverTimeHour'] = date("H:i:s", 0);

                            $total_hour_worked = date("H:i:s", 0);
                        }
                        $flag = TRUE;
                    }
                }
            }
            $data_tbl_daily['Date'] = date("Y-m-d ", strtotime($current->DateTime));
            $data_tbl_daily['CardNo'] = $current->CardNo;
            $data_tbl_daily['TotalWorkedHour'] = $total_hour_worked;

            if ($total_hour_worked > date("H:i:s", 32400)) {
                $data_tbl_daily['GenarelWorkHour'] = date("H:i:s", 32400);
                $total_hour_worked = $total_hour_worked - date("H:i:s", 32400);
            } else {
                $data_tbl_daily['GenarelWorkHour'] = $total_hour_worked;
                $total_hour_worked = date("H:i:s", 0);
            }

            if ($total_hour_worked > date("H:i:s", 10800)) {
                $data_tbl_daily['OverTimeHour'] = date("H:i:s", 10800);
                $total_hour_worked = $total_hour_worked - date("H:i:s", 10800);
                $data_tbl_daily['OT'] = TRUE;
            } else {
                $data_tbl_daily['OverTimeHour'] = $total_hour_worked;
                $total_hour_worked = date("H:i:s", 0);
                if ($data_tbl_daily['OverTimeHour'] > 0)
                    $data_tbl_daily['OT'] = TRUE;
                else
                    $data_tbl_daily['OT'] = FALSE;
            }

            if ($total_hour_worked > date("H:i:s", 10800)) {
                $data_tbl_daily['AdditionalOverTimeHour'] = date("H:i:s", 10800);
                $total_hour_worked = $total_hour_worked - date("H:i:s", 10800);
                $data_tbl_daily['AOT'] = TRUE;
            } else {
                $data_tbl_daily['AdditionalOverTimeHour'] = $total_hour_worked;
                $total_hour_worked = date("H:i:s", 0);
                if ($data_tbl_daily['AdditionalOverTimeHour'] > 0)
                    $data_tbl_daily['AOT'] = TRUE;
                else
                    $data_tbl_daily['AOT'] = FALSE;
            }

            if ($total_hour_worked > date("H:i:s", 7200)) {
                $data_tbl_daily['NihgtShiftOverTimeHour'] = $total_hour_worked;
                $total_hour_worked = $total_hour_worked - date("H:i:s", 7200);
                $data_tbl_daily['Night'] = TRUE;
            } else {
                $data_tbl_daily['NihgtShiftOverTimeHour'] = $total_hour_worked;
                $total_hour_worked = date("H:i:s", 0);
                if ($data_tbl_daily['NihgtShiftOverTimeHour'] > 0)
                    $data_tbl_daily['Night'] = TRUE;
                else
                    $data_tbl_daily['Night'] = FALSE;
            }

            if ($data_tbl_daily['NihgtShiftOverTimeHour'] == date("H:i:s", 0) && $out > strtotime($night_start_time)) {
                $data_tbl_daily['NihgtShiftOverTimeHour'] = -1;
                $data_tbl_daily['Night'] = TRUE;
            }
            array_push($data_tbl_daily_whole, $data_tbl_daily);
            $data_tbl_daily['TotalWorkedHour'] = date("H:i:s", 0);
            $data_tbl_daily['GenarelWorkHour'] = date("H:i:s", 0);
            $data_tbl_daily['OverTimeHour'] = date("H:i:s", 0);
            $data_tbl_daily['AdditionalOverTimeHour'] = date("H:i:s", 0);
            $data_tbl_daily['NihgtShiftOverTimeHour'] = date("H:i:s", 0);
            $data_tbl_daily['InTime'] = 0;
            $total_hour_worked = date("H:i:s", 0);
            $this->mod_daily_attendance_log->insert_batch_daily_report($data_tbl_daily_whole);
        }

//        $data['tbl_access_log'] = $this->mod_access_log->getLongDataArray();
//        $this->mod_access_log_backup->insert_batch_random_data($data['tbl_access_log']);
//        $this->mod_access_log->EmptyTable();
//
//        $data['tbl_incurrect_access_log'] = $this->mod_incurrect_access_log->getLongDataArray();
//        $this->mod_incurrect_access_log_backup->insert_batch_random_data($data['tbl_incurrect_access_log']);
//        $this->mod_incurrect_access_log->EmptyTable();
//
//        $data['tbl_access_log_raw'] = $this->mod_access_log_raw->getLongDataArray();
//        $this->mod_access_log_raw_backup->insert_batch_random_data($data['tbl_access_log_raw']);
//        $this->mod_access_log_raw->EmptyTable();
        //$data['tbl_daily_attendance_log'] = $this->mod_daily_attendance_log->view();
        //$data['container'] = 'temp/daily_report_generate/daily_attendence_view';
        //$this->load->view('main_page', $data);
    }

    public function view_by_id() {
        if ($this->input->post('submit') == 'Search') {
            $this->mod_access_log->CardNo = $this->input->post('CardNo');
            $this->mod_access_log->DateTime = date("Y-m-d ", strtotime($this->input->post('DateTime')));
            $data['tbl_access_log'] = $this->mod_access_log->view_by_id();
            $m = count($data['tbl_access_log']);
            if (!($m % 2) == 0)
                $data['msg'] = 'This User has some problems with access';
            $data['container'] = 'temp/daily_report_generate/view';
            $this->load->view('main_page', $data);
        }
    }

    public function edit() {
        if ($this->input->post('submit') == 'edit') {
            $this->mod_access_log->ID = $this->input->post('ID');
            $data['tbl_log'] = $this->mod_access_log->view_by_id();
            $data['container'] = 'temp/log/edit';
            $this->load->view('temp/main_page', $data);
        }
        if ($this->input->post('submit') == 'delete') {
            $this->delete();
        }
    }

    public function SubTractTime() {
        $tbl_access_log_raw = $this->mod_access_log_raw->getLongDataArray();

        $limit = count($tbl_access_log_raw) - 1;
        for ($index = 0; $index <= $limit; $index++) {
            $date = new DateTime($tbl_access_log_raw[$index]['InTime']);
            $date->modify("-6 hours");
            $tbl_access_log_raw[$index]['InTime'] = $date->format("Y-m-d H:i:s");
        }
//        echo '<pre>';
//        print_r($tbl_access_log_raw);
//        echo '</pre>';
        $this->mod_access_log_raw->EmptyTable();
        $this->mod_access_log_raw->insert_batch_random_data($tbl_access_log_raw);
    }

}

?>