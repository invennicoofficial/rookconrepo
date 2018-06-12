<?php
/*
Users
*/
include ('../include.php');
checkAuthorised('preformance_review');
include_once('../tcpdf/tcpdf.php');

if (isset($_POST['submit'])) {

	$userid = $_POST['userid'];
	$reviewerid = $_POST['reviewerid'];
	$next_review = $_POST['next_review'];
	$honesty = $_POST['honesty'];
	$productivity = $_POST['productivity'];
	$work_quality = $_POST['work_quality'];

	$technical_skills = $_POST['technical_skills'];
	$work_consistency = $_POST['work_consistency'];
	$enthusiasm = $_POST['enthusiasm'];
	$cooperation = $_POST['cooperation'];
	$attitude = $_POST['attitude'];
	$initiative = $_POST['initiative'];
	$working_relations = $_POST['working_relations'];
	$creativity = $_POST['creativity'];
	$punctuality = $_POST['punctuality'];
	$attendance = $_POST['attendance'];
	$dependability = $_POST['dependability'];
	$communication_skills = $_POST['communication_skills'];
	$comment_honesty = filter_var($_POST['comment_honesty'],FILTER_SANITIZE_STRING);
	$comment_productivity = filter_var($_POST['comment_productivity'],FILTER_SANITIZE_STRING);
	$comment_work_quality = filter_var($_POST['comment_work_quality'],FILTER_SANITIZE_STRING);
	$comment_technical_skills = filter_var($_POST['comment_technical_skills'],FILTER_SANITIZE_STRING);
	$comment_work_consistency = filter_var($_POST['comment_work_consistency'],FILTER_SANITIZE_STRING);
	$comment_enthusiasm = filter_var($_POST['comment_enthusiasm'],FILTER_SANITIZE_STRING);
	$comment_cooperation = filter_var($_POST['comment_cooperation'],FILTER_SANITIZE_STRING);
	$comment_attitude = filter_var($_POST['comment_attitude'],FILTER_SANITIZE_STRING);

	$comment_initiative = filter_var($_POST['comment_initiative'],FILTER_SANITIZE_STRING);
	$comment_working_relations = filter_var($_POST['comment_working_relations'],FILTER_SANITIZE_STRING);
	$comment_creativity = filter_var($_POST['comment_creativity'],FILTER_SANITIZE_STRING);
	$comment_punctuality = filter_var($_POST['comment_punctuality'],FILTER_SANITIZE_STRING);
	$comment_attendance = filter_var($_POST['comment_attendance'],FILTER_SANITIZE_STRING);
	$comment_dependability = filter_var($_POST['comment_dependability'],FILTER_SANITIZE_STRING);
	$comment_communication_skills = filter_var($_POST['comment_communication_skills'],FILTER_SANITIZE_STRING);

	$today_date = date('Y-m-d');

	if(empty($_POST['reviewid'])) {
		$query_insert_user = "INSERT INTO `performance_review` (`userid`, `reviewerid`, `next_review`, `honesty`, `productivity`, `work_quality`, `technical_skills`, `work_consistency`, `enthusiasm`, `cooperation`, `attitude`, `initiative`, `working_relations`, `creativity`, `punctuality`, `attendance`, `dependability`, `communication_skills`, `comment_honesty`, `comment_productivity`, `comment_work_quality`, `comment_technical_skills`, `comment_work_consistency`, `comment_enthusiasm`, `comment_cooperation`, `comment_attitude`, `comment_initiative`, `comment_working_relations`, `comment_creativity`, `comment_punctuality`, `comment_attendance`, `comment_dependability`, `comment_communication_skills`, `today_date`) VALUES ('$userid', '$reviewerid', '$next_review', '$honesty', '$productivity', '$work_quality', '$technical_skills', '$work_consistency', '$enthusiasm', '$cooperation', '$attitude', '$initiative', '$working_relations', '$creativity', '$punctuality', '$attendance', '$dependability', '$communication_skills', '$comment_honesty', '$comment_productivity', '$comment_work_quality', '$comment_technical_skills', '$comment_work_consistency', '$comment_enthusiasm', '$comment_cooperation', '$comment_attitude', '$comment_initiative', '$comment_working_relations', '$comment_creativity', '$comment_punctuality', '$comment_attendance', '$comment_dependability', '$comment_communication_skills', '$today_date')";
		$result_insert_user = mysqli_query($dbc, $query_insert_user);
	} else {
		$reviewid = $_POST['reviewid'];
		$query_update_user = "UPDATE `performance_review` SET `userid` = '$userid', `reviewerid` = '$reviewerid',  `next_review` = '$next_review', `honesty` = '$honesty', `productivity` = '$productivity', `work_quality` = '$work_quality', `technical_skills` = '$technical_skills', `work_consistency` = '$work_consistency', `enthusiasm` = '$enthusiasm', `cooperation` = '$cooperation', `attitude` = '$attitude', `initiative` = '$initiative', `working_relations` = '$working_relations', `creativity` = '$creativity', `punctuality` = '$punctuality', `attendance` = '$attendance', `dependability` = '$dependability', `communication_skills` = '$communication_skills', `comment_honesty` = '$comment_honesty', `comment_productivity` = '$comment_productivity', `comment_work_quality` = '$comment_work_quality', `comment_technical_skills` = '$comment_technical_skills', `comment_work_consistency` = '$comment_work_consistency', `comment_enthusiasm` = '$comment_enthusiasm', `comment_cooperation` = '$comment_cooperation', `comment_attitude` = '$comment_attitude', `comment_initiative` = '$comment_initiative', `comment_working_relations` = '$comment_working_relations', `comment_creativity` = '$comment_creativity', `comment_punctuality` = '$comment_punctuality', `comment_attendance` = '$comment_attendance', `comment_dependability` = '$comment_dependability', `comment_communication_skills` = '$comment_communication_skills', `today_date` = '$today_date' WHERE `reviewid` = '$reviewid'";
		$result_update_user = mysqli_query($dbc, $query_update_user);
	}

    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }
		// PDF

		class MYPDF extends TCPDF {

			//Page header
			public function Header() {
				//$image_file = '../img/pdf-logo.png';
                //$this->Image($image_file, 10, 10, 60, '', '', '', 'T', false, 100, '', false, false, 0, false, false, false);
			}

			// Page footer
			public function Footer() {
				// Position at 15 mm from bottom
				$this->SetY(-20);
				$footer_file = '../img/letterhead_footer.png';
				$this->Image($footer_file, '45', '', 120, '', 'PNG', '', 'C', false, 300, '', false, false, 0, false, false, false);
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
		<h2>Employee Performance Review</h2>
		<h3>Employee : '.get_staff($dbc, $userid).'<br>
		Reviewer : '.get_staff($dbc, $reviewerid).'<br>
		Approx Next Review : '.$next_review.'</h3>
		<b>Honesty : '.$honesty.'</b><br>'.$comment_honesty.'<br>
		<b>Productivity : '.$productivity.'</b><br>'.$comment_productivity.'<br>
		<b>Work Quality : '.$work_quality.'</b><br>'.$comment_work_quality.'<br>
		<b>Technical Skills : '.$technical_skills.'</b><br>'.$comment_technical_skills.'<br>
		<b>Work Consistency : '.$work_consistency.'</b><br>'.$comment_work_consistency.'<br>
		<b>Enthusiasm : '.$enthusiasm.'</b><br>'.$comment_enthusiasm.'<br>
		<b>Cooperation : '.$cooperation.'</b><br>'.$comment_cooperation.'<br>
		<b>Attitude : '.$attitude.'</b><br>'.$comment_attitude.'<br>
		<b>Initiative : '.$initiative.'</b><br>'.$comment_initiative.'<br>
		<b>Working Relations : '.$working_relations.'</b><br>'.$comment_working_relations.'<br>
		<b>Creativity : '.$creativity.'</b><br>'.$comment_creativity.'<br>
		<b>Punctuality : '.$punctuality.'</b><br>'.$comment_punctuality.'<br>
		<b>Attendance : '.$attendance.'</b><br>'.$comment_attendance.'<br>
		<b>Dependability : '.$dependability.'</b><br>'.$comment_dependability.'<br>
		<b>Communication Skills : '.$communication_skills.'</b><br>'.$comment_communication_skills.'<br>
		<br/><br/>';

		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->Output('download/Review_'.$today_date.'_'.$userid.'.pdf', 'F');

?>
	<script type="text/javascript" language="Javascript"> window.location.replace("preformance_review.php");
	window.open('download/Review_<?php echo $today_date;?>_<?php echo $userid;?>.pdf', 'fullscreen=yes');
	</script>
    <?php
} // End of the main Submit conditional.

?>
</head>
<body>

<?php include_once ('../navigation.php');?>

<div class="container">
    <div class="row">
		<div class="col-md-12">
		    <h1 class="triple-pad-bottom">Employee Performance Review</h1>

		    <form action="add_preformance_review.php" method="post" class="form-horizontal" role="form">
		    <?php
			$userid = '';
			$reviewerid = '';
			$next_review = '';
			$honesty = '';
			$productivity = '';
			$work_quality = '';

			$technical_skills = '';
			$work_consistency = '';
			$enthusiasm = '';
			$cooperation = '';
			$attitude = '';
			$initiative = '';
			$working_relations = '';
			$creativity = '';
			$punctuality = '';
			$attendance = '';
			$dependability = '';
			$communication_skills = '';
			$comment_honesty = '';
			$comment_productivity = '';
			$comment_work_quality = '';
			$comment_technical_skills = '';
			$comment_work_consistency = '';
			$comment_enthusiasm = '';
			$comment_cooperation = '';
			$comment_attitude = '';

			$comment_initiative = '';
			$comment_working_relations = '';
			$comment_creativity = '';
			$comment_punctuality = '';
			$comment_attendance = '';
			$comment_dependability = '';
			$comment_communication_skills = '';

			if(!empty($_GET['reviewid'])) {
				$reviewid = $_GET['reviewid'];
				$get_review = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM performance_review WHERE reviewid='$reviewid'"));

				$userid = $get_review['userid'];
				$reviewerid = $get_review['reviewerid'];
				$next_review = $get_review['next_review'];
				$honesty = $get_review['honesty'];

				$productivity = $get_review['productivity'];
				$work_quality = $get_review['work_quality'];

				$technical_skills = $get_review['technical_skills'];
				$work_consistency = $get_review['work_consistency'];
				$enthusiasm = $get_review['enthusiasm'];
				$cooperation = $get_review['cooperation'];
				$attitude = $get_review['attitude'];
				$initiative = $get_review['initiative'];
				$working_relations = $get_review['working_relations'];
				$creativity = $get_review['creativity'];
				$punctuality = $get_review['punctuality'];
				$attendance = $get_review['attendance'];
				$dependability = $get_review['dependability'];
				$communication_skills = $get_review['communication_skills'];
				$comment_honesty = $get_review['comment_honesty'];
				$comment_productivity = $get_review['comment_productivity'];
				$comment_work_quality = $get_review['comment_work_quality'];
				$comment_technical_skills = $get_review['comment_technical_skills'];
				$comment_work_consistency = $get_review['comment_work_consistency'];
				$comment_enthusiasm = $get_review['comment_enthusiasm'];
				$comment_cooperation = $get_review['comment_cooperation'];
				$comment_attitude = $get_review['comment_attitude'];

				$comment_initiative = $get_review['comment_initiative'];
				$comment_working_relations = $get_review['comment_working_relations'];
				$comment_creativity = $get_review['comment_creativity'];
				$comment_punctuality = $get_review['comment_punctuality'];
				$comment_attendance = $get_review['comment_attendance'];
				$comment_dependability = $get_review['comment_dependability'];
				$comment_communication_skills = $get_review['comment_communication_skills'];
		    ?>

		    <input type="hidden" name="reviewid" value="<?php echo $reviewid ?>" />

		    <?php } ?>

				<div class="form-group">
				  <label for="site_name" class="col-sm-4 control-label">Staff:</label>
				  <div class="col-sm-8">
					<select data-placeholder="Choose a Staff..." name="userid" class="chosen-select-deselect">
                      <option value=""></option>
                      <option value=""></option>
						<?php
						$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 AND status > 0"),MYSQLI_ASSOC));
						array_filter($query);
						foreach($query as $id) {
							$selected = '';
							$selected = $id == $row['contactid'] ? 'selected = "selected"' : '';
							echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
						}
					  ?>
					</select>
				  </div>
				</div>

				<div class="form-group">
				  <label for="site_name" class="col-sm-4 control-label">Reviewer:</label>
				  <div class="col-sm-8">
					<select data-placeholder="Choose a Reviewer..." name="reviewerid" class="chosen-select-deselect form-control" width="380">
                      <option value=""></option>
						<?php
						$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 AND status > 0"),MYSQLI_ASSOC));
						array_filter($query);
						foreach($query as $id) {
							$selected = '';
							$selected = $id == $reviewerid ? 'selected = "selected"' : '';
							echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
						}
					  ?>
					</select>
				  </div>
				</div>

				<div class="form-group clearfix orientation_date">
					<label for="first_name" class="col-sm-4 control-label text-right">Approx Next Review:</label>
					<div class="col-sm-8">
                        <input name="next_review" value="<?php echo $next_review; ?>" type="text" class="datepicker">
					</div>
				</div>


		<div class="form-group">
			<div class="col-sm-12">
				<div class="form-group">
					<label class="col-sm-2">Points</label>
					<label class="col-sm-1">Excellent</label>
					<label class="col-sm-1">Good</label>
					<label class="col-sm-1">Fair</label>
					<label class="col-sm-1">Poor</label>
					<label class="col-sm-5">Comment</label>
				</div>

				<div class="clearfix">

					<?php for($i=0; $i<= 14; $i++) {
						$point[0] = 'honesty';
						$point[1] = 'productivity';
						$point[2] = 'work quality';
						$point[3] = 'technical skills';
						$point[4] = 'work consistency';
						$point[5] = 'enthusiasm';
						$point[6] = 'cooperation';
						$point[7] = 'attitude';
						$point[8] = 'initiative';
						$point[9] = 'working relations';
						$point[10] = 'creativity';
						$point[11] = 'punctuality';
						$point[12] = 'attendance';
						$point[13] = 'dependability';
						$point[14] = 'communication skills';
						$update_value = str_replace(' ', '_', $point[$i]);
						$comment = 'comment_'.$update_value;
					?>
					<div class="form-group clearfix">
						<div class="col-sm-2">
							<?php echo ucwords($point[$i]);
							 ?>
						</div>
						<div class="col-sm-1">
							<input type="radio" <?php if ($$update_value == 'Excellent') { echo 'checked'; } ?> name="<?php echo str_replace(' ', '_', $point[$i]);?>" value="Excellent">
						</div>
						<div class="col-sm-1">
							<input type="radio" <?php if ($$update_value == 'Good') { echo 'checked'; } ?> name="<?php echo str_replace(' ', '_', $point[$i]);?>" value="Good">
						</div>
						<div class="col-sm-1">
							<input type="radio" <?php if ($$update_value == 'Fair') { echo 'checked'; } ?> name="<?php echo str_replace(' ', '_', $point[$i]);?>" value="Fair">
						</div>
						<div class="col-sm-1">
							<input type="radio" <?php if ($$update_value == 'Poor') { echo 'checked'; } ?> name="<?php echo str_replace(' ', '_', $point[$i]);?>" value="Poor">
						</div>
						<div class="col-sm-5">
							<input name="comment_<?php echo str_replace(' ', '_', $point[$i]);?>" value = "<?php echo $$comment;?>" type="text" maxlength="100"  class="form-control">
						</div>
					</div>

					<?php } $comment_punctuality  ?>

				</div>
			</div>
		</div>

		        <div class="form-group">
		            <div class="col-sm-4">
		                <p><span class="text-red pull-right"><em>Required Fields *</em></span></p>
		            </div>
		            <div class="col-sm-8"></div>
		        </div>

		        <div class="form-group">
		            <div class="col-sm-4 clearfix">
		                <a href="preformance_review.php" class="btn brand-btn mobile-block pull-right">Back</a>
		            </div>
		            <div class="col-sm-8">
		                <input type="submit" name="submit" value="Submit" class="btn btn-lg brand-btn mobile-block pull-right"/>
		            </div>
		        </div>

		</form>

		</div>
	</div>
</div>

<?php include ('../footer.php'); ?>