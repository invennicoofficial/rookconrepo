<?php include_once('../include.php');
checkAuthorised('calendar_rook');
include_once('../Calendar/calendar_functions_inc.php');
// error_reporting(E_ALL);

$from_date = date('Y-m-d');
$to_date = date('Y-m-d', strtotime($from_date.' + 1 month'));
$shift_conflicts_check_num = get_config($dbc, 'shift_conflicts_check_num');
$shift_conflicts_check_type = get_config($dbc, 'shift_conflicts_check_type');
if($shift_conflicts_check_num > 0 && in_array($shift_conflicts_check_type, ['months','weeks'])) {
    $to_date = date('Y-m-d', strtotime($from_date.' + '.$shift_conflicts_check_num.' '.$shift_conflicts_check_type));
}
$contact_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `deleted`=0 AND `status`=1 AND `show_hide_user`=1 AND IFNULL(`calendar_enabled`,1)=1".$region_query),MYSQLI_ASSOC));
$page_query = $_GET;
$has_conflict = false;
?>
<script type="text/javascript">
$(document).ready(function() {
	var height = $(window).height() - $('.calendar-screen').offset().top - $('#footer').height();
	height = height > 500 ? height : 500;
	$('.calendar-screen .scale-to-fill,.calendar-screen .scalable').height(height);
});
</script>
<div class="calendar-screen set-height">
	<?php if($_GET['shiftid']): ?>
		<div class="pull-right scalable unbooked_view" style="height: 30em; overflow: auto; <?= $scale_style ?>">
			<?php include('shifts.php'); ?>
		</div>
	<?php endif; ?>
	<div class="scale-to-fill">
		<div class="conflicts_block">
			<?php $page_query['view'] = $page_query['previous_view']; ?>
			<a href="?<?= http_build_query($page_query) ?>" class="btn brand-btn">Back To Calendar</a>
			<?php $page_query['view'] = 'conflicts';
			foreach($contact_list as $contact_id) {
				$conflicts = [];
				for($current_date = $from_date; strtotime($current_date) <= strtotime($to_date); $current_date = date('Y-m-d', strtotime($current_date.' + 1 day'))) {
					$current_conflicts = getShiftConflicts($dbc, $contact_id, $current_date);
					if(!empty($current_conflicts)) {
						$conflicts = array_filter(array_unique(array_merge($conflicts, $current_conflicts)));
					}
				}
				if(!empty($conflicts)) {
					$has_conflicts = true; ?>
					<div data-contact="<?= $contact_id ?>" class="conflict_block">
						<h4 style="color: #fff;"><?= get_contact($dbc, $contact_id) ?></h4>
						<table id="no-more-tables" class="table table-bordered" style="background-color: #fff">
							<tr>
								<th>Shift</th>
								<th>Conflict</th>
							</tr>
							<?php foreach($conflicts as $conflict) {
								$conflict_shifts = explode('*#*', $conflict);
								$current_shift = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `contacts_shifts` WHERE `shiftid` = '".$conflict_shifts[0]."'"));
								$conflict_shift = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `contacts_shifts` WHERE `shiftid` = '".$conflict_shifts[1]."'")); ?>
								<tr>
									<?php $page_query['shiftid'] = $current_shift['shiftid']; ?>
									<td data-title="Shift"><a href="?<?= http_build_query($page_query) ?>">Shift #<?= $current_shift['shiftid'] ?>: <?= $current_shift['starttime'] ?> - <?= $current_shift['endtime'] ?></a></td>
									<?php $page_query['shiftid'] = $conflict_shift['shiftid']; ?>
									<td data-title="Conflict"><a href="?<?= http_build_query($page_query) ?>">Shift #<?= $conflict_shift['shiftid'] ?>: <?= $conflict_shift['starttime'] ?> - <?= $conflict_shift['endtime'] ?></a></td>
								</tr>
							<?php } ?>
						</table>
					</div>
				<?php }
			} ?>
			<?php if(!$has_conflicts) {
				echo '<h3 style="color: #fff;">No Conflicts Found.</h3>';
			} ?>
		</div>
	</div>
</div>