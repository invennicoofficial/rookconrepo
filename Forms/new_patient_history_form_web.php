<?php
/*
NEW PATIENT HISTORY FORM
*/
include ('../include.php');
include_once('../tcpdf/tcpdf.php');

if (isset($_POST['submit'])) {

	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$date = $_POST['date'];
	$long_injury = $_POST['long_injury'];
	$accident = $_POST['accident'];
	$accident_desc = $_POST['accident_desc'];
	$score_pain = $_POST['score_pain'];
	//$pain_desc = $_POST['pain_desc'];
	$pain_worse = $_POST['pain_worse'];
	$pain_better = $_POST['pain_better'];
	$activity1_name = $_POST['activity1_name'];
	$activity1_score = $_POST['activity1_score'];
	$activity2_name = $_POST['activity2_name'];
	$activity2_score = $_POST['activity2_score'];
	$activity3_name = $_POST['activity3_name'];
	$activity3_score = $_POST['activity3_score'];
	$goal = $_POST['goal'];
	$occupation = $_POST['occupation'];
	$work_influences = $_POST['work_influences'];
	$activity_like = $_POST['activity_like'];
	$condition_dizziness = $_POST['condition_dizziness'];
	$condition_dizziness_comment = $_POST['condition_dizziness_comment'];
	$condition_bal_pro = $_POST['condition_bal_pro'];
	$condition_bal_pro_comment = $_POST['condition_bal_pro_comment'];
	$condition_bladder = $_POST['condition_bladder'];
	$condition_bladder_comment = $_POST['condition_bladder_comment'];
	$condition_face = $_POST['condition_face'];
	$condition_face_comment = $_POST['condition_face_comment'];
	$condition_groin = $_POST['condition_groin'];
	$condition_groin_comment = $_POST['condition_groin_comment'];
	$condition_cough = $_POST['condition_cough'];
	$condition_cough_comment = $_POST['condition_cough_comment'];
	$investigative_test = $_POST['investigative_test'];
	$past_medical_history = implode(',',$_POST['past_medical_history']);
	$past_medical_history_other = $_POST['past_medical_history_other'];

	$query_new_member = "INSERT INTO `patients` (`first_name`, `last_name`) VALUES ('$first_name', '$last_name')";
	$result_new_member = mysqli_query($dbc, $query_new_member);
	$patientid = mysqli_insert_id($dbc);


	$query_insert_form = "INSERT INTO `new_patient_history_form` (`patientid`, `name`, `date`, `long_injury`, `accident`, `accident_desc`, `score_pain`, `pain_desc`, `pain_worse`, `pain_better`, `activity1_name`, `activity1_score`, `activity2_name`, `activity2_score`, `activity3_name`, `activity3_score`, `goal`, `occupation`, `work_influences`, `activity_like`, `condition_dizziness`, `condition_dizziness_comment`, `condition_bal_pro`, `condition_bal_pro_comment`, `condition_bladder`, `condition_bladder_comment`, `condition_face`, `condition_face_comment`, `condition_groin`, `condition_groin_comment`, `condition_cough`, `condition_cough_comment`, `investigative_test`, `past_medical_history`, `past_medical_history_other`) VALUES ('$patientid', '".$first_name." ".$last_name."', '$date', '$long_injury', '$accident', '$accident_desc', '$score_pain', '$pain_desc', '$pain_worse', '$pain_better', '$activity1_name', '$activity1_score', '$activity2_name', '$activity2_score', '$activity3_name', '$activity3_score', '$goal', '$occupation', '$work_influences', '$activity_like', '$condition_dizziness', '$condition_dizziness_comment', '$condition_bal_pro', '$condition_bal_pro_comment', '$condition_bladder', '$condition_bladder_comment', '$condition_face', '$condition_face_comment', '$condition_groin', '$condition_groin_comment', '$condition_cough', '$condition_cough_comment', '$investigative_test', '$past_medical_history', '$past_medical_history_other')";

	$result_insert_form = mysqli_query($dbc, $query_insert_form);
	$formid = mysqli_insert_id($dbc);

	$medication = $_POST['medication'];
	$medication_reason = $_POST['medication_reason'];
	$medication_time = $_POST['medication_time'];
	$injury_year = $_POST['injury_year'];
	$injury_name = $_POST['injury_name'];

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
			$image_file = WEBSITE_URL.'/img/brand-logo-white.png';
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
	<b>NAME : '.$first_name.' '.$last_name.'<br/>
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
	<table border="1" style="border:1px solid black">
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
	<table  border="1" style="border:1px solid black">
		<tr>
			<th>Medications</th>
			<th>Reason for medication</th>
			<th>How long have you been taking them?</th>
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
	<table  border="1" style="border:1px solid black">
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

	// Send PDF
	/*	$to_ffm = 'kelseynealon@freshfocusmedia.com';
		$subject_ffm = 'Clinic Ace Physiotherapy WCB Registration Form';

		$message_ffm = 'Please find the completed New Patient History form attached below.';
		$file = 'Download/patient_history_'.$formid.'.pdf';
		$filename = basename($file);
		$file_size = filesize($file);
		$content = chunk_split(base64_encode(file_get_contents($file)));
		$uid = md5(uniqid(time()));
		$headers_ffm = "From: info@fifthavephysio.com/\r\n"
		  ."MIME-Version: 1.0\r\n"
		  ."Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n"
		  ."This is a multi-part message in MIME format.\r\n"
		  ."--".$uid."\r\n"
		  ."Content-type:text/html; charset=iso-8859-1\r\n"
		  ."Content-Transfer-Encoding: 7bit\r\n\r\n"
		  .$message_ffm."\r\n\r\n"
		  ."--".$uid."\r\n"
		  ."Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"
		  ."Content-Transfer-Encoding: base64\r\n"
		  ."Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n"
		  .$content."\r\n\r\n"
		  ."--".$uid."--";
		  echo $headers_ffm.'<br>';
		  mail($to_ffm, $subject_ffm, $message_ffm, $headers_ffm); */

		// Send PDF
		// Above email code currently gives the error: " Warning: mail(): Multiple or malformed newlines found in additional_header in /home/freshfocus/fifth.freshfocuscrm.com/new_patient_history_form_web.php on line 246 "
	// PDF

    echo "<script>alert('Thank you for your submission!');window.location.href = 'http://www.fifthavephysio.com/clients/';</script>";

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


	$('.customer_info').hide();
	$('.existing_customer').hide();

	$('.add_new_customer').on( 'click', function () {
		$('.customer_db').hide();
		$('.customer_info').show();
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

    var j=2;
	$('#add_injury_button').on( 'click', function () {
        var clone = $('.additional_injury').clone();
        clone.find('.injury').val('');

        clone.removeClass("additional_injury");

        clone.find('.change_injury_title').html("<hr/>Injury "+j);

        $('#add_here_new_injury').append(clone);
       // $(".additional_contact").clone().insertAfter("#add_here_new_contact").attr('id', newId());
       j++;
       return false;
    });

} );

</script>
</head>

<body>
<?php //include_once ('../navigation.php');

?>
<div class="container">
  <div class="row">
			<div class="col-sm-4 clearfix">
                <a href="http://www.fifthavephysio.com/clients" class="btn brand-btn ">Back</a>
            </div><br>
        <h1 class="triple-pad-bottom">NEW PATIENT HISTORY FORM</h1>

		<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">



                <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">First Name*:</label>
            <div class="col-sm-8">
              <input required name="first_name" type="text" class="form-control fname" />
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Last Name*:</label>
            <div class="col-sm-8">
              <input required name="last_name" type="text" class="form-control lname" />
            </div>
          </div>
hai
		  <div class="form-group clearfix completion_date" style="display:none;">
			<label for="first_name" class="col-sm-4 control-label text-right">DATE:</label>
			<div class="col-sm-8">
				<input name="date" type="text" value="<?php echo date('Y-m-d'); ?>" class="form-control" />
			</div>
		  </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">How long have you had this injury*?</label>
            <div class="col-sm-8">
              <input required name="long_injury" type="text" class="form-control" />
            </div>
          </div>

			<div class="form-group">
				<label for="site_name" class="col-sm-4 control-label">Was there an accident that brought on the problem*?</label>
				<div class="col-sm-8">
				  <div class="radio">
					<label class="pad-right"><input required  type="radio" name="accident" value="Yes">Yes</label>
					<label class="pad-right"><input required  type="radio" name="accident" value="No">No</label>
					<label class="pad-right"><input  required type="radio" name="accident" value="Unsure">Unsure</label>
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
            <label for="site_name" class="col-sm-4 control-label">On a scale of 0 - 10 how would you score your pain on average*?<br>(if 0 is no pain and 10 is the worst pain you can imagine)</label>
            <div class="col-sm-8">
              	<select  required  data-placeholder="Choose a Score..." name="score_pain" class="chosen-select-deselect form-control" width="380">
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
            <label for="site_name" class="col-sm-4 control-label">What makes your pain worse*?</label>
            <div class="col-sm-8">
              <input name="pain_worse" required  type="text" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">What makes your pain better*?</label>
            <div class="col-sm-8">
              <input name="pain_better" type="text"  required class="form-control" />
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
            <label for="site_name" class="col-sm-4 control-label">What are your goals/expectations from your experience with us*?</label>
            <div class="col-sm-8">
              <input name="goal" type="text" class="form-control" required  />
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">What is your occupation*?</label>
            <div class="col-sm-8">
              <input name="occupation" type="text" class="form-control" required  />
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Please describe anything at work that influences your injury/pain (e.g., prolonged sitting, stress, physical job demands)*</label>
            <div class="col-sm-8">
              <input name="work_influences" required  type="text" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">What activities do you like to do*?</label>
            <div class="col-sm-8">
              <input name="activity_like" type="text" class="form-control"  required />
            </div>
          </div>

			Do you Experience any of the following*?<br><br>
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
					<td align="center" valign="middle"><input  required type="radio" name="condition_dizziness" value="Yes"></td>
					<td align="center" valign="middle"><input required  type="radio" name="condition_dizziness" value="No"></td>
					<td align="center" valign="middle"><input  required type="radio" name="condition_dizziness" value="Past"></td>
					<td align="center" valign="middle"><input name="condition_dizziness_comment" type="text" class="form-control" /></td>
				</tr>
				<tr>
					<th align="center" valign="middle">Balance Problems</th>
					<td align="center" valign="middle"><input  required type="radio" name="condition_bal_pro" value="Yes"></td>
					<td align="center" valign="middle"><input required  type="radio" name="condition_bal_pro" value="No"></td>
					<td align="center" valign="middle"><input required  type="radio" name="condition_bal_pro" value="Past"></td>
					<td align="center" valign="middle"><input name="condition_bal_pro_comment" type="text" class="form-control" /></td>
				</tr>
				<tr>
					<th align="center" valign="middle">Change in bladder and bowel function</th>
					<td align="center" valign="middle"><input required  type="radio" name="condition_bladder" value="Yes"></td>
					<td align="center" valign="middle"><input  required type="radio" name="condition_bladder" value="No"></td>
					<td align="center" valign="middle"><input  required type="radio" name="condition_bladder" value="Past"></td>
					<td align="center" valign="middle"><input name="condition_bladder_comment" type="text" class="form-control" /></td>
				</tr>
				<tr>
					<th align="center" valign="middle">Numbness in face</th>
					<td align="center" valign="middle"><input  required type="radio" name="condition_face" value="Yes"></td>
					<td align="center" valign="middle"><input required  type="radio" name="condition_face" value="No"></td>
					<td align="center" valign="middle"><input  required type="radio" name="condition_face" value="Past"></td>
					<td align="center" valign="middle"><input name="condition_face_comment" type="text" class="form-control" /></td>
				</tr>
				<tr>
					<th align="center" valign="middle">Numbness in the groin region</th>
					<td align="center" valign="middle"><input required  type="radio" name="condition_groin" value="Yes"></td>
					<td align="center" valign="middle"><input  required type="radio" name="condition_groin" value="No"></td>
					<td align="center" valign="middle"><input  required type="radio" name="condition_groin" value="Past"></td>
					<td align="center" valign="middle"><input name="condition_groin_comment" type="text" class="form-control" /></td>
				</tr>
				<tr>
					<th align="center" valign="middle">Pain with coughing or sneezing</th>
					<td align="center" valign="middle"><input required  type="radio" name="condition_cough" value="Yes"></td>
					<td align="center" valign="middle"><input required  type="radio" name="condition_cough" value="No"></td>
					<td align="center" valign="middle"><input required  type="radio" name="condition_cough" value="Past"></td>
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
				<label for="site_name" class="col-sm-4 control-label">Medications:</label>
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
				<label for="site_name" class="col-sm-4 control-label">How long have you been taking them?</label>
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