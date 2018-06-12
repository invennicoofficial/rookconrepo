<?php 
error_reporting(1);
function profit_loss_revenue_pdf($dbc) {
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
$rowsPerPage = 25;
$offset = ($pageNum - 1) * $rowsPerPage;

//Create Temporary Table for Calculations
$table_name = 'revenue_profit_loss';
if(!mysqli_query($dbc, "CREATE TEMPORARY TABLE IF NOT EXISTS `$table_name` (`invoice_date` VARCHAR(12), `type` VARCHAR(12), `heading` VARCHAR(200), `category` VARCHAR(200), `total` DECIMAL(10,2))")) {
	echo mysqli_error($dbc);
}
//Load in the Point of Sale data
mysqli_query($dbc, "INSERT INTO `$table_name` (`invoice_date`, `type`, `heading`, `category`, `total`) SELECT pos.`invoice_date`, posp.`type_category`,
	IFNULL(i.`category`, IFNULL(p.`category`, IFNULL(s.`category`, IFNULL(i.`name`, 'Other')))) heading,
	'' category,
	(posp.`quantity` * posp.`price`) total
	FROM `point_of_sell_product` posp LEFT JOIN `point_of_sell` pos ON posp.`posid`=pos.`posid`
		LEFT JOIN `products` p ON posp.`type_category`='product' AND posp.`inventoryid`=p.`productid`
		LEFT JOIN `inventory` i ON posp.`type_category`='inventory' AND posp.`inventoryid`=i.`inventoryid`
		LEFT JOIN `services` s ON posp.`type_category`='service' AND posp.`inventoryid`=s.`serviceid`
	WHERE `invoice_date` >= '$search_start' AND `invoice_date` <= '$search_end' AND pos.`status` IN ('Completed')
	ORDER BY IF(IFNULL(i.`category`, IFNULL(p.`category`, IFNULL(s.`category`, IFNULL(i.`name`, 'Other')))) = 'Other', 'ZZZ', IFNULL(i.`category`, IFNULL(p.`category`, IFNULL(s.`category`, IFNULL(i.`name`, 'Other'))))), `heading`");
//Load in the Check Out Invoices
mysqli_query($dbc, "INSERT INTO `$table_name` (`invoice_date`, `type`, `category`, `heading`, `total`)
	SELECT `invoice`.`invoice_date`, 'service', '', `invoice_patient`.`service_category`, `invoice_patient`.`sub_total`
	FROM `invoice_patient` LEFT JOIN `invoice` ON `invoice`.`invoiceid`=`invoice_patient`.`invoiceid`
	WHERE `invoice`.`invoice_date` >= '$search_start' AND `invoice`.`invoice_date` <= '$search_end' AND `invoice_patient`.`paid`!='On Account' AND `invoice_patient`.`paid`!='' AND `invoice_patient`.`paid` IS NOT NULL AND `invoice_patient`.`service_category` != ''");
mysqli_query($dbc, "INSERT INTO `$table_name` (`invoice_date`, `type`, `category`, `heading`, `total`)
	SELECT `invoice`.`invoice_date`, 'service', '', `invoice_insurer`.`service_category`, `invoice_insurer`.`sub_total`
	FROM `invoice_insurer` LEFT JOIN `invoice` ON `invoice`.`invoiceid`=`invoice_insurer`.`invoiceid`
	WHERE `invoice`.`invoice_date` >= '$search_start' AND `invoice`.`invoice_date` <= '$search_end' AND `invoice_insurer`.`paid`='Yes' AND `invoice_insurer`.`service_category` != ''");
mysqli_query($dbc, "INSERT INTO `$table_name` (`invoice_date`, `type`, `category`, `heading`, `total`)
	SELECT `invoice`.`invoice_date`, 'inventory', '', IFNULL(`inventory`.`category`,'Miscellaneous'), MAX(`invoice_patient`.`sub_total`)
	FROM `invoice_patient` LEFT JOIN `invoice_lines` ON `invoice_patient`.`invoiceid`=`invoice_lines`.`invoiceid` AND `invoice_patient`.`product_name`=`invoice_lines`.`description`
		LEFT JOIN `inventory` ON `invoice_lines`.`item_id`=`inventory`.`inventoryid` OR (`invoice_lines`.`item_id` IS NULL AND `invoice_patient`.`product_name`=`inventory`.`name`)
		LEFT JOIN `invoice` ON `invoice`.`invoiceid`=`invoice_patient`.`invoiceid`
	WHERE `invoice`.`invoice_date` >= '$search_start' AND `invoice`.`invoice_date` <= '$search_end' AND `invoice_patient`.`paid`!='On Account' AND `invoice_patient`.`paid`!='' AND
		`invoice_patient`.`paid` IS NOT NULL AND `invoice_patient`.`service_category` = '' GROUP BY `invoice_patient`.`invoicepatientid`");
mysqli_query($dbc, "INSERT INTO `$table_name` (`invoice_date`, `type`, `category`, `heading`, `total`)
	SELECT `invoice`.`invoice_date`, 'inventory', '', IFNULL(`inventory`.`category`,'Miscellaneous'), MAX(`invoice_insurer`.`sub_total`)
	FROM `invoice_insurer` LEFT JOIN `invoice_lines` ON `invoice_insurer`.`invoiceid`=`invoice_lines`.`invoiceid` AND `invoice_insurer`.`product_name`=`invoice_lines`.`description`
		LEFT JOIN `inventory` ON `invoice_lines`.`item_id`=`inventory`.`inventoryid` OR (`invoice_lines`.`item_id` IS NULL AND `invoice_insurer`.`product_name`=`inventory`.`name`)
		LEFT JOIN `invoice` ON `invoice`.`invoiceid`=`invoice_insurer`.`invoiceid`
	WHERE `invoice`.`invoice_date` >= '$search_start' AND `invoice`.`invoice_date` <= '$search_end' AND `invoice_insurer`.`paid`='Yes' AND `invoice_insurer`.`service_category` = '' GROUP BY `invoice_insurer`.`invoiceinsurerid`");
/*$invoices = mysqli_query($dbc, "SELECT `invoice`.`service_date`, `invoice`.`serviceid`, `invoice`.`fee`, `inventoryid`, `invoice`.`sell_price`, `invoice`.`quantity`,
	IFNULL(`invoice_patient`.`patient_price` / `invoice`.`total_price`,0) `patient_ratio`, IFNULL(`invoice_insurer`.`insurer_price` / `invoice`.`total_price`,0) `ins_ratio`, IFNULL(`invoice_patient`.`paid`,'No') `patient_paid`, IFNULL(`invoice_insurer`.`paid`,'No') `ins_paid`
	FROM `invoice` LEFT JOIN `invoice_patient` ON `invoice`.`invoiceid`=`invoice_patient`.`invoiceid`
	LEFT JOIN `invoice_insurer` ON `invoice`.`invoiceid`=`invoice_insurer`.`invoiceid`
	WHERE `service_date` >= '$starttime' AND `service_date` <= '$endtime' AND `invoice`.`deleted`=0");
while($invoice = mysqli_fetch_array($invoices)) {
	$serviceid = explode(',',$invoice['serviceid']);
	$servicefee = explode(',',$invoice['fee']);
	foreach($serviceid as $i => $id) {
		if(!empty($id)) {
			$service = mysqli_fetch_array(mysqli_query($dbc, "SELECT `category`, `heading` FROM `services` WHERE `serviceid`='$id'"));
			if($invoice['patient_paid'] != 'No' && $invoice['patient_paid'] != 'On Account' && $invoice['patient_paid'] != '') {
				$amt = $servicefee[$i] * $invoice['patient_ratio'];
				mysqli_query($dbc, "INSERT INTO `$table_name` (`invoice_date`, `type`, `heading`, `category`, `total`) VALUES ('".$invoice['service_date']."', 'service', '".$service['heading']."', '".$service['category']."', '".$amt."')");
			}
			if($invoice['insurer_paid'] == 'Yes') {
				$amt = $servicefee[$i] * $invoice['insurer_ratio'];
				mysqli_query($dbc, "INSERT INTO `$table_name` (`invoice_date`, `type`, `heading`, `category`, `total`) VALUES ('".$invoice['service_date']."', 'service', '".$service['heading']."', '".$service['category']."', '".$amt."')");
			}
		}
	}
	$inventory = explode(',',$invoice['inventoryid']);
	$inventoryprice = explode(',',$invoice['sell_price']);
	$inventoryqty = explode(',',$invoice['quantity']);
	foreach($inventory as $i => $id) {
		if(!empty($id) && $inventoryqty[$i] > 0) {
			$inventory = mysqli_fetch_array(mysqli_query($dbc, "SELECT `category`, `name` FROM `inventory` WHERE `inventoryid`='$id'"));
			$total = $inventoryprice[$i] * $inventoryqty[$i];
			if($invoice['patient_paid'] != 'No' && $invoice['patient_paid'] != 'On Account') {
				$amt = $total * $invoice['patient_ratio'];
				mysqli_query($dbc, "INSERT INTO `$table_name` (`invoice_date`, `type`, `heading`, `category`, `total`) VALUES ('".$invoice['service_date']."', 'service', '".$service['category']."', '".$service['heading']."', '".$amt."')");
			}
			if($invoice['insurer_paid'] == 'Yes') {
				$amt = $total * $invoice['insurer_ratio'];
				mysqli_query($dbc, "INSERT INTO `$table_name` (`invoice_date`, `type`, `heading`, `category`, `total`) VALUES ('".$invoice['service_date']."', 'service', '".$service['category']."', '".$service['heading']."', '".$amt."')");
			}
		}
	}
}*/

for ($year = $startyear; $year <= $endyear; $year++) {
	//Pull revenue amounts by category
	$total_rows = "SELECT COUNT(DISTINCT CONCAT(`type`,`category`,`heading`)) numrows FROM `$table_name`";
	$months = mysqli_fetch_array(mysqli_query($dbc, "SELECT `type`, `heading`, `category`,
		SUM(IF(`invoice_date` LIKE '".$year."-01%', `total`, 0)) `JAN`,
		SUM(IF(`invoice_date` LIKE '".$year."-02%', `total`, 0)) `FEB`,
		SUM(IF(`invoice_date` LIKE '".$year."-03%', `total`, 0)) `MAR`,
		SUM(IF(`invoice_date` LIKE '".$year."-04%', `total`, 0)) `APR`,
		SUM(IF(`invoice_date` LIKE '".$year."-05%', `total`, 0)) `MAY`,
		SUM(IF(`invoice_date` LIKE '".$year."-06%', `total`, 0)) `JUN`,
		SUM(IF(`invoice_date` LIKE '".$year."-07%', `total`, 0)) `JUL`,
		SUM(IF(`invoice_date` LIKE '".$year."-08%', `total`, 0)) `AUG`,
		SUM(IF(`invoice_date` LIKE '".$year."-09%', `total`, 0)) `SEP`,
		SUM(IF(`invoice_date` LIKE '".$year."-10%', `total`, 0)) `OCT`,
		SUM(IF(`invoice_date` LIKE '".$year."-11%', `total`, 0)) `NOV`,
		SUM(IF(`invoice_date` LIKE '".$year."-12%', `total`, 0)) `DEC`
		FROM `$table_name`"));
	$totals = [ $months['JAN'], $months['FEB'], $months['MAR'], $months['APR'], $months['MAY'], $months['JUN'], $months['JUL'], $months['AUG'], $months['SEP'], $months['OCT'], $months['NOV'], $months['DEC'] ];
	$revenue_sql = "SELECT LOWER(`type`) type, `heading`, `category`,
		SUM(IF(`invoice_date` LIKE '".$year."-01%', `total`, 0)) `JAN`,
		SUM(IF(`invoice_date` LIKE '".$year."-02%', `total`, 0)) `FEB`,
		SUM(IF(`invoice_date` LIKE '".$year."-03%', `total`, 0)) `MAR`,
		SUM(IF(`invoice_date` LIKE '".$year."-04%', `total`, 0)) `APR`,
		SUM(IF(`invoice_date` LIKE '".$year."-05%', `total`, 0)) `MAY`,
		SUM(IF(`invoice_date` LIKE '".$year."-06%', `total`, 0)) `JUN`,
		SUM(IF(`invoice_date` LIKE '".$year."-07%', `total`, 0)) `JUL`,
		SUM(IF(`invoice_date` LIKE '".$year."-08%', `total`, 0)) `AUG`,
		SUM(IF(`invoice_date` LIKE '".$year."-09%', `total`, 0)) `SEP`,
		SUM(IF(`invoice_date` LIKE '".$year."-10%', `total`, 0)) `OCT`,
		SUM(IF(`invoice_date` LIKE '".$year."-11%', `total`, 0)) `NOV`,
		SUM(IF(`invoice_date` LIKE '".$year."-12%', `total`, 0)) `DEC`
		FROM `$table_name`
		GROUP BY CONCAT(`type`,`category`,`heading`)
		ORDER BY `type`,`category`,`heading` LIMIT $offset, $rowsPerPage";
	$revenues = mysqli_query($dbc, $revenue_sql);

	$html = '<table style="border:1px solid black;border-collapse:collapse;height:2px">
		<thead>
			<tr class="hidden-xs hidden-sm" style="border:1px solid black;border-collapse:collapse;height:2px">
				<th style="border:1px solid black;border-collapse:collapse;height:2px">Revenue Items</th>';
				for($month = 0; $month < 12; $month++) {
					$dateObj   = DateTime::createFromFormat('!m', $month+1);
					$html .= '<th style="border:1px solid black;border-collapse:collapse;height:2px">'.$dateObj->format("F").'</th>';
				}
	$html .= '</tr>';
	$html .= '</thead>
		<tbody>';
			$tile_name = '';
			$category = '';
			$startmonth = ($startyear == $year ? intval(explode('-', $search_start)[1]) - 1 : 0);
			$endmonth = ($endyear == $year ? intval(explode('-', $search_end)[1]) - 1 : 11);
			while($row = mysqli_fetch_array($revenues)) {
				if($tile_name != $row['type']) {
					$tile_name = $row['type'];
					$html .= '<tr><td style="border:1px solid black;border-collapse:collapse;height:2px" colspan="13">'.strtoupper($tile_name).'</td></tr>';
				}
				if($category != $row['category']) {
					$category = $row['category'];
					$html .= '<tr><td style="border:1px solid black;border-collapse:collapse;height:2px" colspan="13">'.$category.'</td></tr>';
				}
				$html .= '<tr><td style="border:1px solid black;border-collapse:collapse;height:2px" data-title="Revenue Item">'.$row['heading'].'</td>';
				for($month = 0; $month < 12; $month++) {
					$dateObj = DateTime::createFromFormat('!m', $month+1);
					$amt = $row[strtoupper($dateObj->format('M'))];
					$html .= '<td data-title="'.$dateObj->format('F').'" >&nbsp;&nbsp;'.($month < $startmonth || $month > $endmonth ? '-' : '$'.number_format($amt, 2, '.', ',')).'</td>';
				}
				$html .= '</tr>';
			}
			$html .= '<tr style="font-weight: bold;">
				<td style="border:1px solid black;border-collapse:collapse;height:2px">Monthly Total</td>';
				for($month = 0; $month < 12; $month++) {
					$dateObj = DateTime::createFromFormat('!m', $month+1);
					$html .= '<td style="border:1px solid black;border-collapse:collapse;height:2px" data-title="'.$dateObj->format('F').'" >$'.number_format($totals[$month], 2, '.', ',').'</td>';
				}
			$html .= '</tr>';
			$html .= '<tr style="font-size: 1.5em; font-weight: bold;">
			<td style="border:1px solid black;border-collapse:collapse;height:2px" colspan="10" style="border-right: none;">Total Revenue for ';
			$html .= $year == $startyear ? $search_start : $year;
			$html .= '-01-01  to ';
			$html .= $year == $endyear ? $search_end : $year;
			$html .= '-12-31 </td>
				<td style="border:1px solid black;border-collapse:collapse;height:2px" data-title="Total" colspan="3">$' . number_format(array_sum($totals), 2, '.', ',') . '</td>
			</tr>';
			$html .= '
		</tbody>
	</table>';
	}

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('download/revenue_'.time().'.pdf', 'F');
    echo '<script type="text/javascript" language="Javascript">window.location.replace("profit_loss.php?tab=revenue");
    window.open("download/revenue_'.time().'.pdf", "fullscreen=yes");
    </script>';
}
?>
