<?php
/*
Dashboard FFM
*/
include ('../include.php');
checkAuthorised('training_quiz');

if (isset($_POST['submit'])) {
	$type = $_POST['type'];
	$option = $_POST['option'];
	$q1 = filter_var($_POST['q1'],FILTER_SANITIZE_STRING);
	$o1 = filter_var($_POST['o1'],FILTER_SANITIZE_STRING);
	$a1 = filter_var($_POST['a1'],FILTER_SANITIZE_STRING);
	$q2 = filter_var($_POST['q2'],FILTER_SANITIZE_STRING);
	$o2 = filter_var($_POST['o2'],FILTER_SANITIZE_STRING);
	$a2 = filter_var($_POST['a2'],FILTER_SANITIZE_STRING);
	$q3 = filter_var($_POST['q3'],FILTER_SANITIZE_STRING);
	$o3 = filter_var($_POST['o3'],FILTER_SANITIZE_STRING);
	$a3 = filter_var($_POST['a3'],FILTER_SANITIZE_STRING);
	$q4 = filter_var($_POST['q4'],FILTER_SANITIZE_STRING);
	$o4 = filter_var($_POST['o4'],FILTER_SANITIZE_STRING);
	$a4 = filter_var($_POST['a4'],FILTER_SANITIZE_STRING);
	$q5 = filter_var($_POST['q5'],FILTER_SANITIZE_STRING);
	$o5 = filter_var($_POST['o5'],FILTER_SANITIZE_STRING);
	$a5 = filter_var($_POST['a5'],FILTER_SANITIZE_STRING);
	$training_format = $_POST['training_format'];

    $get_tq = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT count(id) AS type_exist, training_content FROM training_quiz WHERE type='$type'"));

	if(($training_format == 'Video') || ($training_format == 'PDF')) {
		$training_content = $_FILES["file"]["name"];
		if (!file_exists('download')) {
			mkdir('download', 0777, true);
		}
		move_uploaded_file($_FILES["file"]["tmp_name"], "download/" . $_FILES["file"]["name"]) ;
	} else {
		$training_content = $_POST['training_content'];
	}

	if($training_content == '') {
		$training_content = $get_tq['training_content'];
	}

	$contain_s = "'";
	$contain_d = '"';
	$training_content = str_replace($contain_s,"*single*",$training_content);
	$training_content = str_replace($contain_d,"*double*",$training_content);

	if($get_tq['type_exist'] == 0) {
		$query_insert_tq = "INSERT INTO `training_quiz` (`type`, `training_format`, `training_content`, `option`, `q1`, `o1`, `a1`, `q2`, `o2`, `a2`, `q3`, `o3`, `a3`, `q4`, `o4`, `a4`, `q5`, `o5`, `a5`) VALUES ('$type', '$training_format', '$training_content', '$option', '$q1', '$o1', '$a1', '$q2', '$o2', '$a2', '$q3', '$o3', '$a3', '$q4', '$o4', '$a4', '$q5', '$o5', '$a5')";
		$result_insert_tq = mysqli_query($dbc, $query_insert_tq);
	} else {
		$query_update_tq = "UPDATE `training_quiz` SET `training_content` = '$training_content', `training_format` = '$training_format', `option` = '$option', `q1` = '$q1', `o1` = '$o1', `a1` = '$a1', `q2` = '$q2', `o2` = '$o2', `a2` = '$a2', `q3` = '$q3', `o3` = '$o3', `a3` = '$a3', `q4` = '$q4', `o4` = '$o4', `a4` = '$a4', `q5` = '$q5', `o5` = '$o5', `a5` = '$a5' WHERE `type` = '$type'";
		$result_update_tq = mysqli_query($dbc, $query_update_tq);
	}

    header('Location: training_quiz.php?training='.$type);
}

