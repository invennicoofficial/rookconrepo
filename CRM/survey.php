<?php
/*
List all surverys and On/Off status to use send to Patient.
*/
include ('../include.php');
checkAuthorised('crm');
error_reporting(0);

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
                echo '<a href="config_crm.php?category=survey" class="mobile-block pull-right"><img style="width:45px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me" /></a>'; ?>
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
                        <a href='survey.php'><button type="button" class="btn brand-btn mobile-block active_tab">Surveys</button></a><?php
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
        This allows management to create to surveys, and at the Point of Sale you can select a survey to email to the customer.<br>At the Point of Sale, you can request a Testimonial be submitted by the customer.</div>
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

            $query_check_credentials = "SELECT * FROM crm_feedback_survey_result ORDER BY surveyresultid DESC LIMIT $offset, $rowsPerPage";
            $pageQuery = "SELECT count(*) as numrows FROM crm_feedback_survey_result ORDER BY surveyresultid DESC";

            $result = mysqli_query($dbc, $query_check_credentials);

            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {
                // Added Pagination //
                echo display_pagination($dbc, $pageQuery, $pageNum, $rowsPerPage);
                // Pagination Finish //

                echo "<table class='table table-bordered'>";
                echo "<tr class='hidden-xs hidden-sm'>
                <th>Survey ID#</th>
                <th>Customer</th>
                <th>Staff</th>
                <th>Send Date</th>
                <th>Completed Date</th>
                <th>Result</th>";
                echo "</tr>";
            } else {
            	echo "<h2>No Record Found.</h2>";
            }

            while($row = mysqli_fetch_array($result))
            {
                echo "<tr>";
                echo '<td data-title="Survey ID#">'.$row['surveyid'].'</td>';
                echo '<td data-title="Customer">'.get_contact($dbc, $row['patientid']).'</td>';
                echo '<td data-title="Staff">'.get_contact($dbc, $row['therapistid']).'</td>';
                echo '<td data-title="Survey ID#">'.$row['send_date'].'</td>';
                echo '<td data-title="Survey ID#">'.$row['fill_date'].'</td>';
                //echo '<td><a id="'.$row['surveyid'].'_'.$row['surveyresultid'].'" class="iframe_open">View</a></td>';
                if($row['fill_date'] != '') {
				    echo '<td><a href="#" onclick=" window.open(\''.WEBSITE_URL.'/CRM/feedback_survey.php?surveyid='.$row['surveyid'].'&surveyresultid='.$row['surveyresultid'].'\', \'newwindow\', \'width=900, height=900\'); return false;">View</a></td>';
                } else {
                    echo '<td data-title="Survey ID#">-</td>';
                }

                echo "</tr>";
            }

            echo '</table>';
            // Added Pagination //
            echo display_pagination($dbc, $pageQuery, $pageNum, $rowsPerPage);
            // Pagination Finish //

            ?>
        </form>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
