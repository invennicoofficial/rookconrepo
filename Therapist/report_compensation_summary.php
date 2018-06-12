<?php
    $vacation_pay = ($grand_total*$vacation_pay_perc)/100;
    $gt = $total_base_service+$total_base_inv;

    //<th>Vacation Pay</th>

    $report_data .= '<h4>'.get_contact($dbc, $therapistid).' -  Summary</h4>';
    $report_data .= '<table border="1px" class="table table-bordered" style="'.$table_style.'">';
    $report_data .= '<tr style="'.$table_row_style.'" nobr="true">';
    $report_data .= '<th>Services</th><th>Inventory</th><th>Grand Total</th><th>Stat Holiday Pay</th><th>Final Compensation</th></tr>';
    $report_data .= '<tr>';
    $report_data .= '<td>$' . number_format($total_base_service, 2) . '</td>';
    //$report_data .= '<td>$' . number_format($final_metrix, 2).'</td>';
    $report_data .= '<td>$' . number_format($total_base_inv, 2) . '</td>';
    $report_data .= '<td>$' . number_format($gt, 2) . '</td>';
    $report_data .= '<td>';
    //$report_data .= '('.$grand_stat_total.'/'.$days_worked.') = ';
    $report_data .= '$' . number_format($avg_per_day_stat, 2) . '</td>';
    //$report_data .= '<td>$' . number_format($vacation_pay, 2) . '</td>';
    $report_data .= '<td>$' . number_format(($gt+$avg_per_day_stat+$vacation_pay), 2) . '</td>';
    $report_data .= '</tr>';
    $report_data .= '</table><br>';