<?php
if (isset($_POST['submit'])) {
    $material_navigation_position = filter_var($_POST['material_navigation_position'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='material_navigation_position'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$material_navigation_position' WHERE name='material_navigation_position'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('material_navigation_position', '$material_navigation_position')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
}
?>

<div class="gap-top">
    <div class="form-group">
        <label for="fax_number" class="col-sm-4 control-label"><span class="popover-examples list-inline"><a class="" style="margin:7px 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Choose navigation positioning."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span> Navigation Position:</label>
        <div class="col-sm-8">
            <?php $material_navigation_position = get_config($dbc, 'material_navigation_position'); ?>
            <label><input type="radio" name="material_navigation_position" value="" <?= empty($material_navigation_position) ? 'checked' : '' ?>>Side</label>&nbsp;&nbsp;
            <label><input type="radio" name="material_navigation_position" value="top" <?= $material_navigation_position == 'top' ? 'checked' : '' ?>>Top</label>
        </div>
    </div>

    <div class="clearfix"></div>
</div>