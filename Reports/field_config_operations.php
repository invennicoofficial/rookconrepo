<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('report');
error_reporting(0);

if (isset($_POST['submit'])) {
	//Report Fields - Operations Reports
	$report_fields = implode(',', $_POST['report_operation_fields']);
	mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`) SELECT 'report_operation_fields' FROM (SELECT COUNT(*) rows FROM `general_configuration` WHERE `name`='report_operation_fields') num WHERE num.rows=0");
	mysqli_query($dbc, "UPDATE `general_configuration` SET `value`='$report_fields' WHERE `name`='report_operation_fields'");

    echo '<script type="text/javascript"> window.location.replace("?tab='.$_GET['tab'].'"); </script>';
}
?>
<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
    <input type="hidden" name="contactid" value="<?php echo $_GET['contactid'] ?>" />

	<?php $report_fields = explode(',', get_config($dbc, 'report_operation_fields')); ?>
	<h4><?= TICKET_NOUN ?> Activity Report per Customer</h4>
    <div class="form-group">
		<label class="form-checkbox"><input type="checkbox" <?= (in_array('ticket_activity_site',$report_fields) ? 'checked' : '') ?> name="report_operation_fields[]" value="ticket_activity_site">Show Site Information</label>
		<label class="form-checkbox"><input type="checkbox" <?= (in_array('ticket_activity_rate_card',$report_fields) ? 'checked' : '') ?> name="report_operation_fields[]" value="ticket_activity_rate_card">Show Rate Card Name</label>
		<label class="form-checkbox"><input type="checkbox" <?= (in_array('ticket_activity_staff_group',$report_fields) ? 'checked' : '') ?> name="report_operation_fields[]" value="ticket_activity_staff_group">Group by <?= TICKET_NOUN ?> on Report</label>
		<label class="form-checkbox"><input type="checkbox" <?= (in_array('ticket_activity_staff_count',$report_fields) ? 'checked' : '') ?> name="report_operation_fields[]" value="ticket_activity_staff_count">Show Staff Count</label>
		<label class="form-checkbox"><input type="checkbox" <?= (in_array('ticket_activity_notes',$report_fields) ? 'checked' : '') ?> name="report_operation_fields[]" value="ticket_activity_notes">Show Notes</label>
		<label class="form-checkbox"><input type="checkbox" <?= (in_array('ticket_activity_created_dates',$report_fields) ? 'checked' : '') ?> name="report_operation_fields[]" value="ticket_activity_created_dates">Filter by <?= TICKET_NOUN ?> Created Date</label>
    </div>

    <div class="form-group pull-right">
        <a href="report_tiles.php" class="btn brand-btn">Back</a>
        <button type="submit" name="submit" value="Submit" class="btn brand-btn">Submit</button>
    </div>

</form>