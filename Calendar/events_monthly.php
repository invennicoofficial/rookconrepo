<style>
.today-btn {
  color: #fafafa;
  background: green;
  border: 2px solid #fafafa; }
.highlightCell {
	background-color: rgba(0,0,0,0.2);
}
</style>
<script type="text/javascript" src="calendar.js"></script>
<script>
var page_mode = 'staff';
$(document).ready(function() {
	// $(window).resize(resize_calendar_view).resize();
	<?php if (!vuaed_visible_function($dbc, 'calendar_rook')) { ?>
		$('.calendarSortable').sortable('destroy');
	<?php } ?>
	toggle_columns();
	reload_all_data_month();
});
function toggle_columns() {
	// Hide deselected columns
	var visibles = [];
	$('.project_panels').find('.block-item.active').each(function() {
		var projectid = $(this).data('projectid');
		if(projectid > 0) {
			visibles.push(projectid);
		}
	});
	
	$.ajax({
		url: 'calendar_ajax_all.php?fill=selected_projects&offline='+offline_mode,
		method: 'POST',
		data: { projects: visibles },
		success: function(response) {
		}
	});
            
	$('.calendar_table .calendarSortable').filter(function() { return $(this).data('contact') > 0; }).hide();
	visibles.forEach(function (projectid) {
		$('.calendar_table .calendarSortable').filter(function() { return $(this).data('contact') == projectid; }).show();
	});
	resize_calendar_view_monthly();
}
</script>
<?php $calendar_start = $_GET['date'];
if($calendar_start == '') {
	$calendar_start = date('Y-m-d');
} else {
	$calendar_start = date('Y-m-d', strtotime($calendar_start));
}
$weekly_start = get_config($dbc, 'event_weekly_start');
if($weekly_start == 'Sunday') {
	$weekly_start = 1;
} else {
	$weekly_start = 0;
}
$day = date('w', strtotime($calendar_start));
$week_start_date = date('F j', strtotime($calendar_start.' -'.($day - 1 + $weekly_start).' days'));
$week_end_date = date('F j, Y', strtotime($calendar_start.' -'.($day - 7 + $weekly_start).' days'));

$weekly_days = explode(',',get_config($dbc, 'event_weekly_days'));

$projectid = $_GET['projectid'];
$calendar_type = 'events';
$all_projects = [];
?>
<div class="hide_on_iframe ticket-calendar calendar-screen" style="padding-bottom: 0px;">
	<div class="collapsible pull-left">
		<input type="text" class="search-text form-control" placeholder="Search <?= $staff_schedule_client_type ?>">
		<div class="sidebar panel-group block-panels" id="category_accordions" style="margin: 1.5em 0 0.5em; overflow: hidden; padding-bottom: 0;">
			<?php $project_tabs = get_config($dbc, 'project_tabs');
			if($project_tabs == '') {
				$project_tabs = 'Client,SR&ED,Internal,R&D,Business Development,Process Development,Addendum,Addition,Marketing,Manufacturing,Assembly';
			}
			$project_tabs = explode(',',$project_tabs);
			$project_vars = [];
			foreach($project_tabs as $item) {
				$project_vars[] = preg_replace('/[^a-z_]/','',str_replace(' ','_',strtolower($item)));
			}
			$active_projects = array_filter(explode(',',get_user_settings()['events_calendar_projects']));
			
			foreach($project_tabs as $project_i => $project_tab) {
				if(!check_subtab_persmission($dbc, 'project', ROLE, $project_vars[$project_i])) {
					unset($project_tabs[$project_i]);
					unset($project_vars[$project_i]);
				}
			}

			foreach($project_tabs as $project_i => $project_tab) { ?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#category_accordions" href="#collapse_<?= $project_vars[$project_i] ?>">
								<span style="display: inline-block; width: calc(100% - 6em);"><?= $project_tabs[$project_i] ?></span><span class="glyphicon glyphicon-minus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_<?= $project_vars[$project_i] ?>" class="project_panels panel-collapse collapse">
						<div class="panel-body panel-body-height" style="overflow-y: auto; padding: 0;">
							<?php $project_list = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `project` WHERE `projecttype` = '".$project_vars[$project_i]."' AND `deleted` = 0"),MYSQLI_ASSOC);
							foreach ($project_list as $project) {
								$all_projects[] = $project; ?>
								<a href="" onclick="$(this).find('.block-item').toggleClass('active'); toggle_columns(); retrieve_items_month(this); return false;"><div class="block-item <?= (in_array($project['projectid'], $active_projects) ? 'active' : '') ?>" data-projectid="<?= $project['projectid'] ?>"><span style=""><?= $project['project_name'] ?></span></div></a>
							<?php } ?>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
		<div class="block-item"><img src="../img/icons/clock-button.png" style="height: 1em; margin-right: 1em;">Break</div>
	</div>
	<?php
	$search_month = date('F');
	$search_year = date('Y');
	if(isset($_GET['date'])) {
		$search_month = date('F', strtotime($_GET['date']));
		$search_year = date('Y', strtotime($_GET['date']));
	}
	$calendar_month = date("n", strtotime($search_month));
	$calendar_year = $search_year;

	$page_query = $_GET;
	if(isset($_GET['shiftid'])) {
		echo '<div class="scalable calendar-month-sidebar">';
		include('shifts.php');
		echo '</div>';	
	}

	if ($_GET['region']) {
		$region_url = '&region='.$_GET['region'];
	}
	?>
	<div class="scale-to-fill">
		<div class="col-sm-12 calendar_view" style="position: relative; left: -1px; background-color: #fff; margin: 0 0 0.4em 0; padding: 0; overflow: auto;">
			<?php include('monthly_display.php'); ?>
		</div>
		<div class="loading_overlay" style="display: none;"><div class="loading_wheel"></div></div>

		<a href="" onclick="changeDate('', 'prev'); return false;"><div class="block-button" style="margin: 0;"><img src="../img/icons/back-arrow.png" style="height: 1em;">&nbsp;</div></a>
		<div class="block-button">Month</div>
		<a href="" onclick="changeDate('', 'next'); return false;"><div class="block-button">&nbsp;<img src="../img/icons/next-arrow.png" style="height: 1em;"></div></a>
		<a href="?type=event&view=daily<?= $region_url ?>"><div class="block-button" style="margin-left: 1em;">Day</div></a>
		<a href="?type=event&view=weekly<?= $region_url ?>"><div class="block-button">Week</div></a>
		<a href="?type=event&view=monthly<?= $region_url ?>"><div class="block-button active blue">Month</div></a>
		<?php if($ticket_status_color_code_legend == 1 && $wait_list == 'ticket') { ?>
			<div class="block-button legend-block" style="position: relative;">
				<div class="block-button ticket-status-legend" style="display: none; width: 20em; position: absolute; bottom: 1em;"><?= $ticket_status_legend ?></div>
				<img src="../img/legend-icon.png">
			</div>
		<?php } ?>
		<a href="" onclick="$('.set_date').focus(); return false;"><div class="block-button pull-right"><img src="../img/icons/calendar-button.png" style="height: 1em; margin-right: 1em;">Go To Date</div></a>
		<?php unset($page_query['date']); ?>
		<a href="" onclick="changeDate('<?= date('Y-m-d') ?>'); return false;"><div class="block-button pull-right">Today</div></a>
		<input value="<?= $calendar_start ?>" type="text" style="border: 0; width: 0;" class="pull-right datepicker set_date" onchange="changeDate(this.value);">
		<?php $page_query['date'] = $_GET['date']; ?>
	</div>
	<div class="clearfix"></div>
</div>