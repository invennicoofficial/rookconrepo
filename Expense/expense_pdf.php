<?php require_once('../include.php');
checkAuthorised('expense');
require_once('../tcpdf/tcpdf.php');
error_reporting(0);
ob_clean();

$get_config = mysqli_fetch_array(mysqli_query($dbc, "SELECT `pdf_logo`, `pdf_header` FROM `field_config_expense`"));
$head_logo = get_config($dbc, 'expense_logo');
$pdf_header = get_config($dbc, 'expense_header');
$pdf_footer = get_config($dbc, 'expense_footer');
$staff_name = get_contact($dbc, $staff);
$display_month = date('F Y', strtotime($search_month));
DEFINE('HEADER_LOGO', $head_logo);
DEFINE('HEADER_TEXT', html_entity_decode($pdf_header));
DEFINE('FOOTER_TEXT', $pdf_footer == '' ? "<em>Expense Report".($_GET['min_date'] != '' ? ' From '.$_GET['min_date'] : '').($_GET['max_date'] != '' ? ' To '.$_GET['max_date'] : '')."</em>" : html_entity_decode($pdf_footer));

class MYPDF extends TCPDF {

	//Page header
	public function Header() {
		if($front_client_info != '') {
			if ($this->PageNo() > 1) {
				if(HEADER_LOGO != '') {
					$image_file = 'download/'.HEADER_LOGO;
					$this->Image($image_file, 10, 5, 25, '', '', '', 'T', false, 300, 'L', false, false, 0, false, false, false);
				}

				if(HEADER_TEXT != '') {
					$this->setCellHeightRatio(0.7);
					$this->SetFont('helvetica', '', 10);
					$header_text = '<p style="text-align:right;">'.HEADER_TEXT.'</p>';
					$this->writeHTMLCell(0, 0, 0 , 5, $header_text, 0, 0, false, (HEADER_LOGO == '' ? 'C' : 'R'), true);
				}
			}
		} else {
			if(HEADER_LOGO != '') {
				$image_file = 'download/'.HEADER_LOGO;
				$this->Image($image_file, 10, 5, 25, '', '', '', 'T', false, 300, 'L', false, false, 0, false, false, false);
			}

			if(HEADER_TEXT != '') {
				$this->setCellHeightRatio(0.7);
				$this->SetFont('helvetica', '', 10);
				$header_text = '<p style="text-align:right;">'.HEADER_TEXT.'</p>';
				$this->writeHTMLCell(0, 0, 0 , 5, $header_text, 0, 0, false, (HEADER_LOGO == '' ? 'C' : 'R'), true);
			}
		}
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		$this->SetFont('helvetica', 'I', 8);
		$footer_text = '<p style="text-align:right;">'.$this->getAliasNumPage().'</p>';
		$this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "R", true);

		$this->SetY(-15);
		$this->setCellHeightRatio(0.7);
		$this->SetFont('helvetica', '', 8);
		$footer_text = FOOTER_TEXT;
		$this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "C", true);
	}
}

$pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$margin_height = ($head_logo == '' && $pdf_header == '' ? 15 : 30);
$pdf->SetMargins(PDF_MARGIN_LEFT, $margin_height, PDF_MARGIN_RIGHT);
$pdf->AddPage();

$pdf->SetFont('helvetica', '', 14);
$pdf->Write(0, "Expense Report".($_GET['min_date'] != '' ? ' From '.$_GET['min_date'] : '').($_GET['max_date'] != '' ? ' To '.$_GET['max_date'] : ''), '', 0, 'C', true, 0, false, false, 0);
$pdf->Ln();

$html = '<table border="1" cellpadding=2 cellspacing=0 style="width:100%">';
$html .= "<tr>";
$colspan = $totalspan = $tempcol = 0;
$html .= '<th align="center">Heading</th>';
$tempcol++;
$colspan++;
$config_sql = "SELECT expense, expense_dashboard, exchange_buffer, gst_name, gst_amt, pst_name, pst_amt, hst_name, hst_amt, expense_types, expense_rows FROM field_config_expense WHERE `tab`='$current_tab' UNION SELECT
	'Flight,Hotel,Breakfast,Lunch,Dinner,Beverages,Transportation,Entertainment,Gas,Misc',
	'Description,Date,Receipt,Type,Day Expense,Amount,Tax,Total', '0', 'GST', '5', 'PST', '0', 'HST', '0', 'Meals,Tip', 1";
