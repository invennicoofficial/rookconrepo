<?php
/*
 * View Patient Profile
 */
    include ('../include.php');
    $rookconnect = get_software_name();
    error_reporting(0);

    if ( isset($_POST['submit']) ) {
        $category = $_POST['category'];
    }
    
    include('contacts_fields.php');
?>

<script type="text/javascript">
    $(document).ready(function(){
        $("#load").click(function(){
            loadMore();
        });
    });

    function loadMore() {
        var result_no = $('#result_no').val();
        $.ajax({
            type: 'get',
            url: '../ajax_all.php?fill=appointments_history&result_no='+result_no+'&contactid=<?= $contactid; ?>',
            success: function (response) {
                $('.assessment_history').append(response);
                $('#result_no').val(parseInt($('#result_no').val()) + 3);
                $('#load').css('display', 'none');
            }
        });
    }
</script>
</head>

<body>
<?php
	if ( !IFRAME_PAGE ) {
		include_once ('../navigation.php');
	}
	
    checkAuthorised();
	$url_category = $_GET['category'];
    
    if ( isset($_GET['email']) ) {
        if ( $_GET['email']=='reminder' ) {
            $follow_up_assessment_email = $_GET['followup'];
            reminder_email($follow_up_assessment_email, $email_address);
            echo '<script>alert("Remider email sent for the upcoming '.$follow_up_assessment_email.'");</script>';
            
        } elseif ( $_GET['email']=='survey' ) {
            $surveyid = preg_replace('/[^0-9]/', '', $_GET['sid']);
            $tid      = preg_replace('/[^0-9]/', '', $_GET['tid']);
            email_survey($surveyid, $contactid, $tid);
            echo '<script>alert("Survey email sent.");</script>';
        
        } elseif ( $_GET['email']=='nps' ) {
            email_net_promoter_score($email_address);
            echo '<script>alert("Net Promoter Score email sent.");</script>';
        }
        
        echo '<script>document.location="?category='.$url_category.'&contactid='.$contactid.'";</script>';
    }
?>

