<style>
@media (max-width:1599px) {
.tbl-orient input {
			position:absolute;
			left:20px;
}
}
.tbl-orient a {
			Font-weight:bold;
}
.tbl-orient a:hover {
	text-shadow:none;

}
.tbl-orient {
			background-color:#EFEFEF;
			border-radius: 5px;
			position:relative;
			margin:auto;
			color:black;
			Font-weight:bold;
}
.tbl-orient td {
			border-bottom:1px solid #000146;
			padding:10px;
}
.tbl-orient .bord-right {
    border-right:1px solid #D34345;
}
</style>
<?php
function manual_checklist($dbc, $td_height, $img_height, $img_width, $tab, $category) {
    ?>

    <table class="tbl-orient">
    <?php
    $contactid = $_SESSION['contactid'];

    $result = mysqli_query($dbc, "SELECT * FROM hr WHERE deleted = 0 AND tab='$tab' AND category='$category' ORDER BY category, lpad(heading_number, 100, 0), lpad(sub_heading_number, 100, 0)");

    $status_1 = '';
    $status_2 = '';
    $test = 0;
    $loop = 0;
    while($row = mysqli_fetch_array($result)) {
        $hrid = $row['hrid'];

        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_staff WHERE hrid='$hrid' AND staffid='$contactid' ORDER by hrstaffid DESC"));
        $staff_status = $get_staff['hrstaffid'];

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
                    echo '</table>';
                }
            //}
            $loop = 0;
            echo '<h3 class="tbl-orient" style="height:40px; border-bottom: 3px solid black; padding-top: 4px;">&nbsp;' . $row['heading_number'] .' - '.$row['heading']. '</h3>';
            $status_1 = $row['heading_number'];
            if($row['third_heading_number'] == '') {
                echo '<table class="tbl-orient">';
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
                echo '</table>';
            }

            echo '<h4 class="tbl-orient" style="height:40px; border-bottom: 2px solid black; padding-top: 8px;">&nbsp;&nbsp;&nbsp;&nbsp;' . $row['sub_heading_number'] .' - '.$row['sub_heading']. '</h4>';
            $status_2 = $row['sub_heading_number'];
            echo '<table class="tbl-orient">';
            $test = 1;
            $loop++;
        } else {
            $test = 2;
        }
    }

    $permissions_position = $row['permissions_position'];
    $contact_position = mysqli_fetch_array(mysqli_query($dbc, "SELECT `position` FROM `contacts` WHERE `contactid` = '$contactid'"))['position'];
    if (empty($permissions_position) || strpos(','.$permissions_position.',',','.$contact_position.',') !== FALSE) {
    ?>
    <tr>
        <?php
        $formid = hr_click($dbc, $hrid, $contactid);
        ?>

        <?php if($row['third_heading_number'] == '' && $row['third_heading'] == '') {

        ?>
        <td height="<?php echo $td_height;?>" width="20%">
            <?php
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='add_manual.php?hrid=".$hrid."&action=view&formid=".$formid."'>".$row['sub_heading_number'].'&nbsp;&nbsp;'.$row['sub_heading']."</a>";
            ?>&nbsp;&nbsp;
        </td>
        <?php } else { ?>
        <td height="<?php echo $td_height;?>" width="20%">
            <?php
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='add_manual.php?hrid=".$hrid."&action==view&formid=".$formid."'>".$row['third_heading_number'].'&nbsp;&nbsp;'.$row['third_heading']."</a>";
            ?>&nbsp;&nbsp;
        </td>
        <?php } ?>
        <td height="<?php echo $td_height;?>" width="8%">
            <?php
                echo $status;
            ?>&nbsp;&nbsp;
        </td>
        <td height="<?php echo $td_height;?>" width="10%">
            <?php
                echo 'Revised '.$row['last_edited'];
            ?>&nbsp;&nbsp;
        </td>
        <td height="<?php echo $td_height;?>" width="7%">
            <?php
                echo "<a href='add_manual.php?hrid=".$hrid."&action=config'>Configure/Edit</a> | <a href=\"".WEBSITE_URL."/delete_restore.php?category=".$_GET['category']."&tab=".$_GET['tab']."&action=delete&hrid=".$hrid."\">Archive</a> ";
            ?>&nbsp;&nbsp;
        </td>
    </tr>
    <?php }
    }
    if(($loop == 1) || ($test == 2) || ($test == 1)) {
        echo '</table>';
    }
    ?>

<?php }
function hr_click($dbc, $hrid, $contactid) {
    $form = get_hr($dbc, $hrid, 'form');
    $login_user = ','.decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']).',';

    if($form == 'Employee Information Form') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_employee_information_form WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%')"));
    }
    if($form == 'Employee Driver Information Form') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_employee_driver_information_form WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%')"));
    }
    if($form == 'Time Off Request') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_time_off_request WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Confidential Information') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_confidential_information WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Work Hours Policy') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_work_hours_policy WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Direct Deposit Information') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_direct_deposit_information WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%')"));
    }
    if($form == 'Employee Substance Abuse Policy') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_employee_substance_abuse_policy WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Employee Right to Refuse Unsafe Work') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_employee_right_to_refuse_unsafe_work WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Shop Yard and Office Orientation') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_employee_shop_yard_office_orientation WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == "Copy of Drivers Licence and Safety Tickets") {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_copy_of_drivers_licence_safety_tickets WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'PPE Requirements') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_ppe_requirements WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Verbal Training in Emergency Response Plan') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_verbal_training_in_emergency_response_plan WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Eligibility for General Holidays and General Holiday Pay') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_eligibility_for_general_holidays_general_holiday_pay WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Maternity Leave and Parental Leave') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_maternity_leave_parental_leave WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Employment Verification Letter') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_employment_verification_letter WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Background Check Authorization') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_background_check_authorization WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Disclosure of Outside Clients') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_disclosure_of_outside_clients WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Employment Agreement') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_employment_agreement WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Independent Contractor Agreement') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_independent_contractor_agreement WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Letter of Offer') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_letter_of_offer WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Employee Non-Disclosure Agreement') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_employee_nondisclosure_agreement WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Employee Self Evaluation') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_employee_self_evaluation WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%')"));
    }
    if($form == 'HR Complaint') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_hr_complaint WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Exit Interview') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_exit_interview WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%')"));
    }
    if($form == 'Employee Expense Reimbursement') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_employee_expense_reimbursement WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Absence Report') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_absence_report WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Employee Accident Report Form') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_employee_accident_report_form WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Trucking Information') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_trucking_information WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Contractor Orientation') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_contractor_orientation WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Contract Welder Inspection Checklist') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_contract_welder_inspection_checklist WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Contractor Pay Agreement') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_contractor_pay_agreement WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Employee Holiday Request Form') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_employee_holiday_request_form WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }
    if($form == 'Employee Coaching Form') {
        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM hr_employee_coaching_form WHERE hrid='$hrid' AND (contactid='$contactid' OR attendance_staff LIKE '%," . $login_user . ",%') AND DATE(today_date) = CURDATE() AND status='New'"));
    }

    return $formid = $get_staff['fieldlevelriskid'];
}
?>