<?php
/*
Saleser Listing
*/
include ('../include.php');
?>
<script type="text/javascript">
$(document).on('change', 'select[name="next_action[]"]', function() { selectAction(this); });
$(document).on('change', 'select[name="status[]"]', function() { selectStatus(this); });
function selectStatus(sel) {
	var status = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	var fname = $("#fname").val();
	var lname = $("#lname").val();
	var contactid = $("#session_contactid").val();

	$.ajax({	//create an ajax request to load_page.php
		type: "GET",
		url: "sales_ajax_all.php?fill=sales_status&salesid="+arr[1]+'&status='+status,
		dataType: "html",	//expect html to be returned
		success: function(response){
			if(status == 'Won') {
				alert("Lead Won");
			}
			if(status == 'Lost') {
				alert("Lead Lost and Removed from Sales.");
			}
				location.reload();
		}
	});
}

function selectAction(sel) {
	var action = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({	//create an ajax request to load_page.php
		type: "GET",
		url: "sales_ajax_all.php?fill=sales_action&salesid="+arr[1]+'&action='+action,
		dataType: "html",	//expect html to be returned
		success: function(response){
			location.reload();
		}
	});
}
function followupDate(sel) {
	var reminder = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({	//create an ajax request to load_page.php
		type: "GET",
		url: "sales_ajax_all.php?fill=sales_reminder&salesid="+arr[1]+'&reminder='+reminder,
		dataType: "html",	//expect html to be returned
		success: function(response){
			location.reload();
		}
	});
}
</script>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('sales');
?>

