<?php
/*
Add Vendor
*/
include_once('../include.php');
include_once('../tcpdf/tcpdf.php');
require_once('../phpsign/signature-to-image.php');

if (isset($_POST['add_manual'])) {

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
        $fields = $fields.',Topic (Sub Tab),Section #,Section Heading,Sub Section #,Sub Section Heading,';
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
	
    include ('save_form.php');
}

if((!empty($_GET['safetyid'])) && (!empty($_GET['action'])) && ($_GET['action'] == 'delete')) {
    $safetyid = $_GET['safetyid'];
    $category = get_safety($dbc, $safetyid, 'category');
    $tab = get_safety($dbc, $safetyid, 'tab');

    $query = mysqli_query($dbc,"DELETE FROM safety WHERE safetyid='$safetyid'");
    echo '<script type="text/javascript"> window.location.replace("index.php?tab='.$tab.'&category='.$category.'"); </script>';
}

if((empty($_GET['safetyid'])) && (!empty($_GET['action'])) && ($_GET['action'] == 'delete')) {
    $uploadid = $_GET['uploadid'];
    $query = mysqli_query($dbc,"DELETE FROM safety_upload WHERE uploadid='$uploadid'");

    $type = $_GET['type'];
    $safetyid = $_GET['safetyid'];
    echo '<script type="text/javascript"> window.location.replace("index.php?safetyid='.$safetyid.'&type='.$type.'"); </script>';
}

if (isset($_POST['view_manual'])) {
    $comment = filter_var(htmlentities($_POST['comment']),FILTER_SANITIZE_STRING);

    $safetyid = $_POST['safetyid'];

    $type = $_POST['type'];

    if($comment != '') {
        if($type == 'policy_procedures') {
            $column = 'manual_policy_pro_email';
        }
        if($type == 'operations_manual') {
            $column = 'manual_operations_email';
        }
        if($type == 'emp_handbook') {
            $column = 'manual_emp_handbook_email';
        }
        if($type == 'guide') {
            $column = 'manual_guide_email';
        }
        if($type == 'safety') {
            $column = 'manual_safety_email';
        }

        $get_manual =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM	manuals WHERE	safetyid='$safetyid'"));

        //Mail
        $to = get_config($dbc, $column);
        $user = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);
        $subject = 'Manual Read by '.$user;

        $message = "Topic (Sub Tab) : ".$get_manual['category'].'<br>';
        $message .= "Section Heading : ".$get_manual['heading'].'<br>';
        $message .= "Sub Section Heading : ".$get_manual['sub_heading'].'<br>';
        $message .= "Comment<br/><br/>".$_POST['comment'];
        send_email('', $to, '', '', $subject, $message, '');

        //Mail
    }

    $staffid = $_SESSION['contactid'];
    $today_date = date('Y-m-d H:i:s');
	// Insert a row if it isn't already there
	$query_insert_row = "INSERT INTO `safety_staff` (`safetyid`, `staffid`) SELECT '$safetyid', '$staffid' FROM (SELECT COUNT(*) rows FROM `safety_staff` WHERE `safetyid`='$safetyid' AND `staffid`='$staffid') LOGTABLE WHERE rows=0";
	mysqli_query($dbc, $query_insert_row);
    $query_update_ticket = "UPDATE `safety_staff` SET `done` = '1', `today_date` = '$today_date' WHERE `safetyid` = '$safetyid' AND staffid='$staffid' AND done=0";
    $result_update_ticket = mysqli_query($dbc, $query_update_ticket);

	$return_url = 'index.php?tab='.($get_manual['tab'] == 'manual' ? 'manuals' : $get_manual['tab']);
	if(!empty($_GET['return_url'])) {
		$return_url = urldecode($_GET['return_url']);
	}
    echo '<script type="text/javascript"> window.location.replace("'.$return_url.'"); </script>';
}

