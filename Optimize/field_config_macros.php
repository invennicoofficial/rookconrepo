<?php $file_list = array_filter(scandir('macros'), function($filename) { return strpos($filename,'.php') !== FALSE; });
if(count($file_list) == 0) {
	echo '<h3>No Macros Found.</h3>';
} else { ?>
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
			list.push($(this).find('input').val()+'|'+$(this).find('select').val());
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
		<div class="col-sm-6">Macro Name</div>
		<div class="col-sm-5">File Name</div>
	</div>
	<?php $macro_list[] = '';
	foreach($macro_list as $label => $file) { ?>
		<div class="form-group">
			<div class="col-sm-6">
				<span class="show-on-mob">Macro Name:</span>
				<input type="text" class="form-control" name="label" value="<?= $file != '' ? $label : '' ?>">
			</div>
			<div class="col-sm-5">
				<span class="show-on-mob">File Name:</span>
				<select class="chosen-select-deselect" name="file" data-placeholder="Select File..."><option />
					<?php foreach($file_list as $file_name) { ?>
						<option <?= config_safe_str($file_name) == $file ? 'selected' : '' ?> value="<?= $file_name ?>"><?= $file_name ?></option>
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