<?php include('../include.php');
error_reporting(0);

if(!empty($_POST['workorderid'])) {
	foreach($_POST['equipmentid'] as $i => $equipment) {
		$row = $_POST['equipmentrow'][$i];
		$repair_needed = $_POST['repair_needed_'.$row];
		if($repair_needed == 'Yes') {
			$comments = $_POST['repair_comment_'.$row];
			$history = "Equipment ID $equipment marked as Repair Needed by ".get_contact($dbc, $contactid).": $comments.";
			mysqli_query($dbc, "INSERT INTO `equipment_history` (`equipmentid`, `notes`) VALUES ('$equipment', '$history')");
			mysqli_query($dbc, "UPDATE `equipment` SET `repair_needed`='1' WHERE `equipmentid`='$equipment'");
			mysqli_query($dbc, "INSERT INTO `equipment_work_orders` (`equipmentid`, `staffid`, `date`, `service_description`, `status`) VALUES ('$equipment', '$contactid', '".date('Y-m-d')."', '$comments', 'Pending')");
		}
	}
	echo "<script> window.location.replace('site_work_orders.php?tab=schedule'); </script>";
}

include_once ('../navigation.php');
checkAuthorised('site_work_orders');

$equipment_id = ',';
if(!empty($_GET['workorderid'])) {
	$workorderid = filter_var($_GET['workorderid'],FILTER_SANITIZE_STRING);
	$work_order = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `site_work_orders` WHERE `workorderid`='$workorderid'"));
	$equipment_id = $work_order['equipment_id'];
} ?>
<script>
</script>

<div class="container">
  <div class="row">
		<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

		<h1>Work Order #<?= $workorderid ?> Assigned Equipment</h1>

		<div class="form-group">
			<div class="col-sm-6">
				<a href="site_work_orders.php?tab=schedule&site=<?= $siteid ?>" class="btn brand-btn">Back to Dashboard</a>
			</div>
			<div class="col-sm-6">
				<button type="submit" name="submit" value="sign_out" class="btn brand-btn pull-right">Sign Out</button>
			</div>
			<div class="clearfix"></div>
		</div>

		<!--Equipment Checklist-->
		<div class="work_order">
			<div class="panel-group" id="accordion2">
				<?php $row = 0;
				foreach(explode(',', $equipment_id) as $i => $equipment) {
					$equipment = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `equipment` WHERE `equipmentid`='$equipment'")); ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_equip_<?= $i ?>" >
									Equipment: <?= $equipment['category'].' Unit #'.$equipment['unit_number'] ?><span class="glyphicon glyphicon-plus"></span>
								</a>
							</h4>
						</div>

						<div id="collapse_equip_<?= $i ?>" class="panel-collapse collapse">
							<div class="panel-body">
								<div class="form-group hide-titles-mob">
									<label class="col-sm-3 text-center">Equipment</label>
									<label class="col-sm-3 text-center">Repair Requested</label>
									<label class="col-sm-6 text-center">Comments</label>
								</div>
								<div class="form-group">
									<input type="hidden" name="equipmentid[]" value="<?= $equipment['equipmentid'] ?>"><input type="hidden" name="equipmentrow[]" value="<?= $row ?>">
									<div class="col-sm-3 text-center"><?= $equipment['category'].' Unit #'.$equipment['unit_number'] ?></div>
									<div class="col-sm-3 text-center"><label class="show-on-mob">Repair Requested:</label>
										<label class="form-checkbox small"><input type="radio" name="repair_needed_<?= $row ?>" value="Yes">Yes</label>
										<label class="form-checkbox small"><input type="radio" name="repair_needed_<?= $row ?>" checked value="No">No</label></div>
									<div class="col-sm-6"><label class="show-on-mob">Repair Comments:</label><input type="text" name="repair_comment_<?= $row ?>" value="" class="form-control"></div>
								</div>
								<?php $row++;
								$row = get_contained_equipment($dbc, $equipment['equipmentid'], $row); ?>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>

			<div class="form-group double-gap-top">
				<p><span class="brand-color"><em>Required Fields *</em></span></p>
			</div>
		</div>
		
		<div class="form-group">
			<div class="col-sm-6">
				<a href="site_work_orders.php?tab=schedule&site=<?= $siteid ?>" class="btn brand-btn btn-lg">Back</a>
			</div>
			<div class="col-sm-6">
				<input type="hidden" name="workorderid" value="<?= (empty($workorderid) ? 'NEW' : $workorderid) ?>">
				<button	type="submit" name="submit"	value="<?= (empty($workorderid) ? 'NEW' : $workorderid) ?>" class="btn brand-btn btn-lg pull-right">Submit</button>
			</div>
			<div class="clearfix"></div>
		</div>

		</form>

	</div>
  </div>

<?php function get_contained_equipment($dbc, $id, $row) {
	$list = mysqli_query($dbc, "SELECT * FROM `equipment` WHERE `assign_to_equip`='$id'");
	if(mysqli_num_rows($list) > 0) {
		echo "<label>Unit #".mysqli_fetch_array(mysqli_query($dbc, "SELECT `unit_number` FROM `equipment` WHERE `equipmentid`='$id'"))['unit_number']." Contains:</label>";
	}
	while($equipment = mysqli_fetch_array($list)) { ?>
		<div class="form-group">
			<input type="hidden" name="equipmentid[]" value="<?= $equipment['equipmentid'] ?>"><input type="hidden" name="equipmentrow[]" value="<?= $row ?>">
			<div class="col-sm-3 text-center"><?= $equipment['category'].' Unit #'.$equipment['unit_number'] ?></div>
			<div class="col-sm-3 text-center"><label class="show-on-mob">Repair Requested:</label>
				<label class="form-checkbox small"><input type="radio" name="repair_needed_<?= $row ?>" value="Yes">Yes</label>
				<label class="form-checkbox small"><input type="radio" name="repair_needed_<?= $row ?>" checked value="No">No</label></div>
			<div class="col-sm-6"><label class="show-on-mob">Repair Comments:</label><input type="text" name="repair_comment_<?= $row ?>" value="" class="form-control"></div>
		</div>
		<?php $row++;
		$row = get_contained_equipment($dbc, $equipment['equipmentid'], $row);
	}
	return $row;
}

include ('../footer.php'); ?>