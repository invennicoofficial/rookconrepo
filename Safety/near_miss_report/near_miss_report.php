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
$location = '';
$hazard_rating = '';
$action_timeline = '';
$description = '';
$action = '';
$action_to = '';
$est_comp = '';
$date_comp = '';
$analysis_to = '';
$fields = '';
$desc = '';
$desc1 = '';
$desc2 = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];

    echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';

    $get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_near_miss_report WHERE fieldlevelriskid='$formid'"));

    $today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
    $location = $get_field_level['location'];
    $hazard_rating = $get_field_level['hazard_rating'];
    $action_timeline = $get_field_level['action_timeline'];

    $description = $get_field_level['description'];
    $action = $get_field_level['action'];
    $action_to = $get_field_level['action_to'];
    $est_comp = $get_field_level['est_comp'];
    $date_comp = $get_field_level['date_comp'];
    $analysis_to = $get_field_level['analysis_to'];
	$desc = $get_field_level['desc'];
	$desc1 = $get_field_level['desc1'];
	$desc2 = $get_field_level['desc2'];
	$fields = explode('**FFM**', $get_field_level['fields']);
    
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
                    <label for="business_street" class="col-sm-4 control-label">Date:</label>
                    <div class="col-sm-8">
                        <input type="text" name="today_date" value="<?php echo $today_date; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

			    <?php if (strpos($form_config, ','."fields18".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Time:</label>
                    <div class="col-sm-8">
                        <input type="text" name="fields_3" value="<?php echo $fields[3]; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

			    <?php if (strpos($form_config, ','."fields19".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Hazard ID:</label>
                    <div class="col-sm-8">
                        <input type="text" name="fields_4" value="<?php echo $fields[4]; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Location:</label>
                    <div class="col-sm-8">
                        <input type="text" name="location" value="<?php echo $location; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

                <?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Reported By:</label>
                    <div class="col-sm-8">
                        <input type="text" name="contactid" value="<?php echo $contactid; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields12".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Near Miss:</label>
                    <div class="col-sm-8">
                        <input type="text" name="fields_0" value="<?php echo $fields[0]; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields14".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Supervisor:</label>
                    <div class="col-sm-8">
                        <input type="text" name="fields_1" value="<?php echo $fields[1]; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

			</div>
        </div>
    </div>

    <?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info1" >
                    Hazard Rating<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info1" class="panel-collapse collapse">
            <div class="panel-body">
                <input type="radio" <?php if ($hazard_rating == 'Minor') { echo " checked"; } ?>  name="hazard_rating" value="Minor">Minor
                <input type="radio" <?php if ($hazard_rating == 'Serious') { echo " checked"; } ?>  name="hazard_rating" value="Serious">Serious
                <input type="radio" <?php if ($hazard_rating == 'Major') { echo " checked"; } ?> name="hazard_rating" value="Major">Major
                <input type="radio" <?php if ($hazard_rating == 'Catastrophic') { echo " checked"; } ?> name="hazard_rating" value="Catastrophic">Catastrophic

			</div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields5,') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info2" >
                    Action Timeline<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info2" class="panel-collapse collapse">
            <div class="panel-body">
                <input type="radio" <?php if ($action_timeline == 'Action to be taken within 1 month') { echo " checked"; } ?>  name="action_timeline" value="Action to be taken within 1 month">Action to be taken within 1 month
                <input type="radio" <?php if ($action_timeline == 'Action to be taken within 1 week') { echo " checked"; } ?>  name="action_timeline" value="Action to be taken within 1 week">Action to be taken within 1 week
                <input type="radio" <?php if ($action_timeline == 'Action to be taken immediately') { echo " checked"; } ?>  name="action_timeline" value="Action to be taken immediately">Action to be taken immediately
			</div>
        </div>
    </div>
     <?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields6,') !== FALSE) { ?>
    <div class="panel panel-default">
	    <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info3" >
                    Description<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info3" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Description of unsafe Acts/Conditions/Practices:</label>
                    <div class="col-sm-8">
                      <textarea name="description" rows="5" cols="50" class="form-control"><?php echo $description; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields7,') !== FALSE) { ?>
    <div class="panel panel-default">
	    <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info4" >
                    Action To Be Taken<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info4" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Action To Be Taken:</label>
                    <div class="col-sm-8">
                      <textarea name="action" rows="5" cols="50" class="form-control"><?php echo $action; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos($form_config, ','."fields8".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info5" >
                    Action Assigned To<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info5" class="panel-collapse collapse">
            <div class="panel-body">

               <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Action Assigned To:</label>
                <div class="col-sm-8">
                    <input type="text" name="action_to" value="<?php echo $action_to; ?>" class="form-control" />
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info6" >
                    Estimated Completion Date<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info6" class="panel-collapse collapse">
            <div class="panel-body">
               <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Estimated Completion Date:</label>
                <div class="col-sm-8">
                    <input type="text" name="est_comp" value="<?php echo $est_comp; ?>" class="datepicker" />
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info7" >
                    Date Completed<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info7" class="panel-collapse collapse">
            <div class="panel-body">
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Date Completion:</label>
                    <div class="col-sm-8">
                        <input name="date_comp" type="text" class="datepicker" value="<?php echo $date_comp; ?>"></p>
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info8" >
                    Analysis Assigned To<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info8" class="panel-collapse collapse">
            <div class="panel-body">
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Invenstigation/Root Cause Analysis Assigned To:</label>
                    <div class="col-sm-8">
                        <input type="text" name="analysis_to" value="<?php echo $analysis_to; ?>" class="form-control" />
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_decs" >
                    Hazardous Condition or Procedure<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_decs" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Hazardous Condition or Procedure:</label>
                    <div class="col-sm-8">
                      <textarea name="desc" rows="5" cols="50" class="form-control"><?php echo $desc; ?></textarea>
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_decs1" >
                    Action Taken<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_decs1" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Action Taken:</label>
                    <div class="col-sm-8">
                      <textarea name="desc1" rows="5" cols="50" class="form-control"><?php echo $desc1; ?></textarea>
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_decs21" >
                    Cause(s)<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_decs21" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Cause(s):</label>
                    <div class="col-sm-8">
                      <textarea name="desc2" rows="5" cols="50" class="form-control"><?php echo $desc2; ?></textarea>
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info22" >
                    Corrective Action Taken by Whom<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info22" class="panel-collapse collapse">
            <div class="panel-body">

                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Corrective Action Taken by Whom:</label>
                    <div class="col-sm-8">
                        <input type="text" name="fields_5" value="<?php echo $fields[5]; ?>" class="form-control" />
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info9" >
                    Date Hazardous Conditions or Procedures Corrected<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info9" class="panel-collapse collapse">
            <div class="panel-body">

                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Date Hazardous Conditions or Procedures Corrected:</label>
                    <div class="col-sm-8">
                        <input type="text" name="fields_2" value="<?php echo $fields[2]; ?>" class="datepicker" />
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

               <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Review:</label>
                <div class="col-sm-8">
                    <input name="staffcheck_<?php echo $assign_staff_id;?>[]" type="text" class="form-control" />
                </div>
              </div>

            <?php $output_name = 'sign_'.$assign_staff_id;
            include('../phpsign/sign_multiple.php'); ?>

            <?php } else {
                echo '<img src="near_miss_report/download/safety_'.$assign_staff_id.'.png">';
            } ?>

            </div>
        </div>
    </div>
    <?php $sa_inc++;
        }
    } ?>

</div>