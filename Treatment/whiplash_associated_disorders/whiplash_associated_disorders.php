<?php
/*
Add	Sheet
*/
include ('../database_connection.php');
include_once('../tcpdf/tcpdf.php');
error_reporting(0);
?>
<script>
function show_value(sel) {
	var sub_heading_number = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
    document.getElementById("slider_"+arr[1]).innerHTML=sub_heading_number;
}
</script>
<style>
.form-control {
    width: 40%;
    display: inline;
}
</style>
</head>
<body>

<?php
$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_patientform WHERE form='$form'"));
$form_config = ','.$get_field_config['fields'].',';
?>

<br><br>This data check list is intended as a guide to the assessment and treatment of a whiplash patient/claimant with Grade I or Grade II WAD injuries. The checklist is not an exhaustive list and does not take into consideration any non-WAD injuries.

<br><br>
<h3>HISTORY (PATIENT/CLAIMANT TO COMPLETE)</h3><br>
Symptom Checklist For each symptom, rate severity on a scale of 0 to 10 where indicated 0 is "No Pain- and 10 is "pain as bad as it could be".
<br><br>

	<?php if (strpos(','.$form_config.',', ',fields2,') !== FALSE) { ?>
    <select data-placeholder="Choose a Patient..." name="patientid" class="chosen-select-deselect form-control" width="380">
		<option value=""></option>
		  <?php
			$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Patient' AND deleted=0 AND `status`>0"),MYSQLI_ASSOC));
			foreach($query as $id) {
				$selected = '';
				//$selected = $id == $contactid ? 'selected = "selected"' : '';
				echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
			}
		  ?>
    </select>
	<?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields3,') !== FALSE) { ?>
        <br><br>Date : <?php echo date('Y-m-d'); ?>
    <?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields4,') !== FALSE) { ?>
        <br><br>Neck or shoulder pain (0-10)<br>
        <input type="range" onchange="show_value(this);" id="pain_0" min="0" max="10" value="0"step="1" name="pain_0">
        <b><span id="slider_0" style="color:red;"></span></b>
    <?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields5,') !== FALSE) { ?>
        <br><br>Upper or Mid-back pain (0-10)<br>
        <input type="range" onchange="show_value(this);" id="pain_1" min="0" max="10" value="0"step="1" name="pain_1">
        <b><span id="slider_1" style="color:red;"></span></b>
    <?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields6,') !== FALSE) { ?>
        <br><br>Low Back Pain (0-10)<br>
        <input type="range" onchange="show_value(this);" id="pain_2" min="0" max="10" value="0"step="1" name="pain_2">
        <b><span id="slider_2" style="color:red;"></span></b>
    <?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields7,') !== FALSE) { ?>
        <br><br>Headache (0-10)<br>
        <input type="range" onchange="show_value(this);" id="pain_3" min="0" max="10" value="0"step="1" name="pain_3">
        <b><span id="slider_3" style="color:red;"></span></b>
    <?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields8,') !== FALSE) { ?>
        <br><br>Pain in Arm(s) (0-10)<br>
        <input type="range" onchange="show_value(this);" id="pain_4" min="0" max="10" value="0"step="1" name="pain_4">
        <b><span id="slider_4" style="color:red;"></span></b>
    <?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields9,') !== FALSE) { ?>
        <br><br>Pain in Hand(s) (0-10)<br>
        <input type="range" onchange="show_value(this);" id="pain_5" min="0" max="10" value="0"step="1" name="pain_5">
        <b><span id="slider_5" style="color:red;"></span></b>
    <?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields10,') !== FALSE) { ?>
        <br><br>Pain in Face or Jaw (0-10)<br>
        <input type="range" onchange="show_value(this);" id="pain_6" min="0" max="10" value="0"step="1" name="pain_6">
        <b><span id="slider_6" style="color:red;"></span></b>
    <?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields11,') !== FALSE) { ?>
        <br><br>Pain in Leg(s) (0-10)<br>
        <input type="range" onchange="show_value(this);" id="pain_7" min="0" max="10" value="0"step="1" name="pain_7">
        <b><span id="slider_7" style="color:red;"></span></b>
    <?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields12,') !== FALSE) { ?>
        <br><br>Pain in Foot/Feet (0-10)<br>
        <input type="range" onchange="show_value(this);" id="pain_8" min="0" max="10" value="0"step="1" name="pain_8">
        <b><span id="slider_8" style="color:red;"></span></b>
    <?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields13,') !== FALSE) { ?>
        <br><br>Pain in Abdomen or Chest (0-10)<br>
        <input type="range" onchange="show_value(this);" id="pain_9" min="0" max="10" value="0"step="1" name="pain_9">
        <b><span id="slider_9" style="color:red;"></span></b>
    <?php } ?>