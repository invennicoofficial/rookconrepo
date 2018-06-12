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
		<label class="col-sm-4">Cover Page Text:<br /><small>[USER]: Estimate Creator's Name<br />[RECIPIENT]: Estimate Recipient's Name<br />[CREATED]: Date Estimate Created<br />[EXPIRY]: Estimate Expiry Date</small></label>
		<div class="col-sm-8">
			<textarea name="cover_text"><?= html_entity_decode($cover_text) ?></textarea>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4">Cover Page Font:</label>
		<div class="col-sm-8">
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
		<label class="col-sm-4">Text Alignment:</label>
		<div class="col-sm-8">
			<select class="form-control" name="cover_text_alignment">
				<option <?= 'top' == $cover_text_alignment ? 'selected' : '' ?> value="top">Top</option>
				<option <?= 'middle' == $cover_text_alignment || $cover_text_alignment == '' ? 'selected' : '' ?> value="middle">Middle</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4">Cover Page Colour:</label>
		<div class="col-sm-8">
			<input type="color" class="form-control" name="cover_font_colour" value="<?= $cover_font_colour ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4">Cover Page Logo:</label>
		<div class="col-sm-8">
			<input type="file" class="form-control" name="cover_logo">
			<?php if($cover_logo != '' && file_exists('download/'.$cover_logo)): ?>
				<a target="_blank" href="download/<?= $cover_logo ?>"><b><?= $cover_logo ?></b></a> - <a href="" onclick="$('[name=pdf_logo_name]').val(''); return false;">Delete</a>
				<input type="hidden" name="pdf_logo_name" value="<?= $cover_logo ?>">
			<?php endif; ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4">Logo Alignment:</label>
		<div class="col-sm-8">
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
		<label class="col-sm-4">Logo Height:</label>
		<div class="col-sm-8">
			<select class="form-control" name="cover_logo_height">
				<option <?= '25' == $cover_logo_height ? 'selected' : '' ?> value="25">2.5 cm</option>
				<option <?= '30' == $cover_logo_height ? 'selected' : '' ?> value="30">3 cm</option>
				<option <?= '35' == $cover_logo_height ? 'selected' : '' ?> value="35">3.5 cm</option>
				<option <?= '40' == $cover_logo_height ? 'selected' : '' ?> value="40">4 cm</option>
				<option <?= '45' == $cover_logo_height ? 'selected' : '' ?> value="45">4.5 cm</option>
				<option <?= '50' == $cover_logo_height || '' == $cover_logo_height ? 'selected' : '' ?> value="50">5 cm</option>
				<option <?= '55' == $cover_logo_height ? 'selected' : '' ?> value="55">5.5 cm</option>
				<option <?= '60' == $cover_logo_height ? 'selected' : '' ?> value="60">6 cm</option>
				<option <?= '65' == $cover_logo_height ? 'selected' : '' ?> value="65">6.5 cm</option>
				<option <?= '70' == $cover_logo_height ? 'selected' : '' ?> value="70">7 cm</option>
				<option <?= '75' == $cover_logo_height ? 'selected' : '' ?> value="75">7.5 cm</option>
				<option <?= '80' == $cover_logo_height ? 'selected' : '' ?> value="80">8 cm</option>
				<option <?= '85' == $cover_logo_height ? 'selected' : '' ?> value="85">8.5 cm</option>
				<option <?= '90' == $cover_logo_height ? 'selected' : '' ?> value="90">9 cm</option>
				<option <?= '95' == $cover_logo_height ? 'selected' : '' ?> value="95">9.5 cm</option>
				<option <?= '100' == $cover_logo_height ? 'selected' : '' ?> value="100">10 cm</option>
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