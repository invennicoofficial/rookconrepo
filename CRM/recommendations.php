<?php
/*
Referrals : Send Patient a Promotion if reffers someone.
*/
include ('../include.php');
checkAuthorised('crm');
error_reporting(0);

if (isset($_POST['promo_email'])) {
    $referralid = $_POST['promo_email'];
    $promotion = $_POST['promotion_'.$referralid];
    $expiry = $_POST['expiry_'.$referralid];

    $referrer_email = get_all_from_referralid($dbc, $referralid, 'referral_email');
    $referrer_name = get_all_from_referralid($dbc, $referralid, 'referrer_name');
    $referral_name = get_all_from_referralid($dbc, $referralid, 'referral_name');

    $query_update_ref = "UPDATE `crm_referrals` SET `promotion` = 1 WHERE `referralid` = '$referralid'";
    $result_update_ref = mysqli_query($dbc, $query_update_ref);

    if($referrer_email != '') {
        $promo = html_entity_decode(get_config($dbc, 'referral_promo_email_body'));
        $email_body = str_replace("[Referrer Name]", $referrer_name, $promo);
        $email_body = str_replace("[Referral Name]", $referral_name, $email_body);
        $email_body = str_replace("[Expiry Date]", $expiry, $email_body);
        $email_body = str_replace("[Promotion Name]", $promotion, $email_body);
        $subject = get_config($dbc, 'referral_promo_email_subject');
    }

    //Mail
    send_email('', $referrer_email, '', '', $subject, $email_body, '');
    //Mail

    /*
    $from_promo = 'Referral';
    $query_insert_inventory = "INSERT INTO `crm_promotion` (`from_promo`, `patientid`, `serviceid`, `expiry_date`) VALUES	('$from_promo', '$patientid', '$serviceid', '$expiry')";
    $result_insert_inventory = mysqli_query($dbc, $query_insert_inventory);
    $promotionid = mysqli_insert_id($dbc);

    $query_update_patient = "UPDATE `crm_referrals` SET `promotion` = '$promotionid' WHERE `referralid` = '$referralid'";
    $result_update_patient = mysqli_query($dbc, $query_update_patient);
    */

    echo '<script type="text/javascript"> alert("Referral Email sent."); window.location.replace("referral.php"); </script>';
}
?>
<script type="text/javascript">
$(document).ready(function(){
$('.iframe_open').click(function(){
		  if($(this).hasClass("adder")) {
		    $('#iframe_instead_of_window').attr('src', '<?php echo WEBSITE_URL; ?>/CRM/add_referral.php');
		    $('.iframe_title').text('Add New Referral');
		  } else {
			var id = $(this).attr('id');
		    $('#iframe_instead_of_window').attr('src', '<?php echo WEBSITE_URL; ?>/Booking/add_booking.php?referralid='+id);
		    $('.iframe_title').text('Add Booking');
		  }
			$('.iframe_holder').show(1000);
			$('.hide_on_iframe').hide(1000);
	});

	$('.close_iframer').click(function(){
		var result = confirm("Are you sure you want to close this window?");
		if (result) {
			$('.iframe_holder').hide(1000);
			$('.hide_on_iframe').show(1000);
			location.reload();
		}
	});
});
function followupDate(sel) {
	var action = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "<?php echo WEBSITE_URL;?>/ajax_all.php?fill=referral_followup&id="+arr[1]+'&name='+action,
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
    <div class='iframe_holder' style='display:none;'>

		<img src='<?php echo WEBSITE_URL; ?>/img/icons/close.png' class='close_iframer' width="45px" style='position:relative; right: 10px; float:right;top:58px; cursor:pointer;'>
		<span class='iframe_title' style='font-weight:bold; position: relative; left: 20px; font-size: 30px;'></span>
		<iframe id="iframe_instead_of_window" style='width: 100%;' height="1000px; border:0;" src=""></iframe>
    </div>
	<div class="row hide_on_iframe">
        <div class="col-md-12">

        <div class="row">
            <div class="col-xs-11"><h1 class="single-pad-bottom">CRM Dashboard</h1></div>
            <div class="col-xs-1 double-gap-top"><?php
                echo '<a href="config_crm.php?category=referral" class="mobile-block pull-right"><img style="width:45px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me" /></a>'; ?>
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
                        <a href='recommendations.php'><button type="button" class="btn brand-btn mobile-block active_tab">Recommendations</button></a><?php
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
        <div class="notice double-gap-bottom popover-examples">
        <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
        <div class="col-sm-11"><span class="notice-name">NOTE:</span>
        Used to track, maintain and reward positive relationships with customers, as well as relationships that refer customers.</div>
        <div class="clearfix"></div>
        </div>

            <center>
            <div class="form-group">
                <label for="site_name" class="col-sm-5 control-label">Search By Any:</label>
                <div class="col-sm-6">
                <?php if(isset($_POST['search_client_submit'])) { ?>
                    <input type="text" name="search_client" value="<?php echo $_POST['search_client']?>" class="form-control">
                <?php } else { ?>
                    <input type="text" name="search_client" class="form-control">
                <?php } ?>
                </div>
            </div>
            &nbsp;
				<button type="submit" name="search_client_submit" value="Search" class="btn brand-btn mobile-block">Search</button>
                <button type="submit" name="display_all_client" value="Display All" class="btn brand-btn mobile-block">Display All</button>
            </center>

            <div id="no-more-tables">

            <?php
            echo '<a href="add_referral.php" class="btn brand-btn mobile-block gap-bottom pull-right">Add New Referral</a>';

            //echo '<a class="btn brand-btn pull-right">Add New Referral</a><br>';
			//echo '<a class="btn brand-btn pull-right" href="#"  onclick=" window.open(\''.WEBSITE_URL.'/CRM/add_referral.php\', \'newwindow\', \'width=900, height=900\'); return false;">Add New Referral</a><br>';

            $vendor = '';
            if (isset($_POST['search_client_submit'])) {
				$vendor = filter_var($_POST['search_client'],FILTER_SANITIZE_STRING);
            }
            if (isset($_POST['display_all_client'])) {
                $vendor = '';
            }

            /* Pagination Counting */
            $rowsPerPage = 25;
            $pageNum = 1;

            if(isset($_GET['page'])) {
                $pageNum = $_GET['page'];
            }

            $offset = ($pageNum - 1) * $rowsPerPage;

            if($vendor != '') {
				$id_list = search_contacts_table($dbc, $vendor);
                $query_check_credentials = "SELECT * FROM crm_recommend WHERE `contactid` IN ($id_list) ORDER BY `completed_date` DESC LIMIT $offset, $rowsPerPage";
                $pageQuery = "SELECT count(*) as numrows FROM crm_recommend WHERE `contactid` IN ($id_list)";
            } else {
                $query_check_credentials = "SELECT * FROM crm_recommend ORDER BY `completed_date` DESC LIMIT $offset, $rowsPerPage";
                $pageQuery = "SELECT count(*) as numrows FROM crm_recommend";
            }

            $result = mysqli_query($dbc, $query_check_credentials);

            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {
                // Added Pagination //
                echo display_pagination($dbc, $pageQuery, $pageNum, $rowsPerPage);
                // Pagination Finish //

                echo "<table class='table table-bordered'>";
                echo "<tr class='hidden-xs hidden-sm'>
                    <th>Name</th>
                    <th>Date Completed</th>
                    <th>Recommend Scale (0-10)</th>
                    ";
                echo "</tr>";
				while($row = mysqli_fetch_array( $result ))
				{
					echo "<tr>";
					echo '<td data-title="Name">' . get_contact($dbc, $row['contactid']) . '</td>';
					echo '<td data-title="Date Completed"><a href="recommend_request.php?s=' . $row['recommend_id']. '" target="_blank">' . $row['completed_date']. '</a></td>';
					echo '<td data-title="Recommend Scale (0-10)">' . $row['recommend_response'] . '</td>';
					echo "</tr>";
				}

				echo '</table></div>';

				// Added Pagination //
				echo display_pagination($dbc, $pageQuery, $pageNum, $rowsPerPage);
				// Pagination Finish //
            } else {
            	echo "<h2>No Record Found.</h2>";
            } ?>
        </form>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
