<?php error_reporting(0);
include_once('../include.php');
if(!isset($security)) {
	$security = get_security($dbc, $tile);
	$strict_view = strictview_visible_function($dbc, 'project');
	if($strict_view > 0) {
		$security['edit'] = 0;
		$security['config'] = 0;
	}
}
if(!isset($projectid)) {
	$projectid = filter_var($_GET['projectid'],FILTER_SANITIZE_STRING);
	foreach(explode(',',get_config($dbc, "project_tabs")) as $type_name) {
		if($tile == 'project' || $tile == config_safe_str($type_name)) {
			$project_tabs[config_safe_str($type_name)] = $type_name;
		}
	}
}
$project = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `project` WHERE `projectid`='$projectid'")); ?>
<h1><?= get_project_label($dbc, $project) ?> History</h1>
<?php $history = mysqli_query($dbc, "SELECT * FROM `project_history` WHERE `projectid`='$projectid' ORDER BY `updated_at` ASC");
if($history->num_rows > 0) {
	echo '<div id="no-more-tables"><table class="table table-bordered">
		<tr class="hidden-sm hidden-xs">
			<th>User</th>
			<th>Date of Change</th>
			<th>History</th>
		</tr>';
	while($row = $history->fetch_assoc()) {
		echo '<tr>
			<td data-title="User">'.$row['updated_by'].'</td>
			<td data-title="Date">'.date('F j, Y H:i',strtotime($row['updated_at'])).'</td>
			<td data-title="History">'.$row['description'].'</td>
		</tr>';
	}
	echo '</table></div>';
} else {
	echo "<h3>No History Found.</h3>";
} ?>
<?php include('next_buttons.php'); ?>