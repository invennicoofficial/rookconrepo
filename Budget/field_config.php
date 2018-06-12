<?php /* Field Configuration for Budget */
include ('../include.php');
error_reporting(0);
checkAuthorised('budget');

if (isset($_POST['submit'])) {
    /*$budget = implode(',',$_POST['budget']);
    $budget_dashboard = implode(',',$_POST['budget_dashboard']);
	foreach($_POST['budget_dashboard'] as $key => $value) {
		if(strpos(','.$budget.',', ','.$value.',') === false) {
			$budget_dashboard = trim(str_replace(','.$value.',',',',','.$budget_dashboard.','),',');
		}
	}

	$tab = $_GET['tab'];
    $budget_types = filter_var($_POST['budget_types'],FILTER_SANITIZE_STRING);
    $pst_name = filter_var($_POST['pst_name'],FILTER_SANITIZE_STRING);
    $gst_name = filter_var($_POST['gst_name'],FILTER_SANITIZE_STRING);
	
    $budget_tabs = implode(',',$_POST['budget_tabs']);
	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(fieldconfigid) AS fieldconfigid FROM field_config_budget WHERE `tab`='$tab'"));
    if($get_field_config['fieldconfigid'] > 0) {
        $query_update_config = "UPDATE `field_config_budget` SET budget = '$budget', budget_dashboard = '$budget_dashboard', `gst_name`='$gst_name', `pst_name`='$pst_name', `budget_types`='$budget_types' WHERE `tab`='$tab'";
        $result_update_config = mysqli_query($dbc, $query_update_config);
    } else {
        $query_insert_config = "INSERT INTO `field_config_budget` (`budget`, `budget_dashboard`, `gst_name`, `pst_name`, `budget_types`, `tab`)
			VALUES ('$budget', '$budget_dashboard', '$gst_name', '$pst_name', '$budget_types', '$tab')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }echo $query_update_config.$query_insert_config;

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='budget_tabs'"));
    if($get_config['configid'] > 0) {
        $query_update_config = "UPDATE `general_configuration` SET value = '$budget_tabs' WHERE name='budget_tabs'";
        $result_update_config = mysqli_query($dbc, $query_update_config);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('budget_tabs', '$budget_tabs')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }*/

    echo '<script type="text/javascript"> window.location.replace("field_config_budget.php?tab='.$tab.'"); </script>';
}

// Variables
$tab_config = ','.get_config($dbc, 'budget_tabs').',';
if(empty($tab_config)) {
	$tab_config = ',pending,active,expense,';
}

$tab = (empty($_GET['tab']) ? explode(',',trim($tab_config))[0] : $_GET['tab']);

$default_db = '';
$default_fields = '';
if($tab == 'pending') {
	$default_db = 'Budget Name,Staff Lead,Business,Expense,Income,Profit Loss,Notes,Status';
	$default_fields = 'Budget Name,Staff Lead,Co Lead,Business,Site,Created Date,Start Date,Completion Date,Note,Expense Category,Expense Heading,Expense Daily,Expense Weekly,Expense Monthly,Expense Q1,Expense Q2,Expense Q3,Expense Q4,Expense Annual';
} else if($tab == 'active') {
	$default_db = 'Budget Name,Staff Lead,Business,Expense,Income,Profit Loss,Notes';
} else if($tab == 'expense') {
	$default_db = 'Budget,Category,Heading,Date,Staff,Receipt,Amount,Tax,Total';
	$default_fields = 'Budget,Category,Heading,Staff,Heading,Date,Receipt,Amount,Tax,Total';
}
$config_sql = "SELECT budget_fields, budget_dashboard WHERE `tab`='$tab' UNION SELECT
	'$default_fields', '$default_db'";
$get_budget_config = mysqli_fetch_assoc(mysqli_query($dbc,$config_sql));
$value_config = ','.$get_budget_config['budget_fields'].',';
$db_config = ','.$get_budget_config['budget_dashboard'].',';
?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<h1>Budget Settings</h1>
<div class="pad-left gap-top double-gap-bottom"><a href="budgets.php?tab=<?php echo $tab; ?>" class="btn brand-btn">Back to Dashboard</a></div>
<!--<a href="#" class="btn config-btn" onclick="history.go(-1);return false;">Back</a>-->

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<div class="tab-container mobile-100-container">
	<?php if ( check_subtab_persmission($dbc, 'budget', ROLE, 'pending') === TRUE && strpos($tab_config,',pending,') !== FALSE) { ?>
		<a href="?tab=pending"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($tab == 'pending' ? 'active_tab' : ''); ?>">Pending Budgets</button></a>
	<?php }
	if ( check_subtab_persmission($dbc, 'budget', ROLE, 'active') === TRUE && strpos($tab_config,',active,') !== FALSE) { ?>
		<a href="?tab=active"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($tab == 'active' ? 'active_tab' : ''); ?>">Active Budgets</button></a>
	<?php }
	if ( check_subtab_persmission($dbc, 'budget', ROLE, 'expense') === TRUE && strpos($tab_config,',expense,') !== FALSE) { ?>
		<a href="?tab=expense"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($tab == 'expense' ? 'active_tab' : ''); ?>">Expense Tracking</button></a>
	<?php } ?>
