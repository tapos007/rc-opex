<link rel="stylesheet" href="//cdn.datatables.net/plug-ins/e9421181788/integration/bootstrap/3/dataTables.bootstrap.css"/>
<style>
    label.error{
        color: red;
        font-weight: bold;
    }
</style>

<div class="row">   
    <div class="col-lg-12">
        <section class="panel panel-body">
            <div class="panel-primary" > 
                <header class="panel-heading">
                    <h4> 
                        সময়সূচী দ্বারা অনুসন্ধান  করুন                     
                    </h4>                
                </header> 
                <div class="panel-body">
                    <?php
                    $attributes = array(
                        'class' => 'form-inline',
                        'role' => 'form',
                        'id' => 'validateSearchForm'
                    );
                    echo form_open('con_pro_daily_absent_report/Search', $attributes);
                    ?>
                    <div class="form-group">
                        <label for="Date"></label>
                        <input type="text" name="Date" id="Date" class="form-control" placeholder="তারিখ নির্বাচন  করুন" value="<?php echo date('d-m-Y', strtotime($showDate)); ?>" />
                    </div>                 
                    <button class="btn btn-success" type="submit" style="margin-top: 18px;"><i class="glyphicon glyphicon-search"></i> অনুসন্ধান করুন</button>                                    
                    <?php echo form_close(); ?>
                </div>
            </div>
        </section>
    </div>    
</div>

<div class="row">     
    <div class="col-md-12">
        <section class="panel panel-body">
            <div class="text-center">
                <h5><strong>  দৈনন্দিন অনুপস্থিতির রিপোর্ট  বের করার জন্য বাটনটি চাপুন</strong></h5>
                <?php
                $attributes = array(
                    'class' => 'form-inline',
                    'role' => 'form',
                    'id' => 'excelExport'
                );
                echo form_open('con_pro_daily_absent_report/excelExport', $attributes);
                ?>
                <input type="hidden" name="hDate" value="<?php echo $showDate; ?>"/>
                <button class="btn btn-info" type="submit" name="xlexport"><img src="<?php echo base_url(); ?>images/Excel-icon.png" alt="Excel Export" width="16" height="16"/> এক্সেল  এক্সপোর্ট করুন</button>
                <?php
                echo form_close();
                ?>
            </div><hr/>
            <div class="panel-primary" > 
                <header class="panel-heading">
                    <h4>
                        দৈনন্দিন অনুপস্থিত প্রতিবেদন তালিকা                         
                    </h4>                
                </header>                 

                <table class="table table-striped table-advance table-condensed table-bordered" id="daily_log" style="margin-top: 5px;">
                    <thead>
                        <tr>
                            <th><i class="glyphicon glyphicon-edit"></i> কার্ড নং</th>                    
                            <th><i class="glyphicon glyphicon-edit"></i> নাম</th>
                            <th><i class="glyphicon glyphicon-edit"></i> প্রস্তাবিত প্রবেশ সময়</th>  
                            <th><i class="glyphicon glyphicon-edit"></i> ছুটি মঞ্জর</th>     
                            <th><i class="icon icon-pencil"></i> সংশোধন</th>

                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th><i class="glyphicon glyphicon-edit"></i> কার্ড নং</th>
                            <th><i class="glyphicon glyphicon-edit"></i> নাম</th>                    
                            <!--<th><i class="glyphicon glyphicon-edit"></i> বিভাগ</th>-->
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach ($tbl_absent_report as $rec_absent_report) { ?>
                            <tr>         
                                <td><?php echo $rec_absent_report->CardNo; ?></td>
                                <td><?php echo $rec_absent_report->Name; ?></td>
                                <td>
                                    <div class="col-sm-8">
                                        <?php foreach ($tbl_work_hour_breakdown as $rec_work_hour_breakdown) { ?>
                                            <input type="text" class="form-control" name="in_time" id="in_time" value="<?php echo $rec_work_hour_breakdown->StartTime; ?>"/> &nbsp; 
                                        <?php } ?>                                    
                                    </div>
                                    <a class="btn btn-xs btn-success"><i class="glyphicon glyphicon-check"></i></a>
                                </td>
                                <td>
                                    <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#InsertLeave"><i class="glyphicon glyphicon-save"></i> ছুটি মঞ্জরের করুন</button>
                                </td>
                                <td>
                                    <a href="<?php echo base_url(); ?>con_pro_daily_absent_report/attendanceRectifaction/<?php echo $rec_absent_report->CardNo; ?>" class="btn btn-info btn-xs" title="সংশোধন"><strong><i class="icon icon-pencil"></i> সংশোধন</strong></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>    
</div>

<!-- Leave Modal Start -->
<div class="modal fade" id="InsertLeave" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
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
                                    <button type="submit" name="submit" class="btn btn-primary btn-sm">সেভ করুন</button>
                                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">বাতিল করুন</button>
                                </div>
                            </div>        
                        </div>    
                    </section>
                    <div class='col-lg-2'></div>
                </div>
                <!-- Leave Form End -->
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<!-- Leave Modal End -->



<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>js/jquery.dataTables1.js"></script>
<script src="//cdn.datatables.net/plug-ins/e9421181788/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        //For Date Picker
        $('#Date').datepicker({
            format: 'dd-mm-yyyy'
        });

        //For Whole body datepicker
        $("body").on("focus", ".datepicker", function() {
            $(this).datepicker();
        });

        //For Leave Form Validation
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

        //For Retrive Employee Data from Datebase
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

        //For Genarate Textboxes Datepicker
        $('#NumberOfDays').on('change', function() {
            var days = parseInt($('#NumberOfDays').val());
            var counter = 1;
            if (days != 0) {
                if (days > 7) {
                    alert('দুঃখিত মোট দিনের সংখ্যা ৭ দিনের চেয়ে বেশী হতে পারবে না');
                }
                else {
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
            }
        });

        //For Data Table
        var table = $('#daily_log').DataTable();
        $("#daily_log tfoot th").each(function(i) {
            var select = $('<select><option value=""></option></select>')
                    .appendTo($(this).empty())
                    .on('change', function() {
                        table.column(i)
                                .search($(this).val())
                                .draw();
                    });

            table.column(i).data().unique().sort().each(function(d, j) {
                select.append('<option value="' + d + '">' + d + '</option>')
            });
        });
    });
</script>
