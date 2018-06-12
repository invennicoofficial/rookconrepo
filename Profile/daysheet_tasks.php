<!-- Daysheet My Tasks -->
<?php
    if(!empty($_GET['date_display'])) {
        $ptd = $_GET['date_display'];
        if($_GET['date_display'] == 'weekly') {
            $where = 'yearweek(DATE(tl.task_tododate), 1) = yearweek(curdate(), 1)';
        } else if($_GET['date_display'] == 'monthly') {
            $where = 'MONTH(tl.task_tododate) = MONTH(CURRENT_DATE()) AND YEAR(tl.task_tododate) = YEAR(CURRENT_DATE())';
        } else {
            $where = 'DATE(tl.task_tododate) = DATE(NOW())';
        }
    }

    $tasks_query = "SELECT DISTINCT(tb.taskboardid), tb.board_security, tb.company_staff_sharing, tb.board_name  FROM task_board tb, tasklist tl WHERE tb.taskboardid = tl.task_board AND tl.contactid = '$contactid' AND tl.deleted = 0 AND ".$where."";
    $tasks_result = mysqli_query($dbc, $tasks_query);
    $num_rows = mysqli_num_rows($tasks_result);
?>
    <div class="col-xs-12">
        <div class="weekly-div" style="overflow-y: hidden;">
            <?php if($num_rows > 0) {
                echo '<ul class="option-list">';
                while($taskboard = mysqli_fetch_array( $tasks_result )) {
                    echo "<a href='../Tasks/index.php?category=".$taskboard['taskboardid']."&tab=".$taskboard['board_security']."&from_url=".urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI'])."' class='col-sm-6'><li style='width:calc(100% - 3em);'>";
                    profile_id($dbc, trim($taskboard['company_staff_sharing'],','));
                    echo '<div style="display:inline; width:calc(100% - 3em);">'.$taskboard['board_name']."</div><div class='clearfix'></div></li></a>";
                }
                echo '</ul>';
            } else {
                echo "<h2>No Record Found.</h2>";
            } ?>
        </div>
    </div>