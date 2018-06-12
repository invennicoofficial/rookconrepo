<?php include_once('../include.php');
checkAuthorised('staff');
$search_from = '';
$search_to = '';
$clause = '';
if(!empty($_POST['search_from'])) {
	$search_from = $_POST['search_from'];
	$clause .= " AND reminder_date >= '$search_from'";
}
if(!empty($_POST['search_to'])) {
	$search_to = $_POST['search_to'];
	$clause .= " AND reminder_date <= '$search_to'";
}
$detect = new Mobile_Detect;
$is_mobile = ( $detect->isMobile() ) ? true : false;
?>
<form method="post" action="">
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pad-top">
		<label for="search_from" class="control-label">Search From Date:</label>
	<!-- </div>
	<div class="col-lg-4 col-md-3 col-sm-8 col-xs-8 pad-top"> -->
		<input type="text" name="search_from" class="form-control datepicker inline <?= $is_mobile ? 'pull-right' : '' ?>" value="<?php echo $search_from; ?>">
	</div>
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pad-top">
		<label for="search_from" class="control-label">Search To Date:</label>
	<!-- </div>
	<div class="col-lg-4 col-md-3 col-sm-8 col-xs-8 pad-top"> -->
		<input type="text" name="search_to" class="form-control datepicker inline <?= $is_mobile ? 'pull-right' : '' ?>" value="<?php echo $search_to; ?>">
	</div>
	<div class="col-sm-4 col-xs-12 col-lg-3 pad-top pull-xs-right">
		<button type="submit" name="search_contacts_submit" value="Search" class="btn brand-btn mobile-block">Search</button>
		<span class="popover-examples list-inline"><a style="margin:5px 0 0 15px;" data-toggle="tooltip" data-placement="top" title="Refreshes the page to display all Staff Reminders."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
		<a href="" class="btn brand-btn mobile-block">Display All</a>
	</div>
</form>
<div class="clearfix"></div>
<?php $rowsPerPage = 25;
$pageNum = (empty($_GET['page']) ? 1 : $_GET['page']);
$offset = $rowsPerPage * ($pageNum - 1);
$reminders = mysqli_query($dbc, "SELECT * FROM `reminders` WHERE `deleted`=0 AND `reminder_type`='STAFF' $clause LIMIT $offset, $rowsPerPage");
$sql_num = "SELECT COUNT(*) numrows FROM `reminders` WHERE `deleted`=0 AND `reminder_type`='STAFF' $clause";
if(mysqli_num_rows($reminders) == 0)
{
	echo "<h1>No Reminders Found</h1>";
}
else {
	echo '<div class="pagination_links">';
	display_pagination($dbc, $sql_num, $pageNum, $rowsPerPage);
	echo '</div>' ?>
	<table id="no-more-tables" class="table table-bordered">
		<tr class="hide-titles-mob">
			<th>Staff Members</th>
			<th>Reminder Date</th>
			<th>Subject</th>
			<th>Sent</th>
			<th>Function</th>
		</tr>
		<?php while($reminder = mysqli_fetch_array($reminders)) { ?>
			<tr>
				<td data-title="Staff"><?php $staff = explode(',',$reminder['contactid']);
					foreach($staff as $person) {
							echo get_staff($dbc, $person)."<br />\n";
					} ?></td>
				<td data-title="Date"><?php echo $reminder['reminder_date']." (".$reminder['reminder_time'].")"; ?></td>
				<td data-title="Subject"><?php echo $reminder['subject']; ?></td>
				<td data-title="Sent"><?php echo ($reminder['sent'] == 1 ? 'Sent' : 'Not Sent'); ?></td>
				<td data-title="">
					<?php $function_urls = [];
					if(edit_visible_function($dbc, 'staff') > 0) {
						$function_urls[] = '<a href="add_reminder.php?reminderid='.$reminder['reminderid'].'">Edit</a>';
					}
					if(archive_visible_function($dbc, 'staff') > 0) {
						$function_urls[] = '<a href="add_reminder.php?reminderid='.$reminder['reminderid'].'&status=archive">Archive</a>';
					}
					echo implode(' | ', $function_urls); ?>						
				</td>
			</tr>
		<?php } ?>
	</table>
<?php echo '<div class="pagination_links">';
	display_pagination($dbc, $sql_num, $pageNum, $rowsPerPage);
	echo '</div>';
} ?>