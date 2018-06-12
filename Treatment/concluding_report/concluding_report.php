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

<h4>To be completed by Claimant / Representative or a Primary Health Care Practitioner</h4>
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
Scheduling Contact Name:<input name="fields_value_13" type="text" class="form-control"><br><br>
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
<h4>Part 3 - Therapy Status Report (To be completed by Primary Health Care Practitioner)</h4>
<?php if (strpos(','.$form_config.',', ',fields14,') !== FALSE) { ?>
Diagnosis at Initial Assessment : <textarea name="fields_value_17" rows="5" cols="50" class="form-control"></textarea><br><br>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields15,') !== FALSE) { ?>
Key Subjective/Physical Examination Findings at the last visit : <textarea name="fields_value_18" rows="5" cols="50" class="form-control"></textarea><br><br>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields16,') !== FALSE) { ?>
C/O  : <textarea name="fields_value_19" rows="5" cols="50" class="form-control"></textarea><br><br>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields17,') !== FALSE) { ?>
O/E : <textarea name="fields_value_20" rows="5" cols="50" class="form-control"></textarea><br><br>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields18,') !== FALSE) { ?>
Functional Goals:<br>
1.Restore ROM:<input name="fields_value_21" type="text" class="form-control"><br><br>
2.Restore Strength:<input name="fields_value_22" type="text" class="form-control"><br><br>
3.Restore Endurance and Function for Work:<input name="fields_value_23" type="text" class="form-control">% and play <input name="fields_value_24" type="text" class="form-control">%<br><br>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields19,') !== FALSE) { ?>
Progress towards goals:<br>
<input type="checkbox" name="fields_value_25" value="Regressed">&nbsp;&nbsp;Regressed
<input type="checkbox" name="fields_value_26" value="Exercise Prescription">&nbsp;&nbsp;Improved minimally
<input type="checkbox" name="fields_value_27" value="Local Modalities">&nbsp;&nbsp;Improved significantly
<input type="checkbox" name="fields_value_28" value="Resolved">&nbsp;&nbsp;Resolved
<input type="checkbox" name="fields_value_29" value="Plateaued">&nbsp;&nbsp;Plateaued
<input type="checkbox" name="fields_value_30" value="Other">&nbsp;&nbsp;Other<br><br>
(please describe):<input name="fields_value_31" type="text" class="form-control"><br><br>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields20,') !== FALSE) { ?>
Outcome measures<br>
Patient Specific functional Scale:<input name="fields_value_32" type="text" class="form-control"> / 30<br><br>
Neck Disability Index:<input name="fields_value_33" type="text" class="form-control"> / 50<br><br>
Roland Morris(back pain):<input name="fields_value_34" type="text" class="form-control"> / 24<br><br>
Static Endurance Test:<input name="fields_value_35" type="text" class="form-control"><br><br>
Chin Tuck Head Lift:<input name="fields_value_36" type="text" class="form-control"><br><br>
Prone Plank:<input name="fields_value_37" type="text" class="form-control"><br><br>
<?php } ?>

<h4>Part 4 - Treatment Summary</h4>
<?php if (strpos(','.$form_config.',', ',fields21,') !== FALSE) { ?>
Total Number of Treatments:<input name="fields_value_38" type="text" class="form-control"><br><br>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields22,') !== FALSE) { ?>
Date of First Visit:<input name="fields_value_39" type="text" class="form-control"><br><br>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields23,') !== FALSE) { ?>
Date of Last Visit:<input name="fields_value_40" type="text" class="form-control"><br><br>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields24,') !== FALSE) { ?>
Total Cancelled/Missed Visits:<input name="fields_value_41" type="text" class="form-control"><br><br>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields25,') !== FALSE) { ?>
<h4>Part 5 - Reason for Discharge or need for ongoing Treatment</h4>
    <input type="radio" name="fields_value_42" value="Full Recovery">&nbsp;&nbsp;Full Recovery
    <input type="radio" name="fields_value_42" value="Partial Recovery">&nbsp;&nbsp;Partial Recovery
    <input type="radio" name="fields_value_42" value="Plateaued">&nbsp;&nbsp;Plateaued
    <input type="radio" name="fields_value_42" value="No Progress">&nbsp;&nbsp;No Progress
    <input type="radio" name="fields_value_42" value="Transferred to another treatment site">&nbsp;&nbsp;Transferred to another treatment site
    <input type="radio" name="fields_value_42" value="Non-Attendance">&nbsp;&nbsp;Non-Attendance
    <input type="radio" name="fields_value_42" value="Poor Compliance">&nbsp;&nbsp;Poor Compliance

    <input type="radio" name="fields_value_42" value="No Contact">&nbsp;&nbsp;No Contact
    <input type="radio" name="fields_value_42" value="Other">&nbsp;&nbsp;Other
    <br><br>
    (please describe):<input name="fields_value_43" type="text" class="form-control"><br><br>
<?php } ?>

