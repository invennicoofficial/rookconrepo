<?php
/*
Dashboard
*/
include ('../include.php');

error_reporting(0);
?>
<script type="text/javascript">
$(document).ready(function() {
	$("#tab_dashboard").change(function() {
        window.location = 'field_config.php?type=dashboard&tab='+this.value;
	});
	$("#tab_field").change(function() {
        window.location = 'field_config.php?type=field&tab='+this.value;
	});

	$("#acc").change(function() {
        var tabs = $("#tab_field").val();
        window.location = 'field_config.php?type=field&tab='+tabs+'&accr='+this.value;
	});
});
</script>
</head>
<body>

<?php include ('../navigation.php');
checkAuthorised('assets'); ?>

<div class="container">
    <div class="row">
        <div class="main-screen">
            <div class="tile-header">
                <?php include('../Asset/tile_header.php'); ?>
            </div>

            <div class="tile-container" style="height: 100%;">

                <div class="collapsible tile-sidebar set-section-height"><?php
                    $type = $_GET['type'];
                    $config_tabs = [
                        'tab' => 'Tabs',
                        'field' => 'Fields',
                        'dashboard' => 'Dashboard Fields',
                        'general' => 'Min Bin Settings',
                    ];
                    ?>

                    <ul class="sidebar">
                        <a href="asset.php?category=Top"><li>Back to Dashboard</li></a>
                        <?php foreach ($config_tabs as $url => $tab_name) { ?>
                            <a href="?type=<?= $url ?>"><li <?= $type == $url ? 'class="active"' : '' ?>><?= $tab_name ?></li></a>
                        <?php } ?>
                    </ul>
                </div>

                <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
                    <div class="scale-to-fill">
                        <div class="main-screen-white double-gap-top">
                            <div class="preview-block-container">
                                <div class="preview-block">
                                    <div class="preview-block-header"><h4><?= $config_tabs[$type] ?></h4></div>
                                </div>
                                <?php include('field_config_'.$type.'.php'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="pull-right gap-top gap-right gap-bottom">
                        <a href="inventory.php" class="btn brand-btn">Cancel</a>
                        <button type="submit" id="submit" name="submit" value="Submit" class="btn brand-btn">Submit</button>
                    </div>
                </form>
                
                <div class="clearfix"></div>
            </div><!-- .tile-container -->

        </div><!-- .main-screen -->
    </div><!-- .row -->
</div><!-- .container -->

<?php include ('../footer.php'); ?>