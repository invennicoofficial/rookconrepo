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
    $query_check_credentials = "SELECT contactid, first_name, last_name FROM contacts WHERE category='Patient' and deleted=0";
    $result = mysqli_query($dbc, $query_check_credentials);
    while($row = mysqli_fetch_array( $result ))
    {
        $contactid = $row['contactid'];

        //First Name
        $first_name = decryptIt($row['first_name']);
        $table_name = strtolower($first_name[0]);

        $result_insert_vendor = mysqli_query($dbc, "INSERT INTO `contacts_fn_".$table_name."` SELECT * from contacts WHERE contactid = '$contactid'");

        //Last Name
        $last_name = decryptIt($row['last_name']);
        $table_name = strtolower($last_name[0]);

        $result_insert_vendor = mysqli_query($dbc, "INSERT INTO `contacts_ln_".$table_name."` SELECT * from contacts WHERE contactid = '$contactid'");

    }

    echo 'Done';

    ?>

	</div>
</div>


<?php include ('../footer.php'); ?>