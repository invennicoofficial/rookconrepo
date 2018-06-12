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
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_contractor_pay_agreement WHERE fieldlevelriskid='$formid'"));
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
	<label for="business_street" class="col-sm-4 control-label">Name:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_0" value="<?php echo $fields[0]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Company:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_1" value="<?php echo $fields[1]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Address:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_2" value="<?php echo $fields[2]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Phone #:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_3" value="<?php echo $fields[3]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">WCB #:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_4" value="<?php echo $fields[4]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">GST #:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_5" value="<?php echo $fields[5]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields7".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Position:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_6" value="<?php echo $fields[6]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<h3>Rate Of Pay</h3>
<?php if (strpos($form_config, ','."fields8".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Per Hour (regular time pay):</label>
	<div class="col-sm-8">
	<input type="text" name="fields_7" value="<?php echo $fields[7]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields9".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Per Hour (overtime pay):</label>
	<div class="col-sm-8">
	<input type="text" name="fields_8" value="<?php echo $fields[8]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<p>Note: Overtime pay is paid after 8 regular hours per day, excluding travel.</p>

<?php if (strpos($form_config, ','."fields10".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Truck classification:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_9" value="<?php echo $fields[9]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields11".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Rate of Pay (per hour):</label>
	<div class="col-sm-8">
	<input type="text" name="fields_10" value="<?php echo $fields[10]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields12".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">up to a daily maximum of:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_11" value="<?php echo $fields[11]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<p>The travel time rate for the above mentioned will be</p>

<?php if (strpos($form_config, ','."fields13".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Regular</label>
	<div class="col-sm-8">
	<input type="text" name="fields_12" value="<?php echo $fields[12]; ?>" class="form-control" />&nbsp;per hour (regular rate x 0.5)
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields14".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Overtime</label>
	<div class="col-sm-8">
	<input type="text" name="fields_13" value="<?php echo $fields[13]; ?>" class="form-control" />&nbsp;per hour (regular TT rate x 2)
	</div>
	</div>
<?php } ?>

<p>Note: Travel time is only paid from the Company yard to the job site and back.</p>

<h3>Pay Options</h3>
<p>Please tick the pay option selected</p>

<?php if (strpos($form_config, ','."fields15".',') !== FALSE) { ?>
<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Option #1 - Two Week Rotating</label>
	<div class="col-sm-8"><input type="checkbox" <?php if ($fields[14]=='Option #1 - Two Week Rotating') { echo " checked"; } ?>  name="fields_14" value="Option #1 - Two Week Rotating"><input name="fields_15" type="text" value="<?php echo $fields[15]; ?>" class="form-control" />
	</div>
</div>
<?php } ?>

A 5% processing/handling fee will be charged and deducted off each invoice submitted to the Company for payment.<br><br>
Cutoff to coincide with the Company's payroll cutoff date.<br><br>
Invoice must be submitted within 2 days of cutoff for processing.<br><br>

<?php if (strpos($form_config, ','."fields16".',') !== FALSE) { ?>
<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Option #2 - Month End</label>
	<div class="col-sm-8"><input type="checkbox" <?php if ($fields[16]=='Option #2 - Month End') { echo " checked"; } ?>  name="fields_16" value="Option #2 - Month End"><input name="fields_17" type="text" value="<?php echo $fields[17]; ?>" class="form-control" />
	</div>
</div>
<?php } ?>

Submit invoices at the end of each month to the Company for FULL payment by the end of the following month.<br><br>

<p>Note: Option cannot be changed without written management approval.</p>

<h3>Validation</h3>
<input type="checkbox" style="height: 20px; width: 20px;" value="1" required name="agenda_send_email">&nbsp;Information which I have provided here is true and correct. I have read and understand the content of this policy.<br><br>
Date : <?php echo date('Y-m-d'); ?><br>
Person : <?php echo decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']); ?><br><br>
<?php include ('../phpsign/sign.php'); ?>