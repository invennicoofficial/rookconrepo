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
            $footer_text = 'Receipt Summary - <b>'.START_DATE.'</b> To <b>'.END_DATE.'</b>';
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
	window.open('Download/sales_summary_<?php echo  $today_date;?>.pdf', 'fullscreen=yes');
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

    /*
    $report_dd = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(insurer_price) as `all_dd` FROM invoice_insurer WHERE paid='Yes' AND (paid_date >= '".$starttime."' AND paid_date <= '".$endtime."')"));
    $dd = $report_dd['all_dd'];

    $report_validation = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT group_concat(`payment_type` separator '*#*') as `all_payment`, group_concat(`injuryid` separator '*#*') as `all_injury` FROM invoice WHERE payment_type IS NOT NULL AND payment_type NOT LIKE '#*#%' AND (invoice_date >= '".$starttime."' AND invoice_date <= '".$endtime."')"));

    $payment = $report_validation['all_payment'];
    $injury = $report_validation['all_injury'];

    $payment = str_replace(',#*#', '#*#', $payment);
    $payment = str_replace(',*#*', '*#*', $payment);
    $payment = explode('*#*', $payment);
    $injury = explode('*#*', $injury);

    $final_pay = array();
    $k = 0;
    foreach ($payment as $sel) {
        $injury_type = get_all_from_injury($dbc, $injury[$k], 'injury_type');
        if (strpos($sel, ',') !== false) {
            $sep_sel = explode('#*#', $sel);
            $each_sep_pt = explode(',', $sep_sel[0]);
            $each_sep_pp = explode(',', $sep_sel[1]);
            $m = 0;
            foreach ($each_sep_pt as $value_each_sep_sel) {
                $final_pay[][$injury_type][$value_each_sep_sel] = $each_sep_pp[$m];
                $m++;
            }
        } else {
            $each_sel = explode('#*#', $sel);
            $final_pay[][$injury_type][$each_sel[0]] = $each_sel[1];
        }
        $k++;
    }

    $final_pay = call_user_func_array('array_merge_recursive', $final_pay);

    $report_data .=  '<table border="1px" class="table table-bordered" style="'.$table_style.'">';

    $report_data .=  '<tr style="'.$table_row_style.'">';
    $report_data .=  '<th width="25%">&nbsp;</th>';
    $report_data .=  '<th width="9%">Master Card</th>';
    $report_data .=  '<th width="6%">Visa</th>';
    $report_data .=  '<th width="7%">Debit Card</th>';
    $report_data .=  '<th width="6%">Cash</th>';
    $report_data .=  '<th width="6%">Cheque</th>';
    $report_data .=  '<th width="5%">Amex</th>';
    $report_data .=  '<th width="10%">Gift Certificate Redeem</th>';
    $report_data .=  '<th width="8%">Pro-Bono</th>';
    $report_data .=  '<th width="8%">Direct Deposit</th>';
    $report_data .=  '<th width="10%">Total</th>';
    $report_data .=  '</tr>';

    $final_total = 0;
    $total1 =  0;
    $total2 =  0;
    $total3 =  0;
    $total4 =  0;
    $total5 =  0;
    $total6 =  0;
    $total7 =  0;
    $total8 =  0;
    foreach ($final_pay as $key=>$value) {
        $sub_total = 0;
        $report_data .=  '<tr>';
        $report_data .=  '<td>'.$key.'</td>';

        $report_data .=  '<td>';
        if(count($value['Master Card']) == 1) {
            $report_data .=  number_format($value['Master Card'], 2);
            $sub_total += $value['Master Card'];
            $total1 += $value['Master Card'];
        } else {
            $report_data .=  number_format(array_sum($value['Master Card']), 2);
            $sub_total += array_sum($value['Master Card']);
            $total1 += array_sum($value['Master Card']);
        }
        $report_data .=  '</td>';
        $report_data .=  '<td>';
        if(count($value['Visa']) == 1) {
            $report_data .=  number_format($value['Visa'], 2);
            $sub_total += $value['Visa'];
            $total2 += $value['Visa'];
        } else {
            $report_data .=  number_format(array_sum($value['Visa']), 2);
            $sub_total += array_sum($value['Visa']);
            $total2 += array_sum($value['Visa']);
        }
        $report_data .=  '</td>';
        $report_data .=  '<td>';
        if(count($value['Debit Card']) == 1) {
            $report_data .=  number_format($value['Debit Card'], 2);
            $sub_total += $value['Debit Card'];
            $total3 += $value['Debit Card'];
        } else {
            $report_data .=  number_format(array_sum($value['Debit Card']), 2);
            $sub_total += array_sum($value['Debit Card']);
            $total3 += array_sum($value['Debit Card']);
       }
        $report_data .=  '</td>';
        $report_data .=  '<td>';
        if(count($value['Cash']) == 1) {
            $report_data .=  number_format($value['Cash'], 2);
            $sub_total += $value['Cash'];
            $total4 += $value['Cash'];
        } else {
            $report_data .=  number_format(array_sum($value['Cash']), 2);
            $sub_total += array_sum($value['Cash']);
            $total4 += array_sum($value['Cash']);
        }
        $report_data .=  '</td>';
        $report_data .=  '<td>';
        if(count($value['Cheque']) == 1) {
            $report_data .=  number_format($value['Cheque'], 2);
            $sub_total += $value['Cheque'];
            $total5 += $value['Cheque'];
        } else {
            $report_data .=  number_format(array_sum($value['Cheque']), 2);
            $sub_total += array_sum($value['Cheque']);
            $total5 += array_sum($value['Cheque']);
        }
        $report_data .=  '</td>';
        $report_data .=  '<td>';
        if(count($value['Amex']) == 1) {
            $report_data .=  number_format($value['Amex'], 2);
            $sub_total += $value['Amex'];
            $total6 += $value['Amex'];
        } else {
            $report_data .=  number_format(array_sum($value['Amex']), 2);
            $sub_total += array_sum($value['Amex']);
            $total6 += array_sum($value['Amex']);
        }
        $report_data .=  '</td>';
        $report_data .=  '<td>';
        if(count($value['Gift Certificate Redeem']) == 1) {
            $report_data .=  number_format($value['Gift Certificate Redeem'], 2);
            $sub_total += $value['Gift Certificate Redeem'];
            $total7 += $value['Gift Certificate Redeem'];
        } else {
            $report_data .=  number_format(array_sum($value['Gift Certificate Redeem']), 2);
            $sub_total += array_sum($value['Gift Certificate Redeem']);
            $total7 += array_sum($value['Gift Certificate Redeem']);
        }
        $report_data .=  '</td>';
        $report_data .=  '<td>';
        if(count($value['Pro-Bono']) == 1) {
            $report_data .=  number_format($value['Pro-Bono'], 2);
            $sub_total += $value['Pro-Bono'];
            $total8 += $value['Pro-Bono'];
        } else {
            $report_data .=  number_format(array_sum($value['Pro-Bono']), 2);
            $sub_total += array_sum($value['Pro-Bono']);
            $total8 += array_sum($value['Pro-Bono']);
        }
        $report_data .=  '</td>';
        $report_data .=  '<td>0.00</td><td>'.$sub_total.'</td>';
        $report_data .=  '</tr>';
        $final_total += $sub_total;
    }
    $report_data .=  '<tr><td>Insurer</td><td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td><td>'.number_format($dd, 2).'</td><td>'.number_format($dd, 2).'</td></tr>';

    $report_data .=  '<tr><td>Grand Total</td><td>'.number_format($total1, 2).'</td><td>'.number_format($total2, 2).'</td><td>'.number_format($total3, 2).'</td><td>'.number_format($total4, 2).'</td><td>'.number_format($total5, 2).'</td><td>'.number_format($total6, 2).'</td><td>'.number_format($total7, 2).'</td><td>'.number_format($total8, 2).'</td><td>'.number_format($dd, 2).'</td><td>'.number_format(($final_total+$dd), 2).'</td></tr>';

    $report_data .=  '</table>';
    */

    $report_service = mysqli_query($dbc,"SELECT invoiceid, patientid, payment_type, service_date, invoice_date, injuryid FROM invoice WHERE (DATE(invoice_date) >= '".$starttime."' AND DATE(invoice_date) <= '".$endtime."') AND payment_type IS NOT NULL AND payment_type NOT LIKE '#*#%' AND payment_type NOT LIKE ',#*#%' ORDER BY invoiceid");

    $invoiced_patients = 0;
    $cat_invoiced_patients = '';
    $dataPoint = array();
    $k = 0;
    while($row_report = mysqli_fetch_array($report_service)) {

        $arr = explode('#*#', $row_report['payment_type'].',');
        $ptname = $arr[0].',';
        $pt = $arr[1];

        $each_invoice = 0;
        $pt1 = explode(',', $pt);
        $pt1_name = explode(',', $ptname);
        $m = 0;
        foreach($pt1 as $pt2) {
            if($pt2 != '') {
                $invoiced_patients += $pt2;
                $each_invoice += $pt2;

                $it = get_all_from_injury($dbc, $row_report['injuryid'], 'injury_type');
                $dataPoint[$k][$it][$pt1_name[$m]] = $pt2;
            }
            $m++;
        }
        $k++;
    }

    //echo '<pre>';
    //print_r($dataPoint);

    $mymy = group_by_key($dataPoint);
    //print_r($mymy);

    $sumArray = array();
    foreach ($mymy as $k=>$subArray) {
      foreach ($subArray as $id=>$value) {
       foreach ($value as $id1=>$value1) {
        $sumArray[$k][$id1]+=$value1;
      }
      }
    }

    //print_r($sumArray);

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

    $sumArray2 = array();
    foreach ($dataPoint as $k=>$subArray) {
      foreach ($subArray as $id=>$value) {
        $sumArray2[$id]+=$value;
      }
    }

    $report_dd = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(insurer_price) as `all_dd` FROM invoice_insurer WHERE paid='Yes' AND (paid_date >= '".$starttime."' AND paid_date <= '".$endtime."')"));
    $dd = $report_dd['all_dd'];

    $report_data .=  '<table border="1px" class="table table-bordered" style="'.$table_style.'">';

    $report_data .=  '<tr style="'.$table_row_style.'">';
    $report_data .=  '<th width="25%">&nbsp;</th>';
    $report_data .=  '<th width="9%">Master Card</th>';
    $report_data .=  '<th width="6%">Visa</th>';
    $report_data .=  '<th width="7%">Debit Card</th>';
    $report_data .=  '<th width="6%">Cash</th>';
    $report_data .=  '<th width="6%">Cheque</th>';
    $report_data .=  '<th width="5%">Amex</th>';
    $report_data .=  '<th width="10%">Gift Certificate Redeem</th>';
    $report_data .=  '<th width="8%">Pro-Bono</th>';
    $report_data .=  '<th width="8%">Other</th>';
    $report_data .=  '<th width="8%">Direct Deposit</th>';
    $report_data .=  '<th width="10%">Total</th>';
    $report_data .=  '</tr>';

    $mc = 0;
    $v = 0;
    $dc = 0;
    $c = 0;
    $ch = 0;
    $a = 0;
    $gcr = 0;
    $pb = 0;
    $o = 0;

    foreach ($sumArray as $key=>$value) {
        if($key == '') {
            $key = 'Special/Extra';
        }

        $report_data .=  '<tr>';
        $each_total = 0;
        $report_data .=  '<td>'.$key.'</td>';

        $report_data .=  '<td>';
        $report_data .=  number_format($value['Master Card'], 2);
        $each_total += $value['Master Card'];
        $mc += $value['Master Card'];
        $report_data .=  '</td>';

        $report_data .=  '<td>';
        $report_data .=  number_format($value['Visa'], 2);
        $each_total += $value['Visa'];
        $v += $value['Visa'];
        $report_data .=  '</td>';

        $report_data .=  '<td>';
        $report_data .=  number_format($value['Debit Card'], 2);
        $each_total += $value['Debit Card'];
        $dc += $value['Debit Card'];
        $report_data .=  '</td>';

        $report_data .=  '<td>';
        $report_data .=  number_format($value['Cash'], 2);
        $each_total += $value['Cash'];
        $c += $value['Cash'];
        $report_data .=  '</td>';

        $report_data .=  '<td>';
        $report_data .=  number_format($value['Cheque'], 2);
        $each_total += $value['Cheque'];
        $ch += $value['Cheque'];
        $report_data .=  '</td>';

        $report_data .=  '<td>';
        $report_data .=  number_format($value['Amex'], 2);
        $each_total += $value['Amex'];
        $a += $value['Amex'];
        $report_data .=  '</td>';

        $report_data .=  '<td>';
        $report_data .=  number_format($value['Gift Certificate Redeem'], 2);
        $each_total += $value['Gift Certificate Redeem'];
        $gcr += $value['Gift Certificate Redeem'];
        $report_data .=  '</td>';

        $report_data .=  '<td>';
        $report_data .=  number_format($value['Pro-Bono'], 2);
        $each_total += $value['Pro-Bono'];
        $pb += $value['Pro-Bono'];
        $report_data .=  '</td>';

        $other_pay = 0;
        foreach ($sumArray2 as $k=>$subArray) {
            if($k == '') {
                $k = 'Special/Extra';
            }
            if($k == $key && $subArray != $each_total) {
                $other_pay = $subArray-$each_total;
            }
        }

        $report_data .=  '<td>';
        $report_data .=  number_format($other_pay, 2);
        $each_total += $other_pay;
        $o += $other_pay;
        $report_data .=  '</td>';

        $report_data .=  '<td>0.00</td>';

        $report_data .=  '<td>';
        $report_data .=  number_format($each_total, 2);
        $report_data .=  '</td>';

        $report_data .=  '</tr>';
    }

    $report_data .=  '<tr>';
    $report_data .=  '<td>Insurer</td><td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td><td>'.number_format($dd, 2).'</td><td>'.number_format($dd, 2).'</td>';


    $report_data .=  '</tr>';

    $report_data .=  '<tr>';
    $report_data .=  '<td>Total</td><td>'.$mc.'</td><td>'.$v.'</td><td>'.$dc.'</td><td>'.$c.'</td><td>'.$ch.'</td><td>'.$a.'</td><td>'.$gcr.'</td><td>'.$pb.'</td><td>'.$o.'</td><td>'.number_format($dd, 2).'</td><td>'.number_format(($mc+$v+$dc+$c+$ch+$a+$gcr+$pb+$o+$dd), 2).'</td>';
    $report_data .=  '</tr>';

    $report_data .=  '</table>';


    ////
    $report_dd = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(insurer_price) as `all_dd` FROM invoice_insurer WHERE paid='Yes' AND (paid_date >= '".$starttime."' AND paid_date <= '".$endtime."')"));
    $dd = $report_dd['all_dd'];

    return $report_data;

}

function group_by_key ($array) {
  $result = array();
  foreach ($array as $sub) {
    foreach ($sub as $k => $v) {
      $result[$k][] = $v;
    }
  }
  return $result;
}