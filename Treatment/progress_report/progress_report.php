<?php
/*
Add	Sheet
*/
include ('../database_connection.php');
include_once('../tcpdf/tcpdf.php');
error_reporting(0);
?>
<style>
.form-control {
    width: 40%;
    display: inline;
}
</style>
</head>
<body>

<?php
$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_patientform WHERE form='$form'"));
$form_config = ','.$get_field_config['fields'].',';
?>

<?php if (strpos(','.$form_config.',', ',fields1,') !== FALSE) { ?>
<h4>Send this form to the appropriate insurer: </h4>
Fax #:<input name="fields_value_1" type="text" class="form-control"><br><br>
<?php } ?>

<h4>Progress Report</h4>
<?php if (strpos(','.$form_config.',', ',fields2,') !== FALSE) { ?>
Insurance Company:
<select name="fields_value_2" id="payer_name" class="chosen-select-deselect form-control" width="380">
<option value=""></option>
    <?php
    $query = mysqli_query($dbc,"SELECT contactid, name FROM contacts WHERE category='Insurer' AND deleted=0 ORDER BY name");
    while($row = mysqli_fetch_array($query)) {
        echo "<option value='". $row['contactid']."'>".$row['name']. '</option>';
    }
    ?>
</select><br><br>
<br><br>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields3,') !== FALSE) { ?>
Policy Number:<input name="fields_value_3" type="text" class="form-control"><br><br>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields4,') !== FALSE) { ?>
Date of Accident:<input name="fields_value_4" type="text" class="datepicker"><br><br>
<?php } ?>

<h4>Part 1 - Claimant Information</h4>
<?php if (strpos(','.$form_config.',', ',fields5,') !== FALSE) { ?>
Patient:
    <select data-placeholder="Choose a Patient..." name="fields_value_5" class="chosen-select-deselect form-control" width="380">
        <option value=""></option>
        <?php
        $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name, birth_date FROM contacts WHERE category='Patient' AND status>0 AND deleted=0");
        while($row = mysqli_fetch_array($query)) {
            echo "<option value='". $row['contactid']."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';
        }
        ?>
    </select><br><br>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields6,') !== FALSE) { ?>
Date of Initial Assessment:<input name="fields_value_6" type="text" class="datepicker"><br><br>
<?php } ?>
<h4>Part 2 - Information of Primary Health Care Practitioner</h4>
<?php if (strpos(','.$form_config.',', ',fields7,') !== FALSE) { ?>
Name of Professional:
    <select data-placeholder="Choose a Therapist..." name="fields_value_7" class="chosen-select-deselect form-control" width="380">
        <option value=""></option>
        <?php
        $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND (category_contact = 'Physical Therapist' OR category_contact = 'Massage Therapist' OR category_contact = 'Osteopathic Therapist')  AND deleted=0");
        while($row = mysqli_fetch_array($query)) {
            echo "<option value='". $row['contactid']."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';
        }
        ?>
    </select><br><br>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields8,') !== FALSE) { ?>
Profession:<input name="fields_value_8" type="text" class="form-control"><br><br>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields9,') !== FALSE) { ?>
Address:<input name="fields_value_9" type="text" class="form-control"><br><br>
City, Town or County:<input name="fields_value_10" type="text" class="form-control"><br><br>
Province:<input name="fields_value_11" type="text" class="form-control"><br><br>
Postal Code:<input name="fields_value_12" type="text" class="form-control"><br><br>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields10,') !== FALSE) { ?>
Administrative Contact Name:<input name="fields_value_13" type="text" class="form-control"><br><br>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields11,') !== FALSE) { ?>
Facility Name:<input name="fields_value_14" type="text" class="form-control"><br><br>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields12,') !== FALSE) { ?>
Telephone Number (Include area code) :<input name="fields_value_15" type="text" class="form-control"><br><br>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields13,') !== FALSE) { ?>
Fax Number (Include area code):<input name="fields_value_16" type="text" class="form-control"><br><br>
<?php } ?>
<h4>Part 3 - Therapy Status Report</h4>
<?php if (strpos(','.$form_config.',', ',fields14,') !== FALSE) { ?>
Diagnosis WAD : <textarea name="fields_value_17" rows="5" cols="50" class="form-control"></textarea><br><br>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields15,') !== FALSE) { ?>
Key Subjective/Physical Examination Findings : <textarea name="fields_value_18" rows="5" cols="50" class="form-control"></textarea><br><br>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields16,') !== FALSE) { ?>
<?php } ?>
C/O  : <textarea name="fields_value_19" rows="5" cols="50" class="form-control"></textarea><br><br>
<?php if (strpos(','.$form_config.',', ',fields17,') !== FALSE) { ?>
O/E : <textarea name="fields_value_20" rows="5" cols="50" class="form-control"></textarea><br><br>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields18,') !== FALSE) { ?>
Functional Goals:<br>
1.ROM:<input name="fields_value_21" type="text" class="form-control"><br><br>
2.Strength:<input name="fields_value_22" type="text" class="form-control"><br><br>
3.Endurance:<input name="fields_value_23" type="text" class="form-control"><br><br>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields19,') !== FALSE) { ?>
Progress towards goals:<br>
<input type="checkbox" name="fields_value_24" value="Regressed">&nbsp;&nbsp;Regressed
<input type="checkbox" name="fields_value_25" value="Exercise Prescription">&nbsp;&nbsp;Improved minimally
<input type="checkbox" name="fields_value_26" value="Local Modalities">&nbsp;&nbsp;Improved significantly
<input type="checkbox" name="fields_value_27" value="Resolved">&nbsp;&nbsp;Resolved
<input type="checkbox" name="fields_value_28" value="Plateaued">&nbsp;&nbsp;Plateaued
<input type="checkbox" name="fields_value_29" value="Other">&nbsp;&nbsp;Other<br><br>
(please describe):<input name="fields_value_30" type="text" class="form-control"><br><br>
<?php } ?>

<h4>Part 4 - Signature of Primary Health Care Practitioner</h4>
<?php if (strpos(','.$form_config.',', ',fields20,') !== FALSE) { ?>
Name (Please Print)
    <select data-placeholder="Choose a Therapist..." name="fields_value_31" class="chosen-select-deselect form-control" width="380">
        <option value=""></option>
		  <?php
			$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND (category_contact = 'Physical Therapist' OR category_contact = 'Massage Therapist' OR category_contact = 'Osteopathic Therapist') AND deleted=0 AND `status`>0"),MYSQLI_ASSOC));
			foreach($query as $id) {
				$selected = '';
				//$selected = $id == $contactid ? 'selected = "selected"' : '';
				echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
			}
		  ?>
    </select><br><br>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields21,') !== FALSE) { ?>
<?php include ('../phpsign/sign.php'); ?>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields22,') !== FALSE) { ?>
Date:<input name="fields_value_32" type="text" class="datepicker"><br><br>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields23,') !== FALSE) { ?>
Treatment Plan : <textarea name="fields_value_33" rows="5" cols="50" class="form-control"></textarea><br><br>
<?php } ?>