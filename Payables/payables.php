<?php
/* Expenses Listing */
include ('../include.php');
checkAuthorised('payables');
error_reporting(0);
?>

</head>
<body>
<?php include_once ('../navigation.php');

if(empty($_GET['tab'])) {
	$current_tab = 'expense';
} else {
	$current_tab = $_GET['tab'];
}

switch($current_tab) {
	case 'expense':
		$current_tab_name = 'Expense';
		break;
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
        <div class="col-sm-10">
			<h1><?php echo $current_tab_name; ?> Payables Dashboard</h1>
		</div>
		<div class="clearfix double-gap-bottom"></div>
		
		<div class="tab-container mobile-100-container">
			<?php if ( check_subtab_persmission($dbc, 'expense', ROLE, 'payables') === TRUE) { ?>
				<a href="payables.php?tab=expense"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'expense' ? 'active_tab' : ''); ?>">Expense</button></a>
			<?php } ?>
		</div>
			
		<div id="no-more-tables">
			<?php include($current_tab.'_payable.php'); ?>
        </div>
    </div>
</div>

<?php include ('../footer.php'); ?>