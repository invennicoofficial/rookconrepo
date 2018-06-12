<?php
/*
WT
*/
include ('../include.php');
checkAuthorised('field_job');
?>
<script type="text/javascript">
function actionDate(sel) {
	var action = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "field_job_ajax_all.php?from=field_jobs_wt&action=actiondate&id="+arr[1]+'&value='+action,
		dataType: "html",   //expect html to be returned
		success: function(response){
            alert('Date Sent Success.');
			location.reload();
		}
	});
}
</script>
</head>
<body>
<?php include_once ('../navigation.php');

?>

<div class="container">
	<div class="row">
		<div class="col-md-12">

        <h1 class="single-pad-bottom">Work Ticket Dashboard</h1>

		<form name="form_wt" method="post" action="" class="form-inline" role="form">

			<div id="no-more-tables">
				<?php
				$jobid = $_GET['jobid'];
                $type = $_GET['type'];
				$query_check_credentials = "SELECT fj.*, fwt.*, cl.clientid, cl.client_name FROM clients cl, field_work_ticket fwt, field_jobs fj WHERE fwt.jobid = fj.jobid AND fj.clientid = cl.clientid AND fwt.jobid = '$jobid' AND fwt.attach_invoice = 0 AND fwt.status='$type' ORDER BY workticketid DESC";

				$result = mysqli_query($dbc, $query_check_credentials);

				$num_rows = mysqli_num_rows($result);
				if($num_rows > 0) {
				echo "<table class='table table-bordered'>";
				echo "<tr class='hidden-xs hidden-sm'>
						<th>WT#</th>
                        <th>Date</th>
						<th>Job#</th>
						<th>Customer</th>
                        <th>Date Sent</th>
                        <th>Date Approved by Customer</th>
                        <th>PDF</th>
                        <th>Function</th>
						</tr>";
				} else {
					echo "<h2>No Record Found.</h2>";
				}
				$user_loop = '';
				$submit_inc = 0;
				while($row = mysqli_fetch_array( $result ))
				{
					$jobid = $row['jobid'];
					$workticketid = $row['workticketid'];
					echo '<input type="hidden" name="workticketid[]" value="'.$workticketid.'" />';

					echo '<tr>';
					echo '<td data-title="WT#">' . $row['workticketid'] . '</td>';
                    echo '<td data-title="WT#">' . $row['wt_date'] . '</td>';
					echo '<td data-title="Job#"><a href=\'add_field_job.php?jobid='.$row['jobid'].'\'>' . $row['job_number'] . '</a></td>';

					echo '<td data-title="Customer">' . $row['client_name']. '</td>';
                    ?>
                    <td data-title="Date Sent">
                    <input name="date_sent" onchange="actionDate(this)" id="senddate_<?php echo $workticketid; ?>" type="text" style="width: 90px;" class="datepicker" value="<?php echo $row['date_sent']; ?>">

                    <td data-title="Date Received">
                    <?php
                    if($row['date_received'] == '0000-00-00' || $row['date_received'] == '' ) {
                        echo 'Not yet received';
                    } else {
                        echo $row['date_received'];
                    }
                    ?>

                    </td>

                    <?php
                    $name_of_file = 'download/field_work_ticket_'.$row['workticketid'].'.pdf';
                    echo '<td data-title="PDF"><a href='.$name_of_file.' target="_blank">View <img src="'.WEBSITE_URL.'/img/pdf.png" title="PDF"> </a>';
                    if($row['status'] == 'Pending') {
                        //echo ' | <a href=\'field_jobs.php?wtsend=pdf&workticketid='.$row['workticketid'].'&contactid='.$row['contactid'].'\'>Send</a></td>';
                    }

                    $fsid = $row['fsid'];
                    echo '<td data-title="Function">';

                    if($row['status'] == 'Pending') {
                        echo '<a href=\'add_field_work_ticket.php?fsid='.$fsid.'&jobid='.$jobid.'&workticketid='.$row['workticketid'].'\'>Edit</a> | ';
                    }

                    if($row['status'] == 'Pending') {
                        echo 'Pending | <a href=\'field_jobs.php?workticketid='.$row['workticketid'].'&status=Approve\'>Cust. Approve</a>';
                    } else {
                        echo 'Cust. Approved | <a href=\'field_jobs.php?workticketid='.$row['workticketid'].'&status=Revert\'>Revert</a>';
                    }
                    echo '</td>';

					echo "</tr>";
					$submit_inc++;
				}

				echo '</table></div>';

				?>
		</form>

		</div>
	</div>
</div>

<?php include ('../footer.php'); ?>