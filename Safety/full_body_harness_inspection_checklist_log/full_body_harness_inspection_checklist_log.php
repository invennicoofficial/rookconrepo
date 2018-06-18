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
$desc2 = '';
$desc3 = '';
$desc4 = '';
$desc5 = '';
$desc6 = '';
$desc7 = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];
	echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_full_body_harness_inspection_checklist_log WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
	$desc = $get_field_level['desc'];
	$desc1 = $get_field_level['desc1'];
	$desc2 = $get_field_level['desc2'];
	$desc3 = $get_field_level['desc3'];
	$desc4 = $get_field_level['desc4'];
	$desc5 = $get_field_level['desc5'];
	$desc6 = $get_field_level['desc6'];
	$desc7 = $get_field_level['desc7'];
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
                    <label for="business_street" class="col-sm-4 control-label">Harness Model</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_1" value="<?php echo $fields[1]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Manufacture Date</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_2" value="<?php echo $fields[2]; ?>" class="datepicker" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Serial Number</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_3" value="<?php echo $fields[3]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Lot Number</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_4" value="<?php echo $fields[4]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Purchase Date</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_6" value="<?php echo $fields[6]; ?>" class="datepicker" />
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
                <textarea name="desc" rows="3" cols="50" class="form-control"><?php echo $desc; ?></textarea>
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_hard" >
                    Hardware<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_hard" class="panel-collapse collapse">
            <div class="panel-body">


				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Hardware: (includes D-rings, buckles, keepers, and back pads) Inspect for damage, distortion, sharp edges, burrs, cracks and corrosion</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[7] == 'Accepted') { echo " checked"; } ?>  name="fields_7" value="Accepted">Accepted&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[7] == 'Rejected') { echo " checked"; } ?>  name="fields_7" value="Rejected">Rejected&nbsp;&nbsp;
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Supportive Details or Comments</label>
                <div class="col-sm-8">
                <textarea name="desc1" rows="3" cols="50" class="form-control"><?php echo $desc1; ?></textarea>
                </div>
				</div>


 			</div>
        </div>
    </div>

<?php }?>


<?php if (strpos($form_config, ','."fields9".',') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_web" >
                    Webbing<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_web" class="panel-collapse collapse">
            <div class="panel-body">


				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Webbing: Inspect for cuts, burns, tears, abrasion, frays, excessive soiling, and discoloration.</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[8] == 'Accepted') { echo " checked"; } ?>  name="fields_8" value="Accepted">Accepted&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[8] == 'Rejected') { echo " checked"; } ?>  name="fields_8" value="Rejected">Rejected&nbsp;&nbsp;
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Supportive Details or Comments</label>
                <div class="col-sm-8">
                <textarea name="desc2" rows="3" cols="50" class="form-control"><?php echo $desc2; ?></textarea>
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_st" >
                    Stitching<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_st" class="panel-collapse collapse">
            <div class="panel-body">


				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Stitching: inspect for pulled or cut stitches.</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[9] == 'Accepted') { echo " checked"; } ?>  name="fields_9" value="Accepted">Accepted&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[9] == 'Rejected') { echo " checked"; } ?>  name="fields_9" value="Rejected">Rejected&nbsp;&nbsp;
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Supportive Details or Comments</label>
                <div class="col-sm-8">
                <textarea name="desc3" rows="3" cols="50" class="form-control"><?php echo $desc3; ?></textarea>
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_sy" >
                    Labels<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_sy" class="panel-collapse collapse">
            <div class="panel-body">

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Labels: Inspect, make certain all labels are securely held in place and legible.</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[10] == 'Accepted') { echo " checked"; } ?>  name="fields_10" value="Accepted">Accepted&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[10] == 'Rejected') { echo " checked"; } ?>  name="fields_10" value="Rejected">Rejected&nbsp;&nbsp;
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Supportive Details or Comments</label>
                <div class="col-sm-8">
                <textarea name="desc4" rows="3" cols="50" class="form-control"><?php echo $desc4; ?></textarea>
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_wire" >
                    Other 5<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_wire" class="panel-collapse collapse">
            <div class="panel-body">

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label"><input type="text" name="fields_16" value="<?php echo $fields[16]; ?>" class="form-control" />
                </label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[11] == 'Accepted') { echo " checked"; } ?>  name="fields_11" value="Accepted">Accepted&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[11] == 'Rejected') { echo " checked"; } ?>  name="fields_11" value="Rejected">Rejected&nbsp;&nbsp;
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Supportive Details or Comments</label>
                <div class="col-sm-8">
                <textarea name="desc5" rows="3" cols="50" class="form-control"><?php echo $desc5; ?></textarea>
                </div>
				</div>


 			</div>
        </div>
    </div>

<?php }?>

<?php if (strpos($form_config, ','."fields13".',') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_energy" >
                    Other 6<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_energy" class="panel-collapse collapse">
            <div class="panel-body">


				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label"><input type="text" name="fields_17" value="<?php echo $fields[17]; ?>" class="form-control" />
                </label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[12] == 'Accepted') { echo " checked"; } ?>  name="fields_12" value="Accepted">Accepted&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[12] == 'Rejected') { echo " checked"; } ?>  name="fields_12" value="Rejected">Rejected&nbsp;&nbsp;
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Supportive Details or Comments</label>
                <div class="col-sm-8">
                <textarea name="desc6" rows="3" cols="50" class="form-control"><?php echo $desc6; ?></textarea>
                </div>
				</div>


 			</div>
        </div>
    </div>

<?php }?>

<?php if (strpos($form_config, ','."fields14".',') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_labels" >
                    Other 7<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_labels" class="panel-collapse collapse">
            <div class="panel-body">


				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label"><input type="text" name="fields_18" value="<?php echo $fields[18]; ?>" class="form-control" />
                </label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[13] == 'Accepted') { echo " checked"; } ?>  name="fields_13" value="Accepted">Accepted&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[13] == 'Rejected') { echo " checked"; } ?>  name="fields_13" value="Rejected">Rejected&nbsp;&nbsp;
                </div>
				</div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Supportive Details or Comments</label>
                <div class="col-sm-8">
                <textarea name="desc7" rows="3" cols="50" class="form-control"><?php echo $desc7; ?></textarea>
                </div>
				</div>


 			</div>
        </div>
    </div>

<?php }?>

<?php if (strpos($form_config, ','."fields15".',') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_overall" >
                    Overall Disposition<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_overall" class="panel-collapse collapse">
            <div class="panel-body">

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Overall Disposition</label>
                <div class="col-sm-8">
                <input type="radio" <?php if ($fields[14] == 'Accepted') { echo " checked"; } ?>  name="fields_14" value="Accepted">Accepted&nbsp;&nbsp;
                <input type="radio" <?php if ($fields[14] == 'Rejected') { echo " checked"; } ?>  name="fields_14" value="Rejected">Rejected&nbsp;&nbsp;
				<input type="text" name="fields_15" value="<?php echo $fields[15]; ?>"   class="form-control" />
                </div>
				</div>

 			</div>
        </div>
    </div>

<?php }?>

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
                echo '<img src="full_body_harness_inspection_checklist_log/download/safety_'.$assign_staff_id.'.png">';
            } ?>

        </div>
    </div>
</div>
<?php $sa_inc++;
    }
} ?>

</div>