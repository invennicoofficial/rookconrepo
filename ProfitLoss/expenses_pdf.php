<?php 
error_reporting(0);
function profit_loss_expense_pdf($dbc) {
	DEFINE('PDF_HEADER', "");
    DEFINE('PDF_FOOTER', "");

    class MYPDF extends TCPDF {

        //Page header
        public function Header() {
        }

        // Page footer
        public function Footer() {
            // Position at 15 mm from bottom
            $this->SetY(-15);
            $this->SetFont('helvetica', 'I', 8);
            $footer_text = 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages();
            $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "L", true);
            $this->SetY(-30);
            $this->setCellHeightRatio(0.7);
            $this->SetFont('helvetica', '', 9);
            $footer_text = PDF_FOOTER;
            $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "C", true);
        }
    }

    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, false, false);
    $pdf->setFooterData(array(0,64,0), array(0,64,128));

    $pdf->SetMargins(5, 5, 5);

    $pdf->SetAutoPageBreak(TRUE, 40);

    $pdf->AddPage();
    $pdf->setCellHeightRatio(1.6);
    $pdf->SetFont('helvetica', '', 9);

	$invoice_years = mysqli_fetch_array(mysqli_query($dbc, "SELECT DISTINCT(LEFT(`invoice_date`,4)) year FROM `point_of_sell` ORDER BY `invoice_date`"));
	$min_year = $invoice_years['year'];
	$search_start = date('Y-m-01');
	$search_end = date('Y-m-t');
	if(!empty($_POST['search_start'])) {
		$search_start = $_POST['search_start'];
	} else if (!empty($_GET['search_start'])) {
		$search_start = $_GET['search_start'];
	}
	if(!empty($_POST['search_end'])) {
		$search_end = $_POST['search_end'];
	} else if (!empty($_GET['search_end'])) {
		$search_end = $_GET['search_end'];
	}
	$startyear = intval(explode('-', $search_start)[0]);
	$endyear = intval(explode('-', $search_end)[0]);

	include_once('../Reports/compensation_function.php');

	$pageNum = (!empty($_GET['page']) ? $_GET['page'] : 1);
