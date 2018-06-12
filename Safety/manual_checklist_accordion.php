<style>
@media (max-width:1599px) {
.tbl-orient input {
			position:absolute;
			left:20px;
}
}
.tbl-orient a {
			Font-weight:bold;
			color:black;
}
.tbl-orient a:hover {
	text-shadow:none;

}
.h4class {
	border-left:3px solid #646464;
	border-right: 3px solid #646464;
}
.tbl-orient {
			border-radius: 2px;
			position:relative;
			margin:auto;
			Font-weight:bold;
}
.tbl-orient tr {
			background-color: rgb(238, 238, 238);
			border-left:1px solid #646464;
			border-right: 1px solid #646464;
			color:black;
}
.tbl-orient td {
			border-bottom:1px solid #000146;
			padding:10px;

}
.tbl-orient .bord-right {
    border-right:1px solid #D34345;
}
@media(max-width:767px) {
	.full-mobile {
		width:100% !important;
	}
	.disable-mobile {
		width:0% !important;
		display:none;
	}
}
@media(max-width:510px) {
	.full-mobile {
		width:inherit !important;
	}
	.hide-row-mobile {
		display:none !important;
	}
	.circle_marker {
		width:21px !important;
		height:21px !important;
	}
}
</style>
<?php

	// BEGIN THE FOURTH ACCORDION
	$fourthaccordion = '';

	$td_height = '35';
	$img_height = '20';
	$img_width = '20';
	$tab = $_GET['tab'];
	if(isset($_GET['category'])) {
		$category = $_GET['category'];
	} else {
		$category ='x';
	}

	$fourthaccordion .= '<table class="tbl-orient">';

    $contactid = $_SESSION['contactid'];

    $result = mysqli_query($dbc, "SELECT * FROM safety WHERE deleted = 0 AND tab='$tab' AND category='$category' ORDER BY category, lpad(heading_number, 100, 0), lpad(sub_heading_number, 100, 0)");

    $status_1 = '';
    $status_2 = '';
    $test = 0;
    $loop = 0;
    while($row = mysqli_fetch_array($result)) {
        $safetyid = $row['safetyid'];

        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_staff WHERE safetyid='$safetyid' AND staffid='$contactid' ORDER by safetystaffid DESC"));
        $staff_status = $get_staff['safetystaffid'];

        $checked = $get_staff['done']==1 ? 'checked' : '';

        $status = '';
        $deadline = $row['deadline'];
        $today = date('Y-m-d');

        if($staff_status == '') {
            $status = '<span style="color:blue;">New</span>';
        }

        if(($staff_status == '') && ($today > $deadline)) {
            $status = '<span style="color:red;">Review Needed</span>';
        }

        if(($staff_status != '') && ($today > $deadline) && ($get_staff['done'] == 0)) {
            $status = '<span style="color:red;">Past Due</span>';
        }

        if($checked == 'checked') {
            $status = '<img src="'.WEBSITE_URL.'/img/checkmark.png" width="'.$img_width.'" height="'.$img_height.'" border="0" alt="">';
        }

        if($row['heading_number'] != $status_1) {
            //if($row['third_heading_number'] == '') {
                if(($test == 2) || ($test == 1)) {
                    $fourthaccordion .= '</table>';
                }
            //}
            $loop = 0;
            $fourthaccordion .= '<h3 class="tbl-orient brand-btn" style="height:40px; border-bottom: 3px solid black; padding-top: 4px;">&nbsp;' . $row['heading_number'] .' - '.$row['heading']. '</h3>';
            $status_1 = $row['heading_number'];
            if($row['third_heading_number'] == '') {
                $fourthaccordion .= '<table class="tbl-orient">';
                $test = 1;
            }
        } else {
            if($row['third_heading_number'] == '') {
                $test = 2;
            }
        }
    ?>

    <?php

    if($row['third_heading_number'] != '' || $row['third_heading'] != '') {
        if($row['sub_heading_number'] != $status_2) {
            if(($test == 2) || ($test == 1)) {
                $fourthaccordion .= '</table>';
            }

            $fourthaccordion .= '<h4 class="tbl-orient active_tab h4class" style="height:40px; border-bottom: 2px solid black; padding-top: 8px;">&nbsp;&nbsp;&nbsp;&nbsp;' . $row['sub_heading_number'] .' - '.$row['sub_heading']. '</h4>';
            $status_2 = $row['sub_heading_number'];
            $fourthaccordion .= '<table class="tbl-orient subheading">';
            $test = 1;
            $loop++;
        } else {
            $test = 2;
        }
    }


 $fourthaccordion .=   '<tr>';
// MARKER CODE
$marker = '';
 if($row['marker'] !== '' && $row['marker'] !== NULL) {
					$marker = '<div class="circle_marker" style="width:30px; height:30px; border-radius:100%;background-color:'.$row['marker'].';display:inline-block; margin-right:5px; float:left;"></div>';
				}
  // END MARKER CODE
        $formid = safety_click($dbc, $safetyid, $contactid);
        ?>

        <?php if($row['third_heading_number'] == '' && $row['third_heading'] == '') {

        $fourthaccordion .= '<td style="text-align:left; padding:5px;" height="'.$td_height.'" width="90%"  class="full-mobile">';

                $fourthaccordion .= ''.$marker.' &nbsp;&nbsp;&nbsp;&nbsp;<a href="add_manual.php?safetyid='.$safetyid.'&action=view&formid='.$formid.'">'.$row['sub_heading_number'].'&nbsp;&nbsp;'.$row['sub_heading'].'</a>';
           $fourthaccordion .= '&nbsp;&nbsp;
        </td>';
         } else {
        $fourthaccordion .= '<td style="text-align:left;  padding:5px;" height="'.$td_height.'" width="90%" class="full-mobile">';
                           $fourthaccordion .= ''.$marker.' &nbsp;&nbsp;&nbsp;&nbsp;<a href="add_manual.php?safetyid='.$safetyid.'&action=view&formid='.$formid.'">'.$row['third_heading_number'].'&nbsp;&nbsp;'.$row['third_heading'].'</a>';
            $fourthaccordion .= '&nbsp;&nbsp;
        </td>';
         }
        /*$fourthaccordion .= '<td height="'.$td_height.'" width="8%">';
            //$fourthaccordion .= $status.'
        //$fourthaccordion .= '&nbsp;&nbsp';
        $fourthaccordion .= '</td>
        <td height="'.$td_height.'" width="10%">';

        //$fourthaccordion .= 'Revised '.$row['last_edited'].'
        //$fourthaccordion .=    &nbsp;&nbsp;
        $fourthaccordion .= '</td>';*/
        $fourthaccordion .= '<td height="'.$td_height.'" width="7%" style=" padding:5px;" class="hide-row-mobile">';
            if(config_visible_function($dbc, 'safety') == 1) {
				$fourthaccordion .= '<span class="disable-mobile"><a href="add_manual.php?safetyid='.$safetyid.'&action=config">Edit</a> | <a href="add_manual.php?safetyid='.$safetyid.'&action=delete">Delete</a></span>';
			}
         $fourthaccordion .= '</td>
    </tr>';
	}
    if(($loop == 1) || ($test == 2) || ($test == 1)) {
       $fourthaccordion .= '</table>';
    }

	function safety_click($dbc, $safetyid, $contactid) {
    $form = get_safety($dbc, $safetyid, 'form');
    $login_user = ','.decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']).',';

    if($form == '') {
        $get_staff['fieldlevelriskid'] = 0;
    }

    if($form == 'Field Level Hazard Assessment') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_field_level_risk_assessment WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }

    if($form == 'Hydrera Site Specific Pre Job Safety Meeting Hazard Assessment') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_site_specificpre_job WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }

    if($form == 'Weekly Safety Meeting') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_weekly_safety_meeting WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }

    if($form == 'Tailgate Safety Meeting') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_tailgate_safety_meeting WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }

    if($form == 'Toolbox Safety Meeting') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_toolbox_safety_meeting WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }

    if($form == 'Daily Equipment Inspection Checklist') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_daily_equipment_inspection_checklist WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }

    if($form == 'AVS Hazard Identification') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_avs_hazard_identification WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }

    if($form == 'AVS Near Miss') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_near_miss_report WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }

    if($form == 'Incident Investigation Report') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_incident_investigation_report WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }

    if($form == 'Follow Up Incident Report') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_follow_up_incident_report WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }

    if($form == 'Pre Job Hazard Assessment') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_pre_job_hazard_assessment WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }

    if($form == 'Monthly Site Safety Inspections') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_monthly_site_safety_inspection WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }

    if($form == 'Monthly Office Safety Inspections') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_monthly_office_safety_inspection WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Monthly Health and Safety Summary') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_monthly_health_and_safety_summary WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Trailer Inspection Checklist') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_trailer_inspection_checklist WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Employee Misconduct Form') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_employee_misconduct_form WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Site Inspection Hazard Assessment') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_site_inspection_hazard_assessment WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }

    if($form == 'Weekly Planned Inspection Checklist') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_weekly_planned_inspection_checklist WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Equipment Inspection Checklist') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_equipment_inspection_checklist WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Employee Equipment Training Record') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_employee_equipment_training_record WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Vehicle Inspection Checklist') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_vehicle_inspection_checklist WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Safety Meeting Minutes') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_safety_meeting_minutes WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Vehicle Damage Report') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_vehicle_damage_report WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Fall Protection Plan') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_fall_protection_plan WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Spill Incident Report') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_spill_incident_report WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'General Site Safety Inspection') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_general_site_safety_inspection WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Confined Space Entry Permit') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_confined_space_entry_permit WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Lanyards Inspection Checklist Log') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_lanyards_inspection_checklist_log WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'On The Job Training Record') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_on_the_job_training_record WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'General Office Safety Inspection') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_general_office_safety_inspection WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Full Body Harness Inspection Checklist Log') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_full_body_harness_inspection_checklist_log WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Confined Space Pre Entry Checklist') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_confined_space_entry_pre_entry_checklist WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Confined Space Entry Log') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_confined_space_entry_log WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Emergency Response Transportation Plan') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_emergency_response_transportation_plan WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Hazard Id Report') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_hazard_id_report WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Dangerous Goods Shipping Document') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_dangerous_goods_shipping_document WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Safe Work Permit') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_safe_work_permit WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Journey Management - Trip Tracking Form') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM safety_journey_management_trip_tracking WHERE safetyid='$safetyid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    return $formid = $get_staff['fieldlevelriskid'];
}
    ?>