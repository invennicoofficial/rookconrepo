<?php
/*
NEW PATIENT HISTORY FORM
*/
include_once('../tcpdf/tcpdf.php');
include ('../include.php');
error_reporting(0);

if (isset($_POST['submit'])) {

	$date = filter_var($_POST['date'],FILTER_SANITIZE_STRING);
	$patientid = $_POST['patientid'];
	$long_injury = filter_var($_POST['long_injury'],FILTER_SANITIZE_STRING).' '.$_POST['long_type'];
	$accident = filter_var($_POST['accident'],FILTER_SANITIZE_STRING);
	$accident_desc = filter_var($_POST['accident_desc'],FILTER_SANITIZE_STRING);
	$score_pain = filter_var($_POST['score_pain'],FILTER_SANITIZE_STRING);
	//$pain_desc = filter_var($_POST['pain_desc'],FILTER_SANITIZE_STRING);
	$pain_worse = filter_var($_POST['pain_worse'],FILTER_SANITIZE_STRING);
	$pain_better = filter_var($_POST['pain_better'],FILTER_SANITIZE_STRING);
	$activity1_name = filter_var($_POST['activity1_name'],FILTER_SANITIZE_STRING);
	$activity1_score = filter_var($_POST['activity1_score'],FILTER_SANITIZE_STRING);
	$activity2_name = filter_var($_POST['activity2_name'],FILTER_SANITIZE_STRING);
	$activity2_score = filter_var($_POST['activity2_score'],FILTER_SANITIZE_STRING);
	$activity3_name = filter_var($_POST['activity3_name'],FILTER_SANITIZE_STRING);
	$activity3_score = filter_var($_POST['activity3_score'],FILTER_SANITIZE_STRING);
	$goal = filter_var($_POST['goal'],FILTER_SANITIZE_STRING);
	$neworexistingpatient = filter_var($_POST['neworexispatient'],FILTER_SANITIZE_STRING);
	$occupation = filter_var($_POST['occupation'],FILTER_SANITIZE_STRING);
	$work_influences = filter_var($_POST['work_influences'],FILTER_SANITIZE_STRING);
	$activity_like = filter_var($_POST['activity_like'],FILTER_SANITIZE_STRING);
	$condition_dizziness = filter_var($_POST['condition_dizziness'],FILTER_SANITIZE_STRING);
	$condition_dizziness_comment = filter_var($_POST['condition_dizziness_comment'],FILTER_SANITIZE_STRING);
	$condition_bal_pro = filter_var($_POST['condition_bal_pro'],FILTER_SANITIZE_STRING);
	$condition_bal_pro_comment = filter_var($_POST['condition_bal_pro_comment'],FILTER_SANITIZE_STRING);
	$condition_bladder = filter_var($_POST['condition_bladder'],FILTER_SANITIZE_STRING);
	$condition_bladder_comment = filter_var($_POST['condition_bladder_comment'],FILTER_SANITIZE_STRING);
	$condition_face = filter_var($_POST['condition_face'],FILTER_SANITIZE_STRING);
	$condition_face_comment = filter_var($_POST['condition_face_comment'],FILTER_SANITIZE_STRING);
	$condition_groin = filter_var($_POST['condition_groin'],FILTER_SANITIZE_STRING);
	$condition_groin_comment = filter_var($_POST['condition_groin_comment'],FILTER_SANITIZE_STRING);
	$condition_cough = filter_var($_POST['condition_cough'],FILTER_SANITIZE_STRING);
	$condition_cough_comment = filter_var($_POST['condition_cough_comment'],FILTER_SANITIZE_STRING);
	$investigative_test = filter_var($_POST['investigative_test'],FILTER_SANITIZE_STRING);
	$past_medical_history = implode(',',$_POST['past_medical_history']);
	$past_medical_history_other = filter_var($_POST['past_medical_history_other'],FILTER_SANITIZE_STRING);

	$query_insert_form = "INSERT INTO `new_patient_history_form` (`patientid`, `date`, `long_injury`, `accident`, `accident_desc`, `score_pain`, `pain_desc`, `pain_worse`, `pain_better`, `activity1_name`, `activity1_score`, `activity2_name`, `activity2_score`, `activity3_name`, `activity3_score`, `goal`, `occupation`, `work_influences`, `activity_like`, `condition_dizziness`, `condition_dizziness_comment`, `condition_bal_pro`, `condition_bal_pro_comment`, `condition_bladder`, `condition_bladder_comment`, `condition_face`, `condition_face_comment`, `condition_groin`, `condition_groin_comment`, `condition_cough`, `condition_cough_comment`, `investigative_test`, `past_medical_history`, `past_medical_history_other`) VALUES ('$patientid', '$date', '$long_injury', '$accident', '$accident_desc', '$score_pain', '$pain_desc', '$pain_worse', '$pain_better', '$activity1_name', '$activity1_score', '$activity2_name', '$activity2_score', '$activity3_name', '$activity3_score', '$goal', '$occupation', '$work_influences', '$activity_like', '$condition_dizziness', '$condition_dizziness_comment', '$condition_bal_pro', '$condition_bal_pro_comment', '$condition_bladder', '$condition_bladder_comment', '$condition_face', '$condition_face_comment', '$condition_groin', '$condition_groin_comment', '$condition_cough', '$condition_cough_comment', '$investigative_test', '$past_medical_history', '$past_medical_history_other')";

	$result_insert_form = mysqli_query($dbc, $query_insert_form);

	$formid = mysqli_insert_id($dbc);

	$medication = filter_var($_POST['medication'],FILTER_SANITIZE_STRING);
	$medication_reason = filter_var($_POST['medication_reason'],FILTER_SANITIZE_STRING);
	$medication_time = filter_var($_POST['medication_time'],FILTER_SANITIZE_STRING);
	$injury_year = filter_var($_POST['injury_year'],FILTER_SANITIZE_STRING);
	$injury_name = filter_var($_POST['injury_name'],FILTER_SANITIZE_STRING);

	$N = count($medication);
	for($i=0; $i < $N; $i++) {
		if($medication[$i] != '') {
			$query_insert_medication = "INSERT INTO `new_patient_history_form_medication` (`formid`, `medication`, `medication_reason`, `medication_time`) VALUES ('$formid', '$medication[$i]', '$medication_reason[$i]', '$medication_time[$i]')";
			$result_insert_medication = mysqli_query($dbc, $query_insert_medication);
		}
	}

	$N = count($injury_year);
	for($i=0; $i < $N; $i++) {
		if($injury_year[$i] != '') {
			$query_insert_injury = "INSERT INTO `new_patient_history_form_injury` (`formid`, `injury_year`, `injury_name`) VALUES ('$formid', '$injury_year[$i]', '$injury_name[$i]')";
			$result_insert_injury = mysqli_query($dbc, $query_insert_injury);
		}
	}

	// PDF

	class MYPDF extends TCPDF {

		//Page header
		public function Header() {
			$image_file = WEBSITE_URL.'/img/Clinic-Ace-Logo-Final-250px.png';
			$this->Image($image_file, 10, 10, 90, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		}

		// Page footer
		public function Footer() {
			// Position at 15 mm from bottom
			$this->SetY(-15);
			$this->SetFont('helvetica', 'I', 8);
			$footer_text = 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages().' printed on  '.date('m/d/y').' at '.date('g:i:s A');
			$this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "L", true);
		}
	}

	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, false, false);
	$pdf->setFooterData(array(0,64,0), array(0,64,128));

	$pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	$pdf->AddPage();

	$html = '';
	$html .= '
	<center>
	<b>NEW PATIENT HISTORY FORM</b></center>
	<br/><br/>';

	$html .= '
	<b>NAME : '.get_contact($dbc, $patientid).'<br/>
	DATE : '.$date.'</b><br/><br/>

	How long have you had this injury?  '.$long_injury.'<br/><br/>
	Was there an accident that brought on the problem?  '.$accident.'<br/><br/>
	Please describe:  '.$accident_desc.'<br/><br/>
	On a scale of 0 - 10 how would you score your pain on average?
	(if 0 is no pain and 10 is the worst pain you can imagine) '.$score_pain.'<br/><br/>
	Pain:  '.$pain_desc.'<br/><br/>
	What makes your pain worse?  '.$pain_worse.'<br/><br/>
	What makes your pain better?  '.$pain_better.'<br/><br/>

	The following pertains to the activities you having the most difficulty with (eg: sitting, walking, reaching).
	Please state the activity and score (on a scale of 0 - 10) your ability on average?
	(0 = unable to perform and 10 = no difficulties) <br/>
	Activity 1 '.$activity1_name.' '.$activity1_score.'<br/>
	Activity 2 '.$activity2_name.' '.$activity2_score.'<br/>
	Activity 3 '.$activity3_name.' '.$activity3_score.'<br/>

	What are your goals/expectations from your experience with us?  '.$goal.'<br/><br/>
	What is your occupation?  '.$occupation.'<br/><br/>
	Please describe anything at work that influences your injury/pain (ie: prolonged sitting, stress, physical job demands)  '.$work_influences.'<br/><br/>
	What activities do you like to do?  '.$activity_like.'<br/><br/>

	Do you Experience any of the following?<br/>
	<table border=2>
	<tr>
		<th>Conditions/Symptoms</th>
		<th>Yes/No/Past</th>
		<th>Comments</th>
	</tr>
	<tr>
		<td>Dizziness</td>
		<td>'.$condition_dizziness.'</td>
		<td>'.$condition_dizziness_comment.'</td>
	</tr>
	<tr>
		<td>Balance Problems</td>
		<td>'.$condition_bal_pro.'</td>
		<td>'.$condition_bal_pro_comment.'</td>
	</tr>
	<tr>
		<td>Change in bladder and bowel function</td>
		<td>'.$condition_bladder.'</td>
		<td>'.$condition_bladder_comment.'</td>
	</tr>
	<tr>
		<td>Numbness in face</td>
		<td>'.$condition_face.'</td>
		<td>'.$condition_face_comment.'</td>
	</tr>
	<tr>
		<td>Numbness in the groin region</td>
		<td>'.$condition_groin.'</td>
		<td>'.$condition_groin_comment.'</td>
	</tr>
	<tr>
		<td>Pain with coughing or sneezing</td>
		<td>'.$condition_cough.'</td>
		<td>'.$condition_cough_comment.'</td>
	</tr>
	</table><br/><br/>

	Have you had any investigative tests done for this injury (ie: X-ray, MRI, other)?        '.$investigative_test.'<br/><br/>

	Are you currently taking any medications?<br/>
	<table border=2>
		<tr>
			<th>Medication</th>
			<th>Reason for medication</th>
			<th>How long have you been taking it?</th>
		</tr>';
	$N = count($medication);
	for($i=0; $i < $N; $i++) {
		$html .= '<tr>
			<td>'.$medication[$i].'</td>
			<td>'.$medication_reason[$i].'</td>
			<td>'.$medication_time[$i].'</td>
		</tr>';
	}

	$html .= '</table><br/><br/>

	Past Medical History:  '.$past_medical_history.','.$past_medical_history_other;

	$html .= '<br/><br/>Have you had any other injuries, relevant surgeries or trauma in the past? If so please list: <br/>
	<table border=2>
		<tr>
			<th>Year</th>
			<th>Injury</th>
		</tr>';
	$N = count($injury_year);
	for($i=0; $i < $N; $i++) {
		$html .= '<tr>
			<td>'.$injury_year[$i].'</td>
			<td>'.$injury_name[$i].'</td>
		</tr>';
	}

	$html .= '</table>';

	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output('Download/patient_history_'.$formid.'.pdf', 'F');
	// PDF

    $history_form_md5 .= md5_file("Download/patient_history_".$formid.".pdf");
    $query_update_booking = "UPDATE `new_patient_history_form` SET `history_form_md5` = '$history_form_md5' WHERE `formid` = '$formid'";
    $result_update_booking = mysqli_query($dbc, $query_update_booking);

    echo '<script type="text/javascript"> alert("History for Successfully Added"); window.location.replace("new_patient_history.php"); </script>';

    mysqli_close($dbc); //Close the DB Connection
}

