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
$fields = '';
$desc = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];
	echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_vehicle_damage_report WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
	$desc = $get_field_level['desc'];
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
                    Employee Information<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info" class="panel-collapse collapse">
            <div class="panel-body">

				<?php if (strpos($form_config, ','."fields1".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Employee Name</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_0" value="<?php echo $fields[0]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Home phone</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_1" value="<?php echo $fields[1]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Cell Phone</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_2" value="<?php echo $fields[2]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Address</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_3" value="<?php echo $fields[3]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">D.O.B.</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_4" value="<?php echo $fields[4]; ?>" class="datepicker" />
                    </div>
					</div>
                <?php } ?>

			</div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_veh" >
                    Vehicle Information<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_veh" class="panel-collapse collapse">
            <div class="panel-body">

				<?php if (strpos($form_config, ','."fields1".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Vehicle Make</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_5" value="<?php echo $fields[5]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Model</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_6" value="<?php echo $fields[6]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Year</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_7" value="<?php echo $fields[7]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Color</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_8" value="<?php echo $fields[8]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Driver's License #</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_9" value="<?php echo $fields[9]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">License Plate #</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_10" value="<?php echo $fields[10]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Province</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_11" value="<?php echo $fields[11]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">VIN #</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_12" value="<?php echo $fields[12]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

			</div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_dam" >
                    Damage Information<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_dam" class="panel-collapse collapse">
            <div class="panel-body">

				<?php if (strpos($form_config, ','."fields1".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Damage Estimate</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_13" value="<?php echo $fields[13]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Description of Damage</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_14" value="<?php echo $fields[14]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Insurance Company</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_15" value="<?php echo $fields[15]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Policy #</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_16" value="<?php echo $fields[16]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Expiry Date</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_17" value="<?php echo $fields[17]; ?>" class="datepicker" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Occurrence Date</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_18" value="<?php echo $fields[18]; ?>" class="datepicker" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Time</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_19" value="<?php echo $fields[19]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Date Reported</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_20" value="<?php echo $fields[20]; ?>" class="datepicker" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Road Conditions</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_21" value="<?php echo $fields[21]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>
				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Weather Conditions</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_22" value="<?php echo $fields[22]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>
				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Location of incident</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_23" value="<?php echo $fields[23]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>
				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Police/RCMP Collision Report #</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_24" value="<?php echo $fields[24]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>
				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Collision involved</label>
                    <div class="col-sm-8">
                        <input type="radio" <?php if ($fields[25] == 'Wildlife') { echo " checked"; } ?>  name="fields_25" value="Wildlife">Wildlife&nbsp;&nbsp;
                        <input type="radio" <?php if ($fields[25] == 'Moving Vehicle') { echo " checked"; } ?>  name="fields_25" value="Moving Vehicle">Moving Vehicle&nbsp;&nbsp;
                        <input type="radio" <?php if ($fields[25] == 'Stationary Object') { echo " checked"; } ?>  name="fields_25" value="Stationary Object">Stationary Object&nbsp;&nbsp;
                        <input type="radio" <?php if ($fields[25] == 'Pedestrian') { echo " checked"; } ?>  name="fields_25" value="Pedestrian">Pedestrian&nbsp;&nbsp;
                        <input type="radio" <?php if ($fields[25] == 'Other') { echo " checked"; } ?>  name="fields_25" value="Other">Other &nbsp;&nbsp;
                        <input type="text" name="fields_26" value="<?php echo $fields[26]; ?>"   class="form-control" />
                    </div>
					</div>
                <?php } ?>
				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Any injuries as a result?</label>
                    <div class="col-sm-8">
                        <input type="radio" <?php if ($fields[27] == 'Yes') { echo " checked"; } ?>  name="fields_27" value="Yes">Yes&nbsp;&nbsp;
                        <input type="radio" <?php if ($fields[27] == 'No') { echo " checked"; } ?>  name="fields_27" value="No">No&nbsp;&nbsp;
                        If yes, what and to whom<input type="text" name="fields_28" value="<?php echo $fields[28]; ?>"   class="form-control" />
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
                    Other Driver Information<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info1" class="panel-collapse collapse">
            <div class="panel-body">

				<?php if (strpos($form_config, ','."fields1".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Employee Name</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_29" value="<?php echo $fields[29]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Home phone</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_30" value="<?php echo $fields[30]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Cell Phone</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_31" value="<?php echo $fields[31]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Address</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_32" value="<?php echo $fields[32]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">D.O.B.</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_33" value="<?php echo $fields[33]; ?>" class="datepicker" />
                    </div>
					</div>
                <?php } ?>

			</div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_veh1" >
                    Other Vehicle Information<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_veh1" class="panel-collapse collapse">
            <div class="panel-body">

				<?php if (strpos($form_config, ','."fields1".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Vehicle Make</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_34" value="<?php echo $fields[34]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Model</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_35" value="<?php echo $fields[35]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Year</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_36" value="<?php echo $fields[36]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Color</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_37" value="<?php echo $fields[37]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Driver's License #</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_38" value="<?php echo $fields[38]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">License Plate #</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_39" value="<?php echo $fields[39]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Province</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_40" value="<?php echo $fields[40]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">VIN #</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_41" value="<?php echo $fields[41]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

			</div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_dam1" >
                    Other Damage Information<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_dam1" class="panel-collapse collapse">
            <div class="panel-body">

				<?php if (strpos($form_config, ','."fields1".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Damage Estimate</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_42" value="<?php echo $fields[42]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Description of Damage</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_43" value="<?php echo $fields[43]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Insurance Company</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_44" value="<?php echo $fields[44]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Policy #</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_45" value="<?php echo $fields[45]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Expiry Date</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_46" value="<?php echo $fields[46]; ?>" class="datepicker" />
                    </div>
					</div>
                <?php } ?>

			</div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_wit" >
                    Witness<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_wit" class="panel-collapse collapse">
            <div class="panel-body">

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Name</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_47" value="<?php echo $fields[47]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Address</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_48" value="<?php echo $fields[48]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Phone</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_49" value="<?php echo $fields[49]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Name</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_50" value="<?php echo $fields[50]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Address</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_51" value="<?php echo $fields[51]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Phone</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_52" value="<?php echo $fields[52]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

			</div>
        </div>
    </div>

    <?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_des" >
                    Description of events leading up to accident<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_des" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Description</label>
                <div class="col-sm-8">
                <textarea name="desc" rows="3" cols="50" class="form-control"><?php echo $desc; ?></textarea>
                </div>
                </div>

			</div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_12" >
                    Check One<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_12" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">I was</label>
                    <div class="col-sm-8">
                        <input type="radio" <?php if ($fields[53] == 'Driver of a Vehicle A') { echo " checked"; } ?>  name="fields_53" value="Driver of a Vehicle A">Driver of a Vehicle A&nbsp;&nbsp;
                        <input type="radio" <?php if ($fields[53] == 'Passenger of Vehicle A') { echo " checked"; } ?>  name="fields_53" value="Passenger of Vehicle A">Passenger of Vehicle A&nbsp;&nbsp;
                    </div>
                </div>

			</div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_d" >
                    Diagram of Accident scene<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_d" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label"></label>
                    <div class="col-sm-8">
                        <?php echo include ('../phpsign/sign_big.php');
                        ?>
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