<?php
/*
Add Vendor
*/
include ('../include.php');
checkAuthorised('goals_compensation');
error_reporting(0);

if (isset($_POST['add_pay'])) {
    $therapistid = $_POST['therapistid'];
    $ava_hours_stat = filter_var($_POST['ava_hours_stat'],FILTER_SANITIZE_STRING);
    $ava_hours_stat_bi = filter_var($_POST['ava_hours_stat_bi'],FILTER_SANITIZE_STRING);
    $ava_hours_stat_weekly = filter_var($_POST['ava_hours_stat_weekly'],FILTER_SANITIZE_STRING);
    $ava_hours_stat_daily = filter_var($_POST['ava_hours_stat_daily'],FILTER_SANITIZE_STRING);

    $today_date = date('Y-m-d');

    if(empty($_POST['goalid'])) {
        $query_insert_vendor = "INSERT INTO `goal` (`therapistid`, `ava_hours_stat`, `today_date`, `ava_hours_stat_bi`, `ava_hours_stat_weekly`, `ava_hours_stat_daily`) VALUES ('$therapistid', '$ava_hours_stat', '$today_date', '$ava_hours_stat_bi', '$ava_hours_stat_weekly', '$ava_hours_stat_daily')";
        $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
        $url = 'Added';
    } else {
        $goalid = $_POST['goalid'];
        $query_update_vendor = "UPDATE `goal` SET `therapistid` = '$therapistid', `ava_hours_stat` = '$ava_hours_stat', `today_date` = '$today_date', `ava_hours_stat_bi` = '$ava_hours_stat_bi', `ava_hours_stat_weekly` = '$ava_hours_stat_weekly', `ava_hours_stat_daily` = '$ava_hours_stat_daily' WHERE `goalid` = '$goalid'";
        $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
        $url = 'Updated';
    }

    echo '<script type="text/javascript"> window.location.replace("stat_report_setup.php"); </script>';

 //   mysqli_close($dbc);//Close the DB Connection
}

?>
<script type="text/javascript">
$(document).ready(function() {
} );

</script>
</head>

<body>
<?php include_once ('../navigation.php');
?>
<div class="container">
  <div class="row">

    <h1 class="triple-pad-bottom">Set Stat Report</h1>

    <form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
        <?php
        $therapistid = '';
        $ava_hours_stat = '';
        $ava_hours_stat_bi = '';
        $ava_hours_stat_weekly = '';
        $ava_hours_stat_daily = '';

        if(!empty($_GET['goalid'])) {

            $goalid = $_GET['goalid'];
            $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM goal WHERE goalid='$goalid'"));

            $therapistid = $get_contact['therapistid'];
            $ava_hours_stat = $get_contact['ava_hours_stat'];
            $ava_hours_stat_bi = $get_contact['ava_hours_stat_bi'];
            $ava_hours_stat_weekly = $get_contact['ava_hours_stat_weekly'];
            $ava_hours_stat_daily = $get_contact['ava_hours_stat_daily'];

        ?>
        <input type="hidden" id="goalid" name="goalid" value="<?php echo $goalid ?>" />
        <?php   }      ?>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Staff:</label>
            <div class="col-sm-8">
				<select data-placeholder="Select Staff..." name="therapistid" class="chosen-select-deselect form-control" width="380">
					<option></option>
					<?php $staff_groups = explode(',',get_config($dbc, 'comp_staff_groups'));
					$query = sort_contacts_query(mysqli_query($dbc,"SELECT contactid, first_name, last_name, category_contact FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 AND `status`>0"));
					foreach($query as $staff) {
						if(in_array('ALL',$staff_groups) || in_array($staff['category_contact'],$staff_groups)) {
							echo "<option " . ($staff['contactid'] == $therapistid ? 'selected' : '') . "value='". $staff['contactid']."'>".$staff['first_name'].' '.$staff['last_name'].'</option>';
						}
					} ?>
				</select>
            </div>
          </div>

           <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">% of Available Hours Scheduled in Stat(Monthly):<br><em>[(% Arrivals/This field)*100]<br>[If search field date difference is more than 28]</em></label>
            <div class="col-sm-8">
                <input name="ava_hours_stat" type="text" value="<?php echo $ava_hours_stat; ?>" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">% of Available Hours Scheduled in Stat(Bi-weekly):<br><em>[(% Arrivals/This field)*100]<br>[If search field date difference is between 13 and 27]</em></label>
            <div class="col-sm-8">
                <input name="ava_hours_stat_bi" type="text" value="<?php echo $ava_hours_stat_bi; ?>" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">% of Available Hours Scheduled in Stat(Weekly):<br><em>[(% Arrivals/This field)*100]<br>[If search field date difference is between 6 and 12]</em></label>
            <div class="col-sm-8">
                <input name="ava_hours_stat_weekly" type="text" value="<?php echo $ava_hours_stat_weekly; ?>" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">% of Available Hours Scheduled in Stat(Daily):<br><em>[(% Arrivals/This field)*100]<br>[If search field date difference is less than 6]</em></label>
            <div class="col-sm-8">
                <input name="ava_hours_stat_daily" type="text" value="<?php echo $ava_hours_stat_daily; ?>" class="form-control" />
            </div>
          </div>

        <div class="form-group">
            <div class="col-sm-4 clearfix">
                <a href="stat_report_setup.php" class="btn brand-btn pull-right">Back</a>
            </div>
          <div class="col-sm-8">
            <button type="submit" name="add_pay" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
		  </div>
        </div>

        

    </form>

  </div>
</div>
<?php include ('../footer.php'); ?>