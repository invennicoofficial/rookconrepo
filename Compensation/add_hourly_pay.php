<?php
/*
Add Vendor
*/
include ('../include.php');
checkAuthorised('goals_compensation');
error_reporting(0);

if (isset($_POST['add_pay'])) {
    $contactid = $_POST['contactid'];
    $hourly_pay = $_POST['hourly_pay'];

    $today_date = date('Y-m-d');

    if(empty($_POST['hourlypayid'])) {
        $query_insert_vendor = "INSERT INTO `hourly_pay` (`contactid`, `hourly_pay`, `today_date`) VALUES ('$contactid', '$hourly_pay', '$today_date')";
        $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
        $url = 'Added';
    } else {
        $hourlypayid = $_POST['hourlypayid'];
        $query_update_vendor = "UPDATE `hourly_pay` SET `contactid` = '$contactid', `hourly_pay` = '$hourly_pay', `today_date` = '$today_date' WHERE `hourlypayid` = '$hourlypayid'";
        $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
        $url = 'Updated';
    }

    echo '<script type="text/javascript"> window.location.replace("hourly_pay.php"); </script>';

 //   mysqli_close($dbc);//Close the DB Connection
}

?>
</head>

<body>
<?php include_once ('../navigation.php');
?>
<div class="container">
  <div class="row">

    <h1 class="triple-pad-bottom">Hourly Pay</h1>

    <form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
        <?php
        $contactid = '';
        $hourly_pay = '';

        if(!empty($_GET['hourlypayid'])) {

            $hourlypayid = $_GET['hourlypayid'];
            $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hourly_pay WHERE hourlypayid='$hourlypayid'"));

            $contactid = $get_contact['contactid'];
            $hourly_pay = $get_contact['hourly_pay'];
        ?>
        <input type="hidden" id="hourlypayid" name="hourlypayid" value="<?php echo $hourlypayid ?>" />
        <?php   }      ?>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Staff:</label>
            <div class="col-sm-8">
				<select data-placeholder="Select Staff..." name="contactid" id="contactid" class="chosen-select-deselect form-control" width="380">
					<option></option>
					<?php $staff_groups = explode(',',get_config($dbc, 'comp_staff_groups'));
					$query = sort_contacts_query(mysqli_query($dbc,"SELECT contactid, first_name, last_name, category_contact FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 AND `status`>0"));
					foreach($query as $staff) {
						if(in_array('ALL',$staff_groups) || in_array($staff['category_contact'],$staff_groups)) {
							echo "<option " . ($staff['contactid'] == $contactid ? 'selected' : '') . "value='". $staff['contactid']."'>".$staff['first_name'].' '.$staff['last_name'].'</option>';
						}
					} ?>
				</select>
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Hourly Pay:</label>
            <div class="col-sm-8">
              <input name="hourly_pay" type="text" value="<?php echo $hourly_pay; ?>" class="form-control" />
            </div>
          </div>

        <div class="form-group">
            <div class="col-sm-4 clearfix">
                <a href="hourly_pay.php" class="btn brand-btn pull-right">Back</a>
            </div>
          <div class="col-sm-8">
            <button type="submit" name="add_pay" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
		  </div>
        </div>

        

    </form>

  </div>
</div>
<?php include ('../footer.php'); ?>