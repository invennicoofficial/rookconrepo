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
$fields = '';
$desc = '';
$all_task = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];
	echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_confined_space_entry_pre_entry_checklist WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
	$desc = $get_field_level['desc'];
	$all_task = $get_field_level['all_task'];
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
                    <label for="business_street" class="col-sm-4 control-label">Job Number</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_1" value="<?php echo $fields[1]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Safety Watch</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_2" value="<?php echo $fields[2]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Client</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_3" value="<?php echo $fields[3]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Client Rep.</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_4" value="<?php echo $fields[4]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Location</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_5" value="<?php echo $fields[5]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields7".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Supervisor</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_6" value="<?php echo $fields[6]; ?>" class="form-control" />
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_com" >
                    Confined Space Description<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_com" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Confined Space Description</label>
                <div class="col-sm-8">
                <textarea name="desc" rows="3" cols="50" class="form-control"><?php echo $desc; ?></textarea>
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_item" >
                    Item To Be Checked<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_item" class="panel-collapse collapse">
            <div class="panel-body">

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Safety Watch has been designated (Named above on this document)</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[7] == 'Yes') { echo " checked"; } ?>  name="fields_7" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[7] == 'N/A') { echo " checked"; } ?>  name="fields_7" value="N/A">N/A&nbsp;&nbsp;
                <input type="text" placeholder="Checked By" name="fields_8" value="<?php echo $fields[8]; ?>" class="form-control" style="width:25%;" />
                <input type="text" placeholder="Time" name="fields_9" value="<?php echo $fields[9]; ?>" class="form-control" style="width:15%;" />
                <input type="text" placeholder="Description" name="fields_10" value="<?php echo $fields[10]; ?>" class="form-control" style="width:40%;" />
                </div>
                </div>


				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Safety Watch has reviewed the Confined Space Code Of Practice.</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[11] == 'Yes') { echo " checked"; } ?>  name="fields_11" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[11] == 'N/A') { echo " checked"; } ?>  name="fields_11" value="N/A">N/A&nbsp;&nbsp;
                <input type="text" placeholder="Checked By" name="fields_12" value="<?php echo $fields[12]; ?>" class="form-control" style="width:25%;" />
                <input type="text" placeholder="Time" name="fields_13" value="<?php echo $fields[13]; ?>" class="form-control" style="width:15%;" />
                <input type="text" placeholder="Description" name="fields_14" value="<?php echo $fields[14]; ?>" class="form-control" style="width:40%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Confined Space Permit has been completed.</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[15] == 'Yes') { echo " checked"; } ?>  name="fields_15" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[15] == 'N/A') { echo " checked"; } ?>  name="fields_15" value="N/A">N/A&nbsp;&nbsp;
                <input type="text" placeholder="Checked By" name="fields_16" value="<?php echo $fields[16]; ?>" class="form-control" style="width:25%;" />
                <input type="text" placeholder="Time" name="fields_17" value="<?php echo $fields[17]; ?>" class="form-control" style="width:15%;" />
                <input type="text" placeholder="Description" name="fields_18" value="<?php echo $fields[18]; ?>" class="form-control" style="width:40%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Rescue Plan is documented and rescue equipment is in place.</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[19] == 'Yes') { echo " checked"; } ?>  name="fields_19" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[19] == 'N/A') { echo " checked"; } ?>  name="fields_19" value="N/A">N/A&nbsp;&nbsp;
                <input type="text" placeholder="Checked By" name="fields_20" value="<?php echo $fields[20]; ?>" class="form-control" style="width:25%;" />
                <input type="text" placeholder="Time" name="fields_21" value="<?php echo $fields[21]; ?>" class="form-control" style="width:15%;" />
                <input type="text" placeholder="Description" name="fields_22" value="<?php echo $fields[22]; ?>" class="form-control" style="width:40%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Known hazards are identified and mitigated. (Wrapex Hazard Assessment)</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[23] == 'Yes') { echo " checked"; } ?>  name="fields_23" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[23] == 'N/A') { echo " checked"; } ?>  name="fields_23" value="N/A">N/A&nbsp;&nbsp;
                <input type="text" placeholder="Checked By" name="fields_24" value="<?php echo $fields[24]; ?>" class="form-control" style="width:25%;" />
                <input type="text" placeholder="Time" name="fields_25" value="<?php echo $fields[25]; ?>" class="form-control" style="width:15%;" />
                <input type="text" placeholder="Description" name="fields_26" value="<?php echo $fields[26]; ?>" class="form-control" style="width:40%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Confined space is clearly marked.</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[27] == 'Yes') { echo " checked"; } ?>  name="fields_27" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[27] == 'N/A') { echo " checked"; } ?>  name="fields_27" value="N/A">N/A&nbsp;&nbsp;
                <input type="text" placeholder="Checked By" name="fields_28" value="<?php echo $fields[28]; ?>" class="form-control" style="width:25%;" />
                <input type="text" placeholder="Time" name="fields_29" value="<?php echo $fields[29]; ?>" class="form-control" style="width:15%;" />
                <input type="text" placeholder="Description" name="fields_30" value="<?php echo $fields[30]; ?>" class="form-control" style="width:40%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Contents (or previous contents) of confined space are identified.</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[31] == 'Yes') { echo " checked"; } ?>  name="fields_31" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[31] == 'N/A') { echo " checked"; } ?>  name="fields_31" value="N/A">N/A&nbsp;&nbsp;
                <input type="text" placeholder="Checked By" name="fields_32" value="<?php echo $fields[32]; ?>" class="form-control" style="width:25%;" />
                <input type="text" placeholder="Time" name="fields_33" value="<?php echo $fields[33]; ?>" class="form-control" style="width:15%;" />
                <input type="text" placeholder="Description" name="fields_34" value="<?php echo $fields[34]; ?>" class="form-control" style="width:40%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Confined space has been / is being ventilated.</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[35] == 'Yes') { echo " checked"; } ?>  name="fields_35" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[35] == 'N/A') { echo " checked"; } ?>  name="fields_35" value="N/A">N/A&nbsp;&nbsp;
                <input type="text" placeholder="Checked By" name="fields_36" value="<?php echo $fields[36]; ?>" class="form-control" style="width:25%;" />
                <input type="text" placeholder="Time" name="fields_37" value="<?php echo $fields[37]; ?>" class="form-control" style="width:15%;" />
                <input type="text" placeholder="Description" name="fields_38" value="<?php echo $fields[38]; ?>" class="form-control" style="width:40%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Confined space atmosphere is being continuously monitored.</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[39] == 'Yes') { echo " checked"; } ?>  name="fields_39" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[39] == 'N/A') { echo " checked"; } ?>  name="fields_39" value="N/A">N/A&nbsp;&nbsp;
                <input type="text" placeholder="Checked By" name="fields_40" value="<?php echo $fields[40]; ?>" class="form-control" style="width:25%;" />
                <input type="text" placeholder="Time" name="fields_41" value="<?php echo $fields[41]; ?>" class="form-control" style="width:15%;" />
                <input type="text" placeholder="Description" name="fields_42" value="<?php echo $fields[42]; ?>" class="form-control" style="width:40%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Atmospheric testing equipment has been function tested. (bump test)</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[43] == 'Yes') { echo " checked"; } ?>  name="fields_43" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[43] == 'N/A') { echo " checked"; } ?>  name="fields_43" value="N/A">N/A&nbsp;&nbsp;
                <input type="text" placeholder="Checked By" name="fields_44" value="<?php echo $fields[44]; ?>" class="form-control" style="width:25%;" />
                <input type="text" placeholder="Time" name="fields_45" value="<?php echo $fields[45]; ?>" class="form-control" style="width:15%;" />
                <input type="text" placeholder="Description" name="fields_46" value="<?php echo $fields[46]; ?>" class="form-control" style="width:40%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Equipment affecting the confined space is locked out and tagged.</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[47] == 'Yes') { echo " checked"; } ?>  name="fields_47" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[47] == 'N/A') { echo " checked"; } ?>  name="fields_47" value="N/A">N/A&nbsp;&nbsp;
                <input type="text" placeholder="Checked By" name="fields_48" value="<?php echo $fields[48]; ?>" class="form-control" style="width:25%;" />
                <input type="text" placeholder="Time" name="fields_49" value="<?php echo $fields[49]; ?>" class="form-control" style="width:15%;" />
                <input type="text" placeholder="Description" name="fields_50" value="<?php echo $fields[50]; ?>" class="form-control" style="width:40%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Piping into or affecting the confined space is isolated to zero energy. (Blanking & Blinding SWP followed)</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[51] == 'Yes') { echo " checked"; } ?>  name="fields_51" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[51] == 'N/A') { echo " checked"; } ?>  name="fields_51" value="N/A">N/A&nbsp;&nbsp;
                <input type="text" placeholder="Checked By" name="fields_52" value="<?php echo $fields[52]; ?>" class="form-control" style="width:25%;" />
                <input type="text" placeholder="Time" name="fields_53" value="<?php echo $fields[53]; ?>" class="form-control" style="width:15%;" />
                <input type="text" placeholder="Description" name="fields_54" value="<?php echo $fields[54]; ?>" class="form-control" style="width:40%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Physical check for zero energy. Verified by (name)</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[55] == 'Yes') { echo " checked"; } ?>  name="fields_55" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[55] == 'N/A') { echo " checked"; } ?>  name="fields_55" value="N/A">N/A&nbsp;&nbsp;
                <input type="text" placeholder="Checked By" name="fields_56" value="<?php echo $fields[56]; ?>" class="form-control" style="width:25%;" />
                <input type="text" placeholder="Time" name="fields_57" value="<?php echo $fields[57]; ?>" class="form-control" style="width:15%;" />
                <input type="text" placeholder="Description" name="fields_58" value="<?php echo $fields[58]; ?>" class="form-control" style="width:40%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Communications system between entrants and safety watch is in place and tested.</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[59] == 'Yes') { echo " checked"; } ?>  name="fields_59" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[59] == 'N/A') { echo " checked"; } ?>  name="fields_59" value="N/A">N/A&nbsp;&nbsp;
                <input type="text" placeholder="Checked By" name="fields_60" value="<?php echo $fields[60]; ?>" class="form-control" style="width:25%;" />
                <input type="text" placeholder="Time" name="fields_61" value="<?php echo $fields[61]; ?>" class="form-control" style="width:15%;" />
                <input type="text" placeholder="Description" name="fields_62" value="<?php echo $fields[62]; ?>" class="form-control" style="width:40%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Entry workers training qualifications (competencies) reviewed.</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[63] == 'Yes') { echo " checked"; } ?>  name="fields_63" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[63] == 'N/A') { echo " checked"; } ?>  name="fields_63" value="N/A">N/A&nbsp;&nbsp;
                <input type="text" placeholder="Checked By" name="fields_64" value="<?php echo $fields[64]; ?>" class="form-control" style="width:25%;" />
                <input type="text" placeholder="Time" name="fields_65" value="<?php echo $fields[65]; ?>" class="form-control" style="width:15%;" />
                <input type="text" placeholder="Description" name="fields_66" value="<?php echo $fields[66]; ?>" class="form-control" style="width:40%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Hot Work hazard assessment has been completed.</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[67] == 'Yes') { echo " checked"; } ?>  name="fields_67" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[67] == 'N/A') { echo " checked"; } ?>  name="fields_67" value="N/A">N/A&nbsp;&nbsp;
                <input type="text" placeholder="Checked By" name="fields_68" value="<?php echo $fields[68]; ?>" class="form-control" style="width:25%;" />
                <input type="text" placeholder="Time" name="fields_69" value="<?php echo $fields[69]; ?>" class="form-control" style="width:15%;" />
                <input type="text" placeholder="Description" name="fields_70" value="<?php echo $fields[70]; ?>" class="form-control" style="width:40%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Supplied air (SCBA or SABA) is available for workers.</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[71] == 'Yes') { echo " checked"; } ?>  name="fields_71" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[71] == 'N/A') { echo " checked"; } ?>  name="fields_71" value="N/A">N/A&nbsp;&nbsp;
                <input type="text" placeholder="Checked By" name="fields_72" value="<?php echo $fields[72]; ?>" class="form-control" style="width:25%;" />
                <input type="text" placeholder="Time" name="fields_73" value="<?php echo $fields[73]; ?>" class="form-control" style="width:15%;" />
                <input type="text" placeholder="Description" name="fields_74" value="<?php echo $fields[74]; ?>" class="form-control" style="width:40%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Safety ropes and harnesses are available for entry workers.</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[75] == 'Yes') { echo " checked"; } ?>  name="fields_75" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[75] == 'N/A') { echo " checked"; } ?>  name="fields_75" value="N/A">N/A&nbsp;&nbsp;
                <input type="text" placeholder="Checked By" name="fields_76" value="<?php echo $fields[76]; ?>" class="form-control" style="width:25%;" />
                <input type="text" placeholder="Time" name="fields_77" value="<?php echo $fields[77]; ?>" class="form-control" style="width:15%;" />
                <input type="text" placeholder="Description" name="fields_78" value="<?php echo $fields[78]; ?>" class="form-control" style="width:40%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Is electrical equipment over 12 volts ground-fault protected?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[79] == 'Yes') { echo " checked"; } ?>  name="fields_79" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[79] == 'N/A') { echo " checked"; } ?>  name="fields_79" value="N/A">N/A&nbsp;&nbsp;
                <input type="text" placeholder="Checked By" name="fields_80" value="<?php echo $fields[80]; ?>" class="form-control" style="width:25%;" />
                <input type="text" placeholder="Time" name="fields_81" value="<?php echo $fields[81]; ?>" class="form-control" style="width:15%;" />
                <input type="text" placeholder="Description" name="fields_82" value="<?php echo $fields[82]; ?>" class="form-control" style="width:40%;" />
                </div>
                </div>


 			</div>
        </div>
    </div>
    <?php }?>


    <?php if (strpos($form_config, ','."fields10".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_atmo" >
                    Atmospheric Testing<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_atmo" class="panel-collapse collapse">
            <div class="panel-body">

                <?php
                $all_task_each = explode('**##**',$all_task);

                $total_count = mb_substr_count($all_task,'**##**');
                if($total_count > 0) {
                    echo "<table class='table table-bordered'>";
                    echo "<tr class='hidden-xs hidden-sm'>
                    <th>Test Time</th>
                    <th>Tested By</th>
                    <th>LEL %</th>
                    <th>H2S %</th>
					<th>Oxygen %</th>
					<th>Desc</th>";
                }
                for($client_loop=0; $client_loop<=$total_count; $client_loop++) {
                    $task_item = explode('**',$all_task_each[$client_loop]);
                    $task = $task_item[0];
                    $hazard = $task_item[1];
                    $level = $task_item[2];
                    $plan = $task_item[3];
					$plan1 = $task_item[4];
					$plan2 = $task_item[5];
                    if($task != '') {
                        echo '<tr>';
                        echo '<td data-title="Email">' . $task . '</td>';
                        echo '<td data-title="Email">' . $hazard . '</td>';
                        echo '<td data-title="Email">' . $level . '</td>';
                        echo '<td data-title="Email">' . $plan . '</td>';
						echo '<td data-title="Email">' . $plan1 . '</td>';
						echo '<td data-title="Email">' . $plan2 . '</td>';
                        echo '</tr>';
                    }
                }
                echo '</table>';
                ?>
                <div class="additional_hazard clearfix">
                    <div class="row">
                        <div class="col-md-2 col-sm-6 col-xs-6 padded">
                            <p>Test Time</p>
                            <input type="text" name="task[]" class="task_list"/>
                        </div>
                        <div class="col-md-2 col-sm-6 col-xs-6 padded">
                            <p>Tested By</p>
                            <input type="text" name="hazard[]" class="task_list"/>
                        </div>
                        <div class="col-md-1 col-sm-6 col-xs-6 padded">
                            <p>Lel</p>
                            <input type="text" name="hazard1[]" class="task_list"/>
                        </div>
                        <div class="col-md-1 col-sm-6 col-xs-6 padded">
                            <p>H2s</p>
                            <input type="text" name="hazard_plan[]" class="task_list"/>
                        </div>
						<div class="col-md-1 col-sm-6 col-xs-6 padded">
                            <p>Oxygen</p>
                            <input type="text" name="hazard_plan1[]" class="task_list"/>
                        </div>
						<div class="col-md-4 col-sm-6 col-xs-6 padded">
							<p>Description</p>
                            <input type="text" name="hazard_plan2[]" class="task_list"/>
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
                echo '<img src="confined_space_entry_pre_entry_checklist/download/safety_'.$assign_staff_id.'.png">';
            } ?>

        </div>
    </div>
</div>
<?php $sa_inc++;
    }
} ?>

</div>