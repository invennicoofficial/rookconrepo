<?php
$value_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `bowel_movement` FROM `field_config`"));
$value_config = ','.$value_config['bowel_movement'].',';
$charts_time_format = get_config($dbc, 'charts_time_format');

$date = date('Y-m-d');
if(!empty($_GET['bowel_movement_choosedate'])) {
	$date = date('Y-m-d', strtotime($_GET['bowel_movement_choosedate']));
} else if(!empty($_POST['bowel_movement_choosedate'])) {
	$date = date('Y-m-d', strtotime($_POST['bowel_movement_choosedate']));
} ?>
<script type="text/javascript">
$(window).on('load', function() {
	<?php if(isset($_GET['subtab']) && $_GET['subtab'] == 'Bowel Movement Chart') { ?>
		$('#nav_bowel_movement_chart').trigger('click');
	<?php } ?>
});
$(document).on('change', 'select[name="bowel_movement_bm"]', function() { saveFieldsBowelMovement(this); });
$(document).on('change', 'select[name="bowel_movement_size"]', function() { saveFieldsBowelMovement(this); });
$(document).on('change', 'select[name="bowel_movement_form"]', function() { saveFieldsBowelMovement(this); });
$(document).on('change', 'select[name="bowel_movement_staff"]', function() { saveFieldsBowelMovement(this); });
function saveFieldsBowelMovement(field) {
	var row = $(field).closest('tr.bowel_movement_row');
	var contactid = $(field).closest('table.bowel_movement_table').data('contact');
	var id = $(row).attr('data-id');
	var date = $(row).find('[name="bowel_movement_date"]').val();
	var time = $(row).find('[name="bowel_movement_time"]').val();
	var bm = $(row).find('[name="bowel_movement_bm"]').val();
	var size = $(row).find('[name="bowel_movement_size"]').val();
	var form = $(row).find('[name="bowel_movement_form"]').val();
	var note = $(row).find('[name="bowel_movement_note"]').val();
	var staff = $(row).find('[name="bowel_movement_staff"]').val();
	var history = $(row).find('[name="bowel_movement_history"]').val();
	var data = { contactid: contactid, id: id, date: date, time: time, bm: bm, size: size, form: form, note: note, staff: staff, history: history };
	$.ajax({
		url: '../Contacts/contacts_ajax.php?action=bowel_movement_chart',
		type: 'POST',
		data: data,
		success: function(response) {
			if (id == 0) {
				$(row).removeAttr('data-id').attr('data-id', response);
			}
		}
	});
}
function addBowelMovement() {
	destroyInputs('table.bowel_movement_table:visible');
	var block = $('table.bowel_movement_table:visible').find('tr.bowel_movement_row').last();
	var clone = $(block).clone();

	clone.find('.form-control').val('');
    resetChosen(clone.find('select'));
    clone.find('[name="bowel_movement_date"]').val($('#bowel_movement_today').val());
 //    clone.find('[name="bowel_movement_date"]').attr("id", "").removeClass('hasDatepicker').removeData('datepicker').unbind().datepicker({
	// 	changeMonth: true,
	// 	changeYear: true,
	// 	yearRange: '1920:2025',
	// 	dateFormat: 'yy-mm-dd',
	// });
 //    clone.find('[name="bowel_movement_time"]').attr("id", "").removeClass('hasDatepicker').removeData('datetimepicker').unbind().timepicker({
	// 	controlType: 'select',
	// 	oneLine: true,
	// 	timeFormat: 'hh:mm tt'
 //    });
    clone.removeAttr('data-id').attr('data-id', 0);

    block.after(clone);
	initInputs('table.bowel_movement_table:visible');
}
function deleteBowelMovement(btn) {
	if($('table.bowel_movement_table:visible').find('tr.bowel_movement_row').length <= 1) {
		addBowelMovement();
	}

	var id = $(btn).closest('tr.bowel_movement_row').data('id');
	$.ajax({
		url: '../Contacts/contacts_ajax.php?action=bowel_movement_chart_delete',
		type: 'POST',
		data: { id: id },
		success: function(repsonse) {
			$(btn).closest('tr.bowel_movement_row').remove();
		}
	});
}
function exportBowelMovement() {
	var contactid = '<?= $_GET['edit']; ?>';
	var date = $('[name="bowel_movement_choosedate"]').val();
	var url = '../Medical Charts/bowel_movement_pdf.php?contactid='+contactid+'&date='+date;

	window.open(url, '_blank');
}
</script>

