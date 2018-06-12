<?php
if((!empty($_GET['projectid'])) && (!empty($_GET['type']))) {
    $projectid = $_GET['projectid'];
    $type = $_GET['type'];
    $approved_date = date('Y-m-d');
    $project_name = get_client_project($dbc, $projectid, 'project_name');
    $contactid = $_SESSION['contactid'];

    if($type == 'reject') {
        echo insert_day_overview($dbc, $contactid, 'Client Project', date('Y-m-d'), '', 'Rejected Client Project '.$project_name);

        $query_update_report = "UPDATE `client_project` SET `deleted` = 1 WHERE `projectid` = '$projectid'";
        $result_update_report = mysqli_query($dbc, $query_update_report);
        $message = 'Client Project Rejected and Removed.';
		$url = 'project.php?tab='.$nav_tabs[0];
    } else {
        echo insert_day_overview($dbc, $contactid, 'Client Project', date('Y-m-d'), '', 'Approved Client Project '.$project_name);

        $history = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']).' Approved on '.date('Y-m-d H:i:s').'<br>';
        $query_update_report = "UPDATE `client_project` SET `status` = 'Approve as Project', `history` = CONCAT(history,'$history'), `approved_date` = '$approved_date' WHERE `projectid` = '$projectid'";
        $result_update_report = mysqli_query($dbc, $query_update_report);
		$projecttype = mysqli_fetch_array(mysqli_query($dbc, "SELECT `projecttype` FROM `client_project` WHERE `projectid`='$projectid'"))['projecttype'];
        $message = 'Client Project Approved';
		$url = 'project.php?type='.$projecttype;
    }

    echo '<script type="text/javascript"> window.location.replace("'.$url.'"); </script>';
}

if((!empty($_GET['projectid'])) && (!empty($_GET['status']))) {
    $projectid = $_GET['projectid'];
    $status = $_GET['status'];
    $query_update_report = "UPDATE `client_project` SET `status` = '$status' WHERE `projectid` = '$projectid'";
    $result_update_report = mysqli_query($dbc, $query_update_report);

    if($status == 'Approve') {
        echo '<script type="text/javascript"> alert("Client Project Approved and Moved to Active Projects."); window.location.replace("project.php?tab=active"); </script>';
    } else {
        echo '<script type="text/javascript"> alert("Client Project Denied and Removed from Projects."); window.location.replace("project.php"); </script>';
    }
}
?>
<script type="text/javascript">
$(document).on('change', 'select[name="status[]"]', function() { selectStatus(this); });
function selectStatus(sel) {
	var status = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
    var contactid = $("#session_contactid").val();
	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "<?php echo WEBSITE_URL;?>/ajax_all.php?fill=client_project_status&projectid="+arr[1]+'&status='+status+'&contactid='+contactid,
		dataType: "html",   //expect html to be returned
		success: function(response){
            if(status == 'Approve as Project') {
                alert("Project Approved.");
				window.location.reload();
            } else if(status == 'Go To Estimate') {
                alert("Project Moved to Estimate.");
				window.location.reload();
            } else if(status == 'Archive/Delete') {
                alert("Project Removed.");
				$(sel).closest('tr').remove();
            } else {
			    location.reload();
            }
		}
	});
}
function ETAQuoteDate(sel) {
	var action = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "<?php echo WEBSITE_URL;?>/ajax_all.php?fill=client_project_etaquote&id="+arr[1]+'&name='+action,
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
		}
	});
}
function review_project(id) {
	var contactid = '<?php echo $_SESSION['contactid']; ?>';

	$.ajax({
		type: "GET",
		url: "<?php echo WEBSITE_URL; ?>/Client Projects/project_ajax_all.php?fill=review_project&project="+id+"&contact="+contactid,
		success: function(response) {
			location.reload();
		}
	});
}
function assign_review(btn) {
	$(btn).closest('td').find('.review_info').hide();
	$(btn).closest('td').find('.assign_review').show();
}
function save_assign(btn) {
	$.ajax({
		url: 'project_ajax_all.php?fill=assign_review',
		method: 'POST',
		data: { project: $(btn).closest('td').data('project'), staff: $(btn).closest('td').find('[name=assign_staff]').val(), date: $(btn).closest('td').find('[name=assign_date]').val() },
		success: function(response) {
			console.log(response);
		}
	});
	close_assign(btn);
}
function close_assign(btn) {
	$(btn).closest('td').find('select option').removeAttr('selected');
	$(btn).closest('td').find('select').trigger('change.select2');
	$(btn).closest('td').find('input').val('<?= date('Y-m-d',strtotime('+2weeks')) ?>');
	$(btn).closest('td').find('.review_info').show();
	$(btn).closest('td').find('.assign_review').hide();
}

