<?php
/*
Inventory Listing
*/
include ('../include.php');

?>
<script type="text/javascript" src="inventory.js"></script>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('inventory');
$inventory_navigation_position = get_config($dbc, 'inventory_navigation_position');
?>
<div class="container" id="inventory_div">
	<div class="row">
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
                        <div class="standard-body-title"><h3>Receive Shipment</h3></div>
                        <div class="standard-body-content pad-left pad-right">
                            <!-- Notice -->
                            <div class="notice gap-bottom gap-top popover-examples">
                                <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
                                <div class="col-sm-11"><span class="notice-name">NOTE:</span>
                                Used to keep track of that shipments of inventory you have received.</div>
                                <div class="clearfix"></div>
                            </div>
                            <?php include('../Inventory/receive_shipment_inc.php'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include ('../footer.php'); ?>