<?php include('../include.php');
checkAuthorised('estimate');
$estimateid = filter_var($_GET['estimateid'],FILTER_SANITIZE_STRING);
$estimate = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `add_to_project` FROM `estimate` WHERE `estimateid`='$estimateid'")); ?>
<div class="container">
	<h3>Select a <?= PROJECT_NOUN ?></h3>
	<select class="chosen-select-deselect" data-placeholder="Select a <?= PROJECT_NOUN ?>" name="projectid">
		<option value="0">Create a new <?= PROJECT_NOUN ?></option>
		<?php $projects = mysqli_query($dbc, "SELECT `projectid`, `project_name` FROM `project` WHERE `deleted`=0");
		while($project = mysqli_fetch_assoc($projects)) { ?>
			<option <?= $estimate['add_to_project'] == $project['projectid'] ? 'selected' : '' ?> value="<?= $project['projectid'] ?>"><?= PROJECT_NOUN.' #'.$project['projectid'].' '.$project['project_name'] ?></option>
		<?php } ?>
	</select>
	<button class="btn brand-btn pull-right" onclick="window.location.href='convert_to_project.php?estimateid=<?= $estimateid ?>&projectid='+$('[name=projectid]').val();">Confirm</button>
	<button class="btn brand-btn pull-right" onclick="window.location.reload();">Cancel</button>
</div>