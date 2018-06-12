<?php
$value_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `seizure_record` FROM `field_config`"));
$value_config = ','.$value_config['seizure_record'].',';
$charts_time_format = get_config($dbc, 'charts_time_format');

$date = date('Y-m-d');
if(!empty($_GET['seizure_record_choosedate'])) {
	$date = date('Y-m-d', strtotime($_GET['seizure_record_choosedate']));
} else if(!empty($_POST['seizure_record_choosedate'])) {
	$date = date('Y-m-d', strtotime($_POST['seizure_record_choosedate']));
} ?>
<script type="text/javascript">
$(window).on('load', function() {
	<?php if(isset($_GET['subtab']) && $_GET['subtab'] == 'Seizure Record Chart') { ?>
		$('#nav_seizure_record_chart').trigger('click');
	<?php } ?>
});
$(document).on('change', 'select[name="seizure_record_form"]', function() { saveFieldSeizureRecord(this); });
$(document).on('change', 'select[name="seizure_record_staff"]', function() { saveFieldSeizureRecord(this); });
function saveFieldSeizureRecord(field) {
	var row = $(field).closest('tr.seizure_record_row');
	var contactid = $(field).closest('table.seizure_record_table').data('contact');
	var id = $(row).attr('data-id');
	var date = $(row).find('[name="seizure_record_date"]').val();
	var start_time = $(row).find('[name="seizure_record_starttime"]').val();
	var end_time = $(row).find('[name="seizure_record_endtime"]').val();
	var form = $(row).find('[name="seizure_record_form"]').val();
	var note = $(row).find('[name="seizure_record_note"]').val();
	var staff = $(row).find('[name="seizure_record_staff"]').val();
	var history = $(row).find('[name="seizure_record_history"]').val();
	var data = { contactid: contactid, id: id, date: date, start_time: start_time, end_time: end_time, form: form, note: note, staff: staff, history: history };
	$.ajax({
		url: '../Contacts/contacts_ajax.php?action=seizure_record_chart',
		type: 'POST',
		data: data,
		success: function(response) {
			if (id == 0) {
				$(row).removeAttr('data-id').attr('data-id', response);
			}
		}
	});
}
function addSeizureRecord() {
	destroyInputs('table.seizure_record_table:visible');
	var block = $('table.seizure_record_table:visible').find('tr.seizure_record_row').last();
	var clone = $(block).clone();

	clone.find('.form-control').val('');
    resetChosen(clone.find('select'));
    clone.find('[name="seizure_record_date"]').val($('#seizure_record_today').val());
 //    clone.find('[name="seizure_record_date"]').attr("id", "").removeClass('hasDatepicker').removeData('datepicker').unbind().datepicker({
	// 	changeMonth: true,
	// 	changeYear: true,
	// 	yearRange: '1920:2025',
	// 	dateFormat: 'yy-mm-dd',
	// });
 //    clone.find('[name="seizure_record_starttime"]').attr("id", "").removeClass('hasDatepicker').removeData('datetimepickerseconds').unbind().timepicker({
	// 	controlType: 'select',
	// 	oneLine: true,
	// 	timeFormat: 'hh:mm:ss tt'
 //    });
 //    clone.find('[name="seizure_record_endtime"]').attr("id", "").removeClass('hasDatepicker').removeData('datetimepickerseconds').unbind().timepicker({
	// 	controlType: 'select',
	// 	oneLine: true,
	// 	timeFormat: 'hh:mm:ss tt'
 //    });
    clone.removeAttr('data-id').attr('data-id', 0);

    block.after(clone);
	initInputs('table.seizure_record_table:visible');
}
function deleteSeizureRecord(btn) {
	if($('table.seizure_record_table:visible').find('tr.seizure_record_row').length <= 1) {
		addSeizureRecord();
	}

	var id = $(btn).closest('tr.seizure_record_row').data('id');
	$.ajax({
		url: '../Contacts/contacts_ajax.php?action=seizure_record_chart_delete',
		type: 'POST',
		data: { id: id },
		success: function(repsonse) {
			$(btn).closest('tr.seizure_record_row').remove();
		}
	});
}
function exportSeizureRecord() {
	var contactid = '<?= $_GET['edit']; ?>';
	var date = $('[name="seizure_record_choosedate"]').val();
	var url = '../Medical Charts/seizure_record_pdf.php?contactid='+contactid+'&date='+date;

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
		<input type="text" name="seizure_record_choosedate" value="<?= $date ?>" class="form-control inline datepicker">
		<button tyep="submit" name="load_seizure_record_date" value="load_seizure_record_date" onclick="$('[name=subtab]').val('Seizure Record Chart');" class="btn brand-btn mobile-block">Submit</button>
		<input type="hidden" name="edit" value="<?= $_GET['edit'] ?>">
		<input type="hidden" name="category" value="<?= $_GET['category'] ?>">
		<input type="hidden" name="subtab" value="Seizure Record Chart">
	</div>
	<div class="<?= (FOLDER_NAME == 'medical%20charts' ? 'col-sm-2' : 'col-sm-4') ?>">
		<button name="export_seizure_record" value="export_seizure_record" onclick="exportSeizureRecord(); return false;" class="btn brand-btn mobile-block pull-right">Export to PDF</button>
	</div>
</div>
<div class="clearfix"></div>

<?php if(!empty($_GET['edit'])) { ?>
	<div class="seizure_record_block" id="no-more-tables" style="overflow-y: auto;">
		<h4><?= date('F Y', strtotime($date)) ?></h4>
		<input type="hidden" id="seizure_record_today" value="<?= date('Y-m-d') ?>">
		<table class="table table-bordered seizure_record_table" data-contact="<?= $_GET['edit'] ?>">
			<tr class="hide-on-mobile">
				<th><div style="min-width: 10em;">Date</div></th>
				<?php if (strpos($value_config, ','."start_time".',') !== FALSE) { ?>
					<th><div style="min-width: 10em;">Start Time</div></th>
				<?php } ?>
				<?php if (strpos($value_config, ','."end_time".',') !== FALSE) { ?>
					<th><div style="min-width: 10em;">End Time</div></th>
				<?php } ?>
				<?php if (strpos($value_config, ','."form".',') !== FALSE) { ?>
					<th>Type of Seizure</th>
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
			$seizure_record_query = "SELECT * FROM `seizure_record` WHERE `client` = '".$_GET['edit']."' AND `date` BETWEEN '$date_start' AND '$date_end' AND `deleted` = 0 ORDER BY `date` ASC, IFNULL(IFNULL(STR_TO_DATE(`start_time`, '%l:%i:%s %p'),STR_TO_DATE(`start_time`, '%l:%i %p')),IFNULL(STR_TO_DATE(`start_time`, '%H:%i:%s'),STR_TO_DATE(`start_time`, '%H:%i'))) ASC, IFNULL(IFNULL(STR_TO_DATE(`end_time`, '%l:%i:%s %p'),STR_TO_DATE(`end_time`, '%l:%i %p')),IFNULL(STR_TO_DATE(`end_time`, '%H:%i:%s'),STR_TO_DATE(`end_time`, '%H:%i'))) ASC";
			$seizure_record_result = mysqli_fetch_all(mysqli_query($dbc, $seizure_record_query),MYSQLI_ASSOC);
			if(empty($seizure_record_result)) {
				$seizure_record_result[0]['seizure_record_id'] = 0;
				$seizure_record_result[0]['date'] = date('Y-m-d');
				$seizure_record_result[0]['start_time'] = '';
				$seizure_record_result[0]['end_time'] = '';
				$seizure_record_result[0]['form'] = '';
				$seizure_record_result[0]['note'] = '';
				$seizure_record_result[0]['staff'] = '';
				$seizure_record_result[0]['history'] = '';
			}
			$staff_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND IFNULL(`staff_category`,'') NOT IN (".STAFF_CATS_HIDE.") AND `deleted` = 0 AND `status` > 0 AND `show_hide_user` = 1"),MYSQLI_ASSOC));

			foreach ($seizure_record_result as $chart) {
				$seizure_record_id = $chart['seizure_record_id'];
				$seizure_date = $chart['date'];
				$start_time = $chart['start_time'];
				$end_time = $chart['end_time'];
				if($charts_time_format == '24h') {
					$start_time = date('H:i:s', strtotime(date('Y-m-d').' '.$start_time));
					$end_time = date('H:i:s', strtotime(date('Y-m-d').' '.$end_time));
				} else {
					$start_time = date('h:i:s a', strtotime(date('Y-m-d').' '.$start_time));
					$end_time = date('h:i:s a', strtotime(date('Y-m-d').' '.$end_time));
				}
				$seizure_type = $chart['form'];
				$note = $chart['note'];
				$staff = $chart['staff'];
				$history = $chart['history']; ?>
				<tr class="seizure_record_row" data-id="<?= $seizure_record_id ?>">
					<td data-title="Date">
						<input type="text" name="seizure_record_date" class="form-control datepicker" value="<?= $seizure_date ?>" onchange="saveFieldSeizureRecord(this);">
					</td>
					<?php if (strpos($value_config, ','."start_time".',') !== FALSE) { ?>
						<td data-title="Start Time">
							<input type="text" name="seizure_record_starttime" class="form-control <?= $charts_time_format == '24h' ? 'datetimepickerseconds-24h' : 'datetimepickerseconds' ?>" value="<?= $start_time ?>" onchange="saveFieldSeizureRecord(this);">
						</td>
					<?php } ?>
					<?php if (strpos($value_config, ','."end_time".',') !== FALSE) { ?>
						<td data-title="End Time">
							<input type="text" name="seizure_record_endtime" class="form-control <?= $charts_time_format == '24h' ? 'datetimepickerseconds-24h' : 'datetimepickerseconds' ?>" value="<?= $end_time ?>" onchange="saveFieldSeizureRecord(this);">
						</td>
					<?php } ?>
					<?php if (strpos($value_config, ','."form".',') !== FALSE) { ?>
						<td data-title="Seizure Type">
							<select name="seizure_record_form" class="chosen-select-deselect form-control">
								<option></option>
								<option value="Tonic Clonic" <?= ($seizure_type == 'Tonic Clonic' ? 'selected' : '') ?>>Tonic Clonic</option>
								<option value="Absence" <?= ($seizure_type == 'Absence' ? 'selected' : '') ?>>Absence</option>
								<option value="Simple Partial" <?= ($seizure_type == 'Simple Partial' ? 'selected' : '') ?>>Simple Partial</option>
								<option value="Complex Partial" <?= ($seizure_type == 'Complex Partial' ? 'selected' : '') ?>>Complex Partial</option>
							</select>
						</td>
					<?php } ?>
					<?php if (strpos($value_config, ','."note".',') !== FALSE) { ?>
						<td data-title="Note">
							<input type="text" name="seizure_record_note" class="form-control" value="<?= strip_tags(html_entity_decode($note)) ?>" onchange="saveFieldSeizureRecord(this);">
						</td>
					<?php } ?>
					<?php if (strpos($value_config, ','."staff".',') !== FALSE) { ?>
						<td data-title="Staff">
							<select name="seizure_record_staff" class="chosen-select-deselect form-control">
								<option></option>
								<?php foreach($staff_list as $staffid) { ?>
									<option value="<?= $staffid ?>" <?= ($staffid == $staff ? 'selected' : '') ?>><?= get_contact($dbc, $staffid) ?></option>
								<?php } ?>
							</select>
						</td>
					<?php } ?>
					<?php if (strpos($value_config, ','."history".',') !== FALSE) { ?>
						<td data-title="History">
							<input type="text" name="seizure_record_history" class="form-control" value="<?= strip_tags(html_entity_decode($history)) ?>" onchange="saveFieldSeizureRecord(this);">
						</td>
					<?php } ?>
					<td data-title="Function">
						<img src="../img/icons/ROOK-add-icon.png" class="inline-img pull-right hide-on-mobile" onclick="addSeizureRecord();">
						<img src="../img/remove.png" class="inline-img pull-right" onclick="deleteSeizureRecord(this);">
					</td>
				</tr>
			<?php } ?>
		</table>
		<div class="pull-right">
			<img src="../img/icons/ROOK-add-icon.png" class="inline-img pull-right" onclick="addSeizureRecord();">
		</div>
	</div>
<?php } else {
	echo '<h1>No Client Selected</h1>';
} ?>