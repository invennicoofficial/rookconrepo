<?php include_once('../include.php');
checkAuthorised('intake');
if($intakeid > 0) {
	$get_rc = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `rate_card` WHERE `clientid` = '$contactid' AND `deleted` = 0 AND `on_off` = 1 AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31')"));
	if(!empty($get_rc)) {
		$ratecardid = $get_rc['ratecardid'];
		$rc_services = explode('**', $get_rc['services']);
		$existing_services = [];
		foreach($rc_services as $rc_service) {
			$rc_service = explode('#', $rc_service);
			$rc_serviceid = $rc_service[0];
			$rc_service_price = $rc_service[1];
			foreach($intake_services as $intake_serviceid => $intake_service) {
				if($intake_serviceid == $rc_serviceid && number_format($intake_service, 2, '.', '') != number_format($rc_service_price, 2, '.', '')) {
					$service = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `services` WHERE `serviceid` = '$rc_serviceid'"));
					$existing_services[$rc_serviceid] = [$service['heading'], number_format($rc_service_price, 2, '.', ''), number_format($intake_service, 2, '.', '')];
				}
			}
		}
	} else {
		$ratecardid = 'NEW';
	} ?>
	<script type="text/javascript">
	var existing = [];
	<?php foreach($existing_services as $serviceid => $existing_service) { ?>
		if(confirm('<?= $existing_service[0] ?> already exists in this Customer\'s Rate Card with a different price. Would you like to update the price from <?= $existing_service[1] ?> to <?= $existing_service[2] ?>?')) {
			existing.push('<?= $serviceid ?>');
		}
	<?php } ?>
	var intake_services = [];
	<?php foreach($intake_services as $intake_serviceid => $intake_service) { ?>
		var intake_service = { serviceid: '<?= $intake_serviceid ?>', price: '<?= $intake_service ?>' };
		intake_services.push(intake_service);
	<?php } ?>
	intake_services = JSON.stringify(intake_services);

	$.ajax({
		url: '../Intake/add_customer_rate_card.php',
		method: 'POST',
		data: { ratecardid: '<?= $ratecardid ?>', contactid: '<?= $contactid ?>', intake_services: intake_services, existing: existing },
		success: function(response) {
			console.log(response);
		}
	});
	</script>
<?php }