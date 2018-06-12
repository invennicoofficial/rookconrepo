<?php
    $calllog_schedule_status = get_config($dbc, 'calllog_schedule_status');
    
    $each_tab = array('Available', 'Abandoned');
    $statusCount = 0;
    foreach ($each_tab as $cat_tab) {
        if ((!empty($_GET['status'])) && ($_GET['status'] == $cat_tab))
            $statusCount++;
    }
    
    $totalCount = 0;
    foreach ($each_tab as $cat_tab) {
        if(empty($_GET['status']) || ($statusCount == 0 && $totalCount == 0)) {
            $cat_tab = 'Available';
            $_GET['status'] = 'Available';
        }
        
        if ((!empty($_GET['status'])) && ($_GET['status'] == $cat_tab)) {
            $active_to_be = ' active_tab';
        }
        else {
            $active_to_be = '';
        }
        
        // echo "<a href='call_log.php?maintype=leadbank&status=".$cat_tab."'><button type='button' class='btn brand-btn mobile-block  mobile-100 ".$active_to_be."'>".$cat_tab." Leads</button></a>&nbsp;&nbsp";
        $totalCount++;

    }

if($_GET['status'] == 'Available'): ?>
    <?php include('available_lead.php'); ?>
<?php elseif($_GET['status'] == 'Abandoned'): ?>
    <?php include('abandoned_lead.php'); ?>
<?php endif; ?>
