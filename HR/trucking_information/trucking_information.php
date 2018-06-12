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
$desc = '';
$desc1 = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];
	echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_trucking_information WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
    $fields = explode('**FFM**', $get_field_level['fields']);
	$desc = $get_field_level['desc'];
	$desc1 = $get_field_level['desc1'];
}

$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM `hr` WHERE tab LIKE '$tab' AND form='$form'"));
$form_config = ','.$get_field_config['fields'].',';
?>

<h3>Information</h3>
<?php if (strpos($form_config, ','."fields1".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Date:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_0" value="<?php echo $fields[0]; ?>" class="datepicker" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Transport Company:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_1" value="<?php echo $fields[1]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Phone #:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_2" value="<?php echo $fields[2]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Contact Person:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_3" value="<?php echo $fields[3]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Cell #:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_4" value="<?php echo $fields[4]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Company Foreman:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_5" value="<?php echo $fields[5]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields7".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Phone #:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_6" value="<?php echo $fields[6]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields8".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Company Job #:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_7" value="<?php echo $fields[7]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<h3>Equipment Information</h3>
<?php if (strpos($form_config, ','."fields9".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Equipment Being Picked Up - #1:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_8" value="<?php echo $fields[8]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields10".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Length:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_9" value="<?php echo $fields[9]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields11".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Weight:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_10" value="<?php echo $fields[10]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields12".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Location of Pickup:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_11" value="<?php echo $fields[11]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields13".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Date & Time of Pickup:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_12" value="<?php echo $fields[12]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields14".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Contact Person at Pickup:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_13" value="<?php echo $fields[13]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields15".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Phone #:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_14" value="<?php echo $fields[14]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields16".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Equipment Being Picked Up - #2:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_15" value="<?php echo $fields[15]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields17".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Length:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_16" value="<?php echo $fields[16]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields18".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Weight:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_17" value="<?php echo $fields[17]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields19".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Location of Pickup:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_18" value="<?php echo $fields[18]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields20".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Date & Time of Pickup:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_19" value="<?php echo $fields[19]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields21".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Contact Person at Pickup:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_20" value="<?php echo $fields[20]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields22".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Phone #:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_21" value="<?php echo $fields[21]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>
<h3>Destination Information</h3>
<?php if (strpos($form_config, ','."fields23".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Site Location:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_22" value="<?php echo $fields[22]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields24".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Oil Company:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_23" value="<?php echo $fields[23]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields25".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Delivery Date & Time:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_24" value="<?php echo $fields[24]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields26".',') !== FALSE) { ?>
    <h3>Driving Directions/Map Coordinates</h3>
	<div class="form-group">
		<label for="business_street" class="col-sm-4 control-label">Driving Directions/Map Coordinates:</label>
		<div class="col-sm-8">
			<textarea name="desc" rows="3" cols="50" class="form-control"><?php echo $desc; ?></textarea>
		</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields27".',') !== FALSE) { ?>
    <h3>Other Special Instructions</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Other Special Instructions:</label>
	<div class="col-sm-8">
	<textarea name="desc1" rows="3" cols="50" class="form-control"><?php echo $desc1; ?></textarea>
	</div>
	</div>
<?php } ?>

<h3>Validation</h3>
<h4><input type="checkbox" style="height: 20px; width: 20px;" value="1" required name="agenda_send_email">&nbsp;Information which I have provided here is true and correct. I have read and understand the content of this policy.<br><br>
Date : <?php echo date('Y-m-d'); ?><br>
Person : <?php echo decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']); ?><br><br>
</h4>
<?php include ('../phpsign/sign.php'); ?>
</div>