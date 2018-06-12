<?php
/*
Add	Sheet
*/
include ('../database_connection.php');
include_once('../tcpdf/tcpdf.php');
error_reporting(0);
?>
</head>
<body>

<?php
$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_patientform WHERE form='$form'"));
$form_config = ','.$get_field_config['fields'].',';
?>

<?php if (strpos(','.$form_config.',', ',fields2,') !== FALSE) { ?>
<label class="control-label col-sm-4">Patient :</label> <div class="col-sm-8"><select data-placeholder="Choose a Patient..." name="patientid" class="chosen-select-deselect form-control" width="380">
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
    <br><br><label class="control-label col-sm-4">Date :</label> <div class="col-sm-8"><?php echo date('Y-m-d'); ?></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields4,') !== FALSE) { ?>
<br><br>
<label class="control-label col-sm-4">Diagnostic Code</label> &nbsp; <div class="col-sm-8"><input name="code" type="text" class="form-control"></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields5,') !== FALSE) { ?>
<br><br>
<label class="control-label col-sm-4">Referring Doctor</label> &nbsp; <div class="col-sm-8"><input name="doctor" type="text" class="form-control"></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields6,') !== FALSE) { ?>
<br><br>
<label class="control-label col-sm-4">Program</label> &nbsp; <div class="col-sm-8"><input name="program" type="text" class="form-control"></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields7,') !== FALSE) { ?>
<br><br>
<label class="control-label col-sm-4">Work Status</label> &nbsp; <div class="col-sm-8"><input name="work_status" type="text" class="form-control"></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields8,') !== FALSE) { ?>
<br><br>
<label class="control-label col-sm-4">Treatment Description</label> &nbsp; <div class="col-sm-8"><textarea name="desc" rows="5" cols="50" class="form-control"></textarea></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields9,') !== FALSE) { ?>
<br><br>
<label class="control-label col-sm-4">Treatment Date</label> &nbsp; <div class="col-sm-8"><input name="treatment_date" type="text" class="datepicker form-control"></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields10,') !== FALSE) { ?>
<br><br>
<label class="control-label col-sm-4">Physio</label> &nbsp; <div class="col-sm-8"><input name="physio" type="text" class="form-control"></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields11,') !== FALSE) { ?>
<br><br>
<label class="control-label col-sm-4">Visit #</label> &nbsp; <div class="col-sm-8"><input name="visit" type="text" class="form-control"></div>
<?php } ?>