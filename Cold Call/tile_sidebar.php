<!-- Tile Sidebar -->
<?php
$active_maintype = $_GET['maintype'];
if(empty($active_maintype)) {
    $active_maintype = 'calllogpipeline';
}
$each_maintype = [
    'howto' => ['how_to_guide', 'How To Guide'],
    'preparation' => ['preparation', 'Preparation'],
    'calllogpipeline' => ['pipeline', 'Cold Call Pipeline'],
    'schedule' => ['schedule', 'Schedule'],
    'leadbank' => ['lead_bank', 'Lead Bank'],
    'goals' => ['goals', 'Goals'],
    'reporting' => ['reporting', 'Reporting']
];
switch($active_maintype) {
    case 'howto':
        $each_status = ['Funnel' => 'Cold Call Funnel', 'Definitions' => 'Cold Call Definitions', 'Infographic' => 'Cold Call Infographics'];
        break;
    case 'preparation':
        $each_status = ['Target Market' => 'Target Market', 'Objections' => 'Objections', 'Scripts' => 'Scripts'];
        break;
    case 'calllogpipeline':
        $calllog_lead_status = get_config($dbc, 'calllog_lead_status');
        $each_tab = explode(',', $calllog_lead_status);
        $each_status = [];
        foreach ($each_tab as $cat_tab) {
            if($cat_tab == 'Available' || $cat_tab == 'Abandoned' || $cat_tab == 'Lost/Archive') {
                continue;
            }
            $each_status[$cat_tab] = $cat_tab;
        }
        break;
    case 'schedule':
        $calllog_schedule_status = get_config($dbc, 'calllog_schedule_status');
        if(empty($calllog_schedule_status)) {
            $each_tab = ['Today'];
        } else {
            $each_tab = explode(',', $calllog_schedule_status);
        }
        $each_status = [];
        foreach ($each_tab as $cat_tab) {
            $each_status[$cat_tab] = $cat_tab;
        }
        break;
    case 'leadbank':
        $each_status = ['Available' => 'Available Leads', 'Abandoned' => 'Abandoned Leads'];
        break;
    case 'goals':
        $each_status = ['Daily' => 'Daily', 'Weekly' => 'Weekly', 'Bi-Monthly' => 'Bi-Monthly', 'Monthly' => 'Monthly', 'Quarterly' => 'Quarterly', 'Semi-Annually' => 'Semi-Annually', 'Yearly' => 'Yearly'];
        break;
    case 'reporting':
        $each_status = '';
}
$active_status = $_GET['status'];
if(empty($active_status)) {
    reset($each_status);
    $active_status = key($each_status);
    $_GET['status'] = key($each_status);
}
?>

<ul class="sidebar" style="overflow-x: hidden;">
    <?php foreach ($each_maintype as $key => $maintype) {
        if (check_subtab_persmission($dbc, 'calllog', ROLE, $maintype[0]) === TRUE) { ?>
            <a href="call_log.php?maintype=<?= $key ?>"><li <?= ($active_maintype == $key ? 'class="active"' : '') ?>><?= $maintype[1] ?></li></a>
        <?php } else { ?>
            <li <?= ($active_maintype == $key ? 'class="active"' : '') ?>><?= $maintype[1] ?></li>
        <?php }
        if ($active_maintype == $key) { ?>
            <ul style="position: relative; left: 2em; width: calc(100% - 2em);">
                <?php foreach ($each_status as $key2 => $status) { ?>
                    <a href="call_log.php?maintype=<?= $key ?>&status=<?= $key2 ?>"><li <?= ($active_status == $key2 ? 'class="active"' : '') ?>><?= $status ?></li></a>
                <?php } ?>
            </ul>
        <?php }
    } ?>
</ul>