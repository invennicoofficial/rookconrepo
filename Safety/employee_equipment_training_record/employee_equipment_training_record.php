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
$hire_date = '';
$fields = '';
$fields_value = '';
$fields_date = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];
	echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_employee_equipment_training_record WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
	$hire_date = $get_field_level['hire_date'];
	$fields = $get_field_level['fields'];
    $fields_value = explode('**FFM**', $get_field_level['fields_value']);
    $fields_date = explode(',', $get_field_level['fields_date']);
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
                    <label for="business_street" class="col-sm-4 control-label">Hire Date</label>
                    <div class="col-sm-8">
                    <input type="text" name="hire_date" value="<?php echo $hire_date; ?>" class="datepicker" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Employee Name</label>
                    <div class="col-sm-8">
                    <input type="text" name="contactid" value="<?php echo $contactid; ?>" class="form-control" />
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
                    Equipments<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info1" class="panel-collapse collapse">
            <div class="panel-body">

            <?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
			<b>Excavator</b>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Hoe Pack</label>
                <div class="col-sm-8">

            <input name="fields_date_1" type="text" value="<?php echo $fields_date[1]; ?>" class="datepicker" />&nbsp;&nbsp;
			<input type="radio" <?php if (strpos(','.$fields.',', ',field1_competent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_1" value="field1_competent">Competent
			<input type="radio" <?php if (strpos(','.$fields.',', ',field1_noncompetent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_1" value="field1_noncompetent">Noncompetent
			&nbsp;&nbsp;<input name="fields_value_1" type="text" value="<?php echo $fields_value[1]; ?>" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Breaker</label>
                <div class="col-sm-8">

            <input name="fields_date_2" type="text" value="<?php echo $fields_date[2]; ?>" class="datepicker" />&nbsp;&nbsp;
			<input type="radio" <?php if (strpos(','.$fields.',', ',field2_competent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_2" value="field2_competent">Competent
			<input type="radio" <?php if (strpos(','.$fields.',', ',field2_noncompetent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_2" value="field2_noncompetent">Noncompetent
			&nbsp;&nbsp;<input name="fields_value_2" type="text" value="<?php echo $fields_value[2]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>


            <?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Grader</label>
                <div class="col-sm-8">

            <input name="fields_date_3" type="text" value="<?php echo $fields_date[3]; ?>" class="datepicker" />&nbsp;&nbsp;
			<input type="radio" <?php if (strpos(','.$fields.',', ',field3_competent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_3" value="field3_competent">Competent
			<input type="radio" <?php if (strpos(','.$fields.',', ',field3_noncompetent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_3" value="field3_noncompetent">Noncompetent
			&nbsp;&nbsp;<input name="fields_value_3" type="text" value="<?php echo $fields_value[3]; ?>" class="form-control" />
                </div>
            </div>
			<?php } ?>

            <?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
			<b>Loaders </b>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Track Loader</label>
                <div class="col-sm-8">

            <input name="fields_date_4" type="text" value="<?php echo $fields_date[4]; ?>" class="datepicker" />&nbsp;&nbsp;
			<input type="radio" <?php if (strpos(','.$fields.',', ',field4_competent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_4" value="field4_competent">Competent
			<input type="radio" <?php if (strpos(','.$fields.',', ',field4_noncompetent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_4" value="field4_noncompetent">Noncompetent
			&nbsp;&nbsp;<input name="fields_value_4" type="text" value="<?php echo $fields_value[4]; ?>" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Wheel Loader</label>
                <div class="col-sm-8">

            <input name="fields_date_5" type="text" value="<?php echo $fields_date[5]; ?>" class="datepicker" />&nbsp;&nbsp;
			<input type="radio" <?php if (strpos(','.$fields.',', ',field5_competent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_5" value="field5_competent">Competent
			<input type="radio" <?php if (strpos(','.$fields.',', ',field5_noncompetent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_5" value="field5_noncompetent">Noncompetent
			&nbsp;&nbsp;<input name="fields_value_5" type="text" value="<?php echo $fields_value[5]; ?>" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Skid Steer</label>
                <div class="col-sm-8">

            <input name="fields_date_6" type="text" value="<?php echo $fields_date[6]; ?>" class="datepicker" />&nbsp;&nbsp;
			<input type="radio" <?php if (strpos(','.$fields.',', ',field6_competent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_6" value="field6_competent">Competent
			<input type="radio" <?php if (strpos(','.$fields.',', ',field6_noncompetent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_6" value="field6_noncompetent">Noncompetent
			&nbsp;&nbsp;<input name="fields_value_6" type="text" value="<?php echo $fields_value[6]; ?>" class="form-control" />
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($form_config, ','."fields7".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Water Truck</label>
                <div class="col-sm-8">

            <input name="fields_date_7" type="text" value="<?php echo $fields_date[7]; ?>" class="datepicker" />&nbsp;&nbsp;
			<input type="radio" <?php if (strpos(','.$fields.',', ',field7_competent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_7" value="field7_competent">Competent
			<input type="radio" <?php if (strpos(','.$fields.',', ',field7_noncompetent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_7" value="field7_noncompetent">Noncompetent
			&nbsp;&nbsp;<input name="fields_value_7" type="text" value="<?php echo $fields_value[7]; ?>" class="form-control" />
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($form_config, ','."fields8".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Tractor</label>
                <div class="col-sm-8">

            <input name="fields_date_8" type="text" value="<?php echo $fields_date[8]; ?>" class="datepicker" />&nbsp;&nbsp;
			<input type="radio" <?php if (strpos(','.$fields.',', ',field8_competent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_8" value="field8_competent">Competent
			<input type="radio" <?php if (strpos(','.$fields.',', ',field8_noncompetent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_8" value="field8_noncompetent">Noncompetent
			&nbsp;&nbsp;<input name="fields_value_8" type="text" value="<?php echo $fields_value[8]; ?>" class="form-control" />
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($form_config, ','."fields9".',') !== FALSE) { ?>
            <b>Trailers</b>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Low Boy</label>
                <div class="col-sm-8">

            <input name="fields_date_9" type="text" value="<?php echo $fields_date[9]; ?>" class="datepicker" />&nbsp;&nbsp;
			<input type="radio" <?php if (strpos(','.$fields.',', ',field9_competent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_9" value="field9_competent">Competent
			<input type="radio" <?php if (strpos(','.$fields.',', ',field9_noncompetent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_9" value="field9_noncompetent">Noncompetent
			&nbsp;&nbsp;<input name="fields_value_9" type="text" value="<?php echo $fields_value[9]; ?>" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Jeep</label>
                <div class="col-sm-8">

            <input name="fields_date_10" type="text" value="<?php echo $fields_date[10]; ?>" class="datepicker" />&nbsp;&nbsp;
			<input type="radio" <?php if (strpos(','.$fields.',', ',field10_competent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_10" value="field10_competent">Competent
			<input type="radio" <?php if (strpos(','.$fields.',', ',field10_noncompetent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_10" value="field10_noncompetent">Noncompetent
			&nbsp;&nbsp;<input name="fields_value_10" type="text" value="<?php echo $fields_value[10]; ?>" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Tandem Dump</label>
                <div class="col-sm-8">

            <input name="fields_date_11" type="text" value="<?php echo $fields_date[11]; ?>" class="datepicker" />&nbsp;&nbsp;
			<input type="radio" <?php if (strpos(','.$fields.',', ',field11_competent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_11" value="field11_competent">Competent
			<input type="radio" <?php if (strpos(','.$fields.',', ',field11_noncompetent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_11" value="field11_noncompetent">Noncompetent
			&nbsp;&nbsp;<input name="fields_value_11" type="text" value="<?php echo $fields_value[11]; ?>" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Hyd 8 Wheel</label>
                <div class="col-sm-8">

            <input name="fields_date_12" type="text" value="<?php echo $fields_date[12]; ?>" class="datepicker" />&nbsp;&nbsp;
			<input type="radio" <?php if (strpos(','.$fields.',', ',field12_competent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_12" value="field12_competent">Competent
			<input type="radio" <?php if (strpos(','.$fields.',', ',field12_noncompetent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_12" value="field12_noncompetent">Noncompetent
			&nbsp;&nbsp;<input name="fields_value_12" type="text" value="<?php echo $fields_value[12]; ?>" class="form-control" />
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($form_config, ','."fields10".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Dozer</label>
                <div class="col-sm-8">

            <input name="fields_date_13" type="text" value="<?php echo $fields_date[13]; ?>" class="datepicker" />&nbsp;&nbsp;
			<input type="radio" <?php if (strpos(','.$fields.',', ',field13_competent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_13" value="field13_competent">Competent
			<input type="radio" <?php if (strpos(','.$fields.',', ',field13_noncompetent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_13" value="field13_noncompetent">Noncompetent
			&nbsp;&nbsp;<input name="fields_value_13" type="text" value="<?php echo $fields_value[13]; ?>" class="form-control" />
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($form_config, ','."fields11".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Roller</label>
                <div class="col-sm-8">

            <input name="fields_date_14" type="text" value="<?php echo $fields_date[14]; ?>" class="datepicker" />&nbsp;&nbsp;
			<input type="radio" <?php if (strpos(','.$fields.',', ',field14_competent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_14" value="field14_competent">Competent
			<input type="radio" <?php if (strpos(','.$fields.',', ',field14_noncompetent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_14" value="field14_noncompetent">Noncompetent
			&nbsp;&nbsp;<input name="fields_value_14" type="text" value="<?php echo $fields_value[14]; ?>" class="form-control" />
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($form_config, ','."fields12".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Overhead Crane</label>
                <div class="col-sm-8">

            <input name="fields_date_15" type="text" value="<?php echo $fields_date[15]; ?>" class="datepicker" />&nbsp;&nbsp;
			<input type="radio" <?php if (strpos(','.$fields.',', ',field15_competent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_15" value="field15_competent">Competent
			<input type="radio" <?php if (strpos(','.$fields.',', ',field15_noncompetent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_15" value="field15_noncompetent">Noncompetent
			&nbsp;&nbsp;<input name="fields_value_15" type="text" value="<?php echo $fields_value[15]; ?>" class="form-control" />
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($form_config, ','."fields13".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Lathe</label>
                <div class="col-sm-8">

            <input name="fields_date_16" type="text" value="<?php echo $fields_date[16]; ?>" class="datepicker" />&nbsp;&nbsp;
			<input type="radio" <?php if (strpos(','.$fields.',', ',field16_competent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_16" value="field16_competent">Competent
			<input type="radio" <?php if (strpos(','.$fields.',', ',field16_noncompetent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_16" value="field16_noncompetent">Noncompetent
			&nbsp;&nbsp;<input name="fields_value_16" type="text" value="<?php echo $fields_value[16]; ?>" class="form-control" />
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($form_config, ','."fields14".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Grinders</label>
                <div class="col-sm-8">

            <input name="fields_date_17" type="text" value="<?php echo $fields_date[17]; ?>" class="datepicker" />&nbsp;&nbsp;
			<input type="radio" <?php if (strpos(','.$fields.',', ',field17_competent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_17" value="field17_competent">Competent
			<input type="radio" <?php if (strpos(','.$fields.',', ',field17_noncompetent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_17" value="field17_noncompetent">Noncompetent
			&nbsp;&nbsp;<input name="fields_value_17" type="text" value="<?php echo $fields_value[17]; ?>" class="form-control" />
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($form_config, ','."fields15".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Drills</label>
                <div class="col-sm-8">

            <input name="fields_date_18" type="text" value="<?php echo $fields_date[18]; ?>" class="datepicker" />&nbsp;&nbsp;
			<input type="radio" <?php if (strpos(','.$fields.',', ',field18_competent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_18" value="field18_competent">Competent
			<input type="radio" <?php if (strpos(','.$fields.',', ',field18_noncompetent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_18" value="field18_noncompetent">Noncompetent
			&nbsp;&nbsp;<input name="fields_value_18" type="text" value="<?php echo $fields_value[18]; ?>" class="form-control" />
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($form_config, ','."fields16".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Concrete Cutter</label>
                <div class="col-sm-8">

            <input name="fields_date_19" type="text" value="<?php echo $fields_date[19]; ?>" class="datepicker" />&nbsp;&nbsp;
			<input type="radio" <?php if (strpos(','.$fields.',', ',field19_competent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_19" value="field19_competent">Competent
			<input type="radio" <?php if (strpos(','.$fields.',', ',field19_noncompetent,') !== FALSE) { echo " checked"; } ?>  name="fields_option_19" value="field19_noncompetent">Noncompetent
			&nbsp;&nbsp;<input name="fields_value_19" type="text" value="<?php echo $fields_value[19]; ?>" class="form-control" />
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