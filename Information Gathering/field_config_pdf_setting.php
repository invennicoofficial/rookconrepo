<?php
	$style_settings = $_GET['style_settings'];
?>
<?php
	$select_pdf_settings = mysqli_fetch_assoc(mysqli_query($dbc, "select count(pdfsettingid) as setting_count from infogathering_pdf_setting where style = '$style_settings'"));
	if(isset($_POST['pdf_setting'])) {
		$file_name = $_POST['file_name'];
		$font_size = $_POST['font_size'];
		$font_type = $_POST['font_type'];
		$font = $_POST['font'];
		$pdf_logo = $_FILES["pdf_logo"]["name"];
		$pdf_size = $_POST['pdf_size'];
		$page_ori = $_POST['page_ori'];
		$units = $_POST['units'];
		$left_margin = $_POST['left_margin'];
		$right_margin = $_POST['right_margin'];
		$top_margin = $_POST['top_margin'];
		$header_margin = $_POST['header_margin'];
		$bottom_margin = $_POST['bottom_margin'];
		$pdf_color = $_POST['pdf_color'];

	    if (!file_exists('download')) {
	        mkdir('download', 0777, true);
	    }
	    
		if($pdf_logo != '') {
			move_uploaded_file($_FILES["pdf_logo"]["tmp_name"],	"download/" . $pdf_logo);
		}

		if($select_pdf_settings['setting_count'] <= 0) {	
			$insert_pdf_settings = "INSERT INTO infogathering_pdf_setting(`style`,`file_name`,`font_size`,`font_type`,`font`,`pdf_logo`,`pdf_size`,`page_ori`,`units`,`left_margin`,`right_margin`,`top_margin`,`header_margin`,`bottom_margin`,`pdf_color`) VALUES('$style_settings','$file_name','$font_size','$font_type','$font','$pdf_logo','$pdf_size','$page_ori','$units','$left_margin','$right_margin','$top_margin','$header_margin','$bottom_margin','$pdf_color')";
			$result_pdf_settings = mysqli_query($dbc, $insert_pdf_settings);
		}
		else {
			$select_pdf = mysqli_fetch_assoc(mysqli_query($dbc, "select pdf_logo from infogathering_pdf_setting where style = '$style_settings'"));
			if($pdf_logo == '')
				$pdf_logo = $select_pdf['pdf_logo'];
			$update_pdf_settings = "UPDATE infogathering_pdf_setting SET `style`='$style_settings',`file_name`='$file_name',`font_size`='$font_size',`font_type`='$font_type',`font`='$font',`pdf_logo`='$pdf_logo',`pdf_size`='$pdf_size',`page_ori`='$page_ori',`units`='$units',`left_margin`='$left_margin',`right_margin`='$right_margin',`top_margin`='$top_margin',`header_margin`='$header_margin',`bottom_margin`='$bottom_margin',`pdf_color`='$pdf_color' where style = '$style_settings'";
			$result_pdf_settings = mysqli_query($dbc, $update_pdf_settings);
		}
	}

	$file_name = '';
	$font_size = '';
	$font_type = '';
	$font = '';
	$pdf_logo = '';
	$pdf_size = '';
	$page_ori = '';
	$units = '';
	$left_margin = '';
	$right_margin = '';
	$top_margin = '';
	$header_margin = '';
	$bottom_margin = '';
	$pdf_color = '';
	if($select_pdf_settings['setting_count'] > 0) {
		$select_pdf_settings = mysqli_fetch_assoc(mysqli_query($dbc, "select * from infogathering_pdf_setting where style = '$style_settings'"));
		$file_name = $select_pdf_settings['file_name'];
		$font_size = $select_pdf_settings['font_size'];
		$font_type = $select_pdf_settings['font_type'];
		$font = $select_pdf_settings['font'];
		$pdf_logo = $select_pdf_settings['pdf_logo'];
		$pdf_size = $select_pdf_settings['pdf_size'];
		$page_ori = $select_pdf_settings['page_ori'];
		$units = $select_pdf_settings['units'];
		$left_margin = $select_pdf_settings['left_margin'];
		$right_margin = $select_pdf_settings['right_margin'];
		$top_margin = $select_pdf_settings['top_margin'];
		$header_margin = $select_pdf_settings['header_margin'];
		$bottom_margin = $select_pdf_settings['bottom_margin'];
		$pdf_color = $select_pdf_settings['pdf_color'];
	}
