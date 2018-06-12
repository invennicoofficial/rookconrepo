<?php
function incident_investigation_report_pdf($dbc,$safetyid, $fieldlevelriskid) {
    $form_by = $_SESSION['contactid'];

    $get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_incident_investigation_report WHERE fieldlevelriskid='$fieldlevelriskid'"));

    $tab = get_safety($dbc, $safetyid, 'tab');
    $form = get_safety($dbc, $safetyid, 'form');

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';

    $get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pdf_logo FROM field_config_safety WHERE tab='$tab' AND form='$form'"));

    DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));
    $result_update_employee = mysqli_query($dbc, "UPDATE `safety_incident_investigation_report` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

    //$result_update_employee = mysqli_query($dbc, "UPDATE `safety_staff` SET `done` = 1 WHERE safetyid='$safetyid' AND staffid='$form_by' AND DATE(today_date) = CURDATE()");

    $today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
    $today_time = $get_field_level['today_time'];
    $address = $get_field_level['address'];
    $fields = $get_field_level['fields'];
    $fields_value = explode('**FFM**', $get_field_level['fields_value']);
    $person_in_charge = $get_field_level['person_in_charge'];
    $reporter = $get_field_level['reporter'];
    $reported_to = $get_field_level['reported_to'];
    $date_reported = $get_field_level['date_reported'];
    $description_of_incident = $get_field_level['description_of_incident'];
    $direct_cause_of_incident = $get_field_level['direct_cause_of_incident'];
    $contributing_factor = $get_field_level['contributing_factor'];
    $over_the_cause = $get_field_level['over_the_cause'];
    $imm_act_req = $get_field_level['imm_act_req'];
    $ltm_act_req = $get_field_level['ltm_act_req'];
    $immidiate_correcctive_act_req = $get_field_level['immidiate_correcctive_act_req'];
    $immi_date_comp = $get_field_level['immi_date_comp'];
    $long_trm_act_assign = $get_field_level['long_trm_act_assign'];
    $long_term_date = $get_field_level['long_term_date'];
    $dia_scene = $get_field_level['dia_scene'];
	$incident = explode('**FFM**', $get_field_level['incident']);
	$desc = $get_field_level['desc'];
	$desc1 = $get_field_level['desc1'];
	$all_task = $get_field_level['all_task'];
	$accident = explode('**FFM**', $get_field_level['accident']);

    class MYPDF extends TCPDF {

        //Page header
         public function Header() {
            if(PDF_LOGO != '') {
                $image_file = 'download/'.PDF_LOGO;
                $this->Image($image_file, 10, 10, 30, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
            }

            $this->setCellHeightRatio(0.7);
            $this->SetFont('helvetica', '', 9);
            $footer_text = '<p style="text-align:right;">'.PDF_HEADER.'</p>';
            $this->writeHTMLCell(0, 0, 0 , 5, $footer_text, 0, 0, false, "R", true);
        }

        // Page footer
        public function Footer() {
            // Position at 15 mm from bottom
            $this->SetY(-15);
            $this->SetFont('helvetica', 'I', 8);
            $footer_text = 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages();
            $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "L", true);
            $this->SetY(-30);
            $this->setCellHeightRatio(0.7);
            $this->SetFont('helvetica', '', 9);
            $footer_text = PDF_FOOTER;
            $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "C", true);
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
    $pdf->SetAutoPageBreak(TRUE, 40);

    $pdf->AddPage();
    $pdf->setCellHeightRatio(1.6);
    $pdf->SetFont('helvetica', '', 9);

    $html_weekly = '<h2>Incident/Near Miss Investigation Report</h2>';

    if (strpos($form_config, ','."fields3".',') !== FALSE) {
        $html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">
                <tr nobr="true" style="background-color:lightgrey; color:black;  width:22%;">
                <th>Date of Incident</th><th>Time</th><th>Address/Location</th></tr>';
        $html_weekly .= '<tr nobr="true"><td>'.$today_date.'</td><td>'.$today_time.'</td><td>'.$address.'</td></tr>';
        $html_weekly .= '</table>';
	}

	//
    if (strpos($form_config, ','."fields25".',') !== FALSE) {
		$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';

        $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="30%">JOB #</th><th width="35%">Customer</th><th width="35%">LSD #</th></tr>';
        $html_weekly .= '<tr nobr="true"><td>'.$incident[0].'</td><td>'.$incident[1].'</td><td>'.$incident[2].'</td></tr>';

        $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="45%">Facility / Rig Name</th><th width="45%">Date Of Occurrence</th><th width="10%">TIME</th></tr>';
        $html_weekly .= '<tr nobr="true"><td>'.$incident[3].'</td><td>'.$incident[4].'</td><td>'.$incident[5].'</td></tr>';

        $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="60%">Location</th><th width="30%">Date Reported</th><th width="10%">Time</th></tr>';
        $html_weekly .= '<tr nobr="true"><td>'.$incident[6].'</td><td>'.$incident[7].'</td><td>'.$incident[8].'</td></tr>';

        $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="50%">Did this incident involve a Subcontractor?</th><th width="50%">Name of Subcontractor</th></tr>';
        $html_weekly .= '<tr nobr="true"><td>'.$incident[9].'</td><td>'.$incident[10].'</td></tr>';

        $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="50%">Person Reporting Incident</th><th width="50%">Occupation</th></tr>';
        $html_weekly .= '<tr nobr="true"><td>'.$incident[11].'</td><td>'.$incident[12].'</td></tr>';

        $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="50%">Immediate Supervisor</th><th width="50%">Witness To Incident</th></tr>';
        $html_weekly .= '<tr nobr="true"><td>'.$incident[13].'</td><td>'.$incident[14].'</td></tr>';

        $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="100%">Type Of Incident</th></tr>';
        $html_weekly .= '<tr nobr="true"><td>'.$incident[15].'&nbsp;&nbsp;'.$incident[16].'</td></tr>';

        $html_weekly .= '</table>';
	}

	if (strpos($form_config, ','."fields64".',') !== FALSE) {
		$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';

        $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="40%">Date Reported</th><th width="40%">Date of Incident</th><th width="20%">Time of Incident</th></tr>';
        $html_weekly .= '<tr nobr="true"><td>'.$incident[7].'</td><td>'.$today_date.'</td><td>'.$today_time.'</td></tr>';

        $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="35%">Employee Occupation</th><th width="35%">Supervisor</th><th width="30%">Location of Incident</th></tr>';
        $html_weekly .= '<tr nobr="true"><td>'.$incident[12].'</td><td>'.$incident[13].'</td><td>'.$incident[6].'</td></tr>';

		$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="50%">Employee Name</th><th width="50%">Experience (months / years)</th></tr>';
		$html_weekly .= '<tr nobr="true"><td>'.$accident[0].'</td><td>'.$accident[1].'</td></tr>';

		$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="50%">Reported to </th><th width="50%">Legal Description</th></tr>';
		$html_weekly .= '<tr nobr="true"><td>'.$accident[2].'</td><td>'.$accident[3].'</td></tr>';

		$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="100%">Client at time of Incident</th></tr>';
		$html_weekly .= '<tr nobr="true"><td>'.$accident[4].'</td></tr>';

		$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="100%">Type of work being performed</th></tr>';
		$html_weekly .= '<tr nobr="true"><td>'.$accident[5].'</td></tr>';

		$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="100%">Type of Incident</th></tr>';
		$html_weekly .= '<tr nobr="true"><td>'.$accident[6].'&nbsp;&nbsp;'.$accident[7].'</td></tr>';

        $html_weekly .= '</table>';
	}

	if (strpos($form_config, ','."fields71".',') !== FALSE) {
	    $html_weekly .= '<h4>INJURY (complete this section for occupational injury incidents only)</h4>';
		$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';
		$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="100%">Type of Injury</th></tr>';
		$html_weekly .= '<tr nobr="true"><td>'.$accident[8].'&nbsp;&nbsp;'.$accident[9].'</td></tr>';

		$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="50%">Object Inflicting Injury</th><th width="50%">Nature of Injury</th></tr>';
		$html_weekly .= '<tr nobr="true"><td>'.$accident[10].'</td><td>'.$accident[11].'</td></tr>';

		$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="50%">First Aid received?</th><th width="50%">If yes, by whom?</th></tr>';
		$html_weekly .= '<tr nobr="true"><td>'.$accident[12].'</td><td>'.$accident[13].'</td></tr>';

		$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="50%">Medical Attention received?</th><th width="50%">If yes, what?</th></tr>';
		$html_weekly .= '<tr nobr="true"><td>'.$accident[14].'</td><td>'.$accident[15].'</td></tr>';

		$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="50%">Name of Physician?</th><th width="50%">Location</th></tr>';
		$html_weekly .= '<tr nobr="true"><td>'.$accident[16].'</td><td>'.$accident[17].'</td></tr>';

		$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="50%">Has the incident been reported to the WCB?</th><th width="50%">If yes, when?</th></tr>';
		$html_weekly .= '<tr nobr="true"><td>'.$accident[18].'</td><td>'.$accident[19].'</td></tr>';

		$html_weekly .= '</table>';
	}

	if (strpos($form_config, ','."fields72".',') !== FALSE) {
	    $html_weekly .= '<h4>ILLNESS (complete this section for occupational illness incidents only)</h4>';
		$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';
		$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="50%">Nature of Illness</th><th width="50%">Suspected source of illness</th></tr>';
		$html_weekly .= '<tr nobr="true"><td>'.$accident[20].'</td><td>'.$accident[21].'</td></tr>';

		$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="100%">Location where symptoms first appeared</th></tr>';
		$html_weekly .= '<tr nobr="true"><td>'.$accident[22].'</td></tr>';

		$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="100%">Conditions when symptoms first appeared</th></tr>';
		$html_weekly .= '<tr nobr="true"><td>'.$accident[23].'</td></tr>';

		$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="50%">Have you received medical treatment?</th><th width="50%">If yes, what?</th></tr>';
		$html_weekly .= '<tr nobr="true"><td>'.$accident[24].'</td><td>'.$accident[25].'</td></tr>';

		$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="50%">Name of Physician?</th><th width="50%">Location</th></tr>';
		$html_weekly .= '<tr nobr="true"><td>'.$accident[26].'</td><td>'.$accident[27].'</td></tr>';
		$html_weekly .= '</table>';
	}

	if (strpos($form_config, ','."fields73".',') !== FALSE) {
	    $html_weekly .= '<h4>NEAR MISS (complete this section for Near Miss incidents only)</h4>';
		$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';

		$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="100%">Involved</th></tr>';
		$html_weekly .= '<tr nobr="true"><td>'.$accident[28].'&nbsp;&nbsp;'.$accident[29].'</td></tr>';

		$html_weekly .= '</table>';
	}

	if (strpos($form_config, ','."fields74".',') !== FALSE) {
	    $html_weekly .= '<h4>DAMAGE (complete this section for damage incidents only)</h4>';
		$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';

		$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="100%">Damage to</th></tr>';
		$html_weekly .= '<tr nobr="true"><td>'.$accident[30].'&nbsp;&nbsp;'.$accident[31].'</td></tr>';

		$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="50%">What was damaged</th><th width="50%">Object inflicting damage</th></tr>';
		$html_weekly .= '<tr nobr="true"><td>'.$accident[32].'</td><td>'.$accident[33].'</td></tr>';

		$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="100%">Extent of damage</th></tr>';
		$html_weekly .= '<tr nobr="true"><td>'.$accident[34].'&nbsp;&nbsp;'.$accident[35].'</td></tr>';

		$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="100%">Estimated cost of repairs or replacement?$</th></tr>';
		$html_weekly .= '<tr nobr="true"><td>'.$accident[36].'</td></tr>';

		$html_weekly .= '</table>';
	}

    if (strpos($form_config, ','."fields4".',') !== FALSE) {
        $html_weekly .= "<h3>Investigation</h3> ";

        $html_weekly .= '<br>Injury : ';
        if (strpos(','.$fields.',', ',fields4,') !== FALSE) {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[4];
    }

    if (strpos($form_config, ','."fields5".',') !== FALSE) {
        $html_weekly .= '<br>Illness : ';
        if (strpos(','.$fields.',', ',fields5,') !== FALSE) {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[5];
    }

    if (strpos($form_config, ','."fields6".',') !== FALSE) {
        $html_weekly .= '<br>Lost Time : ';
        if (strpos(','.$fields.',', ',fields6,') !== FALSE) {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[6];
    }

    if (strpos($form_config, ','."fields7".',') !== FALSE) {
        $html_weekly .= '<br>Property Damage : ';
        if (strpos(','.$fields.',', ',fields7,') !== FALSE) {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[7];
    }

    if (strpos($form_config, ','."fields8".',') !== FALSE) {
        $html_weekly .= '<br>Fire : ';
        if (strpos(','.$fields.',', ',fields8,') !== FALSE) {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[8];
    }

    if (strpos($form_config, ','."fields9".',') !== FALSE) {
        $html_weekly .= '<br>Environmental Incident : ';
        if (strpos(','.$fields.',', ',fields9,') !== FALSE) {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[9];
    }

    if (strpos($form_config, ','."fields10".',') !== FALSE) {
    $html_weekly .= "<h3>Person In Charge</h3> ".$person_in_charge;
    }
    if (strpos($form_config, ','."fields11".',') !== FALSE) {
    $html_weekly .= "<h3>Reported By</h3> ".$reporter;
    }
    if (strpos($form_config, ','."fields12".',') !== FALSE) {
    $html_weekly .= "<h3>Reported To</h3> ".$reported_to;
    }
    if (strpos($form_config, ','."fields13".',') !== FALSE) {
    $html_weekly .= "<h3>Date Reported</h3> ".$date_reported;
    }

    if (strpos($form_config, ','."fields14".',') !== FALSE) {
    $html_weekly .= "<h3>Description of Incident</h3>".html_entity_decode($description_of_incident);
    }
    if (strpos($form_config, ','."fields15".',') !== FALSE) {
    $html_weekly .= "<h3>Direct Cause of Incident</h3>".html_entity_decode($direct_cause_of_incident);
    }
    if (strpos($form_config, ','."fields16".',') !== FALSE) {
    $html_weekly .= "<h3>Contributing Factors</h3>".html_entity_decode($contributing_factor);
    }
    if (strpos($form_config, ','."fields17".',') !== FALSE) {
    $html_weekly .= "<h3>Person Having Control over the Cause</h3>".$over_the_cause;
    }
    if (strpos($form_config, ','."fields18".',') !== FALSE) {
    $html_weekly .= "<h3>IMMEDIATE Corrective Actions Required</h3>".html_entity_decode($imm_act_req);
    }
    if (strpos($form_config, ','."fields19".',') !== FALSE) {
    $html_weekly .= "<h3>LONG TERM Corrective Actions Required</h3>".html_entity_decode($ltm_act_req);
    }
    if (strpos($form_config, ','."fields20".',') !== FALSE) {
    $html_weekly .= "<h3>Immediate Corrective Actions Assigned To</h3> ".$immidiate_correcctive_act_req;
    }
    if (strpos($form_config, ','."fields21".',') !== FALSE) {
    $html_weekly .= "<h3>Date Complete</h3>".$immi_date_comp;
    }
    if (strpos($form_config, ','."fields22".',') !== FALSE) {
    $html_weekly .= "<h3>Long Term Corrective Actions Assigned To</h3>".$long_trm_act_assign;
    }
    if (strpos($form_config, ','."fields23".',') !== FALSE) {
    $html_weekly .= "<h3>Date Complete</h3>".$long_term_date;
    }
    if (strpos($form_config, ','."fields24".',') !== FALSE) {
    $html_weekly .= "<h3>Diagram of the Scene</h3>".html_entity_decode($dia_scene);
    }

	//

	if (strpos($form_config, ','."fields40".',') !== FALSE) {

        $html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';

        $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="50%">Name Of Injured Employee</th><th width="50%">Occupation</th></tr>';
        $html_weekly .= '<tr nobr="true"><td>'.$incident[17].'</td><td>'.$incident[18].'</td></tr>';

        $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="50%">Employee Address</th><th width="50%">Date Of Birth</th></tr>';
        $html_weekly .= '<tr nobr="true"><td>'.$incident[19].'</td><td>'.$incident[20].'</td></tr>';

        $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="50%">Nature Of The Injury</th><th width="50%">Body Part</th></tr>';
        $html_weekly .= '<tr nobr="true"><td>'.$incident[21].'</td><td>'.$incident[23].'&nbsp; '.$incident[22].' &nbsp;'.$incident[24].'</td></tr>';

        $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="40%">Did This Aggravate A Previous Injury?</th><th width="60%">Nature Of The Injury</th></tr>';
        $html_weekly .= '<tr nobr="true"><td>'.$incident[25].'&nbsp;&nbsp;'.$incident[26].'</td><td>'.$incident[27].'</td></tr>';

        $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="50%">First Aid</th><th width="50%">First Aid Rendered By</th></tr>';
        $html_weekly .= '<tr nobr="true"><td>'.$incident[28].'</td><td>'.$incident[29].'</td></tr>';

        $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="50%">Medical Treatment</th><th width="50%">Treatment Rendered By</th></tr>';
        $html_weekly .= '<tr nobr="true"><td>'.$incident[30].'</td><td>'.$incident[31].'</td></tr>';

        $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="34%">Health Care Number</th><th width="33%">SIN #</th><th width="33%">Home Phone</th></tr>';
        $html_weekly .= '<tr nobr="true"><td>'.$incident[32].'</td><td>'.$incident[33].'</td><td>'.$incident[34].'</td></tr>';

        $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="100%">Object, Equipment, Or Substance Inflicting Injury</th></tr>';
        $html_weekly .= '<tr nobr="true"><td>'.$incident[35].'</td></tr>';

        $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="100%">Date Injured Worker Commenced Employment</th></tr>';
        $html_weekly .= '<tr nobr="true"><td>'.$incident[36].'</td></tr>';

        $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="100%">Was A Tailgate Meeting Held Prior To The Job?</th></tr>';
        $html_weekly .= '<tr nobr="true"><td>'.$incident[37].'&nbsp;&nbsp;'.$incident[38].'</td></tr>';

        $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="100%">Personal Protective Equipment Worn At Time Of Injury</th></tr>';
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Hard Hat';
        if ($incident[39] == 'Hard Hat') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= $incident[40].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Coveralls';
        if ($incident[41] == 'Coveralls') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= $incident[42].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Gloves';
        if ($incident[43] == 'Gloves') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= $incident[44].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Safety Boots';
        if ($incident[45] == 'Safety Boots') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= $incident[46].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Safety Glasses';
        if ($incident[47] == 'Safety Glasses') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= $incident[48].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Mono goggles';
        if ($incident[49] == 'Mono goggles') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= $incident[50].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Face shield';
        if ($incident[51] == 'Face shield') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= $incident[52].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Other';
        if ($incident[53] == 'Other') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;';
        }
        $html_weekly .= $incident[54].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '</table>';

    }

    if (strpos($form_config, ','."fields59".',') !== FALSE) {
        $html_weekly .= '<h3>Analysis</h3>';

        $html_weekly .= '<h4>Causal Factors(Factors that, if corrected, would have prevented this incident from occurring or would have significantly mitigated its consequences.)</h4>' . html_entity_decode($desc);
    }

    if (strpos($form_config, ','."fields60".',') !== FALSE) {
	    $html_weekly .= '<h4>Root Cause(What is / are the most basic cause(s) under work direction, training, management systems, etc.?)</h4>' . html_entity_decode($desc1);
    }

    if (strpos($form_config, ','."fields61".',') !== FALSE) {
        $html_weekly .= '<h4>Prevention</h4>';

        $html_weekly .= '<br><br><table border="1px" style="padding:3px; border:1px solid black;">';
        $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                        <th>What action or recommendations are made to prevent recurrence?</th>
                        <th>Date</th>
                        <th>Action By</th>
                        <th>Complete</th></tr>';

        $all_task_each = explode('**##**',$all_task);

        $total_count = mb_substr_count($all_task,'**##**');
        for($client_loop=0; $client_loop<=$total_count; $client_loop++) {
                        $task_item = explode('**',$all_task_each[$client_loop]);
                        $task = $task_item[0];
                        $hazard = $task_item[1];
                        $level = $task_item[2];
                        $plan = $task_item[3];
                        if($task != '') {
                            $html_weekly .= '<tr>';
                            $html_weekly .= '<td data-title="Email">' . $task . '</td>';
                            $html_weekly .= '<td data-title="Email">' . $hazard . '</td>';
                            $html_weekly .= '<td data-title="Email">' . $level . '</td>';
                            $html_weekly .= '<td data-title="Email">' . $plan . '</td>';
                            $html_weekly .= '</tr>';
                        }
                    }
        $html_weekly .= '</table>';
    }

    if (strpos($form_config, ','."fields62".',') !== FALSE) {
        $html_weekly .= '<h3>Costs</h3>';
        $html_weekly .= '<h4>For Vehicle / Equipment / Property Damage</h4>';
        $html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';
        $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="50%">Estimated</th><th width="50%">Actual</th></tr>';
        $html_weekly .= '<tr nobr="true"><td>'.$incident[55].'</td><td>'.$incident[56].'</td></tr>';
        $html_weekly .= '</table>';
	}

	if (strpos($form_config, ','."fields63".',') !== FALSE) {
        $html_weekly .= '<h3>Injury Classification</h3> '.$incident[57].'&nbsp;&nbsp;'.$incident[58];
	}

    $sa = mysqli_query($dbc, "SELECT * FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");

    $html_weekly .= '<br><br><table border="1px" style="padding:3px; border:1px solid black;">';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
        <th>Name</th>
        <th>Signature</th>
        <th>Date</th>
        </tr>';

    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];
        $staffcheck = $row_sa['staffcheck'];

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">' . $row_sa['assign_staff'] . '</td>';
        $html_weekly .= '<td data-title="Email"><img src="incident_investigation_report/download/safety_'.$assign_staff_id.'.png" width="150" height="70" border="0" alt=""></td>';
        $html_weekly .= '<td data-title="Email">'.$staffcheck.'</td>';
        $html_weekly .= '</tr>';
    }
    $html_weekly .= '</table>';

    $pdf->writeHTML($html_weekly, true, false, true, false, '');
    $pdf->Output('incident_investigation_report/download/hazard_'.$fieldlevelriskid.'.pdf', 'F');

    $sa = mysqli_query($dbc, "SELECT safetyattid FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");
    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];
        unlink("incident_investigation_report/download/safety_".$assign_staff_id.".png");
    }
    echo '';
}
?>