$get_expense_config = mysqli_fetch_assoc(mysqli_query($dbc,$config_sql));
$value_config = ','.$get_expense_config['expense'].',';
$db_config = ','.$get_expense_config['expense_dashboard'].',';
$db_config_arr = explode(',',trim($db_config,','));
$tips_and_tax = [];
if(strpos($db_config,'Tips') !== FALSE) {
	$tips_and_tax[] = 'Tips';
}
if(strpos($db_config,'Tax') !== FALSE) {
	$tips_and_tax[] = 'Tax';
}
$detail = '';
if(strpos($db_config,'Exchange') !== FALSE) {
	$tips_and_tax[] = 'Currency Conversion';
	$detail = ' After Currency Conversion';
}
foreach($db_config_arr as $field) {
	if($field == 'Contact') {
		$html .= '<th align="center">Expense Contact</th>';
		$tempcol++;
		$colspan++;
	} else if($field == 'Expense For') {
		$html .= '<th align="center">Expense Tab</th>';
		$tempcol++;
		$colspan++;
	} else if($field == 'Description') {
		$html .= '<th align="center">Description</th>';
		$tempcol++;
		$colspan++;
	} else if($field == 'Country') {
		$html .= '<th align="center">Country of Expense</th>';
		$tempcol++;
		$colspan++;
	} else if($field == 'Province') {
		$html .= '<th align="center">Province of Expense</th>';
		$tempcol++;
		$colspan++;
	} else if($field == 'Exchange') {
		$html .= '<th align="center">Exchange Currency</th>';
		$tempcol++;
		$colspan++;
	} else if($field == 'Date') {
		$html .= '<th align="center">Expense Date</th>';
		$tempcol++;
		$colspan++;
	} else if($field == 'Work Order') {
		$html .= '<th align="center">Work Order</th>';
		$tempcol++;
		$colspan++;
	} else if($field == 'Receipt') {
		$html .= '<th align="center">Receipt</th>';
		$tempcol++;
		$colspan++;
	} else if($field == 'Type') {
		$html .= '<th align="center">Expense Type</th>';
		$tempcol++;
		$colspan++;
	} else if($field == 'Day Expense') {
		$html .= '<th align="center">Day Expense</th>';
		$tempcol++;
		$colspan++;
	} else if($field == 'Amount') {
		$html .= '<th align="center">Amount'.(count($tips_and_tax) > 0 ? " (excluding ".implode(' & ', $tips_and_tax).")" : '').'</th>';
		$totalspan = ($totalspan > 0 ? $totalspan : $tempcol);
		$colspan++;
	} else if($field == 'Tips') {
		$html .= '<th align="center">Tips</th>';
		$totalspan = ($totalspan > 0 ? $totalspan : $tempcol);
		$colspan++;
	} else if($field == 'Third Tax') {
		$html .= '<th align="center">'.($hst_name == '' ? 'Third Tax' : $hst_name).'</th>';
		$totalspan = ($totalspan > 0 ? $totalspan : $tempcol);
		$colspan++;
	} else if($field == 'Local Tax') {
		$html .= '<th align="center">'.($pst_name == '' ? 'Additional Tax' : $pst_name).'</th>';
		$totalspan = ($totalspan > 0 ? $totalspan : $tempcol);
		$colspan++;
	} else if($field == 'Tax') {
		$html .= '<th align="center">'.($gst_name == '' ? 'Tax' : $gst_name).'</th>';
		$totalspan = ($totalspan > 0 ? $totalspan : $tempcol);
		$colspan++;
	} else if($field == 'Total') {
		$html .= '<th align="center">Total'.$detail.'</th>';
		$totalspan = ($totalspan > 0 ? $totalspan : $tempcol);
		$colspan++;
	} else if($field == 'Budget') {
		$html .= '<th align="center">Budget</th>';
		$totalspan = ($totalspan > 0 ? $totalspan : $tempcol);
		$colspan++;
	} else if($field == 'Reimburse') {
		$html .= '<th align="center">Reimburse</th>';
		$tempcol++;
		$colspan++;
	}
}
$html .= '<th align="center">Expense Status</th>';
$colspan++;
$html .= "</tr>";

$total_amount = 0;
$total_tips = 0;
$total_hst = 0;
$total_pst = 0;
$total_gst = 0;
$total_total = 0;
$total_balance = 0;

