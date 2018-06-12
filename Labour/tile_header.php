<div class="pull-right settings-block">
    <?php
    if(config_visible_function($dbc, 'labour') == 1) { ?>
        <div class="gap-left pull-right">
            <a href="?settings=dashboard" class="mobile-block pull-right "><img title="Tile Settings" src="<?= WEBSITE_URL; ?>/img/icons/settings-4.png" class="settings-classic wiggle-me" width="30"></a>
            <span class="popover-examples list-inline pull-right" style="margin:5px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
        </div><?php
    }
    if(vuaed_visible_function($dbc, 'labour') == 1) {
        echo '<a href="?edit=" class="btn brand-btn mobile-block gap-bottom pull-right">New Labour</a>';
    } ?>
</div>
<div class="scale-to-fill">
    <h1 class="gap-left"><a href="?" class="default-color">Labour</a></h1>
</div>
<div class="clearfix"></div>