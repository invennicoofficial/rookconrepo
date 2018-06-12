<?php
/*
Documents
*/
include ('../include.php');
checkAuthorised('driving_log');

$view_only_mode = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `driving_log_view_only_mode` WHERE `contactid` = '".$_SESSION['contactid']."'"))['view_only_mode'];
include_once('view_only_mode.php');
?>
<script>
$(document).ready(function() {
var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
if(isChrome !== true) {
	$('.notice').html('<span style="padding:10px;"><img src="../img/warning.png" style="width:25px;"> <span style="color:red; font-weight:bold;">NOTICE: </span>You are currently using a browser other than Google Chrome. It is <strong>strongly</strong> recommended that you use this software with the latest Google Chrome internet browser. Download Google Chrome <a href="https://www.google.com/chrome/browser/desktop/index.html?brand=CHBD&gclid=CPT0vf_Xss4CFYY0aQodcMkDfA" target="_BLANK" style="font-weight:bold; color:red; text-decoration:underline" >here</a>.</span>');
}
});
</script>
</head>
<body>
<?php include_once ('../navigation.php');
?>

<div class="container">
	<div class="row">
		<div>
			<div class="col-sm-12">
                <div class="pull-right">
                    View Only: <a href="" class="view_only_button"><img src="../img/icons/switch-<?= ($view_only_mode == 1 ? '7' : '6') ?>.png" style="height: 2em;"></a>&nbsp;&nbsp;
                    <?php
                        if(config_visible_function($dbc, 'driving_log') == 1) {
                            if ($view_only_mode != 1) {
                            echo '<a href="field_config_dl.php"><img style="height: 2em; width: 2em;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
                            }
                        }
                    ?>
                </div>
				<h1 class="pull-left">Driving Log Dashboard</h1>
			</div>
		</div>

		<div class="clearfix"></div>
        
        <div class="notice-alt double-gap-bottom popover-examples">
			<div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25px"></div>
			<div class="col-sm-16"><span class="notice-name">NOTE:</span> In this section you can edit and log your company's Driving history.</div>
		</div>

		<!-- <div class="notice"></div> -->

		<div class="col-md-12">
        <?php
		// if (strpos(','.ROLE.',',',super,') !== false || strpos(','.ROLE.',',',Field Operations Management,') !== false) {
			$contactidfortile = $_SESSION['contactid'];
			 $tdate = date('Y-m-d');
			 $query_getter = "SELECT * FROM driving_log WHERE driverid='".$contactidfortile."' AND start_date = '".$tdate."' ORDER BY `drivinglogid` DESC LIMIT 1";
			 $num_rows = mysqli_num_rows(mysqli_query($dbc,$query_getter));
			$get_driver = mysqli_fetch_assoc(mysqli_query($dbc,$query_getter));
			$last_timer_value = $get_driver['last_timer_value'];
            $hash_url = '';
			if($num_rows > 0) {
                    if($last_timer_value != '0') {
                        $timer_name = explode('*#*',$last_timer_value);
                        $hash_url = '#'.$timer_name[0];
                    }
                    if($get_driver['status'] != 'Done') {
                        if($get_driver['end_date'] == '') {
                            echo '<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" align="center">';
                                echo '<span class="popover-examples list-inline pull-left triple-pad-top"><a style="margin:10px -15px 0 20px;" data-toggle="tooltip" data-placement="top" title="Click here to continue your driving log."><img src="' . WEBSITE_URL . '/img/info.png" width="25"></a></span>';
                                echo '<span class="dashboard link" style="width: calc(100% - 45px);"><a href=\'add_driving_log.php?timer=on&drivinglogid='.$get_driver['drivinglogid'].'\'>Continue Driving Log</a></span>';
                            echo '</div>';
                        } else {
                            if($get_driver['status'] == 'Pending') {
                                echo '<div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href=\'amendments.php?graph=off&drivinglogid='.$get_driver['drivinglogid'].'\'>Continue End of Day</a></div>';
                            }
                        }
                    }
			}
		// }
        // if (strpos(','.ROLE.',',',super,') !== false || strpos(','.ROLE.',',',Field Operations Management,') !== false) {
		    echo '<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" align="center">';
				echo '<span class="popover-examples list-inline pull-left triple-pad-top"><a style="margin:0 -15px 0 20px;" data-toggle="tooltip" data-placement="top" title="If you are ready to start recording your route, click here."><img src="' . WEBSITE_URL . '/img/info.png" width="25"></a></span>';
				echo '<span class="dashboard link" style="width: calc(100% - 45px);"><a href="add_driving_log.php">Start New Driving Log</a></span>';
			echo '</div>';
        // }
		if(strpos(','.ROLE.',',',super,') !== false || strpos(','.ROLE.',',',admin,') !== false) {
			echo '<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" align="center">';
				echo '<span class="popover-examples list-inline pull-left triple-pad-top"><a style="margin:0 -15px 0 20px;" data-toggle="tooltip" data-placement="top" title="If you would like to view all driving logs for review, auditing and approvals, click here."><img src="' . WEBSITE_URL . '/img/info.png" width="25"></a></span>';
				echo '<span class="dashboard link" style="width: calc(100% - 45px);"><a href="driving_log_admin.php">Edit/View Driving Logs</a></span>';
			echo '</div>';
		} else {
			echo '<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" align="center">';
				echo '<span class="popover-examples list-inline pull-left triple-pad-top"><a style="margin:0 -15px 0 20px;" data-toggle="tooltip" data-placement="top" title="If you would like to view all driving logs for review, auditing and approvals, click here."><img src="' . WEBSITE_URL . '/img/info.png" width="25"></a></span>';
				echo '<span class="dashboard link" style="width: calc(100% - 45px);"><a href="driving_log.php">Edit/View My Driving Log(s)</a></span>';
			echo '</div>';
		}

		echo '<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" align="center">';
			echo '<span class="popover-examples list-inline pull-left triple-pad-top"><a style="margin:10px -15px 0 20px;" data-toggle="tooltip" data-placement="top" title="If you would like to view, print or email PDFs of driving logs, click here."><img src="' . WEBSITE_URL . '/img/info.png" width="25"></a></span>';
			echo '<span class="dashboard link" style="width: calc(100% - 45px);"><a href="driving_log_14days.php">14 Day Driving Log(s)</a></span>';
		echo '</div>';

        echo '<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" align="center">';
            echo '<span class="popover-examples list-inline pull-left triple-pad-top"><a style="margin:10px -15px 0 20px;" data-toggle="tooltip" data-placement="top" title="If you would like to log time off, click here."><img src="' . WEBSITE_URL . '/img/info.png" width="25"></a></span>';
            echo '<span class="dashboard link" style="width: calc(100% - 45px);"><a href="add_driving_log_time_off.php">Log Time Off</a></span>';
        echo '</div>';

		$mileage = array_filter(explode(',',get_config($dbc, 'mileage_fields')));
		if(count($mileage) > 0) {
			echo '<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" align="center">';
				echo '<span class="popover-examples list-inline pull-left triple-pad-top"><a style="margin:10px -15px 0 20px;" data-toggle="tooltip" data-placement="top" title="If you want to review all mileage tracked, click here."><img src="' . WEBSITE_URL . '/img/info.png" width="25"></a></span>';
				echo '<span class="dashboard link" style="width: calc(100% - 45px);"><a href="mileage.php">Mileage</a></span>';
			echo '</div>';
		}

		//echo '<div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="driver_codriver.php">Driver/Co-Driver</a></div>';

        if (strpos(','.ROLE.',',',Field Operations Management,') !== false) {
		    /*echo '<div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="vehicle_trailer.php">Vehicle/Trailer</a></div>';
            */

		    //echo '<div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="expenses.php">Expenses</a></div>';
        }

        ?>

		</div>
	</div>
</div>

<?php include ('../footer.php'); ?>