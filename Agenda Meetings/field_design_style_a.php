<?php $style = 'a';
$file_name = 'estimate';
$font_size = 9;
$font_type = 'regular';
$font = 'Encode Sans, sans-serif';
$pdf_logo = '';
$footer_logo = '';
$pdf_size = 9;
$page_ori = 'portrait';
$units = 9;
$left_margin = 9;
$right_margin = 9;
$top_margin = 9;
$header_margin = 9;
$bottom_margin = 9;
$pdf_color = '#000000';
$text = ESTIMATE_TILE;
$header_font_colour = '#000000';
$header_font = 'Encode Sans, sans-serif';
$header_font_size = 9;
$header_font_type = 'regular';
$footer_text = '';
$footer_font_colour = '#000000';
$footer_font = 'Encode Sans, sans-serif';
$footer_alignment = 'left';
$footer_font_size = 9;
$footer_font_type = 'regular';
$alignment = 'right';
$font_body = 'Encode Sans, sans-serif';
$font_body_size = 9;
$font_body_type = 'regular';
$pdf_body_color = '#000000';
$heading_color = '#000000';
$page_numbers = 'bottom_main';
$heading1 = '';
$heading1_colour = '#000000';
$heading2 = '';
$heading2_colour = '#000000';
if(!empty($settings)) {
	$file_name = $settings['file_name'];
	$font_size = $settings['font_size'];
	$font_type = $settings['font_type'];
	$font = $settings['font'];
	$pdf_logo = $settings['pdf_logo'];
	$footer_logo = $settings['footer_logo'];
	$pdf_size = $settings['pdf_size'];
	$page_ori = $settings['page_ori'];
	$units = $settings['units'];
	$left_margin = $settings['left_margin'];
	$right_margin = $settings['right_margin'];
	$top_margin = $settings['top_margin'];
	$header_margin = $settings['header_margin'];
	$bottom_margin = $settings['bottom_margin'];
	$pdf_color = $settings['pdf_color'];
	$text = $settings['text'];
	$header_font_colour = $settings['header_font_colour'];
	$header_font = $settings['header_font'];
	$header_font_size = $settings['header_font_size'];
	$header_font_type = $settings['header_font_type'];
	$footer_text = $settings['footer_text'];
	$footer_font_colour = $settings['footer_font_colour'];
	$footer_font = $settings['footer_font'];
	$footer_alignment = $settings['footer_alignment'];
	$footer_font_size = $settings['footer_font_size'];
	$footer_font_type = $settings['footer_font_type'];
	$alignment = $settings['alignment'];
	$font_body = $settings['font_body'];
	$font_body_size = $settings['font_body_size'];
	$font_body_type = $settings['font_body_type'];
	$pdf_body_color = $settings['pdf_body_color'];
	$heading_color = $settings['heading_color'];
	$page_numbers = $settings['page_numbers'];
	$heading1 = $settings['heading1'];
	$heading1_colour = $settings['heading1_colour'];
	$heading2 = $settings['heading2'];
	$heading2_colour = $settings['heading2_colour'];
}
$pdf_sub_color = '#';
$pdf_sub_text_color = '#FFFFFF';
$val = 0;
for($i = 1; $i < 6; $i+=2) {
	$num = (hexdec(substr($pdf_color,$i,2))+96 > 255 ? 255 : hexdec(substr($pdf_color,$i,2))+96);
	$val += $num;
	$pdf_sub_color .= dechex($num);
}
if($val > 576) {
	$pdf_sub_text_color = '#000000';
}
if($estimateid > 0) {
	$file_name .= '_'.$estimateid.'.pdf';
} else {
	$file_name .= '_[ID].pdf';
}

