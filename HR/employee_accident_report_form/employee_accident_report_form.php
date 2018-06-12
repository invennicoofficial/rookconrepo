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
$desc2 = '';
$desc3 = '';
$desc4 = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];
	echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_employee_accident_report_form WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
    $fields = explode('**FFM**', $get_field_level['fields']);
	$desc = $get_field_level['desc'];
	$desc1 = $get_field_level['desc1'];
	$desc2 = $get_field_level['desc2'];
	$desc3 = $get_field_level['desc3'];
	$desc4 = $get_field_level['desc4'];

}

$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM `hr` WHERE tab LIKE '$tab' AND form='$form'"));
$form_config = ','.$get_field_config['fields'].',';
?>

<h3>Information</h3>
<?php if (strpos($form_config, ','."fields1".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Employee Full Name:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_0" value="<?php echo $fields[0]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
    <h3>Time, Date & Location of Accident</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Time, Date & Location of Accident:</label>
	<div class="col-sm-8">
	<textarea name="desc" rows="3" cols="50" class="form-control"><?php echo $desc; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
    <h3>What injuries are being reported?</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">What injuries are being reported?</label>
	<div class="col-sm-8">
	<textarea name="desc1" rows="3" cols="50" class="form-control"><?php echo $desc1; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
    <h3>How did the accident occur?</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">How did the accident occur?</label>
	<div class="col-sm-8">
	<textarea name="desc2" rows="3" cols="50" class="form-control"><?php echo $desc2; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
    <h3>Witnesses to the accident</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Witnesses to the accident:</label>
	<div class="col-sm-8">
	<textarea name="desc3" rows="3" cols="50" class="form-control"><?php echo $desc3; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
    <h3>Treatment or first aid provided</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Treatment or first aid provided:</label>
	<div class="col-sm-8">
	<textarea name="desc4" rows="3" cols="50" class="form-control"><?php echo $desc4; ?></textarea>
	</div>
	</div>
<?php } ?>

<h3>Validation</h3>
By signing, employee consents to the release of medical charts, reports, xrays, diagnoses and other information to the Company or its authorized representatives from any health care provider rendering treatment or providing consultive or other services in conjunction with the diagnosis and treatment of the injury or injuries described above.<br><br>

<input type="checkbox" style="height: 20px; width: 20px;" value="1" required name="agenda_send_email">&nbsp;Information which I have provided here is true and correct. I have read and understand the content of this policy.<br><br>
Date : <?php echo date('Y-m-d'); ?><br>
Person : <?php echo decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']); ?><br><br>
</h4>
<?php include ('../phpsign/sign.php'); ?>