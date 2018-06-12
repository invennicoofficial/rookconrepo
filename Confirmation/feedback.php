<?php
/*
 * Customer feedback.
 */
$guest_access = true;
include ('../include.php');
checkAuthorised('confirmation');
error_reporting(0);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bookingid = preg_replace('/[^0-9]/', '', $_POST['bookingid']);
    $feedback = filter_var($_POST['feedback'], FILTER_SANITIZE_STRING);
    $feedback_notes = filter_var($_POST['feedback_notes'], FILTER_SANITIZE_STRING);
    $feedback_date = date('Y-m-d');
    
    if ( !empty($bookingid) && $bookingid!=0 ) {
        $result = mysqli_query($dbc, "INSERT INTO `followup_notifications` (`bookingid`, `feedback_method`, `feedback`, `feedback_notes`, `feedback_date`) VALUES ('$bookingid', 'Email', '$feedback', '$feedback_notes', '$feedback_date')");
        $result = mysqli_query($dbc, "UPDATE `booking` SET `confirmation_email_date`='$feedback_date'");
        echo '<script type="text/javascript">window.location.replace("'.WEBSITE_URL.'/Confirmation/thankyou.php");</script>';
    } else {
        echo '<script type="text/javascript">window.location.replace("'.WEBSITE_URL.'");</script>';
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-xs-12 col-md-8">
            <h1 class="single-pad-bottom">Feedback Form</h1>
            <div class="pad-left">Your feedback helps us improve our services.</div>
            <form name="feedback_form" method="post" action="" class="form-horizontal double-gap-top" role="form"><?php
                $bookingid = (isset($_GET['id'])) ? preg_replace('/[^0-9]/', '', $_GET['id']) : '0'; ?>
                <input type="hidden" name="bookingid" value="<?= $bookingid; ?>" />
                <div class="form-group">
                    <label class="col-sm-2 control-label">I am:</label>
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