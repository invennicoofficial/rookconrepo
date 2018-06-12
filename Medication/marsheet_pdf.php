<?php
/*
MAR Sheet
*/
error_reporting(0);
include('../include.php');
include('../tcpdf/tcpdf.php');

$contactid = $_GET['contactid'];
$month = sprintf('%02d', $_GET['month']);
$year = $_GET['year'];
$date = $year.'-'.$month;
$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

class MYPDF extends TCPDF {

	//Page header
	public function Header() {

	}

	//Page footer
	public function Footer() {

	}
}

$pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetMargins(PDF_MARGIN_LEFT, 5, PDF_MARGIN_RIGHT);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 8);
$pdf->setCellHeightRatio(1);

$html = '';
$html .= '<p style="text-align: center">';
$html .= '<h1>'.get_contact($dbc, $contactid).' - '.date('F Y', strtotime($date)).'</h1>';
$html .= '<b>R = Refused, D = Destroyed, S = Sleeping, N = Nausea/Vomiting, O = Other</b>';
$html .= '</p>';

$marsheet_query = "SELECT * FROM `marsheet` WHERE `contactid` = '$contactid' AND `month` = '$month' AND `year` = '$year' AND `deleted` = 0 ORDER BY `marsheetid` ASC";
$marsheet_result = mysqli_fetch_all(mysqli_query($dbc, $marsheet_query),MYSQLI_ASSOC);

foreach ($marsheet_result as $marsheet) {
	$medicationid = $marsheet['medicationid'];
	$marsheetid = $marsheet['marsheetid'];
	$marsheet_route = $marsheet['route'];
	$marsheet_dosage = $marsheet['dosage'];
	$marsheet_instructions = $marsheet['instructions'];
	$marsheet_medication_notes = $marsheet['medication_notes'];
	$marsheet_comment = $marsheet['comment'];

	$html .= '<table border="1">';
	$html .= '<tr>';
	$html .= '<td style="width: 20%;"><table cellpadding="2"><tr><td>';
	foreach(explode(',',$medicationid) as $med_i => $medid) {
		$medication_name = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `medication` WHERE `medicationid` = '$medid'"))['title'];
		$marsheet_medication = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `marsheet_medication` WHERE `medicationid` = '$medid'"));
		$html .= 'Medication: '.$medication_name.'<br>';
		if(!empty($marsheet_medication['route'])) {
			$html .= 'Route: '.$marsheet_medication['route'].'<br>';
		}
		if(!empty($marsheet_medication['dosage'])) {
			$html .= 'Dosage: '.$marsheet_medication['dosage'].'<br>';
		}
		if(!empty($marsheet_medication['instructions'])) {
			$html .= 'Instructions: '.preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', html_entity_decode($marsheet_medication['instructions']), 1);
		}
		if($med_i < (count(explode(',',$medicationid)) - 1)) {
			$html .= '<br><hr>';
		}
	}
	$html .= '</td></tr></table></td>';
	
	$html .= '<td style="width: 80%;">';
	$html .= '<table border="1" cellpadding="2">';
	$html .= '<tr>';
	$html .= '<td style="width: 10%;"></td>';
	$td_width = 90 / $days_in_month;
	for($day_of_month = 1; $day_of_month <= $days_in_month; $day_of_month++) {
		$html .= '<td style="width: '.$td_width.'%;">'.$day_of_month.'</td>';
	}
	$html .= '</tr>';

	$marsheet_row_query = "SELECT * FROM `marsheet_row` WHERE `marsheetid` = '$marsheetid' AND `deleted` = 0 ORDER BY `marsheetrowid` ASC";
	$marsheet_row_result = mysqli_fetch_all(mysqli_query($dbc, $marsheet_row_query),MYSQLI_ASSOC);

	foreach ($marsheet_row_result as $marsheet_row) {
		$marsheetrowid = $marsheet_row['marsheetrowid'];
		$heading = $marsheet_row['heading'];

		$html .= '<tr>';
		$html .= '<td style="width: 10%;">'.$heading.'</td>';

		for($day_of_month = 1; $day_of_month <= $days_in_month; $day_of_month++) {
			$html .= '<td style="width: '.$td_width.'%;">'.$marsheet_row['day_'.$day_of_month].'</td>';
		}
		$html .= '</tr>';
	}
	$html .= '</table>';
	$html .= '</td>';
	$html .= '</tr>';
	$html .= '<tr>';
	$html .= '<td style="width:50%;"><table cellpadding="2"><tr><td>Medication Notes (Allergies or Special Instructions):<br>'.preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', html_entity_decode($marsheet_medication_notes), 1).'</td></tr></table></td>';
	$html .= '<td style="width:50%;"><table cellpadding="2"><tr><td>Notes:<br>'.preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', html_entity_decode($marsheet_comment), 1).'</td></tr></table></td>';
	$html .= '</tr>';
	$html .= '</table><br><br>';
}

$pdf->writeHTML(utf8_encode($html), true, false, true, false, '');

if(!file_exists('download')) {
	mkdir('download', 0777, true);
}

$today_date = date('Y-m-d_H-i-a', time());
$file_name = $contactid.'_'.$today_date.'.pdf';

$pdf->Output('download/'.$file_name, 'F');
echo '<script type="text/javascript">window.location.replace("download/'.$file_name.'", "_blank");</script>';
?>