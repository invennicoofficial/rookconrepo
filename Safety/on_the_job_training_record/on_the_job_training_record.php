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
$desc1 = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];
	echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_on_the_job_training_record WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
	$desc = $get_field_level['desc'];
	$desc1 = $get_field_level['desc1'];
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
                    <label for="business_street" class="col-sm-4 control-label">Location</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_0" value="<?php echo $fields[0]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>
				<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Date</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_1" value="<?php echo $fields[1]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>
				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Employee</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_2" value="<?php echo $fields[2]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>
				<?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Position</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_3" value="<?php echo $fields[3]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>
				<?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Task to be performed</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_4" value="<?php echo $fields[4]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>
				<?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Reason for training</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[5] == 'New Worker') { echo " checked"; } ?>  name="fields_5" value="New Worker">New Worker&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[5] == 'Refresher') { echo " checked"; } ?>  name="fields_5" value="Refresher">Refresher&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[5] == 'New Task/Procedure') { echo " checked"; } ?>  name="fields_5" value="New Task/Procedure">New Task/Procedure&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[5] == 'Incident Follow-Up') { echo " checked"; } ?>  name="fields_5" value="Incident Follow-Up">Incident Follow-Up&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[5] == 'Other') { echo " checked"; } ?>  name="fields_5" value="Other">Other&nbsp;&nbsp;
				<input type="text" name="fields_6" value="<?php echo $fields[6]; ?>"   class="form-control" />
                </div>
				</div>
                <?php } ?>

			</div>
        </div>
    </div>

    <?php if (strpos($form_config, ','."fields7".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_train" >
                    Training provided<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_train" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Training provided</label>
                <div class="col-sm-8">
                <textarea name="desc" rows="3" cols="50" class="form-control"><?php echo $desc; ?></textarea>
                </div>
                </div>

			</div>
        </div>
    </div>
    <?php } ?>

	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_trainer" >
                    Trainer Information<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_trainer" class="panel-collapse collapse">
            <div class="panel-body">


				<?php if (strpos($form_config, ','."fields8".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Trainer</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_7" value="<?php echo $fields[7]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields9".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Date training provided</label>
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_task" >
                    Task to be Evaluated<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_task" class="panel-collapse collapse">
            <div class="panel-body">


				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Did the employee describe the process for performing the task?  Interview or hands-on observation? (check one)</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[9] == 'Satisfactory') { echo " checked"; } ?>  name="fields_9" value="Satisfactory">Satisfactory&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[9] == 'Unsatisfactory') { echo " checked"; } ?>  name="fields_9" value="Unsatisfactory">Unsatisfactory&nbsp;&nbsp;
				<input type="text" name="fields_10" value="<?php echo $fields[10]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Did the employee describe any unique hazards associated with the performance of the task?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[11] == 'Satisfactory') { echo " checked"; } ?>  name="fields_11" value="Satisfactory">Satisfactory&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[11] == 'Unsatisfactory') { echo " checked"; } ?>  name="fields_11" value="Unsatisfactory">Unsatisfactory&nbsp;&nbsp;
				<input type="text" name="fields_12" value="<?php echo $fields[12]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Did the employee identify any unique personal protective equipment (i.e. gloves, respirator) required for this task, or any unique equipment (i.e. gas detector or tools) required for this task?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[13] == 'Satisfactory') { echo " checked"; } ?>  name="fields_13" value="Satisfactory">Satisfactory&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[13] == 'Unsatisfactory') { echo " checked"; } ?>  name="fields_13" value="Unsatisfactory">Unsatisfactory&nbsp;&nbsp;
				<input type="text" name="fields_14" value="<?php echo $fields[14]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Was the task performed in the correct sequence? (this may be N/A in some situations)</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[15] == 'Satisfactory') { echo " checked"; } ?>  name="fields_15" value="Satisfactory">Satisfactory&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[15] == 'Unsatisfactory') { echo " checked"; } ?>  name="fields_15" value="Unsatisfactory">Unsatisfactory&nbsp;&nbsp;
				<input type="text" name="fields_16" value="<?php echo $fields[16]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Did the employee describe the significant details of the step clearly and the consequences if certain steps were not performed correctly?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[17] == 'Satisfactory') { echo " checked"; } ?>  name="fields_17" value="Satisfactory">Satisfactory&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[17] == 'Unsatisfactory') { echo " checked"; } ?>  name="fields_17" value="Unsatisfactory">Unsatisfactory&nbsp;&nbsp;
				<input type="text" name="fields_18" value="<?php echo $fields[18]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Was all personal protective equipment used appropriately and in accordance with company and legislated policies/regulations?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[19] == 'Satisfactory') { echo " checked"; } ?>  name="fields_19" value="Satisfactory">Satisfactory&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[19] == 'Unsatisfactory') { echo " checked"; } ?>  name="fields_19" value="Unsatisfactory">Unsatisfactory&nbsp;&nbsp;
				<input type="text" name="fields_20" value="<?php echo $fields[20]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Was PPE training (use/maintenance)  included appropriate to the task?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[21] == 'Satisfactory') { echo " checked"; } ?>  name="fields_21" value="Satisfactory">Satisfactory&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[21] == 'Unsatisfactory') { echo " checked"; } ?>  name="fields_21" value="Unsatisfactory">Unsatisfactory&nbsp;&nbsp;
				<input type="text" name="fields_22" value="<?php echo $fields[22]; ?>"   class="form-control" />
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Did the practices you observed comply with all of the applicable procedures, work aids, codes of practice etc.?</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[23] == 'Satisfactory') { echo " checked"; } ?>  name="fields_23" value="Satisfactory">Satisfactory&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[23] == 'Unsatisfactory') { echo " checked"; } ?>  name="fields_23" value="Unsatisfactory">Unsatisfactory&nbsp;&nbsp;
				<input type="text" name="fields_24" value="<?php echo $fields[24]; ?>"   class="form-control" />
                </div>
				</div>


 			</div>
        </div>
    </div>
    <?php }?>

    <?php if (strpos($form_config, ','."fields11".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_com" >
                    Comments<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_com" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Comments</label>
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ad" >
                    Additional Training Requirements<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_ad" class="panel-collapse collapse">
            <div class="panel-body">


				<?php if (strpos($form_config, ','."fields12".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Additional Training Requirements</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_25" value="<?php echo $fields[25]; ?>" class="form-control" />
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