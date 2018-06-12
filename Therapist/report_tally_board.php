<?php
/*
Client Listing
*/
include ('../include.php');
include_once('../tcpdf/tcpdf.php');
include_once('report_therapist_function.php');
error_reporting(0);

if (isset($_POST['printpdf'])) {
    $starttimepdf = $_POST['starttimepdf'];
    $endtimepdf = $_POST['endtimepdf'];
    $therapistpdf = $_POST['therapistpdf'];

    DEFINE('START_DATE', $starttimepdf);
    DEFINE('END_DATE', $endtimepdf);
    DEFINE('REPORT_LOGO', get_config($dbc, 'report_logo'));
    DEFINE('REPORT_HEADER', html_entity_decode(get_config($dbc, 'report_header')));
    DEFINE('REPORT_FOOTER', html_entity_decode(get_config($dbc, 'report_footer')));

	class MYPDF extends TCPDF {

		public function Header() {
			//$image_file = WEBSITE_URL.'/img/Clinic-Ace-Logo-Final-250px.png';
            if(REPORT_LOGO != '') {
                $image_file = 'Download/'.REPORT_LOGO;
                $this->Image($image_file, 10, 10, 80, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
            }
            $this->setCellHeightRatio(0.7);
            $this->SetFont('helvetica', '', 9);
            $footer_text = '<p style="text-align:right;">'.REPORT_HEADER.'</p>';
            $this->writeHTMLCell(0, 0, 0 , 5, $footer_text, 0, 0, false, "R", true);

            $this->SetFont('helvetica', '', 13);
            $footer_text = 'Assessment Tally Board From <b>'.START_DATE.'</b> To <b>'.END_DATE.'</b>';
            $this->writeHTMLCell(0, 0, 0 , 35, $footer_text, 0, 0, false, "C", true);
		}

		// Page footer
		public function Footer() {
            $this->SetY(-24);
            $this->SetFont('helvetica', 'I', 9);
            $footer_text = '<span style="text-align:left;">'.REPORT_FOOTER.'</span>';
            $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "L", true);

			// Position at 15 mm from bottom
			$this->SetY(-15);
            $this->SetFont('helvetica', 'I', 9);
			$footer_text = '<span style="text-align:right;">Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages().' printed on '.date('Y-m-d H:i:s').'</span>';
			$this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "R", true);
    	}
	}

	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, false, false);
	$pdf->setFooterData(array(0,64,0), array(0,64,128));

	$pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	$pdf->AddPage('L', 'LETTER');
    $pdf->SetFont('helvetica', '', 9);

    $html .= '<br><br>' . report_goals($dbc, $starttimepdf, $endtimepdf, 'padding:3px; border:1px solid black;', '', '', $therapistpdf, 'print');

    $today_date = date('Y-m-d');
	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output('Download/assessment_tally_board_on_'.$today_date.'.pdf', 'F');
    ?>

	<script type="text/javascript" language="Javascript">
	window.open('Download/assessment_tally_board_on_<?php echo $today_date;?>.pdf', 'fullscreen=yes');
	</script>
    <?php
    $starttime = $starttimepdf;
    $endtime = $endtimepdf;
    $therapist = $therapistpdf;
    } ?>

<script type="text/javascript">

</script>
</head>
<body>
<?php include_once ('../navigation.php');
?>

<div class="container triple-pad-bottom">
    <div class="row">
        <div class="col-md-12">

        <?php echo reports_therapist($dbc);  ?>

        <div class="notice double-gap-bottom popover-examples">
        <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
        <div class="col-sm-11"><span class="notice-name">NOTE:</span>
        Displays your total appointments for the selected date range.</div>
        <div class="clearfix"></div>
        </div>

        <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

        <input type="hidden" name="report_type" value="<?php echo $_GET['type']; ?>">
        <input type="hidden" name="category" value="<?php echo $_GET['category']; ?>">

            <?php
            $therapist = $_SESSION['contactid'];
            if (isset($_POST['search_email_submit'])) {
                $starttime = $_POST['starttime'];
                $endtime = $_POST['endtime'];
                //$therapist = $_POST['therapist'];
            }
            if($starttime == 0000-00-00) {
                $starttime = date('Y-m-01');
            }
            if($endtime == 0000-00-00) {
                $endtime = date('Y-m-t');
            }
            ?>
            <br>

            <center>
            <div class="form-group">
					From:
                    <input name="starttime" type="text" class="datepicker" value="<?php echo $starttime; ?>">
					Until:
                    <input name="endtime" type="text" class="datepicker" value="<?php echo $endtime; ?>">

				<button type="submit" name="search_email_submit" value="Search" class="btn brand-btn mobile-block">Submit</button>
            </div></center>

            <input type="hidden" name="starttimepdf" value="<?php echo $starttime; ?>">
            <input type="hidden" name="endtimepdf" value="<?php echo $endtime; ?>">
            <input type="hidden" name="therapistpdf" value="<?php echo $therapist; ?>">

            <button type="submit" name="printpdf" value="Print Report" class="btn brand-btn pull-right">Print Report</button>
            <br><br>

            <?php
                echo report_goals($dbc, $starttime, $endtime, '', '', '', $therapist, 'screen');
            ?>

        </form>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>

