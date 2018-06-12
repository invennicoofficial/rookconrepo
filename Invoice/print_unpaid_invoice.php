<?php

$invoice_checked = implode(',',$_POST['invoice_checked']);
$insurerid = $_POST['insurerid'];
$patientpdf = $_POST['patientpdf'];
$today_date = date('Y-m-d');

$ins_name = get_all_form_contact($dbc, $insurerid, 'name');
$address = get_address($dbc, $insurerid);
$patient_name = get_contact($dbc, $patientpdf);

$query_insert_invoice = "INSERT INTO `invoice_unpaid_report` (`patientid`, `insurerid`, `invoice_date`) VALUES ('$patientpdf', '$insurerid', '$today_date')";
$result_insert_invoice = mysqli_query($dbc, $query_insert_invoice);
$ui_invoiceid = mysqli_insert_id($dbc);

DEFINE('PATIENT', get_contact($dbc, $patientpdf));
DEFINE('INVOICE_LOGO', get_config($dbc, 'invoice_logo'));
DEFINE('INVOICE_HEADER', html_entity_decode(get_config($dbc, 'invoice_header')));
DEFINE('INVOICE_FOOTER', html_entity_decode(get_config($dbc, 'invoice_unpaid_footer')));

class MYPDF extends TCPDF {

    public function Header() {
        if(INVOICE_LOGO != '') {
            $image_file = 'download/'.INVOICE_LOGO;
            $this->Image($image_file, 10, 10, 80, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
        }
        $this->setCellHeightRatio(0.7);
        $this->SetFont('helvetica', '', 9);
        $footer_text = '<p style="text-align:right;">'.INVOICE_HEADER.'</p>';
        $this->writeHTMLCell(0, 0, 0 , 5, $footer_text, 0, 0, false, "R", true);
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        //$this->SetY(-10);
        //$this->SetFont('helvetica', 'I', 8);
        //$footer_text = 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages().' printed on  '.date('m/d/y').' at '.date('g:i:s A');
        //$this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "L", true);

        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', '', 11);
        // Page number
        $footer_text = INVOICE_FOOTER;
        $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "C", true);
    }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, false, false);
$pdf->setFooterData(array(0,64,0), array(0,64,128));

