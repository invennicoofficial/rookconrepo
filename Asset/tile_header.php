<div class="col-xs-12 col-sm-4">
    <h1>
        <span class="pull-left" style="margin-top: -5px;"><a href="asset.php" class="default-color">Assets</a></span>
        <span class="clearfix"></span>
    </h1>
</div>
<div class="col-xs-12 col-sm-8 text-right settings-block">
    <?php
    if(config_visible_function($dbc, 'asset') == 1) { ?>
        <div class="pull-right gap-left top-settings">
            <a href="field_config.php?type=tab" class="mobile-block pull-right "><img title="Tile Settings" src="<?= WEBSITE_URL; ?>/img/icons/settings-4.png" class="settings-classic wiggle-me" width="30"></a>
            <span class="popover-examples list-inline pull-right" style="margin:5px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
        </div><?php
    }
    if(vuaed_visible_function($dbc, 'asset') == 1) {
        echo '<a href="add_asset.php?category='.$category.'" class="btn brand-btn mobile-block gap-bottom pull-right">New Asset</a>';
    } ?>
</div>
<div class="clearfix"></div>