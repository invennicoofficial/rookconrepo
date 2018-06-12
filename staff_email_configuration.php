<?php include_once('include.php'); ?>
<script type="text/javascript">
$(document).ready(function() {
});
</script>

<div id="no-more-tables">
	<div class="notice double-gap-bottom popover-examples">
	<div class="col-sm-1 notice-icon"><img src="img/info.png" class="wiggle-me" width="25px"></div>
	<div class="col-sm-16"><span class="notice-name">NOTE:</span>
		Configure which Staff email to use when sending out emails from the software.</div>
		<div class="clearfix"></div>
	</div>

	<?php $staff_email_field = get_config($dbc, 'staff_email_field'); ?>

	<form id="form1" name="form1" method="post"	action="admin_software_config.php?email_configuration" enctype="multipart/form-data" class="form-horizontal" role="form">
		<div class="form-group">
			<label class="col-sm-4 control-label">Staff Email Field:</label>
			<div class="col-sm-8">
				<select name="staff_email_field" class="chosen-select-deselect form-control">
					<option <?= empty($staff_email_field) || $staff_email_field == 'email_address' ? 'selected' : '' ?> value="email_address">Email Address (email_address)</option>
					<option <?= $staff_email_field == 'office_email' ? 'selected' : '' ?> value="office_email">Company Email Address (office_email)</option>
				</select>
			</div>
		</div>

		<br><br>
		<div class="form-group">
			<div class="col-sm-12">
				<button	type="submit" name="add_staff_email" value="add_staff_email" class="btn config-btn btn-lg pull-right">Submit</button>
			</div>
		</div>
	</form>
</div>