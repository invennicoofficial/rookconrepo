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

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];
	echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_safe_work_permit WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
    $fields = explode('**FFM**', $get_field_level['fields']);
    $desc = $get_field_level['desc'];
	$desc1 = $get_field_level['desc1'];
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
                    <input type="text" name="fields_0" value="<?php echo $today_date; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Permit Type</label>
                    <div class="col-sm-8">
                    <input type="radio" <?php if ($fields[1] == 'Hot Work Permit') { echo " checked"; } ?>  name="fields_1" value="Hot Work Permit">Hot Work Permit&nbsp;&nbsp;
                    <input type="radio" <?php if ($fields[1] == 'Cold Work Permit') { echo " checked"; } ?>  name="fields_1" value="Cold Work Permit">Cold Work Permit&nbsp;&nbsp;
					<input type="radio" <?php if ($fields[1] == 'Blanket Permit') { echo " checked"; } ?>  name="fields_1" value="Blanket Permit">Blanket Permit&nbsp;&nbsp;
					<input type="radio" <?php if ($fields[1] == 'Work Clearance') { echo " checked"; } ?>  name="fields_1" value="Work Clearance">Work Clearance&nbsp;&nbsp;
					<input type="radio" <?php if ($fields[1] == 'Is a Detailed Procedure Required?') { echo " checked"; } ?>  name="fields_1" value="Is a Detailed Procedure Required?">Is a Detailed Procedure Required?&nbsp;&nbsp;
                    <input type="text" name="fields_2" value="<?php echo $fields[2]; ?>"   class="form-control" />
                    </div>
					</div>

					<?php } ?>

					<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">PERMIT #</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_3" value="<?php echo $fields[3]; ?>" class="form-control" />
                    </div>
					</div>
					<?php } ?>

					<?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Location</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_4" value="<?php echo $fields[4]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Issued by</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_5" value="<?php echo $fields[5]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Phone</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_6" value="<?php echo $fields[6]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields7".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Issued to</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_7" value="<?php echo $fields[7]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields8".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Phone</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_8" value="<?php echo $fields[8]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields9".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Contractor</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_9" value="<?php echo $fields[9]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields10".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label"># of Workers</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_10" value="<?php echo $fields[10]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields11".',') !== FALSE) { ?>
					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label"># of Vehicles</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_11" value="<?php echo $fields[11]; ?>" class="form-control" />
                    </div>
					</div>
                <?php } ?>

			</div>
        </div>
    </div>
	<?php if (strpos($form_config, ','."fields12".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_scope" >
                    Scope of work/Comments<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_scope" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Scope of work/Comments</label>
                <div class="col-sm-8">
                <textarea name="desc" rows="3" cols="50" class="form-control"><?php echo $desc; ?></textarea>
                </div>
                </div>

			</div>
        </div>
    </div>
    <?php } ?>


	<?php if (strpos(','.$form_config.',', ',fields13,') !== FALSE) { ?>

	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_haz" >
                    Hazards<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_haz" class="panel-collapse collapse">
            <div class="panel-body">


				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Flammable Gas</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[12]=='Flammable Gas') { echo " checked"; } ?>  name="fields_12" value="Flammable Gas"><input name="fields_13" type="text" value="<?php echo $fields[13]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Flammable Liquid</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[14]=='Flammable Liquid') { echo " checked"; } ?>  name="fields_14" value="Flammable Liquid"><input name="fields_15" type="text" value="<?php echo $fields[15]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Pressure</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[16]=='Pressure') { echo " checked"; } ?>  name="fields_16" value="Pressure"><input name="fields_17" type="text" value="<?php echo $fields[17]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Hydrogen Sulfide</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[18]=='Hydrogen Sulfide') { echo " checked"; } ?>  name="fields_18" value="Hydrogen Sulfide"><input name="fields_19" type="text" value="<?php echo $fields[19]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">NORM/Radiation</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[20]=='NORM/Radiation') { echo " checked"; } ?>  name="fields_20" value="NORM/Radiation"><input name="fields_21" type="text" value="<?php echo $fields[21]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Chemicals</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[22]=='Chemicals') { echo " checked"; } ?>  name="fields_22" value="Chemicals"><input name="fields_23" type="text" value="<?php echo $fields[23]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Asbestos</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[24]=='Asbestos') { echo " checked"; } ?>  name="fields_24" value="Asbestos"><input name="fields_25" type="text" value="<?php echo $fields[25]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Organic Vapours</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[26]=='Organic Vapours') { echo " checked"; } ?>  name="fields_26" value="Organic Vapours"><input name="fields_27" type="text" value="<?php echo $fields[27]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Iron Sulphides</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[28]=='Iron Sulphides') { echo " checked"; } ?>  name="fields_28" value="Iron Sulphides"><input name="fields_29" type="text" value="<?php echo $fields[29]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Rotating Equipment</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[30]=='Rotating Equipment') { echo " checked"; } ?>  name="fields_30" value="Rotating Equipment"><input name="fields_31" type="text" value="<?php echo $fields[31]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Hot/Cold Piping</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[32]=='Hot/Cold Piping') { echo " checked"; } ?>  name="fields_32" value="Hot/Cold Piping"><input name="fields_33" type="text" value="<?php echo $fields[33]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Electrical Equipment</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[34]=='Electrical Equipment') { echo " checked"; } ?>  name="fields_34" value="Electrical Equipment"><input name="fields_35" type="text" value="<?php echo $fields[35]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Noise</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[36]=='Noise') { echo " checked"; } ?>  name="fields_36" value="Noise"><input name="fields_37" type="text" value="<?php echo $fields[37]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Driving</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[38]=='Driving') { echo " checked"; } ?>  name="fields_38" value="Driving"><input name="fields_39" type="text" value="<?php echo $fields[39]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Working at Heights</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[40]=='Working at Heights') { echo " checked"; } ?>  name="fields_40" value="Working at Heights"><input name="fields_41" type="text" value="<?php echo $fields[41]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Working Alone</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[42]=='Working Alone') { echo " checked"; } ?>  name="fields_42" value="Working Alone"><input name="fields_43" type="text" value="<?php echo $fields[43]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Overhead Hazards</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[44]=='Overhead Hazards') { echo " checked"; } ?>  name="fields_44" value="Overhead Hazards"><input name="fields_45" type="text" value="<?php echo $fields[45]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Ground Disturbance</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[46]=='Ground Disturbance') { echo " checked"; } ?>  name="fields_46" value="Ground Disturbance"><input name="fields_47" type="text" value="<?php echo $fields[47]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Confined Space</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[48]=='Confined Space') { echo " checked"; } ?>  name="fields_48" value="Confined Space"><input name="fields_49" type="text" value="<?php echo $fields[49]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Combustibles</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[50]=='Combustibles') { echo " checked"; } ?>  name="fields_50" value="Combustibles"><input name="fields_51" type="text" value="<?php echo $fields[51]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Lifting</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[52]=='Lifting') { echo " checked"; } ?>  name="fields_52" value="Lifting"><input name="fields_53" type="text" value="<?php echo $fields[53]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Underground Facilities</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[54]=='Underground Facilities') { echo " checked"; } ?>  name="fields_54" value="Underground Facilities"><input name="fields_55" type="text" value="<?php echo $fields[55]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Others</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[56]=='Others') { echo " checked"; } ?>  name="fields_56" value="Others"><input name="fields_57" type="text" value="<?php echo $fields[57]; ?>" class="form-control" />
                    </div>
                </div>

			</div>
        </div>
    </div>

