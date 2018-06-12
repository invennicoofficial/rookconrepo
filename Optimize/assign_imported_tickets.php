<?php include('../include.php');
echo '<h3>'.TICKET_TILE.'</h3>';
foreach(explode(',',$_GET['ids']) as $ticket) {
	if($ticket > 0) {
		$ticket = $dbc->query("SELECT * FROM `tickets` WHERE `ticketid`='$ticket'")->fetch_assoc();
		$unbooked_html = '<span class="block-item ticket" style="position: relative; background-color: '.$ticket_colour.'; border: 1px solid rgba(0,0,0,0.5); color: #000; margin: 0.25em 0 0; display:block; float: left; width: 30em;" data-ticketid="'.$ticket['ticketid'].'" title="View '.TICKET_NOUN.'">
			<div class="drag-handle full-height" title="Drag Me!">
				<img class="drag-handle black-color inline-img pull-right" src="'.WEBSITE_URL.'/img/icons/drag_handle.png" />
			</div>
			<a href="'.WEBSITE_URL.'/Ticket/index.php?edit='.$ticket['ticketid'].'" data-ticketid="'.$ticket['ticketid'].'" onclick=\'
			overlayIFrameSlider(this.href+"&calendar_view=true","auto",true,true); return false;\' style="text-decoration: none; display: block;">
			'.get_ticket_label($dbc, $ticket).($ticket['sub_label'] != '' ? '-'.$ticket['sub_label'] : '').($ticket['scheduled_lock'] > 0 ? '<img class="inline-img" title="Time has been Locked" src="../img/icons/lock.png">' : '').'<br />
			'.(in_array('project',$calendar_ticket_card_fields) ? PROJECT_NOUN.' #'.$ticket['projectid'].' '.$ticket['project_name'].'<br />' : '').'
			'.(in_array('customer',$calendar_ticket_card_fields) ? 'Customer: '.$customer.'<br />' : '').'
			'.(in_array('assigned',$calendar_ticket_card_fields) ? 'Assigned Staff: '.$assigned_staff.'<br />' : '').'
			'.(in_array('start_date',$calendar_ticket_card_fields) && !empty($ticket['to_do_date']) ? 'Date: '.$ticket['to_do_date'] : '');
		$unbooked_html .= '<h4>Addresses</h4>';
		$deliveries = $dbc->query("SELECT `address`, `city` FROM `ticket_schedule` WHERE `ticketid`='".$ticket['ticketid']."' ORDER BY `id`");
		while($delivery = $deliveries->fetch_assoc()) {
			$unbooked_html .= $delivery['address'].', '.$delivery['city'].'<br />';
		}
		$unbooked_html .= '</a></span>';
		echo $unbooked_html;
	}
}
if(empty($_GET['ids'])) {
	echo '<h3>No '.TICKET_TILE.'</h3>';
}