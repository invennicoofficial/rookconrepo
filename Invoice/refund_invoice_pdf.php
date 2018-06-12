 <?php

    $patientid = get_all_from_invoice($dbc, $invoiceid, 'patientid');
    $therapistsid = get_all_from_invoice($dbc, $invoiceid, 'therapistsid');
    $service_date = get_all_from_invoice($dbc, $invoiceid, 'service_date');

    $staff = get_contact($dbc, $therapistsid);

    //Patient Invoice
	class MYPDF extends TCPDF {

		//Page header
		public function Header() {
            if(INVOICE_LOGO != '') {
                $image_file = 'download/'.INVOICE_LOGO;
                $this->Image($image_file, 10, 10, 60, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
            }
            $this->SetFont('helvetica', '', 8);
            $header_text = '';
            $this->writeHTMLCell(0, 0, '', '', $header_text, 0, 0, false, "L", "R",true);
		}

		// Page footer
		public function Footer() {
			// Position at 15 mm from bottom
			$this->SetY(-15);
			$this->SetFont('helvetica', 'I', 8);
			$footer_text = 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages().' printed on  '.date('m/d/y').' at '.date('g:i:s A');
			$this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "L", true);
		}
	}

	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, false, false);
    $pdf->setFooterData(array(0,64,0), array(0,64,128));

    $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 9);

	$html = '';

	$html .= '
    <b>Refund Invoice# : '.$refundid.'<br/>
	<b>Invoice# : '.$invoiceid.'<br/>
	Invoice Date : '.$invoice_date.'</b><br/><br/>
	Patient Information : </b><br/>'.
	$patients .'<br/>'.
	get_contact_phone($dbc, $patientid) .'<br/><br/>

	<b>Invoice Details</b><br/><br/>
	';

	$html .= '<table border="1px" class="table table-bordered" style="padding:3px; border:1px solid black;">
	<tr>
		<th>Date of Service</th>
		<th>Refund Description</th>
		<th>Provider Name & Registration Info</th>
		<th>Total Refund</th>
	</tr>
	';

    if($service_pdf != '') {
        $html .= '<tr>
            <td>'.$service_date.'</td>
            <td>'.$service_pdf.'</td>
            <td>'.$staff.', '.get_all_form_contact($dbc, $therapistsid, 'credential').';'.get_all_form_contact($dbc, $therapistsid, 'license').'</td>
            <td>$'.$refund_service_fee.'</td>
        </tr>';
    }

    if($refund_inventory_item != '') {
        $html .= '<tr>
            <td>'.$service_date.'</td>
            <td>'.$refund_inventory_item.'</td>
            <td><br></td>
            <td>$'.$refund_inv_fee.'</td>
        </tr>';
    }

	$html .= '</table><br/><br/>';

	$html .= 'Total Refund:  <b>$'. $refund_final_price .'</b><br/>';

	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output('Download/refundinvoice_'.$invoiceid.'_'.$refundid.'.pdf', 'F');

	//Patient Invoice