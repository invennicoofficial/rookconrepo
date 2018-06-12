<?php $pages_text = $pdf_settings['pages_text'];
$pages_logo = $pdf_settings['pages_logo'];
$pages_logo_width = $pdf_settings['pages_logo_width'];
$pages_alignment = $pdf_settings['pages_alignment'];
$pages_font_size = $pdf_settings['pages_font_size'];
$pages_font_type = $pdf_settings['pages_font_type'];
$pages_font = $pdf_settings['pages_font'];
$pages_font_colour = $pdf_settings['pages_font_colour'];
$pages_before_content = $pdf_settings['pages_before_content']; ?>
<h3>Additional Page</h3>
<div class="dashboard-item">
	<div class="form-group">
		<label class="col-sm-2 control-label">Page Text/Images:</label>
		<div class="col-sm-10">
			<textarea name="pages_text"><?= html_entity_decode($pages_text) ?></textarea>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Page Font:</label>
		<div class="col-sm-10">
			<div class="col-sm-3">
				<select class="form-control" name="pages_font_size">
					<?php for($i=9;$i<50;$i++): ?>
						<option <?= $i == $pages_font_size ? 'selected' : '' ?> value="<?= $i ?>"><?= $i ?>pt</option>
					<?php endfor; ?>
				</select>
			</div>
			<div class="col-sm-4">
				<?php $font_type_array = array('regular'=>'Regular','bold'=>'Bold','italic'=>'Italic','bold_italic'=>'Bold Italic'); ?>
				<select class="form-control" name="pages_font_type">
					<?php foreach($font_type_array as $font_type_value => $font_types): ?>
						<option <?= $font_type_value == $pages_font_type ? 'selected' : '' ?> value="<?= $font_type_value ?>"><?= $font_types ?></option>
					<?php endforeach; ?>					
				</select>
			</div>
			<div class="col-sm-5">
				<?php $font_array = array('courier'=>'Courier','helvetica'=>'Helvetica','times'=>'Times New Roman','zapfdingbats'=>'Zapf Dingbats','OpenSans'=>'Open Sans','Roboto'=>'Roboto','Encode Sans, sans-serif' => 'Encode Sans, sans-serif', 'Slabo, serif' => 'Slabo', 'Montserrat, sans-serif' => 'Montserrat', 'Raleway, sans-serif' => 'Raleway', 'Merriweather, sans-serif' => 'Merriweather', 'Lora, sans-serif' => 'Lora', 'Nunito, sans-serif' => 'Nunito', 'Karla, sans-serif' => 'Karla');
				ksort($font_array); ?>
				<select name="pages_font" class="form-control">
					<?php foreach($font_array as $font_value => $fonts): ?>
						<option <?= $pages_font == $font_value ? 'selected' : '' ?> value="<?= $font_value ?>"><?= $fonts ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Page Colour:</label>
		<div class="col-sm-10">
			<input type="color" class="form-control" name="pages_font_colour" value="<?= $pages_font_colour ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Page Logo:</label>
		<div class="col-sm-10">
			<input type="file" class="form-control" name="pages_logo">
			<?php if($pages_logo != '' && file_exists('download/'.$pages_logo)): ?>
				<a target="_blank" href="download/<?= $pages_logo ?>"><b>View</b></a>
				<input type="hidden" name="pdf_logo_name" value="<?= $pages_logo ?>">
			<?php endif; ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Logo Alignment:</label>
		<div class="col-sm-10">
			<select class="form-control" name="pages_alignment">
				<option <?= 'left' == $pages_alignment ? 'selected' : '' ?> value="left">Left</option>
				<option <?= 'centre' == $pages_alignment ? 'selected' : '' ?> value="centre">Centre</option>
				<option <?= 'right' == $pages_alignment ? 'selected' : '' ?> value="right">Right</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Logo Width:<br /><em>as a percentage of the page width</em></label>
		<div class="col-sm-10">
			<input type="number" min="0.5" max="90" step="0.5" name="pages_logo_width" class="form-control" value="<?= $pages_logo_width > 0 ? $pages_logo_width : 20 ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Page Placement:</label>
		<div class="col-sm-10">
			<label class="form-checkbox"><input type="radio" name="pages_before_content" <?= $pages_before_content == 2 ? 'checked' : '' ?> value="2"> Before Details</label>
			<label class="form-checkbox"><input type="radio" name="pages_before_content" <?= $pages_before_content == 1 ? 'checked' : '' ?> value="1"> After Details</label>
			<label class="form-checkbox"><input type="radio" name="pages_before_content" <?= $pages_before_content > 0 ? '' : 'checked' ?> value="0"> No Page Added</label>
		</div>
	</div>
</div>