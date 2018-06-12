<?php
include ('database_connection.php');
include ('function.php');

error_reporting(0);
echo "Postal Codes Before Checking Mailing Addresses<br><br>";
$total_contact = mysqli_query($dbc,"SELECT distinct c.contactid, c.first_name, c.last_name, c.business_street, c.business_city, c.business_state, c.business_country, c.business_zip, c.ship_zip, c.zip_code, c.postal_code FROM contacts c,invoice i,booking b WHERE c.contactid=i.patientid AND i.bookingid = b.bookingid AND (DATE(service_date) >= '2016-01-01' AND DATE(service_date) <= '2016-12-31') AND c.deleted=0 AND c.status=1 AND (b.type = 'A' OR b.type = 'B' OR b.type = 'K' or b.type = 'U')");
$zip_three = array();
$contacts_arr = [];
$contacts_arr[] = ['Contact ID','First Name','Last Name','Street Address','City','Province','Country','Postal Code'];
while($row_report = mysqli_fetch_array($total_contact)) {
    $this_zip = '';
    $full_zip = '';
    if($row_report['business_zip'] != '') {
        $zip_three[] = substr(decryptIt($row_report['business_zip']), 0, 3);
        $this_zip = substr(decryptIt($row_report['business_zip']), 0, 3);
        $full_zip = decryptIt($row_report['business_zip']);
    } else if($row_report['postal_code'] != '') {
        $zip_three[] = substr(decryptIt($row_report['postal_code']), 0, 3);
        $this_zip = substr(decryptIt($row_report['postal_code']), 0, 3);
        $full_zip = decryptIt($row_report['postal_code']);
	} else if($row_report['zip_code'] != '') {
        $zip_three[] = substr(decryptIt($row_report['zip_code']), 0, 3);
        $this_zip = substr(decryptIt($row_report['zip_code']), 0, 3);
        $full_zip = decryptIt($row_report['zip_code']);
	} else if($row_report['ship_zip'] != '') {
        $zip_three[] = substr(decryptIt($row_report['ship_zip']), 0, 3);
        $this_zip = substr(decryptIt($row_report['ship_zip']), 0, 3);
        $full_zip = decryptIt($row_report['ship_zip']);
	}
    if (strtolower($this_zip) == 't2k')
    {
        $contacts_arr[] = [$row_report['contactid'],decryptIt($row_report['first_name']),decryptIt($row_report['last_name']),decryptIt($row_report['business_street']),decryptIt($row_report['business_city']),decryptIt($row_report['business_state']),decryptIt($row_report['business_country']),$full_zip];
    }
}

$fp = fopen('contacts_t2k.csv', 'w');

foreach ($contacts_arr as $row) {
    fputcsv($fp, $row);
}
fclose($fp);
