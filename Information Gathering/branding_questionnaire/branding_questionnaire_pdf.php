<?php
function branding_questionnaire_pdf($dbc,$infogatheringid, $fieldlevelriskid) {

    $form = get_infogathering($dbc, $infogatheringid, 'form');

    include('../Information Gathering/includes/pdf_styling.php');

    $get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pdf_logo FROM field_config_infogathering WHERE form='$form'"));
    DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_infogathering WHERE form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';
	$font = $get_field_config['font'];
	if($font == '' || $font == null)
		$font = $font;
	$active_color = $get_field_config['active_color'];
	if($active_color == '' || $active_color == null)
		$active_color = '#000000';
	$website = $get_field_config['website'];
	$GLOBALS['website'] = $website;

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM info_branding_questionnaire WHERE fieldlevelriskid='$fieldlevelriskid'"));
	$today_date = $get_field_level['today_date'];
	$client_name = $get_field_level['client_name'];
	$phone_number = $get_field_level['phone_number'];
	$client_email = $get_field_level['client_email'];
	$gen_info_1 = $get_field_level['gen_info_1'];
	$gen_info_2 = $get_field_level['gen_info_2'];
	$gen_info_3 = $get_field_level['gen_info_3'];
	$gen_info_4 = $get_field_level['gen_info_4'];
	$gen_info_5 = $get_field_level['gen_info_5'];
	$gen_info_6 = $get_field_level['gen_info_6'];
	$gen_info_7 = $get_field_level['gen_info_7'];
	$your_market_1 = $get_field_level['your_market_1'];
	$your_market_2 = $get_field_level['your_market_2'];
	$your_market_3 = $get_field_level['your_market_3'];
	$your_market_4 = $get_field_level['your_market_4'];
	$your_market_5 = $get_field_level['your_market_5'];
	$your_market_6 = $get_field_level['your_market_6'];
	$your_market_7 = $get_field_level['your_market_7'];
	$identity_brand_1 = $get_field_level['identity_brand_1'];
	$identity_brand_2 = $get_field_level['identity_brand_2'];
	$identity_brand_3 = $get_field_level['identity_brand_3'];
	$identity_brand_4 = $get_field_level['identity_brand_4'];
	$identity_brand_5 = $get_field_level['identity_brand_5'];

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

	$html_weekly .= '<h2>Branding Questionnaire</h2>';

	$html_weekly .= '<table style="padding:3px; border:1px solid '.$active_color.';">
					<tr nobr="true" style="background-color:'.$active_color.'; color:white;  width:22%;">
					<th>Date</th><th>Business</th><th>Phone Number</th><th>Email</th>';
    if(strpos($form_config, ',Project,')) {
        $html_weekly .= '<th>Project</th>';
    }
    $html_weekly .= '</tr>';
    $html_weekly .= '<tr nobr="true"><td>'.$today_date.'</td><td>'.$client_name.'</td> <td>'.$phone_number.'</td> <td>'.$client_email.'</td>';
    if(strpos($form_config, ',Project,')) {
        $project = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `project` WHERE `projectid` = '".$_POST['projectid']."'"));
        $html_weekly .= '<td>'.get_project_label($dbc, $project).'</td>';
    }
    $html_weekly .= '</tr>';
    $html_weekly .= '</table>';

    $html_weekly .= "<br><p>It is important for our creative team to have as much information as possible and an in depth understanding of your company to have the basis for creating a great logo and striking identity to strengthen your brand. A good brand communicates a clear message about your company; what  is  stands  for  and  how  it  differs  from  competitors.  The  logo  is  the  visual  marker  of  the brand, and needs to instantly reflect the same information.</p><br>";

	$html_weekly .= "<br><p>A great logo design begins with insightful information about your company. Please answer the questions below  and  feel  free  to  add  additional  information  that  you  feel  is  pertinent  to  your company. The more thorough you can be now, the more creative and effective the results.</p><br>";

	$html_weekly .='<h3>General Information</h3>';

    if (strpos($form_config, ','."fields5".',') !== FALSE) {
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>What products/services does your business provide?</td></tr></table><br>'.html_entity_decode($gen_info_1);
    }

    if (strpos($form_config, ','."fields6".',') !== FALSE) {
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Please describe your business in one sentence.</td></tr></table><br>'.html_entity_decode($gen_info_2);
    }

    if (strpos($form_config, ','."fields7".',') !== FALSE) {
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>What  taglines/slogans/keywords  are  associated  with  your  business?  What  would  you like those keywords to be (if different than what they currently are)?:</td></tr></table><br>'.html_entity_decode($gen_info_3);
    }

    if (strpos($form_config, ','."fields8".',') !== FALSE) {
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>How did your company get its start? Is there a unique story?</td></tr></table><br>'.html_entity_decode($gen_info_4);
    }

    if (strpos($form_config, ','."fields9".',') !== FALSE) {
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>What shapes/symbols represent your industry/company? Do you feel that any of these are overused?</td></tr></table><br>'.html_entity_decode($gen_info_5);
    }

    if (strpos($form_config, ','."fields10".',') !== FALSE) {
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>What would a typical customer Google to find your business?</td></tr></table><br>'.html_entity_decode($gen_info_6);
    }

    if (strpos($form_config, ','."fields11".',') !== FALSE) {
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Will  the  new  brand  we  are  creating  be  in  any  way  connected  to  another  brand  or business?</td></tr></table><br>'.html_entity_decode($gen_info_7);
    }

	$html_weekly .='<h3>Your Market</h3>';

    if (strpos($form_config, ','."fields12".',') !== FALSE) {
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Who would you consider your ideal customer to be?  Why?</td></tr></table><br>'.html_entity_decode($your_market_1);
    }

    if (strpos($form_config, ','."fields13".',') !== FALSE) {
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>How does the market see your company today? How would you like it to be viewed in the future?</td></tr></table><br>'.html_entity_decode($your_market_2);
    }

    if (strpos($form_config, ','."fields14".',') !== FALSE) {
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>What aspects of your image need improvement?</td></tr></table><br>'.html_entity_decode($your_market_3);
    }

	if (strpos($form_config, ','."fields15".',') !== FALSE) {
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Who are your main competitors?</td></tr></table><br>'.html_entity_decode($your_market_4);
    }

	if (strpos($form_config, ','."fields16".',') !== FALSE) {
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>How are their products/services better or worse?:</td></tr></table><br>'.html_entity_decode($your_market_5);
    }

	if (strpos($form_config, ','."fields17".',') !== FALSE) {
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>What competitive edge does your business have?</td></tr></table><br>'.html_entity_decode($your_market_6);
    }

	if (strpos($form_config, ','."fields18".',') !== FALSE) {
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>How have you been generating business recently? What has worked best? What hasn&#39;t? Any idea why?</td></tr></table><br>'.html_entity_decode($your_market_7);
    }

	$html_weekly .='<h3>Identity & Brand</h3>';

	if (strpos($form_config, ','."fields19".',') !== FALSE) {
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Describe your current brand/logo (if relevant). Where do you believe it is failing?:</td></tr></table><br>'.html_entity_decode($identity_brand_1);
    }

	if (strpos($form_config, ','."fields20".',') !== FALSE) {
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>What impression would you like customers to get?</td></tr></table><br>'.html_entity_decode($identity_brand_2);
    }

	if (strpos($form_config, ','."fields21".',') !== FALSE) {
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>What brands in todays market are you most impressed by and why?</td></tr></table><br>'.html_entity_decode($identity_brand_3);
    }

	if (strpos($form_config, ','."fields22".',') !== FALSE) {
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Which colours represent your industry or area of business (if relevant)?</td></tr></table><br>'.html_entity_decode($identity_brand_4);
    }

	if (strpos($form_config, ','."fields23".',') !== FALSE) {
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Please use this space to list any additional information about your business, products, services or target markets that may be useful</td></tr></table><br>'.html_entity_decode($identity_brand_5);
    }

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    $pdf->Output('branding_questionnaire/download/infogathering_'.$fieldlevelriskid.'.pdf', 'F');

    echo '';
}
?>








