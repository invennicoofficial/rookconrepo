<?php
include ('../include.php');
checkAuthorised('charts');

if (isset($_POST['submit'])) {
    $pdf_logo = $_POST['logo_file'];
    if(!empty($_FILES['pdf_logo']['name'])) {
        $pdf_logo = htmlspecialchars($_FILES['pdf_logo']['name'], ENT_QUOTES);
        if (!file_exists('download')) {
            mkdir('download', 0777, true);
        }
        move_uploaded_file($_FILES['pdf_logo']['tmp_name'], '../Medical Charts/download/'.$pdf_logo);
    }
    
    $header_text = filter_var(htmlentities($_POST['header_text']),FILTER_SANITIZE_STRING);
    $header_align = filter_var($_POST['header_align'],FILTER_SANITIZE_STRING);
    $header_font = filter_var($_POST['header_font'],FILTER_SANITIZE_STRING);
    $header_size = filter_var($_POST['header_size'],FILTER_SANITIZE_STRING);
    $header_color = filter_var($_POST['header_color'],FILTER_SANITIZE_STRING);

    $footer_text = filter_var(htmlentities($_POST['footer_text']),FILTER_SANITIZE_STRING);
    $footer_align = filter_var($_POST['footer_align'],FILTER_SANITIZE_STRING);
    $footer_font = filter_var($_POST['footer_font'],FILTER_SANITIZE_STRING);
    $footer_size = filter_var($_POST['footer_size'],FILTER_SANITIZE_STRING);
    $footer_color = filter_var($_POST['footer_color'],FILTER_SANITIZE_STRING);

    $body_font = filter_var($_POST['body_font'],FILTER_SANITIZE_STRING);
    $body_size = filter_var($_POST['body_size'],FILTER_SANITIZE_STRING);
    $body_color = filter_var($_POST['body_color'],FILTER_SANITIZE_STRING);

    $pdf_settings = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `medical_charts_pdf_setting`"));
    if(!empty($pdf_settings)) {
        $query = "UPDATE `medical_charts_pdf_setting` SET `pdf_logo` = '$pdf_logo', `header_text` = '$header_text', `header_align` = '$header_align', `header_font` = '$header_font', `header_size` = '$header_size', `header_color` = '$header_color', `footer_text` = '$footer_text', `footer_align` = '$footer_align', `footer_font` = '$footer_font', `footer_size` = '$footer_size', `footer_color` = '$footer_color', `body_font` = '$body_font', `body_size` = '$body_size', `body_color` = '$body_color'";
    } else {
        $query = "INSERT INTO `medical_charts_pdf_setting` (`pdf_logo`, `header_text`, `header_align`, `header_font`, `header_size` ,`header_color`, `footer_text`, `footer_align`, `footer_font`, `footer_size`, `footer_color`, `body_font`, `body_size`, `body_color`) VALUES ('$pdf_logo', '$header_text', '$header_align', '$header_font', '$header_size' ,'header_color', '$footer_text', '$footer_align', '$footer_font', '$footer_size', '$footer_color', '$body_font', '$body_size', '$body_color')";
    }
    mysqli_query($dbc, $query);

    echo '<script type="text/javascript"> window.location.replace("field_config_pdf.php"); </script>';
}
?>
</head>
<script type="text/javascript">
function colorCodeChange(sel) {
    $(sel).closest('.form-group').find('[name$="color"]').val(sel.value);
}
</script>
<body>

<?php include ('../navigation.php'); ?>

<?php
$pdf_settings = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `medical_charts_pdf_setting`"));

$pdf_logo = !empty($pdf_settings['pdf_logo']) ? $pdf_settings['pdf_logo'] : '';

$header_text = !empty($pdf_settings['header_text']) ? $pdf_settings['header_text'] : '';
$header_align = !empty($pdf_settings['header_align']) ? $pdf_settings['header_align'] : 'R';
$header_font = !empty($pdf_settings['header_font']) ? $pdf_settings['header_font'] : 'helvetica';
$header_size = !empty($pdf_settings['header_size']) ? $pdf_settings['header_size'] : 9;
$header_color = !empty($pdf_settings['header_color']) ? $pdf_settings['header_color'] : '#000000';

$footer_text = !empty($pdf_settings['footer_text']) ? $pdf_settings['footer_text'] : '';
$footer_align = !empty($pdf_settings['footer_align']) ? $pdf_settings['footer_align'] : 'C';
$footer_font = !empty($pdf_settings['footer_font']) ? $pdf_settings['footer_font'] : 'helvetica';
$footer_size = !empty($pdf_settings['footer_size']) ? $pdf_settings['footer_size'] : 9;
$footer_color = !empty($pdf_settings['footer_color']) ? $pdf_settings['footer_color'] : '#000000';

$body_font = !empty($pdf_settings['body_font']) ? $pdf_settings['body_font'] : 'helvetica';
$body_size = !empty($pdf_settings['body_size']) ? $pdf_settings['body_size'] : 9;
$body_color = !empty($pdf_settings['body_color']) ? $pdf_settings['body_color'] : '#000000';
?>

