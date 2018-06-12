<?php
$value_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `blood_glucose` FROM `field_config`"));
$value_config = ','.$value_config['blood_glucose'].',';
$charts_time_format = get_config($dbc, 'charts_time_format');

$date = date('Y-m-d');
if(!empty($_GET['bg_record_choosedate'])) {
	$date = date('Y-m-d', strtotime($_GET['bg_record_choosedate']));
} else if(!empty($_POST['bg_record_choosedate'])) {
	$date = date('Y-m-d', strtotime($_POST['bg_record_choosedate']));
} ?>
<script type="text/javascript">
$(window).on('load', function() {
	<?php if(isset($_GET['subtab']) && $_GET['subtab'] == 'Blood Glucose Chart') { ?>
		$('#nav_blood_glucose_chart').trigger('click');
	<?php } ?>
});
$(document).on('change', 'select[name="bg_record_staff"]', function() { saveFieldBloodGlucoseChart(this); });
function saveFieldBloodGlucoseChart(field) {
	var row = $(field).closest('tr.bg_record_row');
	var contactid = $(field).closest('table.bg_record_table').data('contact');
	var id = $(row).attr('data-id');
	var date = $(row).find('[name="bg_record_date"]').val();
	var time = $(row).find('[name="bg_record_time"]').val();
	var bg = $(row).find('[name="bg_record_bg"]').val();
	var note = $(row).find('[name="bg_record_note"]').val();
	var staff = $(row).find('[name="bg_record_staff"]').val();
	var history = $(row).find('[name="bg_record_history"]').val();
	var data = { contactid: contactid, id: id, date: date, time: time, bg: bg, note: note, staff: staff, history: history };
	$.ajax({
		url: '../Contacts/contacts_ajax.php?action=blood_glucose_chart',
		type: 'POST',
		data: data,
		success: function(response) {
			if (id == 0) {
				$(row).removeAttr('data-id').attr('data-id', response);
			}
		}
	});
}
function addBloodGlucoseRecord() {
	destroyInputs('table.bg_record_table:visible');
	var block = $('table.bg_record_table:visible').find('tr.bg_record_row').last();
	var clone = $(block).clone();

	clone.find('.form-control').val('');
    resetChosen(clone.find('select'));
    clone.find('[name="bg_record_date"]').val($('#bg_record_today').val());
  //   clone.find('[name="bg_record_date"]').attr("id", "").removeClass('hasDatepicker').removeData('datepicker').unbind().datepicker({
		// changeMonth: true,
		// changeYear: true,
		// yearRange: '1920:2025',
		// dateFormat: 'yy-mm-dd',
  //   });
  //   clone.find('[name="bg_record_time"]').attr("id", "").removeClass('hasDatepicker').removeData('datetimepicker').unbind().timepicker({
		// controlType: 'select',
		// oneLine: true,
		// timeFormat: 'hh:mm tt'
  //   });
    clone.removeAttr('data-id').attr('data-id', 0);

    block.after(clone);
	initInputs('table.bg_record_table:visible');
}
function deleteBloodGlucoseRecord(btn) {
	if($('table.bg_record_table:visible').find('tr.bg_record_row').length <= 1) {
		addBloodGlucoseRecord();
	}

	var id = $(btn).closest('tr.bg_record_row').data('id');
	$.ajax({
		url: '../Contacts/contacts_ajax.php?action=blood_glucose_chart_delete',
		type: 'POST',
		data: { id: id },
		success: function(repsonse) {
			$(btn).closest('tr.bg_record_row').remove();
		}
	});
}
function exportBloodGlucoseChart() {
	var contactid = '<?= $_GET['edit']; ?>';
	var date = $('[name="bg_record_choosedate"]').val();
	var url = '../Medical Charts/blood_glucose_chart_pdf.php?contactid='+contactid+'&date='+date;

	window.open(url, '_blank');
}
</script>

