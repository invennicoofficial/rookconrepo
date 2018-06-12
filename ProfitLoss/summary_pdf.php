<?php 
error_reporting(1);
function profit_loss_summary_pdf($dbc) {
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

	$table_name = 'summary_profit_loss';
if(!mysqli_query($dbc, "CREATE TEMPORARY TABLE IF NOT EXISTS `$table_name` (`status` VARCHAR(40), `invoice_date` VARCHAR(12), `type` VARCHAR(12), `heading` VARCHAR(200), `category` VARCHAR(200), `total` DECIMAL(10,2))")) {
	echo mysqli_error($dbc);
}
//Load in the Point of Sale data
mysqli_query($dbc, "INSERT INTO `$table_name` (`status`, `invoice_date`, `type`, `heading`, `category`, `total`) SELECT pos.`status`, pos.`invoice_date`, posp.`type_category`,
	IFNULL(i.`name`, IFNULL(p.`heading`, IFNULL(s.`heading`, IFNULL(`misc_product`, 'Other')))) heading,
	IFNULL(i.`category`, IFNULL(p.`category`, IFNULL(s.`category`, IFNULL(i.`name`, 'Other')))) category, 
	(posp.`quantity` * posp.`price`) total
	FROM `point_of_sell_product` posp LEFT JOIN `point_of_sell` pos ON posp.`posid`=pos.`posid`
		LEFT JOIN `products` p ON posp.`type_category`='product' AND posp.`inventoryid`=p.`productid`
		LEFT JOIN `inventory` i ON posp.`type_category`='inventory' AND posp.`inventoryid`=i.`inventoryid`
		LEFT JOIN `services` s ON posp.`type_category`='service' AND posp.`inventoryid`=s.`serviceid`
	WHERE `invoice_date` >= '$search_start' AND `invoice_date` <= '$search_end' AND pos.`status` NOT IN ('Voided')
	ORDER BY IF(IFNULL(i.`category`, IFNULL(p.`category`, IFNULL(s.`category`, IFNULL(i.`name`, 'Other')))) = 'Other', 'ZZZ', IFNULL(i.`category`, IFNULL(p.`category`, IFNULL(s.`category`, IFNULL(i.`name`, 'Other'))))), `heading`");
//Load in the Check Out Invoices
mysqli_query($dbc, "INSERT INTO `$table_name` (`status`, `invoice_date`, `type`, `heading`, `category`, `total`)
	SELECT IF(`invoice_patient`.`paid`!='On Account' AND `invoice_patient`.`paid`!='' AND `invoice_patient`.`paid` IS NOT NULL,'Completed','Unpaid'), `invoice`.`invoice_date`, '', IF(`invoice_patient`.`service_category`='','Inventory',`invoice_patient`.`service_category`), IF(`invoice_patient`.`service_name`='',`invoice_patient`.`product_name`,`invoice_patient`.`service_name`), `sub_total`
	FROM `invoice_patient` LEFT JOIN `invoice` ON `invoice`.`invoiceid`=`invoice_patient`.`invoiceid`
	WHERE `invoice`.`invoice_date` >= '$search_start' AND `invoice`.`invoice_date` <= '$search_end'");
mysqli_query($dbc, "INSERT INTO `$table_name` (`status`, `invoice_date`, `type`, `heading`, `category`, `total`)
	SELECT IF(`invoice_insurer`.`paid`='Yes','Completed','Unpaid'), `invoice`.`invoice_date`, '', IF(`invoice_insurer`.`service_category`='','Inventory',`invoice_insurer`.`service_category`), IF(`invoice_insurer`.`service_name`='',`invoice_insurer`.`product_name`,`invoice_insurer`.`service_name`), `sub_total`
	FROM `invoice_insurer` LEFT JOIN `invoice` ON `invoice`.`invoiceid`=`invoice_insurer`.`invoiceid`
	WHERE `invoice`.`invoice_date` >= '$search_start' AND `invoice`.`invoice_date` <= '$search_end'");
//$invoices = mysqli_query($dbc, "SELECT `invoice`.`service_date`, `invoice`.`serviceid`, `invoice`.`fee`, `inventoryid`, `invoice`.`sell_price`, `invoice`.`quantity`,
//	IFNULL(`invoice_patient`.`patient_price` / `invoice`.`total_price`,0) `patient_ratio`, IFNULL(`invoice_insurer`.`insurer_price` / `invoice`.`total_price`,0) `ins_ratio`, IFNULL(`invoice_patient`.`paid`,'No') `patient_paid`, IFNULL(`invoice_insurer`.`paid`,'No') `ins_paid`
//	FROM `invoice` LEFT JOIN `invoice_patient` ON `invoice`.`invoiceid`=`invoice_patient`.`invoiceid`
//	LEFT JOIN `invoice_insurer` ON `invoice`.`invoiceid`=`invoice_insurer`.`invoiceid`
//	WHERE `service_date` >= '$starttime' AND `service_date` <= '$endtime' AND `invoice`.`deleted`=0");
//while($invoice = mysqli_fetch_array($invoices)) {
//	$serviceid = explode(',',$invoice['serviceid']);
//	$servicefee = explode(',',$invoice['fee']);
//	foreach($serviceid as $i => $id) {
//		if(!empty($id)) {
//			$service = mysqli_fetch_array(mysqli_query($dbc, "SELECT `category`, `heading` FROM `services` WHERE `serviceid`='$id'"));
//			$amt = $servicefee[$i] * $invoice['patient_ratio'];
//			mysqli_query($dbc, "INSERT INTO `$table_name` (`status`, `invoice_date`, `type`, `heading`, `category`, `total`) VALUES ('".($invoice['patient_paid'] != 'No' && $invoice['patient_paid'] != 'On Account' && $invoice['patient_paid'] != '' ? 'Completed' : 'Incomplete')."', '".$invoice['service_date']."', 'service', '".$service['heading']."', '".$service['category']."', '".$amt."')");
//
//			$amt = $servicefee[$i] * $invoice['insurer_ratio'];
//			mysqli_query($dbc, "INSERT INTO `$table_name` (`status`, `invoice_date`, `type`, `heading`, `category`, `total`) VALUES ('".($invoice['insurer_paid'] == 'Yes' ? 'Completed' : 'Incomplete')."', '".$invoice['service_date']."', 'service', '".$service['heading']."', '".$service['category']."', '".$amt."')");
//		}
//	}
//	$inventory = explode(',',$invoice['inventoryid']);
//	$inventoryprice = explode(',',$invoice['sell_price']);
//	$inventoryqty = explode(',',$invoice['quantity']);
//	foreach($inventory as $i => $id) {
//		if(!empty($id) && $inventoryqty[$i] > 0) {
//			$inventory = mysqli_fetch_array(mysqli_query($dbc, "SELECT `category`, `name` FROM `inventory` WHERE `inventoryid`='$id'"));
//			$total = $inventoryprice[$i] * $inventoryqty[$i];
//			$amt = $total * $invoice['patient_ratio'];
//			mysqli_query($dbc, "INSERT INTO `$table_name` (`status`, `invoice_date`, `type`, `heading`, `category`, `total`) VALUES ('".($invoice['patient_paid'] != 'No' && $invoice['patient_paid'] != 'On Account' ? 'Completed' : 'Incomplete')."','".$invoice['service_date']."', 'inventory', '".$inventory['category']."', '".$inventory['name']."', '$amt')");
//
//			$amt = $total * $invoice['insurer_ratio'];
//			mysqli_query($dbc, "INSERT INTO `$table_name` (`status`, `invoice_date`, `type`, `heading`, `category`, `total`) VALUES ('".($invoice['insurer_paid'] == 'Yes' ? 'Completed' : 'Incomplete')."','".$invoice['service_date']."', 'inventory', '".$inventory['category']."', '".$inventory['name']."', '$amt')");
//		}
//	}
//}
$total_revenue = mysqli_fetch_array(mysqli_query($dbc, "SELECT SUM(`total`) `total`
	FROM `$table_name` WHERE `invoice_date` >= '$search_start' AND `invoice_date` <= '$search_end' AND `status` IN ('Completed')"))['total'];
$total_receivable = mysqli_fetch_array(mysqli_query($dbc, "SELECT SUM(`total`) `total`
	FROM `$table_name` WHERE `invoice_date` >= '$search_start' AND `invoice_date` <= '$search_end' AND `status` NOT IN ('Completed')"))['total'];
mysqli_query($dbc, "DROP TEMPORARY TABLE IF EXISTS `$table_name`"); 
	
//Calculate Staff Compensation
include_once('../Reports/compensation_function.php');
$total_compensation = 0;
$staff_sql = mysqli_query($dbc, "SELECT `contactid`, `category`, `name`, `last_name`, `first_name` FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `status`=1 AND `deleted`=0");

$stat_holidays = explode(',',get_config($dbc, 'stat_holiday'));
while($contactlist = mysqli_fetch_array($staff_sql)) {
	$contactid = $contactlist['contactid'];
	for ($year = $startyear; $year <= $endyear; $year++) {
		$startmonth = 0;
		$endmonth = 12;
		if($startyear == $year) {
			$startmonth = intval(explode('-', $search_start)[1]) - 1;
		}
		if($endyear == $year) {
			$endmonth = intval(explode('-', $search_end)[1]);
		}
		for($month = $startmonth; $month < $endmonth; $month++) {
			$dateObj = DateTime::createFromFormat('!m', $month+1);
			$date_part = $year."-".$dateObj->format('m');
			$starttimemonth = $date_part.'-01';
			if($startyear == $year && $month == $startmonth) {
				$starttimemonth = $search_start;
			}
			$total_stat_holiday = $stat_days[$month];
			$endtimemonth = date('Y-m-t',strtotime($starttimemonth));
			if($endyear == $year && $month == $endmonth) {
				$endtimemonth = $search_end;
			}
			$stat_start = $stat_starts[$month];
			$stat_end = $stat_ends[$month];
			$amt = 0;
			$all_booking = 0;
			$vacation_pay_perc = 0;
			$vacation_pay = 0;
			$grand_stat_total = 0;
			$avg_per_day_stat = 0;
			$therapistid = $contactid;
			if(strtotime($starttimemonth) <= strtotime('today')) {
				$starttime = $starttimemonth;
				$endtime = $endtimemonth;
				$invoicetype = "'New','Refund','Adjustment'";
				
				$table_style = $table_row_style = $grand_total_style = '';
				$row = mysqli_fetch_array(mysqli_query($dbc, "SELECT `contacts`.contactid, `contacts`.scheduled_hours, `contacts`.`schedule_days`, `contacts`.category_contact, IFNULL(`base_pay`,'0*#*0') base_pay FROM contacts LEFT JOIN `compensation` ON `contacts`.`contactid`=`compensation`.`contactid` AND '$starttime' BETWEEN `compensation`.`start_date` AND `compensation`.`end_date` WHERE `contacts`.contactid='$contactid'"));
				$therapistid = $row['contactid'];
				$category_contact = $row['category_contact'];
				$schedule = $row['schedule_days'];
				$base_pay = explode('*#*',$row['base_pay']);

				include ('../Reports/report_compensation_services.php');
				include ('../Reports/report_compensation_preformance_logic.php');
				include ('../Reports/report_compensation_inventory.php');

				foreach(explode(',',$stat_holidays) as $stat_day) {
					if($stat_day >= $starttime && $stat_day <= $endtime) {;
						$stat_day = strtotime($stat_day);
						$weekday = date('w',$stat_day);
						if($schedule == '' || in_array(date('w',$stat_day), explode(',',$schedule))) {
							$stat_start = date('Y-m-d',strtotime('-63 day',$stat_day));
							$stat_end = date('Y-m-d',strtotime('-1 day',$stat_day));
							include('report_compensation_stat_holiday.php');
						}
					}
				}

				$vacation_pay = (($total_base_service+$total_base_inv)*$vacation_pay_perc)/100;
				$total_compensation += $total_base_service+$total_base_inv+$avg_per_day_stat+$vacation_pay;
			}
		}
	}
}
$inv_cost_field = get_config($dbc,'inventory_cost');
$total_costs = mysqli_fetch_array(mysqli_query($dbc, "SELECT SUM(`$inv_cost_field`) * rs.`quantity` cost
	FROM `receive_shipment` rs LEFT JOIN `inventory` i ON rs.`inventoryid`=i.`inventoryid` WHERE `date_added` >= '$search_start' AND `date_added` <= '$search_end'"))['cost'];
$total_expense = mysqli_fetch_array(mysqli_query($dbc, "SELECT SUM(total) `total` FROM (SELECT contacts.contactid, expense.title heading, ex_date expense_date, gst tax, amount total
	FROM expense LEFT JOIN contacts ON contacts.category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND expense.staff=CONCAT(contacts.first_name,' ',contacts.last_name) UNION
	SELECT submit_staff, expense_heading heading, expense_date, tax, actual_amount total FROM budget_expense) expenses WHERE expense_date >= '$search_start' AND expense_date <= '$search_end'"))['total'];
$total = $total_revenue + $total_receivable - $total_compensation - $total_expense - $total_costs;

if(!empty($_POST['search_start'])) {
	$starttime_summary = $_POST['search_start'];
} else if (!empty($_GET['search_start'])) {
    $starttime_summary = $_GET['search_start'];
}
if(!empty($_POST['search_end'])) {
    $endtime_summary = $_POST['search_end'];
} else if (!empty($_GET['search_end'])) {
    $endtime_summary = $_GET['search_end'];
}
$html = '<table class="table table-bordered">
	<thead>
		<tr class="hidden-xs hidden-sm">
			<th></th>
			<th>Profit / Loss</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td style="border:1px solid black;border-collapse:collapse;height:2px" data-title="">Total Revenue:</td>';
	$html .= '<td style="border:1px solid black;border-collapse:collapse;height:2px" data-title="Total Revenue" >$' . number_format($total_revenue, 2, '.', ',') . '</td>
		</tr>
		<tr>
			<td style="border:1px solid black;border-collapse:collapse;height:2px" data-title="">Total Receivables:</td>';
	$html .= '<td style="border:1px solid black;border-collapse:collapse;height:2px" data-title="Total Receivables" >$' . number_format($total_receivable, 2, '.', ',') . '</td>
		</tr>
		<tr>
			<td style="border:1px solid black;border-collapse:collapse;height:2px" data-title="">Total Compensation:</td>';
	$html .= '<td style="border:1px solid black;border-collapse:collapse;height:2px" data-title="Total Compensation" >$' . number_format($total_compensation, 2, '.', ',') . '</td>
		</tr>
		<tr>
			<td style="border:1px solid black;border-collapse:collapse;height:2px" data-title="">Total Expenses:</td>';
	$html .= '<td style="border:1px solid black;border-collapse:collapse;height:2px" data-title="Total Expenses" >$' . number_format($total_expense, 2, '.', ',').'</td>
		</tr>
		<tr>
			<td style="border:1px solid black;border-collapse:collapse;height:2px" data-title="">Total Costs:</td>';
	$html .=  '<td style="border:1px solid black;border-collapse:collapse;height:2px" data-title="Total Costs" >$' . number_format($total_costs, 2, '.', ',') . '</td>;
		</tr>
		<tr style="font-size: 1.5em; font-weight: bold;">';
	$html .= '<td style="border:1px solid black;border-collapse:collapse;height:2px">Summary Profit / Loss for ' . $starttime_summary.' to '.$endtime_summary . '</td>
			<td style="border:1px solid black;border-collapse:collapse;height:2px" data-title="Summary Profit / Loss" >$' . number_format($total, 2, '.', ',') . '</td>
		</tr>';
	$html .= '</tbody>
</table>';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('download/summary_'.time().'.pdf', 'F');
    echo '<script type="text/javascript" language="Javascript">window.location.replace("profit_loss.php?tab=summary");
    window.open("download/summary_'.time().'.pdf", "fullscreen=yes");
    </script>';
}
?>
