<?php include_once('../include.php');
checkAuthorised('estimate');
error_reporting(0);
$style = filter_var($_GET['style'],FILTER_SANITIZE_STRING);
$estimateid = filter_var($_GET['edit'],FILTER_SANITIZE_STRING);
$config_sql = "SELECT * FROM `estimate_pdf_setting` WHERE `pdfsettingid` = '$style' ORDER BY `estimateid` DESC, `style` ASC";
$settings = mysqli_fetch_assoc(mysqli_query($dbc, $config_sql));
$estimate = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `estimate` WHERE `estimateid`='$estimateid'"));
switch($settings['style']) {
	case 'b': include('design_styleB.php'); break;
	case 'c': include('design_styleC.php'); break;
	default: include('design_styleA.php'); break;
}

if(basename($_SERVER['SCRIPT_NAME']) != 'estimates.php') {
	echo $header_html;
	echo $html;
	echo $footer_html;
} ?>