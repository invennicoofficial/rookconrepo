<form name="form_sites" method="post" action="" class="form-inline" role="form">
	<?php

	/* Pagination Counting */
	$rowsPerPage = 25;
	$pageNum = 1;

	if(isset($_GET['page'])) {
		$pageNum = $_GET['page'];
	}

	$offset = ($pageNum - 1) * $rowsPerPage;

	$search_user = $_SESSION['contactid'];

	/*
	$query_check_credentials = "SELECT * FROM tickets WHERE DATE(NOW()) BETWEEN to_do_date AND to_do_end_date AND contactid LIKE '%," . $search_user . ",%' AND status != 'Archive' ORDER BY ticketid DESC LIMIT $offset, $rowsPerPage";
	$query = "SELECT count(*) as numrows FROM tickets WHERE DATE(NOW()) BETWEEN to_do_date AND to_do_end_date AND contactid LIKE '%," . $search_user . ",%' AND status != 'Archive' ORDER BY ticketid DESC";
	*/

	$query_check_credentials = "
		SELECT *
		FROM `tickets`
		WHERE
			(`internal_qa_date`=DATE(NOW()) AND `internal_qa_contactid` LIKE '%," . $search_user . ",%') OR
			(`deliverable_date`=DATE(NOW()) AND `deliverable_contactid` LIKE '%," . $search_user . ",%') OR
			((DATE(NOW()) BETWEEN `to_do_date` AND `to_do_end_date`) AND `contactid` LIKE '%," . $search_user . ",%') AND deleted = 0 AND
			`status` NOT IN('Archive', 'Done')
		ORDER BY `ticketid` DESC
		LIMIT $offset, $rowsPerPage";

	$query = "SELECT count(*) as numrows
		FROM `tickets`
		WHERE
			(`internal_qa_date`=DATE(NOW()) AND `internal_qa_contactid` LIKE '%," . $search_user . ",%') OR
			(`deliverable_date`=DATE(NOW()) AND `deliverable_contactid` LIKE '%," . $search_user . ",%') OR
			((DATE(NOW()) BETWEEN `to_do_date` AND `to_do_end_date`) AND `contactid` LIKE '%," . $search_user . ",%') AND deleted = 0 AND
			`status` NOT IN('Archive', 'Done')
		ORDER BY `ticketid` DESC";

	$result = mysqli_query($dbc, $query_check_credentials);

	$num_rows = mysqli_num_rows($result);
	if($num_rows > 0) {

		// Added Pagination //
		echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
		// Pagination Finish //

		echo '<div id="no-more-tables"><table class="table table-bordered">';
		echo '<tr class="hidden-xs hidden-sm">
			<th>'.TICKET_NOUN.'#</th>
			<th>Business<br>Contact</th>
			<th>Service Type</th>
			<th>'.TICKET_NOUN.' Heading</th>
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
		$ticketid = $row['ticketid'];

		echo '<td data-title="'.TICKET_NOUN.'#">' . $ticketid . '</td>';

		echo '<td data-title="Business/Contact">' . get_contact($dbc, $row['businessid'], 'name').'<br>'.get_contact($dbc, $row['clientid'], 'first_name').' '.get_contact($dbc, $row['clientid'], 'last_name') . '</td>';

		//echo '<td data-title="Serial Number">' . get_client($dbc, $clientid) . '</td>';
		echo '<td data-title="Service Type">' . $row['service'].'<br>'.$row['service_type'] . '</td>';
		echo '<td data-title="'.TICKET_NOUN.' Heading">' . $row['heading'] . '</td>';

		echo '<td data-title="TO DO Date">' . $row['to_do_date'].' - '.$row['to_do_end_date'] . '</td>';
		echo '<td data-title="Internal QA Date">' . $row['internal_qa_date'] . '</td>';
		echo '<td data-title="Deliverable Date">' . $row['deliverable_date'] . '</td>';

		echo '<td data-title="Current Status">' . $row['status'] . '</td>';
		//Comment
		$result_comment = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT count(commentid) AS total_comment FROM comment WHERE fromid=$ticketid AND from_page='tickets'"));
		$comment = $result_comment['total_comment'];
		echo '<td data-title="Function">';
		if(vuaed_visible_function($dbc, 'ticket') == 1) {
			$from = urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']);
			echo '<a href=\''.WEBSITE_URL.'/Ticket/index.php?edit='.$row['ticketid'].'&from='.$from.'\'>Go | </a>';
		}

		echo '<a href=\'../delete_restore.php?action=delete&tab=daysheet&ticketid='.$row['ticketid'].'\' onclick="return confirm(\'Are you sure?\')">Delete</a>';
		echo '</td>';

		echo "</tr>";
	}

	echo '</table></div>';

	// Added Pagination //
	echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
	// Pagination Finish //
	?>
</form>