<?php

    if(isset($_POST['import']))
    {
        if (!file_exists('download')) {
            mkdir('download', 0777, true);
        }

        $document = htmlspecialchars($_FILES["upload_document"]["name"], ENT_QUOTES);
        move_uploaded_file($_FILES["upload_document"]["tmp_name"], "download/".$_FILES["upload_document"]["name"]);
        $file = fopen("download/".$_FILES["upload_document"]["name"],"r");

        $count = 0;
        $created_by = $_SESSION['first_name'].' '.$_SESSION['last_name'];
        $created_date = date('Y-m-d');
        while(!feof($file))
        {
            $data = fgetcsv($file);
            if($count == 0) {
                $heading = $data;
                if(empty($_GET['status']))
                    $_GET['status'] = 'Available';
                $status = $_GET['status'];

                if($heading[0] != 'Business')
                {
                    echo '<script type="text/javascript"> alert("First Header Should be Business & Contact"); window.location.replace("call_log.php?maintype=leadbank&status='.$status.'"); </script>';
                }
                if($heading[1] != 'Contact')
                {
                    echo '<script type="text/javascript"> alert("Second Header Should be Business & Contact"); window.location.replace("call_log.php?maintype=leadbank&status='.$status.'"); </script>';
                }
                if($heading[2] != 'Call Subject')
                {
                    echo '<script type="text/javascript"> alert("Third Header Should be Call Subject"); window.location.replace("call_log.php?maintype=leadbank&status='.$status.'"); </script>';
                }
                if($heading[3] != 'Call Duration')
                {
                    echo '<script type="text/javascript"> alert("Fourth Header Should be Call Duration"); window.location.replace("call_log.php?maintype=leadbank&status='.$status.'"); </script>';
                }
                if($heading[4] != 'Notes')
                {
                    echo '<script type="text/javascript"> alert("Fifth Header Should be Notes"); window.location.replace("call_log.php?maintype=leadbank&status='.$status.'"); </script>';
                }
                if($heading[5] != 'Next Action')
                {
                    echo '<script type="text/javascript"> alert("Sixth Header Should be Next Action"); window.location.replace("call_log.php?maintype=leadbank&status='.$status.'"); </script>';
                }
                if($heading[6] != 'Reminder/Follow Up')
                {
                    echo '<script type="text/javascript"> alert("Seventh Header Should be Reminder/Follow Up"); window.location.replace("call_log.php?maintype=leadbank&status='.$status.'"); </script>';
                }
            }
            else {
                    if(!empty($data)) {
                        $query = "select contactid from contacts where name = '" . $data[0] . "'";
                        $query_select_business = "Select contactid from `contacts` where category = 'Cold Call Contact' AND name = '$data[0]'";
                        $businessIds = mysqli_fetch_array(mysqli_query($dbc, $query_select_business));

                        if(!empty($businessIds)) {
                            $businessIds = array_unique($businessIds, SORT_REGULAR);
                            $databusinessId = implode(',',$businessIds);
                            $name = $data[1];
                            $names = explode(' ', $name);
                            $firstname = encryptIt($names[0]);
                            $lastname = encryptIt($names[1]);
                            $query_select_contact = "Select contactid from `contacts` where contactid IN ($databusinessId) AND first_name = '$firstname' AND last_name = '$lastname'";
                            $contactId = mysqli_fetch_assoc(mysqli_query($dbc, $query_select_contact));
                            if($contactId) {
                                $businessid = $contactId;
                                $contactid = $contactId;
                            }
                            else {
                                $office_phone = '';
                                $email_address = '';
                                $businessid = $businessIds[0];
                                $query_insert_inventory = "INSERT INTO `contacts` (`category`, `businessid`, `name`, `first_name`, `office_phone`, `email_address`) VALUES ('Cold Call Business', '$businessIds[0]', '$data[0]', '$firstname', '$office_phone', '$email_address')";
                                $result_insert_inventory = mysqli_query($dbc, $query_insert_inventory);
                                $contactid = mysqli_insert_id($dbc);
                            }
                        }
                        else {
                            $query_insert_inventory = "INSERT INTO `contacts` (`category`, `name`) VALUES ('Cold Call Contact', '$data[0]')";
                            $result_insert_inventory = mysqli_query($dbc, $query_insert_inventory);
                            $businessid = mysqli_insert_id($dbc);

                            $office_phone = '';
                            $email_address = '';
                            $query_insert_inventory = "INSERT INTO `contacts` (`category`, `businessid`, `name`, `first_name`, `office_phone`, `email_address`) VALUES ('Cold Call Business', '$businessIds[0]', '$data[0]', '$firstname', '$office_phone', '$email_address')";
                            $result_insert_inventory = mysqli_query($dbc, $query_insert_inventory);
                            $contactid = mysqli_insert_id($dbc);
                        }
                        //$contactid = $contactIds['contactid'];

                        $call_subject = $data[2];
                        $call_duration = $data[3];
                        $call_notes = $data[4];
                        $next_action = $data[5];
                        $new_reminder = $data[6];

                        $query_insert_vendor = "INSERT INTO `calllog_pipeline` (`created_date`, `created_by`, `businessid`, `contactid`, `call_subject`,`call_duration`, `call_notes`, `next_action`, `new_reminder`, `status`) VALUES ('$created_date', '$created_by', '$businessid', '$contactid', '$call_subject', '$call_duration', '$call_notes', '$next_action', '$new_reminder', 'Available')";
                        mysqli_query($dbc, $query_insert_vendor);
                    }
            }

            $count++;
        }

        fclose($file);
        $actual_link = WEBSITE_URL."$_SERVER[REQUEST_URI]";
        header("Location: $actual_link");

    }

    if(isset($_POST['export'])) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=callog_export.csv');
        $data = array("Business", "Contact", "Call Subject", "Call Duration", "Notes", "Next Action", "Reminder/Follow Up");
        ob_end_clean();
        $fp = fopen('php://output','w');
        fputcsv($fp, $data);
        exit();
    }
?>

<div class="container">
	<div class="row">
        <br>

        <form name="form_sites" method="post" action="" class="form-inline" role="form">
            <button type='submit' name='export' value='Export' class='btn brand-btn mobile-block  mobile-100'>Export</button>
        </form>

        <br>

        <form name="form_sites_file" id='form_sites_file' method="post" action="" enctype="multipart/form-data" class="form-inline">
            <input style='width:25% !important' name="upload_document" multiple type="file" data-filename-placement="inside" class="form-control" />
            <button type='submit' name='import' value='Import' class='btn brand-btn mobile-block  mobile-100'>Import</button>
        </form>
	</div>
</div>

<script type='text/javascript'>
    $(document).on('change', 'select[name="search_client"]', function() { submitForm(); });
    $(document).on('change', 'select[name="search_contact"]', function() { submitForm(); });
    $(document).on('change', 'select[name="search_action"]', function() { submitForm(); });
    $(document).on('change', 'select[name="search_status"]', function() { submitForm(); });
    $(document).on('change', 'select[name="next_action[]"]', function() { selectAction(this); });
    $(document).on('change', 'select[name="status[]"]', function() { selectStatus(this); });
    function submitForm() {
        jQuery('#form_sites_file').submit();
    }
</script>

<form name="form_sites" method="post" action="" class="form-inline" role="form">
    <div class="pad-top pad-bottom clearfix">
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

        if (strpos($value_config, ','."Business & contact".',') !== FALSE) {
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


