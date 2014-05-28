<script type="text/javascript" src="<?php echo base_url(); ?>assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#Date').datepicker({
            format: 'mm-dd-yyyy'
        });


    });


</script>
<style>
    label.error{
        color: red;
        font-weight: bold;
    }
</style>
<script>
    $(document).ready(function() {
        $("body").on("focus", ".datepicker", function() {
            $(this).datepicker();
        });

        $('#leaveApprovalForm').validate({
            rules: {
                CardNo: 'required',
                NumberOfDays: 'required',
                LeaveTypeName: 'required',
                Date: 'required'
            },
            messages: {
                CardNo: 'Please enter card number',
                NumberOfDays: 'Please enter number of days',
                LeaveTypeName: 'Please select a leave type',
                Date: 'Please select a dates'
            }
        });

        $("#CardNo").change(function() {
            $('#Image').html('');
            $("#employeeLeaveStatus").html('');
            var CardNo = $('#CardNo').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>con_pro_daily_leave_report/get_image_designation_by_cardno",
                data: "CardNo=" + CardNo,
                success: function(data)
                {
                    if (data == '') {
                        alert('Sorry No Data Found');
                        $("#CardNo").val('');
                        $("#CardNo").focus();
                        $('#Image').html('');
                        //image.hide();
                        $('#Name').text('');
                        $('#Designation').text('');
                        $('#BuildingName').text('');
                        $('#Floor').text('');
                        $('#Department').text('');
                        $('#employeeLeaveStatus').html('');
                        return false;
                    } else if (data != '') {
                        var image = $('#Image');
                        image.append('<img src="<?php echo base_url(); ?>img/' + data[0].Image + '" height="150" width="150"/>');
                        $('#Name').text('নাম  : ' + data[0].Name);
                        $('#Designation').text('পদবী  : ' + data[0].Designation);
                        $('#BuildingName').text('ভবনের নাম  : ' + data[0].BuildingName);
                        $('#Floor').text('ফ্লোর : ' + data[0].Floor);
                        $('#Department').text('বিভাগ  : ' + data[0].Department);
                        var empStatus = $('#employeeLeaveStatus');
                        empStatus.append('<thead> <tr><th>সংক্ষিপ্ত নাম</th><th>দিন</th><th>বছর</th></tr></thead><tbody>');
                        $.each(data, function(v, k) {
                            empStatus.append("<tr><td>" + k.ShortForm + "</td><td>" + k.Days + "</td><td>" + k.Year + "</td></tr>");
                        });
                        empStatus.append('</tbody>');

                    }
                }, dataType: 'json'
            });
        });

        $('#NumberOfDays').on('change', function() {
            var days = parseInt($('#NumberOfDays').val());
            var counter = 1;
            if (days != 0) {
                while (counter <= days) {
                    var datepicker_textbox = '<div class="form-group">';
                    datepicker_textbox += '<label for="Date" class="col-sm-3 control-label" >তারিখ</label>';
                    datepicker_textbox += '<div class="col-sm-8">';
                    datepicker_textbox += '<input type="text" name="mydate[]"  class="form-control datepicker" id="Date"  placeholder="তারিখ টাইপ করুন">';
                    datepicker_textbox += '</div>';
                    datepicker_textbox += '</div>';
                    $('#genaratedDatepicker').append(datepicker_textbox);
                    counter++;
                }
            }
        });
    });
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script type="text/javascript" src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.validate.min.js"></script>

