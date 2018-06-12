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

    if(!empty($_GET['formid'])) {
        $formid = $_GET['formid'];

        echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';
        $get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_confined_space_entry_log WHERE fieldlevelriskid='$formid'"));
        $today_date = $get_field_level['today_date'];
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

				<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Job Number</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_0" value="<?php echo $fields[0]; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

                <?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Safety Watch</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_1" value="<?php echo $fields[1]; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Client</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_2" value="<?php echo $fields[2]; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Client Rep.</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_3" value="<?php echo $fields[3]; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Location</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_4" value="<?php echo $fields[4]; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields7".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Supervisor</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_5" value="<?php echo $fields[5]; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields8".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Confined Space Description</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_6" value="<?php echo $fields[6]; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields9".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Start Date & Time</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_7" value="<?php echo $fields[7]; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields10".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Finish Date & Time</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_8" value="<?php echo $fields[8]; ?>" class="form-control" />
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

               <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Time In:</label>
                <div class="col-sm-8">
                    <input name="timein_<?php echo $assign_staff_id;?>[]" type="text" class="form-control" />
                </div>
              </div>

               <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Time Out:</label>
                <div class="col-sm-8">
                    <input name="timeout_<?php echo $assign_staff_id;?>[]" type="text" class="form-control" />
                </div>
              </div>

               <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Running Total:</label>
                <div class="col-sm-8">
                    <input name="runningtotal_<?php echo $assign_staff_id;?>[]" type="text" class="form-control" />
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

            </div>
        </div>
    </div>
    <?php $sa_inc++;
        }
    } ?>

</div>