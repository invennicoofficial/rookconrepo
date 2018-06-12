<?php /*
Inventory Pick List Management
*/
include ('../include.php'); ?>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('inventory'); ?>
<div class="container" id="inventory_div">
	<div class='iframe_holder' style='display:none;'>
		<img src='<?php echo WEBSITE_URL; ?>/img/icons/close.png' class='close_iframe' width="45px" style='position:relative; right: 10px; float:right;top:58px; cursor:pointer;'>
		<span class='iframe_title' style='color:white; font-weight:bold; position: relative;top:58px; left: 20px; font-size: 30px;'></span>
		<iframe id="iframe_instead_of_window" style='width: 100%; overflow: hidden;' height="200px; border:0;" src=""></iframe>
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
						<div class="standard-body-title"><h3>Pick List: <?= $list_name ?></h3></div>
						<div class="standard-body-content pad-left pad-right">
				        	<!-- Notice -->
				            <div class="notice gap-bottom gap-top popover-examples">
				                <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
				                <div class="col-sm-11"><span class="notice-name">NOTE:</span>
					            This tile monitors all physical inventory for resale, displaying the main overview of your inventory. These items are managed by the software with auto Min Bin reminders for when each inventory item reaches a designated level.</div>
				                <div class="clearfix"></div>
				            </div>
				            <?php include('../Inventory/pick_list_fill_inc.php'); ?>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include ('../footer.php'); ?>