<?php
/*
Dashboard
*/
include ('../include.php');

if (isset($_POST['import_csv'])) {
    $csv_file = htmlspecialchars($_FILES['csv']['tmp_name'], ENT_QUOTES);
    if (($handle = fopen($csv_file, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1024, ",")) !== FALSE) {
            $num = count($data);

            $posid = $data[0];
            $contactid = $data[1];
            $inventoryid = $data[2];
            $quantity = $data[3];
            $price = $data[4];
            $sub_total = $data[5];
            $discount_type = $data[6];
            $discount_value = $data[7];
            $total_after_discount = $data[8];
            $total_before_tax = $data[9];
            $gst_total = $data[10];
            $total_price = $data[11];
            $payment_type = $data[12];
            $invoice_date = $data[13];

            $each_invid = explode(',', $inventoryid);
            $each_quantity = explode(',', $quantity);
            $each_price = explode(',', $price);

            $query_insert_invoice = "INSERT INTO `point_of_sell` (`posid`, `invoice_date`, `contactid`, `sub_total`, `discount_type`, `discount_value`, `total_after_discount`, `total_before_tax`, `total_price`, `payment_type`, `gst`) VALUES ('$posid', '$invoice_date', '$contactid', '$sub_total', '$discount_type', '$discount_value', '$total_after_discount', '$total_before_tax', '$total_price', '$payment_type', '$gst_total')";
            $results_are_in = mysqli_query($dbc, $query_insert_invoice);

            $i = 0;
            foreach ($each_invid as $invid) {
                $final_invid = $invid;
                $final_qty = $each_quantity[$i];
                $final_price = $each_price[$i];
                if($final_invid != '') {
                    $query_insert_invoice = "INSERT INTO `point_of_sell_product` (`posid`, `inventoryid`, `quantity`, `price`) VALUES ('$posid', '$final_invid', '$final_qty', '$final_price')";
                    $results_are_in = mysqli_query($dbc, $query_insert_invoice);
                }
                $i++;
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