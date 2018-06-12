 <?php
    $today_date = date('Y-m-d');

	$client_name = filter_var(htmlentities($_POST['client_name']),FILTER_SANITIZE_STRING);
	$phone_number = filter_var(htmlentities($_POST['phone_number']),FILTER_SANITIZE_STRING);
	$client_email = filter_var(htmlentities($_POST['client_email']),FILTER_SANITIZE_STRING);
	$gen_info_1 = filter_var(htmlentities($_POST['gen_info_1']),FILTER_SANITIZE_STRING);
	$gen_info_2 = filter_var(htmlentities($_POST['gen_info_2']),FILTER_SANITIZE_STRING);
	$gen_info_3 = filter_var(htmlentities($_POST['gen_info_3']),FILTER_SANITIZE_STRING);
	$gen_info_4 = filter_var(htmlentities($_POST['gen_info_4']),FILTER_SANITIZE_STRING);
	$gen_info_5 = filter_var(htmlentities($_POST['gen_info_5']),FILTER_SANITIZE_STRING);
	$gen_info_6 = filter_var(htmlentities($_POST['gen_info_6']),FILTER_SANITIZE_STRING);
	$gen_info_7 = filter_var(htmlentities($_POST['gen_info_7']),FILTER_SANITIZE_STRING);
	$your_market_1 = filter_var(htmlentities($_POST['your_market_1']),FILTER_SANITIZE_STRING);
	$your_market_2 = filter_var(htmlentities($_POST['your_market_2']),FILTER_SANITIZE_STRING);
	$your_market_3 = filter_var(htmlentities($_POST['your_market_3']),FILTER_SANITIZE_STRING);
	$your_market_4 = filter_var(htmlentities($_POST['your_market_4']),FILTER_SANITIZE_STRING);
	$your_market_5 = filter_var(htmlentities($_POST['your_market_5']),FILTER_SANITIZE_STRING);
	$your_market_6 = filter_var(htmlentities($_POST['your_market_6']),FILTER_SANITIZE_STRING);
	$your_market_7 = filter_var(htmlentities($_POST['your_market_7']),FILTER_SANITIZE_STRING);
	$identity_brand_1 = filter_var(htmlentities($_POST['identity_brand_1']),FILTER_SANITIZE_STRING);
	$identity_brand_2 = filter_var(htmlentities($_POST['identity_brand_2']),FILTER_SANITIZE_STRING);
	$identity_brand_3 = filter_var(htmlentities($_POST['identity_brand_3']),FILTER_SANITIZE_STRING);
	$identity_brand_4 = filter_var(htmlentities($_POST['identity_brand_4']),FILTER_SANITIZE_STRING);
	$identity_brand_5 = filter_var(htmlentities($_POST['identity_brand_5']),FILTER_SANITIZE_STRING);

    if(empty($_POST['fieldlevelriskid'])) {
        $query_insert_site = "INSERT INTO `info_branding_questionnaire` (`infogatheringid`, `today_date`, `client_name`, `phone_number`, `client_email`, `gen_info_1`, `gen_info_2`, `gen_info_3`, `gen_info_4`, `gen_info_5`, `gen_info_6`, `gen_info_7`, `your_market_1`, `your_market_2`, `your_market_3`, `your_market_4`, `your_market_5`, `your_market_6`, `your_market_7`, `identity_brand_1`, `identity_brand_2`, `identity_brand_3`, `identity_brand_4`, `identity_brand_5`) VALUES	('$infogatheringid','$today_date','$client_name','$phone_number','$client_email','$gen_info_1','$gen_info_2','$gen_info_3','$gen_info_4','$gen_info_5','$gen_info_6','$gen_info_7','$your_market_1','$your_market_2','$your_market_3','$your_market_4','$your_market_5','$your_market_6','$your_market_7','$identity_brand_1','$identity_brand_2','$identity_brand_3','$identity_brand_4','$identity_brand_5')";
        $result_insert_site	= mysqli_query($dbc, $query_insert_site);
        $fieldlevelriskid = mysqli_insert_id($dbc);

        $created_by = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);
        $query_insert_site = "INSERT INTO `infogathering_pdf` (`infogatheringid`, `fieldlevelriskid`, `today_date`, `created_by`, `company`) VALUES	('$infogatheringid', '$fieldlevelriskid', '$today_date', '$created_by', '$client_name')";
        $result_insert_site	= mysqli_query($dbc, $query_insert_site);

    } else {
        $fieldlevelriskid = $_POST['fieldlevelriskid'];
        $query_update_employee = "UPDATE `info_branding_questionnaire` SET `client_name` = '$client_name', `phone_number` = '$phone_number',`client_email` = '$client_email',`gen_info_1` = '$gen_info_1',`gen_info_2` = '$gen_info_2',`gen_info_3` = '$gen_info_3',`gen_info_4` = '$gen_info_4',`gen_info_5` = '$gen_info_5',`gen_info_6` = '$gen_info_6',`gen_info_7` = '$gen_info_7',`your_market_1` = '$your_market_1',`your_market_2` = '$your_market_2',`your_market_3` = '$your_market_3',`your_market_4` = '$your_market_4',`your_market_5` = '$your_market_5',`your_market_6` = '$your_market_6',`your_market_7` = '$your_market_7',`identity_brand_1` = '$identity_brand_1',`identity_brand_2` = '$identity_brand_2',`identity_brand_3` = '$identity_brand_3',`identity_brand_4` = '$identity_brand_4',`identity_brand_5` = '$identity_brand_5' WHERE fieldlevelriskid='$fieldlevelriskid'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);

        $query_update_employee = "UPDATE `infogathering_pdf` SET `company` = '$client_name' WHERE fieldlevelriskid='$fieldlevelriskid' AND infogatheringid='$infogatheringid'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);

    }

    include ('branding_questionnaire_pdf.php');
    echo branding_questionnaire_pdf($dbc,$infogatheringid, $fieldlevelriskid);

    echo '<script type="text/javascript">         window.location.replace("manual_reporting.php?type=infogathering"); </script>';
