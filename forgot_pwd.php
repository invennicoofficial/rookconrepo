<?php /* Forgot Password */
error_reporting(0);
$guest_access = true;
include_once('database_connection.php');
include_once('global.php');
include_once('function.php');
include_once('email.php');

if (isset($_POST['formsubmitted'])) {
	$msg = '';
    $email_address = encryptIt($_POST['email_address']);

    $query_verify_email = "SELECT `contactid`, `user_name`, `first_name`, `last_name`, `email_address`, `password` FROM `contacts` WHERE (`email_address` ='$email_address' OR `office_email` = '$email_address') AND IFNULL(`user_name`,'') != ''";
    $result_user = mysqli_query($dbc, $query_verify_email);
    if (!$result_user) {
		// If the Query Failed
        $msg = '<div class="alert alert-danger">Unable to connect to Database. Please try again later.<!--'.mysqli_error($dbc).'--></div>';
    } else if($result_user->num_rows > 0) {
		while($user = $result_user->fetch_assoc()) {
			$email = get_email($dbc, $user['contactid']);
			$username = $user['user_name'];
			$password = decryptIt($user['password']);
			$fullname = decryptIt($user['first_name']).' '.decryptIt($user['last_name']);
			
			$subject = "Forgotten Password at ".WEBSITE_URL;
			
			$message = "<p>Hello $fullname</p>
			<p>You can log in to <a href='".WEBSITE_URL."/index.php'>".WEBSITE_URL."</a> with the following credentials:<br />
			Username: ".$username."<br />
			Password: ".$password."</p>
			<p>You are receiving this message because the forgotten password request page for ".WEBSITE_URL." was completed with this email address. If you did not request your password, please disregard this message.</p>";
			
			try {
				send_email('', $email, '', '', $subject, $message, '');
				$msg .= '<div class="alert alert-success">A password reminder email has been sent to '.$email.'.<br />If you do not see the email, check your spam folders.</div>';
			} catch(Exception $e) {
				$msg .= '<div class="alert alert-danger">Unable to send the requested email: '.$e->getMessage().'. Please try again later, or contact support.</div>';
			}
		}
		$msg .= '<center><a href="index.php">Click here to log in</a></center>';
	} else {
		$msg = '<div class="alert alert-danger">The email address you entered could not be found in the database. Please try again.</div>';
	}

} // End of the main Submit conditional.

?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Forgotten Password</title>
    <link href="img/favicon.ico" rel="shortcut icon">
    <link href="img/apple-touch-icon.png" rel="apple-touch-icon-precomposed">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,600' rel='stylesheet' type='text/css'>
	<?php include ('login_page_style.php'); ?>
</head>
<body>
    <div class="login">
        <div class="middle">


        <form role="form" action="" method="POST" class="col-sm-6 col-sm-offset-3 form-horizontal">
            
			<?php
                    echo '<input type="hidden" name="location" value="';
                    if(isset($_GET['location'])) {
                        echo htmlspecialchars($_GET['location']);
                    }
                    echo '" />';
                ?>
                <div class="row">
                    <div class="col-lg-12 double-pad-bottom">
                        <?php
                        $logo_upload = get_config($dbc, 'logo_upload');
			            if($logo_upload == '') {
                            echo '<img src="img/logo.png" alt="Fresh Focus Media" class="center-block" width="300">';
                        } else {
                            echo '<img src="Settings/download/'.$logo_upload.'" alt="Fresh Focus Media" class="center-block" width="300">';
                        }
                        ?>
                    </div>
                </div>
				
				<?php if ($msg) { ?>
                    <div class="form-group double-gap-top">
                        <?= $msg ?>
                    </div>
				<?php } ?>
			<center><h3>Forgot Password?</h3>
			<br>
            <div class="form-group">
                <label for="email" class="col-sm-3 control-label">Email:</label>
                <div class="col-sm-6">
                    <input type="text" id="email_address" name="email_address" size="25" required class="form-control">
                </div>
            </div>
			</center>
            <div class="form-group">
                <input type="hidden" name="formsubmitted" value="TRUE"/>
                <div class="col-sm-3">&nbsp;</div>
                <div class="col-sm-6">
                    <button type="submit" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
                    <a href="index.php" class="btn brand-btn btn-lg pull-left">Cancel</a>
                </div>
            </div>
			
        </form>
    </div>
</div>

</body>
</html>