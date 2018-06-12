<?php error_reporting(0);
include_once('../include.php');
checkAuthorised('estimate');
$estimate_status = explode('#*#',get_config($dbc, 'estimate_status'));
$estimate_report_stats = explode('#*#',get_config($dbc, 'estimate_report_stats'));
$estimate_report_alerts = explode('#*#',get_config($dbc, 'estimate_report_alerts'));
?>
<script>
$(document).ready(function() {
	$('input').change(saveReporting);
});
function saveReporting() {
	var estimate_report_stats = [];
	var estimate_report_alerts = [];
	$('[name="estimate_report_stats[]"]').each(function() {
		if($(this).is(':checked')) {
			estimate_report_stats.push(this.value);
		}
	});
	$('[name="estimate_report_alerts[]"]').each(function() {
		if($(this).is(':checked')) {
			estimate_report_alerts.push(this.value);
		}
	});
	$.ajax({
		url: 'estimates_ajax.php?action=setting_reporting',
		method: 'POST',
		data: {
			stats: estimate_report_stats,
			alerts: estimate_report_alerts
		}
	});
}
</script>
<h3>Estimate Stats</h3>
<div class="form-group">
<?php foreach($estimate_status as $status) { ?>
	<div class="col-sm-2">
		<label><input type="checkbox" name="estimate_report_stats[]" value="<?= $status ?>" <?= (in_array($status, $estimate_report_stats) ? 'checked' : '') ?>>&nbsp;&nbsp;<?= $status ?></label>
	</div>
<?php } ?>
</div>

<div class="clearfix"></div>

<h3>Estimate Alerts</h3>
<div class="form-group">
<?php foreach($estimate_status as $status) { ?>
	<div class="col-sm-2">
		<label><input type="checkbox" name="estimate_report_alerts[]" value="<?= $status ?>" <?= (in_array($status, $estimate_report_alerts) ? 'checked' : '') ?>>&nbsp;&nbsp;<?= $status ?></label>
	</div>
<?php } ?>
</div>
<?php if(basename($_SERVER['SCRIPT_FILENAME']) == 'field_config_reporting.php') { ?>
	<div style="display:none;"><?php include('../footer.php'); ?></div>
<?php } ?>