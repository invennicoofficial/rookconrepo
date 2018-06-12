<?php
/*
Saleser Listing
*/
include ('../include.php');
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
				echo "<a href='sales_next_action_report.php'><button type='button' class='btn brand-btn mobile-block mobile-100 active_tab'>Next Action Report</button></a>&nbsp;&nbsp;";
				echo "<a href='sales_total_leads.php'><button type='button' class='btn brand-btn mobile-block mobile-100'>Leads Added to Pipeline</button></a>&nbsp;&nbsp;";
				echo "<a href='sales_total_won_lost.php'><button type='button' class='btn brand-btn mobile-block mobile-100'>Total Won/Lost</button></a>&nbsp;&nbsp;";
				echo "<a href='sales_pipeline_review.php'><button type='button' class='btn brand-btn mobile-block mobile-100'>Pipeline Review</button></a>&nbsp;&nbsp;";
			echo '</div>';
        ?>

		<div class="notice double-gap-bottom double-gap-top popover-examples">
			<div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
			<div class="col-sm-11"><span class="notice-name">NOTE:</span>
			Reporting is essential for all sales software; what's key about this software is that it reports in real time. As sales staff work through the process, reporting will automatically provide the resources you need to properly manage yourself and any team. Custom reports are available; please request through the support tile.</div>
			<div class="clearfix"></div>
		</div>

        <form name="form_sites" method="post" action="" class="form-horizontal" role="form">
            <div class="pad-top pad-bottom clearfix">
                <?php
                if(vuaed_visible_function($dbc, 'sales') == 1) {
                    echo '<a href="add_sales.php" class="btn brand-btn mobile-block pull-right">Add Sales</a>';
					echo '<span class="popover-examples list-inline pull-right" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Add sales lead details here."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
                }

                if(isset($_POST['search_user_submit'])) {
                    $search_user = $_POST['search_user'];
                    $search_client = $_POST['search_client'];
                } else {
                    $search_user = '';
                    $search_client = '';
                }
                if (isset($_POST['display_all_inventory'])) {
                    $search_user = '';
                    $search_client = '';
                }
                ?>
            </div>

            <div class="form-group">
                <label for="site_name" class="col-sm-4 control-label">Staff:</label>
                <div class="col-sm-4" style="width:25%">
                      <select data-placeholder="Select a User" name="search_user" class="chosen-select-deselect form-control" width="380">
                      <option value=""></option>
                      <?php
                        $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE deleted=0 AND category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND status = 1 ORDER BY last_name");

                        $contact_list = [];

                        while($row = mysqli_fetch_array($query)) {
                            $contact_list[$row['contactid']] = decryptIt($row['first_name']) . ' ' . decryptIt($row['last_name']);
                        }
                        asort($contact_list);

                        foreach($contact_list as $key => $value) {
                        ?><option <?php if ($key == $search_user) { echo " selected"; } ?> value='<?php echo  $key; ?>' ><?php echo $value; ?></option>
                    <?php   }
                    ?>
                    </select>
                </div>
                <button type="submit" name="search_user_submit" value="Search" class="btn brand-btn mobile-block">Search</button>
                <button type="submit" name="display_all_inventory" value="Display All" class="btn brand-btn mobile-block">Display All</button>
            </div>

            <div id="no-more-tables">
            <?php

            if($search_user != '') {
                $query_check_credentials = "SELECT COUNT(salesid) AS total_source, next_action FROM sales WHERE status!='Lost' AND primary_staff = '$search_user' GROUP BY next_action";
            } elseif($search_client != '') {
                $query_check_credentials = "SELECT COUNT(salesid) AS total_source, next_action FROM sales WHERE status!='Lost' AND businessid = '$search_client' GROUP BY next_action";
            } else {
                $query_check_credentials = "SELECT COUNT(salesid) AS total_source, next_action FROM sales WHERE status!='Lost' GROUP BY next_action";
            }

            $result = mysqli_query($dbc, $query_check_credentials);

            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {
                echo "<table class='table table-bordered'>";
			    echo "<tr class='hidden-xs hidden-sm'>";
                    echo '<th>Next Action</th>';
                    echo '<th>Total</th>';
                echo "</tr>";
            } else {
                echo "<h2>No Record Found.</h2>";
            }

            while($row = mysqli_fetch_array( $result ))
            {
                echo "<tr>";
                echo '<td data-title="Lead#">' . $row['next_action'] . '</td>';
                echo '<td data-title="Primary Phone">' . $row['total_source'] . '</td>';
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
