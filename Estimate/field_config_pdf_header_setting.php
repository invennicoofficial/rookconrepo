<?php $header_text = $pdf_settings['text'];
$pdf_logo = $pdf_settings['pdf_logo'];
$pdf_logo_width = $pdf_settings['pdf_logo_width'];
$alignment = $pdf_settings['alignment'];
$header_font_size = $pdf_settings['header_font_size'];
$header_font_type = $pdf_settings['header_font_type'];
$header_font = $pdf_settings['header_font'];
$header_font_colour = $pdf_settings['header_font_colour'];
$page_numbers = $pdf_settings['page_numbers']; ?>
<h3>Header Settings</h3>
<div class="dashboard-item">
	<div class="form-group">
		<label class="col-sm-2 control-label">Header Text:</label>
		<div class="col-sm-10">
			<textarea name="header_text"><?= html_entity_decode($header_text) ?></textarea>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Header Font:</label>
		<div class="col-sm-10">
			<div class="col-sm-3">
				<select class="form-control" name="header_font_size">
					<?php for($i=9;$i<50;$i++): ?>
						<option <?= $i == $header_font_size ? 'selected' : '' ?> value="<?= $i ?>"><?= $i ?>pt</option>
					<?php endfor; ?>
				</select>
			</div>
			<div class="col-sm-4">
				<?php $font_type_array = array('regular'=>'Regular','bold'=>'Bold','italic'=>'Italic','bold_italic'=>'Bold Italic'); ?>
				<select class="form-control" name="header_font_type">
					<?php foreach($font_type_array as $font_type_value => $font_types): ?>
						<option <?= $font_type_value == $header_font_type ? 'selected' : '' ?> value="<?= $font_type_value ?>"><?= $font_types ?></option>
					<?php endforeach; ?>					
				</select>
			</div>
			<div class="col-sm-5">
				<?php $font_array = array('courier'=>'Courier','helvetica'=>'Helvetica','times'=>'Times New Roman','zapfdingbats'=>'Zapf Dingbats','OpenSans'=>'Open Sans','Roboto'=>'Roboto','Encode Sans, sans-serif' => 'Encode Sans, sans-serif', 'Slabo, serif' => 'Slabo', 'Montserrat, sans-serif' => 'Montserrat', 'Raleway, sans-serif' => 'Raleway', 'Merriweather, sans-serif' => 'Merriweather', 'Lora, sans-serif' => 'Lora', 'Nunito, sans-serif' => 'Nunito', 'Karla, sans-serif' => 'Karla');
				ksort($font_array); ?>
				<select name="header_font" class="form-control">
					<?php foreach($font_array as $font_value => $fonts): ?>
						<option <?= $header_font == $font_value ? 'selected' : '' ?> value="<?= $font_value ?>"><?= $fonts ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Header Colour:</label>
		<div class="col-sm-10">
			<input type="color" class="form-control" name="header_font_colour" value="<?= $header_font_colour ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Header Logo:</label>
		<div class="col-sm-10">
			<input type="file" class="form-control" name="pdf_logo">
			<?php if($pdf_logo != '' && file_exists('download/'.$pdf_logo)): ?>
				<a target="_blank" href="download/<?= $pdf_logo ?>"><b><?= $pdf_logo ?></b></a> - <a href="" onclick="$('[name=pdf_logo_name]').val(''); return false;">Delete</a>
				<input type="hidden" name="pdf_logo_name" value="<?= $pdf_logo ?>">
			<?php endif; ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Logo Alignment:</label>
		<div class="col-sm-10">
			<select class="form-control" name="alignment">
				<option <?= 'left' == $alignment ? 'selected' : '' ?> value="left">Left</option>
				<option <?= 'centre' == $alignment ? 'selected' : '' ?> value="centre">Centre</option>
				<option <?= 'right' == $alignment ? 'selected' : '' ?> value="right">Right</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Logo Width:<br /><em>as a percentage of the page width</em></label>
		<div class="col-sm-10">
			<input type="number" min="0.5" max="90" step="0.5" name="pdf_logo_width" class="form-control" value="<?= $pdf_logo_width > 0 ? $pdf_logo_width : 20 ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Page Number Position:</label>
		<div class="col-sm-10">
			<select data-placeholder="Select Position" class="chosen-select-deselect" name="page_numbers">
				<option <?= in_array($page_numbers,['','na']) ? 'selected' : '' ?> value="na">No Page Numbers</option>
				<option <?= $page_numbers == 'top_left_mn' ? 'selected' : '' ?> value="top_left_mn">Left in Header</option>
				<option <?= $page_numbers == 'top_main' ? 'selected' : '' ?> value="top_main">Right in Header</option>
				<option <?= $page_numbers == 'top_left_all' ? 'selected' : '' ?> value="top_left_all">Left in Header, including on Cover</option>
				<option <?= $page_numbers == 'top_cover' ? 'selected' : '' ?> value="top_cover">Right in Header, including on Cover</option>
				<?php if($page_numbers != '' && !in_array($page_numbers,['top_left_all','top_left_mn','top_cover','top_main'])) { ?>
					<option selected value="<?= $page_numbers ?>">Currently in Footer</option>
				<?php } ?>
			</select>
		</div>
	</div>
</div>