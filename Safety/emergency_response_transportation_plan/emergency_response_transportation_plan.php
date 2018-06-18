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
$desc1 = '';
$desc2 = '';
$desc3 = '';
$desc4 = '';
$all_task = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];
	echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_emergency_response_transportation_plan WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
	$desc = $get_field_level['desc'];
	$desc1 = $get_field_level['desc1'];
	$desc2 = $get_field_level['desc2'];
	$desc3 = $get_field_level['desc3'];
	$desc4 = $get_field_level['desc4'];
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
                    <label for="business_street" class="col-sm-4 control-label">Office Contact</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_1" value="<?php echo $fields[1]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Client</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_2" value="<?php echo $fields[2]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Type of Work</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_3" value="<?php echo $fields[3]; ?>" class="form-control" />
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_loc" >
                    Location<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_loc" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Location</label>
                <div class="col-sm-8">
                <textarea name="desc" rows="3" cols="50" class="form-control"><?php echo $desc; ?></textarea>
                </div>
                </div>

			</div>
        </div>
    </div>
<?php } ?>

<?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_file" >
                    File Number<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_file" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">File Number</label>
                <div class="col-sm-8">
                <textarea name="desc1" rows="3" cols="50" class="form-control"><?php echo $desc1; ?></textarea>
                </div>
                </div>

			</div>
        </div>
    </div>
<?php } ?>

