<?php
/*
6 Month Follow Up Email : In last 6 month no booking maid with clinic then send them follow up email.
*/
include ('../include.php');
checkAuthorised('crm');
error_reporting(0);

if (isset($_POST['send_follow_up_email'])) {
    $follow_up_email_date = date('Y-m-d');

    for($i = 0; $i < count($_POST['check_send_email']); $i++) {
        $patientid = $_POST['check_send_email'][$i];
        $email = get_email($dbc, $patientid);

        $query_update_patient = "UPDATE `patients` SET `follow_up_email_date` = '$follow_up_email_date' WHERE `contactid` = '$patientid'";
        $result_update_vendor = mysqli_query($dbc, $query_update_patient);

        $promo = $_POST['email_body'];
        $email_body = str_replace("[Customer Name]", get_contact($dbc, $patientid), $promo);
        $email_body = str_replace("[Last Appointment Date]", get_all_from_patient($dbc, $patientid, 'follow_up_email_date'), $email_body);
        $subject = $_POST['email_subject'];

        //Mail
		try {
			send_email([$_POST['email_sender']=>$_POST['email_name']], $email, '', '', $subject, $email_body, '');
		} catch (Exception $e) {
			echo "<script> alert('Unable to send email to $email, please try again later.'); </script>";
		}
        //Mail

    }

    echo '<script type="text/javascript"> window.location.replace("6month_follow_up_email.php"); </script>';
}
?>
<script type="text/javascript">
$(document).ready(function() {
    $('#select_all').click(function(event) {  //on click
        if(this.checked) { // check select status
            $('.check_send_email').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"
            });
        }else{
            $('.check_send_email').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"
            });
        }
    });
});
</script>
</head>
<body>
<?php include_once ('../navigation.php');
?>

