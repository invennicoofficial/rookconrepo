<?php
/*
Add Vendor
*/
include ('../include.php');
checkAuthorised('interactive_calendar');
error_reporting(0);

if (isset($_POST['add_pay'])) {
    $activity_name = $_POST['activity_name'];
    $activity_date = $_POST['activity_date'];

    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }

	$activity_image = $_FILES['activity_image']['name'];
	$morning_image = $_FILES['morning_activity']['name'];
	$lunch_image = $_FILES['lunch_activity']['name'];
	$afternoon_image = $_FILES['afternoon_activity']['name'];
	$dinner_image = $_FILES['dinner_activity']['name'];
	$evening_image = $_FILES['evening_activity']['name'];


    if(empty($_POST['intercalendarid'])) {
	    move_uploaded_file($_FILES["activity_image"]["tmp_name"], "download/" . $_FILES["activity_image"]["name"]) ;
		move_uploaded_file($_FILES["morning_activity"]["tmp_name"], "images/" . $_FILES["morning_activity"]["name"]) ;
		move_uploaded_file($_FILES["lunch_activity"]["tmp_name"], "images/" . $_FILES["lunch_activity"]["name"]) ;
		move_uploaded_file($_FILES["afternoon_activity"]["tmp_name"], "images/" . $_FILES["afternoon_activity"]["name"]) ;
		move_uploaded_file($_FILES["dinner_activity"]["tmp_name"], "images/" . $_FILES["dinner_activity"]["name"]) ;
		move_uploaded_file($_FILES["evening_activity"]["tmp_name"], "images/" . $_FILES["evening_activity"]["name"]) ;

        $query_insert_vendor = "INSERT INTO `interactive_calendar` (`activity_name`, `activity_image`, `activity_date`,`morning_image`,`lunch_image`,`afternoon_image`,`dinner_image`,`evening_image`) VALUES ('$activity_name', '$activity_image', '$activity_date','$morning_image','$lunch_image','$afternoon_image','$dinner_image','$evening_image')";
        $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
        $url = 'Added';
    } else {
        $intercalendarid = $_POST['intercalendarid'];

		if($activity_image == '') {
			$activity_image_update = $_POST['activity_image_file'];
		} else {
			$activity_image_update = $activity_image;
		}
		if($morning_image == '') {
			$morning_image_update = $_POST['morning_activity_image'];
		} else {
			$morning_image_update = $morning_image;
		}
		if($lunch_image == '') {
			$lunch_image_update = $_POST['lunch_activity_image'];
		} else {
			$lunch_image_update = $lunch_image;
		}
		if($afternoon_image == '') {
			$afternoon_image_update = $_POST['afternoon_activity_image'];
		} else {
			$afternoon_image_update = $afternoon_image;
		}
		if($dinner_image == '') {
			$dinner_image_update = $_POST['dinner_activity_image'];
		} else {
			$dinner_image_update = $dinner_image;
		}
		if($evening_image == '') {
			$evening_image_update = $_POST['evening_activity_image'];
		} else {
			$evening_image_update = $evening_image;
		}
		move_uploaded_file($_FILES["activity_image"]["tmp_name"],"download/" . $activity_image_update);
		move_uploaded_file($_FILES["morning_activity"]["tmp_name"],"images/" . $morning_image_update);
		move_uploaded_file($_FILES["lunch_activity"]["tmp_name"],"images/" . $lunch_image_update);
		move_uploaded_file($_FILES["afternoon_activity"]["tmp_name"],"images/" . $afternoon_image_update);
		move_uploaded_file($_FILES["dinner_activity"]["tmp_name"],"images/" . $dinner_image_update);
		move_uploaded_file($_FILES["evening_activity"]["tmp_name"],"images/" . $evening_image_update);

        $query_update_vendor = "UPDATE `interactive_calendar` SET `activity_name` = '$activity_name', `activity_image` = '$activity_image_update', `activity_date` = '$activity_date', `morning_image` = '$morning_image_update', `lunch_image` = '$lunch_image_update', `afternoon_image` = '$afternoon_image_update', `dinner_image` = '$dinner_image_update', `evening_image` = '$evening_image_update' WHERE `intercalendarid` = '$intercalendarid'";
        $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
        $url = 'Updated';
    }
	
	$view = $_GET['view'];
    echo '<script type="text/javascript"> window.location.replace("interactive_calendar.php?view='.$view.'"); </script>';

 //   mysqli_close($dbc);//Close the DB Connection
}

?>
</head>

