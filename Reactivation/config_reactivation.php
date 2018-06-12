<?php
/*
Configuration - Choose which functionality you want for your software. Config email subject and body part for each functionality. Config Email Send Before days/month for patient treatment/booking confirmation and reminder.
*/
include ('../include.php');
checkAuthorised('reactivation');
error_reporting(0);

if (isset($_POST['submit'])) {

    $active_reactivation = implode(',',$_POST['active_reactivation']);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='active_reactivation'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$active_reactivation' WHERE name='active_reactivation'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('active_reactivation', '$active_reactivation')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    //confirmation email
    $massage_drop_off_analysis_subject = filter_var($_POST['massage_drop_off_analysis_subject'],FILTER_SANITIZE_STRING);
    $confirmation = htmlentities($_POST['massage_drop_off_analysis_body']);
    $massage_drop_off_analysis_body = filter_var($confirmation,FILTER_SANITIZE_STRING);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='massage_drop_off_analysis_subject'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$massage_drop_off_analysis_subject' WHERE name='massage_drop_off_analysis_subject'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('massage_drop_off_analysis_subject', '$massage_drop_off_analysis_subject')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='massage_drop_off_analysis_body'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$massage_drop_off_analysis_body' WHERE name='massage_drop_off_analysis_body'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('massage_drop_off_analysis_body', '$massage_drop_off_analysis_body')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //confirmation email

    //confirmation email
    $physio_drop_off_analysis_subject = filter_var($_POST['physio_drop_off_analysis_subject'],FILTER_SANITIZE_STRING);
    $confirmation = htmlentities($_POST['physio_drop_off_analysis_body']);
    $physio_drop_off_analysis_body = filter_var($confirmation,FILTER_SANITIZE_STRING);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='physio_drop_off_analysis_subject'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$physio_drop_off_analysis_subject' WHERE name='physio_drop_off_analysis_subject'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('physio_drop_off_analysis_subject', '$physio_drop_off_analysis_subject')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='physio_drop_off_analysis_body'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$physio_drop_off_analysis_body' WHERE name='physio_drop_off_analysis_body'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('physio_drop_off_analysis_body', '$physio_drop_off_analysis_body')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //confirmation email

    //Inactive Reactivation
    $inactive_reactivation_subject = filter_var($_POST['inactive_reactivation_subject'],FILTER_SANITIZE_STRING);
    $confirmation1 = htmlentities($_POST['inactive_reactivation_body']);
    $inactive_reactivation_body = filter_var($confirmation1,FILTER_SANITIZE_STRING);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='inactive_reactivation_subject'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$inactive_reactivation_subject' WHERE name='inactive_reactivation_subject'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('inactive_reactivation_subject', '$inactive_reactivation_subject')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='inactive_reactivation_body'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$inactive_reactivation_body' WHERE name='inactive_reactivation_body'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('inactive_reactivation_body', '$inactive_reactivation_body')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //Inactive Reactivation

    //Survey
    $survey_deactivated_contact_subject = filter_var($_POST['survey_deactivated_contact_subject'],FILTER_SANITIZE_STRING);
    $survey = htmlentities($_POST['survey_deactivated_contact_body']);
    $survey_deactivated_contact_body = filter_var($survey,FILTER_SANITIZE_STRING);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='survey_deactivated_contact_subject'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value='$survey_deactivated_contact_subject' WHERE name='survey_deactivated_contact_subject'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('survey_deactivated_contact_subject', '$survey_deactivated_contact_subject')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='survey_deactivated_contact_body'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value='$survey_deactivated_contact_body' WHERE name='survey_deactivated_contact_body'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('survey_deactivated_contact_body', '$survey_deactivated_contact_body')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //Survey

    //Offer 1
    $offer1_deactivated_contact_subject = filter_var($_POST['offer1_deactivated_contact_subject'],FILTER_SANITIZE_STRING);
    $offer1 = htmlentities($_POST['offer1_deactivated_contact_body']);
    $offer1_deactivated_contact_body = filter_var($offer1,FILTER_SANITIZE_STRING);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='offer1_deactivated_contact_subject'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value='$offer1_deactivated_contact_subject' WHERE name='offer1_deactivated_contact_subject'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('offer1_deactivated_contact_subject', '$offer1_deactivated_contact_subject')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='offer1_deactivated_contact_body'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value='$offer1_deactivated_contact_body' WHERE name='offer1_deactivated_contact_body'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('offer1_deactivated_contact_body', '$offer1_deactivated_contact_body')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //Offer 1

    //Offer 2
    $offer2_deactivated_contact_subject = filter_var($_POST['offer2_deactivated_contact_subject'],FILTER_SANITIZE_STRING);
    $offer2 = htmlentities($_POST['offer2_deactivated_contact_body']);
    $offer2_deactivated_contact_body = filter_var($offer2,FILTER_SANITIZE_STRING);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='offer2_deactivated_contact_subject'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value='$offer2_deactivated_contact_subject' WHERE name='offer2_deactivated_contact_subject'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('offer2_deactivated_contact_subject', '$offer2_deactivated_contact_subject')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='offer2_deactivated_contact_body'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value='$offer2_deactivated_contact_body' WHERE name='offer2_deactivated_contact_body'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('offer2_deactivated_contact_body', '$offer2_deactivated_contact_body')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //Offer 2

    //Offer 3
    $offer3_deactivated_contact_subject = filter_var($_POST['offer3_deactivated_contact_subject'],FILTER_SANITIZE_STRING);
    $offer3 = htmlentities($_POST['offer3_deactivated_contact_body']);
    $offer3_deactivated_contact_body = filter_var($offer3, FILTER_SANITIZE_STRING);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='offer3_deactivated_contact_subject'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value='$offer3_deactivated_contact_subject' WHERE name='offer3_deactivated_contact_subject'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('offer3_deactivated_contact_subject', '$offer3_deactivated_contact_subject')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='offer3_deactivated_contact_body'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value='$offer3_deactivated_contact_body' WHERE name='offer3_deactivated_contact_body'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('offer3_deactivated_contact_body', '$offer3_deactivated_contact_body')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //Offer 3

    $from=$_POST['from'];
    echo '<script type="text/javascript"> window.location.replace("config_reactivation.php"); </script>';
}
?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row col-sm-12">
<h1>Reactivation</h1>
<div class="gap-top gap-left double-gap-bottom"><a href="active_reactivation.php" class="btn config-btn">Back To Dashbaord</a></div>
<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<div class="panel-group" id="accordion">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_dashboard" >
                    Active Reactivation Setting<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_dashboard" class="panel-collapse collapse">
            <div class="panel-body">

               <?php
                $value_config = ','.get_config($dbc, 'active_reactivation').',';
               ?>

              <input type="checkbox" <?php if (strpos($value_config, ','."1 Week".',') !== FALSE) { echo " checked"; } ?> value="1 Week" style="height: 20px; width: 20px;" name="active_reactivation[]">&nbsp;&nbsp;
              <span class="triple-pad-right control-label">1 Week</span>

              <input type="checkbox" <?php if (strpos($value_config, ','."2 Week".',') !== FALSE) { echo " checked"; } ?> value="2 Week" style="height: 20px; width: 20px;" name="active_reactivation[]">&nbsp;&nbsp;
              <span class="triple-pad-right control-label">2 Weeks</span>

              <input type="checkbox" <?php if (strpos($value_config, ','."1 Month".',') !== FALSE) { echo " checked"; } ?> value="1 Month" style="height: 20px; width: 20px;" name="active_reactivation[]">&nbsp;&nbsp;
              <span class="triple-pad-right control-label">1 Month</span>

              <input type="checkbox" <?php if (strpos($value_config, ','."3 Months".',') !== FALSE) { echo " checked"; } ?> value="3 Months" style="height: 20px; width: 20px;" name="active_reactivation[]">&nbsp;&nbsp;
              <span class="triple-pad-right control-label">3 Months</span>

              <br><br>

                <div class="form-group">
                    <div class="col-sm-4 clearfix">
                        <a href="active_reactivation.php" class="btn config-btn pull-right">Back</a>
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
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_inactive" >
                    Inactive Reactivation Setting<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_inactive" class="panel-collapse collapse">
            <div class="panel-body">

              <input type="checkbox" <?php if (strpos($value_config, ','."3 Months".',') !== FALSE) { echo " checked"; } ?> value="3 Months" style="height: 20px; width: 20px;" name="active_reactivation[]">&nbsp;&nbsp;
              <span class="triple-pad-right control-label">3 Months</span>

              <input type="checkbox" <?php if (strpos($value_config, ','."6 Months".',') !== FALSE) { echo " checked"; } ?> value="6 Months" style="height: 20px; width: 20px;" name="active_reactivation[]">&nbsp;&nbsp;
              <span class="triple-pad-right control-label">6 Months</span>

              <input type="checkbox" <?php if (strpos($value_config, ','."1 Year".',') !== FALSE) { echo " checked"; } ?> value="1 Year" style="height: 20px; width: 20px;" name="active_reactivation[]">&nbsp;&nbsp;
              <span class="triple-pad-right control-label">1 Year</span>

              <br><br>

                <div class="form-group">
                    <div class="col-sm-4 clearfix">
                        <a href="active_reactivation.php" class="btn config-btn pull-right">Back</a>
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
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_confirm1" >
                    Massage Drop Off Analysis - Active Reactivation Email<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_confirm1" class="panel-collapse collapse">
            <div class="panel-body">

               <?php
                $massage_drop_off_analysis_body = html_entity_decode(get_config($dbc, 'massage_drop_off_analysis_body'));
                $massage_drop_off_analysis_subject = get_config($dbc, 'massage_drop_off_analysis_subject');
               ?>

              <div class="form-group">
                <label for="fax_number"	class="col-sm-4	control-label">Email Subject:</label>
                <div class="col-sm-8">
                    <input name="massage_drop_off_analysis_subject" type="text" value = "<?php echo $massage_drop_off_analysis_subject; ?>" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label for="fax_number"	class="col-sm-4	control-label">Email Body:</label>
                <div class="col-sm-8">
                    <textarea name="massage_drop_off_analysis_body" rows="5" cols="50" class="form-control"><?php echo $massage_drop_off_analysis_body; ?></textarea>
                </div>
              </div>

                <div class="form-group">
                    <div class="col-sm-4 clearfix">
                        <a href="active_reactivation.php" class="btn config-btn pull-right">Back</a>
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
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_confirm2" >
                    Physiotherapy Drop Off Analysis - Active Reactivation Email<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_confirm2" class="panel-collapse collapse">
            <div class="panel-body">

               <?php
                $physio_drop_off_analysis_body = html_entity_decode(get_config($dbc, 'physio_drop_off_analysis_body'));
                $physio_drop_off_analysis_subject = get_config($dbc, 'physio_drop_off_analysis_subject');
               ?>

              <div class="form-group">
                <label for="fax_number"	class="col-sm-4	control-label">Email Subject:</label>
                <div class="col-sm-8">
                    <input name="physio_drop_off_analysis_subject" type="text" value = "<?php echo $physio_drop_off_analysis_subject; ?>" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label for="fax_number"	class="col-sm-4	control-label">Email Body:</label>
                <div class="col-sm-8">
                    <textarea name="physio_drop_off_analysis_body" rows="5" cols="50" class="form-control"><?php echo $physio_drop_off_analysis_body; ?></textarea>
                </div>
              </div>

                <div class="form-group">
                    <div class="col-sm-4 clearfix">
                        <a href="active_reactivation.php" class="btn config-btn pull-right">Back</a>
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
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_confirm23" >
                    Inactive Reactivation Email<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_confirm23" class="panel-collapse collapse">
            <div class="panel-body">

               <?php
                $inactive_reactivation_body = html_entity_decode(get_config($dbc, 'inactive_reactivation_body'));
                $inactive_reactivation_subject = get_config($dbc, 'inactive_reactivation_subject');
               ?>

              <div class="form-group">
                <label for="fax_number"	class="col-sm-4	control-label">Email Subject:</label>
                <div class="col-sm-8">
                    <input name="inactive_reactivation_subject" type="text" value = "<?php echo $inactive_reactivation_subject; ?>" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="fax_number"	class="col-sm-4	control-label">Email Body:</label>
                <div class="col-sm-8">
                    <textarea name="inactive_reactivation_body" rows="5" cols="50" class="form-control"><?php echo $inactive_reactivation_body; ?></textarea>
                </div>
              </div>

                <div class="form-group">
                    <div class="col-sm-4 clearfix">
                        <a href="active_reactivation.php" class="btn config-btn pull-right">Back</a>
                    </div>
                    <div class="col-sm-8">
                        <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Survey -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_survey" >
                    Survey Email<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>
        <div id="collapse_survey" class="panel-collapse collapse">
            <div class="panel-body"><?php
                $survey_deactivated_contact_subject = get_config($dbc, 'survey_deactivated_contact_subject');
                $survey_deactivated_contact_body = html_entity_decode(get_config($dbc, 'survey_deactivated_contact_body')); ?>

                <div class="form-group">
                    <label for="fax_number"	class="col-sm-4	control-label">Email Subject:</label>
                    <div class="col-sm-8"><input name="survey_deactivated_contact_subject" type="text" value="<?= $survey_deactivated_contact_subject; ?>" class="form-control" /></div>
                </div>
                <div class="form-group">
                    <label for="fax_number"	class="col-sm-4	control-label">Email Body:<br>Use Below tags<br>[Customer Name]<br></label>
                    <div class="col-sm-8"><textarea name="survey_deactivated_contact_body" rows="5" cols="50" class="form-control"><?= $survey_deactivated_contact_body; ?></textarea></div>
                </div>
                <div class="form-group">
                    <div class="col-sm-4 clearfix"><a href="active_reactivation.php" class="btn config-btn pull-right">Back</a></div>
                    <div class="col-sm-8"><button type="submit" name="submit" value="Submit" class="btn config-btn btn-lg pull-right">Submit</button></div>
                </div>
            </div>
        </div>
    </div><!-- .panel -->

    <!-- Offer 1 -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_offer1" >
                    Offer 1 Email (3 Months)<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>
        <div id="collapse_offer1" class="panel-collapse collapse">
            <div class="panel-body"><?php
                $offer1_deactivated_contact_subject = get_config($dbc, 'offer1_deactivated_contact_subject');
                $offer1_deactivated_contact_body = html_entity_decode(get_config($dbc, 'offer1_deactivated_contact_body')); ?>

                <div class="form-group">
                    <label for="fax_number"	class="col-sm-4	control-label">Email Subject:</label>
                    <div class="col-sm-8"><input name="offer1_deactivated_contact_subject" type="text" value="<?= $offer1_deactivated_contact_subject; ?>" class="form-control" /></div>
                </div>
                <div class="form-group">
                    <label for="fax_number"	class="col-sm-4	control-label">Email Body:<br>Use Below tags<br>[Customer Name]<br></label>
                    <div class="col-sm-8"><textarea name="offer1_deactivated_contact_body" rows="5" cols="50" class="form-control"><?= $offer1_deactivated_contact_body; ?></textarea></div>
                </div>
                <div class="form-group">
                    <div class="col-sm-4 clearfix"><a href="active_reactivation.php" class="btn config-btn pull-right">Back</a></div>
                    <div class="col-sm-8"><button type="submit" name="submit" value="Submit" class="btn config-btn btn-lg pull-right">Submit</button></div>
                </div>
            </div>
        </div>
    </div><!-- .panel -->

    <!-- Offer 2 -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_offer2" >
                    Offer 2 Email (6 Months)<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>
        <div id="collapse_offer2" class="panel-collapse collapse">
            <div class="panel-body"><?php
                $offer2_deactivated_contact_subject = get_config($dbc, 'offer2_deactivated_contact_subject');
                $offer2_deactivated_contact_body = html_entity_decode(get_config($dbc, 'offer2_deactivated_contact_body')); ?>

                <div class="form-group">
                    <label for="fax_number"	class="col-sm-4	control-label">Email Subject:</label>
                    <div class="col-sm-8"><input name="offer2_deactivated_contact_subject" type="text" value="<?= $offer2_deactivated_contact_subject; ?>" class="form-control" /></div>
                </div>
                <div class="form-group">
                    <label for="fax_number"	class="col-sm-4	control-label">Email Body:<br>Use Below tags<br>[Customer Name]<br></label>
                    <div class="col-sm-8"><textarea name="offer2_deactivated_contact_body" rows="5" cols="50" class="form-control"><?= $offer2_deactivated_contact_body; ?></textarea></div>
                </div>
                <div class="form-group">
                    <div class="col-sm-4 clearfix"><a href="active_reactivation.php" class="btn config-btn pull-right">Back</a></div>
                    <div class="col-sm-8"><button type="submit" name="submit" value="Submit" class="btn config-btn btn-lg pull-right">Submit</button></div>
                </div>
            </div>
        </div>
    </div><!-- .panel -->

    <!-- Offer 3 -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_offer3" >
                    Offer 3 Email (1 Year)<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>
        <div id="collapse_offer3" class="panel-collapse collapse">
            <div class="panel-body"><?php
                $offer3_deactivated_contact_subject = get_config($dbc, 'offer3_deactivated_contact_subject');
                $offer3_deactivated_contact_body = html_entity_decode(get_config($dbc, 'offer3_deactivated_contact_body')); ?>

                <div class="form-group">
                    <label for="fax_number"	class="col-sm-4	control-label">Email Subject:</label>
                    <div class="col-sm-8"><input name="offer3_deactivated_contact_subject" type="text" value="<?= $offer3_deactivated_contact_subject; ?>" class="form-control" /></div>
                </div>
                <div class="form-group">
                    <label for="fax_number"	class="col-sm-4	control-label">Email Body:<br>Use Below tags<br>[Customer Name]<br></label>
                    <div class="col-sm-8"><textarea name="offer3_deactivated_contact_body" rows="5" cols="50" class="form-control"><?= $offer3_deactivated_contact_body; ?></textarea></div>
                </div>
                <div class="form-group">
                    <div class="col-sm-4 clearfix"><a href="active_reactivation.php" class="btn config-btn pull-right">Back</a></div>
                    <div class="col-sm-8"><button type="submit" name="submit" value="Submit" class="btn config-btn btn-lg pull-right">Submit</button></div>
                </div>
            </div>
        </div>
    </div><!-- .panel -->

</div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>