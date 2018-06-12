<?php $heading_color = $pdf_settings['heading_color'];
$font_size = $pdf_settings['font_size'];
$font_type = $pdf_settings['font_type'];
$font = $pdf_settings['font'];
$pdf_body_color = $pdf_settings['pdf_body_color'];
$font_body_size = $pdf_settings['font_body_size'];
$font_body_type = $pdf_settings['font_body_type'];
$font_body = $pdf_settings['font_body']; ?>
<h3>Main Content Settings</h3>
<div class="dashboard-item">
	<div class="col-sm-6">
		<h4>Heading</h4>
		<div class="form-group">
			<label class="col-sm-4 control-label">Heading Font:</label>
			<div class="col-sm-8">
				<div class="col-sm-3">
					<select class="form-control" name="font_size">
						<?php for($i=9;$i<50;$i++): ?>
							<option <?= $i == $font_size ? 'selected' : '' ?> value="<?= $i ?>"><?= $i ?>pt</option>
						<?php endfor; ?>
					</select>
				</div>
				<div class="col-sm-4">
					<?php $font_type_array = array('regular'=>'Regular','bold'=>'Bold','italic'=>'Italic','bold_italic'=>'Bold Italic'); ?>
					<select class="form-control" name="font_type">
						<?php foreach($font_type_array as $font_type_value => $font_types): ?>
							<option <?= $font_type_value == $font_type ? 'selected' : '' ?> value="<?= $font_type_value ?>"><?= $font_types ?></option>
						<?php endforeach; ?>					
					</select>
				</div>
				<div class="col-sm-5">
					<?php $font_array = array('courier'=>'Courier','helvetica'=>'Helvetica','times'=>'Times New Roman','zapfdingbats'=>'Zapf Dingbats','OpenSans'=>'Open Sans','Roboto'=>'Roboto','Encode Sans, sans-serif' => 'Encode Sans, sans-serif', 'Slabo, serif' => 'Slabo', 'Montserrat, sans-serif' => 'Montserrat', 'Raleway, sans-serif' => 'Raleway', 'Merriweather, sans-serif' => 'Merriweather', 'Lora, sans-serif' => 'Lora', 'Nunito, sans-serif' => 'Nunito', 'Karla, sans-serif' => 'Karla');
					ksort($font_array); ?>
					<select name="font" class="form-control">
						<?php foreach($font_array as $font_value => $fonts): ?>
							<option <?= $font == $font_value ? 'selected' : '' ?> value="<?= $font_value ?>"><?= $fonts ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Heading Colour:</label>
			<div class="col-sm-8">
				<input type="color" class="form-control" name="heading_color" value="<?= $heading_color ?>">
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<h4>Body</h4>
		<div class="form-group">
			<label class="col-sm-4 control-label">Body Font:</label>
			<div class="col-sm-8">
				<div class="col-sm-3">
					<select class="form-control" name="font_body_size">
						<?php for($i=9;$i<50;$i++): ?>
							<option <?= $i == $font_body_size ? 'selected' : '' ?> value="<?= $i ?>"><?= $i ?>pt</option>
						<?php endfor; ?>
					</select>
				</div>
				<div class="col-sm-4">
					<?php $font_type_array = array('regular'=>'Regular','bold'=>'Bold','italic'=>'Italic','bold_italic'=>'Bold Italic'); ?>
					<select class="form-control" name="font_body_type">
						<?php foreach($font_type_array as $font_type_value => $font_types): ?>
							<option <?= $font_type_value == $font_body_type ? 'selected' : '' ?> value="<?= $font_type_value ?>"><?= $font_types ?></option>
						<?php endforeach; ?>					
					</select>
				</div>
				<div class="col-sm-5">
					<?php $font_array = array('courier'=>'Courier','helvetica'=>'Helvetica','times'=>'Times New Roman','zapfdingbats'=>'Zapf Dingbats','OpenSans'=>'Open Sans','Roboto'=>'Roboto','Encode Sans, sans-serif' => 'Encode Sans, sans-serif', 'Slabo, serif' => 'Slabo', 'Montserrat, sans-serif' => 'Montserrat', 'Raleway, sans-serif' => 'Raleway', 'Merriweather, sans-serif' => 'Merriweather', 'Lora, sans-serif' => 'Lora', 'Nunito, sans-serif' => 'Nunito', 'Karla, sans-serif' => 'Karla');
					ksort($font_array); ?>
					<select name="font_body" class="form-control">
						<?php foreach($font_array as $font_value => $fonts): ?>
							<option <?= $font_body == $font_value ? 'selected' : '' ?> value="<?= $font_value ?>"><?= $fonts ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Body Colour:</label>
			<div class="col-sm-8">
				<input type="color" class="form-control" name="pdf_body_color" value="<?= $pdf_body_color ?>">
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
</div>