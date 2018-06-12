<?php
if (isset($_POST['submit'])) {
    $workorderid = $_POST['workorderid'];
	$task = filter_var($_POST['task'],FILTER_SANITIZE_STRING);
	$clientid = $_POST['clientid'];
	$contactid = $_POST['contactid'];
	$add_worklog = $_POST['add_worklog'];
	$created_date = date('Y-m-d');
    $worklogid = 1;

	/*if($add_worklog == 1) {
		$query_insert_ca = "INSERT INTO `worklog` (`client`, `sub_heading`, `description`) VALUES ('$client', 'Communication', '$task')";
		$result_insert_ca = mysqli_query($dbc, $query_insert_ca);
        $worklogid = mysqli_insert_id($dbc);
	}
    */
	$query_insert_ca = "INSERT INTO `tasklist` (`clientid`, `task`, `contactid`, `worklogid`, `created_date`) VALUES ('$clientid', '$task', '$contactid', '$worklogid', '$created_date')";
	$result_insert_ca = mysqli_query($dbc, $query_insert_ca);
    echo '<script type="text/javascript"> window.location.replace("add_workorder.php"); </script>';

}

?>
<div class="col-md-12">
    <h3>Add Tasklist</h3>
    <div class="form-group">
        <table class='table table-bordered'>
            <tr class='hidden-xs hidden-sm'>
                <th>Task</th>
                <Th>Contact</th>
                <th>Staff</th>
                <th>Add To Worklog</th>
                <th>Add to List</th>
            </tr>

            <tr>
            <td data-title="Name">
                <input type="text" name="task" id="task_other" class="form-control" width="380" />
            </td>
            <td>
                <select data-placeholder="Select a Client..." id="checklist_clientid" name="clientid" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <?php
                    $query = mysqli_query($dbc,"SELECT name, contactid FROM contacts WHERE category='Client' ORDER BY name");
                    while($row = mysqli_fetch_array($query)) {
                        echo "<option value='". $row['contactid']."'>".decryptIt($row['name']).'</option>';
                    }
                  ?>
                </select>
            </td>
            <td data-title="Title">
                <select data-placeholder="Select a Staff Member..." id="checklist_userid" name="contactid" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
				  <?php
					$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Staff' AND deleted=0 AND `status`=1"),MYSQLI_ASSOC));
					foreach($query as $id) {
						$selected = '';
						//$selected = $id == $contactid ? 'selected = "selected"' : '';
						echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
					}
				  ?>
                </select>
            </td>
            <td>
                <input type="checkbox" id="add_worklog" name="add_worklog">
            </td>
            <td>
                <button name="submit" value="submit" class="btn brand-btn">Add</button>
            </td>
            </tr>

        </table>

    </div>
</div>
