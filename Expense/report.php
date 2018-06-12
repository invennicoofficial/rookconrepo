<?php /* Expenses Report */
include_once('../tcpdf/tcpdf.php');

if (isset($_POST['printpdf'])) {
    $starttimepdf = $_POST['starttimepdf'];
    $endtimepdf = $_POST['endtimepdf'];

    DEFINE('START_DATE', $starttimepdf);
    DEFINE('END_DATE', $endtimepdf);
    DEFINE('EXPENSE_LOGO', get_config($dbc, 'expense_logo'));
    DEFINE('EXPENSE_FOOTER', html_entity_decode(get_config($dbc, 'expense_footer')));

	class MYPDF extends TCPDF {

		public function Header() {
            if(EXPENSE_LOGO != '') {
                $image_file = 'download/'.EXPENSE_LOGO;
                //$this->Image($image_file, 10, 10, 80, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
                $this->Image($image_file, 10, 10, '', '', '', '', 'T', false, '', '', false, false, 0, false, false, false);
            }

            $this->SetFont('helvetica', '', 13);
            $footer_text = '<p style="text-align:right;">Pay Period From '.START_DATE.' To '.END_DATE.'</p>';
            $this->writeHTMLCell(0, 0, 0 , 35, $footer_text, 0, 0, false, "R", true);
		}

		// Page footer
		public function Footer() {
            if(EXPENSE_FOOTER != '') {
            $this->SetY(-27);
            $this->SetFont('helvetica', 'I', 9);
            $footer_text = '<span style="text-align:center;">'.EXPENSE_FOOTER.'</span>';
            $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "L", true);
            }

			// Position at 15 mm from bottom
			$this->SetY(-12);
			$this->SetFont('helvetica', 'I', 9);
			$footer_text = 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages();
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

    $html = report_expense($dbc, $starttimepdf, $endtimepdf, 'padding:3px; border:1px solid black;', 'background-color:grey; color:black;', 'background-color:lightgrey; color:black;');

    $today_date = date('Y-m-d');
	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output('download/expense_'.$today_date.'.pdf', 'F');
    ?>

	<script type="text/javascript" language="Javascript">
	window.location.replace('download/expense_<?php echo $today_date;?>.pdf');
	</script>
    <?php
    $starttime = $starttimepdf;
    $endtime = $endtimepdf;
} ?>

<body>
<?php include_once ('../navigation.php');
?>

<form name="form_sites" method="post" action="" class="form-inline" role="form">
	<?php
	if (isset($_POST['search_email_submit'])) {
		$starttime = $_POST['starttime'];
		$endtime = $_POST['endtime'];
	} else {
		if (!isset($starttime)) {
			$starttime = date('Y-m-01');
			$endtime = date('Y-m-d');
		}
	}

	if($starttime == 0000-00-00) {
		$starttime = date('Y-m-01');
	}

	if($endtime == 0000-00-00) {
		$endtime = date('Y-m-d');
	}
	?>
	<br>
	<div class="form-group ">
		<div class="col-sm-4">
			<label for="site_name" class="col-sm-12 control-label">From:</label>
		</div>
		<div class="col-sm-8">
			<input name="starttime" type="text" class="datepicker form-control" value="<?php echo $starttime; ?>">
		</div>
	</div>

	  <!-- end time -->
	<div class="form-group until">
		<div class="col-sm-4">
			<label for="site_name" class="col-sm-12 control-label">Until:</label>
		</div>
		<div class="col-sm-8" >
			<input name="endtime" type="text" class="datepicker form-control" value="<?php echo $endtime; ?>">
		</div>
	</div>

	<button type="submit" name="search_email_submit" value="Search" class="btn brand-btn mobile-block">Submit</button>
	<br>

	<input type="hidden" name="starttimepdf" value="<?php echo $starttime; ?>">
	<input type="hidden" name="endtimepdf" value="<?php echo $endtime; ?>">

	<button type="submit" name="printpdf" value="Print Report" class="btn brand-btn pull-right">Print Report</button>
	<br><br>

</form>

<?php
	echo report_expense($dbc, $starttime, $endtime, '', '', '');
?>

<?php
function report_expense($dbc, $starttime, $endtime, $table_style, $table_row_style, $grand_total_style) {
    $result = mysqli_query($dbc, "SELECT * FROM expense WHERE (DATE(ex_date) >= '".$starttime."' AND DATE(ex_date) <= '".$endtime."') AND `deleted`=0");
	$config_sql = "SELECT expense, expense_dashboard, exchange_buffer, gst_name, gst_amt, pst_name, pst_amt, expense_types FROM field_config_expense WHERE `tab`='$current_tab' UNION SELECT
		'Contact,Staff,Category,Heading,Description,Date,Receipt,Type,Day Expense,Amount,Tax,Total,Budget,Signature,Flight,Hotel,Breakfast,Lunch,Dinner,Beverages,Transportation,Entertainment,Gas,Misc',
		'Contact,Category,Heading,Description,Date,Receipt,Type,Day Expense,Amount,Tax,Total,Budget', '0', 'GST', '5', 'PST', '0', 'Meals,Tip'";
	$get_expense_config = mysqli_fetch_assoc(mysqli_query($dbc,$config_sql));
	$value_config = ','.$get_expense_config['expense'].',';
	$db_config = ','.$get_expense_config['expense_dashboard'].',';
	$gst_name = trim($get_expense_config['gst_name'],',');
	$pst_name = trim($get_expense_config['pst_name'],',');
	$gst_amt = trim($get_expense_config['gst_amt'],',');
	$pst_amt = trim($get_expense_config['pst_amt'],',');
	$expense_types = trim(','.$get_expense_config['expense_types'].',',',');

    $report_data = '<div id="no-more-tables"><table border="1px" class="table table-bordered" style="'.$table_style.'">';
    $report_data .= '<tr style="'.$table_row_style.'" class="hidden-xs hidden-sm">';
    if (strpos($value_config, ','."Category".',') !== FALSE) {
        $report_data .= '<th>Expense For</th>';
    }
    if (strpos($value_config, ','."Date".',') !== FALSE) {
        $report_data .= '<th>Expense Date</th>';
    }
    if (strpos($value_config, ','."Staff".',') !== FALSE) {
        $report_data .= '<th>Staff</th>';
    }

    if (strpos($value_config, ','."Heading".',') !== FALSE) {
        $report_data .= '<th>Expense Heading</th>';
    }
    $report_data .= '<th>Type</th>';
    if (strpos($value_config, ','."Receipt".',') !== FALSE) {
        $report_data .= '<th>Receipt</th>';
    }
    if (strpos($value_config, ','."Description".',') !== FALSE) {
        $report_data .= '<th>Description</th>';
    }
    if (strpos($value_config, ','."Day Expense".',') !== FALSE) {
        $report_data .= '<th>Day Expense</th>';
    }

    if (strpos($value_config, ','."Amount".',') !== FALSE) {
        $report_data .= '<th>Amount</th>';
    }
    if (strpos($value_config, ','."Local Tax".',') !== FALSE) {
        $report_data .= '<th>'.$pst_name.'</th>';
    }
    if (strpos($value_config, ','."Tax".',') !== FALSE) {
        $report_data .= '<th>'.$gst_name.'</th>';
    }
    if (strpos($value_config, ','."Total".',') !== FALSE) {
        $report_data .= '<th>Total</th>';
    }
    if (strpos($value_config, ','."Budget".',') !== FALSE) {
        $report_data .= '<th>Budget</th>';
    }
	$report_data .= '<th>Status</th>';

    $num_rows = mysqli_num_rows($result);
    $report_data .= "</tr>";
            $amount = 0;
            $gst = 0;
            $total = 0;
            $balance = 0;
    while($row = mysqli_fetch_array( $result )) {
        $report_data .= '<tr nobr="true">';

        if (strpos($value_config, ','."Category".',') !== FALSE) {
            $report_data .= '<td data-title="Expense For">' . ucwords($row['expense_for']).'<br>'.$row['contact'] . '</td>';
        }
        if (strpos($value_config, ','."Date".',') !== FALSE) {
            $report_data .= '<td data-title="Expense Date">' . $row['ex_date'] . '</td>';
        }
        if (strpos($value_config, ','."Staff".',') !== FALSE) {
            $report_data .= '<td data-title="Staff">' . get_contact($dbc,$row['staff']) . '</td>';
        }
        if (strpos($value_config, ','."Heading".',') !== FALSE) {
            $report_data .= '<td data-title="Expense Heading">' . $row['title'] . '</td>';
        }
        $report_data .= '<td data-title="Type">' . $row['type'] . '</td>';
        if (strpos($value_config, ','."Receipt".',') !== FALSE) {
            $report_data .= '<td data-title="Receipt"><a href="download/'.$row['ex_file'].'" target="_blank">' . $row['ex_file'] . '</a></td>';
        }
        if (strpos($value_config, ','."Description".',') !== FALSE) {
            $report_data .= '<td data-title="Description">' . html_entity_decode($row['description']) . '</td>';
        }
        if (strpos($value_config, ','."Day Expense".',') !== FALSE) {
            $report_data .= '<td data-title="Day Expense">' . $row['day_expense'] . '</td>';
        }
        if (strpos($value_config, ','."Amount".',') !== FALSE) {
            $report_data .= '<td data-title="Amount">' . $row['amount'] . '</td>';
        }
        if (strpos($value_config, ','."Local Tax".',') !== FALSE) {
            $report_data .= '<td data-title="'.$pst_name.'">' . $row['gst'] . '</td>';
        }
        if (strpos($value_config, ','."Tax".',') !== FALSE) {
            $report_data .= '<td data-title="'.$gst_name.'">' . $row['gst'] . '</td>';
        }
        if (strpos($value_config, ','."Total".',') !== FALSE) {
            $report_data .= '<td data-title="Amount">' . $row['total'] . '</td>';
        }
        if (strpos($value_config, ','."Budget".',') !== FALSE) {
            $report_data .= '<td data-title="Budget">' . $row['balance'] . '</td>';
        }
		$report_data .= '<td data-title="Status">'.$row['status'].'</td>';
        $report_data .= "</tr>";
        $amount += $row['amount'];
        $gst += $row['gst'];
        $total += $row['total'];
        $balance += $row['balance'];
    }

    if($num_rows > 0) {
		$colspan = 0;
    $report_data .= '<tr nobr="true">';
    if (strpos($value_config, ','."Category".',') !== FALSE) {
        $colspan++;
    }
    if (strpos($value_config, ','."Date".',') !== FALSE) {
        $colspan++;
    }
    if (strpos($value_config, ','."Staff".',') !== FALSE) {
        $colspan++;
    }
    if (strpos($value_config, ','."Heading".',') !== FALSE) {
        $colspan++;
    }
    $colspan++;
    if (strpos($value_config, ','."Receipt".',') !== FALSE) {
        $colspan++;
    }
    if (strpos($value_config, ','."Description".',') !== FALSE) {
        $colspan++;
    }
    if (strpos($value_config, ','."Day Expense".',') !== FALSE) {
        $colspan++;
    }
	$report_data .= '<td colspan="'.$colspan.'"><b>Total</b></td>';
    if (strpos($value_config, ','."Amount".',') !== FALSE) {
    $report_data .= '<td data-title="Amount"><b>$' . number_format((float)$amount, 2, '.', '') . '</b></td>';
    }
    if (strpos($value_config, ','."Local Tax".',') !== FALSE) {
    $report_data .= '<td data-title="'.$pst_name.'"><b>$' . number_format((float)$gst, 2, '.', '') . '</b></td>';
    }
    if (strpos($value_config, ','."Tax".',') !== FALSE) {
    $report_data .= '<td data-title="'.$gst_name.'"><b>$' . number_format((float)$gst, 2, '.', '') . '</b></td>';
    }
    if (strpos($value_config, ','."Amount".',') !== FALSE) {
    $report_data .= '<td data-title="Total"><b>$' . number_format((float)$total, 2, '.', '') . '</b></td>';
    }
    if (strpos($value_config, ','."Budget".',') !== FALSE) {
    $report_data .= '<td data-title="Budget"><b>$' . number_format((float)$balance, 2, '.', '') . '</b></td>';
    }
	$report_data .= '<td></td>';
    $report_data .= "</tr>";
    }

    $report_data .= '</table></div>';

    return $report_data;
}