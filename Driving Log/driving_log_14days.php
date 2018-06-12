<?php
/*
equipments Listing
*/



include ('../include.php');
checkAuthorised('driving_log');

$view_only_mode = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `driving_log_view_only_mode` WHERE `contactid` = '".$_SESSION['contactid']."'"))['view_only_mode'];
include_once('view_only_mode.php');

if (isset($_POST['send_drive_logs'])) {
	error_reporting(0);
	$email_list = $_POST['email_list'];


    if ($email_list !== '' && $_POST['pdf_send'] !== null) {

			$emails_arr = explode( ',', $email_list );

			foreach( $emails_arr as $email )
			{
				if (!filter_var(trim($email), FILTER_VALIDATE_EMAIL) === false) {

				} else {
					 echo '<script type="text/javascript"> alert("One or more of the email addresses you have provided is not a proper email address.");
							window.location.replace("driving_log_14days.php"); </script>';
							exit();
				}
			}

	/*$drivinglogid = $_GET['drivinglogid'];
    $get_dl = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM driving_log WHERE drivinglogid='$drivinglogid'"));

    $driverid = $get_dl['driverid'];
    $get_driver = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT first_name, last_name FROM staff WHERE staffid='$driverid'"));
    $driver = $get_driver['first_name'].' '.$get_driver['last_name'];

    $codriverid = $get_dl['codriverid'];
    $get_codriver = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT first_name, last_name  FROM staff WHERE staffid='$codriverid'"));
    $codriver = $get_codriver['first_name'].' '.$get_codriver['last_name'];

    $clientid = $get_dl['clientid'];
    $get_client = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT company_name FROM clients WHERE clientid='$clientid'"));
    $client = $get_client['company_name'];

    $vehicleid = $get_dl['vehicleid'];
    $get_vehicle = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT equipmentid, unit_number, serial_number, model FROM equipment WHERE equipmentid='$vehicleid'"));
    $vehicle = '#'.$get_vehicle['unit_number'];

    $trailerid = $get_dl['trailerid'];
    $get_trailer = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT equipmentid, unit_number, serial_number, model FROM equipment WHERE equipmentid='$trailerid'"));
    $trailer = '#'.$get_trailer['unit_number'];

    $get_km = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT begin_odo_kms, final_odo_kms FROM driving_log_safety_inspect WHERE drivinglogid='$drivinglogid'"));
    $total_km = $get_km['final_odo_kms']-$get_km['begin_odo_kms'];

    $graph_url = $_SERVER['SERVER_NAME'].'/amendments.php?from=14days&graph=on&drivinglogid='.$drivinglogid;*/

		//EMAIL
	$to_email = $email_list;
	$to = explode(',', $to_email);
	$subject = $_POST['email_subject'];
	$message = $_POST['email_body'];
	/*$message = '<html><body>';
    $message .=	'<b>Log# : </b>'.$drivinglogid.'<br/>';
    $message .=	'<b>Driver : </b>'.$driver.'<br/>';
    $message .=	'<b>Co-Driver : </b>'.$codriver.'<br/>';
    $message .=	'<b>Customer : </b>'.$client.'<br/>';
    if($vehicleid != 0) {
        $message .=	'<b>Vehicle : </b>'.$vehicle.'<br/>';
        $message .=	'<b>Trailer : </b>'.$trailer.'<br/>';
    }
    $message .=	'<b>Start Date : </b>'.$get_dl['start_date'].'<br/>';
    $message .=	'<b>End Date : </b>'.$get_dl['end_date'].'<br/>';
    $message .=	'<b>Total KM : </b>'.$total_km.'<br/>';
    $message .=	'<b>View Graph : </b><a target="_blank" href="'.$graph_url.'">View</a><br/>';
	$message .= $email_list;
    $message .=	'</body></html>';*/
	 $meeting_attachment = '';
        foreach($_POST['pdf_send'] as $drivinglogid) {
            if($drivinglogid != '') {
				$get_pdf = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM driving_log WHERE drivinglogid='$drivinglogid'"));
                $meeting_attachment .= 'download/'.$get_pdf['pdf'].'*#FFM#*';
            }
        }

        send_email([$_POST['email_sender']=>$_POST['email_name']], $to, '', '', $subject, $message, $meeting_attachment);


	//Junk code below - use at your own discretion.

	// array with filenames to be sent as attachment
	/*print_r($pdf);

	$pdf2 = '"download/projectdetail_1_2016-05-12.pdf","download/projectdetail_2_2016-05-17.pdf","download/projectdetail_4_2016-05-31.pdf"';
	echo '<br>'.$pdf2;
	$files = array("download/projectdetail_1_2016-05-12.pdf","download/projectdetail_2_2016-05-17.pdf","download/projectdetail_4_2016-05-31.pdf");

	// email fields: to, from, subject, and so on
$to = "kelseynealon@freshfocusmedia.com";
$from = "kelseynealon@freshfocusmedia.com";
$subject ="My subject";
$message = "My message";
$headers = "From: $from";

	// boundary
$semi_rand = md5(time());
$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

	// headers for attachment
$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";

	// multipart boundary
$message = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type: text/plain; charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n";
$message .= "--{$mime_boundary}\n";

	// preparing attachments
for($x=0;$x<count($files);$x++){
	$file = fopen($files[$x],"rb");
	$data = fread($file,filesize($files[$x]));
	fclose($file);
	$data = chunk_split(base64_encode($data));
	$message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$files[$x]\"\n" .
	"Content-Disposition: attachment;\n" . " filename=\"$files[$x]\"\n" .
	"Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
	$message .= "--{$mime_boundary}\n";
}

// send

$ok = @mail($to, $subject, $message, $headers);
if ($ok) {
	echo "<p>mail sent to $to!</p>";
} else {
	echo "<p>mail could not be sent!</p>";
}
 */
?><?php


	/*$to = 'kelseynealon@freshfocusmedia.com';

    $subject = 'Hydrera Driving Log #'.$drivinglogid;

    $headers .=	"MIME-Version: 1.0\r\n";
    $headers .=	"Content-Type: text/html; charset=ISO-8859-1\r\n";

    $message = '<html><body>';
    $message .=	'<b>Log# : </b>'.$drivinglogid.'<br/>';
    $message .=	'<b>Driver : </b>'.$driver.'<br/>';
    $message .=	'<b>Co-Driver : </b>'.$codriver.'<br/>';
    $message .=	'<b>Customer : </b>'.$client.'<br/>';
    if($vehicleid != 0) {
        $message .=	'<b>Vehicle : </b>'.$vehicle.'<br/>';
        $message .=	'<b>Trailer : </b>'.$trailer.'<br/>';
    }
    $message .=	'<b>Start Date : </b>'.$get_dl['start_date'].'<br/>';
    $message .=	'<b>End Date : </b>'.$get_dl['end_date'].'<br/>';
    $message .=	'<b>Total KM : </b>'.$total_km.'<br/>';
    $message .=	'<b>View Graph : </b><a target="_blank" href="'.$graph_url.'">View</a><br/>';
	$message .= $email_list;
    $message .=	'</body></html>';

    mail($to, $subject,	$message, $headers);

	$to = 'kelseynealon@freshfocusmedia.com';
				$from = "no-reply@freshfocusmedia.com";
		        $subject =  'Driving Log PDF Attachment - '.$lname.', '.$fname.'';
				//$message = "Please see attachment";
				$headers = "From: $from";

				// boundary
				$semi_rand = md5(time());
				$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

				// headers for attachment
				$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";

				// multipart boundary
				$message = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type: text/plain; charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n";
				$message .= "--{$mime_boundary}\n";

				$message .= 'Please see the attachment(s) below.';

				// Send ticket in email
				$ticket_file = '5ave_general_registration_'.$applicationid.'.pdf';
				$filename = basename($ticket_file);
				$file = fopen($ticket_file,"rb");
				$data = fread($file,filesize($ticket_file));
				fclose($file);
				$data = chunk_split(base64_encode($data));
				$message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$ticket_file\"\n" .
				"Content-Disposition: attachment;\n" . " filename=\"$filename\"\n" .
				"Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
				$message .= "--{$mime_boundary}\n";
				// Send ticket in email
                $ok = mail($to, $subject, $message, $headers);*/

    echo '<script type="text/javascript"> alert("Driving Log Sent to '.$email_list.'.");
	window.location.replace("driving_log_14days.php"); </script>';
	} else {
	echo '<script type="text/javascript"> alert("Please enter at least 1 email address, or make sure you have selected a Driving Log to send.");
	window.location.replace("driving_log_14days.php"); </script>';
	}
}
?><style>.selectbutton {
	cursor: pointer;
	text-decoration: underline;
}
@media (min-width: 801px) {
	.sel2 {
		display:none;
	}
}
	</style>
