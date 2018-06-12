<?php
/*
Dashboard
*/
include ('database_connection.php');
include ('global.php');
include ('header.php');
include ('pagination.php');
?>
</head>
<body>

<?php include ('navigation.php'); ?>

<div class="container">
	<div class="row">

		<h1 class="double-pad-bottom"><?php echo date('Y-m-d'); ?>  Appointment</h1>

        <div class="table-responsive">

        <form name="form_patients" method="post" action="dashboard.php" class="form-inline" role="form">

            <?php

            $query_check_credentials = "SELECT eve.*, cus.value, phy.name  FROM spc_calendar_events eve, spc_calendar_event_custom_field_vals cus, spc_calendar_calendars phy WHERE eve.id=cus.event_id AND eve.cal_id=phy.id AND CURDATE() between eve.start_date and eve.repeat_end_date AND repeat_type IN('none','daily')";

            $result = mysqli_query($dbc, $query_check_credentials);

            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {
                echo "<table border='2' cellpadding='10' class='table'>";
                echo "<tr>
				<th>Physiotherapist</th>
                <th>Patient</th>
				<th>Subject</th>
                <th>Appointment Time</th>
				<th>Action</th>
				<th>Complete</th>
                </tr>";
            } else {
            	echo "<h2>No Record Found.</h2>";
            }

            while($row = mysqli_fetch_array( $result ))
            {
            	echo "<tr>";
                echo '<td>' . $row['name']. '</td>';
				echo '<td>' . $row['value'] . '</td>';

				echo '<td>' . $row['title'] . '</td>';
				echo '<td>' . $row['start_time'].'-'.$row['end_time'] . '</td>';

	            echo '<td>
				<a href=\'event_complete.php?action=No-Show&event_id='.$row['eve.id'].'\' >No-Show</a> |
				<a href=\'event_complete.php?action=Cancel&event_id='.$row['eve.id'].'\' >Cancel</a>
				</td>';

				echo '<td>
				<a href=\'event_complete.php?action=Complete&event_id='.$row['eve.id'].'\' >Complete</a></td>';

            	echo "</tr>";
            }

            echo '</table></div>
            ';

            ?>
        </form>

        </div>


	</div>
</div>

<?php include ('footer.php'); ?>