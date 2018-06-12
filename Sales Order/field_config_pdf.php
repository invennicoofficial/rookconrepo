<?php include_once('../include.php');
checkAuthorised('sales_order');
/*
/* Sales Order Config - Fields
*/

if(isset($_POST['save_config'])) {
	if (!file_exists('download')) {
		mkdir('download', 0777, true);
	}
	mysqli_query($dbc, "INSERT INTO `field_config_so_pdf` (`header_logo`) SELECT '' FROM (SELECT COUNT(*) rows FROM `field_config_so_pdf`) num WHERE num.rows=0");

	if(!empty($_FILES['header_logo']['name'])) {
		$header_logo = $basename = preg_replace('/[^a-z0-9.]*/','',strtolower($_FILES['header_logo']['name']));
		$j = 0;
		while(file_exists('download/'.$header_logo)) {
			$header_logo = preg_replace('/(\.[a-z0-9]*)/', ' ('.++$j.')$1', $basename);
		}
		move_uploaded_file($_FILES['header_logo']['tmp_name'], 'download/'.$header_logo);
		mysqli_query($dbc, "UPDATE `field_config_so_pdf` SET `header_logo` = '$header_logo'");
	}

	if(!empty($_FILES['footer_logo']['name'])) {
		$footer_logo = $basename = preg_replace('/[^a-z0-9.]*/','',strtolower($_FILES['footer_logo']['name']));
		$j = 0;
		while(file_exists('download/'.$footer_logo)) {
			$footer_logo = preg_replace('/(\.[a-z0-9]*)/', ' ('.++$j.')$1', $basename);
		}
		move_uploaded_file($_FILES['footer_logo']['tmp_name'], 'download/'.$footer_logo);
		mysqli_query($dbc, "UPDATE `field_config_so_pdf` SET `footer_logo` = '$footer_logo'");
	}

	$header_logo_align = filter_var($_POST['header_logo_align'],FILTER_SANITIZE_STRING);
	$header_text = filter_var(htmlentities($_POST['header_text']),FILTER_SANITIZE_STRING);
	$header_align = filter_var($_POST['header_align'],FILTER_SANITIZE_STRING);
	$footer_logo_align = filter_var($_POST['footer_logo_align'],FILTER_SANITIZE_STRING);
	$footer_text = filter_var(htmlentities($_POST['footer_text']),FILTER_SANITIZE_STRING);
	$footer_align = filter_var($_POST['footer_align'],FILTER_SANITIZE_STRING);
	$body_font = filter_var($_POST['body_font'],FILTER_SANITIZE_STRING);
	$body_size = filter_var($_POST['body_size'],FILTER_SANITIZE_STRING);
	$body_color = filter_var($_POST['body_color'],FILTER_SANITIZE_STRING);

	mysqli_query($dbc, "UPDATE `field_config_so_pdf` SET `header_logo_align` = '$header_logo_align', `header_text` = '$header_text', `header_align` = '$header_align', `footer_logo_align` = '$footer_logo_align', `footer_text` = '$footer_text', `footer_align` = '$footer_align', `body_font` = '$body_font', `body_size` = '$body_size', `body_color` = '$body_color'");
}