if($estimateid > 0) {
	$estimate_cover = mysqli_query($dbc, "SELECT `businessid`, `created_date`, `expiry_date` FROM `estimates` WHERE `estimateid`='$estimateid'");
} else {
	$estimate_cover = mysqli_query($dbc, "SELECT 0 `businessid`, '".date('Y-m-d')."' `created_date`, '".date('Y-m-d',strtotime('+1month'))."' `expiry_date`");
}
$estimate_cover = mysqli_fetch_assoc($estimate_cover);
$cover_page = '<table style="height:90%; width:100%;">
	<tr style="height: '.($settings['cover_logo_height'] == '' ? '150' : $settings['cover_logo_height']*30).'px;">
		<td style="text-align:'.($settings['cover_alignment'] == 'centre' ? 'center' : $settings['cover_alignment']).';"><img src="download/'.$settings['cover_logo'].'" style="max-width:100%;height:100%;"/></td>
	</tr>
	<tr>
		<td style="color:'.$settings['cover_font_colour'].';font-size:'.$settings['cover_font_size'].'px;'.($settings['cover_font_type'] == 'bold' || $settings['cover_font_type'] == 'bold_italic' ? 'font-weight:900;' : '').($settings['cover_font_type'] == 'italic' || $settings['cover_font_type'] == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$settings['font'].';margin-top:95px;">
			<div style="'.($settings['cover_text_alignment'] == 'top' ? 'height:100%;' : '').'">'.str_replace(['[RECIPIENT]','[CREATED]','[EXPIRY]'],[($estimate_cover['businessid'] > 0 ? get_client($dbc,$estimate_cover['businessid']) : 'N/A'),$estimate_cover['created_date'],$estimate_cover['expiry_date']],html_entity_decode($settings['cover_text'])).'</div>
		</td>
	</tr>
</table>';

$pages_content = '<table style="height:80%; width:100%;">
	<tr>
		<td style="text-align:'.($settings['pages_alignment'] == 'centre' ? 'center' : $settings['pages_alignment']).';"><img src="download/'.$settings['pages_logo'].'" style="max-width:100%;height:150px;"/></td>
	</tr>
	<tr>
		<td style="color:'.$settings['pages_font_size'].';font-size:'.$settings['pages_font_size'].'px;'.($settings['pages_font_type'] == 'bold' || $settings['pages_font_type'] == 'bold_italic' ? 'font-weight:900;' : '').($settings['pages_font_type'] == 'italic' || $settings['pages_font_type'] == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$settings['font'].';margin-top:95px;">
			'.html_entity_decode($settings['pages_text']).'
		</td>
	</tr>
</table>';

$toc_content = '<table style="height:80%; width:100%;">
	<tr>
		<td style="color:'.$settings['pages_font_size'].';font-size:'.$settings['pages_font_size'].'px;'.($settings['pages_font_type'] == 'bold' || $settings['pages_font_type'] == 'bold_italic' ? 'font-weight:900;' : '').($settings['pages_font_type'] == 'italic' || $settings['pages_font_type'] == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$settings['font'].';margin-top:95px;">';
$toc_content .= '<h4>'.ESTIMATE_TILE.' Scope</h4>';
$toc_content .= '</td>
	</tr>
</table>';

$header_html = '<table style="height:10%;width:100%;">
	<tr>
		'.($alignment != 'centre' && $alignment != 'right' && $pdf_logo != '' ? '<td width="16%"><img src="download/'.$pdf_logo.'" style="max-width:100%;height:75px;"/></td>' : '').'
		<td style="color:'.$header_font_colour.';font-size:'.$header_font_size.'px;'.($header_font_type == 'bold' || $header_font_type == 'bold_italic' ? 'font-weight:900;' : '').($header_font_type == 'italic' || $header_font_type == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$header_font.';margin-top:95px;text-align:'.($alignment == 'left' ? 'right' : 'left').';width:'.($alignment == 'centre' ? '42%' : '84%').';">'.$text.'</td>
		'.(($alignment == 'centre' || $alignment == 'right') && $pdf_logo != '' ? '<td width="16%"><img src="download/'.$pdf_logo.'" style="max-width:100%;height:75px;"/></td>' : '').'
		'.($alignment == 'centre' && $pdf_logo != '' ? '<td width="42%"></td>' : '').'
	</tr>