?><style>
.cb-hldr {
	position:relative;

}

body {
	position:relative;
	margin:2%;
	margin-bottom:160px;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
    $("#form1").submit(function( event ) {
        var clientid = $("#clientid").val();
        if (clientid == '') {
                alert("Please make sure you have filled in all of the required fields.");
                return false;
        }
    });

	$('.customer_info').hide();
	$('.existing_customer').hide();

	$('.add_new_customer').on( 'click', function () {
		$('.customer_db').hide();
		$('.customer_info').show();
		$('.neworexispatient').val('1');

		 $("#clientid").prop('required',false);
		 $(".fname").prop('required',true);
		 $(".lname").prop('required',true);
        $('.hideshowcustfields').css("display", "block");
		$('.new_customer').hide();
		$('.existing_customer').show();
	});
	$('.select_existing_customer').on( 'click', function () {
		$('.customer_db').show();
		$('.customer_info').hide();
		$("#clientid").prop('required',true);
		$(".fname").prop('required',false);
		$('.neworexispatient').val('0');
		$(".lname").prop('required',false);
        $('.hideshowcustfields').css("display", "none");
		$('.new_customer').show();
		$('.existing_customer').hide();
	});

    var i=2;
	$('#add_medication_button').on( 'click', function () {
        var clone = $('.additional_medication').clone();
        clone.find('.medication').val('');

        clone.removeClass("additional_medication");

        clone.find('.change_medication_title').html("<hr/>Medication "+i);

        $('#add_here_new_medication').append(clone);
       // $(".additional_contact").clone().insertAfter("#add_here_new_contact").attr('id', newId());
       i++;
       return false;
    });

    var i=2;
	$('#add_injury_button').on( 'click', function () {
        var clone = $('.additional_injury').clone();
        clone.find('.injury').val('');

        clone.removeClass("additional_injury");

        clone.find('.change_injury_title').html("<hr/>Injury "+i);

        $('#add_here_new_injury').append(clone);
       // $(".additional_contact").clone().insertAfter("#add_here_new_contact").attr('id', newId());
       i++;
       return false;
    });

} );

