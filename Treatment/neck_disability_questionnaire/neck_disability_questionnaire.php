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
function roland_que(sel) {
    var total_score = 0;
    $('input[type="radio"]:checked').each(function() {
        total_score = +total_score + +this.value
    });

    $("#total_score").html(total_score);
    $('#hidden_total_score').val(total_score);
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
Patient : <select data-placeholder="Choose a Patient..." name="patientid" class="chosen-select-deselect form-control" width="380">
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

<b>Please Read:</b> This Questionnaire is designed to enable us to understand how much neck pain has affected your ability to perform your everyday activities.<br><br>
Please answer each section by tick mark <b>ONE CHOICE</b> that most applies to you. We realize that you may feel that more than one statement may relate to you, but <b>PLEASE JUST TICK MARK THE ONE CHOICE WHICH MOST CLOSELY DESCRIBES YOUR PROBLEM RIGHT NOW.</b>
<br><br>

<?php if (strpos(','.$form_config.',', ',fields5,') !== FALSE) { ?>
<ul style="list-style-type: none;">PAIN INTENSITY - Section 1
    <li><input type="radio" name="section1"  onclick="roland_que(this)" value="0">&nbsp;&nbsp;I have no pain at the moment</li>
    <li><input type="radio" name="section1"  onclick="roland_que(this)" value="1">&nbsp;&nbsp;The pain is very mild at the moment</li>
    <li><input type="radio" name="section1"  onclick="roland_que(this)" value="2">&nbsp;&nbsp;The pain is moderate at the moment</li>
    <li><input type="radio" name="section1"  onclick="roland_que(this)" value="3">&nbsp;&nbsp;The pain its fairty severe at the moment</li>
    <li><input type="radio" name="section1"  onclick="roland_que(this)" value="4">&nbsp;&nbsp;The pain is very severe at the moment</li>
    <li><input type="radio" name="section1"  onclick="roland_que(this)" value="5">&nbsp;&nbsp;The pain the worst imaginable at the moment</li>
</ul>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields6,') !== FALSE) { ?>
<ul style="list-style-type: none;">PERSONAL CARE (washing. dressing etc.) - Section 2
    <li><input type="radio" name="section2"  onclick="roland_que(this)" value="0">&nbsp;&nbsp;I can took after myself normally without causing extra pain</li>
    <li><input type="radio" name="section2"  onclick="roland_que(this)" value="1">&nbsp;&nbsp;I can look after myself normally. but it causes extra pain</li>
    <li><input type="radio" name="section2"  onclick="roland_que(this)" value="2">&nbsp;&nbsp;It rs painful to look after myself and I am slow and careful</li>
    <li><input type="radio" name="section2"  onclick="roland_que(this)" value="3">&nbsp;&nbsp;I need some help. but manage most of my personal care</li>
    <li><input type="radio" name="section2"  onclick="roland_que(this)" value="4">&nbsp;&nbsp;I need help every day in most aspects of self care</li>
    <li><input type="radio" name="section2"  onclick="roland_que(this)" value="5">&nbsp;&nbsp;I do not get dressed. I wash with difficulty and stay in bed</li>
</ul>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields7,') !== FALSE) { ?>
    <ul style="list-style-type: none;">LIFTING - Section 3
    <li><input type="radio" name="section3"  onclick="roland_que(this)" value="0">&nbsp;&nbsp;I can lift heavy weights without extra pain</li>
    <li><input type="radio" name="section3"  onclick="roland_que(this)" value="1">&nbsp;&nbsp;I can lift heavy weights. but it gives extra pain</li>
    <li><input type="radio" name="section3"  onclick="roland_que(this)" value="2">&nbsp;&nbsp;Pain prevents me from lifting heavy weights off the floor, but I can manage if they are conveniently positioned, for example on the table</li>
    <li><input type="radio" name="section3"  onclick="roland_que(this)" value="3">&nbsp;&nbsp;Pain prevents me from lifting heavy weights, but I can manage light to medium weights if they are conveniently positioned</li>
    <li><input type="radio" name="section3"  onclick="roland_que(this)" value="4">&nbsp;&nbsp;I can lift very light weights</li>
    <li><input type="radio" name="section3"  onclick="roland_que(this)" value="5">&nbsp;&nbsp;I cannot lift or carry anything at all</li>
</ul>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields8,') !== FALSE) { ?>
<ul style="list-style-type: none;">READING - Section 4
    <li><input type="radio" name="section4"  onclick="roland_que(this)" value="0">&nbsp;&nbsp;I can read as much as I want to with no pain in my neck</li>
    <li><input type="radio" name="section4"  onclick="roland_que(this)" value="1">&nbsp;&nbsp;I can read as much as I want to with slight pain in my neck</li>
    <li><input type="radio" name="section4"  onclick="roland_que(this)" value="2">&nbsp;&nbsp;I can read as much as I want to with moderate pain in my neck</li>
    <li><input type="radio" name="section4"  onclick="roland_que(this)" value="3">&nbsp;&nbsp;I cannot read as much as I want because of moderate pain in my</li>
    <li><input type="radio" name="section4"  onclick="roland_que(this)" value="4">&nbsp;&nbsp;I cannot read as much as I want because of severe pain in my neck</li>
    <li><input type="radio" name="section4"  onclick="roland_que(this)" value="5">&nbsp;&nbsp;I cannot read at all</li>
</ul>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields9,') !== FALSE) { ?>
<ul style="list-style-type: none;">HEADACHES - Section 5
    <li><input type="radio" name="section5"  onclick="roland_que(this)" value="0">&nbsp;&nbsp;I have no headaches at all</li>
    <li><input type="radio" name="section5"  onclick="roland_que(this)" value="1">&nbsp;&nbsp;I have slight headaches which come infrequently</li>
    <li><input type="radio" name="section5"  onclick="roland_que(this)" value="2">&nbsp;&nbsp;I have moderate headaches which come infrequently</li>
    <li><input type="radio" name="section5"  onclick="roland_que(this)" value="3">&nbsp;&nbsp;I have moderate headaches which come frequently</li>
    <li><input type="radio" name="section5"  onclick="roland_que(this)" value="4">&nbsp;&nbsp;I have severe headaches which come frequently</li>
    <li><input type="radio" name="section5"  onclick="roland_que(this)" value="5">&nbsp;&nbsp;I have headaches almost all of the time</li>
</ul>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields10,') !== FALSE) { ?>
<ul style="list-style-type: none;">CONCENTRATION - Section 6
    <li><input type="radio" name="section6"  onclick="roland_que(this)" value="0">&nbsp;&nbsp;I can concentrate fully when I want to with no difficulty</li>
    <li><input type="radio" name="section6"  onclick="roland_que(this)" value="1">&nbsp;&nbsp;I can concentrate fully when I want to with slight difficulty</li>
    <li><input type="radio" name="section6"  onclick="roland_que(this)" value="2">&nbsp;&nbsp;I have a fair degree of difficulty in concentrating when I want to</li>
    <li><input type="radio" name="section6"  onclick="roland_que(this)" value="3">&nbsp;&nbsp;I have a lot of difficulty in concentrating when I want to</li>
    <li><input type="radio" name="section6"  onclick="roland_que(this)" value="4">&nbsp;&nbsp;I have a great deal of difficulty in concentrating when I want to</li>
    <li><input type="radio" name="section6"  onclick="roland_que(this)" value="5">&nbsp;&nbsp;I cannot concentrate at all</li>
</ul>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields11,') !== FALSE) { ?>
<ul style="list-style-type: none;">WORK - Section 7
    <li><input type="radio" name="section7"  onclick="roland_que(this)" value="0">&nbsp;&nbsp;I can do as much work as I want to</li>
    <li><input type="radio" name="section7"  onclick="roland_que(this)" value="1">&nbsp;&nbsp;I can only do my usual work, but no more</li>
    <li><input type="radio" name="section7"  onclick="roland_que(this)" value="2">&nbsp;&nbsp;I can do most of my usual work, but no more</li>
    <li><input type="radio" name="section7"  onclick="roland_que(this)" value="3">&nbsp;&nbsp;I cannot do my usual work</li>
    <li><input type="radio" name="section7"  onclick="roland_que(this)" value="4">&nbsp;&nbsp;I can hardly do any work at all</li>
    <li><input type="radio" name="section7"  onclick="roland_que(this)" value="5">&nbsp;&nbsp;I cannot do any work at all</li>
</ul>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields12,') !== FALSE) { ?>
<ul style="list-style-type: none;">DRIVING - Section 8
    <li><input type="radio" name="section8"  onclick="roland_que(this)" value="0">&nbsp;&nbsp;I can drive my car without any neck pain</li>
    <li><input type="radio" name="section8"  onclick="roland_que(this)" value="1">&nbsp;&nbsp;I can drive my car as long as I want with slight pain in my neck</li>
    <li><input type="radio" name="section8"  onclick="roland_que(this)" value="2">&nbsp;&nbsp;I can drive my car as long as I want with moderate pain in my neck</li>
    <li><input type="radio" name="section8"  onclick="roland_que(this)" value="3">&nbsp;&nbsp;I cannot drive my car as long as I want because of severe pain in my neck</li>
    <li><input type="radio" name="section8"  onclick="roland_que(this)" value="4">&nbsp;&nbsp;I can hardly drive at all because of severe pain in my neck</li>
    <li><input type="radio" name="section8"  onclick="roland_que(this)" value="5">&nbsp;&nbsp;I cannot drive my car at all</li>
</ul>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields13,') !== FALSE) { ?>
<ul style="list-style-type: none;">SLEEPING - Section 9
    <li><input type="radio" name="section9"  onclick="roland_que(this)" value="0">&nbsp;&nbsp;I have no trouble sleeping</li>
    <li><input type="radio" name="section9"  onclick="roland_que(this)" value="1">&nbsp;&nbsp;My sleep is slightly disturbed (less than 1 hour sleepless)</li>
    <li><input type="radio" name="section9"  onclick="roland_que(this)" value="2">&nbsp;&nbsp;My sleep is mildly disturbed (1-2 hours sleepless)</li>
    <li><input type="radio" name="section9"  onclick="roland_que(this)" value="3">&nbsp;&nbsp;My sleep is moderately disturbed (2-3 hours sleepless)</li>
    <li><input type="radio" name="section9"  onclick="roland_que(this)" value="4">&nbsp;&nbsp;My sleep is greatly disturbed (3-4 hours sleepless)</li>
    <li><input type="radio" name="section9"  onclick="roland_que(this)" value="5">&nbsp;&nbsp;My sleep is completely disturbed (5-7 hours sleepless)</li>
</ul>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields14,') !== FALSE) { ?>
<ul style="list-style-type: none;">RECREATION - Section 10
    <li><input type="radio" name="section10"  onclick="roland_que(this)" value="0">&nbsp;&nbsp;I am able to engage in all of my recreational activities with no neck pain at all</li>
    <li><input type="radio" name="section10"  onclick="roland_que(this)" value="1">&nbsp;&nbsp;I am able to engage in all of my recreational activities with some pain in my neck</li>
    <li><input type="radio" name="section10"  onclick="roland_que(this)" value="2">&nbsp;&nbsp;I am able to engage in most, but not all of my recreational activities because of pain in my neck</li>
    <li><input type="radio" name="section10"  onclick="roland_que(this)" value="3">&nbsp;&nbsp;I am able to engage in a few of my recreational activities because of pain in my neck</li>
    <li><input type="radio" name="section10"  onclick="roland_que(this)" value="4">&nbsp;&nbsp;I can hardly do any recreational activities because of pain in my neck</li>
    <li><input type="radio" name="section10"  onclick="roland_que(this)" value="5">&nbsp;&nbsp;I cannot do any recreational activities at all</li>
</ul>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields15,') !== FALSE) { ?>
<br><br>Total Score :
<h3><span id="total_score"></span> / 50</h3><br><br>
<input type="hidden" name="total_score" id="hidden_total_score">
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields16,') !== FALSE) { ?>
<?php include ('../phpsign/sign.php'); ?>
<?php } ?>