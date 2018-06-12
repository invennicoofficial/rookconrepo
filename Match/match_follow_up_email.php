<?php
/**
** Send match follow up email
*/
//include   ('../database_connection.php');
include ('/home/ffm_software_ftp/demo.rookconnect.com/include.php');
ob_clean();
error_reporting(0);

$today_date = date('Y-m-d');
$query_check_credentials = "SELECT * FROM `match_contact` WHERE `follow_up_date` = '$today_date' AND deleted = 0 AND status != 'Suspend'";
$result = mysqli_query($dbc, $query_check_credentials);


while($row = mysqli_fetch_array( $result )) {
    $matchid = $row['matchid'];
    $email_addresses = [];

    $staff_contacts_arr = explode(',', $row['staff_contact']);
    $staff_contacts = [];
    foreach($staff_contacts_arr as $value){
        array_push($staff_contacts, get_staff($dbc, $value).''.get_client($dbc, $value));
        
        $query_contact = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `contactid` = $value"));
        if (!empty($query_contact['email_address'])) {
            array_push($email_addresses, decryptIt($query_contact['email_address']));
        }
    }

    $support_contacts_arr = explode(',', $row['support_contact']);
    $support_contacts = [];
    foreach($support_contacts_arr as $value){
        array_push($support_contacts, get_staff($dbc, $value).''.get_client($dbc, $value));

        $query_contact = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `contactid` = $value"));
        if (!empty($query_contact['email_address'])) {
            array_push($email_addresses, decryptIt($query_contact['email_address']));
        }
    }

    $subject = 'Break the Barrier Innovation - Match Follow Up';

    $message = "This is a follow up email for Match #" . $matchid . ".<br><br>";
    $message .= "Staff: " . implode(', ', $staff_contacts) . "<br>";
    $message .= "Contacts: " . implode(', ', $support_contacts) . "<br>";

    foreach ($email_addresses as $email_address) {
        try {
            send_email('', 'baldwinyu@freshfocusmedia.com', '', '', $subject, $message, '');
        } catch (exception $e) {
            echo $e->getMessage();
        }
    }
}
?>