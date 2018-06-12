 <?php

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT value FROM general_configuration WHERE name='staff_performance_pay'"));
    $value_config_base = $get_field_config['value'];

    $staff_performance_pay = explode('*#*',$value_config_base);
    $total_count = mb_substr_count($value_config_base,'*#*');

    $goal = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM goal WHERE therapistid='$therapistid'"));

    // Vacation Pay
    $vacation_pay_perc = $goal['vacation_pay'];
    // Vacation Pay

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

    //% Arrivals
    $arrival_rate = (($total_completed_booking['total_completed_booking'] / $total_booking['total_booking']) * 100);
    //% Arrivals

    //% of Available Hours Schedules
    $total_active = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(DISTINCT((str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')))) AS total_active FROM booking WHERE therapistsid = '$therapistid' AND type != 'I' AND type != 'E' AND type != 'P' AND type != 'Q' AND type != 'R' AND type != '' AND ((str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) >= '".$starttime."' AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) <= '".$endtime."')"));
    $total_avg_active = ($total_active['total_active']*17);
    $avail_hours_sch = (($total_completed_booking['total_completed_booking'] / $total_avg_active) * 100);
    //% of Available Hours Schedules

    $arr_perc = 0;
    $avg_hours_sch_perc = 0;
    $test_perc = 0;
    $inter_perc = 0;
    $adv_perc = 0;
    $final_perf = 0;
    for($eq_loop=0; $eq_loop<=$total_count; $eq_loop++) {
        if($staff_performance_pay[$eq_loop] == 'Arrival Rate %' || $staff_performance_pay[$eq_loop] == '% of available hours scheduled' || $staff_performance_pay[$eq_loop] == 'Testimonials submitted' || $staff_performance_pay[$eq_loop] == 'Manual Therapy Intermediate certification' || $staff_performance_pay[$eq_loop] == 'Manual Therapy Advanced Diploma certification') {
            $target = '';
            $actual_value = '';

            $comp_perc = str_replace("%","",$performance_pay_perc[$eq_loop]);  ///
            $comp_final = ($report_fee*$comp_perc)/100;  ///

            if($staff_performance_pay[$eq_loop] == 'Arrival Rate %') {
                $arr_target = $goal['arrival_rate'];
                $arr_actual_value = number_format($arrival_rate, 2).'%';
                $arr_perc = $comp_perc;
            }
            if($staff_performance_pay[$eq_loop] == '% of available hours scheduled') {
                $avg_hours_sch_target = $goal['hours_scheduled'];
                $avg_hours_sch_actual_value = number_format($avail_hours_sch, 2).'%';
                $avg_hours_sch_perc = $comp_perc;
            }
            if($staff_performance_pay[$eq_loop] == 'Testimonials submitted') {
                $test_actual_value = $goal['testimonials_submitted'];
                //if($test_actual_value != 0) {
                    $test_perc = $comp_perc;
                //}
            }
            if($staff_performance_pay[$eq_loop] == 'Manual Therapy Intermediate certification') {
                $inter_actual_value = $goal['manual_intermediate'];
                //if($inter_actual_value != 0) {
                    $inter_perc = $comp_perc;
                //}
            }
            if($staff_performance_pay[$eq_loop] == 'Manual Therapy Advanced Diploma certification') {
                $adv_actual_value = $goal['manual_advanced'];
                //if($adv_actual_value != 0) {
                    $adv_perc = $comp_perc;
                //}
            }

            $final_perf +=$c_final;
        }
    }

