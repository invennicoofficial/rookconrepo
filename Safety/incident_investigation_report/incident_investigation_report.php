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

		var inc = 1;
        $('#add_row_hazard').on( 'click', function () {
            $(".hide_show_service").show();
            var clone = $('.additional_hazard').clone();
            clone.find('.task_list').val('');
            clone.removeClass("additional_hazard");
            $('#add_here_new_hazard').append(clone);
            inc++;
            return false;
		});
    });
</script>
</head>
<body>

<?php
$today_date = date('Y-m-d');
$contactid = $_SESSION['contactid'];

$today_time = '';
$address = '';
$fields = '';
$person_in_charge = '';
$reporter = '';
$reported_to = '';
$date_reported = '';
$description_of_incident = '';
$direct_cause_of_incident = '';
$contributing_factor = '';
$over_the_cause = '';
$imm_act_req = '';
$ltm_act_req = '';
$immidiate_correcctive_act_req = '';
$immi_date_comp = '';
$long_trm_act_assign = '';
$long_term_date = '';
$dia_scene = '';
$fields = '';
$fields_value = '';
$incident = '';
$desc  = '';
$desc1  = '';
$all_task = '';
$accident = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];

    echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';

    $get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_incident_investigation_report WHERE fieldlevelriskid='$formid'"));

    $today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];

    $today_time = $get_field_level['today_time'];
    $address = $get_field_level['address'];
    $fields = $get_field_level['fields'];
    $fields_value = explode('**FFM**', $get_field_level['fields_value']);
    $person_in_charge = $get_field_level['person_in_charge'];
    $reporter = $get_field_level['reporter'];
    $reported_to = $get_field_level['reported_to'];
    $date_reported = $get_field_level['date_reported'];
    $description_of_incident = $get_field_level['description_of_incident'];
    $direct_cause_of_incident = $get_field_level['direct_cause_of_incident'];
    $contributing_factor = $get_field_level['contributing_factor'];
    $over_the_cause = $get_field_level['over_the_cause'];
    $imm_act_req = $get_field_level['imm_act_req'];
    $ltm_act_req = $get_field_level['ltm_act_req'];
    $immidiate_correcctive_act_req = $get_field_level['immidiate_correcctive_act_req'];
    $immi_date_comp = $get_field_level['immi_date_comp'];
    $long_trm_act_assign = $get_field_level['long_trm_act_assign'];
    $long_term_date = $get_field_level['long_term_date'];
    $dia_scene = $get_field_level['dia_scene'];
	$incident = explode('**FFM**', $get_field_level['incident']);
	$desc = $get_field_level['desc'];
	$desc1 = $get_field_level['desc1'];
	$all_task = $get_field_level['all_task'];
	$accident = explode('**FFM**', $get_field_level['accident']);
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
                    <label for="business_street" class="col-sm-4 control-label">Date of Incident:</label>
                    <div class="col-sm-8">
                        <input type="text" name="today_date" value="<?php echo $today_date; ?>" class="datepicker" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Time:</label>
                    <div class="col-sm-8">
                        <input type="text" name="today_time" value="<?php echo $today_time; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Address/Location:</label>
                        <div class="col-sm-8">
                            <input type="text" name="address" value="<?php echo $address; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields25".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">JOB #</label>
                        <div class="col-sm-8">
                            <input type="text" name="incident_0" value="<?php echo $incident[0]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields26".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Customer</label>
                        <div class="col-sm-8">
                            <input type="text" name="incident_1" value="<?php echo $incident[1]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields27".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">LSD #</label>
                        <div class="col-sm-8">
                            <input type="text" name="incident_2" value="<?php echo $incident[2]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields28".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Facility / Rig Name</label>
                        <div class="col-sm-8">
                            <input type="text" name="incident_3" value="<?php echo $incident[3]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields29".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Date Of Occurrence</label>
                        <div class="col-sm-8">
                            <input type="text" name="incident_4" value="<?php echo $incident[4]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields30".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Time</label>
                        <div class="col-sm-8">
                            <input type="text" name="incident_5" value="<?php echo $incident[5]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields31".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Location</label>
                        <div class="col-sm-8">
                            <input type="text" name="incident_6" value="<?php echo $incident[6]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields32".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Date Reported</label>
                        <div class="col-sm-8">
                            <input type="text" name="incident_7" value="<?php echo $incident[7]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields33".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Time</label>
                        <div class="col-sm-8">
                            <input type="text" name="incident_8" value="<?php echo $incident[8]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields34".',') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Did this incident involve a Subcontractor?</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($incident[9] == 'Yes') { echo " checked"; } ?>  name="incident_9" value="Yes">Yes&nbsp;&nbsp;
                    <input type="radio" <?php if ($incident[9] == 'No') { echo " checked"; } ?>  name="incident_9" value="No">No&nbsp;&nbsp;
                    </div>
					</div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Name of Subcontractor</label>
                    <div class="col-sm-8">
                    <input type="text" name="incident_10" value="<?php echo $incident[10]; ?>" class="form-control" />
                    </div>
			    </div>

				<?php } ?>

				<?php if (strpos($form_config, ','."fields35".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Person Reporting Incident</label>
                        <div class="col-sm-8">
                            <input type="text" name="incident_11" value="<?php echo $incident[11]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields36".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Occupation</label>
                        <div class="col-sm-8">
                            <input type="text" name="incident_12" value="<?php echo $incident[12]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields37".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Immediate Supervisor</label>
                        <div class="col-sm-8">
                            <input type="text" name="incident_13" value="<?php echo $incident[13]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields38".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Witness To Incident</label>
                        <div class="col-sm-8">
                            <input type="text" name="incident_14" value="<?php echo $incident[14]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields39".',') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Type Of Incident</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($incident[15] == 'Personal Injury') { echo " checked"; } ?>  name="incident_15" value="Personal Injury">Personal Injury&nbsp;&nbsp;
                    <input type="radio" <?php if ($incident[15] == 'Vehicle Damage') { echo " checked"; } ?>  name="incident_15" value="Vehicle Damage">Vehicle Damage&nbsp;&nbsp;
					<input type="radio" <?php if ($incident[15] == 'Occupational Illness') { echo " checked"; } ?>  name="incident_15" value="Occupational Illness">Occupational Illness&nbsp;&nbsp;<br>
					<input type="radio" <?php if ($incident[15] == 'Equipment Damage') { echo " checked"; } ?>  name="incident_15" value="Equipment Damage">Equipment Damage&nbsp;&nbsp;
					<input type="radio" <?php if ($incident[15] == 'Property Damage') { echo " checked"; } ?>  name="incident_15" value="Property Damage">Property Damage&nbsp;&nbsp;
					<input type="radio" <?php if ($incident[15] == 'Near Miss') { echo " checked"; } ?>  name="incident_15" value="Near Miss">Near Miss&nbsp;&nbsp;<br>
                    Others : <input type="text" name="incident_16" value="<?php echo $incident[16]; ?>" class="form-control" />
                    </div>
					</div>
				<?php } ?>


				<?php if (strpos($form_config, ','."fields64".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Employee Name</label>
                        <div class="col-sm-8">
                            <input type="text" name="accident_0" value="<?php echo $accident[0]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields65".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Experience (months / years)</label>
                        <div class="col-sm-8">
                            <input type="text" name="accident_1" value="<?php echo $accident[1]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields66".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Reported to</label>
                        <div class="col-sm-8">
                            <input type="text" name="accident_2" value="<?php echo $accident[2]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields67".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Legal Description</label>
                        <div class="col-sm-8">
                            <input type="text" name="accident_3" value="<?php echo $accident[3]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields68".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Client at time of Incident</label>
                        <div class="col-sm-8">
                            <input type="text" name="accident_4" value="<?php echo $accident[4]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields69".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Type of work being performed</label>
                        <div class="col-sm-8">
                            <input type="text" name="accident_5" value="<?php echo $accident[5]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>


				<?php if (strpos($form_config, ','."fields70".',') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Type of Incident</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($accident[6] == 'Injury') { echo " checked"; } ?>  name="accident_6" value="Injury">Injury&nbsp;&nbsp;
                    <input type="radio" <?php if ($accident[6] == 'Illness') { echo " checked"; } ?>  name="accident_6" value="Illness">Illness&nbsp;&nbsp;
                    <input type="radio" <?php if ($accident[6] == 'Fatality') { echo " checked"; } ?>  name="accident_6" value="Fatality">Fatality&nbsp;&nbsp;
                    <input type="radio" <?php if ($accident[6] == 'Near Miss') { echo " checked"; } ?>  name="accident_6" value="Near Miss">Near Miss&nbsp;&nbsp;
					<input type="radio" <?php if ($accident[6] == 'Other') { echo " checked"; } ?>  name="accident_6" value="Other">Other&nbsp;&nbsp;<br>
                    <input type="text" name="accident_7" value="<?php echo $accident[7]; ?>" class="form-control" />
                    </div>
					</div>
				<?php } ?>

			</div>
        </div>
    </div>

	<?php if (strpos(','.$form_config.',', ',fields71,') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_inju" >
                    Injury<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_inju" class="panel-collapse collapse">
            <div class="panel-body">

			<h4>(complete this section for occupational injury incidents only)</h4>

			<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Type of Injury</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($accident[8] == 'First Aid') { echo " checked"; } ?>  name="accident_8" value="First Aid">First Aid&nbsp;&nbsp;
                    <input type="radio" <?php if ($accident[8] == 'Medical Aid') { echo " checked"; } ?>  name="accident_8" value="Medical Aid">Medical Aid&nbsp;&nbsp;
					<input type="radio" <?php if ($accident[8] == 'Lost Time') { echo " checked"; } ?>  name="accident_8" value="Lost Time">Lost Time&nbsp;&nbsp;<br>
					<input type="radio" <?php if ($accident[8] == 'Modified Work') { echo " checked"; } ?>  name="accident_8" value="Modified Work">Modified Work&nbsp;&nbsp;
					<input type="radio" <?php if ($accident[8] == 'Fatality') { echo " checked"; } ?>  name="accident_8" value="Fatality">Fatality&nbsp;&nbsp;<br>
                    <input type="text" name="accident_9" value="<?php echo $accident[9]; ?>" class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Object Inflicting Injury</label>
                        <div class="col-sm-8">
                            <input type="text" name="accident_10" value="<?php echo $accident[10]; ?>" class="form-control" />
                        </div>
                    </div>

					<div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Nature of Injury</label>
                        <div class="col-sm-8">
                            <input type="text" name="accident_11" value="<?php echo $accident[11]; ?>" class="form-control" />
                        </div>
                    </div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">First Aid received?</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($accident[12] == 'Yes') { echo " checked"; } ?>  name="accident_12" value="Yes">Yes&nbsp;&nbsp;
                    <input type="radio" <?php if ($accident[12] == 'No') { echo " checked"; } ?>  name="accident_12" value="No">No&nbsp;&nbsp;
                    If yes, by whom?<input type="text" name="accident_13" value="<?php echo $accident[13]; ?>" class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Medical Attention received?</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($accident[14] == 'Yes') { echo " checked"; } ?>  name="accident_14" value="Yes">Yes&nbsp;&nbsp;
                    <input type="radio" <?php if ($accident[14] == 'No') { echo " checked"; } ?>  name="accident_14" value="No">No&nbsp;&nbsp;
                    If yes, what?<input type="text" name="accident_15" value="<?php echo $accident[15]; ?>" class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Name of Physician?</label>
                        <div class="col-sm-8">
                            <input type="text" name="accident_16" value="<?php echo $accident[16]; ?>" class="form-control" />
                        </div>
                    </div>

					<div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Location</label>
                        <div class="col-sm-8">
                            <input type="text" name="accident_17" value="<?php echo $accident[17]; ?>" class="form-control" />
                        </div>
                    </div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Has the incident been reported to the WCB?</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($accident[18] == 'Yes') { echo " checked"; } ?>  name="accident_18" value="Yes">Yes&nbsp;&nbsp;
                    <input type="radio" <?php if ($accident[18] == 'No') { echo " checked"; } ?>  name="accident_18" value="No">No&nbsp;&nbsp;
                    If yes, when?<input type="text" name="accident_19" value="<?php echo $accident[19]; ?>" class="form-control" />
                    </div>
					</div>

			</div>
        </div>
    </div>
	<?php } ?>

	<?php if (strpos(','.$form_config.',', ',fields72,') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_illness" >
                    Illness<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

		<div id="collapse_illness" class="panel-collapse collapse">
		<div class="panel-body">

		<h4>(complete this section for occupational illness incidents only)</h4>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Nature of Illness</label>
                    <div class="col-sm-8">
                    <input type="text" name="accident_20" value="<?php echo $accident[20]; ?>" class="form-control" />
                    </div>
                    </div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Suspected source of illness</label>
                    <div class="col-sm-8">
                    <input type="text" name="accident_21" value="<?php echo $accident[21]; ?>" class="form-control" />
                    </div>
                    </div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Location where symptoms first appeared</label>
                    <div class="col-sm-8">
                    <input type="text" name="accident_22" value="<?php echo $accident[22]; ?>" class="form-control" />
                    </div>
                    </div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Conditions when symptoms first appeared</label>
                    <div class="col-sm-8">
                    <input type="text" name="accident_23" value="<?php echo $accident[23]; ?>" class="form-control" />
                    </div>
                    </div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Have you received medical treatment?</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($accident[24] == 'Yes') { echo " checked"; } ?>  name="accident_24" value="Yes">Yes&nbsp;&nbsp;
                    <input type="radio" <?php if ($accident[24] == 'No') { echo " checked"; } ?>  name="accident_24" value="No">No&nbsp;&nbsp;
                    If yes, what?<input type="text" name="accident_25" value="<?php echo $accident[25]; ?>" class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Name of Physician?</label>
                    <div class="col-sm-8">
                    <input type="text" name="accident_26" value="<?php echo $accident[26]; ?>" class="form-control" />
                    </div>
                    </div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Location</label>
                    <div class="col-sm-8">
                    <input type="text" name="accident_27" value="<?php echo $accident[27]; ?>" class="form-control" />
                    </div>
                    </div>

			</div>
        </div>
    </div>
	<?php } ?>

	<?php if (strpos(','.$form_config.',', ',fields73,') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Near" >
                    Near Miss<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

		<div id="collapse_Near" class="panel-collapse collapse">
		<div class="panel-body">

		<h4>(complete this section for Near Miss incidents only)</h4>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Have you received medical treatment?</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($accident[28] == 'Involved') { echo " checked"; } ?>  name="accident_28" value="Involved">Involved&nbsp;&nbsp;
                    <input type="radio" <?php if ($accident[28] == 'Vehicle') { echo " checked"; } ?>  name="accident_28" value="Vehicle">Vehicle&nbsp;&nbsp;
					<input type="radio" <?php if ($accident[28] == 'Equipment') { echo " checked"; } ?>  name="accident_28" value="Equipment">Equipment&nbsp;&nbsp;
					<input type="radio" <?php if ($accident[28] == 'People') { echo " checked"; } ?>  name="accident_28" value="People">People&nbsp;&nbsp;
					<input type="radio" <?php if ($accident[28] == 'Other') { echo " checked"; } ?>  name="accident_28" value="Other">Other&nbsp;&nbsp;
                    <input type="text" name="accident_29" value="<?php echo $accident[29]; ?>" class="form-control" />
                    </div>
					</div>


			</div>
        </div>
    </div>
	<?php } ?>

	<?php if (strpos(','.$form_config.',', ',fields74,') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_damage" >
                    Damage<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

		<div id="collapse_damage" class="panel-collapse collapse">
		<div class="panel-body">

		<h4>(complete this section for damage incidents only)</h4>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Damage to</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($accident[30] == 'Equipment') { echo " checked"; } ?>  name="accident_30" value="Equipment">Equipment&nbsp;&nbsp;
                    <input type="radio" <?php if ($accident[30] == 'Property') { echo " checked"; } ?>  name="accident_30" value="Property">Property&nbsp;&nbsp;
					<input type="radio" <?php if ($accident[30] == 'Other') { echo " checked"; } ?>  name="accident_30" value="Other">Other&nbsp;&nbsp;
                    <input type="text" name="accident_31" value="<?php echo $accident[31]; ?>" class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">What was damaged</label>
                    <div class="col-sm-8">
                    <input type="text" name="accident_32" value="<?php echo $accident[32]; ?>" class="form-control" />
                    </div>
                    </div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Object inflicting damage</label>
                    <div class="col-sm-8">
                    <input type="text" name="accident_33" value="<?php echo $accident[33]; ?>" class="form-control" />
                    </div>
                    </div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Extent of damage</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($accident[34] == 'Minimal') { echo " checked"; } ?>  name="accident_34" value="Minimal">Minimal&nbsp;&nbsp;
                    <input type="radio" <?php if ($accident[34] == 'Significant') { echo " checked"; } ?>  name="accident_34" value="Significant">Significant&nbsp;&nbsp;
					<input type="radio" <?php if ($accident[34] == 'Extensive') { echo " checked"; } ?>  name="accident_34" value="Extensive">Extensive&nbsp;&nbsp;
					<input type="radio" <?php if ($accident[34] == 'Object destroyed') { echo " checked"; } ?>  name="accident_34" value="Object destroyed">Object destroyed&nbsp;&nbsp;
                    <input type="text" name="accident_35" value="<?php echo $accident[35]; ?>" class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Estimated cost of repairs or replacement? $</label>
                    <div class="col-sm-8">
                    <input type="text" name="accident_36" value="<?php echo $accident[36]; ?>" class="form-control" />
                    </div>
                    </div>


			</div>
        </div>
    </div>
	<?php } ?>

	<?php if (strpos(','.$form_config.',', ',fields4,') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info1" >
                    Investigation<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info1" class="panel-collapse collapse">
            <div class="panel-body">

					<?php if (strpos(','.$form_config.',', ',fields4,') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Injury</label>
                        <div class="col-sm-8">
                        <input type="checkbox" <?php if (strpos(','.$fields.',', ',fields4,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields4"><input name="fields_value_4" type="text" value="<?php echo $fields_value[4]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>
					<?php if (strpos(','.$form_config.',', ',fields5,') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Illness</label>
                        <div class="col-sm-8">
                        <input type="checkbox" <?php if (strpos(','.$fields.',', ',fields5,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields5"><input name="fields_value_5" type="text" value="<?php echo $fields_value[5]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>
					<?php if (strpos(','.$form_config.',', ',fields6,') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Lost Time</label>
                        <div class="col-sm-8">
                        <input type="checkbox" <?php if (strpos(','.$fields.',', ',fields6,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields6"><input name="fields_value_6" type="text" value="<?php echo $fields_value[6]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>
					<?php if (strpos(','.$form_config.',', ',fields7,') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Property Damage</label>
                        <div class="col-sm-8">
                        <input type="checkbox" <?php if (strpos(','.$fields.',', ',fields7,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields7"><input name="fields_value_7" type="text" value="<?php echo $fields_value[7]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>
					<?php if (strpos(','.$form_config.',', ',fields8,') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Fire</label>
                        <div class="col-sm-8">
                        <input type="checkbox" <?php if (strpos(','.$fields.',', ',fields8,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields8"><input name="fields_value_8" type="text" value="<?php echo $fields_value[8]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>
					<?php if (strpos(','.$form_config.',', ',fields9,') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Environmental Incident</label>
                        <div class="col-sm-8">
                        <input type="checkbox" <?php if (strpos(','.$fields.',', ',fields9,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields9"><input name="fields_value_9" type="text" value="<?php echo $fields_value[9]; ?>" class="form-control" />
                        </div>
                    </div>
					<?php } ?>

			</div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos($form_config, ','."fields10".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info2" >
                    Person Involved<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info2" class="panel-collapse collapse">
            <div class="panel-body">

			    <?php if (strpos($form_config, ','."fields10".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Person In Charge:</label>
                    <div class="col-sm-8">
                        <input type="text" name="person_in_charge" value="<?php echo $person_in_charge; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields11".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Reported By:</label>
                    <div class="col-sm-8">
                        <input type="text" name="reporter" value="<?php echo $reporter; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields12".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Reported To:</label>
                    <div class="col-sm-8">
                        <input type="text" name="reported_to" value="<?php echo $reported_to; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields13".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Date Reported:</label>
                    <div class="col-sm-8">
                        <input type="text" name="date_reported" value="<?php echo $date_reported; ?>" class="datepicker" />
                    </div>
                  </div>
                <?php } ?>

			</div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields14,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info4" >
                    Description of Incident<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info4" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Description of Incident:</label>
                    <div class="col-sm-8">
                      <textarea name="description_of_incident" rows="5" cols="50" class="form-control"><?php echo $description_of_incident; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos(','.$form_config.',', ',fields15,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info5" >
                    Direct Cause of Incident<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info5" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Direct Cause of Incident:</label>
                    <div class="col-sm-8">
                      <textarea name="direct_cause_of_incident" rows="5" cols="50" class="form-control"><?php echo $direct_cause_of_incident; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos(','.$form_config.',', ',fields16,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info6" >
                    Contributing Factor<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info6" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Contributing Factor:</label>
                    <div class="col-sm-8">
                      <textarea name="contributing_factor" rows="5" cols="50" class="form-control"><?php echo $contributing_factor; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos(','.$form_config.',', ',fields16,') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info7" >
                    Person Having Control Over the Cause<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info7" class="panel-collapse collapse">
            <div class="panel-body">

			<?php if (strpos($form_config, ','."fields17".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Person Having Control Over the Cause:</label>
                    <div class="col-sm-8">
                        <input type="text" name="over_the_cause" value="<?php echo $over_the_cause; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

			</div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos(','.$form_config.',', ',fields18,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info8" >
                    Immediate Corrective Action Required<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info8" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Immediate Corrective Action Required:</label>
                    <div class="col-sm-8">
                      <textarea name="imm_act_req" rows="5" cols="50" class="form-control"><?php echo $imm_act_req; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos(','.$form_config.',', ',fields19,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info9" >
                    Long Term Corrective Action Required<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info9" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Long Term Corrective Action Required:</label>
                    <div class="col-sm-8">
                      <textarea name="ltm_act_req" rows="5" cols="50" class="form-control"><?php echo $ltm_act_req; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos(','.$form_config.',', ',fields19,') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info10" >
                    Assignment<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info10" class="panel-collapse collapse">
            <div class="panel-body">

			    <?php if (strpos($form_config, ','."fields20".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Immediate Corrective Action Assigned To:</label>
                    <div class="col-sm-8">
                        <input type="text" name="immidiate_correcctive_act_req" value="<?php echo $immidiate_correcctive_act_req; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields21".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Date Complete:</label>
                    <div class="col-sm-8">
                        <input type="text" name="immi_date_comp" value="<?php echo $immi_date_comp; ?>" class="datepicker" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields22".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Long Term Corrective Action Assigned To:</label>
                    <div class="col-sm-8">
                        <input type="text" name="long_trm_act_assign" value="<?php echo $long_trm_act_assign; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields23".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Date Complete:</label>
                    <div class="col-sm-8">
                        <input type="text" name="long_term_date" value="<?php echo $long_term_date; ?>" class="datepicker" />
                    </div>
                  </div>
                <?php } ?>

			</div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos(','.$form_config.',', ',fields24,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info11" >
                    Diagram of Scene<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info11" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Diagram of Scene:</label>
                    <div class="col-sm-8">
                      <textarea name="dia_scene" rows="5" cols="50" class="form-control"><?php echo $dia_scene; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos($form_config, ','."fields40".',') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_injured" >
                    Injured Employee Information<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_injured" class="panel-collapse collapse">
            <div class="panel-body">


				<?php if (strpos($form_config, ','."fields40".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Name Of Injured Employee</label>
                        <div class="col-sm-8">
                            <input type="text" name="incident_17" value="<?php echo $incident[17]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields41".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Occupation</label>
                        <div class="col-sm-8">
                            <input type="text" name="incident_18" value="<?php echo $incident[18]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields42".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Employee Address</label>
                        <div class="col-sm-8">
                            <input type="text" name="incident_19" value="<?php echo $incident[19]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields43".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Date Of Birth</label>
                        <div class="col-sm-8">
                            <input type="text" name="incident_20" value="<?php echo $incident[20]; ?>" class="datepicker" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields44".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Nature Of The Injury</label>
                        <div class="col-sm-8">
                            <input type="text" name="incident_21" value="<?php echo $incident[21]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields45".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Body Part</label>
                        <div class="col-sm-8">
							<input type="radio" <?php if ($incident[23] == 'Right') { echo " checked"; } ?>  name="incident_23" value="Right">Right&nbsp;&nbsp;
							<input type="radio" <?php if ($incident[23] == 'Left') { echo " checked"; } ?>  name="incident_23" value="Left">Left&nbsp;&nbsp;
                            <input type="text" name="incident_22" value="<?php echo $incident[22]; ?>" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Body Part Detail</label>
                        <div class="col-sm-8">
							<input type="text" name="incident_24" value="<?php echo $incident[24]; ?>" class="form-control" />
                        </div>
                    </div>

                <?php } ?>

				<?php if (strpos($form_config, ','."fields46".',') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Did This Aggravate A Previous Injury?</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($incident[25] == 'Yes') { echo " checked"; } ?>  name="incident_25" value="Yes">Yes&nbsp;&nbsp;
                    <input type="radio" <?php if ($incident[25] == 'No') { echo " checked"; } ?>  name="incident_25" value="No">No&nbsp;&nbsp;
                    <input type="text" name="incident_26" value="<?php echo $incident[26]; ?>" class="form-control" />
                    </div>
					</div>
				<?php } ?>

				<?php if (strpos($form_config, ','."fields47".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Nature Of The Injury</label>
                        <div class="col-sm-8">
                            <input type="text" name="incident_27" value="<?php echo $incident[27]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields48".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">First Aid</label>
                        <div class="col-sm-8">
                            <input type="text" name="incident_28" value="<?php echo $incident[28]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields49".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">First Aid Rendered By</label>
                        <div class="col-sm-8">
                            <input type="text" name="incident_29" value="<?php echo $incident[29]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields50".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Medical Treatment</label>
                        <div class="col-sm-8">
                            <input type="text" name="incident_30" value="<?php echo $incident[30]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields51".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Treatment Rendered By</label>
                        <div class="col-sm-8">
                            <input type="text" name="incident_31" value="<?php echo $incident[31]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields52".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Health Care Number</label>
                        <div class="col-sm-8">
                            <input type="text" name="incident_32" value="<?php echo $incident[32]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields53".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">SIN #</label>
                        <div class="col-sm-8">
                            <input type="text" name="incident_33" value="<?php echo $incident[33]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields54".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Home Phone</label>
                        <div class="col-sm-8">
                            <input type="text" name="incident_34" value="<?php echo $incident[34]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields55".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Object, Equipment, Or Substance Inflicting Injury</label>
                        <div class="col-sm-8">
                            <input type="text" name="incident_35" value="<?php echo $incident[35]; ?>" class="form-control" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields56".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="business_street" class="col-sm-4 control-label">Date Injured Worker Commenced Employment</label>
                        <div class="col-sm-8">
                            <input type="text" name="incident_36" value="<?php echo $incident[36]; ?>" class="datepicker" />
                        </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields57".',') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Was A Tailgate Meeting Held Prior To The Job?</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($incident[37] == 'Yes') { echo " checked"; } ?>  name="incident_37" value="Yes">Yes&nbsp;&nbsp;
                    <input type="radio" <?php if ($incident[37] == 'No') { echo " checked"; } ?>  name="incident_37" value="No">No&nbsp;&nbsp;
                    <input type="text" name="incident_38" value="<?php echo $incident[38]; ?>" class="form-control" />
                    </div>
					</div>
				<?php } ?>

				<?php if (strpos(','.$form_config.',', ',fields58,') !== FALSE) { ?>
				<h4>Personal Protective Equipment Worn At Time Of Injury</h4>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Hard Hat</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($incident[39]=='Hard Hat') { echo " checked"; } ?>  name="incident_39" value="Hard Hat"><input name="incident_40" type="text" value="<?php echo $incident[40]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Coveralls</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($incident[41]=='Coveralls') { echo " checked"; } ?>  name="incident_41" value="Coveralls"><input name="incident_42" type="text" value="<?php echo $incident[42]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Gloves</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($incident[43]=='Gloves') { echo " checked"; } ?>  name="incident_43" value="Gloves"><input name="incident_44" type="text" value="<?php echo $incident[44]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Safety Boots</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($incident[45]=='Safety Boots') { echo " checked"; } ?>  name="incident_45" value="Safety Boots"><input name="incident_46" type="text" value="<?php echo $incident[46]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Safety Glasses</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($incident[47]=='Safety Glasses') { echo " checked"; } ?>  name="incident_47" value="Safety Glasses"><input name="incident_48" type="text" value="<?php echo $incident[48]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Mono goggles</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($incident[49]=='Mono goggles') { echo " checked"; } ?>  name="incident_49" value="Mono goggles"><input name="incident_50" type="text" value="<?php echo $incident[50]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Face shield</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($incident[51]=='Face shield') { echo " checked"; } ?>  name="incident_51" value="Face shield"><input name="incident_52" type="text" value="<?php echo $incident[52]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Other</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($incident[53]=='Other') { echo " checked"; } ?>  name="incident_53" value="Other"><input name="incident_54" type="text" value="<?php echo $incident[54]; ?>" class="form-control" />
                    </div>
                </div>
				<?php } ?>

			</div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos(','.$form_config.',', ',fields59,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_causal" >
                    Analysis - Causal Factors<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_causal" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Causal Factors(Factors that, if corrected, would have prevented this incident from occurring or would have significantly mitigated its consequences.)</label>
                    <div class="col-sm-8">
                      <textarea name="desc" rows="5" cols="50" class="form-control"><?php echo $desc; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos(','.$form_config.',', ',fields60,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_root" >
                    Analysis - Root Cause<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_root" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Root Cause(What is / are the most basic cause(s) under work direction, training, management systems, etc.?)</label>
                    <div class="col-sm-8">
                      <textarea name="desc1" rows="5" cols="50" class="form-control"><?php echo $desc1; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos($form_config, ','."fields61".',') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_prevention" >
                    Prevention<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_prevention" class="panel-collapse collapse">
            <div class="panel-body">

                <?php
                $all_task_each = explode('**##**',$all_task);

                $total_count = mb_substr_count($all_task,'**##**');
                if($total_count > 0) {
                    echo "<table class='table table-bordered'>";
                    echo "<tr class='hidden-xs hidden-sm'>
                    <th>What action or recommendations are made to prevent recurrence?</th>
                    <th>Date</th>
                    <th>Action By</th>
                    <th>Complete</th>";
                }
                for($client_loop=0; $client_loop<=$total_count; $client_loop++) {
                    $task_item = explode('**',$all_task_each[$client_loop]);
                    $task = $task_item[0];
                    $hazard = $task_item[1];
                    $level = $task_item[2];
                    $plan = $task_item[3];
                    if($task != '') {
                        echo '<tr>';
                        echo '<td data-title="Email">' . $task . '</td>';
                        echo '<td data-title="Email">' . $hazard . '</td>';
                        echo '<td data-title="Email">' . $level . '</td>';
                        echo '<td data-title="Email">' . $plan . '</td>';
                        echo '</tr>';
                    }
                }
                echo '</table>';
                ?>
                <div class="additional_hazard clearfix">
                    <div class="row">
                        <div class="col-md-5 col-sm-6 col-xs-6 padded">
                            <p>What action or recommendations are made to prevent recurrence?</p>
                            <input type="text" name="task[]" class="task_list"/>
                        </div>
                        <div class="col-md-2 col-sm-6 col-xs-6 padded">
                            <p>Date</p>
                            <input type="text" name="hazard[]" class="task_list"/>
                        </div>
                        <div class="col-md-2 col-sm-6 col-xs-6 padded">
                            <p>Action By</p>
                            <input type="text" name="level[]" class="task_list"/>
                        </div>
                        <div class="col-md-2 col-sm-6 col-xs-6 padded">
                            <p>Complete</p>
                            <select name="plan[]" class="task_list">
                                <option value=""></option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>

                    </div>
                </div>
                <div id="add_here_new_hazard"></div>
                <div class="form-group triple-gapped clearfix">
                    <div class="col-sm-offset-4 col-sm-8">
                        <button id="add_row_hazard" class="btn brand-btn pull-left">Add More</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos(','.$form_config.',', ',fields62,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_cost" >
                    Costs<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_cost" class="panel-collapse collapse">
            <div class="panel-body">

			    <h5>For Vehicle / Equipment / Property Damage</h5>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Estimated</label>
                    <div class="col-sm-8">
                        <input type="text" name="incident_55" value="<?php echo $incident[55]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Actual</label>
                    <div class="col-sm-8">
                        <input type="text" name="incident_56" value="<?php echo $incident[56]; ?>" class="form-control" />
                    </div>
                </div>

				<h5>NOTE:Costs Are To Include Materials And Labour.</h5>

           </div>
        </div>
    </div>
	<?php } ?>

	<?php if (strpos(','.$form_config.',', ',fields63,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_classi" >
                    Injury Classification<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_classi" class="panel-collapse collapse">
            <div class="panel-body">

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Injury Classification</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($incident[57] == 'Report Only') { echo " checked"; } ?>  name="incident_57" value="Report Only">Report Only&nbsp;&nbsp;
                    <input type="radio" <?php if ($incident[57] == 'First Aid') { echo " checked"; } ?>  name="incident_57" value="First Aid">First Aid&nbsp;&nbsp;
					<input type="radio" <?php if ($incident[57] == 'Medical Aid') { echo " checked"; } ?>  name="incident_57" value="Medical Aid">Medical Aid&nbsp;&nbsp;
					<input type="radio" <?php if ($incident[57] == 'Restricted Work') { echo " checked"; } ?>  name="incident_57" value="Restricted Work">Restricted Work&nbsp;&nbsp;
					<input type="radio" <?php if ($incident[57] == 'Lost Time') { echo " checked"; } ?>  name="incident_57" value="Lost Time">Lost Time&nbsp;&nbsp;
                    <input type="text" name="incident_58" value="<?php echo $incident[58]; ?>" class="form-control" />
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

               <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Date:</label>
                <div class="col-sm-8">
                    <input name="staffcheck_<?php echo $assign_staff_id;?>[]" type="text" class="datepicker" />
                </div>
              </div>

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