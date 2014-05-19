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
        echo form_open('con_pro_attn_mismatch_report/insert1', $attr);
        
            ?>
            <input type="hidden" name="CardNo" value="<?php $mismatch['CardNo']; ?>"/>
            <div class="form-group">
                <label for="CardNo" class="col-sm-3 control-label" >Card No</label>
                <div class="col-sm-9">
                    <input type="text" name="CardNo"  class="form-control" id="CardNo" value="<?php echo $mismatch['CardNo']; ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="Percentage" class="col-sm-3 control-label" >Name</label>
                <div class="col-sm-9">
                    <input type="text" name="Percentage"  class="form-control" id="id_Percentage" value="<?php echo $mismatch['Name']; ?>" disabled="">
                </div>
            </div>
            <div class="form-group">
                <label for="Head" class="col-sm-3 control-label" >Section</label>
                <div class="col-sm-9">
                    <input type="text" name="Head"  class="form-control" id="id_Head" value="<?php echo $mismatch['Department']; ?>" disabled="">
                </div>
            </div>
            
            
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
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    <input type="submit" name="update"  class="btn btn-primary" id="update" value="Update">
                </div>
            </div>
    <?php

echo form_close();
?>
    </div>
    <div class='col-lg-3'>
    </div>
</div>