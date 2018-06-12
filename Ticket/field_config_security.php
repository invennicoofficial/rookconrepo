<script>
$(document).ready(function() {
	toggleSwitch();
	$('input').change(saveRoles);
});
function saveRoles() {
	var ticket_roles = [];
	$('[name="role[]"]').each(function() {
		var role = $(this).closest('.role-group');
		ticket_roles.push(this.value + '|' +
			(role.find('[name=default_security]').is(':checked') ? 'default' : '') + '|' +
			role.find('[name="role_project[]"]').val() + '|' +
			role.find('[name="role_staff[]"]').val() + '|' +
			role.find('[name="role_contacts[]"]').val() + '|' +
			role.find('[name="role_wait[]"]').val() + '|' +
			role.find('[name="role_staff_checkin[]"]').val() + '|' +
			role.find('[name="role_checkin[]"]').val() + '|' +
			role.find('[name="role_meds[]"]').val() + '|' +
			role.find('[name="role_complete[]"]').val() + '|' +
			role.find('[name="role_services[]"]').val() + '|' +
			role.find('[name="role_ticket[]"]').val());
	});
	$.ajax({
		url: 'ticket_ajax_all.php?action=setting_tile',
		method: 'POST',
		data: {
			field: 'ticket_roles',
			value: ticket_roles.join('#*#')
		}
	});
}
function addRole() {
	destroyInputs('.role-group');
	var role = $('.role-group').last();
	var clone = role.clone();
	clone.find('input').val('');
	clone.find('.toggleSwitch').each(function() {
		$(this).find('span').last().hide();
		$(this).find('span').first().show();
	});
	role.after(clone);
	initInputs('.role-group');
	toggleSwitch();
	$('input').change(saveRoles);
}
function remRole(img) {
	if($('[name="role[]"]').length == 1) {
		addRole();
	}
	$(img).closest('.role-group').remove();
}
function toggleSwitch() {
	$('.toggleSwitch').off('click').click(function() {
		$(this).find('span').toggle();
		var value = $(this).find('.toggle').data('toggle-value');
		$(this).find('.toggle').val($(this).find('.toggle').val() == value ? '' : value).change();
	});
}
</script>
<div class="form-group">
	<div class="hide-titles-mob">
		<label class="col-sm-2">Role</label>
		<div class="action-icons">
			<label class="col-sm-1" style="word-break:break-word;"style="word-break:break-word;">Default Role</label>
			<label class="col-sm-1" style="word-break:break-word;"><?= PROJECT_NOUN ?> Information</label>
			<label class="col-sm-1" style="word-break:break-word;">Staff Information</label>
			<label class="col-sm-1" style="word-break:break-word;">Clients / Members</label>
			<label class="col-sm-1" style="word-break:break-word;">Wait List</label>
			<label class="col-sm-1" style="word-break:break-word;">Staff Check In / Out</label>
			<label class="col-sm-1" style="word-break:break-word;">Other Check In / Out</label>
			<label class="col-sm-1" style="word-break:break-word;">Medication</label>
			<label class="col-sm-1" style="word-break:break-word;">Complete</label>
			<label class="col-sm-1" style="word-break:break-word;">Services</label>
			<label class="col-sm-1" style="word-break:break-word;">All Other</label>
			<label class="col-sm-1"></label>
		</div>
	</div>
