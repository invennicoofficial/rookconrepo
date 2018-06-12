<?php
/*
 * Sale Pipeline
*/
include ('../include.php');
?>
<script type="text/javascript">
$(document).on('change', 'select[name="next_action[]"]', function() { selectAction(this); });
$(document).on('change', 'select[name="status[]"]', function() { selectStatus(this); });
</script>
</head>

<body>
<?php
	include_once ('../navigation.php');
checkAuthorised('sales');
?>

<div class="container triple-pad-bottom">
    <div class="row">
		<div class="col-md-12">
		
        <div class="col-sm-10"><h1>Sales Dashboard</h1></div>
		<div class="col-sm-2 gap-top"><?php
			if ( config_visible_function ( $dbc, 'sales' ) == 1 ) {
				echo '<a href="field_config_sales.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
				echo '<span class="popover-examples list-inline pull-right" style="margin:15px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
			} ?>
		</div>
		<div class="clearfix gap-bottom"></div>
		
        <?php		
			echo '<div class="mobile-100-container tab-container">';
				$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT sales FROM field_config"));
				$value_config = ','.$get_field_config['sales'].',';
				
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
					echo "<a href='sales_pipeline.php?status='><button type='button' class='btn brand-btn mobile-block mobile-100 active_tab'>Sales Pipeline</button></a>&nbsp;&nbsp;";
				} else {
					echo "<button type='button' class='btn disabled-btn mobile-block mobile-100'>Sales Pipeline</button>&nbsp;&nbsp;";
				}
						
				if ( check_subtab_persmission($dbc, 'sales', ROLE, 'schedule') === TRUE ) {
					echo "<a href='sales.php'><button type='button' class='btn brand-btn mobile-block mobile-100'>Schedule</button></a>&nbsp;&nbsp;";
				} else {
					echo "<button type='button' class='btn disabled-btn mobile-block mobile-100'>Schedule</button>&nbsp;&nbsp;";
				}
				
				if ( check_subtab_persmission($dbc, 'sales', ROLE, 'reports') === TRUE ) {
					echo "<a href='sales_lead_source_report.php'><button type='button' class='btn brand-btn mobile-block mobile-100'>Reports</button></a>&nbsp;&nbsp;";
				} else {
					echo "<button type='button' class='btn disabled-btn mobile-block mobile-100'>Reports</button>&nbsp;&nbsp;";
				}
				
				// Get Lead Statuses added in Settings->Lead Status accordion
				$statuses		= get_config ( $dbc, 'sales_lead_status' );
				$each_status	= explode ( ',', $statuses );
				$count			= count( $each_status );
				
				if ( empty($_GET['status']) || !in_array(trim($_GET['status']),$each_status) ) {
					$_GET[ 'status' ] = $each_status[0];
				}
				
				echo '</div>';
				echo "<div class='tab-container1'>";
				if ( $count > 0 ) {
					for ( $i=0; $i<$count; $i++ ) {
						$active_tab	= ( trim ( $_GET[ 'status' ] == $each_status[$i] ) ) ? 'active_tab' : 'boo';
						if ( strpos ( $value_config, ',' . $each_status[$i] . ',' ) !== FALSE ) {
							echo '<a href="sales_pipeline.php?status=' . $each_status[$i] . '"><button type="button" class="btn brand-btn mobile-block mobile-100 ' . $active_tab . '">' . $each_status[$i] . '</button></a>&nbsp;&nbsp;';
						}
					}
				}
			echo '</div>';
        ?>
		
		<div class="notice double-gap-bottom double-gap-top popover-examples">
			<div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
			<div class="col-sm-11"><span class="notice-name">NOTE:</span>
			Organizing a proper sales pipeline is an essential means of tracking, coordinating and communicating clearly between sales staff, customers and management. Track and organize next action and statuses by sales lead in this section to keep yourself organized.</div>
			<div class="clearfix"></div>
		</div>

        <form name="form_sites" method="post" action="" class="form-inline" role="form">
            <div class="pad-top pad-bottom clearfix">
                <?php
                if(vuaed_visible_function($dbc, 'sales') == 1) {
					echo '<div class="mobile-100-container" style="left:-8px;position:relative;">';
						echo '<a href="add_sales.php" class="btn brand-btn mobile-block pull-right mobile-100-pull-right">Add Sales</a>';
						echo '<span class="popover-examples list-inline pull-right" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Add sales lead details here."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
					echo '</div>';
                }
                $search_client = '';
                $search_contact = '';
                $search_action = '';
                $search_status = '';
                if(isset($_POST['search_user_submit'])) {
                    $search_client = $_POST['search_client'];
                    $search_contact = $_POST['search_contact'];
                    $search_action = $_POST['search_action'];
                    $search_status = $_POST['search_status'];
                }
                if (isset($_POST['display_all_inventory'])) {
                    $search_client = '';
                    $search_contact = '';
                    $search_action = '';
                    $search_status = '';
                }
                ?>
            </div>
			
				<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4" style='max-width:200px;'>
                <label for="search_site" style='width:100%; text-align:center;'>By Business:</label>
				</div>
                <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                <select data-placeholder="Select a Business" name="search_client" class="chosen-select-deselect form-control">
                  <option value=""></option>
                  <?php
                    $query = mysqli_query($dbc,"SELECT DISTINCT(c.name), t.businessid FROM contacts c, sales t WHERE t.businessid=c.contactid order by c.name");
                    while($row = mysqli_fetch_array($query)) {
                    ?><option <?php if ($row['businessid'] == $search_client) { echo " selected"; } ?> value='<?php echo  $row['businessid']; ?>' ><?php echo decryptIt($row['name']); ?></option>
                <?php	} ?>
                </select>
				</div>
                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4" style='max-width:200px;'>
                <label for="search_site" style='width:100%; text-align: center;'>By Contact:</label>
				</div>
				<div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                <select data-placeholder="Select a Contact" name="search_contact" class="chosen-select-deselect form-control">
                  <option value=""></option>
                  <?php
                    $query = mysqli_query($dbc,"SELECT DISTINCT(c.contactid), c.first_name, c.last_name, t.contactid FROM contacts c, sales t WHERE t.contactid=c.contactid order by c.first_name");
                    while($row = mysqli_fetch_array($query)) {
                    ?><option <?php if ($row['contactid'] == $search_contact) { echo " selected"; } ?> value='<?php echo  $row['contactid']; ?>' ><?php echo decryptIt($row['first_name']).' '.decryptIt($row['last_name']); ?></option>
                <?php	} ?>
                </select>
				</div><div class="clearfix top-marg-mobile">
				</div>
				<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4" style='max-width:200px;'>
                <label for="search_site" style='width:100%; text-align: center;'>By Action:</label>
				</div>
                <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                <select data-placeholder="Select Next Action" name="search_action" class="chosen-select-deselect form-control" width="380">
					<option value=""></option>
					<!--
					<option <?php //if ($search_action == "Email") { echo " selected"; } ?> value="Email">Email</option>
					<option <?php //if ($search_action == "Follow Up") { echo " selected"; } ?> value="Follow Up">Follow Up</option>
					<option <?php //if ($search_action == "Phone Call") { echo " selected"; } ?> value="Phone Call">Phone Call</option>
					<option <?php //if ($search_action == "Initial Meeting") { echo " selected"; } ?> value="Initial Meeting">Initial Meeting</option>
					<option <?php //if ($search_action == "Meeting") { echo " selected"; } ?> value="Meeting">Meeting</option>
					<option <?php //if ($search_action == "Presentation") { echo " selected"; } ?> value="Presentation">Presentation</option>
					<option <?php //if ($search_action == "Estimate") { echo " selected"; } ?> value="Estimate">Estimate</option>
					<option <?php //if ($search_action == "Quote Sent") { echo " selected"; } ?> value="Quote Sent">Quote Sent</option>
					<option <?php //if ($search_action == "Closing Meeting") { echo " selected"; } ?> value="Closing Meeting">Closing Meeting</option>
					<option <?php //if ($search_action == "Waiting") { echo " selected"; } ?> value="Waiting">Waiting</option>
					-->
					<?php
						$tabs		= get_config ( $dbc, 'sales_next_action' );
						$each_tab	= explode ( ',', $tabs );
						
						foreach ( $each_tab as $cat_tab ) {
							$selected = ( $search_action == $cat_tab ) ? 'selected="selected"' : '';
							echo '<option ' . $selected . ' value="' . $cat_tab . '">' . $cat_tab . '</option>';
						}
					?>
                </select>
				</div>
				<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4 " style='max-width:200px;'>
                <label for="search_site" style='width:100%; text-align: center;'>By Status:</label>
				</div>
                <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                <select data-placeholder="Select a Status" name="search_status" class="chosen-select-deselect form-control" width="380">
					<option value=""></option>
					<?php
						$tabs		= get_config ( $dbc, 'sales_lead_status' );
						$each_tab	= explode ( ',', $tabs );
						foreach ( $each_tab as $cat_tab ) {
							$selected = ( $status == $cat_tab ) ? 'selected="selected"' : '';
							echo "<option " . $selected . " value='" . $cat_tab . "'>" . $cat_tab . '</option>';
						}
					?>
                </select>
				</div>
				<div class="clearfix" style='margin:10px;'>
				</div>
				<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4"></div>
				<div class="col-lg-8 col-md-7 col-sm-8 col-xs-8">
                <button type="submit" name="search_user_submit" value="Search" class="btn brand-btn mobile-block">Search</button>
				
                <button type="submit" name="display_all_inventory" value="Display All" class="btn brand-btn mobile-block">Display All</button>
				</div>
            <br><br>

            
            <?php
            /* Pagination Counting */
            $rowsPerPage = 25;
            $pageNum = 1;

            if(isset($_GET['page'])) {
                $pageNum = $_GET['page'];
            }

            $offset = ($pageNum - 1) * $rowsPerPage;
            
            $add_query = '';
            if($search_client != '') {
                $add_query = " AND `businessid`='$search_client'";
            }
            if($search_contact != '') {
                $add_query = " AND `contactid`='$search_contact'";
            }
            if($search_action != '') {
                $add_query = " AND `next_action`='$search_action'";
            }
            if($search_status != '') {
                $add_query = "OR `status`='$search_status'";
            }
            
			for ( $i=0; $i<$count; $i++ ) {
				if ( $_GET[ 'status' ] == $each_status[$i] ) {
					$query_check_credentials = "SELECT * FROM `sales` WHERE `status`='$each_status[$i]' $add_query LIMIT $offset, $rowsPerPage";
					$query = "SELECT count(*) as `numrows` FROM `sales` WHERE `status`='$each_status[$i]' $add_query";
				}
			}

            $result = mysqli_query($dbc, $query_check_credentials);

            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {
                echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT sales_dashboard FROM field_config WHERE `fieldconfigid` = 1"));
                $value_config = ','.$get_field_config['sales_dashboard'].',';

                echo "<div id='no-more-tables'><table class='table table-bordered'>";
			    echo "<tr class='hidden-xs hidden-sm'>";
                if (strpos($value_config, ','."Lead#".',') !== FALSE) {
                    echo '<th>Lead#</th>';
                }
                if (strpos($value_config, ','."Business/Contact".',') !== FALSE) {
                    echo '<th>Business/Contact</th>';
                }
                if (strpos($value_config, ','."Phone/Email".',') !== FALSE) {
                    echo '<th>Phone/Email</th>';
                }
                if (strpos($value_config, ','."Next Action".',') !== FALSE) {
                    echo '<th>Next Action</th>';
                }
                if (strpos($value_config, ','."Reminder".',') !== FALSE) {
                    echo '<th>Reminder</th>';
                }
                if (strpos($value_config, ','."Notes".',') !== FALSE) {
                    echo '<th>Notes</th>';
                }
                if (strpos($value_config, ','."Status".',') !== FALSE) {
                    echo '<th>Status</th>';
                }
                echo '<th>Function</th>';
                echo "</tr>";
            } else {
                echo "<h2>No Record Found.</h2>";
            }

            while($row = mysqli_fetch_array( $result ))
            {
                echo "<tr>";
                if (strpos($value_config, ','."Lead#".',') !== FALSE) {
                echo '<td data-title="Lead#"><a href=\'add_sales.php?salesid='.$row['salesid'].'\'>' . $row['salesid'] . '</a></td>';
                }

                if (strpos($value_config, ','."Business/Contact".',') !== FALSE) {
                echo '<td data-title="Business">' . get_contact($dbc, $row['businessid'], 'name') . '<br>';
                echo get_contact($dbc, $row['contactid'], 'first_name').' '.get_contact($dbc, $row['contactid'], 'last_name') . '</td>';
                }
                if (strpos($value_config, ','."Phone/Email".',') !== FALSE) {
                echo '<td data-title="Primary Phone">' . $row['primary_number'] . '<br>';
                echo '' . $row['email_address'] . '</td>';
                }
                if (strpos($value_config, ','."Next Action".',') !== FALSE) {
                ?>
                <td data-title="Next Action">
                    <select id="action_<?php echo $row['salesid']; ?>" data-placeholder="Select Next Action" name="next_action[]" class=" form-control chosen-select-deselect" width="380">
						<option value=""></option>
						<!--
						<option <?php //if ($row['next_action'] == "Email") { echo " selected"; } ?> value="Email">Email</option>
						<option <?php //if ($row['next_action'] == "Follow Up") { echo " selected"; } ?> value="Follow Up">Follow Up</option>
						<option <?php //if ($row['next_action'] == "Phone Call") { echo " selected"; } ?> value="Phone Call">Phone Call</option>
						<option <?php //if ($row['next_action'] == "Initial Meeting") { echo " selected"; } ?> value="Initial Meeting">Initial Meeting</option>
						<option <?php //if ($row['next_action'] == "Meeting") { echo " selected"; } ?> value="Meeting">Meeting</option>
						<option <?php //if ($row['next_action'] == "Presentation") { echo " selected"; } ?> value="Presentation">Presentation</option>
						<option <?php //if ($row['next_action'] == "Estimate") { echo " selected"; } ?> value="Estimate">Estimate</option>
						<option <?php //if ($row['next_action'] == "Quote Sent") { echo " selected"; } ?> value="Quote Sent">Quote Sent</option>
						<option <?php //if ($row['next_action'] == "Closing Meeting") { echo " selected"; } ?> value="Closing Meeting">Closing Meeting</option>
						<option <?php //if ($row['next_action'] == "Waiting") { echo " selected"; } ?> value="Waiting">Waiting</option>
						-->
						<?php
							$tabs		= get_config ( $dbc, 'sales_next_action' );
							$each_tab	= explode ( ',', $tabs );
							
							foreach ( $each_tab as $cat_tab ) {
								$selected = ( $row['next_action'] == $cat_tab ) ? 'selected="selected"' : '';
								if ( $cat_tab !== '' && $cat_tab !== NULL ) {
									echo '<option ' . $selected . ' value="' . $cat_tab . '">' . $cat_tab . '</option>';
								}
							}
						?>
                    </select>
                </td>
                <?php
                }
                if (strpos($value_config, ','."Reminder".',') !== FALSE) {
                echo '<td data-title="Reminder"><input name="new_reminder[]" type="text" id="reminder_'.$row['salesid'].'"  onchange="followupDate(this)" class="datepicker" value="'.$row['new_reminder'].'"></td>';
                }

                if (strpos($value_config, ','."Notes".',') !== FALSE) {
                echo '<td data-title="Function">';
                echo '<a href=\'add_sales.php?salesid='.$row['salesid'].'&go=notes\'>Add/View</a>';
                echo '</td>';
                }
                if (strpos($value_config, ','."Status".',') !== FALSE) {
                ?>

                <td data-title="Status">
                    <select id="status_<?php echo $row['salesid']; ?>" data-placeholder="Select a Status" name="status[]" class="form-control chosen-select-deselect" width="380">
						<option value=""></option>
						<!--
						<option <?php //if($row['status'] == "Pending") { echo " selected"; } ?> value="Pending">Pending</option>
						<option <?php //if($row['status'] == "Prospect") { echo " selected"; } ?> value="Prospect">Prospect</option>
						<option <?php //if($row['status'] == "Qualification") { echo " selected"; } ?> value="Qualification">Qualification</option>
						<option <?php //if($row['status'] == "Needs Analysis") { echo " selected"; } ?> value="Needs Analysis">Needs Analysis</option>
						<option <?php //if($row['status'] == "Propose Quote") { echo " selected"; } ?> value="Propose Quote">Propose Quote</option>
						<option <?php //if($row['status'] == "Negotiations") { echo " selected"; } ?> value="Negotiations">Negotiations</option>
						<option <?php //if($row['status'] == "Won") { echo " selected"; } ?> value="Won">Won</option>
						<option <?php //if($row['status'] == "Lost") { echo " selected"; } ?> value="Lost">Lost</option>
						<option <?php //if($row['status'] == "Abandoned") { echo " selected"; } ?> value="Abandoned">Abandoned</option>
						<option <?php //if($row['status'] == "Future Review") { echo " selected"; } ?> value="Future Review">Future Review</option>
						-->
						<?php
						/*
						$tabs = get_config($dbc, 'sales_lead_status');
						$each_tab = explode(',', $tabs);
						foreach ($each_tab as $cat_tab) {
							if($row['status'] == $cat_tab) {
								$selected = 'selected="selected"';
							} else {
								$selected = '';
							}
							if($cat_tab !== '' && $cat_tab !== NULL) {
								echo "<option ".$selected." value='". $cat_tab."'>".$cat_tab.'</option>';
							}
						}
						*/
						?>
						<?php
							$tabs		= get_config ( $dbc, 'sales_lead_status' );
							$each_tab	= explode ( ',', $tabs );
							
							foreach ( $each_tab as $cat_tab ) {
								$selected = ( $row['status'] == $cat_tab ) ? 'selected="selected"' : '';
								echo "<option " . $selected . " value='" . $cat_tab . "'>" . $cat_tab . '</option>';
							}
						?>
                    </select>
                </td>
                <?php
                }

				echo '<td data-title="Function">';
                if(vuaed_visible_function($dbc, 'sales') == 1) {
					echo "<a href='convert_sales_lead.php?leadid=".$row['salesid']."'>Save to Customers</a>";
                }
                echo '</td>';

                echo "</tr>";
            }
			if($num_rows > 0) {
				echo '</table></div>';
			}
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
