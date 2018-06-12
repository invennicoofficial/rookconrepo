<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('package');
error_reporting(0);

if (isset($_POST['submit'])) {
    $package = implode(',',$_POST['package']);
    $package_dashboard = implode(',',$_POST['package_dashboard']);

    //if (strpos(','.$package.',',','.'Service Type,Category,Heading'.',') === false) {
    //    $package = 'Service Type,Category,Heading,'.$package;
    //}
    //if (strpos(','.$package_dashboard.',',','.'Service Type,Category,Heading'.',') === false) {
    //    $package_dashboard = 'Service Type,Category,Heading,'.$package_dashboard;
    //}

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(fieldconfigid) AS fieldconfigid FROM field_config"));
    if($get_field_config['fieldconfigid'] > 0) {
        $query_update_employee = "UPDATE `field_config` SET package = '$package', package_dashboard = '$package_dashboard' WHERE `fieldconfigid` = 1";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config` (`package`, `package_dashboard`) VALUES ('$package', '$package_dashboard')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    echo '<script type="text/javascript"> window.location.replace("field_config_package.php"); </script>';

}
?>
<script>
$(document).ready(function(){
    $("#selectall").change(function(){
      $(".all_check").prop('checked', $(this).prop("checked"));
    });
});
</script>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<h1>Packages</h1>
<div class="gap-left gap-top double-gap-bottom"><a href="package.php" class="btn config-btn">Back to Dashboard</a></div>
<!--<a href="#" class="btn config-btn" onclick="history.go(-1);return false;">Back</a>-->

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<?php
$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT package FROM field_config"));
$value_config = ','.$get_field_config['package'].',';
?>

<div class="panel-group" id="accordion2">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field" >
                    Choose Fields for Packages<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_field" class="panel-collapse collapse">
            <div class="panel-body" id="no-more-tables">
				<span style='font-weight:bold;padding:10px;'>General Fields</span>
                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Service Type".',') !== FALSE) { echo " checked"; } ?> value="Service Type" style="height: 20px; width: 20px;" name="package[]">&nbsp;&nbsp;Service Type
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Category".',') !== FALSE) { echo " checked"; } ?> value="Category" style="height: 20px; width: 20px;" name="package[]">&nbsp;&nbsp;Category
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Heading".',') !== FALSE) { echo " checked"; } ?> value="Heading" style="height: 20px; width: 20px;" name="package[]">&nbsp;&nbsp;Heading
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Cost".',') !== FALSE) { echo " checked"; } ?> value="Cost" style="height: 20px; width: 20px;" name="package[]">&nbsp;&nbsp;Cost
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="package[]">&nbsp;&nbsp;Description
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Quote Description" style="height: 20px; width: 20px;" name="package[]">&nbsp;&nbsp;Quote Description
                        </td>

                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Final Retail Price".',') !== FALSE) { echo " checked"; } ?> value="Final Retail Price" style="height: 20px; width: 20px;" name="package[]">&nbsp;&nbsp;Final Retail Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Admin Price".',') !== FALSE) { echo " checked"; } ?> value="Admin Price" style="height: 20px; width: 20px;" name="package[]">&nbsp;&nbsp;Admin Price
                        </td>
                        <td>
                           <input type="checkbox" <?php if (strpos($value_config, ','."Wholesale Price".',') !== FALSE) { echo " checked"; } ?> value="Wholesale Price" style="height: 20px; width: 20px;" name="package[]">&nbsp;&nbsp;Wholesale Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Commercial Price".',') !== FALSE) { echo " checked"; } ?> value="Commercial Price" style="height: 20px; width: 20px;" name="package[]">&nbsp;&nbsp;Commercial Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Client Price".',') !== FALSE) { echo " checked"; } ?> value="Client Price" style="height: 20px; width: 20px;" name="package[]">&nbsp;&nbsp;Client Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Minimum Billable".',') !== FALSE) { echo " checked"; } ?> value="Minimum Billable" style="height: 20px; width: 20px;" name="package[]">&nbsp;&nbsp;Minimum Billable
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Estimated Hours".',') !== FALSE) { echo " checked"; } ?> value="Estimated Hours" style="height: 20px; width: 20px;" name="package[]">&nbsp;&nbsp;Estimated Hours
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Actual Hours".',') !== FALSE) { echo " checked"; } ?> value="Actual Hours" style="height: 20px; width: 20px;" name="package[]">&nbsp;&nbsp;Actual Hours
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."MSRP".',') !== FALSE) {
                            echo " checked"; } ?> value="MSRP" style="height: 20px; width: 20px;" name="package[]">&nbsp;&nbsp;MSRP
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Invoice Description".',') !== FALSE) {
                            echo " checked"; } ?> value="Invoice Description" style="height: 20px; width: 20px;" name="package[]">&nbsp;&nbsp;Invoice Description
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Ticket Description".',') !== FALSE) {
                            echo " checked"; } ?> value="Ticket Description" style="height: 20px; width: 20px;" name="package[]">&nbsp;&nbsp;<?= TICKET_NOUN ?> Description
                        </td>
                    </tr>
                </table>
				<span style='font-weight:bold;padding:10px;'>Inventory Fields</span>
				<table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Inventory Part Number".',') !== FALSE) { echo " checked"; } ?> value="Inventory Part Number" style="height: 20px; width: 20px;" name="package[]">&nbsp;&nbsp;Part Number
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_accor" >
                    Create Accordion<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_accor" class="panel-collapse collapse">
            <div class="panel-body" id="no-more-tables">
                <input type="checkbox" id="selectall"/> Select All
                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Assign Services".',') !== FALSE) { echo " checked"; } ?> class="all_check" value="Assign Services" style="height: 20px; width: 20px;" name="package[]">&nbsp;&nbsp;Services
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Assign Clients".',') !== FALSE) { echo " checked"; } ?> class="all_check" value="Assign Clients" style="height: 20px; width: 20px;" name="package[]">&nbsp;&nbsp;Clients
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Assign Customer".',') !== FALSE) { echo " checked"; } ?> class="all_check" value="Assign Customer" style="height: 20px; width: 20px;" name="package[]">&nbsp;&nbsp;Customer
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Assign Vendor".',') !== FALSE) { echo " checked"; } ?> class="all_check" value="Assign Vendor" style="height: 20px; width: 20px;" name="package[]">&nbsp;&nbsp;Vendor
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Assign Inventory".',') !== FALSE) { echo " checked"; } ?> class="all_check" value="Assign Inventory" style="height: 20px; width: 20px;" name="package[]">&nbsp;&nbsp;Inventory
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Assign Equipment".',') !== FALSE) { echo " checked"; } ?> class="all_check" value="Assign Equipment" style="height: 20px; width: 20px;" name="package[]">&nbsp;&nbsp;Equipment
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Assign Equipment by Category".',') !== FALSE) { echo " checked"; } ?> value="Assign Equipment by Category" style="height: 20px; width: 20px;" name="package[]">&nbsp;&nbsp;Equipment by Category
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Assign Staff".',') !== FALSE) { echo " checked"; } ?> class="all_check" value="Assign Staff" style="height: 20px; width: 20px;" name="package[]">&nbsp;&nbsp;Staff
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Assign Staff Position".',') !== FALSE) { echo " checked"; } ?> value="Assign Staff Position" style="height: 20px; width: 20px;" name="package[]">&nbsp;&nbsp;Staff Position
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Assign Contractor".',') !== FALSE) { echo " checked"; } ?> class="all_check" value="Assign Contractor" style="height: 20px; width: 20px;" name="package[]">&nbsp;&nbsp;Contractor
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
                    Choose Fields for Packages Dashboard<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_dashboard" class="panel-collapse collapse">
            <div class="panel-body" id="no-more-tables">

                <?php
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT package_dashboard FROM field_config"));
                $value_config = ','.$get_field_config['package_dashboard'].',';
                ?>
                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Service Type".',') !== FALSE) { echo " checked"; } ?> value="Service Type" style="height: 20px; width: 20px;" name="package_dashboard[]">&nbsp;&nbsp;Service Type
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Category".',') !== FALSE) { echo " checked"; } ?> value="Category" style="height: 20px; width: 20px;" name="package_dashboard[]">&nbsp;&nbsp;Category
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Heading".',') !== FALSE) { echo " checked"; } ?> value="Heading" style="height: 20px; width: 20px;" name="package_dashboard[]">&nbsp;&nbsp;Heading
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Cost".',') !== FALSE) { echo " checked"; } ?> value="Cost" style="height: 20px; width: 20px;" name="package_dashboard[]">&nbsp;&nbsp;Cost
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="package_dashboard[]">&nbsp;&nbsp;Description
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Quote Description" style="height: 20px; width: 20px;" name="package_dashboard[]">&nbsp;&nbsp;Quote Description
                        </td>

                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Final Retail Price".',') !== FALSE) { echo " checked"; } ?> value="Final Retail Price" style="height: 20px; width: 20px;" name="package_dashboard[]">&nbsp;&nbsp;Final Retail Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Admin Price".',') !== FALSE) { echo " checked"; } ?> value="Admin Price" style="height: 20px; width: 20px;" name="package_dashboard[]">&nbsp;&nbsp;Admin Price
                        </td>
                        <td>
                           <input type="checkbox" <?php if (strpos($value_config, ','."Wholesale Price".',') !== FALSE) { echo " checked"; } ?> value="Wholesale Price" style="height: 20px; width: 20px;" name="package_dashboard[]">&nbsp;&nbsp;Wholesale Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Commercial Price".',') !== FALSE) { echo " checked"; } ?> value="Commercial Price" style="height: 20px; width: 20px;" name="package_dashboard[]">&nbsp;&nbsp;Commercial Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Client Price".',') !== FALSE) { echo " checked"; } ?> value="Client Price" style="height: 20px; width: 20px;" name="package_dashboard[]">&nbsp;&nbsp;Client Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Minimum Billable".',') !== FALSE) { echo " checked"; } ?> value="Minimum Billable" style="height: 20px; width: 20px;" name="package_dashboard[]">&nbsp;&nbsp;Minimum Billable
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Estimated Hours".',') !== FALSE) { echo " checked"; } ?> value="Estimated Hours" style="height: 20px; width: 20px;" name="package_dashboard[]">&nbsp;&nbsp;Estimated Hours
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Actual Hours".',') !== FALSE) { echo " checked"; } ?> value="Actual Hours" style="height: 20px; width: 20px;" name="package_dashboard[]">&nbsp;&nbsp;Actual Hours
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."MSRP".',') !== FALSE) {
                            echo " checked"; } ?> value="MSRP" style="height: 20px; width: 20px;" name="package_dashboard[]">&nbsp;&nbsp;MSRP
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Invoice Description".',') !== FALSE) {
                            echo " checked"; } ?> value="Invoice Description" style="height: 20px; width: 20px;" name="package_dashboard[]">&nbsp;&nbsp;Invoice Description
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Ticket Description".',') !== FALSE) {
                            echo " checked"; } ?> value="Ticket Description" style="height: 20px; width: 20px;" name="package_dashboard[]">&nbsp;&nbsp;<?= TICKET_NOUN ?> Description
                        </td>
                    </tr>
                </table>


            </div>
        </div>
    </div>

</div>

    <div class="pull-left">
        <span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Clicking here will discard your changes."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
        <a href="package.php" class="btn brand-btn btn-lg">Back</a>
		<!--<a href="#" class="btn config-btn btn-lg pull-right" onclick="history.go(-1);return false;">Back</a>-->
	</div>
	<div class="pull-right">
        <span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here to submit your changes."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
        <button	type="submit" name="submit"	value="Submit" class="btn brand-btn btn-lg">Submit</button>
    </div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>