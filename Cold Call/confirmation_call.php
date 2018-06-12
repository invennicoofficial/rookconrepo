<?php
/*
EIS
*/
include ('../include.php');
checkAuthorised('calllog');

if (!empty($_GET['bookingid'])) {
    $bookingid = $_GET['bookingid'];
	$query_update_es = "UPDATE `booking` SET `call_today` = 1 WHERE `bookingid` = '$bookingid'";
	$result_update_es = mysqli_query($dbc, $query_update_es);
}
?>
<script type="text/javascript">
$(document).on('change', 'select[name="follow_up_call_status[]"]', function() { selectStatus(this); });
function appointDate(sel) {
	var action = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "<?php echo WEBSITE_URL;?>/ajax_all.php?fill=booking_appoint&id="+arr[1]+'&name='+action,
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
		}
	});
}
function followupDate(sel) {
	var action = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "<?php echo WEBSITE_URL;?>/ajax_all.php?fill=booking_followup&id="+arr[1]+'&name='+action,
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
		}
	});
}
function selectStatus(sel) {
	var status = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "<?php echo WEBSITE_URL;?>/ajax_all.php?fill=bookingstatus&id="+arr[1]+'&name='+status,
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
		}
	});
}
</script>
</head>
<body>

<?php include ('../navigation.php');

?>

