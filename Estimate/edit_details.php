<?php include_once('../include.php');
checkAuthorised('estimate');
if(!isset($estimate)) {
	$estimateid = filter_var($estimateid,FILTER_SANITIZE_STRING);
	$estimate = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `estimate` WHERE `estimateid`='$estimateid'"));
}
$details = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `estimate_detail` WHERE `estimateid`='$estimateid'"));
include('arr_detail_types.php'); ?>
<script>
$(document).on('change', 'select.add_detail', function() { addDetail(this); });
function addDetail(select) {
	$(select).closest('.form-group').before('<div class="form-group">' +
			'<label class="col-sm-4">'+$(select).find('option:selected').text()+':<img class="inline-img" src="../img/remove.png" onclick="$(this).closest(\'.form-group\').find(\'textarea\').val(\'\').change(); $(this).closest(\'.form-group\').remove();"></label>' +
			'<div class="col-sm-8">' +
				'<textarea name="'+select.value+'" data-table="estimate_detail" data-id-field="detailid" data-id="<?= $details['detailid'] ?>" data-estimate="<?= $estimateid ?>"></textarea>' +
			'</div>' +
		'</div>');
	$(select).val('').trigger('change.select2');
	var area = $('textarea:not([id])');
	destroyInputs($('[data-tab-name="details"]'));
	initInputs('[data-tab-name="details"]');
	$('textarea').off('change', saveField).change(saveField).off('keyup').keyup(syncUnsaved);
	setTimeout(function() { tinymce.editors[area.attr('id')].focus(); }, 10);
}
</script>
<div class="form-horizontal col-sm-12" data-tab-name="details">
	<h3><?= ESTIMATE_TILE ?> Notes</h3>
	<?php foreach($detail_types as $config_str => $field) {
		if(in_array($config_str, $config) || $details[$field[1]] != '') { ?>
			<div class="form-group">
				<label class="col-sm-4"><?= $field[0] ?>: <span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="This detail is for internal use, and will not appear on the Estimate PDF."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><img class="inline-img" src="../img/remove.png" onclick="$(this).closest('.form-group').find('textarea').val('').change(); $(this).closest('.form-group').remove();"></label>
				<div class="col-sm-8">
					<textarea name="<?= $field[1] ?>" data-table="estimate_detail" data-id-field="detailid" data-id="<?= $details['detailid'] ?>" data-estimate="<?= $estimateid ?>"><?= $details[$field[1]] ?></textarea>
				</div>
			</div>
		<?php }
	} ?>
	<div class="form-group">
		<label class="col-sm-4">Add New Detail: <span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="This detail is for internal use, and will not appear on the Estimate PDF."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span></label>
		<div class="col-sm-8">
			<select class="chosen-select-deselect add_detail">
				<option></option>
				<?php foreach($detail_types as $field) { ?>
					<option value="<?= $field[1] ?>"><?= $field[0] ?></option>
				<?php } ?>
			</select>
		</div>
	</div>
    <hr />
</div>
<?php if(in_array('Notes', $config)) {
	$customer_notes = mysqli_query($dbc, "SELECT * FROM `estimate_notes` WHERE `estimateid`='$estimateid' AND `estimateid` > 0 AND `heading` NOT IN ('Follow Up Completed','Note')");
	$note = mysqli_fetch_assoc($customer_notes);
	do { ?>
		<div class="multi-block">
			<div class="form-group">
				<label class="col-sm-4">Note Type:</label>
				<div class="col-sm-8">
					<div class="col-sm-12" style="<?= $note['heading'] == '' ? '' : 'display:none;' ?>">
						<select class="chosen-select-deselect" name="heading" data-table="estimate_notes" data-id="<?= $note['id'] ?>" data-id-field="id"><option></option>
							<option value="Note">Internal Note</option>
							<option value="CUSTOM">Customer Note</option>
						</select>
					</div>
					<div class="col-sm-11" style="<?= $note['heading'] == '' ? 'display:none;' : '' ?>">
						<input type="text" name="heading" class="form-control" data-table="estimate_notes" data-id="<?= $note['id'] ?>" data-id-field="id" value="<?= $note['heading'] ?>">
					</div>
					<div class="col-sm-1" style="<?= $note['heading'] == '' ? 'display:none;' : '' ?>">
						<img onclick="$(this).closest('.col-sm-8').find('div[class^=col-sm]').hide(); $(this).closest('.form-group').find('.col-sm-12').show().find('select[name=heading]').val('Note').trigger('change.select2').change();" src="../img/remove.png" class="inline-img">
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4">Note: <span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="This note can optionally be included on the PDF."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span></label>
				<div class="col-sm-8">
					<textarea name="notes" class="form-control" data-table="estimate_notes" data-id="<?= $note['id'] ?>" data-id-field="id"><?= html_entity_decode($note['notes']) ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4">Include on <?= ESTIMATE_TILE ?> PDF:</label>
				<div class="col-sm-8">
					<label class="form-checkbox"><input type="checkbox" name="include_pdf" value="1" <?= $note['include_pdf'] > 0 ? 'checked' : '' ?> data-table="estimate_notes" data-id="<?= $note['id'] ?>" data-id-field="id"> Yes</label>
				</div>
			</div>
		</div>
	<?php } while($note = mysqli_fetch_assoc($customer_notes)); ?>
	<button onclick="add_note(); return false;" class="btn brand-btn pull-right">Add Note</button>
    <div class="clearfix"></div>
    <hr />
	<script>
	function add_note() {
		destroyInputs($('.multi-block'));
		var block = $('[data-table=estimate_notes][name=notes]').last().closest('.multi-block');
		var clone = block.clone();
		clone.find('input,select,textarea').val('');
		clone.find('.col-sm-12').show();
		clone.find('.col-sm-1,.col-sm-11').hide();
		clone.find('[data-id]').data('id','');
		clone.find('textarea').removeAttr('id');
		clone.find('input,select,textarea').change(saveField);
		block.after(clone);
		initInputs('.multi-block');
	}
	</script>
<?php } ?>
<?php if(basename($_SERVER['SCRIPT_FILENAME']) == 'edit_details.php') { ?>
	<div style="display:none;"><?php include('../footer.php'); ?></div>
<?php } ?>