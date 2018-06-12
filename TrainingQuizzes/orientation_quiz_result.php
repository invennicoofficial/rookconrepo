<?php
/*
Dashboard FFM
*/
include ('../include.php');
checkAuthorised('training_quiz');
?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<?php

			if($_GET['result'] == 'pass') {
			    echo '<h3>Congratulations!!! You have passed your exam... </h3>';
			    echo '<h4>Total '.$_GET['answer'] .' Questions are right.</h4>';
			    echo '<a href="orientation_training.php">Go Back</a>';
			} else {
			    echo '<h3>Sorry!!! You Fail in your exam... </h3>';
			    echo '<h4>Total '.$_GET['answer'] .' Questions are wrong. In order to pass this exam 3 question shound be right from 5.</h4>';
			    echo 'Keep Trying!!! <a href="orientation_training.php">Go Back</a> to give exam another time.';
			    echo '<h4>All The Best!!!</h4>';
			}

			?>
		</div>
	</div>
</div>

<?php include ('../footer.php'); ?>