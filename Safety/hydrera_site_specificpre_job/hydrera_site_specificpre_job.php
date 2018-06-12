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
$(document).on('change', 'select[name="siteid"]', function() { changeJob(this); });

function changeJob(sel) {
    var proValue = sel.value;
    var proId = sel.id;
    var arr = proId.split('_');

    $.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "ajax_all.php?from=flha&name="+proValue,
        dataType: "html",   //expect html to be returned
        success: function(response){
            $("#location").val(response);
        }
    });
}
</script>
</head>
<body>

<?php
$today_date = date('Y-m-d');
$siteid = '';
$contactid = $_SESSION['contactid'];
$company = '';
$job_desc = '';
$lsd = '';
$fields = '';
$form_time = '';
$all_task = '';
$location = '';
$safety_topic = '';
$concerns = '';
$fields_value = '';

    if(!empty($_GET['formid'])) {
        $formid = $_GET['formid'];

        echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';

        $get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_site_specificpre_job WHERE fieldlevelriskid='$formid'"));

        $today_date = $get_field_level['today_date'];
        $siteid = $get_field_level['siteid'];
        $contactid = $get_field_level['contactid'];
        $company = $get_field_level['company'];
        $job_desc = $get_field_level['job_desc'];
        $lsd = $get_field_level['lsd'];

        $fields = $get_field_level['fields'];
        $fields_value = explode('**FFM**', $get_field_level['fields_value']);

        $form_time = $get_field_level['form_time'];
        $all_task = $get_field_level['all_task'];
        $location = $get_field_level['location'];

        $safety_topic = $get_field_level['safety_topic'];
        $concerns = $get_field_level['concerns'];
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
                    <label for="business_street" class="col-sm-4 control-label">Start Site#:</label>
                    <div class="col-sm-8">
                        <select data-placeholder="Choose a Job..." name="siteid" id="siteid" class="chosen-select-deselect form-control" width="380">
                          <option value=""></option>
                          <?php
                            $query = mysqli_query($dbc,"SELECT sectionid FROM bid_section WHERE deleted=0 order by sectionid");
                            while($row = mysqli_fetch_array($query)) {
                                if ($jobid == $row['sectionid']) {
                                    $selected = 'selected="selected"';
                                } else {
                                    $selected = '';
                                }
                                echo "<option ".$selected." value='". $row['sectionid']."'>".$row['sectionid'].'</option>';
                            }
                          ?>
                        </select>
                    </div>
                  </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Company:</label>
                    <div class="col-sm-8">
                        <input name="company" value="<?php echo $company; ?>" type="text" class="form-control" />
                    </div>
                  </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Job Description:</label>
                    <div class="col-sm-8">
                        <input name="job_desc" type="text" value="<?php echo $job_desc; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">LSD:</label>
                    <div class="col-sm-8">
                        <input name="lsd" type="text" value="<?php echo $lsd; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Contact:</label>
                    <div class="col-sm-8">
                        <input name="contactid" type="text" value="<?php echo $contactid; ?>" class="form-control" />
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
                    Safety Checklist<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info1" class="panel-collapse collapse">
            <div class="panel-body">

            <ul style="list-style-type: none;">
                <?php if (strpos($form_config, ','."fields7".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Equipment operation</label>
                    <div class="col-sm-8">
                        <input type="checkbox" <?php if (strpos(','.$fields.',', ',fields7,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields7">
                        <input name="fields_value_0" type="text" value="<?php echo $fields_value[0]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields8".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Wildlife</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields8,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields8"><input name="fields_value_1" type="text" value="<?php echo $fields_value[1]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields9".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Equipment backing</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields9,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields9"><input name="fields_value_2" type="text" value="<?php echo $fields_value[2]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields10".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Awareness of others</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields10,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields10"><input name="fields_value_3" type="text" value="<?php echo $fields_value[3]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields11".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Overhead work</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields11,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields11"><input name="fields_value_4" type="text" value="<?php echo $fields_value[4]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields12".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Compressed gas cylinder</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields12,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields12"><input name="fields_value_5" type="text" value="<?php echo $fields_value[5]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields13".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">cranes/hoisting</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields13,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields13"><input name="fields_value_6" type="text" value="<?php echo $fields_value[6]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields14".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Driving habit</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields14,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields14"><input name="fields_value_7" type="text" value="<?php echo $fields_value[7]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields15".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Electrical hazards</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields15,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields15"><input name="fields_value_8" type="text" value="<?php echo $fields_value[8]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields16".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Housekeeping/insp.</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields16,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields16"><input name="fields_value_9" type="text" value="<?php echo $fields_value[9]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields17".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Good communication</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields17,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields17"><input name="fields_value_10" type="text" value="<?php echo $fields_value[10]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields18".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Working at heights</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields18,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields18"><input name="fields_value_11" type="text" value="<?php echo $fields_value[11]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields19".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Ignition sources</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields19,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields19"><input name="fields_value_12" type="text" value="<?php echo $fields_value[12]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields20".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Wind Direction</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields20,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields20"><input name="fields_value_13" type="text" value="<?php echo $fields_value[13]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields21".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Excavation</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields21,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields21"><input name="fields_value_14" type="text" value="<?php echo $fields_value[14]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields22".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Ongoing Hazard Management</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields22,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields22"><input name="fields_value_15" type="text" value="<?php echo $fields_value[15]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields23".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Cuts/Sharps</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields23,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields23"><input name="fields_value_16" type="text" value="<?php echo $fields_value[16]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields24".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Manual Lifting</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields24,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields24"><input name="fields_value_17" type="text" value="<?php echo $fields_value[17]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields25".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Mechanical lifting</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields25,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields25"><input name="fields_value_18" type="text" value="<?php echo $fields_value[18]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields26".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Slip/trips/falls</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields26,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields26"><input name="fields_value_19" type="text" value="<?php echo $fields_value[19]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields27".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Working Alone</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields27,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields27"><input name="fields_value_20" type="text" value="<?php echo $fields_value[20]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields28".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Overhead lines</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields28,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields28"><input name="fields_value_21" type="text" value="<?php echo $fields_value[21]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields29".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Pinch points / crushing</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields29,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields29"><input name="fields_value_22" type="text" value="<?php echo $fields_value[22]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields30".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Rigging/ropes/slings/cable</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields30,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields30"><input name="fields_value_23" type="text" value="<?php echo $fields_value[23]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
            </ul>

            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info2" >
                    Safety Equipment / PPE<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info2" class="panel-collapse collapse">
            <div class="panel-body">

            <ul style="list-style-type: none;">
                <?php if (strpos($form_config, ','."fields31".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Tag lines</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields31,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields31"><input name="fields_value_24" type="text" value="<?php echo $fields_value[24]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields32".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Shoring</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields32,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields32"><input name="fields_value_25" type="text" value="<?php echo $fields_value[25]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields33".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Gloves</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields33,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields33"><input name="fields_value_26" type="text" value="<?php echo $fields_value[26]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields34".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Fire extinguishers</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields34,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields34"><input name="fields_value_27" type="text" value="<?php echo $fields_value[27]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields35".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Foot protection</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields35,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields35"><input name="fields_value_28" type="text" value="<?php echo $fields_value[28]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields36".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Warning signs</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields36,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields36"><input name="fields_value_29" type="text" value="<?php echo $fields_value[29]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields37".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Hard hat</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields37,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields37"><input name="fields_value_30" type="text" value="<?php echo $fields_value[30]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields38".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Full body clothing(FRC)</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields38,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields38"><input name="fields_value_31" type="text" value="<?php echo $fields_value[31]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields39".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Fall arrest protection system</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields39,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields39"><input name="fields_value_32" type="text" value="<?php echo $fields_value[32]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields40".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Eye protection</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields40,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields40"><input name="fields_value_33" type="text" value="<?php echo $fields_value[33]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields41".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Ground distrubance</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields41,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields41"><input name="fields_value_34" type="text" value="<?php echo $fields_value[34]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields42".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">SCBA (H2S site)</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields42,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields42"><input name="fields_value_35" type="text" value="<?php echo $fields_value[35]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
            </ul>

            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info3" >
                    Procedures / Checklists<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info3" class="panel-collapse collapse">
            <div class="panel-body">

            <ul style="list-style-type: none;">
                <?php if (strpos($form_config, ','."fields43".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Change managemet</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields43,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields43"><input name="fields_value_36" type="text" value="<?php echo $fields_value[36]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields44".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Confined space</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields44,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields44"><input name="fields_value_37" type="text" value="<?php echo $fields_value[37]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields45".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Emergency response/Muster Pt.</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields45,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields45"><input name="fields_value_38" type="text" value="<?php echo $fields_value[38]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields46".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Incident reporting</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields46,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields46"><input name="fields_value_39" type="text" value="<?php echo $fields_value[39]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields47".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Towing vehicles</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields47,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields47"><input name="fields_value_40" type="text" value="<?php echo $fields_value[40]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields48".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Lockout / tag out</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields48,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields48"><input name="fields_value_41" type="text" value="<?php echo $fields_value[41]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields49".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">H2S</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields49,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields49"><input name="fields_value_42" type="text" value="<?php echo $fields_value[42]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields50".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Orientations</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields50,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields50"><input name="fields_value_43" type="text" value="<?php echo $fields_value[43]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields51".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Scaffold inspection</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields51,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields51"><input name="fields_value_44" type="text" value="<?php echo $fields_value[44]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields52".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Smoking</label>
                    <div class="col-sm-8"><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields52,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields52"><input name="fields_value_45" type="text" value="<?php echo $fields_value[45]; ?>" class="form-control" />
                    </div>
                </div>
                <?php } ?>
            </ul>

            </div>
        </div>
    </div>

    <?php if (strpos($form_config, ','."fields53".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info4" >
                    Hazards, Controls, Type Of Control Measure, Risk <span class="glyphicon glyphicon-plus"></span>
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
                        <th>Hazard</th>
                        <th>Control</th>
                        <th>Type Of Control Measure</th>
                        <th>Risk</th>
                    ";
                }
                for($client_loop=0; $client_loop<=$total_count; $client_loop++) {
                    $task_item = explode('**',$all_task_each[$client_loop]);
                    $task = $task_item[0];
                    $hazard = $task_item[1];
                    $level = $task_item[2];
                    $plan = $task_item[3];
                    if($task != '') {
                        echo '<tr>';
                        echo '<td data-title="Email">' . $task . '</td>';
                        echo '<td data-title="Email">' . $hazard . '</td>';
                        echo '<td data-title="Email">' . $level . '</td>';
                        echo '<td data-title="Email">' . $plan . '</td>';
                        echo '</tr>';
                    }
                }
                echo '</table>';
                ?>
                <div class="additional_hazard clearfix">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-6 padded">
                            <p>Hazards</p>
                            <input type="text" name="task[]" class="form-control"/>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-6 padded">
                            <p>Controls</p>
                            <input type="text" name="hazard[]" class="form-control"/>
                        </div>
                        <div class="col-md-2 col-sm-6 col-xs-6 padded">
                            <p>Type Of Control Measure</p>
                            <input type="text" name="hazard_level[]" class="form-control"/>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-6 padded">
                            <p>Risk</p>
                            <input type="text" name="hazard_plan[]" class="form-control"/>
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info5" >
                    Tailgate Safety Meeting Info<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info5" class="panel-collapse collapse">
            <div class="panel-body">

                <?php if (strpos($form_config, ','."fields62".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Time:</label>
                    <div class="col-sm-8">
                        <input name="form_time" value="<?php echo $form_time; ?>" type="text" class="form-control" />
                    </div>
                  </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields63".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Location / Job #:</label>
                    <div class="col-sm-8">
                        <input name="location" value="<?php echo $location; ?>" type="text" class="form-control" />
                    </div>
                  </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields64".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Safety Topic:</label>
                    <div class="col-sm-8">
                        <input name="safety_topic" value="<?php echo $safety_topic; ?>" type="text" class="form-control" />
                    </div>
                  </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields65".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Concerns:</label>
                    <div class="col-sm-8">
                        <input name="concerns" value="<?php echo $concerns; ?>" type="text" class="form-control" />
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
                <br>
                <ul style="list-style-type: none;">
                    <?php if (strpos($form_config, ','."fields54".',') !== FALSE) { ?>
                        <input type="checkbox" name="staffcheck_<?php echo $assign_staff_id;?>[]" value="staffcheck1">Orient&nbsp;&nbsp;
                    <?php } ?>
                    <?php if (strpos($form_config, ','."fields55".',') !== FALSE) { ?>
                    <input type="checkbox" name="staffcheck_<?php echo $assign_staff_id;?>[]" value="staffcheck2">H2S&nbsp;&nbsp;
                    <?php } ?>
                    <?php if (strpos($form_config, ','."fields56".',') !== FALSE) { ?>
                    <input type="checkbox" name="staffcheck_<?php echo $assign_staff_id;?>[]" value="staffcheck3">1st Aid&nbsp;&nbsp;
                    <?php } ?>
                    <?php if (strpos($form_config, ','."fields57".',') !== FALSE) { ?>
                    <input type="checkbox" name="staffcheck_<?php echo $assign_staff_id;?>[]" value="staffcheck4">TDG&nbsp;&nbsp;
                    <?php } ?>
                    <?php if (strpos($form_config, ','."fields58".',') !== FALSE) { ?>
                    <input type="checkbox" name="staffcheck_<?php echo $assign_staff_id;?>[]" value="staffcheck5">Confined Space&nbsp;&nbsp;
                    <?php } ?>
                    <?php if (strpos($form_config, ','."fields59".',') !== FALSE) { ?>
                    <input type="checkbox" name="staffcheck_<?php echo $assign_staff_id;?>[]" value="staffcheck6">WHMIS&nbsp;&nbsp;
                    <?php } ?>
                    <?php if (strpos($form_config, ','."fields60".',') !== FALSE) { ?>
                    <input type="checkbox" name="staffcheck_<?php echo $assign_staff_id;?>[]" value="staffcheck7">Gr.Dis.&nbsp;&nbsp;
                    <?php } ?>
                    <?php if (strpos($form_config, ','."fields61".',') !== FALSE) { ?>
                    <input type="checkbox" name="staffcheck_<?php echo $assign_staff_id;?>[]" value="staffcheck8">PST&nbsp;&nbsp;
                    <?php } ?>
                </ul>

                <?php } ?>

            </div>
        </div>
    </div>
    <?php $sa_inc++;
        }
    } ?>

</div>
