<?php $style_settings = (isset($_GET['style']) ? $_GET['style'] : $_POST['styleid']);
$estimateid = $_GET['edit'];
$config_sql = "SELECT * FROM `estimate_pdf_setting` WHERE (`pdfsettingid` = '$style_settings' OR '$style_settings' = '') AND (`estimateid`='$estimateid' OR `estimateid` IS NULL) ORDER BY `estimateid` DESC, `style` ASC";
$config = mysqli_query($dbc, $config_sql);
if(isset($_POST['btn_pdf'])) {
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
	
	if($pdf_logo != '') {
		$filename = preg_replace('/(\.[A-Za-z0-9]*)/', '$1', preg_replace('/[^\.A-Za-z0-9]/','',$pdf_logo));
		for($i = 1; file_exists('download/'.$filename); $i++) {
			$filename = preg_replace('/(\.[A-Za-z0-9]*)/', ' ('.$i.')$1', preg_replace('/[^\.A-Za-z0-9]/','',$pdf_logo));
		}
		$pdf_logo = $filename;
		move_uploaded_file($_FILES["pdf_logo"]["tmp_name"],	"download/".$pdf_logo);
	} else {
		$pdf_logo = $_POST['pdf_logo_name'];
	}

	if(mysqli_num_rows($config) > 0 && mysqli_fetch_assoc($config)['estimateid'] == $estimateid) {
		$result_pdf_settings = mysqli_query($dbc, "UPDATE estimate_pdf_setting SET `file_name`='$file_name',`font_size`='$font_size',`font_type`='$font_type',`font`='$font',`pdf_logo`='$pdf_logo',`pdf_size`='$pdf_size',`page_ori`='$page_ori',`units`='$units',`left_margin`='$left_margin',`right_margin`='$right_margin',`top_margin`='$top_margin',`header_margin`='$header_margin',`bottom_margin`='$bottom_margin',`pdf_color`='$pdf_color' WHERE '$style_settings' IN (`pdfsettingid`,'') AND IFNULL(`estimateid`,'') = '$estimateid'");
	} else {
		$result_pdf_settings = mysqli_query($dbc, "INSERT INTO estimate_pdf_setting(`estimateid`,`file_name`,`font_size`,`font_type`,`font`,`pdf_logo`,`pdf_size`,`page_ori`,`units`,`left_margin`,`right_margin`,`top_margin`,`header_margin`,`bottom_margin`,`pdf_color`) VALUES(NULLIF('$estimateid',''),'$file_name','$font_size','$font_type','$font','$pdf_logo','$pdf_size','$page_ori','$units','$left_margin','$right_margin','$top_margin','$header_margin','$bottom_margin','$pdf_color')");
	}
	$_GET['design'] = $_POST['btn_pdf'];
} else if(isset($_POST['btn_name'])) {
	if(mysqli_num_rows($config) > 0 && mysqli_fetch_assoc($config)['estimateid'] == $estimateid) {
		$result_pdf_settings = mysqli_query($dbc, "UPDATE estimate_pdf_setting SET `style_name`='$style_name' WHERE '$style_settings' IN (`pdfsettingid`,'') AND IFNULL(`estimateid`,'') = '$estimateid'");
	} else {
		$result_pdf_settings = mysqli_query($dbc, "INSERT INTO estimate_pdf_setting(`style_name`,`estimateid`) VALUES('$style_name',NULLIF('$estimateid',''))");
	}
	$_GET['design'] = $_POST['btn_name'];
} else if(isset($_POST['btn_style'])) {
	$style_name = filter_var($_POST['style_name'],FILTER_SANITIZE_STRING);
	$style = filter_var($_POST['style_layout'],FILTER_SANITIZE_STRING);
	if(mysqli_num_rows($config) > 0 && mysqli_fetch_assoc($config)['estimateid'] == $estimateid) {
		$result_pdf_settings = mysqli_query($dbc, "UPDATE estimate_pdf_setting SET `style`='$style', `style_name`='$style_name' WHERE '$style_settings' IN (`pdfsettingid`,'') AND IFNULL(`estimateid`,'') = '$estimateid'");
	} else {
		$result_pdf_settings = mysqli_query($dbc, "INSERT INTO estimate_pdf_setting(`style`,`style_name`,`estimateid`) VALUES('$style','$style_name',NULLIF('$estimateid',''))");
	}
	$_GET['design'] = $_POST['btn_style'];
} else if(isset($_POST['btn_numbers'])) {
	$page_numbers = filter_var($_POST['page_numbers'],FILTER_SANITIZE_STRING);
	if(mysqli_num_rows($config) > 0 && mysqli_fetch_assoc($config)['estimateid'] == $estimateid) {
		$result_pdf_settings = mysqli_query($dbc, "UPDATE estimate_pdf_setting SET `page_numbers`='$page_numbers' WHERE '$style_settings' IN (`pdfsettingid`,'') AND IFNULL(`estimateid`,'') = '$estimateid'");
	} else {
		$result_pdf_settings = mysqli_query($dbc, "INSERT INTO estimate_pdf_setting(`page_numbers`,`estimateid`) VALUES('$page_numbers',NULLIF('$estimateid',''))");
	}
	$_GET['design'] = $_POST['btn_numbers'];
} else if(isset($_POST['btn_toc'])) {
	$toc_content = filter_var(implode(',',$_POST['toc_content']),FILTER_SANITIZE_STRING);
	if(mysqli_num_rows($config) > 0 && mysqli_fetch_assoc($config)['estimateid'] == $estimateid) {
		$result_pdf_settings = mysqli_query($dbc, "UPDATE estimate_pdf_setting SET `toc_content`='$toc_content' WHERE '$style_settings' IN (`pdfsettingid`,'') AND IFNULL(`estimateid`,'') = '$estimateid'");
	} else {
		$result_pdf_settings = mysqli_query($dbc, "INSERT INTO estimate_pdf_setting(`toc_content`,`estimateid`) VALUES('$toc_content',NULLIF('$estimateid',''))");
	}
	$_GET['design'] = $_POST['btn_toc'];
} else if(isset($_POST['btn_header'])) {
	$style_settings = $_POST['style'];
	$text = $_POST['header_text'];
	$header_font_size = $_POST['header_font_size'];
	$header_font_type = $_POST['header_font_type'];
	$header_font = $_POST['header_font'];
	$header_font_colour = $_POST['header_font_colour'];
	$pdf_logo = $_FILES["pdf_logo"]["name"];
	$pdf_logo_width = $_POST['pdf_logo_width'];
	$alignment = $_POST['alignment'];
	$page_numbers = filter_var($_POST['page_numbers'],FILTER_SANITIZE_STRING);
	
	if($pdf_logo != '') {
		$filename = preg_replace('/(\.[A-Za-z0-9]*)/', '$1', preg_replace('/[^\.A-Za-z0-9]/','',$pdf_logo));
		for($i = 1; file_exists('download/'.$filename); $i++) {
			$filename = preg_replace('/(\.[A-Za-z0-9]*)/', ' ('.$i.')$1', preg_replace('/[^\.A-Za-z0-9]/','',$pdf_logo));
		}
		$pdf_logo = $filename;
		move_uploaded_file($_FILES["pdf_logo"]["tmp_name"],	"download/".$pdf_logo);
	} else {
		$pdf_logo = $_POST['pdf_logo_name'];
	}

	if(mysqli_num_rows($config) > 0 && mysqli_fetch_assoc($config)['estimateid'] == $estimateid) {
		$result_pdf_settings = mysqli_query($dbc, "UPDATE estimate_pdf_setting SET `text`='$text',`header_font_size`='$header_font_size',`header_font_type`='$header_font_type',`header_font`='$header_font',`header_font_colour`='$header_font_colour',`pdf_logo`='$pdf_logo',`pdf_logo_width`='$pdf_logo_width',`alignment`='$alignment',`page_numbers`='$page_numbers' WHERE '$style_settings' IN (`pdfsettingid`,'') AND IFNULL(`estimateid`,'') = '$estimateid'");
	} else {
		$result_pdf_settings = mysqli_query($dbc, "INSERT INTO estimate_pdf_setting(`estimateid`,`text`,`header_font_size`,`header_font_type`,`header_font`,`header_font_colour`,`pdf_logo`,`pdf_logo_width`,`alignment`,`page_numbers`) VALUES (NULLIF('$estimateid',''),'$text','$header_font_size','$header_font_type','$header_font','$header_font_colour','$pdf_logo','$pdf_logo_width','$alignment','$page_numbers')");
	}
	$_GET['design'] = $_POST['btn_header'];
} else if(isset($_POST['btn_content'])) {
	$style_settings = $_POST['style'];
	$heading_color = $_POST['heading_color'];
	$font_size = $_POST['font_size'];
	$font_type = $_POST['font_type'];
	$font = $_POST['font'];
	$pdf_body_color = $_POST['pdf_body_color'];
	$font_body_size = $_POST['font_body_size'];
	$font_body_type = $_POST['font_body_type'];
	$font_body = $_POST['font_body'];

	if(mysqli_num_rows($config) > 0 && mysqli_fetch_assoc($config)['estimateid'] == $estimateid) {
		$result_pdf_settings = mysqli_query($dbc, "UPDATE estimate_pdf_setting SET `heading_color`='$heading_color',`font_size`='$font_size',`font_type`='$font_type',`font`='$font',`pdf_body_color`='$pdf_body_color',`font_body_size`='$font_body_size',`font_body_type`='$font_body_type',`font_body`='$font_body' WHERE '$style_settings' IN (`pdfsettingid`,'') AND IFNULL(`estimateid`,'') = '$estimateid'");
	} else {
		$result_pdf_settings = mysqli_query($dbc, "INSERT INTO estimate_pdf_setting(`estimateid`,`heading_color`,`font_size`,`font_type`,`font`,`pdf_body_color`,`font_body_size`,`font_body_type`,`font_body`) VALUES (NULLIF('$estimateid',''),'$heading_color','$font_size','$font_type','$font','$pdf_body_color','$font_body_size','$font_body_type','$font_body')");
	}
	$_GET['design'] = $_POST['btn_content'];
} else if(isset($_POST['btn_pages'])) {
	$style_settings = $_POST['style'];
	$pages_text = $_POST['pages_text'];
	$pages_font_size = $_POST['pages_font_size'];
	$pages_font_type = $_POST['pages_font_type'];
	$pages_font = $_POST['pages_font'];
	$pages_font_colour = $_POST['pages_font_colour'];
	$pages_logo = $_FILES["pages_logo"]["name"];
	$pages_logo_width = $_POST['pages_logo_width'];
	$pages_alignment = $_POST['pages_alignment'];
	$pages_before_content = $_POST['pages_before_content'];
	
	if($pages_logo != '') {
		$filename = preg_replace('/(\.[A-Za-z0-9]*)/', '$1', preg_replace('/[^\.A-Za-z0-9]/','',$pages_logo));
		for($i = 1; file_exists('download/'.$filename); $i++) {
			$filename = preg_replace('/(\.[A-Za-z0-9]*)/', ' ('.$i.')$1', preg_replace('/[^\.A-Za-z0-9]/','',$pages_logo));
		}
		$pages_logo = $filename;
		move_uploaded_file($_FILES["pages_logo"]["tmp_name"],	"download/".$pages_logo);
	} else {
		$pages_logo = $_POST['pages_logo_name'];
	}

	if(mysqli_num_rows($config) > 0 && mysqli_fetch_assoc($config)['estimateid'] == $estimateid) {
		$result_pdf_settings = mysqli_query($dbc, "UPDATE estimate_pdf_setting SET `pages_text`='$pages_text',`pages_font_size`='$pages_font_size',`pages_font_type`='$pages_font_type',`pages_font`='$pages_font',`pages_font_colour`='$pages_font_colour',`pages_logo`='$pages_logo',`pages_alignment`='$pages_alignment',`pages_logo_width`='$pages_logo_width',`pages_before_content`='$pages_before_content' WHERE '$style_settings' IN (`pdfsettingid`,'') AND IFNULL(`estimateid`,'') = '$estimateid'");
	} else {
		$result_pdf_settings = mysqli_query($dbc, "INSERT INTO estimate_pdf_setting(`estimateid`,`pages_text`,`pages_font_size`,`pages_font_type`,`pages_font`,`pages_font_colour`,`pages_logo`,`pages_alignment`,`pages_logo_width`,`pages_before_content`) VALUES (NULLIF('$estimateid',''),'$pages_text','$pages_font_size','$pages_font_type','$pages_font','$pages_font_colour','$pages_logo','$pages_alignment','$pages_logo_width','$pages_before_content')");
	}
	$_GET['design'] = $_POST['btn_pages'];
} else if(isset($_POST['btn_cover'])) {
	$style_settings = $_POST['style'];
	$cover_text = $_POST['cover_text'];
	$cover_text_alignment = $_POST['cover_text_alignment'];
	$cover_font_size = $_POST['cover_font_size'];
	$cover_font_type = $_POST['cover_font_type'];
	$cover_font = $_POST['cover_font'];
	$cover_font_colour = $_POST['cover_font_colour'];
	$cover_logo = $_FILES["cover_logo"]["name"];
	$cover_alignment = $_POST['cover_alignment'];
	$cover_logo_height = $_POST['cover_logo_height'];
	
	if($cover_logo != '') {
		$filename = preg_replace('/(\.[A-Za-z0-9]*)/', '$1', preg_replace('/[^\.A-Za-z0-9]/','',$cover_logo));
		for($i = 1; file_exists('download/'.$filename); $i++) {
			$filename = preg_replace('/(\.[A-Za-z0-9]*)/', ' ('.$i.')$1', preg_replace('/[^\.A-Za-z0-9]/','',$cover_logo));
		}
		$cover_logo = $filename;
		move_uploaded_file($_FILES["cover_logo"]["tmp_name"],	"download/".$cover_logo);
	} else {
		$cover_logo = $_POST['cover_logo_name'];
	}

	if(mysqli_num_rows($config) > 0 && mysqli_fetch_assoc($config)['estimateid'] == $estimateid) {
		$result_pdf_settings = mysqli_query($dbc, "UPDATE estimate_pdf_setting SET `cover_text`='$cover_text',`cover_text_alignment`='$cover_text_alignment',`cover_font_size`='$cover_font_size',`cover_font_type`='$cover_font_type',`cover_font`='$cover_font',`cover_font_colour`='$cover_font_colour',`cover_logo`='$cover_logo',`cover_logo_height`='$cover_logo_height',`cover_alignment`='$cover_alignment' WHERE '$style_settings' IN (`pdfsettingid`,'') AND IFNULL(`estimateid`,'') = '$estimateid'");
	} else {
		$result_pdf_settings = mysqli_query($dbc, "INSERT INTO estimate_pdf_setting(`estimateid`,`cover_text`,`cover_text_alignment`,`cover_font_size`,`cover_font_type`,`cover_font`,`cover_font_colour`,`cover_logo`,`cover_logo_height`,`cover_alignment`) VALUES (NULLIF('$estimateid',''),'$cover_text','$cover_text_alignment','$cover_font_size','$cover_font_type','$cover_font','$cover_font_colour','$cover_logo','$cover_logo_height','$cover_alignment')");
	}
	$_GET['design'] = $_POST['btn_cover'];
} else if(isset($_POST['btn_footer'])) {
	$style_settings = $_POST['style'];
	$footer_text = $_POST['footer_text'];
	$footer_font_size = $_POST['footer_font_size'];
	$footer_font_type = $_POST['footer_font_type'];
	$footer_font = $_POST['footer_font'];
	$footer_font_colour = $_POST['footer_font_colour'];
	$footer_logo = $_FILES["footer_logo"]["name"];
	$footer_logo_width = $_POST['footer_logo_width'];
	$footer_alignment = $_POST['footer_alignment'];
	$page_numbers = filter_var($_POST['page_numbers'],FILTER_SANITIZE_STRING);
	
	if($footer_logo != '') {
		$filename = preg_replace('/(\.[A-Za-z0-9]*)/', '$1', preg_replace('/[^\.A-Za-z0-9]/','',$footer_logo));
		for($i = 1; file_exists('download/'.$filename); $i++) {
			$filename = preg_replace('/(\.[A-Za-z0-9]*)/', ' ('.$i.')$1', preg_replace('/[^\.A-Za-z0-9]/','',$footer_logo));
		}
		$footer_logo = $filename;
		move_uploaded_file($_FILES["footer_logo"]["tmp_name"],	"download/".$footer_logo);
	} else {
		$footer_logo = $_POST['pdf_logo_name'];
	}

	if(mysqli_num_rows($config) > 0 && mysqli_fetch_assoc($config)['estimateid'] == $estimateid) {
		$result_pdf_settings = mysqli_query($dbc, "UPDATE estimate_pdf_setting SET `footer_text`='$footer_text',`footer_font_size`='$footer_font_size',`footer_font_type`='$footer_font_type',`footer_font`='$footer_font',`footer_font_colour`='$footer_font_colour',`footer_logo`='$footer_logo',`footer_logo_width`='$footer_logo_width',`footer_alignment`='$footer_alignment', `page_numbers`='$page_numbers' WHERE '$style_settings' IN (`pdfsettingid`,'') AND IFNULL(`estimateid`,'') = '$estimateid'");
	} else {
		$result_pdf_settings = mysqli_query($dbc, "INSERT INTO estimate_pdf_setting(`estimateid`,`footer_text`,`footer_font_size`,`footer_font_type`,`footer_font`,`footer_font_colour`,`footer_logo`,`footer_logo_width`,`footer_alignment`, `page_numbers`) VALUES (NULLIF('$estimateid',''),'$footer_text','$footer_font_size','$footer_font_type','$footer_font','$footer_font_colour','$footer_logo','$footer_logo_width','$footer_alignment','$page_numbers')");
	}
	$_GET['design'] = $_POST['btn_footer'];
}
$styleid = mysqli_insert_id($dbc);
if($styleid > 0) {
	$_GET['style'] = $styleid;
	$config_sql = str_replace("'new'","'$styleid'",$config_sql);
}
$pdf_settings = mysqli_fetch_assoc(mysqli_query($dbc, $config_sql));
if($pdf_settings['style'] == '') {
	$pdf_settings['style'] = 'a';
}