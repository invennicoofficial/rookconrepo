<?php
/*
Add Budget
*/
include ('../include.php');
error_reporting(0);
?>
</head>

<body>
<?php include_once ('../navigation.php');
checkAuthorised('budget');
$from_url = 'budget.php?maintype=pending_budget';
if(!empty($_GET['from_url'])) {
	$from_url = urldecode($_GET['from_url']);
}
?>
<div class="container">
	<div class="row">
		<span class="popover-examples list-inline" style="margin:10px 10px 0 0;"><a data-toggle="tooltip" data-placement="top" title=""><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
        <a href=""><button class="btn brand-btn mobile-block mobile-100 active_tab" type="button">Expense History</button></a>
		<div class="gap-top"><a href="<?php echo $from_url; ?>" class="btn config-btn">Back to Dashboard</a></div>
				
		<?php $budgetid = $_GET['budgetid']; ?>
		
		<?php include('expense_summary_details.php'); ?>
	</div>
	<div class="form-group">
		<div class="col-sm-6">
			<a href="<?php echo $from_url; ?>" class="btn brand-btn btn-lg">Back</a>
			<!--<a href="#" class="btn brand-btn btn-lg pull-right" onclick="history.go(-1);return false;">Back</a>-->
		</div>
	</div>
</div>