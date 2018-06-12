<?php include('../include.php');
include('../tcpdf/tcpdf.php');
error_reporting(0);
ob_clean();

if(!empty($_GET['workorderid'])) {
	$workorderid = $_GET['workorderid'];
	$work_order = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `site_work_orders` WHERE `workorderid`='$workorderid'"));
	$businessid = $work_order['businessid'];
	$siteid = $work_order['siteid'];
	$contactid = $work_order['contactid'];
	$staff_lead = $work_order['staff_lead'];
	$staff_crew = $work_order['staff_crew'];
	$staff_positions = $work_order['staff_positions'];
	$staff_estimate_hours = $work_order['staff_estimate_hours'];
	$staff_estimate_days = $work_order['staff_estimate_days'];
	$service_cat = $work_order['service_cat'];
	$service_head = $work_order['service_heading'];
	$service_rates = $work_order['service_rates'];
	$equipment_id = $work_order['equipment_id'];
	$equipment_rate = $work_order['equipment_rate'];
	$equipment_status = $work_order['equipment_status'];
	$material_id = $work_order['material_id'];
	$material_qty = $work_order['material_qty'];
	$site_location = $work_order['site_location'];
	$site_description = $work_order['site_description'];
	$google_map_link = $work_order['google_map_link'];
	$work_start_date = $work_order['work_start_date'];
	$work_end_date = $work_order['work_end_date'];
	$work_start_time = $work_order['work_start_time'];
	$po_id = $work_order['po_id'];
	$comments = $work_order['comments'];
	$staff_crew = explode(',',$staff_crew);
	$staff_positions = explode(',',$staff_positions);
	$staff_estimate_hours = explode(',',$staff_estimate_hours);
	$staff_estimate_days = explode(',',$staff_estimate_days);
	$staff_summary = explode('#*#',$work_order['summary']);
	$staff_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT `contactid`, `last_name`, `first_name` FROM `contacts` WHERE `category`='Staff' AND `deleted`=0 AND `status`=1 AND `show_hide_user`=1"),MYSQLI_ASSOC));
	$task_list = explode('#*#', get_config($dbc, 'site_work_order_tasks'));
	
	$html = '';
	$html .= "<h1>Site Work Order #".$workorderid."</h1>";
	$html .= "<h2>Who</h2>";
	$html .= "Business: ".get_client($dbc, $businessid)."<br />";
	$html .= "Site: ".get_contact($dbc, $siteid, 'site_name')."<br />";
	$html .= "Contact: ".get_contact($dbc, $contactid)."<br />";
	$html .= "<h2>Staff & Crew</h2>";
	$html .= "<b>Company Team Lead:</b> ".get_contact($dbc, $staff_lead)."<br />";
	$html .= '<table width="100%"><tr><td>Staff</td><td>Position</td><td>Estimated Hours</td><td>Estimated Days</td></tr>';
	foreach($staff_crew as $j => $crew) {
		$html .= '<tr><td>'.get_contact($dbc, $crew).'</td><td>'.mysqli_fetch_array(mysqli_query($dbc, "SELECT `position_id`, `name` FROM `positions` WHERE `position_id`='".$staff_positions[$j]."'"))['name'];
		$html .= '</td><td>'.$staff_estimate_hours[$j].'</td><td>'.$staff_estimate_days[$j].'</td></tr>';
	}
	$html .= "</table>";
	$html .= "<h2>Services</h2>";
	$html .= '<table width="100%"><tr><td>Category</td><td>Heading</td><td>Rate</td></tr>';
	$service_cat = explode('#*#',$service_cat);
	$service_head = explode('#*#',$service_head);
	$service_rates = explode('#*#',$service_rates);
	foreach($service_cat as $j => $cat) {
		$html .= "<tr><td>$cat</td><td>".$service_head[$j]."</td><td>".($service_rates[$j] > 0 ? '$'.number_format($service_rates[$j],2) : '-')."</td></tr>";
	}
	$html .= "</table>";
	$html .= "<h2>Equipment</h2>";
	$html .= '<table width="100%"><tr><td>Equipment</td><td>Rate</td><td>Status</td></tr>';
	$equipment_id = explode(',',$equipment_id);
	$equipment_rate = explode(',',$equipment_rate);
	$equipment_status = explode(',',$equipment_status);
	foreach($equipment_id as $j => $id) {
		$equipment = mysqli_fetch_array(mysqli_query($dbc, "SELECT `category`, `type`, `unit_number`, `make`, `model`, `label`, `equipmentid` FROM `equipment` WHERE `equipmentid`='$id'"));
		$html .= '<tr><td>'.$equipment['category'].": ".$equipment['type'].": ".$equipment['unit_number'].": ".$equipment['label'].'</td><td>'.$equipment_rate[$j].'</td><td>'.$equipment_status[$j].'</td></tr>';
	}
	$html .= "</table>";
	$html .= "<h2>Materials</h2>";
	$html .= '<table width="100%"><tr><td>Category</td><td>Type</td><td>Quantity</td></tr>';
	$material_id = explode('#*#',$material_id);
	$material_qty = explode(',',$material_qty);
	foreach($material_id as $j => $material) {
		if($material == '' || is_numeric($material)) {
			$material = mysqli_fetch_array(mysqli_query($dbc, "SELECT `materialid`, `category`, `name` FROM `material` WHERE `materialid`='$material'"));
			$html .= '<tr><td>'.$material['category'].'</td><td>'.$material['name'].'</td><td>'.$material_qty[$j].'</td></tr>';
		} else {
			$html .= '<tr><td colspan="2">'.$material.'</td><td>'.$material_qty[$j].'</td></tr>';
		}
	}
	$html .= "</table>";
	$html .= "<h2>Where</h2>";
	$html .= "SL: ".$site_location."<br />";
	$html .= "LSD: ".$site_description."<br />";
	$html .= "Google Maps: <a href='".$google_map_link."'>Map Link</a><br />";
	$html .= "Start Date: ".$work_start_date."<br />";
	$html .= "End Date: ".$work_end_date."<br />";
	$html .= "Start Time: ".$work_start_time."<br />";
	$html .= "<h2>Support Documents</h2>";
	if(!empty($_GET['workorderid'])) {
		$query_check_credentials = "SELECT * FROM site_work_document WHERE workorderid='$workorderid' ORDER BY documentid DESC";
		$result = mysqli_query($dbc, $query_check_credentials);
		$num_rows = mysqli_num_rows($result);
		if($num_rows > 0) {
			$html .= '<table width="100%">
			<tr>
			<th>Type</th>
			<th>Document/Link</th>
			<th>Date</th>
			<th>Uploaded By</th>
			<th>Delete</th>
			</tr>';
			while($row = mysqli_fetch_array($result)) {
				$html .= '<tr>';
				$by = $row['created_by'];
				$html .= '<td data-title="Schedule">'.$row['type'].'</td>';
				if($row['document'] != '') {
					$html .= '<td data-title="Schedule"><a href="download/'.$row['document'].'" target="_blank">'.$row['document'].'</a></td>';
				} else {
					$html .= '<td data-title="Schedule"><a target="_blank" href=\''.$row['link'].'\'">Link</a></td>';
				}
				$html .= '<td data-title="Schedule">'.$row['created_date'].'</td>';
				$html .= '<td data-title="Schedule">'.get_staff($dbc, $by).'</td>';
				$html .= '<td data-title="Schedule"><a href=\'../delete_restore.php?action=delete&ticketdocid='.$row['ticketdocid'].'&ticketid='.$row['ticketid'].'\' onclick="return confirm(\'Are you sure?\')">Delete</a></td>';
				$html .= '</tr>';
			}
			$html .= '</table>';
		} else {
			$html .= "<h3>No Support Documents listed</h3>";
		}
	}
	$html .= "<h2>Checklist</h2>";
	$result = mysqli_query($dbc, "SELECT * FROM `site_work_checklist` WHERE workorderid='$workorderid' AND deleted = 0 ORDER BY sort");
	while($row = mysqli_fetch_array( $result )) {
		$html .= html_entity_decode($row['checklist']).'<br />';
		$documents = mysqli_query($dbc, "SELECT * FROM `site_work_checklist_uploads` WHERE `checklistid`='".$row['checklistid']."'");
		while($doc = mysqli_fetch_array($documents)) {
			if($doc['type'] == 'Link') {
				$html .= '<a href="'.$doc['link'].'">'.$doc['link'].' (Link added by '.get_staff($dbc, $doc['created_by']).' on '.$doc['created_date'].')</a><br />';
			} else {
				$html .= '<a href="download/'.$doc['link'].'">'.$doc['link'].' (Uploaded by '.get_staff($dbc, $doc['created_by']).' on '.$doc['created_date'].')</a><br />';
			}
		}
	}
	$html .= "<h2>Purchase Orders</h2>";
	$html .= '<table width="100%"><tr><th>PO</th><th>3rd Party Invoice #</th><th>Invoice</th><th>Total Price</th><th>Mark Up</th><th>Total</th></tr>';
	$pos = mysqli_query($dbc, "SELECT `site_work_po`.*, `site_work_orders`.`workorderid` FROM `site_work_po` LEFT JOIN `site_work_orders` ON CONCAT(',',`site_work_orders`.`po_id`,',') LIKE CONCAT('%,',`site_work_po`.`poid`,',%') WHERE `site_work_orders`.`workorderid`='$workorderid'");
	if(mysqli_num_rows($pos) > 0) {
		while($po = mysqli_fetch_array($pos)) {
			$html .= '<tr>
				<td data-title="PO">#'.$po['poid'].'</td>
				<td data-title="3rd Party Invoice #">'.$po['invoice_number'].'</td>
				<td data-title="Invoice"></td>
				<td data-title="Total Price">'.$po['final_total'].'</td>
				<td data-title="Mark Up">'.$po['final_total'].'</td>
				<td data-title="Total"><input type="text" name="marked_up_total" value="'.$po['final_total'].'" class="form-control"></td>
			</tr>';
		}
	} else {
		$html .= "<tr class='no_order_msg'><td colspan='7'>No Purchase Orders to Attach</td></tr>";
	}
	$html .= "</table>";
	$html .= "<h2>Work Order Comments</h2>";
	$query_check_credentials = "SELECT * FROM site_work_comment WHERE workorderid='$workorderid' AND type='note' ORDER BY commentid DESC";
	$result = mysqli_query($dbc, $query_check_credentials);
	$num_rows = mysqli_num_rows($result);
	if($num_rows > 0) {
		$html .= "<table class='table table-bordered'>
		<tr class='hidden-xs hidden-sm'>
		<th>Note</th>
		<th>Assigned To</th>
		<th>Date</th>
		<th>Added By</th>
		</tr>";
		while($row = mysqli_fetch_array($result)) {
			$html .= '<tr>';
			$by = $row['created_by'];
			$to = $row['email_comment'];
			$html .= '<td data-title="Note">'.html_entity_decode($row['comment']).'</td>';
			$html .= '<td data-title="Assigned To">'.get_staff($dbc, $to).'</td>';
			$html .= '<td data-title="Date">'.$row['created_date'].'</td>';
			$html .= '<td data-title="Added By">'.get_staff($dbc, $by).'</td>';
			$html .= '</tr>';
		}
		$html .= '</table>';
	} else {
		$html .= "There are no comments on this Work Order.";
	}
	$html .= "<h2>Site Summary</h2>";
	$html .= '<table width="100%"><tr><td>Staff</td><td>Task</td><td>Hours</td></tr>';
	foreach($staff_summary as $j => $summary) {
		$summary = explode('**#**', $summary);
		$html .= '<tr><td>'.get_contact($dbc, $summary[0]).'</td><td>'.$summary[1].'</td><td>'.$summary[2].'</td></tr>';
	}
	$html .= "</table>";
	
	$html .= "<h2>Driving Logs</h2>";
	$driving_logs = mysqli_query($dbc, "SELECT `log_id`, `drive_date`, `staff` FROM `site_work_driving_log` WHERE `workorderid`='$workorderid'");
	if(mysqli_num_rows($driving_logs) > 0) {
		while($log = mysqli_fetch_array($driving_logs)) {
			$html .= 'There is a Driving Log for '.get_contact($dbc, $log['staff']).' from '.$log['drive_date'].'<br />';
		}
	} else {
		$html .= "No Driving Logs Found.<br />";
	}
	
	class MYPDF extends TCPDF {

		//Page header
		public function Header() {
			$this->SetFont('helvetica', '', 8);
			$header_text = '';
			$this->writeHTMLCell(0, 0, 5, 5, "Site Work Order Information", 0, 0, false, "L", "C",true);
		}

		// Page footer
		public function Footer() {
			// Position at 15 mm from bottom
			$this->SetY(-15);
			$this->SetFont('helvetica', 'I', 8);
			$footer_text = 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages();
			$this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "L", true);
		}
	}

	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
	$pdf->AddPage();
	$pdf->SetFont('helvetica', '', 9);

	$pdf->writeHTML($html, true, false, true, false, '');
    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }
	$pdf->Output('download/siteworkorder_'.$workorderid.'.pdf', 'F');
	
	echo '<script type="text/javascript"> window.location.replace("download/siteworkorder_'.$workorderid.'.pdf"); </script>';
}