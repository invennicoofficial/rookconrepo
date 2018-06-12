<?php
/*
 * View follow up feedback.
 */
include ('../include.php');
checkAuthorised('confirmation');
error_reporting(0);
?>
</head>
<body>
<?php include_once ('../navigation.php'); ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">

            <form name="form_clients" method="post" action="" class="form-horizontal" role="form">

                <h1 class="single-pad-bottom">Follow Up Dashboard
                <?php
                    echo '<a href="config_confirmation.php" class="mobile-block pull-right"><img style="width:40px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me" /></a>';
                ?>
                </h1>

                <?php
                $value_config = ','.get_config($dbc, 'email_confirmation').',';
                ?>
                <div><?php
                    if ( check_subtab_persmission( $dbc, 'confirmation', ROLE, 'appointments' ) === true ) { ?>
                        <a href='email_confirmation.php'><button type="button" class="btn brand-btn mobile-block">Appointment Confirmations</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">Appointment Confirmations</button></a><?php
                    }
                    if ( check_subtab_persmission( $dbc, 'confirmation', ROLE, 'tickets' ) === true ) { ?>
                        <a href='ticket_notifications.php'><button type="button" class="btn brand-btn mobile-block">Ticket Notifications</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">Ticket Notifications</button></a><?php
                    }
                    if ( check_subtab_persmission( $dbc, 'confirmation', ROLE, 'followup' ) === true ) { ?>
                        <a href='feedback_send_notifications.php'><button type="button" class="btn brand-btn mobile-block active_tab">Follow Up</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">Follow Up</button></a><?php
                    } ?>
                </div>
                <div class="gap-top">
                    <span class="popover-examples list-inline" style="margin:0 0 0 10px;"><a data-toggle="tooltip" data-placement="top" title="Track and send emails to customers requesting feedback for services rendered."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                    if ( check_subtab_persmission( $dbc, 'confirmation', ROLE, 'followup' ) === true ) { ?>
                        <a href='feedback_send_notifications.php'><button type="button" class="btn brand-btn mobile-block">Send Follow Up Requests</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">Send Follow Up Requests</button></a><?php
                    } ?>

                    <span class="popover-examples list-inline" style="margin:0 0 0 10px;"><a data-toggle="tooltip" data-placement="top" title="Add customer feedback received via email, phone calls and text messages."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                    if ( check_subtab_persmission( $dbc, 'confirmation', ROLE, 'add_feedback' ) === true ) { ?>
                        <a href='feedback_add.php'><button type="button" class="btn brand-btn mobile-block">Add Follow Up Feedback</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">Add Follow Up Feedback</button></a><?php
                    } ?>

                    <span class="popover-examples list-inline" style="margin:0 0 0 10px;"><a data-toggle="tooltip" data-placement="top" title="View all customers feedback for services rendered."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                    if ( check_subtab_persmission( $dbc, 'confirmation', ROLE, 'view_feedback' ) === true ) { ?>
                        <a href='feedback_view.php'><button type="button" class="btn brand-btn mobile-block active_tab">View Follow Up Feedback</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">View Follow Up Feedback</button></a><?php
                    } ?>
                </div>

                <div class="notice double-gap-bottom popover-examples">
                <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
                <div class="col-sm-11"><span class="notice-name">NOTE:</span>
                View customer feedback for services rendered.</div>
                <div class="clearfix"></div>
                </div>

                <?php
                if (isset($_POST['search_email_submit'])) {
                    $starttime = $_POST['starttime'];
                    $endtime = $_POST['endtime'];
                    $therapist = $_POST['therapist'];
                }
                if($starttime == 0000-00-00) {
                    $starttime = date('Y-m-d');
                }
                if($endtime == 0000-00-00) {
                    $endtime = date('Y-m-d');
                }
                ?>
                <br>
                <center>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Appointment Date From:</label>
                        <div class="col-sm-2"><input name="starttime" type="text" class="datepicker form-control" value="<?php echo $starttime; ?>"></div>
                        <label class="col-sm-2 control-label">Appointment Date Until:</label>
                        <div class="col-sm-2"><input name="endtime" type="text" class="datepicker form-control" value="<?php echo $endtime; ?>"></div>
                        
                        <button type="submit" name="search_email_submit" value="Search" class="btn brand-btn mobile-block">Submit</button>
                    </div>
                </center><?php
                
                $result_booking = mysqli_query($dbc, "SELECT `f`.*, `b`.`patientid`, `b`.`therapistsid`, `b`.`appoint_date` FROM `followup_notifications` `f` JOIN `booking` `b` ON (`b`.`bookingid`=`f`.`bookingid`) WHERE `f`.`bookingid` IN (SELECT `bookingid` FROM `booking` WHERE DATE_FORMAT(`appoint_date`,'%Y-%m-%d') BETWEEN '$starttime' AND '$endtime')");

                if ( $result_booking->num_rows > 0 ) { ?>
                    <div id="no-more-tables">
                        <table class="table">
                            <tr class='hidden-xs hidden-sm'>
                                <th>Customer</th>
                                <th>Staff</th>
                                <th>Appointment Date</th>
                                <th>Feedback Method</th>
                                <th>Feedback Date</th>
                                <th>Feedback</th>
                                <th>Notes</th>
                            </tr><?php
                            while ( $row=mysqli_fetch_assoc($result_booking) ) { ?>
                                <tr>
                                    <td data-title="Customer"><?= get_contact($dbc, $row['patientid']) ?></td>
                                    <td data-title="Staff"><?= get_contact($dbc, $row['therapistsid']) ?></td>
                                    <td data-title="Appointment Date"><?= $row['appoint_date'] ?></td>
                                    <td data-title="Feedback Method"><?= $row['feedback_method'] ?></td>
                                    <td data-title="Feedback Date"><?= $row['feedback_date'] ?></td>
                                    <td data-title="Feedback"><?= html_entity_decode($row['feedback']) ?></td>
                                    <td data-title="Notes"><?= html_entity_decode($row['feedback_notes']) ?></td>
                                </tr><?php
                            } ?>
                        </table>
                    </div><?php
                    
                } else {
                    echo '<h2>No records found.</h2>';
                } ?>

            </form>
        </div>
    </div>
</div>

<?php include ('../footer.php'); ?>