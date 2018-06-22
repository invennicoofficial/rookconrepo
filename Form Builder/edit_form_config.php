<?php //Form Builder Configuration
$assigned_tiles = ','.(!empty($form['assigned_tile']) ? $form['assigned_tile'] : '').',';
$attached_contacts = ','.$form['attached_contacts'].',';
$subtab = !empty($form['subtab']) ? $form['subtab'] : '';
$subtab_list = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_user_forms`"))['subtabs'];
$subtab_list = explode(',', $subtab_list);
$form_layout = !empty($form['form_layout']) ? $form['form_layout'] : 'Accordions';
?>
<script type="text/javascript">
$(document).ready(function() {
	$('.form-content input,select,textarea').on('change', function() { saveConfig(this); });
});
$(document).on('change', 'select[name="assigned_tile[]"]', function() { changeAssignedTile(); });
function saveConfig(field) {
	var formid = $('[name="formid"]').val();
	var assigned_tiles = [];
	$('[name="assigned_tile[]"]').find('option:selected').each(function() {
		assigned_tiles.push($(this).val());
	});
	assigned_tiles = JSON.stringify(assigned_tiles);
	var attached_contacts = [];
	$('[name="attached_contacts[]"]').find('option:selected').each(function() {
		attached_contacts.push($(this).val());
	});
	attached_contacts = JSON.stringify(attached_contacts);
	var intake_field = $('[name="intake_field"]').val();
	var subtab = $('[name="subtab"]').val();
	var form_layout = $('[name="form_layout"]:checked').val();

	var field_data = { formid: formid, assigned_tiles: assigned_tiles, attached_contacts: attached_contacts, intake_field: intake_field, subtab: subtab, form_layout: form_layout };
	$.ajax({
		url: '../Form Builder/form_ajax.php?fill=update_config',
		type: 'POST',
		data: field_data,
		success: function(response) {
			console.log(response);
		}
	});
}
function changeAssignedTile() {
	$('.intake_field').hide();
	$('.attached_contacts').hide();
	$('[name="assigned_tile[]"]').find('option:selected').each(function() {
		if(this.value == 'intake') {
			$('.intake_field').show();
		}
		if(this.value == 'attach_contact') {
			$('.attached_contacts').show();
		}
	});
}
</script>
<div class="collapsible tile-sidebar" style="height: 100%;">
	<ul class="sidebar" style="padding-top: 1em;">
		<a href="" onclick="return false;"><li class="active">Form Builder Configuration</li></a>
	</ul>
</div>
<div class="scale-to-fill has-main-screen">
	<div class="main-screen form-content">
		<input type="hidden" name="formid" value="<?= $formid ?>">
		<div class="form-horizontal col-sm-12">
			<h3>Form Builder Configuration</h3>
			<div class="form-group">
				<label class="col-sm-4 control-label">Assigned Tiles:</label>
				<div class="col-sm-8">
					<select name="assigned_tile[]" multiple data-placeholder="Select Tiles..." class="form-control chosen-select-deselect">
						<option></option>
						<option <?= strpos($assigned_tiles, ',attach_contact,') !== FALSE ? 'selected' : '' ?> value='attach_contact'>Attach to Contact</option>
						<option <?= strpos($assigned_tiles, ',contracts,') !== FALSE ? 'selected' : '' ?> value='contracts'>Contracts</option>
						<option <?= strpos($assigned_tiles, ',hr,') !== FALSE ? 'selected' : '' ?> value='hr'>HR</option>
						<option <?= strpos($assigned_tiles, ',infogathering,') !== FALSE ? 'selected' : '' ?> value='infogathering'>Information Gathering</option>
						<option <?= strpos($assigned_tiles, ',intake,') !== FALSE ? 'selected' : '' ?> value='intake'>Intake Forms</option>
						<option <?= strpos($assigned_tiles, ',project,') !== FALSE ? 'selected' : '' ?> value='project'>Projects</option>
						<option <?= strpos($assigned_tiles, ',performance_review,') !== FALSE ? 'selected' : '' ?> value='performance_review'>Performance Reviews</option>
						<option <?= strpos($assigned_tiles, ',safety,') !== FALSE ? 'selected' : '' ?> value='safety'>Safety</option>
						<option <?= strpos($assigned_tiles, ',treatment,') !== FALSE ? 'selected' : '' ?> value='treatment'>Treatment Charts</option>
					</select>
				</div>
			</div>
			<div class="form-group intake_field" <?= strpos($assigned_tiles, ',intake,') !== FALSE ? '' : 'style="display: none;"' ?>>
				<label class="col-sm-4 control-label">Intake Form Contact Field:</label>
				<div class="col-sm-8">
					<select name="intake_field" data-placeholder="Select a Field..." class="form-control chosen-select-deselect">
						<option></option>
						<?php $field_list = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `user_form_fields` WHERE `form_id` = '$formid' AND `type` = 'CONTACTINFO' AND `deleted` = 0"),MYSQLI_ASSOC);
						foreach ($field_list as $contact_field) {
							echo '<option value="'.$contact_field['field_id'].'" '.($contact_field['field_id'] == $form['intake_field'] ? 'selected' : '').'>'.$contact_field['label'].'</option>';

						} ?>
					</select>
				</div>
			</div>
			<div class="form-group attached_contacts" <?= strpos($assigned_tiles, ',attach_contact,') !== FALSE ? '' : 'style="display:none;"' ?>>
				<label class="col-sm-4 control-label">Attached Contacts:</label>
				<div class="col-sm-8">
					<select name="attached_contacts[]" multiple data-placeholder="Select a Contact" class="form-control chosen-select-deselect">
						<option></option>
						<?php $contacts_list = sort_contacts_query(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `deleted` = 0 AND `status` > 0"));
						foreach($contacts_list as $attached_contact) {
							if(!empty($attached_contact['full_name']) && $attached_contact['full_name'] != '-') {
								echo '<option value="'.$attached_contact['contactid'].'" '.(strpos($attached_contacts, ','.$attached_contact['contactid'].',') !== FALSE ? 'selected' : '').'>'.$attached_contact['full_name'].'</option>';
							}
						} ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Subtab:</label>
				<div class="col-sm-8">
					<select name="subtab" data-placeholder="Select a Subtab..." class="form-control chosen-select-deselect">
						<option></option>
						<?php foreach ($subtab_list as $subtab_option) {
							echo '<option value="'.$subtab_option.'" '.($subtab == $subtab_option ? 'selected' : '').'>'.$subtab_option.'</option>';
						} ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Form Layout:</label>
				<div class="col-sm-8">
					<label class="form-checkbox"><input type="radio" name="form_layout" value="Accordions" <?= $form_layout == 'Accordions' ? 'checked' : '' ?>> Accordions</label>
					<label class="form-checkbox"><input type="radio" name="form_layout" value="Sidebar" <?= $form_layout == 'Sidebar' ? 'checked' : '' ?>>Sidebar Navigation</label>
				</div>
			</div>
		</div>
	</div>
</div>