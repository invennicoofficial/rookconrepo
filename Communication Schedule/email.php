<div class="tab-container mobile-100-container">
	<?php if ( check_subtab_persmission($dbc, 'email_communication', ROLE, 'internal') === TRUE ) { ?>
		<a href="?tab=email&type=Internal"><button type="button" class="mobile-100 btn brand-btn mobile-block <?php echo ((empty($_GET['type']) || $_GET['type'] == 'Internal') ? 'active_tab' : ''); ?>">Internal</button></a>&nbsp;&nbsp;
	<?php } ?>
	<?php if ( check_subtab_persmission($dbc, 'email_communication', ROLE, 'external') === TRUE ) { ?>
		<a href="?tab=email&type=External"><button type="button" class="mobile-100 btn brand-btn mobile-block <?php echo ((!empty($_GET['type']) && $_GET['type'] == 'External') ? 'active_tab' : ''); ?>">External</button></a>&nbsp;&nbsp;
	<?php } ?>
	<?php if ( check_subtab_persmission($dbc, 'email_communication', ROLE, 'log') === TRUE ) { ?>
		<a href="?tab=email&type=Log"><button type="button" class="btn mobile-100 brand-btn mobile-block <?php echo ((!empty($_GET['type']) && $_GET['type'] == 'Log') ? 'active_tab' : ''); ?>">Log</button></a>&nbsp;&nbsp;
	<?php } ?>
</div>

<?php if($_GET['type'] == 'Log') {
	include('../Email Communication/log_display.php');
} else {
	include('../Email Communication/email_list.php');
} ?>