<?php
/*
 * Summary Tab
 * Called from index.php
 */
?>

<?php include ('../include.php');
checkAuthorised('tasks'); ?>
<div class="standard-dashboard-body-content"><?php
    $get_tabs = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT task_dashboard_tile FROM task_dashboard")); ?>
    <div id="summary-div" class="col-xs-12"><?php
        $get_count = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT count(tl.tasklistid) as task_count FROM tasklist tl JOIN task_board tb ON (tl.task_board=tb.taskboardid) WHERE tl.task_board=tb.taskboardid AND tb.board_security='Private' AND tl.task_milestone_timeline<>'' AND tl.contactid IN({$_SESSION['contactid']}) AND tl.deleted=0 AND tb.deleted=0"));
        $task_count = ($get_count['task_count'] > 0) ? $get_count['task_count'] : 0;
                
        echo '<div class="col-xs-12 col-sm-6 col-md-3 gap-top">';
            echo '<div class="summary-block">';
                echo '<div class="text-lg">'. $task_count .'</div>';
                echo '<div>Private Tasks</div>';
            echo '</div>';
        echo '</div>';
        
        foreach ( explode(',', $get_tabs['task_dashboard_tile']) as $enabled_tab ) {
            if ( $enabled_tab!=='Community Tasks' && $enabled_tab!=='Business Tasks' && $enabled_tab!=='Reporting' ) {
                
                if ( $enabled_tab=='Company Tasks' ) {
                    $enabled_tab = 'Shared Tasks';
                    $get_count = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT count(tl.tasklistid) task_count FROM tasklist tl JOIN task_board tb ON (tl.task_board=tb.taskboardid) WHERE tl.contactid IN (". $_SESSION['contactid'] .") AND (tb.board_security='Company' AND tb.company_staff_sharing LIKE '%,". $_SESSION['contactid'] .",%') AND (tl.archived_date IS NULL OR tl.archived_date='0000-00-00') AND tl.deleted=0 AND tb.deleted=0 ORDER BY tl.task_tododate"));
                } elseif ( $enabled_tab=='Project Tasks' ) {
                    $get_count = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT count(tl.tasklistid) task_count FROM tasklist tl JOIN task_board tb ON (tl.task_board=tb.taskboardid) WHERE tl.contactid IN (". $_SESSION['contactid'] .") AND (tb.board_security='Project' AND tb.company_staff_sharing LIKE '%,". $_SESSION['contactid'] .",%') AND (tl.archived_date IS NULL OR tl.archived_date='0000-00-00') AND tl.deleted=0 AND tb.deleted=0 ORDER BY tl.task_tododate"));
                } elseif ( $enabled_tab=='Client Tasks' ) {
                    $enabled_tab = (substr(CONTACTS_TILE, -1)=='s' && substr(CONTACTS_TILE, -2) !='ss') ? rtrim(CONTACTS_TILE, 's').' Tasks' : CONTACTS_TILE.' Tasks';
                    $get_count = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT count(tl.tasklistid) task_count FROM tasklist tl JOIN task_board tb ON (tl.task_board=tb.taskboardid) WHERE tl.contactid IN (". $_SESSION['contactid'] .") AND (tb.board_security='Client' AND tb.company_staff_sharing LIKE '%,". $_SESSION['contactid'] .",%') AND (tl.archived_date IS NULL OR tl.archived_date='0000-00-00') AND tl.deleted=0 AND tb.deleted=0 ORDER BY tl.task_tododate"));
                } else {
                    $board_security = str_replace(' Tasks', '', $enabled_tab);
                    $get_count = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT count(tl.tasklistid) as task_count FROM tasklist tl JOIN task_board tb ON (tl.task_board=tb.taskboardid) WHERE tl.task_board=tb.taskboardid AND tb.board_security='$board_security' AND tl.task_milestone_timeline<>'' AND tl.deleted=0 AND tb.deleted=0"));
                }
                
                $task_count = ($get_count['task_count'] > 0) ? $get_count['task_count'] : 0;
                
                echo '<div class="col-xs-12 col-sm-6 col-md-3 gap-top">';
                    echo '<div class="summary-block">';
                        echo '<div class="text-lg">'. $task_count .'</div>';
                        echo '<div>'. $enabled_tab .'</div>';
                    echo '</div>';
                echo '</div>';
            }
        } ?>
    </div><!-- #summary-div -->
</div><!-- .standard-dashboard-body-content -->