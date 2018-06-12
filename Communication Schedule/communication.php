<?php
/* Expenses Listing */
include ('../include.php');
checkAuthorised('communication_schedule');
error_reporting(0);
?>

</head>
<body>
<?php include_once ('../navigation.php');
error_reporting(0);

$tab_config = ','.get_config($dbc, 'communication_schedule_tabs').',';
if(empty($_GET['tab'])) {
	$current_tab = explode(',',trim($tab_config,','))[0];
} else {
	$current_tab = $_GET['tab'];
}

switch($current_tab) {
	case 'email':
		$current_tab_name = 'Email Schedule';
		break;
	case 'phone':
		$current_tab_name = 'Phone Schedule';
		break;
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
        <div class="col-sm-10">
			<h1><?php echo $current_tab_name; ?> Dashboard</h1>
		</div>
		<div class="col-sm-2 double-gap-top">
			<?php if(config_visible_function($dbc, 'expense') == 1) {
				echo '<a href="field_config.php?tab='.$current_tab.'" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
			} ?>
        </div>
		<div class="clearfix double-gap-bottom"></div>
		
		<div class="tab-container mobile-100-container">
			<?php if (strpos($tab_config,',email,') !== FALSE && check_subtab_persmission($dbc, 'communication_schedule', ROLE, 'email') === TRUE) { ?>
				<a href="?tab=email"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'email' ? 'active_tab' : ''); ?>">Email Schedule</button></a>
			<?php } ?>
			<?php if (strpos($tab_config,',phone,') !== FALSE && check_subtab_persmission($dbc, 'communication_schedule', ROLE, 'phone') === TRUE) { ?>
				<a href="?tab=phone"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'phone' ? 'active_tab' : ''); ?>">Phone Schedule</button></a>
			<?php } ?>
		</div>
			
		<div id="no-more-tables">
			<?php include($current_tab.'.php'); ?>
        </div>
    </div>
</div>

<?php include ('../footer.php'); ?>