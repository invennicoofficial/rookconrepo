<?php
/*
Header file - Include in all files.
*/

ob_start();
include_once('database_connection.php');
include_once('function.php');
$_SERVER['DBC'] = $dbc;

$external_intake = false;
if(strtok($_SERVER['REQUEST_URI'], '?') == '/Intake/add_form.php') {
	$external_intake = true;
}
DEFINE('EXTERNAL_INTAKE', $external_intake);

if(!isset($_SESSION['user_name']) && !isset($guest_access) && $guest_access != true && !$external_intake) {
    ob_clean();
    header("Location: ".(isset($_SERVER["HTTPS"]) ? 'https://' : 'http://').$_SERVER['SERVER_NAME']."/index.php?location=" . urlencode($_SERVER['REQUEST_URI']));
}

/*if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
*/

date_default_timezone_set('America/Denver');

$folder_path = $_SERVER['REQUEST_URI'];
$each_tab = explode('/', $folder_path);

DEFINE('FOLDER_NAME', strtolower($each_tab[1]));
DEFINE('FOLDER_URL', $each_tab[1]);
DEFINE('WEBSITE_URL', (isset($_SERVER["HTTPS"]) ? 'https://' : 'http://').$_SERVER['SERVER_NAME']);
DEFINE('ITEMS_PER_PAGE', 25);
DEFINE('COMPANY_NAME', 'Washtech');
DEFINE('ROLE', $_SESSION['role']);
DEFINE('LOGINID', $_SESSION['contactid']);
DEFINE('IFRAME_PAGE', (isset($_GET['mode']) ? $_GET['mode'] === 'iframe' : false));
DEFINE('EMBED_MAPS_KEY', (isset($google_embed_maps_key) ? $google_embed_maps_key : 'AIzaSyD69QyoD8Qjj03K2hDNoSSK-BixGE4dI5E'));
DEFINE('DIRECTIONS_KEY', (isset($google_directions_key) ? $google_directions_key : 'AIzaSyAn0JIrWLE3zrWU9chH4oxAcIl0or4HTN8'));
DEFINE('GEOCODER_KEY', (isset($google_geocoding_key) ? $google_geocoding_key : 'AIzaSyD5bpjLVZBUxVqVldqGXUtQWJqqCMvSbcA'));

$staff_cats = explode(',',get_config($dbc, 'staff_assign_categories'));
$staff_cats[] = 'Staff';
$staff_cats = array_filter($staff_cats);
DEFINE('STAFF_CATS', "'".implode("','",$staff_cats)."'");

$staff_cats_hide = array_filter(explode(',',get_config($dbc, 'staff_categories_hide')));
if(empty($staff_cats_hide)) {
	$staff_cats_hide = ['**HIDE_PLACEHOLDER**'];
}
DEFINE('STAFF_CATS_HIDE', "'".implode("','",$staff_cats_hide)."'");
$staff_cats_hide_query = "IF((FIND_IN_SET('".implode("', `staff_category`) > 0 OR FIND_IN_SET('", $staff_cats_hide)."', `staff_category`) > 0) AND ((IF(FIND_IN_SET('".implode("', `staff_category`) > 0,1,0) + IF(FIND_IN_SET('", $staff_cats_hide)."', `staff_category`) > 0,1,0) - (CHAR_LENGTH(`staff_category`) - CHAR_LENGTH(REPLACE(`staff_category`, ',', '')) + 1)) = 0),1,0) = 0";
DEFINE('STAFF_CATS_HIDE_QUERY', $staff_cats_hide_query);

function checkAuthorised($tile=false, $tab=false, $tile_sub=false) {
	$role = $_SESSION['role'];
	$dbc = $_SERVER['DBC'];
	if(!isset($_SESSION['user_name']) && !isset($guest_access) && $guest_access != true && !EXTERNAL_INTAKE) {
		ob_clean();
		header("Location: ".WEBSITE_URL."/index.php?location=" . urlencode($_SERVER['REQUEST_URI']));
	} else if($tile_sub != false && strpos($_SERVER['REQUEST_URI'],'/home.php') === FALSE && strpos(get_privileges($dbc, $tile_sub, ROLE), '*hide*') !== FALSE) {
		ob_clean();
		header('Location: ' . WEBSITE_URL . '/home.php');
	} else if($tile != false && strpos($_SERVER['REQUEST_URI'],'/home.php') === FALSE && (tile_visible($dbc, $tile) == 0 && ($tile != 'project_workflow' || tile_visible($dbc, config_safe_str($_GET['tile'])) == 0)) && !EXTERNAL_INTAKE) {
		ob_clean();
		header('Location: ' . WEBSITE_URL . '/home.php');
	} else if($tile != false && $tab != false && strpos($_SERVER['REQUEST_URI'],'/home.php') === FALSE && !check_subtab_persmission($dbc, $tile, $role, $tab)) {
		$tile_data = tile_data($dbc,$tile);
		$main_url = WEBSITE_URL.'/' . $tile_data['link'];
		$actual_link = WEBSITE_URL.$_SERVER['REQUEST_URI'];
		if($main_url != $actual_link) {
			ob_clean();
			header('Location: ' . $main_url);
		}
	}
}