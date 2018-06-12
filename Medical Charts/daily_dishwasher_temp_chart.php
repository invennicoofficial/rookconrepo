<?php
$value_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `daily_dishwasher_temp` FROM `field_config`"));
$value_config = ','.$value_config['daily_dishwasher_temp'].',';
$charts_time_format = get_config($dbc, 'charts_time_format');

$date = date('Y-m-d');
if(!empty($_GET['dishwasher_record_choosedate'])) {
	$date = date('Y-m-d', strtotime($_GET['dishwasher_record_choosedate']));
} else if(!empty($_POST['dishwasher_record_choosedate'])) {
	$date = date('Y-m-d', strtotime($_POST['dishwasher_record_choosedate']));
} ?>
<script type="text/javascript">
$(window).on('load', function() {
	<?php if(isset($_GET['subtab']) && $_GET['subtab'] == 'dishwasher Temp Chart') { ?>
		$('#nav_daily_dishwasher_temp').trigger('click');
	<?php } ?>
});
$(document).on('change', 'select[name="dishwasher_record_staff"]', function() { saveFieldDishwasherTempRecord(this); });
function saveFieldDishwasherTempRecord(field) {
	var row = $(field).closest('tr.dishwasher_record_row');
	var contactid = $(field).closest('table.dishwasher_record_table').data('contact');
	var id = $(row).attr('data-id');
	var date = $(row).find('[name="dishwasher_record_date"]').val();
	var time = $(row).find('[name="dishwasher_record_time"]').val();
	var water_temp = $(row).find('[name="dishwasher_record_water_temp"]').val();
	var note = $(row).find('[name="dishwasher_record_note"]').val();
	var staff = $(row).find('[name="dishwasher_record_staff"]').val();
	var history = $(row).find('[name="dishwasher_record_history"]').val();
	var data = { contactid: contactid, id: id, date: date, time: time, water_temp: water_temp, note: note, staff: staff, history: history };
	$.ajax({
		url: '../Contacts/contacts_ajax.php?action=daily_dishwasher_temp',
		type: 'POST',
		data: data,
		success: function(response) {
			if (id == 0) {
				$(row).removeAttr('data-id').attr('data-id', response);
			}
		}
	});
}
function addDishwasherTempRecord() {
	destroyInputs('table.dishwasher_record_table:visible');
	var block = $('table.dishwasher_record_table:visible').find('tr.dishwasher_record_row').last();
	var clone = $(block).clone();

	clone.find('.form-control').val('');
    resetChosen(clone.find('select'));
    clone.find('[name="dishwasher_record_date"]').val($('#dishwasher_record_today').val());
 //    clone.find('[name="dishwasher_record_date"]').attr("id", "").removeClass('hasDatepicker').removeData('datepicker').unbind().datepicker({
	// 	changeMonth: true,
	// 	changeYear: true,
	// 	yearRange: '1920:2025',
	// 	dateFormat: 'yy-mm-dd',
	// });
 //    clone.find('[name="dishwasher_record_time"]').attr("id", "").removeClass('hasDatepicker').removeData('datetimepicker').unbind().timepicker({
	// 	controlType: 'select',
	// 	oneLine: true,
	// 	timeFormat: 'hh:mm tt'
 //    });
    clone.removeAttr('data-id').attr('data-id', 0);

    block.after(clone);
	initInputs('table.dishwasher_record_table:visible');
}
function deleteDishwasherTempRecord(btn) {
	if($('table.dishwasher_record_table:visible').find('tr.dishwasher_record_row').length <= 1) {
		addDishwasherTempRecord();
	}

	var id = $(btn).closest('tr.dishwasher_record_row').data('id');
	$.ajax({
		url: '../Contacts/contacts_ajax.php?action=daily_dishwasher_temp_delete',
		type: 'POST',
		data: { id: id },
		success: function(repsonse) {
			$(btn).closest('tr.dishwasher_record_row').remove();
		}
	});
}
function exportDishwasherTempRecord() {
	var contactid = '<?= $_GET['edit']; ?>';
	var date = $('[name="dishwasher_record_choosedate"]').val();
	var url = '../Medical Charts/daily_dishwasher_temp_pdf.php?contactid='+contactid+'&date='+date;

	window.open(url, '_blank');
}
</script>

