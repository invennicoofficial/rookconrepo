<?php
/*
 * Next Action Report
 * Included from reports.php
 */
include ('../include.php');
checkAuthorised('sales');
?>

    <form method="post" action="" class="form-horizontal" role="form"><?php
        $starttime = isset($_GET['starttime']) ? $_GET['starttime'] : date('Y-m-01');
        $endtime = isset($_GET['endtime']) ? $_GET['endtime'] : date('Y-m-d');
        $search_user = '';
        
        if (isset($_GET['staff'])) {
            $search_user = $_GET['staff'];
        }
        if (isset($_POST['search_user_submit'])) {
            $starttime = $_POST['starttime'];
            $endtime = $_POST['endtime'];
            $search_user = $_POST['search_user'];
        }

        if($starttime == 0000-00-00) {
            $starttime = date('Y-m-01');
        }
        if($endtime == 0000-00-00) {
            $endtime = date('Y-m-d');
        } ?>
        
        <input name="search_business" type="hidden" value="" />

        <div class="col-sm-5 form-group">
            <label for="site_name" class="col-sm-4 control-label">Staff:</label>
            <div class="col-sm-8">
                <select data-placeholder="Select Staff" name="search_user" class="chosen-select-deselect form-control" width="380">
                    <option value=""></option><?php
                    $query = mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE `deleted`=0 AND `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `status`>0");
                    $contact_list = [];

                    while ( $row=mysqli_fetch_array($query) ) {
                        $contact_list[$row['contactid']] = decryptIt($row['first_name']) . ' ' . decryptIt($row['last_name']);
                    }
                    asort($contact_list);
                    array_filter($contact_list);

                    foreach($contact_list as $key => $value) { ?>
                        <option <?= ($key==$search_user) ? 'selected="selected"' : ''; ?> value="<?= $key; ?>"><?= $value; ?></option><?php
                    } ?>
                </select>
            </div>
        </div>
        
        <div class="clearfix"></div>
        
        <div class="col-sm-5 form-group">
            <label class="col-sm-4 control-label">From:</label>
            <div class="col-sm-8"><input name="starttime" type="text" class="datepicker form-control" value="<?= $starttime; ?>" /></div>
        </div>
        <div class="col-sm-5 form-group until">
            <label for="site_name" class="col-sm-4 control-label">Until:</label>
            <div class="col-sm-8"><input name="endtime" type="text" class="datepicker form-control" value="<?= $endtime; ?>" /></div>
        </div>
        &nbsp;&nbsp;<button type="submit" name="search_user_submit" value="Search" class="btn brand-btn mobile-block mobile-anchor">Search</button>

        <div id="no-more-tables"><?php
            $lead_status_lost = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `value` FROM `general_configuration` WHERE name='lead_status_lost'"))['value'];
            
            if ( $search_user!='' ) {
                $query_check_credentials = "SELECT COUNT(`salesid`) AS `total_source`, `next_action` FROM `sales` WHERE `status`!='$lead_status_lost' AND `primary_staff`='$search_user' AND (`created_date` BETWEEN '$starttime' AND '$endtime') GROUP BY `next_action`";
            } else {
                $query_check_credentials = "SELECT COUNT(`salesid`) AS `total_source`, `next_action` FROM `sales` WHERE `status`!='$lead_status_lost' AND (`created_date` BETWEEN '$starttime' AND '$endtime') GROUP BY `next_action`";
            }

            $result = mysqli_query($dbc, $query_check_credentials);
            
            if ( $result->num_rows > 0 ) {
                echo '<table class="table table-bordered">';
                    echo '<tr class="hidden-xs hidden-sm">';
                        echo '<th>Next Action</th>';
                        echo '<th>Total</th>';
                    echo '</tr>';

                    while ( $row=mysqli_fetch_array($result) ) {
                        echo '<tr>';
                            echo '<td data-title="Next Action">'. $row['next_action'] .'</td>';
                            echo '<td data-title="Total">'. $row['total_source'] .'</td>';
                        echo '</tr>';
                    }
                echo '</table>';
            } else {
                echo '<h3>No Record Found</h3>';
            } ?>
        </div><!-- #no-more-tables -->
    </form>