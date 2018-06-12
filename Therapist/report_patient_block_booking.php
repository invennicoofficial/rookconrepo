<?php
/*
Client Listing
*/
include ('../include.php');
include_once('../tcpdf/tcpdf.php');
include_once('report_therapist_function.php');
error_reporting(0);

if (isset($_POST['printpdf'])) {
    $patientpdf = $_POST['patientpdf'];

    DEFINE('PATIENT', get_contact($dbc, $patientpdf));
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
            //$this->Image($image_file, 0, 10, 60, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
            $footer_text = 'Patient Future Appointments <b>'.PATIENT.'</b>';
            $this->writeHTMLCell(0, 0, 0 , 35, $footer_text, 0, 0, false, "R", true);
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

	$pdf->SetMargins(PDF_MARGIN_LEFT, 50, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	$pdf->AddPage('L', 'LETTER');
    $pdf->SetFont('helvetica', '', 9);

    $html .= report_bb($dbc, $patientpdf, 'padding:3px; border:1px solid black;', '', '');

    $today_date = date('Y-m-d');
	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output('Download/patient_'.$patientpdf.'.pdf', 'F');

    $from = $_POST['from'];
    if($from == 'calendar') {
        echo '<script type="text/javascript"> window.open("Download/patient_'.$patientpdf.'.pdf", "fullscreen=yes"); window.top.close(); window.opener.location.reload(); </script>';
    } else {
	    echo '<script type="text/javascript" language="Javascript">
	    window.open("Download/patient_'.$patientpdf.'.pdf", "fullscreen=yes");
	    </script>';
    }
    $patient = $patientpdf;
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
        Displays a patient's future block booked appointments.</div>
        <div class="clearfix"></div>
        </div>

        <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
            <input type="hidden" name="report_type" value="<?php echo $_GET['type']; ?>">
            <input type="hidden" name="category" value="<?php echo $_GET['category']; ?>">

            <?php
            if (isset($_POST['search_email_submit'])) {
                $patient = $_POST['patient'];
            } else if(!empty($_GET['patientid'])) {
                $patient = $_GET['patientid'];
                echo '<input type="hidden" name="from" value="calendar">';
            } else {
                $patient = '';
            }
            ?>

            <div class="form-group">
                <label for="site_name" class="col-sm-4 control-label">Patient:</label>
                <div class="col-sm-8" style="width:auto">
                    <select data-placeholder="Choose a Patient..." name="patient" class="chosen-select-deselect form-control">
                        <option value=""></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(b.patientid), c.first_name, c.last_name FROM booking b,contacts c WHERE b.deleted=0 AND b.patientid = c.contactid AND (str_to_date(substr(b.appoint_date,1,10),'%Y-%m-%d')) >= DATE(NOW())");
                        while($row = mysqli_fetch_array($query)) {
                            if ($patient == $row['patientid']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['patientid']."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';
                        }
                        ?>
                    </select>
                </div>

            <button type="submit" name="search_email_submit" value="Search" class="btn brand-btn mobile-block">Submit</button>
            </div>

            <input type="hidden" name="patientpdf" value="<?php echo $patient; ?>">

            <button type="submit" name="printpdf" value="Print Report" class="btn brand-btn pull-right">Print Report</button>
            <br><br>

            <?php
            if($patient != '') {
                echo report_bb($dbc, $patient, '', '', '');
            }
            ?>

        

        </form>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>

<?php
function report_bb($dbc, $patient, $table_style, $table_row_style, $grand_total_style) {

    $report_data .= '<h3>' . get_contact($dbc, $patient) . '</h3>';

    $report_data .= '<table border="1px" class="table table-bordered" style="'.$table_style.'">';
    $report_data .= '<tr style="'.$table_row_style.'">
    <th>Appointment Date & Day</th>
    <th>Therapist</th>
    <th>Injury</th>
    <th>Status</th>
    </tr>';

    $query_check_credentials = "SELECT appoint_date, end_appoint_date, bookingid, injuryid, follow_up_call_status, therapistsid FROM booking WHERE deleted=0 AND patientid = '$patient' AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) >= DATE(NOW()) ORDER BY appoint_date";

    $result = mysqli_query($dbc, $query_check_credentials);
    while($row = mysqli_fetch_array( $result ))
    {
        $appoint_date = explode(' ', $row['appoint_date']);
        $report_data .= '<tr nobr="true">';
        $report_data .= '<td>'.$row['appoint_date'].' : '.date("l", strtotime($appoint_date[0])).'</td>';
        $report_data .= '<td>'.get_contact($dbc, $row['therapistsid']).'</td>';
        $report_data .=  '<td>' . get_all_from_injury($dbc, $row['injuryid'], 'injury_name').' : '.get_all_from_injury($dbc, $row['injuryid'], 'injury_type') . '</td>';
        $report_data .=  '<td>' . $row['follow_up_call_status'] . '</td>';
        $report_data .= '</tr>';
    }

    $report_data .= '</table>';

    return $report_data;
}

?>