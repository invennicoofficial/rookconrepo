<?php
/*
 * Water Temp Chart Export PDF
 * Called from edit_addition_water_temp_chart.php
 */
error_reporting(0);
include('../include.php');
include('../tcpdf/tcpdf.php');

$chart_type = $_GET['type'];
$clientid = $_GET['clientid'];
$no_client = $_GET['no_client'];
if($no_client == 1) {
	$clientid = 0;
}
$date = $_GET['date'];
$month = date('m', strtotime($date));
$year = date('Y', strtotime($date));
$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$td_width = 90 / $days_in_month;

//PDF Settings
$pdf_settings = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `medical_charts_pdf_setting`"));

$pdf_logo = !empty($pdf_settings['pdf_logo']) ? $pdf_settings['pdf_logo'] : '';

$header_text = !empty($pdf_settings['header_text']) ? $pdf_settings['header_text'] : '';
$header_align = !empty($pdf_settings['header_align']) ? $pdf_settings['header_align'] : 'R';
$header_font = !empty($pdf_settings['header_font']) ? $pdf_settings['header_font'] : 'helvetica';
$header_size = !empty($pdf_settings['header_size']) ? $pdf_settings['header_size'] : 9;
$header_color = !empty($pdf_settings['header_color']) ? $pdf_settings['header_color'] : '#000000';

$footer_text = !empty($pdf_settings['footer_text']) ? $pdf_settings['footer_text'] : '';
$footer_align = !empty($pdf_settings['footer_align']) ? $pdf_settings['footer_align'] : 'C';
$footer_font = !empty($pdf_settings['footer_font']) ? $pdf_settings['footer_font'] : 'helvetica';
$footer_size = !empty($pdf_settings['footer_size']) ? $pdf_settings['footer_size'] : 9;
$footer_color = !empty($pdf_settings['footer_color']) ? $pdf_settings['footer_color'] : '#000000';

$body_font = !empty($pdf_settings['body_font']) ? $pdf_settings['body_font'] : 'helvetica';
$body_size = !empty($pdf_settings['body_size']) ? $pdf_settings['body_size'] : 9;
$body_color = !empty($pdf_settings['body_color']) ? $pdf_settings['body_color'] : '#000000';

DEFINE(MC_PDF_LOGO, $pdf_logo);
DEFINE(MC_HEADER_TEXT, html_entity_decode($header_text));
DEFINE(MC_HEADER_ALIGN, $header_align);
DEFINE(MC_HEADER_FONT, $header_font);
DEFINE(MC_HEADER_SIZE, $header_size);
DEFINE(MC_HEADER_COLOR, $header_color);
DEFINE(MC_FOOTER_TEXT, html_entity_decode($footer_text));
DEFINE(MC_FOOTER_ALIGN, $footer_align);
DEFINE(MC_FOOTER_FONT, $footer_font);
DEFINE(MC_FOOTER_SIZE, $footer_size);
DEFINE(MC_FOOTER_COLOR, $footer_color);

class MYPDF extends TCPDF {

	//Page header
	public function Header() {
        $logo_align = (MC_HEADER_ALIGN == "L" ? "R" : "L");
        $header_align = MC_HEADER_ALIGN;
        switch($header_align) {
        	case 'L':
        		$align_style = 'text-align: left;';
        		break;
        	case 'C':
        		$align_style = 'text-align: center;';
        		break;
        	case 'R':
        		$align_style = 'text-align: right;';
        }
		$font_style = 'font-family: '.MC_HEADER_FONT.'; font-size: '.MC_HEADER_SIZE.'; color: '.MC_HEADER_COLOR.'; '.$align_style;
		$this->setFont('helvetica', '', 9);
		if(MC_PDF_LOGO != '') {
			$image_file = '../Medical Charts/download/'.MC_PDF_LOGO;
            $this->Image($image_file, 10, 5, 0, 25, '', '', 'T', false, 300, $logo_align, false, false, 0, false, false, false);
		}

		if(MC_HEADER_TEXT != '') {
            $this->setCellHeightRatio(0.7);
			$header_text = '<p style="'.$font_style.'">'.MC_HEADER_TEXT.'</p>';
            $this->writeHTMLCell(0, 0, 5 , 5, $header_text, 0, 0, false, true, $header_align, true);
		}
	}

	//Page footer
	public function Footer() {
        $page_align = (MC_FOOTER_ALIGN == "R" ? "L" : "R");
        $footer_align = MC_FOOTER_ALIGN;
        switch($footer_align) {
        	case 'L':
        		$align_style = 'text-align: left;';
	            $page_align_style = 'text-align: right;';
        		break;
        	case 'C':
        		$align_style = 'text-align: center;';
	            $page_align_style = 'text-align: right;';
        		break;
        	case 'R':
        		$align_style = 'text-align: right;';
	            $page_align_style = 'text-align: left;';
        }
		$font_style = 'font-family: '.MC_FOOTER_FONT.'; font-size: '.MC_FOOTER_SIZE.'; color: '.MC_FOOTER_COLOR.'; '.$align_style;

        // Position at 15 mm from bottom
        $this->SetY(-10);
        $this->SetFont('times', '', 8);
        $footer_text = '<p style="'.$page_align_style.'">'.$this->getAliasNumPage().'</p>';
        $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, true, $page_align, true);

		if(MC_FOOTER_TEXT != '') {
            $this->SetY(-20);
            $this->setCellHeightRatio(0.7);
			$footer_text = '<p style="'.$font_style.'">'.MC_FOOTER_TEXT.'</p>';
            $this->writeHTMLCell(0, 0, '' , '', $footer_text, 0, 0, false, true, $footer_align, true);
		}
	}
}

$pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetMargins(PDF_MARGIN_LEFT, (!empty(MC_PDF_LOGO) ? 30 : 5), PDF_MARGIN_RIGHT);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 8);
$pdf->setCellHeightRatio(1);

$html = '';
$html .= '<div style="font-family: '.$body_font.'; font-size: '.$body_size.'; color: '.$body_color.';">';
$html .= '<p style="text-align: center">';
$client_label = !empty(get_client($dbc, $clientid)) ? get_client($dbc, $clientid) : get_contact($dbc, $clientid);
$html .= '<h1>'.$chart_type.($client_label != '-' && !empty($client_label) ? ' - '.$client_label.' - ' : ' - ').date('F Y', strtotime($date)).'</h1>';
$html .= '</p>';

$total_width = 0;
$td_widths = [];
$tables = [];
$current_day = 1;
for($day_i = 1; $day_i <= $days_in_month; $day_i++) {
	$num_comments = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(`customchartcommid`) `num_rows` FROM `custom_charts_comments` WHERE `chart_name` = '$chart_type' AND `clientid` = '$clientid' AND `no_client` = '$no_client' AND `year` = '$year' AND `month` = '$month' AND `day` = '$day_i' AND `deleted` = 0"))['num_rows'];
	$td_widths[$day_i] = $td_width*($num_comments > 0 ? 4 : 1);
	$total_width += $td_widths[$day_i];
	if($total_width > 90) {
		$total_width = 0;
		$tables[] = [$current_day, ($day_i - 1)];
		$current_day = $day_i;
	}
}
$tables[] = [$current_day, $days_in_month];

foreach($tables as $table) {
	$day_start = $table[0];
	$day_end = $table[1];

	$html .= '<table border="1" cellpadding="2">';
	$html .= '<tr style="page-break-inside: avoid;">';
	$html .= '<th style="width: 10%;">'.date('F Y', strtotime($date)).'</th>';

	for($day_i = $day_start; $day_i <= $day_end; $day_i++) {
		$html .= '<th style="width: '.$td_widths[$day_i].'%; text-align: center;">'.$day_i.'</th>';
	}
	$html .= '</tr>';

	$headings = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `field_config_custom_charts` WHERE `deleted` = 0 AND `name` = '$chart_type'"),MYSQLI_ASSOC);
	foreach ($headings as $heading) {
		$html .= '<tr style="page-break-inside: avoid;"><td style="background-color: #CCC;" colspan="'.($days_in_month + 1).'">'.$heading['heading'].'</td></tr>';
		$fields = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `field_config_custom_charts_lines` WHERE `deleted` = 0 AND `headingid` = '".$heading['fieldconfigid']."'"),MYSQLI_ASSOC);
		foreach ($fields as $field) {
			$html .= '<tr style="page-break-inside: avoid;">';
			$html .= '<td style="width: 10%;">'.$field['field'].'</td>';
			for($day_i = $day_start; $day_i <= $day_end; $day_i++) {
				$field_checked = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `custom_charts` WHERE `chart_name` = '$chart_type' AND `clientid` = '$clientid' AND `no_client` = '$no_client' AND `headingid` = '".$heading['fieldconfigid']."' AND `fieldid` = '".$field['fieldconfigid']."' AND `year` = '$year' AND `month` = '$month' AND `day` = '$day_i' AND `deleted` = 0"));
				$initials = '';
				if(!empty($field_checked)) {
					$staff = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `contactid` = '".$field_checked['staffid']."'"));
					$initials = ($staff['initials'] == '' ? ($staff['first_name'].$staff['last_name'] == '' ? $field_checked['staffid'] : substr(decryptIt($staff['first_name']),0,1).substr(decryptIt($staff['last_name']),0,1)) : $staff['initials']);
				}
				$comments = mysqli_query($dbc, "SELECT * FROM `custom_charts_comments` WHERE `chart_name` = '$chart_type' AND `clientid` = '$clientid' AND `no_client` = '$no_client' AND `headingid` = '".$heading['fieldconfigid']."' AND `fieldid` = '".$field['fieldconfigid']."' AND `year` = '$year' AND `month` = '$month' AND `day` = '$day_i' AND `deleted` = 0");
				$comments_html = [];
				while($row = mysqli_fetch_assoc($comments)) {
					$comment_staff = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `contactid` = '".$row['staffid']."'"));
					$comment_initials = ($comment_staff['initials'] == '' ? ($comment_staff['first_name'].$comment_staff['last_name'] == '' ? $row['staffid'] : substr(decryptIt($comment_staff['first_name']),0,1).substr(decryptIt($comment_staff['last_name']),0,1)) : $comment_staff['initials']);
					$comments_html[] = $comment_initials.': '.$row['comment'];
				}
				$comments_html = !empty($comments_html) ? '<p style="text-align: left;">'.implode('<br>', $comments_html).'</p>' : '';
				$html .= '<td style="width: '.$td_widths[$day_i].'%; text-align: center;">'.$initials.$comments_html.'</td>';
			}
			$html .= '</tr>';
		}
	}
	$html .= '</table>';
	$html .= '</div>';
}

echo $html;
$pdf->writeHTML(utf8_encode($html), true, false, true, false, '');

if(!file_exists('download')) {
	mkdir('download', 0777, true);
}

$today_date = date('Y-m-d_H-i-a', time());
$file_name = $chart_type.'_'.$clientid.'_'.$today_date.'.pdf';

$pdf->Output('download/'.$file_name, 'F');
echo '<script type="text/javascript">window.location.replace("download/'.$file_name.'", "_blank");</script>';
?>