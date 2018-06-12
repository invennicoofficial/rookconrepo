<?php
function social_media_info_gathering_pdf($dbc,$infogatheringid, $fieldlevelriskid) {

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

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM info_social_media_info_gathering WHERE fieldlevelriskid='$fieldlevelriskid'"));
	$today_date = $get_field_level['today_date'];
    $business = $get_field_level['business'];
	$big_picture = $get_field_level['big_picture'];
	$goal = $get_field_level['goal'];
	$culture = $get_field_level['culture'];
	$community = $get_field_level['community'];
	$conversation = $get_field_level['conversation'];
	$location = $get_field_level['location'];
	$age_range = $get_field_level['age_range'];
	$gender = $get_field_level['gender'];
	$language1 = $get_field_level['language1'];
	$interests = $get_field_level['interests'];
	$character_persona = $get_field_level['character_persona'];
	$tone = $get_field_level['tone'];
	$language = $get_field_level['language'];
	$purpose = $get_field_level['purpose'];
	$features_product_s_e = $get_field_level['features_product_s_e'];
	$channels = $get_field_level['channels'];
	$research = $get_field_level['research'];
	$repurposing = $get_field_level['repurposing'];
	$writing = $get_field_level['writing'];
	$promotion = $get_field_level['promotion'];
	$creative = $get_field_level['creative'];
	$quality_assurance = $get_field_level['quality_assurance'];

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

	$html_weekly .= '<h2>Social Media Info Gathering</h2>';

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
	$html_weekly .='<h3>Big Picture</h3>';
	$html_weekly .= '<br><table><tr style="color:'.$active_color.'"><td>What do you hope to gain as a company from social media?</td></tr></table><br>'.html_entity_decode($big_picture);
    }

    if (strpos($form_config, ','."fields2".',') !== FALSE) {
	$html_weekly .='<h3>Goal</h3>';
	$html_weekly .= '<br><table><tr style="color:'.$active_color.'"><td>What is your objective?</td></tr></table><br>'.$goal;
    }

    if (strpos($form_config, ','."fields3".',') !== FALSE) {
	$html_weekly .='<h3>Culture</h3>';
	$html_weekly .= '<br><table><tr style="color:'.$active_color.'"><td>What does your company stand for? What makes you stand out from all the others who are after the same audience?</td></tr></table><br>'.html_entity_decode($culture);
    }

    if (strpos($form_config, ','."fields4".',') !== FALSE) {
	$html_weekly .='<h3>Community</h3>';
	$html_weekly .= '<br><table><tr style="color:'.$active_color.'"><td>What are the problems & concerns of your target market? How do they express this? What do they want from you?</td></tr></table><br>'.html_entity_decode($community);
    }

    if (strpos($form_config, ','."fields5".',') !== FALSE) {
	$html_weekly .='<h3>Conversation</h3>';
	$html_weekly .= '<br><table><tr style="color:'.$active_color.'"><td>What do you want to add to the conversation? (E.g. - Customer support, industry education, product promotions, general fun.)</td></tr></table><br>'.$conversation;
    }

    if (strpos($form_config, ','."fields6".',') !== FALSE) {
	$html_weekly .='<h3>Audience</h3>';
	$html_weekly .= "<h4>Who are you trying to connect to?</h4>";
	$html_weekly .= '<br><table><tr style="color:'.$active_color.'"><td>Location</td></tr></table><br>'.$location;
	$html_weekly .= '<br><table><tr style="color:'.$active_color.'"><td>Age Range</td></tr></table><br>'.$age_range;
	$html_weekly .= '<br><table><tr style="color:'.$active_color.'"><td>Gender</td></tr></table><br>'.$gender;
	$html_weekly .= '<br><table><tr style="color:'.$active_color.'"><td>Language</td></tr></table><br>'.$language;
	$html_weekly .= '<br><table><tr style="color:'.$active_color.'"><td>Interests</td></tr></table><br>'.$interests;
    }

    if (strpos($form_config, ','."fields7".',') !== FALSE) {
	$html_weekly .='<h3>Character/Persona</h3>';
	$html_weekly .= '<br><table><tr style="color:'.$active_color.'"><td>Who does your brand sound like? Create an identity with specific attributes that fit who you want to sound like online.</td></tr></table><br>'.$character_persona;
    }

    if (strpos($form_config, ','."fields8".',') !== FALSE) {
	$html_weekly .='<h3>Tone</h3>';
	$html_weekly .= '<br><table><tr style="color:'.$active_color.'"><td>What is the general vibe of your brand?</td></tr></table><br>'.$tone;
    }

    if (strpos($form_config, ','."fields9".',') !== FALSE) {
	$html_weekly .='<h3>Language</h3>';
	$html_weekly .= '<br><table><tr style="color:'.$active_color.'"><td>What kind of words do you use in your social media conversations?</td></tr></table><br>'.$language;
    }

    if (strpos($form_config, ','."fields10".',') !== FALSE) {
	$html_weekly .='<h3>Purpose</h3>';
	$html_weekly .= '<br><table><tr style="color:'.$active_color.'"><td>Why are you on social media in the first place?</td></tr></table><br>'.$purpose;
    }

	if (strpos($form_config, ','."fields11".',') !== FALSE) {
	$html_weekly .='<h3>Featured Product, Service or Event</h3>';
	$html_weekly .= '<br><table><tr style="color:'.$active_color.'"><td>What is happening this month that we should make specific mention of?</td></tr></table><br>'.html_entity_decode($features_product_s_e);
    }

	if (strpos($form_config, ','."fields12".',') !== FALSE) {
	$html_weekly .='<h3>Channels</h3>';
	$html_weekly .= '<br><table><tr style="color:'.$active_color.'"><td>What mediums will we be managing?</td></tr></table><br>'.$channels;
    }

	if (strpos($form_config, ','."fields13".',') !== FALSE) {
	$html_weekly .='<h3>Resources</h3>';
	$html_weekly .= "<h4>Who is accountable for this blog strategy?</h4>";
	$html_weekly .= '<br><table><tr style="color:'.$active_color.'"><td>Research</td></tr></table><br>'.$research;
	$html_weekly .= '<br><table><tr style="color:'.$active_color.'"><td>Repurposing</td></tr></table><br>'.$repurposing;
	$html_weekly .= '<br><table><tr style="color:'.$active_color.'"><td>Writing</td></tr></table><br>'.$writing;
	$html_weekly .= '<br><table><tr style="color:'.$active_color.'"><td>Promotion</td></tr></table><br>'.$promotion;
	$html_weekly .= '<br><table><tr style="color:'.$active_color.'"><td>Creative</td></tr></table><br>'.$creative;
	$html_weekly .= '<br><table><tr style="color:'.$active_color.'"><td>Quality Assurance</td></tr></table><br>'.$quality_assurance;
    }

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    $pdf->Output('social_media_info_gathering/download/infogathering_'.$fieldlevelriskid.'.pdf', 'F');

    echo '';
}
?>








