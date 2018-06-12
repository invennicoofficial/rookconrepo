<?php
/*
Client Listing
*/
include ('../include.php');
checkAuthorised('report');
include_once('../tcpdf/tcpdf.php');
error_reporting(0);

if (isset($_POST['printpdf'])) {
    $starttimepdf = $_POST['starttimepdf'];
    $endtimepdf = $_POST['endtimepdf'];

    DEFINE('START_DATE', $starttimepdf);
    DEFINE('END_DATE', $endtimepdf);
    DEFINE('REPORT_LOGO', get_config($dbc, 'report_logo'));
    DEFINE('REPORT_HEADER', html_entity_decode(get_config($dbc, 'report_header')));
    DEFINE('REPORT_FOOTER', html_entity_decode(get_config($dbc, 'report_footer')));

	class MYPDF extends TCPDF {

		public function Header() {
			//$image_file = WEBSITE_URL.'/img/Clinic-Ace-Logo-Final-250px.png';
            if(REPORT_LOGO != '') {
                $image_file = 'download/'.REPORT_LOGO;
                $this->Image($image_file, 10, 10, 80, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
            }
            $this->setCellHeightRatio(0.7);
            $this->SetFont('helvetica', '', 9);
            $footer_text = '<p style="text-align:right;">'.REPORT_HEADER.'</p>';
            $this->writeHTMLCell(0, 0, 0 , 5, $footer_text, 0, 0, false, "R", true);

            $this->SetFont('helvetica', '', 13);
            $footer_text = 'View Receivables by Invoice# <b>'.START_DATE.'</b> To <b>'.END_DATE.'</b>';
            $this->writeHTMLCell(0, 0, 0 , 35, $footer_text, 0, 0, false, "R", true);
		}

		// Page footer
		public function Footer() {
            $this->SetY(-24);
            $this->SetFont('helvetica', 'I', 9);
            $footer_text = '<span style="text-align:left;">'.REPORT_FOOTER.'</span>';
            $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "L", true);

			// Position at 15 mm from bottom
			$this->SetY(-15);
            $this->SetFont('helvetica', 'I', 9);
			$footer_text = '<span style="text-align:right;">Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages().' printed on '.date('Y-m-d H:i:s').'</span>';
			$this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "R", true);
    	}
	}

	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, false, false);
	$pdf->setFooterData(array(0,64,0), array(0,64,128));

	$pdf->SetMargins(PDF_MARGIN_LEFT, 50, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	$pdf->AddPage('L', 'LETTER');
    $pdf->SetFont('helvetica', '', 9);

    $html .= report_receivables($dbc, $starttimepdf, $endtimepdf, 'padding:3px; border:1px solid black;', '', '');

    $today_date = date('Y-m-d');
	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output('Download/receivables_'.$today_date.'.pdf', 'F');
    ?>

	<script type="text/javascript" language="Javascript">
	window.open('Download/receivables_<?php echo $today_date;?>.pdf', 'fullscreen=yes');
	</script>
    <?php
    $starttime = $starttimepdf;
    $endtime = $endtimepdf;
    } ?>

<script type="text/javascript">

</script>
</head>
<body>
<?php include_once ('../navigation.php');
?>

<div class="container triple-pad-bottom">
    <div class="row">
        <div class="col-md-12">

        <?php echo reports_tiles($dbc);  ?>

        <h3>This report is working for data till 2016-10-05.</h3>

        <br><br>
        <a href='1report_receivables.php?type=Daily'><button type="button" class="btn brand-btn mobile-block active_tab" >By Invoice#</button></a>&nbsp;&nbsp;
        <a href='1report_receivables_summary.php?type=Daily'><button type="button" class="btn brand-btn mobile-block" >Insurer Receivable Summary</button></a>&nbsp;&nbsp;
        <a href='1report_receivables_patient_summary.php?type=Daily'><button type="button" class="btn brand-btn mobile-block" >Patient Receivable Summary</button></a>&nbsp;&nbsp;
        <a href='1report_receivables_patient_paid_summary.php?type=Daily'><button type="button" class="btn brand-btn mobile-block" >Patient Paid Summary</button></a>&nbsp;&nbsp;

        <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
            <input type="hidden" name="report_type" value="<?php echo $_GET['type']; ?>">
            <input type="hidden" name="category" value="<?php echo $_GET['category']; ?>">

            <?php
            if (isset($_POST['search_email_submit'])) {
                $starttime = $_POST['starttime'];
                $endtime = $_POST['endtime'];
            }

            if($starttime == 0000-00-00) {
                $starttime = date('Y-m-d');
            }

            if($endtime == 0000-00-00) {
                $endtime = date('Y-m-d');
            }
            ?>
            <center><div class="form-group">
                From: <input name="starttime" type="text" class="datepicker" value="<?php echo $starttime; ?>">
                &nbsp;&nbsp;&nbsp;
                Until: <input name="endtime" type="text" class="datepicker" value="<?php echo $endtime; ?>">
            <button type="submit" name="search_email_submit" value="Search" class="btn brand-btn mobile-block">Submit</button></div></center>

            <input type="hidden" name="starttimepdf" value="<?php echo $starttime; ?>">
            <input type="hidden" name="endtimepdf" value="<?php echo $endtime; ?>">

            <button type="submit" name="printpdf" value="Print Report" class="btn brand-btn pull-right">Print Report</button>
            <br><br>

            <?php
                echo report_receivables($dbc, $starttime, $endtime, '', '', '');
            ?>

        </form>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>

