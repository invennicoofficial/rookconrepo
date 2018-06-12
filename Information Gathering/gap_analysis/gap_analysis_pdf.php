<?php
function gap_analysis_pdf($dbc,$infogatheringid, $fieldlevelriskid) {

    $form = get_infogathering($dbc, $infogatheringid, 'form');

    include('../Information Gathering/includes/pdf_styling.php');

    $get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pdf_logo FROM field_config_infogathering WHERE form='$form'"));
    DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_infogathering WHERE form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';
	$font = $get_field_config['font'];
	if($font == '' || $font == null)
		$font = 'helvetica';
	$active_color = $get_field_config['active_color'];
	if($active_color == '' || $active_color == null)
		$active_color = '#000000';
	$website = $get_field_config['website'];
	$GLOBALS['website'] = $website;

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM info_gap_analysis WHERE fieldlevelriskid='$fieldlevelriskid'"));
	$today_date = $get_field_level['today_date'];
    $business = $get_field_level['business'];
	$business_1 = $get_field_level['business_1'];
	$business_2 = $get_field_level['business_2'];
	$business_3 = $get_field_level['business_3'];
	$business_4 = $get_field_level['business_4'];

    class MYPDF extends TCPDF {
        //Page header
        public function Header() {
            $this->SetFont('helvetica', '', 10);
            if(PDF_LOGO != '') {
                $image_file = '../Information Gathering/download/'.PDF_LOGO;
                $this->Image($image_file, 10, 5, 0, 25, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
            } else if(HEADER_LOGO != '') {
                $image_file = '../Information Gathering/download/'.HEADER_LOGO;
                $this->Image($image_file, 10, 5, 0, 25, '', '', 'T', false, 300, HEADER_LOGO_ALIGN, false, false, 0, false, false, false);
            }

            if(HEADER_TEXT != '') {
                $this->setCellHeightRatio(0.7);
                $font_style = "font-family: ".HEADER_FONT."; font-style: ".HEADER_FONT_TYPE."; font-size: ".HEADER_FONT_SIZE."; color: ".HEADER_COLOR.";";
                
                $header_align = (HEADER_LOGO_ALIGN == "L" ? "R" : "L");
                if ($header_align == "L") {
                    $align_style = 'text-align: left;';
                } else {
                    $align_style = 'text-align: right;';
                }
                $header_text = '<p style="'.$font_style.$align_style.'">'.HEADER_TEXT.'</p>';
                $this->writeHTMLCell(0, 0, '' , 5, $header_text, 0, 0, false, true, $header_align, true);
            }
        }

        // Page footer
        public function Footer() {
            $font_style = "font-family: ".FOOTER_FONT."; font-style: ".FOOTER_FONT_TYPE."; font-size: ".FOOTER_FONT_SIZE."; color: ".FOOTER_COLOR.";";

            $footer_align = (FOOTER_LOGO_ALIGN == "L" ? "R" : "L");
            if ($footer_align == "L") {
                $align_style = 'text-align: left;';
            } else {
                $align_style = 'text-align: right;';
            }

            // Position at 15 mm from bottom
            $this->SetY(-10);
            $this->SetFont('times', '', 8);
            $footer_text = '<p style="'.$align_style.'">'.$this->getAliasNumPage().'</p>';
            $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, true, $footer_align, true);

            if(FOOTER_TEXT != '') {
                $this->SetY(-15);
                $this->setCellHeightRatio(0.7);
                $footer_text = '<p style="'.$font_style.$align_style.'">'.FOOTER_TEXT.'</p>';
                $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, true, $footer_align, true);
            }

            if(PDF_LOGO != '') {
                $this->SetY(-30);
                $image_file = '../Information Gathering/download/'.PDF_LOGO;
                $this->Image($image_file, 0, 275, 0, 15, '', '', '', '', false, 300, '', false, false, 0, false, false, false);
            } else if(FOOTER_LOGO != '') {
                $this->SetY(-30);
                $image_file = '../Information Gathering/download/'.FOOTER_LOGO;
                $this->Image($image_file, 0, 275, 0, 15, '', '', '', false, 300, FOOTER_LOGO_ALIGN, false, false, 0, false, false, false);
            }
        }
    }

    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


    if(PDF_LOGO != '' || HEADER_LOGO != '') {
        $pdf->SetMargins($margin_left, 30, $margin_right);
    } else {
        $pdf->SetMargins($margin_left, 10, $margin_right);
    }
    
    $pdf->AddPage();
    $pdf->SetFont($font, '', 10);

    $html_weekly = html_css($font_main_heading, $font_main_heading_type, $font_main_heading_size, $main_heading_color, $heading_color, $font_main_body, $font_main_body_type, $font_main_body_size, $main_body_color);

	$html_weekly .= '<h2>GAP Analysis</h2>';

	$html_weekly .= '<table style="padding:3px; border:1px solid '.$active_color.';">
					<tr nobr="true" style="background-color:'.$active_color.'; color:white;  width:22%;">
					<th>Date</th><th>Business</th>';
    if(strpos($form_config, ',Project,')) {
        $html_weekly .= '<th>Project</th>';
    }
    $html_weekly .= '</tr>';
    $html_weekly .= '<tr nobr="true"><td>'.$today_date.'</td><td>'.$business.'</td>';
    if(strpos($form_config, ',Project,')) {
        $project = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `project` WHERE `projectid` = '".$_POST['projectid']."'"));
        $html_weekly .= '<td>'.get_project_label($dbc, $project).'</td>';
    }
    $html_weekly .= '</tr>';
	$html_weekly .= '</table><br>';

    if (strpos($form_config, ','."fields1".',') !== FALSE) {
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Objective/Requirement</td></tr></table><br>'.html_entity_decode($business_1);
    }

    if (strpos($form_config, ','."fields2".',') !== FALSE) {
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Current Standing</td></tr></table><br>'.html_entity_decode($business_2);
    }

    if (strpos($form_config, ','."fields3".',') !== FALSE) {
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Deficiency</td></tr></table><br>'.html_entity_decode($business_3);
    }

    if (strpos($form_config, ','."fields4".',') !== FALSE) {
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Action Plan</td></tr></table><br>'.html_entity_decode($business_4);
    }

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    $pdf->Output('gap_analysis/download/infogathering_'.$fieldlevelriskid.'.pdf', 'F');

    echo '';
}
?>








