<?php
if (isset($_POST['add_workorders'])) {
    $enabled_fields = implode(',', $_POST['enabled_fields']);
    mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`) SELECT 'appt_wo_fields' FROM (SELECT COUNT(*) rows FROM `general_configuration` WHERE `name`='appt_wo_fields') num WHERE num.rows=0");
    mysqli_query($dbc, "UPDATE `general_configuration` SET `value`='".$enabled_fields."' WHERE `name`='appt_wo_fields'");

    $workorder_status = filter_var($_POST['workorder_status'], FILTER_SANITIZE_STRING);
    mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`) SELECT 'workorder_status' FROM (SELECT COUNT(*) rows FROM `general_configuration` WHERE `name`='workorder_status') num WHERE num.rows=0");
    mysqli_query($dbc, "UPDATE `general_configuration` SET `value`='".$workorder_status."' WHERE `name`='workorder_status'");
}
?>
<script type="text/javascript">
function showStatusTypes(checkbox) {
    if ($(checkbox).is(":checked")) {
        $("#status_types").show();
    } else {
        $("#status_types").hide();
    }
}
</script>
<?php
$enabled_fields = '';

$get_field_config = get_config($dbc, 'appt_wo_fields');
if (!empty($get_field_config)) {
    $enabled_fields = ','.$get_field_config.',';
}

