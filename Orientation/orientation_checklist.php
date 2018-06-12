<?php
function orientation_checklist($dbc, $contactid, $td_height, $img_height, $img_width) {

    $get_orientation = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM orientation WHERE contactid='$contactid'"));
    $emp_info_medical_form = $get_orientation['emp_info_medical_form']==1 ? 'checked' : '';
    $emp_driver_info_form = $get_orientation['emp_driver_info_form']==1 ? 'checked' : '';
    $time_clock_policy = $get_orientation['time_clock_policy']==1 ? 'checked' : '';
    $confidential_information = $get_orientation['confidential_information']==1 ? 'checked' : '';
    $pay_agreement = $get_orientation['pay_agreement']==1 ? 'checked' : '';
    $direct_deposit_info = $get_orientation['direct_deposit_info']==1 ? 'checked' : '';
    $emp_substance_abuse_policy_confirm = $get_orientation['emp_substance_abuse_policy_confirm']==1 ? 'checked' : '';
    $emp_right_to_refuse_unsafe_work_form = $get_orientation['emp_right_to_refuse_unsafe_work_form']==1 ? 'checked' : '';
    $emp_light_duty_awareness = $get_orientation['emp_light_duty_awareness']==1 ? 'checked' : '';
    $emp_shop_yard_office_ori = $get_orientation['emp_shop_yard_office_ori']==1 ? 'checked' : '';
    $driver_auth_form = $get_orientation['driver_auth_form']==1 ? 'checked' : '';

    $review_payment_schedule = $get_orientation['review_payment_schedule']==1 ? 'checked' : '';
    $provincial_federal_tax_forms = $get_orientation['provincial_federal_tax_forms']==1 ? 'checked' : '';
    $benefits_app = $get_orientation['benefits_app']==1 ? 'checked' : '';
    $emp_trained_in_ppe_req = $get_orientation['emp_trained_in_ppe_req']==1 ? 'checked' : '';
    $advised_loc_of_policies_sjp_swp = $get_orientation['advised_loc_of_policies_sjp_swp']==1 ? 'checked' : '';
    $completed_safety_ori_que = $get_orientation['completed_safety_ori_que']==1 ? 'checked' : '';
    $verbal_training_in_eme_res_plan = $get_orientation['verbal_training_in_eme_res_plan']==1 ? 'checked' : '';
    $copy_of_driver_lic_safety_tickets = $get_orientation['copy_of_driver_lic_safety_tickets']==1 ? 'checked' : '';
    ?>
<table class="tbl-orient">

    <tr>
        <td height="<?php echo $td_height;?>">
        <?php
            if($emp_info_medical_form == 'checked') {
                echo '<img src="'.WEBSITE_URL.'/img/checkmark.png" width="'.$img_width.'" height="'.$img_height.'" border="0" alt="">';
            }
        ?>
        </td>
        <td height="<?php echo $td_height;?>">
            <?php
                echo "<a href='orientation_forms.php?contactid=".$contactid."&form_name=emp_info_medical_form'>EMPLOYEE INFORMATION & MEDICAL FORM</a>";
            ?>&nbsp;&nbsp;
        </td>
        <td height="<?php echo $td_height;?>">
        <?php
            if($emp_info_medical_form == 'checked') {
                echo '<a target="_blank" href="download/'.$contactid.'_emp_info_medical_form.pdf"><img src="'.WEBSITE_URL.'/img/pdf.png" width="'.$img_width.'" height="'.$img_height.'" border="0" alt=""></a>';
            }
        ?>
        </td>
    </tr>

    <tr>
        <td height="<?php echo $td_height;?>">
        <?php
            if($emp_driver_info_form == 'checked') {
                echo '<img src="'.WEBSITE_URL.'/img/checkmark.png" width="'.$img_width.'" height="'.$img_height.'" border="0" alt="">';
            }
        ?>
        </td>
        <td height="<?php echo $td_height;?>">
            <?php
                echo "<a href='orientation_forms.php?contactid=".$contactid."&form_name=emp_driver_info_form'>EMPLOYEE DRIVER INFORMATION FORM </a>";
            ?>&nbsp;&nbsp;
        </td>
        <td height="<?php echo $td_height;?>">
        <?php
            if($emp_driver_info_form == 'checked') {
                echo '<a target="_blank" href="download/'.$contactid.'_emp_driver_info_form.pdf"><img src="'.WEBSITE_URL.'/img/pdf.png" width="'.$img_width.'" height="'.$img_height.'" border="0" alt=""></a>';
            }
        ?>
        </td>
    </tr>

    <tr>
        <td height="<?php echo $td_height;?>">
        <?php
            if($time_clock_policy == 'checked') {
                echo '<img src="'.WEBSITE_URL.'/img/checkmark.png" width="'.$img_width.'" height="'.$img_height.'" border="0" alt="">';
            }
        ?>
        </td>
        <td height="<?php echo $td_height;?>">
            <?php
                echo "<a href='orientation_forms.php?contactid=".$contactid."&form_name=time_clock_policy'>WORK HOURS POLICY</a>";
            ?>&nbsp;&nbsp;
        </td>
        <td></td>
    </tr>

    <tr>
        <td height="<?php echo $td_height;?>">
        <?php
            if($confidential_information == 'checked') {
                echo '<img src="'.WEBSITE_URL.'/img/checkmark.png" width="'.$img_width.'" height="'.$img_height.'" border="0" alt="">';
            }
        ?>
        </td>
        <td height="<?php echo $td_height;?>">
            <?php
                echo "<a href='orientation_forms.php?contactid=".$contactid."&form_name=confidential_information'>CONFIDENTIAL INFORMATION</a>";
            ?>&nbsp;&nbsp;
        </td>
        <td></td>
    </tr>

    <tr>
        <td height="<?php echo $td_height;?>">
        <?php
            if($pay_agreement == 'checked') {
                echo '<img src="'.WEBSITE_URL.'/img/checkmark.png" width="'.$img_width.'" height="'.$img_height.'" border="0" alt="">';
            }
        ?>
        </td>
        <td height="<?php echo $td_height;?>">
            <?php
                echo "<a href='orientation_forms.php?contactid=".$contactid."&form_name=pay_agreement'>PAY AGREEMENT</a>";
            ?>&nbsp;&nbsp;
        </td>
    </tr>

    <tr>
        <td height="<?php echo $td_height;?>">
        <?php
            if($direct_deposit_info == 'checked') {
                echo '<img src="'.WEBSITE_URL.'/img/checkmark.png" width="'.$img_width.'" height="'.$img_height.'" border="0" alt="">';
            }
        ?>
        </td>
        <td height="<?php echo $td_height;?>">
            <?php
                echo "<a href='orientation_forms.php?contactid=".$contactid."&form_name=direct_deposit_info'>DIRECT DEPOSIT INFORMATION</a>";
            ?>&nbsp;&nbsp;
        </td>
        <td height="<?php echo $td_height;?>">
        <?php
            $ori = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM orientation_direct_deposit_info WHERE contactid = '$contactid'"));

            if($direct_deposit_info == 'checked') {
                if($ori['financial_institution_name'] == '') {
                    //echo '<a target="_blank" href="download/orientation/'.$ori['void_cheque_upload'].'">Void Check</a>';
                } else {
                    echo '<a target="_blank" href="download/'.$contactid.'_direct_deposit_info.pdf"><img src="'.WEBSITE_URL.'/img/pdf.png" width="'.$img_width.'" height="'.$img_height.'" border="0" alt=""></a>';
                }
            }
        ?>
        </td>
    </tr>

    <tr>
        <td height="<?php echo $td_height;?>">
        <?php
            if($emp_substance_abuse_policy_confirm == 'checked') {
                echo '<img src="'.WEBSITE_URL.'/img/checkmark.png" width="'.$img_width.'" height="'.$img_height.'" border="0" alt="">';
            }
        ?>
        </td>
        <td height="<?php echo $td_height;?>">
            <?php
                echo "<a href='orientation_forms.php?contactid=".$contactid."&form_name=emp_substance_abuse_policy_confirm'>EMPLOYEE SUBSTANCE ABUSE POLICY CONFIRMATION</a>";
            ?>&nbsp;&nbsp;
        </td>
        <td></td>
    </tr>


    <tr>
        <td height="<?php echo $td_height;?>">
        <?php
            if($emp_right_to_refuse_unsafe_work_form == 'checked') {
                echo '<img src="'.WEBSITE_URL.'/img/checkmark.png" width="'.$img_width.'" height="'.$img_height.'" border="0" alt="">';
            }
        ?>
        </td>
        <td height="<?php echo $td_height;?>">
            <?php
                echo "<a href='orientation_forms.php?contactid=".$contactid."&form_name=emp_right_to_refuse_unsafe_work_form'>EMPLOYEE RIGHT TO REFUSE UNSAFE WORK FORM</a>";
            ?>&nbsp;&nbsp;
        </td>
        <td></td>
    </tr>

    <tr>
        <td height="<?php echo $td_height;?>">
        <?php
            if($emp_shop_yard_office_ori == 'checked') {
                echo '<img src="'.WEBSITE_URL.'/img/checkmark.png" width="'.$img_width.'" height="'.$img_height.'" border="0" alt="">';
            }
        ?>
        </td>
        <td height="<?php echo $td_height;?>">
            <?php
                echo "<a href='orientation_forms.php?contactid=".$contactid."&form_name=emp_shop_yard_office_ori'>EMPLOYEE SHOP, YARD & OFFICE ORIENTATION</a>";
            ?>&nbsp;&nbsp;
        </td>
        <td></td>
    </tr>

    <tr>
        <td height="<?php echo $td_height;?>">
        <?php
            if($benefits_app == 'checked') {
                echo '<img src="'.WEBSITE_URL.'/img/checkmark.png" width="'.$img_width.'" height="'.$img_height.'" border="0" alt="">';
            }
        ?>
        </td>
        <td height="<?php echo $td_height;?>">
            <?php
                echo "<a href='orientation_forms.php?contactid=".$contactid."&form_name=benefits_app'>BENEFITS APPLICATION</a>";
            ?>&nbsp;&nbsp;
        </td>
        <td></td>
    </tr>

    <tr>
        <td height="<?php echo $td_height;?>">
        <?php
            if($copy_of_driver_lic_safety_tickets == 'checked') {
                echo '<img src="'.WEBSITE_URL.'/img/checkmark.png" width="'.$img_width.'" height="'.$img_height.'" border="0" alt="">';
            }
        ?>
        </td>
        <td height="<?php echo $td_height;?>">
            <?php
                echo "<a href='orientation_forms.php?contactid=".$contactid."&form_name=copy_of_driver_lic_safety_tickets'>COPY OF DRIVER'S LICENCE & SAFETY TICKETS</a>";
            ?>&nbsp;&nbsp;
        </td>
        <td></td>
    </tr>

    <tr>
        <td height="<?php echo $td_height;?>">
        <?php
            if($emp_trained_in_ppe_req == 'checked') {
                echo '<img src="'.WEBSITE_URL.'/img/checkmark.png" width="'.$img_width.'" height="'.$img_height.'" border="0" alt="">';
            }
        ?>
        </td>
        <td height="<?php echo $td_height;?>">
            <?php
                echo "<a href='orientation_forms.php?contactid=".$contactid."&form_name=emp_trained_in_ppe_req'>WASHTECH PPE REQUIREMENTS</a>";
            ?>&nbsp;&nbsp;
        </td>
        <td></td>
    </tr>

    <tr>
        <td height="<?php echo $td_height;?>">
        <?php
            if($verbal_training_in_eme_res_plan == 'checked') {
                echo '<img src="'.WEBSITE_URL.'/img/checkmark.png" width="'.$img_width.'" height="'.$img_height.'" border="0" alt="">';
            }
        ?>
        </td>
        <td height="<?php echo $td_height;?>">
            <?php
                echo "<a href='orientation_forms.php?contactid=".$contactid."&form_name=verbal_training_in_eme_res_plan'>VERBAL TRAINING IN EMERGENCY RESPONSE PLAN</a>";
            ?>&nbsp;&nbsp;
        </td>
        <td></td>
    </tr>

</table>
<?php } ?>