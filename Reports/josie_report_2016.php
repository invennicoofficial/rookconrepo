<?php
/*
Client Listing
*/
include ('../include.php');
checkAuthorised('report');
include_once('../tcpdf/tcpdf.php');
error_reporting(0);

if (isset($_POST['printbdpdf'])) {
    DEFINE('REPORT_LOGO', get_config($dbc, 'report_logo'));
    DEFINE('REPORT_HEADER', html_entity_decode(get_config($dbc, 'report_header')));
    DEFINE('REPORT_FOOTER', html_entity_decode(get_config($dbc, 'report_footer')));

	class MYPDF extends TCPDF {

		public function Header() {
			//$image_file = WEBSITE_URL.'/img/Clinic-Ace-Logo-Final-250px.png';
            if(REPORT_LOGO != '') {
                $image_file = 'download/'.REPORT_LOGO;
                $this->Image($image_file, 10, 10, '', '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
            }
            $this->setCellHeightRatio(0.7);
            $this->SetFont('helvetica', '', 9);
            $footer_text = '<p style="text-align:right;">'.REPORT_HEADER.'</p>';
            $this->writeHTMLCell(0, 0, 0 , 5, $footer_text, 0, 0, false, "R", true);

            $this->SetFont('helvetica', '', 13);
            $footer_text = 'Beddington Summary - <b>2016-08-01</b> To <b>2016-12-31</b>';
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

    $html .= report_bd_summary($dbc, 'padding:3px; border:1px solid black;', '', '');

    $today_date = date('Y-m-d');
	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output('Download/bd_2016_summary_'.$today_date.'.pdf', 'F');
    ?>

	<script type="text/javascript" language="Javascript">
	window.open('Download/bd_2016_summary_<?php echo $today_date;?>.pdf', 'fullscreen=yes');
	</script>
    <?php
}

if (isset($_POST['printtcpdf'])) {
    DEFINE('REPORT_LOGO', get_config($dbc, 'report_logo'));
    DEFINE('REPORT_HEADER', html_entity_decode(get_config($dbc, 'report_header')));
    DEFINE('REPORT_FOOTER', html_entity_decode(get_config($dbc, 'report_footer')));

	class MYPDF extends TCPDF {

		public function Header() {
			//$image_file = WEBSITE_URL.'/img/Clinic-Ace-Logo-Final-250px.png';
            if(REPORT_LOGO != '') {
                $image_file = 'download/'.REPORT_LOGO;
                $this->Image($image_file, 10, 10, '', '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
            }
            $this->setCellHeightRatio(0.7);
            $this->SetFont('helvetica', '', 9);
            $footer_text = '<p style="text-align:right;">'.REPORT_HEADER.'</p>';
            $this->writeHTMLCell(0, 0, 0 , 5, $footer_text, 0, 0, false, "R", true);

            $this->SetFont('helvetica', '', 13);
            $footer_text = 'Thorncliffe Summary - <b>2016-03-01</b> To <b>2016-12-31</b>';
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

    $html .= report_tc_summary($dbc, 'padding:3px; border:1px solid black;', '', '');

    $today_date = date('Y-m-d');
	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output('Download/tc_2016_summary_'.$today_date.'.pdf', 'F');
    ?>

	<script type="text/javascript" language="Javascript">
	window.open('Download/tc_2016_summary_<?php echo $today_date;?>.pdf', 'fullscreen=yes');
	</script>
    <?php
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
        <br><br>
        <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

            <input type="hidden" name="report_type" value="<?php echo $_GET['type']; ?>">
            <input type="hidden" name="category" value="<?php echo $_GET['category']; ?>">

            <?php
                if($_SERVER['SERVER_NAME'] == 'ncbeddington.clinicace.com') {
                    echo '<button type="submit" name="printbdpdf" value="Print Report" class="btn brand-btn pull-right">Print Report</button>';
                    echo report_bd_summary($dbc, '', '', '');
                }

                if($_SERVER['SERVER_NAME'] == 'ncthorncliffe.clinicace.com') {
                    echo '<button type="submit" name="printtcpdf" value="Print Report" class="btn brand-btn pull-right">Print Report</button>';
                    echo report_tc_summary($dbc, '', '', '');
                }

            ?>
        </form>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>

<?php
function report_tc_summary($dbc, $table_style, $table_row_style, $grand_total_style) {

    $report_data .= '<table border="1px" class="table table-bordered" style="'.$table_style.'">';
    $report_data .= '<tr style="'.$table_row_style.'">
    <th width="20%"></th>
    <th width="20%">Sales</th>
    <th width="20%">Insurer A/R</th>
    <th width="20%">Patient A/R</th>
    <th width="20%">Clinic Master A/R</th>
    </tr>';

    $report_data .= '<tr nobr="true">';
////

    $report_data .= '<td>2016-03-01 : 2016-03-31</td>';

    $total_daily_sales_march = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(final_price) as `total_daily_sales` FROM invoice WHERE (invoice_date >= '2016-03-01' AND invoice_date <= '2016-03-31')"));


    $row_total_insurer_march = mysqli_query($dbc,"SELECT invoiceid, insurerid, insurance_payment, service_date, invoice_date FROM invoice WHERE (DATE(invoice_date) >= '2016-03-01' AND DATE(invoice_date) <= '2016-03-31') AND paid='Waiting on Insurer' AND insurance_payment != '#*#' ORDER BY invoiceid");

    $total_insurer_march = 0;
    while($row_report = mysqli_fetch_array($row_total_insurer_march)) {
        $insurerid = $row_report['insurerid'];
        $insurance_payment = $row_report['insurance_payment'].',';
        $ip1 = explode('#*#', $insurance_payment);
        $ip2 = explode(',', $ip1[1]);
        $exeid = explode(',', $insurerid);
        $m = 0;
        foreach($exeid as $insid) {
            if($insid != '') {
                $total_insurer_march += $ip2[$m];
                $m++;
            }
        }
    }

    $row_total_patient_march = mysqli_query($dbc,"SELECT invoiceid, patientid, payment_type, service_date, invoice_date FROM invoice WHERE (DATE(invoice_date) >= '2016-03-01' AND DATE(invoice_date) <= '2016-03-31') AND paid='No' AND payment_type IS NOT NULL AND payment_type LIKE '#*#%' ORDER BY patientid");

    $total_patient_march = 0;
    while($row_report = mysqli_fetch_array($row_total_patient_march)) {
        $payment_type = ltrim($row_report['payment_type'],'#*#');
        $total_patient_march += $payment_type;
    }

    $report_data .= '<td>'.number_format($total_daily_sales_march['total_daily_sales'], 2).'</td>';
    $report_data .= '<td>'.number_format($total_insurer_march, 2).'</td>';
    $report_data .= '<td>'.number_format($total_patient_march, 2).'</td>';
    $report_data .= '<td>-</td>';
    $report_data .= '</tr>';

    $report_data .= '<tr nobr="true">';
    $report_data .= '<td>2016-04-01 : 2016-04-30</td>';

    $total_daily_sales_april = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(final_price) as `total_daily_sales` FROM invoice WHERE (invoice_date >= '2016-04-01' AND invoice_date <= '2016-04-30')"));


    $row_total_insurer_april = mysqli_query($dbc,"SELECT invoiceid, insurerid, insurance_payment, service_date, invoice_date FROM invoice WHERE (DATE(invoice_date) >= '2016-04-01' AND DATE(invoice_date) <= '2016-04-30') AND paid='Waiting on Insurer' AND insurance_payment != '#*#' ORDER BY invoiceid");

    $total_insurer_april = 0;
    while($row_report = mysqli_fetch_array($row_total_insurer_april)) {
        $insurerid = $row_report['insurerid'];
        $insurance_payment = $row_report['insurance_payment'].',';
        $ip1 = explode('#*#', $insurance_payment);
        $ip2 = explode(',', $ip1[1]);
        $exeid = explode(',', $insurerid);
        $m = 0;
        foreach($exeid as $insid) {
            if($insid != '') {
                $total_insurer_april += $ip2[$m];
                $m++;
            }
        }
    }

    $row_total_patient_april = mysqli_query($dbc,"SELECT invoiceid, patientid, payment_type, service_date, invoice_date FROM invoice WHERE (DATE(invoice_date) >= '2016-04-01' AND DATE(invoice_date) <= '2016-04-30') AND paid='No' AND payment_type IS NOT NULL AND payment_type LIKE '#*#%' ORDER BY patientid");

    $total_patient_april = 0;
    while($row_report = mysqli_fetch_array($row_total_patient_april)) {
        $payment_type = ltrim($row_report['payment_type'],'#*#');
        $total_patient_april += $payment_type;
    }

    $report_data .= '<td>'.number_format($total_daily_sales_april['total_daily_sales'], 2).'</td>';
    $report_data .= '<td>'.number_format($total_insurer_april, 2).'</td>';
    $report_data .= '<td>'.number_format($total_patient_april, 2).'</td>';
    $report_data .= '<td>-</td>';
    $report_data .= '</tr>';

    $report_data .= '<tr nobr="true">';
    $report_data .= '<td>2016-05-01 : 2016-05-31</td>';

    $total_daily_sales_may = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(final_price) as `total_daily_sales` FROM invoice WHERE (invoice_date >= '2016-05-01' AND invoice_date <= '2016-05-31')"));

    $row_total_insurer_may = mysqli_query($dbc,"SELECT invoiceid, insurerid, insurance_payment, service_date, invoice_date FROM invoice WHERE (DATE(invoice_date) >= '2016-05-01' AND DATE(invoice_date) <= '2016-05-31') AND paid='Waiting on Insurer' AND insurance_payment != '#*#' ORDER BY invoiceid");

    $total_insurer_may = 0;
    while($row_report = mysqli_fetch_array($row_total_insurer_may)) {
        $insurerid = $row_report['insurerid'];
        $insurance_payment = $row_report['insurance_payment'].',';
        $ip1 = explode('#*#', $insurance_payment);
        $ip2 = explode(',', $ip1[1]);
        $exeid = explode(',', $insurerid);
        $m = 0;
        foreach($exeid as $insid) {
            if($insid != '') {
                $total_insurer_may += $ip2[$m];
                $m++;
            }
        }
    }

    $row_total_patient_may = mysqli_query($dbc,"SELECT invoiceid, patientid, payment_type, service_date, invoice_date FROM invoice WHERE (DATE(invoice_date) >= '2016-05-01' AND DATE(invoice_date) <= '2016-05-31') AND paid='No' AND payment_type IS NOT NULL AND payment_type LIKE '#*#%' ORDER BY patientid");

    $total_patient_may = 0;
    while($row_report = mysqli_fetch_array($row_total_patient_may)) {
        $payment_type = ltrim($row_report['payment_type'],'#*#');
        $total_patient_may += $payment_type;
    }

    $report_data .= '<td>'.number_format($total_daily_sales_may['total_daily_sales'], 2).'</td>';
    $report_data .= '<td>'.number_format($total_insurer_may, 2).'</td>';
    $report_data .= '<td>'.number_format($total_patient_may, 2).'</td>';
    $report_data .= '<td>-</td>';
    $report_data .= '</tr>';

    $report_data .= '<tr nobr="true">';
    $report_data .= '<td>2016-06-01 : 2016-06-30</td>';

    $total_daily_sales_june = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(final_price) as `total_daily_sales` FROM invoice WHERE (invoice_date >= '2016-06-01' AND invoice_date <= '2016-06-30')"));


    $row_total_insurer_june = mysqli_query($dbc,"SELECT invoiceid, insurerid, insurance_payment, service_date, invoice_date FROM invoice WHERE (DATE(invoice_date) >= '2016-06-01' AND DATE(invoice_date) <= '2016-06-30') AND paid='Waiting on Insurer' AND insurance_payment != '#*#' ORDER BY invoiceid");

    $total_insurer_june = 0;
    while($row_report = mysqli_fetch_array($row_total_insurer_june)) {
        $insurerid = $row_report['insurerid'];
        $insurance_payment = $row_report['insurance_payment'].',';
        $ip1 = explode('#*#', $insurance_payment);
        $ip2 = explode(',', $ip1[1]);
        $exeid = explode(',', $insurerid);
        $m = 0;
        foreach($exeid as $insid) {
            if($insid != '') {
                $total_insurer_june += $ip2[$m];
                $m++;
            }
        }
    }

    $row_total_patient_june = mysqli_query($dbc,"SELECT invoiceid, patientid, payment_type, service_date, invoice_date FROM invoice WHERE (DATE(invoice_date) >= '2016-06-01' AND DATE(invoice_date) <= '2016-06-30') AND paid='No' AND payment_type IS NOT NULL AND payment_type LIKE '#*#%' ORDER BY patientid");

    $total_patient_june = 0;
    while($row_report = mysqli_fetch_array($row_total_patient_june)) {
        $payment_type = ltrim($row_report['payment_type'],'#*#');
        $total_patient_june += $payment_type;
    }

    $report_data .= '<td>'.number_format($total_daily_sales_june['total_daily_sales'], 2).'</td>';
    $report_data .= '<td>'.number_format($total_insurer_june, 2).'</td>';
    $report_data .= '<td>'.number_format($total_patient_june, 2).'</td>';
    $report_data .= '<td>-</td>';
    $report_data .= '</tr>';

    $report_data .= '<tr nobr="true">';
    $report_data .= '<td>2016-07-01 : 2016-07-31</td>';

    $total_daily_sales_july = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(final_price) as `total_daily_sales` FROM invoice WHERE (invoice_date >= '2016-07-01' AND invoice_date <= '2016-07-31')"));

    $row_total_insurer_july = mysqli_query($dbc,"SELECT invoiceid, insurerid, insurance_payment, service_date, invoice_date FROM invoice WHERE (DATE(invoice_date) >= '2016-07-01' AND DATE(invoice_date) <= '2016-07-31') AND paid='Waiting on Insurer' AND insurance_payment != '#*#' ORDER BY invoiceid");

    $total_insurer_july = 0;
    while($row_report = mysqli_fetch_array($row_total_insurer_july)) {
        $insurerid = $row_report['insurerid'];
        $insurance_payment = $row_report['insurance_payment'].',';
        $ip1 = explode('#*#', $insurance_payment);
        $ip2 = explode(',', $ip1[1]);
        $exeid = explode(',', $insurerid);
        $m = 0;
        foreach($exeid as $insid) {
            if($insid != '') {
                $total_insurer_july += $ip2[$m];
                $m++;
            }
        }
    }

    $row_total_patient_july = mysqli_query($dbc,"SELECT invoiceid, patientid, payment_type, service_date, invoice_date FROM invoice WHERE (DATE(invoice_date) >= '2016-07-01' AND DATE(invoice_date) <= '2016-07-31') AND paid='No' AND payment_type IS NOT NULL AND payment_type LIKE '#*#%' ORDER BY patientid");

    $total_patient_july = 0;
    while($row_report = mysqli_fetch_array($row_total_patient_july)) {
        $payment_type = ltrim($row_report['payment_type'],'#*#');
        $total_patient_july += $payment_type;
    }

    $report_data .= '<td>'.number_format($total_daily_sales_july['total_daily_sales'], 2).'</td>';
    $report_data .= '<td>'.number_format($total_insurer_july, 2).'</td>';
    $report_data .= '<td>'.number_format($total_patient_july, 2).'</td>';
    $report_data .= '<td>-</td>';
    $report_data .= '</tr>';


//////
    $report_data .= '<tr nobr="true">';
    $report_data .= '<td>2016-08-01 : 2016-08-31</td>';

    $total_daily_sales_aug = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(final_price) as `total_daily_sales` FROM invoice WHERE (invoice_date >= '2016-08-01' AND invoice_date <= '2016-08-31')"));

    $row_total_insurer_aug = mysqli_query($dbc,"SELECT invoiceid, insurerid, insurance_payment, service_date, invoice_date FROM invoice WHERE (DATE(invoice_date) >= '2016-08-01' AND DATE(invoice_date) <= '2016-08-31') AND paid='Waiting on Insurer' AND insurance_payment != '#*#' ORDER BY invoiceid");

    $total_insurer_aug = 0;
    while($row_report = mysqli_fetch_array($row_total_insurer_aug)) {
        $insurerid = $row_report['insurerid'];
        $insurance_payment = $row_report['insurance_payment'].',';
        $ip1 = explode('#*#', $insurance_payment);
        $ip2 = explode(',', $ip1[1]);
        $exeid = explode(',', $insurerid);
        $m = 0;
        foreach($exeid as $insid) {
            if($insid != '') {
                $total_insurer_aug += $ip2[$m];
                $m++;
            }
        }
    }

    $row_total_patient_aug = mysqli_query($dbc,"SELECT invoiceid, patientid, payment_type, service_date, invoice_date FROM invoice WHERE (DATE(invoice_date) >= '2016-08-01' AND DATE(invoice_date) <= '2016-08-31') AND paid='No' AND payment_type IS NOT NULL AND payment_type LIKE '#*#%' ORDER BY patientid");

    $total_patient_aug = 0;
    while($row_report = mysqli_fetch_array($row_total_patient_aug)) {
        $payment_type = ltrim($row_report['payment_type'],'#*#');
        $total_patient_aug += $payment_type;
    }

    $report_data .= '<td>'.number_format($total_daily_sales_aug['total_daily_sales'], 2).'</td>';
    $report_data .= '<td>'.number_format($total_insurer_aug, 2).'</td>';
    $report_data .= '<td>'.number_format($total_patient_aug, 2).'</td>';
    $report_data .= '<td>-</td>';
    $report_data .= '</tr>';

    $report_data .= '<tr nobr="true">';

    $report_data .= '<td>2016-09-01 : 2016-09-30</td>';

    $total_daily_sales_sept = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(final_price) as `total_daily_sales` FROM invoice WHERE (invoice_date >= '2016-09-01' AND invoice_date <= '2016-09-30')"));

    $row_total_insurer_sept = mysqli_query($dbc,"SELECT invoiceid, insurerid, insurance_payment, service_date, invoice_date FROM invoice WHERE (DATE(invoice_date) >= '2016-09-01' AND DATE(invoice_date) <= '2016-09-30') AND paid='Waiting on Insurer' AND insurance_payment != '#*#' ORDER BY invoiceid");

    $total_insurer_sept = 0;
    while($row_report = mysqli_fetch_array($row_total_insurer_sept)) {
        $insurerid = $row_report['insurerid'];
        $insurance_payment = $row_report['insurance_payment'].',';
        $ip1 = explode('#*#', $insurance_payment);
        $ip2 = explode(',', $ip1[1]);
        $exeid = explode(',', $insurerid);
        $m = 0;
        foreach($exeid as $insid) {
            if($insid != '') {
                $total_insurer_sept += $ip2[$m];
                $m++;
            }
        }
    }

    $row_total_patient_sept = mysqli_query($dbc,"SELECT invoiceid, patientid, payment_type, service_date, invoice_date FROM invoice WHERE (DATE(invoice_date) >= '2016-09-01' AND DATE(invoice_date) <= '2016-09-30') AND paid='No' AND payment_type IS NOT NULL AND payment_type LIKE '#*#%' ORDER BY patientid");

    $total_patient_sept = 0;
    while($row_report = mysqli_fetch_array($row_total_patient_sept)) {
        $payment_type = ltrim($row_report['payment_type'],'#*#');
        $total_patient_sept += $payment_type;
    }

    $report_data .= '<td>'.number_format($total_daily_sales_sept['total_daily_sales'], 2).'</td>';
    $report_data .= '<td>'.number_format($total_insurer_sept, 2).'</td>';
    $report_data .= '<td>'.number_format($total_patient_sept, 2).'</td>';
    $report_data .= '<td>-</td>';
    $report_data .= '</tr>';


    $report_data .= '<tr nobr="true">';

    $report_data .= '<td>2016-10-01 : 2016-10-31</td>';

    $total_daily_sales_oct = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(final_price) as `total_daily_sales` FROM invoice WHERE (invoice_date >= '2016-10-01' AND invoice_date <= '2016-10-31')"));


    $row_total_insurer_oct = mysqli_query($dbc,"SELECT invoiceid, insurerid, insurance_payment, service_date, invoice_date FROM invoice WHERE (DATE(invoice_date) >= '2016-10-01' AND DATE(invoice_date) <= '2016-10-05') AND paid='Waiting on Insurer' AND insurance_payment != '#*#' ORDER BY invoiceid");

    $total_insurer_oct = 0;
    while($row_report = mysqli_fetch_array($row_total_insurer_oct)) {
        $insurerid = $row_report['insurerid'];
        $insurance_payment = $row_report['insurance_payment'].',';
        $ip1 = explode('#*#', $insurance_payment);
        $ip2 = explode(',', $ip1[1]);
        $exeid = explode(',', $insurerid);
        $m = 0;
        foreach($exeid as $insid) {
            if($insid != '') {
                $total_insurer_oct += $ip2[$m];
                $m++;
            }
        }
    }

    $total_new_insurer_oct = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(insurer_price) as `total_insurer_price` FROM invoice_insurer WHERE (DATE(invoice_date) >= '2016-10-06' AND DATE(invoice_date) <= '2016-10-31') AND paid != 'Yes' ORDER BY invoiceid"));


    $row_total_patient_oct = mysqli_query($dbc,"SELECT invoiceid, patientid, payment_type, service_date, invoice_date FROM invoice WHERE (DATE(invoice_date) >= '2016-10-01' AND DATE(invoice_date) <= '2016-10-05') AND paid='No' AND payment_type IS NOT NULL AND payment_type LIKE '#*#%' ORDER BY patientid");

    $total_patient_oct = 0;
    while($row_report = mysqli_fetch_array($row_total_patient_oct)) {
        $payment_type = ltrim($row_report['payment_type'],'#*#');
        $total_patient_oct += $payment_type;
    }

    $total_new_patient_oct = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(patient_price) as `total_patient_price` FROM invoice_patient WHERE (DATE(invoice_date) >= '2016-10-06' AND DATE(invoice_date) <= '2016-10-31') AND paid = 'On Account' ORDER BY invoiceid"));

    $report_data .= '<td>'.number_format($total_daily_sales_oct['total_daily_sales'], 2).'</td>';
    $report_data .= '<td>'.number_format($total_insurer_oct+$total_new_insurer_oct['total_insurer_price'], 2).'</td>';
    $report_data .= '<td>'.number_format($total_patient_oct+$total_new_patient_oct['total_patient_price'], 2).'</td>';
    $report_data .= '<td>-</td>';
    $report_data .= '</tr>';

    $report_data .= '<tr nobr="true">';

    $report_data .= '<td>2016-11-01 : 2016-11-30</td>';

    $total_daily_sales_nov = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(final_price) as `total_daily_sales` FROM invoice WHERE (invoice_date >= '2016-11-01' AND invoice_date <= '2016-11-30')"));

    $total_insurer_nov = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(insurer_price) as `total_insurer_price` FROM invoice_insurer WHERE (DATE(invoice_date) >= '2016-11-01' AND DATE(invoice_date) <= '2016-11-30') AND paid != 'Yes' ORDER BY invoiceid"));

    $total_patient_nov = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(patient_price) as `total_patient_price` FROM invoice_patient WHERE (DATE(invoice_date) >= '2016-11-01' AND DATE(invoice_date) <= '2016-11-30') AND paid = 'On Account' ORDER BY invoiceid"));

    $report_data .= '<td>'.number_format($total_daily_sales_nov['total_daily_sales'], 2).'</td>';
    $report_data .= '<td>'.number_format($total_insurer_nov['total_insurer_price'], 2).'</td>';
    $report_data .= '<td>'.number_format($total_patient_nov['total_patient_price'], 2).'</td>';
    $report_data .= '<td>-</td>';
    $report_data .= '</tr>';


//

    $report_data .= '<tr nobr="true">';

    $report_data .= '<td>2016-12-01 : 2016-12-31</td>';

    $total_daily_sales_dec = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(final_price) as `total_daily_sales` FROM invoice WHERE (invoice_date >= '2016-12-01' AND invoice_date <= '2016-12-31')"));

    $total_insurer_dec = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(insurer_price) as `total_insurer_price` FROM invoice_insurer WHERE (DATE(invoice_date) >= '2016-12-01' AND DATE(invoice_date) <= '2016-12-31') AND paid != 'Yes' ORDER BY invoiceid"));

    $total_patient_dec = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(patient_price) as `total_patient_price` FROM invoice_patient WHERE (DATE(invoice_date) >= '2016-12-01' AND DATE(invoice_date) <= '2016-12-31') AND paid = 'On Account' ORDER BY invoiceid"));

    $report_data .= '<td>'.number_format($total_daily_sales_dec['total_daily_sales'], 2).'</td>';
    $report_data .= '<td>'.number_format($total_insurer_dec['total_insurer_price'], 2).'</td>';
    $report_data .= '<td>'.number_format($total_patient_dec['total_patient_price'], 2).'</td>';
    $report_data .= '<td>-</td>';
    $report_data .= '</tr>';

//


    //$total_cm = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(amount_owing) as `total_cm` FROM insurer_account_receivables_cm WHERE paid IS NULL"));

    $total_cm = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(amount_owing) as `total_cm` FROM insurer_account_receivables_cm"));

    $report_data .= '<tr nobr="true">';

    $report_data .= '<td>Total</td>';

    $report_data .= '<td>'.number_format(($total_daily_sales_march['total_daily_sales']+$total_daily_sales_april['total_daily_sales']+$total_daily_sales_may['total_daily_sales']+$total_daily_sales_june['total_daily_sales']+$total_daily_sales_july['total_daily_sales']+$total_daily_sales_aug['total_daily_sales']+$total_daily_sales_sept['total_daily_sales']+$total_daily_sales_oct['total_daily_sales']+$total_daily_sales_nov['total_daily_sales']+$total_daily_sales_dec['total_daily_sales']), 2).'</td>';
    $report_data .= '<td>'.number_format(($total_insurer_march+$total_insurer_april+$total_insurer_may+$total_insurer_june+$total_insurer_july+$total_insurer_aug+$total_insurer_sept+$total_insurer_oct+$total_new_insurer_oct['total_insurer_price']+$total_insurer_nov['total_insurer_price']+$total_insurer_dec['total_insurer_price']), 2).'</td>';

    $report_data .= '<td>'.number_format(($total_patient_march+$total_patient_april+$total_patient_may+$total_patient_june+$total_patient_july+$total_patient_aug+$total_patient_sept+$total_patient_oct+$total_new_patient_oct['total_patient_price']+$total_patient_nov['total_patient_price']+$total_patient_dec['total_patient_price']), 2).'</td>';

    $report_data .= '<td>'.number_format($total_cm['total_cm'], 2).'</td>';
    $report_data .= '</tr>';

    $report_data .= '</table>';

    return $report_data;
}

function report_bd_summary($dbc, $table_style, $table_row_style, $grand_total_style) {

    $report_data .= '<table border="1px" class="table table-bordered" style="'.$table_style.'">';
    $report_data .= '<tr style="'.$table_row_style.'">
    <th width="20%"></th>
    <th width="20%">Sales</th>
    <th width="20%">Insurer A/R</th>
    <th width="20%">Patient A/R</th>
    <th width="20%">Clinic Master A/R</th>
    </tr>';

    $report_data .= '<tr nobr="true">';

    $report_data .= '<td>2016-08-01 : 2016-08-31</td>';

    $total_daily_sales_aug = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(final_price) as `total_daily_sales` FROM invoice WHERE (invoice_date >= '2016-08-01' AND invoice_date <= '2016-08-31')"));


    $row_total_insurer_aug = mysqli_query($dbc,"SELECT invoiceid, insurerid, insurance_payment, service_date, invoice_date FROM invoice WHERE (DATE(invoice_date) >= '2016-08-01' AND DATE(invoice_date) <= '2016-08-31') AND paid='Waiting on Insurer' AND insurance_payment != '#*#' ORDER BY invoiceid");

    $total_insurer_aug = 0;
    while($row_report = mysqli_fetch_array($row_total_insurer_aug)) {
        $insurerid = $row_report['insurerid'];
        $insurance_payment = $row_report['insurance_payment'].',';
        $ip1 = explode('#*#', $insurance_payment);
        $ip2 = explode(',', $ip1[1]);
        $exeid = explode(',', $insurerid);
        $m = 0;
        foreach($exeid as $insid) {
            if($insid != '') {
                $total_insurer_aug += $ip2[$m];
                $m++;
            }
        }
    }

    $row_total_patient_aug = mysqli_query($dbc,"SELECT invoiceid, patientid, payment_type, service_date, invoice_date FROM invoice WHERE (DATE(invoice_date) >= '2016-08-01' AND DATE(invoice_date) <= '2016-08-31') AND paid='No' AND payment_type IS NOT NULL AND payment_type LIKE '#*#%' ORDER BY patientid");

    $total_patient_aug = 0;
    while($row_report = mysqli_fetch_array($row_total_patient_aug)) {
        $payment_type = ltrim($row_report['payment_type'],'#*#');
        $total_patient_aug += $payment_type;
    }

    $report_data .= '<td>'.number_format($total_daily_sales_aug['total_daily_sales'], 2).'</td>';
    $report_data .= '<td>'.number_format($total_insurer_aug, 2).'</td>';
    $report_data .= '<td>'.number_format($total_patient_aug, 2).'</td>';
    $report_data .= '<td>-</td>';
    $report_data .= '</tr>';


    $report_data .= '<tr nobr="true">';

    $report_data .= '<td>2016-09-01 : 2016-09-30</td>';

    $total_daily_sales_sept = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(final_price) as `total_daily_sales` FROM invoice WHERE (invoice_date >= '2016-09-01' AND invoice_date <= '2016-09-30')"));

    $row_total_insurer_sept = mysqli_query($dbc,"SELECT invoiceid, insurerid, insurance_payment, service_date, invoice_date FROM invoice WHERE (DATE(invoice_date) >= '2016-09-01' AND DATE(invoice_date) <= '2016-09-30') AND paid='Waiting on Insurer' AND insurance_payment != '#*#' ORDER BY invoiceid");

    $total_insurer_sept = 0;
    while($row_report = mysqli_fetch_array($row_total_insurer_sept)) {
        $insurerid = $row_report['insurerid'];
        $insurance_payment = $row_report['insurance_payment'].',';
        $ip1 = explode('#*#', $insurance_payment);
        $ip2 = explode(',', $ip1[1]);
        $exeid = explode(',', $insurerid);
        $m = 0;
        foreach($exeid as $insid) {
            if($insid != '') {
                $total_insurer_sept += $ip2[$m];
                $m++;
            }
        }
    }

    $row_total_patient_sept = mysqli_query($dbc,"SELECT invoiceid, patientid, payment_type, service_date, invoice_date FROM invoice WHERE (DATE(invoice_date) >= '2016-09-01' AND DATE(invoice_date) <= '2016-09-30') AND paid='No' AND payment_type IS NOT NULL AND payment_type LIKE '#*#%' ORDER BY patientid");

    $total_patient_sept = 0;
    while($row_report = mysqli_fetch_array($row_total_patient_sept)) {
        $payment_type = ltrim($row_report['payment_type'],'#*#');
        $total_patient_sept += $payment_type;
    }

    $report_data .= '<td>'.number_format($total_daily_sales_sept['total_daily_sales'], 2).'</td>';
    $report_data .= '<td>'.number_format($total_insurer_sept, 2).'</td>';
    $report_data .= '<td>'.number_format($total_patient_sept, 2).'</td>';
    $report_data .= '<td>-</td>';
    $report_data .= '</tr>';


    $report_data .= '<tr nobr="true">';

    $report_data .= '<td>2016-10-01 : 2016-10-31</td>';

    $total_daily_sales_oct = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(final_price) as `total_daily_sales` FROM invoice WHERE (invoice_date >= '2016-10-01' AND invoice_date <= '2016-10-31')"));


    $row_total_insurer_oct = mysqli_query($dbc,"SELECT invoiceid, insurerid, insurance_payment, service_date, invoice_date FROM invoice WHERE (DATE(invoice_date) >= '2016-10-01' AND DATE(invoice_date) <= '2016-10-05') AND paid='Waiting on Insurer' AND insurance_payment != '#*#' ORDER BY invoiceid");

    $total_insurer_oct = 0;
    while($row_report = mysqli_fetch_array($row_total_insurer_oct)) {
        $insurerid = $row_report['insurerid'];
        $insurance_payment = $row_report['insurance_payment'].',';
        $ip1 = explode('#*#', $insurance_payment);
        $ip2 = explode(',', $ip1[1]);
        $exeid = explode(',', $insurerid);
        $m = 0;
        foreach($exeid as $insid) {
            if($insid != '') {
                $total_insurer_oct += $ip2[$m];
                $m++;
            }
        }
    }

    $total_new_insurer_oct = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(insurer_price) as `total_insurer_price` FROM invoice_insurer WHERE (DATE(invoice_date) >= '2016-10-06' AND DATE(invoice_date) <= '2016-10-31') AND paid != 'Yes' ORDER BY invoiceid"));


    $row_total_patient_oct = mysqli_query($dbc,"SELECT invoiceid, patientid, payment_type, service_date, invoice_date FROM invoice WHERE (DATE(invoice_date) >= '2016-10-01' AND DATE(invoice_date) <= '2016-10-05') AND paid='No' AND payment_type IS NOT NULL AND payment_type LIKE '#*#%' ORDER BY patientid");

    $total_patient_oct = 0;
    while($row_report = mysqli_fetch_array($row_total_patient_oct)) {
        $payment_type = ltrim($row_report['payment_type'],'#*#');
        $total_patient_oct += $payment_type;
    }

    $total_new_patient_oct = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(patient_price) as `total_patient_price` FROM invoice_patient WHERE (DATE(invoice_date) >= '2016-10-06' AND DATE(invoice_date) <= '2016-10-31') AND paid = 'On Account' ORDER BY invoiceid"));

    $report_data .= '<td>'.number_format($total_daily_sales_oct['total_daily_sales'], 2).'</td>';
    $report_data .= '<td>'.number_format($total_insurer_oct+$total_new_insurer_oct['total_insurer_price'], 2).'</td>';
    $report_data .= '<td>'.number_format($total_patient_oct+$total_new_patient_oct['total_patient_price'], 2).'</td>';
    $report_data .= '<td>-</td>';
    $report_data .= '</tr>';

    $report_data .= '<tr nobr="true">';

    $report_data .= '<td>2016-11-01 : 2016-11-30</td>';

    $total_daily_sales_nov = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(final_price) as `total_daily_sales` FROM invoice WHERE (invoice_date >= '2016-11-01' AND invoice_date <= '2016-11-30')"));

    $total_insurer_nov = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(insurer_price) as `total_insurer_price` FROM invoice_insurer WHERE (DATE(invoice_date) >= '2016-11-01' AND DATE(invoice_date) <= '2016-11-30') AND paid != 'Yes' ORDER BY invoiceid"));

    $total_patient_nov = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(patient_price) as `total_patient_price` FROM invoice_patient WHERE (DATE(invoice_date) >= '2016-11-01' AND DATE(invoice_date) <= '2016-11-30') AND paid = 'On Account' ORDER BY invoiceid"));

    $report_data .= '<td>'.number_format($total_daily_sales_nov['total_daily_sales'], 2).'</td>';
    $report_data .= '<td>'.number_format($total_insurer_nov['total_insurer_price'], 2).'</td>';
    $report_data .= '<td>'.number_format($total_patient_nov['total_patient_price'], 2).'</td>';
    $report_data .= '<td>-</td>';
    $report_data .= '</tr>';




//

    $report_data .= '<tr nobr="true">';

    $report_data .= '<td>2016-12-01 : 2016-12-31</td>';

    $total_daily_sales_dec = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(final_price) as `total_daily_sales` FROM invoice WHERE (invoice_date >= '2016-12-01' AND invoice_date <= '2016-12-31')"));

    $total_insurer_dec = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(insurer_price) as `total_insurer_price` FROM invoice_insurer WHERE (DATE(invoice_date) >= '2016-12-01' AND DATE(invoice_date) <= '2016-12-31') AND paid != 'Yes' ORDER BY invoiceid"));

    $total_patient_dec = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(patient_price) as `total_patient_price` FROM invoice_patient WHERE (DATE(invoice_date) >= '2016-12-01' AND DATE(invoice_date) <= '2016-12-31') AND paid = 'On Account' ORDER BY invoiceid"));

    $report_data .= '<td>'.number_format($total_daily_sales_dec['total_daily_sales'], 2).'</td>';
    $report_data .= '<td>'.number_format($total_insurer_dec['total_insurer_price'], 2).'</td>';
    $report_data .= '<td>'.number_format($total_patient_dec['total_patient_price'], 2).'</td>';
    $report_data .= '<td>-</td>';
    $report_data .= '</tr>';

//

    //$total_cm = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(amount_owing) as `total_cm` FROM insurer_account_receivables_cm WHERE paid IS NULL"));

    $total_cm = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(amount_owing) as `total_cm` FROM insurer_account_receivables_cm"));

    $report_data .= '<tr nobr="true">';

    $report_data .= '<td>Total</td>';

    $report_data .= '<td>'.number_format(($total_daily_sales_aug['total_daily_sales']+$total_daily_sales_sept['total_daily_sales']+$total_daily_sales_oct['total_daily_sales']+$total_daily_sales_nov['total_daily_sales']+$total_daily_sales_dec['total_daily_sales']+$total_daily_sales_march['total_daily_sales']+$total_daily_sales_april['total_daily_sales']+$total_daily_sales_may['total_daily_sales']+$total_daily_sales_june['total_daily_sales']+$total_daily_sales_july['total_daily_sales']), 2).'</td>';
    $report_data .= '<td>'.number_format(($total_insurer_aug+$total_insurer_sept+$total_insurer_oct+$total_new_insurer_oct['total_insurer_price']+$total_insurer_nov['total_insurer_price']+$total_insurer_dec['total_insurer_price']+$total_insurer_march+$total_insurer_april+$total_insurer_may+$total_insurer_june+$total_insurer_july), 2).'</td>';

    $report_data .= '<td>'.number_format(($total_patient_aug+$total_patient_sept+$total_patient_oct+$total_new_patient_oct['total_patient_price']+$total_patient_nov['total_patient_price']+$total_patient_dec['total_patient_price']+$total_patient_march+$total_patient_april+$total_patient_may+$total_patient_june+$total_patient_july), 2).'</td>';

    $report_data .= '<td>'.number_format($total_cm['total_cm'], 2).'</td>';
    $report_data .= '</tr>';

    $report_data .= '</table>';

    return $report_data;
}