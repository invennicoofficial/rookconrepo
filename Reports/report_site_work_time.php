<?php
/*
Client Listing
*/
include ('../include.php');
checkAuthorised('report');
include_once('../tcpdf/tcpdf.php');
error_reporting(0); ?>

</head>
<body>
<?php include_once ('../navigation.php');
?>

<div class="container triple-pad-bottom">
    <div class="row">
        <div class="col-md-12">

		<?php $search_status = (!empty($_GET['wo_type']) ? filter_var($_GET['wo_type'],FILTER_SANITIZE_STRING) : 'Approved');
		$search_from = '';
		$search_until = '';
		
		if (isset($_POST['search_from'])) {
			$search_from = $_POST['search_from'];
		}
		if (isset($_POST['search_until'])) {
			$search_until = $_POST['search_until'];
		} ?>
        <?php echo reports_tiles($dbc);  ?>
		<h2><?= ($search_status == 'Approved' ? 'Active ' : (($search_status == 'Archived' ? 'Closed ' : 'Pending '))) ?>Site Work Order Time on Site</h2>
        <a href='report_site_work_time.php?type=operations&wo_type=Pending'><button type="button" class="btn brand-btn mobile-block <?= ($search_status == 'Pending' ? 'active_tab' : '') ?>" >Pending</button></a>&nbsp;&nbsp;
        <a href='report_site_work_time.php?type=operations&wo_type=Approved'><button type="button" class="btn brand-btn mobile-block <?= ($search_status == 'Approved' ? 'active_tab' : '') ?>" >Active</button></a>&nbsp;&nbsp;
        <a href='report_site_work_time.php?type=operations&wo_type=Archived'><button type="button" class="btn brand-btn mobile-block <?= ($search_status == 'Archived' ? 'active_tab' : '') ?>" >Closed</button></a>&nbsp;&nbsp;

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

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>

<?php
function work_orders($dbc, $status = 'Active', $from_date = '', $until_date = '', $table_style = '', $table_row_style = '', $grand_total_style = '') {
    $report_data = '';

    $sql = "SELECT * FROM `site_work_orders` swo WHERE swo.`status`='$status'";
    if($from_date != '') {
        $sql .= " AND swo.`work_end_date` >= '$from_date'";
    }
    if($until_date != '') {
        $sql .= " AND swo.`work_start_date` <= '$until_date'";
    }
	$result = mysqli_query($dbc, $sql.' ORDER BY swo.`site_location`, swo.`workorderid` DESC');
	
	if(mysqli_num_rows($result) == 0) {
		return "<h3>No Work Orders Found</h3>";
	}

    $report_data .= '<table border="1px" class="table table-bordered" width="100%" style="'.$table_style.'">';
    $report_data .= '<tr style="'.$table_row_style.'">';
    $report_data .= '<th>Work Order #</th>
			<th>Staff on Site</th>
			<th>Estimated Time</th>
			<th>Actual Time</th>';
    $report_data .=  "</tr>";

    while($time = mysqli_fetch_array( $result ))
    {
		$staff = [];
		$staff_names = [];
		$est = [];
		$actual = [];
		$staff_assigned = explode(',', $time['staff_crew']);
		$est_hrs = explode(',', $time['staff_estimate_hours']);
		$est_days = explode(',', $time['staff_estimate_days']);
		$summary = explode('#*#', $time['summary']);
		
		foreach($staff_assigned as $j => $staff_id) {
			$staff[] = $staff_id;
			$staff_names[] = get_contact($dbc, $staff_id);
			$est[] = $est_hrs[$j].' hours '.(!empty($est_days[$j]) ? $est_days[$j].' days' : '');
			$actual[] = '0 hours';
		}
		foreach($summary as $summary_line) {
			$summary_line = explode('**#**', $summary_line);
			$staff_line = array_search($summary_line[0], $staff);
			if($staff_line === false) {
				$staff[] = $summary_line[0];
				$staff_names[] = get_contact($dbc, $summary_line[0]);
				$est[] = '0 hours';
				$actual[] = $summary_line[2].' hours';
			} else {
				$actual[$staff_line] = $summary_line[2].' hours';
			}
		}
		
        $report_data .= '<tr nobr="true">
			<td data-title="Work Order #:">'.$time['workorderid'].'</td>
			<td data-title="Staff on Site:">'.implode('<br />',$staff_names).'</td>
			<td data-title="Estimated Time:">'.implode('<br />',$est).'</td>
			<td data-title="Actual Time:">'.implode('<br />',$actual).'</td></tr>';
    }

    $report_data .= '</table>';

    return $report_data;
}
?>