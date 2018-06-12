<?php
/*
Privileges Change Log
*/
include ('../include.php');
error_reporting(0);
$type_get = '';
if(isset($_GET['type'])) {
	$type_get = $_GET['type'];
}
// END EXPORT
?>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised();
?>
<div class="container">

  <div class="row add">
		<h1	class="triple-pad-bottom">Changes to Security Privileges (History)</h1>
		<?php
?><br><!--<a href="inventory.php?category=Top"	class="btn brand-btn btn-lg ">Back</a>--><a href="#" class="btn brand-btn btn-lg" onclick="history.go(-1);return false;">Back</a><br><?php
    $query_check_credentials = "SELECT * FROM security_privileges_log ORDER BY date_time DESC LIMIT 1000";
	 $gettotalrows = "SELECT * FROM security_privileges_log";
            $result = mysqli_query($dbc, $query_check_credentials);
			$xxres = mysqli_query($dbc, $gettotalrows);
            $num_rows = mysqli_num_rows($result);
			  $num_rowst = mysqli_num_rows($xxres);
            if($num_rows > 0) {
				echo "<br>Currently displaying the last $num_rows rows (out of a total of $num_rowst rows).<br><br>";
                echo "<table class='table table-bordered '>";
                echo "<tr class='hidden-xs hidden-sm'>";
                        echo '<th>Tile Changed</th>';
                        echo '<th>Privileges</th>';
						echo '<th>Role Changed</th>';
                        echo '<th>Date/Time</th>';
                        echo '<th>Author</th>';
                    echo "</tr>";
            } else {
                echo "<h2 class ='list_dashboard'>No Record Found.</h2>";
            }

            while($row = mysqli_fetch_array( $result ))
            {
                echo "<tr>";
						echo '<td data-title="Tile Changed">' . $row['tile'] . '</td>';
                        echo '<td data-title="Privileges">';
						echo $row['privileges'];
						echo '</td>';
						echo '<td data-title="Role Changed">'.$row['level'];
						echo'</td>';
						$time = substr($row['date_time'], strpos($row['date_time'], ' '));
						$time = date("g:i a", strtotime($time));
						$arr = explode(' ',trim($row['date_time']));
						echo '<td data-title="Date & Time">'.$arr[0].' at '.$time. '</td>';
						echo '<td data-title="Author">';
						
						echo $row['contact'] . '</td>';
                echo "</tr>";
            }

            echo '</table></div>';  ?>
					
  </div>
<?php include ('../footer.php'); ?>