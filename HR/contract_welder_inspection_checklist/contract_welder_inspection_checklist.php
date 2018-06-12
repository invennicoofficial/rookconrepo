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
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_contract_welder_inspection_checklist WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
    $fields = explode('**FFM**', $get_field_level['fields']);
}

$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM `hr` WHERE tab LIKE '$tab' AND form='$form'"));
$form_config = ','.$get_field_config['fields'].',';
?>

<h3>Note: This is only to be completed if the contractor is a welder</h3>
<h3>Information</h3>
<?php if (strpos($form_config, ','."fields1".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Inspected by:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_0" value="<?php echo $fields[0]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Welder:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_1" value="<?php echo $fields[1]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Licence #:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_2" value="<?php echo $fields[2]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Make/Color/Unit #:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_3" value="<?php echo $fields[3]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<h3>Checklist</h3>
<?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Vehicle registration and insurance is valid?</label>
	<div class="col-sm-8">
	<input type="radio" <?php if ($fields[4] == 'Yes') { echo " checked"; } ?>  name="fields_4" value="Yes">Yes&nbsp;&nbsp;
	<input type="radio" <?php if ($fields[4] == 'No') { echo " checked"; } ?>  name="fields_4" value="No">No&nbsp;&nbsp;
	<input type="text" name="fields_5" value="<?php echo $fields[5]; ?>"   class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Grinders and buffers have adequate guards/handles?</label>
	<div class="col-sm-8">
	<input type="radio" <?php if ($fields[6] == 'Yes') { echo " checked"; } ?>  name="fields_6" value="Yes">Yes&nbsp;&nbsp;
	<input type="radio" <?php if ($fields[6] == 'No') { echo " checked"; } ?>  name="fields_6" value="No">No&nbsp;&nbsp;
	<input type="text" name="fields_7" value="<?php echo $fields[7]; ?>"   class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields7".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Face shields used by grinder operators?</label>
	<div class="col-sm-8">
	<input type="radio" <?php if ($fields[8] == 'Yes') { echo " checked"; } ?>  name="fields_8" value="Yes">Yes&nbsp;&nbsp;
	<input type="radio" <?php if ($fields[8] == 'No') { echo " checked"; } ?>  name="fields_8" value="No">No&nbsp;&nbsp;
	<input type="text" name="fields_9" value="<?php echo $fields[9]; ?>"   class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields8".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Disk RPM rating matches the grinder/buffer rating?</label>
	<div class="col-sm-8">
	<input type="radio" <?php if ($fields[10] == 'Yes') { echo " checked"; } ?>  name="fields_10" value="Yes">Yes&nbsp;&nbsp;
	<input type="radio" <?php if ($fields[10] == 'No') { echo " checked"; } ?>  name="fields_10" value="No">No&nbsp;&nbsp;
	<input type="text" name="fields_11" value="<?php echo $fields[11]; ?>"   class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields9".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Fire extinguishers have valid inspection certification?</label>
	<div class="col-sm-8">
	<input type="radio" <?php if ($fields[12] == 'Yes') { echo " checked"; } ?>  name="fields_12" value="Yes">Yes&nbsp;&nbsp;
	<input type="radio" <?php if ($fields[12] == 'No') { echo " checked"; } ?>  name="fields_12" value="No">No&nbsp;&nbsp;
	<input type="text" name="fields_13" value="<?php echo $fields[13]; ?>"   class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields10".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Eye protection worn at all times?</label>
	<div class="col-sm-8">
	<input type="radio" <?php if ($fields[14] == 'Yes') { echo " checked"; } ?>  name="fields_14" value="Yes">Yes&nbsp;&nbsp;
	<input type="radio" <?php if ($fields[14] == 'No') { echo " checked"; } ?>  name="fields_14" value="No">No&nbsp;&nbsp;
	<input type="text" name="fields_15" value="<?php echo $fields[15]; ?>"   class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields11".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Hard hats worn when possible and where needed?</label>
	<div class="col-sm-8">
	<input type="radio" <?php if ($fields[16] == 'Yes') { echo " checked"; } ?>  name="fields_16" value="Yes">Yes&nbsp;&nbsp;
	<input type="radio" <?php if ($fields[16] == 'No') { echo " checked"; } ?>  name="fields_16" value="No">No&nbsp;&nbsp;
	<input type="text" name="fields_17" value="<?php echo $fields[17]; ?>"   class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields12".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Oxy/Acetylene torch has flame arresters on regulator end?</label>
	<div class="col-sm-8">
	<input type="radio" <?php if ($fields[18] == 'Yes') { echo " checked"; } ?>  name="fields_18" value="Yes">Yes&nbsp;&nbsp;
	<input type="radio" <?php if ($fields[18] == 'No') { echo " checked"; } ?>  name="fields_18" value="No">No&nbsp;&nbsp;
	<input type="text" name="fields_19" value="<?php echo $fields[19]; ?>"   class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields13".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Oxy/Acetylene hoses and fittings in good condition?</label>
	<div class="col-sm-8">
	<input type="radio" <?php if ($fields[20] == 'Yes') { echo " checked"; } ?>  name="fields_20" value="Yes">Yes&nbsp;&nbsp;
	<input type="radio" <?php if ($fields[20] == 'No') { echo " checked"; } ?>  name="fields_20" value="No">No&nbsp;&nbsp;
	<input type="text" name="fields_21" value="<?php echo $fields[21]; ?>"   class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields14".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Oxy/Acetylene bottles secured?</label>
	<div class="col-sm-8">
	<input type="radio" <?php if ($fields[22] == 'Yes') { echo " checked"; } ?>  name="fields_22" value="Yes">Yes&nbsp;&nbsp;
	<input type="radio" <?php if ($fields[22] == 'No') { echo " checked"; } ?>  name="fields_22" value="No">No&nbsp;&nbsp;
	<input type="text" name="fields_23" value="<?php echo $fields[23]; ?>"   class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields15".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Welding cables/electrical cords in good condition?</label>
	<div class="col-sm-8">
	<input type="radio" <?php if ($fields[24] == 'Yes') { echo " checked"; } ?>  name="fields_24" value="Yes">Yes&nbsp;&nbsp;
	<input type="radio" <?php if ($fields[24] == 'No') { echo " checked"; } ?>  name="fields_24" value="No">No&nbsp;&nbsp;
	<input type="text" name="fields_25" value="<?php echo $fields[25]; ?>"   class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields16".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">TDG place cards on bottle cabinet?</label>
	<div class="col-sm-8">
	<input type="radio" <?php if ($fields[26] == 'Yes') { echo " checked"; } ?>  name="fields_26" value="Yes">Yes&nbsp;&nbsp;
	<input type="radio" <?php if ($fields[26] == 'No') { echo " checked"; } ?>  name="fields_26" value="No">No&nbsp;&nbsp;
	<input type="text" name="fields_27" value="<?php echo $fields[27]; ?>"   class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields17".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Positive air shutoff on diesel trucks and welders?</label>
	<div class="col-sm-8">
	<input type="radio" <?php if ($fields[28] == 'Yes') { echo " checked"; } ?>  name="fields_28" value="Yes">Yes&nbsp;&nbsp;
	<input type="radio" <?php if ($fields[28] == 'No') { echo " checked"; } ?>  name="fields_28" value="No">No&nbsp;&nbsp;
	<input type="text" name="fields_29" value="<?php echo $fields[29]; ?>"   class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields18".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">First aid kit in truck?</label>
	<div class="col-sm-8">
	<input type="radio" <?php if ($fields[30] == 'Yes') { echo " checked"; } ?>  name="fields_30" value="Yes">Yes&nbsp;&nbsp;
	<input type="radio" <?php if ($fields[30] == 'No') { echo " checked"; } ?>  name="fields_30" value="No">No&nbsp;&nbsp;
	<input type="text" name="fields_31" value="<?php echo $fields[31]; ?>"   class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields19".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Fire extinguisher in truck?</label>
	<div class="col-sm-8">
	<input type="radio" <?php if ($fields[32] == 'Yes') { echo " checked"; } ?>  name="fields_32" value="Yes">Yes&nbsp;&nbsp;
	<input type="radio" <?php if ($fields[32] == 'No') { echo " checked"; } ?>  name="fields_32" value="No">No&nbsp;&nbsp;
	<input type="text" name="fields_33" value="<?php echo $fields[33]; ?>"   class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields20".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Overall condition of unit?</label>
	<div class="col-sm-8">
	<input type="radio" <?php if ($fields[34] == 'Yes') { echo " checked"; } ?>  name="fields_34" value="Yes">Yes&nbsp;&nbsp;
	<input type="radio" <?php if ($fields[34] == 'No') { echo " checked"; } ?>  name="fields_34" value="No">No&nbsp;&nbsp;
	<input type="text" name="fields_35" value="<?php echo $fields[35]; ?>"   class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields21".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Corrective Action (if required) to be completed by:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_36" value="<?php echo $fields[36]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<h3>Validation</h3>
<input type="checkbox" style="height: 20px; width: 20px;" value="1" required name="agenda_send_email">&nbsp;Information which I have provided here is true and correct. I have read and understand the content of this policy.<br><br>
Date : <?php echo date('Y-m-d'); ?><br>
Person : <?php echo decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']); ?><br><br>
<?php include ('../phpsign/sign.php'); ?>