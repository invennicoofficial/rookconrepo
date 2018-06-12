<form name="form_sites" method="post" action="" class="form-inline" role="form">

            <center>
            <div class="form-group">
                <label for="site_name" class="col-sm-5 control-label">Search By Any:</label>
                <div class="col-sm-6">
                    <?php if(isset($_POST['search_vendor_submit'])) { ?>
                        <input type="text" name="search_vendor" class="form-control" value="<?php echo $_POST['search_vendor']?>">
                    <?php } else { ?>
                        <input type="text" name="search_vendor" class="form-control">
                    <?php } ?>
                </div>
            </div>
            &nbsp;
				<button type="submit" name="search_vendor_submit" value="Search" class="btn brand-btn mobile-block mobile-100">Search</button>
			    <button type="submit" name="display_all_vendor" value="Display All" class="btn brand-btn mobile-block mobile-100">Display All</button>
            </center>

		<?php
		if(vuaed_visible_function($dbc, 'time_tracking') == 1) {
			echo '<a href="add_time_tracking.php" class="btn brand-btn mobile-block pull-right mobile-100-pull-right">Add Time Tracking</a>';
        }
		?>

	<div id="no-more-tables">

	<?php
	//Search
	$vendor = '';
	if (isset($_POST['search_vendor_submit'])) {
		if (isset($_POST['search_vendor'])) {
			$vendor = $_POST['search_vendor'];
		}
	}
	if (isset($_POST['display_all_vendor'])) {
		$vendor = '';
	}

	/* Pagination Counting */
	$rowsPerPage = 25;
	$pageNum = 1;

	if(isset($_GET['page'])) {
		$pageNum = $_GET['page'];
	}

	$offset = ($pageNum - 1) * $rowsPerPage;

	if($vendor != '') {
		$query_check_credentials = "SELECT * FROM time_tracking WHERE deleted = 0 AND (location LIKE '%" . $vendor . "%' OR job_number ='$vendor' OR afe_number LIKE '%" . $vendor . "%') LIMIT $offset, $rowsPerPage";
		$query = "SELECT count(*) as numrows FROM time_tracking WHERE deleted = 0 AND (location LIKE '%" . $vendor . "%' OR job_number ='$vendor' OR afe_number LIKE '%" . $vendor . "%')";
	} else {
		$query_check_credentials = "SELECT * FROM time_tracking WHERE deleted = 0 LIMIT $offset, $rowsPerPage";
		$query = "SELECT count(*) as numrows FROM time_tracking WHERE deleted = 0";
	}

	$result = mysqli_query($dbc, $query_check_credentials);

	$num_rows = mysqli_num_rows($result);
	if($num_rows > 0) {
		$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT time_tracking_dashboard FROM field_config"));
		$value_config = ','.$get_field_config['time_tracking_dashboard'].',';

		// Added Pagination //
		echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
		// Pagination Finish //

		echo "<table class='table table-bordered'>";
		echo "<tr class='hidden-xs hidden-sm'>";
			if (strpos($value_config, ','."Business".',') !== FALSE) {
				echo '<th>'.BUSINESS_CAT.'</th>';
			}
			if (strpos($value_config, ','."Contact".',') !== FALSE) {
				echo '<th>Contact</th>';
			}
			if (strpos($value_config, ','."Location".',') !== FALSE) {
				echo '<th>Location</th>';
			}
			if (strpos($value_config, ','."Job number".',') !== FALSE) {
				echo '<th>Job #</th>';
			}
			if (strpos($value_config, ','."AFE number".',') !== FALSE) {
				echo '<th>AFE #</th>';
			}
			if (strpos($value_config, ','."Work performed".',') !== FALSE) {
				echo '<th>Work Performed</th>';
			}
			if (strpos($value_config, ','."Short description".',') !== FALSE) {
				echo '<th>Short Description</th>';
			}
			if (strpos($value_config, ','."Job description".',') !== FALSE) {
				echo '<th>Job Description</th>';
			}
			if (strpos($value_config, ','."Labour".',') !== FALSE) {
				echo '<th>Labour - Position - Reg Hours - Reg Rate - OT Hours - OT Rate</th>';
			}
			echo '<th>Function</th>';
			echo "</tr>";
	} else {
		echo "<h2>No Record Found.</h2>";
	}

	while($row = mysqli_fetch_array( $result ))
	{
		echo "<tr>";
		$timetrackingid = $row['timetrackingid'];
		if (strpos($value_config, ','."Business".',') !== FALSE) {
			echo '<td data-title="'.BUSINESS_CAT . '">'.get_contact($dbc, $row['businessid'],'name').'</td>';
		}
		if (strpos($value_config, ','."Contact".',') !== FALSE) {
			echo '<td data-title="Contact">' . get_staff($dbc, $row['contactid']) . '</td>';
		}
		if (strpos($value_config, ','."Location".',') !== FALSE) {
			echo '<td data-title="Location">' . $row['location'] . '</td>';
		}
		if (strpos($value_config, ','."Job number".',') !== FALSE) {
			echo '<td data-title="Job #">' . $row['job_number'] . '</td>';
		}
		if (strpos($value_config, ','."AFE number".',') !== FALSE) {
			echo '<td data-title="AFE #">' . $row['afe_number'] . '</td>';
		}
		if (strpos($value_config, ','."Work performed".',') !== FALSE) {
			echo '<td data-title="Work Performed">' . $row['work_preformed'] . '</td>';
		}
		if (strpos($value_config, ','."Short description".',') !== FALSE) {
			echo '<td data-title="Short Description">' . $row['short_desc'] . '</td>';
		}
		if (strpos($value_config, ','."Job description".',') !== FALSE) {
			echo '<td data-title="Job Description">' . $row['job_desc'] . '</td>';
		}

		if (strpos($value_config, ','."Labour".',') !== FALSE) {
			$emp = '';
			$time_tracking_labour = mysqli_query($dbc, "SELECT * FROM time_tracking_labour WHERE timetrackingid='$timetrackingid'");

			while($row_labour = mysqli_fetch_array( $time_tracking_labour )) {
				$emp .= get_staff($dbc, $row_labour['staffid']);
				$emp .= ' - '.$row_labour['position'];
				$emp .= ' - ' . $row_labour['reg_hours'];
				$emp .= ' - ' . $row_labour['reg_rate'];
				$emp .= ' - ' . $row_labour['ot_hours'];
				$emp .= ' - ' . $row_labour['ot_rate'];
				$emp .= '<br>';
			}
			echo '<td data-title="Labour">' . $emp . '</td>';
		}

		echo '<td data-title="Function">';
		if(vuaed_visible_function($dbc, 'time_tracking') == 1) {
		echo '<a href=\'add_time_tracking.php?timetrackingid='.$row['timetrackingid'].'\'>Edit</a> | ';
		echo '<a href=\''.WEBSITE_URL.'/delete_restore.php?action=delete&timetrackingid='.$row['timetrackingid'].'\' onclick="return confirm(\'Are you sure?\')">Archive</a>';
		}
		echo '</td>';

		echo "</tr>";
	}

	echo '</table></div>';

	// Added Pagination //
	echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
	// Pagination Finish //


	if(vuaed_visible_function($dbc, 'time_tracking') == 1) {
	echo '<a href="add_time_tracking.php" class="btn brand-btn mobile-block pull-right">Add Time Tracking</a>';
	}

	?>
</form>