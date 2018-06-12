<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('time_tracking');
error_reporting(0);

if (isset($_POST['submit'])) {
	if($_POST['submit'] == 'tracking') {
		$time_tracking = implode(',',$_POST['time_tracking']);
		$time_tracking_dashboard = implode(',',$_POST['time_tracking_dashboard']);

		$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(fieldconfigid) AS fieldconfigid FROM field_config"));
		if($get_field_config['fieldconfigid'] > 0) {
			$query_update_employee = "UPDATE `field_config` SET time_tracking = '$time_tracking', time_tracking_dashboard = '$time_tracking_dashboard' WHERE `fieldconfigid` = 1";
			$result_update_employee = mysqli_query($dbc, $query_update_employee);
		} else {
			$query_insert_config = "INSERT INTO `field_config` (`time_tracking`, `time_tracking_dashboard`) VALUES ('$time_tracking', '$time_tracking_dashboard')";
			$result_insert_config = mysqli_query($dbc, $query_insert_config);
		}

		echo '<script type="text/javascript"> window.location.replace("time_tracking.php?tab=tracking"); </script>';
	}
	else if($_POST['submit'] == 'tab_config') {
		$tab_config = implode(',',$_POST['tab_config']);
		mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`) SELECT 'time_tracking_tabs' FROM (SELECT COUNT(*) `rows` FROM general_configuration WHERE `name`='time_tracking_tabs') CONFIG WHERE `rows`=0");
		mysqli_query($dbc, "UPDATE `general_configuration` SET `value`='$tab_config' WHERE `name`='time_tracking_tabs'");
		echo '<script type="text/javascript"> window.location.replace("time_tracking.php"); </script>';
	}
}
?>
</head>
<body>

<?php include ('../navigation.php');
$tab_config = ','.get_config($dbc,'time_tracking_tabs').',';

if(empty($_GET['tab'])) {
	$current_tab = 'tab_config';
} else {
	$current_tab = $_GET['tab'];
}

switch($current_tab) {
	case 'shop_time_sheets':
		$current_tab_name = 'Shop Time Sheets';
		break;
	case 'tracking':
		$current_tab_name = 'Time Tracking';
		break;
	case 'tab_config':
	default:
		$current_tab_name = 'Select Active Tabs';
		break;
}
?>

<div class="container">
<div class="row">
<h1>Time Tracking</h1>
<div class="pad-left gap-top double-gap-bottom"><a href="time_tracking.php" class="btn config-btn">Back to Dashboard</a></div>
<!--<a href="#" class="btn config-btn" onclick="history.go(-1);return false;">Back</a>-->

<div class="tab-container mobile-100-container">
	<a href="?tab=tab_config"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'tab_config' ? 'active_tab' : ''); ?>">Active Tabs</button></a>
	<?php if (strpos(','.$tab_config.',',',tracking,') !== FALSE && check_subtab_persmission($dbc, 'time_tracking', ROLE, 'tracking') === TRUE) { ?>
		<a href="?tab=tracking"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'tracking' ? 'active_tab' : ''); ?>">Time Tracking</button></a>
	<?php } ?>
	<?php if (strpos(','.$tab_config.',',',shop_time_sheets,') !== FALSE && check_subtab_persmission($dbc, 'time_tracking', ROLE, 'shop_time_sheets') === TRUE) { ?>
		<a href="?tab=shop_time_sheets"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'shop_time_sheets' ? 'active_tab' : ''); ?>">Shop Time Sheets</button></a>
	<?php } ?>
</div>

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<div class="panel-group" id="accordion2">
	<?php if('tracking' == $current_tab): ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field" >
						Choose Fields for Time Tracking<span class="glyphicon glyphicon-plus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_field" class="panel-collapse collapse">
				<div class="panel-body" id="no-more-tables">
					<?php
					$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT time_tracking FROM field_config"));
					$value_config = ','.$get_field_config['time_tracking'].',';
					?>

					<table border='2' cellpadding='10' class='table'>
						<tr>
							<td>
								<input type="checkbox" <?php if (strpos($value_config, ','."Business".',') !== FALSE) { echo " checked"; } ?> value="Business" style="height: 20px; width: 20px;" name="time_tracking[]">&nbsp;&nbsp;<?= BUSINESS_CAT ?>
							</td>
							<td>
								<input type="checkbox" <?php if (strpos($value_config, ','."Contact".',') !== FALSE) { echo " checked"; } ?> value="Contact" style="height: 20px; width: 20px;" name="time_tracking[]">&nbsp;&nbsp;Contact
							</td>
							<td>
								<input type="checkbox" <?php if (strpos($value_config, ','."Location".',') !== FALSE) { echo " checked"; } ?> value="Location" style="height: 20px; width: 20px;" name="time_tracking[]">&nbsp;&nbsp;Location
							</td>
							<td>
								<input type="checkbox" <?php if (strpos($value_config, ','."Job number".',') !== FALSE) { echo " checked"; } ?> value="Job number" style="height: 20px; width: 20px;" name="time_tracking[]">&nbsp;&nbsp;Job #
							</td>

							<td>
								<input type="checkbox" <?php if (strpos($value_config, ','."AFE number".',') !== FALSE) { echo " checked"; } ?> value="AFE number" style="height: 20px; width: 20px;" name="time_tracking[]">&nbsp;&nbsp;AFE #
							</td>
							<td>
								<input type="checkbox" <?php if (strpos($value_config, ','."Work performed".',') !== FALSE) { echo " checked"; } ?> value="Work performed" style="height: 20px; width: 20px;" name="time_tracking[]">&nbsp;&nbsp;Work Performed
							</td>
							<td>
								<input type="checkbox" <?php if (strpos($value_config, ','."Short description".',') !== FALSE) { echo " checked"; } ?> value="Short description" style="height: 20px; width: 20px;" name="time_tracking[]">&nbsp;&nbsp;Short Description
							</td>
							<td>
								<input type="checkbox" <?php if (strpos($value_config, ','."Job description".',') !== FALSE) { echo " checked"; } ?> value="Job description" style="height: 20px; width: 20px;" name="time_tracking[]">&nbsp;&nbsp;Job Description
							</td>

						</tr>

						<tr>
							<td>
								<input type="checkbox" <?php if (strpos($value_config, ','."Labour".',') !== FALSE) { echo " checked"; } ?> value="Labour" style="height: 20px; width: 20px;" name="time_tracking[]">&nbsp;&nbsp;Labour
							</td>
							<td>
								<input type="checkbox" <?php if (strpos($value_config, ','."Position".',') !== FALSE) { echo " checked"; } ?> value="Position" style="height: 20px; width: 20px;" name="time_tracking[]">&nbsp;&nbsp;Position
							</td>
							<td>
							   <input type="checkbox" <?php if (strpos($value_config, ','."REG Hours".',') !== FALSE) { echo " checked"; } ?> value="REG Hours" style="height: 20px; width: 20px;" name="time_tracking[]">&nbsp;&nbsp;REG Hours
							</td>
							<td>
								<input type="checkbox" <?php if (strpos($value_config, ','."REG Rate".',') !== FALSE) { echo " checked"; } ?> value="REG Rate" style="height: 20px; width: 20px;" name="time_tracking[]">&nbsp;&nbsp;REG Rate
							</td>
							<td>
								<input type="checkbox" <?php if (strpos($value_config, ','."OT Hours".',') !== FALSE) { echo " checked"; } ?> value="OT Hours" style="height: 20px; width: 20px;" name="time_tracking[]">&nbsp;&nbsp;OT Hours
							</td>

							<td>
								<input type="checkbox" <?php if (strpos($value_config, ','."OT Rate".',') !== FALSE) { echo " checked"; } ?> value="OT Rate" style="height: 20px; width: 20px;" name="time_tracking[]">&nbsp;&nbsp;OT Rate
							</td>
						</tr>

					</table>
				</div>
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_dashboard" >
						Choose Fields for Time Tracking Dashboard<span class="glyphicon glyphicon-plus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_dashboard" class="panel-collapse collapse">
				<div class="panel-body" id="no-more-tables">
					<?php
					$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT time_tracking_dashboard FROM field_config"));
					$value_config = ','.$get_field_config['time_tracking_dashboard'].',';
					?>

					<table border='2' cellpadding='10' class='table'>
						<tr>
							<td>
								<input type="checkbox" <?php if (strpos($value_config, ','."Business".',') !== FALSE) { echo " checked"; } ?> value="Business" style="height: 20px; width: 20px;" name="time_tracking_dashboard[]">&nbsp;&nbsp;<?= BUSINESS_CAT ?>
							</td>
							<td>
								<input type="checkbox" <?php if (strpos($value_config, ','."Contact".',') !== FALSE) { echo " checked"; } ?> value="Contact" style="height: 20px; width: 20px;" name="time_tracking_dashboard[]">&nbsp;&nbsp;Contact
							</td>
							<td>
								<input type="checkbox" <?php if (strpos($value_config, ','."Location".',') !== FALSE) { echo " checked"; } ?> value="Location" style="height: 20px; width: 20px;" name="time_tracking_dashboard[]">&nbsp;&nbsp;Location
							</td>
							<td>
								<input type="checkbox" <?php if (strpos($value_config, ','."Job number".',') !== FALSE) { echo " checked"; } ?> value="Job number" style="height: 20px; width: 20px;" name="time_tracking_dashboard[]">&nbsp;&nbsp;Job #
							</td>

							<td>
								<input type="checkbox" <?php if (strpos($value_config, ','."AFE number".',') !== FALSE) { echo " checked"; } ?> value="AFE number" style="height: 20px; width: 20px;" name="time_tracking_dashboard[]">&nbsp;&nbsp;AFE #
							</td>
							<td>
								<input type="checkbox" <?php if (strpos($value_config, ','."Work performed".',') !== FALSE) { echo " checked"; } ?> value="Work performed" style="height: 20px; width: 20px;" name="time_tracking_dashboard[]">&nbsp;&nbsp;Work Performed
							</td>
							<td>
								<input type="checkbox" <?php if (strpos($value_config, ','."Short description".',') !== FALSE) { echo " checked"; } ?> value="Short description" style="height: 20px; width: 20px;" name="time_tracking_dashboard[]">&nbsp;&nbsp;Short Description
							</td>
							<td>
								<input type="checkbox" <?php if (strpos($value_config, ','."Job description".',') !== FALSE) { echo " checked"; } ?> value="Job description" style="height: 20px; width: 20px;" name="time_tracking_dashboard[]">&nbsp;&nbsp;Job Description
							</td>

						</tr>

						<tr>
							<td>
								<input type="checkbox" <?php if (strpos($value_config, ','."Labour".',') !== FALSE) { echo " checked"; } ?> value="Labour" style="height: 20px; width: 20px;" name="time_tracking_dashboard[]">&nbsp;&nbsp;Labour
							</td>
						<!--
							<td>
								<input type="checkbox" <?php if (strpos($value_config, ','."Position".',') !== FALSE) { echo " checked"; } ?> value="Position" style="height: 20px; width: 20px;" name="time_tracking_dashboard[]">&nbsp;&nbsp;Position
							</td>
							<td>
							   <input type="checkbox" <?php if (strpos($value_config, ','."REG Hours".',') !== FALSE) { echo " checked"; } ?> value="REG Hours" style="height: 20px; width: 20px;" name="time_tracking_dashboard[]">&nbsp;&nbsp;REG Hours
							</td>
							<td>
								<input type="checkbox" <?php if (strpos($value_config, ','."REG Rate".',') !== FALSE) { echo " checked"; } ?> value="REG Rate" style="height: 20px; width: 20px;" name="time_tracking_dashboard[]">&nbsp;&nbsp;REG Rate
							</td>
							<td>
								<input type="checkbox" <?php if (strpos($value_config, ','."OT Hours".',') !== FALSE) { echo " checked"; } ?> value="OT Hours" style="height: 20px; width: 20px;" name="time_tracking_dashboard[]">&nbsp;&nbsp;OT Hours
							</td>

							<td>
								<input type="checkbox" <?php if (strpos($value_config, ','."OT Rate".',') !== FALSE) { echo " checked"; } ?> value="OT Rate" style="height: 20px; width: 20px;" name="time_tracking_dashboard[]">&nbsp;&nbsp;OT Rate
							</td>
						-->
						</tr>

					</table>
				</div>
			</div>
		</div>
	<?php elseif('shop_time_sheets' == $current_tab): ?>
		<h3>Configuration for this tab comes from the Shop Time Sheets tile.</h3>
	<?php elseif('tab_config' == $current_tab): ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_dashboard" >
						Select Active Tabs<span class="glyphicon glyphicon-plus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_dashboard" class="panel-collapse collapse">
				<div class="panel-body" id="no-more-tables">
					<table border='2' cellpadding='10' class='table'>
						<tr>
							<td>
								<label><input type="checkbox" <?php if (strpos($tab_config, ','."tracking".',') !== FALSE) { echo " checked"; } ?> value="tracking" style="height: 20px; width: 20px;" name="tab_config[]">&nbsp;&nbsp;Time Tracking</label>
							</td>
							<td>
								<label><input type="checkbox" <?php if (strpos($tab_config, ','."shop_time_sheets".',') !== FALSE) { echo " checked"; } ?> value="shop_time_sheets" style="height: 20px; width: 20px;" name="tab_config[]">&nbsp;&nbsp;Shop Time Sheets</label>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>

<div class="form-group">
    <div class="col-sm-6">
        <a href="time_tracking.php" class="btn config-btn btn-lg">Back</a>
		<!--<a href="#" class="btn config-btn btn-lg pull-right" onclick="history.go(-1);return false;">Back</a>-->
	</div>
	<div class="col-sm-6">
        <button	type="submit" name="submit"	value="<?php echo $current_tab; ?>" class="btn config-btn btn-lg	pull-right">Submit</button>
    </div>
</div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>