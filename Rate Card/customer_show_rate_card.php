<script>
$(document).ready(function() {
	$('#accordion2 .btn').hide();
});
</script>
<?php
	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT `config_fields` FROM `field_config_ratecard`"));
	$base_field_config = ','.$get_field_config['config_fields'].',';

	$clientid = '';
	$rate_card_name = '';
	$package = '';
	$promotion = '';
	$services = '';
	$products = '';
	$sred = '';
	$client = '';
	$material = '';
	$inventory = '';
	$equipment = '';
	$equipment_category = '';
	$staff = '';
	$staff_position = '';
	$contractor = '';
	$customer = '';
	$expense = '';
	$vendor = '';
	$custom = '';
	$labour = '';
	$other = '';
	$disabled = '';

	if(!empty($_GET['ratecardid'])) {
		$ratecardid = $_GET['ratecardid'];
		$ratecard = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM `rate_card` WHERE `ratecardid`='$ratecardid'"));
		$clientid = $ratecard['clientid'];
		$rate_card_name = $ratecard['rate_card_name'];
		$package = $ratecard['package'];
		$promotion = $ratecard['promotion'];
		$services = $ratecard['services'];
		$products = $ratecard['products'];
		$sred = $ratecard['sred'];
		$client = $ratecard['client'];
		$material = $ratecard['material'];
		$inventory = $ratecard['inventory'];
		$equipment = $ratecard['equipment'];
		$equipment_category = $ratecard['equipment_category'];
		$staff = $ratecard['staff'];
		$staff_position = $ratecard['staff_position'];
		$contractor = $ratecard['contractor'];
		$customer = $ratecard['customer'];
		$expense = $ratecard['expense'];
		$vendor = $ratecard['vendor'];
		$custom = $ratecard['custom'];
		$labour = $ratecard['labour'];
		$other = $ratecard['other'];
		$disabled = 'disabled';
		?>
	<?php
	}
