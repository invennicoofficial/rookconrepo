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
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_absence_report WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
    $fields = explode('**FFM**', $get_field_level['fields']);
}

$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM `hr` WHERE tab LIKE '$tab' AND form='$form'"));
$form_config = ','.$get_field_config['fields'].',';
?>
<h3>Information</h3>
<?php if (strpos($form_config, ','."fields1".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Employee name:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_0" value="<?php echo $fields[0]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Position:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_1" value="<?php echo $fields[1]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Supervisor:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_2" value="<?php echo $fields[2]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Occurrence date(s):</label>
	<div class="col-sm-8">
	<input type="text" name="fields_3" value="<?php echo $fields[3]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Report date:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_4" value="<?php echo $fields[4]; ?>" class="datepicker" />
	</div>
	</div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields6,') !== FALSE) { ?>
	<h3>Check reason for absence from work</h3>
	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Personal Illness:</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[5]=='Personal Illness') { echo " checked"; } ?>  name="fields_5" value="Personal Illness"><input name="fields_6" type="text" value="<?php echo $fields[6]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Medical Reason/Medical Appointment:</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[7]=='Medical Reason/Medical Appointment') { echo " checked"; } ?>  name="fields_7" value="Medical Reason/Medical Appointment"><input name="fields_8" type="text" value="<?php echo $fields[8]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Accident/Injury (outside of job):</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[9]=='Accident/Injury (outside of job)') { echo " checked"; } ?>  name="fields_9" value="Accident/Injury (outside of job)"><input name="fields_10" type="text" value="<?php echo $fields[10]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Accident/Injury (job-related):</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[11]=='Accident/Injury (job-related)') { echo " checked"; } ?>  name="fields_11" value="Accident/Injury (job-related)"><input name="fields_12" type="text" value="<?php echo $fields[12]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Personal Reasons:</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[13]=='Personal Reasons') { echo " checked"; } ?>  name="fields_13" value="Personal Reasons"><input name="fields_14" type="text" value="<?php echo $fields[14]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Family Illness:</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[15]=='Family Illness') { echo " checked"; } ?>  name="fields_15" value="Family Illness"><input name="fields_16" type="text" value="<?php echo $fields[16]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Death in Family:</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[17]=='Death in Family') { echo " checked"; } ?>  name="fields_17" value="Death in Family"><input name="fields_18" type="text" value="<?php echo $fields[18]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Vacation:</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[19]=='Vacation') { echo " checked"; } ?>  name="fields_19" value="Vacation"><input name="fields_20" type="text" value="<?php echo $fields[20]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Court Summons:</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[21]=='Court Summons') { echo " checked"; } ?>  name="fields_21" value="Court Summons"><input name="fields_22" type="text" value="<?php echo $fields[22]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Weather/Natural Disaster:</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[23]=='Weather/Natural Disaster') { echo " checked"; } ?>  name="fields_23" value="Weather/Natural Disaster"><input name="fields_24" type="text" value="<?php echo $fields[24]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Jury Duty:</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[25]=='Jury Duty') { echo " checked"; } ?>  name="fields_25" value="Jury Duty"><input name="fields_26" type="text" value="<?php echo $fields[26]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Military Service:</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[27]=='Military Service') { echo " checked"; } ?>  name="fields_27" value="Military Service"><input name="fields_28" type="text" value="<?php echo $fields[28]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Disciplinary Action:</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[29]=='Disciplinary Action') { echo " checked"; } ?>  name="fields_29" value="Disciplinary Action"><input name="fields_30" type="text" value="<?php echo $fields[30]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Unknown:</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[31]=='Unknown') { echo " checked"; } ?>  name="fields_31" value="Unknown"><input name="fields_32" type="text" value="<?php echo $fields[32]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Other:</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[33]=='Other') { echo " checked"; } ?>  name="fields_33" value="Other"><input name="fields_34" type="text" value="<?php echo $fields[34]; ?>" class="form-control" />
		</div>
	</div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields7,') !== FALSE) { ?>
	<h3>Notification received from</h3>
	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Employee:</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[35]=='Employee') { echo " checked"; } ?>  name="fields_35" value="Employee"><input name="fields_36" type="text" value="<?php echo $fields[36]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Relative:</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[37]=='Relative') { echo " checked"; } ?>  name="fields_37" value="Relative"><input name="fields_38" type="text" value="<?php echo $fields[38]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Doctor:</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[39]=='Doctor') { echo " checked"; } ?>  name="fields_39" value="Doctor"><input name="fields_40" type="text" value="<?php echo $fields[40]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Other:</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[41]=='Other') { echo " checked"; } ?>  name="fields_41" value="Other"><input name="fields_42" type="text" value="<?php echo $fields[42]; ?>" class="form-control" />
		</div>
	</div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields8,') !== FALSE) { ?>
	<h3>Notification by</h3>
	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Telephone:</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[43]=='Telephone') { echo " checked"; } ?>  name="fields_43" value="Telephone"><input name="fields_44" type="text" value="<?php echo $fields[44]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Email:</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[45]=='Email') { echo " checked"; } ?>  name="fields_45" value="Email"><input name="fields_46" type="text" value="<?php echo $fields[46]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Text:</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[47]=='Text') { echo " checked"; } ?>  name="fields_47" value="Text"><input name="fields_48" type="text" value="<?php echo $fields[48]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Writing:</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[49]=='Writing') { echo " checked"; } ?>  name="fields_49" value="Writing"><input name="fields_50" type="text" value="<?php echo $fields[50]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Other:</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[51]=='Other') { echo " checked"; } ?>  name="fields_51" value="Other"><input name="fields_52" type="text" value="<?php echo $fields[52]; ?>" class="form-control" />
		</div>
	</div>
<?php } ?>


<?php if (strpos(','.$form_config.',', ',fields9,') !== FALSE) { ?>
	<h3>Action taken</h3>
	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Salary/Wage Deduction:</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[53]=='Salary/Wage Deduction') { echo " checked"; } ?>  name="fields_53" value="Salary/Wage Deduction"><input name="fields_54" type="text" value="<?php echo $fields[54]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">None:</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[55]=='None') { echo " checked"; } ?>  name="fields_55" value="None"><input name="fields_56" type="text" value="<?php echo $fields[56]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Disciplinary Action:</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[57]=='Disciplinary Action') { echo " checked"; } ?>  name="fields_57" value="Disciplinary Action"><input name="fields_58" type="text" value="<?php echo $fields[58]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Makeup Time:</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[59]=='Makeup Time') { echo " checked"; } ?>  name="fields_59" value="Makeup Time"><input name="fields_60" type="text" value="<?php echo $fields[60]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Other:</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[61]=='Other') { echo " checked"; } ?>  name="fields_61" value="Other"><input name="fields_62" type="text" value="<?php echo $fields[62]; ?>" class="form-control" />
		</div>
	</div>
<?php } ?>

<h3>Validation</h3>
<input type="checkbox" style="height: 20px; width: 20px;" value="1" required name="agenda_send_email">&nbsp;Information which I have provided here is true and correct. I have read and understand the content of this policy.<br><br>
Date : <?php echo date('Y-m-d'); ?><br>
Person : <?php echo decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']); ?><br><br>
<?php include ('../phpsign/sign.php'); ?>