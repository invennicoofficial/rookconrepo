<?php
include	('../database_connection.php');
include	('../global.php');
include ('../function.php');
include ('../phpmailer.php');

if(isset($_GET['certificateid'])) {
	$query = "cert.certificateid={$_GET['id']}";
}
else {
	$query = "cert.reminder_date=DATE(NOW())";
}
$manager = get_config($dbc, 'certificate_reminder_contact');
$subject = get_config($dbc, 'certificate_reminder_subject');
$message = get_config($dbc, 'certificate_reminder_body');

if(date('H') == '08') {
	$sql = "SELECT c.first_name, c.last_name, cert.*
		FROM certificate cert LEFT JOIN contacts c ON cert.contactid=c.contactid WHERE $query";
	$cert_results = mysqli_query($dbc, $sql);

	while($row = mysqli_fetch_array($cert_results)) {
		$staff = trim(decryptIt($row['first_name']).' '.decryptIt($row['last_name']));
		$subject = str_replace("[ISSUE]", $row['issue_date'], $subject);
		$subject = str_replace("[EXPIRY]", $row['expiry_date'], $subject);
		$subject = str_replace("[STAFF]", $staff, $subject);
		$subject = str_replace("[TYPE]", $row['certificate_type'], $subject);
		$subject = str_replace("[TITLE]", $row['title'], $subject);
		$subject = str_replace("[DESCRIPTION]", $row['description'], $subject);
		
		$message = str_replace("[ISSUE]", $row['issue_date'], $message);
		$message = str_replace("[EXPIRY]", $row['expiry_date'], $message);
		$message = str_replace("[STAFF]", $staff, $message);
		$message = str_replace("[TYPE]", $row['certificate_type'], $message);
		$message = str_replace("[TITLE]", $row['title'], $message);
		$message = str_replace("[DESCRIPTION]", $row['description'], $message);
		
		if($row['certificate_reminder'] > 0) {
			$result = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM contacts WHERE contactid='{$row['certificate_reminder']}'"));
			$cert_email = get_email($dbc, $result['contactid']);
			
			$time = date('Y-m-d h:i:s');
			$title = $row['title'];
			
			try {
				send_email('', $cert_email, '', '', $subject, $message, '');
				echo "<p>$subject sent to $cert_email at $time</p>";
			} catch (Exception $e) {
				echo "Unable to send email: $title to $email\n";
			}
		}
		if($manager > 0) {
			$manager = get_email($dbc, $manager);
			
			$time = date('Y-m-d h:i:s');
			$title = $row['title'];
			
			try {
				echo "<p>$subject sent to $manager at $time</p>";
				send_email("", $manager, '', '', $subject, $message, '');
			} catch (Exception $e) {
				echo "Unable to send email: $title to $manager\n";
			}
		} else if(filter_var($manager, FILTER_VALIDATE_EMAIL)) {
			$time = date('Y-m-d h:i:s');
			$title = $row['title'];
			
			try {
				echo "<p>$subject sent to $manager at $time</p>";
				send_email("", $manager, '', '', $subject, $message, '');
			} catch (Exception $e) {
				echo "Unable to send email: $title to $manager\n";
			}
		}
	}
} ?>