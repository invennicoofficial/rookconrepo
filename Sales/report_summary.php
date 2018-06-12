<?php
/*
 * Monthly Summary Report
 * Included from reports.php
 */
 
include_once('../include.php');
checkAuthorised('sales');
?>

    <form method="post" action="" class="form-horizontal" role="form"><?php
        $starttime = isset($_GET['starttime']) ? $_GET['starttime'] : date('Y-m-01');
        $endtime = isset($_GET['endtime']) ? $_GET['endtime'] : date('Y-m-d');
        $search_user = isset($_GET['staff']) ? $_GET['staff'] : '';
        $search_business = isset($_GET['business']) ? $_GET['business'] : '';
        
        if (isset($_POST['search_email_submit'])) {
            $starttime = $_POST['starttime'];
            $endtime = $_POST['endtime'];
            $search_user = $_POST['search_user'];
            $search_business = $_POST['search_business'];
        }

        if($starttime == 0000-00-00) {
            $starttime = date('Y-m-01');
        }
        if($endtime == 0000-00-00) {
            $endtime = date('Y-m-d');
        } ?>
        
        <div class="col-sm-5 form-group">
            <label for="site_name" class="col-sm-4 control-label">Staff:</label>
            <div class="col-sm-8">
                <select data-placeholder="Select Staff" name="search_user" class="chosen-select-deselect form-control" width="380">
                    <option value=""></option><?php
                    /* $query = mysqli_query($dbc,"SELECT DISTINCT `lead_created_by` FROM `sales` ORDER BY `lead_created_by`");
                    while($row = mysqli_fetch_array($query)) { ?>
                        <option <?= ($row['lead_created_by']==$search_user) ? 'selected="selected"' : ''; ?> value="<?= $row['lead_created_by'] ?>"><?= $row['lead_created_by'] ?></option><?php
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

        <div class="col-sm-5 form-group">
            <label for="site_name" class="col-sm-4 control-label">Business:</label>
            <div class="col-sm-8">
                <select data-placeholder="Select a Business" name="search_business" class="chosen-select-deselect form-control" width="380">
                    <option value=""></option><?php
                    $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT `contacts`.`contactid`, `contacts`.`name` FROM `sales` LEFT JOIN `contacts` ON `sales`.`businessid`=`contacts`.`businessid` GROUP BY `sales`.`businessid`, `contacts`.`name`"),MYSQLI_ASSOC));
                    foreach($query as $row) { ?>
                        <option <?= ($row==$search_business) ? 'selected="selected"' : ''; ?> value="<?= $row; ?>"><?= get_client($dbc, $row) ?></option><?php
                    } ?>
                </select>
            </div>
        </div>
        
        <div class="clearfix"></div>

        <div class="col-sm-5 form-group">
            <label for="site_name" class="col-sm-4 control-label">From:</label>
            <div class="col-sm-8"><input name="starttime" type="text" class="datepicker form-control" value="<?= $starttime ?>"></div>
        </div>

        <div class="col-sm-5 form-group until">
            <label for="site_name" class="col-sm-4 control-label">Until:</label>
            <div class="col-sm-8"><input name="endtime" type="text" class="datepicker form-control" value="<?= $endtime ?>"></div>
        </div>

        &nbsp;&nbsp;<button type="submit" name="search_email_submit" value="Search" class="btn brand-btn mobile-block mobile-anchor">Search</button>
        
        <div class="clearfix"></div>

        <div id="no-more-tables"><?php
            $query_check_credentials = "SELECT * FROM sales WHERE (`created_date` BETWEEN '$starttime' AND '$endtime')";
            
            if($search_user != '') {
                $query_check_credentials .= " AND `primary_staff`='$search_user' OR `share_lead`='$search_user'";
            }
            if($search_business > 0) {
                $query_check_credentials .= " AND `businessid`='$search_business'";
            }

            $result = mysqli_query($dbc, $query_check_credentials);
            $num_rows = mysqli_num_rows($result);
            
            if($num_rows > 0) {
                echo '<table class="table table-bordered double-gap-top">';
                    echo '<tr class="hidden-xs hidden-sm">';
                        echo '<th>Sales Lead #</th>';
                        echo '<th>Business</th>';
                        echo '<th>Contact</th>';
                        echo '<th>Phone</th>';
                        echo '<th>Email</th>';
                        echo '<th>Next Action</th>';
                        echo '<th>Reminder</th>';
                        echo '<th>Status</th>';
                    echo '</tr>';
                    
                    $total_won = 0;
                    $total_lost = 0;
                    
                    while ( $row=mysqli_fetch_array($result) ) {
                        echo '<tr>';
                            echo '<td data-title="Sales Lead #"><a href="sale.php?p=preview&id='.$row['salesid'].'">'. $row['salesid'] .'</a></td>';
                            echo '<td data-title="Business">'. get_contact($dbc, $row['businessid'], 'name') .'</td>';
                            echo '<td data-title="Contact Name">'. get_contact($dbc, $row['contactid'], 'first_name').' '.get_contact($dbc, $row['contactid'], 'last_name') . '</td>';
                            echo '<td data-title="Primary Phone">'. $row['primary_number'] .'</td>';
                            echo '<td data-title="Email">'. $row['email_address'] .'</td>';
                            echo '<td data-title="Next Action">'. $row['next_action'] .'</td>';
                            echo '<td data-title="Reminder">'. $row['new_reminder'] .'</td>';
                            echo '<td data-title="Reminder">'. $row['status'] .'</td>';
                        echo "</tr>";
                    }
                echo '</table>';
            } else {
                echo '<h3>No Record Found</h3>';
            } ?>
        </div><!-- #no-more-tables -->
    </form>