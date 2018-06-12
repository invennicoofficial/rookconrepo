 <?php
    $today_date = date('Y-m-d');
    $business = filter_var(htmlentities($_POST['business']),FILTER_SANITIZE_STRING);
	$business_1 = filter_var(htmlentities($_POST['business_1']),FILTER_SANITIZE_STRING);
	$business_2 = filter_var(htmlentities($_POST['business_2']),FILTER_SANITIZE_STRING);
	$business_3 = filter_var(htmlentities($_POST['business_3']),FILTER_SANITIZE_STRING);
	$business_4 = filter_var(htmlentities($_POST['business_4']),FILTER_SANITIZE_STRING);
	$business_5 = filter_var(htmlentities($_POST['business_5']),FILTER_SANITIZE_STRING);
	$business_6 = filter_var(htmlentities($_POST['business_6']),FILTER_SANITIZE_STRING);

    if(empty($_POST['fieldlevelriskid'])) {
        $query_insert_site = "INSERT INTO `info_gap_analysis` (`infogatheringid`, `today_date`, `business`, `business_1`, `business_2`, `business_3`, `business_4`) VALUES	('$infogatheringid','$today_date', '$business', '$business_1','$business_2','$business_3','$business_4')";
        $result_insert_site	= mysqli_query($dbc, $query_insert_site);
        $fieldlevelriskid = mysqli_insert_id($dbc);

        $created_by = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);
        $query_insert_site = "INSERT INTO `infogathering_pdf` (`infogatheringid`, `fieldlevelriskid`, `today_date`, `created_by`, `company`) VALUES	('$infogatheringid', '$fieldlevelriskid', '$today_date', '$created_by', '$business')";
        $result_insert_site	= mysqli_query($dbc, $query_insert_site);

    } else {
        $fieldlevelriskid = $_POST['fieldlevelriskid'];
        $query_update_employee = "UPDATE `info_gap_analysis` SET `business` = '$business', `business_1` = '$business_1', `business_2` = '$business_2',`business_3` = '$business_3',`business_4` = '$business_4' WHERE fieldlevelriskid='$fieldlevelriskid'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);

        $query_update_employee = "UPDATE `infogathering_pdf` SET `company` = '$business' WHERE fieldlevelriskid='$fieldlevelriskid' AND infogatheringid='$infogatheringid'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    }

    include ('gap_analysis_pdf.php');
    echo gap_analysis_pdf($dbc,$infogatheringid, $fieldlevelriskid);

    echo '<script type="text/javascript">
        window.location.replace("manual_reporting.php?type=infogathering"); </script>';
