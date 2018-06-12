<?php if(!empty($_POST['submit'])) {
	mysqli_query($dbc, "INSERT INTO `field_config_user_forms` (`default_header`) SELECT '' FROM (SELECT COUNT(*) rows FROM `field_config_user_forms`) num WHERE num.rows=0");
	// if (!file_exists('fonts')) {
	// 	mkdir('fonts', 0777, true);
	// }
	// foreach($_FILES['upload_fonts']['name'] as $i => $fontname) {
	// 	if($fontname != '') {
	// 		$fontname = $basename = str_replace(' ','_',preg_replace('/[^A-Za-z0-9. ]*/','',$fontname));
	// 		$j = 0;
	// 		while(file_exists('fonts/'.$fontname)) {
	// 			$fontname = preg_replace('/(\.[A-Za-z0-9_]*)/', '_'.++$j.'$1', $basename);
	// 		}
	// 		move_uploaded_file($_FILES['upload_fonts']['tmp_name'][$i], 'fonts/'.$fontname);
	// 	}
	// }
	if (!file_exists('download')) {
		mkdir('download', 0777, true);
	}
	if(!empty($_FILES['default_head_logo']['name'])) {
		$default_head_logo = $basename = preg_replace('/[^a-z0-9.]*/','',strtolower($_FILES['default_head_logo']['name']));
		$j = 0;
		while(file_exists('download/'.$default_head_logo)) {
			$default_head_logo = preg_replace('/(\.[a-z0-9]*)/', ' ('.++$j.')$1', $basename);
		}
		move_uploaded_file($_FILES['default_head_logo']['tmp_name'], 'download/'.$default_head_logo);
		mysqli_query($dbc, "UPDATE `field_config_user_forms` SET `default_head_logo` = '$default_head_logo'");
	}
	// if(!empty($_FILES['req_head_logo']['name'])) {
	// 	$req_head_logo = $basename = preg_replace('/[^a-z0-9.]*/','',strtolower($_FILES['req_head_logo']['name']));
	// 	$j = 0;
	// 	while(file_exists('download/'.$req_head_logo)) {
	// 		$req_head_logo = preg_replace('/(\.[a-z0-9]*)/', ' ('.++$j.')$1', $basename);
	// 	}
	// 	move_uploaded_file($_FILES['req_head_logo']['tmp_name'], 'download/'.$req_head_logo);
	// 	mysqli_query($dbc, "UPDATE `field_config_user_forms` SET `req_head_logo` = '$req_head_logo'");
	// }
	if(!empty($_FILES['default_foot_logo']['name'])) {
		$default_foot_logo = $basename = preg_replace('/[^a-z0-9.]*/','',strtolower($_FILES['default_foot_logo']['name']));
		$j = 0;
		while(file_exists('download/'.$default_foot_logo)) {
			$default_foot_logo = preg_replace('/(\.[a-z0-9]*)/', ' ('.++$j.')$1', $basename);
		}
		move_uploaded_file($_FILES['default_foot_logo']['tmp_name'], 'download/'.$default_foot_logo);
		mysqli_query($dbc, "UPDATE `field_config_user_forms` SET `default_foot_logo` = '$default_foot_logo'");
	}
	// if(!empty($_FILES['req_foot_logo']['name'])) {
	// 	$req_foot_logo = $basename = preg_replace('/[^a-z0-9.]*/','',strtolower($_FILES['req_foot_logo']['name']));
	// 	$j = 0;
	// 	while(file_exists('download/'.$req_foot_logo)) {
	// 		$req_foot_logo = preg_replace('/(\.[a-z0-9]*)/', ' ('.++$j.')$1', $basename);
	// 	}
	// 	move_uploaded_file($_FILES['req_foot_logo']['tmp_name'], 'download/'.$req_foot_logo);
	// 	mysqli_query($dbc, "UPDATE `field_config_user_forms` SET `req_foot_logo` = '$req_foot_logo'");
	// }

	$subtabs = filter_var(htmlentities($_POST['subtabs']),FILTER_SANITIZE_STRING);
	$use_templates = filter_var(htmlentities($_POST['use_templates']),FILTER_SANITIZE_STRING);
	
	$default_header = filter_var(htmlentities($_POST['default_header']),FILTER_SANITIZE_STRING);
	$default_head_align = filter_var(htmlentities($_POST['default_head_align']),FILTER_SANITIZE_STRING);
	$default_head_font = filter_var(htmlentities($_POST['default_head_font']),FILTER_SANITIZE_STRING);
	$default_head_size = filter_var(htmlentities($_POST['default_head_size']),FILTER_SANITIZE_STRING);
	$default_head_color = filter_var(htmlentities($_POST['default_head_color']),FILTER_SANITIZE_STRING);
	$default_head_styling = filter_var(htmlentities(implode(',',$_POST['default_head_styling'])),FILTER_SANITIZE_STRING);
	$req_header = filter_var(htmlentities($_POST['req_header']),FILTER_SANITIZE_STRING);

	$default_footer = filter_var(htmlentities($_POST['default_footer']),FILTER_SANITIZE_STRING);
	$default_foot_align = filter_var(htmlentities($_POST['default_head_align']),FILTER_SANITIZE_STRING);
	$default_foot_font = filter_var(htmlentities($_POST['default_foot_font']),FILTER_SANITIZE_STRING);
	$default_foot_size = filter_var(htmlentities($_POST['default_foot_size']),FILTER_SANITIZE_STRING);
	$default_foot_color = filter_var(htmlentities($_POST['default_foot_color']),FILTER_SANITIZE_STRING);
	$default_foot_styling = filter_var(htmlentities(implode(',',$_POST['default_foot_styling'])),FILTER_SANITIZE_STRING);
	$req_footer = filter_var(htmlentities($_POST['req_footer']),FILTER_SANITIZE_STRING);

	$default_section_heading_font = filter_var(htmlentities($_POST['default_section_heading_font']),FILTER_SANITIZE_STRING);
	$default_section_heading_size = filter_var(htmlentities($_POST['default_section_heading_size']),FILTER_SANITIZE_STRING);
	$default_section_heading_color = filter_var(htmlentities($_POST['default_section_heading_color']),FILTER_SANITIZE_STRING);
	$default_section_heading_styling = filter_var(htmlentities(implode(',',$_POST['default_section_heading_styling'])),FILTER_SANITIZE_STRING);
	$default_body_heading_font = filter_var(htmlentities($_POST['default_body_heading_font']),FILTER_SANITIZE_STRING);
	$default_body_heading_size = filter_var(htmlentities($_POST['default_body_heading_size']),FILTER_SANITIZE_STRING);
	$default_body_heading_color = filter_var(htmlentities($_POST['default_body_heading_color']),FILTER_SANITIZE_STRING);
	$default_body_heading_styling = filter_var(htmlentities(implode(',',$_POST['default_body_heading_styling'])),FILTER_SANITIZE_STRING);
	$default_font = filter_var($_POST['default_font'],FILTER_SANITIZE_STRING);
	$default_body_size = filter_var(htmlentities($_POST['default_body_size']),FILTER_SANITIZE_STRING);
	$default_body_color = filter_var(htmlentities($_POST['default_body_color']),FILTER_SANITIZE_STRING);
	$default_body_styling = filter_var(htmlentities(implode(',',$_POST['default_body_styling'])),FILTER_SANITIZE_STRING);

	$default_page_format = filter_var(htmlentities($_POST['default_page_format']),FILTER_SANITIZE_STRING);

	mysqli_query($dbc, "UPDATE `field_config_user_forms` SET `subtabs`='$subtabs', `use_templates`='$use_templates', `default_header`='$default_header', `default_head_align`='$default_head_align', `default_head_font`='$default_head_font', `default_head_size`='$default_head_size', `default_head_color`='$default_head_color', `req_header`='$req_header', `default_footer`='$default_footer', `default_foot_align`='$default_foot_align', `default_foot_font`='$default_foot_font', `default_foot_size`='$default_foot_size', `default_foot_color`='$default_foot_color', `req_footer`='$req_footer', `default_section_heading_font`='$default_section_heading_font', `default_section_heading_size`='$default_section_heading_size', `default_section_heading_color`='$default_section_heading_color', `default_body_heading_font`='$default_body_heading_font', `default_body_heading_size`='$default_body_heading_size', `default_body_heading_color`='$default_body_heading_color', `default_font`='$default_font', `default_body_size` = '$default_body_size', `default_body_color` = '$default_body_color', `default_page_format` = '$default_page_format', `default_head_styling` = '$default_head_styling', `default_foot_styling` = '$default_foot_styling', `default_section_heading_styling` = '$default_section_heading_styling', `default_body_heading_styling` = '$default_body_heading_styling', `default_body_styling` = '$default_body_styling'");
	
	echo "<script> location.replace('?'); </script>";
} else {
	$form_config = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_user_forms`"));
	$font_array = array('courier'=>'Courier','helvetica'=>'Helvetica','times'=>'Times New Roman','zapfdingbats'=>'Zapf Dingbats','OpenSans'=>'Open Sans','Roboto'=>'Roboto','Encode Sans, sans-serif' => 'Encode Sans, sans-serif', 'Slabo, serif' => 'Slabo', 'Montserrat, sans-serif' => 'Montserrat', 'Raleway, sans-serif' => 'Raleway', 'Merriweather, sans-serif' => 'Merriweather', 'Lora, sans-serif' => 'Lora', 'Nunito, sans-serif' => 'Nunito', 'Karla, sans-serif' => 'Karla');
	ksort($font_array); ?>
	<script type="text/javascript">
	function colorCodeChange(sel) {
	    $(sel).closest('.form-group').find('[name$="color"]').val(sel.value);
	}
	function deleteLogo(logo) {
		if(confirm('Are you sure you want to delete this logo?')) {
			var formid = $('#formid').val();
			$.ajax({
				url: '../Form Builder/form_ajax.php?fill=delete_logo',
				type: 'POST',
				data: { formid: formid, logo: logo, type: 'config' },
				success: function(response) {
					console.log(response);
					if(logo == 'header') {
						$('.header_logo_url').html('');
					} else if(logo == 'footer') {
						$('.footer_logo_url').html('');
					}
				}
			});
		}
	}
	</script>
	<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
		<div class="panel-group" id="accordion">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_dashboard" >
                            Dashboard Settings<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_dashboard" class="panel-collapse collapse">
                    <div class="panel-body">
						<div class="form-group">
							<label class="col-sm-4 control-label">Subtabs:<br><i>(Enter Subtabs separated by a comma.)</i></label>
							<div class="col-sm-8">
								<input type="text" name="subtabs" value="<?= $form_config['subtabs'] ?>" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Use Templates:</label>
							<div class="col-sm-8">
								<label for="form-checkbox">
									<input type="checkbox" name="use_templates" value="1" <?= $form_config['use_templates'] == 1 ? 'checked' : '' ?> class="form-control">
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_header" >
                            Form Header Settings<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_header" class="panel-collapse collapse">
                    <div class="panel-body">
						<div class="form-group">
							<label class="col-sm-4 control-label">Default Header Logo:</label>
							<div class="col-sm-8">
								<div class="header_logo_url">
									<?php if(!empty($form_config['default_head_logo']) && file_exists('download/'.$form_config['default_head_logo'])) { ?>
										<a href="download/<?= $form_config['default_head_logo'] ?>" target="_blank">View</a> | <a href="" onclick="deleteLogo('header'); return false;">Delete</a>
									<?php } ?>
								</div>
								<input name="default_head_logo" type="file" data-filename-placement="inside" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Default Header Text:</label>
							<div class="col-sm-8">
								<textarea name="default_header"><?= html_entity_decode($form_config['default_header']) ?></textarea>
							</div>
						</div>
						<!-- <div class="form-group">
							<label class="col-sm-4 control-label">Required Header Logo:</label>
							<div class="col-sm-8">
								<?php if(!empty($form_config['req_head_logo']) && file_exists('download/'.$form_config['req_head_logo'])) { ?>
									<img src="download/<?= $form_config['req_head_logo'] ?>" height="30px;">
								<?php } ?>
								<input name="req_head_logo" type="file" data-filename-placement="inside" class="form-control" />
							</div>
						</div> -->
						<div class="form-group">
							<label class="col-sm-4 control-label">Default Header Align:</label>
							<div class="col-sm-8">
			                    <select name="default_head_align" class="chosen-select-deselect form-control">
			                        <option></option>
			                        <option <?= $form_config['default_head_align'] == 'L' ? 'selected' : '' ?> value="L">Left</option>
			                        <option <?= $form_config['default_head_align'] == 'C' ? 'selected' : '' ?> value="C">Center</option>
			                        <option <?= $form_config['default_head_align'] == 'R' ? 'selected' : '' ?> value="R">Right</option>
			                    </select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Default Header Font:</label>
							<div class="col-sm-8">
			                    <select name="default_head_font" class="chosen-select-deselect form-control">
			                        <option></option>
			                        <?php 
			                            foreach($font_array as $font_value => $font) { ?>
			                                <option <?= $form_config['default_head_font'] == $font_value ? 'selected' : '' ?> value="<?= $font_value ?>"><?= $font ?></option>
			                            <?php }
			                        ?>
			                    </select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Default Header Size:</label>
							<div class="col-sm-8">
			                    <select name="default_head_size" class="chosen-select-deselect form-control">
			                        <option></option>
			                        <?php for($i = 6; $i < 50; $i++) { ?>
			                            <option <?= $form_config['default_head_size'] == $i ? 'selected' : '' ?> value="<?= $i ?>"><?= $i ?>pt</option>
			                        <?php } ?>
			                    </select>
							</div>
						</div>
			            <div class="form-group">
			                <label for="default_head_color" class="col-sm-4 control-label">Default Header Color:</label>
			                <div class="col-sm-1">
			                    <input type="color" name="default_head_color_picker" value="<?= $form_config['default_head_color'] ?>" class="form-control" onchange="colorCodeChange(this);">
			                </div>
			                <div class="col-sm-7">
			                    <input type="text" name="default_head_color" value="<?= $form_config['default_head_color'] ?>" class="form-control">
			                </div>
			            </div>
			            <div class="form-group">
							<label class="col-sm-4 control-label">Default Header Styling:</label>
							<div class="col-sm-8">
								<label class="form-checkbox"><input type="checkbox" name="default_head_styling[]" value="Bold" <?= strpos(','.$form_config['default_head_styling'].',', ',Bold,') !== FALSE ? 'checked' : '' ?>> Bold</label>
								<label class="form-checkbox"><input type="checkbox" name="default_head_styling[]" value="Underline" <?= strpos(','.$form_config['default_head_styling'].',', ',Underline,') !== FALSE ? 'checked' : '' ?>> Underline</label>
								<label class="form-checkbox"><input type="checkbox" name="default_head_styling[]" value="Italic" <?= strpos(','.$form_config['default_head_styling'].',', ',Italic,') !== FALSE ? 'checked' : '' ?>> Italic</label>
							</div>
			            </div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Required Header Text:<br /><em>This will appear on all forms.</em></label>
							<div class="col-sm-8">
								<textarea name="req_header"><?= html_entity_decode($form_config['req_header']) ?></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_footer" >
                            Form Footer Settings<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_footer" class="panel-collapse collapse">
                    <div class="panel-body">
						<div class="form-group">
							<label class="col-sm-4 control-label">Default Footer Logo:</label>
							<div class="col-sm-8">
								<div class="footer_logo_url">
									<?php if(!empty($form_config['default_foot_logo']) && file_exists('download/'.$form_config['default_foot_logo'])) { ?>
										<a href="download/<?= $form_config['default_foot_logo'] ?>" target="_blank">View</a> | <a href="" onclick="deleteLogo('footer'); return false;">Delete</a>
									<?php } ?>
								</div>
								<input name="default_foot_logo" type="file" data-filename-placement="inside" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Default Footer Text:</label>
							<div class="col-sm-8">
								<textarea name="default_footer"><?= html_entity_decode($form_config['default_footer']) ?></textarea>
							</div>
						</div>
						<!-- <div class="form-group">
							<label class="col-sm-4 control-label">Required Footer Logo:</label>
							<div class="col-sm-8">
								<?php if(!empty($form_config['req_foot_logo']) && file_exists('download/'.$form_config['req_foot_logo'])) { ?>
									<img src="download/<?= $form_config['req_foot_logo'] ?>" height="30px;">
								<?php } ?>
								<input name="req_foot_logo" type="file" data-filename-placement="inside" class="form-control" />
							</div>
						</div> -->
						<div class="form-group">
							<label class="col-sm-4 control-label">Default Footer Align:</label>
							<div class="col-sm-8">
			                    <select name="default_foot_align" class="chosen-select-deselect form-control">
			                        <option></option>
			                        <option <?= $form_config['default_foot_align'] == 'L' ? 'selected' : '' ?> value="L">Left</option>
			                        <option <?= $form_config['default_foot_align'] == 'C' ? 'selected' : '' ?> value="C">Center</option>
			                        <option <?= $form_config['default_foot_align'] == 'R' ? 'selected' : '' ?> value="R">Right</option>
			                    </select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Default Footer Font:</label>
							<div class="col-sm-8">
			                    <select name="default_foot_font" class="chosen-select-deselect form-control">
			                        <option></option>
			                        <?php 
			                            foreach($font_array as $font_value => $font) { ?>
			                                <option <?= $form_config['default_foot_font'] == $font_value ? 'selected' : '' ?> value="<?= $font_value ?>"><?= $font ?></option>
			                            <?php }
			                        ?>
			                    </select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Default Footer Size:</label>
							<div class="col-sm-8">
			                    <select name="default_foot_size" class="chosen-select-deselect form-control">
			                        <option></option>
			                        <?php for($i = 6; $i < 50; $i++) { ?>
			                            <option <?= $form_config['default_foot_size'] == $i ? 'selected' : '' ?> value="<?= $i ?>"><?= $i ?>pt</option>
			                        <?php } ?>
			                    </select>
							</div>
						</div>
			            <div class="form-group">
			                <label for="default_foot_color" class="col-sm-4 control-label">Default Footer Color:</label>
			                <div class="col-sm-1">
			                    <input type="color" name="default_foot_color_picker" value="<?= $form_config['default_foot_color'] ?>" class="form-control" onchange="colorCodeChange(this);">
			                </div>
			                <div class="col-sm-7">
			                    <input type="text" name="default_foot_color" value="<?= $form_config['default_foot_color'] ?>" class="form-control">
			                </div>
			            </div>
			            <div class="form-group">
							<label class="col-sm-4 control-label">Default Footer Styling:</label>
							<div class="col-sm-8">
								<label class="form-checkbox"><input type="checkbox" name="default_foot_styling[]" value="Bold" <?= strpos(','.$form_config['default_foot_styling'].',', ',Bold,') !== FALSE ? 'checked' : '' ?>> Bold</label>
								<label class="form-checkbox"><input type="checkbox" name="default_foot_styling[]" value="Underline" <?= strpos(','.$form_config['default_foot_styling'].',', ',Underline,') !== FALSE ? 'checked' : '' ?>> Underline</label>
								<label class="form-checkbox"><input type="checkbox" name="default_foot_styling[]" value="Italic" <?= strpos(','.$form_config['default_foot_styling'].',', ',Italic,') !== FALSE ? 'checked' : '' ?>> Italic</label>
							</div>
			            </div>
			            <div class="form-group">
			            	<label for="default_page_format" class="col-sm-4 control-label">Default Page Number Format:<br><em>Enter how you want Page Numbers to appear. You can enter [[CURRENT_PAGE]], [[TOTAL_PAGE]].</em></label>
			            	<div class="col-sm-8">
			            		<input type="text" name="default_page_format" value="<?= $form_config['default_page_format'] ?>" class="form-control">
			            	</div>
			            </div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Required Footer Text:<br /><em>This will appear on all forms.</em></label>
							<div class="col-sm-8">
								<textarea name="req_footer"><?= html_entity_decode($form_config['req_footer']) ?></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_style" >
                            Form Body Settings<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_style" class="panel-collapse collapse">
                    <div class="panel-body">
						<div class="form-group">
							<label class="col-sm-4 control-label">Default Section Heading Font:</label>
							<div class="col-sm-8">
			                    <select name="default_section_heading_font" class="chosen-select-deselect form-control">
			                        <option></option>
			                        <?php 
			                            foreach($font_array as $font_value => $font) { ?>
			                                <option <?= $form_config['default_section_heading_font'] == $font_value ? 'selected' : '' ?> value="<?= $font_value ?>"><?= $font ?></option>
			                            <?php }
			                        ?>
			                    </select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Default Section Heading Size:</label>
							<div class="col-sm-8">
			                    <select name="default_section_heading_size" class="chosen-select-deselect form-control">
			                        <option></option>
			                        <?php for($i = 6; $i < 50; $i++) { ?>
			                            <option <?= $form_config['default_section_heading_size'] == $i ? 'selected' : '' ?> value="<?= $i ?>"><?= $i ?>pt</option>
			                        <?php } ?>
			                    </select>
							</div>
						</div>
			            <div class="form-group">
			                <label for="section_heading_color" class="col-sm-4 control-label">Default Section Heading Color:</label>
			                <div class="col-sm-1">
			                    <input type="color" name="default_section_heading_color_picker" value="<?= $form_config['default_section_heading_color'] ?>" class="form-control" onchange="colorCodeChange(this);">
			                </div>
			                <div class="col-sm-7">
			                    <input type="text" name="default_section_heading_color" value="<?= $form_config['default_section_heading_color'] ?>" class="form-control">
			                </div>
			            </div>
			            <div class="form-group">
							<label class="col-sm-4 control-label">Default Section Heading Styling:</label>
							<div class="col-sm-8">
								<label class="form-checkbox"><input type="checkbox" name="default_section_heading_styling[]" value="Bold" <?= strpos(','.$form_config['default_section_heading_styling'].',', ',Bold,') !== FALSE ? 'checked' : '' ?>> Bold</label>
								<label class="form-checkbox"><input type="checkbox" name="default_section_heading_styling[]" value="Underline" <?= strpos(','.$form_config['default_section_heading_styling'].',', ',Underline,') !== FALSE ? 'checked' : '' ?>> Underline</label>
								<label class="form-checkbox"><input type="checkbox" name="default_section_heading_styling[]" value="Italic" <?= strpos(','.$form_config['default_section_heading_styling'].',', ',Italic,') !== FALSE ? 'checked' : '' ?>> Italic</label>
							</div>
			            </div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Default Body Heading Font:</label>
							<div class="col-sm-8">
			                    <select name="default_body_heading_font" class="chosen-select-deselect form-control">
			                        <option></option>
			                        <?php 
			                            foreach($font_array as $font_value => $font) { ?>
			                                <option <?= $form_config['default_body_heading_font'] == $font_value ? 'selected' : '' ?> value="<?= $font_value ?>"><?= $font ?></option>
			                            <?php }
			                        ?>
			                    </select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Default Body Heading Size:</label>
							<div class="col-sm-8">
			                    <select name="default_body_heading_size" class="chosen-select-deselect form-control">
			                        <option></option>
			                        <?php for($i = 6; $i < 50; $i++) { ?>
			                            <option <?= $form_config['default_body_heading_size'] == $i ? 'selected' : '' ?> value="<?= $i ?>"><?= $i ?>pt</option>
			                        <?php } ?>
			                    </select>
							</div>
						</div>
			            <div class="form-group">
			                <label for="body_heading_color" class="col-sm-4 control-label">Default Body Heading Color:</label>
			                <div class="col-sm-1">
			                    <input type="color" name="default_body_heading_color_picker" value="<?= $form_config['default_body_heading_color'] ?>" class="form-control" onchange="colorCodeChange(this);">
			                </div>
			                <div class="col-sm-7">
			                    <input type="text" name="default_body_heading_color" value="<?= $form_config['default_body_heading_color'] ?>" class="form-control">
			                </div>
			            </div>
			            <div class="form-group">
							<label class="col-sm-4 control-label">Default Body Heading Styling:</label>
							<div class="col-sm-8">
								<label class="form-checkbox"><input type="checkbox" name="default_body_heading_styling[]" value="Bold" <?= strpos(','.$form_config['default_body_heading_styling'].',', ',Bold,') !== FALSE ? 'checked' : '' ?>> Bold</label>
								<label class="form-checkbox"><input type="checkbox" name="default_body_heading_styling[]" value="Underline" <?= strpos(','.$form_config['default_body_heading_styling'].',', ',Underline,') !== FALSE ? 'checked' : '' ?>> Underline</label>
								<label class="form-checkbox"><input type="checkbox" name="default_body_heading_styling[]" value="Italic" <?= strpos(','.$form_config['default_body_heading_styling'].',', ',Italic,') !== FALSE ? 'checked' : '' ?>> Italic</label>
							</div>
			            </div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Default Body Font:</label>
							<div class="col-sm-8">
			                    <select name="default_font" class="chosen-select-deselect form-control">
			                        <option></option>
			                        <?php 
			                            foreach($font_array as $font_value => $font) { ?>
			                                <option <?= $form_config['default_font'] == $font_value ? 'selected' : '' ?> value="<?= $font_value ?>"><?= $font ?></option>
			                            <?php }
			                        ?>
			                    </select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Default Body Size:</label>
							<div class="col-sm-8">
			                    <select name="default_body_size" class="chosen-select-deselect form-control">
			                        <option></option>
			                        <?php for($i = 6; $i < 50; $i++) { ?>
			                            <option <?= $form_config['default_body_size'] == $i ? 'selected' : '' ?> value="<?= $i ?>"><?= $i ?>pt</option>
			                        <?php } ?>
			                    </select>
							</div>
						</div>
			            <div class="form-group">
			                <label for="default_body_color" class="col-sm-4 control-label">Default Body Color:</label>
			                <div class="col-sm-1">
			                    <input type="color" name="default_body_color_picker" value="<?= $form_config['default_body_color'] ?>" class="form-control" onchange="colorCodeChange(this);">
			                </div>
			                <div class="col-sm-7">
			                    <input type="text" name="default_body_color" value="<?= $form_config['default_body_color'] ?>" class="form-control">
			                </div>
			            </div>
			            <div class="form-group">
							<label class="col-sm-4 control-label">Default Body Styling:</label>
							<div class="col-sm-8">
								<label class="form-checkbox"><input type="checkbox" name="default_body_styling[]" value="Bold" <?= strpos(','.$form_config['default_body_styling'].',', ',Bold,') !== FALSE ? 'checked' : '' ?>> Bold</label>
								<label class="form-checkbox"><input type="checkbox" name="default_body_styling[]" value="Underline" <?= strpos(','.$form_config['default_body_styling'].',', ',Underline,') !== FALSE ? 'checked' : '' ?>> Underline</label>
								<label class="form-checkbox"><input type="checkbox" name="default_body_styling[]" value="Italic" <?= strpos(','.$form_config['default_body_styling'].',', ',Italic,') !== FALSE ? 'checked' : '' ?>> Italic</label>
							</div>
			            </div>
					</div>
				</div>
			</div>
		</div>
		<span class="popover-examples list-inline pull-left"><a data-toggle="tooltip" data-placement="top" title="Clicking here will discard changes and return you to the dashboard."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
		<a href="?" class="btn brand-btn pull-left">Back</a>
		<button class="btn brand-btn btn-lg pull-right" type="submit" name="submit" value="submit">Submit</button>
	</form>
<?php } ?>