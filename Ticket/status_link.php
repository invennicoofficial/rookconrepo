<?php $guest_access = true;
include_once ('../include.php');
$details = json_decode(decryptIt($_GET['s']), TRUE);
$ticketid = filter_var($details['ticket'],FILTER_SANITIZE_STRING);
$stopid = filter_var($details['stop'],FILTER_SANITIZE_STRING);
$get_ticket = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `tickets` WHERE `ticketid`='$ticketid'"));
$get_stop = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `ticket_schedule` WHERE `ticketid`='$ticketid' AND `id`='$stopid'"));
$date = empty($get_stop['to_do_date']) ? $get_ticket['to_do_date'] : $get_stop['to_do_date'];
$contacts = array_filter(explode(',',$get_stop['contactid']));
$equipment = $get_stop['equipmentid'];
if(empty($contacts)) {
	$equip_assign = $get_stop['equipment_assignmentid'];
	if(empty($equip_assign)) {
		if(empty($equipment)) {
			$equipment = $get_ticket['equipmentid'];
		}
		$equip_assign = $dbc->query("SELECT * FROM `equipment_assignment` WHERE `equipmentid`='$equipment' AND '$date' BETWEEN `start_date` AND `end_date` AND `deleted`=0 ORDER BY `start_date` DESC")->fetch_assoc()['equipment_assignmentid'];
	}
	$assigned_team = $dbc->query("SELECT * FROM `equipment_assignment_staff` WHERE `equipment_assignmentid`='$equip_assign' AND `deleted`=0");
	while($contact = $assigned_team->fetch_assoc()) {
		$contacts[] = $contact['contactid'];
	}
}
$driver_cookie = $dbc->query("SELECT `location_cookie` FROM `equipment` WHERE `equipmentid`='$equipment'")->fetch_assoc()['location_cookie']; ?>
<script>
function loadStatus() {
	$.post('status_info.php', { destination: '<?= urlencode($get_stop['address'].','.$get_stop['city'].','.$get_stop['postal_code']) ?>', driver: '<?= $driver_cookie ?>' }, function(response) {
		response = response.split('#*#');
		$('[name=eta_text]').text(response[0]);
		if((response[1] > 0 || response[1] < 0) && response[2] > 0 || response[2] < 0)) {
			$('[name=map_frame]').prop('src','https://www.google.com/maps/embed/v1/directions?key='.<?= EMBED_MAPS_KEY ?>.'&mode=driving&origin='+response[1]+','+response[2]+'&destination=<?= $_POST['destination'] ?>');
		} else {
			$('[name=map_frame]').prop('src',response[1]);
		}
	});
}
$(document).ready(function() {
	loadStatus();
});
</script>
<div class="container no-gap-pad" style="height: 100vh; width: 100vw; overflow-y: hidden;">
	<div class="row">
		<h3 class="text-center"><?= $get_stop['type'] ?> at <?= implode(' - ',array_filter([$get_stop['location_name'], $get_stop['client_name'], $get_stop['address']])) ?></h3>
		<iframe style="height:calc(100vh - 185px);width:100vw;border:none;" name="map_frame" src="../blank_loading_page.php" allowfullscreen></iframe>
		<?php foreach($contacts as $contact) {
			$id = profile_id($dbc, $contact, false);
			echo '<div class="col-sm-6"><div class="pull-left"><h2 class="no-gap-pad">'.$id.'</h2></div><h2 class="no-gap-pad">'.get_contact($dbc, $contact).'</h2>';
			echo '<div class="clearfix"></div></div>';
		} ?>
		<h3 class="text-center no-gap-pad">ETA: <span name="eta_text"></span></h3>
	</div>
</div>