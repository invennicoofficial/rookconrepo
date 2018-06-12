<?php
/*
Saleser Listing
*/
include ('../include.php');
?>
</head>
<script type="text/javascript">
$(document).on('change', 'select[name="search_user"]', function() { submitForm(); });
$(document).on('change', 'select[name="search_client"]', function() { submitForm(); });
</script>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('calllog');
?>

<div class="container triple-pad-bottom">
    <div class="row">
		<div class="col-md-12">

        <?php
			if(config_visible_function($dbc, 'sales') == 1) {
				echo '<a href="field_config_sales.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
				echo '<span class="popover-examples list-inline pull-right" style="margin:15px 10px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
			}
		?>
		
		<h1 class="single-pad-bottom">Lead Source</h1>
		<div class="double-gap-bottom"><a href="sales.php" class="btn config-btn">Back to Dashboard</a></div>
        
		<?php
			$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT sales FROM field_config"));
			$value_config = ','.$get_field_config['sales'].',';

			echo "<a href='sales_funnel.php'><button type='button' class='btn brand-btn mobile-block mobile-100'>Sales Funnel</button></a>&nbsp;&nbsp;";
			if (strpos($value_config, ','."Today".',') !== FALSE) {
				echo "<a href='sales.php?type=today'><button type='button' class='btn brand-btn mobile-block '>Today</button></a>&nbsp;&nbsp;";
			}
			if (strpos($value_config, ','."This Week".',') !== FALSE) {
				echo "<a href='sales.php?type=week'><button type='button' class='btn brand-btn mobile-block '>This Week</button></a>&nbsp;&nbsp;";
			}
			if (strpos($value_config, ','."This Month".',') !== FALSE) {
				echo "<a href='sales.php?type=month'><button type='button' class='btn brand-btn mobile-block '>This Month</button></a>&nbsp;&nbsp;";
			}
			if (strpos($value_config, ','."Custom".',') !== FALSE) {
				echo "<a href='sales.php?type=custom'><button type='button' class='btn brand-btn mobile-block '>Custom</button></a>&nbsp;&nbsp;";
			}

			echo "<a href='sales_lead_source_report.php'><button type='button' class='btn brand-btn mobile-block active_tab'>Reports</button></a>&nbsp;&nbsp;";

			echo '<br>';

			echo "<a href='sales_lead_source_report.php?type=custom'><button type='button' class='btn brand-btn mobile-block'>Lead Source Report</button></a>&nbsp;&nbsp;";
			echo "<a href='sales_next_action_report.php'><button type='button' class='btn brand-btn mobile-block active_tab'>Next Action Report</button></a>&nbsp;&nbsp;";
			echo "<a href='sales_total_leads.php'><button type='button' class='btn brand-btn mobile-block'>Leads Added to Pipeline</button></a>&nbsp;&nbsp;";
			echo "<a href='sales_total_won_lost.php'><button type='button' class='btn brand-btn mobile-block'>Total Won/Lost</button></a>&nbsp;&nbsp;";
			echo "<a href='sales_pipeline_review.php'><button type='button' class='btn brand-btn mobile-block'>Pipeline Review</button></a>&nbsp;&nbsp;";
        ?>
        

        <form name="form_sites" method="post" action="" class="form-inline" role="form">
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
              <label for="site_name" class="col-sm-4 control-label">Search By Staff:</label>
              <div class="col-sm-8">
                  <select data-placeholder="Pick a User" name="search_user" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
				  <?php
					$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 AND `status`=1"),MYSQLI_ASSOC));
					foreach($query as $id) {
						$selected = '';
						$selected = $id == $search_user ? 'selected = "selected"' : '';
						echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
					}
				  ?>

                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="site_name" class="col-sm-4 control-label">Search By Business:</label>
              <div class="col-sm-8">
                  <select data-placeholder="Pick a Client" name="search_client" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <?php
                    $query = mysqli_query($dbc,"SELECT distinct(c.name),c.contactid FROM contacts c, sales t WHERE t.businessid=c.contactid order by name");
                    while($row = mysqli_fetch_array($query)) {
                    ?><option <?php if ($row['contactid'] == $search_client) { echo " selected"; } ?> value='<?php echo  $row['contactid']; ?>' ><?php echo decryptIt($row['name']); ?></option>
                <?php	} ?>
                </select>
              </div>
            </div>

            <div class="form-group">
                <!--<button type="submit" name="search_user_submit" value="Search" class="btn brand-btn mobile-block">Search</button>-->
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
                    echo '<th>Lead Source</th>';
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
