<?php
function neck_disability_questionnaire_pdf($dbc,$patientformid, $fieldlevelriskid) {

    $form = get_patientform($dbc, $patientformid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_patientform WHERE form='$form'"));
    DEFINE('PDF_LOGO', $get_field_config['pdf_logo']);
    $form_config = ','.$get_field_config['fields'].',';

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM patientform_neck_disability_questionnaire WHERE fieldlevelriskid='$fieldlevelriskid'"));
	$today_date = $get_field_level['today_date'];
	$patient = $get_field_level['patient'];

    $pain_scale = $get_field_level['pain_scale'];
    $section1 = $get_field_level['section1'];
    $section2 = $get_field_level['section2'];
    $section3 = $get_field_level['section3'];
    $section4 = $get_field_level['section4'];
    $section5 = $get_field_level['section5'];
    $section6 = $get_field_level['section6'];
    $section7 = $get_field_level['section7'];
    $section8 = $get_field_level['section8'];
    $section9 = $get_field_level['section9'];
    $section10 = $get_field_level['section10'];
    $total_score = $get_field_level['total_score'];

	class MYPDF extends TCPDF {
        public function Header() {
            if(PDF_LOGO != '') {
                $image_file = 'download/'.PDF_LOGO;
                $this->Image($image_file, 10, 10, 60, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
            }
            $this->SetFont('helvetica', '', 8);
            $header_text = '';
            $this->writeHTMLCell(0, 0, '', '', $header_text, 0, 0, false, "L", "R",true);

        }

        // Page footer
        public function Footer() {
            // Position at 15 mm from bottom
            $this->SetY(-15);
            $this->SetFont('helvetica', 'I', 8);
            $footer_text = 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages();
            $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "L", true);
        }
    }

    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, false, false);
    $pdf->setFooterData(array(0,64,0), array(0,64,128));

    if(PDF_LOGO != '') {
        $pdf->SetMargins(PDF_MARGIN_LEFT, 55, PDF_MARGIN_RIGHT);
    } else {
        $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
    }
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 10);

	$html_weekly = '<h2>Neck Disability Questionnaire</h2>';

    if (strpos($form_config, ','."fields2".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Patient:</b><br>".$patient;
    }

    if (strpos($form_config, ','."fields3".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Date:</b><br>".$today_date;
    }
    if (strpos($form_config, ','."fields4".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Pain Scale:(0 : No Pain - 10 : Worst Pain)</b><br>".$pain_scale.' / 10';
    }

    $html_weekly .= "<br><br><b>Please Read:</b> This Questionnaire is designed to enable us to understand how much neck pain has affected your ability to perform your everyday activities.<br><br>
    Please answer each section by tick mark <b>ONE CHOICE</b> that most applies to you. We realize that you may feel that more than one statement may relate to you, but <b>PLEASE JUST TICK MARK THE ONE CHOICE WHICH MOST CLOSELY DESCRIBES YOUR PROBLEM RIGHT NOW.</b>";

    if (strpos(','.$form_config.',', ',fields5,') !== FALSE) {
        $html_weekly .= '<br><br><b>PAIN INTENSITY</b><br>';
        if($section1 == 0) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I have no pain at the moment<br>';

        if($section1 == 1) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'The pain is very mild at the moment<br>';
        if($section1 == 2) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'The pain is moderate at the moment<br>';
        if($section1 == 3) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'The pain its fairty severe at the moment<br>';
        if($section1 == 4) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'The pain is very severe at the moment<br>';
        if($section1 == 5) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'The pain the worst imaginable at the moment';
    }

    if (strpos(','.$form_config.',', ',fields6,') !== FALSE) {
        $html_weekly .= '<br><br><b>PERSONAL CARE</b><br>';
        if($section2 == 0) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I can took after myself normally without causing extra pain<br>';

        if($section2 == 1) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I can look after myself normally. but it causes extra pain<br>';
        if($section2 == 2) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'It rs painful to look after myself and I am slow and careful<br>';
        if($section2 == 3) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I need some help. but manage most of my personal care<br>';
        if($section2 == 4) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I need help every day in most aspects of self care<br>';
        if($section2 == 5) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I do not get dressed. I wash with difficulty and stay in bed';
    }
    if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br><br><b>LIFTING</b><br>';
        if($section3 == 0) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I can lift heavy weights without extra pain<br>';

        if($section3 == 1) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I can lift heavy weights. but it gives extra pain<br>';
        if($section3 == 2) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'Pain prevents me from lifting heavy weights off the floor, but I can manage if they are conveniently positioned, for example on the table<br>';
        if($section3 == 3) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'Pain prevents me from lifting heavy weights, but I can manage light to medium weights if they are conveniently positioned<br>';
        if($section3 == 4) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I can lift very light weights<br>';
        if($section3 == 5) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I cannot lift or carry anything at all';
    }
    if (strpos(','.$form_config.',', ',fields8,') !== FALSE) {
        $html_weekly .= '<br><br><b>READING</b><br>';
        if($section4 == 0) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I can read as much as I want to with no pain in my neck<br>';

        if($section4 == 1) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I can read as much as I want to with slight pain in my neck<br>';
        if($section4 == 2) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I can read as much as I want to with moderate pain in my neck<br>';
        if($section4 == 3) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I cannot read as much as I want because of moderate pain in my<br>';
        if($section4 == 4) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I cannot read as much as I want because of severe pain in my neck<br>';
        if($section4 == 5) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I cannot read at all';
    }
    if (strpos(','.$form_config.',', ',fields9,') !== FALSE) {
        $html_weekly .= '<br><br><b>HEADACHES</b><br>';
        if($section5 == 0) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I have no headaches at all<br>';

        if($section5 == 1) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I have slight headaches which come infrequently<br>';
        if($section5 == 2) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I have moderate headaches which come infrequently<br>';
        if($section5 == 3) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I have moderate headaches which come frequently<br>';
        if($section5 == 4) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I have severe headaches which come frequently<br>';
        if($section5 == 5) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I have headaches almost all of the time';
    }
    if (strpos(','.$form_config.',', ',fields10,') !== FALSE) {
        $html_weekly .= '<br><br><b>CONCENTRATION</b><br>';
        if($section6 == 0) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I can concentrate fully when I want to with no difficulty<br>';

        if($section6 == 1) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I can concentrate fully when I want to with slight difficulty<br>';
        if($section6 == 2) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I have a fair degree of difficulty in concentrating when I want to<br>';
        if($section6 == 3) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I have a lot of difficulty in concentrating when I want to<br>';
        if($section6 == 4) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I have a great deal of difficulty in concentrating when I want to<br>';
        if($section6 == 5) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I cannot concentrate at all';
    }
    if (strpos(','.$form_config.',', ',fields11,') !== FALSE) {
        $html_weekly .= '<br><br><b>WORK</b><br>';
        if($section7 == 0) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I can do as much work as I want to<br>';

        if($section7 == 1) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I can only do my usual work, but no more<br>';
        if($section7 == 2) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I can do most of my usual work, but no more<br>';
        if($section7 == 3) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I cannot do my usual work<br>';
        if($section7 == 4) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I can hardly do any work at all<br>';
        if($section7 == 5) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I cannot do any work at all';
    }
    if (strpos(','.$form_config.',', ',fields12,') !== FALSE) {
        $html_weekly .= '<br><br><b>DRIVING</b><br>';
        if($section8 == 0) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I can drive my car without any neck pain<br>';

        if($section8 == 1) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I can drive my car as long as I want with slight pain in my neck<br>';
        if($section8 == 2) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I can drive my car as long as I want with moderate pain in my neck<br>';
        if($section8 == 3) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I cannot drive my car as long as I want because of severe pain in my neck<br>';
        if($section8 == 4) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I can hardly drive at all because of severe pain in my neck<br>';
        if($section8 == 5) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I cannot drive my car at all';
    }
    if (strpos(','.$form_config.',', ',fields13,') !== FALSE) {
        $html_weekly .= '<br><br><b>SLEEPING</b><br>';
        if($section9 == 0) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I have no trouble sleeping<br>';

        if($section9 == 1) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'My sleep is slightly disturbed (less than 1 hour sleepless)<br>';
        if($section9 == 2) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'My sleep is mildly disturbed (1-2 hours sleepless)<br>';
        if($section9 == 3) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'My sleep is moderately disturbed (2-3 hours sleepless)<br>';
        if($section9 == 4) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'My sleep is greatly disturbed (3-5 hours sleepless)<br>';
        if($section9 == 5) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'My sleep is completely disturbed (5-7 hours sleepless)';
    }
    if (strpos(','.$form_config.',', ',fields14,') !== FALSE) {
        $html_weekly .= '<br><br><b>RECREATION</b><br>';
        if($section10 == 0) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I am able to engage in all of my recreational activities with no neck pain at all<br>';

        if($section10 == 1) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I am able to engage in all of my recreational activities with some pain in my neck<br>';
        if($section10 == 2) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I am able to engage in most, but not all of my recreational activities because of pain in my neck<br>';
        if($section10 == 3) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I am able to engage in a few of my recreational activities because of pain in my neck<br>';
        if($section10 == 4) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I can hardly do any recreational activities because of pain in my neck<br>';
        if($section10 == 5) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= 'I cannot do any recreational activities at all';
    }

    if (strpos(','.$form_config.',', ',fields15,') !== FALSE) {
    $html_weekly .= '<br><br><b>Total Score : </b>'.$total_score.' / 24';
    }

    if (strpos(','.$form_config.',', ',fields16,') !== FALSE) {
        $html_weekly .= '<br><br><img src="neck_disability_questionnaire/download/sign_'.$fieldlevelriskid.'.png" width="150" height="70" border="0" alt="">&nbsp;&nbsp;';
    }

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    $pdf->Output('neck_disability_questionnaire/download/patientform_'.$fieldlevelriskid.'.pdf', 'F');

    unlink("neck_disability_questionnaire/download/sign_".$fieldlevelriskid.".png");
    echo '';
}
?>








