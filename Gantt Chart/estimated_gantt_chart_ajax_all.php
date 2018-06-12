<?php
include ('../database_connection.php');
date_default_timezone_set('America/Denver');

if($_GET['fill'] == 'ticketservice') {
	$service_type = $_GET['service_type'];
	$service_type = str_replace("__","&",$service_type);

	$query = mysqli_query($dbc,"SELECT distinct(category) FROM services WHERE REPLACE(`service_type`, ' ', '') = '$service_type'");
	echo '<option value="">Please Select</option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['category']."'>".$row['category'].'</option>';
	}
}

if($_GET['fill'] == 'ticketheading') {
	$service_type = $_GET['service_type'];
	$service_type = str_replace("__","&",$service_type);

	$service_category = $_GET['service_category'];
	$service_category = str_replace("__","&",$service_category);

	$query = mysqli_query($dbc,"SELECT serviceid, heading FROM services WHERE REPLACE(`service_type`, ' ', '') = '$service_type' AND REPLACE(`category`, ' ', '') = '$service_category'");
	echo '<option value="">Please Select</option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['heading']."'>".$row['heading'].'</option>';
	}
}

if($_GET['fill'] == 'gantt_startdate') {
	$estimatedganttchartid = $_GET['id'];
	$start_date = $_GET['start_date'];

    $query_update_workorder = "UPDATE `estimated_gantt_chart` SET `start_date` = '$start_date' WHERE `estimatedganttchartid` = '$estimatedganttchartid'";
    $result_update_workorder = mysqli_query($dbc, $query_update_workorder);
}

if($_GET['fill'] == 'gantt_enddate') {
	$estimatedganttchartid = $_GET['id'];
	$end_date = $_GET['end_date'];

    $query_update_workorder = "UPDATE `estimated_gantt_chart` SET `end_date` = '$end_date' WHERE `estimatedganttchartid` = '$estimatedganttchartid'";
    $result_update_workorder = mysqli_query($dbc, $query_update_workorder);
}
?>