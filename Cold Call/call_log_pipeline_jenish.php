<script type="text/javascript">
$(document).on('change', 'select[name="search_client"]', function() { submitForm(); });
$(document).on('change', 'select[name="search_contact"]', function() { submitForm(); });
$(document).on('change', 'select[name="search_action"]', function() { submitForm(); });
$(document).on('change', 'select[name="search_status"]', function() { submitForm(); });
$(document).on('change', 'select[name="next_action[]"]', function() { selectAction(this); });
$(document).on('change', 'select[name="status[]"]', function() { selectStatus(this); });
</script>
<?php
echo '<br /><br /><div class="mobile-100-container">';
        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT value as call_log FROM general_configuration WHERE name='calllog_lead_status'"));
        $value_config = ','.$get_field_config['call_log'].',';

        $active_scheduled = '';
        $active_missed = '';
        $active_passed = '';
        $active_to_be = '';
        if(empty($_GET['type'])) {
            $_GET['type'] = 'tobe';
        }
        if($_GET['type'] == 'scheduled') {
            $active_scheduled = 'active_tab';
        }
        if($_GET['type'] == 'missed') {
            $active_missed = 'active_tab';
        }
        if($_GET['type'] == 'passed') {
            $active_passed = 'active_tab';
        }
        if($_GET['type'] == 'tobe') {
            $active_to_be = 'active_tab';
        }
        
		if (strpos($value_config, ','."To Be Scheduled".',') !== FALSE) {
            echo "<a href='".addOrUpdateUrlParam('type','tobe')."'><button type='button' class='btn brand-btn mobile-block  mobile-100 ".$active_to_be."'>To Be Scheduled</button></a>&nbsp;&nbsp;";
        }
        if (strpos($value_config, ','."Scheduled".',') !== FALSE) {
            echo "<a href='".addOrUpdateUrlParam('type','scheduled')."'><button type='button' class='btn brand-btn mobile-block mobile-100 ".$active_scheduled."'>Scheduled</button></a>&nbsp;&nbsp;";
        }
        if (strpos($value_config, ','."Missed".',') !== FALSE) {
            echo "<a href='".addOrUpdateUrlParam('type','missed')."'><button type='button' class='btn brand-btn mobile-block mobile-100 ".$active_missed."'>Missed</button></a>&nbsp;&nbsp;";
        }
        if (strpos($value_config, ','."Passed Due".',') !== FALSE) {
            echo "<a href='".addOrUpdateUrlParam('type','passed')."'><button type='button' class='btn brand-btn mobile-block mobile-100 ".$active_passed."'>Passed Due</button></a>&nbsp;&nbsp;";
        }

        //echo "<a href='sales_lead_source_report.php'><button type='button' class='btn brand-btn mobile-block'>Lead Source Report</button></a>&nbsp;&nbsp;";
        //echo "<a href='sales_next_action_report.php'><button type='button' class='btn brand-btn mobile-block'>Next Action Report</button></a>&nbsp;&nbsp;";
		echo '</div>';
        ?>
        </h1>

        <form name="form_sites" method="post" action="" class="form-inline" role="form">
            <div class="pad-top pad-bottom clearfix">
                <?php
                if(vuaed_visible_function($dbc, 'sales') == 1) {
					echo '<div class="mobile-100-container" style="left:-8px;position:relative;">';
						echo '<a href="add_sales.php" class="btn brand-btn mobile-block pull-right mobile-100-pull-right">Add Call Log</a>';
						echo '<span class="popover-examples list-inline pull-right" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Add sales lead details here."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
					echo '</div>';
                }
                $search_client = '';
                $search_contact = '';
                $search_action = '';
                $search_status = '';
                $search_date = '';
                if(isset($_POST['search_user_submit'])) {
                    $search_client = $_POST['search_client'];
                    $search_contact = $_POST['search_contact'];
                    $search_action = $_POST['search_action'];
                    $search_status = $_POST['search_status'];
                    if($_GET['type'] == 'custom') {
                    $search_date = $_POST['search_date'];
                    }
                }
                if (isset($_POST['display_all_inventory'])) {
                    $search_client = '';
                    $search_contact = '';
                    $search_action = '';
                    $search_status = '';
                    $search_date = '';
                }
                ?>
            </div>

                <?php if($_GET['type'] == 'custom') { ?>
				<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4" style='max-width:200px;'>
                <label for="search_site" style='width:100%; text-align: center;'>By Created Date:</label>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-8 col-xs-8">
				<input type="text" name="search_date" value="<?php echo $search_date; ?>" class="datepicker form-control" onchange="submitForm();"><br>
                </div>
				<div class="clearfix" style='margin:10px;'>
				</div>
				<?php } ?>
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
                <select data-placeholder="Choose a Next Action..." name="search_action" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <option <?php if ($search_action == "Email") { echo " selected"; } ?> value="Email">Email</option>
                  <option <?php if ($search_action == "Follow Up") { echo " selected"; } ?> value="Follow Up">Follow Up</option>
                  <option <?php if ($search_action == "Phone Call") { echo " selected"; } ?> value="Phone Call">Phone Call</option>
                  <option <?php if ($search_action == "Initial Meeting") { echo " selected"; } ?> value="Initial Meeting">Initial Meeting</option>
                  <option <?php if ($search_action == "Meeting") { echo " selected"; } ?> value="Meeting">Meeting</option>
                  <option <?php if ($search_action == "Presentation") { echo " selected"; } ?> value="Presentation">Presentation</option>
                  <option <?php if ($search_action == "Estimate") { echo " selected"; } ?> value="Estimate">Estimate</option>
                  <option <?php if ($search_action == "Quote Sent") { echo " selected"; } ?> value="Quote Sent">Quote Sent</option>
                  <option <?php if ($search_action == "Closing Meeting") { echo " selected"; } ?> value="Closing Meeting">Closing Meeting</option>
                  <option <?php if ($search_action == "Waiting") { echo " selected"; } ?> value="Waiting">Waiting</option>
                  <?php
                    $tabs = get_config($dbc, 'sales_next_action');
                    $each_tab = explode(',', $tabs);
                    foreach ($each_tab as $cat_tab) {
                        if ($search_action == $cat_tab) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value='". $cat_tab."'>".$cat_tab.'</option>';
                    }
                  ?>
                </select>
				</div>
				<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4 " style='max-width:200px;'>
                <label for="search_site" style='width:100%; text-align: center;'>By Status:</label>
				</div>
                <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                <select data-placeholder="Choose a Status..." name="search_status" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <option <?php if ($search_status == "Pending") { echo " selected"; } ?> value="Pending">Pending</option>
                  <option <?php if ($search_status == "Prospect") { echo " selected"; } ?> value="Prospect">Prospect</option>
                  <option <?php if ($search_status == "Qualification") { echo " selected"; } ?> value="Qualification">Qualification</option>
                  <option <?php if ($search_status == "Needs Analysis") { echo " selected"; } ?> value="Needs Analysis">Needs Analysis</option>
                  <option <?php if ($search_status == "Propose Quote") { echo " selected"; } ?> value="Propose Quote">Propose Quote</option>
                  <option <?php if ($search_status == "Negotiations") { echo " selected"; } ?> value="Negotiations">Negotiations</option>
                  <option <?php if ($search_status == "Won") { echo " selected"; } ?> value="Won">Won</option>
                  <option <?php if ($search_status == "Lost") { echo " selected"; } ?> value="Lost">Lost</option>
                  <option <?php if ($search_status == "Abandoned") { echo " selected"; } ?> value="Abandoned">Abandoned</option>
                  <option <?php if ($search_status == "Future Review") { echo " selected"; } ?> value="Future Review">Future Review</option>
                  <?php
                    $tabs = get_config($dbc, 'sales_lead_status');
                    $each_tab = explode(',', $tabs);
                    foreach ($each_tab as $cat_tab) {
                        if ($search_status == $cat_tab) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value='". $cat_tab."'>".$cat_tab.'</option>';
                    }
                  ?>
                </select>
				</div>
				<div class="clearfix" style='margin:10px;'>
				</div>
				<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4"></div>
				<div class="col-lg-8 col-md-7 col-sm-8 col-xs-8">
                <!--<button type="submit" name="search_user_submit" value="Search" class="btn brand-btn mobile-block">Search</button>-->
				
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
                $add_query = " AND businessid='$search_client'";
            }
            if($search_contact != '') {
                $add_query = " AND contactid='$search_contact'";
            }
            if($search_action != '') {
                $add_query = " AND next_action='$search_action'";
            }
            if($search_status != '') {
                $add_query = " AND status='$search_status'";
            }
            if($_GET['type'] == 'today') {
                $query_check_credentials = "SELECT * FROM sales WHERE status NOT IN('Won','Lost') AND created_date=DATE(NOW()) $add_query LIMIT $offset, $rowsPerPage";
                $query = "SELECT count(*) as numrows FROM sales WHERE status NOT IN('Won','Lost') AND created_date=DATE(NOW()) $add_query";
            }
            if($_GET['type'] == 'week') {
                $query_check_credentials = "SELECT * FROM sales WHERE status NOT IN('Won','Lost') AND WEEKOFYEAR(created_date)=WEEKOFYEAR(NOW()) $add_query  LIMIT $offset, $rowsPerPage";
                $query = "SELECT count(*) as numrows FROM sales WHERE status NOT IN('Won','Lost') AND WEEKOFYEAR(created_date)=WEEKOFYEAR(NOW()) $add_query";
            }
            if($_GET['type'] == 'month') {
                $query_check_credentials = "SELECT * FROM sales WHERE status NOT IN('Won','Lost') AND YEAR(created_date) = YEAR(NOW()) AND MONTH(created_date)=MONTH(NOW()) $add_query LIMIT $offset, $rowsPerPage";
                $query = "SELECT count(*) FROM sales WHERE status NOT IN('Won','Lost') AND YEAR(created_date) = YEAR(NOW()) AND MONTH(created_date)=MONTH(NOW()) $add_query";
            }
            if($_GET['type'] == 'custom') {
                if($search_date == '') {
                    $query_check_credentials = "SELECT * FROM sales WHERE status NOT IN('Won','Lost') AND created_date=DATE(NOW()) $add_query LIMIT $offset, $rowsPerPage";
                    $query = "SELECT count(*) as numrows FROM sales WHERE status NOT IN('Won','Lost') AND created_date=DATE(NOW()) $add_query";
                } else {
                    $query_check_credentials = "SELECT * FROM sales WHERE status NOT IN('Won','Lost') AND created_date='$search_date' $add_query LIMIT $offset, $rowsPerPage";
                    $query = "SELECT count(*) as numrows FROM sales WHERE status NOT IN('Won','Lost') AND created_date='$search_date' $add_query";
                }
            }

            $result = mysqli_query($dbc, $query_check_credentials);

            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {
                echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT call_log_dashboard FROM field_config WHERE `fieldconfigid` = 1"));
                $value_config = ','.$get_field_config['call_log_dashboard'].',';

                echo "<div id='no-more-tables'><table class='table table-bordered'>";
			    echo "<tr class='hidden-xs hidden-sm'>";
                if (strpos($value_config, ','."CL#".',') !== FALSE) {
                    echo '<th>CL#</th>';
                }
                if (strpos($value_config, ','."Business & contact".',') !== FALSE) {
                    echo '<th>Business & contact</th>';
                }
                if (strpos($value_config, ','."Call Subject".',') !== FALSE) {
                    echo '<th>Call Subject</th>';
                }
                if (strpos($value_config, ','."Call Duration".',') !== FALSE) {
                    echo '<th>Call Duration</th>';
                }
                if (strpos($value_config, ','."Next Action".',') !== FALSE) {
                    echo '<th>Next Action</th>';
                }
                if (strpos($value_config, ','."Notes".',') !== FALSE) {
                    echo '<th>Notes</th>';
                }
                if (strpos($value_config, ','."Status".',') !== FALSE) {
                    echo '<th>Status</th>';
                }
                if (strpos($value_config, ','."History".',') !== FALSE) {
                    echo '<th>History</th>';
                }
                  // requested to have this removed  echo '<th>Function</th>';
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
                echo '' . decryptIt($row['email_address']) . '</td>';
                }
                if (strpos($value_config, ','."Next Action".',') !== FALSE) {
                ?>
                <td data-title="Status">
                    <select id="action_<?php echo $row['salesid']; ?>" data-placeholder="Choose a Next Action..." name="next_action[]" class=" form-control" width="380">
                      <option value=""></option>
                      <option <?php if ($row['next_action'] == "Email") { echo " selected"; } ?> value="Email">Email</option>
                      <option <?php if ($row['next_action'] == "Follow Up") { echo " selected"; } ?> value="Follow Up">Follow Up</option>
                      <option <?php if ($row['next_action'] == "Phone Call") { echo " selected"; } ?> value="Phone Call">Phone Call</option>
                      <option <?php if ($row['next_action'] == "Initial Meeting") { echo " selected"; } ?> value="Initial Meeting">Initial Meeting</option>
                      <option <?php if ($row['next_action'] == "Meeting") { echo " selected"; } ?> value="Meeting">Meeting</option>
                      <option <?php if ($row['next_action'] == "Presentation") { echo " selected"; } ?> value="Presentation">Presentation</option>
                      <option <?php if ($row['next_action'] == "Estimate") { echo " selected"; } ?> value="Estimate">Estimate</option>
                      <option <?php if ($row['next_action'] == "Quote Sent") { echo " selected"; } ?> value="Quote Sent">Quote Sent</option>
                      <option <?php if ($row['next_action'] == "Closing Meeting") { echo " selected"; } ?> value="Closing Meeting">Closing Meeting</option>
                      <option <?php if ($row['next_action'] == "Waiting") { echo " selected"; } ?> value="Waiting">Waiting</option>
                      <?php
                        $tabs = get_config($dbc, 'sales_next_action');
                        $each_tab = explode(',', $tabs);
                        foreach ($each_tab as $cat_tab) {
                            if ($row['next_action'] == $cat_tab) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            if($cat_tab !== '' && $cat_tab !== NULL) {
								echo "<option ".$selected." value='". $cat_tab."'>".$cat_tab.'</option>';
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
                    <select id="status_<?php echo $row['salesid']; ?>" data-placeholder="Choose a Status..." name="status[]" class="form-control" width="380">
                      <option value=""></option>
                      <option <?php if($row['status'] == "Pending") { echo " selected"; } ?> value="Pending">Pending</option>
                      <option <?php if($row['status'] == "Prospect") { echo " selected"; } ?> value="Prospect">Prospect</option>
                      <option <?php if($row['status'] == "Qualification") { echo " selected"; } ?> value="Qualification">Qualification</option>
                      <option <?php if($row['status'] == "Needs Analysis") { echo " selected"; } ?> value="Needs Analysis">Needs Analysis</option>
                      <option <?php if($row['status'] == "Propose Quote") { echo " selected"; } ?> value="Propose Quote">Propose Quote</option>
                      <option <?php if($row['status'] == "Negotiations") { echo " selected"; } ?> value="Negotiations">Negotiations</option>
                      <option <?php if($row['status'] == "Won") { echo " selected"; } ?> value="Won">Won</option>
                      <option <?php if($row['status'] == "Lost") { echo " selected"; } ?> value="Lost">Lost</option>
                      <option <?php if($row['status'] == "Abandoned") { echo " selected"; } ?> value="Abandoned">Abandoned</option>
                      <option <?php if($row['status'] == "Future Review") { echo " selected"; } ?> value="Future Review">Future Review</option>
                      <?php
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
                      ?>
                    </select>
                </td>
                <?php
                }

                /* Requested to have this removed... 
				echo '<td data-title="Function">';
                if(vuaed_visible_function($dbc, 'sales') == 1) {
                echo '<a href=\'add_sales.php?salesid='.$row['salesid'].'\'>Edit</a>';
				//echo '<a href=\''.WEBSITE_URL.'/delete_restore.php?action=delete&salesid='.$row['salesid'].'\' onclick="return confirm(\'Are you sure?\')">Delete</a>';
                }
                echo '</td>';
				*/

                echo "</tr>";
            }
			if($num_rows > 0) {
				echo '</table></div>';
			}
            if(vuaed_visible_function($dbc, 'sales') == 1) {
            echo '<a href="add_sales.php" class="btn brand-btn mobile-block pull-right">Add Call Log</a>';
			echo '<span class="popover-examples list-inline pull-right" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Add sales lead details here."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
            }

            ?>

        

        </form>

        </div>
