<?php
/* Expenses Listing */
include ('../include.php');
checkAuthorised('site_work_orders');
error_reporting(0);
?>

</head>
<body>
<?php include_once ('../navigation.php');

$tab_list = explode(',',trim(get_config($dbc, 'site_work_orders'),','));
foreach($tab_list as $key => $tab_name) {
	if(check_subtab_persmission($dbc, 'site_work_orders', ROLE, $tab_name) === FALSE) {
		unset($tab_list[$key]);
	}
}
$tab_config = ','.implode(',',$tab_list).',';
$current_tab = (empty($_GET['tab']) || strpos($tab_config,$_GET['tab']) == FALSE ? explode(',',trim($tab_config,','))[0] : $_GET['tab']);
$edit_access = vuaed_visible_function ($dbc, 'site_work_orders');
$config_access = config_visible_function($dbc, 'site_work_orders');
$approval_access = approval_visible_function($dbc, 'site_work_orders');

$current_staff_group = mysqli_fetch_array(mysqli_query($dbc, "SELECT `position` FROM `contacts` WHERE `contactid`='" . $_SESSION['contactid'] . "'"))['position'];
$site_work_order_staff_groups = get_config($dbc, 'site_work_order_staff_groups');

switch($current_tab) {
	case 'sites':
		$current_tab_name = 'Work Sites';
		break;
	case 'pending':
		$current_tab_name = 'Pending Site Work Orders';
		break;
	case 'active':
		$current_tab_name = 'Site Work Orders';
		break;
	case 'schedule':
		$current_tab_name = 'Sign In';
		break;
	case 'po':
		$current_tab_name = 'Site Work Purchase Orders';
		break;
}
?>

<div class="container">
    <div class="row">
        <div class="col-sm-10">
			<h1><?php echo $current_tab_name; ?> Dashboard</h1>
		</div>
		<div class="col-sm-2 double-gap-top">
			<?php if($config_access == 1) {
				echo '<a href="field_config.php?tab='.$current_tab.'" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
			} ?>
        </div>
		<div class="clearfix double-gap-bottom"></div>
		
		<div class="tab-container mobile-100-container">
			<?php if (strpos($tab_config,',sites,') !== FALSE) { ?>
				<a href="?tab=sites"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'sites' ? 'active_tab' : ''); ?>">Work Sites</button></a>
			<?php } ?>
			<?php if (strpos($tab_config,',pending,') !== FALSE) { ?>
				<a href="?tab=pending"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'pending' ? 'active_tab' : ''); ?>">Pending Work Orders</button></a>
			<?php } ?>
			<?php if (strpos($tab_config,',active,') !== FALSE && strpos(','.$site_work_order_staff_groups.',', ','.$current_staff_group.',') !== FALSE) { ?>
				<a href="?tab=active"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'active' ? 'active_tab' : ''); ?>">Site Work Orders</button></a>
			<?php } ?>
			<?php if (strpos($tab_config,',schedule,') !== FALSE) { ?>
				<a href="?tab=schedule"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'schedule' ? 'active_tab' : ''); ?>">Sign In</button></a>
			<?php } ?>
			<?php if (strpos($tab_config,',po,') !== FALSE) { ?>
				<a href="?tab=po"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'po' ? 'active_tab' : ''); ?>">Purchase Orders</button></a>
			<?php } ?>
		</div>
			
		<div id="no-more-tables">
			<?php if ($current_tab == 'active') {
					if(strpos(','.$site_work_order_staff_groups.',', ','.$current_staff_group.',') !== FALSE) {
						include($current_tab.'.php');
					}
				} else {
					include($current_tab.'.php');
				} ?>
        </div>
    </div>
</div>

<?php include ('../footer.php'); ?>