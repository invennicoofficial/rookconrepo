<?php
	function journey_management_trip_tracking_pdf($dbc,$safetyid, $fieldlevelriskid) {
    $form_by = $_SESSION['contactid'];
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_journey_management_trip_tracking WHERE fieldlevelriskid='$fieldlevelriskid'"));

	$tab = get_safety($dbc, $safetyid, 'tab');
    $form = get_safety($dbc, $safetyid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';
	$get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pdf_logo FROM field_config_safety WHERE tab='$tab' AND form='$form'"));

    DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));
	$result_update_employee = mysqli_query($dbc, "UPDATE `safety_journey_management_trip_tracking` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
	$desc = $get_field_level['desc'];
	$desc1 = $get_field_level['desc1'];
	$desc2 = $get_field_level['desc2'];
    $fields = explode('**FFM**', $get_field_level['fields']);

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

	$html_weekly = '<h2>Journey Management Trip Tracking</h2>'; // Form nu heading

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="40%">Drivers Name (Employee)</th><th width="30%">Date</th><th width="30%">Unit Number</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[0].'</td><td>'.$fields[1].'</td><td>'.$today_date.'</td></tr>';

    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="40%">Customer</th><th width="30%">Departure Location</th><th width="30%">Departure Date / Time</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[3].'</td><td>'.$fields[4].'</td><td>'.$fields[5].'</td></tr>';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="40%">Customer Contact & Phone Number</th><th width="30%">Destination Location</th><th width="30%">Est. Arrival Date / Time</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[6].'</td><td>'.$fields[7].'</td><td>'.$fields[8].'</td></tr>';

	$html_weekly .= '</table>';

	$html_weekly .= '<br>Other Employees in vehicle<br> '.html_entity_decode($desc).'';

//////////////////////////////////////////////////above done ///////////////////////////////////////

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="100%">Points allocated on Journey Management - Trip Assessment Form (as per driver filling out form)</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[9].'</td></tr>';

	$html_weekly .= '</table>';

	$html_weekly .= '<br>Actions Taken ( as per score above)<br> '.html_entity_decode($desc1).'';

////////////////////////////////////////////// above done ////////////////////////////////////////////

	$html_weekly .= '<h4>Trip Assessment Form Scores</h4>';

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="100%">Trip Assessment Form Scores</th></tr>';

	$html_weekly .= '<tr nobr="true"><td>'.$fields[10].'&nbsp;&nbsp;'.$fields[11].'</td></tr>';


	$html_weekly .= '<tr nobr="true"><td>';

	$html_weekly .= '<p>1 - 30 or Less Points : Proceed and re-evaluate in 3 hours or as necessary</p>';
	$html_weekly .= '<p>2 - 31 to 70  points : Proceed with caution and re-evaluate in 1 1/2 hours</p>';
	$html_weekly .= '<p>3 - 71 to 100 points : Do not proceed until you have contacted your Manager / Supervisor and developed a safe plan.</p>';
	$html_weekly .= '<p>4 - > 100 points : Do not proceed! Manager / Supervisor to have an alternate drive the vehicle to the job site.</p></td></tr>';

	$html_weekly .= '</table>';

	$html_weekly .= '<h4>Trip Tracking</h4>';

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';
	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="100%">Estimated Travel Time (Hours)</th></tr>';

	$html_weekly .= '<tr nobr="true"><td>'.$fields[12].'</td></tr>';

	$html_weekly .= '</table>';

//////////////////////////////////////////// above done //////////////////////////////

	$html_weekly .= '<br><br><table border="1px" style="padding:3px; border:1px solid black;">';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                    <th>Call #</th>
                    <th>Time</th>
                    <th>Date</th>
                    <th>Conversation</th></tr>';

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

	$html_weekly .= '<h4>Trip Assessment (As per Drivers Answers)</h4>';

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="70%">1 : Is this trip Necessary?</th><th width="22%">Yes = 0 &nbsp; No = 50</th><th width="8%">Score</th></tr>';

	$html_weekly .= '<tr nobr="true"><td>'.$fields[13].'</td>&nbsp;<td>'.$fields[14].'</td></tr>';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="70%">2 : Amount of rest in Last 24 hours?</th><th width="22%"> <4 = 75 &nbsp; 5 to 7 = 45 &nbsp; >8 = 0</th><th width="8%">Score</th></tr>';

	$html_weekly .= '<tr nobr="true"><td>'.$fields[15].'</td><td>&nbsp;</td><td>'.$fields[16].'</td></tr>';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="70%">3 : Any alcohol or drugs (prescription included) taken within the last 8 hours?</th><th width="22%">Yes = 100 &nbsp; No = 0</th><th width="8%">Score</th></tr>';

	$html_weekly .= '<tr nobr="true"><td>'.$fields[17].'</td><td>&nbsp;</td><td>'.$fields[18].'</td></tr>';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="70%">4 : Weather</th><th width="22%">Good = 0 &nbsp; Poor = 25 &nbsp; Ext. Poor = 50</th><th width="8%">Score</th></tr>';

	$html_weekly .= '<tr nobr="true"><td>'.$fields[19].'</td><td>&nbsp;</td><td>'.$fields[20].'</td></tr>';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="70%">5 : Road Conditions</th><th width="22%">Good = 0 &nbsp; Poor = 25 &nbsp; Ext. Poor = 50</th><th width="8%">Score</th></tr>';

	$html_weekly .= '<tr nobr="true"><td>'.$fields[21].'</td><td>&nbsp;</td><td>'.$fields[22].'</td></tr>';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="70%">6 : Time of Travel</th><th width="22%">Day Light = 0 &nbsp; Dark Light = 10 &nbsp; Down / Dusk = 50</th><th width="8%">Score</th></tr>';

	$html_weekly .= '<tr nobr="true"><td>'.$fields[23].'</td><td>&nbsp;</td><td>'.$fields[24].'</td></tr>';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="70%">7 : Approximate Driving Distance</th><th width="22%"><150 = 0 &nbsp; 150 to 300 = 5 &nbsp; >300 = 10</th><th width="8%">Score</th></tr>';

	$html_weekly .= '<tr nobr="true"><td>'.$fields[25].'</td><td>&nbsp;</td><td>'.$fields[26].'</td></tr>';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="70%">8 : Traveling as a team?</th><th width="22%">Yes = 0 &nbsp; No = 50</th><th width="8%">Score</th></tr>';

	$html_weekly .= '<tr nobr="true"><td>'.$fields[27].'</td><td>&nbsp;</td><td>'.$fields[28].'</td></tr>';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="70%">9 : Trip Type</th><th width="22%">Inbound = 10 &nbsp; Outbound = 0</th><th width="8%">Score</th></tr>';

	$html_weekly .= '<tr nobr="true"><td>'.$fields[29].'</td><td>&nbsp;</td><td>'.$fields[30].'</td></tr>';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="70%">10 : Is there a rest stop at the end of the trip? </th><th width="22%">No = 10 &nbsp; <2 Hours = 10 &nbsp; >2 = 0</th><th width="8%">Score</th></tr>';

	$html_weekly .= '<tr nobr="true"><td>'.$fields[31].'</td><td>&nbsp;</td><td>'.$fields[32].'</td></tr>';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="70%">11 : Is the vehicle safe to drive? Have you performed a Pre-Trip Inspection?</th><th width="22%">Yes = 0 &nbsp; No = 100</th><th width="8%">Score</th></tr>';

	$html_weekly .= '<tr nobr="true"><td>'.$fields[33].'</td><td>&nbsp;</td><td>'.$fields[34].'</td></tr>';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="70%">12 : Is there a Journey Plan for your trip?</th><th width="22%">Yes = 0 &nbsp; No = 100</th><th width="8%">Score</th></tr>';

	$html_weekly .= '<tr nobr="true"><td>'.$fields[35].'</td><td>&nbsp;</td><td>'.$fields[36].'</td></tr>';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="70%">13 : Are you houred out as per the Hours of Service Regulations?(Commercial drivers only)</th><th width="22%">Yes = 100 &nbsp; No = 0</th><th width="8%">Score</th></tr>';

	$html_weekly .= '<tr nobr="true"><td>'.$fields[37].'</td><td>&nbsp;</td><td>'.$fields[38].'</td></tr>';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="70%">14 : Do you know the Hazards associated with driving?</th><th width="22%">Yes = 0 &nbsp; No = 100</th><th width="8%">Score</th></tr>';

	$html_weekly .= '<tr nobr="true"><td>'.$fields[39].'</td><td>&nbsp;</td><td>'.$fields[40].'</td></tr>';

	$html_weekly .= '</table>';

//////// total Score //////////////////

	$html_weekly .= '<h3>Areas of Concern</h3>';

	$html_weekly .= '<h4>Personnel</h4>';

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';

	$html_weekly .= '<tr nobr="true"><td width="70%">Are the Employees able to do the job safely?</td><td width="30%">'.$fields[41].''.$fields[42].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Do they have the experience, skills and training to complete all aspects of the job?</td><td width="30%">'.$fields[43].''.$fields[44].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Do they have the necessary qualifications, certificates and licenses? </td><td width="30%">'.$fields[45].''.$fields[46].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Are they physically and mentally capable of doing the job?</td><td width="30%">'.$fields[47].''.$fields[48].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Have they been briefed on action plans, job hazards and job requirements?</td><td width="30%">'.$fields[49].''.$fields[50].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Have the drivers been provided maps and directions? </td><td width="30%">'.$fields[51].''.$fields[52].'</td></tr>';

	$html_weekly .= '</table>';

	$html_weekly .= '<h4>Vehicle/ Equipment</h4>';

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';

	$html_weekly .= '<tr nobr="true"><td width="70%">Are the vehicles/ equipment suitable for the job?</td><td width="30%">'.$fields[53].''.$fields[54].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Does the site require specific sIfafety equipment?</td><td width="30%">'.$fields[55].''.$fields[56].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Will they create additional hazards?</td><td width="30%">'.$fields[57].''.$fields[58].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Are they in operational condition? </td><td width="30%">'.$fields[59].''.$fields[60].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Have they been inspected and maintained properly?</td><td width="30%">'.$fields[61].''.$fields[62].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Is all of the necessary documentation available? </td><td width="30%">'.$fields[63].''.$fields[64].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Is all of the support/ emergency equipment (communication equipment, safety equipment, fire protection, personal protection, first aid, warning devices) available and in operational condition?</td><td width="30%">'.$fields[65].''.$fields[66].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Is the vehicle equipped with winches, slings or chains?</td><td width="30%">'.$fields[67].''.$fields[68].'</td></tr>';

	$html_weekly .= '</table>';

	$html_weekly .= '<h4>Schedule</h4>';

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';

	$html_weekly .= '<tr nobr="true"><td width="70%">Has enough time been allotted for loading and travel? </td><td width="30%">'.$fields[69].''.$fields[70].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Has enough time been allotted for adverse road and weather conditions? </td><td width="30%">'.$fields[71].''.$fields[72].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Has time been allotted for Weigh Station stops?</td><td width="30%">'.$fields[73].''.$fields[74].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Has consideration been given to the drivers hours of service requirements and log book status? </td><td width="30%">'.$fields[75].''.$fields[76].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Has consideration been given for rest breaks, meals, vehicle inspections and refueling? </td><td width="30%">'.$fields[77].''.$fields[78].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Has consideration been given to time of day to maximize driving in daylight and minimize driving in darkness? </td><td width="30%">'.$fields[79].''.$fields[80].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Have departure times, estimated arrival times and routes been communicated and confirmed by all team members?</td><td width="30%">'.$fields[81].''.$fields[82].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Are all involved capable of making a safe return trip?</td><td width="30%">'.$fields[83].''.$fields[84].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Has adequate time been allowed for required maintenance between trips? </td><td width="30%">'.$fields[85].''.$fields[86].'</td></tr>';

	$html_weekly .= '</table>';


	$html_weekly .= '<h4>Route</h4>';

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';

	$html_weekly .= '<tr nobr="true"><td width="70%">Are up to date maps available to facilitate appropriate routes? </td><td width="30%">'.$fields[87].''.$fields[88].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Has the route been carefully planned to eliminate unsuitable roads?</td><td width="30%">'.$fields[89].''.$fields[90].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Are selected routes compatible with the vehicle and load being transported? </td><td width="30%">'.$fields[91].''.$fields[92].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Do selected routes compromise Company policy, procedures or any legislation? </td><td width="30%">'.$fields[93].''.$fields[94].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Have you ascertained the road type?</td><td width="30%">'.$fields[95].''.$fields[96].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Have you communicated accurate site and location directions to each driver? </td><td width="30%">'.$fields[97].''.$fields[98].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Have you communicated road hazards and precautions to each driver? </td><td width="30%">'.$fields[99].''.$fields[100].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Have you considered and communicated convoy procedures?</td><td width="30%">'.$fields[101].''.$fields[102].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Have dangerous goods (T.D.G.) routes been considered and selected if applicable? </td><td width="30%">'.$fields[103].''.$fields[104].'</td></tr>';

	$html_weekly .= '</table>';

	$html_weekly .= '<h4>Potential Journey Hazards</h4>';

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';

	$html_weekly .= '<tr nobr="true"><td width="70%">Road conditions (rain, mud, snow, icy, construction) </td><td width="30%">'.$fields[105].''.$fields[106].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Driver fatigue</td><td width="30%">'.$fields[107].''.$fields[108].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Weather conditions</td><td width="30%">'.$fields[109].''.$fields[110].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Visibility/ vision (fog, smoke, dust)</td><td width="30%">'.$fields[111].''.$fields[112].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Unusual load characteristics </td><td width="30%">'.$fields[113].''.$fields[114].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Traffic </td><td width="30%">'.$fields[115].''.$fields[116].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Equipment condition </td><td width="30%">'.$fields[117].''.$fields[118].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Other potential hazards (List):</td><td width="30%">'.$fields[119].''.$fields[120].'</td></tr>';

	$html_weekly .= '</table>';

	$html_weekly .= '<h4>Emergency Response Planning</h4>';

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';

	$html_weekly .= '<tr nobr="true"><td width="70%">Have hazards and controls been identified? </td><td width="30%">'.$fields[121].''.$fields[122].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Are drivers and passengers trained in injury, incident or emergency response?</td><td width="30%">'.$fields[123].''.$fields[124].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Are environmental spill kits required and available? </td><td width="30%">'.$fields[125].''.$fields[126].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Is other emergency response equipment available (survival kit)?</td><td width="30%">'.$fields[127].''.$fields[128].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Is emergency contact information available? </td><td width="30%">'.$fields[129].''.$fields[130].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Is an adequate communication system available?</td><td width="30%">'.$fields[131].''.$fields[132].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td width="70%">Are drivers and passengers trained in first aid? </td><td width="30%">'.$fields[133].''.$fields[134].'</td></tr>';

	$html_weekly .= '</table>';

	$html_weekly .= '<br>List corrective actions identified above<br> '.html_entity_decode($desc1).'<br>';

	$sa = mysqli_query($dbc, "SELECT * FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");

    $html_weekly .= '<br><br><table border="1px" style="padding:3px; border:1px solid black;">';
    $html_weekly .= '<tr nobr="true">
        <th>Name</th>
        <th>Signature</th>
        </tr>';

    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];
        $staffcheck = $row_sa['staffcheck'];

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">' . $row_sa['assign_staff'] . '</td>';

        // avs_near_miss = form name

        $html_weekly .= '<td data-title="Email"><img src="journey_management_trip_tracking/download/safety_'.$assign_staff_id.'.png" width="150" height="70" border="0" alt=""></td>';
        $html_weekly .= '</tr>';
    }
    $html_weekly .= '</table>';

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    // avs_near_miss = form name
    $pdf->Output('journey_management_trip_tracking/download/hazard_'.$fieldlevelriskid.'.pdf', 'F');

    $sa = mysqli_query($dbc, "SELECT safetyattid FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");
    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];

        // avs_near_miss = form name
        unlink("journey_management_trip_tracking/download/safety_".$assign_staff_id.".png");
    }
    echo '';
}
?>