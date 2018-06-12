<?php include_once('../include.php');
checkAuthorised('contracts');
$contract_fields = mysqli_fetch_array(mysqli_query($dbc, "SELECT `fields` FROM `field_config_contracts`"))['fields'];
if(empty($contract_fields)) {
	$contract_fields = 'Sub Section Heading,Third Tier Heading,Business';
}
$contract_fields = explode(',',$contract_fields); ?>

<script type="text/javascript">
$(document).ready(function() {
	$('.contract_fields input').change(saveFields);
});
function saveFields() {
	var contract_fields = [];
	$('[name="contract_fields"]').each(function() {
		if($(this).is(':checked')) {
			contract_fields.push($(this).val());
		}
	});
	$.ajax({
		url: '../Contract/contract_ajax.php?action=settings_fields',
		method: 'POST',
		data: { contract_fields: contract_fields },
		success: function() {

		}
	});
}
</script>

<div class="contract_fields gap-left gap-right">
	<h4>Fields</h4>
	<div class="block-group">
		<label class="form-checkbox"><input type="checkbox" name="contract_fields" value="Category" checked disabled> Category</label>
		<label class="form-checkbox"><input type="checkbox" name="contract_fields" value="Business" <?= in_array('Business', $contract_fields) ? 'checked' : '' ?>> Business</label>
		<label class="form-checkbox"><input type="checkbox" name="contract_fields" value="Sub Section Heading" <?= in_array('Sub Section Heading', $contract_fields) ? 'checked' : '' ?>> Sub Section Heading</label>
		<label class="form-checkbox"><input type="checkbox" name="contract_fields" value="Third Tier Heading" <?= in_array('Third Tier Heading', $contract_fields) ? 'checked' : '' ?>> Third Tier Heading</label>
	</div>
</div>