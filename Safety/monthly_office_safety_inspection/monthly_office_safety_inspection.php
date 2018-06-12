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

$location = '';
$fields = '';
$fields_value = '';
$all_task = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];

    echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_monthly_office_safety_inspection WHERE fieldlevelriskid='$formid'"));

	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
    $location = $get_field_level['location'];
    $fields = $get_field_level['fields'];
    $fields_value = explode('**FFM**', $get_field_level['fields_value']);
	$all_task = $get_field_level['all_task'];
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
                    <label for="business_street" class="col-sm-4 control-label">Inspected By:</label>
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
                    Item To Watch for(Check for)<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info1" class="panel-collapse collapse">
            <div class="panel-body">

			<?php if (strpos($form_config, ','."fields7".',') !== FALSE) { ?>

               <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Water/sanitation rest room facilities</label>
                <div class="col-sm-8">
                    <input type="radio" <?php if (strpos(','.$fields.',', ',field7_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_7" value="field7_acceptable">Acceptable
                    <input type="radio" <?php if (strpos(','.$fields.',', ',field7_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_7" value="field7_unacceptable">Unacceptable
                    &nbsp;&nbsp;<input name="fields_value_7" type="text" value="<?php echo $fields_value[7]; ?>" class="form-control" />
                </div>
              </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields8".',') !== FALSE) { ?>
               <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Fire escapes (doors) clear of obstructions  </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field8_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_8" value="field8_acceptable">Acceptable
			<input type="radio" <?php if (strpos(','.$fields.',', ',field8_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_8" value="field8_unacceptable">Unacceptable
			&nbsp;&nbsp;<input name="fields_value_8" type="text" value="<?php echo $fields_value[8]; ?>" class="form-control" />
                </div>
              </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields9".',') !== FALSE) { ?>
               <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Housekeeping (snow and ice at entrance)  </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field9_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_9" value="field9_acceptable">Acceptable
			<input type="radio" <?php if (strpos(','.$fields.',', ',field9_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_9" value="field9_unacceptable">Unacceptable
			&nbsp;&nbsp;<input name="fields_value_9" type="text" value="<?php echo $fields_value[9]; ?>" class="form-control" />
                </div>
              </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields10".',') !== FALSE) { ?>
               <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Safety Training  </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field10_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_10" value="field10_acceptable">Acceptable
			<input type="radio" <?php if (strpos(','.$fields.',', ',field10_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_10" value="field10_unacceptable">Unacceptable
			&nbsp;&nbsp;<input name="fields_value_10" type="text" value="<?php echo $fields_value[10]; ?>" class="form-control" />
                </div>
              </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields11".',') !== FALSE) { ?>
               <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Fire Protection Eqiupment (inspection)  </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field11_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_11" value="field11_acceptable">Acceptable
			<input type="radio" <?php if (strpos(','.$fields.',', ',field11_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_11" value="field11_unacceptable">Unacceptable
			&nbsp;&nbsp;<input name="fields_value_11" type="text" value="<?php echo $fields_value[11]; ?>" class="form-control" />
                </div>
              </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields12".',') !== FALSE) { ?>
               <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">First Aid contents(topped up)  </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field12_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_12" value="field12_acceptable">Acceptable
			<input type="radio" <?php if (strpos(','.$fields.',', ',field12_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_12" value="field12_unacceptable">Unacceptable
			&nbsp;&nbsp;<input name="fields_value_12" type="text" value="<?php echo $fields_value[12]; ?>" class="form-control" />
                </div>
              </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields13".',') !== FALSE) { ?>
               <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Lighting</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field13_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_13" value="field13_acceptable">Acceptable
			<input type="radio" <?php if (strpos(','.$fields.',', ',field13_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_13" value="field13_unacceptable">Unacceptable
			&nbsp;&nbsp;<input name="fields_value_13" type="text" value="<?php echo $fields_value[13]; ?>" class="form-control" />
                </div>
              </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields14".',') !== FALSE) { ?>
               <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Aisle, Work Surfaces (room to drive machine)  </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field14_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_14" value="field14_acceptable">Acceptable
			<input type="radio" <?php if (strpos(','.$fields.',', ',field14_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_14" value="field14_unacceptable">Unacceptable
			&nbsp;&nbsp;<input name="fields_value_14" type="text" value="<?php echo $fields_value[14]; ?>" class="form-control" />
                </div>
              </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields15".',') !== FALSE) { ?>
               <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Emergency Procesure/floor plan  </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field15_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_15" value="field15_acceptable">Acceptable
			<input type="radio" <?php if (strpos(','.$fields.',', ',field15_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_15" value="field15_unacceptable">Unacceptable
			&nbsp;&nbsp;<input name="fields_value_15" type="text" value="<?php echo $fields_value[15]; ?>" class="form-control" />
                </div>
              </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields16".',') !== FALSE) { ?>
               <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Vehicles</label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field16_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_16" value="field16_acceptable">Acceptable
			<input type="radio" <?php if (strpos(','.$fields.',', ',field16_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_16" value="field16_unacceptable">Unacceptable
			&nbsp;&nbsp;<input name="fields_value_16" type="text" value="<?php echo $fields_value[16]; ?>" class="form-control" />
                </div>
              </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields17".',') !== FALSE) { ?>
               <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Personal Protective Equipment  </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field17_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_17" value="field17_acceptable">Acceptable
			<input type="radio" <?php if (strpos(','.$fields.',', ',field17_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_17" value="field17_unacceptable">Unacceptable
			&nbsp;&nbsp;<input name="fields_value_17" type="text" value="<?php echo $fields_value[17]; ?>" class="form-control" />
                </div>
              </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields18".',') !== FALSE) { ?>
               <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Safe Work Practice  </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field18_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_18" value="field18_acceptable">Acceptable
			<input type="radio" <?php if (strpos(','.$fields.',', ',field18_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_18" value="field18_unacceptable">Unacceptable
			&nbsp;&nbsp;<input name="fields_value_18" type="text" value="<?php echo $fields_value[18]; ?>" class="form-control" />
                </div>
              </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields19".',') !== FALSE) { ?>
               <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Job Procedure </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field19_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_19" value="field19_acceptable">Acceptable
			<input type="radio" <?php if (strpos(','.$fields.',', ',field19_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_19" value="field19_unacceptable">Unacceptable
			&nbsp;&nbsp;<input name="fields_value_19" type="text" value="<?php echo $fields_value[19]; ?>" class="form-control" />
                </div>
              </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields20".',') !== FALSE) { ?>
               <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Maintenance (record)  </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field20_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_20" value="field20_acceptable">Acceptable
			<input type="radio" <?php if (strpos(','.$fields.',', ',field20_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_20" value="field20_unacceptable">Unacceptable
			&nbsp;&nbsp;<input name="fields_value_20" type="text" value="<?php echo $fields_value[20]; ?>" class="form-control" />
                </div>
              </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields21".',') !== FALSE) { ?>
               <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Flammable Liquid, Gas, Lables  </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field21_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_21" value="field21_acceptable">Acceptable
			<input type="radio" <?php if (strpos(','.$fields.',', ',field21_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_21" value="field21_unacceptable">Unacceptable
			&nbsp;&nbsp;<input name="fields_value_21" type="text" value="<?php echo $fields_value[21]; ?>" class="form-control" />
                </div>
              </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields22".',') !== FALSE) { ?>
               <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Storage Facilities/Areas (clean,organized)  </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field22_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_22" value="field22_acceptable">Acceptable
			<input type="radio" <?php if (strpos(','.$fields.',', ',field22_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_22" value="field22_unacceptable">Unacceptable
			&nbsp;&nbsp;<input name="fields_value_22" type="text" value="<?php echo $fields_value[22]; ?>" class="form-control" />
                </div>
              </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields23".',') !== FALSE) { ?>
               <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Warning Sign/Labels  </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field23_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_23" value="field23_acceptable">Acceptable
			<input type="radio" <?php if (strpos(','.$fields.',', ',field23_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_23" value="field23_unacceptable">Unacceptable
			&nbsp;&nbsp;<input name="fields_value_23" type="text" value="<?php echo $fields_value[23]; ?>" class="form-control" />
                </div>
              </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields24".',') !== FALSE) { ?>
               <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Locker/Luch Room  </label>
                <div class="col-sm-8">

			<input type="radio" <?php if (strpos(','.$fields.',', ',field24_acceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_24" value="field24_acceptable">Acceptable
			<input type="radio" <?php if (strpos(','.$fields.',', ',field24_unacceptable,') !== FALSE) { echo " checked"; } ?>  name="fields_option_24" value="field24_unacceptable">Unacceptable
			&nbsp;&nbsp;<input name="fields_value_24" type="text" value="<?php echo $fields_value[24]; ?>" class="form-control" />
                </div>
              </div>
			<?php } ?>

			</div>
        </div>
    </div>

	<?php if (strpos(','.$form_config.',', ',fields25,') !== FALSE) { ?>
    <div class="panel panel-default">
	    <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info4" >
                    Hazards<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info4" class="panel-collapse collapse">
            <div class="panel-body">

              <?php
                $all_task_each = explode('**##**',$all_task);

                $total_count = mb_substr_count($all_task,'**##**');
                if($total_count > 0) {
                    echo "<table class='table table-bordered'>";
                    echo "<tr class='hidden-xs hidden-sm'>
                        <th>Hazards Observed</th>
                        <th>Priority</th>
                        <th>Corrective Actions</th>
                        <th>Date Complete</th>
                        <th>By Whom</th>
                    ";
                }
                for($client_loop=0; $client_loop<=$total_count; $client_loop++) {
                    $task_item = explode('**',$all_task_each[$client_loop]);
                    $hazard = $task_item[0];
                    $pri = $task_item[1];
                    $ca = $task_item[2];
                    $dc = $task_item[3];
                    $bw = $task_item[4];
                    if($hazard != '') {
                        echo '<tr>';
                        echo '<td data-title="Email">' . $hazard . '</td>';
                        echo '<td data-title="Email">' . $pri . '</td>';
                        echo '<td data-title="Email">' . $ca . '</td>';
                        echo '<td data-title="Email">' . $dc . '</td>';
                        echo '<td data-title="Email">' . $bw . '</td>';
                        echo '</tr>';
                    }
                }
                echo '</table>';
                ?>
                <div class="additional_hazard clearfix">
                    <div class="row">
                        <div class="col-sm-3">
                            Hazards Observed<br/>
                            <input type="text" name="task[]" class="form-control"/>
                        </div>
                        <div class="col-sm-3">
                            <p>Priority</p>
                            <select name="hazard[]" class="form-control">
                                <option value="Imminent Danger">Imminent Danger</option>
                                <option value="Serious Danger">Serious Danger</option>
                                <option value="Minor">Minor</option>
                                <option value="Not Applicable">Not Applicable</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <p>Corrective Actions</p>
                            <input type="text" name="hazard_level[]" class="form-control"/>
                        </div>
                        <div class="col-sm-3">
                            <p>Date Complete</p>
                            <input type="text" name="hazard_date[]" class="datepicker"/>
                        </div>
                        <div class="col-sm-3">
                            <p>By Whom</p>
                            <input type="text" name="hazard_by_whom[]" class="form-control"/>
                        </div>
                    </div>
                </div>
                <div id="add_here_new_hazard"></div>
                <div class="form-group triple-gapped clearfix">
                    <div class="col-sm-offset-4 col-sm-8">
                        <button id="add_row_hazard" class="btn brand-btn pull-left">Add Hazard</button>
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