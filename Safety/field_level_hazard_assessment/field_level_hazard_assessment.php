<?php
/*
Add	Sheet
*/
include ('../database_connection.php');
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

        var worker_inc = 1;
        $('#add_row_worker').on( 'click', function () {
            $(".hide_show_service").show();
            var clone = $('.additional_worker').clone();
            clone.find('.chosen-select-deselect').val("");
            clone.find('.chosen-select-deselect').attr('id', 'worker_' + worker_inc);
            clone.find('#worker_0_chosen').attr('id', 'worker_1_chosen');
            clone.find('.chosen-select-deselect').trigger("change.select2");
            clone.find('.output').attr('name', 'sign_worker_' + worker_inc);
            clone.removeClass("additional_worker");
            $('#add_here_new_worker').append(clone);
            resetChosen($("#worker_" + worker_inc));

            var options = {
              drawOnly : true,
              validateFields : false
            };
            $('.sigPad').signaturePad(options);
            $('#linear').signaturePad({drawOnly:true, lineTop:200});
            $('#smoothed').signaturePad({drawOnly:true, drawBezierCurves:true, lineTop:200});
            $('#smoothed-variableStrokeWidth').signaturePad({drawOnly:true, drawBezierCurves:true, variableStrokeWidth:true, lineTop:200});

            worker_inc++;
            return false;
        });
	});
$(document).on('change', 'select[name="equipment_selector[]"]', function() { $('[name=fields_value_160]').val($(this).val()); });
$(document).on('change', 'select.staff_selector', function() { $('[name=fields_value_114]').val($(this).val()); });
$(document).on('change', 'select.staff_selector2', function() { $('[name=fields_value_115]').val($(this).val()); });

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
function add_location() {
    var row = $('.additional_location').last();
    var clone = row.clone();
    clone.find('.form-control').val('');
    resetChosen(clone.find('select'));
    row.after(clone);
}
function remove_location(target) {
    if($('.additional_location').length <= 1) {
        add_location();
    }
    $(target).closest('.additional_location').remove();
}
</script>
</head>
<body>

Check all hazards that may be present during the task(s)

<?php
$today_date = date('Y-m-d');
$jobid = '';
$contactid = $_SESSION['contactid'];
$location = [''];
$fields = '';
$fields_value = '';
$working_alone = '';
$all_task = '';
$job_complete = '';
$job_complete_value = '';

    if(!empty($_GET['formid'])) {
        $formid = $_GET['formid'];

        echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';

        $get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_field_level_risk_assessment WHERE fieldlevelriskid='$formid'"));

        $today_date = $get_field_level['today_date'];
        $jobid = $get_field_level['jobid'];
        $contactid = $get_field_level['contactid'];
        $location = explode('*#*',$get_field_level['location']);
        $fields = $get_field_level['fields'];
        $fields_value = explode('**FFM**', $get_field_level['fields_value']);
        $job_complete_value = explode('**FFM**', $get_field_level['job_complete_value']);
        $working_alone = $get_field_level['working_alone'];
        $all_task = $get_field_level['all_task'];
        $job_complete = $get_field_level['job_complete'];
        
    }
?>

<?php
//$form_config = ','.get_config($dbc, 'form_field_level_risk_assessment').',';
$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$form'"));
$form_config = ','.$get_field_config['fields'].',';
?>

