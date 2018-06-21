<?php include('../include.php');
ob_clean();

if($_GET['fill'] == 'add_note') {
	$client = $_POST['client'];
	$user = $_POST['user'];
	$note = $_POST['notes'];
	$date = date('Y-m-d g:i A');

	$note .= "<br />\n<small><em>Note added by ".get_contact($dbc, $user)." at $date</em></small>";
	$note = filter_var(htmlentities($note), FILTER_SANITIZE_STRING);

	$result = mysqli_query($dbc, "INSERT INTO `client_daily_log_notes` (`client_id`, `note`, `created_by`) VALUES ('$client', '$note', '".$_SESSION['contactid']."')");
	echo "<li class='ui-state-default no-sort'>".html_entity_decode($note)."</li>";
}
if($_GET['fill'] == 'delete') {
	$id = $_GET['id'];
    $date_of_archival = date('Y-m-d');
	$query = "UPDATE `client_daily_log_notes` SET `deleted`=1, `date_of_archival` = '$date_of_archival' WHERE `note_id`='$id'";
	$result = mysqli_query($dbc,$query);
}
if($_GET['fill'] == 'reply') {
	$id = $_POST['id'];
	$reply = filter_var(htmlentities('<p>'.$_POST['reply'].'</p>'),FILTER_SANITIZE_STRING);
	$query = "UPDATE `client_daily_log_notes` SET `note`=CONCAT(`note`,'$reply') WHERE `note_id`='$id'";
	$result = mysqli_query($dbc,$query);
}
if($_GET['fill'] == 'alert') {
	$item_id = $_POST['id'];
	$user = $_POST['user'];
	$result = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `client_daily_log_notes` WHERE note_id='$item_id'"));
	$client = $result['client_id'];
	$link = WEBSITE_URL."/Daily Log Notes/daily_log_notes.php";
	$text = "Log Notes for ".get_contact($dbc, $client);
	$date = date('Y/m/d');
	$sql = mysqli_query($dbc, "INSERT INTO `alerts` (`alert_date`, `alert_link`, `alert_text`, `alert_user`) VALUES ('$date', '$link', '$text', '$user')");
}
if($_GET['fill'] == 'email') {
	$item_id = $_POST['id'];
	$user = $_POST['user'];
	$subject = '';
	$title = '';
	$result = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `client_daily_log_notes` WHERE note_id='$item_id'"));
	$id = $result['note_id'];
	$title = explode('<p>',html_entity_decode($result['note']))[0];
	$subject = "A reminder about a Daily Log Note";
	$contacts = mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `contactid`='$user'");
	while($row = mysqli_fetch_array($contacts)) {
		$email_address = get_email($dbc, $row['contactid']);
		if(trim($email_address) != '') {
			$body = "Hi ".decryptIt($row['first_name'])."<br />\n<br />
				This is a reminder about a Daily Log Note.<br />\n<br />
				".html_entity_decode($result['note']).".<br />\n<br />
				<a href='".WEBSITE_URL."/Daily Log Notes/daily_log_notes.php'>Click here</a> to see the Daily Log Notes.";
			send_email('', $email_address, '', '', $subject, $body, '');
		}
	}
}
if($_GET['fill'] == 'reminder') {
	$item_id = $_POST['id'];
	$sender = get_email($dbc, $_SESSION['contactid']);
	$date = $_POST['schedule'];
	$to = $_POST['user'];
	$subject = '';
	$title = '';
	$result = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `client_daily_log_notes` WHERE note_id='$item_id'"));
	$id = $result['note_id'];
	$subject = "A reminder about a Daily Log Note";
	$body = htmlentities("This is a reminder about a Daily Log Note.<br />\n<br />
		This is a reminder about a Daily Log Note.<br />\n<br />
		".html_entity_decode($result['note']).".<br />\n<br />
		<a href=\"".WEBSITE_URL."/Daily Log Notes/index.php?display_contact=".$to."\">Click here</a> to see the Daily Log Notes.");

    mysqli_query($dbc, "UPDATE `reminders` SET `done` = 1 WHERE `contactid` = '$to' AND `src_table` = 'client_daily_log_notes' AND `src_tableid` = '$item_id'");
	$result = mysqli_query($dbc, "INSERT INTO `reminders` (`contactid`, `reminder_date`, `reminder_time`, `reminder_type`, `subject`, `body`, `sender`, `src_table`, `src_tableid`)
		VALUES ('$to', '$date', '08:00:00', 'QUICK', '$subject', '$body', '$sender', 'client_daily_log_notes', '$item_id')");
}
if($_GET['fill'] == 'upload') {
	$id = $_GET['id'];
	$filename = $_FILES['file']['name'];
	$file = $_FILES['file']['tmp_name'];
    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }
	move_uploaded_file($file, "download/".$filename);
	$query = "UPDATE `client_daily_log_notes` SET `documents`=CONCAT(IFNULL(`documents`,''),'#*#".filter_var($filename,FILTER_SANITIZE_STRING)."') WHERE `note_id`='$id'";
	$result = mysqli_query($dbc, $query);
}
if($_GET['action'] == 'settings_tabs') {
	set_config($dbc, 'log_note_categories', filter_var(implode(',',$_POST['categories']),FILTER_SANITIZE_STRING));
	set_config($dbc, 'log_note_tabs', filter_var($_POST['tab_mode'],FILTER_SANITIZE_STRING));
}