$pdf_settings = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_so_pdf`"));

$header_logo = !empty($pdf_settings['header_logo']) ? $pdf_settings['header_logo'] : '';
$header_logo_align = !empty($pdf_settings['header_logo_align']) ? $pdf_settings['header_logo_align'] : 'R';
$header_text = !empty($pdf_settings['header_text']) ? $pdf_settings['header_text'] : '';
$header_align = !empty($pdf_settings['header_align']) ? $pdf_settings['header_align'] : 'L';

$footer_logo = !empty($pdf_settings['footer_logo']) ? $pdf_settings['footer_logo'] : '';
$footer_logo_align = !empty($pdf_settings['footer_logo_align']) ? $pdf_settings['footer_logo_align'] : 'L';
$footer_text = !empty($pdf_settings['footer_text']) ? $pdf_settings['footer_text'] : '';
$footer_align = !empty($pdf_settings['footer_align']) ? $pdf_settings['footer_align'] : 'C';

$body_font = !empty($pdf_settings['body_font']) ? $pdf_settings['body_font'] : 'helvetica';
$body_size = !empty($pdf_settings['body_size']) ? $pdf_settings['body_size'] : 9;
$body_color = !empty($pdf_settings['body_color']) ? $pdf_settings['body_color'] : '#000000';
?>
<script type="text/javascript">
function colorCodeChange(sel) {
    $(sel).closest('.form-group').find('[name$="color"]').val(sel.value);
}
function deleteLogo(logo) {
	if(confirm('Are you sure you want to delete this logo?')) {
		var formid = $('#formid').val();
		$.ajax({
			url: '../Sales Order/ajax.php?fill=settingsDeleteLogo',
			type: 'POST',
			data: { logo: logo },
			success: function(response) {
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

<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
	<div class="gap-top">
		<h3>Header Settings</h3>
		<div class="form-group">
			<label class="col-sm-4 control-label">Header Logo:</label>
			<div class="col-sm-8">
				<div class="header_logo_url">
					<?php if(!empty($header_logo) && file_exists('download/'.$header_logo)) { ?>
						<a href="download/<?= $header_logo ?>" target="_blank">View</a> | <a href="" onclick="deleteLogo('header'); return false;">Delete</a>
					<?php } ?>
				</div>
				<input name="header_logo" type="file" data-filename-placement="inside" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Header Logo Align:</label>
			<div class="col-sm-8">
	            <select name="header_logo_align" class="chosen-select-deselect form-control">
	                <option></option>
	                <option <?= $header_logo_align == 'L' ? 'selected' : '' ?> value="L">Left</option>
	                <option <?= $header_logo_align == 'C' ? 'selected' : '' ?> value="C">Center</option>
	                <option <?= $header_logo_align == 'R' ? 'selected' : '' ?> value="R">Right</option>
	            </select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Header Text:</label>
			<div class="col-sm-8">
				<textarea name="header_text"><?= html_entity_decode($header_text) ?></textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Header Text Align:</label>
			<div class="col-sm-8">
	            <select name="header_align" class="chosen-select-deselect form-control">
	                <option></option>
	                <option <?= $header_align == 'L' ? 'selected' : '' ?> value="L">Left</option>
	                <option <?= $header_align == 'C' ? 'selected' : '' ?> value="C">Center</option>
	                <option <?= $header_align == 'R' ? 'selected' : '' ?> value="R">Right</option>
	            </select>
			</div>
		</div>
		<h3>Footer Settings</h3>
		<div class="form-group">
			<label class="col-sm-4 control-label">Footer Logo:</label>
			<div class="col-sm-8">
				<div class="footer_logo_url">
					<?php if(!empty($footer_logo) && file_exists('download/'.$footer_logo)) { ?>
						<a href="download/<?= $footer_logo ?>" target="_blank">View</a> | <a href="" onclick="deleteLogo('footer'); return false;">Delete</a>
					<?php } ?>
				</div>
				<input name="footer_logo" type="file" data-filename-placement="inside" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Footer Logo Align:</label>
			<div class="col-sm-8">
	            <select name="footer_logo_align" class="chosen-select-deselect form-control">
	                <option></option>
	                <option <?= $footer_logo_align == 'L' ? 'selected' : '' ?> value="L">Left</option>
	                <option <?= $footer_logo_align == 'C' ? 'selected' : '' ?> value="C">Center</option>
	                <option <?= $footer_logo_align == 'R' ? 'selected' : '' ?> value="R">Right</option>
	            </select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Footer Text:</label>
			<div class="col-sm-8">
				<textarea name="footer_text"><?= html_entity_decode($footer_text) ?></textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Footer Text Align:</label>
			<div class="col-sm-8">
	            <select name="footer_align" class="chosen-select-deselect form-control">
	                <option></option>
	                <option <?= $footer_align == 'L' ? 'selected' : '' ?> value="L">Left</option>
	                <option <?= $footer_align == 'C' ? 'selected' : '' ?> value="C">Center</option>
	                <option <?= $footer_align == 'R' ? 'selected' : '' ?> value="R">Right</option>
	            </select>
			</div>
		</div>
		<h3>Body Settings</h3>
		<div class="form-group">
			<label class="col-sm-4 control-label">Body Font:</label>
			<div class="col-sm-8">
	            <select name="body_font" class="chosen-select-deselect form-control">
	                <option></option>
	                <?php $font_array = array('courier'=>'Courier','helvetica'=>'Helvetica','times'=>'Times New Roman','zapfdingbats'=>'Zapf Dingbats','OpenSans'=>'Open Sans','Roboto'=>'Roboto','Encode Sans, sans-serif' => 'Encode Sans, sans-serif', 'Slabo, serif' => 'Slabo', 'Montserrat, sans-serif' => 'Montserrat', 'Raleway, sans-serif' => 'Raleway', 'Merriweather, sans-serif' => 'Merriweather', 'Lora, sans-serif' => 'Lora', 'Nunito, sans-serif' => 'Nunito', 'Karla, sans-serif' => 'Karla');
	                	ksort($font_array);
	                    foreach($font_array as $font_value => $font) { ?>
	                        <option <?= $body_font == $font_value ? 'selected' : '' ?> value="<?= $font_value ?>"><?= $font ?></option>
	                    <?php }
	                ?>
	            </select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Body Font Size:</label>
			<div class="col-sm-8">
	            <select name="body_size" class="chosen-select-deselect form-control">
	                <option></option>
	                <?php for($i = 6; $i < 50; $i++) { ?>
	                    <option <?= $body_size == $i ? 'selected' : '' ?> value="<?= $i ?>"><?= $i ?>pt</option>
	                <?php } ?>
	            </select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Body Font Color:</label>
	        <div class="col-sm-1">
	            <input type="color" name="body_color_picker" value="<?= $body_color ?>" class="form-control" onchange="colorCodeChange(this);">
	        </div>
	        <div class="col-sm-7">
	            <input type="text" name="body_color" value="<?= $body_color ?>" class="form-control">
	        </div>
		</div>
	</div>
	<div class="pull-right gap-top gap-right gap-bottom">
	    <a href="index.php" class="btn brand-btn">Cancel</a>
	    <button type="submit" id="save_config" name="save_config" value="Submit" class="btn brand-btn">Submit</button>
	</div>
</form>