<?php
/*
EIS
*/
include ('../include.php');
checkAuthorised('booking');
?>
</head>
<body>

<?php include ('../navigation.php');

?>

<div class="container">
	<div class="row">

        <h1 class="">Waitlist Dashboard</h1>

        <form name="form_sites" method="post" action="" class="form-inline" role="form">

            <center>
            <div class="form-group">
                <label for="site_name" class="col-sm-5 control-label">Search By Any:</label>
                <div class="col-sm-6">
                <?php if(isset($_POST['search_email_submit'])) { ?>
                    <input type="text" name="search_email" value="<?php echo $_POST['search_email']?>" class="form-control">
                <?php } else { ?>
                    <input type="text" name="search_email" class="form-control">
                <?php } ?>
                </div>
            </div>
            &nbsp;
				<button type="submit" name="search_email_submit" value="Search" class="btn brand-btn">Search</button>
                <button type="submit" name="display_all_email" value="Display All" class="btn brand-btn">Display All</button>
            </center>

            <span><img src="../img/red.png" width="32" height="32" border="0" alt=""> Desired Date Today</span>

            <?php
            echo '<a class="btn brand-btn pull-right" href="'.WEBSITE_URL.'/Booking/add_waitlist.php">Add Waitlist</a>';
            ?>
        <div class="table-responsive">
            <?php

            $contactid = $_GET['contactid'];
            $tabs = mysqli_query($dbc, "SELECT distinct(therapistsid) FROM waitlist WHERE deleted=0 ORDER BY therapistsid");
            while($row_tab = mysqli_fetch_array( $tabs )) {
                $class='';
                $therapistsid = $row_tab['therapistsid'];
                if($therapistsid == $_GET['contactid']) {
                    $class= 'active_tab';
                }
                echo '<a href="waitlist.php?contactid='.$therapistsid.'"><button type="button" class="btn brand-btn mobile-block '.$class.'" >'.get_contact($dbc, $therapistsid).'</button></a>&nbsp;&nbsp;';
            }
            $class = '';
            if('0' == $_GET['contactid']) {
                $class= 'active_tab';
            }
            echo '<a href="waitlist.php?contactid=0"><button type="button" class="btn brand-btn mobile-block '.$class.'" >View All</button></a>&nbsp;&nbsp;';
            ?>

            <?php
            $email = '';
            if (isset($_POST['search_email_submit'])) {
                $email = $_POST['search_email'];
            }
            if (isset($_POST['display_all_email'])) {
                $email = '';
            }

            if($email != '') {
                $query_check_credentials = "SELECT * FROM waitlist WHERE therapistsid='$contactid' AND (s.first_name LIKE '%" . $email . "%' OR  s.last_name LIKE '%" . $email . "%' OR s.first_name LIKE '%" . $email . "%' OR  s.last_name LIKE '%" . $email . "%' OR  b.today_date LIKE '%" . $email . "%' OR  b.treatment_type LIKE '%" . $email . "%' OR  b.appoint_date LIKE '%" . $email . "%' OR b.follow_up_call_date  LIKE '%" . $email . "%') AND b.deleted=0 AND b.appoint_time IS NULL ORDER BY bookingid DESC";
            } else {
                if($contactid == 0) {
                    $query_check_credentials = "SELECT * FROM waitlist WHERE deleted=0 AND desired_date >= DATE(NOW()) ORDER BY therapistsid ASC, desired_date ASC";
                } else {
                    $query_check_credentials = "SELECT * FROM waitlist WHERE therapistsid='$contactid' AND deleted=0 AND desired_date >= DATE(NOW()) ORDER BY therapistsid ASC, desired_date ASC";
                }
            }

            $result = mysqli_query($dbc, $query_check_credentials);

            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {
            } else{
            	echo "<h2>No Record Found.</h2>";
            }
            $status_loop = '';
            while($row = mysqli_fetch_array( $result ))
            {
                if($row['therapistsid'] != $status_loop) {
                    echo "<table border='2' cellpadding='10' class='table'>";
                    echo '<tr>
                    <th>Desired Date</th>
                    <th>Waitlist ID</th>
                    <th>Patient</th>
                    <th>Add to Booking</th>
                    <th>Function</th>
                    ';
                    echo "</tr>";

                    //echo '<h3>' . $users['first_name']. ' '. $users['last_name'] . '</h3>';
                    echo '<h3>' . get_contact($dbc, $row['therapistsid']) . '</h3>';
                    $status_loop = $row['therapistsid'];
                }

                $back = '';
                if($row['desired_date'] == date('Y-m-d')) {
                    $back = 'style="background-color: rgba(255,0,0,0.4);"';
                }
                echo '<tr '.$back.'>';
                echo '<td>'.$row['desired_date'].'</td>';
                echo '<td>' . $row['waitlistid'] . '</td>';
                //echo '<td><a href="#"  onclick=" window.open(\''.WEBSITE_URL.'/Contact/add_contact.php?type=Patient&contactid='.$row['patientid'].'\', \'newwindow\', \'width=900, height=900\'); return false;">'.get_contact($dbc, $row['patientid']). '</a></td>';

                echo '<td>' . get_contact($dbc, $row['patientid']) . '</td>';

                echo '<td><a href=\'add_booking.php?patientid='.$row['patientid'].'&tid='.$row['therapistsid'].'&wid='.$row['waitlistid'].'\'>Add to Booking</a></td>';
                echo '<td>';
    			echo '<a href=\'add_waitlist.php?waitlistid='.$row['waitlistid'].'\'>Edit</a> | ';
				echo '<a href=\''.WEBSITE_URL.'/delete_restore.php?action=delete&waitlistid='.$row['waitlistid'].'\' onclick="return confirm(\'Are you sure?\')">Delete</a>';
				echo '</td>';

                //echo '<td>';
    			//echo '<a href=\'add_checkin_patient.php?bookingid='.$row['bookingid'].'\'>Check In</a>';
				//echo '</td>';

            	echo "</tr>";
            }

            echo '</table></div>';
            echo '<a class="btn brand-btn pull-right" href="'.WEBSITE_URL.'/Booking/add_waitlist.php">Add Waitlist</a>';
            ?>
            <a href="<?php echo WEBSITE_URL;?>/home.php" class="btn brand-btn">Back</a>

	</div>
</div>

<?php include ('../footer.php'); ?>