for ($year = $startyear; $year <= $endyear; $year++) {
	$rowsPerPage = 25;
	$offset = ($pageNum - 1) * $rowsPerPage;
	$total_rows = "SELECT COUNT(*) numrows FROM (SELECT staff heading FROM expense WHERE ex_date >= '$search_start' AND ex_date <= '$search_end' GROUP BY staff UNION
		SELECT budget_categoryid FROM budget_expense WHERE expense_date >= '$search_start' AND expense_date <= '$search_end' GROUP BY budget_categoryid) expenses";
	$total_sql = "SELECT SUM(IF(`expense_date` LIKE '".$year."-01%', `total`, 0)) `JAN`,
		SUM(IF(`expense_date` LIKE '".$year."-02%', `total`, 0)) `FEB`,
		SUM(IF(`expense_date` LIKE '".$year."-03%', `total`, 0)) `MAR`,
		SUM(IF(`expense_date` LIKE '".$year."-04%', `total`, 0)) `APR`,
		SUM(IF(`expense_date` LIKE '".$year."-05%', `total`, 0)) `MAY`,
		SUM(IF(`expense_date` LIKE '".$year."-06%', `total`, 0)) `JUN`,
		SUM(IF(`expense_date` LIKE '".$year."-07%', `total`, 0)) `JUL`,
		SUM(IF(`expense_date` LIKE '".$year."-08%', `total`, 0)) `AUG`,
		SUM(IF(`expense_date` LIKE '".$year."-09%', `total`, 0)) `SEP`,
		SUM(IF(`expense_date` LIKE '".$year."-10%', `total`, 0)) `OCT`,
		SUM(IF(`expense_date` LIKE '".$year."-11%', `total`, 0)) `NOV`,
		SUM(IF(`expense_date` LIKE '".$year."-12%', `total`, 0)) `DEC`,
		SUM(IF(`expense_date` LIKE '".$year."-01%', `tax`, 0)) `JANTAX`,
		SUM(IF(`expense_date` LIKE '".$year."-02%', `tax`, 0)) `FEBTAX`,
		SUM(IF(`expense_date` LIKE '".$year."-03%', `tax`, 0)) `MARTAX`,
		SUM(IF(`expense_date` LIKE '".$year."-04%', `tax`, 0)) `APRTAX`,
		SUM(IF(`expense_date` LIKE '".$year."-05%', `tax`, 0)) `MAYTAX`,
		SUM(IF(`expense_date` LIKE '".$year."-06%', `tax`, 0)) `JUNTAX`,
		SUM(IF(`expense_date` LIKE '".$year."-07%', `tax`, 0)) `JULTAX`,
		SUM(IF(`expense_date` LIKE '".$year."-08%', `tax`, 0)) `AUGTAX`,
		SUM(IF(`expense_date` LIKE '".$year."-09%', `tax`, 0)) `SEPTAX`,
		SUM(IF(`expense_date` LIKE '".$year."-10%', `tax`, 0)) `OCTTAX`,
		SUM(IF(`expense_date` LIKE '".$year."-11%', `tax`, 0)) `NOVTAX`,
		SUM(IF(`expense_date` LIKE '".$year."-12%', `tax`, 0)) `DECTAX`
		FROM (SELECT 'EXPENSE' `source`, 'Staff Expense' `category`, staff `heading`, ex_date expense_date, gst tax, amount total FROM expense UNION
			SELECT 'BUDGET' `source`, category `category`, expense `heading`, expense_date, tax, actual_amount total FROM budget_expense LEFT JOIN budget_category ON budget_expense.budget_categoryid=budget_category.budget_categoryid) expenses
		WHERE `expense_date` >= '$search_start' AND `expense_date` <= '$search_end'";
	$total_result = mysqli_fetch_array(mysqli_query($dbc, $total_sql));
	$totals = [ $total_result['JAN'], $total_result['FEB'], $total_result['MAR'], $total_result['APR'], $total_result['MAY'], $total_result['JUN'],
		$total_result['JUL'], $total_result['AUG'], $total_result['SEP'], $total_result['OCT'], $total_result['NOV'], $total_result['DEC'] ];
	$total_taxes = [ $total_result['JANTAX'], $total_result['FEBTAX'], $total_result['MARTAX'], $total_result['APRTAX'], $total_result['MAYTAX'], $total_result['JUNTAX'],
		$total_result['JULTAX'], $total_result['AUGTAX'], $total_result['SEPTAX'], $total_result['OCTTAX'], $total_result['NOVTAX'], $total_result['DECTAX'] ];
	$expenses_sql = "SELECT source, heading, category, tab,
		SUM(IF(`expense_date` LIKE '".$year."-01%', `total`-`tax`, 0)) `JAN`,
		SUM(IF(`expense_date` LIKE '".$year."-02%', `total`-`tax`, 0)) `FEB`,
		SUM(IF(`expense_date` LIKE '".$year."-03%', `total`-`tax`, 0)) `MAR`,
		SUM(IF(`expense_date` LIKE '".$year."-04%', `total`-`tax`, 0)) `APR`,
		SUM(IF(`expense_date` LIKE '".$year."-05%', `total`-`tax`, 0)) `MAY`,
		SUM(IF(`expense_date` LIKE '".$year."-06%', `total`-`tax`, 0)) `JUN`,
		SUM(IF(`expense_date` LIKE '".$year."-07%', `total`-`tax`, 0)) `JUL`,
		SUM(IF(`expense_date` LIKE '".$year."-08%', `total`-`tax`, 0)) `AUG`,
		SUM(IF(`expense_date` LIKE '".$year."-09%', `total`-`tax`, 0)) `SEP`,
		SUM(IF(`expense_date` LIKE '".$year."-10%', `total`-`tax`, 0)) `OCT`,
		SUM(IF(`expense_date` LIKE '".$year."-11%', `total`-`tax`, 0)) `NOV`,
		SUM(IF(`expense_date` LIKE '".$year."-12%', `total`-`tax`, 0)) `DEC`
		FROM (SELECT 'EXPENSE' source, `expense_for` tab, CONCAT(IFNULL(ec,''), ': ', expense.category) category, concat(IFNULL(gl,''), ': ', expense.title) heading, ex_date expense_date, gst tax, amount total FROM expense LEFT JOIN expense_categories ON expense.category=expense_categories.category AND expense.title=expense_categories.heading UNION
			SELECT `budget`.`budget_name` `source`, `budget`.`budgetid` tab, CONCAT(IFNULL(ec,''), ': ', category) category, CONCAT(IFNULL(gl,''), ': ', expense) heading, expense_date, tax, actual_amount total FROM budget_expense LEFT JOIN budget_category ON budget_expense.budget_categoryid=budget_category.budget_categoryid LEFT JOIN `budget` ON `budget_category`.`budgetid`=`budget`.`budgetid`) expenses
		WHERE `expense_date` LIKE '$search_start' AND `expense_date` <= '$search_end'
		GROUP BY `source`, `tab`, `category`, `heading`
		ORDER BY `source`, `tab`, `category`, `heading`, `expense_date`";
	$expenses = mysqli_query($dbc, $expenses_sql);

	$html = '';
	$html .= '<table style="border:1px solid black;border-collapse:collapse;height:2px">
		<thead>
			<tr style="border:1px solid black;border-collapse:collapse;height:2px">
				<th style="border:1px solid black;border-collapse:collapse;height:2px">Expense</th>';
				for($month = 0; $month < 12; $month++) {
					$dateObj   = DateTime::createFromFormat('!m', $month+1);
					$html .= '<th style="border:1px solid black;border-collapse:collapse;height:2px">'.$dateObj->format('F').'</th>';
				} 
	$html .= '</tr>
		</thead>
		<tbody style="border:1px solid black;border-collapse:collapse;height:2px">';
		$category = $source = '';
			while($row = mysqli_fetch_array($expenses)) {
				if($source != $row['source'].$row['tab']) {
					$source = $row['source'].$row['tab'];
					if($row['source'] != 'EXPENSE') {
						$html .= '<tr><td style="border:1px solid black;border-collapse:collapse;height:2px" colspan="13"><a href="'.WEBSITE_URL.'/Budget/budget_expense.php?budgetid='.$row['tab'].'&from_url='.urlencode(WEBSITE_URL.$_SERVER["REQUEST_URI"]).'">'.$row["source"].'</a></td></tr>';
					}
				}
				if($category != $row['category']) {
					$category = $row['category'];
					$html .=  '<tr><td style="border:1px solid black;border-collapse:collapse;height:2px" colspan="13">'.$category.'</td></tr>';
				}
				$html .=  '<tr><td style="border:1px solid black;border-collapse:collapse;height:2px" data-title="Expense">'.$row["source"] == "STAFF"?get_contact($dbc,$row["heading"]):$row["heading"].'</td>';
				for($month = 0; $month < 12; $month++) {
					$dateObj = DateTime::createFromFormat('!m', $month+1);
					$amt = $row[strtoupper($dateObj->format('M'))];
					$html .= '<td style="border:1px solid black;border-collapse:collapse;height:2px" data-title="'.$dateObj->format("F").'">$'.number_format($amt, 2, ".", ",").'</td>\n';
				}
				$html .= '</tr>\n';
			}
			$html .= '<tr>
				<td style="border:1px solid black;border-collapse:collapse;height:2px">Sales Tax Total</td>';
				for($month = 0; $month < 12; $month++) {
					$dateObj = DateTime::createFromFormat('!m', $month+1);
					$html .= '<td style="border:1px solid black;border-collapse:collapse;height:2px" data-title="'.$dateObj->format("F").' Sales Tax">$'.number_format($total_taxes[$month], 2, ".", ",").'</td>';
				}
			$html .= '</tr>
			<tr>
				<td style="border:1px solid black;border-collapse:collapse;height:2px">Monthly Total</td>';
				for($month = 0; $month < 12; $month++) {
					$dateObj = DateTime::createFromFormat('!m', $month+1);
					$html .= '<td style="border:1px solid black;border-collapse:collapse;height:2px" data-title="'.$dateObj->format("F").'">$'.number_format($totals[$month], 2, ".", ",").'</td>';
				}
			$html .= '</tr>';
			$html .= '<tr>
				<td style="border:1px solid black;border-collapse:collapse;height:2px" colspan="10" >Total Expenses for ';
			$html .= $year == $startyear ? $search_start : $year;
			$html .= '-01-01 to '; 
			$html .= $year == $endyear ? $search_end : $year;
			$html .= '-12-31  </td>
				<td data-title="Total" style="border:1px solid black;border-collapse:collapse;height:2px;text-align:right" colspan="3">$ '.number_format(array_sum($totals), 2, ".", ","). '</td>
			</tr>';
			$html .= '
		</tbody>
	</table>';
	}

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('download/expense_'.time().'.pdf', 'F');
    echo '<script type="text/javascript" language="Javascript">window.location.replace("profit_loss.php?tab=expenses");
    window.open("download/expense_'.time().'.pdf", "fullscreen=yes");
    </script>';
}
?>



