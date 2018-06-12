<?php
/*
Add Vendor
*/
include ('../include.php');
error_reporting(0);

if(isset($_GET['action']) && $_GET['action'] == 'delete') {
    $fundersid = $_GET['fundersid'];
    $query = mysqli_query($dbc,"DELETE FROM fund_development_funder WHERE fundersid='$fundersid'");

    echo '<script type="text/javascript"> window.location.replace("funders.php"); </script>';
}

if (isset($_POST['add_funders'])) {

    $first_name = filter_var(htmlentities($_POST['first_name']),FILTER_SANITIZE_STRING);
    $last_name = filter_var(htmlentities($_POST['last_name']),FILTER_SANITIZE_STRING);
    $client_id = filter_var(htmlentities($_POST['client_id']),FILTER_SANITIZE_STRING);
    $aish = filter_var(htmlentities($_POST['aish']),FILTER_SANITIZE_STRING);
    $work_phone = filter_var(htmlentities($_POST['work_phone']),FILTER_SANITIZE_STRING);
    $cell_phone = filter_var(htmlentities($_POST['cell_phone']),FILTER_SANITIZE_STRING);
    $fax = filter_var(htmlentities($_POST['fax']),FILTER_SANITIZE_STRING);
    $email_address = filter_var(htmlentities($_POST['email_address']),FILTER_SANITIZE_STRING);
    $address = filter_var(htmlentities($_POST['address']),FILTER_SANITIZE_STRING);
    $postal_zip_code = filter_var(htmlentities($_POST['postal_zip_code']),FILTER_SANITIZE_STRING);
    $city_town = filter_var(htmlentities($_POST['city_town']),FILTER_SANITIZE_STRING);
    $province_state = filter_var(htmlentities($_POST['province_state']),FILTER_SANITIZE_STRING);
    $country = filter_var(htmlentities($_POST['country']),FILTER_SANITIZE_STRING);

    $uploads = htmlspecialchars($_FILES["uploads"]["name"], ENT_QUOTES);

    move_uploaded_file($_FILES["uploads"]["tmp_name"], "download/".$_FILES["uploads"]["name"]) ;

    if(empty($_GET['fundersid'])) {

        $query_insert = "INSERT INTO `fund_development_funder` (`first_name`, `last_name`, `client_id`, `aish`, `work_phone`, `cell_phone`, `fax`, `email_address`, `address`, `postal_zip_code`, `city_town`, `province_state`, `country`, `uploads`) VALUES ('$first_name', '$last_name', '$client_id', '$aish', '$work_phone', '$cell_phone', '$fax', '$email_address', '$address', '$postal_zip_code', '$city_town', '$province_state', '$country', '$uploads')";
        $result_insert_vendor = mysqli_query($dbc, $query_insert);
        $fundersid = mysqli_insert_id($dbc);
        echo '<script type="text/javascript"> window.location.replace("funders.php"); </script>';
    } else {
        $fundersid = $_GET['fundersid'];
        $query_update = "UPDATE `fund_development_funder` SET `first_name` = '$first_name', `last_name` = '$last_name', `client_id` = '$client_id', `aish` = '$aish', `work_phone` = '$work_phone', `cell_phone` = '$cell_phone', `fax` = '$fax', `email_address` = '$email_address', `address` = '$address', `postal_zip_code` = '$postal_zip_code', `city_town` = '$city_town', `province_state` = '$province_state', `country` = '$country', `uploads` = '$uploads' WHERE `fundersid` = '$fundersid'";
        $result_update = mysqli_query($dbc, $query_update);
        echo '<script type="text/javascript"> window.location.replace("funders.php"); </script>';
    }



}

?>
</head>

