<!-- Tile Sidebar -->
<?php if($_GET['settings'] == 'config') {
    $settings_type = 'user';
    if(!empty($_GET['settings_type'])) {
        $settings_type = $_GET['settings_type'];
    } ?>
    <ul class="sidebar">
        <a href="?settings=config&settings_type=user"><li <?= ($settings_type == 'user' ? 'class="active"' : '') ?>>User Settings</li></a>
        <?php if (config_visible_function($dbc, 'profile') == 1) { ?><a href="?settings=config&settings_type=software"><li <?= ($settings_type == 'software' ? 'class="active"' : '') ?>>Software Default Settings</li></a><?php } else { ?><li <?= ($settings_type == 'software' ? 'class="active"' : '') ?>>Software Default Settings</li><?php } ?>
    </ul>
<?php } else { ?>
    <ul class="sidebar">
        <a href="?tab=daysheet"><li class="<?= $_GET['tab'] == 'daysheet' ? 'active' : '' ?>">Day Sheet</li></a>
        <a href="?tab=journals"><li class="<?= $_GET['tab'] == 'journals' ? 'active' : '' ?>">My Journal</li></a>
        <a href="?tab=alerts&daily_date=<?= date('Y-m-d') ?>&side_content=past_due"><li class="<?= $_GET['tab'] == 'alerts' ? 'active' : '' ?>">My Alerts</li></a>
        <a href="?tab=projects&daily_date=<?= date('Y-m-d') ?>&side_content=my_projects"><li class="<?= $_GET['tab'] == 'projects' ? 'active' : '' ?>">My <?= PROJECT_TILE ?></li></a>
        <a href="?tab=tickets&daily_date=<?= date('Y-m-d') ?>&side_content=my_tickets&date_display=daily"><li class="<?= $_GET['tab'] == 'tickets' ? 'active' : '' ?>">My <?= TICKET_TILE ?></li></a>
        <a href="?tab=tasks&daily_date=<?= date('Y-m-d') ?>&side_content=my_tasks&date_display=daily"><li class="<?= $_GET['tab'] == 'tasks' ? 'active' : '' ?>">My Tasks</li></a>
        <a href="?tab=checklists&daily_date=<?= date('Y-m-d') ?>&side_content=my_checklists&date_display=daily"><li class="<?= $_GET['tab'] == 'checklists' ? 'active' : '' ?>">My Checklists</li></a>
    </ul>
<?php } ?>