<?php
/*
Comment
*/
include ('include.php');
?>

<?php
$com = '';
if(isset($_GET['com'])) {
    $com = $_GET['com'];
}
$nom = '';
if(isset($_GET['nom'])) {
    $nom = $_GET['nom'];
}
$fromid = $_GET['fromid'];
$from = $_GET['from'];

if($com == 'comment') {
	$result = mysqli_query($dbc, "DELETE FROM comment WHERE commentid = '$nom'");
}

if (isset($_POST['submit'])) {
    $fromid = $_POST['fromid'];
	$from = $_POST['from'];

    $comment = filter_var(htmlentities($_POST['comment']),FILTER_SANITIZE_STRING);
    $goals = filter_var(htmlentities($_POST['goals']),FILTER_SANITIZE_STRING);
    $key_points = filter_var(htmlentities($_POST['key_points']),FILTER_SANITIZE_STRING);
    $likes = filter_var(htmlentities($_POST['likes']),FILTER_SANITIZE_STRING);
    $dislikes = filter_var(htmlentities($_POST['dislikes']),FILTER_SANITIZE_STRING);

    $query_insert_comment = "INSERT INTO `comment` (`fromid`, `comment`, `goals`, `key_points`, `likes`, `dislikes`, `from_page`) VALUES ('$fromid', '$comment', '$goals', '$key_points', '$likes', '$dislikes', '$from')";

	$result_insert_comment = mysqli_query($dbc, $query_insert_comment);

	if($from == 'users') {
		header('Location: users.php');
	}
	if($from == 'clients') {
		header('Location: clients.php');
	}
	if($from == 'contacts') {
		header('Location: contacts.php');
	}
	if($from == 'sales_lead') {
		header('Location: sales_lead.php');
	}
	if($from == 'call_log') {
		header('Location: sales_lead.php');
	}
	if(($from == 'services')) {
		header('Location: potential_project.php');
	}
	if($from == 'request') {
		header('Location: estimate_quote_request.php');
	}
	if($from == 'cost_analysis') {
		header('Location: cost_analysis.php');
	}
	if($from == 'estimate') {
		header('Location: estimate.php');
	}
	if($from == 'projects') {
		header('Location: projects.php');
	}
	if($from == 'tickets') {
		header('Location: index.php');
	}

	//header('Location: comment.php?from='.$from.'&fromid='.$fromid.'');
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

				$query_check_credentials = "SELECT * FROM comment WHERE fromid = '$fromid' AND from_page='$from' ORDER BY commentid DESC";

                $result = mysqli_query($dbc, $query_check_credentials);

                $num_rows = mysqli_num_rows($result);
                if($num_rows > 0) {
                    echo "<table class='table table-bordered'>";
                    echo "<tr class='hidden-xs hidden-sm'>
                    <th>Comment</th>
                    <th>Goals</th>
                    <th>Key Points</th>
					<th>Likes</th>
					<th>Dislikes</th>
					<th>Delete</th>
                    </tr>";
                } else {
                    //echo "<h2>No Comment found.</h2>";
                }
                while($row = mysqli_fetch_array( $result ))
                {
                    echo '<tr>';
                    echo '<td data-title="Comment">' . html_entity_decode($row['comment']) . '</td>';
                    echo '<td data-title="Goals">' . html_entity_decode($row['goals']) . '</td>';
                    echo '<td data-title="Key Points">' . html_entity_decode($row['key_points']) . '</td>';
                    echo '<td data-title="Likes">' . html_entity_decode($row['likes']) . '</td>';
					echo '<td data-title="Dislikes">' . html_entity_decode($row['dislikes']) . '</td>';
					?><td data-title="Delete"><a href="comment.php?com=comment&nom=<?php echo ''.$row['commentid'].'&from='.$from.'&fromid='.$fromid.''; ?>"><button onclick="return confirm('Are you sure you want to delete this item?');" class="deleter">Archive</button></a></td><?php
                    echo "</tr>";
                }

                echo '</table></div>';

            ?>

            <form id="form1" name="form1" method="post" action="comment.php" class="form-horizontal" role="form">
                <?php
					$fromid = $_GET['fromid'];
					$from = $_GET['from'];
				?>
                <input type="hidden" name="fromid" value="<?php echo $fromid; ?>" />
				<input type="hidden" name="from" value="<?php echo $from; ?>" />

                <div class="form-group">
                    <label for="site_name" class="col-sm-4 control-label">Comment<span class="text-red">*</span>:</label>
                    <div class="col-sm-8">
                      <textarea name="comment" rows="5" cols="50" class="form-control"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="site_name" class="col-sm-4 control-label">Goals:</label>
                    <div class="col-sm-8">
                      <textarea name="goals" rows="3" cols="50" class="form-control"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="site_name" class="col-sm-4 control-label">Key Points:</label>
                    <div class="col-sm-8">
                      <textarea name="key_points" rows="3" cols="50" class="form-control"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="site_name" class="col-sm-4 control-label">Likes:</label>
                    <div class="col-sm-8">
                      <textarea name="likes" rows="3" cols="50" class="form-control"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="site_name" class="col-sm-4 control-label">Dislikes:</label>
                    <div class="col-sm-8">
                      <textarea name="dislikes" rows="3" cols="50" class="form-control"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-4 clearfix">
					<?php
					if($_GET['from'] == 'users') {
						$url = 'users.php';
					}
					if($_GET['from'] == 'clients') {
						$url = 'clients.php';
					}
					if($_GET['from'] == 'contacts') {
						$url = 'contacts.php';
					}

					if($_GET['from'] == 'sales_lead') {
						$url = 'sales_lead.php';
					}
					if($_GET['from'] == 'call_log') {
						$url = 'sales_lead.php';
					}
					if(($_GET['from'] == 'services')) {
						$url = 'potential_project.php';
					}
					if($_GET['from'] == 'request') {
						$url = 'estimate_quote_request.php';
					}
					if($_GET['from'] == 'cost_analysis') {
						$url = 'cost_analysis.php';
					}
					if($_GET['from'] == 'estimate') {
						$url = 'estimate.php';
					}
					if($_GET['from'] == 'projects') {
						$url = 'projects.php';
					}
					if($_GET['from'] == 'tickets') {
						$url = 'index.php';
					}
					?>
					<a href="<?php echo $url; ?>" class="btn brand-btn pull-right">Back</a>
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