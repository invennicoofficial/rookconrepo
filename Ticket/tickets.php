<?php // Dashboard
include_once('../include.php');
include_once('../tcpdf/tcpdf.php');
error_reporting(0);

if(!empty($_GET['quoteid'])) {
    $quoteid = $_GET['quoteid'];
    $status = $_GET['status'];
    $query_update_report = "UPDATE `quote` SET `status` = '$status' WHERE `quoteid` = '$quoteid'";
    $result_update_report = mysqli_query($dbc, $query_update_report);
    echo '<script type="text/javascript"> window.location.replace("quotes.php"); </script>';
} ?>

</head>
<body>

<?php include_once('../navigation.php');
$ticket_tabs = [];
foreach(array_filter(explode(',',get_config($dbc, 'ticket_tabs'))) as $ticket_tab) {
	$ticket_tabs[config_safe_str($ticket_tab)] = $ticket_tab;
}
$_GET['tile_name'] = @filter_var($_GET['tile_name'],FILTER_SANITIZE_STRING);
$tile_security = get_security($dbc, ($_GET['tile_name'] == '' ? 'ticket' : 'ticket_type_'.$_GET['tile_name']));
$ticket_type = isset($_GET['type']) ? filter_var($_GET['type'],FILTER_SANITIZE_STRING) : $_GET['tile_name'];
$db_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `tickets_dashboard` FROM `field_config`"))['tickets_dashboard'];
if($db_config == '') {
	$db_config = 'Business,Contact,Heading,Services,Status,Deliverable Date';
}
$db_config = explode(',',$db_config); ?>

<div class="container">
	<div class="row">
		<h1><?= TICKET_TILE.(!empty($_GET['tile_name']) ? ': '.$ticket_tabs[$_GET['tile_name']] : '') ?> <?php $contactid = $_SESSION['contactid'];
			if($tile_security['config'] == 1) { ?>
				<div class="pull-right">
					<span class="popover-examples list-inline" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					<a href="../Ticket/index.php?settings=fields&tile_name=<?= $_GET['tile_name'] ?>&from_url=<?php echo WEBSITE_URL.$_SERVER['REQUEST_URI']; ?>" class="mobile-block"><img style="width:50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>
				</div><?php
			} ?></h1>
		<?php if($_GET['tile_name'] == '' && count($ticket_tabs) > 0) { ?>
			<div class='tab-container1 mobile-100-container'>
				<div class="pull-left tab nav-subtab">
					<span class="popover-examples list-inline" style="margin:0 2px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to see all <?= TICKET_TILE ?>."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					<a href='tickets.php'><button type="button" class="btn brand-btn mobile-block mobile-100 <?= $ticket_type == '' && $_GET['tab'] != 'export' ? 'active_tab' : '' ?>"><?= TICKET_TILE ?></button></a>
				</div>
				<?php foreach($ticket_tabs as $type => $type_name) { ?>
					<div class="pull-left tab nav-subtab">
						<?php if ( check_subtab_persmission($dbc, 'ticket', ROLE, 'ticket_type_'.$type) === TRUE ) { ?>
							<span class="popover-examples list-inline" style="margin:0 2px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to see <?= $type_name ?>."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
							<a href='tickets.php?type=<?= $type ?>'><button type="button" class="btn brand-btn mobile-block mobile-100 <?= $ticket_type == $type ? 'active_tab' : '' ?>"><?= $type_name ?></button></a>
						<?php } ?>
					</div>
				<?php }
			echo "</div>";
		} ?>
		<?php if(in_array('Export',$db_config) && check_subtab_persmission($dbc, 'ticket', ROLE, 'export') === TRUE) { ?>
			<a href="?tab=export&tile_name=<?= $_GET['tile_name'] ?>" class="pull-right btn brand-btn <?= $_GET['tab'] == 'export' ? 'active_tab' : '' ?>">Import / Export</a>
		<?php } ?>
		<div class="clearfix"></div>
		<?php if($_GET['tab'] == 'export') {
			include('ticket_import.php');
		} else {
			include('ticket_list.php');
		} ?>
	</div>
</div>

<?php include_once('../footer.php'); ?>