<script src="js/jquery.cookie.js"></script>
<script type="text/javascript">
    $(document).ready(function () {

		function empty() {
			var x;
			x = document.getElementById("roll-input").value;
			if (x == "") {
				alert("Enter at least one email address.");
				return false;
			};
		}
		$(".ser-sch").hide();
        $(".ser-sch-btn").click(function() {
            $(this).next().show();
			$('html, body').animate({ scrollTop: 0 }, 'fast');
        });

		$(".close-btn").click(function() {
            $(".ser-sch").hide();
        });

		$('.selectall').click(
        function() {
			if($('.selectall').hasClass("deselectall")) {
				$(".selectall").removeClass('deselectall');
				$('.pdf_send').prop('checked', false);
				$(".selectall").text('Select all');
				$('.selectall').prop('title', 'This will select all rows on the current page.');
			} else {
				$(".selectall").addClass('deselectall');
				$('.pdf_send').prop('checked', true);
				$(".selectall").text('Deselect all');
				$('.selectall').prop('title', 'This will deselect all rows on the current page.');
			}

		});
});
</script>
</head>
<body>
<?php include_once ('../navigation.php');
?>

<div class="container">
	<div class="row">
        <div class="col-md-12">
        <h1 style="display: inline;">Completed 14 Day Driving Logs</h1>
        <div class="pull-right">View Only: <a href="" class="view_only_button"><img src="../img/icons/switch-<?= $view_only_mode == 1 ? '7' : '6' ?>.png" style="height: 2em;"></a></div>

		<div class="gap-top triple-gap-bottom"><a href="driving_log_tiles.php" class="btn config-btn">Back to Dashboard</a></div>
        
        <div class="notice double-gap-bottom popover-examples">
			<div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25px"></div>
			<div class="col-sm-16"><span class="notice-name">NOTE:</span> This section displays the completed Driving Logs summary. In this section you can search by Driver and Co-Driver to see a Driving Log summary. To do this, use the dropdown menu beside Search By Driver &amp; Co-Driver and click Search. If you would like to see the entire Completed Driving Log, click Display All.</div>
		</div>

        <form name="form_sites" method="post" action="" class="form-horizontal" role="form">
            <div id="no-more-tables">
				<?php if(strpos(','.ROLE.',',',super,') !== false || strpos(','.ROLE.',',',admin,') !== false) { ?>

                <div class="form-group">
                    <label for="site_name" class="col-sm-4 control-label">Search By Driver & Co-Driver:</label>
					<div class="col-sm-8" style="width:auto">
                        <select data-placeholder="Select Driver/Co-Driver" name="search_vendor" class="chosen-select-deselect form-control">
                            <option></option>
                            <?php
                            $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 AND `status`>0"),MYSQLI_ASSOC));
                            foreach($query as $id) {
                                $selected = '';
                                $selected = $id == $_POST['search_vendor'] ? 'selected = "selected"' : '';
                                echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
                            }
                            ?>
                        </select>
					</div>

                    <button type="submit" name="search_vendor_submit" value="Search" class="btn brand-btn">Search</button>
                    <button type="submit" name="display_all_vendor" value="Display All" class="btn brand-btn">Display All</button>
                </div>
                <?php } ?>
                <?php if (!empty($_POST['search_vendor'])) {
                        $curr_driverid = $_POST['search_vendor'];
                } else {
                    $curr_driverid = $_SESSION['contactid'];
                } ?>
                <h3>14 Day Summary for <?= get_contact($dbc, $curr_driverid) ?></h3>
                <table class="table table-bordered">
                    <tr class="hidden-xs hidden-sm">
                        <th>Date</th>
                        <th>Off-Duty</th>
                        <th>Sleeper Berth</th>
                        <th>Driving</th>
                        <th>On-Duty</th>
                    </tr>
                    <?php
                        $two_week_summary = date('Y-m-d', strtotime(date('Y-m-d').' - 13 days'));
                        for ($counter = 0; $counter < 14; $counter++) {
                            $curr_date = date('Y-m-d', strtotime($two_week_summary.' + '.$counter.' days'));
                            $query_day = "SELECT * FROM `driving_log` WHERE driverid = '$curr_driverid' AND `start_date` = '$curr_date'";
                            $result_day = mysqli_fetch_assoc(mysqli_query($dbc, $query_day));
                            $drivinglogid = $result_day['drivinglogid'];
                            if (!empty($drivinglogid)) {
                                include('get_timers.php');
                            } else {
                                $off_h_time = '00:00';
                                $sleep_h_time = '00:00';
                                $drive_h_time = '00:00';
                                $on_h_time = '00:00';
                            } ?>
                            <tr>
                                <td data-title="Date"><?= $curr_date ?></td>
                                <td data-title="Off-Duty"><?= $off_h_time ?></td>
                                <td data-title="Sleeper Berth"><?= $sleep_h_time ?></td>
                                <td data-title="Driving"><?= $drive_h_time ?></td>
                                <td data-title="On-Duty"><?= $on_h_time ?></td>
                            </tr>
                        <?php }
                    ?>
                </table>

				<br><br>
                <?php if ($view_only_mode != 1) { ?>
                <h3>Email Driving Log PDFs</h3>
				<div class="form-group" style="width:100%">
					<label class="col-sm-4 control-label">Emails (Separated by a Comma):</label>
					<div class="col-sm-8">
						<input type="text" id='roll-input' name="email_list" placeholder='Enter emails here...' class="form-control email_driving_logs" value="">
					</div>
				</div>
				<div class="form-group" style="width:100%">
					<label class="col-sm-4 control-label">Sending Email Address:</label>
					<div class="col-sm-8">
						<input type="text" name="email_name" class="form-control" value="<?php echo get_contact($dbc, $_SESSION['contactid']); ?>">
					</div>
				</div>
				<div class="form-group" style="width:100%">
					<label class="col-sm-4 control-label">Sending Email Address:</label>
					<div class="col-sm-8">
						<input type="text" name="email_sender" class="form-control" value="<?php echo get_email($dbc, $_SESSION['contactid']); ?>">
					</div>
				</div>
				<div class="form-group" style="width:100%">
					<label class="col-sm-4 control-label">Email Subject:</label>
					<div class="col-sm-8">
						<input type="text" name="email_subject" class="form-control" value="Driving Log PDF(s)">
					</div>
				</div>
				<div class="form-group" style="width:100%">
					<label class="col-sm-4 control-label">Email Body:</label>
					<div class="col-sm-8">
						<textarea name="email_body" class="form-control">Please see the attached Driving Log PDF(s) below.</textarea>
					</div>
				</div>
                <div class="pull-right">
                    <span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here to send the Driving Log(s)"><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				    <button onClick="return empty()" type='submit' name='send_drive_logs' class='btn brand-btn dl_send_butt'>Send Driving Logs</button>
                </div>
				<div class='selectall selectbutton sel2' title='This will select all PDFs on the current page.'>Select All</div>
				<br><br>
                <?php }
                $vendor = '';
                if (isset($_POST['search_vendor_submit'])) {
                    if (isset($_POST['search_vendor'])) {
                        $vendor = filter_var($_POST['search_vendor'],FILTER_SANITIZE_STRING);
                    }
                }
                if (isset($_POST['display_all_vendor'])) {
                    $vendor = '';
                }

                if($vendor != '') {
                    $query_check_credentials = "SELECT * FROM driving_log WHERE status='Done' AND (driverid = '".$vendor."' OR codriverid = '".$vendor."') AND (start_date BETWEEN CURDATE() - INTERVAL 14 DAY AND CURDATE()) ORDER BY drivinglogid DESC";
                } else if(strpos(','.ROLE.',',',super,') !== false || strpos(','.ROLE.',',',admin,') !== false) {
                    $query_check_credentials = "SELECT * FROM driving_log WHERE status='Done' AND (start_date BETWEEN CURDATE() - INTERVAL 14 DAY AND CURDATE()) ORDER BY drivinglogid DESC";
                } else {
					$query_check_credentials = "SELECT * FROM driving_log WHERE status='Done' AND (driverid = '".$_SESSION['contactid']."' OR codriverid = '".$_SESSION['contactid']."') AND (start_date BETWEEN CURDATE() - INTERVAL 14 DAY AND CURDATE()) ORDER BY drivinglogid DESC";
				}
                $result = mysqli_query($dbc, $query_check_credentials);

                $num_rows = mysqli_num_rows($result);
                echo "<h3>Driving Logs</h3>";
                if($num_rows > 0) {
                echo "<table class='table table-bordered'>";
                echo "<tr class='hidden-xs hidden-sm'>
                        <th>
							<span class='popover-examples list-inline' style='margin:0 5px 0 0;' id='dl_off_comment_i'>
								<a data-toggle='tooltip' data-placement='top' title='This is the log number used to record and search driving logs.'><img src='" . WEBSITE_URL . "/img/info-w.png' width='20'></a>
							</span>
							Log#
						</th>
                        <th>Driver</th>
                        <th>Co-Driver</th>
                        <th>Customer</th>
                        <th>Vehicle</th>
                        <th>Trailer</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Total KM</th>
                        <th>Safety Inspect</th>
                        <th>
							<span class='popover-examples list-inline' style='margin:0 5px 0 0;' id='dl_off_comment_i'>
								<a data-toggle='tooltip' data-placement='top' title='Select Amendments to go to the end of day overview that the driver signs off on. Select Open PDF to view and print the PDF version of the overview.'><img src='" . WEBSITE_URL . "/img/info.png' width='20'></a>
							</span>
							View PDF/Amendments
						</th>";
						if ($view_only_mode != 1) { ?>
                            <th>Email PDF<br><div class='selectall selectbutton' title='This will select all PDFs on the current page.'>Select All</div></th><?php
                        }
                         echo "</tr>";
                } else {
                    echo "<h2>No Record Found.</h2>";
                }
                while($row = mysqli_fetch_array( $result ))
                {
                    $drivinglogid = $row['drivinglogid'];

                    $get_checklists = mysqli_fetch_all(mysqli_query($dbc,"SELECT * FROM driving_log_safety_inspect WHERE drivinglogid='$drivinglogid'"),MYSQLI_ASSOC);

                    echo '<tr>';
                    echo '<td data-title="Log#">' . $row['drivinglogid'] . '</td>';
                    echo '<td data-title="Driver">' . get_staff($dbc, $row['driverid']) . '</td>';

                    echo '<td data-title="Co-Driver">' . get_staff($dbc, $row['codriverid']) . '</td>';

                    echo '<td data-title="Customer">' .  get_client($dbc, $row['clientid']) . '</td>';

                    if (count($get_checklists) > 1) {
                        echo '<td data-title="Vehicle">';
                        foreach ($get_checklists as $checklist) {
                            echo '#'.get_equipment_field($dbc, $checklist['safety_inspect_vehicleid'], 'unit_number').'<br />';
                        }
                        echo '</td>';
                        echo '<td data-title="Trailer">';
                        foreach ($get_checklists as $checklist) {
                            echo '#'.get_equipment_field($dbc, $checklist['safety_inspect_trailerid'], 'unit_number').'<br />';
                        }
                        echo '</td>';
                    } else {
                        echo '<td data-title="Vehicle">#' . get_equipment_field($dbc, $row['vehicleid'], 'unit_number') . '</td>';
                        echo '<td data-title="Trailer">#' . get_equipment_field($dbc, $row['trailerid'], 'unit_number') . '</td>';
                    }

                    echo '<td data-title="Start Date">' . $row['start_date'] . '</td>';
                    echo '<td data-title="End Date">' . $row['end_date'] . '</td>';

                    if($row['end_date'] != '') {
                        echo '<td data-title="Total KM">';
                        foreach ($get_checklists as $checklist) {
                            $total_km = $checklist['final_odo_kms'] - $checklist['begin_odo_kms'];
                            echo $checklist['final_odo_kms'] - $checklist['begin_odo_kms'].'<br />';
                        }
                        echo '</td>';
                    } else {
                        echo '<td data-title="Total KM">-</td>';
                    }

                    if($row['vehicleid'] != 0) {
                        echo '<td data-title="Safety Inspect">';
                        foreach ($get_checklists as $checklist) {
                            echo '<a href="driving_log.php?safetyinspectid='.$checklist['safetyinspectid'].'&from_url='.$_SERVER['REQUEST_URI'].'">View</a><br />';
                        }
                        echo '</td>';
                    } else {
                        echo '<td data-title="Safety Inspect">-</td>';
                    }

                    $last_timer_value = $row['last_timer_value'];
                    echo '<td data-title="View">';
					$projectid = $row['drivinglogid'].'_'.$row['start_date'];
					if($row['pdf'] !== NULL && $row['pdf'] !== '' && $row['pdf'] !== null) {
						$view_pdf = '<a target="_blank" href=\'download/'.$row['pdf'].'\'>Open PDF</a><br>';
					} else {
						$view_pdf = '<a href=\'amendments.php?from=14days&graph=on&drivinglogid='.$row['drivinglogid'].'\'>Create PDF</a><br>';
					}
                    echo '<a href=\'amendments.php?from=14days&graph=on&drivinglogid='.$row['drivinglogid'].'\'>Amendments</a><br>'.$view_pdf;
                    echo '</td>';

                    if ($view_only_mode != 1) {
                        echo '<td data-title="Email PDF">';
    					if($row['pdf'] !== NULL && $row['pdf'] !== '' && $row['pdf'] !== null) {
    					?><input style="height: 25px; width: 25px;" type='checkbox' name='pdf_send[]' class='pdf_send' value='<?php echo $row['drivinglogid']; ?>'>
    					<?php } else {
    						$view_pdf = '<a href=\'amendments.php?from=14days&graph=on&drivinglogid='.$row['drivinglogid'].'\'>Create PDF</a>';
    						echo $view_pdf;
    					}
                        //echo '<a href=\'driving_log_14days.php?email=send&drivinglogid='.$row['drivinglogid'].'\'>Email</a>';
                        echo '</td>';
                    }

                    echo "</tr>";
                }

                echo '</table></div>';

                echo '<span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Clicking here will discard your changes."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>
                    <a href="driving_log_tiles.php" class="btn brand-btn btn-lg">Back</a>';
				//echo '<a href="#" class="btn brand-btn" onclick="history.go(-1);return false;">Back</a>';

                ?>
        </form>

	    </div>
    </div>
</div>
<?php include ('../footer.php'); ?>