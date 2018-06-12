<?php
include ('../include.php');
checkAuthorised('hr');
error_reporting(0);

if (isset($_POST['submit_tabs'])) {
    $hr_tabs = get_config($dbc, 'hr_tabs');
    if(empty($hr_tabs)) {
        mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('hr_tabs', 'Form,Manual,Onboarding,Orientation')");
    }
    $hr_tabs = filter_var($_POST['hr_tabs'],FILTER_SANITIZE_STRING);
    mysqli_query($dbc, "UPDATE `general_configuration` SET `value` = '$hr_tabs' WHERE `name` = 'hr_tabs'");
} else if (isset($_POST['submit'])) {
    $pdf_header = filter_var(htmlentities($_POST['pdf_header']),FILTER_SANITIZE_STRING);
    $pdf_footer = filter_var(htmlentities($_POST['pdf_footer']),FILTER_SANITIZE_STRING);

    $tab_field = filter_var($_POST['tab_field'],FILTER_SANITIZE_STRING);
    $form_name = filter_var($_POST['form_name'],FILTER_SANITIZE_STRING);
    $fields = implode(',',$_POST['fields']);
	$hr_description = filter_var(htmlentities($_POST['hr_description']),FILTER_SANITIZE_STRING);

	$document = '**##**';
    $total_task = count($_POST['doc_name']);
    for($i=0; $i<$total_task; $i++) {
        if($_POST['doc_name'][$i] != '') {
		    move_uploaded_file($_FILES["doc_upload"]["tmp_name"][$i],"download/" . $_FILES["doc_upload"]["name"][$i]);
            $document .= $_POST['doc_name'][$i].'**'.$_FILES["doc_upload"]["name"][$i].'**##**';
        }
    }

    $max_section = filter_var($_POST['max_section'],FILTER_SANITIZE_STRING);
    $max_subsection = filter_var($_POST['max_subsection'],FILTER_SANITIZE_STRING);
    $max_thirdsection = filter_var($_POST['max_thirdsection'],FILTER_SANITIZE_STRING);

    $pdf_logo = htmlspecialchars($_FILES["pdf_logo"]["name"], ENT_QUOTES);

    $config_extra_fields = implode('**FFM**',$_POST['config_extra_fields']);

    if (strpos(','.$fields.',', ','.'Topic (Sub Tab),Section #,Section Heading,Sub Section #,Sub Section Heading'.',') === false) {
        $fields = $fields.',Topic (Sub Tab),Section #,Section Heading,Sub Section #,Sub Section Heading';
    }

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(fieldconfighrid) AS fieldconfighrid FROM field_config_hr WHERE tab='$tab_field' AND form='$form_name'"));
	if(($tab_field == '' || $form_name == '') && $max_section != '' && $max_subsection != '') {
		$query_update_employee = "UPDATE `field_config_hr` SET max_section = '$max_section', max_subsection = '$max_subsection', max_thirdsection = '$max_thirdsection'";
		$result_update_employee = mysqli_query($dbc, $query_update_employee);
	}
	else {
		if($get_field_config['fieldconfighrid'] > 0) {
			if($pdf_logo == '') {
				$logo_update = htmlspecialchars($_POST['logo_file'], ENT_QUOTES);
			} else {
				$logo_update = $pdf_logo;
			}
			move_uploaded_file($_FILES["pdf_logo"]["tmp_name"],"download/" . $logo_update);

			$query_update_employee = "UPDATE `field_config_hr` SET `pdf_header` = '$pdf_header', `pdf_footer` = '$pdf_footer', `fields` = '$fields', max_section = '$max_section', max_subsection = '$max_subsection', max_thirdsection = '$max_thirdsection', pdf_logo = '$logo_update', `hr_description` = '$hr_description', `document` = CONCAT(document, '$document'), `config_extra_fields` = '$config_extra_fields' WHERE `tab`='$tab_field' AND `form`='$form_name'";

			$result_update_employee = mysqli_query($dbc, $query_update_employee);
		} else {
			move_uploaded_file($_FILES["pdf_logo"]["tmp_name"], "download/" . $_FILES["pdf_logo"]["name"]) ;
			$query_insert_config = "INSERT INTO `field_config_hr` (`pdf_header`, `pdf_footer`, `tab`, `form`, `fields`, `max_section`, `max_subsection`, `max_thirdsection`, `pdf_logo`, `hr_description`, `document`, `config_extra_fields`) VALUES ('$pdf_header', '$pdf_footer', '$tab_field', '$form_name', '$fields', '$max_section', '$max_subsection', '$max_thirdsection', '$pdf_logo', '$hr_description', '$document', '$config_extra_fields')";
			$result_insert_config = mysqli_query($dbc, $query_insert_config);
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

    echo '<script type="text/javascript"> window.location.replace("field_config_hr.php?tab='.$tab_field.'&form='.$form_name.'"); </script>';
}
?>
<script type="text/javascript">
$(document).ready(function() {
	$("#tab_field").change(function() {
        window.location = 'field_config_hr.php?subtab=Forms&tab='+this.value;
	});

	$("#form_name").change(function() {
        var tab = $("#tab_field").val();
        window.location = 'field_config_hr.php?subtab=Forms&tab='+tab+'&form='+this.value;
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
<h1>HR</h1>
<div class="gap-top double-gap-bottom"><a href="hr.php?tab=<?php echo $_GET['tab'];?>" class="btn config-btn">Back to Dashboard</a></div>
<!--<a href="#" class="btn config-btn" onclick="history.go(-1);return false;">Back</a>-->

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
    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_hr WHERE tab='$tab'"));
} else {
    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_hr WHERE tab='$tab' AND form='$form'"));
}
$fields = ','.$get_field_config['fields'].',';
$hr_description = $get_field_config['hr_description'];
$config_extra_fields = explode('**FFM**',$get_field_config['config_extra_fields']);
$document = $get_field_config['document'];
$pdf_header = $get_field_config['pdf_header'];
$pdf_footer = $get_field_config['pdf_footer'];
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
$hr_tabs = get_config($dbc, 'hr_tabs');
?>

    <div class="tab-container">
        <div class="pull-left tab"><span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click this to configure custom tabs for the HR tile."><img src="<?= WEBSITE_URL ?>/img/info.png" width="20"></a></span><a href="field_config_hr.php"><button type="button" class="btn brand-btn mobile-block <?= ($_GET['subtab'] != 'Forms' ? 'active_tab' : '') ?>">Tabs</button></a></div>

        <div class="pull-left tab"><span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click this to configure your settings for HR Forms."><img src="<?= WEBSITE_URL ?>/img/info.png" width="20"></a></span><a href="field_config_hr.php?subtab=Forms"><button type="button" class="btn brand-btn mobile-block <?= ($_GET['subtab'] == 'Forms' ? 'active_tab' : '') ?>">Forms</button></a></div>

        <div class="pull-left tab"><span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click this to configure your settings for HR Manuals."><img src="<?= WEBSITE_URL ?>/img/info.png" width="20"></a></span><a href="field_config_hr_manuals.php"><button type="button" class="btn brand-btn mobile-block">Manuals</button></a></div>
    </div>

    <div class="clearfix"></div>

    <?php if ($_GET['subtab'] == 'Forms') { ?>

    <div class="form-group">
        <label for="fax_number"	class="col-sm-4	control-label">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose your desired tab."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			Tab:
		</label>
        <div class="col-sm-8">
            <select data-placeholder="Choose a Vendor..." id="tab_field" name="tab_field" class="chosen-select-deselect form-control" width="380">
              <option value=""></option>
              <?php
              foreach (explode(',', $hr_tabs) as $hr_tab) {
                echo '<option '.($tab == $hr_tab ? 'selected' : '').' value="'.$hr_tab.'">'.$hr_tab.'</option>';
              } ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="fax_number"	class="col-sm-4	control-label">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose your desired form."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			Form:
		</label>
        <div class="col-sm-8">
            <select data-placeholder="Choose a Vendor..." id="form_name" name="form_name" class="chosen-select-deselect form-control" width="380">
              <option value=""></option>
              <option <?php if ($form == "Employee Information Form") { echo " selected"; } ?> value="Employee Information Form">Employee Information Form</option>
              <option <?php if ($form == "Employee Driver Information Form") { echo " selected"; } ?> value="Employee Driver Information Form">Employee Driver Information Form</option>
              <option <?php if ($form == "Time Off Request") { echo " selected"; } ?> value="Time Off Request">Time Off Request</option>
              <option <?php if ($form == "Confidential Information") { echo " selected"; } ?> value="Confidential Information">Confidential Information</option>
                  <option <?php if ($form == "Work Hours Policy") { echo " selected"; } ?> value="Work Hours Policy">Work Hours Policy</option>
                  <option <?php if ($form == "Direct Deposit Information") { echo " selected"; } ?> value="Direct Deposit Information">Direct Deposit Information</option>
                  <option <?php if ($form == "Employee Substance Abuse Policy") { echo " selected"; } ?> value="Employee Substance Abuse Policy">Employee Substance Abuse Policy</option>
                  <option <?php if ($form == "Employee Right to Refuse Unsafe Work") { echo " selected"; } ?> value="Employee Right to Refuse Unsafe Work">Employee Right to Refuse Unsafe Work</option>
                  <option <?php if ($form == "Shop Yard and Office Orientation") { echo " selected"; } ?> value="Shop Yard and Office Orientation">Shop Yard and Office Orientation</option>
                  <option <?php if ($form == "Copy of Drivers Licence and Safety Tickets") { echo " selected"; } ?> value="Copy of Drivers Licence and Safety Tickets">Copy of Driver's Licence and Safety Tickets</option>
                  <option <?php if ($form == "PPE Requirements") { echo " selected"; } ?> value="PPE Requirements">PPE Requirements</option>
                  <option <?php if ($form == "Verbal Training in Emergency Response Plan") { echo " selected"; } ?> value="Verbal Training in Emergency Response Plan">Verbal Training in Emergency Response Plan</option>
                  <option <?php if ($form == "Eligibility for General Holidays and General Holiday Pay") { echo " selected"; } ?> value="Eligibility for General Holidays and General Holiday Pay">Eligibility for General Holidays and General Holiday Pay</option>
                  <option <?php if ($form == "Maternity Leave and Parental Leave") { echo " selected"; } ?> value="Maternity Leave and Parental Leave">Maternity Leave and Parental Leave</option>
                  <option <?php if ($form == "Employment Verification Letter") { echo " selected"; } ?> value="Employment Verification Letter">Employment Verification Letter</option>
                  <option <?php if ($form == "Background Check Authorization") { echo " selected"; } ?> value="Background Check Authorization">Background Check Authorization</option>
                  <option <?php if ($form == "Disclosure of Outside Clients") { echo " selected"; } ?> value="Disclosure of Outside Clients">Disclosure of Outside Clients</option>
                  <option <?php if ($form == "Employment Agreement") { echo " selected"; } ?> value="Employment Agreement">Employment Agreement</option>
                  <option <?php if ($form == "Independent Contractor Agreement") { echo " selected"; } ?> value="Independent Contractor Agreement">Independent Contractor Agreement</option>
                  <option <?php if ($form == "Letter of Offer") { echo " selected"; } ?> value="Letter of Offer">Letter of Offer</option>
                  <option <?php if ($form == "Employee Non-Disclosure Agreement") { echo " selected"; } ?> value="Employee Non-Disclosure Agreement">Employee Non-Disclosure Agreement</option>
                  <option <?php if ($form == "Employee Self Evaluation") { echo " selected"; } ?> value="Employee Self Evaluation">Employee Self Evaluation</option>
                  <option <?php if ($form == "HR Complaint") { echo " selected"; } ?> value="HR Complaint">HR Complaint</option>
                  <option <?php if ($form == "Exit Interview") { echo " selected"; } ?> value="Exit Interview">Exit Interview</option>
                  <option <?php if ($form == "Employee Expense Reimbursement") { echo " selected"; } ?> value="Employee Expense Reimbursement">Employee Expense Reimbursement</option>
                  <option <?php if ($form == "Absence Report") { echo " selected"; } ?> value="Absence Report">Absence Report</option>
                  <option <?php if ($form == "Employee Accident Report Form") { echo " selected"; } ?> value="Employee Accident Report Form">Employee Accident Report Form</option>
                  <option <?php if ($form == "Trucking Information") { echo " selected"; } ?> value="Trucking Information">Trucking Information</option>
                  <option <?php if ($form == "Contractor Orientation") { echo " selected"; } ?> value="Contractor Orientation">Contractor Orientation</option>
                  <option <?php if ($form == "Contract Welder Inspection Checklist") { echo " selected"; } ?> value="Contract Welder Inspection Checklist">Contract Welder Inspection Checklist</option>
                  <option <?php if ($form == "Contractor Pay Agreement") { echo " selected"; } ?> value="Contractor Pay Agreement">Contractor Pay Agreement</option>
                  <option <?php if ($form == "Employee Holiday Request Form") { echo " selected"; } ?> value="Employee Holiday Request Form">Employee Holiday Request Form</option>
                  <option <?php if ($form == "Employee Coaching Form") { echo " selected"; } ?> value="Employee Coaching Form">Employee Coaching Form</option>
				  <option <?php if ($form == "2016 Alberta Personal Tax Credits Return") { echo " selected"; } ?> value="2016 Alberta Personal Tax Credits Return">2016 Alberta Personal Tax Credits Return TD1AB</option>
				  <option <?php if ($form == "2016 Personal Tax Credits Return") { echo " selected"; } ?> value="2016 Personal Tax Credits Return">2016 Alberta Personal Tax Credits Return TD1</option>
				  <option <?php if ($form == "Driver Abstract Statement of Intent") { echo " selected"; } ?> value="Driver Abstract Statement of Intent">Driver Abstract Statement of Intent</option>
				  <option <?php if ($form == "PERSONAL PROTECTIVE EQUIPMENT POLICY") { echo " selected"; } ?> value="PERSONAL PROTECTIVE EQUIPMENT POLICY">PERSONAL PROTECTIVE EQUIPMENT POLICY</option>
				  <option <?php if ($form == "DRIVER CONSENT FORM") { echo " selected"; } ?> value="DRIVER CONSENT FORM">DRIVER CONSENT FORM</option>
				  <option <?php if ($form == "Policy and Procedure Notice of Understanding and Intent") { echo " selected"; } ?> value="Policy and Procedure Notice of Understanding and Intent">Policy and Procedure Notice of Understanding and Intent</option>
				  <option <?php if ($form == "Employee Personal and Emergency Information") { echo " selected"; } ?> value="Employee Personal and Emergency Information">Employee Personal and Emergency Information</option>
				  <option <?php if ($form == "Employment Agreement Evergreen") { echo " selected"; } ?> value="Employment Agreement Evergreen">Employment Agreement Evergreen</option>
          <option <?php if ($form == "Police Information Check") { echo " selected"; } ?> value="Police Information Check">Police Information Check</option>
                  <?php
                  $query = mysqli_query ($dbc, "SELECT * FROM `user_forms` WHERE CONCAT(',',`assigned_tile`,',') LIKE ',hr,'");;
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
                    <span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click this to select the desired boxes for the Tab and Form selected."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field" >
                        Choose Fields for HR<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_field" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">

                    <table border='2' cellpadding='10' class='table'>
                        <tr>
							<td>
                                <input type="checkbox" <?php if (strpos($fields, ','."First Name".',') !== FALSE) { echo " checked"; } ?> value="First Name" name="fields[]">&nbsp;&nbsp;First Name
                            </td>
							<td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Last Name".',') !== FALSE) { echo " checked"; } ?> value="Last Name" name="fields[]">&nbsp;&nbsp;Last Name
                            </td>
							<td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Birth Date".',') !== FALSE) { echo " checked"; } ?> value="Birth Date" name="fields[]">&nbsp;&nbsp;Birth Date
                            </td>
							<td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Employee Number".',') !== FALSE) { echo " checked"; } ?> value="Employee Number" name="fields[]">&nbsp;&nbsp;Employee Number
                            </td>
							<td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Address including Postal Code".',') !== FALSE) { echo " checked"; } ?> value="Address including Postal Code" name="fields[]">&nbsp;&nbsp;Address including Postal Code
                            </td>
                            <td>
                                <input disabled type="checkbox" <?php if (strpos($fields, ','."Topic (Sub Tab)".',') !== FALSE) { echo " checked"; } ?> value="Topic (Sub Tab)" name="fields[]">&nbsp;&nbsp;Topic (Sub Tab)
                            </td>
                            <td>
                                <input disabled type="checkbox" <?php if (strpos($fields, ','."Section #".',') !== FALSE) { echo " checked"; } ?> value="Section #" name="fields[]">&nbsp;&nbsp;Section #
                            </td>
                            <td>
                                <input disabled type="checkbox" <?php if (strpos($fields, ','."Section Heading".',') !== FALSE) { echo " checked"; } ?> value="Section Heading" name="fields[]">&nbsp;&nbsp;Section Heading
                            </td>
                            <td>
                                <input disabled type="checkbox" <?php if (strpos($fields, ','."Sub Section #".',') !== FALSE) { echo " checked"; } ?> value="Sub Section #" name="fields[]">&nbsp;&nbsp;Sub Section #
                            </td>
                            <td>
                                <input disabled type="checkbox" <?php if (strpos($fields, ','."Sub Section Heading".',') !== FALSE) { echo " checked"; } ?> value="Sub Section Heading" name="fields[]">&nbsp;&nbsp;Sub Section Heading
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Third Tier Section #".',') !== FALSE) { echo " checked"; } ?> value="Third Tier Section #" name="fields[]">&nbsp;&nbsp;Third Tier Section #
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Third Tier Heading".',') !== FALSE) { echo " checked"; } ?> value="Third Tier Heading" name="fields[]">&nbsp;&nbsp;Third Tier Heading
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Detail".',') !== FALSE) { echo " checked"; } ?> value="Detail" name="fields[]">&nbsp;&nbsp;Detail
                            </td>

                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Document".',') !== FALSE) { echo " checked"; } ?> value="Document" name="fields[]">&nbsp;&nbsp;Document
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Link".',') !== FALSE) { echo " checked"; } ?> value="Link" name="fields[]">&nbsp;&nbsp;Link
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Videos".',') !== FALSE) { echo " checked"; } ?> value="Videos" name="fields[]">&nbsp;&nbsp;Videos
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Signature box".',') !== FALSE) { echo " checked"; } ?> value="Signature box" name="fields[]">&nbsp;&nbsp;Signature box
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Comments".',') !== FALSE) { echo " checked"; } ?> value="Comments" name="fields[]">&nbsp;&nbsp;Comments
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Staff".',') !== FALSE) { echo " checked"; } ?> value="Staff" name="fields[]">&nbsp;&nbsp;Staff
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Review Deadline".',') !== FALSE) { echo " checked"; } ?> value="Review Deadline" name="fields[]">&nbsp;&nbsp;Review Deadline
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Status".',') !== FALSE) { echo " checked"; } ?> value="Status" name="fields[]">&nbsp;&nbsp;Status
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Configure Email".',') !== FALSE) { echo " checked"; } ?> value="Configure Email" name="fields[]">&nbsp;&nbsp;Configure Email
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Form".',') !== FALSE) { echo " checked"; } ?> value="Form" name="fields[]">&nbsp;&nbsp;Form
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Permissions by Position".',') !== FALSE) { echo " checked"; } ?> value="Permissions by Position" name="fields[]">&nbsp;&nbsp;Permissions by Position
                            </td>
							<td></td>
							<td></td>
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
                    <span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click this to choose the Max Selections for the Tab and Form selected."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_max" >
                        Max Selection<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_max" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">

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

        <?php if ($form == "Employee Information Form") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_eif" >
                        Employee Information Form<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_eif" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('employee_information_form/field_config_employee_information_form.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

		<?php if ($form == "2016 Alberta Personal Tax Credits Return") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_eif" >
                        TD1AB<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_eif" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('2016_alberta_personal/field_config_2016_alberta_personal.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

		<?php if ($form == "2016 Personal Tax Credits Return") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_eif" >
                        TD1<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_eif" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('tax_credit_1/field_config_2016_alberta_personal.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

		

		

		<?php if ($form == "PERSONAL PROTECTIVE EQUIPMENT POLICY") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_eif" >
                        PERSONAL PROTECTIVE EQUIPMENT POLICY<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_eif" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('personal_protective_equipment_policy/field_config_personal_protective_equipment_policy.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Employee Driver Information Form") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_edi" >
                        Employee Driver Information Form<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_edi" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('employee_driver_information_form/field_config_employee_driver_information_form.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Time Off Request") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_tor" >
                        Time Off Request<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_tor" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('time_off_request/field_config_time_off_request.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Confidential Information") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Confidential Information<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('confidential_information/field_config_confidential_information.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Work Hours Policy") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Work Hours Policy<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('work_hours_policy/field_config_work_hours_policy.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Direct Deposit Information") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Direct Deposit Information<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('direct_deposit_information/field_config_direct_deposit_information.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
		<?php if ($form == "Employee Personal and Emergency Information") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Employee Personal and Emergency Information<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('employee_personal_and_emergency_information/field_config_employee_personal_and_emergency_information.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Employee Substance Abuse Policy") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Employee Substance Abuse Policy<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('employee_substance_abuse_policy/field_config_employee_substance_abuse_policy.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Employee Right to Refuse Unsafe Work") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Employee Right to Refuse Unsafe Work<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('employee_right_to_refuse_unsafe_work/field_config_employee_right_to_refuse_unsafe_work.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
		<?php if ($form == "Policy and Procedure Notice of Understanding and Intent") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Policy and Procedure Notice of Understanding and Intent<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('policy_and_procedure_notice_of_understanding_and_intent/field_config_policy_and_procedure_notice_of_understanding_and_intent.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Shop Yard and Office Orientation") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Shop Yard and Office Orientation<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('employee_shop_yard_office_orientation/field_config_employee_shop_yard_office_orientation.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Copy of Drivers Licence and Safety Tickets") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Copy of Driver's Licence and Safety Tickets<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('copy_of_drivers_licence_safety_tickets/field_config_copy_of_drivers_licence_safety_tickets.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "PPE Requirements") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        PPE Requirements<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('ppe_requirements/field_config_ppe_requirements.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Verbal Training in Emergency Response Plan") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Verbal Training in Emergency Response Plan<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('verbal_training_in_emergency_response_plan/field_config_verbal_training_in_emergency_response_plan.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Eligibility for General Holidays and General Holiday Pay") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Eligibility for General Holidays and General Holiday Pay<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('eligibility_for_general_holidays_general_holiday_pay/field_config_eligibility_for_general_holidays_general_holiday_pay.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Maternity Leave and Parental Leave") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Maternity Leave and Parental Leave<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('maternity_leave_parental_leave/field_config_maternity_leave_parental_leave.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Employment Verification Letter") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Employment Verification Letter<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('employment_verification_letter/field_config_employment_verification_letter.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Background Check Authorization") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Background Check Authorization<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('background_check_authorization/field_config_background_check_authorization.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Disclosure of Outside Clients") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Disclosure of Outside Clients<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('disclosure_of_outside_clients/field_config_disclosure_of_outside_clients.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Employment Agreement") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Employment Agreement<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('employment_agreement/field_config_employment_agreement.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Independent Contractor Agreement") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Independent Contractor Agreement<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('independent_contractor_agreement/field_config_independent_contractor_agreement.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Letter of Offer") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Letter of Offer<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('letter_of_offer/field_config_letter_of_offer.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Employee Non-Disclosure Agreement") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Employee Non-Disclosure Agreement<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('employee_nondisclosure_agreement/field_config_employee_nondisclosure_agreement.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Employee Self Evaluation") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Employee Self Evaluation<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('employee_self_evaluation/field_config_employee_self_evaluation.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "HR Complaint") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        HR Complaint<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('hr_complaint/field_config_hr_complaint.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Exit Interview") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Exit Interview<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('exit_interview/field_config_exit_interview.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Employee Expense Reimbursement") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Employee Expense Reimbursement<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('employee_expense_reimbursement/field_config_employee_expense_reimbursement.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Absence Report") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Absence Report<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('absence_report/field_config_absence_report.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Employee Accident Report Form") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Employee Accident Report Form<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('employee_accident_report_form/field_config_employee_accident_report_form.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Trucking Information") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Trucking Information<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('trucking_information/field_config_trucking_information.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Contractor Orientation") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Contractor Orientation<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('contractor_orientation/field_config_contractor_orientation.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Contract Welder Inspection Checklist") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Contract Welder Inspection Checklist<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('contract_welder_inspection_checklist/field_config_contract_welder_inspection_checklist.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Contractor Pay Agreement") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Contractor Pay Agreement<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('contractor_pay_agreement/field_config_contractor_pay_agreement.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Employee Holiday Request Form") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Employee Holiday Request Form<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('employee_holiday_request_form/field_config_employee_holiday_request_form.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Employee Coaching Form") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Employee Coaching Form<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('employee_coaching_form/field_config_employee_coaching_form.php'); ?>
                 </div>
            </div>
        </div>
    <?php } ?>
    <?php if ($form == "Police Information Check") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ci" >
                        Police Information Check<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_ci" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    <?php include ('police_information_check/field_config_police_information_check.php'); ?>
                 </div>
            </div>
        </div>
    <?php } ?>

    <?php } else { ?>
    <br />
    <div class="panel-group" id="accordion2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_tabs" >
                        Configure Tabs<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_tabs" class="panel-collapse collapse">
                <div class="panel-body" id="no-more-tables">
                    Add tabs separated by a comma in the order you want them in the HR tile:
                    <div class="clearfix"></div>
                    <input type="text" name="hr_tabs" class="form-control" value="<?= $hr_tabs ?>">
                 </div>
            </div>
        </div>
    <?php } ?>
    </div>

<?php
    $category = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT category FROM manuals WHERE deleted=0 AND manual_type='hr' LIMIT 1"));
    $manual_category = $category['category'];
    if($manual_category == '') {
       $manual_category = 0;
    }
?>
<div class="form-group">
    <div class="col-sm-6">
        <span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Clicking this will discard your HR settings."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
		<a href="hr.php?tab=<?php echo $tab; ?>" class="btn config-btn btn-lg">Back</a>
		<!--<a href="#" class="btn brand-btn btn-lg pull-right" onclick="history.go(-1);return false;">Back</a>-->
	</div>
	<div class="col-sm-6">
        <button	type="submit" name="<?= ($_GET['subtab'] == 'Forms' ? 'submit' : 'submit_tabs') ?>"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
		<span class="popover-examples list-inline pull-right" style="margin:15px 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click this to finalize your HR settings."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
    </div>
</div>


</form>
</div>
</div>

<?php include ('../footer.php'); ?>