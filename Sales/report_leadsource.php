<?php
/*
 * Lead Source Report
 * Included from reports.php
 */
include ('../include.php');
checkAuthorised('sales');
?>

    <form method="post" action="" class="form-horizontal" role="form"><?php
        $search_user = '';
        if (isset($_GET['staff'])) {
            $search_user = $_GET['staff'];
        } else {
            $search_user = '';
        }
        if (isset($_POST['search_user_submit'])) {
            $search_user = $_POST['search_user'];
        } ?>
        
        <input name="search_business" type="hidden" value="" />

        <div class="form-group">
            <label for="site_name" class="col-sm-3 control-label">Staff:</label>
            <div class="col-sm-5">
                <select data-placeholder="Select Staff" name="search_user" class="chosen-select-deselect form-control">
                    <option value=""></option><?php
                    /* $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE `deleted`=0 AND `category`='Staff' AND `status`=1"),MYSQLI_ASSOC));
                    foreach($query as $id) { ?>
                        <option <?= ($id==$search_user) ? 'selected="selected"' : ''; ?> value="<?= $id ?>"><?= get_contact($dbc, $id); ?></option><?php
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
            <div class="col-sm-4">
                <button type="submit" name="search_user_submit" value="Search" class="btn brand-btn mobile-block mobile-anchor">Search</button>
            </div>
        </div>

        <div id="no-more-tables"><?php
            $lead_status_lost = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `value` FROM `general_configuration` WHERE name='lead_status_lost'"))['value'];
            
            if($search_user != '') {
                $query_check_credentials = "SELECT `lead_source` FROM `sales` WHERE `status`!='$lead_status_lost' AND `primary_staff`='$search_user' AND IFNULL(`lead_source`,'') != ''";
            } else {
                $query_check_credentials = "SELECT `lead_source` FROM `sales` WHERE `status`!='$lead_status_lost' AND IFNULL(`lead_source`,'') != ''";
            }

            $result = mysqli_query($dbc, $query_check_credentials);
            
            if($result->num_rows > 0) {
                echo '<table class="table table-bordered">';
                    echo '<tr class="hidden-xs hidden-sm">';
                        echo '<th>Lead Source</th>';
                        echo '<th>Total</th>';
                    echo '</tr>';

                    $lead_sources = [];
                    while($row = mysqli_fetch_array( $result ))
                    {
                        $lead_source = explode('#*#', $row['lead_source']);
                        foreach($lead_source as $lead_source_type) {
                            $lead_sources[$lead_source_type]++;
                        }
                    }
                    foreach($lead_sources as $lead_source => $lead_source_count) {
                        if(is_numeric($lead_source)) {
                            if(get_contact($dbc, $lead_source_type, 'category') == 'Business') {
                                $lead_source_label = get_client($dbc, $lead_source);
                            } else {
                                $lead_source_label = get_contact($dbc, $lead_source);
                            }
                        } else {
                            $lead_source_label = $lead_source;
                        }
                        echo "<tr>";
                        echo '<td data-title="Lead Source">' . $lead_source_label . '</td>';
                        echo '<td data-title="Total">' . (!empty($lead_source_count) ? $lead_source_count : 0) . '</td>';
                        echo "</tr>";
                    }
                echo '</table>';
            } else {
                echo '<h3>No Record Found</h3>';
            } ?>
        </div>
    </form>