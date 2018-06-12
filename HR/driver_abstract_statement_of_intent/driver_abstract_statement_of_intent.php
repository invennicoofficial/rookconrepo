<?php
/*
Add	Sheet
*/
include_once ('../database_connection.php');
error_reporting(0);
?>
</head>
<body>

<?php
$today_date = date('Y-m-d');
$contactid = $_SESSION['contactid'];
$fields = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];
	echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_driver_abstract_statement_of_intent WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
    $fields = explode('**FFM**', $get_field_level['fields']);

}

$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM `hr` WHERE tab LIKE '$tab' AND form='$form'"));
$form_config = ','.$get_field_config['fields'].',';
?>

</head>
<body>
<span style="float:left;font-size:25px;"><b> Government Of <br>Alberta</b></span>
<span style="float:right;font-size:25px;"><b> Driver Abstract Statement of Intent </b></span>
<br><br><br>
<table style="border:1px solid black;border-collapse:collapse;width:100%;height:2px">
	<tr>
		<td></td>
	</tr>
</table>
<br><br>
<div style="padding:5px 0" ><b>This form is to be completed If a driver&#39;s abstract of person is being received by someone other than that person. A &quot;Driver abstract&quot; is a product name under which the alberta government releases specific information for a person&#39;s driving record which contain. </div>
	
<table style="width:100%">
  <tr>
    <th style="width:20%;padding:5px 10px;height:40px">Name</th>
    <th style="width:20%;padding:5px 10px;height:40px">Height</th>
	<th style="width:20%;padding:5px 10px;height:40px">Class</th>
	<th style="width:20%;padding:5px 10px;height:40px">Licence Number</th>
	<th style="width:20%;padding:5px 10px;height:40px">Expiration Date</th>
  </tr>
   <tr>
    <td style="padding:5px 10px;height:40px;"><input style="width:100%" class="form-control" type="text" name="fields_0" value="<?php echo $fields[0]; ?>"></td>
    <td style="padding:5px 10px;height:40px"><input style="width:100%" class="form-control" type="text" name="fields_1" value="<?php echo $fields[1]; ?>"></td>
	<td style="padding:5px 10px;height:40px"><input style="width:100%" class="form-control" type="text" name="fields_2" value="<?php echo $fields[2]; ?>"></td>
	<td style="padding:5px 10px;height:40px"><input style="width:100%" class="form-control" type="text" name="fields_3" value="<?php echo $fields[3]; ?>"></td>
	<td style="padding:5px 10px;height:40px"><input style="width:100%" class="form-control datepicker" type="date" name="fields_4" value="<?php echo $fields[4]; ?>"></td>
  </tr>
  <tr>
    <th style="width:20%;padding:5px 10px;height:40px">Address</th>
    <th style="width:20%;padding:5px 10px;height:40px">Weight</th>
	<th style="width:20%;padding:5px 10px;height:40px">Issue Date</th>
	<th style="width:20%;padding:5px 10px;height:40px">Current Demerit Points</th>
	<th style="width:20%;padding:5px 10px;height:40px">Reinstatement Conditions (if any)</th>
  </tr>
  <tr>
    <td style="padding:5px 10px;height:40px;"><input style="width:100%" class="form-control" type="text" name="fields_5" value="<?php echo $fields[5]; ?>"></td>
    <td style="padding:5px 10px;height:40px"><input style="width:100%" class="form-control" type="text" name="fields_6" value="<?php echo $fields[6]; ?>"></td>
	<td style="padding:5px 10px;height:40px"><input style="width:100%" class="form-control datepicker" type="date" name="fields_7" value="<?php echo $fields[7]; ?>"></td>
	<td style="padding:5px 10px;height:40px"><input style="width:100%" class="form-control" type="text" name="fields_8" value="<?php echo $fields[8]; ?>"></td>
	<td style="padding:5px 10px;height:40px"><input style="width:100%" class="form-control" type="text" name="fields_9" value="<?php echo $fields[9]; ?>"></td>
  </tr>
  <tr>
    <th style="width:20%;padding:5px 10px;height:40px">Date of Birth</th>
    <th style="width:20%;padding:5px 10px;height:40px">Sex</th>
	<th style="width:20%;padding:5px 10px;height:40px">MVID Number</th>
	<th style="width:20%;padding:5px 10px;height:40px" colspan="2">Suspended Status</th>
  </tr>
  <tr>
    <td style="padding:5px 10px;height:40px;"><input style="width:100%" class="form-control datepicker" type="date" name="fields_10" value="<?php echo $fields[10]; ?>"></td>
    <td style="padding:5px 10px;height:40px"><input style="width:100%" class="form-control" type="text" name="fields_11" value="<?php echo $fields[11]; ?>"></td>
	<td style="padding:5px 10px;height:40px"><input style="width:100%" class="form-control" type="text" name="fields_12" value="<?php echo $fields[12]; ?>"></td>
	<td style="padding:5px 10px;height:40px" colspan="2"><input style="width:100%" class="form-control" type="text" name="fields_13" value="<?php echo $fields[13]; ?>"></td>
  </tr>
