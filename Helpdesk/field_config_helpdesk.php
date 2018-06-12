<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('helpdesk');
error_reporting(0);

if (isset($_POST['submit'])) {
    $helpdesk_type = filter_var($_POST['helpdesk_type'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='helpdesk_type'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$helpdesk_type' WHERE name='helpdesk_type'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('helpdesk_type', '$helpdesk_type')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    echo '<script type="text/javascript"> window.location.replace("field_config_helpdesk.php?type=tab"); </script>';
}
?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<h1>Help Desk</h1>
<div class="pad-left gap-top double-gap-bottom"><a href="helpdesk.php" class="btn config-btn">Back to Dashboard</a></div>

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<div class="panel-group" id="accordion2">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field" >
                    Choose Fields for Support Types<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_field" class="panel-collapse collapse">
            <div class="panel-body" id="no-more-tables">

                <div class="form-group">
                    <label for="fax_number"	class="col-sm-4	control-label">Add Type separated by a comma without any space:</label>
                    <div class="col-sm-8">
                      <input name="helpdesk_type" type="text" value="<?php echo get_config($dbc, 'helpdesk_type'); ?>" class="form-control"/>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<div class="form-group">
    <div class="col-sm-6">
        <a href="helpdesk.php" class="btn config-btn btn-lg">Back</a>
	</div>
	<div class="col-sm-6">
        <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
    </div>
</div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>