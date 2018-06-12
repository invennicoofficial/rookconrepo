<?php include ('field_list.php');
asort($field_list); ?>
<script>
$(document).ready(function() {
	$('input,select').change(update_field);
	$('#no-more-tables').sortable({
		handle: '.heading-handle',
		items: 'table',
		update: save_sort
	});
	$('.sort_table').sortable({
		connectWith: '.sort_table',
		handle: '.line-handle',
		items: 'tr',
		update: save_sort
	});
});
function save_field(src) {
	var table = $(src).data('table');
	var id = $(src).data('id');
	var template = $('[name=id]').val();
	var field = src.name;
	var value = src.value;
	if(value == undefined) {
		value = 1;
	}
	$.ajax({
		url: 'services_ajax.php?action=save_template_field',
		method: 'POST',
		data: {
			table_name: table,
			heading_id: id,
			template_id: template,
			field_name: field,
			value: value
		},
		result: 'html',
		success: function(response) {
			if(response != '') {
				if(table == 'services_templates') {
					$('[name=id]').val(response);
				} else if(table == 'services_templates_headings') {
					$(src).closest('tr').find('[data-id]').data('id',response);
					save_sort();
				}
			}
		}
	});
}
function update_field() {
	src = this;
	if($(src).data('table') != '') {
		save_field(this);
	}
}
function add_line(src) {
	var line = $(src).closest('tr');
	var clone = line.clone();
	clone.find('input,select').val('');
	resetChosen(clone.find("select[class^=chosen]"));
	clone.find('[data-id]').data('id','');
	
	line.closest('table').append(clone);
	$('input,select').off('change', update_field).change(update_field);
}
function remove_line(a) {
	if($(a).closest('table').find('tr').length <= 2) {
		add_line(a);
	}
	save_field(a);
	$(a).closest('tr').remove();
}

function save_sort() {
	var ids = [];
	$('[name="heading_name"]').each(function() {
		ids.push($(this).data('id'));
	});
	$.ajax({
		url: 'services_ajax.php?action=set_sort_order',
		method: 'POST',
		data: {
			table_name: 'services_templates_headings',
			sort_ids: ids
		}
	});
}
</script>
<form class="form-horizontal">
	<div class="col-sm-12">
		<?php $templateid = filter_var($_GET['template'],FILTER_SANITIZE_STRING);
		if($templateid > 0) {
			$template = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `services_templates` WHERE `id`='$templateid'"));
		}
		$headings = mysqli_query($dbc, "SELECT `id`, `heading_name`, `sort_order` FROM `services_templates_headings` WHERE `template_id`='$templateid' AND `deleted`=0 UNION SELECT '', '', 999999999999 ORDER BY `sort_order`"); ?>
		<h3><?= ($templateid > 0 ? $template['template_name'].' Template' : ($templateid == 'list' ? 'Please select an option' : 'Create New Template')) ?></h3>
		<?php if($templateid == 'new' || $templateid > 0) { ?>
			<div class="form-group">
				<label class="col-sm-4 control-label">Template Name</label>
				<div class="col-sm-8">
					<input type="text" name="template_name" value="<?= $template['template_name'] ?>" class="form-control" data-table="services_templates">
					<input type="hidden" name="id" value="<?= $template['id'] ?>" data-table="services_templates">
				</div>
			</div>
			<div id="no-more-tables" class="form-group">
				<div class="sort_table">
					<table class="table table-bordered">
						<tr class="hidden-sm hidden-xs">
							<th style="width: 85%;">Field</th>
							<th style="width: 15%;"></th>
						</tr>
						<?php while($heading = mysqli_fetch_array($headings)) { ?>
							<tr>
								<td data-title="Field">
									<select name="heading_name" class="chosen-select-deselect form-control" data-id="<?= $heading['id'] ?>" data-table="services_templates_headings"><option></option>
										<?php foreach ($field_list as $key => $value) { ?>
											<option <?= ($key == $heading['heading_name'] ? 'selected' : '') ?> value="<?= $key ?>"><?= $value ?></option>
										<?php } ?>
									</select>
								</td>
								<td data-title="Function">
									<img src="../img/remove.png" style="height: 1em;" onclick="remove_line(this);" data-table="services_templates_headings" data-id="<?= $heading['id'] ?>" name="deleted">
									<img src="../img/icons/ROOK-add-icon.png" style="height: 1em;" onclick="add_line(this);">
									<img src="../img/icons/drag_handle.png" style="height: 1em;" class="pull-right line-handle" data-table="services_templates_headings" data-id="<?= $heading['id'] ?>">
								</td>
							</tr>
						<?php } ?>
					</table>
				</div>
			</div>
		<?php } ?>
	</div>
</form>