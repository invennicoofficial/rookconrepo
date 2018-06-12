<?php $text_editors = [ [ 'software_id', 'email_body', 'swid_email_body', 'Software ID: Software Access Email Body' ] ];
$editor_id = filter_var($_GET['editor'] ?: $text_editors[0][2], FILTER_SANITIZE_STRING); ?>
<script>
$(document).ready(function() {
	setTriggers();
	$('.template_list').sortable({
		handle: ".drag_handle",
		items: ".form-group",
		update: function(event, ui) {
			var id_list = [];
			$('.template_list [name=id]').each(function() { id_list.push(this.value); });
			$.ajax({
				url: '../ajax_all.php?action=text_template_sort',
				method: 'POST',
				data: {
					template_id: id_list
				}
			});
		}
	});
});
function setTriggers() {
	$('[name=text_editor]').off('change',select_field).change(select_field);
	$('.delete_icon').off('click',delete_template).click(delete_template);
	$('.add_icon').off('click',add_template).click(add_template);
	$('.template_list input,.template_list textarea').change(update_field);
}
function select_field() {
	if(this.value != '' && this.value != '<?= $editor_id ?>') {
		window.location.replace('?settings=text_templates&editor='+this.value);
	}
}
function update_field() {
	var block = $(this).closest('.form-group');
	var id = block.find('[name=id]').val();
	$.ajax({
		url: '../ajax_all.php?action=text_template_field',
		method: 'POST',
		data: {
			id: id,
			field: this.name,
			value: this.value,
			tile: 'staff',
			tab: $('[name=template_tab]').val(),
			template_field: $('[name=template_field]').val()
		},
		success: function(response) {
			if(response > 0) {
				block.find('[name=id]').val(response);
			}
		}
	});
}
function delete_template() {
	$(this).closest('.form-group').find('[name=deleted]').val(1).change();
	$(this).closest('.form-group').hide();
	if($('.template_list .form-group:visible').length == 0) {
		add_template();
	}
	return false;
}
function add_template() {
	destroyInputs($('.template_list'));
	var new_template = $('.template_list .form-group').last().clone();
	new_template.find('input,textarea').val('');
	new_template.show();
	$('.template_list').append(new_template);
	initInputs('.template_list');
	setTriggers();
	return false;
}
</script>
<div class="form-group">
	<label class="col-sm-4 control-label">Text Editor:</label>
	<div class="col-sm-8">
		<select name="text_editor" data-placeholder="Select a Field" class="chosen-select-deselect">
			<?php foreach($text_editors as $editor) { ?>
				<option <?= $editor_id == $editor[2] ? 'selected' : '' ?> data-tile="staff" data-tab="<?= $editor[0] ?>" data-field="<?= $editor[1] ?>" value="<?= $editor[2] ?>"><?= $editor[3] ?></option>
				<?php if($editor_id == $editor[2]) {
					$tile = 'staff';
					$tab = $editor[0];
					$field = $editor[1];
				}
			} ?>
		</select>
	</div>
</div>
<input type="hidden" name="template_tab" value="<?= $tab ?>">
<input type="hidden" name="template_field" value="<?= $field ?>">
<div class="template_list">
	<?php $templates = mysqli_query($dbc, "SELECT `id`, `description`, `template` FROM `text_templates` WHERE `tile`='$tile' AND `tab`='$tab' AND `field`='$field' AND `deleted`=0 ORDER BY `sort`");
	$template = mysqli_fetch_assoc($templates);
	do { ?>
		<div class="form-group">
			<input type="hidden" name="id" value="<?= $template['id'] ?>">
			<input type="hidden" name="deleted" value="0">
			<label class="col-sm-4 control-label">Template Name:</label>
			<div class="col-sm-6"><input type="text" name="description" class="form-control" value="<?= $template['description'] ?>"></div>
			<div class="col-sm-2">
				<img class="inline-img pull-right drag_handle" src="../img/icons/drag_handle.png">
				<a href=""><img class="inline-img pull-right add_icon" src="../img/icons/ROOK-add-icon.png">
				<img class="inline-img pull-right delete_icon" src="../img/remove.png"></a>
			</div>
			<label class="col-sm-4 control-label">Template Text:<br /><em>Refer to the original Text Editor for fields that can be used such as [STAFF_NAME].</em></label>
			<div class="col-sm-8"><textarea name="template"><?= html_entity_decode($template['template']) ?></textarea></div>
		</div>
	<?php } while($template = mysqli_fetch_assoc($templates)); ?>
</div>