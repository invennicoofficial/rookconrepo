<?php
/*
Client Listing
*/
include ('../include.php');
checkAuthorised('report');
include_once('../tcpdf/tcpdf.php');
error_reporting(0);

if(isset($_POST['printpdf'])) {
	$status = $_POST['report_status'];
	$from_date = $_POST['report_from'];
	$until_date = $_POST['report_until'];
    $today_date = date('Y-m-d');
	$pdf_name = "Download/site_work_orders_$today_date.pdf";

	class MYPDF extends TCPDF {

		public function Header() {
			$image_file = WEBSITE_URL.'/img/fresh-focus-logo-dark.png';
			$this->SetFont('helvetica', '', 13);
            $this->Image($image_file, 0, 10, 60, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
            $footer_text = 'Field Job Reports';
            $this->writeHTMLCell(0, 0, 0 , 40, $footer_text, 0, 0, false, "R", true);
		}

		// Page footer
		public function Footer() {
			// Location at 15 mm from bottom
			$this->SetY(-15);
			$this->SetFont('helvetica', '', 9);
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

	$pdf->AddPage('L', 'LETTER');
    $pdf->SetFont('helvetica', '', 9);

	$html = 'Period Start: '.$from_date.'<br />';
	$html .= 'Period End: '.$until_date.'<br />';
    $html .= work_orders($dbc, $status, $from_date, $until_date, 'padding:3px; border:1px solid black;', 'background-color:grey; color:black;', 'background-color:lightgrey; color:black;');

	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output($pdf_name, 'F');
    ?>

	<script>
		window.location.replace('<?php echo $pdf_name; ?>');
	</script>
<?php } ?>

</head>
<body>
<?php include_once ('../navigation.php');
?>

<div class="container triple-pad-bottom">
    <div class="row">
        <div class="col-md-12">

		<?php $search_status = (!empty($_GET['wo_type']) ? filter_var($_GET['wo_type'],FILTER_SANITIZE_STRING) : 'Approved');
		$search_from = '';
		$search_until = '';
		
		if (isset($_POST['search_from'])) {
			$search_from = $_POST['search_from'];
		}
		if (isset($_POST['search_until'])) {
			$search_until = $_POST['search_until'];
		} ?>
        <?php echo reports_tiles($dbc);  ?>
		<h2><?= ($search_status == 'Approved' ? 'Active ' : (($search_status == 'Archived' ? 'Closed ' : 'Pending '))) ?>Site Work Orders</h2>
        <a href='report_site_work_orders.php?type=operations&wo_type=Pending'><button type="button" class="btn brand-btn mobile-block <?= ($search_status == 'Pending' ? 'active_tab' : '') ?>" >Pending</button></a>&nbsp;&nbsp;
        <a href='report_site_work_orders.php?type=operations&wo_type=Approved'><button type="button" class="btn brand-btn mobile-block <?= ($search_status == 'Approved' ? 'active_tab' : '') ?>" >Active</button></a>&nbsp;&nbsp;
        <a href='report_site_work_orders.php?type=operations&wo_type=Archived'><button type="button" class="btn brand-btn mobile-block <?= ($search_status == 'Archived' ? 'active_tab' : '') ?>" >Closed</button></a>&nbsp;&nbsp;

        <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
            <center><div class="form-group">
				<div class="form-group col-sm-5">
					<label class="col-sm-4">From:</label>
					<div class="col-sm-8"><input name="search_from" type="text" class="datepicker form-control" value="<?php echo $search_from; ?>"></div>
                </div>
				<div class="form-group col-sm-5">
					<label class="col-sm-4">Until:</label>
					<div class="col-sm-8"><input name="search_until" type="text" class="datepicker form-control" value="<?php echo $search_until; ?>"></div>
				</div>
            <button type="submit" name="search_email_submit" value="Search" class="btn brand-btn mobile-block">Submit</button>
			<button type="button" onclick="window.location=''" name="display_all_inventory" value="Display All" class="btn brand-btn mobile-block">Display All</button></div></center>
        <button type="submit" name="printpdf" value="Print Report" class="btn brand-btn pull-right">Print Report</button>

            <input type="hidden" name="report_status" value="<?php echo $search_status; ?>">
            <input type="hidden" name="report_from" value="<?php echo $search_from; ?>">
            <input type="hidden" name="report_until" value="<?php echo $search_until; ?>">
            <br><br>

            <?php
                echo work_orders($dbc, $search_status, $search_from, $search_until);
            ?>

        </form>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>

<?php
function work_orders($dbc, $status = 'Active', $from_date = '', $until_date = '', $table_style = '', $table_row_style = '', $grand_total_style = '') {
    $report_data = '';

    $sql = "SELECT * FROM `site_work_orders` swo WHERE swo.`status`='$status'";
    if($from_date != '') {
        $sql .= " AND swo.`work_end_date` >= '$from_date'";
    }
    if($until_date != '') {
        $sql .= " AND swo.`work_start_date` <= '$until_date'";
    }
	$result = mysqli_query($dbc, $sql.' ORDER BY swo.`site_location`, swo.`workorderid` DESC');
	
	if(mysqli_num_rows($result) == 0) {
		return "<h3>No Work Orders Found</h3>";
	}

    $report_data .= '<table border="1px" class="table table-bordered" width="100%" style="'.$table_style.'">';
    $report_data .= '<tr style="'.$table_row_style.'">';
    $report_data .= '<th>Work Order #</th>
			<th>Site</th>
			<th>Staff & Crew</th>
			<th>Services</th>
			<th>Equipment</th>
			<th>Material</th>
			<th>PO</th>';
    $report_data .=  "</tr>";

    while($work_order = mysqli_fetch_array( $result ))
    {
			$crew_list = [ 'Lead: '.get_contact($dbc, $work_order['staff_lead']) ];
			$staff_crew = explode(',',$work_order['staff_crew']);
			$staff_pos = explode(',',$work_order['staff_positions']);
			$staff_est = explode(',',$work_order['staff_estimate']);
			foreach($staff_crew as $i => $id) {
				$crew_list[] = get_contact($dbc, $id).': '.get_positions($dbc, $staff_pos[$i], 'name').' - '.$staff_est[$i];
			}
			$service_list = [];
			$service_cat = explode('#*#',$work_order['service_cat']);
			$service_headings = explode('#*#',$work_order['service_heading']);
			foreach($service_headings as $j => $heading) {
				$service_list[] = $service_cat[$j].': '.$heading;
			}
			$equip_list = [];
			$equipments = explode(',',$work_order['equipment_id']);
			$equip_rates = explode(',',$work_order['equipment_rate']);
			foreach($equipments as $i => $id) {
				$equipment = mysqli_fetch_array(mysqli_query($dbc, "SELECT `category`, `type`, `unit_number`, `make`, `model`, `hourly_rate`, `monthly_rate`, `semi_monthly_rate`, `daily_rate`, `status` FROM `equipment` WHERE `equipmentid`='$id'"));
				$rate = $equip_rates[$i];
				$equip_list[] = $equipment['category'].' '.$equipment['type'].' #'.$equipment['unit_number'].': '.$equipment['make'].' '.$equipment['model'].' ($'.$rate.')';
			}
			$material_list = [];
			$materials = explode(',',$work_order['material_id']);
			$material_qty = explode(',',$work_order['material_qty']);
			foreach($materials as $i => $id) {
				$material = mysqli_fetch_array(mysqli_query($dbc, "SELECT `category`, `name`, `quantity` FROM `material` WHERE `materialid`='$id'"));
				$material_list[] = $material['category'].' '.$material['name'].' Qty: '.$material_qty[$i].(!empty($material['quantity']) ? '('.$material['quantity'].' available)' : '');
			}
			$po_list = [];
			$orders = explode(',',$work_order['po_id']);
			foreach($orders as $id) {
				if($id != '') {
					$po = mysqli_fetch_array(mysqli_query($dbc, "SELECT `poid`, `issue_date` FROM `site_work_po` WHERE `poid`='$id'"));
					$po_list[] = 'PO #'.$po['poid'].': '.$po['issue_date'];
				}
			}
        $report_data .= '<tr nobr="true">
			<td data-title="Work Order #:">'.$work_order['workorderid'].'</td>
			<td data-title="Site:">'.$work_order['site_location'].'</td>
			<td data-title="Staff & Crew:">'.implode("<br />\n", $crew_list).'</td>
			<td data-title="Services:">'.implode("<br />\n", $service_list).'</td>
			<td data-title="Equipment:">'.implode("<br />\n", $equip_list).'</td>
			<td data-title="Materials:">'.implode("<br />\n", $material_list).'</td>
			<td data-title="PO:">'.implode("<br />\n", $po_list).'</td></tr>';
    }

    $report_data .= '</table>';

    return $report_data;
}
?>