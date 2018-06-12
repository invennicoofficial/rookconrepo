<?php
include ('database_connection.php');
require('function.php');

$select_temp_data = mysqli_query($dbc, "SELECT contactid, name, first_name, last_name, password, office_phone, cell_phone, home_phone, email_address, business_street, business_city, business_state, business_country, business_zip, health_care_no FROM contacts where category in('Staff', 'Insurer')");

while($col = mysqli_fetch_array($select_temp_data)) {
	$contactid = $col['contactid'];
	$name = encryptIt($col['name']);
	$first_name = encryptIt($col['first_name']);
	$last_name = encryptIt($col['last_name']);
	$password = encryptIt($col['password']);
	$office_phone = encryptIt($col['office_phone']);
	$cell_phone = encryptIt($col['cell_phone']);
	$home_phone = encryptIt($col['home_phone']);
	$email_address = encryptIt($col['email_address']);
	$business_street = encryptIt($col['business_street']);
	$business_city = encryptIt($col['business_city']);
	$business_state = encryptIt($col['business_state']);
	$business_country = encryptIt($col['business_country']);
	$business_zip = encryptIt($col['business_zip']);
	$health_care_no = encryptIt($col['health_care_no']);

	$query_update_contacts = "UPDATE `contacts` SET name = '$name', first_name = '$first_name', last_name = '$last_name', password = '$password', office_phone = '$office_phone', cell_phone = '$cell_phone', home_phone = '$home_phone', email_address = '$email_address', business_street = '$business_street', business_city = '$business_city', business_state = '$business_state', business_country = '$business_country', business_zip = '$business_zip', health_care_no = '$health_care_no'  WHERE contactid = $contactid";

	$result_update_contacts = mysqli_query($dbc, $query_update_contacts);
}

$update_status = "update contacts set status = 0 where status = 'Inactive'";
$result_status = mysqli_query($dbc, $update_status);

$update_status = "update contacts set status = 1 where status = 'Active' or status = ''";
$result_status = mysqli_query($dbc, $update_status);

$update_status = "update contacts set status = 1 where role='super'";
$result_status = mysqli_query($dbc, $update_status);

echo "<br> Data Encryption is done <br>";
?>