<?php
include ('../include.php');
checkAuthorised('treatment_charts');
error_reporting(0);

if (isset($_POST['submit'])) {

    $form_name = filter_var($_POST['form_name'],FILTER_SANITIZE_STRING);
    $fields = implode(',',$_POST['fields']);

    $max_section = filter_var($_POST['max_section'],FILTER_SANITIZE_STRING);
    $max_subsection = filter_var($_POST['max_subsection'],FILTER_SANITIZE_STRING);
    $max_thirdsection = filter_var($_POST['max_thirdsection'],FILTER_SANITIZE_STRING);
    if (isset($_POST['attach_contact']) && $_POST['attach_contact'] == 1) {
        $attach_contact_type = $_POST['attach_contact_type'];
    } else {
        $attach_contact_type = '';
    }

    $pdf_logo = htmlspecialchars($_FILES["pdf_logo"]["name"], ENT_QUOTES);

    if (strpos(','.$fields.',', ','.'Topic (Sub Tab),Section #,Section Heading,Sub Section #,Sub Section Heading'.',') === false) {
        $fields = $fields.',Topic (Sub Tab),Section #,Section Heading,Sub Section #,Sub Section Heading';
    }

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(fieldconfigpatientformid) AS fieldconfigpatientformid FROM field_config_patientform WHERE form='$form_name'"));
    if($get_field_config['fieldconfigpatientformid'] > 0) {
		if($pdf_logo == '') {
			$logo_update = $_POST['logo_file'];
		} else {
			$logo_update = $pdf_logo;
		}
		move_uploaded_file($_FILES["pdf_logo"]["tmp_name"],"download/" . $logo_update);

        $query_update_employee = "UPDATE `field_config_patientform` SET `fields` = '$fields', max_section = '$max_section', max_subsection = '$max_subsection', max_thirdsection = '$max_thirdsection', pdf_logo = '$logo_update', attach_contact_type = '$attach_contact_type' WHERE `form`='$form_name'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
		move_uploaded_file($_FILES["pdf_logo"]["tmp_name"], "download/" . $_FILES["pdf_logo"]["name"]) ;
        $query_insert_config = "INSERT INTO `field_config_patientform` (`form`, `fields`, `max_section`, `max_subsection`, `max_thirdsection`, `pdf_logo`, `attach_contact_type`) VALUES ('$form_name', '$fields', '$max_section', '$max_subsection', '$max_thirdsection', '$pdf_logo', '$attach_contact_type')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

	// Preset Values
	$preset_ids = implode(',',$_POST['preset_id']);
	$sql = "DELETE FROM `field_config_treatment_presets` WHERE `configid` NOT IN ($preset_ids)";
	$result = mysqli_query($dbc, $sql);
	foreach($_POST['preset_value'] as $key => $value) {
		$field_name = $_POST['preset_field'][$key];
		$field_id = $_POST['preset_id'][$key];
		$field_value = filter_var(htmlentities($value),FILTER_SANITIZE_STRING);
		if($field_id == '') {
			$sql = "INSERT INTO `field_config_treatment_presets` (`form`, `field`, `preset_text`) VALUES ('$form_name', '$field_name', '$field_value')";
		} else {
			$sql = "UPDATE `field_config_treatment_presets` SET `preset_text`='$field_value' WHERE `configid`='$field_id'";
		}
		if($field_value != '') {
			$result = mysqli_query($dbc, $sql);
		}
	}

    /*
    $manual_policy_pro_email = filter_var($_POST['manual_policy_pro_email'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='manual_policy_pro_email'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$manual_policy_pro_email' WHERE name='manual_policy_pro_email'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('manual_policy_pro_email', '$manual_policy_pro_email')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    */

    echo '<script type="text/javascript"> window.location.replace("field_config_patientform.php?form='.$form_name.'"); </script>';
}
?>
<script type="text/javascript">
$(document).ready(function() {
	$("#tab_field").change(function() {
        window.location = 'field_config_patientform.php?tab='+this.value;
	});

	$("#form_name").change(function() {
        var tab = $("#tab_field").val();
        window.location = 'field_config_patientform.php?tab='+tab+'&form='+this.value;
	});

    $(".selecctall").change(function(){
      $("input:checkbox").prop('checked', $(this).prop("checked"));
    });
});
</script>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<a href="index.php" class="btn config-btn">Back</a>

<?php //include ('field_config_manual.php'); ?>
<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<?php
//$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT manual FROM field_config_manuals"));
//$value_config = ','.$get_field_config['manual'].',';
?>

<?php
$form = $_GET['form'];
$user_form_id = mysqli_fetch_array(mysqli_query($dbc, "SELECT `form_id` FROM `user_forms` WHERE `form_id` = '$form'"))['form_id'];

$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_patientform WHERE form='$form'"));

$fields = ','.$get_field_config['fields'].',';
$pdf_logo = $get_field_config['pdf_logo'];
$max_section = $get_field_config['max_section'];
$max_subsection = $get_field_config['max_subsection'];
$max_thirdsection = $get_field_config['max_thirdsection'];
if($max_section == '') {
    $max_section = 10;
}
if($max_subsection == '') {
    $max_subsection = 10;
}
if($max_thirdsection == '') {
    $max_thirdsection = 10;
}
$attach_contact_type = $get_field_config['attach_contact_type'];
?>

    <div class="form-group">
        <label for="fax_number"	class="col-sm-4	control-label">Form:</label>
        <div class="col-sm-8">
            <select data-placeholder="Select a Form..." id="form_name" name="form_name" class="chosen-select-deselect form-control" width="380">
              <option value=""></option>
              <option <?php if ($form == "Whiplash Associated Disorders") { echo " selected"; } ?> value="Whiplash Associated Disorders">Whiplash Associated Disorders</option>
              <option <?php if ($form == "WCB Provider Employer Contact") { echo " selected"; } ?> value="WCB Provider Employer Contact">WCB Provider Employer Contact</option>
              <option <?php if ($form == "WCB Calendar") { echo " selected"; } ?> value="WCB Calendar">WCB Calendar</option>
              <option <?php if ($form == "Spinal Discharge Checklist") { echo " selected"; } ?> value="Spinal Discharge Checklist">Spinal Discharge Checklist</option>
              <option <?php if ($form == "Roland Morris Questionnaire") { echo " selected"; } ?> value="Roland Morris Questionnaire">Roland Morris Questionnaire</option>
              <option <?php if ($form == "Personal Consent Form") { echo " selected"; } ?> value="Personal Consent Form">Personal Consent Form</option>
              <option <?php if ($form == "Neck Disability Questionnaire") { echo " selected"; } ?> value="Neck Disability Questionnaire">Neck Disability Questionnaire</option>
              <option <?php if ($form == "Treatment Notes") { echo " selected"; } ?> value="Treatment Notes">Treatment Notes</option>
              <option <?php if ($form == "Medical History Form") { echo " selected"; } ?> value="Medical History Form">Medical History Form</option>
              <option <?php if ($form == "Massage Treatment Notes") { echo " selected"; } ?> value="Massage Treatment Notes">Massage Treatment Notes</option>
              <option <?php if ($form == "Prescribed Treatment Schedule") { echo " selected"; } ?> value="Prescribed Treatment Schedule">Prescribed Treatment Schedule</option>
              <option <?php if ($form == "AB-1 Initial Claim Form") { echo " selected"; } ?> value="AB-1 Initial Claim Form">AB-1 Initial Claim Form</option>
              <option <?php if ($form == "AB-2 Treatment Plan") { echo " selected"; } ?> value="AB-2 Treatment Plan">AB-2 Treatment Plan</option>
              <option <?php if ($form == "AB-3 Progress Report") { echo " selected"; } ?> value="AB-3 Progress Report">AB-3 Progress Report</option>
              <option <?php if ($form == "AB-4 Concluding Report") { echo " selected"; } ?> value="AB-4 Concluding Report">AB-4 Concluding Report</option>
              <option <?php if ($form == "Concluding Report") { echo " selected"; } ?> value="Concluding Report">Concluding Report</option>
              <option <?php if ($form == "Progress Report") { echo " selected"; } ?> value="Progress Report">Progress Report</option>
              <option <?php if ($form == "Body Targeted Assessment") { echo " selected"; } ?> value="Body Targeted Assessment">Body Targeted Assessment</option>
              <option <?php if ($form == "General Assessment") { echo " selected"; } ?> value="General Assessment">General Assessment</option>
              <option <?php if ($form == "General Treatment") { echo " selected"; } ?> value="General Treatment">General Treatment</option>
              <option <?php if ($form == "General Treatment Plan") { echo " selected"; } ?> value="General Treatment Plan">General Treatment Plan</option>
              <option <?php if ($form == "General Discharge") { echo " selected"; } ?> value="General Discharge">General Discharge</option>
              <option <?php if ($form == "C040 Employer Report of Injury") { echo " selected"; } ?> value="C040 Employer Report of Injury">C040 Employer Report of Injury</option>
              <?php
                  $query = mysqli_query ($dbc, "SELECT * FROM `user_forms` WHERE CONCAT(',',`assigned_tile`,',') LIKE '%,treatment,%'");;
                  while ($row = mysqli_fetch_array($query)) { ?>
                    <option <?php if ($user_form_id == $row['form_id']) { echo " selected" ; } ?> value="<?php echo $row['form_id']; ?>"><?php echo $row['name']; ?></option>
              <?php } ?>
            </select>
        </div>
    </div>

    <div class="panel-group" id="accordion2">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field" >
                        Choose Fields for Treatment Chart<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_field" class="panel-collapse collapse">
                <div class="panel-body">

                    <table border='2' cellpadding='10' class='table'>
                        <tr>
                            <td>
                                <input disabled type="checkbox" <?php if (strpos($fields, ','."Topic (Sub Tab)".',') !== FALSE) { echo " checked"; } ?> value="Topic (Sub Tab)" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Topic (Sub Tab)
                            </td>
                            <td>
                                <input disabled type="checkbox" <?php if (strpos($fields, ','."Section #".',') !== FALSE) { echo " checked"; } ?> value="Section #" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Section #
                            </td>
                            <td>
                                <input disabled type="checkbox" <?php if (strpos($fields, ','."Section Heading".',') !== FALSE) { echo " checked"; } ?> value="Section Heading" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Section Heading
                            </td>
                            <td>
                                <input disabled type="checkbox" <?php if (strpos($fields, ','."Sub Section #".',') !== FALSE) { echo " checked"; } ?> value="Sub Section #" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Sub Section #
                            </td>
                            <td>
                                <input disabled type="checkbox" <?php if (strpos($fields, ','."Sub Section Heading".',') !== FALSE) { echo " checked"; } ?> value="Sub Section Heading" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Sub Section Heading
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Third Tier Section #".',') !== FALSE) { echo " checked"; } ?> value="Third Tier Section #" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Third Tier Section #
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Third Tier Heading".',') !== FALSE) { echo " checked"; } ?> value="Third Tier Heading" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Third Tier Heading
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Detail".',') !== FALSE) { echo " checked"; } ?> value="Detail" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Detail
                            </td>

                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Document".',') !== FALSE) { echo " checked"; } ?> value="Document" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Document
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Link".',') !== FALSE) { echo " checked"; } ?> value="Link" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Link
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Videos".',') !== FALSE) { echo " checked"; } ?> value="Videos" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Videos
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Signature box".',') !== FALSE) { echo " checked"; } ?> value="Signature box" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Signature box
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Comments".',') !== FALSE) { echo " checked"; } ?> value="Comments" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Comments
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Staff".',') !== FALSE) { echo " checked"; } ?> value="Staff" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Assign to Staff
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Review Deadline".',') !== FALSE) { echo " checked"; } ?> value="Review Deadline" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Review Deadline
                            </td>

                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Status".',') !== FALSE) { echo " checked"; } ?> value="Status" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Status
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Form".',') !== FALSE) { echo " checked"; } ?> value="Form" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Form
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Hide Injury".',') !== FALSE) { echo " checked"; } ?> value="Hide Injury" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Hide Injury
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Date".',') !== FALSE) { echo " checked"; } ?> value="Date" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Date
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Filled By Staff".',') !== FALSE) { echo " checked"; } ?> value="Filled By Staff" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Filled by Staff
                            </td>
                            <td>
                                <input type="checkbox" <?php if (!empty($attach_contact_type)) { echo " checked"; } ?> value="1" style="height: 20px; width: 20px;" name="attach_contact">&nbsp;&nbsp;Attach to Contact
                                <select data-placeholder="Select Contact Type" name="attach_contact_type" class="chosen-select-deselect form-control">
                                    <option></option>
                                    <?php
                                        $query = "SELECT DISTINCT `category` FROM `contacts` WHERE `category` != 'Business' AND `deleted` = 0 ORDER BY `category`";
                                        $result = mysqli_query($dbc, $query);
                                        while ($row = mysqli_fetch_array($result)) {
                                            echo '<option value="'.$row['category'].'"'.($row['category'] == $attach_contact_type ? ' selected' : '').'>'.$row['category'].'</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Ticket".',') !== FALSE) { echo " checked"; } ?> value="Ticket" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Attach to <?= TICKET_NOUN ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!--
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_email" >
                        Send Email on Comment<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_email" class="panel-collapse collapse">
                <div class="panel-body">

                    <div class="form-group">
                    <label for="company_name" class="col-sm-4 control-label">Send Email on Comment:</label>
                    <div class="col-sm-8">
                      <input name="manual_policy_pro_email" value="<?php echo get_config($dbc, 'manual_policy_pro_email'); ?>" type="text" class="form-control">
                    </div>
                    </div>

                </div>
            </div>
        </div>
        -->

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_max" >
                        Max Selection<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_max" class="panel-collapse collapse">
                <div class="panel-body">

                    <div class="form-group">
                    <label for="company_name" class="col-sm-4 control-label">Max Section #:<br><em>(Add Digit Only)</em></label>
                    <div class="col-sm-8">
                      <input name="max_section" value="<?php echo $max_section; ?>" type="text" class="form-control">
                    </div>
                    </div>

                    <div class="form-group">
                    <label for="company_name" class="col-sm-4 control-label">Max Sub Section #:<br><em>(Add Digit Only)</em></label>
                    <div class="col-sm-8">
                      <input name="max_subsection" value="<?php echo $max_subsection; ?>" type="text" class="form-control">
                    </div>
                    </div>

                    <div class="form-group">
                    <label for="company_name" class="col-sm-4 control-label">Max Third Tier Section #:<br><em>(Add Digit Only)</em></label>
                    <div class="col-sm-8">
                      <input name="max_thirdsection" value="<?php echo $max_thirdsection ?>" type="text" class="form-control">
                    </div>
                    </div>

                </div>
            </div>
        </div>

        <?php if ($form == "Whiplash Associated Disorders") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_cbi" >
                        Whiplash Associated Disorders<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_cbi" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('whiplash_associated_disorders/field_config_whiplash_associated_disorders.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "WCB Provider Employer Contact") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_bq" >
                        WCB Provider Employer Contact<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_bq" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('wcb_provider_employer_contact/field_config_wcb_provider_employer_contact.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "WCB Calendar") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_wig" >
                        WCB Calendar<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_wig" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('wcb_calendar/field_config_wcb_calendar.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Spinal Discharge Checklist") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_blog" >
                        Spinal Discharge Checklist<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_blog" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('spinal_discharge_checklist/field_config_spinal_discharge_checklist.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Roland Morris Questionnaire") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_smig" >
                        Roland Morris Questionnaire<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_smig" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('roland_morris_questionnaire/field_config_roland_morris_questionnaire.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Personal Consent Form") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_smsuq" >
                        Personal Consent Form<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_smsuq" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('personal_consent_form/field_config_personal_consent_form.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Neck Disability Questionnaire") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ndq" >
                        Neck Disability Questionnaire<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ndq" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('neck_disability_questionnaire/field_config_neck_disability_questionnaire.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Treatment Notes") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_tn" >
                        Treatment Notes<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_tn" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('treatment_notes/field_config_treatment_notes.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Medical History Form") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_pfs" >
                        Medical History Form<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_pfs" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('patient_functional_scale/field_config_patient_functional_scale.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Massage Treatment Notes") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_mtn" >
                        Massage Treatment Notes<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_mtn" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('massage_treatment_notes/field_config_massage_treatment_notes.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Prescribed Treatment Schedule") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_pts" >
                        Prescribed Treatment Schedule<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_pts" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('prescribed_treatment_schedule/field_config_prescribed_treatment_schedule.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "AB-1 Initial Claim Form") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_tp" >
                        AB-1 Initial Claim Form<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_tp" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('wcb_ab_forms/field_config_ab1_initial_claim.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "AB-2 Treatment Plan") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_tp" >
                        AB-2 Treatment Plan<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_tp" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('treatment_plan/field_config_treatment_plan.php'); ?>
                 </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_tp_p" >
                        AB-2 Treatment Plan Presets<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_tp_p" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('treatment_plan/field_config_treatment_plan_presets.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "AB-3 Progress Report") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_tp" >
                        AB-3 Progress Report<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_tp" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('wcb_ab_forms/field_config_ab3_progress_report.php'); ?>
                 </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_tp_p" >
                        AB-3 Progress Report<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_tp_p" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('wcb_ab_forms/field_config_ab3_progress_report_presets.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "AB-4 Concluding Report") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_tp" >
                        AB-4 Concluding Report<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_tp" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('wcb_ab_forms/field_config_ab4_concluding_report.php'); ?>
                 </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_tp_p" >
                        AB-4 Concluding Report<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_tp_p" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('wcb_ab_forms/field_config_ab4_concluding_report_presets.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Concluding Report") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_cr" >
                        Concluding Report<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_cr" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('concluding_report/field_config_concluding_report.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Progress Report") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_pr" >
                        Progress Report<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_pr" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('progress_report/field_config_progress_report.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

    </div>

<?php
    $category = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT category FROM manuals WHERE deleted=0 AND manual_type='patientform' LIMIT 1"));
    $manual_category = $category['category'];
    if($manual_category == '') {
       $manual_category = 0;
    }
?>
<div class="form-group">
    <div class="col-sm-4 clearfix">
        <a href="index.php" class="btn brand-btn pull-right">Back</a>
    </div>
    <div class="col-sm-8">
        <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
    </div>
</div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>
