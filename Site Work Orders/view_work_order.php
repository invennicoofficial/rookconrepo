<?php include('../include.php');
error_reporting(0);
$from_url = (empty($_GET['from']) ? 'site_work_orders.php?tab=schedule' : $_GET['from']);
$from_site_url = (empty($_GET['from']) ? 'site_work_orders.php?tab=schedule&site='.$_POST['siteid'] : $_GET['from']);

if(!empty($_POST['workorderid'])) {
	$workorderid = $_POST['workorderid'];
	$staff_crew_count = $_POST['staff_crew_count'];
	$staff_crew = [];
	$staff_positions = [];
	$staff_estimate_hours = [];
	$staff_estimate_days = [];
	for ($i = 0; $i < $staff_crew_count; $i++) {
		foreach ($_POST['staff_crew_'.$i] as $single_staff) {
			$staff_crew[] = empty($single_staff) ? '' : $single_staff;
			$staff_positions[] = empty($_POST['staff_positions_'.$i]) ? '' : $_POST['staff_positions_'.$i];
			$staff_estimate_hours[] = empty($_POST['staff_estimate_hours_'.$i]) ? '' : $_POST['staff_estimate_hours_'.$i];
			$staff_estimate_days[] = empty($_POST['staff_estimate_days_'.$i]) ? '' : $_POST['staff_estimate_days_'.$i];
		}
	}
	foreach($staff_crew as $key => $value) {
		if($value == '') {
			unset($staff_crew[$key]);
			unset($staff_positions[$key]);
			unset($staff_estimate_hours[$key]);
			unset($staff_estimate_days[$key]);
		}
	}
	$staff_crew = implode(',',$staff_crew);
	$staff_positions = implode(',',$staff_positions);
	$staff_estimate_hours = filter_var(implode(',',$staff_estimate_hours),FILTER_SANITIZE_STRING);
	$staff_estimate_days = filter_var(implode(',',$staff_estimate_days),FILTER_SANITIZE_STRING);
	$work_start_date = filter_var($_POST['work_start_date'],FILTER_SANITIZE_STRING);
	$work_end_date = filter_var($_POST['work_end_date'],FILTER_SANITIZE_STRING);
	$work_start_time = filter_var(implode(',',$_POST['work_start_time']),FILTER_SANITIZE_STRING);
	$work_start_details = filter_var(implode(',',$_POST['work_start_details']),FILTER_SANITIZE_STRING);
	$service_check = implode(',',$_POST['service_check']);
	$equip_check = implode(',',$_POST['equip_check']);
	foreach($_POST['equipmentid'] as $key => $value) {
		if($value > 0) {
			mysqli_query($dbc, "UPDATE `equipment` SET `status`='".$_POST['equipment_status'][$key]."' WHERE `equipmentid`='$value'");
		}
	}
	$material_check = implode(',',$_POST['material_check']);
	$po_list = [];
	foreach($_POST['po_list'] as $row => $poid) {
		if($_POST['attach_po_'.$poid] == 'attach') {
			$po_list[] = $poid;
			$markup = $_POST['mark_up'][$row];
			mysqli_query($dbc, "UPDATE `site_work_po` SET `mark_up`='$markup' WHERE `poid`='$poid'");
		}
	}
	$po_id = implode(',',$po_list);
	$comments = filter_var(htmlentities($_POST['comments']),FILTER_SANITIZE_STRING);
	
	$summary = [];
	$current_wo = mysqli_fetch_array(mysqli_query($dbc, "SELECT `summary`, `summary_timer_start` FROM `site_work_orders` WHERE `workorderid`='$workorderid'"));
	$summary_current = explode('#*#',$current_wo['summary']);
	foreach($_POST['summary_staff'] as $row => $staff) {
		if($staff != '') {
			$task = filter_var($_POST['summary_task'][$row],FILTER_SANITIZE_STRING);
			$hours = filter_var($_POST['summary_hours'][$row],FILTER_SANITIZE_STRING);
			$timer_start = filter_var($_POST['summary_timer_start'][$row],FILTER_SANITIZE_STRING);
			if($_POST['submit'] == 'assign' && $timer_start == 'NEW' && $current_wo['summary_timer_start'] > 0) {
				$other_work_orders = mysqli_query($dbc, "SELECT `workorderid`, `siteid`, `id_label`, `summary_timer_start`, `summary` FROM `site_work_orders` WHERE (`summary` LIKE '%#*#".$staff."**#**%' OR `summary` LIKE '".$staff."**#**%') AND `workorderid` != '$workorderid'");
				while($crew_wo = mysqli_fetch_array($other_work_orders)) {
					$wo_summary = explode('#*#',$crew_wo['summary']);
					foreach($wo_summary as $wo_row => $wo_line) {
						$wo_line = explode('**#**', $wo_line);
						if($wo_line[0] == $staff && empty($wo_line[4])) {
							$start_time = ($wo_line[3] > $crew_wo['summary_time_start'] ? $wo_line[3] : $crew_wo['summary_time_start']);
							$duration = round((time() - $start_time) / 3600, 3);
							$max_time = get_config($dbc, 'max_timer');
							if($duration > $max_time && !empty($max_time) && $max_time > 0) {
								$duration = $max_time;
							}
							mysqli_query($dbc, "INSERT INTO `time_cards` (`staff`, `business`, `date`, `type_of_time`, `total_hrs`, `comment_box`) VALUES ('{$wo_line[0]}', '{$crew_wo['siteid']}', '".date('Y-m-d')."', 'Regular Hrs.', '$duration', 'Site Work Order {$crew_wo['id_label']}: {$wo_line[1]}')");
							$wo_line[2] += $duration;
							$wo_line[3] = 0;
							$wo_line[4] = 1;
							$wo_summary[$wo_row] = implode('**#**',$wo_line);
						}
					}
					$wo_summary = implode('#*#',$wo_summary);
					mysqli_query($dbc, "UPDATE `site_work_orders` SET `summary`='$wo_summary' WHERE `workorderid`='{$crew_wo['workorderid']}'");
				}
				$timer_start = time();
			} else {
				foreach($summary_current as $current_line) {
					$current_line = explode('**#**', $current_line);
					if($current_line[0] == $staff && $current_line[1] == $task && $current_line[3] > 0) {
						$timer_start = $current_line[3];
					}
				}
			}
			$disabled = filter_var($_POST['summary_disabled'][$row],FILTER_SANITIZE_STRING);
			if(empty($disabled)) {
				foreach($summary_current as $current_line) {
					$current_line = explode('**#**', $current_line);
					if($current_line[0] == $staff && $current_line[1] == $task && $current_line[4] > 0) {
						$timer_start = $current_line[4];
					}
				}
			}
			$summary[] = implode('**#**', [$staff, $task, $hours, $timer_start, $disabled]);
		}
	}
	$summary_line = implode('#*#', $summary);
	
	if($workorderid == 'NEW') {
		$work_order_sql = "INSERT INTO `site_work_orders` (`staff_crew`, `staff_positions`, `staff_estimate_hours`, `staff_estimate_days`, `work_start_date`, `work_end_date`, `work_start_time`, `work_start_details`, `po_id`, `service_check`, `equip_check`, `material_check`)
			VALUES ('$staff_crew', '$staff_positions', '$staff_estimate_hours', '$staff_estimate_days', '$work_start_date', '$work_end_date', '$work_start_time', '$work_start_details', '$po_id', '$service_check', '$equip_check', '$material_check')";
	} else {
		$work_order_sql = "UPDATE `site_work_orders` SET `staff_crew`='$staff_crew', `staff_positions`='$staff_positions', `staff_estimate_hours`='$staff_estimate_hours', `staff_estimate_days`='$staff_estimate_days', `work_start_date`='$work_start_date', `work_end_date`='$work_end_date', `work_start_time`='$work_start_time', `work_start_details`='$work_start_details', `po_id`='$po_id', `service_check`='$service_check', `equip_check`='$equip_check', `material_check`='$material_check', `summary`='$summary_line' WHERE `workorderid`='$workorderid'";
	}
	
	$result = mysqli_query($dbc, $work_order_sql);

	if($workorderid == 'NEW') {
		$workorderid = mysqli_insert_id($dbc);
	}
	$status = mysqli_fetch_array(mysqli_query($dbc, "SELECT `status` FROM `site_work_orders` WHERE `workorderid`='$workorderid'"))['status'];
	mysqli_query($dbc, "UPDATE `site_work_orders` SET `active`='' WHERE `workorderid`='$workorderid'");
	
	$checklists = trim(filter_var($_POST['checklists'],FILTER_SANITIZE_STRING),',');
	$result = mysqli_query($dbc, "UPDATE `site_work_checklist` SET `workorderid`='$workorderid' WHERE `checklistid` IN ($checklists)");
	
	//Comment
	$note_heading = filter_var($_POST['note_heading'],FILTER_SANITIZE_STRING);
	$type = 'note';
	$comments = filter_var(htmlentities($_POST['comments']),FILTER_SANITIZE_STRING);
	if($comments != '') {
		$email_comment = $_POST['email_comment'];
		$query_insert_ca = "INSERT INTO `site_work_comment` (`workorderid`, `comment`, `created_date`, `created_by`, `type`, `note_heading`) VALUES ('$workorderid', '$comments', '$created_date', '$created_by', '$type', '$note_heading')";
		$result_insert_ca = mysqli_query($dbc, $query_insert_ca);

		if($_POST['send_email_on_comment'] == 'Yes') {
			$email = $_POST['comment_email_sender'];
			$email_name_result = mysqli_query($dbc, "SELECT `contactid` FROM `contacts` WHERE `email_address` = '".encryptIt($email)."' OR `office_email` = '".encryptIt($email)."'");
			if($email != '' && $email_name_id == mysqli_fetch_array($email_name_result)) {
				$from = [$email => get_contact($dbc, $email_name_id['contactid'])];
			} else if($email != '') {
				$from = [$email => get_contact($dbc, $_SESSION['contactid'])];
			} else {
				$from = '';
			}

			$subject = $_POST['comment_email_subject'];
			$message = str_replace(['[NOTE]','[WORKORDERID]'], [$_POST['comments'],$workorderid,get_client($dbc,$businessid),$heading,$status], $_POST['comment_email_body']).get_contact($dbc, $email_name_id['contactid'], 'email_address');
			$email = get_email($dbc, $email_comment);
			try {
				send_email($from, $email, '', '', $subject, $message, '');
			} catch(Exception $e) {
				echo "<script>alert('Unable to send email. Please try again later.');console.log('".$e->getMessage()."');</script>";
			}
		}
	}
	
	//Addendum
	$note_heading = filter_var($_POST['addendum_note_heading'],FILTER_SANITIZE_STRING);
	$type = 'addendum';
	$comments = filter_var(htmlentities($_POST['addendum_comments']),FILTER_SANITIZE_STRING);
	if($comments != '') {
		$email_comment = $_POST['addendum_email_comment'];
		$query_insert_ca = "INSERT INTO `site_work_comment` (`workorderid`, `comment`, `created_date`, `created_by`, `type`, `note_heading`) VALUES ('$workorderid', '$comments', '$created_date', '$created_by', '$type', '$note_heading')";
		$result_insert_ca = mysqli_query($dbc, $query_insert_ca);

		if($_POST['addendum_send_email_on_comment'] == 'Yes') {
			$email = $_POST['addendum_comment_email_sender'];
			$email_name_result = mysqli_query($dbc, "SELECT `contactid` FROM `contacts` WHERE `email_address` = '".encryptIt($email)."' OR `office_email` = '".encryptIt($email)."'");
			if($email != '' && $email_name_id == mysqli_fetch_array($email_name_result)) {
				$from = [$email => get_contact($dbc, $email_name_id['contactid'])];
			} else if($email != '') {
				$from = [$email => get_contact($dbc, $_SESSION['contactid'])];
			} else {
				$from = '';
			}

			$subject = $_POST['addendum_comment_email_subject'];
			$message = str_replace(['[ADDENDUM]','[WORKORDERID]'], [$_POST['addendum_comments'],$workorderid,get_client($dbc,$businessid),$heading,$status], $_POST['addendum_comment_email_body']).get_contact($dbc, $email_name_id['contactid'], 'email_address');
			$email = get_email($dbc, $email_comment);
			try {
				send_email($from, $email, '', '', $subject, $message, '');
			} catch(Exception $e) {
				echo "<script> alert('Unable to send email. Please try again later.'); console.log('".$e->getMessage()."'); </script>";
			}
		}
	}
	
    //Document
    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }
    for($i = 0; $i < count($_FILES['upload_document']['name']); $i++) {
        $document = $_FILES["upload_document"]["name"][$i];

        move_uploaded_file($_FILES["upload_document"]["tmp_name"][$i], "download/".$_FILES["upload_document"]["name"][$i]) ;

        if($document != '') {
            $query_insert_client_doc = "INSERT INTO `site_work_document` (`workorderid`, `type`, `document`, `created_date`, `created_by`) VALUES ('$workorderid', 'Support Document', '".filter_var($document, FILTER_SANITIZE_STRING)."', '$created_date', '$created_by')";
            $result_insert_client_doc = mysqli_query($dbc, $query_insert_client_doc);
        }
    }
	
	if($_POST['submit'] == 'sign_out') {
		echo "<script> window.location.replace('$from_url');</script>";
	} else if(substr($_POST['submit'], 0, 8) == 'DRIVING_') {
		echo "<script> window.location.replace('add_driving_log.php?log_id=".substr($_POST['submit'],8)."');</script>";
	} else if($_POST['submit'] == 'DUPLICATE') {
		echo "<script> window.location.replace('add_work_order.php?src_id=".$workorderid."');</script>";
	} else if($_POST['submit'] != 'assign' && $_POST['submit'] != 'unassign') {
		echo "<script> window.location.replace('$from_site_url');</script>";
	}
} else if(!empty($_GET['workorderid'])) {
	mysqli_query($dbc, "UPDATE `site_work_orders` SET `active`=CURRENT_TIMESTAMP WHERE `workorderid`='".$_GET['workorderid']."'");
}

