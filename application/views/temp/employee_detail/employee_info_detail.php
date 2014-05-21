<link rel="stylesheet" href="//cdn.datatables.net/plug-ins/e9421181788/integration/bootstrap/3/dataTables.bootstrap.css"/>
<script src="<?php echo base_url(); ?>js/jquery.dataTables1.js"></script>
<script src="//cdn.datatables.net/plug-ins/e9421181788/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script>

    $(document).ready(function() {
        var table = $('#employee_info_log').DataTable();

        $("#employee_info_log tfoot th").each(function(i) {
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


        // Change the width of the div
        $('#employee').css({'width': ($('#employee_info_log').width()) + 'px'});


    });
</script>


<div class="row" >
    <div class="col-lg-12" >
        <section class="panel panel-body" id="employee">
            <div class="panel-primary">
                <header class="panel-heading">
                    <h4>কর্মচারীগনের বিস্তারিত তথ্য</h4>
                </header>

                <div class="panel panel-body">
                    <table class="table table-condensed table-responsive table-bordered table-hover" id="employee_info_log" >
                        <thead>                    
                            <tr  class="success"> 
                                <th>ক্রমিক নং </th> 
                                <th>ভবনের নাম </th>
                                <th>ফ্লোর</th>
                                <th>বিভাগ/সেকশন </th>
                                <th>লাইন </th>
                                <th>নাম </th>
                                <th>পদবি </th>
                                <th>যোগদানের তারিখ </th>
                                <th>কার্ড নং </th>
                                <th>গ্রেড </th>
                                <th>বর্তমান বেতন </th>
                                <th>শেষ বর্ধিত তারিখ </th>
                                <th>শেষ বর্ধিত টাকা </th>
                                <th>মোবাইল নং </th>
                                <th>জাতীয় পরিচয় পত্র নং</th>
                                <th>পদোন্নতির তারিখ </th>
                                <th>পিতা/স্বামীর নাম </th>
                                <th>গ্রাম </th>
                                <th>ডাকঘর </th> 
                                <th>থানা </th>
                                <th>জেলা </th>
                                <th>গ্রাম </th>
                                <th>ডাকঘর </th> 
                                <th>থানা </th>
                                <th>জেলা </th>
                                <th>রেফারেন্স </th>
                                <th>শিক্ষাগত যোগ্যতা </th>
                                <th>মন্তব্য </th>						
                            </tr>                        
                        </thead>

                        <tfoot>
                            <tr>
                                <th>ক্রমিক নং </th> 
                                <th>ভবনের নাম </th>
                                <th>ফ্লোর</th>
                                <th>বিভাগ/সেকশন </th>
                                <th>লাইন </th>
                                <th>নাম </th>
                                <th>পদবি </th>
                                <th>যোগদানের তারিখ </th>
                                <th>কার্ড নং </th>
                                <th>গ্রেড </th>
                                <th>বর্তমান বেতন </th>
                                <th>শেষ বর্ধিত তারিখ </th>
                                <th>শেষ বর্ধিত টাকা </th>
                                <th>মোবাইল নং </th>
                                <th>জাতীয় পরিচয় পত্র নং</th>
                                <th>পদোন্নতির তারিখ </th>
                                <th>পিতা/স্বামীর নাম </th>
                                <th>গ্রাম </th>
                                <th>ডাকঘর </th> 
                                <th>থানা </th>
                                <th>জেলা </th>
                                <th>গ্রাম </th>
                                <th>ডাকঘর </th> 
                                <th>থানা </th>
                                <th>জেলা </th>
                                <th>রেফারেন্স </th>
                                <th>শিক্ষাগত যোগ্যতা </th>

                                <th>মন্তব্য </th>                                                 
                            </tr>                        
                        </tfoot>

                        <tbody>
                            <?php
                            $bn_digits = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');
                            $count = 1;
                            foreach ($results as $monthly_employee_wages) {
                                ?>
                                <tr>
                                    <td><?php
                                        echo str_replace(range(0, 9), $bn_digits, '' . $count);
                                        $count++;
                                        ?>
                                    </td>
                                    <td><?php echo $monthly_employee_wages->BuildingName; ?> </td>
                                    <td><?php echo $monthly_employee_wages->Floor ?> </td>
                                    <td><?php echo $monthly_employee_wages->Department ?> </td>
                                    <td><?php echo $monthly_employee_wages->Line ?> </td>							
                                    <td><a href="<?php echo base_url(); ?>con_set_worker_profile/edit1/<?php echo $monthly_employee_wages->CardNo; ?>" target="_blank" title="সংশোধন করুন"><strong><?php echo $monthly_employee_wages->Name; ?></strong> </a></td>
                                    <td><?php echo $monthly_employee_wages->Designation ?> </td>
                                    <td><?php echo str_replace(range(0, 9), $bn_digits, '' . $monthly_employee_wages->JoiningDate); ?> </td>
                                    <td><?php echo str_replace(range(0, 9), $bn_digits, '' . $monthly_employee_wages->CardNo); ?> </td>
                                    <td><?php echo $monthly_employee_wages->Grade ?> </td>
                                    <td><?php echo str_replace(range(0, 9), $bn_digits, '' . $monthly_employee_wages->GrossSalary); ?> </td>
                                    <td><?php echo str_replace(range(0, 9), $bn_digits, '' . $monthly_employee_wages->LastIncrementDate); ?> </td>
                                    <td><?php echo str_replace(range(0, 9), $bn_digits, '' . $monthly_employee_wages->LastIncrementMoney); ?> </td>
                                    <td><?php echo str_replace(range(0, 9), $bn_digits, '' . $monthly_employee_wages->ContactNo); ?> </td> 
                                    <td><?php echo str_replace(range(0, 9), $bn_digits, '' . $monthly_employee_wages->NID); ?> </td> 
                                    <td><?php echo str_replace(range(0, 9), $bn_digits, '' . $monthly_employee_wages->PromotionDate); ?> </td>
                                    <td><?php echo $monthly_employee_wages->GuardianName; ?> </td>
                                    <td><?php echo $monthly_employee_wages->PermanentVillage; ?> </td>
                                    <td><?php echo $monthly_employee_wages->PermanenttPost; ?> </td>
                                    <td><?php echo $monthly_employee_wages->PermanentThana; ?> </td>
                                    <td><?php echo $monthly_employee_wages->PermanentDistrict; ?> </td>
                                    <td><?php echo $monthly_employee_wages->PresentVillage; ?> </td>
                                    <td><?php echo $monthly_employee_wages->PresentPost; ?> </td>
                                    <td><?php echo $monthly_employee_wages->PresentThana; ?> </td>
                                    <td><?php echo $monthly_employee_wages->PresentDistrict; ?> </td>
                                    <td><?php echo $monthly_employee_wages->Reference; ?> </td>
                                    <td><?php echo $monthly_employee_wages->EducationalQual; ?> </td>
                                    
                                    <td><?php echo $monthly_employee_wages->Comment; ?> </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div> 
            </div>
        </section>
    </div>
</div>
