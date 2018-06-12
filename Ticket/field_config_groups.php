<script>
$(document).ready(function() {
	$('input,select').off('change',saveGroups).change(saveGroups);
});
function saveGroups() {
	var groups = [];
	$('.block-group').each(function() {
		var group = [];
		$(this).find('[name="ticket_groups[]"]').each(function() {
			group.push(this.value);
		});
		groups.push(group.join(','));
	});
	$.ajax({
		url: 'ticket_ajax_all.php?action=setting_tile',
		method: 'POST',
		data: {
			field: 'ticket_groups',
			value: groups.join('#*#')
		}
	});
}
function addStaffGroup() {
	var clone = $('.block-group').last().clone();
	clone.find('.form-group select').each(function() { removeGroupStaff(this); });
	clone.find('input[name="ticket_groups[]"]').val('Group #'+($('#collapse_groups .block-group').length+1));
	$('.block-group').last().after(clone);

	$('[name=staffid]').last().focus();
	$('input,select').off('change',saveGroups).change(saveGroups);
}
function addGroupStaff(img) {
	var group = $(img).closest('.block-group');
	var clone = group.find('.form-group').last().clone();
	resetChosen(clone.find("select[class*=chosen]"));
	clone.find('option:selected').removeAttr('selected');
	group.append(clone);
	$('input,select').off('change',saveGroups).change(saveGroups);
}
function removeStaffGroup(img) {
	if($('.block-group').length <= 1) {
		addStaffGroup();
	}
	$(img).closest('.block-group').remove();
	saveGroups();
}
function removeGroupStaff(img) {
	if($(img).closest('.block-group').find('select').length <= 1) {
		addGroupStaff(img);
	}
	$(img).closest('.form-group').remove();
	saveGroups();
}
</script>
<?php $groups = explode('#*#',get_config($dbc,'ticket_groups'));
$staff_list = sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `last_name`, `first_name` FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `deleted`=0 AND `status`>0 AND `show_hide_user`=1"));
foreach($groups as $gid => $group) {
	$group = explode(',',$group);
	$group_name = 'Group #'.($gid+1);
	if(count($group) > 1 && !($group[0] > 0)) {
		$group_name = $group[0];
		unset($group[0]);
	} ?>
	<div class="block-group form-horizontal">
		<h4>Staff Group: <img src="../img/remove.png" class="inline-img" onclick="removeStaffGroup(this);"></h4>
		<input type="text" placeholder="Enter a name for the Group" name="ticket_groups[]" class="form-control" value="<?= $group_name ?>">
		<?php foreach($group as $staff) { ?>
			<div class="form-group">
				<label class="col-sm-3">Staff:</label>
				<div class="col-sm-8">
					<select name="ticket_groups[]" class="chosen-select-deselect">
						<option></option>
						<?php foreach($staff_list as $staff_option) { ?>
							<option <?= $staff == $staff_option['contactid'] ? 'selected' : '' ?> value="<?= $staff_option['contactid'] ?>"><?= $staff_option['first_name'].' '.$staff_option['last_name'] ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-sm-1">
					<img src="../img/icons/ROOK-add-icon.png" class="inline-img pull-right" onclick="addGroupStaff(this);">
					<img src="../img/remove.png" class="inline-img pull-right" onclick="removeGroupStaff(this);">
				</div>
			</div>
		<?php } ?>
	</div>
<?php } ?>
<button class="btn brand-btn pull-right" onclick="addStaffGroup(); return false;">Add Staff Collaboration Group</button>