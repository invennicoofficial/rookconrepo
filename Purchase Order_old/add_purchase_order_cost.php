<?php if (strpos($value_config, ','."Description".',') !== FALSE) { ?>
<div class="form-group">
    <label for="additional_note" class="col-sm-4 control-label">Description:</label>
    <div class="col-sm-8">
        <textarea name="description" rows="5" cols="50" class="form-control"><?php echo $description; ?></textarea>
    </div>
</div>
<?php } ?>
<?php if (strpos($value_config, ','."Cost".',') !== FALSE) { ?>
<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Cost<span class="text-red">*</span>:</label>
    <div class="col-sm-8">
      <input name="cost" type="text" id="cost" value="<?php echo $cost; ?>" class="form-control">
    </div>
</div>
<?php } ?>

<?php if (strpos($value_config, ','."Sales Tax %".',') !== FALSE) { ?>
<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Sales Tax %:</label>
    <div class="col-sm-8">
      <input name="mark_up" type="text" id="mark_up" value = "<?php if($mark_up == '') { echo "5"; } else { echo $mark_up;}?>" onKeyUp="totalCost();" class="form-control">
    </div>
</div>
<?php } ?>

<?php if (strpos($value_config, ','."Total Cost".',') !== FALSE) { ?>
<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total Cost<span class="text-red">*</span>:</label>
    <div class="col-sm-8">
      <input name="total_cost" id="total_cost" value = "<?php if($total_cost !== '') { echo $total_cost; }?>" type="text" class="form-control">
    </div>
</div>
<?php } ?>