</table>';
$html = '<div style="height:80%;"><table cellspacing="'.$left_margin.'px" style="border:5px solid '.$pdf_color.';" width="100%">
	<tr>
		<td colspan="4" style="border-bottom: 5px solid '.$pdf_color.';padding:'.$top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin.'px;color:'.$heading1_color.';font-size:'.$font_size.'px;'.($font_type == 'bold' || $font_type == 'bold_italic' ? 'font-weight:900;' : '').($font_type == 'italic' || $font_type == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$font.';width:100%;vertical-align:top;">'.$heading1.'</td>
	</tr>
	<tr>
		<td style="padding:'.$top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin.'px;color:'.$heading_color.';font-size:'.$font_size.'px;'.($font_type == 'bold' || $font_type == 'bold_italic' ? 'font-weight:900;' : '').($font_type == 'italic' || $font_type == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$font.';width:20%;vertical-align:top;">Business:</td>
		<td style="padding:'.$top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin.'px;color:'.$pdf_body_color.';font-size:'.$font_body_size.'px;'.($font_body_type == 'bold' || $font_body_type == 'bold_italic' ? 'font-weight:900;' : '').($font_body_type == 'italic' || $font_body_type == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$font_body.';width:30%;vertical-align:top;">Nose Creek Physiotherapy</td>
		<td style="padding:'.$top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin.'px;color:'.$heading_color.';font-size:'.$font_size.'px;'.($font_type == 'bold' || $font_type == 'bold_italic' ? 'font-weight:900;' : '').($font_type == 'italic' || $font_type == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$font.';width:20%;vertical-align:top;">Attendees:</td>
		<td style="padding:'.$top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin.'px;color:'.$pdf_body_color.';font-size:'.$font_body_size.'px;'.($font_body_type == 'bold' || $font_body_type == 'bold_italic' ? 'font-weight:900;' : '').($font_body_type == 'italic' || $font_body_type == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$font_body.';width:30%;vertical-align:top;">Kenneth Bond<br>Kayla Valtins</td>
	</tr>
	<tr>
		<td style="padding:'.$top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin.'px;color:'.$heading_color.';font-size:'.$font_size.'px;'.($font_type == 'bold' || $font_type == 'bold_italic' ? 'font-weight:900;' : '').($font_type == 'italic' || $font_type == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$font.';width:20%;vertical-align:top;">Date of Meeting:</td>
		<td style="padding:'.$top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin.'px;color:'.$pdf_body_color.';font-size:'.$font_body_size.'px;'.($font_body_type == 'bold' || $font_body_type == 'bold_italic' ? 'font-weight:900;' : '').($font_body_type == 'italic' || $font_body_type == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$font_body.';width:30%;vertical-align:top;">2017-04-19</td>
		<td style="padding:'.$top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin.'px;color:'.$heading_color.';font-size:'.$font_size.'px;'.($font_type == 'bold' || $font_type == 'bold_italic' ? 'font-weight:900;' : '').($font_type == 'italic' || $font_type == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$font.';width:20%;vertical-align:top;">Location:</td>
		<td style="padding:'.$top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin.'px;color:'.$pdf_body_color.';font-size:'.$font_body_size.'px;'.($font_body_type == 'bold' || $font_body_type == 'bold_italic' ? 'font-weight:900;' : '').($font_body_type == 'italic' || $font_body_type == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$font_body.';width:30%;vertical-align:top;">Other Google Hangouts</td>
	</tr>
	<tr>
		<td style="padding:'.$top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin.'px;color:'.$heading_color.';font-size:'.$font_size.'px;'.($font_type == 'bold' || $font_type == 'bold_italic' ? 'font-weight:900;' : '').($font_type == 'italic' || $font_type == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$font.';width:20%;vertical-align:top;">Meeting Start Time:</td>
		<td style="padding:'.$top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin.'px;color:'.$pdf_body_color.';font-size:'.$font_body_size.'px;'.($font_body_type == 'bold' || $font_body_type == 'bold_italic' ? 'font-weight:900;' : '').($font_body_type == 'italic' || $font_body_type == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$font_body.';width:30%;vertical-align:top;">9:00 am</td>
		<td style="padding:'.$top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin.'px;color:'.$heading_color.';font-size:'.$font_size.'px;'.($font_type == 'bold' || $font_type == 'bold_italic' ? 'font-weight:900;' : '').($font_type == 'italic' || $font_type == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$font.';width:20%;vertical-align:top;">Meeting End Time:</td>
		<td style="padding:'.$top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin.'px;color:'.$pdf_body_color.';font-size:'.$font_body_size.'px;'.($font_body_type == 'bold' || $font_body_type == 'bold_italic' ? 'font-weight:900;' : '').($font_body_type == 'italic' || $font_body_type == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$font_body.';width:30%;vertical-align:top;">10:30 am</td>
	</tr>
	<tr>
		<td style="padding:'.$top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin.'px;color:'.$heading_color.';font-size:'.$font_size.'px;'.($font_type == 'bold' || $font_type == 'bold_italic' ? 'font-weight:900;' : '').($font_type == 'italic' || $font_type == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$font.';width:20%;vertical-align:top;">Project:</td>
		<td style="padding:'.$top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin.'px;color:'.$pdf_body_color.';font-size:'.$font_body_size.'px;'.($font_body_type == 'bold' || $font_body_type == 'bold_italic' ? 'font-weight:900;' : '').($font_body_type == 'italic' || $font_body_type == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$font_body.';width:30%;vertical-align:top;">Clinic Ace Software</td>
		<td style="padding:'.$top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin.'px;color:'.$heading_color.';font-size:'.$font_size.'px;'.($font_type == 'bold' || $font_type == 'bold_italic' ? 'font-weight:900;' : '').($font_type == 'italic' || $font_type == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$font.';width:20%;vertical-align:top;">Service:</td>
		<td style="padding:'.$top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin.'px;color:'.$pdf_body_color.';font-size:'.$font_body_size.'px;'.($font_body_type == 'bold' || $font_body_type == 'bold_italic' ? 'font-weight:900;' : '').($font_body_type == 'italic' || $font_body_type == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$font_body.';width:30%;vertical-align:top;">Business Developement</td>
	</tr>
	<tr>
		<td style="padding:'.$top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin.'px;color:'.$heading_color.';font-size:'.$font_size.'px;'.($font_type == 'bold' || $font_type == 'bold_italic' ? 'font-weight:900;' : '').($font_type == 'italic' || $font_type == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$font.';width:20%;vertical-align:top;">Meeting Subject:</td>
		<td colspan="3" style="padding:'.$top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin.'px;color:'.$pdf_body_color.';font-size:'.$font_body_size.'px;'.($font_body_type == 'bold' || $font_body_type == 'bold_italic' ? 'font-weight:900;' : '').($font_body_type == 'italic' || $font_body_type == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$font_body.';width:30%;vertical-align:top;">Nose Creek - Software - Check Out tile - and other items that may come up</td>
	</tr>
	<tr>
		<td colspan="4" style="border-top: 5px solid '.$pdf_color.';border-bottom: 5px solid '.$pdf_color.';padding:'.$top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin.'px;color:'.$heading2_color.';font-size:'.$font_size.'px;'.($font_type == 'bold' || $font_type == 'bold_italic' ? 'font-weight:900;' : '').($font_type == 'italic' || $font_type == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$font.';width:100%;vertical-align:top;">'.$heading2.'</td>
	</tr>
	<tr>
		<td style="padding:'.$top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin.'px;color:'.$heading_color.';font-size:'.$font_size.'px;'.($font_type == 'bold' || $font_type == 'bold_italic' ? 'font-weight:900;' : '').($font_type == 'italic' || $font_type == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$font.';width:20%;vertical-align:top;">Topic(s):</td>
		<td colspan="3" style="padding:'.$top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin.'px;color:'.$pdf_body_color.';font-size:'.$font_body_size.'px;'.($font_body_type == 'bold' || $font_body_type == 'bold_italic' ? 'font-weight:900;' : '').($font_body_type == 'italic' || $font_body_type == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$font_body.';width:30%;vertical-align:top;">Nose Creek - Software - Check Out tile - and other items that may come up</td>
	</tr>
	<tr>
		<td style="padding:'.$top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin.'px;color:'.$heading_color.';font-size:'.$font_size.'px;'.($font_type == 'bold' || $font_type == 'bold_italic' ? 'font-weight:900;' : '').($font_type == 'italic' || $font_type == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$font.';width:20%;vertical-align:top;">Subject:</td>
		<td colspan="3" style="padding:'.$top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin.'px;color:'.$pdf_body_color.';font-size:'.$font_body_size.'px;'.($font_body_type == 'bold' || $font_body_type == 'bold_italic' ? 'font-weight:900;' : '').($font_body_type == 'italic' || $font_body_type == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$font_body.';width:30%;vertical-align:top;"></td>
	</tr>
