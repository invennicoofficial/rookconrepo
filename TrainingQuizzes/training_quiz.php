<?php
/*
Dashboard FFM
*/
include ('../include.php');
checkAuthorised('training_quiz');

if (isset($_POST['submit_questionsanswer'])) {
	$type = $_POST['type'];
	$a1 = $_POST['a1'];
	$a2 = $_POST['a2'];
	$a3 = $_POST['a3'];
	$a4 = $_POST['a4'];
	$a5 = $_POST['a5'];
	$timer = $_POST['timer'];

    $get_tq = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM training_quiz WHERE type='$type'"));

    $correct=0;

    for($i=1; $i<=5; $i++) {
        if ($_POST['a'.$i] == $get_tq["a".$i]) {
            $correct++;
        }
    }

    $wrong = 5 - $correct;

    $wrong = 5 - $correct;

	$userid = $_SESSION['contactid'];
	$today_date = date('Y-m-d');
	$option = 'Quiz';

	$query_insert_t = "INSERT INTO `training_quiz_result` (`userid`, `training_name`, `option`, `correct_quiz`, `timer`, `today_date`) VALUES ('$userid', '$type', '$option', '$correct', '$timer', '$today_date')";

	$result_insert_t = mysqli_query($dbc, $query_insert_t);

    if ($correct >= 3) {
        header('Location: orientation_quiz_result.php?result=pass&answer='.$correct);
    }
    else {
        header('Location: orientation_quiz_result.php?result=fail&answer='.$wrong);
    }

}

if (isset($_POST['submit_checkmark'])) {
    header('Location: orientation_training.php');
}

?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
	<div class="row">
        <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

        <?php
        $training = $_GET['training'];
        $get_tq = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM training_quiz WHERE type='$training'"));
        ?>
        <h2> Training Material :  </h2>

		<p class="pull-right mobile-block">
		<input type='text' readonly name='timer' class='form-control timer' placeholder='0 sec' />
		</p>

		<?php
			if($get_tq['training_format'] == 'Video') {
		?>
		<center><video width='720' height='540' controls>
		  <source type='video/mp4' src="<?php echo 'download/'.$get_tq['training_content']; ?>" id='fill_up_video_src'>
		Your browser does not support the video tag.
		</video></center>

		<?php }
			if($get_tq['training_format'] == 'PDF') {
		?>
		<center><object height="500" data="<?php echo 'download/'.$get_tq['training_content']; ?>" type="application/pdf" width="860">
            <p>It appears you don't have a PDF plugin for this browser.
                No biggie... you can <a href="sample-report.pdf">click here to
                download the PDF file.</a>
            </p>
        </object></center>
		<?php }
			if($get_tq['training_format'] == 'Text Editor') {
				echo nl2br($get_tq['training_content']);
			}
		?>

		<input type="hidden" name="type" value="<?php echo $training; ?>">

		<?php if($get_tq['option'] == 'Read Receipt') { ?>
			<div class="form-group">
				<div class="col-sm-8 col-sm-offset-4">
					<label for="site_name"><input type="checkbox" name="accept_policy" required value=1>&nbsp; I have read and understand this Training material.<em class="text-red">*</em></label>
				</div>
			</div>

		  <div class="form-group">
			<div class="col-sm-4 clearfix">
				<a href="orientation_training.php" class="btn brand-btn pull-right">Back</a>
			</div>
			<div class="col-sm-8">
				<button type="submit" name="submit_checkmark" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
			</div>
		  </div>

		<?php } else { ?>
			<h2>Quiz</h2>
			<table class='table table-bordered'>

					<tr>
						<td>
							<h5>Question 1</h5>
							<?php echo $get_tq['q1'] ?>  <br/>
							<?php
							$tags = explode(',',$get_tq['o1']);
							foreach($tags as $key) {
								echo '<input type="radio" required value="'.$key.'" name="a1">'.$key.'<br>';
							}
							?>
						</td>
					</tr>

					<tr>
						<td>
							<h5>Question 2</h5>
							<?php echo $get_tq['q2'] ?>  <br/>
							<?php
							$tags = explode(',',$get_tq['o2']);
							foreach($tags as $key) {
								echo '<input type="radio" required value="'.$key.'" name="a2">'.$key.'<br>';
							}
							?>
						</td>
					</tr>

					<tr>
						<td>
							<h5>Question 3</h5>
							<?php echo $get_tq['q3'] ?>  <br/>
							<?php
							$tags = explode(',',$get_tq['o3']);
							foreach($tags as $key) {
								echo '<input type="radio" required value="'.$key.'" name="a3">'.$key.'<br>';
							}
							?>
						</td>
					</tr>

					<tr>
						<td>
							<h5>Question 4</h5>
							<?php echo $get_tq['q4'] ?>  <br/>
							<?php
							$tags = explode(',',$get_tq['o4']);
							foreach($tags as $key) {
								echo '<input type="radio" required value="'.$key.'" name="a4">'.$key.'<br>';
							}
							?>
						</td>
					</tr>

					<tr>
						<td>
							<h5>Question 5</h5>
							<?php echo $get_tq['q5'] ?>  <br/>
							<?php
							$tags = explode(',',$get_tq['o5']);
							foreach($tags as $key) {
								echo '<input type="radio" required value="'.$key.'" name="a5">'.$key.'<br>';
							}
							?>
						</td>
					</tr>

					<tr>
						<td>
						  <div class="form-group">
							<div class="col-sm-4 clearfix">
								<a href="orientation_training.php" class="btn brand-btn pull-right">Back</a>
							</div>
							<div class="col-sm-8">
								<button type="submit" name="submit_questionsanswer" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
							</div>
						  </div>

						</td>
					</tr>
			</table>
		<?php } ?>
			<div class="form-group">
				<div class="col-sm-8">
					<a class="btn brand-btn btn-lg" href='change_training_quiz.php?training=<?php echo $training?>' >Upload</a>
				</div>
			</div>
    </form>

	</div>
</div>
	<script>
	   (function(){
			var hasTimer = false;
			// Init timer start
			//$('.start-timer-btn').on('click', function() {
				hasTimer = true;
				$('.timer').timer({
					editable: true
				});
				$(this).addClass('hidden');
				$('.pause-timer-btn, .remove-timer-btn').removeClass('hidden');
				return false;
			//});

		})();
	</script>
<?php include ('../footer.php'); ?>