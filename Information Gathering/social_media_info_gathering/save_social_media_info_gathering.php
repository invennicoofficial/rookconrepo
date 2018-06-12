<?php
    $today_date = date('Y-m-d');
    $business = filter_var(htmlentities($_POST['business']),FILTER_SANITIZE_STRING);
	$big_picture = filter_var(htmlentities($_POST['big_picture']),FILTER_SANITIZE_STRING);
	$goal = implode(',',$_POST['goal']);
	$culture = filter_var(htmlentities($_POST['culture']),FILTER_SANITIZE_STRING);
	$community = filter_var(htmlentities($_POST['community']),FILTER_SANITIZE_STRING);
	$conversation = filter_var(htmlentities($_POST['conversation']),FILTER_SANITIZE_STRING);
	$location = filter_var(htmlentities($_POST['location']),FILTER_SANITIZE_STRING);
	$age_range = filter_var(htmlentities($_POST['age_range']),FILTER_SANITIZE_STRING);
	$gender = filter_var(htmlentities($_POST['gender']),FILTER_SANITIZE_STRING);
	$language1 = filter_var(htmlentities($_POST['language1']),FILTER_SANITIZE_STRING);
	$interests = filter_var(htmlentities($_POST['interests']),FILTER_SANITIZE_STRING);
	$character_persona = implode(',',$_POST['character_persona']);
	$tone = implode(',',$_POST['tone']);
	$language = implode(',',$_POST['language']);
	$purpose = implode(',',$_POST['purpose']);
	$features_product_s_e = filter_var(htmlentities($_POST['features_product_s_e']),FILTER_SANITIZE_STRING);
	$channels = implode(',',$_POST['channels']);
	$research = filter_var(htmlentities($_POST['research']),FILTER_SANITIZE_STRING);
	$repurposing = filter_var(htmlentities($_POST['repurposing']),FILTER_SANITIZE_STRING);
	$writing = filter_var(htmlentities($_POST['writing']),FILTER_SANITIZE_STRING);
	$promotion = filter_var(htmlentities($_POST['promotion']),FILTER_SANITIZE_STRING);
	$creative = filter_var(htmlentities($_POST['creative']),FILTER_SANITIZE_STRING);
	$quality_assurance = filter_var(htmlentities($_POST['quality_assurance']),FILTER_SANITIZE_STRING);

	if(empty($_POST['fieldlevelriskid'])) {
        $query_insert_site = "INSERT INTO `info_social_media_info_gathering` (`infogatheringid`, `today_date`, `business`, `big_picture`, `goal`, `culture`, `community`, `conversation`, `location`, `age_range`, `gender`, `language1`, `interests`, `character_persona`, `tone`, `language`, `purpose`, `features_product_s_e`, `channels`, `research`, `repurposing`, `writing`, `promotion`, `creative`, `quality_assurance`) VALUES ('$infogatheringid','$today_date', '$business', '$big_picture', '$goal','$culture','$community','$conversation','$location','$age_range','$gender','$language1','$interests','$character_persona','$tone','$language','$purpose','$features_product_s_e','$channels','$research','$repurposing','$writing','$promotion','$creative','$quality_assurance')";

		$result_insert_site	= mysqli_query($dbc, $query_insert_site);
        $fieldlevelriskid = mysqli_insert_id($dbc);

        $created_by = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);
        $query_insert_site = "INSERT INTO `infogathering_pdf` (`infogatheringid`, `fieldlevelriskid`, `today_date`, `created_by`, `company`) VALUES	('$infogatheringid', '$fieldlevelriskid', '$today_date', '$created_by', '$business')";
        $result_insert_site	= mysqli_query($dbc, $query_insert_site);

	} else {
        $fieldlevelriskid = $_POST['fieldlevelriskid'];
        $query_update_employee = "UPDATE `info_social_media_info_gathering` SET `business`= '$business', `big_picture` = '$big_picture', `goal` = '$goal', `culture` = '$culture', `community` = '$community', `conversation` = '$conversation', `location` = '$location', `age_range` = '$age_range', `gender` = '$gender', `language1` = '$language1', `interests` = '$interests', `character_persona` = '$character_persona', `tone` = '$tone', `language` = '$language', `purpose` = '$purpose', `features_product_s_e` = '$features_product_s_e', `channels` = '$channels', `research` = '$research', `repurposing` = '$repurposing', `writing` = '$writing', `promotion` = '$promotion', `creative` = '$creative', `quality_assurance` = '$quality_assurance' WHERE fieldlevelriskid='$fieldlevelriskid'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);

        $query_update_employee = "UPDATE `infogathering_pdf` SET `company` = '$business' WHERE fieldlevelriskid='$fieldlevelriskid' AND infogatheringid='$infogatheringid'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);

    }

    include ('social_media_info_gathering_pdf.php');
    echo social_media_info_gathering_pdf($dbc,$infogatheringid, $fieldlevelriskid);

    echo '<script type="text/javascript">
        window.location.replace("manual_reporting.php?type=infogathering"); </script>';



















