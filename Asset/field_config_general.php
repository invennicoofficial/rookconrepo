<?php
if (isset($_POST['submit'])) {
    $asset_minbin_email = filter_var($_POST['asset_minbin_email'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='asset_minbin_email'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$asset_minbin_email' WHERE name='asset_minbin_email'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('asset_minbin_email', '$asset_minbin_email')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    $asset_order_list = filter_var($_POST['asset_order_list'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='asset_order_list'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$asset_order_list' WHERE name='asset_order_list'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('asset_order_list', '$asset_order_list')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
}
?>

<div class="gap-top">
    <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Send Email for Min Bin</label>
    <div class="col-sm-8">
      <input name="asset_minbin_email" value="<?php echo get_config($dbc, 'asset_minbin_email'); ?>" type="text" class="form-control">
    </div>
    </div>
    <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Use Asset Order List</label>
    <div class="col-sm-8">
        <?php $asset_order_list = get_config($dbc, 'asset_order_list'); ?>
      <label><input name="asset_order_list" value="1" <?= $asset_order_list > 0 ? 'checked' : '' ?> type="radio" class="form-control"> Yes</label>
      <label><input name="asset_order_list" value="0" <?= $asset_order_list > 0 ? '' : 'checked' ?> type="radio" class="form-control"> No</label>
    </div>
    </div>
</div>