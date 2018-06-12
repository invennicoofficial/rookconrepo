<?php include('../include.php');
error_reporting(0);
checkAuthorised();
ob_clean();

if($_GET['action'] == 'save_estimate_scope') {
	$table = filter_var($_POST['table_name'],FILTER_SANITIZE_STRING);
	$id = filter_var($_POST['id'],FILTER_SANITIZE_STRING);
	$line = filter_var($_POST['line'],FILTER_SANITIZE_STRING);
	$rate = filter_var($_POST['ratecard'],FILTER_SANITIZE_STRING);
	$field = filter_var($_POST['field_name'],FILTER_SANITIZE_STRING);
	$value = filter_var($_POST['value'],FILTER_SANITIZE_STRING);
	$id_field = ($table == 'rate_card_breakdown' ? 'rcbid' : 'id');
	
	if(!($id > 0)) {
		mysqli_query($dbc, "INSERT INTO `$table` () VALUES ()");
		$id = mysqli_insert_id($dbc);
		echo $id;
		if($table == 'rate_card_estimate_scope_lines') {
			mysqli_query($dbc, "UPDATE `$table` SET `line_id`='$line', `rate_id`='$rate' WHERE `$id_field`='$id'");
		} else if($table == 'rate_card_breakdown') {
			mysqli_query($dbc, "UPDATE `$table` SET `rate_card_id`='$rate', `rate_card_type`='scope' WHERE `$id_field`='$id'");
		}
	}
	mysqli_query($dbc, "UPDATE `$table` SET `$field`='$value' WHERE `$id_field`='$id'");
}