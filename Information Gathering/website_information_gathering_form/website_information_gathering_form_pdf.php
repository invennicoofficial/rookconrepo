<?php
function website_information_gathering_form_pdf($dbc,$infogatheringid, $fieldlevelriskid) {

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

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM info_website_information_gathering_form WHERE fieldlevelriskid='$fieldlevelriskid'"));
	$today_date = $get_field_level['today_date'];
	$client_name = $get_field_level['client_name'];
	$phone_num = $get_field_level['phone_num'];
	$email = $get_field_level['email'];
	$work_completed = $get_field_level['work_completed'];
	$project_details = $get_field_level['project_details'];
	$branding_1 = $get_field_level['branding_1'];
	$branding_2 = $get_field_level['branding_2'];
	$branding_3 = $get_field_level['branding_3'];
	$branding_4 = $get_field_level['branding_4'];
	$hosting_email_1 = $get_field_level['hosting_email_1'];
	$hosting_email_2 = $get_field_level['hosting_email_2'];
	$hosting_email_3 = $get_field_level['hosting_email_3'];
	$hosting_email_4 = $get_field_level['hosting_email_4'];
	$hosting_email_5 = $get_field_level['hosting_email_5'];
	$hosting_email_6 = $get_field_level['hosting_email_6'];
	$hosting_email_7 = $get_field_level['hosting_email_7'];
	$hosting_email_8 = $get_field_level['hosting_email_8'];
	$hosting_email_9 = $get_field_level['hosting_email_9'];
	$hosting_email_10 = $get_field_level['hosting_email_10'];
	$hosting_email_11 = $get_field_level['hosting_email_11'];
	$hosting_email_12 = $get_field_level['hosting_email_12'];
	$hosting_email_13 = $get_field_level['hosting_email_13'];
	$hosting_email_14 = $get_field_level['hosting_email_14'];
	$web_development_1 = $get_field_level['web_development_1'];
	$web_development_2 = $get_field_level['web_development_2'];
	$web_development_3 = $get_field_level['web_development_3'];
	$web_development_4 = $get_field_level['web_development_4'];
	$web_development_5 = $get_field_level['web_development_5'];
	$web_development_6 = $get_field_level['web_development_6'];
	$web_development_7 = $get_field_level['web_development_7'];
	$web_development_8 = $get_field_level['web_development_8'];
	$web_development_9 = $get_field_level['web_development_9'];
	$web_development_10 = $get_field_level['web_development_10'];
	$web_development_11 = $get_field_level['web_development_'];
	$web_development_12 = $get_field_level['web_development_12'];
	$web_development_13 = $get_field_level['web_development_13'];
	$web_development_14 = $get_field_level['web_development_14'];
	$web_development_15 = $get_field_level['web_development_15'];
	$web_development_16 = $get_field_level['web_development_16'];
	$web_development_17 = $get_field_level['web_development_17'];
	$web_development_18 = $get_field_level['web_development_18'];
	$web_development_19 = $get_field_level['web_development_19'];
	$web_development_20 = $get_field_level['web_development_20'];
	$web_development_21 = $get_field_level['web_development_21'];
	$web_development_22 = $get_field_level['web_development_22'];
	$web_development_23 = $get_field_level['web_development_23'];
	$web_development_24 = $get_field_level['web_development_24'];
	$web_development_25 = $get_field_level['web_development_25'];
	$web_development_26 = $get_field_level['web_development_26'];
	$web_development_27 = $get_field_level['web_development_27'];
	$notes_comments = $get_field_level['notes_comments'];

    $project_details_1 = $get_field_level['project_details_1'];
    $branding_5 = $get_field_level['branding_5'];
    $landing_1 = $get_field_level['landing_1'];
    $web_development_28 = $get_field_level['web_development_28'];
    $web_development_29 = $get_field_level['web_development_29'];
    $web_development_30 = $get_field_level['web_development_30'];
    $web_development_31 = $get_field_level['web_development_31'];
    $web_development_32 = $get_field_level['web_development_32'];
    $web_development_33 = $get_field_level['web_development_33'];
    $web_development_34 = $get_field_level['web_development_34'];
    $web_development_35 = $get_field_level['web_development_35'];
    $web_development_36 = $get_field_level['web_development_36'];
    $web_development_37 = $get_field_level['web_development_37'];
    $web_development_38 = $get_field_level['web_development_38'];

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

	$html_weekly .= '<h2>Website Information Gathering Form</h2>';

	$html_weekly .= '<table style="padding:3px; border:1px solid '.$active_color.';">
					<tr nobr="true" style="background-color:'.$active_color.'; color:black;  width:22%;">
					<th>Date</th><th>Business</th><th>Phone Number</th>';
    if(strpos($form_config, ',Project,')) {
        $html_weekly .= '<th>Project</th>';
    }
    $html_weekly .= '</tr>';
    $html_weekly .= '<tr nobr="true"><td>'.$today_date.'</td><td>'.$client_name.'</td><td>'.$phone_num.'</td>';
    if(strpos($form_config, ',Project,')) {
        $project = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `project` WHERE `projectid` = '".$_POST['projectid']."'"));
        $html_weekly .= '<td>'.get_project_label($dbc, $project).'</td>';
    }
    $html_weekly .= '</tr>';
	$html_weekly .= '</table><br>';

	$html_weekly .= '<table style="padding:3px; border:1px solid '.$active_color.';">
					<tr nobr="true" style="background-color:'.$active_color.'; color:black;  width:22%;">
					<th>Email</th><th>Individual/Team to approve work completed</th></tr>';
    $html_weekly .= '<tr nobr="true"><td>'.$email.'</td><td>'.$work_completed.'</td></tr>';
	$html_weekly .= '</table>';

	$html_weekly .= "<br><p>*Confirm that information above is for the main point of contact for the project</p><br>";

    if (strpos($form_config, ','."fields5".',') !== FALSE) {
	$html_weekly .='<h3>Website Project Details</h3>';
	$html_weekly .= "<br>".$project_details;
    $html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Do you have a coding language preference for the website?</td></tr></table><br>'.html_entity_decode($project_details_1);
    }

    if (strpos($form_config, ','."fields6".',') !== FALSE) {
	$html_weekly .='<h3>Branding</h3>';
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Is new business branding required?</td></tr></table><br>'.$branding_1;
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Would you like FFM to update or modernize your brand?</td></tr></table><br>'.$branding_2;
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Do you have a brand standard for your business that FFM must adhere to?</td></tr></table><br>'.$branding_3;
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>How can FFM acquire your current logo and branding elements? FFM will provide a specific list of our needs</td></tr></table><br>'.html_entity_decode($branding_4);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>What tone and level of sophistication do you believe would be best for your online visitors?</td></tr></table><br>'.html_entity_decode($branding_5);
    }

	$html_weekly .= "<br><p>*If any level of business branding is required, FFM branding information gathering must be completed. Please note that web development time will not begin until all branding has been completed and approved.</p>";

    if (strpos($form_config, ','."fields7".',') !== FALSE) {
	$html_weekly .='<h3>Hosting & Email Packages</h3>';
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Do you currently own the domain(s)/URL you wish the website to get posted to?</td></tr></table><br>'.$hosting_email_1;
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>If so list all domains/URLs</td></tr></table><br>'.html_entity_decode($hosting_email_2);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>How can FFM gain access to the domain(s)/URL</td></tr></table><br>'.html_entity_decode($hosting_email_3);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Do you need FFM to purchase domains/URLs on your behalf?</td></tr></table><br>'.$hosting_email_4;
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>If so what domains/URLS</td></tr></table><br>'.html_entity_decode($hosting_email_5);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Do you currently have or will you be hosting your website through a third party?</td></tr></table><br>'.$hosting_email_6;
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>If so will you be posting the website once complete or will FFM?</td></tr></table><br>'.$hosting_email_7;
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>How can FFM gain access to your hosting</td></tr></table><br>'.html_entity_decode($hosting_email_8);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Do you have any other sub domains, sub folders, micro sites or software applications that could be affected by FFM uploading a new website on your behalf?</td></tr></table><br>'.$hosting_email_9;
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Would you like FFM to setup and manage your hosting?</td></tr></table><br>'.$hosting_email_10;
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Would you like FFM to setup and manage your email(s)</td></tr></table><br>'.$hosting_email_11;
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Will FFM uploading a new website affect your email in anyway?</td></tr></table><br>'.$hosting_email_12;
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Would you like to use a forwarding email service?</td></tr></table><br>'.$hosting_email_13;
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Would you like to use Google Apps?</td></tr></table><br>'.$hosting_email_14;
    }

    if (strpos($form_config, ','."fields8".',') !== FALSE) {

	$html_weekly .='<h3>Temporary Landing Page</h3>';
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Do you require an under construction or coming soon landing page?</td></tr></table><br>'.$web_development_1;


	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>What details would you like on this landing page?</td></tr></table><br>'.html_entity_decode($landing_1);


    $html_weekly .='<h3>Website Strategy</h3>';
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Do you have a web strategy in mind for your online presence?</td></tr></table><br>'.html_entity_decode($web_development_2);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>What do you want your website to do and what do you want from your website?</td></tr></table><br>'.html_entity_decode($web_development_3);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>What would be the best thing a customer or potential customer could get out of your website?</td></tr></table><br>'.html_entity_decode($web_development_4);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>What would be the best thing your staff could get out of your website?</td></tr></table><br>'.html_entity_decode($web_development_5);

	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Will your website take or store and any customer information?</td></tr></table><br>'.html_entity_decode($web_development_6);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Will your website take or store any marketing information for distribution?</td></tr></table><br>'.html_entity_decode($web_development_7);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Do you have any competitor websites that you want FFM to review?</td></tr></table><br>'.html_entity_decode($web_development_12);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Do you have any infographics are process outlines you want outlined on your website?</td></tr></table><br>'.html_entity_decode($web_development_28);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Ideally, websites match your sales process, they talk and walk you through the same details and same process a person would meeting one to one, how do you see this fitting into your strategy?</td></tr></table><br>'.html_entity_decode($web_development_29);


	$html_weekly .='<h3>Website Design Details</h3>';
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Will you be providing designs to be built or will FFM be providing design options?</td></tr></table><br>'.html_entity_decode($web_development_9);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Do you have specific website options you want to ensure are built into your designs?</td></tr></table><br>'.html_entity_decode($web_development_10);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Do you have any specific websites that you really like?</td></tr></table><br>'.html_entity_decode($web_development_11);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>FFM websites automatically respond based on the device the website is being viewed on. Is there additional functionality or reduced functionality required for tablet and mobile versions of your website?</td></tr></table><br>'.html_entity_decode($web_development_14);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>What colors do you specifically like and don\'t like that you&#39;d want to see incorporated into the website?</td></tr></table><br>'.html_entity_decode($web_development_30);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Do you have any design specifics you&#39;d like our designers to know when building your website?</td></tr></table><br>'.html_entity_decode($web_development_31);

	$html_weekly .='<h3>Website Content</h3>';
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>How many pages does your website need to have and what would those pages be called?</td></tr></table><br>'.html_entity_decode($web_development_13);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Will copy/content need to be created and SEO optimized by FFM?</td></tr></table><br>'.html_entity_decode($web_development_15);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Will you be providing final format content, SEO optimized and ready for publish?</td></tr></table><br>'.html_entity_decode($web_development_16);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Ideally, FFM content interviews are done with the specific individuals who have working knowledge of each page. Who might be best for us to contact for each page of your website?</td></tr></table><br>'.html_entity_decode($web_development_32);

	$html_weekly .='<h3>Website Images</h3>';
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Will FFM be sourcing and editing images on your behalf for the website?</td></tr></table><br>'.html_entity_decode($web_development_17);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Will FFM be editing and reformatting images provided by you?</td></tr></table><br>'.html_entity_decode($web_development_18);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Will you be providing images ready for publish to FFM?</td></tr></table><br>'.html_entity_decode($web_development_19);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>What type of imagery do you think best represents your business?</td></tr></table><br>'.html_entity_decode($web_development_33);

	$html_weekly .='<h3>Website Search Engine Optimization</h3>';
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>What Keywords would potential customers/clients use to find your website on Google?</td></tr></table><br>'.html_entity_decode($web_development_34);

	$html_weekly .='<h3>Website Social Media</h3>';
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Will FFM be interlinking any social media pages to your website, if so which ones?</td></tr></table><br>'.html_entity_decode($web_development_20);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Will FFM be updating or creating any social media pages on your behalf, if so which ones?</td></tr></table><br>'.html_entity_decode($web_development_21);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>What tone and messaging are you currently using or would you like to use for your social media?</td></tr></table><br>'.html_entity_decode($web_development_35);

	$html_weekly .='<h3>Analytics</h3>';
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Would you like to receive Google Analytics on a monthly basis from FFM?</td></tr></table><br>'.html_entity_decode($web_development_26);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Do you require quarterly meetings to review online strategies?</td></tr></table><br>'.html_entity_decode($web_development_36);

	$html_weekly .='<h3>Website Back End Development</h3>';
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Will your website store any leads for your sales team(if so where will the leads be stored, CRM details required)?</td></tr></table><br>'.html_entity_decode($web_development_8);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Would you like to have the ability to edit and update your website on your own?</td></tr></table><br>'.html_entity_decode($web_development_22);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Will you be running any payment information through your website?</td></tr></table><br>'.html_entity_decode($web_development_23);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Does your website need to connect to any third party or inhouse software applications? If so, where can FFM get all the details required to limit any potential downtime?</td></tr></table><br>'.html_entity_decode($web_development_24);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Do you currently use or need a Customer Relationship Manager (CRM) to manage your clients ongoing needs?</td></tr></table><br>'.html_entity_decode($web_development_37);

	$html_weekly .='<h3>Support Plans</h3>';
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Will you be looking for FFM to support and maintain your website through a support plan?</td></tr></table><br>'.html_entity_decode($web_development_27);
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>What level of support best represents your specific business needs?</td></tr></table><br>'.html_entity_decode($web_development_38);

    }

    if (strpos($form_config, ','."fields9".',') !== FALSE) {
	$html_weekly .= '<br><br><table><tr style="color:'.$active_color.'"><td>Additional Notes & Comments</td></tr></table><br>'.html_entity_decode($notes_comments);
    }

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    $pdf->Output('website_information_gathering_form/download/infogathering_'.$fieldlevelriskid.'.pdf', 'F');

    echo '';
}
?>








