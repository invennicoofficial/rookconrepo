<?php
$bio = filter_var(htmlentities($_POST['bio']),FILTER_SANITIZE_STRING);
$quote_description = filter_var(htmlentities($_POST['quote_description']),FILTER_SANITIZE_STRING);
$description = filter_var(htmlentities($_POST['description']),FILTER_SANITIZE_STRING);
$property_information = filter_var(htmlentities($_POST['property_information']),FILTER_SANITIZE_STRING);
$general_comments = filter_var(htmlentities($_POST['general_comments']),FILTER_SANITIZE_STRING);
$comments = filter_var(htmlentities($_POST['comments']),FILTER_SANITIZE_STRING);
$notes = filter_var(htmlentities($_POST['notes']),FILTER_SANITIZE_STRING);
$medical_details_diagnosis = filter_var(htmlentities($_POST['medical_details_diagnosis']),FILTER_SANITIZE_STRING);
$medical_details_allergies = filter_var(htmlentities($_POST['medical_details_allergies']),FILTER_SANITIZE_STRING);
$medical_details_equipment = filter_var(htmlentities($_POST['medical_details_equipment']),FILTER_SANITIZE_STRING);
$medical_details_first_aid_cpr = filter_var(htmlentities($_POST['medical_details_first_aid_cpr']),FILTER_SANITIZE_STRING);
$medications_daily_log_notes = filter_var(htmlentities($_POST['medications_daily_log_notes']),FILTER_SANITIZE_STRING);
$medications_management_comments = filter_var(htmlentities($_POST['medications_management_comments']),FILTER_SANITIZE_STRING);
$seizure_protocol_details = filter_var(htmlentities($_POST['seizure_protocol_details']),FILTER_SANITIZE_STRING);
$slip_fall_protocol_details = filter_var(htmlentities($_POST['slip_fall_protocol_details']),FILTER_SANITIZE_STRING);
$transfer_protocol_details = filter_var(htmlentities($_POST['transfer_protocol_details']),FILTER_SANITIZE_STRING);
$toileting_protocol_details = filter_var(htmlentities($_POST['toileting_protocol_details']),FILTER_SANITIZE_STRING);
$bathing_protocol_details = filter_var(htmlentities($_POST['bathing_protocol_details']),FILTER_SANITIZE_STRING);
$gtube_protocol_details = filter_var(htmlentities($_POST['gtube_protocol_details']),FILTER_SANITIZE_STRING);
$oxygen_protocol_details = filter_var(htmlentities($_POST['oxygen_protocol_details']),FILTER_SANITIZE_STRING);
$protocols_daily_log_notes = filter_var(htmlentities($_POST['protocols_daily_log_notes']),FILTER_SANITIZE_STRING);
$protocols_management_comments = filter_var(htmlentities($_POST['protocols_management_comments']),FILTER_SANITIZE_STRING);
$routines_daily_log_notes = filter_var(htmlentities($_POST['routines_daily_log_notes']),FILTER_SANITIZE_STRING);
$routines_management_comments = filter_var(htmlentities($_POST['routines_management_comments']),FILTER_SANITIZE_STRING);
$communication_daily_log_notes = filter_var(htmlentities($_POST['communication_daily_log_notes']),FILTER_SANITIZE_STRING);
$communication_management_comments = filter_var(htmlentities($_POST['communication_management_comments']),FILTER_SANITIZE_STRING);
$activities_daily_log_notes = filter_var(htmlentities($_POST['activities_daily_log_notes']),FILTER_SANITIZE_STRING);
$activities_management_comments = filter_var(htmlentities($_POST['activities_management_comments']),FILTER_SANITIZE_STRING);

$get_desc = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(contactdescid) AS contactdescid FROM contacts_description WHERE contactid='$contactid'"));
if($get_desc['contactdescid'] > 0) {
	$query_update_desc = "UPDATE `contacts_description` SET `bio` = '$bio', `quote_description` = '$quote_description', `property_information` = '$property_information', `general_comments` = '$general_comments', `comments` = '$comments', `notes` = '$notes', `medical_details_diagnosis` = '$medical_details_diagnosis', `medical_details_allergies` = '$medical_details_allergies', `medical_details_equipment` = '$medical_details_equipment', `medical_details_first_aid_cpr` = '$medical_details_first_aid_cpr', `medications_daily_log_notes` = '$medications_daily_log_notes', `medications_management_comments` = '$medications_management_comments', `seizure_protocol_details` = '$seizure_protocol_details', `slip_fall_protocol_details` = '$slip_fall_protocol_details', `transfer_protocol_details` = '$transfer_protocol_details', `toileting_protocol_details` = '$toileting_protocol_details', `bathing_protocol_details` = '$bathing_protocol_details', `gtube_protocol_details` = '$gtube_protocol_details', `oxygen_protocol_details` = '$oxygen_protocol_details', `protocols_daily_log_notes` = '$protocols_daily_log_notes', `protocols_management_comments` = '$protocols_management_comments', `routines_daily_log_notes` = '$routines_daily_log_notes', `routines_management_comments` = '$routines_management_comments', `communication_daily_log_notes` = '$communication_daily_log_notes', `communication_management_comments` = '$communication_management_comments', `activities_daily_log_notes` = '$activities_daily_log_notes', `activities_management_comments` = '$activities_management_comments' WHERE `contactid` = '$contactid'";
	$result_update_desc	= mysqli_query($dbc, $query_update_desc);
} else {
	$query_insert_desc = "INSERT INTO `contacts_description` (`contactid`, `bio`, `quote_description`, `property_information`, `general_comments`, `comments`, `notes`, `medical_details_diagnosis`, `medical_details_allergies`, `medical_details_equipment`, `medical_details_first_aid_cpr`, `medications_daily_log_notes`, `medications_management_comments`, `seizure_protocol_details`, `slip_fall_protocol_details`, `transfer_protocol_details`, `toileting_protocol_details`, `bathing_protocol_details`, `gtube_protocol_details`, `oxygen_protocol_details`, `protocols_daily_log_notes`, `protocols_management_comments`, `routines_daily_log_notes`, `routines_management_comments`, `communication_daily_log_notes`, `communication_management_comments`, `activities_daily_log_notes`, `activities_management_comments`) VALUES ('$contactid', '$bio', '$quote_description', '$property_information', '$general_comments', '$comments', '$notes', '$medical_details_diagnosis', '$medical_details_allergies', '$medical_details_equipment', '$medical_details_first_aid_cpr', '$medications_daily_log_notes', '$medications_management_comments', '$seizure_protocol_details', '$slip_fall_protocol_details', '$transfer_protocol_details', '$toileting_protocol_details', '$bathing_protocol_details', '$gtube_protocol_details', '$oxygen_protocol_details', '$protocols_daily_log_notes', '$protocols_management_comments', '$routines_daily_log_notes', '$routines_management_comments', '$communication_daily_log_notes', '$communication_management_comments', '$activities_daily_log_notes', '$activities_management_comments')";
	$result_insert_desc= mysqli_query($dbc, $query_insert_desc);
}