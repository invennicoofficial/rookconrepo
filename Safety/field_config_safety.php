<?php
include ('../include.php');
checkAuthorised('safety');
error_reporting(0);

if (isset($_POST['submit'])) {

    $safety_main_site_tabs = filter_var($_POST['safety_main_site_tabs'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='safety_main_site_tabs'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$safety_main_site_tabs' WHERE name='safety_main_site_tabs'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('safety_main_site_tabs', '$safety_main_site_tabs')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $pdf_header = filter_var(htmlentities($_POST['pdf_header']),FILTER_SANITIZE_STRING);
    $pdf_footer = filter_var(htmlentities($_POST['pdf_footer']),FILTER_SANITIZE_STRING);

    $tab_field = filter_var($_POST['tab_field'],FILTER_SANITIZE_STRING);
    $form_name = filter_var($_POST['form_name'],FILTER_SANITIZE_STRING);
    $fields = implode(',',$_POST['fields']);

    $max_section = filter_var($_POST['max_section'],FILTER_SANITIZE_STRING);
    $max_subsection = filter_var($_POST['max_subsection'],FILTER_SANITIZE_STRING);
    $max_thirdsection = filter_var($_POST['max_thirdsection'],FILTER_SANITIZE_STRING);

    $pdf_logo = htmlspecialchars($_FILES["pdf_logo"]["name"], ENT_QUOTES);

    if (strpos(','.$fields.',', ','.'Topic (Sub Tab),Section #,Section Heading,Sub Section #,Sub Section Heading'.',') === false) {
        $fields = $fields.',Topic (Sub Tab),Section #,Section Heading,Sub Section #,Sub Section Heading';
    }

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(fieldconfigsafetyid) AS fieldconfigsafetyid FROM field_config_safety WHERE tab='$tab_field' AND form='$form_name'"));
    if($get_field_config['fieldconfigsafetyid'] > 0) {
		if($pdf_logo == '') {
			$logo_update = $_POST['logo_file'];
		} else {
			$logo_update = $pdf_logo;
		}
		move_uploaded_file($_FILES["pdf_logo"]["tmp_name"],"download/" . $logo_update);

        $query_update_employee = "UPDATE `field_config_safety` SET `pdf_header` = '$pdf_header', `pdf_footer` = '$pdf_footer', `fields` = '$fields', max_section = '$max_section', max_subsection = '$max_subsection', max_thirdsection = '$max_thirdsection', pdf_logo = '$logo_update' WHERE `tab`='$tab_field' AND `form`='$form_name'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
		move_uploaded_file($_FILES["pdf_logo"]["tmp_name"], "download/" . $_FILES["pdf_logo"]["name"]) ;
        $query_insert_config = "INSERT INTO `field_config_safety` (`pdf_header`, `pdf_footer`, `tab`, `form`, `fields`, `max_section`, `max_subsection`, `max_thirdsection`, `pdf_logo`) VALUES ('$pdf_header', '$pdf_footer', '$tab_field', '$form_name', '$fields', '$max_section', '$max_subsection', '$max_thirdsection', '$pdf_logo')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
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

    echo '<script type="text/javascript"> window.location.replace("field_config_safety.php?tab='.$tab_field.'&form='.$form_name.'"); </script>';
}
?>
<script type="text/javascript">
$(document).ready(function() {
	$("#tab_field").change(function() {
        window.location = 'field_config_safety.php?tab='+this.value;
	});

	$("#form_name").change(function() {
        var tab = $("#tab_field").val();
        window.location = 'field_config_safety.php?tab='+tab+'&form='+this.value;
	});

    $(".selecctall").change(function(){
      $(".field_config input:checkbox").prop('checked', $(this).prop("checked"));
    });
});
</script>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<h1>Safety</h1>
<div class="gap-top double-gap-bottom"><a href="safety.php?tab=<?php echo $_GET['tab'];?>" class="btn config-btn">Back to Dashboard</a></div>

<div class="tab-container">
    <div class="tab pull-left"><a href="field_config_tabs.php"><button class="btn brand-btn" name="tabs">Tabs</button></a></div>
    <div class="tab pull-left"><a href="field_config_safety.php"><button class="btn brand-btn active_tab" name="safety">Safety</button></a></div>
</div>

<div class="clearfix double-gap-bottom"></div>

<?php //include ('field_config_manual.php'); ?>
<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<?php
//$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT manual FROM field_config_manuals"));
//$value_config = ','.$get_field_config['manual'].',';
?>

<?php
$tab = $_GET['tab'];
$form = $_GET['form'];
$user_form_id = mysqli_fetch_array(mysqli_query($dbc, "SELECT `form_id` FROM `user_forms` WHERE `form_id` = '$form'"))['form_id'];

if($form == '') {
    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab'"));
} else {
    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$form'"));
}
$fields = ','.$get_field_config['fields'].',';
$pdf_logo = $get_field_config['pdf_logo'];
$pdf_header = $get_field_config['pdf_header'];
$pdf_footer = $get_field_config['pdf_footer'];
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
?>

  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">
		<span class="popover-examples list-inline" style="margin:0 2px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Insert your main Safety Tabs here. Use this to get started: Site 1,Site 2"><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
		Main Site Tabs:
	</label>
    <div class="col-sm-8">
      <input name="safety_main_site_tabs" value="<?php echo get_config($dbc, 'safety_main_site_tabs'); ?>" type="text" class="form-control">
    </div>
  </div>

    <div class="form-group">
        <label for="fax_number"	class="col-sm-4	control-label">
			<span class="popover-examples list-inline" style="margin:0 2px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Choose a Tab from the dropdown menu."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			Tab:
		</label>
        <div class="col-sm-8"><?php
            $get_tabs = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `value` FROM `general_configuration` WHERE `name`='safety_dashboard'"));
            $tabs_list = array_filter(explode(',', $get_tabs['value'])); ?>
            <select data-placeholder="Choose a Tab..." id="tab_field" name="tab_field" class="chosen-select-deselect form-control" width="380">
                <option value=""></option><?php
                foreach ($tabs_list as $tab_option) {
                    echo '<option '.($tab == $tab_option ? 'selected' : '').' value="'. $tab_option .'">'. $tab_option .'</option>';
                } ?>
                <!--
                <option <?php if ($tab == "Toolbox") { echo " selected"; } ?> value="Toolbox">Toolbox</option>
                <option <?php if ($tab == "Tailgate") { echo " selected"; } ?> value="Tailgate">Tailgate</option>
                <option <?php if ($tab == "Form") { echo " selected"; } ?> value="Form">Form</option>
                <option <?php if ($tab == "Manual") { echo " selected"; } ?> value="Manual">Manual</option>
                -->
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="fax_number"	class="col-sm-4	control-label">
			<span class="popover-examples list-inline" style="margin:0 2px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Choose a Form from the dropdown menu."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			Form:
		</label>
        <div class="col-sm-8">
            <select data-placeholder="Choose a Form..." id="form_name" name="form_name" class="chosen-select-deselect form-control" width="380">
              <option value=""></option>
              <option <?php if ($form == "Field Level Hazard Assessment") { echo " selected"; } ?> value="Field Level Hazard Assessment">Field Level Hazard Assessment</option>
              <option <?php if ($form == "Hydrera Site Specific Pre Job Safety Meeting Hazard Assessment") { echo " selected"; } ?> value="Hydrera Site Specific Pre Job Safety Meeting Hazard Assessment">Hydrera Site Specific Pre Job Safety Meeting Hazard Assessment</option>
              <option <?php if ($form == "Weekly Safety Meeting") { echo " selected"; } ?> value="Weekly Safety Meeting">Weekly Safety Meeting</option>
              <option <?php if ($form == "Tailgate Safety Meeting") { echo " selected"; } ?> value="Tailgate Safety Meeting">Tailgate Safety Meeting</option>
              <option <?php if ($form == "Toolbox Safety Meeting") { echo " selected"; } ?> value="Toolbox Safety Meeting">Toolbox Safety Meeting</option>
              <option <?php if ($form == "Daily Equipment Inspection Checklist") { echo " selected"; } ?> value="Daily Equipment Inspection Checklist">Daily Equipment Inspection Checklist</option>
              <option <?php if ($form == "AVS Hazard Identification") { echo " selected"; } ?> value="AVS Hazard Identification">AVS Hazard Identification</option>
              <option <?php if ($form == "AVS Near Miss") { echo " selected"; } ?> value="AVS Near Miss">AVS Near Miss</option>
              <option <?php if ($form == "Incident Investigation Report") { echo " selected"; } ?> value="Incident Investigation Report">Incident Investigation Report</option>
              <option <?php if ($form == "Follow Up Incident Report") { echo " selected"; } ?> value="Follow Up Incident Report">Follow Up Incident Report</option>
              <option <?php if ($form == "Pre Job Hazard Assessment") { echo " selected"; } ?> value="Pre Job Hazard Assessment">Pre Job Hazard Assessment</option>
              <option <?php if ($form == "Monthly Site Safety Inspections") { echo " selected"; } ?> value="Monthly Site Safety Inspections">Monthly Site Safety Inspections</option>
              <option <?php if ($form == "Monthly Office Safety Inspections") { echo " selected"; } ?> value="Monthly Office Safety Inspections">Monthly Office Safety Inspections</option>
              <option <?php if ($form == "Monthly Health and Safety Summary") { echo " selected"; } ?> value="Monthly Health and Safety Summary">Monthly Health and Safety Summary</option>
              <option <?php if ($form == "Trailer Inspection Checklist") { echo " selected"; } ?> value="Trailer Inspection Checklist">Trailer Inspection Checklist</option>
              <option <?php if ($form == "Employee Misconduct Form") { echo " selected"; } ?> value="Employee Misconduct Form">Employee Misconduct Form</option>
              <option <?php if ($form == "Site Inspection Hazard Assessment") { echo " selected"; } ?> value="Site Inspection Hazard Assessment">Site Inspection Hazard Assessment</option>
              <option <?php if ($form == "Weekly Planned Inspection Checklist") { echo " selected"; } ?> value="Weekly Planned Inspection Checklist">Weekly Planned Inspection Checklist</option>
              <option <?php if ($form == "Equipment Inspection Checklist") { echo " selected"; } ?> value="Equipment Inspection Checklist">Equipment Inspection Checklist</option>
              <option <?php if ($form == "Employee Equipment Training Record") { echo " selected"; } ?> value="Employee Equipment Training Record">Employee Equipment Training Record</option>
              <option <?php if ($form == "Vehicle Inspection Checklist") { echo " selected"; } ?> value="Vehicle Inspection Checklist">Vehicle Inspection Checklist</option>
              <option <?php if ($form == "Safety Meeting Minutes") { echo " selected"; } ?> value="Safety Meeting Minutes">Safety Meeting Minutes</option>
              <option <?php if ($form == "Vehicle Damage Report") { echo " selected"; } ?> value="Vehicle Damage Report">Vehicle Damage Report</option>
              <option <?php if ($form == "Fall Protection Plan") { echo " selected"; } ?> value="Fall Protection Plan">Fall Protection Plan</option>
              <option <?php if ($form == "Spill Incident Report") { echo " selected"; } ?> value="Spill Incident Report">Spill Incident Report</option>
              <option <?php if ($form == "General Site Safety Inspection") { echo " selected"; } ?> value="General Site Safety Inspection">General Site Safety Inspection</option>
              <option <?php if ($form == "Confined Space Entry Permit") { echo " selected"; } ?> value="Confined Space Entry Permit">Confined Space Entry Permit</option>
              <option <?php if ($form == "Lanyards Inspection Checklist Log") { echo " selected"; } ?> value="Lanyards Inspection Checklist Log">Lanyards Inspection Checklist Log</option>
              <option <?php if ($form == "On The Job Training Record") { echo " selected"; } ?> value="On The Job Training Record">On The Job Training Record</option>
              <option <?php if ($form == "General Office Safety Inspection") { echo " selected"; } ?> value="General Office Safety Inspection">General Office Safety Inspection</option>
              <option <?php if ($form == "Full Body Harness Inspection Checklist Log") { echo " selected"; } ?> value="Full Body Harness Inspection Checklist Log">Full Body Harness Inspection Checklist Log</option>
              <option <?php if ($form == "Confined Space Pre Entry Checklist") { echo " selected"; } ?> value="Confined Space Pre Entry Checklist">Confined Space Pre Entry Checklist</option>
              <option <?php if ($form == "Confined Space Entry Log") { echo " selected"; } ?> value="Confined Space Entry Log">Confined Space Entry Log</option>
              <option <?php if ($form == "Emergency Response Transportation Plan") { echo " selected"; } ?> value="Emergency Response Transportation Plan">Emergency Response Transportation Plan</option>
              <option <?php if ($form == "Hazard Id Report") { echo " selected"; } ?> value="Hazard Id Report">Hazard Id Report</option>
              <option <?php if ($form == "Dangerous Goods Shipping Document") { echo " selected"; } ?> value="Dangerous Goods Shipping Document">Dangerous Goods Shipping Document</option>
              <option <?php if ($form == "Safe Work Permit") { echo " selected"; } ?> value="Safe Work Permit">Safe Work Permit</option>
              <option <?php if ($form == "Journey Management - Trip Tracking Form") { echo " selected"; } ?> value="Journey Management - Trip Tracking Form">Journey Management - Trip Tracking Form</option>
              <?php
              $query = mysqli_query($dbc, "SELECT * FROM `user_forms` WHERE CONCAT(',',`assigned_tile`,',') LIKE '%,safety,%'");;
              while ($row = mysqli_fetch_array($query)) { ?>
                <option <?php if ($user_form_id == $row['form_id']) { echo " selected" ; } ?> value="<?php echo $row['form_id']; ?>"><?php echo $row['name']; ?></option>
              <?php }
              $query = "SELECT * FROM `field_config_incident_report` WHERE `row_type` = ''";
              $result = mysqli_fetch_array(mysqli_query($dbc, $query))['incident_types'];
              $incident_reports = explode(',',$result);
              foreach ($incident_reports as $incident_report) { ?>
                <option <?php if ($form == $incident_report) { echo " selected" ; } ?> value="<?php echo $incident_report; ?>"><?php echo $incident_report; ?></option>
              <?php } ?>
           </select>
        </div>
    </div>

    <span class="popover-examples list-inline" style="margin:0 2px 0 5px;"><a data-toggle="tooltip" data-placement="top" title="Make sure you select the desired Tab and Form before changing anything in the following accordion."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><br>
	<br>

	<div class="panel-group" id="accordion2">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field" >
                        Choose Fields for Safety<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_field" class="panel-collapse collapse">
                <div class="panel-body">
					<div id='no-more-tables'>

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
                                <input type="checkbox" <?php if (strpos($fields, ','."Staff".',') !== FALSE) { echo " checked"; } ?> value="Staff" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Staff
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
                                <input type="checkbox" <?php if (strpos($fields, ','."Row Marker".',') !== FALSE) { echo " checked"; } ?> value="Row Marker" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Row Marker
                            </td>
							<td>
								<input type="checkbox" <?php if (strpos($fields, ','."Configure Email".',') !== FALSE) { echo " checked"; } ?> value="Configure Email" name="fields[]">&nbsp;&nbsp;Configure Email
							</td>
                        </tr>
                    </table>
					</div>
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
                    <label for="company_name" class="col-sm-4 control-label">Max Section #:<br><em>(add only digits)</em></label>
                    <div class="col-sm-8">
                      <input name="max_section" value="<?php echo $max_section; ?>" type="text" class="form-control">
                    </div>
                    </div>

                    <div class="form-group">
                    <label for="company_name" class="col-sm-4 control-label">Max Sub Section #:<br><em>(add only digits)</em></label>
                    <div class="col-sm-8">
                      <input name="max_subsection" value="<?php echo $max_subsection; ?>" type="text" class="form-control">
                    </div>
                    </div>

                    <div class="form-group">
                    <label for="company_name" class="col-sm-4 control-label">Max Third Tier Section #:<br><em>(add only digits)</em></label>
                    <div class="col-sm-8">
                      <input name="max_thirdsection" value="<?php echo $max_thirdsection ?>" type="text" class="form-control">
                    </div>
                    </div>

                </div>
            </div>
        </div>

        <?php if ($form == "Field Level Hazard Assessment") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_hazard" >
                        Field Level Hazard Assessment<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_hazard" class="panel-collapse collapse">
                <div class="panel-body">

                    <?php include ('field_level_hazard_assessment/field_config_field_level_hazard_assessment.php'); ?>

                 </div>
            </div>
        </div>
        <?php } ?>


        <?php if ($form == "Hydrera Site Specific Pre Job Safety Meeting Hazard Assessment") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_hazard2" >
                        Hydrera Site Specific Pre Job Safety Meeting Hazard Assessment<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_hazard2" class="panel-collapse collapse">
                <div class="panel-body">

                    <?php include ('hydrera_site_specificpre_job/field_config_hydrera_site_specificpre_job.php'); ?>

                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Weekly Safety Meeting") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_weekly_safety_meeting" >
                        Weekly Safety Meeting<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_weekly_safety_meeting" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('weekly_safety_meeting/field_config_weekly_safety_meeting.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Tailgate Safety Meeting") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_tailgate_safety_meeting" >
                        Tailgate Safety Meeting<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_tailgate_safety_meeting" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('tailgate_safety_meeting/field_config_tailgate_safety_meeting.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Toolbox Safety Meeting") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_toolbox_safety_meeting" >
                        Toolbox Safety Meeting<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_toolbox_safety_meeting" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('toolbox_safety_meeting/field_config_toolbox_safety_meeting.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Daily Equipment Inspection Checklist") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_deic" >
                        Daily Equipment Inspection Checklist<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_deic" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('daily_equipment_inspection_checklist/field_config_daily_equipment_inspection_checklist.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "AVS Hazard Identification") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ahi" >
                        AVS Hazard Identification<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ahi" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('avs_hazard_identification/field_config_avs_hazard_identification.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "AVS Near Miss") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_nm" >
                        AVS Near Miss<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_nm" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('near_miss_report/field_config_near_miss_report.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Incident Investigation Report") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_iir" >
                        Incident Investigation Report<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_iir" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('incident_investigation_report/field_config_incident_investigation_report.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Follow Up Incident Report") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_fuir" >
                        Follow Up Incident Report<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_fuir" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('follow_up_incident_report/field_config_follow_up_incident_report.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Pre Job Hazard Assessment") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_pjha" >
                        Pre Job Hazard Assessment<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_pjha" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('pre_job_hazard_assessment/field_config_pre_job_hazard_assessment.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Monthly Site Safety Inspections") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_msosi" >
                        Monthly Site Safety Inspections<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_msosi" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('monthly_site_safety_inspection/field_config_monthly_site_safety_inspection.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Monthly Office Safety Inspections") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_msosio" >
                        Monthly Office Safety Inspections<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_msosio" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('monthly_office_safety_inspection/field_config_monthly_office_safety_inspection.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Monthly Health and Safety Summary") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_mhass" >
                        Monthly Health and Safety Summary<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_mhass" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('monthly_health_and_safety_summary/field_config_monthly_health_and_safety_summary.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Trailer Inspection Checklist") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_tic" >
                        Trailer Inspection Checklist<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_tic" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('trailer_inspection_checklist/field_config_trailer_inspection_checklist.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Employee Misconduct Form") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_emf" >
                        Employee Misconduct Form<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_emf" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('employee_misconduct_form/field_config_employee_misconduct_form.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Site Inspection Hazard Assessment") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_siha" >
                        Site Inspection Hazard Assessment<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_siha" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('site_inspection_hazard_assessment/field_config_site_inspection_hazard_assessment.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Weekly Planned Inspection Checklist") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_wpic" >
                        Weekly Planned Inspection Checklist<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_wpic" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('weekly_planned_inspection_checklist/field_config_weekly_planned_inspection_checklist.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Equipment Inspection Checklist") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_eic" >
                        Equipment Inspection Checklist<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_eic" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('equipment_inspection_checklist/field_config_equipment_inspection_checklist.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Employee Equipment Training Record") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_eetr" >
                        Employee Equipment Training Record<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_eetr" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('employee_equipment_training_record/field_config_employee_equipment_training_record.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Vehicle Inspection Checklist") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_vic" >
                        Vehicle Inspection Checklist<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_vic" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('vehicle_inspection_checklist/field_config_vehicle_inspection_checklist.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Safety Meeting Minutes") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_smm" >
                        Safety Meeting Minutes<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_smm" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('safety_meeting_minutes/field_config_safety_meeting_minutes.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Vehicle Damage Report") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_vdr" >
                        Vehicle Damage Report<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_vdr" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('vehicle_damage_report/field_config_vehicle_damage_report.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Fall Protection Plan") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_fpp" >
                        Fall Protection Plan<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_fpp" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('fall_protection_plan/field_config_fall_protection_plan.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Spill Incident Report") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_sir" >
                        Spill Incident Report<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_sir" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('spill_incident_report/field_config_spill_incident_report.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "General Site Safety Inspection") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_gssi" >
                        General Site Safety Inspection<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_gssi" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('general_site_safety_inspection/field_config_general_site_safety_inspection.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Confined Space Entry Permit") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_csep" >
                        Confined Space Entry Permit<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_csep" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('confined_space_entry_permit/field_config_confined_space_entry_permit.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Lanyards Inspection Checklist Log") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_licl" >
                        Lanyards Inspection Checklist Log<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_licl" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('lanyards_inspection_checklist_log/field_config_lanyards_inspection_checklist_log.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "On The Job Training Record") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_otjtr" >
                        On The Job Training Record<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_otjtr" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('on_the_job_training_record/field_config_on_the_job_training_record.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "General Office Safety Inspection") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_gosi" >
                        General Office Safety Inspection<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_gosi" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('general_office_safety_inspection/field_config_general_office_safety_inspection.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Full Body Harness Inspection Checklist Log") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_fbhicl" >
                        Full Body Harness Inspection Checklist Log<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_fbhicl" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('full_body_harness_inspection_checklist_log/field_config_full_body_harness_inspection_checklist_log.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Confined Space Pre Entry Checklist") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_cspec" >
                        Confined Space Pre Entry Checklist<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_cspec" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('confined_space_entry_pre_entry_checklist/field_config_confined_space_entry_pre_entry_checklist.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
       <?php if ($form == "Confined Space Entry Log") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_csel" >
                        Confined Space Entry Log<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_csel" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('confined_space_entry_log/field_config_confined_space_entry_log.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Emergency Response Transportation Plan") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ertp" >
                        Emergency Response Transportation Plan<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ertp" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('emergency_response_transportation_plan/field_config_emergency_response_transportation_plan.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

       <?php if ($form == "Hazard Id Report") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_hir" >
                        Hazard Id Report<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_hir" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('hazard_id_report/field_config_hazard_id_report.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Dangerous Goods Shipping Document") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_dgsd" >
                        Dangerous Goods Shipping Document<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_dgsd" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('dangerous_goods_shipping_document/field_config_dangerous_goods_shipping_document.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Safe Work Permit") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_swp" >
                        Safe Work Permit<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_swp" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('safe_work_permit/field_config_safe_work_permit.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Journey Management - Trip Tracking Form") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_jmttf" >
                        Journey Management - Trip Tracking Form<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_jmttf" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('journey_management_trip_tracking/field_config_journey_management_trip_tracking.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

    </div>

<?php
    $category = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT category FROM manuals WHERE deleted=0 AND manual_type='safety' LIMIT 1"));
    $manual_category = $category['category'];
    if($manual_category == '') {
       $manual_category = 0;
    }
?>
<div class="form-group">
    <div class="col-sm-6">
        <a href="safety.php?tab=<?php echo $tab; ?>" class="btn config-btn btn-lg">Back</a>
    </div>
    <div class="col-sm-6">
        <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
    </div>
</div>
        
</form>
</div>
</div>

<?php include ('../footer.php'); ?>