<?php
include ('../include.php');
ob_clean();
//include ('../function.php');

if($_GET['fill'] == 'activity_calendar') {
    $intercalendarid = $_GET['intercalendarid'];
    $activity_date = $_GET['activity_date'];

	$query_update_project = "UPDATE `interactive_calendar` SET  activity_date='$activity_date' WHERE `intercalendarid` = '$intercalendarid'";
	$result_update_project = mysqli_query($dbc, $query_update_project);
}
?>