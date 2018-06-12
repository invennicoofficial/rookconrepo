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
            $footer_text = 'Daily Deposit Summary - <b>'.START_DATE.'</b> To <b>'.END_DATE.'</b>';
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
	$pdf->Output('Download/daily_deposit_sales_summary_'.$today_date.'.pdf', 'F');
    ?>

	<script type="text/javascript" language="Javascript">
	window.open('Download/daily_deposit_sales_summary_<?php echo  $today_date;?>.pdf', 'fullscreen=yes');
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

        <?php echo  reports_tiles($dbc);  ?>
        <h3>This report is working for data till 2016-10-05.</h3>

        <br><br>
        <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

            <input type="hidden" name="report_type" value="<?php echo  $_GET['type']; ?>">
            <input type="hidden" name="category" value="<?php echo  $_GET['category']; ?>">

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

            <input type="hidden" name="starttimepdf" value="<?php echo  $starttime; ?>">
            <input type="hidden" name="endtimepdf" value="<?php echo  $endtime; ?>">

            <button type="submit" name="printpdf" value="Print Report" class="btn brand-btn pull-right">Print Report</button>
            <br><br>

            <?php
                //echo  '<a href="report_referral.php?referral=printpdf&starttime='.$starttime.'&endtime='.$endtime.'" class="btn brand-btn pull-right">Print Report</a></h4><br>';

                echo  report_sales_summary($dbc, $starttime, $endtime, '', '', '');
            ?>
        </form>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>

