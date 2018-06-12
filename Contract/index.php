<?php include_once('../include.php');
checkAuthorised('contracts');

$security = get_security($dbc, 'contracts');

$security = get_security($dbc, 'contracts');
$pin_levels = implode(",%' OR `pinned` LIKE '%,",array_filter(explode(',',ROLE)));
$pincount = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(*) `rows` FROM `contracts` WHERE `deleted`=0 AND (CONCAT(',',`pinned`,',') LIKE '%,ALL,%' OR CONCAT(',',`pinned`,',') LIKE '%,".$pin_levels.",%' OR CONCAT(',',`pinned`,',') LIKE '%,".$_SESSION['contactid'].",%')"))['rows'];
$contract_tabs = explode('#*#',mysqli_fetch_array(mysqli_query($dbc, "SELECT `contract_tabs` FROM `field_config_contracts`"))['contract_tabs']);
foreach($contract_tabs as $contract_i => $contract_tab) {
	if(!check_subtab_persmission($dbc, 'contracts', ROLE, config_safe_str($contract_tab))) {
		unset($contract_tabs[$contract_i]);
	}
}
array_unshift($contract_tabs, 'Favourites');
if($pincount > 0) {
	array_unshift($contract_tabs, 'Pinned');
}
if(empty($_GET['tab'])) {
	$_GET['tab'] = $contract_tabs[0];
}

include_once('../navigation.php');
checkAuthorised();

$contractid = $_GET['edit'];
if($contractid > 0) {
	$user_form_id = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM `contracts` WHERE `contractid`='$contractid'"))['user_form_id'];
	$user_form_layout = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM `user_forms` WHERE `form_id` = '$user_form_id'"))['form_layout'];
	$user_form_layout = !empty($user_form_layout) ? $user_form_layout : 'Accordions';	
}
?>
<script type="text/javascript">
$(document).ready(function() {
	if($(window).width() > 767) {
		resizeScreen();
		$(window).resize(function() {
			resizeScreen();
		});
	}
});
function resizeScreen() {
	var view_height = $(window).height() > 800 ? $(window).height() : 800;
	$('#contract_content .scale-to-fill .main-screen,#contract_content .tile-sidebar').height(view_height - $('#contract_content .scale-to-fill').offset().top - $('#footer').outerHeight());
}
</script>

<div class="container" <?= $user_form_layout == 'Sidebar' ? 'style="padding: 0; margin: 0;"' : '' ?>>
	<div class="row">
		<div <?= $user_form_layout != 'Sidebar' ? 'class="main-screen"' : '' ?>>
			<div class="tile-header standard-header">
                <div class="pull-right settings-block"><?php
                    if($security['config'] > 0) {
                        echo "<div class='pull-right gap-left'><a href='?settings=tabs'><img src='".WEBSITE_URL."/img/icons/settings-4.png' class='settings-classic wiggle-me' width='30' /></a></div>";
                    }
					if(check_subtab_persmission($dbc, 'hr', ROLE, 'reporting')) {
                        echo "<div class='pull-right gap-left'><a href='?reports=view'><button class='btn brand-btn hide-titles-mob ".($_GET['reports'] == 'view' ? "active_tab" : "")." icon-pie-chart'>Reporting</button></a></div>";
                    }
                    if($security['edit'] > 0) {
						echo "<div class='pull-right gap-left'><a href='?add_contract=' class='new-btn'><button class='btn brand-btn hide-titles-mob'>New Contract</button>";
						echo "<img src='".WEBSITE_URL."/img/icons/ROOK-add-icon.png' class='show-on-mob' style='height: 2.5em;'></a></div>";
                    } ?>
                </div>
                <div class="scale-to-fill">
					<h1 class="gap-left"><a href="?tab=<?= $_GET['tab'] ?>">Contracts</a></h1>
				</div>
                <div class="clearfix"></div>
            </div><!-- .tile-header -->

			<div class="clearfix"></div>
			<div id="contract_content">
				<?php if(isset($_GET['edit'])) {
					include('fill_contract.php');
				} else if(isset($_GET['blank_pdf'])) {
					include('user_forms.php');
				} else if(isset($_GET['add_contract'])) {
					include('add_contract.php');
				} else if(!empty($_GET['settings']) && $security['config'] > 0) {
					include('field_config.php');
				} else if(isset($_GET['reports'])) {
					checkAuthorised('contracts', 'reporting');
					$_GET['tab'] = '';
					include('tile_sidebar.php');
					include('reports.php');
				} else {
					include('tile_sidebar.php');
					include('contract_dashboard.php');
				} ?>
			</div>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<?php include('../footer.php'); ?>