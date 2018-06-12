<?php
/*
Project
*/
include ('../include.php');
checkAuthorised('preformance_review');
?>

</head>
<body>
<?php include_once ('../navigation.php');
?>

<div class="container triple-pad-bottom">
    <div class="row">
		<div class="col-md-12">
        <h1 class="double-pad-bottom">Employee Performance Review Dashboard</h1>

		<form id="form1" name="estimate" method="post" action="projects.php" class="form-inline" role="form">

			<a href="add_preformance_review.php" class="btn brand-btn mobile-block gap-bottom pull-right">Add Review</a>
			<div id="no-more-tables">

            <?php
			$query_check_credentials = "SELECT * FROM performance_review ORDER BY userid DESC";

            $result = mysqli_query($dbc, $query_check_credentials);

            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {
            } else{
				echo "<div class='clearfix'><h2>No Record Found.</h2></div>";
            }
			$user_loop = '';
			$submit_inc = 0;
            while($row = mysqli_fetch_array( $result ))
            {
				$userid = $row['userid'];

				if($row['userid'] != $user_loop) {
						echo "<table class='table table-bordered'>";

						echo "<tr class='hidden-xs hidden-sm'>
						<th>Reviewer</th>
						<th>Approx Next Review</th>
						<th>Honesty</th>
						<th>Productivity</th>
						<th>Work Quality</th>
						<th>Technical Skills</th>
						<th>Work Consistency</th>
						<th>Enthusiasm</th>
						<th>Cooperation</th>
						<th>Attitude</th>
						<th>Initiative</th>
						<th>Working Relations</th>
						<th>Creativity</th>
						<th>Punctuality</th>
						<th>Attendance</th>
						<th>Dependability</th>
						<th>Communication Skills</th>
						<th>Function</th>
						</tr>";

					echo '<h3>' . get_staff($dbc, $userid) . '</h3>';
					$user_loop = $row['userid'];
				}
				$reviewerid = $row['reviewerid'];

				echo '<td data-title="UB#">' . get_staff($dbc, $reviewerid) . '</td>';

				echo '<td data-title="Job#">'.$row['next_review'].'</td>';
				echo '<td data-title="Job#">'.$row['honesty'].'</td>';
				echo '<td data-title="Job#">'.$row['productivity'].'</td>';
				echo '<td data-title="Job#">'.$row['work_quality'].'</td>';
				echo '<td data-title="Job#">'.$row['technical_skills'].'</td>';
				echo '<td data-title="Job#">'.$row['work_consistency'].'</td>';
				echo '<td data-title="Job#">'.$row['enthusiasm'].'</td>';
				echo '<td data-title="Job#">'.$row['cooperation'].'</td>';
				echo '<td data-title="Job#">'.$row['attitude'].'</td>';
				echo '<td data-title="Job#">'.$row['initiative'].'</td>';
				echo '<td data-title="Job#">'.$row['working_relations'].'</td>';
				echo '<td data-title="Job#">'.$row['creativity'].'</td>';
				echo '<td data-title="Job#">'.$row['punctuality'].'</td>';
				echo '<td data-title="Job#">'.$row['attendance'].'</td>';
				echo '<td data-title="Job#">'.$row['dependability'].'</td>';
				echo '<td data-title="Job#">'.$row['communication_skills'].'</td>';

                echo '<td data-title="Function">';
				echo '<a href=\'add_preformance_review.php?reviewid='.$row['reviewid'].'\'>Edit</a> | ';
				$name_of_file = 'Review_'.$row['today_date'].'_'.$row['userid'].'.pdf';
				echo '<a href="download/'.$name_of_file.'" target="_blank"> View </a></td>';

				echo "</tr>";
            }

            echo '</table></div>';

            // how many rows we have in database

            $query   = "SELECT COUNT(projectid) AS numrows FROM project";

            ?>

			</form>
        </div>
    </div>
</div>

<?php include ('../footer.php'); ?>