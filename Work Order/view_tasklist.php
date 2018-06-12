<?php
if (isset($_POST['submit_time'])) {
	$submit_time = $_POST['submit_time'];
	$count_all = explode('_',$submit_time);
	$count = $count_all[1];

	$tasklistid = $_POST['tasklistid'][$count];
	$work_time = $_POST['work_time'][$count];

	$query_update_project = "UPDATE `tasklist` SET  work_time='$work_time' WHERE `tasklistid` = '$tasklistid'";
	$result_update_project = mysqli_query($dbc, $query_update_project);

    echo '<script type="text/javascript"> window.location.replace("workorder_daysheet.php"); </script>';
}
?>
<script type="text/javascript">
$(document).ready(function() {

	$('input[type="checkbox"][name="checklist[]"]').change(function() {
     if(this.checked) {
		 var tasklistid = this.value;
         var work_time = $('#worktime_'+tasklistid).val();
		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "<?php echo WEBSITE_URL;?>/Work Order/workorder_ajax_all.php?fill=checklistdone&tasklistid="+tasklistid+'&work_time='+work_time,
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
		url: "<?php echo WEBSITE_URL;?>/Work Order/workorder_ajax_all.php?fill=tasklistreassignstaff&tasklistid="+arr[1]+'&contactid='+contactid,
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
		url: "<?php echo WEBSITE_URL;?>/Work Order/workorder_ajax_all.php?fill=tasklistreassignstatus&tasklistid="+arr[1]+'&status='+status,
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
                $contactid = $_SESSION['contactid'];
                $query_check_credentials = "SELECT * FROM tasklist WHERE contactid='$contactid' AND status!='Archived' ORDER BY tasklistid DESC";

                $result = mysqli_query($dbc, $query_check_credentials);

                $num_rows = mysqli_num_rows($result);
                if($num_rows > 0) {
                    echo "<table class='table table-bordered'>";
                    echo "<tr class='hidden-xs hidden-sm'>
                    <th>Contact</th>
                    <th>Task</th>
                    <th>Created Date</th>
                    <th>To Do Date</th>
                    <th>Reassign</th>
                    <th>Work time</th>
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

                    //echo '<td data-title="Business"><input class="checklist" '.$checked.' type="checkbox" name="checklist[]" value="'.$row['tasklistid'].'">';

					$clientid = $row['clientid'];
					$get_client = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT name FROM contacts WHERE contactid='$clientid'"));
            		$name = decryptIt($get_client['name']);

					echo '<td data-title="Business Name">' . $name . '</td>';
                    echo '<td data-title="Name">' . $row['task'] . '</td>';
                    echo '<td data-title="Office Phone">' . $row['created_date'] . '</td>';
                    echo '<td data-title="Office Phone">' . $row['task_tododate'] . '</td>';
                    ?>

                    <td data-title="Office Phone">
                        <select onchange="selectCheckliststaff(this)" id="assign_<?php echo $tasklistid; ?>" data-placeholder="Choose a User..." name="contactid" class="chosen-select-deselect form-control" width="380">
                          <option value=""></option>
							  <?php
								$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Staff' AND deleted=0 AND `status`=1"),MYSQLI_ASSOC));
								foreach($query as $id) {
									$selected = '';
									$selected = $id == $contactid ? 'selected = "selected"' : '';
									echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
								}
							  ?>
                        </select>
                    </td>

                    <?php
                    echo '<td data-title="Cell Phone"><input name="work_time[]" id="worktime_'.$tasklistid.'" value="'.$row['work_time'].'" type="text" class="form-control">';

				    $submit_value = 'submit_'.$submit_inc;
				    echo '<button type="submit" name="submit_time" value="'.$submit_value.'" class="btn btn-xs brand-btn">Submit</button>';

                    $status = $row['status'];
                    ?>
                    <input type="hidden" name="tasklistid[]" id="tasklistid" value="<?php echo $tasklistid; ?>">

                    <td data-title="Office Phone">
                        <select onchange="selectCheckliststatus(this)" id="status_<?php echo $tasklistid; ?>" data-placeholder="Choose a Status..." name="status" class="chosen-select-deselect form-control" width="380">
                            <option value=""></option>
                            <option value="Priority" <?php if ($status == "Priority") { echo " selected"; } ?> >Priority</option>
                            <option value="To Do" <?php if ($status == "To Do") { echo " selected"; } ?> >To Do</option>
                            <option value="Doing" <?php if ($status == "Doing") { echo " selected"; } ?> >Doing</option>
                            <option value="Internal QA" <?php if ($status == "Internal QA") { echo " selected"; } ?> >Internal QA</option>
                            <option value="Client QA" <?php if ($status == "Client QA") { echo " selected"; } ?> >Client QA</option>
                            <option value="Done" <?php if ($status == "Done") { echo " selected"; } ?> >Done</option>
                            <option value="Archived" <?php if ($status == "Archived") { echo " selected"; } ?> >Archived</option>
                        </select>
                    </td>

                    <?php

                    echo "</tr>";
                    $submit_inc++;
                }

                echo '</table>';

                ?>

            </form>
        </div>
