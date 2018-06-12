<?php
/*
On Patient's Birthday, Send them birthday wishes and if Good history then send them Birthday promotion.
*/
include ('../include.php');
checkAuthorised('crm');
error_reporting(0);

if (isset($_POST['send_follow_up_email'])) {

    for($i = 0; $i < count($_POST['check_send_email']); $i++) {
        $patientid = $_POST['check_send_email'][$i];
        $email = get_email($dbc, $patientid);
        $patient_name = get_contact($dbc, $patientid);

        $serviceid = filter_var($_POST['promo_'.$patientid],FILTER_SANITIZE_STRING);
        $expiry = $_POST['expiry_'.$patientid];

        if($serviceid != '') {
            $promo = html_entity_decode(get_config($dbc, 'birthday_promo_email_body'));
            $email_body = str_replace("[Customer Name]", $patient_name, $promo);
            $email_body = str_replace("[Expiry Date]", $expiry, $email_body);
            $email_body = str_replace("[Promotion Name]", $serviceid, $email_body);
            $subject = get_config($dbc, 'birthday_promo_email_subject');
        } else {
            $bday = html_entity_decode(get_config($dbc, 'birthday_email_body'));
            $email_body = str_replace("[Customer Name]", $patient_name, $bday);
            $subject = get_config($dbc, 'birthday_email_subject');
        }

        //Mail
		try {
			send_email([$_POST['email_sender']=>$_POST['email_name']], $email, '', '', $subject, $email_body, '');
		} catch (Exception $e) {
			echo "<script> alert('Unable to send email to $email, please try again later.'); </script>";
		}
        //Mail

        $from_promo = 'Birthday';
        $query_insert_inventory = "INSERT INTO `crm_promotion` (`from_promo`, `patientid`, `promotion`, `expiry_date`) VALUES	('$from_promo', '$patientid', '$serviceid', '$expiry')";
        $result_insert_inventory = mysqli_query($dbc, $query_insert_inventory);
    }

    //$query_update_patient = "UPDATE `patients` SET `promo_amount` = promo_amount + '$amount', `promo_expiry_date` = '$expiry' WHERE `contactid` = '$patientid'";
    //$result_update_vendor = mysqli_query($dbc, $query_update_patient);

    echo '<script type="text/javascript"> window.location.replace("birthday_promo.php"); </script>';
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

function expiryDate(sel) {
	var action = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "<?php echo WEBSITE_URL;?>/ajax_all.php?fill=birthday_promo&id="+arr[1]+'&name='+action,
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
                echo '<a href="config_crm.php?category=birthday" class="mobile-block pull-right"><img style="width:45px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me" /></a>'; ?>
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
                        <a href='birthday_promo.php'><button type="button" class="btn brand-btn mobile-block active_tab">Birthdays &amp; Promotions</button></a><?php
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

        <span class="popover-examples list-inline pull-right">
            <a href="#job_file" data-toggle="tooltip" data-placement="top" title="Do not select a Promotion when sending out Birthday messages."><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="25"></a>
        </span>
        <form name="form_clients" method="post" action="" class="form-inline" role="form">
        <div class="notice double-gap-bottom popover-examples">
        <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
        <div class="col-sm-11"><span class="notice-name">NOTE:</span>
        Tracks customer birthdays so that happy birthday wishes can be emailed, and tracks promotions provided to each customer and when the promotion expires.</div>
        <div class="clearfix"></div>
        </div>

            <?php

            /* Pagination Counting */
            $rowsPerPage = 25;
            $pageNum = 1;

            if(isset($_GET['page'])) {
                $pageNum = $_GET['page'];
            }

            $offset = ($pageNum - 1) * $rowsPerPage;

            $query_check_credentials = "SELECT * FROM contacts WHERE deleted = 0 AND category= 'Patient' AND DATE_FORMAT(birth_date, '%m-%d') = DATE_FORMAT(NOW(), '%m-%d') AND email_address != '' AND email_address != 'no' AND email_address != 'no@email.com' ORDER by status DESC";
            $pageQuery = "SELECT count(*) as numrows FROM contacts WHERE deleted = 0 AND category= 'Patient' AND DATE_FORMAT(birth_date, '%m-%d') = DATE_FORMAT(NOW(), '%m-%d')";

            $result = mysqli_query($dbc, $query_check_credentials);

            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) { ?>
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
                <br><br><button type="submit" name="send_follow_up_email" value="Submit" class="btn brand-btn pull-right">Send</button><br><br>
                <span class="pull-right"><h4>Select All&nbsp;<input type="checkbox" id="select_all" class="form-control" style="width:25px;"/></h4></span>

                <?php // Added Pagination //
                //echo display_pagination($dbc, $pageQuery, $pageNum, $rowsPerPage);
                // Pagination Finish //

                echo "<table class='table table-bordered'>";
                echo "<tr class='hidden-xs hidden-sm'>
                <th>Customer</th>
                <th>Email</th>
                <th>Status</th>
                <th># of Visits</th>
                <th>Promotion</th>
                <th>Set Expiry Date</th>
                <th>Send</th>";
                echo "</tr>";
            } else {
            	echo "<h2>No Record Found.</h2>";
            }

            while($row = mysqli_fetch_array($result))
            {
                echo "<tr>";
                $patientid = $row['contactid'];
                $get_staff =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT promotion, expiry_date, count(promotionid) AS total_promo FROM	crm_promotion WHERE	patientid='$patientid' AND from_promo='Birthday'"));
                $promotion = $get_staff['promotion'];
                $expiry_date = $get_staff['expiry_date'];

                echo '<td data-title="Contact Person">'.get_contact($dbc, $patientid).'</td>';
                echo '<td data-title="Contact Person">'.get_email($dbc, $patientid).'</td>';

                if(get_staff_field($dbc, $patientid, 'status')== 0) {
                    $st = 'Inactive';
                } else {
                    $st = 'Active';
                }
                echo '<td data-title="Contact Person">'.$st.'</td>';

                echo '<td data-title="Contact Person">'.get_history($dbc, $patientid).' Times</td>';

                ?>
                <td data-title="Status">
                    <select data-placeholder="Choose a Promotion..." name="promo_<?php echo $patientid; ?>" class="chosen-select-deselect form-control input-sm">
					<?php
                    $tabs = get_config($dbc, 'birthday_promotions');
                    $each_tab = explode(',', $tabs);
                    echo '<option value="">Birthday Wish</option>';
                    foreach ($each_tab as $cat_tab) {
                        if ($promotion == $cat_tab) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value='". $cat_tab."'>".$cat_tab.'</option>';
                    }
					?>
                    </select>
                </td>
                <?php
				echo '<td data-title="Reminder Date">
                    <input name="expiry_'.$patientid.'" id="patient_'.$patientid.'" type="text" placeholder="Click for Datepicker" value="'.$expiry_date.'" class="datefuturepicker">
  				</td>';

                //echo '<td>';
                if($get_staff['total_promo'] >= 1) {
                    echo '<td>-</td>';
                } else {
                    echo '<td><input name="check_send_email[]" type="checkbox" value="'.$patientid.'" class="form-control check_send_email" style="width:25px;"/></td>';
                }

               // echo '<button type="submit" name="promo_email" value="'.$patientid.'" class="">Send</button>';
                //echo '</td>';

                echo "</tr>";
            }

            echo '</table>';

            // Added Pagination //
            //echo display_pagination($dbc, $pageQuery, $pageNum, $rowsPerPage);
            // Pagination Finish //

            ?>
        </form>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
