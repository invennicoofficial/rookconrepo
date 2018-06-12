<?php 
error_reporting(1);
function profit_loss_inventory_pdf($dbc) {
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

	$rowsPerPage = 25;
	$offset = ($pageNum - 1) * $rowsPerPage;
	$inv_cost_field = get_config($dbc,'inventory_cost');
	$total_rows = "SELECT COUNT(DISTINCT `inventoryid`) numrows FROM (SELECT i.`inventoryid`, rs.`date_added` FROM `receive_shipment` rs LEFT JOIN `inventory` i ON rs.`inventoryid`=i.`inventoryid`) INVENTORY WHERE `date_added` >= '$search_start' AND `date_added` <= '$search_end'";
	$total_sql = "SELECT SUM(IF(`date_added` LIKE '".$year."-01%', `cost`*`quantity`, 0)) `JAN`,
		SUM(IF(`date_added` LIKE '".$year."-02%', `cost`*`quantity`, 0)) `FEB`,
		SUM(IF(`date_added` LIKE '".$year."-03%', `cost`*`quantity`, 0)) `MAR`,
		SUM(IF(`date_added` LIKE '".$year."-04%', `cost`*`quantity`, 0)) `APR`,
		SUM(IF(`date_added` LIKE '".$year."-05%', `cost`*`quantity`, 0)) `MAY`,
		SUM(IF(`date_added` LIKE '".$year."-06%', `cost`*`quantity`, 0)) `JUN`,
		SUM(IF(`date_added` LIKE '".$year."-07%', `cost`*`quantity`, 0)) `JUL`,
		SUM(IF(`date_added` LIKE '".$year."-08%', `cost`*`quantity`, 0)) `AUG`,
		SUM(IF(`date_added` LIKE '".$year."-09%', `cost`*`quantity`, 0)) `SEP`,
		SUM(IF(`date_added` LIKE '".$year."-10%', `cost`*`quantity`, 0)) `OCT`,
		SUM(IF(`date_added` LIKE '".$year."-11%', `cost`*`quantity`, 0)) `NOV`,
		SUM(IF(`date_added` LIKE '".$year."-12%', `cost`*`quantity`, 0)) `DEC`
		FROM (SELECT CONCAT(IF(i.`category` = '', '', CONCAT(i.`category`,': ')), i.`name`) name, i.`inventoryid`, rs.`quantity`, `$inv_cost_field` cost,
			rs.`date_added` FROM `receive_shipment` rs LEFT JOIN `inventory` i ON rs.`inventoryid`=i.`inventoryid` WHERE `date_added` >= '$search_start' AND `date_added` <= '$search_end') inventory";
	$total_result = mysqli_fetch_array(mysqli_query($dbc, $total_sql));
	$totals = [ $total_result['JAN'], $total_result['FEB'], $total_result['MAR'], $total_result['APR'], $total_result['MAY'], $total_result['JUN'],
		$total_result['JUL'], $total_result['AUG'], $total_result['SEP'], $total_result['OCT'], $total_result['NOV'], $total_result['DEC'] ];
	$total_taxes = [ $total_result['JANTAX'], $total_result['FEBTAX'], $total_result['MARTAX'], $total_result['APRTAX'], $total_result['MAYTAX'], $total_result['JUNTAX'],
		$total_result['JULTAX'], $total_result['AUGTAX'], $total_result['SEPTAX'], $total_result['OCTTAX'], $total_result['NOVTAX'], $total_result['DECTAX'] ];
	$inventory_sql = "SELECT `name`,
		SUM(IF(`date_added` LIKE '".$year."-01%', `cost`*`quantity`, 0)) `JAN`,
		SUM(IF(`date_added` LIKE '".$year."-02%', `cost`*`quantity`, 0)) `FEB`,
		SUM(IF(`date_added` LIKE '".$year."-03%', `cost`*`quantity`, 0)) `MAR`,
		SUM(IF(`date_added` LIKE '".$year."-04%', `cost`*`quantity`, 0)) `APR`,
		SUM(IF(`date_added` LIKE '".$year."-05%', `cost`*`quantity`, 0)) `MAY`,
		SUM(IF(`date_added` LIKE '".$year."-06%', `cost`*`quantity`, 0)) `JUN`,
		SUM(IF(`date_added` LIKE '".$year."-07%', `cost`*`quantity`, 0)) `JUL`,
		SUM(IF(`date_added` LIKE '".$year."-08%', `cost`*`quantity`, 0)) `AUG`,
		SUM(IF(`date_added` LIKE '".$year."-09%', `cost`*`quantity`, 0)) `SEP`,
		SUM(IF(`date_added` LIKE '".$year."-10%', `cost`*`quantity`, 0)) `OCT`,
		SUM(IF(`date_added` LIKE '".$year."-11%', `cost`*`quantity`, 0)) `NOV`,
		SUM(IF(`date_added` LIKE '".$year."-12%', `cost`*`quantity`, 0)) `DEC`
		FROM (SELECT CONCAT(IF(i.`category` = '', '', CONCAT(i.`category`,': ')), i.`name`) name, i.`inventoryid`, rs.`quantity`, `$inv_cost_field` cost,
			rs.`date_added` FROM `receive_shipment` rs LEFT JOIN `inventory` i ON rs.`inventoryid`=i.`inventoryid` WHERE `date_added` >= '$search_start' AND `date_added` <= '$search_end') inventory
		GROUP BY `name`
		ORDER BY `name`, `date_added`";
	$inventory = mysqli_query($dbc, $inventory_sql);

	$html = '<table style="border:1px solid black;border-collapse:collapse;height:2px">
		<thead>
			<tr style="border:1px solid black;border-collapse:collapse;height:2px">
				<th style="border:1px solid black;border-collapse:collapse;height:2px">Inventory</th>';
				for($month = 0; $month < 12; $month++) {
					$dateObj   = DateTime::createFromFormat('!m', $month+1);
					$html .= '<th style="border:1px solid black;border-collapse:collapse;height:2px">'.$dateObj->format('F').'</th>';
				}
	$html .= '</tr>
		</thead>
		<tbody style="border:1px solid black;border-collapse:collapse;height:2px">';
			$category = '';
			while($row = mysqli_fetch_array($inventory)) {
				$html .= '<tr><td style="border:1px solid black;border-collapse:collapse;height:2px" data-title="Inventory">'.$row['name'].'</td>';
				for($month = 0; $month < 12; $month++) {
					$dateObj = DateTime::createFromFormat('!m', $month+1);
					$amt = $row[strtoupper($dateObj->format('M'))];
					$html .= '<td style="border:1px solid black;border-collapse:collapse;height:2px" data-title="'.$dateObj->format('F').'">$'.number_format($amt, 2, '.', ',').'</td>';
				}
				$html .= '</tr>';
			}
			$html .= '<tr style="border:1px solid black;border-collapse:collapse;height:2px">
				<td style="border:1px solid black;border-collapse:collapse;height:2px">Monthly Total</td>';
				for($month = 0; $month < 12; $month++) {
					$dateObj = DateTime::createFromFormat('!m', $month+1);
					$html .= '<td data-title="'.$dateObj->format('F').'" style="border:1px solid black;border-collapse:collapse;height:2px">$'.number_format($totals[$month], 2, '.', ',').'</td>';
				}
			$html .= '</tr>';
			$html .= '<tr style="border:1px solid black;border-collapse:collapse;height:2px">
				<td colspan="10" style="border:1px solid black;border-collapse:collapse;height:2px">Total Inventory Costs for ';
			$html .=  $year == $startyear ? $search_start : $year;
			$html .= '-01-01' . ' to ';
			$html .= $year == $endyear ? $search_end : $year;
			$html .= '-12-31 </td>
				<td data-title="Total" colspan="3" style="border:1px solid black;border-collapse:collapse;height:2px">$' . number_format(array_sum($totals), 2, '.', ',') . '</td></tr>';
			$html .= '
		</tbody>
	</table>';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('download/inventory_'.time().'.pdf', 'F');
    echo '<script type="text/javascript" language="Javascript">window.location.replace("profit_loss.php?tab=expenses");
    window.open("download/inventory_'.time().'.pdf", "fullscreen=yes");
    </script>';
}
?>