<?php
function report_receivables($dbc, $starttime, $endtime, $table_style, $table_row_style, $grand_total_style) {

    $report_data .= '<h3>By Insurer</h3><table border="1px" class="table table-bordered" style="'.$table_style.'">';
    $report_data .= '<tr style="'.$table_row_style.'">
    <th width="10%">Invoice#</th>
    <th width="15%">Invoice Date</th>
    <th width="60%">Insurer</th>
    <th width="15%">Amount Receivable</th>
    </tr>';

    $report_service = mysqli_query($dbc,"SELECT invoiceid, insurerid, insurance_payment, service_date, invoice_date FROM invoice WHERE (DATE(invoice_date) >= '".$starttime."' AND DATE(invoice_date) <= '".$endtime."') AND paid='Waiting on Insurer' AND insurance_payment != '#*#' ORDER BY invoiceid");

    $amt_to_bill = 0;
    while($row_report = mysqli_fetch_array($report_service)) {
        $insurer_price = $row_report['insurer_price'];
        $invoiceid = $row_report['invoiceid'];
        $patientid = get_all_from_invoice($dbc, $invoiceid, 'patientid');
        $insurerid = $row_report['insurerid'];
        $insurance_payment = $row_report['insurance_payment'].',';
        $ip1 = explode('#*#', $insurance_payment);
        $ip2 = explode(',', $ip1[1]);

        $exeid = explode(',', $insurerid);

        $m = 0;
        foreach($exeid as $insid) {
            if($insid != '') {
                $report_data .= '<tr nobr="true">';
                $report_data .= '<td>#'.$invoiceid.'</td>';
                $report_data .= '<td>'.$row_report['invoice_date'].'</td>';
                //$report_data .= '<td>'.$row_report['service_date'].'</td>';
                $report_data .= '<td>'.get_all_form_contact($dbc, $insid, 'name').'</td>';
                $report_data .= '<td>$'.$ip2[$m].'</td>';
                $report_data .= '</tr>';

                $amt_to_bill += $ip2[$m];
                $m++;
            }
        }
    }

    $report_data .= '<tr nobr="true">';
    $report_data .= '<td>Total</td><td></td><td></td><td>$'.number_format($amt_to_bill, 2).'</td>';
    $report_data .= "</tr>";
    $report_data .= '</table><br>';

    $report_data .= '<h3>By Patient</h3><table border="1px" class="table table-bordered" style="'.$table_style.'">';
    $report_data .= '<tr style="'.$table_row_style.'">
    <th width="15%">Invoice#</th>
    <th width="15%">Invoice Date</th>
    <th width="15%">Service Date</th>
    <th width="40%">Patient</th>
    <th width="15%">Amount Receivable</th>
    </tr>';

    $report_service = mysqli_query($dbc,"SELECT invoiceid, patientid, payment_type, service_date, invoice_date FROM invoice WHERE (DATE(invoice_date) >= '".$starttime."' AND DATE(invoice_date) <= '".$endtime."') AND paid='No' AND payment_type IS NOT NULL AND payment_type LIKE '#*#%' ORDER BY patientid");

    $amt_to_bill_by_patient = 0;
    while($row_report = mysqli_fetch_array($report_service)) {

        $invoiceid = $row_report['invoiceid'];
        $payment_type = ltrim($row_report['payment_type'],'#*#');

        $report_data .= '<tr nobr="true">';
        $report_data .= '<td>#'.$invoiceid.'</td>';
        $report_data .= '<td>'.$row_report['invoice_date'].'</td>';
        $report_data .= '<td>'.$row_report['service_date'].'</td>';
        $report_data .= '<td>'.get_contact($dbc, $row_report['patientid']).'</td>';
        $report_data .= '<td>$'.$payment_type.'</td>';
        $report_data .= '</tr>';
        $amt_to_bill_by_patient += $payment_type;
    }
    $report_data .= '<tr nobr="true">';
    $report_data .= '<td>Total</td><td></td><td></td><td></td><td>$'.number_format($amt_to_bill_by_patient, 2).'</td>';
    $report_data .= "</tr>";
    $report_data .= '</table><br>';

    /*

    $report_data .= '<h3>By Insurer</h3><table border="1px" class="table table-bordered" style="'.$table_style.'">';
    $report_data .= '<tr style="'.$table_row_style.'">
    <th width="10%">Invoice#</th>
    <th width="15%">Invoice Date</th>
    <th width="60%">Insurer</th>
    <th width="15%">Amount Receivable</th>
    </tr>';

    //$report_service = mysqli_query($dbc,"SELECT invoiceid, insurerid, insurance_payment, service_date, invoice_date FROM invoice WHERE (DATE(invoice_date) >= '".$starttime."' AND DATE(invoice_date) <= '".$endtime."') AND paid='Waiting on Insurer' AND insurance_payment != '#*#' ORDER BY insurerid");

    $report_service = mysqli_query($dbc,"SELECT * FROM invoice_insurer WHERE (DATE(invoice_date) >= '".$starttime."' AND DATE(invoice_date) <= '".$endtime."') AND paid!='Yes' ORDER BY invoiceid");

    $amt_to_bill = 0;
    while($row_report = mysqli_fetch_array($report_service)) {
        $insurer_price = $row_report['insurer_price'];
        $invoiceid = $row_report['invoiceid'];
        $patientid = get_all_from_invoice($dbc, $invoiceid, 'patientid');
        $insurerid = $row_report['insurerid'];

        $report_data .= '<tr nobr="true">';
        $report_data .= '<td>#'.$invoiceid.'</td>';
        $report_data .= '<td>'.$row_report['invoice_date'].'</td>';
        //$report_data .= '<td>'.$row_report['service_date'].'</td>';
        $report_data .= '<td>'.get_all_form_contact($dbc, $insurerid, 'name').'</td>';
        $report_data .= '<td>$'.$insurer_price.'</td>';
        $report_data .= '</tr>';
        $amt_to_bill += $insurer_price;
    }

    $report_data .= '<tr nobr="true">';
    $report_data .= '<td>Total</td><td></td><td></td><td>$'.number_format($amt_to_bill, 2).'</td>';
    $report_data .= "</tr>";
    $report_data .= '</table><br>';

    $report_data .= '<h3>By Patient</h3><table border="1px" class="table table-bordered" style="'.$table_style.'">';
    $report_data .= '<tr style="'.$table_row_style.'">
    <th width="15%">Invoice#</th>
    <th width="15%">Invoice Date</th>
    <th width="15%">Service Date</th>
    <th width="40%">Patient</th>
    <th width="15%">Amount Receivable</th>
    </tr>';

    $report_service = mysqli_query($dbc,"SELECT invoiceid, patientid, payment_type, service_date, invoice_date FROM invoice WHERE (DATE(invoice_date) >= '".$starttime."' AND DATE(invoice_date) <= '".$endtime."') AND paid='No' AND final_price IS NOT NULL ORDER BY patientid");

    $amt_to_bill = 0;
    while($row_report = mysqli_fetch_array($report_service)) {

        $invoiceid = $row_report['invoiceid'];
        $payment_type = ltrim($row_report['payment_type'],'#*#');

        $report_data .= '<tr nobr="true">';
        $report_data .= '<td>#'.$invoiceid.'</td>';
        $report_data .= '<td>'.$row_report['invoice_date'].'</td>';
        $report_data .= '<td>'.$row_report['service_date'].'</td>';
        $report_data .= '<td>'.get_contact($dbc, $row_report['patientid']).'</td>';
        $report_data .= '<td>$'.$payment_type.'</td>';
        $report_data .= '</tr>';
        $amt_to_bill += $payment_type;
    }
    $report_data .= '<tr nobr="true">';
    $report_data .= '<td>Total</td><td></td><td></td><td></td><td>$'.number_format($amt_to_bill, 2).'</td>';
    $report_data .= "</tr>";
    $report_data .= '</table><br>';

    */

    return $report_data;
}

?>