$status = filter_var($_GET['status'],FILTER_SANITIZE_STRING);
$min_date = filter_var($_GET['min_date'],FILTER_SANITIZE_STRING);
$max_date = filter_var($_GET['max_date'],FILTER_SANITIZE_STRING);
$categories_sql = "SELECT * FROM (SELECT CONCAT('EC ',`ec`,': ',`category`) `ec_code`, `category`, CONCAT('GL ',`gl`,': ',`heading`) `gl_code`, `heading` FROM `expense_categories` ORDER BY `ec`, `gl`) `categories` UNION SELECT 'Uncategorized', '', 'Miscellaneous', ''";
$category_query = mysqli_query($dbc, $categories_sql);
$category_group = '';
while($cat_row = mysqli_fetch_array($category_query)) {
	$category_value = $cat_row['category'];
	$heading_value = $cat_row['heading'];
	$result = mysqli_query($dbc, "SELECT * FROM `expense` WHERE (`status`='$status' OR '$status'='Submitted' AND `status`='') AND `deleted`=0 AND (`ex_date` >= '$min_date' OR '$min_date' = '') AND (`ex_date` <= '$max_date' OR '$max_date' = '') AND IFNULL(`category`,'')='$category_value' AND IFNULL(`title`,'')='$heading_value'");
	$row_count = mysqli_num_rows($result);
	if($category_group != $cat_row['ec_code']) {
		$category_group = $cat_row['ec_code'];
		$html .= '<tr><td colspan="'.$colspan.'" align="center"><h3>'.$category_group.' Expenses</h3></td></tr>';
	}

	for($i = 0; $i < $row_count; $i++) {
		$id = $contact = $expense_tab = $category = $heading = $description = $country = $province = $currency = $date = $work_order = $receipt = $type = $day_expense = $amount = $tips = $third_tax = $local_tax = $tax = $total = $budget = $status = $comments = '';
		$reimburse = $exchange = 1;
		if($row = mysqli_fetch_array($result)) {
			$id = $row['expenseid'];
			$contact = $row['contact'];
			$expense_tab = $row['expense_for'];
			$category = $row['category'];
			$heading = $row['title'];
			$description = strip_tags(html_entity_decode($row['description']));
			$country = $row['country'];
			$province = $row['province'];
			$exchange = $row['exchange_rate'];
			$currency = $row['currency'];
			$date = $row['ex_date'];
			$work_order = $row['work_order'];
			$receipt = $row['ex_file'];
			$type = $row['type'];
			$day_expense = $row['day_expense'];
			$amount = $row['amount'];
			$tips = $row['tips'];
			$third_tax = $row['hst'];
			$local_tax = $row['pst'];
			$tax = $row['gst'];
			$total = $row['total'];
			$budget = $row['balance'];
			$status = $row['status'];
			$reimburse = $row['reimburse'];
			$comments = $row['comments'];
		}
		
		$html .= "<tr>";
		$html .= '<td align="center">'.$cat_row['gl_code'].'</td>';
		foreach($db_config_arr as $field) {
			$html .= '<td align="center">';
			if($field == 'Contact') {
				$html .= $contact;
			} else if($field == 'Expense For') {
				$html .= ucwords($expense_tab);
			} else if($field == 'Description') {
				$html .= $description;
			} else if($field == 'Country') {
				$html .= $country;
			} else if($field == 'Province') {
				$html .= $province;
			} else if($field == 'Exchange') {
				$html .= $currency.' @ '.$exchange;
			} else if($field == 'Date') {
				$html .= $date;
			} else if($field == 'Work Order') {
				$html .= $work_order;
			} else if($field == 'Receipt') {
				$html .= ($receipt != '' ? '<a href="'.WEBSITE_URL.'/Expense/download/'.$receipt.'" target="_blank">View</a>' : 'No Receipt');
			} else if($field == 'Type') {
				$html .= $type;
			} else if($field == 'Day Expense') {
				$html .= '$' . $day_expense;
			} else if($field == 'Amount') {
				$html .= '$' . $amount;
			} else if($field == 'Tips') {
				$html .= '$' . $tips;
			} else if($field == 'Third Tax') {
				$html .= '$' . $third_tax;
			} else if($field == 'Local Tax') {
				$html .= '$' . $local_tax;
			} else if($field == 'Tax') {
				$html .= '$' . $tax;
			} else if($field == 'Total') {
				$html .= '$' . $total;
			} else if($field == 'Budget') {
				$html .= '$' . $budget;
			} else if($field == 'Reimburse') {
				$html .= ($reimburse ? 'To be reimbursed' : 'Not for reimbursement');
			}
			$html .= '</td>';
		}
		$html .= '<td>'.($status == '' ? 'Saved' : $status).'</td>';
		$html .= "</tr>";
		$total_amount += (float)$amount;
		$total_tips += (float)$tips;
		$total_hst += (float)$third_tax;
		$total_pst += (float)$local_tax;
		$total_gst += (float)$tax;
		$total_total += (float)$total;
		$total_balance += (float)$budget;
	}
}

