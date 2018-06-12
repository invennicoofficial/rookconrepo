<div class="main-screen standard-body override-main-screen form-horizontal">
	<div class="standard-body-title">
		<h3>Upload <?= TICKET_TILE ?> CSV</h3>
	</div>
	<div class="standard-body-content pad-top">
		<div class="col-sm-12">
			<?php $ticket_tabs = [];
			foreach(array_filter(explode(',',get_config($dbc, 'ticket_tabs'))) as $ticket_tab) {
				$ticket_tabs[config_safe_str($ticket_tab)] = $ticket_tab;
			}
			include('../Ticket/ticket_import.php'); ?>
		</div>
	</div>
</div>