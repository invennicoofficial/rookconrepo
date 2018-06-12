<?php
//PDF
//for($i=0; $i<count($_POST['insurance_payment']); $i++) {
foreach($insurers as $insurerid_pdf => $insurer_price_pdf) {
    //$insurerid_pdf = $_POST['insurerid'][$i];
    //$insurer_price_pdf = $_POST['insurance_payment'][$i];

    if(($insurerid_pdf != '') || (!empty($insurerid_pdf) || ($insurer_price_pdf!=''))) {
		//$query_insert_ins = "INSERT INTO `report_insurer` (`contactid`, `cost`, `today_date`, `invoiceid`, `paid`) VALUES ('$insurerid_pdf', '$insurer_price_pdf', '$today_date', '$invoiceid', 'No')";
		//$result_insert_ins = mysqli_query($dbc, $query_insert_ins);

		//$query_update_patient = "UPDATE `contacts` SET `amount_to_bill` = amount_to_bill + '$insurer_price_pdf' WHERE `contactid` = '$insurerid_pdf'";
		//$result_update_patient = mysqli_query($dbc, $query_update_patient);

		//$pdfname = 'T'. $i;

		//$pdfname =new TCPDF('P', 'pt', $pageLayout, true, 'UTF-8', false);

		$insure_pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		$insure_pdf->setHeaderData('',0,'','',array(0,0,0), array(255,255,255) );
		$insure_pdf->setFooterData(array(0,64,0), array(0,64,128));

		$insure_pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
		$insure_pdf->SetHeaderMargin(0);
		$insure_pdf->SetFooterMargin(0);
		$insure_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		$insure_pdf->AddPage();
		$insure_pdf->SetFont('helvetica', '', 9);

		$html = '';

		$html .= '<table>
		<tr>
			<td style="width:55%">';
			if(INVOICE_LOGO != '') {
				$html .= '<img style="text-align:left;" src="download/'.INVOICE_LOGO.'" style="height:55px; width:220px;" border="0" alt="">';
			}
		$html .= '</td>
			<td style="width:45%; line-height:60%; text-align:right;">'.INVOICE_HEADER.'</td>
		</tr></table>
		';

		$insure_pdf->writeHTML($html, true, false, true, false, '');
		$insure_pdf->SetFont('helvetica', '', 11);

		$html = '';
		$html .= '
		<b>Invoice# : '.$invoiceid.'<br/>
		Invoice Date : '.$get_invoice['invoice_date'].'</b><br/><br/>
		Patient Information : </b><br/>'.
		$patients .'<br/>'.
		get_address($dbc, $get_invoice['patientid']).'<br/><br/>
		<b>Invoice Details</b><br/>
		';

		$insure_pdf->writeHTML($html, true, false, true, false, '');
		$insure_pdf->SetFont('helvetica', '', 9);

		$html = '';
		$html .= '<table border="1px" class="table table-bordered" style="padding:3px; border:1px solid black;">
		<tr>
			<th>Date of Service</th>
			<th>Description</th>
			<th>Provider Name & Registration Info</th>
			<th>Total Fee/Payment</th>
			<th>Your Portion</th>
		</tr>
		';

		$html .= '<tr>
			<td>'.$get_invoice['service_date'].'</td>
			<td>'.$list_service_insurer[$insurerid_pdf].'</td>
			<td>'.$staff.', '.get_all_form_contact($dbc, $therapistsid, 'credential').'; '.get_all_form_contact($dbc, $therapistsid, 'license').'</td>
			<td>$'.$fee_total_price.'</td>
			<td>$'.$fee_insurer_price[$insurerid_pdf].'</td>
		</tr>';

		if($list_inventory_insures[$insurerid_pdf] != '') {
			$html .= '<tr>
			<td>'.$get_invoice['service_date'].'</td>
			<td>'.$list_inventory_insures[$insurerid_pdf].'</td>
			<td>'.get_all_form_contact($dbc, $insurerid_pdf, 'name').'</td>
			<td>$'.$inv_total_price.'</td>
			<td>$'.$inv_insurer_price[$insurerid_pdf].'</td>
			</tr>';
		}

		if($list_package_insures[$insurerid_pdf] != '') {
			$html .= '<tr>
			<td>'.$get_invoice['service_date'].'</td>
			<td>'.$list_package_insures[$insurerid_pdf].'</td>
			<td>'.get_all_form_contact($dbc, $insurerid_pdf, 'name').'</td>
			<td>$'.$package_total_price.'</td>
			<td>$'.$package_insurer_price[$insurerid_pdf].'</td>
			</tr>';
		}

		$html .= '</table><br/><br/>';
		$get_pos_tax = get_config($dbc, 'invoice_tax');
		if($get_pos_tax != '') {
			$pos_tax = explode('*#*',$get_pos_tax);

			$total_count = mb_substr_count($get_pos_tax,'*#*');
			for($eq_loop=0; $eq_loop<=$total_count; $eq_loop++) {
				$pos_tax_name_rate = explode('**',$pos_tax[$eq_loop]);
				$html .= $pos_tax_name_rate[0] .' : '.$pos_tax_name_rate[1].'%  ['.$pos_tax_name_rate[2].'] : <b>$'.round(array_sum($insurer_price_pdf)/($final_price)*($final_price-$total_price),2).'</b><br>';
			}
		}
		$html .= 'Total Charges:  <b>$'. $insurer_price_pdf .'</b><br/>';

		$insure_pdf->writeHTML($html, true, false, true, false, '');
		$insure_pdf->Output('Download/insuranceinvoice_'.$insurerid_pdf.'_'.$invoiceid.'.pdf', 'F');
    }
}
	//Insurer Invoice