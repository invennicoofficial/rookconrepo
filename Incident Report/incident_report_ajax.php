<?php include_once('../include.php');
ob_clean();

if($_GET['action'] == 'admin_status') {
	$id = filter_var($_POST['id'],FILTER_SANITIZE_STRING);
	$status = filter_var($_POST['status'],FILTER_SANITIZE_STRING);
	$user = $_SESSION['contactid'];
	$dbc->query("UPDATE `incident_report` SET `status`='$status', `approved_by`='$user' WHERE `incidentreportid`='$id'");
}