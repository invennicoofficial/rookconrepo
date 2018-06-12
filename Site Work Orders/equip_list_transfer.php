<?php include('../include.php');
include('../phpsign/signature-to-image.php');
error_reporting(0);

if(!empty($_POST['workorderid'])) {
	foreach($_POST['equipmentid'] as $i => $equipment) {
		$row = $_POST['equipmentrow'][$i];
		$transfer = $_POST['transfer_'.$row];
		if($transfer != $_POST['assigned_'.$row]) {
			$contactid = $_SESSION['contactid'];
			$signature = $_POST['transfer_sign'];
			$history = "Equipment ID $equipment transferred from Equipment ID ".$_POST['assigned_'.$row]." to Equipment ID $transfer by ".get_contact($dbc, $contactid).".";
			mysqli_query($dbc, "INSERT INTO `equipment_history` (`equipmentid`, `notes`, `signature`) VALUES ('$equipment', '$history', '$signature')");
			mysqli_query($dbc, "UPDATE `equipment` SET `assign_to_equip`='$transfer' WHERE `equipmentid`='$equipment'");
		}
		if(isset($_POST['assigned_staff_'.$row])) {
			$transfer_staff = $_POST['transfer_staff_'.$row];
			if($transfer_staff != $_POST['assigned_staff_'.$row]) {
				$contactid = $_SESSION['contactid'];
				$signature = $_POST['transfer_sign'];
				if($_POST['assigned_staff_'.$row] == 0) {
					$history = "Equipment ID $equipment transferred to ".get_contact($dbc, $_POST['transfer_staff_'.$row])." by ".get_contact($dbc, $contactid).".";
				} else {
					$history = "Equipment ID $equipment transferred from ".get_contact($dbc, $_POST['assigned_staff_'.$row])." to ".get_contact($dbc, $_POST['transfer_staff_'.$row])." by ".get_contact($dbc, $contactid).".";
				}
				mysqli_query($dbc, "INSERT INTO `equipment_history` (`equipmentid`, `notes`, `signature`) VALUES ('$equipment', '$history', '$signature')");
				mysqli_query($dbc, "UPDATE `equipment` SET `assigned_staff`='$transfer_staff' WHERE `equipmentid`='$equipment'");
			}
		}
	}
	echo "<script> window.location.replace('site_work_orders.php?tab=schedule'); </script>";
}

include_once ('../navigation.php');
checkAuthorised('site_work_orders');

$equipment_id = ',';
$equip_list = mysqli_fetch_all(mysqli_query($dbc, "SELECT `equipmentid`, `category`, `unit_number` FROM `equipment` WHERE `deleted`=0"),MYSQLI_ASSOC);
if(!empty($_GET['workorderid'])) {
	$workorderid = filter_var($_GET['workorderid'],FILTER_SANITIZE_STRING);
	$work_order = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `site_work_orders` WHERE `workorderid`='$workorderid'"));
	$equipment_id = $work_order['equipment_id'];
}
$equipment_transfer_staff = get_config($dbc, "equipment_transfer_staff"); ?>
<script>
</script>

