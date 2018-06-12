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
    <br><br><label class="control-label col-sm-4">Date : </label><div class="col-sm-8"><?php echo date('Y-m-d'); ?></div>
<?php } ?>

<br><br>
<div class="col-sm-8 pull-right">Please list 3 functional activities you are having trouble with because of your injury or condition.
<br>On a scale of 0-10 list the amount of trouble you have with each activity.</div>
<br>
<?php if (strpos(','.$form_config.',', ',fields4,') !== FALSE) { ?>
    <br><br><label class="control-label col-sm-4">Activity 1</label>
    <div class="col-sm-8"><input name="activity_1" type="text" class="form-control"></div><br><br>
    <input type="range" onchange="show_value(this);" id="pain_0" min="0" max="10" value="0" step="1" name="pain_0">
    <b><span id="slider_0" style="color:red;"></span></b><br><br>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields5,') !== FALSE) { ?>
    <br><br><label class="control-label col-sm-4">Activity 2</label>
    <div class="col-sm-8"><input name="activity_2" type="text" class="form-control"></div><br><br>
    <input type="range" onchange="show_value(this);" id="pain_1" min="0" max="10" value="0" step="1" name="pain_1">
    <b><span id="slider_1" style="color:red;"></span></b><br><br>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields6,') !== FALSE) { ?>
    <br><br><label class="control-label col-sm-4">Activity 3</label>
    <div class="col-sm-8"><input name="activity_3" type="text" class="form-control"></div><br><br>
    <input type="range" onchange="show_value(this);" id="pain_2" min="0" max="10" value="0" step="1" name="pain_2">
    <b><span id="slider_2" style="color:red;"></span></b><br><br>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields7,') !== FALSE) { ?>
<br><br><label class="control-label col-sm-4">Total Score :</label>
<div class="col-sm-8"><h3><span id="total_score"></span> / 30</h3></div><br>
<input type="hidden" name="total_score" id="hidden_total_score">
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields8,') !== FALSE) { ?>
<label class="control-label col-sm-4">Mean Score :</label>
<div class="col-sm-8"><h3><span id="mean_score"></span> / 10</h3></div>
<input type="hidden" name="mean_score" id="hidden_mean_score">
<?php } ?>
<div class="clearfix"></div><br><br>
<?php if (strpos(','.$form_config.',', ',fields9,') !== FALSE) { ?>
<?php include ('../phpsign/sign.php'); ?>
<?php } ?>