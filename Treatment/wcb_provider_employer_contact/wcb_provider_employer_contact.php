<?php
/*
Add	Sheet
*/
include ('../database_connection.php');
include_once('../tcpdf/tcpdf.php');
require_once('../phpsign/signature-to-image.php');
error_reporting(0);
?>
<script>
</script>
</head>
<body>

<?php
$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_patientform WHERE form='$form'"));
$form_config = ','.$get_field_config['fields'].',';
?>

<?php if (strpos(','.$form_config.',', ',fields2,') !== FALSE) { ?>
<label class="control-label col-sm-4">Patient :</label><div class="col-sm-8">
<select data-placeholder="Choose a Patient..." name="patientid" class="chosen-select-deselect form-control" width="380">
    <option value=""></option>
	  <?php
		$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Patient' AND deleted=0 AND `status`>0"),MYSQLI_ASSOC));
		foreach($query as $id) {
			$selected = '';
			//$selected = $id == $contactid ? 'selected = "selected"' : '';
			echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
		}
	  ?>
</select></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields3,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Date:</label> <?php echo date('Y-m-d'); ?></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields4,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">WCB Claim Number</label><div class="col-sm-8"><input name="wcb" type="text" class="form-control"></div></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields5,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Personal Health Care Number</label><div class="col-sm-8"><input name="health_card" type="text" class="form-control"></div></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields6,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Worker's Surname</label><div class="col-sm-8"><input name="surname" type="text" class="form-control"></div></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields7,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">First Name</label><div class="col-sm-8"><input name="first_name" type="text" class="form-control"></div></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields8,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Date of Birth</label><div class="col-sm-8"><input name="birth_date" type="text" class="datepicker form-control"></div></div>
<?php } ?>

<br><br>
<h3>Employer Contact Information</h3>

<?php if (strpos(','.$form_config.',', ',fields9,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Employer Name</label><div class="col-sm-8"><input name="employer" type="text" class="form-control"></div></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields10,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Person Contacted</label><div class="col-sm-8"><input name="person_contacted" type="text" class="form-control"></div></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields11,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Supervisor</label><div class="col-sm-8"><input name="supervisor" type="text" class="form-control"></div></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields12,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Phone Number</label><div class="col-sm-8"><input name="phone_number" type="text" class="form-control"></div></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields13,') !== FALSE) { ?>
<br><div class="form-group"><label class="control-label col-sm-4">Date Contacted</label><div class="col-sm-8"><input name="date_contacted" type="text" class="datepicker form-control"></div></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields14,') !== FALSE) { ?>
<br><div class="form-group"><div class="col-sm-8 pull-right">Are modified duties available?
<ul style="list-style-type: none;">
    <li><input type="radio" name="modified" value="Yes">&nbsp;&nbsp;Yes</li>
    <li><input type="radio" name="modified" value="No">&nbsp;&nbsp;No</li>
</ul></div></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields15,') !== FALSE) { ?>
<div class="form-group"><div class="col-sm-8 pull-right">Alternate duties available?
<ul style="list-style-type: none;">
    <li><input type="radio" name="alternate" value="Yes">&nbsp;&nbsp;Yes</li>
    <li><input type="radio" name="alternate" value="No">&nbsp;&nbsp;No</li>
</ul></div></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields16,') !== FALSE) { ?>
<div class="form-group"><div class="col-sm-8 pull-right">Is a return to work schedule available?
<ul style="list-style-type: none;">
    <li><input type="radio" name="return" value="Yes">&nbsp;&nbsp;Yes</li>
    <li><input type="radio" name="return" value="No">&nbsp;&nbsp;No</li>
</ul></div></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields17,') !== FALSE) { ?>
<div class="form-group"><div class="col-sm-8 pull-right">Is a physical Job demand analysis available?
<ul style="list-style-type: none;">
    <li><input type="radio" name="physical" value="Yes">&nbsp;&nbsp;Yes</li>
    <li><input type="radio" name="physical" value="No">&nbsp;&nbsp;No</li>
</ul></div></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields18,') !== FALSE) { ?>
<div class="form-group"><div class="col-sm-8 pull-right">Has it been requested?
<ul style="list-style-type: none;">
    <li><input type="radio" name="requested" value="Yes">&nbsp;&nbsp;Yes</li>
    <li><input type="radio" name="requested" value="No">&nbsp;&nbsp;No</li>
</ul></div></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields19,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Additional Comments:</label><div class="col-sm-8"><textarea name="goals" rows="5" cols="50" class="form-control"></textarea></div></div>
<?php } ?>