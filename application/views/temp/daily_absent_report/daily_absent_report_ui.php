<div class="row">     
    <div class="col-md-12">
        <section class="panel panel-body">
            <div class="panel-primary" > 
                <header class="panel-heading">
                    <h4>
                        দৈনন্দিন অনুপস্থিত প্রতিবেদন তালিকা                         
                    </h4>                
                </header>                 
                <div class="panel panel-body">
                    <table class="table table-striped table-advance table-hover" id="daily_log" border="1">
                        <thead>
                            <tr>
                                <th><i class="glyphicon glyphicon-edit"></i> ভবনের নাম</th>
                                <th><i class="glyphicon glyphicon-edit"></i> ফ্লোর</th>                    
                                <th><i class="glyphicon glyphicon-edit"></i> বিভাগ</th>     
                                <th><i class="glyphicon glyphicon-edit"></i> লাইন</th> 
                                <th><i class="glyphicon glyphicon-edit"></i> কার্ড নং</th>                    
                                <th><i class="glyphicon glyphicon-edit"></i> নাম</th>
                                 <th><i class="icon icon-pencil"></i> সংশোধন</th>

                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th><i class="glyphicon glyphicon-edit"></i> ভবনের নাম</th>
                                <th><i class="glyphicon glyphicon-edit"></i> ফ্লোর</th>                    
                                <th><i class="glyphicon glyphicon-edit"></i> বিভাগ</th>
                                <th><i class="glyphicon glyphicon-edit"></i> লাইন</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php foreach ($tbl_absent_report as $rec_absent_report) { ?>
                                <tr style="font-size: 14px;">
                                    <td><?php echo $rec_absent_report['BuildingName']; ?></td>
                                    <td><?php echo $rec_absent_report['Floor']; ?></td>
                                    <td><?php echo $rec_absent_report['Department']; ?></td>
                                    <td><?php echo $rec_absent_report['Line']; ?></td>          
                                    <td><?php echo $rec_absent_report['CardNo']; ?></td>
                                    <td><?php echo $rec_absent_report['Name']; ?></td>
                                    <td>
                            <a href="<?php echo base_url(); ?>con_pro_daily_absent_report/attendanceRectifaction/<?php echo $rec_absent_report['CardNo']; ?>" class="btn btn-success btn-xs btn-round" title="সংশোধন"><strong><i class="icon icon-pencil"></i> সংশোধন</strong></a>
                        </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>    
</div>

<link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.dataTables.css"/>
<script src="<?php echo base_url(); ?>js/jquery.dataTables1.js"></script>
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
</script>