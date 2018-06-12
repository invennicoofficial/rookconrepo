<?php
/*
 * Leads Added To Pipeline Report
 * Included from reports.php
 */
include ('../include.php');
checkAuthorised('sales');
?>

    <form method="post" action="" class="form-horizontal" role="form"><?php
        $starttime = isset($_GET['starttime']) ? $_GET['starttime'] : date('Y-m-01');
        $endtime = isset($_GET['endtime']) ? $_GET['endtime'] : date('Y-m-d');
        
        if (isset($_POST['search_email_submit'])) {
            $starttime = $_POST['starttime'];
            $endtime = $_POST['endtime'];
        }

        if($starttime == 0000-00-00) {
            $starttime = date('Y-m-01');
        }
        if($endtime == 0000-00-00) {
            $endtime = date('Y-m-d');
        } ?>
        
        <input name="search_user" type="hidden" value="" />
        <input name="search_business" type="hidden" value="" />
        
        <div class="col-sm-5 form-group">
            <label for="site_name" class="col-sm-4 control-label">From:</label>
            <div class="col-sm-8"><input name="starttime" type="text" class="datepicker form-control" value="<?= $starttime; ?>" /></div>
        </div>
        <div class="col-sm-5 form-group until">
            <label for="site_name" class="col-sm-4 control-label">Until:</label>
            <div class="col-sm-8"><input name="endtime" type="text" class="datepicker form-control" value="<?= $endtime; ?>" /></div>
        </div>
        &nbsp;&nbsp;<button type="submit" name="search_email_submit" value="Search" class="btn brand-btn mobile-block mobile-anchor">Search</button>
        
        <div class="clearfix double-gap-top"></div>

        <div id="no-more-tables"><?php
            $query_check_credentials = "SELECT COUNT(`salesid`) AS `total_source`, `primary_staff` FROM `sales` WHERE (`created_date` BETWEEN '$starttime' AND '$endtime') GROUP BY `primary_staff`";
            $result = mysqli_query($dbc, $query_check_credentials);
            
            if ( $result->num_rows > 0 ) {
                echo '<table class="table table-bordered">';
                    echo '<tr class="hidden-xs hidden-sm">';
                        echo '<th>Staff</th>';
                        echo '<th>Total</th>';
                    echo '</tr>';

                    while ( $row=mysqli_fetch_array($result) ) {
                        echo '<tr>';
                            echo '<td data-title="Staff">'. get_staff($dbc, $row['primary_staff']) .'</td>';
                            echo '<td data-title="Total">'. $row['total_source'] .'</td>';
                        echo '</tr>';
                    }
                echo '</table>';
            } else {
                echo '<h3>No Record Found</h3>';
            } ?>
        </div><!-- #no-more-tables -->
    </form>