<div class="container">
    <div class="row">
        <h1>Charts</h1>
        <div class="pad-left gap-top double-gap-bottom"><a href="index.php" class="btn config-btn">Back to Dashboard</a></div>

        <div class="tab-container">
            <div class="pull-left tab"><span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click this to configure the Fields for the Charts."><img src="<?= WEBSITE_URL ?>/img/info.png" width="20"></a></span><a href="field_config.php"><button type="button" class="btn brand-btn mobile-block">Fields</button></a></div>

            <?php $custom_monthly_charts = explode(',', get_config($dbc, 'custom_monthly_charts'));
            foreach ($custom_monthly_charts as $custom_monthly_chart) {
                if(!empty($custom_monthly_chart)) { ?>
                    <div class="pull-left tab"><span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click this to configure the Headings and Fields for this Custom Chart."><img src="<?= WEBSITE_URL ?>/img/info.png" width="20"></a></span><a href="field_config_custom.php?type=<?= $custom_monthly_chart ?>"><button type="button" class="btn brand-btn mobile-block"><?= $custom_monthly_chart ?></button></a></div>
                <?php }
            } ?>
            
            <div class="pull-left tab"><span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click this to configure the PDF Styling for the Charts."><img src="<?= WEBSITE_URL ?>/img/info.png" width="20"></a></span><a href="field_config_pdf.php"><button type="button" class="btn brand-btn mobile-block active_tab">PDF Styling</button></a></div>
        </div>

        <div class="clearfix"></div>

        <form id="form1" name="form1" method="post" enctype="multipart/form-data" class="form-horizontal" role="form">

            <div class="panel-group" id="accordion2">
                <!-- Logo Settings -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_logo">
                                Logo for PDF<span class="glyphicon glyphicon-plus"></span>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse_logo" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="pdf_logo" class="col-sm-4 control-label">
                                    Upload Logo:
                                </label>
                                <div class="col-sm-8">
                                    <?php if(!empty($pdf_logo)) { ?>
                                        <a href="<?= WEBSITE_URL ?>/Medical Charts/download/<?= $pdf_logo ?>" target="_blank">View</a>
                                        <input type="hidden" name="logo_file" value="<?= $pdf_logo ?>">
                                    <?php } ?>
                                    <input type="file" name="pdf_logo" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Header Settings -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_header">
                                Header Settings<span class="glyphicon glyphicon-plus"></span>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse_header" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="header_text" class="col-sm-4 control-label">
                                    Header Text:
                                </label>
                                <div class="col-sm-8">
                                    <textarea name="header_text" class="form-control"><?= html_entity_decode($header_text) ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="header_align" class="col-sm-4 control-label">
                                    Header Align
                                </label>
                                <div class="col-sm-8">
                                    <select name="header_align" class="chosen-select-deselect form-control">
                                        <option></option>
                                        <option <?= $header_align == 'L' ? 'selected' : '' ?> value="L">Left</option>
                                        <option <?= $header_align == 'C' ? 'selected' : '' ?> value="C">Center</option>
                                        <option <?= $header_align == 'R' ? 'selected' : '' ?> value="R">Right</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="header_font" class="col-sm-4 control-label">
                                    Header Font:
                                </label>
                                <div class="col-sm-8">
                                    <select name="header_font" class="chosen-select-deselect form-control">
                                        <option></option>
                                        <?php $font_array = array('courier'=>'Courier','helvetica'=>'Helvetica','times'=>'Times New Roman','zapfdingbats'=>'Zapf Dingbats','OpenSans'=>'Open Sans','Roboto'=>'Roboto','Encode Sans, sans-serif' => 'Encode Sans, sans-serif', 'Slabo, serif' => 'Slabo', 'Montserrat, sans-serif' => 'Montserrat', 'Raleway, sans-serif' => 'Raleway', 'Merriweather, sans-serif' => 'Merriweather', 'Lora, sans-serif' => 'Lora', 'Nunito, sans-serif' => 'Nunito', 'Karla, sans-serif' => 'Karla');
                                            ksort($font_array);
                                            foreach($font_array as $font_value => $font) { ?>
                                                <option <?= $header_font == $font_value ? 'selected' : '' ?> value="<?= $font_value ?>"><?= $font ?></option>
                                            <?php }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="header_size" class="col-sm-4 control-label">
                                    Header Size:
                                </label>
                                <div class="col-sm-8">
                                    <select name="header_size" class="chosen-select-deselect form-control">
                                        <option></option>
                                        <?php for($i = 9; $i < 50; $i++) { ?>
                                            <option <?= $header_size == $i ? 'selected' : '' ?> value="<?= $i ?>"><?= $i ?>pt</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="header_color" class="col-sm-4 control-label">
                                    Header Color:
                                </label>
                                <div class="col-sm-1">
                                    <input type="color" name="header_color_picker" value="<?= $header_color ?>" class="form-control" onchange="colorCodeChange(this);">
                                </div>
                                <div class="col-sm-7">
                                    <input type="text" name="header_color" value="<?= $header_color ?>" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Settings -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_footer">
                                Footer Settings<span class="glyphicon glyphicon-plus"></span>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse_footer" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="footer_text" class="col-sm-4 control-label">
                                    Footer Text:
                                </label>
                                <div class="col-sm-8">
                                    <textarea name="footer_text" class="form-control"><?= html_entity_decode($footer_text) ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="footer_align" class="col-sm-4 control-label">
                                    Footer Align
                                </label>
                                <div class="col-sm-8">
                                    <select name="footer_align" class="chosen-select-deselect form-control">
                                        <option></option>
                                        <option <?= $footer_align == 'L' ? 'selected' : '' ?> value="L">Left</option>
                                        <option <?= $footer_align == 'C' ? 'selected' : '' ?> value="C">Center</option>
                                        <option <?= $footer_align == 'R' ? 'selected' : '' ?> value="R">Right</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="footer_font" class="col-sm-4 control-label">
                                    Footer Font:
                                </label>
                                <div class="col-sm-8">
                                    <select name="footer_font" class="chosen-select-deselect form-control">
                                        <option></option>
                                        <?php $font_array = array('courier'=>'Courier','helvetica'=>'Helvetica','times'=>'Times New Roman','zapfdingbats'=>'Zapf Dingbats','OpenSans'=>'Open Sans','Roboto'=>'Roboto','Encode Sans, sans-serif' => 'Encode Sans, sans-serif', 'Slabo, serif' => 'Slabo', 'Montserrat, sans-serif' => 'Montserrat', 'Raleway, sans-serif' => 'Raleway', 'Merriweather, sans-serif' => 'Merriweather', 'Lora, sans-serif' => 'Lora', 'Nunito, sans-serif' => 'Nunito', 'Karla, sans-serif' => 'Karla');
                                            ksort($font_array);
                                            foreach($font_array as $font_value => $font) { ?>
                                                <option <?= $footer_font == $font_value ? 'selected' : '' ?> value="<?= $font_value ?>"><?= $font ?></option>
                                            <?php }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="footer_size" class="col-sm-4 control-label">
                                    Footer Size:
                                </label>
                                <div class="col-sm-8">
                                    <select name="footer_size" class="chosen-select-deselect form-control">
                                        <option></option>
                                        <?php for($i = 9; $i < 50; $i++) { ?>
                                            <option <?= $footer_size == $i ? 'selected' : '' ?> value="<?= $i ?>"><?= $i ?>pt</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="footer_color" class="col-sm-4 control-label">
                                    Footer Color:
                                </label>
                                <div class="col-sm-1">
                                    <input type="color" name="footer_color_picker" value="<?= $footer_color ?>" class="form-control" onchange="colorCodeChange(this);">
                                </div>
                                <div class="col-sm-7">
                                    <input type="text" name="footer_color" value="<?= $footer_color ?>" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Body Settings -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_body">
                                Body Settings<span class="glyphicon glyphicon-plus"></span>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse_body" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="body_font" class="col-sm-4 control-label">
                                    Body Font:
                                </label>
                                <div class="col-sm-8">
                                    <select name="body_font" class="chosen-select-deselect form-control">
                                        <option></option>
                                        <?php $font_array = array('courier'=>'Courier','helvetica'=>'Helvetica','times'=>'Times New Roman','zapfdingbats'=>'Zapf Dingbats','OpenSans'=>'Open Sans','Roboto'=>'Roboto','Encode Sans, sans-serif' => 'Encode Sans, sans-serif', 'Slabo, serif' => 'Slabo', 'Montserrat, sans-serif' => 'Montserrat', 'Raleway, sans-serif' => 'Raleway', 'Merriweather, sans-serif' => 'Merriweather', 'Lora, sans-serif' => 'Lora', 'Nunito, sans-serif' => 'Nunito', 'Karla, sans-serif' => 'Karla');
                                            ksort($font_array);
                                            foreach($font_array as $font_value => $font) { ?>
                                                <option <?= $body_font == $font_value ? 'selected' : '' ?> value="<?= $font_value ?>"><?= $font ?></option>
                                            <?php }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="body_size" class="col-sm-4 control-label">
                                    Body Size:
                                </label>
                                <div class="col-sm-8">
                                    <select name="body_size" class="chosen-select-deselect form-control">
                                        <option></option>
                                        <?php for($i = 9; $i < 50; $i++) { ?>
                                            <option <?= $body_size == $i ? 'selected' : '' ?> value="<?= $i ?>"><?= $i ?>pt</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="body_color" class="col-sm-4 control-label">
                                    Body Color:
                                </label>
                                <div class="col-sm-1">
                                    <input type="color" name="body_color_picker" value="<?= $body_color ?>" class="form-control" onchange="colorCodeChange(this);">
                                </div>
                                <div class="col-sm-7">
                                    <input type="text" name="body_color" value="<?= $body_color ?>" class="form-control">
                                </div>
                            </div>
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