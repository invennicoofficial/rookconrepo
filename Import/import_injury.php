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

            $injury_type = $col[0];
            $injury_therapistsid = $col[1];

            $patient = explode(', ', $col[2]);

            $f_file = encryptIt($patient[1]);
            $l_file = encryptIt($patient[0]);

            $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT contactid FROM contacts WHERE first_name='$f_file' AND last_name='$l_file'"));
            $contactid = $get_config['contactid'];

            $today_date = $col[3];
            $injury_date = $col[4];
            $injury_name = $col[5];

            $query_insert_injury = "INSERT INTO `patient_injury` (`contactid`, `injury_therapistsid`, `injury_name`, `injury_type`, `injury_date`, `today_date`) VALUES ('$contactid', '$injury_therapistsid', '$injury_name', '$injury_type', '$injury_date', '$today_date')";
            $result_insert_injury = mysqli_query($dbc, $query_insert_injury);

            $query_update_staff = "UPDATE `contacts` SET `status` = 'Active' WHERE `contactid` = '$contactid'";
            $result_update_staff = mysqli_query($dbc, $query_update_staff);
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