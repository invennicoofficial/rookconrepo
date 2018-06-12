<?php

    $calllog_lead_status = get_config($dbc, 'calllog_lead_status');

    $each_tab = explode(',', $calllog_lead_status);
    $statusCount = 0;
    foreach ($each_tab as $cat_tab) {
        if($cat_tab == 'Available' || $cat_tab == 'Abandoned' || $cat_tab == 'Lost/Archive')
            continue;
        if ((!empty($_GET['status'])) && ($_GET['status'] == $cat_tab))
            $statusCount++;
    }

    $totalCount = 0;
    foreach ($each_tab as $cat_tab) {
        if($cat_tab == 'Available' || $cat_tab == 'Abandoned' || $cat_tab == 'Lost/Archive')
            continue;

        if(empty($_GET['status']) || ($statusCount == 0 && $totalCount == 0)) {
            $_GET['status'] = $cat_tab;
        }

        if ((!empty($_GET['status'])) && ($_GET['status'] == $cat_tab)) {
            $active_to_be = ' active_tab';
        }
        else {
            $active_to_be = '';
        }
        $totalCount++;
    }
    $active_status = strtolower($_GET['status']);
    $tab_info = "";
    switch(strtolower($active_status)) {

        case 'not scheduled':
        $notes = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT note FROM notes_setting WHERE subtab = 'cc_not_scheduled'"));
        $note = $notes['note'];
        $tab_info = $note; break;

        case 'scheduled':
        $notes = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT note FROM notes_setting WHERE subtab = 'cc_scheduled'"));
        $note = $notes['note'];
        $tab_info = $note; break;

        case 'missed call':
        $notes = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT note FROM notes_setting WHERE subtab = 'cc_missed_call'"));
        $note = $notes['note'];
        $tab_info = $note; break;

        case 'past due':
        $notes = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT note FROM notes_setting WHERE subtab = 'cc_past_due'"));
        $note = $notes['note'];
        $tab_info = $note; break;

        case 'available':
        $notes = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT note FROM notes_setting WHERE subtab = 'cc_available'"));
        $note = $notes['note'];
        $tab_info = $note; break;

        case 'abondoned': $notes = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT note FROM notes_setting WHERE subtab = 'cc_abondoned'"));
        $note = $notes['note'];
        $tab_info = $note; break;

        case 'lost/archive':
        $notes = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT note FROM notes_setting WHERE subtab = 'cc_lost_archive'"));
        $note = $notes['note'];
        $tab_info = $note; break;
    } ?>
    
    <script type="text/javascript">
    $(document).on('change', 'select[name="search_client"]', function() { submitForm(); });
    $(document).on('change', 'select[name="search_contact"]', function() { submitForm(); });
    $(document).on('change', 'select[name="search_action"]', function() { submitForm(); });
    $(document).on('change', 'select[name="search_status"]', function() { submitForm(); });
    $(document).on('change', 'select[name="next_action[]"]', function() { selectAction(this); });
    $(document).on('change', 'select[name="status[]"]', function() { selectStatus(this); });
    </script>
    <div class="clearfix"></div><?php
    
    if ( !empty($tab_info) ) { ?>
        <div class="notice popover-examples">
            <div class="col-sm-1 notice-icon"><img src="../img/info.png" class="wiggle-me" width="25"></div>
            <div class="col-sm-11"><span class="notice-name">NOTE:</span>
            <?= $tab_info ?></div>
            <div class="clearfix"></div>
        </div><?php
    } ?>
    
        <form name="form_sites" method="post" action="" class="form-inline" role="form">
            <div class="pad-bottom clearfix">
                <?php
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
                    if($_GET['status'] == 'custom') {
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

				<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4" style='max-width:200px;'>
                <label for="search_site" style='width:100%; text-align:center;'>By Business:</label>
				</div>
                <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                <select data-placeholder="Select a Business" name="search_client" class="chosen-select-deselect form-control">
                  <option value=""></option>
                  <?php
                    $query = mysqli_query($dbc,"SELECT DISTINCT(c.name), t.businessid FROM contacts c, calllog_pipeline t WHERE t.businessid=c.contactid order by c.name");
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
                    $query = mysqli_query($dbc,"SELECT DISTINCT(c.contactid), c.first_name, c.last_name, t.contactid FROM contacts c, calllog_pipeline t WHERE t.contactid=c.contactid order by c.first_name");
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
                  <?php
                    $tabs = get_config($dbc, 'calllog_next_action');
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
                  <?php
                    $tabs = get_config($dbc, 'calllog_lead_status');
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
                <!-- <button type="submit" name="search_user_submit" value="Search" class="btn brand-btn mobile-block">Search</button> -->

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

            if(!empty($_GET['status'])) {
                $status_url = $_GET['status'];
                $query_check_credentials = "SELECT * FROM calllog_pipeline WHERE status = '$status_url' $add_query LIMIT $offset, $rowsPerPage";
                $query = "SELECT count(*) as numrows FROM calllog_pipeline WHERE status = '$status_url' $add_query";
            } else {
                //$query_check_credentials = "SELECT * FROM calllog_pipeline WHERE status NOT IN('Won','Lost') $add_query LIMIT $offset, $rowsPerPage";
                //$query = "SELECT count(*) as numrows FROM calllog_pipeline WHERE status NOT IN('Won','Lost') $add_query";
            }

            $result = mysqli_query($dbc, $query_check_credentials);

            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {
                echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pipeline_dashboard FROM field_config_calllog WHERE `fccalllogid` = 1"));
                $value_config = ','.$get_field_config['pipeline_dashboard'].',';

                echo "<div id='no-more-tables'><table class='table table-bordered'>";
			    echo "<tr class='hidden-xs hidden-sm'>";
                if (strpos($value_config, ','."CL#".',') !== FALSE) {
                    echo '<th>CL#</th>';
                }
                if (stripos($value_config, ','."Business & Contact".',') !== FALSE) {
                    echo '<th>Business & Contact</th>';
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
                if (strpos($value_config, ','."Reminder/Follow Up".',') !== FALSE) {
                    echo '<th>Reminder/Follow Up</th>';
                }
                if (strpos($value_config, ','."Notes".',') !== FALSE) {
                    echo '<th>Notes</th>';
                }
                if (strpos($value_config, ','."Status".',') !== FALSE) {
                    echo '<th>Status</th>';
                }
                  // requested to have this removed  echo '<th>Function</th>';
                echo "</tr>";
            } else {
                echo "<h2>No Record Found.</h2>";
            }

            while($row = mysqli_fetch_array( $result ))
            {
                echo "<tr>";
                if (strpos($value_config, ','."CL#".',') !== FALSE) {
                    echo '<td data-title="Lead#"><a href=\'add_call_log.php?calllogid='.$row['calllogid'].'\'>' . $row['calllogid'] . '</a></td>';
                }

                if (stripos($value_config, ','."Business & Contact".',') !== FALSE) {
                    echo '<td data-title="Business">' . get_contact($dbc, $row['businessid'], 'name') . '<br>';
                    echo get_contact($dbc, $row['contactid'], 'first_name').' '.get_contact($dbc, $row['contactid'], 'last_name') . '</td>';
                }
                if (strpos($value_config, ','."Call Subject".',') !== FALSE) {
                    echo '<td data-title="Primary Phone">' . $row['call_subject'] . '</td>';
                }
                if (strpos($value_config, ','."Call Duration".',') !== FALSE) {
                    echo '<td data-title="Primary Phone">' . $row['call_duration'] . '</td>';
                }
                if (strpos($value_config, ','."Next Action".',') !== FALSE) {
                ?>
                <td data-title="Status">
                    <select id="action_<?php echo $row['calllogid']; ?>" data-placeholder="Choose a Next Action..." name="next_action[]" class=" form-control" width="380">
                      <option value=""></option>
                      <?php
                        $tabs = get_config($dbc, 'calllog_next_action');
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
                if (strpos($value_config, ','."Reminder/Follow Up".',') !== FALSE) {
                    echo '<td data-title="Reminder"><input name="new_reminder[]" type="text" id="reminder_'.$row['calllogid'].'"  onchange="followupDate(this)" class="datepicker" value="'.$row['new_reminder'].'"></td>';
                }

                if (strpos($value_config, ','."Notes".',') !== FALSE) {
                    echo '<td data-title="Function">';
                    echo '<a href=\'add_call_log.php?calllogid='.$row['calllogid'].'&go=notes\'>Add/View</a>';
                    echo '</td>';
                }
                if (strpos($value_config, ','."Status".',') !== FALSE) {
                ?>

                <td data-title="Status">
                    <select id="status_<?php echo $row['calllogid']; ?>" data-placeholder="Choose a Status..." name="status[]" class="form-control" width="380">
                      <option value=""></option>
                      <?php
                        $tabs = get_config($dbc, 'calllog_lead_status');
                        $each_tab = explode(',', $tabs);
                        foreach ($each_tab as $cat_tab) {
                            if($cat_tab == 'Available' || $cat_tab == 'Abandoned' || $cat_tab == 'Lost/Archive')
                                continue;
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
                echo '<a href=\'add_call_log.php?calllogid='.$row['calllogid'].'\'>Edit</a>';
				//echo '<a href=\''.WEBSITE_URL.'/delete_restore.php?action=delete&calllogid='.$row['calllogid'].'\' onclick="return confirm(\'Are you sure?\')">Delete</a>';
                }
                echo '</td>';
				*/

                echo "</tr>";
            }
			if($num_rows > 0) {
				echo '</table></div>';
			}

            ?>

        

        </form>

        </div>
