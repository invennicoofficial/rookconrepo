<?php include_once('../include.php');
checkAuthorised('estimate');
if(!isset($estimate)) {
	$estimateid = filter_var($estimateid,FILTER_SANITIZE_STRING);
	$estimate = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `estimate` WHERE `estimateid`='$estimateid'"));
} ?>
<div class="form-horizontal col-sm-12" data-tab-name="dates">
	<h3>Deliverables</h3>
	<div class="form-group">
		<label class="col-sm-4">Date <?= ESTIMATE_TILE ?> Created:</label>
		<div class="col-sm-8">
			<input type="text" readonly class="form-control" value="<?= $estimate['created_date'] ?>">
		</div>
	</div>
	<?php if(in_array('Project Start',$config)) { ?>
		<div class="form-group">
			<label class="col-sm-4">Estimated Project Start Date:</label>
			<div class="col-sm-8">
				<input type="text" name="start_date" class="form-control datepicker" value="<?= $estimate['start_date'] ?>" data-table="estimate" data-id-field="estimateid" data-id="<?= $estimateid ?>">
			</div>
		</div>
	<?php } ?>
	<?php if(in_array('Expiry',$config)) { ?>
		<div class="form-group">
			<label class="col-sm-4">Estimate Expiration Date:</label>
			<div class="col-sm-8">
				<input type="text" name="expiry_date" class="form-control datepicker" value="<?= $estimate['expiry_date'] ?>" data-table="estimate" data-id-field="estimateid" data-id="<?= $estimateid ?>">
			</div>
		</div>
	<?php } ?>
    <hr />
</div>
<?php if(basename($_SERVER['SCRIPT_FILENAME']) == 'edit_dates.php') { ?>
	<div style="display:none;"><?php include('../footer.php'); ?></div>
<?php } ?>