
<?php
/*
Add	Sheet
*/
include_once ('../database_connection.php');
include_once('../tcpdf/tcpdf.php');
require_once('../phpsign/signature-to-image.php');
error_reporting(0);
?>
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

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];

    echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';

    $get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_avs_near_miss WHERE fieldlevelriskid='$formid'"));

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
}
?>

<?php
//$form_config = ','.get_config($dbc, 'hr_field_level_risk_assessment').',';
$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM `hr` WHERE tab LIKE '$tab' AND form='$form'"));
$form_config = ','.$get_field_config['fields'].',';
?>

<h3>Information</h3>
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

<?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
    <h3>Hazard Rating:</h3>
	<input type="radio" <?php if ($hazard_rating == 'Minor') { echo " checked"; } ?>  name="hazard_rating" value="Minor">Minor
	<input type="radio" <?php if ($hazard_rating == 'Serious') { echo " checked"; } ?>  name="hazard_rating" value="Serious">Serious
	<input type="radio" <?php if ($hazard_rating == 'Major') { echo " checked"; } ?> name="hazard_rating" value="Major">Major
	<input type="radio" <?php if ($hazard_rating == 'Catastrophic') { echo " checked"; } ?> name="hazard_rating" value="Catastrophic">Catastrophic
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields5,') !== FALSE) { ?>
	<h3>Action Timeline:</h3>
	<input type="radio" <?php if ($action_timeline == 'Action to be taken within 1 month') { echo " checked"; } ?>  name="action_timeline" value="Action to be taken within 1 month">Action to be taken within 1 month
	<input type="radio" <?php if ($action_timeline == 'Action to be taken within 1 week') { echo " checked"; } ?>  name="action_timeline" value="Action to be taken within 1 week">Action to be taken within 1 week
	<input type="radio" <?php if ($action_timeline == 'Action to be taken immediately') { echo " checked"; } ?>  name="action_timeline" value="Action to be taken immediately">Action to be taken immediately
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields6,') !== FALSE) { ?>
    <h3>Description:</h3>
	  <div class="form-group">
		<label for="first_name[]" class="col-sm-4 control-label">Description of unsafe Acts/Conditions/Practices:</label>
		<div class="col-sm-8">
		  <textarea name="description" rows="5" cols="50" class="form-control"><?php echo $description; ?></textarea>
		</div>
	  </div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields7,') !== FALSE) { ?>
    <h3>Action To Be Taken:<span class="glyphicon glyphicon-plus"></span>
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
    <h3>Action Assigned To:<span class="glyphicon glyphicon-plus"></span>
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
    <h3>Estimated Completion Date:<span class="glyphicon glyphicon-plus"></span>
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
    <h3>Date Completed:<span class="glyphicon glyphicon-plus"></span>
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
    <h3>Analysis Assigned To:<span class="glyphicon glyphicon-plus"></span>
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

<?php if(!empty($_GET['formid'])) {
	$sa = mysqli_query($dbc, "SELECT * FROM hr_attendance WHERE fieldlevelriskid = '$formid' AND hrid='$hrid'");
	$sa_inc=  0;
	while($row_sa = mysqli_fetch_array( $sa )) {
		$assign_staff_sa = $row_sa['assign_staff'];
		$assign_staff_id = $row_sa['hrattid'];
		$assign_staff_done = $row_sa['done'];
		?>
    
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
		<label for="business_street" class="col-sm-4 control-label">Review:</label>
		<div class="col-sm-8">
			<input name="staffcheck_<?php echo $assign_staff_id;?>[]" type="text" class="form-control" />
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
    <?php $sa_inc++;
	}
} ?>