<?php
include_once('../tcpdf/tcpdf.php');

if (!file_exists('download')) {
    mkdir('download', 0777, true);
}

function pdf_header_footer() {
	class MYPDF extends TCPDF {

		//Page header
		public function Header() {
			//$image_file = 'img/washtech-logo-400px.png';
			//$this->Image($image_file, 10, 10, 60, '', 'PNG', '', 'C', false, 300, '', false, false, 0, false, false, false);
		}

		// Page footer
		public function Footer() {
			// Position at 15 mm from bottom
			$this->SetY(-15);
			$this->SetFont('helvetica', 'I', 8);
			$footer_text = date('Y-m-d');
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

	return $pdf;
}

function emp_info_medical_form($dbc,$contactid) {
	$pdf = pdf_header_footer();
	$employee = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT ori.*, s.* FROM orientation_emp_info_medical_form ori, contacts s WHERE ori.contactid = s.contactid AND ori.contactid = '$contactid'"));

	$html = '';
	$html .= '<center><h2>Employee Information & Emergency Contacts form</h2></center>';

    $html .= '<table border="1px" style="padding:3px; border:1px solid black;">
    <tr class="hidden-xs hidden-sm">
    <th style="background: red;">Name</th>
    <td data-title="Due Date">'.$employee['first_name'].' '.$employee['last_name'].'</td>
    </tr><tr>
    <th style="background: red;">Home Number</th>
    <td data-title="Business">' . $employee['home_phone'] . '</td>
    </tr><tr>
    <th style="background: red;">Cell Number</th>
    <td data-title="Business">' . $employee['mobile_phone'] . '</td>
    </tr><tr>
    <th style="background: red;">S.I.N.</th>
    <td data-title="Due Date">'.$employee['sin'].'</td>
    </tr><tr>
    <th style="background: red;">Health Care Card Number</th>
     <td data-title="Due Date">'.$employee['health_care_card_number'].'</td>
     </tr><tr>
    <th style="background: red;">Emergency Contact Name</th>
    <td data-title="Due Date">'.$employee['eme1_name'].'</td>
    </tr><tr>
    <th style="background: red;">Emergency Contact Phone</th>
    <td data-title="Due Date">'.$employee['eme1_phone'].'</td>
    </tr><tr>
    <th style="background: red;">Emergency Contact Relationship</th>
    <td data-title="Due Date">'.$employee['eme1_relationship'].'</td>
    </tr><tr>
    <th style="background: red;">Emergency Contact Name</th>
    <td data-title="Due Date">'.$employee['eme2_name'].'</td>
    </tr><tr>
    <th style="background: red;">Emergency Contact Phone</th>
    <td data-title="Due Date">'.$employee['eme2_phone'].'</td>
    </tr><tr>
    <th style="background: red;">Emergency Contact Relationship</th>
    <td data-title="Due Date">'.$employee['eme2_relationship'].'</td>
    </tr><tr>
    <th style="background: red;">Do you have any medical conditions that Hydrera needs to know about? If yes, please list</th>
    <td data-title="Due Date">'.$employee['medical_conditions'].'</td>
    </tr><tr>
    <th style="background: red;">Are you on any medications? If yes, please list</th>
    <td data-title="Due Date">'.$employee['medications'].'</td>
    </tr>';

	$html .=  '</table>';

	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output('download/'.$contactid.'_emp_info_medical_form.pdf', 'F');
}

function emp_driver_info_form($dbc,$contactid) {
	$pdf = pdf_header_footer();

	$employee = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT ori.*, s.* FROM orientation_emp_driver_info_form ori, contacts s WHERE ori.contactid = s.contactid AND ori.contactid = '$contactid'"));

	$html = '';
	$html .= '<center><h2>Employee Driver Information Form</h2></center>';

    $html .= '<table border="1px" style="padding:3px; border:1px solid black;">
    <tr class="hidden-xs hidden-sm">
    <th style="background: red;">Name</th>
    <td data-title="Due Date">'.$employee['first_name'].' '.$employee['last_name'].'</td>
    </tr><tr>
    <th style="background: red;">Driver License Number</th>
    <td data-title="Business">' . $employee['driver_license_number'] . '</td>
    </tr><tr>
    <th style="background: red;">Expiry Date</th>
    <td data-title="Due Date">'.$employee['expiry_date'].'</td>
    </tr><tr>
    <th style="background: red;">Class</th>
    <td data-title="Due Date">'.$employee['class'].'</td>
    </tr><tr>
    <th style="background: red;">Can you drive a truck with a standard transmission?</th>
     <td data-title="Due Date">'.$employee['truck_standard_transmission'].'</td>
     </tr><tr>
    <th style="background: red;">Do you have a current TDG (Transportation of Dangerous Goods)Ticket?</th>
    <td data-title="Due Date">'.$employee['tdg_ticket'].'</td>
    </tr>';
    if($employee['tdg_ticket'] == 'Yes') {
    $html .= '<tr>
    <th style="background: red;">TDG Expiry Date</th>
    <td data-title="Due Date">'.$employee['tdg_expiry_date'].'</td>
    </tr>';
    }

	$html .=  '</table>';

	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output('download/'.$contactid.'_emp_driver_info_form.pdf', 'F');
}

function direct_deposit_info($dbc,$contactid) {
	$pdf = pdf_header_footer();

	$employee = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT ori.*, s.* FROM orientation_direct_deposit_info ori, contacts s WHERE ori.contactid = s.contactid AND ori.contactid = '$contactid'"));

	$html = '';
	$html .= '<center><h2>Employee Direct Deposit Information</h2></center>';

    $html .= '<table border="1px" style="padding:3px; border:1px solid black;">
    <tr class="hidden-xs hidden-sm">
    <th style="background: red;">Name</th>
    <td data-title="Due Date">'.$employee['first_name'].' '.$employee['last_name'].'</td>
    </tr><tr>
    <th style="background: red;">Financial Institution Name</th>
    <td data-title="Business">' . $employee['financial_institution_name'] . '</td>
    </tr><tr>
    <th style="background: red;">Transit Number</th>
    <td data-title="Due Date">'.$employee['transit_number'].'</td>
    </tr><tr>
    <th style="background: red;">Financial Institution Number</th>
    <td data-title="Due Date">'.$employee['financial_institution_number'].'</td>
    </tr><tr>
    <th style="background: red;">Account Number</th>
    <td data-title="Due Date">'.$employee['account_number'].'</td>
    </tr>';

	$html .=  '</table>';

	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output('download/'.$contactid.'_direct_deposit_info.pdf', 'F');
}
?>