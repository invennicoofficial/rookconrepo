<?php include('../include.php');
ob_clean();
error_reporting(0);
$folder = filter_var($_GET['folder'],FILTER_SANITIZE_STRING);
if($_GET['action'] == 'addHeading') {
	mysqli_query($dbc, "INSERT INTO `order_checklists` (`tile_name`) VALUES ('$folder')");
} else if($_GET['action'] == 'addItem') {
	$heading = filter_var($_POST['heading'],FILTER_SANITIZE_STRING);
	$item = filter_var($_POST['item'],FILTER_SANITIZE_STRING);
	$history = "$item added by ".get_contact($dbc, $_SESSION['contactid'])." on ".date('Y-m-d').' at '.date('g:i a');
	mysqli_query($dbc, "INSERT INTO `order_checklist_lines` (`checklist_id`,`checklist`,`history`) VALUES ('$heading','$item','$history')");
	echo mysqli_insert_id($dbc);
} else if($_GET['action'] == 'checkItem') {
	$checked = filter_var($_GET['checked'],FILTER_SANITIZE_STRING);
	$item = filter_var($_GET['item'],FILTER_SANITIZE_STRING);
	$history = " marked ".($checked ? 'completed' : 'incomplete')." by ".get_contact($dbc, $_SESSION['contactid'])." on ".date('Y-m-d').' at '.date('g:i a');
	mysqli_query($dbc, "UPDATE `order_checklist_lines` SET `checked`='$checked', `history`=CONCAT(`history`,'&lt;br /&gt;',`checklist`,'$history') WHERE `id`='$item'");
} else if($_GET['action'] == 'updateHeading') {
	$heading = filter_var($_POST['heading'],FILTER_SANITIZE_STRING);
	$value = filter_var($_POST['value'],FILTER_SANITIZE_STRING);
	mysqli_query($dbc, "UPDATE `order_checklists` SET `heading`='$value' WHERE `id`='$heading'");
} else if($_GET['action'] == 'changeHeading') {
	$heading = filter_var($_GET['heading'],FILTER_SANITIZE_STRING);
	$item = filter_var($_GET['item'],FILTER_SANITIZE_STRING);
	mysqli_query($dbc, "UPDATE `order_checklist_lines` SET `checklist_id`='$heading' WHERE `id`='$item'");echo "UPDATE `order_checklist_lines` SET `checklist_id`='$heading' WHERE `id`='$item'";
} else if($_GET['action'] == 'sortItems') {
	foreach($_POST['items'] as $i => $item) {
		$item = filter_var($item, FILTER_SANITIZE_STRING);
		$sort = count($_POST['items']) - $i;
		mysqli_query($dbc, "UPDATE `order_checklist_lines` SET `sort_order`='$sort' WHERE `id`='$item'");
	}
}