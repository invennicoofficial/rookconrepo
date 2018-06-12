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
    $FileName = "duplicate_contact_tc.csv";
    $file = fopen($FileName,"w");

    $HeadingsArray=array();
    $HeadingsArray[]= 'cid';
    $HeadingsArray[]='First Name';
    $HeadingsArray[]='Last Name';
    $HeadingsArray[]='Appointment';
    fputcsv($file,$HeadingsArray);

    $query_check_credentials = "SELECT contactid, contacts.first_name, contacts.last_name
FROM contacts
   INNER JOIN (SELECT first_name, last_name
               FROM    contacts
               GROUP  BY first_name, last_name
               HAVING COUNT(contactid) > 1) dup
           ON contacts.first_name = dup.first_name AND contacts.last_name = dup.last_name ORDER BY dup.first_name, dup.last_name";
    $result = mysqli_query($dbc, $query_check_credentials);
    while($row = mysqli_fetch_array( $result ))
    {
        $contactid = $row['contactid'];

        $get_field_config = mysqli_query($dbc,"SELECT bookingid, appoint_date FROM booking WHERE patientid='$contactid'");

        $appoint_date = '';
        while($row1 = mysqli_fetch_array( $get_field_config )) {
            $appoint_date .= $row1['appoint_date'].' == ';
        }

        //First Name
        $first_name = decryptIt($row['first_name']);
        $last_name = decryptIt($row['last_name']);

        $valuesArray=array();

        $valuesArray[] =   $contactid;
        $valuesArray[] =   $first_name;
        $valuesArray[] =   $last_name;
        $valuesArray[] =   $appoint_date;
        fputcsv($file,$valuesArray);
    }
    fclose($file);
    header("Location: $FileName");

    echo 'Done';

    ?>

	</div>
</div>


<?php include ('../footer.php'); ?>