$workorder_status = get_config($dbc, 'workorder_status');
?>
<h3>Work Orders</h3>
<div class="panel-group" id="accordion2">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field">Field Settings</a>
            </h4>
        </div>
        <div id="collapse_field" class="panel-collapse collapse">
            <div class="panel-body">
                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($enabled_fields, ',project,') !== FALSE) { echo " checked"; } ?> value="project" style="height: 20px; width: 20px;" name="enabled_fields[]">&nbsp;&nbsp;Project
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($enabled_fields, ',region,') !== FALSE) { echo " checked"; } ?> value="region" style="height: 20px; width: 20px;" name="enabled_fields[]">&nbsp;&nbsp;Region
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($enabled_fields, ',customer,') !== FALSE) { echo " checked"; } ?> value="customer" style="height: 20px; width: 20px;" name="enabled_fields[]">&nbsp;&nbsp;Customer
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($enabled_fields, ',heading,') !== FALSE) { echo " checked"; } ?> value="heading" style="height: 20px; width: 20px;" name="enabled_fields[]">&nbsp;&nbsp;Work Order Number
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($enabled_fields, ',location,') !== FALSE) { echo " checked"; } ?> value="location" style="height: 20px; width: 20px;" name="enabled_fields[]">&nbsp;&nbsp;Location
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($enabled_fields, ',pickup_location,') !== FALSE) { echo " checked"; } ?> value="pickup_location" style="height: 20px; width: 20px;" name="enabled_fields[]">&nbsp;&nbsp;Pick Up Location
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($enabled_fields, ',dropoff_location,') !== FALSE) { echo " checked"; } ?> value="dropoff_location" style="height: 20px; width: 20px;" name="enabled_fields[]">&nbsp;&nbsp;Drop Off Location
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($enabled_fields, ',workorder_type,') !== FALSE) { echo " checked"; } ?> value="workorder_type" style="height: 20px; width: 20px;" name="enabled_fields[]">&nbsp;&nbsp;Delivery Type
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($enabled_fields, ',to_do_date,') !== FALSE) { echo " checked"; } ?> value="to_do_date" style="height: 20px; width: 20px;" name="enabled_fields[]">&nbsp;&nbsp;Delivery Date
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($enabled_fields, ',to_do_time,') !== FALSE) { echo " checked"; } ?> value="to_do_time" style="height: 20px; width: 20px;" name="enabled_fields[]">&nbsp;&nbsp;Delivery Time
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($enabled_fields, ',distance,') !== FALSE) { echo " checked"; } ?> value="distance" style="height: 20px; width: 20px;" name="enabled_fields[]">&nbsp;&nbsp;Distance
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($enabled_fields, ',num_items,') !== FALSE) { echo " checked"; } ?> value="num_items" style="height: 20px; width: 20px;" name="enabled_fields[]">&nbsp;&nbsp;Number of Items
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($enabled_fields, ',item_description,') !== FALSE) { echo " checked"; } ?> value="item_description" style="height: 20px; width: 20px;" name="enabled_fields[]">&nbsp;&nbsp;Item Description
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($enabled_fields, ',exchange_product,') !== FALSE) { echo " checked"; } ?> value="exchange_product" style="height: 20px; width: 20px;" name="enabled_fields[]">&nbsp;&nbsp;Exchange Product
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($enabled_fields, ',return_location,') !== FALSE) { echo " checked"; } ?> value="return_location" style="height: 20px; width: 20px;" name="enabled_fields[]">&nbsp;&nbsp;Return Location
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($enabled_fields, ',oversized_item,') !== FALSE) { echo " checked"; } ?> value="oversized_item" style="height: 20px; width: 20px;" name="enabled_fields[]">&nbsp;&nbsp;Oversized Item
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($enabled_fields, ',measurement,') !== FALSE) { echo " checked"; } ?> value="measurement" style="height: 20px; width: 20px;" name="enabled_fields[]">&nbsp;&nbsp;Measurements
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($enabled_fields, ',description,') !== FALSE) { echo " checked"; } ?> value="description" style="height: 20px; width: 20px;" name="enabled_fields[]">&nbsp;&nbsp;Description
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($enabled_fields, ',assembly_required,') !== FALSE) { echo " checked"; } ?> value="assembly_required" style="height: 20px; width: 20px;" name="enabled_fields[]">&nbsp;&nbsp;Assembly Required
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($enabled_fields, ',estimated_time,') !== FALSE) { echo " checked"; } ?> value="estimated_time" style="height: 20px; width: 20px;" name="enabled_fields[]">&nbsp;&nbsp;Estimated Time
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($enabled_fields, ',documents,') !== FALSE) { echo " checked"; } ?> value="documents" style="height: 20px; width: 20px;" name="enabled_fields[]">&nbsp;&nbsp;Documents
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($enabled_fields, ',deliverables,') !== FALSE) { echo " checked"; } ?> value="deliverables" style="height: 20px; width: 20px;" name="enabled_fields[]" onclick="showStatusTypes(this);">&nbsp;&nbsp;Deliverables
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($enabled_fields, ',notes,') !== FALSE) { echo " checked"; } ?> value="notes" style="height: 20px; width: 20px;" name="enabled_fields[]">&nbsp;&nbsp;Notes
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($enabled_fields, ',assign_staffid,') !== FALSE) { echo " checked"; } ?> value="assign_staffid" style="height: 20px; width: 20px;" name="enabled_fields[]">&nbsp;&nbsp;Staff Assignment
                        </td>
                        <?php if (!empty(get_config($dbc, 'appt_teams'))) { ?>
                            <td>
                                <input type="checkbox" <?php if (strpos($enabled_fields, ',assign_teamid,') !== FALSE) { echo " checked"; } ?> value="assign_teamid" style="height: 20px; width: 20px;" name="enabled_fields[]">&nbsp;&nbsp;Team Assignment
                            </td>
                        <?php } ?>
                        <?php
                        $equip_type = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_equip_assign`"))['equipment_category'];
                        if (!empty(get_config($dbc, 'appt_equip_assign')) && !empty($equip_type)) { ?>
                            <td>
                                <input type="checkbox" <?php if (strpos($enabled_fields, ',assign_equip_assignid,') !== FALSE) { echo " checked"; } ?> value="assign_equip_assignid" style="height: 20px; width: 20px;" name="enabled_fields[]">&nbsp;&nbsp;<?= $equip_type ?> Assignment
                            </td>
                        <?php } ?>
                    </tr>
                </table>
                <div id="status_types" class="form-group" <?= (strpos($enabled_fields, ',deliverables,') !== FALSE ? '' : 'style="display: none;"') ?>>
                    <label class="col-sm-4 control-label">Work Order Status Types:<br />(Add Status Types separated by comma.)</label>
                    <div class="col-sm-8">
                        <input name="workorder_status" type="text" value="<?= $workorder_status ?>" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group clearfix">
	<div class="col-sm-6">
		<a href="calendars.php" class="btn brand-btn btn-lg">Back</a>
	</div>
	<div class="col-sm-6">
		<button	type="submit" name="add_workorders" value="add_workorders" class="btn brand-btn btn-lg pull-right">Submit</button>
	</div>
</div>