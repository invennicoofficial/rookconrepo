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
$fields_option = '';
$fields_value = '';
$fields = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];
	echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_vehicle_inspection_checklist WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
	$make = $get_field_level['make'];
	$vehicle_type = $get_field_level['vehicle_type'];
	$serial_number = $get_field_level['serial_number'];
	$model = $get_field_level['model'];
	$kilometers = $get_field_level['kilometers'];
	$fields_option = $get_field_level['fields_option'];
    $fields_value = explode('**FFM**', $get_field_level['fields_value']);
    $fields = explode('**FFM**', $get_field_level['fields']);
    
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

    			<?php if (strpos($form_config, ','."fields45".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Unit #</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_0" value="<?php echo $fields[0]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>
				<?php if (strpos($form_config, ','."fields46".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Next Service</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_1" value="<?php echo $fields[1]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>
				<?php if (strpos($form_config, ','."fields47".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Current Mileage / Hours</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_2" value="<?php echo $fields[2]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

			</div>
        </div>
    </div>

    <?php if (strpos($form_config, ','."fields8".',') !== FALSE) { ?>
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
                <label for="business_street" class="col-sm-4 control-label">Engine oil and filter</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field8_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_8" value="field8_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field8_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_8" value="field8_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field8_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_8" value="field8_na">N/A
			&nbsp;&nbsp;<input name="fields_value_8" type="text" value="<?php echo $fields_value[8]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields9".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Antifreeze</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field9_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_9" value="field9_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field9_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_9" value="field9_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field9_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_9" value="field9_na">N/A
			&nbsp;&nbsp;<input name="fields_value_9" type="text" value="<?php echo $fields_value[9]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields10".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Battery and tie down</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field10_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_10" value="field10_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field10_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_10" value="field10_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field10_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_10" value="field10_na">N/A
			&nbsp;&nbsp;<input name="fields_value_10" type="text" value="<?php echo $fields_value[10]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields11".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Battery cables</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field11_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_11" value="field11_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field11_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_11" value="field11_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field11_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_11" value="field11_na">N/A
			&nbsp;&nbsp;<input name="fields_value_11" type="text" value="<?php echo $fields_value[11]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields12".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Fan belts and Hoses</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field12_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_12" value="field12_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field12_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_12" value="field12_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field12_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_12" value="field12_na">N/A
			&nbsp;&nbsp;<input name="fields_value_12" type="text" value="<?php echo $fields_value[12]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields13".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Air filter and piping</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field13_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_13" value="field13_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field13_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_13" value="field13_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field13_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_13" value="field13_na">N/A
			&nbsp;&nbsp;<input name="fields_value_13" type="text" value="<?php echo $fields_value[13]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields14".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Exhaust System</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field14_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_14" value="field14_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field14_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_14" value="field14_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field14_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_14" value="field14_na">N/A
			&nbsp;&nbsp;<input name="fields_value_14" type="text" value="<?php echo $fields_value[14]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields15".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Fuel Tank and Lines</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field15_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_15" value="field15_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field15_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_15" value="field15_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field15_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_15" value="field15_na">N/A
			&nbsp;&nbsp;<input name="fields_value_15" type="text" value="<?php echo $fields_value[15]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields16".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Tires, Wheels, Tracks</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field16_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_16" value="field16_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field16_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_16" value="field16_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field16_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_16" value="field16_na">N/A
			&nbsp;&nbsp;<input name="fields_value_16" type="text" value="<?php echo $fields_value[16]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields17".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Service Brakes</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field17_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_17" value="field17_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field17_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_17" value="field17_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field17_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_17" value="field17_na">N/A
			&nbsp;&nbsp;<input name="fields_value_17" type="text" value="<?php echo $fields_value[17]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields18".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Parking Brakes</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field18_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_18" value="field18_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field18_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_18" value="field18_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field18_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_18" value="field18_na">N/A
			&nbsp;&nbsp;<input name="fields_value_18" type="text" value="<?php echo $fields_value[18]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields19".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Seat Belt</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field19_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_19" value="field19_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field19_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_19" value="field19_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field19_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_19" value="field19_na">N/A
			&nbsp;&nbsp;<input name="fields_value_19" type="text" value="<?php echo $fields_value[19]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields20".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Gauges and Alarms</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field20_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_20" value="field20_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field20_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_20" value="field20_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field20_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_20" value="field20_na">N/A
			&nbsp;&nbsp;<input name="fields_value_20" type="text" value="<?php echo $fields_value[20]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields21".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Heater, def., and a/c</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field21_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_21" value="field21_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field21_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_21" value="field21_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field21_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_21" value="field21_na">N/A
			&nbsp;&nbsp;<input name="fields_value_21" type="text" value="<?php echo $fields_value[21]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields22".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Horn, Lights</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field22_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_22" value="field22_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field22_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_22" value="field22_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field22_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_22" value="field22_na">N/A
			&nbsp;&nbsp;<input name="fields_value_22" type="text" value="<?php echo $fields_value[22]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields23".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Signal Lights</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field23_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_23" value="field23_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field23_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_23" value="field23_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field23_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_23" value="field23_na">N/A
			&nbsp;&nbsp;<input name="fields_value_23" type="text" value="<?php echo $fields_value[23]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields24".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Beacon</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field24_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_24" value="field24_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field24_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_24" value="field24_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field24_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_24" value="field24_na">N/A
			&nbsp;&nbsp;<input name="fields_value_24" type="text" value="<?php echo $fields_value[24]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields25".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Back-up Alarm</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field25_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_25" value="field25_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field25_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_25" value="field25_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field25_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_25" value="field25_na">N/A
			&nbsp;&nbsp;<input name="fields_value_25" type="text" value="<?php echo $fields_value[25]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields26".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Glass</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field26_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_26" value="field26_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field26_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_26" value="field26_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field26_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_26" value="field26_na">N/A
			&nbsp;&nbsp;<input name="fields_value_26" type="text" value="<?php echo $fields_value[26]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields27".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Window Wipers</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field27_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_27" value="field27_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field27_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_27" value="field27_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field27_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_27" value="field27_na">N/A
			&nbsp;&nbsp;<input name="fields_value_27" type="text" value="<?php echo $fields_value[27]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields28".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Mirrors</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field28_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_28" value="field28_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field28_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_28" value="field28_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field28_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_28" value="field28_na">N/A
			&nbsp;&nbsp;<input name="fields_value_28" type="text" value="<?php echo $fields_value[28]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields29".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Doors and Latches</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field29_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_29" value="field29_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field29_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_29" value="field29_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field29_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_29" value="field29_na">N/A
			&nbsp;&nbsp;<input name="fields_value_29" type="text" value="<?php echo $fields_value[29]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields30".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Body and Frame</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field30_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_30" value="field30_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field30_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_30" value="field30_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field30_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_30" value="field30_na">N/A
			&nbsp;&nbsp;<input name="fields_value_30" type="text" value="<?php echo $fields_value[30]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields31".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Steering System</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field31_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_31" value="field31_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field31_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_31" value="field31_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field31_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_31" value="field31_na">N/A
			&nbsp;&nbsp;<input name="fields_value_31" type="text" value="<?php echo $fields_value[31]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields32".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Operators Manual</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field32_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_32" value="field32_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field32_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_32" value="field32_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field32_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_32" value="field32_na">N/A
			&nbsp;&nbsp;<input name="fields_value_32" type="text" value="<?php echo $fields_value[32]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields33".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Grab Handles and Steps</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field33_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_33" value="field33_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field33_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_33" value="field33_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field33_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_33" value="field33_na">N/A
			&nbsp;&nbsp;<input name="fields_value_33" type="text" value="<?php echo $fields_value[33]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields34".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Hood and Latches</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field34_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_34" value="field34_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field34_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_34" value="field34_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field34_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_34" value="field34_na">N/A
			&nbsp;&nbsp;<input name="fields_value_34" type="text" value="<?php echo $fields_value[34]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields35".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Hydraulic Oil Levels</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field35_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_35" value="field35_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field35_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_35" value="field35_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field35_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_35" value="field35_na">N/A
			&nbsp;&nbsp;<input name="fields_value_35" type="text" value="<?php echo $fields_value[35]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields36".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Hydraulic Hoses</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field36_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_36" value="field36_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field36_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_36" value="field36_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field36_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_36" value="field36_na">N/A
			&nbsp;&nbsp;<input name="fields_value_36" type="text" value="<?php echo $fields_value[36]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields37".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Chains, straps, tie downs</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field37_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_37" value="field37_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field37_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_37" value="field37_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field37_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_37" value="field37_na">N/A
			&nbsp;&nbsp;<input name="fields_value_37" type="text" value="<?php echo $fields_value[37]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields38".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Air Leaks</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field38_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_38" value="field38_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field38_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_38" value="field38_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field38_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_38" value="field38_na">N/A
			&nbsp;&nbsp;<input name="fields_value_38" type="text" value="<?php echo $fields_value[38]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields39".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Oil Leaks</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field39_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_39" value="field39_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field39_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_39" value="field39_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field39_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_39" value="field39_na">N/A
			&nbsp;&nbsp;<input name="fields_value_39" type="text" value="<?php echo $fields_value[39]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields40".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Transmission Oil Level</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field40_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_40" value="field40_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field40_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_40" value="field40_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field40_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_40" value="field40_na">N/A
			&nbsp;&nbsp;<input name="fields_value_40" type="text" value="<?php echo $fields_value[40]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields41".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Fire Extinguisher</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field41_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_41" value="field41_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field41_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_41" value="field41_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field41_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_41" value="field41_na">N/A
			&nbsp;&nbsp;<input name="fields_value_41" type="text" value="<?php echo $fields_value[41]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields42".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">First Aid Kit and Flares</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field42_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_42" value="field42_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field42_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_42" value="field42_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field42_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_42" value="field42_na">N/A
			&nbsp;&nbsp;<input name="fields_value_42" type="text" value="<?php echo $fields_value[42]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields43".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Permits</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field43_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_43" value="field43_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field43_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_43" value="field43_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field43_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_43" value="field43_na">N/A
			&nbsp;&nbsp;<input name="fields_value_43" type="text" value="<?php echo $fields_value[43]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields44".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Insurance - Registration</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field44_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_44" value="field44_good">Good
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field44_bad,') !== FALSE) { echo " checked"; } ?>  name="fields_option_44" value="field44_bad">Bad
			<input type="radio" <?php if (strpos(','.$fields_option.',', ',field44_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_44" value="field44_na">N/A
			&nbsp;&nbsp;<input name="fields_value_44" type="text" value="<?php echo $fields_value[44]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>


			</div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos($form_config, ','."fields48".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info48" >
                    Walk Around Check<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info48" class="panel-collapse collapse">
            <div class="panel-body">

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Tires and wheel nuts</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[3] == 'OK') { echo " checked"; } ?>  name="fields_3" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[3] == 'Work required') { echo " checked"; } ?>  name="fields_3" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_4" value="<?php echo $fields[4]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Head lights</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[5] == 'OK') { echo " checked"; } ?>  name="fields_5" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[5] == 'Work required') { echo " checked"; } ?>  name="fields_5" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_6" value="<?php echo $fields[6]; ?>"   class="form-control" />
                </div>
				</div>
				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Tail lights</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[7] == 'OK') { echo " checked"; } ?>  name="fields_7" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[7] == 'Work required') { echo " checked"; } ?>  name="fields_7" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_8" value="<?php echo $fields[8]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Signal lights</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[9] == 'OK') { echo " checked"; } ?>  name="fields_9" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[9] == 'Work required') { echo " checked"; } ?>  name="fields_9" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_10" value="<?php echo $fields[10]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Brake lights</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[11] == 'OK') { echo " checked"; } ?>  name="fields_11" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[11] == 'Work required') { echo " checked"; } ?>  name="fields_11" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_12" value="<?php echo $fields[12]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Park lights</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[13] == 'OK') { echo " checked"; } ?>  name="fields_13" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[13] == 'Work required') { echo " checked"; } ?>  name="fields_13" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_14" value="<?php echo $fields[14]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Windshield and wipers</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[15] == 'OK') { echo " checked"; } ?>  name="fields_15" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[15] == 'Work required') { echo " checked"; } ?>  name="fields_15" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_16" value="<?php echo $fields[16]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Body damage</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[17] == 'OK') { echo " checked"; } ?>  name="fields_17" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[17] == 'Work required') { echo " checked"; } ?>  name="fields_17" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_18" value="<?php echo $fields[18]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Paint</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[19] == 'OK') { echo " checked"; } ?>  name="fields_19" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[19] == 'Work required') { echo " checked"; } ?>  name="fields_19" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_20" value="<?php echo $fields[20]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Tool boxes and deck</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[21] == 'OK') { echo " checked"; } ?>  name="fields_21" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[21] == 'Work required') { echo " checked"; } ?>  name="fields_21" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_22" value="<?php echo $fields[22]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Fire extinguisher</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[23] == 'OK') { echo " checked"; } ?>  name="fields_23" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[23] == 'Work required') { echo " checked"; } ?>  name="fields_23" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_24" value="<?php echo $fields[24]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Jack</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[25] == 'OK') { echo " checked"; } ?>  name="fields_25" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[25] == 'Work required') { echo " checked"; } ?>  name="fields_25" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_26" value="<?php echo $fields[26]; ?>"   class="form-control" />
                </div>
				</div>

			</div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos($form_config, ','."fields49".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info49" >
                    Under The Hood<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info49" class="panel-collapse collapse">
            <div class="panel-body">

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Fluid leaks</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[27] == 'OK') { echo " checked"; } ?>  name="fields_27" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[27] == 'Work required') { echo " checked"; } ?>  name="fields_27" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_28" value="<?php echo $fields[28]; ?>"   class="form-control" />
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Engine oil levels</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[29] == 'OK') { echo " checked"; } ?>  name="fields_29" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[29] == 'Work required') { echo " checked"; } ?>  name="fields_29" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_30" value="<?php echo $fields[30]; ?>"   class="form-control" />
                </div>

				</div>
				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Transmission fluid levels</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[31] == 'OK') { echo " checked"; } ?>  name="fields_31" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[31] == 'Work required') { echo " checked"; } ?>  name="fields_31" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_32" value="<?php echo $fields[32]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Coolant surge tank</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[33] == 'OK') { echo " checked"; } ?>  name="fields_33" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[33] == 'Work required') { echo " checked"; } ?>  name="fields_33" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_34" value="<?php echo $fields[34]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Brake fluid</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[35] == 'OK') { echo " checked"; } ?>  name="fields_35" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[35] == 'Work required') { echo " checked"; } ?>  name="fields_35" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_36" value="<?php echo $fields[36]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Power steering fluid levels</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[37] == 'OK') { echo " checked"; } ?>  name="fields_37" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[37] == 'Work required') { echo " checked"; } ?>  name="fields_37" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_38" value="<?php echo $fields[38]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Belts</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[39] == 'OK') { echo " checked"; } ?>  name="fields_39" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[39] == 'Work required') { echo " checked"; } ?>  name="fields_39" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_40" value="<?php echo $fields[40]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Hoses</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[41] == 'OK') { echo " checked"; } ?>  name="fields_41" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[41] == 'Work required') { echo " checked"; } ?>  name="fields_41" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_42" value="<?php echo $fields[42]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Plug wires</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[43] == 'OK') { echo " checked"; } ?>  name="fields_43" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[43] == 'Work required') { echo " checked"; } ?>  name="fields_43" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_44" value="<?php echo $fields[44]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Battery / battery terminals</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[45] == 'OK') { echo " checked"; } ?>  name="fields_45" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[45] == 'Work required') { echo " checked"; } ?>  name="fields_45" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_46" value="<?php echo $fields[46]; ?>"   class="form-control" />
                </div>
				</div>

			</div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos($form_config, ','."fields50".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info50" >
                    Inside The Truck<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info50" class="panel-collapse collapse">
            <div class="panel-body">

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Seat belts</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[47] == 'OK') { echo " checked"; } ?>  name="fields_47" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[47] == 'Work required') { echo " checked"; } ?>  name="fields_47" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_48" value="<?php echo $fields[48]; ?>"   class="form-control" />
                </div>
				</div>
				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Flares (if any)</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[49] == 'OK') { echo " checked"; } ?>  name="fields_49" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[49] == 'Work required') { echo " checked"; } ?>  name="fields_49" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_50" value="<?php echo $fields[50]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Radio</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[51] == 'OK') { echo " checked"; } ?>  name="fields_51" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[51] == 'Work required') { echo " checked"; } ?>  name="fields_51" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_52" value="<?php echo $fields[52]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Safety blanket</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[53] == 'OK') { echo " checked"; } ?>  name="fields_53" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[53] == 'Work required') { echo " checked"; } ?>  name="fields_53" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_54" value="<?php echo $fields[54]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Seats</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[55] == 'OK') { echo " checked"; } ?>  name="fields_55" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[55] == 'Work required') { echo " checked"; } ?>  name="fields_55" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_56" value="<?php echo $fields[56]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">First aid kit</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[57] == 'OK') { echo " checked"; } ?>  name="fields_57" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[57] == 'Work required') { echo " checked"; } ?>  name="fields_57" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_58" value="<?php echo $fields[58]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Cleanliness</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[59] == 'OK') { echo " checked"; } ?>  name="fields_59" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[59] == 'Work required') { echo " checked"; } ?>  name="fields_59" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_60" value="<?php echo $fields[60]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Insurance</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[61] == 'OK') { echo " checked"; } ?>  name="fields_61" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[61] == 'Work required') { echo " checked"; } ?>  name="fields_61" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_62" value="<?php echo $fields[62]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Registration</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[63] == 'OK') { echo " checked"; } ?>  name="fields_63" value="OK">OK&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[63] == 'Work required') { echo " checked"; } ?>  name="fields_63" value="Work required">Work required &nbsp;&nbsp;
                    <input type="text" name="fields_64" value="<?php echo $fields[64]; ?>"   class="form-control" />
                </div>
				</div>

			</div>
        </div>
    </div>
    <?php } ?>

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

            <?php if (strpos($assign_staff_sa, 'Extra') !== false) { ?>
               <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Name:</label>
                <div class="col-sm-8">
                    <input name="assign_staff_<?php echo $assign_staff_id;?>" type="text" class="form-control" />
                </div>
              </div>
            <?php } ?>

            <?php $output_name = 'sign_'.$assign_staff_id;
            include('../phpsign/sign_multiple.php'); ?>

            <?php } else {
                echo '<img src="vehicle_inspection_checklist/download/safety_'.$assign_staff_id.'.png">';
            } ?>

        </div>
    </div>
</div>
<?php $sa_inc++;
    }
} ?>

</div>


