<?php
/*
Saleser Listing
*/
include ('../include.php');
error_reporting(0);
?>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('sales');
?>

<div class="container triple-pad-bottom">
    <div class="row">
		<div class="col-md-12">

		<div class="col-sm-10"><h1>Lead Source</h1></div>
		<div class="col-sm-2 gap-top"><?php
			if ( config_visible_function ( $dbc, 'sales' ) == 1 ) {
				echo '<a href="field_config_sales.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
				echo '<span class="popover-examples list-inline pull-right" style="margin:15px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
			} ?>
		</div>
		<div class="clearfix gap-bottom"></div>

        <?php
			echo '<div class="mobile-100-container">';
				if ( check_subtab_persmission($dbc, 'sales', ROLE, 'how_to_guide') === TRUE ) {
					$result     = get_how_to_guide( $dbc, 'Sales'); // $dbc, $tile_name
                    $num_rows   = mysqli_num_rows($result);
                    if ( $num_rows > 0 ) {
                        echo "<a href='how_to_guide.php'><button type='button' class='btn brand-btn mobile-block mobile-100'>How to Guide</button></a>&nbsp;&nbsp;";
                    } else {
                         echo "<a href='lead_status_definitions.php'><button type='button' class='btn brand-btn mobile-block mobile-100'>How to Guide</button></a>&nbsp;&nbsp;";
                    }
				} else {
					echo "<button type='button' class='btn disabled-btn mobile-block mobile-100'>How to Guide</button>&nbsp;&nbsp;";
				}

				if ( check_subtab_persmission($dbc, 'sales', ROLE, 'sales_pipeline') === TRUE ) {
					echo "<a href='sales_pipeline.php?status='><button type='button' class='btn brand-btn mobile-block mobile-100'>Sales Pipeline</button></a>&nbsp;&nbsp;";
				} else {
					echo "<button type='button' class='btn disabled-btn mobile-block mobile-100'>Sales Pipeline</button>&nbsp;&nbsp;";
				}

				if ( check_subtab_persmission($dbc, 'sales', ROLE, 'schedule') === TRUE ) {
					echo "<a href='sales.php'><button type='button' class='btn brand-btn mobile-block mobile-100'>Schedule</button></a>&nbsp;&nbsp;";
				} else {
					echo "<button type='button' class='btn disabled-btn mobile-block mobile-100'>Schedule</button>&nbsp;&nbsp;";
				}

				if ( check_subtab_persmission($dbc, 'sales', ROLE, 'reports') === TRUE ) {
					echo "<a href='sales_lead_source_report.php'><button type='button' class='btn brand-btn mobile-block active_tab mobile-100'>Reports</button></a>&nbsp;&nbsp;";
				} else {
					echo "<button type='button' class='btn disabled-btn mobile-block mobile-100'>Reports</button>&nbsp;&nbsp;";
				}

				echo '<br /><br />';

				echo "<a href='sales_lead_source_report.php?type=custom'><button type='button' class='btn brand-btn mobile-block mobile-100'>Lead Source Report</button></a>&nbsp;&nbsp;";
				echo "<a href='sales_next_action_report.php'><button type='button' class='btn brand-btn mobile-block mobile-100'>Next Action Report</button></a>&nbsp;&nbsp;";
				echo "<a href='sales_total_leads.php'><button type='button' class='btn brand-btn mobile-block mobile-100'>Leads Added to Pipeline</button></a>&nbsp;&nbsp;";
				echo "<a href='sales_total_won_lost.php'><button type='button' class='btn brand-btn mobile-block mobile-100'>Total Won/Lost</button></a>&nbsp;&nbsp;";
				echo "<a href='sales_pipeline_review.php'><button type='button' class='btn brand-btn mobile-block mobile-100 active_tab'>Pipeline Review</button></a>&nbsp;&nbsp;";
			echo '</div>';
        ?>

		<div class="notice double-gap-bottom double-gap-top popover-examples">
			<div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
			<div class="col-sm-11"><span class="notice-name">NOTE:</span>
			Reporting is essential for all sales software; what's key about this software is that it reports in real time. As sales staff work through the process, reporting will automatically provide the resources you need to properly manage yourself and any team. Custom reports are available; please request through the support tile.</div>
			<div class="clearfix"></div>
		</div>

        <form name="form_sites" method="post" action="" class="form-inline" role="form">
            <div class="pad-top pad-bottom clearfix">
                <?php
                if(vuaed_visible_function($dbc, 'sales') == 1) {
                    echo '<a href="add_sales.php" class="btn brand-btn mobile-block pull-right">Add Sales</a>';
					echo '<span class="popover-examples list-inline pull-right" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Add sales lead details here."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
                }
                ?>
            </div>

           <?php
            $starttime = date('Y-m-d');
            $endtime = date('Y-m-d');
			$search_user = '';
			$search_business = '';
            if (isset($_POST['search_email_submit'])) {
                $starttime = $_POST['starttime'];
                $endtime = $_POST['endtime'];
                $search_user = $_POST['search_user'];
                $search_business = $_POST['search_business'];
            }

            if($starttime == 0000-00-00) {
                $starttime = date('Y-m-d');
            }

            if($endtime == 0000-00-00) {
                $endtime = date('Y-m-d');
            }
            ?>
            <div class="col-sm-5 form-group">
				<label for="site_name" class="col-sm-4 control-label">Staff:</label>
				<div class="col-sm-8">
					  <select data-placeholder="Select Staff" name="search_user" class="chosen-select-deselect form-control" width="380">
					  <option value=""></option>
					  <?php
						$query = mysqli_query($dbc,"SELECT DISTINCT `lead_created_by` FROM `sales` ORDER BY `lead_created_by`");
						while($row = mysqli_fetch_array($query)) {
						?><option <?php if ($row['lead_created_by'] == $search_user) { echo " selected"; } ?> value='<?php echo  $row['lead_created_by']; ?>' ><?php echo $row['lead_created_by']; ?></option>
					<?php	}
					?>
					</select>
				</div>
            </div>

            <div class="col-sm-5 form-group">
				<label for="site_name" class="col-sm-4 control-label">Business:</label>
				<div class="col-sm-8">
					  <select data-placeholder="Select a Business" name="search_business" class="chosen-select-deselect form-control" width="380">
					  <option value=""></option>
					  <?php
						$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT `contacts`.`contactid`, `contacts`.`name` FROM `sales` LEFT JOIN `contacts` ON `sales`.`businessid`=`contacts`.`businessid` GROUP BY `sales`.`businessid`, `contacts`.`name`"),MYSQLI_ASSOC));
						foreach($query as $row) {
						?><option <?php if ($row == $search_business) { echo " selected"; } ?> value='<?php echo  $row; ?>' ><?php echo get_client($dbc, $row); ?></option>
					<?php	}
					?>
					</select>
				</div>
            </div>

            <div class="col-sm-5 form-group">
                <label for="site_name" class="col-sm-4 control-label">From:</label>
                <div class="col-sm-8">
                    <input name="starttime" type="text" class="datepicker" value="<?php echo $starttime; ?>">
                </div>
            </div>

            <div class="col-sm-5 form-group until">
                <label for="site_name" class="col-sm-4 control-label">Until:</label>
                <div class="col-sm-8">
                    <input name="endtime" type="text" class="datepicker" value="<?php echo $endtime; ?>">&nbsp;
                </div>
            </div>

            &nbsp;<button type="submit" name="search_email_submit" value="Search" class="btn brand-btn mobile-block">Submit</button>

            <div id="no-more-tables">
            <?php

            $query_check_credentials = "SELECT * FROM sales WHERE (DATE(created_date) >= '".$starttime."' AND DATE(created_date) <= '".$endtime."')";
			if($search_user != '') {
				$query_check_credentials .= " AND `lead_created_by`='$search_user'";
			}
			if($search_business > 0) {
				$query_check_credentials .= " AND `businessid`='$search_business'";
			}

            $result = mysqli_query($dbc, $query_check_credentials);

            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {
                echo "<table class='table table-bordered'>";
			    echo "<tr class='hidden-xs hidden-sm'>";
                    echo '<th>Lead#</th>';
                    echo '<th>Business</th>';
                    echo '<th>Contact</th>';
                    echo '<th>Phone</th>';
                    echo '<th>Email</th>';
                    echo '<th>Next Action</th>';
                    echo '<th>Reminder</th>';
                    echo '<th>Status</th>';
                echo "</tr>";
            } else {
                echo "<h2>No Record Found.</h2>";
            }
            $total_won = 0;
            $total_lost = 0;
            while($row = mysqli_fetch_array( $result ))
            {
                echo "<tr>";
                echo '<td data-title="Lead#">' . $row['salesid'] . '</td>';
                echo '<td data-title="Business">' . get_contact($dbc, $row['businessid'], 'name') . '</td>';
                echo '<td data-title="Contact Name">' . get_contact($dbc, $row['contactid'], 'first_name').' '.get_contact($dbc, $row['contactid'], 'last_name') . '</td>';
                echo '<td data-title="Primary Phone">' . $row['primary_number'] . '</td>';
                echo '<td data-title="Email">' . $row['email_address'] . '</td>';
                echo '<td data-title="Next Action">' . $row['next_action'] . '</td>';
                echo '<td data-title="Reminder">' . $row['new_reminder'] . '</td>';

                echo '<td data-title="Reminder">' . $row['status'] . '</td>';

                echo "</tr>";
            }

            echo '</table></div>';
            if(vuaed_visible_function($dbc, 'sales') == 1) {
				echo '<a href="add_sales.php" class="btn brand-btn mobile-block pull-right">Add Sales</a>';
				echo '<span class="popover-examples list-inline pull-right" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Add sales lead details here."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
            }

            ?>
		
        </form>

        </div>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>