<div class="container triple-pad-bottom">
    <div class="row">
        <div class="col-md-12">

        <div class="row">
            <div class="col-xs-11"><h1 class="single-pad-bottom">CRM Dashboard</h1></div>
            <div class="col-xs-1 double-gap-top"><?php
                echo '<a href="config_crm.php?category=month" class="mobile-block pull-right"><img style="width:45px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me" /></a>'; ?>
            </div>
        </div>

        <?php $value_config = ','.get_config($dbc, 'crm_dashboard').','; ?>

        <div class="tab-container gap-top"><?php
            if (strpos($value_config, ',Referrals,') !== false) { ?>
                <div class="tab pull-left">
                    <span class="popover-examples list-inline" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="Track the Referrals you receive."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                    if ( check_subtab_persmission( $dbc, 'crm', ROLE, 'referrals' ) === true ) { ?>
                        <a href='referral.php'><button type="button" class="btn brand-btn mobile-block">Referrals</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">Referrals</button></a><?php
                    } ?>
                </div><?php
            }
            if (strpos($value_config, ',Recommendations,') !== false) { ?>
                <div class="tab pull-left">
                    <span class="popover-examples list-inline" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="Track the Referrals you receive."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                    if ( check_subtab_persmission( $dbc, 'crm', ROLE, 'recommendations' ) === true ) { ?>
                        <a href='recommendations.php'><button type="button" class="btn brand-btn mobile-block">Recommendations</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">Recommendations</button></a><?php
                    } ?>
                </div><?php
            }
            if (strpos($value_config, ',Surveys,') !== false) { ?>
                <div class="tab pull-left">
                    <span class="popover-examples list-inline" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="Send out Surveys to customers."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                    if ( check_subtab_persmission( $dbc, 'crm', ROLE, 'surveys' ) === true ) { ?>
                        <a href='survey.php'><button type="button" class="btn brand-btn mobile-block">Surveys</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">Surveys</button></a><?php
                    } ?>
                </div><?php
            }
            if (strpos($value_config, ',Testimonials,') !== false) { ?>
                <div class="tab pull-left">
                    <span class="popover-examples list-inline" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="Record and track Testimonials."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                    if ( check_subtab_persmission( $dbc, 'crm', ROLE, 'testimonials' ) === true ) { ?>
                        <a href='testimonials.php'><button type="button" class="btn brand-btn mobile-block">Testimonials</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">Testimonials</button></a><?php
                    } ?>
                </div><?php
            }
            if (strpos($value_config, ',Birthday & Promotion,') !== false) { ?>
                <div class="tab pull-left">
                    <span class="popover-examples list-inline" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="Track Birthdays and General Promotions."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                    if ( check_subtab_persmission( $dbc, 'crm', ROLE, 'birthdays_promotions' ) === true ) { ?>
                        <a href='birthday_promo.php'><button type="button" class="btn brand-btn mobile-block">Birthdays &amp; Promotions</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">Birthdays &amp; Promotions</button></a><?php
                    } ?>
                </div><?php
            }
            if (strpos($value_config, ',6 Month Follow Up Email,') !== false) { ?>
                <div class="tab pull-left">
                    <span class="popover-examples list-inline" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="Check in on/follow up with customers after 6 months to see how they are doing and potentially book future appointments."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                    if ( check_subtab_persmission( $dbc, 'crm', ROLE, 'follow_up_email' ) === true ) { ?>
                        <a href='6month_follow_up_email.php'><button type="button" class="btn brand-btn mobile-block gap_left active_tab">6 Month Follow Up Email</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">6 Month Follow Up Email</button></a><?php
                    } ?>
                </div><?php
            }
            if (strpos($value_config, ',Newsletter,') !== false) { ?>
                <div class="tab pull-left">
                    <span class="popover-examples list-inline" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="Newsletter"><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                    if ( check_subtab_persmission( $dbc, 'crm', ROLE, 'newsletter' ) === true ) { ?>
                        <a href='newsletter.php'><button type="button" class="btn brand-btn mobile-block gap_left">Newsletter</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">Newsletter</button></a><?php
                    } ?>
                </div><?php
            }
            if (strpos($value_config, ',Reminders,') !== false) { ?>
                <div class="tab pull-left">
                    <span class="popover-examples list-inline" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="Reminders"><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                    if ( check_subtab_persmission( $dbc, 'crm', ROLE, 'reminders' ) === true ) { ?>
                        <a href='reminders.php'><button type="button" class="btn brand-btn mobile-block gap_left">Reminders</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">Reminders</button></a><?php
                    } ?>
                </div><?php
            } ?>

            <!--<?php if (strpos($value_config, ',Confirmation Email,') !== false) { ?>
            <span>
                <span class="popover-examples list-inline" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="Send and track confirmation emails one month ahead."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                <a href='confirmation_email.php'><button type="button" class="btn brand-btn mobile-block">Confirmation Email</button></a>
            </span>
            <?php } ?>

            <?php if (strpos($value_config, ',Reminder Email,') !== false) { ?>
            <span>
                <span class="popover-examples list-inline" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="Send and track appointment reminder emails."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                <a href='reminder_email.php'><button type="button" class="btn brand-btn mobile-block">Reminder Email</button></a>
            </span>
            <?php } ?>-->
            <div class="clearfix"></div>
        </div><!-- .tab-container -->

        <form name="form_clients" method="post" action="" class="form-horizontal" role="form">

        <div class="notice double-gap-bottom popover-examples">
        <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
        <div class="col-sm-11"><span class="notice-name">NOTE:</span>
        Provides an opportunity to check in on/follow up with customers after 6 months to see how they are doing and potentially book future appointments.</div>
        <div class="clearfix"></div>
        </div>

        <?php
        echo '<span class="pull-right"><h4>Select All&nbsp;<input type="checkbox" id="select_all" class="form-control" style="width:25px;"/></h4></span>';

        $result =	mysqli_query($dbc,"SELECT MAX((str_to_date(substr(appoint_date,1,10),'%Y-%m-%d'))) AS max_date, patientid FROM booking	GROUP BY patientid");

        echo "<table class='table table-bordered'>";
        echo '<tr><th>Customer</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Last Appointment Date</th>
        <th>Last Follow Up Date</th>
        <th>Send Email</th>';
        echo "</tr>";

        while($row = mysqli_fetch_array($result)) {
            if(strtotime($row['max_date']) < strtotime('-6 month')) {
                $patientid = $row['patientid'];
                echo "<tr>";
                echo '<td>' . get_contact($dbc, $row['patientid']) . '</td>';
                echo '<td>' . get_email($dbc, $row['patientid']) . '</td>';
                echo '<td>' . get_contact_phone($dbc, $row['patientid']) . '</td>';
                echo '<td>' . $row['max_date'] . '</td>';
                echo '<td>' . get_all_from_patient($dbc, $row['patientid'], 'follow_up_email_date') . '</td>';
                echo '<td><input name="check_send_email[]" type="checkbox" value="'.$patientid.'" class="form-control check_send_email" style="width:25px;"/></td>';
                echo "</tr>";
            }
        }
        echo '</table>'; ?>
		
		<div class="form-group">
			<label class="col-sm-4 control-label">Sending Email Name:</label>
			<div class="col-sm-8">
				<input type="text" name="email_name" class="form-control" value="<?= get_contact($dbc, $_SESSION['contactid']) ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Sending Email Address:</label>
			<div class="col-sm-8">
				<input type="text" name="email_sender" class="form-control" value="<?= get_email($dbc, $_SESSION['contactid']) ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Email Subject:</label>
			<div class="col-sm-8">
				<input type="text" name="email_subject" class="form-control" value="<?= get_config($dbc, 'month6_follow_up_subject') ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Email Body:</label>
			<div class="col-sm-8">
				<textarea name="email_body" class="form-control"><?= html_entity_decode(get_config($dbc, 'month6_follow_up_body')) ?></textarea>
			</div>
		</div>
		
        <button type="submit" name="send_follow_up_email" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
        </form>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>