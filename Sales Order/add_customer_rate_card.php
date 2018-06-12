<?php include_once('../include.php');
checkAuthorised('sales_order');
ob_clean();

$posid = $_POST['posid'];
if($posid > 0) {
	$existing = $_POST['existing'];
	if(!is_array($existing)) {
		$existing = [$existing];
	}
	$ratecardid = $_POST['ratecardid'];
	$get_so = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `sales_order` WHERE `posid` = '$posid'"));
	$customerid = $get_so['contactid'];

	$so_services = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `sales_order_product` WHERE `posid` = '$posid' AND `type_category` = 'services'"),MYSQLI_ASSOC);

	if($ratecardid == 'NEW') {
		mysqli_query($dbc, "INSERT INTO `rate_card` (`clientid`,`rate_card_name`, `when_added`, `who_added`) VALUES ('$customerid', '".(!empty(get_client($dbc, $customerid)) ? get_client($dbc, $customerid) : get_contact($dbc, $customerid))."', '".date('Y-m-d')."', '".$_SESSION['contactid']."')");
		$ratecardid = mysqli_insert_id($dbc);
	}
	$get_rc = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `rate_card` WHERE `ratecardid` = '$ratecardid'"));

	$rc_services = explode('**', $get_rc['services']);
	$total_price = $get_rc['total_price'];
	if(empty($total_price)) {
		$total_price = 0;
	}
	$total_price = number_format($get_rc['total_price'], 2, '.', '');
	$updated_services = '';
	$existing_services = [];

	foreach($rc_services as $rc_service) {
		$rc_service = explode('#', $rc_service);
		$rc_serviceid = $rc_service[0];
		$rc_service_price = $rc_service[1];
		$existing_services[] = $rc_serviceid;
		if(in_array($rc_serviceid, $existing)) {
			foreach($so_services as $key => $so_service) {
				if($so_service['inventoryid'] == $rc_serviceid) {
					$price_diff = number_format($so_service['price'], 2, '.', '') - number_format($rc_service_price, 2, '.', '');
					$total_price += $price_diff;
					$updated_services .= $rc_serviceid.'#'.$so_service['price'].'#'.$rc_service[2].'**';
					unset($so_services[$key]);
					break;
				}
			}
		} else if($rc_serviceid > 0) {
			foreach($so_services as $key => $so_service) {
				if($so_service['inventoryid'] == $rc_serviceid) {
					unset($so_services[$key]);
					break;
				}
			}
			$updated_services .= $rc_serviceid.'#'.$rc_service_price.'#'.$rc_service[2].'**';
		}
	}

	foreach($so_services as $so_service) {
		if($so_service['inventoryid'] > 0) {
			$updated_services .= $so_service['inventoryid'].'#'.$so_service['price'].'#**';
			$total_price += number_format($so_service['price'], 2, '.', '');	
		}
	}
	mysqli_query($dbc, "UPDATE `rate_card` SET `services` = '$updated_services', `total_price` = '$total_price' WHERE `ratecardid` = '$ratecardid'");
}