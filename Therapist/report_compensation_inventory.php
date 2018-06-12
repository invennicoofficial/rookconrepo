<?php
$report_validation = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT group_concat(`inventoryid` separator ',') as `all_inventoryid`, group_concat(`sell_price` separator ',') as `all_sell_price` FROM invoice WHERE therapistsid='$therapistid' AND serviceid IS NOT NULL AND (service_date >= '".$starttime."' AND service_date <= '".$endtime."')"));

$inventoryid = explode(',', $report_validation['all_inventoryid']);
$sell_price = explode(',', $report_validation['all_sell_price']);

$inventoryid = array_filter($inventoryid);
$sell_price = array_filter($sell_price);
$total_base_inv = 0;
$total_qty = 0;

    // Inventory
    $comma_remove = str_replace(',', '', $report_validation['all_inventoryid']);
    if($comma_remove != '') {
        asort($inventoryid);
        $sorted_arr2 = [];
        foreach($inventoryid as $key=>$val) {
          array_push($sorted_arr2, $sell_price[$key]);
        }
        $combined = combineStringArrayWithDuplicates($inventoryid, $sorted_arr2);

        $report_data .= '<h4>'.get_contact($dbc, $therapistid).' -  Inventory Compensation</h4>';

        $report_data .= '<table border="1px" class="table table-bordered" style="'.$table_style.'">';
        $report_data .= '<tr style="'.$table_row_style.'">
        <th width="65%">Item Description</th>
        <th width="5%">Qty</th>
        <th width="10%">Total Price</th>
        <th width="10%">Comp %</th>
        <th width="10%">Compensation</th>';
        $report_data .= "</tr>";
        foreach ($combined as $key => $sell_price) {
            $key_invid_qty = explode(':', $key);
            $invid = $key_invid_qty[0];

            $base_pay_inv_perc = $base_pay[1];
            $inv_pay = ($base_pay_inv_perc/100)*$sell_price;

            if(number_format($inv_pay, 2) != '0.00') {
                $report_data .= '<tr nobr="true">';
                //$report_data .= '<td>'.get_contact($dbc, $therapistid).'</td>';
                $report_data .= '<td>'.$invid.' - '.get_all_from_inventory($dbc, $invid, 'name').'</td>';
                $report_data .= '<td>'.$key_invid_qty[1].'</td>';
                $report_data .= '<td>$'.$sell_price.'</td>';
                $report_data .= '<td>'.$base_pay_inv_perc.'%</td>';
                $report_data .= '<td>$'.number_format($inv_pay, 2).'</td>';
                $report_data .= '</tr>';
            }

            $total_base_inv += $inv_pay;
            $total_qty += $key_invid_qty[1];
        }
        $report_data .= '<tr nobr="true">';
        $report_data .= '<td colspan="1">Total : '.get_contact($dbc, $therapistid).'</td>';
        $report_data .= '<td>' . $total_qty . '</td>';
        $report_data .= '<td></td>';
        $report_data .= '<td></td>';
        $report_data .= '<td>$' . number_format($total_base_inv, 2) . '</td>';
        $report_data .= "</tr>";
        $report_data .= '</table><br>';
        $grand_total += $total_base_inv;
    }