<input type="hidden" name="contactid" value="<?= $_GET['edit'] ?>">
<div class="form-group">
	<?php if(FOLDER_NAME == 'medical%20charts') { ?>
	<div class="col-sm-5">
	    <label class="col-sm-6 control-label"><span class="pull-right">Program:</span></label>
	    <div class="col-sm-6">
	        <select name="search_businessid" class="form-control chosen-select-deselect">
	        	<option></option>
		        <?php
		            $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `category` = 'Business' AND `deleted` = 0 AND `status` > 0 AND `show_hide_user` = 1"),MYSQLI_ASSOC));
		            foreach ($query as $id) { ?>
		                <option <?= ($id == $_GET['edit'] ? 'selected' : '') ?> value="<?= $id ?>"><?= get_client($dbc, $id) ?></option>
		            <?php }
		            if(!in_array($_GET['edit'],$query) && $_GET['edit'] > 0) { ?>
		            	<option selected value="<?= $_GET['edit'] ?>"><?= get_client($dbc, $_GET['edit']) ?></option>
		            <?php }
		        ?>
	        </select>
	    </div>
	</div>
	<?php } ?>
	<div class="col-sm-4 <?= (FOLDER_NAME == 'medical%20charts' ? 'col-sm-offset-1' : 'col-sm-offset-4') ?>">
		<label class="control-label">Date:</label>
		<input type="text" name="dishwasher_record_choosedate" value="<?= $date ?>" class="form-control inline datepicker" />
		<button tyep="submit" name="load_dishwasher_record_date" value="load_dishwasher_record_date" onclick="$('[name=subtab]').val('Dishwasher Temp Chart');" class="btn brand-btn mobile-block">Submit</button>
		<input type="hidden" name="edit" value="<?= $_GET['edit'] ?>" />
		<input type="hidden" name="category" value="<?= $_GET['category'] ?>" />
		<input type="hidden" name="subtab" value="Dishwasher Temp Chart" />
	</div>
	<div class="<?= (FOLDER_NAME == 'medical%20charts' ? 'col-sm-2' : 'col-sm-4') ?>">
		<button name="export_daily_dishwasher_temp" value="export_daily_dishwasher_temp" onclick="exportDishwasherTempRecord(); return false;" class="btn brand-btn mobile-block pull-right">Export to PDF</button>
	</div>
</div>
<div class="clearfix"></div>

