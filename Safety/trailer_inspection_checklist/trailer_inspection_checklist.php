<?php
/*
Add	Sheet
*/
include ('../database_connection.php');
include_once('../tcpdf/tcpdf.php');
require_once('../phpsign/signature-to-image.php');
error_reporting(0);
?>
<style>
.form-control {
    width: 40%;
    display: inline;
}
</style>
<script type="text/javascript">
	$(document).ready(function(){
        $("#form1").submit(function( event ) {
            var jobid = $("#jobid").val();
            var contactid = $("input[name=contactid]").val();
            var job_location = $("input[name=location]").val();
            if (contactid == '' || job_location == '') {
                //alert("Please make sure you have filled in all of the required fields.");
                //return false;
            }
        });
    });
</script>
</head>
<body>

<?php
$today_date = date('Y-m-d');
$contactid = $_SESSION['contactid'];
$make = '';
$vehicle_type = '';
$serial_number = '';
$model = '';
$kilometers = '';
$fields = '';
$fields_value = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];
	echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_trailer_inspection_checklist WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
	$make = $get_field_level['make'];
	$vehicle_type = $get_field_level['vehicle_type'];
	$serial_number = $get_field_level['serial_number'];
	$model = $get_field_level['model'];
	$kilometers = $get_field_level['kilometers'];
	$fields = $get_field_level['fields'];
    $fields_value = explode('**FFM**', $get_field_level['fields_value']);
}

$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$form'"));
$form_config = ','.$get_field_config['fields'].',';
?>

