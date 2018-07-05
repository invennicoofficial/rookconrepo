<?php
include ('../database_connection.php');
date_default_timezone_set('America/Denver');

if($_GET['fill'] == 'staff') {
	$supportid = $_GET['supportid'];
    $contactid = $_GET['contactid'];

	$query_update_project = "UPDATE `support` SET  contactid='$contactid' WHERE `supportid` = '$supportid'";
    $result_update_project = mysqli_query($dbc, $query_update_project);
}
if($_GET['fill'] == 'status') {
        $date_of_archival = date('Y-m-d');

	$supportid = $_GET['supportid'];
    $status = $_GET['status'];
    if($status != 'Ticket' && $status != 'Task' && $status != 'Archived') {
	    $query_update_project = "UPDATE `support` SET  status='$status' WHERE `supportid` = '$supportid'";
	    $result_update_project = mysqli_query($dbc, $query_update_project);
    } else if($status == 'Archived') {
		$query_update_project = "UPDATE `support` set deleted=1, `date_of_archival` = '$date_of_archival' WHERE `supportid` = '$supportid'";
	    $result_update_project = mysqli_query($dbc, $query_update_project);
	}
}
?>