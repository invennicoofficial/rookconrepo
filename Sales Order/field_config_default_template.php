<?php include_once('../include.php');
checkAuthorised('sales_order');
/*
/* Sales Order Config - Fields
*/

if(isset($_POST['save_config'])) {
    $sales_order_type = $_POST['so_type'];
    $field_config = filter_var($_POST['default_template'],FILTER_SANITIZE_STRING);
    if($field_config == 'NO_TEMPLATE') {
        $field_config = '';
    }

    if(!empty($sales_order_type)) {
        set_config($dbc, 'so_'.config_safe_str($sales_order_type).'_default_template', $field_config);
    } else {
        mysqli_query($dbc, "INSERT INTO `field_config_so` (`default_template`) SELECT '$field_config' FROM (SELECT COUNT(*) rows FROM `field_config_so`) num WHERE num.rows = 0");
        mysqli_query($dbc, "UPDATE `field_config_so` SET `default_template` = '$field_config'");   
    }
}
?>

<script type="text/javascript">
$(document).on('change', 'select[name="so_type"]', function() { changeSOType(this); });
function changeSOType(sel) {
    window.location.href = "?tab=default_template&so_type="+sel.value;
}
</script>

<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
    <div class="gap-top">
        <?php $sales_order_types = get_config($dbc, 'sales_order_types');
        $so_type = $_GET['so_type'];
        if(!empty($sales_order_types)) { ?>
            <div class="form-group">
                <label class="col-sm-4"><?= SALES_ORDER_NOUN ?> Type:</label>
                <div class="col-sm-8">
                    <select name="so_type" data-placeholder="Select a Type" class="chosen-select-deselect form-control">
                        <?php foreach(explode(',', $sales_order_types) as $sales_order_type) {
                            if(empty($so_type)) {
                                $so_type = $sales_order_type;
                            } ?>
                            <option value="<?= $sales_order_type ?>" <?= $sales_order_type == $so_type ? 'selected' : '' ?>><?= $sales_order_type ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        <?php }
        $field_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `field_config_so`"));
        $default_template = $field_config['default_template'];
        if(!empty($so_type)) {
            $default_template = get_config($dbc, 'so_'.config_safe_str($so_type).'_default_template');
        } ?>
        <div class="form-group">
            <label class="col-sm-4">Default Template:<br><em>This will be the Template that will automatically be loaded into new <?= SALES_ORDER_TILE ?>.</em></label>
            <div class="col-sm-8">
                <select name="default_template" data-placeholder="Select a Template..." class="chosen-select-deselect form-control">
                    <option></option>
                    <option value="NO_TEMPLATE">No Default Template</option>
                    <?php $templates = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `sales_order_template` WHERE `deleted` = 0 AND `sales_order_type` = '$so_type'"),MYSQLI_ASSOC);
                    foreach($templates as $template) {
                        echo '<option value="'.$template['id'].'" '.($default_template == $template['id'] ? 'selected' : '').'>'.$template['template_name'].'</option>';
                    } ?>
                </select>
            </div>
        </div>
    </div>
    <div class="pull-right gap-top gap-right gap-bottom">
        <a href="index.php" class="btn brand-btn">Cancel</a>
        <button type="submit" id="save_config" name="save_config" value="Submit" class="btn brand-btn">Submit</button>
    </div>
</form>