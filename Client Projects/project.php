<?php
/*
Dashboard
*/
include ('../include.php');
include_once('../tcpdf/tcpdf.php');
error_reporting(0); 
checkAuthorised('client_projects');

$nav_tabs = array_filter(explode(',',get_config($dbc, 'client_project_tabs')));
$current_tab = (empty($_GET['tab']) ? '' : $_GET['tab']);
foreach($nav_tabs as $key => $tab_name) {
	if(check_subtab_persmission($dbc, 'client_projects', ROLE, $tab_name) !== TRUE) {
		unset($nav_tabs[$key]);
		if($current_tab == $tab_name) {
			$current_tab = '';
		}
	} else if($current_tab == '' || $current_tab == 'pending') {
		$current_tab = $tab_name;
	}
}
?>

</head>
<body>
<?php include ('../navigation.php'); ?>

<div class="container">
	<div class='iframe_holder' style='display:none;'>

		<img src='<?php echo WEBSITE_URL; ?>/img/icons/close.png' class='close_iframer' width="45px" style='position:relative; right: 10px; float:right;top:58px; cursor:pointer;'>
		<span class='iframe_title' style='color:white; font-weight:bold; position: relative; left: 20px; font-size: 30px;'></span>
		<iframe id="iframe_instead_of_window" style='width: 100%;' height="1000px; border:0;" src=""></iframe>
    </div>
	<div class="row hide_on_iframe">
		<h1>Client Projects: <?php switch($current_tab) {
			case 'pending': echo 'Pending Projects Dashboard'; break;
			case 'active': echo 'Active Projects Dashboard'; break;
			case 'archived': echo 'Archived Projects Dashboard'; break;
			case 'tickets': echo 'Tickets Dashboard'; break;
			case 'daysheet': echo 'Daysheet Dashboard'; break;
			default: echo 'Dashboard'; break;
			}
			if(config_visible_function($dbc, 'client_project') == 1) { ?>
				<div class="pull-right">
					<span class="popover-examples list-inline" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					<a href="field_config_project.php?type=Pending" class="mobile-block"><img style="width:50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>
				</div><?php
			} ?></h1>
		<?php if((empty($_GET['nav']) || $_GET['nav'] != 'no_tabs') && count($nav_tabs) > 1): ?>
			<div class='mobile-100-container'>
				<?php if (in_array('pending', $nav_tabs)) { ?>
					<span class="nav-subtab">
						<span class="popover-examples list-inline" style="margin:0 2px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to see the pending Client Projects."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<a href='project.php?tab=pending'><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'pending' ? 'active_tab' : ''); ?>">Pending Projects</button></a>
					</span><?php
				} ?>
				<?php if (in_array('active', $nav_tabs)) { ?>
					<span class="nav-subtab">
						<span class="popover-examples list-inline" style="margin:0 2px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to see the active Client Projects."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<a href='project.php?tab=active'><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'active' ? 'active_tab' : ''); ?>">Active Projects</button></a>
					</span><?php
				} ?>
				<?php if (in_array('archived', $nav_tabs)) { ?>
					<span class="nav-subtab">
						<span class="popover-examples list-inline" style="margin:0 2px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to see the archived Client Projects."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<a href='project.php?tab=archived'><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'archived' ? 'active_tab' : ''); ?>">Archived Projects</button></a>
					</span><?php
				} ?>
				<?php if (in_array('tickets', $nav_tabs)) { ?>
					<span class="nav-subtab">
						<span class="popover-examples list-inline" style="margin:0 2px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to see the Tickets."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<a href='project.php?tab=tickets'><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'tickets' ? 'active_tab' : ''); ?>">Tickets</button></a>
					</span><?php
				} ?>
				<?php if (in_array('daysheet', $nav_tabs)) { ?>
					<span class="nav-subtab">
						<span class="popover-examples list-inline" style="margin:0 2px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to see the Day Sheet."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<a href='project.php?tab=daysheet'><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'daysheet' ? 'active_tab' : ''); ?>">Day Sheet</button></a>
					</span><?php
				} ?>
			</div>
		<?php endif; ?>

		<?php switch($current_tab) {
			case 'tickets' : include('../Ticket/ticket_list.php'); break;
			case 'daysheet' : include('../Daysheet/daysheet_display.php'); break;
			default : include('project_list.php'); break;
		} ?>
	</div>
</div>

<?php include ('../footer.php'); ?>
