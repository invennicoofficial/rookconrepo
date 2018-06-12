<?php
/**
** Send appointment confirmation email before 2 days.
*/
//include	('../database_connection.php');
include	('include.php');
error_reporting(0);

$today_date = date('Y-m-d');
$query_check_credentials = "SELECT * FROM `match_contact` WHERE `follow_up_date` = '$today_date'";
$result = mysqli_query($dbc, $query_check_credentials);

while($row = mysqli_fetch_array( $result )) {
    $matchid = $row['matchid'];

    $staff_contacts_arr = explode(',', $row['staff_contact']);
    $staff_contacts = [];
    foreach($staff_contacts_arr as $value){
        array_push($staff_contacts, get_staff($dbc, $value).''.get_client($dbc, $value));
    }

    $support_contacts_arr = explode(',', $row['support_contact']);
    $support_contacts = [];
    foreach($support_contacts_arr as $value){
        array_push($support_contacts, get_staff($dbc, $value).''.get_client($dbc, $value));
    }

    $subject = 'Break the Barrier Innovation - Match Follow Up';

    $message = "This is a follow up email for Match #" . $matchid . ".<br><br>";
    $message .= "Staff: " . implode(',', $staff_contacts) . "<br>";
    $message .= "Contacts: " . implode(',', $support_contacts) . "<br>";

    send_email('', 'baldwinyu@freshfocusmedia.com', '', '', $subject, $message, '');
}
?>