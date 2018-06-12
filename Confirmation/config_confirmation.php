<?php
/*
Configuration - Choose which functionality you want for your software. Config email subject and body part for each functionality. Config Email Send Before days/month for patient treatment/booking email_confirmation and reminder.
*/
include ('../include.php');
checkAuthorised('confirmation');
error_reporting(0);

if (isset($_POST['submit'])) {

    $email_confirmation = implode(',',$_POST['email_confirmation']);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='email_confirmation'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$email_confirmation' WHERE name='email_confirmation'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('email_confirmation', '$email_confirmation')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }


    $call_confirmation = implode(',',$_POST['call_confirmation']);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='call_confirmation'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$call_confirmation' WHERE name='call_confirmation'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('call_confirmation', '$call_confirmation')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    //confirmation email
    $confirmation_email_subject = filter_var($_POST['confirmation_email_subject'],FILTER_SANITIZE_STRING);
    $confirmation = htmlentities($_POST['confirmation_email_body']);
    $confirmation_email_body = filter_var($confirmation,FILTER_SANITIZE_STRING);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='confirmation_email_subject'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$confirmation_email_subject' WHERE name='confirmation_email_subject'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('confirmation_email_subject', '$confirmation_email_subject')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='confirmation_email_body'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$confirmation_email_body' WHERE name='confirmation_email_body'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('confirmation_email_body', '$confirmation_email_body')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //confirmation email

    //followup email
    $followup_email_subject = filter_var($_POST['followup_email_subject'],FILTER_SANITIZE_STRING);
    $followup = htmlentities($_POST['followup_email_body']);
    $followup_email_body = filter_var($followup,FILTER_SANITIZE_STRING);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='followup_email_subject'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$followup_email_subject' WHERE name='followup_email_subject'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('followup_email_subject', '$followup_email_subject')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='followup_email_body'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$followup_email_body' WHERE name='followup_email_body'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('followup_email_body', '$followup_email_body')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //followup email

    echo '<script type="text/javascript"> window.location.replace("config_confirmation.php"); </script>';
}
?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<div class="panel-group" id="accordion">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_dashboard" >
                    Email Confirmation<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_dashboard" class="panel-collapse collapse">
            <div class="panel-body">

               <?php
                $value_config = ','.get_config($dbc, 'email_confirmation').',';
               ?>

              <input type="checkbox" <?php if (strpos($value_config, ','."1 Day".',') !== FALSE) { echo " checked"; } ?> value="1 Day" style="height: 20px; width: 20px;" name="email_confirmation[]">&nbsp;&nbsp;
              <span class="triple-pad-right control-label">1 Day</span>

              <input type="checkbox" <?php if (strpos($value_config, ','."2 Days".',') !== FALSE) { echo " checked"; } ?> value="2 Days" style="height: 20px; width: 20px;" name="email_confirmation[]">&nbsp;&nbsp;
              <span class="triple-pad-right control-label">2 Days</span>

              <input type="checkbox" <?php if (strpos($value_config, ','."1 Week".',') !== FALSE) { echo " checked"; } ?> value="1 Week" style="height: 20px; width: 20px;" name="email_confirmation[]">&nbsp;&nbsp;
              <span class="triple-pad-right control-label">1 Week</span>

              <input type="checkbox" <?php if (strpos($value_config, ','."1 Month".',') !== FALSE) { echo " checked"; } ?> value="1 Month" style="height: 20px; width: 20px;" name="email_confirmation[]">&nbsp;&nbsp;
              <span class="triple-pad-right control-label">1 Month</span>

              <input type="checkbox" <?php if (strpos($value_config, ','."3 Months".',') !== FALSE) { echo " checked"; } ?> value="3 Months" style="height: 20px; width: 20px;" name="email_confirmation[]">&nbsp;&nbsp;
              <span class="triple-pad-right control-label">3 Months</span>

              <input type="checkbox" <?php if (strpos($value_config, ','."Follow up Date".',') !== FALSE) { echo " checked"; } ?> value="Follow up Date" style="height: 20px; width: 20px;" name="email_confirmation[]">&nbsp;&nbsp;
              <span class="triple-pad-right control-label">Follow Up Date</span>

              <br><br>

                <div class="form-group">
                    <div class="col-sm-4 clearfix">
                        <a href="email_confirmation.php?time=fud" class="btn config-btn pull-right">Back</a>
                    </div>
                    <div class="col-sm-8">
                        <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_call" >
                    Call Confirmation<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_call" class="panel-collapse collapse">
            <div class="panel-body">

               <?php
                $value_config = ','.get_config($dbc, 'call_confirmation').',';
               ?>

              <input type="checkbox" <?php if (strpos($value_config, ','."1 Day".',') !== FALSE) { echo " checked"; } ?> value="1 Day" style="height: 20px; width: 20px;" name="call_confirmation[]">&nbsp;&nbsp;
              <span class="triple-pad-right control-label">1 Day</span>

              <input type="checkbox" <?php if (strpos($value_config, ','."2 Days".',') !== FALSE) { echo " checked"; } ?> value="2 Days" style="height: 20px; width: 20px;" name="call_confirmation[]">&nbsp;&nbsp;
              <span class="triple-pad-right control-label">2 Days</span>

              <input type="checkbox" <?php if (strpos($value_config, ','."1 Week".',') !== FALSE) { echo " checked"; } ?> value="1 Week" style="height: 20px; width: 20px;" name="call_confirmation[]">&nbsp;&nbsp;
              <span class="triple-pad-right control-label">1 Week</span>

              <input type="checkbox" <?php if (strpos($value_config, ','."1 Month".',') !== FALSE) { echo " checked"; } ?> value="1 Month" style="height: 20px; width: 20px;" name="call_confirmation[]">&nbsp;&nbsp;
              <span class="triple-pad-right control-label">1 Month</span>

              <input type="checkbox" <?php if (strpos($value_config, ','."3 Months".',') !== FALSE) { echo " checked"; } ?> value="3 Months" style="height: 20px; width: 20px;" name="call_confirmation[]">&nbsp;&nbsp;
              <span class="triple-pad-right control-label">3 Months</span>

              <input type="checkbox" <?php if (strpos($value_config, ','."Follow up Date".',') !== FALSE) { echo " checked"; } ?> value="Follow up Date" style="height: 20px; width: 20px;" name="call_confirmation[]">&nbsp;&nbsp;
              <span class="triple-pad-right control-label">Follow Up Date</span>

              <br><br>

                <div class="form-group">
                    <div class="col-sm-4 clearfix">
                        <a href="email_confirmation.php?time=fud" class="btn config-btn pull-right">Back</a>
                    </div>
                    <div class="col-sm-8">
                        <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_confirm" >
                    Confirmation Email<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_confirm" class="panel-collapse collapse">
            <div class="panel-body">

               <?php
                $confirmation_email_body = html_entity_decode(get_config($dbc, 'confirmation_email_body'));
                $confirmation_email_subject = get_config($dbc, 'confirmation_email_subject');
               ?>

              <div class="form-group">
                <label for="fax_number"	class="col-sm-4	control-label">Email Subject:</label>
                <div class="col-sm-8">
                    <input name="confirmation_email_subject" type="text" value = "<?php echo $confirmation_email_subject; ?>" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label for="fax_number"	class="col-sm-4	control-label">Email Body:<br>Use Below tags <br> [Customer Name] <br>[Therapist Name] <br>[Appointment Date]<br>[Staff Profile Link]<br>[Confirmation Link]<br>[Reschedule]<br>[Cancel]<br>[Feedback Link]</label>
                <div class="col-sm-8">
                    <textarea name="confirmation_email_body" rows="5" cols="50" class="form-control"><?php echo $confirmation_email_body; ?></textarea>
                </div>
              </div>

                <div class="form-group">
                    <div class="col-sm-4 clearfix">
                        <a href="email_confirmation.php?time=fud" class="btn config-btn pull-right">Back</a>
                    </div>
                    <div class="col-sm-8">
                        <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Follow Up -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_followup" >
                    Follow Up Email<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_followup" class="panel-collapse collapse">
            <div class="panel-body">

               <?php
                $followup_email_body = html_entity_decode(get_config($dbc, 'followup_email_body'));
                $followup_email_subject = get_config($dbc, 'followup_email_subject');
               ?>

              <div class="form-group">
                <label for="fax_number"	class="col-sm-4	control-label">Email Subject:</label>
                <div class="col-sm-8">
                    <input name="followup_email_subject" type="text" value = "<?php echo $followup_email_subject; ?>" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label for="fax_number"	class="col-sm-4	control-label">Email Body:<br>Use Below tags <br>[Customer Name]<br>[Therapist Name]<br>[Appointment Date]<br>[Staff Profile Link]<br>[Feedback Link]</label>
                <div class="col-sm-8">
                    <textarea name="followup_email_body" rows="5" cols="50" class="form-control"><?php echo $followup_email_body; ?></textarea>
                </div>
              </div>

                <div class="form-group">
                    <div class="col-sm-4 clearfix">
                        <a href="email_confirmation.php?time=fud" class="btn config-btn pull-right">Back</a>
                    </div>
                    <div class="col-sm-8">
                        <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
                    </div>
                </div>

            </div>
        </div>
    </div><!-- .panel -->
</div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>