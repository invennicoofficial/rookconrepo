<?php
/*
Customer Listing
*/
include ('../include.php');
?>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('injury');
?>

<div class="container triple-pad-bottom">
    <div class="row">
		<div class="col-md-12">

        <h1 class="">Injury Dashboard
        <?php
        /*if(config_visible_function($dbc, 'injury') == 1) {
            echo '<a href="field_config_medication.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
        }*/
        ?>
        </h1>
        <?php
        $active_tab = '';
        $dis_tab = '';
        if($_GET['category'] == 'active') {
            $active_tab = ' active_tab';
            $note = 'An Active Injury is an open injury file where treatment can be applied to it. Treatments are tracked to each injury created for each customer. You can Add/Edit/Discharge Injuries for Patients directly from here.';
        }
        if($_GET['category'] == 'discharged') {
            $dis_tab = ' active_tab';
            $note = 'A Discharged Injury means the customer completed all of the treatments for that injury, or they are not seeking any further treatment for that injury.';
       }
        ?>
		<div class="tab-container col-sm-12">
            <div class="tab pull-left"><?php
                if ( check_subtab_persmission( $dbc, 'injury', ROLE, 'active' ) === true ) { ?>
                    <a href="injury.php?category=active"><button type="button" class="btn brand-btn mobile-block <?php echo $active_tab; ?>">Active</button></a><?php
                } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block">Active</button></a><?php
                } ?>
            </div>
            <div class="tab pull-left"><?php
                if ( check_subtab_persmission( $dbc, 'injury', ROLE, 'discharged' ) === true ) { ?>
                    <a href="injury.php?category=discharged"><button type="button" class="btn brand-btn mobile-block <?php echo $dis_tab; ?>">Discharged</button></a><?php
                } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block">Discharged</button></a><?php
                } ?>
            </div>
        </div>
        
        <div class="clearfix"></div>

	    <form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
            <div class="notice gap-top double-gap-bottom popover-examples">
                <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
                <div class="col-sm-11"><span class="notice-name">NOTE:</span>
                <?php echo $note; ?></div>
                <div class="clearfix"></div>
            </div>
            <?php
            if(vuaed_visible_function($dbc, 'injury') == 1) {
                echo '<a href="add_injury.php" class="btn brand-btn mobile-block pull-right">Add Injury</a>';
            }

            if (isset($_POST['search_email_submit'])) {
                $patient = $_POST['patient'];
            } else {
                $patient = '';
            }
            ?>

            <div class="form-group">
                <label for="site_name" class="col-sm-4 control-label">Patient:</label>
                <div class="col-sm-8" style="width:auto">
                    <select data-placeholder="Select a Patient..." name="patient" class="chosen-select-deselect form-control" width="380">
                        <option value=""></option>
                        <?php
                        if($_GET['category'] == 'active') {
                            $query = mysqli_query($dbc,"SELECT distinct(b.contactid), c.first_name, c.last_name FROM patient_injury b,contacts c WHERE b.deleted=0 AND b.discharge_date IS NULL AND b.contactid = c.contactid");
                        }
                        if($_GET['category'] == 'discharged') {
                            $query = mysqli_query($dbc,"SELECT distinct(b.contactid), c.first_name, c.last_name FROM patient_injury b,contacts c WHERE b.deleted=0 AND b.discharge_date IS NOT NULL AND b.contactid = c.contactid");
                        }

						while($row = mysqli_fetch_array($query)) {
							$patients[$row['contactid']] = decryptit($row['first_name']) . ' ' . decryptit($row['last_name']);
						}
		
						$patients = sortByLastName($patients);
                        foreach($patients as $patientid => $patientp) {
                            if ($patient == $patientid) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $patientid."'>".$patientp.'</option>';
                        }
                        ?>
                    </select>
                </div>
                &nbsp;&nbsp;&nbsp;
            <button type="submit" name="search_email_submit" value="Search" class="btn brand-btn mobile-block">Submit</button></div>

            <br><br><div id="no-more-tables">
            <?php
			$rowsPerPage = ITEMS_PER_PAGE;
			$pageNum = 1;

			if(isset($_GET['page'])) {
				$pageNum = $_GET['page'];
			}

			$offset = ($pageNum - 1) * $rowsPerPage;

            if($_GET['category'] == 'active' && $patient != '') {
                $query_check_credentials = "SELECT * FROM patient_injury WHERE deleted = 0 AND discharge_date IS NULL AND contactid = '$patient' ORDER BY injuryid DESC LIMIT $offset, $rowsPerPage";
            } else if($_GET['category'] == 'active') {
                $query_check_credentials = "SELECT * FROM patient_injury WHERE deleted = 0 AND discharge_date IS NULL ORDER BY injuryid DESC LIMIT $offset, $rowsPerPage";
                $query = "SELECT count(*) as numrows FROM patient_injury WHERE deleted = 0 AND discharge_date IS NULL";
            } else if($_GET['category'] == 'discharged' && $patient != '') {
                $query_check_credentials = "SELECT * FROM patient_injury WHERE deleted = 0 AND discharge_date IS NOT NULL AND contactid = '$patient' ORDER BY injuryid DESC LIMIT $offset, $rowsPerPage";
            } else {
                $query_check_credentials = "SELECT * FROM patient_injury WHERE deleted = 0 AND discharge_date IS NOT NULL ORDER BY injuryid DESC LIMIT $offset, $rowsPerPage";
                $query = "SELECT count(*) as numrows FROM patient_injury WHERE deleted = 0 AND discharge_date IS NOT NULL";
            }

            $result = mysqli_query($dbc, $query_check_credentials);

            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {

                // Added Pagination //
                if($patient == '') {
                    echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
                }
                // Pagination Finish //

                echo "<table class='table table-bordered'>";
                echo "<tr class='hidden-xs hidden-sm'>
                <th>Patient</th>
                <th>Therapist</th>
                <th>Type : Name</th>
                <th>Injury Date</th>
                <th>Treatment Plan</th>";
				echo $_GET['category'] == 'active' ? "<th>Edit</th>" : "";
                echo "<th>Discharge</th>
                </tr>";
            } else {
                echo "<h2>No Record Found.</h2>";
            }
            while($row_injury = mysqli_fetch_array( $result ))
            {
                $injuryid = $row_injury['injuryid'];

                $back = '';
                $discharge = 0;
                $link_text = 'View/Edit';
                if($row_injury['discharge_date'] != '') {
                    $back = 'style="background-color: #ffa64d;"';
                    $discharge = 1;
                    $link_text = 'View';
                }
                //echo '<tr '.$back.'>';
                echo '<tr>';
                echo '<td data-title="Email">' .get_contact($dbc, $row_injury['contactid']) . '</td>';

                echo '<td data-title="Email">' .get_contact($dbc, $row_injury['injury_therapistsid']) . '</td>';
                echo '<td data-title="Email">' . $row_injury['injury_type']. ' : '. $row_injury['injury_name'] . '</td>';
                echo '<td data-title="Email">' . $row_injury['injury_date'] . '</td>';

                /*
                echo '<td data-title="Email"><a href="Download/'.$row_injury['injury_reg_form'].'" target="_blank">'.$row_injury['injury_reg_form'].'</a></td>';
                echo '<td>';
                if($row_injury['injury_other_form'] != '') {
                    $file_names = explode('#$#', $row_injury['injury_other_form']);
                    echo '<ul>';
                    $i=0;
                    foreach($file_names as $file_name) {
                        if($file_name != '') {
                            echo '<li><a href="Download/'.$file_name.'" target="_blank">'.$file_name.'</a></li>';
                        }
                        $i++;
                    }
                    echo '</ul>';
                } else {
                    echo '-';
                }
                echo '</td>';
                */

                echo '<td>';
                $future_appt = 0;
                $appoint_date = mysqli_query($dbc,"SELECT appoint_date, follow_up_call_status FROM booking WHERE injuryid='$injuryid' ORDER BY appoint_date DESC");
                echo $row_injury['treatment_plan'].'<br>';
                while($appoint_date1 = mysqli_fetch_array($appoint_date)) {
                    echo substr($appoint_date1['appoint_date'],0,10).' : '.$appoint_date1['follow_up_call_status'].'<br>';

                    if (new DateTime() < new DateTime($appoint_date1['appoint_date'])) {
                        $future_appt = 1;
                    }
                }

                echo '</td>';

                
				if($_GET['category'] == 'active') {
					echo '<td>';
					if($row_injury['discharge_date'] != '') {
						echo '-';
					} else {
						//echo '<a class="iframe_open edit_injury" id="'.$contactid.'_'.$row_injury['injuryid'].'">Edit</a>';
						echo '<a href="add_injury.php?injuryid='.$row_injury['injuryid'].'">Edit</a>';
					}
					echo '</td>';
				}

                if (strpos($row_injury['injury_type'], "Massage") !== FALSE) {
                    $drop_off = 'Massage';
                } else {
                    $drop_off = 'Physiotherapy';
                }
                //echo '<td><a href=\''.WEBSITE_URL.'/Contacts/send_injury_drop_off.php?patientid='.$row_injury['contactid'].'&type='.$drop_off.'\' onclick="return confirm(\'Are you sure you want to send drop of analysis email to patient?\')">Send</a></td>';

                if($row_injury['discharge_date'] != '') {
                    //echo '<td><a class="iframe_open view_discharge_comment" id="'.$row_injury['injuryid'].'">View Discharge Note</a></td>';
                    echo '<td><a href="discharge_comment.php?injuryid='.$row_injury['injuryid'].'">View Discharge Note</a></td>';
                } else {
                    //echo '<td><a class="iframe_open discharge" id="'.$row_injury['injuryid'].'">Discharge</a></td>';
                    if($future_appt == 0) {
                        echo '<td><a href="discharge_comment.php?injuryid='.$row_injury['injuryid'].'">Discharge</a></td>';
                    } else {
                        echo '<td>-</td>';
                    }
                }
                echo "</tr>";
            }

            echo '</table></div>';

            // Added Pagination //
            if($patient == '') {
                echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
            }
            // Pagination Finish //

            if(vuaed_visible_function($dbc, 'injury') == 1) {
                echo '<a href="add_injury.php" class="btn brand-btn mobile-block pull-right">Add Injury</a>';
            }

            ?>

        </form>

        </div>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