</table>
<br><br>
<b> List of violations (Descriptions, Demerit/Merit Points and Suspension Term) </b>
<br><br>
<b> A Commercial Driver Abstract (CDA) includes Commercial Vehicle Safety Alliance Inspection (CVSA) information and all of the above information with the exception of date of birth, height, weight and sex. </b>
<br>

<span style="font-size:20px"> I/We, <input style="width:100%" type="text" class="form-control" name="fields_23" value="<?php echo $fields[23]; ?>"> </span>
<table style="border:1px solid black;border-collapse:collapse;width:100%;height:2px">
	<tr>
		<td></td>
	</tr>
</table>
<div style="margin-left:400px; font-size:15px"> Name of the person / organization requesting the driver's abstract </div>

<br>

<span style="font-size:20px"> of <input style="width:100%" type="text" class="form-control" name="fields_24" value="<?php echo $fields[24]; ?>"> </span>
<table style="border:1px solid black;border-collapse:collapse;width:100%;height:2px">
	<tr>
		<td></td>
	</tr>
</table>
<div style="margin-left:570px; font-size:15px"> Full Address </div>

<div style="font-size:15px"> solemnly declare that I/We have received permission to request the : </div>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" value="1" name="fields_15"> 3 Year, <input value="2" type="checkbox" name="fields_15"> 5 Year, <input type="checkbox" value="3" name="fields_15"> 10 Year Driver Abstract (SDA) <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" value="1" name="fields_16"> 3 Year, <input type="checkbox" value="2" name="fields_16"> 5 Year, <input type="checkbox" value="3" name="fields_16"> 10 Year Commercial Driver Abstract (CDA) <br>
<span style="font-size:20px"> of <input style="width:100%" type="text" class="form-control" name="fields_17" value="<?php echo $fields[17]; ?>"> </span>
<div style="margin-left:400px; font-size:15px"> Name of the person whose driver's abstract is being requested </div> <br>

<p> In accordance with Alberta Motor Vehicle Information Regulation (AMVIR)(choose one of the following subsections): </p>

<p><input type="radio" value="1" name="fields_18"><b>5(1)(a) driver&#39;s abstract released to someone known to that person </b></p>
<p style="margin-left:50px"> <b> I solemnly declare that: <b> </p>
<p style="margin-left:150px"> I have received valid written consent </p>
<p style="margin-left:150px"> the person is personally known to me and I am receiving the driver's abstract only to transfer it to that person </p>
<p style="margin-left:150px"> after receiving the driver's abstract I am responsible for it </p>
<p style="margin-left:150px"> I am not acting as an agent or employee or any person in this transaction, and that I am not compensated in any manner for receiving or transferring the driver's abstract to that person. </p>

