<?php
/*
MAR Sheet
*/
include('../include.php');
include('../tcpdf/tcpdf.php');
// error_reporting(E_ALL);
$charts_time_format = get_config($dbc, 'charts_time_format');

$contactid = $_GET['contactid'];
$date = $_GET['date'];

$value_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `bowel_movement` FROM `field_config`"));
$value_config = ','.$value_config['bowel_movement'].',';

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
$html .= '<h1>Bowel Movement - '.get_contact($dbc, $contactid).' - '.date('F Y', strtotime($date)).'</h1>';
$html .= '</p>';

$date_start = date('Y-m-01', strtotime($date));
$date_end = date('Y-m-t', strtotime($date));
$bowel_movement_query = "SELECT * FROM `bowel_movement` WHERE `client` = '$contactid' AND `date` BETWEEN '$date_start' AND '$date_end' AND `deleted` = 0 ORDER BY `date` ASC, IFNULL(STR_TO_DATE(`time`, '%l:%i %p'),STR_TO_DATE(`time`, '%H:%i')) ASC";
$bowel_movement_result = mysqli_fetch_all(mysqli_query($dbc, $bowel_movement_query),MYSQLI_ASSOC);

if(strpos($value_config, ',pdf_days_column,') !== FALSE) {
	$pdf_times = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `field_config_charts_pdf_times` WHERE `chart` = 'bowel_movement' ORDER BY `fieldconfigid`"),MYSQLI_ASSOC);
	$days_in_month = date('t', strtotime($date));
	$day_of_month = 1;
	$day_tables = [];
	$column_num = 0;
	while($day_of_month <= $days_in_month) {
		$start_day = $day_of_month;
		$end_day = ($day_of_month + 6) > $days_in_month ? $days_in_month : ($day_of_month + 6);
		$day_tables[] = [$start_day, $end_day];
		$day_of_month = $end_day + 1;
	}
	foreach($day_tables as $day_table) {
		$start_day = $day_table[0];
		$end_day = $day_table[1];
		$html .= '<table border="1" cellpadding="2">';
		$html .= '<tr style="page-break-inside: avoid;">';
		$html .= '<th><b>Time</b></th>';
		for($i = $start_day; $i <= $end_day; $i++) {
			$html .= '<th><b>'.$i.'</b></th>';
		}
		$html .= '</tr>';
		foreach($pdf_times as $pdf_time) {
			if($charts_time_format == '24h') {
				$time_start = date('H:i', strtotime(date('Y-m-d').' '.$pdf_time['start_time']));
				$time_end = date('H:i', strtotime(date('Y-m-d').' '.$pdf_time['end_time']));
			} else {
				$time_start = date('H:i', strtotime(date('Y-m-d').' '.$pdf_time['start_time']));
				$time_end = date('H:i', strtotime(date('Y-m-d').' '.$pdf_time['end_time']));
			}

			$start_seconds = strtotime(date('Y-m-d').' '.$pdf_time['start_time']);
			$end_seconds = strtotime(date('Y-m-d').' '.$pdf_time['end_time']);
			if($start_seconds > $end_seconds) {
				$end_seconds = strtotime(date('Y-m-d').' '.$pdf_time['end_time'].' + 1 day');
			}

			$html .= '<tr style="page-break-inside: avoid;">';
			$html .= '<td>'.$pdf_time['label'].' ('.$time_start.' - '.$time_end.')'.'</td>';
			for($i = $start_day; $i <= $end_day; $i++) {
				$html .= '<td>';
				foreach($bowel_movement_result as $chart) {
					$bowel_date = $chart['date'];
					$time = $chart['time'];
					if($charts_time_format == '24h') {
						$time = date('H:i', strtotime(date('Y-m-d').' '.$time));
					} else {
						$time = date('h:i a', strtotime(date('Y-m-d').' '.$time));
					}
					$bm = $chart['bm'];
					$size = $chart['size'];
					$form = $chart['form'];
					$note = $chart['note'];
					$staff = $chart['staff'];
					$history = $chart['history'];

					if($bowel_date == date('Y-m-'.sprintf('%02d',$i), strtotime($date)) && strtotime(date('Y-m-d').' '.$time) >= $start_seconds && strtotime(date('Y-m-d').' '.$time) <= $end_seconds) {
						if (strpos($value_config, ','."time".',') !== FALSE) {
							$html .= 'Time: '.$time.'<br>';
						}
						if (strpos($value_config, ','."bm".',') !== FALSE) {
							$html .= 'BM: '.$bm.'<br>';
						}
						if (strpos($value_config, ','."size".',') !== FALSE) {
							$html .= 'Size: '.$size.'<br>';
						}
						if (strpos($value_config, ','."form".',') !== FALSE) {
							$html .= 'Form: '.$form.'<br>';
						}
						if (strpos($value_config, ','."note".',') !== FALSE) {
							$html .= 'Note: '.strip_tags(html_entity_decode($note)).'<br>';
						}
						if (strpos($value_config, ','."staff".',') !== FALSE) {
							$html .= 'Staff: '.get_contact($dbc, $staff).'<br>';
						}
						if (strpos($value_config, ','."history".',') !== FALSE) {
							$html .= 'History: '.strip_tags(html_entity_decode($history)).'<br>';
						}
						$html .= '<hr>';
					}
				}
				$html .= '</td>';
			}
			$html .= '</tr>';
		}
		$html .= '</table><p></p>';
	}
} else {
	$html .= '<table border="1" cellpadding="2">';
	$html .= '<tr>';
	$html .= '<th><b>Date</b></th>';
	if (strpos($value_config, ','."time".',') !== FALSE) {
		$html .= '<th><b>Time</b></th>';
	}
	if (strpos($value_config, ','."bm".',') !== FALSE) {
		$html .= '<th><b>BM</b></th>';
	}
	if (strpos($value_config, ','."bm".',') !== FALSE) {
		$html .= '<th><b>Size</b></th>';
	}
	if (strpos($value_config, ','."form".',') !== FALSE) {
		$html .= '<th><b>Form</b></th>';
	}
	if (strpos($value_config, ','."note".',') !== FALSE) {
		$html .= '<th><b>Note</b></th>';
	}
	if (strpos($value_config, ','."staff".',') !== FALSE) {
		$html .= '<th><b>Staff</b></th>';
	}
	if (strpos($value_config, ','."history".',') !== FALSE) {
		$html .= '<th><b>History</b></th>';
	}
	$html .= '</tr>';

	foreach ($bowel_movement_result as $chart) {
		$bowel_movement_id = $chart['bowel_movement_id'];
		$bowel_date = $chart['date'];
		$time = $chart['time'];
		if($charts_time_format == '24h') {
			$time = date('H:i', strtotime(date('Y-m-d').' '.$time));
		} else {
			$time = date('h:i a', strtotime(date('Y-m-d').' '.$time));
		}
		$bm = $chart['bm'];
		$size = $chart['size'];
		$form = $chart['form'];
		$note = $chart['note'];
		$staff = $chart['staff'];
		$history = $chart['history'];

		$html .= '<tr>';
		$html .= '<td>'.$bowel_date.'</td>';
		if (strpos($value_config, ','."time".',') !== FALSE) {
			$html .= '<td>'.$time.'</td>';
		}
		if (strpos($value_config, ','."bm".',') !== FALSE) {
			$html .= '<td>'.$bm.'</td>';
		}
		if (strpos($value_config, ','."size".',') !== FALSE) {
			$html .= '<td>'.$size.'</td>';
		}
		if (strpos($value_config, ','."form".',') !== FALSE) {
			$html .= '<td>'.$form.'</td>';
		}
		if (strpos($value_config, ','."note".',') !== FALSE) {
			$html .= '<td>'.html_entity_decode($note).'</td>';
		}
		if (strpos($value_config, ','."staff".',') !== FALSE) {
			$html .= '<td>'.get_contact($dbc, $staff).'</td>';
		}
		if (strpos($value_config, ','."history".',') !== FALSE) {
			$html .= '<td>'.html_entity_decode($history).'</td>';
		}
		$html .= '</tr>';
	}
	$html .= '</table>';
}
$html .= '</div>';
$pdf->writeHTML(utf8_encode($html), true, false, true, false, '');

if(!file_exists('download')) {
	mkdir('download', 0777, true);
}

$today_date = date('Y-m-d_H-i-a', time());
$file_name = 'bowel_movement_'.$contactid.'_'.$today_date.'.pdf';

$pdf->Output('download/'.$file_name, 'F');
echo '<script type="text/javascript">window.location.replace("download/'.$file_name.'", "_blank");</script>';
?>