</table>
<table cellpadding="'.$left_margin.'px" style="border:5px solid '.$pdf_color.';width:100%;">
	<tr style="color:'.$pdf_color.';font-size:'.$font_size.'px;'.($font_type == 'bold' || $font_type == 'bold_italic' ? 'font-weight:900;' : '').($font_type == 'italic' || $font_type == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$font.';">
		<td style="padding:'.$top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin.'px;width:100%">
			<p>Notes for changes/revisions from staff members:</p>
			<ul>
			<li><span style="text-decoration: line-through;">Information i\'s needed</span>
			<ul>
			<li><span style="text-decoration: line-through;"><strong>Kayla will be writing these</strong></span></li>
			</ul>
			</li>
			<li><span style="text-decoration: line-through;">Alphabetical Order - Insurance Companies</span>
			<ul>
			<li><span style="text-decoration: line-through;"><strong>Jonathan</strong></span></li>
			</ul>
			</li>
			<li><span style="text-decoration: line-through;">Insurers are showing up twice in some places - should not be happening</span>
			<ul>
			<li><span style="text-decoration: line-through;"><strong>Dayana</strong></span></li>
			</ul>
			</li>
			<li><span style="text-decoration: line-through;">attached to all appointments is the invoice - so the specific price goes to that patient-- <strong>Jonathan will insure this updates in all areas</strong></span>
			<ul>
			<li><span style="text-decoration: line-through;">if we make changes in the receipt- what happens to the booking calendar appointment? It should update and change that. We need this history, with dates and times and the correct appointment type/price.&nbsp;<strong>Jonathan will insure this updates in all areas</strong></span></li>
			<li><span style="text-decoration: line-through;">adjuster phones in to check on a patient - they need to know all of those details</span></li>
			<li><span style="text-decoration: line-through;">where do we put delete appointments? - Blair said he doesn\'t want but with the calendar, at the front desk, they need that slot to book something else.-- <strong>This will not show up in the calendar/day sheet but it will show up in the tally board -</strong>&nbsp;<strong>Jonathan will take care of this</strong></span></li>
			</ul>
			</li>
			<li><span style="text-decoration: line-through;">Once you are checked out, you can never go back --- Belle is constantly sending Dayana&nbsp;Support Requests for this - they need the ability to do this themselves-- <strong>both transaction will appear - the original and edited&nbsp;</strong></span></li>
			<li><span style="text-decoration: line-through;">"View Patient History" -- should go here but it is going to the Reports tile - link is wrong--&nbsp;<strong>missing the one accordion "invoices"-- in the patient tile-- when you edit this, you can see all of these - we will make it so that the calendar links to this</strong></span></li>
			<li><span style="text-decoration: line-through;">Check out tile headings order:</span>
			<ul>
			<li><span style="text-decoration: line-through;">Patient</span></li>
			<li><span style="text-decoration: line-through;">Injury</span></li>
			<li><span style="text-decoration: line-through;">Staff</span></li>
			<li><span style="text-decoration: line-through;">Appointment Type</span></li>
			<li><span style="text-decoration: line-through;"><strong>Payment Method</strong></span></li>
			<li><span style="text-decoration: line-through;">Services</span></li>
			<li><span style="text-decoration: line-through;">Inventory</span></li>
			<li><span style="text-decoration: line-through;">Packages</span></li>
			<li><span style="text-decoration: line-through;">Promotion</span></li>
			<li><span style="text-decoration: line-through;"><strong>Payment Method</strong></span></li>
			</ul>
			</li>
			<li><span style="text-decoration: line-through;"><strong>Have the Payment Method at the top and bottom.*</strong></span></li>
			<li><span style="text-decoration: line-through;">PT day sheet tile (personal day sheet) - PT\'s name at the top -&nbsp;<strong>Jonathan</strong></span></li>
			<li><span style="text-decoration: line-through;">right now if they change the appointment/profile&nbsp;WCB&nbsp;to Private-- it changes the ones before - this should not be happening</span></li>
			<li><span style="text-decoration: line-through;">Unassigned revenue--&nbsp;when the WCB&nbsp;can\'t go to that patient that is \'marked\' as private -- because it doesn\'t know where to assign to</span></li>
			</ul>
			<p><span style="text-decoration: line-through;">When adjusting invoices, it needs to cancel and create booking appointments, and when they have cancelled appointments, they need to be able to create new appointments in that slot, so cancelled appointments do not need to appear on the appointment calendar.</span></p>
			<p><em><strong>Notes from April 19:&nbsp;</strong></em></p>
			<p>Information i\'s - not on for this demorca&nbsp;site - we will turn this function on</p>
			<p><strong>Contacts tile</strong>: now we have new subtabs that will make it easier - went over these - Profile, Injury, Account</p>
			<ul>
			<li>Discharge button to be added</li>
			<li>add Documents accordion in Profile sub tab- so staff can upload PDFs&nbsp;to this part (instead of to the server at the clinic)</li>
			</ul>
			<p><strong>Check out tile</strong>: went over this</p>
		</td>
	</tr>
