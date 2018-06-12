<?php
/*
Customer Listing
*/
include ('../include.php');
error_reporting(0);

if (isset($_POST['profile_info'])) {
    $state_issued = filter_var($_POST['state_issued'],FILTER_SANITIZE_STRING);
    $license_number = filter_var($_POST['license_number'],FILTER_SANITIZE_STRING);

    $class = filter_var($_POST['class'],FILTER_SANITIZE_STRING);
    $issued = filter_var($_POST['issued'],FILTER_SANITIZE_STRING);
    $expires = filter_var($_POST['expires'],FILTER_SANITIZE_STRING);
    $email_alert = filter_var($_POST['email_alert'],FILTER_SANITIZE_STRING);

    $driver_license = htmlspecialchars($_FILES["driver_license"]["name"], ENT_QUOTES);

	if (!file_exists('download')) {
		mkdir('download', 0777, true);
	}

    $contactid = $_POST['contactid'];

    $query_count   = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(drivinginfoid) AS numrows FROM my_driving_info WHERE `contactid` = '$contactid'"));

    if($query_count['numrows'] == 0) {
		move_uploaded_file($_FILES["driver_license"]["tmp_name"], "download/" . $driver_license) ;
        $query_insert_staff = "INSERT INTO `my_driving_info` (`contactid`, `state_issued`, `license_number`, `class`, `issued`, `expires`, `email_alert`, `driver_license`) VALUES ('$contactid', '$state_issued', '$license_number', '$class', '$issued', '$expires', '$email_alert', '$driver_license')";
        $result_insert_staff = mysqli_query($dbc, $query_insert_staff);
    } else {

        if($driver_license == '') {
            $driver_license_update = $_POST['driver_license_file'];
        } else {
            $driver_license_update = $driver_license;
        }
        move_uploaded_file($_FILES["driver_license"]["tmp_name"], "download/" .    $driver_license_update);

        $query_update_staff = "UPDATE `my_driving_info` SET `state_issued` = '$state_issued',  `license_number` = '$license_number', `class` = '$class', `issued` = '$issued', `expires` = '$expires', `email_alert` = '$email_alert', `driver_license` = '$driver_license_update' WHERE `contactid` = '$contactid'";
        $result_update_staff = mysqli_query($dbc, $query_update_staff);
        $url_msg = 'Updated';

    }

    echo '<script type="text/javascript">window.location.replace("my_driving_info.php?contactid='.$contactid.'"); </script>';
}
?>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised();
?>

<div class="container triple-pad-bottom">
<div class="row">
<div class="col-md-12">

<a href='my_profile.php?contactid=<?php echo $_SESSION['contactid']; ?>'><button type="button" class="btn brand-btn mobile-100 mobile-block" >My Profile</button></a>
<a href='my_certificate.php?contactid=<?php echo $_SESSION['contactid']; ?>'><button type="button" class="btn brand-btn mobile-100 mobile-block" >My Certificates</button></a>
<button type="button" class="btn brand-btn mobile-100 mobile-block active_tab" >My Driving Info</button>
<a href='my_security.php?contactid=<?php echo $_SESSION['contactid']; ?>'><button type="button" class="btn brand-btn mobile-100 mobile-block" >My Security</button></a>
<a href="<?php echo WEBSITE_URL;?>/home.php" class="btn brand-btn pull-right">Back</a>
<br><br>

<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<?php
$state_issued = '';
$license_number = '';
$class = '';
$issued = '';
$expires = '';
$email_alert = '';
$driver_license = '';

if(!empty($_GET['contactid'])) {

    $contactid = $_GET['contactid'];
    $get_data = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM my_driving_info WHERE contactid='$contactid'"));

    $state_issued = $get_data['state_issued'];
    $license_number = $get_data['license_number'];
    $class = $get_data['class'];
    $issued = $get_data['issued'];
    $expires = $get_data['expires'];
    $email_alert = $get_data['email_alert'];
    $driver_license = $get_data['driver_license'];
?>
<input type="hidden" id="contactid" name="contactid" value="<?php echo $contactid ?>" />
<?php   } ?>