<?php
function getWeekDates($date, $start_date, $end_date)
{
    $week =  date('W', strtotime($date));
    $year =  date('Y', strtotime($date));
    $from = date("Y-m-d", strtotime("{$year}-W{$week}+1")); //Returns the date of monday in week
    if($from < $start_date) $from = $start_date;
    $to = date("Y-m-d", strtotime("{$year}-W{$week}-6"));   //Returns the date of sunday in week
    if($to > $end_date) $to = $end_date;
    return $from." : ".$to;//Output : Start Date-->2012-09-03 End Date-->2012-09-09
}

function report_goals($dbc, $starttime, $endtime, $table_style, $table_row_style, $grand_total_style, $therapist, $output) {

    if ( $output == 'screen' ) {
		//print to screen

		$report_data = '<br><table border="1px" class="table table-bordered" style="'.$table_style.'">
		<tr style="'.$table_row_style.'" nobr="true">
		<th>Staff</th>
		<th>Arrived/Completed/<br />Invoiced/Paid</th>
		<th>Cancelled</th>
		<th>Rescheduled</th>
		<th>Late/Cancellation/<br />No-Show</th>
		<th>Booked Confirmed/<br />Unconfirmed</th>
		<th><b>Total Assessments</b></th>
		<th><b>Total Treatments</b></th>
		</tr>';

	} else {
		//print PDF

		$report_data = '<table border="1px" class="table table-bordered" style="'.$table_style.'">
		<tr style="'.$table_row_style.'" nobr="true">
		<th>Staff</th>
		<th>Arrived/Completed/<br />Invoiced/Paid</th>
		<th>Cancelled</th>
		<th>Rescheduled</th>
		<th>Late/Cancellation/<br />No-Show</th>
		<th>Booked Confirmed/<br />Unconfirmed</th>
		<th><b>Total Assessments</b></th>
		<th><b>Total Treatments</b></th>
        </tr>';
	}

    if($therapist == '') {
        $result = mysqli_query($dbc, "SELECT contactid, first_name, last_name, scheduled_hours FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND contactid != '6896' AND contactid != '6909' AND (category_contact = 'Physical Therapist' OR category_contact = 'Massage Therapist' OR category_contact = 'Osteopathic Therapist')");
    } else if($therapist == 'Physical Therapist') {
        $result = mysqli_query($dbc, "SELECT contactid, first_name, last_name, scheduled_hours FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND contactid != '6896' AND contactid != '6909' AND category_contact = 'Physical Therapist'");
    } else if($therapist == 'Massage Therapist') {
        $result = mysqli_query($dbc, "SELECT contactid, first_name, last_name, scheduled_hours FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND contactid != '6896' AND contactid != '6909' AND category_contact = 'Massage Therapist'");
    } else if($therapist == 'Osteopathic Therapist') {
        $result = mysqli_query($dbc, "SELECT contactid, first_name, last_name, scheduled_hours FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND contactid != '6896' AND contactid != '6909' AND category_contact = 'Osteopathic Therapist'");
    } else {
        $result = mysqli_query($dbc, "SELECT contactid, first_name, last_name, scheduled_hours FROM contacts WHERE contactid='$therapist'");
    }

    $final_total_appoint = 0;
    $total_assess = 0;
    $total_treated = 0;
    $total_appoint = 0;
    $total_arrived = 0;
    $total_cancelled = 0;
    $total_absent = 0;
    $total_pending = 0;
    $total_rescheduled = 0;

    $total_block_booking = 0;
    while($row = mysqli_fetch_array($result)) {
        $therapistsid = $row['contactid'];

        $report_arrived = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(bookingid) AS total_arrived FROM booking WHERE therapistsid = '$therapistsid' AND type != 'I' AND type != 'E' AND type != 'P' AND type != 'Q' AND type != 'R' AND type != '' AND (follow_up_call_status='Arrived' OR follow_up_call_status='Completed' OR follow_up_call_status='Invoiced' OR follow_up_call_status='Paid') AND ((str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) >= '".$starttime."' AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) <= '".$endtime."')"));
        $report_cancelled = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(bookingid) AS total_cancelled FROM booking WHERE therapistsid = '$therapistsid' AND type != 'I' AND type != 'E' AND type != 'P' AND type != 'Q' AND type != 'R' AND type != ''  AND follow_up_call_status='Cancelled' AND ((str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) >= '".$starttime."' AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) <= '".$endtime."')"));
        $report_rescheduled = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(bookingid) AS total_rescheduled FROM booking WHERE therapistsid = '$therapistsid' AND type != 'I' AND type != 'E' AND type != 'P' AND type != 'Q' AND type != 'R' AND type != ''  AND follow_up_call_status='Rescheduled' AND ((str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) >= '".$starttime."' AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) <= '".$endtime."')"));
        $report_absent = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(bookingid) AS total_absent FROM booking WHERE therapistsid = '$therapistsid' AND type != 'I' AND type != 'E' AND type != 'P' AND type != 'Q' AND type != 'R' AND type != '' AND follow_up_call_status='Late Cancellation / No-Show' AND ((str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) >= '".$starttime."' AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) <= '".$endtime."')"));
        $report_pending = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(bookingid) AS total_pending FROM booking WHERE therapistsid = '$therapistsid' AND type != 'I' AND type != 'E' AND type != 'P' AND type != 'Q' AND type != 'R' AND type != '' AND (follow_up_call_status='Booked Confirmed' OR follow_up_call_status='Booked Unconfirmed') AND ((str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) >= '".$starttime."' AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) <= '".$endtime."')"));
        // Need to verify this only selects assessed
		$report_assessed = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(bookingid) AS total_assessed FROM booking WHERE therapistsid = '$therapistsid' AND type IN('A','C','F','H','N','U') AND (follow_up_call_status='Arrived' OR follow_up_call_status='Completed' OR follow_up_call_status='Invoiced' OR follow_up_call_status='Paid') AND ((str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) >= '".$starttime."' AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) <= '".$endtime."')"));
		// Need to verify this only selects treated
        $report_treated = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(bookingid) AS total_treated FROM booking WHERE therapistsid = '$therapistsid' AND type NOT IN('A','C','F','H','N','U') AND (follow_up_call_status='Arrived' OR follow_up_call_status='Completed' OR follow_up_call_status='Invoiced' OR follow_up_call_status='Paid') AND ((str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) >= '".$starttime."' AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) <= '".$endtime."')"));

        $report_data .= '<tr nobr="true">';
        $report_data .= '<td>'.decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</td>';
        $report_data .= '<td>'.$report_arrived['total_arrived'].'</td>';
        $report_data .= '<td>'.$report_cancelled['total_cancelled'].'</td>';
        $report_data .= '<td>'.$report_rescheduled['total_rescheduled'].'</td>';
        $report_data .= '<td>'.$report_absent['total_absent'].'</td>';
        $report_data .= '<td>'.$report_pending['total_pending'].'</td>';
        $report_data .= '<td><b>'.$report_assessed['total_assessed'].'</b></td>';
        $report_data .= '<td><b>'.$report_treated['total_treated'].'</b></td>';
        $report_data .= '</tr>';

        $total_appoint = ($report_arrived['total_arrived']+$report_cancelled['total_cancelled']+$report_rescheduled['total_rescheduled']+$report_absent['total_absent']+$report_pending['total_pending']);

        $final_total_appoint += $total_appoint;
        $total_assess += $report_assessed['total_assessed'];
        $total_treated += $report_treated['total_treated'];
        $total_arrived += $report_arrived['total_arrived'];
        $total_cancelled += $report_cancelled['total_cancelled'];
        $total_absent += $report_absent['total_absent'];
        $total_pending += $report_pending['total_pending'];
        $total_rescheduled += $report_rescheduled['total_rescheduled'];
    }

    $report_data .= '</table>';

    return $report_data;
}

?>