<?php $style_settings = (isset($_GET['style']) ? $_GET['style'] : $_POST['styleid']);
$config_sql = "SELECT * FROM `pdf_settings` WHERE `pdfsettingid` AND (`tile_name`='agenda_meeting') ORDER BY `style` ASC";
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

	if(mysqli_num_rows($config) > 0) {
		$result_pdf_settings = mysqli_query($dbc, "UPDATE pdf_settings SET `file_name`='$file_name',`font_size`='$font_size',`font_type`='$font_type',`font`='$font',`pdf_logo`='$pdf_logo',`pdf_size`='$pdf_size',`page_ori`='$page_ori',`units`='$units',`left_margin`='$left_margin',`right_margin`='$right_margin',`top_margin`='$top_margin',`header_margin`='$header_margin',`bottom_margin`='$bottom_margin',`pdf_color`='$pdf_color' WHERE '$style_settings'=`pdfsettingid`");
	} else {
		$result_pdf_settings = mysqli_query($dbc, "INSERT INTO pdf_settings (`tile_name`,`file_name`,`font_size`,`font_type`,`font`,`pdf_logo`,`pdf_size`,`page_ori`,`units`,`left_margin`,`right_margin`,`top_margin`,`header_margin`,`bottom_margin`,`pdf_color`) VALUES('agenda_meeting','$file_name','$font_size','$font_type','$font','$pdf_logo','$pdf_size','$page_ori','$units','$left_margin','$right_margin','$top_margin','$header_margin','$bottom_margin','$pdf_color')");
	}
	$_GET['design'] = $_POST['btn_pdf'];
} else if(isset($_POST['btn_name'])) {
	$style_name = filter_var($_POST['style_name'],FILTER_SANITIZE_STRING);
	if(mysqli_num_rows($config) > 0) {
		$result_pdf_settings = mysqli_query($dbc, "UPDATE pdf_settings SET `style_name`='$style_name' WHERE '$style_settings' = `pdfsettingid`");
	} else {
		$result_pdf_settings = mysqli_query($dbc, "INSERT INTO pdf_settings (`style_name`,`tile_name`) VALUES('$style_name','agenda_meeting')");
	}
	$_GET['design'] = $_POST['btn_name'];
} else if(isset($_POST['btn_style'])) {
	$style = filter_var($_POST['style_layout'],FILTER_SANITIZE_STRING);
	if(mysqli_num_rows($config) > 0) {
		$result_pdf_settings = mysqli_query($dbc, "UPDATE pdf_settings SET `style`='$style' WHERE '$style_settings'=`pdfsettingid`");
	} else {
		$result_pdf_settings = mysqli_query($dbc, "INSERT INTO pdf_settings (`style`,`tile_name`) VALUES('$style','agenda_meeting')");
	}
	$_GET['design'] = $_POST['btn_style'];
} else if(isset($_POST['btn_numbers'])) {
	$page_numbers = filter_var($_POST['page_numbers'],FILTER_SANITIZE_STRING);
	if(mysqli_num_rows($config) > 0) {
		$result_pdf_settings = mysqli_query($dbc, "UPDATE pdf_settings SET `page_numbers`='$page_numbers' WHERE '$style_settings' = `pdfsettingid`");
	} else {
		$result_pdf_settings = mysqli_query($dbc, "INSERT INTO pdf_settings (`page_numbers`,`tile_name`) VALUES('$page_numbers','agenda_meeting')");
	}
	$_GET['design'] = $_POST['btn_numbers'];
} else if(isset($_POST['btn_toc'])) {
	$toc_content = filter_var(implode(',',$_POST['toc_content']),FILTER_SANITIZE_STRING);
	if(mysqli_num_rows($config) > 0) {
		$result_pdf_settings = mysqli_query($dbc, "UPDATE pdf_settings SET `toc_content`='$toc_content' WHERE '$style_settings' = `pdfsettingid`");
	} else {
		$result_pdf_settings = mysqli_query($dbc, "INSERT INTO pdf_settings (`toc_content`,`tile_name`) VALUES('$toc_content','agenda_meeting')");
	}
	$_GET['design'] = $_POST['btn_toc'];
} else if(isset($_POST['btn_header'])) {
	$text = $_POST['header_text'];
	$header_font_size = $_POST['header_font_size'];
	$header_font_type = $_POST['header_font_type'];
	$header_font = $_POST['header_font'];
	$header_font_colour = $_POST['header_font_colour'];
	$pdf_logo = $_FILES["pdf_logo"]["name"];
	$alignment = $_POST['alignment'];
	
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

	if(mysqli_num_rows($config) > 0) {
		$result_pdf_settings = mysqli_query($dbc, "UPDATE pdf_settings SET `text`='$text',`header_font_size`='$header_font_size',`header_font_type`='$header_font_type',`header_font`='$header_font',`header_font_colour`='$header_font_colour',`pdf_logo`='$pdf_logo',`alignment`='$alignment' WHERE '$style_settings' = `pdfsettingid`");
	} else {
		$result_pdf_settings = mysqli_query($dbc, "INSERT INTO pdf_settings (`tile_name`,`text`,`header_font_size`,`header_font_type`,`header_font`,`header_font_colour`,`pdf_logo`,`alignment`) VALUES ('agenda_meeting','$text','$header_font_size','$header_font_type','$header_font','$header_font_colour','$pdf_logo','$alignment')");
	}
	$_GET['design'] = $_POST['btn_header'];
} else if(isset($_POST['btn_content'])) {
	$heading_color = $_POST['heading_color'];
	$font_size = $_POST['font_size'];
	$font_type = $_POST['font_type'];
	$font = $_POST['font'];
	$pdf_body_color = $_POST['pdf_body_color'];
	$font_body_size = $_POST['font_body_size'];
	$font_body_type = $_POST['font_body_type'];
	$font_body = $_POST['font_body'];
	$heading1 = $_POST['heading1'];
	$heading1_colour = $_POST['heading1_colour'];
	$heading2 = $_POST['heading2'];
	$heading2_colour = $_POST['heading2_colour'];

	if(mysqli_num_rows($config) > 0) {
		$result_pdf_settings = mysqli_query($dbc, "UPDATE pdf_settings SET `heading_color`='$heading_color',`font_size`='$font_size',`font_type`='$font_type',`font`='$font',`pdf_body_color`='$pdf_body_color',`font_body_size`='$font_body_size',`font_body_type`='$font_body_type',`font_body`='$font_body',`heading1`='$heading1',`heading1_colour`='$heading1_colour',`heading2`='$heading2',`heading2_colour`='$heading2_colour' WHERE '$style_settings' = `pdfsettingid`");
	} else {
		$result_pdf_settings = mysqli_query($dbc, "INSERT INTO pdf_settings (`tile_name`,`heading_color`,`font_size`,`font_type`,`font`,`pdf_body_color`,`font_body_size`,`font_body_type`,`font_body`,`heading1`,`heading1_colour`,`heading2_colour`,`heading2_colour`) VALUES ('agenda_meeting','$heading_color','$font_size','$font_type','$font','$pdf_body_color','$font_body_size','$font_body_type','$font_body','$heading1','$heading1_colour','$heading2','$heading2_colour')");
	}
	$_GET['design'] = $_POST['btn_content'];
} else if(isset($_POST['btn_pages'])) {
	$pages_text = $_POST['pages_text'];
	$pages_font_size = $_POST['pages_font_size'];
	$pages_font_type = $_POST['pages_font_type'];
	$pages_font = $_POST['pages_font'];
	$pages_font_colour = $_POST['pages_font_colour'];
	$pages_logo = $_FILES["pages_logo"]["name"];
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

	if(mysqli_num_rows($config) > 0) {
		$result_pdf_settings = mysqli_query($dbc, "UPDATE pdf_settings SET `pages_text`='$pages_text',`pages_font_size`='$pages_font_size',`pages_font_type`='$pages_font_type',`pages_font`='$pages_font',`pages_font_colour`='$pages_font_colour',`pages_logo`='$pages_logo',`pages_alignment`='$pages_alignment',`pages_before_content`='$pages_before_content' WHERE '$style_settings' = `pdfsettingid`");
	} else {
		$result_pdf_settings = mysqli_query($dbc, "INSERT INTO pdf_settings (`tile_name`,`pages_text`,`pages_font_size`,`pages_font_type`,`pages_font`,`pages_font_colour`,`pages_logo`,`pages_alignment`,`pages_before_content`) VALUES ('agenda_meeting','$pages_text','$pages_font_size','$pages_font_type','$pages_font','$pages_font_colour','$pages_logo','$pages_alignment','$pages_before_content')");
	}
	$_GET['design'] = $_POST['btn_pages'];
} else if(isset($_POST['btn_cover'])) {
	$cover_text = $_POST['cover_text'];
	$cover_text_alignment = $_POST['cover_text_alignment'];
	$cover_font_size = $_POST['cover_font_size'];
	$cover_font_type = $_POST['cover_font_type'];
	$cover_font = $_POST['cover_font'];
	$cover_font_colour = $_POST['cover_font_colour'];
	$cover_logo = $_FILES["cover_logo"]["name"];
	$cover_alignment = $_POST['cover_alignment'];
	
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

	if(mysqli_num_rows($config) > 0) {
		$result_pdf_settings = mysqli_query($dbc, "UPDATE pdf_settings SET `cover_text`='$cover_text',`cover_text_alignment`='$cover_text_alignment',`cover_font_size`='$cover_font_size',`cover_font_type`='$cover_font_type',`cover_font`='$cover_font',`cover_font_colour`='$cover_font_colour',`cover_logo`='$cover_logo',`cover_logo_height`='$cover_logo_height',`cover_alignment`='$cover_alignment' WHERE '$style_settings' = `pdfsettingid`");
	} else {
		$result_pdf_settings = mysqli_query($dbc, "INSERT INTO pdf_settings (`tile_name`,`cover_text`,`cover_text_alignment`,`cover_font_size`,`cover_font_type`,`cover_font`,`cover_font_colour`,`cover_logo`,`cover_logo_height`,`cover_alignment`) VALUES ('agenda_meeting','$cover_text','$cover_text_alignment','$cover_font_size','$cover_font_type','$cover_font','$cover_font_colour','$cover_logo','$cover_logo_height','$cover_alignment')");
	}
	$_GET['design'] = $_POST['btn_cover'];
} else if(isset($_POST['btn_footer'])) {
	$footer_text = $_POST['footer_text'];
	$footer_font_size = $_POST['footer_font_size'];
	$footer_font_type = $_POST['footer_font_type'];
	$footer_font = $_POST['footer_font'];
	$footer_font_colour = $_POST['footer_font_colour'];
	$footer_logo = $_FILES["footer_logo"]["name"];
	$footer_alignment = $_POST['footer_alignment'];
	
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

	if(mysqli_num_rows($config) > 0) {
		$result_pdf_settings = mysqli_query($dbc, "UPDATE pdf_settings SET `footer_text`='$footer_text',`footer_font_size`='$footer_font_size',`footer_font_type`='$footer_font_type',`footer_font`='$footer_font',`footer_font_colour`='$footer_font_colour',`footer_logo`='$footer_logo',`footer_alignment`='$footer_alignment' WHERE '$style_settings' = `pdfsettingid`");
	} else {
		$result_pdf_settings = mysqli_query($dbc, "INSERT INTO pdf_settings (`tile_name`,`footer_text`,`footer_font_size`,`footer_font_type`,`footer_font`,`footer_font_colour`,`footer_logo`,`footer_alignment`) VALUES ('agenda_meeting','$footer_text','$footer_font_size','$footer_font_type','$footer_font','$footer_font_colour','$footer_logo','$footer_alignment')");
	}
	$_GET['design'] = $_POST['btn_footer'];
}
$styleid = mysqli_insert_id($dbc);
if($styleid > 0) {
	$_GET['style'] = $styleid;
	$config_sql = str_replace("'new'","'$styleid'",$config_sql);
}
$pdf_settings = mysqli_fetch_assoc(mysqli_query($dbc, $config_sql));