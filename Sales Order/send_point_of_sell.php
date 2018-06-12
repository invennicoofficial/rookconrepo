<?php
error_reporting(0);
/*
Payment/Invoice Listing SEA
*/
include ('../include.php');
include_once('../tcpdf/tcpdf.php');

if (isset($_POST['submit'])) {
    $posid = $_POST['posid'];
    $subject = $_POST['subject'];
    $email_body = $_POST['email_body'];

    $attachment = '';
    $attachment = 'download/invoice_'.$posid.'.pdf';
    $send_email = $_POST['email'];

    send_email('', $_POST['email'], '', '', $subject, $email_body, $attachment);

    echo '<script type="text/javascript"> window.location.replace('.urldecode($_GET['from']).'); </script>';
}
?>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('sales_order');
?>
<div class="container triple-pad-bottom">
    <div class="row">

		<h1>Send <?= SALES_ORDER_NOUN ?> to Client</h1>
        <form name="invoice_table" method="post" action="" class="form-horizontal" role="form">
			<input type="hidden" name="from" value="<?php echo $back_url; ?>">
			<a href="<?php echo $back_url; ?>" class="btn config-btn btn-lg">Back to Dashboard</a>
            <h3>Choose Name to send <?= SALES_ORDER_NOUN ?></h3>
            <?php
            $posid = $_GET['posid'];

            $contactid = get_pos($dbc, $posid, 'contactid');

            $name = get_client($dbc, $contactid);

            $resultt = mysqli_query($dbc, "SELECT contactid, first_name, last_name, email_address FROM contacts WHERE name='$name'");

            while($roww = mysqli_fetch_array( $resultt )) {
                if($roww['email_address'] != '') {
                    echo '<input type="checkbox" value="'.$roww['email_address'].'" style="height: 20px; width: 20px;" name="email[]">';
                } else {
                    echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                }
                echo '&nbsp;&nbsp;'.$roww['first_name'].' '.$roww['last_name'].' - '.$roww['email_address'];
                echo '<br>';
            }
            ?>
            <input type="hidden" name="posid" id="posid" value="<?php echo $posid; ?>">
            <br><br>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Email Subject:</label>
            <div class="col-sm-8">
              <input name="subject" value="<?= SALES_ORDER_NOUN ?> Attached" type="text" class="form-control">
            </div>
            </div>

            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Email Body:</label>
            <div class="col-sm-8">
              <textarea name="email_body" rows="5" cols="50" class="form-control"><?php echo 'Please find attachment for your '.SALES_ORDER_NOUN.'.';?></textarea>
            </div>
            </div>

            <a	href="<?php echo $back_url; ?>" class="btn brand-btn btn-lg	pull-left">Back</a>
            <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>

        </form>

	</div>
</div>
<?php include ('../footer.php'); ?>