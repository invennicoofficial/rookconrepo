<?php
include ('../database_connection.php');
include ('../function.php');
include ('../global.php');

if($_GET['fill'] == 'checklist') {
    $checklistid = $_GET['checklistid'];
    $checked = $_GET['checked'];
    $updated_by = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);
    $updated_date = date('Y-m-d');

	$query_update_project = "UPDATE `checklist_name` SET  `checked`='$checked', `updated_date`='$updated_date', `updated_by`='$updated_by'  WHERE `checklistnameid` = '$checklistid'";
	$result_update_project = mysqli_query($dbc, $query_update_project);
}

if($_GET['fill'] == 'checklist_priority') {
    $checklistnameid = $_GET['checklistnameid'];
    $after_checklistnameid = $_GET['after_checklistnameid'];
    $checklistnameid_pri = get_checklist_name($dbc, $after_checklistnameid, 'priority')+1;
    $checklistid = get_checklist_name($dbc, $checklistnameid, 'checklistid');

	$query_update_project = "UPDATE `checklist_name` SET  `priority`=`priority`+1 WHERE `priority` >= '$checklistnameid_pri' AND `checklistid` = '$checklistid'";
	$result_update_project = mysqli_query($dbc, $query_update_project);

	$query_update_project = "UPDATE `checklist_name` SET  `priority`='$checklistnameid_pri' WHERE `checklistnameid` = '$checklistnameid'";
	$result_update_project = mysqli_query($dbc, $query_update_project);

}

if($_GET['fill'] == 'add_checklist') {

    $checklistid = $_GET['checklistid'];
    $checklist = $_GET['checklist'];

    $contactid = $_SESSION['contactid'];

	$checklist = str_replace("FFMEND","&",$checklist);
    $checklist = str_replace("FFMSPACE"," ",$checklist);
    $checklist = str_replace("FFMHASH","#",$checklist);

    $checklist = filter_var($checklist,FILTER_SANITIZE_STRING);
    $checklist_name = get_checklist($dbc, $checklistid, 'checklist_name');

    $get_staff =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT MAX(priority) AS total_checklistnameid FROM	checklist_name WHERE checklistid='$checklistid'"));
    $max_checklist = $get_staff['total_checklistnameid']+1;

    if($checklist != '') {
        $query_insert_log = "INSERT INTO `checklist_name` (`checklistid`, `checklist`, `priority`) VALUES ('$checklistid', '$checklist', '$max_checklist')";
        $result_insert_log = mysqli_query($dbc, $query_insert_log);

        $report = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']).' Added Checklist Item in <b>'.$checklist_name.'</b> on '.date('Y-m-d');
        $query_insert_ca = "INSERT INTO `checklist_report` (`report`) VALUES ('$report')";
        $result_insert_ca = mysqli_query($dbc, $query_insert_ca);

    }
}

?>