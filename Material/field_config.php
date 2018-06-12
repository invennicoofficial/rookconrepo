<?php
/*
 * Sales Order Config
 */
error_reporting(0);
include ('../include.php');
?>

<script type="text/javascript">
$(document).ready(function() {
});
</script>
</head>

<body>
<?php
	include_once ('../navigation.php');
checkAuthorised('material');

    $tab_list = [
        'tab' => 'Tabs',
        'fields' => 'Fields',
        'dashboard' => 'Dashboard Fields',
        'send_email' => 'Send Email for Min Bin',
        'order_list' => 'Order Lists'
    ];

    $tab = $_GET['tab'];
    if(empty($tab)) {
        $tab = 'tab';
    }
?>

<div class="container">
    
    <div class="row hide_on_iframe">
		<div class="main-screen">
            <div class="tile-header">
                <div class="col-xs-12 col-sm-4">
                    <h1>
                        <span class="pull-left" style="margin-top: -5px;"><a href="material.php" class="default-color">Materials</a></span>
                        <span class="clearfix"></span>
                    </h1>
                </div>
                <div class="col-xs-12 col-sm-8 text-right settings-block">
                    <?php
                    if(config_visible_function($dbc, 'material') == 1) { ?>
                        <div class="pull-right gap-left top-settings">
                            <a href="field_config_material.php" class="mobile-block pull-right "><img title="Tile Settings" src="<?= WEBSITE_URL; ?>/img/icons/settings-4.png" class="settings-classic wiggle-me" width="30"></a>
                            <span class="popover-examples list-inline pull-right" style="margin:5px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                        </div><?php
                    }
                    if(vuaed_visible_function($dbc, 'material') == 1) {
                        echo '<div class="row gap-left gap-right">';
                            echo '<a href="add_material.php" class="btn brand-btn mobile-block gap-bottom pull-right">New Material</a>';
                            echo '<span class="popover-examples list-inline pull-right" style="margin:7px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add a new Material."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
                        echo '</div>';
                    } ?>
                </div>
                <div class="clearfix"></div>
            </div>
            
            <div class="tile-container" style="height: 100%;">
                <!-- Sidebar -->
                <div class="collapsible tile-sidebar set-section-height">
                    <ul class="double-gap-top">
                        <li><a href="material.php">Back to Dashboard</a></li>
                        <?php foreach ($tab_list as $key => $value) {
                            echo '<a href="?tab='.$key.'"><li class="collapsed cursor-hand '.($tab == $key ? 'active' : '').'">'.$value.'</li></a>';
                        } ?>
                    </ul>
                </div><!-- .tile-sidebar -->
                
                <!-- Main Screen -->
                <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

                    <div class="scale-to-fill">
                        <div class="main-screen-white double-gap-top">
                            <div class="preview-block-container">
                                <div class="preview-block">
                                    <div class="preview-block-header"><h4><?= $tab_list[$tab] ?></h4></div>
                                </div>
                                <?php include('field_config_'.$tab.'.php'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="pull-right gap-top gap-right gap-bottom">
                        <a href="material.php" class="btn brand-btn">Cancel</a>
                        <button type="submit" id="submit" name="submit" value="Submit" class="btn brand-btn">Submit</button>
                    </div>
                </form>
                
                <div class="clearfix"></div>
            </div><!-- .tile-container -->

        </div><!-- .main-screen -->
    </div><!-- .row -->
</div><!-- .container -->

<?php include ('../footer.php'); ?>