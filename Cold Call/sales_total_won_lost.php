<?php
/*
Cold Caller Listing
*/
include ('../include.php');
?>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('calllog');
?>

<div class="container triple-pad-bottom">
    <div class="row">
		<div class="col-md-12">

		<?php
        if(config_visible_function($dbc, 'call log') == 1) {
            echo '<a href="field_config_call log.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
			echo '<span class="popover-examples list-inline pull-right" style="margin:15px 10px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
        }
		?>

        <h1 class="single-pad-bottom">Lead Source</h1>
		<div class="double-gap-bottom"><a href="call log.php" class="btn config-btn">Back to Dashboard</a></div>

		<?php
        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT call log FROM field_config"));
        $value_config = ','.$get_field_config['call log'].',';

        echo "<a href='call log_funnel.php'><button type='button' class='btn brand-btn mobile-block mobile-100'>Cold Call Funnel</button></a>&nbsp;&nbsp;";
		if (strpos($value_config, ','."Today".',') !== FALSE) {
            echo "<a href='call log.php?type=today'><button type='button' class='btn brand-btn mobile-block '>Today</button></a>&nbsp;&nbsp;";
        }
        if (strpos($value_config, ','."This Week".',') !== FALSE) {
            echo "<a href='call log.php?type=week'><button type='button' class='btn brand-btn mobile-block '>This Week</button></a>&nbsp;&nbsp;";
        }
        if (strpos($value_config, ','."This Month".',') !== FALSE) {
            echo "<a href='call log.php?type=month'><button type='button' class='btn brand-btn mobile-block '>This Month</button></a>&nbsp;&nbsp;";
        }
        if (strpos($value_config, ','."Custom".',') !== FALSE) {
            echo "<a href='call log.php?type=custom'><button type='button' class='btn brand-btn mobile-block '>Custom</button></a>&nbsp;&nbsp;";
        }

        echo "<a href='call log_lead_source_report.php'><button type='button' class='btn brand-btn mobile-block active_tab'>Reports</button></a>&nbsp;&nbsp;";

        echo '<br>';

        echo "<a href='call log_lead_source_report.php?type=custom'><button type='button' class='btn brand-btn mobile-block'>Lead Source Report</button></a>&nbsp;&nbsp;";
        echo "<a href='call log_next_action_report.php'><button type='button' class='btn brand-btn mobile-block'>Next Action Report</button></a>&nbsp;&nbsp;";

        echo "<a href='call log_total_leads.php'><button type='button' class='btn brand-btn mobile-block'>Leads Added to Pipeline</button></a>&nbsp;&nbsp;";
        echo "<a href='call log_total_won_lost.php'><button type='button' class='btn brand-btn mobile-block active_tab'>Total Won/Lost</button></a>&nbsp;&nbsp;";
        echo "<a href='call log_pipeline_review.php'><button type='button' class='btn brand-btn mobile-block'>Pipeline Review</button></a>&nbsp;&nbsp;";
        ?>


        <form name="form_sites" method="post" action="" class="form-inline" role="form">
            <div class="pad-top pad-bottom clearfix">
                <?php
                if(vuaed_visible_function($dbc, 'call log') == 1) {
                    echo '<a href="add_call log.php" class="btn brand-btn mobile-block pull-right">Add Cold Call</a>';
					echo '<span class="popover-examples list-inline pull-right" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Add call log lead details here."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
                }
                ?>
            </div>

           <?php
            $starttime = date('Y-m-d');
            $endtime = date('Y-m-d');
            if (isset($_POST['search_email_submit'])) {
                $starttime = $_POST['starttime'];
                $endtime = $_POST['endtime'];
            }

            if($starttime == 0000-00-00) {
                $starttime = date('Y-m-d');
            }

            if($endtime == 0000-00-00) {
                $endtime = date('Y-m-d');
            }
            ?>
            <div class="form-group">
                <label for="site_name" class="col-sm-4 control-label">From:</label>
                <div class="col-sm-8">
                    <input name="starttime" type="text" class="datepicker" value="<?php echo $starttime; ?>">
                </div>
            </div>

            <div class="form-group until">
                <label for="site_name" class="col-sm-4 control-label">Until:</label>
                <div class="col-sm-8" style="width:auto">
                    <input name="endtime" type="text" class="datepicker" value="<?php echo $endtime; ?>"></p>
                </div>
            </div>

            <button type="submit" name="search_email_submit" value="Search" class="btn brand-btn mobile-block">Submit</button>
            <br>

            <div id="no-more-tables">
            <?php

            $query_check_credentials = "SELECT * FROM call log WHERE status != 'Pending' AND (DATE(created_date) >= '".$starttime."' AND DATE(created_date) <= '".$endtime."')";

            $result = mysqli_query($dbc, $query_check_credentials);

            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {
                echo "<table class='table table-bordered'>";
			    echo "<tr class='hidden-xs hidden-sm'>";
                    echo '<th>Cold Call Person</th>
                    <th>Business</th>
                    <th>Contact</th>
                    <th>Lead Value</th>
                    <th>Status</th>
                    ';
                echo "</tr>";
            } else {
                echo "<h2>No Record Found.</h2>";
            }
            $total_won = 0;
            $total_lost = 0;
            while($row = mysqli_fetch_array( $result ))
            {
                echo "<tr>";
                echo '<td data-title="Business">' . get_staff($dbc, $row['primary_staff']) . '</td>';
                echo '<td data-title="Business">' . get_contact($dbc, $row['businessid'], 'name') . '</td>';
                echo '<td data-title="Contact Name">' . get_contact($dbc, $row['contactid'], 'first_name').' '.get_contact($dbc, $row['contactid'], 'last_name') . '</td>';
                echo '<td data-title="Primary Phone">' . $row['lead_value'] . '</td>';
                echo '<td data-title="Primary Phone">' . $row['status'] . '</td>';
                echo "</tr>";
                if($row['status'] == 'Won') {
                    $total_won++;
                }
                if($row['status'] == 'Lost') {
                    $total_lost++;
                }
            }

            echo '</table></div>';
            if(vuaed_visible_function($dbc, 'call log') == 1) {
				echo '<a href="add_call log.php" class="btn brand-btn mobile-block pull-right">Add Cold Call</a>';
				echo '<span class="popover-examples list-inline pull-right" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Add call log lead details here."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
            }

            ?>
        </form>

        </div>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
