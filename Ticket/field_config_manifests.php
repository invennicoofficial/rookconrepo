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
		<label class="form-checkbox"><input type="checkbox" <?= in_array('file', $manifest_fields) ? 'checked' : '' ?> value="file" style="height: 20px; width: 20px;" name="tickets_manifests[]"> File #</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('po', $manifest_fields) ? 'checked' : '' ?> value="po" style="height: 20px; width: 20px;" name="tickets_manifests[]"> PO #</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('vendor', $manifest_fields) ? 'checked' : '' ?> value="vendor" style="height: 20px; width: 20px;" name="tickets_manifests[]"> Vendor / Shipper</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('line', $manifest_fields) ? 'checked' : '' ?> value="line" style="height: 20px; width: 20px;" name="tickets_manifests[]"> Line Item #</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('qty', $manifest_fields) ? 'checked' : '' ?> value="qty" style="height: 20px; width: 20px;" name="tickets_manifests[]"> Quantity</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('manual qty', $manifest_fields) ? 'checked' : '' ?> value="manual qty" style="height: 20px; width: 20px;" name="tickets_manifests[]"> Manual Quantity</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('notes', $manifest_fields) ? 'checked' : '' ?> value="notes" style="height: 20px; width: 20px;" name="tickets_manifests[]"> Line Item Notes</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('site', $manifest_fields) ? 'checked' : '' ?> value="site" style="height: 20px; width: 20px;" name="tickets_manifests[]"> <?= SITES_CAT ?></label>
	</div>
</div>