if (isset($_POST['field_level_hazard'])) {
    $field_level_hazard = $_POST['field_level_hazard'];
    $safetyid = $_POST['safetyid'];

    $form_name_save = get_safety($dbc, $safetyid, 'form');

    if($form_name_save == 'Field Level Hazard Assessment') {
        include ('field_level_hazard_assessment/save_field_level_hazard_assessment.php');
    }
    if($form_name_save == 'Hydrera Site Specific Pre Job Safety Meeting Hazard Assessment') {
        include ('hydrera_site_specificpre_job/save_hydrera_site_specificpre_job.php');
    }

    if($form_name_save == 'Weekly Safety Meeting') {
        include ('weekly_safety_meeting/save_weekly_safety_meeting.php');
    }

    if($form_name_save == 'Tailgate Safety Meeting') {
        include ('tailgate_safety_meeting/save_tailgate_safety_meeting.php');
    }

    if($form_name_save == 'Toolbox Safety Meeting') {
        include ('toolbox_safety_meeting/save_toolbox_safety_meeting.php');
    }

    if($form_name_save == 'Daily Equipment Inspection Checklist') {
        include ('daily_equipment_inspection_checklist/save_daily_equipment_inspection_checklist.php');
    }

    if($form_name_save == 'AVS Hazard Identification') {
        include ('avs_hazard_identification/save_avs_hazard_identification.php');
    }

    if($form_name_save == 'AVS Near Miss') {
        include ('near_miss_report/save_near_miss_report.php');
    }

    if($form_name_save == 'Incident Investigation Report') {
        include ('incident_investigation_report/save_incident_investigation_report.php');
    }
    if($form_name_save == 'Follow Up Incident Report') {
        include ('follow_up_incident_report/save_follow_up_incident_report.php');
    }
    if($form_name_save == 'Pre Job Hazard Assessment') {
        include ('pre_job_hazard_assessment/save_pre_job_hazard_assessment.php');
    }
    if($form_name_save == 'Monthly Site Safety Inspections') {
        include ('monthly_site_safety_inspection/save_monthly_site_safety_inspection.php');
    }
    if($form_name_save == 'Monthly Office Safety Inspections') {
        include ('monthly_office_safety_inspection/save_monthly_office_safety_inspection.php');
    }
    if($form_name_save == 'Monthly Health and Safety Summary') {
        include ('monthly_health_and_safety_summary/save_monthly_health_and_safety_summary.php');
    }
    if($form_name_save == 'Trailer Inspection Checklist') {
        include ('trailer_inspection_checklist/save_trailer_inspection_checklist.php');
    }
    if($form_name_save == 'Employee Misconduct Form') {
        include ('employee_misconduct_form/save_employee_misconduct_form.php');
    }
    if($form_name_save == 'Site Inspection Hazard Assessment') {
        include ('site_inspection_hazard_assessment/save_site_inspection_hazard_assessment.php');
    }

    if($form_name_save == 'Weekly Planned Inspection Checklist') {
        include ('weekly_planned_inspection_checklist/save_weekly_planned_inspection_checklist.php');
    }
    if($form_name_save == 'Equipment Inspection Checklist') {
        include ('equipment_inspection_checklist/save_equipment_inspection_checklist.php');
    }
    if($form_name_save == 'Employee Equipment Training Record') {
        include ('employee_equipment_training_record/save_employee_equipment_training_record.php');
    }
    if($form_name_save == 'Vehicle Inspection Checklist') {
        include ('vehicle_inspection_checklist/save_vehicle_inspection_checklist.php');
    }
    if($form_name_save == 'Safety Meeting Minutes') {
        include ('safety_meeting_minutes/save_safety_meeting_minutes.php');
    }
    if($form_name_save == 'Vehicle Damage Report') {
        include ('vehicle_damage_report/save_vehicle_damage_report.php');
    }
    if($form_name_save == 'Fall Protection Plan') {
        include ('fall_protection_plan/save_fall_protection_plan.php');
    }
    if($form_name_save == 'Spill Incident Report') {
        include ('spill_incident_report/save_spill_incident_report.php');
    }
    if($form_name_save == 'General Site Safety Inspection') {
        include ('general_site_safety_inspection/save_general_site_safety_inspection.php');
    }
    if($form_name_save == 'Confined Space Entry Permit') {
        include ('confined_space_entry_permit/save_confined_space_entry_permit.php');
    }
    if($form_name_save == 'Lanyards Inspection Checklist Log') {
        include ('lanyards_inspection_checklist_log/save_lanyards_inspection_checklist_log.php');
    }
    if($form_name_save == 'On The Job Training Record') {
        include ('on_the_job_training_record/save_on_the_job_training_record.php');
    }
    if($form_name_save == 'General Office Safety Inspection') {
        include ('general_office_safety_inspection/save_general_office_safety_inspection.php');
    }
    if($form_name_save == 'Full Body Harness Inspection Checklist Log') {
        include ('full_body_harness_inspection_checklist_log/save_full_body_harness_inspection_checklist_log.php');
    }
    if($form_name_save == 'Confined Space Pre Entry Checklist') {
        include ('confined_space_entry_pre_entry_checklist/save_confined_space_entry_pre_entry_checklist.php');
    }
    if($form_name_save == 'Confined Space Entry Log') {
        include ('confined_space_entry_log/save_confined_space_entry_log.php');
    }
    if($form_name_save == 'Emergency Response Transportation Plan') {
        include ('emergency_response_transportation_plan/save_emergency_response_transportation_plan.php');
    }

    if($form_name_save == 'Hazard Id Report') {
        include ('hazard_id_report/save_hazard_id_report.php');
    }
    if($form_name_save == 'Dangerous Goods Shipping Document') {
        include ('dangerous_goods_shipping_document/save_dangerous_goods_shipping_document.php');
    }
    if($form_name_save == 'Safe Work Permit') {
        include ('safe_work_permit/save_safe_work_permit.php');
    }
    if($form_name_save == 'Journey Management - Trip Tracking Form') {
        include ('journey_management_trip_tracking/save_journey_management_trip_tracking.php');
    }
    if($form_name_save == 'Motor Vehicle Accident Form') {
        include ('incident_reports/save_incident_reports.php');
    }
}
?>
<script type="text/javascript">
$(document).ready(function() {

	$("#tab_field").change(function() {
        window.location = '?safetyid=<?= $_GET['safetyid'] ?>&action=edit&tab='+this.value+'&form='+$('#form_name').val();
	});

	$("#form_name").change(function() {
        var tab = $("#tab_field").val();
        window.location = '?safetyid=<?= $_GET['safetyid'] ?>&action=edit&tab='+tab+'&form='+this.value;
	});

    $("#category").change(function() {
        if($("#category option:selected").text() == 'New Topic (Sub Tab)') {
                $( "#new_category" ).show();
        } else {
            $( "#new_category" ).hide();
        }
    });

    $("#heading").change(function() {
        if($("#heading option:selected").text() == 'New Heading') {
                $( "#new_heading" ).show();
        } else {
            $( "#new_heading" ).hide();
        }
    });

    $("#heading_number").change(function() {
        if($("#heading_number option:selected").text() == 'New Heading Number') {
                $("#new_heading_number").show();
        } else {
            $( "#new_heading_number" ).hide();
        }
    });

    $('#add_row_doc').on( 'click', function () {
        var clone = $('.additional_doc').clone();
        clone.find('.form-control').val('');
        clone.removeClass("additional_doc");
        $('#add_here_new_doc').append(clone);
        return false;
    });

    $('#add_row_link').on( 'click', function () {
        var clone = $('.additional_link').clone();
        clone.find('.form-control').val('');
        clone.removeClass("additional_link");
        $('#add_here_new_link').append(clone);
        return false;
    });

    $('#add_row_videos').on( 'click', function () {
        var clone = $('.additional_videos').clone();
        clone.find('.form-control').val('');
        clone.removeClass("additional_videos");
        $('#add_here_new_videos').append(clone);
        return false;
    });

    $(".selecctall").change(function(){
      $(".field_config input:checkbox").prop('checked', $(this).prop("checked"));
    });
});
function selectSection(sel) {
    var category = $('#category').val();
	var heading_number = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "manual_ajax_all.php?fill=section&heading_number="+heading_number+"&category="+category,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#heading").val(response);
			$("#heading").trigger("change.select2");
		}
	});
	if(heading_number > 0) {
		var current_sub = $('#sub_heading_value').val();
		var max_sub = $('#max_subsection').val();
		$.ajax({    //create an ajax request to load_page.php
			type: "POST",
			url: "manual_ajax_all.php?fill=sub_heading_number",
			data: { heading_number: heading_number, sub_heading: current_sub, category: $('#tab_field').val(), max_section: max_sub },
			dataType: "html",   //expect html to be returned
			success: function(response){
				$("#sub_heading_number").empty().html(response).trigger("change.select2");
			}
		});
	}
	else {
		$("#sub_heading_number").empty().trigger("change.select2");
	}
}
function selectSubSection(sel) {
    var category = $('#category').val();
	var sub_heading_number = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "manual_ajax_all.php?fill=subsection&sub_heading_number="+sub_heading_number+"&category="+category,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#sub_heading").val(response);
		}
	});
	if(sub_heading_number > 0) {
		var current_sub = $('#third_heading_number').val();
		var max_sub = $('#max_subsection').val();
		$.ajax({    //create an ajax request to load_page.php
			type: "POST",
			url: "manual_ajax_all.php?fill=third_heading_number",
			data: { sub_heading_number: sub_heading_number, third_heading: current_sub, category: $('#tab_field').val(), max_section: max_sub },
			dataType: "html",   //expect html to be returned
			success: function(response){
				$("#third_heading_number").empty().html(response).trigger("change.select2");
			}
		});
	}
	else {
		$("#third_heading_number").empty().trigger("change.select2");
	}
}
</script>
<script src="<?php echo WEBSITE_URL; ?>/js/jquery.simplecolorpicker.js"></script>
			<link rel="stylesheet" href="<?php echo WEBSITE_URL; ?>/css/jquery.simplecolorpicker.css">