$pdf->SetMargins(PDF_MARGIN_LEFT, 50, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->AddPage();
$pdf->SetFont('helvetica', '', 11);

$report_data = '';

$report_data .= '<table style="padding:3px;width:100%" class="table table-bordered">
<tr style="padding:3px;" >
    <td>To : '.$ins_name.'<br>'.$address.'</td>
    <td>Invoice # : UI'.$ui_invoiceid.'<br>Date : '.$today_date.'</td>
</tr></table><br>';

$pdf->writeHTML($report_data, true, false, true, false, '');
$pdf->SetFont('helvetica', '', 9);

$report_data = '';
$report_data .= '<br>Client : '.$patient_name.'';

$result = mysqli_query($dbc,"SELECT * FROM invoice WHERE invoiceid IN ($invoice_checked) AND ((serviceid IS NOT NULL AND serviceid != ',') OR (inventoryid IS NOT NULL AND inventoryid != ',')) ORDER BY service_date");

$serviceid = 0;
$j = 0;
$gst_grand = 0;
$sub_total = 0;
$service_total = 0;
$invoice_service = ',';
$total_insurer_price = 0;
$insurer_price = 0;

while($row = mysqli_fetch_array($result))
{
    $invoiceid = $row['invoiceid'];
    $get_insurer_price =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(insurer_price) insurer_price FROM	invoice_insurer WHERE	invoiceid='$invoiceid' AND insurerid = '$insurerid'"));
    $insurer_price = $get_insurer_price['insurer_price'];
    $total_insurer_price += $insurer_price;

    $query_update_employee = "UPDATE `invoice_insurer` SET ui_invoiceid = '$ui_invoiceid' WHERE invoiceid='$invoiceid' AND insurerid = '$insurerid'";
    $result_update_employee = mysqli_query($dbc, $query_update_employee);

    if($serviceid != $row['serviceid']) {
        if($j != 0) {
            $report_data .= '<tr nobr="true">';
            $report_data .= '<td colspan="5">Sub Total</td><td>$'.number_format((float)$service_total, 2, '.', '').'</td>';
            $report_data .= '<td>$'.number_format((float)$gst_grand, 2, '.', '').'</td><td>$'.number_format((float)$sub_total, 2, '.', '').'</td>';
            $report_data .= '</tr>';
            $report_data .= '</table>';

            $sub_total = 0;
            $service_total = 0;
            $gst_grand = 0;
        }
        $report_data .= '<br><br><table border="1px" class="table table-bordered" style="padding:3px; border:1px solid black; width:100%; margin:0px;">';
        $report_data .= '<tr nobr="true">';
        $report_data .= '<th style="width:6%;">Inv#</th>
        <th style="width:11%;">Date</th>
        <th style="width:15%;">Professional</th>
        <th style="width:33%;">Service</th>
        <th style="width:4%;">Qty</th>
        <th style="width:14%;">Fee</th>
        <th style="width:7%;">GST</th>
        <th style="width:10%;">Insurer Total</th>
        </tr>';
        $serviceid = $row['serviceid'];
    }

    $patientid = $row['patientid'];

    $report_data .= '<tr nobr="true">';
    $report_data .= '<td>' . $row['invoiceid'] . '</td>';
    $report_data .= '<td>' . $row['service_date'] . '</td>';
    $report_data .= '<td>' . get_contact($dbc, $row['therapistsid']).'<br>'.get_all_form_contact($dbc, $row['therapistsid'], 'license'). '</td>';

    $report_data .= '<td>';
    $parts = explode(',', $row['serviceid']);
    $parts_ff = explode(',', $row['fee']);
    foreach ($parts as $key) {
        if($key != '') {
            $report_data .= get_all_from_service($dbc, $key, 'service_code').' : '.get_all_from_service($dbc, $key, 'heading').'<br><br>';
        }
    }

    $parts1 = explode(',', $row['inventoryid']);
    $invtype = explode(',', $row['invtype']);
    $k = 0;
    foreach ($parts1 as $key1) {
        if($key1 != '') {
            $report_data .= $invtype[$k].' : '.get_all_from_inventory($dbc, $key1 , 'name').'<br><br>';
        }
        $k++;
    }
    $report_data .= '</td>';

    $report_data .= '<td>';
    $parts = explode(',', $row['serviceid']);
    foreach ($parts as $key) {
        if($key != '') {
            $report_data .= '1<br><br><br>';
        }
    }
    $parts1 = explode(',', $row['inventoryid']);
    foreach ($parts1 as $key1) {
        if($key1 != '') {
            $report_data .= '1<br><br>';
        }
    }
    $report_data .= '</td>';

    $report_data .= '<td>';
    $parts = explode(',', $row['serviceid']);
    $parts_ff = explode(',', $row['fee']);
    //$total_service = 0;
    $l = 0;
    foreach ($parts as $key) {
        if($key != '') {
            $report_data .= '$'.number_format((float)$parts_ff[$l], 2, '.', '').'<br><br><br>';
            //$total_service += $parts_ff[$l];
            $service_total += $parts_ff[$l];
        }
        $l++;
    }
    $parts1 = explode(',', $row['inventoryid']);
    $sell_price = explode(',', $row['sell_price']);
    $k = 0;
    foreach ($parts1 as $key1) {
        if($key1 != '') {
            $report_data .= '$'.number_format((float)$sell_price[$k], 2, '.', '').'<br><br>';
            $service_total += $sell_price[$k];
        }
        $k++;
    }
    $report_data .= '</td>';

    //$report_data .= '<td>$0</td>';

    $report_data .= '<td>';
    $parts = explode(',', $row['serviceid']);
    $parts_ff = explode(',', $row['fee']);
    $total_service = 0;
    $n = 0;
    foreach ($parts as $key) {
        if($key != '') {
            $gst_exempt = get_all_from_service($dbc, $key, 'gst_exempt');
            if($gst_exempt == 1) {
                $report_data .= '$0<br><br><br>';
            } else {
                $report_data .= '$'.number_format((float)($parts_ff[$n]*0.05), 2, '.', '').'<br><br><br>';
                $gst_grand += $parts_ff[$n]*0.05;
            }
        }
        $n++;
    }

    $parts1 = explode(',', $row['inventoryid']);
    $sell_price = explode(',', $row['sell_price']);
    $k = 0;
    foreach ($parts1 as $key1) {
        if($key1 != '') {
            $sp = ($sell_price[$k]*0.05);
            $report_data .= '$'.number_format((float)$sp, 2, '.', '').'<br><br>';
            $gst_grand += $sp;
        }
        $k++;
    }
    $report_data .= '</td>';

    $report_data .= '<td>$'.$insurer_price;
    $all_insurer_price =	mysqli_query($dbc,"SELECT SUM(insurer_price) insurer_price, insurerid,  paid FROM	invoice_insurer WHERE	invoiceid='$invoiceid' AND insurerid != '$insurerid' GROUP BY `insurerid`, `paid`, `invoiceid`");

    while($row3 = mysqli_fetch_array($all_insurer_price)) {
        $report_data .= '<br><em>['.get_all_form_contact($dbc, $row3['insurerid'], 'name').' : $'. $row3['insurer_price'];
        if($row3['paid'] == 'Yes') {
            //$report_data .= 'Paid';
        } else {
            //$report_data .= 'Not Paid';
        }
        $report_data .= ']</em>';
    }

    $report_data .= '</td>';

    //$report_data .= '<td>$'.$insurer_price.'</td>';

    $sub_total += $insurer_price;


    $report_data .= '</tr>';
    $j++;
    $invoice_service .= $row['invoiceid'].',';
}

if($m == 0) {
    $report_data .= '<tr nobr="true">';
    $report_data .= '<td colspan="5">Sub Total</td><td>$'.number_format((float)$service_total, 2, '.', '').'</td>';
    $report_data .= '<td>$'.number_format((float)$gst_grand, 2, '.', '').'</td><td>$'.number_format((float)$sub_total, 2, '.', '').'</td>';
    $report_data .= '</tr>';
    $report_data .= '</table>';
}

$report_data .= '</table>';

$report_data .= '<br><br><table border="1px" class="table table-bordered" style="padding:3px; border:1px solid black; margin:0px;">';
$report_data .= '<tr nobr="true">';
$report_data .= '<td colspan="6" style="width:90%;">Insurer Invoice Total</td><td style="width:10%;">$'.number_format((float)$total_insurer_price, 2, '.', '').'</td>';
$report_data .= '</tr>';
$report_data .= '</table>';

$today_date = date('Y-m-d');
$pdf->writeHTML($report_data, true, false, true, false, '');
$pdf->Output('download/patientunpaid_'.$ui_invoiceid.'.pdf', 'F');

echo '<script type="text/javascript" language="Javascript">window.location.replace("unpaid_insurer_invoice.php");
    window.open("download/patientunpaid_'.$ui_invoiceid.'.pdf", "fullscreen=yes");
    </script>';
$search_user = $patientpdf;