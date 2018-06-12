 <?php
    $today_date = date('Y-m-d');

    $client_name = filter_var(htmlentities($_POST['client_name']),FILTER_SANITIZE_STRING);
    $phone_num = filter_var(htmlentities($_POST['phone_num']),FILTER_SANITIZE_STRING);
    $email = filter_var(htmlentities($_POST['email']),FILTER_SANITIZE_STRING);
    $work_completed = filter_var(htmlentities($_POST['work_completed']),FILTER_SANITIZE_STRING);
    $project_details = implode(',',$_POST['project_details']);
    $branding_1 = filter_var(htmlentities($_POST['branding_1']),FILTER_SANITIZE_STRING);
    $branding_2 = filter_var(htmlentities($_POST['branding_2']),FILTER_SANITIZE_STRING);
    $branding_3 = filter_var(htmlentities($_POST['branding_3']),FILTER_SANITIZE_STRING);
    $branding_4 = filter_var(htmlentities($_POST['branding_4']),FILTER_SANITIZE_STRING);
    $hosting_email_1 = filter_var(htmlentities($_POST['hosting_email_1']),FILTER_SANITIZE_STRING);
    $hosting_email_2 = filter_var(htmlentities($_POST['hosting_email_2']),FILTER_SANITIZE_STRING);
    $hosting_email_3 = filter_var(htmlentities($_POST['hosting_email_3']),FILTER_SANITIZE_STRING);
    $hosting_email_4 = filter_var(htmlentities($_POST['hosting_email_4']),FILTER_SANITIZE_STRING);
    $hosting_email_5 = filter_var(htmlentities($_POST['hosting_email_5']),FILTER_SANITIZE_STRING);
    $hosting_email_6 = filter_var(htmlentities($_POST['hosting_email_6']),FILTER_SANITIZE_STRING);
    $hosting_email_7 = filter_var(htmlentities($_POST['hosting_email_7']),FILTER_SANITIZE_STRING);
    $hosting_email_8 = filter_var(htmlentities($_POST['hosting_email_8']),FILTER_SANITIZE_STRING);
    $hosting_email_9 = filter_var(htmlentities($_POST['hosting_email_9']),FILTER_SANITIZE_STRING);
    $hosting_email_10 = filter_var(htmlentities($_POST['hosting_email_10']),FILTER_SANITIZE_STRING);
    $hosting_email_11 = filter_var(htmlentities($_POST['hosting_email_11']),FILTER_SANITIZE_STRING);
    $hosting_email_12 = filter_var(htmlentities($_POST['hosting_email_12']),FILTER_SANITIZE_STRING);
    $hosting_email_13 = filter_var(htmlentities($_POST['hosting_email_13']),FILTER_SANITIZE_STRING);
    $hosting_email_14 = filter_var(htmlentities($_POST['hosting_email_14']),FILTER_SANITIZE_STRING);
    $web_development_1 = filter_var(htmlentities($_POST['web_development_1']),FILTER_SANITIZE_STRING);
    $web_development_2 = filter_var(htmlentities($_POST['web_development_2']),FILTER_SANITIZE_STRING);
    $web_development_3 = filter_var(htmlentities($_POST['web_development_3']),FILTER_SANITIZE_STRING);
    $web_development_4 = filter_var(htmlentities($_POST['web_development_4']),FILTER_SANITIZE_STRING);
    $web_development_5 = filter_var(htmlentities($_POST['web_development_5']),FILTER_SANITIZE_STRING);
    $web_development_6 = filter_var(htmlentities($_POST['web_development_6']),FILTER_SANITIZE_STRING);
    $web_development_7 = filter_var(htmlentities($_POST['web_development_7']),FILTER_SANITIZE_STRING);
    $web_development_8 = filter_var(htmlentities($_POST['web_development_8']),FILTER_SANITIZE_STRING);
    $web_development_9 = filter_var(htmlentities($_POST['web_development_9']),FILTER_SANITIZE_STRING);
    $web_development_10 = filter_var(htmlentities($_POST['web_development_10']),FILTER_SANITIZE_STRING);
    $web_development_11 = filter_var(htmlentities($_POST['web_development_11']),FILTER_SANITIZE_STRING);
    $web_development_12 = filter_var(htmlentities($_POST['web_development_12']),FILTER_SANITIZE_STRING);
    $web_development_13 = filter_var(htmlentities($_POST['web_development_13']),FILTER_SANITIZE_STRING);
    $web_development_14 = filter_var(htmlentities($_POST['web_development_14']),FILTER_SANITIZE_STRING);
    $web_development_15 = filter_var(htmlentities($_POST['web_development_15']),FILTER_SANITIZE_STRING);
    $web_development_16 = filter_var(htmlentities($_POST['web_development_16']),FILTER_SANITIZE_STRING);
    $web_development_17 = filter_var(htmlentities($_POST['web_development_17']),FILTER_SANITIZE_STRING);
    $web_development_18 = filter_var(htmlentities($_POST['web_development_18']),FILTER_SANITIZE_STRING);
    $web_development_19 = filter_var(htmlentities($_POST['web_development_19']),FILTER_SANITIZE_STRING);
    $web_development_20 = filter_var(htmlentities($_POST['web_development_20']),FILTER_SANITIZE_STRING);
    $web_development_21 = filter_var(htmlentities($_POST['web_development_21']),FILTER_SANITIZE_STRING);
    $web_development_22 = filter_var(htmlentities($_POST['web_development_22']),FILTER_SANITIZE_STRING);
    $web_development_23 = filter_var(htmlentities($_POST['web_development_23']),FILTER_SANITIZE_STRING);
    $web_development_24 = filter_var(htmlentities($_POST['web_development_24']),FILTER_SANITIZE_STRING);
    $web_development_25 = filter_var(htmlentities($_POST['web_development_25']),FILTER_SANITIZE_STRING);
    $web_development_26 = filter_var(htmlentities($_POST['web_development_26']),FILTER_SANITIZE_STRING);
    $web_development_27 = filter_var(htmlentities($_POST['web_development_27']),FILTER_SANITIZE_STRING);
    $notes_comments = filter_var(htmlentities($_POST['notes_comments']),FILTER_SANITIZE_STRING);

    $project_details_1 = filter_var(htmlentities($_POST['project_details_1']),FILTER_SANITIZE_STRING);
    $branding_5 = filter_var(htmlentities($_POST['branding_5']),FILTER_SANITIZE_STRING);
    $landing_1 = filter_var(htmlentities($_POST['landing_1']),FILTER_SANITIZE_STRING);
    $web_development_28 = filter_var(htmlentities($_POST['web_development_28']),FILTER_SANITIZE_STRING);
    $web_development_29 = filter_var(htmlentities($_POST['web_development_29']),FILTER_SANITIZE_STRING);
    $web_development_30 = filter_var(htmlentities($_POST['web_development_30']),FILTER_SANITIZE_STRING);
    $web_development_31 = filter_var(htmlentities($_POST['web_development_31']),FILTER_SANITIZE_STRING);
    $web_development_32 = filter_var(htmlentities($_POST['web_development_32']),FILTER_SANITIZE_STRING);
    $web_development_33 = filter_var(htmlentities($_POST['web_development_33']),FILTER_SANITIZE_STRING);
    $web_development_34 = filter_var(htmlentities($_POST['web_development_34']),FILTER_SANITIZE_STRING);
    $web_development_35 = filter_var(htmlentities($_POST['web_development_35']),FILTER_SANITIZE_STRING);
    $web_development_36 = filter_var(htmlentities($_POST['web_development_36']),FILTER_SANITIZE_STRING);
    $web_development_37 = filter_var(htmlentities($_POST['web_development_37']),FILTER_SANITIZE_STRING);
    $web_development_38 = filter_var(htmlentities($_POST['web_development_38']),FILTER_SANITIZE_STRING);

    if(empty($_POST['fieldlevelriskid'])) {
        $query_insert_site = "INSERT INTO `info_website_information_gathering_form` (`infogatheringid`, `today_date`, `client_name`, `phone_num`, `email`, `work_completed`, `project_details`, `branding_1`, `branding_2`, `branding_3`, `branding_4`, `hosting_email_1`, `hosting_email_2`, `hosting_email_3`, `hosting_email_4`, `hosting_email_5`, `hosting_email_6`, `hosting_email_7`, `hosting_email_8`, `hosting_email_9`, `hosting_email_10`, `hosting_email_11`, `hosting_email_12`, `hosting_email_13`, `hosting_email_14`, `web_development_1`, `web_development_2`, `web_development_3`, `web_development_4`, `web_development_5`, `web_development_6`, `web_development_7`, `web_development_8`, `web_development_9`, `web_development_10`, `web_development_11`, `web_development_12`, `web_development_13`, `web_development_14`, `web_development_15`, `web_development_16`, `web_development_17`, `web_development_18`, `web_development_19`, `web_development_20`, `web_development_21`, `web_development_22`, `web_development_23`, `web_development_24`, `web_development_25`, `web_development_26`, `web_development_27`, `notes_comments`, `project_details_1`, `branding_5`, `landing_1`, `web_development_28`, `web_development_29`, `web_development_30`, `web_development_31`, `web_development_32`, `web_development_33`, `web_development_34`, `web_development_35`, `web_development_36`, `web_development_37`, `web_development_38`) VALUES	('$infogatheringid', '$today_date', '$client_name', '$phone_num', '$email', '$work_completed', '$project_details', '$branding_1', '$branding_2', '$branding_3', '$branding_4', '$hosting_email_1', '$hosting_email_2', '$hosting_email_3', '$hosting_email_4', '$hosting_email_5', '$hosting_email_6', '$hosting_email_7', '$hosting_email_8', '$hosting_email_9', '$hosting_email_10', '$hosting_email_11', '$hosting_email_12', '$hosting_email_13', '$hosting_email_14', '$web_development_1', '$web_development_2', '$web_development_3', '$web_development_4', '$web_development_5', '$web_development_6', '$web_development_7', '$web_development_8', '$web_development_9', '$web_development_10', '$web_development_11', '$web_development_12', '$web_development_13', '$web_development_14', '$web_development_15', '$web_development_16', '$web_development_17', '$web_development_18', '$web_development_19', '$web_development_20', '$web_development_21', '$web_development_22', '$web_development_23', '$web_development_24', '$web_development_25', '$web_development_26', '$web_development_27', '$notes_comments', '$project_details_1', '$branding_5', '$landing_1', '$web_development_28', '$web_development_29', '$web_development_30', '$web_development_31', '$web_development_32', '$web_development_33', '$web_development_34', '$web_development_35', '$web_development_36', '$web_development_37', '$web_development_38')";
        $result_insert_site	= mysqli_query($dbc, $query_insert_site);
        $fieldlevelriskid = mysqli_insert_id($dbc);

        $created_by = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);
        $query_insert_site = "INSERT INTO `infogathering_pdf` (`infogatheringid`, `fieldlevelriskid`, `today_date`, `created_by`, `company`) VALUES	('$infogatheringid', '$fieldlevelriskid', '$today_date', '$created_by', '$client_name')";
        $result_insert_site	= mysqli_query($dbc, $query_insert_site);

    } else {
        $fieldlevelriskid = $_POST['fieldlevelriskid'];

        $query_update_employee = "UPDATE `info_website_information_gathering_form` SET `client_name` = '$client_name', `phone_num` = '$phone_num', `email` = '$email', `work_completed` = '$work_completed', `project_details` = '$project_details', `branding_1` = '$branding_1', `branding_2` = '$branding_2', `branding_3` = '$branding_3', `branding_4` = '$branding_4', `hosting_email_1` = '$hosting_email_1', `hosting_email_2` = '$hosting_email_2', `hosting_email_3` = '$hosting_email_3', `hosting_email_4` = '$hosting_email_4', `hosting_email_5` = '$hosting_email_5', `hosting_email_6` = '$hosting_email_6', `hosting_email_7` = '$hosting_email_7', `hosting_email_8` = '$hosting_email_8', `hosting_email_9` = '$hosting_email_9', `hosting_email_10` = '$hosting_email_10', `hosting_email_11` = '$hosting_email_11', `hosting_email_12` = '$hosting_email_12', `hosting_email_13` = '$hosting_email_13', `hosting_email_14` = '$hosting_email_14', `web_development_1` = '$web_development_1', `web_development_2` = '$web_development_2', `web_development_3` = '$web_development_3', `web_development_4` = '$web_development_4', `web_development_5` = '$web_development_5', `web_development_6` = '$web_development_6', `web_development_7` = '$web_development_7', `web_development_8` = '$web_development_8', `web_development_9` = '$web_development_9', `web_development_10` = '$web_development_10', `web_development_11` = '$web_development_11', `web_development_12` = '$web_development_12', `web_development_13` = '$web_development_13', `web_development_14` = '$web_development_14', `web_development_15` = '$web_development_15', `web_development_16` = '$web_development_16', `web_development_17` = '$web_development_17', `web_development_18` = '$web_development_18', `web_development_19` = '$web_development_19', `web_development_20` = '$web_development_20', `web_development_21` = '$web_development_21', `web_development_22` = '$web_development_22', `web_development_23` = '$web_development_23', `web_development_24` = '$web_development_24', `web_development_25` = '$web_development_25', `web_development_26` = '$web_development_26', `web_development_27` = '$web_development_27', `notes_comments` = '$notes_comments', `project_details_1` = '$project_details_1', `branding_5` = '$branding_5', `landing_1` = '$landing_1', `web_development_28` = '$web_development_28', `web_development_29` = '$web_development_29', `web_development_30` = '$web_development_30', `web_development_31` = '$web_development_31', `web_development_32` = '$web_development_32', `web_development_33` = '$web_development_33', `web_development_34` = '$web_development_34', `web_development_35` = '$web_development_35', `web_development_36` = '$web_development_36', `web_development_37` = '$web_development_37', `web_development_38` = '$web_development_38' WHERE fieldlevelriskid='$fieldlevelriskid'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);

        $query_update_employee = "UPDATE `infogathering_pdf` SET `company` = '$client_name' WHERE fieldlevelriskid='$fieldlevelriskid' AND infogatheringid='$infogatheringid'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    }

    include ('website_information_gathering_form_pdf.php');
    echo website_information_gathering_form_pdf($dbc,$infogatheringid, $fieldlevelriskid);

    echo '<script type="text/javascript">
        window.location.replace("manual_reporting.php?type=infogathering"); </script>';