<div class="container">
	<div class="row">

        <h1 class="double-pad-bottom">Cold Call</h1>
        <?php
        $class1 = '';
        $class2 = '';
        $class3 = '';
        $class4 = '';
        $class5 = '';
        $class6 = '';
        if($_GET['status'] == 'Booked Confirmed') {
            $class1 = 'active_tab';
            $follow_up_call_status = 'Booked Confirmed';
        }
        if($_GET['status'] == 'Cancelled') {
            $class2 = 'active_tab';
            $follow_up_call_status = 'Cancelled';
        }
        if($_GET['status'] == 'Leftmessage') {
            $class3 = 'active_tab';
            $follow_up_call_status = 'Call Again Left Message';
        }
        if($_GET['status'] == 'Nomessage') {
            $class4 = 'active_tab';
            $follow_up_call_status = 'Call Again No Message';
        }
        if($_GET['status'] == 'Rescheduled') {
            $class5 = 'active_tab';
            $follow_up_call_status = 'Rescheduled';
        }
        ?>
        <a href='confirmation_call.php?status=Booked Confirmed'><button type="button" class="btn brand-btn mobile-block <?php echo $class1; ?>" >Booked Confirmed</button></a>&nbsp;&nbsp;
        <a href='confirmation_call.php?status=Cancelled'><button type="button" class="btn brand-btn mobile-block <?php echo $class2; ?>" >Cancelled</button></a>&nbsp;&nbsp;
        <a href='confirmation_call.php?status=Leftmessage'><button type="button" class="btn brand-btn mobile-block <?php echo $class3; ?>" >Call Again Left Message</button></a>&nbsp;&nbsp;
        <a href='confirmation_call.php?status=Nomessage'><button type="button" class="btn brand-btn mobile-block <?php echo $class4; ?>" >Call Again No Message</button></a>&nbsp;&nbsp;
        <a href='confirmation_call.php?status=Rescheduled'><button type="button" class="btn brand-btn mobile-block <?php echo $class5; ?>" >Rescheduled</button></a>

        <a href='follow_up_calls.php'><button type="button" class="btn brand-btn mobile-block" >Follow Up Calls</button></a>

        <br><br>

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

            <span class="popover-examples list-inline pull-right">
                <a href="#job_file" data-toggle="tooltip" data-placement="top" title="Click star to mark call as completed."><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="30"></a>
            </span>

        <div class="table-responsive">

            <?php
            // Display Pager

            $email = '';
            if (isset($_POST['search_email_submit'])) {
                $email = $_POST['search_email'];
            }
            if (isset($_POST['display_all_email'])) {
                $email = '';
            }

            if($email != '') {
                $query_check_credentials = "SELECT b.*, p.first_name AS pf, p.last_name AS pl, s.first_name AS sf, s.last_name AS sl, p.home_phone FROM booking b, contacts s  WHERE b.patientid = s.contactid AND s.contactid = b.therapistsid AND DATE(b.follow_up_call_date) = DATE(NOW()) AND (p.first_name LIKE '%" . $email . "%' OR  p.last_name LIKE '%" . $email . "%' OR s.first_name LIKE '%" . $email . "%' OR  s.last_name LIKE '%" . $email . "%' OR  b.today_date LIKE '%" . $email . "%' OR  b.treatment_type LIKE '%" . $email . "%' OR  b.appoint_date LIKE '%" . $email . "%' OR b.follow_up_call_date  LIKE '%" . $email . "%') ORDER BY follow_up_call_status";
            } else {
                $query_check_credentials = "SELECT * FROM booking WHERE appoint_time IS NULL AND DATE(follow_up_call_date) = DATE(NOW()) AND follow_up_call_status = '$follow_up_call_status' ORDER BY follow_up_call_status";
            }

            $result = mysqli_query($dbc, $query_check_credentials);

            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {
            } else {
            	echo "<h2>No Record Found.</h2>";
            }
            $status_loop = '';
            while($row = mysqli_fetch_array( $result ))
            {
                if($row['follow_up_call_status'] != $status_loop) {
                        echo "<table class='table table-bordered'>";
                        echo "<tr class='hidden-xs hidden-sm'>
                        <th>Booking Date</th>
                        <th>Appointment Date</th>
                        <th>Patient</th>
                        <th>Patient Phone Number</th>
                        <th>Treatment Type</th>
                        <th>Document</th>
                        <th>Therapist</th>
                        <th>Follow Up Status</th>
                        <th>Notes</th>
                        <th>Call Today</th>
                        </tr>";

                    //echo '<h3>' . $users['first_name']. ' '. $users['last_name'] . '</h3>';
                    echo '<h3>'.$row['follow_up_call_status'].'</h3>';
                    $status_loop = $row['follow_up_call_status'];
                }

                echo '<tr>';
                echo '<td>' . $row['today_date'] . '</td>';
            	echo '<td>' . $row['appoint_date'] . '</td>';
                echo '<td><a href="'.WEBSITE_URL.'/Contacts/add_contacts.php?contactid='.$row['patientid'].'">'.get_contact($dbc, $row['patientid']). '</a></td>';

                echo '<td>' . get_contact_phone($dbc, $row['patientid']) . '</td>';
                echo '<td>' . $row['treatment_type'] . '</td>';

                echo '<td>';
                    if($row['upload_document'] != '') {
                    $file_names = explode('#$#', $row['upload_document']);
                    echo '<ul>';
                    foreach($file_names as $file_name) {
                        if($file_name != '') {
                            echo '<li><a href="Download/'.$file_name.'" target="_blank">'.$file_name.'</a></li>';
                        }
                    }
                    echo '</ul>';
                } else {
                    echo '-';
                }
                echo '</td>';

            	echo '<td>' . get_contact($dbc, $row['therapistsid']) . '</td>';
                ?>
                <td data-title="Status">
                    <select data-placeholder="Choose a Status..." name="follow_up_call_status[]" id="status_<?php echo $row['bookingid']; ?>" class="chosen-select-deselect form-control input-sm">
                        <option value=""></option>
                        <option value="Booked Confirmed" <?php if ($row['follow_up_call_status'] == "Booked Confirmed") { echo " selected"; } ?> >Booked Confirmed</option>
                        <option value="Cancelled" <?php if ($row['follow_up_call_status'] == "Cancelled") { echo " selected"; } ?> >Cancelled</option>
                        <option value="Call Again Left Message" <?php if ($row['follow_up_call_status'] == "Call Again Left Message") { echo " selected"; } ?> >Call Again Left Message</option>
                        <option value="Call Again No Message" <?php if ($row['follow_up_call_status'] == "Call Again No Message") { echo " selected"; } ?> >Call Again No Message</option>
                        <option value="Rescheduled" <?php if ($row['follow_up_call_status'] == "Rescheduled") { echo " selected"; } ?> >Rescheduled</option>
                    </select>
                </td>

                <?php
                $bookingid = $row['bookingid'];
                $result_comment = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT count(commentid) AS total_comment FROM comment WHERE fromid='$bookingid' AND from_page='confirmation_call'"));

                $comment = $result_comment['total_comment'];

                echo '<td data-title="Comments"><a href="comment.php?from=confirmation_call&fromid='.$bookingid.'">'.$comment.'</a></td>';

                echo '<td>';
                if($row['call_today'] == 0) {
                    echo '<a href="confirmation_call.php?status='.$_GET['status'].'&bookingid='.$row['bookingid'].'"><img src="'.WEBSITE_URL.'/img/blank_star.png" onclick="return confirm(\'Are you sure?\')" width="32" height="32" border="0" alt=""></a>';
                } else {
                    echo '<img src="'.WEBSITE_URL.'/img/filled_star.png" width="32" height="32" border="0" alt="">';
                }
                echo '</td>';

            	echo "</tr>";
            }

            echo '</table></div>';
            ?>

            <a href="<?php echo WEBSITE_URL;?>/home.php" class="btn brand-btn">Back</a>

	</div>
</div>

<?php include ('../footer.php'); ?>