</div>
<?php $position_list = [];
$positions = $dbc->query("SELECT `position_id`,`name` FROM `positions` WHERE `deleted`=0");
while($position = $positions->fetch_assoc()) {
	$position_list[$position['position_id']] = $position['name'];
}
foreach(explode('#*#',get_config($dbc,'ticket_roles')) as $role_security) {
	$role_security = explode('|',$role_security); ?>
	<div class="form-group role-group">
		<div class="col-sm-2"><label class="show-on-mob">Role:</label>
			<select name="role[]" class="chosen-select-deselect" onchange="$(this).closest('.role-group').find('[name=default_security]').val(this.value);"><option />
				<?php foreach($position_list as $posid => $position) { ?>
					<option <?= $role_security[0] == $position || $role_security[0] == $posid ? 'selected' : '' ?> value="<?= $posid ?>"><?= $position ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="action-icons">
			<div class="col-sm-1"><label class="show-on-mob">Default Security Level:</label>
				<label class="form-checkbox"><input type="radio" name="default_security" <?= in_array('default',$role_security) ? 'checked' : '' ?> value="<?= $role_security[0] ?>"> Default</label>
			</div>
			<div class="col-sm-1"><label class="show-on-mob"><?= PROJECT_NOUN ?> Information:</label>
				<div class="toggleSwitch">
					<input type="hidden" name="role_project[]" value="<?= in_array('project',$role_security) ? 'project' : '' ?>" class="toggle" data-toggle-value="project">
					<span style="<?= in_array('project',$role_security) ? 'display: none;' : '' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-6.png" class="text-lg no-margin inline-img"></span>
					<span style="<?= in_array('project',$role_security) ? '' : 'display: none;' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-7.png" class="text-lg no-margin inline-img"></span>
				</div>
			</div>
			<div class="col-sm-1"><label class="show-on-mob">Staff Information:</label>
				<div class="toggleSwitch">
					<input type="hidden" name="role_staff[]" value="<?= in_array('staff_list',$role_security) ? 'staff_list' : '' ?>" class="toggle" data-toggle-value="staff_list">
					<span style="<?= in_array('staff_list',$role_security) ? 'display: none;' : '' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-6.png" class="text-lg no-margin inline-img"></span>
					<span style="<?= in_array('staff_list',$role_security) ? '' : 'display: none;' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-7.png" class="text-lg no-margin inline-img"></span>
				</div>
			</div>
			<div class="col-sm-1"><label class="show-on-mob">Clients / Members Information:</label>
				<div class="toggleSwitch">
					<input type="hidden" name="role_contacts[]" value="<?= in_array('contact_list',$role_security) ? 'contact_list' : '' ?>" class="toggle" data-toggle-value="contact_list">
					<span style="<?= in_array('contact_list',$role_security) ? 'display: none;' : '' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-6.png" class="text-lg no-margin inline-img"></span>
					<span style="<?= in_array('contact_list',$role_security) ? '' : 'display: none;' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-7.png" class="text-lg no-margin inline-img"></span>
				</div>
			</div>
			<div class="col-sm-1"><label class="show-on-mob">Wait List:</label>
				<div class="toggleSwitch">
					<input type="hidden" name="role_wait[]" value="<?= in_array('wait_list',$role_security) ? 'wait_list' : '' ?>" class="toggle" data-toggle-value="wait_list">
					<span style="<?= in_array('wait_list',$role_security) ? 'display: none;' : '' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-6.png" class="text-lg no-margin inline-img"></span>
					<span style="<?= in_array('wait_list',$role_security) ? '' : 'display: none;' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-7.png" class="text-lg no-margin inline-img"></span>
				</div>
			</div>
			<div class="col-sm-1"><label class="show-on-mob">Staff Check In / Check Out / Staff Summary:</label>
				<div class="toggleSwitch">
					<input type="hidden" name="role_staff_checkin[]" value="<?= in_array('staff_checkin',$role_security) ? 'staff_checkin' : '' ?>" class="toggle" data-toggle-value="staff_checkin">
					<span style="<?= in_array('staff_checkin',$role_security) ? 'display: none;' : '' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-6.png" class="text-lg no-margin inline-img"></span>
					<span style="<?= in_array('staff_checkin',$role_security) ? '' : 'display: none;' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-7.png" class="text-lg no-margin inline-img"></span>
				</div>
			</div>
			<div class="col-sm-1"><label class="show-on-mob">Other Check In / Check Out:</label>
				<div class="toggleSwitch">
					<input type="hidden" name="role_checkin[]" value="<?= in_array('all_checkin',$role_security) ? 'all_checkin' : '' ?>" class="toggle" data-toggle-value="all_checkin">
					<span style="<?= in_array('all_checkin',$role_security) ? 'display: none;' : '' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-6.png" class="text-lg no-margin inline-img"></span>
					<span style="<?= in_array('all_checkin',$role_security) ? '' : 'display: none;' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-7.png" class="text-lg no-margin inline-img"></span>
				</div>
			</div>
			<div class="col-sm-1"><label class="show-on-mob">Medication Administration:</label>
				<div class="toggleSwitch">
					<input type="hidden" name="role_meds[]" value="<?= in_array('medication',$role_security) ? 'medication' : '' ?>" class="toggle" data-toggle-value="medication">
					<span style="<?= in_array('medication',$role_security) ? 'display: none;' : '' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-6.png" class="text-lg no-margin inline-img"></span>
					<span style="<?= in_array('medication',$role_security) ? '' : 'display: none;' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-7.png" class="text-lg no-margin inline-img"></span>
				</div>
			</div>
			<div class="col-sm-1"><label class="show-on-mob">Complete <?= TICKET_NOUN ?>:</label>
				<div class="toggleSwitch">
					<input type="hidden" name="role_complete[]" value="<?= in_array('complete',$role_security) ? 'complete' : '' ?>" class="toggle" data-toggle-value="complete">
					<span style="<?= in_array('complete',$role_security) ? 'display: none;' : '' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-6.png" class="text-lg no-margin inline-img"></span>
					<span style="<?= in_array('complete',$role_security) ? '' : 'display: none;' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-7.png" class="text-lg no-margin inline-img"></span>
				</div>
			</div>
			<div class="col-sm-1"><label class="show-on-mob">Services:</label>
				<div class="toggleSwitch">
					<input type="hidden" name="role_services[]" value="<?= in_array('services',$role_security) ? 'services' : '' ?>" class="toggle" data-toggle-value="services">
					<span style="<?= in_array('services',$role_security) ? 'display: none;' : '' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-6.png" class="text-lg no-margin inline-img"></span>
					<span style="<?= in_array('services',$role_security) ? '' : 'display: none;' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-7.png" class="text-lg no-margin inline-img"></span>
				</div>
			</div>
			<div class="col-sm-1"><label class="show-on-mob">All Other Accordions:</label>
				<div class="toggleSwitch">
					<input type="hidden" name="role_ticket[]" value="<?= in_array('all_access',$role_security) ? 'all_access' : '' ?>" class="toggle" data-toggle-value="all_access">
					<span style="<?= in_array('all_access',$role_security) ? 'display: none;' : '' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-6.png" class="text-lg no-margin inline-img"></span>
					<span style="<?= in_array('all_access',$role_security) ? '' : 'display: none;' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-7.png" class="text-lg no-margin inline-img"></span>
				</div>
			</div>
			<div class="col-sm-1">
				<img class="inline-img" src="../img/remove.png" onclick="remRole(this);">
				<img class="inline-img" src="../img/icons/ROOK-add-icon.png" onclick="addRole();">
			</div>
		</div>
	</div>
<?php } ?>