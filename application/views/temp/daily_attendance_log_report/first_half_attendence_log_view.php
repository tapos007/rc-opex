<script type="text/javascript" src="<?php echo base_url(); ?>assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#Date').datepicker({
            format: 'mm-dd-yyyy'
        });
    });
</script>

<div class="row">   
    <div class="col-lg-12">
        <section class="panel panel-body">
            <div class="panel-primary" > 
                <header class="panel-heading">
                    <h4> 
                        বিভাগ দ্বারা অনুসন্ধান  করুন                    
                    </h4>                
                </header> 
                <div class="panel-body">
                    <?php
                    $attributes = array(
                        'class' => 'form-inline',
                        'role' => 'form',
                        'id' => 'validateSearchForm'
                    );
                    echo form_open('con_pro_first_half_attendance_log/', $attributes);
                    ?>
                    <div class="form-group">
                        <label for="Date"></label>
                        <input type="text" name="Date" id="Date" class="form-control" placeholder="তারিখ নির্বাচন  করুন"/>
                    </div>                 
                    <button class="btn btn-success" type="submit" style="margin-top: 18px;"><i class="glyphicon glyphicon-search"></i> অনুসন্ধান করুন</button>                                    
                    <?php echo form_close(); ?>
                </div>
            </div>
        </section>
    </div>    
</div>
<div class="row"> 
    <div class="col-lg-12">
        <section class="panel panel-body">
            <div class="text-center">
                <h5><strong> দৈনিক প্রথম অর্ধেকের রিপোর্ট  বের করার জন্য বাটনটি চাপুন</strong></h5>
                <button class="btn btn-info" type="submit" name="xlexport"><img src="<?php echo base_url(); ?>images/Excel-icon.png" alt="Excel Export" width="16" height="16"/> এক্সেল  এক্সপোর্ট করুন</button>
            </div><hr/>
            <div class="panel-primary" > 
                <header class="panel-heading">
                    <h4>
                        দৈনিক প্রথম অর্ধেক উপস্থিতির তালিকা
                    </h4>                
                </header> 
                <table class="table table-striped table-advance table-bordered table-condensed" id="daily_log" style="margin-top: 5px;">
                    <thead>
                        <tr>                   
                            <th><i class="glyphicon glyphicon-edit"></i> কার্ড নং</th>                    
                            <th><i class="glyphicon glyphicon-edit"></i> নাম</th>
                            <th><i class="glyphicon glyphicon-edit"></i> ভবনের নাম</th>     
                            <th><i class="glyphicon glyphicon-edit"></i> ফ্লোর</th>                    
                            <th><i class="glyphicon glyphicon-edit"></i> বিভাগ/সেকশন</th>                    
                            <th><i class="glyphicon glyphicon-edit"></i> লাইন/ইউনিট</th> 
                            <?php //if (!date('d-m-Y H:i:s', now())) { ?>
<!--                                <th><i class="glyphicon glyphicon-time"></i> সময়সূচী</th>-->
                            <?php //} ?>               
                            <th><i class="glyphicon glyphicon-time"></i> প্রবেশ সময়</th>                    
                            <th><i class="glyphicon glyphicon-time"></i> বাহির সময়</th> 
                        </tr>
                    </thead>
<!--                    <tfoot>
                        <tr style="font-size: 18px;">
                            <th><i class="glyphicon glyphicon-edit"></i> ভবনের নাম</th>     
                            <th><i class="glyphicon glyphicon-edit"></i> ফ্লোর</th>                    
                            <th><i class="glyphicon glyphicon-edit"></i> বিভাগ/সেকশন</th>                    
                            <th><i class="glyphicon glyphicon-edit"></i> লাইন/ইউনিট</th>                                               
                        </tr>
                    </tfoot>-->
                    <tbody>
                        <?php foreach ($tbl_first_half_log_report as $rec_mismatch_report) { ?>
                            <tr>
                                <td><?php echo $rec_mismatch_report['CardNo']; ?></td>
                                <td><?php echo $rec_mismatch_report['Name']; ?></td>
                                <td><?php echo $rec_mismatch_report['BuildingName']; ?></td>
                                <td><?php echo $rec_mismatch_report['Floor']; ?></td>
                                <td><?php echo $rec_mismatch_report['Department']; ?></td>
                                <td><?php echo $rec_mismatch_report['Line']; ?></td>
                                <?php if (date('d-m-Y H:i:s', strtotime($rec_mismatch_report['InTime']) != date('d-m-Y H:i:s', now()))) { ?>
                                    <td><?php
                                        echo date('d-m-Y H:i:s', strtotime($rec_mismatch_report['InTime']));
                                        //echo date('d-m-Y H:i:s', strtotime('+6 hours', strtotime(date('d-m-Y H:i:s', strtotime($rec_mismatch_report['InTime'])))));
                                        ?>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>

<!--<link rel="stylesheet" href="<?php //echo base_url();    ?>css/jquery.dataTables.css"/>
<script src="<?php //echo base_url();    ?>js/jquery.dataTables1.js"></script>
<script>

    $(document).ready(function() {
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
</script>-->