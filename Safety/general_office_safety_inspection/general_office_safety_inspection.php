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

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];
	echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_general_office_safety_inspection WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
	$desc = $get_field_level['desc'];
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
                    Identification<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info1" class="panel-collapse collapse">
            <div class="panel-body">

				<?php if (strpos($form_config, ','."fields0".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Performed By</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_0" value="<?php echo $fields[0]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields1".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Date of Assessment</label>
                    <div class="col-sm-8">
                    <input type="text" name="today_date" value="<?php echo $today_date; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Company Name</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_1" value="<?php echo $fields[1]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Address</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_2" value="<?php echo $fields[2]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Location of Assessment</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_3" value="<?php echo $fields[3]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

			</div>
        </div>
    </div>

    <?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_2" >
                    Assessment Team - Names, Positions<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_2" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Assessment Team - Names, Positions</label>
                <div class="col-sm-8">
                <textarea name="desc" rows="3" cols="50" class="form-control"><?php echo $desc; ?></textarea>
                </div>
                </div>

			</div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_safety" >
                    Safety Program<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_safety" class="panel-collapse collapse">
            <div class="panel-body">

				<h4>Company Safety Policy</h4>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Current</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[4] == 'Okay') { echo " checked"; } ?>  name="fields_4" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[4] == 'Action Reqired') { echo " checked"; } ?>  name="fields_4" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_5" value="<?php echo $fields[5]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Dated</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[5] == 'Okay') { echo " checked"; } ?>  name="fields_5" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[5] == 'Action Reqired') { echo " checked"; } ?>  name="fields_5" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_6" value="<?php echo $fields[6]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Signed</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[7] == 'Okay') { echo " checked"; } ?>  name="fields_7" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[7] == 'Action Reqired') { echo " checked"; } ?>  name="fields_7" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_8" value="<?php echo $fields[8]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Posted</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[9] == 'Okay') { echo " checked"; } ?>  name="fields_9" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[9] == 'Action Reqired') { echo " checked"; } ?>  name="fields_9" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_10" value="<?php echo $fields[10]; ?>"   class="form-control" />
                    </div>
					</div>

					<h4>Company Safety Program Manual</h4>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Current</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[11] == 'Okay') { echo " checked"; } ?>  name="fields_11" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[11] == 'Action Reqired') { echo " checked"; } ?>  name="fields_11" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_12" value="<?php echo $fields[12]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Available</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[13] == 'Okay') { echo " checked"; } ?>  name="fields_13" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[13] == 'Action Reqired') { echo " checked"; } ?>  name="fields_13" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_14" value="<?php echo $fields[14]; ?>"   class="form-control" />
                    </div>
					</div>

					<h4>Safe Work Practices</h4>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">In Place</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[15] == 'Okay') { echo " checked"; } ?>  name="fields_15" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[15] == 'Action Reqired') { echo " checked"; } ?>  name="fields_15" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_16" value="<?php echo $fields[16]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">At Field Locations</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[17] == 'Okay') { echo " checked"; } ?>  name="fields_17" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[17] == 'Action Reqired') { echo " checked"; } ?>  name="fields_17" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_18" value="<?php echo $fields[18]; ?>"   class="form-control" />
                    </div>
					</div>

					<h4>Copies Of OH&S Act And Regulations Available</h4>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">At Office</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[19] == 'Okay') { echo " checked"; } ?>  name="fields_19" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[19] == 'Action Reqired') { echo " checked"; } ?>  name="fields_19" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_20" value="<?php echo $fields[20]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">At Field Locations</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[21] == 'Okay') { echo " checked"; } ?>  name="fields_21" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[21] == 'Action Reqired') { echo " checked"; } ?>  name="fields_21" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_22" value="<?php echo $fields[22]; ?>"   class="form-control" />
                    </div>
					</div>

					<h4>Inspections</h4>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Policy in Place</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[23] == 'Okay') { echo " checked"; } ?>  name="fields_23" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[23] == 'Action Reqired') { echo " checked"; } ?>  name="fields_23" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_24" value="<?php echo $fields[24]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Being Done Regularly</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[25] == 'Okay') { echo " checked"; } ?>  name="fields_25" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[25] == 'Action Reqired') { echo " checked"; } ?>  name="fields_25" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_26" value="<?php echo $fields[26]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Records Available</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[27] == 'Okay') { echo " checked"; } ?>  name="fields_27" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[27] == 'Action Reqired') { echo " checked"; } ?>  name="fields_27" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_28" value="<?php echo $fields[28]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Corrective Actions Complete From Last</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[29] == 'Okay') { echo " checked"; } ?>  name="fields_29" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[29] == 'Action Reqired') { echo " checked"; } ?>  name="fields_29" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_30" value="<?php echo $fields[30]; ?>"   class="form-control" />
                    </div>
					</div>

 			</div>
        </div>
    </div>
	<?php } ?>

    <?php if (strpos($form_config, ','."fields7".',') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_walk" >
                    Walkways/Flooring<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_walk" class="panel-collapse collapse">
            <div class="panel-body">

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Free From Debris</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[31] == 'Okay') { echo " checked"; } ?>  name="fields_31" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[31] == 'Action Reqired') { echo " checked"; } ?>  name="fields_31" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_32" value="<?php echo $fields[32]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Slips/Trips</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[33] == 'Okay') { echo " checked"; } ?>  name="fields_33" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[33] == 'Action Reqired') { echo " checked"; } ?>  name="fields_33" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_34" value="<?php echo $fields[34]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Walkways clear</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[35] == 'Okay') { echo " checked"; } ?>  name="fields_35" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[35] == 'Action Reqired') { echo " checked"; } ?>  name="fields_35" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_36" value="<?php echo $fields[36]; ?>"   class="form-control" />
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_emer" >
                    Emergency Response<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_emer" class="panel-collapse collapse">
            <div class="panel-body">

				<h4>Site Specific</h4>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Response Plan Posted</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[37] == 'Okay') { echo " checked"; } ?>  name="fields_37" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[37] == 'Action Reqired') { echo " checked"; } ?>  name="fields_37" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_38" value="<?php echo $fields[38]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Muster Points Identified</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[39] == 'Okay') { echo " checked"; } ?>  name="fields_39" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[39] == 'Action Reqired') { echo " checked"; } ?>  name="fields_39" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_40" value="<?php echo $fields[40]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Emergency Phone List Posted</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[41] == 'Okay') { echo " checked"; } ?>  name="fields_41" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[41] == 'Action Reqired') { echo " checked"; } ?>  name="fields_41" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_42" value="<?php echo $fields[42]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Site Map Posted/Egress Routes Identified</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[43] == 'Okay') { echo " checked"; } ?>  name="fields_43" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[43] == 'Action Reqired') { echo " checked"; } ?>  name="fields_43" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_44" value="<?php echo $fields[44]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Fire Response Plan</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[45] == 'Okay') { echo " checked"; } ?>  name="fields_45" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[45] == 'Action Reqired') { echo " checked"; } ?>  name="fields_45" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_46" value="<?php echo $fields[46]; ?>"   class="form-control" />
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_first" >
                    First Aid<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_first" class="panel-collapse collapse">
            <div class="panel-body">

				<h4></h4>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Facilities</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[47] == 'Okay') { echo " checked"; } ?>  name="fields_47" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[47] == 'Action Reqired') { echo " checked"; } ?>  name="fields_47" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_48" value="<?php echo $fields[48]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Supplies</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[49] == 'Okay') { echo " checked"; } ?>  name="fields_49" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[49] == 'Action Reqired') { echo " checked"; } ?>  name="fields_49" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_50" value="<?php echo $fields[50]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">List Of Personnel Trained</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[51] == 'Okay') { echo " checked"; } ?>  name="fields_51" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[51] == 'Action Reqired') { echo " checked"; } ?>  name="fields_51" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_52" value="<?php echo $fields[52]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Eye Wash Station</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[53] == 'Okay') { echo " checked"; } ?>  name="fields_53" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[53] == 'Action Reqired') { echo " checked"; } ?>  name="fields_53" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_54" value="<?php echo $fields[54]; ?>"   class="form-control" />
                    </div>
					</div>

					<h4>Emergency Services Availability</h4>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Are emergency numbers posted?</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[55] == 'Okay') { echo " checked"; } ?>  name="fields_55" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[55] == 'Action Reqired') { echo " checked"; } ?>  name="fields_55" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_56" value="<?php echo $fields[56]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Does every employee know how to get help?</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[57] == 'Okay') { echo " checked"; } ?>  name="fields_57" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[57] == 'Action Reqired') { echo " checked"; } ?>  name="fields_57" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_58" value="<?php echo $fields[58]; ?>"   class="form-control" />
                    </div>
					</div>

 			</div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos($form_config, ','."fields10".',') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_fire" >
                    Fire Prevention<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_fire" class="panel-collapse collapse">
            <div class="panel-body">

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Smoking / No Smoking Rules</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[59] == 'Okay') { echo " checked"; } ?>  name="fields_59" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[59] == 'Action Reqired') { echo " checked"; } ?>  name="fields_59" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_60" value="<?php echo $fields[60]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Fire Extinguishers</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[61] == 'Okay') { echo " checked"; } ?>  name="fields_61" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[61] == 'Action Reqired') { echo " checked"; } ?>  name="fields_61" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_62" value="<?php echo $fields[62]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">On Vehicles</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[63] == 'Okay') { echo " checked"; } ?>  name="fields_63" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[63] == 'Action Reqired') { echo " checked"; } ?>  name="fields_63" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_64" value="<?php echo $fields[64]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">On Equipment</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[65] == 'Okay') { echo " checked"; } ?>  name="fields_65" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[65] == 'Action Reqired') { echo " checked"; } ?>  name="fields_65" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_66" value="<?php echo $fields[66]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">In Buildings</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[67] == 'Okay') { echo " checked"; } ?>  name="fields_67" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[67] == 'Action Reqired') { echo " checked"; } ?>  name="fields_67" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_68" value="<?php echo $fields[68]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">All Personnel Trained in Their Use</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[69] == 'Okay') { echo " checked"; } ?>  name="fields_69" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[69] == 'Action Reqired') { echo " checked"; } ?>  name="fields_69" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_70" value="<?php echo $fields[70]; ?>"   class="form-control" />
                    </div>
					</div>

				<h4>Fire Department Assistance</h4>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Does every employee know how to get help?</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[71] == 'Okay') { echo " checked"; } ?>  name="fields_71" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[71] == 'Action Reqired') { echo " checked"; } ?>  name="fields_71" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_72" value="<?php echo $fields[72]; ?>"   class="form-control" />
                    </div>
					</div>

 			</div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos($form_config, ','."fields11".',') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_toil" >
                    Toilet/Wash Facilities<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_toil" class="panel-collapse collapse">
            <div class="panel-body">

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Required Facilities Based On # Of Workes</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[73] == 'Okay') { echo " checked"; } ?>  name="fields_73" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[73] == 'Action Reqired') { echo " checked"; } ?>  name="fields_73" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_74" value="<?php echo $fields[74]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Maintained</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[75] == 'Okay') { echo " checked"; } ?>  name="fields_75" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[75] == 'Action Reqired') { echo " checked"; } ?>  name="fields_75" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_76" value="<?php echo $fields[76]; ?>"   class="form-control" />
                    </div>
					</div>

 			</div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos($form_config, ','."fields12".',') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_per" >
                    Personal Protective Equip<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_per" class="panel-collapse collapse">
            <div class="panel-body">

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">P.P.E. Policy / Rules In Place</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[77] == 'Okay') { echo " checked"; } ?>  name="fields_77" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[77] == 'Action Reqired') { echo " checked"; } ?>  name="fields_77" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_78" value="<?php echo $fields[78]; ?>"   class="form-control" />
                    </div>
					</div>

				<h4>Basic P.P.E. In Use</h4>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Hard Hats</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[79] == 'Okay') { echo " checked"; } ?>  name="fields_79" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[79] == 'Action Reqired') { echo " checked"; } ?>  name="fields_79" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_80" value="<?php echo $fields[80]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Safety Glasses</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[81] == 'Okay') { echo " checked"; } ?>  name="fields_81" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[81] == 'Action Reqired') { echo " checked"; } ?>  name="fields_81" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_82" value="<?php echo $fields[82]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Safety Boots</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[83] == 'Okay') { echo " checked"; } ?>  name="fields_83" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[83] == 'Action Reqired') { echo " checked"; } ?>  name="fields_83" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_84" value="<?php echo $fields[84]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Hearing Protection</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[85] == 'Okay') { echo " checked"; } ?>  name="fields_85" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[85] == 'Action Reqired') { echo " checked"; } ?>  name="fields_85" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_86" value="<?php echo $fields[86]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Coveralls/Long Sleeve Shirts</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[87] == 'Okay') { echo " checked"; } ?>  name="fields_87" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[87] == 'Action Reqired') { echo " checked"; } ?>  name="fields_87" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_88" value="<?php echo $fields[88]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Gloves</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[89] == 'Okay') { echo " checked"; } ?>  name="fields_89" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[89] == 'Action Reqired') { echo " checked"; } ?>  name="fields_89" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_90" value="<?php echo $fields[90]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Other</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[91] == 'Okay') { echo " checked"; } ?>  name="fields_91" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[91] == 'Action Reqired') { echo " checked"; } ?>  name="fields_91" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_92" value="<?php echo $fields[92]; ?>"   class="form-control" />
                    </div>
					</div>

					<h4>Specialized P.P.E. Available</h4>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Respirators</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[93] == 'Okay') { echo " checked"; } ?>  name="fields_93" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[93] == 'Action Reqired') { echo " checked"; } ?>  name="fields_93" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_94" value="<?php echo $fields[94]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Fall Arresting Equipment</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[95] == 'Okay') { echo " checked"; } ?>  name="fields_95" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[95] == 'Action Reqired') { echo " checked"; } ?>  name="fields_95" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_96" value="<?php echo $fields[96]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Face Shields / Goggles</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[97] == 'Okay') { echo " checked"; } ?>  name="fields_97" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[97] == 'Action Reqired') { echo " checked"; } ?>  name="fields_97" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_98" value="<?php echo $fields[98]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Other</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[99] == 'Okay') { echo " checked"; } ?>  name="fields_99" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[99] == 'Action Reqired') { echo " checked"; } ?>  name="fields_99" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_100" value="<?php echo $fields[100]; ?>"   class="form-control" />
                    </div>
					</div>

					<h4>Atmospheric Monitors</h4>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Bump Tests Performed</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[101] == 'Okay') { echo " checked"; } ?>  name="fields_101" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[101] == 'Action Reqired') { echo " checked"; } ?>  name="fields_101" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_102" value="<?php echo $fields[102]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Calibrations Up To Date</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[103] == 'Okay') { echo " checked"; } ?>  name="fields_103" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[103] == 'Action Reqired') { echo " checked"; } ?>  name="fields_103" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_104" value="<?php echo $fields[104]; ?>"   class="form-control" />
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_build" >
                    Buildings<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_build" class="panel-collapse collapse">
            <div class="panel-body">

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Lighting</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[105] == 'Okay') { echo " checked"; } ?>  name="fields_105" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[105] == 'Action Reqired') { echo " checked"; } ?>  name="fields_105" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_106" value="<?php echo $fields[106]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Emergency Lighting</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[107] == 'Okay') { echo " checked"; } ?>  name="fields_107" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[107] == 'Action Reqired') { echo " checked"; } ?>  name="fields_107" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_108" value="<?php echo $fields[108]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Ventilation</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[109] == 'Okay') { echo " checked"; } ?>  name="fields_109" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[109] == 'Action Reqired') { echo " checked"; } ?>  name="fields_109" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_110" value="<?php echo $fields[110]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Heating</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[111] == 'Okay') { echo " checked"; } ?>  name="fields_111" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[111] == 'Action Reqired') { echo " checked"; } ?>  name="fields_111" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_112" value="<?php echo $fields[112]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Access / Egress</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[113] == 'Okay') { echo " checked"; } ?>  name="fields_113" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[113] == 'Action Reqired') { echo " checked"; } ?>  name="fields_113" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_114" value="<?php echo $fields[114]; ?>"   class="form-control" />
                    </div>
					</div>

				<h4>Trailers/Office</h4>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Stairs</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[115] == 'Okay') { echo " checked"; } ?>  name="fields_115" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[115] == 'Action Reqired') { echo " checked"; } ?>  name="fields_115" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_116" value="<?php echo $fields[116]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Fire Extinguishers</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[117] == 'Okay') { echo " checked"; } ?>  name="fields_117" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[117] == 'Action Reqired') { echo " checked"; } ?>  name="fields_117" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_118" value="<?php echo $fields[118]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Blocking</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[119] == 'Okay') { echo " checked"; } ?>  name="fields_119" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[119] == 'Action Reqired') { echo " checked"; } ?>  name="fields_119" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_120" value="<?php echo $fields[120]; ?>"   class="form-control" />
                    </div>
					</div>

					<h4>Facilities</h4>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Lunchrooms</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[121] == 'Okay') { echo " checked"; } ?>  name="fields_121" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[121] == 'Action Reqired') { echo " checked"; } ?>  name="fields_121" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_122" value="<?php echo $fields[122]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Washrooms</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[123] == 'Okay') { echo " checked"; } ?>  name="fields_123" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[123] == 'Action Reqired') { echo " checked"; } ?>  name="fields_123" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_124" value="<?php echo $fields[124]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Changerooms</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[125] == 'Okay') { echo " checked"; } ?>  name="fields_125" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[125] == 'Action Reqired') { echo " checked"; } ?>  name="fields_125" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_126" value="<?php echo $fields[126]; ?>"   class="form-control" />
                    </div>
					</div>

 			</div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos($form_config, ','."fields14".',') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_chem" >
                    Chemicals<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_chem" class="panel-collapse collapse">
            <div class="panel-body">

				<h4>WHMIS</h4>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">MSDS</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[127] == 'Okay') { echo " checked"; } ?>  name="fields_127" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[127] == 'Action Reqired') { echo " checked"; } ?>  name="fields_127" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_128" value="<?php echo $fields[128]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Supplier Labels Visible</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[129] == 'Okay') { echo " checked"; } ?>  name="fields_129" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[129] == 'Action Reqired') { echo " checked"; } ?>  name="fields_129" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_130" value="<?php echo $fields[130]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Workplace Labels</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[131] == 'Okay') { echo " checked"; } ?>  name="fields_131" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[131] == 'Action Reqired') { echo " checked"; } ?>  name="fields_131" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_132" value="<?php echo $fields[132]; ?>"   class="form-control" />
                    </div>
					</div>

					<h4>Transportation Of Dangerous Goods</h4>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Employees Trained Where Required</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[133] == 'Okay') { echo " checked"; } ?>  name="fields_133" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[133] == 'Action Reqired') { echo " checked"; } ?>  name="fields_133" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_134" value="<?php echo $fields[134]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Emergency Response Procedure in Place</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[135] == 'Okay') { echo " checked"; } ?>  name="fields_135" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[135] == 'Action Reqired') { echo " checked"; } ?>  name="fields_135" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_136" value="<?php echo $fields[136]; ?>"   class="form-control" />
                    </div>
					</div>


 			</div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos($form_config, ','."fields15".',') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_equipment" >
                    Equipment<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_equipment" class="panel-collapse collapse">
            <div class="panel-body">

				<h4>Mobile Equipment</h4>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Maintenance Procedures</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[137] == 'Okay') { echo " checked"; } ?>  name="fields_137" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[137] == 'Action Reqired') { echo " checked"; } ?>  name="fields_137" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_138" value="<?php echo $fields[138]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Log Book Current </label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[139] == 'Okay') { echo " checked"; } ?>  name="fields_139" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[139] == 'Action Reqired') { echo " checked"; } ?>  name="fields_139" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_140" value="<?php echo $fields[140]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Pre Use Inspections Completed</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[141] == 'Okay') { echo " checked"; } ?>  name="fields_141" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[141] == 'Action Reqired') { echo " checked"; } ?>  name="fields_141" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_142" value="<?php echo $fields[142]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Operator Compitent</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[143] == 'Okay') { echo " checked"; } ?>  name="fields_143" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[143] == 'Action Reqired') { echo " checked"; } ?>  name="fields_143" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_144" value="<?php echo $fields[144]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Fire Extinguisher</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[145] == 'Okay') { echo " checked"; } ?>  name="fields_145" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[145] == 'Action Reqired') { echo " checked"; } ?>  name="fields_145" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_146" value="<?php echo $fields[146]; ?>"   class="form-control" />
                    </div>
					</div>

					<h4>Vehicles</h4>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Proper Maintenance</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[147] == 'Okay') { echo " checked"; } ?>  name="fields_147" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[147] == 'Action Reqired') { echo " checked"; } ?>  name="fields_147" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_148" value="<?php echo $fields[148]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Insurance Papers/Registration</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[149] == 'Okay') { echo " checked"; } ?>  name="fields_149" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[149] == 'Action Reqired') { echo " checked"; } ?>  name="fields_149" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_150" value="<?php echo $fields[150]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Fire Extinguisher/First Aid Kit</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[151] == 'Okay') { echo " checked"; } ?>  name="fields_151" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[151] == 'Action Reqired') { echo " checked"; } ?>  name="fields_151" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_152" value="<?php echo $fields[152]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Loads Secured</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[153] == 'Okay') { echo " checked"; } ?>  name="fields_153" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[153] == 'Action Reqired') { echo " checked"; } ?>  name="fields_153" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_154" value="<?php echo $fields[154]; ?>"   class="form-control" />
                    </div>
					</div>

					<h4>Power Tools</h4>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">In Good Shape</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[155] == 'Okay') { echo " checked"; } ?>  name="fields_155" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[155] == 'Action Reqired') { echo " checked"; } ?>  name="fields_155" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_156" value="<?php echo $fields[156]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">All Guards in Place</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[157] == 'Okay') { echo " checked"; } ?>  name="fields_157" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[157] == 'Action Reqired') { echo " checked"; } ?>  name="fields_157" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_158" value="<?php echo $fields[158]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Maintenance Program Followed</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[159] == 'Okay') { echo " checked"; } ?>  name="fields_159" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[159] == 'Action Reqired') { echo " checked"; } ?>  name="fields_159" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_160" value="<?php echo $fields[160]; ?>"   class="form-control" />
                    </div>
					</div>

					<h4>Hand Tool</h4>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Regular Inspection & Maintenance</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[161] == 'Okay') { echo " checked"; } ?>  name="fields_161" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[161] == 'Action Reqired') { echo " checked"; } ?>  name="fields_161" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_162" value="<?php echo $fields[162]; ?>"   class="form-control" />
                    </div>
					</div>

					<h4>Scaffolding</h4>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Erected by Qualified Personnel</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[163] == 'Okay') { echo " checked"; } ?>  name="fields_163" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[163] == 'Action Reqired') { echo " checked"; } ?>  name="fields_163" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_164" value="<?php echo $fields[164]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Inspected Before Use</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[165] == 'Okay') { echo " checked"; } ?>  name="fields_165" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[165] == 'Action Reqired') { echo " checked"; } ?>  name="fields_165" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_166" value="<?php echo $fields[166]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Meet Regulations</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[167] == 'Okay') { echo " checked"; } ?>  name="fields_167" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[167] == 'Action Reqired') { echo " checked"; } ?>  name="fields_167" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_168" value="<?php echo $fields[168]; ?>"   class="form-control" />
                    </div>
					</div>

					<h4>Ladders</h4>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">In Good Repair</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[169] == 'Okay') { echo " checked"; } ?>  name="fields_169" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[169] == 'Action Reqired') { echo " checked"; } ?>  name="fields_169" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_170" value="<?php echo $fields[170]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Inspection Program in Place</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[171] == 'Okay') { echo " checked"; } ?>  name="fields_171" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[171] == 'Action Reqired') { echo " checked"; } ?>  name="fields_171" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_172" value="<?php echo $fields[172]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Tag Out of Service for Damage</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[173] == 'Okay') { echo " checked"; } ?>  name="fields_173" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[173] == 'Action Reqired') { echo " checked"; } ?>  name="fields_173" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_174" value="<?php echo $fields[174]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Correct Use</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[175] == 'Okay') { echo " checked"; } ?>  name="fields_175" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[175] == 'Action Reqired') { echo " checked"; } ?>  name="fields_175" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_176" value="<?php echo $fields[176]; ?>"   class="form-control" />
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_electricity" >
                    Electricity<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_electricity" class="panel-collapse collapse">
            <div class="panel-body">

				<h4>Overhead Power Lines</h4>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Marked Where Required</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[177] == 'Okay') { echo " checked"; } ?>  name="fields_177" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[177] == 'Action Reqired') { echo " checked"; } ?>  name="fields_177" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_178" value="<?php echo $fields[178]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Workers Trained in Clearances</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[179] == 'Okay') { echo " checked"; } ?>  name="fields_179" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[179] == 'Action Reqired') { echo " checked"; } ?>  name="fields_179" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_180" value="<?php echo $fields[180]; ?>"   class="form-control" />
                    </div>
					</div>

					<h4>Explosion Proof Fixtures</h4>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Are they required?</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[181] == 'Okay') { echo " checked"; } ?>  name="fields_181" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[181] == 'Action Reqired') { echo " checked"; } ?>  name="fields_181" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_182" value="<?php echo $fields[182]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Are they maintained?</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[183] == 'Okay') { echo " checked"; } ?>  name="fields_183" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[183] == 'Action Reqired') { echo " checked"; } ?>  name="fields_183" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_184" value="<?php echo $fields[184]; ?>"   class="form-control" />
                    </div>
					</div>

					<h4>Extension Cords</h4>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Three Conductor</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[185] == 'Okay') { echo " checked"; } ?>  name="fields_185" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[185] == 'Action Reqired') { echo " checked"; } ?>  name="fields_185" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_186" value="<?php echo $fields[186]; ?>"   class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Strung out of the Way</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[187] == 'Okay') { echo " checked"; } ?>  name="fields_187" value="Okay">Okay&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[187] == 'Action Reqired') { echo " checked"; } ?>  name="fields_187" value="Action Reqired">Action Reqired&nbsp;&nbsp;
                    <input type="text" name="fields_188" value="<?php echo $fields[188]; ?>"   class="form-control" />
                    </div>
					</div>

					<p>*To list specific hazards identified and complete corrective action plans, use form HS-11-05, and attach to this document.</p>

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