<body>
<?php include_once ('../navigation.php');
?>
<div class="container">
  <div class="row">

    <h1 class="triple-pad-bottom">Add Activity</h1>

    <form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
        <?php
        $activity_name = '';
        $activity_image = '';
        $activity_date = '';

		$morning_image = '';
		$lunch_image = '';
		$afternoon_image = '';
		$dinner_image = '';
		$evening_image = '';

        if(!empty($_GET['intercalendarid'])) {

            $intercalendarid = $_GET['intercalendarid'];
            $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM interactive_calendar WHERE intercalendarid='$intercalendarid'"));

            $activity_name = $get_contact['activity_name'];
            $activity_image = $get_contact['activity_image'];
            $activity_date = $get_contact['activity_date'];
			$morning_image = $get_contact['morning_image'];
			$lunch_image = $get_contact['lunch_image'];
			$afternoon_image = $get_contact['afternoon_image'];
			$dinner_image = $get_contact['dinner_image'];
			$evening_image = $get_contact['evening_image'];
        ?>
        <input type="hidden" id="intercalendarid" name="intercalendarid" value="<?php echo $intercalendarid ?>" />
        <?php   }      ?>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Name:</label>
            <div class="col-sm-8">
              <input name="activity_name" type="text" value="<?php echo $activity_name; ?>" class="form-control" />
            </div>
          </div>

            <!--<div class="form-group">
                <label class="col-sm-4 control-label">Activity Image:<br><em>[The recommended image size is 100 X 100 pixels. The maximum image size is 250 X 250 pixels.]</em></label>
                <div class="col-sm-8">
                    <?php if($activity_image != '') {
                    echo '<img src="download/'.$activity_image.'"><br><br>';
                    ?>
                    <input type="hidden" name="activity_image_file" value="<?php echo $activity_image; ?>" />
                    <input name="activity_image" accept="image/*" type="file" data-filename-placement="inside" class="form-control" />
                    <?php } else { ?>
                    <input name="activity_image" accept="image/*" type="file" data-filename-placement="inside" class="form-control" />
                    <?php } ?>
                </div>
            </div>-->

			<div class="form-group">
                <label class="col-sm-4 control-label">Morning Activity Image:<br><em>[The recommended image size is 100 X 100 pixels. The maximum image size is 250 X 250 pixels.]</em></label>
                <div class="col-sm-8">
                    <?php if($morning_image != '') {
                    echo '<img src="images/'.$morning_image.'"><br><br>';
                    ?>
                    <input type="hidden" name="morning_activity_image" value="<?php echo $morning_image; ?>" />
                    <input name="morning_activity" accept="image/*" type="file" data-filename-placement="inside" class="form-control" />
                    <?php } else { ?>
                    <input name="morning_activity" accept="image/*" type="file" data-filename-placement="inside" class="form-control" />
                    <?php } ?>
                </div>
            </div>

			<div class="form-group">
                <label class="col-sm-4 control-label">Lunch Activity Image:<br><em>[The recommended image size is 100 X 100 pixels. The maximum image size is 250 X 250 pixels.]</em></label>
                <div class="col-sm-8">
                    <?php if($lunch_image != '') {
                    echo '<img src="images/'.$lunch_image.'"><br><br>';
                    ?>
                    <input type="hidden" name="lunch_activity_image" value="<?php echo $lunch_image; ?>" />
                    <input name="lunch_activity" accept="image/*" type="file" data-filename-placement="inside" class="form-control" />
                    <?php } else { ?>
                    <input name="lunch_activity" accept="image/*" type="file" data-filename-placement="inside" class="form-control" />
                    <?php } ?>
                </div>
            </div>

			<div class="form-group">
                <label class="col-sm-4 control-label">Afternoon Activity Image:<br><em>[The recommended image size is 100 X 100 pixels. The maximum image size is 250 X 250 pixels.]</em></label>
                <div class="col-sm-8">
                    <?php if($afternoon_image != '') {
                    echo '<img src="images/'.$afternoon_image.'"><br><br>';
                    ?>
                    <input type="hidden" name="afternoon_activity_image" value="<?php echo $afternoon_image; ?>" />
                    <input name="afternoon_activity" accept="image/*" type="file" data-filename-placement="inside" class="form-control" />
                    <?php } else { ?>
                    <input name="afternoon_activity" accept="image/*" type="file" data-filename-placement="inside" class="form-control" />
                    <?php } ?>
                </div>
            </div>

			<div class="form-group">
                <label class="col-sm-4 control-label">Dinner Activity Image:<br><em>[The recommended image size is 100 X 100 pixels. The maximum image size is 250 X 250 pixels.]</em></label>
                <div class="col-sm-8">
                    <?php if($dinner_image != '') {
                    echo '<img src="images/'.$dinner_image.'"><br><br>';
                    ?>
                    <input type="hidden" name="dinner_activity_image" value="<?php echo $dinner_image; ?>" />
                    <input name="dinner_activity" accept="image/*" type="file" data-filename-placement="inside" class="form-control" />
                    <?php } else { ?>
                    <input name="dinner_activity" accept="image/*" type="file" data-filename-placement="inside" class="form-control" />
                    <?php } ?>
                </div>
            </div>

			<div class="form-group">
                <label class="col-sm-4 control-label">Evening Activity Image:<br><em>[The recommended image size is 100 X 100 pixels. The maximum image size is 250 X 250 pixels.]</em></label>
                <div class="col-sm-8">
                    <?php if($evening_image != '') {
                    echo '<img src="images/'.$evening_image.'"><br><br>';
                    ?>
                    <input type="hidden" name="evening_activity_image" value="<?php echo $evening_image; ?>" />
                    <input name="evening_activity" accept="image/*" type="file" data-filename-placement="inside" class="form-control" />
                    <?php } else { ?>
                    <input name="evening_activity" accept="image/*" type="file" data-filename-placement="inside" class="form-control" />
                    <?php } ?>
                </div>
            </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Date:</label>
            <div class="col-sm-8">
              <input name="activity_date" type="text" value="<?php echo $activity_date; ?>" class="datepicker" />
            </div>
          </div>


        <div class="form-group">
            <div class="col-sm-6">
                <span class="popover-examples"><a data-toggle="tooltip" data-placement="top" title="Clicking here will discard your changes."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<?php $view = $_GET['view']; ?>
				<a href="interactive_calendar.php?view=<?php echo $view; ?>" class="btn brand-btn btn-lg">Back</a>
				<!--<a href="#" class="btn brand-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
            </div>
			<div class="col-sm-6">
				<button type="submit" name="add_pay" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
				<span class="popover-examples pull-right" style="margin:15px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to submit your changes."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			</div>
        </div>

    </form>

  </div>
</div>
<?php include ('../footer.php'); ?>