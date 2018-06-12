<?php

$siteid = $_POST['siteid'];

$name = encryptIt(filter_var($_POST['name'],FILTER_SANITIZE_STRING));
$first_name = encryptIt(filter_var($_POST['first_name'],FILTER_SANITIZE_STRING));
$last_name = encryptIt(filter_var($_POST['last_name'],FILTER_SANITIZE_STRING));
$prefer_name = encryptIt(filter_var($_POST['prefer_name'],FILTER_SANITIZE_STRING));
$assign_staff = filter_var($_POST['assign_staff'],FILTER_SANITIZE_STRING);
$role = filter_var(','.trim(implode(',',$_POST['role']),',').',',FILTER_SANITIZE_STRING);
$region = filter_var($_POST['region'],FILTER_SANITIZE_STRING);
$classification = filter_var($_POST['classification'],FILTER_SANITIZE_STRING);
$name_on_account = filter_var($_POST['name_on_account'],FILTER_SANITIZE_STRING);
$operating_as = filter_var($_POST['operating_as'],FILTER_SANITIZE_STRING);
$rating = filter_var($_POST['rating'],FILTER_SANITIZE_STRING);
$emergency_contact = filter_var($_POST['emergency_contact'],FILTER_SANITIZE_STRING);
$occupation = filter_var($_POST['occupation'],FILTER_SANITIZE_STRING);
$office_phone = encryptIt(filter_var($_POST['office_phone'],FILTER_SANITIZE_STRING));
$cell_phone = encryptIt(filter_var($_POST['cell_phone'],FILTER_SANITIZE_STRING));
$primary_contact = implode(',',$_POST['primary_contact']);
$home_phone = encryptIt(filter_var($_POST['home_phone'],FILTER_SANITIZE_STRING));
$fax = filter_var($_POST['fax'],FILTER_SANITIZE_STRING);
$email_address = encryptIt(filter_var($_POST['email_address'],FILTER_SANITIZE_STRING));
$office_email = encryptIt(filter_var($_POST['office_email'],FILTER_SANITIZE_STRING));
$website = filter_var($_POST['website'],FILTER_SANITIZE_STRING);
$customer_address = filter_var($_POST['customer_address'],FILTER_SANITIZE_STRING);

$referred_by = filter_var($_POST['referred_by'],FILTER_SANITIZE_STRING);
$referred_by_name = filter_var($_POST['referred_by_name'],FILTER_SANITIZE_STRING);
$company = filter_var($_POST['company'],FILTER_SANITIZE_STRING);
$position = filter_var($_POST['position'],FILTER_SANITIZE_STRING);
$title = filter_var($_POST['title'],FILTER_SANITIZE_STRING);

$linkedin = filter_var($_POST['linkedin'],FILTER_SANITIZE_STRING);
$facebook = filter_var($_POST['facebook'],FILTER_SANITIZE_STRING);
$twitter = filter_var($_POST['twitter'],FILTER_SANITIZE_STRING);
$duns = filter_var($_POST['duns'],FILTER_SANITIZE_STRING);
$cage = filter_var($_POST['cage'],FILTER_SANITIZE_STRING);
$sin = filter_var($_POST['sin'],FILTER_SANITIZE_STRING);
$employee_num = filter_var($_POST['employee_num'],FILTER_SANITIZE_STRING);
$show_hide_user =  filter_var($_POST['show_hide_user'],FILTER_SANITIZE_STRING);
$profile_link = filter_var($_POST['profile_link'],FILTER_SANITIZE_STRING);

$initials = filter_var($_POST['initials'],FILTER_SANITIZE_STRING);
$calendar_color = filter_var($_POST['calendar_color'],FILTER_SANITIZE_STRING);

if($_POST['new_self_identification'] != '') {
	$self_identification = $_POST['new_self_identification'];
} else {
	$self_identification = $_POST['self_identification'];
}

if($_POST['new_category'] != '') {
    $category_contact = filter_var($_POST['new_category'],FILTER_SANITIZE_STRING);
} else {
    $category_contact = $_POST['category_contact'];
}
$gender = $_POST['gender'];
$license = filter_var($_POST['license'],FILTER_SANITIZE_STRING);
$insurerid = implode(',',$_POST['insurerid']);
$plan_acctno = filter_var(implode(',',$_POST['plan_acctno']),FILTER_SANITIZE_STRING);

