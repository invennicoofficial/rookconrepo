<?php
include('field_list.php');
include_once('../tcpdf/tcpdf.php');

$category = $_POST['category'];
$template = $_POST['template'];
$style_settings = $_POST['pdf_styling'];

if(!empty($template)) {
    $query_template = "SELECT GROUP_CONCAT(`heading_name`) as field_list FROM `services_templates_headings` WHERE `template_id` = '$template' AND `deleted` = 0 ORDER BY `sort_order` ASC";
    $result_template = mysqli_fetch_assoc(mysqli_query($dbc, $query_template));
    $fields = $result_template['field_list'];
} else {
    $fields = implode(',', array_keys($field_list));
}

if(!empty($category)) {
    $category_query = "AND `category` = '$category'";
} else {
    $category_query = '';
}

$query_services = "SELECT $fields FROM `services` WHERE `deleted` = 0 $category_query";
$result_services = mysqli_fetch_all(mysqli_query($dbc, $query_services),MYSQLI_ASSOC);

$color = "#000000";
$units = "8";
$page_ori = "Portrait";

$file_name = "";

$font_heading_size = "8";
$font_heading_type = "";
$font_heading = "times";

$font_main_heading_size = "8";
$font_main_heading_type = "";
$font_main_heading = "times";

$font_main_body_size = "8";
$font_main_body_type = "";
$font_main_body = "times";

$font_footer_size = "8";
$font_footer_type = "";
$font_footer = "times";

$pdf_header_logo = "";
$pdf_footer_logo = "";
$pdf_header_logo_align = "C";
$pdf_footer_logo_align = "C";

$margin_left = "10px";
$margin_right = "10px";
$margin_top = "10px";
$margin_header = "10px";
$margin_bottom = "10px";

$heading_color = "#000000";
$main_body_color = "#000000";
$main_heading_color = "#000000";
$footer_color = "#000000";

$select_pdf_settings = mysqli_fetch_assoc(mysqli_query($dbc, "select * from services_pdf_setting where style = '$style_settings'"));
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

$select_header_pdf_settings = mysqli_fetch_assoc(mysqli_query($dbc, "select * from services_pdf_setting where style = '$style_settings' AND setting_type = 'header'"));
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

$select_footer_pdf_settings = mysqli_fetch_assoc(mysqli_query($dbc, "select * from services_pdf_setting where style = '$style_settings' AND setting_type = 'footer'"));
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

$select_main_pdf_settings = mysqli_fetch_assoc(mysqli_query($dbc, "select * from services_pdf_setting where style = '$style_settings' AND setting_type = 'main'"));
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

class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        $this->setFont('times', '', 8);
        if(HEADER_LOGO != '') {
            $image_file = 'download/'.HEADER_LOGO;
            $this->Image($image_file, 10, 5, 0, 25, '', '', 'T', false, 300, HEADER_LOGO_ALIGN, false, false, 0, false, false, false);
        }

        if(HEADER_TEXT != '') {
            $this->setCellHeightRatio(0.7);
            $font_style = "font-family: ".HEADER_FONT."; font-style: ".HEADER_FONT_TYPE."; font-size: ".HEADER_FONT_SIZE."; color: ".HEADER_COLOR.";";
            
            $header_align = (HEADER_LOGO_ALIGN == "L" ? "R" : "L");
            if ($header_align == "L") {
                $align_style = 'text-align: left;';
            } else {
                $align_style = 'text-align: right;';
            }
            $header_text = '<p style="'.$font_style.$align_style.'">'.HEADER_TEXT.'</p>';
            $this->writeHTMLCell(0, 0, 5 , 5, $header_text, 0, 0, false, true, $header_align, true);
        }
    }

    // Page footer
    public function Footer() {
        $font_style = "font-family: ".FOOTER_FONT."; font-style: ".FOOTER_FONT_TYPE."; font-size: ".FOOTER_FONT_SIZE."; color: ".FOOTER_COLOR.";";

        $footer_align = (FOOTER_LOGO_ALIGN == "L" ? "R" : "L");
        if ($footer_align == "L") {
            $align_style = 'text-align: left;';
        } else {
            $align_style = 'text-align: right;';
        }

        // Position at 15 mm from bottom
        $this->SetY(-10);
        $this->SetFont('times', '', 8);
        $footer_text = '<p style="'.$align_style.'">'.$this->getAliasNumPage().'</p>';
        $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, true, $footer_align, true);

        if(FOOTER_TEXT != '') {
            $this->SetY(-15);
            $this->setCellHeightRatio(0.7);
            $footer_text = '<p style="'.$font_style.$align_style.'">'.FOOTER_TEXT.'</p>';
            $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, true, $footer_align, true);
        }

        if(FOOTER_LOGO != '') {
            $this->SetY(-30);
            $image_file = 'download/'.FOOTER_LOGO;
            $this->Image($image_file, 11, 275, 25, '', '', '', '', false, 300, FOOTER_LOGO_ALIGN, false, false, 0, false, false, false);
        }
    }
}