<?php if(!empty($_GET['edit'])) { ?>
	<div class="dishwasher_record_block" id="no-more-tables" style="overflow-y: auto;">
		<h4><?= date('F Y', strtotime($date)) ?></h4>
		<input type="hidden" id="dishwasher_record_today" value="<?= date('Y-m-d') ?>">
		<table class="table table-bordered dishwasher_record_table" data-contact="<?= $_GET['edit'] ?>">
			<tr class="hide-on-mobile">
				<th><div style="min-width: 10em;">Date</div></th>
				<?php if (strpos($value_config, ','."time".',') !== FALSE) { ?>
					<th><div style="min-width: 10em;">Time</div></th>
				<?php } ?>
				<?php if (strpos($value_config, ','."water_temp".',') !== FALSE) { ?>
					<th><div style="min-width: 10em;">Water Temp</div></th>
				<?php } ?>
				<?php if (strpos($value_config, ','."note".',') !== FALSE) { ?>
					<th><div style="min-width: 10em;">Note</div></th>
				<?php } ?>
				<?php if (strpos($value_config, ','."staff".',') !== FALSE) { ?>
					<th>Staff</th>
				<?php } ?>
				<?php if (strpos($value_config, ','."history".',') !== FALSE) { ?>
					<th><div style="min-width: 10em;">History</div></th>
				<?php } ?>
				<th>Function</th>
			</tr>
			<?php
			$date_start = date('Y-m-01', strtotime($date));
			$date_end = date('Y-m-t', strtotime($date));
			$dishwasher_record_query = "SELECT * FROM `daily_dishwasher_temp` WHERE `business`='".$_GET['edit']."' AND `date` BETWEEN '$date_start' AND '$date_end' AND `deleted`=0 ORDER BY `date`, IFNULL(STR_TO_DATE(`time`, '%l:%i %p'),STR_TO_DATE(`time`, '%H:%i')) ASC";
			$dishwasher_record_result = mysqli_fetch_all(mysqli_query($dbc, $dishwasher_record_query),MYSQLI_ASSOC);
			if(empty($dishwasher_record_result)) {
				$dishwasher_record_result[0]['daily_dishwasher_temp_id'] = 0;
				$dishwasher_record_result[0]['date'] = date('Y-m-d');
				$dishwasher_record_result[0]['time'] = '';
				$dishwasher_record_result[0]['water_temp'] = '';
				$dishwasher_record_result[0]['note'] = '';
				$dishwasher_record_result[0]['staff'] = '';
				$dishwasher_record_result[0]['history'] = '';
			}
			$staff_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `deleted`=0 AND `status` > 0 AND `show_hide_user`=1"),MYSQLI_ASSOC));

			foreach ($dishwasher_record_result as $chart) {
				$daily_dishwasher_temp_id = $chart['daily_dishwasher_temp_id'];
				$dishwasher_date = $chart['date'];
				$time = $chart['time'];
				if($charts_time_format == '24h') {
					$time = date('H:i', strtotime(date('Y-m-d').' '.$time));
				} else {
					$time = date('h:i a', strtotime(date('Y-m-d').' '.$time));
				}
				$water_temp = $chart['water_temp'];
				$note = $chart['note'];
				$staff = $chart['staff'];
				$history = $chart['history']; ?>
				<tr class="dishwasher_record_row" data-id="<?= $daily_dishwasher_temp_id ?>">
					<td data-title="Date">
						<input type="text" name="dishwasher_record_date" class="form-control datepicker" value="<?= $dishwasher_date; ?>" onchange="saveFieldDishwasherTempRecord(this);">
					</td>
					<?php if (strpos($value_config, ','."time".',') !== FALSE) { ?>
						<td data-title="Time">
							<input type="text" name="dishwasher_record_time" class="form-control <?= $charts_time_format == '24h' ? 'datetimepicker-24h' : 'datetimepicker' ?>" value="<?= $time; ?>" onchange="saveFieldDishwasherTempRecord(this);">
						</td>
					<?php } ?>
					<?php if (strpos($value_config, ','."water_temp".',') !== FALSE) { ?>
						<td data-title="Water Temp">
							<input type="text" name="dishwasher_record_water_temp" class="form-control" value="<?= $water_temp; ?>" onchange="saveFieldDishwasherTempRecord(this);">
						</td>
					<?php } ?>
					<?php if (strpos($value_config, ','."note".',') !== FALSE) { ?>
						<td data-title="Note">
							<input type="text" name="dishwasher_record_note" class="form-control" value="<?= strip_tags(html_entity_decode($note)); ?>" onchange="saveFieldDishwasherTempRecord(this);">
						</td>
					<?php } ?>
					<?php if (strpos($value_config, ','."staff".',') !== FALSE) { ?>
						<td data-title="Staff">
							<select name="dishwasher_record_staff" class="chosen-select-deselect form-control">
								<option></option>
								<?php foreach($staff_list as $staffid) { ?>
									<option value="<?= $staffid ?>" <?= ($staffid == $staff ? 'selected' : '') ?>><?= get_contact($dbc, $staffid); ?></option>
								<?php } ?>
							</select>
						</td>
					<?php } ?>
					<?php if (strpos($value_config, ','."history".',') !== FALSE) { ?>
						<td data-title="History">
							<input type="text" name="dishwasher_record_history" class="form-control" value="<?= strip_tags(html_entity_decode($history)); ?>" onchange="saveFieldDishwasherTempRecord(this);">
						</td>
					<?php } ?>
					<td data-title="Function">
						<img src="../img/icons/ROOK-add-icon.png" class="inline-img pull-right hide-on-mobile" onclick="addDishwasherTempRecord();">
						<img src="../img/remove.png" class="inline-img pull-right" onclick="deleteDishwasherTempRecord(this);">
					</td>
				</tr>
			<?php } ?>
		</table>
		<div class="pull-right">
			<img src="../img/icons/ROOK-add-icon.png" class="inline-img pull-right" onclick="addDishwasherTempRecord();">
		</div>
	</div>
<?php } else {
	echo '<h1>No Program Selected</h1>';
} ?>