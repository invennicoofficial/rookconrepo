<?php
include ('../include.php');
checkAuthorised('charts');
include 'config.php';

if (isset($_POST['submit'])) {
    //Charts Settings
    set_config($dbc, 'charts_time_format', $_POST['charts_time_format']);

    //Chart Tile Charts to Display
    mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`) SELECT 'charts_tile_charts' FROM (SELECT COUNT(*) rows FROM `general_configuration` WHERE `name`='charts_tile_charts') num WHERE num.rows=0");
    mysqli_query($dbc, "UPDATE `general_configuration` SET `value`='".implode(',',$_POST['charts_tile_charts'])."' WHERE `name`='charts_tile_charts'");

    //Custom Charts
    mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`) SELECT 'custom_monthly_charts' FROM (SELECT COUNT(*) rows FROM `general_configuration` WHERE `name`='custom_monthly_charts') num WHERE num.rows=0");
    mysqli_query($dbc, "UPDATE `general_configuration` SET `value`='".$_POST['custom_monthly_charts']."' WHERE `name`='custom_monthly_charts'");

    //Field Settings
    foreach($config['settings'] as $settings => $value) {
        if(isset($value['config_field'])) {
            $post_value = implode(',',$_POST[$value['config_field']]);
        }

        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(fieldconfigid) AS fieldconfigid FROM field_config"));
        if($get_field_config['fieldconfigid'] > 0) {
            $query_update = "UPDATE `field_config` SET ".$value['config_field']." = '".$post_value."' WHERE `fieldconfigid` = 1";
            $result_update = mysqli_query($dbc, $query_update);
        } else {
            $query_insert_config = "INSERT INTO `field_config` (`".$value['config_field']."`) VALUES ('".$post_value."')";
            $result_insert_config = mysqli_query($dbc, $query_insert_config);
        }

        mysqli_query($dbc, "DELETE FROM `field_config_charts_pdf_times` WHERE `chart` = '".$value['config_field']."'");
        foreach($_POST[$value['config_field'].'_time_label'] as $i => $time_label) {
            $start_time = $_POST[$value['config_field'].'_time_start_time'][$i];
            $end_time = $_POST[$value['config_field'].'_time_end_time'][$i];
            if(!empty($time_label.$start_time.$end_time)) {
                mysqli_query($dbc, "INSERT INTO `field_config_charts_pdf_times` (`chart`, `label`, `start_time`, `end_time`) VALUES ('".$value['config_field']."', '$time_label', '$start_time', '$end_time')");
            }
        }
    }

    //Daily Water Temp Locations
    mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`) SELECT 'daily_water_temp_bus_locations' FROM (SELECT COUNT(*) rows FROM `general_configuration` WHERE `name`='daily_water_temp_bus_locations') num WHERE num.rows=0");
    mysqli_query($dbc, "UPDATE `general_configuration` SET `value`='".$_POST['daily_water_temp_bus_locations']."' WHERE `name`='daily_water_temp_bus_locations'");

    //Daily Fridge Temp Fridges
    mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`) SELECT 'daily_fridge_temp_fridges' FROM (SELECT COUNT(*) rows FROM `general_configuration` WHERE `name`='daily_fridge_temp_fridges') num WHERE num.rows=0");
    mysqli_query($dbc, "UPDATE `general_configuration` SET `value`='".$_POST['daily_fridge_temp_fridges']."' WHERE `name`='daily_fridge_temp_fridges'");

    //Daily Freezer Temp Fridges
    mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`) SELECT 'daily_freezer_temp_freezers' FROM (SELECT COUNT(*) rows FROM `general_configuration` WHERE `name`='daily_freezer_temp_freezers') num WHERE num.rows=0");
    mysqli_query($dbc, "UPDATE `general_configuration` SET `value`='".$_POST['daily_freezer_temp_freezers']."' WHERE `name`='daily_freezer_temp_freezers'");

    // echo '<script type="text/javascript"> window.location.replace("field_config.php"); </script>';
}
?>
<style>
.config_ulli li {
    list-style: none;
    float: left;
    width: 20%;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
    initSortable();
});
function initSortable() {
    $('.pdf_times_table').sortable({
        items: '.sortable_times',
        handle: '.drag-handle'
    });
}
function changePDFStyle(radio) {
    if($(radio).val() == 'pdf_days_column') {
        $(radio).closest('.panel-body').find('.pdf_times').show();
    } else {
        $(radio).closest('.panel-body').find('.pdf_times').hide();
    }
}
function addPDFTime(img) {
    destroyInputs('.pdf_times_table');

    var block = $(img).closest('.pdf_times_table');
    var row = block.find('tr.sortable_times').last();

    var clone = $(row).clone();
    clone.find('input').val('');

    row.after(clone);
    initInputs('.pdf_times_table');
    initSortable();
}
function removePDFTime(img) {
    var block = $(img).closest('.pdf_times_table');
    if($(block).find('tr.sortable_times').length <= 1) {
        addPDFTime(img);
    }
    $(img).closest('tr.sortable_times').remove();
}
</script>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<h1>Charts</h1>
<div class="pad-left gap-top double-gap-bottom"><a href="index.php" class="btn config-btn">Back to Dashboard</a></div>

<div class="tab-container">
    <div class="pull-left tab"><span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click this to configure the Fields for the Charts."><img src="<?= WEBSITE_URL ?>/img/info.png" width="20"></a></span><a href="field_config.php"><button type="button" class="btn brand-btn mobile-block active_tab">Fields</button></a></div>

    <?php $custom_monthly_charts = explode(',', get_config($dbc, 'custom_monthly_charts'));
    foreach ($custom_monthly_charts as $custom_monthly_chart) {
        if(!empty($custom_monthly_chart)) { ?>
            <div class="pull-left tab"><span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click this to configure the Headings and Fields for this Custom Chart."><img src="<?= WEBSITE_URL ?>/img/info.png" width="20"></a></span><a href="field_config_custom.php?type=<?= $custom_monthly_chart ?>"><button type="button" class="btn brand-btn mobile-block"><?= $custom_monthly_chart ?></button></a></div>
        <?php }
    } ?>

    <div class="pull-left tab"><span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click this to configure the PDF Styling for the Charts."><img src="<?= WEBSITE_URL ?>/img/info.png" width="20"></a></span><a href="field_config_pdf.php"><button type="button" class="btn brand-btn mobile-block">PDF Styling</button></a></div>
</div>

<div class="clearfix"></div>

<form id="form1" name="form1" method="post" enctype="multipart/form-data" class="form-horizontal" role="form">

<div class="panel-group" id="accordion2">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_settings" >
                    Choose Settings for Charts<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>
        <div id="collapse_settings" class="panel-collapse collapse">
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Time Format:</label>
                    <div class="col-sm-8">
                        <?php $charts_time_format = get_config($dbc, 'charts_time_format'); ?>
                        <label class="form-checkbox"><input type="radio" name="charts_time_format" value="" <?= empty($charts_time_format) ? 'checked' : '' ?>> AM/PM</label>
                        <label class="form-checkbox"><input type="radio" name="charts_time_format" value="24h" <?= $charts_time_format == '24h' ? 'checked' : '' ?>> 24 Hour</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_general" >
                    Choose Charts to Display<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>
        <div id="collapse_general" class="panel-collapse collapse">
            <div class="panel-body">
                <div class="form-group">
                    <ul class="config_ulli">
                        <?php $charts_tile_charts = ','.get_config($dbc, 'charts_tile_charts').','; 
                        foreach ($config['tabs'] as $title => $url) { ?>
                            <li><input type="checkbox" name="charts_tile_charts[]" style="height: 20px; width: 20px;" <?= strpos($charts_tile_charts, $url) !== FALSE ? 'checked' : '' ?> value="<?= $url ?>">&nbsp;&nbsp;<?= $title ?></li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="form-group">
                    <?php $custom_monthly_charts = get_config($dbc, 'custom_monthly_charts'); ?>
                    <label class="col-sm-4 control-label">Custom Monthly Charts:<br><em>(Enter all Custom Charts separated by a comma. After submitting the Settings, there will be new tabs above to configure each Chart.)</em></label>
                    <div class="col-sm-8">
                        <input type="text" name="custom_monthly_charts" value="<?= $custom_monthly_charts ?>" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
$k=0;
foreach($config['settings'] as $settings => $value) {
    if(isset($value['config_field'])) {
        $get_field_config = @mysqli_fetch_assoc(mysqli_query($dbc,"SELECT ".$value['config_field']." FROM field_config"));
        $value_config = ','.$get_field_config[$value['config_field']].',';
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field<?php echo $k; ?>" >
                        <?php echo $settings; ?><span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>
            <div id="collapse_field<?php echo $k; ?>" class="panel-collapse collapse">
                <div class="panel-body">
                    <ul class="config_ulli">
                        <?php
                        foreach($value['data'] as $tabs) {
                            foreach($tabs as $field) {
                         ?>
                        <li>
                            <input type="checkbox" <?php if (strpos($value_config, ','.$field[2].',') !== FALSE) { echo " checked"; } ?> value="<?php echo $field[2];?>" style="height: 20px; width: 20px;" name="<?php echo $value['config_field']; ?>[]">&nbsp;&nbsp;<?php echo $field[0]; ?>
                        </li>
                        <?php }
                        }
                        ?>
                    </ul>
                    <?php if($value['config_field'] == 'daily_water_temp_bus') {
                        $daily_water_temp_bus_locations = get_config($dbc, 'daily_water_temp_bus_locations');
                        if(empty($daily_water_temp_bus_locations)) {
                            $daily_water_temp_bus_locations = 'Kitchen Double Sink,Kitchen Hand Wash Sink,North Bathroom Sink,North Showerhead,South Bathroom Sink,South Showerhead';
                        } ?>
                        <div class="clearfix"></div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Locations:<br><i>(Enter locations separated by a comma.)</i></label>
                            <div class="col-sm-8">
                                <input type="text" name="daily_water_temp_bus_locations" value="<?= $daily_water_temp_bus_locations ?>" class="form-control">
                            </div>
                        </div>
                    <?php } ?>
                    <?php if($value['config_field'] == 'daily_fridge_temp') {
                        $daily_fridge_temp_fridges = get_config($dbc, 'daily_fridge_temp_fridges');
                        if(empty($daily_fridge_temp_fridges)) {
                            $daily_fridge_temp_fridges = 'Kitchen Fridge,Storage Fridge,Client Fridge';
                        } ?>
                        <div class="clearfix"></div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Fridges:<br><i>(Enter fridges separated by a comma.)</i></label>
                            <div class="col-sm-8">
                                <input type="text" name="daily_fridge_temp_fridges" value="<?= $daily_fridge_temp_fridges ?>" class="form-control">
                            </div>
                        </div>
                    <?php } ?>
                    <?php if($value['config_field'] == 'daily_freezer_temp') {
                        $daily_freezer_temp_freezers = get_config($dbc, 'daily_freezer_temp_freezers');
                        if(empty($daily_freezer_temp_freezers)) {
                            $daily_freezer_temp_freezers = 'Kitchen Freezer,Storage Freezer,Deep Freezer';
                        } ?>
                        <div class="clearfix"></div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Freezers:<br><i>(Enter freezers separated by a comma.)</i></label>
                            <div class="col-sm-8">
                                <input type="text" name="daily_freezer_temp_freezers" value="<?= $daily_freezer_temp_freezers ?>" class="form-control">
                            </div>
                        </div>
                    <?php } ?>
                    <?php if(in_array($value['config_field'], ['bowel_movement','blood_glucose','seizure_record'])) { ?>
                        <div class="clearfix"></div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">PDF Styling:</label>
                            <div class="col-sm-8">
                                <label class="form-checkbox"><input type="radio" name="<?php echo $value['config_field']; ?>[]" value="" <?= strpos($value_config, ',pdf_days_column,') === FALSE ? 'checked' : '' ?> onchange="changePDFStyle(this);"> Normal View</label>
                                <label class="form-checkbox"><input type="radio" name="<?php echo $value['config_field']; ?>[]" value="pdf_days_column" <?= strpos($value_config, ',pdf_days_column,') !== FALSE ? 'checked' : '' ?> onchange="changePDFStyle(this);"> Days as Columns</label>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group pdf_times" <?= strpos($value_config, ',pdf_days_column,') !== FALSE ? '' : 'style="display:none;"' ?>>
                            <label class="col-sm-4 control-label">PDF Row Times:<br><em>Each Label here represents a row in the PDF. The Start and End Times classify what to show in each row.</em></label>
                            <div class="col-sm-8">
                                <table class="pdf_times_table table table-bordered">
                                    <tr>
                                        <th>Label</th>
                                        <th>Start Time</th>
                                        <th>End TIme</th>
                                        <th>Function</th>
                                    </tr>
                                    <?php $query = mysqli_query($dbc, "SELECT * FROM `field_config_charts_pdf_times` WHERE `chart` = '".$value['config_field']."' ORDER BY `fieldconfigid`");
                                    $row = mysqli_fetch_array($query);
                                    do { ?>
                                        <tr class="sortable_times">
                                            <td data-title="Label"><input type="text" name="<?= $value['config_field'] ?>_time_label[]" class="form-control" value="<?= $row['label'] ?>"></td>
                                            <td data-title="Start Time"><input type="text" name="<?= $value['config_field'] ?>_time_start_time[]" class="<?= $charts_time_format == '24h' ? 'datetimepicker-24h' : 'datetimepicker' ?> form-control" value="<?= $row['start_time'] ?>"></td>
                                            <td data-title="End Time"><input type="text" name="<?= $value['config_field'] ?>_time_end_time[]" class="<?= $charts_time_format == '24h' ? 'datetimepicker-24h' : 'datetimepicker' ?> form-control" value="<?= $row['end_time'] ?>"></td>
                                            <td data-title="Function">
                                                <img src="../img/icons/drag_handle.png" class="black-color inline-img pull-right drag-handle">
                                                <img src="../img/icons/ROOK-add-icon.png" class="inline-img pull-right" onclick="addPDFTime(this);">
                                                <img src="../img/remove.png" class="inline-img pull-right" onclick="removePDFTime(this);">
                                            </td>
                                        </tr>
                                    <?php } while($row = mysqli_fetch_array($query)); ?>
                                </table>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php
        $k++;
    }
}

?>
</div>



<div class="form-group">
    <div class="col-sm-6"><a href="index.php" class="btn config-btn btn-lg">Back</a></div>
    <div class="col-sm-6"><button type="submit" name="submit" value="Submit" class="btn config-btn btn-lg pull-right">Submit</button></div>
	<div class="clearfix"></div>
</div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>