<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('staff_documents');
error_reporting(0);

if (isset($_POST['submit'])) {
    $staff_documents = implode(',',$_POST['staff_documents']);
    $staff_documents_dashboard = implode(',',$_POST['staff_documents_dashboard']);

    if (strpos(','.$staff_documents.',',','.'Staff Documents Type,Category,Title'.',') === false) {
        $staff_documents = 'Staff Documents Type,Category,Title,'.$staff_documents;
    }
    if (strpos(','.$staff_documents_dashboard.',',','.'staff Documents Type,Category,Title'.',') === false) {
        $staff_documents_dashboard = 'Staff Documents Type,Category,Title,'.$staff_documents_dashboard;
    }

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(fieldconfigid) AS fieldconfigid FROM field_config"));
    if($get_field_config['fieldconfigid'] > 0) {
        $query_update_employee = "UPDATE `field_config` SET staff_documents = '$staff_documents', staff_documents_dashboard = '$staff_documents_dashboard' WHERE `fieldconfigid` = 1";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config` (`staff_documents`, `staff_documents_dashboard`) VALUES ('$staff_documents', '$staff_documents_dashboard')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    echo '<script type="text/javascript"> window.location.replace("field_config_staff_documents.php"); </script>';

}
?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<h1>Staff Documents</h1>
<div class="gap-top double-gap-bottom"><a href="staff_documents.php" class="btn config-btn">Back to Dashboard</a></div>
<!--<a href="#" class="btn config-btn" onclick="history.go(-1);return false;">Back</a>-->

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<div class="panel-group" id="accordion2">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to add or remove the fields that will be visible only when you add Staff Documents."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field" >
                    Choose Fields for Staff Documents<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_field" class="panel-collapse collapse">
            <div class="panel-body" id="no-more-tables">
                <?php
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT staff_documents FROM field_config"));
                $value_config = ','.$get_field_config['staff_documents'].',';
                ?>

                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" disabled <?php if (strpos($value_config, ','."Staff Documents Type".',') !== FALSE) { echo " checked"; } ?> value="Staff Documents Type" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Staff Document Type
                        </td>
                        <td>
                            <input type="checkbox" disabled <?php if (strpos($value_config, ','."Category".',') !== FALSE) { echo " checked"; } ?> value="Category" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Category
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Heading".',') !== FALSE) { echo " checked"; } ?> value="Heading" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Heading
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Cost".',') !== FALSE) { echo " checked"; } ?> value="Cost" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Cost
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Description
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Quote Description" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Quote Description
                        </td>

                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Final Retail Price".',') !== FALSE) { echo " checked"; } ?> value="Final Retail Price" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Final Retail Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Admin Price".',') !== FALSE) { echo " checked"; } ?> value="Admin Price" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Admin Price
                        </td>
                        <td>
                           <input type="checkbox" <?php if (strpos($value_config, ','."Wholesale Price".',') !== FALSE) { echo " checked"; } ?> value="Wholesale Price" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Wholesale Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Commercial Price".',') !== FALSE) { echo " checked"; } ?> value="Commercial Price" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Commercial Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Staff Price".',') !== FALSE) { echo " checked"; } ?> value="Staff Price" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Staff Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Minimum Billable".',') !== FALSE) { echo " checked"; } ?> value="Minimum Billable" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Minimum Billable
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Estimated Hours".',') !== FALSE) { echo " checked"; } ?> value="Estimated Hours" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Estimated Hours
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Actual Hours".',') !== FALSE) { echo " checked"; } ?> value="Actual Hours" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Actual Hours
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."MSRP".',') !== FALSE) {
                            echo " checked"; } ?> value="MSRP" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;MSRP
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Staff Documents Code".',') !== FALSE) {
                            echo " checked"; } ?> value="Staff Documents Code" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Staff Documents Code
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Invoice Description".',') !== FALSE) {
                            echo " checked"; } ?> value="Invoice Description" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Invoice Description
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Ticket Description".',') !== FALSE) {
                            echo " checked"; } ?> value="Ticket Description" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;<?= TICKET_NOUN ?> Description
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Name".',') !== FALSE) {
                            echo " checked"; } ?> value="Name" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Name
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Fee".',') !== FALSE) {
                            echo " checked"; } ?> value="Fee" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Fee
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Unit Price".',') !== FALSE) { echo " checked"; } ?> value="Unit Price" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Unit Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Unit Cost".',') !== FALSE) { echo " checked"; } ?> value="Unit Cost" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Unit Cost
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Rent Price".',') !== FALSE) { echo " checked"; } ?> value="Rent Price" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Rent Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Rental Days".',') !== FALSE) { echo " checked"; } ?> value="Rental Days" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Rental Days
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Rental Weeks".',') !== FALSE) { echo " checked"; } ?> value="Rental Weeks" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Rental Weeks
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Rental Months".',') !== FALSE) { echo " checked"; } ?> value="Rental Months" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Rental Months
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Rental Years".',') !== FALSE) { echo " checked"; } ?> value="Rental Years" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Rental Years
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Reminder/Alert".',') !== FALSE) { echo " checked"; } ?> value="Reminder/Alert" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Reminder/Alert
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Daily".',') !== FALSE) { echo " checked"; } ?> value="Daily" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Daily
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Weekly".',') !== FALSE) { echo " checked"; } ?> value="Weekly" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Weekly
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Monthly".',') !== FALSE) { echo " checked"; } ?> value="Monthly" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Monthly
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Annually".',') !== FALSE) { echo " checked"; } ?> value="Annually" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Annually
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."#Of Days".',') !== FALSE) { echo " checked"; } ?> value="#Of Days" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;#Of Days
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."#Of Hours".',') !== FALSE) { echo " checked"; } ?> value="#Of Hours" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;#Of Hours
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."#Of Kilometers".',') !== FALSE) { echo " checked"; } ?> value="#Of Kilometers" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;#Of Kilometers
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."#Of Miles".',') !== FALSE) { echo " checked"; } ?> value="#Of Miles" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;#Of Miles
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" disabled <?php if (strpos($value_config, ','."Title".',') !== FALSE) { echo " checked"; } ?> value="Title" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Title
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Uploader".',') !== FALSE) { echo " checked"; } ?> value="Uploader" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Uploader
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Link".',') !== FALSE) { echo " checked"; } ?> value="Link" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Link
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Staff".',') !== FALSE) { echo " checked"; } ?> value="Staff" style="height: 20px; width: 20px;" name="staff_documents[]">&nbsp;&nbsp;Staff
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to add or remove the fields that will be visible on the Staff Documents Dashboard."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_dashboard" >
                    Choose Fields for Staff Documents Dashboard<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_dashboard" class="panel-collapse collapse">
            <div class="panel-body" id="no-more-tables">
                <?php
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT staff_documents_dashboard FROM field_config"));
                $value_config = ','.$get_field_config['staff_documents_dashboard'].',';
                ?>

                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" disabled <?php if (strpos($value_config, ','."Staff Documents Type".',') !== FALSE) { echo " checked"; } ?> value="Staff Documents Type" style="height: 20px; width: 20px;" name="staff_documents_dashboard[]">&nbsp;&nbsp;Staff Document Type
                        </td>
                        <td>
                            <input type="checkbox" disabled <?php if (strpos($value_config, ','."Category".',') !== FALSE) { echo " checked"; } ?> value="Category" style="height: 20px; width: 20px;" name="staff_documents_dashboard[]">&nbsp;&nbsp;Category
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Heading".',') !== FALSE) { echo " checked"; } ?> value="Heading" style="height: 20px; width: 20px;" name="staff_documents_dashboard[]">&nbsp;&nbsp;Heading
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Cost".',') !== FALSE) { echo " checked"; } ?> value="Cost" style="height: 20px; width: 20px;" name="staff_documents_dashboard[]">&nbsp;&nbsp;Cost
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="staff_documents_dashboard[]">&nbsp;&nbsp;Description
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Quote Description" style="height: 20px; width: 20px;" name="staff_documents_dashboard[]">&nbsp;&nbsp;Quote Description
                        </td>

                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Final Retail Price".',') !== FALSE) { echo " checked"; } ?> value="Final Retail Price" style="height: 20px; width: 20px;" name="staff_documents_dashboard[]">&nbsp;&nbsp;Final Retail Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Admin Price".',') !== FALSE) { echo " checked"; } ?> value="Admin Price" style="height: 20px; width: 20px;" name="staff_documents_dashboard[]">&nbsp;&nbsp;Admin Price
                        </td>
                        <td>
                           <input type="checkbox" <?php if (strpos($value_config, ','."Wholesale Price".',') !== FALSE) { echo " checked"; } ?> value="Wholesale Price" style="height: 20px; width: 20px;" name="staff_documents_dashboard[]">&nbsp;&nbsp;Wholesale Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Commercial Price".',') !== FALSE) { echo " checked"; } ?> value="Commercial Price" style="height: 20px; width: 20px;" name="staff_documents_dashboard[]">&nbsp;&nbsp;Commercial Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Staff Price".',') !== FALSE) { echo " checked"; } ?> value="Staff Price" style="height: 20px; width: 20px;" name="staff_documents_dashboard[]">&nbsp;&nbsp;Staff Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Minimum Billable".',') !== FALSE) { echo " checked"; } ?> value="Minimum Billable" style="height: 20px; width: 20px;" name="staff_documents_dashboard[]">&nbsp;&nbsp;Minimum Billable
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Estimated Hours".',') !== FALSE) { echo " checked"; } ?> value="Estimated Hours" style="height: 20px; width: 20px;" name="staff_documents_dashboard[]">&nbsp;&nbsp;Estimated Hours
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Actual Hours".',') !== FALSE) { echo " checked"; } ?> value="Actual Hours" style="height: 20px; width: 20px;" name="staff_documents_dashboard[]">&nbsp;&nbsp;Actual Hours
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."MSRP".',') !== FALSE) {
                            echo " checked"; } ?> value="MSRP" style="height: 20px; width: 20px;" name="staff_documents_dashboard[]">&nbsp;&nbsp;MSRP
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Staff Documents Code".',') !== FALSE) {
                            echo " checked"; } ?> value="Staff Documents Code" style="height: 20px; width: 20px;" name="staff_documents_dashboard[]">&nbsp;&nbsp;Staff Documents Code
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Invoice Description".',') !== FALSE) {
                            echo " checked"; } ?> value="Invoice Description" style="height: 20px; width: 20px;" name="staff_documents_dashboard[]">&nbsp;&nbsp;Invoice Description
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Ticket Description".',') !== FALSE) {
                            echo " checked"; } ?> value="Ticket Description" style="height: 20px; width: 20px;" name="staff_documents_dashboard[]">&nbsp;&nbsp;<?= TICKET_NOUN ?> Description
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Name".',') !== FALSE) {
                            echo " checked"; } ?> value="Name" style="height: 20px; width: 20px;" name="staff_documents_dashboard[]">&nbsp;&nbsp;Name
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Fee".',') !== FALSE) {
                            echo " checked"; } ?> value="Fee" style="height: 20px; width: 20px;" name="staff_documents_dashboard[]">&nbsp;&nbsp;Fee
                        </td>
                        <td>
                            <input type="checkbox" disabled <?php if (strpos($value_config, ','."Title".',') !== FALSE) { echo " checked"; } ?> value="Title" style="height: 20px; width: 20px;" name="staff_documents_dashboard[]">&nbsp;&nbsp;Title
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Uploader".',') !== FALSE) { echo " checked"; } ?> value="Uploader" style="height: 20px; width: 20px;" name="staff_documents_dashboard[]">&nbsp;&nbsp;Uploader
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Link".',') !== FALSE) { echo " checked"; } ?> value="Link" style="height: 20px; width: 20px;" name="staff_documents_dashboard[]">&nbsp;&nbsp;Link
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Staff".',') !== FALSE) { echo " checked"; } ?> value="Staff" style="height: 20px; width: 20px;" name="staff_documents_dashboard[]">&nbsp;&nbsp;Staff
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

</div>

<div class="form-group">
    <div class="col-sm-6">
        <span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Clicking this will discard your Staff Document settings."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
        <a href="staff_documents.php" class="btn config-btn btn-lg">Back</a>
		<!--<a href="#" class="btn config-btn btn-lg pull-right" onclick="history.go(-1);return false;">Back</a>-->
	</div>
	<div class="col-sm-6">
        <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
		<span class="popover-examples list-inline pull-right" style="margin:15px 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click this to finalize your Staff Document settings."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
    </div>
</div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>