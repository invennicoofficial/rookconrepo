<?php
    $each_tab = array('Daily', 'Weekly', 'Bi-Monthly', 'Monthly', 'Quarterly', 'Semi-Annually', 'Yearly');
    $statusCount = 0;
    foreach ($each_tab as $cat_tab) {
        if ((!empty($_GET['status'])) && ($_GET['status'] == $cat_tab))
            $statusCount++;
    }

    $totalCount = 0;
    foreach ($each_tab as $cat_tab) {
        if(empty($_GET['status']) || ($statusCount == 0 && $totalCount == 0)) {
            $cat_tab = 'Daily';
            $_GET['status'] = 'Daily';
        }

        if ((!empty($_GET['status'])) && ($_GET['status'] == $cat_tab)) {
            $active_to_be = ' active_tab';
        }
        else {
            $active_to_be = '';
        }

        // echo "<a href='call_log.php?maintype=goals&status=".$cat_tab."'><button type='button' class='btn brand-btn mobile-block  mobile-100 ".$active_to_be."'>".$cat_tab."</button></a>&nbsp;&nbsp";
        $totalCount++;

    } ?>

<div class="container">
	<div class="row"><?php
        $notes = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT note FROM notes_setting WHERE subtab='cc_goals'"));
        $note = $notes['note'];
        if ( !empty($note) ) { ?>
            <div class="notice popover-examples">
                <div class="col-sm-1 notice-icon"><img src="../img/info.png" class="wiggle-me" width="25"></div>
                <div class="col-sm-11"><span class="notice-name">NOTE:</span>
                <?= $note ?></div>
                <div class="clearfix"></div>
            </div><?php
        }
        
            //echo '<a href="#" class="btn brand-btn" onclick="history.go(-1);return false;">Back</a><br>';

            $statusLike = (empty($_GET['status']) ? 'Daily' : str_replace('-','_',$_GET['status']));
            $status = (empty($_GET['status']) ? 'Daily' : $_GET['status']);

            $query_check_credentials = "SELECT * FROM calllog_goals WHERE goal_set =" . $_SESSION['contactid'] .  " AND goal_timeline LIKE '" . $statusLike . "'";
            $result = mysqli_query($dbc, $query_check_credentials);
            if(mysqli_num_rows($result) == 0) {
                echo "<h1>No Goals Found!</h1>";
            }

            while($row = mysqli_fetch_array( $result )) {
                if($row['heading'] != '')
                    echo '<div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href=\''.WEBSITE_URL.'/Cold Call/field_config_call_log_goals.php?status='.$status.'&calllog_goal='.$row["calllog_goals_id"].'\' >'.$row['heading'].'</a></div>';
            }
		?>
	</div>
</div>
