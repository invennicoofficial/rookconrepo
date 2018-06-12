<?php

$contacts_after = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `contactid` = '$contactid'"));
$contacts_cost_after = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `contacts_cost` WHERE `contactid` = '$contactid'"));
$contacts_dates_after = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `contacts_dates` WHERE `contactid` = '$contactid'"));
$contacts_description_after = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `contacts_description` WHERE `contactid` = '$contactid'"));
$contacts_upload_after = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `contacts_upload` WHERE `contactid` = '$contactid'"));
$user = get_contact($dbc, $_SESSION['contactid']);
$change_log = '';
if($_POST['contactid'] != '') {
	foreach($contacts_after as $name => $value) {
		if(str_replace(['0000-00-00','0'], '', $contacts_prior[$name]) != str_replace(['0000-00-00','0'], '', $value)) {
			$change_log .= "$name set from '{$contacts_prior[$name]}' to '$value'.\n";
		}
	}
	foreach($contacts_cost_after as $name => $value) {
		if(str_replace(['0000-00-00','0'], '', $contacts_cost_prior[$name]) != str_replace(['0000-00-00','0'], '', $value)) {
			$change_log .= "$name set from '{$contacts_cost_prior[$name]}' to '$value'.\n";
		}
	}
	foreach($contacts_dates_after as $name => $value) {
		if(str_replace(['0000-00-00','0'], '', $contacts_dates_prior[$name]) != str_replace(['0000-00-00','0'], '', $value)) {
			$change_log .= "$name set from '{$contacts_dates_prior[$name]}' to '$value'.\n";
		}
	}
	foreach($contacts_description_after as $name => $value) {
		if(str_replace(['0000-00-00','0'], '', $contacts_description_prior[$name]) != str_replace(['0000-00-00','0'], '', $value)) {
			$change_log .= "$name set from '{$contacts_description_prior[$name]}' to '$value'.\n";
		}
	}
	foreach($contacts_upload_after as $name => $value) {
		if(str_replace(['0000-00-00','0'], '', $contacts_upload_prior[$name]) != str_replace(['0000-00-00','0'], '', $value)) {
			$change_log .= "$name set from '{$contacts_upload_prior[$name]}' to '$value'.\n";
		}
	}
} else {
	foreach($_POST as $name => $value) {
		if(trim($value) != '') {
			$change_log .= "$name set to '$value'.\n";
		}
	}
}
$change_log = filter_var($change_log,FILTER_SANITIZE_STRING);
if(trim($change_log) != '') {
	$query = "INSERT INTO contacts_history (`updated_by`, `description`, `contactid`) VALUES ('$user', '$change_log', '$contactid')";
	mysqli_query($dbc, $query);
}