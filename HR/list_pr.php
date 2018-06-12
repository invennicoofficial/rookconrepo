<?php 
if(isset($_POST['select_staff'])) {
	$_GET['select_staff'] = $_POST['select_staff'];
}
if(isset($_POST['select_date'])) {
	$_GET['select_date'] = $_POST['select_date'];
}
if(isset($_POST['search_all'])) {
	$_GET['select_staff'] = '';
	$_GET['select_date'] = '';
}
$select_staff = $_GET['select_staff'];
$select_date = $_GET['select_date'];
$pr_tab = $_GET['pr_tab'];

$pr_positions = explode(',', get_config($dbc, 'performance_review_positions'));

$allowed_positions = [];
foreach ($pr_positions as $pr_position) {
	if(check_subtab_persmission($dbc, 'preformance_review', ROLE, $pr_position)) {
		$allowed_positions[] = $pr_position;
	}
}

if(!empty($select_staff)) {
	$staff_query = " AND `userid` = '$select_staff'";
}
if(!empty($select_date)) {
	$date_query = " AND `today_date` >= '$select_date'";
}
if(!empty($pr_tab)) {
	$tab_query = " AND `position` = '$pr_tab'";
} else {
	$tab_query = " AND `position` IN ('".implode("', '", $allowed_positions)."')";
}


/* Pagination Counting */
$rowsPerPage = 25;
$pageNum = 1;

if(isset($_GET['page'])) {
	$pageNum = $_GET['page'];
}

$offset = ($pageNum - 1) * $rowsPerPage;

$pr_query = "SELECT * FROM `performance_review` WHERE `deleted` = 0".$staff_query.$date_query.$tab_query." LIMIT $offset, $rowsPerPage";
$query = "SELECT COUNT(*) as numrows FROM `performance_review` WHERE `deleted` = 0".$staff_query.$date_query.$tab_query;
$result = mysqli_query($dbc, $pr_query);
$num_rows = mysqli_num_rows($result);
?>
<div class='scale-to-fill has-main-screen'>
	<div class='main-screen form-horizontal'>
		<div class="block-group">
			<div class="col-sm-12">
				<h3 class="inline">Performance Reviews</h3>
				<?php if(vuaed_visible_function($dbc, 'preformance_review')) { ?>
					<a href="?performance_review=add" class="btn brand-btn pull-right">Add Performance Review</a>
				<?php } ?>
			</div>
			<div class="clearfix"></div>
			<div class="form-group" style="margin: 0 1.5em 0 1.5em;">
				<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
					<div class="col-sm-3">
						<label class="super-label">Staff
							<select name="select_staff" class="chosen-select-deselect">
								<option></option>
								<?php $staff_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `deleted` = 0 AND `status` = 1 AND `show_hide_user` = 1"),MYSQLI_ASSOC));
								foreach ($staff_list as $staff_id) { ?>
									<option value="<?= $staff_id ?>" <?= $select_staff == $staff_id ? 'selected' : '' ?>><?= get_contact($dbc, $staff_id) ?></option>
								<?php } ?>
							</select>
						</label>
					</div>
					<div class="col-sm-3">
						<label class="super-label">Since
							<input type="text" name="select_date" class="form-control datepicker" value="<?= $select_date ?>">
						</label>
					</div>
					<div class="col-sm-6">
						<p style="font-size: 0.6em;"></p>
						<button type="submit" name="search_pr" class="btn brand-btn">Submit</button>
						<button type="submit" name="search_all" class="btn brand-btn">Display All</button>
					</div>
				</form>
				<div class="clearfix"></div>
				<?php if($num_rows > 0) {
					echo display_pagination($dbc, $query, $pageNum, $rowsPerPage); ?>
					<table id="no-more-tables" class="table table-bordered">
						<tr class="hide-titles-mob">
							<th>Name</th>
							<th>Position</th>
							<th>Date Created</th>
							<th>PDF</th>
							<?php if(vuaed_visible_function($dbc, 'preformance_review')) { ?>
								<th>Function</th>
							<?php } ?>
						</tr>
						<?php while($row = mysqli_fetch_array($result)) { ?>
							<tr>
								<td data-title="Name"><?= get_contact($dbc, $row['userid']) ?></td>
								<td data-title="Position"><?= $row['position'] ?></td>
								<td data-title="Date Created"><?= $row['today_date'] ?></td>
								<td data-title="PDF">
									<?php $user_pdf = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `user_form_pdf` WHERE `pdf_id` = '".$row['pdf_id']."'")); ?>
									<a href="download/<?= $user_pdf['generated_file'] ?>"><img src="../img/pdf.png"></a>
								</td>
								<?php if(vuaed_visible_function($dbc, 'preformance_review')) { ?>
									<td data-title="Function">
										<a href="../HR/index.php?performance_review=add&form_id=<?= $row['user_form_id'] ?>&reviewid=<?= $row['reviewid'] ?>">Edit</a> | <a href="../delete_restore.php?reviewid=<?= $row['reviewid'] ?>&action=delete" onclick="return confirm('Are you sure you want to delete this Performance Review?');">Archive</a>
									</td>
								<?php } ?>
							</tr>
						<?php } ?>
					</table>
					<?php echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
				} else {
					echo '<h3>No Performance Reviews Found.</h3>';
				} ?>
			</div>
		</div>
	</div>
</div>