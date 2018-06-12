<?php
function client_business_introduction_pdf($dbc,$infogatheringid, $fieldlevelriskid) {

    $form = get_infogathering($dbc, $infogatheringid, 'form');

    include('../Information Gathering/includes/pdf_styling.php');

    $get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pdf_logo FROM field_config_infogathering WHERE form='$form'"));
    DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_infogathering WHERE form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM info_client_business_introduction WHERE fieldlevelriskid='$fieldlevelriskid'"));
	$today_date = $get_field_level['today_date'];
	$business_name = $get_field_level['business_name'];
	$business_services = $get_field_level['business_services'];
	$business_products = $get_field_level['business_products'];
    $business_culture = $get_field_level['business_culture'];
	$business_vision = $get_field_level['business_vision'];
	$business_goals = $get_field_level['business_goals'];
    $business_tone = $get_field_level['business_tone'];
	$target_markets = $get_field_level['target_markets'];
	$competitors = $get_field_level['competitors'];
	$current_areas_of_concern = $get_field_level['current_areas_of_concern'];
	$estimated_project_timeline_budget = $get_field_level['estimated_project_timeline_budget'];
	$communication_expectations_methods = $get_field_level['communication_expectations_methods'];

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

	$html_weekly .= '<h2>Customer Business Introduction</h2>';

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">
					<tr nobr="true" style="background-color:lightgrey; color:black;  width:22%;">
					<th>Date</th><th>Business</th>';
    if(strpos($form_config, ',Project,') !== FALSE) {
        $html_weekly .= '<th>Project</th>';
    }
    $html_weekly .= '</tr>';
    $html_weekly .= '<tr nobr="true"><td>'.$today_date.'</td><td>'.$business_name.'</td>';
    if(strpos($form_config, ',Project,') !== FALSE) {
        $project = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `project` WHERE `projectid` = '".$_POST['projectid']."'"));
        $html_weekly .= '<td>'.get_project_label($dbc, $project).'</td>';
    }
    $html_weekly .= '</tr>';
	$html_weekly .= '</table><br>';

    if (strpos($form_config, ','."fields3".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Business Services:</td></tr></table><br>".html_entity_decode($business_services);
    }

    if (strpos($form_config, ','."fields4".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Business Products:</td></tr></table><br>".html_entity_decode($business_products);
    }

    if (strpos($form_config, ','."fields12".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Business Culture:</td></tr></table><br>".html_entity_decode($business_culture);
    }

    if (strpos($form_config, ','."fields5".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Business Vision:</td></tr></table><br>".html_entity_decode($business_vision);
    }

    if (strpos($form_config, ','."fields6".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Business Goals:</td></tr></table><br>".html_entity_decode($business_goals);
    }

    if (strpos($form_config, ','."fields13".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Business Tone:</td></tr></table><br>".html_entity_decode($business_tone);
    }

    if (strpos($form_config, ','."fields7".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Target Markets:</td></tr></table><br>".html_entity_decode($target_markets);
    }

    if (strpos($form_config, ','."fields8".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Competitors:</td></tr></table><br>".html_entity_decode($competitors);
    }

    if (strpos($form_config, ','."fields9".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Current Areas of Concern:</td></tr></table><br>".html_entity_decode($current_areas_of_concern);
    }

    if (strpos($form_config, ','."fields10".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Estimated Project Timeline & Budget:</td></tr></table><br>".html_entity_decode($estimated_project_timeline_budget);
    }

    if (strpos($form_config, ','."fields11".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Communication Expectations & Methods:</td></tr></table><br>".html_entity_decode($communication_expectations_methods);
    }

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    $pdf->Output('client_business_introduction/download/infogathering_'.$fieldlevelriskid.'.pdf', 'F');

    echo '';
}
?>








