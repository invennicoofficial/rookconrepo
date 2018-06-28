<?php $file_list = array_filter(scandir('macros'), function($filename) { return strpos($filename,'.php') !== FALSE; });
if(count($file_list) == 0) {
	echo '<h3>No Macros Found.</h3>';
} else {
	$ticket_tabs = explode(',',get_config($dbc,'ticket_tabs')); ?>
	<script>
	$(document).ready(function() {
		setSave();
	});
	function setSave() {
		$('.form-group input,.form-group select').off('change',saveMacros).change(saveMacros);
	}
	function saveMacros() {
		var list = [];
		$('.form-group').each(function() {
			list.push($(this).find('input').val()+'|'+$(this).find('select').last().val()+'|'+$(this).find('select').first().val());
		});
		$.post('optimize_ajax.php?action=add_macro', { value: list });
	}
	function addRow() {
		var block = $('.form-group').last();
		destroyInputs();
		var clone = block.clone();
		clone.find('input,select').val('');
		block.after(clone);
		initInputs();
		setSave();
	}
	function remRow(img) {
		if($('.form-group').length == 1) {
			addRow();
		}
		$(img).closest('.form-group').remove();
		saveMacros();
	}
	</script>
	<div class="hide-title-mob">
		<div class="col-sm-4">Macro Name</div>
		<div class="col-sm-4"><?= TICKET_NOUN ?> Type</div>
		<div class="col-sm-3">File Name</div>
	</div>
	<?php $macro_list[] = '';
	foreach($macro_list as $label => $file) { ?>
		<div class="form-group">
			<div class="col-sm-4">
				<span class="show-on-mob">Macro Name:</span>
				<input type="text" class="form-control" name="label" value="<?= $file != '' ? $label : '' ?>">
			</div>
			<div class="col-sm-4">
				<span class="show-on-mob"><?= TICKET_NOUN ?> Type:</span>
				<select class="chosen-select-deselect" name="file" data-placeholder="Select Type..."><option />
					<?php foreach($ticket_tabs as $ticket_type) { ?>
						<option <?= config_safe_str($ticket_type) == $file[1] ? 'selected' : '' ?> value="<?= config_safe_str($ticket_type) ?>"><?= $ticket_type ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-sm-3">
				<span class="show-on-mob">File Name:</span>
				<select class="chosen-select-deselect" name="file" data-placeholder="Select File..."><option />
					<?php foreach($file_list as $file_name) { ?>
						<option <?= config_safe_str($file_name) == $file[0] ? 'selected' : '' ?> value="<?= $file_name ?>"><?= $file_name ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-sm-1">
				<img class="cursor-hand inline-img pull-right" src="../img/icons/ROOK-add-icon.png" onclick="addRow()">
				<img class="cursor-hand inline-img pull-right" src="../img/remove.png" onclick="remRow(this)">
			</div>
		</div>
	<?php }
} ?>