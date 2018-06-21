<?php
include ('../include.php');
error_reporting(0);
ob_clean();

if($_GET['fill'] == 'update_status') {
	$ticketnotificationid = $_POST['ticketnotificationid'];
	$status = $_POST['status'];
	mysqli_query($dbc, "UPDATE `ticket_notifications` SET `status` = '$status' WHERE `ticketnotificationid` = '$ticketnotificationid'");
	echo "UPDATE `ticket_notifications` SET `status` = '$status' WHERE `ticketnotificationid` = '$ticketnotificationid'";
} else if($_GET['fill'] == 'update_send_date') {
	$ticketnotificationid = $_POST['ticketnotificationid'];
	$send_date = $_POST['send_date'];
	mysqli_query($dbc, "UPDATE `ticket_notifications` SET `status` = 'Pending', `send_date` = '$send_date' WHERE `ticketnotificationid` = '$ticketnotificationid'");
} else if($_GET['fill'] == 'update_followup_date') {
	$ticketnotificationid = $_POST['ticketnotificationid'];
	$follow_up_date = $_POST['follow_up_date'];
	mysqli_query($dbc, "UPDATE `ticket_notifications` SET `follow_up_date` = '$follow_up_date' WHERE `ticketnotificationid` = '$ticketnotificationid'");
} else if($_GET['fill'] == 'delete_notification') {
	$ticketnotificationid = $_POST['ticketnotificationid'];
        $date_of_archival = date('Y-m-d');
	mysqli_query($dbc, "UPDATE `ticket_notifications` SET `deleted` = 1, `date_of_archival` = '$date_of_archival' WHERE `ticketnotificationid` = '$ticketnotificationid'");
} else if($_GET['fill'] == 'send_notification') {
	$ticketnotificationid = $_POST['ticketnotificationid'];
	$noti = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `ticket_notifications` WHERE `ticketnotificationid` = '$ticketnotificationid'"));
	$staffid = $noti['staffid'];
	$contactid = $noti['contactid'];
	$sender_name = $noti['sender_name'];
	$sender_email = $noti['sender_email'];
	$subject = $noti['subject'];
	$body = html_entity_decode($noti['email_body']);
	$log = '';

	$recipients = array_merge(explode(',', $staffid), explode(',', $contactid));
	foreach ($recipients as $address) {
		$address = get_email($dbc, filter_var($address,FILTER_SANITIZE_STRING));
		try {
			send_email([$sender_email=>$sender_name], $address, '', '', $subject, $body, '');
		} catch(Exception $e) { $log .= "Unable to send e-mail to ".get_contact($dbc, $address).": ".$e->getMessage()."\n"; }
	}
	$log .= "Notification Sent.";
	mysqli_query($dbc, "UPDATE `ticket_notifications` SET `log` = '".$noti['log']."\n$log', `status` = 'Sent', `send_date` = '".date('Y-m-d')."' WHERE `ticketnotificationid` = '$ticketnotificationid'");
	echo $log;
}