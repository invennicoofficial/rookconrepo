<?php // Include Here Everything
error_reporting(0);
$_SERVER['page_load_info'] = 'Start of Script: '.number_format(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],5)."\n";
session_start(['cookie_lifetime' => 518400]);
$_SERVER['page_load_info'] .= 'Session Started: '.number_format(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],5)."\n";
include_once ('database_connection.php');
$_SERVER['page_load_info'] .= 'Database Connected: '.number_format(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],5)."\n";
include_once ('function.php');
$_SERVER['page_load_info'] .= 'Functions Loaded: '.number_format(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],5)."\n";
include_once ('global.php');
session_write_close();
$_SERVER['page_load_info'] .= 'Globals Declared: '.number_format(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],5)."\n";
if ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
	include_once ('header.php');
} else { ?>
	<script>
		$(document).ready(function() { 
			initInputs();
		});
	</script>
<?php }
$_SERVER['page_load_info'] .= 'Header Loaded: '.number_format(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],5)."\n";
include_once ('output_functions.php');
$_SERVER['page_load_info'] .= 'Output Declared: '.number_format(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],5)."\n";
include_once ('pagination.php');
$_SERVER['page_load_info'] .= 'Pagination Declared: '.number_format(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],5)."\n";
include_once ('filter.php');
$_SERVER['page_load_info'] .= 'Filters Built: '.number_format(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],5)."\n";
include_once ('email.php');
$_SERVER['page_load_info'] .= 'Email Initialized: '.number_format(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],5)."\n";
if ( FOLDER_NAME=='reports' ) {
    include_once ('Reports/report_tiles_function.php');
}
include_once ('mobile_detect.php');
$_SERVER['page_load_info'] .= 'Mobile Detected: '.number_format(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],5)."\n";
if($_SESSION['user_preferences']['loaded_time'] + 3600 < time()) {
	session_start(['cookie_lifetime' => 518400]);
	$_SESSION['user_preferences'] = get_user_settings();
	$_SERVER['page_load_info'] .= 'User Settings Updated: '.number_format(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],5)."\n";
	$_SESSION['user_preferences']['loaded_time'] = time();
	session_write_close();
}
include_once ('user_font_settings.php');
$_SERVER['page_load_info'] .= 'User Font Initialized: '.number_format(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],5)."\n";
if(!($_SESSION['tile_list_updated'] > 0)) {
	$no_display = true;
	include_once('tiles.php');
	unset($no_display);
	$_SERVER['page_load_info'] .= 'Updated Tile List: '.number_format(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],5)."\n";
}
if($_SESSION['contactid'] > 0) {
	set_last_active($dbc, $_SESSION['contactid']);
	$_SERVER['page_load_info'] .= 'Activity Noted: '.number_format(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],5)."\n";
}
include_once ('reload_cache.php');
$_SERVER['page_load_info'] .= 'Cache Loaded: '.number_format(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],5)."\n";

$rookconnect = get_software_name();
$scale_style = '';
$page_options = mysqli_query($dbc, "SELECT * FROM `page_options` WHERE `php_self`='".filter_var($_SERVER['PHP_SELF'],FILTER_SANITIZE_STRING)."' AND `contactid`='".$_SESSION['contactid']."'");
if(mysqli_num_rows($page_options) > 0) {
	if($page_options = mysqli_fetch_assoc($page_options)) {
		if($page_options['scale_width'] > 0) {
			$scale_style = 'width:'.$page_options['scale_width'].'%;';
		}
	}
}
if(strpos_any !== FALSE)
$_SERVER['page_load_info'] .= 'Start of Page: '.number_format(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],5)."\n"; ?>