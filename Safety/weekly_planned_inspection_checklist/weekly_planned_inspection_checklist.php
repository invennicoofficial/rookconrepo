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
$area_inspected = '';
$permit_type = '';
$fields = '';
$fields_value = '';
$permit_comments = '';
$personal_comments = '';
$scaffold_comments = '';
$stbsh_comment = '';
$housekeeping_comments = '';
$tool_comments = '';
$mobile_equipment_comments = '';
$miscellaneous_comments = '';
$general_comments = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];

    echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_weekly_planned_inspection_checklist WHERE fieldlevelriskid='$formid'"));

    $today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
    $area_inspected = $get_field_level['area_inspected'];
    $permit_type = $get_field_level['permit_type'];
    $fields = $get_field_level['fields'];
    $fields_value = explode('**FFM**', $get_field_level['fields_value']);
    $permit_comments = $get_field_level['permit_comments'];
    $personal_comments = $get_field_level['personal_comments'];
    $scaffold_comments = $get_field_level['scaffold_comments'];
    $stbsh_comment = $get_field_level['stbsh_comment'];
    $housekeeping_comments = $get_field_level['housekeeping_comments'];
    $tool_comments = $get_field_level['tool_comments'];
    $mobile_equipment_comments = $get_field_level['mobile_equipment_comments'];
    $miscellaneous_comments = $get_field_level['miscellaneous_comments'];
    $general_comments = $get_field_level['general_comments'];
    
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
                <label for="business_street" class="col-sm-4 control-label">Date:</label>
                <div class="col-sm-8">
                <input type="text" name="today_date" value="<?php echo $today_date; ?>" class="form-control" />
                </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Contact Id:</label>
                <div class="col-sm-8">
                <input type="text" name="contactid" value="<?php echo $contactid; ?>" class="form-control" />
                </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Area Inspected:</label>
                <div class="col-sm-8">
                <input type="text" name="area_inspected" value="<?php echo $area_inspected; ?>" class="form-control" />
                </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Permit type:</label>
                <div class="col-sm-8">
				<input type="radio" <?php if ($permit_type == 'Hot') { echo " checked"; } ?>  name="permit_type" value="Hot">Hot
				<input type="radio" <?php if ($permit_type == 'Cold') { echo " checked"; } ?>  name="permit_type" value="Cold">Cold
				<input type="radio" <?php if ($permit_type == 'Confined Space') { echo " checked"; } ?> name="permit_type" value="Confined Space">Confined Space
                </div>
                </div>
			<?php } ?>

			</div>
        </div>
    </div>

	<?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info1" >
                    Permit Details<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info1" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">
				Does the permit adequately describe the work and the associated area hazards? </label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field1_yes,') !== FALSE) { echo " checked"; } ?>  name="fields_option_1" value="field1_yes">Yes
				<input type="radio" <?php if (strpos(','.$fields.',', ',field1_no,') !== FALSE) { echo " checked"; } ?>  name="fields_option_1" value="field1_no">No&nbsp;&nbsp;<input name="fields_value_1" type="text" value="<?php echo $fields_value[1]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

				Are the appropriate controls in place for those hazards</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field2_yes,') !== FALSE) { echo " checked"; } ?>  name="fields_option_2" value="field2_yes">Yes
				<input type="radio" <?php if (strpos(','.$fields.',', ',field2_no,') !== FALSE) { echo " checked"; } ?>  name="fields_option_2" value="field2_no">No&nbsp;&nbsp;<input name="fields_value_2" type="text" value="<?php echo $fields_value[2]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

				Are permits posted or located at the job site?</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field3_yes,') !== FALSE) { echo " checked"; } ?>  name="fields_option_3" value="field3_yes">Yes
				<input type="radio" <?php if (strpos(','.$fields.',', ',field3_no,') !== FALSE) { echo " checked"; } ?>  name="fields_option_3" value="field3_no">No&nbsp;&nbsp;<input name="fields_value_3" type="text" value="<?php echo $fields_value[3]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

				Are the personnel using the specified PPE?</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field4_yes,') !== FALSE) { echo " checked"; } ?>  name="fields_option_4" value="field4_yes">Yes
				<input type="radio" <?php if (strpos(','.$fields.',', ',field4_no,') !== FALSE) { echo " checked"; } ?>  name="fields_option_4" value="field4_no">No&nbsp;&nbsp;<input name="fields_value_4" type="text" value="<?php echo $fields_value[4]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

				Is the specified fire protection readily accessible?</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field5_yes,') !== FALSE) { echo " checked"; } ?>  name="fields_option_5" value="field5_yes">Yes
				<input type="radio" <?php if (strpos(','.$fields.',', ',field5_no,') !== FALSE) { echo " checked"; } ?>  name="fields_option_5" value="field5_no">No&nbsp;&nbsp;<input name="fields_value_5" type="text" value="<?php echo $fields_value[5]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

				Does the number of personnel identified on the permit match the number working on the job?</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field6_yes,') !== FALSE) { echo " checked"; } ?>  name="fields_option_6" value="field6_yes">Yes
				<input type="radio" <?php if (strpos(','.$fields.',', ',field6_no,') !== FALSE) { echo " checked"; } ?>  name="fields_option_6" value="field6_no">No&nbsp;&nbsp;<input name="fields_value_6" type="text" value="<?php echo $fields_value[6]; ?>" class="form-control" />
                    </div>
                </div>

			</div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos(','.$form_config.',', ',fields6,') !== FALSE) { ?>
	<div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info2" >
                    Permit Comments<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info2" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Permit Comments:</label>
                    <div class="col-sm-8">
                      <textarea name="permit_comments" rows="5" cols="50" class="form-control"><?php echo $permit_comments; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos($form_config, ','."fields7".',') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info3" >
                    Personnel Details<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info3" class="panel-collapse collapse">
            <div class="panel-body">

			    <b>As a minimum, are personnel using the following PPE: </b>

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">
				Hardhat?</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field7_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_7" value="field7_na">N/A
				<input type="radio" <?php if (strpos(','.$fields.',', ',field7_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_7" value="field7_poor">Poor
				<input type="radio" <?php if (strpos(','.$fields.',', ',field7_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_7" value="field7_good">Good
				<input type="radio" <?php if (strpos(','.$fields.',', ',field7_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_7" value="field7_exe">Execellent
				&nbsp;&nbsp;<input name="fields_value_7" type="text" value="<?php echo $fields_value[7]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

				Safety glasses with side shields or goggles?</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field8_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_8" value="field8_na">N/A
				<input type="radio" <?php if (strpos(','.$fields.',', ',field8_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_8" value="field8_poor">Poor
				<input type="radio" <?php if (strpos(','.$fields.',', ',field8_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_8" value="field8_good">Good
				<input type="radio" <?php if (strpos(','.$fields.',', ',field8_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_8" value="field8_exe">Execellent
				&nbsp;&nbsp;<input name="fields_value_8" type="text" value="<?php echo $fields_value[8]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

				Hearing protection?</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field9_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_9" value="field9_na">N/A
				<input type="radio" <?php if (strpos(','.$fields.',', ',field9_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_9" value="field9_poor">Poor
				<input type="radio" <?php if (strpos(','.$fields.',', ',field9_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_9" value="field9_good">Good
				<input type="radio" <?php if (strpos(','.$fields.',', ',field9_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_9" value="field9_exe">Execellent
				&nbsp;&nbsp;<input name="fields_value_9" type="text" value="<?php echo $fields_value[9]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

				Steel-toed boots or shoes?</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field10_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_10" value="field10_na">N/A
				<input type="radio" <?php if (strpos(','.$fields.',', ',field10_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_10" value="field10_poor">Poor
				<input type="radio" <?php if (strpos(','.$fields.',', ',field10_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_10" value="field10_good">Good
				<input type="radio" <?php if (strpos(','.$fields.',', ',field10_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_10" value="field10_exe">Execellent
				&nbsp;&nbsp;<input name="fields_value_10" type="text" value="<?php echo $fields_value[10]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

				Long sleeves?</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field11_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_11" value="field11_na">N/A
				<input type="radio" <?php if (strpos(','.$fields.',', ',field11_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_11" value="field11_poor">Poor
				<input type="radio" <?php if (strpos(','.$fields.',', ',field11_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_11" value="field11_good">Good
				<input type="radio" <?php if (strpos(','.$fields.',', ',field11_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_11" value="field11_exe">Execellent
				&nbsp;&nbsp;<input name="fields_value_11" type="text" value="<?php echo $fields_value[11]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

				Gloves (appropriate for the task?)</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field12_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_12" value="field12_na">N/A
				<input type="radio" <?php if (strpos(','.$fields.',', ',field12_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_12" value="field12_poor">Poor
				<input type="radio" <?php if (strpos(','.$fields.',', ',field12_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_12" value="field12_good">Good
				<input type="radio" <?php if (strpos(','.$fields.',', ',field12_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_12" value="field12_exe">Execellent
				&nbsp;&nbsp;<input name="fields_value_12" type="text" value="<?php echo $fields_value[12]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

				Fire retardant clothing where required?</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field13_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_13" value="field13_na">N/A
				<input type="radio" <?php if (strpos(','.$fields.',', ',field13_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_13" value="field13_poor">Poor
				<input type="radio" <?php if (strpos(','.$fields.',', ',field13_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_13" value="field13_good">Good
				<input type="radio" <?php if (strpos(','.$fields.',', ',field13_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_13" value="field13_exe">Execellent
				&nbsp;&nbsp;<input name="fields_value_13" type="text" value="<?php echo $fields_value[13]; ?>" class="form-control" />
                    </div>
                </div>

				<b>Is specialized PPE required for the task and being used properly: </b>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">
				Fall Protection</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field14_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_14" value="field14_na">N/A
				<input type="radio" <?php if (strpos(','.$fields.',', ',field14_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_14" value="field14_poor">Poor
				<input type="radio" <?php if (strpos(','.$fields.',', ',field14_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_14" value="field14_good">Good
				<input type="radio" <?php if (strpos(','.$fields.',', ',field14_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_14" value="field14_exe">Execellent
				&nbsp;&nbsp;<input name="fields_value_14" type="text" value="<?php echo $fields_value[14]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

				Respiratory Protection</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field15_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_15" value="field15_na">N/A
				<input type="radio" <?php if (strpos(','.$fields.',', ',field15_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_15" value="field15_poor">Poor
				<input type="radio" <?php if (strpos(','.$fields.',', ',field15_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_15" value="field15_good">Good
				<input type="radio" <?php if (strpos(','.$fields.',', ',field15_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_15" value="field15_exe">Execellent
				&nbsp;&nbsp;<input name="fields_value_15" type="text" value="<?php echo $fields_value[15]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

				Other</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field16_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_16" value="field16_na">N/A
				<input type="radio" <?php if (strpos(','.$fields.',', ',field16_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_16" value="field16_poor">Poor
				<input type="radio" <?php if (strpos(','.$fields.',', ',field16_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_16" value="field16_good">Good
				<input type="radio" <?php if (strpos(','.$fields.',', ',field16_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_16" value="field16_exe">Execellent
				&nbsp;&nbsp;<input name="fields_value_16" type="text" value="<?php echo $fields_value[16]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

				Do personnel know where the nearest EMP and Assembly Area are?</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field17_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_17" value="field17_na">N/A
				<input type="radio" <?php if (strpos(','.$fields.',', ',field17_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_17" value="field17_poor">Poor
				<input type="radio" <?php if (strpos(','.$fields.',', ',field17_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_17" value="field17_good">Good
				<input type="radio" <?php if (strpos(','.$fields.',', ',field17_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_17" value="field17_exe">Execellent
				&nbsp;&nbsp;<input name="fields_value_17" type="text" value="<?php echo $fields_value[17]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

				Do personnel know whom to contact in the event of an emergency?</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field18_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_18" value="field18_na">N/A
				<input type="radio" <?php if (strpos(','.$fields.',', ',field18_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_18" value="field18_poor">Poor
				<input type="radio" <?php if (strpos(','.$fields.',', ',field18_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_18" value="field18_good">Good
				<input type="radio" <?php if (strpos(','.$fields.',', ',field18_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_18" value="field18_exe">Execellent
				&nbsp;&nbsp;<input name="fields_value_18" type="text" value="<?php echo $fields_value[18]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

				Do personnel know whom to contact to report a safety concern/issue?</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field19_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_19" value="field19_na">N/A
				<input type="radio" <?php if (strpos(','.$fields.',', ',field19_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_19" value="field19_poor">Poor
				<input type="radio" <?php if (strpos(','.$fields.',', ',field19_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_19" value="field19_good">Good
				<input type="radio" <?php if (strpos(','.$fields.',', ',field19_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_19" value="field19_exe">Execellent
				&nbsp;&nbsp;<input name="fields_value_19" type="text" value="<?php echo $fields_value[19]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

				Do personnel know where the procedure is located for the task they are performing?</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field20_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_20" value="field20_na">N/A
				<input type="radio" <?php if (strpos(','.$fields.',', ',field20_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_20" value="field20_poor">Poor
				<input type="radio" <?php if (strpos(','.$fields.',', ',field20_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_20" value="field20_good">Good
				<input type="radio" <?php if (strpos(','.$fields.',', ',field20_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_20" value="field20_exe">Execellent
				&nbsp;&nbsp;<input name="fields_value_20" type="text" value="<?php echo $fields_value[20]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

				Are personnel ensuring they are out of the line of fire - safe positions, no pinch points, not in danger of overreaching, falling, sliding, etc.</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field21_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_21" value="field21_na">N/A
				<input type="radio" <?php if (strpos(','.$fields.',', ',field21_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_21" value="field21_poor">Poor
				<input type="radio" <?php if (strpos(','.$fields.',', ',field21_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_21" value="field21_good">Good
				<input type="radio" <?php if (strpos(','.$fields.',', ',field21_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_21" value="field21_exe">Execellent
				&nbsp;&nbsp;<input name="fields_value_21" type="text" value="<?php echo $fields_value[21]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

				Are personnel focused on the task at hand - eyes and mind on tasks, good view of work</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field22_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_22" value="field22_na">N/A
				<input type="radio" <?php if (strpos(','.$fields.',', ',field22_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_22" value="field22_poor">Poor
				<input type="radio" <?php if (strpos(','.$fields.',', ',field22_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_22" value="field22_good">Good
				<input type="radio" <?php if (strpos(','.$fields.',', ',field22_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_22" value="field22_exe">Execellent
				&nbsp;&nbsp;<input name="fields_value_22" type="text" value="<?php echo $fields_value[22]; ?>" class="form-control" />
                    </div>
                </div>

			</div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields8,') !== FALSE) { ?>
	<div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info4" >
                    Personal Comments<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info4" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Personal Comments:</label>
                    <div class="col-sm-8">
                      <textarea name="personal_comments" rows="5" cols="50" class="form-control"><?php echo $personal_comments; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos($form_config, ','."fields9".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info5" >
                    Scaffold Details<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info5" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">
				Is an updated tag (21 days max.) in place at each entrance to a scaffold structure?</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field23_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_23" value="field23_na">N/A
				<input type="radio" <?php if (strpos(','.$fields.',', ',field23_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_23" value="field23_poor">Poor
				<input type="radio" <?php if (strpos(','.$fields.',', ',field23_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_23" value="field23_good">Good
				<input type="radio" <?php if (strpos(','.$fields.',', ',field23_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_23" value="field23_exe">Execellent
				&nbsp;&nbsp;<input name="fields_value_23" type="text" value="<?php echo $fields_value[23]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

				Are the scaffold platforms clear of tools, materials, debris, and other tripping hazards?</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field24_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_24" value="field24_na">N/A
				<input type="radio" <?php if (strpos(','.$fields.',', ',field24_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_24" value="field24_poor">Poor
				<input type="radio" <?php if (strpos(','.$fields.',', ',field24_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_24" value="field24_good">Good
				<input type="radio" <?php if (strpos(','.$fields.',', ',field24_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_24" value="field24_exe">Execellent
				&nbsp;&nbsp;<input name="fields_value_24" type="text" value="<?php echo $fields_value[24]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

				Are 4-inch toe boards installed on the scaffold platform?</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field25_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_25" value="field25_na">N/A
				<input type="radio" <?php if (strpos(','.$fields.',', ',field25_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_25" value="field25_poor">Poor
				<input type="radio" <?php if (strpos(','.$fields.',', ',field25_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_25" value="field25_good">Good
				<input type="radio" <?php if (strpos(','.$fields.',', ',field25_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_25" value="field25_exe">Execellent
				&nbsp;&nbsp;<input name="fields_value_25" type="text" value="<?php echo $fields_value[25]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

				Are guardrails in place, or other precautions taken for all scaffold platforms that are greater than 10 feet (3m) above the ground?</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field26_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_26" value="field26_na">N/A
				<input type="radio" <?php if (strpos(','.$fields.',', ',field26_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_26" value="field26_poor">Poor
				<input type="radio" <?php if (strpos(','.$fields.',', ',field26_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_26" value="field26_good">Good
				<input type="radio" <?php if (strpos(','.$fields.',', ',field26_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_26" value="field26_exe">Execellent
				&nbsp;&nbsp;<input name="fields_value_26" type="text" value="<?php echo $fields_value[26]; ?>" class="form-control" />
                    </div>
                </div>

			</div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos(','.$form_config.',', ',fields10,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info6" >
                    Scaffold Comments<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info6" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Scaffold Comments:</label>
                    <div class="col-sm-8">
                      <textarea name="scaffold_comments" rows="5" cols="50" class="form-control"><?php echo $scaffold_comments; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos($form_config, ','."fields11".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info7" >
                    Signs/Tags/Barricades/Screens/Hoardings Details<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info7" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">
				Are confined space entry points closed off with appropriate signage when a "Confined Space Monitor" is not present?</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field27_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_27" value="field27_na">N/A
				<input type="radio" <?php if (strpos(','.$fields.',', ',field27_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_27" value="field27_poor">Poor
				<input type="radio" <?php if (strpos(','.$fields.',', ',field27_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_27" value="field27_good">Good
				<input type="radio" <?php if (strpos(','.$fields.',', ',field27_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_27" value="field27_exe">Execellent
				&nbsp;&nbsp;<input name="fields_value_27" type="text" value="<?php echo $fields_value[27]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

				Are overhead hazards and hazardous areas identified using "caution" or "Do Not Enter" flagging and is the flagging tagged to identify the hazards?</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field28_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_28" value="field28_na">N/A
				<input type="radio" <?php if (strpos(','.$fields.',', ',field28_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_28" value="field28_poor">Poor
				<input type="radio" <?php if (strpos(','.$fields.',', ',field28_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_28" value="field28_good">Good
				<input type="radio" <?php if (strpos(','.$fields.',', ',field28_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_28" value="field28_exe">Execellent
				&nbsp;&nbsp;<input name="fields_value_28" type="text" value="<?php echo $fields_value[28]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

				Hearing protection signs posted?</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field29_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_29" value="field29_na">N/A
				<input type="radio" <?php if (strpos(','.$fields.',', ',field29_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_29" value="field29_poor">Poor
				<input type="radio" <?php if (strpos(','.$fields.',', ',field29_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_29" value="field29_good">Good
				<input type="radio" <?php if (strpos(','.$fields.',', ',field29_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_29" value="field29_exe">Execellent
				&nbsp;&nbsp;<input name="fields_value_29" type="text" value="<?php echo $fields_value[29]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

				Are barricades being utilized if required?</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field30_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_30" value="field30_na">N/A
				<input type="radio" <?php if (strpos(','.$fields.',', ',field30_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_30" value="field30_poor">Poor
				<input type="radio" <?php if (strpos(','.$fields.',', ',field30_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_30" value="field30_good">Good
				<input type="radio" <?php if (strpos(','.$fields.',', ',field30_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_30" value="field30_exe">Execellent
				&nbsp;&nbsp;<input name="fields_value_30" type="text" value="<?php echo $fields_value[30]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

				Are screens, blankets, hoardings being used to protect other personnel where welding, grinding activities are taking place?</label>
                    <div class="col-sm-8">
				<input type="radio" <?php if (strpos(','.$fields.',', ',field31_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_31" value="field31_na">N/A
				<input type="radio" <?php if (strpos(','.$fields.',', ',field31_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_31" value="field31_poor">Poor
				<input type="radio" <?php if (strpos(','.$fields.',', ',field31_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_31" value="field31_good">Good
				<input type="radio" <?php if (strpos(','.$fields.',', ',field31_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_31" value="field31_exe">Execellent
				&nbsp;&nbsp;<input name="fields_value_31" type="text" value="<?php echo $fields_value[31]; ?>" class="form-control" />
                    </div>
                </div>

			</div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields12,') !== FALSE) { ?>
	<div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info8" >
                    Signs/Tags/Barricades/Screens/Hoardings Comments<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info8" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Signs/Tags/Barricades/Screens/Hoardings Comments:</label>
                    <div class="col-sm-8">
                      <textarea name="stbsh_comment" rows="5" cols="50" class="form-control"><?php echo $stbsh_comment; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos($form_config, ','."fields13".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info9" >
                    Housekeeping Details<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info9" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">
			Are aisles, stairways, and doorways free of clutter?</label>
                    <div class="col-sm-8">
			<input type="radio" <?php if (strpos(','.$fields.',', ',field32_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_32" value="field32_na">N/A
			<input type="radio" <?php if (strpos(','.$fields.',', ',field32_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_32" value="field32_poor">Poor
			<input type="radio" <?php if (strpos(','.$fields.',', ',field32_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_32" value="field32_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field32_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_32" value="field32_exe">Execellent
			&nbsp;&nbsp;<input name="fields_value_32" type="text" value="<?php echo $fields_value[32]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

			Is unrestricted access provided to all safety showers, PPE, fire-fighting equipment and emergency exits?</label>
                    <div class="col-sm-8">
			<input type="radio" <?php if (strpos(','.$fields.',', ',field33_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_33" value="field33_na">N/A
			<input type="radio" <?php if (strpos(','.$fields.',', ',field33_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_33" value="field33_poor">Poor
			<input type="radio" <?php if (strpos(','.$fields.',', ',field33_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_33" value="field33_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field33_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_33" value="field33_exe">Execellent
			&nbsp;&nbsp;<input name="fields_value_33" type="text" value="<?php echo $fields_value[33]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

			Are floors free of grease, oil, and other slipping hazards?</label>
                    <div class="col-sm-8">
			<input type="radio" <?php if (strpos(','.$fields.',', ',field34_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_34" value="field34_na">N/A
			<input type="radio" <?php if (strpos(','.$fields.',', ',field34_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_34" value="field34_poor">Poor
			<input type="radio" <?php if (strpos(','.$fields.',', ',field34_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_34" value="field34_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field34_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_34" value="field34_exe">Execellent
			&nbsp;&nbsp;<input name="fields_value_34" type="text" value="<?php echo $fields_value[34]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

			Has all garbage been placed in appropriate bins and have the bins been emptied as required?</label>
                    <div class="col-sm-8">
			<input type="radio" <?php if (strpos(','.$fields.',', ',field35_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_35" value="field35_na">N/A
			<input type="radio" <?php if (strpos(','.$fields.',', ',field35_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_35" value="field35_poor">Poor
			<input type="radio" <?php if (strpos(','.$fields.',', ',field35_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_35" value="field35_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field35_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_35" value="field35_exe">Execellent
			&nbsp;&nbsp;<input name="fields_value_35" type="text" value="<?php echo $fields_value[35]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

			Have all unused hoses been removed?</label>
                    <div class="col-sm-8">
			<input type="radio" <?php if (strpos(','.$fields.',', ',field36_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_36" value="field36_na">N/A
			<input type="radio" <?php if (strpos(','.$fields.',', ',field36_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_36" value="field36_poor">Poor
			<input type="radio" <?php if (strpos(','.$fields.',', ',field36_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_36" value="field36_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field36_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_36" value="field36_exe">Execellent
			&nbsp;&nbsp;<input name="fields_value_36" type="text" value="<?php echo $fields_value[36]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

			Are hoses, ext. cords, and welder cables routed to minimize tripping hazards?</label>
                    <div class="col-sm-8">
			<input type="radio" <?php if (strpos(','.$fields.',', ',field37_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_37" value="field37_na">N/A
			<input type="radio" <?php if (strpos(','.$fields.',', ',field37_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_37" value="field37_poor">Poor
			<input type="radio" <?php if (strpos(','.$fields.',', ',field37_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_37" value="field37_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field37_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_37" value="field37_exe">Execellent
			&nbsp;&nbsp;<input name="fields_value_37" type="text" value="<?php echo $fields_value[37]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

			Is lighting adequate and functioning?</label>
                    <div class="col-sm-8">
			<input type="radio" <?php if (strpos(','.$fields.',', ',field38_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_38" value="field38_na">N/A
			<input type="radio" <?php if (strpos(','.$fields.',', ',field38_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_38" value="field38_poor">Poor
			<input type="radio" <?php if (strpos(','.$fields.',', ',field38_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_38" value="field38_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field38_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_38" value="field38_exe">Execellent
			&nbsp;&nbsp;<input name="fields_value_38" type="text" value="<?php echo $fields_value[38]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

			Are ladder access gates in place and closed?</label>
                    <div class="col-sm-8">
			<input type="radio" <?php if (strpos(','.$fields.',', ',field39_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_39" value="field39_na">N/A
			<input type="radio" <?php if (strpos(','.$fields.',', ',field39_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_39" value="field39_poor">Poor
			<input type="radio" <?php if (strpos(','.$fields.',', ',field39_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_39" value="field39_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field39_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_39" value="field39_exe">Execellent
			&nbsp;&nbsp;<input name="fields_value_39" type="text" value="<?php echo $fields_value[39]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

			Are portable ladders properly positioned and tied off? (1:4)</label>
                    <div class="col-sm-8">
			<input type="radio" <?php if (strpos(','.$fields.',', ',field40_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_40" value="field40_na">N/A
			<input type="radio" <?php if (strpos(','.$fields.',', ',field40_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_40" value="field40_poor">Poor
			<input type="radio" <?php if (strpos(','.$fields.',', ',field40_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_40" value="field40_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field40_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_40" value="field40_exe">Execellent
			&nbsp;&nbsp;<input name="fields_value_40" type="text" value="<?php echo $fields_value[40]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

			Are ladders being used properly?  (not standing on top platform or top 2 rungs)</label>
                    <div class="col-sm-8">
			<input type="radio" <?php if (strpos(','.$fields.',', ',field41_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_41" value="field41_na">N/A
			<input type="radio" <?php if (strpos(','.$fields.',', ',field41_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_41" value="field41_poor">Poor
			<input type="radio" <?php if (strpos(','.$fields.',', ',field41_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_41" value="field41_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field41_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_41" value="field41_exe">Execellent
			&nbsp;&nbsp;<input name="fields_value_41" type="text" value="<?php echo $fields_value[41]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

			Are ladders protruding 1m above intended landing point?</label>
                    <div class="col-sm-8">
			<input type="radio" <?php if (strpos(','.$fields.',', ',field42_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_42" value="field42_na">N/A
			<input type="radio" <?php if (strpos(','.$fields.',', ',field42_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_42" value="field42_poor">Poor
			<input type="radio" <?php if (strpos(','.$fields.',', ',field42_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_42" value="field42_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field42_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_42" value="field42_exe">Execellent
			&nbsp;&nbsp;<input name="fields_value_42" type="text" value="<?php echo $fields_value[42]; ?>" class="form-control" />
                    </div>
                </div>

			</div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields14,') !== FALSE) { ?>
	<div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info10" >
                    Housekeeping Comments<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info10" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Housekeeping Comments:</label>
                    <div class="col-sm-8">
                      <textarea name="housekeeping_comments" rows="5" cols="50" class="form-control"><?php echo $housekeeping_comments; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos($form_config, ','."fields15".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info11" >
                    Tools Details<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info11" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">
			Are tools in good working condition and free of defects (i.e.: frayed cords, broken housings, cracks in metal)?</label>
                    <div class="col-sm-8">
			<input type="radio" <?php if (strpos(','.$fields.',', ',field43_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_43" value="field43_na">N/A
			<input type="radio" <?php if (strpos(','.$fields.',', ',field43_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_43" value="field43_poor">Poor
			<input type="radio" <?php if (strpos(','.$fields.',', ',field43_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_43" value="field43_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field43_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_43" value="field43_exe">Execellent
			&nbsp;&nbsp;<input name="fields_value_43" type="text" value="<?php echo $fields_value[43]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

			Are the required guards in place and safety precautions being followed?</label>
                    <div class="col-sm-8">
			<input type="radio" <?php if (strpos(','.$fields.',', ',field44_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_44" value="field44_na">N/A
			<input type="radio" <?php if (strpos(','.$fields.',', ',field44_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_44" value="field44_poor">Poor
			<input type="radio" <?php if (strpos(','.$fields.',', ',field44_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_44" value="field44_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field44_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_44" value="field44_exe">Execellent
			&nbsp;&nbsp;<input name="fields_value_44" type="text" value="<?php echo $fields_value[44]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

			Are the tools being used for their intended purpose?</label>
                    <div class="col-sm-8">
			<input type="radio" <?php if (strpos(','.$fields.',', ',field45_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_45" value="field45_na">N/A
			<input type="radio" <?php if (strpos(','.$fields.',', ',field45_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_45" value="field45_poor">Poor
			<input type="radio" <?php if (strpos(','.$fields.',', ',field45_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_45" value="field45_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field45_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_45" value="field45_exe">Execellent
			&nbsp;&nbsp;<input name="fields_value_45" type="text" value="<?php echo $fields_value[45]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

			Are air hoses equipped with Chicago couplings, pins and whipchecks?</label>
                    <div class="col-sm-8">
			<input type="radio" <?php if (strpos(','.$fields.',', ',field46_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_46" value="field46_na">N/A
			<input type="radio" <?php if (strpos(','.$fields.',', ',field46_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_46" value="field46_poor">Poor
			<input type="radio" <?php if (strpos(','.$fields.',', ',field46_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_46" value="field46_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field46_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_46" value="field46_exe">Execellent
			&nbsp;&nbsp;<input name="fields_value_46" type="text" value="<?php echo $fields_value[46]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

			Are welding machines grounded?</label>
                    <div class="col-sm-8">
			<input type="radio" <?php if (strpos(','.$fields.',', ',field47_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_47" value="field47_na">N/A
			<input type="radio" <?php if (strpos(','.$fields.',', ',field47_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_47" value="field47_poor">Poor
			<input type="radio" <?php if (strpos(','.$fields.',', ',field47_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_47" value="field47_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field47_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_47" value="field47_exe">Execellent
			&nbsp;&nbsp;<input name="fields_value_47" type="text" value="<?php echo $fields_value[47]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

			If welding machines are not in use are valves closed and regulators and hoses removed?</label>
                    <div class="col-sm-8">
			<input type="radio" <?php if (strpos(','.$fields.',', ',field48_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_48" value="field48_na">N/A
			<input type="radio" <?php if (strpos(','.$fields.',', ',field48_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_48" value="field48_poor">Poor
			<input type="radio" <?php if (strpos(','.$fields.',', ',field48_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_48" value="field48_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field48_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_48" value="field48_exe">Execellent
			&nbsp;&nbsp;<input name="fields_value_48" type="text" value="<?php echo $fields_value[48]; ?>" class="form-control" />
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info12" >
                    Tools Comments<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info12" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Tools Comments:</label>
                    <div class="col-sm-8">
                      <textarea name="tool_comments" rows="5" cols="50" class="form-control"><?php echo $tool_comments; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos($form_config, ','."fields17".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info13" >
                    Mobile Equipment Details<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info13" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">

			Is proper equipment being used for the task? (Crane, Forklift, AWP, etc)</label>
                    <div class="col-sm-8">
			<input type="radio" <?php if (strpos(','.$fields.',', ',field49_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_49" value="field49_na">N/A
			<input type="radio" <?php if (strpos(','.$fields.',', ',field49_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_49" value="field49_poor">Poor
			<input type="radio" <?php if (strpos(','.$fields.',', ',field49_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_49" value="field49_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field49_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_49" value="field49_exe">Execellent
			&nbsp;&nbsp;<input name="fields_value_49" type="text" value="<?php echo $fields_value[49]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

			If AWP is being used are occupants tied off? Properly?</label>
                    <div class="col-sm-8">
			<input type="radio" <?php if (strpos(','.$fields.',', ',field50_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_50" value="field50_na">N/A
			<input type="radio" <?php if (strpos(','.$fields.',', ',field50_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_50" value="field50_poor">Poor
			<input type="radio" <?php if (strpos(','.$fields.',', ',field50_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_50" value="field50_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field50_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_50" value="field50_exe">Execellent
			&nbsp;&nbsp;<input name="fields_value_50" type="text" value="<?php echo $fields_value[50]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

			Has the equipment been inspected prior to use?</label>
                    <div class="col-sm-8">
			<input type="radio" <?php if (strpos(','.$fields.',', ',field51_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_51" value="field51_na">N/A
			<input type="radio" <?php if (strpos(','.$fields.',', ',field51_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_51" value="field51_poor">Poor
			<input type="radio" <?php if (strpos(','.$fields.',', ',field51_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_51" value="field51_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field51_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_51" value="field51_exe">Execellent
			&nbsp;&nbsp;<input name="fields_value_51" type="text" value="<?php echo $fields_value[51]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

			Is the equipment in good condition? (cab housekeeping, lights, horn, backup alarm, fire extinguisher)</label>
                    <div class="col-sm-8">
			<input type="radio" <?php if (strpos(','.$fields.',', ',field52_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_52" value="field52_na">N/A
			<input type="radio" <?php if (strpos(','.$fields.',', ',field52_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_52" value="field52_poor">Poor
			<input type="radio" <?php if (strpos(','.$fields.',', ',field52_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_52" value="field52_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field52_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_52" value="field52_exe">Execellent
			&nbsp;&nbsp;<input name="fields_value_52" type="text" value="<?php echo $fields_value[52]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

			Is a spotter required and being used for this job?</label>
                    <div class="col-sm-8">
			<input type="radio" <?php if (strpos(','.$fields.',', ',field53_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_53" value="field53_na">N/A
			<input type="radio" <?php if (strpos(','.$fields.',', ',field53_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_53" value="field53_poor">Poor
			<input type="radio" <?php if (strpos(','.$fields.',', ',field53_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_53" value="field53_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field53_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_53" value="field53_exe">Execellent
			&nbsp;&nbsp;<input name="fields_value_53" type="text" value="<?php echo $fields_value[53]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

			If lifts are being done are proper procedures being used? (signaler/s identified by armbands, area flagged off and/or horns being used to alert others)</label>
                    <div class="col-sm-8">
			<input type="radio" <?php if (strpos(','.$fields.',', ',field54_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_54" value="field54_na">N/A
			<input type="radio" <?php if (strpos(','.$fields.',', ',field54_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_54" value="field54_poor">Poor
			<input type="radio" <?php if (strpos(','.$fields.',', ',field54_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_54" value="field54_good">Good
			<input type="radio" <?php if (strpos(','.$fields.',', ',field54_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_54" value="field54_exe">Execellent
			&nbsp;&nbsp;<input name="fields_value_54" type="text" value="<?php echo $fields_value[54]; ?>" class="form-control" />
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos(','.$form_config.',', ',fields18,') !== FALSE) { ?>
	<div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info14" >
                    Mobile Equipment Comments<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info14" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Mobile Equipment Comments:</label>
                    <div class="col-sm-8">
                      <textarea name="mobile_equipment_comments" rows="5" cols="50" class="form-control"><?php echo $mobile_equipment_comments; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos($form_config, ','."fields19".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info15" >
                    Miscellaneous Details<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info15" class="panel-collapse collapse">
            <div class="panel-body">

            <b>Fire Extinguishers </b>
                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">
            Are fire extinguishers in proper places - as required for the job?</label>
                    <div class="col-sm-8">
            <input type="radio" <?php if (strpos(','.$fields.',', ',field55_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_55" value="field55_na">N/A
            <input type="radio" <?php if (strpos(','.$fields.',', ',field55_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_55" value="field55_poor">Poor
            <input type="radio" <?php if (strpos(','.$fields.',', ',field55_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_55" value="field55_good">Good
            <input type="radio" <?php if (strpos(','.$fields.',', ',field55_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_55" value="field55_exe">Execellent
            &nbsp;&nbsp;<input name="fields_value_55" type="text" value="<?php echo $fields_value[55]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

            Are the right type of fire extinguishers in place?</label>
                    <div class="col-sm-8">
            <input type="radio" <?php if (strpos(','.$fields.',', ',field56_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_56" value="field56_na">N/A
            <input type="radio" <?php if (strpos(','.$fields.',', ',field56_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_56" value="field56_poor">Poor
            <input type="radio" <?php if (strpos(','.$fields.',', ',field56_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_56" value="field56_good">Good
            <input type="radio" <?php if (strpos(','.$fields.',', ',field56_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_56" value="field56_exe">Execellent
            &nbsp;&nbsp;<input name="fields_value_56" type="text" value="<?php echo $fields_value[56]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

            Are they in good condition, working order?</label>
                    <div class="col-sm-8">
            <input type="radio" <?php if (strpos(','.$fields.',', ',field57_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_57" value="field57_na">N/A
            <input type="radio" <?php if (strpos(','.$fields.',', ',field57_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_57" value="field57_poor">Poor
            <input type="radio" <?php if (strpos(','.$fields.',', ',field57_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_57" value="field57_good">Good
            <input type="radio" <?php if (strpos(','.$fields.',', ',field57_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_57" value="field57_exe">Execellent
            &nbsp;&nbsp;<input name="fields_value_57" type="text" value="<?php echo $fields_value[57]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

            Have they been recently inspected?</label>
                    <div class="col-sm-8">
            <input type="radio" <?php if (strpos(','.$fields.',', ',field58_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_58" value="field58_na">N/A
            <input type="radio" <?php if (strpos(','.$fields.',', ',field58_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_58" value="field58_poor">Poor
            <input type="radio" <?php if (strpos(','.$fields.',', ',field58_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_58" value="field58_good">Good
            <input type="radio" <?php if (strpos(','.$fields.',', ',field58_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_58" value="field58_exe">Execellent
            &nbsp;&nbsp;<input name="fields_value_58" type="text" value="<?php echo $fields_value[58]; ?>" class="form-control" />
                    </div>
                </div>

            <b>FLRA</b>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">
            Have personnel done their own FLRA?</label>
                    <div class="col-sm-8">
            <input type="radio" <?php if (strpos(','.$fields.',', ',field59_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_59" value="field59_na">N/A
            <input type="radio" <?php if (strpos(','.$fields.',', ',field59_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_59" value="field59_poor">Poor
            <input type="radio" <?php if (strpos(','.$fields.',', ',field59_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_59" value="field59_good">Good
            <input type="radio" <?php if (strpos(','.$fields.',', ',field59_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_59" value="field59_exe">Execellent
            &nbsp;&nbsp;<input name="fields_value_59" type="text" value="<?php echo $fields_value[59]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

            Does FLRA accurately describe work being performed?</label>
                    <div class="col-sm-8">
            <input type="radio" <?php if (strpos(','.$fields.',', ',field60_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_60" value="field60_na">N/A
            <input type="radio" <?php if (strpos(','.$fields.',', ',field60_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_60" value="field60_poor">Poor
            <input type="radio" <?php if (strpos(','.$fields.',', ',field60_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_60" value="field60_good">Good
            <input type="radio" <?php if (strpos(','.$fields.',', ',field60_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_60" value="field60_exe">Execellent
            &nbsp;&nbsp;<input name="fields_value_60" type="text" value="<?php echo $fields_value[60]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

            Have all of the workers been part of the development and/or signed off on the FLRA?</label>
                    <div class="col-sm-8">
            <input type="radio" <?php if (strpos(','.$fields.',', ',field61_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_61" value="field61_na">N/A
            <input type="radio" <?php if (strpos(','.$fields.',', ',field61_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_61" value="field61_poor">Poor
            <input type="radio" <?php if (strpos(','.$fields.',', ',field61_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_61" value="field61_good">Good
            <input type="radio" <?php if (strpos(','.$fields.',', ',field61_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_61" value="field61_exe">Execellent
            &nbsp;&nbsp;<input name="fields_value_61" type="text" value="<?php echo $fields_value[61]; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">

            Have all the hazards been identified on the FLRA?</label>
                    <div class="col-sm-8">
            <input type="radio" <?php if (strpos(','.$fields.',', ',field62_na,') !== FALSE) { echo " checked"; } ?>  name="fields_option_62" value="field62_na">N/A
            <input type="radio" <?php if (strpos(','.$fields.',', ',field62_poor,') !== FALSE) { echo " checked"; } ?>  name="fields_option_62" value="field62_poor">Poor
            <input type="radio" <?php if (strpos(','.$fields.',', ',field62_good,') !== FALSE) { echo " checked"; } ?>  name="fields_option_62" value="field62_good">Good
            <input type="radio" <?php if (strpos(','.$fields.',', ',field62_exe,') !== FALSE) { echo " checked"; } ?>  name="fields_option_62" value="field62_exe">Execellent
            &nbsp;&nbsp;<input name="fields_value_62" type="text" value="<?php echo $fields_value[62]; ?>" class="form-control" />
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields20,') !== FALSE) { ?>
	<div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info16" >
                    Miscellaneous Comments<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info16" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Miscellaneous Comments:</label>
                    <div class="col-sm-8">
                      <textarea name="miscellaneous_comments" rows="5" cols="50" class="form-control"><?php echo $miscellaneous_comments; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields21,') !== FALSE) { ?>
	<div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info17" >
                    General Comments<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info17" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">General Comments:</label>
                    <div class="col-sm-8">
                      <textarea name="general_comments" rows="5" cols="50" class="form-control"><?php echo $general_comments; ?></textarea>
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
                echo '<img src="weekly_planned_inspection_checklist/download/safety_'.$assign_staff_id.'.png">';
            } ?>

            </div>
        </div>
    </div>
    <?php $sa_inc++;
        }
    } ?>

</div>