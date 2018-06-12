<?php
error_reporting(0);
$guest_access = true;
include ('../include.php');
checkAuthorised('crm');

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
	<link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>

    <div class="login">
        <div class="middle">
                <div class="row">
                    <div class="col-lg-12 double-pad-bottom">
                        <img src="<?php echo WEBSITE_URL;?>/img/Clinic-Ace-Logo-Final-500px.png" alt="Clinic Ace" class="center-block" width="300">
                    </div>
                </div>
                <div class="row triple-pad-top">
                    <ul class="list-inline text-center">
                        <li><a href="https://www.facebook.com/" class="social-icon facebook hide-text" target="_blank">Facebook</a></li>
                        <li><a href="https://www.linkedin.com/" class="social-icon linkedin hide-text" target="_blank">LinkedIn</a></li>
                        <li><a href="https://twitter.com/" class="social-icon twitter hide-text" target="_blank">Twitter</a></li>
                        <li><a href="https://plus.google.com/" class="social-icon google hide-text" target="_blank">Google+</a></li>
                    </ul>
                </div>

                <center><h3 class="double-pad-bottom">Thank you for completing the feedback</h3></center>

        </div>
    </div>
</body>
</html>