<?php include('../include.php');
error_reporting(0);
checkAuthorised();
ob_clean();

if($_GET['fill'] == 'check_chart_field') {
	$chart_name = $_POST['chart_name'];
	$clientid = $_POST['clientid'];
	$no_client = $_POST['no_client'];
	if($no_client == 1) {
		$clientid = 0;
	}
	$year = $_POST['year'];
	$month = $_POST['month'];
	$day = $_POST['day'];
	$headingid = $_POST['headingid'];
	$fieldid = $_POST['fieldid'];
	$staffid = $_SESSION['contactid'];
	$checked_date = date('Y-m-d');
	$checked = $_POST['checked'];

	if($checked == 1) {
		mysqli_query($dbc, "INSERT INTO `custom_charts` (`chart_name`, `clientid`, `headingid`, `fieldid`, `year`, `month`, `day`, `staffid`, `checked_date`, `no_client`) VALUES ('$chart_name', '$clientid', '$headingid', '$fieldid', '$year', '$month', '$day', '$staffid', '$checked_date', '$no_client')");
	} else {
        $date_of_archival = date('Y-m-d');
		mysqli_query($dbc, "UPDATE `custom_charts` SET `deleted` = 1, `date_of_archival` = '$date_of_archival' WHERE `chart_name` = '$chart_name' AND `clientid` = '$clientid' AND `no_client` = '$no_client' AND `headingid` = '$headingid' AND `fieldid` = '$fieldid' AND `year` = '$year' AND `month` = '$month' AND `day` = '$day'");
	}
}