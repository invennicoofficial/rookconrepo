<?php
/*
Add Service Code
*/
include ('../include.php');
error_reporting(0);
checkAuthorised('booking');

if (isset($_POST['submit'])) {
    $today_date = date('Y-m-d');
    $patientid = $_POST['patientid'];
    $therapistsid = $_POST['therapistsid'];
    $comment = filter_var(htmlentities($_POST['comment']),FILTER_SANITIZE_STRING);

    $desired_date = $_POST['desired_date'];
    if(empty($_POST['waitlistid'])) {
        $query_insert_booking = "INSERT INTO `waitlist` (`today_date`, `patientid`, `therapistsid`, `desired_date`, `comment`) VALUES ('$today_date', '$patientid', '$therapistsid', '$desired_date', '$comment')";
        $result_insert_booking = mysqli_query($dbc, $query_insert_booking);
        $url = 'Added';
    } else {
        $waitlistid = $_POST['waitlistid'];
        $query_update_cal = "UPDATE `waitlist` SET `today_date` = '$today_date', `patientid` = '$patientid', `therapistsid` = '$therapistsid', `desired_date` = '$desired_date', `comment` = '$comment' WHERE `waitlistid` = '$waitlistid'";
        $result_update_cal = mysqli_query($dbc, $query_update_cal);
        $url = 'Updated';
    }
    echo '<script type="text/javascript"> window.location.replace("waitlist.php?contactid='.$therapistsid.'"); </script>';

    mysqli_close($dbc); //Close the DB Connection
}

?>
<script type="text/javascript">
$(document).ready(function() {
    $("#form1").submit(function( event ) {
        var patientid = $("#patientid").val();
        var desired_date = $("input[name=desired_date]").val();
        var therapistsid = $("#therapistsid").val();
        if (patientid == '' || therapistsid == '' || desired_date == '') {
            alert("Please make sure you have filled in all of the required fields.");
            return false;
        }
    });
});
</script>
</head>

<body>
<?php include_once ('../navigation.php');

?>
<div class="container">
  <div class="row">

        <h1 class="triple-pad-bottom">Waitlist</h1>

		<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

        <?php
		$patientid = '';
        $therapistsid = '';
        $desired_date = '';
        $comment = '';

        if(!empty($_GET['waitlistid'])) {
            $waitlistid = $_GET['waitlistid'];
            $get_site = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM waitlist WHERE waitlistid='$waitlistid'"));
            $patientid = $get_site['patientid'];
            $therapistsid = $get_site['therapistsid'];
            $desired_date = $get_site['desired_date'];
            $comment = $get_site['comment'];
        ?>
        <input type="hidden" id="waitlistid" name="waitlistid" value="<?php echo $waitlistid ?>" />
        <?php   }
        ?>

          <div class="form-group patient">
            <label for="site_name" class="col-sm-4 control-label">Patient<span class="hp-red">*</span>:</label>
            <div class="col-sm-8">
				<select id="patientid" data-placeholder="Choose a Patient..." name="patientid" class="chosen-select-deselect form-control" width="380">
					<option value=""></option>
					<?php
					$query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Patient' AND status=1 AND deleted=0");
					while($row = mysqli_fetch_array($query)) {
                        if (($patientid == $row['contactid']) || ($_GET['patientid'] == $row['contactid'])) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
						echo "<option ".$selected." value='". $row['contactid']."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';
					}
					?>
				</select>
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Therapist<span class="hp-red">*</span>:</label>
            <div class="col-sm-8">
				<select id="therapistsid" data-placeholder="Choose a Therapist..." name="therapistsid" class="chosen-select-deselect form-control" width="380">
					<option value=""></option>
					<?php
						$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE (category_contact = 'Physical Therapist' OR category_contact = 'Massage Therapist' OR category_contact = 'Osteopathic Therapist') AND deleted=0 AND `status`=1"),MYSQLI_ASSOC));
						foreach($query as $id) {
							$selected = '';
							$selected = $id == $therapistsid ? 'selected = "selected"' : '';
							echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
						}
					?>
				</select>
            </div>
          </div>

          <div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Comment:</label>
            <div class="col-sm-8">
              <textarea name="comment" rows="5" cols="50" class="form-control"><?php echo $comment; ?></textarea>
            </div>
          </div>

          <div class="form-group clearfix">
            <label for="first_name" class="col-sm-4 control-label text-right">Desired Date<span class="empire-red">*</span>:</label>
            <div class="col-sm-8">
                <input name="desired_date" placeholder="Click for Datepicker" value="<?php echo $desired_date; ?>" type="text" class="datefuturepicker"></p>
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
                <a href="waitlist.php?contactid=<?php echo $therapistsid; ?>" class="btn brand-btn pull-right">Back</a>
            </div>
            <div class="col-sm-8">
                <button type="submit" name="submit" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
            </div>
          </div>

        

        </form>
    </div>
  </div>

<?php include ('../footer.php'); ?>