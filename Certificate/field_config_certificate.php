<?php
/*
Dashboard
*/
include ('../include.php');
error_reporting(0);
checkAuthorised('certificate');

if (isset($_POST['submit'])) {
    $certificate = implode(',',$_POST['certificate']);
    $certificate_dashboard = implode(',',$_POST['certificate_dashboard']);
	$certificate_reminder_contact = filter_var($_POST['certificate_reminder_contact'], FILTER_SANITIZE_STRING);
	$certificate_reminder_subject = mysqli_real_escape_string($dbc, $_POST['certificate_reminder_subject']);
	$certificate_reminder_body = mysqli_real_escape_string($dbc, $_POST['certificate_reminder_body']);

    if (strpos(','.$certificate.',',','.'Certificate Type,Title'.',') === false) {
        $certificate = 'Certificate Type,Title,'.$certificate;
    }
    if (strpos(','.$certificate_dashboard.',',','.'Certificate Type,Title'.',') === false) {
        $certificate_dashboard = 'Certificate Type,Title,'.$certificate_dashboard;
    }

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(fieldconfigid) AS fieldconfigid FROM field_config"));
    if($get_field_config['fieldconfigid'] > 0) {
        $query_update_employee = "UPDATE `field_config` SET certificate = '$certificate', certificate_dashboard = '$certificate_dashboard' WHERE `fieldconfigid` = 1";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config` (`certificate`, `certificate_dashboard`) VALUES ('$certificate', '$certificate_dashboard')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc, "select count(configid) config from general_configuration where name='certificate_reminder_contact'"));
	if($get_field_config['config'] > 0) {
		$sql = "update general_configuration set value='$certificate_reminder_contact' where name='certificate_reminder_contact'";
	}
	else {
		$sql = "insert into general_configuration (name, value) VALUES ('certificate_reminder_contact', '$certificate_reminder_contact')";
	}
	$result = mysqli_query($dbc, $sql);

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc, "select count(configid) config from general_configuration where name='certificate_reminder_subject'"));
	if($get_field_config['config'] > 0) {
		$sql = "update general_configuration set value='$certificate_reminder_subject' where name='certificate_reminder_subject'";
	}
	else {
		$sql = "insert into general_configuration (name, value) VALUES ('certificate_reminder_subject', '$certificate_reminder_subject')";
	}
	$result = mysqli_query($dbc, $sql);

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc, "select count(configid) config from general_configuration where name='certificate_reminder_body'"));
	if($get_field_config['config'] > 0) {
		$sql = "update general_configuration set value='$certificate_reminder_body' where name='certificate_reminder_body'";
	}
	else {
		$sql = "insert into general_configuration (name, value) VALUES ('certificate_reminder_body', '$certificate_reminder_body')";
	}
	$result = mysqli_query($dbc, $sql);

    echo '<script type="text/javascript"> window.location.replace("field_config_certificate.php"); </script>';

}
?>
</head>
<script>
$(document).on('change.select2', 'select[name="certificate_reminder_contact"]', function() {
    if(this.value == 'MANUAL') {
        $('.manager_email').hide(); $('.manager_manual_email').show();
        $('input[name=certificate_reminder_contact]').removeAttr('disabled').val('');
    }
});
</script>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<h1>Certificate</h1>
<div class="gap-top double-gap-bottom"><a href="certificate.php" class="btn config-btn">Back to Dashboard</a></div>
<!--<a href="#" class="btn config-btn" onclick="history.go(-1);return false;">Back</a>-->

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<div class="panel-group" id="accordion2">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here to add or remove the fields that will be visible only when you add a certificate."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field" >
                    Choose Fields for Certificate<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_field" class="panel-collapse collapse">
            <div class="panel-body no-more-tables">
                <?php
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT certificate FROM field_config"));
                $value_config = ','.$get_field_config['certificate'].',';
                ?>

                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" disabled <?php if (strpos($value_config, ','."Certificate Type".',') !== FALSE) { echo " checked"; } ?> value="Certificate Type" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Certificate Type
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Category".',') !== FALSE) { echo " checked"; } ?> value="Category" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Category
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Heading".',') !== FALSE) { echo " checked"; } ?> value="Heading" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Heading
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Cost".',') !== FALSE) { echo " checked"; } ?> value="Cost" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Cost
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Description
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Quote Description" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Quote Description
                        </td>

                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Final Retail Price".',') !== FALSE) { echo " checked"; } ?> value="Final Retail Price" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Final Retail Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Admin Price".',') !== FALSE) { echo " checked"; } ?> value="Admin Price" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Admin Price
                        </td>
                        <td>
                           <input type="checkbox" <?php if (strpos($value_config, ','."Wholesale Price".',') !== FALSE) { echo " checked"; } ?> value="Wholesale Price" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Wholesale Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Commercial Price".',') !== FALSE) { echo " checked"; } ?> value="Commercial Price" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Commercial Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Client Price".',') !== FALSE) { echo " checked"; } ?> value="Client Price" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Client Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Minimum Billable".',') !== FALSE) { echo " checked"; } ?> value="Minimum Billable" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Minimum Billable
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Estimated Hours".',') !== FALSE) { echo " checked"; } ?> value="Estimated Hours" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Estimated Hours
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Actual Hours".',') !== FALSE) { echo " checked"; } ?> value="Actual Hours" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Actual Hours
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."MSRP".',') !== FALSE) {
                            echo " checked"; } ?> value="MSRP" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;MSRP
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Certificate Code".',') !== FALSE) {
                            echo " checked"; } ?> value="Certificate Code" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Certificate Code
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Invoice Description".',') !== FALSE) {
                            echo " checked"; } ?> value="Invoice Description" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Invoice Description
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Ticket Description".',') !== FALSE) {
                            echo " checked"; } ?> value="Ticket Description" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;<?= TICKET_NOUN ?> Description
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Name".',') !== FALSE) {
                            echo " checked"; } ?> value="Name" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Name
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Fee".',') !== FALSE) {
                            echo " checked"; } ?> value="Fee" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Fee
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Unit Price".',') !== FALSE) { echo " checked"; } ?> value="Unit Price" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Unit Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Unit Cost".',') !== FALSE) { echo " checked"; } ?> value="Unit Cost" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Unit Cost
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Rent Price".',') !== FALSE) { echo " checked"; } ?> value="Rent Price" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Rent Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Rental Days".',') !== FALSE) { echo " checked"; } ?> value="Rental Days" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Rental Days
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Rental Weeks".',') !== FALSE) { echo " checked"; } ?> value="Rental Weeks" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Rental Weeks
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Rental Months".',') !== FALSE) { echo " checked"; } ?> value="Rental Months" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Rental Months
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Rental Years".',') !== FALSE) { echo " checked"; } ?> value="Rental Years" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Rental Years
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Certificate Reminder Email".',') !== FALSE) { echo " checked"; } ?> value="Certificate Reminder Email" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Certificate Reminder Email
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Reminder/Alert".',') !== FALSE) { echo " checked"; } ?> value="Reminder/Alert" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Reminder/Alert
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Daily".',') !== FALSE) { echo " checked"; } ?> value="Daily" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Daily
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Weekly".',') !== FALSE) { echo " checked"; } ?> value="Weekly" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Weekly
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Monthly".',') !== FALSE) { echo " checked"; } ?> value="Monthly" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Monthly
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Annually".',') !== FALSE) { echo " checked"; } ?> value="Annually" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Annually
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."#Of Days".',') !== FALSE) { echo " checked"; } ?> value="#Of Days" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;#Of Days
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."#Of Hours".',') !== FALSE) { echo " checked"; } ?> value="#Of Hours" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;#Of Hours
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."#Of Kilometers".',') !== FALSE) { echo " checked"; } ?> value="#Of Kilometers" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;#Of Kilometers
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."#Of Miles".',') !== FALSE) { echo " checked"; } ?> value="#Of Miles" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;#Of Miles
                        </td>
                        <td>
                            <input type="checkbox" disabled <?php if (strpos($value_config, ','."Title".',') !== FALSE) { echo " checked"; } ?> value="Title" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Title
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Uploader".',') !== FALSE) { echo " checked"; } ?> value="Uploader" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Uploader
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Link".',') !== FALSE) { echo " checked"; } ?> value="Link" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Link
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Staff".',') !== FALSE) { echo " checked"; } ?> value="Staff" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Staff
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Projects".',') !== FALSE) { echo " checked"; } ?> value="Projects" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Project
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Client Project".',') !== FALSE) { echo " checked"; } ?> value="Client Project" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Client Project
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Jobs".',') !== FALSE) { echo " checked"; } ?> value="Jobs" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Job
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Issue Date".',') !== FALSE) { echo " checked"; } ?> value="Issue Date" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Issue Date
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Reminder Date".',') !== FALSE) { echo " checked"; } ?> value="Reminder Date" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Reminder Date
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Expiry Date".',') !== FALSE) { echo " checked"; } ?> value="Expiry Date" style="height: 20px; width: 20px;" name="certificate[]">&nbsp;&nbsp;Expiry Date
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here to add or remove the fields that will be visible on the Certificate Dashboard."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_dashboard" >
                    Choose Fields for Certificate Dashboard<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_dashboard" class="panel-collapse collapse">
            <div class="panel-body no-more-tables">
                <?php
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT certificate_dashboard FROM field_config"));
                $value_config = ','.$get_field_config['certificate_dashboard'].',';
                ?>

                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" disabled <?php if (strpos($value_config, ','."Certificate Type".',') !== FALSE) { echo " checked"; } ?> value="Certificate Type" style="height: 20px; width: 20px;" name="certificate_dashboard[]">&nbsp;&nbsp;Certificate Type
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Category".',') !== FALSE) { echo " checked"; } ?> value="Category" style="height: 20px; width: 20px;" name="certificate_dashboard[]">&nbsp;&nbsp;Category
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Heading".',') !== FALSE) { echo " checked"; } ?> value="Heading" style="height: 20px; width: 20px;" name="certificate_dashboard[]">&nbsp;&nbsp;Heading
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Cost".',') !== FALSE) { echo " checked"; } ?> value="Cost" style="height: 20px; width: 20px;" name="certificate_dashboard[]">&nbsp;&nbsp;Cost
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="certificate_dashboard[]">&nbsp;&nbsp;Description
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Quote Description" style="height: 20px; width: 20px;" name="certificate_dashboard[]">&nbsp;&nbsp;Quote Description
                        </td>

                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Final Retail Price".',') !== FALSE) { echo " checked"; } ?> value="Final Retail Price" style="height: 20px; width: 20px;" name="certificate_dashboard[]">&nbsp;&nbsp;Final Retail Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Admin Price".',') !== FALSE) { echo " checked"; } ?> value="Admin Price" style="height: 20px; width: 20px;" name="certificate_dashboard[]">&nbsp;&nbsp;Admin Price
                        </td>
                        <td>
                           <input type="checkbox" <?php if (strpos($value_config, ','."Wholesale Price".',') !== FALSE) { echo " checked"; } ?> value="Wholesale Price" style="height: 20px; width: 20px;" name="certificate_dashboard[]">&nbsp;&nbsp;Wholesale Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Commercial Price".',') !== FALSE) { echo " checked"; } ?> value="Commercial Price" style="height: 20px; width: 20px;" name="certificate_dashboard[]">&nbsp;&nbsp;Commercial Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Client Price".',') !== FALSE) { echo " checked"; } ?> value="Client Price" style="height: 20px; width: 20px;" name="certificate_dashboard[]">&nbsp;&nbsp;Client Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Minimum Billable".',') !== FALSE) { echo " checked"; } ?> value="Minimum Billable" style="height: 20px; width: 20px;" name="certificate_dashboard[]">&nbsp;&nbsp;Minimum Billable
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Estimated Hours".',') !== FALSE) { echo " checked"; } ?> value="Estimated Hours" style="height: 20px; width: 20px;" name="certificate_dashboard[]">&nbsp;&nbsp;Estimated Hours
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Actual Hours".',') !== FALSE) { echo " checked"; } ?> value="Actual Hours" style="height: 20px; width: 20px;" name="certificate_dashboard[]">&nbsp;&nbsp;Actual Hours
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."MSRP".',') !== FALSE) {
                            echo " checked"; } ?> value="MSRP" style="height: 20px; width: 20px;" name="certificate_dashboard[]">&nbsp;&nbsp;MSRP
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Certificate Code".',') !== FALSE) {
                            echo " checked"; } ?> value="Certificate Code" style="height: 20px; width: 20px;" name="certificate_dashboard[]">&nbsp;&nbsp;Certificate Code
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Invoice Description".',') !== FALSE) {
                            echo " checked"; } ?> value="Invoice Description" style="height: 20px; width: 20px;" name="certificate_dashboard[]">&nbsp;&nbsp;Invoice Description
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Ticket Description".',') !== FALSE) {
                            echo " checked"; } ?> value="Ticket Description" style="height: 20px; width: 20px;" name="certificate_dashboard[]">&nbsp;&nbsp;<?= TICKET_NOUN ?> Description
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Name".',') !== FALSE) {
                            echo " checked"; } ?> value="Name" style="height: 20px; width: 20px;" name="certificate_dashboard[]">&nbsp;&nbsp;Name
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Fee".',') !== FALSE) {
                            echo " checked"; } ?> value="Fee" style="height: 20px; width: 20px;" name="certificate_dashboard[]">&nbsp;&nbsp;Fee
                        </td>
                        <td>
                            <input type="checkbox" disabled <?php if (strpos($value_config, ','."Title".',') !== FALSE) { echo " checked"; } ?> value="Title" style="height: 20px; width: 20px;" name="certificate_dashboard[]">&nbsp;&nbsp;Title
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Uploader".',') !== FALSE) { echo " checked"; } ?> value="Uploader" style="height: 20px; width: 20px;" name="certificate_dashboard[]">&nbsp;&nbsp;Uploader
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Link".',') !== FALSE) { echo " checked"; } ?> value="Link" style="height: 20px; width: 20px;" name="certificate_dashboard[]">&nbsp;&nbsp;Link
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Staff".',') !== FALSE) { echo " checked"; } ?> value="Staff" style="height: 20px; width: 20px;" name="certificate_dashboard[]">&nbsp;&nbsp;Staff / Project
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Issue Date".',') !== FALSE) { echo " checked"; } ?> value="Issue Date" style="height: 20px; width: 20px;" name="certificate_dashboard[]">&nbsp;&nbsp;Issue Date
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Reminder Date".',') !== FALSE) { echo " checked"; } ?> value="Reminder Date" style="height: 20px; width: 20px;" name="certificate_dashboard[]">&nbsp;&nbsp;Reminder Date
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Expiry Date".',') !== FALSE) { echo " checked"; } ?> value="Expiry Date" style="height: 20px; width: 20px;" name="certificate_dashboard[]">&nbsp;&nbsp;Expiry Date
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here to change the preferences of email reminders."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_options" >
                    Certificate Email Preferences<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_options" class="panel-collapse collapse">
            <div class="panel-body no-more-tables">
				<div class="form-group">
					<?php
					$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT certificate FROM field_config"));
					$value_config = ','.$get_field_config['certificate'].',';
					?>

					<label for="certificate_reminder_contact" class="col-sm-4 control-label">Manager Email for Reminders</label>
					<div class="col-sm-8">
						<div class="col-sm-12 manager_email">
							<select name="certificate_reminder_contact" class="chosen-select-deselect"><option></option>
								<option value="MANUAL">Add Manual Address</option>
								<?php $staff_result = mysqli_query($dbc, "SELECT first_name, last_name, contactid FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `deleted`=0 AND `status`=1");
								$reminder_email = get_config($dbc, 'certificate_reminder_contact');
								while($row = mysqli_fetch_array($staff_result)) {
									echo "<option ".($reminder_email == $row['contactid'] ? "selected " : "")."value='{$row['contactid']}'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name'])."</option>";
								}
								if(filter_var($reminder_email, FILTER_VALIDATE_EMAIL)) {
									echo "<option selected value='$reminder_email'>".$reminder_email."</option>";
								} ?>
							</select>
						</div>
						<div class="col-sm-11 manager_manual_email" style="display:none;"><input name="certificate_reminder_contact" disabled value="" type="text" class="form-control"></div>
						<div class="col-sm-1 manager_manual_email" style="display:none;"><a href="" onclick="$('select[name=certificate_reminder_contact]').val('').trigger('change.select2'); $('.manager_email').show(); $('.manager_manual_email').hide(); $('input[name=certificate_reminder_contact]').prop('disabled','disabled'); return false;"><img src="<?= WEBSITE_URL ?>/img/remove.png"></a></div>
					</div>
				</div>

				<div class="form-group">
					<?php $subject = get_config($dbc, 'certificate_reminder_subject');
					if($subject == '') {
						$subject = "Reminder Email regarding expiry of [TITLE] on [EXPIRY]";
					} ?>
					<label for="certificate_reminder_subject" class="col-sm-4 control-label">Email Subject:</label>
					<div class="col-sm-8">
						<input type="text" name="certificate_reminder_subject" value="<?php echo $subject; ?>" class="form-control">
					</div>
				</div>

				<div class="form-group">
					<?php $body = get_config($dbc, 'certificate_reminder_body');
					if($body == '') {
						$body = "<p>[STAFF] received a [TITLE] on [ISSUE]. It will expire on [EXPIRY], and needs to be renewed by then. Please review the certificate through your ROOK Connect software.</p>
							<p>You have received this message because your email address is configured to receive reminders for this certificate.</p>";
					} ?>
					<label for="certificate_reminder_body" class="col-sm-4 control-label">Email Body:<br />
					<small>Use the following tags<br />
					Issue Date: [ISSUE]<br />
					Expiry Date: [EXPIRY]<br />
					Staff Name: [STAFF]<br />
					Certificate Type: [TYPE]<br />
					Certificate Title: [TITLE]<br />
					Description: [DESCRIPTION]</small></label>
					<div class="col-sm-8">
						<textarea name="certificate_reminder_body"><?php echo $body; ?></textarea>
					</div>
				</div>
            </div>
        </div>
    </div>

</div>

<div class="form-group">
    <div class="col-sm-4">
        <a href="certificate.php" class="btn config-btn btn-lg">Back</a>
		<!--<a href="#" class="btn config-btn btn-lg pull-right" onclick="history.go(-1);return false;">Back</a>-->
	</div>
	<div class="col-sm-8">
        <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
    </div>
</div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>