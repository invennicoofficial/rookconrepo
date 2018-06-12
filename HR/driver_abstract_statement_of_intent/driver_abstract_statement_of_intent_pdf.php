<?php
function driver_abstract_statement_of_intent($dbc,$hrid, $fieldlevelriskid) {
    $tab = get_hr($dbc, $hrid, 'tab');
    $form = get_hr($dbc, $hrid, 'form');

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_hr WHERE tab='$tab' AND form='$form'"));
    $hr_description = $get_field_config['hr_description'];
    $config_extra_fields = explode('**FFM**',$get_field_config['config_extra_fields']);

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_driver_abstract_statement_of_intent WHERE fieldlevelriskid='$fieldlevelriskid'"));
	$today_date = date('Y-m-d');
    $contactid = $_SESSION['contactid'];
    $fields = explode('**FFM**', $get_field_level['fields']);

    DEFINE('PDF_LOGO', $get_field_config['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));

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

	$field_15_1 = '';
	$field_15_2 = '';
	$field_15_3 = '';
	$field_16_1 = '';
	$field_16_2 = '';
	$field_16_3 = '';
	$field_18_1 = '';
	$field_18_2 = '';
	$field_18_3 = '';
	$field_18_4 = '';


	if($fields[15] == 1)
		$field_15_1 = '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
	if($fields[15] == 2)
		$field_15_2 = '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
	if($fields[15] == 3)
		$field_15_3 = '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';

	if($fields[16] == 1)
		$field_16_1 = '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
	if($fields[16] == 2)
		$field_16_2 = '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
	if($fields[16] == 3)
		$field_16_3 = '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';

	if($fields[18] == 1)
		$field_18_1 = '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
	if($fields[18] == 2)
		$field_18_2 = '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
	if($fields[18] == 3)
		$field_18_3 = '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
	if($fields[18] == 4)
		$field_18_4 = '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';

    $html = '<table style="width:100%">
				<tr>
					<td style="width:20%">
						<b>Government Of <br>Alberta</b>
					</td>
					<td style="width:47%">
						&nbsp;
					</td>
					<td style="width:40%">
						<b> Driver Abstract Statement of Intent </b>
					</td>
				</tr>
			</table>
<br>
<table style="border:1px solid black;border-collapse:collapse;width:100%;height:2px"><tr></tr></table>
<br>
<div style="padding:5px 0" ><b>This form is to be completed If a driver&#39;s abstract of person is being received by someone other than that person. A &quot;Driver abstract&quot; is a product name under which the alberta government releases specific information for a person&#39;s driving record which contain. </div>
<br>
<table style="border:1px solid black;border-collapse:collapse;width:130%">
  <tr>
    <th style="border:1px solid black;border-collapse:collapse;width:15%;padding:5px 10px;height:10px"><b>Name</b></th>
    <th style="border:1px solid black;border-collapse:collapse;width:15%;padding:5px 10px;height:10px"><b>Height</b></th>
	<th style="border:1px solid black;border-collapse:collapse;width:15%;padding:5px 10px;height:10px"><b>Class</b></th>
	<th style="border:1px solid black;border-collapse:collapse;width:15%;padding:5px 10px;height:10px"><b>Licence Number</b></th>
	<th style="border:1px solid black;border-collapse:collapse;width:15%;padding:5px 10px;height:10px"><b>Expiration Date</b></th>
  </tr>
   <tr>
    <td style="border:1px solid black;border-collapse:collapse;width:15%;padding:5px 10px;height:10px">'.$fields[0].'</td>
    <td style="border:1px solid black;border-collapse:collapse;width:15%;padding:5px 10px;height:10px">'.$fields[1].'</td>
	<td style="border:1px solid black;border-collapse:collapse;width:15%;padding:5px 10px;height:10px">'.$fields[2].'</td>
	<td style="border:1px solid black;border-collapse:collapse;width:15%;padding:5px 10px;height:10px">'.$fields[3].'</td>
	<td style="border:1px solid black;border-collapse:collapse;width:15%;padding:5px 10px;height:10px">'.$fields[4].'</td>
  </tr>
  <tr>
    <th style="border:1px solid black;border-collapse:collapse;width:15%;padding:5px 10px;height:10px"><b>Address</b></th>
    <th style="border:1px solid black;border-collapse:collapse;width:15%;padding:5px 10px;height:10px"><b>Weight</b></th>
	<th style="border:1px solid black;border-collapse:collapse;width:15%;padding:5px 10px;height:10px"><b>Issue Date</b></th>
	<th style="border:1px solid black;border-collapse:collapse;width:15%;padding:5px 10px;height:10px"><b>Current Demerit Points</b></th>
	<th style="border:1px solid black;border-collapse:collapse;width:15%;padding:5px 10px;height:10px"><b>Reinstatement Conditions (if any)</b></th>
  </tr>
  <tr>
    <td style="border:1px solid black;border-collapse:collapse;width:15%;padding:5px 10px;height:10px">'.$fields[5].'</td>
    <td style="border:1px solid black;border-collapse:collapse;width:15%;padding:5px 10px;height:10px">'.$fields[6].'</td>
	<td style="border:1px solid black;border-collapse:collapse;width:15%;padding:5px 10px;height:10px">'.$fields[7].'</td>
	<td style="border:1px solid black;border-collapse:collapse;width:15%;padding:5px 10px;height:10px">'.$fields[8].'</td>
	<td style="border:1px solid black;border-collapse:collapse;width:15%;padding:5px 10px;height:10px">'.$fields[9].'</td>
  </tr>
  <tr>
    <th style="border:1px solid black;border-collapse:collapse;width:15%;padding:5px 10px;height:10px"><b>Date of Birth</b></th>
    <th style="border:1px solid black;border-collapse:collapse;width:15%;padding:5px 10px;height:10px"><b>Sex</b></th>
	<th style="border:1px solid black;border-collapse:collapse;width:15%;padding:5px 10px;height:10px"><b>MVID Number</b></th>
	<th style="border:1px solid black;border-collapse:collapse;width:15%;padding:5px 10px;height:10px" colspan="2"><b>Suspended Status</b></th>
  </tr>
  <tr>
    <td style="border:1px solid black;border-collapse:collapse;width:15%;padding:5px 10px;height:10px">'.$fields[10].'</td>
    <td style="border:1px solid black;border-collapse:collapse;width:15%;padding:5px 10px;height:10px">'.$fields[11].'</td>
	<td style="border:1px solid black;border-collapse:collapse;width:15%;padding:5px 10px;height:10px">'.$fields[12].'</td>
	<td style="border:1px solid black;border-collapse:collapse;width:15%;padding:5px 10px;height:10px" colspan="2">'.$fields[13].'</td>
  </tr>
</table>
<br><br>
<b> List of violation (Description , demerit / merit points and suspension Term) </b>
<br><br>
<b> A Commercial driver abstract (CDA) includes commercial vehicle safety Alliance inspection (CVSA) information and all of the above information with the exception of date of birth , height, weight ,sex. </b>
<br>
<table style="width:100%">
	<tr>
		<td style="width:15%"> I/We, </td>
		<td style="width:65%;text-align:center"> ' . $fields[23] . ' </td>
	</tr>
	<tr>
		<td style="width:15%"> &nbsp; </td>
		<td style="width:65%;text-align:center"> <hr> </td>
	</tr>
	<tr>
		<td style="width:15%"> &nbsp; </td>
		<td style="width:65%;text-align:center"> Name of the person / organization requesting the driver&#39;s abstract </td>
	</tr>
</table>

<br><br>

<table style="width:100%">
	<tr>
		<td style="width:15%"> of </td>
		<td style="width:65%;text-align:center"> ' . $fields[24] . ' </td>
	</tr>
	<tr>
		<td style="width:15%"> &nbsp; </td>
		<td style="width:65%;text-align:center"> <hr> </td>
	</tr>
	<tr>
		<td style="width:15%"> &nbsp; </td>
		<td style="width:65%;text-align:center"> Full Address </td>
	</tr>
</table>

<br>
<div><b>solemnly declare that I/We have received permission to request the : </b></div>
'.$field_15_1. ' 3 Year,    '.$field_15_2.' 5 Year,    '.$field_15_3.' 10 Year Driver Abstract (SDA)<br>
'.$field_16_1. '3 Year,    '.$field_16_2.' 5 Year,    '.$field_16_3.' 10 Year Commercial Driver Abstract (CDA)<br><br>

<table style="width:100%">
	<tr>
		<td style="width:15%"> of </td>
		<td style="width:65%;text-align:center"> ' . $fields[17] . ' </td>
	</tr>
	<tr>
		<td style="width:15%"> &nbsp; </td>
		<td style="width:65%;text-align:center"> <hr> </td>
	</tr>
	<tr>
		<td style="width:15%"> &nbsp; </td>
		<td style="width:65%;text-align:center"> Name of the person whose driver&#39;s abstract is being requested </td>
	</tr>
</table>

<p> In accordance with alberta motor vehicle information regular (AMVIR)(choose one of the following subsections): </p>

<table style="width:100%">
	<tr>
		<td style="width:80%" colspan="2"> '.$field_18_1.' <b>5(1)(a) driver&#39;s abstract released to someone known to that person </b> </td>
	</tr>
	<tr>
		<td style="width:5%"> &nbsp; </td>
		<td style="width:75%;text-align:left"> <b> I solemnly declare that: </b> </td>
	</tr>
	<tr>
		<td style="width:10%"> &nbsp; </td>
		<td style="width:75%;text-align:left"> I have received valid written consent. </td>
	</tr>
	<tr>
		<td style="width:10%"> &nbsp; </td>
		<td style="width:75%;text-align:left"> &nbsp; </td>
	</tr>
	<tr>
		<td style="width:10%"> &nbsp; </td>
		<td style="width:75%;text-align:left"> the person is personally known to me and I am receiving driver&#39;s abstract only to transfer it to that person after receiving the driver&#39;s abstract I am responsible for it. </td>
	</tr>
	<tr>
		<td style="width:10%"> &nbsp; </td>
		<td style="width:75%;text-align:left"> &nbsp; </td>
	</tr>
	<tr>
		<td style="width:10%"> &nbsp; </td>
		<td style="width:75%;text-align:left"> I am not acting as agent or employer or any person in this transaction, and that I am not compensated in any manner for receiving or transferring the driver&#39;s abstract to the person. </td>
	</tr>
</table>
<br><br>
<table style="width:100%">
	<tr>
		<td style="width:80%" colspan="2"> '.$field_18_2.' <b>5(1)(a) driver&#39;s abstract released to someone known to that person </b> </td>
	</tr>
	<tr>
		<td style="width:5%"> &nbsp; </td>
		<td style="width:75%;text-align:left"> <b> I/we solemnly declare that: </b> </td>
	</tr>
	<tr>
		<td style="width:10%"> &nbsp; </td>
		<td style="width:75%;text-align:left"> Valid written consent has been received </td>
	</tr>
	<tr>
		<td style="width:10%"> &nbsp; </td>
		<td style="width:75%;text-align:left"> the driver&#39;s abstract will be use for employment purpose only </td>
	</tr>
	<tr>
		<td style="width:10%"> &nbsp; </td>
		<td style="width:75%;text-align:left"> after receiving the driver&#39;s abstract I am fully responsible for it </td>
	</tr>
</table>
<br><br>
<table style="width:100%">
	<tr>
		<td style="width:80%" colspan="2"> '.$field_18_3.' <b>5(1)(b)(iv) Driver&#39;s abstract released to the parents or guardian of a minor </b>  </td>
	</tr>
	<tr>
		<td style="width:5%"> &nbsp; </td>
		<td style="width:75%;text-align:left">Consent is not required. </td>
	</tr>
</table>
<br><br>
<table style="width:100%">
	<tr>
		<td style="width:80%" colspan="2"> '.$field_18_4.' <b>5(1)(b)(v) Driver&#39;s abstract released to a lawyer representing the driver </b> </td>
	</tr>
	<tr>
		<td style="width:5%"> &nbsp; </td>
		<td style="width:75%;text-align:left"> <b> I/We solemnly declare that: </b> </td>
	</tr>
	<tr>
		<td style="width:10%"> &nbsp; </td>
		<td style="width:75%;text-align:left"> Valid written consent has been received </td>
	</tr>
	<tr>
		<td style="width:10%"> &nbsp; </td>
		<td style="width:75%;text-align:left"> the driver&#39;s abstract will be use for employment purpose only </td>
	</tr>
	<tr>
		<td style="width:10%"> &nbsp; </td>
		<td style="width:75%;text-align:left"> after receiving the driver&#39;s abstract I am fully responsible for it </td>
	</tr>
</table>
<br><br>
<p> I/We agree that alberta Registries and/or the registry agent are not liable for any damages or losses however caused ,in respect to any defect , error or omission in the driver&#39;s abstract, or use of the driver&#39;s abstract. </p>
<table style="border:1px solid black;border-collapse:collapse;width:100%">
  <tr>
    <th style="border:1px solid black;border-collapse:collapse;width:32%;padding:5px 10px;height:10px"><b>Signature of the authorized individual</b></th>
    <th style="border:1px solid black;border-collapse:collapse;width:32%;padding:5px 10px;height:10px"><b>City/Town/Village</b></th>
	<th style="border:1px solid black;border-collapse:collapse;width:32%;padding:5px 10px;height:10px"><b>Province/State</b></th>
  </tr>
   <tr>
    <td style="border:1px solid black;border-collapse:collapse;width:32%;padding:5px 10px;height:10px"><img src="driver_abstract_statement_of_intent/download/hr_'.$_SESSION['contactid'].'.png" width="150" height="70" border="0" alt=""></td>
    <td style="border:1px solid black;border-collapse:collapse;width:32%;padding:5px 10px;height:10px">'.$fields[19].'</td>
	<td style="border:1px solid black;border-collapse:collapse;width:32%;padding:5px 10px;height:10px">'.$fields[20].'</td>
  </tr>
  <tr>
    <th style="border:1px solid black;border-collapse:collapse;width:32%;padding:5px 10px;height:10px"><b>Date</b></th>
    <th style="border:1px solid black;border-collapse:collapse;width:32%;padding:5px 10px;height:10px"><b>Name of Witness (PRINT)</b></th>
	<th style="border:1px solid black;border-collapse:collapse;width:32%;padding:5px 10px;height:10px"><b>Signature of Witness</b></th>
  </tr>
   <tr>
    <td style="border:1px solid black;border-collapse:collapse;width:32%;padding:5px 10px;height:10px">'.$fields[21].'</td>
    <td style="border:1px solid black;border-collapse:collapse;width:32%;padding:5px 10px;height:10px">'.$fields[22].'</td>
	<td style="border:1px solid black;border-collapse:collapse;width:32%;padding:5px 10px;height:10px"><img src="driver_abstract_statement_of_intent/download/hr_'.$_SESSION['contactid'].'_sign2_.png" width="150" height="70" border="0" alt=""></td>
  </tr>
</table>
<p>
In according with s.33(c) of the freedom of the information and protection of privacy Act ,and the access of motor vehicle information Regulation, specific personal information is collected to determine the recipient&#39;s authority to request to the information under the AMVIR and to conform the identity of the consenting individual, of the recipient&#39;s , and of the authorized employer of the recipient&#39;s(If the recipient is organization). The registry agent store this form for a one year. The form is used to monitor and audit to release of information and to conduct investigation if the registrar receives complaints about the release. Questions about the collection of this information can be direct to a service alberta information officer at 780-427-7013, toll free 310-0000 within alberta. Alternatively ,question may be mailed to box 3140,Edmonton,AB T5j 2G7 ,attention Data access and contract Management Unit(DACMU).
</p>';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('driver_abstract_statement_of_intent/download/hr_'.$fieldlevelriskid.'.pdf', 'F');
    echo '<script type="text/javascript" language="Javascript">window.location.replace("?tile_name='.$tile.'");
    window.open("driver_abstract_statement_of_intent/download/hr_'.$fieldlevelriskid.'.pdf", "fullscreen=yes");
    </script>';

    unlink("driver_abstract_statement_of_intent/download/hr_".$_SESSION['contactid'].".png");
	unlink("driver_abstract_statement_of_intent/download/hr_".$_SESSION['contactid']."_sign2_.png");
    echo '';
}
?>