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

            //$patient_name = explode(' ',$col[29]);

            //$patient_name = explode(' ',$col[29]);
            $status = $col[0];
            $first_name = encryptIt($col[2]);
            $last_name = encryptIt($col[1]);
            $email_address = encryptIt($col[3]);
            $gender = $col[4];
            $birth_date = $col[5];
            $business_street = encryptIt($col[6]);
            $business_city = encryptIt($col[7]);
            $business_state = encryptIt($col[8]);
            $business_zip = encryptIt($col[9]);
            $business_country = encryptIt($col[10]);
            $health_care_no = encryptIt($col[11]);
            $health_care_no_expiry = $col[12];

            $cell_phone = encryptIt($col[13]);
            $home_phone = encryptIt($col[14]);
            $office_phone = encryptIt($col[15]);

            $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT contactid FROM contacts WHERE first_name = '$first_name' AND last_name = '$last_name' AND health_care_no='$health_care_no'"));

            if($get_config['contactid'] == 0) {
                $query_insert_patient = "INSERT INTO `contacts` (`category`, `first_name`, `last_name`, `home_phone`, `cell_phone`, `office_phone`, `email_address`, `gender`, `birth_date`, `business_street`, `business_city`, `business_state`, `business_country`, `business_zip`, `health_care_no`, `health_care_no_expiry`, `status`) VALUES ('Patient', '$first_name', '$last_name', '$home_phone', '$cell_phone', '$office_phone', '$email_address', '$gender', '$birth_date', '$business_street', '$business_city', '$business_state', '$business_country', '$business_zip', '$health_care_no', '$health_care_no_expiry', '$status')";
                $result_insert_patient = mysqli_query($dbc, $query_insert_patient);
            }
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