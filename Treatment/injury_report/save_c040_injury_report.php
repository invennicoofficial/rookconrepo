 <?php
    $today_date = date('Y-m-d');
    $patientid = filter_var($_POST['patientid'],FILTER_SANITIZE_STRING);
    $patient = get_contact($dbc, $patientid);

    $patientformid = filter_var($_POST["patientformid"],FILTER_SANITIZE_STRING);
    $claim_number = filter_var($_POST["claim_number"],FILTER_SANITIZE_STRING);
    $claim_type = filter_var($_POST["claim_type"],FILTER_SANITIZE_STRING);
    $last_name = filter_var($_POST["last_name"],FILTER_SANITIZE_STRING);
    $first_name = filter_var($_POST["first_name"],FILTER_SANITIZE_STRING);
    $Initial = filter_var($_POST["Initial"],FILTER_SANITIZE_STRING);
    $mailing_address = filter_var($_POST["mailing_address"],FILTER_SANITIZE_STRING);
    $city = filter_var($_POST["city"],FILTER_SANITIZE_STRING);
    $province = filter_var($_POST["province"],FILTER_SANITIZE_STRING);
    $postal_code = filter_var($_POST["postal_code"],FILTER_SANITIZE_STRING);
    $soc1 = filter_var($_POST["soc1"],FILTER_SANITIZE_STRING);
    $soc2 = filter_var($_POST["soc2"],FILTER_SANITIZE_STRING);
    $soc3 = filter_var($_POST["soc3"],FILTER_SANITIZE_STRING);
    $soc4 = filter_var($_POST["soc4"],FILTER_SANITIZE_STRING);
    $soc5 = filter_var($_POST["soc5"],FILTER_SANITIZE_STRING);
    $soc6 = filter_var($_POST["soc6"],FILTER_SANITIZE_STRING);
    $soc7 = filter_var($_POST["soc7"],FILTER_SANITIZE_STRING);
    $soc8 = filter_var($_POST["soc8"],FILTER_SANITIZE_STRING);
    $soc9 = filter_var($_POST["soc9"],FILTER_SANITIZE_STRING);
    $ph1 = filter_var($_POST["ph1"],FILTER_SANITIZE_STRING);
    $ph2 = filter_var($_POST["ph2"],FILTER_SANITIZE_STRING);
    $ph3 = filter_var($_POST["ph3"],FILTER_SANITIZE_STRING);
    $ph4 = filter_var($_POST["ph4"],FILTER_SANITIZE_STRING);
    $ph5 = filter_var($_POST["ph5"],FILTER_SANITIZE_STRING);
    $ph6 = filter_var($_POST["ph6"],FILTER_SANITIZE_STRING);
    $ph7 = filter_var($_POST["ph7"],FILTER_SANITIZE_STRING);
    $ph8 = filter_var($_POST["ph8"],FILTER_SANITIZE_STRING);
    $ph9 = filter_var($_POST["ph9"],FILTER_SANITIZE_STRING);
    $ind1 = filter_var($_POST["ind1"],FILTER_SANITIZE_STRING);
    $ind2 = filter_var($_POST["ind2"],FILTER_SANITIZE_STRING);
    $ind3 = filter_var($_POST["ind3"],FILTER_SANITIZE_STRING);
    $ind4 = filter_var($_POST["ind4"],FILTER_SANITIZE_STRING);
    $ind5 = filter_var($_POST["ind5"],FILTER_SANITIZE_STRING);
    $phone_number = filter_var($_POST["phone_number"],FILTER_SANITIZE_STRING);
    $dob = filter_var($_POST["dob"],FILTER_SANITIZE_STRING);
    $gender = filter_var($_POST["gender"],FILTER_SANITIZE_STRING);
    $occupation = filter_var($_POST["occupation"],FILTER_SANITIZE_STRING);
    $job_description = filter_var($_POST["job_description"],FILTER_SANITIZE_STRING);
    $date_hired = filter_var($_POST["date_hired"],FILTER_SANITIZE_STRING);
    $wcb_personal = filter_var($_POST["wcb_personal"],FILTER_SANITIZE_STRING);
    $partner = filter_var($_POST["partner"],FILTER_SANITIZE_STRING);
    $apprentice = filter_var($_POST["apprentice"],FILTER_SANITIZE_STRING);
    $journeyman = filter_var($_POST["journeyman"],FILTER_SANITIZE_STRING);
    $business_name = filter_var($_POST["business_name"],FILTER_SANITIZE_STRING);
    $wcb_account_number = filter_var($_POST["wcb_account_number"],FILTER_SANITIZE_STRING);
    $employer_mailing_address = filter_var($_POST["employer_mailing_address"],FILTER_SANITIZE_STRING);
    $employer_contact_name = filter_var($_POST["employer_contact_name"],FILTER_SANITIZE_STRING);
    $employer_city = filter_var($_POST["employer_city"],FILTER_SANITIZE_STRING);
    $employer_province = filter_var($_POST["employer_province"],FILTER_SANITIZE_STRING);
    $employer_post_code = filter_var($_POST["employer_post_code"],FILTER_SANITIZE_STRING);
    $employer_phone = filter_var($_POST["employer_phone"],FILTER_SANITIZE_STRING);
    $employer_fax = filter_var($_POST["employer_fax"],FILTER_SANITIZE_STRING);
    $pemployer_contact_phone = filter_var($_POST["pemployer_contact_phone"],FILTER_SANITIZE_STRING);
    $employer_email = filter_var($_POST["employer_email"],FILTER_SANITIZE_STRING);
    $accident_date = filter_var($_POST["accident_date"],FILTER_SANITIZE_STRING);
    $accident_time = filter_var($_POST["accident_time"],FILTER_SANITIZE_STRING);
    $shift_start_date = filter_var($_POST["shift_start_date"],FILTER_SANITIZE_STRING);
    $shift_start_time = filter_var($_POST["shift_start_time"],FILTER_SANITIZE_STRING);
    $shift_end_date = filter_var($_POST["shift_end_date"],FILTER_SANITIZE_STRING);
    $shift_end_time = filter_var($_POST["shift_end_time"],FILTER_SANITIZE_STRING);
    $injury_overtime = filter_var($_POST["injury_overtime"],FILTER_SANITIZE_STRING);
    $accident_reported_date = filter_var($_POST["accident_reported_date"],FILTER_SANITIZE_STRING);
    $detail1 = filter_var($_POST["detail1"],FILTER_SANITIZE_STRING);
    $detail2 = filter_var($_POST["detail2"],FILTER_SANITIZE_STRING);
    $detail3 = filter_var($_POST["detail3"],FILTER_SANITIZE_STRING);
    $motor = filter_var($_POST["motor"],FILTER_SANITIZE_STRING);
    $cardiac_injury = filter_var($_POST["cardiac_injury"],FILTER_SANITIZE_STRING);
    $attach_letter = filter_var($_POST["attach_letter"],FILTER_SANITIZE_STRING);
    $employer_premises = filter_var($_POST["employer_premises"],FILTER_SANITIZE_STRING);
    $location = filter_var($_POST["location"],FILTER_SANITIZE_STRING);
    $body_type = filter_var($_POST["body_type"],FILTER_SANITIZE_STRING);
    $type_of_injury = filter_var($_POST["type_of_injury"],FILTER_SANITIZE_STRING);
    $claim_date = filter_var($_POST["claim_date"],FILTER_SANITIZE_STRING);
    $worker_last_name = filter_var($_POST["worker_last_name"],FILTER_SANITIZE_STRING);
    $worker_first_name = filter_var($_POST["worker_first_name"],FILTER_SANITIZE_STRING);
    $worker_initial = filter_var($_POST["worker_initial"],FILTER_SANITIZE_STRING);
    $worker_soc1 = filter_var($_POST["worker_soc1"],FILTER_SANITIZE_STRING);
    $worker_soc2 = filter_var($_POST["worker_soc2"],FILTER_SANITIZE_STRING);
    $worker_soc3 = filter_var($_POST["worker_soc3"],FILTER_SANITIZE_STRING);
    $worker_soc4 = filter_var($_POST["worker_soc4"],FILTER_SANITIZE_STRING);
    $worker_soc5 = filter_var($_POST["worker_soc5"],FILTER_SANITIZE_STRING);
    $worker_soc6 = filter_var($_POST["worker_soc6"],FILTER_SANITIZE_STRING);
    $worker_soc7 = filter_var($_POST["worker_soc7"],FILTER_SANITIZE_STRING);
    $worker_soc8 = filter_var($_POST["worker_soc8"],FILTER_SANITIZE_STRING);
    $worker_soc9 = filter_var($_POST["worker_soc9"],FILTER_SANITIZE_STRING);
    $worker_dob = filter_var($_POST["worker_dob"],FILTER_SANITIZE_STRING);
    $regular_pay = filter_var($_POST["regular_pay"],FILTER_SANITIZE_STRING);
    $work_returned = filter_var($_POST["work_returned"],FILTER_SANITIZE_STRING);
    $has_returned = filter_var($_POST["has_returned"],FILTER_SANITIZE_STRING);
    $missed_work_date = filter_var($_POST["missed_work_date"],FILTER_SANITIZE_STRING);
    $missed_work_time = filter_var($_POST["missed_work_time"],FILTER_SANITIZE_STRING);
    $return_work_date = filter_var($_POST["return_work_date"],FILTER_SANITIZE_STRING);
    $return_work_time = filter_var($_POST["return_work_time"],FILTER_SANITIZE_STRING);
    $current_work_status = filter_var($_POST["current_work_status"],FILTER_SANITIZE_STRING);
    $current_work_status_hours = filter_var($_POST["current_work_status_hours"],FILTER_SANITIZE_STRING);
    $current_work_status_rate = filter_var($_POST["current_work_status_rate"],FILTER_SANITIZE_STRING);
    $modified_hours = filter_var($_POST["modified_hours"],FILTER_SANITIZE_STRING);
    $modified_per = filter_var($_POST["modified_per"],FILTER_SANITIZE_STRING);
    $regular_hours = filter_var($_POST["regular_hours"],FILTER_SANITIZE_STRING);
    $regular_per = filter_var($_POST["regular_per"],FILTER_SANITIZE_STRING);
    $working_duties = filter_var($_POST["working_duties"],FILTER_SANITIZE_STRING);
    $accommodate = filter_var($_POST["accommodate"],FILTER_SANITIZE_STRING);
    $approx_return_date = filter_var($_POST["approx_return_date"],FILTER_SANITIZE_STRING);
    $position_number = filter_var($_POST["position_number"],FILTER_SANITIZE_STRING);
    $employment_type = filter_var($_POST["employment_type"],FILTER_SANITIZE_STRING);
    $permanent_type = filter_var($_POST["permanent_type"],FILTER_SANITIZE_STRING);
    $non_permanent_type = filter_var($_POST["non_permanent_type"],FILTER_SANITIZE_STRING);
    $position_start_date = filter_var($_POST["position_start_date"],FILTER_SANITIZE_STRING);
    $position_end_date = filter_var($_POST["position_end_date"],FILTER_SANITIZE_STRING);
    $position_type = filter_var($_POST["position_type"],FILTER_SANITIZE_STRING);
    $number_of_months = filter_var($_POST["number_of_months"],FILTER_SANITIZE_STRING);
    $alternate_employment = filter_var($_POST["alternate_employment"],FILTER_SANITIZE_STRING);
    $incur_expense = filter_var($_POST["incur_expense"],FILTER_SANITIZE_STRING);
    $receive_t4 = filter_var($_POST["receive_t4"],FILTER_SANITIZE_STRING);
    $earning_contact_name = filter_var($_POST["earning_contact_name"],FILTER_SANITIZE_STRING);
    $earning_contact_phone = filter_var($_POST["earning_contact_phone"],FILTER_SANITIZE_STRING);
    $earning_contact_email = filter_var($_POST["earning_contact_email"],FILTER_SANITIZE_STRING);
    $gross_earning = filter_var($_POST["gross_earning"],FILTER_SANITIZE_STRING);
    $gross_start_date = filter_var($_POST["gross_start_date"],FILTER_SANITIZE_STRING);
    $gross_end_date = filter_var($_POST["gross_end_date"],FILTER_SANITIZE_STRING);
    $time_missed = filter_var($_POST["time_missed"],FILTER_SANITIZE_STRING);
    $date_n_reason = filter_var($_POST["date_n_reason"],FILTER_SANITIZE_STRING);
    $hourly_rate = filter_var($_POST["hourly_rate"],FILTER_SANITIZE_STRING);
    $vacation_pay = filter_var($_POST["vacation_pay"],FILTER_SANITIZE_STRING);
    $vacation_pay_amount = filter_var($_POST["vacation_pay_amount"],FILTER_SANITIZE_STRING);
    $shift_earning = filter_var($_POST["shift_earning"],FILTER_SANITIZE_STRING);
    $shift_earning_start_date = filter_var($_POST["shift_earning_start_date"],FILTER_SANITIZE_STRING);
    $shift_earning_end_date = filter_var($_POST["shift_earning_end_date"],FILTER_SANITIZE_STRING);
    $overtime_earning = filter_var($_POST["overtime_earning"],FILTER_SANITIZE_STRING);
    $overtime_start_date = filter_var($_POST["overtime_start_date"],FILTER_SANITIZE_STRING);
    $overtime_end_date = filter_var($_POST["overtime_end_date"],FILTER_SANITIZE_STRING);
    $other_earning = filter_var($_POST["other_earning"],FILTER_SANITIZE_STRING);
    $other_start_date = filter_var($_POST["other_start_date"],FILTER_SANITIZE_STRING);
    $other_end_date = filter_var($_POST["other_end_date"],FILTER_SANITIZE_STRING);
    $number_of_hours = filter_var($_POST["number_of_hours"],FILTER_SANITIZE_STRING);
    $number_of_hours_per = filter_var($_POST["number_of_hours_per"],FILTER_SANITIZE_STRING);
    $number_of_hours_other = filter_var($_POST["number_of_hours_other"],FILTER_SANITIZE_STRING);
    $commenced = filter_var($_POST["commenced"],FILTER_SANITIZE_STRING);
    $repeat = filter_var($_POST["repeat"],FILTER_SANITIZE_STRING);
    $sun1 = filter_var($_POST["sun1"],FILTER_SANITIZE_STRING);
    $mon1 = filter_var($_POST["mon1"],FILTER_SANITIZE_STRING);
    $tue1 = filter_var($_POST["tue1"],FILTER_SANITIZE_STRING);
    $wed1 = filter_var($_POST["wed1"],FILTER_SANITIZE_STRING);
    $thu1 = filter_var($_POST["thu1"],FILTER_SANITIZE_STRING);
    $fri1 = filter_var($_POST["fri1"],FILTER_SANITIZE_STRING);
    $sat1 = filter_var($_POST["sat1"],FILTER_SANITIZE_STRING);
    $sun2 = filter_var($_POST["sun2"],FILTER_SANITIZE_STRING);
    $mon2 = filter_var($_POST["mon2"],FILTER_SANITIZE_STRING);
    $tue2 = filter_var($_POST["tue2"],FILTER_SANITIZE_STRING);
    $wed2 = filter_var($_POST["wed2"],FILTER_SANITIZE_STRING);
    $thu2 = filter_var($_POST["thu2"],FILTER_SANITIZE_STRING);
    $fri2 = filter_var($_POST["fri2"],FILTER_SANITIZE_STRING);
    $sat2 = filter_var($_POST["sat2"],FILTER_SANITIZE_STRING);
    $sun3 = filter_var($_POST["sun3"],FILTER_SANITIZE_STRING);
    $mon3 = filter_var($_POST["mon3"],FILTER_SANITIZE_STRING);
    $tue3 = filter_var($_POST["tue3"],FILTER_SANITIZE_STRING);
    $wed3 = filter_var($_POST["wed3"],FILTER_SANITIZE_STRING);
    $thu3 = filter_var($_POST["thu3"],FILTER_SANITIZE_STRING);
    $fri3 = filter_var($_POST["fri3"],FILTER_SANITIZE_STRING);
    $sat3 = filter_var($_POST["sat3"],FILTER_SANITIZE_STRING);
    $regular_repeat_hours = filter_var($_POST["regular_repeat_hours"],FILTER_SANITIZE_STRING);

    $purpose_of_business = filter_var($_POST["purpose_of_business"],FILTER_SANITIZE_STRING);
    $regular_duties = filter_var($_POST["regular_duties"],FILTER_SANITIZE_STRING);
    $modified_duties = filter_var($_POST["modified_duties"],FILTER_SANITIZE_STRING);

    $signature = $_POST['output'];

    $query_insert_site = "INSERT INTO `patientform_c040_injury_report` (`patientformid`,`claim_number`,`claim_type`,`last_name`,`first_name`,`Initial`,
      `mailing_address`,`city`,`province`,`postal_code`,`soc1`,`soc2`,`soc3`,`soc4`,`soc5`,`soc6`,`soc7`,`soc8`,
      `soc9`,`ph1`,`ph2`,`ph3`,`ph4`,`ph5`,`ph6`,`ph7`,`ph8`,`ph9`,`ind1`,`ind2`,`ind3`,`ind4`,`ind5`,`phone_number`,`dob`,`gender`,`occupation`,`job_description`,`date_hired`,`wcb_personal`,
      `partner`,`apprentice`,`journeyman`,`business_name`,`wcb_account_number`,`employer_mailing_address`,`employer_contact_name`,`employer_city`,
      `employer_province`,`employer_post_code`,`employer_phone`,`employer_fax`,`pemployer_contact_phone`,`employer_email`,`accident_date`,`accident_time`,
      `shift_start_date`,`shift_start_time`,`shift_end_date`,`shift_end_time`,`injury_overtime`,`accident_reported_date`,`detail1`,`detail2`,`detail3`,`motor`,`cardiac_injury`,`attach_letter`,
      `employer_premises`,`location`,`body_type`,`type_of_injury`,`claim_date`,`worker_last_name`,`worker_first_name`,`worker_initial`,
      `worker_soc1`,`worker_soc2`,`worker_soc3`,`worker_soc4`,`worker_soc5`,`worker_soc6`,`worker_soc7`,`worker_soc8`,`worker_soc9`,`worker_dob`,
      `regular_pay`,`work_returned`,`has_returned`,`missed_work_date`,`missed_work_time`,`return_work_date`,`return_work_time`,`current_work_status`,`current_work_status_hours`,`current_work_status_rate`,
      `modified_hours`,`modified_per`,`regular_hours`,`regular_per`,`working_duties`,`accommodate`,`approx_return_date`,`position_number`,
      `employment_type`,`permanent_type`,`non_permanent_type`,`position_start_date`,`position_end_date`,`position_type`,`number_of_months`,
      `alternate_employment`,`incur_expense`,`receive_t4`,`earning_contact_name`,`earning_contact_phone`,`earning_contact_email`,`gross_earning`,
      `gross_start_date`,`gross_end_date`,`time_missed`,`date_n_reason`,`hourly_rate`,`vacation_pay`,`vacation_pay_amount`,`shift_earning`,
      `shift_earning_start_date`,`shift_earning_end_date`,`overtime_earning`,`overtime_start_date`,`overtime_end_date`,`other_earning`,
      `other_start_date`,`other_end_date`,`number_of_hours`,`number_of_hours_per`,`number_of_hours_other`,`commenced`,`repeat`,`sun1`,`mon1`,`tue1`,
      `wed1`,`thu1`,`fri1`,`sat1`,`sun2`,`mon2`,`tue2`,`wed2`,`thu2`,`fri2`,`sat2`,`sun3`,`mon3`,`tue3`,`wed3`,`thu3`,`fri3`,`sat3`,
      `regular_repeat_hours`,`purpose_of_business`,`regular_duties`,`modified_duties`)
      VALUES('$patientformid','$claim_number','$claim_type','$last_name','$first_name','$Initial','$mailing_address','$city','$province','$postal_code','$soc1','$soc2',
        '$soc3','$soc4','$soc5','$soc6','$soc7','$soc8','$soc9','$ph1','$ph2','$ph3','$ph4','$ph5','$ph6','$ph7','$ph8','$ph9','$ind1','$ind2','$ind3','$ind4',
        '$ind5','$phone_number','$dob','$gender','$occupation','$job_description','$date_hired',
        '$wcb_personal','$partner','$apprentice','$journeyman','$business_name','$wcb_account_number','$employer_mailing_address','$employer_contact_name',
        '$employer_city','$employer_province','$employer_post_code','$employer_phone','$employer_fax','$pemployer_contact_phone','$employer_email',
        '$accident_date','$accident_time','$shift_start_date','$shift_start_time','$shift_end_date','$shift_end_time','$injury_overtime','$accident_reported_date',
        '$detail1','$detail2','$detail3', '$motor','$cardiac_injury','$attach_letter','$employer_premises','$location','$body_type','$type_of_injury','$claim_date','$worker_last_name',
        '$worker_first_name','$worker_initial','$worker_soc1','$worker_soc2','$worker_soc3','$worker_soc4','$worker_soc5','$worker_soc6','$worker_soc7',
        '$worker_soc8','$worker_soc9','$worker_dob','$regular_pay','$work_returned','$has_returned','$missed_work_date','$missed_work_time',
        '$return_work_date','$return_work_time','$current_work_status','$current_work_status_hours','$current_work_status_rate','$modified_hours','$modified_per','$regular_hours','$regular_per',
        '$working_duties','$accommodate','$approx_return_date','$position_number','$employment_type','$permanent_type','$non_permanent_type',
        '$position_start_date','$position_end_date','$position_type','$number_of_months','$alternate_employment','$incur_expense','$receive_t4','$earning_contact_name',
        '$earning_contact_phone','$earning_contact_email','$gross_earning','$gross_start_date','$gross_end_date','$time_missed','$date_n_reason',
        '$hourly_rate','$vacation_pay','$vacation_pay_amount','$shift_earning','$shift_earning_start_date','$shift_earning_end_date','$overtime_earning',
        '$overtime_start_date','$overtime_end_date','$other_earning','$other_start_date','$other_end_date','$number_of_hours','$number_of_hours_per',
        '$number_of_hours_other','$commenced','$repeat','$sun1','$mon1','$tue1','$wed1','$thu1','$fri1','$sat1','$sun2','$mon2','$tue2','$wed2','$thu2',
        '$fri2','$sat2','$sun3','$mon3','$tue3','$wed3','$thu3','$fri3','$sat3','$regular_repeat_hours','$purpose_of_business','$regular_duties', '$modified_duties')";

    $result_insert_site	= mysqli_query($dbc, $query_insert_site);
    $fieldlevelriskid = mysqli_insert_id($dbc);

    $img = sigJsonToImage($signature);
    imagepng($img, 'injury_report/download/sign_'.$fieldlevelriskid.'.png');

    $form_name = get_formid_from_patientform($dbc, $patientformid, 'form');
    $pdf_path = 'injury_report/download/patientform_'.$fieldlevelriskid.'.pdf';

    $query_insert_site = "INSERT INTO `patientform_pdf` (`patientformid`, `fieldlevelriskid`, `patientid`, `form_name`, `pdf_path`, `today_date`) VALUES	('$patientformid', '$fieldlevelriskid', '$patientid', '$form_name', '$pdf_path', '$today_date')";
    $result_insert_site	= mysqli_query($dbc, $query_insert_site);

    include ('c040_injury_report_pdf.php');
    echo c040_injury_report_pdf($dbc,$patientformid, $fieldlevelriskid);

    echo '<script type="text/javascript">
        window.location.replace("patientform.php?tab=Form");
        window.open("injury_report/download/patientform_'.$fieldlevelriskid.'.pdf", "fullscreen=yes"); </script>';
