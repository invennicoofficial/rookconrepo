<?php include_once('../include.php');

$service_fields = explode(',',get_field_config($dbc, 'services'));
$templateid = $_GET['templateid'];
$contactid = $_GET['contactid'];
if($templateid != 'ADD_NEW') {
	$template = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `services_service_templates` WHERE `templateid` = '$templateid'"));
	$serviceids = explode(',',$template['serviceid']);
}
$rate_card = mysqli_query($dbc, "SELECT * FROM `rate_card` WHERE `clientid` ='$contactid' AND `deleted` = 0 AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31')")->fetch_assoc();
?>
<div id="no-more-tables" class="service_table">
	<div class="form-group">
		<label class="col-sm-3">Template Name:</label>
		<div class="col-sm-9">
			<input type="text" name="template_name" value="<?= $template['name'] ?>" class="form-control">
		</div>
	</div>
	<table class="table table-bordered">
		<tr class="hidden-xs">
			<?php if(in_array('Category',$service_fields)) { ?>
				<th>Category</th>
			<?php } ?>
			<?php if(in_array('Service Type',$service_fields)) { ?>
				<th>Service Type</th>
			<?php } ?>
			<th>Heading</th>
			<th>Include</th>
		</tr>
		<?php foreach(explode('**',$rate_card['services']) as $service_i => $service) {
			$service_line = explode('#',$service);
			$serviceid = $service_line[0];
			if($serviceid > 0) {
				$service = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `services` WHERE `serviceid` = '$serviceid'")); ?>
				<tr>
					<?php if(in_array('Category',$service_fields)) { ?>
						<td data-title="Category"><?= $service['category'] ?></td>
					<?php } ?>
					<?php if(in_array('Service Type',$service_fields)) { ?>
						<td data-title="Service Type"><?= $service['service_type'] ?></td>
					<?php } ?>
					<td data-title="Heading"><?= $service['heading'] ?></td>
					<td data-title="Include">
						<label class="form-checkbox"><input type="checkbox" name="customer_serviceid[]" value="<?= $serviceid ?>" <?= in_array($serviceid, $serviceids) ? 'checked' : '' ?>></label>
					</td>
				</tr>
			<?php }
		} ?>
	</table>
</div>