<?php include_once('../include.php');
checkAuthorised('estimate');
if(!isset($estimate)) {
	$estimateid = filter_var($estimateid,FILTER_SANITIZE_STRING);
	$estimate = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `estimate` WHERE `estimateid`='$estimateid'"));
}
$staff_list = sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `last_name`, `first_name` FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `deleted`=0 AND `status`>0 AND `show_hide_user`=1")); ?>
<script>
function addStaff() {
	var row = $('[name="assign_staffid[]"]').last().closest('.form-group');
	var clone = row.clone();
	resetChosen(clone.find("select[class*=chosen]"));
	row.after(clone);
	$('input,select').off('change', saveField).change(saveField);
}
function removeStaff(img) {
	if($('[name="assign_staffid[]"]').length <= 1) {
		addStaff();
	}
	$(img).closest('.form-group').remove();
	$('[name="assign_staffid[]"]').last().change();
}
function assignGroup(group_list) {
	group_list = group_list.split(',');
	$(group_list).each(function() {
		staff_id = this;
		if($('[name="assign_staffid[]"]').filter(function() { return $(this).val() == staff_id.valueOf(); }).length == 0) {
			addStaff();
			$('[name="assign_staffid[]"]').last().val(staff_id.valueOf()).trigger('change.select2');
		}
	});
	$('[name="assign_staffid[]"]').last().change();
}
</script>
<div class="form-horizontal col-sm-12" data-tab-name="staff">
	<h3>Staff Collaboration</h3>
	<?php $staff_assigned = array_filter(explode(',',$estimate['assign_staffid']));
	if(count($staff_assigned) == 0) {
		$staff_assigned[] = 0;
	}
	foreach($staff_assigned as $staff) { ?>
		<div class="form-group">
			<label class="col-sm-3">Staff:</label>
			<div class="col-sm-8">
				<select name="assign_staffid[]" class="chosen-select-deselect" data-table="estimate" data-id-field="estimateid" data-id="<?= $estimateid ?>">
					<option></option><?php
					foreach($staff_list as $staff_id) { ?>
						<option <?= $staff_id['contactid'] == $staff ? 'selected' : '' ?> value="<?= $staff_id['contactid'] ?>"><?= $staff_id['first_name'].' '.$staff_id['last_name'] ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-sm-1">
				<img src="../img/icons/ROOK-add-icon.png" class="inline-img pull-right" onclick="addStaff(this);">
				<img src="../img/remove.png" class="inline-img pull-right" onclick="removeStaff(this);">
			</div>
		</div>
	<?php } ?>
	<?php $groups = explode('#*#',mysqli_fetch_array(mysqli_query($dbc, "SELECT `estimate_groups` FROM field_config_estimate"))[0]);
	foreach($groups as $id => $group) { ?>
		<div class="form-group">
			<?php $group_list = [];
			$group_name = '';
			foreach(explode(',',$group) as $staff_id) {
				if($staff_id > 0) {
					$group_list[] = get_contact($dbc, $staff_id);
				} else if($staff_id !== 0) {
					$group_name = $staff_id;
				}
			} ?>
			<button class="btn brand-btn pull-right" data-group="<?= $group ?>" onclick="assignGroup($(this).data('group'));">Assign <?= ($group_name == '' ? 'Group #'.($id + 1) : $group_name) ?>: 
			<?= implode(', ',$group_list); ?></button>
		</div>
	<?php } ?>
    <hr />
</div>
<?php if(basename($_SERVER['SCRIPT_FILENAME']) == 'edit_staff.php') { ?>
	<div style="display:none;"><?php include('../footer.php'); ?></div>
<?php } ?>