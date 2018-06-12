<?php
	function employee_driver_information_form_pdf($dbc,$hrid, $fieldlevelriskid) {

	$tab = get_hr($dbc, $hrid, 'tab');
    $form = get_hr($dbc, $hrid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_hr WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';

    DEFINE('PDF_LOGO', $get_field_config['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));

	//$result_update_employee = mysqli_query($dbc, "UPDATE `hr_employee_driver_information_form` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_employee_driver_information_form WHERE fieldlevelriskid='$fieldlevelriskid'"));
	$today_date = date('Y-m-d');
    $contactid = $_SESSION['contactid'];
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
        $pdf->SetMargins(PDF_MARGIN_LEFT, 45, PDF_MARGIN_RIGHT);
    } else {
        $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
    }
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, 40);

    $pdf->AddPage();
    $pdf->setCellHeightRatio(1.6);
    $pdf->SetFont('helvetica', '', 9);

	$html .= '<h2>Employee Driver Information Form</h2>'; // Form nu heading

	$html .= '<table border="1px" style="padding:3px; border:1px solid black;">';

    if (strpos(','.$form_config.',', ',fields1,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Name</th>
            <td width="70%">'.$fields[0].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields2,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Drivers Licence Number</th>
            <td width="70%">'.$fields[1].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields3,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Expiry Date</th>
            <td width="70%">'.$fields[2].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields4,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Class</th>
            <td width="70%">'.$fields[3].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields5,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Can you drive a truck with a standard transmission?</th>
            <td width="70%">'.$fields[4].'<br>'.$fields[5].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields6,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Do you have a current TDG Ticket?</th>
            <td width="70%">'.$fields[6].'<br>'.$fields[7].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">TDG expiry Date</th>
            <td width="70%">'.$fields[8].'</td></tr>';
    }

	$html .= '</table>';

	$html .= '<br><br>As an employee of the Company you are to follow all laws of the road. If at any time Company representatives feel that you are not operating equipment in a safe and courteous manner, the Company reserves the right to suspend you from operating any or all equipment.';

	$html .= '<h3>Vehicle Policy</h3>'; // Form nu heading

	$html .= 'The Company provides certain employees with vehicles in order to carry out the duties and functions related to their positions. The use of a Company vehicle is subject to conditions and restrictions, which must be observed by all employees assigned to operate a Company vehicle.<br>';

	$html .= 'When you operate a Company vehicle, you accept certain responsibilities to yourself, to the Company, and to the public. Our name and reputation rides with you. It is expected that you will drive with care at all times using common sense and good judgement.<br>';

	$html .= 'The Company is committed to ensuring that all vehicles provided for the use of employees are in good working order, operated in a safe manner.<br>';

	$html .= "All Company employees eligible for vehicle assignment must be in possession of a valid driver's license in good standing. The driver's license must be of the appropriate class and free of limiting restrictions with respect to the vehicle assigned to the employee. A copy of the driver's license and abstract is required for insurance purpose.<br>";

	$html .= 'All Company vehicles must be operated in a safe and responsible manner in accordance with applicable traffic laws and regulations. The employee must ensure the vehicle is in proper working order. This includes regular and required maintenance.<br>';

	$html .= 'Employees are responsible for all fines and tickets resulting from the operation of the Company vehicle to with they are assigned or operating. This includes any moving violations, photo radar tickets, parking tickets, or any other violations of applicable traffic laws and regulations.<br>';

	$html .= 'The operation of any Company vehicle while the driver is under the influence of alcohol or intoxicating drugs is prohibited.<br>';

	$html .= 'All persons in a Company vehicle must wear seat belts at all times.<br>';

	$html .= 'Failure to comply with the Company policy may result in disciplinary action, up to and including dismissal.';

	$html .= '<h3>Personal Use</h3>';

	$html .= "<br>Personal use of company vehicles shall be limited to employee to which the vehicle is assigned. The company may permit, at the discretion of the company and with prior notification to the employee, immediate members of the employee's family to operate the vehicle subject to the conditions of this policy and other policies of the company.<br>";

	$html .= 'At no time shall a company vehicle be operated by an individual under the age of twenty-one(21) years of age.<br>';

	$html .= "The employee's responsibilities regarding the use and operation of the company vehicle under this policy extends to any personal use of the company vehicle.<br>";

	$html .= '<br><img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;Information which I have provided here is true and correct. I have read and understand the content of this policy.<br>';

    $html .= 'Date : '.date('Y-m-d').'<br>';
    $html .= 'Person : '.decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);

    $html .= '<img src="employee_driver_information_form/download/hr_'.$_SESSION['contactid'].'.png" width="150" height="70" border="0" alt="">';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('employee_driver_information_form/download/hr_'.$fieldlevelriskid.'.pdf', 'F');
    echo '<script type="text/javascript" language="Javascript">window.location.replace("?tile_name='.$tile.'");
    window.open("employee_driver_information_form/download/hr_'.$fieldlevelriskid.'.pdf", "fullscreen=yes");
    </script>';

    unlink("employee_driver_information_form/download/hr_".$_SESSION['contactid'].".png");

    echo '';
}
?>






