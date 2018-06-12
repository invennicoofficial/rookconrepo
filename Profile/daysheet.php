<?php
/*
 * Daysheet
 */
error_reporting(0);
if(!isset($_GET['mobile_view'])) {
    include_once ('../include.php');
} else {
    include_once ('../database_connection.php');
    include_once ('../global.php');
    include_once ('../function.php');
    include_once ('../output_functions.php');
    include_once ('../email.php');
    include_once ('../user_font_settings.php');
}
//Insert config settings if none exist
mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`, `value`) SELECT 'daysheet_fields_config', 'Reminders,Tickets,Tasks,Checklists' FROM (SELECT COUNT(*) rows FROM `general_configuration` WHERE `name` = 'daysheet_fields_config') num WHERE num.rows = 0");
mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`, `value`) SELECT 'daysheet_button_config', 'My Projects,My Tickets,My Checklists,My Tasks' FROM (SELECT COUNT(*) rows FROM `general_configuration` WHERE `name` = 'daysheet_button_config') num WHERE num.rows = 0");
mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`, `value`) SELECT 'daysheet_weekly_config', '1,2,3,4,5,6,7' FROM (SELECT COUNT(*) rows FROM `general_configuration` WHERE `name` = 'daysheet_weekly_config') num WHERE num.rows = 0");

//Configs
$daysheet_fields_config = get_user_settings()['daysheet_fields_config'];
if(empty($daysheet_fields_config)) {
    $daysheet_fields_config = explode(',', get_config($dbc, 'daysheet_fields_config'));
} else {
    $daysheet_fields_config = explode(',', $daysheet_fields_config);
}
$daysheet_weekly_config = get_user_settings()['daysheet_weekly_config'];
if(empty($daysheet_weekly_config)) {
    $daysheet_weekly_config = explode(',', get_config($dbc, 'daysheet_weekly_config'));
} else {
    $daysheet_weekly_config = explode(',', $daysheet_weekly_config);
}
$daysheet_button_config = get_user_settings()['daysheet_button_config'];
if(empty($daysheet_button_config)) {
    $daysheet_button_config = explode(',', get_config($dbc, 'daysheet_button_config'));
} else {
    $daysheet_button_config = explode(',', $daysheet_button_config);
}

if(!empty($_POST['subtab']) && !isset($_GET['mobile_view'])) {
    $action_page = 'my_profile.php';
    if($_POST['subtab'] == 'software_access') {
        $action_page = 'edit_software_access.php';
    } else if($_POST['subtab'] == 'certificates') {
        $action_page = 'my_certificate.php';
    } else if($_POST['subtab'] == 'goals') {
        $action_page = 'gao_goal.php';
    } else if($_POST['subtab'] == 'daysheet') {
        $action_page = 'daysheet.php';
        echo '<script type="text/javascript"> window.location.href = "'.$action_page.'"</script>';
    } else if($_POST['subtab'] == 'schedule') {
        $action_page = 'staff_schedule.php';
    }

    ?>
    <form action="<?php echo $action_page; ?>" method="post" id="change_page">
        <input type="hidden" name="subtab" value="<?php echo $_POST['subtab']; ?>">
    </form>
    <script type="text/javascript"> document.getElementById('change_page').submit(); </script>
<?php }

$subtab = 'daysheet';
if (!empty($_POST['subtab'])) {
    $subtab = $_POST['subtab'];
}
?>
</head>
<style>
hr {
    margin: 0;
}
</style>
<script type="text/javascript" src="profile.js"></script>
<body>
<?php if(!isset($_GET['mobile_view'])) { include_once ('../navigation.php'); }
checkAuthorised();
?>

<div class="container">
    <?php if(!isset($_GET['mobile_view']) && $_GET['end_day'] == 'end') {
        echo '<div class="visible-xs">';
		include('daysheet_overview.php');
        echo '</div>';
	} else if(!isset($_GET['mobile_view'])) { include('mobile_view.php'); } ?>
    <div class="iframe_holder" style="display:none;">
        <img src="<?= WEBSITE_URL ?>/img/icons/close.png" class="close_iframer" width="45px" style="position:relative; right:10px; float:right; top:58px; cursor:pointer;">
        <span class="iframe_title" style="color:white; font-weight:bold; position:relative; top:58px; left:20px; font-size:30px;"></span>
        <iframe id="iframe_instead_of_window" style="width:100%; overflow:hidden; height:200px; border:0;" src=""></iframe>
    </div>
    <div class="row hide_on_iframe hide-titles-mob">
        <div class="main-screen">
            <!-- Tile Header -->
            <div class="tile-header">
                <div class="col-xs-12 col-sm-4">
                    <h1>
                        <span class="pull-left" style="margin-top: -5px;"><a href="daysheet.php" class="default-color">My Profile</a></span>
                        <span class="clearfix"></span>
                    </h1>
                </div>
                <div class="col-xs-12 col-sm-8 text-right settings-block">
                    <?php if ( config_visible_function ( $dbc, 'profile' ) == 1 ) { ?>
                        <div class="pull-right gap-left top-settings">
                            <a href="?settings=config" class="mobile-block pull-right "><img title="Tile Settings" src="<?= WEBSITE_URL; ?>/img/icons/settings-4.png" class="settings-classic wiggle-me" width="30"></a>
                            <span class="popover-examples list-inline pull-right" style="margin:5px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to change settings for the Planner."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                        </div><?php
                    } ?>
					<?php if(get_config($dbc, 'planner_end_day') == 'show') { ?>
						<a href="?end_day=end" class="btn brand-btn pull-right">End Day</a>
					<?php } ?>
                </div>
                <div class="clearfix"></div>
            </div><!-- .tile-header -->

            <div class="tile-container">
                <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

                    <!-- Sidebar -->
                    <div class="collapsible tile-sidebar set-section-height">
                        <?php include('tile_sidebar.php'); ?>
                    </div><!-- .tile-sidebar -->

                    <!-- Main Screen -->
                    <div class="scale-to-fill tile-content set-section-height" style="padding: 0; overflow-y: auto;"><?php
                        if ($_GET['end_day'] == 'end') {
                            include('daysheet_overview.php');
                        } else if ($_GET['settings'] == 'config') {
                            include('field_config_daysheet.php');
                        } else {
                            include('daysheet_main.php');
                        } ?>
                    </div><!-- .tile-content -->
                    
                    <div class="clearfix"></div>
                </div><!-- .tile-container -->
            </form>

        </div><!-- .main-screen -->
    </div><!-- .row -->
</div><!-- .container -->

<?php include ('../footer.php'); ?>