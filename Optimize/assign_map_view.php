<?php include('../include.php');
$region = filter_var(($_GET['region'] != '' ? $_GET['region'] : '%'),FILTER_SANITIZE_STRING);
$location = filter_var(($_GET['location'] != '' ? $_GET['location'] : '%'),FILTER_SANITIZE_STRING);
$classification = filter_var(($_GET['classification'] != '' ? $_GET['classification'] : '%'),FILTER_SANITIZE_STRING);
$date = filter_var(($_GET['date'] != '' ? $_GET['date'] : date('Y-m-d')),FILTER_SANITIZE_STRING);

$city_target = $dbc->query("SELECT IFNULL(`ticket_schedule`.`city`,`tickets`.`city`) FROM `tickets` LEFT JOIN `ticket_schedule` ON `tickets`.`ticketid`=`ticket_schedule`.`ticketid` WHERE `tickets`.`deleted`=0 AND IFNULL(`ticket_schedule`.`deleted`,0)=0 AND IFNULL(NULLIF(IFNULL(`ticket_schedule`.`to_do_date`,`tickets`.`to_do_date`),''),'$date')='$date' AND IFNULL(IFNULL(`ticket_schedule`.`city`,`tickets`.`city`),'') != '' AND CONCAT(',',IFNULL(`tickets`.`region`,''),',') LIKE '%,$region,%' AND CONCAT(',',IFNULL(`tickets`.`con_location`,''),',') LIKE '%,$location,%' AND CONCAT(',',IFNULL(`tickets`.`classification`,''),',') LIKE '%,$classification,%' AND IFNULL(NULLIF(IFNULL(`ticket_schedule`.`equipmentid`,`tickets`.`equipmentid`),''),0)=0 GROUP BY IFNULL(`ticket_schedule`.`city`,`tickets`.`city`) ORDER BY COUNT(*) DESC")->fetch_array()[0];
if($city_target != '') {
	$map_url = 'https://maps.googleapis.com/maps/api/staticmap?center='.$city_target.'&zoom=10&size=640x640&maptype=roadmap&key=AIzaSyD69QyoD8Qjj03K2hDNoSSK-BixGE4dI5E';
}
$ticket_list = $dbc->query("SELECT `tickets`.`ticket_label`, IF(`ticket_schedule`.`id` IS NULL, `tickets`.`ticketid`,`ticket_schedule`.`id`) `id`, IF(`ticket_schedule`.`id` IS NULL, 'ticketid','id') `id_field`, IF(`ticket_schedule`.`id` IS NULL, 'tickets','ticket_schedule') `table`, IFNULL(`ticket_schedule`.`equipmentid`,`tickets`.`equipmentid`) `equipment`, IFNULL(`ticket_schedule`.`to_do_date`,`tickets`.`to_do_date`) `to_do_date`, IFNULL(`ticket_schedule`.`to_do_start_time`,`tickets`.`to_do_start_time`) `to_do_start_time`, IFNULL(`ticket_schedule`.`to_do_end_time`,`tickets`.`to_do_end_time`) `to_do_end_time`, IF(`ticket_schedule`.`id` IS NULL, CONCAT(IFNULL(`tickets`.`address`,''),',',IFNULL(`tickets`.`city`,'')),CONCAT(IFNULL(`ticket_schedule`.`address`,''),',',IFNULL(`ticket_schedule`.`city`,''))) `address` FROM `tickets` LEFT JOIN `ticket_schedule` ON `tickets`.`ticketid`=`ticket_schedule`.`ticketid` WHERE `tickets`.`deleted`=0 AND IFNULL(`ticket_schedule`.`deleted`,0)=0 AND IFNULL(NULLIF(IFNULL(`ticket_schedule`.`to_do_date`,`tickets`.`to_do_date`),''),'$date')='$date' AND IFNULL(IFNULL(`ticket_schedule`.`city`,`tickets`.`city`),'') != '' AND CONCAT(',',IFNULL(`tickets`.`region`,''),',') LIKE '%,$region,%' AND CONCAT(',',IFNULL(`tickets`.`con_location`,''),',') LIKE '%,$location,%' AND CONCAT(',',IFNULL(`tickets`.`classification`,''),',') LIKE '%,$classification,%' AND IFNULL(NULLIF(IFNULL(`ticket_schedule`.`equipmentid`,`tickets`.`equipmentid`),''),0)=0");
$equipment_list = $dbc->query("SELECT `equipmentid`, CONCAT(`category`,': ',`make`,' ',`model`,' ',`unit_number`) `label` FROM `equipment` WHERE `deleted`=0 ORDER BY `make`, `model`, `unit_number`")->fetch_all(MYSQLI_ASSOC);
if($map_url != '') {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://maps.google.com/maps/api/geocode/xml?address='.$city_target.'&key='.EMBED_MAPS_KEY);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$map_center = simplexml_load_string(curl_exec($ch))->result->geometry->location;
	curl_close($ch); ?>
	<script>
	var height = $('.map_view').height() / 2;
	var width = $('.map_view').width() / 2;
	if(height > width) {
		height = width;
	} else {
		width = height;
	}
	</script>
	<img style="margin:auto;" src="<?= $map_url ?>">
	<?php while($ticket = $ticket_list->fetch_assoc()) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://maps.google.com/maps/api/geocode/xml?address='.urlencode($ticket['address']).'&key='.EMBED_MAPS_KEY);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$ticket_coordinates = simplexml_load_string(curl_exec($ch))->result->geometry->location;
		curl_close($ch); ?>
		<script>
		$('.map_view').append('<span class="cursor-hand block-item ticket" data-table="<?= $ticket['table'] ?>" data-id-field="<?= $ticket['id_field'] ?>" data-id="<?= $ticket['id'] ?>" style="background-color:#000;color:#FFF;position:absolute;top:'+Math.round(height * 3.521067551250225 * (<?= $map_center->lat ?> - <?= $ticket_coordinates->lat ?>) + height)+'px;left:calc(20% + '+Math.round(width * 2.286361409982097 * (<?= $ticket_coordinates->lng ?> - <?= $map_center->lng ?>) + width)+'px);"><div class="drag-handle"><?= $ticket['ticket_label'] ?></div></span>');
		</script>
	<?php } ?>
	<script>
	$(document).ready(function() {
		initOptions();
		$('.map_view').find('img').css('width',(width * 2) + 'px').css('height',(width * 2) + 'px');
	});
	</script>
