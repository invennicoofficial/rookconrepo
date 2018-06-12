<?php include_once('../include.php');
checkAuthorised('sales_order');
if($posid > 0) {
	$get_so = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `sales_order` WHERE `posid` = '$posid'"));
	$customerid = $get_so['contactid'];

	$so_services = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `sales_order_product` WHERE `posid` = '$posid' AND `type_category` = 'services'"),MYSQLI_ASSOC);

	$get_rc = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `rate_card` WHERE `clientid` = '$customerid' AND `deleted` = 0 AND `on_off` = 1 AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31')"));
	if(!empty($get_rc)) {
		$ratecardid = $get_rc['ratecardid'];
		$rc_services = explode('**', $get_rc['services']);
		$existing_services = [];
		foreach($rc_services as $rc_service) {
			$rc_service = explode('#', $rc_service);
			$rc_serviceid = $rc_service[0];
			$rc_service_price = $rc_service[1];
			foreach($so_services as $so_service) {
				if($so_service['inventoryid'] == $rc_serviceid && number_format($so_service['price'], 2, '.', '') != number_format($rc_service_price, 2, '.', '')) {
					$service = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `services` WHERE `serviceid` = '$rc_serviceid'"));
					$existing_services[$rc_serviceid] = [$service['heading'], number_format($rc_service_price, 2, '.', ''), number_format($so_service['price'], 2, '.', '')];
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
	$.ajax({
		url: '../Sales Order/add_customer_rate_card.php',
		method: 'POST',
		data: { ratecardid: '<?= $ratecardid ?>', posid: '<?= $posid ?>', existing: existing },
		success: function(response) {
			console.log(response);
		}
	});
	</script>
<?php }