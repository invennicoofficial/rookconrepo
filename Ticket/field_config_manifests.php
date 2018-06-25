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
		<label class="form-checkbox"><input type="checkbox" <?= in_array('sort_top', $manifest_fields) ? 'checked' : '' ?> value="sort_top" style="height: 20px; width: 20px;" name="tickets_manifests[]"> Show Top 25 Inventory</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('sort_project', $manifest_fields) ? 'checked' : '' ?> value="sort_project" style="height: 20px; width: 20px;" name="tickets_manifests[]"> Sort by <?= PROJECT_NOUN ?> Types</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('file', $manifest_fields) ? 'checked' : '' ?> value="file" style="height: 20px; width: 20px;" name="tickets_manifests[]"> File #</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('po', $manifest_fields) ? 'checked' : '' ?> value="po" style="height: 20px; width: 20px;" name="tickets_manifests[]"> PO #</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('vendor', $manifest_fields) ? 'checked' : '' ?> value="vendor" style="height: 20px; width: 20px;" name="tickets_manifests[]"> Vendor / Shipper</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('line', $manifest_fields) ? 'checked' : '' ?> value="line" style="height: 20px; width: 20px;" name="tickets_manifests[]"> Line Item #</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('qty', $manifest_fields) ? 'checked' : '' ?> value="qty" style="height: 20px; width: 20px;" name="tickets_manifests[]"> Quantity</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('manual qty', $manifest_fields) ? 'checked' : '' ?> value="manual qty" style="height: 20px; width: 20px;" name="tickets_manifests[]"> Manual Quantity</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('max qty', $manifest_fields) ? 'checked' : '' ?> value="max qty" style="height: 20px; width: 20px;" name="tickets_manifests[]"> Default to Max Qty</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('hide qty', $manifest_fields) ? 'checked' : '' ?> value="hide qty" style="height: 20px; width: 20px;" name="tickets_manifests[]"> Hide 0 Qty</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('notes', $manifest_fields) ? 'checked' : '' ?> value="notes" style="height: 20px; width: 20px;" name="tickets_manifests[]"> Line Item Notes</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('site', $manifest_fields) ? 'checked' : '' ?> value="site" style="height: 20px; width: 20px;" name="tickets_manifests[]"> <?= SITES_CAT ?> on Manifest</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('req site', $manifest_fields) ? 'checked' : '' ?> value="req site" style="height: 20px; width: 20px;" name="tickets_manifests[]"> <?= SITES_CAT ?> are Mandatory</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('edit', $manifest_fields) ? 'checked' : '' ?> value="edit" style="height: 20px; width: 20px;" name="tickets_manifests[]"> Edit Manifests</label>
		<label class="form-checkbox"><input type="checkbox" <?= in_array('ticket_sort', $manifest_fields) ? 'checked' : '' ?> value="ticket_sort" style="height: 20px; width: 20px;" name="tickets_manifests[]"> Sort by <?= TICKET_NOUN ?></label>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label"><?= TICKET_NOUN ?> to Include on Manifests:</label>
	<div class="col-sm-8">
		<?php foreach($ticket_tabs as $ticket_type) {
			$type_id = config_safe_str($ticket_type); ?>
			<label class="form-checkbox"><input type="checkbox" <?= in_array('type '.$type_id, $manifest_fields) || !in_array_starts('type ',$manifest_fields) ? 'checked' : '' ?> value="type <?= $type_id ?>" name="tickets_manifests[]"> <?= $ticket_type ?></label>
		<?php } ?>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label"><?= PROJECT_NOUN ?> Types to Display For Manifests:</label>
	<div class="col-sm-8">
		<?php foreach(explode(',',get_config($dbc, 'project_tabs')) as $project_type) {
			$type_id = config_safe_str($project_type); ?>
			<label class="form-checkbox"><input type="checkbox" <?= in_array('project_type '.$type_id, $manifest_fields) || !in_array_starts('project_type ',$manifest_fields) ? 'checked' : '' ?> value="project_type <?= $type_id ?>" name="tickets_manifests[]"> <?= $project_type ?></label>
		<?php } ?>
	</div>
</div>