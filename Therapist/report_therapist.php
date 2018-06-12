<?php
/*
Client Listing
*/
include ('../include.php');
include_once('report_therapist_function.php');
error_reporting(0);
?>
<script type="text/javascript">

</script>
</head>
<body>
<?php include_once ('../navigation.php');
?>

<div class="container triple-pad-bottom">
    <div class="row">
        <div class="col-md-12">

        <?php echo reports_therapist($dbc);  ?>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>