$(document).ready(function() {
	$('.iframe_open').click(function(){
			var id = $(this).attr('id');
		   $('#iframe_instead_of_window').attr('src', 'project_history.php?projectid='+id);
		   $('.iframe_title').text('Client Project History');
		   $('.iframe_holder').show();
		   $('.hide_on_iframe').hide();
	});

	$('.close_iframer').click(function(){
		$('.iframe_holder').hide();
		$('.hide_on_iframe').show();
	});
});
</script>

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
<input type="hidden" id="session_contactid" value="<?php echo $_SESSION['contactid']; ?>" />

<?php
	$status = '';
	$nav_tabs = array_filter(explode(get_config($dbc, 'client_project_tabs')));
	switch($current_tab) {
		case 'pending': $status = "='Pending' AND p.`deleted`=0"; break;
		case 'active':
			if(!in_array('pending', $nav_tabs)) {
				$status = " NOT IN ('Archived','Rejected','Archive/Delete') AND p.`deleted`=0";
			} else {
				$status = " NOT IN ('Pending','Archived','Rejected','Archive/Delete') AND p.`deleted`=0";
			}
			break;
		case 'archived': $status = " IN ('Archived','Rejected','Archive/Delete') AND p.`deleted`=1"; break;
	}
	$search_cust = '';
	if(isset($_POST['search_user_submit'])) {
		$search_cust = $_POST['search_cust'];
	} else {
		$search_user = $contactid;
	}
	if(isset($_POST['search_project'])) {
		$search_project = $_POST['search_project'];
	} else {
		$search_project = '';
	}
	if (isset($_POST['display_all_inventory'])) {
		$search_user = $contactid;
		$search_project = '';
	}
?>

<div class="search-group">
	<div class="form-group col-lg-9 col-md-8 col-sm-12 col-xs-12">
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
			<div class="col-sm-4">
				<label for="site_name" class="control-label">
					<span class="popover-examples list-inline" style="margin:0 2px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to see all clients that you have created a project for."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					Search By Client:</label>
			</div>
			<div class="col-sm-8">
				<select data-placeholder="Select a Client" name="search_cust" class="chosen-select-deselect form-control">
					<option></option>
					<?php $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT c.`contactid`, c.`first_name`, c.`last_name`, c.`name` FROM `client_project` p LEFT JOIN `contacts` c ON CONCAT(',',p.`clientid`,',') LIKE CONCAT('%,',c.`contactid`,',%') WHERE p.`status`$status"), MYSQLI_ASSOC));
					foreach($query as $custid) { ?>
						<option <?php if ($custid == $search_cust) { echo " selected"; } ?> value='<?php echo  $custid; ?>' ><?php echo get_contact($dbc, $custid); ?></option><?php
					} ?>
				</select>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
			<div class="col-sm-4">
				<label for="site_name" class="control-label">
					<span class="popover-examples list-inline" style="margin:0 2px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to see all projects that you have created."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					Search By Project Short Name:</label>
			</div>
			<div class="col-sm-8">
				<select data-placeholder="Select a Project Name" name="search_project" class="chosen-select-deselect form-control">
					<option value=""></option><?php
					$query = mysqli_query($dbc,"SELECT project_name FROM `client_project` p WHERE p.`status`$status ORDER BY `project_name`");
					while($row = mysqli_fetch_array($query)) { ?>
						<option <?php if ($row['project_name'] == $search_project) { echo " selected"; } ?> value='<?php echo  $row['project_name']; ?>' ><?php echo $row['project_name']; ?></option><?php
					} ?>
				</select>
			</div>
		</div>
	</div>
	<div class="form-group col-lg-3 col-md-4 col-sm-12 col-xs-12">
		<div style="display:inline-block; padding: 0 0.5em;">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here after you have made your customer selection."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<button type="submit" name="search_user_submit" value="Search" class="btn brand-btn mobile-block">Search</button>
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click to refresh the page and see all projects within this tab."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<button type="submit" name="display_all_inventory" value="Display All" class="btn brand-btn mobile-block">Display All</button>
		</div>
	</div><!-- .form-group -->
	<div class="clearfix"></div>
