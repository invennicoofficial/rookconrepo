<?php $cover_text = $pdf_settings['cover_text'];
$cover_text_alignment = $pdf_settings['cover_text_alignment'];
$cover_logo = $pdf_settings['cover_logo'];
$cover_logo_height = $pdf_settings['cover_logo_height'];
$cover_alignment = $pdf_settings['cover_alignment'];
$cover_font_size = $pdf_settings['cover_font_size'];
$cover_font_type = $pdf_settings['cover_font_type'];
$cover_font = $pdf_settings['cover_font'];
$cover_font_colour = $pdf_settings['cover_font_colour']; ?>
<h3>Cover Page</h3>
<div class="dashboard-item">
	<div class="form-group">
		<label class="col-sm-3 control-label">Cover Page Text:<br /><small>The following text will be replaced in the final document as follows:<br />[RECIPIENT]: <?= ESTIMATE_TILE ?> Recipient's Name<br />[CREATED]: Date <?= ESTIMATE_TILE ?> Created<br />[EXPIRY]: <?= ESTIMATE_TILE ?> Expiry Date</small></label>
		<div class="col-sm-9">
			<textarea name="cover_text"><?= html_entity_decode($cover_text) ?></textarea>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Cover Page Font:</label>
		<div class="col-sm-9">
			<div class="col-sm-3">
				<select class="form-control" name="cover_font_size">
					<?php for($i=9;$i<50;$i++): ?>
						<option <?= $i == $cover_font_size ? 'selected' : '' ?> value="<?= $i ?>"><?= $i ?>pt</option>
					<?php endfor; ?>
				</select>
			</div>
			<div class="col-sm-4">
				<?php $font_type_array = array('regular'=>'Regular','bold'=>'Bold','italic'=>'Italic','bold_italic'=>'Bold Italic'); ?>
				<select class="form-control" name="cover_font_type">
					<?php foreach($font_type_array as $font_type_value => $font_types): ?>
						<option <?= $font_type_value == $cover_font_type ? 'selected' : '' ?> value="<?= $font_type_value ?>"><?= $font_types ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-sm-5">
				<?php $font_array = array('courier'=>'Courier','helvetica'=>'Helvetica','times'=>'Times New Roman','zapfdingbats'=>'Zapf Dingbats','OpenSans'=>'Open Sans','Roboto'=>'Roboto','Encode Sans, sans-serif' => 'Encode Sans, sans-serif', 'Slabo, serif' => 'Slabo', 'Montserrat, sans-serif' => 'Montserrat', 'Raleway, sans-serif' => 'Raleway', 'Merriweather, sans-serif' => 'Merriweather', 'Lora, sans-serif' => 'Lora', 'Nunito, sans-serif' => 'Nunito', 'Karla, sans-serif' => 'Karla');
				ksort($font_array); ?>
				<select name="cover_font" class="form-control">
					<?php foreach($font_array as $font_value => $fonts): ?>
						<option <?= $cover_font == $font_value ? 'selected' : '' ?> value="<?= $font_value ?>"><?= $fonts ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Text Alignment:</label>
		<div class="col-sm-9">
			<select class="form-control" name="cover_text_alignment">
				<option <?= 'top' == $cover_text_alignment ? 'selected' : '' ?> value="top">Top</option>
				<option <?= 'middle' == $cover_text_alignment || $cover_text_alignment == '' ? 'selected' : '' ?> value="middle">Middle</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Cover Page Colour:</label>
		<div class="col-sm-9">
			<input type="color" class="form-control" name="cover_font_colour" value="<?= $cover_font_colour ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Cover Page Logo:</label>
		<div class="col-sm-9">
			<input type="file" class="form-control" name="cover_logo">
			<?php if($cover_logo != '' && file_exists('download/'.$cover_logo)): ?>
				<a target="_blank" href="download/<?= $cover_logo ?>"><b><?= $cover_logo ?></b></a> - <a href="" onclick="$('[name=cover_logo_name]').val(''); return false;">Delete</a>
				<input type="hidden" name="cover_logo_name" value="<?= $cover_logo ?>">
			<?php endif; ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Logo Alignment:</label>
		<div class="col-sm-9">
			<select class="form-control" name="cover_alignment">
				<option <?= 'left' == $cover_alignment ? 'selected' : '' ?> value="left">Top Left</option>
				<option <?= 'centre' == $cover_alignment ? 'selected' : '' ?> value="centre">Top Centre</option>
				<option <?= 'right' == $cover_alignment ? 'selected' : '' ?> value="right">Top Right</option>
				<option <?= 'mid_left' == $cover_alignment ? 'selected' : '' ?> value="mid_left">Middle Left</option>
				<option <?= 'mid_centre' == $cover_alignment ? 'selected' : '' ?> value="mid_centre">Middle Centre</option>
				<option <?= 'mid_right' == $cover_alignment ? 'selected' : '' ?> value="mid_right">Middle Right</option>
				<option <?= 'bot_left' == $cover_alignment ? 'selected' : '' ?> value="bot_left">Bottom Left</option>
				<option <?= 'bot_centre' == $cover_alignment ? 'selected' : '' ?> value="bot_centre">Bottom Centre</option>
				<option <?= 'bot_right' == $cover_alignment ? 'selected' : '' ?> value="bot_right">Bottom Right</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Logo Width:<br /><em>as a percentage of the page width</em></label>
		<div class="col-sm-9">
			<input type="number" min="0.5" max="90" step="0.5" name="cover_logo_height" class="form-control" value="<?= $cover_logo_height > 0 ? $cover_logo_height : 20 ?>">
		</div>
	</div>
</div>