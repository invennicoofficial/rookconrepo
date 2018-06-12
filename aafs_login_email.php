<?php
/* Update Databases */
include ('database_connection.php');
include ('function.php');
error_reporting(0);

$query_staff = "SELECT * FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND `deleted` = 0";
$result_staff = mysqli_query($dbc, $query_staff);

echo 'AAFS - Update username to aafscalgary.com emails<br /><br />';

while ($row = mysqli_fetch_array($result_staff)) {
    $contactid = $row['contactid'];
    echo $contactid . ' - ' . get_contact($dbc, $contactid) . ' - ';
    $email_address = decryptIt($row['email_address']);
    $office_email = decryptIt($row['office_email']);
    if ($email_address.$office_email == '') {
        echo 'No email attached to contact';
    } else if (strpos(strtolower($email_address), 'aafscalgary.com') !== FALSE) {
        $query_update = "UPDATE `contacts` SET `user_name` = '" . strtolower($email_address) . "' WHERE `contactid` = '$contactid'";
        $result_update = mysqli_query($dbc, $query_update);
        echo strtolower($email_address);
    } else if (strpos(strtolower($office_email), 'aafscalgary.com') !== FALSE) {
        $query_update = "UPDATE `contacts` SET `user_name` = '" . strtolower($office_email) . "' WHERE `contactid` = '$contactid'";
        $result_update = mysqli_query($dbc, $query_update);
        echo strtolower($office_email);
    } else {
        echo 'Email is not aafscalgary.com';
    }
    echo '<br />';
}

echo '<br />AAFS - End Update';