<?php
function report_sales_summary($dbc, $starttime, $endtime, $table_style, $table_row_style, $grand_total_style) {
    $report_data = '';

    $begin = new DateTime($starttime);
    $end = new DateTime($endtime);

    $end->modify('+1 day');

    $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);

    $report_data .=  '<table border="1px" class="table table-bordered" style="'.$table_style.'">';

    $report_data .=  '<tr style="'.$table_row_style.'">';
    $report_data .=  '<th width="18%">&nbsp;</th>';
    $report_data .=  '<th width="8%">Master Card</th>';
    $report_data .=  '<th width="8%">Visa</th>';
    $report_data .=  '<th width="8%">Debit Card</th>';
    $report_data .=  '<th width="8%">Cash</th>';
    $report_data .=  '<th width="8%">Cheque</th>';
    $report_data .=  '<th width="8%">Amex</th>';
    $report_data .=  '<th width="8%">Gift Certificate Redeem</th>';
    $report_data .=  '<th width="8%">Pro-Bono</th>';
    $report_data .=  '<th width="8%">Direct Deposit/Insurer</th>';
    $report_data .=  '<th width="10%">Total</th>';
    $report_data .=  '</tr>';

    $total1 =  0;
    $total2 =  0;
    $total3 =  0;
    $total4 =  0;
    $total5 =  0;
    $total6 =  0;
    $total7 =  0;
    $total8 =  0;
    $total9 =  0;
    $final_total = 0;

    foreach($daterange as $date) {
        $check_date = $date->format("Y-m-d") . "<br>";

        $report_dd = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(insurer_price) as `all_dd` FROM invoice_insurer WHERE paid='Yes' AND paid_date = '".$check_date."'"));
        $dd = $report_dd['all_dd'];

        $report_validation = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT group_concat(`payment_type` separator '*#*') as `all_payment` FROM invoice WHERE payment_type IS NOT NULL AND payment_type NOT LIKE '#*#%' AND invoice_date = '".$check_date."'"));

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

        $sub_total = 0;
        $report_data .=  '<tr>';
        $report_data .=  '<td>'.$check_date.'</td>';

        $report_data .=  '<td>';
        if(count($final_pay['Master Card']) == 1) {
            $report_data .=  '$'.number_format($final_pay['Master Card'], 2);
            $sub_total += $final_pay['Master Card'];
            $total1 += $final_pay['Master Card'];
        } else {
            $report_data .=  '$'.number_format(array_sum($final_pay['Master Card']), 2);
            $sub_total += array_sum($final_pay['Master Card']);
            $total1 += array_sum($final_pay['Master Card']);
        }
        $report_data .=  '</td>';
        $report_data .=  '<td>';
        if(count($final_pay['Visa']) == 1) {
            $report_data .=  '$'.number_format($final_pay['Visa'], 2);
            $sub_total += $final_pay['Visa'];
            $total2 += $final_pay['Visa'];
        } else {
            $report_data .=  '$'.number_format(array_sum($final_pay['Visa']), 2);
            $sub_total += array_sum($final_pay['Visa']);
            $total2 += array_sum($final_pay['Visa']);
        }
        $report_data .=  '</td>';
        $report_data .=  '<td>';
        if(count($final_pay['Debit Card']) == 1) {
            $report_data .=  '$'.number_format($final_pay['Debit Card'], 2);
            $sub_total += $final_pay['Debit Card'];
            $total3 += $final_pay['Debit Card'];
        } else {
            $report_data .=  '$'.number_format(array_sum($final_pay['Debit Card']), 2);
            $sub_total += array_sum($final_pay['Debit Card']);
            $total3 += array_sum($final_pay['Debit Card']);
       }
        $report_data .=  '</td>';
        $report_data .=  '<td>';
        if(count($final_pay['Cash']) == 1) {
            $report_data .=  '$'.number_format($final_pay['Cash'], 2);
            $sub_total += $final_pay['Cash'];
            $total4 += $final_pay['Cash'];
        } else {
            $report_data .=  '$'.number_format(array_sum($final_pay['Cash']), 2);
            $sub_total += array_sum($final_pay['Cash']);
            $total4 += array_sum($final_pay['Cash']);
        }
        $report_data .=  '</td>';
        $report_data .=  '<td>';
        if(count($final_pay['Cheque']) == 1) {
            $report_data .=  '$'.number_format($final_pay['Cheque'], 2);
            $sub_total += $final_pay['Cheque'];
            $total5 += $final_pay['Cheque'];
        } else {
            $report_data .=  '$'.number_format(array_sum($final_pay['Cheque']), 2);
            $sub_total += array_sum($final_pay['Cheque']);
            $total5 += array_sum($final_pay['Cheque']);
        }
        $report_data .=  '</td>';
        $report_data .=  '<td>';
        if(count($final_pay['Amex']) == 1) {
            $report_data .=  '$'.number_format($final_pay['Amex'], 2);
            $sub_total += $final_pay['Amex'];
            $total6 += $final_pay['Amex'];
        } else {
            $report_data .=  '$'.number_format(array_sum($final_pay['Amex']), 2);
            $sub_total += array_sum($final_pay['Amex']);
            $total6 += array_sum($final_pay['Amex']);
        }
        $report_data .=  '</td>';
        $report_data .=  '<td>';
        if(count($final_pay['Gift Certificate Redeem']) == 1) {
            $report_data .=  '$'.number_format($final_pay['Gift Certificate Redeem'], 2);
            $sub_total += $final_pay['Gift Certificate Redeem'];
            $total7 += $final_pay['Gift Certificate Redeem'];
        } else {
            $report_data .=  '$'.number_format(array_sum($final_pay['Gift Certificate Redeem']), 2);
            $sub_total += array_sum($final_pay['Gift Certificate Redeem']);
            $total7 += array_sum($final_pay['Gift Certificate Redeem']);
        }
        $report_data .=  '</td>';
        $report_data .=  '<td>';
        if(count($final_pay['Pro-Bono']) == 1) {
            $report_data .=  '$'.number_format($final_pay['Pro-Bono'], 2);
            $sub_total += $final_pay['Pro-Bono'];
            $total8 += $final_pay['Pro-Bono'];
        } else {
            $report_data .=  '$'.number_format(array_sum($final_pay['Pro-Bono']), 2);
            $sub_total += array_sum($final_pay['Pro-Bono']);
            $total8 += array_sum($final_pay['Pro-Bono']);
        }
        $total9 += $dd;
        $sub_total += $dd;
        $report_data .=  '</td>';
        $report_data .=  '<td>$'.number_format($dd, 2).'</td><td>$'.$sub_total.'</td>';
        $report_data .=  '</tr>';
        $final_total += $sub_total;
    }

    $report_data .=  '<tr><td>Grand Total</td><td>$'.number_format($total1, 2).'</td><td>$'.number_format($total2, 2).'</td><td>$'.number_format($total3, 2).'</td><td>$'.number_format($total4, 2).'</td><td>$'.number_format($total5, 2).'</td><td>$'.number_format($total6, 2).'</td><td>$'.number_format($total7, 2).'</td><td>$'.number_format($total8, 2).'</td><td>$'.number_format($total9, 2).'</td><td>$'.number_format(($final_total), 2).'</td></tr>';

    $report_data .=  '</table>';

    return $report_data;

}