<?php
/*
Customer Listing
*/
include ('../include.php');
?>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('match');

//Status change
if(isset($_GET['status'])) {
    $matchid = $_GET['matchid'];
    $status = $_GET['status'];
    if($status == 0) {
        $status = 'Active';
    } elseif($status == 1) {
        $status = 'Suspend';
    }

    $match = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `match_contact` WHERE `matchid` = '$matchid'"));
    $history = '';
    if ($status != $match['status']) {
        $history = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']).' Changed Status on '.date('Y-m-d H:i:s').'<br>';   
    }

    $query_update_status = "UPDATE match_contact SET status = '$status', `history` = CONCAT(IFNULL(history, ''), '$history') WHERE matchid = $matchid";
    $result_update_status = mysqli_query($dbc, $query_update_status);
}
/* Get logged in user's role */
if ( !empty ( $_GET[ 'level' ] ) ) {
    $level_url = $_GET[ 'level' ];

} else {
    $contacterid    = $_SESSION['contactid'];
    $result = mysqli_query ( $dbc, "SELECT * FROM contacts WHERE contactid='$contacterid'" );

    while ( $row = mysqli_fetch_assoc( $result ) ) {
        $role = $row[ 'role' ];
    }

    $level_url = (strpos(','.ROLE.',',',super,') !== false) ? 'admin' : $role;
}
?>

<script type="text/javascript">
$(document).ready(function() {
    $('.iframe_open').click(function(){
        var id = $(this).attr('id');
        $('#iframe_instead_of_window').attr('src', 'match_history.php?matchid='+id);
        $('.iframe_title').text('Match History');
        $('.iframe_holder').show();
        $('.hide_on_iframe').hide();
    });

    $('.close_iframer').click(function(){
        $('.iframe_holder').hide();
        $('.hide_on_iframe').show();
    });
});
</script>

