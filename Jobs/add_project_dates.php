<?php
if(!empty($_GET['projectid'])) {
            $projectid = $_GET['projectid'];
            $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"select * from jobs WHERE projectid='$projectid'"));
            $created_date = $get_contact['created_date'];
            $start_date = $get_contact['start_date'];
            $estimated_completed_date = $get_contact['estimated_completed_date'];
            $completion_date = $get_contact['completion_date'];


}?>

<div class="form-group clearfix">
    <label for="first_name" class="col-sm-4 control-label text-right">Date <?php echo (JOBS_TILE == 'Projects' ? 'Project' : (JOBS_TILE == 'Jobs' ? 'Job' : JOBS_TILE)); ?> Created:</label>
    <div class="col-sm-8">
        <input name="created_date" value="<?php echo $created_date; ?>" type="text" class="datepicker"></p>
    </div>
</div>

<div class="form-group clearfix">
    <label for="first_name" class="col-sm-4 control-label text-right">Start Date:</label>
    <div class="col-sm-8">
        <input name="start_date" value="<?php echo $start_date; ?>" type="text" class="datepicker"></p>
    </div>
</div>

<div class="form-group clearfix">
    <label for="first_name" class="col-sm-4 control-label text-right">Estimated Completion Date:</label>
    <div class="col-sm-8">
        <input name="estimated_completed_date" value="<?php echo $estimated_completed_date; ?>" type="text" class="datepicker"></p>
    </div>
</div>

<!--
<div class="form-group clearfix">
    <label for="first_name" class="col-sm-4 control-label text-right">Actual Completion Date:</label>
    <div class="col-sm-8">
        <input name="completion_date" value="<?php echo $completion_date; ?>" type="text" class="datepicker"></p>
    </div>
</div>
-->