<div class="panel-group" id="accordion2">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info" >
                    Information<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info" class="panel-collapse collapse">
            <div class="panel-body">

				<?php if (strpos($form_config, ','."fields1".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Date</label>
                    <div class="col-sm-8">
                    <input type="text" name="today_date" value="<?php echo $today_date; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Make</label>
                    <div class="col-sm-8">
                    <input type="text" name="make" value="<?php echo $make; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Vehicle Type</label>
                    <div class="col-sm-8">
                    <input type="text" name="vehicle_type" value="<?php echo $vehicle_type; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Serial Number</label>
                    <div class="col-sm-8">
                    <input type="text" name="serial_number" value="<?php echo $serial_number; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Model</label>
                    <div class="col-sm-8">
                    <input type="text" name="model" value="<?php echo $model; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Kilometers</label>
                    <div class="col-sm-8">
                    <input type="text" name="kilometers" value="<?php echo $kilometers; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields7".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Inspected By</label>
                    <div class="col-sm-8">
                    <input type="text" name="contactid" value="<?php echo $contactid; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

			</div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info1" >
                    Checklist<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info1" class="panel-collapse collapse">
            <div class="panel-body">

			<?php if (strpos($form_config, ','."fields8".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Glad Hands </label>
                <div class="col-sm-8">
                    <input type="radio" <?php if (strpos(','.$fields.',', ',field8_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_8" value="field8_good">Good
                    <input type="radio" <?php if (strpos(','.$fields.',', ',field8_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_8" value="field8_bad">Bad
                    <input type="radio" <?php if (strpos(','.$fields.',', ',field8_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_8" value="field8_na">N/A
                    &nbsp;&nbsp;<input name="fields_value_8" type="text" value="<?php echo $fields_value[8]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields9".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">King Pin/Coupler </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field9_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_9" value="field9_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field9_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_9" value="field9_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field9_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_9" value="field9_na">N/A
			&nbsp;&nbsp;<input name="fields_value_9" type="text" value="<?php echo $fields_value[9]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields10".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Jacks or legs </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field10_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_10" value="field10_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field10_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_10" value="field10_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field10_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_10" value="field10_na">N/A
			&nbsp;&nbsp;<input name="fields_value_10" type="text" value="<?php echo $fields_value[10]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields11".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Tire wear & pressure </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field11_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_11" value="field11_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field11_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_11" value="field11_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field11_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_11" value="field11_na">N/A
			&nbsp;&nbsp;<input name="fields_value_11" type="text" value="<?php echo $fields_value[11]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields12".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Wheels & fasteners </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field12_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_12" value="field12_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field12_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_12" value="field12_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field12_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_12" value="field12_na">N/A
			&nbsp;&nbsp;<input name="fields_value_12" type="text" value="<?php echo $fields_value[12]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields13".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Hub Caps </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field13_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_13" value="field13_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field13_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_13" value="field13_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field13_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_13" value="field13_na">N/A
			&nbsp;&nbsp;<input name="fields_value_13" type="text" value="<?php echo $fields_value[13]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields14".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Hub Oil Leaks </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field14_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_14" value="field14_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field14_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_14" value="field14_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field14_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_14" value="field14_na">N/A
			&nbsp;&nbsp;<input name="fields_value_14" type="text" value="<?php echo $fields_value[14]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields15".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Bearing Adjustment </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field15_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_15" value="field15_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field15_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_15" value="field15_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field15_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_15" value="field15_na">N/A
			&nbsp;&nbsp;<input name="fields_value_15" type="text" value="<?php echo $fields_value[15]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields16".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Brake Chambers </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field16_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_16" value="field16_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field16_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_16" value="field16_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field16_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_16" value="field16_na">N/A
			&nbsp;&nbsp;<input name="fields_value_16" type="text" value="<?php echo $fields_value[16]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields17".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Brake Adjustment </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field17_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_17" value="field17_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field17_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_17" value="field17_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field17_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_17" value="field17_na">N/A
			&nbsp;&nbsp;<input name="fields_value_17" type="text" value="<?php echo $fields_value[17]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields18".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Brake Shoe Wear </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field18_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_18" value="field18_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field18_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_18" value="field18_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field18_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_18" value="field18_na">N/A
			&nbsp;&nbsp;<input name="fields_value_18" type="text" value="<?php echo $fields_value[18]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields19".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Brake Drum Wear </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field19_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_19" value="field19_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field19_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_19" value="field19_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field19_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_19" value="field19_na">N/A
			&nbsp;&nbsp;<input name="fields_value_19" type="text" value="<?php echo $fields_value[19]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields20".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Cam Shaft Bushings </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field20_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_20" value="field20_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field20_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_20" value="field20_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field20_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_20" value="field20_na">N/A
			&nbsp;&nbsp;<input name="fields_value_20" type="text" value="<?php echo $fields_value[20]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields21".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Slack Adjusters </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field21_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_21" value="field21_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field21_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_21" value="field21_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field21_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_21" value="field21_na">N/A
			&nbsp;&nbsp;<input name="fields_value_21" type="text" value="<?php echo $fields_value[21]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields22".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Air hose condition </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field22_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_22" value="field22_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field22_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_22" value="field22_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field22_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_22" value="field22_na">N/A
			&nbsp;&nbsp;<input name="fields_value_22" type="text" value="<?php echo $fields_value[22]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields23".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Air bags </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field23_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_23" value="field23_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field23_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_23" value="field23_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field23_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_23" value="field23_na">N/A
			&nbsp;&nbsp;<input name="fields_value_23" type="text" value="<?php echo $fields_value[23]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields24".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Air leaks </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field24_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_24" value="field24_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field24_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_24" value="field24_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field24_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_24" value="field24_na">N/A
			&nbsp;&nbsp;<input name="fields_value_24" type="text" value="<?php echo $fields_value[24]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields25".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Springs & Suspension </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field25_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_25" value="field25_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field25_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_25" value="field25_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field25_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_25" value="field25_na">N/A
			&nbsp;&nbsp;<input name="fields_value_25" type="text" value="<?php echo $fields_value[25]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields26".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Lights</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field26_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_26" value="field26_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field26_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_26" value="field26_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field26_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_26" value="field26_na">N/A
			&nbsp;&nbsp;<input name="fields_value_26" type="text" value="<?php echo $fields_value[26]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields27".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Reflectors</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field27_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_27" value="field27_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field27_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_27" value="field27_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field27_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_27" value="field27_na">N/A
			&nbsp;&nbsp;<input name="fields_value_27" type="text" value="<?php echo $fields_value[27]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields28".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Beacon/Strobes </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field28_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_28" value="field28_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field28_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_28" value="field28_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field28_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_28" value="field28_na">N/A
			&nbsp;&nbsp;<input name="fields_value_28" type="text" value="<?php echo $fields_value[28]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields29".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Frame</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field29_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_29" value="field29_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field29_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_29" value="field29_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field29_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_29" value="field29_na">N/A
			&nbsp;&nbsp;<input name="fields_value_29" type="text" value="<?php echo $fields_value[29]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields30".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Grab handles and steps </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field30_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_30" value="field30_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field30_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_30" value="field30_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field30_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_30" value="field30_na">N/A
			&nbsp;&nbsp;<input name="fields_value_30" type="text" value="<?php echo $fields_value[30]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields31".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Safety Shields </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field31_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_31" value="field31_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field31_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_31" value="field31_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field31_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_31" value="field31_na">N/A
			&nbsp;&nbsp;<input name="fields_value_31" type="text" value="<?php echo $fields_value[31]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields32".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Hydraulic Oil Level </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field32_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_32" value="field32_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field32_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_32" value="field32_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field32_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_32" value="field32_na">N/A
			&nbsp;&nbsp;<input name="fields_value_32" type="text" value="<?php echo $fields_value[32]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields33".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Hydraulic Cylinder Pins </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field33_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_33" value="field33_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field33_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_33" value="field33_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field33_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_33" value="field33_na">N/A
			&nbsp;&nbsp;<input name="fields_value_33" type="text" value="<?php echo $fields_value[33]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields34".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Linkage and Pins </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field34_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_34" value="field34_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field34_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_34" value="field34_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field34_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_34" value="field34_na">N/A
			&nbsp;&nbsp;<input name="fields_value_34" type="text" value="<?php echo $fields_value[34]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields35".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Chains, straps, tie downs </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field35_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_35" value="field35_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field35_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_35" value="field35_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field35_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_35" value="field35_na">N/A
			&nbsp;&nbsp;<input name="fields_value_35" type="text" value="<?php echo $fields_value[35]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields36".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Deck</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field36_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_36" value="field36_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field36_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_36" value="field36_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field36_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_36" value="field36_na">N/A
			&nbsp;&nbsp;<input name="fields_value_36" type="text" value="<?php echo $fields_value[36]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields37".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Visibility tape </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field37_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_37" value="field37_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field37_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_37" value="field37_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field37_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_37" value="field37_na">N/A
			&nbsp;&nbsp;<input name="fields_value_37" type="text" value="<?php echo $fields_value[37]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields38".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Tarp</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field38_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_38" value="field38_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field38_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_38" value="field38_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field38_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_38" value="field38_na">N/A
			&nbsp;&nbsp;<input name="fields_value_38" type="text" value="<?php echo $fields_value[38]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields39".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Tarp Roller </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field39_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_39" value="field39_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field39_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_39" value="field39_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field39_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_39" value="field39_na">N/A
			&nbsp;&nbsp;<input name="fields_value_39" type="text" value="<?php echo $fields_value[39]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields40".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Tail gate & linkage </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field40_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_40" value="field40_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field40_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_40" value="field40_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field40_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_40" value="field40_na">N/A
			&nbsp;&nbsp;<input name="fields_value_40" type="text" value="<?php echo $fields_value[40]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields41".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Side Boards </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field41_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_41" value="field41_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field41_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_41" value="field41_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field41_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_41" value="field41_na">N/A
			&nbsp;&nbsp;<input name="fields_value_41" type="text" value="<?php echo $fields_value[41]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields42".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Licence Plates </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field42_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_42" value="field42_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field42_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_42" value="field42_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field42_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_42" value="field42_na">N/A
			&nbsp;&nbsp;<input name="fields_value_42" type="text" value="<?php echo $fields_value[42]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields43".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Insurance - Registra </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field43_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_43" value="field43_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field43_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_43" value="field43_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields.',', ',field43_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_43" value="field43_na">N/A
			&nbsp;&nbsp;<input name="fields_value_43" type="text" value="<?php echo $fields_value[43]; ?>" class="form-control" />
			    </div>
            </div>
			<?php } ?>

			</div>
        </div>
    </div>


<?php if(!empty($_GET['formid'])) {
    $sa = mysqli_query($dbc, "SELECT * FROM safety_attendance WHERE fieldlevelriskid = '$formid' AND safetyid='$safetyid'");
    $sa_inc=  0;
    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_sa = $row_sa['assign_staff'];
        $assign_staff_id = $row_sa['safetyattid'];
        $assign_staff_done = $row_sa['done'];
        ?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_sa<?php echo $sa_inc;?>" >
                <?php echo $assign_staff_sa; ?><span class="glyphicon glyphicon-plus"></span>
            </a>
        </h4>
    </div>

    <div id="collapse_sa<?php echo $sa_inc;?>" class="panel-collapse collapse">
        <div class="panel-body">

            <?php
            if($assign_staff_done == 0) { ?>
            <?php include ('../phpsign/sign3.php');
            ?>

            <?php if (strpos($assign_staff_sa, 'Extra') !== false) { ?>
               <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Name:</label>
                <div class="col-sm-8">
                    <input name="assign_staff_<?php echo $assign_staff_id;?>" type="text" class="form-control" />
                </div>
              </div>
            <?php } ?>

            <div class="sigPad" id="linear2" style="width:404px;">
            <ul class="sigNav">
            <li class="drawIt"><a href="#draw-it" >Draw It</a></li>
            <li class="clearButton"><a href="#clear">Clear</a></li>
            </ul>
            <div class="sig sigWrapper" style="height:auto;">
            <div class="typed"></div>
            <canvas class="pad" width="400" height="150" style="border:2px solid black;"></canvas>
            <input type="hidden" name="sign_<?php echo $assign_staff_id;?>" class="output">
            </div>
            </div>

            <?php } ?>

        </div>
    </div>
</div>
<?php $sa_inc++;
    }
} ?>

</div>