<?php } else if($ticket_list->num_rows > 0) {
	while($ticket = $ticket_list->fetch_assoc()) { ?>
		<h4><?= $ticket['ticket_label'] ?></h4>
		<div class="form-group">
			<label class="col-sm-4">Equipment:</label>
			<div class="col-sm-8">
				<select class="chosen-select-deselect" data-placeholder="Select Equipment" name="equipmentid" data-table="<?= $ticket['table'] ?>" data-id-field="<?= $ticket['id_field'] ?>" data-id="<?= $ticket['id'] ?>"><option />
					<?php foreach($equipment_list as $equipment) { ?>
						<option <?= $ticket['equipment'] == $equipment['equipmentid'] ? 'selected' : '' ?> value="<?= $equipment['equipmentid'] ?>"><?= $equipment['label'] ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4">Scheduled:</label>
			<div class="col-sm-2">
				<input type="text" name="to_do_date" data-table="<?= $ticket['table'] ?>" data-id-field="<?= $ticket['id_field'] ?>" data-id="<?= $ticket['id'] ?>" value="<?= $ticket['to_do_date'] ?>" class="form-control datepicker" placeholder="Scheduled Date">
			</div>
			<div class="col-sm-2">
				<input type="text" name="to_do_start_time" data-table="<?= $ticket['table'] ?>" data-id-field="<?= $ticket['id_field'] ?>" data-id="<?= $ticket['id'] ?>" value="<?= $ticket['to_do_start_time'] ?>" class="form-control datetimepicker" placeholder="Scheduled Start">
			</div>
			<div class="col-sm-2">
				<input type="text" name="to_do_end_time" data-table="<?= $ticket['table'] ?>" data-id-field="<?= $ticket['id_field'] ?>" data-id="<?= $ticket['id'] ?>" value="<?= $ticket['to_do_end_time'] ?>" class="form-control datetimepicker" placeholder="Scheduled End">
			</div>
		</div>
	<?php }
} else {
	echo '<h3>No Unscheduled '.TICKET_TILE.' Found</h3>';
}