<div class="container triple-pad-bottom">
    <div class='iframe_holder' style='display:none;'>

        <img src='<?php echo WEBSITE_URL; ?>/img/icons/close.png' class='close_iframer' width="45px" style='position:relative; right: 10px; float:right;top:58px; cursor:pointer;'>
        <span class='iframe_title' style='color:white; font-weight:bold; position: relative; left: 20px; font-size: 30px;'></span>
        <iframe id="iframe_instead_of_window" style='width: 100%;' height="1000px; border:0;" src=""></iframe>
    </div>
    <div class="row hide_on_iframe">
		<div class="col-md-12">

        <h1 class="">Match Dashboard
        <?php
        if(config_visible_function($dbc, 'match') == 1) {
            //echo '<a href="field_config.php" class="mobile-block pull-right "><img style="width: 50px;" title="Match Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>';
        }
        ?>
        </h1>

        <div class="notice double-gap-bottom popover-examples">
            <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
            <div class="col-sm-11"><span class="notice-name">NOTE:</span>
            The matching tile allows staff to be matched to specific contact/customer information for a set period of time. The match system allows staff to view and have full access to specific contacts/customer information based on the timeline set. Once the match expiry time has passed, staff will no longer have access to the matched contact/customer information.</div>
            <div class="clearfix"></div>
        </div>

	    <form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

                <?php
                $search_vendor = '';
                $search_type = '';
                $search_category = '';
                if(isset($_POST['search_user_submit'])) {
                    $search_vendor = $_POST['search_vendor'];
                    $search_type = $_POST['search_type'];
                    $search_category = $_POST['search_category'];
                }
                if (isset($_POST['display_all_inventory'])) {
                    $search_vendor = '';
                    $search_type = '';
                    $search_category = '';
                }
                ?>

                <?php if(vuaed_visible_function($dbc, 'match') == 1) {
                    echo '<a href="add_match.php" class="btn brand-btn mobile-block pull-right">Add Match</a>';
                } ?>

            <br><br><div id="no-more-tables">
            <span class="pull-right">
                <img src="<?php echo WEBSITE_URL;?>/img/block/red.png" width="23" height="23" border="0" alt=""> Suspended
                <img src="<?php echo WEBSITE_URL;?>/img/block/green.png" width="23" height="23" border="0" alt=""> Active
            </span><br><br>
            <?php
            if($search_type != '') {
                $query_check_credentials = "SELECT * FROM medication WHERE deleted = 0 AND medication_type ='$search_type'";
            } else if($search_category != '') {
                $query_check_credentials = "SELECT * FROM medication WHERE deleted = 0 AND category ='$search_category'";
            } else if($search_vendor != '') {
                $query_check_credentials = "SELECT * FROM medication WHERE deleted = 0 AND medication_code LIKE '%" . $search_vendor . "%' OR medication_type LIKE '%" . $search_vendor . "%' OR category LIKE '%" . $search_vendor . "%' OR heading LIKE '%" . $search_vendor . "%' OR name LIKE '%" . $search_vendor . "%' OR title LIKE '%" . $search_vendor . "%' OR fee LIKE '%" . $search_vendor . "%'";
            } else {
                $query_check_credentials = "SELECT * FROM match_contact WHERE deleted = 0";
            }

            if ($level_url != 'admin') {
                $query_check_credentials .= " AND find_in_set($contacterid, support_contact) <> 0 OR find_in_set($contacterid, staff_contact) <> 0";
            }

            $result = mysqli_query($dbc, $query_check_credentials);

            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT medication_dashboard FROM field_config"));
                $value_config = ','.$get_field_config['medication_dashboard'].',';

                echo "<table class='table table-bordered'>";
                echo "<tr class='hidden-xs hidden-sm'>";
                    echo '<th>Staff</th>';
                    echo '<th>Contacts</th>';
                    echo '<th>Timeline</th>';
                    echo '<th>Follow Up</th>';
                    echo '<th>End Date</th>';
                    echo '<th>Status</th>';
                    echo '<th>History</th>';
                    echo '<th>Function</th>';
                    echo "</tr>";
            } else {
                echo "<h2>No Record Found.</h2>";
            }

            $curr_date = date("Y-m-d");

            while($row = mysqli_fetch_array( $result ))
            {
                if(!empty($row['end_date']) && $curr_date > $row['end_date'] && $row['status'] != 'Suspend') {
                    $row['status'] = 'Suspend';
                    $history = 'Match passed end date and was automatically suspended on '.date('Y-m-d H:i:s').'<br>';
                    $query_update_status = "UPDATE match_contact SET status = 'Suspend', history = CONCAT(IFNULL(history, ''), '$history') WHERE matchid = ". $row['matchid'];
                    $result_update_status = mysqli_query($dbc, $query_update_status);
                }

                $style = '';
                if($row['status'] == 'Active') {
                    $style = 'style="color: green;"';
                }
                if($row['status'] == 'Suspend') {
                    $style = 'style="color: red;"';
                }
                echo "<tr $style>";
                $matchid = $row['matchid'];

                $staff_contacts_arr = explode(',', $row['staff_contact']);
                $staff_contacts = [];
                foreach($staff_contacts_arr as $value){
                    array_push($staff_contacts, get_staff($dbc, $value).''.get_client($dbc, $value));
                }
                echo '<td data-title="Staff">' . implode(', ', $staff_contacts) . '</td>';

                $support_contacts_arr = explode(',', $row['support_contact']);
                $support_contacts = [];
                foreach($support_contacts_arr as $value){
                    array_push($support_contacts, get_staff($dbc, $value).''.get_client($dbc, $value));
                }
                echo '<td data-title="Contacts">' . implode(', ', $support_contacts) . '</td>';

                echo '<td data-title="Timeline">'. $row['match_date']. '</td>';
                echo '<td data-title="Follow Up">'. $row['follow_up_date']. '</td>';
                echo '<td data-title="End Date">'. $row['end_date']. '</td>';

                if($row['status'] == 'Active') {
                    echo '<td data-title="Status">Active | <a href="match.php?matchid='. $row['matchid']. '&status=1">Suspend</a></td>';
                } else if($row['status'] == 'Suspend') {
                    echo '<td data-title="Status"><a href="match.php?matchid='. $row['matchid']. '&status=0">Active</a> | Suspend</td>';
                } else {
                    echo '<td data-title="Status"><a href="match.php?matchid='. $row['matchid']. '&status=0">Active</a> | <a href="match.php?matchid='. $row['matchid']. '&status=1">Suspend</a></td>';
                }

                echo '<td data-title="History">';
                echo '<span class="iframe_open" id="'.$row['matchid'].'"style="cursor:pointer">View All</span></td>';

                echo '<td data-title="Function">';
                if(vuaed_visible_function($dbc, 'match') == 1) {
                echo '<a href=\'add_match.php?matchid='.$matchid.'\'>Edit</a> | ';
				echo '<a href=\''.WEBSITE_URL.'/delete_restore.php?action=delete&medicationid='.$matchid.'\' onclick="return confirm(\'Are you sure?\')">Archive</a>';
                }
                echo '</td>';

                echo "</tr>";
            }

            echo '</table></div>';
            if(vuaed_visible_function($dbc, 'match') == 1) {
                echo '<a href="add_match.php" class="btn brand-btn mobile-block pull-right">Add Match</a>';
            }

            ?>
        </form>

        </div>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
