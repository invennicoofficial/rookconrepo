<?php include('../include.php');
ob_clean();

if($_GET['action'] == 'remove_policy') {
	$policy_id = $_POST['policy_id'];
    $date_of_archival = date('Y-m-d');
	mysqli_query($dbc, "UPDATE `expense_policy` SET `deleted`=1, `date_of_archival` = '$date_of_archival' WHERE `policy_id`='$policy_id'");
} else if($_GET['action'] == 'category_fields') {
	$ex_category = preg_replace('/[^a-z]/','_',strtolower($_POST['category']));
	$db_config = mysqli_fetch_array(mysqli_query($dbc, "SELECT expense_dashboard FROM field_config_expense WHERE `tab` IN ('current_month','category_".$ex_category."') ORDER BY `tab` ASC"));
	echo $db_config['expense_dashboard'];
}