$credential = filter_var($_POST['credential'],FILTER_SANITIZE_STRING);
$schedule_days = implode(',',$_POST['schedule_days']);
$scheduled_hours = implode('*',$_POST['scheduled_hours']);
$hd = '';
for($i=0;$i<=6;$i++) {
    $occur = in_array($i, explode(',', $schedule_days));
    if ($occur == 1) {
    } else {
        $hd .= $i.',';
    }
}
$health_care_no = encryptIt(filter_var($_POST['health_care_no'],FILTER_SANITIZE_STRING));
$correspondence_language = filter_var($_POST['correspondence_language'],FILTER_SANITIZE_STRING);
$accepts_receive_emails = filter_var($_POST['accepts_receive_emails'],FILTER_SANITIZE_STRING);
$amount_to_bill = filter_var($_POST['amount_to_bill'],FILTER_SANITIZE_STRING);
$amount_owing = filter_var($_POST['amount_owing'],FILTER_SANITIZE_STRING);
$amount_credit = filter_var($_POST['amount_credit'],FILTER_SANITIZE_STRING);
$business_street = encryptIt(filter_var($_POST['business_street'],FILTER_SANITIZE_STRING));
$business_country = encryptIt(filter_var($_POST['business_country'],FILTER_SANITIZE_STRING));
$business_city = encryptIt(filter_var($_POST['business_city'],FILTER_SANITIZE_STRING));
$business_state = encryptIt(filter_var($_POST['business_state'],FILTER_SANITIZE_STRING));
$business_zip = encryptIt(filter_var($_POST['business_zip'],FILTER_SANITIZE_STRING));

$client_tax_exemption = filter_var($_POST['client_tax_exemption'],FILTER_SANITIZE_STRING);
$tax_exemption_number = filter_var($_POST['tax_exemption_number'],FILTER_SANITIZE_STRING);
$aish_card_no = filter_var($_POST['aish_card_no'],FILTER_SANITIZE_STRING);
$license_plate_no = filter_var($_POST['license_plate_no'],FILTER_SANITIZE_STRING);
$carfax = filter_var($_POST['carfax'],FILTER_SANITIZE_STRING);
$address = filter_var($_POST['address'],FILTER_SANITIZE_STRING);
$mailing_address = filter_var($_POST['mailing_address'],FILTER_SANITIZE_STRING);
$business_address = filter_var($_POST['business_address'],FILTER_SANITIZE_STRING);
$ship_to_address = filter_var($_POST['ship_to_address'],FILTER_SANITIZE_STRING);
$postal_code = filter_var($_POST['postal_code'],FILTER_SANITIZE_STRING);
$zip_code = filter_var($_POST['zip_code'],FILTER_SANITIZE_STRING);
$city = filter_var($_POST['city'],FILTER_SANITIZE_STRING);
$province = filter_var($_POST['province'],FILTER_SANITIZE_STRING);
$state = filter_var($_POST['state'],FILTER_SANITIZE_STRING);
$country = filter_var($_POST['country'],FILTER_SANITIZE_STRING);

$ship_zip = filter_var($_POST['ship_zip'],FILTER_SANITIZE_STRING);
$ship_state = filter_var($_POST['ship_state'],FILTER_SANITIZE_STRING);
$ship_city = filter_var($_POST['ship_city'],FILTER_SANITIZE_STRING);
$ship_country = filter_var($_POST['ship_country'],FILTER_SANITIZE_STRING);

$google_maps_address = filter_var($_POST['google_maps_address'],FILTER_SANITIZE_STRING);
$city_part = filter_var($_POST['city_part'],FILTER_SANITIZE_STRING);
$account_number = filter_var($_POST['account_number'],FILTER_SANITIZE_STRING);
$payment_type = filter_var($_POST['payment_type'],FILTER_SANITIZE_STRING);
$payment_name = filter_var($_POST['payment_name'],FILTER_SANITIZE_STRING);
$payment_address = filter_var($_POST['payment_address'],FILTER_SANITIZE_STRING);
$payment_city = filter_var($_POST['payment_city'],FILTER_SANITIZE_STRING);
$payment_state = filter_var($_POST['payment_state'],FILTER_SANITIZE_STRING);
$payment_postal_code = filter_var($_POST['payment_postal_code'],FILTER_SANITIZE_STRING);
$payment_zip_code = filter_var($_POST['payment_zip_code'],FILTER_SANITIZE_STRING);
$gst_no = filter_var($_POST['gst_no'],FILTER_SANITIZE_STRING);
$pst_no = filter_var($_POST['pst_no'],FILTER_SANITIZE_STRING);
$vendor_gst_no = filter_var($_POST['vendor_gst_no'],FILTER_SANITIZE_STRING);
$payment_information = filter_var($_POST['payment_information'],FILTER_SANITIZE_STRING);

