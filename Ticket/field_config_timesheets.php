<script>
$(document).ready(function() {
	$('[name=ticket_min_hours],[name=timesheet_hour_intervals]').change(saveFields);
});
function saveFields() {
	if(this.name == 'ticket_min_hours') {
		$.ajax({
			url: 'ticket_ajax_all.php?action=setting_tile',
			method: 'POST',
			data: {
				field: 'ticket_min_hours',
				value: $('[name=ticket_min_hours]').val()
			}
		});
	} else if(this.name == 'timesheet_hour_intervals') {
		$.ajax({
			url: 'ticket_ajax_all.php?action=setting_tile',
			method: 'POST',
			data: {
				field: 'timesheet_hour_intervals',
				value: $('[name=timesheet_hour_intervals]').val()
			}
		});
	}
}
</script>
<!-- <h1>Time Tracking</h1> -->
<div class="form-group">
	<label class="col-sm-4 control-label">Minimum Hours per <?= TICKET_NOUN ?></label>
	<div class="col-sm-8">
		<input type="number" min="0" max="24" step="0.25" name="ticket_min_hours" value="<?= get_config($dbc, 'ticket_min_hours') ?>" class="form-control">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label">Time Sheet Tracking Intervals</label>
	<div class="col-sm-8">
		<input type="number" min="0" max="2" step="0.05" name="timesheet_hour_intervals" value="<?= get_config($dbc, 'timesheet_hour_intervals') ?>" class="form-control">
	</div>
</div>