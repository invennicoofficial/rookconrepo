<?php

    class MYPDF extends TCPDF {

        public function Header() {
            if(INVOICE_LOGO != '') {
                $image_file = 'download/'.INVOICE_LOGO;
                $this->Image($image_file, 10, 10, 60, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
            }
            $this->SetFont('helvetica', '', 8);
            $header_text = '';
            $this->writeHTMLCell(0, 0, '', '', $header_text, 0, 0, false, "L", "R",true);
        }

        // Page footer
        public function Footer() {
            // Position at 15 mm from bottom
            $this->SetY(-15);
            $this->SetFont('helvetica', 'I', 8);
            $footer_text = 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages().' printed on  '.date('m/d/y').' at '.date('g:i:s A');
            $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "L", true);
        }

    function createPDF()
    {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);




    //$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, false, false);
    $pdf->setFooterData(array(0,64,0), array(0,64,128));

    $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 9);

    $html = '';

    $html .= '
    <b>Invoice# : '.$invoiceid.'<br/>
    Invoice Date : '.$get_invoice['invoice_date'].'</b><br/><br/>
    Patient Information : </b><br/>'.
    $patients .'<br/>'.
    get_contact_phone($dbc, $get_invoice['patientid']) .'<br/><br/>
    <b>Invoice Details</b><br/><br/>
    ';

    $html .= '<table border="1px" class="table table-bordered" style="padding:3px; border:1px solid black;">
    <tr style="background-color:grey; color:black;">
        <th>Date of Service</th>
        <th>Description</th>
        <th>Provider Name & Registration Info</th>
        <th>Total Fee/Payment</th>
        <th>Your Portion</th>
    </tr>
    ';

    $html .= '<tr>
        <td>'.$get_invoice['service_date'].'</td>
        <td>'.$service.'</td>
        <td>'.$staff.', '.get_all_form_contact($dbc, $therapistsid, 'credential').';'.get_all_form_contact($dbc, $therapistsid, 'license').'</td>
        <td>$'.$_POST['total_price'].'</td>
        <td>$'.$insurer_price_pdf.'</td>
    </tr>';

    if($list_inventory_insures != '') {
        $html .= '<tr>
        <td>'.$get_invoice['service_date'].'</td>
        <td>'.$list_inventory_insures.'</td>
        <td>-<br></td>
        <td>$'.$inv_insurer_price.'</td>
        <td>$'.$inv_insurer_price.'</td>
        </tr>';
    }

    $html .= '</table><br/><br/>';
    $html .= $pdf_tax.'<br/>';
    $html .= 'Total Charges:  <b>$'. $insurer_price_pdf .'</b><br/>';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('Download/insuranceinvoice_'.$insurerid_pdf.'_'.$invoiceid.'.pdf', 'S');
    }
    }

    //Insurer Invoice