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
            $footer_text = 'Sales Summary - <b>'.START_DATE.'</b> To <b>'.END_DATE.'</b>';
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

    $html .= report_sales_summary($dbc, $starttimepdf, $endtimepdf, 'padding:3px; border:1px solid black;', '', '');

    $today_date = date('Y-m-d');
	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output('Download/sales_summary_'.$today_date.'.pdf', 'F');
    ?>

	<script type="text/javascript" language="Javascript">
	window.open('Download/sales_summary_<?php echo $today_date;?>.pdf', 'fullscreen=yes');
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
                //echo '<a href="report_referral.php?referral=printpdf&starttime='.$starttime.'&endtime='.$endtime.'" class="btn brand-btn pull-right">Print Report</a></h4><br>';

                echo report_sales_summary($dbc, $starttime, $endtime, '', '', '');
            ?>
        </form>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>

<?php
function report_sales_summary($dbc, $starttime, $endtime, $table_style, $table_row_style, $grand_total_style) {

    /*

    $report_dd = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(insurer_price) as `all_dd` FROM invoice_insurer WHERE paid='Yes' AND (paid_date >= '".$starttime."' AND paid_date <= '".$endtime."')"));
    $dd = $report_dd['all_dd'];

/*
        $report_invoiced = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(final_price) as `final_price` FROM invoice WHERE (invoice_date >= '".$starttime."' AND invoice_date <= '".$endtime."')"));
        $total_invoiced = $report_invoiced['final_price'];

        $report_validation = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT group_concat(`payment_type` separator '*#*') as `all_payment` FROM invoice WHERE payment_type IS NOT NULL AND payment_type NOT LIKE '#*#%' AND (invoice_date >= '".$starttime."' AND invoice_date <= '".$endtime."')"));

        $payment = $report_validation['all_payment'];

        $payment = str_replace(',#*#', '#*#', $payment);
        $payment = str_replace(',*#*', '*#*', $payment);
        $payment = explode('*#*', $payment);

        $final_pay = array();
        $k = 0;
        foreach ($payment as $sel) {
            if (strpos($sel, ',') !== false) {
                $sep_sel = explode('#*#', $sel);
                $each_sep_pt = explode(',', $sep_sel[0]);
                $each_sep_pp = explode(',', $sep_sel[1]);
                $m = 0;
                foreach ($each_sep_pt as $value_each_sep_sel) {
                    $final_pay[][$value_each_sep_sel] = $each_sep_pp[$m];
                    $m++;
                }
            } else {
                $each_sel = explode('#*#', $sel);
                $final_pay[][$each_sel[0]] = $each_sel[1];
            }
            $k++;
        }

        $final_pay = call_user_func_array('array_merge_recursive', $final_pay);

        $total_daily_sales = 0;

        $report_data .= '<table border="1px" class="table table-bordered" style="'.$table_style.'">';
        $report_data .= '<tr style="'.$table_row_style.'">
        <th>Daily Payments</th>
        <th>Daily Summary</th>
        </tr>';

        $report_data .= '<tr nobr="true">';
        $report_data .= '<td>';

        if(count($final_pay['Master Card']) == 1) {
            $report_data .=  'Master Card : '.number_format($final_pay['Master Card'], 2);
            $total_daily_sales += $final_pay['Master Card'];
        } else {
            $report_data .=  'Master Card : '.number_format(array_sum($final_pay['Master Card']), 2);
            $total_daily_sales += array_sum($final_pay['Master Card']);
        }
        $report_data .=  '<br>';
        if(count($final_pay['Visa']) == 1) {
            $report_data .=  'Visa : '.number_format($final_pay['Visa'], 2);
            $total_daily_sales += $final_pay['Visa'];
        } else {
            $report_data .=  'Visa : '.number_format(array_sum($final_pay['Visa']), 2);
            $total_daily_sales += array_sum($final_pay['Visa']);
        }
        $report_data .=  '<br>';
        if(count($final_pay['Debit Card']) == 1) {
            $report_data .=  'Debit Card : '.number_format($final_pay['Debit Card'], 2);
            $total_daily_sales += $final_pay['Debit Card'];
        } else {
            $report_data .=  'Debit Card : '.number_format(array_sum($final_pay['Debit Card']), 2);
            $total_daily_sales += array_sum($final_pay['Debit Card']);
       }
        $report_data .=  '<br>';
        if(count($final_pay['Cash']) == 1) {
            $report_data .=  'Cash : '.number_format($final_pay['Cash'], 2);
            $total_daily_sales += $final_pay['Cash'];
        } else {
            $report_data .=  'Cash : '.number_format(array_sum($final_pay['Cash']), 2);
            $total_daily_sales += array_sum($final_pay['Cash']);
        }
        $report_data .=  '<br>';
        if(count($final_pay['Cheque']) == 1) {
            $report_data .=  'Cheque : '.number_format($final_pay['Cheque'], 2);
            $total_daily_sales += $final_pay['Cheque'];
        } else {
            $report_data .=  'Cheque : '.number_format(array_sum($final_pay['Cheque']), 2);
            $total_daily_sales += array_sum($final_pay['Cheque']);
        }
        $report_data .=  '<br>';
        if(count($final_pay['Amex']) == 1) {
            $report_data .=  'Amex : '.number_format($final_pay['Amex'], 2);
            $total_daily_sales += $final_pay['Amex'];
        } else {
            $report_data .=  'Amex : '.number_format(array_sum($final_pay['Amex']), 2);
            $total_daily_sales += array_sum($final_pay['Amex']);
        }
        $report_data .=  '<br>';
        if(count($final_pay['Gift Certificate Redeem']) == 1) {
            $report_data .=  'Gift Certificate Redeem : '.number_format($final_pay['Gift Certificate Redeem'], 2);
            $total_daily_sales += $final_pay['Gift Certificate Redeem'];
        } else {
            $report_data .=  'Gift Certificate Redeem : '.number_format(array_sum($final_pay['Gift Certificate Redeem']), 2);
            $total_daily_sales += array_sum($final_pay['Gift Certificate Redeem']);
        }
        $report_data .=  '<br>';
        if(count($final_pay['Pro-Bono']) == 1) {
            $report_data .=  'Pro-Bono : '.number_format($final_pay['Pro-Bono'], 2);
            $total_daily_sales += $final_pay['Pro-Bono'];
        } else {
            $report_data .=  'Pro-Bono : '.number_format(array_sum($final_pay['Pro-Bono']), 2);
            $total_daily_sales += array_sum($final_pay['Pro-Bono']);
        }

        $report_data .=  '<br>Direct Deposit : '.number_format($dd, 2);

        $total_daily_sales += $dd;

        $report_data .= '</td>';

        $report_data .= '<td>';
        //$report_data .= 'Amount To Bill = ' . $row_report['daily_to_bill'].'<br>';
        $report_data .= 'Invoiced Amount = ' . number_format($total_invoiced, 2).'<br>';
        $report_data .= 'Total Daily Sales = ' . number_format($total_daily_sales, 2).'<br>';
        //$report_data .= 'Payment Amount = -'. $row_report['daily_payment_amount'].'<br>';
        $report_data .= 'Daily A/R = '. number_format(($total_invoiced - $total_daily_sales), 2) .'<br>';
        //$report_data .= 'Revenue = '. ($row_report['daily_to_bill']+$row_report['daily_invoiced'] -$row_report['daily_payment_amount']) .'<br>';

        $report_data .= '</td>';

    $report_data .= '</table>';

*/
//


//
    /*

    $report_validation = mysqli_query($dbc, "SELECT SUM(`daily_to_bill`) AS daily_to_bill, SUM(`daily_invoiced`) AS daily_invoiced, SUM(`daily_payment_amount`) AS daily_payment_amount, SUM(`Master Card`) AS Master_Card, SUM(`Visa`) AS Visa, SUM(`Debit Card`) AS Debit_Card, SUM(`Cash`) AS Cash, SUM(`Cheque`) AS Cheque, SUM(`Amex`) AS Amex, SUM(`Direct Deposit`) AS Direct_Deposit, SUM(`Gift Certificate Redeem`) AS Gift_Certificate_Redeem, SUM(`gratuity`) AS gratuity FROM report_summary WHERE (DATE(today_date) >= '".$starttime."' AND DATE(today_date) <= '".$endtime."')");

    $report_data .= '<table border="1px" class="table table-bordered" style="'.$table_style.'">';
    $report_data .= '<tr style="'.$table_row_style.'"><th>Daily Sales</th>
    <th>Daily Payments</th>
    <th>Daily A/R</th>
    </tr>';

    while($row_report = mysqli_fetch_array($report_validation)) {
        $report_data .= '<tr nobr="true">';
        $report_data .= '<td>';
        $report_data .= 'To Bill = ' . $row_report['daily_to_bill'].'<br>';
        $report_data .= 'Invoiced = ' . $row_report['daily_invoiced'].'<br>';
        $report_data .= 'Total Daily Sales = ' . ($row_report['daily_to_bill']+$row_report['daily_invoiced']).'<br><br>';

        $report_data .= '</td>';

        $report_data .= '<td>';
        $report_data .= 'Cheque = ' . $row_report['Cheque'].'<br>';
        $report_data .= 'Cash = ' . $row_report['Cash'].'<br>';
        $report_data .= 'Debit Card = ' . $row_report['Debit_Card'].'<br>';
        $report_data .= 'MasterCard = ' . $row_report['Master_Card'].'<br>';
        $report_data .= 'Visa = ' . $row_report['Visa'].'<br>';
        $report_data .= 'Amex = ' . $row_report['Amex'].'<br>';
        $report_data .= 'Direct Deposit = ' . number_format($dd, 2).'<br>';

        $report_data .= 'Total Deposit Payment = ' . ($row_report['Cheque']+$row_report['Cash']+$row_report['Debit_Card']+$row_report['Master_Card']+$row_report['Visa']+$row_report['Amex']+number_format($dd, 2)).'<br><br>';

        $report_data .= 'Gift Certificate = ' . $row_report['Gift_Certificate_Redeem'].'<br>';
        $report_data .= 'Gratuity = ' . $row_report['gratuity'].'<br>';
        $report_data .= '</td>';

        $report_data .= '<td>';
        $report_data .= 'Amount To Bill = ' . $row_report['daily_to_bill'].'<br>';
        $report_data .= 'Invoiced Amount = ' . $row_report['daily_invoiced'].'<br>';
        $report_data .= 'Payment Amount = -'. $row_report['daily_payment_amount'].'<br>';
        $report_data .= 'Daily A/R = '. ($row_report['daily_to_bill']+$row_report['daily_invoiced'] -$row_report['daily_payment_amount']) .'<br>';

        $report_data .= '</td>';
        $report_data .= "</tr>";
    }
    $report_data .= '</table>';
    */

    $report_service = mysqli_query($dbc,"SELECT invoiceid, insurerid, insurance_payment, service_date, invoice_date, injuryid FROM invoice WHERE (DATE(invoice_date) >= '".$starttime."' AND DATE(invoice_date) <= '".$endtime."') AND paid != 'Yes' AND insurance_payment != '#*#' ORDER BY invoiceid");

    $cat_to_bill_by_insurer = '';
    $amt_to_bill_by_insurer = 0;
    $dataPoint = array();
    $k = 0;
    while($row_report = mysqli_fetch_array($report_service)) {
        $insurerid = $row_report['insurerid'];
        $insurance_payment = $row_report['insurance_payment'].',';
        $ip1 = explode('#*#', $insurance_payment);
        $ip2 = explode(',', $ip1[1]);
        $exeid = explode(',', $insurerid);

        $m = 0;
        $each_invoice = 0;
        foreach($exeid as $insid) {
            if($insid != '') {
                $amt_to_bill_by_insurer += $ip2[$m];
                $each_invoice += $ip2[$m];
                $m++;
            }
        }

        if($each_invoice != 0) {
            $it = get_all_from_injury($dbc, $row_report['injuryid'], 'injury_type');
            $dataPoint[$k][$it] = $each_invoice;
            $k++;
        }

    }

    $sumArray = array();
    foreach ($dataPoint as $k=>$subArray) {
      foreach ($subArray as $id=>$value) {
        $sumArray[$id]+=$value;
      }
    }

    foreach ($sumArray as $k=>$subArray) {
        if($k == '') {
            $k = 'Special/Extra<br>(Reports/Non Patients/Patients without injury)';
        }
        $cat_to_bill_by_insurer .= '&nbsp;&nbsp; - '.$k.' : $'.number_format($subArray, 2).'<br>';
    }


    // Insurer Paid
    $report_service = mysqli_query($dbc,"SELECT invoiceid, insurerid, insurance_payment, service_date, invoice_date, injuryid FROM invoice WHERE (DATE(invoice_date) >= '".$starttime."' AND DATE(invoice_date) <= '".$endtime."') AND paid = 'Yes' AND insurance_payment != '#*#' ORDER BY invoiceid");

    $cat_invoiced_insurer = '';
    $invoiced_insurer = 0;
    $dataPoint = array();
    $k = 0;
    $invoice_invoiced_insurer = '';
    while($row_report = mysqli_fetch_array($report_service)) {
        $insurerid = $row_report['insurerid'];
        $insurance_payment = $row_report['insurance_payment'].',';
        $ip1 = explode('#*#', $insurance_payment);
        $ip2 = explode(',', $ip1[1]);
        $exeid = explode(',', $insurerid);

        $m = 0;
        $each_invoice = 0;
        foreach($exeid as $insid) {
            if($insid != '') {
                $invoiced_insurer += $ip2[$m];
                $invoice_invoiced_insurer .= $row_report['invoiceid'].'<br>';
                $each_invoice += $ip2[$m];
                $m++;
            }
        }

        if($each_invoice != 0) {
            $it = get_all_from_injury($dbc, $row_report['injuryid'], 'injury_type');
            $dataPoint[$k][$it] = $each_invoice;
            $k++;
        }

    }

    $sumArray = array();
    foreach ($dataPoint as $k=>$subArray) {
      foreach ($subArray as $id=>$value) {
        $sumArray[$id]+=$value;
      }
    }

    foreach ($sumArray as $k=>$subArray) {
        if($k == '') {
            $k = 'Special/Extra<br>(Reports/Non Patients/Patients without injury)';
        }
        $cat_invoiced_insurer .= '&nbsp;&nbsp; - '.$k.' : $'.number_format($subArray, 2).'<br>';
    }

    //Insurer paid


    $report_service = mysqli_query($dbc,"SELECT invoiceid, patientid, payment_type, service_date, invoice_date, injuryid FROM invoice WHERE (DATE(invoice_date) >= '".$starttime."' AND DATE(invoice_date) <= '".$endtime."') AND payment_type IS NOT NULL AND payment_type LIKE '#*#%' ORDER BY patientid");

    $amt_to_bill_by_patient = 0;
    $cat_to_bill_by_patient = '';
    $dataPoint = array();
    $k = 0;

    while($row_report = mysqli_fetch_array($report_service)) {
        $payment_type = ltrim($row_report['payment_type'],'#*#');
        $amt_to_bill_by_patient += $payment_type;

        $it = get_all_from_injury($dbc, $row_report['injuryid'], 'injury_type');
        $dataPoint[$k][$it] = $payment_type;
        $k++;
    }

    $sumArray = array();
    foreach ($dataPoint as $k=>$subArray) {
      foreach ($subArray as $id=>$value) {
        $sumArray[$id]+=$value;
      }
    }

    foreach ($sumArray as $k=>$subArray) {
        if($k == '') {
            $k = 'Special/Extra<br>(Reports/Non Patients/Patients without injury)';
        }
        $cat_to_bill_by_patient .= '&nbsp;&nbsp; - '.$k.' : $'.number_format($subArray, 2).'<br>';
    }

    $report_gst = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(final_price) as `final_price`, SUM(total_price) as `total_price` FROM invoice WHERE (invoice_date >= '".$starttime."' AND invoice_date <= '".$endtime."')"));
    $invoiced_gst = ($report_gst['final_price']-$report_gst['total_price']);

    $result5 = mysqli_query($dbc, "SELECT pi.injury_type, inv.total_price, inv.final_price, SUM(inv.final_price-inv.total_price) AS total_gst FROM invoice inv, patient_injury pi WHERE (pi.injuryid = inv.injuryid) AND inv.injuryid != 0 AND pi.injury_type IS NOT NULL AND pi.injury_type !='' AND inv.total_price != inv.final_price AND (inv.invoice_date >= '".$starttime."' AND inv.invoice_date <= '".$endtime."') GROUP BY pi.injury_type");

    $cat_invoiced_gst = '';
    $total_all_gst = 0;
    $gst_array = array();
    while($row5 = mysqli_fetch_array($result5)) {
        $cat_injury_type = $row2['injury_type'];

        if($row5['injury_type'] == '') {
            $row5['injury_type'] = 'Special/Extra<br>(Reports/Non Patients/Patients without injury)';
        }
        $cat_invoiced_gst .= '&nbsp;&nbsp; - '.$row5['injury_type'].' : $'.number_format($row5['total_gst'], 2).'<br>';
        $gst_array[$row5['injury_type']] = $row5['total_gst'];
        $total_all_gst += $row5['total_gst'];
    }

    $special_extra_gst = $invoiced_gst-$total_all_gst;
    if($special_extra_gst != 0) {
        $cat_invoiced_gst .= '&nbsp;&nbsp; - Special/Extra<br>(Reports/Non Patients/Patients without injury) : $'.number_format($invoiced_gst-$total_all_gst, 2).'<br>';
        $gst_array['Special/Extra<br>(Reports/Non Patients/Patients without injury)'] = $special_extra_gst;
    }


    $report_service = mysqli_query($dbc,"SELECT invoiceid, patientid, payment_type, service_date, invoice_date, injuryid FROM invoice WHERE (DATE(invoice_date) >= '".$starttime."' AND DATE(invoice_date) <= '".$endtime."') AND payment_type IS NOT NULL AND payment_type NOT LIKE '#*#%' AND payment_type NOT LIKE ',#*#%' ORDER BY invoiceid");

    $invoiced_patients = 0;
    $cat_invoiced_patients = '';
    $dataPoint = array();
    $k = 0;
    while($row_report = mysqli_fetch_array($report_service)) {

        $arr = explode('#*#', $row_report['payment_type'].',');
        $pt = $arr[1];

        $each_invoice = 0;
        $pt1 = explode(',', $pt);
        foreach($pt1 as $pt2) {
            if($pt2 != '') {
                $invoiced_patients += $pt2;
                $each_invoice += $pt2;
            }
        }

        if($each_invoice != 0) {
            $it = get_all_from_injury($dbc, $row_report['injuryid'], 'injury_type');
            $dataPoint[$k][$it] = $each_invoice;
            $k++;
        }
    }

    $sumArray = array();
    foreach ($dataPoint as $k=>$subArray) {
      foreach ($subArray as $id=>$value) {
        $sumArray[$id]+=$value;
      }
    }

    foreach ($sumArray as $k=>$subArray) {
        if($k == '') {
            $k = 'Special/Extra<br>(Reports/Non Patients/Patients without injury)';
        }

        if (array_key_exists($k,$gst_array)) {
            $cat_invoiced_patients .= '&nbsp;&nbsp; - '.$k.' : $'.number_format(($subArray-$gst_array[$k]),2).'<br>';
        } else {
            $cat_invoiced_patients .= '&nbsp;&nbsp; - '.$k.' : $'.number_format($subArray, 2).'<br>';
        }
    }

    $report_service = mysqli_query($dbc,"SELECT payment_type FROM invoice WHERE (DATE(invoice_date) >= '".$starttime."' AND DATE(invoice_date) <= '".$endtime."') AND payment_type IS NOT NULL AND payment_type NOT LIKE '#*#%' AND payment_type NOT LIKE ',#*#%' ORDER BY invoiceid");

    $mc = 0;
    $v = 0;
    $dc = 0;
    $c = 0;
    $ch = 0;
    $a = 0;
    $g = 0;
    $p = 0;
    $other = 0;
    $total_dp = 0;
    while($row_report = mysqli_fetch_array($report_service)) {

        $arr = explode('#*#', $row_report['payment_type'].',');
        $pt = $arr[1];
        $ptname = $arr[0];

        $pt1 = explode(',', $pt);
        $ptn = explode(',', $ptname);
        $m = 0;
        foreach($pt1 as $pt2) {
            if($pt2 != '') {
                if($ptn[$m] == 'Master Card') {
                    $mc += $pt2;
                } elseif($ptn[$m] == 'Visa') {
                    $v += $pt2;
                } elseif($ptn[$m] == 'Debit Card') {
                    $dc += $pt2;
                } elseif($ptn[$m] == 'Cash') {
                    $c += $pt2;
                } elseif($ptn[$m] == 'Cheque') {
                    $ch += $pt2;
                } elseif($ptn[$m] == 'Amex') {
                    $a += $pt2;
                } elseif($ptn[$m] == 'Gift Certificate Redeem') {
                    $g += $pt2;
                } elseif($ptn[$m] == 'Pro-Bono') {
                    $p += $pt2;
                } else {
                    $other += $pt2;
                }
                $total_dp += $pt2;
            }
            $m++;
        }
    }

    $report_dd = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(insurer_price) as `all_dd` FROM invoice_insurer WHERE paid='Yes' AND (paid_date >= '".$starttime."' AND paid_date <= '".$endtime."')"));
    $dd = $report_dd['all_dd'];
    $total_dp += $dd;

    $total_daily_sales = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(final_price) as `total_daily_sales` FROM invoice WHERE (invoice_date >= '".$starttime."' AND invoice_date <= '".$endtime."')"));
    $total_sales = $total_daily_sales['total_daily_sales'];

    $report_data .= '<table border="1px" class="table table-bordered" style="'.$table_style.'">';
    $report_data .= '<tr style="'.$table_row_style.'"><th width="50%">Daily Sales</th>
    <th width="30%">Daily Payments</th>
    <th width="20%">Daily A/R</th>
    </tr>';

    $report_data .= '<tr nobr="true">';
    $report_data .= '<td width="50%">';
    $report_data .= 'Patients Amount To Bill = ' . number_format($amt_to_bill_by_patient, 2).'<br>'.$cat_to_bill_by_patient.'<br>';
    $report_data .= 'Insurer Amount To Bill = ' . number_format($amt_to_bill_by_insurer, 2).'<br>'.$cat_to_bill_by_insurer.'<br>';
    $report_data .= 'Patients Invoiced Amount = ' . number_format($invoiced_patients, 2).'<br>'.$cat_invoiced_patients.'<br>';

    //$invoice_invoiced_insurer
    $report_data .= 'Insurer Invoiced Amount = ' . number_format($invoiced_insurer, 2).'<br>'.$cat_invoiced_insurer.'<br>';
    $report_data .= 'Total GST(Total GST from all invoice) = ' . number_format($invoiced_gst, 2).'<br>'.$cat_invoiced_gst.'<br>';

    $report_data .= 'Unassigned amount = '. number_format($total_sales-$amt_to_bill_by_patient-$amt_to_bill_by_insurer-$invoiced_patients-$invoiced_insurer-$invoiced_gst, 2).'<br>';

    $report_data .= 'Total Daily Sales without GST = '. number_format($report_gst['total_price'], 2).'<br>';
    $report_data .= 'Total Daily Sales with GST = '. number_format($total_sales, 2).'<br><br>';

    $report_data .= '</td>';

    $report_data .= '<td width="30%">';
    $report_data .=  'Master Card : '.number_format($mc, 2).'<br>';
    $report_data .=  'Visa : '.number_format($v, 2).'<br>';
    $report_data .=  'Debit Card : '.number_format($dc, 2).'<br>';
    $report_data .=  'Cash : '.number_format($c, 2).'<br>';
    $report_data .=  'Cheque : '.number_format($ch, 2).'<br>';
    $report_data .=  'Amex : '.number_format($a, 2).'<br>';
    $report_data .=  'Gift Certificate Redeem : '.number_format($g, 2).'<br>';
    $report_data .=  'Pro-Bono : '.number_format($p, 2).'<br>';
    $report_data .=  'Other(Refund, Move to Insurer etc) : '.number_format($other, 2).'<br>';
    $report_data .=  'Direct Deposit(Work after 02/08) : '.number_format($dd, 2).'<br>';

    $report_data .=  '<br>Total Deposit Payment : '.number_format($total_dp, 2);

    $report_data .= '</td>';

    $report_data .= '<td width="20%">';
    $report_data .= 'Amount To Bill = ' . number_format(($amt_to_bill_by_insurer+$amt_to_bill_by_patient), 2).'<br>';
    $report_data .= 'Invoiced Amount = ' . number_format($invoiced_patients, 2).'<br>';
    $report_data .= '-Payment Amount = -'. number_format($total_dp, 2).'<br>';
    $report_data .= 'Daily A/R = '.number_format(($amt_to_bill_by_insurer+$amt_to_bill_by_patient+$invoiced_patients-$total_dp), 2) .'<br>';

    $report_data .= '</td>';
    $report_data .= '</tr>';

    $report_data .= '</table>';

    return $report_data;
}