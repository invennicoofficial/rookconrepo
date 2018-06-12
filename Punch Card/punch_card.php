<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('punch_card');
error_reporting(0);
$time_clock_mode = get_config($dbc, "time_clock_select_mode");
$time_clock_for = get_config($dbc, "time_clock_for");

?>
</head>
<body>

<?php include ('../navigation.php'); ?>
<div class="container">
	<div class="row">
		<h1><?= $_GET['title'] == 'sign_in' ? 'Sign In / Out' : 'Time Clock' ?> <?php if(config_visible_function($dbc, 'punch_card') + config_visible_function($dbc, 'sign_in_time') > 0) {
			echo "<a href='field_config.php'><img class='inline-img wiggle-me pull-right' src='../img/icons/settings-4.png'></a>";
		} ?></h1>
		<div class="pad-5 tab-container mobile-100-container">
			<div class="tab list-inline pull-left">
                <?php if ($time_clock_mode != 'multi' && (check_subtab_persmission($dbc, 'punch_card', ROLE, 'how_to_guide') === TRUE || check_subtab_persmission($dbc, 'sign_in_time', ROLE, 'how_to_guide') === TRUE )) { ?>
                    <a href="how_to_guide.php" class="btn brand-btn mobile-100">How To Guide</a>
                <?php } else { ?>
                    <button class="btn disabled-btn mobile-100">How To Guide</button>
                <?php } ?>
            </div>
			<div class="tab list-inline pull-left">
                <?php if ( check_subtab_persmission($dbc, 'punch_card', ROLE, 'time_clock') === TRUE || check_subtab_persmission($dbc, 'sign_in_time', ROLE, 'time_clock') === TRUE ) { ?>
                    <a href="punch_card.php" class="btn brand-btn mobile-100 active_tab">Time Clock</a>
                <?php } else { ?>
                    <button class="btn disabled-btn mobile-100">Time Clock</button>
                <?php } ?>
            </div>
			<div class="clearfix"></div>
		</div>
		<?php //if($time_clock_mode == 'multi') { ?>
			<!--<form class="form-horizontal" action="" method="POST">
				<h2>Staff</h2>
				<?php foreach(sort_contacts_query(mysqli_query($dbc, "SELECT `first_name`, `last_name`, `contactid` FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND `deleted`=0 AND `status` > 0")) as $staff) { ?>
					<label class="form-checkbox"><input type="checkbox" name="staff" value="<?= $staff['contactid'] ?>"> <?= $staff['first_name'].' '.$staff['last_name'] ?></label>
				<?php } ?>
				<div class="clearfix"></div>
				<button class="btn brand-btn pull-right" type="submit">Sign In</button>
			</form>-->
		<?php //} else {
            if(empty($_GET['contactid'])) {
				$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT tile_employee, tile_data FROM field_config_project_manage WHERE tile='Shop Work Orders' AND tab='Shop Time Clock' AND project_manage_dashboard IS NOT NULL"));
				$sql_condition = '';
				$tile_employee = "'".trim(htmlspecialchars_decode($get_field_config['tile_employee'], ENT_QUOTES), "'")."'";
				if($tile_employee != "''" && $tile_employee != "'All Active Employee'") {
					$sql_condition = " AND self_identification IN ($tile_employee)";
				}
                $get_user = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `deleted`=0 AND `status` > 0 $sql_condition");
                $contactid = '';
                while($row_user = mysqli_fetch_array($get_user)) {
					$tickets = mysqli_query($dbc, "SELECT * FROM `project_manage_assign_to_timer` WHERE `created_by`='".$row_user['contactid']."' AND DATE(NOW()) = DATE(created_date) AND `end_time` IS NULL");
					if(mysqli_num_rows($tickets) > 0) {
						$ticket = mysqli_fetch_array($tickets);
						if($ticket['timer_type'] == 'Work') {
							$timer = '#start_timer';
						} else if($ticket['timer_type'] == 'Break') {
							$timer = '#break_timer';
						} else {
							$timer = '';
						}
						echo '<div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<a href=\'../Project Workflow/add_project_manage.php?tile=Shop Work Orders&tab=Shop Time Clock&projectmanageid='.$ticket['projectmanageid'].'&tab_from_tile_view=Time Clock&contactid='.$ticket['created_by'].$timer.'\' >'.decryptIt($row_user['first_name']).' '.decryptIt($row_user['last_name']).'</a>
							</div>';
					}
					else {
						echo '<div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="punch_card.php?contactid='.$row_user['contactid'].'" >'.decryptIt($row_user['first_name']).' '.decryptIt($row_user['last_name']).'</a></div>';
					}
                }
            }

            if(!empty($_GET['contactid'])) {
				echo '<a href="punch_card.php" class="btn brand-btn pull-right">Employee List</a><br>';

				$contactid = $_GET['contactid'];

				$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT tile_employee, tile_data FROM field_config_project_manage WHERE tile='Shop Work Orders' AND tab='Shop Time Clock' AND project_manage_dashboard IS NOT NULL"));
				$active_tickets = "SELECT * FROM project_manage WHERE tab='{$get_field_config['tile_data']}' AND status='Approved' AND projectmanageid IN (SELECT projectmanageid FROM project_manage_assign_to_timer WHERE created_by='$contactid' AND end_time IS NULL) ORDER BY projectmanageid DESC";
				$result = mysqli_query($dbc, $active_tickets);
				if(mysqli_num_rows($result) == 0) {
					$query_check_credentials = "SELECT * FROM project_manage WHERE tab='{$get_field_config['tile_data']}' AND status='Approved' ORDER BY projectmanageid DESC";
					$result = mysqli_query($dbc, $query_check_credentials);
				}

				while($row = mysqli_fetch_array( $result )) {
					$projectmanageid = $row['projectmanageid'];

					$get_timer = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT timer_type FROM project_manage_assign_to_timer WHERE projectmanageid='$projectmanageid' AND created_by='$contactid' AND DATE(NOW()) = DATE(created_date) AND end_time IS NULL"));
					if($get_timer['timer_type'] == 'Work') {
						$timer = '#start_timer';
					} else if($get_timer['timer_type'] == 'Break') {
						$timer = '#break_timer';
					} else {
						$timer = '';
					}

					echo '<div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href=\'../Project Workflow/add_project_manage.php?tile=Shop Work Orders&tab=Shop Time Clock&projectmanageid='.$projectmanageid.'&tab_from_tile_view=Time Clock&contactid='.$contactid.$timer.'\' >W-'.$row['unique_id'].'<br>'.$row['heading'].'</a></div>';
				}
            }
		// } ?>
	</div>
</div>

<?php include ('../footer.php'); ?>