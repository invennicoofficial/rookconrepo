<?php
$total_monthly_rate = filter_var($_POST['total_monthly_rate'],FILTER_SANITIZE_STRING);
$total_annual_rate = filter_var($_POST['total_annual_rate'],FILTER_SANITIZE_STRING);
$condo_fees = filter_var($_POST['condo_fees'],FILTER_SANITIZE_STRING);
$deposit = filter_var($_POST['deposit'],FILTER_SANITIZE_STRING);
$damage_deposit = filter_var($_POST['damage_deposit'],FILTER_SANITIZE_STRING);
$cost = filter_var($_POST['cost'],FILTER_SANITIZE_STRING);
$final_retail_price = filter_var($_POST['final_retail_price'],FILTER_SANITIZE_STRING);
$admin_price = filter_var($_POST['admin_price'],FILTER_SANITIZE_STRING);
$wholesale_price = filter_var($_POST['wholesale_price'],FILTER_SANITIZE_STRING);
$commercial_price = filter_var($_POST['commercial_price'],FILTER_SANITIZE_STRING);
$client_price = filter_var($_POST['client_price'],FILTER_SANITIZE_STRING);
$minimum_billable = filter_var($_POST['minimum_billable'],FILTER_SANITIZE_STRING);
$estimated_hours = filter_var($_POST['estimated_hours'],FILTER_SANITIZE_STRING);
$actual_hours = filter_var($_POST['actual_hours'],FILTER_SANITIZE_STRING);
$msrp = filter_var($_POST['msrp'],FILTER_SANITIZE_STRING);
$hourly_rate = filter_var($_POST['hourly_rate'],FILTER_SANITIZE_STRING);
$monthly_rate = filter_var($_POST['monthly_rate'],FILTER_SANITIZE_STRING);
$semi_monthly_rate = filter_var($_POST['semi_monthly_rate'],FILTER_SANITIZE_STRING);
$daily_rate = filter_var($_POST['daily_rate'],FILTER_SANITIZE_STRING);
$hr_rate_work = filter_var($_POST['hr_rate_work'],FILTER_SANITIZE_STRING);
$hr_rate_travel = filter_var($_POST['hr_rate_travel'],FILTER_SANITIZE_STRING);
$field_day_cost = filter_var($_POST['field_day_cost'],FILTER_SANITIZE_STRING);
$field_day_billable = filter_var($_POST['field_day_billable'],FILTER_SANITIZE_STRING);
$probation_pay_rate = filter_var($_POST['probation_pay_rate'],FILTER_SANITIZE_STRING);
$base_pay = filter_var($_POST['base_pay'],FILTER_SANITIZE_STRING);
$performance_pay = filter_var($_POST['performance_pay'],FILTER_SANITIZE_STRING);
$unit_no = filter_var($_POST['unit_no'],FILTER_SANITIZE_STRING);
$base_rent = filter_var($_POST['base_rent'],FILTER_SANITIZE_STRING);
$base_rent_sq_ft = filter_var($_POST['base_rent_sq_ft'],FILTER_SANITIZE_STRING);
$cac = filter_var($_POST['cac'],FILTER_SANITIZE_STRING);
$cac_sq_ft = filter_var($_POST['cac_sq_ft'],FILTER_SANITIZE_STRING);
$property_tax = filter_var($_POST['property_tax'],FILTER_SANITIZE_STRING);
$property_tax_sq_ft = filter_var($_POST['property_tax_sq_ft'],FILTER_SANITIZE_STRING);

$get_cost = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(contactcostid) AS contactcostid FROM contacts_cost WHERE contactid='$contactid'"));
if($get_cost['contactcostid'] > 0) {
	$query_update_cost = "UPDATE `contacts_cost` SET `total_monthly_rate` = '$total_monthly_rate', `total_annual_rate` = '$total_annual_rate', `condo_fees` = '$condo_fees', `deposit` = '$deposit', `damage_deposit` = '$damage_deposit', `cost` = '$cost', `final_retail_price` = '$final_retail_price', `admin_price` = '$admin_price', `wholesale_price` = '$wholesale_price', `commercial_price` = '$commercial_price', `client_price` = '$client_price', `minimum_billable` = '$minimum_billable', `estimated_hours` = '$estimated_hours', `actual_hours` = '$actual_hours', `msrp` = '$msrp', `hourly_rate` = '$hourly_rate', `monthly_rate` = '$monthly_rate', `semi_monthly_rate` = '$semi_monthly_rate', `daily_rate` = '$daily_rate', `hr_rate_work` = '$hr_rate_work', `hr_rate_travel` = '$hr_rate_travel', `field_day_cost` = '$field_day_cost', `field_day_billable` = '$field_day_billable', `probation_pay_rate` = '$probation_pay_rate', `base_pay` = '$base_pay', `performance_pay` = '$performance_pay', `base_rent` = '$base_rent', `base_rent_sq_ft` = '$base_rent_sq_ft', `cac` = '$cac', `cac_sq_ft` = '$cac_sq_ft', `property_tax` = '$property_tax', `property_tax_sq_ft` = '$property_tax_sq_ft' WHERE `contactid` = '$contactid'";
	$result_update_cost	= mysqli_query($dbc, $query_update_cost);
} else {
	$query_insert_cost = "INSERT INTO `contacts_cost` (`contactid`, `total_monthly_rate`, `total_annual_rate`, `condo_fees`, `deposit`, `damage_deposit`, `cost`, `final_retail_price`, `admin_price`, `wholesale_price`, `commercial_price`, `client_price`, `minimum_billable`, `estimated_hours`, `actual_hours`, `msrp`, `hourly_rate`, `monthly_rate`, `semi_monthly_rate`, `daily_rate`, `hr_rate_work`, `hr_rate_travel`, `field_day_cost`, `field_day_billable`, `probation_pay_rate`, `base_pay`, `performance_pay`, `base_rent`, `base_rent_sq_ft`, `cac`, `cac_sq_ft`, `property_tax`, `property_tax_sq_ft`) VALUES ('$contactid', '$total_monthly_rate', '$total_annual_rate', '$condo_fees', '$deposit', '$damage_deposit', '$cost', '$final_retail_price', '$admin_price', '$wholesale_price', '$commercial_price', '$client_price', '$minimum_billable', '$estimated_hours', '$actual_hours', '$msrp', '$hourly_rate', '$monthly_rate', '$semi_monthly_rate', '$daily_rate', '$hr_rate_work', '$hr_rate_travel', '$field_day_cost', '$field_day_billable', '$probation_pay_rate', '$base_pay', '$performance_pay', '$base_rent', '$base_rent_sq_ft', '$cac', '$cac_sq_ft', '$property_tax', '$property_tax_sq_ft')";
	$result_insert_cost = mysqli_query($dbc, $query_insert_cost);
}