</div>

<!--<div class="panel-group" id="accordion2">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_config" >
					Budget Configuration<span class="glyphicon glyphicon-plus"></span>
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
							<input type="checkbox" <?php if (strpos($tab_config, ','."budget".',') !== FALSE) { echo " checked"; } ?> value="budget" style="height: 20px; width: 20px;" name="budget_tabs[]">&nbsp;&nbsp;Use Budget Tab
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($tab_config, ','."current_month".',') !== FALSE) { echo " checked"; } ?> value="current_month" style="height: 20px; width: 20px;" name="budget_tabs[]">&nbsp;&nbsp;Current Month
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($tab_config, ','."business".',') !== FALSE) { echo " checked"; } ?> value="business" style="height: 20px; width: 20px;" name="budget_tabs[]">&nbsp;&nbsp;Business budgets
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($tab_config, ','."customers".',') !== FALSE) { echo " checked"; } ?> value="customers" style="height: 20px; width: 20px;" name="budget_tabs[]">&nbsp;&nbsp;Customer budgets
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($tab_config, ','."clients".',') !== FALSE) { echo " checked"; } ?> value="clients" style="height: 20px; width: 20px;" name="budget_tabs[]">&nbsp;&nbsp;Client budgets
						</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" <?php if (strpos($tab_config, ','."staff".',') !== FALSE) { echo " checked"; } ?> value="staff" style="height: 20px; width: 20px;" name="budget_tabs[]">&nbsp;&nbsp;Staff budgets
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($tab_config, ','."sales".',') !== FALSE) { echo " checked"; } ?> value="sales" style="height: 20px; width: 20px;" name="budget_tabs[]">&nbsp;&nbsp;Sales budgets
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($tab_config, ','."manager".',') !== FALSE) { echo " checked"; } ?> value="manager" style="height: 20px; width: 20px;" name="budget_tabs[]">&nbsp;&nbsp;Require Manager Approval
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($tab_config, ','."payables".',') !== FALSE) { echo " checked"; } ?> value="payables" style="height: 20px; width: 20px;" name="budget_tabs[]">&nbsp;&nbsp;Payables
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($tab_config, ','."report".',') !== FALSE) { echo " checked"; } ?> value="report" style="height: 20px; width: 20px;" name="budget_tabs[]">&nbsp;&nbsp;Enable Reporting
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
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field" >
					Choose Fields for Budget<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

		<div id="collapse_field" class="panel-collapse collapse">
			<div class="panel-body">
				<div id='no-more-tables'>
				<table border='2' cellpadding='10' class='table'>
					<tr>
						<td>
							<input type="checkbox" <?php if (strpos($value_config, ','."Staff".',') !== FALSE) { echo " checked"; } ?> value="Staff" style="height: 20px; width: 20px;" name="budget[]">&nbsp;&nbsp;budget Staff
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($value_config, ','."Contact".',') !== FALSE) { echo " checked"; } ?> value="Contact" style="height: 20px; width: 20px;" name="budget[]">&nbsp;&nbsp;budget Contact
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($value_config, ','."budget For".',') !== FALSE) { echo " checked"; } ?> value="budget For" style="height: 20px; width: 20px;" name="budget[]">&nbsp;&nbsp;budget Tab
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($value_config, ','."Category".',') !== FALSE) { echo " checked"; } ?> value="Category" style="height: 20px; width: 20px;" name="budget[]">&nbsp;&nbsp;budget Category
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($value_config, ','."Heading".',') !== FALSE) { echo " checked"; } ?> value="Heading" style="height: 20px; width: 20px;" name="budget[]">&nbsp;&nbsp;budget Heading
						</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" <?php if (strpos($value_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="budget[]">&nbsp;&nbsp;Description
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($value_config, ','."Date".',') !== FALSE) { echo " checked"; } ?> value="Date" style="height: 20px; width: 20px;" name="budget[]">&nbsp;&nbsp;budget Date
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($value_config, ','."Receipt".',') !== FALSE) { echo " checked"; } ?> value="Receipt" style="height: 20px; width: 20px;" name="budget[]">&nbsp;&nbsp;Receipt
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($value_config, ','."Type".',') !== FALSE) { echo " checked"; } ?> value="Type" style="height: 20px; width: 20px;" name="budget[]">&nbsp;&nbsp;budget Type
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($value_config, ','."Day budget".',') !== FALSE) { echo " checked"; } ?> value="Day budget" style="height: 20px; width: 20px;" name="budget[]">&nbsp;&nbsp;Day budget
						</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" <?php if (strpos($value_config, ','."Amount".',') !== FALSE) { echo " checked"; } ?> value="Amount" style="height: 20px; width: 20px;" name="budget[]">&nbsp;&nbsp;Amount
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($value_config, ','."Local Tax".',') !== FALSE) { echo " checked"; } ?> value="Local Tax" style="height: 20px; width: 20px;" name="budget[]">&nbsp;&nbsp;<?php echo $pst_name; ?>
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($value_config, ','."Tax".',') !== FALSE) { echo " checked"; } ?> value="Tax" style="height: 20px; width: 20px;" name="budget[]">&nbsp;&nbsp;<?php echo $gst_name; ?>
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($value_config, ','."Total".',') !== FALSE) { echo " checked"; } ?> value="Total" style="height: 20px; width: 20px;" name="budget[]">&nbsp;&nbsp;Total
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($value_config, ','."Budget".',') !== FALSE) { echo " checked"; } ?> value="Budget" style="height: 20px; width: 20px;" name="budget[]">&nbsp;&nbsp;Budget
						</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" <?php if (strpos($value_config, ','."Signature".',') !== FALSE) { echo " checked"; } ?> value="Signature" style="height: 20px; width: 20px;" name="budget[]">&nbsp;&nbsp;Signature
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($value_config, ','."Flight".',') !== FALSE) { echo " checked"; } ?> value="Flight" style="height: 20px; width: 20px;" name="budget[]">&nbsp;&nbsp;Flight
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($value_config, ','."Hotel".',') !== FALSE) { echo " checked"; } ?> value="Hotel" style="height: 20px; width: 20px;" name="budget[]">&nbsp;&nbsp;Hotel
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($value_config, ','."Breakfast".',') !== FALSE) { echo " checked"; } ?> value="Breakfast" style="height: 20px; width: 20px;" name="budget[]">&nbsp;&nbsp;Breakfast
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($value_config, ','."Lunch".',') !== FALSE) { echo " checked"; } ?> value="Lunch" style="height: 20px; width: 20px;" name="budget[]">&nbsp;&nbsp;Lunch
						</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" <?php if (strpos($value_config, ','."Dinner".',') !== FALSE) { echo " checked"; } ?> value="Dinner" style="height: 20px; width: 20px;" name="budget[]">&nbsp;&nbsp;Dinner
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($value_config, ','."Beverages".',') !== FALSE) { echo " checked"; } ?> value="Beverages" style="height: 20px; width: 20px;" name="budget[]">&nbsp;&nbsp;Beverages
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($value_config, ','."Transportation".',') !== FALSE) { echo " checked"; } ?> value="Transportation" style="height: 20px; width: 20px;" name="budget[]">&nbsp;&nbsp;Transportation
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($value_config, ','."Entertainment".',') !== FALSE) { echo " checked"; } ?> value="Entertainment" style="height: 20px; width: 20px;" name="budget[]">&nbsp;&nbsp;Entertainment
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($value_config, ','."Gas".',') !== FALSE) { echo " checked"; } ?> value="Gas" style="height: 20px; width: 20px;" name="budget[]">&nbsp;&nbsp;Gas
						</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" <?php if (strpos($value_config, ','."Misc".',') !== FALSE) { echo " checked"; } ?> value="Misc" style="height: 20px; width: 20px;" name="budget[]">&nbsp;&nbsp;Misc
						</td>
					</tr>
				</table>
				</div>
				  <div class="form-group">
					<label for="office_zip" class="col-sm-4 control-label">Additional Types<br><em>(separated by a comma, no spaces)</em>:</label>
					<div class="col-sm-8">
					  <input name="budget_types" value="<?php echo $budget_types; ?>" type="text" class="form-control office_zip" />
					</div>
				  </div>

				  <div class="form-group">
					<label for="office_zip" class="col-sm-4 control-label">Tax Name:<br><em>(e.g. - GST, HST, etc.)</em></label>
					<div class="col-sm-8">
					  <input name="gst_name" value="<?php echo $gst_name; ?>" type="text" class="form-control" />
					</div>
				  </div>
				  <div class="form-group">
					<label for="office_zip" class="col-sm-4 control-label">Tax Amount %:<br><em>(e.g. - 5)</em></label>
					<div class="col-sm-8">
					  <input name="gst_amt" value="<?php echo $gst_amt; ?>" type="text" class="form-control" />
					</div>
				  </div>
				  <div class="form-group">
					<label for="office_zip" class="col-sm-4 control-label">Additional Tax Name:<br><em>(e.g. - PST, Sales Tax, etc.)</em></label>
					<div class="col-sm-8">
					  <input name="pst_name" value="<?php echo $pst_name; ?>" type="text" class="form-control" />
					</div>
				  </div>
				  <div class="form-group">
					<label for="office_zip" class="col-sm-4 control-label">Additional Tax Amount %:<br><em>(e.g. - 5)</em></label>
					<div class="col-sm-8">
					  <input name="pst_amt" value="<?php echo $pst_amt; ?>" type="text" class="form-control" />
					</div>
				  </div>
			</div>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field2" >
					Choose Fields for Budget Dashboard<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

		<div id="collapse_field2" class="panel-collapse collapse">
			<div class="panel-body">
				<div id='no-more-tables'>
				<table border='2' cellpadding='10' class='table'>
					<tr>
						<td>
							<input type="checkbox" <?php if (strpos($db_config, ','."Contact".',') !== FALSE) { echo " checked"; } ?> value="Contact" style="height: 20px; width: 20px;" name="budget_dashboard[]">&nbsp;&nbsp;budget Contact
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($db_config, ','."budget For".',') !== FALSE) { echo " checked"; } ?> value="budget For" style="height: 20px; width: 20px;" name="budget_dashboard[]">&nbsp;&nbsp;budget Tab
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($db_config, ','."Category".',') !== FALSE) { echo " checked"; } ?> value="Category" style="height: 20px; width: 20px;" name="budget_dashboard[]">&nbsp;&nbsp;budget Category
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($db_config, ','."Heading".',') !== FALSE) { echo " checked"; } ?> value="Heading" style="height: 20px; width: 20px;" name="budget_dashboard[]">&nbsp;&nbsp;budget Heading
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($db_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="budget_dashboard[]">&nbsp;&nbsp;Description
						</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" <?php if (strpos($db_config, ','."Date".',') !== FALSE) { echo " checked"; } ?> value="Date" style="height: 20px; width: 20px;" name="budget_dashboard[]">&nbsp;&nbsp;budget Date
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($db_config, ','."Receipt".',') !== FALSE) { echo " checked"; } ?> value="Receipt" style="height: 20px; width: 20px;" name="budget_dashboard[]">&nbsp;&nbsp;Receipt
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($db_config, ','."Type".',') !== FALSE) { echo " checked"; } ?> value="Type" style="height: 20px; width: 20px;" name="budget_dashboard[]">&nbsp;&nbsp;budget Type
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($db_config, ','."Day budget".',') !== FALSE) { echo " checked"; } ?> value="Day budget" style="height: 20px; width: 20px;" name="budget_dashboard[]">&nbsp;&nbsp;Day budget
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($db_config, ','."Amount".',') !== FALSE) { echo " checked"; } ?> value="Amount" style="height: 20px; width: 20px;" name="budget_dashboard[]">&nbsp;&nbsp;Amount
						</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" <?php if (strpos($db_config, ','."Local Tax".',') !== FALSE) { echo " checked"; } ?> value="Local Tax" style="height: 20px; width: 20px;" name="budget_dashboard[]">&nbsp;&nbsp;<?php echo $pst_name; ?>
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($db_config, ','."Tax".',') !== FALSE) { echo " checked"; } ?> value="Tax" style="height: 20px; width: 20px;" name="budget_dashboard[]">&nbsp;&nbsp;<?php echo $gst_name; ?>
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($db_config, ','."Total".',') !== FALSE) { echo " checked"; } ?> value="Total" style="height: 20px; width: 20px;" name="budget_dashboard[]">&nbsp;&nbsp;Total
						</td>
						<td>
							<input type="checkbox" <?php if (strpos($db_config, ','."Budget".',') !== FALSE) { echo " checked"; } ?> value="Budget" style="height: 20px; width: 20px;" name="budget_dashboard[]">&nbsp;&nbsp;Budget
						</td>
					</tr>
				</table>
			   </div>
			</div>
		</div>
	</div>
</div>-->

<div class="form-group">
    <div class="col-sm-6">
        <a href="budgets.php?tab=<?php echo $tab; ?>" class="btn brand-btn btn-lg">Back</a>
		<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
    </div>
    <div class="col-sm-6">
        <button	type="submit" name="submit"	value="Submit" class="btn brand-btn btn-lg	pull-right">Submit</button>
    </div>
</div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>