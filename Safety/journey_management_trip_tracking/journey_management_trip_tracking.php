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
$all_task = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];
	echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_journey_management_trip_tracking WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
	$desc = $get_field_level['desc'];
	$desc1 = $get_field_level['desc1'];
	$desc2 = $get_field_level['desc2'];
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
                    <label for="business_street" class="col-sm-4 control-label">Drivers Name (Employee)</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_0" value="<?php echo $fields[0]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Date</label>
                    <div class="col-sm-8">
                    <input type="text" name="today_date" value="<?php echo $today_date; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Unit Number</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_2" value="<?php echo $fields[2]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Customer</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_3" value="<?php echo $fields[3]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Departure Location</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_4" value="<?php echo $fields[4]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Departure Date / Time</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_5" value="<?php echo $fields[5]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields7".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Customer Contact & Phone Number</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_6" value="<?php echo $fields[6]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields8".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Destination Location</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_7" value="<?php echo $fields[7]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields9".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Est. Arrival Date / Time</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_8" value="<?php echo $fields[8]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>


			</div>
        </div>
    </div>

	<?php if (strpos($form_config, ','."fields10".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_oth" >
                    Other Employee's in vehicle<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_oth" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Other Employee's in vehicle</label>
                <div class="col-sm-8">
                <textarea name="desc" rows="3" cols="50" class="form-control"><?php echo $desc; ?></textarea>
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_trip" >
                    Trip Evaluation<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_trip" class="panel-collapse collapse">
            <div class="panel-body">

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Points allocated on Journey Management - Trip Assessment Form (as per driver filling out form)</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_9" value="<?php echo $fields[9]; ?>" class="form-control" />
                    </div>
					</div>

			</div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos($form_config, ','."fields12".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_oth12" >
                    Actions Taken<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_oth12" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Actions Taken ( as per score above)</label>
                <div class="col-sm-8">
                <textarea name="desc1" rows="3" cols="50" class="form-control"><?php echo $desc1; ?></textarea>
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_scor" >
                    Trip Assessment Form Scores<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_scor" class="panel-collapse collapse">
            <div class="panel-body">

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Trip Assessment Form Scores</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[10] == '1 - 30 or Less Points') { echo " checked"; } ?>  name="fields_10" value="1 - 30 or Less Points">1 - 30 or Less Points&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[10] == '2 - 31 to 70  points') { echo " checked"; } ?>  name="fields_10" value="2 - 31 to 70  points">2 - 31 to 70  points&nbsp;&nbsp;<br>
				<input type="radio" <?php if ($fields[10] == '3 - 71 to 100 points') { echo " checked"; } ?>  name="fields_10" value="3 - 71 to 100 points">3 - 71 to 100 points&nbsp;&nbsp;
				<input type="radio" <?php if ($fields[10] == '4 - > 100 points') { echo " checked"; } ?>  name="fields_10" value="4 - > 100 points">4 - > 100 points&nbsp;&nbsp;
                <input type="text" name="fields_11" value="<?php echo $fields[11]; ?>" class="form-control" style="width:25%;" />
                <br>
                <ul>
                    <li>1 - 30 or Less Points : Proceed and re-evaluate in 3 hours or as necessary</li>
                    <li>2 - 31 to 70  points : Proceed with caution and re-evaluate in 1 1/2 hours</li>
                    <li>3 - 71 to 100 points : Do not proceed until you have contacted your Manager / Supervisor and developed a safe plan.</li>
                    <li>4 - > 100 points : Do not proceed! Manager / Supervisor to have an alternate drive the vehicle to the job site.</li>
                </ul>
                </div>
                </div>

			</div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos($form_config, ','."fields14".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_tracking" >
                    Trip Tracking<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_tracking" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Estimated Travel Time (Hours)</label>
                <div class="col-sm-8">
                <input type="text" name="fields_12" value="<?php echo $fields[12]; ?>" class="form-control" />
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_phone" >
                    Phone Calls as per Sections above (Actual times and conversation)<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_phone" class="panel-collapse collapse">
            <div class="panel-body">

                <?php
                $all_task_each = explode('**##**',$all_task);

                $total_count = mb_substr_count($all_task,'**##**');
                if($total_count > 0) {
                    echo "<table class='table table-bordered'>";
                    echo "<tr class='hidden-xs hidden-sm'>
                    <th>Call #</th>
                    <th>Time</th>
                    <th>Date</th>
                    <th>Conversation</th>";
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
                        <div class="col-md-2 col-sm-6 col-xs-6 padded">
                            <p>Call #</p>
                            <input type="text" name="task[]" class="task_list"/>
                        </div>
                        <div class="col-md-2 col-sm-6 col-xs-6 padded">
                            <p>Time</p>
                            <input type="text" name="hazard[]" class="task_list"/>
                        </div>
                        <div class="col-md-2 col-sm-6 col-xs-6 padded">
                            <p>Date</p>
                            <input type="text" name="level[]" class="task_list"/>
                        </div>
                        <div class="col-md-2 col-sm-6 col-xs-6 padded">
                            <p>Conversation</p>
                            <input type="text" name="plan[]" class="task_list"/>
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

    <?php if (strpos($form_config, ','."fields16".',') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ta" >
                    Trip Assessment(As per Driver's Answers)<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_ta" class="panel-collapse collapse">
            <div class="panel-body">

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">1 : Is this trip Necessary?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[13] == 'Yes') { echo " checked"; } ?>  name="fields_13" value="Yes">Yes (0)&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[13] == 'No') { echo " checked"; } ?>  name="fields_13" value="No">No (50)&nbsp;&nbsp;
                Score&nbsp;&nbsp;<input type="text" name="fields_14" value="<?php echo $fields[14]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">2 : Amount of rest in Last 24 hours?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[15] == '< 4') { echo " checked"; } ?>  name="fields_15" value="< 4"> < 4 (75)&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[15] == '5 to 7') { echo " checked"; } ?>  name="fields_15" value="5 to 7">5 to 7 (45)&nbsp;&nbsp;
                Score&nbsp;&nbsp;<input type="radio" <?php if ($fields[15] == '> 8') { echo " checked"; } ?>  name="fields_15" value="> 8"> > 8 (0)&nbsp;&nbsp;
                <input type="text" name="fields_16" value="<?php echo $fields[16]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">3 : Any alcohol or drugs (prescription included) taken within the last 8 hours?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[17] == 'Yes') { echo " checked"; } ?>  name="fields_17" value="Yes">Yes (100)&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[17] == 'No') { echo " checked"; } ?>  name="fields_17" value="No">No (0)&nbsp;&nbsp;
                Score&nbsp;&nbsp;<input type="text" name="fields_18" value="<?php echo $fields[18]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">4 : Weather</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[19] == 'Good') { echo " checked"; } ?>  name="fields_19" value="Good"> Good (0)&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[19] == 'Poor') { echo " checked"; } ?>  name="fields_19" value="Poor">Poor (25)&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[19] == 'Extremely Poor') { echo " checked"; } ?>  name="fields_19" value="Extremely Poor"> Extremely Poor (50)&nbsp;&nbsp;
                Score&nbsp;&nbsp;<input type="text" name="fields_20" value="<?php echo $fields[20]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">5 : Road Conditions</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[21] == 'Good') { echo " checked"; } ?>  name="fields_21" value="Good"> Good (0)&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[21] == 'Poor') { echo " checked"; } ?>  name="fields_21" value="Poor">Poor (25)&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[21] == 'Extremely Poor') { echo " checked"; } ?>  name="fields_21" value="Extremely Poor"> Extremely Poor (50)&nbsp;&nbsp;
                Score&nbsp;&nbsp;<input type="text" name="fields_22" value="<?php echo $fields[22]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">6 : Time of Travel</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[23] == 'Daylight') { echo " checked"; } ?>  name="fields_23" value="Daylight"> Daylight (0)&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[23] == 'Darkness') { echo " checked"; } ?>  name="fields_23" value="Darkness">Darkness (10)&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[23] == 'Dawn / Dusk') { echo " checked"; } ?>  name="fields_23" value="Dawn / Dusk"> Dawn / Dusk (25)&nbsp;&nbsp;
                Score&nbsp;&nbsp;<input type="text" name="fields_24" value="<?php echo $fields[24]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">7 : Approximate Driving Distance</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[25] == '< 150') { echo " checked"; } ?>  name="fields_25" value="< 150"> < 150 (0)&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[25] == '150 to 300') { echo " checked"; } ?>  name="fields_25" value="150 to 300">150 to 300 (5)&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[25] == '> 300') { echo " checked"; } ?>  name="fields_25" value="> 300"> > 300 (10)&nbsp;&nbsp;
                Score&nbsp;&nbsp;<input type="text" name="fields_26" value="<?php echo $fields[26]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">8 : Traveling as a team?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[27] == 'Yes') { echo " checked"; } ?>  name="fields_27" value="Yes">Yes (0)&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[27] == 'No') { echo " checked"; } ?>  name="fields_27" value="No">No (10)&nbsp;&nbsp;
                Score&nbsp;&nbsp;<input type="text" name="fields_28" value="<?php echo $fields[28]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">9 : Trip Type</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[29] == 'Inbound (Personal)') { echo " checked"; } ?>  name="fields_29" value="Inbound (Personal)"> Inbound (Personal) (10)&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[29] == 'Outbound') { echo " checked"; } ?>  name="fields_29" value="Outbound">Outbound (0)&nbsp;&nbsp;
                Score&nbsp;&nbsp;<input type="text" name="fields_30" value="<?php echo $fields[30]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">10 : Is there a rest stop at the end of the trip?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[31] == 'No') { echo " checked"; } ?>  name="fields_31" value="No"> No (10)&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[31] == '< 2 Hours') { echo " checked"; } ?>  name="fields_31" value="< 2 Hours"> < 2 Hours (10)&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[31] == '> 2 Hours') { echo " checked"; } ?>  name="fields_31" value="> 2 Hours"> > 2 Hours (0)&nbsp;&nbsp;
                Score&nbsp;&nbsp;<input type="text" name="fields_32" value="<?php echo $fields[32]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">11 : Is the vehicle safe to drive? Have you performed a Pre-Trip Inspection?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[33] == 'Yes') { echo " checked"; } ?>  name="fields_33" value="Yes">Yes (0)&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[33] == 'No') { echo " checked"; } ?>  name="fields_33" value="No">No (100)&nbsp;&nbsp;
                Score&nbsp;&nbsp;<input type="text" name="fields_34" value="<?php echo $fields[34]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">12 : Is there a Journey Plan for your trip?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[35] == 'Yes') { echo " checked"; } ?>  name="fields_35" value="Yes">Yes (0)&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[35] == 'No') { echo " checked"; } ?>  name="fields_35" value="No">No (100)&nbsp;&nbsp;
                Score&nbsp;&nbsp;<input type="text" name="fields_36" value="<?php echo $fields[36]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">13 : Are you houred out as per the Hours of Service Regulations?(Commercial drivers only)</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[37] == 'Yes') { echo " checked"; } ?>  name="fields_37" value="Yes">Yes (100)&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[37] == 'No') { echo " checked"; } ?>  name="fields_37" value="No">No (0)&nbsp;&nbsp;
                Score&nbsp;&nbsp;<input type="text" name="fields_38" value="<?php echo $fields[38]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">14 : Do you know the Hazards associated with driving?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[39] == 'Yes') { echo " checked"; } ?>  name="fields_39" value="Yes">Yes (0)&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[39] == 'No') { echo " checked"; } ?>  name="fields_39" value="No">No (100)&nbsp;&nbsp;
                Score&nbsp;&nbsp;<input type="text" name="fields_40" value="<?php echo $fields[40]; ?>" class="form-control" style="width:25%;" />
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_personnel" >
                    Personnel<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_personnel" class="panel-collapse collapse">
            <div class="panel-body">
	            <h4>Areas of Concern</h4>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">1 : Are the Employees able to do the job safely?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[41] == 'Yes') { echo " checked"; } ?>  name="fields_41" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[41] == 'No') { echo " checked"; } ?>  name="fields_41" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_42" value="<?php echo $fields[42]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">2 : Do they have the experience, skills and training to complete all aspects of the job?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[43] == 'Yes') { echo " checked"; } ?>  name="fields_43" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[43] == 'No') { echo " checked"; } ?>  name="fields_43" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_44" value="<?php echo $fields[44]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">3 : Do they have the necessary qualifications, certificates and licenses?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[45] == 'Yes') { echo " checked"; } ?>  name="fields_45" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[45] == 'No') { echo " checked"; } ?>  name="fields_45" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_46" value="<?php echo $fields[46]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">4 : Are they physically and mentally capable of doing the job? </label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[47] == 'Yes') { echo " checked"; } ?>  name="fields_47" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[47] == 'No') { echo " checked"; } ?>  name="fields_47" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_48" value="<?php echo $fields[48]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">5 : Have they been briefed on action plans, job hazards and job requirements?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[49] == 'Yes') { echo " checked"; } ?>  name="fields_49" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[49] == 'No') { echo " checked"; } ?>  name="fields_49" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_50" value="<?php echo $fields[50]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">6 : Have the drivers been provided maps and directions? </label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[51] == 'Yes') { echo " checked"; } ?>  name="fields_51" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[51] == 'No') { echo " checked"; } ?>  name="fields_51" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_52" value="<?php echo $fields[52]; ?>" class="form-control" style="width:25%;" />
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_vech" >
                    Vehicle/ Equipment<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_vech" class="panel-collapse collapse">
            <div class="panel-body">

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">1 : Are the vehicles/ equipment suitable for the job? </label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[53] == 'Yes') { echo " checked"; } ?>  name="fields_53" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[53] == 'No') { echo " checked"; } ?>  name="fields_53" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_54" value="<?php echo $fields[54]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">2 : Does the site require specific sIfafety equipment?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[55] == 'Yes') { echo " checked"; } ?>  name="fields_55" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[55] == 'No') { echo " checked"; } ?>  name="fields_55" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_56" value="<?php echo $fields[56]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">3 : Will they create additional hazards?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[57] == 'Yes') { echo " checked"; } ?>  name="fields_57" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[57] == 'No') { echo " checked"; } ?>  name="fields_57" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_58" value="<?php echo $fields[58]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">4 : Are they in operational condition? </label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[59] == 'Yes') { echo " checked"; } ?>  name="fields_59" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[59] == 'No') { echo " checked"; } ?>  name="fields_59" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_60" value="<?php echo $fields[60]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">5 : Have they been inspected and maintained properly?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[61] == 'Yes') { echo " checked"; } ?>  name="fields_61" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[61] == 'No') { echo " checked"; } ?>  name="fields_61" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_62" value="<?php echo $fields[62]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">6 : Is all of the necessary documentation available? </label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[63] == 'Yes') { echo " checked"; } ?>  name="fields_63" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[63] == 'No') { echo " checked"; } ?>  name="fields_63" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_64" value="<?php echo $fields[64]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">7 : Is all of the support/ emergency equipment (communication equipment, safety equipment, fire protection, personal protection, first aid, warning devices) available and in operational condition?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[65] == 'Yes') { echo " checked"; } ?>  name="fields_65" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[65] == 'No') { echo " checked"; } ?>  name="fields_65" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_66" value="<?php echo $fields[66]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">8 : Is the vehicle equipped with winches, slings or chains?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[67] == 'Yes') { echo " checked"; } ?>  name="fields_67" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[67] == 'No') { echo " checked"; } ?>  name="fields_67" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_68" value="<?php echo $fields[68]; ?>" class="form-control" style="width:25%;" />
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_schedule" >
                    Schedule<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_schedule" class="panel-collapse collapse">
            <div class="panel-body">

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">1 : Has enough time been allotted for loading and travel? </label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[69] == 'Yes') { echo " checked"; } ?>  name="fields_69" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[69] == 'No') { echo " checked"; } ?>  name="fields_69" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_70" value="<?php echo $fields[70]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">2 : Has enough time been allotted for adverse road and weather conditions? </label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[71] == 'Yes') { echo " checked"; } ?>  name="fields_71" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[71] == 'No') { echo " checked"; } ?>  name="fields_71" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_72" value="<?php echo $fields[72]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">3 : Has time been allotted for Weigh Station stops?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[73] == 'Yes') { echo " checked"; } ?>  name="fields_73" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[73] == 'No') { echo " checked"; } ?>  name="fields_73" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_74" value="<?php echo $fields[74]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">4 : Has consideration been given to the drivers hours of service requirements and log book status? </label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[75] == 'Yes') { echo " checked"; } ?>  name="fields_75" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[75] == 'No') { echo " checked"; } ?>  name="fields_75" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_76" value="<?php echo $fields[76]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">5 : Has consideration been given for rest breaks, meals, vehicle inspections and refueling? </label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[77] == 'Yes') { echo " checked"; } ?>  name="fields_77" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[77] == 'No') { echo " checked"; } ?>  name="fields_77" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_78" value="<?php echo $fields[78]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">6 : Has consideration been given to time of day to maximize driving in daylight and minimize driving in darkness? </label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[79] == 'Yes') { echo " checked"; } ?>  name="fields_79" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[79] == 'No') { echo " checked"; } ?>  name="fields_79" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_80" value="<?php echo $fields[80]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">7 : Have departure times, estimated arrival times and routes been communicated and confirmed by all team members?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[81] == 'Yes') { echo " checked"; } ?>  name="fields_81" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[81] == 'No') { echo " checked"; } ?>  name="fields_81" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_82" value="<?php echo $fields[82]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">8 : Are all involved capable of making a safe return trip?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[83] == 'Yes') { echo " checked"; } ?>  name="fields_83" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[83] == 'No') { echo " checked"; } ?>  name="fields_83" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_84" value="<?php echo $fields[84]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">9 : Has adequate time been allowed for required maintenance between trips? </label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[85] == 'Yes') { echo " checked"; } ?>  name="fields_85" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[85] == 'No') { echo " checked"; } ?>  name="fields_85" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_86" value="<?php echo $fields[86]; ?>" class="form-control" style="width:25%;" />
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_route" >
                    Route<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_route" class="panel-collapse collapse">
            <div class="panel-body">

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">1 : Are up to date maps available to facilitate appropriate routes? </label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[87] == 'Yes') { echo " checked"; } ?>  name="fields_87" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[87] == 'No') { echo " checked"; } ?>  name="fields_87" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_88" value="<?php echo $fields[88]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">2 : Has the route been carefully planned to eliminate unsuitable roads?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[89] == 'Yes') { echo " checked"; } ?>  name="fields_89" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[89] == 'No') { echo " checked"; } ?>  name="fields_89" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_90" value="<?php echo $fields[90]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">3 : Are selected routes compatible with the vehicle and load being transported? </label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[91] == 'Yes') { echo " checked"; } ?>  name="fields_91" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[91] == 'No') { echo " checked"; } ?>  name="fields_91" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_92" value="<?php echo $fields[92]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">4 : Do selected routes compromise Company policy, procedures or any legislation? </label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[93] == 'Yes') { echo " checked"; } ?>  name="fields_93" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[93] == 'No') { echo " checked"; } ?>  name="fields_93" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_94" value="<?php echo $fields[94]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">5 : Have you ascertained the road type?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[95] == 'Yes') { echo " checked"; } ?>  name="fields_95" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[95] == 'No') { echo " checked"; } ?>  name="fields_95" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_96" value="<?php echo $fields[96]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">6 : Have you communicated accurate site and location directions to each driver? </label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[97] == 'Yes') { echo " checked"; } ?>  name="fields_97" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[97] == 'No') { echo " checked"; } ?>  name="fields_97" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_98" value="<?php echo $fields[98]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">7 : Have you communicated road hazards and precautions to each driver? </label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[99] == 'Yes') { echo " checked"; } ?>  name="fields_99" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[99] == 'No') { echo " checked"; } ?>  name="fields_99" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_100" value="<?php echo $fields[100]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">8 : Have you considered and communicated convoy procedures?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[101] == 'Yes') { echo " checked"; } ?>  name="fields_101" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[101] == 'No') { echo " checked"; } ?>  name="fields_101" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_102" value="<?php echo $fields[102]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">9 : Have dangerous goods (T.D.G.) routes been considered and selected if applicable? </label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[103] == 'Yes') { echo " checked"; } ?>  name="fields_103" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[103] == 'No') { echo " checked"; } ?>  name="fields_103" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_104" value="<?php echo $fields[104]; ?>" class="form-control" style="width:25%;" />
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_pot" >
                    Potential Journey Hazards<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_pot" class="panel-collapse collapse">
            <div class="panel-body">

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">1 : Road conditions (rain, mud, snow, icy, construction) </label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[105] == 'Yes') { echo " checked"; } ?>  name="fields_105" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[105] == 'No') { echo " checked"; } ?>  name="fields_105" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_106" value="<?php echo $fields[106]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">2 : Driver fatigue</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[107] == 'Yes') { echo " checked"; } ?>  name="fields_107" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[107] == 'No') { echo " checked"; } ?>  name="fields_107" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_108" value="<?php echo $fields[108]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">3 : Weather conditions</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[109] == 'Yes') { echo " checked"; } ?>  name="fields_109" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[109] == 'No') { echo " checked"; } ?>  name="fields_109" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_110" value="<?php echo $fields[110]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">4 : Visibility/ vision (fog, smoke, dust)</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[111] == 'Yes') { echo " checked"; } ?>  name="fields_111" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[111] == 'No') { echo " checked"; } ?>  name="fields_111" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_112" value="<?php echo $fields[112]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">5 : Unusual load characteristics </label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[113] == 'Yes') { echo " checked"; } ?>  name="fields_113" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[113] == 'No') { echo " checked"; } ?>  name="fields_113" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_114" value="<?php echo $fields[114]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">6 : Traffic </label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[115] == 'Yes') { echo " checked"; } ?>  name="fields_115" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[115] == 'No') { echo " checked"; } ?>  name="fields_115" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_116" value="<?php echo $fields[116]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">7 : Equipment condition </label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[117] == 'Yes') { echo " checked"; } ?>  name="fields_117" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[117] == 'No') { echo " checked"; } ?>  name="fields_117" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_118" value="<?php echo $fields[118]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">8 : Other potential hazards (List):</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[119] == 'Yes') { echo " checked"; } ?>  name="fields_119" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[119] == 'No') { echo " checked"; } ?>  name="fields_119" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_120" value="<?php echo $fields[120]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>


			</div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos($form_config, ','."fields22".',') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_pal" >
                    Emergency Response Planning<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_pal" class="panel-collapse collapse">
            <div class="panel-body">

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">1 : Have hazards and controls been identified? </label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[121] == 'Yes') { echo " checked"; } ?>  name="fields_121" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[121] == 'No') { echo " checked"; } ?>  name="fields_121" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_122" value="<?php echo $fields[122]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">2 : Are drivers and passengers trained in injury, incident or emergency response?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[123] == 'Yes') { echo " checked"; } ?>  name="fields_123" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[123] == 'No') { echo " checked"; } ?>  name="fields_123" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_124" value="<?php echo $fields[124]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">3 : Are environmental spill kits required and available? </label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[125] == 'Yes') { echo " checked"; } ?>  name="fields_125" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[125] == 'No') { echo " checked"; } ?>  name="fields_125" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_126" value="<?php echo $fields[126]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">4 : Is other emergency response equipment available (survival kit)?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[127] == 'Yes') { echo " checked"; } ?>  name="fields_127" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[127] == 'No') { echo " checked"; } ?>  name="fields_127" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_128" value="<?php echo $fields[128]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">5 : Is emergency contact information available? </label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[129] == 'Yes') { echo " checked"; } ?>  name="fields_129" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[129] == 'No') { echo " checked"; } ?>  name="fields_129" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_130" value="<?php echo $fields[130]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">6 : Is an adequate communication system available?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[131] == 'Yes') { echo " checked"; } ?>  name="fields_131" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[131] == 'No') { echo " checked"; } ?>  name="fields_131" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_132" value="<?php echo $fields[132]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">7 : Are drivers and passengers trained in first aid? </label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[133] == 'Yes') { echo " checked"; } ?>  name="fields_133" value="Yes">Yes&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[133] == 'No') { echo " checked"; } ?>  name="fields_133" value="No">No&nbsp;&nbsp;
                <input type="text" name="fields_134" value="<?php echo $fields[134]; ?>" class="form-control" style="width:25%;" />
                </div>
                </div>


			</div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos($form_config, ','."fields23".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_oth35" >
                    List corrective actions identified above<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_oth35" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">List corrective actions identified above</label>
                <div class="col-sm-8">
                <textarea name="desc2" rows="3" cols="50" class="form-control"><?php echo $desc2; ?></textarea>
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