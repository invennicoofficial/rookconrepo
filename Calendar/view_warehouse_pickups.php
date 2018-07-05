<?php include_once('../include.php');

$ticketids = $_GET['ticketids'];
$warehouse = urldecode($_GET['warehouse']);

if(!empty($_GET['export_pdf'])) {
    include('../tcpdf/tcpdf.php');
    class MYPDF extends TCPDF {

        //Page header
        public function Header() {
        }

        //Page footer
        public function Footer() {
            $this->SetY(-10);
            $this->SetFont('helvetica', '', 6);
            $this->writeHTMLCell(0, 0, '', '', 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, 0, false, true, 'R', true);
        }
    }

    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LETTER', true, 'UTF-8', false);
    $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
    $pdf->SetAutoPageBreak(TRUE, 25);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 9);
    $pdf->setCellHeightRatio(1);

    $html = '<p style="text-align: center"><h3>'.$warehouse.'</h3></p>';

    foreach(explode(',', $ticketids) as $ticketid) {
		$ticket_table = explode('-', $ticketid)[0];
		$ticketid = explode('-', $ticketid)[1];

		if($ticket_table == 'ticket_schedule') {
			$ticket_schedule = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `ticket_schedule` WHERE `id` = '$ticketid'"));
			$ticket = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `tickets` WHERE `ticketid` = '".$ticket_schedule['ticketid']."'"));
			$html .= '<table cellpadding="2" style="border: 1px solid black;">';
			$html .= '<tr><td width="30%"><b>'.TICKET_NOUN.':</b></td><td width="70%">'.get_ticket_label($dbc, $ticket).'</td></tr>';
			$html .= '<tr><td width="30%"><b>Name:</b></td><td width="70%">'.$ticket_schedule['client_name'].'</td></tr>';
			$html .= '<tr><td width="30%"><b>Order #:</b></td><td width="70%">'.$ticket_schedule['order_number'].'</td></tr>';
			$html .= '<tr><td width="30%"><b>Notes:</b></td><td width="70%">'.html_entity_decode($ticket_schedule['notes']).'</td></tr>';
			$html .= '</table>';
			$html .= '<p style="font-size: 1px;"></p>';
		}
	}

    $pdf->writeHTML(utf8_encode($html), true, false, true, false, '');

    if(!file_exists('download')) {
        mkdir('download', 0777, true);
    }
    $today_date = date('Y-m-d_H-i-a', time());
    $file_name = 'warehouse_pickups_'.$today_date.'.pdf';
    $pdf->Output('download/'.$file_name, 'F');

    echo '<script type="text/javascript">
            window.location.replace("download/'.$file_name.'");
        </script>';
}
?>

<h3 class="gap-left"><?= $warehouse ?><a href="?warehouse=<?= $_GET['warehouse'] ?>&ticketids=<?= $_GET['ticketids'] ?>&export_pdf=1" class="btn brand-btn pull-right gap-right" target="_blank">Export PDF</a></h3>
<div class="clearfix"></div>

<?php foreach(explode(',', $ticketids) as $ticketid) {
	$ticket_table = explode('-', $ticketid)[0];
	$ticketid = explode('-', $ticketid)[1];

	if($ticket_table == 'ticket_schedule') {
		$ticket_schedule = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `ticket_schedule` WHERE `id` = '$ticketid'"));
		$ticket = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `tickets` WHERE `ticketid` = '".$ticket_schedule['ticketid']."'")); ?>
		<div class="block-group">
			<div class="form-group">
				<label class="col-sm-3 control-label"><?= TICKET_NOUN ?>:</label>
				<div class="col-sm-9">
					<a href="" onclick="overlayIFrameSlider('<?= WEBSITE_URL ?>/Ticket/index.php?calendar_view=true&edit=<?= $ticket['ticketid'] ?>&stop=<?= $ticket_schedule['id'] ?>', 'auto', true, true); return false"><?= get_ticket_label($dbc, $ticket); ?></a>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Name:</label>
				<div class="col-sm-9">
					<?= $ticket_schedule['client_name'] ?>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Order #:</label>
				<div class="col-sm-9">
					<?= $ticket_schedule['order_number'] ?>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Notes:</label>
				<div class="col-sm-9">
					<?= html_entity_decode($ticket_schedule['notes']) ?>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		</div>
	<?php }
} ?>