</table>
</div>';
$footer_html = '<table style="height:10%;border-collapse: separate; border-spacing:'.$top_margin.'px '.$left_margin.'px;width:100%;">
	<tr>
		'.($footer_alignment != 'centre' && $footer_alignment != 'right' && $footer_logo != '' ? '<td width="16%"><img src="download/'.$footer_logo.'" style="max-width:100%;height:75px;"/></td>' : '').'
		<td style="color:'.$footer_font_colour.';font-size:'.$footer_font_size.'px;'.($footer_font_type == 'bold' || $footer_font_type == 'bold_italic' ? 'font-weight:900;' : '').($footer_font_type == 'italic' || $footer_font_type == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$footer_font.';margin-top:95px;text-align:'.($footer_alignment == 'left' ? 'right' : 'left').';width:'.($footer_alignment == 'centre' ? '42%' : '84%').';">'.$footer_text.'</td>
		'.(($footer_alignment == 'centre' || $footer_alignment == 'right') && $footer_logo != '' ? '<td width="16%"><img src="download/'.$footer_logo.'" style="max-width:100%;height:75px;"/></td>' : '').'
		'.($footer_alignment == 'centre' && $footer_logo != '' ? '<td width="42%"></td>' : '').'
	</tr>
</table>';
$cover_footer = '<table style="border-collapse: separate; border-spacing:'.$top_margin.'px '.$left_margin.'px;width:100%; height: 10%;">
	<tr>
		<td style="color:'.$footer_font_colour.';font-size:'.$footer_font_size.'px;'.($footer_font_type == 'bold' || $footer_font_type == 'bold_italic' ? 'font-weight:900;' : '').($footer_font_type == 'italic' || $footer_font_type == 'bold_italic' ? 'font-style:italic;' : '').'font-family:'.$footer_font.';margin-top:95px;text-align:right;width:100%;">'.($page_numbers == 'bottom_cover' ? '1' : '').'</td>
	</tr>
</table>';