<p><input type="radio" value="2" name="fields_18"><b>5(1)(b)(iii) driver&#39;s abstract released to employer or prospective employer </b></p>
<p style="margin-left:50px"> <b> I/we solemnly declare that: <b> </p>
<p style="margin-left:150px"> Valid written consent has been received </p>
<p style="margin-left:150px"> the driver&#39;s abstract will be used for employment purpose only </p>
<p style="margin-left:150px"> after receiving the driver&#39;s abstract I am fully responsible for it. </p>

<p><input type="radio" value="3" name="fields_18"><b>5(1)(b)(iv) driver&#39;s abstract released to parent or guardian of a minor consent is not required. </b> </p>
<p style="margin-left:150px"> <b>  Consent is not required. <b> </p>

<p><input type="radio" value="4" name="fields_18"><b>5(1)(b)(v) driver&#39;s abstract released to a lawyer representing the driver</p>
<p style="margin-left:50px"> <b> I/We solemnly declare that: <b> </p>
<p style="margin-left:150px"> valid written consent has been received </p>
<p style="margin-left:150px">  the driver&#39;s abstract will be used to represent the client </p>
<p style="margin-left:150px"> after receiving the driver's abstract I am fully responsible for it. </p>

<p> I/We agree that Alberta Registries and/or the registry agent are not liable for any damages or losses however caused, in respect to any defect, error or omission in the driver's abstract, or use of the driver&#39;s abstract, or use of the driver's abstract. </p>
<table style="width:100%">
  <tr>
    <th style="width:20%;padding:5px 10px;height:40px">Signature of the authorized individual</th>
    <th style="width:20%;padding:5px 10px;height:40px">City/Town/Village</th>
	<th style="width:20%;padding:5px 10px;height:40px">Province/State</th>
  </tr>
   <tr>
    <td style="padding:5px 10px;height:40px;"><?php include ('../phpsign/sign.php'); ?></td>
    <td style="padding:5px 10px;height:40px"><input style="width:100%" class="form-control" type="text" name="fields_19" value="<?php echo $fields[19]; ?>"></td>
	<td style="padding:5px 10px;height:40px"><input style="width:100%" class="form-control" type="text" name="fields_20" value="<?php echo $fields[20]; ?>"></td>
  </tr>
  <tr>
    <th style="width:20%;padding:5px 10px;height:40px">Date</th>
    <th style="width:20%;padding:5px 10px;height:40px">Name of Witness (PRINT)</th>
	<th style="width:20%;padding:5px 10px;height:40px">Signature of Witness</th>
  </tr>
   <tr>
    <td style="padding:5px 10px;height:40px;"><input style="width:100%" class="form-control datepicker" type="date" name="fields_21" value="<?php echo $fields[21]; ?>"></td>
    <td style="padding:5px 10px;height:40px"><input style="width:100%" class="form-control" type="text" name="fields_22" value="<?php echo $fields[22]; ?>"></td>
	<td style="padding:5px 10px;height:40px"><?php include ('../phpsign/sign2.php'); ?></td>
  </tr>
</table>
<p>
In accordance with s.33(c) of the Freedom of Information and Protection of Privacy Act, the Traffic Safety Act and the Access to Motor Vehicle Information Regulation, specific personal information is collected to determine the recipient's authority to request the information under AMVIR and to confirm the identity of the consenting individual, of the recipient, and of the authorized employee of the recipient (if the recipient is an organization). The registry agent stores this form for one year. The form is used to monitor and audit the release of information and to conduct investigations if the Registrar receives complaints about the release. Questions about the collection of this information can be directed to a Service Alberta Information Officer at 780-427-7013, toll free 310-0000 within Alberta. Alternatively, questions may be mailed to Box 3140, Edmonton, AB T5J 2G7, attention Data Access and Contract Management Unit (DACMU).
</p>
</div>




