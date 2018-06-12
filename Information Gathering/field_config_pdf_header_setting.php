<?php
	$style_settings = $_GET['style_settings'];
?>
<?php
	$select_pdf_settings = mysqli_fetch_assoc(mysqli_query($dbc, "select count(pdfsettingid) as setting_count from infogathering_pdf_setting where style = '$style_settings' and setting_type = 'header'"));
	
	if(isset($_POST['pdf_setting'])) {
		$font_size = $_POST['font_size'];
		$font_type = $_POST['font_type'];
		$font = $_POST['font'];
		if(!empty($_FILES["pdf_logo"]["name"])) {
			$pdf_logo = 'header_'.$_FILES["pdf_logo"]["name"];
		}
		$pdf_color = $_POST['pdf_color'];
		$header_text = $_POST['header_text'];
		$alignment = $_POST['alignment'];

	    if (!file_exists('download')) {
	        mkdir('download', 0777, true);
	    }

		if($select_pdf_settings['setting_count'] <= 0) {	
			$insert_pdf_settings = "INSERT INTO infogathering_pdf_setting(`style`,`font_size`,`font_type`,`font`,`pdf_logo`,`pdf_color`,`setting_type`,`text`,`alignment`) VALUES('$style_settings','$font_size','$font_type','$font','$pdf_logo','$pdf_color','header','$header_text','$alignment')";
			$result_pdf_settings = mysqli_query($dbc, $insert_pdf_settings);
		}
		else {
			$select_pdf = mysqli_fetch_assoc(mysqli_query($dbc, "select pdf_logo from infogathering_pdf_setting where style = '$style_settings' and setting_type = 'header'"));
			if($pdf_logo == 'header_')
				$pdf_logo = $select_pdf['pdf_logo'];
			$update_pdf_settings = "UPDATE infogathering_pdf_setting SET `style`='$style_settings',`font_size`='$font_size',`font_type`='$font_type',`font`='$font',`pdf_color`='$pdf_color',`text`='$header_text',`alignment`='$alignment' where style = '$style_settings' and setting_type='header'";
			$result_pdf_settings = mysqli_query($dbc, $update_pdf_settings);
		}
			
		if($_FILES["pdf_logo"]["name"] != '') {
			move_uploaded_file($_FILES["pdf_logo"]["tmp_name"],	"download/" . $pdf_logo);
			$update_pdf_settings = "UPDATE infogathering_pdf_setting SET `pdf_logo`='$pdf_logo' where style = '$style_settings' and setting_type='header'";
			$result_pdf_settings = mysqli_query($dbc, $update_pdf_settings);
		}
	}

	$file_name = '';
	$font_size = '';
	$font_type = '';
	$font = '';
	$pdf_logo = '';
	$pdf_color = '';
	$header_text = '';
	$alignment = '';
	if($select_pdf_settings['setting_count'] > 0) {
		$select_pdf_settings = mysqli_fetch_assoc(mysqli_query($dbc, "select * from infogathering_pdf_setting where style = '$style_settings' and setting_type='header'"));
		$file_name = $select_pdf_settings['file_name'];
		$font_size = $select_pdf_settings['font_size'];
		$font_type = $select_pdf_settings['font_type'];
		$font = $select_pdf_settings['font'];
		$pdf_color = $select_pdf_settings['pdf_color'];
		$header_text = $select_pdf_settings['text'];
		$pdf_logo = $select_pdf_settings['pdf_logo'];
		$alignment = $select_pdf_settings['alignment'];
	}