<!-- Leave Modal Start -->
<div class="modal fade" id="InsertLeave" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="myModalLabel">Leave Entry Form</h3>
            </div>
            <div class="modal-body">
                <!-- Leave Form Start -->
                <div class='col-lg-12'> 
                    <?php
                    $attr = array(
                        'class' => 'form-horizontal',
                        'role' => 'form',
                        'id' => 'leaveApprovalForm'
                    );
                    echo form_open('con_proc_leave_type_allocation/insert', $attr);
                    ?>
                    <section class="panel panel-primary">                        
                        <div class="panel-heading">ছুটির অনুমোদন তথ্য</div>
                        <div class="panel-body">                                
                            <div class="form-group">
                                <label for="CardNo" class="col-sm-3 control-label" >কার্ড  নং</label>
                                <div class="col-sm-8">
                                    <input type="text" name="CardNo"  class="form-control " id="CardNo" placeholder="কার্ড  নং টাইপ করুন">
                                </div>
                            </div>
                            <div class="form-group">                    
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-3" id="Image" style="margin-right: 105px; margin-top: 5px; height: 75px; width:75px; ">

                                    </div>

                                    <div class="col-sm-5">
                                        <h3 id="Name"></h3>
                                        <h4 id="Designation"></h4>
                                        <p id="BuildingName"></p>
                                        <p id="Floor"></p>
                                        <p id="Department"></p>                                                
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-8">
                                            <table class="table table-advance table-condensed" id="employeeLeaveStatus">

                                            </table>
                                        </div>
                                        <div class="col-sm-1"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="NumberOfDays" class="col-sm-3 control-label" >দিনের সংখ্যা </label>
                                <div class="col-sm-8">
                                    <input type="text" name="NumberOfDays"  class="form-control" id="NumberOfDays" placeholder="কত দিনের ছুটি চান টাইপ করুন?">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3" for="LeaveTypeName">ছুটির ধরনের নাম</label>
                                <div class="col-lg-8">
                                    <select class ="form-control" name="LeaveTypeName" id="LeaveTypeName">
                                        <option value="">--ছুটির ধরনের নাম নির্বাচন করুন--</option>
                                        <?php
                                        $this->load->model('mod_leave_detail');
                                        $tbl_leave_category = $this->mod_leave_detail->get_leave_type_names();
                                        foreach ($tbl_leave_category as $rec_leave_category) {
                                            echo "<option value='" . $rec_leave_category->CatagoryName . "'>" . $rec_leave_category->CatagoryName . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Note" class="col-sm-3 control-label" >নোট</label>
                                <div class="col-sm-8">
                                    <textarea name="Note" id="Note" col="5" row="30" class="form-control"></textarea>
                                </div>
                            </div>
                            <div id="genaratedDatepicker">

                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-8">
                                    <button type="submit" name="submit" class="btn btn-primary">সেভ করুন</button>
                                </div>
                            </div>        
                        </div>    
                    </section>
                    <div class='col-lg-2'></div>
                </div>

                <!-- Leave Form End -->
            </div>
            <div class="modal-footer">
                <input type="submit" name="submit"  class="btn btn-success" id="update" value="Update">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<!-- Leave Modal End -->
<div class="modal fade" id="editIncorrect" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="myModalLabel">Edit In/Out Time</h3>
            </div>
            <div class="modal-body">
                <div class='col-lg-6'>
                    <fieldset>
                        <legend>Update Mismatch Access Log</legend>
                    </fieldset>                
                    <?php
                    $attr = array(
                        'class' => 'form-horizontal',
                        'role' => 'form'
                    );
                    echo form_open('con_pro_attn_mismatch_report/insert', $attr);
                    ?>
                    <div class="form-group">
                        <label for="CardNo" class="col-sm-3 control-label" >Card No</label>
                        <div class="col-sm-9">
                            <input type="text" name="CardNo"  class="form-control" id="CardNo_edit" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Percentage" class="col-sm-3 control-label" >Name</label>
                        <div class="col-sm-9">
                            <input type="text" name="Percentage"  class="form-control" id="id_Name" value="" disabled="">
                        </div>
                    </div>

                    <?php
                    //$date = date('Y-m-d', strtotime($mismatch['DateTime']));
                    //$time = date('H:i:s', strtotime($mismatch['DateTime']));
                    ?>
                    <div class="form-group">
                        <label for="Percentage" class="col-sm-3 control-label" >In Time</label>
                        <div class="col-sm-9">
                            <input type="text" name="InTime"  class="form-control" id="InTime" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Percentage" class="col-sm-3 control-label" >Out Time</label>
                        <div class="col-sm-9">
                            <input type="text" name="OutTime"  class="form-control" id="OutTime" value="">
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <input type="submit" name="submit"  class="btn btn-success" id="update" value="Update">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editInOut" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="myModalLabel">Edit In/Out Time</h3>
            </div>
            <div class="modal-body">
                <?php
                $attr = array(
                    'class' => 'form-horizontal',
                    'role' => 'form'
                );
                echo form_open('con_pro_employee_monthly_report/update', $attr);
                ?>
                <div class="form-group">
                    <label for="CardNo" class="col-sm-2 control-label" >কার্ড নং : </label>
                    <div class="col-sm-4">
                        <input type="text" name="CardNo"  class="form-control" id="id_CardNo"  value="">
                    </div>
                    <label for="DateTime" class="col-sm-2 control-label" >কার্ড নং : </label>
                    <div class="col-sm-4">
                        <input type="text" name="DateTime"  class="form-control" id="id_DateTime" >
                        <input type="hidden" name="DateTimeOld"  class="form-control" id="id_DateTimeOld" >
                        <input type="hidden" name="Month"  class="form-control" id="id_Month" >
                    </div>

                </div>


            </div>
            <div class="modal-footer">
                <input type="submit" name="submit"  class="btn btn-success" id="update" value="Update">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<div class="row">   
    <div class="col-lg-12">
        <section class="panel panel-body">
            <div class="panel-primary" > 
                <header class="panel-heading">
                    <h4> 
                        <?php
                        if ($this->input->post('CardNo')) {
                            $infoss = $this->input->post('CardNo');
                        } else {
                            $infoss = 0;
                        }
                        ?>
                        কার্ড নং এবং মাস দারা অনুসন্ধান করুন        <a href="<?php echo base_url(); ?>con_pro_attn_mismatch_report/edit1/<?php echo $infoss; ?>" class="btn btn-default">New day entry</a>          
                    </h4>                
                </header> 
                <div class="panel-body">
                    <?php
                    $attr = array(
                        'class' => 'form-horizontal',
                        'role' => 'form'
                    );
                    echo form_open('con_pro_employee_monthly_report/search', $attr);
                    ?>
                    <div class="form-group">
                        <label for="CardNo" class="col-sm-1 control-label" >কার্ড নং : </label>
                        <div class="col-sm-3">
                            <input type="text" name="CardNo" value="<?php if ($this->input->post('CardNo')) echo $this->input->post('CardNo'); ?>"  class="form-control" id="id_CardNo" placeholder="কার্ড নং টাইপ করুন">
                        </div>
                        <label for="Month" class="col-sm-1 control-label" >মাস : </label>
                        <?php

                        function check($myvalue, $abc) {

                            if ($abc == $myvalue) {
                                echo 'selected';
                            }
                        }
                        ?>
                        <div class="form-group col-sm-3">
                            <select class ="form-control" name="Month" id="Month">
                                <option value="0" <?php check(0, $this->input->post('Month')); ?>>মাস নির্বাচন করুন</option>                                                                                        
                                <option value="1" <?php check(1, $this->input->post('Month')); ?>>জানুয়ারী </option>                                                       
                                <option value="2" <?php check(2, $this->input->post('Month')); ?>>ফেব্রুয়ারী </option>                                                       
                                <option value="3" <?php check(3, $this->input->post('Month')); ?>>মার্চ </option>                                                       
                                <option value="4" <?php check(4, $this->input->post('Month')); ?>>এপ্রিল </option>                                                       
                                <option value="5" <?php check(5, $this->input->post('Month')); ?>>মে </option>                                                       
                                <option value="6" <?php check(6, $this->input->post('Month')); ?>>জুন</option>                                                       
                                <option value="7" <?php check(7, $this->input->post('Month')); ?>>জুলাই </option>
                                <option value="8" <?php check(8, $this->input->post('Month')); ?>>অগাস্ট </option>                                                       
                                <option value="9" <?php check(9, $this->input->post('Month')); ?>>সেপ্টেম্বর </option>                                                       
                                <option value="10" <?php check(10, $this->input->post('Month')); ?>>অক্টোবর </option>                                                       
                                <option value="11" <?php check(11, $this->input->post('Month')); ?>>নভেম্বর </option>                                                       
                                <option value="12"<?php check(12, $this->input->post('Month')); ?>>ডিসেম্বর </option>
                            </select>
                        </div>&nbsp;
                        <input type="submit" name="submit"  class="btn btn-success" id="update" value="Search">
                    </div>
                    <?php
                    echo form_close();
                    ?>
                </div>
            </div>
        </section>
    </div>    
</div>
<!-- Miss-match Report -->
<div class="row"> 
    <div class="col-lg-12">
        <section class="panel panel-body">
            <div class="panel-primary" > 
                <header class="panel-heading">
                    <h4>
                        শ্রমিক এবং কর্মচারীগণের মাসিক উপস্থিতি অমিলের প্রতিবেদন তালিকা
                    </h4> 
                    <?php
                    $attr = array(
                        'class' => 'form-horizontal',
                        'role' => 'form'
                    );
                    echo form_open('con_pro_employee_monthly_report/generic_intime', $attr);
                    ?>
                    Month<input type="text" name="Month" value="">
                    CardNo<input type="text" name="CardNo" value="">
                    Intime<input type="text" name="Intime" value="">
                    <input type="submit" name="submit"  class="btn btn-success" id="update" value="Generate">
                    <?php echo form_close();
                    ?>
                </header> 
                <table class="table table-striped border-top display" id="daily_log" border="1" style="font-size: 10px;">
                    <thead>                        
                        <tr style="font-size: 15px;">
                            <th><i class="glyphicon glyphicon-edit"></i> তারিখ</th>                    
                            <th><i class="glyphicon glyphicon-edit"></i> কার্ড নং</th>                    
                            <th><i class="glyphicon glyphicon-time"></i> নাম</th>               
                            <th><i class="glyphicon glyphicon-time"></i> প্রবেশ সময়</th>
                            <th><i class="glyphicon glyphicon-time"></i> বাহির সময়</th>
                            <th><i class="glyphicon glyphicon-time"></i> সংশোধন</th>
                        </tr>
                    </thead>

                    <tbody>                        
                        <?php foreach ($tbl_employee_monthly_missmatch_report as $rec_mismatch_report) { ?>
                            <tr style="font-size: 15px;">
                                <td><?php echo date('d-M-Y', strtotime($rec_mismatch_report['DateTime'])); ?></td>     
                                <td><?php echo $rec_mismatch_report['CardNo']; ?></td>
                                <td><?php echo $rec_mismatch_report['Name']; ?></td>                                                          
                                <td>
                                    <?php
                                    $date = date('Y-m-d', strtotime($rec_mismatch_report['DateTime']));
                                    $time = date('H:i:s', strtotime($rec_mismatch_report['DateTime']));
                                    if (date('H:i:s', strtotime($rec_mismatch_report['DateTime'])) < date('H:i:s', strtotime('06:59:59'))) {
                                        echo date('d-m-Y H:i:s', strtotime('+6 hours', strtotime($rec_mismatch_report['DateTime'])));
                                    }
                                    ?>
                                </td> 
                                <td>
                                    <?php
                                    $date = date('Y-m-d', strtotime($rec_mismatch_report['DateTime']));
                                    $time = date('H:i:s', strtotime($rec_mismatch_report['DateTime']));
                                    if (date('H:i:s', strtotime($rec_mismatch_report['DateTime'])) > date('H:i:s', strtotime('06:59:59'))) {
                                        echo date('d-m-Y H:i:s', strtotime('+6 hours', strtotime($rec_mismatch_report['DateTime'])));
                                    }
                                    ?>
                                </td> 
                                <td>
                                    <?php echo form_open('con_pro_attn_mismatch_report/edit'); ?>
                                    <input type="hidden" name="CardNo" value="<?php echo $rec_mismatch_report['CardNo']; ?>"/>
                                    <input type="hidden" name="Date" value="<?php echo $rec_mismatch_report['DateTime']; ?>"/>
                                    <button class="btn btn-primary btn-xs" name="submit" value="edit" id="mismatchLogEditButton"><i class="glyphicon glyphicon-pencil"></i></button>
                                    <?php echo form_close(); ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>

<!--<div class="row"> 
    <div class="col-lg-12">
        <section class="panel panel-body">
            <div class="panel-primary" > 
                <header class="panel-heading">
                    <h4>
                        দৈনিক প্রথম অর্ধেক উপস্থিতির তালিকা 
                    </h4>                
                </header> 
                <table class="table table-striped border-top" id="daily_log" border="1" style="font-size: 10px;">
                    <thead>
                        <tr style="font-size: 18px;">                    
                            <th><i class="glyphicon glyphicon-edit"></i> কার্ড নং</th>  
                            <th><i class="glyphicon glyphicon-edit"></i> কার্ড নং</th>                    
                            <th><i class="glyphicon glyphicon-time"></i> নাম</th>
                            <th><i class="glyphicon glyphicon-time"></i> সময়সূচী</th>
                            
                        </tr>
                    </thead>
                    <tfoot>
                        <tr style="font-size: 18px;">
                        <th><i class="glyphicon glyphicon-edit"></i> ভবনের নাম</th>     
                            <th><i class="glyphicon glyphicon-edit"></i> ফ্লোর</th>                    
                            <th><i class="glyphicon glyphicon-edit"></i> বিভাগ/সেকশন</th>                    
                                                                           
                        </tr>
                    </tfoot>
                    <tbody>

<?php /* $iCount=1;
  foreach ($tbl_employee_monthly_missmatch_report as $rec_employee_monthly_report) {


  ?>
  <tr style="font-size: 15px;">
  <td><?php echo date('d-M-Y', gmt_to_local(strtotime($rec_employee_monthly_report['DateTime']), 'UTC')); ?></td>
  <td><?php echo $rec_employee_monthly_report['CardNo']; ?></td>
  <input type="hidden" name="cNo" id="cNo" value="<?php echo $rec_employee_monthly_report['CardNo']; ?>" />
  <td><?php echo $rec_employee_monthly_report['Name']; ?></td>

  <td><?php

  echo date('d-m-Y H:i:s', gmt_to_local(strtotime($rec_employee_monthly_report['DateTime']), 'UP6'));
  ?>
  &nbsp;<button  data-toggle="modal" name="dd" id="DateTimeNew<?php echo  $iCount++;?>"  data-target="#editInOut" value ="<?php echo date('d-m-Y H:i:s', gmt_to_local(strtotime($rec_employee_monthly_report['DateTime']), 'UP6')); ?>" onclick="editDateTime(this.id);" >Edit</button>
  </td>
  </tr>
  <?php } */ ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>-->
<!-- End Miss-match Report -->



<!-- Monthly Attendance Report -->
<div class="row"> 
    <div class="col-lg-12">
        <section class="panel panel-body">
            <div class="panel-primary" > 
                <header class="panel-heading">
                    <h4>
                        শ্রমিক এবং কর্মচারীগণের মাসিক উপস্থিতি তালিকা
                    </h4>    

                </header> 
                <table class="table table-striped border-top" id="daily_log" border="1" style="font-size: 10px;">
                    <thead>
                        <tr style="font-size: 18px;">                    
                            <th><i class="glyphicon glyphicon-edit"></i> তারিখ</th>                    
                            <th><i class="glyphicon glyphicon-edit"></i> কার্ড নং</th>                    
                            <th><i class="glyphicon glyphicon-time"></i> নাম</th>               
                            <th><i class="glyphicon glyphicon-time"></i> প্রবেশ সময়</th>
                            <th><i class="glyphicon glyphicon-time"></i> বাহির সময়</th>
                            <th><i class="glyphicon glyphicon-trash"></i> মুছুন</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr style="font-size: 18px;">
<!--                        <th><i class="glyphicon glyphicon-edit"></i> ভবনের নাম</th>     
                            <th><i class="glyphicon glyphicon-edit"></i> ফ্লোর</th>                    
                            <th><i class="glyphicon glyphicon-edit"></i> বিভাগ/সেকশন</th>                    
                            <th><i class="glyphicon glyphicon-edit"></i> লাইন/ইউনিট</th>                                               -->
                        </tr>
                    </tfoot>
                    <tbody>

                        <?php
                        $iCount = 0;
//                        echo '<pre>';
//                        print_r($tbl_employee_monthly_report);
//                        echo '</pre>';
//                        exit();
                        foreach ($tbl_employee_monthly_report as $rec_employee_monthly_report) {
                            if ($iCount % 2 == 0) {
                                $iCount++;
                                ?>
                                <tr style ="color:
                                <?php if ($rec_employee_monthly_report['CreatedBy'] == 'SYSTEM') {
                                    ?>black;<?php
                                    } else if ($rec_employee_monthly_report['CreatedBy'] == 'AUTO') {
                                        ?> red;<?php
                                    } else {
                                        ?> green;<?php }
                                    ?>font-size:15px;">
                                    <td><?php echo date('d-M-Y', strtotime($rec_employee_monthly_report['DateTime'])); ?></td>
                                    <td><?php echo $rec_employee_monthly_report['CardNo']; ?></td>
                            <input type="hidden" name="cNo" id="cNo" value="<?php echo $rec_employee_monthly_report['CardNo']; ?>" />
                            <td><?php echo $rec_employee_monthly_report['Name']; ?></td>

                            <td><?php
                                echo date('d-m-Y H:i:s', strtotime('+6 hours', strtotime($rec_employee_monthly_report['DateTime'])));
                                ?>
                                &nbsp;<button class="btn btn-primary btn-xs" data-toggle="modal" name="dd" id="DateTimeNew<?php echo $iCount; ?>"  data-target="#editInOut" value ="<?php echo date('d-m-Y H:i:s', gmt_to_local(strtotime($rec_employee_monthly_report['DateTime']), 'UP6')); ?>" onclick="editDateTime(this.id);" ><i class="glyphicon glyphicon-pencil"></i> সংশোধন</button>
                            </td>    
                            <?php
                        } else {
                            $iCount++;
                            ?>

                            <td><?php
                                echo date('d-m-Y H:i:s', strtotime('+6 hours', strtotime($rec_employee_monthly_report['DateTime'])));
                                ?>

                                &nbsp;<button class="btn btn-primary btn-xs" data-toggle="modal" name="dd" id="DateTimeNew<?php echo $iCount; ?>"  data-target="#editInOut" value ="<?php echo date('d-m-Y H:i:s', gmt_to_local(strtotime($rec_employee_monthly_report['DateTime']), 'UP6')); ?>" onclick="editDateTime(this.id);" ><i class="glyphicon glyphicon-pencil"></i> সংশোধন</button>
                            </td>

                            <td><a href="<?php echo base_url(); ?>con_pro_employee_monthly_report/delete_monthly_attandance_record/<?php echo $rec_employee_monthly_report['CardNo'] ?>/<?php echo date('Y-m-d', strtotime($rec_employee_monthly_report['DateTime'])); ?>" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i></a></td>

                            </tr>
                            <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
<!-- End Monthly Attendance Report -->
<div class="modal fade" id="editInOut" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="myModalLabel">Edit In/Out Time</h3>
            </div>
            <div class="modal-body">
                <?php
                $attr = array(
                    'class' => 'form-horizontal',
                    'role' => 'form'
                );
                echo form_open('con_pro_employee_monthly_report/update', $attr);
                ?>
                <div class="form-group">
                    <label for="CardNo" class="col-sm-2 control-label" >কার্ড নং : </label>
                    <div class="col-sm-4">
                        <input type="text" name="CardNo"  class="form-control" id="id_CardNo"  value="">
                    </div>
                    <label for="DateTime" class="col-sm-2 control-label" >কার্ড নং : </label>
                    <div class="col-sm-4">
                        <input type="text" name="DateTime"  class="form-control" id="id_DateTime" >
                        <input type="hidden" name="DateTimeOld"  class="form-control" id="id_DateTimeOld" >
                        <input type="hidden" name="Month"  class="form-control" id="id_Month" >
                    </div>

                </div>


            </div>
            <div class="modal-footer">
                <input type="submit" name="submit"  class="btn btn-success" id="update" value="Update">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Leave Related Report-->

<!-- Miss-match Report -->
<div class="row"> 
    <div class="col-lg-12">
        <section class="panel panel-body">
            <div class="panel-primary" > 
                <header class="panel-heading">
                    <h4>
                        শ্রমিক এবং কর্মচারীগণের মাসিক উপস্থিতি অমিলের প্রতিবেদন তালিকা
                    </h4> 
                    <button class="btn btn-primary btn-xs" data-toggle="modal" name="dd" id="InsertLeaveDetails" data-target="#InsertLeave" >Insert Leave </button>
                </header> 

                <table class="table table-striped border-top display" id="daily_log" border="1" style="font-size: 10px;">
                    <thead>                        
                        <tr style="font-size: 15px;">
                            <th><i class="glyphicon glyphicon-edit"></i> ক্রমিক নং</th>                    
                            <th><i class="glyphicon glyphicon-edit"></i> কার্ড নং</th>                    
                            <th><i class="glyphicon glyphicon-time"></i> ছুটির ধরণ</th>               
                            <th><i class="glyphicon glyphicon-time"></i> তারিখ</th>
                            <th><i class="glyphicon glyphicon-time"></i> নোট </th>
                            <th><i class="glyphicon glyphicon-time"></i> আবেদন নং</th>
                            <th><i class="icon icon-rocket"></i> প্রক্রিয়া</th>
                        </tr>
                    </thead>

                    <tbody>    

                        <?php
                        $i = 1;
                        foreach ($tbl_employee_monthly_leave_report as $rec_leave_report) {
                            ?>
                            <tr style="font-size: 15px;">
                                <td><?php echo $i++; ?></td>     
                                <td><?php echo $rec_leave_report['CardNo']; ?></td>
                                <td><?php echo $rec_leave_report['LeaveCategoryName']; ?></td>                                                          
                                <td>
                                    <?php
                                    $date = date('d-m-Y', strtotime($rec_leave_report['Date']));
                                    echo $date;
                                    ?>
                                </td> 
                                <td>
                                    <?php
                                    echo $rec_leave_report['Note'];
                                    ?>
                                </td> 
                                <td>
                                    <?php echo $rec_leave_report['ApplicationNo']; ?>
                                </td>
                                <td>
    <!--                                    <a href="<?php echo base_url(); ?>con_pro_employee_monthly_report/Leave_Details_Edit/<?php //echo $rec_leave_report['CardNo'].'/'.$rec_leave_report['Date'];   ?>" class="btn btn-info btn-xs" title="সংশোধন করুন"><i class="icon icon-pencil"></i> সংশোধন করুন</a>-->
                                    <a href="<?php echo base_url(); ?>con_pro_employee_monthly_report/Leave_Details_Delete/<?php echo $rec_leave_report['CardNo'] . '/' . $rec_leave_report['Date'] ?>" class="btn btn-danger btn-xs" title="মুছুন" onclick="return confirm('আপনি কি নিশ্চিত যে এই তথ্যটি মুছে ফেলতে চান?')"><i class="icon icon-trash"></i> মুছুন</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
<script>
    function editDateTime(mytime)
    {

        var t = document.getElementById(mytime).value;
        var m = document.getElementById('Month').value;
        var c = document.getElementById('cNo').value;
        document.getElementById('id_DateTime').value = t;
        document.getElementById('id_DateTimeOld').value = t;
        document.getElementById('id_CardNo').value = c;
        document.getElementById('id_Month').value = m;



    }


</script>
