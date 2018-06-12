<?php
/*
Dashboard
*/
include ('../include.php');
error_reporting(0);

if((!empty($_GET['action'])) && ($_GET['action'] == 'send_followup_email')) {
    $manualtypeid = $_GET['manualtypeid'];
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
    $message .= "Please login with software and click on below link.<br>If you have any question or concern then add comment to it. And don't forget to Sign that manual to complete process of manual.<br><br>";
    $message .= 'Manual : <a target="_blank" href="'.$_SERVER['SERVER_NAME'].'/add_manual.php?manualtypeid='.$manualtypeid.'&type='.$manual_type.'&action=view">Click Here</a><br>';
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
            if($type == 'safety') {
                $column = 'Safety';
            }
            echo '<h2>'.$column.' Follow Up</h2>';

            echo '<a href="field_config_policy_procedures.php" class="btn brand-btn mobile-block pull-right  config-btn">Tile Configurator</a><br><br>';
        ?>
        <a href='<?php echo $type; ?>.php?contactid=<?php echo $_SESSION['contactid']; ?>'><button type="button" class="btn brand-btn mobile-block" >Dashboard</button></a>
        <a href='manual_follow_up.php?type=<?php echo $type; ?>'><button type="button" class="btn brand-btn mobile-block active_tab" >Follow Up</button></a>
        <br><br>
        <?php

        $s_start_date = date('Y-m-d');
        $s_end_date = date('Y-m-d');
        if(!empty($_POST['s_start_date'])) {
            $s_start_date = $_POST['s_start_date'];
        }
        if(!empty($_POST['s_end_date'])) {
            $s_end_date = $_POST['s_end_date'];
        }
        ?>
        <!--
        <div class="form-group">
            <label for="site_name" class="col-sm-2 control-label">Start Date:</label>
            <div class="col-sm-4">
                <input name="s_start_date" type="text" class="datepicker" value="<?php echo $s_start_date; ?>">
            </div>

            <label for="first_name" class="col-sm-2 control-label">End Date:</label>
            <div class="col-sm-4">
                <input name="s_end_date" type="text" class="datepicker" value="<?php echo $s_end_date; ?>">
            </div>

            <button type="submit" name="reporting_client" value="Submit" class="btn brand-btn mobile-block">Submit</button>
        </div>
        -->

        <span class="pull-right">
            <a href="manual_follow_up.php?type=<?php echo $type; ?>"><img src="<?php echo WEBSITE_URL;?>/img/white.png" width="32" height="32" border="0" alt=""> Display All</a>
            <a href="manual_follow_up.php?action=red&type=<?php echo $type; ?>"><img src="<?php echo WEBSITE_URL;?>/img/red.png" width="32" height="32" border="0" alt=""> Deadline Gone</a>
            <a href="manual_follow_up.php?action=green&type=<?php echo $type; ?>"><img src="<?php echo WEBSITE_URL;?>/img/green.png" width="32" height="32" border="0" alt=""> Deadline Today</a>
        </span><br><br>
        <?php

        if(isset($_POST['reporting_client'])) {
            $s_start_date = $_POST['s_start_date'];
            $s_end_date = $_POST['s_end_date'];
            $query_check_credentials = "SELECT m.*, ms.*  FROM manuals_staff ms, manuals m WHERE m.deleted=0 AND m.manual_type = '$type' AND m.manualtypeid = ms.manualtypeid AND m.deadline >= '$s_start_date' AND m.deadline <= '$s_end_date'";
        } else if(empty($_GET['action'])) {
            $query_check_credentials = "SELECT m.*, ms.*  FROM manuals_staff ms, manuals m WHERE m.deleted=0 AND m.manual_type = '$type' AND m.manualtypeid = ms.manualtypeid";
        } else if($_GET['action'] == 'red') {
            $query_check_credentials = "SELECT m.*, ms.*  FROM manuals_staff ms, manuals m WHERE m.deleted=0 AND m.manual_type = '$type' AND m.manualtypeid = ms.manualtypeid AND ms.done=0 AND DATE(NOW()) > DATE(m.deadline)";
        } else if($_GET['action'] == 'green') {
            $query_check_credentials = "SELECT m.*, ms.*  FROM manuals_staff ms, manuals m WHERE m.deleted=0 AND m.manual_type = '$type' AND m.manualtypeid = ms.manualtypeid AND ms.done=0 AND DATE(NOW()) = DATE(m.deadline)";
        }

        $result = mysqli_query($dbc, $query_check_credentials);

        $num_rows = mysqli_num_rows($result);
        if($num_rows > 0) {
            echo "<table class='table table-bordered'>";
            echo "<tr class='hidden-xs hidden-sm'>
                <th>Staff</th>
                <th>Email</th>
                <th>Category</th>
                <th>Heading</th>
                <th>Sub Heading</th>
                <th>Deadline</th>
                <th>Signed Off</th>
                </tr>";
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
            echo "<tr ".$color." >";
            echo '<td data-title="Contact Person">' . get_staff($dbc, $row['staffid']) . '</td>';
            echo '<td data-title="Code">' . get_email($dbc, $row['staffid']) . '</td>';
            echo '<td data-title="Code">' . $row['category'] . '</td>';
            echo '<td data-title="Code">' . $row['heading'] . '</td>';
            echo '<td data-title="Code">' . $row['sub_heading'] . '</td>';
            echo '<td data-title="Code">' . $row['deadline'] . '</td>';

            if($row['done'] == 1) {
                echo '<td data-title="Code"><img src="'.WEBSITE_URL.'/img/checkmark.png" width="22" height="22" border="0" alt=""> '.$row['today_date'] .'</td>';
            } else {
                echo '<td data-title="Code"><a href="manual_follow_up.php?staffid='.$row['staffid'].'&manual_type='.$row['manual_type'].'&manualtypeid='.$row['manualtypeid'].'&action=send_followup_email">Send Follow Up Email</a></td>';
            }

            echo "</tr>";
        }

        echo '</table></div>';
        ?>
        </form>

    </div>
</div>

<?php include ('../footer.php'); ?>