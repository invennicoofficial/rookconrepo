<?php
include ('database_connection.php');
include ('function.php');

error_reporting(0);
echo "Postal Codes Before Checking Mailing Addresses<br><br>";
$total_contact = mysqli_query($dbc,"SELECT c.contactid, c.business_zip, c.ship_zip, c.zip_code, c.postal_code, b.type, group_concat(b.type), count(*) FROM contacts c INNER JOIN invoice i ON c.contactid = i.patientid INNER JOIN booking b ON i.bookingid = b.bookingid WHERE (DATE(service_date) >= '2016-01-01' AND DATE(service_date) <= '2016-12-31') AND c.deleted=0 AND c.status=1 AND (b.type = 'A' OR b.type = 'B' OR b.type = 'K' or b.type = 'U') group by c.contactid");
$zip_three = array();
while($row_report = mysqli_fetch_array($total_contact)) {
    if($row_report['business_zip'] != '') {
        $zip_three[] = strtolower(substr(decryptIt($row_report['business_zip']), 0, 3));
    } else if($row_report['postal_code'] != '') {
        $zip_three[] = strtolower(substr(decryptIt($row_report['postal_code']), 0, 3));
	} else if($row_report['zip_code'] != '') {
        $zip_three[] = strtolower(substr(decryptIt($row_report['zip_code']), 0, 3));
	} else if($row_report['ship_zip'] != '') {
        $zip_three[] = strtolower(substr(decryptIt($row_report['ship_zip']), 0, 3));
	}
}
asort($zip_three);
$occurences = array_count_values($zip_three);
foreach ($occurences as $key => $value) {
    // if (strtolower($key) == 't2e' || strtolower($key) == 't2m' || strtolower($key) == 't2n' || strtolower($key) == 't2l' || strtolower($key) == 't3a' || strtolower($key) == 't3g' || strtolower($key) == 't3n' || strtolower($key) == 't3j' || strtolower($key) == 't1y' || strtolower($key) == 't2a') {
        echo $key . " - " . $value . "<br />";
    // }
}

// echo "<br>Postal Codes After Checking Mailing Addresses<br><br>";
// $total_contact = mysqli_query($dbc,"SELECT b.type, c.business_zip, c.ship_zip, c.zip_code, c.postal_code FROM contacts c,invoice i,booking b WHERE c.contactid=i.patientid AND i.bookingid = b.bookingid AND (DATE(service_date) >= '2016-01-01' AND DATE(service_date) <= '2016-12-31') AND c.deleted=0 AND c.status=1 AND c.business_street != '' AND c.business_street IS NOT NULL AND (b.type = 'A' OR b.type = 'B' OR b.type = 'K' or b.type = 'U')");
// $zip_three = array();
// while($row_report = mysqli_fetch_array($total_contact)) {
//     if($row_report['business_zip'] != '') {
//         $zip_three[] = substr(decryptIt($row_report['business_zip']), 0, 3);
//     } else if($row_report['postal_code'] != '') {
//         $zip_three[] = substr(decryptIt($row_report['postal_code']), 0, 3);
//     } else if($row_report['zip_code'] != '') {
//         $zip_three[] = substr(decryptIt($row_report['zip_code']), 0, 3);
//     } else if($row_report['ship_zip'] != '') {
//         $zip_three[] = substr(decryptIt($row_report['ship_zip']), 0, 3);
//     }
// }
// asort($zip_three);
// $occurences = array_count_values($zip_three);
// foreach ($occurences as $key => $value) {
//     if (strtolower($key) == 't2k' || strtolower($key) == 't3k') {
//         echo $key . " - " . $value . "<br />";
//     }
// }

// echo "<br>Postal Codes After Checking Mailing Address (FULL WITH CITY, PROVINCE, ETC.)<br><br>";
// $total_contact = mysqli_query($dbc,"SELECT b.type, c.business_zip, c.ship_zip, c.zip_code, c.postal_code FROM contacts c,invoice i,booking b WHERE c.contactid=i.patientid AND i.bookingid = b.bookingid AND (DATE(service_date) >= '2016-01-01' AND DATE(service_date) <= '2016-12-31') AND c.deleted=0 AND c.status=1 AND c.business_street != '' AND c.business_street IS NOT NULL AND c.business_city != '' AND c.business_city IS NOT NULL AND c.business_state != '' AND c.business_city IS NOT NULL AND c.business_country != '' AND c.business_country IS NOT NULL AND (b.type = 'A' OR b.type = 'B' OR b.type = 'K' or b.type = 'U')");
// $zip_three = array();
// while($row_report = mysqli_fetch_array($total_contact)) {
//     if($row_report['business_zip'] != '') {
//         $zip_three[] = substr(decryptIt($row_report['business_zip']), 0, 3);
//     } else if($row_report['postal_code'] != '') {
//         $zip_three[] = substr(decryptIt($row_report['postal_code']), 0, 3);
//     } else if($row_report['zip_code'] != '') {
//         $zip_three[] = substr(decryptIt($row_report['zip_code']), 0, 3);
//     } else if($row_report['ship_zip'] != '') {
//         $zip_three[] = substr(decryptIt($row_report['ship_zip']), 0, 3);
//     }
// }
// asort($zip_three);
// $occurences = array_count_values($zip_three);
// foreach ($occurences as $key => $value) {
//     if (strtolower($key) == 't2k' || strtolower($key) == 't3k') {
//         echo $key . " - " . $value . "<br />";
//     }
// }

// echo "<br>Contacts missing full mailing addresses<br><br>";
// $total_contact = mysqli_query($dbc,"SELECT c.contactid, b.type, c.business_zip, c.ship_zip, c.zip_code, c.postal_code FROM contacts c,invoice i,booking b WHERE c.contactid=i.patientid AND i.bookingid = b.bookingid AND (DATE(service_date) >= '2016-01-01' AND DATE(service_date) <= '2016-12-31') AND c.deleted=0 AND c.status=1 AND (c.business_street = '' OR c.business_street IS NULL OR c.business_city = '' OR c.business_city IS NULL OR c.business_state = '' OR c.business_city IS NULL OR c.business_country = '' OR c.business_country IS NULL) AND (b.type = 'A' OR b.type = 'B' OR b.type = 'K' or b.type = 'U')");

// $contacts = [];
// while($row_report = mysqli_fetch_array($total_contact)) {
//     $zip_code = '';
//     if($row_report['business_zip'] != '') {
//         $zip_code = substr(decryptIt($row_report['business_zip']), 0, 3);
//     } else if($row_report['postal_code'] != '') {
//         $zip_code = substr(decryptIt($row_report['postal_code']), 0, 3);
//     } else if($row_report['zip_code'] != '') {
//         $zip_code = substr(decryptIt($row_report['zip_code']), 0, 3);
//     } else if($row_report['ship_zip'] != '') {
//         $zip_code = substr(decryptIt($row_report['ship_zip']), 0, 3);
//     }

//     if(strtolower($zip_code) == 't2k' && !in_array($row_report['contactid'], $contacts)) {
//         echo 'T2K - ' . $row_report['contactid'] . " - " . get_contact($dbc, $row_report['contactid']) . "<br />";
//         array_push($contacts, $row_report['contactid']);
//     }
//     if(strtolower($zip_code) == 't3k' && !in_array($row_report['contactid'], $contacts)) {
//         echo 'T3K - ' . $row_report['contactid'] . " - " . get_contact($dbc, $row_report['contactid']) . "<br />";
//         array_push($contacts, $row_report['contactid']);
//     }
// }