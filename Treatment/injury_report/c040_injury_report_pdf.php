<?php
ob_start();
error_reporting(1);
function c040_injury_report_pdf($dbc,$patientformid, $fieldlevelriskid) {


    $form = get_patientform($dbc, $patientformid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_patientform WHERE form='$form'"));
    DEFINE('PDF_LOGO', $get_field_config['pdf_logo']);
    $form_config = ','.$get_field_config['fields'].',';

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM patientform_c040_injury_report WHERE fieldlevelriskid=$fieldlevelriskid"));

  class MYPDF extends TCPDF {

  	public function Header() {
  		if($this->page == 1) {
  	        // set bacground image
  	        $img_file = '../Treatment/injury_report/bg1.png';
  	        $this->Image($img_file, 0, 5, 214, 276, '', '', '', false, 300, '', false, false, 0);
  		} else if($this->page == 2) {
  	        // set bacground image
  	        $img_file = '../Treatment/injury_report/bg2.png';
  	        $this->Image($img_file, 2, 8, 212, 270, '', '', '', false, 300, '', false, false, 0);
  	    }
  	}
  }

  $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LETTER', true, 'UTF-8', false);
  $pdf->SetMargins(10, 10, 10);
  $pdf->SetFooterMargin(10);
  $pdf->SetAutoPageBreak(FALSE, 0);
  $pdf->AddPage();
  $pdf->SetFont('helvetica', '', 8);
  $pdf->setCellHeightRatio(1);
  $pdf->setPrintFooter(false);

  $claim_number=!empty($get_field_level['claim_number']) ? $get_field_level['claim_number'] : '';

  $Time_Lost="";
  $Modified_Work="";
  $Fatality="";
  $No_Time_Lost="";
  $claim_type = $get_field_level['claim_type'];
  if($claim_type == 'Time Lost') {
    $Time_Lost="X";
  }
  if($claim_type == 'Modified Work') {
    $Modified_Work="X";
  }
  if($claim_type == 'Fatality') {
    $Fatality="X";
  }
  if($claim_type == 'No Time Lost') {
    $No_Time_Lost="X";
  }

  $Last_name=$get_field_level['last_name'];
  $First_name=$get_field_level['first_name'];
  $Initial=$get_field_level['Initial'];
  $mailing_address = $get_field_level['mailing_address'];

  $Social_Insurance1=$get_field_level['soc1'];
  $Social_Insurance2=$get_field_level['soc2'];
  $Social_Insurance3=$get_field_level['soc3'];
  $Social_Insurance4=$get_field_level['soc4'];
  $Social_Insurance5=$get_field_level['soc5'];
  $Social_Insurance6=$get_field_level['soc6'];
  $Social_Insurance7=$get_field_level['soc7'];
  $Social_Insurance8=$get_field_level['soc8'];
  $Social_Insurance9=$get_field_level['soc9'];

  $business_name = $get_field_level['business_name'];
  $City=$get_field_level['city'];
  $Province=$get_field_level['province'];
  $Postal_Code=$get_field_level['postal_code'];

  $Personal_Health1=$get_field_level['ph1'];
  $Personal_Health2=$get_field_level['ph2'];
  $Personal_Health3=$get_field_level['ph3'];
  $Personal_Health4=$get_field_level['ph4'];
  $Personal_Health5=$get_field_level['ph5'];
  $Personal_Health6=$get_field_level['ph6'];
  $Personal_Health7=$get_field_level['ph7'];
  $Personal_Health8=$get_field_level['ph8'];
  $Personal_Health9=$get_field_level['ph9'];

  $Phone_number=$get_field_level['phone_number'];

  $m="";
  $f="";
  $gender = $get_field_level['gender'];
  if($gender == 'male') {
    $m="X";
  }
  if($gender == 'female') {
    $f="X";
  }

  $Occupation=$get_field_level['occupation'];
  $Job_description=$get_field_level['job_description'];

  $dobb = $get_field_level['dob'];
  $dob[0] = str_split($dobb);
  if(!empty($dobb) && $dobb != '0000-00-00') {
    $dobb1=$dob[0][0];
    $dobb2=$dob[0][1];
    $dobb3=$dob[0][2];
    $dobb4=$dob[0][3];
    $dobb5=$dob[0][5];
    $dobb6=$dob[0][6];
    $dobb7=$dob[0][8];
    $dobb8=$dob[0][9];
  }

  $date_hired = $get_field_level['date_hired'];
  $dob[0] = str_split($date_hired);
  if(!empty($date_hired) && $date_hired != '0000-00-00') {
    $Date_hired1=$dob[0][0];
    $Date_hired2=$dob[0][1];
    $Date_hired3=$dob[0][2];
    $Date_hired4=$dob[0][3];
    $Date_hired5=$dob[0][5];
    $Date_hired6=$dob[0][6];
    $Date_hired7=$dob[0][8];
    $Date_hired8=$dob[0][9];
  }

  $WCB_personal_business_yes="";
  $WCB_personal_business_no="";
  $wcb_personal = $get_field_level['wcb_personal'];
  if($wcb_personal == 'yes') {
    $WCB_personal_business_yes="X";
  }
  if($wcb_personal == 'no') {
    $WCB_personal_business_no="X";
  }

  $director_in_this_business_yes="";
  $director_in_this_business_no="";
  $partner = $get_field_level['partner'];
  if($partner == 'yes') {
    $director_in_this_business_yes="X";
  }
  if($partner == 'no') {
    $director_in_this_business_no="X";
  }

  $apprentice_yes="";
  $apprentice_no="";
  $apprentice = $get_field_level['apprentice'];
  if($apprentice == 'yes') {
    $apprentice_yes="X";
  }
  if($apprentice == 'no') {
    $apprentice_no="X";
  }

  $journeyman = $get_field_level['journeyman'];
  $dob[0] = str_split($journeyman);
  if(!empty($journeyman) && $journeyman != '0000-00-00') {
    $journeyman_status1=$dob[0][0];
    $journeyman_status2=$dob[0][1];
    $journeyman_status3=$dob[0][2];
    $journeyman_status4=$dob[0][3];
    $journeyman_status5=$dob[0][5];
    $journeyman_status6=$dob[0][6];
    $journeyman_status7=$dob[0][8];
    $journeyman_status8=$dob[0][9];
  }

  $WCB_Account_Number1=$get_field_level['ind1'];
  $WCB_Account_Number2=$get_field_level['ind2'];
  $WCB_Account_Number3=$get_field_level['ind3'];
  $WCB_Account_Number4=$get_field_level['ind4'];
  $WCB_Account_Number5=$get_field_level['ind5'];

  $Mailing_Address=$get_field_level['employer_mailing_address'];
  $employer_contact_name=$get_field_level['employer_contact_name'];
  $wcb_account_number = $get_field_level['wcb_account_number'];
  $City1=$get_field_level['employer_city'];
  $Province1=$get_field_level['employer_province'];
  $Postal_Code1=$get_field_level['employer_post_code'];
  $Phone1=$get_field_level['employer_phone'];
  $Fax1=$get_field_level['employer_fax'];
  $Contact_Phone1=$get_field_level['pemployer_contact_phone'];
  $Contact_Email1=$get_field_level['employer_email'];

  $accident_date = $get_field_level['accident_date'];
  $dob[0] = str_split($accident_date);
  if(!empty($accident_date) && $accident_date != '0000-00-00') {
    $Date_of_accidentdob1=$dob[0][0];
    $Date_of_accidentdob2=$dob[0][1];
    $Date_of_accidentdob3=$dob[0][2];
    $Date_of_accidentdob4=$dob[0][3];
    $Date_of_accidentdob5=$dob[0][5];
    $Date_of_accidentdob6=$dob[0][6];
    $Date_of_accidentdob7=$dob[0][8];
    $Date_of_accidentdob8=$dob[0][9];
  }

  $shift_start_date = $get_field_level['shift_start_date'];
  $dob[0] = str_split($shift_start_date);
  if(!empty($shift_start_date) && $shift_start_date != '0000-00-00') {
    $Date_scheduled_shift_starteddob1=$dob[0][0];
    $Date_scheduled_shift_starteddob2=$dob[0][1];
    $Date_scheduled_shift_starteddob3=$dob[0][2];
    $Date_scheduled_shift_starteddob4=$dob[0][3];
    $Date_scheduled_shift_starteddob5=$dob[0][5];
    $Date_scheduled_shift_starteddob6=$dob[0][6];
    $Date_scheduled_shift_starteddob7=$dob[0][8];
    $Date_scheduled_shift_starteddob8=$dob[0][9];
  }

  $shift_end_date = $get_field_level['shift_end_date'];
  $dob[0] = str_split($shift_end_date);
  if(!empty($shift_end_date) && $shift_end_date != '0000-00-00') {
    $Date_scheduled_shift_endedddob1=$dob[0][0];
    $Date_scheduled_shift_endedddob2=$dob[0][1];
    $Date_scheduled_shift_endedddob3=$dob[0][2];
    $Date_scheduled_shift_endedddob4=$dob[0][3];
    $Date_scheduled_shift_endedddob5=$dob[0][5];
    $Date_scheduled_shift_endedddob6=$dob[0][6];
    $Date_scheduled_shift_endedddob7=$dob[0][8];
    $Date_scheduled_shift_endedddob8=$dob[0][9];
  }

  $injury_overtime = $get_field_level['injury_overtime'];
  if($injury_overtime == 'Overtime injury') {
    $injury_overtime_x = "X";
  }

  $accident_reported_date = $get_field_level['accident_reported_date'];
  $dob[0] = str_split($accident_reported_date);
  if(!empty($accident_reported_date) && $accident_reported_date != '0000-00-00') {
    $Date_injury_reported_to_employerdob1=$dob[0][0];
    $Date_injury_reported_to_employerdob2=$dob[0][1];
    $Date_injury_reported_to_employerdob3=$dob[0][2];
    $Date_injury_reported_to_employerdob4=$dob[0][3];
    $Date_injury_reported_to_employerdob5=$dob[0][5];
    $Date_injury_reported_to_employerdob6=$dob[0][6];
    $Date_injury_reported_to_employerdob7=$dob[0][8];
    $Date_injury_reported_to_employerdob8=$dob[0][9];
  }

  $accident_time = $get_field_level['accident_time'];
  $dob[0] = str_split($accident_time);
  if(!empty($accident_time) && $accident_time != '00:00 am') {
    $accident_time1=$dob[0][0];
    $accident_time2=$dob[0][1];
    $accident_time3=$dob[0][3];
    $accident_time4=$dob[0][4];
    $accident_time_am="";
    $accident_time_pm="";
    if($dob[0][6].$dob[0][7] == 'am') {
      $accident_time_am="X";
    }
    else if($dob[0][6].$dob[0][7] == 'pm') {
      $accident_time_pm="X";
    }
  }

  $shift_start_time = $get_field_level['shift_start_time'];
  $dob[0] = str_split($shift_start_time);
  if(!empty($shift_start_time) && $shift_start_time != '00:00 am') {
    $shift_start_time1=$dob[0][0];
    $shift_start_time2=$dob[0][1];
    $shift_start_time3=$dob[0][3];
    $shift_start_time4=$dob[0][4];
    $shift_start_time_am="";
    $shift_start_time_pm="";
    if($dob[0][6].$dob[0][7] == 'am') {
      $shift_start_time_am="X";
    }
    else if($dob[0][6].$dob[0][7] == 'pm') {
      $shift_start_time_pm="X";
    }
  }

  $shift_end_time = $get_field_level['shift_end_time'];
  $dob[0] = str_split($shift_end_time);
  if(!empty($shift_end_time) && $shift_end_time != '00:00 am') {
    $shift_end_time1=$dob[0][0];
    $shift_end_time2=$dob[0][1];
    $shift_end_time3=$dob[0][3];
    $shift_end_time4=$dob[0][4];
    $shift_end_time_am="";
    $shift_end_time_pm="";
    if($dob[0][6].$dob[0][7] == 'am') {
      $shift_end_time_am="X";
    }
    else if($dob[0][6].$dob[0][7] == 'pm') {
      $shift_end_time_pm="X";
    }
  }

  $report_phone_number = 'test';
  $accident_reported = 'test';
  $detail1 = $get_field_level['detail1'];
  $detail2 = $get_field_level['detail2'];
  $detail3 = $get_field_level['detail3'];

  $motor_yes="";
  $motor_no="";
  $motor = $get_field_level['motor'];
  if($motor == 'yes') {
    $motor_yes="X";
  }
  if($motor == 'no') {
    $motor_no="X";
  }

  $injury_type = $get_field_level['type_of_injury'];

  $claim_date = $get_field_level['claim_date'];
  $dob[0] = str_split($claim_date);
  if(!empty($claim_date) && $claim_date != '0000-00-00') {
    $claim_date1=$dob[0][0];
    $claim_date2=$dob[0][1];
    $claim_date3=$dob[0][2];
    $claim_date4=$dob[0][3];
    $claim_date5=$dob[0][5];
    $claim_date6=$dob[0][6];
    $claim_date7=$dob[0][8];
    $claim_date8=$dob[0][9];
  }

  $cardiac_injury_yes="";
  $cardiac_injury_no="";
  $cardiac_injury = $get_field_level['cardiac_injury'];
  if($cardiac_injury == 'yes') {
    $cardiac_injury_yes="X";
  }
  if($gender == 'no') {
    $cardiac_injury_no="X";
  }

  $$letter_attached_yesm="";
  $letter_attached_no="";
  $attach_letter = $get_field_level['attach_letter'];
  if($attach_letter == 'yes') {
    $letter_attached_yes="X";
  }
  if($attach_letter == 'no') {
    $letter_attached_no="X";
  }

  $accident_injury_yes="";
  $accident_injury_no="";
  $employer_premises = $get_field_level['employer_premises'];
  if($employer_premises == 'yes') {
    $accident_injury_yes="X";
  }
  if($employer_premises == 'no') {
    $accident_injury_no="X";
  }

  $location = $get_field_level['location'];

  $purpose_of_business_yes="";
  $purpose_of_business_no="";
  $purpose_of_business = $get_field_level['purpose_of_business'];
  if($purpose_of_business == 'yes') {
    $purpose_of_business_yes="X";
  }
  if($purpose_of_business == 'no') {
    $purpose_of_business_no="X";
  }

  $regular_duties_yes="";
  $regular_duties_no="";
  $regular_duties = $get_field_level['regular_duties'];
  if($regular_duties == 'yes') {
    $regular_duties_yes="X";
  }
  if($regular_duties == 'no') {
    $regular_duties_no="X";
  }

  $right_side="";
  $left_side="";
  $body_type = $get_field_level['body_type'];
  if($body_type == 'right') {
    $right_side="X";
  }
  if($body_type == 'left') {
    $left_side="X";
  }

  $sign_image = '<img style="height: 20px;" src="injury_report/download/sign_'.$fieldlevelriskid.'.png">';

  $worker_first_name = $get_field_level['worker_last_name'];
  $worker_last_name = $get_field_level['worker_first_name'];
  $worker_initial = $get_field_level['worker_initial'];
  $employer_soc1 = $get_field_level['worker_soc1'];
  $employer_soc2 = $get_field_level['worker_soc2'];
  $employer_soc3 = $get_field_level['worker_soc3'];
  $employer_soc4 = $get_field_level['worker_soc4'];
  $employer_soc5 = $get_field_level['worker_soc5'];
  $employer_soc6 = $get_field_level['worker_soc6'];
  $employer_soc7 = $get_field_level['worker_soc7'];
  $employer_soc8 = $get_field_level['worker_soc8'];
  $employer_soc9 = $get_field_level['worker_soc9'];

  $worker_dob = $get_field_level['worker_dob'];
  $dob[0] = str_split($worker_dob);
  if(!empty($worker_dob) && $work_dob != '0000-00-00') {
    $dob1=$dob[0][0];
    $dob2=$dob[0][1];
    $dob3=$dob[0][2];
    $dob4=$dob[0][3];
    $dob5=$dob[0][5];
    $dob6=$dob[0][6];
    $dob7=$dob[0][8];
    $dob8=$dob[0][9];
  }

  $regular_pay_yes = '';
  $regular_pay_no = '';
  $regular_pay = $get_field_level['regular_pay'];
  if($regular_pay == 'yes') {
    $regular_pay_yes="X";
  }
  else if($regular_pay == 'no') {
    $regular_pay_no="X";
  }

  $returned_work_yes="";
  $returned_work_no="";
  $has_returned = $get_field_level['has_returned'];
  if($has_returned == 'yes') {
    $returned_work_yes="X";
  }
  else if($has_returned == 'no') {
    $returned_work_no="X";
  }

  $missed_work_date = $get_field_level['missed_work_date'];
  $dob[0] = str_split($missed_work_date);
  if(!empty($missed_work_date) && $missed_work_date != '0000-00-00') {
    $dob_first_work1=$dob[0][0];
    $dob_first_work2=$dob[0][1];
    $dob_first_work3=$dob[0][2];
    $dob_first_work4=$dob[0][3];
    $dob_first_work5=$dob[0][5];
    $dob_first_work6=$dob[0][6];
    $dob_first_work7=$dob[0][8];
    $dob_first_work8=$dob[0][9];
  }

  $missed_work_time = $get_field_level['missed_work_time'];
  $dob[0] = str_split($missed_work_time);
  if(!empty($missed_work_time) && $missed_work_time != '00:00 am') {
    $Time1=$dob[0][0];
    $Time2=$dob[0][1];
    $Time3=$dob[0][3];
    $Time4=$dob[0][4];
    $am="";
    $pm="";
    if($dob[0][6].$dob[0][7] == 'am') {
      $am="X";
    }
    else if($dob[0][6].$dob[0][7] == 'pm') {
      $pm="X";
    }
  }


  $return_work_date = $get_field_level['return_work_date'];
  $dob[0] = str_split($return_work_date);
  if(!empty($return_work_date) && $return_work_date != '0000-00-00') {
    $indicate_date1=$dob[0][0];
    $indicate_date2=$dob[0][1];
    $indicate_date3=$dob[0][2];
    $indicate_date4=$dob[0][3];
    $indicate_date5=$dob[0][5];
    $indicate_date6=$dob[0][6];
    $indicate_date7=$dob[0][8];
    $indicate_date8=$dob[0][9];
  }

  $return_work_time = $get_field_level['return_work_time'];
  $dob[0] = str_split($return_work_time);
  if(!empty($return_work_time) && $return_work_time != '00:00 am') {
    $indicate_Time1=$dob[0][0];
    $indicate_Time2=$dob[0][1];
    $indicate_Time3=$dob[0][3];
    $indicate_Time4=$dob[0][4];
    $indicate_am="";
    $indicate_pm="";
    if($dob[0][6].$dob[0][7] == 'am') {
      $indicate_am="X";
    }
    else if($dob[0][6].$dob[0][7] == 'pm') {
      $indicate_pm="X";
    }
  }

  $accommodate_return_yes = '';
  $accommodate_return_no = '';
  $accommodate_return_offered = '';
  $accommodate = $get_field_level['accommodate'];
  if($accommodate == 'yes') {
    $accommodate_return_yes="X";
  } else if($accommodate == 'was_offered') {
    $accommodate_return_offered = "X";
  } else if($accommodate == 'no') {
    $accommodate_return_no="X";
  }


  $approx_return_date = $get_field_level['approx_return_date'];
  $dob[0] = str_split($approx_return_date);
  if(!empty($approx_return_date) && $approx_return_date != '0000-00-00') {
    $Approximate_date1=$dob[0][0];
    $Approximate_date2=$dob[0][1];
    $Approximate_date3=$dob[0][2];
    $Approximate_date4=$dob[0][3];
    $Approximate_date5=$dob[0][5];
    $Approximate_date6=$dob[0][6];
    $Approximate_date7=$dob[0][8];
    $Approximate_date8=$dob[0][9];
  }

  $position_company_yes="";
  $position_company_no="";
  $position = $get_field_level['position_number'];
  if($position == 'yes') {
    $position_company_yes="X";
  }
  else if($position == 'no') {
    $position_company_no="X";
  }


  $position_start_date = $get_field_level['position_start_date'];
  $dob[0] = str_split($position_start_date);
  if(!empty($position_start_date) && $position_start_date != '0000-00-00') {
    $Position_date1=$dob[0][0];
    $Position_date2=$dob[0][1];
    $Position_date3=$dob[0][2];
    $Position_date4=$dob[0][3];
    $Position_date5=$dob[0][5];
    $Position_date6=$dob[0][6];
    $Position_date7=$dob[0][8];
    $Position_date8=$dob[0][9];
  }


  $position_end_date = $get_field_level['position_end_date'];
  $dob[0] = str_split($position_end_date);
  if(!empty($position_end_date) && $position_start_date != '0000-00-00') {
    $Position_end_date1=$dob[0][0];
    $Position_end_date2=$dob[0][1];
    $Position_end_date3=$dob[0][2];
    $Position_end_date4=$dob[0][3];
    $Position_end_date5=$dob[0][5];
    $Position_end_date6=$dob[0][6];
    $Position_end_date7=$dob[0][8];
    $Position_end_date8=$dob[0][9];
  }


  $Estimated="";
  $Actual="";
  $position = $get_field_level['position_type'];
  if($position == 'yes') {
    $Estimated="X";
  }
  else if($position == 'no') {
    $Actual="X";
  }

  $alternate_employment = $get_field_level['alternate_employment'];
  $Sub_contractor="";
  $Piece_work="";
  $Vehicle_owner="";
  $Welder_owner="";
  $Self_employed="";
  $Volunteer="";
  $Commission="";
  $Other="";
  if($alternate_employment == 'Sub contractor')
      $Sub_contractor="X";
  if($alternate_employment == 'Piece work')
      $Piece_work="X";
  if($alternate_employment == 'Vehicle owner/operator')
      $Vehicle_owner="X";
  if($alternate_employment == 'Welder owner/operator')
      $Welder_owner="X";
  if($alternate_employment == 'Self-employed')
      $Self_employed="X";
  if($alternate_employment == 'Volunteer')
      $Volunteer="X";
  if($alternate_employment == 'Commission')
      $Commission="X";
  if($alternate_employment == 'Other')
      $Other="X";

  $expenses_perform_work_yes="";
  $expenses_perform_work_no="";
  $incur_expense = $get_field_level['incur_expense'];
  if($incur_expense == 'yes') {
    $expenses_perform_work_yes="X";
  }
  else if($incur_expense == 'no') {
    $expenses_perform_work_no="X";
  }

  $worker_receive_T4="";
  $worker_receive_T4_no="";
  $receive_t4 = $get_field_level['receive_t4'];
  if($receive_t4 == 'yes') {
    $worker_receive_T4 = "X";
  } else if($receive_t4 == 'no') {
    $worker_receive_T4_no = "X";
  }

  $Earnings_contact_name=$get_field_level['earning_contact_name'];
  $Earnings_contact_number=$get_field_level['earning_contact_phone'];
  $Earnings_contact_email=$get_field_level['earning_contact_email'];


  $gross_start_date = $get_field_level['gross_start_date'];
  $dob[0] = str_split($gross_start_date);
  if(!empty($gross_start_date) && $gross_start_date != '0000-00-00') {
    $injury_dob1=$dob[0][0];
    $injury_dob2=$dob[0][1];
    $injury_dob3=$dob[0][2];
    $injury_dob4=$dob[0][3];
    $injury_dob5=$dob[0][5];
    $injury_dob6=$dob[0][6];
    $injury_dob7=$dob[0][8];
    $injury_dob8=$dob[0][9];
  }

  $gross_to_date = $get_field_level['gross_end_date'];
  $dob[0] = str_split($gross_to_date);
  if(!empty($gross_to_date) && $gross_to_date != '0000-00-00') {
    $injury_to_dob1=$dob[0][0];
    $injury_to_dob2=$dob[0][1];
    $injury_to_dob3=$dob[0][2];
    $injury_to_dob4=$dob[0][3];
    $injury_to_dob5=$dob[0][5];
    $injury_to_dob6=$dob[0][6];
    $injury_to_dob7=$dob[0][8];
    $injury_to_dob8=$dob[0][9];
  }

  $excluding_vacation_yes="";
  $excluding_vacation_no="";
  $time_missed = $get_field_level['time_missed'];
  if($time_missed == 'yes') {
    $excluding_vacation_yes="X";
  }
  else if($time_missed == 'no') {
    $excluding_vacation_no="X";
  }

  $Dates=$get_field_level['date_n_reason'];
  $reasons="";

  $Vacation_pay="";
  $Taken_with_pay="";
  $time_missed = $get_field_level['vacation_pay'];
  if($time_missed == 'Taken as time off with pay') {
    $Vacation_pay="X";
  }
  else if($time_missed == 'Paid on a regular basis') {
    $Taken_with_pay="X";
  }

  $Paid_regular_basis=$get_field_level['vacation_pay_amount'];

  $Gross_earnings=$get_field_level['shift_earning'];
  $Overtime_Gross_earnings=$get_field_level['overtime_earning'];
  $Other_Gross_earnings=$get_field_level['other_earning'];


  $shift_earning_start_date = $get_field_level['shift_earning_start_date'];
  $dob[0] = str_split($shift_earning_start_date);
  if(!empty($shift_earning_start_date) && $shift_earning_start_date != '0000-00-00') {
    $Gross_earnings_dob1=$dob[0][0];
    $Gross_earnings_dob2=$dob[0][1];
    $Gross_earnings_dob3=$dob[0][2];
    $Gross_earnings_dob4=$dob[0][3];
    $Gross_earnings_dob5=$dob[0][5];
    $Gross_earnings_dob6=$dob[0][6];
    $Gross_earnings_dob7=$dob[0][8];
    $Gross_earnings_dob8=$dob[0][9];
  }

  $shift_earning_end_date = $get_field_level['shift_earning_end_date'];
  $dob[0] = str_split($shift_earning_end_date);
  if(!empty($shift_earning_end_date) && $shift_earning_end_date != '0000-00-00') {
    $Gross_earnings_to_dob1=$dob[0][0];
    $Gross_earnings_to_dob2=$dob[0][1];
    $Gross_earnings_to_dob3=$dob[0][2];
    $Gross_earnings_to_dob4=$dob[0][3];
    $Gross_earnings_to_dob5=$dob[0][5];
    $Gross_earnings_to_dob6=$dob[0][6];
    $Gross_earnings_to_dob7=$dob[0][8];
    $Gross_earnings_to_dob8=$dob[0][9];
  }

  $overtime_start_date = $get_field_level['overtime_start_date'];
  $dob[0] = str_split($overtime_start_date);
  if(!empty($overtime_start_date) && $overtime_start_date != '0000-00-00') {
    $Overtime_Gross_earnings_dob1=$dob[0][0];
    $Overtime_Gross_earnings_dob2=$dob[0][1];
    $Overtime_Gross_earnings_dob3=$dob[0][2];
    $Overtime_Gross_earnings_dob4=$dob[0][3];
    $Overtime_Gross_earnings_dob5=$dob[0][5];
    $Overtime_Gross_earnings_dob6=$dob[0][6];
    $Overtime_Gross_earnings_dob7=$dob[0][8];
    $Overtime_Gross_earnings_dob8=$dob[0][9];
  }


  $overtime_end_date = $get_field_level['overtime_end_date'];
  $dob[0] = str_split($overtime_end_date);
  if(!empty($overtime_end_date) && $overtime_end_date != '0000-00-00') {
    $Overtime_Gross_earnings_to_dob1=$dob[0][0];
    $Overtime_Gross_earnings_to_dob2=$dob[0][1];
    $Overtime_Gross_earnings_to_dob3=$dob[0][2];
    $Overtime_Gross_earnings_to_dob4=$dob[0][3];
    $Overtime_Gross_earnings_to_dob5=$dob[0][5];
    $Overtime_Gross_earnings_to_dob6=$dob[0][6];
    $Overtime_Gross_earnings_to_dob7=$dob[0][8];
    $Overtime_Gross_earnings_to_dob8=$dob[0][9];
  }

  $other_start_date = $get_field_level['other_start_date'];
  $dob[0] = str_split($other_start_date);
  if(!empty($other_start_date) && $other_start_date != '0000-00-00') {
    $Other_Gross_earnings_dob1=$dob[0][0];
    $Other_Gross_earnings_dob2=$dob[0][1];
    $Other_Gross_earnings_dob3=$dob[0][2];
    $Other_Gross_earnings_dob4=$dob[0][3];
    $Other_Gross_earnings_dob5=$dob[0][5];
    $Other_Gross_earnings_dob6=$dob[0][6];
    $Other_Gross_earnings_dob7=$dob[0][8];
    $Other_Gross_earnings_dob8=$dob[0][9];
  }

  $other_end_date = $get_field_level['other_end_date'];
  $dob[0] = str_split($other_end_date);
  if(!empty($other_end_date) && $other_end_date != '0000-00-00') {
    $Other_Gross_earnings_to_dob1=$dob[0][0];
    $Other_Gross_earnings_to_dob2=$dob[0][1];
    $Other_Gross_earnings_to_dob3=$dob[0][2];
    $Other_Gross_earnings_to_dob4=$dob[0][3];
    $Other_Gross_earnings_to_dob5=$dob[0][5];
    $Other_Gross_earnings_to_dob6=$dob[0][6];
    $Other_Gross_earnings_to_dob7=$dob[0][8];
    $Other_Gross_earnings_to_dob8=$dob[0][9];
  }

  $Number_hours=$get_field_level['number_of_hours'];


  $Per="";
  $Day="";
  $Week="";
  $Shift_cycle="";
  $number_of_hours_per = $get_field_level['number_of_hours_per'];
  if($number_of_hours_per == 'Day') {
    $Per="X";
  }
  if($number_of_hours_per == 'Week') {
    $Day="X";
  }
  if($number_of_hours_per == 'Shift cycle') {
    $Week="X";
  }
  if($number_of_hours_per == 'Other') {
    $Shift_cycle="X";
  }
  $number_of_hours_other = $get_field_level['number_of_hours_other'];


  $commenced = $get_field_level['commenced'];
  $dob[0] = str_split($commenced);
  if(!empty($commenced) && $commenced != '0000-00-00') {
    $shift_cycle_commenced_dob1=$dob[0][0];
    $shift_cycle_commenced_dob2=$dob[0][1];
    $shift_cycle_commenced_dob3=$dob[0][2];
    $shift_cycle_commenced_dob4=$dob[0][3];
    $shift_cycle_commenced_dob5=$dob[0][5];
    $shift_cycle_commenced_dob6=$dob[0][6];
    $shift_cycle_commenced_dob7=$dob[0][8];
    $shift_cycle_commenced_dob8=$dob[0][9];
  }

  $no="";
  $yes="";
  $repeat = $get_field_level['repeat'];
  if($repeat == 'No') {
    $no="X";
  }
  if($repeat == 'Yes') {
    $yes="X";
  }

  $per_week=$get_field_level['regular_repeat_hours'];

    $Current_work_status="";
    $Modified_work_duties="";
    $Regular_hours_of_work="";
    $Modified_hours_of_work="";
    $Pre_accident_rate_of_pay="";
    $Revised_rate_of_pay="";
    $Regular_work_duties="";

    if($get_field_level['current_work_status'] == 'Regular work duties') {
      $Regular_work_duties="X";
    }
    if($get_field_level['current_work_status'] == 'Modified work duties') {
      $Modified_work_duties="X";
    }
    if($get_field_level['current_work_status_hours'] == 'Regular hours of work, or') {
      $Regular_hours_of_work="X";
    }
    if($get_field_level['current_work_status_hours'] == 'Modified hours of work') {
      $Modified_hours_of_work="X";
    }
    if($get_field_level['current_work_status_rate'] == 'Pre-accident rate of pay, or') {
      $Pre_accident_rate_of_pay="X";
    }
    if($get_field_level['current_work_status_rate'] == 'Revised rate of pay') {
      $Revised_rate_of_pay="X";
    }

    $Modified_hours_of_work_hrs=$get_field_level['modified_hours'];
    $Modified_hours_of_work_hrs_per=$get_field_level['modified_per'];;
    $Revised_rate_of_pay1=$get_field_level['regular_hours'];;
    $Revised_rate_of_pay_per=$get_field_level['regular_per'];;

  $working_modified_duties=$get_field_level['modified_duties'];


  $A_Permanent_position="";
  $B_nonPermanent_position="";
  $employment_type = $get_field_level['employment_type'];
  if($employment_type == 'permanent') {
    $A_Permanent_position="X";
  }
  if($employment_type == 'non-permanent') {
    $B_nonPermanent_position="X";
  }

  $part_time="";
  $full_time="";
  $regulare_time="";
  $permanent_type = $get_field_level['permanent_type'];
  if($permanent_type == 'Full Time') {
    $full_time="X";
  }
  if($permanent_type == 'Part Time') {
    $part_time="X";
  }
  if($permanent_type == 'Irregular/Casual') {
    $regulare_time="X";
  }


  $Seasonal_worker="";
  $Summer_Student="";
  $Temporary="";
  $non_permanent_type = $get_field_level['non_permanent_type'];
  if($non_permanent_type == 'Seasonal worker') {
    $Seasonal_worker="X";
  }
  if($non_permanent_type == 'Summer Student') {
    $Summer_Student="X";
  }
  if($non_permanent_type == 'Temporary') {
    $Temporary="X";
  }

  $number_of_months = $get_field_level['number_of_months'];
  $Gross_earnings_year=$get_field_level['gross_earning'];
  $woker_hourly_pay_of_accident=$get_field_level['hourly_rate'];

  $Hours_per_day_sun1=$get_field_level['sun1'];
  $Hours_per_day_mon1=$get_field_level['mon1'];
  $Hours_per_day_tue1=$get_field_level['tue1'];
  $Hours_per_day_wed1=$get_field_level['wed1'];
  $Hours_per_day_thu1=$get_field_level['thu1'];
  $Hours_per_day_fri1=$get_field_level['fri1'];
  $Hours_per_day_sat1=$get_field_level['sat1'];

  $Hours_per_day_sun2=$get_field_level['sun2'];
  $Hours_per_day_mon2=$get_field_level['mon2'];
  $Hours_per_day_tue2=$get_field_level['tue2'];
  $Hours_per_day_wed2=$get_field_level['wed2'];
  $Hours_per_day_thu2=$get_field_level['thu2'];
  $Hours_per_day_fri2=$get_field_level['fri2'];
  $Hours_per_day_sat2=$get_field_level['sat2'];

  $Hours_per_day_sun3=$get_field_level['sun3'];
  $Hours_per_day_mon3=$get_field_level['mon3'];
  $Hours_per_day_tue3=$get_field_level['tue3'];
  $Hours_per_day_wed3=$get_field_level['wed3'];
  $Hours_per_day_thu3=$get_field_level['thu3'];
  $Hours_per_day_fri3=$get_field_level['fri3'];
  $Hours_per_day_sat3=$get_field_level['sat3'];











  $html = '<table><tr><td>P.O. BOX 2415</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 69, 5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>EDMONTON AB T5J 2S5</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 69, 9, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td><b>Phone</b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 69, 13, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td><b>780-498-3999 </b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 81, 13, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>(in Edmonton)</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 100, 13, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td><b>1-866-922-9221</b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 81, 16, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>(toll free in Alberta)</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 102, 16, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td><b>1-800-661-9608</b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 81, 19, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>(outside Alberta)</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 102, 19, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td><b>Fax</b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 69, 24, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td><b>780-427-5863</b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 81, 24, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>or</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 99, 24, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td><b>1-800-661-1993</b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 102, 24, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Seven Digit Claim # (if available):</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 127, 20.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$claim_number.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 170, 20.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td style="font-size:10px;"><b>September 2014</b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 178, 4, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td style="font-size:18px;"><b>EMPLOYER REPORT</b></td></tr></table>';
  $pdf->writeHTMLCell(80, 50, 142, 8, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td style="font-size:12px;"><b>of Injury</b></td></tr></table>';
  $pdf->writeHTMLCell(80, 75, 142, 14, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td style="font-size:8px;"><b>C040</b></td></tr></table>';
  $pdf->writeHTMLCell(80, 75, 199, 14, $html, 0, 0, false, true, 'L', true);

  $html = '<table style="font-size:11.5px;"><tr><td><b>Claim Type</b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 2, 31.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table style="font-size:11.5px;"><tr><td><b><font color="white">1</font></b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 36.5, 31.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td><b>Time Lost</b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 49, 31.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Time_Lost.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 44.6, 31.9, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td><b>Modified Work</b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 76, 31.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Modified_Work.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 71.8, 31.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td><b>Fatality</b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 109, 31.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Fatality.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 104.4, 31.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table style="font-size:7px;width:100%"><tr><td>Complete entire report if claim type is one of the above</td></tr></table>';
  $pdf->writeHTMLCell(70, 50, 42.7, 35.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td><b>No Time Lost (Notice of non-disabling injury/illness)</b></td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 136, 31.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$No_Time_Lost.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 131.5, 31.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table style="font-size:7px;width:100%"><tr><td>Complete first page only</td></tr></table>';
  $pdf->writeHTMLCell(70, 50, 150, 35.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td style="font-size:10px;padding-left: 80px;"><b>Worker Details</b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 2, 42.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Last Name:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 2, 49.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Last_name.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 19, 49.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>First Name:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 101, 49.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$First_name.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 118, 49.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Initial:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 195, 49.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Initial.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 205, 49.5, $html, 0, 0, false, true, 'L', true);


  $html = '<table><tr><td>Mailing Address: Apt# _____ ,</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 2, 56.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$mailing_address.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 32, 56.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Social Insurance #:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 125, 56.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Social_Insurance1.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 154, 57.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Social_Insurance2.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 161, 57.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Social_Insurance3.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 167, 57.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Social_Insurance4.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 174, 57.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Social_Insurance5.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 181, 57.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Social_Insurance6.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 187, 57.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Social_Insurance7.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 193, 57.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Social_Insurance8.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 200, 57.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Social_Insurance9.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 206, 57.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>City:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 2, 62.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$City.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 9.5, 62.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Province:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 56, 62.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Province.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 69, 62.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Postal Code:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 85, 62.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Postal_Code.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 102.4, 62.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Personal Health #:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 125, 63.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Personal_Health1.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 157, 63.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Personal_Health2.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 163, 63.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Personal_Health3.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 168, 63.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Personal_Health4.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 174, 63.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Personal_Health5.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 180, 63.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Personal_Health6.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 190, 63.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Personal_Health7.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 196, 63.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Personal_Health8.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 201, 63.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Personal_Health9.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 207, 63.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Phone Number:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 2, 69.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Phone_number.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 23, 69.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Date of Birth:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 125, 69.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$dobb1.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 144, 70, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$dobb2.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 150, 70, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$dobb3.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 154, 70, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$dobb4.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 159, 70, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$dobb5.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 164, 70, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$dobb6.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 169, 70, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$dobb7.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 175, 70, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$dobb8.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 180, 70, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td style="font-size:5px;">(Year / Month / Day)</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 160, 68, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>Gender:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 186, 69.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>M</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 202, 69.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$m.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 196.5, 69.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>F</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 211, 69.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$f.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 206, 69.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Occupation:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 2, 76.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Occupation.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 18, 76.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Job description:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 56, 76.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Job_description.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 79, 76.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Date hired:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 148, 76.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Date_hired1.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 165, 77.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_hired2.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 171, 77.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_hired3.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 178, 77.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_hired4.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 183, 77.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_hired5.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 189, 77.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_hired6.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 195, 77.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_hired7.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 201, 77.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_hired8.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 207, 77.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td style="font-size:5px;">(Year / Month / Day)</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 182, 74.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Does the worker have WCB personal coverage with this business?</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 2, 82.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Yes</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 93, 82.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$WCB_personal_business_yes.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 89, 82.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>No</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 106, 82.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$WCB_personal_business_no.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 101.3, 82.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Is the worker a partner or director in this business?</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 115.5, 82.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Yes</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 186, 82.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$director_in_this_business_yes.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 180.6, 82.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>No</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 198, 82.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$director_in_this_business_no.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 193, 82.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Is the worker an apprentice?</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 2, 89.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>Yes</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 46, 89.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$apprentice_yes.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 41.8, 89.1, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>No</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 58, 89.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$apprentice_no.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 54, 89.1, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>If yes, date the worker would have obtained journeyman status:</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 80, 89.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td style="font-size:5px;">(Year / Month / Day)</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 180, 88.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$journeyman_status1.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 165, 90.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$journeyman_status2.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 171, 90.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$journeyman_status3.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 178, 90.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$journeyman_status4.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 183, 90.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$journeyman_status5.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 189, 90.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$journeyman_status6.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 195, 90.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$journeyman_status7.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 201, 90.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$journeyman_status8.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 207, 90.5, $html, 0, 0, false, true, 'L', true);


  $html = '<table><tr><td style="font-size:10px;"><b>Employer Details</b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 2, 96.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>Business Name or Government Department:</td></tr></table>';
  $pdf->writeHTMLCell(70, 50, 2, 102.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$business_name.'</td></tr></table>';
  $pdf->writeHTMLCell(70, 50, 2, 106, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>WCB Account Number:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 104, 103.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$wcb_account_number.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 140, 103.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Industry:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 170, 103.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$WCB_Account_Number1.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 184, 103.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$WCB_Account_Number2.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 191, 103.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$WCB_Account_Number3.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 197, 103.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$WCB_Account_Number4.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 203, 103.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$WCB_Account_Number5.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 209, 103.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Mailing Address:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 2, 114.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Mailing_Address.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 25, 114.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>City:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 2, 121, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$City1.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 9.5, 121, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Province:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 2, 127.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Province1.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 18, 127.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Postal Code:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 52, 127.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Postal_Code1.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 70, 127.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Phone:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 2, 134.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Phone1.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 12, 134.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Fax:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 52, 134.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Fax1.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 60, 134.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Employer/Supervisor Contact Name and Title:</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 108, 110.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td style="color:white;font-size:11.5px">2</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 103, 109.2, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$employer_contact_name.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 108, 115, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Contact Phone:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 104, 127.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Contact_Phone1.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 124, 127.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Contact E-mail:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 104, 134.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Contact_Email1.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 124, 134.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td style="font-size:10px;"><b>Accident Details</b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 2, 141, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td style="color:white;font-size:11.5px"><b>3</b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 3.5, 147.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Date/time of accident:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 9, 147.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_of_accidentdob1.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 63.6, 149.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_of_accidentdob2.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 70, 149.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_of_accidentdob3.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 76, 149.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_of_accidentdob4.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 82, 149.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_of_accidentdob5.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 88, 149.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_of_accidentdob6.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 93, 149.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_of_accidentdob7.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 99, 149.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_of_accidentdob8.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 105, 149.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td  style="font-size:5px;">(Year / Month / Day)</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 78, 146.8, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>Date/time scheduled shift started: </td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 9, 154, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td  style="font-size:5px;">(Year / Month / Day)</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 78, 152.8, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_scheduled_shift_starteddob1.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 63.6, 155.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_scheduled_shift_starteddob2.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 70, 155.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_scheduled_shift_starteddob3.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 76, 155.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_scheduled_shift_starteddob4.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 82, 155.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_scheduled_shift_starteddob5.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 88, 155.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_scheduled_shift_starteddob6.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 93, 155.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_scheduled_shift_starteddob7.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 99, 155.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_scheduled_shift_starteddob8.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 105, 155.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Date/time scheduled shift ended: </td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 9, 161, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td  style="font-size:5px;">(Year / Month / Day)</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 78, 165.8, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_scheduled_shift_endedddob1.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 63.6, 162.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_scheduled_shift_endedddob2.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 70, 162.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_scheduled_shift_endedddob3.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 76, 162.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_scheduled_shift_endedddob4.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 82, 162.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_scheduled_shift_endedddob5.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 88, 162.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_scheduled_shift_endedddob6.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 93, 162.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_scheduled_shift_endedddob7.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 99, 162.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_scheduled_shift_endedddob8.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 105, 162.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Date accident/injury reported to employer:</td></tr></table>';
  $pdf->writeHTMLCell(70, 50, 9, 167, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td  style="font-size:5px;">(Year / Month / Day)</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 78, 159.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_injury_reported_to_employerdob1.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 63.6, 168.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_injury_reported_to_employerdob2.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 70, 168.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_injury_reported_to_employerdob3.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 76, 168.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_injury_reported_to_employerdob4.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 82, 168.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_injury_reported_to_employerdob5.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 88, 168.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_injury_reported_to_employerdob6.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 93, 168.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_injury_reported_to_employerdob7.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 99, 168.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$Date_injury_reported_to_employerdob8.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 105, 168.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Time:    __ __ : __ __  </td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 113, 148.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$accident_time1.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 122, 148.2, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$accident_time2.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 125.5, 148.2, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$accident_time3.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 131, 148.2, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$accident_time4.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 135, 148.2, $html, 0, 0, false, true, 'L', true);



  $html = '<table><tr><td>a.m.</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 149, 148, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$accident_time_am.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 145.5, 148, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>p.m.</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 161, 148, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$accident_time_pm.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 157, 148, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Time:    __ __ : __ __  </td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 113, 155, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$shift_start_time1.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 122, 155, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$shift_start_time2.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 125.5, 155, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$shift_start_time3.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 131, 155, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$shift_start_time4.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 135, 155, $html, 0, 0, false, true, 'L', true);


  $html = '<table><tr><td>a.m.</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 149, 154.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$shift_start_time_am.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 145.5, 154.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>p.m.</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 161, 154.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$shift_start_time_pm.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 157, 154.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Time:    __ __ : __ __  </td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 113, 161.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$shift_end_time1.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 122, 161.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$shift_end_time2.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 125.5, 161.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$shift_end_time3.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 131, 161.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$shift_end_time4.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 135, 161.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>a.m.</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 149, 161, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$shift_end_time_am.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 145.5, 161, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>p.m.</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 161, 161, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$shift_end_time_pm.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 157, 161, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Or</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 174, 152, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$injury_overtime_x.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 180, 153, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>the injury/condition</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 184, 152.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>developed over time</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 184, 155, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td style="color:white;font-size:11.5px"><b>4</b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 3.4, 166.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>To whom was the accident/injury reported?:</td></tr></table>';
  $pdf->writeHTMLCell(70, 50, 9, 174, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$accident_reported.'</td></tr></table>';
  $pdf->writeHTMLCell(70, 50, 65, 174, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Phone Number:</td></tr></table>';
  $pdf->writeHTMLCell(70, 50, 140, 174, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$report_phone_number.'</td></tr></table>';
  $pdf->writeHTMLCell(70, 50, 161, 174, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td style="color:white;font-size:11.5px"><b>5</b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 3.4, 180, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Describe fully, based on the information you have, what happened to cause this injury or disease. Please describe what the worker was doing, including details
  </td></tr></table>';
  $pdf->writeHTMLCell(210, 50, 9, 180, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>about any tools, equipment, materials, etc., the worker was using. State any gas, chemicals or extreme temperatures worker may have been exposed to:
  </td></tr></table>';
  $pdf->writeHTMLCell(200, 50, 9, 183.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$detail1.'</td></tr></table>';
  $pdf->writeHTMLCell(200, 50, 9, 186, $html, 0, 0, false, true, 'L', true) ;

  $html = '<table><tr><td>'.$detail2.'</td></tr></table>';
  $pdf->writeHTMLCell(200, 50, 9, 193, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$detail3.'</td></tr></table>';
  $pdf->writeHTMLCell(200, 50, 9, 199.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Motor vehicle accident?</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 9, 213.5, $html, 0, 0, false, true, 'L', true);


  $html = '<table><tr><td></td>'.$motor_yes.'</tr></table>';
  $pdf->writeHTMLCell(50, 50, 40.5, 208, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>Yes</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 45, 213.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td></td>'.$motor_no.'</tr></table>';
  $pdf->writeHTMLCell(50, 50, 52.5, 208, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>No</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 57, 213.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Cardiac condition/injury?</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 67, 213.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$cardiac_injury_yes.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 99.8, 213.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>Yes</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 104, 213.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$cardiac_injury_no.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 112.5, 213.5, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>No</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 116, 213.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>If you have more information, please attach a letter.</td></tr></table>';
  $pdf->writeHTMLCell(70, 50, 145, 207, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Letter attached?</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 145, 214, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$letter_attached_yes.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 167.5, 213.7, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>Yes</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 172, 214, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$letter_attached_no.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 180.5, 213.7, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>No</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 184, 214, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Did the accident/injury occur on employer’s premises?</td></tr></table>';
  $pdf->writeHTMLCell(78, 50, 9, 219, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$accident_injury_yes.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 117.2, 220, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>Yes</td></tr></table>';
  $pdf->writeHTMLCell(78, 50, 122, 220, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$accident_injury_no.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 134.5, 220, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>No</td></tr></table>';
  $pdf->writeHTMLCell(78, 50, 139, 220, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Location where the accident happened (address, general location or site):</td></tr></table>';
  $pdf->writeHTMLCell(120, 50, 9, 226, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$location.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 103, 226, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td style="color:white;font-size:11.5px"><b>6</b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 3.4, 226, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Were the worker’s actions at the time of injury for the purpose of your business?</td></tr></table>';
  $pdf->writeHTMLCell(110, 50, 9, 233, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$purpose_of_business_yes.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 116.8, 233, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>Yes</td></tr></table>';
  $pdf->writeHTMLCell(78, 50, 121, 233, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$purpose_of_business_no.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 134.5, 233, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>No</td></tr></table>';
  $pdf->writeHTMLCell(78, 50, 139, 233, $html, 0, 0, false, true, 'L', true);


  $html = '<table><tr><td>Were the actions part of the worker’s regular duties?</td></tr></table>';
  $pdf->writeHTMLCell(110, 50, 9, 240, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$regular_duties_yes.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 116.8, 240, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>Yes</td></tr></table>';
  $pdf->writeHTMLCell(110, 50, 121, 240, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$regular_duties_no.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 134.5, 240, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>No</td></tr></table>';
  $pdf->writeHTMLCell(110, 50, 139, 240, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td style="font-size:10px;"><b>Injury Details</b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 2, 247, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>What part of body was injured? (hand, eye, back, lungs, etc.)</td></tr></table>';
  $pdf->writeHTMLCell(80, 50, 42, 247, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$left_side.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 173.5, 247, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>Left side</td></tr></table>';
  $pdf->writeHTMLCell(80, 50, 178, 247, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$right_side.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 192.5, 247, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>Right side</td></tr></table>';
  $pdf->writeHTMLCell(80, 50, 197, 247, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>What type of injury is this? (sprain, strain, bruise, etc.)</td></tr></table>';
  $pdf->writeHTMLCell(80, 50, 9, 254, $html, 0, 0, false, true, 'L', true);


  $html = '<table><tr><td>'.$injury_type.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 78, 254, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Employer’s Signature:</td></tr></table>';
  $pdf->writeHTMLCell(80, 50, 2, 262, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$sign_image.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 30, 259, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Date:</td></tr></table>';
  $pdf->writeHTMLCell(80, 50, 155, 262, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$claim_date1.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 167, 262, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$claim_date2.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 174, 262, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$claim_date3.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 179.5, 262, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$claim_date4.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 185, 262, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$claim_date5.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 191.5, 262, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$claim_date6.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 197, 262, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$claim_date7.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 203.5, 262, $html, 0, 0, false, true, 'L', true);
  $html = '<table><tr><td>'.$claim_date8.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 209, 262, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>If you have any other information that would help us make a decision, or if you have concerns, please attach a letter.</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 66, 269, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td><b>THIS DOCUMENT MAY BE EXAMINED BY ANY PERSON WITH A DIRECT INTEREST IN A CLAIM THAT IS UNDER REVIEW OR APPEAL.</b></td></tr></table>';
  $pdf->writeHTMLCell(210, 50, 32, 272.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td><small>C-040 REV SEPT 2014</small></td></tr></table>';
  $pdf->writeHTMLCell(210, 50, 29, 276, $html, 0, 0, false, true, 'L', true);














  $pdf->AddPage();



















  $html = '<table><tr><td>EMPLOYER REPORT</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 2, 4, $html, 0, 0, false, true, 'L', true);

  $html = "<table><tr><td>Worker's Last Name:".$worker_last_name."</td></tr></table>";
  $pdf->writeHTMLCell(50, 50, 4, 9, $html, 0, 0, false, true, 'L', true);

  $html = "<table><tr><td>Worker's First Name:".$worker_first_name."</td></tr></table>";
  $pdf->writeHTMLCell(50, 50, 80, 9, $html, 0, 0, false, true, 'L', true);

  $html = "<table><tr><td>Initial:</td></tr></table>";
  $pdf->writeHTMLCell(50, 50, 190, 9, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Social Insurance #:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 4, 16, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$employer_soc1.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 33, 16, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$employer_soc2.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 40, 16, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$employer_soc3.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 47, 16, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$employer_soc4.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 53, 16, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$employer_soc5.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 59, 16, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$employer_soc6.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 66, 16, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$employer_soc7.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 73, 16, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$employer_soc8.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 79, 16, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$employer_soc9.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 86, 16, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Date of Birth:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 112, 16, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$dob1.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 133, 16, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$dob2.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 139, 16, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$dob3.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 145, 16, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$dob4.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 151, 16, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$dob5.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 157, 16, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$dob6.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 163, 16, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$dob7.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 168, 16, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$dob8.'</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 174, 16, $html, 0, 0, false, true, 'L', true);

  $html = '<table style="font-size:11.5px;color:white"><tr><td><b>7</b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 3, 22.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table style="font-size:11.5px;"><tr><td><b>Return to Work Details</b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 9, 22.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>a. Will/did you pay the worker regular pay while off work?</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 6, 29.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Yes</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 89, 29.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$regular_pay_yes.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 85, 29.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>No</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 102, 29.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$regular_pay_no.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 98, 29.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Has the worker returned to work?</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 117, 29.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Yes</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 166, 29.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$returned_work_yes.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 162.5, 29.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>No</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 179.5, 29.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$returned_work_no.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 175.5, 29.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>b. Date and time worker first missed work:</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 6, 35.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$dob_first_work1.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 75.5, 37, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$dob_first_work2.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 81.5, 37, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$dob_first_work3.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 87.5, 37, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$dob_first_work4.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 93.5, 37, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$dob_first_work5.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 99.5, 37, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$dob_first_work6.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 105.5, 37, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$dob_first_work7.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 111.5, 37, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$dob_first_work8.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 116.5, 37, $html, 0, 0, false, true, 'L', true);

  $html = '<table style="font-size:7px"><tr><td>(Year / Month / Day)</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 80, 34, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>c. If the worker has returned to work, indicate date:</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 6, 41.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Time: ___ ___ : ___ ___</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 123, 35.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Time1.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 132, 35.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Time2.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 138, 35.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Time3.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 145, 35.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Time4.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 150, 35.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>a.m.</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 174, 35.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$am.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 170, 35.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>p.m.</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 188, 35.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$pm.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 184, 35.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table style="font-size:7px"><tr><td>(Year / Month / Day)</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 80, 40, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$indicate_date1.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 75.5, 43, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$indicate_date2.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 81.5, 43, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$indicate_date3.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 87.5, 43, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$indicate_date4.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 93.5, 43, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$indicate_date5.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 99.5, 43, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$indicate_date6.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 105.5, 43, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$indicate_date7.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 111.5, 43, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$indicate_date8.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 116.5, 43, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Time: ___ ___ : ___ ___</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 123, 42, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$indicate_Time1.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 132, 42, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$indicate_Time2.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 138, 42, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$indicate_Time3.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 145, 42, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$indicate_Time4.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 150, 42, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>a.m.</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 174, 42, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$indicate_am.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 170, 42, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>p.m.</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 188, 42, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$indicate_pm.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 184, 42, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Current work status:</td></tr></table>';
  $pdf->writeHTMLCell(100, 50,12, 47.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Current_work_status.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 12, 47.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Regular work duties, or</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 44, 47.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Regular_work_duties.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 40.8, 47.8, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Modified work duties</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 80, 47.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Modified_work_duties.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 75.6, 47.8, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Regular hours of work, or</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 118	, 47.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Regular_hours_of_work.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 114.6, 47.8, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Modified hours of work: _____ hrs per _____</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 156, 47.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Modified_hours_of_work.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 152, 47.8, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Modified_hours_of_work_hrs.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 186, 47.8, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Modified_hours_of_work_hrs_per.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 203.5, 47.8, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Pre-accident rate of pay, or</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 44, 54, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Pre_accident_rate_of_pay.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 40.8, 54, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Revised rate of pay: $ ______________ per _________</td></tr></table>';
  $pdf->writeHTMLCell(105, 50, 84, 54, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Revised_rate_of_pay.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 80, 54, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Revised_rate_of_pay1.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 113, 54, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Revised_rate_of_pay_per.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 141, 54, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>If the worker is working modified duties, please describe:</td></tr></table>';
  $pdf->writeHTMLCell(100, 50,12, 60, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$working_modified_duties.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 86, 60, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>d.If the worker is not back at work are you able to modify work duties/hours to accommodate an early return?</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 6, 72.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Yes</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 146, 72.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$accommodate_return_yes.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 143, 72.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>No</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 157, 72.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$accommodate_return_no.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 153, 72.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Was offered but the worker declined</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 167.6, 72.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$accommodate_return_offered.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 164, 72.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>e. Approximate return to work date:</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 6, 78.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table style="font-size:7px"><tr><td>(Year / Month / Day)</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 60, 77, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Approximate_date1.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 56, 79.8, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Approximate_date2.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 62, 79.8, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Approximate_date3.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 67, 79.8, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Approximate_date4.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 74, 79.8, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Approximate_date5.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 80, 79.8, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Approximate_date6.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 86, 79.8, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Approximate_date7.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 91.5, 79.8, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Approximate_date8.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 97, 79.8, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Does the worker have more than one position at your company?</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 105, 78.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Yes</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 191, 78.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$position_company_yes.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 188, 78.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>No</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 206, 78.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$position_company_no.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 202.5, 78.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table style="font-size:11.5px;color:white"><tr><td><b>8</b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 2.7, 85.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table style="font-size:11.5px;"><tr><td><b>Employment Type Details</b></td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 9, 85, $html, 0, 0, false, true, 'L', true);

  $html = "<table><tr><td><b>(Complete A or B or C. Select the worker's type of employment.)</b></td></tr></table>";
  $pdf->writeHTMLCell(150, 50, 61.5, 86.2, $html, 0, 0, false, true, 'L', true);

  $html = '<table style="font-size:12.5px;"><tr><td><b>A</b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 6, 91.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>or</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 4, 98.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table style="font-size:11.5px;"><tr><td><b>B</b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 6, 97.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Permanent position employed 12 months of the year:</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 16.5, 92.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$A_Permanent_position.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 13, 92.3, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Full Time</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 92, 92.3, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$full_time.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 88, 92.3, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Part Time</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 113, 92.3, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$part_time.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 109, 92.3, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Irregular/Casual</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 135, 92.3, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$regulare_time.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 130, 92.3, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Non-permanent position employed only part of the year(subject to seasonal or lack of work layoff)</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 16.5, 98.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$B_nonPermanent_position.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 13, 98.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Seasonal worker</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 143, 98.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Seasonal_worker.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 140, 98.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Summer Student</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 171, 98.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Summer_Student.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 167, 98.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Temporary</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 199, 98.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Temporary.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 195.5, 98.5, $html, 0, 0, false, true, 'L', true);


  $html = '<table><tr><td>Position start date:</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 12, 104.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table style="font-size:7px"><tr><td>(Year / Month / Day)</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 48, 103, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Position_date1.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 41, 106, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Position_date2.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 47, 106, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Position_date3.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 53, 106, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Position_date4.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 59, 106, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Position_date5.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 65, 106, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Position_date6.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 71, 106, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Position_date7.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 77, 106, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Position_date8.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 82, 106, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Position end date:</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 90, 104.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table style="font-size:7px"><tr><td>(Year / Month / Day)</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 125, 103, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Position_end_date1.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 120, 106, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Position_end_date2.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 125, 106, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Position_end_date3.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 132, 106, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Position_end_date4.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 137, 106, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Position_end_date5.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 144, 106, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Position_end_date6.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 149, 106, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Position_end_date7.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 155, 106, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Position_end_date8.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 160.6, 106, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Estimated</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 175, 104.7, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Estimated.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 171, 104.7, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Actual</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 196, 104.7, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Actual.'</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 191.8, 104.7, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>How many months or days per year do you employ workers in this position?</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 12, 110.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$number_of_months.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 120, 110.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>or</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 4, 116.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table style="font-size:11.5px;"><tr><td><b>C</b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 6, 115.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Alternate employment:</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 12, 116.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Sub contractor</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 48, 116.8, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Sub_contractor.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 44, 116.8, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Piece work</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 85, 116.8, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Piece_work.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 82, 116.8, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Vehicle owner/operator</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 123.5, 116.8, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Vehicle_owner.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 119.8, 116.8, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Welder owner/operator</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 159, 116.8, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Welder_owner.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 155.7, 116.8, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Self-employed</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 48, 123, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Self_employed.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 44, 123, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Volunteer</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 85, 123, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Volunteer.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 82, 123, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Commission</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 123.5, 123, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Commission.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 119.8, 116.8, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Other</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 159, 123, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Other.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 155.7, 123, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Does the worker incur expenses to perform the work (substantial materials, heavy equipment, larger tools, etc.)?</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 12, 129.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Yes</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 159, 129.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$expenses_perform_work_yes.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 155.7, 129.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>No</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 173, 129.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$expenses_perform_work_no.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 169, 129.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Yes</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 55, 135.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>No</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 66.5, 135.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$worker_receive_T4_no.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 63, 135.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Will the worker receive a T4?</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 12, 135.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$worker_receive_T4.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 51, 135.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td style="font-size:11.5px;"><b>Earnings Details</b></td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 9, 142, $html, 0, 0, false, true, 'L', true);

  $html = '<table style="font-size:11.5px;color:white"><tr><td><b>9</b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 2.5, 142.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Earnings information contact name (please print):</td></tr></table>';
  $pdf->writeHTMLCell(70, 50, 57, 142.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Earnings_contact_name.'</td></tr></table>';
  $pdf->writeHTMLCell(70, 50, 119.5, 142.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Earnings contact phone number:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 9, 149, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Earnings_contact_number.'</td></tr></table>';
  $pdf->writeHTMLCell(70, 50, 51, 149, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Earnings contact e-mail:</td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 93, 149, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Earnings_contact_email.'</td></tr></table>';
  $pdf->writeHTMLCell(70, 50, 124.6, 149, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td><b>Choose A or B:</b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 9, 155, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td style="font-size:11.5px;"><b>A</b></td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 8, 161, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Gross earnings for the period of one year prior to the date</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 11.5, 161, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Gross_earnings_year.'</td></tr></table>';
  $pdf->writeHTMLCell(70, 50, 88, 162.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>of injury or date the worker was hired if less than one year:</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 11.5, 164, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>$</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 84, 162.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>from:</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 110, 163, $html, 0, 0, false, true, 'L', true);

  $html = '<table style="font-size:7px"><tr><td>(Year / Month / Day)</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 125, 160, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$injury_dob1.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 120, 165, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$injury_dob2.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 125, 165, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$injury_dob3.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 130, 165, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$injury_dob4.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 135, 165, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$injury_dob5.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 142, 165, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$injury_dob6.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 147, 165, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$injury_dob7.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 153, 165, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$injury_dob8.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 159, 165, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>to</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 163.5, 163, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$injury_to_dob1.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 169, 165, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$injury_to_dob2.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 174, 165, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$injury_to_dob3.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 180, 165, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$injury_to_dob4.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 185, 165, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$injury_to_dob5.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 191, 165, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$injury_to_dob6.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 197, 165, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$injury_to_dob7.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 203, 165, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$injury_to_dob8.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 208, 165, $html, 0, 0, false, true, 'L', true);

  $html = '<table style="font-size:7px"><tr><td>(Year / Month / Day)</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 180, 160, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Was any time missed from work</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 11.5, 169.3, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td><b>without pay</b></td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 52.7, 169, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>during the above period, excluding vacation? (eg. maternity, sick, WCB benefits)</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 69.5, 169.3, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Yes</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 179, 169.3, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$excluding_vacation_yes.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 175, 169.8, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>No</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 191.5, 169.3, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$excluding_vacation_no.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 187, 169.8, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Dates and reasons:</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 11.5, 175, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Dates.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 38, 175, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$reasons.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 55, 175, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>or</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 5, 181.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td style="font-size:11.5px;"><b>B</b></td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 8, 181.5, $html, 0, 0, false, true, 'L', true);

  $html = "<table><tr><td>Worker's hourly rate of pay at time of accident:$</td></tr></table>";
  $pdf->writeHTMLCell(150, 50, 11.5, 182, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$woker_hourly_pay_of_accident.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 74, 182, $html, 0, 0, false, true, 'L', true);

  $html = "<table><tr><td>Additional taxable benefits:</td></tr></table>";
  $pdf->writeHTMLCell(150, 50, 11.5, 189, $html, 0, 0, false, true, 'L', true);

  $html = "<table><tr><td>Vacation Pay</td></tr></table>";
  $pdf->writeHTMLCell(150, 50, 11.5, 194, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Vacation_pay.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 47, 194, $html, 0, 0, false, true, 'L', true);

  $html = "<table><tr><td>Taken as time off with pay</td></tr></table>";
  $pdf->writeHTMLCell(150, 50, 52, 194, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Taken_with_pay.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 94.8, 194, $html, 0, 0, false, true, 'L', true);

  $html = "<table><tr><td>OR</td></tr></table>";
  $pdf->writeHTMLCell(150, 50, 88, 194, $html, 0, 0, false, true, 'L', true);

  $html = "<table><tr><td>Paid on a regular basis</td></tr></table>";
  $pdf->writeHTMLCell(150, 50, 100, 194, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Paid_regular_basis.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 138, 194, $html, 0, 0, false, true, 'L', true);

  $html = "<table><tr><td>%</td></tr></table>";
  $pdf->writeHTMLCell(150, 50, 131, 194, $html, 0, 0, false, true, 'L', true);

  $html = "<table><tr><td>Shift Premium Gross earnings:</td></tr></table>";
  $pdf->writeHTMLCell(150, 50, 11.5, 200, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Gross_earnings.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 65, 200, $html, 0, 0, false, true, 'L', true);

  $html = "<table><tr><td>$</td></tr></table>";
  $pdf->writeHTMLCell(150, 50, 53, 200, $html, 0, 0, false, true, 'L', true);

  $html = "<table><tr><td>from:</td></tr></table>";
  $pdf->writeHTMLCell(150, 50, 95, 200, $html, 0, 0, false, true, 'L', true);

  $html = '<table style="font-size:7px"><tr><td>(Year / Month / Day)</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 110, 199, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Gross_earnings_dob1.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 105, 202, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Gross_earnings_dob2.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 111, 202, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Gross_earnings_dob3.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 117, 202, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Gross_earnings_dob4.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 123, 202, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Gross_earnings_dob5.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 129, 202, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Gross_earnings_dob6.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 135, 202, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Gross_earnings_dob7.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 141, 202, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Gross_earnings_dob8.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 147, 202, $html, 0, 0, false, true, 'L', true);


  $html = '<table><tr><td>to</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 152, 200, $html, 0, 0, false, true, 'L', true);

  $html = '<table style="font-size:7px"><tr><td>(Year / Month / Day)</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 162, 199, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Gross_earnings_to_dob1.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 157, 202, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Gross_earnings_to_dob2.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 163, 202, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Gross_earnings_to_dob3.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 168, 202, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Gross_earnings_to_dob4.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 175, 202, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Gross_earnings_to_dob5.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 181, 202, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Gross_earnings_to_dob6.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 186, 202, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Gross_earnings_to_dob7.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 193, 202, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Gross_earnings_to_dob8.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 199, 202, $html, 0, 0, false, true, 'L', true);

  $html = "<table><tr><td>Overtime Gross earnings:</td></tr></table>";
  $pdf->writeHTMLCell(150, 50, 11.5, 207, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Overtime_Gross_earnings.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 65, 207, $html, 0, 0, false, true, 'L', true);

  $html = "<table><tr><td>$</td></tr></table>";
  $pdf->writeHTMLCell(150, 50, 53, 207, $html, 0, 0, false, true, 'L', true);

  $html = "<table><tr><td>from:</td></tr></table>";
  $pdf->writeHTMLCell(150, 50, 95, 207, $html, 0, 0, false, true, 'L', true);

  $html = '<table style="font-size:7px"><tr><td>(Year / Month / Day)</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 110, 206, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Overtime_Gross_earnings_dob1.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 105, 208, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Overtime_Gross_earnings_dob2.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 111, 208, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Overtime_Gross_earnings_dob3.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 117, 208, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Overtime_Gross_earnings_dob4.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 123, 208, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Overtime_Gross_earnings_dob5.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 129, 208, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Overtime_Gross_earnings_dob6.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 135, 208, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Overtime_Gross_earnings_dob7.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 141, 208, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Overtime_Gross_earnings_dob8.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 147, 208, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>to</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 152, 207, $html, 0, 0, false, true, 'L', true);

  $html = '<table style="font-size:7px"><tr><td>(Year / Month / Day)</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 162, 206, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Overtime_Gross_earnings_to_dob1.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 157, 208.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Overtime_Gross_earnings_to_dob2.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 163, 208.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Overtime_Gross_earnings_to_dob3.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 168, 208.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Overtime_Gross_earnings_to_dob4.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 175, 208.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Overtime_Gross_earnings_to_dob5.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 181, 208.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Overtime_Gross_earnings_to_dob6.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 186, 208.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Overtime_Gross_earnings_to_dob7.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 193, 208.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Overtime_Gross_earnings_to_dob8.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 199, 208.5, $html, 0, 0, false, true, 'L', true);

  $html = "<table><tr><td>Other Gross earnings:</td></tr></table>";
  $pdf->writeHTMLCell(150, 50, 11.5, 213, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Other_Gross_earnings.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 65, 213, $html, 0, 0, false, true, 'L', true);

  $html = "<table><tr><td>$</td></tr></table>";
  $pdf->writeHTMLCell(150, 50, 53, 213, $html, 0, 0, false, true, 'L', true);

  $html = "<table><tr><td>from:</td></tr></table>";
  $pdf->writeHTMLCell(150, 50, 95, 213, $html, 0, 0, false, true, 'L', true);

  $html = '<table style="font-size:7px"><tr><td>(Year / Month / Day)</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 110, 212, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Other_Gross_earnings_dob1.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 105, 214, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Other_Gross_earnings_dob2.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 111, 214, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Other_Gross_earnings_dob3.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 117, 214, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Other_Gross_earnings_dob4.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 123, 214, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Other_Gross_earnings_dob5.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 129, 214, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Other_Gross_earnings_dob6.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 135, 214, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Other_Gross_earnings_dob7.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 141, 214, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Other_Gross_earnings_dob8.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 147, 214, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>to</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 152, 213, $html, 0, 0, false, true, 'L', true);

  $html = '<table style="font-size:7px"><tr><td>(Year / Month / Day)</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 162, 212, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Other_Gross_earnings_to_dob1.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 157, 214.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Other_Gross_earnings_to_dob2.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 163, 214.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Other_Gross_earnings_to_dob3.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 168, 214.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Other_Gross_earnings_to_dob4.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 175, 214.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Other_Gross_earnings_to_dob5.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 181, 214.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Other_Gross_earnings_to_dob6.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 186, 214.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Other_Gross_earnings_to_dob7.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 193, 214.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Other_Gross_earnings_to_dob8.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 199, 214.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table style="font-size:10	px;color:white"><tr><td><b>10</b></td></tr></table>';
  $pdf->writeHTMLCell(50, 50, 2, 220, $html, 0, 0, false, true, 'L', true);

  $html = '<table style="font-size:11.5px;"><tr><td><b>Hours of Work Details</b></td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 9, 219, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>a. Number of hours (not including overtime):</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 9, 226, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Number_hours.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 70, 226, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Per</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 86.3, 226.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Per.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 93.8, 226.8, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Day</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 98, 227, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Day.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 107.8, 227, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Week</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 111.5, 227, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Week.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 124, 227, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Shift cycle</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 127.7, 227, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Shift_cycle.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 146.8, 227, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Other:_________</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 150, 227, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$number_of_hours_other.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 165, 227, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>b. Does the work schedule repeat?</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 9, 234, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Date shift cycle commenced:</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 58, 234, $html, 0, 0, false, true, 'L', true);

  $html = '<table style="font-size:7px"><tr><td>(Year / Month / Day)</td></tr></table>';
  $pdf->writeHTMLCell(100, 50, 102, 232, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$shift_cycle_commenced_dob1.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 97, 234.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$shift_cycle_commenced_dob2.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 103, 234.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$shift_cycle_commenced_dob3.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 110, 234.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$shift_cycle_commenced_dob4.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 115, 234.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$shift_cycle_commenced_dob5.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 121.5, 234.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$shift_cycle_commenced_dob6.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 126.8, 234.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$shift_cycle_commenced_dob7.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 132, 234.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$shift_cycle_commenced_dob8.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 138, 234.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>No</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 17, 239, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$no.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 12.9, 239.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Yes</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 45, 239, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$yes.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 41.8, 239.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Average regular hours<br>worked per week<br>(not including overtime):</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 10, 247.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td><b>Mark hours worked<br>for one complete<br>work schedule<br>(use zero for<br> days off):</b></td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 58, 242, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$per_week.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 15.5, 257.8, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Sun</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 109.5, 240, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Mon</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 121.5, 240, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Tue</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 134.5, 240, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Wed</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 146.5, 240, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Thu</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 158.5, 240, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Fri</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 170.5, 240, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>sat</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 182.5, 240, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Hours per day:</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 87, 245, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Hours_per_day_sun1.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 109, 245, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Hours_per_day_mon1.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 122, 245, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Hours_per_day_tue1.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 134, 245, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Hours_per_day_wed1.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 146, 245, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Hours_per_day_thu1.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 159, 245, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Hours_per_day_fri1.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 172, 245, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Hours_per_day_sat1.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 183, 245, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Hours per day:</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 87, 250.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Hours_per_day_sun2.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 109, 250.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Hours_per_day_mon2.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 122, 250.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Hours_per_day_tue2.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 134, 250.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Hours_per_day_wed2.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 146, 250.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Hours_per_day_thu2.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 159, 250.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Hours_per_day_fri2.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 172, 250.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Hours_per_day_sat2.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 183, 250.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>Hours per day:</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 87, 256, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Hours_per_day_sun3.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 109, 256, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Hours_per_day_mon3.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 122, 256, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Hours_per_day_tue3.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 134, 256, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Hours_per_day_wed3.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 146, 256, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Hours_per_day_thu3.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 159, 256, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Hours_per_day_fri3.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 172, 256, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td>'.$Hours_per_day_sat3.'</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 183, 256, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td><b>IMPORTANT<br>Circle day<br>of injury. See<br>instructions</b></td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 194, 246.5, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td><b>or</b> if your schedule is more than 21 days, attach a copy of the schedule.</td></tr></table>';
  $pdf->writeHTMLCell(150, 50, 63, 261, $html, 0, 0, false, true, 'L', true);

  $html = '<table><tr><td><small>C-040 REV SEPT 2014</small></td></tr></table>';
  $pdf->writeHTMLCell(210, 50, 29, 269, $html, 0, 0, false, true, 'L', true);

  $pdf->Output('injury_report/download/patientform_'.$fieldlevelriskid.'.pdf', 'F');

    unlink("injury_report/download/sign_".$fieldlevelriskid.".png");
    echo '';
}
?>
