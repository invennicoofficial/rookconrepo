<?php
/*
Dashboard
*/
include ('../include.php');

?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
	<div class="row">

    <?php
    $query_check_credentials = "SELECT contactid, first_name, last_name FROM contacts WHERE category='Staff' and deleted=0";
    $result = mysqli_query($dbc, $query_check_credentials);
    while($row = mysqli_fetch_array( $result ))
    {
        $contactid = $row['contactid'];

        //First Name
        $first_name = decryptIt($row['first_name']);
        $first_letter = strtolower($first_name[0]);

        $result_insert_vendor = mysqli_query($dbc, "UPDATE `contacts` SET `first_letter` = '$first_letter' WHERE `contactid` = '$contactid'");
    }

    echo 'Done';

    ?>

	</div>
</div>


<?php include ('../footer.php'); ?>