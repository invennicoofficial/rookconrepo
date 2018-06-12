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
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_employee_information_form WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
    $fields = explode('**FFM**', $get_field_level['fields']);
}

$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM `hr` WHERE tab LIKE '$tab' AND form='$form'"));
$form_config = ','.$get_field_config['fields'].',';
?>

<h3>Information</h3>
<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Name:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_1" value="<?php echo $fields[1]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields1".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Date of Birth:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_0" value="<?php echo $fields[0]; ?>" class="datepicker" />
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
	<label for="business_street" class="col-sm-4 control-label">Home Phone:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_3" value="<?php echo $fields[3]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Cell Phone:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_4" value="<?php echo $fields[4]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">SIN:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_5" value="<?php echo $fields[5]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields7".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Health Care Card Number:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_6" value="<?php echo $fields[6]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields8".',') !== FALSE) { ?>

	<h3>Emergency Contact</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Name:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_7" value="<?php echo $fields[7]; ?>" class="form-control" />
	</div>
	</div>

	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Phone:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_8" value="<?php echo $fields[8]; ?>" class="form-control" />
	</div>
	</div>

	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Relationship:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_9" value="<?php echo $fields[9]; ?>" class="form-control" />
	</div>
	</div>

	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Name:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_10" value="<?php echo $fields[10]; ?>" class="form-control" />
	</div>
	</div>

	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Phone:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_11" value="<?php echo $fields[11]; ?>" class="form-control" />
	</div>
	</div>

	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Relationship:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_12" value="<?php echo $fields[12]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields9".',') !== FALSE) { ?>
    <h3>Medical Conditions</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Please list any relevant medical conditions:</label>
	<div class="col-sm-8">
	<textarea name="fields_14" rows="3" cols="50" class="form-control"><?php echo $fields[14]; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields10".',') !== FALSE) { ?>
	<h3>Medications</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Are you on any medication? If yes, please list:</label>
	<div class="col-sm-8">
	<textarea name="fields_16" rows="3" cols="50" class="form-control"><?php echo $fields[16]; ?></textarea>
	</div>
	</div>

	<p>You must notify Human Resources should your medication list change in the future (so proper care can be given in the event of an emergency).</p>
<?php } ?>

<h3>Validation</h3>
<input type="checkbox" style="height: 20px; width: 20px;" value="1" required name="agenda_send_email">&nbsp;Information which I have provided here is true and correct. I have read and understand the content of this policy.<br><br>
Date : <?php echo date('Y-m-d'); ?><br>
Person : <?php echo decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']); ?><br><br>
<?php include ('../phpsign/sign.php'); ?>