<?php include('../database_connection.php');
include('../phpmailer.php');

if($_GET['fill'] == 'send_email') {
	$addresses = explode(',',$_POST['email']);print_r($_POST);
	$attach = $_POST['file'];
	$from = $_POST['from'];
	$subject = $_POST['subject'];
	$body = $_POST['body'];
	foreach($addresses as $address) {
		echo "\nTo: ".$address.', From: '.$from.', Subject: '.$subject.', Attachment: '.$attach;
		send_email($from, $address, '', '', $subject, $body, $attach);
	}
}