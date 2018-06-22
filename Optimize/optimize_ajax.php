<?php include_once('../include.php');
ob_clean();

if($_GET['action'] == 'add_macro') {
	set_config($dbc, 'upload_macros', filter_var(implode('#*#',$_POST['value']),FILTER_SANITIZE_STRING));
}
else if($_GET['action'] == 'lock') {
	$region = filter_var($_POST['region'],FILTER_SANITIZE_STRING);
	if($region != '') {
		set_config($dbc, 'region_lock_'.config_safe_str($region), time());
	}
	$location = filter_var($_POST['location'],FILTER_SANITIZE_STRING);
	if($location != '') {
		set_config($dbc, 'location_lock_'.config_safe_str($location), time());
	}
	$classification = filter_var($_POST['classification'],FILTER_SANITIZE_STRING);
	if($classification != '') {
		set_config($dbc, 'classification_lock_'.config_safe_str($classification), time());
	}
}
else if($_GET['action'] == 'assign_ticket') {
	$equipmentid = filter_var($_POST['equipment'],FILTER_SANITIZE_STRING);
	$table = filter_var($_POST['table'],FILTER_SANITIZE_STRING);
	$id_field = filter_var($_POST['id_field'],FILTER_SANITIZE_STRING);
	$id = filter_var($_POST['id'],FILTER_SANITIZE_STRING);
	$date = filter_var($_POST['date'],FILTER_SANITIZE_STRING);
	$max_start = $dbc->query("SELECT MAX(`to_do_start_time`) FROM (SELECT `to_do_start_time` FROM `ticket_schedule` WHERE `equipmentid`='$equipmentid' AND `to_do_date`='$date' AND `deleted`=0 UNION SELECT `to_do_start_time` FROM `tickets` WHERE `equipmentid`='$equipmentid' AND `to_do_date`='$date' AND `deleted`=0) `times`")->fetch_array()[0];
	if($max_start == '') {
		$max_start = '7:00 AM';
	}
	$start_time = date('g:i a',strtotime($max_start.' + 1 hour'));
	// echo $start_time.' ('.$max_start.' + 1 hour'.') ';
	// echo "UPDATE `$table` SET `to_do_date`='$date', `equipmentid`='$equipmentid', `to_do_start_time`=".($table == 'ticket_schedule' ? "IF(`scheduled_lock`='1',`to_do_start_time`,'$start_time')" : "'$start_time'")." WHERE `$id_field`='$id'";
	$dbc->query("UPDATE `$table` SET `to_do_date`='$date', `equipmentid`='$equipmentid', `to_do_start_time`=".($table == 'ticket_schedule' ? "IF(`scheduled_lock`='1',`to_do_start_time`,'$start_time')" : "'$start_time'")." WHERE `$id_field`='$id'");
}
else if($_GET['action'] == 'assign_ticket_deliveries') {
	$equipmentid = filter_var($_POST['equipment'],FILTER_SANITIZE_STRING);
	$ticket = filter_var($_POST['ticket'],FILTER_SANITIZE_STRING);
	$start_time = filter_var($_POST['start'],FILTER_SANITIZE_STRING);
	$increment = filter_var($_POST['increment'],FILTER_SANITIZE_STRING);
	if($start_time == '') {
		$start_time = '08:00';
	}
	$start_time = date('H:i',strtotime($start_time));
	if($increment == '') {
		$increment = '30 minutes';
	}
	$stops = $dbc->query("SELECT `id` FROM `ticket_schedule` WHERE `ticketid`='$ticket' AND `deleted`=0 ORDER BY `id`");
	while($stop = $stops->fetch_assoc()) {
		$dbc->query("UPDATE `ticket_schedule` SET `to_do_start_time`='$start_time', `equipmentid`='$equipmentid' WHERE `id`='".$stop['id']."'");
		$start_time = date('H:i',strtotime($start_time.' + '.$increment));
	}
}