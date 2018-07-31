<?php
/*
 * Daysheet
 */
error_reporting(0);
include ('../include.php');
if(empty($_GET['tab'])) {
    $_GET['tab'] = 'daysheet';
}

//Insert config settings if none exist
mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`, `value`) SELECT 'daysheet_fields_config', 'Reminders,Tickets,Tasks,Checklists' FROM (SELECT COUNT(*) rows FROM `general_configuration` WHERE `name` = 'daysheet_fields_config') num WHERE num.rows = 0");
mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`, `value`) SELECT 'daysheet_button_config', 'My Projects,My Tickets,My Checklists,My Tasks,My Time Sheets' FROM (SELECT COUNT(*) rows FROM `general_configuration` WHERE `name` = 'daysheet_button_config') num WHERE num.rows = 0");
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

?>
</head>
<style>
hr {
    margin: 0;
}
</style>
<script type="text/javascript" src="../Profile/profile.js"></script>
<script type="text/javascript">
    $(document).ready(function(){        
        $(window).resize(function() {
            var available_height = window.innerHeight - $('footer:visible').outerHeight() - $('#daysheet_div .tile-container').offset().top;
            if(available_height > 200) {
                $('#daysheet_div .tile-sidebar, #daysheet_div .tile-sidebar ul, #daysheet_div .tile-content').height(available_height);
                $('#daysheet_div .main-screen-details .sidebar').height(available_height);
            }
        }).resize();
    });
</script>
<body>
<?php include_once ('../navigation.php');
checkAuthorised();
?>
<div id="daysheet_div" class="container">
    <div class="iframe_overlay" style="display:none; margin-top: -20px;margin-left:-15px;">
        <div class="iframe">
            <div class="iframe_loading">Loading...</div>
            <iframe name="daysheet_iframe" src=""></iframe>
        </div>
    </div>
    <div class="iframe_holder" style="display:none;">
        <img src="<?= WEBSITE_URL ?>/img/icons/close.png" class="close_iframer" width="45px" style="position:relative; right:10px; float:right; top:58px; cursor:pointer;">
        <span class="iframe_title" style="color:white; font-weight:bold; position:relative; top:58px; left:20px; font-size:30px;"></span>
        <iframe id="iframe_instead_of_window" style="width:100%; overflow:hidden; height:200px; border:0;" src=""></iframe>
    </div>
    <div class="row hide_on_iframe">
        <div class="main-screen">
            <!-- Tile Header -->
            <div class="tile-header">
                <div class="col-xs-12 col-sm-4">
                    <h1>
                        <span class="pull-left" style="margin-top: -5px;"><a href="daysheet.php" class="default-color">Planner</a></span>
                        <span class="clearfix"></span>
                    </h1>
                </div>
                <div class="col-xs-12 col-sm-8 text-right settings-block">
                    <div class="pull-right gap-left top-settings">
	                    <?php if ( config_visible_function ( $dbc, 'profile' ) == 1 ) { ?>
                            <a href="?settings=config" class="mobile-block pull-right "><img title="Tile Settings" src="<?= WEBSITE_URL; ?>/img/icons/settings-4.png" class="settings-classic wiggle-me" width="30"></a>
                            <span class="popover-examples list-inline pull-right" style="margin:5px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to change settings for the Planner."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
	                    } ?>
						<?php if(get_config($dbc, 'planner_end_day') == 'show') { ?>
							<a href="?end_day=end" class="btn brand-btn pull-right">End Day</a>
						<?php } ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div><!-- .tile-header -->

            <div class="tile-container" style="height: 100%;">
                <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

                	<?php if($_GET['settings'] == 'config') { ?>
	                    <!-- Sidebar -->
	                    <div class="collapsible tile-sidebar set-section-height">
	                        <?php include('../Daysheet/tile_sidebar.php'); ?>
	                    </div><!-- .tile-sidebar -->
	                <?php } else { ?>
                        <!-- Sidebar -->
                        <div class="collapsible tile-sidebar set-section-height hide-on-mobile">
                            <?php include('../Daysheet/tile_sidebar.php'); ?>
                        </div><!-- .tile-sidebar -->
                    <?php } ?>

                    <!-- Main Screen -->
                    <div class="scale-to-fill tile-content set-section-height" style="padding: 0; overflow-y: auto;"><?php
                        if ($_GET['end_day'] == 'end') {
                            include('../Profile/daysheet_overview.php');
                        } else if ($_GET['settings'] == 'config') {
                            include('../Profile/field_config_daysheet.php');
                        } else if ($_GET['tab'] == 'journals') {
                            include('../Daysheet/journal.php');
                        } else {
                            include('../Profile/daysheet_main.php');
                        } ?>
                    </div><!-- .tile-content -->
                    
                    <div class="clearfix"></div>
                </div><!-- .tile-container -->
            </form>

        </div><!-- .main-screen -->
    </div><!-- .row -->
</div><!-- .container -->

<?php include ('../footer.php'); ?>