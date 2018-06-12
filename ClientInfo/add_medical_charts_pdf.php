<?php
include_once('../tcpdf/tcpdf.php');

DEFINE('CLIENT_NAME', get_contact($dbc, $client));

class MYPDF extends TCPDF {
    public function Header() {
        $this->SetFont('helvetica', '', 30);
        $footer_text = '<p style="text-align:center; background-color: #516371; color:white; height:100px; ">Medical Chart - '.CLIENT_NAME.'</p>';
        $this->writeHTMLCell(0, 40, 15 , 15, $footer_text, 0, 0, false, "L", true);
    }

    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $footer_text = 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages();
        $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "L", true);
    }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, false, false);
$pdf->setFooterData(array(0,64,0), array(0,64,128));
$pdf->SetMargins(PDF_MARGIN_LEFT, 45, PDF_MARGIN_RIGHT);

$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->AddPage();
$pdf->SetFont('helvetica', '', 11);

$html = '';

//Bowel Movement
if(!empty($bowel_movement_id)) {
    $value = $config['settings']['Choose Fields for Bowel Movement'];

    $inputs = get_all_inputs($value['data']);

    foreach($inputs as $input) {
        $$input = '';
    }

    $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM `bowel_movement` WHERE `bowel_movement_id` = '$bowel_movement_id'"));

    foreach($inputs as $input) {
        $$input = $get_contact[$input];
    }
    $bowel_movement_id = $get_contact['bowel_movement_id'];
    $client = $_GET['contactid'];

    if(isset($value['config_field'])) {
        $get_field_config = @mysqli_fetch_assoc(mysqli_query($dbc,"SELECT ".$value['config_field']." FROM field_config"));
        $value_config = ','.$get_field_config[$value['config_field']].',';

        $html .= '<h2>Bowel Movement</h2>';
        $html .= '<table cellpadding="2">';
        $k = 0;
        foreach($value['data'] as $tab_name => $tabs) {
            foreach($tabs as $field) {
                if (strpos($value_config, ','.$field[2].',') !== FALSE && $field[2] != 'client') {
                    // $html .= ($k % 2 == 0) ? '</tr>' : '';
                    if ($k % 2 == 0) {
                        $html .= '<tr>';
                    }
                    if ($field[2] == 'staff') {
                        @$$field[2] = get_contact($dbc, @$$field[2]);
                    }
                    @$$field[2] = html_entity_decode(@$$field[2]);
                    @$$field[2] = ltrim(@$$field[2], '<p>');
                    @$$field[2] = rtrim(@$$field[2], '</p>');
                    $html .= '<td><b>'.$field[0].': </b>'.@$$field[2].'</td>';
                    if ($k % 2 == 1) {
                        $html .= '</tr>';
                    }
                    $k++;
                }
            }
        }
        if ($k % 2 == 1) {
            $html .= '</tr>';
        }
        $html .= '</table>';
    }
}

//Seizure Record
if(!empty($sezire_record_id)) {
    $value = $config['settings']['Choose Fields for Seizure Record'];

    $inputs = get_all_inputs($value['data']);

    foreach($inputs as $input) {
        $$input = '';
    }
    $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM `seizure_record` WHERE `seizure_record_id` = '$seizure_record_id'"));

    foreach($inputs as $input) {
        $$input = $get_contact[$input];
    }
    $seizure_record_id = $get_contact['seizure_record_id'];
    $client = $_GET['contactid'];

    if(isset($value['config_field'])) {
        $get_field_config = @mysqli_fetch_assoc(mysqli_query($dbc,"SELECT ".$value['config_field']." FROM field_config"));
        $value_config = ','.$get_field_config[$value['config_field']].',';

        $html .= '<h2>Seizure Record</h2>';
        $html .= '<table cellpadding="2">';
        $k = 0;
        foreach($value['data'] as $tab_name => $tabs) {
            foreach($tabs as $field) {
                if (strpos($value_config, ','.$field[2].',') !== FALSE && $field[2] != 'client') {
                    // $html .= ($k % 2 == 0) ? '</tr>' : '';
                    if ($k % 2 == 0) {
                        $html .= '<tr>';
                    }
                    if ($field[2] == 'staff') {
                        @$$field[2] = get_contact($dbc, @$$field[2]);
                    }
                    @$$field[2] = html_entity_decode(@$$field[2]);
                    @$$field[2] = ltrim(@$$field[2], '<p>');
                    @$$field[2] = rtrim(@$$field[2], '</p>');
                    $html .= '<td><b>'.$field[0].': </b>'.@$$field[2].'</td>';
                    if ($k % 2 == 1) {
                        $html .= '</tr>';
                    }
                    $k++;
                }
            }
        }
        if ($k % 2 == 1) {
            $html .= '</tr>';
        }
        $html .= '</table>';
    }
}

