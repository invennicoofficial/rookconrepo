<?php
/*
Dashboard
*/
include_once ('../include.php');
checkAuthorised('services');

if (isset($_POST['submit'])) {
    $service_types = implode(',',array_filter($_POST['service_type']));
    set_config($dbc, 'service_types', $service_types);
    
    // Default Image
    if (!file_exists('download')) {
		mkdir('download', 0777, true);
	}
    $services_default_image = htmlspecialchars($_FILES["services_default_image"]["name"], ENT_QUOTES);
    if ( !empty($services_default_image) ) {
        $default_image = $services_default_image;
        move_uploaded_file($_FILES["services_default_image"]["tmp_name"], "download/" . $services_default_image) ;
    } else {
        $default_image = $_POST['current_default_image'];
    }
    set_config($dbc, 'services_default_image', $default_image);

    echo '<script type="text/javascript"> window.location.replace("field_config.php?tab=general"); </script>';

}
?>
<script type="text/javascript">
function add_service_type() {
    var block = $('.service_type_div').last();
    var clone = $(block).clone();

    clone.find('input').val('');

    block.after(clone);
}
function remove_service_type(img) {
    if($('.service_type_div').length <= 1) {
        add_service_type();
    }
    $(img).closest('.service_type_div').remove();
}
</script>

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
    <?php $service_types = explode(',',get_config($dbc, "service_types"));
    foreach($service_types as $service_type) { ?>
        <div class="form-group service_type_div">
            <label class="col-sm-4 control-label">Service Type:</label>
            <div class="col-sm-7">
                <input type="text" name="service_type[]" class="form-control" value="<?= $service_type ?>">
            </div>
            <div class="col-sm-1">
                <img src="../img/icons/ROOK-add-icon.png" class="inline-img pull-right" onclick="add_service_type();">
                <img src="../img/remove.png" class="inline-img pull-right" onclick="remove_service_type(this);">
            </div>
        </div>
    <?php } ?>
    <div class="form-group">
        <?php $services_default_image = get_config($dbc, 'services_default_image'); ?>
        <label class="col-sm-4 control-label">Default Service Image:</label>
        <div class="col-sm-7">
            <?php if ( !empty($services_default_image) ) { ?>
                <a href="download/<?= $services_default_image ?>" target="_blank">View</a>
                <input type="hidden" name="current_default_image" value="<?= $services_default_image ?>" />
            <?php } ?>
            <input type="file" name="services_default_image" data-filename-placement="inside" class="form-control" />
        </div>
    </div>

    <div class="form-group pull-right">
        <a href="index.php" class="btn brand-btn">Back</a>
        <button type="submit" name="submit" value="Submit" class="btn brand-btn">Submit</button>
    </div>

</form>