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
<script>
function roland_que() {
  var zzz = $("[type='checkbox']:checked").length;
  $("#total_score").html(zzz);
  $('#hidden_total_score').val(zzz);
}
function show_value(sel) {
	var sub_heading_number = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
    document.getElementById("slider_"+arr[1]).innerHTML=sub_heading_number;
}
</script>
</head>
<body>

<?php
$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_patientform WHERE form='$form'"));
$form_config = ','.$get_field_config['fields'].',';
?>

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
    <br><br>Pain Scale<br>
    <input type="range" onchange="show_value(this);" id="pain_0" min="0" max="10" value="0"step="1" name="pain_0">
    <b><span id="slider_0" style="color:red;"></span></b><br><br>
<?php } ?>

When your back hurts, you may find it difficult to do some of the things you normally do. This list contains some sentences that people have used to describe themselves when they have back pain. When you read them, you may find that some stand out because they describe you today. As you read the list, think of yourself today. When you read a sentence that describes you today, put a circle around its number. If the sentence does not describe you, then leave the space blank and go on to the next one.
<br><br>
Remember only Tick mark the number of the sentence if you are sure that it describes you today.
<br>
<?php if (strpos(','.$form_config.',', ',fields5,') !== FALSE) { ?>
<ul style="list-style-type: none;">
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields1,') !== FALSE) { echo " checked"; } ?>  name="fields[]"  onclick="roland_que()" value="fields1">&nbsp;&nbsp;I stay at home most of the time because of my back.</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields2,') !== FALSE) { echo " checked"; } ?>  name="fields[]"  onclick="roland_que()" value="fields2">&nbsp;&nbsp;I change positions frequently to try to get my back comfortable.</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields3,') !== FALSE) { echo " checked"; } ?>  name="fields[]"  onclick="roland_que()" value="fields3">&nbsp;&nbsp;I walk more slowly than usual because of my back.</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields4,') !== FALSE) { echo " checked"; } ?>  name="fields[]"  onclick="roland_que()" value="fields5">&nbsp;&nbsp;Because of my back, I am not doing any of the jobs that I usually do around the house.</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields5,') !== FALSE) { echo " checked"; } ?>  name="fields[]"  onclick="roland_que()" value="fields5">&nbsp;&nbsp;Because of my back, I use a handrail to get upstairs.</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields6,') !== FALSE) { echo " checked"; } ?>  name="fields[]"  onclick="roland_que()" value="fields6">&nbsp;&nbsp;Because of my back, I lie down to rest more often.</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields7,') !== FALSE) { echo " checked"; } ?>  name="fields[]"  onclick="roland_que()" value="fields7">&nbsp;&nbsp;Because of my back, I have to hold onto something to get out of an easy chair.</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields8,') !== FALSE) { echo " checked"; } ?>  name="fields[]"  onclick="roland_que()" value="fields8">&nbsp;&nbsp;Because of my back, I try to get other people to do things for me.</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields9,') !== FALSE) { echo " checked"; } ?>  name="fields[]"  onclick="roland_que()" value="fields9">&nbsp;&nbsp;I get dressed more slowly than usual because of my back.</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields10,') !== FALSE) { echo " checked"; } ?>  name="fields[]"  onclick="roland_que()" value="fields10">&nbsp;&nbsp;I only stand for short periods of time because of my back.</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields11,') !== FALSE) { echo " checked"; } ?>  name="fields[]"  onclick="roland_que()" value="fields11">&nbsp;&nbsp;Because of my back, I try not to bend or kneel down.</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields12,') !== FALSE) { echo " checked"; } ?>  name="fields[]"  onclick="roland_que()" value="fields12">&nbsp;&nbsp;I find it difficult to get out of a chair because of my back.</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields13,') !== FALSE) { echo " checked"; } ?>  name="fields[]"  onclick="roland_que()" value="fields13">&nbsp;&nbsp;My back is painful almost all of the time.</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields14,') !== FALSE) { echo " checked"; } ?>  name="fields[]"  onclick="roland_que()" value="fields14">&nbsp;&nbsp;I find it difficult to turn over in bed because of my </li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields15,') !== FALSE) { echo " checked"; } ?>  name="fields[]"  onclick="roland_que()" value="fields15">&nbsp;&nbsp;My appetite is not very good because of my back pain.</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields16,') !== FALSE) { echo " checked"; } ?>  name="fields[]"  onclick="roland_que()" value="fields16">&nbsp;&nbsp;I have trouble putting on my socks (or stockings) because of the pain in my back.</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields17,') !== FALSE) { echo " checked"; } ?>  name="fields[]"  onclick="roland_que()" value="fields17">&nbsp;&nbsp;I only walk short distances because of my back pain.</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields18,') !== FALSE) { echo " checked"; } ?>  name="fields[]"  onclick="roland_que()" value="fields18">&nbsp;&nbsp;I sleep less well because of my back.</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields19,') !== FALSE) { echo " checked"; } ?>  name="fields[]"  onclick="roland_que()" value="fields19">&nbsp;&nbsp;Because of my back pain, I get dressed with help from someone else.</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields20,') !== FALSE) { echo " checked"; } ?>  name="fields[]"  onclick="roland_que()" value="fields20">&nbsp;&nbsp;I sit down for most of the day because of my back.</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields21,') !== FALSE) { echo " checked"; } ?>  name="fields[]"  onclick="roland_que()" value="fields21">&nbsp;&nbsp;I avoid jobs around the house because of my back.</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields22,') !== FALSE) { echo " checked"; } ?>  name="fields[]"  onclick="roland_que()" value="fields22">&nbsp;&nbsp;Because of my back pain, I am more irritable and bad tempered with people than usual.</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields23,') !== FALSE) { echo " checked"; } ?>  name="fields[]"  onclick="roland_que()" value="fields23">&nbsp;&nbsp;Because of my back, I go up and down stairs more slowly than usual.</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields24,') !== FALSE) { echo " checked"; } ?>  name="fields[]"  onclick="roland_que()" value="fields24">&nbsp;&nbsp;I stay in bed most of the time because of my back.</li>
</ul>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields6,') !== FALSE) { ?>
<br><br>Total Score :
<h3><span id="total_score"></span> / 24</h3><br><br>
<input type="hidden" name="total_score" id="hidden_total_score">
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields7,') !== FALSE) { ?>
<?php include ('../phpsign/sign.php'); ?>
<?php } ?>