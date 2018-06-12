<?php
/*
Testimonials : Patient responce on survey. And on testimonial give Patient to send Promotion.
*/
include ('../include.php');
checkAuthorised('crm');
error_reporting(0);

if (isset($_POST['promo_email'])) {
    $surveyresultid = $_POST['promo_email'];

    $get_staff =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT patientid FROM	crm_feedback_survey_result WHERE	surveyresultid='$surveyresultid'"));

    $patientid = $get_staff['patientid'];
    $serviceid = $_POST['serviceid_'.$surveyresultid];
    $expiry = $_POST['expiry_'.$surveyresultid];
    $email = get_email($dbc, $patientid);
    $patient_name = get_contact($dbc, $patientid);

    //if($amount != 0 || $amount != '') {
        $promo = $_POST['email_body'];
        $email_body = str_replace("[Customer Name]", $patient_name, $promo);
        $email_body = str_replace("[Expiry Date]", $expiry, $email_body);
        $email_body = str_replace("[Promotion Name]", get_all_from_service($dbc, $serviceid, 'heading'), $email_body);

        $subject = $_POST['email_subject'];

        //Mail
		try {
			send_email([$_POST['email_sender']=>$_POST['email_name']], $email, '', '', $subject, $email_body, '');
		} catch (Exception $e) {
			echo "<script> alert('Unable to send email to $email, please try again later.'); </script>";
		}
        //Mail
    //}

    $from_promo = 'Testimonials';
    $query_insert_inventory = "INSERT INTO `crm_promotion` (`from_promo`, `patientid`, `serviceid`, `expiry_date`) VALUES	('$from_promo', '$patientid', '$serviceid', '$expiry')";
    $result_insert_inventory = mysqli_query($dbc, $query_insert_inventory);
    $testimonial_promo = mysqli_insert_id($dbc);

    $query_update_patient = "UPDATE `crm_feedback_survey_result` SET `testimonial_promo` = '$testimonial_promo' WHERE `surveyresultid` = '$surveyresultid'";
    $result_update_patient = mysqli_query($dbc, $query_update_patient);

    //$query_update_patient = "UPDATE `patients` SET `promo_amount` = promo_amount + '$amount', `promo_expiry_date` = '$expiry' WHERE `contactid` = '$patientid'";
    //$result_update_vendor = mysqli_query($dbc, $query_update_patient);

    echo '<script type="text/javascript"> window.location.replace("testimonials.php"); </script>';
}
?>
<script type="text/javascript">
    function surveyConfig(sel) {
        var name = sel.name;
        var value = sel.value;
		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "<?php echo WEBSITE_URL;?>/ajax_all.php?fill=survey&name="+name+"&value="+value,
			dataType: "html",   //expect html to be returned
			success: function(response){
                location.reload();
			}
		});
    }
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
                echo '<a href="config_crm.php?category=testimonial" class="mobile-block pull-right"><img style="width:45px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me" /></a>'; ?>
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
                        <a href='testimonials.php'><button type="button" class="btn brand-btn mobile-block active_tab">Testimonials</button></a><?php
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
                        <a href='6month_follow_up_email.php'><button type="button" class="btn brand-btn mobile-block gap_left">6 Month Follow Up Email</button></a><?php
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
        
        <form name="form_clients" method="post" action="" class="form-inline" role="form">

            <a href="add_testimonial.php" class="btn brand-btn mobile-block gap-bottom pull-right">Add New Testimonial</a>

            <?php
            /* Pagination Counting */
            $rowsPerPage = 25;
            $pageNum = 1;

            if(isset($_GET['page'])) {
                $pageNum = $_GET['page'];
            }

            $offset = ($pageNum - 1) * $rowsPerPage;

            $query_check_credentials = "SELECT * FROM crm_feedback_survey_result WHERE testimonial_request!= '' ORDER BY surveyresultid DESC LIMIT $offset, $rowsPerPage";
            $pageQuery = "SELECT count(*) as numrows FROM crm_feedback_survey_result WHERE testimonial_request!= '' ORDER BY surveyresultid DESC";

            $result = mysqli_query($dbc, $query_check_credentials);

            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {

                // Added Pagination //
                echo display_pagination($dbc, $pageQuery, $pageNum, $rowsPerPage);
                // Pagination Finish //


                echo "<table class='table table-bordered'>";
                echo "<tr class='hidden-xs hidden-sm'>
                <th>Customer</th>
                <th>Staff</th>
                <th>Date</th>
                <th>Testimonial</th>
                <th>Public Permission</th>
                <th>Promotion Name</th>
                <th>Set Expiry Date</th>
                <th>Send Promotion</th>
                ";
                echo "</tr>";
            } else {
            	echo "<h2>No Record Found.</h2>";
            }
            $i=0;
            while($row = mysqli_fetch_array($result))
            {
                $testimonial_promo = $row['testimonial_promo'];
                $surveyresultid = $row['surveyresultid'];
                $get_staff =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT serviceid, expiry_date FROM	crm_promotion WHERE	promotionid='$testimonial_promo' AND from_promo='Testimonials'"));
                $serviceid = $get_staff['serviceid'];
                $expiry_date = $get_staff['expiry_date'];

                echo "<tr>";
                echo '<td data-title="Contact Person">'.get_contact($dbc, $row['patientid']).'</td>';
                echo '<td data-title="Contact Person">'.get_contact($dbc, $row['therapistid']).'</td>';
                echo '<td data-title="Contact Person">'.$row['today_date'].'</td>';
                echo '<td data-title="Contact Person">'.html_entity_decode($row['testimonial_request']).'</td>';
                echo '<td data-title="Contact Person">'.$row['public_permission'].'</td>';

                ?>
                <td data-title="Status">
                    <select data-placeholder="Choose a Promotion..." name="serviceid_<?php echo $surveyresultid; ?>" class="chosen-select-deselect form-control input-sm">
                    <?php
                    $query = mysqli_query($dbc,"SELECT serviceid, heading FROM services WHERE heading = 'Testimonial Promotion' AND deleted=0");
                    echo '<option value="">Please Select</option>';
                    while($row = mysqli_fetch_array($query)) {
                        if ($serviceid == $row['serviceid']) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value='". $row['serviceid']."'>".$row['heading'].'</option>';
                    }
                    ?>
                    </select>
                <?php
				echo '<td data-title="Reminder Date">
                    <input value="'.$expiry_date.'" name="expiry_'.$surveyresultid.'" type="text" placeholder="Click for Datepicker" class="datefuturepicker">
  				</td>';

                echo '<td>';
                echo '<button type="submit" name="promo_email" value="'.$surveyresultid.'" class="">Send</button>';
                echo '</td>';
                echo "</tr>";
                $i++;
            }

            echo '</table>';

            // Added Pagination //
            echo display_pagination($dbc, $pageQuery, $pageNum, $rowsPerPage);
            // Pagination Finish //

            ?>
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
					<input type="text" name="email_subject" class="form-control" value="<?= get_config($dbc, 'testimonial_promo_email_subject') ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Email Body:</label>
				<div class="col-sm-8">
					<textarea name="email_body" class="form-control"><?= html_entity_decode(get_config($dbc, 'testimonial_promo_email_body')) ?></textarea>
				</div>
			</div>
        </form>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
