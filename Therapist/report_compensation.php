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
    $stat_startpdf = $_POST['stat_startpdf'];
    $stat_endpdf = $_POST['stat_endpdf'];
    $total_stat_holidaypdf = $_POST['total_stat_holidaypdf'];

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
            $footer_text = 'Compensation From <b>'.START_DATE.'</b> To <b>'.END_DATE.'</b>';
            $this->writeHTMLCell(0, 0, 0 , 35, $footer_text, 0, 0, false, "R", true);
		}

		// Page footer
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

	$pdf->SetMargins(PDF_MARGIN_LEFT, 50, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	$pdf->AddPage('L', 'LETTER');
    $pdf->SetFont('helvetica', '', 9);

    $html .= report_compensation($dbc, $starttimepdf, $endtimepdf, 'padding:3px; border:1px solid black;', '', '', $therapistpdf, $stat_startpdf, $stat_endpdf, $total_stat_holidaypdf);

    $today_date = date('Y-m-d');
	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output('Download/compensation_'.$today_date.'.pdf', 'F');
    ?>

	<script type="text/javascript" language="Javascript">
	window.open('Download/compensation_<?php echo $today_date;?>.pdf', 'fullscreen=yes');
	</script>
    <?php
    $starttime = $starttimepdf;
    $endtime = $endtimepdf;
    $therapist = $therapistpdf;

    $stat_start = $stat_startpdf;
    $stat_end = $stat_endpdf;
    $total_stat_holiday = $total_stat_holidaypdf;

}
if (isset($_POST['printapptpdf'])) {
    $starttimepdf = $_POST['starttimepdf'];
    $endtimepdf = $_POST['endtimepdf'];
    $therapistpdf = $_POST['therapistpdf'];
    $stat_startpdf = $_POST['stat_startpdf'];
    $stat_endpdf = $_POST['stat_endpdf'];
    $total_stat_holidaypdf = $_POST['total_stat_holidaypdf'];

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
            $footer_text = 'Compensation From <b>'.START_DATE.'</b> To <b>'.END_DATE.'</b>';
            $this->writeHTMLCell(0, 0, 0 , 35, $footer_text, 0, 0, false, "R", true);
		}

		// Page footer
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

	$pdf->SetMargins(PDF_MARGIN_LEFT, 50, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	$pdf->AddPage('L', 'LETTER');
    $pdf->SetFont('helvetica', '', 9);

    $html .= report_appt_compensation($dbc, $starttimepdf, $endtimepdf, 'padding:3px; border:1px solid black;', '', '', $therapistpdf, $stat_startpdf, $stat_endpdf, $total_stat_holidaypdf);

    $today_date = date('Y-m-d');
	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output('Download/compensation_appt_'.$today_date.'.pdf', 'F');
    ?>

	<script type="text/javascript" language="Javascript">
	window.open('Download/compensation_appt_<?php echo $today_date;?>.pdf', 'fullscreen=yes');
	</script>
    <?php
    $starttime = $starttimepdf;
    $endtime = $endtimepdf;
    $therapist = $therapistpdf;

    $stat_start = $stat_startpdf;
    $stat_end = $stat_endpdf;
    $total_stat_holiday = $total_stat_holidaypdf;

}

?>

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
        Displays the history of compensation according to selected dates.</div>
        <div class="clearfix"></div>
        </div>

        <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
            <input type="hidden" name="report_type" value="<?php echo $_GET['type']; ?>">
            <input type="hidden" name="category" value="<?php echo $_GET['category']; ?>">

            <?php
            //$contactid = '';
            $therapist = $_SESSION['contactid'];
            if (isset($_POST['search_email_submit'])) {
                $starttime = $_POST['starttime'];
                $endtime = $_POST['endtime'];
            }

            if($starttime == 0000-00-00) {
                $starttime = date('Y-m-01');
            }

            if($endtime == 0000-00-00) {
                $endtime = date('Y-m-t');
            }

            ?>
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
                //echo '<a href="report_compensation.php?compensation=printpdf&starttime='.$starttime.'&endtime='.$endtime.'" class="btn brand-btn pull-right">Print Report</a></h4><br>';

                $contractDateBegin = strtotime($starttime);
                $contractDateEnd = strtotime($endtime);

                //$stat_start = '2016-08-02';
                //$stat_end = '2016-08-31';
                $stat_start = '0000-00-00';
                $stat_end = '0000-00-00';

                $total_stat_holiday = 0;
				$stat_holidays = [];
				foreach(mysqli_fetch_all(mysqli_query($dbc, "SELECT `date` FROM `holidays` WHERE `paid`=1 AND `deleted`=0")) as $stat_day) {
					$stat_holidays[] = $stat_day[0];
				}
				//$stat_holiday = explode(',',get_config($dbc, 'stat_holiday'));
                foreach($stat_holiday as $stat_day){
                    $stat_date = strtotime($stat_day);
                    if (($stat_date >= $contractDateBegin) && ($stat_date <= $contractDateEnd)) {
                        //$stat_end = date('Y-m-d', strtotime('-1 day', strtotime($stat_day)));
                        //$stat_start = date('Y-m-d', strtotime('-63 day', strtotime($stat_day)));

                        $total_stat_holiday++;
                    }
                }

                echo report_compensation($dbc, $starttime, $endtime, '', '', '', $therapist, $stat_start, $stat_end, $total_stat_holiday);
            ?>

            <input type="hidden" name="stat_startpdf" value="<?php echo $stat_start; ?>">
            <input type="hidden" name="stat_endpdf" value="<?php echo $stat_end; ?>">
            <input type="hidden" name="total_stat_holidaypdf" value="<?php echo $total_stat_holiday; ?>">

        </form>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>

