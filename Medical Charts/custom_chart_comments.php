<?php include_once('../include.php');

if(isset($_POST['submit_comment'])) {
	$chart_name = $_POST['chart_name'];
	$clientid = $_POST['clientid'];
	$no_client = $_POST['no_client'];
	$year = $_POST['year'];
	$month = $_POST['month'];
	$day = $_POST['day'];
	$headingid = $_POST['headingid'];
	$fieldid = $_POST['fieldid'];
	$staffid = $_SESSION['contactid'];
	$time_stamp = date('Y-m-d H:i:s');
	$comment = htmlentities($_POST['chart_comment']);

	mysqli_query($dbc, "INSERT INTO `custom_charts_comments` (`chart_name`, `clientid`, `headingid`, `fieldid`, `year`, `month`, `day`, `staffid`, `comment`, `time_stamp`, `no_client`) VALUES ('$chart_name', '$clientid', '$headingid', '$fieldid', '$year', '$month', '$day', '$staffid', '$comment', '$time_stamp', '$no_client')");

	echo '<script type="text/javascript"> window.parent.updateCommentCount(\''.$day.'\',\''.$headingid.'\',\''.$fieldid.'\'); </script>';
}

$chart_name = $_GET['chart_name'];
$clientid = $_GET['clientid'];
$no_client = $_GET['no_client'];
if($no_client == 1) {
	$clientid = 0;
	$client_query = " AND `no_client` = 1 AND `clientid` = 0";
} else {
	$client_query = " AND `no_client` = 0 AND `clientid` = '$clientid'";
}
$year = $_GET['year'];
$month = $_GET['month'];
$day = $_GET['day'];
$headingid = $_GET['headingid'];
$fieldid = $_GET['fieldid'];
?>

<form class="form-horizontal" action="" method="POST" enctype="multipart/form-data">
	<h2>View/Add Comments</h2>
	<input type="hidden" name="chart_name" value="<?= $chart_name ?>">
	<input type="hidden" name="clientid" value="<?= $clientid ?>">
	<input type="hidden" name="no_client" value="<?= $no_client ?>">
	<input type="hidden" name="year" value="<?= $year ?>">
	<input type="hidden" name="month" value="<?= $month ?>">
	<input type="hidden" name="day" value="<?= $day ?>">
	<input type="hidden" name="headingid" value="<?= $headingid ?>">
	<input type="hidden" name="fieldid" value="<?= $fieldid ?>">
	<?php $comments = mysqli_query($dbc, "SELECT * FROM `custom_charts_comments` WHERE `chart_name` = '$chart_name' AND `headingid` = '$headingid' AND `fieldid` = '$fieldid' AND `year` = '$year' AND `month` = '$month' AND `day` = '$day' AND `deleted` = 0 $client_query ORDER BY `customchartcommid` DESC");
	while($row = mysqli_fetch_assoc($comments)) {
		echo '<div class="note_block">';
		echo profile_id($dbc, $row['staffid']);
		echo '<div class="pull-right" style="width: calc(100% - 3.5em);">'.html_entity_decode($row['comment']);
		echo "<br><em>Added by ".get_contact($dbc, $row['staffid'])." at ".$row['time_stamp'].'</em>';
		echo '</div><div class="clearfix"></div><hr></div>';
	}?>
	<div class="form-group">
		<label class="col-sm-3 control-label" style="text-align: left;">Add Comment:</label>
		<div class="col-sm-9">
			<textarea name="chart_comment" class="noMceEditor form-control"></textarea>
		</div>
	</div>
	<div class="form-group">
		<button type="submit" name="submit_comment" value="submit_comment" class="btn brand-btn pull-right">Submit</button>
	</div>
</form>