<?php 
checkAuthorised('safety');
if(!empty($_GET['safetyid'])) {
    $user_form_id = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety WHERE safetyid='".$_GET['safetyid']."'"))['user_form_id'];
    if($user_form_id > 0) {
        $user_form_layout = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM `user_forms` WHERE `form_id` = '$user_form_id'"))['form_layout'];
        $user_form_layout = !empty($user_form_layout) ? $user_form_layout : 'Accordions';   
    }
}
?>
<div class="scale-to-fill has-main-screen" <?= $user_form_layout == 'Sidebar' ? 'style="padding: 0; margin: 0;"' : '' ?>>
    <form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="main-screen form-horizontal pad-horizontal" role="form" <?= $user_form_layout == 'Sidebar' ? 'style="padding: 0; margin: 0; border-top: 1px solid #E1E1E1;"' : '' ?>>

    <?php
        $category = '';
        $heading = '';
        $sub_heading = '';
        $description = '';
        $assign_staff = '';
        $assign_sites = '';
        $assign_work_orders = '';
        $deadline = '';
        $action = '';
		$marker = '';
        $heading_number = '';
        $sub_heading_number = '';
        $third_heading_number = '';
        $third_heading = '';
        $form_name = '';

        $tab = $_GET['tab'];
        $form = $_GET['form'];
        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$form'"));
		$pdf_logo = $get_field_config['pdf_logo'];
		$pdf_header = $get_field_config['pdf_header'];
		$pdf_footer = $get_field_config['pdf_footer'];
        $value_config = empty($get_field_config['fields']) ? ',Staff,Configure Email,Topic (Sub Tab),field1,field2,field3,field4,field5,field6,field7,field8,field9,field10,field11,field12,field13,field14,field15,field16,field17,field18,field19,field20,field21,field22,field23,field24,field25,field26,field27,field28,field29,field30,field31,field32,field33,field34,field35,field36,field37,field38,field39,field40,field41,field42,field43,field44,field45,field46,field47,field48,field49,field50,field51,field52,field53,field54,field55,field56,field57,field58,field59,field60,field61,field62,field63,field64,field65,field66,field67,field68,field69,field70,Section #,Section Heading,Sub Section #,Sub Section Heading,' : ','.$get_field_config['fields'].',';
		$sections = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT MAX(`heading_number`) + 5 `headings`, MAX(CONVERT(REPLACE(`sub_heading_number`,CONCAT(`heading_number`,'.'),''),UNSIGNED INTEGER)) + 5 `sub_headings`, MAX(CONVERT(REPLACE(`third_heading_number`,CONCAT(`sub_heading_number`,'.'),''),UNSIGNED INTEGER)) + 5 `third_headings` FROM `safety`"));
        $max_section = $sections['headings'];
        $max_subsection = $sections['sub_headings'];
        $max_thirdsection = $sections['third_headings'];
        $user_form_id = mysqli_fetch_array(mysqli_query($dbc, "SELECT `form_id` FROM `user_forms` WHERE `form_id` = '$form'"))['form_id'];

		if($form == 'Manual' || $form == '') {
			$value_config = ','.mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form IN ('Manual','')"))['fields'].',';
			if(',,' == $value_config) {
				$value_config = ',Topic (Sub Tab),Section #,Section Heading,Sub Section #,Sub Section Heading,Detail,Document,Staff,';
			}
		} else if(',,' == $value_config) {
			$value_config = ',Topic (Sub Tab),Section #,Section Heading,Sub Section #,Sub Section Heading,Staff,';
		}
        if(!empty($_GET['safetyid'])) {

            $safetyid = $_GET['safetyid'];
            $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety WHERE safetyid='$safetyid'"));

            $tab = $get_contact['tab'];
            $form = $get_contact['form'];
            $heading_number = $get_contact['heading_number'];
            $sub_heading_number = $get_contact['sub_heading_number'];
            $category = $get_contact['category'];
            $heading = $get_contact['heading'];
            $sub_heading = $get_contact['sub_heading'];
            $description = $get_contact['description'];
			$marker = $get_contact['marker'];
            $assign_staff = $get_contact['assign_staff'];
			$assign_sites = $get_contact['assign_sites'];
			$assign_work_orders = $get_contact['assign_work_orders'];
            $deadline = $get_contact['deadline'];
			$email_subject = $get_contact['email_subject'];
			$email_message = $get_contact['email_message'];
            $third_heading_number = $get_contact['third_heading_number'];
            $third_heading = $get_contact['third_heading'];
            $form_name = $get_contact['form_name'];
            $action = $_GET['action'];

            $user_form_id = $get_contact['user_form_id'];

            if ($user_form_id > 0) {
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$user_form_id'"));
            } else {
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND (form='$form' OR ('$form' IN ('','Manual') AND `form` IN ('','Manual')))"));
            }
            $value_config = ','.$get_field_config['fields'].',';
			
			if($value_config == ',,') {
				$value_config = ',Topic (Sub Tab),Section #,Section Heading,Sub Section #,Sub Section Heading,Detail,Document,Staff,';
			}

			$fields = $value_config; ?>
        <input type="hidden" id="safetyid" name="safetyid" value="<?php echo $safetyid ?>" />
        <?php   }
        ?>
        <input type="hidden" id="type" name="type" value="<?php echo $type; ?>" />
        <input type="hidden" id="max_subsection" name="max_subsection" value="<?php echo $max_subsection ?>" />
        <input type="hidden" id="sub_heading_value" name="sub_heading_value" value="<?php echo $sub_heading_number ?>" />
        <input type="hidden" id="third_heading_value" name="third_heading_value" value="<?php echo $third_heading_number ?>" />

		<h2><?= $get_contact['category'] != '' ? '<b>'.$get_contact['category'].'</b><br />' : '' ?>
		<?= $get_contact['heading_number'].' '.$get_contact['heading'].'<br />' ?>
		<?= $get_contact['sub_heading_number'] != '' ? $get_contact['sub_heading_number'].' '.$get_contact['sub_heading'].'<br />' : '' ?>
		<?= $get_contact['third_heading_number'] != '' ? $get_contact['third_heading_number'].' '.$get_contact['third_heading'].'<br />' : '' ?></h2>
        <?php
            $category_safety = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT category FROM safety WHERE deleted=0 AND tab='$tab' LIMIT 1"));
            $manual_category = $category_safety['category'];
            if($manual_category == '') {
               $manual_category = 0;
            }
        ?>

        <?php if($action != 'view') { ?>
        <div class="form-group">
            <label for="fax_number"	class="col-sm-4	control-label">Tab:</label>
            <div class="col-sm-8">
                <select data-placeholder="Select Tab..." id="tab_field" name="tab_field" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <option <?php if ($tab == "Toolbox") { echo " selected"; } ?> value="Toolbox">Toolbox</option>
                  <option <?php if ($tab == "Tailgate") { echo " selected"; } ?> value="Tailgate">Tailgate</option>
                  <option <?php if ($tab == "Form") { echo " selected"; } ?> value="Form">Form</option>
                  <option <?php if ($tab == "Manual") { echo " selected"; } ?> value="Manual">Manual</option>
                </select>
            </div>
        </div>

        <div class="panel-group block-panels" id="accordion2">
        <div class="form-group">
            <label for="fax_number"	class="col-sm-4	control-label">Form:</label>
            <div class="col-sm-8">
                <select data-placeholder="Select Form..." id="form_name" name="form_name" class="chosen-select-deselect form-control" width="380">
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
                  $query = mysqli_query ($dbc, "SELECT * FROM `user_forms` WHERE CONCAT(',',`assigned_tile`,',') LIKE '%,safety,%'");;
                  while ($row = mysqli_fetch_array($query)) { ?>
                    <option <?php if ($user_form_id == $row['form_id']) { echo " selected" ; } ?> value="<?php echo $row['form_id']; ?>"><?php echo $row['name']; ?></option>
                  <?php }
                  $query = "SELECT * FROM `field_config_incident_report` WHERE `row_type` = ''";
                  $result = mysqli_fetch_array(mysqli_query($dbc, $query))['incident_types'];
                  $incident_reports = explode(',',$result);
                  foreach ($incident_reports as $incident_report) { ?>
                    <option <?php if ($form == $incident_report) { echo " selected" ; } ?> value="<?php echo $incident_report; ?>"><?php echo $incident_report; ?></option>
                  <?php } ?>
				<option <?php if ($form == "Manual" || $form == '') { echo " selected"; } ?> value="Manual">Manual</option>
                </select>
            </div>
        </div>
        <?php } ?>

        <?php if($action == 'view') {
        ?>
            <?php if($user_form_layout == 'Sidebar') {
                include('user_forms_sidebar.php'); ?>
                <div id="user_form_div_safety_0" class="tab-section">
                <h4>General Information</h4>
            <?php } else { ?>
    			<div class="panel-group block-panels" id="accordion2">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_geninfor" >General Information<span class="glyphicon glyphicon-minus"></span></a>
                        </h4>
                    </div>

                    <div id="collapse_geninfor" class="panel-collapse collapse in">
                        <div class="panel-body">
            <?php } ?>
                        <?php include ('manual_basic_field.php'); ?>

            <?php if (strpos($value_config, ','."Detail".',') !== FALSE || $form == '') { ?>
            <div class="form-group">
                <div class="col-sm-12">
                    <?php $fix = ' ';
					$offending = '&lt;p&gt;&amp;nbsp;&lt;/p&gt;';
						while (strpos($description,$offending.$offending.$offending.$offending) !== FALSE) {
						  $description = str_replace($offending.$offending.$offending.$offending,$fix,$description);
						}
					$description = html_entity_decode($description);
					echo $description; ?>
                </div>
            </div>
            <?php } ?>

			<ul>
            <?php if (strpos($value_config, ','."Document".',') !== FALSE) { ?>
                <?php include ('manual_document_field.php'); ?>
            <?php } ?>

            <?php if (strpos($value_config, ','."Link".',') !== FALSE) { ?>
                <?php include ('manual_link_field.php'); ?>
            <?php } ?>

            <?php if (strpos($value_config, ','."Videos".',') !== FALSE) { ?>
                <?php include ('manual_video_field.php'); ?>
            <?php } ?>
			</ul>
			
            <?php if (strpos($value_config, ','."Comments".',') !== FALSE) { ?>
              <div class="form-group">
                <label for="first_name[]" class="col-sm-4 control-label">Comments:</label>
                <div class="col-sm-8">
                  <textarea name="comment" rows="5" cols="50" class="form-control"></textarea>
                </div>
              </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Signature box".',') !== FALSE) { ?>
              <div class="form-group">
                <label for="first_name[]" class="col-sm-4 control-label">Signature:</label>
                <div class="col-sm-8">
                  <?php include ('../phpsign/sign.php'); ?>
                </div>
              </div>
            <?php } ?>

            <?php if($user_form_layout == 'Sidebar') { ?>
    			</div>
                <hr>
            <?php } else { ?>
                </div>
                </div>
                </div>
            <?php } ?>

            <?php if ((($tab == 'Toolbox' || $tab == 'Tailgate')) && empty($_GET['formid'])) { ?>
                <?php include ('safety_attendance.php'); ?>
            <?php } ?>

            <?php if ($user_form_id > 0) {
                include ('user_forms.php');
            } else {

                if ($form == 'Field Level Hazard Assessment') { ?>
                    <?php include ('field_level_hazard_assessment/field_level_hazard_assessment.php'); ?>
                <?php } ?>

                <?php if ($form == 'Hydrera Site Specific Pre Job Safety Meeting Hazard Assessment') { ?>
                    <?php include ('hydrera_site_specificpre_job/hydrera_site_specificpre_job.php'); ?>
                <?php } ?>

                <?php if ($form == 'Weekly Safety Meeting') { ?>
                    <?php include ('weekly_safety_meeting/weekly_safety_meeting.php'); ?>
                <?php } ?>

                <?php if ($form == 'Tailgate Safety Meeting') { ?>
                    <?php include ('tailgate_safety_meeting/tailgate_safety_meeting.php'); ?>
                <?php } ?>

                <?php if ($form == 'Toolbox Safety Meeting') { ?>
                    <?php include ('toolbox_safety_meeting/toolbox_safety_meeting.php'); ?>
                <?php } ?>

                <?php if ($form == 'Daily Equipment Inspection Checklist') { ?>
                    <?php include ('daily_equipment_inspection_checklist/daily_equipment_inspection_checklist.php'); ?>
                <?php } ?>

                <?php if ($form == 'AVS Hazard Identification') { ?>
                    <?php include ('avs_hazard_identification/avs_hazard_identification.php'); ?>
                <?php } ?>

                <?php if ($form == 'AVS Near Miss') { ?>
                    <?php include ('near_miss_report/near_miss_report.php'); ?>
                <?php } ?>

                <?php if ($form == 'Incident Investigation Report') { ?>
                    <?php include ('incident_investigation_report/incident_investigation_report.php'); ?>
                <?php } ?>

                <?php if ($form == 'Follow Up Incident Report') { ?>
                    <?php include ('follow_up_incident_report/follow_up_incident_report.php'); ?>
                <?php } ?>

                <?php if ($form == 'Pre Job Hazard Assessment') { ?>
                    <?php include ('pre_job_hazard_assessment/pre_job_hazard_assessment.php'); ?>
                <?php } ?>

                <?php if ($form == 'Monthly Site Safety Inspections') { ?>
                    <?php include ('monthly_site_safety_inspection/monthly_site_safety_inspection.php'); ?>
                <?php } ?>

                <?php if ($form == 'Monthly Office Safety Inspections') { ?>
                    <?php include ('monthly_office_safety_inspection/monthly_office_safety_inspection.php'); ?>
                <?php } ?>

                <?php if ($form == 'Monthly Health and Safety Summary') { ?>
                    <?php include ('monthly_health_and_safety_summary/monthly_health_and_safety_summary.php'); ?>
                <?php } ?>

                <?php if ($form == 'Trailer Inspection Checklist') { ?>
                    <?php include ('trailer_inspection_checklist/trailer_inspection_checklist.php'); ?>
                <?php } ?>

                <?php if ($form == 'Employee Misconduct Form') { ?>
                    <?php include ('employee_misconduct_form/employee_misconduct_form.php'); ?>
                <?php } ?>

                <?php if ($form == 'Site Inspection Hazard Assessment') { ?>
                    <?php include ('site_inspection_hazard_assessment/site_inspection_hazard_assessment.php'); ?>
                <?php } ?>

                <?php if ($form == 'Weekly Planned Inspection Checklist') { ?>
                    <?php include ('weekly_planned_inspection_checklist/weekly_planned_inspection_checklist.php'); ?>
                <?php } ?>
                <?php if ($form == 'Equipment Inspection Checklist') { ?>
                    <?php include ('equipment_inspection_checklist/equipment_inspection_checklist.php'); ?>
                <?php } ?>
                <?php if ($form == 'Employee Equipment Training Record') { ?>
                    <?php include ('employee_equipment_training_record/employee_equipment_training_record.php'); ?>
                <?php } ?>
                <?php if ($form == 'Vehicle Inspection Checklist') { ?>
                    <?php include ('vehicle_inspection_checklist/vehicle_inspection_checklist.php'); ?>
                <?php } ?>
                <?php if ($form == 'Safety Meeting Minutes') { ?>
                    <?php include ('safety_meeting_minutes/safety_meeting_minutes.php'); ?>
                <?php } ?>
                <?php if ($form == 'Vehicle Damage Report') { ?>
                    <?php include ('vehicle_damage_report/vehicle_damage_report.php'); ?>
                <?php } ?>
                <?php if ($form == 'Fall Protection Plan') { ?>
                    <?php include ('fall_protection_plan/fall_protection_plan.php'); ?>
                <?php } ?>
                <?php if ($form == 'Spill Incident Report') { ?>
                    <?php include ('spill_incident_report/spill_incident_report.php'); ?>
                <?php } ?>
                <?php if ($form == 'General Site Safety Inspection') { ?>
                    <?php include ('general_site_safety_inspection/general_site_safety_inspection.php'); ?>
                <?php } ?>
                <?php if ($form == 'Confined Space Entry Permit') { ?>
                    <?php include ('confined_space_entry_permit/confined_space_entry_permit.php'); ?>
                <?php } ?>
                <?php if ($form == 'Lanyards Inspection Checklist Log') { ?>
                    <?php include ('lanyards_inspection_checklist_log/lanyards_inspection_checklist_log.php'); ?>
                <?php } ?>
                <?php if ($form == 'On The Job Training Record') { ?>
                    <?php include ('on_the_job_training_record/on_the_job_training_record.php'); ?>
                <?php } ?>
                <?php if ($form == 'General Office Safety Inspection') { ?>
                    <?php include ('general_office_safety_inspection/general_office_safety_inspection.php'); ?>
                <?php } ?>
                <?php if ($form == 'Full Body Harness Inspection Checklist Log') { ?>
                    <?php include ('full_body_harness_inspection_checklist_log/full_body_harness_inspection_checklist_log.php'); ?>
                <?php } ?>
                <?php if ($form == 'Confined Space Pre Entry Checklist') { ?>
                    <?php include ('confined_space_entry_pre_entry_checklist/confined_space_entry_pre_entry_checklist.php'); ?>
                <?php } ?>
                <?php if ($form == 'Confined Space Entry Log') { ?>
                    <?php include ('confined_space_entry_log/confined_space_entry_log.php'); ?>
                <?php } ?>
                <?php if ($form == 'Emergency Response Transportation Plan') { ?>
                    <?php include ('emergency_response_transportation_plan/emergency_response_transportation_plan.php'); ?>
                <?php } ?>

                <?php if ($form == 'Hazard Id Report') { ?>
                    <?php include ('hazard_id_report/hazard_id_report.php'); ?>
                <?php } ?>
                <?php if ($form == 'Dangerous Goods Shipping Document') { ?>
                    <?php include ('dangerous_goods_shipping_document/dangerous_goods_shipping_document.php'); ?>
                <?php } ?>
                <?php if ($form == 'Safe Work Permit') { ?>
                    <?php include ('safe_work_permit/safe_work_permit.php'); ?>
                <?php } ?>
                <?php if ($form == 'Journey Management - Trip Tracking Form') { ?>
                    <?php include ('journey_management_trip_tracking/journey_management_trip_tracking.php'); ?>
                <?php } ?>
                <?php if ($form == 'Motor Vehicle Accident Form') { ?>
                    <?php include ('incident_reports/incident_reports.php'); ?>
                <?php }
                } ?>
				<button type="submit" name="field_level_hazard" value="field_level_hazard_submit" class="btn brand-btn btn-lg pull-right">Submit</button>
			</div>
            <?php if($user_forms_layout == 'Sidebar') { ?>
                </div>
            </div>
            <?php } ?>

        <?php } else { ?>
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
                                <input disabled type="checkbox" <?php if (strpos($value_config, ','."Topic (Sub Tab)".',') !== FALSE) { echo " checked"; } ?> value="Topic (Sub Tab)" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Topic (Sub Tab)
                            </td>
                            <td>
                                <input disabled type="checkbox" <?php if (strpos($value_config, ','."Section #".',') !== FALSE) { echo " checked"; } ?> value="Section #" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Section #
                            </td>
                            <td>
                                <input disabled type="checkbox" <?php if (strpos($value_config, ','."Section Heading".',') !== FALSE) { echo " checked"; } ?> value="Section Heading" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Section Heading
                            </td>
                            <td>
                                <input disabled type="checkbox" <?php if (strpos($value_config, ','."Sub Section #".',') !== FALSE) { echo " checked"; } ?> value="Sub Section #" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Sub Section #
                            </td>
                            <td>
                                <input disabled type="checkbox" <?php if (strpos($value_config, ','."Sub Section Heading".',') !== FALSE) { echo " checked"; } ?> value="Sub Section Heading" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Sub Section Heading
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($value_config, ','."Third Tier Section #".',') !== FALSE) { echo " checked"; } ?> value="Third Tier Section #" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Third Tier Section #
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($value_config, ','."Third Tier Heading".',') !== FALSE) { echo " checked"; } ?> value="Third Tier Heading" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Third Tier Heading
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" <?php if (strpos($value_config, ','."Detail".',') !== FALSE) { echo " checked"; } ?> value="Detail" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Detail
                            </td>

                            <td>
                                <input type="checkbox" <?php if (strpos($value_config, ','."Document".',') !== FALSE) { echo " checked"; } ?> value="Document" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Document
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($value_config, ','."Link".',') !== FALSE) { echo " checked"; } ?> value="Link" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Link
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($value_config, ','."Videos".',') !== FALSE) { echo " checked"; } ?> value="Videos" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Videos
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($value_config, ','."Signature box".',') !== FALSE) { echo " checked"; } ?> value="Signature box" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Signature box
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($value_config, ','."Comments".',') !== FALSE) { echo " checked"; } ?> value="Comments" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Comments
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($value_config, ','."Staff".',') !== FALSE) { echo " checked"; } ?> value="Staff" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Staff
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" <?php if (strpos($value_config, ','."Review Deadline".',') !== FALSE) { echo " checked"; } ?> value="Review Deadline" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Review Deadline
                            </td>

                            <td>
                                <input type="checkbox" <?php if (strpos($value_config, ','."Status".',') !== FALSE) { echo " checked"; } ?> value="Status" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Status
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($value_config, ','."Form".',') !== FALSE) { echo " checked"; } ?> value="Form" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Form
                            </td>
							<td>
                                <input type="checkbox" <?php if (strpos($value_config, ','."Row Marker".',') !== FALSE) { echo " checked"; } ?> value="Row Marker" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Row Marker
                            </td>
							<td>
								<input type="checkbox" <?php if (strpos($value_config, ','."Configure Email".',') !== FALSE) { echo " checked"; } ?> value="Configure Email" name="fields[]">&nbsp;&nbsp;Configure Email
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

        <!--<div class="panel panel-default">
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
        </div>-->

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

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_abi" >Headings<span class="glyphicon glyphicon-minus"></span></a>
                    </h4>
                </div>

                <div id="collapse_abi" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <?php include ('manual_basic_field.php'); ?>
                    </div>
                </div>
            </div>

            <?php if (strpos($value_config, ','."Detail".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_content" >Content<span class="glyphicon glyphicon-minus"></span></a>
                    </h4>
                </div>

                <div id="collapse_content" class="panel-collapse collapse">
                    <div class="panel-body">
                          <div class="form-group">
                            <label for="first_name[]" class="col-sm-4 control-label">Detail:</label>
                            <div class="col-sm-8">
                              <textarea name="description" rows="5" cols="50" class="form-control"><?php echo $description; ?></textarea>
                            </div>
                          </div>
                    </div>
                </div>
            </div>
            <?php } ?>

			<?php if (strpos($value_config, ','."Row Marker".',') !== FALSE) { ?>

			<div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_contentmarker" >Row Marker<span class="glyphicon glyphicon-minus"></span></a>
                    </h4>
                </div>

                <div id="collapse_contentmarker" class="panel-collapse collapse">
                    <div class="panel-body">
                          <div class="form-group">
                            <label for="first_name[]" class="col-sm-4 control-label">Choose a Marker:</label>
                            <div class="col-sm-8">
								<select name="colorpickers" class='form-control' style='max-width:200px; background-color:<?php echo $marker; ?>'>

								  <option <?php if($marker == '#ffffff') { echo "selected="; } ?> style='background-color:#ffffff;' value="">Disable Marker</option>
								  <option <?php if($marker == '#7bd148') { echo "selected"; } ?> style='background-color:#7bd148;' value="#7bd148">Green</option>
								  <option <?php if($marker == '#5484ed') { echo "selected"; } ?> style='background-color:#5484ed;' value="#5484ed">Bold blue</option>
								  <option <?php if($marker == '#a4bdfc') { echo "selected"; } ?> style='background-color:#a4bdfc;' value="#a4bdfc">Blue</option>
								  <option <?php if($marker == '#46d6db') { echo "selected"; } ?> style='background-color:#46d6db;' value="#46d6db">Turquoise</option>
								  <option <?php if($marker == '#7ae7bf') { echo "selected"; } ?> style='background-color:#7ae7bf;' value="#7ae7bf">Light green</option>
								  <option <?php if($marker == '#51b749') { echo "selected"; } ?> style='background-color:#51b749;' value="#51b749">Bold green</option>
								  <option <?php if($marker == '#fbd75b') { echo "selected"; } ?> style='background-color:#fbd75b;' value="#fbd75b">Yellow</option>
								  <option <?php if($marker == '#ffb878') { echo "selected"; } ?> style='background-color:#ffb878;' value="#ffb878">Orange</option>
								  <option <?php if($marker == '#ff887c') { echo "selected"; } ?> style='background-color:#ff887c;' value="#ff887c">Red</option>
								  <option <?php if($marker == '#dc2127') { echo "selected"; } ?> style='background-color:#dc2127;' value="#dc2127">Bold red</option>
								  <option <?php if($marker == '#dbadff') { echo "selected"; } ?> style='background-color:#dbadff;' value="#dbadff">Purple</option>
								  <option <?php if($marker == '#e1e1e1') { echo "selected"; } ?> style='background-color:#e1e1e1;' value="#e1e1e1">Gray</option>
								</select>
                            </div>
                          </div>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Document".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_docs" >Document Upload<span class="glyphicon glyphicon-minus"></span></a>
                    </h4>
                </div>

                <div id="collapse_docs" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?php include ('manual_document_field.php'); ?>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Link".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_link" >Add Link<span class="glyphicon glyphicon-minus"></span></a>
                    </h4>
                </div>

                <div id="collapse_link" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?php include ('manual_link_field.php'); ?>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Videos".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_video" >Upload Video<span class="glyphicon glyphicon-minus"></span></a>
                    </h4>
                </div>

                <div id="collapse_video" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?php include ('manual_video_field.php'); ?>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Staff".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_staff" >Assign to Staff<span class="glyphicon glyphicon-minus"></span></a>
                    </h4>
                </div>

                <div id="collapse_staff" class="panel-collapse collapse">
                    <div class="panel-body">

                            <div class="form-group clearfix completion_date">
                                <label for="first_name" class="col-sm-4 control-label text-right">Staff:</label>
                                <div class="col-sm-8">
                                    <select name="assign_staff[]" data-placeholder="Choose a Staff Member..." class="chosen-select-deselect form-control" multiple width="380">
                                        <option value=""></option><?php
                                        $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 ORDER BY first_name");
                                        while($row = mysqli_fetch_array($query)) {
											if ( !empty ( $assign_staff ) ) { ?>
												<option <?php if (strpos(','.$assign_staff.',', ','.$row['contactid'].',') !== FALSE) { echo ' selected="selected"'; } ?> value="<?php echo $row['contactid']; ?>"><?php echo decryptIt($row['first_name']) . ' ' . decryptIt($row['last_name']); ?></option><?php
											} else { ?>
												<option value="<?= $row['contactid']; ?>"><?= decryptIt($row['first_name']) . ' ' . decryptIt($row['last_name']); ?></option><?php
											}
										} ?>
                                    </select>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (tile_visible($dbc, 'site_work_orders') == 1) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_site_work_orders" >Assign Sites and Work Orders<span class="glyphicon glyphicon-minus"></span></a>
                    </h4>
                </div>

                <div id="collapse_site_work_orders" class="panel-collapse collapse">
                    <div class="panel-body">
						<div class="form-group clearfix completion_date">
							<script>
								$(document).ready(function() {
									$('[name="assign_sites[]"]').change(function() {
										if(this.value == 'SELECT ALL') {
											$(this).find('option[value!=ALL]').removeAttr('selected');
											$(this).find('option[value="SELECT ALL"]').val('ALL');
											$(this).val('ALL').trigger('change.select2');
										} else if($(this).val() != 'ALL') {
											$(this).find('option[value=ALL]').removeAttr('selected').val('SELECT ALL');
											$(this).trigger('change.select2');
										}
									});
								});
							</script>
							<label for="first_name" class="col-sm-4 control-label text-right">Sites:</label>
							<div class="col-sm-8">
								<select name="assign_sites[]" data-placeholder="Select Sites" class="chosen-select-deselect form-control" multiple width="380">
									<option value=""></option>
									<option <?= ($assign_sites == ',ALL,' ? 'selected value="ALL"' : 'value="SELECT ALL"') ?>>All Sites</option><?php
									$query = mysqli_query($dbc,"SELECT `contactid`, `site_name` FROM `contacts` WHERE `category`='Sites' ORDER BY `site_name`");
									while($row = mysqli_fetch_array($query)) { ?>
										<option <?php if (strpos(','.$assign_sites.',', ','.$row['contactid'].',') !== FALSE) { echo ' selected="selected"'; } ?> value="<?php echo $row['contactid']; ?>"><?= $row['site_name'] ?></option><?php
									} ?>
								</select>
							</div>
						</div>
						<div class="form-group clearfix completion_date">
							<script>
								$(document).ready(function() {
									$('[name="assign_work_orders[]"]').change(function() {
										if(this.value == 'SELECT ALL') {
											$(this).find('option[value!=ALL]').removeAttr('selected');
											$(this).find('option[value="SELECT ALL"]').val('ALL');
											$(this).val('ALL').trigger('change.select2');
										} else if($(this).val() != 'ALL') {
											$(this).find('option[value=ALL]').removeAttr('selected').val('SELECT ALL');
											$(this).trigger('change.select2');
										}
									});
								});
							</script>
							<label for="first_name" class="col-sm-4 control-label text-right">Site Work Orders:</label>
							<div class="col-sm-8">
								<select name="assign_work_orders[]" data-placeholder="Select Work Orders" class="chosen-select-deselect form-control" multiple width="380">
									<option value=""></option>
									<option <?= ($assign_work_orders == ',ALL,' ? 'selected value="ALL"' : 'value="SELECT ALL"') ?>>All Work Orders</option><?php
									$query = mysqli_query($dbc,"SELECT * FROM `site_work_orders` WHERE `status`!='Archived'");
									while($row = mysqli_fetch_array($query)) { ?>
										<option <?php if (strpos(','.$assign_work_orders.',', ','.$row['workorderid'].',') !== FALSE) { echo ' selected="selected"'; } ?> value="<?php echo $row['workorderid']; ?>">Work Order #<?= $row['workorderid'] ?></option><?php
									} ?>
								</select>
							</div>
						</div>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Review Deadline".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_deadline" >Review Deadline<span class="glyphicon glyphicon-minus"></span></a>
                    </h4>
                </div>

                <div id="collapse_deadline" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div class="form-group clearfix">
                            <label for="first_name" class="col-sm-4 control-label text-right">Review Deadline:</label>
                            <div class="col-sm-8">
                                <input name="deadline" type="text" class="datepicker" value="<?php echo $deadline; ?>"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>

			<!-- Configure Email -->
			<?php if ( strpos ( $value_config, ',' . "Configure Email" . ',' ) !== FALSE ) { ?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_email">Configure Email<span class="glyphicon glyphicon-minus"></span></a>
						</h4>
					</div>

					<div id="collapse_email" class="panel-collapse collapse">
						<div class="panel-body">
							<div class="form-group clearfix">
								<label for="email_subject" class="col-sm-4 control-label text-right">Email Subject:</label>
								<div class="col-sm-8"><input class="form-control" name="email_subject" type="text" value="<?= $email_subject; ?>"></div>
							</div>
							<div class="form-group clearfix">
								<label for="email_message" class="col-sm-4 control-label text-right">Email Message:</label>
								<div class="col-sm-8"><textarea name="email_message"><?= html_entity_decode($email_message); ?></textarea></div>
							</div>
						</div>
					</div>
				</div>
            <?php } ?>

        </div>

            <div class="form-group">
				<p><span class="hp-red"><em>Required Fields *</em></span></p>
				<button type="submit" name="add_manual" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
            </div>
        <?php } ?>

        

		<script type="text/javascript">

			$(document).ready(function() {
				$('select[name="colorpicker"]').simplecolorpicker();
				$('select[name="colorpicker"]').simplecolorpicker('selectColor', '#7bd148');
				$('select[name="colorpicker"]').simplecolorpicker('destroy');

				$('select[name="colorpicker"]').simplecolorpicker({
				  picker: true
				}).on('change', function() {
				  $(document.body).css('background-color', $('select[name="colorpicker"]').val());
				});
			});

</script>

    </form>

  </div>

