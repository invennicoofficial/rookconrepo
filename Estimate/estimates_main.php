<script>
$(document).ready(function() {
	$('.panel-heading').click(loadPanel);
});
function loadPanel() {
	body = $(this).closest('.panel').find('.panel-body');
	if($(body).data('file') != '') {
		$(body).html('Loading...');
		$.ajax({
			url: $(body).data('file'),
			method: 'POST',
			response: 'html',
			success: function(response) {
				$(body).html(response);
			}
		});
	}
}
</script>
<?php $status = explode('#*#', get_config($dbc, 'estimate_status'));
$summarized = get_config($dbc, 'estimate_summarize');
if($summarized != '') {
	$summarized = explode('#*#', $summarized);
} else {
	$summarized = $status;
}
$summary = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(*) total, SUM(`total_price`) value FROM `estimate` WHERE `deleted`=0"));
$summary_total = $summary['total'];
$closed_status = preg_replace('/[^a-z]/','',strtolower(get_config($dbc, 'estimate_project_status')));
$closed_date = get_user_settings()['estimate_closed'];
$closed_date = strtotime($closed_date) > date('Y-m-01') ? $closed_date : date('Y-m-01');
$summary_view = explode(',',get_config($dbc, 'estimate_summary_view')); ?>
<div class="collapsible-horizontal collapsed hide-titles-mob">
	<div class="col-xs-12 col-sm-6 col-md-3 gap-top">
		<div class="summary-block">
			<span class="text-lg"><?= number_format($summary['total'],0) ?></span><br />Total Estimates
		</div>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-3 gap-top">
		<div class="summary-block">
			<a href="?status=all"><span class="text-lg">$<?= number_format($summary['value'],2) ?></span></a><br />Value of Estimates
		</div>
	</div>
	<?php foreach($summarized as $summ_status) {
		$summary = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(*) total, SUM(`total_price`) value FROM `estimate` WHERE `deleted`=0 AND `status`='".preg_replace('/[^a-z]/','',strtolower($summ_status))."'")); ?>
		<div class="col-xs-12 col-sm-6 col-md-3 gap-top">
			<div class="summary-block">
				<a href="?status=<?= preg_replace('/[^a-z]/','',strtolower($summ_status)) ?>"><span class="text-lg"><?= number_format($summary['total'],0) ?></span></a><br />Total <?= $summ_status ?>
			</div>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-3 gap-top">
			<div class="summary-block">
				<a href="?status=<?= preg_replace('/[^a-z]/','',strtolower($summ_status)) ?>"><span class="text-lg">$<?= number_format($summary['value'],2) ?></span></a><br />Value of <?= $summ_status ?>
			</div>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-3 gap-top">
			<div class="summary-block">
				<a href="?status=<?= preg_replace('/[^a-z]/','',strtolower($summ_status)) ?>"><span class="text-lg"><?= number_format($summary['total'] / $summary_total * 100,1) ?>%</span></a><br />% of <?= $summ_status ?>
			</div>
		</div>
	<?php } ?>
