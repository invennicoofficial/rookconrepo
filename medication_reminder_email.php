<?php
/**
** Send medication reminder follow up email
*/
//include   ('../database_connection.php');
include ('include.php');
ob_clean();
error_reporting(0);

$today_date = date('Y-m-d');
$query_check_credentials = "SELECT * FROM `medication` WHERE `reminder_date` = '$today_date' AND deleted = 0";
$result = mysqli_query($dbc, $query_check_credentials);

while($row = mysqli_fetch_array( $result )) {
    $medicationid = $row['medicationid'];
    $staffid = $row['contactid'];
    $clientid = $row['clientid'];

    $email_addresses = [];

    $query_contact = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `contactid` = $staffid"));
    if (!empty(get_email($dbc, $query_contact['contactid']))) {
        $email_addresses[get_contact($dbc, $staffid)] = get_email($dbc, $query_contact['contactid']);
    }

    $query_contact = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `contactid` = $clientid"));
    if (!empty(get_email($dbc, $query_contact['contactid']))) {
        $email_addresses[get_contact($dbc, $clientid)] = get_email($dbc, $query_contact['contactid']);
    }

    foreach ($email_addresses as $contactname => $email_address) {
        $subject = $contactname . ' - Medication Requires Attention';

        $message = $contactname . ",<br><br>";
        $message .= "This is a reminder email that a medication requires attention.";

        try {
            send_email('', $email_address, '', '', $subject, $message, '');
        } catch (exception $e) {
            echo $e->getMessage();
        }
    }
}
?>