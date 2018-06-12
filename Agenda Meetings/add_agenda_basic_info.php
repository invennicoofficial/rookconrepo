<?php if (strpos($value_config, ','."Agenda Topic".',') !== FALSE) { ?>
<div class="form-group clearfix completion_date">
    <label for="first_name" class="col-sm-4 control-label" style="text-align:left;">Agendas Topic(s):</label>
    <div class="clearfix"></div>
    <div><input name="agenda_topic" value="<?php echo $agenda_topic; ?>" type="text" class="form-control" /></div>
</div>
<?php } ?>

<?php if (strpos($value_config, ','."Agenda Notes".',') !== FALSE) { ?>
<div class="form-group">
    <label for="site_name" class="col-sm-4 control-label" style="text-align:left;">Agendas Note:</label>
    <div class="clearfix"></div>
    <div><textarea name="agenda_note" rows="3" cols="50" class="form-control"><?php echo html_entity_decode($agenda_note); ?></textarea></div>
</div>
<?php } ?>