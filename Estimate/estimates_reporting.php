<?php $estimate_report_stats = get_config($dbc, 'estimate_report_stats');
$estimate_report_alerts = get_config($dbc, 'estimate_report_alerts');
$startdate = !empty($_POST['startdate']) ? $_POST['startdate'] : date('Y-m-01');
$enddate = !empty($_POST['enddate']) ? $_POST['enddate'] : date('Y-m-t');
$staffid = !empty($_POST['staffid']) ? $_POST['staffid'] : '';
?>
<ul class="sidebar col-sm-3 hide-titles-mob">
	<a href="?reports=statistics"><li>Reports</li></a>
	<ul>
		<a href="?reports=statistics"><li class="<?= $_GET['reports'] == 'statistics' ? 'active blue' : '' ?>"><?= ESTIMATE_TILE ?> Stats</li></a>
		<a href="?reports=alerts"><li class="<?= $_GET['reports'] == 'alerts' ? 'active blue' : '' ?>"><?= ESTIMATE_TILE ?> Alerts</li></a>
	</ul>
</ul>
<div class='col-sm-9 has-main-screen hide-titles-mob'>
	<div class='main-screen'>
		<h3><?= $_GET['reports'] == 'alerts' ? ESTIMATE_TILE.' Alerts' : ESTIMATE_TILE.' Stats' ?></h3>

		<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
			<div class="form-group">
				<div class="col-sm-1"><label class="control-label">From:</label></div><div class="col-sm-3"><input type="text" name="startdate" type="text" class="datepicker form-control" value="<?= $startdate ?>"></div>
				<div class="col-sm-1"><label class="control-label">Until:</label></div><div class="col-sm-3"><input type="text" name="enddate" type="text" class="datepicker form-control" value="<?= $enddate ?>"></div>
				<div class="clearfix"></div>
				<div class="col-sm-1"><label class="control-label">Staff:</label></div>
				<div class="col-sm-3">
					<select name="staffid" data-placeholder="Select Staff" class="chosen-select-deselect">
						<option></option>
						<?php $staff_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `deleted` = 0 AND `status` > 0"),MYSQLI_ASSOC));
						foreach ($staff_list as $id) {
							echo '<option value="'.$id.'"'.($id == $staffid ? ' selected' : '').'>'.get_contact($dbc, $id).'</option>';
						} ?>
					</select>
				</div>
				<div class="col-sm-4">
					<button type="submit" name="search_reporting" value="Search" class="btn brand-btn mobile-block pull-right">Submit</button>
				</div>
			</div>
			<?php if($_GET['reports'] == 'alerts') {
				echo reporting_estimate_alerts($dbc, $startdate, $enddate, explode('#*#', $estimate_report_alerts), $staffid);
			} else {
				echo reporting_estimate_stats($dbc, $startdate, $enddate, explode('#*#', $estimate_report_stats), $staffid);
			} ?>
		</form>
	</div>
</div>

<?php
function reporting_estimate_stats($dbc, $from, $until, $statuses, $staff) {
	$staff_list = [];
	if (!empty($staff)) {
		$staff_list[] = $staff;
	} else {
		$staff_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `deleted` = 0 AND `status` > 0"),MYSQLI_ASSOC));
	}
	$html = '<div id="no-more-tables">';
	$html .= '<table class="table table-bordered text-center">';
	$html .= '<tr>';
	$html .= '<th>Staff</th>';
	$html .= '<th>Total Opportunities</th>';
	$html .= '<th>Total Value of Opportunities</th>';
	foreach ($statuses as $status) {
		$html .= '<th>Total '.$status.'</th>';
		$html .= '<th>Total Value of '.$status.'</th>';
		$html .= '<th>% of '.$status.' Opportunities</th>';
	}
	$html .= '</tr>';

	foreach ($staff_list as $id) {
		$estimate_summary = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(*) as num_estimates, SUM(total_price) as total_value FROM `estimate` WHERE CONCAT(',',`assign_staffid`,',') LIKE '%,".$id.",%' AND `status_date` >= '".$from."' AND `status_date` <= '".$until."' AND `deleted` = 0"));
		$page_url = '?status=all&startdate='.$from.'&enddate='.$until.'&staffid='.$id;
		$html .= '<tr>';
		$html .= '<td>'.get_contact($dbc, $id).'</td>';
		$html .= '<td><a href="'.$page_url.'">'.$estimate_summary['num_estimates'].'</a></td>';
		$html .= '<td><a href="'.$page_url.'">$'.number_format($estimate_summary['total_value'], 2).'</a></td>';
		foreach ($statuses as $status) {
			$estimate_stats = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(*) as num_estimates, SUM(total_price) as total_value FROM `estimate` WHERE CONCAT(',',`assign_staffid`,',') LIKE '%,".$id.",%' AND `status` = '".preg_replace('/[^a-z]/','',strtolower($status))."' AND `status_date` >= '".$from."' AND `status_date` <= '".$until."' AND `deleted` = 0"));
			$page_url = '?status='.preg_replace('/[^a-z]/','',strtolower($status)).'&startdate='.$from.'&enddate='.$until.'&staffid='.$id;
			$html .= '<td><a href="'.$page_url.'">'.$estimate_stats['num_estimates'].'</a></td>';
			$html .= '<td><a href="'.$page_url.'">$'.number_format($estimate_stats['total_value'], 2).'</a></td>';
			$html .= '<td><a href="'.$page_url.'">'.number_format($estimate_summary['num_estimates'] / $estimate_stats['num_estimates'] * 100, 1).'%</a></td>';
		}
		$html .= '</tr>';
	}

	$html .= '</table>';
	$html .= '</div>';

	return $html;
}

