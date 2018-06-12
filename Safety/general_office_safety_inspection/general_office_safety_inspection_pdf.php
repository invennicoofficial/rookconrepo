<?php
	function general_office_safety_inspection_pdf($dbc,$safetyid, $fieldlevelriskid) {
    $form_by = $_SESSION['contactid'];
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_general_office_safety_inspection WHERE fieldlevelriskid='$fieldlevelriskid'"));

	$tab = get_safety($dbc, $safetyid, 'tab');
    $form = get_safety($dbc, $safetyid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';
	$get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pdf_logo FROM field_config_safety WHERE tab='$tab' AND form='$form'"));

    DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));
	$result_update_employee = mysqli_query($dbc, "UPDATE `safety_general_office_safety_inspection` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
	$desc = $get_field_level['desc'];
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

	$html_weekly = '<h2>General Office Safety Inspection</h2>'; // Form nu heading

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="20%">Date of Assessment</th><th width="30%">Performed By</th><th width="50%">Company Name</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$today_date.'</td><td>'.$fields[0].'</td><td>'.$fields[1].'</td></tr>';

    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="50%">Address</th><th width="50%">Location of Assessment</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[2].'</td><td>'.$fields[3].'</td></tr>
            </table>';

	$html_weekly .= '<br>Assessment Team - Names, Positions <br> '.html_entity_decode($desc).'<br>';

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="40%">Safety Program</th><th width="20%">Okay/Action Required</th><th width="40%">Description</th></tr>';

    $html_weekly .= '<tr nobr="true">
    <td colspan="3"><b>Company Safety Policy</b></td></tr>';

    $html_weekly .= '<tr nobr="true">
            <td>Current</td><td>'.$fields[4].'</td><td>'.$fields[5].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Dated</td><td>'.$fields[5].'</td><td>'.$fields[6].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Signed</td><td>'.$fields[7].'</td><td>'.$fields[8].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Posted</td><td>'.$fields[9].'</td><td>'.$fields[10].'</td></tr>';

    $html_weekly .= '<tr nobr="true">
            <td colspan="3"><b>Company Safety Program Manual</b></td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Current</td><td>'.$fields[11].'</td><td>'.$fields[12].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Available</td><td>'.$fields[13].'</td><td>'.$fields[14].'</td></tr>';

    $html_weekly .= '<tr nobr="true">
            <td colspan="3"><b>Safe Work Practices</b></td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>In Place</td><td>'.$fields[15].'</td><td>'.$fields[16].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>At Field Locations</td><td>'.$fields[17].'</td><td>'.$fields[18].'</td></tr>';

    $html_weekly .= '<tr nobr="true">
            <td colspan="3"><b>Copies Of OH&S Act And Regulations Available</b></td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>At Office</td><td>'.$fields[19].'</td><td>'.$fields[20].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>At Field Locations</td><td>'.$fields[21].'</td><td>'.$fields[22].'</td></tr>';

    $html_weekly .= '<tr nobr="true">
            <td colspan="3"><b>Inspections</b></td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Policy in Place</td><td>'.$fields[23].'</td><td>'.$fields[24].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Being Done Regularly</td><td>'.$fields[25].'</td><td>'.$fields[26].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Records Available</td><td>'.$fields[27].'</td><td>'.$fields[28].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Corrective Actions Complete From Last</td><td>'.$fields[29].'</td><td>'.$fields[30].'</td></tr>';

    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="40%">Walkways/Flooring</th><th width="20%">Okay/Action Required</th><th width="40%">Description</th></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Free From Debris</td><td>'.$fields[31].'</td><td>'.$fields[32].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Slips/Trips</td><td>'.$fields[33].'</td><td>'.$fields[34].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Walkways clear</td><td>'.$fields[35].'</td><td>'.$fields[36].'</td></tr>';


    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="40%">Emergency Response</th><th width="20%">Okay/Action Required</th><th width="40%">Description</th></tr>';

    $html_weekly .= '<tr nobr="true">
            <td colspan="3"><b>Site Specific</b></td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Response Plan Posted</td><td>'.$fields[37].'</td><td>'.$fields[38].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Muster Points Identified</td><td>'.$fields[39].'</td><td>'.$fields[40].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Emergency Phone List Posted</td><td>'.$fields[41].'</td><td>'.$fields[42].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Site Map Posted/Egress Routes Identified</td><td>'.$fields[43].'</td><td>'.$fields[44].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Fire Response Plan</td><td>'.$fields[45].'</td><td>'.$fields[46].'</td></tr>';

    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="40%">First Aid</th><th width="20%">Okay/Action Required</th><th width="40%">Description</th></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Facilities</td><td>'.$fields[47].'</td><td>'.$fields[48].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Supplies</td><td>'.$fields[49].'</td><td>'.$fields[50].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>List Of Personnel Trained</td><td>'.$fields[51].'</td><td>'.$fields[52].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Eye Wash Station</td><td>'.$fields[53].'</td><td>'.$fields[54].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td colspan="3"><b>Emergency Services Availability</b></td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Are emergency numbers posted?</td><td>'.$fields[55].'</td><td>'.$fields[56].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Does every employee know how to get help?</td><td>'.$fields[57].'</td><td>'.$fields[58].'</td></tr>';

    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="40%">Fire Prevention</th><th width="20%">Okay/Action Required</th><th width="40%">Description</th></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Smoking / No Smoking Rules</td><td>'.$fields[59].'</td><td>'.$fields[60].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Fire Extinguishers</td><td>'.$fields[61].'</td><td>'.$fields[62].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>On Vehicles</td><td>'.$fields[63].'</td><td>'.$fields[64].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>On Equipment</td><td>'.$fields[65].'</td><td>'.$fields[66].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>In Buildings</td><td>'.$fields[67].'</td><td>'.$fields[68].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>All Personnel Trained in Their Use</td><td>'.$fields[69].'</td><td>'.$fields[70].'</td></tr>';

    $html_weekly .= '<tr nobr="true">
            <td colspan="3"><b>Fire Department Assistance</b></td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Does every employee know how to get help?</td><td>'.$fields[71].'</td><td>'.$fields[72].'</td></tr>';

    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="40%">Toilet/Wash Facilities</th><th width="20%">Okay/Action Required</th><th width="40%">Description</th></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Required Facilities Based On # Of Workes</td><td>'.$fields[73].'</td><td>'.$fields[74].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Maintained</td><td>'.$fields[75].'</td><td>'.$fields[76].'</td></tr>';

    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="40%">Personal Protective Equip</th><th width="20%">Okay/Action Required</th><th width="40%">Description</th></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>P.P.E. Policy / Rules In Place</td><td>'.$fields[77].'</td><td>'.$fields[78].'</td></tr>';

    $html_weekly .= '<tr nobr="true">
            <td colspan="3"><b>Basic P.P.E. In Use</b></td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Hard Hats</td><td>'.$fields[79].'</td><td>'.$fields[80].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Safety Glasses</td><td>'.$fields[81].'</td><td>'.$fields[82].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Safety Boots</td><td>'.$fields[83].'</td><td>'.$fields[84].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Hearing Protection</td><td>'.$fields[85].'</td><td>'.$fields[86].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Coveralls/Long Sleeve Shirts</td><td>'.$fields[87].'</td><td>'.$fields[88].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Gloves</td><td>'.$fields[89].'</td><td>'.$fields[90].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Other</td><td>'.$fields[91].'</td><td>'.$fields[92].'</td></tr>';

    $html_weekly .= '<tr nobr="true">
            <td colspan="3"><b>Specialized P.P.E. Available</b></td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Respirators</td><td>'.$fields[93].'</td><td>'.$fields[94].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Fall Arresting Equipment</td><td>'.$fields[95].'</td><td>'.$fields[96].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Face Shields / Goggles</td><td>'.$fields[97].'</td><td>'.$fields[98].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Other</td><td>'.$fields[99].'</td><td>'.$fields[100].'</td></tr>';

    $html_weekly .= '<tr nobr="true">
            <td colspan="3"><b>Atmospheric Monitors</b></td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Bump Tests Performed</td><td>'.$fields[101].'</td><td>'.$fields[102].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Calibrations Up To Date</td><td>'.$fields[103].'</td><td>'.$fields[104].'</td></tr>';

    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="40%">Buildings</th><th width="20%">Okay/Action Required</th><th width="40%">Description</th></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Lighting</td><td>'.$fields[105].'</td><td>'.$fields[106].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Emergency Lighting</td><td>'.$fields[107].'</td><td>'.$fields[108].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Ventilation</td><td>'.$fields[109].'</td><td>'.$fields[110].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Heating</td><td>'.$fields[111].'</td><td>'.$fields[112].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Access / Egress</td><td>'.$fields[113].'</td><td>'.$fields[114].'</td></tr>';

    $html_weekly .= '<tr nobr="true">
            <td colspan="3"><b>Trailers/Office</b></td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Stairs</td><td>'.$fields[115].'</td><td>'.$fields[116].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Fire Extinguishers</td><td>'.$fields[117].'</td><td>'.$fields[118].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Blocking</td><td>'.$fields[119].'</td><td>'.$fields[120].'</td></tr>';

    $html_weekly .= '<tr nobr="true">
            <td colspan="3"><b>Facilities</b></td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Lunchrooms</td><td>'.$fields[121].'</td><td>'.$fields[122].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Washrooms</td><td>'.$fields[123].'</td><td>'.$fields[124].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Changerooms</td><td>'.$fields[125].'</td><td>'.$fields[126].'</td></tr>';


    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="40%">Chemicals</th><th width="20%">Okay/Action Required</th><th width="40%">Description</th></tr>';
    $html_weekly .= '<tr nobr="true">
            <td colspan="3"><b>WHMIS</b></td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>MSDS</td><td>'.$fields[127].'</td><td>'.$fields[128].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Supplier Labels Visible</td><td>'.$fields[129].'</td><td>'.$fields[130].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Workplace Labels</td><td>'.$fields[131].'</td><td>'.$fields[132].'</td></tr>';

    $html_weekly .= '<tr nobr="true">
            <td colspan="3"><b>Transportation Of Dangerous Goods</b></td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Employees Trained Where Required</td><td>'.$fields[133].'</td><td>'.$fields[134].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Emergency Response Procedure in Place</td><td>'.$fields[135].'</td><td>'.$fields[136].'</td></tr>';

    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="40%">Equipment</th><th width="20%">Okay/Action Required</th><th width="40%">Description</th></tr>';
    $html_weekly .= '<tr nobr="true">
            <td colspan="3"><b>Mobile Equipment</b></td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Maintenance Procedures</td><td>'.$fields[137].'</td><td>'.$fields[138].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Log Book Current</td><td>'.$fields[139].'</td><td>'.$fields[140].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Pre Use Inspections Completed</td><td>'.$fields[141].'</td><td>'.$fields[142].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Operator Compitent</td><td>'.$fields[143].'</td><td>'.$fields[144].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Fire Extinguisher</td><td>'.$fields[145].'</td><td>'.$fields[146].'</td></tr>';

    $html_weekly .= '<tr nobr="true">
            <td colspan="3"><b>Vehicles</b></td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Proper Maintenance</td><td>'.$fields[147].'</td><td>'.$fields[148].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Insurance Papers/Registration</td><td>'.$fields[149].'</td><td>'.$fields[150].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Fire Extinguisher/First Aid Kit</td><td>'.$fields[151].'</td><td>'.$fields[152].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Loads Secured</td><td>'.$fields[153].'</td><td>'.$fields[154].'</td></tr>';

    $html_weekly .= '<tr nobr="true">
            <td colspan="3"><b>Power Tools</b></td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>In Good Shape</td><td>'.$fields[155].'</td><td>'.$fields[156].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>All Guards in Place</td><td>'.$fields[157].'</td><td>'.$fields[158].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Maintenance Program Followed</td><td>'.$fields[159].'</td><td>'.$fields[160].'</td></tr>';

    $html_weekly .= '<tr nobr="true">
            <td colspan="3"><b>Hand Tool</b></td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Regular Inspection & Maintenance</td><td>'.$fields[161].'</td><td>'.$fields[162].'</td></tr>';

    $html_weekly .= '<tr nobr="true">
            <td colspan="3"><b>Scaffolding</b></td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Erected by Qualified Personnel</td><td>'.$fields[163].'</td><td>'.$fields[164].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Inspected Before Use</td><td>'.$fields[165].'</td><td>'.$fields[166].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Meet Regulations</td><td>'.$fields[167].'</td><td>'.$fields[168].'</td></tr>';

    $html_weekly .= '<tr nobr="true">
            <td colspan="3"><b>Ladders</b></td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>In Good Repair</td><td>'.$fields[169].'</td><td>'.$fields[170].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Inspection Program in Place</td><td>'.$fields[171].'</td><td>'.$fields[172].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Tag Out of Service for Damage</td><td>'.$fields[173].'</td><td>'.$fields[174].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Correct Use</td><td>'.$fields[175].'</td><td>'.$fields[176].'</td></tr>';

    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="40%">Electricity</th><th width="20%">Okay/Action Required</th><th width="40%">Description</th></tr>';
    $html_weekly .= '<tr nobr="true">
            <td colspan="3"><b>Overhead Power Lines</b></td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Marked Where Required</td><td>'.$fields[177].'</td><td>'.$fields[178].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Workers Trained in Clearances</td><td>'.$fields[179].'</td><td>'.$fields[180].'</td></tr>';

    $html_weekly .= '<tr nobr="true">
            <td colspan="3"><b>Explosion Proof Fixtures</b></td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Are they required?</td><td>'.$fields[181].'</td><td>'.$fields[182].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Are they maintained?</td><td>'.$fields[183].'</td><td>'.$fields[184].'</td></tr>';

    $html_weekly .= '<tr nobr="true">
            <td colspan="3"><b>Extension Cords</b></td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Three Conductor</td><td>'.$fields[185].'</td><td>'.$fields[186].'</td></tr>';
    $html_weekly .= '<tr nobr="true">
            <td>Strung out of the Way</td><td>'.$fields[187].'</td><td>'.$fields[188].'</td></tr>
            </table>';

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

        $html_weekly .= '<td data-title="Email"><img src="general_office_safety_inspection/download/safety_'.$assign_staff_id.'.png" width="150" height="70" border="0" alt=""></td>';
        $html_weekly .= '</tr>';
    }
    $html_weekly .= '</table>';

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    // avs_near_miss = form name
    $pdf->Output('general_office_safety_inspection/download/hazard_'.$fieldlevelriskid.'.pdf', 'F');

    $sa = mysqli_query($dbc, "SELECT safetyattid FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");
    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];

        // avs_near_miss = form name
        unlink("general_office_safety_inspection/download/safety_".$assign_staff_id.".png");
    }
    echo '';
}
?>