<?php
/*
Client Listing
*/
include ('../include.php');
checkAuthorised('report');
include_once('../tcpdf/tcpdf.php');
error_reporting(0); ?>


		<?php $search_status = (!empty($_GET['wo_type']) ? filter_var($_GET['wo_type'],FILTER_SANITIZE_STRING) : 'Approved');
		$search_from = '';
		$search_until = '';
		
		if (isset($_POST['search_from'])) {
			$search_from = $_POST['search_from'];
		} else {
			$search_from = date('Y-m-01');
		}
		if (isset($_POST['search_until'])) {
			$search_until = $_POST['search_until'];
		} else {
			$search_until = date('Y-m-d');
		} ?>
		<h2>Work Order Time on Site</h2>

        <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
			<center><div class="form-group">
				<div class="form-group col-sm-5">
					<label class="col-sm-4">From:</label>
					<div class="col-sm-8"><input name="search_from" type="text" class="datepicker form-control" value="<?php echo $search_from; ?>"></div>
				</div>
				<div class="form-group col-sm-5">
					<label class="col-sm-4">Until:</label>
					<div class="col-sm-8"><input name="search_until" type="text" class="datepicker form-control" value="<?php echo $search_until; ?>"></div>
				</div>
			<button type="submit" name="search_email_submit" value="Search" class="btn brand-btn mobile-block">Submit</button></div>
			<button type="button" onclick="window.location=''" name="display_all_inventory" value="Display All" class="btn brand-btn mobile-block">Display All</button></center>
        <button type="submit" name="printpdf" value="Print Report" class="btn brand-btn pull-right">Print Report</button>

            <input type="hidden" name="report_status" value="<?php echo $search_status; ?>">
            <input type="hidden" name="report_from" value="<?php echo $search_from; ?>">
            <input type="hidden" name="report_until" value="<?php echo $search_until; ?>">
            <br><br>

            <?php
                echo work_orders($dbc, $search_status, $search_from, $search_until);
            ?>

        </form>

<?php
function work_orders($dbc, $status = 'Active', $from_date = '', $until_date = '', $table_style = '', $table_row_style = '', $grand_total_style = '') {
    $report_data = '';

	$sql = "SELECT `ticketid`, `date_stamp` FROM `ticket_attached` WHERE `deleted`=0 AND `src_table` IN ('Staff','Staff_Tasks') AND `date_stamp` BETWEEN '$from_date' AND '$until_date' AND `ticketid` NOT IN (SELECT `ticketid` FROM `tickets` WHERE `deleted`=1) GROUP BY `ticketid`, `date_stamp` ORDER BY `date_stamp` ASC";
	$result = mysqli_query($dbc, $sql);
	
	if($result->num_rows == 0) {
		return "<h3>No Work Orders Found</h3>";
	}

    $report_data .= '<table border="1px" class="table table-bordered" width="100%" style="'.$table_style.'">';
    $report_data .= '<tr style="'.$table_row_style.'">';
    $report_data .= '<th>Work Order #</th>
			<th>Staff on Site</th>
			<th>Actual Time</th>';
    $report_data .=  "</tr>";

    while($time = mysqli_fetch_array( $result ))
    {
		$hours = mysqli_query($dbc, "SELECT * FROM `ticket_attached` WHERE `ticketid`='{$time['ticketid']}' AND `date_stamp`='{$time['date_stamp']}' AND `deleted`=0 AND `src_table` IN ('Staff','Staff_Tasks')");
		while($hour = $hours->fetch_assoc()) {
			$staff[] = $hour['item_id'];
			$staff_names[] = get_contact($dbc, $hour['item_id']).' - '.$hour['position'];
			$actual[] = $hour['hours_tracked'];
		}
		
        $report_data .= '<tr nobr="true">
			<td data-title="Work Order #:">'.get_ticket_label($dbc, $dbc->query("SELECT * FROM `tickets` WHERE `ticketid`='{$time['ticketid']}'")->fetch_assoc()).' on '.$time['date_stamp'].'</td>
			<td data-title="Staff on Site:">'.implode('<br />',$staff_names).'</td>
			<td data-title="Actual Time:">'.implode('<br />',$actual).'</td></tr>';
    }

    $report_data .= '</table>';

    return $report_data;
}
?>