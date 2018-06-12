<?php
/*
equipments Listing
*/

/**
 * File Name : driving_log_admin.php
 * Short description for file : This file essentially allows users with the role of admin or superadmin to view multiple driving logs of different drivers, as well as see any unapproved timers and/or audit notices.
 * Long description for file (if any)...
 *
 * @author Kelsey Nealon
*/

include ('../include.php');
checkAuthorised('driving_log');

if(strpos(','.ROLE.',',',super,') !== false && strpos(','.ROLE.',',',admin,') !== false) { 
header('Location: driving_log_tiles.php');
die();
}

$view_only_mode = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `driving_log_view_only_mode` WHERE `contactid` = '".$_SESSION['contactid']."'"))['view_only_mode'];
include_once('view_only_mode.php');
?>
<script src="js/jquery.cookie.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
		$(".ser-sch").hide();
        $(".ser-sch-btn").click(function() {
            $(this).next().show();
			$('html, body').animate({ scrollTop: 0 }, 'fast');
        });

		$(".close-btn").click(function() {
            $(".ser-sch").hide();
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
        <h1 style="display: inline;">Drivers</h1>
        <div class="pull-right">View Only: <a href="" class="view_only_button"><img src="../img/icons/switch-<?= $view_only_mode == 1 ? '7' : '6' ?>.png" style="height: 2em;"></a></div>
		
		<div class="gap-top triple-gap-bottom"><a href="driving_log_tiles.php" class="btn config-btn">Back to Dashboard</a></div>
        
        <div class="notice double-gap-bottom popover-examples">
			<div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25px"></div>
			<div class="col-sm-16"><span class="notice-name">NOTE:</span> This section displays a log of your company's Drivers. You are able to click on each Driver to view the Driver Log.</div>
		</div>
		
        <form name="form_sites" method="post" action="" class="form-inline" role="form">
			<?php
                if (strpos(','.ROLE.',',',Field Operations Management,') !== false) {
                    //echo '<a href="add_driving_log.php" class="btn brand-btn mobile-block gap-bottom pull-right">Start Driving Log</a>';
                }
                
                $vendor = '';
                if (strpos(','.ROLE.',',',Executive,') !== false || strpos(','.ROLE.',',',Human Resources,') !== false || strpos(','.ROLE.',',',Accounting,') !== false || strpos(','.ROLE.',',',Field Operations Management,') !== false || strpos(','.ROLE.',',',super,') !== false || strpos(','.ROLE.',',',admin,') !== false) { ?>
                    <div class="form-group col-sm-12" style='top:-10px; position:relative;'>
                        <label for="search_vendor" class="control-label col-sm-2">Search by Driver:</label>
						<div class='col-sm-4'>
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
						<div class="form-group  class='col-sm-4 margin-on-mobile" >
							<div class="pull-left">
								<span class='popover-examples list-inline' style="margin:0 0 0 10px;" id="dl_off_comment_i">
									<a data-toggle='tooltip' data-placement='top' title='Search by the name of the driver or co-driver you are looking for.'><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
								</span>
								<button type="submit" name="search_vendor_submit" value="Search" class="btn brand-btn">Search</button>
							</div>
							
							<div class="pull-left">
								<span class='popover-examples list-inline' style="margin:0 0 0 15px;" id="dl_off_comment_i">
									<a data-toggle='tooltip' data-placement='top' title='Display all of the Driving Logs to date.'><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
								</span>
								<button type="submit" name="display_all_vendor" value="Display All" class="btn brand-btn">Display All</button>
							</div>
						</div>
                    </div>
            <div id="no-more-tables">
                
                <?php
                    if (isset($_POST['search_vendor_submit'])) {
                        if (isset($_POST['search_vendor'])) {
                            $vendor = filter_var($_POST['search_vendor'],FILTER_SANITIZE_STRING);
                        }
                    }
                    if (isset($_POST['display_all_vendor'])) {
                        $vendor = '';
                    }
                }
				$date = date('Y/m/d', time());
				$date = strtotime($date .' -4 months');
				$cut_off_date = date('Y-m-d', $date);
                if($vendor != '') {
                    //$query_check_credentials = "SELECT dl.*, s.first_name, s.last_name  FROM driving_log dl, contacts s WHERE (s.contactid = dl.driverid OR s.contactid = dl.codriverid) AND (s.first_name = '$vendor' OR s.last_name = '$vendor') AND (dl.start_date BETWEEN CURDATE() - INTERVAL 21 DAY AND CURDATE()) ORDER BY dl.drivinglogid DESC";
					$query_check_credentials = "SELECT dl.*, s.first_name, s.last_name  FROM driving_log dl, contacts s WHERE (s.contactid = dl.driverid) AND dl.start_date >= '".$cut_off_date."' AND (dl.driverid = '$vendor') GROUP BY dl.driverid ORDER BY dl.driverid DESC";
                } else {
                    //$query_check_credentials = "SELECT * FROM driving_log WHERE (start_date BETWEEN CURDATE() - INTERVAL 21 DAY AND CURDATE()) AND (driverid = '$login' OR codriverid = '$login') ORDER BY drivinglogid DESC";
					$query_check_credentials = "SELECT c.*, d.* FROM contacts c, driving_log d WHERE (d.driverid=c.contactid) AND d.start_date >= '".$cut_off_date."' GROUP BY d.driverid ORDER BY c.last_name, c.first_name DESC";
					
					//"SELECT * FROM d driving_log ORDER BY (SELECT last_name FROM contacts WHERE d.driverid = contactid) GROUP BY d.driverid DESC";
                }
                $result = mysqli_query($dbc, $query_check_credentials);

                $num_rows = mysqli_num_rows($result);
                if($num_rows > 0) {
					echo "Displaying a total of $num_rows drivers.<br><br>";
                echo "<table class='table table-bordered'>";
                echo "<tr class='hidden-xs hidden-sm'>
                        <th>
                            <span class='popover-examples list-inline' style='margin:0 5px 0 0;' id='dl_off_comment_i'>
								<a data-toggle='tooltip' data-placement='top' title='These are the Drivers who are displayed in your software. Click on the Driver name to view their Log.'><img src='" . WEBSITE_URL . "/img/info-w.png' width='20'></a>
							</span>
                            Driver
                        </th>
						<th>
							<span class='popover-examples list-inline' style='margin:0 5px 0 0;' id='dl_off_comment_i'>
								<a data-toggle='tooltip' data-placement='top' title='This is the total number of Driving Logs that the specified driver has completed to date.'><img src='" . WEBSITE_URL . "/img/info-w.png' width='20'></a>
							</span>
							Total Driving Logs
						</th>
						<th>
							<span class='popover-examples list-inline' style='margin:0 5px 0 0;' id='dl_off_comment_i'>
								<a data-toggle='tooltip' data-placement='top' title='This is the number of Driving Logs that have not yet been approved. You can select the warning icon to go to the specific line item, and then to the Amendments dashboard by pressing the pending warning icon again. This dashboard is where you approve the Driving Logs.'><img src='" . WEBSITE_URL . "/img/info-w.png' width='20'></a>
							</span>
							Logs With Pending Statuses
						</th>
						<th>
							<span class='popover-examples list-inline' style='margin:0 5px 0 0;' id='dl_off_comment_i'>
								<a data-toggle='tooltip' data-placement='top' title='You will be flagged to audit any Driving Logs that haven’t met the set requirements. You can approve the audit by selecting the warning icon, and then selecting the audit warning symbol again to see specific notices in the Driving Log. You can dismiss these notices by selecting the checkbox at the end.'><img src='" . WEBSITE_URL . "/img/info-w.png' width='20'></a>
							</span>
							Auditing
						</th>
                        </tr>";
                } else {
                    echo "<h2>No Record Found.</h2>";
                }
				
                while($row = mysqli_fetch_array( $result ))
                {
					$amount = 0;
					$amountx = 0;
                    $drivinglogid = $row['drivinglogid'];
					$query_check_credentials2 = "SELECT * FROM driving_log WHERE driverid = ".$row['driverid']." AND start_date >= '".$cut_off_date."'";
					$result2 = mysqli_query($dbc, $query_check_credentials2);
					$num_rows2 = mysqli_num_rows($result2);
					while($row2 = mysqli_fetch_array( $result2 ))
					{
						// Check for pending statuses
						$query_check_credentialsx = "SELECT * FROM driving_log_timer WHERE drivinglogid = '".$row2['drivinglogid']."' AND amendments_status = 'Pending' GROUP BY drivinglogid ORDER BY level";
						$resultx = mysqli_query($dbc, $query_check_credentialsx);
						$num_rowsx = mysqli_num_rows($resultx);
						if($num_rowsx > 0) {
							$amount += $num_rowsx;
						}
						// Check for audit notices
						$queryyy = "SELECT * FROM driving_log WHERE drivinglogid = '".$row2['drivinglogid']."' AND audit_dismiss IS NULL AND ((audit_shift_time IS NOT NULL) OR (audit_drive_time IS NOT NULL) OR (audit_cycle_time IS NOT NULL) OR (audit_off_duty IS NOT NULL) OR (audit_drive_sixteen IS NOT NULL)) GROUP BY drivinglogid";
						$resultxx = mysqli_query($dbc, $queryyy) or die(mysqli_error($dbc));
						$num_rowsxx = mysqli_num_rows($resultxx);
						if($num_rowsxx > 0) {
							$amountx += $num_rowsxx;
						}
					}
					if($amount > 0) {
						$alert_style = 'style="font-weight:bold; color:red;"';
						$href = 'driving_log.php?driverid='.$row['driverid'].'&pendingstatus=true';
						$amount = '<a '.$alert_style.' href="'.$href.'">('.$amount.') <img src="../img/warning.png" style="width:25px"></a>';
					}
					if($amountx > 0) {
						$alert_style = 'style="font-weight:bold; color:red;"';
						$href = 'driving_log.php?driverid='.$row['driverid'].'&audit=true';
						$amountx = '<a '.$alert_style.' href="'.$href.'">('.$amountx.') <img src="../img/warning.png" style="width:25px"></a>';
					}
                    echo '<tr>';
                    echo '<td data-title="Driver"><a href="driving_log.php?driverid='.$row['driverid'].'">' . get_staff($dbc, $row['driverid']) . '</a></td>';
					echo '<td data-title="Total Logs">'.$num_rows2.'</td>';
                    echo '<td data-title="Statuses">'.$amount.'</td>';
				    echo '<td data-title="Auditing">'.$amountx.'</td>';

                    echo "</tr>";
                }

                echo '</table></div>';
                if (strpos(','.ROLE.',',',Field Operations Management,') !== false) {
                    //echo '<a href="add_driving_log.php" class="btn brand-btn pull-right">Start Driving Log</a>';
                }

                echo '<a href="driving_log_tiles.php" class="btn brand-btn">Back</a>';

                ?>
        </form>

	    </div>
    </div>
</div>
<?php include ('../footer.php'); ?>