<?php /* Field Configuration for Expenses */
include ('../include.php');
checkAuthorised('communication_schedule');
error_reporting(0);

if (isset($_POST['submit'])) {
    $communication_schedule_tabs = implode(',',$_POST['tab_config']);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='communication_schedule_tabs'"));
    if($get_config['configid'] > 0) {
        $query_update_config = "UPDATE `general_configuration` SET value = '$communication_schedule_tabs' WHERE name='communication_schedule_tabs'";
        $result_update_config = mysqli_query($dbc, $query_update_config);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('communication_schedule_tabs', '$communication_schedule_tabs')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
}

// Variables
$tab_config = ','.get_config($dbc, 'communication_schedule_tabs').',';
?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<h1>Communication</h1>
<div class="pad-left gap-top double-gap-bottom"><a href="communication.php" class="btn config-btn">Back to Dashboard</a></div>
<!--<a href="#" class="btn config-btn" onclick="history.go(-1);return false;">Back</a>-->

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<div class="panel-group" id="accordion2">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_config" >
					Communication Configuration<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

		<div id="collapse_config" class="panel-collapse collapse">
			<div class="panel-body">
				<h3>Enable Tabs</h3>
				<div id='no-more-tables'>
				<table border='2' cellpadding='10' class='table'>
					<tr>
						<td>
							<input type="checkbox" <?php if (strpos($tab_config, ','."email".',') !== FALSE) { echo " checked"; } ?> value="email" style="height: 20px; width: 20px;" name="tab_config[]">&nbsp;&nbsp;Email Schedule
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($tab_config, ','."phone".',') !== FALSE) { echo " checked"; } ?> value="phone" style="height: 20px; width: 20px;" name="tab_config[]">&nbsp;&nbsp;Phone Schedule
						</td>
					</tr>
				</table>
				</div>
			</div>
		</div>
	</div>
	<?php include('../Email Communication/field_config_communication.php'); ?>
</div>

<div class="form-group">
    <div class="col-sm-6">
        <a href="communication.php" class="btn config-btn btn-lg">Back</a>
		<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
    </div>
    <div class="col-sm-6">
        <button	type="submit" name="service_record_btn"	value="service_record_btn" class="btn config-btn btn-lg	pull-right">Submit</button>
    </div>
</div>

</form>
</div>

<?php if (isset($_POST['service_record_btn'])) {
    echo "<script>window.location.replace('communication.php');</script>";
} ?>
<?php include ('../footer.php'); ?>