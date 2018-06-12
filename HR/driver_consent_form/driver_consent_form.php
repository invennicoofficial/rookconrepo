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
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM `hr_driver_consent_form` WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
    $fields = explode('**FFM**', $get_field_level['fields']);

}

$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM `hr` WHERE tab LIKE '$tab' AND form='$form'"));
$form_config = ','.$get_field_config['fields'].',';
?>

<span style="font-size:20px"> I, </span> <span style="margin-left:30px; font-size:20px; margin-right:30px;"> <input style="width:40%" type="text" class="form-control" name="fields_0" value="<?php echo $fields[0]; ?>"> </span> <span style="font-size:20px"> of </span> <span style="margin-right:30px; margin-left:30px; font-size:20px"> <input style="width:40%" type="text" class="form-control" name="fields_1" value="<?php echo $fields[1]; ?>"> </span>

<div style="margin-left:200px; font-size:15px"> Name <span style="margin-left:550px; font-size:15px"> Address </span> </div> 

<br>

<p style="font-size:15px"> in the Province of <input type="text" style="width:20%" class="form-control" name="fields_11" value="<?php echo $fields[11]; ?>"> hereby consent to the disclosure of my driver&#39;s abstract/record, which is made from personal information in the Motor Vehicle Registry of the Province of </p>

<span style="font-size:15px"><input type="text" style="width:30%" class="form-control" name="fields_12" value="<?php echo $fields[12]; ?>"> </span> <br><br> <span style="margin-left:30px; font-size:20px; margin-right:30px;"> <input style="width:90%" type="text" class="form-control" name="fields_2" value="<?php echo $fields[2]; ?>"> </span>

<div style="margin-left:350px; font-size:15px"> (Name of person to whom the information is being disclosed) </div> 
<br><br>

<p style="font-size:15px"> Who may use this personal information for the following purpose(s): </p>

<p> <input style="width:100%" type="text" class="form-control" name="fields_3" value="<?php echo $fields[3]; ?>"> </p>

<div style="margin-left:450px; font-size:15px"> (List specific purpose or purposes) </div>

<br>

<span style="font-size:15px"> DATED this </span> <span style="margin-left:30px; font-size:15px; margin-right:30px;"> <input style="width:30%" type="text" class="form-control" name="fields_4" value="<?php echo $fields[4]; ?>"> </span> <span style="font-size:15px"> day of </span> <span style="margin-right:30px; margin-left:30px; font-size:15px"> <input style="width:30%" type="text" class="form-control" name="fields_5" value="<?php echo $fields[5]; ?>"> </span> <span style="font-size:15px"> ,20 </span> <span style="margin-right:30px; margin-left:30px; font-size:15px"> <input style="width:10%" type="text" class="form-control" name="fields_6" value="<?php echo $fields[6]; ?>"> </span>

<br><br>

<table style="width:100%">
  <tr>
    <th style="width:20%;padding:5px 10px;height:40px">Signature</th>
    <th style="width:20%;padding:5px 10px;height:40px">Driver&#39;s License Number</th>
	<th style="width:20%;padding:5px 10px;height:40px">Witness Signature</th>
  </tr>
   <tr>
    <td style="padding:5px 10px;height:40px;"><?php include ('../phpsign/sign.php'); ?></td>
    <td style="padding:5px 10px;height:40px"><input style="width:100%" class="form-control" type="text" name="fields_7" value="<?php echo $fields[7]; ?>"></td>
	<td style="padding:5px 10px;height:40px"><input style="width:100%" class="form-control" type="text" name="fields_8" value="<?php echo $fields[8]; ?>"></td>
  </tr>
  <tr>
    <th style="width:20%;padding:5px 10px;height:40px">Date</th>
    <th colspan="2" style="width:20%;padding:5px 10px;height:40px">Print Name of Witness</th>
  </tr>
   <tr>
    <td style="padding:5px 10px;height:40px;"><input style="width:100%" class="form-control datepicker" type="date" name="fields_9" value="<?php echo $fields[9]; ?>"></td>
    <td colspan="2" style="padding:5px 10px;height:40px"><input style="width:100%" class="form-control" type="text" name="fields_10" value="<?php echo $fields[10]; ?>"></td>
  </tr>
</table>
</div>




