<?php
if(!empty($_GET['tile'])) {
    include_once('../include.php');
}
error_reporting(0);

if (isset($_POST['submit_time'])) {
	$submit_time = $_POST['submit_time'];
	$count_all = explode('_',$submit_time);
	$count = $count_all[1];

	$tasklistid = $_POST['tasklistid'][$count];
	$work_time = $_POST['work_time'][$count];

	$query_update_project = "UPDATE `tasklist` SET  work_time='$work_time' WHERE `tasklistid` = '$tasklistid'";
	$result_update_project = mysqli_query($dbc, $query_update_project);

    $redirect_url = $_POST['redirect_url'];
    echo '<script type="text/javascript"> window.location.replace("'.$redirect_url.'"); </script>';
}
?>
<script type="text/javascript">
$(document).ready(function() {
    $('select[name="task_category"]').change(function() {
        selectBoard(this);
    });
    $('select[name="task_reassign"]').change(function() {
        selectCheckliststaff(this);
    });
    $('select[name="task_status"]').change(function() {
        selectCheckliststatus(this);
    });
	$('input[type="checkbox"][name="checklist[]"]').change(function() {
     if(this.checked) {
		 var tasklistid = this.value;
         var work_time = $('#worktime_'+tasklistid).val();
		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "<?php echo WEBSITE_URL;?>/Ticket/ticket_ajax_all.php?fill=checklistdone&tasklistid="+tasklistid+'&work_time='+work_time,
			dataType: "html",   //expect html to be returned
			success: function(response){
				location.reload();
			}
		});

     }
	});
});

function selectCheckliststaff(sel) {
	var contactid = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "<?php echo WEBSITE_URL;?>/Ticket/ticket_ajax_all.php?fill=tasklistreassignstaff&tasklistid="+arr[1]+'&contactid='+contactid,
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
		}
	});
}

function selectCheckliststatus(sel) {
	var status = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "<?php echo WEBSITE_URL;?>/Ticket/ticket_ajax_all.php?fill=tasklistreassignstatus&tasklistid="+arr[1]+'&status='+status,
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
		}
	});
}
function selectBoard(sel) {
	var category = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "ticket_ajax_all.php?fill=tasklistboard&tasklistid="+arr[1]+'&category='+category,
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
		}
	});
}
</script>
</head>

