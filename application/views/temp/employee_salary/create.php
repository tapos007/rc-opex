<script>
    $(document).ready(function() {
        $('#JobCategoryName').on('change', function(e) {
            var jobCatName = $(this).val();
            if (jobCatName == 0) {
                var mySelect = $('#GradeName');
                mySelect.html('');
                mySelect.append("<option>অনুগ্রহ করে বিভাগ নির্বাচন  করুন</option> ");
                var mySelect1 = $('#DesignationName');
                mySelect1.html('');
                mySelect1.append("<option>অনুগ্রহ করে গ্রেড নির্বাচন  করুন</option> ");
            } else {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>con_set_grade_mapping/get_grade_names",
                    data: "Name=" + jobCatName,
                    success: function(data)
                    {
                        var mySelect = $('#GradeName');
                        mySelect.html('');
                        mySelect.append("<option value='0'>গ্রেড নির্বাচন করুন</option> ");
                        mySelect.append("<option>------------------</option> ");
                        $.each(data, function(v, k) {
                            mySelect.append("<option value='" + k.ID + "'>" + k.Name + "</option>");
                        });
                        $('#GradeKeyword').val(data.Keyword);
                    }, dataType: 'json'
                });
            }
        });

        $('#GradeName').on('change', function(e) {
            var gradeName = $(this).val();
            if (gradeName == 0) {
                var mySelect = $('#DesignationName');
                mySelect.html('');
                mySelect.append("<option>অনুগ্রহ করে গ্রেড নির্বাচন  করুন</option> ");
            } else {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>con_set_grade_mapping/get_designation_list",
                    data: "GradeName=" + gradeName,
                    success: function(data)
                    {
                        var mySelect = $('#DesignationName');
                        mySelect.html('');
                        mySelect.append("<option>উপাধি নির্বাচন করুন</option> ");
                        mySelect.append("<option>------------------</option> ");
                        $.each(data, function(v, k) {

                            mySelect.append("<option value='" + k.Designation + "' data-foo='" + k.ID + "' >" + k.Designation + "</option>");
                        });
                        $('#GradeKeyword').val(data.Keyword);
                    }, dataType: 'json'
                });
            }
        });

        $('#DesignationName').on('change', function(e) {
            var DesignationID = $(this).val();
            if (DesignationID == 0) {
                $('#GradeMappingName').val('');
            } else {
                $.ajax({
                    type: "POST",
                    data: "DesignationID=" + DesignationID,
                    url: "<?php echo base_url(); ?>con_set_grade_mapping/get_mapping_keyword",
                    dataType: 'json',
                    success: function(abc)
                    {
                        $('#GradeMappingName').val(abc.ShortForm + '-' + abc.Grade + '-' + abc.Designation);
                    }
                });
            }
        });
    });
</script>
<style>
    label.error{
        color: red;
        font-weight: bold;
    }
</style>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.validate.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bootstrap-datepicker/css/datepicker.css"/>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#employeeSalaryInsertUpdateForm").validate({
            rules: {
                GradeName: "required",
                DesignationName: "required",
                CardNo: "required",
                GrossSalary: "required",
                LastIncrementDate: "required",
                LastIncrementMoney: "required",
                PromotionDate: "required",
                OT: "required",
                AttendanceBonus: "required",
                OtherAllowance: "required",
                OthAllowCal: "required"
            },
            messages: {
                GradeName: "অনুগ্রহ করে গ্রেডের নাম টাইপ করুন",
                DesignationName: "অনুগ্রহ করে উপাধির নাম টাইপ করুন",
                CardNo: "অনুগ্রহ করে কার্ড নং টাইপ করুন",
                GrossSalary: "অনুগ্রহ করে মূল বেতন টাইপ করুন",
                LastIncrementDate: "অনুগ্রহ করে সর্বশেষ বর্ধিত তারিখ নির্বাচন করুন",
                LastIncrementMoney: "অনুগ্রহ করে গসর্বশেষ বর্ধিত টাকা টাইপ করুন",
                PromotionDate: "অনুগ্রহ করে পদোন্নতির তারিখ নির্বাচন করুন",
                OT: "অনুগ্রহ করে ওভার টাইম টাইপ করুন",
                AttendanceBonus: "অনুগ্রহ করে উপস্থিত বোনাস টাইপ করুন",
                OtherAllowance: "অনুগ্রহ করে অন্যান্য ভাতা টাইপ করুন",
                OthAllowCal: "অনুগ্রহ করে অন্যান্য ভাতা হিসাব টাইপ করুন"
            }
        });
        
        $('#searchForm').validate({
           rules:{
               Search: "required"
           },
           messages:{
               Search:"অনুগ্রহ করে কার্ড নং টাইপ করে অনুসন্ধান করুন"
           }
        });

        $("body").on("focus", ".datepicker", function() {
            $(this).datepicker();
        });

    });
</script>

<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <section class="panel panel-primary">                        
            <div class="panel-heading"> কার্ড নং দ্বারা অনুসন্ধান করুন</div>
            <div class="panel-body">
                <?php
                $attributes = array(
                    'class' => 'form-inline',
                    'role' => 'form',
                    'id' => 'searchForm'
                );
                echo form_open('', $attributes);
                ?>
                <div class="form-group">
                    <label for="Search" class="col-lg-4 control-label">কার্ড নং টাইপ করুন</label>
                    <div class="col-lg-5"> 
                        <input type="text" name="Search" id="Search" class="form-control"/>
                    </div>
                    <button class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-search"></i> অনুসন্ধান করুন</button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </section>
    </div>
    <div class="col-md-2"></div>