<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields14,') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_communication" >
                    Control Measures - Communication<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_communication" class="panel-collapse collapse">
            <div class="panel-body">

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Pre Job Safety Meeting</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[58]=='Pre Job Safety Meeting') { echo " checked"; } ?>  name="fields_58" value="Pre Job Safety Meeting"><input name="fields_59" type="text" value="<?php echo $fields[59]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">MSDS present and reviewed</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[60]=='MSDS present and reviewed') { echo " checked"; } ?>  name="fields_60" value="MSDS present and reviewed"><input name="fields_61" type="text" value="<?php echo $fields[61]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Radio/Communication present</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[62]=='Radio/Communication present') { echo " checked"; } ?>  name="fields_62" value="Radio/Communication present"><input name="fields_63" type="text" value="<?php echo $fields[63]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Code pf Practice Reviewed</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[64]=='Code pf Practice Reviewed') { echo " checked"; } ?>  name="fields_64" value="Code pf Practice Reviewed"><input name="fields_65" type="text" value="<?php echo $fields[65]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">SWP Reviewed</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[66]=='SWP Reviewed') { echo " checked"; } ?>  name="fields_66" value="SWP Reviewed"><input name="fields_67" type="text" value="<?php echo $fields[67]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Additional Hazard Assessment</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[68]=='Additional Hazard Assessment') { echo " checked"; } ?>  name="fields_68" value="Additional Hazard Assessment"><input name="fields_69" type="text" value="<?php echo $fields[69]; ?>" class="form-control" />
                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">One Call Notification</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[70]=='One Call Notification') { echo " checked"; } ?>  name="fields_70" value="One Call Notification"><input name="fields_71" type="text" value="<?php echo $fields[71]; ?>" class="form-control" />
                    </div>
                </div>



			</div>
        </div>
    </div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields15,') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_erp" >
                    Control Measures - ERP<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_erp" class="panel-collapse collapse">
            <div class="panel-body">

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Fire Extinguishers</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[72]=='Fire Extinguishers') { echo " checked"; } ?>  name="fields_72" value="Fire Extinguishers"><input name="fields_73" type="text" value="<?php echo $fields[73]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">ERP Plan</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[74]=='ERP Plan') { echo " checked"; } ?>  name="fields_74" value="ERP Plan"><input name="fields_75" type="text" value="<?php echo $fields[75]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Safety Watch Required</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[76]=='Safety Watch Required') { echo " checked"; } ?>  name="fields_76" value="Safety Watch Required"><input name="fields_77" type="text" value="<?php echo $fields[77]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Muster Point Identified</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[78]=='Muster Point Identified') { echo " checked"; } ?>  name="fields_78" value="Muster Point Identified"><input name="fields_79" type="text" value="<?php echo $fields[79]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">First Aid Plan</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[80]=='First Aid Plan') { echo " checked"; } ?>  name="fields_80" value="First Aid Plan"><input name="fields_81" type="text" value="<?php echo $fields[81]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Spill Controls</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[82]=='Spill Controls') { echo " checked"; } ?>  name="fields_82" value="Spill Controls"><input name="fields_83" type="text" value="<?php echo $fields[83]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Line Strike procedures</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[84]=='Line Strike procedures') { echo " checked"; } ?>  name="fields_84" value="Line Strike procedures"><input name="fields_85" type="text" value="<?php echo $fields[85]; ?>" class="form-control" />

                    </div>
                </div>



			</div>
        </div>
    </div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields16,') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_swp" >
                    Control Measures - SWP and Training<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_swp" class="panel-collapse collapse">
            <div class="panel-body">

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Atmospheric Testing</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[86]=='Atmospheric Testing') { echo " checked"; } ?>  name="fields_86" value="Atmospheric Testing"><input name="fields_87" type="text" value="<?php echo $fields[87]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Safety Watch</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[88]=='Safety Watch') { echo " checked"; } ?>  name="fields_88" value="Safety Watch"><input name="fields_89" type="text" value="<?php echo $fields[89]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Continuous Atmospheric Testing</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[90]=='Continuous Atmospheric Testing') { echo " checked"; } ?>  name="fields_90" value="Continuous Atmospheric Testing"><input name="fields_91" type="text" value="<?php echo $fields[91]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Equipment De-energized</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[92]=='Equipment De-energized') { echo " checked"; } ?>  name="fields_92" value="Equipment De-energized"><input name="fields_93" type="text" value="<?php echo $fields[93]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Lock Out/Tag Out Performed</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[94]=='Lock Out/Tag Out Performed') { echo " checked"; } ?>  name="fields_94" value="Lock Out/Tag Out Performed"><input name="fields_95" type="text" value="<?php echo $fields[95]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Ground Disturbance Procedure/Training</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[96]=='Ground Disturbance Procedure/Training') { echo " checked"; } ?>  name="fields_96" value="Ground Disturbance Procedure/Training"><input name="fields_97" type="text" value="<?php echo $fields[97]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Confined Space Procedure/Training</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[98]=='Confined Space Procedure/Training') { echo " checked"; } ?>  name="fields_98" value="Confined Space Procedure/Training"><input name="fields_99" type="text" value="<?php echo $fields[99]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Venting/Purging</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[100]=='Venting/Purging') { echo " checked"; } ?>  name="fields_100" value="Venting/Purging"><input name="fields_101" type="text" value="<?php echo $fields[101]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Working Alone Procedure</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[102]=='Working Alone Procedure') { echo " checked"; } ?>  name="fields_102" value="Working Alone Procedure"><input name="fields_103" type="text" value="<?php echo $fields[103]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Blanks/Blinds Installed</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[104]=='Blanks/Blinds Installed') { echo " checked"; } ?>  name="fields_104" value="Blanks/Blinds Installed"><input name="fields_105" type="text" value="<?php echo $fields[105]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Work are flagged off/Signage</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[106]=='Work are flagged off/Signage') { echo " checked"; } ?>  name="fields_106" value="Work are flagged off/Signage"><input name="fields_107" type="text" value="<?php echo $fields[107]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Cathodic Protection</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[108]=='Cathodic Protection') { echo " checked"; } ?>  name="fields_108" value="Cathodic Protection"><input name="fields_109" type="text" value="<?php echo $fields[109]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Working at Heights</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[110]=='Working at Heights') { echo " checked"; } ?>  name="fields_110" value="Working at Heights"><input name="fields_111" type="text" value="<?php echo $fields[111]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Site Orientation</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[112]=='Site Orientation') { echo " checked"; } ?>  name="fields_112" value="Site Orientation"><input name="fields_113" type="text" value="<?php echo $fields[113]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Equipment Use/Competent Operator</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[114]=='Equipment Use/Competent Operator') { echo " checked"; } ?>  name="fields_114" value="Equipment Use/Competent Operator"><input name="fields_115" type="text" value="<?php echo $fields[115]; ?>" class="form-control" />

                    </div>
                </div>



			</div>
        </div>
    </div>

