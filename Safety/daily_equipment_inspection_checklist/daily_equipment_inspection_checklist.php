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
$company = '';
$inspection_time = '';
$job_number = '';
$model = '';
$type = '';
$check = '';
$equ_unit = '';
$odometer = '';
$trip_type = '';
$fields = '';
$fields_value = '';
$remarks = '';
$defect_status= '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];

    echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';

    $get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_daily_equipment_inspection_checklist WHERE fieldlevelriskid='$formid'"));

    $today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
    $company = $get_field_level['company'];
    $inspection_time = $get_field_level['inspection_time'];
    $job_number = $get_field_level['job_number'];

    $model = $get_field_level['model'];
    $type = $get_field_level['type'];
    $check = $get_field_level['check'];
    $equ_unit = $get_field_level['equ_unit'];
    $odometer = $get_field_level['odometer'];
    $trip_type = $get_field_level['trip_type'];
    $fields = $get_field_level['fields'];
    $fields_value = explode('**FFM**', $get_field_level['fields_value']);
    $remarks = $get_field_level['remarks'];
    $defect_status= $get_field_level['defect_status'];
    
}
?>

<?php
//$form_config = ','.get_config($dbc, 'safety_field_level_risk_assessment').',';
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
                    <label for="business_street" class="col-sm-4 control-label">Company:</label>
                    <div class="col-sm-8">
                        <input type="text" name="company" value="<?php echo $company; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Inspection Date:</label>
                    <div class="col-sm-8">
                        <input name="today_date" value="<?php echo $today_date; ?>" type="text" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

                <?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Inspection Time:</label>
                    <div class="col-sm-8">
                        <input name="inspection_time" value="<?php echo $inspection_time; ?>" type="text" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Job#:</label>
                    <div class="col-sm-8">
                        <input name="job_number" value="<?php echo $job_number; ?>" type="text" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Model:</label>
                    <div class="col-sm-8">
                        <input name="model" value="<?php echo $model; ?>" type="text" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields7".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Type of Equiment:</label>
                    <div class="col-sm-8">
                        <input name="type" value="<?php echo $type; ?>" type="text" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields8".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Check Item if ok:</label>
                    <div class="col-sm-8">
                        <input name="check" value="<?php echo $check; ?>" type="text" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields9".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Eqipment unit#:</label>
                    <div class="col-sm-8">
                        <input name="equ_unit" value="<?php echo $equ_unit; ?>" type="text" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields10".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Odometer-hours:</label>
                    <div class="col-sm-8">
                        <input name="odometer" value="<?php echo $odometer; ?>" type="text" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields11".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Trip Type:</label>
                    <div class="col-sm-8">
                        <input type="radio" <?php if ($trip_type == 'Pre Trip') { echo " checked"; } ?>  name="trip_type" value="Pre Trip">Pre Trip
                        <input type="radio" <?php if ($trip_type == 'Post Trip') { echo " checked"; } ?>  name="trip_type" value="Post Trip">Post Trip
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
                    Equipment Check<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info1" class="panel-collapse collapse">
            <div class="panel-body">

				<ul style="list-style-type: none;">
					<?php if (strpos(','.$form_config.',', ',fields12,') !== FALSE) { ?>
                    <div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Oil</label>
                        <div class="col-sm-8">
                            <input type="checkbox" <?php if (strpos(','.$fields.',', ',fields12,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields12">
                            <input name="fields_value_12" type="text" value="<?php echo $fields_value[12]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields13,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Coolant-Red</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields13,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields13"><input name="fields_value_13" type="text" value="<?php echo $fields_value[13]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields14,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Collant Overflow</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields14,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields14"><input name="fields_value_14" type="text" value="<?php echo $fields_value[14]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields15,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Hyadrulic Oil</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields15,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields15"><input name="fields_value_15" type="text" value="<?php echo $fields_value[15]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields16,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Hydraulic Oil - Leaks</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields16,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields16"><input name="fields_value_16" type="text" value="<?php echo $fields_value[16]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields17,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Transmission Oil</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields17,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields17"><input name="fields_value_17" type="text" value="<?php echo $fields_value[17]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields18,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Air Filter</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields18,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields18"><input name="fields_value_18" type="text" value="<?php echo $fields_value[18]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields19,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Belts</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields19,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields19"><input name="fields_value_19" type="text" value="<?php echo $fields_value[19]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields20,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Track SAG</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields20,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields20"><input name="fields_value_20" type="text" value="<?php echo $fields_value[20]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields21,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Brake, Emergency</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields21,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields21"><input name="fields_value_21" type="text" value="<?php echo $fields_value[21]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields22,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Planetaries</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields22,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields22"><input name="fields_value_22" type="text" value="<?php echo $fields_value[22]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields23,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Break Pedal</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields23,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields23"><input name="fields_value_23" type="text" value="<?php echo $fields_value[23]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields24,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Hydraulic Break Fluid</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields24,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields24"><input name="fields_value_24" type="text" value="<?php echo $fields_value[24]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields25,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Parking Break</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields25,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields25"><input name="fields_value_25" type="text" value="<?php echo $fields_value[25]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields26,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Defroster and Heaters</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields26,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields26"><input name="fields_value_26" type="text" value="<?php echo $fields_value[26]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields27,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Emergency Equipment</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields27,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields27"><input name="fields_value_27" type="text" value="<?php echo $fields_value[27]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields28,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Engine</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields28,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields28"><input name="fields_value_28" type="text" value="<?php echo $fields_value[28]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields29,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Exhaust System</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields29,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields29"><input name="fields_value_29" type="text" value="<?php echo $fields_value[29]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields30,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Fire Extinguisher</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields30,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields30"><input name="fields_value_30" type="text" value="<?php echo $fields_value[30]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields31,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Fuel System</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields31,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields31"><input name="fields_value_31" type="text" value="<?php echo $fields_value[31]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields32,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Generator/Alternator</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields32,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields32"><input name="fields_value_32" type="text" value="<?php echo $fields_value[32]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields33,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Horn</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields33,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields33"><input name="fields_value_33" type="text" value="<?php echo $fields_value[33]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields34,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Lights and Reflectors</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields34,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields34"><input name="fields_value_34" type="text" value="<?php echo $fields_value[34]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields35,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Head-Stoplights</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields35,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields35"><input name="fields_value_35" type="text" value="<?php echo $fields_value[35]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields36,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Tail-Dash Lights</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields36,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields36"><input name="fields_value_36" type="text" value="<?php echo $fields_value[36]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields37,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Blade</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields37,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields37"><input name="fields_value_37" type="text" value="<?php echo $fields_value[37]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields38,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Bucket</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields38,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields38"><input name="fields_value_38" type="text" value="<?php echo $fields_value[38]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields39,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Body Damage</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields39,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields39"><input name="fields_value_39" type="text" value="<?php echo $fields_value[39]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields40,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Doors</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields40,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields40"><input name="fields_value_40" type="text" value="<?php echo $fields_value[40]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields41,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Mirrors (Adjustment and Condition)</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields41,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields41"><input name="fields_value_41" type="text" value="<?php echo $fields_value[41]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields42,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Oil Pressure</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields42,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields42"><input name="fields_value_42" type="text" value="<?php echo $fields_value[42]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields43,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Radiator</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields43,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields43"><input name="fields_value_43" type="text" value="<?php echo $fields_value[43]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields44,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Driver's Sheat belt and Seat Security</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields44,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields44"><input name="fields_value_44" type="text" value="<?php echo $fields_value[44]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields45,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Cutting edges</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields45,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields45"><input name="fields_value_45" type="text" value="<?php echo $fields_value[45]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields46,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Ripper Teeth</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields46,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields46"><input name="fields_value_46" type="text" value="<?php echo $fields_value[46]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields47,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Towing And Coupling Devices</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields47,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields47"><input name="fields_value_47" type="text" value="<?php echo $fields_value[47]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields48,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Windshield and Windows</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields48,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields48"><input name="fields_value_48" type="text" value="<?php echo $fields_value[48]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

					<?php if (strpos(','.$form_config.',', ',fields49,') !== FALSE) { ?>
						<div class="form-group">
						<label for="business_street" class="col-sm-4 control-label">Windshield Washer and Wipers</label>
                        <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields49,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields49"><input name="fields_value_49" type="text" value="<?php echo $fields_value[49]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>
				</ul>

            </div>
        </div>
    </div>

    <?php if (strpos(','.$form_config.',', ',fields50,') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info2" >
                    Remarks<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info2" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Remarks:</label>
                    <div class="col-sm-8">
                      <textarea name="remarks" rows="5" cols="50" class="form-control"><?php echo $remarks; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields51,') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info4" >
                    Defect Status<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info4" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label"></label>
                    <div class="col-sm-8">
                        <input type="radio" <?php if ($defect_status == 'Above Defects Corrected') { echo " checked"; } ?>  name="defect_status" value="Above Defects Corrected">Above Defects Corrected<br>
                        <input type="radio" <?php if ($defect_status == 'Above Defects needs not be Corrected For Safe Operation of Vehicle But noted For Repair') { echo " checked"; } ?>  name="defect_status" value="Above Defects needs not be Corrected For Safe Operation of Vehicle But noted For Repair">Above Defects needs not be Corrected For Safe Operation of Vehicle But noted For Repair

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
                echo '<img src="daily_equipment_inspection_checklist/download/safety_'.$assign_staff_id.'.png">';
            } ?>

            </div>
        </div>
    </div>
    <?php $sa_inc++;
        }
    } ?>

</div>