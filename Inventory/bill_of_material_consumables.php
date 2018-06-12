<?php
	/*
	 * Bill of Material (Consumables)
	 * Takes cost into account instead of different price types
	 */
	include ('../include.php');
	error_reporting(0);
?>
<script type="text/javascript" src="inventory.js"></script>
</head>

<body>
<?php
	include_once ('../navigation.php');
	checkAuthorised('inventory');
	$inventory_navigation_position = get_config($dbc, 'inventory_navigation_position');
?>
<div class="container" id="inventory_div">
	<div class='iframe_holder' style='display:none;'>

		<img src='<?php echo WEBSITE_URL; ?>/img/icons/close.png' class='close_iframer' width="45px" style='position:relative; right: 10px; float:right;top:58px; cursor:pointer;'>
		<span class='iframe_title' style='color:white; font-weight:bold; position: relative; left: 20px; font-size: 30px;'></span>
		<div id="iframe_instead_of_window" style='width: 100%;' height="1000px; border:0;" src="">
			<!-- Displays history of bill of material: creating, and editing. -->
		</div>
    </div>
	<div class="row hide_on_iframe">
        <div class="main-screen">
            <div class="tile-header standard-header">
				<?php include('../Inventory/tile_header.php'); ?>
            </div>

            <div class="tile-container" style="height: 100%;">
            	<?php include('../Inventory/mobile_view.php'); ?>

				<?php if($inventory_navigation_position == 'top') {
					include('../Inventory/tile_nav_top.php');
				} ?>

	            <?php if($inventory_navigation_position != 'top') { ?>
		            <div class="standard-collapsible tile-sidebar set-section-height hide-titles-mob">
		            	<?php include('../Inventory/tile_sidebar.php'); ?>
		            </div>
	            <?php } ?>

	            <div class="scale-to-fill has-main-screen tile-content hide-titles-mob">
					<div class="main-screen standard-body">
						<div class="standard-body-title"><h3>Bill of Material (Consumables)</h3></div>
						<div class="standard-body-content pad-left pad-right">
							<?php include('../Inventory/bill_of_material_consumables_inc.php'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include ('../footer.php'); ?>