<?php
$match_business = '';
if(!empty(MATCH_CONTACTS)) {
    $match_business = " AND `tickets`.`businessid` IN (".MATCH_CONTACTS.")";
}
include('field_list.php');
include_once('../tcpdf/tcpdf.php');
//error_reporting(E_ALL);
//set_time_limit(9000);
ini_set('max_execution_time',5000);
ini_set('memory_limit', '512M');
//echo ini_get('max_execution_time'); exit;
//echo ini_get('memory_limit'); exit;
//echo ini_get('pcre.backtrack_limit');exit;

$category = $_POST['category_export'];
$template = $_POST['template'];
$style_settings = $_POST['pdf_styling'];
$limit_rows = $_POST['limit_rows'];
if($limit_rows < 0 || empty($limit_rows)) {
    $limit_rows = 0;
}

if(!empty($template)) {
    $query_template = "SELECT GROUP_CONCAT(`heading_name`) as field_list FROM `inventory_templates_headings` WHERE `template_id` = '$template' AND `deleted` = 0 ORDER BY `sort_order` ASC";
    $result_template = mysqli_fetch_assoc(mysqli_query($dbc, $query_template));
    $fields = $result_template['field_list'];
} else {
    if(empty($category)) {
        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT inventory_dashboard FROM field_config_inventory WHERE (tab='".$category."' OR tab='".$_GET['category']."') AND accordion IS NULL UNION (SELECT `inventory_dashboard` FROM `field_config_inventory` WHERE `inventory_dashboard` IS NOT NULL ORDER BY IF(`tab`='Uncategorized','',IF(`tab`='','Z',`tab`)))"));
        $value_config = explode(',',$get_field_config['inventory_dashboard']);
    } else {
        $get_field_config = mysqli_fetch_assoc(mysqli_query ( $dbc, "SELECT `inventory_dashboard` FROM `field_config_inventory` WHERE `tab`='Top' UNION (SELECT `inventory_dashboard` FROM `field_config_inventory` WHERE `inventory_dashboard` IS NOT NULL ORDER BY IF(`tab`='Uncategorized','',IF(`tab`='','Z',`tab`)))"));
    }
    $value_config = explode(',',$get_field_config['inventory_dashboard']);
    $fields = [];
    foreach($field_list as $key => $field) {
        if(in_array($field, $value_config)) {
            $fields[] = $key;
        }
    }
    foreach($ticket_field_list as $key => $field) {
        if(in_array($field[0], $value_config)) {
            $fields[] = 'ticket#*#'.$key;
        }
    }
    $fields = implode(',', $fields);
    // $fields = implode(',', array_keys($field_list));
}
$fields_select = explode(',', $fields);
foreach($fields_select as $key => $field) {
    if(strpos($field, 'ticket#*#') !== FALSE) {
        unset($fields_select[$key]);
    } else {
        $fields_select[$key] = trim($field,'#');
    }
}
$fields_select = implode('`,`inventory`.`', $fields_select);

if($category == '3456780123456971230' || empty($category)) {
    $category_query = '';
} else {
    $category_query = "AND `inventory`.`category` = '$category'";
}

$query_inventory = "SELECT `inventory`.`inventoryid`, `inventory`.`$fields_select`, `tickets`.`ticket_label`, `tickets`.`purchase_order`, `ticket_attached`.`po_num` FROM `inventory` LEFT JOIN `ticket_attached` ON `ticket_attached`.`src_table`='inventory' AND `ticket_attached`.`deleted`=0 AND `ticket_attached`.`item_id`=`inventory`.`inventoryid` LEFT JOIN `tickets` ON `ticket_attached`.`ticketid`=`tickets`.`ticketid` WHERE `inventory`.`deleted` = 0 $match_business $category_query GROUP BY `inventory`.`inventoryid` ORDER BY `inventoryid` ASC";

if($limit_rows > 0) {
    $query_inventory = "SELECT * FROM (".$query_inventory.") `inventory` LIMIT $limit_rows";
 }
$result_inventory = mysqli_query($dbc, $query_inventory);

$select_pdf_settings = mysqli_fetch_assoc(mysqli_query($dbc, "select * from inventory_pdf_setting where style = '$style_settings'"));
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

$select_header_pdf_settings = mysqli_fetch_assoc(mysqli_query($dbc, "select * from inventory_pdf_setting where style = '$style_settings' AND setting_type = 'header'"));
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

$select_footer_pdf_settings = mysqli_fetch_assoc(mysqli_query($dbc, "select * from inventory_pdf_setting where style = '$style_settings' AND setting_type = 'footer'"));
if(!empty($select_footer_pdf_settings)) {
    $file_name = $select_footer_pdf_settings['file_name'];

    $font_footer_size = $select_footer_pdf_settings['font_size'];
    $font_footer_type = $select_footer_pdf_settings['font_type'];
    $font_footer = $select_footer_pdf_settings['font'];

    $pdf_footer_logo = $select_footer_pdf_settings['pdf_logo'];
    $pdf_footer_logo_align = $select_header_pdf_settings['alignment'];

    $footer_color = $select_footer_pdf_settings['pdf_color'];
    $footer_text = $select_footer_pdf_settings['text'];
}

