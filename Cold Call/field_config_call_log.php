<?php
/*
Dashboard
*/
include ('../include.php');
error_reporting(0);
checkAuthorised('calllog');

if (isset($_POST['submit'])) {
    $pipeline_fields = implode(',',$_POST['pipeline_fields']);
    $pipeline_dashboard = implode(',',$_POST['pipeline_dashboard']);

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(fccalllogid) AS fccalllogid FROM field_config_calllog"));
    if($get_field_config['fccalllogid'] > 0) {
        $query_update_employee = "UPDATE `field_config_calllog` SET `pipeline_fields` = '$pipeline_fields', `pipeline_dashboard` = '$pipeline_dashboard' WHERE `fccalllogid` = 1";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config_calllog` (`pipeline_fields`, `pipeline_dashboard`) VALUES ('$pipeline_fields', '$pipeline_dashboard')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    /*$sales_lead_source = filter_var($_POST['sales_lead_source'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='sales_lead_source'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$sales_lead_source' WHERE name='sales_lead_source'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('sales_lead_source', '$sales_lead_source')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }*/

    $calllog_next_action = filter_var($_POST['calllog_next_action'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='calllog_next_action'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$calllog_next_action' WHERE name='calllog_next_action'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('calllog_next_action', '$calllog_next_action')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }


    $calllog_lead_status = filter_var($_POST['calllog_lead_status'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='calllog_lead_status'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$calllog_lead_status' WHERE name='calllog_lead_status'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('calllog_lead_status', '$calllog_lead_status')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $calllog_schedule_status = filter_var($_POST['calllog_schedule_status'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='calllog_schedule_status'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$calllog_schedule_status' WHERE name='calllog_schedule_status'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('calllog_schedule_status', '$calllog_schedule_status')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    echo '<script type="text/javascript"> window.location.replace("field_config_call_log.php"); </script>';

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
<h1>Cold Call</h1>
<div class="pad-left gap-top double-gap-bottom"><a href="call_log.php" class="btn config-btn">Back to Dashboard</a></div>
<!--<a href="#" class="btn config-btn" onclick="history.go(-1);return false;">Back</a>-->

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<div class="panel-group" id="accordion2">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_accordion" >
                    Cold Call Pipeline Fields Accordions<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_accordion" class="panel-collapse collapse">
            <div class="panel-body">
                <input type="checkbox" id="selectall"/> Select All
				<div id='no-more-tables'>
                <table border='2' cellpadding='10' class='table'>
                    <?php
                    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pipeline_fields FROM field_config_calllog"));
                    $pipeline_fields = ','.$get_field_config['pipeline_fields'].',';
                    ?>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($pipeline_fields, ','."CL#".',') !== FALSE) { echo " checked"; } ?> class="all_check" value="CL#" style="height: 20px; width: 20px;" name="pipeline_fields[]">&nbsp;&nbsp;CL#
                        </td>
                        <td>
                            <input type="checkbox" <?php if (stripos($pipeline_fields, ','."Business Information".',') !== FALSE) { echo " checked"; } ?> class="all_check" value="Business Information" style="height: 20px; width: 20px;" name="pipeline_fields[]">&nbsp;&nbsp;Business Information
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($pipeline_fields, ','."Contact Information".',') !== FALSE) { echo " checked"; } ?> class="all_check" value="Contact Information" style="height: 20px; width: 20px;" name="pipeline_fields[]">&nbsp;&nbsp;Contact Information
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($pipeline_fields, ','."Call Information".',') !== FALSE) { echo " checked"; } ?> class="all_check" value="Call Information" style="height: 20px; width: 20px;" name="pipeline_fields[]">&nbsp;&nbsp;Call Information
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($pipeline_fields, ','."Next Steps".',') !== FALSE) { echo " checked"; } ?> class="all_check" value="Next Steps" style="height: 20px; width: 20px;" name="pipeline_fields[]">&nbsp;&nbsp;Next Steps
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($pipeline_fields, ','."Status".',') !== FALSE) { echo " checked"; } ?> class="all_check" value="Status" style="height: 20px; width: 20px;" name="pipeline_fields[]">&nbsp;&nbsp;Status
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_dash" >
                    Cold Call Pipeline Dashboard<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_dash" class="panel-collapse collapse">
            <div class="panel-body">
                <?php
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pipeline_dashboard FROM field_config_calllog"));
                $pipeline_dashboard = ','.$get_field_config['pipeline_dashboard'].',';
                ?>

                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($pipeline_dashboard, ','."CL#".',') !== FALSE) { echo " checked"; } ?> class="all_check" value="CL#" style="height: 20px; width: 20px;" name="pipeline_dashboard[]">&nbsp;&nbsp;CL#
                        </td>
                        <td>
                            <input type="checkbox" <?php if (stripos($pipeline_dashboard, ','."Business & Contact".',') !== FALSE) { echo " checked"; } ?> class="all_check" value="Business & Contact" style="height: 20px; width: 20px;" name="pipeline_dashboard[]">&nbsp;&nbsp;Business & Contact
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($pipeline_dashboard, ','."Call Subject".',') !== FALSE) { echo " checked"; } ?> class="all_check" value="Call Subject" style="height: 20px; width: 20px;" name="pipeline_dashboard[]">&nbsp;&nbsp;Call Subject
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($pipeline_dashboard, ','."Call Duration".',') !== FALSE) { echo " checked"; } ?> class="all_check" value="Call Duration" style="height: 20px; width: 20px;" name="pipeline_dashboard[]">&nbsp;&nbsp;Call Duration
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($pipeline_dashboard, ','."Next Action".',') !== FALSE) { echo " checked"; } ?> class="all_check" value="Next Action" style="height: 20px; width: 20px;" name="pipeline_dashboard[]">&nbsp;&nbsp;Next Action
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($pipeline_dashboard, ','."Reminder/Follow Up".',') !== FALSE) { echo " checked"; } ?> class="all_check" value="Reminder/Follow Up" style="height: 20px; width: 20px;" name="pipeline_dashboard[]">&nbsp;&nbsp;Reminder/Follow Up
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($pipeline_dashboard, ','."Notes".',') !== FALSE) { echo " checked"; } ?> class="all_check" value="Notes" style="height: 20px; width: 20px;" name="pipeline_dashboard[]">&nbsp;&nbsp;Notes
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($pipeline_dashboard, ','."Status".',') !== FALSE) { echo " checked"; } ?> class="all_check" value="Status" style="height: 20px; width: 20px;" name="pipeline_dashboard[]">&nbsp;&nbsp;Status
                        </td>
                    </tr>
                </table>

            </div>
        </div>
    </div>

    <!--
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field" >
                    Choose Fields for Pipeline<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_field" class="panel-collapse collapse">
            <div class="panel-body">
                <?php
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT call_log FROM field_config"));
                $value_config = ','.$get_field_config['call_log'].',';
                ?>

                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Today".',') !== FALSE) { echo " checked"; } ?> value="Today" style="height: 20px; width: 20px;" name="sales[]">&nbsp;&nbsp;Today
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."This Week".',') !== FALSE) { echo " checked"; } ?> value="This Week" style="height: 20px; width: 20px;" name="sales[]">&nbsp;&nbsp;This Week
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."This Month".',') !== FALSE) { echo " checked"; } ?> value="This Month" style="height: 20px; width: 20px;" name="sales[]">&nbsp;&nbsp;This Month
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Custom".',') !== FALSE) { echo " checked"; } ?> value="Custom" style="height: 20px; width: 20px;" name="sales[]">&nbsp;&nbsp;Custom
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Services" >
                    Services Accordion<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_Services" class="panel-collapse collapse">
            <div class="panel-body">

                <input type="checkbox" <?php if (strpos($value_config, ','."Services Service Type".',') !== FALSE) { echo " checked"; } ?> value="Services Service Type" style="height: 20px; width: 20px;" name="sales[]">&nbsp;&nbsp;Service Type&nbsp;&nbsp;
                <input type="checkbox" <?php if (strpos($value_config, ','."Services Category".',') !== FALSE) { echo " checked"; } ?> value="Services Category" style="height: 20px; width: 20px;" name="sales[]">&nbsp;&nbsp;Category&nbsp;&nbsp;
                <input type="checkbox" <?php if (strpos($value_config, ','."Services Heading".',') !== FALSE) { echo " checked"; } ?> value="Services Heading" style="height: 20px; width: 20px;" name="sales[]">&nbsp;&nbsp;Heading&nbsp;&nbsp;

            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Products" >
                    Products Accordion<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_Products" class="panel-collapse collapse">
            <div class="panel-body">

                <input type="checkbox" <?php if (strpos($value_config, ','."Products Product Type".',') !== FALSE) { echo " checked"; } ?> value="Products Product Type" style="height: 20px; width: 20px;" name="sales[]">&nbsp;&nbsp;Product Type&nbsp;&nbsp;
                <input type="checkbox" <?php if (strpos($value_config, ','."Products Category".',') !== FALSE) { echo " checked"; } ?> value="Products Category" style="height: 20px; width: 20px;" name="sales[]">&nbsp;&nbsp;Category&nbsp;&nbsp;
                <input type="checkbox" <?php if (strpos($value_config, ','."Products Heading".',') !== FALSE) { echo " checked"; } ?> value="Products Heading" style="height: 20px; width: 20px;" name="sales[]">&nbsp;&nbsp;Heading&nbsp;&nbsp;

             </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_mm" >
                    Marketing Material Accordion<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_mm" class="panel-collapse collapse">
            <div class="panel-body">

                <input type="checkbox" <?php if (strpos($value_config, ','."Marketing Material Material Type".',') !== FALSE) { echo " checked"; } ?> value="Marketing Material Material Type" style="height: 20px; width: 20px;" name="sales[]">&nbsp;&nbsp;Material Type&nbsp;&nbsp;
                <input type="checkbox" <?php if (strpos($value_config, ','."Marketing Material Category".',') !== FALSE) { echo " checked"; } ?> value="Marketing Material Category" style="height: 20px; width: 20px;" name="sales[]">&nbsp;&nbsp;Category&nbsp;&nbsp;
                <input type="checkbox" <?php if (strpos($value_config, ','."Marketing Material Heading".',') !== FALSE) { echo " checked"; } ?> value="Marketing Material Heading" style="height: 20px; width: 20px;" name="sales[]">&nbsp;&nbsp;Heading&nbsp;&nbsp;

             </div>
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_accordionls" >
                    Lead Source<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_accordionls" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                    <label for="company_name" class="col-sm-4 control-label">Lead Source:</label>
                    <div class="col-sm-8">
                      <input name="sales_lead_source" value="<?php echo get_config($dbc, 'sales_lead_source'); ?>" type="text" class="form-control">
                    </div>
                </div>

            </div>
        </div>
    </div>
    -->

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_accordionna" >
                    Next Action<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_accordionna" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                    <label for="company_name" class="col-sm-4 control-label">Next Action (enter without a space following the comma):</label>
                    <div class="col-sm-8">
                      <input name="calllog_next_action" value="<?php echo get_config($dbc, 'calllog_next_action'); ?>" type="text" class="form-control">
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_accordionstatus" >
                    Cold Call Pipeline Status<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_accordionstatus" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                    <label for="company_name" class="col-sm-4 control-label">Cold Call Pipeline Status (enter without a space following the comma):</label>
                    <div class="col-sm-8">
                      <input name="calllog_lead_status" value="<?php echo get_config($dbc, 'calllog_lead_status'); ?>" type="text" class="form-control">
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_accordionschedulestatus" >
                    Schedule Status<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_accordionschedulestatus" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                    <label for="company_name" class="col-sm-4 control-label">Schedule Status (enter without a space following the comma):</label>
                    <div class="col-sm-8">
                      <input name="calllog_schedule_status" value="<?php echo get_config($dbc, 'calllog_schedule_status'); ?>" type="text" class="form-control">
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-6">
        <a href="call_log.php" class="btn config-btn btn-lg">Back</a>
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