<h4>Part 6 - Discharge Status</h4>
<?php if (strpos(','.$form_config.',', ',fields26,') !== FALSE) { ?>
Is the claimant now working?&nbsp;&nbsp;
    <input type="radio" name="fields_value_44" value="Yes">&nbsp;&nbsp;Yes
    <input type="radio" name="fields_value_44" value="No">&nbsp;&nbsp;No
    <input type="radio" name="fields_value_44" value="Unknown">&nbsp;&nbsp;Unknown
<br><br>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields27,') !== FALSE) { ?>
Are they employed or engaged in training activities?
    <input type="radio" name="fields_value_45" value="Full Time">&nbsp;&nbsp;Full Time
    <input type="radio" name="fields_value_45" value="Retired Part Time">&nbsp;&nbsp;Retired Part Time
    <input type="radio" name="fields_value_45" value="Seasonal">&nbsp;&nbsp;Seasonal
    <input type="radio" name="fields_value_45" value="Self">&nbsp;&nbsp;Self
    <input type="radio" name="fields_value_45" value="Student">&nbsp;&nbsp;Student
    <input type="radio" name="fields_value_45" value="Not employed">&nbsp;&nbsp;Not employed
    <br><br>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields28,') !== FALSE) { ?>
Work or Training Restrictions?&nbsp;&nbsp;
    <input type="radio" name="fields_value_46" value="Yes">&nbsp;&nbsp;Yes
    <input type="radio" name="fields_value_46" value="None">&nbsp;&nbsp;None<br>
    If Yes, : &nbsp;&nbsp;
    <input type="radio" name="fields_value_47" value="Temporary Restriction">&nbsp;&nbsp;Temporary Restriction
    <input type="radio" name="fields_value_47" value="Permanent Restriction">&nbsp;&nbsp;Permanent Restriction<br>
<br><br>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields29,') !== FALSE) { ?>
Has the claimant returned to a pre-accident level of activity outside work?&nbsp;&nbsp;
    <input type="radio" name="fields_value_48" value="Yes">&nbsp;&nbsp;Yes
    <input type="radio" name="fields_value_48" value="No">&nbsp;&nbsp;No
<br><br>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields30,') !== FALSE) { ?>
Did you refer the claimant to any other health care provider(s)?&nbsp;&nbsp;
    <input type="radio" name="fields_value_49" value="Yes">&nbsp;&nbsp;Yes
    <input type="radio" name="fields_value_49" value="No">&nbsp;&nbsp;No
<br><br>
If Yes, who? Massage : <input name="fields_value_50" type="text" class="form-control"><br><br>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields31,') !== FALSE) { ?>
Discharge comments (residual symptoms, signs, prognosis, details of exercise program, etc.) : <br>  <textarea name="fields_value_51" rows="5" cols="50" class="form-control"></textarea><br><br>
<?php } ?>
<h4>Part 7 - Signature of Primary Health Care Practitioner</h4>
<?php if (strpos(','.$form_config.',', ',fields32,') !== FALSE) { ?>
Name (Please Print)
    <select data-placeholder="Choose a Therapist..." name="fields_value_52" class="chosen-select-deselect form-control" width="380">
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
<?php if (strpos(','.$form_config.',', ',fields33,') !== FALSE) { ?>
<?php include ('../phpsign/sign.php'); ?>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields34,') !== FALSE) { ?>
Date:<input name="fields_value_53" type="text" class="datepicker"><br><br>
<?php } ?>