$select_main_pdf_settings = mysqli_fetch_assoc(mysqli_query($dbc, "select * from inventory_pdf_setting where style = '$style_settings' AND setting_type = 'main'"));
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

$color = empty($color) ? "#000000" : $color;
$units = empty($units) ? "8" : $units;
$page_ori = empty($page_ori) ? "Portrait" : $page_ori;

$file_name = empty($file_name) ? "" : $file_name;

$font_heading_size = empty($font_heading_size) ? "8" : $font_heading_size;
$font_heading_type = empty($font_heading_type) ? "" : $font_heading_type;
$font_heading = empty($font_heading) ? "times" : $font_heading;

$font_main_heading_size = empty($font_main_heading_size) ? "8" : $font_main_heading_size;
$font_main_heading_type = empty($font_main_heading_type) ? "" : $font_main_heading_type;
$font_main_heading = empty($font_main_heading) ? "times" : $font_main_heading;

$font_main_body_size = empty($font_main_body_size) ? "8" : $font_main_body_size;
$font_main_body_type = empty($font_main_body_type) ? "" : $font_main_body_type;
$font_main_body = empty($font_main_body) ? "times" : $font_main_body;

$font_footer_size = empty($font_footer_size) ? "8" : $font_footer_size;
$font_footer_type = empty($font_footer_type) ? "" : $font_footer_type;
$font_footer = empty($font_footer) ? "times" : $font_footer;

$pdf_header_logo = empty($pdf_header_logo) ? "" : $pdf_header_logo;
$pdf_footer_logo = empty($pdf_footer_logo) ? "" : $pdf_footer_logo;
$pdf_header_logo_align = empty($pdf_header_logo_align) ? "C" : $pdf_header_logo_align;
$pdf_footer_logo_align = empty($pdf_footer_logo_align) ? "C" : $pdf_footer_logo_align;

$margin_left = empty($margin_left) ? "10" : $margin_left;
$margin_right = empty($margin_right) ? "10" : $margin_right;
$margin_top = empty($margin_top) ? (!empty($pdf_header_logo) ? "30" : "10") : $margin_top;
$margin_header = empty($margin_header) ? "10" : $margin_header;
$margin_bottom = empty($margin_bottom) ? "10" : $margin_bottom;

$heading_color = empty($heading_color) ? "#000000" : $heading_color;
$main_body_color = empty($main_body_color) ? "#000000" : $main_body_color;
$main_heading_color = empty($main_heading_color) ? "#000000" : $main_heading_color;
$footer_color = empty($footer_color) ? "#000000" : $footer_color;

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
        border: 1px solid black;
    }
    th {
        font-family: $font_main_heading;
        font-style: $font_main_heading_type;
        font-size: $font_main_heading_size;
        color: $main_heading_color;
        border: 1px solid $heading_color;
    }
    td {
        font-family: $font_main_body;
        font-style: $font_main_body_type;
        font-size: $font_main_body_size;
        color: $main_body_color;
        border: 1px solid $heading_color;
    }
</style>
";

$html .= '<table cellspacing="1" cellpadding="2">';
$html .= '<tr>';
foreach ($fields as $field) {
    if(strpos($field, 'ticket#*#') !== FALSE) {
        $html .= '<th>'.$ticket_field_list[explode('ticket#*#',$field)[1]][1].'</th>';
    } else {
        $html .= '<th>'.$field_list[$field].'</th>';
    }
}
$html .= '</tr>';

$yesno_list = ['gst_exempt', 'include_in_so', 'include_in_po', 'include_in_pos', 'include_in_product', 'featured', 'new', 'sale', 'clearance'];
while($inventory = mysqli_fetch_assoc($result_inventory)) {
    $html .= '<tr>';
    foreach ($fields as $field) {
        if(strpos($field, 'ticket#*#') !== FALSE) {
            $value = $inventory[explode('ticket#*#',$field)[1]];
        } else if (in_array($field, $is_money)) {
            if($inventory[$field][0] == '$') {
                $inventory[$field] = trim($inventory[$field], '$');
            }
            $value = '$'.number_format($inventory[$field],2,'.','');
        } else if ($field == 'vendorid') {
            $value = get_contact($dbc, $inventory[$field]);
        } else if (in_array($field, $yesno_list)) {
            $value = $inventory[$field] == '1' ? 'Yes' : 'No';
        } else {
            $value = $inventory[$field];
        }
        //$value = 'test';
        $html .= '<td>'.$value.'</td>';
    }
    $html .= '</tr>';
}

$html .= '</table>';
$html = utf8_encode($html);
$html = utf8_decode($html);
$pdf->AddPage();
$pdf->setCellHeightRatio(1);
//$pdf->writeHTML(utf8_encode('<form action="" method="POST">'.$html.'</form>'), true, false, true, false, '');
$pdf->writeHTML($html, true, false, true, false, '');

$today_date = date('Y-m-d H.i.s');
$today_date = str_replace(' ', '_', $today_date);
if(!file_exists('download')) {
    mkdir('download', 0777, true);
}
$pdf->Output('download/inventory_'.$today_date.'.pdf', 'F');

echo '<script type="text/javascript">window.open("download/inventory_'.$today_date.'.pdf", "_blank"); </script>';
?>