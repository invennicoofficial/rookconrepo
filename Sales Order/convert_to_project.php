<?php include('../include.php');
checkAuthorised('sales_order');
error_reporting(0);
$posid = filter_var($_GET['posid'], FILTER_SANITIZE_STRING);
$projectid = $_GET['projectid'];
$projecttype = $_GET['projecttype'];
$max_sort = 0;
if($projectid > 0) {
	$max_sort = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT MAX(`sort_order`) max_sort FROM `project_scope` WHERE `projectid`='$projectid'"))['max_sort'];
	$user = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);
	mysqli_query($dbc, "INSERT INTO `project_history` (`updated_by`, `description`, `projectid`) VALUES ('$user', '".SALES_ORDER_NOUN." #$posid attached to ".PROJECT_NOUN."', '$projectid')");
} else {
	$max_sort = 0;
	mysqli_query($dbc, "INSERT INTO `project` (`project_name`,`start_date`,`businessid`,`clientid`,`projecttype`,`created_date`,`created_by`) SELECT `name`,`invoice_date`,`contactid`,`contactid`,'$projecttype','".date('Y-m-d')."','".$_SESSION['contactid']."' FROM `sales_order` WHERE `posid` = '$posid'");
	$projectid = mysqli_insert_id($dbc);
	$user = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);
	mysqli_query($dbc, "INSERT INTO `project_history` (`updated_by`, `description`, `projectid`) VALUES ('$user', 'Created from ".SALES_ORDER_NOUN." #$posid', '$projectid')");
}
$so_items = mysqli_query($dbc, "SELECT * FROM `sales_order_product` WHERE `posid` = '$posid'");
while($so_item = mysqli_fetch_array($so_items)) {
	$posproductid = $so_item['posproductid'];
	$src_table = $so_item['type_category'];
	if($src_table == 'vendor') {
		$src_table = 'vpl';
	}
	$total_price = $so_item['quantity'] * $so_item['price'];
	$max_sort++;

	mysqli_query($dbc, "INSERT INTO `project_scope` (`projectid`,`salesorderline`,`heading`,`src_table`,`src_id`,`qty`,`cost`,`price`,`retail`,`sort_order`) SELECT '$projectid',`posproductid`,`heading_name`,'$src_table',`inventoryid`,`quantity`,`price`,`price`,'$total_price','$max_sort' FROM `sales_order_product` WHERE `posproductid` = '$posproductid'");
}
mysqli_query($dbc, "UPDATE `sales_order` SET `projectid`='$projectid' WHERE `posid`='$posid'"); ?>
<script>
window.location.replace('../Project/projects.php?edit=<?= $projectid ?>');
</script>