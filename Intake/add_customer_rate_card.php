<?php include_once('../include.php');
checkAuthorised('intake');
ob_clean();

$contactid = $_POST['contactid'];
if($contactid > 0) {
	$existing = $_POST['existing'];
	if(!is_array($existing)) {
		$existing = [$existing];
	}
	$intake_services = json_decode($_POST['intake_services']);

	$ratecardid = $_POST['ratecardid'];
	if($ratecardid == 'NEW') {
		mysqli_query($dbc, "INSERT INTO `rate_card` (`clientid`,`rate_card_name`, `when_added`, `who_added`) VALUES ('$contactid', '".(!empty(get_client($dbc, $contactid)) ? get_client($dbc, $contactid) : get_contact($dbc, $contactid))."', '".date('Y-m-d')."', '".$_SESSION['contactid']."')");
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
			foreach($intake_services as $key => $intake_service) {
				$intake_service = json_decode(json_encode($intake_service), true);
				$serviceid = $intake_service['serviceid'];
				if($serviceid == $rc_serviceid)	{
					$price = $intake_service['price'];
					$price_diff = number_format($price, 2, '.', '') - number_format($rc_service_price, 2, '.', '');
					$total_price += $price_diff;
					$updated_services .= $rc_serviceid.'#'.$price.'#'.$rc_service[2].'**';
					unset($intake_services[$key]);
					break;
				}
			}
		} else if($rc_serviceid > 0) {
			foreach($intake_services as $key => $intake_service) {
				$intake_service = json_decode(json_encode($intake_service), true);
				$serviceid = $intake_service['serviceid'];
				if($rc_serviceid == $serviceid) {
					unset($intake_services[$key]);
					break;
				}
			}
			$updated_services .= $rc_serviceid.'#'.$rc_service_price.'#'.$rc_service[2].'**';
		}
	}

	foreach($intake_services as $intake_service) {
		$intake_service = json_decode(json_encode($intake_service), true);
		$serviceid = $intake_service['serviceid'];
		$price = $intake_service['price'];
		if($serviceid > 0) {
			$updated_services .= $serviceid.'#'.$price.'#**';
			$total_price += number_format($price, 2, '.', '');	
		}
	}
	mysqli_query($dbc, "UPDATE `rate_card` SET `services` = '$updated_services', `total_price` = '$total_price' WHERE `ratecardid` = '$ratecardid'");
}