<?php
/* Expenses Listing */
include ('../include.php');
checkAuthorised('payroll');
error_reporting(0);
?>

</head>
<body>
<?php include_once ('../navigation.php');
error_reporting(0);

$tab_config = ','.get_config($dbc, 'payroll_tabs').',';
if(empty($_GET['tab'])) {
	$current_tab = explode(',',trim($tab_config,','))[0];
} else {
	$current_tab = $_GET['tab'];
}

switch($current_tab) {
	case 'compensation':
		$current_tab_name = 'Staff Compensation';
		break;
	case 'salary':
		$current_tab_name = 'Staff Salary';
		break;
	case 'contractor':
		$current_tab_name = 'Contractor Compensation';
		break;
	case 'field_ticket':
		$current_tab_name = 'Field Ticketing Payroll';
		break;
	case 'shop_work_order':
		$current_tab_name = 'Shop Work Order Payroll';
		break;
}
?>

<div class="container">
    <div class="row">
		<div class="col-sm-10">
			<h1><?php echo $current_tab_name; ?> Payroll Dashboard</h1>
		</div>
		<div class="col-sm-2 double-gap-top">
			<?php if(config_visible_function($dbc, 'expense') == 1) {
				echo '<a href="field_config_payroll.php?tab='.$current_tab.'" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
			} ?>
		</div>
		<div class="clearfix double-gap-bottom"></div>
		
		<div class="tab-container mobile-100-container">
			<?php if (strpos($tab_config,',compensation,') !== FALSE && check_subtab_persmission($dbc, 'payroll', ROLE, 'compensation') === TRUE) { ?>
				<a href="?tab=compensation"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'compensation' ? 'active_tab' : ''); ?>">Staff Compensation</button></a>
			<?php } ?>
			<?php if (strpos($tab_config,',salary,') !== FALSE && check_subtab_persmission($dbc, 'payroll', ROLE, 'salary') === TRUE) { ?>
				<a href="?tab=salary"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'salary' ? 'active_tab' : ''); ?>">Staff Salary</button></a>
			<?php } ?>
			<?php if (strpos($tab_config,',contractor,') !== FALSE && check_subtab_persmission($dbc, 'payroll', ROLE, 'contractor') === TRUE) { ?>
				<a href="?tab=contractor"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'contractor' ? 'active_tab' : ''); ?>">Contractor Compensation</button></a>
			<?php } ?>
			<?php if (strpos($tab_config,',field_ticket,') !== FALSE && check_subtab_persmission($dbc, 'payroll', ROLE, 'field_ticket') === TRUE) { ?>
				<a href="?tab=field_ticket"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'field_ticket' ? 'active_tab' : ''); ?>">Field Tickets Payroll</button></a>
			<?php } ?>
			<?php if (strpos($tab_config,',shop_work_order,') !== FALSE && check_subtab_persmission($dbc, 'payroll', ROLE, 'shop_work_order') === TRUE) { ?>
				<a href="?tab=shop_work_order"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'shop_work_order' ? 'active_tab' : ''); ?>">Shop Work Orders Payroll</button></a>
			<?php } ?>
		</div>
			
		<div id="no-more-tables">
			<?php include($current_tab.'.php'); ?>
		</div>
    </div>
</div>

<?php include ('../footer.php'); ?>