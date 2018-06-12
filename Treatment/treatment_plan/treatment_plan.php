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
	$form_config = ',fields1,fields2,fields3,fields4,fields5,fields6,fields7,fields8,fields9,fields10,fields11,fields12,fields13,fields14,fields15,fields16,fields17,fields18,fields19,fields20,fields21,fields22,fields23,fields24,fields25,fields26,fields27,fields28,fields29,fields30,fields31,fields32,fields33,fields34,fields35,fields36,fields37,fields38,';
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
    <div class="col-sm-8"><select data-placeholder="Choose a Patient..." name="fields_value_5" class="chosen-select-deselect form-control" width="380">
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
<div class="form-group"><label class="control-label col-sm-4">Date of Accident:</label><div class="col-sm-8"><input name="fields_value_6" type="text" class="datepicker form-control"></div></div>
<?php } ?>
<h4>Part 2 - Claimant's Authorized Representative</h4>
<?php if (strpos(','.$form_config.',', ',fields7,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Therapist:</label>
    <div class="col-sm-8"><select data-placeholder="Choose a Therapist..." name="fields_value_7" class="chosen-select-deselect form-control" width="380">
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
<div class="form-group"><label class="control-label col-sm-4">Relationship with Claimant</label>
    <div class="col-sm-8"><input type="radio" name="fields_value_12" value="Parent">&nbsp;&nbsp;Parent
    <input type="radio" name="fields_value_12" value="Guardian">&nbsp;&nbsp;Guardian
    <input type="radio" name="fields_value_12" value="Other">&nbsp;&nbsp;Other</div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields10,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Telephone Number (Include area code) :</label><div class="col-sm-8"><input name="fields_value_13" type="text" class="form-control"></div></div>
<div class="form-group"><label class="control-label col-sm-4">Telephone Number (Include area code) :</label><div class="col-sm-8"><input name="fields_value_14" type="text" class="form-control"></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields11,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Fax Number (Include area code):</label><div class="col-sm-8"><input name="fields_value_15" type="text" class="form-control"></div></div>
<?php } ?>
<h4>Part 3 - Therapy Status Report (To be completed by Primary Health Care Practitioner)</h4>
<?php if (strpos(','.$form_config.',', ',fields12,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Diagnosis WAD :</label><div class="col-sm-8"><select onchange="tinyMCE.get('fields_value_16').setContent(this.value);" class="form-control chosen-select-deselect"><option></option>
<?php $sql = mysqli_query($dbc, "SELECT * FROM `field_config_treatment_presets` WHERE `form`='AB-2 Treatment Plan' AND `field`='fields12'");
while($row = mysqli_fetch_array($sql)) {
	echo '<option value="'.$row['preset_text'].'">'.$row['preset_text'].'</option>';
} ?></select>
<textarea name="fields_value_16" rows="5" cols="50" class="form-control"></textarea></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields13,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Key Subjective/Physical Examination Findings :</label><div class="col-sm-8"><select onchange="tinyMCE.get('fields_value_17').setContent(this.value);" class="form-control chosen-select-deselect"><option></option>
<?php $sql = mysqli_query($dbc, "SELECT * FROM `field_config_treatment_presets` WHERE `form`='AB-2 Treatment Plan' AND `field`='fields13'");
while($row = mysqli_fetch_array($sql)) {
	echo '<option value="'.$row['preset_text'].'">'.$row['preset_text'].'</option>';
} ?></select>
<textarea name="fields_value_17" rows="5" cols="50" class="form-control"></textarea></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields14,') !== FALSE) { ?>
<?php } ?>
<div class="form-group"><label class="control-label col-sm-4">C/O  :</label><div class="col-sm-8"><select onchange="tinyMCE.get('fields_value_18').setContent(this.value);" class="form-control chosen-select-deselect"><option></option>
<?php $sql = mysqli_query($dbc, "SELECT * FROM `field_config_treatment_presets` WHERE `form`='AB-2 Treatment Plan' AND `field`='fields14'");
while($row = mysqli_fetch_array($sql)) {
	echo '<option value="'.$row['preset_text'].'">'.$row['preset_text'].'</option>';
} ?></select>
<textarea name="fields_value_18" rows="5" cols="50" class="form-control"></textarea></div></div>
<?php if (strpos(','.$form_config.',', ',fields15,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">O/E :</label><div class="col-sm-8"><select onchange="tinyMCE.get('fields_value_19').setContent(this.value);" class="form-control chosen-select-deselect"><option></option>
<?php $sql = mysqli_query($dbc, "SELECT * FROM `field_config_treatment_presets` WHERE `form`='AB-2 Treatment Plan' AND `field`='fields15'");
while($row = mysqli_fetch_array($sql)) {
	echo '<option value="'.$row['preset_text'].'">'.$row['preset_text'].'</option>';
} ?></select>
<textarea name="fields_value_19" rows="5" cols="50" class="form-control"></textarea></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields16,') !== FALSE) { ?>
<h5>Diagnosis</h5>
<div class="form-group"><label class="control-label col-sm-4">Sprain</label>
    <div class="col-sm-8"><input type="radio" name="fields_value_20" value="1">&nbsp;&nbsp;1
    <input type="radio" name="fields_value_20" value="2">&nbsp;&nbsp;2
    <input type="radio" name="fields_value_20" value="3">&nbsp;&nbsp;3</div></div>
<div class="form-group"><label class="control-label col-sm-4">Strain</label>
    <div class="col-sm-8"><input type="radio" name="fields_value_21" value="1">&nbsp;&nbsp;1
    <input type="radio" name="fields_value_21" value="2">&nbsp;&nbsp;2
    <input type="radio" name="fields_value_21" value="3">&nbsp;&nbsp;3</div></div>
<div class="form-group"><label class="control-label col-sm-4">WAD</label>
    <div class="col-sm-8"><input type="radio" name="fields_value_22" value="1">&nbsp;&nbsp;1
    <input type="radio" name="fields_value_22" value="2">&nbsp;&nbsp;2
    <input type="radio" name="fields_value_22" value="3">&nbsp;&nbsp;3</div></div>
<div class="form-group"><label class="control-label col-sm-4">Other:</label><div class="col-sm-8"><input name="fields_value_23" type="text" class="form-control"></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields17,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">ICD-10-CA Injury Code* :<br><em>*ICD-10-CA injury codes are only required for Sprains, Strains and WAD injuries.  It is recommended, not required, that ICD-10-CA injury codes be used for other injuries when practical. </em></label>
<div class="col-sm-8"><textarea name="fields_value_24" rows="5" cols="50" class="form-control"></textarea></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields18,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Patient Specific functional Scale:</label><div class="col-sm-4"><input name="fields_value_25" type="text" class="form-control"></div> / 30</div>
<div class="form-group"><label class="control-label col-sm-4">Neck Disability Index:</label><div class="col-sm-4"><input name="fields_value_26" type="text" class="form-control"></div> / 50</div>
<div class="form-group"><label class="control-label col-sm-4">Roland Morris(back pain):</label><div class="col-sm-4"><input name="fields_value_27" type="text" class="form-control"></div> / 24</div>
<div class="form-group"><label class="control-label col-sm-4">Static Endurance Test:</label><div class="col-sm-8"><input name="fields_value_28" type="text" class="form-control"></div></div>
<div class="form-group"><label class="control-label col-sm-4">Chin Tuck Head Lift:</label><div class="col-sm-8"><input name="fields_value_29" type="text" class="form-control"></div></div>
<div class="form-group"><label class="control-label col-sm-4">Prone Plank:</label><div class="col-sm-8"><input name="fields_value_30" type="text" class="form-control"></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields19,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Is the claimant employed or engaged in training activities?</label>
    <div class="col-sm-8"><input type="radio" name="fields_value_31" value="Full Time">&nbsp;&nbsp;Full Time
    <input type="radio" name="fields_value_31" value="Part Time">&nbsp;&nbsp;Part Time
    <input type="radio" name="fields_value_31" value="Seasonal">&nbsp;&nbsp;Seasonal
    <input type="radio" name="fields_value_31" value="Self-employed">&nbsp;&nbsp;Self-employed
    <input type="radio" name="fields_value_31" value="Retired">&nbsp;&nbsp;Retired
    <input type="radio" name="fields_value_31" value="Student">&nbsp;&nbsp;Student
    <input type="radio" name="fields_value_31" value="Not employed">&nbsp;&nbsp;Not employed</div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields20,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Functional Goals (outcomes to be measured):</label>
<div class="col-sm-8"><input type="checkbox" name="fields_value_32" value="Restore ROM - self stretches & Manual Therapy">&nbsp;&nbsp;Restore ROM - self stretches & Manual Therapy
<input type="checkbox" name="fields_value_33" value="Restore Strength - spinal exercises">&nbsp;&nbsp;Restore Strength - spinal exercises
<input type="checkbox" name="fields_value_34" value="Restore Endurance, Function for Work and Play">&nbsp;&nbsp;Restore Endurance, Function for Work and Play</div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields21,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Comments  :</label><div class="col-sm-8"><textarea name="fields_value_35" rows="5" cols="50" class="form-control"></textarea></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields22,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Expected Number of Visits:</label><div class="col-sm-8"><input name="fields_value_36" type="text" class="form-control"></div></div>
<div class="form-group"><label class="control-label col-sm-4">Date of expected treatment discharge:</label><div class="col-sm-8"><input name="fields_value_37" type="text" class="datepicker form-control"></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields23,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Do you expect these visits to be sufficient to meet functional goals:</label>
    <div class="col-sm-8"><input type="radio" name="fields_value_38" value="Yes">&nbsp;&nbsp;Yes
    <input type="radio" name="fields_value_38" value="No">&nbsp;&nbsp;No</div></div>
<div class="form-group"><label class="control-label col-sm-4">If No, please provide details of expected further assessment and treatment:</label><div class="col-sm-8"><input name="fields_value_39" type="text" class="form-control"></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields24,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Do you expect to reassess within three weeks due to alerting factors?:</label>
    <div class="col-sm-8"><input type="radio" name="fields_value_40" value="Yes">&nbsp;&nbsp;Yes
    <input type="radio" name="fields_value_40" value="No">&nbsp;&nbsp;No</div></div>
<div class="form-group"><label class="control-label col-sm-4">If Yes, please describe:</label><div class="col-sm-8"><input name="fields_value_41" type="text" class="form-control"></div></div>
<?php } ?>
<h4>Part 4 - Treatment (To be completed with reference to the Diagnostic and Treatment Protocols Regulation)</h4>
<?php if (strpos(','.$form_config.',', ',fields25,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Treatment Provided:</label>
<div class="col-sm-8"><input type="checkbox" name="fields_value_42" value="Manual Therapy">&nbsp;&nbsp;Manual Therapy
<input type="checkbox" name="fields_value_43" value="Exercise Prescription">&nbsp;&nbsp;Exercise Prescription
<input type="checkbox" name="fields_value_44" value="Local Modalities">&nbsp;&nbsp;Local Modalities
<input type="checkbox" name="fields_value_45" value="Education">&nbsp;&nbsp;Education
<input type="checkbox" name="fields_value_46" value="Acupuncture IMS">&nbsp;&nbsp;Acupuncture IMS
<input type="checkbox" name="fields_value_47" value="Massage Therapy">&nbsp;&nbsp;Massage Therapy</div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields26,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Do you expect the claimant to return to normal and essential activities?:</label>
    <div class="col-sm-8"><input type="radio" name="fields_value_48" value="Yes">&nbsp;&nbsp;Yes
    <input type="radio" name="fields_value_48" value="No">&nbsp;&nbsp;No
    <input type="radio" name="fields_value_48" value="Unable to determine">&nbsp;&nbsp;Unable to determine</div></div>
<div class="form-group"><label class="control-label col-sm-4">If Yes, date expected?:</label><div class="col-sm-8"><input name="fields_value_49" type="text" class="datepicker form-control"></div></div>
<?php } ?>

<h4>Part 5 - Primary Health Care Practitioner Information</h4>
<?php if (strpos(','.$form_config.',', ',fields27,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Name of Primary Health Care Practitioner:</label>
<div class="col-sm-8"><select data-placeholder="Choose a Therapist..." name="fields_value_50" class="chosen-select-deselect form-control" width="380">
    <option value=""></option>
    <?php
    $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND (category_contact = 'Physical Therapist' OR category_contact = 'Massage Therapist' OR category_contact = 'Osteopathic Therapist')  AND deleted=0");
    while($row = mysqli_fetch_array($query)) {
        echo "<option value='". $row['contactid']."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';
    }
    ?>
</select></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields28,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Profession:</label>
<div class="col-sm-8"><input type="checkbox" name="fields_value_51" value="Medical Doctor">&nbsp;&nbsp;Medical Doctor
<input type="checkbox" name="fields_value_52" value="Chiropractor">&nbsp;&nbsp;Chiropractor
<input type="checkbox" name="fields_value_53" value="Physical Therapist">&nbsp;&nbsp;Physical Therapist</div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields29,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Address:</label><div class="col-sm-8"><input name="fields_value_54" type="text" class="form-control"></div></div>
<div class="form-group"><label class="control-label col-sm-4">City, Town or County:</label><div class="col-sm-8"><input name="fields_value_55" type="text" class="form-control"></div></div>
<div class="form-group"><label class="control-label col-sm-4">Province:</label><div class="col-sm-8"><input name="fields_value_56" type="text" class="form-control"></div></div>
<div class="form-group"><label class="control-label col-sm-4">Postal Code:</label><div class="col-sm-8"><input name="fields_value_57" type="text" class="form-control"></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields30,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Telephone Number (Include area code) :</label><div class="col-sm-8"><input name="fields_value_58" type="text" class="form-control"></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields31,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Fax Number (Include area code):</label><div class="col-sm-8"><input name="fields_value_59" type="text" class="form-control"></div></div>
<?php } ?>

<h4>Part 6 - Signature of Primary Health Care Practitioner</h4>
<?php if (strpos(','.$form_config.',', ',fields32,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Name (Please Print)</label>
    <div class="col-sm-8"><select data-placeholder="Choose a Therapist..." name="fields_value_60" class="chosen-select-deselect form-control" width="380">
        <option value=""></option>
        <?php
        $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND (category_contact = 'Physical Therapist' OR category_contact = 'Massage Therapist' OR category_contact = 'Osteopathic Therapist')  AND deleted=0");
        while($row = mysqli_fetch_array($query)) {
            echo "<option value='". $row['contactid']."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';
        }
        ?>
    </select></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields33,') !== FALSE) { ?>
<?php include ('../phpsign/sign.php'); ?>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields34,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Date:</label><div class="col-sm-8"><input name="fields_value_61" type="text" class="datepicker form-control"></div></div>
<?php } ?>

<h4>Part 7 - Choice in Following Diagnostic and Treatment Protocols</h4>
<?php if (strpos(','.$form_config.',', ',fields35,') !== FALSE) { ?>
Please state your preference of treatment within or not within the Diagnostic and Treatment Protocols:</div><br><br>
<label class="col-sm-12" style="text-align:right;"><input type="checkbox" value="1">&nbsp;&nbsp;<div class="col-sm-8 pull-right" style="text-align:left;">I choose to be treated within the Diagnostic and Treatment Protocols as indicated on Form AB-1</div></label><br><br>
<label class="col-sm-12" style="text-align:right;"><input type="checkbox" value="1">&nbsp;&nbsp;<div class="col-sm-8 pull-right" style="text-align:left;">I choose not to be treated within the Diagnostic and Treatment Protocols</div></label><br><br>
<label class="col-sm-12" style="text-align:right;"><input type="checkbox" value="1">&nbsp;&nbsp;<div class="col-sm-8 pull-right" style="text-align:left;">I am the claimant</div></label><br><br>
<label class="col-sm-12" style="text-align:right;"><input type="checkbox" value="1">&nbsp;&nbsp;<div class="col-sm-8 pull-right" style="text-align:left;">I am the authorized representative of the claimant</div></label><br><br>
<?php } ?>
I certify that the information provided is true and correct to the best of my knowledge. I confirm that I have consented to the collection, use and disclosure of my personal information for my treatment and care and determination of my eligibility for accident and/or disability income benefits as outline on Form AB-1<br><br>
<?php if (strpos(','.$form_config.',', ',fields36,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4" style="text-align:right;">Name (Please Print)</label>
    <div class="col-sm-8"><select data-placeholder="Choose a Therapist..." name="fields_value_62" class="chosen-select-deselect form-control" width="380">
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
<?php if (strpos(','.$form_config.',', ',fields37,') !== FALSE) { ?>
<?php include ('../phpsign/sign2.php'); ?>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields38,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4" style="text-align:right;">Date:</label><div class="col-sm-8"><input name="fields_value_63" type="text" class="datepicker form-control"></div></div>
<?php } ?>