<?php
function report_compensation($dbc, $starttime, $endtime, $table_style, $table_row_style, $grand_total_style, $therapist, $stat_start, $stat_end, $total_stat_holiday) {
    $report_data = '';

    $result = mysqli_query($dbc, "SELECT contactid, scheduled_hours, category_contact FROM contacts WHERE contactid='$therapist'");

    $all_booking = 0;
    $grand_total = 0;
    $grand_stat_total = 0;
    $avg_per_day_stat = 0;
    while($row = mysqli_fetch_array($result)) {
        $therapistid = $row['contactid'];
        $category_contact = $row['category_contact'];

        $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM compensation WHERE contactid='$therapistid' AND '$starttime' BETWEEN start_date AND end_date"));
        $base_pay = explode('*#*',$get_contact['base_pay']);

        include_once ('report_compensation_services.php');

        //include_once ('report_compensation_metrix.php');
        include_once ('report_compensation_preformance_logic.php');
        //include_once ('report_compensation_metrix2.php');

        //$report_fee = $total_base_fee;

        include_once ('report_compensation_inventory.php');

        if($total_stat_holiday != 0) {
            include_once ('report_compensation_stat_holiday.php');
        } else {
            $avg_per_day_stat = '0.00';
        }

        include_once ('report_compensation_summary.php');

        //include_once ('report_compensation_preformance.php');
    }

    return $report_data;
}

function report_appt_compensation($dbc, $starttime, $endtime, $table_style, $table_row_style, $grand_total_style, $therapist, $stat_start, $stat_end, $total_stat_holiday) {
    $report_data = '';

    if($therapist == '') {
        $result = mysqli_query($dbc, "SELECT contactid, scheduled_hours, category_contact FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND (category_contact = 'Physical Therapist' OR category_contact = 'Massage Therapist' OR category_contact = 'Osteopathic Therapist') AND deleted=0");
    } else {
        $result = mysqli_query($dbc, "SELECT contactid, scheduled_hours, category_contact FROM contacts WHERE contactid='$therapist'");
    }

    $all_booking = 0;
    $grand_total = 0;
    $grand_stat_total = 0;
    $avg_per_day_stat = 0;
    while($row = mysqli_fetch_array($result)) {
        $therapistid = $row['contactid'];
        $category_contact = $row['category_contact'];

        $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM compensation WHERE contactid='$therapistid' AND '$starttime' BETWEEN start_date AND end_date"));
        $base_pay = explode('*#*',$get_contact['base_pay']);

        include_once ('report_compensation_services_appt.php');

        //include_once ('report_compensation_metrix.php');
        include_once ('report_compensation_preformance_logic.php');
        //include_once ('report_compensation_metrix2.php');

        //$report_fee = $total_base_fee;

        include_once ('report_compensation_inventory.php');

        if($total_stat_holiday != 0) {
            include_once ('report_compensation_stat_holiday.php');
        } else {
            $avg_per_day_stat = '0.00';
        }

        include_once ('report_compensation_summary.php');

        //include_once ('report_compensation_preformance.php');
    }

    return $report_data;
}

function combineStringArrayWithDuplicates ($keys, $values) {
    $total_array = sizeof($keys);
    $iter = 0;
    $key_old = 0;
    $fee = 0;
    $m = 0;
    foreach ($keys as $key) {
        if($iter == 0) {
            $fee += $values[$iter];
            $key_old = $key;

        } else if($key != $key_old && $iter != 0) {
            $combined[$key_old.':'.$m] = $fee;
            $m = 0;
            $fee = 0;
            $key_old = $key;
            $fee += $values[$iter];
        } else {
            $fee += $values[$iter];
        }
        $m++;
        $iter++;
    }
    if($iter == $total_array) {
        $combined[$key_old.':'.$m] = $fee;
    }
    return $combined;
}
?>