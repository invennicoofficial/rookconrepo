<?php
// Software Settings Configuration

include ('../include.php');
error_reporting(0);
$tab = (isset($_GET['tab']) ? $_GET['tab'] : 'style');
?>
<script>
</script>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('software_config');
?>
<div class="container">
	<div class="row">
        <h1 class="single-pad-bottom"><?php switch($tab) {
			case 'style': echo 'Software Styling Dashboard'; break;
			case 'format': echo 'Software Formatting Dashboard'; break;
			case 'menus': echo 'Menu Formatting Dashboard'; break;
			case 'tile_order': echo 'Tile Sort Order Dashboard'; break;
			case 'my_dashboard': echo 'My Dashboards'; break;
			case 'dashboard': echo 'Create Dashboards'; break;
			case 'identity': echo 'Software Identity Dashboard'; break;
			case 'login': echo 'Software Login Page'; break;
			case 'social': echo 'Social Media Links Dashboard'; break;
			case 'favicon': echo 'Software Favicon Dashboard'; break;
			case 'logo': echo 'Software Logo Dashboard'; break;
			case 'contacts_sort_order': echo 'Display Preferences'; break;
			case 'font_settings': echo 'Font Settings'; break;
			case 'data_use': echo 'Data Usage Reporting'; break;
			case 'notes': echo 'Notes'; break;
			case 'ticket_slider': echo TICKET_NOUN.' Slider View'; break;
		} ?></h1>
		<div class="tab-container mobile-100-container">
			<a href='settings.php?tab=style'><button type='button' class='btn brand-btn mobile-block mobile-100<?php echo ($tab == 'style' ? ' active_tab' : ''); ?>' >Styling</button></a>
			<a href='settings.php?tab=format'><button type='button' class='btn brand-btn mobile-block mobile-100<?php echo ($tab == 'format' ? ' active_tab' : ''); ?>' >Formatting</button></a>
			<a href='settings.php?tab=menus'><button type='button' class='btn brand-btn mobile-block mobile-100<?php echo ($tab == 'menus' ? ' active_tab' : ''); ?>' >Menu Formatting</button></a>
			<a href='settings.php?tab=tile_order'><button type='button' class='btn brand-btn mobile-block mobile-100<?php echo ($tab == 'tile_order' ? ' active_tab' : ''); ?>' >Tile Sort Order</button></a>
			<a href='settings.php?tab=my_dashboard'><button type='button' class='btn brand-btn mobile-block mobile-100<?php echo ($tab == 'my_dashboard' ? ' active_tab' : ''); ?>' >My Dashboards</button></a>
			<?php if(check_subtab_persmission($dbc, 'software_config', ROLE, 'dashboard')) { ?><a href='settings.php?tab=dashboard'><button type='button' class='btn brand-btn mobile-block mobile-100<?php echo ($tab == 'dashboard' ? ' active_tab' : ''); ?>' >Dashboards</button></a><?php } ?>
			<?php if(check_subtab_persmission($dbc, 'software_config', ROLE, 'identity')) { ?><a href='settings.php?tab=identity'><button type='button' class='btn brand-btn mobile-block mobile-100<?php echo ($tab == 'identity' ? ' active_tab' : ''); ?>' >Software Identity</button></a><?php } ?>
			<?php if(check_subtab_persmission($dbc, 'software_config', ROLE, 'login')) { ?><a href='settings.php?tab=login'><button type='button' class='btn brand-btn mobile-block mobile-100<?php echo ($tab == 'login' ? ' active_tab' : ''); ?>' >Software Login Page</button></a><?php } ?>
			<?php if(check_subtab_persmission($dbc, 'software_config', ROLE, 'social')) { ?><a href='settings.php?tab=social'><button type='button' class='btn brand-btn mobile-block mobile-100<?php echo ($tab == 'social' ? ' active_tab' : ''); ?>' >Social Media Links</button></a><?php } ?>
			<?php if(check_subtab_persmission($dbc, 'software_config', ROLE, 'favicon')) { ?><a href='settings.php?tab=favicon'><button type='button' class='btn brand-btn mobile-block mobile-100<?php echo ($tab == 'favicon' ? ' active_tab' : ''); ?>' >URL Favicon</button></a><?php } ?>
			<?php if(check_subtab_persmission($dbc, 'software_config', ROLE, 'logo')) { ?><a href='settings.php?tab=logo'><button type='button' class='btn brand-btn mobile-block mobile-100<?php echo ($tab == 'logo' ? ' active_tab' : ''); ?>' >Logo</button></a><?php } ?>
			<?php if(check_subtab_persmission($dbc, 'software_config', ROLE, 'contact_sort')) { ?><a href='settings.php?tab=contacts_sort_order'><button type='button' class='btn brand-btn mobile-block mobile-100<?php echo ($tab == 'contacts_sort_order' ? ' active_tab' : ''); ?>' >Display Preferences</button></a><?php } ?>
			<?php if(check_subtab_persmission($dbc, 'software_config', ROLE, 'font')) { ?><a href='settings.php?tab=font_settings'><button type='button' class='btn brand-btn mobile-block mobile-100<?php echo ($tab == 'font_settings' ? ' active_tab' : ''); ?>' >Font Settings</button></a><?php } ?>
			<?php if(check_subtab_persmission($dbc, 'software_config', ROLE, 'data_use')) { ?><a href='settings.php?tab=data_use'><button type='button' class='btn brand-btn mobile-block mobile-100<?php echo ($tab == 'data_use' ? ' active_tab' : ''); ?>' >Data Usage</button></a><?php } ?>
			<?php if(check_subtab_persmission($dbc, 'software_config', ROLE, 'notes')) { ?><a href='settings.php?tab=notes'><button type='button' class='btn brand-btn mobile-block mobile-100<?php echo ($tab == 'notes' ? ' active_tab' : ''); ?>' >Notes</button></a><?php } ?>
			<?php if(check_subtab_persmission($dbc, 'software_config', ROLE, 'ticket_slider')) { ?><a href='settings.php?tab=ticket_slider'><button type='button' class='btn brand-btn mobile-block mobile-100<?php echo ($tab == 'ticket_slider' ? ' active_tab' : ''); ?>' ><?= TICKET_NOUN ?> Slider</button></a><?php } ?>
		</div>

		<?php switch($tab) {
			case 'style': include('style_config.php'); break;
			case 'format': include('software_format.php'); break;
			case 'menus': include('menu_settings.php'); break;
			case 'tile_order': include('tile_order.php'); break;
			case 'my_dashboard':
			case 'dashboard': include('dashboards.php'); break;
			case 'identity': include('identity.php'); break;
			case 'login': include('login_style.php'); break;
			case 'social': include('social_media.php'); break;
			case 'favicon': include('../Admin Settings/favicon_settings.php'); break;
			case 'logo': include('logo.php'); break;
			case 'contacts_sort_order': include('contacts_sort_order.php'); break;
			case 'font_settings': include('font_settings.php'); break;
			case 'data_use': include('data_usage.php'); break;
			case 'notes': include('notes.php'); break;
			case 'ticket_slider': include('ticket_slider.php'); break;
		}
		?>
	</div>
</div>

<?php include ('../footer.php'); ?>
