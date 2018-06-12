<script type="text/javascript">
$(document).ready(function() {
    $(".worktime").focus(function() {
        //alert(this.id);
    }).blur(function() {
        var worktime_id = this.id;
        var worktime_value = this.value;
        var arr = worktime_id.split('_');
        $.ajax({    //create an ajax request to load_page.php
            type: "GET",
            url: "workorder_ajax_all.php?fill=tasklistaddtime&tasklistid="+arr[1]+'&worktime='+worktime_value,
            dataType: "html",   //expect html to be returned
            success: function(response){
                location.reload();
            }
        });
    });
});
function selectCheckliststaff(sel) {
	var contactid = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "workorder_ajax_all.php?fill=tasklistreassignstaff&tasklistid="+arr[1]+'&contactid='+contactid,
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
		}
	});
}
function selectTaskliststatus(sel) {
	var status = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "workorder_ajax_all.php?fill=tasklistreassignstatus&tasklistid="+arr[1]+'&status='+status,
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
		}
	});
}
</script>
<div class="col-md-122">
    <table class='table table-bordered'>
        <tr class='hidden-xs hidden-sm'>
            <Th>Contact</th>
            <th>Task</th>
            <th>Staff</th>
            <th>Scheduled/To Do Date</th>
            <th>Add to List</th>
        </tr>

        <tr>
        <td>
            <select data-placeholder="Choose a Client..." id="checklist_clientid" name="task_clientid" class="chosen-select-deselect form-control1" width="380">
              <option value=""></option>
              <?php
                $query = mysqli_query($dbc,"SELECT name, contactid FROM contacts WHERE category='Client' ORDER BY name");
                while($row = mysqli_fetch_array($query)) {
                    echo "<option value='". $row['contactid']."'>".decryptIt($row['name']).'</option>';
                }
              ?>
            </select>
        </td>
        <td data-title="Name">
            <input type="text" name="task" id="task_other" class="form-control" width="380" />
        </td>
        <td data-title="Title">
            <select data-placeholder="Select a Staff Member..." id="checklist_userid" name="task_contactid" class="chosen-select-deselect1 form-control" width="380">
              <option value=""></option>
              <?php
                $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE deleted=0 AND category='Staff' order by first_name");
                while($row = mysqli_fetch_array($query)) {
                    ?>
                    <option value='<?php echo $row['contactid']; ?>' ><?php echo decryptIt($row['first_name']).' '.decryptIt($row['last_name']); ?></option>
                <?php }
              ?>
            </select>
        </td>
        <td>
            <input name="task_tododate" type="text" class="datepicker">
        </td>
        <td>
            <button name="tasklist" value="tasklist" class="btn brand-btn">Add</button>
        </td>
        </tr>

    </table>

    <?php
    $contactid = $_SESSION['contactid'];
    $query_check_credentials = "SELECT * FROM tasklist WHERE workorderid='$workorderid' AND status!='Archived' ORDER BY tasklistid DESC";

    $result = mysqli_query($dbc, $query_check_credentials);

    $num_rows = mysqli_num_rows($result);
    if($num_rows > 0) {
        echo "<table class='table table-bordered'>";
        echo "<tr class='hidden-xs hidden-sm'>
        <th>Contact</th>
        <th>Task</th>
        <th>Created Date</th>
        <th>Reassign</th>
        <th>Work time</th>
        <th>Status</th>
        ";
        echo "</tr>";
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
        ?>

        <td data-title="Office Phone">
            <select onchange="selectCheckliststaff(this)" id="assign_<?php echo $tasklistid; ?>" data-placeholder="Choose a User..." class="chosen-select-deselect1 form-control" width="380">
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
        echo '<td data-title="Cell Phone"><input name="work_time[]" id="worktime_'.$tasklistid.'" value="'.$row['work_time'].'" type="text" class="form-control worktime">';

        //$submit_value = 'submit_'.$submit_inc;
        //echo '<button type="submit" name="submit_time" value="'.$submit_value.'" class="btn btn-xs brand-btn">Submit</button>';

        $status = $row['status'];
        ?>
        <input type="hidden" name="tasklistid[]" id="tasklistid" value="<?php echo $tasklistid; ?>">

        <td data-title="Office Phone">
            <select onchange="selectTaskliststatus(this)" id="status_<?php echo $tasklistid; ?>" data-placeholder="Choose a Status..." class="chosen-select-deselect1 form-control" width="380">
                <option value=""></option>
                <option value="Priority" <?php if ($status == "Priority") { echo " selected"; } ?> >Priority</option>
                <option value="Scheduled/To Do" <?php if ($status == "Scheduled/To Do") { echo " selected"; } ?> >Scheduled/To Do</option>
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

</div>
