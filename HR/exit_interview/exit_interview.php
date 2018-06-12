<?php
/*
Add	Sheet
*/
include_once ('../database_connection.php');
error_reporting(0);
?>
<style>
.form-control {
    width: 70%;
    display: inline;
}
</style>
</head>
<body>

<?php
$today_date = date('Y-m-d');
$contactid = $_SESSION['contactid'];
$fields = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];
	echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_exit_interview WHERE fieldlevelriskid='$formid'"));
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
	<label for="business_street" class="col-sm-4 control-label">Employee Name:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_0" value="<?php echo $fields[0]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Date of Exit:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_1" value="<?php echo $fields[1]; ?>" class="datepicker" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
    <h3>What is the main reason you decided to leave your job?</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">1. What is the main reason you decided to leave your job?</label>
	<div class="col-sm-8">
	<textarea name="fields_2" rows="3" cols="50" class="form-control"><?php echo $fields[2]; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
    <h3>What did you like best about your job/working for the Company?</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">2. What did you like best about your job/working for the Company?</label>
	<div class="col-sm-8">
	<textarea name="fields_3" rows="3" cols="50" class="form-control"><?php echo $fields[3]; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
    <h3>What did you like least about your job/working for the Company?</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">3. What did you like least about your job/working for the Company?</label>
	<div class="col-sm-8">
	<textarea name="fields_4" rows="3" cols="50" class="form-control"><?php echo $fields[4]; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
    <h3>How did you feel about the way you were managed daily/weekly/monthly? Did you feel your manager was available when you needed them?</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">4. How did you feel about the way you were managed daily/weekly/monthly? Did you feel your manager was available when you needed them?</label>
	<div class="col-sm-8">
	<textarea name="fields_5" rows="3" cols="50" class="form-control"><?php echo $fields[5]; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields7".',') !== FALSE) { ?>
    <h3>How did you feel about the level of supervision and feedback you received?</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">5. How did you feel about the level of supervision and feedback you received?</label>
	<div class="col-sm-8">
	<textarea name="fields_6" rows="3" cols="50" class="form-control"><?php echo $fields[6]; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields8".',') !== FALSE) { ?>
    <h3>Did you feel the expectations and demands that were placed on you were fair and manageable? Were you capable of taking on more responsibility and held back in any way?</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">6. Did you feel the expectations and demands that were placed on you were fair and manageable? Were you capable of taking on more responsibility and held back in any way?</label>
	<div class="col-sm-8">
	<textarea name="fields_7" rows="3" cols="50" class="form-control"><?php echo $fields[7]; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields9".',') !== FALSE) { ?>
    <h3>How did you feel about your coworkers, the office environment, and morale? Did you feel like a valued and included member of the team?</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">7. How did you feel about your coworkers, the office environment, and morale? Did you feel like a valued and included member of the team?</label>
	<div class="col-sm-8">
	<textarea name="fields_8" rows="3" cols="50" class="form-control"><?php echo $fields[8]; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields10".',') !== FALSE) { ?>
    <h3>What could the Company do to increase morale and employee happiness to retain its best employees?</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">8. What could the Company do to increase morale and employee happiness to retain its best employees?</label>
	<div class="col-sm-8">
	<textarea name="fields_9" rows="3" cols="50" class="form-control"><?php echo $fields[9]; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields11".',') !== FALSE) { ?>
    <h3>According to you who was a strong leader? Did the staff feel they were approachable?</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">9. According to you who was a strong leader? Did the staff feel they were approachable?</label>
	<div class="col-sm-8">
	<textarea name="fields_10" rows="3" cols="50" class="form-control"><?php echo $fields[10]; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields12".',') !== FALSE) { ?>
    <h3>Did you feel that the management team communicated well with the staff? Were goals and objectives to be met clearly outlined?</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">10. Did you feel that the management team communicated well with the staff? Were goals and objectives to be met clearly outlined?</label>
	<div class="col-sm-8">
	<textarea name="fields_11" rows="3" cols="50" class="form-control"><?php echo $fields[11]; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields14".',') !== FALSE) { ?>
    <h3>Do you feel that the company will continue to be successful in the future? Why or why not?</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">11. Do you feel that the company will continue to be successful in the future? Why or why not?</label>
	<div class="col-sm-8">
	<textarea name="fields_13" rows="3" cols="50" class="form-control"><?php echo $fields[13]; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields15".',') !== FALSE) { ?>
    <h3>What would you suggest we do to make the company a better place to work?</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">12. What would you suggest we do to make the company a better place to work?</label>
	<div class="col-sm-8">
	<textarea name="fields_14" rows="3" cols="50" class="form-control"><?php echo $fields[14]; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields16".',') !== FALSE) { ?>
    <h3>Would you work for the company again in the future?</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">13. Would you work for the company again in the future?</label>
	<div class="col-sm-8">
	<textarea name="fields_15" rows="3" cols="50" class="form-control"><?php echo $fields[15]; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields17".',') !== FALSE) { ?>
    <h3>Do you have any other comments or insights about your time at the company?</h3>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">14. Do you have any other comments or insights about your time at the company?</label>
	<div class="col-sm-8">
	<textarea name="fields_16" rows="3" cols="50" class="form-control"><?php echo $fields[16]; ?></textarea>
	</div>
	</div>
<?php } ?>

<h3>Validation</h3>
Thank you for your honesty and opinions!<br><br>

<input type="checkbox" style="height: 20px; width: 20px;" value="1" required name="agenda_send_email">&nbsp;Information which I have provided here is true and correct. I have read and understand the content of this policy.<br><br>
Date : <?php echo date('Y-m-d'); ?><br>
Person : <?php echo decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']); ?><br><br>
</h4>
<?php include ('../phpsign/sign.php'); ?>