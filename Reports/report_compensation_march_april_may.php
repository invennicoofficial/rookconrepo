<?php
/*
Client Listing
*/
include ('../include.php');
checkAuthorised('report');
include_once('../tcpdf/tcpdf.php');
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
                $image_file = 'download/'.REPORT_LOGO;
                $this->Image($image_file, 10, 10, '', '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
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

    $html .= report_compensation($dbc, $starttimepdf, $endtimepdf, 'padding:3px; border:1px solid black;', '', '', $therapistpdf);

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

        <?php echo reports_tiles($dbc);  ?>

        <br><br>

        <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
            <input type="hidden" name="report_type" value="<?php echo $_GET['type']; ?>">
            <input type="hidden" name="category" value="<?php echo $_GET['category']; ?>">

            <?php
            //$contactid = '';
            if (isset($_POST['search_email_submit'])) {
                $starttime = $_POST['starttime'];
                $endtime = $_POST['endtime'];
                $therapist = $_POST['therapist'];
            }

            if($starttime == 0000-00-00) {
                $starttime = date('Y-m-d');
            }

            if($endtime == 0000-00-00) {
                $endtime = date('Y-m-d');
            }

            ?>
            <div class="form-group">
                <label for="site_name" class="col-sm-4 control-label">From:</label>
                <div class="col-sm-8">
                    <input name="starttime" type="text" class="datepicker" value="<?php echo $starttime; ?>">
                </div>
            </div>

              <!-- end time -->
            <div class="form-group until">
                <label for="site_name" class="col-sm-4 control-label">Until:</label>
                <div class="col-sm-8" style="width:auto">
                    <input name="endtime" type="text" class="datepicker" value="<?php echo $endtime; ?>"></p>
                </div>
            </div>

            <div class="form-group until">
                <label for="site_name" class="col-sm-4 control-label">Staff:</label>
                <div class="col-sm-8" style="width:auto">
                    <select data-placeholder="Select a Staff..." name="therapist" class="chosen-select-deselect form-control" width="380">
                        <option value=""></option>
						<?php $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 AND status=1"),MYSQLI_ASSOC));
						foreach($query as $rowid) {
							echo "<option ".($rowid == $therapist ? 'selected' : '')." value='$rowid'>".get_contact($dbc, $rowid)."</option>";
						} ?>
                    </select>
                </div>
            </div>

            <button type="submit" name="search_email_submit" value="Search" class="btn brand-btn mobile-block">Submit</button>
            <br>

            <input type="hidden" name="starttimepdf" value="<?php echo $starttime; ?>">
            <input type="hidden" name="endtimepdf" value="<?php echo $endtime; ?>">
            <input type="hidden" name="therapistpdf" value="<?php echo $therapist; ?>">

            <button type="submit" name="printpdf" value="Print Report" class="btn brand-btn pull-right">Print Report</button>
            <br><br>

            <?php
                //echo '<a href="report_compensation.php?compensation=printpdf&starttime='.$starttime.'&endtime='.$endtime.'" class="btn brand-btn pull-right">Print Report</a></h4><br>';

                echo report_compensation($dbc, $starttime, $endtime, '', '', '', $therapist);
            ?>

        </form>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>

<?php
function report_compensation($dbc, $starttime, $endtime, $table_style, $table_row_style, $grand_total_style, $therapist) {
    $report_data = '';

    if($therapist == '') {
		$result = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT contactid, scheduled_hours FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 AND status=1"),MYSQLI_ASSOC));
    } else {
		$result = [$therapist];
    }

    $all_booking = 0;
    $grand_total = 0;
    foreach($result as $therapistid) {
		$row = mysqli_fetch_array(mysqli_query($dbc, "SELECT contactid, scheduled_hours FROM contacts WHERE contactid='$therapist'"));
		
        $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM compensation WHERE contactid='$therapistid'"));
        $base_pay = explode('*#*',$get_contact['base_pay']);

        $report_validation = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT group_concat(`serviceid` separator ',') as `all_serviceid`, group_concat(`inventoryid` separator ',') as `all_inventoryid`, group_concat(`sell_price` separator ',') as `all_sell_price` FROM invoice WHERE therapistsid='$therapistid' AND serviceid IS NOT NULL AND (service_date >= '".$starttime."' AND service_date <= '".$endtime."')"));

        //March
        $report_service = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT group_concat(`serviceid` separator ',') as `all_serviceid` FROM compensation_march WHERE contactid='$therapistid' AND serviceid IS NOT NULL AND today_date = '$starttime'"));
        //March

        $serviceid = explode(',', $report_service['all_serviceid']);
        $inventoryid = explode(',', $report_validation['all_inventoryid']);
        $sell_price = explode(',', $report_validation['all_sell_price']);

        $serviceid = array_filter($serviceid);
        $inventoryid = array_filter($inventoryid);
        $sell_price = array_filter($sell_price);

        // Services
        if($report_validation['all_serviceid'] != '') {
            asort($serviceid);
            $occurences = array_count_values($serviceid);

            $report_data .= '<h4>'.get_contact($dbc, $therapistid).' -  Base Pay Services</h4>';

            $report_data .= '<table border="1px" class="table table-bordered" style="'.$table_style.'">';
            $report_data .= '<tr style="'.$table_row_style.'">
            <th>Professional</th>
            <th>Item Description</th>
            <th>Total Appointments</th>
            <th>Compensation</th>';
            $report_data .= "</tr>";
            $total_base_service = 0;
            $total_base_fee = 0;
            $total_appt = 0;
            foreach ($occurences as $key => $value) {
                $final_serviceid = rtrim($key,',');

                // March
                //$get_march_comp = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT total_appt FROM compensation_march WHERE contactid='$therapistid' AND serviceid = '$final_serviceid'"));

                $get_march_comp = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT total_appt FROM compensation_march WHERE contactid='$therapistid' AND serviceid = '$final_serviceid' AND today_date = '$starttime'"));

                $value = $get_march_comp['total_appt'];
                // March

                $fee = get_all_from_service($dbc, $final_serviceid, 'fee');
                $report_data .= '<tr nobr="true">';
                $report_data .= '<td>'.get_contact($dbc, $therapistid).'</td>';
                $report_data .= '<td>'.get_all_from_service($dbc, $final_serviceid, 'service_code').' - '.get_all_from_service($dbc, $final_serviceid, 'heading').'</td>';
                $report_data .= '<td>'.$value.'</td>';

                $service_fee = $value*$fee;
                $base_pay_perc = $base_pay[0];
                $comp_pay = ($base_pay_perc*0.01*$service_fee);
                $report_data .= '<td>'.number_format($comp_pay, 2).'</td>';
                $report_data .= '</tr>';
                $total_appt += $value;
                $total_base_service += $comp_pay;
                $total_base_fee += $service_fee;
            }
            $report_data .= '<tr nobr="true">';
            $report_data .= '<td colspan="2">Total : '.get_contact($dbc, $therapistid).'</td>';
            $report_data .= '<td>' . $total_appt . '</td>';
            $report_data .= '<td>' . number_format($total_base_service, 2) . '</td>';
            $report_data .= "</tr>";
            $report_data .= '</table><br>';
            $grand_total += $total_base_service;
        }

        // Inventory
        $comma_remove = str_replace(',', '', $report_validation['all_inventoryid']);
        if($comma_remove != '') {
            asort($inventoryid);
            $sorted_arr2 = [];
            foreach($inventoryid as $key=>$val) {
              array_push($sorted_arr2, $sell_price[$key]);
            }
            $combined = combineStringArrayWithDuplicates($inventoryid, $sorted_arr2);

            $report_data .= '<h4>'.get_contact($dbc, $therapistid).' -  Base Pay Inventory</h4>';

            $report_data .= '<table border="1px" class="table table-bordered" style="'.$table_style.'">';
            $report_data .= '<tr style="'.$table_row_style.'">
            <th>Professional</th>
            <th>Item Description</th>
            <th>Qty</th>
            <th>Compensation</th>';
            $report_data .= "</tr>";
            $total_base_inv = 0;
            foreach ($combined as $key => $value) {
                $key_invid_qty = explode(':', $key);
                $invid = $key_invid_qty[0];
                $report_data .= '<tr nobr="true">';
                $report_data .= '<td>'.get_contact($dbc, $therapistid).'</td>';
                $report_data .= '<td>'.$invid.' - '.get_all_from_inventory($dbc, $invid, 'name').'</td>';
                $base_pay_inv_perc = $base_pay[1];
                $inv_pay = ($base_pay_inv_perc/100)*$value;
                $report_data .= '<td>'.$key_invid_qty[1].'</td>';
                $report_data .= '<td>'.number_format($inv_pay, 2).'</td>';
                $report_data .= '</tr>';
                $total_base_inv += $inv_pay;
            }
            $report_data .= '<tr nobr="true">';
            $report_data .= '<td colspan="3">Total : '.get_contact($dbc, $therapistid).'</td>';
            $report_data .= '<td>' . number_format($total_base_inv, 2) . '</td>';
            $report_data .= "</tr>";
            $report_data .= '</table><br>';
            $grand_total += $total_base_inv;
        }

        //Performance Pay

        //% of Available Hours Schedules
        $start_for_total_hours = $starttime;
        $now = strtotime($endtime);
        $your_date = strtotime($start_for_total_hours);
        $datediff = $now - $your_date;
        $total_days = floor($datediff/(60*60*24));
        $total_weekdays = '';
        for($i=0;$i<=$total_days;$i++) {
            $total_weekdays .= date('w', strtotime($start_for_total_hours)).',';
            $start_for_total_hours = date('Y-m-d',strtotime($start_for_total_hours . "+1 days"));
        }
        $schedule_days_var = rtrim($total_weekdays, ",");
        $total_therapist = 0;
        //% of Available Hours Schedules

        $report_data .= '<h4>'.get_contact($dbc, $therapistid).' -  Performance Pay</h4>';

        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT value FROM general_configuration WHERE name='staff_performance_pay'"));
        $value_config_base = $get_field_config['value'];

        $staff_performance_pay = explode('*#*',$value_config_base);
        $total_count = mb_substr_count($value_config_base,'*#*');

        $goal = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM goal WHERE therapistid='$therapistid'"));
        $compensation = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM compensation WHERE contactid='$therapistid'"));
        $performance_pay_perc = explode('*#*',$compensation['performance_pay_perc']);

        // ****************** Actual Value ****************** //
        //Client Scheduled
        $total_booking = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(bookingid) AS total_booking FROM booking WHERE therapistsid = '$therapistid' AND type != 'I' AND type != 'E' AND type != 'P' AND type != 'Q' AND type != 'R' AND type != '' AND ((str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) >= '".$starttime."' AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) <= '".$endtime."')"));
        //Client Scheduled

        //# of Client Visits
        $total_completed_booking = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(bookingid) AS total_completed_booking FROM booking WHERE therapistsid = '$therapistid' AND type != 'I' AND type != 'E' AND type != 'P' AND type != 'Q' AND type != 'R' AND type != '' AND (follow_up_call_status = 'Arrived' OR follow_up_call_status='Completed' OR follow_up_call_status = 'Paid' OR follow_up_call_status = 'Invoiced') AND ((str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) >= '".$starttime."' AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) <= '".$endtime."')"));
        //# of Client Visits

        //% Arrivals
        $arrival_rate = (($total_completed_booking['total_completed_booking'] / $total_booking['total_booking']) * 100);
        //% Arrivals

        //	Average # Visits per Client to Discharge
        $total_discharge_patient = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(injuryid) AS total_discharge_patient FROM patient_injury WHERE injury_therapistsid = '$therapistid' AND (DATE(discharge_date) >= '".$starttime."' AND DATE(discharge_date) <= '".$endtime."')"));
        $avg_visit_discharge = (($total_completed_booking['total_completed_booking'] / $total_discharge_patient['total_discharge_patient']));
        //	Average # Visits per Client to Discharge

        //% of Available Hours Schedules

        $s_hours = explode('*', $row['scheduled_hours']);
        $s_days = explode(',', $schedule_days_var);
        $total_work_hours = 0;
        $total_book_hours = 0;

        for($i=0;$i<=$total_days;$i++) {
            $s_day_index = $s_days[$i];
            $each_hours = $s_hours[$s_day_index];
            if($each_hours != '') {
                $each_double = explode(',', $each_hours);
                $count_no_come = count($each_double);
                if($count_no_come == 1) {
                    $each_double = explode('-', $each_hours);

                    $t1 = StrToTime ($each_double[1]);
                    $t2 = StrToTime ($each_double[0]);
                    $diff = $t1 - $t2;
                    $total_work_hours += $diff / ( 60 * 60 );
                } else {
                    $each_double = explode(',', $each_hours);
                    foreach($each_double as $key) {
                        $each_key = explode('-', $key);

                        $t1 = StrToTime ($each_key[1]);
                        $t2 = StrToTime ($each_key[0]);
                        $diff = $t1 - $t2;
                        $total_work_hours += $diff / ( 60 * 60 );
                    }
                }
            }
        }

        $appoints = mysqli_query($dbc, "SELECT appoint_date, end_appoint_date FROM booking WHERE therapistsid = '$therapistid' AND type != 'I' AND type != 'E' AND type != 'P' AND type != 'Q' AND type != 'R' AND type != '' AND follow_up_call_status != 'Cancelled' AND ((str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) >= '".$starttime."' AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) <= '".$endtime."')");

        while($row_appoints = mysqli_fetch_array( $appoints )) {
            $appoint_date = strtotime($row_appoints['appoint_date']);
            $end_appoint_date = strtotime($row_appoints['end_appoint_date']);
            $differenceInSeconds = $end_appoint_date - $appoint_date;
            $total_book_hours += ($differenceInSeconds / 3600);
        }

        $avail_booked = number_format((float)(($total_book_hours/$total_work_hours)*100), 2, '.', '');
        //% of Available Hours Schedules

        //# of New Clients
        $total_newclient = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(statid) AS total_newclient FROM therapist_stat WHERE therapistid = '$therapistid' AND (DATE(today_date) >= '".$starttime."' AND DATE(today_date) <= '".$endtime."')"));
        //# of New Clients

        //Assessment Count
        $total_injury = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(bookingid) AS total_assessment FROM booking WHERE therapistsid = '$therapistid' AND (type = 'A' OR type = 'C' OR type = 'F' OR type = 'H' OR type = 'N' OR type = 'U') AND (follow_up_call_status = 'Arrived' OR follow_up_call_status='Completed' OR follow_up_call_status = 'Paid' OR follow_up_call_status = 'Invoiced') AND ((str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) >= '".$starttime."' AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) <= '".$endtime."')"));
        //Assessment Count

        //Block Booking
        $total_bb_booking = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(bookingid) AS total_bb_booking FROM booking WHERE therapistsid = '$therapistid' AND type != 'I' AND type != 'E' AND type != 'P' AND type != 'Q' AND type != 'R' AND type != '' AND block_booking = 1 AND ((str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) >= '".$starttime."' AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) <= '".$endtime."')"));
        $bb_rate = (($total_bb_booking['total_bb_booking'] / $total_booking['total_booking']) * 100);
        //Block Booking

        //$report_fee =  mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(servicefee) AS total_servicefee FROM report_compensation WHERE therapistid='$therapistid' AND (DATE(today_date) >= '".$starttime."' AND DATE(today_date) <= '".$endtime."')"));

        $report_fee = $total_base_fee;


        // ****************** Actual Value ******************
        $report_data .= '<table border="1px" class="table table-bordered" style="'.$table_style.'">';
        $report_data .= '<tr style="'.$table_row_style.'">
        <th>Goal</th>
        <th>Goal Value</th>
        <th>Actual Value</th>
        <th>Comp%</th>
        <th>Compensation</th>';
        $report_data .= "</tr>";
        $final_perf = 0;
        for($eq_loop=0; $eq_loop<=$total_count; $eq_loop++) {
            if($staff_performance_pay[$eq_loop] == 'Arrival Rate %' || $staff_performance_pay[$eq_loop] == '% of available hours scheduled' || $staff_performance_pay[$eq_loop] == 'Testimonials submitted' || $staff_performance_pay[$eq_loop] == 'Manual Therapy Intermediate certification' || $staff_performance_pay[$eq_loop] == 'Manual Therapy Advanced Diploma certification') {
                $target = '';
                $actual_value = '';
                if($staff_performance_pay[$eq_loop] == 'Arrival Rate %') {
                    $target = $goal['arrival_rate'];
                    $actual_value = number_format($arrival_rate, 2).'%';
                }
                if($staff_performance_pay[$eq_loop] == 'Average Visits to Discharge') {
                    $target = $goal['average_visit_discharge'];
                    $actual_value = $avg_visit_discharge;
                }
                if($staff_performance_pay[$eq_loop] == '% of available hours scheduled') {
                    $target = $goal['hours_scheduled'];
                    $actual_value = $avail_booked.'%';
                }
                if($staff_performance_pay[$eq_loop] == '# of New Clients') {
                    $target = $goal['new_client'];
                    $actual_value = $total_newclient['total_newclient'];
                }
                if($staff_performance_pay[$eq_loop] == '# of Assessments') {
                    $target = $goal['assessment'];
                    $actual_value = $total_injury['total_assessment'];
                }
                if($staff_performance_pay[$eq_loop] == 'Block Booking') {
                    $target = $goal['block_booking'];
                    $actual_value = number_format((float)($bb_rate), 2, '.', '');
                }
                if($staff_performance_pay[$eq_loop] == 'Testimonials submitted') {
                    $target = $goal['testimonials_submitted'];
                }
                if($staff_performance_pay[$eq_loop] == 'Manual Therapy Intermediate certification') {
                    $target = $goal['manual_intermediate'];
                }
                if($staff_performance_pay[$eq_loop] == 'Manual Therapy Advanced Diploma certification') {
                    $target = $goal['manual_advanced'];
                }

                $comp_perc = str_replace("%","",$performance_pay_perc[$eq_loop]);

                $final_target = str_replace("%","",$target);
                $final_actual = str_replace("%","",$actual_value);

                $comp_final = ($report_fee*$comp_perc)/100;

                $report_data .= '<tr nobr="true">';
                $report_data .= '<td>'.$staff_performance_pay[$eq_loop].'</td>';
                $report_data .= '<td>'.$final_target.'</td>';
                $report_data .= '<td>'.$final_actual.'</td>';
                $report_data .= '<td>'.$comp_perc.'</td>';

                if($final_actual == '' || $final_actual<0 || $final_actual == 0 || $final_actual == '0.00' || $final_actual<$final_target ) {
                    $report_data .= '<td>0.00</td>';
                    $c_final = 0.00;
                } else {
                    $report_data .= '<td>'.number_format((float)($comp_final), 2, '.', '').'</td>';
                    $c_final = $comp_final;
                }
                $report_data .= '</tr>';
                $final_perf +=$c_final;
            }
        }
        $report_data .= '<tr nobr="true">';
        $report_data .= '<td colspan="4">Total : '.get_contact($dbc, $therapistid).'</td>';
        $report_data .= '<td>' . number_format($final_perf, 2) . '</td>';
        $report_data .= "</tr>";
        $report_data .= '</table><br>';
        $grand_total += $final_perf;

        $report_data .= '<table border="1px" class="table table-bordered" style="'.$table_style.'">';
        $report_data .= '<tr style="'.$table_row_style.'">
        <th>Goal</th>
        <th>Goal Value</th>
        <th>Actual Value</th>
        <th>Comp%</th>
        <th>Compensation</th>';
        $report_data .= "</tr>";

        for($eq_loop=0; $eq_loop<=$total_count; $eq_loop++) {
            if($staff_performance_pay[$eq_loop] == 'Average Visits to Discharge' || $staff_performance_pay[$eq_loop] == 'Block Booking' || $staff_performance_pay[$eq_loop] == '# of New Clients' || $staff_performance_pay[$eq_loop] == '# of Assessments') {
                $target = '';
                $actual_value = '';
                if($staff_performance_pay[$eq_loop] == 'Arrival Rate %') {
                    $target = $goal['arrival_rate'];
                    $actual_value = number_format($arrival_rate, 2).'%';
                }
                if($staff_performance_pay[$eq_loop] == 'Average Visits to Discharge') {
                    $target = $goal['average_visit_discharge'];
                    $actual_value = $avg_visit_discharge;
                }
                if($staff_performance_pay[$eq_loop] == '% of available hours scheduled') {
                    $target = $goal['hours_scheduled'];
                    $actual_value = $avail_booked.'%';
                }
                if($staff_performance_pay[$eq_loop] == '# of New Clients') {
                    $target = $goal['new_client'];
                    $actual_value = $total_newclient['total_newclient'];
                }
                if($staff_performance_pay[$eq_loop] == '# of Assessments') {
                    $target = $goal['assessment'];
                    $actual_value = $total_injury['total_assessment'];
                }
                if($staff_performance_pay[$eq_loop] == 'Block Booking') {
                    $target = $goal['block_booking'];
                    $actual_value = number_format((float)($bb_rate), 2, '.', '');
                }
                if($staff_performance_pay[$eq_loop] == 'Testimonials submitted') {
                    $target = $goal['testimonials_submitted'];
                }
                if($staff_performance_pay[$eq_loop] == 'Manual Therapy Intermediate certification') {
                    $target = $goal['manual_intermediate'];
                }
                if($staff_performance_pay[$eq_loop] == 'Manual Therapy Advanced Diploma certification') {
                    $target = $goal['manual_advanced'];
                }

                $comp_perc = str_replace("%","",$performance_pay_perc[$eq_loop]);

                $final_target = str_replace("%","",$target);
                $final_actual = str_replace("%","",$actual_value);

                $comp_final = ($report_fee*$comp_perc)/100;

                $report_data .= '<tr nobr="true">';
                $report_data .= '<td>'.$staff_performance_pay[$eq_loop].'</td>';
                $report_data .= '<td>'.$final_target.'</td>';
                $report_data .= '<td>'.$final_actual.'</td>';
                //$report_data .= '<td>'.$comp_perc.'</td>';
                $report_data .= '<td>0</td>';

                if($final_actual == '' || $final_actual<0 || $final_actual == 0 || $final_actual == '0.00' || $final_actual<$final_target ) {
                    $report_data .= '<td>0.00</td>';
                } else {
                    //$report_data .= '<td>'.number_format((float)($comp_final), 2, '.', '').'</td>';
                    $report_data .= '<td>0.00</td>';
                }
                $report_data .= '</tr>';
            }
        }
        $report_data .= '</table><br>';

        $report_data .= '<table border="1px" class="table table-bordered" style="'.$table_style.'">';
        $report_data .= '<tr style="'.$table_row_style.'">';
        $report_data .= '<tr nobr="true">';
        $report_data .= '<th colspan="4">Grand Total : '.get_contact($dbc, $therapistid).'</th>';
        $report_data .= '<th>$' . number_format($grand_total, 2) . '</th>';
        $report_data .= "</tr>";
        $report_data .= '</table><br>';
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