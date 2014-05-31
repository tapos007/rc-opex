<script>
    $(document).ready(function() {
       

    });
</script>

<div class='row'>
    <div class='col-lg-3'></div>
    <div class='col-lg-6'>
        <fieldset>
            <legend>Update Mismatch Access Log</legend>
        </fieldset>                
        <?php
        $attr = array(
            'class' => 'form-horizontal',
            'role' => 'form'
        );
        echo form_open('con_pro_daily_absent_report/InsertAbsentEmployee', $attr);
//        echo '<pre>';
//        print_r($anEmployeeInfo);
//        echo '</pre>';
//        exit();
        foreach ($anEmployeeInfo as $employee) {
            ?>
            
            <div class="form-group">
                <label for="CardNo" class="col-sm-3 control-label" >Card No</label>
                <div class="col-sm-9">
                    <input type="text" name="CardNo"  class="form-control" id="CardNo" value="<?php echo $employee['CardNo']; ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="Percentage" class="col-sm-3 control-label" >Name</label>
                <div class="col-sm-9">
                    <input type="text" name="Name"  class="form-control" id="id_Percentage" value="<?php echo $employee['Name']; ?>" disabled="">
                </div>
            </div>
            <div class="form-group">
                <label for="Head" class="col-sm-3 control-label" >Section</label>
                <div class="col-sm-9">
                    <input type="text" name="Department"  class="form-control" id="id_Head" value="<?php echo $employee['Department']; ?>" disabled="">
                </div>
            </div>
            <div class="form-group">
                <label for="Percentage" class="col-sm-3 control-label" >Line</label>
                <div class="col-sm-9">
                    <input type="text" name="Line"  class="form-control" id="id_Percentage" value="<?php echo $employee['Line']; ?>" disabled="">
                </div>
            </div>
            <?php
//            $date = date('Y-m-d', strtotime($mismatch['DateTime']));
//            $time = date('H:i:s', strtotime($mismatch['DateTime']));
           ?>
            <div class="form-group">
                <label for="Percentage" class="col-sm-3 control-label" >In Time</label>
                <div class="col-sm-9">
                    <input type="text" name="InTime"  class="form-control" id="InTime" value="<?php
//                    if (date('H:i:s', strtotime($mismatch['DateTime'])) < date('H:i:s', strtotime('10:59:59'))) {
//                        echo date('d-m-Y H:i:s', strtotime($mismatch['DateTime']));
//                    }
                    ?>">
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    <input type="submit" name="update"  class="btn btn-primary" id="update" value="Update">
                </div>
            </div>
    <?php
}
echo form_close();
?>
    </div>
    <div class='col-lg-3'>
    </div>
</div>
