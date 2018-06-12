<?php
/*
EIS
*/
include ('../include.php');
checkAuthorised('booking');
?>
<script type="text/javascript">
$(document).ready(function(){
    $( ".appointdatetimepicker" ).datetimepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: '1960:2025',
		dateFormat: 'yy-mm-dd',
		minuteGrid: 15,
		hourMin: 8,
		hourMax: 16,
        minDate: 0,
		onClose: function(dateText, inst) {
             appointDate(dateText,inst);
		}
    });
	$('.iframe_open').click(function(){

			var id = $(this).attr('id');
		   $('#iframe_instead_of_window').attr('src', '<?php echo WEBSITE_URL; ?>/Booking/waitlist.php?contactid='+id);
		   $('.iframe_title').text('Waitlist');

			$('.iframe_holder').show(1000);
			$('.hide_on_iframe').hide(1000);
	});

	$('.close_iframer').click(function(){
		var result = confirm("Are you sure you want to close this window?");
		if (result) {
			$('.iframe_holder').hide(1000);
			$('.hide_on_iframe').show(1000);
			location.reload();
		}
	});
});

function appointDate(dateVal,sel) {
	var action = dateVal;
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
</script>
</head>
<body>

<?php include ('../navigation.php');

?>

<div class="container">
	<div class='iframe_holder' style='display:none;'>

		<img src='<?php echo WEBSITE_URL; ?>/img/icons/close.png' class='close_iframer' width="45px" style='position:relative; right: 10px; float:right;top:58px; cursor:pointer;'>
		<span class='iframe_title' style=' font-weight:bold; position: relative; left: 20px; font-size: 30px;'></span>
		<iframe id="iframe_instead_of_window" style='width: 100%;' height="1000px; border:0;" src=""></iframe>
    </div>
	<div class="row hide_on_iframe">

        <h1 class="">Booking Dashboard
        <?php
            echo '<a href="config_booking.php?contactid='.$_GET['contactid'].'" class="mobile-block pull-right"><img style="width:50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>';
        ?>
        </h1>

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

            <br>
            <span class="pull-right"><img src="../img/green.png" width="32" height="32" border="0" alt=""> Follow Up Call Today &nbsp;&nbsp;
            <img src="../img/blue.png" width="32" height="32" border="0" alt=""> Appointment Today&nbsp;&nbsp;
            <img src="../img/red.png" width="32" height="32" border="0" alt=""> Appointment Cancelled/No-Show</span>

            <br><br>

            <?php
            $therapistsid_booking = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT therapistsid FROM waitlist WHERE deleted=0 ORDER BY therapistsid LIMIT 1"));

            echo '<div class="pull-right">';
				echo '<span class="popover-examples list-inline" style="margin:0 5px 0 15px;"><a data-toggle="tooltip" data-placement="top" title="Click here to add a Booking."><img src="'. WEBSITE_URL .'/img/info.png" width="20"></a></span>';
				echo '<a href="add_booking.php" class="btn brand-btn">Add Booking</a>';
				echo '<span class="popover-examples list-inline" style="margin:0 5px 0 10px;"><a data-toggle="tooltip" data-placement="top" title="View the current Waitlist and/or add a new Waitlist entry."><img src="'. WEBSITE_URL .'/img/info.png" width="20"></a></span>';
				echo '<a class="btn brand-btn iframe_open" style="display:inline;" id="'.$therapistsid_booking['therapistsid'].'">Waitlist</a>';
				//echo '<a class="btn brand-btn pull-right" onclick=" window.open(\''.WEBSITE_URL.'/Booking/waitlist.php?contactid='.$therapistsid_booking['therapistsid'].'\', \'newwindow\', \'width=900, height=900\'); return false;">Waitlist</a>';
			echo '</div>';
            ?>
        <div class="table-responsive">
            <?php

            $contactid = $_GET['contactid'];
            $tabs = mysqli_query($dbc, "SELECT distinct(therapistsid) FROM booking b, contacts c WHERE b.deleted=0 AND c.contactid=b.therapistsid AND c.deleted=0 AND b.appoint_time IS NULL AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) = DATE(NOW()) ORDER BY therapistsid");
            while($row_tab = mysqli_fetch_array( $tabs )) {
                $class='';
                $therapistsid = $row_tab['therapistsid'];
                if($therapistsid == $_GET['contactid']) {
                    $class= 'active_tab';
                }
                echo '<a href="booking.php?contactid='.$therapistsid.'"><button type="button" class="btn brand-btn mobile-block '.$class.'" >'.get_contact($dbc, $therapistsid).'</button></a>&nbsp;&nbsp;';
            }
            $class = '';
            if('0' == $_GET['contactid']) {
                $class= 'active_tab';
            }
            echo '<a href="booking.php?contactid=0"><button type="button" class="btn brand-btn mobile-block '.$class.'" >View All</button></a>&nbsp;&nbsp;';
            ?>

            <?php
            $email = '';
            if (isset($_POST['search_email_submit'])) {
                $email = $_POST['search_email'];
            }
            if (isset($_POST['display_all_email'])) {
                $email = '';
            }

            /* Pagination Counting */
            $rowsPerPage = 25;
            $pageNum = 1;

            if(isset($_GET['page'])) {
                $pageNum = $_GET['page'];
            }

            $offset = ($pageNum - 1) * $rowsPerPage;

            if($email != '') {
                $query_check_credentials = "SELECT * FROM booking WHERE therapistsid='$contactid' AND (today_date LIKE '%" . $email . "%' OR treatment_type LIKE '%" . $email . "%' OR  appoint_date LIKE '%" . $email . "%' OR follow_up_call_date  LIKE '%" . $email . "%') AND deleted=0 AND appoint_time IS NULL AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) = DATE(NOW()) ORDER BY bookingid DESC LIMIT $offset, $rowsPerPage";
                $query = "SELET count(*) as numrows FROM booking WHERE therapistsid='$contactid' AND (today_date LIKE '%" . $email . "%' OR treatment_type LIKE '%" . $email . "%' OR  appoint_date LIKE '%" . $email . "%' OR follow_up_call_date  LIKE '%" . $email . "%') AND deleted=0 AND appoint_time IS NULL ORDER BY bookingid DESC";
                $result1 = mysqli_query($dbc, $query_check_credentials);

                $num_rows1 = mysqli_num_rows($result1);
                if($num_rows1 == 0) {
                    $query_check_credentials = "SELECT * FROM booking WHERE therapistsid='$contactid' AND type!='E' AND type !='I' AND deleted=0 AND appoint_time IS NULL AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) = DATE(NOW()) ORDER BY therapistsid,appoint_date LIMIT $offset, $rowsPerPage";
                    $query = "SELECT count(*) as numrows FROM booking WHERE therapistsid='$contactid' AND type!='E' AND type !='I' AND deleted=0 AND appoint_time IS NULL ORDER BY therapistsid,appoint_date";
                }
            } else {
                if($contactid == 0) {
                    //$query_check_credentials = "SELECT * FROM booking WHERE deleted=0 AND appoint_time IS NULL ORDER BY therapistsid,appoint_date";

                    $query_check_credentials = "SELECT * FROM booking WHERE deleted=0 AND type!='E' AND type !='I' AND appoint_time IS NULL AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) = DATE(NOW()) ORDER BY therapistsid,appoint_date LIMIT $offset, $rowsPerPage";
                    $query = "SELECT count(*) as numrows FROM booking WHERE deleted=0 AND type!='E' AND type !='I' AND appoint_time IS NULL ORDER BY therapistsid,appoint_date";

                } else {
                    $query_check_credentials = "SELECT * FROM booking WHERE therapistsid='$contactid' AND type!='E' AND type !='I' AND deleted=0 AND appoint_time IS NULL AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) = DATE(NOW()) ORDER BY therapistsid,appoint_date LIMIT $offset, $rowsPerPage";
                    $query = "SELECT count(*) as numrows FROM booking WHERE therapistsid='$contactid' AND type!='E' AND type !='I' AND deleted=0 AND appoint_time IS NULL ORDER BY therapistsid,appoint_date";
                }
            }

            $result = mysqli_query($dbc, $query_check_credentials);

            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {
            } else{
            	echo "<h2>No Record Found.</h2>";
            }
            $status_loop = '';

            // Added Pagination //
            echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
            // Pagination Finish //

            while($row = mysqli_fetch_array( $result ))
            {
                $patientid = $row['patientid'];
                $first_name = get_all_form_contact($dbc, $patientid, 'first_name');
                $last_name = get_all_form_contact($dbc, $patientid, 'last_name');

                if ($first_name == $email || $last_name == $email || $email == '') {
                    if($row['therapistsid'] != $status_loop) {
                        echo "<table border='2' cellpadding='10' class='table'>";
                        echo '<tr>
                        <th>Appointment Date & Time</th>
                        <th>Booking ID</th>
                        <th>Booking Date</th>
                        <th>Patient</th>
                        <th>Injury</th>
                        <th>Document</th>
                        <th>Follow Up Call</th>
                        <th>Status</th>
                        <th>Checkin</th>
                        <th>Function</th>
                        ';
                        echo "</tr>";

                        //echo '<h3>' . $users['first_name']. ' '. $users['last_name'] . '</h3>';
                        echo '<h3>' . get_contact($dbc, $row['therapistsid']) . '</h3>';
                        $status_loop = $row['therapistsid'];
                    }

                    $back = '';
                    $just_date = explode(' ', $row['appoint_date']);
                    $today = 0;
                    if($row['follow_up_call_date'] == date('Y-m-d')) {
                        $back = 'style="background-color: rgba(0,255,0,0.4);"';
                    }
                    if($just_date[0] == date('Y-m-d')) {
                        $back = 'style="background-color: rgba(0,0,255,0.4);"';
                        $today = 1;
                    }
                    if ((strtotime("now")) >= strtotime($row['appoint_date'])) {
                        $back = 'style="background-color: rgba(255,0,0,0.4);"';
                    }
                    echo '<tr '.$back.'>';
                    echo '<td>';
                    echo $row['appoint_date'].'<br>'.$row['end_appoint_date'];
                    echo '</td>';
                    echo '<td>' . $row['bookingid'] . '</td>';
                    echo '<td>' . $row['today_date'] . '</td>';

                    echo '<td>'.get_contact($dbc, $row['patientid']). '</td>';
					//<a href="#"  onclick=" window.open(\''.WEBSITE_URL.'/Contact/add_contact.php?type=Patient&contactid='.$row['patientid'].'\', \'newwindow\', \'width=900, height=900\'); return false;">'.get_contact($dbc, $row['patientid']). '</a>

                    echo '<td>' . get_all_from_injury($dbc, $row['injuryid'], 'injury_name').' : '.get_all_from_injury($dbc, $row['injuryid'], 'injury_type') . '</td>';
                    echo '<td>';
                    if($row['upload_document'] != '') {
                        $file_names = explode('#$#', $row['upload_document']);
                        $file_names_md5 = explode('#*FFM*#', $row['upload_document_md5']);
                        echo '<ul>';
                        $i=0;
                        foreach($file_names as $file_name) {
                            if($file_name != '') {
                                $md5 = md5_file("Download/".$file_name);
                                if($md5 == $file_names_md5[$i]) {
                                    echo '<li><a href="Download/'.$file_name.'" target="_blank">'.$file_name.'</a></li>';
                                } else {
                                    echo '<li>'.$file_name.' (Error : File Change)</li>';
                                }
                            }
                            $i++;
                        }
                        echo '</ul>';
                    } else {
                        echo '-';
                    }
                    echo '</td>';
                    ?>
                    <td data-title="Reminder Date">
                        <input name="follow_up_call_date" onchange="followupDate(this)" id="followupdate_<?php echo $row['bookingid']; ?>" value="<?php echo $row['follow_up_call_date']; ?>" type="text" placeholder="Click for Datepicker" class="datefuturepicker">
                    </td>
                    <?php
                    $bookingid = $row['bookingid'];
                    echo '<td>' . $row['follow_up_call_status'] . '</td>';
                    echo '<td>';
                    if($today == 1) {
                        echo '<a href=\''.WEBSITE_URL.'/Check In/add_checkin_patient.php?bookingid='.$row['bookingid'].'\'>Check In</a>';
                    } else {
                        echo '-';
                    }
                    echo '</td>';

                    echo '<td>';
                    echo '<a href=\'add_booking.php?bookingid='.$row['bookingid'].'\'>Edit</a> | ';
                    echo '<a href=\''.WEBSITE_URL.'/delete_restore.php?action=delete&bookingid='.$row['bookingid'].'\' onclick="return confirm(\'Are you sure?\')">Delete</a>';
                    echo '</td>';
                    echo "</tr>";
                }
            }

            echo '</table></div>';

            // Added Pagination //
            echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
            // Pagination Finish //

			echo '<a href="add_booking.php" class="btn brand-btn pull-right">Add Booking</a>';
            ?>
            <a href="<?php echo WEBSITE_URL;?>/home.php" class="btn brand-btn">Back</a>

	</div>
</div>

<?php include ('../footer.php'); ?>