<div class="col-md-12">

    <form name="form_clients" method="post" action="" class="form-inline" role="form">

        <h3>View Tasklist</h3>
        <?php
        $file_name = basename($_SERVER['REQUEST_URI'], '?'.$_SERVER['QUERY_STRING']);

        /* Pagination Counting */
        $rowsPerPage = 25;
        $pageNum = 1;

        if(isset($_GET['page'])) {
            $pageNum = $_GET['page'];
        }

        $offset = ($pageNum - 1) * $rowsPerPage;

        if(!empty($_GET['tile'])) {
            $tasklistid = $_GET['tasklistid'];
            $query_check_credentials = "SELECT * FROM tasklist WHERE tasklistid='$tasklistid' LIMIT $offset, $rowsPerPage";
            $query = "SELECT count(*) as numrows FROM tasklist WHERE tasklistid='$tasklistid'";
        } else {
            $contactid = $_SESSION['contactid'];
            if($file_name == 'ticket_daysheet.php') {
                $query_check_credentials = "SELECT * FROM tasklist WHERE contactid='$contactid' AND status!='Archived' ORDER BY tasklistid DESC LIMIT $offset, $rowsPerPage";
                $query = "SELECT count(*) as numrows FROM tasklist WHERE contactid='$contactid' AND status!='Archived' ORDER BY tasklistid DESC";
                echo '<input type="hidden" name="redirect_url" value="ticket_daysheet.php">';
            } else if($file_name == 'workorder_daysheet.php') {
                $query_check_credentials = "SELECT * FROM tasklist WHERE contactid='$contactid' AND status!='Archived' AND workorderid IS NOT NULL ORDER BY tasklistid DESC LIMIT $offset, $rowsPerPage";
                $query = "SELECT count(*) as numrows FROM tasklist WHERE contactid='$contactid' AND status!='Archived' AND workorderid IS NOT NULL ORDER BY tasklistid DESC";
                echo '<input type="hidden" name="redirect_url" value="workorder_daysheet.php">';
            } else {
                $query_check_credentials = "SELECT * FROM tasklist WHERE contactid='$contactid' AND status!='Archived' ORDER BY tasklistid DESC LIMIT $offset, $rowsPerPage";
                $query = "SELECT count(*) as numrows FROM tasklist WHERE contactid='$contactid' AND status!='Archived' ORDER BY tasklistid DESC";
            }
        }

        $result = mysqli_query($dbc, $query_check_credentials);

        $num_rows = mysqli_num_rows($result);
        if($num_rows > 0) {
            // Added Pagination //
            echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
            // Pagination Finish //

            echo "<div id='no-more-tables'><table class='table table-bordered'>";
            echo "<tr class='hidden-xs hidden-sm'>
            <th>Contact</th>
            <th>Heading</th>
            <th>Task</th>
            <th>Created Date</th>
            <th>Board</th>
            <th>Reassign</th>
            <th>Work Time</th>
            <th>Status</th>
            ";
            echo "</tr>";
        } else {
            echo "<h2>No Record Found.</h2>";
        }
        $submit_inc = 0;
        while($row = mysqli_fetch_array( $result ))
        {
            $style = '';
            if($row['status'] == 'Priority') {
                $style = 'style="color: red;"';
            }
            $contactid = $row['contactid'];
            $tasklistid = $row['tasklistid'];
            echo "<tr $style>";
            $checked = '';
            if($row['status'] == 'Done') {
                $checked = 'checked disabled';
            }

            $clientid = $row['clientid'];
            $businessid = $row['businessid'];
            echo '<td data-title="Contact">' . get_contact($dbc, $businessid, 'name').'<br>'.get_contact($dbc, $clientid, 'first_name').' '.get_contact($dbc, $clientid, 'last_name') . '</td>';

            echo '<td data-title="Heading">' . $row['heading'] . '</td>';
            echo '<td data-title="Task">' . strip_tags(html_entity_decode($row['task'])) . '</td>';
            echo '<td data-title="Created Date">' . $row['created_date'] . '</td>';
            ?>
            <td data-title="Board">
                <select data-placeholder="Choose a Board..." name="task_category" id="board_<?php echo $tasklistid; ?>" class="chosen-select-deselect1 form-control" width="380">
                  <option value=""></option>
                  <?php
                    $tabs_cat = get_config($dbc, 'task_tab');
                    $each_tab_cat = explode(',', $tabs_cat);
                    foreach ($each_tab_cat as $cat_tab_cat) {
                        ?>
                        <option <?php if ($row['category'] == $cat_tab_cat) { echo  'selected="selected"'; } ?> value='<?php echo $cat_tab_cat; ?>' ><?php echo $cat_tab_cat; ?></option>
                        <?php }
                  ?>
                </select>
            </td>

            <td data-title="Reassign">
                <select name="task_reassign" id="assign_<?php echo $tasklistid; ?>" data-placeholder="Choose a User..." name="contactid" class="chosen-select-deselect form-control" width="380">
				  <option value=""></option>
				  <?php
					$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 AND `status`>0"),MYSQLI_ASSOC));
					foreach($query as $id) {
						$selected = '';
						$selected = $id == $contactid ? 'selected = "selected"' : '';
						echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
					}
				  ?>
                </select>
            </td>

            <?php
            echo '<td data-title="Work Time"><input name="work_time[]" id="worktime_'.$tasklistid.'" value="'.$row['work_time'].'" type="text" class="form-control">';

            $submit_value = 'submit_'.$submit_inc;
            echo '<button type="submit" name="submit_time" value="'.$submit_value.'" class="btn btn-xs brand-btn">Submit</button>';

            $status = $row['status'];
            ?>
            <input type="hidden" name="tasklistid[]" id="tasklistid" value="<?php echo $tasklistid; ?>">

            <td data-title="Status">

                <select data-placeholder="Choose a Vendor..." name="task_status" id="status_<?php echo $tasklistid; ?>" name="status" class="chosen-select-deselect1 form-control" width="380">
                  <option value=""></option>
                  <?php
                    $tabs = get_config($dbc, 'ticket_status');
                    $each_tab = explode(',', $tabs);
                    foreach ($each_tab as $cat_tab) {
                        if ($status == $cat_tab) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value='". $cat_tab."'>".$cat_tab.'</option>';
                    }
                  ?>
                </select>
            </td>

            <?php

            echo "</tr>";
            $submit_inc++;
        }

        echo '</table></div>';
        // Added Pagination //
        echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
        // Pagination Finish //

        ?>



    </form>
</div>
