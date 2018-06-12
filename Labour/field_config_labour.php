<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('labour');
error_reporting(0);

if (isset($_POST['submit'])) {
    $labour = implode(',',$_POST['labour']);
    $labour_dashboard = implode(',',$_POST['labour_dashboard']);

    if (strpos(','.$labour.',',','.'Labour Type'.',') === false) {
        $labour = 'Labour Type,'.$labour;
    }
    if (strpos(','.$labour_dashboard.',',','.'Labour Type'.',') === false) {
        $labour_dashboard = 'Labour Type,'.$labour_dashboard;
    }

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(fieldconfigid) AS fieldconfigid FROM field_config"));
    if($get_field_config['fieldconfigid'] > 0) {
        $query_update_employee = "UPDATE `field_config` SET labour = '$labour', labour_dashboard = '$labour_dashboard' WHERE `fieldconfigid` = 1";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config` (`labour`, `labour_dashboard`) VALUES ('$labour', '$labour_dashboard')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    echo '<script type="text/javascript"> window.location.replace("field_config_labour.php"); </script>';

}
?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<h1>Labour</h1>
<div class="pad-left gap-top double-gap-bottom"><a href="labour.php" class="btn config-btn">Back to Dashboard</a></div>
<!--<a href="#" class="btn config-btn" onclick="history.go(-1);return false;">Back</a>-->

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<div class="panel-group" id="accordion2">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field" >
                    Choose Fields for Labour<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_field" class="panel-collapse collapse">
            <div class="panel-body" id="no-more-tables">
                <?php
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT labour FROM field_config"));
                $value_config = ','.$get_field_config['labour'].',';
                ?>

                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" disabled <?php if (strpos($value_config, ','."Labour Type".',') !== FALSE) { echo " checked"; } ?> value="Labour Type" style="height: 20px; width: 20px;" name="labour[]">&nbsp;&nbsp;Labour Type
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Category".',') !== FALSE) { echo " checked"; } ?> value="Category" style="height: 20px; width: 20px;" name="labour[]">&nbsp;&nbsp;Category
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Heading".',') !== FALSE) { echo " checked"; } ?> value="Heading" style="height: 20px; width: 20px;" name="labour[]">&nbsp;&nbsp;Heading
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Cost".',') !== FALSE) { echo " checked"; } ?> value="Cost" style="height: 20px; width: 20px;" name="labour[]">&nbsp;&nbsp;Cost
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="labour[]">&nbsp;&nbsp;Description
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Quote Description" style="height: 20px; width: 20px;" name="labour[]">&nbsp;&nbsp;Quote Description
                        </td>

                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Daily Rate".',') !== FALSE) { echo " checked"; } ?> value="Daily Rate" style="height: 20px; width: 20px;" name="labour[]">&nbsp;&nbsp;Daily Rate
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."WCB".',') !== FALSE) { echo " checked"; } ?> value="WCB" style="height: 20px; width: 20px;" name="labour[]">&nbsp;&nbsp;WCB
                        </td>
                        <td>
                           <input type="checkbox" <?php if (strpos($value_config, ','."Benefits".',') !== FALSE) { echo " checked"; } ?> value="Benefits" style="height: 20px; width: 20px;" name="labour[]">&nbsp;&nbsp;Benefits
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Salary".',') !== FALSE) { echo " checked"; } ?> value="Salary" style="height: 20px; width: 20px;" name="labour[]">&nbsp;&nbsp;Salary
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Bonus".',') !== FALSE) { echo " checked"; } ?> value="Bonus" style="height: 20px; width: 20px;" name="labour[]">&nbsp;&nbsp;Bonus
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Minimum Billable".',') !== FALSE) { echo " checked"; } ?> value="Minimum Billable" style="height: 20px; width: 20px;" name="labour[]">&nbsp;&nbsp;Minimum Billable
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Estimated Hours".',') !== FALSE) { echo " checked"; } ?> value="Estimated Hours" style="height: 20px; width: 20px;" name="labour[]">&nbsp;&nbsp;Estimated Hours
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Actual Hours".',') !== FALSE) { echo " checked"; } ?> value="Actual Hours" style="height: 20px; width: 20px;" name="labour[]">&nbsp;&nbsp;Actual Hours
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."MSRP".',') !== FALSE) {
                            echo " checked"; } ?> value="MSRP" style="height: 20px; width: 20px;" name="labour[]">&nbsp;&nbsp;MSRP
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Labour Code".',') !== FALSE) {
                            echo " checked"; } ?> value="Labour Code" style="height: 20px; width: 20px;" name="labour[]">&nbsp;&nbsp;Labour Code
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Invoice Description".',') !== FALSE) {
                            echo " checked"; } ?> value="Invoice Description" style="height: 20px; width: 20px;" name="labour[]">&nbsp;&nbsp;Invoice Description
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Ticket Description".',') !== FALSE) {
                            echo " checked"; } ?> value="Ticket Description" style="height: 20px; width: 20px;" name="labour[]">&nbsp;&nbsp;<?= TICKET_NOUN ?> Description
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Name".',') !== FALSE) {
                            echo " checked"; } ?> value="Name" style="height: 20px; width: 20px;" name="labour[]">&nbsp;&nbsp;Name
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Hourly Rate".',') !== FALSE) {
                            echo " checked"; } ?> value="Hourly Rate" style="height: 20px; width: 20px;" name="labour[]">&nbsp;&nbsp;Hourly Rate
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
                    Choose Fields for Labour Dashboard<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_dashboard" class="panel-collapse collapse">
            <div class="panel-body" id="no-more-tables">
                <?php
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT labour_dashboard FROM field_config"));
                $value_config = ','.$get_field_config['labour_dashboard'].',';
                ?>

                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" disabled <?php if (strpos($value_config, ','."Labour Type".',') !== FALSE) { echo " checked"; } ?> value="Labour Type" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Labour Type
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Category".',') !== FALSE) { echo " checked"; } ?> value="Category" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Category
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Heading".',') !== FALSE) { echo " checked"; } ?> value="Heading" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Heading
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Cost".',') !== FALSE) { echo " checked"; } ?> value="Cost" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Cost
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Description
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Quote Description" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Quote Description
                        </td>

                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Daily Rate".',') !== FALSE) { echo " checked"; } ?> value="Daily Rate" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Daily Rate
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."WCB".',') !== FALSE) { echo " checked"; } ?> value="WCB" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;WCB
                        </td>
                        <td>
                           <input type="checkbox" <?php if (strpos($value_config, ','."Benefits".',') !== FALSE) { echo " checked"; } ?> value="Benefits" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Benefits
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Salary".',') !== FALSE) { echo " checked"; } ?> value="Salary" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Salary
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Bonus".',') !== FALSE) { echo " checked"; } ?> value="Bonus" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Bonus
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Minimum Billable".',') !== FALSE) { echo " checked"; } ?> value="Minimum Billable" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Minimum Billable
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Estimated Hours".',') !== FALSE) { echo " checked"; } ?> value="Estimated Hours" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Estimated Hours
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Actual Hours".',') !== FALSE) { echo " checked"; } ?> value="Actual Hours" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Actual Hours
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."MSRP".',') !== FALSE) {
                            echo " checked"; } ?> value="MSRP" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;MSRP
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Labour Code".',') !== FALSE) {
                            echo " checked"; } ?> value="Labour Code" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Labour Code
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Invoice Description".',') !== FALSE) {
                            echo " checked"; } ?> value="Invoice Description" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Invoice Description
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Ticket Description".',') !== FALSE) {
                            echo " checked"; } ?> value="Ticket Description" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;<?= TICKET_NOUN ?> Description
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Name".',') !== FALSE) {
                            echo " checked"; } ?> value="Name" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Name
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Hourly Rate".',') !== FALSE) {
                            echo " checked"; } ?> value="Hourly Rate" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Hourly Rate
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

</div>

<div class="form-group">
    <div class="col-sm-6">
        <a href="labour.php" class="btn config-btn btn-lg">Back</a>
		<!--<a href="#" class="btn config-btn btn-lg pull-right" onclick="history.go(-1);return false;">Back</a>-->
	</div>
	<div class="col-sm-6">
		<button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
    </div>
</div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>