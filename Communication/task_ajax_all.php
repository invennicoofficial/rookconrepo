<?php
include ('../include.php');

if($_GET['fill'] == 'tasklist') {
    $taskid = $_GET['taskid'];
    $task_status = $_GET['status'];
	$task_status = str_replace("FFMEND","&",$task_status);
    $task_status = str_replace("FFMSPACE"," ",$task_status);
    $task_status = str_replace("FFMHASH","#",$task_status);

	$query_update_project = "UPDATE `tasklist` SET  status='$task_status' WHERE `tasklistid` = '$taskid'";
	$result_update_project = mysqli_query($dbc, $query_update_project);
}

if($_GET['fill'] == 'add_task') {

    $status = $_GET['status'];
    $category = $_GET['category'];
    $heading = $_GET['task_value'];
    $contactid = $_SESSION['contactid'];

    $tast_data = explode(' ',$_GET['tast_data']);

    //$category = $tast_data[2];
    //$status = str_replace('**FFM**', ' ', $tast_data[1]);

	$task = $_GET['tast_value'];
	$status = str_replace("FFMEND","&",$status);
    $status = str_replace("FFMSPACE"," ",$status);
    $status = str_replace("FFMHASH","#",$status);

	$heading = str_replace("FFMEND","&",$heading);
    $heading = str_replace("FFMSPACE"," ",$heading);
    $heading = str_replace("FFMHASH","#",$heading);

    $heading = filter_var($heading,FILTER_SANITIZE_STRING);

    if($heading != '') {
        $query_insert_log = "INSERT INTO `tasklist` (`heading`, `category`, `status`, `contactid`) VALUES ('$heading', '$category', '$status', '$contactid')";
        $result_insert_log = mysqli_query($dbc, $query_insert_log);
    }
}
if($_GET['fill'] == 'ticket') {
    $ticketid = $_GET['ticketid'];
    $task_status = $_GET['status'];
	$task_status = str_replace("FFMEND","&",$task_status);
    $task_status = str_replace("FFMSPACE"," ",$task_status);
    $task_status = str_replace("FFMHASH","#",$task_status);

	$query_update_project = "UPDATE `tickets` SET  status='$task_status' WHERE `ticketid` = '$ticketid'";
	$result_update_project = mysqli_query($dbc, $query_update_project);
}

if($_GET['fill'] == 'trellotable') {
    $contactid = $_GET['contactid'];
	$value = $_GET['value'];
	if($value !== '1') {
		$value = NULL;
	}
    $query_update_project = "UPDATE `contacts` SET horizontal_communication='$value' WHERE `contactid` = '$contactid'";
	$result_update_project = mysqli_query($dbc, $query_update_project);
	

}
?>