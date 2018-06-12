<?php
include_once('../Reports/compensation_function.php');
$report_validation = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT group_concat(`inventoryid` separator '|') as `all_inventoryid`, group_concat(`sell_price` separator '|') as `all_sell_price`, group_concat(`quantity` separator '|') as `all_qty`, group_concat(`patientid` separator '|') as `patientids` FROM invoice WHERE therapistsid='$therapistid' AND serviceid IS NOT NULL AND (service_date >= '".$starttime."' AND service_date <= '".$endtime."') AND `invoice_type` IN ($invoicetype)"));

$all_inventoryid = explode('|', $report_validation['all_inventoryid']);
$all_sell_price = explode('|', $report_validation['all_sell_price']);
$all_patientids = explode('|', $report_validation['patientids']);
$all_qty = explode('|', $report_validation['all_qty']);
$inventoryid = [];
$sell_price = [];
$patientid = [];
$qty = [];

foreach($all_inventoryid as $i => $inventory) {
	$sell_prices = explode(',',$all_sell_price[$i]);
	$patientids = explode(',',$all_patientids[$i]);
	$quantities = explode(',',$all_qty[$i]);
	foreach(explode(',',$inventory) as $j => $invid) {
		if($invid > 0 || $sell_prices[$j] > 0) {
			$inventoryid[] = $invid;
			$sell_price[] = $sell_prices[$j];
			$patientid[] = $patientids[$j];
			$qty[] = $quantities[$j];
		}
	}
}

$total_base_inv = 0;
$total_qty = 0;

    // Inventory
    $comma_remove = str_replace(',', '', $report_validation['all_inventoryid']);
    if($comma_remove != '') {
        asort($inventoryid);
        $sorted_prices = [];
        $sorted_patient = [];
        $sorted_qty = [];
        foreach($inventoryid as $key=>$val) {
          array_push($sorted_prices, $sell_price[$key]);
          array_push($sorted_patient, $patientid[$key]);
          array_push($sorted_qty, $qty[$key]);
        }
        $combined = combineArrayDuplicates($inventoryid, $sorted_prices, $sorted_patient, $sorted_qty);

        $report_data .= '<h4>'.get_contact($dbc, $therapistid).' -  Inventory Compensation</h4>';

        $report_data .= '<table border="1px" class="table table-bordered" style="'.$table_style.'">';
        $report_data .= '<tr style="'.$table_row_style.'">';
		if(in_array('therapist_patient_info',$report_fields)) {
			$report_data .= '<th width="45%">Item Description</th>';
			$report_data .= '<th width="20%">Patient</th>';
		} else {
			$report_data .= '<th width="65%">Item Description</th>';
		}
		$report_data .= '<th width="5%">Qty</th>
        <th width="10%">Total Price</th>
        <th width="10%">Comp %</th>
        <th width="10%">Compensation</th>';
        $report_data .= "</tr>";
        foreach ($combined as $invid => $info) {
            $base_pay_inv_perc = 5;//$base_pay[1];
            $inv_pay = ($base_pay_inv_perc/100)*$info['sell_price'];

            if(number_format($inv_pay, 2) != '0.00') {
                $report_data .= '<tr nobr="true">';
                //$report_data .= '<td>'.get_contact($dbc, $therapistid).'</td>';
                $report_data .= '<td>'.$invid.' - '.get_all_from_inventory($dbc, $invid, 'name').'</td>';
				if(in_array('therapist_patient_info',$report_fields)) {
					$report_data .= '<td>';
					foreach($info['patientids'] as $patient) {
						$patient = mysqli_fetch_array(mysqli_query($dbc, "SELECT `contactid`, `category` FROM `contacts` WHERE `contactid`='$patient'"));
						$report_data .= '<a href="../Contacts/add_contacts.php?category='.$patient['category'].'&contactid='.$patient['contactid'].'&from_url='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'">'.get_contact($dbc, $patient['contactid']).'</a><br />';
					}
					$report_data .= '</td>';
				}
                $report_data .= '<td>'.$info['qty'].'</td>';
                $report_data .= '<td>$'.$info['sell_price'].'</td>';
                $report_data .= '<td>'.$base_pay_inv_perc.'%</td>';
                $report_data .= '<td>$'.number_format($inv_pay, 2).'</td>';
                $report_data .= '</tr>';
            }

            $total_base_inv += $inv_pay;
            $total_qty += $info['qty'];
        }
        $report_data .= '<tr nobr="true">';
        $report_data .= '<td colspan="'.(in_array('therapist_patient_info',$report_fields) ? 2 : 1).'">Total : '.get_contact($dbc, $therapistid).'</td>';
        $report_data .= '<td>' . $total_qty . '</td>';
        $report_data .= '<td></td>';
        $report_data .= '<td></td>';
        $report_data .= '<td>$' . number_format($total_base_inv, 2) . '</td>';
        $report_data .= "</tr>";
        $report_data .= '</table><br>';
        $grand_total += $total_base_inv;
    }