include_once ('../navigation.php');
checkAuthorised('site_work_orders');

$workorderid = '';
$businessid = '';
$service_code = '';
$id_label = '';
$siteid = '';
$contactid = '';
$staff_lead = '';
$staff_crew = ',,';
$staff_positions = ',,';
$staff_estimate_hours = ',,';
$staff_estimate_days = ',,';
$service_cat = '#*#';
$service_head = '#*#';
$service_check = explode(',',',');
$equipment_id = ',';
$equipment_rate = ',';
$equipment_status = ',';
$equipment_check = explode(',',',');
$material_id = '#*#';
$material_qty = ',';
$material_check = explode(',',',');
$site_location = '';
$site_description = '';
$google_map_link = '';
$work_start_date = '';
$work_end_date = '';
$work_start_time = '';
$work_start_details = '';
$po_id = '';
$comments = '';
if(!empty($_GET['workorderid'])) {
	$workorderid = filter_var($_GET['workorderid'],FILTER_SANITIZE_STRING);
	$work_order = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `site_work_orders` WHERE `workorderid`='$workorderid'"));
	$service_code = $work_order['service_code'];
	$id_label = $work_order['id_label'];
	$businessid = $work_order['businessid'];
	$siteid = $work_order['siteid'];
	$contactid = $work_order['contactid'];
	$staff_lead = $work_order['staff_lead'];
	$staff_crew = $work_order['staff_crew'];
	$staff_positions = $work_order['staff_positions'];
	$staff_estimate_hours = $work_order['staff_estimate_hours'];
	$staff_estimate_days = $work_order['staff_estimate_days'];
	$service_cat = $work_order['service_cat'];
	$service_head = $work_order['service_heading'];
	$service_check = explode(',',$work_order['service_check']);
	$equipment_id = $work_order['equipment_id'];
	$equipment_rate = $work_order['equipment_rate'];
	$equipment_status = $work_order['equipment_status'];
	$equip_check = explode(',',$work_order['equip_check']);
	$material_id = $work_order['material_id'];
	$material_qty = $work_order['material_qty'];
	$material_check = explode(',',$work_order['material_check']);
	$site_location = $work_order['site_location'];
	$site_description = $work_order['site_description'];
	$google_map_link = $work_order['google_map_link'];
	$work_start_date = $work_order['work_start_date'];
	$work_end_date = $work_order['work_end_date'];
	$work_start_time = $work_order['work_start_time'];
	$work_start_details = $work_order['work_start_details'];
	$po_id = $work_order['po_id'];
	$comments = $work_order['comments'];
	$summary_timer = $work_order['summary_timer_start'];
}
$staff_crew = explode(',',$staff_crew);
$staff_positions = explode(',',$staff_positions);
$staff_estimate_hours = explode(',',$staff_estimate_hours);
$staff_estimate_days = explode(',',$staff_estimate_days);
$staff_summary = explode('#*#',$work_order['summary']);
$staff_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT `contactid`, `last_name`, `first_name` FROM `contacts` WHERE `category`='Staff' AND `deleted`=0 AND `status`=1 AND `show_hide_user`=1"),MYSQLI_ASSOC));
$task_list = explode('#*#', get_config($dbc, 'site_work_order_tasks'));