$pricing_level = filter_var($_POST['pricing_level'],FILTER_SANITIZE_STRING);
$unit_no = filter_var($_POST['unit_no'],FILTER_SANITIZE_STRING);

$bay_no = filter_var($_POST['bay_no'],FILTER_SANITIZE_STRING);
$option_to_renew = filter_var($_POST['option_to_renew'],FILTER_SANITIZE_STRING);
$lease_term_no_of_years = filter_var($_POST['lease_term_no_of_years'],FILTER_SANITIZE_STRING);

$commercial_insurer = filter_var($_POST['commercial_insurer'],FILTER_SANITIZE_STRING);
$residential_insurer = filter_var($_POST['residential_insurer'],FILTER_SANITIZE_STRING);
$wcb_no = filter_var($_POST['wcb_no'],FILTER_SANITIZE_STRING);
$cc_on_file = ( filter_var($_POST['cc_on_file'],FILTER_SANITIZE_STRING) == 'Yes' ) ? 1 : 0;

$user_name = $_POST['user_name'];
$password = encryptIt($_POST['password']);

$status=$_POST['status'];

$hire_date = filter_var($_POST['hire_date'],FILTER_SANITIZE_STRING);
$birth_date = filter_var($_POST['birth_date'],FILTER_SANITIZE_STRING);
$description = filter_var(htmlentities($_POST['description']),FILTER_SANITIZE_STRING);

$siteclientid = $_POST['siteclientid'];
$site_name = filter_var($_POST['site_name'],FILTER_SANITIZE_STRING);
$lsd = filter_var($_POST['lsd'],FILTER_SANITIZE_STRING);
$display_name = filter_var($_POST['display_name'],FILTER_SANITIZE_STRING);

