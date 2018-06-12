<?php
/*
Configuration - Choose which functionality you want for your software. Config email subject and body part for each functionality. Config Email Send Before days/month for patient treatment/booking confirmation and reminder.
*/
include ('../include.php');
checkAuthorised('field_ticket_estimates');
error_reporting(0);

if (isset($_POST['submit'])) {
    $estimateid = $_POST['estimateid'];

	if (!file_exists('download')) {
		mkdir('download', 0777, true);
	}
    $front_company_logo = htmlspecialchars($_FILES["front_company_logo"]["name"], ENT_QUOTES);
    $front_client_logo = htmlspecialchars($_FILES["front_client_logo"]["name"], ENT_QUOTES);
    $front_client_info = filter_var(htmlentities($_POST['front_client_info']),FILTER_SANITIZE_STRING);
    $front_other_info = filter_var(htmlentities($_POST['front_other_info']),FILTER_SANITIZE_STRING);
    $front_content_pages = filter_var(htmlentities($_POST['front_content_pages']),FILTER_SANITIZE_STRING);
    $last_content_pages = filter_var(htmlentities($_POST['last_content_pages']),FILTER_SANITIZE_STRING);

    if($front_company_logo == '') {
        $front_company_logo_update = $_POST['front_company_logo_file'];
    } else {
        $front_company_logo_update = $front_company_logo;
    }
    move_uploaded_file($_FILES["front_company_logo"]["tmp_name"],"download/" . $front_company_logo_update);
    if($front_client_logo == '') {
        $front_client_logo_update = $_POST['quote_pdf_footer_logo_file'];
    } else {
        $front_client_logo_update = $front_client_logo;
    }
    move_uploaded_file($_FILES["front_client_logo"]["tmp_name"],"download/" . $front_client_logo_update);
    $query_update_employee = "UPDATE `bid` SET front_company_logo = '$front_company_logo_update', front_client_info = '$front_client_info', front_other_info = '$front_other_info', front_client_logo = '$front_client_logo_update', front_content_pages = '$front_content_pages', last_content_pages = '$last_content_pages' WHERE `estimateid` = '$estimateid'";
    $result_update_employee = mysqli_query($dbc, $query_update_employee);

    echo '<script type="text/javascript"> window.location.replace("quote_front_page.php?estimateid='.$estimateid.'"); </script>';
}
?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<!--<a href="estimate.php" class="btn config-btn">Back</a>-->
<a href="#" class="btn config-btn" onclick="history.go(-1);return false;">Back</a>

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
    <div class="panel-group" id="accordion2">

    <?php
    $estimateid = $_GET['estimateid'];
    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM bid WHERE estimateid='$estimateid'"));
    $front_company_logo = $get_field_config['front_company_logo'];
    $front_client_info = $get_field_config['front_client_info'];
    $front_other_info = $get_field_config['front_other_info'];
    $front_client_logo = $get_field_config['front_client_logo'];
    $front_content_pages = $get_field_config['front_content_pages'];
    $last_content_pages = $get_field_config['last_content_pages'];
    ?>
	<input type="hidden" name="estimateid" value="<?php echo $estimateid; ?>" />

    <div class="form-group">
    <label for="file[]" class="col-sm-4 control-label">Company Logo
    <span class="popover-examples list-inline">&nbsp;
    <a  data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas"><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a>
    </span>
    :</label>
    <div class="col-sm-8">
    <?php if($front_company_logo != '') {
        echo '<a href="download/'.$front_company_logo.'" target="_blank">View</a>';
        ?>
        <input type="hidden" name="front_company_logo_file" value="<?php echo $front_company_logo; ?>" />
        <input name="front_company_logo" type="file" data-filename-placement="inside" class="form-control" />
      <?php } else { ?>
      <input name="front_company_logo" type="file" data-filename-placement="inside" class="form-control" />
      <?php } ?>
    </div>
    </div>

    <div class="form-group">
    <label for="file[]" class="col-sm-4 control-label">Client Logo
    <span class="popover-examples list-inline">&nbsp;
    <a  data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas"><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a>
    </span>
    :</label>
    <div class="col-sm-8">
    <?php if($front_client_logo != '') {
        echo '<a href="download/'.$front_client_logo.'" target="_blank">View</a>';
        ?>
        <input type="hidden" name="front_client_logo_file" value="<?php echo $front_client_logo; ?>" />
        <input name="front_client_logo" type="file" data-filename-placement="inside" class="form-control" />
      <?php } else { ?>
      <input name="front_client_logo" type="file" data-filename-placement="inside" class="form-control" />
      <?php } ?>
    </div>
    </div>

    <div class="form-group">
        <label for="office_country" class="col-sm-4 control-label">Client Info<br><em>(Ex: Name, Address, Phone, Email etc.)</em></label>
        <div class="col-sm-8">
            <textarea name="front_client_info" rows="3" cols="50" class="form-control"><?php echo $front_client_info; ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="office_country" class="col-sm-4 control-label">Other Info:<br><em>(Ex: Prepared For, Prepared By, )</em></label>
        <div class="col-sm-8">
            <textarea name="front_other_info" rows="3" cols="50" class="form-control"><?php echo $front_other_info; ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="office_country" class="col-sm-4 control-label">Front Content Pages:</label>
        <div class="col-sm-8">
            <textarea name="front_content_pages" rows="3" cols="50" class="form-control"><?php echo $front_content_pages; ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="office_country" class="col-sm-4 control-label">Last Pages:</label>
        <div class="col-sm-8">
            <textarea name="last_content_pages" rows="3" cols="50" class="form-control"><?php echo $last_content_pages; ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-4 clearfix">
            <!--<a href="estimate.php" class="btn config-btn pull-right">Back</a>-->
			<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>
        </div>
        <div class="col-sm-8">
            <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
        </div>
    </div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>