$html .= '<tr><td colspan="'.$totalspan.'"><b>Total</b></td>';
echo '<pre>';
foreach($db_config_arr as $i => $field) {
	if($field == 'Amount') {
		$html .= '<td><b>$' . number_format($total_amount, 2, '.', '') . '</b></td>';
	} else if($field == 'Tips') {
		$html .= '<td><b>$' . number_format($total_tips, 2, '.', '') . '</b></td>';
	} else if($field == 'Third Tax') {
		$html .= '<td><b>$' . number_format($total_hst, 2, '.', '') . '</b></td>';
	} else if($field == 'Local Tax') {
		$html .= '<td><b>$' . number_format($total_pst, 2, '.', '') . '</b></td>';
	} else if($field == 'Tax') {
		$html .= '<td><b>$' . number_format($total_gst, 2, '.', '') . '</b></td>';
	} else if($field == 'Total') {
		$html .= '<td><b>$' . number_format($total_total, 2, '.', '') . '</b></td>';
	} else if($field == 'Budget') {
		$html .= '<td><b>$' . number_format($total_balance, 2, '.', '') . '</b></td>';
	} else if($i >= $totalspan) {
		$html .= "<td></td>";
	}
}
$html .= "<td></td></tr></table>";

$category_query = mysqli_query($dbc, $categories_sql);
$html .= "<h3>Summary by Categories</h3>";
$html .= '<table border="1" cellpadding=2 cellspacing=0 style="width:67%"><tr><th>Category & Heading</th><th>Expense Amount</th><th>Tax</th><th>Total</th></tr>';
$final_amt = $final_tax = $final_total = 0;
while($cat_row = mysqli_fetch_array($category_query)) {
	// $query_expenses and $min_rows should be set by the tab page
	// $min_open is a setting
	$category_value = $cat_row['category'];
	$heading_value = $cat_row['heading'];
	$html .= "<tr><td>".$cat_row['ec_code'].': '.$cat_row['gl_code']."</td>";
	$result = mysqli_query($dbc, "SELECT * FROM `expense` WHERE (`status`='$status' OR '$status'='Submitted' AND `status`='') AND `deleted`=0 AND (`ex_date` >= '$min_date' OR '$min_date' = '') AND (`ex_date` <= '$max_date' OR '$max_date' = '') AND IFNULL(`category`,'')='$category_value' AND IFNULL(`title`,'')='$heading_value'");
	$cat_amt = $cat_tax = $cat_total = 0;
	while($row = mysqli_fetch_array($result)) {
		$cat_amt += $row['amount'];
		$cat_tax += $row['pst'] + $row['gst'];
		$cat_total += $row['total'];
	}
	$final_amt += $cat_amt;
	$final_tax += $cat_tax;
	$final_total += $cat_total;
	$html .= "<td>$".number_format($cat_amt, 2, '.', '')."</td><td>$".number_format($cat_tax, 2, '.', '')."</td><td>$".number_format($cat_total, 2, '.', '')."</td></tr>";
}
$html .= "<tr><td><b>Totals</b></td><td><b>$".number_format($final_amt, 2, '.', '')."</b></td><td><b>$".number_format($final_tax, 2, '.', '')."</b></td><td><b>$".number_format($final_total, 2, '.', '')."</b></td></tr>";
$html .= "</table>";

$pdf->SetFont('helvetica', '', 8);
$pdf->setCellHeightRatio(1.75);
$pdf->writeHTML($html, true, false, true, false, '');

if (!file_exists('download/reports')) {
	mkdir('download/reports', 0777, true);
}
$pdf->SetMargins(PDF_MARGIN_LEFT, 20, PDF_MARGIN_RIGHT);
$pdf_name = 'download/reports/expenses_'.date('Y_m_d_h_i').'.pdf';
$pdf->Output($pdf_name, 'F');
echo '<script type="text/javascript"> window.location.replace("'.$pdf_name.'"); </script>';