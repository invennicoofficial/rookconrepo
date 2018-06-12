<?php
/*
Client Listing
*/
include ('../include.php');
checkAuthorised('report');
error_reporting(0);
mysqli_query($dbc, "DELETE FROM `invoice_compensation` WHERE `invoiceid` NOT IN (SELECT `invoiceid` FROM `invoice`)");
?>
<script type="text/javascript">

</script>
</head>
<body>
<?php include_once ('../navigation.php');
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">

        <?php echo reports_tiles($dbc);  ?>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>