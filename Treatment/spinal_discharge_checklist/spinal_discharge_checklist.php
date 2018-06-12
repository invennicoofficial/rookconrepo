<?php
/*
Add	Sheet
*/
include ('../database_connection.php');
include_once('../tcpdf/tcpdf.php');
require_once('../phpsign/signature-to-image.php');
error_reporting(0);
?>
<script>
function show_value(sel) {
	var sub_heading_number = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
    document.getElementById("slider_"+arr[1]).innerHTML=sub_heading_number;
    var pain_0 = $("#pain_0").val();
    var pain_1 = $("#pain_1").val();
    var pain_2 = $("#pain_2").val();

    var total_score = +pain_0 + +pain_1 + +pain_2;

    $("#total_score").html(total_score);
    $('#hidden_total_score').val(total_score);

    var mean_score = parseInt(total_score) / parseInt(3);
    $("#mean_score").html(round2Fixed(mean_score));
    $('#hidden_mean_score').val(round2Fixed(mean_score));
}
</script>
</head>
<body>

<?php
$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_patientform WHERE form='$form'"));
$form_config = ','.$get_field_config['fields'].',';
?>

<?php if (strpos(','.$form_config.',', ',fields2,') !== FALSE) { ?>
<label class="control-label col-sm-4">Patient :</label> <div class="col-sm-8"><select data-placeholder="Choose a Patient..." name="patientid" class="chosen-select-deselect form-control" width="380">
	<option value=""></option>
	  <?php
		$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Patient' AND deleted=0 AND `status`>0"),MYSQLI_ASSOC));
		foreach($query as $id) {
			$selected = '';
			//$selected = $id == $contactid ? 'selected = "selected"' : '';
			echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
		}
	  ?>
</select></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields3,') !== FALSE) { ?>
    <br><br><label class="control-label col-sm-4">Date :</label> <div class="col-sm-8"><?php echo date('Y-m-d'); ?></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields4,') !== FALSE) { ?>
<br><br>
<div class="col-sm-8 pull-right"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields6,') !== FALSE) { echo " checked"; } ?>  name="rom" value="rom">&nbsp;&nbsp;ROM - Ideally full to 90%</div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields5,') !== FALSE) { ?>
<br><br>
<h5>Strength and endurance : </h5>
<label class="control-label col-sm-4"> - Chin Tuck Head Lift = 89 seconds Female; 2:30 for Male</label>&nbsp;<div class="col-sm-8"><input name="chin" type="text" class="form-control"></div>

<br><br>
<label class="control-label col-sm-4"> - Prone Plank = 1 min for Females; 2 min for Males</label>&nbsp;<div class="col-sm-8"><input name="prone" type="text" class="form-control"></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields6,') !== FALSE) { ?>
<br><br>
<label class="control-label col-sm-4">Resisted isometric at : 0,45,90 degrees Gr.</label>
<input type="range" onchange="show_value(this);" id="pain_0" min="0" max="5" value="0" step="1" name="isometric_0">
<b><span id="slider_0" style="color:red;"></span></b>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields7,') !== FALSE) { ?>
<br><br>
<div class="col-sm-8 pull-right"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields6,') !== FALSE) { echo " checked"; } ?>  name="neck_strap" value="neck_strap">&nbsp;Able to do 3 sets of reps on Neck strap work: Red to green tubing.</div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields8,') !== FALSE) { ?>
<br><br>
<div class="col-sm-8 pull-right"><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields6,') !== FALSE) { echo " checked"; } ?>  name="body_parts" value="body_parts">&nbsp;Other body parts; 80% of normal strength</div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields9,') !== FALSE) { ?>
<br><br>
<h5>Outcome measures</h5>
<label class="control-label col-sm-4">NDI < 10/50, (5/50 indicates no disability)</label>&nbsp;<div class="col-sm-8"><input name="ndi" type="text" class="form-control"></div>

<br><br>
<label class="control-label col-sm-4">PSFS < 5/30</label>&nbsp;<div class="col-sm-8"><input name="psfs" type="text" class="form-control"></div>

<br><br>
<label class="control-label col-sm-4">Roland Morris < 5/24</label>&nbsp;<div class="col-sm-8"><input name="roland" type="text" class="form-control"></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields10,') !== FALSE) { ?>
<br><br>
<label class="control-label col-sm-4">Pain : Visual Analog Scale = 2 or < /10</label>
<input type="range" onchange="show_value(this);" id="pain_1" min="0" max="10" value="0" step="1" name="pain_0">
<b><span id="slider_1" style="color:red;"></span></b>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields11,') !== FALSE) { ?>
<br><br>
<label class="control-label col-sm-4">Client goals met? :</label> <div class="col-sm-8"><textarea name="goals" rows="5" cols="50" class="form-control"></textarea></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields12,') !== FALSE) { ?>
<br><br>
<label class="control-label col-sm-4">Independence :</label> <div class="col-sm-8"><textarea name="independence" rows="5" cols="50" class="form-control"></textarea></div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields13,') !== FALSE) { ?>
<br><br>
<label class="control-label col-sm-4">Testimonial :</label> <div class="col-sm-8"><textarea name="testimonial" rows="5" cols="50" class="form-control"></textarea></div>
<?php } ?>