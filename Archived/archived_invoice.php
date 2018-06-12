<?php
/*
Customer Listing
*/
include ('../include.php');
checkAuthorised('archiveddata');
?>
</head>
<body>
<?php include_once ('../navigation.php');
?>

<div class="container triple-pad-bottom">
    <div class="row">
		<div class="col-md-12">

        <h1 class="single-pad-bottom">Invoices</h1>

        <!--<a href='archived_invoice.php'><button type="button" class="btn brand-btn mobile-block active_tab" >Invoices</button></a>-->

        <form name="form_sites" method="post" action="" class="form-inline" role="form">
             <div id="no-more-tables">

            <?php

            $query_check_credentialss = "SELECT * FROM point_of_sell WHERE (deleted = 0 AND status = 'Completed') OR (deleted = 1) ORDER BY posid DESC";

           $resultt = mysqli_query($dbc, $query_check_credentialss);

            $num_rowss = mysqli_num_rows($resultt);
            if($num_rowss > 0) {
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pos_dashboard FROM field_config"));
                $value_config = ','.$get_field_config['pos_dashboard'].',';

                echo "<table class='table table-bordered'>";
                echo "<tr class='hidden-xs hidden-sm'>";
                    if (strpos($value_config, ','."Invoice #".',') !== FALSE) {
                        echo '<th>Invoice #</th>';
                    }
                    if (strpos($value_config, ','."Invoice Date".',') !== FALSE) {
                        echo '<th>Invoice Date</th>';
                    }
                    if (strpos($value_config, ','."Customer".',') !== FALSE) {
                        echo '<th>Customer</th>';
                    }
                    if (strpos($value_config, ','."Total Price".',') !== FALSE) {
                        echo '<th>Total Price</th>';
                    }
                    echo '<th>Due Date</th>';
                    if (strpos($value_config, ','."Invoice PDF".',') !== FALSE) {
                        echo '<th>Invoice PDF</th>';
                    }
                    if (strpos($value_config, ','."Comment".',') !== FALSE) {
                        echo '<th>Comment</th>';
                    }
                    if (strpos($value_config, ','."Status".',') !== FALSE) {
                        echo '<th>Status</th>';
                    }
                echo "</tr>";
            } else{
                echo "<h2>No Record Found.</h2>";
            }

            while($roww = mysqli_fetch_array( $resultt ))
            {
                $style = '';
                if($roww['status'] == 'Posted Past Due') {
                    $style = 'style = color:red;';
                }

                $contactid = $roww['contactid'];
                echo "<tr ".$style.">";
                $customer = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT name, first_name, last_name FROM contacts WHERE contactid='$contactid'"));

                if (strpos($value_config, ','."Invoice #".',') !== FALSE) {
                    echo '<td data-title="Invoice #">' . $roww['posid'] . '</td>';
                }
                if (strpos($value_config, ','."Invoice Date".',') !== FALSE) {
                    echo '<td data-title="Invoice Date">' . $roww['invoice_date'] . '</td>';
                }
                if (strpos($value_config, ','."Customer".',') !== FALSE) {
                    echo '<td data-title="Customer">' . $customer['name'] . '</td>';
                }
                if (strpos($value_config, ','."Total Price".',') !== FALSE) {
                    echo '<td data-title="Total Price">' . $roww['total_price'] . '</td>';
                }
                echo '<td data-title="Due Date">' . date('Y-m-d', strtotime($roww['invoice_date'] . "+30 days")) . '</td>';
                if (strpos($value_config, ','."Invoice PDF".',') !== FALSE) {
                    echo '<td data-title="Invoice PDF"><a target="_blank" href="'.WEBSITE_URL.'/Point of Sale/download/invoice_'.$roww['posid'].'.pdf">PDF <img src="'.WEBSITE_URL.'/img/pdf.png" title="PDF"></a></td>';
                }
                if (strpos($value_config, ','."Comment".',') !== FALSE) {
                    echo '<td data-title="Comment">' . $roww['comment'] . '</td>';
                }
                if (strpos($value_config, ','."Status".',') !== FALSE) {
                    echo '<td data-title="Status">' . $roww['status'] . '</td>';
                    }
                echo "</tr>";
            }

            echo '</table>';

            ?>
        </form>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>