</script>
</head>

<body>
<?php include_once ('../navigation.php');

?>
<div class="container">
  <div class="row">

        <h1 class="triple-pad-bottom">NEW PATIENT HISTORY FORM</h1>

		<form id="form1" name="form1" method="post" action="new_patient_history_form.php" enctype="multipart/form-data" class="form-horizontal" role="form">



          <div class="form-group customer_db">
			  <label for="site_name" class="col-sm-4 control-label">Patient<span class="empire-red">*</span>:</label>
			  <div class="col-sm-8">
				<select  data-placeholder="Choose a Patient..." id="clientid" name="patientid" class="chosen-select-deselect form-control" width="380">
				  <option value=""></option>
				  <?php
					$query = mysqli_query($dbc,"SELECT first_name, last_name, contactid FROM contacts WHERE category='Patient' AND status>0 AND deleted=0");
					while($row = mysqli_fetch_array($query)) {
						echo "<option value='". $row['contactid']."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';
					}
				  ?>
				</select>
			  </div>
			</div>


		   <!-- <div class="form-group new_customer">
			  <label for="site_name" class="col-sm-4 control-label"></label>
			  <div class="col-sm-8">
			  <a target="_blank" class="btn brand-btn mobile-block add_new_customer">Add New Patient</a>
			  </div>
		    </div>
            -->

		  <div class="form-group clearfix completion_date" style="display:none;">
			<label for="first_name" class="col-sm-4 control-label text-right">DATE:</label>
			<div class="col-sm-8">
				<input name="date" type="text" value="<?php echo date('Y-m-d'); ?>" class="form-control" />
			</div>
		  </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">How long have you had this injury?</label>
            <div class="col-sm-8">
              <input name="long_injury" type="text" class="form-control" />
              	<select  data-placeholder="Choose a Type..." name="long_type" class="chosen-select-deselect form-control" width="380">
					<option value=""></option>
					<option value="Days">Days</option>
					<option value="Months">Months</option>
					<option value="Years">Years</option>
				</select>
            </div>
          </div>

			<div class="form-group">
				<label for="site_name" class="col-sm-4 control-label">Was there an accident that brought on the problem?</label>
				<div class="col-sm-8">
				  <div class="radio">
					<label class="pad-right"><input type="radio" name="accident" value="Yes">Yes</label>
					<label class="pad-right"><input type="radio" name="accident" value="No">No</label>
					<label class="pad-right"><input type="radio" name="accident" value="Unsure">Unsure</label>
				  </div>
				</div>
			</div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">If yes, please describe:</label>
            <div class="col-sm-8">
              <input name="accident_desc" type="text" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">On a scale of 0 - 10 how would you score your pain on average?<br>(if 0 is no pain and 10 is the worst pain you can imagine)</label>
            <div class="col-sm-8">
              	<select  data-placeholder="Choose a Score..." name="score_pain" class="chosen-select-deselect form-control" width="380">
					<option value=""></option>
					<option value="0">0</option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
				</select>
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">What makes your pain worse?</label>
            <div class="col-sm-8">
              <input name="pain_worse" type="text" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">What makes your pain better?</label>
            <div class="col-sm-8">
              <input name="pain_better" type="text" class="form-control" />
            </div>
          </div>

			<em>The following pertains to the activities you having the most difficulty with (eg: sitting, walking, reaching).</em><br>
			<strong>Please state the activity and score (on a scale of 0 - 10) your ability on average:</strong><br>