<body>
<?php include_once ('../navigation.php');
checkAuthorised('fund_development');
?>
<div class="container">
  <div class="row">

    <h1>Add Funder</h1>
	<div class="gap-top double-gap-bottom"><a href="funders.php" class="btn config-btn">Back to Dashboard</a></div>

    <form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

    <?php
        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fund_development_funders FROM field_config"));
        $value_config = ','.$get_field_config['fund_development_funders'].',';

        $first_name = '';
        $last_name = '';
        $client_id = '';
        $aish = '';
        $work_phone = '';
        $cell_phone = '';
        $fax = '';
        $email_address = '';
        $address = '';
        $postal_zip_code = '';
        $city_town = '';
        $province_state = '';
        $country = '';
        $uploads = '';

        if(!empty($_GET['fundersid'])) {

            $fundersid = $_GET['fundersid'];

            $get_contact = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM fund_development_funder WHERE fundersid='$fundersid'"));

            $first_name = $get_contact['first_name'];
            $last_name = $get_contact['last_name'];
            $client_id = $get_contact['client_id'];
            $aish = $get_contact['aish'];
            $work_phone = $get_contact['work_phone'];
            $cell_phone = $get_contact['cell_phone'];
            $fax = $get_contact['fax'];
            $email_address = $get_contact['email_address'];
            $address = $get_contact['address'];
            $postal_zip_code = $get_contact['postal_zip_code'];
            $city_town  = $get_contact['city_town'];
            $province_state = $get_contact['province_state'];
            $country = $get_contact['country'];
            $uploads = $get_contact['uploads'];

        ?>
        <input type="hidden" id="fundersid" name="fundersid" value="<?php echo $fundersid ?>" />
        <?php   }      ?>

    <div class="panel-group" id="accordion2">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse1" >
                        Funder Information<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse1" class="panel-collapse collapse">
                <div class="panel-body">

                    <?php if (strpos($value_config, ','."First Name".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="first_name" class="col-sm-4 control-label">First Name:</label>
                        <div class="col-sm-8">
                            <input name="first_name" type="text" value="<?php echo $first_name; ?>" class="form-control" />
                        </div>
                    </div>
                    <?php } ?>

                    <?php if (strpos($value_config, ','."Last Name".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="last_name" class="col-sm-4 control-label">Last Name:</label>
                        <div class="col-sm-8">
                            <input name="last_name" type="text" value="<?php echo $last_name; ?>" class="form-control" />
                        </div>
                    </div>
                    <?php } ?>

                    <?php if (strpos($value_config, ','."Client ID #".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="client_id" class="col-sm-4 control-label">Client ID #:</label>
                        <div class="col-sm-8">
                            <input name="client_id" type="text" value="<?php echo $client_id; ?>" class="form-control" />
                        </div>
                    </div>
                    <?php } ?>

                    <?php if (strpos($value_config, ','."AISH #".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="aish" class="col-sm-4 control-label">AISH #:</label>
                        <div class="col-sm-8">
                            <input name="aish" type="text" value="<?php echo $aish; ?>" class="form-control" />
                        </div>
                    </div>
                    <?php } ?>

                    <?php if (strpos($value_config, ','."Work Phone".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="work_phone" class="col-sm-4 control-label">Work Phone:</label>
                        <div class="col-sm-8">
                            <input name="work_phone" type="text" value="<?php echo $work_phone; ?>" class="form-control" />
                        </div>
                    </div>
                    <?php } ?>

                    <?php if (strpos($value_config, ','."Cell Phone".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="cell_phone" class="col-sm-4 control-label">Cell Phone:</label>
                        <div class="col-sm-8">
                            <input name="cell_phone" type="text" value="<?php echo $cell_phone; ?>" class="form-control" />
                        </div>
                    </div>
                    <?php } ?>

                    <?php if (strpos($value_config, ','."Fax #".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="fax" class="col-sm-4 control-label">Fax #:</label>
                        <div class="col-sm-8">
                            <input name="fax" type="text" value="<?php echo $fax; ?>" class="form-control" />
                        </div>
                    </div>
                    <?php } ?>

                    <?php if (strpos($value_config, ','."Email Address".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="email_address" class="col-sm-4 control-label">Email Address:</label>
                        <div class="col-sm-8">
                            <input name="email_address" type="text" value="<?php echo $email_address; ?>" class="form-control" />
                        </div>
                    </div>
                    <?php } ?>

                    <?php if (strpos($value_config, ','."Address".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="address" class="col-sm-4 control-label">Address:</label>
                        <div class="col-sm-8">
                            <input name="address" type="text" value="<?php echo $address; ?>" class="form-control" />
                        </div>
                    </div>
                    <?php } ?>

                    <?php if (strpos($value_config, ','."Postal/Zip Code".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="postal_zip_code" class="col-sm-4 control-label">Postal/Zip Code:</label>
                        <div class="col-sm-8">
                            <input name="postal_zip_code" type="text" value="<?php echo $postal_zip_code; ?>" class="form-control" />
                        </div>
                    </div>
                    <?php } ?>

                    <?php if (strpos($value_config, ','."City/Town".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="city_town" class="col-sm-4 control-label">City/Town:</label>
                        <div class="col-sm-8">
                            <input name="city_town" type="text" value="<?php echo $city_town; ?>" class="form-control" />
                        </div>
                    </div>
                    <?php } ?>

                    <?php if (strpos($value_config, ','."Province/State".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="province_state" class="col-sm-4 control-label">Province/State:</label>
                        <div class="col-sm-8">
                            <input name="province_state" type="text" value="<?php echo $province_state; ?>" class="form-control" />
                        </div>
                    </div>
                    <?php } ?>

                    <?php if (strpos($value_config, ','."Country".',') !== FALSE) { ?>
                    <div class="form-group">
                        <label for="country" class="col-sm-4 control-label">Country:</label>
                        <div class="col-sm-8">
                            <input name="country" type="text" value="<?php echo $country; ?>" class="form-control" />
                        </div>
                    </div>
                    <?php } ?>

                  <?php if (strpos($value_config, ','."Support Documents".',') !== FALSE) { ?>
                    <div class="form-group">
                    <label for="file" class="col-sm-4 control-label">Support Documents:
                    <span class="popover-examples list-inline">&nbsp;
                    <a  data-toggle="tooltip" data-placement="top" title="File name cannot contain commas or apostrophes"><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a>
                    </span>
                    </label>
                    <div class="col-sm-8">
                    <?php if($uploads != '') {
                    echo '<a href="download/'.$uploads.'" target="_blank">View</a>' ?>
                    <input type="hidden" name="uploads_hidden" value="<?php echo $uploads; ?>" />
                    <input name="uploads" type="file" data-filename-placement="inside" class="form-control" />
                    <?php } else { ?>
                    <input name="uploads" type="file" data-filename-placement="inside" class="form-control" />
                          <?php } ?>
                      </div>
                 </div>
                  <?php } ?>

                </div>
            </div>
        </div>


    </div>

        <div class="form-group">
			<p><span class="hp-red"><em>Required Fields *</em></span></p>
        </div>

        <div class="form-group">
            <div class="col-sm-6"><a href="funders.php" class="btn brand-btn btn-lg">Back</a></div>
			<div class="col-sm-6"><button type="submit" name="add_funders" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button></div>
			<div class="clearfix"></div>
        </div>


    </form>

  </div>
</div>
<?php include ('../footer.php'); ?>
