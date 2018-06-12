<form name="form_sites" method="post" action="" class="form-inline" role="form">
	<?php
	$search_user = $_SESSION['contactid'];
	$query_check_credentials = "SELECT * FROM workorder WHERE contactid LIKE '%," . $search_user . ",%' ORDER BY workorderid DESC";

	$result = mysqli_query($dbc, $query_check_credentials);

	$num_rows = mysqli_num_rows($result);
	if($num_rows > 0) {
		echo '<div id="no-more-tables"><table class="table table-bordered">';
		echo '<tr class="hidden-xs hidden-sm">
			<th>Workorder#</th>
			<th>Contact</th>
			<th>Service Type</th>
			<th>Workorder Heading</th>
			<th>TO DO Date</th>
			<th>Internal QA Date</th>
			<th>Deliverable Date</th>
			<th>Current Status</th>
			<th>Function</th>
			</tr>';
	} else {
		echo "<h2>No Record Found.</h2>";
	}
	while($row = mysqli_fetch_array( $result )) {
		echo '<tr>';
		$clientid = $row['clientid'];
		$contactid = $row['contactid'];
		$workorderid = $row['workorderid'];

		echo '<td data-title="Workorder#">' . $workorderid . '</td>';

		echo '<td data-title="Contact">' . get_client($dbc, $clientid) . '</td>';
		echo '<td data-title="Service Type">' . $row['service'].'<br>'.$row['service_type'] . '</td>';
		echo '<td data-title="Workorder Heading">' . $row['heading'] . '</td>';

		echo '<td data-title="TO DO Date">' . $row['to_do_date'] . '</td>';
		echo '<td data-title="Internal QA Date">' . $row['internal_qa_date'] . '</td>';
		echo '<td data-title="Deliverable Date">' . $row['deliverable_date'] . '</td>';

		echo '<td data-title="Current Status">' . $row['status'] . '</td>';
		//Comment
		$result_comment = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT count(commentid) AS total_comment FROM comment WHERE fromid=$workorderid AND from_page='workorder'"));
		$comment = $result_comment['total_comment'];
		//echo '<td data-title="Comment"><a href="comment.php?from=workorder&fromid='.$workorderid.'">'.$comment.'</a></td>';
		echo '<td data-title="Function">';
		if(vuaed_visible_function($dbc, 'workorder') == 1) {
			echo '<a href=\''.WEBSITE_URL.'/Work Order/add_workorder.php?workorderid='.$row['workorderid'].'&contactid='.$_GET['contactid'].'&from=daysheet\'>Go</a>';
		}
		echo '</td>';

		echo "</tr>";
	}

	echo '</table></div>';
	?>
</form>