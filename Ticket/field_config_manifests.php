<script>
$(document).ready(function() {
	$('.form-group label [type=checkbox],[name=slider_button]').change(saveFields);
});
function saveFields() {
	var tickets_manifests = [];
	$('[name="tickets_manifests[]"]:checked').each(function() {
		tickets_manifests.push(this.value);
	});
	$.ajax({
		url: 'ticket_ajax_all.php?action=ticket_field_config',
		method: 'POST',
		data: {
			field_name: 'ticket_manifest_fields',
			fields: tickets_manifests
		}
	});
}
</script>
<?php $manifest_fields = explode(',',get_config($dbc, 'ticket_manifest_fields')); ?>
<div class="form-group">
	<label class="col-sm-4 control-label">Fields to Display on Manifests:</label>
	<div class="col-sm-8">
		<label class="form-checkbox"><input type="checkbox" <?= in_array('file', $manifest_fields) ? 'checked' : '' ?> value="file" name="tickets_manifests[]"> File #</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('po', $manifest_fields) ? 'checked' : '' ?> value="po" name="tickets_manifests[]"> PO #</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('vendor', $manifest_fields) ? 'checked' : '' ?> value="vendor" name="tickets_manifests[]"> Vendor / Shipper</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('line', $manifest_fields) ? 'checked' : '' ?> value="line" name="tickets_manifests[]"> Line Item #</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('qty', $manifest_fields) ? 'checked' : '' ?> value="qty" name="tickets_manifests[]"> Quantity</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('manual qty', $manifest_fields) ? 'checked' : '' ?> value="manual qty" name="tickets_manifests[]"> Manual Quantity</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('notes', $manifest_fields) ? 'checked' : '' ?> value="notes" name="tickets_manifests[]"> Line Item Notes</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('site', $manifest_fields) ? 'checked' : '' ?> value="site" name="tickets_manifests[]"> <?= SITES_CAT ?></label>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label"><?= TICKET_NOUN ?> to include on Manifests:</label>
	<div class="col-sm-8">
		<?php foreach($ticket_tabs as $ticket_type) {
			$type_id = config_safe_str($ticket_type); ?>
			<label class="form-checkbox"><input type="checkbox" <?= in_array('type '.$type_id, $manifest_fields) || !in_array_starts('type ',$manifest_fields) ? 'checked' : '' ?> value="type <?= $type_id ?>" name="tickets_manifests[]"> <?= $ticket_type ?></label>
		<?php } ?>
	</div>
</div>