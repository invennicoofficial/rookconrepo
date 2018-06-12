 <?php

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT value FROM general_configuration WHERE name='staff_performance_pay'"));
    $value_config_base = $get_field_config['value'];

    $staff_performance_pay = explode('*#*',$value_config_base);
    $total_count = mb_substr_count($value_config_base,'*#*');

    $goal = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM goal WHERE therapistid='$therapistid'"));
    $compensation = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM compensation WHERE contactid='$therapistid' AND '$starttime' BETWEEN start_date AND end_date"));
    $performance_pay_perc = explode('*#*',$compensation['performance_pay_perc']);

    //Performance Pay

    // ****************** Actual Value ****************** //
    //Client Scheduled
    $total_booking = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(bookingid) AS total_booking FROM booking WHERE therapistsid = '$therapistid' AND type != 'I' AND type != 'E' AND type != 'P' AND type != 'Q' AND type != 'R' AND type != '' AND ((str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) >= '".$starttime."' AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) <= '".$endtime."')"));
    //Client Scheduled

    //# of Client Visits
    $total_completed_booking = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(bookingid) AS total_completed_booking FROM booking WHERE therapistsid = '$therapistid' AND type != 'I' AND type != 'E' AND type != 'P' AND type != 'Q' AND type != 'R' AND type != '' AND (follow_up_call_status = 'Arrived' OR follow_up_call_status='Completed' OR follow_up_call_status = 'Paid' OR follow_up_call_status = 'Invoiced') AND ((str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) >= '".$starttime."' AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) <= '".$endtime."')"));
    //# of Client Visits

    //	Average # Visits per Client to Discharge
    $total_discharge_patient = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(injuryid) AS total_discharge_patient FROM patient_injury WHERE injury_therapistsid = '$therapistid' AND (DATE(discharge_date) >= '".$starttime."' AND DATE(discharge_date) <= '".$endtime."')"));
    $avg_visit_discharge = (($total_completed_booking['total_completed_booking'] / $total_discharge_patient['total_discharge_patient']));
    //	Average # Visits per Client to Discharge

    //# of New Clients
    $total_newclient = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(statid) AS total_newclient FROM therapist_stat WHERE therapistid = '$therapistid' AND (DATE(today_date) >= '".$starttime."' AND DATE(today_date) <= '".$endtime."')"));
    //# of New Clients

    //Assessment Count
    $total_injury = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(bookingid) AS total_assessment FROM booking WHERE therapistsid = '$therapistid' AND (type = 'A' OR type = 'C' OR type = 'F' OR type = 'H' OR type = 'N' OR type = 'U') AND (follow_up_call_status = 'Arrived' OR follow_up_call_status='Completed' OR follow_up_call_status = 'Paid' OR follow_up_call_status = 'Invoiced') AND ((str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) >= '".$starttime."' AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) <= '".$endtime."')"));
    //Assessment Count

    //Block Booking
    //$total_bb_booking = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(bookingid) AS total_bb_booking FROM booking WHERE therapistsid = '$therapistid' AND type != 'I' AND type != 'E' AND type != 'P' AND type != 'Q' AND type != 'R' AND type != '' AND block_booking = 1 AND ((str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) >= '".$starttime."' AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) <= '".$endtime."')"));
    //$bb_rate = (($total_bb_booking['total_bb_booking'] / $total_booking['total_booking']) * 100);

    $get_booking = mysqli_query($dbc,"SELECT bookingid, appoint_date, today_date, patientid FROM booking WHERE therapistsid = '$therapistid' AND type IN('A','C','F','H','N','U') AND ((str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) >= '".$starttime."' AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) <= '".$endtime."')");
    $total_bb = 0;
    while($row_get_booking = mysqli_fetch_array($get_booking)) {
        $bb_appoint_date = explode(' ', $row_get_booking['appoint_date']);
        $final_ass_appoint_date = $bb_appoint_date[0];
        $patientid = $row_get_booking['patientid'];
        $type = $row_get_booking['type'];

        $get_bb = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(bookingid) AS total_bb FROM booking WHERE patientid='$patientid' AND today_date = '".$final_ass_appoint_date."' AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) > '".$final_ass_appoint_date."'"));

        if($get_bb['total_bb'] >= 4) {
            $total_bb++;
        }
    }
    $block_booking = (($total_bb / $total_completed_booking['total_completed_booking']) * 100);

    //Block Booking

    $report_data .= '<h4>'.get_contact($dbc, $therapistid).' -  Stats</h4>';

    $report_data .= '<table border="1px" class="table table-bordered" style="'.$table_style.'">';
    $report_data .= '<tr style="'.$table_row_style.'">
    <th>Goal</th>
    <th>Goal Value</th>
    <th>Actual Value</th>';
    $report_data .= "</tr>";

    for($eq_loop=0; $eq_loop<=$total_count; $eq_loop++) {
        if($staff_performance_pay[$eq_loop] == 'Average Visits to Discharge' || $staff_performance_pay[$eq_loop] == 'Block Booking' || $staff_performance_pay[$eq_loop] == '# of New Clients' || $staff_performance_pay[$eq_loop] == '# of Assessments') {
            $target = '';
            $actual_value = '';
            $name = '';
            if($staff_performance_pay[$eq_loop] == 'Average Visits to Discharge') {
                $target = $goal['average_visit_discharge'];
                $actual_value = number_format($avg_visit_discharge, 2).'%';
                $name = '(Total Completed Booking/Total Discharge Patient)';
            }
            if($staff_performance_pay[$eq_loop] == '# of New Clients') {
                $target = $goal['new_client'];
                $actual_value = $total_newclient['total_newclient'];
            }
            if($staff_performance_pay[$eq_loop] == '# of Assessments') {
                $target = $goal['assessment'];
                $actual_value = $total_injury['total_assessment'];
                $name = '(Just Assessment)';
            }
            if($staff_performance_pay[$eq_loop] == 'Block Booking') {
                $target = $goal['block_booking'];
                $actual_value = number_format((float)($block_booking), 2, '.', '');
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
            //$report_data .= '<td>0</td>';

            if($final_actual == '' || $final_actual<0 || $final_actual == 0 || $final_actual == '0.00' || $final_actual<$final_target ) {
                //$report_data .= '<td>0.00</td>';
            } else {
                //$report_data .= '<td>'.number_format((float)($comp_final), 2, '.', '').'</td>';
                //$report_data .= '<td>0.00</td>';
            }
            $report_data .= '</tr>';
        }
    }
    $report_data .= '</table><br>';