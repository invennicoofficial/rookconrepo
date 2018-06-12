<?php
$vend_name = get_client($dbc, $vendorid);
$contact = get_staff($dbc, $vendorid);

$total_count = count($_POST['qty']);
$po_html = '';
for($emp_loop=0; $emp_loop<=$total_count; $emp_loop++) {
    if($_POST['qty'][$emp_loop] != '') {
        $po_html .= '<tr>
        <td style="border-right: 1px solid grey; border-top:1px solid grey;">' . ($emp_loop+1).'</td>
        <td style="border-right: 1px solid grey; border-top:1px solid grey;">' . $_POST['qty'][$emp_loop].'</td>
        <td style="border-right: 1px solid grey; border-top:1px solid grey;">' . $_POST['desc'][$emp_loop] .'</td>
        <td style="border-right: 1px solid grey; border-top:1px solid grey; ">'. $_POST['tag'][$emp_loop].'</td>
        <td style="border-right: 1px solid grey; border-top:1px solid grey; ">'. $_POST['detail'][$emp_loop].'</td>
        <td style="border-right: 1px solid grey; border-top:1px solid grey; ">$'. number_format((float)$_POST['price_per_unit'][$emp_loop], 2, '.', '').'</td>
        <td style="border-right: 1px solid grey; border-top:1px solid grey; ">$'.number_format((float)$_POST['each_cost'][$emp_loop], 2, '.', '').'</td>
        </tr>';
    }
}

DEFINE('PO_LOGO', get_config($dbc, 'purchase_order_logo'));
DEFINE('PO_FOOTER', get_config($dbc, 'purchase_order_footer'));
DEFINE('PO_ADDRESS', html_entity_decode(get_config($dbc, 'purchase_order_company_address')));

class MYPDF extends TCPDF {

    public function Header() {
        if(PO_LOGO != '') {
            $image_file = 'download/'.PO_LOGO;
			$this->Image($image_file, 10, 10, 51, '', '', '', 'T', false, 300, 'L', false, false, 0, false, false, false);
        }

        $this->SetFont('helvetica', '', 9);
        $footer_text = '<p style="text-align:right;">'.PO_ADDRESS.'</p>';
        $this->writeHTMLCell(0, 0, 0 , 5, $footer_text, 0, 0, false, "R", true);
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-25);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $footer_text = PO_FOOTER;
        $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "L", true);
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
$pdf->SetFont('helvetica', '', 9);

$html = '<table border="1">
            <tr>
                <td>Purchase Order : '.$fieldpoid.'</td>
                <td>Issue Date : '.$issue_date.'</td>
            </tr>
            <tr>
                <td>Contact : '.get_client($dbc, $businessid).'</td>
                <td>Revision : '.$revision.'</td>
            </tr>
        </table>';

//$html .= '<p style="text-align:right;">Box 2052, Sundre, AB, T0M 1X0<br>Phone: 403-638-4030<br>Fax: 403-638-4001<br>Email: info@highlandprojects.com<br><br></p>';

if($po_html != '') {
    $html .='
    <table  style="text-align:left; border:1px solid black;">
        <tr>
        <th style="border-right: 1px solid grey; text-align:center; width:20%;font-weight:bold;">ITEM</th>
        <th style="border-right: 1px solid grey; text-align:center; width:10%;font-weight:bold;">QTY</th>
        <th style="border-right: 1px solid grey; text-align:center; width:20%;font-weight:bold;">ITEM DETAIL</th>
        <th style="border-right: 1px solid grey; text-align:center; width:10%;font-weight:bold;">TAG</th>
        <th style="border-right: 1px solid grey; text-align:center; width:20%;font-weight:bold;">DETAIL</th>
        <th style="border-right: 1px solid grey; text-align:center; width:10%;font-weight:bold;">EACH</th>
        <th style="border-right: 1px solid grey; text-align:center; width:10%;font-weight:bold;">Line Total</th>
        </tr>';

        $html .= $po_html;

        $html .='<tr><td  style=" border-top:1px solid grey;font-weight:bold;" colspan="6">Ordered By : '.$created_by.'</td><td style="border-top:1px solid grey;font-weight:bold;">&nbsp;</td></tr>
        <tr><td  style=" border-top:1px solid grey;font-weight:bold;" colspan="6">&nbsp;</td><td style="border-top:1px solid grey;font-weight:bold;">&nbsp;</td></tr>
        <tr><td style="border-top:1px solid grey;font-weight:bold;" colspan="6">Sub Total</td><td style="border-top:1px solid grey;font-weight:bold;">$'.number_format((float)$_POST['cost'], 2, '.', '').'</td></tr>
        <tr><td  style=" border-top:1px solid grey;font-weight:bold;" colspan="6">Sales Tax</td><td style="border-top:1px solid grey;font-weight:bold;">'.'5%'.'</td></tr>
        <tr><td  style=" border-top:1px solid grey;font-weight:bold;" colspan="6">Total</td><td style="border-top:1px solid grey;font-weight:bold;">$'.number_format((float)$_POST['total_cost'], 2, '.', '').'</td></tr>
    </table>
    ';
}

$html .= '<br><br>General Details : '.html_entity_decode($description).'<br><br><br>';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('download/po_'.$fieldpoid.'.pdf', 'F');