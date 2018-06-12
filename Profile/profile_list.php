<?php // Staff Profile page
include_once ('../include.php');
?>
</head>
<body>

<?php include_once ('navigation.php');

?>

<div class="container">
	<div class="row">

        <h1 class="double-pad-bottom">Profile</h1>

        <form name="form_sites" method="post" action="" class="form-inline" role="form">
	        <div id="no-more-tables">

            <?php
			$contactid = $_GET['contactid'];
            $query_check_credentials = "SELECT * FROM contacts WHERE contactid='$contactid'";

            $result = mysqli_query($dbc, $query_check_credentials);

            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {
                echo "<table class='table table-bordered'>";
                echo "<tr>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Mobile Phone#</th>
				<th>Home Phone#</th>
                <th>Office Phone#</th>
                <th>Function</th>
                ";
                echo "</tr>";
            } else{
            	echo "<h2>No Record Found.</h2>";
            }

            while($row = mysqli_fetch_array( $result ))
            {
            	echo '<td data-title="Name">' . decryptIt($row['first_name']). ' '.decryptIt($row['last_name']) . '</td>';
                echo '<td data-title="Username">' . decryptIt($row['user_name']) . '</td>';
            	echo '<td data-title="Email">' . decryptIt($row['email_address']) . '</td>';
            	echo '<td data-title="Mobile Phone">' . decryptIt($row['cell_phone']) . '</td>';
				echo '<td data-title="Home Phone">' . decryptIt($row['home_phone']) . '</td>';
                echo '<td data-title="Home Phone">' . decryptIt($row['office_phone']) . '</td>';

                echo '<td data-title="Function">';
				echo '<a href=\'add_contact.php?from=profile&contactid='.$row['contactid'].'\'>Edit</a>';

				echo '</td>';

            	echo "</tr>";
            }

            echo '</table></div>';

            ?>

	</div>
</div>

<?php include ('footer.php'); ?>