<?php if(strpos($_SERVER['SCRIPT_FILENAME'], 'contacts_save.php') !== false) {
	exit('No Direct Access to this script.');
}

//echo "<!--"; // Just hide the missing field warnings that will show up for submitted values
if($_POST['contactid'] != '') {
	$contacts_prior = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `contactid` = '{$_POST['contactid']}'"));
	$contacts_cost_prior = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `contacts_cost` WHERE `contactid` = '{$_POST['contactid']}'"));
	$contacts_dates_prior = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `contacts_dates` WHERE `contactid` = '{$_POST['contactid']}'"));
	$contacts_description_prior = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `contacts_description` WHERE `contactid` = '{$_POST['contactid']}'"));
	$contacts_upload_prior = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `contacts_upload` WHERE `contactid` = '{$_POST['contactid']}'"));
}

$businessid = $_POST['businessid'];
/* Not all Contacts have a business attached to them.
 * In this case the INSERT & UPDATE queries fail.
 * So we manipulate the SQL query
 */
$businessid_field = ', ';
$businessid_value = ', ';
$businessid_upate_query = ', ';

if ( !empty ( $businessid ) ) {
	// For INSERT
	$businessid_field = ', `businessid`, ';
	$businessid_value = ", '$businessid', ";
	// For UPDATE
	$businessid_upate_query = ", `businessid`='$businessid', ";
}

// Main Table
include('contacts_save_main.php');
// Main Table

/* Update intake table ONLY if we don't need to create a Project.
 * If we're creating a Project, update intake table at that time.
 */
if ( !empty($intakeid ) && empty($project_type) ) {
	$assigned_date = date('Y-m-d');
	$intake_update = mysqli_query($dbc, "UPDATE `intake` SET `contactid`='$contactid', `assigned_date`='$assigned_date' WHERE `intakeid`='$intakeid'");
}

// Medical Table
include('contacts_save_medical.php');
// Medical Table

// Cost Table
include('contacts_save_cost.php');
// Cost Table

// Cost Table
include('contacts_save_dates.php');
// Cost Table

// Description Table
include('contacts_save_desc.php');
// Description Table

// Upload Table
include('contacts_save_upload.php');
// Upload Table

// Record the history of the change
include('contacts_save_history.php');
// Record the history of the change

// If there were contacts related to a submitted business
if(isset($_POST['related-contacts'])) {
    if($category == 'Sites') {
        $bcid = $businessid;
    }  else {
        $bcid = $contactid;
    }
    $related = json_decode($_POST['related-contacts']);
    foreach($related as $id) {
        if(is_numeric($id)) {
            $sql = "UPDATE CONTACTS set businessid='$bcid' where contactid='$id'";
            mysqli_query($dbc, $sql);
        }
    }
}
?>