if(empty($_POST['contactid'])) {
	$query_insert_inventory = "INSERT INTO `contacts` (`category`, `businessid`, `name`, `first_name`, `last_name`, `prefer_name`, `assign_staff`, `classification`, `region`, `name_on_account`, `operating_as`, `emergency_contact`, `occupation`, `office_phone`, `cell_phone`, `home_phone`, `primary_contact`, `fax`, `email_address`, `office_email`, `website`, `customer_address`, `referred_by`, `referred_by_name`, `company`, `position`, `title`, `linkedin`, `facebook`, `twitter`, `employee_num`, `sin`, `client_tax_exemption`, `tax_exemption_number`, `duns`, `cage`, `self_identification`, `aish_card_no`, `license_plate_no`, `carfax`, `address`, `mailing_address`, `business_address`, `ship_to_address`, `postal_code`, `zip_code`, `city`, `province`, `state`, `country`, `ship_country`, `ship_city`, `ship_state`, `ship_zip`, `google_maps_address`, `city_part`, `rating`, `account_number`, `payment_type`, `payment_name`, `payment_address`, `payment_city`, `payment_state`, `payment_postal_code`, `payment_zip_code`, `gst_no`, `pst_no`, `vendor_gst_no`, `payment_information`, `pricing_level`, `unit_no`, `bay_no`, `option_to_renew`, `lease_term_no_of_years`, `commercial_insurer`, `residential_insurer`, `wcb_no`, `deleted`, `cc_on_file`, `category_contact`, `gender`, `license`, `insurerid`, `plan_acctno`, `credential`, `schedule_days`, `scheduled_hours`, `health_care_no`, `correspondence_language`, `accepts_receive_emails`, `amount_to_bill`, `amount_owing`, `amount_credit`, `business_street`, `business_country`, `business_city`, `business_state`, `business_zip`, `hire_date`, `birth_date`, `description`, `siteclientid`, `site_name`, `lsd`, `display_name`, `siteid`, `tile_name`, `profile_link`, `initials`, `calendar_color`)
		VALUES		('$category', '$businessid', '$name', '$first_name', '$last_name', '$prefer_name', '$assign_staff', '$classification', ,'$region', '$name_on_account', '$operating_as', '$emergency_contact', '$occupation', '$office_phone', '$cell_phone', '$home_phone', '$primary_contact', '$fax', '$email_address', '$office_email', '$website', '$customer_address', '$referred_by', '$referred_by_name', '$company', '$position', '$title', '$linkedin', '$facebook', '$twitter', '$employee_num', '$sin', '$client_tax_exemption', '$tax_exemption_number', '$duns', '$cage', '$self_identification', '$aish_card_no', '$license_plate_no', '$carfax', '$address', '$mailing_address', '$business_address', '$ship_to_address', '$postal_code', '$zip_code', '$city', '$province', '$state', '$country', '$ship_country', '$ship_city', '$ship_state', '$ship_zip', '$google_maps_address', '$city_part', '$rating', '$account_number', '$payment_type', '$payment_name', '$payment_address', '$payment_city', '$payment_state', '$payment_postal_code', '$payment_zip_code', '$gst_no', '$pst_no', '$vendor_gst_no', '$payment_information', '$pricing_level', '$unit_no', '$bay_no', '$option_to_renew', '$lease_term_no_of_years', '$commercial_insurer', '$residential_insurer', '$wcb_no', '$deleted', '$cc_on_file', '$category_contact', '$gender', '$license', '$insurerid', '$plan_acctno', '$credential', '$schedule_days', '$scheduled_hours', '$health_care_no', '$correspondence_language', '$accepts_receive_emails', '$amount_to_bill', '$amount_owing', '$amount_credit', '$business_street', '$business_country', '$business_city', '$business_state', '$business_zip', '$hire_date', '$birth_date', '$description', '$siteclientid', '$site_name', '$lsd', '$display_name', '$siteid', '".FOLDER_NAME."', '$profile_link', '$initials', '$calendar_color')";

	$result_insert_inventory = mysqli_query($dbc, $query_insert_inventory);
	$contactid = mysqli_insert_id($dbc);
	$_GET['contactid'] = $contactid;
	$url = 'Added';

} else {
	$contactid = $_POST['contactid'];
	$query_update_inventory = "UPDATE `contacts` SET `businessid` = '$businessid', `name` = '$name', `first_name` = '$first_name', `last_name` = '$last_name', `prefer_name` = '$prefer_name', `assign_staff`='$assign_staff', `classification` = '$classification', `region` = '$region', `name_on_account` = '$name_on_account', `operating_as` = '$operating_as', `emergency_contact` = '$emergency_contact', `occupation` = '$occupation', `office_phone` = '$office_phone', `cell_phone` = '$cell_phone', `home_phone` = '$home_phone', `primary_contact` = '$primary_contact', `fax` = '$fax', `email_address` = '$email_address', `office_email` = '$office_email', `website` = '$website', `customer_address` = '$customer_address', `referred_by` = '$referred_by', `referred_by_name` = '$referred_by_name', `company` = '$company', `position` = '$position', `title` = '$title', `linkedin` = '$linkedin', `facebook` = '$facebook', `twitter` = '$twitter', `employee_num` = '$employee_num', `sin` = '$sin', `client_tax_exemption` = '$client_tax_exemption', `tax_exemption_number` = '$tax_exemption_number', `duns` = '$duns', `cage` = '$cage', `self_identification` = '$self_identification', `aish_card_no` = '$aish_card_no', `license_plate_no` = '$license_plate_no', `carfax` = '$carfax', `address` = '$address', `mailing_address` = '$mailing_address', `business_address` = '$business_address', `ship_to_address` = '$ship_to_address', `postal_code` = '$postal_code', `zip_code` = '$zip_code', `city` = '$city', `province` = '$province', `state` = '$state', `country` = '$country', `ship_country` = '$ship_country', `ship_city` = '$ship_city', `ship_state` = '$ship_state', `ship_zip` = '$ship_zip', `google_maps_address` = '$google_maps_address', `city_part` = '$city_part', `rating` = '$rating', `account_number` = '$account_number', `payment_type` = '$payment_type', `payment_name` = '$payment_name', `payment_address` = '$payment_address', `payment_city` = '$payment_city', `payment_state` = '$payment_state', `payment_postal_code` = '$payment_postal_code', `payment_zip_code` = '$payment_zip_code', `gst_no` = '$gst_no', `pst_no` = '$pst_no', `vendor_gst_no` = '$vendor_gst_no', `payment_information` = '$payment_information', `pricing_level` = '$pricing_level', `unit_no` = '$unit_no', `bay_no` = '$bay_no', `option_to_renew` = '$option_to_renew', `lease_term_no_of_years` = '$lease_term_no_of_years', `commercial_insurer` = '$commercial_insurer', `residential_insurer` = '$residential_insurer', `wcb_no` = '$wcb_no', `deleted` = '$deleted', `cc_on_file` = '$cc_on_file', `category_contact` = '$category_contact', `gender` = '$gender', `license` = '$license', `insurerid` = '$insurerid', `plan_acctno` = '$plan_acctno', `credential` = '$credential', `schedule_days` = '$schedule_days', `scheduled_hours` = '$scheduled_hours', `health_care_no` = '$health_care_no', `correspondence_language` = '$correspondence_language', `accepts_receive_emails` = '$accepts_receive_emails', `amount_to_bill` = '$amount_to_bill', `amount_owing` = '$amount_owing', `amount_credit` = '$amount_credit', `business_street` = '$business_street', `business_country` = '$business_country', `business_city` = '$business_city', `business_state` = '$business_state', `business_zip` = '$business_zip', `hire_date` = '$hire_date', `birth_date` = '$birth_date', `description` = '$description', `siteclientid` = '$siteclientid', `site_name` = '$site_name', `lsd`='$lsd', `display_name` = '$display_name', `siteid` = '$siteid', `profile_link` = '$profile_link', `initials`='$initials', `calendar_color`='$calendar_color'  WHERE `contactid` = '$contactid'";
	$result_update_inventory	= mysqli_query($dbc, $query_update_inventory);
	$_GET['contactid'] = $contactid;
	$url = 'Updated';
}

