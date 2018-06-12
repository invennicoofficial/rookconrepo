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
	$form_config = ',fields1,fields2,fields3,fields4,fields5,fields6,fields7,fields8,fields9,fields10,fields11,fields12,fields13,fields14,fields15,fields16,fields17,fields18,fields19,fields20,fields21,fields22,fields23,';
} ?>

<?php if (strpos(','.$form_config.',', ',fields1,') !== FALSE) { ?>
<h4>Send this form to the appropriate insurer: </h4>
<div class="form-group"><label class="control-label col-sm-4">Fax #:</label><div class="col-sm-8"><input name="fields_value_1" type="text" class="form-control"></div></div>
<?php } ?>

<h4>To be completed by Insurer</h4>
<?php if (strpos(','.$form_config.',', ',fields2,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Claim #:</label><div class="col-sm-8"><input name="fields_value_2" type="text" class="form-control"></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields3,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Insurance Company:</label>
<div class="col-sm-8"><select name="fields_value_3" id="payer_name" class="chosen-select-deselect form-control" width="380">
<option value=""></option>
    <?php
    $query = mysqli_query($dbc,"SELECT contactid, name FROM contacts WHERE category='Insurer' AND deleted=0 ORDER BY name");
    while($row = mysqli_fetch_array($query)) {
        echo "<option value='". $row['contactid']."'>".decryptIt($row['name']). '</option>';
    }
    ?>
</select></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields4,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Claim Representative:</label><div class="col-sm-8"><input name="fields_value_4" type="text" class="form-control"></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields5,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Policy Number:</label><div class="col-sm-8"><input name="fields_value_5" type="text" class="form-control"></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields6,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Date of Accident:</label><div class="col-sm-8"><input name="fields_value_6" type="text" class="datepicker form-control"></div></div>
<?php } ?>

<h4>Part 1 - Claimant Information</h4>
<?php if (strpos(','.$form_config.',', ',fields7,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Patient:</label>
    <div class="col-sm-8"><select data-placeholder="Select a Patient..." name="fields_value_7" class="chosen-select-deselect form-control" width="380">
        <option value=""></option>
        <?php
        $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name, birth_date FROM contacts WHERE category='Patient' AND status>0 AND deleted=0");
        while($row = mysqli_fetch_array($query)) {
            echo "<option value='". $row['contactid']."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';
        }
        ?>
    </select></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields8,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Best Contact Method:</label>
    <div class="col-sm-8"><select data-placeholder="Select a Method..." name="fields_value_8" class="chosen-select-deselect form-control" width="380"
		onchange="if(this.value=='Other') { $('#other_contact').show(); } else { $('#other_contact').hide(); }">
        <option value=""></option>
        <option value="Telephone">By telephone</option>
        <option value="Personal">By personal visit</option>
        <option value="Home">At home</option>
        <option value="Work">At work</option>
        <option value="Other">Other</option>
    </select>
	<div id="other_contact" style="display:none;"><input type="text" class="form-control" name="fields_value_9"></div></div></div>
<div class="form-group"><label class="control-label col-sm-4">Best Time of Contact:</label>
    <div class="col-sm-8"><input type="text" name="fields_value_10" class="form-control"></div>
	<label class="control-label col-sm-4">Best Day(s) of the week:</label>
    <div class="col-sm-8"><input type="text" name="fields_value_11" class="form-control"></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields9,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Insurance Company:</label>
    <div class="col-sm-8"><select data-placeholder="Select an Insurance Company..." name="fields_value_12" class="chosen-select-deselect form-control" width="380">
		<option value=""></option>
		<?php $query = mysqli_query($dbc,"SELECT contactid, name FROM contacts WHERE category='Insurer' AND deleted=0 ORDER BY name");
		while($row = mysqli_fetch_array($query)) {
			echo "<option value='". $row['contactid']."'>".decryptIt($row['name']). '</option>';
		} ?>
    </select></div></div>
<div class="form-group"><label class="control-label col-sm-4">Policy Number:</label>
    <div class="col-sm-8"><input type="text" name="fields_value_13" class="form-control"></div></div>
<div class="form-group"><label class="control-label col-sm-4">Will this be an Alberta Workers’ Compensation Board Claim?</label>
    <div class="col-sm-8"><input type="radio" name="fields_value_14" value="Yes">&nbsp;&nbsp;Yes
    <input type="radio" name="fields_value_14" value="No">&nbsp;&nbsp;No</div></div>
<div class="form-group"><label class="control-label col-sm-4">Are Extended Health Care Benefits Available? (e.g., Blue Cross or similar Employee benefits plans)</label>
    <div class="col-sm-8"><input type="radio" name="fields_value_15" value="Yes">&nbsp;&nbsp;Yes
	<input type="radio" name="fields_value_15" value="No">&nbsp;&nbsp;No
	<br />Details:<br />
	<input type="text" name="fields_value_16" class="form-control"></label></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields10,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Are you currently employed or engaged in training activities?</label>
	<div class="col-sm-8"><input name="fields_value_17" type="radio" value="Full"> Full Time 
	<input name="fields_value_17" type="radio" value="Part"> Part Time 
	<input name="fields_value_17" type="radio" value="Self"> Self-employed 
	<input name="fields_value_17" type="radio" value="Retired"> Retired 
	<input name="fields_value_17" type="radio" value="Student"> Student 
	<input name="fields_value_17" type="radio" value="Unemployed"> Not Employed</div></div>
<?php } ?>

<h4>Part 2 - Claimant's Authorized Representative</h4>
<?php if (strpos(','.$form_config.',', ',fields11,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Last Name:</label><div class="col-sm-8"><input name="fields_value_18" type="text" class="form-control"></div></div>
<div class="form-group"><label class="control-label col-sm-4">First Name:</label><div class="col-sm-8"><input name="fields_value_19" type="text" class="form-control"></div></div>
<div class="form-group"><label class="control-label col-sm-4">Middle Name(s):</label><div class="col-sm-8"><input name="fields_value_20" type="text" class="form-control"></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields12,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Address:</label><div class="col-sm-8"><input name="fields_value_21" type="text" class="form-control"></div></div>
<div class="form-group"><label class="control-label col-sm-4">City, Town or County:</label><div class="col-sm-8"><input name="fields_value_22" type="text" class="form-control"></div></div>
<div class="form-group"><label class="control-label col-sm-4">Province:</label><div class="col-sm-8"><input name="fields_value_23" type="text" class="form-control"></div></div>
<div class="form-group"><label class="control-label col-sm-4">Postal Code:</label><div class="col-sm-8"><input name="fields_value_24" type="text" class="form-control"></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields13,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Relationship with Claimant</label>
    <div class="col-sm-8"><input type="radio" name="fields_value_25" value="Parent">&nbsp;&nbsp;Parent
    <input type="radio" name="fields_value_25" value="Guardian">&nbsp;&nbsp;Guardian
    <input type="radio" name="fields_value_25" value="Other">&nbsp;&nbsp;Other:<br />
	<input type="text" name="fields_value_55" class="form-control"></div></div>
<?php } ?>
<div class="form-group"><label class="control-label col-sm-4">Relevant Documentation Attached? If no, please authorize your representative by completing Part 5 of this form.</label>
    <div class="col-sm-8"><input type="radio" name="fields_value_26" value="Yes">&nbsp;&nbsp;Yes
	<input type="radio" name="fields_value_26" value="No">&nbsp;&nbsp;No
	<input type="radio" name="fields_value_26" value="NA">&nbsp;&nbsp;Not Applicable</div></div>
<?php if (strpos(','.$form_config.',', ',fields14,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Telephone Number (Home) (Include area code) :</label><div class="col-sm-8"><input name="fields_value_27" type="text" class="form-control"></div></div>
<div class="form-group"><label class="control-label col-sm-4">Telephone Number (Work) (Include area code) :</label><div class="col-sm-8"><input name="fields_value_28" type="text" class="form-control"></div></div>
<div class="form-group"><label class="control-label col-sm-4">Fax Number (Include area code):</label><div class="col-sm-8"><input name="fields_value_29" type="text" class="form-control"></div></div>
<?php } ?>

<h4>Part 3 - Claimant's Accident Details</h4>
<?php if (strpos(','.$form_config.',', ',fields15,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">You were a:</label>
    <div class="col-sm-8"><input type="radio" name="fields_value_30" value="Driver">&nbsp;&nbsp;Driver
	<input type="radio" name="fields_value_30" value="Passenger">&nbsp;&nbsp;Passenger
	<input type="radio" name="fields_value_30" value="Pedestrian">&nbsp;&nbsp;Pedestrian
	<input type="radio" name="fields_value_30" value="Other">&nbsp;&nbsp;Other:<br />
	<input type="text" name="fields_value_31" class="form-control"></label></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields16,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Location of Accident:</label><div class="col-sm-8"><input name="fields_value_32" type="text" class="form-control"></div></div>
<div class="form-group"><label class="control-label col-sm-4">City, Town or County:</label><div class="col-sm-8"><input name="fields_value_33" type="text" class="form-control"></div></div>
<div class="form-group"><label class="control-label col-sm-4">Province:</label><div class="col-sm-8"><input name="fields_value_34" type="text" class="form-control"></div></div>
<div class="form-group"><label class="control-label col-sm-4">Time of Accident:</label><div class="col-sm-8"><input name="fields_value_35" type="text" class="form-control datetimepicker"></div></div>
<div class="form-group"><label class="control-label col-sm-4">Date of Accident:</label><div class="col-sm-8"><input name="fields_value_36" type="text" class="form-control datepicker"></div></div>
<div class="form-group"><label class="control-label col-sm-4">Accident was Reported to the Police?</label>
    <div class="col-sm-8"><input type="radio" name="fields_value_37" value="Yes">&nbsp;&nbsp;Yes
	<input type="radio" name="fields_value_37" value="No">&nbsp;&nbsp;No</div></div>
<div class="form-group"><label class="control-label col-sm-4">Date Reported:</label><div class="col-sm-8"><input name="fields_value_38" type="text" class="form-control datepicker"></div></div>
<div class="form-group"><label class="control-label col-sm-4">Please provide a brief description of how the accident occurred and how you were injured:</label><div class="col-sm-8">
	<textarea name="fields_value_39" rows="5" cols="50" class="form-control"></textarea></div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields17,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Have you seen a Medical Doctor, Physical Therapist, Chiropractor, Dentist or other health service provider for diagnosis, treatment and care for an injury related to this accident?</label>
    <div class="col-sm-8"><input type="radio" name="fields_value_40" value="Yes">&nbsp;&nbsp;Yes
    <input type="radio" name="fields_value_40" value="No">&nbsp;&nbsp;No
    <input type="radio" name="fields_value_40" value="Booked">&nbsp;&nbsp;Appointment booked for:<br />
	<input type="text" name="fields_value_41" class="form-control"></div></div>
<div class="form-group"><label class="control-label col-sm-4">Have you started treatment?</label>
    <div class="col-sm-8"><input type="radio" name="fields_value_42" value="Yes">&nbsp;&nbsp;Yes
    <input type="radio" name="fields_value_42" value="No">&nbsp;&nbsp;No
    <input type="radio" name="fields_value_42" value="Booked">&nbsp;&nbsp;Appointment booked for:<br />
	<input type="text" name="fields_value_43" class="form-control"></div></div>
<div class="form-group"><label class="control-label col-sm-4">Are you currently receiving medical or rehabilitation benefits related to another motor vehicle accident?</label>
    <div class="col-sm-8"><input type="radio" name="fields_value_44" value="Yes">&nbsp;&nbsp;Yes
	<input type="radio" name="fields_value_44" value="No">&nbsp;&nbsp;No</div></div>
<?php } ?>
<?php if (strpos(','.$form_config.',', ',fields18,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Please provide a brief description of your injuries and the symptoms that you are currently experiencing:</label><div class="col-sm-8">
	<textarea name="fields_value_45" rows="5" cols="50" class="form-control"></textarea></div></div>
<?php } ?>

<h4>Part 4 - Information of Health Provider Providing Ongoing Treatment and Care</h4>
<?php if (strpos(','.$form_config.',', ',fields19,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Name</label>
    <div class="col-sm-8"><select data-placeholder="Select a Therapist..." name="fields_value_46" class="chosen-select-deselect form-control" width="380">
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

<h4>Part 5 - Authority to Act on Claimants Behalf</h4>
<div class="form-group"><label class="control-label col-sm-4">Authority:</label>
<div class="col-sm-8">I, </div></div>
<?php if (strpos(','.$form_config.',', ',fields20,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Claimant's Name:</label>
    <div class="col-sm-8"><select data-placeholder="Select a Patient..." name="fields_value_47" class="chosen-select-deselect form-control" width="380">
        <option value=""></option>
        <?php
        $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name, birth_date FROM contacts WHERE category='Patient' AND status>0 AND deleted=0");
        while($row = mysqli_fetch_array($query)) {
            echo "<option value='". $row['contactid']."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';
        }
        ?>
    </select></div></div>
<?php } ?>
<div class="form-group"><label class="control-label col-sm-4"></label>
<div class="col-sm-8">hereby authorize </div></div>
<?php if (strpos(','.$form_config.',', ',fields21,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Name of Authorized Representative:</label><div class="col-sm-8"><input name="fields_value_48" type="text" class="form-control"></div></div>
<?php } ?>
<div class="form-group"><label class="control-label col-sm-4"></label>
<div class="col-sm-8"><p>to act as my representative concerning the treatment and care of my injury, the submission and ongoing handling of my claim for accident and/or disability income benefits and the collection, use and disclosure of information concerning my injury, diagnosis, assessment, treatment or care resulting from the automobile accident referred to in Section 1 of this form.</p>
<p>I authorize my primary health care practitioner(s), dentist(s), other health service provider(s) and my insurance company,</p></div></div>
<?php if (strpos(','.$form_config.',', ',fields22,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Insurance Company:</label>
    <div class="col-sm-8"><select data-placeholder="Select an Insurance Company..." name="fields_value_49" class="chosen-select-deselect form-control" width="380">
		<option value=""></option>
		<?php $query = mysqli_query($dbc,"SELECT contactid, name FROM contacts WHERE category='Insurer' AND deleted=0 ORDER BY name");
		while($row = mysqli_fetch_array($query)) {
			echo "<option value='". $row['contactid']."'>".decryptIt($row['name']). '</option>';
		} ?>
    </select></div></div>
<?php } ?>
<div class="form-group"><label class="control-label col-sm-4"></label>
<div class="col-sm-8">and their agents, to collect relevant information concerning me and my accident from my representative as required. I further authorize primary health care practitioner(s), dentist(s), other health service provider(s) and my insurance company to disclose relevant information concerning my injury, diagnosis, assessment, treatment and care and my claim for accident and/or disability income benefits to my representative.</div></div>
<div class="form-group"><label class="control-label col-sm-4">Claimant's Signature:</label><div class="col-sm-8">
<?php $output_name = "sign_claimant";
include('../phpsign/sign_multiple.php'); ?></div></div>
<div class="form-group"><label class="control-label col-sm-4">Claimant Signature Date:</label><div class="col-sm-8"><input name="fields_value_50" type="text" class="form-control datepicker"></div></div>
<div class="form-group"><label class="control-label col-sm-4">Representative's Signature:</label><div class="col-sm-8">
<?php $output_name = "sign_rep";
include('../phpsign/sign_multiple.php'); ?></div></div>
<div class="form-group"><label class="control-label col-sm-4">Representative Signature Date:</label><div class="col-sm-8"><input name="fields_value_51" type="text" class="form-control datepicker"></div></div>

<h4>Part 6 - Certification and Consent to Share Information</h4>
<div class="form-group"><label class="control-label col-sm-4">Consent:</label>
<div class="col-sm-8">I certify that the information provided is true and correct to the best of my knowledge.
I authorize all assessing or treating Primary Health Care Practitioners, dentist(s) or other health service provider(s) to collect, use and disclose any relevant information concerning my injury, including diagnosis, assessment, treatment or care resulting from the automobile accident referred to in Section 1 herein, for the purpose of providing ongoing treatment and care.
I further authorize all assessing or treating Primary Health Care Practitioners, dentist(s) or other health service providers to disclose my personal information to my insurance company, </div></div>
<?php if (strpos(','.$form_config.',', ',fields23,') !== FALSE) { ?>
<div class="form-group"><label class="control-label col-sm-4">Insurance Company:</label>
    <div class="col-sm-8"><select data-placeholder="Select an Insurance Company..." name="fields_value_52" class="chosen-select-deselect form-control" width="380">
		<option value=""></option>
		<?php $query = mysqli_query($dbc,"SELECT contactid, name FROM contacts WHERE category='Insurer' AND deleted=0 ORDER BY name");
		while($row = mysqli_fetch_array($query)) {
			echo "<option value='". $row['contactid']."'>".decryptIt($row['name']). '</option>';
		} ?>
		<option value=""></option>
		  <?php
			$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Insurer' AND deleted=0 AND `status`>0"),MYSQLI_ASSOC));
			foreach($query as $id) {
				$selected = '';
				//$selected = $id == $contactid ? 'selected = "selected"' : '';
				echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
			}
		  ?>
    </select></div></div>
<?php } ?>
<label class="control-label col-sm-4"></label>
<div class="col-sm-8">and their agents that is relevant for the purpose of determining my eligibility for accident and disability benefits as outlined on Form AB-1 and for the purpose of administering my claim.
I further authorize my insurance company and its agents to collect, use and disclose relevant information concerning my injury, diagnosis, assessment, treatment or care received as a result of the automobile accident referred to in Section 1 herein, including a treatment plan and services provided, for the purpose of determining my eligibility for accident and disability benefits as outlined on Form AB-1 and administering my claim.</div></div>
<div class="form-group"><label class="control-label col-sm-4"></label>
<script>
$(document).ready(function() {
	$('form').submit(function() {
		if($('[name=fields_value_54]:checked').val() == 'no_consent') {
			alert('Please consent to the conditions.');
			return false;
		}
	});
});
</script>
    <div class="col-sm-8"><input type="radio" name="fields_value_54" value="claimant">&nbsp;&nbsp;I am the claimant
	<input type="radio" name="fields_value_54" value="representative">&nbsp;&nbsp;I am the authorized representative of the claimant
	<input type="radio" name="fields_value_54" checked value="no_consent" style="display:none;"></div></div>
<div class="form-group"><label class="control-label col-sm-4">Signature:</label><div class="col-sm-8">
<?php $output_name = "sign_final";
include('../phpsign/sign_multiple.php'); ?></div></div>
<div class="form-group"><label class="control-label col-sm-4">Signature Date:</label><div class="col-sm-8"><input name="fields_value_53" type="text" class="form-control datepicker"></div></div>