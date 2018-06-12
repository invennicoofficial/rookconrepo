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
$fields[0] = date('Y-m-d');
$contactid = $_SESSION['contactid'];
$fields = '';
$desc = '';
$desc1 = '';
$desc2 = '';
$desc3 = '';
$desc4 = '';
$desc5 = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];
	echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_spill_incident_report WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
	$desc = $get_field_level['desc'];
	$desc1 = $get_field_level['desc1'];
	$desc2 = $get_field_level['desc2'];
	$desc3 = $get_field_level['desc3'];
	$desc4 = $get_field_level['desc4'];
	$desc5 = $get_field_level['desc5'];
    $fields = explode('**FFM**', $get_field_level['fields']);
}

$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$form'"));
$form_config = ','.$get_field_config['fields'].',';
?>

<div class="panel-group" id="accordion2">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info1" >
                    Information<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info1" class="panel-collapse collapse">
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
                    <label for="business_street" class="col-sm-4 control-label">Facility location and type</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_1" value="<?php echo $fields[1]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Individual Reporting the spill / incident</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_2" value="<?php echo $fields[2]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Company representative contacted</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_3" value="<?php echo $fields[3]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Date and time</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_4" value="<?php echo $fields[4]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Customer representative contacted</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_5" value="<?php echo $fields[5]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields7".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Date and time</label>
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_2" >
                    Regulatory agency notified (name, date, time)<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_2" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Regulatory agency notified (name, date, time)</label>
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_des3" >
                    Was the facility Emergency Response Plan activated? Provide details<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_des3" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Was the facility Emergency Response Plan activated? Provide details</label>
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info4" >
                    Date and time of spill / incident<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info4" class="panel-collapse collapse">
            <div class="panel-body">

				<?php if (strpos($form_config, ','."fields10".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Date and time of spill / incident</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_7" value="<?php echo $fields[7]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>


			</div>
        </div>
    </div>

	<?php if (strpos(','.$form_config.',', ',fields11,') !== FALSE) { ?>
	    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_spill" >
                    Type of spill / incident<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_spill" class="panel-collapse collapse">
            <div class="panel-body">

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Gas / Vapour</label>
                <div class="col-sm-8"><input type="checkbox" <?php if ($fields[8]=='Gas / Vapour') { echo " checked"; } ?>  name="fields_8" value="Gas / Vapour"><input name="fields_9" type="text" value="<?php echo $fields[9]; ?>" class="form-control" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Liquid</label>
                <div class="col-sm-8"><input type="checkbox" <?php if ($fields[10]=='Liquid') { echo " checked"; } ?>  name="fields_10" value="Liquid"><input name="fields_11" type="text" value="<?php echo $fields[11]; ?>" class="form-control" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Sludge</label>
                <div class="col-sm-8"><input type="checkbox" <?php if ($fields[12]=='Sludge') { echo " checked"; } ?>  name="fields_12" value="Sludge"><input name="fields_13" type="text" value="<?php echo $fields[13]; ?>" class="form-control" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Solid</label>
                <div class="col-sm-8"><input type="checkbox" <?php if ($fields[14]=='Solid') { echo " checked"; } ?>  name="fields_14" value="Solid"><input name="fields_15" type="text" value="<?php echo $fields[15]; ?>" class="form-control" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Other</label>
                <div class="col-sm-8"><input type="checkbox" <?php if ($fields[16]=='Other') { echo " checked"; } ?>  name="fields_16" value="Other"><input name="fields_17" type="text" value="<?php echo $fields[17]; ?>" class="form-control" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Fire</label>
                <div class="col-sm-8"><input type="checkbox" <?php if ($fields[18]=='Fire') { echo " checked"; } ?>  name="fields_18" value="Fire"><input name="fields_19" type="text" value="<?php echo $fields[19]; ?>" class="form-control" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Explosion</label>
                <div class="col-sm-8"><input type="checkbox" <?php if ($fields[20]=='Explosion') { echo " checked"; } ?>  name="fields_20" value="Explosion"><input name="fields_21" type="text" value="<?php echo $fields[21]; ?>" class="form-control" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Combination / multiple release</label>
                <div class="col-sm-8"><input type="checkbox" <?php if ($fields[22]=='Combination / multiple release') { echo " checked"; } ?>  name="fields_22" value="Combination / multiple release"><input name="fields_23" type="text" value="<?php echo $fields[23]; ?>" class="form-control" />
                </div>
                </div>


			</div>
        </div>
    </div>

	<?php }?>

<?php if (strpos($form_config, ','."fields12".',') !== FALSE) { ?>

	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_specify" >
                    Specify location of spill / incident (include where it traveled)<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_specify" class="panel-collapse collapse">
            <div class="panel-body">

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Specify location of spill / incident (include where it traveled)</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[24] == 'Onsite') { echo " checked"; } ?>  name="fields_24" value="Onsite">Onsite&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[24] == 'Offsite') { echo " checked"; } ?>  name="fields_24" value="Offsite">Offsite&nbsp;&nbsp;
                    <input type="text" name="fields_25" value="<?php echo $fields[25]; ?>"   class="form-control" />
                    </div>
					</div>
 			</div>
        </div>
    </div>

	<?php } ?>

	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_duration" >
                    Duration of  spill / incident (min. / hrs. / days)<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

	   <div id="collapse_duration" class="panel-collapse collapse">
            <div class="panel-body">

				<?php if (strpos($form_config, ','."fields13".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Duration of  spill / incident (min. / hrs. / days)</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_26" value="<?php echo $fields[26]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>


			</div>
        </div>
    </div>

	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_est" >
                    Estimated volume of material lost<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>
       <div id="collapse_est" class="panel-collapse collapse">
            <div class="panel-body">

				<?php if (strpos($form_config, ','."fields14".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Estimated volume of material lost</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_27" value="<?php echo $fields[27]; ?>" class="form-control" />
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_cause" >
                    Cause(s) of spill / incident<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_cause" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Cause(s) of spill / incident</label>
                <div class="col-sm-8">
                <textarea name="desc2" rows="3" cols="50" class="form-control"><?php echo $desc2; ?></textarea>
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_act" >
                    Action taken to contain the spill<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_act" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Action taken to contain the spill</label>
                <div class="col-sm-8">
                <textarea name="desc3" rows="3" cols="50" class="form-control"><?php echo $desc3; ?></textarea>
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_tak" >
                    Action taken to clean up the spill<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_tak" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Action taken to clean up the spill</label>
                <div class="col-sm-8">
                <textarea name="desc4" rows="3" cols="50" class="form-control"><?php echo $desc4; ?></textarea>
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_follow" >
                    Follow up action required (if applicable)<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_follow" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Follow up action required (if applicable)</label>
                <div class="col-sm-8">
                <textarea name="desc5" rows="3" cols="50" class="form-control"><?php echo $desc5; ?></textarea>
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