$from_site_url = (empty($_GET['from']) ? 'site_work_orders.php?tab=schedule&site='.$siteid : $_GET['from']); ?>
<style>
.summary input[type=number][data-disabled="true"] {
	background-image: url("data:image/svg+xml;utf8,<svg xmlns='https://www.w3.org/2000/svg' version='1.1' height='50px' width='140px'><text x='0' y='15' fill='rgb(55,55,55)' font-family='Arial' font-size='10'>Time will not track for this staff.</text></svg>");
    background-repeat: no-repeat;
    background-position-x: calc(100% - 1em);
    background-position-y: 0.25em;
}
.summary input[type=number][data-disabled="false"]:read-only {
	background-image: url("data:image/svg+xml;utf8,<svg xmlns='https://www.w3.org/2000/svg' version='1.1' height='50px' width='120px'><text x='0' y='15' fill='rgb(55,55,55)' font-family='Arial' font-size='10'>Time is currently tracking.</text></svg>");
    background-repeat: no-repeat;
    background-position-x: calc(100% - 1em);
    background-position-y: 0.25em;
}
</style>
<script>
$(document).ready(function() {
	$('[name=businessid]').change(function() { business_select(this.value); });
	business_select('<?= $businessid ?>');
	site_select('<?= $siteid ?>');
	$('[name$="_check[]"]').change(function() {
		$(this).closest('div').find('input').not(this)[0].checked = !this.checked;
	});

	<?php
		$accordion_list = ['who' => 'Who', 'staff' => 'Staff & Crew', 'services' => 'Services', 'equip' => 'Equipment', 'material' => 'Materials', 'where' => 'Where', 'when' => 'When', 'docs' => 'Support Documents', 'checklist' => 'Site Checklist', 'pos' => 'Purchase Orders', 'comments' => 'Comments', 'safety' => 'Safety Checklist', 'addendum' => 'Addendum'];
		$contact_position = mysqli_fetch_array(mysqli_query($dbc, "SELECT `position` FROM `contacts` WHERE `contactid` = '".$_SESSION['contactid']."'"))['position'];

		foreach ($accordion_list as $accordion => $accordion_title) {
			$permission = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `general_configuration` WHERE `name` = 'swo_readonly_".$accordion."'"));
			if (!empty($permission['value'])) {
				$positions = ','.$permission['value'].',';
				if (strpos($positions, ','.$contact_position.',') !== FALSE) {
					$accordion_id = 'collapse_'.$accordion;
					echo '$("#'.$accordion_id.'").find("div.panel-body").addClass("disabled-div");';
				}
			}
		}
	?>
});
$(document).on('change', 'select[name="businessid"]', function() { business_select(this.value); });
$(document).on('change', 'select[name="siteid"]', function() { site_select(this.value); });
$(document).on('change', 'select[name="service_cat[]"]', function() { category_filter(this); });
$(document).on('change', 'select[name="service_head[]"]', function() { set_category(this); });
$(document).on('change', 'select[name="equip_cat_value[]"]', function() { equip_filter(this); });
$(document).on('change', 'select[name="equipment_id[]"]', function() { equip_filter(this); });
$(document).on('change', 'select[name="equip_make_value[]"]', function() { equip_filter(this); });
$(document).on('change', 'select[name="equip_model_value[]"]', function() { equip_filter(this); });

function business_select(id) {
	if(id != '') {
		$.ajax({
			data: { business: id, site: $('[name=siteid]').val(), contact: $('[name=contactid]').data('value') },
			method: 'POST',
			url: 'site_work_orders_ajax.php?fill=businessid',
			success: function(result) {
				var arr = result.split('#*#');
				$('[name=siteid]').empty().html(arr[0]).trigger('change.select2');
				$('[name=contactid]').empty().html(arr[1]).trigger('change.select2');
			}
		});
	}
	else {
		$('[name=siteid]').empty().html('<option>Please select a business first</option>').trigger('change.select2');
		$('[name=contactid]').empty().html('<option>Please select a site first</option>').trigger('change.select2');
	}
}
function site_select(id) {
	if(id != '') {
		$.ajax({
			data: { business: $('[name=businessid]').val(), site: id, contact: $('[name=contactid]').data('value') },
			method: 'POST',
			url: 'site_work_orders_ajax.php?fill=siteid',
			success: function(result) {
				$('[name=contactid]').empty().html(result).trigger('change.select2');
				var location = $('[name=siteid] option:selected').data('name');
				if($('[name=site_location]').val() == '') {
					$('[name=site_location]').val(location);
				}
				var description = $('[name=siteid] option:selected').data('location');
				if($('[name=site_description]').val() == '') {
					$('[name=site_description]').val(description);
				}
				var google = $('[name=siteid] option:selected').data('google');
				if($('[name=google_map_link]').val() == '') {
					$('[name=google_map_link]').val(google);
				}
			}
		});
	}
	else {
		$('[name=contactid]').empty().html('<option>Please select a site first</option>').trigger('change.select2');
	}
}
function category_filter(cat) {
	var val = cat.value;
	if(val == 'custom') {
		$(cat).closest('.form-group').find('input[name^=service_]').removeAttr('disabled').closest('div').show();
		$(cat).closest('.form-group').find('select').attr('disabled','disabled').closest('div').hide();
	} else {
		$(cat).closest('.form-group').find('[name="service_head[]"] option').each(function() {
			if($(this).data('category') == val || this.value == 'custom') {
				$(this).show();
			} else {
				$(this).hide();
			}
		});
		$(cat).closest('.form-group').find('[name="service_head[]"]').trigger('change.select2');
	}
}
function set_category(heading) {
	if(heading.value == 'custom') {
		$(heading).closest('.form-group').find('input[name="service_head[]"]').removeAttr('disabled').closest('div').show();
		$(heading).closest('div').attr('disabled','disabled').closest('div').hide();
	} else {
		$(heading).closest('.form-group').find('[name="service_cat[]"]').val($(heading).find('option:selected').data('category')).trigger('change.select2');
	}
}
function equip_filter(row) {
	row = $(row).closest('.form-group');
	var unit = $(row).closest('.form-group').find('[name="equipment_id[]"]');
	if(unit.val() != '') {
		unit = unit.find('option:selected');
		$(row).find('[name="equip_cat_value[]"]').val(unit.data('category')).trigger('change.select2');
		$(row).find('[name="equip_type_value[]"]').val(unit.data('type')).trigger('change.select2');
		$(row).find('[name="equip_make_value[]"]').val(unit.data('make')).trigger('change.select2');
		$(row).find('[name="equip_model_value[]"]').val(unit.data('model')).trigger('change.select2');
	} else {
		var cat = $(row).find('[name="equip_cat_value[]"]').val();
		var type = $(row).find('[name="equip_type_value[]"]').val();
		if(cat != '') {
			unit.find('option').each(function() {
				if($(this).data('category') != cat && $(this).val() != '') {
					$(this).hide();
				}
			});
		}
		if(type != '') {
			unit.find('option').each(function() {
				if($(this).data('type') != type && $(this).val() != '') {
					$(this).hide();
				}
			});
		}
		unit.trigger('change.select2');
	}
}
function addCrew() {
	var staff_crew_count = $('#staff_crew_count').val();
	var staff_crew_curr = staff_crew_count - 1;
	var clone = $('.form-group.crew').last().clone();
	clone.find('.form-control').val('');
	resetChosen(clone.find("select"));
	clone.find('[name="staff_crew_' + staff_crew_curr + '[]"]').attr("name", "staff_crew_" + staff_crew_count + "[]");
	clone.find('[name="staff_positions_' + staff_crew_curr + '"]').attr("name", "staff_positions_" + staff_crew_count);
	clone.find('[name="staff_estimate_hours_' + staff_crew_curr + '"]').attr("name", "staff_estimate_hours_" + staff_crew_count);
	clone.find('[name="staff_estimate_days_' + staff_crew_curr + '"]').attr("name", "staff_estimate_days_" + staff_crew_count);
	$('#staff_crew_count').val(parseInt(staff_crew_count) + 1);
	$('#crew_btn').before(clone);
}
function addService() {
	var clone = $('.form-group.service').last().clone();
	clone.find('.form-control').val('');
	clone.find('input[name^=service_]').attr('disabled','disabled').closest('div').hide();
	clone.find('select').removeAttr('disabled').closest('div').show();
	resetChosen(clone.find("select"));
	$('#service_btn').before(clone);
}
function addEquip() {
	var clone = $('.form-group.equip').last().clone();
	clone.find('.form-control').val('');
	resetChosen(clone.find("select"));
	$('#equip_btn').before(clone);
}
function add_staff_summary() {
	var clone = $('.form-group.summary').last().clone();
	clone.find('.form-control').val('');
	resetChosen(clone.find("select"));
	clone.find('[name="summary_hours[]"]').val(0);
	clone.find('[name="summary_timer_start[]"]').val('NEW');
	clone.find('[name="summary_disabled[]"]').val('');
	$('#summary_btn').before(clone);
}
function display_tasks(link) {
	if($('#multi_assign_checked').is(':checked')) {
		$(link).closest('div').find('input').prop('checked',true).change();
		return;
	}
	$('#collapse_assign_crew').find('.assign_crew_btn').hide();
	$(link).parents('.assign_crew_btn').show();
	$('#assign_tasks').show();
	$('#assign_task_crew').val($(link).data('crew'));
	$('#assign_task_crew_id').val($(link).data('id'));
}
function hide_tasks() {
	$('#collapse_assign_crew').find('.assign_crew_btn.link').show();
	$('#assign_tasks').hide();
	$('#assign_task_crew').val('');
	$('#assign_task_crew_id').val('');
	$('[name="multi_assign_crew[]"]').removeAttr('checked').hide();
	$('#multi_assign_checked').removeAttr('checked');
}
function assign_task_crew(link) {
	var task = $(link).data('task');
	var crew = $('#assign_task_crew').val();
	if(crew == 'NEW') {
		add_staff_summary();
		var crew = $('#assign_task_crew_id').val();
		$('[name="summary_staff[]"]').last().val(crew).trigger('change.select2');
		$('[name="summary_task[]"]').last().val(task).trigger('change.select2');
	} else if(crew.substr(0,5) == 'MULTI') {
		var list = crew.substr(6).split(',');
		for(var i = 0; i < list.length; i++) {
			if(list[i] > 0) {
				add_staff_summary();
				$('[name="summary_staff[]"]').last().val(list[i]).trigger('change.select2');
				$('[name="summary_task[]"]').last().val(task).trigger('change.select2');
			}
		}
	} else {
		$('[name="summary_task[]"][data-row='+crew+']').val(task);
	}
	$('#assign_submit').click();
}
function unassign_crew(link) {
	var task = $(link).data('task');
	var id = $(link).data('id');
	$('[name="summary_task[]"]').each(function() {debugger;
		if($(this).val() == task && $(this).closest('.form-group.summary').find('[name="summary_staff[]"]').val() == id) {
			//console.log('Remove '+$(this).data('row'));
			$(this).closest('.form-group.summary').remove();
		}
	});
	$('#unassign_submit').click();
}
function addTime(btn) {
	var group = $(btn).closest('.form-group');
	var clone = group.clone();
	clone.find('input').val('');
	group.after(clone);
	$(btn).remove();
	group.find('.col-sm-7').removeClass('col-sm-7').addClass('col-sm-8');
	$('.datetimepicker').timepicker({
		controlType: 'select',
		oneLine: true,
		timeFormat: 'hh:mm tt'
	});
}
function multi_assign(checked) {
	if(checked) {
		hide_tasks();
		$('.assign_crew_btn.assigned').hide();
		$('[name="multi_assign_crew[]"]').show();
		$('#assign_tasks').show();
		$('#multi_assign_checked').prop('checked',true);
	} else {
		$('.assign_crew_btn.assigned').show();
		$('[name="multi_assign_crew[]"]').hide();
		hide_tasks();
	}
}
function set_assigned_crew() {
	$('#assign_task_crew').val('MULTI,'+$('[name="multi_assign_crew[]"]:checked').map(function() { return this.value; }).get().join(','));
}
function track_time() {
	$.ajax({
		url: 'site_work_orders_ajax.php?fill=time_tracking&id=<?= $workorderid ?>',
		success: function(response) {
			var timers = response.split('#*#');
			timers.forEach(function(value, row) {
				$($('[name="summary_hours[]"]').get(row)).val(value);
			});
			var timer = $('#time_tracker');
			if(timer.text() == 'Start Time Tracking') {
				$('[name="summary_hours[]"][data-disabled=false]').prop('readonly','readonly');
				timer.text('Stop Time Tracking');
			} else {
				$('[name="summary_hours[]"]').removeAttr('readonly');
				$('[name="summary_timer_start[]"]').val(0);
				timer.text('Start Time Tracking');
			}
		}
	});
}
var signed_out = false;
function stop_time_sign_out() {
	if($('#time_tracker').text() == 'Stop Time Tracking') {
		if(!signed_out) {
			track_time();
			signed_out = true;
		}
		setTimeout(function() {
			$('[name=submit][value=sign_out]').click();
		}, 1000);
		return false;
	} else {
		return true;
	}
}
</script>

