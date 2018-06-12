<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('fund_development');
error_reporting(0);

if (isset($_POST['submit'])) {
    $funders = implode(',',$_POST['funders']);
    $funding = implode(',',$_POST['funding']);

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(fieldconfigid) AS fieldconfigid FROM field_config"));
    if($get_field_config['fieldconfigid'] > 0) {
        $query_update = "UPDATE `field_config` SET fund_development_funders = '$funders', fund_development_funding = '$funding' WHERE `fieldconfigid` = 1";
        $result_update = mysqli_query($dbc, $query_update);
    } else {
        $query_insert_config = "INSERT INTO `field_config` (`fund_development_funders`, `fund_development_funding`) VALUES ('$funders', '$funding')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    echo '<script type="text/javascript"> window.location.replace("field_config.php"); </script>';
}
?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<h1>Funders</h1>
<div class="pad-left gap-top double-gap-bottom"><a href="funders.php" class="btn config-btn">Back to Dashboard</a></div>

<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<div class="panel-group" id="accordion2">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field" >
                    Choose Fields for Funders<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_field" class="panel-collapse collapse">
            <div class="panel-body">
                <?php
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fund_development_funders FROM field_config"));
                $value_config = ','.$get_field_config['fund_development_funders'].',';
                ?>
                <div id='no-more-tables'>
                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."First Name".',') !== FALSE) { echo " checked"; } ?> value="First Name" style="height: 20px; width: 20px;" name="funders[]">&nbsp;&nbsp;First Name
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Last Name".',') !== FALSE) { echo " checked"; } ?> value="Last Name" style="height: 20px; width: 20px;" name="funders[]">&nbsp;&nbsp;Last Name
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Client ID #".',') !== FALSE) { echo " checked"; } ?> value="Client ID #" style="height: 20px; width: 20px;" name="funders[]">&nbsp;&nbsp;Client ID #
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."AISH #".',') !== FALSE) { echo " checked"; } ?> value="AISH #" style="height: 20px; width: 20px;" name="funders[]">&nbsp;&nbsp;AISH #
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Work Phone".',') !== FALSE) { echo " checked"; } ?> value="Work Phone" style="height: 20px; width: 20px;" name="funders[]">&nbsp;&nbsp;Work Phone
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Home Phone".',') !== FALSE) { echo " checked"; } ?> value="Home Phone" style="height: 20px; width: 20px;" name="funders[]">&nbsp;&nbsp;Home Phone
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Cell Phone".',') !== FALSE) { echo " checked"; } ?> value="Cell Phone" style="height: 20px; width: 20px;" name="funders[]">&nbsp;&nbsp;Cell Phone
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Fax #".',') !== FALSE) { echo " checked"; } ?> value="Fax #" style="height: 20px; width: 20px;" name="funders[]">&nbsp;&nbsp;Fax #
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Email Address".',') !== FALSE) { echo " checked"; } ?> value="Email Address" style="height: 20px; width: 20px;" name="funders[]">&nbsp;&nbsp;Email Address
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Address".',') !== FALSE) { echo " checked"; } ?> value="Address" style="height: 20px; width: 20px;" name="funders[]">&nbsp;&nbsp;Address
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Postal/Zip Code".',') !== FALSE) { echo " checked"; } ?> value="Postal/Zip Code" style="height: 20px; width: 20px;" name="funders[]">&nbsp;&nbsp;Postal/Zip Code
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."City/Town".',') !== FALSE) { echo " checked"; } ?> value="City/Town" style="height: 20px; width: 20px;" name="funders[]">&nbsp;&nbsp;City/Town
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Province/State".',') !== FALSE) { echo " checked"; } ?> value="Province/State" style="height: 20px; width: 20px;" name="funders[]">&nbsp;&nbsp;Province/State
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Country".',') !== FALSE) { echo " checked"; } ?> value="Country" style="height: 20px; width: 20px;" name="funders[]">&nbsp;&nbsp;Country
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Support Documents".',') !== FALSE) { echo " checked"; } ?> value="Support Documents" style="height: 20px; width: 20px;" name="funders[]">&nbsp;&nbsp;Support Documents
                        </td>
                    </tr>
                </table>
                </div>
            </div>
        </div>
    </div>

     <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field2" >
                    Choose Fields for Fundings<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_field2" class="panel-collapse collapse">
            <div class="panel-body">
                <?php
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fund_development_funding FROM field_config"));
                $value_config = ','.$get_field_config['fund_development_funding'].',';
                ?>
                <div id='no-more-tables'>
                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Funding For".',') !== FALSE) { echo " checked"; } ?> value="Funding For" style="height: 20px; width: 20px;" name="funding[]">&nbsp;&nbsp;Funding For
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Funding Date".',') !== FALSE) { echo " checked"; } ?> value="Funding Date" style="height: 20px; width: 20px;" name="funding[]">&nbsp;&nbsp;Funding Date
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Receipt".',') !== FALSE) { echo " checked"; } ?> value="Receipt" style="height: 20px; width: 20px;" name="funding[]">&nbsp;&nbsp;Receipt
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Funding Heading".',') !== FALSE) { echo " checked"; } ?> value="Funding Heading" style="height: 20px; width: 20px;" name="funding[]">&nbsp;&nbsp;Funding Heading
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Flight".',') !== FALSE) { echo " checked"; } ?> value="Flight" style="height: 20px; width: 20px;" name="funding[]">&nbsp;&nbsp;Flight
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Hotel".',') !== FALSE) { echo " checked"; } ?> value="Hotel" style="height: 20px; width: 20px;" name="funding[]">&nbsp;&nbsp;Hotel
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Breakfast".',') !== FALSE) { echo " checked"; } ?> value="Breakfast" style="height: 20px; width: 20px;" name="funding[]">&nbsp;&nbsp;Breakfast
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Lunch".',') !== FALSE) { echo " checked"; } ?> value="Lunch" style="height: 20px; width: 20px;" name="funding[]">&nbsp;&nbsp;Lunch
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Dinner".',') !== FALSE) { echo " checked"; } ?> value="Dinner" style="height: 20px; width: 20px;" name="funding[]">&nbsp;&nbsp;Dinner
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Drink".',') !== FALSE) { echo " checked"; } ?> value="Drink" style="height: 20px; width: 20px;" name="funding[]">&nbsp;&nbsp;Beverages
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Transportation".',') !== FALSE) { echo " checked"; } ?> value="Transportation" style="height: 20px; width: 20px;" name="funding[]">&nbsp;&nbsp;Transportation
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Entertainment".',') !== FALSE) { echo " checked"; } ?> value="Entertainment" style="height: 20px; width: 20px;" name="funding[]">&nbsp;&nbsp;Entertainment
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."GAS".',') !== FALSE) { echo " checked"; } ?> value="GAS" style="height: 20px; width: 20px;" name="funding[]">&nbsp;&nbsp;Gas
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Misc".',') !== FALSE) { echo " checked"; } ?> value="Misc" style="height: 20px; width: 20px;" name="funding[]">&nbsp;&nbsp;Misc
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Amount".',') !== FALSE) { echo " checked"; } ?> value="Amount" style="height: 20px; width: 20px;" name="funding[]">&nbsp;&nbsp;Amount
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="funding[]">&nbsp;&nbsp;Description
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Staff".',') !== FALSE) { echo " checked"; } ?> value="Staff" style="height: 20px; width: 20px;" name="funding[]">&nbsp;&nbsp;Staff
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Day Funding".',') !== FALSE) { echo " checked"; } ?> value="Day Funding" style="height: 20px; width: 20px;" name="funding[]">&nbsp;&nbsp;Day Funding
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Budget".',') !== FALSE) { echo " checked"; } ?> value="Budget" style="height: 20px; width: 20px;" name="funding[]">&nbsp;&nbsp;Budget
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."GST".',') !== FALSE) { echo " checked"; } ?> value="GST" style="height: 20px; width: 20px;" name="funding[]">&nbsp;&nbsp;GST
                        </td>
                    </tr>

                </table>
               </div>
            </div>
        </div>
    </div>

</div>

<div class="form-group">
    <div class="col-sm-6">
        <a href="funders.php" class="btn config-btn btn-lg">Back</a>
        <!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
    </div>
    <div class="col-sm-6">
        <button type="submit" name="submit" value="Submit" class="btn config-btn btn-lg pull-right">Submit</button>
    </div>
	<div class="clearfix"></div>
</div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>