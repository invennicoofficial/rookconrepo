<?php
$style_settings = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `pdf_style` FROM `field_config_infogathering` WHERE `form` = '$form'"))['pdf_style'];
$color = "#000000";
$units = "8";

$file_name = "";

$font_heading_size = "10";
$font_heading_type = "";
$font_heading = "helvetica";

$font_main_heading_size = "10";
$font_main_heading_type = "";
$font_main_heading = "helvetica";

$font_main_body_size = "10";
$font_main_body_type = "";
$font_main_body = "helvetica";

$font_footer_size = "10";
$font_footer_type = "";
$font_footer = "helvetica";

$pdf_header_logo_align = "L";
$pdf_footer_logo_align = "L";

$margin_left = PDF_MARGIN_LEFT;
$margin_right = PDF_MARGIN_RIGHT;
$margin_top = PDF_MARGIN_TOP;
$margin_header = PDF_MARGIN_HEADER;
$margin_bottom = PDF_MARGIN_BOTTOM;
$page_ori = PDF_PAGE_ORIENTATION;

$heading_color = "#000000";
$main_body_color = "#000000";
$main_heading_color = "#000000";
$footer_color = "#000000";

if(!empty($style_settings)) {
    $select_pdf_settings = mysqli_fetch_assoc(mysqli_query($dbc, "select * from infogathering_pdf_setting where style = '$style_settings'"));
    if(!empty($select_pdf_settings)) {
        $file_name = $select_pdf_settings['file_name'];

        $font_heading_size = $select_pdf_settings['font_size'];
        $font_heading_type = $select_pdf_settings['font_type'];
        $font_heading = $select_pdf_settings['font'];

        $font_main_heading_size = $select_pdf_settings['font_size'];
        $font_main_heading_type = $select_pdf_settings['font_type'];
        $font_main_heading = $select_pdf_settings['font'];

        $font_main_body_size = $select_pdf_settings['font_size'];
        $font_main_body_type = $select_pdf_settings['font_type'];
        $font_main_body = $select_pdf_settings['font'];

        $font_footer_size = $select_pdf_settings['font_size'];
        $font_footer_type = $select_pdf_settings['font_type'];
        $font_footer = $select_pdf_settings['font'];

        $pdf_header_logo = $select_pdf_settings['pdf_logo'];

        $pdf_size = $select_pdf_settings['pdf_size'];
        $page_ori = $select_pdf_settings['page_ori'];
        $units = $select_pdf_settings['units'];
        $margin_left = $select_pdf_settings['left_margin'];
        $margin_right = $select_pdf_settings['right_margin'];
        $margin_top = $select_pdf_settings['top_margin'];
        $margin_header = $select_pdf_settings['header_margin'];
        $margin_bottom = $select_pdf_settings['bottom_margin'];

        $heading_color = $select_pdf_settings['pdf_color'];
        $main_body_color = $select_pdf_settings['pdf_color'];
        $main_heading_color = $select_pdf_settings['pdf_color'];
        $footer_color = $select_pdf_settings['pdf_color'];
    }

    $select_header_pdf_settings = mysqli_fetch_assoc(mysqli_query($dbc, "select * from infogathering_pdf_setting where style = '$style_settings' AND setting_type = 'header'"));
    if(!empty($select_header_pdf_settings)) {
        $file_name = $select_header_pdf_settings['file_name'];

        $font_heading_size = $select_header_pdf_settings['font_size'];
        $font_heading_type = $select_header_pdf_settings['font_type'];
        $font_heading = $select_header_pdf_settings['font'];

        $pdf_header_logo = $select_header_pdf_settings['pdf_logo'];
        $pdf_header_logo_align = $select_header_pdf_settings['alignment'];

        $heading_color = $select_header_pdf_settings['pdf_color'];
        $header_text = $select_header_pdf_settings['text'];
    }

    $select_footer_pdf_settings = mysqli_fetch_assoc(mysqli_query($dbc, "select * from infogathering_pdf_setting where style = '$style_settings' AND setting_type = 'footer'"));
    if(!empty($select_footer_pdf_settings)) {
        $file_name = $select_footer_pdf_settings['file_name'];

        $font_footer_size = $select_footer_pdf_settings['font_size'];
        $font_footer_type = $select_footer_pdf_settings['font_type'];
        $font_footer = $select_footer_pdf_settings['font'];

        $pdf_footer_logo = $select_footer_pdf_settings['pdf_logo'];
        $pdf_footer_logo_align = $select_footer_pdf_settings['alignment'];

        $footer_color = $select_footer_pdf_settings['pdf_color'];
        $footer_text = $select_footer_pdf_settings['text'];
    }

    $select_main_pdf_settings = mysqli_fetch_assoc(mysqli_query($dbc, "select * from infogathering_pdf_setting where style = '$style_settings' AND setting_type = 'main'"));
    if(!empty($select_main_pdf_settings)) {

        $font_main_heading_size = $select_main_pdf_settings['font_size'];
        $font_main_heading_type = $select_main_pdf_settings['font_type'];
        $font_main_heading = $select_main_pdf_settings['font'];

        $font_main_body_size = $select_main_pdf_settings['font_body_size'];
        $font_main_body_type = $select_main_pdf_settings['font_body_type'];
        $font_main_body = $select_main_pdf_settings['font_body'];

        $main_body_color = $select_main_pdf_settings['pdf_body_color'];
        $main_heading_color = $select_main_pdf_settings['pdf_color'];
    }
}

DEFINE('HEADER_LOGO', $pdf_header_logo);
DEFINE('FOOTER_LOGO', $pdf_footer_logo);
DEFINE('HEADER_TEXT', html_entity_decode($header_text));
DEFINE('FOOTER_TEXT', html_entity_decode($footer_text));
DEFINE('HEADER_FONT', $font_heading);
DEFINE('FOOTER_FONT', $font_footer);
DEFINE('HEADER_FONT_TYPE', $font_heading_type);
DEFINE('FOOTER_FONT_TYPE', $font_footer_type);
DEFINE('HEADER_FONT_SIZE', $font_heading_size);
DEFINE('FOOTER_FONT_SIZE', $font_footer_size);
DEFINE('HEADER_LOGO_ALIGN', $pdf_header_logo_align);
DEFINE('FOOTER_LOGO_ALIGN', $pdf_footer_logo_align);
DEFINE('HEADER_COLOR', $heading_color);
DEFINE('FOOTER_COLOR', $footer_color);

function html_css($font_main_heading, $font_main_heading_type, $font_main_heading_size, $main_heading_color, $font_main_body, $font_main_body_type, $font_main_body_size, $main_body_color) {
    $html = "
        <style>
            h1,h2,h3,h4,h5 {
                color: $main_heading_color;
            }
            body {
                font-family: $font_main_body;
                font-style: $font_main_body_type;
                font-size: $font_main_body_size;
                color: $main_body_color;
            }
            tr,th,td {
                font-family: $font_main_heading;
                font-style: $font_main_heading_type;
                font-size: $font_main_heading_size;
                color: $main_heading_color;
            }
        </style>
    ";
    return $html;
}