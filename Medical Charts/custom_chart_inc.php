<?php
$chart_settings = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_custom_charts_settings` WHERE `name` = '".$_GET['type']."'"));
$client_cat = $chart_settings['client_category'];
if(empty($client_cat)) {
	$client_cat = 'Clients';
}
$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `category` = '$client_cat' AND `deleted` = 0 AND `status` > 0 AND `show_hide_user` = 1"),MYSQLI_ASSOC));
if(!empty($_POST['search_clientid'])) {
	$_GET['edit'] = $_POST['search_clientid'];
} else {
	$_GET['edit'] = array_values($query)[0];
}
$client_label = !empty(get_client($dbc, $_GET['edit'])) ? get_client($dbc, $_GET['edit']) : get_contact($dbc, $_GET['edit']);
if($chart_settings['no_client'] == 1) {
	$client_query = " AND `no_client` = 1 AND `clientid` = 0";
} else {
	$client_query = " AND `no_client` = 0 AND `clientid` = '".$_GET['edit']."'";
}
$date = date('Y-m-d');
if(!empty($_GET['custom_chart_choosedate'])) {
	$date = date('Y-m-d', strtotime($_GET['custom_chart_choosedate']));
} else if(!empty($_POST['custom_chart_choosedate'])) {
	$date = date('Y-m-d', strtotime($_POST['custom_chart_choosedate']));
}
$month = date('m', strtotime($date));
$year = date('Y', strtotime($date));
$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
?>
<script type="text/javascript">
function checkChartField(chk, checked) {
	var field = $(chk).closest('td');
	var chart_name = $('[name="chart_name"]').val();
	var clientid = $('[name="clientid"]').val();
	var no_client = $('[name="no_client"]').val();
	var year = $('[name="chart_year"]').val();
	var month = $('[name="chart_month"]').val();
	var day = $(field).data('day');
	var headingid = $(field).data('headingid');
	var fieldid = $(field).data('fieldid');

	var data = { chart_name: chart_name, clientid: clientid, no_client: no_client, year: year, month: month, day: day, headingid: headingid, fieldid: fieldid, checked: checked };

	$.ajax({
		url: '../Medical Charts/charts_ajax.php?fill=check_chart_field',
		type: 'POST',
		data: data,
		success: function(response) {
			if(checked == 1) {
				$(field).find('[name="field_checkbox"]').hide();
				$(field).find('.id-circle').show();
			} else {
				$(field).find('[name="field_checkbox"]').prop('checked',false).show();
				$(field).find('.id-circle').hide();
			}
		}
	});
}
function exportCustomChart() {
	var chart_name = $('[name="chart_name"]').val();
	var date = $('[name="chart_date"]').val();
	var clientid = $('[name="clientid"]').val();
	var no_client = $('[name="no_client"]').val();
	var url = '../Medical Charts/custom_chart_pdf.php?type='+chart_name+'&clientid='+clientid+'&no_client='+no_client+'&date='+date;

	window.open(url, '_blank');
}
function addChartComment(a) {
	var field = $(a).closest('td');
	var chart_name = $('[name="chart_name"]').val();
	var clientid = $('[name="clientid"]').val();
	var no_client = $('[name="no_client"]').val();
	var year = $('[name="chart_year"]').val();
	var month = $('[name="chart_month"]').val();
	var day = $(field).data('day');
	var headingid = $(field).data('headingid');
	var fieldid = $(field).data('fieldid');

	var url = "<?= WEBSITE_URL ?>/Medical Charts/custom_chart_comments.php?chart_name="+chart_name+"&clientid="+clientid+"&no_client="+no_client+"&year="+year+"&month="+month+"&day="+day+"&headingid="+headingid+"&fieldid="+fieldid;
	overlayIFrameSlider(url,'auto',true,true,$('#custom_chart_div').outerHeight() + 20);
}
function updateCommentCount(day, headingid, fieldid) {
	var field = $('td[data-day='+day+'][data-headingid='+headingid+'][data-fieldid='+fieldid+']');
	var num_comments = parseInt($(field).find('.custom-chart-comments').text());
	num_comments++
	$(field).find('.custom-chart-comments').text(num_comments).show();
}
</script>
<input type="hidden" name="chart_name" value="<?= $chart_type ?>">
<input type="hidden" name="clientid" value="<?= $_GET['edit'] ?>">
<input type="hidden" name="chart_year" value="<?= $year ?>">
<input type="hidden" name="chart_month" value="<?= $month ?>">
<input type="hidden" name="chart_date" value="<?= $date ?>">
<input type="hidden" name="no_client" value="<?= $chart_settings['no_client'] ?>">
<div class="form-group">
	<?php if($chart_settings['no_client'] != 1 ) { ?>
		<div class="col-sm-5">
		    <label class="col-sm-6 control-label"><span class="pull-right"><?= $client_cat == 'Business' ? 'Programs' : $client_cat ?>:</span></label>
		    <div class="col-sm-6">
		        <select name="search_clientid" class="form-control chosen-select-deselect">
		        	<option></option>
			        <?php
			            foreach ($query as $id) { ?>
			                <option <?= ($id == $_GET['edit'] ? 'selected' : '') ?> value="<?= $id ?>"><?= get_contact($dbc, $id) ?></option>
			            <?php }
			        ?>
		        </select>
		    </div>
		</div>
	<div class="col-sm-4 col-sm-offset-1">
	<?php } else { ?>
	<div class="col-sm-4 col-sm-offset-3">
	<?php } ?>
		<label class="control-label">Date:</label>
		<input type="text" name="custom_chart_choosedate" value="<?= $date ?>" class="form-control inline datepicker" />
		<button tyep="submit" name="load_custom_chart_date" value="load_custom_chart_date" class="btn brand-btn mobile-block">Submit</button>
	</div>
	<div class="pull-right">
		<button name="export_blood_glucose_chart" value="export_blood_glucose_chart" onclick="exportCustomChart(); return false;" class="btn brand-btn mobile-block pull-right">Export to PDF</button>
	</div>
</div>
<div class="clearfix"></div>
<div class="cheart_block" style="overflow-x: auto;">
	<?= !empty($client_label) && $client_label != '-' && $chart_settings['no_client'] != 1 ? '<h3>'.$client_label.'</h3>' : '' ?>
	<?php if(!empty($_GET['edit']) || $chart_settings['no_client'] == 1) { ?>
		<table class="table table-bordered chart_table" data-contact="<?= $_GET['edit'] ?>">
			<tr>
				<th><?= date('F Y', strtotime($date)) ?></th>
				<?php for($day_i = 1; $day_i <= $days_in_month; $day_i++) { ?>
					<th style="text-align: center;"><?= $day_i ?></th>
				<?php } ?>
			</tr>
			<?php $headings = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `field_config_custom_charts` WHERE `deleted` = 0 AND `name` = '$chart_type'"),MYSQLI_ASSOC);
			foreach ($headings as $heading) { ?>
				<tr>
					<td style="background-color: #CCC;" colspan="<?= $days_in_month + 1 ?>"><b><?= $heading['heading'] ?></b></td>
				</tr>
				<?php $fields = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `field_config_custom_charts_lines` WHERE `deleted` = 0 AND `headingid` = '".$heading['fieldconfigid']."'"),MYSQLI_ASSOC);
				foreach ($fields as $field) { ?>
					<tr>
						<td><?= $field['field'] ?></td>
						<?php for($day_i = 1; $day_i <= $days_in_month; $day_i++) {
							$field_checked = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `custom_charts` WHERE `chart_name` = '$chart_type' AND `headingid` = '".$heading['fieldconfigid']."' AND `fieldid` = '".$field['fieldconfigid']."' AND `year` = '$year' AND `month` = '$month' AND `day` = '$day_i' AND `deleted` = 0 $client_query")); ?>
							<td data-headingid="<?= $heading['fieldconfigid'] ?>" data-fieldid="<?= $field['fieldconfigid'] ?>" data-day="<?= $day_i ?>" align="center">
								<?php if(empty($field_checked)) {
									$staff = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `contactid` = '".$_SESSION['contactid']."'"));
								} else {
									$staff = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `contactid` = '".$field_checked['staffid']."'"));
								}
								$initials = ($staff['initials'] == '' ? ($staff['first_name'].$staff['last_name'] == '' ? $staffid : substr(decryptIt($staff['first_name']),0,1).substr(decryptIt($staff['last_name']),0,1)) : $staff['initials']);
								$colour = ($staff['calendar_color'] == '' ? '#6DCFF6' : $staff['calendar_color']);?>
								<span class="id-circle" style="background-color: <?= $colour ?>; font-family: 'Open Sans'; cursor: pointer; <?= (empty($field_checked) ? 'display:none;' : '') ?>" onclick="checkChartField(this, 0);" title="Click to Uncheck."><?= $initials ?></span>
								<input type="checkbox" name="field_checkbox" style="width: 20px; height: 20px; <?= (!empty($field_checked) ? 'display:none;' : '') ?>" value="1" onchange="checkChartField(this, 1);">
								<?php if($chart_settings['add_comments'] == 1) {
									$num_comments = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(`customchartcommid`) `num_rows` FROM `custom_charts_comments` WHERE `chart_name` = '$chart_type' AND `headingid` = '".$heading['fieldconfigid']."' AND `fieldid` = '".$field['fieldconfigid']."' AND `year` = '$year' AND `month` = '$month' AND `day` = '$day_i' AND `deleted` = 0 $client_query"))['num_rows']; ?>
									<a href="" onclick="addChartComment(this); return false" style="position: relative;" data-fieldid="<?= $field['fieldconfigid'] ?>" data-day="<?= $day_i ?>">
										<img class="inline-img" src="../img/icons/ROOK-reply-icon.png" title="View/Add Comments">
										<span class="custom-chart-comments" <?= !($num_comments > 0) ? 'style="display:none;"' : '' ?>><?= $num_comments ?></span>
									</a>
								<?php } ?>
							</td>
						<?php } ?>
					</tr>
				<?php }
			} ?>
		</table>
	<?php } else {
		echo '<h1>No Client Selected.</h1>';
	} ?>
</div>