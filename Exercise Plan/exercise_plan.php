<?php
/* Pagination Counting */
$rowsPerPage = 25;
$pageNum = 1;

if(isset($_GET['page'])) {
	$pageNum = $_GET['page'];
}

$offset = ($pageNum - 1) * $rowsPerPage;

echo '<a href="edit_exercise_plan.php" class="btn brand-btn pull-right">Add Exercise Plan</a>';
//$query_check_credentials = "SELECT * FROM treatment WHERE DATE(treatment_date) = DATE(NOW()) ORDER BY treatmentid DESC";

$query_check_credentials = "SELECT * FROM treatment_exercise_plan WHERE deleted=0 ORDER BY treatmentexerciseid DESC LIMIT $offset, $rowsPerPage";
$query = "SELECT count(*) as numrows FROM treatment_exercise_plan WHERE deleted=0 ORDER BY treatmentexerciseid DESC";

$result = mysqli_query($dbc, $query_check_credentials);

$num_rows = mysqli_num_rows($result);
if($num_rows > 0) {
	// Added Pagination //
	echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
	$status_loop = '';
	while($row = mysqli_fetch_array( $result ))
	{
		if($row['therapistsid'] != $status_loop) {
			if($status_loop != '') {
				echo "</table>";
			}
			echo '<h3>Therapist: '.get_contact($dbc, $row['therapistsid']).'</h3>';
			echo "<table border='2' cellpadding='10' class='table'>";
			echo "<tr>
			<th>Patient</th>
			<th>Injury</th>
			<th>Date Last Updated</th>
			<th>Function</th>
			</tr>";
			$status_loop = $row['therapistsid'];
		}

		echo "<tr>";
		echo '<td>'.get_contact($dbc, $row['patientid']). '</td>';
		echo '<td>' . get_all_from_injury($dbc, $row['injuryid'], 'injury_name').' - '.get_all_from_injury($dbc, $row['injuryid'], 'injury_type').' : '.
			get_all_from_injury($dbc, $row['injuryid'], 'injury_date'). '</td>';
		echo '<td>' . $row['updated_at']. '</td>';
		echo '<td><a href=\'edit_exercise_plan.php?treatmentexerciseid='.$row['treatmentexerciseid'].'\'>Edit</a> |
			<a href="'.$row['file_name'].'"><img src="'.WEBSITE_URL.'/img/pdf.png"> PDF</a> |
			<a href="edit_exercise_plan.php?treatmentexerciseid='.$row['treatmentexerciseid'].'&action=archive">Archive</a></td>';
		echo "</tr>";
	}

	echo '</table></div>';
	// Added Pagination //
	echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
	echo "<br><br>";
} else {
	echo "<h2>No Record Found.</h2>";
}

echo '<a href="edit_exercise_plan.php" class="btn brand-btn pull-right">Add Exercise Plan</a>';
?>