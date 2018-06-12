<?php
/*
Customer Listing
*/
include ('../include.php');
?>
<script type="text/javascript">
$(document).ready(function() {
});
</script>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('time_tracking');
$tab_config = ','.get_config($dbc,'time_tracking_tabs').',';

if(empty($_GET['tab'])) {
	$current_tab = explode(',',trim($tab_config,','))[0];
} else {
	$current_tab = $_GET['tab'];
}

switch($current_tab) {
	case 'shop_time_sheets':
		$current_tab_name = 'Shop Time Sheets';
		break;
	case 'tracking':
	default:
		$current_tab_name = 'Time Tracking';
		break;
}
?>

<div class="container triple-pad-bottom">
    <div class="row">
		<div class="col-md-12">

        <h1 class=""><?php echo $current_tab_name; ?> Dashboard
        <?php
        if(config_visible_function($dbc, 'time_tracking') == 1) {
            echo '<a href="field_config_time_tracking.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>';
        }
        ?>
        </h1>
		
		<div class="tab-container mobile-100-container"><?php
            if (strpos(','.$tab_config.',',',tracking,') !== FALSE) {
                if (check_subtab_persmission($dbc, 'time_tracking', ROLE, 'tracking') === TRUE) { ?>
                    <a href="?tab=tracking"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'tracking' ? 'active_tab' : ''); ?>">Time Tracking</button></a><?php
                } else { ?>
                    <button type="button" class="btn mobile-block mobile-100 disabled-btn">Time Tracking</button><?php
                }
			}
            if (strpos(','.$tab_config.',',',shop_time_sheets,') !== FALSE) {
                if (check_subtab_persmission($dbc, 'time_tracking', ROLE, 'shop_time_sheets') === TRUE) { ?>
                    <a href="?tab=shop_time_sheets"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'shop_time_sheets' ? 'active_tab' : ''); ?>">Shop Time Sheets</button></a><?php
                } else { ?>
                    <button type="button" class="btn mobile-block mobile-100 disabled-btn">Shop Time Sheets</button><?php
                }
            } ?>
		</div>

		<?php include($current_tab.'.php'); ?>
        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
