<?php include_once('../include.php');
include_once('../Ticket/field_list.php');

if(!empty($_GET['edit'])) {
	if(IFRAME_MODE) { ?>
		<script type="text/javascript">
		$(document).ready(function() {
			$('.overview-block a').click(function() {
				window.parent.location.href = $(this).prop('href');
				return false;
			});
		});
		</script>
	<?php }
	$ticketid = $_GET['edit'];
	$value_config = get_field_config($dbc, 'tickets');

	$get_ticket = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `tickets` WHERE `ticketid` = '$ticketid'"));
	$ticket_type = $get_ticket['ticket_type'];
	$businessid = $get_ticket['businessid'];
	$clientid = $get_ticket['clientid'];
	$projectid = $get_ticket['projectid'];
	$projecttype = get_project($dbc, $get_ticket['projectid'], 'projecttype');
	if(!empty($ticket_type)) {
		$value_config .= get_config($dbc, 'ticket_fields_'.$ticket_type).',';
	}
	$sort_field = 'Customer History';

	$field_list = $accordion_list[$sort_field];
	$field_sort_order = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_ticket_fields` WHERE `ticket_type` = '".(empty($ticket_type) ? 'tickets' : 'tickets_'.$ticket_type)."' AND `accordion` = '".$sort_field."'"))['fields'];
	if(empty($field_sort_order)) {
		$field_sort_order = $value_config;
	}
	$field_sort_order = explode(',', $field_sort_order);
	foreach ($field_list as $default_field) {
		if(!in_array($default_field, $field_sort_order)) {
			$field_sort_order[] = $default_field;
		}
	}

	$ticket_type_label = '';
	$ticket_tabs = array_filter(explode(',',get_config($dbc, 'ticket_tabs')));
	foreach($ticket_tabs as $ticket_tab) {
		if(config_safe_str($ticket_tab) == $ticket_type) {
			$ticket_type_label = $ticket_tab;
		}
	}

	$projecttype_label = '';
	$project_tabs = array_filter(explode(',',get_config($dbc, 'project_tabs')));
	foreach($project_tabs as $project_tab) {
		if(config_safe_str($project_tab) == $projecttype) {
			$projecttype_label = $project_tab;
		}
	}

	echo '<h3 style="margin: 20px 20px 0px 20px;">'.get_ticket_label($dbc, $get_ticket).'</h3>';
	$block = '';
	foreach($field_sort_order as $field_sort_field) {
		if(strpos($value_config, ',Customer History Business Ticket Type,') !== FALSE && $field_sort_field == 'Customer History Business Ticket Type') {
			if($businessid > 0) {
				$block .= '<div class="overview-block">';
				$block .= '<h4>'.get_client($dbc, $businessid).' - Last 5 by '.TICKET_NOUN.' Type'.(!empty($ticket_type_label) ? ' ('.$ticket_type_label.')' : '').'</h4>';
				$tickets = mysqli_query($dbc, "SELECT * FROM `tickets` WHERE `ticket_type` = '$ticket_type' AND `businessid` = '$businessid' AND `deleted` = 0 AND `ticketid` != '$ticketid' ORDER BY `ticketid` DESC LIMIT 0, 5");
				if(mysqli_num_rows($tickets) > 0) {
					while($row = mysqli_fetch_assoc($tickets)) {
						$block .= '<p><a style="color:black !important;" href="'.WEBSITE_URL.'/Ticket/index.php?edit='.$row['ticketid'].'">'.get_ticket_label($dbc, $row).'</a></p>';
					}
				} else {
					$block .= '<p>No '.TICKET_TILE.' Found.</p>';
				}
				$block .= '</div>';
			}
		} else if(strpos($value_config, ',Customer History Business Project Type,') !== FALSE && $field_sort_field == 'Customer History Business Project Type') {
			if($businessid > 0 && $projectid > 0) {
				$block .= '<div class="overview-block">';
				$block .= '<h4>'.get_client($dbc, $businessid).' - Last 5 by '.PROJECT_NOUN.' Type'.(!empty($projecttype_label) ? ' ('.$projecttype_label.')' : '').'</h4>';
				$tickets = mysqli_query($dbc, "SELECT `tickets`.* FROM `tickets` LEFT JOIN `project` ON `tickets`.`projectid` = `project`.`projectid` WHERE `tickets`.`businessid` = '$businessid' AND `project`.`projecttype` = '$projecttype' AND `tickets`.`deleted` = 0 AND `tickets`.`ticketid` != '$ticketid' ORDER BY `tickets`.`ticketid` DESC LIMIT 0, 5");
				if(mysqli_num_rows($tickets) > 0) {
					while($row = mysqli_fetch_assoc($tickets)) {
						$block .= '<p><a style="color:black !important;" href="'.WEBSITE_URL.'/Ticket/index.php?edit='.$row['ticketid'].'">'.get_ticket_label($dbc, $row).'</a></p>';
					}
				} else {
					$block .= '<p>No '.TICKET_TILE.' Found.</p>';
				}
				$block .= '</div>';
			}
		} else if(strpos($value_config, ',Customer History Business Ticket Project Type,') !== FALSE && $field_sort_field == 'Customer History Business Ticket Project Type') {
			if($businessid > 0 && $projectid > 0) {
				$block .= '<div class="overview-block">';
				$block .= '<h4>'.get_client($dbc, $businessid).' - Last 5 by '.TICKET_NOUN.' Type'.(!empty($ticket_type_label) ? ' ('.$ticket_type_label.')' : '').' & '.PROJECT_NOUN.' Type'.(!empty($projecttype_label) ? ' ('.$projecttype_label.')' : '').'</h4>';
				$tickets = mysqli_query($dbc, "SELECT `tickets`.* FROM `tickets` LEFT JOIN `project` ON `tickets`.`projectid` = `project`.`projectid` WHERE `tickets`.`businessid` = '$businessid' AND `project`.`projecttype` = '$projecttype' AND `tickets`.`ticket_type` = '$ticket_type' AND `tickets`.`deleted` = 0 AND `tickets`.`ticketid` != '$ticketid' ORDER BY `tickets`.`ticketid` DESC LIMIT 0, 5");
				if(mysqli_num_rows($tickets) > 0) {
					while($row = mysqli_fetch_assoc($tickets)) {
						$block .= '<p><a style="color:black !important;" href="'.WEBSITE_URL.'/Ticket/index.php?edit='.$row['ticketid'].'">'.get_ticket_label($dbc, $row).'</a></p>';
					}
				} else {
					$block .= '<p>No '.TICKET_TILE.' Found.</p>';
				}
				$block .= '</div>';
			}
		} else if(strpos($value_config, ',Customer History Customer Ticket Type,') !== FALSE && $field_sort_field == 'Customer History Customer Ticket Type') {
			foreach(explode(',', $clientid) as $client_id) {
				if($client_id > 0) {
					$block .= '<div class="overview-block">';
					$block .= '<h4>'.(!empty(get_client($dbc, $client_id)) ? get_client($dbc, $client_id) : get_contact($dbc, $client_id)).' - Last 5 by '.TICKET_NOUN.' Type'.(!empty($ticket_type_label) ? ' ('.$ticket_type_label.')' : '').'</h4>';
					$tickets = mysqli_query($dbc, "SELECT * FROM `tickets` WHERE `ticket_type` = '$ticket_type' AND CONCAT(',',`clientid`,',') LIKE ('%,$client_id,%') AND `deleted` = 0 AND `ticketid` != '$ticketid' ORDER BY `ticketid` DESC LIMIT 0, 5");
					if(mysqli_num_rows($tickets) > 0) {
						while($row = mysqli_fetch_assoc($tickets)) {
							$block .= '<p><a style="color:black !important;" href="'.WEBSITE_URL.'/Ticket/index.php?edit='.$row['ticketid'].'">'.get_ticket_label($dbc, $row).'</a></p>';
						}
					} else {
						$block .= '<p>No '.TICKET_TILE.' Found.</p>';
					}
					$block .= '</div>';
				}
			}
		} else if(strpos($value_config, ',Customer History Customer Project Type,') !== FALSE && $field_sort_field == 'Customer History Customer Project Type') {
			foreach(explode(',', $clientid) as $client_id) {
				if($client_id > 0 && $projectid > 0) {
					$block .= '<div class="overview-block">';
					$block .= '<h4>'.(!empty(get_client($dbc, $client_id)) ? get_client($dbc, $client_id) : get_contact($dbc, $client_id)).' - Last 5 by '.PROJECT_NOUN.' Type'.(!empty($projecttype_label) ? ' ('.$projecttype_label.')' : '').'</h4>';
					$tickets = mysqli_query($dbc, "SELECT `tickets`.* FROM `tickets` LEFT JOIN `project` ON `tickets`.`projectid` = `project`.`projectid` WHERE CONCAT(',',`tickets`.`clientid`,',') LIKE ('%,$client_id,%') AND `project`.`projecttype` = '$projecttype' AND `tickets`.`deleted` = 0 AND `tickets`.`ticketid` != '$ticketid' ORDER BY `tickets`.`ticketid` DESC LIMIT 0, 5");
					if(mysqli_num_rows($tickets) > 0) {
						while($row = mysqli_fetch_assoc($tickets)) {
							$block .= '<p><a style="color:black !important;" href="'.WEBSITE_URL.'/Ticket/index.php?edit='.$row['ticketid'].'">'.get_ticket_label($dbc, $row).'</a></p>';
						}
					} else {
						$block .= '<p>No '.TICKET_TILE.' Found.</p>';
					}
					$block .= '</div>';
				}
			}
		} else if(strpos($value_config, ',Customer History Customer Ticket Project Type,') !== FALSE && $field_sort_field == 'Customer History Customer Ticket Project Type') {
			foreach(explode(',', $clientid) as $client_id) {
				if($client_id > 0 && $projectid > 0) {
					$block .= '<div class="overview-block">';
					$block .= '<h4>'.(!empty(get_client($dbc, $client_id)) ? get_client($dbc, $client_id) : get_contact($dbc, $client_id)).' - Last 5 by '.TICKET_NOUN.' Type'.(!empty($ticket_type_label) ? ' ('.$ticket_type_label.')' : '').' & '.PROJECT_NOUN.' Type'.(!empty($projecttype_label) ? ' ('.$projecttype_label.')' : '').'</h4>';
					$tickets = mysqli_query($dbc, "SELECT `tickets`.* FROM `tickets` LEFT JOIN `project` ON `tickets`.`projectid` = `project`.`projectid` WHERE CONCAT(',',`tickets`.`clientid`,',') LIKE ('%,$client_id,%') AND `project`.`projecttype` = '$projecttype' AND `tickets`.`ticket_type` = '$ticket_type' AND `tickets`.`deleted` = 0 AND `tickets`.`ticketid` != '$ticketid' ORDER BY `tickets`.`ticketid` DESC LIMIT 0, 5");
					if(mysqli_num_rows($tickets) > 0) {
						while($row = mysqli_fetch_assoc($tickets)) {
							$block .= '<p><a style="color:black !important;" href="'.WEBSITE_URL.'/Ticket/index.php?edit='.$row['ticketid'].'">'.get_ticket_label($dbc, $row).'</a></p>';
						}
					} else {
						$block .= '<p>No '.TICKET_TILE.' Found.</p>';
					}
					$block .= '</div>';
				}
			}
		}
	}
	echo $block;
} else {
	echo '<h3 style="margin: 20px 20px 0px 20px;">Invalid '.TICKET_NOUN.'</h3>';
}