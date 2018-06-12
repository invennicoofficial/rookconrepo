<?php
	$style_settings = $_GET['style_settings'];
?>
<?php
	$select_pdf_settings = mysqli_fetch_assoc(mysqli_query($dbc, "select count(pdfsettingid) as setting_count from services_pdf_setting where style = '$style_settings' and setting_type='main'"));
	if(isset($_POST['pdf_setting'])) {
		$font_main_heading_size = $_POST['font_main_heading_size'];
		$font_main_heading_type = $_POST['font_main_heading_type'];
		$font_main_heading = $_POST['font_main_heading'];

		$font_main_body_size = $_POST['font_main_body_size'];
		$font_main_body_type = $_POST['font_main_body_type'];
		$font_main_body = $_POST['font_main_body'];

		$pdf_heading_color = $_POST['pdf_heading_color'];
		$pdf_body_color = $_POST['pdf_body_color'];

	    if (!file_exists('download')) {
	        mkdir('download', 0777, true);
	    }
		
		if($pdf_logo != '') {
			move_uploaded_file($_FILES["pdf_logo"]["tmp_name"],	"download/" . $pdf_logo);
		}

		if($select_pdf_settings['setting_count'] <= 0) {	
			$insert_pdf_settings = "INSERT INTO services_pdf_setting(`style`,`font_size`,`font_type`,`font`,`font_body_size`,`font_body_type`,`font_body`,`pdf_color`,`pdf_body_color`,`setting_type`) VALUES('$style_settings','$font_main_heading_size','$font_main_heading_type','$font_main_body','$font_main_heading_size','$font_main_heading_type','$font_main_body','$pdf_heading_color','$pdf_body_color','main')";
			$result_pdf_settings = mysqli_query($dbc, $insert_pdf_settings);
		}
		else {
			$select_pdf = mysqli_fetch_assoc(mysqli_query($dbc, "select pdf_logo from services_pdf_setting where style = '$style_settings' and setting_type='main'"));
			if($pdf_logo == '')
				$pdf_logo = $select_pdf['pdf_logo'];
			$update_pdf_settings = "UPDATE services_pdf_setting SET `font_size`='$font_main_heading_size',`font_type`='$font_main_heading_type',`font`='$font_main_heading',`font_body_size`='$font_main_body_size',`font_body_type`='$font_main_body_type',`font_body`='$font_main_body',`pdf_color`='$pdf_heading_color',`pdf_body_color`='$pdf_body_color' where style = '$style_settings' and setting_type='main'";
			$result_pdf_settings = mysqli_query($dbc, $update_pdf_settings);
		}
	}

	$font_main_heading_size = '';
	$font_main_heading_type = '';
	$font_main_heading = '';

	$font_main_body_size = '';
	$font_main_body_type = '';
	$font_main_body = '';

	$pdf_body_color = '';

	$pdf_heading_color = '';

	if($select_pdf_settings['setting_count'] > 0) {
		$select_pdf_settings = mysqli_fetch_assoc(mysqli_query($dbc, "select * from services_pdf_setting where style = '$style_settings' and setting_type='main'"));
		$font_main_heading_size = $select_pdf_settings['font_size'];
		$font_main_heading_type = $select_pdf_settings['font_type'];
		$font_main_heading = $select_pdf_settings['font'];

		$font_main_body_size = $select_pdf_settings['font_body_size'];
		$font_main_body_type = $select_pdf_settings['font_body_type'];
		$font_main_body = $select_pdf_settings['font_body'];

		$pdf_heading_color = $select_pdf_settings['pdf_color'];
		$pdf_body_color = $select_pdf_settings['pdf_body_color'];
	}
