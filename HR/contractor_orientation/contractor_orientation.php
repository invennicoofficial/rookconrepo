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
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_contractor_orientation WHERE fieldlevelriskid='$formid'"));
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
	<label for="business_street" class="col-sm-4 control-label">Contractor's name:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_0" value="<?php echo $fields[0]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Start date:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_1" value="<?php echo $fields[1]; ?>" class="datepicker" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Orientation date:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_2" value="<?php echo $fields[2]; ?>" class="datepicker" />
	</div>
	</div>
<?php } ?>

<h3>Orientation</h3>
<p>It is the practice of the Company to have all contractors review the Health, Safety & Environment Orientation prior to commencing work.</p>

<?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">I agree to adhere to the Company Health and Safety Program and commit to aiding the Company in its goal of achieving a safe work environment.</label>
	<div class="col-sm-8">
	<input type="text" name="fields_3" value="<?php echo $fields[3]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">I have been advised of the Company Safe Work Practices and Safe Job Procedures and will participate in all ongoing Job Hazard Assessments.</label>
	<div class="col-sm-8">
	<input type="text" name="fields_4" value="<?php echo $fields[4]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<h3>Shop & Yard Orientation</h3>
<?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">I have been provided and reviewed a map (or had a tour of) the Company shop and yard, showing buildings, exits, fire extinguishers, first aid kits and muster point. I have reviewed and I am familiar with the emergency response plan for the shop and yard.</label>
	<div class="col-sm-8">
	<input type="text" name="fields_5" value="<?php echo $fields[5]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields7,') !== FALSE) { ?>
	<h3>PPE Checklist</h3>
	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Hard Hat</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[6]=='Hard Hat') { echo " checked"; } ?>  name="fields_6" value="Hard Hat"><input name="fields_7" type="text" value="<?php echo $fields[7]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">CSA Approved Safety Glasses</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[8]=='CSA Approved Safety Glasses') { echo " checked"; } ?>  name="fields_8" value="CSA Approved Safety Glasses"><input name="fields_9" type="text" value="<?php echo $fields[9]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">CSA Approved Steel Toed Boots</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[10]=='CSA Approved Steel Toed Boots') { echo " checked"; } ?>  name="fields_10" value="CSA Approved Steel Toed Boots"><input name="fields_11" type="text" value="<?php echo $fields[11]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Welding Helmet</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[12]=='Welding Helmet') { echo " checked"; } ?>  name="fields_12" value="Welding Helmet"><input name="fields_13" type="text" value="<?php echo $fields[13]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Hearing Protection</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[14]=='Hearing Protection') { echo " checked"; } ?>  name="fields_14" value="Hearing Protection"><input name="fields_15" type="text" value="<?php echo $fields[15]; ?>" class="form-control" />
		</div>
	</div>
<?php } ?>

<h3>Incident Reporting</h3>
In the event an incident occurs, all contractors are to report the incident immediately to the Company. Contractors involved in an incident or as witness to an incident will participate in the investigation along with the Company. This will help create a safer work environment for all workers involved.

