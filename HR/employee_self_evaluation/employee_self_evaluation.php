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
$desc5 = '';
$desc6 = '';
$desc7 = '';
$desc8 = '';
$desc9 = '';
$desc10 = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];
	echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_employee_self_evaluation WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
    $fields = explode('**FFM**', $get_field_level['fields']);
	$desc = $get_field_level['desc'];
	$desc1 = $get_field_level['desc1'];
	$desc2 = $get_field_level['desc2'];
	$desc3 = $get_field_level['desc3'];
	$desc4 = $get_field_level['desc4'];
	$desc5 = $get_field_level['desc5'];
	$desc6 = $get_field_level['desc6'];
	$desc7 = $get_field_level['desc7'];
	$desc8 = $get_field_level['desc8'];
	$desc9 = $get_field_level['desc9'];
	$desc10 = $get_field_level['desc10'];
}

$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM `hr` WHERE tab LIKE '$tab' AND form='$form'"));
$form_config = ','.$get_field_config['fields'].',';
?>

<h3>Information</h3>
<?php if (strpos($form_config, ','."fields1".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Employee Name:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_0" value="<?php echo $fields[0]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Job Title:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_1" value="<?php echo $fields[1]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Manager:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_2" value="<?php echo $fields[2]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Date:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_3" value="<?php echo $fields[3]; ?>" class="datepicker" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
    <h3>Goals - Describe the goals you had set out to accomplish for this time period</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Describe the goals you had set out to accomplish for this time period:</label>
	<div class="col-sm-8">
	<textarea name="desc" rows="3" cols="50" class="form-control"><?php echo $desc; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
    <h3>Goals - Which goals did you accomplish?</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Which goals did you accomplish?</label>
	<div class="col-sm-8">
	<textarea name="desc1" rows="3" cols="50" class="form-control"><?php echo $desc1; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields7".',') !== FALSE) { ?>
    <h3>Goals - Which goals were not accomplished and why?</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Which goals were not accomplished and why?</label>
	<div class="col-sm-8">
	<textarea name="desc2" rows="3" cols="50" class="form-control"><?php echo $desc2; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields8".',') !== FALSE) { ?>
    <h3>Goals - What other objectives did you meet beyond your stated goals?</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">What other objectives did you meet beyond your stated goals?</label>
	<div class="col-sm-8">
	<textarea name="desc3" rows="3" cols="50" class="form-control"><?php echo $desc3; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields9".',') !== FALSE) { ?>
    <h3>Goals - What achievements are you most proud of?</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">What achievements are you most proud of?</label>
	<div class="col-sm-8">
	<textarea name="desc4" rows="3" cols="50" class="form-control"><?php echo $desc4; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields10".',') !== FALSE) { ?>
    <h3>Expectations - What are your goals for the next evaluation period? Please be clear and concise.</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">What are your goals for the next evaluation period? Please be clear and concise.</label>
	<div class="col-sm-8">
	<textarea name="desc5" rows="3" cols="50" class="form-control"><?php echo $desc5; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields11".',') !== FALSE) { ?>
    <h3>Expectations - What can your supervisor do to help you achieve your future goals?</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">What can your supervisor do to help you achieve your future goals?</label>
	<div class="col-sm-8">
	<textarea name="desc6" rows="3" cols="50" class="form-control"><?php echo $desc6; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields12".',') !== FALSE) { ?>
    <h3>The Company - What as a company do we do well?</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">What as a company do we do well?</label>
	<div class="col-sm-8">
	<textarea name="desc7" rows="3" cols="50" class="form-control"><?php echo $desc7; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields13".',') !== FALSE) { ?>
    <h3>The Company - What as a company could we improve on?</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">What as a company could we improve on?</label>
	<div class="col-sm-8">
	<textarea name="desc8" rows="3" cols="50" class="form-control"><?php echo $desc8; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields14".',') !== FALSE) { ?>
    <h3>The Company - How could you assist with this improvement?</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">How could you assist with this improvement?</label>
	<div class="col-sm-8">
	<textarea name="desc9" rows="3" cols="50" class="form-control"><?php echo $desc9; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields15".',') !== FALSE) { ?>
    <h3>Additional Comments</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Additional Comments:</label>
	<div class="col-sm-8">
	<textarea name="desc10" rows="3" cols="50" class="form-control"><?php echo $desc10; ?></textarea>
	</div>
	</div>
<?php } ?>

<h3>Validation</h3>
<input type="checkbox" style="height: 20px; width: 20px;" value="1" required name="agenda_send_email">&nbsp;Information which I have provided here is true and correct. I have read and understand the content of this policy.<br><br>
Date : <?php echo date('Y-m-d'); ?><br>
Person : <?php echo decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']); ?><br><br>
</h4>
<?php include ('../phpsign/sign.php'); ?>