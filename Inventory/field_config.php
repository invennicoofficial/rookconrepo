<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('inventory');

error_reporting(0); ?>
<script type="text/javascript" src="inventory.js"></script>
<script type="text/javascript" src="field_config.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#mobile_tabs .panel-heading').click(loadPanel);
});
function loadPanel() {
    var panel = $(this).closest('.panel').find('.panel-body');
    panel.html('Loading...');
    $.ajax({
        url: panel.data('file-name'),
        method: 'POST',
        response: 'html',
        success: function(response) {
            panel.html(response);
        }
    });
}
</script>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container" id="inventory_div">
    <div class="row">
        <div class="main-screen">
            <div class="tile-header">
                <?php include('../Inventory/tile_header.php'); ?>
            </div>

            <div class="tile-container" style="height: 100%;">
                <?php $type = $_GET['type'];
                $config_tabs = [
                    'tile' => 'Tile Settings',
                    'tab' => 'Tabs',
                    'field' => 'Fields',
                    'dashboard_tab' => 'Dashboard Sub Tabs',
                    'dashboard' => 'Dashboard Fields',
                    'pick_list' => 'Pick List Options',
                    'cost' => 'Inventory Cost &amp; Pricing',
                    'general' => 'Min Bin Settings',
                    'rs' => 'Receive Shipment',
                    'order_list' => 'Order Lists',
                    'digi_count' => 'Digital Inventory Count',
                    'impexp' => 'Import/Export'
                ]; ?>

                <div class="show-on-mob panel-group block-panels col-xs-12 form-horizontal" id="mobile_tabs">
                    <?php foreach($config_tabs as $url => $config_tab) { ?>
                        <div class="panel panel-default" style="background: white;">
                            <div class="panel-heading mobile_load">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#mobile_tabs" href="#collapse_<?= $url ?>">
                                        <?= $config_tab ?><span class="glyphicon glyphicon-plus"></span>
                                    </a>
                                </h4>
                            </div>

                            <div id="collapse_<?= $url ?>" class="panel-collapse collapse">
                                <div class="panel-body" data-file-name="field_config_load_tab.php?type=<?= $url ?>">
                                    Loading...
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="standard-collapsible tile-sidebar set-section-height hide-titles-mob">
                    <ul class="sidebar">
                        <?php if(get_config($dbc, 'inventory_default_select_all') == 1) { ?>
                            <a href="inventory.php?category=dispall_31V2irt2u3e5S3s1f2ADe3_31"><li>Back to Dashboard</li></a>
                        <?php } else { ?>
                            <a href="inventory.php?category=Top"><li>Back to Dashboard</li></a>
                        <?php } ?>
                        <?php foreach ($config_tabs as $url => $tab_name) { ?>
                            <a href="?type=<?= $url ?>"><li <?= $type == $url ? 'class="active"' : '' ?>><?= $tab_name ?></li></a>
                        <?php } ?>
                    </ul>
                </div>

                <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

                    <div class="scale-to-fill has-main-screen tile-content hide-titles-mob">
                        <div class="main-screen standard-body">
                            <div class="standard-body-title"><h3><?= $config_tabs[$type] ?></h3></div>
                            <div class="standard-body-content pad-left pad-right">
                                <?php include('field_config_'.$type.'.php'); ?>
                                <?php if ($type != 'order_list') { ?>
                                    <div class="pull-right gap-top gap-right gap-bottom">
                                        <a href="inventory.php" class="btn brand-btn">Cancel</a>
                                        <button type="submit" id="submit" name="submit" value="Submit" class="btn brand-btn">Submit</button>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </form>
                
                <div class="clearfix"></div>
            </div><!-- .tile-container -->

        </div><!-- .main-screen -->
    </div><!-- .row -->
</div><!-- .container -->

<?php include ('../footer.php'); ?>