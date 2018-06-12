<?php error_reporting(0);
include_once('../include.php');
$folder = FOLDER_NAME;
if(isset($_GET['folder'])) {
	$folder = filter_var($_GET['folder'], FILTER_SANITIZE_STRING);
	$workorderid = filter_var($_GET['workorderid'],FILTER_SANITIZE_STRING);
} ?>
<script>
$(document).ready(function() {
	$('.toggleSwitch').click(function() {
		$(this).find('span').toggle();
		$(this).find('.toggle').val($(this).find('.toggle').val() == 1 ? 0 : 1).change();
	});
});
function toggleAll(button) {
	$(button).closest('.panel-body').find('.toggle[value=0]').closest('.toggleSwitch').click();
}
</script>
<h4>Check In
<?php $checkins = mysqli_query($dbc, "SELECT `id`, `src_table`, `arrived`, `item_id` FROM `workorder_attached` WHERE `src_table` NOT IN ('Wait List') AND `deleted`=0 AND `workorderid`='$workorderid' AND `tile_name`='".$folder."' ORDER BY `src_table` != 'Staff', `src_table`");
if(mysqli_num_rows($checkins) == 0) { ?>
	</h4><h3>No records found attached to this workorder.</h3>
<?php } else { ?>
	<button class="btn brand-btn pull-right" onclick="toggleAll(this); return false;">Check In All</button></h4>
	<div class="clearfix"></div>
<?php }
while($checkin = mysqli_fetch_assoc($checkins)) {
	if($checkin['src_table'] == 'Member') { ?>
		<div class="form-group">
			<label class="col-sm-4 control-label"><?= get_contact($dbc, $checkin['item_id']) ?>:</label>
			<div class="col-sm-8 toggleSwitch">
				<input type="hidden" name="checkin_id[]" value="<?= $checkin['item_id'] ?>">
				<input type="hidden" name="arrived" data-table="workorder_attached" data-id="<?= $checkin['id'] ?>" data-id-field="id" value="<?= $checkin['arrived'] ?>" value="<?= $checkin['arrived'] ?>" class="toggle">
				<span style="<?= $checkin['arrived'] > 0 ? 'display: none;' : '' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-6.png" class="inline-img"> Not Checked In</span>
				<span style="<?= $checkin['arrived'] > 0 ? '' : 'display: none;' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-7.png" class="inline-img"> Checked In</span>
			</div>
			<div class="col-sm-8 pull-right">
				<div class="panel-group" id="checkin<?= $checkin['id'] ?>">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add any necessary project information. This will group your workorders under a certain project, under a certain customer."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
								<a data-toggle="collapse" data-parent="#checkin<?= $checkin['id'] ?>" href="#collapse_medication">
									Medications<span class="glyphicon glyphicon-plus"></span>
								</a>
							</h4>
						</div>

						<div id="collapse_medication" class="panel-collapse collapse in">
							<div class="panel-body">
								<div class="hide-titles-mob">
									<label class="col-sm-4">Medication</label>
									<label class="col-sm-4">Dosage</label>
									<label class="col-sm-3">Time</label>
								</div>
								<?php $medications = mysqli_query($dbc, "SELECT * FROM `workorder_attached` WHERE `src_table`='medication' AND `line_id`='{$checkin['id']}' AND `deleted`=0");
								$medication = mysqli_fetch_assoc($medications);
								do { ?>
									<div class="multi-block">
										<div class="col-sm-4">
											<label class="show-on-mob">Medication:</label>
											<input type="text" name="position" data-table="workorder_attached" data-id="<?= $medication['id'] ?>" data-id-field="id" data-type="medication" data-type-field="src_table" data-attach="<?= $checkin['id'] ?>" data-attach-field="line_id" class="form-control" value="<?= $medication['position'] ?>">
										</div>
										<div class="col-sm-4">
											<label class="show-on-mob">Dosage:</label>
											<input type="text" name="description" data-table="workorder_attached" data-id="<?= $medication['id'] ?>" data-id-field="id" data-type="medication" data-type-field="src_table" data-attach="<?= $checkin['id'] ?>" data-attach-field="line_id" class="form-control" value="<?= $medication['description'] ?>">
										</div>
										<div class="col-sm-3">
											<label class="show-on-mob">Time:</label>
											<input type="text" name="shift_start" data-table="workorder_attached" data-id="<?= $medication['id'] ?>" data-id-field="id" data-type="medication" data-type-field="src_table" data-attach="<?= $checkin['id'] ?>" data-attach-field="line_id" class="form-control" value="<?= $medication['shift_start'] ?>">
										</div>
										<div class="col-sm-1">
											<img class="inline-img pull-right" onclick="addMulti(this);" src="../img/icons/ROOK-add-icon.png">
											<img class="inline-img pull-right" onclick="remMulti(this);" src="../img/remove.png">
										</div>
									</div>
								<?php } while($medication = mysqli_fetch_assoc($medications)); ?>
							</div>
						</div>
						<div class="panel-heading">
							<h4 class="panel-title">
								<span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add any necessary project information. This will group your workorders under a certain project, under a certain customer."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
								<a data-toggle="collapse" data-parent="#checkin<?= $checkin['id'] ?>" href="#collapse_guardian">
									Guardian<span class="glyphicon glyphicon-plus"></span>
								</a>
							</h4>
						</div>

						<div id="collapse_guardian" class="panel-collapse collapse in">
							<div class="panel-body">
								<div class="hide-titles-mob">
									<label class="col-sm-4">Name</label>
									<label class="col-sm-4">Phone Number</label>
									<label class="col-sm-3">Confirmed</label>
								</div>
								<?php $guardians = mysqli_query($dbc, "SELECT * FROM `workorder_attached` WHERE `src_table`='guardian' AND `line_id`='{$checkin['id']}' AND `deleted`=0");
								$guardian = mysqli_fetch_assoc($guardians);
								do { ?>
									<div class="multi-block">
										<div class="col-sm-4">
											<label class="show-on-mob">Name:</label>
											<input type="text" name="description" data-table="workorder_attached" data-id="<?= $guardian['id'] ?>" data-id-field="id" data-type="guardian" data-type-field="src_table" data-attach="<?= $checkin['id'] ?>" data-attach-field="line_id" class="form-control" value="<?= $guardian['description'] ?>">
										</div>
										<div class="col-sm-4">
											<label class="show-on-mob">Phone Number:</label>
											<input type="text" name="contact_info" data-table="workorder_attached" data-id="<?= $guardian['id'] ?>" data-id-field="id" data-type="guardian" data-type-field="src_table" data-attach="<?= $checkin['id'] ?>" data-attach-field="line_id" class="form-control" value="<?= $guardian['contact_info'] ?>">
										</div>
										<div class="col-sm-3">
											<label class="show-on-mob">Confirmed:</label>
											<div class="toggleSwitch">
												<input type="hidden" name="arrived" data-table="workorder_attached" data-id="<?= $guardian['id'] ?>" data-id-field="id" data-type="guardian" data-type-field="src_table" data-attach="<?= $checkin['id'] ?>" data-attach-field="line_id" value="<?= $guardian['arrived'] ?>" class="toggle">
												<span style="<?= $guardian['arrived'] > 0 ? 'display: none;' : '' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-6.png" class="inline-img"> Unconfirmed</span>
												<span style="<?= $guardian['arrived'] > 0 ? '' : 'display: none;' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-7.png" class="inline-img"> Confirmed</span>
											</div>
										</div>
										<div class="col-sm-1">
											<img class="inline-img pull-right" onclick="addMulti(this);" src="../img/icons/ROOK-add-icon.png">
											<img class="inline-img pull-right" onclick="remMulti(this);" src="../img/remove.png">
										</div>
									</div>
								<?php } while($guardian = mysqli_fetch_assoc($guardians)); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } else if(in_array($checkin['src_table'],['Staff','Client'])) { ?>
		<div class="form-group">
			<label class="col-sm-4 control-label"><?= get_contact($dbc, $checkin['item_id']) ?>:</label>
			<div class="col-sm-8 toggleSwitch">
				<input type="hidden" name="checkin_id[]" value="<?= $checkin['item_id'] ?>">
				<input type="hidden" name="arrived" data-table="workorder_attached" data-id="<?= $checkin['id'] ?>" data-id-field="id" value="<?= $checkin['arrived'] ?>" class="toggle">
				<span style="<?= $checkin['arrived'] > 0 ? 'display: none;' : '' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-6.png" class="inline-img"> Not Checked In</span>
				<span style="<?= $checkin['arrived'] > 0 ? '' : 'display: none;' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-7.png" class="inline-img"> Checked In</span>
			</div>
		</div>
	<?php } else if(in_array($checkin['src_table'],['material'])) {
		$material = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `material` WHERE `materialid`='{$checkin['item_id']}'")); ?>
		<div class="form-group">
			<label class="col-sm-4 control-label"><?= $material['category'].': '.$material['sub_category'].' ',$material['name'] ?>:</label>
			<div class="col-sm-8 toggleSwitch">
				<input type="hidden" name="checkin_id[]" value="<?= $checkin['item_id'] ?>">
				<input type="hidden" name="arrived" data-table="workorder_attached" data-id="<?= $checkin['id'] ?>" data-id-field="id" value="<?= $checkin['arrived'] ?>" class="toggle">
				<span style="<?= $checkin['arrived'] > 0 ? 'display: none;' : '' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-6.png" class="inline-img"> Not Checked In</span>
				<span style="<?= $checkin['arrived'] > 0 ? '' : 'display: none;' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-7.png" class="inline-img"> Checked In</span>
			</div>
		</div>
	<?php } else if(in_array($checkin['src_table'],['equipment'])) {
		$equipment = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `equipment` WHERE `equipmentid`='{$checkin['item_id']}'")); ?>
		<div class="form-group">
			<label class="col-sm-4 control-label"><?= $equipment['category'].': '.$equipment['make'].' ',$equipment['model'].' '.$equipment['label'].' '.$equipment['unit_number'] ?>:</label>
			<div class="col-sm-8 toggleSwitch">
				<input type="hidden" name="checkin_id[]" value="<?= $checkin['item_id'] ?>">
				<input type="hidden" name="arrived" data-table="workorder_attached" data-id="<?= $checkin['id'] ?>" data-id-field="id" value="<?= $checkin['arrived'] ?>" class="toggle">
				<span style="<?= $checkin['arrived'] > 0 ? 'display: none;' : '' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-6.png" class="inline-img"> Not Checked In</span>
				<span style="<?= $checkin['arrived'] > 0 ? '' : 'display: none;' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-7.png" class="inline-img"> Checked In</span>
			</div>
		</div>
	<?php }
} ?>