?>
<form action="" method="POST" enctype="multipart/form-data">
<h3 style="margin-left:10px"><?php echo "PDF Settings"; ?></h3>
<div class="clearfix"></div>
<div style="margin:10px;padding:25px;height:100%">
		<div class="pull-left" style="width:44%">
			<h5><b>Heading</b></h5>
			<br>
			<span class="pull-left"><label>Font:</label></span>
			<div class="pull-right">
				<select class="form-control" name="font_main_heading_size">
					<?php for($i=9;$i<50;$i++): ?>
						<?php $selected = ''; ?>
						<?php if($i == $font_main_heading_size): ?>
							<?php $selected = 'selected="selected"'; ?>
						<?php endif; ?>
						<option <?php echo $selected; ?>value="<?php echo $i; ?>"><?php echo $i; ?>pt</option>
					<?php endfor; ?>
				</select>
			</div>
			<div style="width: 25%;padding-right: 39px;" class="pull-right">
				<?php
					$font_main_heading_type_array = array('regular'=>'Regular','bold'=>'Bold','italic'=>'Italic');
				?>
				<select class="form-control" name="font_main_heading_type">
					<?php foreach($font_main_heading_type_array as $font_main_heading_type_value => $font_main_heading_types): ?>
						<?php $selected = ''; ?>
						<?php if($font_main_heading_type_value == $font_main_heading_type): ?>
							<?php $selected = 'selected="selected"'; ?>
						<?php endif; ?>
						<option <?php echo $selected; ?> value="<?php echo $font_main_heading_type_value; ?>"><?php echo $font_main_heading_types; ?></option>
					<?php endforeach; ?>					
				</select>
			</div>
			<div style="padding-right: 39px;width: 39%;" class="pull-right">
				<?php
					$font_array = array('courier'=>'Courier','helvetica'=>'Helvetica','times'=>'Times New Roman','zapfdingbats'=>'Zapf Dingbats','OpenSans'=>'Open Sans','Roboto'=>'Roboto','Encode Sans, sans-serif' => 'Encode Sans, sans-serif', 'Slabo, serif' => 'Slabo', 'Montserrat, sans-serif' => 'Montserrat', 'Raleway, sans-serif' => 'Raleway', 'Merriweather, sans-serif' => 'Merriweather', 'Lora, sans-serif' => 'Lora', 'Nunito, sans-serif' => 'Nunito', 'Karla, sans-serif' => 'Karla');
					ksort($font_array);
				?>
				<select name="font_main_heading" class="form-control">
					<?php foreach($font_array as $font_value => $fonts): ?>
						<?php $selected = ''; ?>
						<?php if($font_main_heading == $font_value): ?>
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
			<input type="hidden" value="" name="pdf_heading_color" id="pdf_heading_color"/>
		</div>
		<div class="pull-right" style="width:44%">
			<h5><b>Body</b></h5>
			<br>
			<span class="pull-left"><label>Font:</label></span>
			<div class="pull-right">
				<select class="form-control" name="font_main_body_size">
					<?php for($i=9;$i<50;$i++): ?>
						<?php $selected = ''; ?>
						<?php if($i == $font_main_body_size): ?>
							<?php $selected = 'selected="selected"'; ?>
						<?php endif; ?>
						<option <?php echo $selected; ?>value="<?php echo $i; ?>"><?php echo $i; ?>pt</option>
					<?php endfor; ?>
				</select>
			</div>
			<div style="width: 25%;padding-right: 39px;" class="pull-right">
				<?php
					$font_main_body_type_array = array('regular'=>'Regular','bold'=>'Bold','italic'=>'Italic');
				?>
				<select class="form-control" name="font_main_body_type">
					<?php foreach($font_main_body_type_array as $font_main_body_type_value => $font_main_body_types): ?>
						<?php $selected = ''; ?>
						<?php if($font_main_body_type_value == $font_main_body_type): ?>
							<?php $selected = 'selected="selected"'; ?>
						<?php endif; ?>
						<option <?php echo $selected; ?> value="<?php echo $font_main_body_type_value; ?>"><?php echo $font_main_body_types; ?></option>
					<?php endforeach; ?>					
				</select>
			</div>
			<div style="padding-right: 39px;width: 39%;" class="pull-right">
				<?php
					$font_array = array('courier'=>'Courier','helvetica'=>'Helvetica','times'=>'Times New Roman','zapfdingbats'=>'Zapf Dingbats','OpenSans'=>'Open Sans','Roboto'=>'Roboto','Encode Sans, sans-serif' => 'Encode Sans, sans-serif', 'Slabo, serif' => 'Slabo', 'Montserrat, sans-serif' => 'Montserrat', 'Raleway, sans-serif' => 'Raleway', 'Merriweather, sans-serif' => 'Merriweather', 'Lora, sans-serif' => 'Lora', 'Nunito, sans-serif' => 'Nunito', 'Karla, sans-serif' => 'Karla');
					ksort($font_array);
				?>
				<select name="font_main_body" class="form-control">
					<?php foreach($font_array as $font_value => $fonts): ?>
						<?php $selected = ''; ?>
						<?php if($font_main_body == $font_value): ?>
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
				<div id="cpInline2"></div>
			</div>
			<input type="hidden" value="" name="pdf_body_color" id="pdf_body_color"/>
		</div>
		<div class="clearfix"></div>
		<br><br>
		<span class="pull-right">
			<a href="?style_settings=<?php echo $style_settings; ?>&preview=<?php echo $style_settings; ?>"><button class="btn brand-btn hide-titles-mob" type="button" value="PDF Setting for Style <?php echo substr($style_settings, -1); ?>" >Preview For Style <?php echo substr($style_settings, -1); ?></button></a>
		</span>
		<span class="pull-right" style="margin-right:10px">
			<input class="btn brand-btn hide-titles-mob" type="submit" onClick="return setFormcolor();" name="pdf_setting" value="Save"/>
		</span>
		<br><br>
	</div>
</form>

<script type="text/javascript">
	jQuery(document).ready(function(){
		var pdf_color1 = '<?php echo $pdf_heading_color; ?>';
		jQuery("#cpInline .evo-color span").text(pdf_color1);
		jQuery("#cpInline .evo-color div").css('background-color', pdf_color1)

		var pdf_color = '<?php echo $pdf_body_color; ?>';
		jQuery("#cpInline2 .evo-color span").text(pdf_color);
		jQuery("#cpInline2 .evo-color div").css('background-color', pdf_color)

	});

	jQuery(document).ready(function(){
		
	});

	function setFormcolor()
	{
		var color = jQuery("#cpInline .evo-color span").text();
		var res_color = color.substring(0, 7);
		jQuery("#pdf_heading_color").val(res_color);

		var color = jQuery("#cpInline2 .evo-color span").text();
		var res_color1 = color.substring(0, 7);
		jQuery("#pdf_body_color").val(res_color1);
		return true;
	}
</script>