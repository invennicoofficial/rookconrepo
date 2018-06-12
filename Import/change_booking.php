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
    $query_check_credentials = "SELECT appoint_date, bookingid FROM booking";
    $result = mysqli_query($dbc, $query_check_credentials);
    while($row = mysqli_fetch_array( $result ))
    {
        $bookingid = $row['bookingid'];
        $appoint_date = $row['appoint_date'];
        $ad = explode(' ', $row['appoint_date']);
        $a_date = $ad[0];
        $a_time = $ad[1];

        $len_time = strlen($a_time);
        if($len_time == 7) {
            $a_time = '0'.$a_time;

            $final_ad = $a_date.' '.$a_time;

            $query_update_booking = "UPDATE `booking` SET `appoint_date` = '$final_ad' WHERE `bookingid` = '$bookingid'";
            $result_update_booking = mysqli_query($dbc, $query_update_booking);
            echo $bookingid.' : '.$final_ad;
            echo '<br>';
        }
    }



    ?>

	</div>
</div>


<?php include ('../footer.php'); ?>