//Daily Water Temp
if(!empty($daily_water_temp_id)) {
    $value = $config['settings']['Choose Fields for Daily Water Temp'];

    $inputs = get_all_inputs($value['data']);

    foreach($inputs as $input) {
        $$input = '';
    }
    $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM `daily_water_temp` WHERE `daily_water_temp_id` = '$daily_water_temp_id'"));

    foreach($inputs as $input) {
        $$input = $get_contact[$input];
    }
    $daily_water_temp_id = $get_contact['daily_water_temp_id'];
    $client = $_GET['contactid'];

    if(isset($value['config_field'])) {
        $get_field_config = @mysqli_fetch_assoc(mysqli_query($dbc,"SELECT ".$value['config_field']." FROM field_config"));
        $value_config = ','.$get_field_config[$value['config_field']].',';

        $html .= '<h2>Daily Water Temp</h2>';
        $html .= '<table cellpadding="2">';
        $k = 0;
        foreach($value['data'] as $tab_name => $tabs) {
            foreach($tabs as $field) {
                if (strpos($value_config, ','.$field[2].',') !== FALSE && $field[2] != 'client') {
                    // $html .= ($k % 2 == 0) ? '</tr>' : '';
                    if ($k % 2 == 0) {
                        $html .= '<tr>';
                    }
                    if ($field[2] == 'staff') {
                        @$$field[2] = get_contact($dbc, @$$field[2]);
                    }
                    @$$field[2] = html_entity_decode(@$$field[2]);
                    @$$field[2] = ltrim(@$$field[2], '<p>');
                    @$$field[2] = rtrim(@$$field[2], '</p>');
                    $html .= '<td><b>'.$field[0].': </b>'.@$$field[2].'</td>';
                    if ($k % 2 == 1) {
                        $html .= '</tr>';
                    }
                    $k++;
                }
            }
        }
        if ($k % 2 == 1) {
            $html .= '</tr>';
        }
        $html .= '</table>';
    }
}

//Blood Glucose
if(!empty($blood_glucose_id)) {
    $value = $config['settings']['Choose Fields for Blood Glucose'];

    $inputs = get_all_inputs($value['data']);

    foreach($inputs as $input) {
        $$input = '';
    }
    $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM `blood_glucose` WHERE `blood_glucose_id` = '$blood_glucose_id'"));

    foreach($inputs as $input) {
        $$input = $get_contact[$input];
    }
    $blood_glucose_id = $get_contact['blood_glucose_id'];
    $client = $_GET['contactid'];

    if(isset($value['config_field'])) {
        $get_field_config = @mysqli_fetch_assoc(mysqli_query($dbc,"SELECT ".$value['config_field']." FROM field_config"));
        $value_config = ','.$get_field_config[$value['config_field']].',';

        $html .= '<h2>Blood Glucose</h2>';
        $html .= '<table cellpadding="2">';
        $k = 0;
        foreach($value['data'] as $tab_name => $tabs) {
            foreach($tabs as $field) {
                if (strpos($value_config, ','.$field[2].',') !== FALSE && $field[2] != 'client') {
                    // $html .= ($k % 2 == 0) ? '</tr>' : '';
                    if ($k % 2 == 0) {
                        $html .= '<tr>';
                    }
                    if ($field[2] == 'staff') {
                        @$$field[2] = get_contact($dbc, @$$field[2]);
                    }
                    @$$field[2] = html_entity_decode(@$$field[2]);
                    @$$field[2] = ltrim(@$$field[2], '<p>');
                    @$$field[2] = rtrim(@$$field[2], '</p>');
                    $html .= '<td><b>'.$field[0].': </b>'.@$$field[2].'</td>';
                    if ($k % 2 == 1) {
                        $html .= '</tr>';
                    }
                    $k++;
                }
            }
        }
        if ($k % 2 == 1) {
            $html .= '</tr>';
        }
        $html .= '</table>';
    }
}

$pdf->writeHTML($html, true, false, true, false, '');
$today_date = date('Y-m-d');
if(!empty($medical_chart_date)) {
    $today_date = $medical_chart_date;
}
$pdf->Output('download/medchart_'.$client.'_'.$today_date.'.pdf', 'F');

echo '<script type="text/javascript">window.open("download/medchart_'.$client.'_'.$today_date.'.pdf", "fullscreen=yes");</script>';

?>