$pdf = new MYPDF($page_ori, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetMargins($margin_left, $margin_top, $margin_right);

$fields = explode(',', $fields);

$html = "
<style>
    table {
        border: 0px none black;
    }
    th {
        font-family: $font_main_heading;
        font-style: $font_main_heading_type;
        font-size: $font_main_heading_size;
        color: $main_heading_color;
        border: 0px none $heading_color;
    }
    td {
        font-family: $font_main_body;
        font-style: $font_main_body_type;
        font-size: $font_main_body_size;
        color: $main_body_color;
        border: 0px none $heading_color;
    }
</style>
";

// $html .= '<table cellspacing="1" cellpadding="2">';
// $html .= '<tr>';
// foreach ($fields as $field) {
    // $html .= '<th>'.$field_list[$field].'</th>';
// }
// $html .= '</tr>';

// foreach ($result_services as $service) {
    // $html .= '<tr>';
    // foreach ($fields as $field) {
        // if (in_array($field, $is_money)) {
            // $value = '$'.number_format($service[$field],2,'.','');
        // } else if ($field == 'checklist') {
            // $value = str_replace('#*#', '<br />', $service[$field]);
        // } else if ($field == 'appointment_type') {
            // $value = get_type_from_booking($dbc, $service[$field]);
        // } else if ($field == 'gst_exempt') {
            // $value = $service[$field] == '1' ? 'Yes' : 'No';
        // } else {
            // $value = $service[$field];
        // }
        // $html .= '<td>'.$value.'</td>';
    // }
    // $html .= '</tr>';
// }

foreach ($result_services as $service) {
    $html .= '<table width="100%" cellspacing="5" cellpadding="5" nobr="true"><tr>';
	$image_cell = '';
	$text_cell = '';
    foreach ($fields as $field) {
        if ($field == 'service_image' && $service['service_image'] != '' && file_exists('download/'.$service['service_image'])) {
            $image_cell = '<td style="width:30%"><img src="download/'.$service['service_image'].'"></td>';
        } else if (in_array($field, $is_money)) {
            $text_cell .= '<b>'.$field_list[$field].'</b>: $'.number_format($service[$field],2,'.','').'<br />';
        } else if ($field == 'checklist') {
            $text_cell .= '<b>'.$field_list[$field].'</b>: '.str_replace('#*#', '<br />', $service[$field]).'<br />';
        } else if ($field == 'appointment_type') {
            $text_cell .= '<b>'.$field_list[$field].'</b>: '.get_type_from_booking($dbc, $service[$field]).'<br />';
        } else if ($field == 'gst_exempt') {
            $text_cell .= '<b>'.$field_list[$field].'</b>: '.($service[$field] == '1' ? 'Yes' : 'No').'<br />';
        } else {
            $text_cell .= '<b>'.$field_list[$field].'</b>: '.html_entity_decode($service[$field]).'<br />';
        }
    }
    $html .= $image_cell.'<td style="'.($image_cell == '' ? 'width:100%;' : 'width:70%;').'">'.$text_cell.'</td></tr></table>';
}

// $html .= '</table>';

$pdf->AddPage();
$pdf->setCellHeightRatio(1);
$pdf->writeHTML(utf8_encode('<form action="" method="POST">'.$html.'</form>'), true, false, true, false, '');

$today_date = date('Y-m-d H.i.s');
if(!file_exists('download')) {
    mkdir('download', 0777, true);
}
// echo $html;
$pdf->Output('download/services - '.$today_date.'.pdf', 'F');

echo '<script type="text/javascript">window.open("download/services - '.$today_date.'.pdf", "_blank"); </script>';
?>