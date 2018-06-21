<?php include_once('../include.php');
checkAuthorised('labour');
if($labourid > 0 && isset($_POST['ratecardid'])) {
	$rate_cards_keep = [];
	foreach($_POST['ratecardid'] as $i => $ratecardid) {
		$row_i = filter_var($_POST['ratecard_row_i'][$i]);

		$start_date = filter_var($_POST['start_date'][$i],FILTER_SANITIZE_STRING);
		$end_date = filter_var($_POST['end_date'][$i],FILTER_SANITIZE_STRING);
		$alert_date = filter_var($_POST['alert_date'][$i],FILTER_SANITIZE_STRING);
		$alert_staff = filter_var(implode(',',$_POST['alert_staff_'.$row_i]),FILTER_SANITIZE_STRING);
		$uom = filter_var($_POST['uom'][$i],FILTER_SANITIZE_STRING);
		if($uom == 'NEW_UOM') {
			$uom = filter_var($_POST['uom_new'][$i],FILTER_SANITIZE_STRING);
		}
		$cost = filter_var($_POST['cost'][$i],FILTER_SANITIZE_STRING);
		$profit_percent = filter_var($_POST['profit_percent'][$i],FILTER_SANITIZE_STRING);
		$profit_dollar = filter_var($_POST['profit_dollar'][$i],FILTER_SANITIZE_STRING);
		$price = filter_var($_POST['price'][$i],FILTER_SANITIZE_STRING);

		if(!empty($start_date.$end_date.$alert_date.$alert_staff.$uom.$cost.$profit_percent.$profit_dollar.$price)) {
			$history = 'Labour rate card '.($ratecardid == '' ? 'Added' : 'Edited').' by '.decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']).' on '.date('Y-m-d h:i:s');
			$sql = '';
			if($ratecardid == '') {
				$sql = "INSERT INTO `tile_rate_card` (`tile_name`,`src_id`,`start_date`,`end_date`,`uom`,`cost`,`profit_percent`,`profit_dollar`,`price`,`history`,`created_by`,`alert_date`,`alert_staff`) VALUES
					('labour','$labourid','$start_date','$end_date','$uom','$cost','$profit_percent','$profit_dollar','$price','$history','".$_SESSION['contactid']."','$alert_date','$alert_staff')";
				$result = mysqli_query($dbc, $sql);
				$ratecardid = mysqli_insert_id($dbc);
			}
			else {
				$sql = "UPDATE `tile_rate_card` SET `src_id`='$labourid',`start_date`='$start_date',`end_date`='$end_date',`uom`='$uom',`cost`='$cost',`profit_percent`='$profit_percent',`profit_dollar`='$profit_dollar',`price`='$price',`history`=IFNULL(CONCAT(`history`,'<br />\n','$history'),'$history'),`alert_date`='$alert_date',`alert_staff`='$alert_staff' WHERE `ratecardid`='$ratecardid'";
				$result = mysqli_query($dbc, $sql);
			}
			$rate_cards_keep[] = $ratecardid;
		}
	}
	$rate_cards_keep = "'".implode("','", $rate_cards_keep)."'";
	mysqli_query($dbc, "UPDATE `tile_rate_card` SET `deleted` = 1 WHERE `tile_name` = 'labour' AND `src_id` = '$labourid' AND `ratecardid` NOT IN ($rate_cards_keep)");
}