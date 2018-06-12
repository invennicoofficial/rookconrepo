<div class="tab-container mobile-100-container">
	<?php if ( check_subtab_persmission($dbc, 'email_communication', ROLE, 'internal') === TRUE ) { ?>
		<a href="?type=email_comm&projectid=<?php echo $projectid; ?>&category=Internal&from_url=<?php echo urlencode($_GET['from_url']); ?>"><button type="button" class="mobile-100 btn brand-btn mobile-block <?php echo ((empty($_GET['category']) || $_GET['category'] == 'Internal') ? 'active_tab' : ''); ?>">Internal</button></a>&nbsp;&nbsp;
	<?php } ?>
	<?php if ( check_subtab_persmission($dbc, 'email_communication', ROLE, 'external') === TRUE ) { ?>
		<a href="?type=email_comm&projectid=<?php echo $projectid; ?>&category=External&from_url=<?php echo urlencode($_GET['from_url']); ?>"><button type="button" class="mobile-100 btn brand-btn mobile-block <?php echo ((!empty($_GET['category']) && $_GET['category'] == 'External') ? 'active_tab' : ''); ?>">External</button></a>&nbsp;&nbsp;
	<?php } ?>
	<?php if ( check_subtab_persmission($dbc, 'email_communication', ROLE, 'log') === TRUE ) { ?>
		<a href="?type=email_comm&projectid=<?php echo $projectid; ?>&category=Log&from_url=<?php echo urlencode($_GET['from_url']); ?>"><button type="button" class="btn mobile-100 brand-btn mobile-block <?php echo ((!empty($_GET['category']) && $_GET['category'] == 'Log') ? 'active_tab' : ''); ?>">Log</button></a>&nbsp;&nbsp;
	<?php } ?>
</div>

<?php if($_GET['category'] == 'Log') {
	include('../Email Communication/log_display.php');
} else {
	$_GET['type'] = (empty($_GET['category']) ? 'Internal' : $_GET['category']);
	include('../Email Communication/email_list.php');
} ?>