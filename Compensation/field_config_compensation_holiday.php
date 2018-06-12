<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('goals_compensation');
error_reporting(0);

if (isset($_POST['submit'])) {
    //Base Pay

    //$stat_holiday = implode(',',$_POST['stat_holiday']);
    //$stat_holiday = filter_var($stat_holiday,FILTER_SANITIZE_STRING);
    //
    //$get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='stat_holiday'"));
    //if($get_config['configid'] > 0) {
    //    $query_update_employee = "UPDATE `general_configuration` SET value = '$stat_holiday' WHERE name='stat_holiday'";
    //    $result_update_employee = mysqli_query($dbc, $query_update_employee);
    //} else {
    //    $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('stat_holiday', '$stat_holiday')";
    //    $result_insert_config = mysqli_query($dbc, $query_insert_config);
    //}
	// Save Holidays
	foreach($_POST['holiday_date'] as $row => $date) {
		if($_POST['holiday_id'][$row] > 0 || ($date != '' && $date != '0000-00-00')) {
			$holidays_id = $_POST['holidays_id'][$row];
			$name = $_POST['holiday_name'][$row];
			$paid = $_POST['holiday_paid'][$row];
			$deleted = $_POST['holiday_archived'][$row];
			if($holidays_id > 0) {
				$query = "UPDATE `holidays` SET `name`='$name', `date`='$date', `paid`='$paid', `deleted`='$deleted' WHERE `holidays_id`='$holidays_id'";
			} else {
				$query = "INSERT INTO `holidays` (`name`, `date`, `paid`, `deleted`) VALUES ('$name', '$date', '$paid', '$deleted')";
			}
			mysqli_query($dbc, $query);
		}
	}

	echo '<script type="text/javascript"> alert("Field Configuration Successfully Added."); window.location.replace(""); </script>';
}
?>
<script>
$(document).ready(function() {
	$('.toggle-switch').click(function() {
		$(this).find('img').toggle();
		$(this).find('input').val($(this).find('input').val() == 1 ? 0 : 1);
	});
});
$(document).on('change', 'select[name="defined_holidays"]', function() { use_defined_holiday(this); });
function use_defined_holiday(select) {
	if(select.value == 'CUSTOM') {
		$(select).closest('div').find('.select2').hide();
		$(select).closest('div').find('input').show().focus();
	} else {
		var row = $(select).closest('.form-group');
		var choice = $(select).find('option:selected');
		$.ajax({
			url: '../ajax_dates.php?action=next_occurrence',
			method: 'POST',
			data: { day: choice.data('day'), month: choice.data('month'), week: choice.data('week'), weekday: choice.data('weekday'), name: choice.val() },
			success: function(response) {
				row.find('[name="holiday_date[]"]').val(response);
			}
		});
		row.find('[name="holiday_name[]"]').val(choice.val());
		if(choice.data('paid') != row.find('[name="holiday_paid[]"]').val()) {
			row.find('.toggle-switch img').toggle();
			row.find('.toggle-switch input').val(choice.data('paid'));
		}
	}
}
</script>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

    <a href='field_config_compensation.php'><button type="button" class="btn brand-btn mobile-block " >Pay Fields</button></a>
    <a href='field_config_compensation_holiday.php'><button type="button" class="btn brand-btn mobile-block active_tab" >Statutory Holidays</button></a>

	<div class="form-group hide-titles-mob text-center">
		<label class="col-sm-5">Holiday Name</label>
		<label class="col-sm-3">Statutory Date</label>
		<label class="col-sm-2">Paid</label>
	</div>
	<?php $holiday_list = mysqli_query($dbc, "SELECT * FROM (SELECT `holidays_id`, `name`, `date`, `paid` FROM `holidays` WHERE `deleted`=0 UNION SELECT 'NEW', '', '', 1) holidays ORDER BY `date`='' DESC, `date` DESC");
	$defined_holidays = [];
	include('../Calendar/defined_holidays.php');
	while($holiday = mysqli_fetch_array($holiday_list)) { ?>
		<div class="form-group">
			<input type="hidden" name="holidays_id[]" value="<?= $holiday['holidays_id'] ?>">
			<div class="col-sm-5 col-xs-12">
				<label class="show-on-mob">Holiday Name:</label>
				<?php if($holiday['name'] == '' && $holiday['date'] == '') { ?>
					<select class="chosen-select-deselect" name="defined_holidays"><option></option>
						<option value="CUSTOM">Custom Holiday</option>
						<?php foreach($defined_holidays as $defined) { ?>
							<option data-day="<?= $defined['day'] ?>" data-week="<?= $defined['week'] ?>" data-weekday="<?=$defined['weekday'] ?>" data-month="<?= $defined['month'] ?>" data-paid="<?= $defined['paid'] ?>" value="<?= $defined['name'] ?>"><?= $defined['label'] ?></option>
						<?php } ?>
					</select>
					<input type="text" name="holiday_name[]" value="<?= $holiday['name'] ?>" class="form-control" style="display:none;">
				<?php } else { ?>
					<input type="text" name="holiday_name[]" value="<?= $holiday['name'] ?>" class="form-control">
				<?php } ?>
			</div>
			<div class="col-sm-3 col-xs-12">
				<label class="show-on-mob">Statutory Date:</label>
				<input type="text" name="holiday_date[]" value="<?= $holiday['date'] ?>" class="datepicker form-control">
			</div>
			<div class="col-sm-2 col-xs-12">
				<label class="show-on-mob">Paid:</label>
				<div class="toggle-switch form-group"><input type="hidden" name="holiday_paid[]" value="<?= $holiday['paid'] ?>">
					<img src="<?= WEBSITE_URL ?>/img/icons/switch-6.png" style="height: 2em; <?= $holiday['paid'] > 0 ? 'display: none;' : '' ?>">
					<img src="<?= WEBSITE_URL ?>/img/icons/switch-7.png" style="height: 2em; <?= $holiday['paid'] > 0 ? '' : 'display: none;' ?>"> Holiday is Paid
				</div>
			</div>
			<div class="col-sm-1 col-xs-12">
				<input type="hidden" name="holiday_archived[]" value="<?= $holiday['deleted'] ?>">
				<button class="btn brand-btn" onclick="$(this).closest('div').find('[name^=holiday_archived]').val(1); $(this).closest('.form-group').hide(); return false;">Archive</button>
			</div>
		</div>
	<?php } ?>
    <!--<?php
    $stat_holiday = explode(',',get_config($dbc, 'stat_holiday'));
    ?>

      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Stat 1:</label>
        <div class="col-sm-8">
          <input name="stat_holiday[]" value="<?php echo $stat_holiday[0]; ?>" type="text" class="datepicker">
        </div>
      </div>

      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Stat 2:</label>
        <div class="col-sm-8">
          <input name="stat_holiday[]" value="<?php echo $stat_holiday[1]; ?>" type="text" class="datepicker">
        </div>
      </div>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Stat 3:</label>
        <div class="col-sm-8">
          <input name="stat_holiday[]" value="<?php echo $stat_holiday[2]; ?>" type="text" class="datepicker">
        </div>
      </div>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Stat 4:</label>
        <div class="col-sm-8">
          <input name="stat_holiday[]" value="<?php echo $stat_holiday[3]; ?>" type="text" class="datepicker">
        </div>
      </div>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Stat 5:</label>
        <div class="col-sm-8">
          <input name="stat_holiday[]" value="<?php echo $stat_holiday[4]; ?>" type="text" class="datepicker">
        </div>
      </div>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Stat 6:</label>
        <div class="col-sm-8">
          <input name="stat_holiday[]" value="<?php echo $stat_holiday[5]; ?>" type="text" class="datepicker">
        </div>
      </div>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Stat 7:</label>
        <div class="col-sm-8">
          <input name="stat_holiday[]" value="<?php echo $stat_holiday[6]; ?>" type="text" class="datepicker">
        </div>
      </div>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Stat 8:</label>
        <div class="col-sm-8">
          <input name="stat_holiday[]" value="<?php echo $stat_holiday[7]; ?>" type="text" class="datepicker">
        </div>
      </div>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Stat 9:</label>
        <div class="col-sm-8">
          <input name="stat_holiday[]" value="<?php echo $stat_holiday[8]; ?>" type="text" class="datepicker">
        </div>
      </div>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Stat 10:</label>
        <div class="col-sm-8">
          <input name="stat_holiday[]" value="<?php echo $stat_holiday[9]; ?>" type="text" class="datepicker">
        </div>
      </div>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Stat 11:</label>
        <div class="col-sm-8">
          <input name="stat_holiday[]" value="<?php echo $stat_holiday[10]; ?>" type="text" class="datepicker">
        </div>
      </div>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Stat 12:</label>
        <div class="col-sm-8">
          <input name="stat_holiday[]" value="<?php echo $stat_holiday[11]; ?>" type="text" class="datepicker">
        </div>
      </div>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Stat 13:</label>
        <div class="col-sm-8">
          <input name="stat_holiday[]" value="<?php echo $stat_holiday[12]; ?>" type="text" class="datepicker">
        </div>
      </div>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Stat 14:</label>
        <div class="col-sm-8">
          <input name="stat_holiday[]" value="<?php echo $stat_holiday[13]; ?>" type="text" class="datepicker">
        </div>
      </div>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Stat 15:</label>
        <div class="col-sm-8">
          <input name="stat_holiday[]" value="<?php echo $stat_holiday[14]; ?>" type="text" class="datepicker">
        </div>
      </div>



      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Stat 16:</label>
        <div class="col-sm-8">
          <input name="stat_holiday[]" value="<?php echo $stat_holiday[15]; ?>" type="text" class="datepicker">
        </div>
      </div>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Stat 17:</label>
        <div class="col-sm-8">
          <input name="stat_holiday[]" value="<?php echo $stat_holiday[16]; ?>" type="text" class="datepicker">
        </div>
      </div>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Stat 18:</label>
        <div class="col-sm-8">
          <input name="stat_holiday[]" value="<?php echo $stat_holiday[17]; ?>" type="text" class="datepicker">
        </div>
      </div>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Stat 19:</label>
        <div class="col-sm-8">
          <input name="stat_holiday[]" value="<?php echo $stat_holiday[18]; ?>" type="text" class="datepicker">
        </div>
      </div>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Stat 20:</label>
        <div class="col-sm-8">
          <input name="stat_holiday[]" value="<?php echo $stat_holiday[19]; ?>" type="text" class="datepicker">
        </div>
      </div>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Stat 21:</label>
        <div class="col-sm-8">
          <input name="stat_holiday[]" value="<?php echo $stat_holiday[20]; ?>" type="text" class="datepicker">
        </div>
      </div>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Stat 22:</label>
        <div class="col-sm-8">
          <input name="stat_holiday[]" value="<?php echo $stat_holiday[21]; ?>" type="text" class="datepicker">
        </div>
      </div>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Stat 23:</label>
        <div class="col-sm-8">
          <input name="stat_holiday[]" value="<?php echo $stat_holiday[22]; ?>" type="text" class="datepicker">
        </div>
      </div>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Stat 24:</label>
        <div class="col-sm-8">
          <input name="stat_holiday[]" value="<?php echo $stat_holiday[23]; ?>" type="text" class="datepicker">
        </div>
      </div>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Stat 25:</label>
        <div class="col-sm-8">
          <input name="stat_holiday[]" value="<?php echo $stat_holiday[24]; ?>" type="text" class="datepicker">
        </div>
      </div>-->

    <div class="form-group">
        <div class="col-sm-4 clearfix">
            <a href="compensation.php" class="btn brand-btn pull-right">Back</a>
        </div>
        <div class="col-sm-8">
            <button	type="submit" name="submit"	value="Submit" class="btn brand-btn btn-lg	pull-right">Submit</button>
        </div>
    </div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>