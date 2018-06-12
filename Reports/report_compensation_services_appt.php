<?php

$starttime1 = $starttime.' 06:00:00';
$endtime1 = $endtime.' 20:00:00';
$start_time_int = strtotime($starttime1);
$end_time_int = strtotime($endtime1);

$report_validation = mysqli_query($dbc,"SELECT i.serviceid FROM invoice i, booking b, mrbs_entry m WHERE i.therapistsid='$therapistid' AND b.bookingid = i.bookingid AND m.id = b.calid AND (m.start_time >= '$start_time_int' AND m.end_time <= '$end_time_int')");

//$report_validation = mysqli_query($dbc,"SELECT serviceid FROM invoice WHERE therapistsid='$therapistid' AND serviceid IS NOT NULL AND (service_date >= '".$starttime."' AND service_date <= '".$endtime."')");

$all_service = '';
while($row_tab = mysqli_fetch_array( $report_validation )) {
    $all_service .= $row_tab['serviceid'].',';
}

$all_service = str_replace(",,,",",",$all_service);
$all_service = str_replace(",,",",",$all_service);

$serviceid = explode(',', $all_service);
$serviceid = array_filter($serviceid);

// Services
if($all_service != '') {
    asort($serviceid);

    $occurences = array_count_values($serviceid);

    $report_data .= '<h4>'.get_contact($dbc, $therapistid).' -  Services Compensation</h4>';

    $report_data .= '<table border="1px" class="table table-bordered" style="'.$table_style.'">';
    $report_data .= '<tr nobr="true" style="'.$table_row_style.'">
    <th width="53%">Service</th>
    <th width="7%">Fee</th>
    <th width="6%">Admin Fee</th>
    <th width="7%">Final Fee</th>
    <th width="8%">Comp%</th>
    <th width="7%">Comp Fee</th>
    <th width="5%">Total App.</th>
    <th width="7%">Services</th>';
    $report_data .= "</tr>";
    $total_base_service = 0;
    $total_base_fee = 0;
    $final_total_appt = 0;
    $final_comp = 0;
    foreach ($occurences as $key => $total_appt) {
        $final_serviceid = rtrim($key,',');

        /*
        $invoice = mysqli_query($dbc,"SELECT invoiceid, serviceid, patientid FROM invoice WHERE therapistsid='$therapistid' AND (service_date >= '".$starttime."' AND service_date <= '".$endtime."')");
        $iid = '';
        $total_appointments = 0;
        while($row_invoice = mysqli_fetch_assoc($invoice)) {
            $serviceid = $row_invoice['serviceid'];
            $each_serviceid = explode(',', $serviceid);
            foreach ($each_serviceid as $each_sid) {
                if($each_sid == $final_serviceid) {
                    $invoiceid = $row_invoice['invoiceid'];
                    $patientid = $row_invoice['patientid'];

                    $iid .= $invoiceid.' : '.get_contact($dbc, $patientid).',';
                    $total_appointments++;
                }
            }
        }

        $in_list = rtrim($iid, ',');
        */

        $fee = get_all_from_service($dbc, $final_serviceid, 'fee');
        $admin_price = get_all_from_service($dbc, $final_serviceid, 'admin_price');
        $final_fee = ($fee-$admin_price);
        $service_fee = ($final_fee*$total_appt);
        $base_pay_perc = $base_pay[0];
        $comp_pay = ($base_pay_perc*0.01*$service_fee);

        if(($final_serviceid == 47 && $therapistid == 6908) || ($final_serviceid == 62)) {

        } else {
            $report_data .= '<tr nobr="true">';
            //$report_data .= '<td>'.$final_serviceid.' : '.get_all_from_service($dbc, $final_serviceid, 'service_code').' - '.get_all_from_service($dbc, $final_serviceid, 'heading').'<br>(Inv# : '.$in_list.')</td>';
            $report_data .= '<td>'.$final_serviceid.' : '.get_all_from_service($dbc, $final_serviceid, 'service_code').' - '.get_all_from_service($dbc, $final_serviceid, 'heading').'</td>';
            $report_data .= '<td>$'.$fee.'</td>';
            $report_data .= '<td>-$'.$admin_price.'</td>';
            $report_data .= '<td>= $'.$final_fee.'</td>';

            $report_data .= '<td>'.$base_pay_perc.'% : X'.($base_pay_perc/100).'</td>';
            $report_data .= '<td>= $'.number_format(($final_fee*($base_pay_perc/100)), 2).'</td>';

            $report_data .= '<td>X'.$total_appt.'</td>';

            $report_data .= '<td>$'.number_format((($final_fee*($base_pay_perc/100))*$total_appt), 2).'</td>';

            $report_data .= '</tr>';
        }
        $final_total_appt += $total_appt;
        $total_base_service += $comp_pay;
    }
    $report_data .= '<tr nobr="true">';
    $report_data .= '<td colspan="1">Total : '.get_contact($dbc, $therapistid).'</td><td></td><td></td><td></td>';
    $report_data .= '<td></td><td></td><td>' . $final_total_appt . '</td>';
    $report_data .= '<td>$' . number_format($total_base_service, 2) . '</td>';
    $report_data .= "</tr>";
    $report_data .= '</table><br>';
    $grand_total += $total_base_service;
}