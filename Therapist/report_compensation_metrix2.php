<?php

$report_validation = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT group_concat(`serviceid` separator ',') as `all_serviceid`, group_concat(`inventoryid` separator ',') as `all_inventoryid`, group_concat(`sell_price` separator ',') as `all_sell_price` FROM invoice WHERE therapistsid='$therapistid' AND serviceid IS NOT NULL AND (service_date >= '".$starttime."' AND service_date <= '".$endtime."')"));

$serviceid = explode(',', $report_validation['all_serviceid']);
$inventoryid = explode(',', $report_validation['all_inventoryid']);
$sell_price = explode(',', $report_validation['all_sell_price']);

$serviceid = array_filter($serviceid);
$inventoryid = array_filter($inventoryid);
$sell_price = array_filter($sell_price);

// Services
if($report_validation['all_serviceid'] != '') {
    asort($serviceid);
    $occurences = array_count_values($serviceid);

    $report_data .= '<h4>'.get_contact($dbc, $therapistid).' -   Metrics Compensation</h4>';

    $report_data .= '<table border="1px" class="table table-bordered" style="'.$table_style.'">';
    $report_data .= '<tr nobr="true" style="'.$table_row_style.'">
    <th width="42%">Service : Fee - Admin Fee = Final Fee</th>
    <th width="7%">Services</th>';

    if($category_contact == 'Physical Therapist') {
    //if($arr_target <= $arr_actual_value) {
        $report_data .= '<th width="10%">Arr. Rate % <br>Goal : '.$arr_target.'%<br> Actual : '.$arr_actual_value.'<br>Comp. : '.$arr_perc.'%</th>';
    //}
    //if($avg_hours_sch_target <= $avg_hours_sch_actual_value) {
        $report_data .= '<th width="10%">% scheduled <br>Goal : '.$avg_hours_sch_target.'%<br> Actual : '.$avg_hours_sch_actual_value.'<br>Comp. : '.$avg_hours_sch_perc.'%</th>';
    //}
    } else {
        $report_data .= '<th width="17%">Arr. Rate % <br>Goal : '.$arr_target.'%<br> Actual : '.$arr_actual_value.'<br>Comp. : '.$arr_perc.'%</th>';
        $report_data .= '<th width="17%">% scheduled <br>Goal : '.$avg_hours_sch_target.'%<br> Actual : '.$avg_hours_sch_actual_value.'<br>Comp. : '.$avg_hours_sch_perc.'%</th>';
    }

    //if($total_testi != 0) {
        $report_data .= '<th width="7%">Test. submitted <br>Actual : '.$test_actual_value.'<br>Comp. : '.$test_perc.'%</th>';
    //}
    //if($total_inter != 0) {
    if($category_contact == 'Physical Therapist') {
        $report_data .= '<th width="7%">Intermediate cert. <br>Actual : '.$inter_actual_value.'<br>Comp. : '.$inter_perc.'%</th>';
    //}
    //if($total_adv != 0) {
        $report_data .= '<th width="7%">Advanced Diploma cert. <br>Actual : '.$adv_actual_value.'<br>Comp. : '.$adv_perc.'%</th>';
    //}
    }
    $report_data .= '<th width="10%">Final Comp.</th>';

    $report_data .= "</tr>";
    $final_metrix = 0;
    foreach ($occurences as $key => $total_appt) {
        $final_serviceid = rtrim($key,',');

        $fee = get_all_from_service($dbc, $final_serviceid, 'fee');
        $admin_price = get_all_from_service($dbc, $final_serviceid, 'admin_price');
        $final_fee = ($fee-$admin_price);

        if($final_serviceid != '42' && $final_serviceid != '43' && $final_serviceid != '45') {
            $after1 = 0;
            $after2 = 0;
            $after3 = 0;
            $after4 = 0;
            $after5 = 0;

            $report_data .= '<tr nobr="true">';
            $report_data .= '<td>'.get_all_from_service($dbc, $final_serviceid, 'service_code').' - '.get_all_from_service($dbc, $final_serviceid, 'heading').'</td>';

            $service_fee = ($final_fee*$total_appt);

            $base_pay_perc = $base_pay[0];
            $comp_pay = ($base_pay_perc*0.01*$service_fee);

            $report_data .= '<td>$'.number_format($final_fee, 2).'</td>';

            $total_header = '';
            if($arr_target <= $arr_actual_value) {
                $report_data .= '<td>X'.($arr_perc/100).' = $'.number_format($final_fee*($arr_perc/100), 2).' X '.$total_appt.'<br> = '.number_format(($final_fee*($arr_perc/100)) * $total_appt, 2).'</td>';
                $total_header .= '<td></td>';
                $after1 = ($final_fee*($arr_perc/100)) * $total_appt;
            } else {
                $report_data .= '<td>$0</td>';
                $total_header .= '<td></td>';
            }

            if($avg_hours_sch_target <= $avg_hours_sch_actual_value) {
                $report_data .= '<td>X'.($avg_hours_sch_perc/100).' = $'.number_format($final_fee*($avg_hours_sch_perc/100), 2).' X '.$total_appt.'<br> = '.number_format(($final_fee*($avg_hours_sch_perc/100)) * $total_appt, 2).'</td>';
                $total_header .= '<td></td>';
                $after2 = ($final_fee*($avg_hours_sch_perc/100)) * $total_appt;
            } else {
                $report_data .= '<td>$0</td>';
                $total_header .= '<td></td>';
            }


            if($test_actual_value != 0) {
                $report_data .= '<td>X'.($test_perc/100).' = $'.number_format($final_fee*($test_perc/100), 2).' X '.$total_appt.'<br> = '.number_format(($final_fee*($test_perc/100)) * $total_appt, 2).'</td>';
                $total_header .= '<td></td>';
                $after3 = ($final_fee*($test_perc/100)) * $total_appt;
            } else {
                $report_data .= '<td>$0</td>';
                $total_header .= '<td></td>';
            }

            if($category_contact == 'Physical Therapist') {
                if($inter_actual_value != 0) {
                    $report_data .= '<td>X'.($inter_perc/100).' = $'.number_format($final_fee*($inter_perc/100), 2).' X '.$total_appt.'<br> = '.number_format(($final_fee*($inter_perc/100)) * $total_appt, 2).'</td>';

                    $total_header .= '<td></td>';
                    $after4 = ($final_fee*($inter_perc/100)) * $total_appt;
                } else {
                    $report_data .= '<td>$0</td>';
                    $total_header .= '<td></td>';
                }

                if($adv_actual_value != 0) {
                    $report_data .= '<td>X'.($adv_perc/100).' = $'.number_format($final_fee*($adv_perc/100), 2).' X '.$total_appt.'<br> = '.number_format(($final_fee*($adv_perc/100)) * $total_appt, 2).'</td>';

                    $total_header .= '<td></td>';
                    $after5 = ($final_fee*($adv_perc/100)) * $total_appt;
                } else {
                    $report_data .= '<td>$0</td>';
                    $total_header .= '<td></td>';
                }
            }

            $metrix = ($after1+$after2+$after3+$after4+$after5);
            $report_data .= '<td>$'.number_format($metrix, 2).'</td>';

            $report_data .= '</tr>';
            $final_metrix += $metrix;
        }
    }
    $report_data .= '<tr nobr="true">';
    $report_data .= '<td colspan="1">Total : '.get_contact($dbc, $therapistid).'</td>';
    $report_data .= '<td></td>'.$total_header;
    $report_data .= '<td>$' . number_format($final_metrix, 2).'</td>';
    $report_data .= "</tr>";
    $report_data .= '</table><br>';
    $grand_total += $final_metrix;
}