<div class="container">
    <div class="row">
		<h1><?= $first_name . ' ' . $last_name; ?></h1>
        
        <div class="pad-left gap-top double-gap-bottom"><a href="contacts.php?category=<?php echo $url_category; ?>&filter=Top" class="btn config-btn">Back to Dashboard</a></div><?php
            
        /* Display Sub Tabs */
        echo '<button class="btn brand-btn active_tab">Overview</button>';
        
        $subtab_list = get_config($dbc, FOLDER_NAME.'_field_subtabs');
        
        if ( $subtab_list != '' ) {
            $used_subtabs = [];
            $used_subtab_result = mysqli_query($dbc, "SELECT DISTINCT `subtab` FROM `field_config_contacts` WHERE `tile_name`='".FOLDER_NAME."' AND `tab`='".$url_category."' AND `contacts` IS NOT NULL");
            
            while ( $subtab_row=mysqli_fetch_array($used_subtab_result) ) {
                $subtab_name = $subtab_row['subtab'];
                if ( $subtab_name==null && strpos(','.$subtab_list.',' , ',Main,')===false ) {
                    $subtab_list = 'Main,'.$subtab_list;
                    $subtab_name = 'Main';
                }
                $used_subtabs[] = $subtab_name;
            }
            
            $subtab_list = explode ( ',', $subtab_list );
            if ( !in_array($subtab, $subtab_list) ) {
                $subtab = $subtab_list[0];
            }
            
            foreach ( $subtab_list as $subtab_name ) {
                $subtab = ( $subtab=='' ) ? $subtab_name : '';
                if ( in_array($subtab_name, $used_subtabs) ) { ?>
                    <a href="add_contacts.php?category=<?= $url_category ?>&contactid=<?= $contactid ?>&subtab=<?= $subtab_name ?>" class="btn brand-btn"><?= $subtab_name; ?></a><?php
                }
            }
            
            echo '<div class="clearfix"></div><br />';
        } ?>
	</div><!-- .row -->
    
    <div class="row"><?php
        $today   = date('Y-m-d');
        $booking = mysqli_fetch_assoc ( mysqli_query ( $dbc, "
            SELECT * FROM 
                (SELECT COUNT(`patientid`) AS `total_appt` FROM `booking` WHERE `patientid`='$contactid') AS a,
                (SELECT COUNT(`patientid`) AS `upcoming_appt` FROM `booking` WHERE DATE(`appoint_date`)>=NOW() AND `patientid`='$contactid') AS b,
                (SELECT COUNT(`patientid`) AS `noshow_appt` FROM `booking` WHERE `patientid`='$contactid' AND `follow_up_call_status`='Late Cancellation / No-Show') AS c,
                (SELECT `appoint_date` FROM `booking` WHERE `patientid`='$contactid' ORDER BY `appoint_date` DESC LIMIT 1) AS d" ) );
        
        $total_appt     = ( $booking['total_appt']>0 ) ? $booking['total_appt'] : 0;
        $upcoming_appt  = ( $booking['upcoming_appt']>0 ) ? $booking['upcoming_appt'] : 0; ?>
        
        <div class="profile-overview">
            <div class="one eighth">
                <div class="overview-num overview-blue"><?= ($booking['total_appt']) ? '<a href="../Reports/report_patient_appoint_history.php?patientid='.$contactid.'&type=sales">'.$booking['total_appt'].'</a>' : 0; ?></div>
                <div class="overview-desc">Total<br />Appointments</div>
            </div>
            <div class="one eighth">
                <div class="overview-num overview-blue"><?= ($booking['upcoming_appt']) ? '<a href="../Reports/report_patient_block_booking.php?patientid='.$contactid.'&type=operations">'.$booking['upcoming_appt'].'</a>' : 0; ?></div>
                <div class="overview-desc">Upcoming<br />Appointments</div>
            </div>
            <div class="one eighth">
                <div class="overview-num overview-blue"><?= ($booking['noshow_appt']) ? '<a href="../Reports/report_patient_appoint_history.php?patientid='.$contactid.'&type=sales">'.$booking['noshow_appt'].'</a>' : 0; ?></div>
                <div class="overview-desc">No<br />Shows</div>
            </div>
            <div class="one eighth">
                <div class="overview-num overview-blue"><?php
                    if ( !empty($booking['appoint_date']) ) {
                        $appt_date  = date_create($booking['appoint_date']);
                        $today      = date_create('now');
                        $date_diff  = $appt_date->diff($today);
                        echo '<a href="../Reports/report_patient_appoint_history.php?patientid='.$contactid.'&type=sales">'. $date_diff->days . '</a>';
                    } else {
                        echo '-';
                    } ?>
                </div>
                <div class="overview-desc">Days Since<br />Last Visit</div>
            </div>
            <div class="one eighth">
                <div class="overview-num overview-blue"><?php
                    $insurer = mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT SUM(`i`.`insurer_price`) AS `insurer_ar` FROM `invoice_insurer` AS `i` JOIN `invoice` AS `inv` ON (`inv`.`invoiceid`=`i`.`invoiceid`) WHERE `i`.`paid`<>'Yes' AND `inv`.`patientid`='$contactid'" ) );
                    $insurer_ar = $insurer['insurer_ar'];
                    $insurer_ar = explode('.', $insurer_ar);
                    echo '$';
                    echo (!empty($insurer_ar[0])) ? '<a href="add_contacts.php?category=Patient&contactid='.$contactid.'#insurer_accounts_receivable_for_patient">'.$insurer_ar[0].'.</a>' : '0.';
                    echo (!empty($insurer_ar[1])) ? '<sup><a href="add_contacts.php?category=Patient&contactid='.$contactid.'#insurer_accounts_receivable_for_patient">'.$insurer_ar[1].'</a></sup>' : '<sup>00</sup>'; ?>
                </div>
                <div class="overview-desc">Insurer<br />A/R</div>
            </div>
            <div class="one eighth">
                <div class="overview-num overview-blue"><?php
                    $patient = mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT SUM(`p`.`patient_price`) AS `patient_ar` FROM `invoice_patient` AS `p` JOIN `invoice` AS `inv` ON (`inv`.`invoiceid`=`p`.`invoiceid`) WHERE (`p`.`paid`='On Account' OR `p`.`paid`='' OR `p`.`paid` IS NULL) AND `inv`.`patientid`='$contactid'" ) );
                    $patient_ar = $patient['patient_ar'];
                    $patient_ar = explode('.', $patient_ar);
                    echo '$';
                    echo (!empty($patient_ar[0])) ? '<a href="add_contacts.php?category=Patient&contactid='.$contactid.'#patient_accounts_receivable">'.$patient_ar[0].'.</a>' : '0.';
                    echo (!empty($patient_ar[1])) ? '<sup><a href="add_contacts.php?category=Patient&contactid='.$contactid.'#patient_accounts_receivable">'.$patient_ar[1].'</a></sup>' : '<sup>00</sup>'; ?>
                </div>
                <div class="overview-desc">A/R<br /><br /></div>
            </div>
            <div class="one eighth">
                <div class="overview-num overview-blue"><?php
                    $patient = mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT `amount_credit` FROM `contacts` WHERE `contactid`='$contactid'" ) );
                    $patient_ar = $patient['patient_ar'];
                    $patient_ar = explode('.', $patient_ar);
                    echo '$';
                    echo (!empty($patient_ar[0])) ? '<a href="add_contacts.php?category=Patient&contactid='.$contactid.'#accounts_receivable_credit_on_account">'.$patient_ar[0].'.</a>' : '0.';
                    echo (!empty($patient_ar[1])) ? '<sup><a href="add_contacts.php?category=Patient&contactid='.$contactid.'#accounts_receivable_credit_on_account">'.$patient_ar[1].'</a></sup>' : '<sup>00</sup>'; ?>
                </div>
                <div class="overview-desc">Credit<br />On Account</div>
            </div>
            <div class="one eighth">
                <div class="overview-num overview-blue"><?php
                    $patient = mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT `amount_owing` FROM `contacts` WHERE `contactid`='$contactid'" ) );
                    $patient_ar = $patient['patient_ar'];
                    $patient_ar = explode('.', $patient_ar);
                    echo '$';
                    echo (!empty($patient_ar[0])) ? '<a href="add_contacts.php?category=Patient&contactid='.$contactid.'#account_statement">'.$patient_ar[0].'.</a>' : '0.';
                    echo (!empty($patient_ar[1])) ? '<sup><a href="add_contacts.php?category=Patient&contactid='.$contactid.'#account_statement">'.$patient_ar[1].'</a></sup>' : '<sup>00</sup>'; ?>
                </div>
                <div class="overview-desc">Account<br />Balance</div>
            </div>
        </div><!-- .profile-overview -->
        
        <!-- Left -->
        <div class="profile-overview-left">
            <div class="row">
                <div class="col-sm-1 overview-icon"><img src="<?= WEBSITE_URL; ?>/img/icons/icon-user.png" /></div>
                <div class="col-sm-11 overview-value"><?= $first_name . ' ' . $last_name; ?></div>
            </div>
            <div class="row">
                <div class="col-sm-1 overview-icon"><img src="<?= WEBSITE_URL; ?>/img/icons/icon-mail.png" /></div>
                <div class="col-sm-11 overview-value"><?= ( !empty($email_address) ) ? $email_address : '-'; ?></div>
            </div>
            <div class="row">
                <div class="col-sm-1 overview-icon"><img src="<?= WEBSITE_URL; ?>/img/icons/icon-phone.png" /></div>
                <div class="col-sm-3 col-xs-12 overview-value"><span class="overview-blue">H:&nbsp;</span> <?= ( !empty($home_phone) ) ? $home_phone : '-'; ?></div>
                <div class="col-sm-3 col-xs-12 overview-value"><span class="overview-blue">C:&nbsp;</span> <?= ( !empty($cell_phone) ) ? $cell_phone : '-'; ?></div>
                <div class="col-sm-3 col-xs-12 overview-value"><span class="overview-blue">O:&nbsp;</span> <?= ( !empty($office_phone) ) ? $office_phone : '-'; ?></div>
            </div>
            <div class="row">
                <div class="col-sm-1 overview-icon"><img src="<?= WEBSITE_URL; ?>/img/icons/icon-address.png" /></div>
                <div class="col-sm-11 overview-value"><?php
                    if ( empty($mailing_address) && empty($city) && empty($province) && empty($country) && empty($postal_code) ) {
                        echo '-';
                    } else {
                        echo ( !empty($mailing_address) ) ? $mailing_address . '<br />' : '';
                        echo ( !empty($city) ) ? $city . ', ' : '';
                        echo ( !empty($province) ) ? $province . ', ' : '';
                        echo ( !empty($country) ) ? $country : '';
                        echo ( !empty($postal_code) ) ? '<br />' . $postal_code : '';
                    } ?>
                </div>
            </div>
            <div class="row birthday">
                <div class="col-sm-1 col-xs-1 overview-icon"><img src="<?= WEBSITE_URL; ?>/img/icons/icon-gift.png" /></div>
                <div class="col-sm-5 col-xs-5 overview-value">
                    <small>Birth Date</small><br /><?php
                    echo ( $birth_date=='0000-00-00' || empty($birth_date) ) ? 'Not given' : date('F j, Y', strtotime($birth_date)); ?><br />
                    <br />
                    <small>Age</small><br /><?php
                    echo ( $birth_date=='0000-00-00' || empty($birth_date) ) ? '-' : date_diff(date_create($birth_date), date_create('now'))->y; ?>
                </div>
                <div class="col-sm-6 col-xs-6 overview-value">
                    <small>Sex</small><br />
                    <?= !empty($gender) ? $gender : '-'; ?>
                </div>
            </div>
        </div><!-- .col-sm-6 .left -->
        
        <!-- Right -->
        <div class="profile-overview-right">
             <div class="row">
                <div class="col-sm-1 overview-icon"><img src="<?= WEBSITE_URL; ?>/img/icons/icon-calendar.png" /></div>
                <div class="col-sm-11 overview-value"><?php
                    $today = date('Y-m-d');
                    $result = mysqli_query ( $dbc, "SELECT `patientid`, `appoint_date` FROM `booking` WHERE `patientid`='$contactid' AND (`follow_up_call_status`='Booked Confirmed' OR `follow_up_call_status`='Booked Unconfirmed') AND `appoint_date`>'$today' ORDER BY `appoint_date` DESC LIMIT 0,1" );
                    
                    if ( mysqli_num_rows($result) > 0 ) {
                        while ( $row=mysqli_fetch_assoc($result) ) {
                            $appt_date  = new DateTime($row['appoint_date']);
                            $appt_date  = date_create($appt_date->format('Y-m-d'));
                            $today      = date_create('now');
                            $date_diff  = $appt_date->diff($today);
                            echo $first_name . '\'s next visit is in ' . $date_diff->days . ' days.<br />';
                        }
                    } else {
                        echo $first_name . '\'s next visit is not scheduled yet.<br />';
                    }
                    
                    $result = mysqli_query ( $dbc, "SELECT `bookingid`, `patientid`, `therapistsid`, `appoint_date`, `type` FROM `booking` WHERE `patientid`='$contactid' ORDER BY `appoint_date` DESC LIMIT 0,3" );
                    
                    if ( mysqli_num_rows($result) > 0 ) { ?>
                        <div class="assessment_history"><?php
                            while ( $row=mysqli_fetch_assoc($result) ) {
                                $therapist  = mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT `first_name`, `last_name` FROM `contacts` WHERE `contactid`='{$row['therapistsid']}'" ) ); ?>
                                <div class="overview-blue assessment-history">Assessment with <?= decryptIt($therapist['first_name']) . ' ' . decryptIt($therapist['last_name']); ?></div>
                                <div><?= date('l, F j, Y', strtotime($row['appoint_date'])); ?> at <?= date('h:i A', strtotime($row['appoint_date'])); ?></div><?php
                            } ?>
                        </div>
                        
                        <input type="hidden" id="result_no" value="3" />
                        <a href="javascript:void(0);" id="load"><?= ($total_appt<=3) ? '0' : '+'.$total_appt-3; ?> more...</a><?php
                    
                    } else {
                        echo 'No previous appointments.';
                    } ?>
                    
                    <div class="double-gap-top"><?= $first_name; ?> has <?= $upcoming_appt; ?> upcoming appointment(s)</div><?php
                    
                    $result = mysqli_query ( $dbc, "SELECT `bookingid`, `type` FROM `booking` WHERE `patientid`='$contactid' ORDER BY `appoint_date` DESC LIMIT 0,1" );
                    
                    if ( mysqli_num_rows($result) > 0 ) {
                        while ( $row=mysqli_fetch_assoc($result) ) {
                            $service_type = $row['type'];
                        }
                        
                        $service_type = ( !empty($service_type) ) ? get_type_from_booking($dbc, $service_type) : '';
                    } ?>
                        
                    <div class="buttons"><?php
                        $result = mysqli_query ( $dbc, "SELECT `bookingid`, `therapistsid`, `type` FROM `booking` WHERE `patientid`='$contactid' ORDER BY `appoint_date` DESC LIMIT 0,1" );
                    
                        if ( mysqli_num_rows($result) > 0 ) {
                            while ( $row=mysqli_fetch_assoc($result) ) {
                                $service_type = $row['type'];
                                $tid          = $row['therapistsid'];
                            }
                            $service_name = ( !empty($service_type) ) ? get_type_from_booking($dbc, $service_type) : '';
                        }
                        
                        /* Reminder Email */
                        if ( $upcoming_appt>0 ) {
                            if ( strpos($service_name, '-PT-') !== false ) {
                                $follow_up_assessment_email = 'Physiotherapy';
                            } elseif ( strpos($service_name, '-MT') !== false ) {
                                $follow_up_assessment_email = 'Massage';
                            }
                        } ?>
                        
                        <a href="<?php if ( !empty($follow_up_assessment_email) ) { echo '?category='.$url_category.'&contactid='.$contactid.'&email=reminder&followup='.$follow_up_assessment_email; } else { echo 'javascript:alert(\'No upcoming appointments for '. $first_name.'. Reminder email not sent.\');'; } ?>" class="overview-button">Reminder Email</a><?php
                        
                        
                        /* Email Survey */
                        $result = mysqli_query ( $dbc, "SELECT `surveyid`, `name`, `service` FROM `crm_feedback_survey_form` WHERE `deleted`=0 AND `name`='$service_name'" );
                        
                        if ( mysqli_num_rows($result) > 0 ) {
                            $email_survey = true;
                            while ( $row=mysqli_fetch_assoc($result) ) {
                                $surveyid = $row['surveyid'];
                            }
                        } else {
                            $email_survey = false;
                            $surveyid     = '';
                        } ?>
                        
                        <a href="<?php if ($email_survey) { echo '?category='.$url_category.'&contactid='.$contactid.'&email=survey&sid='.$surveyid.'&pid='.$tid; } else { echo 'javascript:alert(\'A survey is not configured for '.$service_name.'. Survey email not sent. \');'; } ?>" class="overview-button">Email Survey</a><?php
                        
                        
                        /* Email Net Promoter Score */ ?>
                        <a href="<?php echo '?category='.$url_category.'&contactid='.$contactid.'&email=nps'; ?>" class="overview-button">Email NPS</a>
                    </div>
                </div>
            </div>
        </div><!-- .col-sm-6 .right -->
    </div><!-- .row -->
</div><?php

function email_survey($surveyid, $pid, $tid) {
    $send_date = date('Y-m-d');
    
    $result = mysqli_query ( $dbc, "INSERT INTO `crm_feedback_survey_result` (`surveyid`, `patientid`, `therapistid`, `send_date`) VALUES ('$surveyid', '$pid', '$tid', '$send_date')" );
    
    $surveyresultid = mysqli_insert_id($dbc);

    $survey_link = WEBSITE_URL.'/CRM/feedback_survey.php?s='.$surveyresultid;

    $feedback_survey_email_body = html_entity_decode(get_config($dbc, 'feedback_survey_email_body'));

    $email_body = str_replace("[Customer Name]", $patients, $feedback_survey_email_body);
    $email_body = str_replace("[Survey Link]", $survey_link, $email_body);
    $email      = get_email($dbc, $get_invoice['patientid']);
    $subject    = get_config($dbc, 'feedback_survey_email_subject');

    //send_email('', $email, '', '', $subject, $email_body, '');

    $query_update_booking = "UPDATE `invoice` SET `survey`='$surveyid' WHERE `invoiceid`='$invoiceid'";
    $result_update_booking = mysqli_query($dbc, $query_update_booking);
}

function reminder_email($follow_up_assessment_email, $email) {
    $email_body = "Dear Valued Client,<br><br><br>";

    if ( $follow_up_assessment_email == 'Physiotherapy' ) {
        $email_body .= "The center of our attention is to consistently provide quality therapy, and our reputation is built on taking extra care of your health and well-being.<br><br>
        Your customized treatment plan was setup to optimize your results and minimize the chance of reinjury.  We truly care about our clients and when they fail to finish their program we become concerned.  We haven't seen you in the clinic for over a week and as experience has taught us, although you may be pain free and feeling better, failing to totally complete your rehab program will not give you the ideal long term results.<br><br>
        We hope you will make your healing a priority, and work with us to complete your program.  If you are pain free and feel you are ready for graduation, give us a call so we can assess and make sure you are ready to return to activity. This will prevent re-injury and set you up for success.  We will facilitate your total recovery and allow you to get back to the activities you love without fear of relapse. <br><br>";
    }

    if ( $follow_up_assessment_email == 'Massage' ) {
        $email_body .= "The center of our attention is to consistently provide quality therapy, and our reputation is built on our ability to not only meet, but exceed your expectations. <br><br>
        Your customized massage plan was setup to optimize your results and minimize pain and discomfort.  We truly care about our patients and we hope you are feeling your best. We make your healing a priority, and we hope you’ll continue with us in the future. <br><br>
        We will facilitate your total recovery and allow you to get back to the activities you love without fear of injury.";
    }

    $email_body .= "We hope to hear from you soon.<br><br>
    Please e-mail or call us at 403-295-8590.<br><br>
    Warmest regards,<br>
    Your Nose Creek Sport Physical Therapy<br>
    and Massage Therapy Team
    ";
    
    $subject = 'Follow Up Email From Nose Creek Sport Physical Therapy';

    //send_email('', $email, '', '', $subject, $email_body, '');
}

function email_net_promoter_score($email) {
    if ( $_SERVER['SERVER_NAME']=='ncbd.clinicace.com' ) {
        $nps_url = 'http://nosecreekphysiotherapy.com/how-would-you-rate-us-beddington/';
    } elseif ( $_SERVER['SERVER_NAME']=='nctc.clinicace.com' ) {
        $nps_url = 'http://nosecreekphysiotherapy.com/how-would-you-rate-us-thorncliffe/';
    }
    
    $email_body = "
        Dear Valued Client,<br>
        <br>
        The center of our attention is to consistently provide quality therapy, and our reputation is built on taking extra care of your health and well-being.<br>
        <br>
        Please take a moment to let us know how we did.<br>
        <br>" . $nps_url;

    $email_body .= "
        We hope to hear from you soon.<br>
        <br>
        Warmest regards,<br>
        Your Nose Creek Sport Physical Therapy<br>
        and Massage Therapy Team";
        
    $subject = 'Rate Your Visit To Nose Creek Sport Physical Therapy';
    
    //send_email('', $email, '', '', $subject, $email_body, '');
} ?>

<?php include ('../footer.php'); ?>