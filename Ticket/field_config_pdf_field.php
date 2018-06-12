<?php include_once('../include.php');
if($_GET['id'] > 0) {
	$field = $dbc->query("SELECT * FROM `ticket_pdf_fields` WHERE `id`='{$_GET['id']}'")->fetch_assoc(); ?>
	<script>
	function saveField(field) {
		$.post('ticket_ajax_all.php?action=template_field', {
			id: $(field).data('id'),
			field: field.name,
			value: field.value
		});
	}
	</script>
	<div class="form-group">
		<label class="col-sm-4">Unique Name:</label>
		<div class="col-sm-8">
			<input type="text" data-id="<?= $_GET['id'] ?>" name="field_name" value="<?= $field['field_name'] ?>" class="form-control" onchange="saveField(this);">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4">Label:</label>
		<div class="col-sm-8">
			<input type="text" data-id="<?= $_GET['id'] ?>" name="field_label" value="<?= $field['field_label'] ?>" class="form-control" onchange="saveField(this);">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4">Font Size:</label>
		<div class="col-sm-8">
			<input type="number" min="5" max="72" step="1" data-id="<?= $_GET['id'] ?>" name="font_size" value="<?= $field['font_size'] ?>" class="form-control" onchange="saveField(this);">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4">Options:</label>
		<div class="col-sm-8">
			<input type="text" data-id="<?= $_GET['id'] ?>" name="options" value="<?= $field['options'] ?>" class="form-control" onchange="saveField(this);">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4">Input Helper:</label>
		<div class="col-sm-8">
			<select data-id="<?= $_GET['id'] ?>" name="input_class" class="chosen-select-deselect" onchange="saveField(this);"><option />
				<option <?= $field['input_class'] == 'datepicker' ? 'selected' : '' ?> value="datepicker">Calendar</option>
				<option <?= $field['input_class'] == 'datetimepicker' ? 'selected' : '' ?> value="datetimepicker">Time of Date</option>
				<option <?= $field['input_class'] == 'timepicker' ? 'selected' : '' ?> value="timepicker">Hours : Minutes</option>
				<option <?= $field['input_class'] == 'editLink' ? 'selected' : '' ?> value="editLink">Edit Link</option>
				<option <?= $field['input_class'] == 'revisionField' ? 'selected' : '' ?> value="revisionField">Revision #</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4">Default Value:</label>
		<div class="col-sm-8">
			<input type="text" data-id="<?= $_GET['id'] ?>" name="default_value" value="<?= $field['default_value'] ?>" class="form-control" onchange="saveField(this);">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4">Sort Order:</label>
		<div class="col-sm-8">
			<input type="text" data-id="<?= $_GET['id'] ?>" name="sort" value="<?= $field['sort'] ?>" class="form-control" onchange="saveField(this);">
		</div>
	</div>
<?php } else {
	echo 'No Field Selected. Please select a field to configure the details.';
}