<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('medication');
error_reporting(0);

if (isset($_POST['submit'])) {
    $medication = implode(',',$_POST['medication']);
    $medication_dashboard = implode(',',$_POST['medication_dashboard']);

    if (strpos(','.$medication.',',','.'Medication Type,Category,Title'.',') === false) {
        $medication = 'Medication Type,Category,Title,'.$medication;
    }
    if (strpos(','.$medication_dashboard.',',','.'Medication Type,Category,Title'.',') === false) {
        $medication_dashboard = 'Medication Type,Category,Title,'.$medication_dashboard;
    }

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(fieldconfigid) AS fieldconfigid FROM field_config"));
    if($get_field_config['fieldconfigid'] > 0) {
        $query_update_employee = "UPDATE `field_config` SET medication = '$medication', medication_dashboard = '$medication_dashboard' WHERE `fieldconfigid` = 1";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config` (`medication`, `medication_dashboard`) VALUES ('$medication', '$medication_dashboard')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $medtype_custom = filter_var($_POST['medtype_custom'], FILTER_SANITIZE_STRING);
    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(*) as num_rows FROM `general_configuration` WHERE `name` = 'medication_medtype_custom'"));
    if($get_field_config['num_rows'] > 0) {
        $query_update = "UPDATE `general_configuration` SET `value` = '$medtype_custom' WHERE `name` = 'medication_medtype_custom'";
        $result_update = mysqli_query($dbc, $query_update);
    } else {
        $query_insert = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('medication_medtype_custom', '$medtype_custom')";
        $result_insert = mysqli_query($dbc, $query_insert);
    }

    $title_custom = filter_var($_POST['title_custom'], FILTER_SANITIZE_STRING);
    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(*) as num_rows FROM `general_configuration` WHERE `name` = 'medication_title_custom'"));
    if($get_field_config['num_rows'] > 0) {
        $query_update = "UPDATE `general_configuration` SET `value` = '$title_custom' WHERE `name` = 'medication_title_custom'";
        $result_update = mysqli_query($dbc, $query_update);
    } else {
        $query_insert = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('medication_title_custom', '$title_custom')";
        $result_insert = mysqli_query($dbc, $query_insert);
    }

    $category_default = filter_var($_POST['category_default'], FILTER_SANITIZE_STRING);
    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(*) as num_rows FROM `general_configuration` WHERE `name` = 'medication_category_default'"));
    if($get_field_config['num_rows'] > 0) {
        $query_update = "UPDATE `general_configuration` SET `value` = '$category_default' WHERE `name` = 'medication_category_default'";
        $result_update = mysqli_query($dbc, $query_update);
    } else {
        $query_insert = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('medication_category_default', '$category_default')";
        $result_insert = mysqli_query($dbc, $query_insert);
    }

    $medication_contacts = filter_var(implode(',',$_POST['client_category']), FILTER_SANITIZE_STRING);
    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(*) as num_rows FROM `general_configuration` WHERE `name` = 'medication_contacts'"));
    if($get_field_config['num_rows'] > 0) {
        $query_update = "UPDATE `general_configuration` SET `value` = '$medication_contacts' WHERE `name` = 'medication_contacts'";
        $result_update = mysqli_query($dbc, $query_update);
    } else {
        $query_insert = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('medication_contacts', '$medication_contacts')";
        $result_insert = mysqli_query($dbc, $query_insert);
    }

    echo '<script type="text/javascript"> window.location.replace("field_config_medication.php"); </script>';

} else if (isset($_POST['submit_marsheet'])) {
    $marsheet_fields = filter_var(implode(',',$_POST['marsheet_fields']),FILTER_SANITIZE_STRING);
    $marsheet_row_headings = filter_var($_POST['marsheet_row_headings'],FILTER_SANITIZE_STRING);
    $marsheet_medication_tile = filter_var($_POST['marsheet_medication_tile'],FILTER_SANITIZE_STRING);
    if(empty($marsheet_medication_tile)) {
        $marsheet_medication_tile = '';
    }

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(*) as num_rows FROM `general_configuration` WHERE `name` = 'marsheet_fields'"));
    if($get_field_config['num_rows'] > 0) {
        $query_update = "UPDATE `general_configuration` SET `value` = '$marsheet_fields' WHERE `name` = 'marsheet_fields'";
        $result_update = mysqli_query($dbc, $query_update);
    } else {
        $query_insert = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('marsheet_fields', '$marsheet_fields')";
        $result_insert = mysqli_query($dbc, $query_insert);
    }

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(*) as num_rows FROM `general_configuration` WHERE `name` = 'marsheet_row_headings'"));
    if($get_field_config['num_rows'] > 0) {
        $query_update = "UPDATE `general_configuration` SET `value` = '$marsheet_row_headings' WHERE `name` = 'marsheet_row_headings'";
        $result_update = mysqli_query($dbc, $query_update);
    } else {
        $query_insert = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('marsheet_row_headings', '$marsheet_row_headings')";
        $result_insert = mysqli_query($dbc, $query_insert);
    }

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(*) as num_rows FROM `general_configuration` WHERE `name` = 'marsheet_medication_tile'"));
    if($get_field_config['num_rows'] > 0) {
        $query_update = "UPDATE `general_configuration` SET `value` = '$marsheet_medication_tile' WHERE `name` = 'marsheet_medication_tile'";
        $result_update = mysqli_query($dbc, $query_update);
    } else {
        $query_insert = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('marsheet_medication_tile', '$marsheet_medication_tile')";
        $result_insert = mysqli_query($dbc, $query_insert);
    }
}
?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<h1>Medication</h1>
<div class="pad-left gap-top double-gap-bottom"><a href="medication.php" class="btn config-btn">Back to Dashboard</a></div>
<a href="?tab=medication" class="btn brand-btn <?= $_GET['tab'] != 'marsheet' ? 'active_tab' : '' ?>">Medication</a>
<a href="?tab=marsheet" class="btn brand-btn <?= $_GET['tab'] == 'marsheet' ? 'active_tab' : '' ?>">MAR Sheet</a>

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<?php if($_GET['tab'] == 'marsheet') { ?>
<div class="panel-group" id="accordion2">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field" >
                    Choose Fields for MAR Sheet<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_field" class="panel-collapse collapse in">
            <div class="panel-body">
                <?php
                mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`, `value`) SELECT 'marsheet_fields', 'Route,Dosage,Instructions,Medication Notes,Notes' FROM (SELECT COUNT(*) rows FROM `general_configuration` WHERE `name` = 'marsheet_fields') num WHERE num.rows = 0");
                mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`, `value`) SELECT 'marsheet_row_headings', 'AM,Snack,Lunch,Snack,Supper,Snack,Bedtime' FROM (SELECT COUNT(*) rows FROM `general_configuration` WHERE `name` = 'marsheet_row_headings') num WHERE num.rows = 0");
                $value_config = ','.get_config($dbc, "marsheet_fields").',';
                $row_headings = get_config($dbc, "marsheet_row_headings");
                ?>

                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Route".',') !== FALSE) { echo " checked"; } ?> value="Route" style="height: 20px; width: 20px;" name="marsheet_fields[]">&nbsp;&nbsp;Route<br />
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Dosage".',') !== FALSE) { echo " checked"; } ?> value="Dosage" style="height: 20px; width: 20px;" name="marsheet_fields[]">&nbsp;&nbsp;Dosage<br />
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Instructions".',') !== FALSE) { echo " checked"; } ?> value="Instructions" style="height: 20px; width: 20px;" name="marsheet_fields[]">&nbsp;&nbsp;Instructions
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Medication Notes".',') !== FALSE) { echo " checked"; } ?> value="Medication Notes" style="height: 20px; width: 20px;" name="marsheet_fields[]">&nbsp;&nbsp;Medication Notes (Allergies or Special Instructions)
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Notes".',') !== FALSE) { echo " checked"; } ?> value="Notes" style="height: 20px; width: 20px;" name="marsheet_fields[]">&nbsp;&nbsp;Notes
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Inline View".',') !== FALSE) { echo " checked"; } ?> value="Inline View" style="height: 20px; width: 20px;" name="marsheet_fields[]">&nbsp;&nbsp;Inline View
                        </td>
                    </tr>
                </table>
                <div class="clearfix"></div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Default MAR Sheet Row Headings:<br>(Add Headings separated by a comma.)</label>
                    <div class="col-sm-8">
                        <input type="text" name="marsheet_row_headings" class="form-control" value="<?= $row_headings ?>">
                    </div>
                </div>
                <div class="form-gruop">
                    <label class="col-sm-4 control-label">Use MAR Sheet in Medication Tile:</label>
                    <div class="col-sm-8">
                        <?php $marsheet_medication_tile = get_config($dbc, 'marsheet_medication_tile'); ?>
                        <label class="form-checkbox"><input type="checkbox" name="marsheet_medication_tile" class="form-control" value="1" <?= $marsheet_medication_tile == 1 ? 'checked="checked"' : '' ?>></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-6"><a href="medication.php" class="btn config-btn btn-lg">Back</a></div>
    <div class="col-sm-6"><button type="submit" name="submit_marsheet" value="Submit" class="btn config-btn btn-lg   pull-right">Submit</button></div>
    <div class="clearfix"></div>
</div>

<?php } else { ?>
<div class="panel-group" id="accordion2">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field" >
                    Choose Fields for Medication<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_field" class="panel-collapse collapse in">
            <div class="panel-body">
                <?php
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT medication FROM field_config"));
                $value_config = ','.$get_field_config['medication'].',';
                $medtype_custom = get_config($dbc, 'medication_medtype_custom');
                $title_custom = get_config($dbc, 'medication_title_custom');
                $category_default = get_config($dbc, 'medication_category_default');
                $medication_contacts = array_filter(explode(',',get_config($dbc, 'medication_contacts'))); ?>

                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" disabled <?php if (strpos($value_config, ','."Medication Type".',') !== FALSE) { echo " checked"; } ?> value="Medication Type" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Medication Type<br />
                            Custom Medication Types (comma-separated): <input type="text" name="medtype_custom" value="<?= $medtype_custom ?>" class="form-control">
                        </td>
                        <td>
                            <input type="checkbox" disabled <?php if (strpos($value_config, ','."Category".',') !== FALSE) { echo " checked"; } ?> value="Category" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Category<br />
                            Default Category: <input type="text" name="category_default" value="<?= $category_default ?>" class="form-control">
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Heading".',') !== FALSE) { echo " checked"; } ?> value="Heading" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Heading
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Cost".',') !== FALSE) { echo " checked"; } ?> value="Cost" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Cost
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Description
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Quote Description" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Quote Description
                        </td>


                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Final Retail Price".',') !== FALSE) { echo " checked"; } ?> value="Final Retail Price" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Final Retail Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Admin Price".',') !== FALSE) { echo " checked"; } ?> value="Admin Price" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Admin Price
                        </td>
                        <td>
                           <input type="checkbox" <?php if (strpos($value_config, ','."Wholesale Price".',') !== FALSE) { echo " checked"; } ?> value="Wholesale Price" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Wholesale Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Commercial Price".',') !== FALSE) { echo " checked"; } ?> value="Commercial Price" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Commercial Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Client Price".',') !== FALSE) { echo " checked"; } ?> value="Client Price" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Client Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Minimum Billable".',') !== FALSE) { echo " checked"; } ?> value="Minimum Billable" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Minimum Billable
                        </td>

                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Estimated Hours".',') !== FALSE) { echo " checked"; } ?> value="Estimated Hours" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Estimated Hours
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Actual Hours".',') !== FALSE) { echo " checked"; } ?> value="Actual Hours" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Actual Hours
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."MSRP".',') !== FALSE) {
                            echo " checked"; } ?> value="MSRP" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;MSRP
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Medication Code".',') !== FALSE) {
                            echo " checked"; } ?> value="Medication Code" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Medication Code
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Invoice Description".',') !== FALSE) {
                            echo " checked"; } ?> value="Invoice Description" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Invoice Description
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Ticket Description".',') !== FALSE) {
                            echo " checked"; } ?> value="Ticket Description" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;<?= TICKET_NOUN ?> Description
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Name".',') !== FALSE) {
                            echo " checked"; } ?> value="Name" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Name
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Fee".',') !== FALSE) {
                            echo " checked"; } ?> value="Fee" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Fee
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Unit Price".',') !== FALSE) { echo " checked"; } ?> value="Unit Price" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Unit Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Unit Cost".',') !== FALSE) { echo " checked"; } ?> value="Unit Cost" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Unit Cost
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Rent Price".',') !== FALSE) { echo " checked"; } ?> value="Rent Price" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Rent Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Rental Days".',') !== FALSE) { echo " checked"; } ?> value="Rental Days" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Rental Days
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Rental Weeks".',') !== FALSE) { echo " checked"; } ?> value="Rental Weeks" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Rental Weeks
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Rental Months".',') !== FALSE) { echo " checked"; } ?> value="Rental Months" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Rental Months
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Rental Years".',') !== FALSE) { echo " checked"; } ?> value="Rental Years" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Rental Years
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Reminder/Alert".',') !== FALSE) { echo " checked"; } ?> value="Reminder/Alert" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Reminder/Alert
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Daily".',') !== FALSE) { echo " checked"; } ?> value="Daily" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Daily
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Weekly".',') !== FALSE) { echo " checked"; } ?> value="Weekly" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Weekly
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Monthly".',') !== FALSE) { echo " checked"; } ?> value="Monthly" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Monthly
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Annually".',') !== FALSE) { echo " checked"; } ?> value="Annually" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Annually
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."#Of Days".',') !== FALSE) { echo " checked"; } ?> value="#Of Days" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;#Of Days
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."#Of Hours".',') !== FALSE) { echo " checked"; } ?> value="#Of Hours" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;#Of Hours
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."#Of Kilometers".',') !== FALSE) { echo " checked"; } ?> value="#Of Kilometers" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;#Of Kilometers
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."#Of Miles".',') !== FALSE) { echo " checked"; } ?> value="#Of Miles" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;#Of Miles
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" disabled <?php if (strpos($value_config, ','."Title".',') !== FALSE) { echo " checked"; } ?> value="Title" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Title<br />
                            Custom Label: <input type="text" name="title_custom" value="<?= $title_custom ?>" class="form-control">
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Uploader".',') !== FALSE) { echo " checked"; } ?> value="Uploader" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Uploader
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Link".',') !== FALSE) { echo " checked"; } ?> value="Link" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Link
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Staff".',') !== FALSE) { echo " checked"; } ?> value="Staff" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Staff
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Client".',') !== FALSE) { echo " checked"; } ?> value="Client" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Client<br />
							Categories of Clients:
							<select class="chosen-select-deselect" multiple name="client_category[]">
								<?php foreach(array_filter(array_unique(explode(',',get_config($dbc, 'all_contact_tabs')))) as $category) { ?>
									<option <?= count($medication_contacts) == 0 || in_array($category, $medication_contacts) ? 'selected' : '' ?> value="<?= $category ?>"><?= $category ?></option>
								<?php } ?>
							</select>
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Delivery Method".',') !== FALSE) { echo " checked"; } ?> value="Delivery Method" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Delivery Method
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Dosage".',') !== FALSE) { echo " checked"; } ?> value="Dosage" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Dosage
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Side Effects".',') !== FALSE) { echo " checked"; } ?> value="Side Effects" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Side Effects
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Administration Times".',') !== FALSE) { echo " checked"; } ?> value="Administration Times" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Administration Times
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Start Date".',') !== FALSE) { echo " checked"; } ?> value="Start Date" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Start Date
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."End Date".',') !== FALSE) { echo " checked"; } ?> value="End Date" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;End Date
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Reminder Date".',') !== FALSE) { echo " checked"; } ?> value="Reminder Date" style="height: 20px; width: 20px;" name="medication[]">&nbsp;&nbsp;Reminder Date
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
                    Choose Fields for Medication Dashboard<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_dashboard" class="panel-collapse collapse">
            <div class="panel-body">
                <?php
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT medication_dashboard FROM field_config"));
                $value_config = ','.$get_field_config['medication_dashboard'].',';
                ?>

                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" disabled <?php if (strpos($value_config, ','."Medication Type".',') !== FALSE) { echo " checked"; } ?> value="Medication Type" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;Medication Type
                        </td>
                        <td>
                            <input type="checkbox" disabled <?php if (strpos($value_config, ','."Category".',') !== FALSE) { echo " checked"; } ?> value="Category" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;Category
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Heading".',') !== FALSE) { echo " checked"; } ?> value="Heading" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;Heading
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Cost".',') !== FALSE) { echo " checked"; } ?> value="Cost" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;Cost
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;Description
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Quote Description" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;Quote Description
                        </td>

                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Final Retail Price".',') !== FALSE) { echo " checked"; } ?> value="Final Retail Price" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;Final Retail Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Admin Price".',') !== FALSE) { echo " checked"; } ?> value="Admin Price" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;Admin Price
                        </td>
                        <td>
                           <input type="checkbox" <?php if (strpos($value_config, ','."Wholesale Price".',') !== FALSE) { echo " checked"; } ?> value="Wholesale Price" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;Wholesale Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Commercial Price".',') !== FALSE) { echo " checked"; } ?> value="Commercial Price" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;Commercial Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Client Price".',') !== FALSE) { echo " checked"; } ?> value="Client Price" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;Client Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Minimum Billable".',') !== FALSE) { echo " checked"; } ?> value="Minimum Billable" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;Minimum Billable
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Estimated Hours".',') !== FALSE) { echo " checked"; } ?> value="Estimated Hours" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;Estimated Hours
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Actual Hours".',') !== FALSE) { echo " checked"; } ?> value="Actual Hours" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;Actual Hours
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."MSRP".',') !== FALSE) {
                            echo " checked"; } ?> value="MSRP" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;MSRP
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Medication Code".',') !== FALSE) {
                            echo " checked"; } ?> value="Medication Code" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;Medication Code
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Invoice Description".',') !== FALSE) {
                            echo " checked"; } ?> value="Invoice Description" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;Invoice Description
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Ticket Description".',') !== FALSE) {
                            echo " checked"; } ?> value="Ticket Description" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;<?= TICKET_NOUN ?> Description
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Name".',') !== FALSE) {
                            echo " checked"; } ?> value="Name" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;Name
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Fee".',') !== FALSE) {
                            echo " checked"; } ?> value="Fee" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;Fee
                        </td>
                        <td>
                            <input type="checkbox" disabled <?php if (strpos($value_config, ','."Title".',') !== FALSE) { echo " checked"; } ?> value="Title" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;Title
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Uploader".',') !== FALSE) { echo " checked"; } ?> value="Uploader" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;Uploader
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Link".',') !== FALSE) { echo " checked"; } ?> value="Link" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;Link
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Staff".',') !== FALSE) { echo " checked"; } ?> value="Staff" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;Staff
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Client".',') !== FALSE) { echo " checked"; } ?> value="Client" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;Client
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Delivery Method".',') !== FALSE) { echo " checked"; } ?> value="Delivery Method" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;Delivery Method
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Side Effects".',') !== FALSE) { echo " checked"; } ?> value="Side Effects" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;Side Effects
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Administration Times".',') !== FALSE) { echo " checked"; } ?> value="Administration Times" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;Administration Times
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Dosage".',') !== FALSE) { echo " checked"; } ?> value="Dosage" style="height: 20px; width: 20px;" name="medication_dashboard[]">&nbsp;&nbsp;Dosage
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

</div>

<div class="form-group">
    <div class="col-sm-6"><a href="medication.php" class="btn config-btn btn-lg">Back</a></div>
	<div class="col-sm-6"><button type="submit" name="submit" value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button></div>
	<div class="clearfix"></div>
</div>
<?php } ?>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>