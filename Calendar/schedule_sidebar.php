<?php include_once('../include.php');
checkAuthorised('calendar_rook');
include_once('calendar_settings_inc.php');
include_once('calendar_functions_inc.php');

$equip_multi_assign_staff_disallow = get_config($dbc, 'equip_multi_assign_staff_disallow');
$classification_onclick = '';
if($multi_class_admin == 1 && strpos(','.ROLE.',', ',admin,') === FALSE && strpos(','.ROLE.',', ',super,') === FALSE) {
	$classification_onclick = '$(this).closest(".panel").find(".block-item").not($(this).find(".block-item")).removeClass("active");';
}

if($_GET['view'] == 'weekly') {
	$calendar_start = $_GET['date'];
	if($calendar_start == '') {
		$calendar_start = date('Y-m-d');
	} else {
		$calendar_start = date('Y-m-d', strtotime($calendar_start));
	}
	$equipment_category = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_equip_assign`"))['equipment_category'];
	$client_type = get_config($dbc, 'scheduling_client_type');
	$calendar_type = get_config($dbc, 'scheduling_wait_list');
	if($calendar_type == 'ticket_multi') {
		$calendar_type = 'ticket';
	}
	$weekly_start = get_config($dbc, 'scheduling_weekly_start');
	if($weekly_start == 'Sunday') {
		$weekly_start = 1;
	} else {
		$weekly_start = 0;
	}
	$day = date('w', strtotime($calendar_start));
	$week_start_date = date('F j', strtotime($calendar_start.' -'.($day - 1 + $weekly_start).' days'));
	$week_end_date = date('F j, Y', strtotime($calendar_start.' -'.($day - 7 + $weekly_start).' days'));
	$week_start_date_check = date('Y-m-d', strtotime($calendar_start.' -'.($day - 1 + $weekly_start).' days'));
	$week_end_date_check = date('Y-m-d', strtotime($calendar_start.' -'.($day - 7 + $weekly_start).' days'));

	$weekly_days = explode(',',get_config($dbc, 'scheduling_weekly_days'));

	if (empty($equipment_category)) {
		$equipment_category = 'Equipment';
	}
	if(!empty($_GET['equipment_id'])) {
		$equipment_id = $_GET['equipment_id'];
	}
	if($_GET['mode'] == 'staff') {
		$contact_id = $_GET['contactid'];
		if(empty($contact_id)) {
			$contact_id = $_SESSION['contactid'];
		}
	} ?>
	<input type="text" class="search-text form-control" placeholder="Search All">
	<div class="sidebar panel-group block-panels equip_assign_div" id="category_accordions" style="margin: 1.5em 0 0.5em; overflow: auto; padding-bottom: 0;">
		<?php if(count($contact_regions) > 0 && strpos(",$dispatch_filters,", ",Region,") !== FALSE) { ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#category_accordions" href="#collapse_region" >
						<span style="display: inline-block; width: calc(100% - 6em);">Regions</span><span class="glyphicon glyphicon-plus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_region" class="panel-collapse collapse">
				<div class="panel-body panel-body-height" style="overflow-y: auto; padding: 0;">
				<?php $active_regions = array_filter(explode(',',get_user_settings()['appt_calendar_regions']));
				$region_list = $contact_regions;
				foreach($region_list as $region_line => $region) {
					$color_styling = '#6DCFF6';
					if(!empty($region_colours[$region_line])) {
						$color_styling = $region_colours[$region_line];
					}
					$color_box = '<span style="height: 15px; width: 15px; background-color: '.$color_styling.'; border: 1px solid black; float: right;"></span>';
					if(in_array($region, $allowed_regions)) {
						echo "<a href='' onclick='$(this).closest(\".panel\").find(\".block-item\").not($(this).find(\".block-item\")).removeClass(\"active\"); $(this).find(\".block-item\").toggleClass(\"active\"); toggle_columns(); return false;'><div class='block-item ".(in_array($region,$active_regions) ? 'active' : '')."' data-region='".$region."'>".$region.$color_box."</div></a>";
					}
				}
				echo "<a href='' onclick='$(this).closest(\".panel\").find(\".block-item\").not($(this).find(\".block-item\")).removeClass(\"active\"); $(this).find(\".block-item\").toggleClass(\"active\"); toggle_columns(); return false;'><div class='block-item ".(in_array('**UNASSIGNED**',$active_regions) ? 'active' : '')."' data-region='**UNASSIGNED**'>Unassigned Region</div></a>"; ?>
				</div>
			</div>
		</div>
		<div class="active_blocks_regions active_blocks" data-accordion="collapse_region" style="display: none;">
			<?php foreach($region_list as $region) { ?>
				<div class="block-item active" data-region="<?= $region ?>"><?= $region ?></div> 
			<?php } ?>
			<div class="block-item active" data-region="**UNASSIGNED**">Unassigned Region</div>
		</div>
		<?php } ?>
	    
		<?php if(count($contact_locations) > 0 && strpos(",$dispatch_filters,", ",Location,") !== FALSE) { ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#category_accordions" href="#collapse_locations" >
						<span style="display: inline-block; width: calc(100% - 6em);">Locations</span><span class="glyphicon glyphicon-plus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_locations" class="panel-collapse collapse">
				<div class="panel-body panel-body-height" style="overflow-y: auto; padding: 0;"><?php $active_locations = array_filter(explode(',',get_user_settings()['appt_calendar_locations']));
				$location_list = $allowed_locations;
				foreach($location_list as $location) {
					echo "<a href='' onclick='$(this).find(\".block-item\").toggleClass(\"active\"); toggle_columns(); return false;'><div class='block-item ".(in_array($location,$active_locations) ? 'active' : '')."' data-location='".$location."'>".$location."</div></a>";
				}
				echo "<a href='' onclick='$(this).find(\".block-item\").toggleClass(\"active\"); toggle_columns(); return false;'><div class='block-item ".(in_array('**UNASSIGNED**',$active_locations) ? 'active' : '')."' data-location='**UNASSIGNED**'>Unassigned Location</div></a>"; ?>
				</div>
			</div>
		</div>
		<div class="active_blocks_locations active_blocks" data-accordion="collapse_locations" style="display: none;">
			<?php foreach($location_list as $location) { ?>
				<div class="block-item active" data-location="<?= $location ?>"><?= $location ?></div>
			<?php } ?>
			<div class="block-item active" data-location="**UNASSIGNED**">Unassigned Location</div>
		</div>
		<?php } ?>
	    
		<?php if(count($contact_classifications) > 0 && strpos(",$dispatch_filters,", ",Classification,") !== FALSE) { ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#category_accordions" href="#collapse_classifications" >
						<span style="display: inline-block; width: calc(100% - 6em);">Classifications</span><span class="glyphicon glyphicon-plus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_classifications" class="panel-collapse collapse">
				<div class="panel-body panel-body-height" style="overflow-y: auto; padding: 0;"><?php $active_classifications = array_filter(explode(',',get_user_settings()['appt_calendar_classifications']));
				foreach($contact_classifications as $i => $classification) {
					echo "<a href='' onclick='".$classification_onclick."$(this).find(\".block-item\").toggleClass(\"active\"); toggle_columns(); return false;'><div class='block-item ".(in_array($classification,$active_classifications) ? 'active' : '')."' data-regions='".json_encode($classification_regions[$i])."' data-classification='".$classification."'>".getClassificationLogo($dbc, $classification, $classification_logos[$i]).$classification."<span class='id-circle active_user_count pull-right' style='background-color: #00ff00; font-family: \"Open Sans\"; display: none;' onmouseover='displayActiveUsers(this);' onmouseout='hideActiveUsers();'>0</span></div></a>";
				}
				echo "<a href='' onclick='$(this).find(\".block-item\").toggleClass(\"active\"); toggle_columns(); return false;'><div class='block-item ".(in_array('**UNASSIGNED**',$active_classifications) ? 'active' : '')."' data-regions='[]' data-classification='**UNASSIGNED**'><span data-classification='**UNASSIGNED**' class='id-circle' style='background-color: #6DCFF6; font-family: \"Open Sans\";'>UC</span>Unassigned Classification</div></a>"; ?>
				</div>
			</div>
		</div>
		<div class="active_blocks_classifications active_blocks" data-accordion="collapse_classifications" style="display: none;">
			<?php foreach($contact_classifications as $classification) { ?>
				<div class="block-item active" data-classification="<?= $classification ?>"><?= $classification ?></div> 
			<?php } ?>
			<div class="block-item active" data-classification="**UNASSIGNED**">Unassigned Classification</div>
		</div>
		<?php } ?>
	    
	    <?php if($_GET['mode'] != 'staff' && $_GET['mode'] != 'contractors') { ?>
	        <?php if(!empty($client_type)) { ?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#category_accordions" href="#collapse_customers" >
								<span style="display: inline-block; width: calc(100% - 6em);"><?= $client_type ?></span><span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_customers" class="panel-collapse collapse">
						<div class="panel-body" style="overflow-y: auto; padding: 0; height: auto;">
							<?php $active_clients = array_filter(explode(',',get_user_settings()['appt_calendar_clients']));
							$client_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `deleted` = 0 AND `status` = 1 AND `category` = '".$client_type."'".$region_query),MYSQLI_ASSOC));
							foreach($client_list as $clientid) {
								echo "<a href='' onclick='$(this).find(\".block-item\").toggleClass(\"active\"); toggle_columns(); return false;'><div class='block-item equip_assign_draggable ".(in_array($clientid,$active_clients) ? 'active' : '')."' data-client='".$clientid."' data-blocktype='client' data-region='".get_contact($dbc, $clientid, 'region')."' data-classification='".get_contact($dbc, $clientid, 'classification')."' data-location='".get_contact($dbc, $clientid, 'con_locations')."'><img class='drag-handle' src='".WEBSITE_URL."/img/icons/drag_handle.png' style='float: right; width: 2em;'>".(!empty(get_client($dbc, $clientid)) ? get_client($dbc, $clientid) : get_contact($dbc, $clientid))."</div></a>";
							} ?>
						</div>
					</div>
				</div>
				<div class="active_blocks_customers active_blocks" data-accordion="collapse_customers" style="display: none;">
					<?php foreach($client_list as $clientid) { ?>
						<div class="block-item active" data-client="<?= $clientid ?>"><?= (!empty(get_client($dbc, $clientid)) ? get_client($dbc, $clientid) : get_contact($dbc, $clientid)) ?></div> 
					<?php } ?>
				</div>
			<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#category_accordions" href="#collapse_equipment" >
						<span style="display: inline-block; width: calc(100% - 6em);"><?= $equipment_category ?></span><span class="glyphicon glyphicon-minus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_equipment" class="panel-collapse collapse in">
				<div class="panel-body" style="overflow-y: auto; padding: 0;">
					<?php $equip_options = explode(',',get_config($dbc,'equip_options'));
                    $active_equipment = array_filter(explode(',',get_user_settings()['appt_calendar_equipment']));
					$equip_list = mysqli_fetch_all(mysqli_query($dbc, "SELECT *, CONCAT(`category`, ' #', `unit_number`) label FROM `equipment` WHERE `deleted`=0 ".($equipment_category == 'Equipment' ? '' : " AND `category`='".$equipment_category."'")." $allowed_equipment_query ORDER BY ".(in_array('region_sort',$equip_options) ? "IFNULL(NULLIF(`region`,''),'ZZZ'), " : '')."`label`"),MYSQLI_ASSOC);
					if(empty($equipment_id)) {
						$equipment_id = $equip_list[0]['equipmentid'];
					}
                    $region = false;
                    $region_list = explode(',',get_config($dbc, '%_region', true));
                    $region_colours = explode(',',get_config($dbc, '%_region_colour', true));
					foreach($equip_list as $equipment) {
                        if(in_array('region_sort',$equip_options) && $region != $equipment['region']) {
                            $region = $equipment['region'];
                            $region_colour = '';
                            if($region == '') {
                                $region_label = 'No Region';
                            } else {
                                $region_label = implode(', ',explode('*#*',$region));
                                $region_key = array_search($region, $region_list);
                                if($region_key !== false) {
                                    $region_colour = 'background-color:'.$region_colours[$region_key].';';
                                }
                            }
                            echo '<div class="block-item small" style="'.$region_colour.'" data-region="'.$region.'">'.$region_label.'</div>';
                        }
						echo getEquipmentAssignmentBlock($dbc, $equipment['equipmentid'], $_GET['view'], $calendar_start);
					} ?>
				</div>
			</div>
		</div>
		<div class="active_blocks_equipment active_blocks" data-accordion="collapse_equipment" style="display: none;">
			<?php foreach($equip_list as $equipment) { ?>
				<div class="block-item active" data-equipment="<?= $equipment['equipmentid'] ?>"><?= $equipment['label'] ?></div> 
			<?php } ?>
		</div>
		<?php } ?>
		<?php if($allowed_dispatch_staff > 0 && $_GET['mode'] != 'contractors') { ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#category_accordions" href="#collapse_staff" >
						<span style="display: inline-block; width: calc(100% - 6em);">Staff</span><span class="glyphicon glyphicon-plus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_staff" class="panel-collapse collapse <?= $_GET['mode'] == 'staff' ? 'in' : '' ?>">
				<div class="panel-body" style="overflow-y: auto; padding: 0;">
				<?php $active_staff = array_filter(explode(',',get_user_settings()['appt_calendar_staff']));
				$get_field_config = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_equip_assign`"));
				$contact_category = !empty($get_field_config) ? explode(',', $get_field_config['contact_category']) : '';
				$staff_list = sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name`, `category`, `category_contact`, `region`, `classification`, `con_locations` FROM `contacts` WHERE `category` IN (".("'".implode("','",$contact_category)."'").") AND `deleted`=0 AND `status`=1 AND `show_hide_user`=1 AND IFNULL(`calendar_enabled`,1)=1".$region_query));
				foreach ($staff_list as $staff_row) {
					if($_GET['mode'] == 'staff') {
						echo "<a href='' onclick='$(this).find(\".block-item\").toggleClass(\"active\"); toggle_columns(\"staff\"); retrieve_items(this); return false;'><div class='block-item equip_assign_draggable ".(in_array($staff_row['contactid'],$active_staff) ? 'active' : '')."' data-blocktype='staff' data-staff='".$staff_row['contactid']."' data-category='".$staff_row['category_contact']."' data-region='".$staff_row['region']."' data-classification='".$staff_row['classification']."' data-location='".$staff_row['con_locations']."'>";
						profile_id($dbc, $staff_row['contactid']);
						echo ($_GET['mode'] != 'staff' && $_GET['mode'] != 'contractors' ? "<img class='drag-handle' src='".WEBSITE_URL."/img/icons/drag_handle.png' style='float: right; width: 2em;'>" : "").$staff_row['full_name']."</div></a>";
					} else {
						$staff_teams = mysqli_fetch_array(mysqli_query($dbc, "SELECT GROUP_CONCAT(DISTINCT `teamid` SEPARATOR ',') as teams_list FROM `teams_staff` WHERE `contactid` = '".$staff_row['contactid']."' AND `deleted` = 0"));
						if(!empty($staff_teams['teams_list'])) {
							$teams_query = 'OR `teamid` IN ('.$staff_teams['teams_list'].')';
						} else {
							$teams_query = '';
						}
						$staff_equipassigns = mysqli_fetch_array(mysqli_query($dbc, "SELECT GROUP_CONCAT(DISTINCT ea.`equipment_assignmentid` SEPARATOR ',') as ea_list, GROUP_CONCAT(DISTINCT ea.`equipmentid` SEPARATOR ',') as eq_list FROM `equipment_assignment` ea LEFT JOIN `equipment_assignment_staff` eas ON ea.`equipment_assignmentid` = eas.`equipment_assignmentid` WHERE ea.`deleted` = 0 AND (DATE(`start_date`) BETWEEN '$week_start_date_check' AND '$week_end_date_check' OR DATE(`end_date`) BETWEEN '$week_start_date_check' AND '$week_end_date_check') AND ((eas.`contactid` = '".$staff_row['contactid']."' AND eas.`deleted` = 0) $teams_query) AND CONCAT(',',ea.`hide_staff`,',') NOT LIKE '%,".$staff_row['contactid'].",%'"));
						echo "<a href='' onclick='$(this).find(\".block-item\").toggleClass(\"active\"); toggle_columns(\"staff\"); return false;'><div class='block-item equip_assign_draggable ".(in_array($staff_row['contactid'],$active_staff) ? 'active' : '')."' data-blocktype='staff' data-staff='".$staff_row['contactid']."' data-category='".$staff_row['category_contact']."' data-region='".$staff_row['region']."' data-classification='".$staff_row['classification']."' data-location='".$staff_row['con_locations']."' data-equipassign='".$staff_equipassigns['ea_list']."' data-equipment='".$staff_equipassigns['eq_list']."' data-restrict-assign='".$equip_multi_assign_staff_disallow."'>";
						profile_id($dbc, $staff_row['contactid']);
						echo "<img class='drag-handle' src='".WEBSITE_URL."/img/icons/drag_handle.png' style='float: right; width: 2em;'>".$staff_row['full_name']."</div></a>";
					}
				}

				?>
				</div>
			</div>
		</div>
		<div class="active_blocks_staff active_blocks" data-accordion="collapse_staff" style="display: none;">
			<?php foreach($staff_list as $staff_row) { ?>
				<div class="block-item active" data-staff="<?= $staff_row['contactid'] ?>"><?= $staff_row['full_name'] ?></div> 
			<?php } ?>
		</div>
		<?php } ?>
		<?php if($allowed_dispatch_staff > 0 && !empty($contractor_category) && $_GET['mode'] != 'staff') { ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#category_accordions" href="#collapse_contractors" >
						<span style="display: inline-block; width: calc(100% - 6em);">Contractors</span><span class="glyphicon glyphicon-plus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_contractors" class="panel-collapse collapse <?= $_GET['mode'] == 'contractors' ? 'in' : '' ?>">
				<div class="panel-body" style="overflow-y: auto; padding: 0;">
				<?php $active_staff = array_filter(explode(',',get_user_settings()['appt_calendar_staff']));
				$get_field_config = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_equip_assign`"));
				$contractor_category = !empty($get_field_config['contractor_category']) ? explode(',', $get_field_config['contractor_category']) : '';
				$staff_list = sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name`, `name`, `category`, `category_contact`, `region`, `classification`, `con_locations` FROM `contacts` WHERE `category` IN (".("'".implode("','",$contractor_category)."'").") AND `deleted`=0 AND `status`=1 AND `show_hide_user`=1 AND IFNULL(`calendar_enabled`,1)=1".$region_query));
				foreach ($staff_list as $staff_row) {
					if($_GET['mode'] == 'contractors') {
						echo "<a href='' onclick='$(this).find(\".block-item\").toggleClass(\"active\"); toggle_columns(\"staff\"); retrieve_items(this); return false;'><div class='block-item equip_assign_draggable ".(in_array($staff_row['contactid'],$active_staff) ? 'active' : '')."' data-blocktype='staff' data-staff='".$staff_row['contactid']."' data-category='".$staff_row['category_contact']."' data-region='".$staff_row['region']."' data-classification='".$staff_row['classification']."' data-location='".$staff_row['con_locations']."' data-contractor='1'>";
						profile_id($dbc, $staff_row['contactid']);
						echo ($_GET['mode'] != 'staff' && $_GET['mode'] != 'contractors' ? "<img class='drag-handle' src='".WEBSITE_URL."/img/icons/drag_handle.png' style='float: right; width: 2em;'>" : "").$staff_row['full_name']."</div></a>";
					} else {
						$staff_teams = mysqli_fetch_array(mysqli_query($dbc, "SELECT GROUP_CONCAT(DISTINCT `teamid` SEPARATOR ',') as teams_list FROM `teams_staff` WHERE `contactid` = '".$staff_row['contactid']."' AND `deleted` = 0"));
						if(!empty($staff_teams['teams_list'])) {
							$teams_query = 'OR `teamid` IN ('.$staff_teams['teams_list'].')';
						} else {
							$teams_query = '';
						}
						$staff_equipassigns = mysqli_fetch_array(mysqli_query($dbc, "SELECT GROUP_CONCAT(DISTINCT ea.`equipment_assignmentid` SEPARATOR ',') as ea_list, GROUP_CONCAT(DISTINCT ea.`equipmentid` SEPARATOR ',') as eq_list FROM `equipment_assignment` ea LEFT JOIN `equipment_assignment_staff` eas ON ea.`equipment_assignmentid` = eas.`equipment_assignmentid` WHERE ea.`deleted` = 0 AND (DATE(`start_date`) BETWEEN '$week_start_date_check' AND '$week_end_date_check' OR DATE(`end_date`) BETWEEN '$week_start_date_check' AND '$week_end_date_check') AND ((eas.`contactid` = '".$staff_row['contactid']."' AND eas.`deleted` = 0) $teams_query) AND CONCAT(',',ea.`hide_staff`,',') NOT LIKE '%,".$staff_row['contactid'].",%'"));
						echo "<a href='' onclick='$(this).find(\".block-item\").toggleClass(\"active\"); toggle_columns(\"staff\"); return false;'><div class='block-item equip_assign_draggable ".(in_array($staff_row['contactid'],$active_staff) ? 'active' : '')."' data-blocktype='staff' data-staff='".$staff_row['contactid']."' data-category='".$staff_row['category_contact']."' data-region='".$staff_row['region']."' data-classification='".$staff_row['classification']."' data-location='".$staff_row['con_locations']."' data-equipassign='".$staff_equipassigns['ea_list']."' data-equipment='".$staff_equipassigns['eq_list']."' data-restrict-assign='".$equip_multi_assign_staff_disallow."' data-contractor='1'>";
						profile_id($dbc, $staff_row['contactid']);
						echo "<img class='drag-handle' src='".WEBSITE_URL."/img/icons/drag_handle.png' style='float: right; width: 2em;'>".$staff_row['full_name']."</div></a>";
					}
				}

				?>
				</div>
			</div>
		</div>
		<div class="active_blocks_contractors active_blocks" data-accordion="collapse_contractors" style="display: none;">
			<?php foreach($staff_list as $staff_row) { ?>
				<div class="block-item active" data-staff="<?= $staff_row['contactid'] ?>"><?= $staff_row['full_name'] ?></div> 
			<?php } ?>
		</div>
		<?php } ?>
		<?php if(get_config($dbc, 'scheduling_teams') !== '' && $allowed_dispatch_team > 0) { ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#category_accordions" href="#collapse_teams" >
						<span style="display: inline-block; width: calc(100% - 6em);">Teams</span><span class="glyphicon glyphicon-plus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_teams" class="panel-collapse collapse">
				<div class="panel-body" style="overflow-y: auto; padding: 0;">
					<?php 
					$team_list = mysqli_query($dbc, "SELECT * FROM `teams` WHERE `deleted` = 0 AND (DATE(`start_date`) <= DATE(CURDATE()) OR `start_date` IS NULL OR `start_date` = '' OR `start_date` = '0000-00-00') AND (DATE(`end_date`) >= DATE(CURDATE()) OR `end_date` IS NULL OR `end_date` = '' OR `end_date` = '0000-00-00')".$region_query);
					$active_teams = array_filter(explode(',',get_user_settings()['appt_calendar_teams']));
					while($row = mysqli_fetch_array($team_list)) {
						$team_contactids = [];
						$team_equipment = [];
	                    $team_name = get_team_name($dbc, $row['teamid'], '<br />');
	                    $team_contacts = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `teams_staff` WHERE `teamid` ='".$row['teamid']."' AND `deleted` = 0"),MYSQLI_ASSOC);
	                    foreach ($team_contacts as $team_contact) {
	                    	if (get_contact($dbc, $team_contact['contactid'], 'category') == 'Staff') {
	                    		$team_contactids[] = $team_contact['contactid'];
	                    	}
	                    }
	                    $team_contactids = implode(',', $team_contactids);
	                    $team_equipassigns = mysqli_fetch_array(mysqli_query($dbc, "SELECT GROUP_CONCAT(DISTINCT ea.`equipment_assignmentid` SEPARATOR ',') as ea_list, GROUP_CONCAT(DISTINCT ea.`equipmentid` SEPARATOR ',') as eq_list FROM `equipment_assignment` ea LEFT JOIN `equipment_assignment_staff` eas ON ea.`equipment_assignmentid` = eas.`equipment_assignmentid` WHERE ea.`deleted` = 0 AND (DATE(`start_date`) BETWEEN '$week_start_date_check' AND '$week_end_date_check' OR DATE(`end_date`) BETWEEN '$week_start_date_check' AND '$week_end_date_check') AND ((',$team_contactids,' LIKE CONCAT('%,',eas.`contactid`,',%') AND eas.`deleted` = 0) OR `teamid` = '".$row['teamid']."')"));
						// $team_equipassigns = mysqli_fetch_array(mysqli_query($dbc, "SELECT GROUP_CONCAT(DISTINCT `equipment_assignmentid` SEPARATOR ',') as ea_list FROM `equipment_assignment` WHERE `deleted` = 0 AND (DATE(`start_date`) BETWEEN '$week_start_date_check' AND '$week_end_date_check' OR DATE(`end_date`) BETWEEN '$week_start_date_check' AND '$week_end_date_check') AND `teamid` = '".$row['teamid']."'"))['ea_list'];
						echo "<a href='' onclick='$(this).find(\".block-item\").toggleClass(\"active\"); toggle_columns(\"team\"); return false;'><div class='block-item equip_assign_draggable ".(in_array($row['teamid'],$active_teams) ? 'active' : '')."' data-teamid='".$row['teamid']."' data-blocktype='team' data-contactids='".$team_contactids."' data-region='".$row['region']."' data-location='".$row['location']."' data-classification='".$row['classification']."' data-equipassign='".$team_equipassigns['ea_list']."'' data-equipment='".$team_equipassigns['eq_list']."' data-restrict-assign='".$equip_multi_assign_staff_disallow."'>".($_GET['mode'] != 'staff' && $_GET['mode'] != 'contractors' ? "<img class='drag-handle' src='".WEBSITE_URL."/img/icons/drag_handle.png' style='float: right; width: 2em;'>" : "")."<span style=''>$team_name</span></div></a>";
					} ?>
				</div>
			</div>
		</div>
		<div class="active_blocks_teams active_blocks" data-accordion="collapse_teams" style="display: none;">
			<?php $team_list = mysqli_query($dbc, "SELECT * FROM `teams` WHERE `deleted` = 0 AND (DATE(`start_date`) <= DATE(CURDATE()) OR `start_date` IS NULL OR `start_date` = '' OR `start_date` = '0000-00-00') AND (DATE(`end_date`) >= DATE(CURDATE()) OR `end_date` IS NULL OR `end_date` = '' OR `end_date` = '0000-00-00')".$region_query);
			$active_teams = array_filter(explode(',',get_user_settings()['appt_calendar_teams']));
			while($row = mysqli_fetch_array($team_list)) { ?>
				<div class="block-item active" data-teamid="<?= $row['teamid'] ?>"><?= get_team_name($dbc, $row['teamid']) ?></div> 
			<?php } ?>
		</div>
		<?php } ?>
	</div>
	<div class="block-item"><img src="../img/icons/clock-button.png" style="height: 1em; margin-right: 1em;">Break</div>
<?php } else {
	$calendar_start = $_GET['date'];
	if($calendar_start == '') {
		$calendar_start = date('Y-m-d');
	} else {
		$calendar_start = date('Y-m-d', strtotime($calendar_start));
	}
	$equipment_category = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_equip_assign`"))['equipment_category'];
	$client_type = get_config($dbc, 'scheduling_client_type');
	$calendar_type = get_config($dbc, 'scheduling_wait_list');
	if($calendar_type == 'ticket_multi') {
		$calendar_type = 'ticket';
	}
	if (empty($equipment_category)) {
		$equipment_category = 'Equipment';
	} ?>
	<input type="text" class="search-text form-control" placeholder="Search All">
	<div class="sidebar panel-group block-panels equip_assign_div" id="category_accordions" style="margin: 1.5em 0 0.5em; overflow: auto; padding-bottom: 0;">
		<?php if(count($contact_regions) > 0 && strpos(",$dispatch_filters,", ",Region,") !== FALSE) { ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#category_accordions" href="#collapse_region" >
						<span style="display: inline-block; width: calc(100% - 6em);">Regions</span><span class="glyphicon glyphicon-plus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_region" class="panel-collapse collapse">
				<div class="panel-body panel-body-height" style="overflow-y: auto; padding: 0;">
				<?php $active_regions = array_filter(explode(',',get_user_settings()['appt_calendar_regions']));
				$region_list = $contact_regions;
				foreach($region_list as $region_line => $region) {
					$color_styling = '#6DCFF6';
					if(!empty($region_colours[$region_line])) {
						$color_styling = $region_colours[$region_line];
					}
					$color_box = '<span style="height: 15px; width: 15px; background-color: '.$color_styling.'; border: 1px solid black; float: right;"></span>';
					if(in_array($region, $allowed_regions)) {
						echo "<a href='' onclick='$(this).closest(\".panel\").find(\".block-item\").not($(this).find(\".block-item\")).removeClass(\"active\"); $(this).find(\".block-item\").toggleClass(\"active\"); toggle_columns(); return false;'><div class='block-item ".(in_array($region,$active_regions) ? 'active' : '')."' data-region='".$region."'>".$region.$color_box."</div></a>";
					}
				}
				echo "<a href='' onclick='$(this).closest(\".panel\").find(\".block-item\").not($(this).find(\".block-item\")).removeClass(\"active\"); $(this).find(\".block-item\").toggleClass(\"active\"); toggle_columns(); return false;'><div class='block-item ".(in_array('**UNASSIGNED**',$active_regions) ? 'active' : '')."' data-region='**UNASSIGNED**'>Unassigned Region</div></a>"; ?>
				</div>
			</div>
		</div>
		<div class="active_blocks_regions active_blocks" data-accordion="collapse_region" style="display: none;">
			<?php foreach($region_list as $region) { ?>
				<div class="block-item active" data-region="<?= $region ?>"><?= $region ?></div> 
			<?php } ?>
			<div class="block-item active" data-region="**UNASSIGNED**">Unassigned Region</div>
		</div>
		<?php } ?>
        
		<?php if(count($contact_locations) > 0 && strpos(",$dispatch_filters,", ",Location,") !== FALSE) { ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#category_accordions" href="#collapse_locations" >
						<span style="display: inline-block; width: calc(100% - 6em);">Locations</span><span class="glyphicon glyphicon-plus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_locations" class="panel-collapse collapse">
				<div class="panel-body panel-body-height" style="overflow-y: auto; padding: 0;"><?php $active_locations = array_filter(explode(',',get_user_settings()['appt_calendar_locations']));
				$location_list = $allowed_locations;
				foreach($location_list as $location) {
					echo "<a href='' onclick='$(this).find(\".block-item\").toggleClass(\"active\"); toggle_columns(); return false;'><div class='block-item ".(in_array($location,$active_locations) ? 'active' : '')."' data-location='".$location."'>".$location."</div></a>";
				}
				echo "<a href='' onclick='$(this).find(\".block-item\").toggleClass(\"active\"); toggle_columns(); return false;'><div class='block-item ".(in_array('**UNASSIGNED**',$active_locations) ? 'active' : '')."' data-location='**UNASSIGNED**'>Unassigned Location</div></a>"; ?>
				</div>
			</div>
		</div>
		<div class="active_blocks_locations active_blocks" data-accordion="collapse_locations" style="display: none;">
			<?php foreach($location_list as $location) { ?>
				<div class="block-item active" data-location="<?= $location ?>"><?= $location ?></div> 
			<?php } ?>
			<div class="block-item active" data-location="**UNASSIGNED**">Unassigned Location</div>
		</div>
		<?php } ?>
        
		<?php if(count($contact_classifications) > 0 && strpos(",$dispatch_filters,", ",Classification,") !== FALSE) { ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#category_accordions" href="#collapse_classifications" >
						<span style="display: inline-block; width: calc(100% - 6em);">Classifications</span><span class="glyphicon glyphicon-plus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_classifications" class="panel-collapse collapse">
				<div class="panel-body panel-body-height" style="overflow-y: auto; padding: 0;"><?php $active_classifications = array_filter(explode(',',get_user_settings()['appt_calendar_classifications']));
				foreach($contact_classifications as $i => $classification) {
					echo "<a href='' onclick='$(this).find(\".block-item\").toggleClass(\"active\"); toggle_columns(); return false;'><div class='block-item ".(in_array($classification,$active_classifications) ? 'active' : '')."' data-regions='".json_encode($classification_regions[$i])."' data-classification='".$classification."'>".getClassificationLogo($dbc, $classification, $classification_logos[$i]).$classification."<span class='id-circle active_user_count pull-right' style='background-color: #00ff00; font-family: \"Open Sans\"; display: none;' onmouseover='displayActiveUsers(this);' onmouseout='hideActiveUsers();'>0</span></div></a>";
				}
				echo "<a href='' onclick='$(this).find(\".block-item\").toggleClass(\"active\"); toggle_columns(); return false;'><div class='block-item ".(in_array('**UNASSIGNED**',$active_classifications) ? 'active' : '')."' data-regions='[]' data-classification='**UNASSIGNED**'><span data-classification='**UNASSIGNED**' class='id-circle' style='background-color: #6DCFF6; font-family: \"Open Sans\";'>UC</span>Unassigned Classification</div></a>"; ?>
				</div>
			</div>
		</div>
		<div class="active_blocks_classifications active_blocks" data-accordion="collapse_classifications" style="display: none;">
			<?php foreach($contact_classifications as $classification) { ?>
				<div class="block-item active" data-classification="<?= $classification ?>"><?= $classification ?></div> 
			<?php } ?>
			<div class="block-item active" data-classification="**UNASSIGNED**">Unassigned Classification</div>
		</div>
		<?php } ?>
        
        <?php if($_GET['mode'] != 'staff' && $_GET['mode'] != 'contractors') { ?>
            <?php if(!empty($client_type)) { ?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#category_accordions" href="#collapse_customers" >
								<span style="display: inline-block; width: calc(100% - 6em);"><?= $client_type ?></span><span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_customers" class="panel-collapse collapse">
						<div class="panel-body" style="overflow-y: auto; padding: 0;">
							<?php $active_clients = array_filter(explode(',',get_user_settings()['appt_calendar_clients']));
							$client_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `deleted` = 0 AND `status` = 1 AND `category` = '".$client_type."'".$region_query),MYSQLI_ASSOC));
							foreach($client_list as $clientid) {
								echo "<a href='' onclick='$(this).find(\".block-item\").toggleClass(\"active\"); toggle_columns(); return false;'><div class='block-item equip_assign_draggable ".(in_array($clientid,$active_clients) ? 'active' : '')."' data-client='".$clientid."' data-blocktype='client' data-region='".get_contact($dbc, $clientid, 'region')."' data-classification='".get_contact($dbc, $clientid, 'classification')."' data-location='".get_contact($dbc, $clientid, 'con_locations')."'><img class='drag-handle' src='".WEBSITE_URL."/img/icons/drag_handle.png' style='float: right; width: 2em;'>".(!empty(get_client($dbc, $clientid)) ? get_client($dbc, $clientid) : get_contact($dbc, $clientid))."</div></a>";
							} ?>
						</div>
					</div>
				</div>
				<div class="active_blocks_customers active_blocks" data-accordion="collapse_customers" style="display: none;">
					<?php foreach($client_list as $clientid) { ?>
						<div class="block-item active" data-client="<?= $clientid ?>"><?= (!empty(get_client($dbc, $clientid)) ? get_client($dbc, $clientid) : get_contact($dbc, $clientid)) ?></div> 
					<?php } ?>
				</div>
			<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#category_accordions" href="#collapse_equipment" >
						<span style="display: inline-block; width: calc(100% - 6em);"><?= $equipment_category ?></span><span class="glyphicon glyphicon-minus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_equipment" class="panel-collapse collapse in">
				<div class="panel-body" style="overflow-y: auto; padding: 0;">
					<?php $equip_options = explode(',',get_config($dbc,'equip_options'));
                    $active_equipment = array_filter(explode(',',get_user_settings()['appt_calendar_equipment']));
					$equip_list = mysqli_fetch_all(mysqli_query($dbc, "SELECT *, CONCAT(`category`, ' #', `unit_number`) label FROM `equipment` WHERE `deleted`=0 ".($equipment_category == 'Equipment' ? '' : " AND `category`='".$equipment_category."'")." $allowed_equipment_query ORDER BY ".(in_array('region_sort',$equip_options) ? "IFNULL(NULLIF(`region`,''),'ZZZ'), " : '')."`label`"),MYSQLI_ASSOC);
					if(empty($equipment_id)) {
						$equipment_id = $equip_list[0]['equipmentid'];
					}
                    $region = false;
                    $region_list = explode(',',get_config($dbc, '%_region', true));
                    $region_colours = explode(',',get_config($dbc, '%_region_colour', true));
					foreach($equip_list as $equipment) {
                        if(in_array('region_sort',$equip_options) && $region != $equipment['region']) {
                            $region = $equipment['region'];
                            $region_colour = '';
                            if($region == '') {
                                $region_label = 'No Region';
                            } else {
                                $region_label = implode(', ',explode('*#*',$region));
                                $region_key = array_search($region, $region_list);
                                if($region_key !== false) {
                                    $region_colour = 'background-color:'.$region_colours[$region_key].';';
                                }
                            }
                            echo '<div class="block-item small" style="'.$region_colour.'" data-region="'.$region.'">'.$region_label.'</div>';
                        }
						echo getEquipmentAssignmentBlock($dbc, $equipment['equipmentid'], $_GET['view'], $calendar_start);
					} ?>
				</div>
			</div>
		</div>
		<div class="active_blocks_equipment active_blocks" data-accordion="collapse_equipment" style="display: none;">
			<?php foreach($equip_list as $equipment) { ?>
				<div class="block-item active" data-equipment="<?= $equipment['equipmentid'] ?>"><?= $equipment['label'] ?></div> 
			<?php } ?>
		</div>
		<?php } ?>
		<?php if($allowed_dispatch_staff > 0 && $_GET['mode'] != 'contractors') { ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#category_accordions" href="#collapse_staff" >
						<span style="display: inline-block; width: calc(100% - 6em);">Staff</span><span class="glyphicon glyphicon-plus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_staff" class="panel-collapse collapse <?= $_GET['mode'] == 'staff' ? 'in' : '' ?>">
				<div class="panel-body" style="overflow-y: auto; padding: 0; height: auto;">
				<?php $active_staff = array_filter(explode(',',get_user_settings()['appt_calendar_staff']));
				$get_field_config = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_equip_assign`"));
				$contact_category = !empty($get_field_config) ? explode(',', $get_field_config['contact_category']) : '';
				$staff_list = sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name`, `category`, `category_contact`, `region`, `classification`, `con_locations` FROM `contacts` WHERE `category` IN (".("'".implode("','",$contact_category)."'").") AND `deleted`=0 AND `status`=1 AND `show_hide_user`=1 AND IFNULL(`calendar_enabled`,1)=1".$region_query));
				foreach ($staff_list as $staff_row) {
					if($_GET['mode'] == 'staff') {
						echo "<a href='' onclick='$(this).find(\".block-item\").toggleClass(\"active\"); toggle_columns(\"staff\"); retrieve_items(this); return false;'><div class='block-item equip_assign_draggable ".(in_array($staff_row['contactid'],$active_staff) ? 'active' : '')."' data-blocktype='staff' data-staff='".$staff_row['contactid']."' data-category='".$staff_row['category_contact']."' data-region='".$staff_row['region']."' data-classification='".$staff_row['classification']."' data-location='".$staff_row['con_locations']."'>";
						profile_id($dbc, $staff_row['contactid']);
						echo ($_GET['mode'] != 'staff' && $_GET['mode'] != 'contractors' ? "<img class='drag-handle' src='".WEBSITE_URL."/img/icons/drag_handle.png' style='float: right; width: 2em;'>" : "").$staff_row['full_name']."</div></a>";
					} else {
						$staff_teams = mysqli_fetch_array(mysqli_query($dbc, "SELECT GROUP_CONCAT(DISTINCT `teamid` SEPARATOR ',') as teams_list FROM `teams_staff` WHERE `contactid` = '".$staff_row['contactid']."' AND `deleted` = 0"));
						if(!empty($staff_teams['teams_list'])) {
							$teams_query = 'OR `teamid` IN ('.$staff_teams['teams_list'].')';
						} else {
							$teams_query = '';
						}
						$staff_equipassigns = mysqli_fetch_array(mysqli_query($dbc, "SELECT GROUP_CONCAT(DISTINCT ea.`equipment_assignmentid` SEPARATOR ',') as ea_list, GROUP_CONCAT(DISTINCT ea.`equipmentid` SEPARATOR ',') as eq_list FROM `equipment_assignment` ea LEFT JOIN `equipment_assignment_staff` eas ON ea.`equipment_assignmentid` = eas.`equipment_assignmentid` WHERE ea.`deleted` = 0 AND DATE(`start_date`) <= '$calendar_start' AND DATE(ea.`end_date`) >= '$calendar_start' AND CONCAT(',',ea.`hide_days`,',') NOT LIKE '%,$calendar_start,%' AND ((eas.`contactid` = '".$staff_row['contactid']."' AND eas.`deleted` = 0) $teams_query) AND CONCAT(',',ea.`hide_staff`,',') NOT LIKE '%,".$staff_row['contactid'].",%'"));
						echo "<a href='' onclick='$(this).find(\".block-item\").toggleClass(\"active\"); toggle_columns(\"staff\"); return false;'><div class='block-item equip_assign_draggable ".(in_array($staff_row['contactid'],$active_staff) ? 'active' : '')."' data-blocktype='staff' data-staff='".$staff_row['contactid']."' data-category='".$staff_row['category_contact']."' data-region='".$staff_row['region']."' data-classification='".$staff_row['classification']."' data-location='".$staff_row['con_locations']."' data-equipassign='".$staff_equipassigns['ea_list']."' data-equipment='".$staff_equipassigns['eq_list']."' data-restrict-assign='".$equip_multi_assign_staff_disallow."'>";
						profile_id($dbc, $staff_row['contactid']);
						echo ($_GET['mode'] != 'staff' && $_GET['mode'] != 'contractors' ? "<img class='drag-handle' src='".WEBSITE_URL."/img/icons/drag_handle.png' style='float: right; width: 2em;'>" : "").$staff_row['full_name']."</div></a>";
					}
				}

				?>
				</div>
			</div>
		</div>
		<div class="active_blocks_staff active_blocks" data-accordion="collapse_staff" style="display: none;">
			<?php foreach($staff_list as $staff_row) { ?>
				<div class="block-item active" data-staff="<?= $staff_row['contactid'] ?>"><?= $staff_row['full_name'] ?></div> 
			<?php } ?>
		</div>
		<?php } ?>
		<?php if($allowed_dispatch_staff > 0 && !empty($contractor_category) && $_GET['mode'] != 'staff') { ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#category_accordions" href="#collapse_contractors" >
						<span style="display: inline-block; width: calc(100% - 6em);">Contractors</span><span class="glyphicon glyphicon-plus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_contractors" class="panel-collapse collapse <?= $_GET['mode'] == 'contractors' ? 'in' : '' ?>">
				<div class="panel-body" style="overflow-y: auto; padding: 0; height: auto;">
				<?php $active_staff = array_filter(explode(',',get_user_settings()['appt_calendar_staff']));
				$get_field_config = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_equip_assign`"));
				$contractor_category = !empty($get_field_config['contractor_category']) ? explode(',', $get_field_config['contractor_category']) : '';
				$staff_list = sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name`, `name`, `category`, `category_contact`, `region`, `classification`, `con_locations` FROM `contacts` WHERE `category` IN (".("'".implode("','",$contractor_category)."'").") AND `deleted`=0 AND `status`=1 AND `show_hide_user`=1 AND IFNULL(`calendar_enabled`,1)=1".$region_query));
				foreach ($staff_list as $staff_row) {
					if($_GET['mode'] == 'contractors') {
						echo "<a href='' onclick='$(this).find(\".block-item\").toggleClass(\"active\"); toggle_columns(\"staff\"); retrieve_items(this); return false;'><div class='block-item equip_assign_draggable ".(in_array($staff_row['contactid'],$active_staff) ? 'active' : '')."' data-blocktype='staff' data-staff='".$staff_row['contactid']."' data-category='".$staff_row['category_contact']."' data-region='".$staff_row['region']."' data-classification='".$staff_row['classification']."' data-location='".$staff_row['con_locations']."' data-contractor='1'>";
						profile_id($dbc, $staff_row['contactid']);
						echo ($_GET['mode'] != 'staff' && $_GET['mode'] != 'contractors' ? "<img class='drag-handle' src='".WEBSITE_URL."/img/icons/drag_handle.png' style='float: right; width: 2em;'>" : "").$staff_row['full_name']."</div></a>";
					} else {
						$staff_teams = mysqli_fetch_array(mysqli_query($dbc, "SELECT GROUP_CONCAT(DISTINCT `teamid` SEPARATOR ',') as teams_list FROM `teams_staff` WHERE `contactid` = '".$staff_row['contactid']."' AND `deleted` = 0"));
						if(!empty($staff_teams['teams_list'])) {
							$teams_query = 'OR `teamid` IN ('.$staff_teams['teams_list'].')';
						} else {
							$teams_query = '';
						}
						$staff_equipassigns = mysqli_fetch_array(mysqli_query($dbc, "SELECT GROUP_CONCAT(DISTINCT ea.`equipment_assignmentid` SEPARATOR ',') as ea_list, GROUP_CONCAT(DISTINCT ea.`equipmentid` SEPARATOR ',') as eq_list FROM `equipment_assignment` ea LEFT JOIN `equipment_assignment_staff` eas ON ea.`equipment_assignmentid` = eas.`equipment_assignmentid` WHERE ea.`deleted` = 0 AND DATE(`start_date`) <= '$calendar_start' AND DATE(ea.`end_date`) >= '$calendar_start' AND CONCAT(',',ea.`hide_days`,',') NOT LIKE '%,$calendar_start,%' AND ((eas.`contactid` = '".$staff_row['contactid']."' AND eas.`deleted` = 0) $teams_query) AND CONCAT(',',ea.`hide_staff`,',') NOT LIKE '%,".$staff_row['contactid'].",%'"));
						echo "<a href='' onclick='$(this).find(\".block-item\").toggleClass(\"active\"); toggle_columns(\"staff\"); return false;'><div class='block-item equip_assign_draggable ".(in_array($staff_row['contactid'],$active_staff) ? 'active' : '')."' data-blocktype='staff' data-staff='".$staff_row['contactid']."' data-category='".$staff_row['category_contact']."' data-region='".$staff_row['region']."' data-classification='".$staff_row['classification']."' data-location='".$staff_row['con_locations']."' data-equipassign='".$staff_equipassigns['ea_list']."' data-equipment='".$staff_equipassigns['eq_list']."' data-restrict-assign='".$equip_multi_assign_staff_disallow."' data-contractor='1'>";
						profile_id($dbc, $staff_row['contactid']);
						echo ($_GET['mode'] != 'staff' && $_GET['mode'] != 'contractors' ? "<img class='drag-handle' src='".WEBSITE_URL."/img/icons/drag_handle.png' style='float: right; width: 2em;'>" : "").$staff_row['full_name']."</div></a>";
					}
				}

				?>
				</div>
			</div>
		</div>
		<div class="active_blocks_contractors active_blocks" data-accordion="collapse_contractors" style="display: none;">
			<?php foreach($staff_list as $staff_row) { ?>
				<div class="block-item active" data-staff="<?= $staff_row['contactid'] ?>"><?= $staff_row['full_name'] ?></div> 
			<?php } ?>
		</div>
		<?php } ?>
		<?php if(get_config($dbc, 'scheduling_teams') !== '' && $allowed_dispatch_team > 0) { ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#category_accordions" href="#collapse_teams" >
						<span style="display: inline-block; width: calc(100% - 6em);">Teams</span><span class="glyphicon glyphicon-plus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_teams" class="panel-collapse collapse">
				<div class="panel-body" style="overflow-y: auto; padding: 0;">
					<?php 
					$team_list = mysqli_query($dbc, "SELECT * FROM `teams` WHERE `deleted` = 0 AND (DATE(`start_date`) <= DATE(CURDATE()) OR `start_date` IS NULL OR `start_date` = '' OR `start_date` = '0000-00-00') AND (DATE(`end_date`) >= DATE(CURDATE()) OR `end_date` IS NULL OR `end_date` = '' OR `end_date` = '0000-00-00')".$region_query);
					$active_teams = array_filter(explode(',',get_user_settings()['appt_calendar_teams']));
					while($row = mysqli_fetch_array($team_list)) {
						$team_contactids = [];
                        $team_name = get_team_name($dbc, $row['teamid'], '<br />');
                        $team_contacts = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `teams_staff` WHERE `teamid` ='".$row['teamid']."' AND `deleted` = 0"),MYSQLI_ASSOC);
                        foreach ($team_contacts as $team_contact) {
                        	if (get_contact($dbc, $team_contact['contactid'], 'category') == 'Staff') {
                        		$team_contactids[] = $team_contact['contactid'];
                        	}
                        }
                        $team_contactids = implode(',', $team_contactids);
	                    $team_equipassigns = mysqli_fetch_array(mysqli_query($dbc, "SELECT GROUP_CONCAT(DISTINCT ea.`equipment_assignmentid` SEPARATOR ',') as ea_list, GROUP_CONCAT(DISTINCT ea.`equipmentid` SEPARATOR ',') as eq_list FROM `equipment_assignment` ea LEFT JOIN `equipment_assignment_staff` eas ON ea.`equipment_assignmentid` = eas.`equipment_assignmentid` WHERE ea.`deleted` = 0 AND DATE(`start_date`) <= '$calendar_start' AND DATE(ea.`end_date`) >= '$calendar_start' AND CONCAT(',',ea.`hide_days`,',') NOT LIKE '%,$calendar_start,%' AND ((',$team_contactids,' LIKE CONCAT('%,',eas.`contactid`,',%') AND eas.`deleted` = 0) OR `teamid` = '".$row['teamid']."')"));
						// $team_equipassigns = mysqli_fetch_array(mysqli_query($dbc, "SELECT GROUP_CONCAT(DISTINCT `equipment_assignmentid` SEPARATOR ',') as ea_list FROM `equipment_assignment` WHERE `deleted` = 0 AND DATE(`start_date`) <= '$calendar_start' AND DATE(`end_date`) >= '$calendar_start' AND CONCAT(',',`hide_days`,',') NOT LIKE '%,$calendar_start,%' AND `teamid` = '".$row['teamid']."'"))['ea_list'];
						echo "<a href='' onclick='$(this).find(\".block-item\").toggleClass(\"active\"); toggle_columns(\"team\"); return false;'><div class='block-item equip_assign_draggable ".(in_array($row['teamid'],$active_teams) ? 'active' : '')."' data-teamid='".$row['teamid']."' data-blocktype='team' data-contactids='".$team_contactids."' data-region='".$row['region']."' data-location='".$row['location']."' data-classification='".$row['classification']."' data-equipassign='".$team_equipassigns['ea_list']."' data-equipment='".$team_equipassigns['eq_list']."' data-restrict-assign='".$equip_multi_assign_staff_disallow."'>".($_GET['mode'] != 'staff' && $_GET['mode'] != 'contractors' ? "<img class='drag-handle' src='".WEBSITE_URL."/img/icons/drag_handle.png' style='float: right; width: 2em;'>" : "")."<span style=''>$team_name</span></div></a>";
					} ?>
				</div>
			</div>
		</div>
		<div class="active_blocks_teams active_blocks" data-accordion="collapse_teams" style="display: none;">
			<?php $team_list = mysqli_query($dbc, "SELECT * FROM `teams` WHERE `deleted` = 0 AND (DATE(`start_date`) <= DATE(CURDATE()) OR `start_date` IS NULL OR `start_date` = '' OR `start_date` = '0000-00-00') AND (DATE(`end_date`) >= DATE(CURDATE()) OR `end_date` IS NULL OR `end_date` = '' OR `end_date` = '0000-00-00')".$region_query);
			$active_teams = array_filter(explode(',',get_user_settings()['appt_calendar_teams']));
			while($row = mysqli_fetch_array($team_list)) { ?>
				<div class="block-item active" data-teamid="<?= $row['teamid'] ?>"><?= get_team_name($dbc, $row['teamid']) ?></div> 
			<?php } ?>
		</div>
		<?php } ?>
	</div>
	<div class="block-item"><img src="../img/icons/clock-button.png" style="height: 1em; margin-right: 1em;">Break</div>
<?php } ?>