?>
<form action="" method="POST" enctype="multipart/form-data">
<h3 style="margin-left:10px"><?php echo "Header Settings"; ?></h3>
<div class="clearfix"></div>
<div style="margin:10px;padding:25px;height:100%">
		<div class="pull-left" style="width:55%">
			<span class="pull-left"><label>Header Text:</label></span>
			<span style="margin-left:2px;width:77%" class="pull-right"><input type="text" class="form-control" style="height:70px" value="<?php echo $header_text; ?>" name="header_text"/></span>
			<br><br><br><br>
			<span class="pull-left"><label>Font:</label></span>
			<div class="pull-right">
				<select class="form-control" name="font_size">
					<?php for($i=9;$i<50;$i++): ?>
						<?php $selected = ''; ?>
						<?php if($i == $font_size): ?>
							<?php $selected = 'selected="selected"'; ?>
						<?php endif; ?>
						<option <?php echo $selected; ?>value="<?php echo $i; ?>"><?php echo $i; ?>pt</option>
					<?php endfor; ?>
				</select>
			</div>
			<div style="width: 25%;padding-right: 39px;" class="pull-right">
				<?php
					$font_type_array = array('regular'=>'Regular','bold'=>'Bold','italic'=>'Italic');
				?>
				<select class="form-control" name="font_type">
					<?php foreach($font_type_array as $font_type_value => $font_types): ?>
						<?php $selected = ''; ?>
						<?php if($font_type_value == $font_type): ?>
							<?php $selected = 'selected="selected"'; ?>
						<?php endif; ?>
						<option <?php echo $selected; ?> value="<?php echo $font_type_value; ?>"><?php echo $font_types; ?></option>
					<?php endforeach; ?>					
				</select>
			</div>
			<div style="padding-right: 39px;width: 39%;" class="pull-right">
				<?php
					$font_array = array('courier'=>'Courier','helvetica'=>'Helvetica','times'=>'Times New Roman','zapfdingbats'=>'Zapf Dingbats','OpenSans'=>'Open Sans','Roboto'=>'Roboto','Encode Sans, sans-serif' => 'Encode Sans, sans-serif', 'Slabo, serif' => 'Slabo', 'Montserrat, sans-serif' => 'Montserrat', 'Raleway, sans-serif' => 'Raleway', 'Merriweather, sans-serif' => 'Merriweather', 'Lora, sans-serif' => 'Lora', 'Nunito, sans-serif' => 'Nunito', 'Karla, sans-serif' => 'Karla');
					ksort($font_array);
				?>
				<select name="font" class="form-control">
					<?php foreach($font_array as $font_value => $fonts): ?>
						<?php $selected = ''; ?>
						<?php if($font == $font_value): ?>
							<?php $selected = "selected='selected'"; ?>
						<?php endif; ?>
						<option <?php echo $selected; ?> value="<?php echo $font_value; ?>"><?php echo $fonts; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<br><br>
			<div class="clearfix"></div>
			<span class="pull-left"><label>Color:</label></span>
			<div style="margin-left:20%;" class="demoPanel pull-left">
				<div id="cpInline"></div>
			</div>
			<input type="hidden" value="" name="pdf_color" id="pdf_color"/>
		</div>
		<div class="pull-right">
			<span class="pull-left"><label>Logo:</label></span>
			<span style="margin-left:2px;width:61%" class="pull-right"><input type="file" class="form-control" name="pdf_logo">
			<?php if($pdf_logo != ''): ?>
				<a target="_blank" href="download/<?php echo $pdf_logo; ?>"><b>View</b></a>
			<?php endif; ?>
			</span>
			<br><br>
			<div class="clearfix"></div>
			<span class="pull-left" style="margin-right:55px"><label>Alignment:</label></span>
			<span style="margin-left:2px;width:40%" class="pull-left">
				<select name="alignment" class="form-control">
					<?php if($alignment == 'L'): ?>
						<option selected='selected' value="L">Left</option>
					<?php else: ?>
						<option value="L">Left</option>
					<?php endif; ?>
					<?php if($alignment == 'C'): ?>
						<option selected='selected' value="C">Center</option>
					<?php else: ?>
						<option value="C">Center</option>
					<?php endif; ?>
					<?php if($alignment == 'R'): ?>
						<option selected='selected' value="R">Right</option>
					<?php else: ?>
						<option value="R">Right</option>
					<?php endif; ?>
				</select>
			</span>
		</div>
		<div class="clearfix"></div>
		<br><br>
		<span class="pull-right">
			<a href="?style_settings=<?php echo $style_settings; ?>&setting=main"><button class="btn brand-btn hide-titles-mob" type="button" value="Set Main Content for PDF" >Next</button></a>
		</span>
		<span class="pull-right" style="margin-right:10px">
			<input class="btn brand-btn hide-titles-mob" type="submit" onClick="return setFormcolor();" name="pdf_setting" value="Save"/>
		</span>
		<br><br>
	</div>
</form>

<script type="text/javascript">
	jQuery(document).ready(function(){
		var pdf_color = '<?php echo $pdf_color; ?>';
		jQuery(".evo-color span").text(pdf_color);
		jQuery(".evo-color div").css('background-color', pdf_color)
	});

	function setFormcolor()
	{
		var color = jQuery(".evo-color span").text();
		var res_color = color.substring(0, 7);
		jQuery("#pdf_color").val(res_color);
		return true;
	}
</script>