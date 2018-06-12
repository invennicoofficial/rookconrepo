<?php include_once('../include.php');
checkAuthorised('calendar_rook');
include_once('../Calendar/calendar_functions_inc.php');
$contact_regions = array_filter(array_unique(explode(',', mysqli_fetch_array(mysqli_query($dbc, "SELECT GROUP_CONCAT(`value` SEPARATOR ',') FROM `general_configuration` WHERE `name` LIKE '%_region'"))[0])));
$contact_security = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `contacts_security` WHERE `contactid`='$contactid'"));
$allowed_regions = array_filter(explode('#*#',$contact_security['region_access']));
if(count($allowed_regions) == 0) {
    $allowed_regions = $contact_regions;
}
$class_regions = explode(',',get_config($dbc, '%_class_regions', true, ','));
$contact_classifications = [];
$classification_regions = [];
foreach(explode(',',get_config($dbc, '%_classification', true, ',')) as $i => $contact_classification) {
	$row = array_search($contact_classification, $contact_classifications);
	if($row !== FALSE && $class_regions[$i] != '') {
		$classification_regions[$row][] = $class_regions[$i];
	} else {
		$contact_classifications[] = $contact_classification;
		$classification_regions[] = array_filter([$class_regions[$i]]);
	}
}
$contact_locations = array_filter(explode(',', mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `con_locations` FROM `field_config_contacts` WHERE `con_locations` IS NOT NULL"))['con_locations']));
$allowed_locations = array_filter(explode('#*#',$contact_security['location_access']));
if(count($allowed_locations) == 0) {
    $allowed_locations = $contact_locations;
}
if(empty($_GET['region'])) {
    $_GET['region'] = $allowed_regions[0];
}
$region_query = '';
if($_GET['region'] == 'Display All' && $allowed_regions != $contact_regions) {
    $all_allowed = "'".trim(implode("','", $allowed_regions), "','")."'";
    $region_query = " AND IFNULL(`region`,'') IN (".$all_allowed.",'')";
} else if($_GET['region'] != 'Display All' && count($contact_regions) > 0) {
    $region_query = " AND IFNULL(`region`,'') IN ('".$_GET['region']."','')";
}
$weekly_days = explode(',',$_GET['weekly_days']);

if(isset($_POST['submit'])) {
	$staffids = array_unique(array_filter($_POST['ticket_staff']));
	$from_date = $_POST['ticket_from_date'];
	$end_date = $_POST['ticket_end_date'];
	$shift_days = $_POST['ticket_shift_days'];
	$skip_days = $_POST['ticket_skip_days'];

	$staff_query = [];
	foreach ($staffids as $staffid) {
		$staff_query[] = "(`contactid` LIKE '%,$staffid,%' OR `internal_qa_contactid` LIKE '%,$staffid,%' OR `deliverable_contactid` LIKE '%,$staffid,%')"; 
	}
	if(!empty($staff_query)) {
		$staff_query = '('.implode(' OR ', $staff_query).')';
		$all_tickets_sql = "SELECT * FROM `tickets` WHERE (`internal_qa_date` >= '$from_date' OR `deliverable_date` >= '$from_date' OR `to_do_date` >= '$from_date') AND $staff_query AND `deleted` = 0 AND `status` NOT IN ('Archive', 'Done')";
		if(!empty($end_date)) {
			echo $all_tickets_sql = "SELECT * FROM `tickets` WHERE ((`internal_qa_date` >= '$from_date' AND `internal_qa_date` <= '$end_date') OR (`deliverable_date` >= '$from_date' AND `deliverable_date` <= '$end_date') OR (`to_do_date` >= '$from_date' AND `to_do_date` <= '$end_date')) AND $staff_query AND `deleted` = 0 AND `status` NOT IN ('Archive', 'Done')";
		}
		$tickets = mysqli_fetch_all(mysqli_query($dbc, $all_tickets_sql),MYSQLI_ASSOC);

		foreach ($tickets as $ticket) {
			$ticketid = $ticket['ticketid'];
			$to_do_date = $ticket['to_do_date'];
			$to_do_end_date = $ticket['to_do_end_date'];
			$internal_qa_date = $ticket['internal_qa_date'];
			$deliverable_date = $ticket['deliverable_date'];
			$contactids = array_unique(array_filter(explode(',', $ticket['contactid'])));
			$internal_qa_contactids = array_unique(array_filter(explode(',', $ticket['internal_qa_contactid'])));
			$deliverable_contactids = array_unique(array_filter(explode(',', $ticket['deliverable_contactid'])));

			if(strtotime($to_do_date) >= strtotime($from_date) && !empty(array_intersect($staffids, $contactids))) {
				if((!empty($end_date) && strtotime($to_do_date) <= strtotime($end_date)) || empty($end_date)) {
					$day_counter = 0;
					while($day_counter < $shift_days || $skip_day) {
						$to_do_date = date('Y-m-d', strtotime($to_do_date.' + 1 days'));
						if(!empty($to_do_end_date)) {
							$to_do_end_date = date('Y-m-d', strtotime($to_do_end_date.' + 1 days'));
						}
						$skip_day = false;
						$day_of_week = date('l', strtotime($to_do_date));
						if(!in_array($day_of_week, $skip_days)) {
							$day_counter++;
						} else {
							$skip_day = true;
						}
					}
				}
			}
			if(strtotime($internal_qa_date) >= strtotime($from_date) && !empty(array_intersect($staffids, $internal_qa_contactids))) {
				if((!empty($end_date) && strtotime($internal_qa_date) <= strtotime($end_date)) || empty($end_date)) {
					$day_counter = 0;
					while($day_counter < $shift_days || $skip_day) {
						$internal_qa_date = date('Y-m-d', strtotime($internal_qa_date.' + 1 days'));
						$skip_day = false;
						$day_of_week = date('l', strtotime($internal_qa_date));
						if(!in_array($day_of_week, $skip_days)) {
							$day_counter++;
						} else {
							$skip_day = true;
						}
					}
				}
			}
			if(strtotime($deliverable_date) >= strtotime($from_date) && !empty(array_intersect($staffids, $deliverable_contactids))) {
				if((!empty($end_date) && strtotime($deliverable_date) <= strtotime($end_date)) || empty($end_date)) {
					$day_counter = 0;
					while($day_counter < $shift_days || $skip_day) {
						$deliverable_date = date('Y-m-d', strtotime($deliverable_date.' + 1 days'));
						$skip_day = false;
						$day_of_week = date('l', strtotime($deliverable_date));
						if(!in_array($day_of_week, $skip_days)) {
							$day_counter++;
						} else {
							$skip_day = true;
						}
					}
				}
			}

			$ticket_query = "UPDATE `tickets` SET `to_do_date` = '$to_do_date', `to_do_end_date` = '$to_do_end_date', `internal_qa_date` = '$internal_qa_date', `deliverable_date` = '$deliverable_date' WHERE `ticketid` = '$ticketid'";
			mysqli_query($dbc, $ticket_query);
		}
	}
}
?>

<script type="text/javascript">
function addContact() {
    var block = $('div.contact-block').last();
    var clone = block.clone();

	clone.find('.form-control').val('');
    resetChosen(clone.find('select'));

    block.after(clone);
}
function deleteContact(button) {
    if($('div.contact-block').length <= 1) {
        addContact();
    }
    $(button).closest('div.contact-block').remove();
}
</script>
<h3>Shift <?= TICKET_TILE ?></h3>

<div class="block-group ticket_shift_block" style="height: 100%; overflow-y: auto;">
	<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
		<div class="contact-block">
			<label for="contact" class="super-label">Staff
				<select data-placeholder="Select Staff" name="ticket_staff[]" class="chosen-select-deselect form-control">
					<option></option>
	                <?php $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `deleted` = 0 AND `status` = 1 AND `show_hide_user` = 1".$region_query),MYSQLI_ASSOC));
	                foreach ($query as $id) {
	                	echo '<option value="'.$id.'">'.get_contact($dbc, $id).'</option>';
	                } ?>
	            </select>
			</label>
	        <div class="pull-right">
	            <img src="../img/icons/ROOK-add-icon.png" class="inline-img pull-right" onclick="addContact();">
	            <img src="../img/remove.png" class="inline-img pull-right" onclick="deleteContact(this);">
	        </div>
		</div>

		<label for="from_date" class="super-label">From Date
			<input type="text" name="ticket_from_date" class="form-control datepicker" value="<?= date('Y-m-d') ?>">
		</label>

		<label for="end_date" class="super-label">End Date<br><i>(Leave empty to shift all dates following the From Date.)</i>
			<input type="text" name="ticket_end_date" class="form-control datepicker" value="">
		</label>

		<label for="shift_days" class="super-label">Number of Days Shifted
			<input type="number" name="ticket_shift_days" class="form-control" value="1">
		</label>

		<label for="skip_days" class="super-label">Skip Days
			<div class="block-group">
	            <?php $days_of_week = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
	            foreach ($days_of_week as $day_of_week_skip) {
	            	echo '<label style="padding-right: 0.5em;"><input type="checkbox" name="ticket_skip_days[]" value="'.$day_of_week_skip.'" '.(!in_array($day_of_week_skip, $weekly_days) ? 'checked="checked"' : '').'>'.$day_of_week_skip.'</label>';
	            } ?>
            </div>
		</label>

	    <div class="pull-right" style="padding-top: 1em;">
	    	<button type="submit" name="submit" value="Submit" class="btn brand-btn">Submit</button>
	        <?php
	            unset($page_query['teamid']);
	            unset($page_query['subtab']);
	            unset($page_query['unbooked']);
	            unset($page_query['equipment_assignmentid']);
	            unset($page_query['shiftid']);
	            unset($page_query['action']);
	            unset($page_query['bookingid']);
	            unset($page_query['appoint_date']);
	            unset($page_query['end_appoint_date']);
	            unset($page_query['therapistsid']);
	            unset($page_query['equipmentid']);
	            unset($page_query['add_reminder']);
	        ?>
	        <a href="?<?= http_build_query($page_query) ?>" class="btn brand-btn mobile-anchor">Cancel</a>
	    </div>
	</form>
</div>