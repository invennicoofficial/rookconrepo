<?php
/* Client Documentation Listing */
include ('../include.php');
error_reporting(0);
checkAuthorised('client_documentation');
?>

</head>
<body>
<?php include_once ('../navigation.php');

$tab_list = explode(',','medication,charts,day_program,individual_support_plan,daily_log_notes');
foreach($tab_list as $key => $tab_name) {
	if(check_subtab_persmission($dbc, 'client_documentation', ROLE, $tab_name) === FALSE) {
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
	case 'medication':
		$current_tab_name = 'Medication';
		break;
	case 'charts':
		$current_tab_name = 'Medical Charts';
		break;
	case 'day_program':
		$current_tab_name = 'Day Program';
		break;
	case 'individual_support_plan':
		$current_tab_name = 'Individual Service Plan';
		break;
	case 'daily_log_notes':
		$current_tab_name = 'Daily Log Notes';
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
			<?php if(config_visible_function($dbc, 'client_documentation') == 1) {
				echo '<a href="field_config.php?tab='.$current_tab.'" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
			} ?>
        </div>
		<div class="clearfix double-gap-bottom"></div>
		
		<div class="tab-container mobile-100-container"><?php
            if (strpos($tab_config,',medication,') !== FALSE) { ?>
                <a href="?tab=medication"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'medication' ? 'active_tab' : ''); ?>">Medication</button></a><?php
            } else { ?>
                <button type="button" class="btn disabled-btn mobile-block mobile-100">Medication</button><?php
            }
            
            if (strpos($tab_config,',charts,') !== FALSE) { ?>
                <a href="?tab=charts"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'charts' ? 'active_tab' : ''); ?>">Medical Charts</button></a><?php
            } else { ?>
                <button type="button" class="btn disabled-btn mobile-block mobile-100">Medical Charts</button><?php
            }
            
            if (strpos($tab_config,',day_program,') !== FALSE) { ?>
                <a href="?tab=day_program"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'day_program' ? 'active_tab' : ''); ?>">Day Program</button></a><?php
            } else { ?>
                <button type="button" class="btn disabled-btn mobile-block mobile-100">Day Program</button><?php
            }
            
            if (strpos($tab_config,',individual_support_plan,') !== FALSE) { ?>
                <a href="?tab=individual_support_plan"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'individual_support_plan' ? 'active_tab' : ''); ?>">Individual Service Plan</button></a><?php
            } else { ?>
                <button type="button" class="btn disabled-btn mobile-block mobile-100">Individual Service Plan</button><?php
            }
            
            if (strpos($tab_config,',daily_log_notes,') !== FALSE) { ?>
                <a href="?tab=daily_log_notes"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'daily_log_notes' ? 'active_tab' : ''); ?>">Daily Log Notes</button></a><?php
            } else { ?>
                <button type="button" class="btn disabled-btn mobile-block mobile-100">Daily Log Notes</button><?php
            } ?>
		</div>
			
		<div id="no-more-tables">
			<?php include($current_tab.'.php'); ?>
        </div>
    </div>
</div>
</div>

<?php include ('../footer.php'); ?>