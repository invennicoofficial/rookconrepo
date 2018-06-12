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
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_employee_driver_information_form WHERE fieldlevelriskid='$formid'"));
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
	<label for="business_street" class="col-sm-4 control-label">Driver's Licence Number:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_1" value="<?php echo $fields[1]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Expiry Date:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_2" value="<?php echo $fields[2]; ?>" class="datepicker" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Class:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_3" value="<?php echo $fields[3]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Can you drive a truck with a standard transmission?</label>
	<div class="col-sm-8">
	<input type="radio" <?php if ($fields[4] == 'Yes') { echo " checked"; } ?>  name="fields_4" value="Yes">Yes&nbsp;&nbsp;
	<input type="radio" <?php if ($fields[4] == 'No') { echo " checked"; } ?>  name="fields_4" value="No">No&nbsp;&nbsp;
	<input type="text" name="fields_5" value="<?php echo $fields[5]; ?>"   class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Do you have a current TDG(Transportation of Dangerous Goods) Ticket?</label>
	<div class="col-sm-8">
	<input type="radio" <?php if ($fields[6] == 'Yes') { echo " checked"; } ?>  name="fields_6" value="Yes">Yes&nbsp;&nbsp;
	<input type="radio" <?php if ($fields[6] == 'No') { echo " checked"; } ?>  name="fields_6" value="No">No&nbsp;&nbsp;
	<input type="text" name="fields_7" value="<?php echo $fields[7]; ?>"   class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields7".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">TDG expiry Date:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_8" value="<?php echo $fields[8]; ?>" class="datepicker" />
	</div>
	</div>
<?php } ?>

As an employee of the Company you are to follow all laws of the road. If at any time Company representatives feel that you are not operating equipment in a safe and courteous manner, the Company reserves the right to suspend you from operating any or all equipment.<br><br>

<h3>Vehicle Policy</h3>
The Company provides certain employees with vehicles in order to carry out the duties and functions related to their positions. The use of a Company vehicle is subject to conditions and restrictions, which must be observed by all employees assigned to operate a Company vehicle.<br><br>

When you operate a Company vehicle, you accept certain responsibilities to yourself, to the Company, and to the public. Our name and reputation rides with you. It is expected that you will drive with care at all times using common sense and good judgement.<br><br>

The Company is committed to ensuring that all vehicles provided for the use of employees are in good working order, operated in a safe manner.<br><br>

All Company employees eligible for vehicle assignment must be in possession of a valid driver's license in good standing. The driver's license must be of the appropriate class and free of limiting restrictions with respect to the vehicle assigned to the employee. A copy of the driver's license and abstract is required for insurance purpose.<br><br>

All Company vehicles must be operated in a safe and responsible manner in accordance with applicable traffic laws and regulations. The employee must ensure the vehicle is in proper working order. This includes regular and required maintenance.<br><br>

Employees are responsible for all fines and tickets resulting from the operation of the Company vehicle to with they are assigned or operating. This includes any moving violations, photo radar tickets, parking tickets, or any other violations of applicable traffic laws and regulations.<br><br>

The operation of any Company vehicle while the driver is under the influence of alcohol or intoxicating drugs is prohibited.<br><br>

All persons in a Company vehicle must wear seat belts at all times.<br><br>

Failure to comply with the Company policy may result in disciplinary action, up to and including dismissal.<br><br>

<h3>PERSONAL USE</h3>

Personal use of Company vehicles shall be limited to the employee to which the vehicle is assigned. The Company may permit, at the discretion of the Company and with prior notification to the employee, immediate members of the employee's family to operate the vehicle subject to the conditions of this policy and other policies of the company.<br><br>

At no time shall a Company vehicle be operated by an individual under the age of twenty one (21) years of age.<br><br>

The employee's responsibilities regarding the use and operation of the Company vehicle under this policy extends to any personal use of the Company vehicle.<br><br>

<h3>Validation</h3>
<h4><input type="checkbox" style="height: 20px; width: 20px;" value="1" required name="agenda_send_email">&nbsp;Information which I have provided here is true and correct. I have read and understand the content of this policy.<br><br>
Date : <?php echo date('Y-m-d'); ?><br>
Person : <?php echo decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']); ?><br><br>
</h4>
<?php include ('../phpsign/sign.php'); ?>