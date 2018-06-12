<?php
/*
 * Order Lists Sub Tab Dashboard
 * Uses same code as field_config_order_lists.php
 * If you update this file, update field_config_order_lists.php as well
 */
include ('../include.php');
checkAuthorised('inventory');
error_reporting(0);

?>
<script type="text/javascript" src="inventory.js"></script>
</head>
<body>

<?php include ('../navigation.php');
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
                        <div class="standard-body-title"><h3>Order Lists</h3></div>
                        <div class="standard-body-content pad-left pad-right">
                            <?php include('../Inventory/order_lists_inc.php'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include ('../footer.php'); ?>