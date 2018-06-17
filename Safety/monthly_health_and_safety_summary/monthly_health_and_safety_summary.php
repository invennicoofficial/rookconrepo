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
$period_ending = '';
$summary_type = '';
$workers = '';
$comp_orent = '';
$toolbox_meeting = '';
$conducetd_number = '';
$per_attendance = '';
$inspection_schd = '';
$comp_num = '';
$unsafe_acts = '';
$corrected_num = '';
$outstanding_num = '';
$incident_reported = '';
$damage_only = '';
$injury_only = '';
$injuty_and_damage = '';
$vehicle_accident = '';
$no_loss = '';
$comments = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];

    echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_monthly_health_and_safety_summary WHERE fieldlevelriskid='$formid'"));

    $today_date = date('Y-m-d');
    $contactid = $_SESSION['contactid'];
    $period_ending = $get_field_level['period_ending'];
    $summary_type = $get_field_level['summary_type'];
    $workers = $get_field_level['workers'];
    $comp_orent = $get_field_level['comp_orent'];
    $toolbox_meeting = $get_field_level['toolbox_meeting'];
    $conducetd_number = $get_field_level['conducetd_number'];
    $per_attendance = $get_field_level['per_attendance'];
    $inspection_schd = $get_field_level['inspection_schd'];
    $comp_num = $get_field_level['comp_num'];
    $unsafe_acts = $get_field_level['unsafe_acts'];
    $corrected_num = $get_field_level['corrected_num'];
    $outstanding_num = $get_field_level['outstanding_num'];
    $incident_reported = $get_field_level['incident_reported'];
    $damage_only = $get_field_level['damage_only'];
    $injury_only = $get_field_level['injury_only'];
    $injuty_and_damage = $get_field_level['injuty_and_damage'];
    $vehicle_accident = $get_field_level['vehicle_accident'];
    $no_loss = $get_field_level['no_loss'];
    $comments = $get_field_level['comments'];
    
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
                <label for="business_street" class="col-sm-4 control-label">For the Period Ending:</label>
                <div class="col-sm-8">
                <input type="text" name="period_ending" value="<?php echo $period_ending; ?>" class="form-control" />
                </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Summary Type:</label>
				<div class="col-sm-8">
				<input type="radio" <?php if ($summary_type == 'Monthly') { echo " checked"; } ?>  name="summary_type" value="Monthly">Monthly
				<input type="radio" <?php if ($summary_type == 'Yearly') { echo " checked"; } ?>  name="summary_type" value="Yearly">Yearly
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
                    Summary<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info1" class="panel-collapse collapse">
            <div class="panel-body">

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
                    <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Number of workers hired:</label>
                    <div class="col-sm-8">
                    <input type="text" name="workers" value="<?php echo $workers; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
                    <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Number completed orientations:</label>
                    <div class="col-sm-8">
                    <input type="text" name="comp_orent" value="<?php echo $comp_orent; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
                    <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Number of Tool Box Meetings scheduled:</label>
                    <div class="col-sm-8">
                    <input type="text" name="toolbox_meeting" value="<?php echo $toolbox_meeting; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
                    <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Number conducted:</label>
                    <div class="col-sm-8">
                    <input type="text" name="conducetd_number" value="<?php echo $conducetd_number; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields7".',') !== FALSE) { ?>
                    <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Percentage attendance:</label>
                    <div class="col-sm-8">
                    <input type="text" name="per_attendance" value="<?php echo $per_attendance; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields8".',') !== FALSE) { ?>
                    <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Number of Formal Inspections scheduled:</label>
                    <div class="col-sm-8">
                    <input type="text" name="inspection_schd" value="<?php echo $inspection_schd; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields9".',') !== FALSE) { ?>
                    <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Number completed:</label>
                    <div class="col-sm-8">
                    <input type="text" name="comp_num" value="<?php echo $comp_num; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields10".',') !== FALSE) { ?>
                    <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Total Unsafe Acts Identified:</label>
                    <div class="col-sm-8">
                    <input type="text" name="unsafe_acts" value="<?php echo $unsafe_acts; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields11".',') !== FALSE) { ?>
                    <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Number Corrected:</label>
                    <div class="col-sm-8">
                    <input type="text" name="corrected_num" value="<?php echo $corrected_num; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields12".',') !== FALSE) { ?>
                    <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Number Outstanding:</label>
                    <div class="col-sm-8">
                    <input type="text" name="outstanding_num" value="<?php echo $outstanding_num; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields13".',') !== FALSE) { ?>
                    <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Number of Reported Incidents:</label>
                    <div class="col-sm-8">
                    <input type="text" name="incident_reported" value="<?php echo $incident_reported; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields14".',') !== FALSE) { ?>
                    <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Damage only:</label>
                    <div class="col-sm-8">
                    <input type="text" name="damage_only" value="<?php echo $damage_only; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields15".',') !== FALSE) { ?>
                    <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Injury only:</label>
                    <div class="col-sm-8">
                    <input type="text" name="injury_only" value="<?php echo $injury_only; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields16".',') !== FALSE) { ?>
                    <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Injury and damage:</label>
                    <div class="col-sm-8">
                    <input type="text" name="injuty_and_damage" value="<?php echo $injuty_and_damage; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields17".',') !== FALSE) { ?>
                    <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Vehicle Accident:</label>
                    <div class="col-sm-8">
                    <input type="text" name="vehicle_accident" value="<?php echo $vehicle_accident; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields18".',') !== FALSE) { ?>
                    <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">No-loss:</label>
                    <div class="col-sm-8">
                    <input type="text" name="no_loss" value="<?php echo $no_loss; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>


			</div>
        </div>
    </div>

    <?php if (strpos(','.$form_config.',', ',fields19,') !== FALSE) { ?>
    <div class="panel panel-default">
	    <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info2" >
                    Comments<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info2" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Comments:</label>
                    <div class="col-sm-8">
                      <textarea name="comments" rows="5" cols="50" class="form-control"><?php echo $comments; ?></textarea>
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
                echo '<img src="monthly_health_and_safety_summary/download/safety_'.$assign_staff_id.'.png">';
            } ?>

        </div>
    </div>
</div>
<?php $sa_inc++;
    }
} ?>
</div>