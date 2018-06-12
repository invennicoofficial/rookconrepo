<?php $footer_text = $pdf_settings['footer_text'];
$footer_logo = $pdf_settings['footer_logo'];
$footer_logo_width = $pdf_settings['footer_logo_width'];
$footer_alignment = $pdf_settings['footer_alignment'];
$footer_font_size = $pdf_settings['footer_font_size'];
$footer_font_type = $pdf_settings['footer_font_type'];
$footer_font = $pdf_settings['footer_font'];
$footer_font_colour = $pdf_settings['footer_font_colour'];
$page_numbers = $pdf_settings['page_numbers']; ?>
<h3>Footer Settings</h3>
<div class="dashboard-item">
	<div class="form-group">
		<label class="col-sm-2 control-label">Footer Text:</label>
		<div class="col-sm-10">
			<textarea name="footer_text"><?= html_entity_decode($footer_text) ?></textarea>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Footer Font:</label>
		<div class="col-sm-10">
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
		<label class="col-sm-2 control-label">Footer Colour:</label>
		<div class="col-sm-10">
			<input type="color" class="form-control" name="footer_font_colour" value="<?= $footer_font_colour ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Footer Logo:</label>
		<div class="col-sm-10">
			<input type="file" class="form-control" name="footer_logo">
			<?php if($footer_logo != '' && file_exists('download/'.$footer_logo)): ?>
				<a target="_blank" href="download/<?= $footer_logo ?>"><b><?= $footer_logo ?></b></a> - <a href="" onclick="$('[name=pdf_logo_name]').val(''); return false;">Delete</a>
				<input type="hidden" name="pdf_logo_name" value="<?= $footer_logo ?>">
			<?php endif; ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Logo Alignment:</label>
		<div class="col-sm-10">
			<select class="form-control" name="footer_alignment">
				<option <?= 'left' == $footer_alignment ? 'selected' : '' ?> value="left">Left</option>
				<option <?= 'centre' == $footer_alignment ? 'selected' : '' ?> value="centre">Centre</option>
				<option <?= 'right' == $footer_alignment || $footer_alignment == '' ? 'selected' : '' ?> value="right">Right</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Logo Width:<br /><em>as a percentage of the page width</em></label>
		<div class="col-sm-10">
			<input type="number" min="0.5" max="90" step="0.5" name="footer_logo_width" class="form-control" value="<?= $footer_logo_width > 0 ? $footer_logo_width : 20 ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Page Number Position:</label>
		<div class="col-sm-10">
			<select data-placeholder="Select Position" class="chosen-select-deselect" name="page_numbers">
				<option <?= in_array($page_numbers,['','na']) ? 'selected' : '' ?> value="na">No Page Numbers</option>
				<option <?= $page_numbers == 'bot_left_mn' ? 'selected' : '' ?> value="bot_left_mn">Left in Footer</option>
				<option <?= $page_numbers == 'bottom_main' ? 'selected' : '' ?> value="bottom_main">Right in Footer</option>
				<option <?= $page_numbers == 'bot_left_all' ? 'selected' : '' ?> value="bot_left_all">Left in Footer, including on Cover</option>
				<option <?= $page_numbers == 'bottom_cover' ? 'selected' : '' ?> value="bottom_cover">Right in Footer, including on Cover</option>
				<?php if($page_numbers != '' && !in_array($page_numbers,['bot_left_all','bot_left_mn','bottom_cover','bottom_main'])) { ?>
					<option selected value="<?= $page_numbers ?>">Currently in Header</option>
				<?php } ?>
			</select>
		</div>
	</div>
</div>