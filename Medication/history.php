<?php
/*
Add Vendor
*/
include ('../include.php');
error_reporting(0);
?>

</head>

<body>
<?php include_once ('../navigation.php');
checkAuthorised('medication');
?>
<div class="container">
  <div class="row">

    <h1 class="triple-pad-bottom">History</h1>

    <form id="form1" name="form1" method="post"	action="add_medication.php" enctype="multipart/form-data" class="form-horizontal" role="form">

        <div class="form-group">
			<?php $medicationid = filter_var($_GET['medicationid'],FILTER_SANITIZE_STRING);
            $query = "SELECT * FROM medication_history WHERE medicationid = '$medicationid'";
            $result = mysqli_query($dbc, $query);
            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) { ?>
				<table class='table table-bordered'>
					<tr class='hidden-xs hidden-sm'>
						<th>User</th>
						<th>Datetime</th>
						<th>Operation</th>
					</tr>
					<?php while($row = mysqli_fetch_array( $result ))
					{ ?>
					<tr>
						<td data-title="User"><?= get_contact($dbc, $row['userid']) ?></td>
						<td data-title="Datetime"><?php echo $row['date'];?></td>
						<td data-title="Operation"><?php echo $row['description'];?></td>
					</tr>
					<?php } ?>
				</table>
			<?php } else { 
			echo "<h2>No Record Found.</h2>";
			} ?>

            <div class="col-sm-4 clearfix">
                <a href="medication.php" class="btn brand-btn pull-right">Back</a>
            </div>
        </div>

    </form>

  </div>
</div>
<?php include ('../footer.php'); ?>
