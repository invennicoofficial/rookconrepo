<?php include('../include.php');
$region = filter_var(($_GET['region'] != '' ? $_GET['region'] : '%'),FILTER_SANITIZE_STRING);
$location = filter_var(($_GET['location'] != '' ? $_GET['location'] : '%'),FILTER_SANITIZE_STRING);
$classification = filter_var(($_GET['classification'] != '' ? $_GET['classification'] : '%'),FILTER_SANITIZE_STRING);
$date = filter_var(($_GET['date'] != '' ? $_GET['date'] : date('Y-m-d')),FILTER_SANITIZE_STRING);

$city_target = $dbc->query("SELECT IFNULL(`ticket_schedule`.`city`,`tickets`.`city`) FROM `tickets` LEFT JOIN `ticket_schedule` ON `tickets`.`ticketid`=`ticket_schedule`.`ticketid` WHERE `tickets`.`deleted`=0 AND IFNULL(`ticket_schedule`.`deleted`,0)=0 AND IFNULL(NULLIF(IFNULL(`ticket_schedule`.`to_do_date`,`tickets`.`to_do_date`),''),'$date')='$date' AND IFNULL(IFNULL(`ticket_schedule`.`city`,`tickets`.`city`),'') != '' AND CONCAT(',',IFNULL(`tickets`.`region`,''),',') LIKE '%,$region,%' AND CONCAT(',',IFNULL(`tickets`.`con_location`,''),',') LIKE '%,$location,%' AND CONCAT(',',IFNULL(`tickets`.`classification`,''),',') LIKE '%,$classification,%' AND IFNULL(NULLIF(IFNULL(`ticket_schedule`.`equipmentid`,`tickets`.`equipmentid`),''),0)=0 GROUP BY IFNULL(`ticket_schedule`.`city`,`tickets`.`city`) ORDER BY COUNT(*) DESC")->fetch_array()[0];
if($city_target != '') {
	$height = $_GET['y'];
	$width = $_GET['x'];
	$ratio = $width / $height;
	if($ratio < 1) {
		$height = 640;
		$width = round($height * $ratio);
	} else {
		$width = 640;
		$height = round($width / $ratio);
	}
	$map_url = 'https://maps.googleapis.com/maps/api/staticmap?center='.$city_target.'&zoom=10&size='.$width.'x'.$height.'&maptype=roadmap&key=AIzaSyD69QyoD8Qjj03K2hDNoSSK-BixGE4dI5E';
}
$ticket_list = $dbc->query("SELECT `tickets`.`ticket_label`, IF(`ticket_schedule`.`id` IS NULL, `tickets`.`ticketid`,`ticket_schedule`.`id`) `id`, IF(`ticket_schedule`.`id` IS NULL, 'ticketid','id') `id_field`, IF(`ticket_schedule`.`id` IS NULL, 'tickets','ticket_schedule') `table`, IFNULL(`ticket_schedule`.`equipmentid`,`tickets`.`equipmentid`) `equipment`, IFNULL(`ticket_schedule`.`to_do_date`,`tickets`.`to_do_date`) `to_do_date`, IFNULL(`ticket_schedule`.`to_do_start_time`,`tickets`.`to_do_start_time`) `to_do_start_time`, IFNULL(`ticket_schedule`.`to_do_end_time`,`tickets`.`to_do_end_time`) `to_do_end_time`, IF(`ticket_schedule`.`id` IS NULL, CONCAT(IFNULL(`tickets`.`address`,''),',',IFNULL(`tickets`.`city`,'')),CONCAT(IFNULL(`ticket_schedule`.`address`,''),',',IFNULL(`ticket_schedule`.`city`,''))) `address` FROM `tickets` LEFT JOIN `ticket_schedule` ON `tickets`.`ticketid`=`ticket_schedule`.`ticketid` WHERE `tickets`.`deleted`=0 AND IFNULL(`ticket_schedule`.`deleted`,0)=0 AND IFNULL(NULLIF(IFNULL(`ticket_schedule`.`to_do_date`,`tickets`.`to_do_date`),''),'$date')='$date' AND IFNULL(IFNULL(`ticket_schedule`.`city`,`tickets`.`city`),'') != '' AND CONCAT(',',IFNULL(`tickets`.`region`,''),',') LIKE '%,$region,%' AND CONCAT(',',IFNULL(`tickets`.`con_location`,''),',') LIKE '%,$location,%' AND CONCAT(',',IFNULL(`tickets`.`classification`,''),',') LIKE '%,$classification,%' AND IFNULL(NULLIF(IFNULL(`ticket_schedule`.`equipmentid`,`tickets`.`equipmentid`),''),0)=0 AND (`type` != 'warehouse' AND IFNULL(NULLIF(CONCAT(IFNULL(`ticket_schedule`.`address`,''),IFNULL(`ticket_schedule`.`city`,'')),''),'') NOT IN (SELECT CONCAT(IFNULL(`address`,''),IFNULL(`city`,'')) FROM `contacts` WHERE `category`='Warehouses'))");
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
	</script>
	<img style="margin:auto;" src="<?= $map_url ?>" class="map_img">
	<?php while($ticket = $ticket_list->fetch_assoc()) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://maps.google.com/maps/api/geocode/xml?address='.urlencode($ticket['address']).'&key='.EMBED_MAPS_KEY);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$ticket_coordinates = simplexml_load_string(curl_exec($ch))->result->geometry->location;
		curl_close($ch); ?>
		<script>
		$('.map_view').append('<span class="cursor-hand ticket" data-table="<?= $ticket['table'] ?>" data-id-field="<?= $ticket['id_field'] ?>" data-id="<?= $ticket['id'] ?>" style="position:absolute;top:'+Math.round(height * 3.521067551250225 * <?= $ratio > 1 ? $ratio : 1 ?> * (<?= $map_center->lat ?> - <?= $ticket_coordinates->lat ?>) + height - 20)+'px;left:calc(20% + '+Math.round(width * 2.286361409982097 * <?= $ratio > 1 ? 1 : $ratio ?> * (<?= $ticket_coordinates->lng ?> - <?= $map_center->lng ?>) + width - 10)+'px);"><img class="no-toggle drag-handle" title="<?= $ticket['ticket_label'] ?>" src="../img/icons/location-pin.png" style="height:20px;width:20px;"></span>');
		</script>
	<?php } ?>
	<script>
	$(document).ready(function() {
		initOptions();
		$('.map_view').find('img.map_img').css('width',(width * 2) + 'px').css('height',(height * 2) + 'px').on('dragstart', function(event) { event.preventDefault(); });
		initTooltips();
		$('.map_view img.map_img').mousedown(function() {
			$('.draw_sort').empty();
			$(document).mousemove(mouseMove);
			$(document).mouseup(function() {
				doneDraw();
			});
		})
	});
	function doneDraw() {
		ticket_list = [];
		$('.drag-handle').each(function() {
			var y = $(this).closest('span').offset().top;
			var x = ($(this).closest('span').offset().left+10);
			if($('.drawn_dot').filter(function() { return $(this).offset().top < y && $(this).offset().left < x; }).length > 0
				&& $('.drawn_dot').filter(function() { return $(this).offset().top < y && $(this).offset().left > x; }).length > 0
				&& $('.drawn_dot').filter(function() { return $(this).offset().top > y && $(this).offset().left < x; }).length > 0
				&& $('.drawn_dot').filter(function() { return $(this).offset().top > y && $(this).offset().left > x; }).length > 0) {
				ticket_list.push(this);
			}
		});
		var final_dot = $('.drawn_dot').last();
		if(final_dot.length > 0) {
			$(".draw_sort").append('<img class="inline-img drag_handle cursor-hand" src="../img/icons/drag_handle.png" style="max-width:100px;position:absolute;top:'+(final_dot.offset().top+8-$('.draw_sort').offset().top)+'px;left:'+(final_dot.offset().left+8-$('.draw_sort').offset().left)+'px;z-index:1;">');
		}
		$(document).off('mousemove',mouseMove).off('mouseup');
		initDraw();
	}
	function mouseMove(e) {
        var color = '#000000';
        var size = '2px';
		var prev_dot = $('.drawn_dot').last();
		var off_x = $('.draw_sort').offset().left;
		var off_y = $('.draw_sort').offset().top;
		if(prev_dot.length > 0) {
			var prev_x = prev_dot.offset().left;
			var prev_y = prev_dot.offset().top;
			var diff_x = e.pageX - prev_x;
			var diff_y = e.pageY - prev_y;
			var x_inc = (Math.abs(diff_x) > Math.abs(diff_y) ? (diff_x > 0 ? 1 : -1) : diff_x / Math.abs(diff_y));
			for(var i = prev_x; (i < e.pageX && diff_x > 0) || (i > e.pageX && diff_x < 0); i += x_inc) {
				prev_y += (diff_y / (diff_x / x_inc));
				$(".draw_sort").append(
					$('<div class="drawn_dot"></div>')
						.css('position', 'absolute')
						.css('top', (prev_y - off_y) + 'px')
						.css('left', (i - off_x) + 'px')
						.css('width', size)
						.css('height', size)
						.css('background-color', color)
				);
			}
		}
        $(".draw_sort").append(
            $('<div class="drawn_dot"></div>')
                .css('position', 'absolute')
                .css('top', (e.pageY - off_y) + 'px')
                .css('left', (e.pageX - off_x) + 'px')
                .css('width', size)
                .css('height', size)
                .css('background-color', color)
        );
	}
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