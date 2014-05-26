<script type="text/javascript" src="<?php echo base_url(); ?>assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#Date').datepicker({
            format: 'mm-dd-yyyy'
        });


    });


</script>

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
foreach ($tbl_employee_monthly_report as $rec_employee_monthly_report) {
    if ($iCount % 2 == 0) {
        $iCount++;
        ?>
                                <tr style="font-size: 15px;">
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

                            </tr>
    <?php }
} ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
<!-- End Monthly Attendance Report -->

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
                        </tr>
                    </thead>

                    <tbody>    

<?php $i = 1;
foreach ($tbl_employee_monthly_leave_report as $rec_leave_report) {
    ?>
                            <tr style="font-size: 15px;">
                                <td><?php echo $i++; ?></td>     
                                <td><?php echo $rec_leave_report['CardNo']; ?></td>
                                <td><?php echo $rec_leave_report['LeaveCategoryName']; ?></td>                                                          
                                <td>
                                    <?php
                                    $date = date('Y-m-d', strtotime($rec_leave_report['Date']));
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