<input type="hidden" name="contactid" value="<?= $_GET['edit'] ?>">
<div class="form-group">
</div>
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
		<input type="text" name="bowel_movement_choosedate" value="<?= $date ?>" class="form-control inline datepicker">
		<button tyep="submit" name="load_bowel_movement_date" value="load_bowel_movement_date" onclick="$('[name=subtab]').val('Bowel Movement Chart');" class="btn brand-btn mobile-block">Submit</button>
		<input type="hidden" name="edit" value="<?= $_GET['edit'] ?>">
		<input type="hidden" name="category" value="<?= $_GET['category'] ?>">
		<input type="hidden" name="subtab" value="Bowel Movement Chart">
	</div>
	<div class="<?= (FOLDER_NAME == 'medical%20charts' ? 'col-sm-2' : 'col-sm-4') ?>">
		<button name="export_bowel_movement" value="export_bowel_movement" onclick="exportBowelMovement(); return false;" class="btn brand-btn mobile-block pull-right">Export to PDF</button>
	</div>
</div>
<div class="clearfix"></div>

<?php if(!empty($_GET['edit'])) { ?>
	<div class="bowel_movement_block" id="no-more-tables" style="overflow-y: auto;">
		<h4><?= date('F Y', strtotime($date)) ?></h4>
		<input type="hidden" id="bowel_movement_today" value="<?= date('Y-m-d') ?>">
		<table class="table table-bordered bowel_movement_table" data-contact="<?= $_GET['edit'] ?>">
			<tr class="hide-on-mobile">
				<th><div style="min-width: 10em;">Date</div></th>
				<?php if (strpos($value_config, ','."time".',') !== FALSE) { ?>
					<th><div style="min-width: 10em;">Time</div></th>
				<?php } ?>
				<?php if (strpos($value_config, ','."bm".',') !== FALSE) { ?>
					<th>BM</th>
				<?php } ?>
				<?php if (strpos($value_config, ','."size".',') !== FALSE) { ?>
					<th>Size</th>
				<?php } ?>
				<?php if (strpos($value_config, ','."form".',') !== FALSE) { ?>
					<th>Form</th>
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
			$bowel_movement_query = "SELECT * FROM `bowel_movement` WHERE `client` = '".$_GET['edit']."' AND `date` BETWEEN '$date_start' AND '$date_end' AND `deleted` = 0 ORDER BY `date` ASC, IFNULL(STR_TO_DATE(`time`, '%l:%i %p'),STR_TO_DATE(`time`, '%H:%i')) ASC";
			$bowel_movement_result = mysqli_fetch_all(mysqli_query($dbc, $bowel_movement_query),MYSQLI_ASSOC);
			if(empty($bowel_movement_result)) {
				$bowel_movement_result[0]['bowel_movement_id'] = 0;
				$bowel_movement_result[0]['date'] = date('Y-m-d');
				$bowel_movement_result[0]['time'] = '';
				$bowel_movement_result[0]['bm'] = '';
				$bowel_movement_result[0]['size'] = '';
				$bowel_movement_result[0]['form'] = '';
				$bowel_movement_result[0]['note'] = '';
				$bowel_movement_result[0]['staff'] = '';
				$bowel_movement_result[0]['history'] = '';
			}
			$staff_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND IFNULL(`staff_category`,'') NOT IN (".STAFF_CATS_HIDE.") AND `deleted` = 0 AND `status` > 0 AND `show_hide_user` = 1"),MYSQLI_ASSOC));

			foreach ($bowel_movement_result as $chart) {
				$bowel_movement_id = $chart['bowel_movement_id'];
				$bowel_date = $chart['date'];
				$bowel_time = $chart['time'];
				if($charts_time_format == '24h') {
					$bowel_time = date('H:i', strtotime(date('Y-m-d').' '.$bowel_time));
				} else {
					$bowel_time = date('h:i a', strtotime(date('Y-m-d').' '.$bowel_time));
				}
				$bm = $chart['bm'];
				$size = $chart['size'];
				$form = $chart['form'];
				$note = $chart['note'];
				$staff = $chart['staff'];
				$history = $chart['history']; ?>
				<tr class="bowel_movement_row" data-id="<?= $bowel_movement_id ?>">
					<td data-title="Date">
						<input type="text" name="bowel_movement_date" class="form-control datepicker" value="<?= $bowel_date ?>" onchange="saveFieldsBowelMovement(this);">
					</td>
					<?php if (strpos($value_config, ','."time".',') !== FALSE) { ?>
						<td data-title="Time">
							<input type="text" name="bowel_movement_time" class="form-control <?= $charts_time_format == '24h' ? 'datetimepicker-24h' : 'datetimepicker' ?>" value="<?= $bowel_time ?>" onchange="saveFieldsBowelMovement(this);">
						</td>
					<?php } ?>
					<?php if (strpos($value_config, ','."bm".',') !== FALSE) { ?>
						<td data-title="BM">
							<select name="bowel_movement_bm" class="chosen-select-deselect form-control">
								<option></option>
								<option value="Unsupported" <?= ($bm == 'Unsupported' ? 'selected' : '') ?>>Unsupported</option>
								<option value="Supported" <?= ($bm == 'Supported' ? 'selected' : '') ?>>Supported</option>
								<option value="PRN" <?= ($bm == 'PRN' ? 'selected' : '') ?>>PRN</option>
							</select>
						</td>
					<?php } ?>
					<?php if (strpos($value_config, ','."size".',') !== FALSE) { ?>
						<td data-title="Size">
							<select name="bowel_movement_size" class="chosen-select-deselect form-control">
								<option></option>
								<option value="Large" <?= ($size == 'Large' ? 'selected' : '') ?>>Large</option>
								<option value="Medium" <?= ($size == 'Medium' ? 'selected' : '') ?>>Medium</option>
								<option value="Small" <?= ($size == 'Small' ? 'selected' : '') ?>>Small</option>
							</select>
						</td>
					<?php } ?>
					<?php if (strpos($value_config, ','."form".',') !== FALSE) { ?>
						<td data-title="Form">
							<select name="bowel_movement_form" class="chosen-select-deselect form-control">
								<option></option>
								<option value="Formed" <?= ($form == 'Formed' ? 'selected' : '') ?>>Formed</option>
								<option value="Soft" <?= ($form == 'Soft' ? 'selected' : '') ?>>Soft</option>
								<option value="Loose" <?= ($form == 'Loose' ? 'selected' : '') ?>>Loose</option>
								<option value="Diarrhea" <?= ($form == 'Diarrhea' ? 'selected' : '') ?>>Diarrhea</option>
							</select>
						</td>
					<?php } ?>
					<?php if (strpos($value_config, ','."note".',') !== FALSE) { ?>
						<td data-title="Note">
							<input type="text" name="bowel_movement_note" class="form-control" value="<?= strip_tags(html_entity_decode($note)) ?>" onchange="saveFieldsBowelMovement(this);">
						</td>
					<?php } ?>
					<?php if (strpos($value_config, ','."staff".',') !== FALSE) { ?>
						<td data-title="Staff">
							<select name="bowel_movement_staff" class="chosen-select-deselect form-control">
								<option></option>
								<?php foreach($staff_list as $staffid) { ?>
									<option value="<?= $staffid ?>" <?= ($staffid == $staff ? 'selected' : '') ?>><?= get_contact($dbc, $staffid) ?></option>
								<?php } ?>
							</select>
						</td>
					<?php } ?>
					<?php if (strpos($value_config, ','."history".',') !== FALSE) { ?>
						<td data-title="History">
							<input type="text" name="bowel_movement_history" class="form-control" value="<?= strip_tags(html_entity_decode($history)) ?>" onchange="saveFieldsBowelMovement(this);">
						</td>
					<?php } ?>
					<td data-title="Function">
						<img src="../img/icons/ROOK-add-icon.png" class="inline-img pull-right hide-on-mobile" onclick="addBowelMovement();">
						<img src="../img/remove.png" class="inline-img pull-right" onclick="deleteBowelMovement(this);">
					</td>
				</tr>
			<?php } ?>
		</table>
		<div class="pull-right">
			<img src="../img/icons/ROOK-add-icon.png" class="inline-img pull-right" onclick="addBowelMovement();">
		</div>
	</div>
<?php } else {
	echo '<h1>No Client Selected</h1>';
} ?>