<em>			(0 = unable to perform and 10 = no difficulties)</em>
          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Activity 1</label>
            <div class="col-sm-8">
				<input name="activity1_name" type="text" class="form-control" /><br>
                <select data-placeholder="Choose a Number..." name="activity1_score" class=" form-control" width="380" style="width:100%;">
					<option value=""></option>
					<option value="0">0</option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
				</select>
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Activity 2</label>
            <div class="col-sm-8">
				<input name="activity2_name" type="text" class="form-control" /><br>
                <select data-placeholder="Choose a Number..." name="activity2_score" class=" form-control" width="380">
					<option value=""></option>
					<option value="0">0</option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
				</select>
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Activity 3</label>
            <div class="col-sm-8">
				<input name="activity3_name" type="text" class="form-control" /><br>
                <select data-placeholder="Choose a Number..." name="activity3_score" class="form-control" width="380">
					<option value=""></option>
					<option value="0">0</option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
				</select>
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">What are your goals/expectations from your experience with us?</label>
            <div class="col-sm-8">
              <input name="goal" type="text" class="form-control"  />
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">What is your occupation?</label>
            <div class="col-sm-8">
              <input name="occupation" type="text" class="form-control"  />
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Please describe anything at work that influences your injury/pain (e.g., prolonged sitting, stress, physical job demands)</label>
            <div class="col-sm-8">
              <input name="work_influences"  type="text" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">What activities do you like to do?</label>
            <div class="col-sm-8">
              <input name="activity_like" type="text" class="form-control" />
            </div>
          </div>

			Do you Experience any of the following?<br><br>
			<table border='2' cellpadding='10' class='table'>
                <tr>
                <th align="center" valign="middle">Conditions/Symptoms</th>
                <th align="center" valign="middle">Yes</th>
                <th align="center" valign="middle">No</th>
                <th align="center" valign="middle">Past</th>
                <th align="center" valign="middle">Comments</th>
				</tr>

				<tr>
					<th align="center" valign="middle">Dizziness</th>
					<td align="center" valign="middle"><input type="radio" name="condition_dizziness" value="Yes"></td>
					<td align="center" valign="middle"><input type="radio" name="condition_dizziness" value="No"></td>
					<td align="center" valign="middle"><input type="radio" name="condition_dizziness" value="Past"></td>
					<td align="center" valign="middle"><input name="condition_dizziness_comment" type="text" class="form-control" /></td>
				</tr>
				<tr>
					<th align="center" valign="middle">Balance Problems</th>
					<td align="center" valign="middle"><input type="radio" name="condition_bal_pro" value="Yes"></td>
					<td align="center" valign="middle"><input type="radio" name="condition_bal_pro" value="No"></td>
					<td align="center" valign="middle"><input type="radio" name="condition_bal_pro" value="Past"></td>
					<td align="center" valign="middle"><input name="condition_bal_pro_comment" type="text" class="form-control" /></td>
				</tr>
				<tr>
					<th align="center" valign="middle">Change in bladder and bowel function</th>
					<td align="center" valign="middle"><input type="radio" name="condition_bladder" value="Yes"></td>
					<td align="center" valign="middle"><input type="radio" name="condition_bladder" value="No"></td>
					<td align="center" valign="middle"><input type="radio" name="condition_bladder" value="Past"></td>
					<td align="center" valign="middle"><input name="condition_bladder_comment" type="text" class="form-control" /></td>
				</tr>
				<tr>
					<th align="center" valign="middle">Numbness in face</th>
					<td align="center" valign="middle"><input type="radio" name="condition_face" value="Yes"></td>
					<td align="center" valign="middle"><input   type="radio" name="condition_face" value="No"></td>
					<td align="center" valign="middle"><input type="radio" name="condition_face" value="Past"></td>
					<td align="center" valign="middle"><input name="condition_face_comment" type="text" class="form-control" /></td>
				</tr>
				<tr>
					<th align="center" valign="middle">Numbness in the groin region</th>
					<td align="center" valign="middle"><input   type="radio" name="condition_groin" value="Yes"></td>
					<td align="center" valign="middle"><input type="radio" name="condition_groin" value="No"></td>
					<td align="center" valign="middle"><input type="radio" name="condition_groin" value="Past"></td>
					<td align="center" valign="middle"><input name="condition_groin_comment" type="text" class="form-control" /></td>
				</tr>
				<tr>
					<th align="center" valign="middle">Pain with coughing or sneezing</th>
					<td align="center" valign="middle"><input   type="radio" name="condition_cough" value="Yes"></td>
					<td align="center" valign="middle"><input   type="radio" name="condition_cough" value="No"></td>
					<td align="center" valign="middle"><input   type="radio" name="condition_cough" value="Past"></td>
					<td align="center" valign="middle"><input name="condition_cough_comment" type="text" class="form-control" /></td>
				</tr>
			</table>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">If you have you had any investigative tests done for this injury (i.e., X-ray, MRI, other), then please provide a description:</label>
            <div class="col-sm-8">
              <input name="investigative_test" type="text" class="form-control" />
            </div>
          </div>

			<strong>Are you currently taking any medications?</strong>

		<div class="additional_medication">
			 <h3 class="change_medication_title">Medication 1</h3>

			  <div class="form-group">
				<label for="site_name" class="col-sm-4 control-label">Medication:</label>
				<div class="col-sm-8">
				  <input name="medication[]" type="text" class="form-control medication" />
				</div>
			  </div>
			  <div class="form-group">
				<label for="site_name" class="col-sm-4 control-label">Reason for medication:</label>
				<div class="col-sm-8">
				  <input name="medication_reason[]" type="text" class="form-control medication" />
				</div>
			  </div>
			  <div class="form-group">
				<label for="site_name" class="col-sm-4 control-label">How long have you been taking it?</label>
				<div class="col-sm-8">
				  <input name="medication_time[]" type="text" class="form-control medication" />
				</div>
			  </div>

		</div>
		<div id="add_here_new_medication"></div>

		  <div class="form-group triple-gap-bottom">
			<div class="col-sm-offset-4 col-sm-8">
			  <button id="add_medication_button" class="btn brand-btn pull-right">Add another Medication</button>
			</div>
		  </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Past Medical History (Information will remain confidential):</label>
            <div class="col-sm-8">
			  <div class="radio">
				<div class='cb-hldr'><label class="pad-right"><input type="checkbox" name="past_medical_history[]" value="Heart Disease">&nbsp; Heart Disease</label></div>
				<div class='cb-hldr'><label class="pad-right"><input type="checkbox" name="past_medical_history[]" value="Metal implants">&nbsp; Metal implants</label></div>
				<div class='cb-hldr'><label class="pad-right"><input type="checkbox" name="past_medical_history[]" value="Osteoporosis">&nbsp; Osteoporosis</label></div>
				<div class='cb-hldr'><label class="pad-right"><input type="checkbox" name="past_medical_history[]" value="Pregnant">&nbsp; Pregnant</label></div>
				<div class='cb-hldr'><label class="pad-right"><input type="checkbox" name="past_medical_history[]" value="Steroids">&nbsp; Steroids</label></div>
				<div class='cb-hldr'><label class="pad-right"><input type="checkbox" name="past_medical_history[]" value="Diabetes">&nbsp; Diabetes</label></div>
				<div class='cb-hldr'><label class="pad-right"><input type="checkbox" name="past_medical_history[]" value="Pace Maker">&nbsp; Pace Maker</label></div>
				<div class='cb-hldr'><label class="pad-right"><input type="checkbox" name="past_medical_history[]" value="Epilepsy">&nbsp; Epilepsy</label></div>
				<div class='cb-hldr'><label class="pad-right"><input type="checkbox" name="past_medical_history[]" value="Cancer">&nbsp; Cancer</label></div>
				<div class='cb-hldr'><label class="pad-right"><input type="checkbox" name="past_medical_history[]" value="Bleeding disorders">&nbsp; Bleeding disorders</label></div>
				<div class='cb-hldr'><label class="pad-right"><input type="checkbox" name="past_medical_history[]" value="Circulatory Disorders">&nbsp; Circulatory Disorders</label></div>
				<div class='cb-hldr'><label class="pad-right"><input type="checkbox" name="past_medical_history[]" value="Breathing Disorders">&nbsp; Breathing Disorders</label></div>
				<div class='cb-hldr'><label class="pad-right"><input type="checkbox" name="past_medical_history[]" value="Hepatitis A, B, C">&nbsp; Hepatitis A, B, C</label></div>
				<div class='cb-hldr'><label class="pad-right"><input type="checkbox" name="past_medical_history[]" value="HIV/AIDS">&nbsp; HIV/AIDS</label></div>
				<div class='cb-hldr'><label class="pad-right"><input type="checkbox" name="past_medical_history[]" value="Latex allergy">&nbsp; Latex allergy</label></div><br>
				<label class="pad-right">If other than the conditions listed above, please list below (seperating each condition with a comma)</label>
				<input name="past_medical_history_other" type="text" class="form-control" />
			  </div>
			 </div>
          </div>

			<strong>Have you had any other injuries, relevant surgeries or trauma in the past? If so please list:</strong>
            <div class="additional_injury">
                <h3 class="change_injury_title">Injury 1</h3>

				  <div class="form-group">
					<label for="site_name" class="col-sm-4 control-label">Year:</label>
					<div class="col-sm-8">
					  <input name="injury_year[]" type="text" class="form-control injury" />
					</div>
				  </div>
				  <div class="form-group">
					<label for="site_name" class="col-sm-4 control-label">Injury:</label>
					<div class="col-sm-8">
					  <input name="injury_name[]" type="text" class="form-control injury" />
					</div>
				  </div>

            </div>
            <div id="add_here_new_injury"></div>

              <div class="form-group triple-gap-bottom">
                <div class="col-sm-offset-4 col-sm-8">
                  <button id="add_injury_button" class="btn brand-btn pull-right">Add another Injury</button>
                </div>
              </div>

             <div class="form-group">
                <div class="col-sm-4">
                    <p><span class="empire-red pull-right"><em>Fields *</em></span></p>
                </div>
                <div class="col-sm-8"></div>
            </div>

          <div class="form-group">
            <div class="col-sm-4 clearfix">
                <a href="new_patient_history.php" class="btn brand-btn pull-right">Back</a>
            </div>
            <div class="col-sm-8">
                <button type="submit" name="submit" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
            </div>
          </div>

        

        </form>

    </div>
  </div>
<?php include ('../footer.php'); ?>