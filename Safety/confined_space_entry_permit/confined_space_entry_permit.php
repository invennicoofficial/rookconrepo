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
$all_task = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];
	echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_confined_space_entry_permit WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
	$desc = $get_field_level['desc'];
	$desc1 = $get_field_level['desc1'];
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
                    <label for="business_street" class="col-sm-4 control-label">Entry Date</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_0" value="<?php echo $today_date; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>
				<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Start Time</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_1" value="<?php echo $fields[1]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>
				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Completion Time</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_2" value="<?php echo $fields[2]; ?>" class="form-control" />
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_des" >
                    Description of Work To Be Performed<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_des" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Description of Work To Be Performed</label>
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_class" >
                    Classification<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_class" class="panel-collapse collapse">
            <div class="panel-body">

				<?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Location of Confined Space</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_3" value="<?php echo $fields[3]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Confined Space ID #</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_4" value="<?php echo $fields[4]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields7".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Classification</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_5" value="<?php echo $fields[5]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields8".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Type of Confined Space</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_6" value="<?php echo $fields[6]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields9".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Supervisor in Charge Of Entry (print name)</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_7" value="<?php echo $fields[7]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields10".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Safety Watch (print name)</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_8" value="<?php echo $fields[8]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

			</div>
        </div>
    </div>



<!-- fields_8 and field 10 -->

<?php if (strpos(','.$form_config.',', ',fields11,') !== FALSE) { ?>

	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_pre" >
                    Pre-Entry Authorization<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

	<div id="collapse_pre" class="panel-collapse collapse">
        <div class="panel-body">

				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Oxygen-Deficient Atmosphere</label>
				<div class="col-sm-8"><input type="checkbox" <?php if ($fields[9]=='Oxygen-Deficient Atmosphere') { echo " checked"; } ?>  name="fields_9" value="Oxygen-Deficient Atmosphere"><input name="fields_10" type="text" value="<?php echo $fields[10]; ?>" class="form-control" />
				</div>
				</div>

				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Oxygen-Enriched Atmosphere</label>
				<div class="col-sm-8"><input type="checkbox" <?php if ($fields[11]=='Oxygen-Enriched Atmosphere') { echo " checked"; } ?>  name="fields_11" value="Oxygen-Enriched Atmosphere"><input name="fields_12" type="text" value="<?php echo $fields[12]; ?>" class="form-control" />
				</div>
				</div>

				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Welding/Cutting (Hot Work)</label>
				<div class="col-sm-8"><input type="checkbox" <?php if ($fields[13]=='Welding/Cutting (Hot Work)') { echo " checked"; } ?>  name="fields_13" value="Welding/Cutting (Hot Work)"><input name="fields_14" type="text" value="<?php echo $fields[14]; ?>" class="form-control" />
				</div>
				</div>

				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Engulfment</label>
				<div class="col-sm-8"><input type="checkbox" <?php if ($fields[15]=='Engulfment') { echo " checked"; } ?>  name="fields_15" value="Engulfment"><input name="fields_16" type="text" value="<?php echo $fields[16]; ?>" class="form-control" />
				</div>
				</div>

				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Toxic Atmosphere</label>
				<div class="col-sm-8"><input type="checkbox" <?php if ($fields[17]=='Toxic Atmosphere') { echo " checked"; } ?>  name="fields_17" value="Toxic Atmosphere"><input name="fields_18" type="text" value="<?php echo $fields[18]; ?>" class="form-control" />
				</div>
				</div>

				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Flammable Atmosphere</label>
				<div class="col-sm-8"><input type="checkbox" <?php if ($fields[19]=='Flammable Atmosphere') { echo " checked"; } ?>  name="fields_19" value="Flammable Atmosphere"><input name="fields_20" type="text" value="<?php echo $fields[20]; ?>" class="form-control" />
				</div>
				</div>

				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Energized Electrical Equipment</label>
				<div class="col-sm-8"><input type="checkbox" <?php if ($fields[21]=='Flammable Atmosphere') { echo " checked"; } ?>  name="fields_21" value="Flammable Atmosphere"><input name="fields_22" type="text" value="<?php echo $fields[22]; ?>" class="form-control" />
				</div>
				</div>

				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Entrapment</label>
				<div class="col-sm-8"><input type="checkbox" <?php if ($fields[23]=='Entrapment') { echo " checked"; } ?>  name="fields_23" value="Entrapment"><input name="fields_24" type="text" value="<?php echo $fields[24]; ?>" class="form-control" />
				</div>
				</div>

				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Hazardous Chemical</label>
				<div class="col-sm-8"><input type="checkbox" <?php if ($fields[25]=='Hazardous Chemical') { echo " checked"; } ?>  name="fields_25" value="Hazardous Chemical"><input name="fields_26" type="text" value="<?php echo $fields[26]; ?>" class="form-control" />
				</div>
				</div>

			</div>
        </div>
    </div>

<?php } ?>


<!-- fields_26 and field 11 -->