</div>

<div id='no-more-tables'>
<?php
if($type != 'Pending') {
	if(vuaed_visible_function($dbc, 'client_projects') == 1) {
		echo '<br /><div class="pull-right">';
			echo '<a href="add_project.php?type='.$type.'&from_url='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'" class="btn brand-btn pull-right">Add Client Project</a>';
			echo '<div class="popover-examples list-inline pull-right" style="margin:5px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add a new client project."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></div>';
		echo '</div>';
	}
}

$rowsPerPage = 25;
$pageNum = 1;

if(isset($_GET['page'])) {
	$pageNum = $_GET['page'];
}

$offset = ($pageNum - 1) * $rowsPerPage;

if($search_cust != '') {
	$query_check_credentials = "SELECT p.*, c.name FROM client_project p, contacts c WHERE p.clientid = c.contactid AND p.`clientid`='$search_cust' AND p.`project_name` LIKE '%$search_project%' AND p.`status`$status ORDER BY projectid DESC";
} else if($search_project != '') {
	$query_check_credentials = "SELECT p.*, c.name FROM client_project p, contacts c WHERE p.clientid = c.contactid AND p.`project_name` LIKE '%$search_project%' AND p.`status`$status ORDER BY projectid DESC";
} else {
	$query_check_credentials = "SELECT * FROM client_project p WHERE status$status ORDER BY projectid DESC LIMIT $offset, $rowsPerPage";
	$query   = "SELECT COUNT(projectid) AS numrows FROM client_project p WHERE status$status";
}

$result = mysqli_query($dbc, $query_check_credentials);

$num_rows = mysqli_num_rows($result);
if($num_rows > 0) {

	// Added Pagination //
	if($search_cust == '' && $search_project == '') {
	echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
	}
	// Pagination Finish //

	echo '<table class="table table-bordered">
		<tr class="hidden-xs hidden-sm">
		<th>Project #</th>
		<th>Client</th>
		<th>Project Name & Created Date</th>
		<th>Project Value</th>
		<th>Notes</th>
		<th>Function</th>
		<th>Reviewed</th>
		<th>History</th>
		</tr>';
} else {
	echo "<h2>No Record Found.</h2>";
}
$staff_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE `category`='Staff' AND `status`=1 AND `deleted`=0"),MYSQLI_ASSOC));

