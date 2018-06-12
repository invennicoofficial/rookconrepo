<?php
$contact_since = filter_var($_POST['contact_since'],FILTER_SANITIZE_STRING);
$date_of_last_contact = filter_var($_POST['date_of_last_contact'],FILTER_SANITIZE_STRING);
$start_date = filter_var($_POST['start_date'],FILTER_SANITIZE_STRING);
$expiry_date = filter_var($_POST['expiry_date'],FILTER_SANITIZE_STRING);
$renewal_date = filter_var($_POST['renewal_date'],FILTER_SANITIZE_STRING);
$lease_term_date = filter_var($_POST['lease_term_date'],FILTER_SANITIZE_STRING);
$date_contract_signed = filter_var($_POST['date_contract_signed'],FILTER_SANITIZE_STRING);
$option_to_renew_date = filter_var($_POST['option_to_renew_date'],FILTER_SANITIZE_STRING);
$rate_increase_date = filter_var($_POST['rate_increase_date'],FILTER_SANITIZE_STRING);
$insurance_expiry_date = filter_var($_POST['insurance_expiry_date'],FILTER_SANITIZE_STRING);
$account_expiry_date = filter_var($_POST['account_expiry_date'],FILTER_SANITIZE_STRING);
$probation_end_date = filter_var($_POST['probation_end_date'],FILTER_SANITIZE_STRING);
$probation_expiry_reminder_date = filter_var($_POST['probation_expiry_reminder_date'],FILTER_SANITIZE_STRING);
$medications_completed_date = filter_var($_POST['medications_completed_date'],FILTER_SANITIZE_STRING);
$medications_management_completed_date = filter_var($_POST['medications_management_completed_date'],FILTER_SANITIZE_STRING);
$protocols_completed_date = filter_var($_POST['protocols_completed_date'],FILTER_SANITIZE_STRING);
$protocols_management_completed_date = filter_var($_POST['protocols_management_completed_date'],FILTER_SANITIZE_STRING);
$routines_completed_date = filter_var($_POST['routines_completed_date'],FILTER_SANITIZE_STRING);
$routines_management_completed_date = filter_var($_POST['routines_management_completed_date'],FILTER_SANITIZE_STRING);
$communication_completed_date = filter_var($_POST['communication_completed_date'],FILTER_SANITIZE_STRING);
$communication_management_completed_date = filter_var($_POST['communication_management_completed_date'],FILTER_SANITIZE_STRING);
$activities_completed_date = filter_var($_POST['activities_completed_date'],FILTER_SANITIZE_STRING);
$activities_management_completed_date = filter_var($_POST['activities_management_completed_date'],FILTER_SANITIZE_STRING);
$company_benefit_start_date = filter_var($_POST['company_benefit_start_date'],FILTER_SANITIZE_STRING);

$get_dates = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(contactdateid) AS contactdateid FROM contacts_dates WHERE contactid='$contactid'"));
if($get_dates['contactdateid'] > 0) {
	$query_update_date = "UPDATE `contacts_dates` SET `contact_since` = '$contact_since', `date_of_last_contact` = '$date_of_last_contact', `start_date` = '$start_date', `expiry_date` = '$expiry_date', `renewal_date` = '$renewal_date', `lease_term_date` = '$lease_term_date', `date_contract_signed` = '$date_contract_signed', `option_to_renew_date` = '$option_to_renew_date', `rate_increase_date` = '$rate_increase_date', `insurance_expiry_date` = '$insurance_expiry_date', `account_expiry_date` = '$account_expiry_date', `probation_end_date` = '$probation_end_date', `probation_expiry_reminder_date` = '$probation_expiry_reminder_date', `medications_completed_date` = '$medications_completed_date', `medications_management_completed_date` = '$medications_management_completed_date', `protocols_completed_date` = '$protocols_completed_date', `protocols_management_completed_date` = '$protocols_management_completed_date', `routines_completed_date` = '$routines_completed_date', `routines_management_completed_date` = '$routines_management_completed_date', `communication_completed_date` = '$communication_completed_date', `communication_management_completed_date` = '$communication_management_completed_date', `activities_completed_date` = '$activities_completed_date', `activities_management_completed_date` = '$activities_management_completed_date', `company_benefit_start_date` = '$company_benefit_start_date' WHERE `contactid` = '$contactid'";
	$result_update_date	= mysqli_query($dbc, $query_update_date);
} else {
	$query_insert_date = "INSERT INTO `contacts_dates` (`contactid`, `contact_since`, `date_of_last_contact`, `start_date`, `expiry_date`, `renewal_date`, `lease_term_date`, `date_contract_signed`, `option_to_renew_date`, `rate_increase_date`, `insurance_expiry_date`, `account_expiry_date`, `probation_end_date`, `probation_expiry_reminder_date`, `medications_completed_date`, `medications_management_completed_date`, `protocols_completed_date`, `protocols_management_completed_date`, `routines_completed_date`, `routines_management_completed_date`, `communication_completed_date`, `communication_management_completed_date`, `activities_completed_date`, `activities_management_completed_date`, `company_benefit_start_date`) VALUES ('$contactid', '$contact_since', '$date_of_last_contact', '$start_date', '$expiry_date', '$renewal_date', '$lease_term_date', '$date_contract_signed', '$option_to_renew_date', '$rate_increase_date', '$insurance_expiry_date', '$account_expiry_date', '$probation_end_date', '$probation_expiry_reminder_date', '$medications_completed_date', '$medications_management_completed_date', '$protocols_completed_date', '$protocols_management_completed_date', '$routines_completed_date', '$routines_management_completed_date', '$communication_completed_date', '$communication_management_completed_date', '$activities_completed_date', '$activities_management_completed_date', '$company_benefit_start_date')";
	$result_insert_date = mysqli_query($dbc, $query_insert_date);
}