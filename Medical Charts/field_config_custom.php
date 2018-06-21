<?php
include ('../include.php');
checkAuthorised('charts');
include 'config.php';

if (isset($_POST['submit'])) {
    $chart_name = $_POST['chart_name'];
    $no_client = filter_var($_POST['no_client'],FILTER_SANITIZE_STRING);
    $client_cat = filter_var($_POST['client_cat'],FILTER_SANITIZE_STRING);
    $add_comments = filter_var($_POST['add_comments'],FILTER_SANITIZE_STRING);
    mysqli_query($dbc, "INSERT INTO `field_config_custom_charts_settings` (`name`) SELECT '$chart_name' FROM (SELECT COUNT(*) rows FROM `field_config_custom_charts_settings` WHERE `name` = '$chart_name') num WHERE num.rows = 0");
    mysqli_query($dbc, "UPDATE `field_config_custom_charts_settings` SET `no_client` = '$no_client', `client_category` = '$client_cat', `add_comments` = '$add_comments' WHERE `name` = '$chart_name'");

    $headings_keep = [];
    foreach($_POST['heading_name'] as $key => $value) {
        $fieldconfigid = $_POST['heading_configid'][$key];
        $heading_name = $value;
        if(!empty($heading_name)) {
            if(!empty($fieldconfigid)) {
                mysqli_query($dbc, "UPDATE `field_config_custom_charts` SET `heading` = '$heading_name' WHERE `fieldconfigid` = '$fieldconfigid'");
            } else {
                mysqli_query($dbc, "INSERT INTO `field_config_custom_charts` (`name`, `heading`) VALUES ('$chart_name', '$heading_name')");
                $fieldconfigid = mysqli_insert_id($dbc);
            }
            $headings_keep[] = $fieldconfigid;
            $fields_keep = [];
            foreach($_POST['field_'.$key] as $key2 => $value2) {
                $fieldid = $_POST['field_configid_'.$key][$key2];
                $field = $value2;
                if(!empty($field)) {
                    if(!empty($fieldid)) {
                        mysqli_query($dbc, "UPDATE `field_config_custom_charts_lines` SET `field` = '$field' WHERE `fieldconfigid` = '$fieldid'");
                    } else {
                        mysqli_query($dbc, "INSERT INTO `field_config_custom_charts_lines` (`headingid`, `field`) VALUES ('$fieldconfigid', '$field')");
                        $fieldid = mysqli_insert_id($dbc);
                    }
                    $fields_keep[] = $fieldid;
                }
            }
            $fields_keep = "'".implode("','", $fields_keep)."'";
            mysqli_query($dbc, "UPDATE `field_config_custom_charts_lines` SET `deleted` = 1 WHERE `headingid` = '$fieldconfigid' AND `fieldconfigid` NOT IN ($fields_keep)");
        }
    }
    $headings_keep = "'".implode("','", $headings_keep)."'";
    mysqli_query($dbc, "UPDATE `field_config_custom_charts` SET `deleted` = 1 WHERE `name` = '$chart_name' AND `fieldconfigid` NOT IN ($headings_keep)");
}
?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<script type="text/javascript">
$(document).ready(function() {
});
function addField(sel) {
    var field_block = $(sel).closest('.heading_block').find('.field_block').last();
    var clone = field_block.clone();

    clone.find('.form-control').val('');
    field_block.after(clone);
}
function deleteField(sel) {
    if ($(sel).closest('.heading_block').find('.field_block').length <= 1) {
        addField(sel);
    }
    $(sel).closest('.field_block').remove();
}
function addHeading() {
    var heading_block = $('.heading_block').last();
    var clone = heading_block.clone();
    var counter = parseInt($('#heading_counter').val());

    while (clone.find('.field_block').length > 1) {
        clone.find('.field_block').first().remove();
    }
    clone.find('.form-control').val('');
    clone.find('.heading_name').attr('name', 'heading_name['+counter+']');
    clone.find('.heading_configid').attr('name', 'heading_configid['+counter+']');
    clone.find('.heading_field').attr('name', 'field_'+counter+'[]');
    clone.find('.heading_field_configid').attr('name', 'field_configid_'+counter+'[]');
    heading_block.after(clone);

    $('#heading_counter').val(counter+1);
}
function deleteHeading(sel) {
    if ($('.heading_block').length <= 1) {
        addHeading();
    }
    $(sel).closest('.heading_block').remove();
}
function showClientCat(chk) {
    if($(chk).is(':checked')) {
        $('.client_cat').hide();
    } else {
        $('.client_cat').show();
    }
}
</script>

<div class="container">
<div class="row">
<h1>Charts</h1>
<div class="pad-left gap-top double-gap-bottom"><a href="index.php" class="btn config-btn">Back to Dashboard</a></div>

