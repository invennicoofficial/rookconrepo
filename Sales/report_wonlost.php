<?php
/*
 * Total Won/Lost Report
 * Included from reports.php
 */
include ('../include.php');
checkAuthorised('sales');
?>

    <form method="post" action="" class="form-horizontal" role="form"><?php
        $starttime = isset($_GET['starttime']) ? $_GET['starttime'] : date('Y-m-01');
        $endtime = isset($_GET['endtime']) ? $_GET['endtime'] : date('Y-m-d');
        $search_user = isset($_GET['staff']) ? $_GET['staff'] : '';
        
        if (isset($_POST['search_email_submit'])) {
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
                    /* $query = mysqli_query($dbc, "SELECT DISTINCT `lead_created_by` FROM `sales` ORDER BY `lead_created_by`");
                    while($row = mysqli_fetch_array($query)) { ?>
                        <option <?= ($row['lead_created_by']==$search_user) ? 'selected="selected"' : ''; ?> value="<?= $row['lead_created_by']; ?>"><?= $row['lead_created_by']; ?></option><?php
                    } */
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
        &nbsp;&nbsp;<button type="submit" name="search_email_submit" value="Search" class="btn brand-btn mobile-block mobile-anchor">Search</button>
        
        <div class="clearfix double-gap-top"></div>

        <div id="no-more-tables"><?php
            $lead_status_won = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `value` FROM `general_configuration` WHERE name='lead_status_won'"))['value'];
            $lead_status_lost = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `value` FROM `general_configuration` WHERE name='lead_status_lost'"))['value'];
            
            $query_check_credentials = "SELECT * FROM `sales` WHERE (`status`='$lead_status_won' OR `status`='$lead_status_lost') AND (`created_date` BETWEEN '$starttime' AND '$endtime')";
            
            if($search_user != '') {
                $query_check_credentials .= " AND (`primary_staff`='$search_user' OR `share_lead`='$search_user')";
            }
            $result = mysqli_query($dbc, $query_check_credentials);
            
            if ( $result->num_rows > 0 ) {
                echo '<table class="table table-bordered">';
                    echo '<tr class="hidden-xs hidden-sm">';
                        echo '<th>Staff</th>
                        <th>Business</th>
                        <th>Contact</th>
                        <th>Lead Value</th>
                        <th>Status</th>';
                    echo "</tr>";
                    
                    $total_won = 0;
                    $total_lost = 0;
                    
                    while ( $row=mysqli_fetch_array($result) ) {
                        echo '<tr>';
                            echo '<td data-title="Staff">' . get_staff($dbc, $row['primary_staff']) . '</td>';
                            echo '<td data-title="Business">' . get_contact($dbc, $row['businessid'], 'name') . '</td>';
                            echo '<td data-title="Contact">' . get_contact($dbc, $row['contactid'], 'first_name').' '.get_contact($dbc, $row['contactid'], 'last_name') . '</td>';
                            echo '<td data-title="Lead Value">' . $row['lead_value'] . '</td>';
                            echo '<td data-title="Status">' . $row['status'] . '</td>';
                        echo "</tr>";
                        if($row['status'] == $lead_status_won) {
                            $total_won++;
                        }
                        if($row['status'] == $lead_status_lost) {
                            $total_lost++;
                        }
                    }
                echo '</table>';
            } else {
                echo "<h3>No Record Found.</h3>";
            } ?>
        </div><!-- #no-more-tables -->
    </form>