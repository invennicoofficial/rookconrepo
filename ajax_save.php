<?php include_once('database_connection.php');

if($_GET['action'] == 'browser_load_time') {
	$page_build_time = $_GET['page_build'];
	if($page_build_time > 0) {
		$browser_time = microtime(true) - $page_build_time;
		$dbc->query("INSERT INTO `page_load_times` (`url`,`duration`,`user`,`ip`,`info`) VALUES ('{$_SERVER['HTTP_REFERER']}','$browser_time','{$_SESSION['contactid']}','{$_SERVER['REMOTE_ADDR']}','Browser Load Time')");
	}
}