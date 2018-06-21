<?php
/*
Customer Listing
*/
if(!isset($_GET['mobile_view'])) {
	include_once ('../include.php');
} else {
	include_once ('../database_connection.php');
	include_once ('../global.php');
	include_once ('../function.php');
	include_once ('../output_functions.php');
	include_once ('../email.php');
	include_once ('../user_font_settings.php');
}
error_reporting(0);
?>
<?php
if(!empty($_POST['subtab']) && $_POST['subtab'] != 'goals') {
	$action_page = 'my_profile.php?edit_contact='.$_GET['edit_contact'];
	if($_POST['subtab'] == 'certificates') {
		$action_page = 'my_certificate.php?edit_contact='.$_GET['edit_contact'];
	}
	if($_POST['subtab'] == 'daysheet') {
		$action_page = 'daysheet.php?edit_contact='.$_GET['edit_contact'];
	}
	if($_POST['subtab'] == 'schedule') {
		$action_page = 'staff_schedule.php';
	}

	?>
	<form action="<?php echo $action_page; ?>" method="post" id="change_page">
		<input type="hidden" name="subtab" value="<?php echo $_POST['subtab']; ?>">
	</form>
	<script type="text/javascript"> document.getElementById('change_page').submit(); </script>
<?php } ?>
</head>
<script type="text/javascript" src="profile.js"></script>
<script type="text/javascript">
$(document).on('change', 'select#weekly_type', function() { changeGaoType(this); return false; });
</script>
<body>
<?php if(!isset($_GET['mobile_view'])) { include_once ('../navigation.php'); }
checkAuthorised();
$subtab = 'goals';
if (!empty($_POST['subtab'])) {
    $subtab = $_POST['subtab'];
}
?>
<div class="container">
    <div class="row">
		<!-- <div id="no-more-tables" class="main-screen contacts-list"> -->
		<div class="main-screen">
			<!-- Tile Header -->
            <div class="tile-header standard-header">
                <div class="col-xs-12 col-sm-4">
                    <h1>
                        <span class="pull-left" style="margin-top: -5px;"><a href="my_profile.php" class="default-color">My Profile</a></span>
                        <span class="clearfix"></span>
                    </h1>
                </div>
                <div class="col-xs-12 col-sm-8 text-right settings-block">
            		<form action="?<?= $_GET['edit_contact'] != 'true' ? 'edit_contact=true' : '' ?>" method="post" id="edit_contact">
            			<button name="subtab" value="<?= $subtab ?>" onclick="$('#edit_contact').submit();" class="btn brand-btn pull-right"><?= $_GET['edit_contact'] != 'true' ? 'Edit' : 'View' ?></button>
            		</form>
                    <a href="<?= WEBSITE_URL ?>/Daysheet/daysheet.php" class="btn brand-btn pull-right">Planner</a>
                </div>
                <div class="clearfix"></div>
            </div><!-- .tile-header -->

            <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

                <!-- Sidebar -->
				<div class="standard-collapsible tile-sidebar set-section-height">
                    <?php include('tile_sidebar.php'); ?>
                </div><!-- .tile-sidebar -->

				<?php
				/* Pagination Counting */
				$rowsPerPage = 25;
				$pageNum = 1;

				if(isset($_GET['page'])) {
				    $pageNum = $_GET['page'];
				}

				$offset = ($pageNum - 1) * $rowsPerPage;

				$status = $_GET['status'];
				$contactid = $_SESSION['contactid'];
				$query_check_credentials = "SELECT * FROM goals where goal_set_for = '$contactid' and goal_timeline='$status' LIMIT $offset, $rowsPerPage";
				$query = "SELECT count(*) as numrows FROM goals where goal_set_for = '$contactid' and goal_timeline='$status'";

				$result = mysqli_query($dbc, $query_check_credentials);

				$num_rows = mysqli_num_rows($result);
				?>
                <div class="has-main-screen scale-to-fill tile-content set-section-height" style="padding: 0; overflow-y: auto;">
					<div class="main-screen-details main-screen override-main-screen <?= $subtab != 'id_card' ? 'standard-body' : '' ?>" style="height: inherit;">
						<?php if($subtab != 'id_card') { ?>
							<div class='standard-body-title'>
								<h3><?= $sidebar_fields[$subtab][1]; ?></h3>
							</div>
						<?php } ?>
						<div class='standard-body-dashboard-content pad-top pad-left pad-right'>
						<!--<div class="col-sm-12 col-xs-12 col-lg-12 pad-top offset-xs-top-20">
							<a href="add_gao.php?type=self" class="btn brand-btn mobile-100 mobile-block gap-bottom pull-right">Add Goal</a>
						</div>-->
						<?php if (isset($_GET['mobile_view'])) { ?>
							<div class="form-group">
								<label class="control-label col-sm-4">Goal Type:</label>
								<div class="col-sm-8">
									<select id="weekly_type" class="form-control chosen-select-deselect">
										<?php
								            $each_tab = array('Daily', 'Weekly', 'Bi-Monthly', 'Monthly', 'Quarterly', 'Semi-Annually', 'Yearly');
								            foreach ($each_tab as $cat_tab) {
								            	echo '<option '.($cat_tab == $status ? 'selected' : '').' value="'.$cat_tab.'">'.$cat_tab.'</option>';
								            }
										?>
									</select>
								</div>
							</div>
				        <?php }

						if($num_rows > 0) {
					    	echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
					    ?>
							<!--<table class="table table-bordered">
								<tbody>
									<tr class="hidden-xs hidden-sm">
										<th>Goal Heading</th>
										<th>Goal Setter</th>
										<th>Goal Set For</th>
										<th>Goal Timeline</th>
										<th>Goal Start Date</th>
										<th>Goal End Date</th>
										<th>The Goal</th>
										<th>Objective</th>
										<th>Action</th>
									</tr>
									<?php // while($row = mysqli_fetch_array( $result )) { ?>
										<tr>
											<td><?php echo $row['goal_heading']; ?></td>
											<td></td>
											<td></td>
											<td><?php echo $row['goal_timeline']; ?></td>
											<td><?php echo $row['start_date']; ?></td>
											<td><?php echo $row['end_date']; ?></td>
											<td><?php echo $row['goal']; ?></td>
											<td><a href="">View</a>
											<?php
											/*$goalid = $row['goalid'];
											$select_obj = "select objectives from goal_objectives where goalid = $goalid";
											$result_obj = mysqli_query($dbc, $select_obj);
											while($row_obj = mysqli_fetch_array( $result_obj )) {
												echo $row_obj['objectives'] . '<br>';
											}*/
											?>
											</td>
											<td><a href="">View</a>
											<?php
											/*$goalid = $row['goalid'];
											$select_obj = "select actions from goal_objectives where goalid = $goalid";
											$result_obj = mysqli_query($dbc, $select_obj);
											while($row_obj1 = mysqli_fetch_array( $result_obj )) {
												echo rtrim($row_obj1['actions'], ',') . '<br>';
											}*/
											?>
											</td>
										</tr>
									<?php //} ?>
								</tbody>
							</table>-->
							<?php while($row = mysqli_fetch_array( $result )) { ?>
								<h4><?= $row['goal_heading'] ?></h4>

								<div class="form-group">
									<?php if($row['goal_setter'] != ''): ?>
										<strong>Goal Setter:
											<?php if(get_contact($dbc, $row['goal_setter'], 'name') == ''): ?>
												<?php echo get_staff($dbc, $row['goal_setter'], 'name'); ?>
											<?php else: ?>
												<?php echo get_contact($dbc, $row['goal_setter'], 'name'); ?>
											<?php endif; ?>
										</strong>
										<div class="clearfix"></div>
									<?php endif; ?>
									<?php if($row['goal_set_for'] != ''): ?>
										<strong>Goal Set For:
											<?php if(get_contact($dbc, $row['goal_set_for'], 'name') == ''): ?>
												<?php $setter = get_staff($dbc, $row['goal_set_for'], 'name'); ?>
											<?php else: ?>
												<?php $setter = get_contact($dbc, $row['goal_set_for'], 'name'); ?>
											<?php endif; ?>
											<?php if($row['goal_set_for'] == 0) echo "For All"; else echo $setter; ?></strong>
										<div class="clearfix"></div>
									<?php endif; ?>
									<?php if($row['goal_timeline'] != ''): ?>
										<strong>Goal Timeline:
											<?php echo $row['goal_timeline'] ?></strong>
										<div class="clearfix"></div>
									<?php endif; ?>
									<?php if($row['start_date'] != ''): ?>
										<strong>Goal Start Date:
											<?php echo $row['start_date'] ?></strong>
										<div class="clearfix"></div>
									<?php endif; ?>
									<?php if($row['end_date'] != ''): ?>
										<strong>Goal End Date:
											<?php echo $row['end_date'] ?></strong>
										<div class="clearfix"></div>
									<?php endif; ?>
									<?php if($row['goal'] != ''): ?>
										<strong>The Goal:
											<?php echo $row['goal'] ?></strong>
										<div class="clearfix"></div>
									<?php endif; ?>
									<strong>Objective:
										<?php $goalid = $row['goalid']; ?>
										<a href='add_gao.php?note=obac&goalid=<?php echo $goalid; ?>'>View</a></strong>
									<div class="clearfix"></div>
									<strong>Action:
										<a href='add_gao.php?note=obac&goalid=<?php echo $goalid; ?>'>View</a></strong>
									<div class="clearfix"></div>
								</div>
							<?php } ?>
						<?php }
						else {
							echo "<br> No Records Found.";
						} ?>
						</div>
					</div>
                    <div class="clearfix"></div>
				</form>
			</div>
		</div>
	</div>
</div>
<!--<div class="col-sm-12 col-xs-12 col-lg-12 pad-top offset-xs-top-20">
	<a href="add_gao.php?type=self" class="btn brand-btn mobile-100 mobile-block gap-bottom pull-right">Add Goal</a>
</div>-->
<?php include('../footer.php'); ?>