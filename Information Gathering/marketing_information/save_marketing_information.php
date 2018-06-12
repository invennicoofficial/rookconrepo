 <?php
    $today_date = date('Y-m-d');
    $business = filter_var(htmlentities($_POST['business']),FILTER_SANITIZE_STRING);
	$business_1 = filter_var(htmlentities($_POST['business_1']),FILTER_SANITIZE_STRING);
	$business_2 = filter_var(htmlentities($_POST['business_2']),FILTER_SANITIZE_STRING);
	$business_3 = filter_var(htmlentities($_POST['business_3']),FILTER_SANITIZE_STRING);
	$business_4 = filter_var(htmlentities($_POST['business_4']),FILTER_SANITIZE_STRING);
	$business_5 = filter_var(htmlentities($_POST['business_5']),FILTER_SANITIZE_STRING);
	$business_6 = filter_var(htmlentities($_POST['business_6']),FILTER_SANITIZE_STRING);
	$business_7 = filter_var(htmlentities($_POST['business_7']),FILTER_SANITIZE_STRING);
	$business_8 = filter_var(htmlentities($_POST['business_8']),FILTER_SANITIZE_STRING);
	$business_9 = filter_var(htmlentities($_POST['business_9']),FILTER_SANITIZE_STRING);
	$business_10 = filter_var(htmlentities($_POST['business_10']),FILTER_SANITIZE_STRING);

    if(empty($_POST['fieldlevelriskid'])) {
        $query_insert_site = "INSERT INTO `info_marketing_information` (`infogatheringid`, `today_date`, `business`, `business_1`, `business_2`, `business_3`, `business_4`, `business_5`, `business_6`, `business_7`, `business_8`, `business_9`, `business_10`) VALUES	('$infogatheringid','$today_date', '$business', '$business_1','$business_2','$business_3','$business_4','$business_5','$business_6','$business_7','$business_8','$business_9','$business_10')";
        $result_insert_site	= mysqli_query($dbc, $query_insert_site);
        $fieldlevelriskid = mysqli_insert_id($dbc);

        $created_by = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);
        $query_insert_site = "INSERT INTO `infogathering_pdf` (`infogatheringid`, `fieldlevelriskid`, `today_date`, `created_by`, `company`) VALUES	('$infogatheringid', '$fieldlevelriskid', '$today_date', '$created_by', '$business')";
        $result_insert_site	= mysqli_query($dbc, $query_insert_site);

    } else {
        $fieldlevelriskid = $_POST['fieldlevelriskid'];
        $query_update_employee = "UPDATE `info_marketing_information` SET `business` = '$business', `business_1` = '$business_1', `business_2` = '$business_2',`business_3` = '$business_3',`business_4` = '$business_4',`business_5` = '$business_5',`business_6` = '$business_6',`business_7` = '$business_7',`business_8` = '$business_8',`business_9` = '$business_9',`business_10` = '$business_10' WHERE fieldlevelriskid='$fieldlevelriskid'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);

        $query_update_employee = "UPDATE `infogathering_pdf` SET `company` = '$business' WHERE fieldlevelriskid='$fieldlevelriskid' AND infogatheringid='$infogatheringid'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    }

    include ('marketing_information_pdf.php');
    echo marketing_information_pdf($dbc,$infogatheringid, $fieldlevelriskid);

    echo '<script type="text/javascript">
        window.location.replace("manual_reporting.php?type=infogathering"); </script>';
