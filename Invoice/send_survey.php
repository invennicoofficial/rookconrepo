<?php
    //Survey
        //$survey = $_POST['survey'];
        //$result_survey = mysqli_query($dbc, "SELECT surveyid FROM crm_feedback_survey_form WHERE service='$survey'");
        //$types = array();

        //while(($row =  mysqli_fetch_assoc($result_survey))) {
        //    $types[] = $row['surveyid'];
        //}
        //$rand_keys = array_rand($types);
        //$surveyid = $types[$rand_keys];

        $pid = $get_invoice['patientid'];
        $tid =  $get_invoice['therapistsid'];
        $surveyid = $_POST['survey'];

        $send_date = date('Y-m-d');
        $query_insert_inventory = "INSERT INTO `crm_feedback_survey_result` (`surveyid`, `patientid`, `therapistid`, `send_date`) VALUES	('$surveyid', '$pid', '$tid', '$send_date')";
        $result_insert_inventory = mysqli_query($dbc, $query_insert_inventory);
        $surveyresultid = mysqli_insert_id($dbc);

        $survey_link = WEBSITE_URL.'/CRM/feedback_survey.php?s='.$surveyresultid;

        $feedback_survey_email_body = html_entity_decode(get_config($dbc, 'feedback_survey_email_body'));

        $email_body = str_replace("[Customer Name]", $patients, $feedback_survey_email_body);
        $email_body = str_replace("[Survey Link]", $survey_link, $email_body);
        $email = get_email($dbc, $get_invoice['patientid']);
        $subject = get_config($dbc, 'feedback_survey_email_subject');

        send_email('', $email, '', '', $subject, $email_body, '');

        $query_update_booking = "UPDATE `invoice` SET `survey` = '$surveyid' WHERE `invoiceid` = '$invoiceid'";
        $result_update_booking = mysqli_query($dbc, $query_update_booking);


    //Survey