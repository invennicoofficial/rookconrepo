<?php
//For Gratuity //

//if($gratuity != '' && $gratuity != 0) {
//    $query_insert_gratuity = "INSERT INTO `report_gratuity` (`today_date`, `patientid`, `therapistsid`, `gratuity`) VALUES ('$final_service_date', '$patientid', '$therapistsid', '$gratuity')";
//    $result_insert_gratuity = mysqli_query($dbc, $query_insert_gratuity);
//}

//For Gratuity //

//$payor = $patients;

//For Daily Validation Report
$qty = count($_POST['serviceid']);

$validation_desc = '';
$pt = $_POST['payment_type'][0];
$service = '';

for($i=0; $i<count($_POST['serviceid']); $i++) {
    $serviceid = $_POST['serviceid'][$i];
    $fee = $_POST['fee'][$i];

    if($serviceid != '') {
        $service .= get_all_from_service($dbc, $serviceid, 'service_code').' : '.get_all_from_service($dbc, $serviceid, 'heading').'<br>';

        /*
        if($paid == 'No') {
            $query_insert_invoice_report = "INSERT INTO `report_receivables` (`invoiceid`, `itemid`, `fee`, `today_date`) VALUES ('$invoiceid', '$serviceid', '$fee', '$final_service_date')";
            $result_insert_invoice_report = mysqli_query($dbc, $query_insert_invoice_report);
        }
        */

        /*
        //report_compensation
        $compy_pay = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pay_dollor, pay_perc FROM compensation_pay_therapist WHERE serviceid='$serviceid' AND therapistid='$therapistsid'"));
        $pay_dollor = $compy_pay['pay_dollor'];
        $pay_perc = $compy_pay['pay_perc'];

        if($pay_perc != '') {
            $compensation_pay = ($fee*$pay_perc)/100;
            $query_insert_report = "INSERT INTO `report_compensation` (`invoiceid`, `therapistid`, `item_type`, `serviceid`, `servicefee`, `patientid`, `compensation_pay`, `today_date`) VALUES ('$invoiceid', '$therapistsid', 'Service', '$serviceid', '$fee', '$patientid', '$compensation_pay', '$final_service_date')";
            $result_insert_report = mysqli_query($dbc, $query_insert_report);
        }
        if($pay_dollor != '') {
            $compensation_pay = $pay_dollor;
            $query_insert_report = "INSERT INTO `report_compensation` (`invoiceid`, `therapistid`, `item_type`, `serviceid`, `servicefee`, `patientid`, `compensation_pay`, `today_date`) VALUES ('$invoiceid', '$therapistsid', 'Service', '$serviceid', '$fee', '$patientid', '$compensation_pay', '$final_service_date')";
            $result_insert_report = mysqli_query($dbc, $query_insert_report);
        }
        //report_compensation
        */

        if($paid == 'Yes') {
            $amt_invoiced = $amt_paid = $fee;
            $amt_bill = '0.00';
        }
        if($paid == 'No') {
            $amt_bill = $fee;
            $amt_invoiced = $amt_paid = '0.00';
        }

        $validation_desc = get_all_from_service($dbc, $serviceid, 'service_code').' - '.get_all_from_service($dbc, $serviceid, 'heading').'<br>';

        $payer_name = get_all_form_contact($dbc, $insurerid, 'name');
        /*
        $query_insert_validation = "INSERT INTO `report_validation` (`therapist`, `invoice_date`, `patient`, `invoiceid`, `serviceid`, `description`, `payer_name`, `rate`, `qty`, `amt_to_bill`, `amt_invoiced`, `amt_paid`) VALUES ('$staff', '$final_service_date', '$patients', '$invoiceid', '$serviceid', '$validation_desc', '$payer_name', '$fee', '1', '$amt_bill', '$amt_invoiced', '$amt_paid')";
        $result_insert_validation = mysqli_query($dbc, $query_insert_validation);
        */
    }
}

$promotion = '';
if($promotionid != '') {
    $promotion .= 'Promotion : '.get_promotion($dbc, $promotionid, 'heading').' : $'.get_promotion($dbc, $promotionid, 'cost');
}

//For Daily Validation Report

//For Daily Sales Summary
$daily_to_bill = 0;
for($i=0; $i<count($_POST['insurance_payment']); $i++) {
    $daily_to_bill += $_POST['insurance_payment'][$i];
}

if($_POST['paid'] == 'Yes') {
    $daily_payment_amount = $_POST['final_price']-$daily_to_bill;
} else {
    $daily_payment_amount = 0;
}

$daily_invoiced = $_POST['final_price'];
$daily_total_price = $_POST['total_price'];
$daily_gratuity = $_POST['gratuity'];

/*
$get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(summaryid) AS summaryid FROM report_summary WHERE DATE(today_date) = '$final_service_date'"));
if($get_config['summaryid'] == 0) {
    $query_insert_summary = "INSERT INTO `report_summary` (`today_date`) VALUES ('$final_service_date')";
    $result_insert_summary = mysqli_query($dbc, $query_insert_summary);
}

$query_update_employee = "UPDATE `report_summary` SET `daily_to_bill` = `daily_to_bill` + '$daily_to_bill', `daily_invoiced` = `daily_invoiced` + '$daily_invoiced', `daily_payment_amount` = `daily_payment_amount` + '$daily_payment_amount', `gratuity` = `gratuity` + '$daily_gratuity' WHERE DATE(today_date) = '$final_service_date'";
$result_update_employee = mysqli_query($dbc, $query_update_employee);
*/

$summary_value_config = get_config($dbc, 'invoice_tax');
$summary_invoice_tax = explode('*#*',$summary_value_config);

$summary_total_count = mb_substr_count($summary_value_config,'*#*');
$summary_tax_rate = 0;
for($summary_eq_loop=0; $summary_eq_loop<=$summary_total_count; $summary_eq_loop++) {
    $summary_invoice_tax_name_rate = explode('**',$summary_invoice_tax[$summary_eq_loop]);

    $tax_rate = $summary_invoice_tax_name_rate[1];
    $tax_rate_value = ($daily_total_price*$tax_rate)/100;

    $column_name = $summary_invoice_tax_name_rate[0];

    //$query_update_summary = "UPDATE `report_summary` SET `$column_name` = `$column_name` + $tax_rate_value WHERE DATE(today_date) = '$final_service_date'";
    //$result_update_summary = mysqli_query($dbc, $query_update_summary);
}

/*
for($summary_i=0; $summary_i<count($_POST['payment_type']); $summary_i++) {
    $summary_daily_fee = $_POST['payment_price'][$summary_i];
    $summary_daily_payment_type = $_POST['payment_type'][$summary_i];

    if($summary_daily_payment_type != '') {
        $query_update_summary = "UPDATE `report_summary` SET `$summary_daily_payment_type` = `$summary_daily_payment_type` + $summary_daily_fee WHERE DATE(today_date) = '$final_service_date'";
        $result_update_summary = mysqli_query($dbc, $query_update_summary);
    }
}
*/

//For Daily Sales Summary