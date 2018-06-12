<?php
/*
Dashboard
*/
include ('../include.php');
error_reporting(0);

$from_url = 'workorder.php';
if(!empty($_GET['from_url'])) {
	$from_url = urldecode($_GET['from_url']);
}
if (isset($_POST['submit'])) {
    //Work Order Fields
    $workorder  = implode(',',$_POST['workorder']);
    $workorder .= ( strpos($workorder, 'PI Business') === false && strpos($workorder, 'PI Name') === false ) ? ',PI Business' : '';
    $workorder_dashboard = implode(',',$_POST['workorder_dashboard']);
	$colours = implode(',',$_POST['flag_colours']);
	$flag_names = implode('#*#',$_POST['flag_name']);

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(fieldconfigid) AS fieldconfigid FROM field_config"));
    if($get_field_config['fieldconfigid'] > 0) {
        $query_update_employee = "UPDATE `field_config` SET workorder = '$workorder', workorder_dashboard = '$workorder_dashboard' WHERE `fieldconfigid` = 1";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config` (`workorder`, `workorder_dashboard`) VALUES ('$workorder', '$workorder_dashboard')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //Work Order Fields

    //Task Status
    $task_status = filter_var($_POST['task_status'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='task_status'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$task_status' WHERE name='task_status'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('task_status', '$task_status')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //Task Status

    //Work Order Status
    $workorder_status = filter_var($_POST['workorder_status'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='workorder_status'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$workorder_status' WHERE name='workorder_status'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('workorder_status', '$workorder_status')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //Work Order Status

    //Work Order Colours
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='workorder_colour_flags'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$colours' WHERE name='workorder_colour_flags'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('workorder_colour_flags', '$colours')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //Work Order Colours

    //Work Order Colour Labels
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='workorder_colour_flag_names'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$flag_names' WHERE name='workorder_colour_flag_names'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('workorder_colour_flag_names', '$flag_names')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //Work Order Colour Labels

    echo '<script type="text/javascript"> window.location.replace("'.$from_url.'"); </script>';

}
?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<h1>Work Orders</h1>
<div class="pad-left gap-top double-gap-bottom"><a href="<?php echo $from_url; ?>" class="btn config-btn">Back to Dashboard</a></div>
<!--<a href="#" class="btn config-btn" onclick="history.go(-1);return false;">Back</a>-->

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<?php
$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT workorder, `workorder_dashboard` FROM field_config"));
$value_config = ','.$get_field_config['workorder'].',';
$db_config = ','.$get_field_config['workorder_dashboard'].',';
$flag_colours = get_config($dbc, 'workorder_colour_flags');
$flag_names = explode('#*#', get_config($dbc, 'workorder_colour_flag_names')); 
?>

    <div class="panel-group" id="accordion2">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add or remove the fields."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_db" >
                        Choose Fields for Dashboard<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_db" class="panel-collapse collapse">
                <div class="panel-body">
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($db_config, ','."Services".',') !== false) { echo " checked"; } ?> value="Services" style="height: 20px; width: 20px;" name="workorder_dashboard[]">&nbsp;&nbsp;Services</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($db_config, ','."Status".',') !== false) { echo " checked"; } ?> value="Status" style="height: 20px; width: 20px;" name="workorder_dashboard[]">&nbsp;&nbsp;Status</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($db_config, ','."Staff".',') !== false) { echo " checked"; } ?> value="Staff" style="height: 20px; width: 20px;" name="workorder_dashboard[]">&nbsp;&nbsp;Staff</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($db_config, ','."To Do Date".',') !== false) { echo " checked"; } ?> value="To Do Date" style="height: 20px; width: 20px;" name="workorder_dashboard[]">&nbsp;&nbsp;To Do Date</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($db_config, ','."Deliverable Date".',') !== false) { echo " checked"; } ?> value="Deliverable Date" style="height: 20px; width: 20px;" name="workorder_dashboard[]">&nbsp;&nbsp;Deliverable Date</label>
					<div class="form-group">
						<div class="col-sm-4">
							<!--<a href="" class="btn brand-btn">Back</a>-->
							<a href="#" class="btn brand-btn" onclick="history.go(-1);return false;">Back</a>
						</div>
						<div class="col-sm-8">
							<span class="popover-examples list-inline pull-right" style="margin-left:-6em;"><a data-toggle="tooltip" data-placement="top" title="" data-original-title="The entire form will submit and close if this submit button is pressed.">
								<button type="submit" name="submit" value="submit" title="The entire form will submit and close if this submit button is pressed." onclick="return confirm('The entire form will submit and close if this submit button is pressed.')" class="btn brand-btn">Submit</button></a></span>
							<button type="submit" name="submit" value="submit" title="The entire form will submit and close if this submit button is pressed." onclick="return confirm('The entire form will submit and close if this submit button is pressed.')" class="btn brand-btn pull-right">Submit</button>
						</div>
					</div>
                </div>
				
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add or remove the fields."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field" >
                        Choose Fields for Work Orders<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_field" class="panel-collapse collapse">
                <div class="panel-body">
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Information".',') !== false) { echo " checked"; } ?> value="Information" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Information</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Details".',') !== false) { echo " checked"; } ?> value="Details" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Details</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Path & Milestone".',') !== false) { echo " checked"; } ?> value="Path & Milestone" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Path & Milestone</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Fees".',') !== false) { echo " checked"; } ?> value="Fees" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Fees</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Location".',') !== false) { echo " checked"; } ?> value="Location" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Location</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Staff".',') !== false) { echo " checked"; } ?> value="Staff" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Staff</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Members".',') !== false) { echo " checked"; } ?> value="Members" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Members</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Clients".',') !== false) { echo " checked"; } ?> value="Clients" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Clients</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Wait List".',') !== false) { echo " checked"; } ?> value="Wait List" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Wait List</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Check In".',') !== false) { echo " checked"; } ?> value="Check In" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Check In</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Medication".',') !== false) { echo " checked"; } ?> value="Medication" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Medication Administration</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Deliverables".',') !== false) { echo " checked"; } ?> value="Deliverables" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Deliverables</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Work Order Details".',') !== false) { echo " checked"; } ?> value="Work Order Details" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Work Order Details</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Services".',') !== false) { echo " checked"; } ?> value="Services" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Services</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Equipment".',') !== false) { echo " checked"; } ?> value="Equipment" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Equipment</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Checklist".',') !== false) { echo " checked"; } ?> value="Checklist" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Checklist</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Emergency".',') !== false) { echo " checked"; } ?> value="Emergency" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Emergency Plan</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Safety".',') !== false) { echo " checked"; } ?> value="Safety" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Safety</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Timer".',') !== false) { echo " checked"; } ?> value="Timer" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Timer</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Materials".',') !== false) { echo " checked"; } ?> value="Materials" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Materials</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Purchase Orders".',') !== false) { echo " checked"; } ?> value="Purchase Orders" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Purchase Orders</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Delivery".',') !== false) { echo " checked"; } ?> value="Delivery" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Delivery Details</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Documents".',') !== false) { echo " checked"; } ?> value="Documents" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Documents</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Check Out".',') !== false) { echo " checked"; } ?> value="Check Out" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Check Out</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Addendum".',') !== false) { echo " checked"; } ?> value="Addendum" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Addendum</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Client Log".',') !== false) { echo " checked"; } ?> value="Client Log" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Client Log</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Debrief".',') !== false) { echo " checked"; } ?> value="Debrief" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Debrief</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Notes".',') !== false) { echo " checked"; } ?> value="Notes" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Notes</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Summary".',') !== false) { echo " checked"; } ?> value="Summary" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Summary</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Checklist Items".',') !== false) { echo " checked"; } ?> value="Checklist Items" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Checklist Items</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Tasks".',') !== false) { echo " checked"; } ?> value="Tasks" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Tasks</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Complete".',') !== false) { echo " checked"; } ?> value="Complete" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Complete (Sign Off)</label>
                    
                    <h4 class="double-gap-top">Project Information</h4>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."PI Business".',') !== false || ( strpos($value_config, ','."PI Business".',') === false && strpos($value_config, ','."PI Name".',') === false ) ) { echo " checked"; } ?> value="PI Business" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Business</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."PI Name".',') !== false) { echo " checked"; } ?> value="PI Name" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Name</label>
                    
                    <h4 class="double-gap-top">Project Details</h4>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Detail Date".',') !== false) { echo " checked"; } ?> value="Detail Date" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Date</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Detail Staff Times".',') !== false) { echo " checked"; } ?> value="Detail Staff Times" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Staff Times</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Detail Member Times".',') !== false) { echo " checked"; } ?> value="Detail Member Times" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Member Times</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Detail Notes".',') !== false) { echo " checked"; } ?> value="Detail Notes" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Notes</label>
                    
                    <h4 class="double-gap-top">Staff Information</h4>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Staff Position".',') !== false) { echo " checked"; } ?> value="Staff Position" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Position</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Staff Start".',') !== false) { echo " checked"; } ?> value="Staff Start" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Start Time</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Staff Hours".',') !== false) { echo " checked"; } ?> value="Staff Hours" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Hours</label>
                    
                    <h4 class="double-gap-top">Delivery Details</h4>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Delivery Pickup".',') !== false) { echo " checked"; } ?> value="Delivery Pickup" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Pickup Location</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Delivery Pickup Date".',') !== false) { echo " checked"; } ?> value="Delivery Pickup Date" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Pickup Date</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Delivery Pickup Order".',') !== false) { echo " checked"; } ?> value="Delivery Pickup Order" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Pickup Order#</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Delivery Dropoff".',') !== false) { echo " checked"; } ?> value="Delivery Dropoff" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Drop Off Location</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Delivery Dropoff Date".',') !== false) { echo " checked"; } ?> value="Delivery Dropoff Date" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Drop Off Date</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Delivery Dropoff Order".',') !== false) { echo " checked"; } ?> value="Delivery Dropoff Order" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Drop Off Order#</label>
                    
                    <h4 class="double-gap-top">Summary Options</h4>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Summary Times".',') !== false) { echo " checked"; } ?> value="Summary Times" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Show Times on Summary</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Time Tracking".',') !== false) { echo " checked"; } ?> value="Time Tracking" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Time Tracking</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Time Tasks".',') !== false) { echo " checked"; } ?> value="Time Tasks" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Tasks for Time</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Summary Notes".',') !== false) { echo " checked"; } ?> value="Summary Notes" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Notes</label>
					<label class="form-checkbox"><input type="checkbox" <?php if (strpos($value_config, ','."Member Log Notes".',') !== false) { echo " checked"; } ?> value="Member Log Notes" style="height: 20px; width: 20px;" name="workorder[]">&nbsp;&nbsp;Member Log Notes</label>
					<div class="form-group">
						<div class="col-sm-4">
							<!--<a href="" class="btn brand-btn">Back</a>-->
							<a href="#" class="btn brand-btn" onclick="history.go(-1);return false;">Back</a>
						</div>
						<div class="col-sm-8">
							<span class="popover-examples list-inline pull-right" style="margin-left:-6em;"><a data-toggle="tooltip" data-placement="top" title="" data-original-title="The entire form will submit and close if this submit button is pressed.">
								<button type="submit" name="submit" value="submit" title="The entire form will submit and close if this submit button is pressed." onclick="return confirm('The entire form will submit and close if this submit button is pressed.')" class="btn brand-btn">Submit</button></a></span>
							<button type="submit" name="submit" value="submit" title="The entire form will submit and close if this submit button is pressed." onclick="return confirm('The entire form will submit and close if this submit button is pressed.')" class="btn brand-btn pull-right">Submit</button>
						</div>
					</div>
                </div>
				
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add or remove the status/heading you want attached to every work order."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field2" >
                        Work Order Status/Heading<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_field2" class="panel-collapse collapse">
                <div class="panel-body">

                    <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Add Headings separated by a comma:</label>
                        <div class="col-sm-8">
                          <input name="workorder_status" type="text" value="<?php echo get_config($dbc, 'workorder_status'); ?>" class="form-control"/>
                        </div>
                    </div>

					<div class="form-group">
						<div class="col-sm-4">
							<!--<a href="" class="btn brand-btn">Back</a>-->
							<a href="#" class="btn brand-btn" onclick="history.go(-1);return false;">Back</a>
						</div>
						<div class="col-sm-8">
							<span class="popover-examples list-inline pull-right" style="margin-left:-6em;"><a data-toggle="tooltip" data-placement="top" title="" data-original-title="The entire form will submit and close if this submit button is pressed.">
								<button type="submit" name="submit" title="The entire form will submit and close if this submit button is pressed." value="submit" class="btn brand-btn">Submit</button></a></span>
							<button type="submit" name="submit" title="The entire form will submit and close if this submit button is pressed." value="submit" class="btn brand-btn pull-right">Submit</button>
						</div>
					</div>	

                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add or remove the task headings you would like attached to every work order."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field3" >
                        Task Status/Heading<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_field3" class="panel-collapse collapse">
                <div class="panel-body">

                    <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Add Headings separated by a comma:</label>
                        <div class="col-sm-8">
                          <input name="task_status" type="text" value="<?php echo get_config($dbc, 'task_status'); ?>" class="form-control"/>
                        </div>
                    </div>

					<div class="form-group">
						<div class="col-sm-4">
							<!--<a href="" class="btn brand-btn">Back</a>-->
							<a href="#" class="btn brand-btn" onclick="history.go(-1);return false;">Back</a>
						</div>
						<div class="col-sm-8">
							<span class="popover-examples list-inline pull-right" style="margin-left:-6em;"><a data-toggle="tooltip" data-placement="top" title="" data-original-title="The entire form will submit and close if this submit button is pressed.">
								<button type="submit" name="submit" value="submit" class="btn brand-btn">Submit</button></a></span>
							<button type="submit" name="submit" value="submit" title="The entire form will submit and close if this submit button is pressed." class="btn brand-btn pull-right">Submit</button>
						</div>
					</div>

                </div>
            </div>
        </div>
		<div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to choose and label the colours that will be used for Quick Flags."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_actions" >
                        Quick Flag Colours<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_actions" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="form-group">
						<label for="file[]" class="col-sm-4 control-label">Flag Colours to Use<span class="popover-examples list-inline">&nbsp;
						<a  data-toggle="tooltip" data-placement="top" title="The selected colours will be cycled through when you flag an entry."><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a>
						</span>:</label>
						<div class="col-sm-8">
							<label class="col-sm-4"><input type="checkbox" <?php echo (strpos($flag_colours, 'FB0D0D') !== false ? 'checked' : ''); ?> value="FB0D0D" name="flag_colours[]" style="height:1.5em; width: 1.5em;">
							<div style="border: 1px solid black; border-radius: 0.25em; background-color: #FB0D0D; display: inline-block; height: 1.5em; margin: 0 0.25em; min-width: 4em; width: calc(100% - 2.5em);"></div></label>
							<div class="col-sm-8"><input type="text" name="flag_name[]" value="<?php echo $flag_names[0]; ?>" class="form-control"></div>
							<label class="col-sm-4"><input type="checkbox" <?php echo (strpos($flag_colours, 'B97A57') !== false ? 'checked' : ''); ?> value="B97A57" name="flag_colours[]" style="height:1.5em; width: 1.5em;">
							<div style="border: 1px solid black; border-radius: 0.25em; background-color: #B97A57; display: inline-block; height: 1.5em; margin: 0 0.25em; min-width: 4em; width: calc(100% - 2.5em);"></div></label>
							<div class="col-sm-8"><input type="text" name="flag_name[]" value="<?php echo $flag_names[1]; ?>" class="form-control"></div>
							<label class="col-sm-4"><input type="checkbox" <?php echo (strpos($flag_colours, 'FFAEC9') !== false ? 'checked' : ''); ?> value="FFAEC9" name="flag_colours[]" style="height:1.5em; width: 1.5em;">
							<div style="border: 1px solid black; border-radius: 0.25em; background-color: #FFAEC9; display: inline-block; height: 1.5em; margin: 0 0.25em; min-width: 4em; width: calc(100% - 2.5em);"></div></label>
							<div class="col-sm-8"><input type="text" name="flag_name[]" value="<?php echo $flag_names[2]; ?>" class="form-control"></div>
							<label class="col-sm-4"><input type="checkbox" <?php echo (strpos($flag_colours, 'FFC90E') !== false ? 'checked' : ''); ?> value="FFC90E" name="flag_colours[]" style="height:1.5em; width: 1.5em;">
							<div style="border: 1px solid black; border-radius: 0.25em; background-color: #FFC90E; display: inline-block; height: 1.5em; margin: 0 0.25em; min-width: 4em; width: calc(100% - 2.5em);"></div></label>
							<div class="col-sm-8"><input type="text" name="flag_name[]" value="<?php echo $flag_names[3]; ?>" class="form-control"></div>
							<label class="col-sm-4"><input type="checkbox" <?php echo (strpos($flag_colours, 'EFE4B0') !== false ? 'checked' : ''); ?> value="EFE4B0" name="flag_colours[]" style="height:1.5em; width: 1.5em;">
							<div style="border: 1px solid black; border-radius: 0.25em; background-color: #EFE4B0; display: inline-block; height: 1.5em; margin: 0 0.25em; min-width: 4em; width: calc(100% - 2.5em);"></div></label>
							<div class="col-sm-8"><input type="text" name="flag_name[]" value="<?php echo $flag_names[4]; ?>" class="form-control"></div>
							<label class="col-sm-4"><input type="checkbox" <?php echo (strpos($flag_colours, 'B5E61D') !== false ? 'checked' : ''); ?> value="B5E61D" name="flag_colours[]" style="height:1.5em; width: 1.5em;">
							<div style="border: 1px solid black; border-radius: 0.25em; background-color: #B5E61D; display: inline-block; height: 1.5em; margin: 0 0.25em; min-width: 4em; width: calc(100% - 2.5em);"></div></label>
							<div class="col-sm-8"><input type="text" name="flag_name[]" value="<?php echo $flag_names[5]; ?>" class="form-control"></div>
							<label class="col-sm-4"><input type="checkbox" <?php echo (strpos($flag_colours, '99D9EA') !== false ? 'checked' : ''); ?> value="99D9EA" name="flag_colours[]" style="height:1.5em; width: 1.5em;">
							<div style="border: 1px solid black; border-radius: 0.25em; background-color: #99D9EA; display: inline-block; height: 1.5em; margin: 0 0.25em; min-width: 4em; width: calc(100% - 2.5em);"></div></label>
							<div class="col-sm-8"><input type="text" name="flag_name[]" value="<?php echo $flag_names[6]; ?>" class="form-control"></div>
							<label class="col-sm-4"><input type="checkbox" <?php echo (strpos($flag_colours, '7092BE') !== false ? 'checked' : ''); ?> value="7092BE" name="flag_colours[]" style="height:1.5em; width: 1.5em;">
							<div style="border: 1px solid black; border-radius: 0.25em; background-color: #7092BE; display: inline-block; height: 1.5em; margin: 0 0.25em; min-width: 4em; width: calc(100% - 2.5em);"></div></label>
							<div class="col-sm-8"><input type="text" name="flag_name[]" value="<?php echo $flag_names[7]; ?>" class="form-control"></div>
							<label class="col-sm-4"><input type="checkbox" <?php echo (strpos($flag_colours, 'C8BFE7') !== false ? 'checked' : ''); ?> value="C8BFE7" name="flag_colours[]" style="height:1.5em; width: 1.5em;">
							<div style="border: 1px solid black; border-radius: 0.25em; background-color: #C8BFE7; display: inline-block; height: 1.5em; margin: 0 0.25em; min-width: 4em; width: calc(100% - 2.5em);"></div></label>
							<div class="col-sm-8"><input type="text" name="flag_name[]" value="<?php echo $flag_names[8]; ?>" class="form-control"></div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-4">
							<!--<a href="" class="btn brand-btn">Back</a>-->
							<a href="#" class="btn brand-btn" onclick="history.go(-1);return false;">Back</a>
						</div>
						<div class="col-sm-8">
							<span class="popover-examples list-inline pull-right" style="margin-left:-6em;"><a data-toggle="tooltip" data-placement="top" title="" data-original-title="The entire form will submit and close if this submit button is pressed.">
								<button type="submit" name="submit" value="submit" title="The entire form will submit and close if this submit button is pressed."  class="btn brand-btn">Submit</button></a></span>
							<button type="submit" name="submit" value="submit" title="The entire form will submit and close if this submit button is pressed."  class="btn brand-btn pull-right">Submit</button>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>


<?php
$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT workorder_dashboard FROM field_config"));
$value_config = ','.$get_field_config['workorder_dashboard'].',';
?>
<!--
<h2>Choose Fields for Custom Dashboard</h2>
<table border='2' cellpadding='10' class='table'>
    <tr>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Service Type".',') !== false) { echo " checked"; } ?> value="Service Type" style="height: 20px; width: 20px;" name="custom_dashboard[]">&nbsp;&nbsp;Service Type
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Category".',') !== false) { echo " checked"; } ?> value="Category" style="height: 20px; width: 20px;" name="custom_dashboard[]">&nbsp;&nbsp;Category
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Heading".',') !== false) { echo " checked"; } ?> value="Heading" style="height: 20px; width: 20px;" name="custom_dashboard[]">&nbsp;&nbsp;Heading
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Cost".',') !== false) { echo " checked"; } ?> value="Cost" style="height: 20px; width: 20px;" name="custom_dashboard[]">&nbsp;&nbsp;Cost
        </td>

        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Description".',') !== false) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="custom_dashboard[]">&nbsp;&nbsp;Description
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Quote Description".',') !== false) { echo " checked"; } ?> value="Quote Description" style="height: 20px; width: 20px;" name="custom_dashboard[]">&nbsp;&nbsp;Quote Description
        </td>

    </tr>

    <tr>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Final Retail Price".',') !== false) { echo " checked"; } ?> value="Final Retail Price" style="height: 20px; width: 20px;" name="custom_dashboard[]">&nbsp;&nbsp;Final Retail Price
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Admin Price".',') !== false) { echo " checked"; } ?> value="Admin Price" style="height: 20px; width: 20px;" name="custom_dashboard[]">&nbsp;&nbsp;Admin Price
        </td>
        <td>
           <input type="checkbox" <?php if (strpos($value_config, ','."Wholesale Price".',') !== false) { echo " checked"; } ?> value="Wholesale Price" style="height: 20px; width: 20px;" name="custom_dashboard[]">&nbsp;&nbsp;Wholesale Price
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Commercial Price".',') !== false) { echo " checked"; } ?> value="Commercial Price" style="height: 20px; width: 20px;" name="custom_dashboard[]">&nbsp;&nbsp;Commercial Price
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Client Price".',') !== false) { echo " checked"; } ?> value="Client Price" style="height: 20px; width: 20px;" name="custom_dashboard[]">&nbsp;&nbsp;Client Price
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Minimum Billable".',') !== false) { echo " checked"; } ?> value="Minimum Billable" style="height: 20px; width: 20px;" name="custom_dashboard[]">&nbsp;&nbsp;Minimum Billable
        </td>
    </tr>

    <tr>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Estimated Hours".',') !== false) { echo " checked"; } ?> value="Estimated Hours" style="height: 20px; width: 20px;" name="custom_dashboard[]">&nbsp;&nbsp;Estimated Hours
        </td>

        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Actual Hours".',') !== false) { echo " checked"; } ?> value="Actual Hours" style="height: 20px; width: 20px;" name="custom_dashboard[]">&nbsp;&nbsp;Actual Hours
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."MSRP".',') !== false) {
            echo " checked"; } ?> value="MSRP" style="height: 20px; width: 20px;" name="custom_dashboard[]">&nbsp;&nbsp;MSRP
        </td>
    </tr>
</table>
-->
<div class="form-group">
	<div class="col-sm-4">
		<!--<a href="" class="btn brand-btn">Back</a>-->
		<a href="#" class="btn brand-btn" onclick="history.go(-1);return false;">Back</a>
	</div>
	<div class="col-sm-8">
		<span class="popover-examples list-inline pull-right" style="margin-left:-6em;"><a data-toggle="tooltip" data-placement="top" title="" data-original-title="The entire form will submit and close if this submit button is pressed.">
			<button type="submit" name="submit" value="submit" class="btn brand-btn">Submit</button></a></span>
		<button type="submit" name="submit" value="submit" class="btn brand-btn pull-right">Submit</button>
	</div>
</div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>