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

<?php if (strpos(','.$form_config.',', ',fields4,') !== FALSE) { ?>
<br><br>We are committed to protecting the privacy of our patients' personal information and to utilizing all personal information in a responsible and professional manner. This
document summarizes some of the personal information that we collect. Use of personal information when permitted or required by law.
<br><br>
Contact and or medical information is disclosed to the Calgary Health Region, Motor Vehicle Insurance Companies, Workers Compensation Board, on behalf of the patient to discuss treatment. I hereby authorize <?php echo get_config($dbc, 'company_name'); ?> to release or obtain information to or from my insurance company, family member, employer, doctor medical records department, lawyer or representative regarding my ability to return to normal activity or work.
<br><br>
We collect information from our patients about their health history, family health history, physical condition, and previous physical therapy treatments. (Collectively referred to as "Medical Information"). Patients' Medical Information is collected and used for the purpose of diagnosing musculoskeletal conditions and providing physical therapy treatment.
<br><br>
Physical Therapists are regulated by the Alberta College of Physical Therapists of Alberta which may inspect our records and interview our staff as part of its regulatory activities in the public interest.<br><br>
	<?php } ?>

	<?php if (strpos(','.$form_config.',', ',fields2,') !== FALSE) { ?>
	I
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
    </select>
    Have read the <b>Patient Missed Appointment Policy and Personal consent form</b> and understand
these policies.
	<?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields3,') !== FALSE) { ?>
        <br><br>Date : <?php echo date('Y-m-d'); ?>
    <?php } ?>