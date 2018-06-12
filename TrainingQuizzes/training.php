<?php
/*
Training FFM
*/
include ('../include.php');
checkAuthorised('training_quiz');
?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
	<div class="row">

		<form id="form1" name="training" method="post" action="orientation_training.php" enctype="multipart/form-data" class="form-horizontal" role="form">
    	<!-- Admin -->
		<?php
			$type = $_GET['type'];

			if($type == 'result') {
				echo '<h3>Training/Quiz Result</h3>';
				$staff = mysqli_query($dbc, "SELECT t.*, s.first_name, s.last_name FROM training_quiz_result t, contacts s WHERE t.userid = s.contactid ORDER BY t.userid");
                echo "<table class='table table-bordered'>";
                echo "<tr class='hidden-xs hidden-sm'>
				<th>Staff</th>
				<th>Training</th>
				<th>Correct Quiz</th>
				<th>Time</th>
				<th>Date</th>
				</tr>";
				while($row = mysqli_fetch_array( $staff )) {
					echo '<tr>';
					echo '<td data-title="Staff">' . decryptIt($row['first_name']).' '.decryptIt($row['last_name']) . '</td>';
					echo '<td data-title="Training">' . $row['training_name'] . '</td>';
					echo '<td data-title="Correct Quiz">' . $row['correct_quiz'] . '</td>';
					echo '<td data-title="Option">' . $row['timer'] . '</td>';
					echo '<td data-title="Date">' . $row['today_date'] . '</td>';
					echo '</tr>';
				}
				echo '</table>';
				echo '<a href="orientation_training.php" class="btn brand-btn pull-right">Back</a>';
			}
		?>
		</form>

	</div>
</div>

<?php include ('../footer.php'); ?>