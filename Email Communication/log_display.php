<link rel="stylesheet" href="../css/style_popup.css">
<script type="text/javascript">
$(document).ready(function() {
	$('#email_popup').click(function(e) {
		if(!$(e.target).hasClass('popup-inner')) {
			closeEmailPopup();
		}
	});
});
$(document).on('change', 'select[name="followup_by[]"]', function() { changeFollowup(this); });
$(document).on('change', 'select[name="status[]"]', function() { changePOSStatus(this); });
function changePOSStatus(sel) {
	var status = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "<?php echo WEBSITE_URL; ?>/Email Communication/communication_ajax_all.php?fill=update_comm_status&email_communicationid="+arr[1]+'&status='+status,
		dataType: "html",   //expect html to be returned
		success: function(response){
			if(status == 'Archived') {
				$(sel).closest('tr').hide();
			}
		}
	});
}
function changeFollowup(sel) {
	var by = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "../Email Communication/communication_ajax_all.php?fill=update_comm_followup&email_communicationid="+arr[1]+'&by='+by,
		dataType: "html",   //expect html to be returned
		success: function(response){
		}
	});
}
function changeFollowupDate(sel) {
	var date = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "../Email Communication/communication_ajax_all.php?fill=update_comm_followup_date&email_communicationid="+arr[1]+'&date='+date,
		dataType: "html",   //expect html to be returned
		success: function(response){
		}
	});
}
function displayEmailPopup(a) {
	$('#email_popup').html('Loading...');
	var email_communicationid = $(a).data('id');
	$.ajax({
		type: "GET",
		url: '../Email Communication/email_popup.php?email_communicationid='+email_communicationid,
		dataType: "html",
		success: function(response) {
			$('#email_popup').html(response);
			jQuery("#log_contact").css("display","none");
			jQuery("#select2-log_contact-container").css("display","none");
	        jQuery(".select2-container").css("display","none");
	        jQuery(".chosen-container").css("display","none");
	        $('#email_popup').fadeIn(350);
	        $('#email_popup .popup-inner-resizable').height($('.popup-inner').innerHeight() - 80);
		}
	});
}
function closeEmailPopup() {
    $('#email_popup').fadeOut(350);
    jQuery("#select2-log_contact-container").css("display","block");
    jQuery(".chosen-container").css("display","block");
    jQuery(".select2-container").css("display","block");
}
</script>
<div id="no-more-tables">
<?php
$project_clause = '';
if(!empty($_GET['projectid'])) {
	$project_clause = " WHERE (`projectid`='".$_GET['projectid']."' OR CONCAT('C',`client_projectid`)='".$_GET['projectid']."')";
} else if(!empty($_GET['edit'])) {
	$project_clause = " WHERE (`projectid`='".$_GET['edit']."' OR CONCAT('C',`client_projectid`)='".$_GET['edit']."')";
}
/* Pagination Counting */
$rowsPerPage = 25;
$pageNum = 1;

if(isset($_GET['page'])) {
	$pageNum = $_GET['page'];
}

$offset = ($pageNum - 1) * $rowsPerPage;

$query_check_credentials = "SELECT * FROM email_communication $project_clause ORDER BY email_communicationid DESC LIMIT $offset, $rowsPerPage";
$query_num_rows = "SELECT COUNT(*) numrows FROM email_communication $project_clause ORDER BY email_communicationid DESC";
$result = mysqli_query($dbc, $query_check_credentials);
$num_rows = mysqli_num_rows($result);

$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT log_communication_dashboard FROM field_config"));
$value_config = ','.$get_field_config['log_communication_dashboard'].',';