</div>
<div id="estimate_accordions" class="sidebar show-on-mob panel-group block-panels col-xs-12">
	<?php if(!empty(array_filter($summary_view))) { ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#estimate_accordions" href="#collapse_summary">
						Summary<span class="glyphicon glyphicon-plus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_summary" class="panel-collapse collapse">
				<div class="panel-body" data-file="estimates_summary.php">
				</div>
			</div>
		</div>
	<?php } ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#estimate_accordions" href="#collapse_report">
					Reporting<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

		<div id="collapse_report" class="panel-collapse collapse">
			<div class="panel-body" data-file="">
				<div class="col-xs-12 col-sm-6 col-md-3 gap-top">
					<div class="summary-block">
						<span class="text-lg"><?= number_format($summary['total'],0) ?></span></a><br />Total Estimates
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-3 gap-top">
					<div class="summary-block">
						<a href="?status=all"><span class="text-lg">$<?= number_format($summary['value'],2) ?></span></a><br />Value of Estimates
					</div>
				</div>
				<?php foreach($summarized as $summ_status) {
					$summary = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(*) total, SUM(`total_price`) value FROM `estimate` WHERE `deleted`=0 AND `status`='".preg_replace('/[^a-z]/','',strtolower($summ_status))."'")); ?>
					<div class="col-xs-12 col-sm-6 col-md-3 gap-top">
						<div class="summary-block">
							<a href="?status=<?= preg_replace('/[^a-z]/','',strtolower($summ_status)) ?>"><span class="text-lg"><?= number_format($summary['total'],0) ?></span></a><br />Total <?= $summ_status ?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-3 gap-top">
						<div class="summary-block">
							<a href="?status=<?= preg_replace('/[^a-z]/','',strtolower($summ_status)) ?>"><span class="text-lg">$<?= number_format($summary['value'],2) ?></span></a><br />Value of <?= $summ_status ?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-3 gap-top">
						<div class="summary-block">
							<a href="?status=<?= preg_replace('/[^a-z]/','',strtolower($summ_status)) ?>"><span class="text-lg"><?= number_format($summary['total'] / $summary_total * 100,1) ?>%</span></a><br />% of <?= $summ_status ?>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php $statuses = $set_status = [];
	foreach($status as $status_name) {
		$statuses[$status_name] = preg_replace('/[^a-z]/','',strtolower($status_name));
		$set_status[] = "'".$statuses[$status_name]."'";
	}
	$sqlstatuses = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(*) estimates FROM `estimate` WHERE `status` NOT IN ('',".implode(',',$set_status).") AND `deleted`=0 GROUP BY `status`"))['estimates'];
	if($sqlstatuses > 0) {
		$statuses['Uncategorized'] = "misc";
	}
	foreach($statuses as $status_name => $status_id) { ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#estimate_accordions" href="#collapse_<?= $status_id ?>">
						<?= $status_name ?><span class="glyphicon glyphicon-plus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_<?= $status_id ?>" class="panel-collapse collapse">
				<div class="panel-body" data-file="estimates_list.php?status=<?= $status_id ?>">
					Loading...
				</div>
			</div>
		</div>
	<?php } ?>
</div>
<div class="sidebar-override tile-sidebar inherit-height standard-collapsible hide-titles-mob overflow-y">
    <ul>
		<?php if(array_filter($summary_view)) {
			if(!isset($_GET['status'])) {
				$_GET['status'] = 'summary_view';
			} ?>
			<a href="?view=summary"><li class="<?= $_GET['status'] == 'summary_view' ? 'active blue' : '' ?>">Summary</li></a>
		<?php } ?>
        <a href="?status="><li class="<?= $_GET['status'] == '' && $_GET['status'] != 'summary_view' ? 'active blue' : '' ?>">Dashboard</li></a>
        <li class="sidebar-higher-level highest-level"><a class="cursor-hand <?= $_GET['status'] != '' && $_GET['status'] != 'summary_view' ? 'active blue' : 'collapsed' ?>" data-toggle="collapse" data-target="#collapse_status_list">Status<span class="arrow"></a>
			<ul id="collapse_status_list" class="collapse <?= $_GET['status'] != '' && $_GET['status'] != 'summary_view' ? 'in' : '' ?>">
				<?php foreach($statuses as $status_name => $status_id) { ?>
					<a href="?status=<?= $status_id ?>"><li class="<?= $_GET['status'] == $status_id || $_GET['status'] == 'all' ? 'active blue' : '' ?>"><?= $status_name ?></li></a>
				<?php } ?>
			</ul>
		</li>
    </ul>
</div>
<div class='scale-to-fill has-main-screen hide-titles-mob'>
	<div class='main-screen main-screen-override'>
		<?php if($_GET['status'] == 'summary_view') {
			include('estimates_summary.php');
		} else if($_GET['status'] != '') {
			include('estimates_list.php');
		} else {
			include('estimates_dashboard.php');
		} ?>
	</div>
</div>
<div class="clearfix"></div>