// Update Special Fields
if(!empty($_POST['role'])) {
	$query_update = "UPDATE `contacts` SET `role`='$role' WHERE `contactid` = '$contactid' AND `category` != 'Staff'";
	// if(strpos($_SERVER['HTTP_HOST'],'highland') !== FALSE) {
		// send_email('', 'jonathanhurdman@freshfocusmedia.com', '', '', 'Security Level Change: Contacts', get_contact($dbc, $_SESSION['contactid'])." changed the security level for $contactid to $role.<br />".WEBSITE_URL."<br />UPDATE `contacts` SET `role`='$role' WHERE `contactid` = '$contactid' AND `category` != 'Staff'", '');
	// }
}
if(!empty($_POST['user_name'])) {
	$query_update = "UPDATE `contacts` SET `user_name`='$user_name' WHERE `contactid` = '$contactid' AND `category` != 'Staff'";
}
if(!empty($_POST['password'])) {
	$query_update = "UPDATE `contacts` SET `password`='$password' WHERE `contactid` = '$contactid' AND `category` != 'Staff'";
}
if(!empty($_POST['status'])) {
	$query_update = "UPDATE `contacts` SET `status`='$status' WHERE `contactid` = '$contactid'";
}
if(!empty($_POST['show_hide_user'])) {
	$query_update = "UPDATE `contacts` SET `show_hide_user`='$show_hide_user' WHERE `contactid` = '$contactid'";
}

if(($category == 'Employee' || $category == 'Staff' || $category == 'employee' || $category == 'staff') && ($category_contact == 'Physical Therapist' || $category_contact == 'Massage Therapist' || $category_contact == 'Osteopathic Therapist')) {
    $phy_name = filter_var($_POST['first_name'],FILTER_SANITIZE_STRING).' '.filter_var($_POST['last_name'],FILTER_SANITIZE_STRING);

    $get_id_count = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(id) AS total_staff FROM mrbs_room WHERE room_name='$phy_name'"));

    if($get_id_count['total_staff'] > 0) {
        $query_update_staff = "UPDATE `mrbs_room` SET `room_name` = '$phy_name', `sort_key` = '$phy_name', `hidden_days` = '$hidden_days', `description` = '$category_contact' WHERE `room_name` = '$phy_name'";
        $result_update_staff = mysqli_query($dbc, $query_update_staff);
    } else {
        $query_insert_privileges = "INSERT INTO `mrbs_room` (`area_id`, `room_name`, `sort_key`, `hidden_days`, `description`) VALUES (1, '$phy_name', '$phy_name', '$hidden_days', '$category_contact')";
        $result_insert_privileges = mysqli_query($dbc, $query_insert_privileges);
    }
}