?>
<form action="" method="POST" enctype="multipart/form-data">
<h3 style="margin-left:10px"><?php echo "PDF Settings"; ?></h3>
<div class="clearfix"></div>
<div style="margin:10px;padding:25px;height:100%">
		<div class="pull-left" style="width:55%">
			<h5><b>Document Settings</b></h5>
			<br>
			<div class="clearfix"></div>
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
			<span class="pull-left"><label>Logo:</label></span>
			<span style="margin-left:2px;width:77%" class="pull-right"><input type="file" class="form-control" name="pdf_logo">
			<?php if($pdf_logo != ''): ?>
				<a target="_blank" href="download/<?php echo $pdf_logo; ?>"><b>View</b></a>
			<?php endif; ?>
			</span>
			<br><br>
			<div class="clearfix"></div>
			<span class="pull-left"><label>Color:</label></span>
			<div style="margin-left:20%;" class="demoPanel pull-left">
				<div id="cpInline"></div>
			</div>
			<input type="hidden" value="" name="pdf_color" id="pdf_color"/>
		</div>
		<div class="pull-right" style="padding-right:10%">
			<h5><b>Page Settings</b></h5>
			<br>
			<span class="pull-left" style="margin-right: 20px;margin-top:-10px"><label>Page Size:</label></span>
			<div class="pull-right">
				<select class="form-control" name="pdf_size">
					<?php for($i=9;$i<50;$i++): ?>
						<?php $selected = ''; ?>
						<?php if($i == $pdf_size): ?>
							<?php $selected = 'selected="selected"'; ?>
						<?php endif; ?>
						<option <?php echo $selected; ?> value="<?php echo $i; ?>"><?php echo $i; ?>pt</option>
					<?php endfor; ?>
				</select>
			</div>
			<br><br>
			<span class="pull-left" style="margin-right: 20px;margin-top:-10px"><label>Page Orientation:</label></span>
			<div class="pull-right">
				<select class="form-control" name="page_ori">
					<?php $ori_array = array("portrait"=>"Portrait","landscape"=>"Landscape"); ?>
					<?php foreach($ori_array as $ori): ?>
						<?php $selected = ''; ?>
						<?php if($i == $page_ori): ?>
							<?php $selected = 'selected="selected"'; ?>
						<?php endif; ?>
						<option <?php echo $selected; ?> value="<?php echo $ori; ?>"><?php echo $ori; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<br><br>
			<span class="pull-left" style="margin-right: 20px;margin-top:-10px"><label>Units:</label></span>
			<div class="pull-right">
				<select class="form-control" name="units">
					<?php for($i=9;$i<50;$i++): ?>
						<?php $selected = ''; ?>
						<?php if($i == $units): ?>
							<?php $selected = 'selected="selected"'; ?>
						<?php endif; ?>
						<option <?php echo $selected; ?> value="<?php echo $i; ?>"><?php echo $i; ?>pt</option>
					<?php endfor; ?>
				</select>
			</div>
			<br><br>
			<span class="pull-left" style="margin-right: 20px;margin-top:-10px"><label>Left Margin:</label></span>
			<div class="pull-right">
				<select class="form-control" name="left_margin">
					<?php for($i=9;$i<50;$i++): ?>
						<?php $selected = ''; ?>
						<?php if($i == $left_margin): ?>
							<?php $selected = 'selected="selected"'; ?>
						<?php endif; ?>
						<option <?php echo $selected; ?> value="<?php echo $i; ?>"><?php echo $i; ?>pt</option>
					<?php endfor; ?>
				</select>
			</div>
			<br><br>
			<span class="pull-left" style="margin-right: 20px;margin-top:-10px"><label>Right Margin:</label></span>
			<div class="pull-right">
				<select class="form-control" name="right_margin">
					<?php for($i=9;$i<50;$i++): ?>
						<?php $selected = ''; ?>
						<?php if($i == $font_size): ?>
							<?php $selected = 'selected="selected"'; ?>
						<?php endif; ?>
						<option <?php echo $selected; ?> value="<?php echo $i; ?>"><?php echo $i; ?>pt</option>
					<?php endfor; ?>
				</select>
			</div>
			<br><br>
			<span class="pull-left" style="margin-right: 20px;margin-top:-10px"><label>Top Margin:</label></span>
			<div class="pull-right">
				<select class="form-control" name="top_margin">
					<?php for($i=9;$i<50;$i++): ?>
						<?php $selected = ''; ?>
						<?php if($i == $top_margin): ?>
							<?php $selected = 'selected="selected"'; ?>
						<?php endif; ?>
						<option <?php echo $selected; ?> value="<?php echo $i; ?>"><?php echo $i; ?>pt</option>
					<?php endfor; ?>
				</select>
			</div>
			<br><br>
			<span class="pull-left" style="margin-right: 20px;margin-top:-10px"><label>Header Margin</label></span>
			<div class="pull-right">
				<select class="form-control" name="header_margin">
					<?php for($i=9;$i<50;$i++): ?>
						<?php $selected = ''; ?>
						<?php if($i == $header_margin): ?>
							<?php $selected = 'selected="selected"'; ?>
						<?php endif; ?>
						<option <?php echo $selected; ?> value="<?php echo $i; ?>"><?php echo $i; ?>pt</option>
					<?php endfor; ?>
				</select>
			</div>
			<br><br>
			<span class="pull-left" style="margin-right: 20px;margin-top:-10px"><label>Bottom Margin:</label></span>
			<div class="pull-right">
				<select class="form-control" name="bottom_margin">
					<?php for($i=9;$i<50;$i++): ?>
						<?php $selected = ''; ?>
						<?php if($i == $bottom_margin): ?>
							<?php $selected = 'selected="selected"'; ?>
						<?php endif; ?>
						<option <?php echo $selected; ?> value="<?php echo $i; ?>"><?php echo $i; ?>pt</option>
					<?php endfor; ?>
				</select>
			</div>
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
		var pdf_color = '<?php echo $pdf_color; ?>';
		jQuery(".evo-color span").text(pdf_color)
	});

	function setFormcolor()
	{
		var color = jQuery(".evo-color span").text();
		var res_color = color.substring(0, 7);
		jQuery("#pdf_color").val(res_color);
		return true;
	}
</script>