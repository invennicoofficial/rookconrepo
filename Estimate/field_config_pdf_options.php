<?php error_reporting(0);
include_once('../include.php');
checkAuthorised('estimate');
$estimate_types = explode(',',get_config($dbc, 'project_tabs')); ?>
<script>
$(document).ready(function() {
	$('textarea').change(saveText);
});
function saveText() {
	$.ajax({
		url: 'estimates_ajax.php?action=settings_general',
		method: 'POST',
		data: {
			name: this.name,
			value: this.value
		}
	});
}
</script>
<h3>Estimate PDF Options</h3>
<div class="form-horizontal">
	<div class="form-group">
		<label class="col-sm-4 control-label">PDF Notes:<br><em>(e.g. - details about quote, etc., displayed immediately after the quote)</em></label>
		<div class="col-sm-8">
			<textarea name="quote_sign_notes" data-table="general_configuration"><?= get_config($dbc, "quote_sign_notes") ?></textarea>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label">PDF Terms:<br><em>Will be displayed in a smaller font at the bottom of the quote.</em></label>
		<div class="col-sm-8">
			<textarea name="quote_terms" data-table="general_configuration"><?= get_config($dbc, "quote_terms") ?></textarea>
		</div>
	</div>
</div>