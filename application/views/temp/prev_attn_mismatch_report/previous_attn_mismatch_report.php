<style>
    .editbox
    {
        display:none
    }
    td
    {
        padding:5px;
    }
    .editbox
    {
        font-size:14px;
        width:270px;
        background-color:#ffffcc;
        border:solid 1px #000;
        padding:4px;
    }
    .edit_tr:hover
    {
        background:url(<?php echo base_url(); ?>images/edit.png) right no-repeat #80C8E5;
        cursor:pointer;
    }
</style>

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
                        সময়সূচী দ্বারা  অনুসন্ধান  করুন                    
                    </h4>                
                </header> 
                <div class="panel-body">
                    <?php
                    $attributes = array(
                        'class' => 'form-inline',
                        'role' => 'form'
                    );
                    echo form_open('con_pro_attn_mismatch_report/', $attributes);
                    ?>
                    <div class="form-group">
                        <label for="Date"></label>
                        <input type="text" name="Date" id="Date" value="<?php if ($this->input->post('Date')) echo date('m-d-Y', strtotime(str_replace('-', '/', $this->input->post('Date')))); ?>" class="form-control" placeholder="তারিখ নির্বাচন  করুন"/>
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
                <h5><strong>  পূর্ববর্তী উপস্থিতির রিপোর্ট  বের করার জন্য বাটনটি চাপুন</strong></h5>
                <?php
                $attributes = array(
                    'class' => 'form-inline',
                    'role' => 'form',
                    'id' => 'excelExport'
                );
                echo form_open('con_pro_attn_mismatch_report/excelExport', $attributes);
                ?>
                <input type="hidden" name="hDate" value="<?php
                echo $showDate;
                ?>"/>
                <button class="btn btn-info" type="submit" name="xlexport"><img src="<?php echo base_url(); ?>images/Excel-icon.png" alt="Excel Export" width="16" height="16"/> এক্সেল  এক্সপোর্ট করুন</button>
                <?php
                echo form_close();
                ?>
            </div><hr/>
            <div class="panel-primary" > 
                <header class="panel-heading">
                    <h4>
                        পূর্ববর্তী উপস্থিতি অমিলের প্রতিবেদন তালিকা                         
                    </h4>                
                </header>
                <table class="table table-striped table-advance table-condensed table-bordered" id="daily_log">
                    <thead>                        
                        <tr>                   
                            <th><i class="glyphicon glyphicon-edit"></i> কার্ড নং</th>                    
                            <th><i class="glyphicon glyphicon-time"></i> নাম</th>     
                            <th><i class="glyphicon glyphicon-edit"></i> বিভাগ/সেকশন</th>                    
                            <th><i class="glyphicon glyphicon-time"></i> প্রবেশ সময়&nbsp;&nbsp;</th>
                            <th><i class="glyphicon glyphicon-time"></i> বাহির সময়</th>
                            <th><i class="glyphicon glyphicon-time"></i> ওভার টাইম/ঘন্টা</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th><i class="glyphicon glyphicon-edit"></i> ভবনের নাম</th>     
                            <th><i class="glyphicon glyphicon-edit"></i> ফ্লোর</th>                    
                            <th><i class="glyphicon glyphicon-edit"></i> বিভাগ/সেকশন</th>
                        </tr>
                    </tfoot>
                    <tbody>                        
                        <?php
                        $counter = 0;
                        //$bn_digits = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');
                        foreach ($tbl_mismatch_report as $rec_mismatch_report) {
                            $counter++;
                            ?>
                            <tr> 
                                <td><?php echo $rec_mismatch_report['CardNo']; ?></td>
                                <td><?php echo $rec_mismatch_report['Name']; ?></td>   
                                <td><?php echo $rec_mismatch_report['Department']; ?></td> 

                                <td class="edit_td0" data-ip="<?php echo $rec_mismatch_report['IP']; ?>" data-cmcard="<?php echo $rec_mismatch_report['CardNo']; ?>" id="<?php echo $rec_mismatch_report['PID']; ?>">
                                    <span  id="first_<?php echo $rec_mismatch_report['PID']; ?>" class="text">
                                        <?php
                                        $date = date('Y-m-d', strtotime($rec_mismatch_report['DateTime']));
                                        $time = date('H:i:s', strtotime($rec_mismatch_report['DateTime']));
                                        if (date('H:i:s', strtotime($rec_mismatch_report['DateTime'])) < date('H:i:s', strtotime('10:59:59'))) {
                                            echo date('d-m-Y H:i:s', strtotime('+6 hours', strtotime($rec_mismatch_report['DateTime'])));
                                        }
                                        ?>
                                    </span>
                                    <input type="text"  value="<?php
                                    if (date('H:i:s', strtotime($rec_mismatch_report['DateTime'])) < date('H:i:s', strtotime('10:59:59'))) {
                                        echo date('d-m-Y H:i:s', strtotime('+6 hours', strtotime($rec_mismatch_report['DateTime'])));
                                    }
                                    ?>" class="editbox form-control" style="width: 105%;"  id="first_input_<?php echo $rec_mismatch_report['PID']; ?>">
                                </td> 
                                <?php
                                if (date('H:i:s', strtotime($rec_mismatch_report['DateTime'])) > date('H:i:s', strtotime('10:59:59'))) {
                                    ?>
                                    <td data-lll="<?php echo $rec_mismatch_report['PID']; ?>"><?php echo date('d-m-Y H:i:s', strtotime('+6 hours', strtotime($rec_mismatch_report['DateTime']))); ?></td>
                                    <td></td>
                                    <?php
                                } else {
                                    ?>

                                    <td class="edit_td"  data-pnid="<?php echo $rec_mismatch_report['PID']; ?>" id="<?php echo $counter; ?>">

                                        <?php
                                        $date = date('Y-m-d', strtotime($rec_mismatch_report['DateTime']));
                                        $time = date('H:i:s', strtotime($rec_mismatch_report['DateTime']));
                                        if (date('H:i:s', strtotime($rec_mismatch_report['DateTime'])) > date('H:i:s', strtotime('10:59:59'))) {
                                            echo date('d-m-Y H:i:s', strtotime('+6 hours', strtotime($rec_mismatch_report['DateTime'])));
                                        }
                                        ?>


                                    </td>

                                    <td>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control edit_td1" id="msc_gsc_<?php echo $counter; ?>" name="OT" />
                                        </div>
                                        <a data-ip="<?php echo $rec_mismatch_report['IP']; ?>" data-dropcard="<?php echo $rec_mismatch_report['CardNo']; ?>" id="<?php echo $counter; ?>"  class="btn btn-primary btn-xs uuu"><i class="glyphicon glyphicon-check"></i></a>
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

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/e9421181788/integration/bootstrap/3/dataTables.bootstrap.css"/>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.dataTables1.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/plug-ins/e9421181788/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".uuu").click(function() {
            var kk = $(this);
            var ID = $(this).attr('id');
            var cardno = $(this).data('dropcard');
            var datetime = $(this).closest('td').prev('td').text();
            var checkdate = $("#Date").val();
            var ip = $(this).data('ip');
            var textboxvalue = $(this).closest('tr').find('.edit_td1').val();
            ;
            var dataString = 'CardNo=' + cardno + '&DateTime=' + datetime + '&IP=' + ip + '&txtvalue=' + textboxvalue + "&incomeTime=" + checkdate;
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>con_pro_attn_mismatch_report/editOuttimes",
                data: dataString,
                dataType: 'json',
                success: function(data)
                {
                    if (data.myinfo == "true") {
                        $(kk).closest("tr").hide();
                    }
                }
            });
            i

        });
        $(".edit_td1").keyup(function() {
            var ID = $(this).val();
            if (!ID) {
                ID = 0;
            }
            var myId = parseInt(ID);
            var mdadsf = $("#Date").val();
            var res = mdadsf.split("-");
            var d = new Date(res[2], res[0], res[1], 17, 15, 00);
            d.setHours(d.getHours() + myId);
            var mytimes = ("0" + (d.getDate())).slice(-2) + "-" + ("0" + (d.getMonth())).slice(-2) + "-" + d.getFullYear() + " " + d.getHours() + ":" + d.getMinutes() + ":" + ("0" + (d.getSeconds())).slice(-2);
            var outprimary = $(this).closest('td').prev('td').text(mytimes);

        });

        $(".editbox").hide();
        $(".edit_td0").click(function()
        {

            var ID = $(this).attr('id');
            $("#first_" + ID).hide();
            $("#first_input_" + ID).show();

        }).change(function()
        {
            var kk = $(this);
            var ID = $(this).attr('id');
            var CardNo = $("#first_" + ID).data('card');

            if ($(kk).closest('td').next('td').data('lll')) {
                var outprimary = $(kk).closest('td').next('td').data('lll');
                var lastcardNo = $(kk).data('cmcard');
                var ipnumber = $(kk).data('ip');
                var datetime = $("#first_input_" + ID).val();

                var dataString = 'CardNo=' + lastcardNo + '&DateTime=' + datetime + '&IP=' + ipnumber + '&ID=' + outprimary;
                //alert(dataString);
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>con_pro_attn_mismatch_report/insert_in_time",
                    data: dataString,
                    dataType: 'json',
                    success: function(data)
                    {
                        if (data.myinfo == "true") {
                            $(kk).closest("tr").hide();
                        }
                    }
                });

            } else {
                alert("no data");
            }