<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_est" >
                    Estimation<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_est" class="panel-collapse collapse">
            <div class="panel-body">

				<?php if (strpos($form_config, ','."fields7".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Estimated Travel Time (one-way)</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_4" value="<?php echo $fields[4]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields8".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Estimated duration of work</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_5" value="<?php echo $fields[5]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

			</div>
        </div>
    </div>

<?php if (strpos($form_config, ','."fields9".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_rot" >
                    Route(s) to site(s)<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_rot" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Route(s) to site(s)</label>
                <div class="col-sm-8">
                <textarea name="desc2" rows="3" cols="50" class="form-control"><?php echo $desc2; ?></textarea>
                </div>
                </div>

			</div>
        </div>
    </div>
<?php } ?>

<?php if (strpos($form_config, ','."fields10".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_call" >
                    Call-In Time(S)<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_call" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Call-In Time(S)</label>
                <div class="col-sm-8">
                <textarea name="desc3" rows="3" cols="50" class="form-control"><?php echo $desc3; ?></textarea>
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_stay" >
                    staying<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_stay" class="panel-collapse collapse">
            <div class="panel-body">

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Are you staying out of town for the night?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[6] == 'Yes') { echo " checked"; } ?>  name="fields_6" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[6] == 'No') { echo " checked"; } ?>  name="fields_6" value="No">No&nbsp;&nbsp;
                If yes, where<input type="text" name="fields_7" value="<?php echo $fields[7]; ?>"   class="form-control" />
                </div>
				</div>

			</div>
        </div>
    </div>

<?php } ?>


	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_vinfo" >
                    Vehicle Information<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_vinfo" class="panel-collapse collapse">
            <div class="panel-body">

				<?php if (strpos($form_config, ','."fields12".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Vehicle make/model</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_8" value="<?php echo $fields[8]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields13".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Color</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_9" value="<?php echo $fields[9]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields14".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">License Plate#</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_10" value="<?php echo $fields[10]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

			</div>
        </div>
    </div>

<?php if (strpos($form_config, ','."fields15".',') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_vtype" >
                    Vehicle Type<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_vtype" class="panel-collapse collapse">
            <div class="panel-body">

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Vehicle Type</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[11] == 'Pioneer vehicle') { echo " checked"; } ?>  name="fields_11" value="Pioneer vehicle">Pioneer vehicle&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[11] == 'Rental') { echo " checked"; } ?>  name="fields_11" value="Rental">Rental&nbsp;&nbsp;
				<input type="radio" <?php if ($fields[11] == 'Personal vehicle') { echo " checked"; } ?>  name="fields_11" value="Personal vehicle">Personal vehicle&nbsp;&nbsp;
				<input type="radio" <?php if ($fields[11] == 'Other') { echo " checked"; } ?>  name="fields_11" value="Other">Other&nbsp;&nbsp;
                specify<input type="text" name="fields_12" value="<?php echo $fields[12]; ?>"   class="form-control" />
                </div>
				</div>

			</div>
        </div>
    </div>

<?php } ?>


<?php if (strpos($form_config, ','."fields16".',') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_com" >
                    Comunication<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_com" class="panel-collapse collapse">
            <div class="panel-body">

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Is there cellular coverage in the area?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[13] == 'Yes') { echo " checked"; } ?>  name="fields_13" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[13] == 'No') { echo " checked"; } ?>  name="fields_13" value="No">No&nbsp;&nbsp;
				<input type="radio" <?php if ($fields[13] == 'Partial') { echo " checked"; } ?>  name="fields_13" value="Partial">Partial&nbsp;&nbsp;
                <input type="text" name="fields_14" value="<?php echo $fields[14]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Are you taking another means of communication?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[15] == 'Yes') { echo " checked"; } ?>  name="fields_15" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[15] == 'No') { echo " checked"; } ?>  name="fields_15" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_16" value="<?php echo $fields[16]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Cell Phone #</label>
                <div class="col-sm-8"><input type="checkbox" <?php if ($fields[17]=='Cell Phone #') { echo " checked"; } ?>  name="fields_17" value="Cell Phone #"><input name="fields_18" type="text" value="<?php echo $fields[18]; ?>" class="form-control" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Truck Phone #</label>
                <div class="col-sm-8"><input type="checkbox" <?php if ($fields[19]=='Truck Phone #') { echo " checked"; } ?>  name="fields_19" value="Truck Phone #"><input name="fields_20" type="text" value="<?php echo $fields[20]; ?>" class="form-control" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Satellite Phone #</label>
                <div class="col-sm-8"><input type="checkbox" <?php if ($fields[21]=='Satellite Phone #') { echo " checked"; } ?>  name="fields_21" value="Satellite Phone #"><input name="fields_22" type="text" value="<?php echo $fields[22]; ?>" class="form-control" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Other #</label>
                <div class="col-sm-8"><input type="checkbox" <?php if ($fields[23]=='Other #') { echo " checked"; } ?>  name="fields_23" value="Other #"><input name="fields_24" type="text" value="<?php echo $fields[24]; ?>" class="form-control" />
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_safe" >
                    Safety Equipment(check all that are available in the vehicle)<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_safe" class="panel-collapse collapse">
            <div class="panel-body">

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">AB#2 First aid kit</label>
                <div class="col-sm-8"><input type="checkbox" <?php if ($fields[25]=='AB#2 First aid kit') { echo " checked"; } ?>  name="fields_25" value="AB#2 First aid kit"><input name="fields_26" type="text" value="<?php echo $fields[26]; ?>" class="form-control" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Fire extinguisher</label>
                <div class="col-sm-8"><input type="checkbox" <?php if ($fields[27]=='Fire extinguisher') { echo " checked"; } ?>  name="fields_27" value="Fire extinguisher"><input name="fields_28" type="text" value="<?php echo $fields[28]; ?>" class="form-control" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Roadside flare kit</label>
                <div class="col-sm-8"><input type="checkbox" <?php if ($fields[29]=='Roadside flare kit') { echo " checked"; } ?>  name="fields_29" value="Roadside flare kit"><input name="fields_30" type="text" value="<?php echo $fields[30]; ?>" class="form-control" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Tow rope / chains</label>
                <div class="col-sm-8"><input type="checkbox" <?php if ($fields[31]=='Tow rope / chains') { echo " checked"; } ?>  name="fields_31" value="Tow rope / chains"><input name="fields_32" type="text" value="<?php echo $fields[32]; ?>" class="form-control" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Survival kit</label>
                <div class="col-sm-8"><input type="checkbox" <?php if ($fields[33]=='Survival kit') { echo " checked"; } ?>  name="fields_33" value="Survival kit"><input name="fields_34" type="text" value="<?php echo $fields[34]; ?>" class="form-control" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">GPS unit</label>
                <div class="col-sm-8"><input type="checkbox" <?php if ($fields[35]=='GPS unit') { echo " checked"; } ?>  name="fields_35" value="GPS unit"><input name="fields_36" type="text" value="<?php echo $fields[36]; ?>" class="form-control" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Bear spray/bangers</label>
                <div class="col-sm-8"><input type="checkbox" <?php if ($fields[37]=='Bear spray/bangers') { echo " checked"; } ?>  name="fields_37" value="Bear spray/bangers"><input name="fields_38" type="text" value="<?php echo $fields[38]; ?>" class="form-control" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Emergency phone list </label>
                <div class="col-sm-8"><input type="checkbox" <?php if ($fields[39]=='Emergency phone list') { echo " checked"; } ?>  name="fields_39" value="Emergency phone list "><input name="fields_40" type="text" value="<?php echo $fields[40]; ?>" class="form-control" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Other</label>
                <div class="col-sm-8"><input type="checkbox" <?php if ($fields[41]=='Other') { echo " checked"; } ?>  name="fields_41" value="Other"><input name="fields_42" type="text" value="<?php echo $fields[42]; ?>" class="form-control" />
                </div>
                </div>


			</div>
        </div>
    </div>

<?php } ?>


