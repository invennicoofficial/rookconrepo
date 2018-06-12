 <?php
    $today_date = date('Y-m-d');
    $business = filter_var(htmlentities($_POST['business']),FILTER_SANITIZE_STRING);
	$business_1 = filter_var(htmlentities($_POST['business_1']),FILTER_SANITIZE_STRING);
	$business_2 = filter_var(htmlentities($_POST['business_2']),FILTER_SANITIZE_STRING);
	$business_3 = filter_var(htmlentities($_POST['business_3']),FILTER_SANITIZE_STRING);
	$business_4 = filter_var(htmlentities($_POST['business_4']),FILTER_SANITIZE_STRING);
	$business_5 = filter_var(htmlentities($_POST['business_5']),FILTER_SANITIZE_STRING);
	$business_6 = filter_var(htmlentities($_POST['business_6']),FILTER_SANITIZE_STRING);
	$client_1 = filter_var(htmlentities($_POST['client_1']),FILTER_SANITIZE_STRING);
	$client_2 = filter_var(htmlentities($_POST['client_2']),FILTER_SANITIZE_STRING);
	$customer_service_1 = filter_var(htmlentities($_POST['customer_service_1']),FILTER_SANITIZE_STRING);
	$customer_service_2 = filter_var(htmlentities($_POST['customer_service_2']),FILTER_SANITIZE_STRING);
	$customer_service_3 = filter_var(htmlentities($_POST['customer_service_3']),FILTER_SANITIZE_STRING);
	$social_1 = filter_var(htmlentities($_POST['social_1']),FILTER_SANITIZE_STRING);
	$social_2 = filter_var(htmlentities($_POST['social_2']),FILTER_SANITIZE_STRING);
	$social_3 = filter_var(htmlentities($_POST['social_3']),FILTER_SANITIZE_STRING);

    if(empty($_POST['fieldlevelriskid'])) {
        $query_insert_site = "INSERT INTO `info_social_media_start_up_questionnaire` (`infogatheringid`, `today_date`, `business`, `business_1`, `business_2`, `business_3`, `business_4`, `business_5`, `business_6`, `client_1`, `client_2`, `customer_service_1`, `customer_service_2`, `customer_service_3`, `social_1`, `social_2`, `social_3`) VALUES	('$infogatheringid','$today_date', '$business', '$business_1','$business_2','$business_3','$business_4','$business_5','$business_6','$client_1','$client_2','$customer_service_1','$customer_service_2','$customer_service_3','$social_1','$social_2','$social_3')";
        $result_insert_site	= mysqli_query($dbc, $query_insert_site);
        $fieldlevelriskid = mysqli_insert_id($dbc);

        $created_by = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);
        $query_insert_site = "INSERT INTO `infogathering_pdf` (`infogatheringid`, `fieldlevelriskid`, `today_date`, `created_by`, `company`) VALUES	('$infogatheringid', '$fieldlevelriskid', '$today_date', '$created_by', '$business')";
        $result_insert_site	= mysqli_query($dbc, $query_insert_site);

    } else {
        $fieldlevelriskid = $_POST['fieldlevelriskid'];
        $query_update_employee = "UPDATE `info_social_media_start_up_questionnaire` SET `business` = '$business', `business_1` = '$business_1', `business_2` = '$business_2',`business_3` = '$business_3',`business_4` = '$business_4',`business_5` = '$business_5',`business_6` = '$business_6',`client_1` = '$client_1',`client_2` = '$client_2',`customer_service_1` = '$customer_service_1',`customer_service_2` = '$customer_service_2',`customer_service_3` = '$customer_service_3',`social_1` = '$social_1',`social_2` = '$social_2',`social_3` = '$social_3' WHERE fieldlevelriskid='$fieldlevelriskid'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);

        $query_update_employee = "UPDATE `infogathering_pdf` SET `company` = '$business' WHERE fieldlevelriskid='$fieldlevelriskid' AND infogatheringid='$infogatheringid'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    }

    include ('social_media_start_up_questionnaire_pdf.php');
    echo social_media_start_up_questionnaire_pdf($dbc,$infogatheringid, $fieldlevelriskid);

    echo '<script type="text/javascript">
        window.location.replace("manual_reporting.php?type=infogathering"); </script>';