function reporting_estimate_alerts($dbc, $from, $until, $statuses, $staff) {
	$staff_list = [];
	if (!empty($staff)) {
		$staff_list[] = $staff;
	} else {
		$staff_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `deleted` = 0 AND `status` > 0"),MYSQLI_ASSOC));
	}

	$html = '<div id="no-more-tables">';
	$html .= '<table class="table table-bordered text-center">';
	$html .= '<tr>';
	$html .= '<th>Staff</th>';
	$html .= '<th>Upcoming Opportunities</th>';
	foreach ($statuses as $status) {
		$html .= '<th>Upcoming '.$status.'</th>';
	}
	$html .= '<th>Past Due Opportunities</th>';
	foreach ($statuses as $status) {
		$html .= '<th>Past Due '.$status.'</th>';
	}
	$html .= '</tr>';

	foreach ($staff_list as $id) {
		$html .= '<tr>';
		$today_date = date('Y-m-d');
		$all_estimates = mysqli_fetch_all(mysqli_query($dbc, "SELECT MIN(`due_date`) as alert_date FROM `estimate` e LEFT JOIN `estimate_actions` ea ON e.`estimateid` = ea.`estimateid` WHERE CONCAT(',',e.`assign_staffid`,',') LIKE '%,".$id.",%' AND e.`status_date` >= '".$from."' AND e.`status_date` <= '".$until."' AND e.`deleted` = 0 AND ea.`deleted` = 0 AND ea.`contactid` = '".$id."' HAVING MIN(`due_date`) IS NOT NULL"),MYSQLI_ASSOC);
		$all_upcoming = 0;
		$all_past = 0;
		foreach ($all_estimates as $estimate) {
			if (strtotime($estimate['alert_date']) >= strtotime($today_date)) {
				$all_upcoming++;
			} else if (strtotime($estimate['alert_date']) < strtotime($today_date)) {
				$all_past++;
			}
		}
		$status_upcoming = [];
		$status_past = [];
		foreach ($statuses as $status) {
			$status_estimates = mysqli_fetch_all(mysqli_query($dbc, "SELECT MIN(`due_date`) as alert_date FROM `estimate` e LEFT JOIN `estimate_actions` ea ON e.`estimateid` = ea.`estimateid` WHERE CONCAT(',',e.`assign_staffid`,',') LIKE '%,".$id.",%' AND e.`status_date` >= '".$from."' AND e.`status_date` <= '".$until."' AND e.`deleted` = 0 AND ea.`deleted` = 0 AND ea.`contactid` = '".$id."' AND e.`status` = '".preg_replace('/[^a-z]/','',strtolower($status))."' HAVING MIN(`due_date`) IS NOT NULL"),MYSQLI_ASSOC);
			$status_upcoming[$status] = 0;
			$status_past[$status] = 0;
			foreach ($status_estimates as $estimate) {
				if (strtotime($estimate['alert_date']) >= strtotime($today_date)) {
					$status_upcoming[$status]++;
				} else if (strtotime($estimate['alert_date']) < strtotime($today_date)) {
					$status_past[$status]++;
				}
			}
		}
		$html .= '<td>'.get_contact($dbc, $id).'</td>';
		$page_url = '?status=all&startdate='.$from.'&enddate='.$until.'&staffid='.$id.'&action_type=upcoming';
		$html .= '<td><a href="'.$page_url.'">'.$all_upcoming.'</a></td>';
		foreach ($statuses as $status) {
			$page_url = '?status='.preg_replace('/[^a-z]/','',strtolower($status)).'&startdate='.$from.'&enddate='.$until.'&staffid='.$id.'&action_type=upcoming';
			$html .= '<td><a href="'.$page_url.'">'.$status_upcoming[$status].'</a></td>';
		}
		$page_url = '?status=all&startdate='.$from.'&enddate='.$until.'&staffid='.$id.'&action_type=pastdue';
		$html .= '<td><a href="'.$page_url.'">'.$all_past.'</a></td>';
		foreach ($statuses as $status) {
			$page_url = '?status='.preg_replace('/[^a-z]/','',strtolower($status)).'&startdate='.$from.'&enddate='.$until.'&staffid='.$id.'&action_type=pastdue';
			$html .= '<td><a href="'.$page_url.'">'.$status_past[$status].'</a></td>';
		}

		$html .= '</tr>';
	}

	$html .= '</table>';
	$html .= '</div>';

	return $html;
}
?>