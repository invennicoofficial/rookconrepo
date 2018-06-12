<?php
if (isset($_POST['submit'])) {
    $asset_tabs = filter_var($_POST['asset_tabs'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='asset_tabs'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$asset_tabs' WHERE name='asset_tabs'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('asset_tabs', '$asset_tabs')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    $asset_navigation_position = filter_var($_POST['asset_navigation_position'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='asset_navigation_position'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$asset_navigation_position' WHERE name='asset_navigation_position'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('asset_navigation_position', '$asset_navigation_position')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
}
?>

<div class="gap-top">
    <div class="form-group">
        <label for="fax_number" class="col-sm-4 control-label">Add Tabs separated by a comma:</label>
        <div class="col-sm-8">
          <input name="asset_tabs" type="text" value="<?php echo get_config($dbc, 'asset_tabs'); ?>" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label for="fax_number" class="col-sm-4 control-label"><span class="popover-examples list-inline"><a class="" style="margin:7px 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Choose navigation positioning."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span> Navigation Position:</label>
        <div class="col-sm-8">
            <?php $asset_navigation_position = get_config($dbc, 'asset_navigation_position'); ?>
            <label><input type="radio" name="asset_navigation_position" value="" <?= empty($asset_navigation_position) ? 'checked' : '' ?>>Side</label>&nbsp;&nbsp;
            <label><input type="radio" name="asset_navigation_position" value="top" <?= $asset_navigation_position == 'top' ? 'checked' : '' ?>>Top</label>
        </div>
    </div>
    <div class="clearfix"></div>
</div>