//            var first = $("#first_input_" + ID).val();
//            var dataString = 'ID=' + ID + '&DateTime=' + first;
//            $("#first_" + ID).html('<img src="<?php echo base_url(); ?>images/ajax-loader.gif" />'); // Loading image
//
//            if (first.length > 0)
//            {
//                $.ajax({
//                    type: "POST",
//                    url: "<?php echo base_url(); ?>con_pro_attn_mismatch_report/update_in_time",
//                    data: dataString,
//                    cache: false,
//                    success: function(html)
//                    {
//                        $("#first_" + ID).html(first);
//                         $(ID).addClass('hidden');
//                    }
//                });
//            }

        });
//        $(".edit_td").click(function()
//        {
//
//            var ID = $(this).attr('id');
//
//            $("#last_" + ID).hide();
//
//            $("#last_input_" + ID).show();
//        }).change(function()
//        {
//            var kk = $(this);
//            var ID = $(this).attr('id');
//
//            var first = $("#last_input_" + ID).val();
//
//            var cardId = kk.data('pnid');
//            alert(cardId);
//            alert(first);
////            var last = $("#last_input_" + ID).val();
////            var dataString = 'CardNo=' + CardNo + '&DateTime=' + first + '&DateTimeOld=' + oldDate;
////            var dataString1 = 'CardNo=' + CardNo + '&DateTime=' + last + '&IP=' + IP;
////
////            $("#first_" + ID).html('<img src="<?php echo base_url(); ?>images/ajax-loader.gif" />'); // Loading image
//
//            
//            if (last.length > 0) {
//                $.ajax({
//                    type: "POST",
//                    url: "<?php echo base_url(); ?>con_pro_attn_mismatch_report/insert_out_time",
////                    data: dataString1,
////                    dataType: 'json',
////                    success: function(html)
////                    {
////                        if (html.myinfo == "true") {
////                            $(ID).addClass('hidden');
////                            alert('Successfully Inserted' + ID);
////                            
////                            //kk.parents('tr').addClass('hidden');
////                        }
////                    }
////                });
////            }
//        });
        // Edit input box click action
        $(".editbox").mouseup(function()
        {
            return false;
        });

// Outside click action
        $(document).mouseup(function()
        {
            $(".editbox").hide();
            $(".text").show();
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

