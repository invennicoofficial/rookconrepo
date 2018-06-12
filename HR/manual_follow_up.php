<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('hr');
error_reporting(0);

if (isset($_POST['send_follow_up_email'])) {
    for($i = 0; $i < count($_POST['check_send_email']); $i++) {
        $check_send_email = explode('_', $_POST['check_send_email'][$i]);
        $staffid = $check_send_email[0];
        $hrid = $check_send_email[1];

        $email = get_email($dbc, $staffid);

        $manual_type = get_manual($dbc, $hrid, 'manual_type');

        $email_body = "Please login through the software and click on the link below. Sign in the signature box to confirm you understand and will adhere to this policy. If you have any questions or concerns, add them in the comment section. <br><br>";
        $email_body .= 'Manual : <a target="_blank" href="'.$_SERVER['SERVER_NAME'].'/Manuals/add_manual.php?hrid='.$hrid.'&type='.$manual_type.'&action=view">Click Here</a><br>';

        $subject = 'Follow Up : Manual Assigned to you for Review';

        //Mail
        send_email('', $email, '', '', $subject, $email_body, '');
        //Mail
    }

    echo '<script type="text/javascript"> alert("Follow up Send to staff"); window.location.replace("manual_follow_up.php?type='.$manual_type.'"); </script>';
}

if((!empty($_GET['action'])) && ($_GET['action'] == 'send_followup_email')) {
    $hrid = $_GET['hrid'];
    $manual_type = $_GET['manual_type'];
    $staffid = $_GET['staffid'];

    //Mail
    $to = get_email($dbc, $staffid);
    // $to = 'dayanapatel@freshfocusmedia.com';
    $subject = 'Follow Up : Manual Assigned to you for Review';
    $headers .= "From: info@freshfocusmedia.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $message = '<html><body>';
    $message .= "Please login through the software and click on the link below. Sign in the signature box to confirm you understand and will adhere to this policy. If you have any questions or concerns, add them in the comment section. <br><br>";
    $message .= 'Manual : <a target="_blank" href="'.$_SERVER['SERVER_NAME'].'/add_manual.php?hrid='.$hrid.'&type='.$manual_type.'&action=view">Click Here</a><br>';
    $message .= '</body></html>';
    mail($to, $subject, $message, $headers);

    //Mail
    echo '<script type="text/javascript"> alert("Follow up Send to staff"); window.location.replace("manual_follow_up.php?type='.$manual_type.'"); </script>';
}

?>
</head>
<body>

<?php include_once ('../navigation.php'); ?>