<div class="tab-container">
    <div class="pull-left tab"><span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click this to configure the Fields for the Charts."><img src="<?= WEBSITE_URL ?>/img/info.png" width="20"></a></span><a href="field_config.php"><button type="button" class="btn brand-btn mobile-block">Fields</button></a></div>

    <?php $custom_monthly_charts = explode(',', get_config($dbc, 'custom_monthly_charts'));
    foreach ($custom_monthly_charts as $custom_monthly_chart) {
        if(!empty($custom_monthly_chart)) { ?>
            <div class="pull-left tab"><span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click this to configure the Headings and Fields for this Custom Chart."><img src="<?= WEBSITE_URL ?>/img/info.png" width="20"></a></span><a href="field_config_custom.php?type=<?= $custom_monthly_chart ?>"><button type="button" class="btn brand-btn mobile-block <?= $_GET['type'] == $custom_monthly_chart ? 'active_tab' : '' ?>"><?= $custom_monthly_chart ?></button></a></div>
        <?php }
    } ?>

    <div class="pull-left tab"><span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click this to configure the PDF Styling for the Charts."><img src="<?= WEBSITE_URL ?>/img/info.png" width="20"></a></span><a href="field_config_pdf.php"><button type="button" class="btn brand-btn mobile-block">PDF Styling</button></a></div>
</div>

<div class="clearfix"></div>

<form id="form1" name="form1" method="post" enctype="multipart/form-data" class="form-horizontal" role="form">
<?php $chart = $_GET['type']; ?>
<input type="hidden" name="chart_name" value="<?= $chart ?>">
<div class="panel-group" id="accordion2">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_general" >
                    Choose Headings and Fields<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>
        <div id="collapse_general" class="panel-collapse collapse">
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label col-sm-4">No Client:</label>
                    <div class="col-sm-8">
                        <?php $settings = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_custom_charts_settings` WHERE `name` = '$chart'")); ?>
                        <label class="form-checkbox"><input type="checkbox" name="no_client" value="1" <?= $settings['no_client'] == 1 ? 'checked' : '' ?> onchange="showClientCat(this);"> Enable</label>
                    </div>
                </div>
                <div class="form-group client_cat" <?= $settings['no_client'] == 1 ? 'style="display:none;"' : '' ?>>
                    <label class="control-label col-sm-4">Client Category:</label>
                    <div class="col-sm-8">
                        <select name="client_cat" placeholder="Select a Category" class="chosen-select-deselect form-control"><option></option>
                            <?php $category_list = mysqli_fetch_all(mysqli_query($dbc, "SELECT DISTINCT `category` FROM `contacts` WHERE `deleted` = 0 AND `status` = 1 AND IFNULL(`category`,'') != '' ORDER BY `category`"),MYSQLI_ASSOC);
                            if(empty($settings['client_category'])) {
                                $settings['client_category'] = 'Clients';
                            }
                            foreach($category_list as $category) { ?>
                                <option value="<?= $category['category'] ?>" <?= $category['category'] == $settings['client_category'] ? 'selected' : '' ?>><?= $category['category'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4">Allow Comments:</label>
                    <div class="col-sm-8">
                        <label class="form-checkbox"><input type="checkbox" name="add_comments" value="1" <?= $settings['add_comments'] == 1 ? 'checked' : '' ?>> Enable</label>
                    </div>
                </div>
                <hr>
                <?php $headings = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `field_config_custom_charts` WHERE `name` = '$chart' AND `deleted` = 0"),MYSQLI_ASSOC);
                for ($i = 0; $i < count($headings) || $i < 1; $i++) { ?>
                    <div class="heading_block">
                        <div class="form-group">
                            <label class="control-label col-sm-4"><a href="#" onclick="deleteHeading(this); return false"><img src="<?= WEBSITE_URL ?>/img/remove.png" height="20" /></a> Heading Name:</label>
                            <div class="col-sm-8">
                                <input type="text" name="heading_name[<?= $i ?>]" class="form-control heading_name" value="<?= $headings[$i]['heading'] ?>">
                                <input type="hidden" name="heading_configid[<?= $i ?>]" class="form-control heading_configid" value="<?= $headings[$i]['fieldconfigid'] ?>">
                            </div>
                        </div>
                        <?php $fields = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `field_config_custom_charts_lines` WHERE `headingid` = '".$headings[$i]['fieldconfigid']."'"),MYSQLI_ASSOC);
                        for ($j = 0; $j < count($fields) || $j < 1; $j++) { ?>
                            <div class="form-group field_block">
                                <label class="control-label col-sm-4">Field:</label>
                                <div class="col-sm-7">
                                    <input type="text" name="field_<?= $i ?>[]" class="form-control heading_field" value="<?= $fields[$j]['field'] ?>">
                                    <input type="hidden" name="field_configid_<?= $i ?>[]" class="form-control heading_field_configid" value="<?= $fields[$j]['fieldconfigid'] ?>">
                                </div>
                                <div class="col-sm-1 pull-right">
                                    <a href="#" onclick="deleteField(this); return false;"><img src="<?= WEBSITE_URL; ?>/img/remove.png" height="20" /></a>&nbsp;&nbsp;<a href="#" class="add_field" onclick="addField(this); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/plus.png" height="20" /></a>
                                </div>
                            </div>
                        <?php } ?>
                        <hr>
                    </div>
                <?php } ?>
                <input type="hidden" id="heading_counter" name="heading_counter" value="<?= $i ?>">
                <button name="add_heading" class="btn brand-btn pull-right" onclick="addHeading(); return false;">Add Heading</button>
            </div>
        </div>
    </div>
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