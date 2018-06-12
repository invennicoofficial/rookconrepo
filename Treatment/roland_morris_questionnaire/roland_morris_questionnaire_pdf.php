<?php
function roland_morris_questionnaire_pdf($dbc,$patientformid, $fieldlevelriskid) {

    $form = get_patientform($dbc, $patientformid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_patientform WHERE form='$form'"));
    DEFINE('PDF_LOGO', $get_field_config['pdf_logo']);
    $form_config = ','.$get_field_config['fields'].',';

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM patientform_roland_morris_questionnaire WHERE fieldlevelriskid='$fieldlevelriskid'"));
	$today_date = $get_field_level['today_date'];
	$patient = $get_field_level['patient'];

    $pain_scale = $get_field_level['pain_scale'];
    $fields = $get_field_level['fields'];
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

	$html_weekly = '<h2>Roland Morris Questionnaire</h2>';

    if (strpos($form_config, ','."fields2".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Patient:</b><br>".$patient;
    }

    if (strpos($form_config, ','."fields3".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Date:</b><br>".$today_date;
    }
    if (strpos($form_config, ','."fields4".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Pain Scale:(0 : No Pain - 10 : Worst Pain)</b><br>".$pain_scale.' / 10';
    }

    $html_weekly .= "<br><br>When your back hurts, you may find it difficult to do some of the things you normally do. This list contains some sentences that people have used to describe themselves when they have back pain. When you read them, you may find that some stand out because they describe you today. As you read the list, think of yourself today. When you read a sentence that describes you today, put a circle around its number. If the sentence does not describe you, then leave the space blank and go on to the next one.<br><br>Remember only Tick mark the number of the sentence if you are sure that it describes you today.<br><br>";

    if (strpos(','.$form_config.',', ',fields5,') !== FALSE) {

    if (strpos(','.$fields.',', ',fields1,') !== FALSE) {
        $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html_weekly .= 'I stay at home most of the time because of my back.<br>';

    if (strpos(','.$fields.',', ',fields2,') !== FALSE) {
        $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html_weekly .= ' I change positions frequently to try to get my back comfortable.<br>';
    if (strpos(','.$fields.',', ',fields3,') !== FALSE) {
        $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html_weekly .= ' I walk more slowly than usual because of my back.<br>';
    if (strpos(','.$fields.',', ',fields4,') !== FALSE) {
        $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html_weekly .= ' Because of my back, I am not doing any of the jobs that I usually do around the house.<br>';
    if (strpos(','.$fields.',', ',fields5,') !== FALSE) {
        $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html_weekly .= ' Because of my back, I use a handrail to get upstairs.<br>';
    if (strpos(','.$fields.',', ',fields6,') !== FALSE) {
        $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html_weekly .= ' Because of my back, I lie down to rest more often.<br>';
    if (strpos(','.$fields.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html_weekly .= ' Because of my back, I have to hold onto something to get out of an easy chair.<br>';
    if (strpos(','.$fields.',', ',fields8,') !== FALSE) {
        $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html_weekly .= ' Because of my back, I try to get other people to do things for me.<br>';
    if (strpos(','.$fields.',', ',fields9,') !== FALSE) {
        $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html_weekly .= ' I get dressed more slowly than usual because of my back.<br>';
    if (strpos(','.$fields.',', ',fields10,') !== FALSE) {
        $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html_weekly .= ' I only stand for short periods of time because of my back.<br>';
    if (strpos(','.$fields.',', ',fields11,') !== FALSE) {
        $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html_weekly .= ' Because of my back, I try not to bend or kneel down.<br>';
    if (strpos(','.$fields.',', ',fields12,') !== FALSE) {
        $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html_weekly .= ' I find it difficult to get out of a chair because of my back.<br>';
    if (strpos(','.$fields.',', ',fields13,') !== FALSE) {
        $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html_weekly .= ' My back is painful almost all of the time.<br>';
    if (strpos(','.$fields.',', ',fields14,') !== FALSE) {
        $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html_weekly .= ' I find it difficult to turn over in bed because of my back.<br>';
    if (strpos(','.$fields.',', ',fields15,') !== FALSE) {
        $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html_weekly .= ' My appetite is not very good because of my back pain.<br>';
    if (strpos(','.$fields.',', ',fields16,') !== FALSE) {
        $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html_weekly .= ' I have trouble putting on my socks (or stockings) because of the pain in my back.<br>';
    if (strpos(','.$fields.',', ',fields17,') !== FALSE) {
        $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html_weekly .= ' I only walk short distances because of my back pain.<br>';
    if (strpos(','.$fields.',', ',fields18,') !== FALSE) {
        $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html_weekly .= ' I sleep less well because of my back.<br>';
    if (strpos(','.$fields.',', ',fields19,') !== FALSE) {
        $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html_weekly .= ' Because of my back pain, I get dressed with help from someone else.<br>';
    if (strpos(','.$fields.',', ',fields20,') !== FALSE) {
        $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html_weekly .= ' I sit down for most of the day because of my back.<br>';
    if (strpos(','.$fields.',', ',fields21,') !== FALSE) {
        $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html_weekly .= ' I avoid jobs around the house because of my back.<br>';
    if (strpos(','.$fields.',', ',fields22,') !== FALSE) {
        $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html_weekly .= ' Because of my back pain, I am more irritable and bad tempered with people than usual.<br>';
    if (strpos(','.$fields.',', ',fields23,') !== FALSE) {
        $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html_weekly .= ' Because of my back, I go up and down stairs more slowly than usual.<br>';
    if (strpos(','.$fields.',', ',fields24,') !== FALSE) {
        $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html_weekly .= ' I stay in bed most of the time because of my back.<br>';

    }

    if (strpos(','.$form_config.',', ',fields6,') !== FALSE) {
    $html_weekly .= '<br><br><b>Total Score : </b>'.$total_score.' / 24';
    }

    if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br><br><img src="roland_morris_questionnaire/download/sign_'.$fieldlevelriskid.'.png" width="150" height="70" border="0" alt="">';
    }

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    $pdf->Output('roland_morris_questionnaire/download/patientform_'.$fieldlevelriskid.'.pdf', 'F');

    unlink("roland_morris_questionnaire/download/sign_".$fieldlevelriskid.".png");
    echo '';
}
?>








