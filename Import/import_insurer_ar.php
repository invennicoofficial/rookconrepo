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

            $insurerid = $col[0];
            $insurer = $col[1];
            $amount_to_bill = $col[2];
            $amount_owing = $col[3];
            $amount_credit = $col[4];

            if($insurerid != '') {
                $query_update_vendor = "UPDATE `contacts` SET `amount_to_bill` = '$amount_to_bill', `amount_owing` = '$amount_owing', `amount_credit` = '$amount_credit' WHERE `contactid` = '$insurerid'";
                $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
            } else {
                $query_insert_patient = "INSERT INTO `contacts` (`category`, `name`, `amount_to_bill`, `amount_owing`, `amount_credit`) VALUES ('Insurer', '$insurer', '$amount_to_bill', '$amount_owing', '$amount_credit')";
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