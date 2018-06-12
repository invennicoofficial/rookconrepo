<?php //Reload cache. Change the config setting check each time we need to reload the cache
if(strtotime($_SESSION['cache_last_reloaded']) < strtotime('today')) {
	session_start();
	$_SESSION['cache_last_reloaded'] = time();
	session_write_close();
	$_SERVER['page_load_info'] .= 'Cache Reloading: '.number_format(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],5)."\n";
	header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
} else if(!empty($_GET['calendar_view'])) {
	if(strtotime($_SESSION['ticket_iframe_cache_last_reloaded']) < strtotime('today')) {
		session_start();
		$_SESSION['cache_last_reloaded'] = time();
		session_write_close();
		$_SERVER['page_load_info'] .= 'Calendar Cache Reloading: '.number_format(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],5)."\n";
		header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	}
}
?>