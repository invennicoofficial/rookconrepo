<?php include_once('../include.php');
checkAuthorised('equipment');

$security = get_security($dbc, 'equipment');
?>
<script type="text/javascript">
$(document).ready(function() {
	$(window).resize(function() {
		$('.main-screen').css('padding-bottom',0);
		if($('.main-screen .main-screen').not('.show-on-mob .main-screen').is(':visible')) {
			var available_height = window.innerHeight;
			if($('#footer').is(':visible')) {
				available_height = available_height - $('#footer').outerHeight();
			}
			if($('.sidebar').is(':visible')) {
				available_height = available_height - $('.sidebar:visible').offset().top;
			}
			if(available_height > 200 && $(window).width() >= 768) {
				$('.main-screen .main-screen').outerHeight(available_height).css('overflow-y','auto');
				$('.sidebar').outerHeight(available_height).css('overflow-y','auto');
				$('.search-results').outerHeight(available_height).css('overflow-y','auto');
			} else {
				$('.main-screen .main-screen').height('auto');
			}
		}
	}).resize();
});
</script>
</head>
<body>
<?php include_once ('../navigation.php'); ?>

<div id="equip_div" class="container">
	<div class="iframe_overlay" style="display:none; margin-top: -20px;margin-left:-15px;">
		<div class="iframe">
			<div class="iframe_loading">Loading...</div>
			<iframe name="equipment_iframe" src=""></iframe>
		</div>
	</div>
	<div class='iframe_holder' style='display:none;'>
		<img src='<?php echo WEBSITE_URL; ?>/img/icons/close.png' class='close_iframer' width="45px" style='position:relative; right: 10px; float:right;top:58px; cursor:pointer;'>
		<span class='iframe_title' style='color:white; font-weight:bold; position: relative;top:58px; left: 20px; font-size: 30px;'></span>
		<iframe id="iframe_instead_of_window" style='width: 100%; overflow: hidden;' height="200px; border:0;" src=""></iframe>
    </div>
	<div class="row hide_on_iframe">
		<div class="main-screen">
			<div class="tile-header standard-header">
				<div class="pull-right settings-block">
					<?php if($security['config'] > 0) {
						echo '<div class="pull-right gap-left"><a href="?settings=tab"><img src="'.WEBSITE_URL.'/img/icons/settings-4.png" class="settings-classic wiggle-me" width="30"></a></div>';
					}
					if($security['edit'] > 0) {
						echo '<div class="pull-right gap-left"><a href="?edit=&category='.($_GET['category'] != 'Top' ? $_GET['category'] : '').'" class="new-btn"><button class="btn brand-btn">New Equipment</button</a></div>';
					} ?>
				</div>
				<div class="scale-to-fill">
					<?php if($_GET['edit'] > 0) {
						$equipmentid = $_GET['edit'];
						$get_equipment = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `equipment` WHERE `equipmentid`='".$equipmentid."'"));

						$unit_number = $get_equipment['unit_number'];
					} ?>
					<h1 class="gap-left"><a href="?">Equipment</a><?= $equipmentid > 0 ? ': Unit #'.$unit_number : '' ?></h1>
				</div>
				<div class="clearfix"></div>
			</div>

			<div class="clearfix"></div>
			<?php if(isset($_GET['edit_inspection'])) {
				include('edit_inspection.php');
			} else if(isset($_GET['edit_assigned_equipment'])) {
				include('edit_assigned_equipment.php');
			} else if(isset($_GET['edit_work_order'])) {
				include('edit_work_order.php');
			} else if(isset($_GET['edit_service_request'])) {
				include('edit_service_request.php');
			} else if(isset($_GET['edit_service_record'])) {
				include('edit_service_record.php');
			} else if(isset($_GET['edit_checklist'])) {
				include('edit_checklist.php');
			} else if(isset($_GET['edit'])) {
				include('edit_equipment_header.php');
				if($_GET['subtab'] == 'inspections') {
					include('edit_equipment_inspections.php');
				} else if($_GET['subtab'] == 'work_orders') {
					include('edit_equipment_work_order.php');
				} else if($_GET['subtab'] == 'service') {
					include('edit_equipment_service.php');
				} else if($_GET['subtab'] == 'expenses') {
					include('edit_equipment_expenses.php');
				} else if($_GET['subtab'] == 'balance') {
					include('edit_equipment_balance.php');
				} else if($_GET['subtab'] == 'equip_assign') {
					include('edit_equipment_assignment.php');
				} else {
					include('edit_equipment.php');
				}
			} else if(isset($_GET['settings']) && $security['config'] > 0) {
				include('field_config.php');
			} else {
				include('equipment_dashboard.php');
			} ?>
		</div>
	</div>
</div>

<?php include_once('../footer.php'); ?>