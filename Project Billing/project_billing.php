<?php
/* Expenses Listing */
include ('../include.php');
checkAuthorised('billing');
error_reporting(0);
?>

</head>
<body>
<?php include_once ('../navigation.php');
error_reporting(0);

$tab_list = explode(',',trim(get_config($dbc, 'billing_tabs'),','));
foreach($tab_list as $key => $tab_name) {
	if(check_subtab_persmission($dbc, 'billing', ROLE, $tab_name) === FALSE) {
		unset($tab_list[$key]);
	}
}
$tab_config = ','.implode(',',$tab_list).',';
if(empty($_GET['tab']) || strpos($tab_config,$_GET['tab']) == FALSE) {
	$current_tab = explode(',',trim($tab_config,','))[0];
} else {
	$current_tab = $_GET['tab'];
}

switch($current_tab) {
	case 'billing':
		$current_tab_name = 'Project Billing';
		break;
	case 'invoices':
		$current_tab_name = 'Generated Invoices';
		break;
	case 'accounts_receivable':
		$current_tab_name = 'Accounts Receivable';
		break;
}
?>

<div class="container">
    <div class="row">
        <div class="col-sm-10">
			<h1><?php echo $current_tab_name; ?> Dashboard</h1>
		</div>
		<div class="col-sm-2 double-gap-top">
			<?php if(config_visible_function($dbc, 'billing') == 1) {
				echo '<a href="field_config.php?tab='.$current_tab.'" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
			} ?>
        </div>
		<div class="clearfix double-gap-bottom"></div>
		
		<div class="tab-container mobile-100-container">
			<?php if (strpos($tab_config,',billing,') !== FALSE) { ?>
				<a href="?tab=billing"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'billing' ? 'active_tab' : ''); ?>">Billing</button></a>
			<?php } ?>
			<?php if (strpos($tab_config,',invoices,') !== FALSE) { ?>
				<a href="?tab=invoices"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'invoices' ? 'active_tab' : ''); ?>">Generated Invoices</button></a>
			<?php } ?>
			<?php if (strpos($tab_config,',accounts_receivable,') !== FALSE) { ?>
				<a href="?tab=accounts_receivable"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'accounts_receivable' ? 'active_tab' : ''); ?>">Accounts Receivable</button></a>
			<?php } ?>
		</div>
			
		<div id="no-more-tables">
			<?php include($current_tab.'.php'); ?>
        </div>
    </div>
</div>

<?php include ('../footer.php'); ?>