<?php
include ('../database_connection.php');
date_default_timezone_set('America/Denver');


if($_GET['fill'] == 'assigncontact') {
	$businessid = $_GET['businessid'];

	$query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE businessid = '$businessid'");
	echo '<option value="">Please Select</option>';
    echo "<option value = 'New Contact'>New Contact</option>";
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['contactid']."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';
	}
}

if($_GET['fill'] == 'sales_status') {
	$calllogid = $_GET['salesid'];
	$status = $_GET['status'];
	$query_update_project = "UPDATE `calllog_pipeline` SET  status='$status' WHERE `calllogid` = '$calllogid'";
	$result_update_project = mysqli_query($dbc, $query_update_project);
}

if($_GET['fill'] == 'sales_action') {
	$calllogid = $_GET['salesid'];
	$action = $_GET['action'];
	$query_update_project = "UPDATE `calllog_pipeline` SET  next_action='$action' WHERE `calllogid` = '$calllogid'";
	$result_update_project = mysqli_query($dbc, $query_update_project);
}
if($_GET['fill'] == 'sales_reminder') {
	$calllogid = $_GET['salesid'];
	$reminder = $_GET['reminder'];
	$query_update_project = "UPDATE `calllog_pipeline` SET  new_reminder='$reminder' WHERE `calllogid` = '$calllogid'";
	$result_update_project = mysqli_query($dbc, $query_update_project);
	$call_log = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `calllog_pipeline` WHERE `calllogid`='$calllogid'"));
	$recip_name = explode(' ',$call_log['created_by']);
	$recip_first = encryptIt($recip_name[0]);
	$recip_last = encryptIt($recip_name[1]);
	$recipient = decryptIt(mysqli_fetch_array(mysqli_query($dbc, "SELECT `email_address` FROM `contacts` WHERE `first_name`='$recip_first' AND `last_name`='$recip_last'"))['email_address']);
	
	if($reminder != '' && $reminder != '0000-00-00') {
		$body = filter_var(htmlentities('This is a reminder about a call log that needs to be followed up with.<br />
			The scheduled next action is: '.$call_log['next_action'].'<br />
			Click <a href="'.WEBSITE_URL.'/Cold Call/add_call_log.php?calllogid='.$calllogid.'">here</a> to review the call log.'), FILTER_SANITIZE_STRING);
		$verify = "calllog_pipeline#*#next_action#*#calllogid#*#".$calllogid."#*#".$call_log['next_action'];
        mysqli_query($dbc, "UPDATE `reminders` SET `done` = 1 WHERE `src_table` = 'calllog' AND `src_tableid` = '$calllogid'");
		$reminder_result = mysqli_query($dbc, "INSERT INTO `reminders` (`contactid`, `reminder_date`, `reminder_type`, `subject`, `body`, `src_table`, `src_tableid`)
			VALUES ('$recipient', '$new_reminder', 'Call Log Reminder', 'Reminder of Call Log', '$body', 'calllog', '$calllogid')");
	}
}
?>