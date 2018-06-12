<?php
error_reporting(0);
$guest_access = true;
include ('../include.php');
checkAuthorised('confirmation');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Thank you!</title>
    <link href="<?= WEBSITE_URL;?>/img/favicon.ico" rel="shortcut icon">
    <link href="<?= WEBSITE_URL;?>/img/apple-touch-icon.png" rel="apple-touch-icon-precomposed">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,600' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>

    <div class="login">
        <div class="middle">
            <div class="row">
                <div class="col-lg-12 double-pad-bottom"><?php
                    $logo_upload = get_config($dbc, 'logo_upload');
                    if ( $logo_upload=='' ) {
                        echo '<img src="'.WEBSITE_URL.'/img/logo.png" alt="Logo" class="center-block" style="max-height:100px;" />';
                    } else {
                        echo '<img src="'.WEBSITE_URL.'/Settings/download/'.$logo_upload.'" alt="Logo" class="center-block" style="max-height:100px;" />';
                    } ?>
                </div>
            </div>
            <center><h3 class="double-pad-bottom">Thank you! Your submission was received.</h3></center>
        </div>
    </div>
</body>
</html>