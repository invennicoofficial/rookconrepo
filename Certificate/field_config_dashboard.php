<?php error_reporting(0);
include_once('../include.php');
checkAuthorised('certificate');
$db_config = explode(",",get_field_config($dbc, 'certificate_dashboard')); ?>
<script>
function save_options() {
	statusUnsaved($('[name="db_field[]"]').get(0));
	var field_list = '';
	$('[name="db_field[]"]:checked').each(function() {
		field_list += this.value + ',';
	});
	statusSaving();
	$.ajax({
		url: 'certificate_ajax.php?action=update_field',
		method: 'POST',
		data: { table: 'field_config', field: 'certificate_dashboard', id_field: 'fieldconfigid', id: 1, value: field_list },
		response: 'html',
		success: function(response) {
			statusDone($('[name="db_field[]"]').get(0));
			console.log(response);
		}
	});
}
</script>
<h3>Settings - Dashboard</h3>
<div class="content-block main-screen-white">
	<label class="col-sm-4">Dashboard Fields</label>
	<div class="col-sm-8">
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Certificate Type', $db_config) ? 'checked' : '' ?> name="db_field[]" value="Certificate Type" onchange="save_options();">Certificate Type</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Category', $db_config) ? 'checked' : '' ?> name="db_field[]" value="Category" onchange="save_options();">Category</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Heading', $db_config) ? 'checked' : '' ?> name="db_field[]" value="Heading" onchange="save_options();">Heading</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Cost', $db_config) ? 'checked' : '' ?> name="db_field[]" value="Cost" onchange="save_options();">Cost</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Description', $db_config) ? 'checked' : '' ?> name="db_field[]" value="Description" onchange="save_options();">Description</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Quote Description', $db_config) ? 'checked' : '' ?> name="db_field[]" value="Quote Description" onchange="save_options();">Quote Description</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Final Retail Price', $db_config) ? 'checked' : '' ?> name="db_field[]" value="Final Retail Price" onchange="save_options();">Final Retail Price</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Admin Price', $db_config) ? 'checked' : '' ?> name="db_field[]" value="Admin Price" onchange="save_options();">Admin Price</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Wholesale Price', $db_config) ? 'checked' : '' ?> name="db_field[]" value="Wholesale Price" onchange="save_options();">Wholesale Price</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Commercial Price', $db_config) ? 'checked' : '' ?> name="db_field[]" value="Commercial Price" onchange="save_options();">Commercial Price</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Client Price', $db_config) ? 'checked' : '' ?> name="db_field[]" value="Client Price" onchange="save_options();">Client Price</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Minimum Billable', $db_config) ? 'checked' : '' ?> name="db_field[]" value="Minimum Billable" onchange="save_options();">Minimum Billable</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Estimated Hours', $db_config) ? 'checked' : '' ?> name="db_field[]" value="Estimated Hours" onchange="save_options();">Estimated Hours</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Actual Hours', $db_config) ? 'checked' : '' ?> name="db_field[]" value="Actual Hours" onchange="save_options();">Actual Hours</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('MSRP', $db_config) ? 'checked' : '' ?> name="db_field[]" value="MSRP" onchange="save_options();">MSRP</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Certificate Code', $db_config) ? 'checked' : '' ?> name="db_field[]" value="Certificate Code" onchange="save_options();">Certificate Code</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Invoice Description', $db_config) ? 'checked' : '' ?> name="db_field[]" value="Invoice Description" onchange="save_options();">Invoice Description</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Ticket Description', $db_config) ? 'checked' : '' ?> name="db_field[]" value="Ticket Description" onchange="save_options();">Ticket Description</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Name', $db_config) ? 'checked' : '' ?> name="db_field[]" value="Name" onchange="save_options();">Name</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Fee', $db_config) ? 'checked' : '' ?> name="db_field[]" value="Fee" onchange="save_options();">Fee</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Title', $db_config) ? 'checked' : '' ?> name="db_field[]" value="Title" onchange="save_options();">Title</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Uploader', $db_config) ? 'checked' : '' ?> name="db_field[]" value="Uploader" onchange="save_options();">Uploader</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Link', $db_config) ? 'checked' : '' ?> name="db_field[]" value="Link" onchange="save_options();">Link</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Staff', $db_config) ? 'checked' : '' ?> name="db_field[]" value="Staff" onchange="save_options();">Staff</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Issue Date', $db_config) ? 'checked' : '' ?> name="db_field[]" value="Issue Date" onchange="save_options();">Issue Date</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Reminder Date', $db_config) ? 'checked' : '' ?> name="db_field[]" value="Reminder Date" onchange="save_options();">Reminder Date</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Expiry Date', $db_config) ? 'checked' : '' ?> name="db_field[]" value="Expiry Date" onchange="save_options();">Expiry Date</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Status', $db_config) ? 'checked' : '' ?> name="db_field[]" value="Status" onchange="save_options();">Status</label>
	</div>
	<div class="clearfix"></div>
</div>