?>
<div class="panel-group" id="accordion2">

	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_abi" >Rate Card Info<span class="glyphicon glyphicon-minus"></span></a>
			</h4>
		</div>

		<div id="collapse_abi" class="panel-collapse collapse in">
			<div class="panel-body">

				<div class="form-group clearfix completion_date">
					<label for="first_name" class="col-sm-4 control-label text-right">Generate For:</label>
					<div class="col-sm-8">
						<select name="ratecardclientid" <?php echo $disabled; ?> data-placeholder="Choose an Option..." class="chosen-select-deselect form-control" width="380">
							<option value=''></option>
							<?php
							$query = mysqli_query($dbc,"SELECT `contactid`, `name` FROM `contacts` WHERE (`category`='Business') AND `deleted`=0 ORDER BY `name`");
							while($row = mysqli_fetch_array($query)) {
								if ($clientid == $row['contactid']) {
									$selected = 'selected="selected"';
								} else {
									$selected = '';
								}
								echo "<option ".$selected." value='". $row['contactid']."'>".decryptIt($row['name']).'</option>';
							}
							?>
						</select>
					</div>
				</div>

				<div class="form-group clearfix completion_date">
					<label for="first_name" class="col-sm-4 control-label text-right">Rate Card Name:</label>
					<div class="col-sm-8">
						<input name="rate_card_name" value="<?php echo $rate_card_name; ?>" type="text" class="form-control"></p>
					</div>
				</div>

			</div>
		</div>
	</div>

	<?php
	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM `field_config_ratecard`"));
	$value_config = ','.$get_field_config['config_fields'].',';
	?>

	<?php if (strpos($value_config, ','."Package".',') !== FALSE) { ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_package" >Packages<span class="glyphicon glyphicon-plus"></span></a>
			</h4>
		</div>

		<div id="collapse_package" class="panel-collapse collapse">
			<div class="panel-body">
				<?php
				include ('add_rate_card_package.php');
				?>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if (strpos($value_config, ','."Promotion".',') !== FALSE) { ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_promotion" >Promotion<span class="glyphicon glyphicon-plus"></span></a>
			</h4>
		</div>

		<div id="collapse_promotion" class="panel-collapse collapse">
			<div class="panel-body">
				<?php
				include ('add_rate_card_promotion.php');
				?>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if (strpos($value_config, ','."Custom".',') !== FALSE) { ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_cus" >Custom<span class="glyphicon glyphicon-plus"></span></a>
			</h4>
		</div>

		<div id="collapse_cus" class="panel-collapse collapse">
			<div class="panel-body">
				<?php
				include ('add_rate_card_custom.php');
				?>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if (strpos($value_config, ','."Material".',') !== FALSE) { ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Material" >Material<span class="glyphicon glyphicon-plus"></span></a>
			</h4>
		</div>

		<div id="collapse_Material" class="panel-collapse collapse">
			<div class="panel-body">
				<?php
				include ('add_rate_card_material.php');
				?>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if (strpos($value_config, ','."Services".',') !== FALSE) { ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_service" >Services<span class="glyphicon glyphicon-plus"></span></a>
			</h4>
		</div>

		<div id="collapse_service" class="panel-collapse collapse">
			<div class="panel-body">
				<?php
				include ('add_rate_card_services.php');
				?>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if (strpos($value_config, ','."Products".',') !== FALSE) { ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Products" >Products<span class="glyphicon glyphicon-plus"></span></a>
			</h4>
		</div>

		<div id="collapse_Products" class="panel-collapse collapse">
			<div class="panel-body">
				<?php
				include ('add_rate_card_products.php');
				?>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if (strpos($value_config, ','."SRED".',') !== FALSE) { ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_sred" >SR&ED<span class="glyphicon glyphicon-plus"></span></a>
			</h4>
		</div>

		<div id="collapse_sred" class="panel-collapse collapse">
			<div class="panel-body">
				<?php
				include ('add_rate_card_sred.php');
				?>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if (strpos($value_config, ','."Staff".',') !== FALSE) { ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_staff" >Staff<span class="glyphicon glyphicon-plus"></span></a>
			</h4>
		</div>

		<div id="collapse_staff" class="panel-collapse collapse">
			<div class="panel-body">
				<?php
				include ('add_rate_card_staff.php');
				?>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if (strpos($value_config, ','."Contractor".',') !== FALSE) { ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_contractor" >Contractor<span class="glyphicon glyphicon-plus"></span></a>
			</h4>
		</div>

		<div id="collapse_contractor" class="panel-collapse collapse">
			<div class="panel-body">
				<?php
				include ('add_rate_card_contractor.php');
				?>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if (strpos($value_config, ','."Clients".',') !== FALSE) { ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_clients" >Clients<span class="glyphicon glyphicon-plus"></span></a>
			</h4>
		</div>

		<div id="collapse_clients" class="panel-collapse collapse">
			<div class="panel-body">
			   <?php
				include ('add_rate_card_clients.php');
				?>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if (strpos($value_config, ','."Vendor Pricelist".',') !== FALSE) { ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_vendor" >Vendor Pricelist<span class="glyphicon glyphicon-plus"></span></a>
			</h4>
		</div>

		<div id="collapse_vendor" class="panel-collapse collapse">
			<div class="panel-body">
				<?php
				include ('add_rate_card_vendor.php');
				?>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if (strpos($value_config, ','."Customer".',') !== FALSE) { ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_customer" >Customer<span class="glyphicon glyphicon-plus"></span></a>
			</h4>
		</div>

		<div id="collapse_customer" class="panel-collapse collapse">
			<div class="panel-body">
			   <?php
				include ('add_rate_card_customer.php');
				?>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if (strpos($value_config, ','."Inventory".',') !== FALSE) { ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_inv" >Inventory<span class="glyphicon glyphicon-plus"></span></a>
			</h4>
		</div>

		<div id="collapse_inv" class="panel-collapse collapse">
			<div class="panel-body">
				<?php
				include ('add_rate_card_inventory.php');
				?>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if (strpos($value_config, ','."Equipment".',') !== FALSE) { ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_equipment" >Equipment<span class="glyphicon glyphicon-plus"></span></a>
			</h4>
		</div>

		<div id="collapse_equipment" class="panel-collapse collapse">
			<div class="panel-body">
				<?php
				include ('add_rate_card_equipment.php');
				?>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if (strpos($value_config, ','."Labour".',') !== FALSE) { ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Labour" >Labour<span class="glyphicon glyphicon-plus"></span></a>
			</h4>
		</div>

		<div id="collapse_Labour" class="panel-collapse collapse">
			<div class="panel-body">
				<?php
				include ('add_rate_card_labour.php');
				?>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if (strpos($value_config, ','."Equipment by Category".',') !== FALSE) { ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_eqcat" >Equipment by Category<span class="glyphicon glyphicon-plus"></span></a>
			</h4>
		</div>

		<div id="collapse_eqcat" class="panel-collapse collapse">
			<div class="panel-body">
				<?php
				include ('add_rate_card_equipment_category.php');
				?>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if (strpos($value_config, ','."Staff Position".',') !== FALSE) { ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_staffpos" >Staff Position<span class="glyphicon glyphicon-plus"></span></a>
			</h4>
		</div>

		<div id="collapse_staffpos" class="panel-collapse collapse">
			<div class="panel-body">
				<?php
				include ('add_rate_card_staff_position.php');
				?>
			</div>
		</div>
	</div>
	<?php } ?>
</div>