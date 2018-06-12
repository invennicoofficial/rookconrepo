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

<?php $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_patientform WHERE form='$form'"));
$form_config = ','.$get_field_config['fields'].',';
if(str_replace(',','',$form_config) == '') {
	$form_config = ',fields1,fields2,fields3,fields4,fields5,fields6,fields7,fields8,fields9,fields10,fields11,fields12,fields13,fields14,fields15,fields16,fields17,fields18,fields19,fields20,fields21,fields22,fields23,fields24,fields25,fields26,fields27,fields28,fields29,';
} ?>

<?php if (strpos(','.$form_config.',', ',fields1,') !== FALSE) { ?>
<h4>Send this form to the appropriate insurer: </h4>
<div class="form-group"><label class="control-label col-sm-4">Fax #:</label><div class="col-sm-8"><input name="fields_value_1" type="text" class="form-control"></div></div>
<?php } ?>

<h4>To be completed by Claimant / Representative or a Primary Health Care Practitioner</h4>
<?php if (strpos(','.$form_config.',', ',fields2,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Insurance Company:</label>
<div class="col-sm-8"><select name="fields_value_2" id="payer_name" class="chosen-select-deselect form-control" width="380">
<option value=""></option>
    <?php
    $query = mysqli_query($dbc,"SELECT contactid, name FROM contacts WHERE category='Insurer' AND deleted=0 ORDER BY name");
    while($row = mysqli_fetch_array($query)) {
        echo "<option value='". $row['contactid']."'>".decryptIt($row['name']). '</option>';
    }
    ?>
</select></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields3,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Policy Number:</label><div class="col-sm-8"><input name="fields_value_3" type="text" class="form-control"></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields4,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Date of Accident:</label><div class="col-sm-8"><input name="fields_value_4" type="text" class="datepicker form-control"></div></div>
<?php } ?>

<h4>Part 1 - Claimant Information</h4>
<?php if (strpos(','.$form_config.',', ',fields5,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Patient:</label>
    <div class="col-sm-8"><select data-placeholder="Select a Patient..." name="fields_value_5" class="chosen-select-deselect form-control" width="380">
        <option value=""></option>
        <?php
        $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name, birth_date FROM contacts WHERE category='Patient' AND status>0 AND deleted=0");
        while($row = mysqli_fetch_array($query)) {
            echo "<option value='". $row['contactid']."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';
        }
        ?>
    </select></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields6,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Date of Initial Assessment:</label><div class="col-sm-8"><input name="fields_value_6" type="text" class="datepicker form-control"></div></div>
<?php } ?>
<h4>Part 2 - Information of Primary Health Care Practitioner</h4>
<?php if (strpos(','.$form_config.',', ',fields7,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Therapist:</label>
    <div class="col-sm-8"><select data-placeholder="Select a Therapist..." name="fields_value_7" class="chosen-select-deselect form-control" width="380">
        <option value=""></option>
        <?php
        $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND (category_contact = 'Physical Therapist' OR category_contact = 'Massage Therapist' OR category_contact = 'Osteopathic Therapist')  AND deleted=0");
        while($row = mysqli_fetch_array($query)) {
            echo "<option value='". $row['contactid']."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';
        }
        ?>
    </select></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields8,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Address:</label><div class="col-sm-8"><input name="fields_value_8" type="text" class="form-control"></div></div>
<div class="form-group"><label class="control-label col-sm-4">City, Town or County:</label><div class="col-sm-8"><input name="fields_value_9" type="text" class="form-control"></div></div>
<div class="form-group"><label class="control-label col-sm-4">Province:</label><div class="col-sm-8"><input name="fields_value_10" type="text" class="form-control"></div></div>
<div class="form-group"><label class="control-label col-sm-4">Postal Code:</label><div class="col-sm-8"><input name="fields_value_11" type="text" class="form-control"></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields9,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Scheduling Contact Name:</label><div class="col-sm-8"><input name="fields_value_12" type="text" class="form-control"></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields10,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Facility Name:</label><div class="col-sm-8"><input name="fields_value_13" type="text" class="form-control"></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields11,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Telephone Number (Include area code) :</label><div class="col-sm-8"><input name="fields_value_14" type="text" class="form-control"></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields12,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Fax Number (Include area code):</label><div class="col-sm-8"><input name="fields_value_15" type="text" class="form-control"></div></div>
<?php } ?>
<h4>Part 3 - Assessment Status</h4>
<?php if (strpos(','.$form_config.',', ',fields13,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Diagnosis at Initial Assessment :</label><div class="col-sm-8"><select onchange="tinyMCE.get('fields_value_16').setContent(this.value);" class="form-control chosen-select-deselect"><option></option>
<?php $sql = mysqli_query($dbc, "SELECT * FROM `field_config_treatment_presets` WHERE `form`='Treatment Plan' AND `field`='fields13'");
while($row = mysqli_fetch_array($sql)) {
	echo '<option value="'.$row['preset_text'].'">'.$row['preset_text'].'</option>';
} ?></select>
<textarea name="fields_value_16" rows="5" cols="50" class="form-control"></textarea></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields14,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Key Subjective/Physical Examination Findings at the last visit:</label><div class="col-sm-8"><select onchange="tinyMCE.get('fields_value_17').setContent(this.value);" class="form-control chosen-select-deselect"><option></option>
<?php $sql = mysqli_query($dbc, "SELECT * FROM `field_config_treatment_presets` WHERE `form`='Treatment Plan' AND `field`='fields14'");
while($row = mysqli_fetch_array($sql)) {
	echo '<option value="'.$row['preset_text'].'">'.$row['preset_text'].'</option>';
} ?></select>
<textarea name="fields_value_17" rows="5" cols="50" class="form-control"></textarea></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields15,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Functional Goals:</label><div class="col-sm-8">
Goal 1:<br />
<input type="text" class="form-control" name="fields_value_18"><br />
Goal 2:<br />
<input type="text" class="form-control" name="fields_value_19"><br />
Goal 3:<br />
<input type="text" class="form-control" name="fields_value_20"><br />
</div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields16,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Progress towards goals:</label>
    <div class="col-sm-8"><input type="radio" name="fields_value_21" value="Regressed">&nbsp;&nbsp;Regressed
    <input type="radio" name="fields_value_21" value="Improved Minimally">&nbsp;&nbsp;Improved Minimally
    <input type="radio" name="fields_value_21" value="Improved Significantly">&nbsp;&nbsp;Improved Significantly
    <input type="radio" name="fields_value_21" value="Resolved">&nbsp;&nbsp;Resolved
    <input type="radio" name="fields_value_21" value="Plateaued">&nbsp;&nbsp;Plateaued
	<label><input type="radio" name="fields_value_21" value="Other">&nbsp;&nbsp;Other (please describe):
	<input type="text" name="fields_value_22" class="form-control"></label></div></div>
<?php } ?>
<h4>Part 4 - Treatment Summary</h4>
<?php if (strpos(','.$form_config.',', ',fields17,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Total number of Treatments:</label>
<div class="col-sm-8"><input type="text" name="fields_value_23" class="form-control"></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields18,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Date of First Visit:</label>
<div class="col-sm-8"><input type="text" name="fields_value_24" class="form-control"></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields19,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Date of Last Visit:</label>
<div class="col-sm-8"><input type="text" name="fields_value_25" class="form-control"></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields20,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Total number of Cancelled/Missed Visits:</label>
<div class="col-sm-8"><input type="text" name="fields_value_26" class="form-control"></div></div>
<?php } ?>

<h4>Part 5 - Reason for Discharge or need for ongoing Treatment</h4>
<?php if (strpos(','.$form_config.',', ',fields21,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Reason for Discharge:</label>
    <div class="col-sm-8"><input type="radio" name="fields_value_27" value="Full Recovery">&nbsp;&nbsp;Full Recovery
    <input type="radio" name="fields_value_27" value="Partial Recovery">&nbsp;&nbsp;Partial Recovery
    <input type="radio" name="fields_value_27" value="Plateaued">&nbsp;&nbsp;Plateaued
    <input type="radio" name="fields_value_27" value="No Progress">&nbsp;&nbsp;No Progress
    <input type="radio" name="fields_value_27" value="Transferred to another treatment site">&nbsp;&nbsp;Transferred to another treatment site
    <input type="radio" name="fields_value_27" value="Non-Attendance">&nbsp;&nbsp;Non-Attendance
    <input type="radio" name="fields_value_27" value="Poor Compliance">&nbsp;&nbsp;Poor Compliance
    <input type="radio" name="fields_value_27" value="No Contact">&nbsp;&nbsp;No Contact
	<label><input type="radio" name="fields_value_27" value="Other">&nbsp;&nbsp;Other (please describe):
	<input type="text" name="fields_value_28" class="form-control"></label></div></div>
<?php } ?>

<h4>Part 6 - Discharge Status</h4>
<?php if (strpos(','.$form_config.',', ',fields22,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Is the claimant now working?</label>
<div class="col-sm-8"><input type="radio" name="fields_value_29" value="Yes">&nbsp;&nbsp;Yes
<input type="radio" name="fields_value_29" value="No">&nbsp;&nbsp;No
<input type="radio" name="fields_value_29" value="Unknown">&nbsp;&nbsp;Unknown</div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields23,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Are they employed or engaged in training activities?</label>
<div class="col-sm-8"><input type="radio" name="fields_value_30" value="Full Time">&nbsp;&nbsp;Full Time
<input type="radio" name="fields_value_30" value="Part Time">&nbsp;&nbsp;Part Time
<input type="radio" name="fields_value_30" value="Seasonal">&nbsp;&nbsp;Seasonal
<input type="radio" name="fields_value_30" value="Self-Employed">&nbsp;&nbsp;Self-Employed
<input type="radio" name="fields_value_30" value="Retired">&nbsp;&nbsp;Retired
<input type="radio" name="fields_value_30" value="Student">&nbsp;&nbsp;Student
<input type="radio" name="fields_value_30" value="Not Employed">&nbsp;&nbsp;Not Employed
</div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields24,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Work or Training Restrictions?</label>
<div class="col-sm-8"><input type="radio" name="fields_value_31" value="None">&nbsp;&nbsp;None
<input type="radio" name="fields_value_31" value="Temporary Restriction">&nbsp;&nbsp;Temporary Restriction
<input type="radio" name="fields_value_31" value="Permanent Restriction">&nbsp;&nbsp;Permanent Restriction
</div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields25,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Is the claimant returned to a pre-accident level of activity outside work?</label>
<div class="col-sm-8"><input type="radio" name="fields_value_32" value="Yes">&nbsp;&nbsp;Yes
<input type="radio" name="fields_value_32" value="No">&nbsp;&nbsp;No</div></div>
<?php } ?>
<div class="form-group"><label class="control-label col-sm-4">Did you refer the claimant to any other health care provider(s)?</label>
<div class="col-sm-8"><input type="radio" name="fields_value_33" value="No">&nbsp;&nbsp;No
<input type="radio" name="fields_value_33" value="Yes">&nbsp;&nbsp;Yes: Who?<br />
<input type="text" name="fields_value_34" class="form-control"></div></div>
<?php if (strpos(','.$form_config.',', ',fields26,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Discharge comments (residual symptoms, signs, prognosis, details of exercise program, etc.):</label><div class="col-sm-8"><select onchange="tinyMCE.get('fields_value_35').setContent(this.value);" class="form-control chosen-select-deselect"><option></option>
<?php $sql = mysqli_query($dbc, "SELECT * FROM `field_config_treatment_presets` WHERE `form`='Treatment Plan' AND `field`='fields26'");
while($row = mysqli_fetch_array($sql)) {
	echo '<option value="'.$row['preset_text'].'">'.$row['preset_text'].'</option>';
} ?></select>
<textarea name="fields_value_35" rows="5" cols="50" class="form-control"></textarea></div></div>
<?php } ?>

<h4>Part 7 - Signature of Primary Health Care Practitioner</h4>
<?php if (strpos(','.$form_config.',', ',fields27,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Name (Please Print)</label>
    <div class="col-sm-8"><select data-placeholder="Select a Therapist..." name="fields_value_36" class="chosen-select-deselect form-control" width="380">
		<option value=""></option>
		  <?php
			$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND (category_contact = 'Physical Therapist' OR category_contact = 'Massage Therapist' OR category_contact = 'Osteopathic Therapist') AND deleted=0 AND `status`>0"),MYSQLI_ASSOC));
			foreach($query as $id) {
				$selected = '';
				//$selected = $id == $contactid ? 'selected = "selected"' : '';
				echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
			}
		  ?>
    </select></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields28,') !== FALSE) { ?>
<?php include ('../phpsign/sign.php'); ?>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields29,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Date:</label><div class="col-sm-8"><input name="fields_value_37" type="text" class="datepicker form-control"></div></div>
<?php } ?>
