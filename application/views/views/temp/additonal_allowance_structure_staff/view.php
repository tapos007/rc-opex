<style>
    label.error{
        color: red;
        font-weight: bold;
    }
</style>
<script>
    $(document).ready(function() {
        $("#profile").validate({
            rules: {
                Head: "required",
                StaffAmount: "required"
            },
            messages: {
                Head: "Please enter your first name",
                StaffAmount: "Please enter your last name"
            }
        });

    });
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<div class='row'>
    <?php
    if ($this->session->flashdata('msg')) {
        ?>
        <script>
    $(document).ready(function() {
        $('#myModal').modal('show');
    });
        </script>
        <?php
    }
    ?>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Status....</h4>
                </div>
                <div class="modal-body">
                    <?php echo $this->session->flashdata('msg'); ?>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>                    
                </div>
            </div>
        </div>
    </div>
    <div class='col-lg-2'></div>
    <div class='col-lg-8'>
        <fieldset>
            <legend>Create Staff Additional Allowance</legend>
        </fieldset>
<!--        <a href="<?php //echo site_url('con_set_additonal_allowance_structure_staff/create');   ?>">
            <button class="btn btn-info"><i class="glyphicon glyphicon-plus"></i>Add</button>
        </a>-->
        <?php
        $attr = array(
            'class' => 'form-horizontal',
            'role' => 'form',
            'id' => 'profile'
        );
        echo form_open('con_set_additonal_allowance_structure_staff/insert', $attr);
        ?>
        <div class="form-group">
            <label for="Head" class="col-sm-3 control-label" >Head</label>
            <div class="col-sm-9">
                <input type="text" name="Head"  class="form-control" id="id_Head" placeholder="Enter Head">
            </div>
        </div>
        <div class="form-group">
            <label for="StaffAmount" class="col-sm-3 control-label" >Staff Amount</label>
            <div class="col-sm-9">
                <input type="text" name="StaffAmount"  class="form-control" id="id_StaffAmount" placeholder="Enter StaffAmount">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-10">
                <button type="submit" name="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
        <?php echo form_close(); ?>

        <fieldset>
            <legend>View Staff Additional Allowance</legend>
        </fieldset>
        <table class="table">            
            <thead>
                <tr class="active">
                    <th>Sl</th>
                    <th>Head</th>
                    <th>StaffAmount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = 1;
                foreach ($tbl_additonal_allowance_structure_staff as $rec_additonal_allowance_structure_staff) {
                    ?>
                    <tr class="success">
                        <td><?php echo $count++; ?></td>
                        <td><?php echo $rec_additonal_allowance_structure_staff->Head; ?> </td>
                        <td><?php echo $rec_additonal_allowance_structure_staff->StaffAmount; ?> </td>
                        <td>
                            <?php echo form_open('con_set_additonal_allowance_structure_staff/edit'); ?>
                            <input type="hidden" name="ID" id="ID" value="<?php echo $rec_additonal_allowance_structure_staff->ID; ?>"/>
                            <button class="btn btn-primary" name="submit" value="edit"><i class="glyphicon glyphicon-edit"></i></button>
                            <button class="btn btn-danger" name="submit" value="delete" onclick="return confirm('Are u sure u want to delete')"><i class="glyphicon glyphicon-trash"></i></button>
                            <?php echo form_close(); ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <div class='col-lg-2'></div>
</div>