<input type="hidden" name="contactid" value="<?= $_GET['edit'] ?>">
<div class="form-group">
	<?php if(FOLDER_NAME == 'medical%20charts') { ?>
	<div class="col-sm-5">
	    <label class="col-sm-6 control-label"><span class="pull-right">Client:</span></label>
	    <div class="col-sm-6">
	        <select name="search_clientid" class="form-control chosen-select-deselect">
	        	<option></option>
		        <?php
		            $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `category` = 'Clients' AND `deleted` = 0 AND `status` > 0 AND `show_hide_user` = 1"),MYSQLI_ASSOC));
		            foreach ($query as $id) { ?>
		                <option <?= ($id == $_GET['edit'] ? 'selected' : '') ?> value="<?= $id ?>"><?= get_contact($dbc, $id) ?></option>
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
		<input type="text" name="bg_record_choosedate" value="<?= $date ?>" class="form-control inline datepicker" />
		<button tyep="submit" name="load_bg_record_date" value="load_bg_record_date" onclick="$('[name=subtab]').val('Blood Glucose Chart');" class="btn brand-btn mobile-block">Submit</button>
		<input type="hidden" name="edit" value="<?= $_GET['edit'] ?>" />
		<input type="hidden" name="category" value="<?= $_GET['category'] ?>" />
		<input type="hidden" name="subtab" value="Blood Glucose Chart" />
	</div>
	<div class="<?= (FOLDER_NAME == 'medical%20charts' ? 'col-sm-2' : 'col-sm-4') ?>">
		<button name="export_blood_glucose_chart" value="export_blood_glucose_chart" onclick="exportBloodGlucoseChart(); return false;" class="btn brand-btn mobile-block pull-right">Export to PDF</button>
	</div>
</div>
<div class="clearfix"></div>

<?php if(!empty($_GET['edit'])) { ?>
	<div class="bg_record_block" id="no-more-tables" style="overflow-y: auto;">
		<h4><?= date('F Y', strtotime($date)) ?></h4>
		<input type="hidden" id="bg_record_today" value="<?= date('Y-m-d') ?>">
		<table class="table table-bordered bg_record_table" data-contact="<?= $_GET['edit'] ?>">
			<tr class="hide-on-mobile">
				<th><div style="min-width: 10em;">Date</div></th>
				<?php if (strpos($value_config, ','."time".',') !== FALSE) { ?>
					<th><div style="min-width: 10em;">Time</div></th>
				<?php } ?>
				<?php if (strpos($value_config, ','."bg".',') !== FALSE) { ?>
					<th><div style="min-width: 10em;">Blood Glucose</div></th>
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
			$bg_record_query = "SELECT * FROM `blood_glucose` WHERE `client`='".$_GET['edit']."' AND `date` BETWEEN '$date_start' AND '$date_end' AND `deleted`=0 ORDER BY `date`, IFNULL(STR_TO_DATE(`time`, '%l:%i %p'),STR_TO_DATE(`time`, '%H:%i')) ASC";
			$bg_record_result = mysqli_fetch_all(mysqli_query($dbc, $bg_record_query),MYSQLI_ASSOC);
			if(empty($bg_record_result)) {
				$bg_record_result[0]['blood_glucose_id'] = 0;
				$bg_record_result[0]['date'] = date('Y-m-d');
				$bg_record_result[0]['time'] = '';
				$bg_record_result[0]['bg'] = '';
				$bg_record_result[0]['note'] = '';
				$bg_record_result[0]['staff'] = '';
				$bg_record_result[0]['history'] = '';
			}
			$staff_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND IFNULL(`staff_category`,'') NOT IN (".STAFF_CATS_HIDE.") AND `deleted`=0 AND `status` > 0 AND `show_hide_user`=1"),MYSQLI_ASSOC));

			foreach ($bg_record_result as $chart) {
				$blood_glucose_id = $chart['blood_glucose_id'];
				$bg_date = $chart['date'];
				$time = $chart['time'];
				if($charts_time_format == '24h') {
					$time = date('H:i', strtotime(date('Y-m-d').' '.$time));
				} else {
					$time = date('h:i a', strtotime(date('Y-m-d').' '.$time));
				}
				$bg = $chart['bg'];
				$note = $chart['note'];
				$staff = $chart['staff'];
				$history = $chart['history']; ?>
				<tr class="bg_record_row" data-id="<?= $blood_glucose_id ?>">
					<td data-title="Date">
						<input type="text" name="bg_record_date" class="form-control datepicker" value="<?= $bg_date; ?>" onchange="saveFieldBloodGlucoseChart(this);">
					</td>
					<?php if (strpos($value_config, ','."time".',') !== FALSE) { ?>
						<td data-title="Start Time">
							<input type="text" name="bg_record_time" class="form-control <?= $charts_time_format == '24h' ? 'datetimepicker-24h' : 'datetimepicker' ?>" value="<?= $time; ?>" onchange="saveFieldBloodGlucoseChart(this);">
						</td>
					<?php } ?>
					<?php if (strpos($value_config, ','."bg".',') !== FALSE) { ?>
						<td data-title="End Time">
							<input type="text" name="bg_record_bg" class="form-control" value="<?= $bg; ?>" onchange="saveFieldBloodGlucoseChart(this);">
						</td>
					<?php } ?>
					<?php if (strpos($value_config, ','."note".',') !== FALSE) { ?>
						<td data-title="Note">
							<input type="text" name="bg_record_note" class="form-control" value="<?= strip_tags(html_entity_decode($note)); ?>" onchange="saveFieldBloodGlucoseChart(this);">
						</td>
					<?php } ?>
					<?php if (strpos($value_config, ','."staff".',') !== FALSE) { ?>
						<td data-title="Staff">
							<select name="bg_record_staff" class="chosen-select-deselect form-control">
								<option></option>
								<?php foreach($staff_list as $staffid) { ?>
									<option value="<?= $staffid ?>" <?= ($staffid == $staff ? 'selected' : '') ?>><?= get_contact($dbc, $staffid); ?></option>
								<?php } ?>
							</select>
						</td>
					<?php } ?>
					<?php if (strpos($value_config, ','."history".',') !== FALSE) { ?>
						<td data-title="History">
							<input type="text" name="bg_record_history" class="form-control" value="<?= strip_tags(html_entity_decode($history)); ?>" onchange="saveFieldBloodGlucoseChart(this);">
						</td>
					<?php } ?>
					<td data-title="Function">
						<img src="../img/icons/ROOK-add-icon.png" class="inline-img pull-right hide-on-mobile" onclick="addBloodGlucoseRecord();">
						<img src="../img/remove.png" class="inline-img pull-right" onclick="deleteBloodGlucoseRecord(this);">
					</td>
				</tr>
			<?php } ?>
		</table>
		<div class="pull-right">
			<img src="../img/icons/ROOK-add-icon.png" class="inline-img pull-right" onclick="addBloodGlucoseRecord();">
		</div>
	</div>
<?php } else {
	echo '<h1>No Client Selected</h1>';
} ?>