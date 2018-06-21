<?php if(!empty($_POST['submit'])) {
	$name = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
	$assigned_tile = filter_var($_POST['assigned_tile'],FILTER_SANITIZE_STRING);
	$header = filter_var(htmlentities($_POST['header']),FILTER_SANITIZE_STRING);
	$footer = filter_var(htmlentities($_POST['footer']),FILTER_SANITIZE_STRING);
	$font = filter_var($_POST['font'],FILTER_SANITIZE_STRING);
	$contents = filter_var(htmlentities($_POST['contents']),FILTER_SANITIZE_STRING);
	$display_form = filter_var(htmlentities($_POST['display_form']),FILTER_SANITIZE_STRING);
	if(empty($_GET['id'])) {
		$query = "INSERT INTO `user_forms` (`name`, `header`, `footer`, `font`, `contents`, `display_form`, `assigned_tile`) VALUES ('$name', '$header', '$footer', '$font', '$contents', '$display_form', '$assigned_tile')";
	} else {
		$query = "UPDATE `user_forms` SET `name`='$name', `header`='$header', `footer`='$footer', `font`='$font', `contents`='$contents', `display_form`='$display_form', `assigned_tile` = '$assigned_tile' WHERE `form_id`='".intval($_GET['id'])."'";
	}
	if(!mysqli_query($dbc, $query)) {
		echo "<script> alert('Unable to save form: ".str_replace("'", '"', mysqli_error($dbc))."\nPlease contact Fresh Focus Media'); </script>";
	}
	$id = (empty($_GET['id']) ? mysqli_insert_id($dbc) : intval($_GET['id']));
	if(!empty($_FILES['header_logo']['name'])) {
		$header_logo = $basename = preg_replace('/[^a-z0-9.]*/','',strtolower($_FILES['header_logo']['name']));
		$j = 0;
		while(file_exists('download/'.$header_logo)) {
			$header_logo = preg_replace('/(\.[a-z0-9]*)/', ' ('.++$j.')$1', $basename);
		}
		move_uploaded_file($_FILES['header_logo']['tmp_name'], 'download/'.$header_logo);
		mysqli_query($dbc, "UPDATE `user_forms` SET `header_logo` = '$header_logo' WHERE `form_id`='$id'");
	}
	if(!empty($_FILES['footer_logo']['name'])) {
		$footer_logo = $basename = preg_replace('/[^a-z0-9.]*/','',strtolower($_FILES['footer_logo']['name']));
		$j = 0;
		while(file_exists('download/'.$footer_logo)) {
			$footer_logo = preg_replace('/(\.[a-z0-9]*)/', ' ('.++$j.')$1', $basename);
		}
		move_uploaded_file($_FILES['footer_logo']['tmp_name'], 'download/'.$footer_logo);
		mysqli_query($dbc, "UPDATE `user_forms` SET `footer_logo` = '$footer_logo' WHERE `form_id`='$id'");
	}
	
	// mysqli_query($dbc, "UPDATE `user_form_fields` SET `deleted`=1 WHERE `form_id`='$id'");
	foreach($_POST['delete_id'] as $i => $delete_id) {
		$field_id = filter_var($_POST['delete_id'][$i]);
		$field_name = mysqli_fetch_array(mysqli_query($dbc, "SELECT `name` FROM `user_form_fields` WHERE `field_id` = '$field_id'"))['name'];
		$query = "UPDATE `user_form_fields` SET `deleted`=1 WHERE `field_id` = '$field_id' AND `form_id` = '$id'";
		mysqli_query($dbc, $query);
		$query = "UPDATE `user_form_fields` SET `deleted`=1 WHERE `form_id` = '$id' AND `name` = '$field_name' AND `type` = 'OPTION'";
		mysqli_query($dbc, $query);
	}
	foreach($_POST['field_id'] as $i => $field_id) {
		$label = filter_var($_POST['field_label'][$i],FILTER_SANITIZE_STRING);
		$name = filter_var($_POST['field_name'][$i],FILTER_SANITIZE_STRING);
		if($label.$name != '') {
			$type = filter_var($_POST['field_type'][$i],FILTER_SANITIZE_STRING);
			$default = filter_var($_POST['field_default'][$i],FILTER_SANITIZE_STRING);
			$references = filter_var($_POST['field_references'][$i],FILTER_SANITIZE_STRING);
			$totaled = filter_var($_POST['field_totaled'][$i],FILTER_SANITIZE_STRING);
			$source_table = filter_var($_POST['field_source_table'][$i],FILTER_SANITIZE_STRING);
			$source_conditions = filter_var($_POST['field_source_conditions'][$i],FILTER_SANITIZE_STRING);
			$field_content = filter_var(htmlentities($_POST['field_content'][$i]),FILTER_SANITIZE_STRING);
			$reference_needed = [];
			$sort_order = $i;
			$mandatory = filter_var($_POST['field_mandatory'][$i],FILTER_SANITIZE_STRING);
			if($type == 'REFERENCE' || $type == 'TEXTBOXREF') {
				if(!is_numeric($references)) {
					$reference_needed[$name] = $references;
					$references = 0;
				}
				if ($_POST['field_field'][$i] == 'CUSTOM_VALUE') {
					$source_conditions = filter_var($_POST['custom_ref_value'][$i]);
				} else {
					$source_conditions = filter_var($_POST['field_field'][$i],FILTER_SANITIZE_STRING);
				}
			}
			if($type == 'TABLEADV') {
				$table_styling = filter_var($_POST['field_styling'][$i],FILTER_SANITIZE_STRING);
			}
			if($type == 'DATE') {
				$table_styling = filter_var($_POST['date_format'][$i],FILTER_SANITIZE_STRING);
			}
			if($type == 'SELECT' || $type == 'SELECT_CUS') {
				if($source_conditions == 'SELECT_CUS') {
					$type = 'SELECT_CUS';
				} else {
					$type = 'SELECT';
					if($field_id > 0) {
						$field_name = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `name` FROM `user_form_fields` WHERE `field_id` = '$field_id'"))['name'];
						mysqli_query($dbc, "UPDATE `user_form_fields` SET `deleted` = 1 WHERE `type` = 'OPTION' AND `name` = '$name' AND `form_id` = '$id'");
					}
				}
			}
			
			if ($name != '') {
				if($field_id > 0) {
					$query = "UPDATE `user_form_fields` SET `deleted`=0, `form_id`='$id', `label`='$label', `name`='$name', `type`='$type', `default`='$default', `references`='$references', `totaled`='$totaled', `source_table`='$source_table', `source_conditions`='$source_conditions', `sort_order` = '$sort_order', `content` = '$field_content', `styling` = '$table_styling', `mandatory` = '$mandatory' WHERE `field_id`='$field_id'";
				} else {
					$query = "INSERT INTO `user_form_fields` (`form_id`, `label`, `name`, `type`, `default`, `references`, `totaled`, `source_table`, `source_conditions`, `sort_order`, `content`, `styling`, `mandatory`)
						VALUES ('$id', '$label', '$name', '$type', '$default', '$references', '$totaled', '$source_table', '$source_conditions', '$sort_order', '$field_content', '$table_styling', '$mandatory')";
				}
				if(!mysqli_query($dbc, $query)) {
					echo "<script> alert('Unable to save field $name: ".str_replace("'", '"', mysqli_error($dbc))."\nPlease contact Fresh Focus Media'); </script>";
				}
			}
			if ($type == 'TABLEADV') {
				$row_sort_order = 0;
				foreach($_POST['option_row_id'] as $i => $option_row_id) {
					$row_name = filter_var($_POST['option_row_name'][$i],FILTER_SANITIZE_STRING);
					$row_label = filter_var(htmlentities(implode('*#*', $_POST['option_row_'.$i])),FILTER_SANITIZE_STRING);
					$row_type = 'OPTION';
					if($row_name == $name) {
						if($option_row_id > 0) {
							$query = "UPDATE `user_form_fields` SET `deleted`=0, `form_id`='$id', `label`='$row_label', `name`='$row_name', `type`='$row_type', `sort_order` = '$row_sort_order' WHERE `field_id`='$option_row_id'";
						} else {
							$query = "INSERT INTO `user_form_fields` (`form_id`, `label`, `name`, `type`, `sort_order`)
								VALUES ('$id', '$row_label', '$row_name', '$row_type', '$row_sort_order')";
						}
						if(!mysqli_query($dbc, $query)) {
							echo "<script> alert('Unable to save option $label: ".str_replace("'", '"', mysqli_error($dbc))."\nPlease contact Fresh Focus Media'); </script>";
						}
						$row_sort_order++;
					}
				}
			}
		}
	}
	foreach($reference_needed as $field_name => $src_name) {
		mysqli_query($dbc, "UPDATE `user_form_fields` ufr LEFT JOIN `user_form_fields` ufs ON ufr.`name`='$field_name' AND ufs.`name`='$src_name' AND ufs.`form_id`=ufr.`form_id` SET ufr.`references`=ufs.`field_id` WHERE ufs.`form_id`='$id'");
	}
	foreach($_POST['option_id'] as $i => $option_id) {
		$name = filter_var($_POST['option_name'][$i],FILTER_SANITIZE_STRING);
		$label = filter_var($_POST['option_label'][$i],FILTER_SANITIZE_STRING);
		$sort_order = $i;
		if($label != '') {
			$totaled = filter_var($_POST['option_totaled'][$i],FILTER_SANITIZE_STRING);
			$type = 'OPTION';
			if($option_id > 0) {
				$query = "UPDATE `user_form_fields` SET `deleted`=0, `form_id`='$id', `label`='$label', `name`='$name', `type`='$type', `totaled`='$totaled', `sort_order` = '$sort_order' WHERE `field_id`='$option_id'";
			} else {
				$query = "INSERT INTO `user_form_fields` (`form_id`, `label`, `name`, `type`, `totaled`, `sort_order`)
					VALUES ('$id', '$label', '$name', '$type', '$totaled', '$sort_order')";
			}
			if(!mysqli_query($dbc, $query)) {
				echo "<script> alert('Unable to save option $label: ".str_replace("'", '"', mysqli_error($dbc))."\nPlease contact Fresh Focus Media'); </script>";
			}
		}
	}
	
	echo "<script> location.replace('?tab=add_form&id=$id'); </script>";
} else {
	$formid = 0;
	$name = '';
	$assigned_tile = '';
	$header = '';
	$header_logo = '';
	$footer = '';
	$footer_logo = '';
	$form_font = '';
	$contents = '';
	$display_form = '';
	if(!empty($_GET['id'])) {
		$formid = $_GET['id'];
		$user_form = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `user_forms` WHERE `form_id`='{$_GET['id']}'"));
		$name = $user_form['name'];
		$assigned_tile = $user_form['assigned_tile'];
		$header = $user_form['header'];
		$header_logo = $user_form['header_logo'];
		$footer = $user_form['footer'];
		$footer_logo = $user_form['footer_logo'];
		$form_font = $user_form['font'];
		$contents = $user_form['contents'];
		$display_form = $user_form['display_form'];
	} else {
		$defaults = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_user_forms`"));
		$header = $defaults['default_header'];
		$header_logo = $defaults['default_head_logo'];
		$footer = $defaults['default_footer'];
		$footer_logo = $defaults['default_foot_logo'];
	} ?>
	<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
		<div class="panel-group" id="accordion">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_style" >
                            Form Settings<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_style" class="panel-collapse collapse">
                    <div class="panel-body">
						<div class="form-group">
							<label class="col-sm-4 control-label">Form Name:</label>
							<div class="col-sm-8">
								<input name="name" type="text" value="<?= $name ?>" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Assign to Tile:</label>
							<div class="col-sm-8">
								<select data-placeholder="Select a tile..." class="form-control chosen-select-deselect" name="assigned_tile">
									<option <?php echo $assigned_tile == '' ? 'selected' : ''; ?> value=''>NONE</option>
									<option <?php echo $assigned_tile == 'hr' ? 'selected' : ''; ?> value='hr'>HR</option>
									<option <?php echo $assigned_tile == 'infogathering' ? 'selected' : ''; ?> value='infogathering'>Information Gathering</option>
									<option <?php echo $assigned_tile == 'safety' ? 'selected' : ''; ?> value='safety'>Safety</option>
									<option <?php echo $assigned_tile == 'treatment' ? 'selected' : ''; ?> value='treatment'>Treatment Charts</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Header Logo:</label>
							<div class="col-sm-8">
								<?php if(!empty($header_logo) && file_exists('download/'.$header_logo)) { ?>
									<img src="download/<?= $header_logo ?>" height="30px;">
								<?php } ?>
								<input name="header_logo" type="file" data-filename-placement="inside" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Header Text:</label>
							<div class="col-sm-8">
								<textarea name="header"><?= html_entity_decode($header) ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Footer Logo:</label>
							<div class="col-sm-8">
								<?php if(!empty($footer_logo) && file_exists('download/'.$footer_logo)) { ?>
									<img src="download/<?= $footer_logo ?>" height="30px;">
								<?php } ?>
								<input name="footer_logo" type="file" data-filename-placement="inside" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Footer Text:</label>
							<div class="col-sm-8">
								<textarea name="footer"><?= html_entity_decode($footer) ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<?php $font_list = [ 'courier' => 'Courier (Fixed Width)', 'helvetica' => 'Helvetica', 'times' => 'Times New Roman' ];
							if (!file_exists('fonts')) {
								mkdir('fonts', 0777, true);
							}
							foreach(scandir('fonts') as $font) {
								if(strpos($font, '.ttf') !== FALSE) {
									$font_list[$font] = ucwords(str_replace(['_','.ttf'],[' ',''],$font));
								}
							} ?>
							<table class="table table-bordered">
								<tr class="hidden-sm hidden-xs">
									<th>Font Name</th>
									<th>Font Demo</th>
								</tr>
								<?php foreach($font_list as $file => $font) { ?>
									<?php if(file_exists('fonts/'.$file)) { ?>
										<style>
										@font-face {
											font-family: <?= preg_replace('/[^A-Za-z]/', '', $font) ?>;
											src: url('fonts/<?= $file ?>');
										}
										</style>
									<?php } ?>
									<tr style="font-family: <?= ($file == 'courier' || $file == 'helvetica' || $file == 'times' ? $file : preg_replace('/[^A-Za-z]/', '', $font)) ?>">
										<td data-title="Font Name"><?= $font ?></td>
										<td data-title="Font Demo">The quick brown fox jumps over the lazy dog.</font></td>
									</tr>
								<?php } ?>
							</table>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Select Default Font:<br /><em>This will be used for all forms unless a font is specified for the form.</em></label>
							<div class="col-sm-8">
								<select data-placeholder="Select a font..." class="form-control chosen-select-deselect" name="font"><option></option>
									<?php foreach($font_list as $file => $font) { ?>
										<option <?= ($file == $form_font ? 'selected' : '') ?> value="<?= $file ?>"><?= $font ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_content" >
                            Form Contents<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_content" class="panel-collapse collapse">
                    <div class="panel-body">
						<script>
						function insertField(btn) {
							tinyMCE.get('form_contents').execCommand('mceInsertContent', false, '[['+$('[name="field_name[]"]').get($(btn).data('index')).value+']]');
							return false;
						}<?php if($formid > 0) { ?>
							$(document).ready(function() {
								fieldButtons();
								$('select[name="field_default[]"]').each(function() {
									setDefault(this);
								});
							});
						<?php } ?>
						</script>
						<div class="form-group">
							<label class="col-sm-4 control-label form-contents">Form Contents:</label>
							<div class="col-sm-8">
								<textarea id="form_contents" name="contents"><?= html_entity_decode($contents) ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Display Form Contents:</label>
							<div class="col-sm-8">
								<input type="checkbox" name="display_form" value="1" <?php echo $display_form == 1 ? 'checked' : ''; ?>>
							</div>
						</div>
					</div>
				</div>
			</div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_fields" >
                            Form Fields<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_fields" class="panel-collapse collapse">
                    <div class="panel-body">
						<script>
						$(document).on('change', 'select[name="field_type[]"]', function() { fieldButtons(); });
						$(document).on('change', 'select[name="field_default[]"]', function() { setDefault(this); });
						$(document).on('change', 'select[name="dropdown_source_onchange"]', function() { setDropdownFields($(this).find('option:selected')); });
						$(document).on('change', 'select[name="field_field[]"]', function() { setDropdownFields($(this).find('option:selected')); });
						function addField() {
							// Remove mce plugin for source and cloned textarea
							var oldId = $('#collapse_fields .form-group').last().find('textarea').attr('id');
							var newId = 'field_content_' + $('#counter_num').val();
							$('#counter_num').val(parseInt($('#counter_num').val()) + 1);
							tinymce.EditorManager.execCommand('mceRemoveEditor', true, oldId);

							var fieldGroup = $('#collapse_fields .form-group').last().clone();
							fieldGroup.find('input,select').val('');
							resetChosen(fieldGroup.find('select'));
							fieldGroup.find('select[name="field_default[]"]').removeAttr('disabled').trigger('change.select2').closest('.selectSpan').show();
							fieldGroup.find('input[name="field_default[]"]').prop('disabled','disabled').val('').hide().focus();
							fieldGroup.find('textarea').val('');
							fieldGroup.find('textarea').attr('id', newId);
							$('#collapse_fields .panel-body').append(fieldGroup);

							// Reinitialize mce plugin for source and cloned textarea
							tinymce.EditorManager.execCommand('mceAddEditor', true, oldId);
							tinymce.EditorManager.execCommand('mceAddEditor', true, newId);
							return false;
						}
						function addField(link) {
							// Remove mce plugin for source and cloned textarea
							var oldId = $('#collapse_fields .form-group').last().find('textarea').attr('id');
							var newId = 'field_content_' + $('#counter_num').val();
							$('#counter_num').val(parseInt($('#counter_num').val()) + 1);
							tinymce.EditorManager.execCommand('mceRemoveEditor', true, oldId);

							var fieldGroup = $('#collapse_fields .form-group').last().clone();
							fieldGroup.find('input,select').val('');
							resetChosen(fieldGroup.find('select'));
							fieldGroup.find('select[name="field_default[]"]').removeAttr('disabled').trigger('change.select2').closest('.selectSpan').show();
							fieldGroup.find('input[name="field_default[]"]').prop('disabled','disabled').val('').hide().focus();
							fieldGroup.find('textarea').val('');
							fieldGroup.find('textarea').attr('id', newId);
							fieldGroup.find('.tableadv_fields').find('tr').each(function() {
								var counter = parseInt($('#counter_num').val());
								$(this).find('input.option_row_id').attr('name', 'option_row_id['+counter+']');
								$(this).find('input.option_row_name').attr('name', 'option_row_name['+counter+']').val('');
								$(this).find('input.form-control').attr('name', 'option_row_'+counter+'[]');
								counter++;
								$('#counter_num').val(counter);
							});
							$(link).parent('.form-group').after(fieldGroup);

							// Reinitialize mce plugin for source and cloned textarea
							tinymce.EditorManager.execCommand('mceAddEditor', true, oldId);
							tinymce.EditorManager.execCommand('mceAddEditor', true, newId);
							return false;
						}
						function remField(link) {
							if($('#collapse_fields.form-group').length == 1) {
								addField();
							}
							var delete_id = $(link).parent('.form-group').find('input[name="field_id[]"]').val();
							var delete_html = '<input type="hidden" name="delete_id[]" value="' + delete_id + '">';
							$('#collapse_fields .panel-body').append(delete_html);
							$(link).closest('.form-group').remove();
							fieldButtons();
							return false;
						}
						function addOption(link) {
							var option = $(link).closest('.option_fields,.table_fields').find('div.col-sm-12').last().clone();
							option.find('input[type=text]').val('');
							$(link).closest('.option_fields,.table_fields').append(option).find('input[type=text]').last().focus();
							return false;
						}
						function remOption(link) {
							if($(link).closest('.option_fields,.table_fields').find('div.col-sm-12').length == 1) {
								addOption(link);
							}
							var delete_id = $(link).parent('div.col-sm-8').find('input[name="option_id[]"]').val();
							var delete_html = '<input type="hidden" name="delete_id[]" value="' + delete_id + '">';
							$('#collapse_fields .panel-body').append(delete_html);
							$(link).closest('div.col-sm-12').remove();
							return false;
						}
						function setDefault(select) {
							if(select.value == 'TEXT') {
								$(select).prop('disabled','disabled').trigger('change.select2').closest('.selectSpan').hide();
								$(select).closest('.form-group').find('input[name="field_default[]"]').removeAttr('disabled').show().focus();
							}
						}
						function fieldButtons() {
							$('label.form-contents').empty().html('Form Contents:');
							var valid = true;
							$('[name="field_name[]"]').each(function() {
								var current_value = this.value;
								if(valid && $('[name="field_name[]"]').filter(function() { return this.value == current_value; }).length > 1) {
									valid = false;
									if (this.value != '') {
										alert('You cannot have multiple fields with the same name. Please change one of the fields.');
									}
								} else {
									$(this).closest('.form-group').find('.option_fields input[name="option_name[]"],.table_fields input[name="option_name[]"]').val(this.value);
									$(this).closest('.form-group').find('.tableadv_fields').find('.option_row_name	').val(this.value);
								}
							});
							if(valid) {
								var i = 0;
								$('[name="field_label[]"]').each(function() {
									if(this.value != '') {
										$('label.form-contents').append('<br /><button class="btn brand-btn" data-index="'+i+'" onclick="return insertField(this);">Insert '+this.value+'</button>');
									}
									i++;
								});
							}
							$('[name="field_type[]"]').each(function() {
								group = $(this).closest('.form-group');
								displayDefaultOptions(this.value, group);
								fieldTypeDescription(this.value, group);
								group.find('.dropdown_fields').hide();
								group.find('.option_fields').hide();
								group.find('.reference_fields').hide();
								group.find('.table_fields').hide();
								group.find('.text_content').hide();
								group.find('.tableadv_fields').hide();
								group.find('.date_format').hide();
								switch(this.value) {
									case 'DATE':
										group.find('.date_format').show();
										break;
									case 'TABLEADV':
										group.find('.default_value').hide();
										group.find('.option_fields').hide();
										group.find('.text_content').hide();
										group.find('.tableadv_fields').show();
										break;
									case 'SELECT_CUS':
										group.find('.default_value').hide();
										group.find('.dropdown_fields').show();
										group.find('.option_fields').show();
										group.find('.text_content').hide();
										group.find('.tableadv_fields').hide();
										break;
									case 'RADIO':
									case 'CHECKBOX':
										group.find('.default_value').hide();
										group.find('.option_fields').show();
										group.find('.text_content').hide();
										group.find('.tableadv_fields').hide();
										break;
									case 'SELECT':
										group.find('.default_value').show();
										group.find('.dropdown_fields').show();
										group.find('.text_content').hide();
										group.find('.tableadv_fields').hide();
										break;
									case 'TABLE':
										group.find('.default_value').hide();
										group.find('.table_fields').show();
										group.find('.text_content').hide();
										group.find('.tableadv_fields').hide();
										break;
									case 'TEXTBOXREF':
									case 'REFERENCE':
										var options = [];
										var ids = [];
										$('.form-group [name="field_type[]"]').filter(function() { return this.value == 'SELECT' }).each(function() {
											var name = $(this).closest('.form-group').find('[name="field_name[]"]').val();
											var id = $(this).closest('.form-group').find('[name="field_id[]"]').val();
											if(id == 0) {
												id = name;
											}
											options.push(name);
											ids.push(id);
										});
										var ref_src = group.find('.reference_fields [name="field_references[]"]').data('id');
										group.find('.reference_fields [name="field_references[]"]').empty().append('<option value=0></option>');
										for(var i = 0; i < options.length; i++) {
											group.find('.reference_fields [name="field_references[]"]').append('<option '+(ids[i] == ref_src ? 'selected' : '')+' value="'+ids[i]+'">'+options[i]+'</option>');
										}
										group.find('.reference_fields [name="field_references[]"]').trigger('change.select2');
										group.find('.default_value').hide();
										group.find('.reference_fields').show();
										group.find('.text_content').hide();
										group.find('.tableadv_fields').hide();
										break;
									case 'CHECKINFO':
									case 'TIME':
									case 'TEXT':
									case 'MULTISIGN':
									case 'ACCORDION':
										group.find('.default_value').hide();
										group.find('.text_content').hide();
										group.find('.tableadv_fields').hide();
										break;
									case 'TEXTBLOCK':
										group.find('.default_value').hide();
										group.find('.reference_fields').hide();
										group.find('.text_content').show();
										group.find('.tableadv_fields').hide();
										break;
									case 'TEXTAREA':
									default:
										group.find('.default_value').show();
										group.find('.text_content').hide();
										group.find('.tableadv_fields').hide();
										
								}
							});
						}
						function setDropdownFields(option) {
							if ($(option).val() == 'CUSTOM_VALUE') {
								$(option).closest('.reference_fields').find('div.custom_ref_value').show();
								$(option).closest('.form-group').find('div.option_fields').hide();
								// $(option).closest('.reference_fields').find('div.custom_ref_value').find('input[name="custom_ref_value[]"]').val('');
							} else if($(option).val() == 'SELECT_CUS') {
								$(option).closest('.reference_fields').find('div.custom_ref_value').hide();
								$(option).closest('.form-group').find('div.option_fields').show();
								$(option).closest('.form-group').find('.default_value').hide();
								$(option).closest('.dropdown_fields').find('[name="field_source_table[]"]').val($(option).data('table'));
								$(option).closest('.dropdown_fields').find('[name="field_source_conditions[]"]').val($(option).data('condition'));
							} else {
								$(option).closest('.reference_fields').find('div.custom_ref_value').hide();
								$(option).closest('.form-group').find('div.option_fields').hide();
								$(option).closest('.form-group').find('.default_value').show();
								// $(option).closest('.reference_fields').find('div.custom_ref_value').find('input[name="custom_ref_value[]"]').val('not_custom');
								$(option).closest('.dropdown_fields').find('[name="field_source_table[]"]').val($(option).data('table'));
								$(option).closest('.dropdown_fields').find('[name="field_source_conditions[]"]').val($(option).data('condition'));
							}
						}
						function addContentInput(button) {
							var text_content = $(button).closest('.text_content').find('textarea');
                        	tinyMCE.get(text_content.attr('id')).execCommand('mceInsertContent', false, '[[input]]');
						}
						function tableAddRow(link) {
							var counter= $('#counter_num').val();
							$('#counter_num').val(parseInt($('#counter_num').val()) + 1);
							var row = $(link).closest('div.col-sm-8').find('table tr').last().clone();
							row.find('input.form-control').val('');
							row.find('input.option_row_id').val('');
							row.find('input.form-control').attr('name', 'option_row_'+counter+'[]');
							row.find('input.option_row_id').attr('name', 'option_row_id['+counter+']');
							row.find('input.option_row_name').attr('name', 'option_row_name['+counter+']');
							$(link).closest('div.col-sm-8').find('table').append(row);
							return false;
						}
						function tableRemRow(link) {
							var delete_id = $(link).closest('div.col-sm-8').find('table tr').last().find('input.option_row_id').val();
							var delete_html = '<input type="hidden" name="delete_id[]" value="' + delete_id + '">';
							$('#collapse_fields .panel-body').append(delete_html);

							if ($(link).closest('div.col-sm-8').find('table tr').length == 2) {
								tableAddRow(link);
								$(link).closest('div.col-sm-8').find('table tr').last().prev().remove();
							} else {
								$(link).closest('div.col-sm-8').find('table tr').last().remove();
							}
							return false;
						}
						function tableAddCol(link) {
							$(link).closest('div.col-sm-8').find('table tr').each(function() {
								var table_cell = $(this).find('td').last().clone();
								table_cell.find('input').val('');
								$(this).append(table_cell);
								var table_cell = $(this).find('th').last().clone();
								table_cell.find('input').val('');
								$(this).append(table_cell);
							});
							return false;
						}
						function tableRemCol(link) {
							$(link).closest('div.col-sm-8').find('table tr').each(function() {
								if ($(this).find('th').length == 1 || $(this).find('td').length == 1) {
									$(this).find('th').first().find('input').val('');
									$(this).find('td').first().find('input').val('');
								} else {
									$(this).find('th').last().remove();
									$(this).find('td').last().remove();
								}
							});
							return false;
						}
						function displayDefaultOptions(type, group) {
							var default_sel = $(group).find('[name="field_default[]"]');
							default_sel.find('option').hide();
							default_sel.find('option[value=""]').show();
							switch(type) {
								case 'DATE':
								case 'DATETIME':
									default_sel.find('option[value="TIMESTAMP"]').show();
									break;
								case 'SIGNONLY':
								case 'SIGN':
								case 'SELECT':
									default_sel.find('option[value="SESSION_CONTACT"]').show();
									break;
								default:
									default_sel.find('option').show();
							}
							default_sel.trigger('change.select2');
						}
						function fieldTypeDescription(type, group) {
							var desc_div = $(group).find('div.field_type_description');
							var desc = '';
							switch(type) {
								case 'TEXT':
									desc = "This field displays a line of text in the Form (no input from the user).";
									break;
								case 'TEXTBLOCK':
									desc = "This field displays a block of text in the Form (no input from the user).";
									break;
								case 'TEXTBOX':
									desc = "This field is a text input field filled in by the user.";
									break;
								case 'TEXTBOXREF':
									desc = "This field is a text input field filled in by the user with advanced Reference Values. This value will be automatically filled in when the Reference Source is chosen (it will fetch the field type chosen in the database). The Reference Source dropdown field is based on fields from this Form that have the Dropdown field type with a Contact Type as the Dropdown Source.";
									break;
								case 'TEXTAREA':
									desc = "This field is a text area field filled in by the user.";
									break;
								case 'DATE':
									desc = "This field is a date field filled in by the user.";
									break;
								case 'DATETIME':
									desc = "This field is a date and time field filled in by the user.";
									break;
								case 'TIME':
									desc = "This field is a time field filled in by the user.";
									break;
								case 'SELECT_CUS':
								case 'SELECT':
									desc = "This field is a dropdown field filled in by the user. The values in the Dropdown Source are based on the different Contact types in the software. Choosing the Custom Values value will allow you to input your own dropdown options.";
									break;
								case 'REFERENCE':
									desc = "This field is an advanced reference field in which you choose a field from the database and based on the Reference Source, this value will be populated by the chosen value for the Reference Source. The Reference Source dropdown field is based on fields from this Form that have the Dropdown field type with a Contact Type as the Dropdown Source. This is not a user input field and will only display if it is input into the PDF content.";
									break;
								case 'RADIO':
									desc = "This field consists of Radio buttons, which are buttons that will only allow the user to check off only one value.";
									break;
								case 'CHECKBOX':
									desc = "This field consists of Checkbox buttons, which are buttons that allows the user to check and uncheck as many checkboxes as they would like.";
									break;
								case 'CHECKINFO':
									desc = "This field consists of a single Checkbox button with a custom text input value filled in by the user.";
									break;
								case 'SIGNONLY':
									desc = "This field is a Signature box.";
									break;
								case 'SIGN':
									desc = "This field consists of a Signature box, Name, and Date.";
									break;
								case 'MULTISIGN':
									desc = "This field consists of a Signature box, Name, and Date and also allows the user to add multiple Signatures.";
									break;
								case 'TABLE':
									desc = "This field generates a table for the user with all the column types that you add to the table. Ticking the Total checkbox off makes that column numbers only, and will create a sum of the numbers when the PDF is generated.";
									break;
								case 'TABLEADV':
									desc = "This field is an advanced table that allows the user to specify the number of rows, columns, where the user puts inputs, table styling, cell styling, row-span, column-span, checkboxes, etc. Should only be used by advanced users.";
									break;
								case 'ACCORDION':
									desc = "This field creates a new Accordion for the Form as a way to organize the Form better (no input from the user).";
									break;
							}
							$(desc_div).html(desc);
						}
						function mandatoryField(chk) {
							if($(chk).is(':checked')) {
								$(chk).closest('.mandatory_field').find('[name="field_mandatory[]"]').val('1');
							} else {
								$(chk).closest('.mandatory_field').find('[name="field_mandatory[]"]').val('0');
							}
						}
						</script>
						<?php $field_list = mysqli_query($dbc, "SELECT * FROM (SELECT * FROM `user_form_fields` WHERE `form_id`='$formid' AND `type`!='OPTION' AND `deleted`=0 ORDER BY `sort_order`) AS form_fields UNION
							SELECT 0, '$formid', '', '', 'TEXTBOX', '', 0, 0, '', '', 0, 9999, '', '', 0 ORDER BY `sort_order`");
						$dropdown_source_tables = [];
						$dropdown_source_categories = [];
						foreach(array_filter(array_unique(explode(',',get_config($dbc,'contacts_tabs').','.get_config($dbc,'contacts3_tabs').','.get_config($dbc,'contactsrolodex_tabs').','.get_config($dbc,'clientinfo_tabs').',Staff'))) as $category) {
							if($category != '') {
								$dropdown_source_tables[] = 'contacts';
								$dropdown_source_categories[] = $category;
							}
						}
						sort($dropdown_source_categories);
						$counter = 0;
						$dropdown_field_categories = ['name', 'contact_name', 'full_address', 'street', 'city', 'province', 'postal', 'country', 'home_phone', 'office_phone', 'cell_phone', 'email_address', 'birth_date'];
						while($field_info = mysqli_fetch_array($field_list)) { ?>
							<div class="form-group">
								<input type="hidden" name="field_id[]" value="<?= $field_info['field_id'] ?>">
								<label class="col-sm-4 control-label">Field Label:<br /><em>This is what will appear on the screen when completing the form.</em></label>
								<div class="col-sm-8">
									<input type="text" name="field_label[]" value="<?= $field_info['label'] ?>" onchange="fieldButtons();" class="form-control">
								</div><div class="clearfix"></div>
								<label class="col-sm-4 control-label">Field Name:<br /><em>This must be a unique name for the field for this form.</em></label>
								<div class="col-sm-8">
									<input type="text" name="field_name[]" value="<?= $field_info['name'] ?>" onblur="fieldButtons();" class="form-control">
								</div><div class="clearfix"></div>
								<label class="col-sm-4 control-label">Field Type:</label>
								<div class="col-sm-8">
									<select name="field_type[]" class="form-control chosen-select-deselect"><option></option>
										<option <?= ($field_info['type'] == 'TEXT' || $field_info['type'] == '' ? 'selected' : '') ?> value="TEXT">Text (Single Line Text)</option>
										<option <?= ($field_info['type'] == 'TEXTBLOCK' ? 'selected' : '') ?> value="TEXTBLOCK">Text Block (Multi Line Text)</option>
										<option <?= ($field_info['type'] == 'TEXTBOX' || $field_info['type'] == '' ? 'selected' : '') ?> value="TEXTBOX">Textbox (Single Line Input)</option>
										<option <?= ($field_info['type'] == 'TEXTBOXREF' || $field_info['type'] == '' ? 'selected' : '') ?> value="TEXTBOXREF">Textbox (With Reference Source)</option>
										<option <?= ($field_info['type'] == 'TEXTAREA' ? 'selected' : '') ?> value="TEXTAREA">Text Area (Multi Line Input)</option>
										<option <?= ($field_info['type'] == 'DATE' ? 'selected' : '') ?> value="DATE">Date Box</option>
										<option <?= ($field_info['type'] == 'DATETIME' ? 'selected' : '') ?> value="DATETIME">Date Time Box</option>
										<option <?= ($field_info['type'] == 'TIME' ? 'selected' : '') ?> value="TIME">Time Box</option>
										<option <?= ($field_info['type'] == 'SELECT' || $field_info['type'] == 'SELECT_CUS' ? 'selected' : '') ?> value="<?= $field_info['type'] == 'SELECT_CUS' ? 'SELECT_CUS' : 'SELECT' ?>">Dropdown</option>
										<!-- <option <?= ($field_info['type'] == 'SELECT_CUS' ? 'selected' : '') ?> value="SELECT_CUS">Dropdown Custom</option> -->
										<option <?= ($field_info['type'] == 'REFERENCE' ? 'selected' : '') ?> value="REFERENCE">Referential Value</option>
										<option <?= ($field_info['type'] == 'RADIO' ? 'selected' : '') ?> value="RADIO">Radio Buttons</option>
										<option <?= ($field_info['type'] == 'CHECKBOX' ? 'selected' : '') ?> value="CHECKBOX">Checkbox</option>
										<option <?= ($field_info['type'] == 'CHECKINFO' ? 'selected' : '') ?> value="CHECKINFO">Checkbox with Detail</option>
										<option <?= ($field_info['type'] == 'SIGNONLY' ? 'selected' : '') ?> value="SIGNONLY">Signature</option>
										<option <?= ($field_info['type'] == 'SIGN' ? 'selected' : '') ?> value="SIGN">Signature, Name, and Date</option>
										<option <?= ($field_info['type'] == 'MULTISIGN' ? 'selected' : '') ?> value="MULTISIGN">Multiple Signatures, Names, and Dates</option>
										<option <?= ($field_info['type'] == 'TABLE' ? 'selected' : '') ?> value="TABLE">Table</option>
										<option <?= ($field_info['type'] == 'TABLEADV' ? 'selected' : '') ?> value="TABLEADV">Table (Advanced)</option>
										<option <?= ($field_info['type'] == 'ACCORDION' ? 'selected' : '') ?> value="ACCORDION">Accordion</option>
									</select>
									<div class="field_type_description"></div>
								</div><div class="clearfix"></div>
								<label class="col-sm-4 control-label default_value">Default Value:</label>
								<div class="col-sm-8 default_value">
									<span class="selectSpan"><select name="field_default[]" value="<?= $field_info['default'] ?>" class="chosen-select-deselect form-control"><option></option>
										<option <?= $field_info['default'] == 'SESSION_CONTACT' ? 'selected' : '' ?> value="SESSION_CONTACT">Current User</option>
										<option <?= $field_info['default'] == 'TIMESTAMP' ? 'selected' : '' ?> value="TIMESTAMP">Current Date/Time</option>
										<option <?= $field_info['default'] != 'TIMESTAMP' && $field_info['default'] != 'SESSION_CONTACT' && $field_info['default'] != '' ? 'selected' : '' ?> value="TEXT">Text Value</option></select></span>
									<input type="text" name="field_default[]" value="<?= $field_info['default'] ?>" class="form-control" disabled style="display:none;">
								</div><div class="clearfix"></div>
								<div class="dropdown_fields" style="display:none;">
									<input type="hidden" name="field_source_table[]" value="<?= $field_info['source_table'] ?>">
									<input type="hidden" name="field_source_conditions[]" value="<?= $field_info['source_conditions'] ?>">
									<label class="col-sm-4 control-label">Dropdown Source:</label>
									<div class="col-sm-8">
										<select class="chosen-select-deselect form-control dropdown_source_onchange"><option></option>
											<option <?= $field_info['type'] == 'SELECT_CUS' ? 'selected' : '' ?> data-table="SELECT_CUS" data-condition="SELECT_CUS" value="SELECT_CUS">Custom Values</option>
											<?php foreach($dropdown_source_categories as $i => $category) {
												echo "<option ".($category == $field_info['source_conditions'] && $dropdown_source_tables[$i] == $field_info['source_table'] ? 'selected' : '')." data-table='".$dropdown_source_tables[$i]."' data-condition='$category'>$category Contacts</option>";
											} ?>
										</select>
									</div>
								</div>
								<div class="date_format" style="display:none;">
									<label class="col-sm-4 control-label">Date Format:</label>
									<div class="col-sm-8">
										<select class="chosen-select-deselect form-control" name="date_format[]">
											<option <?= ($field_info['styling'] != '/' ? 'selected' : '') ?> value="">YYYY-MM-DD</option>
											<option <?= ($field_info['styling'] == '/' ? 'selected' : '') ?> value="/">YYYY/MM/DD</option>
										</select>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="option_fields" style="display:none;">
									<?php $option_list = mysqli_query($dbc, "SELECT * FROM (SELECT `field_id`, `label` FROM `user_form_fields` WHERE `form_id`='$formid' AND `type`='OPTION' AND `name`='".$field_info['name']."' AND '".$field_info['type']."' IN ('RADIO','CHECKBOX','SELECT_CUS') AND `deleted`=0 ORDER BY `sort_order`) AS form_fields UNION SELECT 0, ''");
									while($option_info = mysqli_fetch_array($option_list)) { ?>
										<div class="col-sm-12">
											<label class="col-sm-4 control-label">Option Value:</label>
											<div class="col-sm-8">
												<input type="hidden" name="option_id[]" value="<?= $option_info['field_id'] ?>">
												<input type="hidden" name="option_name[]" value="<?= $field_info['name'] ?>">
												<input type="hidden" name="option_totaled[]" value="0">
												<div class="col-sm-11"><input type="text" class="form-control" name="option_label[]" onchange="" value="<?= $option_info['label'] ?>"></div>
												<a href="" onclick="return addOption(this);" class="pull-right"><img src="<?= WEBSITE_URL ?>/img/plus.png"></a>
												<a href="" onclick="return remOption(this);" class="pull-right" style="position: relative; left: -1em;"><img src="<?= WEBSITE_URL ?>/img/remove.png"></a>
											</div>
										</div>
									<?php } ?>
								</div>
								<div class="reference_fields" style="display:none;">
									<?php
										$is_custom_value = (!in_array($field_info['source_conditions'], $dropdown_field_categories) && $field_info['source_conditions'] != '') ? true : false;
									?>
									<label class="col-sm-4 control-label">Reference Source:<br /><em>This field will pull a chosen value based on the contact selected in the Reference Source field.</em></label>
									<div class="col-sm-8">
										<select class="chosen-select-deselect form-control" name="field_references[]" data-id="<?= $field_info['references'] ?>"><option value=0>N/A</option>
										</select>
									</div>
									<div class="clearfix"></div>
									<label class="col-sm-4 control-label">Field Name:</label>
									<div class="col-sm-8">
										<select class="chosen-select-deselect form-control" name="field_field[]"><option></option>
											<option <?= $field_info['source_conditions'] == 'name' ? 'selected' : '' ?> value="name">Business Name</option>
											<option <?= $field_info['source_conditions'] == 'contact_name' ? 'selected' : '' ?> value="contact_name">Contact Name</option>
											<option <?= $field_info['source_conditions'] == 'full_address' ? 'selected' : '' ?> value="full_address">Full Address</option>
											<option <?= $field_info['source_conditions'] == 'street' ? 'selected' : '' ?> value="street">Street Address</option>
											<option <?= $field_info['source_conditions'] == 'city' ? 'selected' : '' ?> value="city">City</option>
											<option <?= $field_info['source_conditions'] == 'province' ? 'selected' : '' ?> value="province">Province</option>
											<option <?= $field_info['source_conditions'] == 'postal_code' ? 'selected' : '' ?> value="postal">Postal Code</option>
											<option <?= $field_info['source_conditions'] == 'country' ? 'selected' : '' ?> value="country">Country</option>
											<option <?= $field_info['source_conditions'] == 'home_phone' ? 'selected' : '' ?> value="home_phone">Home Phone</option>
											<option <?= $field_info['source_conditions'] == 'office_phone' ? 'selected' : '' ?> value="business_phone">Business Phone</option>
											<option <?= $field_info['source_conditions'] == 'cell_phone' ? 'selected' : '' ?> value="cell_phone">Cell Phone</option>
											<option <?= $field_info['source_conditions'] == 'email_address' ? 'selected' : '' ?> value="email_address">Email Address</option>
											<option <?= $field_info['source_conditions'] == 'birth_date' ? 'selected' : '' ?> value="birth_date">Birth Date</option>
											<option value="CUSTOM_VALUE" <?= $is_custom_value ? 'selected' : '' ?>>Custom Value</option>
										</select>
									</div>
									<div class="clearfix"></div>
									<div class="custom_ref_value" <?= $is_custom_value ? '' : 'style="display:none;"' ?>>
										<label class="col-sm-4 control-label">Custom Value:<br /><em>Enter the field name from contacts database to search.</em></label>
										<div class="col-sm-8">
											<input name="custom_ref_value[]" class="form-control" value="<?= $is_custom_value ? $field_info['source_conditions'] : '' ?>">
										</div>
									</div>
								</div>
								<div class="table_fields" style="display:none;">
									<?php $option_list = mysqli_query($dbc, "SELECT * FROM (SELECT `field_id`, `label`, `totaled` FROM `user_form_fields` WHERE `form_id`='$formid' AND `type`='OPTION' AND `name`='".$field_info['name']."' AND '".$field_info['type']."' IN ('TABLE') AND `deleted`=0 ORDER BY `sort_order`) AS form_fields UNION SELECT 0, '', 0");
									while($option_info = mysqli_fetch_array($option_list)) { ?>
										<div class="col-sm-12">
											<label class="col-sm-4 control-label">Column Value:</label>
											<div class="col-sm-8">
												<input type="hidden" name="option_id[]" value="<?= $option_info['field_id'] ?>">
												<input type="hidden" name="option_name[]" value="<?= $field_info['name'] ?>">
												<div class="col-sm-9"><input type="text" class="form-control" name="option_label[]" onchange="" value="<?= $option_info['label'] ?>"></div>
												<div class="col-sm-2"><input type="checkbox" name="option_totaled[]" <?= $option_info['totaled'] != 1 ? 'checked' : '' ?> value="0" style="display:none;">
													<label><input type="checkbox" onchange="if(this.checked) { $(this).closest('.col-sm-2').find('[name^=option_totaled]').first().removeAttr('checked'); } else { $(this).closest('.col-sm-2').find('[name^=option_totaled]').first().prop('checked','checked'); }" class="form-control" name="option_totaled[]" <?= $option_info['totaled'] == 1 ? 'checked' : '' ?> value="1"> Total<label></div>
												<a href="" onclick="return addOption(this);" class="pull-right"><img src="<?= WEBSITE_URL ?>/img/plus.png"></a>
												<a href="" onclick="return remOption(this);" class="pull-right" style="position: relative; left: -1em;"><img src="<?= WEBSITE_URL ?>/img/remove.png"></a>
											</div>
										</div>
									<?php } ?>
								</div>
								<div class="tableadv_fields" style="display:none;">
									<div class="col-sm-12">
										<label class="col-sm-4 control-label">Table Styling:</label>
										<div class="col-sm-8">
											<input type="text" name="field_styling[]" value="<?= $field_info['styling'] ?>" class="form-control">
										</div>
									</div>
									<div class="col-sm-12">
										<label class="col-sm-4 control-label">Table:<br /><em>Cells with text will display the text and empty cells will create an input box.<br />To style rows enter the styling in [[ and ]] after text (eg. [[colspan="2" style="border: 0px;"]]).<br />To disable a row enter [[disable]].<br />To use a checkbox enter [[checkbox]].</em></label>
										<div class="col-sm-8">
											<table class="table table-bordered">
											<?php $option_list = mysqli_query($dbc, "SELECT `field_id`, `label`, `totaled` FROM `user_form_fields` WHERE `form_id` = '$formid' AND `type` = 'OPTION' AND `name` = '".$field_info['name']."' AND '".$field_info['type']."' IN ('TABLEADV') AND `deleted` = 0 ORDER BY `sort_order`");
											$option_list = mysqli_fetch_all($option_list, MYSQLI_ASSOC); 

											if (count($option_list) < 2) {
												echo '<tr class="hidden-sm hidden-xs">';
												echo '<input type="hidden" name="option_row_id['.$counter.']" class="option_row_id">';
												echo '<input type="hidden" name="option_row_name['.$counter.']" value="'.$field_info['name'].'" class="option_row_name">';
												echo '<th><input type="text" name="option_row_'.$counter.'[]" class="form-control"></th>';
												echo '</tr>';
												$counter++;
												echo '<tr>';
												echo '<input type="hidden" name="option_row_id['.$counter.']" class="option_row_id">';
												echo '<input type="hidden" name="option_row_name['.$counter.']" class="option_row_name" value="'.$field_info['name'].'">';
												echo '<td><input type="text" name="option_row_'.$counter.'[]" class="form-control"></td>';
												echo '</tr>';
												$counter++;
											} else {
												$table_headers = explode('*#*', $option_list[0]['label']);
												echo '<tr class="hidden-sm hidden-xs">';
												echo '<input type="hidden" name="option_row_id['.$counter.']" value="'.$option_list[0]['field_id'].'" class="option_row_id">';
												echo '<input type="hidden" name="option_row_name['.$counter.']" value="'.$field_info['name'].'" class="option_row_name">';
												foreach ($table_headers as $table_header) { ?>
													<th><input type="text" name="option_row_<?= $counter ?>[]" value="<?= $table_header ?>" class="form-control"></th>
												<?php }
												$counter++;
												echo '</tr>';
												for ($i = 1; $i < count($option_list); $i++) {
													$table_row = explode('*#*', $option_list[$i]['label']);
													echo '<tr>';
													echo '<input type="hidden" name="option_row_id['.$counter.']" value="'.$option_list[$i]['field_id'].'" class="option_row_id">';
													echo '<input type="hidden" name="option_row_name['.$counter.']" value="'.$field_info['name'].'" class="option_row_name">';
													foreach ($table_row as $single_cell) { ?>
														<td><input type="text" name="option_row_<?= $counter ?>[]" value="<?= $single_cell ?>" class="form-control"></td>
													<?php }
													$counter++;
													echo '</tr>';
												}
											}
											?>
											</table>
											<div class="pull-right">
												<label class="control-label" style="position:relative; left: -1.5em;">Rows:</label>
													<a href="" onclick="return tableRemRow(this);" style="position: relative; left: -1em;"><img src="<?= WEBSITE_URL ?>/img/remove.png"></a>
													<a href="" onclick="return tableAddRow(this);"><img src="<?= WEBSITE_URL ?>/img/plus.png"></a>
											</div>
											<div class="clearfix"></div>
											<div class="pull-right">
												<label class="control-label" style="position: relative; left: -1.5em;">Columns:</label>
													<a href="" onclick="return tableRemCol(this);" style="position: relative; left: -1em;"><img src="<?= WEBSITE_URL ?>/img/remove.png"></a>
													<a href="" onclick="return tableAddCol(this);"><img src="<?= WEBSITE_URL ?>/img/plus.png"></a>
											</div>
										</div>
									</div>
								</div>
								<div class="text_content" style="display:none;">
									<div class="col-sm-12">
										<label class="col-sm-4 control-label">Content:</label>
										<div class="col-sm-8">
											<textarea id="field_content_<?= $counter ?>" name="field_content[]" class="form-control"><?= html_entity_decode($field_info['content']) ?></textarea>
										</div>
										<button id="add_content_input" class="btn brand-btn pull-right" onclick="addContentInput(this); return false;">Add Input</button>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="mandatory_field">
									<input type="hidden" name="field_mandatory[]" value="<?= $field_info['mandatory'] ?>">
									<div class="col-sm-12">
										<label class="col-sm-4 control-label">Mandatory:</label>
										<div class="col-sm-8">
											<label class="form-checkbox">
												<input type="checkbox" onclick="mandatoryField(this);" value="1" <?= $field_info['mandatory'] == 1 ? 'checked' : '' ?>>
											</label>
										</div>
									</div>
								</div>
								<br />
								<a href="" onclick="return addField(this);" class="pull-right"><img src="<?= WEBSITE_URL ?>/img/plus.png"></a>
								<a href="" onclick="return remField(this);" class="pull-right" style="position: relative; left: -1em;"><img src="<?= WEBSITE_URL ?>/img/remove.png"></a>
							</div>
						<?php $counter++; } ?>
						<input type="hidden" id="counter_num" value="<?= $counter ?>">
					</div>
				</div>
			</div>
		</div>
		
		<span class="popover-examples list-inline pull-left"><a data-toggle="tooltip" data-placement="top" title="Clicking here will discard changes and return you to the dashboard."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
		<a href="?" class="btn brand-btn pull-left">Back</a>
		<button class="btn brand-btn btn-lg pull-right" type="submit" name="submit" value="submit">Submit</button>
	</form>
<?php } ?>