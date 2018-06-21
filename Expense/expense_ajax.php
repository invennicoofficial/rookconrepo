<?php
//Ajax//
session_start();
include ('../database_connection.php');
include ('../function.php');
include ('../phpmailer.php');

$id = $_POST['expenseid'];
$user = $_SESSION['contactid'];

if($_GET['action'] == 'delete') {
	$source = $_POST['source'];
	if($source == 'manager' || $source == 'payables') {
		$sql = "UPDATE `expense` SET `status`='Rejected', `approval_by`='$user', `approval_date`='".date('Y-m-d')."' WHERE `expenseid`='$id'";
		echo $sql."\n";
		mysqli_query($dbc, $sql);

		$expense = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `expense` WHERE `expenseid`='$id'"));

		$to = get_email($dbc, $expense['staff']);
		$subject = "Your expense was rejected";
		$body = "This is to inform you that one of your expenses was rejected. Please contact your manager for more details.<br />
			Expense Date: ".$expense['ex_date']."<br />
			Expense Heading: ".$expense['title']."<br />
			Expense Type: ".$expense['type']."<br />
			Expense Amount: ".$expense['total'];

		send_email([get_email($dbc, $_SESSION['contactid']) => get_contact($dbc, $_SESSION['contactid']], $to, '', '', $subject, $body, '');
	}
        $date_of_archival = date('Y-m-d');

	$sql = "UPDATE `expense` SET `deleted`=1, `date_of_archival` = '$date_of_archival' WHERE `expenseid`='$id'";
	echo $sql;
	exit;
	mysqli_query($dbc, $sql);
}
if($_GET['action'] == 'pay') {
	$sql = "UPDATE `expense` SET `status`='Paid', `paid_by`='$user', `paid_date`='".date('Y-m-d')."' WHERE `expenseid`='$id'";
	echo $sql;
	mysqli_query($dbc, $sql);
}
if($_GET['action'] == 'approve') {
	$sql = "UPDATE `expense` SET `status`='Approved', `approval_by`='$user', `approval_date`='".date('Y-m-d')."' WHERE `expenseid`='$id'";
	echo $sql;
	mysqli_query($dbc, $sql);
}
if($_GET['action'] == 'comment') {
	$comments = $_POST['comments']." (Comment added by ".get_contact($dbc,$user)." on ".date('Y-m-d h:i:s').")";
	$sql = "UPDATE `expense` SET `comments`='$comments' WHERE `expenseid`='$id'";
	echo $sql;
	mysqli_query($dbc, $sql);
}