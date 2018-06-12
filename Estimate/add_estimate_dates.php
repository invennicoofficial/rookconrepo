<?php if($expiry_date == '' && is_numeric($expiry_length)) {
	$expiry_date = date('Y-m-d', strtotime($created_date.' + '.$expiry_length.' days'));
} ?>
<div class="form-group clearfix">
    <label for="first_name" class="col-sm-4 control-label text-right">Date Estimate Created:</label>
    <div class="col-sm-8">
        <input name="created_date" value="<?php echo $created_date; ?>" type="text" class="datepicker"></p>
    </div>
</div>

<div class="form-group clearfix">
    <label for="first_name" class="col-sm-4 control-label text-right">Project Start Date:</label>
    <div class="col-sm-8">
        <input name="start_date" value="<?php echo $start_date; ?>" type="text" class="datepicker"></p>
    </div>
</div>

<div class="form-group clearfix">
    <label for="first_name" class="col-sm-4 control-label text-right">Expiration Date:</label>
    <div class="col-sm-8">
        <input name="expiry_date" value="<?php echo $expiry_date; ?>" type="text" class="datepicker"></p>
    </div>
</div>