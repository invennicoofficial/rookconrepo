<?php
$value_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `daily_water_temp_bus` FROM `field_config`"));
$value_config = ','.$value_config['daily_water_temp_bus'].',';
$charts_time_format = get_config($dbc, 'charts_time_format');

$date = date('Y-m-d');
if(!empty($_GET['wtcb_record_choosedate'])) {
	$date = date('Y-m-d', strtotime($_GET['wtcb_record_choosedate']));
} else if(!empty($_POST['wtcb_record_choosedate'])) {
	$date = date('Y-m-d', strtotime($_POST['wtcb_record_choosedate']));
} ?>
<script type="text/javascript">
$(window).on('load', function() {
	<?php if(isset($_GET['subtab']) && $_GET['subtab'] == 'Water Temp Chart Bus') { ?>
		$('#nav_water_temp_chart_(business)').trigger('click');
	<?php } ?>
});
$(document).on('change', 'select[name="wtcb_record_staff"]', function() { saveFieldWaterTempBusRecord(this); });
$(document).on('change', 'select[name="wtcb_record_location"]', function() { saveFieldWaterTempBusRecord(this); });
function saveFieldWaterTempBusRecord(field) {
	var row = $(field).closest('tr.wtcb_record_row');
	var contactid = $(field).closest('table.wtcb_record_table').data('contact');
	var id = $(row).attr('data-id');
	var date = $(row).find('[name="wtcb_record_date"]').val();
	var time = $(row).find('[name="wtcb_record_time"]').val();
	var location = $(row).find('[name="wtcb_record_location"]').val();
	var water_temp = $(row).find('[name="wtcb_record_water_temp"]').val();
	var note = $(row).find('[name="wtcb_record_note"]').val();
	var ai_done = '';
	if($(row).find('[name="wtcb_record_ai_done"]').is(':checked')) {
		ai_done = $(row).find('[name="wtcb_record_ai_done"]').val();
	}
	var staff = $(row).find('[name="wtcb_record_staff"]').val();
	var history = $(row).find('[name="wtcb_record_history"]').val();
	var data = { contactid: contactid, id: id, date: date, time: time, location: location, water_temp: water_temp, note: note, ai_done: ai_done, staff: staff, history: history };
	$.ajax({
		url: '../Contacts/contacts_ajax.php?action=water_temp_chart_bus',
		type: 'POST',
		data: data,
		success: function(response) {
			if (id == 0) {
				$(row).removeAttr('data-id').attr('data-id', response);
			}
		}
	});
}
function addWaterTempBusRecord() {
	destroyInputs('table.wtcb_record_table:visible');
	var block = $('table.wtcb_record_table:visible').find('tr.wtcb_record_row').last();
	var clone = $(block).clone();

	clone.find('.form-control').val('');
	clone.find('[type="checkbox"]').attr('checked', false);
    resetChosen(clone.find('select'));
    clone.find('[name="wtcb_record_date"]').val($('#wtcb_record_today').val());
 //    clone.find('[name="wtcb_record_date"]').attr("id", "").removeClass('hasDatepicker').removeData('datepicker').unbind().datepicker({
	// 	changeMonth: true,
	// 	changeYear: true,
	// 	yearRange: '1920:2025',
	// 	dateFormat: 'yy-mm-dd',
	// });
 //    clone.find('[name="wtcb_record_time"]').attr("id", "").removeClass('hasDatepicker').removeData('datetimepicker').unbind().timepicker({
	// 	controlType: 'select',
	// 	oneLine: true,
	// 	timeFormat: 'hh:mm tt'
 //    });
    clone.removeAttr('data-id').attr('data-id', 0);

    block.after(clone);
	initInputs('table.wtcb_record_table:visible');
}
function deleteWaterTempBusRecord(btn) {
	if($('table.wtcb_record_table:visible').find('tr.wtcb_record_row').length <= 1) {
		addWaterTempBusRecord();
	}

	var id = $(btn).closest('tr.wtcb_record_row').data('id');
	$.ajax({
		url: '../Contacts/contacts_ajax.php?action=water_temp_chart_bus_delete',
		type: 'POST',
		data: { id: id },
		success: function(repsonse) {
			$(btn).closest('tr.wtcb_record_row').remove();
		}
	});
}
function exportWaterTempBusRecord() {
	var contactid = '<?= $_GET['edit']; ?>';
	var date = $('[name="wtcb_record_choosedate"]').val();
	var url = '../Medical Charts/water_temp_record_bus_pdf.php?contactid='+contactid+'&date='+date;

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
		            	<option selected value="<?= $_GET['edit'] ?>"><?= get_contact($dbc, $_GET['edit']) ?></option>
		            <?php }
		        ?>
	        </select>
	    </div>
	</div>
	<?php } ?>
	<div class="col-sm-4 <?= (FOLDER_NAME == 'medical%20charts' ? 'col-sm-offset-1' : 'col-sm-offset-4') ?>">
		<label class="control-label">Date:</label>
		<input type="text" name="wtcb_record_choosedate" value="<?= $date ?>" class="form-control inline datepicker" />
		<button tyep="submit" name="load_wtcb_record_date" value="load_wtcb_record_date" onclick="$('[name=subtab]').val('Water Temp Chart Bus');" class="btn brand-btn mobile-block">Submit</button>
		<input type="hidden" name="edit" value="<?= $_GET['edit'] ?>" />
		<input type="hidden" name="category" value="<?= $_GET['category'] ?>" />
		<input type="hidden" name="subtab" value="Water Temp Chart Bus" />
	</div>
	<div class="<?= (FOLDER_NAME == 'medical%20charts' ? 'col-sm-2' : 'col-sm-4') ?>">
		<button name="export_water_temp_record" value="export_water_temp_record" onclick="exportWaterTempBusRecord(); return false;" class="btn brand-btn mobile-block pull-right">Export to PDF</button>
	</div>
