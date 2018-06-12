<?= (!empty($renamed_accordion) ? '<h3>'.$renamed_accordion.'</h3>' : '<h3>Charts</h3>') ?>
<?php $tab_names = ['front_desk' => 'Front Desk', 'physiotherapy' => 'Physiotherapy', 'massage' => 'Massage Therapy', 'mvc' => 'MVC/MVA', 'wcb' => 'WCB']; ?>
<script type="text/javascript">
$(document).ready(function() {

});
</script>

<h4>Existing Charts</h4>
<?php $ticket_charts = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `patientform_pdf` pdf LEFT JOIN `patientform` pf ON pdf.`patientformid` = pf.`patientformid` WHERE `ticketid` = '$ticketid' AND $ticketid > 0 ORDER BY `today_date` DESC"),MYSQLI_ASSOC);
if($generate_pdf) {
	ob_clean();
}
if(count($ticket_charts) > 0) { ?>
	<table class="table table-bordered">
		<tr>
			<th>Staff</th>
			<th>Client</th>
			<th>Topic (Sub Tab)</th>
			<th>Heading</th>
			<th>Sub Section Heading</th>
			<th>Date</th>
			<th>PDF</th>
		</tr>
		<?php foreach ($ticket_charts as $row) {
		    $patientformid = $row['patientformid'];
		    $pdf_tab = $tab_names[$row['tab']];
		    $pdf_heading = $row['heading'];
		    $pdf_sub_heading = $row['sub_heading'];
		    $fieldlevelriskid = $row['fieldlevelriskid'];
		    $staffid = $row['staffid'];
		    $patientid = $row['patientid'];
		    $today = date('Y-m-d');
		    $pdf_date = $row['today_date'];
		    $pdf_url = $row['pdf_path'];

		    echo "<tr>";
		    echo '<td data-title="Staff">'.get_contact($dbc, $staffid).'</td>';
		    echo '<td data-title="Client">'.get_contact($dbc, $patientid).'</td>';
		    echo '<td data-title="Topic (Sub Tab)">'.$pdf_tab.'</td>';
		    echo '<td data-title="Heading">'.$pdf_heading.'</td>';
		    echo '<td data-title="Sub Heading">'.$pdf_sub_heading.'</td>';
		    echo '<td data-title="Date">'.$pdf_date.'</td>';
		    echo '<td data-title="PDF"><a href="'.WEBSITE_URL.'/Treatment/'.$pdf_url.'" target="_blank"><img src="'.WEBSITE_URL.'/img/pdf.png" width="'.$img_width.'" height="'.$img_height.'" border="0" alt=""></a>';
		    echo "</tr>";
		} ?>
	</table>
<?php } else {
	echo '<h5>No Charts Found.</h5>';
}
if($generate_pdf) {
	$pdf_contents[] = ['', ob_get_contents()];
} ?>

<h4>Add Chart</h4>
<table class="table table-bordered">
	<tr>
		<th>Topic (Sub Tab)</th>
		<th>Heading</th>
		<th>Sub Section Heading</th>
		<th>Function</th>
	</tr>
<?php foreach ($attached_charts as $attached_chart) {
	$attached_chart = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `patientform` WHERE `patientformid` = '$attached_chart'"));
    echo "<tr>";
    echo '<td data-title="Topic (Sub Tab)">'.$tab_names[$attached_chart['tab']].'</td>';
    echo '<td data-title="Heading">'.$attached_chart['heading'].'</td>';
    echo '<td data-title="Sub Heading">'.$attached_chart['sub_heading'].'</td>';
    echo '<td data-title="Function"><a onclick="addTreatmentChart(this); return false;" data-patientformid="'.$attached_chart['patientformid'].'" href="">Add Chart</a></td>';
    echo "</tr>";
} ?>
</table>
<div class="clearfix"></div>