<?php if (strpos($form_config, ','."fields18".',') !== FALSE) { ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_read" >
                    Emergency Transportation<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_read" class="panel-collapse collapse">
            <div class="panel-body">

			<ul>

			<li>If you are within 40 minutes of the nearest medical facility, call 911 or local emergency services.</li>

			<li>Provide emergency personnel with your exact location, the nature of the injury, and the condition of the victim.</li>

			<li>Contact your supervisor to inform him or her of the situation and that emergency assistance has been summoned.</li>

			<li>If your are more than 40 minutes from the nearest medical facility, contact your supervisor to arrange alternative transportation (air ambulance, other emergency services).</li>

			<li>Keep the victim comfortable as best you can while awaiting medical assistance.</li>

			<li>Go with or follow medical personnel to the medical facility.  You will be the liaison between the victim, the medics and the office.</li>

			</ul>

			</div>
        </div>
    </div>
<?php } ?>


<?php if (strpos($form_config, ','."fields19".',') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_risk" >
                    Risk Category<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_risk" class="panel-collapse collapse">
            <div class="panel-body">


					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Risk Category</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_43" value="<?php echo $fields[43]; ?>" class="form-control" />
                    </div>
					</div>


			</div>
        </div>
    </div>
<?php } ?>


<?php if (strpos($form_config, ','."fields20".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_log" >
                    Call-in Log<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_log" class="panel-collapse collapse">
            <div class="panel-body">

                <?php
                $all_task_each = explode('**##**',$all_task);

                $total_count = mb_substr_count($all_task,'**##**');
                if($total_count > 0) {
                    echo "<table class='table table-bordered'>";
                    echo "<tr class='hidden-xs hidden-sm'>
                    <th>Time of Call-in</th>
                    <th>Initials</th>
                    <th>Changes to Plan?</th>";
                }
                for($client_loop=0; $client_loop<=$total_count; $client_loop++) {
                    $task_item = explode('**',$all_task_each[$client_loop]);
                    $task = $task_item[0];
                    $hazard = $task_item[1];
                    $plan = $task_item[2];
					$hazard1 = $task_item[3];

                    if($task != '') {
                        echo '<tr>';
                        echo '<td data-title="Email">' . $task . '</td>';
                        echo '<td data-title="Email">' . $hazard . '</td>';
                        echo '<td data-title="Email">' . $plan.' : '. $hazard1. '</td>';
                        echo '</tr>';
                    }
                }
                echo '</table>';
                ?>
                <div class="additional_hazard clearfix">
                    <div class="row">
                        <div class="col-md-2 col-sm-6 col-xs-6 padded">
                            <p>Time of Call-in</p>
                            <input type="text" name="task[]" class="task_list"/>
                        </div>
                        <div class="col-md-2 col-sm-6 col-xs-6 padded">
                            <p>Initials</p>
                            <input type="text" name="hazard[]" class="task_list"/>
                        </div>
                        <div class="col-md-5 col-sm-6 col-xs-6 padded">
                            <p>Changes to Plan?</p>
                            <select name="plan[]" class="task_list">
                                <option value=""></option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                            <input type="text" name="hazard1[]" class="task_list"/>
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

<?php if (strpos($form_config, ','."fields21".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_c3" >
                    Comments<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_c3" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Comments</label>
                <div class="col-sm-8">
                <textarea name="desc4" rows="3" cols="50" class="form-control"><?php echo $desc4; ?></textarea>
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
                echo '<img src="emergency_response_transportation_plan/download/safety_'.$assign_staff_id.'.png">';
            } ?>

        </div>
    </div>
</div>
<?php $sa_inc++;
    }
} ?>

</div>