<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields17,') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_ppe" >
                    Control Measures - Safety & PPE<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_ppe" class="panel-collapse collapse">
            <div class="panel-body">

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Hearing Protection</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[116]=='Hearing Protection') { echo " checked"; } ?>  name="fields_116" value="Hearing Protection"><input name="fields_117" type="text" value="<?php echo $fields[117]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Personal Atmospheric Monitor</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[118]=='Personal Atmospheric Monitor') { echo " checked"; } ?>  name="fields_118" value="Personal Atmospheric Monitor"><input name="fields_119" type="text" value="<?php echo $fields[119]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Supplied Breathing Air</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[120]=='Supplied Breathing Air') { echo " checked"; } ?>  name="fields_120" value="Supplied Breathing Air"><input name="fields_121" type="text" value="<?php echo $fields[121]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Harness and Lifeline</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[122]=='Harness and Lifeline') { echo " checked"; } ?>  name="fields_122" value="Harness and Lifeline"><input name="fields_123" type="text" value="<?php echo $fields[123]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Fall Protection Harness</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[124]=='Fall Protection Harness') { echo " checked"; } ?>  name="fields_124" value="Fall Protection Harness"><input name="fields_125" type="text" value="<?php echo $fields[125]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Scaffolding</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[126]=='Scaffolding') { echo " checked"; } ?>  name="fields_126" value="Scaffolding"><input name="fields_127" type="text" value="<?php echo $fields[127]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Respirator/Fit Test</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[128]=='Respirator/Fit Test') { echo " checked"; } ?>  name="fields_128" value="Respirator/Fit Test"><input name="fields_129" type="text" value="<?php echo $fields[129]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">First Aid Facilities/Eye Wash</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[130]=='First Aid Facilities/Eye Wash') { echo " checked"; } ?>  name="fields_130" value="First Aid Facilities/Eye Wash"><input name="fields_131" type="text" value="<?php echo $fields[131]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Eye/Face Protection</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[132]=='Eye/Face Protection') { echo " checked"; } ?>  name="fields_132" value="Eye/Face Protection"><input name="fields_133" type="text" value="<?php echo $fields[133]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Fire Retardant Clothing</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[134]=='Fire Retardant Clothing') { echo " checked"; } ?>  name="fields_134" value="Fire Retardant Clothing"><input name="fields_135" type="text" value="<?php echo $fields[135]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Bonding/Grounding</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[136]=='Bonding/Grounding') { echo " checked"; } ?>  name="fields_136" value="Bonding/Grounding"><input name="fields_137" type="text" value="<?php echo $fields[137]; ?>" class="form-control" />

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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_other" >
                    Control Measures - Other<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_other" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Other</label>
                <div class="col-sm-8">
                <textarea name="desc1" rows="3" cols="50" class="form-control"><?php echo $desc1; ?></textarea>
                </div>
                </div>

			</div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields19,') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_ground" >
                    Ground Disturbance<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_ground" class="panel-collapse collapse">
            <div class="panel-body">

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Crossing Agreement</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[138]=='Crossing Agreement') { echo " checked"; } ?>  name="fields_138" value="Crossing Agreement"><input name="fields_139" type="text" value="<?php echo $fields[139]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Alberta One Call Notified</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[140]=='Alberta One Call Notified') { echo " checked"; } ?>  name="fields_140" value="Alberta One Call Notified"><input name="fields_141" type="text" value="<?php echo $fields[141]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Land Owner Notified</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[142]=='Land Owner Notified') { echo " checked"; } ?>  name="fields_142" value="Land Owner Notified"><input name="fields_143" type="text" value="<?php echo $fields[143]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">All Facilities Owners Notified</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[144]=='All Facilities Owners Notified') { echo " checked"; } ?>  name="fields_144" value="All Facilities Owners Notified"><input name="fields_145" type="text" value="<?php echo $fields[145]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Any Signs of New Ground Disturbance?</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[146]=='Any Signs of New Ground Disturbance?') { echo " checked"; } ?>  name="fields_146" value="Any Signs of New Ground Disturbance?"><input name="fields_147" type="text" value="<?php echo $fields[147]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Hand Exposure Required</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[148]=='Hand Exposure Required') { echo " checked"; } ?>  name="fields_148" value="Hand Exposure Required"><input name="fields_149" type="text" value="<?php echo $fields[149]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Code of Practice On-Site</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[150]=='Code of Practice On-Site') { echo " checked"; } ?>  name="fields_150" value="Code of Practice On-Site"><input name="fields_151" type="text" value="<?php echo $fields[151]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Copy of The Pipeline Act On-Site</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[152]=='Copy of The Pipeline Act On-Site') { echo " checked"; } ?>  name="fields_152" value="Copy of The Pipeline Act On-Site"><input name="fields_153" type="text" value="<?php echo $fields[153]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Supervisor Has GD Level II Training</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[154]=='Supervisor Has GD Level II Training') { echo " checked"; } ?>  name="fields_154" value="Supervisor Has GD Level II Training"><input name="fields_155" type="text" value="<?php echo $fields[155]; ?>" class="form-control" />

                    </div>
                </div>

				<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Back Fill Inspections Complete Before Final Sign off</label>
                    <div class="col-sm-8"><input type="checkbox" <?php if ($fields[156]=='Back Fill Inspections Complete Before Final Sign off') { echo " checked"; } ?>  name="fields_156" value="Back Fill Inspections Complete Before Final Sign off"><input name="fields_157" type="text" value="<?php echo $fields[157]; ?>" class="form-control" />

                    </div>
                </div>


			</div>
        </div>
    </div>

<?php } ?>


<?php if (strpos($form_config, ','."fields20".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_validation" >
                    Validation<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_validation" class="panel-collapse collapse">
            <div class="panel-body">


					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Issue Date</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_158" value="<?php echo $fields[158]; ?>" class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Time</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_159" value="<?php echo $fields[159]; ?>" class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Expires</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_160" value="<?php echo $fields[160]; ?>" class="form-control" />
                    </div>
					</div>

					<div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Time</label>
                    <div class="col-sm-8">
                    <input type="text" name="fields_161" value="<?php echo $fields[161]; ?>" class="form-control" />
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