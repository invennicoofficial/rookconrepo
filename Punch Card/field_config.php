<?php /* Settings */
include ('../include.php');
checkAuthorised('punch_card');
error_reporting(0);
$time_select_mode = get_config($dbc, "time_clock_select_mode");
$time_clock_for = get_config($dbc, "time_clock_for");
?>
<script>
$(document).ready(function() {
	$('input').off('change').change(function() {
		$.ajax({
			url: '../ajax_all.php?fill=ajax_save_general',
			method: 'POST',
			data: {
				name: this.name,
				value: this.value
			}
		});
	});
});
</script>
</head>
<body>

<?php include ('../navigation.php'); ?>
<div class="container">
	<div class="row">
		<h1>Settings</h1>
		<a href="<?= $_SERVER['HTTP_REFERER'] ?>" class="btn brand-btn">Back to Dashboard</a>
		<div class="form-horizontal">
			<!--<div class="form-group">
				<label class="col-sm-4 control-label">Sign In Select:</label>
				<div class="col-sm-8">
					<label class="form-checkbox"><input type="radio" name="time_clock_select_mode" value="single" <?= $time_select_mode == 'multi' ? '' : 'checked' ?>> Single Select Only</label>
					<label class="form-checkbox"><input type="radio" name="time_clock_select_mode" value="multi" <?= $time_select_mode == 'multi' ? 'checked' : '' ?>> Select Multiple Staff</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Sign In To:</label>
				<div class="col-sm-8">
					<label class="form-checkbox"><input type="radio" name="time_clock_for" value="shop_work_order" <?= in_array($time_clock_for, ['tickets','time_sheet']) ? '' : 'checked' ?>> Shop Work Orders</label>
					<label class="form-checkbox"><input type="radio" name="time_clock_for" value="tickets" <?= $time_clock_for == 'tickets' ? 'checked' : '' ?>> <?= TICKET_TILE ?></label>
					<label class="form-checkbox"><input type="radio" name="time_clock_for" value="time_sheet" <?= $time_clock_for == 'time_sheet' ? 'checked' : '' ?>> Time Sheet Only</label>
				</div>
			</div>-->
		</div>
		<a href="<?= $_SERVER['HTTP_REFERER'] ?>" class="btn brand-btn">Back</a>
		<a href="<?= $_SERVER['HTTP_REFERER'] ?>" class="btn brand-btn pull-right">Save</a>
	</div>
</div>
<?php include('../footer.php'); ?>