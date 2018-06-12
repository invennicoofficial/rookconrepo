<?php
if (isset($_POST['submit'])) {
    $material_minbin_email = filter_var($_POST['material_minbin_email'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='material_minbin_email'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$material_minbin_email' WHERE name='material_minbin_email'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('material_minbin_email', '$material_minbin_email')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
}
?>

<div class="gap-top">
    <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label"><h4></h4></label>
        <div class="col-sm-12">
            Send Email for Min Bin:<br />
            <br />
            <input name="material_minbin_email" value="<?php echo get_config($dbc, 'material_minbin_email'); ?>" type="text" class="form-control">
        </div>
    </div>
</div>