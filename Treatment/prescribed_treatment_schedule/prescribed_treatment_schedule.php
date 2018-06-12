<?php
/*
Add	Sheet
*/
include ('../database_connection.php');
include_once('../tcpdf/tcpdf.php');
require_once('../phpsign/signature-to-image.php');
error_reporting(0);
?>
<style>
.form-control {
    width: 40%;
    display: inline;
}
</style>
<script>
</script>
</head>
<body>

<?php
$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_patientform WHERE form='$form'"));
$form_config = ','.$get_field_config['fields'].',';
?>

<?php if (strpos(','.$form_config.',', ',fields2,') !== FALSE) { ?>
Patient : <select data-placeholder="Choose a Patient..." name="patientid" class="chosen-select-deselect form-control" width="380">
    <option value=""></option>
    <?php
    $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Patient' AND status>0 AND deleted=0");
    while($row = mysqli_fetch_array($query)) {
        echo "<option value='". $row['contactid']."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';
    }
    ?>
</select>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields3,') !== FALSE) { ?>
    <br><br>Date : <?php echo date('Y-m-d'); ?>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields4,') !== FALSE) { ?>
<br><br>
Therapist : <select data-placeholder="Choose a Therapist..." name="therapist" class="chosen-select-deselect form-control" width="380">
	<option value=""></option>
	  <?php
		$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND (category_contact = 'Physical Therapist' OR category_contact = 'Massage Therapist' OR category_contact = 'Osteopathic Therapist') AND deleted=0 AND `status`>0"),MYSQLI_ASSOC));
		foreach($query as $id) {
			$selected = '';
			//$selected = $id == $contactid ? 'selected = "selected"' : '';
			echo "<option " . $selected . "value='". get_contact($dbc, $id)."'>".get_contact($dbc, $id).'</option>';
		}
	  ?>
</select>
<?php } ?>

<h3>Initital Personal Treatment Plan</h3>
<?php if (strpos(','.$form_config.',', ',fields5,') !== FALSE) { ?>
<br><br>
Initial Treatment Schedule : <textarea name="initial_treatment" rows="5" cols="50" class="form-control"></textarea>
<?php } ?>

<h3>Re-Evaluation</h3>
<?php if (strpos(','.$form_config.',', ',fields6,') !== FALSE) { ?>
<br><br>
Re-Evaluation Date : <input name="reev_date" type="text" class="datepicker">
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields7,') !== FALSE) { ?>
<br><br>
Re-Evaluation Treatment Schedule : <textarea name="reev_treatment" rows="5" cols="50" class="form-control"></textarea>
<?php } ?>

<h3>Required Dates & Description</h3>
<?php if (strpos(','.$form_config.',', ',fields8,') !== FALSE) { ?>
<br><br>
Surgery Date : <input name="surgery_date" type="text" class="datepicker">
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields9,') !== FALSE) { ?>
<br><br>
X-Rays : <textarea name="xrays" rows="5" cols="50" class="form-control"></textarea>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields10,') !== FALSE) { ?>
<br><br>
MRI/US : <textarea name="mri_us" rows="5" cols="50" class="form-control"></textarea>
<?php } ?>