<div class="container triple-pad-bottom">
	<div class="row">
		<div class="col-md-12">

		<div class="col-sm-10"><h1>Sales Dashboard</h1></div>
		<div class="col-sm-2 gap-top"><?php
			if ( config_visible_function ( $dbc, 'sales' ) == 1 ) {
				echo '<a href="field_config_sales.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
				echo '<span class="popover-examples list-inline pull-right" style="margin:15px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
			} ?>
		</div>
		<div class="clearfix gap-bottom"></div>
		
		<?php
			$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT sales FROM field_config"));
			$value_config = ','.$get_field_config['sales'].',';

			$active_today = '';
			$active_week = '';
			$active_month = '';
			$active_custom = '';
			
			if(empty($_GET['type'])) {
				$_GET['type'] = 'today';
			}
			if($_GET['type'] == 'today') {
				$active_today = 'active_tab';
			}
			if($_GET['type'] == 'week') {
				$active_week = 'active_tab';
			}
			if($_GET['type'] == 'month') {
				$active_month = 'active_tab';
			}
			if($_GET['type'] == 'custom') {
				$active_custom = 'active_tab';
			}
			
			echo '<div class="mobile-100-container tab-container">';
				if ( check_subtab_persmission($dbc, 'sales', ROLE, 'how_to_guide') === TRUE ) {
					$result	 = get_how_to_guide( $dbc, 'Sales'); // $dbc, $tile_name
					$num_rows	= mysqli_num_rows($result);
					if ( $num_rows > 0 ) {
						echo "<a href='how_to_guide.php'><button type='button' class='btn brand-btn mobile-block mobile-100'>How to Guide</button></a>&nbsp;&nbsp;";
					} else {
						 echo "<a href='lead_status_definitions.php'><button type='button' class='btn brand-btn mobile-block mobile-100'>How to Guide</button></a>&nbsp;&nbsp;";
					}
				} else {
					echo "<button type='button' class='btn disabled-btn mobile-block mobile-100'>How to Guide</button>&nbsp;&nbsp;";
				}
						
				if ( check_subtab_persmission($dbc, 'sales', ROLE, 'sales_pipeline') === TRUE ) {
					echo "<a href='sales_pipeline.php?status='><button type='button' class='btn brand-btn mobile-block mobile-100'>Sales Pipeline</button></a>&nbsp;&nbsp;";
				} else {
					echo "<button type='button' class='btn disabled-btn mobile-block mobile-100'>Sales Pipeline</button>&nbsp;&nbsp;";
				}
				
				if ( check_subtab_persmission($dbc, 'sales', ROLE, 'schedule') === TRUE ) {
					echo "<a href='sales.php'><button type='button' class='btn brand-btn mobile-block mobile-100 active_tab'>Schedule</button></a>&nbsp;&nbsp;";
				} else {
					echo "<button type='button' class='btn disabled-btn mobile-block mobile-100'>Schedule</button>&nbsp;&nbsp;";
				}
				
				if ( check_subtab_persmission($dbc, 'sales', ROLE, 'reports') === TRUE ) {
					echo "<a href='sales_lead_source_report.php'><button type='button' class='btn brand-btn mobile-block mobile-100'>Reports</button></a>&nbsp;&nbsp;";
				} else {
					echo "<button type='button' class='btn disabled-btn mobile-block mobile-100'>Reports</button>&nbsp;&nbsp;";
				}
				
				echo '</div>';
				echo '<div class="mobile-100-container tab-container1">';
				if (strpos($value_config, ','."Today".',') !== FALSE) {
					echo "<a href='sales.php?type=today'><button type='button' class='btn brand-btn mobile-block mobile-100 ".$active_today."'>Today</button></a>&nbsp;&nbsp;";
				}
				if (strpos($value_config, ','."This Week".',') !== FALSE) {
					echo "<a href='sales.php?type=week'><button type='button' class='btn brand-btn mobile-block mobile-100 ".$active_week."'>This Week</button></a>&nbsp;&nbsp;";
				}
				if (strpos($value_config, ','."This Month".',') !== FALSE) {
					echo "<a href='sales.php?type=month'><button type='button' class='btn brand-btn mobile-block mobile-100 ".$active_month."'>This Month</button></a>&nbsp;&nbsp;";
				}
				if (strpos($value_config, ','."Custom".',') !== FALSE) {
					echo "<a href='sales.php?type=custom'><button type='button' class='btn brand-btn mobile-block mobile-100 ".$active_custom."'>Custom</button></a>&nbsp;&nbsp;";
				}
			echo '</div>';
		?>
		
		<div class="notice double-gap-bottom double-gap-top popover-examples">
			<div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
			<div class="col-sm-11"><span class="notice-name">NOTE:</span>
			Organization is key. In this section all your sales leads are sorted by priority and when attention is due. The Today sub tab shows all scheduled items for today and items that require scheduling. The This Week and This Month sub tabs take your reminders and split them up into easy to find sub tabs where you can review both weekly and monthly where you’re at with all your leads. If you require further sorting, use the Custom sub tab and select the dates you want to sort your leads into to see all details.</div>
			<div class="clearfix"></div>
		</div>

		<form name="form_sites" method="post" action="" class="form-inline" role="form">
			<div class="pad-top pad-bottom clearfix">
				<?php
				if(vuaed_visible_function($dbc, 'sales') == 1) {
					echo '<div class="mobile-100-container" style="left:-8px;position:relative;">';
						echo '<a href="add_sales.php" class="btn brand-btn mobile-block pull-right mobile-100-pull-right">Add Sales</a>';
						echo '<span class="popover-examples list-inline pull-right" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Add sales lead details here."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
					echo '</div>';
				}
				$search_client = '';
				$search_contact = '';
				$search_action = '';
				$search_status = '';
				$search_date = '';
				if(isset($_POST['search_user_submit'])) {
					$search_client = $_POST['search_client'];
					$search_contact = $_POST['search_contact'];
					$search_action = $_POST['search_action'];
					$search_status = $_POST['search_status'];
					if($_GET['type'] == 'custom') {
						$search_date = $_POST['search_date'];
					}
				}
				if (isset($_POST['display_all_inventory'])) {
					$search_client = '';
					$search_contact = '';
					$search_action = '';
					$search_status = '';
					$search_date = '';
				}
				?>
			</div>

				<?php if($_GET['type'] == 'custom') { ?>
					<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4" style='max-width:200px;'>
					<label for="search_site" style='width:100%; text-align: center;'>By Created Date:</label>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-8 col-xs-8">
					<input type="text" name="search_date" value="<?php echo $search_date; ?>" class="datepicker form-control"><br>
					</div>
					<div class="clearfix" style='margin:10px;'>
					</div>
				<?php } ?>
				<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4" style='max-width:200px;'>
				<label for="search_site" style='width:100%; text-align:center;'>By Business:</label>
				</div>
				<div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
				<select data-placeholder="Select a Business" name="search_client" class="chosen-select-deselect form-control">
					<option value=""></option>
					<?php
					$query = mysqli_query($dbc,"SELECT DISTINCT(c.name), t.businessid FROM contacts c, sales t WHERE t.businessid=c.contactid order by c.name");
					while($row = mysqli_fetch_array($query)) {
					?><option <?php if ($row['businessid'] == $search_client) { echo " selected"; } ?> value='<?php echo $row['businessid']; ?>' ><?php echo decryptIt($row['name']); ?></option>
				<?php	} ?>
				</select>
				</div>
				<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4" style='max-width:200px;'>
				<label for="search_site" style='width:100%; text-align: center;'>By Contact:</label>
				</div>
				<div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
				<select data-placeholder="Select a Contact" name="search_contact" class="chosen-select-deselect form-control">
					<option value=""></option>
					<?php
					$query = mysqli_query($dbc,"SELECT DISTINCT(c.contactid), c.first_name, c.last_name, t.contactid FROM contacts c, sales t WHERE t.contactid=c.contactid order by c.first_name");
					while($row = mysqli_fetch_array($query)) {
					?><option <?php if ($row['contactid'] == $search_contact) { echo " selected"; } ?> value='<?php echo $row['contactid']; ?>' ><?php echo decryptIt($row['first_name']).' '.decryptIt($row['last_name']); ?></option>
				<?php	} ?>
				</select>
				</div><div class="clearfix top-marg-mobile">
				</div>
				<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4" style='max-width:200px;'>
				<label for="search_site" style='width:100%; text-align: center;'>By Action:</label>
				</div>
				<div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
				<select data-placeholder="Select Next Action" name="search_action" class="chosen-select-deselect form-control" width="380">
					<option value=""></option>
					<!--
					<option <?php //if ($search_action == "Email") { echo " selected"; } ?> value="Email">Email</option>
					<option <?php //if ($search_action == "Follow Up") { echo " selected"; } ?> value="Follow Up">Follow Up</option>
					<option <?php //if ($search_action == "Phone Call") { echo " selected"; } ?> value="Phone Call">Phone Call</option>
					<option <?php //if ($search_action == "Initial Meeting") { echo " selected"; } ?> value="Initial Meeting">Initial Meeting</option>
					<option <?php //if ($search_action == "Meeting") { echo " selected"; } ?> value="Meeting">Meeting</option>
					<option <?php //if ($search_action == "Presentation") { echo " selected"; } ?> value="Presentation">Presentation</option>
					<option <?php //if ($search_action == "Estimate") { echo " selected"; } ?> value="Estimate">Estimate</option>
					<option <?php //if ($search_action == "Quote Sent") { echo " selected"; } ?> value="Quote Sent">Quote Sent</option>
					<option <?php //if ($search_action == "Closing Meeting") { echo " selected"; } ?> value="Closing Meeting">Closing Meeting</option>
					<option <?php //if ($search_action == "Waiting") { echo " selected"; } ?> value="Waiting">Waiting</option>
					-->
					<?php
						$tabs		= get_config ( $dbc, 'sales_next_action' );
						$each_tab	= explode ( ',', $tabs );
						
						foreach ( $each_tab as $cat_tab ) {
							$selected = ( $search_action == $cat_tab ) ? 'selected="selected"' : '';
							echo '<option ' . $selected . ' value="' . $cat_tab . '">' . $cat_tab . '</option>';
						}
					?>
				</select>
				</div>
				<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4 " style='max-width:200px;'>
				<label for="search_site" style='width:100%; text-align: center;'>By Status:</label>
				</div>
				<div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
				<select data-placeholder="Select a Status" name="search_status" class="chosen-select-deselect form-control" width="380">
					<option value=""></option>
					<!--
					<option <?php //if ($search_status == "Pending") { echo " selected"; } ?> value="Pending">Pending</option>
					<option <?php //if ($search_status == "Prospect") { echo " selected"; } ?> value="Prospect">Prospect</option>
					<option <?php //if ($search_status == "Qualification") { echo " selected"; } ?> value="Qualification">Qualification</option>
					<option <?php //if ($search_status == "Needs Analysis") { echo " selected"; } ?> value="Needs Analysis">Needs Analysis</option>
					<option <?php //if ($search_status == "Propose Quote") { echo " selected"; } ?> value="Propose Quote">Propose Quote</option>
					<option <?php //if ($search_status == "Negotiations") { echo " selected"; } ?> value="Negotiations">Negotiations</option>
					<option <?php //if ($search_status == "Won") { echo " selected"; } ?> value="Won">Won</option>
					<option <?php //if ($search_status == "Lost") { echo " selected"; } ?> value="Lost">Lost</option>
					<option <?php //if ($search_status == "Abandoned") { echo " selected"; } ?> value="Abandoned">Abandoned</option>
					<option <?php //if ($search_status == "Future Review") { echo " selected"; } ?> value="Future Review">Future Review</option>
					-->
					<?php
					/*
					$tabs = get_config($dbc, 'sales_lead_status');
					$each_tab = explode(',', $tabs);
					foreach ($each_tab as $cat_tab) {
						if ($search_status == $cat_tab) {
							$selected = 'selected="selected"';
						} else {
							$selected = '';
						}
						echo "<option ".$selected." value='". $cat_tab."'>".$cat_tab.'</option>';
					}
					*/
					?>
					<?php
						$tabs		= get_config ( $dbc, 'sales_lead_status' );
						$each_tab	= explode ( ',', $tabs );
						foreach ( $each_tab as $cat_tab ) {
							$selected = ( $status == $cat_tab ) ? 'selected="selected"' : '';
							echo "<option " . $selected . " value='" . $cat_tab . "'>" . $cat_tab . '</option>';
						}
					?>
				</select>
				</div>
				<div class="clearfix" style='margin:10px;'>
				</div>
				<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4"></div>
				<div class="col-lg-8 col-md-7 col-sm-8 col-xs-8">
				<button type="submit" name="search_user_submit" value="Search" class="btn brand-btn mobile-block">Search</button>
				
				<button type="submit" name="display_all_inventory" value="Display All" class="btn brand-btn mobile-block">Display All</button>
				</div>
			<br><br>

			
			<?php
			/* Pagination Counting */
			$rowsPerPage = 25;
			$pageNum = 1;

			if(isset($_GET['page'])) {
				$pageNum = $_GET['page'];
			}

			$offset = ($pageNum - 1) * $rowsPerPage;
			
			$add_query = '';
			if($search_client != '') {
				$add_query = " AND businessid='$search_client'";
			}
			if($search_contact != '') {
				$add_query = " AND contactid='$search_contact'";
			}
			if($search_action != '') {
				$add_query = " AND next_action='$search_action'";
			}
			if($search_status != '') {
				$add_query = " AND status='$search_status'";
			}
			if($_GET['type'] == 'today') {
				$query_check_credentials = "SELECT * FROM sales WHERE status NOT IN('Won','Lost','Customers') AND (created_date=DATE(NOW()) OR new_reminder=DATE(NOW())) $add_query LIMIT $offset, $rowsPerPage";
				$query = "SELECT count(*) as numrows FROM sales WHERE status NOT IN('Won','Lost','Customers') AND (created_date=DATE(NOW()) OR new_reminder=DATE(NOW())) $add_query";
			}
			if($_GET['type'] == 'week') {
				$query_check_credentials = "SELECT * FROM sales WHERE status NOT IN('Won','Lost','Customers') AND (WEEKOFYEAR(created_date)=WEEKOFYEAR(NOW()) OR WEEKOFYEAR(new_reminder)=WEEKOFYEAR(NOW())) $add_query LIMIT $offset, $rowsPerPage";
				$query = "SELECT count(*) as numrows FROM sales WHERE status NOT IN('Won','Lost','Customers') AND (WEEKOFYEAR(created_date)=WEEKOFYEAR(NOW()) OR WEEKOFYEAR(new_reminder)=WEEKOFYEAR(NOW())) $add_query";
			}
			if($_GET['type'] == 'month') {
				$query_check_credentials = "SELECT * FROM sales WHERE status NOT IN('Won','Lost','Customers') AND ((YEAR(created_date) = YEAR(NOW()) AND MONTH(created_date)=MONTH(NOW())) OR (YEAR(new_reminder) = YEAR(NOW()) AND MONTH(new_reminder)=MONTH(NOW()))) $add_query LIMIT $offset, $rowsPerPage";
				$query = "SELECT count(*) as numrows FROM sales WHERE status NOT IN('Won','Lost','Customers') AND ((YEAR(created_date) = YEAR(NOW()) AND MONTH(created_date)=MONTH(NOW())) OR (YEAR(new_reminder) = YEAR(NOW()) AND MONTH(new_reminder)=MONTH(NOW()))) $add_query";
			}
			if($_GET['type'] == 'custom') {
				if($search_date == '') {
					$query_check_credentials = "SELECT * FROM sales WHERE status NOT IN('Won','Lost','Customers') AND (created_date=DATE(NOW()) OR created_date=DATE(NOW())) $add_query LIMIT $offset, $rowsPerPage";
					$query = "SELECT count(*) as numrows FROM sales WHERE status NOT IN('Won','Lost','Customers') AND (created_date=DATE(NOW()) OR created_date=DATE(NOW())) $add_query";
				} else {
					$query_check_credentials = "SELECT * FROM sales WHERE status NOT IN('Won','Lost','Customers') AND (created_date='$search_date' OR new_reminder='$search_date') $add_query LIMIT $offset, $rowsPerPage";
					$query = "SELECT count(*) as numrows FROM sales WHERE status NOT IN('Won','Lost','Customers') AND (created_date='$search_date' OR new_reminder='$search_date') $add_query";
				}
			}

			$result = mysqli_query($dbc, $query_check_credentials);

			$num_rows = mysqli_num_rows($result);
			if($num_rows > 0) {
				echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
				$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT sales_dashboard FROM field_config WHERE `fieldconfigid` = 1"));
				$value_config = ','.$get_field_config['sales_dashboard'].',';

				echo "<div id='no-more-tables'><table class='table table-bordered'>";
				echo "<tr class='hidden-xs hidden-sm'>";
				if (strpos($value_config, ','."Lead#".',') !== FALSE) {
					echo '<th>Lead#</th>';
				}
				if (strpos($value_config, ','."Business/Contact".',') !== FALSE) {
					echo '<th>Business/Contact</th>';
				}
				if (strpos($value_config, ','."Phone/Email".',') !== FALSE) {
					echo '<th>Phone/Email</th>';
				}
				if (strpos($value_config, ','."Next Action".',') !== FALSE) {
					echo '<th>Next Action</th>';
				}
				if (strpos($value_config, ','."Reminder".',') !== FALSE) {
					echo '<th>Reminder</th>';
				}
				if (strpos($value_config, ','."Notes".',') !== FALSE) {
					echo '<th>Notes</th>';
				}
				if (strpos($value_config, ','."Status".',') !== FALSE) {
					echo '<th>Status</th>';
				}
				echo '<th>Function</th>';
				echo "</tr>";
			} else {
				echo "<h2>No Record Found.</h2>";
			}

			while($row = mysqli_fetch_array( $result ))
			{
				echo "<tr>";
				if (strpos($value_config, ','."Lead#".',') !== FALSE) {
				echo '<td data-title="Lead#"><a href=\'add_sales.php?salesid='.$row['salesid'].'\'>' . $row['salesid'] . '</a></td>';
				}

				if (strpos($value_config, ','."Business/Contact".',') !== FALSE) {
				echo '<td data-title="Business">' . get_contact($dbc, $row['businessid'], 'name') . '<br>';
				echo get_contact($dbc, $row['contactid'], 'first_name').' '.get_contact($dbc, $row['contactid'], 'last_name') . '</td>';
				}
				if (strpos($value_config, ','."Phone/Email".',') !== FALSE) {
				echo '<td data-title="Primary Phone">' . $row['primary_number'] . '<br>';
				echo '' . $row['email_address'] . '</td>';
				}
				if (strpos($value_config, ','."Next Action".',') !== FALSE) {
				?>
				<td data-title="Next Action">
					<select id="action_<?php echo $row['salesid']; ?>" data-placeholder="Select Next Action" name="next_action[]" class=" form-control chosen-select-deselect" width="380">
						<option value=""></option>
						<!--
						<option <?php //if ($row['next_action'] == "Email") { echo " selected"; } ?> value="Email">Email</option>
						<option <?php //if ($row['next_action'] == "Follow Up") { echo " selected"; } ?> value="Follow Up">Follow Up</option>
						<option <?php //if ($row['next_action'] == "Phone Call") { echo " selected"; } ?> value="Phone Call">Phone Call</option>
						<option <?php //if ($row['next_action'] == "Initial Meeting") { echo " selected"; } ?> value="Initial Meeting">Initial Meeting</option>
						<option <?php //if ($row['next_action'] == "Meeting") { echo " selected"; } ?> value="Meeting">Meeting</option>
						<option <?php //if ($row['next_action'] == "Presentation") { echo " selected"; } ?> value="Presentation">Presentation</option>
						<option <?php //if ($row['next_action'] == "Estimate") { echo " selected"; } ?> value="Estimate">Estimate</option>
						<option <?php //if ($row['next_action'] == "Quote Sent") { echo " selected"; } ?> value="Quote Sent">Quote Sent</option>
						<option <?php //if ($row['next_action'] == "Closing Meeting") { echo " selected"; } ?> value="Closing Meeting">Closing Meeting</option>
						<option <?php //if ($row['next_action'] == "Waiting") { echo " selected"; } ?> value="Waiting">Waiting</option>
						-->
						<?php
							$tabs		= get_config ( $dbc, 'sales_next_action' );
							$each_tab	= explode ( ',', $tabs );
							
							foreach ( $each_tab as $cat_tab ) {
								$selected = ( $row['next_action'] == $cat_tab ) ? 'selected="selected"' : '';
								if ( $cat_tab !== '' && $cat_tab !== NULL ) {
									echo '<option ' . $selected . ' value="' . $cat_tab . '">' . $cat_tab . '</option>';
								}
							}
						?>
					</select>
				</td>
				<?php
				}
				if (strpos($value_config, ','."Reminder".',') !== FALSE) {
				echo '<td data-title="Reminder"><input name="new_reminder[]" type="text" id="reminder_'.$row['salesid'].'" onchange="followupDate(this)" class="datepicker" value="'.$row['new_reminder'].'"></td>';
				}

				if (strpos($value_config, ','."Notes".',') !== FALSE) {
				echo '<td data-title="Function">';
				echo '<a href=\'add_sales.php?salesid='.$row['salesid'].'&go=notes\'>Add/View</a>';
				echo '</td>';
				}
				if (strpos($value_config, ','."Status".',') !== FALSE) {
				?>

				<td data-title="Status">
					<select id="status_<?php echo $row['salesid']; ?>" data-placeholder="Select a Status" name="status[]" class="form-control chosen-select-deselect" width="380">
						<option value=""></option>
						<!--
						<option <?php //if($row['status'] == "Pending") { echo " selected"; } ?> value="Pending">Pending</option>
						<option <?php //if($row['status'] == "Prospect") { echo " selected"; } ?> value="Prospect">Prospect</option>
						<option <?php //if($row['status'] == "Qualification") { echo " selected"; } ?> value="Qualification">Qualification</option>
						<option <?php //if($row['status'] == "Needs Analysis") { echo " selected"; } ?> value="Needs Analysis">Needs Analysis</option>
						<option <?php //if($row['status'] == "Propose Quote") { echo " selected"; } ?> value="Propose Quote">Propose Quote</option>
						<option <?php //if($row['status'] == "Negotiations") { echo " selected"; } ?> value="Negotiations">Negotiations</option>
						<option <?php //if($row['status'] == "Won") { echo " selected"; } ?> value="Won">Won</option>
						<option <?php //if($row['status'] == "Lost") { echo " selected"; } ?> value="Lost">Lost</option>
						<option <?php //if($row['status'] == "Abandoned") { echo " selected"; } ?> value="Abandoned">Abandoned</option>
						<option <?php //if($row['status'] == "Future Review") { echo " selected"; } ?> value="Future Review">Future Review</option>
						-->
						<?php
						/*
						$tabs = get_config($dbc, 'sales_lead_status');
						$each_tab = explode(',', $tabs);
						foreach ($each_tab as $cat_tab) {
							if($row['status'] == $cat_tab) {
								$selected = 'selected="selected"';
							} else {
								$selected = '';
							}
							if($cat_tab !== '' && $cat_tab !== NULL) {
								echo "<option ".$selected." value='". $cat_tab."'>".$cat_tab.'</option>';
							}
						}
						*/
						?>
						<?php
							$tabs		= get_config ( $dbc, 'sales_lead_status' );
							$each_tab	= explode ( ',', $tabs );
							
							foreach ( $each_tab as $cat_tab ) {
								$selected = ( $row['status'] == $cat_tab ) ? 'selected="selected"' : '';
								echo "<option " . $selected . " value='" . $cat_tab . "'>" . $cat_tab . '</option>';
							}
						?>
					</select>
				</td>
				<?php
				}

				echo '<td data-title="Function">';
				if(vuaed_visible_function($dbc, 'sales') == 1) {
					echo "<a href='convert_sales_lead.php?leadid=".$row['salesid']."'>Save to Customers</a>";
				}
				echo '</td>';

				echo "</tr>";
			}
			if($num_rows > 0) {
				echo '</table></div>';
			}
			if(vuaed_visible_function($dbc, 'sales') == 1) {
			echo '<a href="add_sales.php" class="btn brand-btn mobile-block pull-right">Add Sales</a>';
			echo '<span class="popover-examples list-inline pull-right" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Add sales lead details here."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
			}

			?>

		</form>

		</div>

		</div>
	</div>
</div>
<?php include ('../footer.php'); ?>