<?php if(strpos_any([',fields118,',',fields119,',',fields120,',',fields121,'], $form_config)) { ?>
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
				<?php if (strpos($form_config, ','."fields118".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Date:</label>
				<div class="col-sm-8">
				<input type="text" name="today_date" value="<?php echo $today_date; ?>" class="form-control datepicker" />
				</div>
				</div>
				<?php } ?>

				<?php if (strpos($form_config, ','."fields119".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Job#:</label>
				<div class="col-sm-8">
				<input type="text" name="jobid" value="<?php echo $jobid; ?>" class="form-control" />
				</div>
				</div>
				<?php } ?>

				<?php if (strpos($form_config, ','."fields120".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Contact Id:</label>
				<div class="col-sm-8">
				<input type="text" name="contactid" value="<?php echo $contactid; ?>" class="form-control" />
				</div>
				</div>
				<?php } ?>
				<?php if (strpos($form_config, ','."fields121".',') !== FALSE) {
                    foreach($location as $single_location) { ?>
    				<div class="form-group additional_location">
    				<label for="business_street" class="col-sm-4 control-label">Location:</label>
    				<div class="col-sm-7">
    				<!-- <input type="text" name="location" value="<?php echo $location; ?>" class="form-control" /> -->
                    <select name="location[]" class="chosen-select-deselect form-control"><option></option>
                        <?php
                        $site_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT sites.`contactid`, sites.`site_name` status, bus.`name` first_name FROM `contacts` sites LEFT JOIN `contacts` bus on sites.`contactid`=bus.`siteid` WHERE sites.`category`='Sites' AND sites.`deleted`=0 AND sites.`status`>0 AND sites.`show_hide_user`=1"), MYSQLI_ASSOC));
                        foreach ($site_list as $site_id) {
                            $site_name = get_contact($dbc, $site_id, 'site_name');
                            echo '<option '.($site_name == $single_location ? 'selected' : '').' value="'.$site_name.'">'.$site_name.'</option>';
                        }
                        ?>
                    </select>
    				</div>
                    <div class="col-sm-1">
                        <img src="../img/icons/plus.png" class="inline-img pull-right" onclick="add_location();">
                        <img src="../img/remove.png" class="inline-img pull-right" onclick="remove_location(this);">
                    </div>
    				</div>
                    <?php }
                } ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php if(strpos_any([',fields1,',',fields2,',',fields3,',',fields108,',',fields4,',',fields5,',',fields6,',',fields7,',',fields161,',',fields161,'], $form_config)) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_2" >
                    Permits/Plans<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_2" class="panel-collapse collapse">
            <div class="panel-body">

                <?php if (strpos($form_config, ','."fields1".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Hot Work/Cold Work</label>
                    <div class="col-sm-8">
                        <input type="checkbox" <?php if (strpos(','.$fields.',', ',fields1,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields1"><input name="fields_value_1" type="text" value="<?php echo $fields_value[1]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>

                <?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Confined Space</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields2,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields2"><input name="fields_value_2" type="text" value="<?php echo $fields_value[2]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>


                <?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Demolition</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields3,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields3"><input name="fields_value_3" type="text" value="<?php echo $fields_value[3]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields108".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Ground Disturbance</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields108,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields108"><input name="fields_value_108" type="text" value="<?php echo $fields_value[108]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Excavation</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields4,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields4"><input name="fields_value_4" type="text" value="<?php echo $fields_value[4]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Lockout</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields5,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields5"><input name="fields_value_5" type="text" value="<?php echo $fields_value[5]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Critical Lift Plan</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields6,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields6"><input name="fields_value_6" type="text" value="<?php echo $fields_value[6]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields7".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Fall Protection Plan</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields7,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields7"><input name="fields_value_7" type="text" value="<?php echo $fields_value[7]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields161".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Road Closure Permit</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields161,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields161"><input name="fields_value_161" type="text" value="<?php echo $fields_value[161]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields162".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Locates Expiration</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields162,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields162"><input name="fields_value_162" type="text" value="<?php echo $fields_value[162]; ?>" class="form-control datepicker" />
                    </div>
                    </div>
                <?php } ?>


            </div>
        </div>
    </div>
<?php } ?>
<?php if(strpos_any([',fields8,',',fields9,',',fields10,',',fields11,',',fields12,',',fields13,',',fields14,',',fields15,'], $form_config)) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_3" >
                    Permit Identified Hazards<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_3" class="panel-collapse collapse">
            <div class="panel-body">

                <?php if (strpos($form_config, ','."fields8".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Hazards Detailed on Safe Work Permit</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields8,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields8"><input name="fields_value_8" type="text" value="<?php echo $fields_value[8]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields9".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Hazards on Critical Lift Permit</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields9,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields9"><input name="fields_value_9" type="text" value="<?php echo $fields_value[9]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields10".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Hazards on Electrical Permit</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields10,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields10"><input name="fields_value_10" type="text" value="<?php echo $fields_value[10]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields11".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Hazards Identified for Confined Space Entry</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields11,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields11"><input name="fields_value_11" type="text" value="<?php echo $fields_value[11]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields12".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Hazards on Confined Space Entry Permit</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields12,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields12"><input name="fields_value_12" type="text" value="<?php echo $fields_value[12]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields13".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Hazards on Hot/Cold Work Permit</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields13,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields13"><input name="fields_value_13" type="text" value="<?php echo $fields_value[13]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields14".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Hazards on Underground/ Excavation, Permit</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields14,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields14"><input name="fields_value_14" type="text" value="<?php echo $fields_value[14]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields15".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Hazards on Line Opening Permit</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields15,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields15"><input name="fields_value_15" type="text" value="<?php echo $fields_value[15]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
<?php } ?>
<?php if(strpos_any([',fields16,',',fields17,',',fields18,',',fields109,',',fields19,',',fields157,',',fields158,',',fields159,',',fields160,'], $form_config)) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_4" >
                    Emergency Equipment<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_4" class="panel-collapse collapse">
            <div class="panel-body">


                <?php if (strpos($form_config, ','."fields16".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Fire Extinguisher</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields16,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields16"><input name="fields_value_16" type="text" value="<?php echo $fields_value[16]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields17".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Eyewash/Shower</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields17,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields17"><input name="fields_value_17" type="text" value="<?php echo $fields_value[17]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields109".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">All Conditions Met</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields109,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields109"><input name="fields_value_109" type="text" value="<?php echo $fields_value[109]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields18".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Extraction Equipment</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields18,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields18"><input name="fields_value_18" type="text" value="<?php echo $fields_value[18]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields19".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Permit Displayed</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields19,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields19"><input name="fields_value_19" type="text" value="<?php echo $fields_value[19]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields157".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">First Aid Kit</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields157,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields157"><input name="fields_value_157" type="text" value="<?php echo $fields_value[157]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields158".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Spill Kit</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields158,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields158"><input name="fields_value_158" type="text" value="<?php echo $fields_value[158]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields159".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Road Flares</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields159,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields159"><input name="fields_value_159" type="text" value="<?php echo $fields_value[159]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields160".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Location of Emergency Equipment</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields160,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields160"><input name="fields_value_160" type="hidden" value="<?php echo $fields_value[160]; ?>" />
						<select data-placeholder="Select Locations" name="equipment_selector[]" multiple class="chosen-select-deselect" class="chosen-select-deselect"><option></option>
							<?php $equip_query = mysqli_query($dbc, "SELECT * from `equipment` WHERE `category` IN ('Vehicles','Truck')");
							while($equip_row = mysqli_fetch_array($equip_query)) {
								$label = trim($equip_row['unit_number'].' '.$equip_row['label']);
								echo "<option ".(in_array($label, explode(',',$field_value[160])) ? 'selected' : '')." value='$label'>$label</option>";
							} ?>
						</select>
                    </div>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
<?php } ?>
<?php if(strpos_any([',fields20,',',fields21,',',fields22,',',fields23,',',fields110,',',fields24,',',fields25,',',fields26,',',fields27,'], $form_config)) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_5" >
                    Overhead Or Working At Height Hazards<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_5" class="panel-collapse collapse">
            <div class="panel-body">


                <?php if (strpos($form_config, ','."fields20".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Harness Required/Appropriate Tie-off identified</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields20,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields20"><input name="fields_value_20" type="text" value="<?php echo $fields_value[20]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields21".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Others Working Overhead/Below</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields21,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields21"><input name="fields_value_21" type="text" value="<?php echo $fields_value[21]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields22".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Hoisting or Moving Loads Overhead</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields22,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields22"><input name="fields_value_22" type="text" value="<?php echo $fields_value[22]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields23".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Falls From Height</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields23,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields23"><input name="fields_value_23" type="text" value="<?php echo $fields_value[23]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields24".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Hoisting or Moving Loads Overhead/Around Task</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields24,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields24"><input name="fields_value_24" type="text" value="<?php echo $fields_value[24]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields110".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Use of Scaffolds</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields110,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields110"><input name="fields_value_110" type="text" value="<?php echo $fields_value[110]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields25".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Tasks Requiring You to Work Above Your Task</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields25,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields25"><input name="fields_value_25" type="text" value="<?php echo $fields_value[25]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields26".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Objects / Debris Falling From Above</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields26,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields26"><input name="fields_value_26" type="text" value="<?php echo $fields_value[26]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields27".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Overhead Power Line</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields27,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields27"><input name="fields_value_27" type="text" value="<?php echo $fields_value[27]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>


            </div>
        </div>
    </div>
<?php } ?>
<?php if(strpos_any([',fields28,',',fields29,',',fields30,',',fields31,',',fields32,',',fields33,',',fields34,',',fields35,',',fields36,'], $form_config)) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_6" >
                    Equipment Hazards<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_6" class="panel-collapse collapse">
            <div class="panel-body">


                <?php if (strpos($form_config, ','."fields28".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Operating Power Equipment</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields28,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields28"><input name="fields_value_28" type="text" value="<?php echo $fields_value[28]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields29".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Operating Motor Vehicle / Heavy Equipment</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields29,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields29"><input name="fields_value_29" type="text" value="<?php echo $fields_value[29]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields30".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Contract With / Contract By</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields30,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields30"><input name="fields_value_30" type="text" value="<?php echo $fields_value[30]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>

                <b>Working with:</b>
                        <?php if (strpos($form_config, ','."fields31".',') !== FALSE) { ?>
                         <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Saws</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields31,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields31"><input name="fields_value_31" type="text" value="<?php echo $fields_value[31]; ?>" class="form-control" />
                    </div>
                    </div>
                        <?php } ?>
                        <?php if (strpos($form_config, ','."fields32".',') !== FALSE) { ?>
                         <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Cutting Torch Equipment</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields32,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields32"><input name="fields_value_32" type="text" value="<?php echo $fields_value[32]; ?>" class="form-control" />
                    </div>
                    </div>
                        <?php } ?>
                        <?php if (strpos($form_config, ','."fields33".',') !== FALSE) { ?>
                         <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Hand Tools</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields33,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields33"><input name="fields_value_33" type="text" value="<?php echo $fields_value[33]; ?>" class="form-control" />
                    </div>
                    </div>
                        <?php } ?>
                        <?php if (strpos($form_config, ','."fields34".',') !== FALSE) { ?>
                         <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Grinders</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields34,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields34"><input name="fields_value_34" type="text" value="<?php echo $fields_value[34]; ?>" class="form-control" />
                    </div>
                    </div>
                        <?php } ?>
                        <?php if (strpos($form_config, ','."fields35".',') !== FALSE) { ?>
                         <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Welding Machines</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields35,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields35"><input name="fields_value_35" type="text" value="<?php echo $fields_value[35]; ?>" class="form-control" />
                    </div>
                    </div>
                        <?php } ?>
                        <?php if (strpos($form_config, ','."fields36".',') !== FALSE) { ?>
                         <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Cranes</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields36,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields36"><input name="fields_value_36" type="text" value="<?php echo $fields_value[36]; ?>" class="form-control" />
                    </div>
                    </div>
                        <?php } ?>

            </div>
        </div>
    </div>
<?php } ?>
<?php if(strpos_any([',fields37,',',fields38,',',fields39,',',fields40,',',fields41,',',fields42,',',fields43,',',fields44,',',fields45,',',fields46,',',fields47,',',fields48,',',fields49,',',fields50,',',fields51,',',fields52,',',fields53,',',fields54,'], $form_config)) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_7" >
                    Work Environment Hazards<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_7" class="panel-collapse collapse">
            <div class="panel-body">


                <?php if (strpos($form_config, ','."fields37".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Weather Conditions</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields37,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields37"><input name="fields_value_37" type="text" value="<?php echo $fields_value[37]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields38".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Slips or Trips Possible</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields38,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields38"><input name="fields_value_38" type="text" value="<?php echo $fields_value[38]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields39".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Waste Material Generated Performing Task</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields39,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields39"><input name="fields_value_39" type="text" value="<?php echo $fields_value[39]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields40".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Limited Access / Egress</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields40,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields40"><input name="fields_value_40" type="text" value="<?php echo $fields_value[40]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields41".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Foreign Bodies in Eyes</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields41,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields41"><input name="fields_value_41" type="text" value="<?php echo $fields_value[41]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields42".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Exposure to Energized Electrical Systems</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields42,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields42"><input name="fields_value_42" type="text" value="<?php echo $fields_value[42]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields43".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Lighing Levels Too High/Too Low</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields43,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields43"><input name="fields_value_43" type="text" value="<?php echo $fields_value[43]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields44".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Position of Fingers / Hands - Pinch Points</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields44,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields44"><input name="fields_value_44" type="text" value="<?php echo $fields_value[44]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>

                <b>Exposure to:</b>

                        <?php if (strpos($form_config, ','."fields45".',') !== FALSE) { ?>
                         <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Chemicals</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields45,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields45"><input name="fields_value_45" type="text" value="<?php echo $fields_value[45]; ?>" class="form-control" />
                    </div>
                    </div>
                        <?php } ?>
                        <?php if (strpos($form_config, ','."fields46".',') !== FALSE) { ?>
                         <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Dust/Particulates</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields46,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields46"><input name="fields_value_46" type="text" value="<?php echo $fields_value[46]; ?>" class="form-control" />
                    </div>
                    </div>
                        <?php } ?>
                        <?php if (strpos($form_config, ','."fields47".',') !== FALSE) { ?>
                         <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Extreme Heat/Cold</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields47,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields47"><input name="fields_value_47" type="text" value="<?php echo $fields_value[47]; ?>" class="form-control" />
                    </div>
                    </div>
                        <?php } ?>
                        <?php if (strpos($form_config, ','."fields48".',') !== FALSE) { ?>
                         <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Reactive Chemicals</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields48,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields48"><input name="fields_value_48" type="text" value="<?php echo $fields_value[48]; ?>" class="form-control" />
                    </div>
                    </div>
                        <?php } ?>
                        <?php if (strpos($form_config, ','."fields49".',') !== FALSE) { ?>
                         <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Sharp Objects / Edges</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields49,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields49"><input name="fields_value_49" type="text" value="<?php echo $fields_value[49]; ?>" class="form-control" />
                    </div>
                    </div>
                        <?php } ?>
                        <?php if (strpos($form_config, ','."fields50".',') !== FALSE) { ?>
                         <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Noise</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields50,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields50"><input name="fields_value_50" type="text" value="<?php echo $fields_value[50]; ?>" class="form-control" />
                    </div>
                    </div>
                        <?php } ?>
                        <?php if (strpos($form_config, ','."fields51".',') !== FALSE) { ?>
                         <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Odors</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields51,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields51"><input name="fields_value_51" type="text" value="<?php echo $fields_value[51]; ?>" class="form-control" />
                    </div>
                    </div>
                        <?php } ?>
                        <?php if (strpos($form_config, ','."fields52".',') !== FALSE) { ?>
                         <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Steam</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields52,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields52"><input name="fields_value_52" type="text" value="<?php echo $fields_value[52]; ?>" class="form-control" />
                    </div>
                    </div>
                        <?php } ?>
                        <?php if (strpos($form_config, ','."fields53".',') !== FALSE) { ?>
                         <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Fogging of Monogoggles / Eye Protection</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields53,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields53"><input name="fields_value_53" type="text" value="<?php echo $fields_value[53]; ?>" class="form-control" />
                    </div>
                    </div>
                        <?php } ?>
                        <?php if (strpos($form_config, ','."fields54".',') !== FALSE) { ?>
                         <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Flammable Gases / Atmospheric Hazards</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields54,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields54"><input name="fields_value_54" type="text" value="<?php echo $fields_value[54]; ?>" class="form-control" />
                    </div>
                    </div>
                        <?php } ?>

            </div>
        </div>
    </div>
<?php } ?>
<?php if(strpos_any([',fields55,',',fields56,',',fields57,',',fields58,',',fields59,',',fields60,',',fields61,',',fields62,'], $form_config)) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_8" >
                    Personal Limitations/Hazards<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_8" class="panel-collapse collapse">
            <div class="panel-body">


                <?php if (strpos($form_config, ','."fields55".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Procedure Not Available for Task</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields55,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields55"><input name="fields_value_55" type="text" value="<?php echo $fields_value[55]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields56".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Confusing Instructions</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields56,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields56"><input name="fields_value_56" type="text" value="<?php echo $fields_value[56]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields57".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">No Training in Procedure / Task</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields57,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields57"><input name="fields_value_57" type="text" value="<?php echo $fields_value[57]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields58".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">No Training in Tools to be Used</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields58,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields58"><input name="fields_value_58" type="text" value="<?php echo $fields_value[58]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields59".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">First Time Performing This Task</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields59,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields59"><input name="fields_value_59" type="text" value="<?php echo $fields_value[59]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields60".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Mental Limitations / Distractions / Loss of Focus</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields60,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields60"><input name="fields_value_60" type="text" value="<?php echo $fields_value[60]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields61".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Not Physically Able to Perform Task</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields61,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields61"><input name="fields_value_61" type="text" value="<?php echo $fields_value[61]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields62".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Complacency</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields62,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields62"><input name="fields_value_62" type="text" value="<?php echo $fields_value[62]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>


            </div>
        </div>
    </div>
<?php } ?>
<?php if(strpos_any([',fields63,',',fields64,',',fields65,',',fields66,',',fields67,',',fields68,',',fields69,',',fields70,',',fields71,',',fields72,'], $form_config)) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_9" >
                    Welding<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_9" class="panel-collapse collapse">
            <div class="panel-body">

                <?php if (strpos($form_config, ','."fields63".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Shields</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields63,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields63"><input name="fields_value_63" type="text" value="<?php echo $fields_value[63]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields64".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Fire Blankets</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields64,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields64"><input name="fields_value_64" type="text" value="<?php echo $fields_value[64]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields65".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Fire Extinguisher</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields65,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields65"><input name="fields_value_65" type="text" value="<?php echo $fields_value[65]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields66".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Cylinder Secured / Secure Connections</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields66,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields66"><input name="fields_value_66" type="text" value="<?php echo $fields_value[66]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields67".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Cylinder Caps On</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields67,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields67"><input name="fields_value_67" type="text" value="<?php echo $fields_value[67]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields68".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Flashback Arrestor</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields68,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields68"><input name="fields_value_68" type="text" value="<?php echo $fields_value[68]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields69".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Combustibles Moved</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields69,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields69"><input name="fields_value_69" type="text" value="<?php echo $fields_value[69]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields70".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Sparks Contained</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields70,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields70"><input name="fields_value_70" type="text" value="<?php echo $fields_value[70]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields71".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Ground Within 18 Inches</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields71,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields71"><input name="fields_value_71" type="text" value="<?php echo $fields_value[71]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields72".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Fire Watch / Spark Watch</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields72,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields72"><input name="fields_value_72" type="text" value="<?php echo $fields_value[72]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>


            </div>
        </div>
    </div>
<?php } ?>
<?php if(strpos_any([',fields73,',',fields74,',',fields75,',',fields76,',',fields77,',',fields78,',',fields79,',',fields80,',',fields81,',',fields82,',',fields83,'], $form_config)) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_10" >
                    Physical Hazards<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_10" class="panel-collapse collapse">
            <div class="panel-body">


                <b>Manual Lifting</b>
                <?php if (strpos($form_config, ','."fields73".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Load Too Heavy / Awkward To Lift</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields73,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields73"><input name="fields_value_73" type="text" value="<?php echo $fields_value[73]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields74".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Over Reaching</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields74,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields74"><input name="fields_value_74" type="text" value="<?php echo $fields_value[74]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields75".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Prolonged / Extreme Bending</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields75,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields75"><input name="fields_value_75" type="text" value="<?php echo $fields_value[75]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields76".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Repetitive Motions</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields76,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields76"><input name="fields_value_76" type="text" value="<?php echo $fields_value[76]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields77".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Unstable Position</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields77,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields77"><input name="fields_value_77" type="text" value="<?php echo $fields_value[77]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields78".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Part(s) of Body in Line of Fire</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields78,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields78"><input name="fields_value_78" type="text" value="<?php echo $fields_value[78]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields79".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Hands Not in Line of Sight</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields79,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields79"><input name="fields_value_79" type="text" value="<?php echo $fields_value[79]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields80".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Working in Tight Clearances</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields80,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields80"><input name="fields_value_80" type="text" value="<?php echo $fields_value[80]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields81".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Physical Limitation - Need Assistance</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields81,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields81"><input name="fields_value_81" type="text" value="<?php echo $fields_value[81]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields82".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Uncontrolled Release of Energy / Force</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields82,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields82"><input name="fields_value_82" type="text" value="<?php echo $fields_value[82]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields83".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Fall Potential</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields83,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields83"><input name="fields_value_83" type="text" value="<?php echo $fields_value[83]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>


            </div>
        </div>
    </div>
<?php } ?>
<?php if(strpos_any([',fields122,',',fields123,',',fields124,',',fields125,',',fields126,',',fields127,',',fields128,',',fields129,',',fields130,',',fields131,',',fields132,',',fields133,',',fields134,',',fields135,',',fields136,',',fields137,',',fields164,',',fields165,',',fields166,',',fields138,'], $form_config)) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_c_h" >
                    Common Hazards<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_c_h" class="panel-collapse collapse">
            <div class="panel-body">
                <?php if (strpos($form_config, ','."fields122".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Overhead Powerlines</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields122,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields122"><input name="fields_value_122" type="text" value="<?php echo $fields_value[122]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields123".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Underground Hazards (Gas Lines)</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields123,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields123"><input name="fields_value_123" type="text" value="<?php echo $fields_value[123]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields124".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Traffic</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields124,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields124"><input name="fields_value_124" type="text" value="<?php echo $fields_value[124]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields125".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Pedestrians</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields125,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields125"><input name="fields_value_125" type="text" value="<?php echo $fields_value[125]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields126".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Open Excavation</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields126,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields126"><input name="fields_value_126" type="text" value="<?php echo $fields_value[126]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields127".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Working Around Extreme Heat</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields127,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields127"><input name="fields_value_127" type="text" value="<?php echo $fields_value[127]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields128".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Heavy Lifting</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields128,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields128"><input name="fields_value_128" type="text" value="<?php echo $fields_value[128]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields129".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Working Alone</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields129,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields129"><input name="fields_value_129" type="text" value="<?php echo $fields_value[129]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields130".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Weather (heat, rain, snow)</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields130,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields130"><input name="fields_value_130" type="text" value="<?php echo $fields_value[130]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields131".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Noise</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields131,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields131"><input name="fields_value_131" type="text" value="<?php echo $fields_value[131]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields132".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Working From Heights</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields132,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields132"><input name="fields_value_132" type="text" value="<?php echo $fields_value[132]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields133".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Dust, Gases, Fumes</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields133,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields133"><input name="fields_value_133" type="text" value="<?php echo $fields_value[133]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields134".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Spraying Chemicals</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields134,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields134"><input name="fields_value_134" type="text" value="<?php echo $fields_value[134]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields135".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Faulty Equipment</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields135,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields135"><input name="fields_value_135" type="text" value="<?php echo $fields_value[135]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields136".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Branches Hitting Face and Eyes</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields136,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields136"><input name="fields_value_136" type="text" value="<?php echo $fields_value[136]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields137".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Slips, Trips, and Falls</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields137,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields137"><input name="fields_value_137" type="text" value="<?php echo $fields_value[137]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields164".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Hypothermia/ Frostbite</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields164,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields164"><input name="fields_value_164" type="text" value="<?php echo $fields_value[164]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields165".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Poor Lighting</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields165,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields165"><input name="fields_value_165" type="text" value="<?php echo $fields_value[165]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields166".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Ergonomic Strain (shoveling)</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields166,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields166"><input name="fields_value_166" type="text" value="<?php echo $fields_value[166]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields138".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Other Hazards</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields138,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields138"><input name="fields_value_138" type="text" value="<?php echo $fields_value[138]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php if(strpos_any([',fields139,',',fields140,',',fields141,',',fields142,',',fields143,',',fields144,',',fields145,',',fields146,',',fields147,',',fields148,',',fields149,',',fields150,',',fields151,',',fields152,',',fields153,',',fields154,',',fields155,'], $form_config)) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_j_s" >
                    Job Scope<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_j_s" class="panel-collapse collapse">
            <div class="panel-body">
                <?php if (strpos($form_config, ','."fields139".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Mowing</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields139,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields139"><input name="fields_value_139" type="text" value="<?php echo $fields_value[139]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields140".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Line Painting</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields140,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields140"><input name="fields_value_140" type="text" value="<?php echo $fields_value[140]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields141".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Construction/ Hardscaping</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields141,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields141"><input name="fields_value_141" type="text" value="<?php echo $fields_value[141]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields142".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Irrigation Start Up/ Breakdown</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields142,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields142"><input name="fields_value_142" type="text" value="<?php echo $fields_value[142]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields143".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Irrigation Repair</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields143,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields143"><input name="fields_value_143" type="text" value="<?php echo $fields_value[143]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields144".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Pesticide Spraying</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields144,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields144"><input name="fields_value_144" type="text" value="<?php echo $fields_value[144]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields145".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Summer Maintenance</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields145,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields145"><input name="fields_value_145" type="text" value="<?php echo $fields_value[145]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields146".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Spring Clean Up</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields146,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields146"><input name="fields_value_146" type="text" value="<?php echo $fields_value[146]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields147".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Fall Clean Up</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields147,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields147"><input name="fields_value_147" type="text" value="<?php echo $fields_value[147]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields148".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Power Washing/ Sanding</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields148,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields148"><input name="fields_value_148" type="text" value="<?php echo $fields_value[148]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields149".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Indoor</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields149,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields149"><input name="fields_value_149" type="text" value="<?php echo $fields_value[149]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields150".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Tree Planting/ Removal</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields150,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields150"><input name="fields_value_150" type="text" value="<?php echo $fields_value[150]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields151".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Pruning</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields151,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields151"><input name="fields_value_151" type="text" value="<?php echo $fields_value[151]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields152".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Watering</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields152,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields152"><input name="fields_value_152" type="text" value="<?php echo $fields_value[152]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields153".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Parkade Scrubbing</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields153,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields153"><input name="fields_value_153" type="text" value="<?php echo $fields_value[153]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields154".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Street Sweeping</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields154,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields154"><input name="fields_value_154" type="text" value="<?php echo $fields_value[154]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields155".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Other Job Scope</label>
                    <div class="col-sm-8">
                        <input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields155,') !== FALSE) { echo " checked"; } ?>name="fields[]" value="fields155"><input name="fields_value_155" type="text" value="<?php echo $fields_value[155]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php if(strpos_any([',fields84,',',fields85,',',fields86,',',fields87,',',fields88,',',fields89,',',fields90,',',fields91,',',fields92,',',fields93,',',fields94,',',fields95,',',fields96,',',fields97,',',fields98,',',fields156,'], $form_config)) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_11" >
                    Personal Protective Equipment<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_11" class="panel-collapse collapse">
            <div class="panel-body">


                <?php if (strpos($form_config, ','."fields84".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Work Gloves</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields84,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields84"><input name="fields_value_84" type="text" value="<?php echo $fields_value[84]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields85".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Chemical Gloves</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields85,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields85"><input name="fields_value_85" type="text" value="<?php echo $fields_value[85]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields86".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Kevlar Gloves</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields86,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields86"><input name="fields_value_86" type="text" value="<?php echo $fields_value[86]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields87".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Rain Gear</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields87,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields87"><input name="fields_value_87" type="text" value="<?php echo $fields_value[87]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields88".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Thermal Suits</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields88,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields88"><input name="fields_value_88" type="text" value="<?php echo $fields_value[88]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields89".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Rubber Boots</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields89,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields89"><input name="fields_value_89" type="text" value="<?php echo $fields_value[89]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields90".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Monogoggles/Faceshield</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields90,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields90"><input name="fields_value_90" type="text" value="<?php echo $fields_value[90]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields91".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Safety Glasses</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields91,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields91"><input name="fields_value_91" type="text" value="<?php echo $fields_value[91]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields92".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Respiratory Protection</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields92,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields92"><input name="fields_value_92" type="text" value="<?php echo $fields_value[92]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields93".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Hearing Protection</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields93,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields93"><input name="fields_value_93" type="text" value="<?php echo $fields_value[93]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields94".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Safety Harness/Lanyard/Lifeline</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields94,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields94"><input name="fields_value_94" type="text" value="<?php echo $fields_value[94]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields95".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Head Protection</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields95,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields95"><input name="fields_value_95" type="text" value="<?php echo $fields_value[95]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields96".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Steel-toed Work Boots</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields96,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields96"><input name="fields_value_96" type="text" value="<?php echo $fields_value[96]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields97".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Hi-Vis Vest</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields97,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields97"><input name="fields_value_97" type="text" value="<?php echo $fields_value[97]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields98".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Fire Retardant Wear</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields98,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields98"><input name="fields_value_98" type="text" value="<?php echo $fields_value[98]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields156".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Cut Proof Gloves/ Clothing</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields156,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields156"><input name="fields_value_156" type="text" value="<?php echo $fields_value[156]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
<?php } ?>
<?php if(strpos_any([',fields99,',',fields100,',',fields101,',',fields102,',',fields103,',',fields104,',',fields105,',',fields106,',',fields107,'], $form_config)) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_12" >
                    Walk Around/Inspection<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_12" class="panel-collapse collapse">
            <div class="panel-body">


                <?php if (strpos($form_config, ','."fields99".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Leaks</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields99,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields99"><input name="fields_value_99" type="text" value="<?php echo $fields_value[99]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields100".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Oil</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields100,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields100"><input name="fields_value_100" type="text" value="<?php echo $fields_value[100]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields101".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Fuel</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields101,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields101"><input name="fields_value_101" type="text" value="<?php echo $fields_value[101]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields102".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Tires</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields102,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields102"><input name="fields_value_102" type="text" value="<?php echo $fields_value[102]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields103".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Lights</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields103,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields103"><input name="fields_value_103" type="text" value="<?php echo $fields_value[103]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields104".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Windows</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields104,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields104"><input name="fields_value_104" type="text" value="<?php echo $fields_value[104]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields105".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Hoses</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields105,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields105"><input name="fields_value_105" type="text" value="<?php echo $fields_value[105]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields106".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Alarms</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields106,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields106"><input name="fields_value_106" type="text" value="<?php echo $fields_value[106]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>
                <?php if (strpos($form_config, ','."fields107".',') !== FALSE) { ?>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Bolts</label>
                    <div class="col-sm-8">
                        <input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields107,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields107"><input name="fields_value_107" type="text" value="<?php echo $fields_value[107]; ?>" class="form-control" />
                    </div>
                    </div>
                <?php } ?>


            </div>
        </div>
    </div>
<?php } ?>
    <?php if (strpos($form_config, ','."fields111".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_14" >
                    Is This Worker Working Alone?<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_14" class="panel-collapse collapse">
            <div class="panel-body">


                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label"></label>
                    <div class="col-sm-8">
                        <input type="radio" <?php if ($working_alone == 'Yes') { echo " checked"; } ?> name="working_alone" value="Yes">&nbsp;&nbsp;Yes
                        <input type="radio" <?php if ($working_alone == 'No') { echo " checked"; } ?> name="working_alone" value="No">&nbsp;&nbsp;No
                    </div>
                </div>


            </div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos($form_config, ','."fields112".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_13" >
                    Irregular Hazards<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_13" class="panel-collapse collapse">
            <div class="panel-body">

                <?php
                $all_task_each = explode('**##**',$all_task);

                $total_count = mb_substr_count($all_task,'**##**');
                if($total_count > 0) {
                    echo "<table class='table table-bordered'>";
                    echo "<tr class='hidden-xs hidden-sm'>
                    <th>Task</th>
                    <th>Hazard</th>
                    <th>Hazard Level</th>
                    <th>Plans To Eliminate/Control Risk</th>";
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
                            <p>Task(S)</p>
                            <input type="text" name="task[]" class="task_list"/>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-6 padded">
                            <p>Hazards</p>
                            <input type="text" name="hazard[]" class="task_list"/>
                        </div>
                        <div class="col-md-2 col-sm-6 col-xs-6 padded">
                            <p>Hazard Level</p>
                            <select name="hazard_level[]" class="task_list">
                                <option value="1 HIGH">1 HIGH</option>
                                <option value="2 MEDIUM">2 MEDIUM</option>
                                <option value="3 LOW">3 LOW</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-6 padded">
                            <p>Plans To  Eliminate/Control Risk</p>
                            <input type="text" name="hazard_plan[]" id="txtControlRisk" class="task_list"/>
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

    <?php if (strpos($form_config, ','."fields113".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_15" >
                    Clean Up / Close Out - Job Completion<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_15" class="panel-collapse collapse">
            <div class="panel-body">


                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Waste containers sealed, labeled  and dated?</label>
                    <div class="col-sm-8">
                        <input type="checkbox" <?php if (strpos(','.$job_complete.',', ',job_complete1,') !== FALSE) { echo " checked"; } ?> name="job_complete[]" value="job_complete1"><input name="job_complete_value_1" type="text" value="<?php echo $job_complete_value[1]; ?>" class="form-control" />
                    </div>
                    </div>

                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">All tools / equipment removed from Task Location?</label>
                    <div class="col-sm-8">
                        <input type="checkbox" <?php if (strpos(','.$job_complete.',', ',job_complete2,') !== FALSE) { echo " checked"; } ?> name="job_complete[]" value="job_complete2"><input name="job_complete_value_2" type="text" value="<?php echo $job_complete_value[2]; ?>" class="form-control" />
                    </div>
                    </div>
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Task area cleaned up at end of job / shift?</label>
                    <div class="col-sm-8">
                        <input type="checkbox" <?php if (strpos(','.$job_complete.',', ',job_complete3,') !== FALSE) { echo " checked"; } ?> name="job_complete[]" value="job_complete3"><input name="job_complete_value_3" type="text" value="<?php echo $job_complete_value[3]; ?>" class="form-control" />
                    </div>
                    </div>

            </div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos($form_config, ','."fields114".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_16" >
                    Designated First Aider<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_16" class="panel-collapse collapse">
            <div class="panel-body">
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Designated First Aider:</label>
                    <div class="col-sm-8">
                        <input type="checkbox" <?php if (strpos(','.$fields.',', ',fields114,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields114"><input name="fields_value_114" type="hidden" value="<?php echo $fields_value[114]; ?>" />
						<select name="staff_selector" class="chosen-select-deselect" class="chosen-select-deselect staff_selector2"><option></option>
							<?php $staff_query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT contactid, first_name, last_name from `contacts` WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 and status>0"),MYSQLI_ASSOC));
							foreach($staff_query as $staff_row) {
								$label = get_contact($dbc, $staff_row);
								echo "<option ".(in_array($label, explode(',',$field_value[115])) ? 'selected' : '')." value='$label'>$label</option>";
							} ?>
						</select>
                    </div>
				</div>
            </div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos($form_config, ','."fields115".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_17" >
                    Single Driver<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_17" class="panel-collapse collapse">
            <div class="panel-body">
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Single Driver:</label>
                    <div class="col-sm-8">
                        <input type="checkbox" <?php if (strpos(','.$fields.',', ',fields115,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields115"><input name="fields_value_115" type="hidden" value="<?php echo $fields_value[115]; ?>" />
						<select name="staff_selector" class="chosen-select-deselect" onchange="$('[name=fields_value_115]').val($(this).val());" class="chosen-select-deselect staff_selector2"><option></option>
							<?php $staff_query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT contactid, first_name, last_name from `contacts` WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 and status>0"),MYSQLI_ASSOC));
							foreach($staff_query as $staff_row) {
								$label = get_contact($dbc, $staff_row);
								echo "<option ".(in_array($label, explode(',',$field_value[115])) ? 'selected' : '')." value='$label'>$label</option>";
							} ?>
						</select>
                    </div>
				</div>
            </div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos($form_config, ','."fields116".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_18" >
                    Comments / Notes<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_18" class="panel-collapse collapse">
            <div class="panel-body">
                 <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Comments / Notes:</label>
                    <div class="col-sm-8">
                        <input type="checkbox" <?php if (strpos(','.$fields.',', ',fields116,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields116"><input name="fields_value_116" type="text" value="<?php echo $fields_value[116]; ?>" class="form-control" />
                    </div>
				</div>
            </div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos($form_config, ','."fields117".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_19" >
                    Signatures (Workers on Crew)<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_19" class="panel-collapse collapse">
            <div class="panel-body">
                <div class="clearfix form-group">
                    <label for="business_street" class="col-sm-4 control-label">Crew Leader:</label>
                    <div class="col-sm-8">
                        <select id="crew_leader" name="crew_leader" class="chosen-select-deselect"><option></option>
                            <?php $staff_query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT contactid, first_name, last_name from `contacts` WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 and status>0"),MYSQLI_ASSOC));
                            foreach($staff_query as $staff_row) {
                                $label = get_contact($dbc, $staff_row);
                                echo "<option ".(in_array($label, explode(',',$field_value[117])) ? 'selected' : '')." value='$label'>$label</option>";
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="additional_worker clearfix form-group">
                    <label for="business_street" class="col-sm-4 control-label">Worker:</label>
                    <div class="col-sm-8">
                        <select id="worker_0" name="workers_on_crew[]" class="chosen-select-deselect"><option></option>
                            <?php $staff_query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT contactid, first_name, last_name from `contacts` WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 and status>0"),MYSQLI_ASSOC));
                            foreach($staff_query as $staff_row) {
                                $label = get_contact($dbc, $staff_row);
                                echo "<option ".(in_array($label, explode(',',$field_value[117])) ? 'selected' : '')." value='$label'>$label</option>";
                            } ?>
                        </select>
                    </div>
                    <div class="col-sm-4"></div>
                    <div class="col-sm-8">
                        <?php
                            $output_name = 'sign_worker_0';
                            include ('../phpsign/sign_multiple.php');
                        ?>
                    </div>
                </div>
                <div id="add_here_new_worker"></div>
                <div class="form-group triple-gapped clearfix">
                    <div class="col-sm-offset-4 col-sm-8">
                        <button id="add_row_worker" class="btn brand-btn pull-left">Add Worker</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php } ?>

    <?php if(!empty($_GET['formid'])) {
		if($_GET['formid'] == 'new') { ?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_sa<?php echo $sa_inc;?>" >
							Signature<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>

				<div id="collapse_sa<?php echo $sa_inc;?>" class="panel-collapse collapse">
					<div class="panel-body">
						<?php $output_name = 'sign_new';
						include ('../phpsign/sign_multiple.php'); ?>
					</div>
				</div>
			</div>
		<?php } else {
			$sa = mysqli_query($dbc, "SELECT * FROM safety_attendance WHERE fieldlevelriskid = '$formid' AND safetyid='$safetyid'");
			$sa_inc=  0;
			while($row_sa = mysqli_fetch_array( $sa )) {
				$assign_staff_sa = $row_sa['assign_staff'];
				$assign_staff_id = $row_sa['safetyattid'];
				$assign_staff_done = $row_sa['done']; ?>
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
							if($assign_staff_done == 0) {
								$output_name = 'sign_'.$assign_staff_id; ?>
							<?php include ('../phpsign/sign_multiple.php'); ?>

							<?php if (strpos($assign_staff_sa, 'Extra') !== false) { ?>
							   <div class="form-group">
								<label for="business_street" class="col-sm-4 control-label">Name:</label>
								<div class="col-sm-8">
									<input name="assign_staff_<?php echo $assign_staff_id;?>" type="text" class="form-control" />
								</div>
							  </div>
							<?php } ?>
							</div>

							<?php } ?>

						</div>
					</div>
				</div>
				<?php $sa_inc++;
			}
		}
    } ?>


<style>
.form-control {
    width: 60%;
    display: inline;
}
</style>