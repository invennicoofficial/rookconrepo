<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('report');
error_reporting(0);

if (isset($_POST['submit'])) {

    //Tabs
    $report_tabs = filter_var(implode(',', $_POST['report_tabs']),FILTER_SANITIZE_STRING);
    set_config($dbc, 'report_tabs', $report_tabs);
    //Tabs

    //Logo
	if (!file_exists('download')) {
		mkdir('download', 0777, true);
	}
    $logo = htmlspecialchars($_FILES["logo"]["name"], ENT_QUOTES);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='report_logo'"));
    if($get_config['configid'] > 0) {
		if($logo == '') {
			$logo_update = $_POST['logo_file'];
		} else {
			$logo_update = $logo;
		}
		move_uploaded_file($_FILES["logo"]["tmp_name"],"download/" . $logo_update);
        $query_update_employee = "UPDATE `general_configuration` SET value = '$logo_update' WHERE name='report_logo'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
		move_uploaded_file($_FILES["logo"]["tmp_name"], "download/" . $_FILES["logo"]["name"]) ;
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('report_logo', '$logo')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //Logo

    //Header & Footer
    $report_header = filter_var(htmlentities($_POST['report_header']),FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='report_header'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$report_header' WHERE name='report_header'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('report_header', '$report_header')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $report_footer = filter_var(htmlentities($_POST['report_footer']),FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='report_footer'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$report_footer' WHERE name='report_footer'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('report_footer', '$report_footer')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $invoice_unpaid_footer = filter_var(htmlentities($_POST['invoice_unpaid_footer']),FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='invoice_unpaid_footer'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$invoice_unpaid_footer' WHERE name='invoice_unpaid_footer'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('invoice_unpaid_footer', '$invoice_unpaid_footer')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //Header & Footer

    $reports_dashboard = implode(',',$_POST['reports_dashboard']);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='reports_dashboard'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$reports_dashboard' WHERE name='reports_dashboard'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('reports_dashboard', '$reports_dashboard')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

	//Report Fields - Operations Reports
	$report_fields = implode(',', $_POST['report_operation_fields']);
	mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`) SELECT 'report_operation_fields' FROM (SELECT COUNT(*) rows FROM `general_configuration` WHERE `name`='report_operation_fields') num WHERE num.rows=0");
	mysqli_query($dbc, "UPDATE `general_configuration` SET `value`='$report_fields' WHERE `name`='report_operation_fields'");

	//Report Fields - Compensation Reports
	$report_fields = implode(',', $_POST['report_compensation_fields']);
	mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`) SELECT 'report_compensation_fields' FROM (SELECT COUNT(*) rows FROM `general_configuration` WHERE `name`='report_compensation_fields') num WHERE num.rows=0");
	mysqli_query($dbc, "UPDATE `general_configuration` SET `value`='$report_fields' WHERE `name`='report_compensation_fields'");

    $contactid = $_POST['contactid'];

    //Default Sub Tab
    $mobile_landing_subtab = ( !empty($_POST['mobile_landing_subtab']) ) ? filter_var($_POST['mobile_landing_subtab'], FILTER_SANITIZE_STRING) : '';
    $desktop_landing_subtab = ( !empty($_POST['desktop_landing_subtab']) ) ? filter_var($_POST['desktop_landing_subtab'], FILTER_SANITIZE_STRING) : '';

    $mobile_landing_subtab_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(`configid`) AS `configid` FROM `general_configuration` WHERE `name`='reports_mobile_landing_subtab'"));
    if($mobile_landing_subtab_config['configid'] > 0) {
        $query_update_config = "UPDATE `general_configuration` SET `value`='$mobile_landing_subtab' WHERE `name`='reports_mobile_landing_subtab'";
        $result_update_config = mysqli_query($dbc, $query_update_config);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('reports_mobile_landing_subtab', '$mobile_landing_subtab')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $desktop_landing_subtab_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(`configid`) AS `configid` FROM `general_configuration` WHERE `name`='reports_desktop_landing_subtab'"));
    if($desktop_landing_subtab_config['configid'] > 0) {
        $query_update_config = "UPDATE `general_configuration` SET `value`='$desktop_landing_subtab' WHERE `name`='reports_desktop_landing_subtab'";
        $result_update_config = mysqli_query($dbc, $query_update_config);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('reports_desktop_landing_subtab', '$desktop_landing_subtab')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    echo '<script type="text/javascript"> window.location.replace("config_reports.php"); </script>';
}
?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<h1>Reports</h1>
<div class="pad-left gap-top double-gap-bottom"><a href="report_tiles.php" class="btn config-btn">Back to Dashboard</a></div>
<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
        <input type="hidden" name="contactid" value="<?php echo $_GET['contactid'] ?>" />

        <div class="panel-group" id="accordion2">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_tabs" >
                            Reports Tabs<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_tabs" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?php $report_tabs = !empty(get_config($dbc, 'report_tabs')) ? get_config($dbc, 'report_tabs') : 'operations,sales,ar,marketing,compensation,pnl,customer,staff';
                        $report_tabs = explode(',', $report_tabs); ?>
                        <h3>Enable Tabs</h3>
                        <div class="form-group">
                            <label class="form-checkbox"><input type="checkbox" name="report_tabs[]" value="operations" <?= in_array('operations', $report_tabs) ? 'checked' : '' ?>> Operations</label>
                            <label class="form-checkbox"><input type="checkbox" name="report_tabs[]" value="sales" <?= in_array('sales', $report_tabs) ? 'checked' : '' ?>> Sales</label>
                            <label class="form-checkbox"><input type="checkbox" name="report_tabs[]" value="ar" <?= in_array('ar', $report_tabs) ? 'checked' : '' ?>> Accounts Receivable</label>
                            <label class="form-checkbox"><input type="checkbox" name="report_tabs[]" value="marketing" <?= in_array('marketing', $report_tabs) ? 'checked' : '' ?>> Marketing</label>
                            <label class="form-checkbox"><input type="checkbox" name="report_tabs[]" value="compensation" <?= in_array('compensation', $report_tabs) ? 'checked' : '' ?>> Compensation</label>
                            <label class="form-checkbox"><input type="checkbox" name="report_tabs[]" value="pnl" <?= in_array('pnl', $report_tabs) ? 'checked' : '' ?>> Profit &amp; Loss</label>
                            <label class="form-checkbox"><input type="checkbox" name="report_tabs[]" value="customer" <?= in_array('customer', $report_tabs) ? 'checked' : '' ?>> Customer</label>
                            <label class="form-checkbox"><input type="checkbox" name="report_tabs[]" value="staff" <?= in_array('staff', $report_tabs) ? 'checked' : '' ?>> Staff</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field" >
                            Logo for Reports<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_field" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?php
                        $logo = get_config($dbc, 'report_logo');
                        ?>

                        <div class="form-group">
                        <label for="file[]" class="col-sm-4 control-label">
							<span class="popover-examples list-inline"><a href="#job_file" data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas."><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
							Upload Logo
                        :</label>
                        <div class="col-sm-8">
                        <?php if($logo != '') {
                            echo '<a href="download/'.$logo.'" target="_blank">View</a>';
                            ?>
                            <input type="hidden" name="logo_file" value="<?php echo $logo; ?>" />
                            <input name="logo" type="file" data-filename-placement="inside" class="form-control" />
                          <?php } else { ?>
                          <input name="logo" type="file" data-filename-placement="inside" class="form-control" />
                          <?php } ?>
                        </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-4 clearfix">
                                <a href="report_tiles.php?type=Per" class="btn config-btn pull-right">Back</a>
                            </div>
                            <div class="col-sm-8">
                                <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_hf" >
                            Reports Header & Footer<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_hf" class="panel-collapse collapse">
                    <div class="panel-body">

                        <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label"><h4>Header</h4></label>
                        <div class="col-sm-8">
                            <textarea name="report_header" rows="5" cols="50" class="form-control"><?php echo get_config($dbc, 'report_header'); ?></textarea>
                        </div>
                        </div>

                        <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label"><h4>Footer</h4></label>
                        <div class="col-sm-8">
                            <textarea name="report_footer" rows="5" cols="50" class="form-control"><?php echo get_config($dbc, 'report_footer'); ?></textarea>
                        </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-4 clearfix">
                                <a href="report_tiles.php?type=Per" class="btn config-btn pull-right">Back</a>
                            </div>
                            <div class="col-sm-8">
                                <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_survey" >
                            Dashboard Setting<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_survey" class="panel-collapse collapse">
                    <div class="panel-body">

						<?php $value_config = ','.get_config($dbc, 'reports_dashboard').','; ?>

                        <h3>Operations</h3>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Daysheet".',') !== FALSE) { echo " checked"; } ?> value="Daysheet" name="reports_dashboard[]"> Therapist Day Sheet</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Appointment Summary".',') !== FALSE) { echo " checked"; } ?> value="Appointment Summary" name="reports_dashboard[]"> Appointment Summary</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Ticket Attached".',') !== FALSE) { echo " checked"; } ?> value="Ticket Attached" name="reports_dashboard[]"> Attached to <?= TICKET_TILE ?></label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Therapist Stats".',') !== FALSE) { echo " checked"; } ?> value="Therapist Stats" name="reports_dashboard[]"> Therapist Stats</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Patient Block Booking".',') !== FALSE) { echo " checked"; } ?> value="Patient Block Booking" name="reports_dashboard[]"> Block Booking</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Drop Off Analysis".',') !== FALSE) { echo " checked"; } ?> value="Drop Off Analysis" name="reports_dashboard[]"> Drop Off Analysis</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Block Booking vs Not Block Booking".',') !== FALSE) { echo " checked"; } ?> value="Block Booking vs Not Block Booking" name="reports_dashboard[]"> Block Booking vs Not Block Booking</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Assessment Tally Board".',') !== FALSE) { echo " checked"; } ?> value="Assessment Tally Board" name="reports_dashboard[]"> Assessment Tally Board</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Discharge Report".',') !== FALSE) { echo " checked"; } ?> value="Discharge Report" name="reports_dashboard[]"> Discharge Report</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Injury Type".',') !== FALSE) { echo " checked"; } ?> value="Injury Type" name="reports_dashboard[]"> Injury Type</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Assessment Follow Up".',') !== FALSE) { echo " checked"; } ?> value="Assessment Follow Up" name="reports_dashboard[]"> Assessment Follow Ups</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Ticket Report".',') !== FALSE) { echo " checked"; } ?> value="Ticket Report" name="reports_dashboard[]"> <?= TICKET_NOUN ?> Report</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Treatment Report".',') !== FALSE) { echo " checked"; } ?> value="Treatment Report" name="reports_dashboard[]"> Treatment Report</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Field Jobs".',') !== FALSE) { echo " checked"; } ?> value="Field Jobs" name="reports_dashboard[]"> Field Jobs</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Site Work Time".',') !== FALSE) { echo " checked"; } ?> value="Site Work Time" name="reports_dashboard[]"> Site Work Order Time on Site</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Equipment List".',') !== FALSE) { echo " checked"; } ?> value="Equipment List" name="reports_dashboard[]"> Equipment List</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Shop Work Orders".',') !== FALSE) { echo " checked"; } ?> value="Shop Work Orders" name="reports_dashboard[]"> Shop Work Orders</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Site Work Driving".',') !== FALSE) { echo " checked"; } ?> value="Site Work Driving" name="reports_dashboard[]"> Site Work Order Driving Logs</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Shop Work Order Task Time".',') !== FALSE) { echo " checked"; } ?> value="Shop Work Order Task Time" name="reports_dashboard[]"> Shop Work Order Task Time</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Purchase Orders".',') !== FALSE) { echo " checked"; } ?> value="Purchase Orders" name="reports_dashboard[]"> Purchase Orders</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Shop Work Order Time".',') !== FALSE) { echo " checked"; } ?> value="Shop Work Order Time" name="reports_dashboard[]"> Shop Work Order Time</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Site Work Orders".',') !== FALSE) { echo " checked"; } ?> value="Site Work Orders" name="reports_dashboard[]"> Site Work Orders</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Inventory Log".',') !== FALSE) { echo " checked"; } ?> value="Inventory Log" name="reports_dashboard[]"> Inventory Log</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Equipment Transfer".',') !== FALSE) { echo " checked"; } ?> value="Equipment Transfer" name="reports_dashboard[]"> Equipment Transfer History</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Scrum Business Productivity Summary".',') !== FALSE) { echo " checked"; } ?> value="Scrum Business Productivity Summary" name="reports_dashboard[]"> Scrum Business Productivity Summary</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Point of Sale".',') !== FALSE) { echo " checked"; } ?> value="Point of Sale" name="reports_dashboard[]"> Point of Sale (Basic)</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Work Order".',') !== FALSE) { echo " checked"; } ?> value="Work Order" name="reports_dashboard[]"> Work Order</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Scrum Staff Productivity Summary".',') !== FALSE) { echo " checked"; } ?> value="Scrum Staff Productivity Summary" name="reports_dashboard[]"> Scrum Staff Productivity Summary</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."POS".',') !== FALSE) { echo " checked"; } ?> value="POS" name="reports_dashboard[]"> Point of Sale (Advanced)</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Staff Tickets".',') !== FALSE) { echo " checked"; } ?> value="Staff Tickets" name="reports_dashboard[]"> Staff <?= TICKET_TILE ?></label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Scrum Status Report".',') !== FALSE) { echo " checked"; } ?> value="Scrum Status Report" name="reports_dashboard[]"> Scrum Status Report</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Credit Card on File".',') !== FALSE) { echo " checked"; } ?> value="Credit Card on File" name="reports_dashboard[]"> Credit Card on File</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Day Sheet Report".',') !== FALSE) { echo " checked"; } ?> value="Day Sheet Report" name="reports_dashboard[]"> Day Sheet Report</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Service Usage Report".',') !== FALSE) { echo " checked"; } ?> value="Service Usage Report" name="reports_dashboard[]"> % Breakdown of Services Sold</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Checklist Time".',') !== FALSE) { echo " checked"; } ?> value="Checklist Time" name="reports_dashboard[]"> Checklist Time Tracking</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Ticket Time Summary".',') !== FALSE) { echo " checked"; } ?> value="Ticket Time Summary" name="reports_dashboard[]"> <?= TICKET_NOUN ?> Time Summary</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Download Tracker".',') !== FALSE) { echo " checked"; } ?> value="Download Tracker" name="reports_dashboard[]"> Download Tracker</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Tasklist Time".',') !== FALSE) { echo " checked"; } ?> value="Tasklist Time" name="reports_dashboard[]"> Task Time Tracking</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Ticket Deleted Notes".',') !== FALSE) { echo " checked"; } ?> value="Ticket Deleted Notes" name="reports_dashboard[]"> Archived <?= TICKET_NOUN ?> Notes</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Ticket Inventory Transport".',') !== FALSE) { echo " checked"; } ?> value="Ticket Inventory Transport" name="reports_dashboard[]"> <?= TICKET_NOUN ?> Transport of Inventory</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Dispatch Travel Time".',') !== FALSE) { echo " checked"; } ?> value="Dispatch Travel Time" name="reports_dashboard[]"> Dispatch <?= TICKET_NOUN ?> Travel Time</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Time Sheet".',') !== FALSE) { echo " checked"; } ?> value="Time Sheet" name="reports_dashboard[]"> Time Sheets Report</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Ticket Activity Report".',') !== FALSE) { echo " checked"; } ?> value="Ticket Activity Report" name="reports_dashboard[]"> <?= TICKET_NOUN ?> Activity Report per Customer</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Ticket by Task".',') !== FALSE) { echo " checked"; } ?> value="Ticket by Task" name="reports_dashboard[]"> <?= TICKET_NOUN ?> by Task</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Rate Card Report".',') !== FALSE) { echo " checked"; } ?> value="Rate Card Report" name="reports_dashboard[]"> Rate Cards Report</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Import Summary".',') !== FALSE) { echo " checked"; } ?> value="Import Summary" name="reports_dashboard[]"> Import Summary Report</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Import Details".',') !== FALSE) { echo " checked"; } ?> value="Import Details" name="reports_dashboard[]"> Detailed Import Report</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Ticket Manifest Summary".',') !== FALSE) { echo " checked"; } ?> value="Ticket Manifest Summary" name="reports_dashboard[]"> Manifest Daily Summary</label>
						</div>
						<div class="clearfix"></div>

						<h3>Sales</h3>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Validation by Therapist".',') !== FALSE) { echo " checked"; } ?> value="Validation by Therapist" name="reports_dashboard[]"> Validation by Therapist</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Sales Summary by Injury Type".',') !== FALSE) { echo " checked"; } ?> value="Sales Summary by Injury Type" name="reports_dashboard[]"> Sales Summary by Injury Type</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Gross Revenue by Staff".',') !== FALSE) { echo " checked"; } ?> value="Gross Revenue by Staff" name="reports_dashboard[]"> Gross Revenue by Staff</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."POS Validation".',') !== FALSE) { echo " checked"; } ?> value="POS Validation" name="reports_dashboard[]"> POS (Basic) Validation</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Inventory Analysis".',') !== FALSE) { echo " checked"; } ?> value="Inventory Analysis" name="reports_dashboard[]"> Inventory Analysis</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Patient Invoices".',') !== FALSE) { echo " checked"; } ?> value="Patient Invoices" name="reports_dashboard[]"> Customer Invoices</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."POS Advanced Validation".',') !== FALSE) { echo " checked"; } ?> value="POS Advanced Validation" name="reports_dashboard[]"> POS (Advanced) Validation</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Unassigned/Error Invoices".',') !== FALSE) { echo " checked"; } ?> value="Unassigned/Error Invoices" name="reports_dashboard[]"> Unassigned/Error Invoices</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."POS Sales Summary".',') !== FALSE) { echo " checked"; } ?> value="POS Sales Summary" name="reports_dashboard[]"> POS (Basic) Sales Summary</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Phone Communication".',') !== FALSE) { echo " checked"; } ?> value="Phone Communication" name="reports_dashboard[]"> Phone Communication</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Staff Revenue Report".',') !== FALSE) { echo " checked"; } ?> value="Staff Revenue Report" name="reports_dashboard[]"> Staff Revenue Report</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."POS Advanced Sales Summary".',') !== FALSE) { echo " checked"; } ?> value="POS Advanced Sales Summary" name="reports_dashboard[]"> POS (Advanced) Sales Summary</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Daily Deposit Report".',') !== FALSE) { echo " checked"; } ?> value="Daily Deposit Report" name="reports_dashboard[]"> Daily Deposit Report</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Expense Summary Report".',') !== FALSE) { echo " checked"; } ?> value="Expense Summary Report" name="reports_dashboard[]"> Expense Summary Report</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Profit-Loss".',') !== FALSE) { echo " checked"; } ?> value="Profit-Loss" name="reports_dashboard[]"> Profit-Loss</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Monthly Sales by Injury Type".',') !== FALSE) { echo " checked"; } ?> value="Monthly Sales by Injury Type" name="reports_dashboard[]"> Monthly Sales by Injury Type</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Sales by Inventory/Service Detail".',') !== FALSE) { echo " checked"; } ?> value="Sales by Inventory/Service Detail" name="reports_dashboard[]"> Sales by Inventory/Service Detail</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Profit-Loss POS Advanced".',') !== FALSE) { echo " checked"; } ?> value="Profit-Loss POS Advanced" name="reports_dashboard[]"> Profit-Loss (POS Advanced)</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Invoice Sales Summary".',') !== FALSE) { echo " checked"; } ?> value="Invoice Sales Summary" name="reports_dashboard[]"> Invoice Sales Summary</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Payment Method List".',') !== FALSE) { echo " checked"; } ?> value="Payment Method List" name="reports_dashboard[]"> Payment Method List</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Transaction List by Customer".',') !== FALSE) { echo " checked"; } ?> value="Transaction List by Customer" name="reports_dashboard[]"> Transaction List by Customer</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Sales by Customer Summary".',') !== FALSE) { echo " checked"; } ?> value="Sales by Customer Summary" name="reports_dashboard[]"> Sales by Customer Summary</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Patient History".',') !== FALSE) { echo " checked"; } ?> value="Patient History" name="reports_dashboard[]"> Customer History</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Unbilled Invoices".',') !== FALSE) { echo " checked"; } ?> value="Unbilled Invoices" name="reports_dashboard[]"> Unbilled Invoices</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Sales History by Customer".',') !== FALSE) { echo " checked"; } ?> value="Sales History by Customer" name="reports_dashboard[]"> Sales History by Customer</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Sales by Service Category".',') !== FALSE) { echo " checked"; } ?> value="Sales by Service Category" name="reports_dashboard[]"> Sales by Service Category</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Deposit Detail".',') !== FALSE) { echo " checked"; } ?> value="Deposit Detail" name="reports_dashboard[]"> Deposit Detail</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Sales by Service Summary".',') !== FALSE) { echo " checked"; } ?> value="Sales by Service Summary" name="reports_dashboard[]"> Sales by Service Summary</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Sales Estimates".',') !== FALSE) { echo " checked"; } ?> value="Sales Estimates" name="reports_dashboard[]"> Sales Estimates</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Sales by Inventory Summary".',') !== FALSE) { echo " checked"; } ?> value="Sales by Inventory Summary" name="reports_dashboard[]"> Sales by Inventory Summary</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Receipts Summary Report".',') !== FALSE) { echo " checked"; } ?> value="Receipts Summary Report" name="reports_dashboard[]"> Receipts Summary Report</label>
						</div>
                        <div class="clearfix"></div>

                        <h3>Accounts Receivable</h3>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."A/R Aging Summary".',') !== FALSE) { echo " checked"; } ?> value="A/R Aging Summary" name="reports_dashboard[]"> A/R Aging Summary</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Customer Balance Summary".',') !== FALSE) { echo " checked"; } ?> value="Customer Balance Summary" name="reports_dashboard[]"> Customer Balance Summary</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Invoice List".',') !== FALSE) { echo " checked"; } ?> value="Invoice List" name="reports_dashboard[]"> Invoice List</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Patient Aging Receivable Summary".',') !== FALSE) { echo " checked"; } ?> value="Patient Aging Receivable Summary" name="reports_dashboard[]"> Customer Aging Receivable Summary</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Customer Balance by Invoice".',') !== FALSE) { echo " checked"; } ?> value="Customer Balance by Invoice" name="reports_dashboard[]"> Customer Balance by Invoice</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."POS Receivables".',') !== FALSE) { echo " checked"; } ?> value="POS Receivables" name="reports_dashboard[]"> POS Receivables</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Insurer Aging Receivable Summary".',') !== FALSE) { echo " checked"; } ?> value="Insurer Aging Receivable Summary" name="reports_dashboard[]"> Insurer Aging Receivable Summary</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Collections Report by Customer".',') !== FALSE) { echo " checked"; } ?> value="Collections Report by Customer" name="reports_dashboard[]"> Collections Report by Customer</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."UI Invoice Report".',') !== FALSE) { echo " checked"; } ?> value="UI Invoice Report" name="reports_dashboard[]"> UI Invoice Report</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."By Invoice#".',') !== FALSE) { echo " checked"; } ?> value="By Invoice#" name="reports_dashboard[]"> By Invoice#</label>
						</div>
                        <div class="clearfix"></div>

                        <h3>Profit &amp; Loss</h3>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ',Revenue Receivables,') !== FALSE) { echo " checked"; } ?> value="Revenue Receivables" name="reports_dashboard[]"> Revenue &amp; Receivables</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ',Expenses,') !== FALSE) { echo " checked"; } ?> value="Expenses" name="reports_dashboard[]"> Expenses</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ',Summary,') !== FALSE) { echo " checked"; } ?> value="Summary" name="reports_dashboard[]"> Summary</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ',Staff Compensation,') !== FALSE) { echo " checked"; } ?> value="Staff Compensation" name="reports_dashboard[]"> Staff &amp; Compensation</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ',Costs,') !== FALSE) { echo " checked"; } ?> value="Costs" name="reports_dashboard[]"> Costs</label>
						</div>
                        <div class="clearfix"></div>

                        <h3>Marketing</h3>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."CRM Recommendations - By Customer".',') !== FALSE) { echo " checked"; } ?> value="CRM Recommendations - By Customer" name="reports_dashboard[]"> CRM Recommendations - By Customer</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Cart Abandonment".',') !== FALSE) { echo " checked"; } ?> value="Cart Abandonment" name="reports_dashboard[]"> Cart Abandonment</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Referral".',') !== FALSE) { echo " checked"; } ?> value="Referral" name="reports_dashboard[]"> Referrals</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."CRM Recommendations - By Date".',') !== FALSE) { echo " checked"; } ?> value="CRM Recommendations - By Date" name="reports_dashboard[]"> CRM Recommendations - By Date</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Demographics".',') !== FALSE) { echo " checked"; } ?> value="Demographics" name="reports_dashboard[]"> Demographics</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Web Referrals Report".',') !== FALSE) { echo " checked"; } ?> value="Web Referrals Report" name="reports_dashboard[]"> Web Referrals Report</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Customer Contact List".',') !== FALSE) { echo " checked"; } ?> value="Customer Contact List" name="reports_dashboard[]"> Customer Contact List</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."POS Coupons".',') !== FALSE) { echo " checked"; } ?> value="POS Coupons" name="reports_dashboard[]"> POS Coupons</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Pro Bono Report".',') !== FALSE) { echo " checked"; } ?> value="Pro Bono Report" name="reports_dashboard[]"> Pro-Bono</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Customer Stats".',') !== FALSE) { echo " checked"; } ?> value="Customer Stats" name="reports_dashboard[]"> Customer Stats</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Postal Code".',') !== FALSE) { echo " checked"; } ?> value="Postal Code" name="reports_dashboard[]"> Postal Code</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Contact Postal Code".',') !== FALSE) { echo " checked"; } ?> value="Contact Postal Code" name="reports_dashboard[]"> Contact Postal Code</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Site Visitors".',') !== FALSE) { echo " checked"; } ?> value="Site Visitors" name="reports_dashboard[]"> Website Visitors</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Net Promoter Score".',') !== FALSE) { echo " checked"; } ?> value="Net Promoter Score" name="reports_dashboard[]"> Net Promoter Score</label>
						</div>

						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Contact Report by Status".',') !== FALSE) { echo " checked"; } ?> value="Contact Report by Status" name="reports_dashboard[]"> Contact Report by Status</label>
						</div>
                        <div class="clearfix"></div>

                        <h3>Compensation</h3>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Adjustment Compensation".',') !== FALSE) { echo " checked"; } ?> value="Adjustment Compensation" name="reports_dashboard[]"> Adjustment Compensation</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Hourly Compensation".',') !== FALSE) { echo " checked"; } ?> value="Hourly Compensation" name="reports_dashboard[]"> Hourly Compensation</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Therapist Compensation".',') !== FALSE) { echo " checked"; } ?> value="Therapist Compensation" name="reports_dashboard[]"> Therapist Compensation</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Compensation Print Appointment Reports".',') !== FALSE) { echo " checked"; } ?> value="Compensation Print Appointment Reports" name="reports_dashboard[]"> Compensation: Print Appt. Reports Button</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Statutory Holiday Pay Breakdown".',') !== FALSE) { echo " checked"; } ?> value="Statutory Holiday Pay Breakdown" name="reports_dashboard[]"> Statutory Holiday Pay Breakdown</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Timesheet Payroll".',') !== FALSE) { echo " checked"; } ?> value="Timesheet Payroll" name="reports_dashboard[]"> Time Sheet Payroll</label>
						</div>
                        <div class="clearfix"></div>

                        <h3>Customer</h3>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Customer Sales by Customer Summary".',') !== FALSE) { echo " checked"; } ?> value="Customer Sales by Customer Summary" name="reports_dashboard[]"> Sales by Customer Summary</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Customer Sales History by Customer".',') !== FALSE) { echo " checked"; } ?> value="Customer Sales History by Customer" name="reports_dashboard[]"> Sales History by Customer</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Customer Patient Invoices".',') !== FALSE) { echo " checked"; } ?> value="Customer Patient Invoices" name="reports_dashboard[]"> Customer Invoices</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Customer Transaction List by Customer".',') !== FALSE) { echo " checked"; } ?> value="Customer Transaction List by Customer" name="reports_dashboard[]"> Transaction List by Customer</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Customer Patient History".',') !== FALSE) { echo " checked"; } ?> value="Customer Patient History" name="reports_dashboard[]"> Patient History</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Customer Customer Balance Summary".',') !== FALSE) { echo " checked"; } ?> value="Customer Customer Balance Summary" name="reports_dashboard[]"> Customer Balance Summary</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Customer Customer Balance by Invoice".',') !== FALSE) { echo " checked"; } ?> value="Customer Customer Balance by Invoice" name="reports_dashboard[]"> Customer Balance by Invoice</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Customer Collections Report by Customer".',') !== FALSE) { echo " checked"; } ?> value="Customer Collections Report by Customer" name="reports_dashboard[]"> Collections Report by Customer</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Customer Patient Aging Receivable Summary".',') !== FALSE) { echo " checked"; } ?> value="Customer Patient Aging Receivable Summary" name="reports_dashboard[]"> Patient Aging Receivable Summary</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Customer Customer Contact List".',') !== FALSE) { echo " checked"; } ?> value="Customer Customer Contact List" name="reports_dashboard[]"> Customer Contact List</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Customer Customer Stats".',') !== FALSE) { echo " checked"; } ?> value="Customer Customer Stats" name="reports_dashboard[]"> Customer Stats</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Customer CRM Recommendations - By Customer".',') !== FALSE) { echo " checked"; } ?> value="Customer CRM Recommendations - By Customer" name="reports_dashboard[]"> CRM Recommendations - By Customer</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Customer Contact Postal Code".',') !== FALSE) { echo " checked"; } ?> value="Customer Contact Postal Code" name="reports_dashboard[]"> Contact Postal Code</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Customer Service Rates".',') !== FALSE) { echo " checked"; } ?> value="Customer Service Rates" name="reports_dashboard[]"> Service Rates &amp; Hours</label>
						</div>
                        <div class="clearfix"></div>

                        <h3>Staff</h3>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Staff Staff Tickets".',') !== FALSE) { echo " checked"; } ?> value="Staff Staff Tickets" name="reports_dashboard[]"> Staff Tickets</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Staff Scrum Staff Productivity Summary".',') !== FALSE) { echo " checked"; } ?> value="Staff Scrum Staff Productivity Summary" name="reports_dashboard[]"> Scrum Staff Productivity Summary</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Staff Daysheet".',') !== FALSE) { echo " checked"; } ?> value="Staff Daysheet" name="reports_dashboard[]"> Therapist Day Sheet</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Staff Therapist Stats".',') !== FALSE) { echo " checked"; } ?> value="Staff Therapist Stats" name="reports_dashboard[]"> Therapist Stats</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Staff Day Sheet Report".',') !== FALSE) { echo " checked"; } ?> value="Staff Day Sheet Report" name="reports_dashboard[]"> Day Sheet Report</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Staff Staff Revenue Report".',') !== FALSE) { echo " checked"; } ?> value="Staff Staff Revenue Report" name="reports_dashboard[]"> Staff Revenue Report</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Staff Gross Revenue by Staff".',') !== FALSE) { echo " checked"; } ?> value="Staff Gross Revenue by Staff" name="reports_dashboard[]"> Gross Revenue by Staff</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Staff Validation by Therapist".',') !== FALSE) { echo " checked"; } ?> value="Staff Validation by Therapist" name="reports_dashboard[]"> Validation by Therapist</label>
						</div>
						<div class="col-sm-4">
							<label class="form-checkbox-any"><input type="checkbox" <?php if (strpos($value_config, ','."Staff Staff Compensation".',') !== FALSE) { echo " checked"; } ?> value="Staff Staff Compensation" name="reports_dashboard[]"> Staff Compensation</label>
						</div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_default_subtab" >
                            Choose Default Report<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_default_subtab" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Mobile Default Report</label>
                            <div class="col-sm-8"><?php
                                $mobile_landing_subtab_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `value` FROM `general_configuration` WHERE `name`='reports_mobile_landing_subtab'"));
                                $desktop_landing_subtab_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `value` FROM `general_configuration` WHERE `name`='reports_desktop_landing_subtab'")); ?>
                                <select name="mobile_landing_subtab" class="form-control chosen-select-deselect" data-placeholder="Select Sub Tab...">
                                    <option value=""></option>
                                    <optgroup label="Operations">
                                        <option value="Daysheet" <?= $mobile_landing_subtab_config['value']=='Daysheet' ? 'selected="selected"' : '' ?>>Therapist Day Sheet</option>
                                        <option value="Therapist Stats" <?= $mobile_landing_subtab_config['value']=='Therapist Stats' ? 'selected="selected"' : '' ?>>Therapist Stats</option>
                                        <option value="Block Booking vs Not Block Booking" <?= $mobile_landing_subtab_config['value']=='Block Booking vs Not Block Booking' ? 'selected="selected"' : '' ?>>Block Booking vs Not Block Booking</option>
                                        <option value="Injury Type" <?= $mobile_landing_subtab_config['value']=='Injury Type' ? 'selected="selected"' : '' ?>>Injury Type</option>
                                        <option value="Treatment Report" <?= $mobile_landing_subtab_config['value']=='Treatment Report' ? 'selected="selected"' : '' ?>>Treatment Report</option>
                                        <option value="Equipment List" <?= $mobile_landing_subtab_config['value']=='Equipment List' ? 'selected="selected"' : '' ?>>Equipment List</option>
                                        <option value="Shop Work Order Task Time" <?= $mobile_landing_subtab_config['value']=='Shop Work Order Task Time' ? 'selected="selected"' : '' ?>>Shop Work Order Task Time</option>
                                        <option value="Site Work Orders" <?= $mobile_landing_subtab_config['value']=='Site Work Orders' ? 'selected="selected"' : '' ?>>Site Work Orders</option>
                                        <option value="Scrum Business Productivity Summary" <?= $mobile_landing_subtab_config['value']=='Scrum Business Productivity Summary' ? 'selected="selected"' : '' ?>>Scrum Business Productivity Summary</option>
                                        <option value="Scrum Staff Productivity Summary" <?= $mobile_landing_subtab_config['value']=='Scrum Staff Productivity Summary' ? 'selected="selected"' : '' ?>>Scrum Staff Productivity Summary</option>
                                        <option value="Scrum Status Report" <?= $mobile_landing_subtab_config['value']=='Scrum Status Report' ? 'selected="selected"' : '' ?>>Scrum Status Report</option>
                                        <option value="Service Usage Report" <?= $mobile_landing_subtab_config['value']=='Service Usage Report' ? 'selected="selected"' : '' ?>>% Breakdown of Services Sold</option>
                                        <option value="Download Tracker" <?= $mobile_landing_subtab_config['value']=='Download Tracker' ? 'selected="selected"' : '' ?>>Download Tracker</option>
                                        <option value="Appointment Summary" <?= $mobile_landing_subtab_config['value']=='Appointment Summary' ? 'selected="selected"' : '' ?>>Appointment Summary</option>
                                        <option value="Patient Block Booking" <?= $mobile_landing_subtab_config['value']=='Patient Block Booking' ? 'selected="selected"' : '' ?>>Block Booking</option>
                                        <option value="Assessment Tally Board" <?= $mobile_landing_subtab_config['value']=='Assessment Tally Board' ? 'selected="selected"' : '' ?>>Assessment Tally Board</option>
                                        <option value="Assessment Follow Up" <?= $mobile_landing_subtab_config['value']=='Assessment Follow Up' ? 'selected="selected"' : '' ?>>Assessment Follow Ups</option>
                                        <option value="Field Jobs" <?= $mobile_landing_subtab_config['value']=='Field Jobs' ? 'selected="selected"' : '' ?>>Field Jobs</option>
                                        <option value="Shop Work Orders" <?= $mobile_landing_subtab_config['value']=='Shop Work Orders' ? 'selected="selected"' : '' ?>>Shop Work Orders</option>
                                        <option value="Purchase Orders" <?= $mobile_landing_subtab_config['value']=='Purchase Orders' ? 'selected="selected"' : '' ?>>Purchase Orders</option>
                                        <option value="Inventory Log" <?= $mobile_landing_subtab_config['value']=='Inventory Log' ? 'selected="selected"' : '' ?>>Inventory Log</option>
                                        <option value="Point of Sale" <?= $mobile_landing_subtab_config['value']=='Point of Sale' ? 'selected="selected"' : '' ?>>Point of Sale (Basic)</option>
                                        <option value="POS" <?= $mobile_landing_subtab_config['value']=='POS' ? 'selected="selected"' : '' ?>>Point of Sale (Advanced)</option>
                                        <option value="Credit Card on File" <?= $mobile_landing_subtab_config['value']=='Credit Card on File' ? 'selected="selected"' : '' ?>>Credit Card on File</option>
                                        <option value="Checklist Time" <?= $mobile_landing_subtab_config['value']=='Checklist Time' ? 'selected="selected"' : '' ?>>Checklist Time Tracking</option>
                                        <option value="Tasklist Time" <?= $mobile_landing_subtab_config['value']=='Tasklist Time' ? 'selected="selected"' : '' ?>>Task Time Tracking</option>
                                        <option value="Ticket Attached" <?= $mobile_landing_subtab_config['value']=='Ticket Attached' ? 'selected="selected"' : '' ?>>Attached to <?= TICKET_TILE ?></option>
                                        <option value="Drop Off Analysis" <?= $mobile_landing_subtab_config['value']=='Drop Off Analysis' ? 'selected="selected"' : '' ?>>Drop Off Analysis</option>
                                        <option value="Discharge Report" <?= $mobile_landing_subtab_config['value']=='Discharge Report' ? 'selected="selected"' : '' ?>>Discharge Report</option>
                                        <option value="Ticket Report" <?= $mobile_landing_subtab_config['value']=='Ticket Report' ? 'selected="selected"' : '' ?>><?= TICKET_NOUN ?> Report</option>
                                        <option value="Site Work Time" <?= $mobile_landing_subtab_config['value']=='Site Work Time' ? 'selected="selected"' : '' ?>>Site Work Order Time on Site</option>
                                        <option value="Site Work Driving" <?= $mobile_landing_subtab_config['value']=='Site Work Driving' ? 'selected="selected"' : '' ?>>Site Work Order Driving Logs</option>
                                        <option value="Shop Work Order Time" <?= $mobile_landing_subtab_config['value']=='Shop Work Order Time' ? 'selected="selected"' : '' ?>>Shop Work Order Time</option>
                                        <option value="Equipment Transfer" <?= $mobile_landing_subtab_config['value']=='Equipment Transfer' ? 'selected="selected"' : '' ?>>Equipment Transfer History</option>
                                        <option value="Work Order" <?= $mobile_landing_subtab_config['value']=='Work Order' ? 'selected="selected"' : '' ?>>Work Order</option>
                                        <option value="Staff Tickets" <?= $mobile_landing_subtab_config['value']=='Staff Tickets' ? 'selected="selected"' : '' ?>>Staff <?= TICKET_TILE ?></option>
                                        <option value="Day Sheet Report" <?= $mobile_landing_subtab_config['value']=='Day Sheet Report' ? 'selected="selected"' : '' ?>>Day Sheet Report</option>
                                        <option value="Ticket Time Summary" <?= $mobile_landing_subtab_config['value']=='Ticket Time Summary' ? 'selected="selected"' : '' ?>><?= TICKET_NOUN ?> Time Summary</option>
                                        <option value="Ticket Deleted Notes" <?= $mobile_landing_subtab_config['value']=='Ticket Deleted Notes' ? 'selected="selected"' : '' ?>>Archived <?= TICKET_NOUN ?> Notes</option>
                                        <option value="Ticket Activity Report" <?= $mobile_landing_subtab_config['value']=='Ticket Activity Report' ? 'selected="selected"' : '' ?>><?= TICKET_NOUN ?> Activity Report per Customer</option>
                                        <option value="Rate Card Report" <?= $mobile_landing_subtab_config['value']=='Rate Card Report' ? 'selected="selected"' : '' ?>>Rate Cards Report</option>
                                        <option value="Import Summary" <?= $mobile_landing_subtab_config['value']=='Import Summary' ? 'selected="selected"' : '' ?>>Import Summary Report</option>
                                        <option value="Import Details" <?= $mobile_landing_subtab_config['value']=='Import Details' ? 'selected="selected"' : '' ?>>Detailed Import Report</option>
                                        <option value="Ticket Manifest Summary" <?= $mobile_landing_subtab_config['value']=='Ticket Manifest Summary' ? 'selected="selected"' : '' ?>>Manifest Daily Summary</option>
                                    </optgroup>
                                    <optgroup label="Sales">
                                        <option value="Validation by Therapist" <?= $mobile_landing_subtab_config['value']=='Validation by Therapist' ? 'selected="selected"' : '' ?>>Validation by Therapist</option>
                                        <option value="POS Validation" <?= $mobile_landing_subtab_config['value']=='POS Validation' ? 'selected="selected"' : '' ?>>POS (Basic) Validation</option>
                                        <option value="POS Advanced Validation" <?= $mobile_landing_subtab_config['value']=='POS Advanced Validation' ? 'selected="selected"' : '' ?>>POS (Advanced) Validation</option>
                                        <option value="Phone Communication" <?= $mobile_landing_subtab_config['value']=='Phone Communication' ? 'selected="selected"' : '' ?>>Phone Communication</option>
                                        <option value="Daily Deposit Report" <?= $mobile_landing_subtab_config['value']=='Daily Deposit Report' ? 'selected="selected"' : '' ?>>Daily Deposit Report</option>
                                        <option value="Monthly Sales by Injury Type" <?= $mobile_landing_subtab_config['value']=='Monthly Sales by Injury Type' ? 'selected="selected"' : '' ?>>Monthly Sales by Injury Type</option>
                                        <option value="Invoice Sales Summary" <?= $mobile_landing_subtab_config['value']=='Invoice Sales Summary' ? 'selected="selected"' : '' ?>>Invoice Sales Summary</option>
                                        <option value="Sales by Customer Summary" <?= $mobile_landing_subtab_config['value']=='Sales by Customer Summary' ? 'selected="selected"' : '' ?>>Sales by Customer Summary</option>
                                        <option value="Sales History by Customer" <?= $mobile_landing_subtab_config['value']=='Sales History by Customer' ? 'selected="selected"' : '' ?>>Sales History by Customer</option>
                                        <option value="Sales by Service Summary" <?= $mobile_landing_subtab_config['value']=='Sales by Service Summary' ? 'selected="selected"' : '' ?>>Sales by Service Summary</option>
                                        <option value="Sales by Inventory Summary" <?= $mobile_landing_subtab_config['value']=='Sales by Inventory Summary' ? 'selected="selected"' : '' ?>>Sales by Inventory Summary</option>
                                        <option value="Sales Summary by Injury Type" <?= $mobile_landing_subtab_config['value']=='Sales Summary by Injury Type' ? 'selected="selected"' : '' ?>>Sales Summary by Injury Type</option>
                                        <option value="Inventory Analysis" <?= $mobile_landing_subtab_config['value']=='Inventory Analysis' ? 'selected="selected"' : '' ?>>Inventory Analysis</option>
                                        <option value="Unassigned/Error Invoices" <?= $mobile_landing_subtab_config['value']=='Unassigned/Error Invoices' ? 'selected="selected"' : '' ?>>Unassigned/Error Invoices</option>
                                        <option value="Staff Revenue Report" <?= $mobile_landing_subtab_config['value']=='Staff Revenue Report' ? 'selected="selected"' : '' ?>>Staff Revenue Report</option>
                                        <option value="Expense Summary Report" <?= $mobile_landing_subtab_config['value']=='Expense Summary Report' ? 'selected="selected"' : '' ?>>Expense Summary Report</option>
                                        <option value="Sales by Inventory/Service Detail" <?= $mobile_landing_subtab_config['value']=='Sales by Inventory/Service Detail' ? 'selected="selected"' : '' ?>>Sales by Inventory/Service Detail</option>
                                        <option value="Payment Method List" <?= $mobile_landing_subtab_config['value']=='Payment Method List' ? 'selected="selected"' : '' ?>>Payment Method List</option>
                                        <option value="Patient History" <?= $mobile_landing_subtab_config['value']=='Patient History' ? 'selected="selected"' : '' ?>>Customer History</option>
                                        <option value="Sales by Service Category" <?= $mobile_landing_subtab_config['value']=='Sales by Service Category' ? 'selected="selected"' : '' ?>>Sales by Service Category</option>
                                        <option value="Sales Estimates" <?= $mobile_landing_subtab_config['value']=='Sales Estimates' ? 'selected="selected"' : '' ?>>Sales Estimates</option>
                                        <option value="Receipts Summary Report" <?= $mobile_landing_subtab_config['value']=='Receipts Summary Report' ? 'selected="selected"' : '' ?>>Receipts Summary Report</option>
                                        <option value="Gross Revenue by Staff" <?= $mobile_landing_subtab_config['value']=='Gross Revenue by Staff' ? 'selected="selected"' : '' ?>>Gross Revenue by Staff</option>
                                        <option value="Patient Invoices" <?= $mobile_landing_subtab_config['value']=='Patient Invoices' ? 'selected="selected"' : '' ?>>Customer Invoices</option>
                                        <option value="POS Sales Summary" <?= $mobile_landing_subtab_config['value']=='POS Sales Summary' ? 'selected="selected"' : '' ?>>POS (Basic) Sales Summary</option>
                                        <option value="POS Advanced Sales Summary" <?= $mobile_landing_subtab_config['value']=='POS Advanced Sales Summary' ? 'selected="selected"' : '' ?>>POS (Advanced) Sales Summary</option>
                                        <option value="Profit-Loss" <?= $mobile_landing_subtab_config['value']=='Profit-Loss' ? 'selected="selected"' : '' ?>>Profit-Loss</option>
                                        <option value="Profit-Loss POS Advanced" <?= $mobile_landing_subtab_config['value']=='Profit-Loss POS Advanced' ? 'selected="selected"' : '' ?>>Profit-Loss (POS Advanced)</option>
                                        <option value="Transaction List by Customer" <?= $mobile_landing_subtab_config['value']=='Transaction List by Customer' ? 'selected="selected"' : '' ?>>Transaction List by Customer</option>
                                        <option value="Unbilled Invoices" <?= $mobile_landing_subtab_config['value']=='Unbilled Invoices' ? 'selected="selected"' : '' ?>>Unbilled Invoices</option>
                                        <option value="Deposit Detail" <?= $mobile_landing_subtab_config['value']=='Deposit Detail' ? 'selected="selected"' : '' ?>>Deposit Detail</option>
                                    </optgroup>
                                    <optgroup label="Accounts Receivable">
                                        <option value="A/R Aging Summary" <?= $mobile_landing_subtab_config['value']=='A/R Aging Summary' ? 'selected="selected"' : '' ?>>A/R Aging Summary</option>
                                        <option value="Patient Aging Receivable Summary" <?= $mobile_landing_subtab_config['value']=='Patient Aging Receivable Summary' ? 'selected="selected"' : '' ?>>Customer Aging Receivable Summary</option>
                                        <option value="Insurer Aging Receivable Summary" <?= $mobile_landing_subtab_config['value']=='Insurer Aging Receivable Summary' ? 'selected="selected"' : '' ?>>Insurer Aging Receivable Summary</option>
                                        <option value="By Invoice#" <?= $mobile_landing_subtab_config['value']=='By Invoice#' ? 'selected="selected"' : '' ?>>By Invoice#</option>
                                        <option value="Customer Balance Summary" <?= $mobile_landing_subtab_config['value']=='Customer Balance Summary' ? 'selected="selected"' : '' ?>>Customer Balance Summary</option>
                                        <option value="Customer Balance by Invoice" <?= $mobile_landing_subtab_config['value']=='Customer Balance by Invoice' ? 'selected="selected"' : '' ?>>Customer Balance by Invoice</option>
                                        <option value="Collections Report by Customer" <?= $mobile_landing_subtab_config['value']=='Collections Report by Customer' ? 'selected="selected"' : '' ?>>Collections Report by Customer</option>
                                        <option value="Invoice List" <?= $mobile_landing_subtab_config['value']=='Invoice List' ? 'selected="selected"' : '' ?>>Invoice List</option>
                                        <option value="POS Receivables" <?= $mobile_landing_subtab_config['value']=='POS Receivables' ? 'selected="selected"' : '' ?>>POS Receivables</option>
                                        <option value="UI Invoice Report" <?= $mobile_landing_subtab_config['value']=='UI Invoice Report' ? 'selected="selected"' : '' ?>>UI Invoice Report</option>
                                    </optgroup>
                                    <optgroup label="Profit & Loss">
                                        <option value="Revenue Receivables" <?= $mobile_landing_subtab_config['value']=='Revenue Receivables' ? 'selected="selected"' : '' ?>>Revenue &amp; Receivables</option>
                                        <option value="Staff Compensation" <?= $mobile_landing_subtab_config['value']=='Staff Compensation' ? 'selected="selected"' : '' ?>>Staff &amp; Compensation</option>
                                        <option value="Expenses" <?= $mobile_landing_subtab_config['value']=='Expenses' ? 'selected="selected"' : '' ?>>Expenses</option>
                                        <option value="Costs" <?= $mobile_landing_subtab_config['value']=='Costs' ? 'selected="selected"' : '' ?>>Costs</option>
                                        <option value="Summary" <?= $mobile_landing_subtab_config['value']=='Summary' ? 'selected="selected"' : '' ?>>Summary</option>
                                    </optgroup>
                                    <optgroup label="Marketing">
                                        <option value="CRM Recommendations - By Customer" <?= $mobile_landing_subtab_config['value']=='CRM Recommendations - By Customer' ? 'selected="selected"' : '' ?>>CRM Recommendations - By Customer</option>
                                        <option value="CRM Recommendations - By Date" <?= $mobile_landing_subtab_config['value']=='CRM Recommendations - By Date' ? 'selected="selected"' : '' ?>>CRM Recommendations - By Date</option>
                                        <option value="Customer Contact List" <?= $mobile_landing_subtab_config['value']=='Customer Contact List' ? 'selected="selected"' : '' ?>>Customer Contact List</option>
                                        <option value="Customer Stats" <?= $mobile_landing_subtab_config['value']=='Customer Stats' ? 'selected="selected"' : '' ?>>Customer Stats</option>
                                        <option value="Demographics" <?= $mobile_landing_subtab_config['value']=='Demographics' ? 'selected="selected"' : '' ?>>Demographics</option>
                                        <option value="POS Coupons" <?= $mobile_landing_subtab_config['value']=='POS Coupons' ? 'selected="selected"' : '' ?>>POS Coupons</option>
                                        <option value="Postal Code" <?= $mobile_landing_subtab_config['value']=='Postal Code' ? 'selected="selected"' : '' ?>>Postal Code</option>
                                        <option value="Net Promoter Score" <?= $mobile_landing_subtab_config['value']=='Net Promoter Score' ? 'selected="selected"' : '' ?>>Net Promoter Score</option>
                                        <option value="Contact Report by Status" <?= $mobile_landing_subtab_config['value']=='Contact Report by Status' ? 'selected="selected"' : '' ?>>Contact Report by Status</option>
                                        <option value="Referral" <?= $mobile_landing_subtab_config['value']=='Referral' ? 'selected="selected"' : '' ?>>Referrals</option>
                                        <option value="Web Referrals Report" <?= $mobile_landing_subtab_config['value']=='Web Referrals Report' ? 'selected="selected"' : '' ?>>Web Referrals Report</option>
                                        <option value="Pro Bono Report" <?= $mobile_landing_subtab_config['value']=='Pro Bono Report' ? 'selected="selected"' : '' ?>>Pro-Bono</option>
                                        <option value="Contact Postal Code" <?= $mobile_landing_subtab_config['value']=='Contact Postal Code' ? 'selected="selected"' : '' ?>>Contact Postal Code</option>
                                    </optgroup>
                                    <optgroup label="Compensation">
                                        <option value="Adjustment Compensation" <?= $mobile_landing_subtab_config['value']=='Adjustment Compensation' ? 'selected="selected"' : '' ?>>Adjustment Compensation</option>
                                        <option value="Compensation Print Appointment Reports" <?= $mobile_landing_subtab_config['value']=='Compensation Print Appointment Reports' ? 'selected="selected"' : '' ?>>Compensation: Print Appt. Reports Button</option>
                                        <option value="Hourly Compensation" <?= $mobile_landing_subtab_config['value']=='Hourly Compensation' ? 'selected="selected"' : '' ?>>Hourly Compensation</option>
                                        <option value="Therapist Compensation" <?= $mobile_landing_subtab_config['value']=='Therapist Compensation' ? 'selected="selected"' : '' ?>>Therapist Compensation</option>
                                        <option value="Statutory Holiday Pay Breakdown" <?= $mobile_landing_subtab_config['value']=='Statutory Holiday Pay Breakdown' ? 'selected="selected"' : '' ?>>Statutory Holiday Pay Breakdown</option>
                                        <option value="Timesheet Payroll" <?= $mobile_landing_subtab_config['value']=='Timesheet Payroll' ? 'selected="selected"' : '' ?>>Time Sheet Payroll</option>
                                    </optgroup>
                                    <optgroup label="Customer">
                                        <option value="Customer Sales by Customer Summary" <?= $mobile_landing_subtab_config['value']=='Customer Sales by Customer Summary' ? 'selected="selected"' : '' ?>>Sales by Customer Summary</option>
                                        <option value="Customer Sales History by Customer" <?= $mobile_landing_subtab_config['value']=='Customer Sales History by Customer' ? 'selected="selected"' : '' ?>>Sales History by Customer</option>
                                        <option value="Customer Patient Invoices" <?= $mobile_landing_subtab_config['value']=='Customer Patient Invoices' ? 'selected="selected"' : '' ?>>Customer Invoices</option>
                                        <option value="Customer Transaction List by Customer" <?= $mobile_landing_subtab_config['value']=='Customer Transaction List by Customer' ? 'selected="selected"' : '' ?>>Transaction List by Customer</option>
                                        <option value="Customer Patient History" <?= $mobile_landing_subtab_config['value']=='Customer Patient History' ? 'selected="selected"' : '' ?>>Patient History</option>
                                        <option value="Customer Customer Balance Summary" <?= $mobile_landing_subtab_config['value']=='Customer Customer Balance Summary' ? 'selected="selected"' : '' ?>>Customer Balance Summary</option>
                                        <option value="Customer Customer Balance by Invoice" <?= $mobile_landing_subtab_config['value']=='Customer Customer Balance by Invoice' ? 'selected="selected"' : '' ?>>Customer Balance by Invoice</option>
                                        <option value="Customer Collections Report by Customer" <?= $mobile_landing_subtab_config['value']=='Customer Collections Report by Customer' ? 'selected="selected"' : '' ?>>Collections Report by Customer</option>
                                        <option value="Customer Patient Aging Receivable Summary" <?= $mobile_landing_subtab_config['value']=='Customer Patient Aging Receivable Summary' ? 'selected="selected"' : '' ?>>Patient Aging Receivable Summary</option>
                                        <option value="Customer Customer Contact List" <?= $mobile_landing_subtab_config['value']=='Customer Customer Contact List' ? 'selected="selected"' : '' ?>>Customer Contact List</option>
                                        <option value="Customer Customer Stats" <?= $mobile_landing_subtab_config['value']=='Customer Customer Stats' ? 'selected="selected"' : '' ?>>Customer Stats</option>
                                        <option value="Customer CRM Recommendations - By Customer" <?= $mobile_landing_subtab_config['value']=='Customer CRM Recommendations - By Customer' ? 'selected="selected"' : '' ?>>CRM Recommendations - By Customer</option>
                                        <option value="Customer Contact Postal Code" <?= $mobile_landing_subtab_config['value']=='Customer Contact Postal Code' ? 'selected="selected"' : '' ?>>Contact Postal Code</option>
                                    </optgroup>
                                    <optgroup label="Staff">
                                        <option value="Staff Staff Tickets" <?= $mobile_landing_subtab_config['value']=='Staff Staff Tickets' ? 'selected="selected"' : '' ?>>Staff Tickets</option>
                                        <option value="Staff Scrum Staff Productivity Summary" <?= $mobile_landing_subtab_config['value']=='Staff Scrum Staff Productivity Summary' ? 'selected="selected"' : '' ?>>Scrum Staff Productivity Summary</option>
                                        <option value="Staff Daysheet" <?= $mobile_landing_subtab_config['value']=='Staff Daysheet' ? 'selected="selected"' : '' ?>>Therapist Day Sheet</option>
                                        <option value="Staff Therapist Stats" <?= $mobile_landing_subtab_config['value']=='Staff Therapist Stats' ? 'selected="selected"' : '' ?>>Therapist Stats</option>
                                        <option value="Staff Day Sheet Report" <?= $mobile_landing_subtab_config['value']=='Staff Day Sheet Report' ? 'selected="selected"' : '' ?>>Day Sheet Report</option>
                                        <option value="Staff Staff Revenue Report" <?= $mobile_landing_subtab_config['value']=='Staff Staff Revenue Report' ? 'selected="selected"' : '' ?>>Staff Revenue Report</option>
                                        <option value="Staff Gross Revenue by Staff" <?= $mobile_landing_subtab_config['value']=='Staff Gross Revenue by Staff' ? 'selected="selected"' : '' ?>>Gross Revenue by Staff</option>
                                        <option value="Staff Validation by Therapist" <?= $mobile_landing_subtab_config['value']=='Staff Validation by Therapist' ? 'selected="selected"' : '' ?>>Validation by Therapist</option>
                                        <option value="Staff Staff Compensation" <?= $mobile_landing_subtab_config['value']=='Staff Staff Compensation' ? 'selected="selected"' : '' ?>>Staff Compensation</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Desktop Default Report</label>
                            <div class="col-sm-8">
                                <select name="desktop_landing_subtab" class="form-control chosen-select-deselect" data-placeholder="Select Sub Tab...">
                                    <option value=""></option>
                                    <optgroup label="Operations">
                                        <option value="Daysheet" <?= $desktop_landing_subtab_config['value']=='Daysheet' ? 'selected="selected"' : '' ?>>Therapist Day Sheet</option>
                                        <option value="Therapist Stats" <?= $desktop_landing_subtab_config['value']=='Therapist Stats' ? 'selected="selected"' : '' ?>>Therapist Stats</option>
                                        <option value="Block Booking vs Not Block Booking" <?= $desktop_landing_subtab_config['value']=='Block Booking vs Not Block Booking' ? 'selected="selected"' : '' ?>>Block Booking vs Not Block Booking</option>
                                        <option value="Injury Type" <?= $desktop_landing_subtab_config['value']=='Injury Type' ? 'selected="selected"' : '' ?>>Injury Type</option>
                                        <option value="Treatment Report" <?= $desktop_landing_subtab_config['value']=='Treatment Report' ? 'selected="selected"' : '' ?>>Treatment Report</option>
                                        <option value="Equipment List" <?= $desktop_landing_subtab_config['value']=='Equipment List' ? 'selected="selected"' : '' ?>>Equipment List</option>
                                        <option value="Shop Work Order Task Time" <?= $desktop_landing_subtab_config['value']=='Shop Work Order Task Time' ? 'selected="selected"' : '' ?>>Shop Work Order Task Time</option>
                                        <option value="Site Work Orders" <?= $desktop_landing_subtab_config['value']=='Site Work Orders' ? 'selected="selected"' : '' ?>>Site Work Orders</option>
                                        <option value="Scrum Business Productivity Summary" <?= $desktop_landing_subtab_config['value']=='Scrum Business Productivity Summary' ? 'selected="selected"' : '' ?>>Scrum Business Productivity Summary</option>
                                        <option value="Scrum Staff Productivity Summary" <?= $desktop_landing_subtab_config['value']=='Scrum Staff Productivity Summary' ? 'selected="selected"' : '' ?>>Scrum Staff Productivity Summary</option>
                                        <option value="Scrum Status Report" <?= $desktop_landing_subtab_config['value']=='Scrum Status Report' ? 'selected="selected"' : '' ?>>Scrum Status Report</option>
                                        <option value="Service Usage Report" <?= $desktop_landing_subtab_config['value']=='Service Usage Report' ? 'selected="selected"' : '' ?>>% Breakdown of Services Sold</option>
                                        <option value="Download Tracker" <?= $desktop_landing_subtab_config['value']=='Download Tracker' ? 'selected="selected"' : '' ?>>Download Tracker</option>
                                        <option value="Appointment Summary" <?= $desktop_landing_subtab_config['value']=='Appointment Summary' ? 'selected="selected"' : '' ?>>Appointment Summary</option>
                                        <option value="Patient Block Booking" <?= $desktop_landing_subtab_config['value']=='Patient Block Booking' ? 'selected="selected"' : '' ?>>Block Booking</option>
                                        <option value="Assessment Tally Board" <?= $desktop_landing_subtab_config['value']=='Assessment Tally Board' ? 'selected="selected"' : '' ?>>Assessment Tally Board</option>
                                        <option value="Assessment Follow Up" <?= $desktop_landing_subtab_config['value']=='Assessment Follow Up' ? 'selected="selected"' : '' ?>>Assessment Follow Ups</option>
                                        <option value="Field Jobs" <?= $desktop_landing_subtab_config['value']=='Field Jobs' ? 'selected="selected"' : '' ?>>Field Jobs</option>
                                        <option value="Shop Work Orders" <?= $desktop_landing_subtab_config['value']=='Shop Work Orders' ? 'selected="selected"' : '' ?>>Shop Work Orders</option>
                                        <option value="Purchase Orders" <?= $desktop_landing_subtab_config['value']=='Purchase Orders' ? 'selected="selected"' : '' ?>>Purchase Orders</option>
                                        <option value="Inventory Log" <?= $desktop_landing_subtab_config['value']=='Inventory Log' ? 'selected="selected"' : '' ?>>Inventory Log</option>
                                        <option value="Point of Sale" <?= $desktop_landing_subtab_config['value']=='Point of Sale' ? 'selected="selected"' : '' ?>>Point of Sale (Basic)</option>
                                        <option value="POS" <?= $desktop_landing_subtab_config['value']=='POS' ? 'selected="selected"' : '' ?>>Point of Sale (Advanced)</option>
                                        <option value="Credit Card on File" <?= $desktop_landing_subtab_config['value']=='Credit Card on File' ? 'selected="selected"' : '' ?>>Credit Card on File</option>
                                        <option value="Checklist Time" <?= $desktop_landing_subtab_config['value']=='Checklist Time' ? 'selected="selected"' : '' ?>>Checklist Time Tracking</option>
                                        <option value="Tasklist Time" <?= $desktop_landing_subtab_config['value']=='Tasklist Time' ? 'selected="selected"' : '' ?>>Task Time Tracking</option>
                                        <option value="Ticket Attached" <?= $desktop_landing_subtab_config['value']=='Ticket Attached' ? 'selected="selected"' : '' ?>>Attached to <?= TICKET_TILE ?></option>
                                        <option value="Drop Off Analysis" <?= $desktop_landing_subtab_config['value']=='Drop Off Analysis' ? 'selected="selected"' : '' ?>>Drop Off Analysis</option>
                                        <option value="Discharge Report" <?= $desktop_landing_subtab_config['value']=='Discharge Report' ? 'selected="selected"' : '' ?>>Discharge Report</option>
                                        <option value="Ticket Report" <?= $desktop_landing_subtab_config['value']=='Ticket Report' ? 'selected="selected"' : '' ?>><?= TICKET_NOUN ?> Report</option>
                                        <option value="Site Work Time" <?= $desktop_landing_subtab_config['value']=='Site Work Time' ? 'selected="selected"' : '' ?>>Site Work Order Time on Site</option>
                                        <option value="Site Work Driving" <?= $desktop_landing_subtab_config['value']=='Site Work Driving' ? 'selected="selected"' : '' ?>>Site Work Order Driving Logs</option>
                                        <option value="Shop Work Order Time" <?= $desktop_landing_subtab_config['value']=='Shop Work Order Time' ? 'selected="selected"' : '' ?>>Shop Work Order Time</option>
                                        <option value="Equipment Transfer" <?= $desktop_landing_subtab_config['value']=='Equipment Transfer' ? 'selected="selected"' : '' ?>>Equipment Transfer History</option>
                                        <option value="Work Order" <?= $desktop_landing_subtab_config['value']=='Work Order' ? 'selected="selected"' : '' ?>>Work Order</option>
                                        <option value="Staff Tickets" <?= $desktop_landing_subtab_config['value']=='Staff Tickets' ? 'selected="selected"' : '' ?>>Staff <?= TICKET_TILE ?></option>
                                        <option value="Day Sheet Report" <?= $desktop_landing_subtab_config['value']=='Day Sheet Report' ? 'selected="selected"' : '' ?>>Day Sheet Report</option>
                                        <option value="Ticket Time Summary" <?= $desktop_landing_subtab_config['value']=='Ticket Time Summary' ? 'selected="selected"' : '' ?>><?= TICKET_NOUN ?> Time Summary</option>
                                        <option value="Ticket Deleted Notes" <?= $desktop_landing_subtab_config['value']=='Ticket Deleted Notes' ? 'selected="selected"' : '' ?>>Archived <?= TICKET_NOUN ?> Notes</option>
                                        <option value="Ticket Activity Report" <?= $desktop_landing_subtab_config['value']=='Ticket Activity Report' ? 'selected="selected"' : '' ?>><?= TICKET_NOUN ?> Activity Report per Customer</option>
                                        <option value="Rate Card Report" <?= $desktop_landing_subtab_config['value']=='Rate Card Report' ? 'selected="selected"' : '' ?>>Rate Cards Report</option>
                                        <option value="Import Summary" <?= $desktop_landing_subtab_config['value']=='Import Summary' ? 'selected="selected"' : '' ?>>Import Summary Report</option>
                                        <option value="Import Details" <?= $desktop_landing_subtab_config['value']=='Import Details' ? 'selected="selected"' : '' ?>>Detailed Import Report</option>
                                        <option value="Ticket Manifest Summary" <?= $desktop_landing_subtab_config['value']=='Ticket Manifest Summary' ? 'selected="selected"' : '' ?>>Manifest Daily Summary</option>
                                    </optgroup>
                                    <optgroup label="Sales">
                                        <option value="Validation by Therapist" <?= $desktop_landing_subtab_config['value']=='Validation by Therapist' ? 'selected="selected"' : '' ?>>Validation by Therapist</option>
                                        <option value="POS Validation" <?= $desktop_landing_subtab_config['value']=='POS Validation' ? 'selected="selected"' : '' ?>>POS (Basic) Validation</option>
                                        <option value="POS Advanced Validation" <?= $desktop_landing_subtab_config['value']=='POS Advanced Validation' ? 'selected="selected"' : '' ?>>POS (Advanced) Validation</option>
                                        <option value="Phone Communication" <?= $desktop_landing_subtab_config['value']=='Phone Communication' ? 'selected="selected"' : '' ?>>Phone Communication</option>
                                        <option value="Daily Deposit Report" <?= $desktop_landing_subtab_config['value']=='Daily Deposit Report' ? 'selected="selected"' : '' ?>>Daily Deposit Report</option>
                                        <option value="Monthly Sales by Injury Type" <?= $desktop_landing_subtab_config['value']=='Monthly Sales by Injury Type' ? 'selected="selected"' : '' ?>>Monthly Sales by Injury Type</option>
                                        <option value="Invoice Sales Summary" <?= $desktop_landing_subtab_config['value']=='Invoice Sales Summary' ? 'selected="selected"' : '' ?>>Invoice Sales Summary</option>
                                        <option value="Sales by Customer Summary" <?= $desktop_landing_subtab_config['value']=='Sales by Customer Summary' ? 'selected="selected"' : '' ?>>Sales by Customer Summary</option>
                                        <option value="Sales History by Customer" <?= $desktop_landing_subtab_config['value']=='Sales History by Customer' ? 'selected="selected"' : '' ?>>Sales History by Customer</option>
                                        <option value="Sales by Service Summary" <?= $desktop_landing_subtab_config['value']=='Sales by Service Summary' ? 'selected="selected"' : '' ?>>Sales by Service Summary</option>
                                        <option value="Sales by Inventory Summary" <?= $desktop_landing_subtab_config['value']=='Sales by Inventory Summary' ? 'selected="selected"' : '' ?>>Sales by Inventory Summary</option>
                                        <option value="Sales Summary by Injury Type" <?= $desktop_landing_subtab_config['value']=='Sales Summary by Injury Type' ? 'selected="selected"' : '' ?>>Sales Summary by Injury Type</option>
                                        <option value="Inventory Analysis" <?= $desktop_landing_subtab_config['value']=='Inventory Analysis' ? 'selected="selected"' : '' ?>>Inventory Analysis</option>
                                        <option value="Unassigned/Error Invoices" <?= $desktop_landing_subtab_config['value']=='Unassigned/Error Invoices' ? 'selected="selected"' : '' ?>>Unassigned/Error Invoices</option>
                                        <option value="Staff Revenue Report" <?= $desktop_landing_subtab_config['value']=='Staff Revenue Report' ? 'selected="selected"' : '' ?>>Staff Revenue Report</option>
                                        <option value="Expense Summary Report" <?= $desktop_landing_subtab_config['value']=='Expense Summary Report' ? 'selected="selected"' : '' ?>>Expense Summary Report</option>
                                        <option value="Sales by Inventory/Service Detail" <?= $desktop_landing_subtab_config['value']=='Sales by Inventory/Service Detail' ? 'selected="selected"' : '' ?>>Sales by Inventory/Service Detail</option>
                                        <option value="Payment Method List" <?= $desktop_landing_subtab_config['value']=='Payment Method List' ? 'selected="selected"' : '' ?>>Payment Method List</option>
                                        <option value="Patient History" <?= $desktop_landing_subtab_config['value']=='Patient History' ? 'selected="selected"' : '' ?>>Customer History</option>
                                        <option value="Sales by Service Category" <?= $desktop_landing_subtab_config['value']=='Sales by Service Category' ? 'selected="selected"' : '' ?>>Sales by Service Category</option>
                                        <option value="Sales Estimates" <?= $desktop_landing_subtab_config['value']=='Sales Estimates' ? 'selected="selected"' : '' ?>>Sales Estimates</option>
                                        <option value="Receipts Summary Report" <?= $desktop_landing_subtab_config['value']=='Receipts Summary Report' ? 'selected="selected"' : '' ?>>Receipts Summary Report</option>
                                        <option value="Gross Revenue by Staff" <?= $desktop_landing_subtab_config['value']=='Gross Revenue by Staff' ? 'selected="selected"' : '' ?>>Gross Revenue by Staff</option>
                                        <option value="Patient Invoices" <?= $desktop_landing_subtab_config['value']=='Patient Invoices' ? 'selected="selected"' : '' ?>>Customer Invoices</option>
                                        <option value="POS Sales Summary" <?= $desktop_landing_subtab_config['value']=='POS Sales Summary' ? 'selected="selected"' : '' ?>>POS (Basic) Sales Summary</option>
                                        <option value="POS Advanced Sales Summary" <?= $desktop_landing_subtab_config['value']=='POS Advanced Sales Summary' ? 'selected="selected"' : '' ?>>POS (Advanced) Sales Summary</option>
                                        <option value="Profit-Loss" <?= $desktop_landing_subtab_config['value']=='Profit-Loss' ? 'selected="selected"' : '' ?>>Profit-Loss</option>
                                        <option value="Profit-Loss POS Advanced" <?= $desktop_landing_subtab_config['value']=='Profit-Loss POS Advanced' ? 'selected="selected"' : '' ?>>Profit-Loss (POS Advanced)</option>
                                        <option value="Transaction List by Customer" <?= $desktop_landing_subtab_config['value']=='Transaction List by Customer' ? 'selected="selected"' : '' ?>>Transaction List by Customer</option>
                                        <option value="Unbilled Invoices" <?= $desktop_landing_subtab_config['value']=='Unbilled Invoices' ? 'selected="selected"' : '' ?>>Unbilled Invoices</option>
                                        <option value="Deposit Detail" <?= $desktop_landing_subtab_config['value']=='Deposit Detail' ? 'selected="selected"' : '' ?>>Deposit Detail</option>
                                    </optgroup>
                                    <optgroup label="Accounts Receivable">
                                        <option value="A/R Aging Summary" <?= $desktop_landing_subtab_config['value']=='A/R Aging Summary' ? 'selected="selected"' : '' ?>>A/R Aging Summary</option>
                                        <option value="Patient Aging Receivable Summary" <?= $desktop_landing_subtab_config['value']=='Patient Aging Receivable Summary' ? 'selected="selected"' : '' ?>>Customer Aging Receivable Summary</option>
                                        <option value="Insurer Aging Receivable Summary" <?= $desktop_landing_subtab_config['value']=='Insurer Aging Receivable Summary' ? 'selected="selected"' : '' ?>>Insurer Aging Receivable Summary</option>
                                        <option value="By Invoice#" <?= $desktop_landing_subtab_config['value']=='By Invoice#' ? 'selected="selected"' : '' ?>>By Invoice#</option>
                                        <option value="Customer Balance Summary" <?= $desktop_landing_subtab_config['value']=='Customer Balance Summary' ? 'selected="selected"' : '' ?>>Customer Balance Summary</option>
                                        <option value="Customer Balance by Invoice" <?= $desktop_landing_subtab_config['value']=='Customer Balance by Invoice' ? 'selected="selected"' : '' ?>>Customer Balance by Invoice</option>
                                        <option value="Collections Report by Customer" <?= $desktop_landing_subtab_config['value']=='Collections Report by Customer' ? 'selected="selected"' : '' ?>>Collections Report by Customer</option>
                                        <option value="Invoice List" <?= $desktop_landing_subtab_config['value']=='Invoice List' ? 'selected="selected"' : '' ?>>Invoice List</option>
                                        <option value="POS Receivables" <?= $desktop_landing_subtab_config['value']=='POS Receivables' ? 'selected="selected"' : '' ?>>POS Receivables</option>
                                        <option value="UI Invoice Report" <?= $desktop_landing_subtab_config['value']=='UI Invoice Report' ? 'selected="selected"' : '' ?>>UI Invoice Report</option>
                                    </optgroup>
                                    <optgroup label="Profit & Loss">
                                        <option value="Revenue Receivables" <?= $desktop_landing_subtab_config['value']=='Revenue Receivables' ? 'selected="selected"' : '' ?>>Revenue &amp; Receivables</option>
                                        <option value="Staff Compensation" <?= $desktop_landing_subtab_config['value']=='Staff Compensation' ? 'selected="selected"' : '' ?>>Staff &amp; Compensation</option>
                                        <option value="Expenses" <?= $desktop_landing_subtab_config['value']=='Expenses' ? 'selected="selected"' : '' ?>>Expenses</option>
                                        <option value="Costs" <?= $desktop_landing_subtab_config['value']=='Costs' ? 'selected="selected"' : '' ?>>Costs</option>
                                        <option value="Summary" <?= $desktop_landing_subtab_config['value']=='Summary' ? 'selected="selected"' : '' ?>>Summary</option>
                                    </optgroup>
                                    <optgroup label="Marketing">
                                        <option value="CRM Recommendations - By Customer" <?= $desktop_landing_subtab_config['value']=='CRM Recommendations - By Customer' ? 'selected="selected"' : '' ?>>CRM Recommendations - By Customer</option>
                                        <option value="CRM Recommendations - By Date" <?= $desktop_landing_subtab_config['value']=='CRM Recommendations - By Date' ? 'selected="selected"' : '' ?>>CRM Recommendations - By Date</option>
                                        <option value="Customer Contact List" <?= $desktop_landing_subtab_config['value']=='Customer Contact List' ? 'selected="selected"' : '' ?>>Customer Contact List</option>
                                        <option value="Customer Stats" <?= $desktop_landing_subtab_config['value']=='Customer Stats' ? 'selected="selected"' : '' ?>>Customer Stats</option>
                                        <option value="Demographics" <?= $desktop_landing_subtab_config['value']=='Demographics' ? 'selected="selected"' : '' ?>>Demographics</option>
                                        <option value="POS Coupons" <?= $desktop_landing_subtab_config['value']=='POS Coupons' ? 'selected="selected"' : '' ?>>POS Coupons</option>
                                        <option value="Postal Code" <?= $desktop_landing_subtab_config['value']=='Postal Code' ? 'selected="selected"' : '' ?>>Postal Code</option>
                                        <option value="Net Promoter Score" <?= $desktop_landing_subtab_config['value']=='Net Promoter Score' ? 'selected="selected"' : '' ?>>Net Promoter Score</option>
                                        <option value="Contact Report by Status" <?= $desktop_landing_subtab_config['value']=='Contact Report by Status' ? 'selected="selected"' : '' ?>>Contact Report by Status</option>
                                        <option value="Referral" <?= $desktop_landing_subtab_config['value']=='Referral' ? 'selected="selected"' : '' ?>>Referrals</option>
                                        <option value="Web Referrals Report" <?= $desktop_landing_subtab_config['value']=='Web Referrals Report' ? 'selected="selected"' : '' ?>>Web Referrals Report</option>
                                        <option value="Pro Bono Report" <?= $desktop_landing_subtab_config['value']=='Pro Bono Report' ? 'selected="selected"' : '' ?>>Pro-Bono</option>
                                        <option value="Contact Postal Code" <?= $desktop_landing_subtab_config['value']=='Contact Postal Code' ? 'selected="selected"' : '' ?>>Contact Postal Code</option>
                                    </optgroup>
                                    <optgroup label="Compensation">
                                        <option value="Adjustment Compensation" <?= $desktop_landing_subtab_config['value']=='Adjustment Compensation' ? 'selected="selected"' : '' ?>>Adjustment Compensation</option>
                                        <option value="Compensation Print Appointment Reports" <?= $desktop_landing_subtab_config['value']=='Compensation Print Appointment Reports' ? 'selected="selected"' : '' ?>>Compensation: Print Appt. Reports Button</option>
                                        <option value="Hourly Compensation" <?= $desktop_landing_subtab_config['value']=='Hourly Compensation' ? 'selected="selected"' : '' ?>>Hourly Compensation</option>
                                        <option value="Therapist Compensation" <?= $desktop_landing_subtab_config['value']=='Therapist Compensation' ? 'selected="selected"' : '' ?>>Therapist Compensation</option>
                                        <option value="Statutory Holiday Pay Breakdown" <?= $desktop_landing_subtab_config['value']=='Statutory Holiday Pay Breakdown' ? 'selected="selected"' : '' ?>>Statutory Holiday Pay Breakdown</option>
                                        <option value="Timesheet Payroll" <?= $desktop_landing_subtab_config['value']=='Timesheet Payroll' ? 'selected="selected"' : '' ?>>Time Sheet Payroll</option>
                                    </optgroup>
                                    <optgroup label="Customer">
                                        <option value="Customer Sales by Customer Summary" <?= $desktop_landing_subtab_config['value']=='Customer Sales by Customer Summary' ? 'selected="selected"' : '' ?>>Sales by Customer Summary</option>
                                        <option value="Customer Sales History by Customer" <?= $desktop_landing_subtab_config['value']=='Customer Sales History by Customer' ? 'selected="selected"' : '' ?>>Sales History by Customer</option>
                                        <option value="Customer Patient Invoices" <?= $desktop_landing_subtab_config['value']=='Customer Patient Invoices' ? 'selected="selected"' : '' ?>>Customer Invoices</option>
                                        <option value="Customer Transaction List by Customer" <?= $desktop_landing_subtab_config['value']=='Customer Transaction List by Customer' ? 'selected="selected"' : '' ?>>Transaction List by Customer</option>
                                        <option value="Customer Patient History" <?= $desktop_landing_subtab_config['value']=='Customer Patient History' ? 'selected="selected"' : '' ?>>Patient History</option>
                                        <option value="Customer Customer Balance Summary" <?= $desktop_landing_subtab_config['value']=='Customer Customer Balance Summary' ? 'selected="selected"' : '' ?>>Customer Balance Summary</option>
                                        <option value="Customer Customer Balance by Invoice" <?= $desktop_landing_subtab_config['value']=='Customer Customer Balance by Invoice' ? 'selected="selected"' : '' ?>>Customer Balance by Invoice</option>
                                        <option value="Customer Collections Report by Customer" <?= $desktop_landing_subtab_config['value']=='Customer Collections Report by Customer' ? 'selected="selected"' : '' ?>>Collections Report by Customer</option>
                                        <option value="Customer Patient Aging Receivable Summary" <?= $desktop_landing_subtab_config['value']=='Customer Patient Aging Receivable Summary' ? 'selected="selected"' : '' ?>>Patient Aging Receivable Summary</option>
                                        <option value="Customer Customer Contact List" <?= $desktop_landing_subtab_config['value']=='Customer Customer Contact List' ? 'selected="selected"' : '' ?>>Customer Contact List</option>
                                        <option value="Customer Customer Stats" <?= $desktop_landing_subtab_config['value']=='Customer Customer Stats' ? 'selected="selected"' : '' ?>>Customer Stats</option>
                                        <option value="Customer CRM Recommendations - By Customer" <?= $desktop_landing_subtab_config['value']=='Customer CRM Recommendations - By Customer' ? 'selected="selected"' : '' ?>>CRM Recommendations - By Customer</option>
                                        <option value="Customer Contact Postal Code" <?= $desktop_landing_subtab_config['value']=='Customer Contact Postal Code' ? 'selected="selected"' : '' ?>>Contact Postal Code</option>
                                    </optgroup>
                                    <optgroup label="Staff">
                                        <option value="Staff Staff Tickets" <?= $desktop_landing_subtab_config['value']=='Staff Staff Tickets' ? 'selected="selected"' : '' ?>>Staff Tickets</option>
                                        <option value="Staff Scrum Staff Productivity Summary" <?= $desktop_landing_subtab_config['value']=='Staff Scrum Staff Productivity Summary' ? 'selected="selected"' : '' ?>>Scrum Staff Productivity Summary</option>
                                        <option value="Staff Daysheet" <?= $desktop_landing_subtab_config['value']=='Staff Daysheet' ? 'selected="selected"' : '' ?>>Therapist Day Sheet</option>
                                        <option value="Staff Therapist Stats" <?= $desktop_landing_subtab_config['value']=='Staff Therapist Stats' ? 'selected="selected"' : '' ?>>Therapist Stats</option>
                                        <option value="Staff Day Sheet Report" <?= $desktop_landing_subtab_config['value']=='Staff Day Sheet Report' ? 'selected="selected"' : '' ?>>Day Sheet Report</option>
                                        <option value="Staff Staff Revenue Report" <?= $desktop_landing_subtab_config['value']=='Staff Staff Revenue Report' ? 'selected="selected"' : '' ?>>Staff Revenue Report</option>
                                        <option value="Staff Gross Revenue by Staff" <?= $desktop_landing_subtab_config['value']=='Staff Gross Revenue by Staff' ? 'selected="selected"' : '' ?>>Gross Revenue by Staff</option>
                                        <option value="Staff Validation by Therapist" <?= $desktop_landing_subtab_config['value']=='Staff Validation by Therapist' ? 'selected="selected"' : '' ?>>Validation by Therapist</option>
                                        <option value="Staff Staff Compensation" <?= $desktop_landing_subtab_config['value']=='Staff Staff Compensation' ? 'selected="selected"' : '' ?>>Staff Compensation</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

			<?php $report_fields = explode(',', get_config($dbc, 'report_operation_fields')); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_op_reports" >
                            Operations Reports<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_op_reports" class="panel-collapse collapse">
                    <div class="panel-body">
						<h2><?= TICKET_NOUN ?> Activity Report per Customer</h2>
                        <div class="form-group">
							<label class="form-checkbox"><input type="checkbox" <?= (in_array('ticket_activity_site',$report_fields) ? 'checked' : '') ?> name="report_operation_fields[]" value="ticket_activity_site">Show Site Information</label>
							<label class="form-checkbox"><input type="checkbox" <?= (in_array('ticket_activity_rate_card',$report_fields) ? 'checked' : '') ?> name="report_operation_fields[]" value="ticket_activity_rate_card">Show Rate Card Name</label>
							<label class="form-checkbox"><input type="checkbox" <?= (in_array('ticket_activity_staff_count',$report_fields) ? 'checked' : '') ?> name="report_operation_fields[]" value="ticket_activity_staff_count">Show Staff Count</label>
							<label class="form-checkbox"><input type="checkbox" <?= (in_array('ticket_activity_notes',$report_fields) ? 'checked' : '') ?> name="report_operation_fields[]" value="ticket_activity_notes">Show Notes</label>
                        </div>
                    </div>
                </div>
            </div>

			<?php $report_fields = explode(',', get_config($dbc, 'report_compensation_fields')); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_comp_reports" >
                            Compensation Reports<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_comp_reports" class="panel-collapse collapse">
                    <div class="panel-body">
						<h2>Therapist and Adjustment Compensation Reports</h2>
                        <div class="form-group">
							<label class="form-checkbox"><input type="checkbox" <?= (in_array('therapist_patient_info',$report_fields) ? 'checked' : '') ?> name="report_compensation_fields[]" value="therapist_patient_info">
							Show Customer Information</label>
                        </div>
                    </div>
                </div>
            </div>

        </div><div class="form-group">
			<div class="col-sm-6">
				<a href="report_tiles.php" class="btn config-btn btn-lg">Back</a>
				<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
			</div>
			<div class="col-sm-6">
				<button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
			</div>
		</div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>
