<?php include_once('../include.php');

if(!empty($_GET['reload_table'])) {
	ob_clean();
	$contactid = $_GET['edit'];
	$rate_card = mysqli_query($dbc, "SELECT * FROM `rate_card` WHERE `clientid` ='$contactid' AND `deleted` = 0 AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31')")->fetch_assoc();
	$service_fields = explode(',',get_field_config($dbc, 'services'));

	$total_hours = 0;
	$total_value = 0;

	$load_template = $_GET['load_template'];
	$template_items = [];
	$loaded_templates = mysqli_fetch_array(mysqli_query($dbc, "SELECT `service_templates` FROM `contacts` WHERE `contactid` = '$contactid'"))['service_templates'];
	foreach(explode(',',$loaded_templates) as $loaded_template) {
		if($loaded_template > 0) {
			$loaded_template = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `services_service_templates` WHERE `templateid` = '$loaded_template'"));
			if(!empty($loaded_template)) {
				if($loaded_template['templateid'] == $load_template) {
					$template_items = explode(',', $loaded_template['serviceid']);
				}
			}
		}
	}

	$category = $_POST['category'];
	$service_type = $_POST['service_type'];
	$services = [];
	foreach(explode('**',$rate_card['services']) as $service_i => $service) {
		$service_line = explode('#',$service);
		if($service_line[0] > 0) {
			$service = $dbc->query("SELECT * FROM `services` WHERE `serviceid`='{$service_line[0]}'")->fetch_assoc();
			if($service['category'] == $category && $service['service_type'] == $service_type) {
				$services[] = [$service, $service_line];
			}
		}
	}
	$panel_i = $_GET['panel_i'];
} ?>
<table id="service_group_table_<?= $panel_i ?>" class="table table-bordered customer_rate_services_group">
	<tr class="hidden-sm hidden-xs">
		<th style="width:10%;">Heading</th>
		<th style="width:10%;">Rate Card Price</th>
		<?php if(in_array('Estimated Hours',$service_fields)) {
			$columns++;  ?>
			<th style="width:10%;">Time Estimate</th>
		<?php } ?>
		<th>Comments</th>
		<?php if(in_array('Service Create Ticket',$service_fields)) {
			$columns++;  ?>
			<th style="width:10%;">Function</th>
		<?php } ?>
		<th style="width: 10%;"></th>
	</tr>
	<?php $services[] = '';
	foreach($services as $service) {
		$service_line = $service[1];
		$service = $service[0];
		$service_time_estimate = $service['estimated_hours']; ?>
		<tr <?= ($load_template > 0 && $service['serviceid'] > 0 && !in_array($service['serviceid'], $template_items)) ? 'style="display:none;"' : '' ?>>
			<?php if($service['serviceid'] > 0) { ?>
				<td data-title="Heading"><?= $service['heading'] ?><input type="hidden" name="heading" value="<?= $service['serviceid'] ?>"></td>
			<?php } else { ?>
				<td data-title="Heading">
					<select onchange="rate_service_updated(this);" name="heading" data-placeholder="Select Service" class="chosen-select-deselect"><option />
						<?php $service_categories = $dbc->query("SELECT `serviceid`,`heading`,`category`,`service_type` FROM `services` WHERE `deleted`=0 ORDER BY `category`");
							while($service_row = $service_categories->fetch_assoc()) { ?>
								<option data-category="<?= $service_row['category'] ?>" data-service-type="<?= $service_row['service_type'] ?>" value="<?= $service_row['serviceid'] ?>" <?= ($category != $service_row['category'] || $service_type != $service_row['service_type']) ? 'style="display: none;"' : '' ?>><?= $service_row['heading'] ?></option>
							<?php } ?>
					</select>
				</td>
			<?php } ?>
			<td data-title="Rate Card Price"><input type="number" name="services[]" data-contactid-field="clientid" data-row-id="<?= $rate_card['ratecardid'] ?>" data-row-field="ratecardid" value="<?= $service_line[1] ?>" data-field="services" data-prepend="<?= $service_line[0] ?>#" data-append="#<?= $service_line[2] ?>" data-append-last="**" data-table="rate_card" data-delimiter="**" class="form-control"></td>
			<?php if(in_array('Estimated Hours',$service_fields)) { ?>
				<td data-title="Time Estimate"><input type="text" name="service_time_estimate" class="form-control" disabled readonly value="<?= $service_time_estimate ?>"></td>
			<?php } ?>
			<td data-title="Comments"><input type="text" name="service_comments[]" data-contactid-field="clientid" data-row-id="<?= $rate_card['ratecardid'] ?>" data-row-field="ratecardid" value="<?= explode('#*#',$rate_card['service_comments'])[$service_i] ?>" data-field="service_comments" data-table="rate_card" data-delimiter="#*#" class="form-control"></td>
			<?php if(in_array('Service Create Ticket',$service_fields)) { ?>
				<td data-title="Function">
					<label class="control-checkbox any-width"><input type="checkbox" name="service_create" value="<?= $service_line[0] ?>" <?= $service_line[0] > 0 && in_array($service_line[0], $template_items) ? 'checked' : '' ?>>Include on <?= TICKET_NOUN ?></label>
					<button class="btn brand-btn" name="service_create" value="<?= $service_line[0] ?>" onclick="overlayIFrameSlider('../Ticket/index.php?calendar_view=true&edit=0&<?= $contact['category'] == BUSINESS_CAT ? 'bid' : 'clientid' ?>=<?= $contactid ?>&from_type=customer_rate_services&serviceid='+this.value, 'auto', true, true); return false;">Create <?= TICKET_NOUN ?></button>
				</td>
			<?php } ?>
			<td data-title="" align="center">
                <img src="../img/icons/ROOK-add-icon.png" class="inline-img pull-right" onclick="addDelimitedRow($('#service_group_table_<?= $panel_i ?>'));">
                <img src="../img/remove.png" class="inline-img pull-right remove_btn" onclick="rate_service_remove(this, '#service_group_table_<?= $panel_i ?>');">
			</td>
			<?php $service_time_estimate = time_time2decimal($service_time_estimate);
			$total_hours += $service_time_estimate;
			if($service_time_estimate == 0) {
				$service_time_estimate = 1;
			}
			$total_value += $service_line[1]; ?>
		</tr>
	<?php } ?>
</table>