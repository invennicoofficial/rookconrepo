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
$fields_value = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];
	echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_fall_protection_plan WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
    $fields = explode('**FFM**', $get_field_level['fields']);
    $fields_value = explode('**FFM**', $get_field_level['fields_value']);
    
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
                    <label for="business_street" class="col-sm-4 control-label">Job #</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_1" value="<?php echo $fields[1]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Worksite Location</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_2" value="<?php echo $fields[2]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Permit #</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_3" value="<?php echo $fields[3]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Client</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_4" value="<?php echo $fields[4]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Scope of Work</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_5" value="<?php echo $fields[5]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

			</div>
        </div>
    </div>

	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info2" >
                    Fall Hazards<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info2" class="panel-collapse collapse">
            <div class="panel-body">

				<?php if (strpos(','.$form_config.',', ',fields7,') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Sharp Edges</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[6]=='Sharp Edges') { echo " checked"; } ?>  name="fields_6" value="Sharp Edges"><input name="fields_value_1" type="text" value="<?php echo $fields_value[1]; ?>" class="form-control" />
                    </div>
                </div>
				<?php } ?>

				<?php if (strpos(','.$form_config.',', ',fields8,') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Unguarded Edges</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[7]=='Unguarded Edges') { echo " checked"; } ?>  name="fields_7" value="Unguarded Edges"><input name="fields_value_2" type="text" value="<?php echo $fields_value[2]; ?>" class="form-control" />
                    </div>
                </div>
				<?php } ?>

				<?php if (strpos(','.$form_config.',', ',fields9,') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Missing Guard Rails</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[8]=='Missing Guard Rails') { echo " checked"; } ?>  name="fields_8" value="Missing Guard Rails"><input name="fields_value_3" type="text" value="<?php echo $fields_value[3]; ?>" class="form-control" />
                    </div>
                </div>
				<?php } ?>

				<?php if (strpos(','.$form_config.',', ',fields10,') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Obstruction Below</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[9]=='Obstruction Below') { echo " checked"; } ?>  name="fields_9" value="Obstruction Below"><input name="fields_value_4" type="text" value="<?php echo $fields_value[4]; ?>" class="form-control" />
                    </div>
                </div>
				<?php } ?>

				<?php if (strpos(','.$form_config.',', ',fields11,') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Slippery Surfaces</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[10]=='Slippery Surfaces') { echo " checked"; } ?>  name="fields_10" value="Slippery Surfaces"><input name="fields_value_5" type="text" value="<?php echo $fields_value[5]; ?>" class="form-control" />
                    </div>
                </div>
				<?php } ?>

				<?php if (strpos(','.$form_config.',', ',fields12,') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Ice</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[11]=='Ice') { echo " checked"; } ?>  name="fields_11" value="Ice"><input name="fields_value_6" type="text" value="<?php echo $fields_value[6]; ?>" class="form-control" />
                    </div>
                </div>
				<?php } ?>

				<?php if (strpos(','.$form_config.',', ',fields13,') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Open Holes in Work Surface</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[12]=='Open Holes in Work Surface') { echo " checked"; } ?>  name="fields_12" value="Open Holes in Work Surface"><input name="fields_value_7" type="text" value="<?php echo $fields_value[7]; ?>" class="form-control" />
                    </div>
                </div>
				<?php } ?>

				<?php if (strpos(','.$form_config.',', ',fields14,') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Wind Hazards</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[13]=='Wind Hazards') { echo " checked"; } ?>  name="fields_13" value="Wind Hazards"><input name="fields_value_8" type="text" value="<?php echo $fields_value[8]; ?>" class="form-control" />
                    </div>
                </div>
				<?php } ?>

				<?php if (strpos(','.$form_config.',', ',fields15,') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Trip Hazards</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[14]=='Trip Hazards') { echo " checked"; } ?>  name="fields_14" value="Trip Hazards"><input name="fields_value_9" type="text" value="<?php echo $fields_value[9]; ?>" class="form-control" />
                    </div>
                </div>
				<?php } ?>

				<?php if (strpos(','.$form_config.',', ',fields16,') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Loose Equipment or Tools</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[15]=='Loose Equipment or Tools') { echo " checked"; } ?>  name="fields_15" value="Loose Equipment or Tools"><input name="fields_value_10" type="text" value="<?php echo $fields_value[10]; ?>" class="form-control" />
                    </div>
                </div>
				<?php } ?>

				<?php if (strpos(','.$form_config.',', ',fields17,') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Moving Equipment</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[16]=='Moving Equipment') { echo " checked"; } ?>  name="fields_16" value="Moving Equipment"><input name="fields_value_11" type="text" value="<?php echo $fields_value[11]; ?>" class="form-control" />
                    </div>
                </div>
				<?php } ?>

				<?php if (strpos(','.$form_config.',', ',fields18,') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Other</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[17]=='Other') { echo " checked"; } ?>  name="fields_17" value="Other"><input type="text" name="fields_18" value="<?php echo $fields[18]; ?>" class="form-control" />&nbsp;&nbsp;<input name="fields_value_12" type="text" value="<?php echo $fields_value[12]; ?>" class="form-control" />
                    </div>
                </div>
				<?php } ?>

			</div>
        </div>
    </div>


	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info3" >
                    Control Measures<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info3" class="panel-collapse collapse">
            <div class="panel-body">

				<?php if (strpos(','.$form_config.',', ',fields19,') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Fall Arrest System</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[19]=='Fall Arrest System') { echo " checked"; } ?>  name="fields_19" value="Fall Arrest System"><input name="fields_value_13" type="text" value="<?php echo $fields_value[13]; ?>" class="form-control" />
                    </div>
                </div>
				<?php } ?>

				<?php if (strpos(','.$form_config.',', ',fields20,') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Travel Restraint System</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[20]=='Travel Restraint System') { echo " checked"; } ?>  name="fields_20" value="Travel Restraint System"><input name="fields_value_14" type="text" value="<?php echo $fields_value[14]; ?>" class="form-control" />
                    </div>
                </div>
				<?php } ?>

				<?php if (strpos(','.$form_config.',', ',fields21,') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Temporary Guard Rail</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[21]=='Temporary Guard Rail') { echo " checked"; } ?>  name="fields_21" value="Temporary Guard Rail"><input name="fields_value_15" type="text" value="<?php echo $fields_value[15]; ?>" class="form-control" />
                    </div>
                </div>
				<?php } ?>

				<?php if (strpos(','.$form_config.',', ',fields22,') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Temporary Open Covers</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[22]=='Temporary Open Covers') { echo " checked"; } ?>  name="fields_22" value="Temporary Open Covers"><input name="fields_value_16" type="text" value="<?php echo $fields_value[16]; ?>" class="form-control" />
                    </div>
                </div>
				<?php } ?>

				<?php if (strpos(','.$form_config.',', ',fields23,') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Taglines for lowering equipment</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[23]=='Taglines for lowering equipment') { echo " checked"; } ?>  name="fields_23" value="Taglines for lowering equipment"><input name="fields_value_17" type="text" value="<?php echo $fields_value[17]; ?>" class="form-control" />
                    </div>
                </div>
				<?php } ?>

				<?php if (strpos(','.$form_config.',', ',fields24,') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Man Basket</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[24]=='Man Basket') { echo " checked"; } ?>  name="fields_24" value="Man Basket"><input name="fields_value_18" type="text" value="<?php echo $fields_value[18]; ?>" class="form-control" />
                    </div>
                </div>
				<?php } ?>

				<?php if (strpos(','.$form_config.',', ',fields25,') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Scaffolding</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[25]=='Scaffolding') { echo " checked"; } ?>  name="fields_25" value="Scaffolding"><input name="fields_value_19" type="text" value="<?php echo $fields_value[19]; ?>" class="form-control" />
                    </div>
                </div>
				<?php } ?>

				<?php if (strpos(','.$form_config.',', ',fields26,') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Man-lift</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[26]=='Man-lift') { echo " checked"; } ?>  name="fields_26" value="Man-lift"><input name="fields_value_20" type="text" value="<?php echo $fields_value[20]; ?>" class="form-control" />
                    </div>
                </div>
				<?php } ?>

				<?php if (strpos(','.$form_config.',', ',fields27,') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Control Zone</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[27]=='Control Zone') { echo " checked"; } ?>  name="fields_27" value="Control Zone"><input name="fields_value_21" type="text" value="<?php echo $fields_value[21]; ?>" class="form-control" />
                    </div>
                </div>
				<?php } ?>

				<?php if (strpos(','.$form_config.',', ',fields28,') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Tool Lanyards</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[28]=='Tool Lanyards') { echo " checked"; } ?>  name="fields_28" value="Tool Lanyards"><input name="fields_value_22" type="text" value="<?php echo $fields_value[22]; ?>" class="form-control" />
                    </div>
                </div>
				<?php } ?>

				<?php if (strpos(','.$form_config.',', ',fields29,') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Debris Netting</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[29]=='Debris Netting') { echo " checked"; } ?>  name="fields_29" value="Debris Netting"><input name="fields_value_23" type="text" value="<?php echo $fields_value[23]; ?>" class="form-control" />
                    </div>
                </div>
				<?php } ?>

				<?php if (strpos(','.$form_config.',', ',fields30,') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Lock Out / Tag Out</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[30]=='Lock Out / Tag Out') { echo " checked"; } ?>  name="fields_30" value="Lock Out / Tag Out"><input name="fields_value_24" type="text" value="<?php echo $fields_value[24]; ?>" class="form-control" />
                    </div>
                </div>
				<?php } ?>

				<?php if (strpos(','.$form_config.',', ',fields31,') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Other</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[31]=='Other') { echo " checked"; } ?>  name="fields_31" value="Other"><input type="text" name="fields_32" value="<?php echo $fields[32]; ?>" class="form-control" />&nbsp;&nbsp;<input name="fields_value_25" type="text" value="<?php echo $fields_value[25]; ?>" class="form-control" />
                    </div>
                </div>
				<?php } ?>

			</div>
        </div>
    </div>

    <?php if (strpos($form_config, ','."fields32".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info4" >
                    Equipment Inspection<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info4" class="panel-collapse collapse">
            <div class="panel-body">
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Has all personal fall protection equipment been inspected (pre-use) as per the manufacturer's specifications?</label>
                    <div class="col-sm-8">
                        <input type="radio" <?php if ($fields[33] == 'Yes') { echo " checked"; } ?>  name="fields_33" value="Yes">Yes&nbsp;&nbsp;
                        <input type="radio" <?php if ($fields[33] == 'No') { echo " checked"; } ?>  name="fields_33" value="No">No&nbsp;&nbsp;
                        <input type="text" name="fields_34" value="<?php echo $fields[34]; ?>"   class="form-control" />
                    </div>
					</div>
			</div>
        </div>
    </div>
    <?php } ?>

	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info5" >
                    Rescue Plan<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info5" class="panel-collapse collapse">
            <div class="panel-body">

			<?php if (strpos(','.$form_config.',', ',fields33,') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Man-lift</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[35]=='Man-lift') { echo " checked"; } ?>  name="fields_35" value="Man-lift"><input name="fields_value_26" type="text" value="<?php echo $fields_value[26]; ?>" class="form-control" />
                    </div>
                </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields34,') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Ladders</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[36]=='Ladders') { echo " checked"; } ?>  name="fields_36" value="Ladders"><input name="fields_value_27" type="text" value="<?php echo $fields_value[27]; ?>" class="form-control" />
                    </div>
                </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields35,') !== FALSE) { ?>
			<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">On-site Rescue (Emergency Response Crew)</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[37]=='On-site Rescue (Emergency Response Crew)') { echo " checked"; } ?>  name="fields_37" value="On-site Rescue (Emergency Response Crew)"><input name="fields_value_28" type="text" value="<?php echo $fields_value[28]; ?>" class="form-control" />
                    </div>
                </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields36,') !== FALSE) { ?>
			<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Local Emergency Response Available (Within 15 min.)</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[38]=='Local Emergency Response Available (Within 15 min.)') { echo " checked"; } ?>  name="fields_38" value="Local Emergency Response Available (Within 15 min.)"><input name="fields_value_29" type="text" value="<?php echo $fields_value[29]; ?>" class="form-control" />
                    </div>
                </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields37,') !== FALSE) { ?>
			<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">First Aid Attendants</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[39]=='First Aid Attendants') { echo " checked"; } ?>  name="fields_39" value="First Aid Attendants"><input name="fields_value_30" type="text" value="<?php echo $fields_value[30]; ?>" class="form-control" />
                    </div>
                </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields38,') !== FALSE) { ?>
			<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Method of Transportation Available</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[40]=='Method of Transportation Available') { echo " checked"; } ?>  name="fields_40" value="Method of Transportation Available"><input name="fields_value_31" type="text" value="<?php echo $fields_value[31]; ?>" class="form-control" />
                    </div>
                </div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields39".',') !== FALSE) { ?>
				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Emergency Phone Contact #1</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[41]=='Emergency Phone Contact #1') { echo " checked"; } ?>  name="fields_41" value="Emergency Phone Contact #1"><input type="text" name="fields_42" value="<?php echo $fields[42]; ?>" class="form-control" />
					</div>
				</div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields40".',') !== FALSE) { ?>
            <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Emergency Phone Contact #2</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[43]=='Emergency Phone Contact #2') { echo " checked"; } ?>  name="fields_43" value="Emergency Phone Contact #2"><input type="text" name="fields_44" value="<?php echo $fields[44]; ?>" class="form-control" />
            </div>
            </div>
            <?php } ?>

			</div>
        </div>
    </div>

	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info6" >
                    General<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info6" class="panel-collapse collapse">
            <div class="panel-body">

				<?php if (strpos($form_config, ','."fields41".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Has all emergency and rescue equipment been inspected (prior to work commencement) as per the manufacturer's specifications?</label>
                    <div class="col-sm-8">
                        <input type="radio" <?php if ($fields[45] == 'Yes') { echo " checked"; } ?>  name="fields_45" value="Yes">Yes&nbsp;&nbsp;
                        <input type="radio" <?php if ($fields[45] == 'No') { echo " checked"; } ?>  name="fields_45" value="No">No&nbsp;&nbsp;
                        <input type="text" name="fields_46" value="<?php echo $fields[46]; ?>"   class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields42".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Have are all workers being trained in the safe use of fall protection equipment?</label>
                    <div class="col-sm-8">
                        <input type="radio" <?php if ($fields[47] == 'Yes') { echo " checked"; } ?>  name="fields_47" value="Yes">Yes&nbsp;&nbsp;
                        <input type="radio" <?php if ($fields[47] == 'No') { echo " checked"; } ?>  name="fields_47" value="No">No&nbsp;&nbsp;
                        <input type="text" name="fields_49" value="<?php echo $fields[49]; ?>"   class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields43".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Have all affected workers been made aware of this plan? </label>
                    <div class="col-sm-8">
                        <input type="radio" <?php if ($fields[50] == 'Yes') { echo " checked"; } ?>  name="fields_50" value="Yes">Yes&nbsp;&nbsp;
                        <input type="radio" <?php if ($fields[50] == 'No') { echo " checked"; } ?>  name="fields_50" value="No">No&nbsp;&nbsp;
                        <input type="text" name="fields_52" value="<?php echo $fields[52]; ?>"   class="form-control" />
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
                echo '<img src="fall_protection_plan/download/safety_'.$assign_staff_id.'.png">';
            } ?>

        </div>
    </div>
</div>
<?php $sa_inc++;
    }
} ?>

</div>