if($num_rows > 0) {
	// Added Pagination //
	echo display_pagination($dbc, $query_num_rows, $pageNum, $rowsPerPage);
	// Pagination Finish //

	echo "<table class='table table-bordered'>";
	echo "<tr class='hidden-xs hidden-sm'>";
	if (strpos($value_config, ','."Email#".',') !== FALSE) {
		echo '<th>Email#</th>';
	}
	if (strpos($value_config, ','."Business".',') !== FALSE) {
		echo '<th>Business</th>';
	}
	if (strpos($value_config, ','."Contact".',') !== FALSE) {
		echo '<th>Contact</th>';
	}
	if (strpos($value_config, ','."Project".',') !== FALSE) {
		echo '<th>Project</th>';
	}
	if (strpos($value_config, ','."Subject".',') !== FALSE) {
		echo '<th>Subject</th>';
	}
	if (strpos($value_config, ','."Body".',') !== FALSE) {
		echo '<th>Body</th>';
	}
	if (strpos($value_config, ','."Attachment".',') !== FALSE) {
		echo '<th>Attachment</th>';
	}
	if (strpos($value_config, ','."Email To".',') !== FALSE) {
		echo '<th>Email To</th>';
	}
	if (strpos($value_config, ','."Email Date".',') !== FALSE) {
		echo '<th>Email Date</th>';
	}
	if (strpos($value_config, ','."Email By".',') !== FALSE) {
		echo '<th>Email By</th>';
	}
	if (strpos($value_config, ','."Follow Up By".',') !== FALSE) {
		echo '<th>Follow Up By</th>';
	}
	if (strpos($value_config, ','."Follow Up Date".',') !== FALSE) {
		echo '<th>Follow Up Date</th>';
	}
	if (strpos($value_config, ','."Status".',') !== FALSE) {
		echo '<th>Status</th>';
	}
	echo "</tr>";

	while($row = mysqli_fetch_array($result))
	{
		echo '<tr>';
		if (strpos($value_config, ','."Email#".',') !== FALSE) {
			echo '<td data-title="Email#">' . $row['email_communicationid']. '</td>';
		}
		if (strpos($value_config, ','."Business".',') !== FALSE) {
			echo '<td data-title="Business">' . get_contact($dbc, $row['businessid'], 'name'). '</td>';
		}
		if (strpos($value_config, ','."Contact".',') !== FALSE) {
			echo '<td data-title="Business">'.get_staff($dbc, $row['contactid']) . '</td>';
		}
		if (strpos($value_config, ','."Project".',') !== FALSE) {
			echo '<td data-title="Project">';
			$project_tabs = get_config($dbc, 'project_tabs');
			$project_tabs = explode(',',$project_tabs);
			foreach($project_tabs as $item) {
				if(preg_replace('/[^a-z_]/','',str_replace(' ','_',strtolower($item))) == get_project($dbc, $row['projectid'], 'projecttype')) {
					echo $item.': ';
				}
			}
			echo get_project($dbc, $row['projectid'], 'project_name').'</td>';
		}
		if (strpos($value_config, ','."Subject".',') !== FALSE) {
			echo '<td data-title="Subject"><a onclick="displayEmailPopup(this); return false;" data-id="'.$row['email_communicationid'].'" href="#">' . $row['subject']. '</a></td>';
		}
		if (strpos($value_config, ','."Body".',') !== FALSE) {
			echo '<td data-title="Body"><a onclick="displayEmailPopup(this); return false;" data-id="'.$row['email_communicationid'].'" href="#">' . html_entity_decode($row['email_body']). '</a></td>';
		}
		if (strpos($value_config, ','."Attachment".',') !== FALSE) {
			echo '<td data-title="Attachment">';
			$email_communicationid = $row['email_communicationid'];
			$result1 = mysqli_query($dbc, "SELECT * FROM email_communicationid_upload WHERE email_communicationid='$email_communicationid' ORDER BY emailcommuploadid DESC");
			while($row2 = mysqli_fetch_array($result1)) {
				echo '<a href="../Email Communication/download/'.$row2['document'].'" target="_blank">'.$row2['document'].'</a></br>';
			}
			echo '</td>';
		}
		if (strpos($value_config, ','."Email To".',') !== FALSE) {
			$all_email = $row['to_staff'].','.$row['cc_staff'].','.$row['to_contact'].','.$row['cc_contact'].','.$row['new_emailid'];
			$all_email = str_replace(',,', ',', $all_email);
			$all_email = str_replace(',,', ',', $all_email);
			$all_email = rtrim($all_email,',');
			$all_email = ltrim($all_email,',');
			$all_email = str_replace(',', '<br>', $all_email);
			echo '<td data-title="Email To">' . $all_email. '</td>';
		}
		if (strpos($value_config, ','."Email Date".',') !== FALSE) {
			echo '<td data-title="Email Date">' . $row['today_date']. '</td>';
		}
		if (strpos($value_config, ','."Email By".',') !== FALSE) {
			echo '<td data-title="Email By">' . get_staff($dbc, $row['created_by']). '</td>';
		}
		if (strpos($value_config, ','."Follow Up By".',') !== FALSE) {
			echo '<td data-title="Follow Up By"><select name="followup_by[]" id="followupby_'.$row['email_communicationid'].'" data-placeholder="Select a Staff..." class="chosen-select-deselect form-control">
					<option value=""></option>';
					$staff_query = sort_contacts_query(mysqli_query($dbc,"SELECT contactid, first_name, last_name, category, email_address FROM contacts WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `deleted` = 0 AND `status` = 1"));
					foreach($staff_query as $contact) {
							echo '<option '.($row['follow_up_by'] == $contact['contactid'] ? " selected" : '').' value="'.$contact['contactid'].'">'.$contact['first_name'].' '.$contact['last_name'].'</option>';
					}
			echo '</select></td>';
		}
		if (strpos($value_config, ','."Follow Up Date".',') !== FALSE) {
			echo '<td data-title="Follow Up Date"><input type="text" name="followupdate[]" onchange="changeFollowupDate(this)" id="followupdate_'.$row['email_communicationid'].'" value="'.$row['follow_up_date'].'" class="datepicker form-control"></td>';
		}
		if (strpos($value_config, ','."Status".',') !== FALSE) {
			echo '<td data-title="Current Status">';
			?>
			<select name="status[]" id="status_<?php echo $row['email_communicationid']; ?>" class="chosen-select-deselect1 form-control" width="380">
				 <option value=""></option>
				 <option <?php if ($row['status'] == "Pending") { echo " selected"; } ?> value="Pending">Pending</option>
				 <option <?php if ($row['status'] == "Follow up") { echo " selected"; } ?> value="Follow up">Follow up</option>
				 <option <?php if ($row['status'] == "Resolved") { echo " selected"; } ?> value="Resolved">Resolved</option>
				 <!--<option <?php if ($row['status'] == "Archived") { echo " selected"; } ?> value="Archived">Archived</option>-->
			</select>

			<?php echo '</td>';
		}
		echo "</tr>";
	}
	echo '</table>';

	// Added Pagination //
	echo display_pagination($dbc, $query_num_rows, $pageNum, $rowsPerPage);
	// Pagination Finish //
} else {
	echo "<h2>No Record Found.</h2>";
} ?>
	<div id="email_popup" class="popup" style="color:black; z-index:99999;">
	</div>
</div>
