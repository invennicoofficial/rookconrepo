<?php $footer_text = $pdf_settings['footer_text'];
$footer_logo = $pdf_settings['footer_logo'];
$footer_alignment = $pdf_settings['footer_alignment'];
$footer_font_size = $pdf_settings['footer_font_size'];
$footer_font_type = $pdf_settings['footer_font_type'];
$footer_font = $pdf_settings['footer_font'];
$footer_font_colour = $pdf_settings['footer_font_colour']; ?>
<h3>Footer Settings</h3>
<div class="dashboard-item">
	<div class="form-group">
		<label class="col-sm-4">Footer Text:</label>
		<div class="col-sm-8">
			<textarea name="footer_text"><?= html_entity_decode($footer_text) ?></textarea>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4">Footer Font:</label>
		<div class="col-sm-8">
			<div class="col-sm-3">
				<select class="form-control" name="footer_font_size">
					<?php for($i=9;$i<50;$i++): ?>
						<option <?= $i == $footer_font_size ? 'selected' : '' ?> value="<?= $i ?>"><?= $i ?>pt</option>
					<?php endfor; ?>
				</select>
			</div>
			<div class="col-sm-4">
				<?php $font_type_array = array('regular'=>'Regular','bold'=>'Bold','italic'=>'Italic','bold_italic'=>'Bold Italic'); ?>
				<select class="form-control" name="footer_font_type">
					<?php foreach($font_type_array as $font_type_value => $font_types): ?>
						<option <?= $font_type_value == $footer_font_type ? 'selected' : '' ?> value="<?= $font_type_value ?>"><?= $font_types ?></option>
					<?php endforeach; ?>					
				</select>
			</div>
			<div class="col-sm-5">
				<?php $font_array = array('courier'=>'Courier','helvetica'=>'Helvetica','times'=>'Times New Roman','zapfdingbats'=>'Zapf Dingbats','OpenSans'=>'Open Sans','Roboto'=>'Roboto','Encode Sans, sans-serif' => 'Encode Sans, sans-serif', 'Slabo, serif' => 'Slabo', 'Montserrat, sans-serif' => 'Montserrat', 'Raleway, sans-serif' => 'Raleway', 'Merriweather, sans-serif' => 'Merriweather', 'Lora, sans-serif' => 'Lora', 'Nunito, sans-serif' => 'Nunito', 'Karla, sans-serif' => 'Karla');
				ksort($font_array); ?>
				<select name="footer_font" class="form-control">
					<?php foreach($font_array as $font_value => $fonts): ?>
						<option <?= $footer_font == $font_value ? 'selected' : '' ?> value="<?= $font_value ?>"><?= $fonts ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4">Footer Colour:</label>
		<div class="col-sm-8">
			<input type="color" class="form-control" name="footer_font_colour" value="<?= $footer_font_colour ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4">Footer Logo:</label>
		<div class="col-sm-8">
			<input type="file" class="form-control" name="footer_logo">
			<?php if($footer_logo != '' && file_exists('download/'.$footer_logo)): ?>
				<a target="_blank" href="download/<?= $footer_logo ?>"><b><?= $footer_logo ?></b></a> - <a href="" onclick="$('[name=pdf_logo_name]').val(''); return false;">Delete</a>
				<input type="hidden" name="pdf_logo_name" value="<?= $footer_logo ?>">
			<?php endif; ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4">Logo Alignment:</label>
		<div class="col-sm-8">
			<select class="form-control" name="footer_alignment">
				<option <?= 'left' == $footer_alignment ? 'selected' : '' ?> value="left">Left</option>
				<option <?= 'centre' == $footer_alignment ? 'selected' : '' ?> value="centre">Centre</option>
				<option <?= 'right' == $footer_alignment ? 'selected' : '' ?> value="right">Right</option>
			</select>
		</div>
	</div>
</div>
<script>
$(document).ready(function() {
	$('input,select,textarea,hidden').change(function() {
		if(target != undefined) {
			switch(this.name) {
			}
		}
	});
});
</script>