while($row = mysqli_fetch_array( $result )) {
	$projectid = $row['projectid'];
	echo '<input type="hidden" name="projectid_dashboard" value="'.$row['projectid'].'" /><tr>';
	echo '<td data-title="Project #"><a href="review_project.php?type=project_path&projectid='.$projectid.'&from_url='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'">#'.$projectid.'</a></td>';

	$clientid = explode(',',$row['clientid']);
	$businessid = $row['businessid'];
	if($businessid ==  '' || $businessid ==  0) {
		$businessid = get_contact($dbc, $clientid, 'businessid');
	}
	$client_name = [];
	foreach($clientid as $client) {
		$client_name[] = get_contact($dbc, $client, "");
	}
	echo '<td data-title="Client">'.implode('<br />', $client_name) . '</td>';

	echo '<td data-title="Name & Created Date">' . $row['project_name'] . '<br>'.$row['start_date'].'</td>';
	echo '<td data-title="Project Value">$' . $row['total_price'] . '</td>';

	echo '<td data-title="Notes"><a href=\'add_project.php?type='.$type.'&projectid='.$row['projectid'].'&note=add_view\'>Add/View</a></td>';

	echo '<td data-title="Function">';
	if(vuaed_visible_function($dbc, 'project') == 1) {
		echo '<a href=\'add_project.php?type='.$type.'&projectid='.$row['projectid'].'&from_url='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'\'>Edit</a> | ';
	}
	if($_GET['type'] == 'Pending') {
		echo '<a href=\'project.php?projectid='.$row['projectid'].'&type=reject\'>Reject</a> | <a href=\'project.php?projectid='.$row['projectid'].'&type=approve\'>Approve</a>';
	} else { ?>
	<div class="form-group">
		<select id="projectstatus_<?php echo $row['projectid']; ?>"  data-placeholder="Choose a Status..." name="status[]" class="chosen-select-deselect form-control input-sm">
		  <option value=""></option>
		  <option value="Pending" <?php if($row['status'] == "Pending") { echo " selected"; } ?> >Pending</option>
		  <option value="In Development" <?php if($row['status'] == "In Development")  { echo " selected"; } ?> >In Development</option>
		  <option value="Go to Estimate" <?php if($row['status'] == "Go to Estimate")  { echo " selected"; } ?> >Go to Estimate</option>
		  <option value="Approve as Project" <?php if($row['status'] == "Approve as Project")  { echo " selected"; } ?> >Approve as <?php echo (PROJECT_TILE == 'Projects' ? 'Project' : (PROJECT_TILE == 'Jobs' ? 'Job' : PROJECT_TILE)); ?></option>
		  <option value="Attach To A Project" <?php if($row['status'] == "Attach To A Project")  { echo " selected"; } ?> >Attach to a <?php echo (PROJECT_TILE == 'Projects' ? 'Project' : (PROJECT_TILE == 'Jobs' ? 'Job' : PROJECT_TILE)); ?></option>
		  <option value="Archive/Delete" <?php if($row['status'] == "Archive/Delete")  { echo " selected"; } ?> >Archive/Delete</option>
		</select>
	</div>
	<?php }
	echo '</td>';

	echo '<td data-title="Review Information" data-project="'.$row['projectid'].'">';
		echo '<div class="review_info"><button class="btn brand-btn" onclick="assign_review(this); return false;">Assign</button> ';
		echo '<button class="btn brand-btn" onclick="review_project('.$row['projectid'].'); return false;">Mark Reviewed</button><br />';
		echo (empty($row['review_date']) ? 'Never Reviewed' : 'Reviewed '.date('Y-m-d', strtotime($row['review_date'])).' by '.get_contact($dbc,$row['reviewer_id'])).'</div>';
		echo '<div class="assign_review" style="display:none;"><div class="form-group"><label class="col-sm-2">Date:</label>';
		echo '<div class="col-sm-10"><input type="text" name="assign_date" value="'.date('Y-m-d',strtotime('+2weeks')).'" class="form-control datepicker"></div></div>';
		echo '<div class="form-group"><label class="col-sm-2">Staff:</label>';
		echo '<div class="col-sm-10"><select name="assign_staff" class="chosen-select-deselect"><option></option>';
		foreach($staff_list as $id) {
			echo "<option value='$id'>".get_contact($dbc, $id)."</option>";
		}
		echo '</select></div></div>';
		echo '<button class="btn brand-btn" onclick="save_assign(this); return false;">Submit</button> ';
		echo '<button class="btn brand-btn" onclick="close_assign(this); return false;">Cancel</button></div>';
	echo "</td>";

	echo '<td data-title="History">';
	echo '<span class="iframe_open" id="'.$row['projectid'].'" style="cursor:pointer">View All</span></td>';
	echo "</tr>";
}

echo '</table>';

// Added Pagination //
if($search_cust == '' && $search_project == '') {
echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
}
// Pagination Finish //

if(vuaed_visible_function($dbc, 'client_projects') == 1) {
	echo '<div class="pull-right">';
		echo '<a href="add_project.php?from_url='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'" class="btn brand-btn pull-right">Add Client Project</a>';
		echo '<div class="popover-examples list-inline pull-right" style="margin:5px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add a new project."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></div>';
	echo '</div>';
	echo '<div class="clearfix"></div>';
}
?>
</form>
</div>