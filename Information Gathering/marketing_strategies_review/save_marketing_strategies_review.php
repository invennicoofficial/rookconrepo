<?php
    $today_date = date('Y-m-d');
    $business = filter_var(htmlentities($_POST['business']),FILTER_SANITIZE_STRING);

	$msr1 = filter_var(htmlentities($_POST['msr1']),FILTER_SANITIZE_STRING);
	$msr2 = filter_var(htmlentities($_POST['msr2']),FILTER_SANITIZE_STRING);
	$msr3 = filter_var(htmlentities($_POST['msr3']),FILTER_SANITIZE_STRING);
	$msr4 = filter_var(htmlentities($_POST['msr4']),FILTER_SANITIZE_STRING);
	$msr5 = filter_var(htmlentities($_POST['msr5']),FILTER_SANITIZE_STRING);
	$msr6 = filter_var(htmlentities($_POST['msr6']),FILTER_SANITIZE_STRING);
	$msr7 = filter_var(htmlentities($_POST['msr7']),FILTER_SANITIZE_STRING);
	$msr8 = filter_var(htmlentities($_POST['msr8']),FILTER_SANITIZE_STRING);
	$msr9 = filter_var(htmlentities($_POST['msr9']),FILTER_SANITIZE_STRING);

	if(empty($_POST['fieldlevelriskid'])) {
        $query_insert_site = "INSERT INTO `info_marketing_strategies_review` (`infogatheringid`, `today_date`, `business`, `msr1`, `msr2`, `msr3`, `msr4`, `msr5`, `msr6`, `msr7`, `msr8`, `msr9`) VALUES	('$infogatheringid','$today_date','$business','$msr1','$msr2','$msr3','$msr4','$msr5','$msr6','$msr7','$msr8','$msr9')";

		$result_insert_site	= mysqli_query($dbc, $query_insert_site);
        $fieldlevelriskid = mysqli_insert_id($dbc);

        $created_by = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);
        $query_insert_site = "INSERT INTO `infogathering_pdf` (`infogatheringid`, `fieldlevelriskid`, `today_date`, `created_by`, `company`) VALUES	('$infogatheringid', '$fieldlevelriskid', '$today_date', '$created_by', '$business')";
        $result_insert_site	= mysqli_query($dbc, $query_insert_site);

	} else {
        $fieldlevelriskid = $_POST['fieldlevelriskid'];
        $query_update_employee = "UPDATE `info_marketing_strategies_review` SET `business` = '$business', `msr1` = '$msr1', `msr2` = '$msr2',`msr3` = '$msr3',`msr4` = '$msr4',`msr5` = '$msr5',`msr6` = '$msr6',`msr7` = '$msr7',`msr8` = '$msr8',`msr9` = '$msr9' WHERE fieldlevelriskid='$fieldlevelriskid'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);

        $query_update_employee = "UPDATE `infogathering_pdf` SET `company` = '$business' WHERE fieldlevelriskid='$fieldlevelriskid' AND infogatheringid='$infogatheringid'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    }

    include ('marketing_strategies_review_pdf.php');
    echo marketing_strategies_review_pdf($dbc,$infogatheringid, $fieldlevelriskid);

    echo '<script type="text/javascript">
       window.location.replace("manual_reporting.php?type=infogathering"); </script>';

?>