<style>
.disabled-div {
	pointer-events: none;
}
</style>

<div class="container">
  <div class="row">
		<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

		<h1>View Work Order #<?= $id_label ?></h1>

		<div class="form-group">
			<div class="col-sm-6">
				<a href="<?= $from_site_url ?>" class="btn brand-btn btn-lg">Back to Dashboard</a>
			</div>
			<div class="col-sm-6">
				<button type="submit" name="submit" value="sign_out" onclick="return stop_time_sign_out();" class="btn brand-btn btn-lg pull-right">Sign Out</button>
				<button onclick="track_time(); return false;" id="time_tracker" class="btn brand-btn btn-lg pull-right"><?= $summary_timer > 0 ? 'Stop' : 'Start' ?> Time Tracking</button>
			</div>
			<div class="clearfix"></div>
		</div>

		<div class="form-group">
			<div class="col-sm-6">
				<button onclick="$('.active_tab').removeClass('active_tab'); $(this).addClass('active_tab'); $('.my_crew').hide(); $('.assign_crew').show(); $('.work_order').hide(); return false;" class="btn brand-btn btn-lg crew_tab <?= ($_POST['submit'] != 'unassign' ? 'active_tab' : '') ?>">Assign Crew</button>
				<button onclick="$('.active_tab').removeClass('active_tab'); $(this).addClass('active_tab'); $('.my_crew').show(); $('.assign_crew').hide(); $('.work_order').hide(); return false;" class="btn brand-btn btn-lg crew_tab <?= ($_POST['submit'] == 'unassign' ? 'active_tab' : '') ?>">My Crew</button>
				<button onclick="$('.active_tab').removeClass('active_tab'); $(this).addClass('active_tab'); $('.my_crew').hide(); $('.assign_crew').hide(); $('.work_order').show(); return false;" class="btn brand-btn btn-lg work_order_tab">Site Work Order</button>
			</div>
			<div class="clearfix"></div>
		</div>
		<!--Quick Assign Crew Tasks-->
		<div class="assign_crew" <?= ($_POST['submit'] != 'unassign' ? '' : 'style="display:none;"') ?>>
			<div class="panel-group" id="accordion1">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion1" href="#collapse_assign_crew" >
								Assign Crew <span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_assign_crew" class="panel-collapse collapse in">
						<style>
						input[type='checkbox']:checked+label a {
							border-width: 0.35em; font-weight: bold;
						}
						</style>
						<div class="panel-body">
							<div class="pull-left" style="font-size:1.25em;"><label><input type="checkbox" id="multi_assign_checked" onchange="multi_assign(this.checked);"><b>Assign Multiple Staff</b></label></div><div class="clearfix"></div>
							<h3>Crew Assignments</h3>
							<div class="clearfix"><em>Listed below are staff that can be assigned to the crew. To assign additional crew members, click the person's name.</em></div>
							<div class="clearfix">
								<?php $crew_input_id = 0;
								foreach($staff_list as $crew) { ?>
									<div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12 assign_crew_btn">
										<input type="checkbox" id="checkbox_<?= $crew_input_id ?>" name="multi_assign_crew[]" value="<?= $crew ?>" onchange="set_assigned_crew();" style="display:none;">
										<label style="width:100%;" for="checkbox_<?= $crew_input_id++ ?>">
											<a href="" onclick="display_tasks(this); return false;" data-crew="NEW" data-id="<?= $crew ?>" style="display:block; padding-top:1em; width:100%;"><?= get_contact($dbc, $crew) ?></a>
										</label>
									</div>
								<?php } ?>
							</div>
							<div id="assign_tasks" class="form-group" style="display:none">
								<input type="hidden" id="assign_task_crew" value="">
								<input type="hidden" id="assign_task_crew_id" value="">
								<div class="clearfix"><em>Select a task to assign it to a crew.</em><button onclick="hide_tasks(); return false;" class="btn brand-btn pull-right">Back to Staff List</button></div>
								<?php foreach($task_list as $task_group) {
									$task_group = explode('*#*',$task_group);
									echo "<h4>".$task_group[0]." Tasks</h4>";
									unset($task_group[0]); ?>
									<div class="clearfix">
										<?php foreach($task_group as $task_name) { ?>
											<div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12 work_btn">
												<a href="" onclick="assign_task_crew(this); return false;" data-task="<?= $task_name ?>"><?= $task_name ?></a>
											</div>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
							<button style="display:none" type="submit" name="submit" id="assign_submit" value="assign"></button>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<!--View/Remove Assigned Crew Tasks-->
		<div class="my_crew" <?= ($_POST['submit'] == 'unassign' ? '' : 'style="display:none;"') ?>>
			<div class="panel-group" id="accordion1">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion1" href="#collapse_assign_crew" >
								My Crew <span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_assign_crew" class="panel-collapse collapse in">
						<style>
						input[type='checkbox']:checked+label a {
							border-width: 0.35em; font-weight: bold;
						}
						</style>
						<div class="panel-body">
							<h3>Crew Assignments</h3>
							<div class="clearfix"><em>Listed below are staff that have been assigned to the crew. To unassign a crew member, click the person's name.</em></div>
							<div class="clearfix">
								<?php foreach($staff_summary as $j => $summary) {
									if($summary != '') {
										$summary = explode('**#**', $summary); ?>
										<div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12 assign_crew_btn assigned">
											<a href="" onclick="if(confirm('Are you sure you want to unassign this crew member?')) { unassign_crew(this); } return false;" data-crew="<?= $j ?>" data-id="<?= $summary[0] ?>" data-task="<?= (empty($summary[1]) ? 'Needs Assigned' : $summary[1]) ?>"><?= get_contact($dbc, $summary[0]) ?><img src="<?= WEBSITE_URL ?>/img/remove.png" class="pull-right"><br /><small><em>(Assigned Task: <?= (empty($summary[1]) ? 'Needs Assigned' : $summary[1]) ?>)</em></small></a>
										</div>
									<?php }
								} ?>
							</div>
							<button style="display:none" type="submit" name="submit" id="unassign_submit" value="unassign"></button>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<!--View Work Order Details-->
		<div class="work_order" style="display:none;">
			<div class="panel-group" id="accordion2">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_who" >
								Who: <span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_who" class="panel-collapse collapse">
						<div class="panel-body">
							<div class="form-group"><label class="col-sm-4 control-label">Business:</label>
								<div class="col-sm-8"><select name="businessid" disabled class="form-control chosen-select-deselect"><option></option>
								<?php $business_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT `contactid`, `name` FROM `contacts` WHERE `category`='Business'"),MYSQLI_ASSOC));
								foreach($business_list as $id) {
									$name = get_client($dbc, $id);
									echo "<option ".($businessid == $id ? 'selected' : '')." value='$id'>$name</option>";
								}
								?></select></div></div>
							<div class="form-group"><label class="col-sm-4 control-label">Site:</label>
								<input type="hidden" name="siteid" value="<?= $siteid ?>">
								<div class="col-sm-8"><select name="siteid" disabled data-value="<?= $siteid ?>" class="form-control chosen-select-deselect"></select></div></div>
							<div class="form-group"><label class="col-sm-4 control-label">Contact:</label>
								<div class="col-sm-8"><select name="contactid" disabled data-value="<?= $contactid ?>" class="form-control chosen-select-deselect"></select></div></div>
						</div>
					</div>
				</div>
				
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_staff" >
								Staff & Crew: <span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_staff" class="panel-collapse collapse">
						<div class="panel-body">
							<div class="form-group"><label class="col-sm-4 control-label">Company Team Lead:</label>
								<div class="col-sm-8"><select disabled name="staff_lead" class="form-control chosen-select-deselect"><option></option>
									<?php foreach($staff_list as $id) {
										echo "<option ".($id == $staff_lead ? 'selected' : '')." value='$id'>".get_contact($dbc, $id)."</option>";
									} ?>
									</select></div></div>
							<div class="form-group hide-titles-mob">
								<div class="col-sm-3 text-center">Staff</div>
								<div class="col-sm-3 text-center">Position</div>
								<div class="col-sm-2 text-center">Estimated Hours</div>
								<div class="col-sm-2 text-center">Estimated Days</div>
							</div>
							<br /><div class="clearfix"></div>
							<?php 
							$staff_crew_count = 0;
							foreach($staff_crew as $j => $crew) { ?>
								<div class="form-group crew">
									<div class="col-sm-3"><label class="show-on-mob">Staff:</label><select multiple name="staff_crew_<?php echo $staff_crew_count; ?>[]" class="form-control chosen-select-deselect"><option></option>
										<?php foreach($staff_list as $id) {
											echo "<option ".($id == $crew ? 'selected' : '')." value='$id'>".get_contact($dbc, $id)."</option>";
										} ?></select></div>
									<div class="col-sm-3"><label class="show-on-mob">Position:</label><select name="staff_positions_<?php echo $staff_crew_count; ?>" class="form-control chosen-select-deselect"><option></option>
										<?php $position_list = mysqli_query($dbc, "SELECT `position_id`, `name` FROM `positions` WHERE `deleted`=0 ORDER BY `name`");
										while($row = mysqli_fetch_array($position_list)) {
											echo "<option ".($row['position_id'] == $staff_positions[$j] ? 'selected' : '')." value='".$row['position_id']."'>".$row['name']."</option>";
										} ?></select></div>
									<div class="col-sm-2"><label class="show-on-mob">Estimated Hours:</label><input type="number" class="form-control" name="staff_estimate_hours_<?php echo $staff_crew_count; ?>" value="<?php echo $staff_estimate_hours[$j]; ?>" min="0" step="any"></div>
									<div class="col-sm-2"><label class="show-on-mob">Estimated Days:</label><input type="number" class="form-control" name="staff_estimate_days_<?php echo $staff_crew_count; ?>" value="<?php echo $staff_estimate_days[$j]; ?>" min="0" step="any"></div>
									<div class="col-sm-1"><button class="btn brand-btn" onclick="$(this).closest('.form-group').remove();">Delete</button></div>
								</div>
							<?php 
							$staff_crew_count++;
							} ?>
							<button class="btn brand-btn pull-right" id="crew_btn" onclick="addCrew(); return false;">Add Crew</button>
							<input type="hidden" id="staff_crew_count" name="staff_crew_count" value="<?php echo count($staff_crew); ?>">
						</div>
					</div>
				</div>
				
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_services" >
								Services: <span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_services" class="panel-collapse collapse">
						<div class="panel-body">
							<div class="form-group hide-titles-mob">
								<div class="col-sm-6 text-center">Category</div>
								<div class="col-sm-5 text-center">Heading</div>
								<div class="col-sm-1 text-center">Checklist</div>
							</div>
							<?php $service_cat = explode('#*#',$service_cat);
							$service_head = explode('#*#',$service_head);
							foreach($service_cat as $j => $cat) { ?>
								<div class="form-group service">
									<div class="col-sm-6"><label class="show-on-mob">Category:</label><select name="service_cat[]" disabled class="form-control chosen-select-deselect">
									<option value="custom">Add Custom</option>
									<option <?= (!empty($cat) ? 'selected' : '') ?> value='<?php echo $cat; ?>'><?php echo $cat; ?></option>
									<?php $service_categories = mysqli_query($dbc, "SELECT DISTINCT `category` FROM `services` ORDER BY `category`");
									while($row = mysqli_fetch_array($service_categories)) {
										echo "<option value='".$row['category']."'>".$row['category']."</option>";
									} ?></select></div>
								<div class="col-sm-5" style="display:none;"><label class="show-on-mob">Category:</label><input disabled name="service_cat[]" type="text" class="form-control"></div>
								<div class="col-sm-5"><label class="show-on-mob">Heading:</label><select name="service_head[]" class="form-control chosen-select-deselect">
									<option value="custom">Add Custom</option>
									<option <?= (!empty($service_head[$j]) ? 'selected' : '') ?> value="<?php echo $service_head[$j]; ?>"><?php echo $service_head[$j]; ?></option>
									<?php $service_headings = mysqli_query($dbc, "SELECT `category`, `heading` FROM `services` ORDER BY `category`, `heading`");
									while($row = mysqli_fetch_array($service_headings)) {
										echo "<option data-category='".$row['category']."' value='".$row['heading']."'>".$row['heading']."</option>";
									} ?></select></div>
								<div class="col-sm-5" style="display:none;"><label class="show-on-mob">Heading:</label><input disabled name="service_head[]" type="text" class="form-control"></div>
								<div class="col-sm-1 text-center"><input type="checkbox" <?= ($service_check[$j] == 'verified' ? 'checked' : '') ?> name="service_check[]" value="verified"><input type="checkbox" <?= ($service_check[$j] != 'verified' ? 'checked' : '') ?> name="service_check[]" value="unverified" style="display:none;"></div>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_equip" >
								Equipment: <span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_equip" class="panel-collapse collapse">
						<div class="panel-body">
							<?php $equip_fields = ','.mysqli_fetch_array(mysqli_query($dbc, "SELECT GROUP_CONCAT(`equipment`) FROM `field_config_equipment` WHERE `accordion` IS NOT NULL"))[0].','; ?>
							<div class="form-group hide-titles-mob">
								<?php if(strpos($equip_fields, ',Category,') !== FALSE) { ?>
									<div class="col-sm-2 text-center">Category</div>
								<?php } ?>
								<!--<?php if(strpos($equip_fields, ',Type,') !== FALSE) { ?>
									<div class="col-sm-2 text-center">Type</div>
								<?php } ?>-->
								<?php if(strpos($equip_fields, ',Unit #,') !== FALSE) { ?>
									<div class="col-sm-2 text-center">Unit #</div>
								<?php } ?>
								<?php if(strpos($equip_fields, ',Make,') !== FALSE) { ?>
									<div class="col-sm-1 text-center">Make</div>
								<?php } ?>
								<?php if(strpos($equip_fields, ',Model,') !== FALSE) { ?>
									<div class="col-sm-1 text-center">Model</div>
								<?php } ?>
								<?php if(strpos($equip_fields, ',Category,') === FALSE && strpos($equip_fields, ',Type,') === FALSE && strpos($equip_fields, ',Unit #,') === FALSE && strpos($equip_fields, ',Make,') === FALSE && strpos($equip_fields, ',Model,') === FALSE) { ?>
									<div class="col-sm-8 text-center">Equipment</div>
								<?php } ?>
								<div class="col-sm-1 text-center">Rate</div>
								<div class="col-sm-1 text-center">Status</div>
								<div class="col-sm-1 text-center">Checklist</div>
							</div>
							<?php $equipment_id = explode(',',$equipment_id);
							$equipment_rate = explode(',',$equipment_rate);
							$equipment_status = explode(',',$equipment_status);
							foreach($equipment_id as $j => $id) {
								$equip_status = mysqli_fetch_array(mysqli_query($dbc, "SELECT `status` FROM `equipment` WHERE `equipmentid`='$id' AND `status` != '' UNION SELECT '".$equipment_status[$j]."'"))['status']; ?>
								<div class="form-group equip">
								<?php if(strpos($equip_fields, ',Category,') !== FALSE) { ?>
									<div class="col-sm-2"><label class="show-on-mob">Category:</label>
										<select name="equip_cat_value[]" disabled class="form-control chosen-select-deselect">
										<option></option>
										<?php $equip_list = mysqli_query($dbc, "SELECT `category`, `type`, `unit_number`, `make`, `model`, `equipmentid` FROM `equipment` ORDER BY `category`, `type`, `unit_number`, `make`, `model`, `equipmentid`");
										$category = '';
										while($row = mysqli_fetch_array($equip_list)) {
											if($category != $row['category']) {
												echo "<option ".($row['equipmentid'] == $id ? 'selected' : '')." value='".$row['category']."'>".$row['category']."</option>";
											}
											$category = $row['category'];
										} ?></select></div>
								<?php } ?>
								<!--<?php if(strpos($equip_fields, ',Type,') !== FALSE) { ?>
									<div class="col-sm-2"><label class="show-on-mob">Type:</label>
										<select name="equip_type_value[]" disabled class="form-control chosen-select-deselect" onchange="equip_filter(this);">
										<option></option>
										<?php mysqli_data_seek($equip_list, 0);
										$type = '';
										while($row = mysqli_fetch_array($equip_list)) {
											if($type != $row['type']) {
												echo "<option ".($row['equipmentid'] == $id ? 'selected' : '')." value='".$row['type']."'>".$row['type']."</option>";
											}
											$type = $row['type'];
										} ?></select></div>
								<?php } ?>-->
								<?php if(strpos($equip_fields, ',Unit #,') !== FALSE) { ?>
									<div class="col-sm-2"><label class="show-on-mob">Unit #:</label>
										<select name="equipment_id[]" disabled class="form-control chosen-select-deselect">
										<option></option>
										<?php mysqli_data_seek($equip_list, 0);
										while($row = mysqli_fetch_array($equip_list)) {
											echo "<option data-category='".$row['category']."' data-type='".$row['type']."' data-make='".$row['make']."' data-model='".$row['model']."' ".($row['equipmentid'] == $id ? 'selected' : '')." value='".$row['equipmentid']."'>".$row['unit_number']."</option>";
										} ?></select></div>
								<?php } ?>
								<?php if(strpos($equip_fields, ',Make,') !== FALSE) { ?>
									<div class="col-sm-1"><label class="show-on-mob">Make:</label>
										<select name="equip_make_value[]" disabled class="form-control chosen-select-deselect">
										<option></option>
										<?php mysqli_data_seek($equip_list, 0);
										$make = '';
										while($row = mysqli_fetch_array($equip_list)) {
											if($make != $row['make']) {
												echo "<option ".($row['equipmentid'] == $id ? 'selected' : '')." value='".$row['make']."'>".$row['make']."</option>";
											}
											$make = $row['make'];
										} ?></select></div>
								<?php } ?>
								<?php if(strpos($equip_fields, ',Model,') !== FALSE) { ?>
									<div class="col-sm-1"><label class="show-on-mob">Model:</label>
										<select name="equip_model_value[]" disabled class="form-control chosen-select-deselect">
										<option></option>
										<?php mysqli_data_seek($equip_list, 0);
										$model = '';
										while($row = mysqli_fetch_array($equip_list)) {
											if($model != $row['model']) {
												echo "<option ".($row['equipmentid'] == $id ? 'selected' : '')." value='".$row['model']."'>".$row['model']."</option>";
											}
											$model = $row['model'];
										} ?></select></div>
								<?php } ?>
								<?php if(strpos($equip_fields, ',Category,') === FALSE && strpos($equip_fields, ',Type,') === FALSE && strpos($equip_fields, ',Unit #,') === FALSE && strpos($equip_fields, ',Make,') === FALSE && strpos($equip_fields, ',Model,') === FALSE) { ?>
									<div class="col-sm-8"><label class="show-on-mob">Equipment:</label>
										<select name="equipment_id[]" disabled class="form-control chosen-select-deselect">
											<option></option>
											<?php mysqli_data_seek($equip_list, 0);
											while($row = mysqli_fetch_array($equip_list)) {
												echo "<option value='".$row['equipmentid']."'>".$row['category'].' '.$row['type'].' '.$row['make'].' '.$row['model'].' '.$row['unit_number'].(!empty($row['label']) ? ': '.$row['label'] : '')."</option>";
											} ?></select></div>
								<?php } ?>
									<div class="col-sm-1"><label class="show-on-mob">Rate:</label>
										<input type="number" disabled name="equipment_rate[]" class="form-control" value="<?= $equipment_rate[$j] ?>" min="0" step="any"></div>
									<div class="col-sm-1"><label class="show-on-mob">Status:</label>
										<input type="hidden" name="equipmentid[]" value="<?= $id ?>">
										<select name="equipment_status[]" class="form-control chosen-select-deselect">
										<option></option>
										<option value='Active' <?= ($equip_status=='Active' ? 'selected="selected"' : '') ?> >Active</option>
										<option value='In Service' <?= ($equip_status=='In Service' || $equip_status == 'In Repair' ? 'selected="selected"' : '') ?> >In Service</option>
										<option value='Service Required' <?= ($equip_status=='Service Required' ? 'selected="selected"' : '') ?> >Service Required</option>
										<option value='On Site' <?= ($equip_status=='On Site' ? 'selected="selected"' : '') ?> >On Site</option>
										<option value='Inactive' <?= ($equip_status=='Inactive' ? 'selected="selected"' : '' )?> >Inactive</option>
										<option value='Sold' <?= ($equip_status=='Sold' ? 'selected="selected"' : '')?> >Sold</option>
										</select></div>
									<div class="col-sm-1 text-center"><label class="show-on-mob">Checklist:</label>
										<input type="checkbox" <?= ($equip_check[$j] == 'verified' ? 'checked' : '') ?> name="equip_check[]" value="verified"><input type="checkbox" <?= ($equip_check[$j] != 'verified' ? 'checked' : '') ?> name="equip_check[]" value="unverified" style="display:none;"></div>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_material" >
								Materials: <span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_material" class="panel-collapse collapse">
						<div class="panel-body">
							<div class="form-group hide-titles-mob">
								<div class="col-sm-4 text-center">Category</div>
								<div class="col-sm-4 text-center">Type</div>
								<div class="col-sm-3 text-center">Quantity</div>
								<div class="col-sm-1 text-center">Checklist</div>
							</div>
							<?php $material_id = explode('#*#',$material_id);
							$material_qty = explode(',',$material_qty);
							foreach($material_id as $j => $material) { ?>
								<div class="form-group material">
									<div class="col-sm-4"><label class="show-on-mob">Category:</label><select name="material_category[]" disabled class="form-control chosen-select-deselect"><option></option>
										<?php $material_list = mysqli_query($dbc, "SELECT `materialid`, `category`, `name` FROM `material` ORDER BY `name`");
										$category = '';
										while($row = mysqli_fetch_array($material_list)) {
											if($category != $row['category']) {
												echo "<option ".($row['materialid'] == $material ? 'selected' : '')." value='".$row['materialid']."'>".$row['name']."</option>";
											}
											$category = $row['category'];
										} ?></select></div>
									<div class="col-sm-4"><label class="show-on-mob">Type:</label><select name="material_id[]" disabled class="form-control chosen-select-deselect"><option></option>
										<?php mysqli_data_seek($material_list, 0);
										while($row = mysqli_fetch_array($material_list)) {
											echo "<option ".($row['materialid'] == $material ? 'selected' : '')." value='".$row['materialid']."'>".$row['name']."</option>";
										} ?></select></div>
									<div class="col-sm-3"><label class="show-on-mob">Quantity:</label><input type="number" disabled class="form-control" name="material_qty[]" value="<?php echo $material_qty[$j]; ?>" min="0"></div>
									<div class="col-sm-1 text-center"><input type="checkbox" <?= ($material_check[$j] == 'verified' ? 'checked' : '') ?> name="material_check[]" value="verified"><input type="checkbox" <?= ($material_check[$j] != 'verified' ? 'checked' : '') ?> name="material_check[]" value="unverified" style="display:none;"></div>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_where" >
								Where: <span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_where" class="panel-collapse collapse">
						<div class="panel-body">
							<div class="form-group"><label class="col-sm-4 control-label">SL:</label>
								<div class="col-sm-8"><input disabled name="site_location" class="form-control" value="<?= $site_location ?>"></div></div>
							<div class="form-group"><label class="col-sm-4 control-label">LSD:</label>
								<div class="col-sm-8"><input disabled name="site_description" class="form-control" value="<?= $site_description ?>"></div></div>
							<div class="form-group"><label class="col-sm-4 control-label">Google Maps:</label>
								<div class="col-sm-8"><a href="<?= $google_map_link ?>">Open Map</a></div></div>
						</div>
					</div>
				</div>
				
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_when" >
								When: <span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_when" class="panel-collapse collapse">
						<div class="panel-body">
							<div class="form-group"><label class="col-sm-4 control-label">Start Date:</label>
								<div class="col-sm-8"><input name="work_start_date" class="form-control datepicker" value="<?= $work_start_date ?>"></div></div>
							<div class="form-group"><label class="col-sm-4 control-label">End Date:</label>
								<div class="col-sm-8"><input name="work_end_date" class="form-control datepicker" value="<?= $work_end_date ?>"></div></div>
							<?php $work_start_arr = explode(',',$work_start_time);
							$work_details_arr = explode(',',$work_start_details);
							foreach($work_start_arr as $j => $start_work) { ?>
								<div class="form-group"><label class="col-sm-4 control-label">Start Time:</label>
									<?php if($j == count($work_start_arr) - 1) { ?>
										<div class="col-sm-7"><div class="col-sm-4"><input name="work_start_time[]" class="form-control datetimepicker" value="<?= $start_work ?>"></div>
											<div class="col-sm-2">Details</div><div class="col-sm-6"><input name="work_start_details[]" class="form-control" value="<?= $work_details_arr[$j] ?>"></div></div>
										<button class="btn brand-btn" onclick="addTime(this); return false;">Add Time</button>
									<?php } else { ?>
										<div class="col-sm-8"><div class="col-sm-4"><input name="work_start_time[]" class="form-control datetimepicker" value="<?= $start_work ?>"></div>
											<div class="col-sm-2">Details</div><div class="col-sm-6"><input name="work_start_details[]" class="form-control" value="<?= $work_details_arr[$j] ?>"></div></div>
									<?php } ?>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_docs" >
								Support Documents: <span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_docs" class="panel-collapse collapse">
						<div class="panel-body">
						<?php
						if(!empty($_GET['workorderid'])) {
							$query_check_credentials = "SELECT * FROM site_work_document WHERE workorderid='$workorderid' ORDER BY documentid DESC";
							$result = mysqli_query($dbc, $query_check_credentials);
							$num_rows = mysqli_num_rows($result);
							if($num_rows > 0) {
								echo "<table class='table table-bordered'>
								<tr class='hidden-xs hidden-sm'>
								<th>Type</th>
								<th>Document/Link</th>
								<th>Date</th>
								<th>Uploaded By</th>
								<th>Delete</th>
								</tr>";
								while($row = mysqli_fetch_array($result)) {
									echo '<tr>';
									$by = $row['created_by'];
									echo '<td data-title="Schedule">'.$row['type'].'</td>';
									if($row['document'] != '') {
										echo '<td data-title="Schedule"><a href="download/'.$row['document'].'" target="_blank">'.$row['document'].'</a></td>';
									} else {
										echo '<td data-title="Schedule"><a target="_blank" href=\''.$row['link'].'\'">Link</a></td>';
									}
									echo '<td data-title="Schedule">'.$row['created_date'].'</td>';
									echo '<td data-title="Schedule">'.get_staff($dbc, $by).'</td>';
									echo '<td data-title="Schedule"><a href=\'../delete_restore.php?action=delete&ticketdocid='.$row['ticketdocid'].'&ticketid='.$row['ticketid'].'\' onclick="return confirm(\'Are you sure?\')">Delete</a></td>';
									echo '</tr>';
								}
								echo '</table>';
							}
						}
						?>
							<div class="form-group">
								<label for="additional_note" class="col-sm-4 control-label">Upload Document(s):
										<span class="popover-examples list-inline">&nbsp;
										<a href="#job_file" data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas"><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
										</span>
								</label>
								<div class="col-sm-8">

									<div class="enter_cost additional_doc clearfix">
										<div class="clearfix"></div>

										<div class="form-group clearfix">
											<div class="col-sm-5">
												<input name="upload_document[]" multiple type="file" data-filename-placement="inside" class="form-control" />
											</div>
										</div>

									</div>

									<div id="add_here_new_doc"></div>

									<div class="form-group triple-gapped clearfix">
										<div class="col-sm-offset-4 col-sm-8">
											<button id="add_row_doc" class="btn brand-btn pull-left">Add Another Document</button>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label for="additional_note" class="col-sm-4 control-label">Link(s):<br><em>(e.g. - https://www.google.com)</em>
								</label>
								<div class="col-sm-8">

									<div class="enter_cost additional_link clearfix">
										<div class="clearfix"></div>

										<div class="form-group clearfix">
											<div class="col-sm-5">
												<input name="support_link[]" type="text" class="form-control">
											</div>
										</div>

									</div>

									<div id="add_here_new_link"></div>

									<div class="form-group triple-gapped clearfix">
										<div class="col-sm-offset-4 col-sm-8">
											<button id="add_row_link" class="btn brand-btn pull-left">Add Another Link</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_checklist" >
								Site Checklist: <span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_checklist" class="panel-collapse collapse">
						<div class="panel-body">
							<?php include('add_work_order_checklist.php'); ?>
						</div>
					</div>
				</div>
				
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_pos" >
								Purchase Orders: <span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_pos" class="panel-collapse collapse">
						<div class="panel-body">
							<h3>Attach Purchase Orders</h3>
							<?php include('add_work_order_po.php'); ?>
						</div>
					</div>
				</div>
				
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_comments" >
								Comments: <span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

						<div id="collapse_comments" class="panel-collapse collapse">
							<div class="panel-body"><div class="col-md-12">
							   <?php if($workorderid != 'NEW') {
									$query_check_credentials = "SELECT * FROM site_work_comment WHERE workorderid='$workorderid' AND type='note' ORDER BY commentid DESC";
									$result = mysqli_query($dbc, $query_check_credentials);
									$num_rows = mysqli_num_rows($result);
									if($num_rows > 0) {
										echo "<table class='table table-bordered'>
										<tr class='hidden-xs hidden-sm'>
										<th>Note</th>
										<th>Assigned To</th>
										<th>Date</th>
										<th>Added By</th>
										</tr>";
										while($row = mysqli_fetch_array($result)) {
											echo '<tr>';
											$by = $row['created_by'];
											$to = $row['email_comment'];
											echo '<td data-title="Note">'.html_entity_decode($row['comment']).'</td>';
											echo '<td data-title="Assigned To">'.get_staff($dbc, $to).'</td>';
											echo '<td data-title="Date">'.$row['created_date'].'</td>';
											echo '<td data-title="Added By">'.get_staff($dbc, $by).'</td>';
											echo '</tr>';
										}
										echo '</table>';
									}
								} ?>
							  <div class="form-group">
								<label for="site_name" class="col-sm-4 control-label">Note:</label>
								<div class="col-sm-8">
								  <textarea name="comments" rows="4" cols="50" class="form-control" ></textarea>
								</div>
							  </div>

								<div class="form-group">
								  <label for="site_name" class="col-sm-4 control-label">Send Email:</label>
								  <div class="col-sm-8">
									<input type="checkbox" value="Yes" name="send_email_on_comment" onclick="comment_check_send_email(this);">
								  </div>
								</div>

								<div class="form-group">
								  <label for="site_name" class="col-sm-4 control-label">Assign/Email To:</label>
								  <div class="col-sm-8">
									<select data-placeholder="Choose a Staff Member..." name="email_comment" class="chosen-select-deselect form-control" width="380">
									  <option value=""></option>
									  <?php
										$query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE deleted=0 AND category='Staff' order by first_name");
										while($row = mysqli_fetch_array($query)) {
											echo "<option value='". $row['contactid']."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';
										}
									  ?>
									</select>
								  </div>
								</div><?php
								$sender = get_contact($dbc, $_SESSION['contactid'], 'email_address');
								$subject = 'Note added on Site Work Order for you to Review';
								$body = 'The following note has been added on a site work order for you:<br>[NOTE]<br><br>
										Please click the Site Work Order link below to view all information.<br>
										<a target="_blank" href="'.WEBSITE_URL.'/Site Work Orders/add_work_order.php?workorderid=[WORKORDERID]">Work Order #[WORKORDERID]</a><br>';
								?>
								<script>
								function comment_check_send_email(checked) {
									if(checked.checked) {
										$('#comment_email_send_div').show();
									} else {
										$('#comment_email_send_div').hide();
									}
								}
								</script>
								<div id="comment_email_send_div" style="display:none;">
									<div class="form-group">
										<label class="col-sm-4 control-label">Sending Email Address:</label>
										<div class="col-sm-8">
											<input type="text" name="comment_email_sender" class="form-control" value="<?php echo $sender; ?>">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label">Email Subject:</label>
										<div class="col-sm-8">
											<input type="text" name="comment_email_subject" class="form-control" value="<?php echo $subject; ?>">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label">Email Body:</label>
										<div class="col-sm-8">
											<textarea name="comment_email_body" class="form-control"><?php echo $body; ?></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_safety" >
								Safety Checklist: <span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_safety" class="panel-collapse collapse">
						<div class="panel-body">
							<?php include('add_work_order_safety_checklist.php'); ?>
						</div>
					</div>
				</div>
			
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_addendum" >
								Addendum: <span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_addendum" class="panel-collapse collapse">
						<div class="panel-body">
						   <?php if($workorderid != 'NEW') {
								$query_check_credentials = "SELECT * FROM site_work_comment WHERE workorderid='$workorderid' AND type='addendum' ORDER BY commentid DESC";
								$result = mysqli_query($dbc, $query_check_credentials);
								$num_rows = mysqli_num_rows($result);
								if($num_rows > 0) {
									echo "<table class='table table-bordered'>
									<tr class='hidden-xs hidden-sm'>
									<th>Addendum</th>
									<th>Assigned To</th>
									<th>Date</th>
									<th>Added By</th>
									</tr>";
									while($row = mysqli_fetch_array($result)) {
										echo '<tr>';
										$by = $row['created_by'];
										$to = $row['email_comment'];
										echo '<td data-title="Addendum">'.html_entity_decode($row['comment']).'</td>';
										echo '<td data-title="Assigned To">'.get_staff($dbc, $to).'</td>';
										echo '<td data-title="Date">'.$row['created_date'].'</td>';
										echo '<td data-title="Added By">'.get_staff($dbc, $by).'</td>';
										echo '</tr>';
									}
									echo '</table>';
								}
							} ?>
						  <div class="form-group">
							<label for="site_name" class="col-sm-4 control-label">Addendum:</label>
							<div class="col-sm-8">
							  <textarea name="addendum_comments" rows="4" cols="50" class="form-control" ></textarea>
							</div>
						  </div>

							<div class="form-group">
							  <label for="site_name" class="col-sm-4 control-label">Send Email:</label>
							  <div class="col-sm-8">
								<input type="checkbox" value="Yes" name="addendum_send_email_on_comment" onclick="comment_check_send_email(this);">
							  </div>
							</div>

							<div class="form-group">
							  <label for="site_name" class="col-sm-4 control-label">Assign/Email To:</label>
							  <div class="col-sm-8">
								<select data-placeholder="Choose a Staff Member..." name="addendum_email_comment" class="chosen-select-deselect form-control" width="380">
								  <option value=""></option>
								  <?php
									$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Staff' AND deleted=0 AND `status`=1"),MYSQLI_ASSOC));
									foreach($query as $id) {
										$selected = '';
										//$selected = $task_clientid == $id ? 'selected = "selected"' : '';
										echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
									}
								  ?>
								</select>
							  </div>
							</div><?php
							$sender = get_contact($dbc, $_SESSION['contactid'], 'email_address');
							$subject = 'Addendum added on Site Work Order for you to Review';
							$body = 'The following addendum has been added on a site work order for you:<br>[ADDENDUM]<br><br>
									Please click the Site Work Order link below to view all information.<br>
									<a target="_blank" href="'.WEBSITE_URL.'/Site Work Orders/add_work_order.php?workorderid=[WORKORDERID]">Work Order #[WORKORDERID]</a><br>';
							?>
							<script>
							function comment_check_send_email(checked) {
								if(checked.checked) {
									$('#comment_email_send_div').show();
								} else {
									$('#comment_email_send_div').hide();
								}
							}
							</script>
							<div id="comment_email_send_div" style="display:none;">
								<div class="form-group">
									<label class="col-sm-4 control-label">Sending Email Address:</label>
									<div class="col-sm-8">
										<input type="text" name="addendum_comment_email_sender" class="form-control" value="<?php echo $sender; ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label">Email Subject:</label>
									<div class="col-sm-8">
										<input type="text" name="addendum_comment_email_subject" class="form-control" value="<?php echo $subject; ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label">Email Body:</label>
									<div class="col-sm-8">
										<textarea name="addendum_comment_email_body" class="form-control"><?php echo $body; ?></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
					
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_summary" >
								Site Summary: <span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_summary" class="panel-collapse collapse">
						<div class="panel-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Start Time On Site:</label>
								<div class="col-sm-8">
									<input type="text" name="start_time_on_site" class="form-control datetimepicker" value="">
								</div>
								<div class="col-sm-4 text-center">Staff</div>
								<div class="col-sm-4 text-center">Task</div>
								<div class="col-sm-3 text-center"><span class="popover-examples list-inline">
										<a href="" data-toggle="tooltip" data-placement="top" title="This is the time that has been saved. It does not include time currently being tracked. It cannot be edited while you are tracking time. In order to edit it, you will first need to stop the timer."><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
									</span>Hours Tracked</div>
								<?php foreach($staff_summary as $j => $summary) {
									$summary = explode('**#**', $summary); ?>
									<div class="form-group summary">
										<div class="col-sm-4"><select name="summary_staff[]" class="form-control chosen-select-deselect"><option></option>
											<?php foreach($staff_list as $id) {
												echo "<option ".($id == $summary[0] ? 'selected' : '')." value='$id'>".get_contact($dbc, $id)."</option>";
											} ?></select></div>
										<div class="col-sm-4"><select name="summary_task[]" value="<?= $summary[1] ?>" class="form-control chosen-select-deselect" data-row="<?= $j ?>"><option></option>
												<?php foreach($task_list as $task_group) {
													$task_group = explode('*#*',$task_group);
													echo "<optgroup label='".$task_group[0]." Tasks' />";
													unset($task_group[0]); ?>
													<?php foreach($task_group as $task_name) { ?>
														<option <?= ($summary[1] == $task_name ? 'selected' : '') ?> value="<?= $task_name ?>"><?= $task_name ?></option>
													<?php } ?>
												<?php } ?>
												<option></option>
											</select>
										</div>
										<div class="col-sm-3"><input data-disabled="<?= $summary[4] > 0 ? 'true' : 'false' ?>" <?= $summary_timer > 0 && empty($summary[4]) ? 'readonly' : '' ?> type="number" name="summary_hours[]" value="<?= $summary[2] ?>" class="form-control" min="0" step="any"></div>
										<div class="col-sm-1"><a href="" onclick="$(this).closest('.form-group.summary').remove(); return false;"><img src="<?= WEBSITE_URL ?>/img/remove.png"></a></div>
										<input type="hidden" name="summary_timer_start[]" value="<?= $summary[3] ?>">
										<input type="hidden" name="summary_disabled[]" value="<?= $summary[4] ?>">
									</div>
								<?php } ?>
								<button class="btn brand-btn pull-right" id="summary_btn" onclick="add_staff_summary(); return false;">Add</button>
								<div class="clearfix"></div>
								<label class="col-sm-4 control-label">Total Time On Site:</label>
								<div class="col-sm-8">
									<input type="text" name="total_time_on_site" class="form-control timepicker" value="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Driving Logs:</label>
								<div class="col-sm-8">
									<?php $driving_logs = mysqli_query($dbc, "SELECT `log_id`, `drive_date`, `staff` FROM `site_work_driving_log` WHERE `workorderid`='$workorderid'");
									if(mysqli_num_rows($driving_logs) > 0) {
										while($log = mysqli_fetch_array($driving_logs)) {
											echo '<button class="btn brand-btn" type="submit" name="submit" value="DRIVING_'.$log['log_id'].'">Driving Log for '.get_contact($dbc, $log['staff']).' from '.$log['drive_date'].'</button>';
										}
									} else {
										echo "No Driving Logs Found.";
									} ?>
								</div>
							</div>
							<button class="btn brand-btn pull-right" type="submit" name="submit" value="DUPLICATE">Add New Work Order</button>
						</div>
					</div>
				</div>
			</div>

			<div class="form-group double-gap-top">
				<p><span class="brand-color"><em>Required Fields *</em></span></p>
			</div>
		</div>
		
		<div class="form-group">
			<div class="col-sm-6">
				<a href="<?= $from_site_url ?>" class="btn brand-btn btn-lg">Back</a>
			</div>
			<div class="col-sm-6">
				<input type="hidden" name="workorderid" value="<?= (empty($workorderid) ? 'NEW' : $workorderid) ?>">
				<button	type="submit" name="submit"	value="<?= (empty($workorderid) ? 'NEW' : $workorderid) ?>" class="btn brand-btn btn-lg pull-right">Submit</button>
			</div>
			<div class="clearfix"></div>
		</div>

		</form>

	</div>
  </div>

<?php include ('../footer.php'); ?>