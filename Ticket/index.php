<?php // Projects View
include_once('../include.php');
include_once('../Ticket/field_list.php');
$strict_view = strictview_visible_function($dbc, 'ticket');
$ticket_layout = get_config($dbc, 'ticket_layout'); ?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<?php if(!IFRAME_PAGE) { ?>
	<script>
	$(document).ready(function() {
		$(window).resize(function() {
			$('.main-screen').css('padding-bottom',0);
			if($('.main-screen .main-screen').not('.show-on-mob .main-screen').is(':visible')) {
				<?php if(isset($_GET['edit']) && $ticket_layout == 'Accordions') { ?>
					var available_height = window.innerHeight - $('footer:visible').outerHeight() - $('.standard-body').offset().top;
					<?php } else { ?>
						var available_height = window.innerHeight - $('footer:visible').outerHeight() - $('.sidebar:visible').offset().top;
						<?php } ?>
						if(available_height > 200) {
							$('.main-screen .main-screen').outerHeight(available_height).css('overflow-y','auto');
							$('.sidebar').outerHeight(available_height).css('overflow-y','auto');
							$('.search-results').outerHeight(available_height).css('overflow-y','auto');
						}
					}
				}).resize();
			});
		</script>
	<?php } ?>
</head>
<body>
	<?php
	include_once ('../navigation.php');
	$ticket_tabs = [];
	foreach(array_filter(explode(',',get_config($dbc, 'ticket_tabs'))) as $ticket_tab) {
		$ticket_tabs[config_safe_str($ticket_tab)] = $ticket_tab;
	}
	$security = get_security($dbc, ($_GET['tile_name'] == '' ? 'ticket' : 'ticket_type_'.$_GET['tile_name']));
	if($strict_view > 0) {
		$security['edit'] = 0;
		$security['config'] = 0;
	}
	$ticket_type = isset($_GET['type']) ? filter_var($_GET['type'],FILTER_SANITIZE_STRING) : (empty($_GET['tile_name']) ? mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `ticket_type` FROM `tickets` WHERE `ticketid`='".filter_var($_GET['edit'],FILTER_SANITIZE_STRING)."'"))['ticket_type'] : $_GET['tile_name']);
	$db_config = explode(',',get_field_config($dbc, 'tickets_dashboard'));
	$ticketid = $_GET['edit'] > 0 ? $_GET['edit'] : 0;
	if(!empty($_GET['tile_name'])) {
		checkAuthorised(false,false,'ticket_type_'.$_GET['tile_name']);
	} else {
		checkAuthorised('ticket');
	}
	?>
	<div class="container">
		<div class="iframe_overlay" style="display:none;">
			<div class="iframe">
				<div class="iframe_loading">Loading...</div>
				<iframe name="ticket_iframe" src=""></iframe>
			</div>
		</div>
		<div class="row">
			<div class="main-screen">
				<div class="tile-header standard-header" style="<?= IFRAME_PAGE ? 'display:none;' : '' ?>">
					<div class="pull-right settings-block"><?php
					if($security['config'] > 0) {
						echo "<div class='pull-right gap-left'><a href='?settings=fields&tile_name=".$_GET['tile_name']."'><img src='".WEBSITE_URL."/img/icons/settings-4.png' class='settings-classic wiggle-me' width='30' /></a></div>";
					}
					if(in_array('PDF',$db_config)) {
						echo '<a href="../Ticket/ticket_pdf.php?ticketid=&ticket_type='.$ticket_type.'" class="btn brand-btn pull-right hide-titles-mob">Blank '.TICKET_NOUN.' Form <img src="../img/pdf.png" class="inline-img smaller"></a>';
						if($_GET['edit'] > 0) {
							echo '<a href="../Ticket/ticket_pdf.php?ticketid='.$_GET['edit'].'&ticket_type='.$ticket_type.'" class="btn brand-btn pull-right hide-titles-mob">Print Current '.TICKET_NOUN.' <img src="../img/pdf.png" class="inline-img smaller"></a>';
						}
					}
					if(in_array('Export Ticket Log',$db_config)) {
						$ticket_log_template = !empty(get_config($dbc, 'ticket_log_template')) ? get_config($dbc, 'ticket_log_template') : 'template_a';
						echo '<a href="../Ticket/ticket_log_templates/'.$ticket_log_template.'_pdf.php?ticketid=" class="btn brand-btn pull-right hide-titles-mob">Blank '.TICKET_NOUN.' Log <img src="../img/pdf.png" class="inline-img smaller"></a>';
					}
					if($security['edit'] > 0) {
						echo "<div class='pull-right gap-left'><a href='?edit=0&type=".$ticket_type."&tile_name=".$_GET['tile_name']."' class='new-btn'><button class='btn brand-btn hide-titles-mob'>New ".TICKET_NOUN."</button>";
						echo "<img src='".WEBSITE_URL."/img/icons/ROOK-add-icon.png' class='show-on-mob' style='height: 2.5em;'></a></div>";
					} ?>
				</div>
				<div class="scale-to-fill">
					<h1 class="gap-left"><a href="?tile_name=<?= $_GET['tile_name'] ?>"><?= TICKET_TILE.(!empty($_GET['tile_name']) ? ': '.$ticket_tabs[$_GET['tile_name']] : '') ?></a><?= isset($_GET['edit']) ? ($ticketid > 0 && $_GET['new_ticket'] != 'true' ? ': '.get_ticket_label($dbc, mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `tickets` WHERE `ticketid`='$ticketid'"))) : ': <span class="ticketid_span">New '.TICKET_NOUN.'</span>') : '' ?>
						<img class="no-toggle statusIcon pull-right no-margin inline-img small" title="" src="" data-original-title=""></h1>
					</div>
					<div class="clearfix"></div>
				</div><!-- .tile-header -->

				<div class="clearfix"></div>
				<?php if(isset($_GET['edit'])) {
					include('edit_tickets.php');
					if(empty($ticketid) && $calendar_ticket_slider == 'accordion') {
						$include_hidden = 'true'; ?>
						<div style="display:none;"><?php include('edit_tickets.php'); ?></div>
					<?php }
				} else if(!empty($_GET['settings']) && $security['config'] > 0) {
					include('field_config.php');
				} else if(!empty($_GET['custom_form'])) {
					include('ticket_pdf_build.php');
				} else {
					include('ticket_dashboard.php');
				} ?>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<?php include_once('../footer.php'); ?>
