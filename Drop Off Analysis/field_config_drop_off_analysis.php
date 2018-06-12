<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('drop_off_analysis');

error_reporting(0);

if (isset($_POST['add_tab'])) {
    $drop_off_analysis_status = filter_var($_POST['drop_off_analysis_status'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='drop_off_analysis_status'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$drop_off_analysis_status' WHERE name='drop_off_analysis_status'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('drop_off_analysis_status', '$drop_off_analysis_status')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    echo '<script type="text/javascript"> window.location.replace("field_config_drop_off_analysis.php"); </script>';
}

?>
<script type="text/javascript">

</script>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<h1>Settings</h1>

<form id="form1" name="form1" method="post"	enctype="multipart/form-data" class="form-horizontal" role="form">

<div class="panel-group" id="accordion2">

        <div class="form-group">
            <label for="fax_number"	class="col-sm-4	control-label"><!-- <span class="popover-examples list-inline"><a class="" style="margin:7px 5px 0 0;" data-toggle="tooltip" data-placement="top" title="These tabs sort your inventory by Category, so please make sure the tab names match your inventory's category names. Also, please make sure you do not place any spaces beside the commas."><img src="<?php //echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span> --> Add Status Separated By a Comma:</label>
            <div class="col-sm-8">
              <input name="drop_off_analysis_status" type="text" value="<?php echo get_config($dbc, 'drop_off_analysis_status'); ?>" class="form-control"/>
            </div>
        </div>

        <div class="form-group double-gap-top">
            <div class="col-sm-6">
					<div class="double-gap-bottom"><a href="drop_off_analysis.php" class="btn config-btn btn-lg">Back</a></div>
            </div>
            <div class="col-sm-6">
                <button	type="submit" name="add_tab" value="add_tab" class="btn config-btn btn-lg pull-right">Submit</button>
            </div>
        </div>

        
</form>
</div>
</div>

<?php include ('../footer.php'); ?>