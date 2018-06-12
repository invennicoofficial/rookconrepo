<?php
	$active_funnel		= '';
	$active_definitions	= '';
	$active_infographic	= '';

	if ( empty ( $_GET['status'] ) ) {
		$active_funnel = ' active_tab';
		$_GET['status'] = 'Funnel';
	}
	if ( $_GET['status'] == 'Funnel' ) {
		$active_funnel = ' active_tab';
	}
	if ( $_GET['status'] == 'Definitions' ) {
		$active_definitions = ' active_tab';
	}
	if ( $_GET['status'] == 'Infographic' ) {
		$active_infographic = ' active_tab';
	}

	// echo '<a href="call_log.php?maintype=howto&status=Funnel"><button type="button" class="btn brand-btn mobile-block mobile-100 ' . $active_funnel . '">Cold Call Funnel</button></a>&nbsp;&nbsp';
	// echo '<a href="call_log.php?maintype=howto&status=Definitions"><button type="button" class="btn brand-btn mobile-block mobile-100 ' . $active_definitions . '">Cold Call Definitions</button></a>&nbsp;&nbsp';
	// echo '<a href="call_log.php?maintype=howto&status=Infographic"><button type="button" class="btn brand-btn mobile-block mobile-100 ' . $active_infographic . '">Cold Call Infographic</button></a>&nbsp;&nbsp'; ?>
<?php

if ( $_GET['status'] == 'Funnel' ) {
	echo '<img src="download/ROOK-CallLog-Funnel.png" alt="Cold Call Funnel" />';
}
if ( $_GET['status'] == 'Definitions' ) {
	echo '<img src="download/ROOK-CallLog-Definitions.png" alt="Cold Call Definitions" />';
}
if ( $_GET['status'] == 'Infographic' ) {
	echo '<img src="download/ROOK-CallLog-Infographic.png" alt="Cold Call Infographic" />';
}