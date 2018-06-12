<?php /* See Patient's Feedback result for Survery we sent. */
$guest_access = true;
error_reporting(0);
include ('../include.php');
checkAuthorised('crm');

if (isset($_POST['survey'])) {
	$recommend_id = $_GET['s'];
	$recommend = $_POST['scale'];

    $query = "UPDATE `crm_recommend` SET `recommend_response`='$recommend', `completed_date`=CURRENT_TIMESTAMP WHERE `recommend_id` = '$recommend_id'";
    $result = mysqli_query($dbc, $query);

    echo '<script type="text/javascript"> window.location.replace("feedback_survey_thankyou.php"); </script>';
}

$company_name = get_config($dbc, 'company_name');
$recommend_request = $_GET['s'];
$request = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `crm_recommend` WHERE `recommend_id`='$recommend_request'"));
$today_date = date('Y-m-d');
if(!isset($_SESSION['user_name']) && $request['completed_date'] != null) {
	echo "<script> window.location.replace('feedback_survey_thankyou.php'); </script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
    <link href="<?php echo WEBSITE_URL;?>/img/favicon.ico" rel="shortcut icon">
    <link href="<?php echo WEBSITE_URL;?>/img/apple-touch-icon.png" rel="apple-touch-icon-precomposed">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,600' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="<?php echo WEBSITE_URL;?>/css/style.css" type="text/css">
    <script type="text/javascript">
    $(document).ready(function() {
		showValue();
    });

    function showValue() {
        $("#scale").html($('[name=scale]').val());
    }
    </script>
</head>
<body>
    <div class="login">
        <div class="middle">
            <form role="form" action="" method="post" class="registration_form survey_form triple-padded" style="width: 1080px; margin-top: 0px;">
                <div class="row">
                    <div class="col-lg-12 double-pad-bottom">
                        <img src="<?php echo WEBSITE_URL;?>/img/Clinic-Ace-Logo-Final-500px.png" alt="Clinic Ace" class="center-block" width="300">
                    </div>
                </div>
				<center><h3 class="double-pad-bottom">Feedback Form</h3></center>
                <div class="row triple-pad-top">
                    <ul class="list-inline text-center">
                        <li><a href="https://www.facebook.com/" class="social-icon facebook hide-text" target="_blank">Facebook</a></li>
                        <li><a href="https://www.linkedin.com/" class="social-icon linkedin hide-text" target="_blank">LinkedIn</a></li>
                        <li><a href="https://twitter.com/" class="social-icon twitter hide-text" target="_blank">Twitter</a></li>
                        <li><a href="https://plus.google.com/" class="social-icon google hide-text" target="_blank">Google+</a></li>
                    </ul>
                </div>
              <div class="form-group clearfix">
                <label for="first_name" class="col-sm-4 control-label text-right">Name:</label>
                <div class="col-sm-8">
                  <input name="name" readonly type="text" value="<?php echo get_contact($dbc, $request['contactid']);?>" class="form-control">
                </div>
              </div>

              <div class="form-group clearfix completion_date">
                <label for="first_name" class="col-sm-4 control-label text-right">Date :</label>
                <div class="col-sm-8">
                    <?php echo $today_date; ?>
                </div>
              </div>

              <div class="form-group clearfix">
                <label for="first_name" class="col-sm-4 control-label text-right">Provider :</label>
                <div class="col-sm-8">
                  <input name="provider" readonly type="text" value="<?php echo get_contact($dbc, $request['staffid']);?>" class="form-control">
                </div>
              </div>
			  
			<div class="form-group clearfix">
				<label for="first_name" class="col-sm-4 control-label text-right">How likely are you to recommend <?= (empty($company_name) ? 'Nose Creek' : $company_name) ?> to a friend?</label>
				<div class="col-sm-8">
					<div class="pull-left">0</div><div class="pull-right">10</div><div style="text-align:center; margin:0 auto; width: 2em;">5</div>
					<input type="range" list="volsettings" <?= ($request['completed_date'] == null ? '' : 'readonly') ?> min="0" max="10" value="<?= $request['recommend_response'] ?>" step="1" name="scale" onchange="showValue()"/>
					<span id="scale"></span>
				</div>
			</div>
			<datalist id="volsettings">
				<option>0</option>
				<option>1</option>
				<option>2</option>
				<option>3</option>
				<option>4</option>
				<option>5</option>
				<option>6</option>
				<option>7</option>
				<option>8</option>
				<option>9</option>
				<option>10</option>
			</datalist>

			<?php if($request['completed_date'] == null) { ?>
                <div class="form-group">
                        <button type="submit" name="survey" value="Submit" class="btn brand-btn pull-right">Submit</button>
                </div>
			<?php } ?>

            </form>
        </div>
    </div>
</body>
</html>