<?php
include ('database_connection.php');

for($i=1;$i<=183;$i++) {
    $cost = mysqli_query($dbc, "INSERT INTO `contacts_cost` (`contactid`, `total_monthly_rate`, `total_annual_rate`, `condo_fees`, `deposit`, `damage_deposit`, `cost`, `final_retail_price`, `admin_price`, `wholesale_price`, `commercial_price`, `client_price`, `minimum_billable`, `estimated_hours`, `actual_hours`, `msrp`, `hourly_rate`, `monthly_rate`, `semi_monthly_rate`, `daily_rate`, `hr_rate_work`, `hr_rate_travel`, `field_day_cost`, `field_day_billable`, `probation_pay_rate`, `base_pay`, `performance_pay`, `unit_no`, `base_rent`, `base_rent_sq_ft`, `cac`, `cac_sq_ft`, `property_tax`, `property_tax_sq_ft`) VALUES ('$i', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00')");

    $dates = mysqli_query($dbc, "INSERT INTO `contacts_dates` (`contactid`, `contact_since`, `date_of_last_contact`, `start_date`, `expiry_date`, `renewal_date`, `lease_term_date`, `date_contract_signed`, `option_to_renew_date`, `rate_increase_date`, `insurance_expiry_date`, `account_expiry_date`, `hire_date`, `probation_end_date`, `probation_expiry_reminder_date`, `birth_date`) VALUES
    ('$i', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00')");

    $description = mysqli_query($dbc, "INSERT INTO `contacts_description` (`contactid`, `quote_description`, `description`, `property_information`, `general_comments`, `comments`, `notes`) VALUES
    ('$i', '', '', '', '', '', '')");

    $upload = mysqli_query($dbc, "INSERT INTO `contacts_upload` (`contactid`, `application`, `contactimage`, `upload_license_plate`, `upload_property_information`, `upload_inspection`, `upload_letter_of_intent`, `upload_vendor_documents`, `upload_marketing_material`, `upload_purchase_contract`, `upload_support_contract`, `upload_support_terms`, `upload_rental_contract`, `upload_management_contract`, `upload_articles_of_incorporation`, `upload_commercial_insurance`, `upload_residential_insurance`, `upload_wcb`) VALUES
    ('$i', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '')");

}
    echo 'Done';

?>