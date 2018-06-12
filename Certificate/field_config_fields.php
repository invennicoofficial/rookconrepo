<?php error_reporting(0);
include_once('../include.php');
checkAuthorised('certificate');
$field_config = explode(",",get_field_config($dbc, 'certificate')); ?>
<script>
function save_options() {
	statusUnsaved($('[name="certificate_field[]"]').get(0));
	var field_list = '';
	$('[name="certificate_field[]"]:checked').each(function() {
		field_list += this.value + ',';
	});
	statusSaving();
	$.ajax({
		url: 'certificate_ajax.php?action=update_field',
		method: 'POST',
		data: { table: 'field_config', field: 'certificate', id_field: 'fieldconfigid', id: 1, value: field_list },
		response: 'html',
		success: function(response) {
			statusDone($('[name="certificate_field[]"]').get(0));
			console.log(response);
		}
	});
}
</script>
<h3>Settings - Dashboard</h3>
<div class="content-block main-screen-white">
	<label class="col-sm-4">Dashboard Fields</label>
	<div class="col-sm-8">
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Certificate Type', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Certificate Type" onchange="save_options();">Certificate Type</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Category', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Category" onchange="save_options();">Category</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Heading', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Heading" onchange="save_options();">Heading</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Cost', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Cost" onchange="save_options();">Cost</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Description', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Description" onchange="save_options();">Description</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Quote Description', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Quote Description" onchange="save_options();">Quote Description</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Final Retail Price', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Final Retail Price" onchange="save_options();">Final Retail Price</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Admin Price', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Admin Price" onchange="save_options();">Admin Price</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Wholesale Price', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Wholesale Price" onchange="save_options();">Wholesale Price</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Commercial Price', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Commercial Price" onchange="save_options();">Commercial Price</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Client Price', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Client Price" onchange="save_options();">Client Price</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Minimum Billable', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Minimum Billable" onchange="save_options();">Minimum Billable</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Estimated Hours', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Estimated Hours" onchange="save_options();">Estimated Hours</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Actual Hours', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Actual Hours" onchange="save_options();">Actual Hours</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('MSRP', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="MSRP" onchange="save_options();">MSRP</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Certificate Code', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Certificate Code" onchange="save_options();">Certificate Code</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Invoice Description', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Invoice Description" onchange="save_options();">Invoice Description</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Ticket Description', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Ticket Description" onchange="save_options();">Ticket Description</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Name', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Name" onchange="save_options();">Name</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Fee', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Fee" onchange="save_options();">Fee</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Unit Price', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Unit Price" onchange="save_options();">Unit Price</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Unit Cost', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Unit Cost" onchange="save_options();">Unit Cost</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Rent Price', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Rent Price" onchange="save_options();">Rent Price</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Rental Days', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Rental Days" onchange="save_options();">Rental Days</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Rental Weeks', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Rental Weeks" onchange="save_options();">Rental Weeks</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Rental Months', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Rental Months" onchange="save_options();">Rental Months</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Rental Years', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Rental Years" onchange="save_options();">Rental Years</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Certificate Reminder Email', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Certificate Reminder Email" onchange="save_options();">Certificate Reminder Email</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Reminder/Alert', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Reminder/Alert" onchange="save_options();">Reminder/Alert</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Daily', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Daily" onchange="save_options();">Daily</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Weekly', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Weekly" onchange="save_options();">Weekly</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Monthly', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Monthly" onchange="save_options();">Monthly</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Annually', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Annually" onchange="save_options();">Annually</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('#Of Days', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="#Of Days" onchange="save_options();">#Of Days</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('#Of Hours', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="#Of Hours" onchange="save_options();">#Of Hours</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('#Of Kilometers', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="#Of Kilometers" onchange="save_options();">#Of Kilometers</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('#Of Miles', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="#Of Miles" onchange="save_options();">#Of Miles</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Title', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Title" onchange="save_options();">Title</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Uploader', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Uploader" onchange="save_options();">Uploader</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Link', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Link" onchange="save_options();">Link</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Staff', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Staff" onchange="save_options();">Staff</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Projects', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Projects" onchange="save_options();">Projects</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Client Project', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Client Project" onchange="save_options();">Client Project</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Jobs', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Jobs" onchange="save_options();">Jobs</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Issue Date', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Issue Date" onchange="save_options();">Issue Date</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Reminder Date', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Reminder Date" onchange="save_options();">Reminder Date</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('Expiry Date', $field_config) ? 'checked' : '' ?> name="certificate_field[]" value="Expiry Date" onchange="save_options();">Expiry Date</label>
	</div>
	<div class="clearfix"></div>
</div>