</div>

<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <?php
        $attr = array(
            'class' => 'form-horizontal',
            'id' => 'employeeSalaryInsertUpdateForm',
            'role' => 'form'
        );
        echo form_open('', $attr);
        ?>

        <section class="panel panel-primary">                        
            <div class="panel-heading">কর্মচারী বেতন সংক্রান্ত তথ্য</div>
            <div class="panel-body">                                                          
                <div class="form-group">
                    <label for="JobCategoryName" class="col-lg-3 control-label" >পেশা বিভাগের নাম</label>
                    <div class="col-lg-6">                            
                        <select class ="form-control" name="JobCategoryName" id="JobCategoryName">
                            <option value="0">বিভাগ নির্বাচন করুন</option>
                            <option>------------------------</option>
                            <?php foreach ($tbl_job_category as $rec_job_category) { ?>
                                <option value="<?php echo $rec_job_category->ID; ?>"><?php echo $rec_job_category->CategoryName; ?></option>
                            <?php } ?>                                
                        </select>                        
                    </div>
                </div>

                <div class="form-group">
                    <label for="GradeName" class="col-lg-3 control-label" >গ্রেডের নাম</label>
                    <div class="col-lg-6">                            
                        <select class ="form-control" name="GradeName" id="GradeName">                                                                       
                        </select>
                        <input type="hidden" name="GradeKeyword" id="GradeKeyword" value=""/>
                    </div>
                </div>

                <div class="form-group">
                    <label for="DesignationName" class="col-lg-3 control-label" >উপাধির নাম </label>
                    <div class="col-lg-6">                            
                        <select class ="form-control" name="DesignationName" id="DesignationName">                                                                       
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="CardNo" class="col-lg-3 control-label" >কার্ড নং</label>
                    <div class="col-lg-6">                        
                        <input type="text" name="CardNo"  class="form-control" id="CardNo" placeholder="কার্ড নং টাইপ করুন" >
                    </div>
                </div>

                <div class="form-group">
                    <label for="GrossSalary" class="col-lg-3 control-label" >মূল বেতন</label>
                    <div class="col-lg-6">                        
                        <input type="text" name="GrossSalary"  class="form-control" id="GrossSalary" placeholder="মূল বেতন টাইপ করুন" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="LastIncrementDate" class="col-lg-3 control-label" >সর্বশেষ বর্ধিত তারিখ</label>
                    <div class="col-lg-6">
                        <input type="text" name="LastIncrementDate"  class="form-control datepicker" id="LastIncrementDate" placeholder="সর্বশেষ বর্ধিত তারিখ নির্বাচন করুন" >
                    </div>
                </div>

                <div class="form-group">
                    <label for="LastIncrementMoney" class="col-lg-3 control-label" >সর্বশেষ বর্ধিত টাকা</label>
                    <div class="col-lg-6">
                        <input type="text" name="LastIncrementMoney"  class="form-control" id="LastIncrementMoney" placeholder="সর্বশেষ বর্ধিত টাকা টাইপ করুন">
                    </div>
                </div>

                <div class="form-group">
                    <label for="PromotionDate" class="col-lg-3 control-label" >পদোন্নতির তারিখ</label>
                    <div class="col-lg-6">
                        <input type="text" name="PromotionDate"  class="form-control datepicker" id="PromotionDate" placeholder="পদোন্নতির তারিখ নির্বাচন করুন" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="OT" class="col-lg-3 control-label" >ওভার টাইম</label>
                    <div class="col-lg-6">
                        <input type="text" name="OT"  class="form-control" id="OT" placeholder="ওভার টাইম  টাইপ করুন" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="AttendanceBonus" class="col-lg-3 control-label" >উপস্থিত বোনাস</label>
                    <div class="col-lg-6">                        
                        <input type="text" name="AttendanceBonus"  class="form-control" id="AttendanceBonus" placeholder="উপস্থিত বোনাস  টাইপ করুন" >
                    </div>
                </div>

                <div class="form-group">
                    <label for="OtherAllowance" class="col-lg-3 control-label" >অন্যান্য ভাতা</label>
                    <div class="col-lg-6">                        
                        <input type="text" name="OtherAllowance"  class="form-control" id="OtherAllowance" placeholder="অন্যান্য ভাতা  টাইপ করুন" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="OthAllowCal" class="col-lg-3 control-label" >অন্যান্য ভাতা হিসাব</label>
                    <div class="col-lg-6">
                        <input type="text" name="OthAllowCal"  class="form-control" id="OthAllowCal" placeholder="অন্যান্য ভাতা হিসাব টাইপ করুন" >
                    </div>
                </div>

                <div class="form-group">
                    <label for="IsActive" class="col-lg-3 control-label" >সক্রিয়</label>
                    <div class="col-lg-6">
                        <select class="form-control" name="IsActive" id="IsActive">
                            <option value="1">হ্যাঁ</option>
                            <option value="0">না</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-6 col-lg-push-3">
                        <button class="btn btn-success" type="submit" name="Insert"><i class="glyphicon glyphicon-save"></i> সংরক্ষণ করুন</button>
                        <button class="btn btn-info" type="submit" name="Update"><i class="glyphicon glyphicon-pencil"></i> সংশোধন করুন</button>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php echo form_close(); ?>
    <div class="col-md-2"></div>
</div>