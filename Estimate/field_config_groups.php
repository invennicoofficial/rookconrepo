<?php include_once('../include.php');
checkAuthorised('estimate');
error_reporting(0); ?>
<script>
$(document).ready(function() {
	$('select,input').change(saveGroups);
});
function saveGroups() {
	var group_list = [];
	var i = 0;
	$('.block-group').each(function() {
		group_list[i] = [];
		var group_name = $(this).find('[name=group_name]').val();
		if(group_name != '') {
			group_list[i].push(group_name);
		}
		$(this).find('select').each(function() {
			if(this.value > 0) {
				group_list[i].push(this.value);
			}
		});
		i++;
	});
	$.ajax({
		url: 'estimates_ajax.php?action=setting_groups',
		method: 'POST',
		data: {
			groups: group_list
		}
	});
}
function addGroup() {
	var clone = $('.block-group').last().clone();
	clone.find('.form-group select').each(function() { removeStaff(this); });
	clone.find('input').val('');
	$('.block-group').last().after(clone);
	
	$('select').off('change', saveGroups).change(saveGroups);
	$('[name=staffid]').last().focus();
}
function addStaff(img) {
	var group = $(img).closest('.block-group');
	var clone = group.find('.form-group').last().clone();
	resetChosen(clone.find("select[class*=chosen]"));
	group.append(clone);
	$('select').off('change', saveGroups).change(saveGroups);
}
function removeGroup(img) {
	if($('.block-group').length <= 1) {
		addGroup();
	}
	$(img).closest('.block-group').remove();
	saveGroups();
}
function removeStaff(img) {
	if($(img).closest('.block-group').find('select').length <= 1) {
		addStaff(img);
	}
	$(img).closest('.form-group').remove();
	saveGroups();
}
</script>
<?php $groups = explode('#*#',mysqli_fetch_array(mysqli_query($dbc, "SELECT `estimate_groups` FROM field_config_estimate"))[0]);
$staff_list = sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `last_name`, `first_name` FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `deleted`=0 AND `status`>0 AND `show_hide_user`=1"));
foreach($groups as $gid => $group) {
	$group = explode(',',$group);
	$group_name = 'Group #'.($gid+1);
	if(count($group) > 1 && !($group[0] > 0)) {
		$group_name = $group[0];
		unset($group[0]);
	}
	/*foreach($group as $i => $staff) {
		if(!($staff > 0) && $staff !== 0) {
			$group_name = $staff;
			unset($group[$i]);
		}
	}*/ ?>
	<div class="block-group form-horizontal">
		<h4>Staff Collaboration Group: <img src="../img/remove.png" class="inline-img" onclick="removeGroup(this);"></h4>
		<input type="text" placeholder="Enter a name for the Group" name="group_name" class="form-control" value="<?= $group_name ?>">
		<?php foreach($group as $staff) { ?>
			<div class="form-group">
				<label class="col-sm-3">Staff:</label>
				<div class="col-sm-8">
					<select name="staffid" class="chosen-select-deselect">
						<option></option>
						<?php foreach($staff_list as $staff_option) { ?>
							<option <?= $staff == $staff_option['contactid'] ? 'selected' : '' ?> value="<?= $staff_option['contactid'] ?>"><?= $staff_option['first_name'].' '.$staff_option['last_name'] ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-sm-1">
					<img src="../img/icons/ROOK-add-icon.png" class="inline-img pull-right" onclick="addStaff(this);">
					<img src="../img/remove.png" class="inline-img pull-right" onclick="removeStaff(this);">
				</div>
			</div>
		<?php } ?>
	</div>
<?php } ?>
<button class="btn brand-btn pull-right" onclick="addGroup(); return false;">Add Staff Collaboration Group</button>
<?php if(basename($_SERVER['SCRIPT_FILENAME']) == 'field_config_groups.php') { ?>
	<div style="display:none;"><?php include('../footer.php'); ?></div>
<?php } ?>