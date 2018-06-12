<?php
	$pid = $get_invoice['patientid'];
	$tid =  $get_invoice['therapistsid'];

	$send_date = date('Y-m-d');
	$query_insert = "INSERT INTO `crm_recommend` (`surveyid`, `patientid`, `therapistid`, `send_date`) VALUES	('$surveyid', '$pid', '$tid', '$send_date')";
	$result = mysqli_query($dbc, $query_insert);
	$recommendid = mysqli_insert_id($dbc);

	$link = WEBSITE_URL.'/CRM/recommend_request.php?s='.$recommendid;

	$email_body = str_replace(["[Customer Name]","[Link]"], [$patients,$link], html_entity_decode(get_config($dbc, 'crm_recommend_body')));
	$email = get_email($dbc, $get_invoice['patientid']);
	$subject = get_config($dbc, 'crm_recommend_subject');
	$from_address = get_config($dbc, 'crm_recommend_address');

	try {
		send_email($from_address, $email, '', '', $subject, $email_body, '');
	} catch  (Exception $e) {
		echo "<script> alert('Unable to send email to patient.') </script>";
	}