<?php if (strpos(','.$form_config.',', ',fields8,') !== FALSE) { ?>
	<h3>Safety Tickets & Qualification</h3>
	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">First Aid</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[16]=='First Aid') { echo " checked"; } ?>  name="fields_16" value="First Aid">&nbsp;Issue Date&nbsp;<input name="fields_17" type="text" value="<?php echo $fields[17]; ?>" class="datepicker" />&nbsp;Expiry Date&nbsp;<input name="fields_18" type="text" value="<?php echo $fields[18]; ?>" class="datepicker" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">H2S</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[19]=='H2S') { echo " checked"; } ?>  name="fields_19" value="H2S">&nbsp;Issue Date&nbsp;<input name="fields_20" type="text" value="<?php echo $fields[20]; ?>" class="datepicker" />&nbsp;Expiry Date&nbsp;<input name="fields_21" type="text" value="<?php echo $fields[21]; ?>" class="datepicker" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">CSTS</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[22]=='CSTS') { echo " checked"; } ?>  name="fields_22" value="CSTS">&nbsp;Issue Date&nbsp;<input name="fields_23" type="text" value="<?php echo $fields[23]; ?>" class="datepicker" />&nbsp;Expiry Date&nbsp;<input name="fields_24" type="text" value="<?php echo $fields[24]; ?>" class="datepicker" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Journeyman Certificate</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[25]=='Journeyman Certificate') { echo " checked"; } ?>  name="fields_25" value="Journeyman Certificate">&nbsp;Issue Date&nbsp;<input name="fields_26" type="text" value="<?php echo $fields[26]; ?>" class="datepicker" />&nbsp;Expiry Date&nbsp;<input name="fields_27" type="text" value="<?php echo $fields[27]; ?>" class="datepicker" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Ground Disturbance</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[28]=='Ground Disturbance') { echo " checked"; } ?>  name="fields_28" value="Ground Disturbance">&nbsp;Issue Date&nbsp;<input name="fields_29" type="text" value="<?php echo $fields[29]; ?>" class="datepicker" />&nbsp;Expiry Date&nbsp;<input name="fields_30" type="text" value="<?php echo $fields[30]; ?>" class="datepicker" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">"B" Pressure</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[31]=='B Pressure') { echo " checked"; } ?>  name="fields_31" value='B Pressure'>&nbsp;Issue Date&nbsp;<input name="fields_32" type="text" value="<?php echo $fields[32]; ?>" class="datepicker" />&nbsp;Expiry Date&nbsp;<input name="fields_33" type="text" value="<?php echo $fields[33]; ?>" class="datepicker" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Other</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[34]=='Other') { echo " checked"; } ?>  name="fields_34" value="Other">&nbsp;Issue Date&nbsp;<input name="fields_35" type="text" value="<?php echo $fields[35]; ?>" class="datepicker" />&nbsp;Expiry Date&nbsp;<input name="fields_36" type="text" value="<?php echo $fields[36]; ?>" class="datepicker" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Other-1</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[37]=='Other-1') { echo " checked"; } ?>  name="fields_37" value="Other-1">&nbsp;Issue Date&nbsp;<input name="fields_38" type="text" value="<?php echo $fields[38]; ?>" class="datepicker" />&nbsp;Expiry Date&nbsp;<input name="fields_39" type="text" value="<?php echo $fields[39]; ?>" class="datepicker" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Other-2</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[40]=='Other-2') { echo " checked"; } ?>  name="fields_40" value="Other-2">&nbsp;Issue Date&nbsp;<input name="fields_41" type="text" value="<?php echo $fields[41]; ?>" class="datepicker" />&nbsp;Expiry Date&nbsp;<input name="fields_42" type="text" value="<?php echo $fields[42]; ?>" class="datepicker" />
		</div>
	</div>
<?php } ?>


<?php if (strpos(','.$form_config.',', ',fields7,') !== FALSE) { ?>
	<h3>Provided Documents</h3>
	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">WCB Clearance Letter (addressed to the Company)</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[43]=='WCB Clearance Letter (addressed to the Company)') { echo " checked"; } ?>  name="fields_43" value="WCB Clearance Letter (addressed to the Company)"><input name="fields_44" type="text" value="<?php echo $fields[44]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">WCB Experience Rating/Premium Rate Statement</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[45]=='WCB Experience Rating/Premium Rate Statement') { echo " checked"; } ?>  name="fields_45" value="WCB Experience Rating/Premium Rate Statement"><input name="fields_46" type="text" value="<?php echo $fields[46]; ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Current Certificate of Insurance</label>
		<div class="col-sm-8"><input type="checkbox" <?php if ($fields[47]=='Current Certificate of Insurance') { echo " checked"; } ?>  name="fields_47" value="Current Certificate of Insurance"><input name="fields_48" type="text" value="<?php echo $fields[48]; ?>" class="form-control" />
		</div>
	</div>

	<p>Note: The Company must have proof of WCB Coverage prior to you being able to work . WCB rates are always taken into consideration and evaluated prior to work being issued.</p>
<?php } ?>

<h3>Validation</h3>
<input type="checkbox" style="height: 20px; width: 20px;" value="1" required name="agenda_send_email">&nbsp;Information which I have provided here is true and correct. I have read and understand the content of this policy.<br><br>
Date : <?php echo date('Y-m-d'); ?><br>
Person : <?php echo decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']); ?><br><br>
<?php include ('../phpsign/sign.php'); ?>