<div class="container">
	<div class="row">

        <form name="form_sites" method="post" action="" class="form-inline" role="form">
        <?php
            $type = $_GET['type'];
            if($type == 'policy_procedures') {
                $column = 'Policies & Procedures';
            }
            if($type == 'operations_manual') {
                $column = 'Operations Manual';
            }
            if($type == 'emp_handbook') {
                $column = 'Employee Handbook';
            }
            if($type == 'guide') {
                $column = 'How to Guide';
            }
            if($type == 'hr') {
                $column = 'HR';
            }
            echo '<h2>'.$column.' Follow Up</h2>';

            /*
            if(config_visible_function($dbc, 'promotion') == 1) {
                echo '<a href="field_config_policy_procedures.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>';
            }
            */

            $category = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT category FROM manuals WHERE deleted=0 AND manual_type='$type' LIMIT 1"));
            $manual_category = $category['category'];
            if($manual_category == '') {
               $manual_category = 0;
            }
        ?>

        <!--
        <a href='hr.php?tab=Toolbox'><button type="button" class="btn brand-btn mobile-block <?php echo $active_toolbox; ?>" >Toolbox</button></a>
        <a href='hr.php?tab=Tailgate'><button type="button" class="btn brand-btn mobile-block <?php echo $active_taligate; ?>" >Tailgate</button></a>
        -->
        <a href='hr.php?tab=Form'><button type="button" class="btn brand-btn mobile-block <?php echo $active_form; ?>" >Forms</button></a>
        <a href='hr.php?tab=Manual'><button type="button" class="btn brand-btn mobile-block <?php echo $active_manual; ?>" >Manuals</button></a>
        <!-- <a href='<?php echo $type; ?>.php?category=<?php echo $manual_category; ?>&type=<?php echo $type; ?>'><button type="button" class="btn brand-btn mobile-block" >Dashboard</button></a> -->
        <a href='manual_follow_up.php?type=<?php echo $type; ?>'><button type="button" class="btn brand-btn mobile-block active_tab" >Follow Up</button></a>
        <a href='manual_reporting.php?type=<?php echo $type; ?>'><button type="button" class="btn brand-btn mobile-block" >Reporting</button></a>
        <br><br>
        <?php
        $contactid = '';
        $category = '';
        $heading = '';
        $status = '';
        if(!empty($_POST['contactid'])) {
            $contactid = $_POST['contactid'];
        }
        if(!empty($_POST['category'])) {
            $category = $_POST['category'];
        }
        if(!empty($_POST['heading'])) {
            $heading = $_POST['heading'];
        }
        if(!empty($_POST['status'])) {
            $status = $_POST['status'];
        }
        if (isset($_POST['display_all_asset'])) {
            $contactid = '';
            $category = '';
            $heading = '';
            $status = '';
        }
        ?>

        <div class="form-group">
          <label for="ship_country" class="col-sm-4 control-label">Staff:</label>
          <div class="col-sm-8">
                <select data-placeholder="Choose a Staff Member..." name="contactid" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
				  <?php
						$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 AND `status`=1"),MYSQLI_ASSOC));
						foreach($query as $id) {
							$selected = '';
							$selected = $id == $contactid ? 'selected = "selected"' : '';
							echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
						}
					  ?>
                </select>

          </div>
        </div>

        <div class="form-group">
          <label for="ship_zip" class="col-sm-4 control-label">Topic:</label>
          <div class="col-sm-8">
                <select data-placeholder="Choose a Topic (Sub Tab)..." name="category" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <?php
                    $query = mysqli_query($dbc,"SELECT distinct(category) FROM manuals WHERE deleted=0 AND manual_type='$type' order by category");
                    while($row = mysqli_fetch_array($query)) {
                        if ($category == $row['category']) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        ?>
                        <option <?php echo $selected; ?> value='<?php echo $row['category']; ?>' ><?php echo $row['category']; ?></option>
                    <?php }
                  ?>
                </select>
          </div>
        </div>

        <div class="form-group">
          <label for="ship_zip" class="col-sm-4 control-label">Heading:</label>
          <div class="col-sm-8">
                <select data-placeholder="Choose a Heading..." name="heading" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <?php
                    $query = mysqli_query($dbc,"SELECT distinct(heading) FROM manuals WHERE deleted=0 AND manual_type='$type' order by heading");
                    while($row = mysqli_fetch_array($query)) {
                        if ($heading == $row['heading']) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        ?>
                        <option <?php echo $selected; ?> value='<?php echo $row['heading']; ?>' ><?php echo $row['heading']; ?></option>
                    <?php }
                  ?>
                </select>
          </div>
        </div>

        <div class="form-group">
          <label for="ship_zip" class="col-sm-4 control-label">Status:</label>
          <div class="col-sm-8">
                <select data-placeholder="Choose a Status..." name="status" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <option <?php if ($status=='Deadline Past') echo 'selected="selected"';?> value="Deadline Past">Deadline Passed</option>
                  <option <?php if ($status=='Deadline Today') echo 'selected="selected"';?> value="Deadline Today">Deadline Today</option>
                </select>
          </div>
        </div>

        <div class="form-group">
            <div class="col-sm-4 clearfix">

            </div>
          <div class="col-sm-8">
                <button type="submit" name="reporting_client" value="Submit" class="btn brand-btn mobile-block">Submit</button>
                <button type="submit" name="display_all_asset" value="Display All" class="btn brand-btn mobile-block">Display All</button>
          </div>
        </div>

        <br><br>

        <span class="pull-right">
            <img src="<?php echo WEBSITE_URL;?>/img/block/red.png" width="23" height="23" border="0" alt=""> Deadline Passed
            <img src="<?php echo WEBSITE_URL;?>/img/block/green.png" width="23" height="23" border="0" alt=""> Deadline Today
        </span><br><br>
        <?php

        if(isset($_POST['reporting_client'])) {
            $contactid = $_POST['contactid'];
            $category = $_POST['category'];
            $heading = $_POST['heading'];
            $status = $_POST['status'];
            $query_check_credentials = "SELECT m.*, ms.*  FROM hr_staff ms, manuals m WHERE m.deleted=0 AND m.manual_type = '$type' AND m.hrid = ms.hrid AND (ms.staffid = '$contactid' OR m.category='$category' OR m.heading='$heading')";
            if($status == 'Deadline Past') {
                $query_check_credentials = "SELECT m.*, ms.*  FROM hr_staff ms, manuals m WHERE m.deleted=0 AND m.manual_type = '$type' AND m.hrid = ms.hrid AND ms.done=0 AND DATE(NOW()) > DATE(m.deadline)";
            }
            if($status == 'Deadline Today') {
                $query_check_credentials = "SELECT m.*, ms.*  FROM hr_staff ms, manuals m WHERE m.deleted=0 AND m.manual_type = '$type' AND m.hrid = ms.hrid AND ms.done=0 AND DATE(NOW()) = DATE(m.deadline)";
            }
        } else if(empty($_GET['action'])) {
            $query_check_credentials = "SELECT m.*, ms.*  FROM hr_staff ms, manuals m WHERE m.deleted=0 AND m.manual_type = '$type' AND m.hrid = ms.hrid";
        }

        $result = mysqli_query($dbc, $query_check_credentials);

        $num_rows = mysqli_num_rows($result);
        if($num_rows > 0) {
            echo "<table class='table table-bordered'>";
            echo '<tr class="hidden-xs hidden-sm">
                <th>Staff</th>
                <th>Email</th>
                <th>Topic (Sub Tab)</th>
                <th>Heading</th>
                <th>Sub Section Heading</th>
                <th>Deadline</th>
                <th>Status</th>
                <th>Signed Off&nbsp;&nbsp;<button type="submit" name="send_follow_up_email" value="Submit" class="btn brand-btn">Send</button></th>
                </tr>';
        } else {
            echo "<h2>No Record Found.</h2>";
        }

        while($row = mysqli_fetch_array( $result ))
        {
            $deadline = $row['deadline'];
            $today = date('Y-m-d');
            $color = '';

            if(($today > $deadline) && ($row['done'] == 0)) {
                $color = 'style="background-color: lightcoral;"';
            }
            if(($today == $deadline) && ($row['done'] == 0)) {
                $color = 'style="background-color: lightgreen;"';
            }
            echo "<tr>";
            echo '<td data-title="Contact Person">' . get_staff($dbc, $row['staffid']) . '</td>';
            echo '<td data-title="Code">' . get_email($dbc, $row['staffid']) . '</td>';
            echo '<td data-title="Code">' . $row['category'] . '</td>';
            echo '<td data-title="Code">' . $row['heading'] . '</td>';
            echo '<td data-title="Code">' . $row['sub_heading'] . '</td>';
            echo '<td data-title="Code">' . $row['deadline'] . '</td>';

            echo '<td data-title="Code">';
            if(($today > $deadline) && ($row['done'] == 0)) {
                echo '<img src="'.WEBSITE_URL.'/img/block/red.png" width="22" height="22" border="0" alt=""> '.$row['today_date'];
            }
            if(($today == $deadline) && ($row['done'] == 0)) {
                echo '<img src="'.WEBSITE_URL.'/img/block/green.png" width="22" height="22" border="0" alt=""> '.$row['today_date'];
            }
            if($row['done'] == 1) {
                echo '<img src="'.WEBSITE_URL.'/img/checkmark.png" width="22" height="22" border="0" alt="">';
            }
            echo '</td>';

            if($row['done'] == 1) {
                echo '<td data-title="Code">'.$row['today_date'] .'</td>';
            } else {
                echo '<td data-title="Code">';
                echo '<a href="manual_follow_up.php?staffid='.$row['staffid'].'&manual_type='.$row['manual_type'].'&hrid='.$row['hrid'].'&action=send_followup_email">Send</a>';
                echo '&nbsp;&nbsp;<input name="check_send_email[]" type="checkbox" value="'.$row['staffid'].'_'.$row['hrid'].'" class="form-control check_send_email" style="width:25px;"/></td>';
            }

            echo "</tr>";
        }

        echo '</table></div>';
        ?>

        
        </form>

    </div>
</div>

<?php include ('../footer.php'); ?>
