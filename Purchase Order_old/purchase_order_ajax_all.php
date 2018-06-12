<?php
include ('../database_connection.php');
date_default_timezone_set('America/Denver');

if($_GET['fill'] == 'projectname') {
	$businessid = $_GET['businessid'];

	$query = mysqli_query($dbc,"SELECT projectid, project_name FROM project WHERE clientid = '$businessid' OR businessid='$businessid'");
	echo '<option value="">Please Select</option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['projectid']."'>".$row['project_name'].'</option>';
	}

    echo '**##**';

	$query = mysqli_query($dbc,"SELECT ticketid, service_type, heading FROM tickets WHERE status!='Archived' AND businessid='$businessid'");
	echo '<option value="">Please Select</option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['ticketid']."'>".$row['service_type'].' : '.$row['heading'].'</option>';
	}
}
?>