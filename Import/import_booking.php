<?php
/*
Dashboard
*/
include ('../include.php');

if (isset($_POST['import_csv'])) {
    $csv_file = htmlspecialchars($_FILES['csv']['tmp_name'], ENT_QUOTES);
    if (($handle = fopen($csv_file, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);

            for ($c=0; $c < $num; $c++) {
              $col[$c] = $data[$c];
            }

            $first_name = encryptIt($col[1]);
            $last_name = encryptIt($col[0]);

            $result_patient = mysqli_query($dbc, "SELECT contactid FROM contacts WHERE first_name='$first_name' AND last_name='$last_name'");
            $num_rows_patient = mysqli_num_rows($result_patient);
            if($num_rows_patient > 0) {
                $patientid_get = mysqli_fetch_assoc($result_patient);
                $patientid = $patientid_get['contactid'];
            } else {
                $query_insert_patient = "INSERT INTO `contacts` (`category`, `first_name`, `last_name`) VALUES ('Patient', '$first_name', '$last_name')";
                $result_insert_patient = mysqli_query($dbc, $query_insert_patient);
                $patientid = mysqli_insert_id($dbc);
            }

            //$app_date = date_format(date_create_from_format('M d/y', $col[2]), 'Y-m-d');
            $app_date1 = $col[2];
            $app_date = date("Y-m-d", strtotime($app_date1));
            $app_time = $col[3];
            $appoint_date = $app_date.' '.$app_time;

            $duration = $col[4];

            $start_time = strtotime($col[3]);
            $end_time = date("H:i:s", strtotime('+'.$duration.' minutes', $start_time));
            $end_appoint_date = $app_date.' '.$end_time;

            $injury_type = $col[5];

            $result_injury = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT injuryid FROM patient_injury WHERE contactid='$patientid' AND injury_type='$injury_type'"));
            $injuryid = $result_injury['injuryid'];

            $therapistid = $col[6];
            $type = $col[8];

            $today_date_time = explode('- ', $col[7]);
            $today_date = $today_date_time[0];

            //
            $patient = $col[1].' '.$col[0];
            $injury_data = get_all_from_injury($dbc, $injuryid, 'injury_name').' : '.get_all_from_injury($dbc, $injuryid, 'injury_type'). ' - '.get_all_from_injury($dbc, $injuryid, 'injury_date');
            $patientstatus = 'Booked Unconfirmed';
            $start_time = strtotime($appoint_date);
            $end_time = strtotime($end_appoint_date);

            $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT first_name, last_name FROM contacts WHERE contactid='$therapistid'"));
            $therapist = $get_staff['first_name'].' '.$get_staff['last_name'];
            $get_roomid = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT id FROM mrbs_room WHERE room_name='$therapist'"));
            $room_id = $get_roomid['id'];

            //$timestamp = date('Y-m-d H:i:s');
            $timestamp = $col[7];

            $query_insert_cal = "INSERT INTO `mrbs_entry` (`patient`, `injury`, `patientstatus`, `start_time`, `end_time`, `room_id`, `timestamp`, `type`) VALUES ('$patient', '$injury_data', '$patientstatus', '$start_time', '$end_time', '$room_id', '$timestamp', '$type')";
            $result_insert_cal = mysqli_query($dbc, $query_insert_cal);
            $calid = mysqli_insert_id($dbc);

            $query_insert_booking = "INSERT INTO `booking` (`today_date`, `patientid`, `injuryid`, `therapistsid`, `appoint_date`, `end_appoint_date`, `type`, `calid`) VALUES ('$today_date', '$patientid', '$injuryid', '$therapistid', '$appoint_date', '$end_appoint_date', '$type', '$calid')";
            $result_insert_booking = mysqli_query($dbc, $query_insert_booking);

        }
        fclose($handle);
    }

    echo "File data successfully imported to database!!";
    //mysql_close($connect);
}

?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
	<div class="row">

		<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
          <div class="form-group">
            <label for="first_name" class="col-sm-4 control-label">Choose CSV file:</label>
            <div class="col-sm-8">
              <input name="csv" type="file" required />
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-4 clearfix">
                <a href="home.php" class="btn brand-btn pull-right">Back</a>
            </div>
            <div class="col-sm-8">
                <button type="submit" name="import_csv" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
            </div>
          </div>

        </form>

	</div>
</div>


<?php include ('../footer.php'); ?>