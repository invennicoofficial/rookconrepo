<?php error_reporting(0);
include_once('../include.php');
checkAuthorised('estimate');
$estimate_status = explode('#*#',get_config($dbc, 'estimate_status'));
$summarized = explode('#*#',get_config($dbc, 'estimate_summarize'));
$project_status = get_config($dbc, 'estimate_project_status'); ?>
<script>
$(document).ready(function() {
	$('input').change(saveStatus);
	$('.main-screen').sortable({
		handle: '.drag-handle',
		items: '.status-option',
		update: saveStatus
	});
});
function saveStatus() {
	var status_list = [];
	var summarized = [];
	var project_status = '';
	$('[name="estimate_status[]"]').each(function() {
		status_list.push(this.value);
		if($(this).closest('.form-group').find('input[type=checkbox]:visible').is(':checked')) {
			summarized.push(this.value);
		}
		if($(this).closest('.form-group').find('input[type=radio]:visible').is(':checked')) {
			project_status = this.value;
		}
	});
	$.ajax({
		url: 'estimates_ajax.php?action=setting_status',
		method: 'POST',
		data: {
			status: status_list,
			summary: summarized,
			projects: project_status
		}
	});
}
function addStatus() {
	var clone = $('.status-option').last().clone();
	clone.find('input').val('').removeAttr('checked');
	$('.status-option').last().after(clone);

	$('input').off('change', saveStatus).change(saveStatus);
	$('[name="estimate_status[]"]').last().focus();
}
function removeStatus(a) {
	if($('.status-option').length <= 1) {
		addStatus();
	}
	$(a).closest('.status-option').remove();
	saveStatus();
}
</script>
<h3><?= ESTIMATE_TILE ?> Status</h3>
<label class="col-sm-8 text-center hide-titles-mob">Status Name</label>
<label class="col-sm-1 hide-titles-mob">Use for <?= PROJECT_TILE ?></label>
<label class="col-sm-3 hide-titles-mob">Summarize on Dashboard</label>
<?php foreach($estimate_status as $status) { ?>
	<div class="form-group status-option">
		<div class="col-sm-8">
			<label class="show-on-mob">Status Name:</label>
			<input type="text" name="estimate_status[]" class="form-control" value="<?= $status ?>">
		</div>
		<div class="col-sm-1">
			<label class="form-checkbox hide-titles-mob"><input type="radio" <?= $status == $project_status ? 'checked="checked"' : '' ?> name="project_status_normal"></label>
			<label class="form-checkbox show-on-mob"><input type="radio" <?= $status == $project_status ? 'checked="checked"' : '' ?> name="project_status_mobile"> Once <?= PROJECT_TILE ?> created, use this status</label>
		</div>
		<div class="col-sm-3">
			<label><input type="checkbox" <?= in_array($status, $summarized) ? 'checked' : '' ?> name="summarize[]" class="hide-titles-mob"></label>
			<label class="form-checkbox show-on-mob"><input type="checkbox" <?= in_array($status, $summarized) ? 'checked' : '' ?> name="summarize[]"> Summarize on Dashboard</label>
			<img src="../img/icons/drag_handle.png" style="height: 1.5em; margin: 0 0.25em;" class="pull-right drag-handle">
			<img src="../img/icons/ROOK-add-icon.png" style="height: 1.5em; margin: 0 0.25em;" class="pull-right" onclick="addStatus();">
			<img src="../img/remove.png" style="height: 1.5em; margin: 0 0.25em;" class="pull-right" onclick="removeStatus(this);">
		</div>
		<div class="clearfix"></div>
	</div>
<?php } ?>
<button onclick="addStatus(); return false;" class="btn brand-btn pull-right">Add Status</button>
<?php if(basename($_SERVER['SCRIPT_FILENAME']) == 'field_config_status.php') { ?>
	<div style="display:none;"><?php include('../footer.php'); ?></div>
<?php } ?>