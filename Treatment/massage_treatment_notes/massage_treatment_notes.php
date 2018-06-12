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
    $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Patient' AND status>0 AND deleted=0");
    while($row = mysqli_fetch_array($query)) {
        echo "<option value='". $row['contactid']."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';
    }
    ?>
</select></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields3,') !== FALSE) { ?>
    <br><br>
	<label class="control-label col-sm-4">Date :</label> <div class="col-sm-8"><?php echo date('Y-m-d'); ?></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields4,') !== FALSE) { ?>
<br><br>
<label class="control-label col-sm-4">Therapist :</label> <div class="col-sm-8"><select data-placeholder="Choose a Therapist..." name="therapist" class="chosen-select-deselect form-control" width="380">
	<option value=""></option>
	  <?php
		$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND (category_contact = 'Physical Therapist' OR category_contact = 'Massage Therapist' OR category_contact = 'Osteopathic Therapist') AND deleted=0 AND `status`>0"),MYSQLI_ASSOC));
		foreach($query as $id) {
			$selected = '';
			//$selected = $id == $contactid ? 'selected = "selected"' : '';
			echo "<option " . $selected . "value='". get_contact($dbc, $id)."'>".get_contact($dbc, $id).'</option>';
		}
	  ?>
</select></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields5,') !== FALSE) { ?>
<br><br>
<label class="control-label col-sm-4">Notes :</label> <div class="col-sm-8"><textarea name="notes" rows="5" cols="50" class="form-control"></textarea></div>
<?php } ?>