?>
<script type="text/javascript">
$(document).ready(function(){
	$('.show_quiz').hide();
	$('.show_video_pdf').hide();
	$('.show_text_editor').hide();

	$("input[name$='option']").click(function() {
		var test = $(this).val();
		if(test == 'Quiz') {
	      $('.show_quiz').show();
		} else {
	      $('.show_quiz').hide();
		}
	});

	$("input[name$='training_format']").click(function() {
		var test = $(this).val();
		if((test == 'Video') || (test == 'PDF')) {
	      $('.show_video_pdf').show();
		  $('.show_text_editor').hide();
		} else {
	      $('.show_text_editor').show();
		  $('.show_video_pdf').hide();
		}
	});
});
</script>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
	<div class="row">
        <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

        <?php
        $training = $_GET['training'];
		$get_tq = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM training_quiz WHERE type='$training'"));
		$q1 = $get_tq['q1'];
		$o1 = $get_tq['o1'];
		$a1 = $get_tq['a1'];
		$q2 = $get_tq['q2'];
		$o2 = $get_tq['o2'];
		$a2 = $get_tq['a2'];

		$q3 = $get_tq['q3'];
		$o3 = $get_tq['o3'];
		$a3 = $get_tq['a3'];

		$q4 = $get_tq['q4'];
		$o4 = $get_tq['o4'];
		$a4 = $get_tq['a4'];

		$q5 = $get_tq['q5'];
		$o5 = $get_tq['o5'];
		$a5 = $get_tq['a5'];

		$v_checked = '';
		$p_checked = '';
		$t_checked = '';

		if($get_tq['training_format'] == 'Video') {
			$v_checked = ' checked';
		}
		if($get_tq['training_format'] == 'PDF') {
			$p_checked = ' checked';
		}
		if($get_tq['training_format'] == 'Text Editor') {
			$t_checked = ' checked';
		}

		$r_checked = '';
		$q_checked = '';

		if($get_tq['option'] == 'Read Receipt') {
			$r_checked = ' checked';
		}
		if($get_tq['option'] == 'Quiz') {
			$q_checked = ' checked';
		}
        ?>
		<input type="hidden" name="type" value="<?php echo $training; ?>">

		  <div class="form-group">
			<label for="file[]" class="col-sm-4 control-label">Training:</label>
			<div class="col-sm-8">
				<label class="pad-right"><input type="radio" name="training_format" value="Video" class="privileges_view" <?php echo $v_checked; ?> >Video</label>
				<label class="pad-right"><input type="radio" name="training_format" value="PDF" class="privileges_archive" <?php echo $p_checked; ?> >PDF</label>
				<label class="pad-right"><input type="radio" name="training_format" value="Text Editor" class="privileges_archive"<?php echo $t_checked; ?> >Text Editor</label>
			</div>
		  </div>

			<div class="show_video_pdf">
				<div class="form-group">
					<label for="file[]" class="col-sm-4 control-label">Video/PDF:</label>
					<div class="col-sm-8">
					  <input name="file" type="file" id="file" data-filename-placement="inside" class="form-control" />
					</div>
				</div>
			</div>

			<div class="show_text_editor">
				<div class="form-group">
					<label for="file[]" class="col-sm-4 control-label">Content:</label>
					<div class="col-sm-8">
						<textarea name="training_content" rows="4" cols="50" class="form-control" ></textarea>
					</div>
				</div>
			</div>

		  <div class="form-group clearfix location">
			<label for="site_name" class="col-sm-4 control-label text-right">Options:</label>
			<div class="col-sm-8">
			  <div class="radio">
				<label class="pad-right"><input type="radio" name="option" value="Read Receipt" class="privileges_view" <?php echo $r_checked; ?> >Read Receipt</label>
				<label class="pad-right"><input type="radio" <?php echo $q_checked; ?> name="option" value="Quiz" class="privileges_archive">Quiz</label>
			  </div>
			</div>
		  </div>

		<div class="show_quiz">
		  <div class="form-group">
			<label for="client_name" class="col-sm-4 control-label">Question 1 :</label>
			<div class="col-sm-8">
			  <input name="q1" value="<?php echo $q1; ?>" type="text" class="form-control">
			</div>
		  </div>

		  <div class="form-group">
			<label for="client_name" class="col-sm-4 control-label">Options 1:<br/><em>(Comma Separated without space)</em></label>
			<div class="col-sm-8">
			  <input name="o1" value="<?php echo $o1; ?>" type="text" class="form-control">
			</div>
		  </div>

		  <div class="form-group">
			<label for="client_name" class="col-sm-4 control-label">Answer 1:</label>
			<div class="col-sm-8">
			  <input name="a1" value="<?php echo $a1; ?>" type="text" class="form-control">
			</div>
		  </div>

		  <div class="form-group">
			<label for="client_name" class="col-sm-4 control-label">Question 2 :</label>
			<div class="col-sm-8">
			  <input name="q2" value="<?php echo $q2; ?>" type="text" class="form-control">
			</div>
		  </div>

		  <div class="form-group">
			<label for="client_name" class="col-sm-4 control-label">Options 2:<br/><em>(Comma Separated without space)</em></label>
			<div class="col-sm-8">
			  <input name="o2" value="<?php echo $o2; ?>" type="text" class="form-control">
			</div>
		  </div>

		  <div class="form-group">
			<label for="client_name" class="col-sm-4 control-label">Answer 2:</label>
			<div class="col-sm-8">
			  <input name="a2" value="<?php echo $a2; ?>" type="text" class="form-control">
			</div>
		  </div>

		  <div class="form-group">
			<label for="client_name" class="col-sm-4 control-label">Question 3 :</label>
			<div class="col-sm-8">
			  <input name="q3" value="<?php echo $q3; ?>" type="text" class="form-control">
			</div>
		  </div>

		  <div class="form-group">
			<label for="client_name" class="col-sm-4 control-label">Options 3:<br/><em>(Comma Separated without space)</em></label>
			<div class="col-sm-8">
			  <input name="o3" value="<?php echo $o3; ?>" type="text" class="form-control">
			</div>
		  </div>

		  <div class="form-group">
			<label for="client_name" class="col-sm-4 control-label">Answer 3:</label>
			<div class="col-sm-8">
			  <input name="a3" value="<?php echo $a3; ?>" type="text" class="form-control">
			</div>
		  </div>

		  <div class="form-group">
			<label for="client_name" class="col-sm-4 control-label">Question 4 :</label>
			<div class="col-sm-8">
			  <input name="q4" value="<?php echo $q4; ?>" type="text" class="form-control">
			</div>
		  </div>

		  <div class="form-group">
			<label for="client_name" class="col-sm-4 control-label">Options 4:<br/><em>(Comma Separated without space)</em></label>
			<div class="col-sm-8">
			  <input name="o4" value="<?php echo $o4; ?>" type="text" class="form-control">
			</div>
		  </div>

		  <div class="form-group">
			<label for="client_name" class="col-sm-4 control-label">Answer 4:</label>
			<div class="col-sm-8">
			  <input name="a4" value="<?php echo $a4; ?>" type="text" class="form-control">
			</div>
		  </div>

		  <div class="form-group">
			<label for="client_name" class="col-sm-4 control-label">Question 5 :</label>
			<div class="col-sm-8">
			  <input name="q5" value="<?php echo $q5; ?>" type="text" class="form-control">
			</div>
		  </div>

		  <div class="form-group">
			<label for="client_name" class="col-sm-4 control-label">Options 5:<br/><em>(Comma Separated without space)</em></label>
			<div class="col-sm-8">
			  <input name="o5" value="<?php echo $o5; ?>" type="text" class="form-control">
			</div>
		  </div>

		  <div class="form-group">
			<label for="client_name" class="col-sm-4 control-label">Answer:</label>
			<div class="col-sm-8">
			  <input name="a5" value="<?php echo $a5; ?>" type="text" class="form-control">
			</div>
		  </div>
		</div>

			<div class="form-group">
				<div class="col-sm-4 clearfix">
					<a href="orientation_training.php" class="btn brand-btn pull-right">Back</a>
				</div>
				<div class="col-sm-8">
					<button type="submit" name="submit" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
				</div>
			</div>

    </form>


	</div>
</div>

<?php include ('../footer.php'); ?>