</div>
<div class="clearfix"></div>

<?php if(!empty($_GET['edit'])) { ?>
	<div class="wtcb_record_block" id="no-more-tables" style="overflow-y: auto;">
		<h4><?= date('F Y', strtotime($date)) ?></h4>
		<input type="hidden" id="wtcb_record_today" value="<?= date('Y-m-d') ?>">
		<table class="table table-bordered wtcb_record_table" data-contact="<?= $_GET['edit'] ?>">
			<tr class="hide-on-mobile">
				<th><div style="min-width: 10em;">Date</div></th>
				<?php if (strpos($value_config, ','."time".',') !== FALSE) { ?>
					<th><div style="min-width: 10em;">Time</div></th>
				<?php } ?>
				<?php if (strpos($value_config, ','."location".',') !== FALSE) { ?>
					<th><div style="min-width: 10em;">Location</div></th>
				<?php } ?>
				<?php if (strpos($value_config, ','."water_temp".',') !== FALSE) { ?>
					<th><div style="min-width: 10em;">Water Temperature</div></th>
				<?php } ?>
				<?php if (strpos($value_config, ','."note".',') !== FALSE) { ?>
					<th><div style="min-width: 10em;">Note</div></th>
				<?php } ?>
				<?php if (strpos($value_config, ','."ai_done".',') !== FALSE) { ?>
					<th>A/I Done</th>
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
			$wtcb_record_query = "SELECT * FROM `daily_water_temp_bus` WHERE `business`='".$_GET['edit']."' AND `date` BETWEEN '$date_start' AND '$date_end' AND `deleted`=0 ORDER BY `date`, IFNULL(STR_TO_DATE(`time`, '%l:%i %p'),STR_TO_DATE(`time`, '%H:%i')) ASC";
			$wtcb_record_result = mysqli_fetch_all(mysqli_query($dbc, $wtcb_record_query),MYSQLI_ASSOC);
			if(empty($wtcb_record_result)) {
				$wtcb_record_result[0]['daily_water_temp_bus_id'] = 0;
				$wtcb_record_result[0]['date'] = date('Y-m-d');
				$wtcb_record_result[0]['time'] = '';
				$wtcb_record_result[0]['location'] = '';
				$wtcb_record_result[0]['water_temp'] = '';
				$wtcb_record_result[0]['note'] = '';
				$wtcb_record_result[0]['ai_done'] = '';
				$wtcb_record_result[0]['staff'] = '';
				$wtcb_record_result[0]['history'] = '';
			}
			$staff_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `deleted`=0 AND `status` > 0 AND `show_hide_user`=1"),MYSQLI_ASSOC));

			foreach ($wtcb_record_result as $chart) {
				$daily_water_temp_bus_id = $chart['daily_water_temp_bus_id'];
				$wtcb_date = $chart['date'];
				$time = $chart['time'];
				if($charts_time_format == '24h') {
					$time = date('H:i', strtotime(date('Y-m-d').' '.$time));
				} else {
					$time = date('h:i a', strtotime(date('Y-m-d').' '.$time));
				}
				$water_temp = $chart['water_temp'];
				$wtcb_location = $chart['location'];
				$note = $chart['note'];
				$ai_done = $chart['ai_done'];
				$staff = $chart['staff'];
				$history = $chart['history']; ?>
				<tr class="wtcb_record_row" data-id="<?= $daily_water_temp_bus_id ?>">
					<td data-title="Date">
						<input type="text" name="wtcb_record_date" class="form-control datepicker" value="<?= $wtcb_date; ?>" onchange="saveFieldWaterTempBusRecord(this);">
					</td>
					<?php if (strpos($value_config, ','."time".',') !== FALSE) { ?>
						<td data-title="Time">
							<input type="text" name="wtcb_record_time" class="form-control <?= $charts_time_format == '24h' ? 'datetimepicker-24h' : 'datetimepicker' ?>" value="<?= $time; ?>" onchange="saveFieldWaterTempBusRecord(this);">
						</td>
					<?php } ?>
					<?php if (strpos($value_config, ','."location".',') !== FALSE) { ?>
						<td data-title="Location">
							<select name="wtcb_record_location" class="form-control chosen-select-deselect">
								<option></option>
								<?php $daily_water_temp_bus_locations = get_config($dbc, 'daily_water_temp_bus_locations');
								    if(empty($daily_water_temp_bus_locations)) {
								        $daily_water_temp_bus_locations = 'Kitchen Double Sink,Kitchen Hand Wash Sink,North Bathroom Sink,North Showerhead,South Bathroom Sink,South Showerhead';
								    }
							    	$daily_water_temp_bus_locations = explode(',', $daily_water_temp_bus_locations);
							    	foreach ($daily_water_temp_bus_locations as $bus_location) {
							    		echo '<option value="'.$bus_location.'" '.($bus_location == $wtcb_location ? 'selected' : '').'>'.$bus_location.'</option>';
							    	} ?>
							</select>
						</td>
					<?php } ?>
					<?php if (strpos($value_config, ','."water_temp".',') !== FALSE) { ?>
						<td data-title="Water Temp">
							<input type="text" name="wtcb_record_water_temp" class="form-control" value="<?= $water_temp; ?>" onchange="saveFieldWaterTempBusRecord(this);">
						</td>
					<?php } ?>
					<?php if (strpos($value_config, ','."note".',') !== FALSE) { ?>
						<td data-title="Note">
							<input type="text" name="wtcb_record_note" class="form-control" value="<?= strip_tags(html_entity_decode($note)); ?>" onchange="saveFieldWaterTempBusRecord(this);">
						</td>
					<?php } ?>
					<?php if (strpos($value_config, ','."ai_done".',') !== FALSE) { ?>
						<td data-title="A/I Done">
							<label class="form-checkbox"><input type="checkbox" name="wtcb_record_ai_done" value="Yes" <?= $ai_done == 'Yes' ? 'checked' : '' ?> onchange="saveFieldWaterTempBusRecord(this);"></label>
						</td>
					<?php } ?>
					<?php if (strpos($value_config, ','."staff".',') !== FALSE) { ?>
						<td data-title="Staff">
							<select name="wtcb_record_staff" class="chosen-select-deselect form-control">
								<option></option>
								<?php foreach($staff_list as $staffid) { ?>
									<option value="<?= $staffid ?>" <?= ($staffid == $staff ? 'selected' : '') ?>><?= get_contact($dbc, $staffid); ?></option>
								<?php } ?>
							</select>
						</td>
					<?php } ?>
					<?php if (strpos($value_config, ','."history".',') !== FALSE) { ?>
						<td data-title="History">
							<input type="text" name="wtcb_record_history" class="form-control" value="<?= strip_tags(html_entity_decode($history)); ?>" onchange="saveFieldWaterTempBusRecord(this);">
						</td>
					<?php } ?>
					<td data-title="Function">
						<img src="../img/icons/ROOK-add-icon.png" class="inline-img pull-right hide-on-mobile" onclick="addWaterTempBusRecord();">
						<img src="../img/remove.png" class="inline-img pull-right" onclick="deleteWaterTempBusRecord(this);">
					</td>
				</tr>
			<?php } ?>
		</table>
		<div class="pull-right">
			<img src="../img/icons/ROOK-add-icon.png" class="inline-img pull-right" onclick="addWaterTempBusRecord();">
		</div>
	</div>
<?php } else {
	echo '<h1>No Program Selected</h1>';
} ?>