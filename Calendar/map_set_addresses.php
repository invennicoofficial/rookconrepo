<?php include('../include.php'); ?>
<div class="form-horizontal triple-padded">
	<div class="form-group">
		<label class="col-sm-4">Day Starting Address:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" name="origin" value="<?= $_GET['origin'] ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4">Day Ending Address:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" name="destination" value="<?= $_GET['destination'] ?>">
		</div>
	</div>
	<button class="btn brand-btn confirm_btn pull-right">Confirm Addresses</button>
	<button class="btn brand-btn pull-right" onclick="window.location.reload();">Cancel Sorting</button>
</div>