<div class="container">
  <div class="row">
		<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

		<h1>Work Order #<?= $workorderid ?> Transfer Equipment</h1>

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
					$equipment = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `equipment` WHERE `equipmentid`='$equipment'"));
					$staff_list = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `category` = 'Staff' AND `deleted` = 0 AND `status` = 1"),MYSQLI_ASSOC); ?>
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
									<label class="col-sm-4 text-center">Equipment</label>
									<label class="col-sm-<?= ($equipment_transfer_staff == 1 ? '4' : '8') ?> text-center">Transfer to Equipment</label>
									<?php if ($equipment_transfer_staff == 1) { ?>
										<label class="col-sm-4 text-center">Transfer to Staff</label>
									<?php } ?>
								</div>
								<div class="form-group">
									<input type="hidden" name="equipmentid[]" value="<?= $equipment['equipmentid'] ?>"><input type="hidden" name="equipmentrow[]" value="<?= $row ?>"><input type="hidden" name="assigned_<?= $row ?>" value="<?= $equipment['assign_to_equip'] ?>">
									<?php if ($equipment_transfer_staff == 1) { ?>
										<input type="hidden" name="assigned_staff_<?= $row ?>" value="<?= $equipment['assigned_staff'] ?>">
									<?php } ?>
									<div class="col-sm-4 text-center"><?= $equipment['category'].' Unit #'.$equipment['unit_number'] ?></div>
									<div class="col-sm-<?= ($equipment_transfer_staff == 1 ? '4' : '8') ?>"><label class="show-on-mob">Transfer to Equipment:</label>
										<select name="transfer_<?= $row ?>" class="chosen-select-deselect form-control"><option value='0'></option>
											<?php foreach($equip_list as $equip_row) {
												echo "<option ".($equip_row['equipmentid'] == $equipment['assign_to_equip'] ? 'selected' : '')." value='".$equip_row['equipmentid']."'>".$equip_row['category'].": Unit #".$equip_row['unit_number']."</option>";
											} ?>
										</select>
									</div>
									<?php if ($equipment_transfer_staff == 1) { ?>
										<div class="col-sm-4"><label class="show-on-mob">Transfer to Staff:</label>
											<select data-placeholder="Select a Staff" name="transfer_staff_<?= $row ?>" class="chosen-select-deselect form-control"><option value="0"></option>
												<?php foreach($staff_list as $staff_row) {
													echo "<option ".($staff_row['contactid'] == $equipment['assigned_staff'] ? 'selected' : '')." value='".$staff_row['contactid']."'>".get_contact($dbc, $staff_row['contactid'])."</option>";
												} ?>
											</select>
										</div>
									<?php } ?>
								</div>
								<?php $row++;
								$row = get_contained_equipment($dbc, $equipment['equipmentid'], $row, $equip_list, $staff_list, $equipment_transfer_staff); ?>
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
			<label class="col-sm-4">Sign off on Transfers:<br /><em>Sign here for any transfers that have been noted above.</em></label>
			<div class="col-sm-8">
				<label class="col-sm-12">I confirm that the above transfers are correct.</label>
				<?php $output_name = 'transfer_sign';
				include('../phpsign/sign_multiple.php'); ?>
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

<?php function get_contained_equipment($dbc, $id, $row, $equip_list, $staff_list, $equipment_transfer_staff) {
	$list = mysqli_query($dbc, "SELECT * FROM `equipment` WHERE `assign_to_equip`='$id'");
	if(mysqli_num_rows($list) > 0) {
		echo "<label>Unit #".mysqli_fetch_array(mysqli_query($dbc, "SELECT `unit_number` FROM `equipment` WHERE `equipmentid`='$id'"))['unit_number']." Contains:</label>";
	}
	while($equipment = mysqli_fetch_array($list)) { ?>
		<div class="form-group">
			<input type="hidden" name="equipmentid[]" value="<?= $equipment['equipmentid'] ?>"><input type="hidden" name="equipmentrow[]" value="<?= $row ?>"><input type="hidden" name="assigned_<?= $row ?>" value="<?= $equipment['assign_to_equip'] ?>">
			<?php if ($equipment_transfer_staff == 1) { ?>
				<input type="hidden" name="assigned_staff_<?= $row ?>" value="<?= $equipment['assigned_staff'] ?>">
			<?php } ?>
			<div class="col-sm-4 text-center"><?= $equipment['category'].' Unit #'.$equipment['unit_number'] ?></div>
			<div class="col-sm-<?= ($equipment_transfer_staff == 1 ? '4' : '8') ?>"><label class="show-on-mob">Transfer to Equipment:</label>
				<select name="transfer_<?= $row ?>" class="chosen-select-deselect form-control"><option value='0'></option>
					<?php foreach($equip_list as $equip_row) {
						echo "<option ".($equip_row['equipmentid'] == $equipment['assign_to_equip'] ? 'selected' : '')." value='".$equip_row['equipmentid']."'>".$equip_row['category'].": Unit #".$equip_row['unit_number']."</option>";
					} ?>
				</select>
			</div>
			<?php if ($equipment_transfer_staff == 1) { ?>
				<div class="col-sm-4"><label class="show-on-mob">Transfer to Staff:</label>
					<select data-placeholder="Select a Staff" name="transfer_staff_<?= $row ?>" class="chosen-select-deselect form-control"><option value="0"></option>
						<?php foreach($staff_list as $staff_row) {
							echo "<option ".($staff_row['contactid'] == $equipment['assigned_staff'] ? 'selected' : '')." value='".$staff_row['contactid']."'>".get_contact($dbc, $staff_row['contactid'])."</option>";
						} ?>
					</select>
				</div>
			<?php } ?>
		</div>
		<?php $row++;
		$row = get_contained_equipment($dbc, $equipment['equipmentid'], $row, $equip_list);
	}
	return $row;
}

include ('../footer.php'); ?>