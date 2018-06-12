<?php // Checkout View
error_reporting(0);
include_once('../include.php'); ?>
</head>
<body>
<?php 
if(FOLDER_NAME == 'posadvanced') {
    checkAuthorised('posadvanced');
} else {
    checkAuthorised('check_out');
}
$edit_access = vuaed_visible_function($dbc, 'check_out');
$config_access = config_visible_function($dbc, 'check_out');
include_once ('../navigation.php'); ?>
<div class="container">
    <div class="row">
        <div class="main-screen" style="background-color: #fff; border-width: 0; height: auto; margin-top: -20px;">
            <h3 style="margin-top: 0; padding: 0.25em;"><a href="?"><?= (empty($current_tile_name) ? 'Check Out' : $current_tile_name) ?></a><?php if($config_access > 0) {
                echo "<div class='pull-right' style='height: 1.35em; width: 1.35em;'><a href='../Invoice/field_config_settings.php'><img src='".WEBSITE_URL."/img/icons/settings-4.png' class='settings-classic wiggle-me' style='height: 100%;'></a></div>";
            } ?>
            <?php
            if($edit_access > 0) {
                echo "<div class='pull-right' style='height: 1em; padding: 0 0.25em;'><a href='?invoiceid=new' style='font-size: 0.5em;'><button class='btn brand-btn hide-titles-mob'>New Invoice</button>";
                echo "<img src='".WEBSITE_URL."/img/icons/ROOK-add-icon.png' class='show-on-mob' style='height: 2.5em;'></a></div>";
            } ?>
            </h3>
            <div class="clearfix"></div>
            <?php include('../Invoice/edit_invoice.php'); ?>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<?php include('../footer.php'); ?>