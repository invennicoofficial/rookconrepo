<?php
/*
Dashboard
*/
include ('../include.php');


$query_check_credentials = "SELECT * FROM invoice WHERE deleted = 0 AND insurance_payment IS NOT NULL AND insurance_payment != '#*#' AND insurance_payment != ''";
$result = mysqli_query($dbc, $query_check_credentials);

while($row = mysqli_fetch_array( $result ))
{
    $invoiceid = $row['invoiceid'];
    $invoice_date = $row['invoice_date'];
    $paid = $row['paid'];

    $insurance_payment = $row['insurance_payment'];
    $insurance_payment = rtrim($insurance_payment,',');
    if (strpos($insurance_payment, ',#*#') !== false) {
        $insurance_payment = str_replace(',#*#', '#*#', $insurance_payment);
    }
    $selectors = explode('#*#', $insurance_payment);
    $ins = $selectors[0];
    $ins_pay = $selectors[1];

    if (strpos($ins, ',') !== false) {
        $each_insid = explode(',', $ins);
        $each_ins_pay = explode(',', $ins_pay);
        $m = 0;
        foreach($each_insid as $pp) {
            if($pp != '') {
                $final_ins_pay = $each_ins_pay[$m];
                $query_insert_vendor = "INSERT INTO `invoice_insurer` (`invoiceid`, `invoice_date`, `insurerid`, `insurer_price`, `paid`) VALUES ('$invoiceid', '$invoice_date', '$pp', '$final_ins_pay', '$paid')";
                $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
                $m++;
            }
        }
    } else {
        $query_insert_vendor = "INSERT INTO `invoice_insurer` (`invoiceid`, `invoice_date`, `insurerid`, `insurer_price`, `paid`) VALUES ('$invoiceid', '$invoice_date', '$ins', '$ins_pay', '$paid')";
        $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
    }
}

    echo "File data successfully imported to database!!";
    //mysql_close($connect);

?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<?php include ('../footer.php'); ?>