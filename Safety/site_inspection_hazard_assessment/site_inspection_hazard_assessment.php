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
$project_name = '';
$employer = '';
$project_number = '';
$purpose = '';
$purpose_other = '';
$fields_value = '';
$fields_option = '';
$overall_rating = '';
$additional_comment = '';
$date_comp = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];

    echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_site_inspection_hazard_assessment WHERE fieldlevelriskid='$formid'"));

	$today_date = $get_field_level['today_date'];
    $project_name = $get_field_level['project_name'];
    $employer = $get_field_level['employer'];
    $project_number = $get_field_level['project_number'];
    $contactid = $get_field_level['contactid'];
	$purpose = $get_field_level['purpose'];
    $purpose_other = $get_field_level['purpose_other'];
    $fields_value = explode('**FFM**', $get_field_level['fields_value']);
    $field_rating = explode(',', $get_field_level['field_rating']);
    $fields = $get_field_level['fields'];
    $overall_rating = $get_field_level['overall_rating'];
    $additional_comment = $get_field_level['additional_comment'];
    $date_comp = $get_field_level['date_comp'];
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
                    <label for="business_street" class="col-sm-4 control-label">Date/Time</label>
                    <div class="col-sm-38">
                        <input type="text" name="today_date" value="<?php echo $today_date; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Project Name</label>
                    <div class="col-sm-38">
                        <input type="text" name="project_name" value="<?php echo $project_name; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Employer</label>
                    <div class="col-sm-38">
                        <input type="text" name="employer" value="<?php echo $employer; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Project Number</label>
                    <div class="col-sm-38">
                        <input type="text" name="project_number" value="<?php echo $project_number; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Inspection By</label>
                    <div class="col-sm-38">
                        <input type="text" name="contactid" value="<?php echo $contactid; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Purpose</label>
                    <div class="col-sm-38">
					<input type="radio" <?php if ($purpose == 'Inspection') { echo " checked"; } ?>  name="purpose" value="Inspection">Inspection
					<input type="radio" <?php if ($purpose == 'Re-Inspection') { echo " checked"; } ?>  name="purpose" value="Re-Inspection">Re-Inspection
					<input type="radio" <?php if ($purpose == 'Hazard Assessment') { echo " checked"; } ?> name="purpose" value="Hazard Assessment">Hazard Assessment
					<input type="radio" <?php if ($purpose == 'Other') { echo " checked"; } ?> name="purpose" value="Other">Other
				</div>
                  </div>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label"></label>
                    <div class="col-sm-38">
                    <input name="purpose_other" type="text" style="width: 33%;" value="<?php echo $purpose_other; ?>" class="form-control" />
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
                    Instruction<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info1" class="panel-collapse collapse">
            <div class="panel-body">

			<?php if (strpos($form_config, ','."fields7".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Project Management Compliance </label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field7_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_7" value="field7_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field7_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_7" value="field7_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_7" type="text" style="width: 33%;" value="<?php echo $fields_value[7]; ?>" class="form-control" />
                <select name="field_rating_7" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[7]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[7]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[7]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[7]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields8".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">O.H.S. Committee Minutes </label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field8_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_8" value="field8_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field8_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_8" value="field8_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_8" type="text" style="width: 33%;" value="<?php echo $fields_value[8]; ?>" class="form-control" />
                <select name="field_rating_8" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[8]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[8]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[8]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[8]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields9".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Supervision</label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field9_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_9" value="field9_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field9_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_9" value="field9_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_9" type="text" style="width: 33%;" value="<?php echo $fields_value[9]; ?>" class="form-control" />
                <select name="field_rating_9" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[9]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[9]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[9]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[9]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields10".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Safe Work Practices & Procedures </label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field10_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_10" value="field10_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field10_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_10" value="field10_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_10" type="text" style="width: 33%;" value="<?php echo $fields_value[10]; ?>" class="form-control" />
                <select name="field_rating_10" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[10]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[10]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[10]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[10]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields11".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Training of Workers / Records </label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field11_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_11" value="field11_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field11_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_11" value="field11_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_11" type="text" style="width: 33%;" value="<?php echo $fields_value[11]; ?>" class="form-control" />
                <select name="field_rating_11" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[11]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[11]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[11]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[11]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields12".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Postings / Safety Manuals / OH&S Regulations </label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field12_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_12" value="field12_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field12_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_12" value="field12_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_12" type="text" style="width: 33%;" value="<?php echo $fields_value[12]; ?>" class="form-control" />
                <select name="field_rating_12" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[12]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[12]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[12]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[12]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields13".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Personal Protection Equipment </label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field13_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_13" value="field13_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field13_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_13" value="field13_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_13" type="text" style="width: 33%;" value="<?php echo $fields_value[13]; ?>" class="form-control" />
                <select name="field_rating_13" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[13]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[13]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[13]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[13]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields14".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Tool Box Meetings / Minutes </label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field14_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_14" value="field14_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field14_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_14" value="field14_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_14" type="text" style="width: 33%;" value="<?php echo $fields_value[14]; ?>" class="form-control" />
                <select name="field_rating_14" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[14]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[14]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[14]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[14]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields15".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Ventilation / Environmental Conditions </label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field15_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_15" value="field15_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field15_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_15" value="field15_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_15" type="text" style="width: 33%;" value="<?php echo $fields_value[15]; ?>" class="form-control" />
                <select name="field_rating_15" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[15]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[15]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[15]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[15]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields16".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Respiratory Protection </label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field16_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_16" value="field16_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field16_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_16" value="field16_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_16" type="text" style="width: 33%;" value="<?php echo $fields_value[16]; ?>" class="form-control" />
                <select name="field_rating_16" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[16]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[16]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[16]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[16]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields17".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Lighting</label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field17_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_17" value="field17_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field17_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_17" value="field17_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_17" type="text" style="width: 33%;" value="<?php echo $fields_value[17]; ?>" class="form-control" />
                <select name="field_rating_17" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[17]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[17]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[17]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[17]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields18".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Toilet, Washing, Drinking & Eating Facilities </label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field18_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_18" value="field18_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field18_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_18" value="field18_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_18" type="text" style="width: 33%;" value="<?php echo $fields_value[18]; ?>" class="form-control" />
                <select name="field_rating_18" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[18]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[18]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[18]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[18]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields19".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Security / Fire Prevention </label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field19_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_19" value="field19_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field19_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_19" value="field19_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_19" type="text" style="width: 33%;" value="<?php echo $fields_value[19]; ?>" class="form-control" />
                <select name="field_rating_19" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[19]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[19]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[19]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[19]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields20".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">First Aid / Emergency Protocols </label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field20_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_20" value="field20_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field20_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_20" value="field20_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_20" type="text" style="width: 33%;" value="<?php echo $fields_value[20]; ?>" class="form-control" />
                <select name="field_rating_20" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[20]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[20]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[20]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[20]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields21".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Equipment Condition / Maintenance </label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field21_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_21" value="field21_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field21_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_21" value="field21_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_21" type="text" style="width: 33%;" value="<?php echo $fields_value[21]; ?>" class="form-control" />
                <select name="field_rating_21" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[21]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[21]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[21]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[21]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields22".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Inspections</label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field22_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_22" value="field22_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field22_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_22" value="field22_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_22" type="text" style="width: 33%;" value="<?php echo $fields_value[22]; ?>" class="form-control" />
                <select name="field_rating_22" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[22]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[22]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[22]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[22]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields23".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Machine Guarding </label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field23_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_23" value="field23_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field23_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_23" value="field23_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_23" type="text" style="width: 33%;" value="<?php echo $fields_value[23]; ?>" class="form-control" />
                <select name="field_rating_23" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[23]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[23]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[23]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[23]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields24".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Signs / Barricades </label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field24_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_24" value="field24_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field24_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_24" value="field24_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_24" type="text" style="width: 33%;" value="<?php echo $fields_value[24]; ?>" class="form-control" />
                <select name="field_rating_24" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[24]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[24]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[24]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[24]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields25".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Handling of Loads </label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field25_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_25" value="field25_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field25_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_25" value="field25_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_25" type="text" style="width: 33%;" value="<?php echo $fields_value[25]; ?>" class="form-control" />
                <select name="field_rating_25" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[25]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[25]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[25]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[25]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields26".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Access / Egress </label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field26_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_26" value="field26_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field26_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_26" value="field26_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_26" type="text" style="width: 33%;" value="<?php echo $fields_value[26]; ?>" class="form-control" />
                <select name="field_rating_26" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[26]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[26]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[26]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[26]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields27".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Chemical Substances / W.H.M.I.S. / M.S.D.S. / Hazard Comm. Program </label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field27_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_27" value="field27_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field27_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_27" value="field27_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_27" type="text" style="width: 33%;" value="<?php echo $fields_value[27]; ?>" class="form-control" />
                <select name="field_rating_27" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[27]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[27]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[27]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[27]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields28".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Scaffolds</label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field28_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_28" value="field28_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field28_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_28" value="field28_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_28" type="text" style="width: 33%;" value="<?php echo $fields_value[28]; ?>" class="form-control" />
                <select name="field_rating_28" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[28]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[28]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[28]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[28]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields29".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Fall Protection </label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field29_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_29" value="field29_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field29_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_29" value="field29_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_29" type="text" style="width: 33%;" value="<?php echo $fields_value[29]; ?>" class="form-control" />
                <select name="field_rating_29" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[29]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[29]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[29]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[29]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields30".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Noise Awareness / Protection </label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field30_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_30" value="field30_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field30_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_30" value="field30_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_30" type="text" style="width: 33%;" value="<?php echo $fields_value[30]; ?>" class="form-control" />
                <select name="field_rating_30" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[30]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[30]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[30]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[30]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields31".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Work Clothing (& Accommodation if applicable) </label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field31_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_31" value="field31_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field31_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_31" value="field31_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_31" type="text" style="width: 33%;" value="<?php echo $fields_value[31]; ?>" class="form-control" />
                <select name="field_rating_31" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[31]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[31]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[31]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[31]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields32".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Housekeeping</label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field32_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_32" value="field32_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field32_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_32" value="field32_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_32" type="text" style="width: 33%;" value="<?php echo $fields_value[32]; ?>" class="form-control" />
                <select name="field_rating_32" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[32]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[32]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[32]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[32]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields33".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Hoisting & Rigging </label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field33_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_33" value="field33_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field33_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_33" value="field33_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_33" type="text" style="width: 33%;" value="<?php echo $fields_value[33]; ?>" class="form-control" />
                <select name="field_rating_33" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[33]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[33]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[33]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[33]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields34".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Accident Reporting / Investigations </label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field34_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_34" value="field34_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field34_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_34" value="field34_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_34" type="text" style="width: 33%;" value="<?php echo $fields_value[34]; ?>" class="form-control" />
                <select name="field_rating_34" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[34]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[34]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[34]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[34]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields35".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Fire Extinguishers </label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field35_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_35" value="field35_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field35_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_35" value="field35_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_35" type="text" style="width: 33%;" value="<?php echo $fields_value[35]; ?>" class="form-control" />
                <select name="field_rating_35" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[35]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[35]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[35]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[35]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields36".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">BBS</label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field36_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_36" value="field36_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field36_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_36" value="field36_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_36" type="text" style="width: 33%;" value="<?php echo $fields_value[36]; ?>" class="form-control" />
                <select name="field_rating_36" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[36]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[36]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[36]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[36]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields37".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Orientations</label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field37_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_37" value="field37_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field37_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_37" value="field37_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_37" type="text" style="width: 33%;" value="<?php echo $fields_value[37]; ?>" class="form-control" />
                <select name="field_rating_37" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[37]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[37]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[37]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[37]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields38".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Hazard Assessments </label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field38_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_38" value="field38_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field38_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_38" value="field38_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_38" type="text" style="width: 33%;" value="<?php echo $fields_value[38]; ?>" class="form-control" />
                <select name="field_rating_38" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[38]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[38]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[38]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[38]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields39".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Others</label>
                    <div class="col-sm-38">

				<input type="radio" <?php if (strpos(','.$fields.',', ',field39_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_39" value="field39_acceptable">Acceptable
				<input type="radio" <?php if (strpos(','.$fields.',', ',field39_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_39" value="field39_unacceptable">Unacceptable
				&nbsp;&nbsp;<input name="fields_value_39" type="text" style="width: 33%;" value="<?php echo $fields_value[39]; ?>" class="form-control" />
                <select name="field_rating_39" class="form-control" style="width: 13%;">
                    <option value=""></option>
                    <option <?php if ($field_rating[39]=='Imminent Danger') echo 'selected="selected"';?> value="Imminent Danger">Imminent Danger</option>
                    <option <?php if ($field_rating[39]=='Serious') echo 'selected="selected"';?> value="Serious">Serious</option>
                    <option <?php if ($field_rating[39]=='Minor') echo 'selected="selected"';?> value="Minor">Minor</option>
                    <option <?php if ($field_rating[39]=='Acceptable') echo 'selected="selected"';?> value="Acceptable">Acceptable</option>
                </select>
                    </div>
                </div>
            <?php } ?>

			</div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info2" >
                    Overall Rating<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info2" class="panel-collapse collapse">
            <div class="panel-body">
				<?php if (strpos($form_config, ','."fields40".',') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label"></label>
                    <div class="col-sm-38">
					<input type="radio" <?php if ($overall_rating == 'Satisfactory') { echo " checked"; } ?>  name="overall_rating" value="Satisfactory">Satisfactory&nbsp;&nbsp;
					<input type="radio" <?php if ($overall_rating == 'Corrective Action Report Required Within 3 days ') { echo " checked"; } ?> name="overall_rating" value="Corrective Action Report Required Within 3 days ">Corrective Action Report Required Within 3 days
					</div>
					</div>
                <?php } ?>
			</div>
        </div>
    </div>

	<?php if (strpos(','.$form_config.',', ',fields41,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info3" >
                    Additional Comment<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info3" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Additional Comment</label>
                    <div class="col-sm-8">
                      <textarea name="additional_comment" rows="5" cols="50" class="form-control"><?php echo $additional_comment; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
	<?php } ?>

    <?php if (strpos($form_config, ','."fields42".',') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info4" >
                    Date Completed<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info4" class="panel-collapse collapse">
            <div class="panel-body">
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Date Completed:</label>
                    <div class="col-sm-8">
                        <input type="text" name="date_comp" value="<?php echo $date_comp; ?>" class="datepicker" />
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