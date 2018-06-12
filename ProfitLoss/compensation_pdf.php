<?php 
error_reporting(0);
ini_set('memory_limit','2048M');
ini_set('max_execution_time', 1000);
function profit_loss_pdf($dbc) {

	DEFINE('PDF_LOGO', 'logo.png');
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

    $pdf->SetMargins(PDF_MARGIN_LEFT, 5, PDF_MARGIN_RIGHT);

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

	$staff = mysqli_query($dbc, "SELECT `contactid`, `category`, `name`, `last_name`, `first_name` FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND `deleted`=0 AND `status`=1");
$contactlist = sort_contacts_array(mysqli_fetch_all($staff, MYSQLI_ASSOC));
	$html = '';
	for ($year = $startyear; $year <= $endyear; $year++) {
	$stat_holidays = explode(',',get_config($dbc, 'stat_holiday'));
	$totals = [0,0,0,0,0,0,0,0,0,0,0,0];

	$html .= '<table style="border:1px solid black;border-collapse:collapse;height:2px">
		<thead>
			<tr class="hidden-xs hidden-sm" style="border:1px solid black;border-collapse:collapse;height:2px">
				<th>Staff Member</th>';
				for($month = 0; $month < 12; $month++) {
					$dateObj   = DateTime::createFromFormat('!m', $month+1);
					$html .= '<th style="border:1px solid black;border-collapse:collapse;height:2px">'.$dateObj->format("F").'</th>';
				} 
		$html .= '</tr>
		</thead>
		<tbody>';
			foreach($contactlist as $contactid) {
				$html .= '<tr style="border:1px solid black;border-collapse:collapse;height:2px"><td style="border:1px solid black;border-collapse:collapse;height:2px" data-title="Staff">'.get_contact($dbc, $contactid).'</td>';
				$startmonth = 0;
				$endmonth = 12;
				if($startyear == $year) {
					$startmonth = intval(explode('-', $search_start)[1]) - 1;
				}
				if($endyear == $year) {
					$endmonth = intval(explode('-', $search_end)[1]);
				}
				for($month = 0; $month < $startmonth; $month++) {
					$dateObj = DateTime::createFromFormat('!m', $month+1);
					$html .= '<td style="border:1px solid black;border-collapse:collapse;height:2px" data-title="'.$dateObj->format("F").'" >-</td>';
				}
				for($month = $startmonth; $month < $endmonth; $month++) {
					$dateObj = DateTime::createFromFormat('!m', $month+1);
					$date_part = $year."-".$dateObj->format('m');
					$starttimemonth = $date_part.'-01';
					if($startyear == $year && $month == $startmonth) {
						$starttimemonth = $search_start;
					}
					//$total_stat_holiday = $stat_days[$month];
					$endtimemonth = date('Y-m-t',strtotime($starttimemonth));
					if($endyear == $year && $month == $endmonth) {
						$endtimemonth = $search_end;
					}
					$amt = 0;
					$all_booking = 0;
					$vacation_pay_perc = 0;
					$vacation_pay = 0;
					$grand_stat_total = 0;
					$avg_per_day_stat = 0;
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
						//$html .= $report_data;
						include ('../Reports/report_compensation_preformance_logic.php');
						include ('../Reports/report_compensation_inventory.php');
						//$html .= $report_data;
						$stat_holidays = get_config($dbc, 'stat_holiday');

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
						$amt = $total_base_service+$total_base_inv+$avg_per_day_stat+$vacation_pay;
						$totals[$month] += $amt;
					}
					$html .= '<td data-title="'.$dateObj->format("F").'" style="border:1px solid black;border-collapse:collapse;height:2px">$'.number_format($amt, 2, ".", ",").'</td>';
				}
				for($month = $endmonth; $month < 12; $month++) {
					$dateObj = DateTime::createFromFormat('!m', $month+1);
					$html .= '<td data-title="'.$dateObj->format("F").'" style="border:1px solid black;border-collapse:collapse;height:2px">-</td>';
				}
				$html .= '</tr>';
			}
			
			$html .= '<tr style="border:1px solid black;border-collapse:collapse;height:2px"><td>Monthly Total</td>';
				for($month = 0; $month < 12; $month++) {
					$dateObj = DateTime::createFromFormat('!m', $month+1);
					$html .= '<td data-title="'.$dateObj->format("F").'" style="border:1px solid black;border-collapse:collapse;height:2px">$'.number_format($totals[$month], 2, ".", ",").'</td>';
				}
			$html .= '</tr>';
			$html .= '<tr style="border:1px solid black;border-collapse:collapse;height:2px">
				<td colspan="10" style="border:1px solid black;border-collapse:collapse;height:2px">Total Compensation for ';
			$html .= $year == $startyear ? $starttime : $year;
			$html .= '-01-01'.' to ';
			$html .= $year == $endyear ? $endtime : $year;
			$html .= '-12-31 </td>
				<td data-title="Total" colspan="3" style="border:1px solid black;border-collapse:collapse;height:2px">$ '. number_format(array_sum($totals), 2, ".", ","). '</td>';
			$html .= '</tr>
		</tbody>
	</table> ';
	}

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('download/compensation_'.$search_start.'.pdf', 'F');
    echo '<script type="text/javascript" language="Javascript">window.location.replace("profit_loss.php?tab=compensation");
    window.open("download/compensation_'.$search_start.'.pdf", "fullscreen=yes");
    </script>';
}
?>



