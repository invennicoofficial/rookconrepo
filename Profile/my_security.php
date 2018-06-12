<?php
/*
Customer Listing
*/
include ('../include.php');
error_reporting(0);

if (isset($_POST['profile_info'])) {
    $eme_name = filter_var($_POST['eme_name'],FILTER_SANITIZE_STRING);
    $eme_phone = filter_var($_POST['eme_phone'],FILTER_SANITIZE_STRING);
    $eme_email = filter_var($_POST['eme_email'],FILTER_SANITIZE_STRING);
    $eme_relation = filter_var($_POST['eme_relation'],FILTER_SANITIZE_STRING);
    $contactid = $_POST['contactid'];
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    if($eme_name != '') {
        $query_insert_staff = "INSERT INTO `my_emergency_contact` (`contactid`, `eme_name`, `eme_phone`, `eme_email`, `eme_relation`) VALUES ('$contactid', '$eme_name', '$eme_phone', '$eme_email', '$eme_relation')";
        $result_insert_staff = mysqli_query($dbc, $query_insert_staff);
    }

    $query_insert_staff = "UPDATE `contacts` SET `user_name` = '$user_name', `password` = '$password' WHERE `contactid` = '$contactid'";
    $result_insert_staff = mysqli_query($dbc, $query_insert_staff);

    echo '<script type="text/javascript"> window.location.replace("my_security.php?contactid='.$contactid.'"); </script>';
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
<a href='my_driving_info.php?contactid=<?php echo $_SESSION['contactid']; ?>'><button type="button" class="btn brand-btn mobile-100 mobile-block" >My Driving Info</button></a>
<button type="button" class="btn brand-btn mobile-100 mobile-block active_tab" >My Security</button>
<a href="<?php echo WEBSITE_URL;?>/home.php" class="btn brand-btn mobile-100 pull-right">Back</a>
<br><br>

<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<?php
if(!empty($_GET['contactid'])) {

    $contactid = $_GET['contactid'];
    $get_data = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM contacts WHERE contactid='$contactid'"));

    $user_name = $get_data['user_name'];
    $password = $get_data['password'];
?>
<input type="hidden" id="contactid" name="contactid" value="<?php echo $contactid ?>" />
<?php   } ?>

<div class="panel-group" id="accordion2">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_eme" >My Emergency Contacts<span class="glyphicon glyphicon-minus"></span></a>
            </h4>
        </div>

        <div id="collapse_eme" class="panel-collapse collapse in">
            <div class="panel-body">

                <?php
                    $result = mysqli_query($dbc, "SELECT * FROM my_emergency_contact WHERE contactid='$contactid'");
                    $num_rows = mysqli_num_rows($result);
                    if($num_rows > 0) {
                        echo "<table class='table table-bordered'>";
                        echo "<tr class='hidden-xs hidden-sm'>";
                                echo '<th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Relationship</th>
                                ';
                        echo "</tr>";
                    }
                    while($row = mysqli_fetch_array( $result ))
                    {
                        echo "<tr>";
                        echo '<td data-title="Service Type">' . $row['eme_name'] . '</td>';
                        echo '<td data-title="Service Type">' . $row['eme_phone'] . '</td>';
                        echo '<td data-title="Service Type">' . $row['eme_email'] . '</td>';
                        echo '<td data-title="Service Type">' . $row['eme_relation'] . '</td>';
                        echo "</tr>";
                    }

                    echo '</table>';
                ?>

              <div class="form-group">
                <label for="site_name" class="col-sm-4 control-label">Name:</label>
                <div class="col-sm-8">
                  <input name="eme_name" type="text" class="form-control" />
                </div>
              </div>

              <div class="form-group">
                <label for="site_name" class="col-sm-4 control-label">Phone:</label>
                <div class="col-sm-8">
                  <input name="eme_phone" type="text" class="form-control" />
                </div>
              </div>

              <div class="form-group">
                <label for="site_name" class="col-sm-4 control-label">Email:</label>
                <div class="col-sm-8">
                  <input name="eme_email" type="text" class="form-control" />
                </div>
              </div>

              <div class="form-group">
                <label for="site_name" class="col-sm-4 control-label">Relationship:</label>
                <div class="col-sm-8">
                  <input name="eme_relation" type="text" class="form-control" />
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

    <!--
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_pass" >Profile Password Reset<span class="glyphicon glyphicon-plus"></span></a>
            </h4>
        </div>

        <div id="collapse_pass" class="panel-collapse collapse">
            <div class="panel-body">

              <div class="form-group">
                <div class="col-sm-4 clearfix">
                    <a href="<?php echo WEBSITE_URL;?>/home.php" class="btn brand-btn mobile-100 pull-right">Back</a>
                </div>
                <div class="col-sm-8">
                    <button type="submit" name="profile_info" value="Submit" class="btn brand-btn mobile-100 pull-right">Submit</button>
                </div>
              </div>

            </div>
        </div>
    </div>
    -->

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_access" >My Profile Access<span class="glyphicon glyphicon-plus"></span></a>
            </h4>
        </div>

        <div id="collapse_access" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                    <label for="user_name" class="col-sm-4 control-label">Username:
                        <span class="popover-examples list-inline">&nbsp;
                        <a href="#job_file" data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas"><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
                        </span>
                    </label>
                    <div class="col-sm-8">
                        <input name="user_name" type="text" maxlength="50" value="<?php echo $user_name; ?>" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-sm-4 control-label">Password:
                        <span class="popover-examples list-inline">&nbsp;
                        <a href="#job_file" data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas"><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
                        </span>
                    </label>
                    <div class="col-sm-8">
                        <input name="password" type="password" maxlength="20" value="<?php echo $password; ?>" class="form-control">
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