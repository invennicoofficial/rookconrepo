<br />
<?php
    echo '<br /><br /><div class="mobile-100-container">';
    $each_tab = array('Daily', 'Weekly', 'Bi-Monthly', 'Monthly', 'Quarterly', 'Semi-Annually', 'Yearly');
    $statusCount = 0;
    foreach ($each_tab as $cat_tab) {
        if ((!empty($_GET['status'])) && ($_GET['status'] == $cat_tab))
            $statusCount++;
    }
    
    $totalCount = 0;
    foreach ($each_tab as $cat_tab) {
        if(empty($_GET['status']) || ($statusCount == 0 && $totalCount == 0)) {
            $cat_tab = 'Daily';
            $_GET['status'] = 'Daily';
        }
        
        if ((!empty($_GET['status'])) && ($_GET['status'] == $cat_tab)) {
            $active_to_be = ' active_tab';
        }
        else {
            $active_to_be = '';
        }
        
        echo "<a href='gao.php?maintype=company&status=".$cat_tab."'><button type='button' class='btn brand-btn mobile-block  mobile-100 ".$active_to_be."'>".$cat_tab."</button></a>&nbsp;&nbsp";
        $totalCount++;

    }

    echo '</div></h4>';
        ?>
<?php 
error_reporting(0);
?>

<?php
/* Pagination Counting */
$rowsPerPage = 25;
$pageNum = 1;

if(isset($_GET['page'])) {
    $pageNum = $_GET['page'];
}

$offset = ($pageNum - 1) * $rowsPerPage;

$status = $_GET['status'];
$query_check_credentials = "SELECT * FROM goals where type='company' and goal_timeline='$status' LIMIT $offset, $rowsPerPage";
$query = "SELECT count(*) as numrows FROM goals where type='company' and goal_timeline='$status'";

$result = mysqli_query($dbc, $query_check_credentials);

$num_rows = mysqli_num_rows($result);
?>
<div class="mobile-100-container">
	<div class="col-sm-12 col-xs-12 col-lg-12 pad-top offset-xs-top-20">
		<div class="pull-right">
			<span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add a new Goal."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a href="add_gao.php?type=company" class="btn brand-btn mobile-block gap-bottom">Add Goal</a>
		</div>
	</div>
	<?php
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
		
		<div class="row">
			<div class="panel-group" id="accordion2">
				<?php while($row = mysqli_fetch_array( $result )) { ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_prep_<?php echo $row['goalid']; ?>" >
									<?php echo $row['goal_heading'] ?><span class="glyphicon glyphicon-plus"></span>
								</a>
							</h4>
						</div>
						
						<div id="collapse_prep_<?php echo $row['goalid']; ?>" class="panel-collapse collapse <?php echo $in; $in = ''; ?>">
							<div class="panel-body">
								<div class="form-group">
									<?php if($row['goal_setter'] != ''): ?>
										<label class="col-sm-4 control-label">Goal Setter:</label>
										<div class="col-sm-8">
											<strong>
												<?php if(get_contact($dbc, $row['goal_setter'], 'name') == ''): ?>
													<?php echo get_staff($dbc, $row['goal_setter'], 'name'); ?>
												<?php else: ?>
													<?php echo get_contact($dbc, $row['goal_setter'], 'name'); ?>
												<?php endif; ?>
											</strong>
										</div>
									<?php endif; ?>
									<?php if($row['goal_set_for'] != ''): ?>
										<label class="col-sm-4 control-label">Goal Set For:</label>
										<div class="col-sm-8">
											<?php if(get_contact($dbc, $row['goal_set_for'], 'name') == ''): ?>
												<?php $setter = get_staff($dbc, $row['goal_set_for'], 'name'); ?>
											<?php else: ?>
												<?php $setter = get_contact($dbc, $row['goal_set_for'], 'name'); ?>
											<?php endif; ?>
											<strong><?php if($row['goal_set_for'] == 0) echo "For All"; else echo $setter; ?></strong>
										</div>
									<?php endif; ?>
									<?php if($row['goal_timeline'] != ''): ?>
										<label class="col-sm-4 control-label">Goal Timeline:</label>
										<div class="col-sm-8">
											<strong><?php echo $row['goal_timeline'] ?></strong>
										</div>
									<?php endif; ?>
									<?php if($row['start_date'] != ''): ?>
									<label class="col-sm-4 control-label">Goal Start Date:</label>
									<div class="col-sm-8">
										<strong><?php echo $row['start_date'] ?></strong>
									</div>
									<?php endif; ?>
									<?php if($row['end_date'] != ''): ?>
									<label class="col-sm-4 control-label">Goal End Date:</label>
									<div class="col-sm-8">
										<strong><?php echo $row['end_date'] ?></strong>
									</div>
									<?php endif; ?>
									<?php if($row['goal'] != ''): ?>
										<label class="col-sm-4 control-label">The Goal:</label>
										<div class="col-sm-8">
											<strong><?php echo $row['goal'] ?></strong>
										</div>
									<?php endif; ?>
									<label class="col-sm-4 control-label">Objective:</label>
									<div class="col-sm-8">
										<?php $goalid = $row['goalid']; ?>
										<strong><a href='add_gao.php?note=obac&goalid=<?php echo $goalid; ?>'>View</a></strong>
									</div>
									<label class="col-sm-4 control-label">Action:</label>
									<div class="col-sm-8">
										<strong><a href='add_gao.php?note=obac&goalid=<?php echo $goalid; ?>'>View</a></strong>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>

	<?php } 
	else {
		echo "No Records Found.";
	}
	?>
	<div class="col-sm-12 col-xs-12 col-lg-12 pad-top offset-xs-top-20">
		<div class="pull-right">
			<span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add a new Goal."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a href="add_gao.php?type=company" class="btn brand-btn mobile-block gap-bottom">Add Goal</a>
		</div>
	</div>
</div>
