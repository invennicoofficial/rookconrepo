<script>
$(document).ready(function() {
    $('.block-group input,.block-group textarea').change(saveFields);

	$('[name=subtabs],[name=bypass_tabs]').change(function() {
		tabs = [];
		$('[name=subtabs]:checked').each(function() { tabs.push(this.value); });
		bypass_tabs = [];
		$('[name=bypass_tabs]:checked').each(function() { bypass_tabs.push(this.value); });
		$.post('safety_ajax.php?action=setting_tabs', { subtabs: tabs, bypass: bypass_tabs });
	});
});
function saveFields() {
	$.ajax({
		url: 'safety_ajax.php?action=settings_config',
		method: 'POST',
		data: {
			name: this.name,
			value: this.value
		}
	});
}
</script>
<h3>Enable Safety Tabs</h3>
<div class="block-group">
	<label class="form-checkbox"><input type="checkbox" <?= in_array('Driving Log',$categories) ? ' checked' : '' ?> value="Driving Log" name="subtabs"> Driving Log</label>
	<label class="form-checkbox"><input type="checkbox" <?= in_array('FLHA',$categories) ? ' checked' : '' ?> value="FLHA" name="subtabs"> FLHA</label>
	<label class="form-checkbox"><input type="checkbox" <?= in_array('Toolbox',$categories) ? ' checked' : '' ?> value="Toolbox" name="subtabs"> Toolbox</label>
	<label class="form-checkbox"><input type="checkbox" <?= in_array('Tailgate',$categories) ? ' checked' : '' ?> value="Tailgate" name="subtabs"> Tailgate</label>
	<label class="form-checkbox"><input type="checkbox" <?= in_array('Form',$categories) ? ' checked' : '' ?> value="Form" name="subtabs"> Forms</label>
	<label class="form-checkbox"><input type="checkbox" <?= in_array('Manuals',$categories) ? ' checked' : '' ?> value="Manuals" name="subtabs"> Manuals</label>
	<label class="form-checkbox"><input type="checkbox" <?= in_array('Incident Reports',$categories) ? ' checked' : '' ?> value="Incident Reports" name="subtabs"> Incident Reports</label>
</div>
<h3>Bypass Safety Tabs</h3>
<p>The following tabs, if selected, will skip the list if there is only one form in the list.</p>
<div class="block-group">
	<label class="form-checkbox"><input type="checkbox" <?= in_array('FLHA',$bypass_cat) ? ' checked' : '' ?> value="FLHA" name="bypass_tabs"> Bypass FLHA List</label>
	<label class="form-checkbox"><input type="checkbox" <?= in_array('Toolbox',$bypass_cat) ? ' checked' : '' ?> value="Toolbox" name="bypass_tabs"> Bypass Toolbox List</label>
	<label class="form-checkbox"><input type="checkbox" <?= in_array('Tailgate',$bypass_cat) ? ' checked' : '' ?> value="Tailgate" name="bypass_tabs"> Bypass Tailgate List</label>
	<label class="form-checkbox"><input type="checkbox" <?= in_array('Forms',$bypass_cat) ? ' checked' : '' ?> value="Forms" name="bypass_tabs"> Bypass Forms List</label>
</div>

<div class="block-group">
	<h3>Email Settings</h3>
	<div class="form-group">
		<label class="col-sm-4 control-label">Submission Email Recipient:</label>
		<div class="col-sm-8">
			<input class="form-control" name="safety_manual_completed_email" value="<?= get_config($dbc, "safety_manual_completed_email") ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label">Submission Email Subject:</label>
		<div class="col-sm-8">
			<input class="form-control" name="safety_manual_subject_completed" value="<?= get_config($dbc, "safety_manual_subject_completed") ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label">Submission Email Body:<br /><em>You can use [COMMENT]</em></label>
		<div class="col-sm-8">
			<textarea name="safety_manual_body_completed"><?= html_entity_decode(get_config($dbc, "safety_manual_body_completed")) ?></textarea>
		</div>
	</div>
</div>