<?php if (strpos(','.$form_config.',', ',fields12,') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ppe" >
                    PPE & Safety Equipment Required for Entry<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_ppe" class="panel-collapse collapse">
            <div class="panel-body">


				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Self Contained Breathing Apparatus</label>
				<div class="col-sm-8"><input type="checkbox" <?php if ($fields[27]=='Self Contained Breathing Apparatus') { echo " checked"; } ?>  name="fields_27" value="Self Contained Breathing Apparatus"><input name="fields_28" type="text" value="<?php echo $fields[28]; ?>" class="form-control" />
				</div>
				</div>

				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Air-Line Respirator (SABA)</label>
				<div class="col-sm-8"><input type="checkbox" <?php if ($fields[29]=='Air-Line Respirator (SABA)') { echo " checked"; } ?>  name="fields_29" value="Air-Line Respirator (SABA)"><input name="fields_30" type="text" value="<?php echo $fields[30]; ?>" class="form-control" />
				</div>
				</div>

				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Flame Resistant Clothing</label>
				<div class="col-sm-8"><input type="checkbox" <?php if ($fields[31]=='Flame Resistant Clothing') { echo " checked"; } ?>  name="fields_31" value="Flame Resistant Clothing"><input name="fields_32" type="text" value="<?php echo $fields[32]; ?>" class="form-control" />
				</div>
				</div>

				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Ventilation</label>
				<div class="col-sm-8"><input type="checkbox" <?php if ($fields[33]=='Ventilation') { echo " checked"; } ?>  name="fields_33" value="Ventilation"><input name="fields_34" type="text" value="<?php echo $fields[34]; ?>" class="form-control" />
				</div>
				</div>

				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Two Way Communications</label>
				<div class="col-sm-8"><input type="checkbox" <?php if ($fields[35]=='Two Way Communications') { echo " checked"; } ?>  name="fields_35" value="Two Way Communications"><input name="fields_36" type="text" value="<?php echo $fields[36]; ?>" class="form-control" />
				</div>
				</div>

				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Harness</label>
				<div class="col-sm-8"><input type="checkbox" <?php if ($fields[37]=='Harness') { echo " checked"; } ?>  name="fields_37" value="Harness"><input name="fields_38" type="text" value="<?php echo $fields[38]; ?>" class="form-control" />
				</div>
				</div>

				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Rescue Tripod with lifeline</label>
				<div class="col-sm-8"><input type="checkbox" <?php if ($fields[39]=='Rescue Tripod with lifeline') { echo " checked"; } ?>  name="fields_39" value="Rescue Tripod with lifeline"><input name="fields_40" type="text" value="<?php echo $fields[40]; ?>" class="form-control" />
				</div>
				</div>

				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Rescue Tripod with mechanical winch</label>
				<div class="col-sm-8"><input type="checkbox" <?php if ($fields[41]=='Rescue Tripod with mechanical winch') { echo " checked"; } ?>  name="fields_41" value="Rescue Tripod with mechanical winch"><input name="fields_42" type="text" value="<?php echo $fields[42]; ?>" class="form-control" />
				</div>
				</div>

				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Chemical Suits</label>
				<div class="col-sm-8"><input type="checkbox" <?php if ($fields[43]=='Chemical Suits') { echo " checked"; } ?>  name="fields_43" value="Chemical Suits"><input name="fields_44" type="text" value="<?php echo $fields[44]; ?>" class="form-control" />
				</div>
				</div>

				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Gloves</label>
				<div class="col-sm-8"><input type="checkbox" <?php if ($fields[45]=='Gloves') { echo " checked"; } ?>  name="fields_45" value="Gloves"><input name="fields_46" type="text" value="<?php echo $fields[46]; ?>" class="form-control" />
				</div>
				</div>

				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Hard Hat</label>
				<div class="col-sm-8"><input type="checkbox" <?php if ($fields[47]=='Hard Hat') { echo " checked"; } ?>  name="fields_47" value="Hard Hat"><input name="fields_48" type="text" value="<?php echo $fields[48]; ?>" class="form-control" />
				</div>
				</div>

				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Safety glasses / Goggles / Shields</label>
				<div class="col-sm-8"><input type="checkbox" <?php if ($fields[49]=='Safety glasses / Goggles / Shields') { echo " checked"; } ?>  name="fields_49" value="Safety glasses / Goggles / Shields"><input name="fields_50" type="text" value="<?php echo $fields[50]; ?>" class="form-control" />
				</div>
				</div>

				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Hearing Protection</label>
				<div class="col-sm-8"><input type="checkbox" <?php if ($fields[51]=='Hearing Protection') { echo " checked"; } ?>  name="fields_51" value="Hearing Protection"><input name="fields_52" type="text" value="<?php echo $fields[52]; ?>" class="form-control" />
				</div>
				</div>

				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Steel Toed Boots</label>
				<div class="col-sm-8"><input type="checkbox" <?php if ($fields[53]=='Steel Toed Boots') { echo " checked"; } ?>  name="fields_53" value="Steel Toed Boots"><input name="fields_54" type="text" value="<?php echo $fields[54]; ?>" class="form-control" />
				</div>
				</div>

				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Others</label>
				<div class="col-sm-8"><input type="checkbox" <?php if ($fields[55]=='Others') { echo " checked"; } ?>  name="fields_55" value="Others"><input name="fields_56" type="text" value="<?php echo $fields[56]; ?>" class="form-control" />
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_des1" >
                    Comments<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_des1" class="panel-collapse collapse">
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





<!-- fields_56 and field 12 -->

<?php if (strpos($form_config, ','."fields14".',') !== FALSE) { ?>

<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_air" >
                    Air Monitoring Results Prior To Entry<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_air" class="panel-collapse collapse">
            <div class="panel-body">

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Monitor Type</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_57" value="<?php echo $fields[57]; ?>" class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Serial Number</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_58" value="<?php echo $fields[58]; ?>" class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Oxygen</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_59" value="<?php echo $fields[59]; ?>" class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">LEL</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_60" value="<?php echo $fields[60]; ?>" class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">CO</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_61" value="<?php echo $fields[61]; ?>" class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">H2S</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_62" value="<?php echo $fields[62]; ?>" class="form-control" />
                    </div>
					</div>


					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Calibration Performed?</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[63] == 'Yes') { echo " checked"; } ?>  name="fields_63" value="Yes">Yes&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[63] == 'No') { echo " checked"; } ?>  name="fields_63" value="No">No&nbsp;&nbsp;
                    Initials<input type="text" name="fields_64" value="<?php echo $fields[64]; ?>" class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Alarm Conditions?</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[65] == 'Yes') { echo " checked"; } ?>  name="fields_65" value="Yes">Yes&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[65] == 'No') { echo " checked"; } ?>  name="fields_65" value="No">No&nbsp;&nbsp;
                    <input type="text" name="fields_66" value="<?php echo $fields[66]; ?>" class="form-control" />
                    </div>
					</div>

			</div>
        </div>
    </div>
  <?php } ?>


  <!-- add More field 15-->
    <?php if (strpos($form_config, ','."fields15".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_14" >
                    Continuous Air Monitoring Results<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_14" class="panel-collapse collapse">
            <div class="panel-body">

                <?php
                $all_task_each = explode('**##**',$all_task);

                $total_count = mb_substr_count($all_task,'**##**');
                if($total_count > 0) {
                    echo "<table class='table table-bordered'>";
                    echo "<tr class='hidden-xs hidden-sm'>
                    <th>Time</th>
                    <th>Oxygen</th>
                    <th>LEL Level</th>
                    <th>CO</th>
					<th>H2S</th>";
                }
                for($client_loop=0; $client_loop<=$total_count; $client_loop++) {
                    $task_item = explode('**',$all_task_each[$client_loop]);
                    $task = $task_item[0];
                    $hazard = $task_item[1];
                    $level = $task_item[2];
                    $plan = $task_item[3];
					$plan1 = $task_item[4];
                    if($task != '') {
                        echo '<tr>';
                        echo '<td data-title="Email">' . $task . '</td>';
                        echo '<td data-title="Email">' . $hazard . '</td>';
                        echo '<td data-title="Email">' . $level . '</td>';
                        echo '<td data-title="Email">' . $plan . '</td>';
						echo '<td data-title="Email">' . $plan1 . '</td>';
                        echo '</tr>';
                    }
                }
                echo '</table>';
                ?>
                <div class="additional_hazard clearfix">
                    <div class="row">
                        <div class="col-md-2 col-sm-6 col-xs-6 padded">
                            <p>Time</p>
                            <input type="text" name="task[]" class="task_list"/>
                        </div>
                        <div class="col-md-2 col-sm-6 col-xs-6 padded">
                            <p>Oxygen</p>
                            <input type="text" name="hazard[]" class="task_list"/>
                        </div>
                        <div class="col-md-2 col-sm-6 col-xs-6 padded">
                            <p>LEL</p>
                            <input type="text" name="hazard1[]" class="task_list"/>
                        </div>
                        <div class="col-md-2 col-sm-6 col-xs-6 padded">
                            <p>CO</p>
                            <input type="text" name="hazard_plan[]" class="task_list"/>
                        </div>
						<div class="col-md-2 col-sm-6 col-xs-6 padded">
                            <p>H2S</p>
                            <input type="text" name="hazard_plan1[]" class="task_list"/>
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

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info1" >
                    Entry Authorization<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info1" class="panel-collapse collapse">
            <div class="panel-body">

			<p></p>

				<?php if (strpos(','.$form_config.',', ',fields16,') !== FALSE) { ?>
				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Entry Authorization</label>
                <div class="col-sm-8"><input type="checkbox" <?php if ($fields[67]=='Entry Authorization') { echo " checked"; } ?>  name="fields_67" value="Entry Authorization">&nbsp;&nbsp;All actions and/or conditions for safe entry have been performed
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

