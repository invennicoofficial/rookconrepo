<?php
/*
Reject Comment
*/
include ('include.php');

if (isset($_POST['submit'])) {
    $contactid = filter_var($_POST['contactid'],FILTER_SANITIZE_STRING);

    $note_heading = filter_var($_POST['note_heading'],FILTER_SANITIZE_STRING);
	$comment = filter_var(htmlentities($_POST['comment']),FILTER_SANITIZE_STRING);

    $created_by = $_SESSION['first_name'].' '.$_SESSION['last_name'];
    $created_date = date('Y-m-d');

    $query_insert_comment = "INSERT INTO `notes` (`contactid`, `from_page`, `comment`, `note_heading`, `created_by`, `created_date`) VALUES ('$contactid', 'Reactivation', '$comment', '$note_heading', '$created_by', '$created_date')";

	$result_insert_comment = mysqli_query($dbc, $query_insert_comment);

    header('Location: notes.php?contactid='.$contactid);
}
?>
</head>
<body>
<?php include_once ('navigation.php');

?>

<div class="container">
  <div class="row">
    <div class="col-md-12">

      <h1 class="triple-pad-bottom">Comment/Note</h1>
        <div id="no-more-tables">
            <?php
                $contactid = $_GET['contactid'];
				$query_check_credentials = "SELECT * FROM notes WHERE from_page = 'Reactivation' AND contactid = '$contactid' ORDER BY noteid DESC";

                $result = mysqli_query($dbc, $query_check_credentials);

                $num_rows = mysqli_num_rows($result);
                if($num_rows > 0) {
                echo "<table class='table table-bordered'>";
                echo "<tr class='hidden-xs hidden-sm'>
                        <th>Heading</th>
                        <th>Comment/Note</th>
                        <th>Added By</th>
                        <th>Date</th>
                        </tr>";
                } else{
                    //echo "<h2>No Comment found.</h2>";
                }
                while($row = mysqli_fetch_array( $result ))
                {
                    echo '<tr>';
                    echo '<td data-title="Note Type">' . $row['note_heading'] . '</td>';
                    echo '<td data-title="Comment/Note">' . html_entity_decode($row['comment']) . '</td>';
                    echo '<td data-title="Added By">' . $row['created_by'] . '</td>';
                    echo '<td data-title="Date/Time">' . $row['created_date'] . '</td>';
                    echo "</tr>";
                }

                echo '</table></div>';

            ?>

            <form id="form1" name="form1" method="post" action="" class="form-horizontal" role="form">
                <input type="hidden" name="contactid" value="<?php echo $contactid; ?>" />

                <div class="form-group clearfix">
                    <label for="first_name" class="col-sm-4 control-label text-right">Heading:</label>
                    <div class="col-sm-8">
                        <input name="note_heading" type="text" class="form-control"></p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="site_name" class="col-sm-4 control-label">Comment:</label>
                    <div class="col-sm-8">
                      <textarea name="comment" rows="10" cols="50" class="form-control"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-4 clearfix">
                        <a href="home.php" class="btn brand-btn pull-right">Back</a>
                    </div>
                    <div class="col-sm-8">
                        <button type="submit" name="submit" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
                    </div>
                </div>

            </form>

    </div>
  </div>
</div>
<?php include ('footer.php'); ?>