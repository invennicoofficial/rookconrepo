<?php /* Field Configuration for Expenses */
include ('../include.php');
checkAuthorised('payroll');
error_reporting(0);

if (isset($_POST['submit'])) {
    $payroll_tabs = implode(',',$_POST['tab_config']);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='payroll_tabs'"));
    if($get_config['configid'] > 0) {
        $query_update_config = "UPDATE `general_configuration` SET value = '$payroll_tabs' WHERE name='payroll_tabs'";
        $result_update_config = mysqli_query($dbc, $query_update_config);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('payroll_tabs', '$payroll_tabs')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    echo '<script type="text/javascript"> window.location.replace("payroll.php"); </script>';
}

// Variables
$tab_config = ','.get_config($dbc, 'payroll_tabs').',';
?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<h1>Payroll</h1>
<div class="pad-left gap-top double-gap-bottom"><a href="payroll.php" class="btn config-btn">Back to Dashboard</a></div>
<!--<a href="#" class="btn config-btn" onclick="history.go(-1);return false;">Back</a>-->

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<div class="panel-group" id="accordion2">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_config" >
					Payroll Configuration<span class="glyphicon glyphicon-plus"></span>
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
							<input type="checkbox" <?php if (strpos($tab_config, ','."compensation".',') !== FALSE) { echo " checked"; } ?> value="compensation" style="height: 20px; width: 20px;" name="tab_config[]">&nbsp;&nbsp;Staff Compensation
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($tab_config, ','."salary".',') !== FALSE) { echo " checked"; } ?> value="salary" style="height: 20px; width: 20px;" name="tab_config[]">&nbsp;&nbsp;Staff Salary
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($tab_config, ','."contractor".',') !== FALSE) { echo " checked"; } ?> value="contractor" style="height: 20px; width: 20px;" name="tab_config[]">&nbsp;&nbsp;Contractor Compensation
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($tab_config, ','."field_ticket".',') !== FALSE) { echo " checked"; } ?> value="field_ticket" style="height: 20px; width: 20px;" name="tab_config[]">&nbsp;&nbsp;Field Tickets Payroll
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($tab_config, ','."shop_work_order".',') !== FALSE) { echo " checked"; } ?> value="shop_work_order" style="height: 20px; width: 20px;" name="tab_config[]">&nbsp;&nbsp;Shop Work Orders Payroll
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="form-group">
    <div class="col-sm-6">
        <a href="payroll.php" class="btn config-btn btn-lg">Back</a>
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