<div class="panel-group" id="accordion2">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_emp_info" >My Drivers License<span class="glyphicon glyphicon-minus"></span></a>
            </h4>
        </div>

        <div id="collapse_emp_info" class="panel-collapse collapse in">
            <div class="panel-body">

              <div class="form-group">
                <label for="site_name" class="col-sm-4 control-label">State/Province Issued:</label>
                <div class="col-sm-8">
                  <input name="state_issued" type="text"  value="<?php echo $state_issued; ?>" class="form-control" />
                </div>
              </div>

              <div class="form-group">
                <label for="site_name" class="col-sm-4 control-label">Drivers License Number:</label>
                <div class="col-sm-8">
                  <input name="license_number" type="text"  value="<?php echo $license_number; ?>" class="form-control" />
                </div>
              </div>

              <div class="form-group">
                <label for="site_name" class="col-sm-4 control-label">Class of License:</label>
                <div class="col-sm-8">
                  <input name="class" type="text"  value="<?php echo $class; ?>" class="form-control" />
                </div>
              </div>

                <div class="form-group clearfix completion_date">
                    <label for="first_name" class="col-sm-4 control-label text-right">Issued:</label>
                    <div class="col-sm-8">
                        <input name="issued" value="<?php echo $issued; ?>" type="text" class="datepicker"></p>
                    </div>
                </div>

                <div class="form-group clearfix completion_date">
                    <label for="first_name" class="col-sm-4 control-label text-right">Expires:</label>
                    <div class="col-sm-8">
                        <input name="expires" value="<?php echo $expires; ?>" type="text" class="datepicker"></p>
                    </div>
                </div>

                <div class="form-group clearfix completion_date">
                    <label for="first_name" class="col-sm-4 control-label text-right">Set Email Alert:</label>
                    <div class="col-sm-8">
                        <input name="email_alert" value="<?php echo $email_alert; ?>" type="text" class="datepicker"></p>
                    </div>
                </div>

              <div class="form-group">
                <label for="file" class="col-sm-4 control-label">My Drivers License:
                <span class="popover-examples list-inline">&nbsp;
                <a href="#job_file" data-toggle="tooltip" data-placement="top" title="File name cannot contain commas or apostrophes"><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
                </span>
                </label>
                <div class="col-sm-8">
                <?php if((!empty($_GET['contactid'])) && ($driver_license != '') ) {
                    echo '<img src="download/'.$driver_license.'" height="100" border="0" alt="">';
                    ?>
                    <input type="hidden" name="driver_license_file" value="<?php echo $driver_license; ?>" />
                <?php } ?>

                  <input name="driver_license" type="file" id="file" data-filename-placement="inside" class="form-control" />
                </div>
              </div>

              <div class="form-group">
                <div class="col-sm-4 clearfix">
                    <a href="<?php echo WEBSITE_URL;?>/home.php" class="btn brand-btn btn-lg pull-right">Back</a>
                    <button type="submit" name="profile_info" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
                </div>
              </div>

            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_abi" >My Driving Abilities<span class="glyphicon glyphicon-plus"></span></a>
            </h4>
        </div>

        <div id="collapse_abi" class="panel-collapse collapse">
            <div class="panel-body">

              <div class="form-group">
                <div class="col-sm-4 clearfix">
                    <a href="<?php echo WEBSITE_URL;?>/home.php" class="btn brand-btn btn-lg pull-right">Back</a>
                    <button type="submit" name="profile_info" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
                </div>
              </div>

            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_auth" >Driver Authorization Form<span class="glyphicon glyphicon-plus"></span></a>
            </h4>
        </div>

        <div id="collapse_auth" class="panel-collapse collapse">
            <div class="panel-body">

              <div class="form-group">
                <div class="col-sm-4 clearfix">
                    <a href="<?php echo WEBSITE_URL;?>/home.php" class="btn brand-btn btn-lg pull-right">Back</a>
                    <button type="submit" name="profile_info" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
                </div>
              </div>

            </div>
        </div>
    </div>

</div>

<div class="form-group">
    <div class="col-sm-4">
        <p><span class="empire-red pull-right"><em>Required Fields *</em></span></p>
    </div>
    <div class="col-sm-8"></div>
</div>

<div class="form-group">
    <div class="col-sm-4 clearfix">
        <a href="<?php echo WEBSITE_URL;?>/home.php" class="btn brand-btn btn-lg pull-right">Back</a>
        <button type="submit" name="profile_info" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
    </div>
</div>

</form>

</div>
</div>
</div>
<?php include ('../footer.php'); ?>