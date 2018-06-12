<?php
if (isset($_POST['submit'])) {
    $material_order_list = filter_var($_POST['material_order_list'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='material_order_list'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$material_order_list' WHERE name='material_order_list'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('material_order_list', '$material_order_list')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
}
?>

<div class="gap-top">
    <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Use Material Order Lists:</label>
        <div class="col-sm-8">
            <?php $material_order_list = get_config($dbc, 'material_order_list'); ?>
            <label><input name="material_order_list" value="1" <?= $material_order_list > 0 ? 'checked' : '' ?> type="radio"> Yes</label>
            <label><input name="material_order_list" value="0" <?= $material_order_list > 0 ? '' : 'checked' ?> type="radio"> No</label>
        </div>
    </div>
</div>