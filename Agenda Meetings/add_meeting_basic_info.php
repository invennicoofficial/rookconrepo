<?php if (strpos($value_config, ','."Meeting Topic".',') !== FALSE) { ?>
<div class="form-group clearfix completion_date">
    <label for="first_name" class="col-sm-4 control-label" style="text-align:left;">Meeting Topic(s):</label>
    <div class="clearfix"></div>
    <div><input name="meeting_topic" value="<?php echo $meeting_topic; ?>" type="text" class="form-control" /></div>
</div>
<?php } ?>

<?php if (strpos($value_config, ','."Meeting Notes".',') !== FALSE) { ?>
<div class="form-group">
    <label for="site_name" class="col-sm-4 control-label" style="text-align:left;">Meeting Note:</label>
    <div class="clearfix"></div>
    <div><textarea name="meeting_note" rows="3" cols="50" class="form-control"><?php echo html_entity_decode($meeting_note); ?></textarea></div>
</div>
<?php } ?>

<?php if (strpos($value_config, ','."Client Deliverables".',') !== FALSE) { ?>
<div class="form-group">
    <label for="site_name" class="col-sm-4 control-label" style="text-align:left;">Client Deliverables:</label>
    <div class="clearfix"></div>
    <div><textarea name="client_deliverables" rows="3" cols="50" class="form-control"><?php echo html_entity_decode($client_deliverables); ?></textarea></div>
</div>
<?php } ?>

<?php if (strpos($value_config, ','."Company Deliverables".',') !== FALSE) { ?>
<div class="form-group">
    <label for="site_name" class="col-sm-4 control-label" style="text-align:left;">Company Deliverables:</label>
    <div class="clearfix"></div>
    <div><textarea name="company_deliverables" rows="3" cols="50" class="form-control"><?php echo html_entity_decode($company_deliverables); ?></textarea></div>
</div>
<?php } ?>