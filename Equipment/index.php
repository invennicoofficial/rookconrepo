<?php include_once('../include.php');
checkAuthorised('equipment');

$security = get_security($dbc, 'equipment');
?>
</head>
<body>
<?php include_once ('../navigation.php'); ?>

<div id="equip_div" class="container">
	<div class="row">
		<div class="main-screen">
			<div class="tile-header standard-header">
				<div class="pull-right settings-block">
					<?php if($security['config'] > 0) {
						echo '<div class="pull-right gap-left"><a href="?settings=tab"><img src="'.WEBSITE_URL.'/img/icons/settings-4.png" class="settings-classic wiggle-me" width="30"></a></div>';
					}
					if($security['edit'] > 0) {
						echo '<div class="pull-right gap-left"><a href="?edit=&category='.($_GET['category'] != 'Top' ? $_GET['category'] : '').' class="new-btn"><button class="btn brand-btn">New Equipment</button</a></div>';
					} ?>
				</div>
				<div class="scale-to-fill">
					<h1 class="gap-left"><a href="?">Equipment</a></h1>
				</div>
				<div class="clearfix"></div>
			</div>

			<div class="clearfix"></div>
			<?php if(isset($_GET['edit'])) {
				include('edit_equipment.php');
			} else if(isset($_GET['settings']) && $config_access > 0) {
				include('field_config.php');
			} else {
				include('equipment_dashboard.php');
			} ?>
		</div>
	</div>
</div>

<?php include_once('../footer.php'); ?>