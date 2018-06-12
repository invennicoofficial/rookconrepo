<?php include_once('../include.php');
if(FOLDER_NAME == 'posadvanced') {
    checkAuthorised('posadvanced');
} else {
    checkAuthorised('check_out');
}
error_reporting(0);
$invoice_search = FOLDER_NAME.'_search_fields';
$invoice_ux = FOLDER_NAME.'_ux'; ?>
<script>
$(document).ready(function() {
	$('input').change(saveOptions);
});
function saveOptions() {
	var search = [];
	$('[name="<?= $invoice_search ?>[]"]:checked').each(function() {
		search.push(this.value);
	});
	$.ajax({
		url: '../Invoice/invoice_ajax.php?action=general_config',
		method: 'POST',
		data: {
			name: '<?= $invoice_search ?>',
			value: search
		}
	});
	var search = [];
	$('[name="<?= $invoice_ux ?>[]"]:checked').each(function() {
		search.push(this.value);
	});
	$.ajax({
		url: '../Invoice/invoice_ajax.php?action=general_config',
		method: 'POST',
		data: {
			name: '<?= $invoice_ux ?>',
			value: search
		}
	});
}
</script>
<div class="block-group form-horizontal">
	<h3>Search Fields</h3>
	<?php $search_fields = array_filter(explode(',',get_config($dbc, $invoice_search)));
	foreach(['Invoice #','Contact Name','Delivery Type','Invoice Date'] as $search_field) { ?>
		<label class="form-checkbox"><input <?= (count($search_fields) == 0 || in_array($search_field,$search_fields) ? 'checked' : '') ?> type="checkbox" name="<?= $invoice_search ?>[]" value="<?= $search_field ?>"> <?= $search_field ?></label>
	<?php } ?>
	<h3>Interface</h3>
	<?php $ux_fields = array_filter(explode(',',get_config($dbc, $invoice_ux))); ?>
	<label class="form-checkbox"><input <?= (count($ux_fields) == 0 || in_array('standard',$ux_fields) ? 'checked' : '') ?> type="checkbox" name="<?= $invoice_ux ?>[]" value="standard"> Standard Interface</label>
	<label class="form-checkbox"><input <?= (in_array('touch',$ux_fields) ? 'checked' : '') ?> type="checkbox" name="<?= $invoice_ux ?>[]" value="touch"> Touch Interface</label>
</div>
<?php if(basename($_SERVER['SCRIPT_FILENAME']) == 'field_config_options.php') { ?>
	<div style="display:none;"><?php include('../footer.php'); ?></div>
<?php } ?>