<?php if(!empty($_POST['search_staff'])) {
	$search_staff = $_POST['search_staff'];
	$clause .= " AND `created_by`='$search_staff'";
}
if(!empty($_POST['search_client'])) {
	$search_client = $_POST['search_client'];
	$clause .= " AND `client_id`='$search_client'";
}
if(!empty($_POST['search_from_date'])) {
	$search_from_date = $_POST['search_from_date'];
	$clause .= " AND `note_date` >= '$search_from_date'";
}
if(!empty($_POST['search_to_date'])) {
	$search_to_date = $_POST['search_to_date'];
	$clause .= " AND `note_date` <= '$search_to_date'";
} ?>
<form id="form1" name="form1" method="POST" enctype="multipart/form-data" class="form-horizontal" role="form">
	<div class="form-group col-sm-5">
		<label for="search_client" class="col-sm-4 control-label">Search By Contact:</label>
		<div class="col-sm-8">
			<select data-placeholder="Select a Contact" name="search_client" class="chosen-select-deselect form-control">
				<option value=""></option>
				<?php $client_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT `contactid`, `last_name`, `first_name` FROM `contacts` WHERE `contactid` IN (SELECT `client_id` FROM `client_daily_log_notes`) AND `deleted`=0 AND `status`>0"),MYSQLI_ASSOC));
				foreach($client_list as $row_id) {
					?><option <?php if ($row_id == $search_client) { echo " selected"; } ?> value='<?php echo  $row_id; ?>' ><?php echo get_contact($dbc, $row_id); ?></option>
				<?php } ?>
			</select>
		</div>
	</div>
	<div class="form-group col-sm-5">
		<label for="search_staff" class="col-sm-4 control-label">Search By Staff:</label>
		<div class="col-sm-8">
			<select data-placeholder="Select a Staff" name="search_staff" class="chosen-select-deselect form-control">
				<option value=""></option>
				<?php $client_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT `contactid`, `last_name`, `first_name` FROM `contacts` WHERE `contactid` IN (SELECT `created_by` FROM `client_daily_log_notes`) AND `deleted`=0 AND `status`>0"),MYSQLI_ASSOC));
				foreach($client_list as $row_id) {
					?><option <?php if ($row_id == $search_staff) { echo " selected"; } ?> value='<?php echo  $row_id; ?>' ><?php echo get_contact($dbc, $row_id); ?></option>
				<?php } ?>
			</select>
		</div>
	</div>
	<div class="form-group col-sm-5">
		<label for="search_from_date" class="col-sm-4 control-label">Search From Date:</label>
		<div class="col-sm-8">
			<input name="search_from_date" value="<?php echo $search_from_date; ?>" type="text" class="form-control datepicker">
		</div>
	</div>
	<div class="form-group col-sm-5">
		<label for="search_to_date" class="col-sm-4 control-label">Search To Date:</label>
		<div class="col-sm-8">
			<input name="search_to_date" value="<?php echo $search_to_date; ?>" type="text" class="form-control datepicker">
		</div>
	</div>

	<div class="col-sm-2 pull-right">
		<?php if(!isset($display_contact)) { ?>
			<button type="submit" name="search_user_submit" value="Search" class="btn brand-btn mobile-block">Search</button>
			<button type="button" onclick="window.location=''" name="display_all_inventory" value="Display All" class="btn brand-btn mobile-block">Display All</button>
		<?php } else { ?>
			<button class="btn brand-btn mobile-block" onclick="search_notes(); return false;">Search</button>
		<?php } ?>
	</div>
	<div class="clearfix"></div>
</form>

<div id="no-more-tables">
	<?php $result = mysqli_query($dbc, 'SELECT * FROM client_daily_log_notes WHERE `deleted`=0 '.$clause.' ORDER BY `note_date` DESC');
	if(mysqli_num_rows($result) > 0) { ?>
		<table class="table table-bordered">
			<tr class="hidden-sm hidden-xs">
				<th>Staff</th>
				<th>Contact</th>
				<th>Date</th>
				<th>Note</th>
			</tr>
			<?php while($row = mysqli_fetch_assoc($result)) { ?>
				<tr>
					<td data-title="Staff"><?= get_contact($dbc, $row['created_by']) ?></td>
					<td data-title="Contact"><?= get_contact($dbc, $row['client_id']) ?></td>
					<td data-title="Date"><?= $row['note_date'] ?></td>
					<td data-title="Note"><?= explode('&lt;br',$row['note'])[0] ?></td>
				</tr>
			<?php } ?>
		</table>
	<?php } else {
		echo "<h3>No notes found</h3>";
	} ?>
</div>