<?php // Tile Configuration History
include ('../include.php');
include ('../tcpdf/tcpdf.php');
ob_clean();
$id = filter_var($_GET['id'],FILTER_SANITIZE_STRING);
$start_date = date('Y-m-d', strtotime($_POST['start'] ?: '-7days'));
$end_date = date('Y-m-d', strtotime($_POST['end'] ?: 'today'));
DEFINE('START_DATE',$start_date);
DEFINE('END_DATE',$end_date);
$title = $id > 0 ? get_contact($dbc, $id) : 'All Users';
$html = '';
$html .= "<h1>Login History - $title</h1>";
$sql = "SELECT `contactid`, `user_name`, `login_at`, `login_ip`, `success` FROM `login_history` WHERE '$id' IN (`contactid`,'ALL') AND `login_at` BETWEEN '$start_date' AND '$end_date 23:59:59' ORDER BY `login_at` DESC";
$history = mysqli_query($dbc, $sql);
$html .= "<table>
	<tr>
		<th>Name</th>
		<th>User</th>
		<th>Status</th>
		<th>Time</th>
		<th>IP Address</th>
	</tr>";
	while($row = mysqli_fetch_array($history)) {
		$html .= "<tr>
			<td>".get_contact($dbc,$row['contactid'])."</td>
			<td>".$row['user_name']."</td>
			<td>".($row['success'] ? 'Successful' : 'Failed Attempt')."</td>
			<td>".$row['login_at']."</td>
			<td>".$row['login_ip']."</td>
		</tr>";
	}
$html .= "</table>";

class MYPDF extends TCPDF {
	public function Header() {
		$this->SetFont('helvetica', '', 8);
		$this->writeHTMLCell(0, 0, '', '', 'Login History Report for '.START_DATE.' to '.END_DATE.' produced on '.date('Y-m-d'), 0, 0, false, "L", "R",true);
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		$this->SetFont('helvetica', 'I', 8);
		$footer_text = '<br />Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages();
		$this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "L", true);
	}
}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf_path = 'download/login_history'.($id > 0 ? '_'.$id : '').'_'.date('Y_m_d_h_i').'.pdf';
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);
$pdf->writeHTML($html, true, false, true, false, '');
if (!file_exists('download')) {
	mkdir('download', 0777, true);
}
$pdf->Output($pdf_path, 'F');
header('Location: '.$pdf_path);