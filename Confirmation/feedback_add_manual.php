<?php
/*
 * Customer feedback added manually by staff.
 */
include ('../include.php');
checkAuthorised('confirmation');
error_reporting(0);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bookingid = preg_replace('/[^0-9]/', '', $_POST['bookingid']);
    $feedback_method = filter_var($_POST['feedback_method'], FILTER_SANITIZE_STRING);
    $feedback = filter_var($_POST['feedback'], FILTER_SANITIZE_STRING);
    $feedback_notes = filter_var($_POST['feedback_notes'], FILTER_SANITIZE_STRING);
    $feedback_date = date('Y-m-d');
    
    if ( !empty($bookingid) && $bookingid!=0 ) {
        $result = mysqli_query($dbc, "INSERT INTO `followup_notifications` (`bookingid`, `feedback_method`, `feedback`, `feedback_notes`, `feedback_date`) VALUES ('$bookingid', '$feedback_method', '$feedback', '$feedback_notes', '$feedback_date')");
        $result = mysqli_query($dbc, "UPDATE `booking` SET `confirmation_email_date`='$feedback_date'");
    }
    
    echo '<script type="text/javascript">window.location.replace("'.WEBSITE_URL.'/Confirmation/feedback_add.php");</script>';
}
?>
</head>
<body>
<?php include_once ('../navigation.php'); ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="single-pad-bottom">Add Feedback</h1>
            <form name="feedback_form" method="post" action="" class="form-horizontal double-gap-top" role="form"><?php
                $bookingid = (isset($_GET['id'])) ? preg_replace('/[^0-9]/', '', $_GET['id']) : '0';
                $get_booking = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `patientid`, `appoint_date` FROM `booking` WHERE `bookingid`='$bookingid'")); ?>
                <input type="hidden" name="bookingid" value="<?= $bookingid; ?>" />
                <div class="form-group">
                    <label class="col-sm-2 control-label">Customer:</label>
                    <div class="col-sm-10"><?= get_contact($dbc, $get_booking['patientid']) ?></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Appointment Date:</label>
                    <div class="col-sm-10"><?= $get_booking['appoint_date'] ?></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Follow Up Method:</label>
                    <div class="col-sm-10">
                        <select name="feedback_method">
                            <option value="Spot Check">Spot Check</option>
                            <option value="Phone Call">Phone Call</option>
                            <option value="Text Message">Text Message</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Customer Feedback:</label>
                    <div class="col-sm-10">
                        <select name="feedback">
                            <option value="Happy">Happy</option>
                            <option value="Not Happy">Not Happy</option>
                        </select>
                    </div>
                </div>
                <div class="form-group gap-top">
                    <label class="col-sm-2 control-label">Notes:</label>
                    <div class="col-sm-10">
                        <textarea name="feedback_notes" class="form-control"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" name="submit" value="Submit" class="btn brand-btn pull-right gap-top" />
                </div>
            </form>
        </div>
    </div>
</div><!-- .container -->