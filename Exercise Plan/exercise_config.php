<?php
/*
New PAtient Hidtory list
*/
include ('../include.php');
checkAuthorised('exercise_library');
?>
</head>
<body>
<?php include_once ('../navigation.php');
if(empty($_GET['view'])) {
	$_GET['view'] = 'exercise';
}
?>

<div class="container triple-pad-bottom">
    <div class="row">

        <h1 class="double-pad-bottom">Exercise Library Dashboard</h1>

        <div class="table-responsive">
            
            <div class="tab-container">
                <div class="tab pull-left">
                    <span class="popover-examples list-inline" style="margin:0 0 0 0;"><a data-toggle="tooltip" data-placement="top" title="This is where treatment information that can be sent to patients is stored."><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                    if ( check_subtab_persmission( $dbc, 'exercise_library', ROLE, 'send_plan' ) === true ) { ?>
                        <a href="exercise_config.php?view=exercise"><button type="button" class="btn brand-btn mobile-block <?php echo ($_GET['view'] == 'exercise' ? 'active_tab' : ''); ?>" >Send Exercise Plan</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">Send Exercise Plan</button><?php
                    } ?>
                </div>
                <div class="tab pull-left">
                    <span class="popover-examples list-inline" style="margin:0 0 0 0;"><a data-toggle="tooltip" data-placement="top" title="The main default library of exercises."><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                    if ( check_subtab_persmission( $dbc, 'exercise_library', ROLE, 'company' ) === true ) { ?>
                        <a href="exercise_config.php?view=master"><button type="button" class="btn brand-btn mobile-block <?php echo ($_GET['view'] == 'master' ? 'active_tab' : ''); ?>" >Company Library</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">Company Library</button><?php
                    } ?>
                </div>
                <div class="tab pull-left">
                    <span class="popover-examples list-inline" style="margin:0 0 0 10px;"><a data-toggle="tooltip" data-placement="top" title="The selected exercises for your personalized library."><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                    if ( check_subtab_persmission( $dbc, 'exercise_library', ROLE, 'private' ) === true ) { ?>
                        <a href="exercise_config.php?view=private"><button type="button" class="btn brand-btn mobile-block <?php echo ($_GET['view'] == 'private' ? 'active_tab' : ''); ?>" >My Private Library</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">My Private Library</button><?php
                    } ?>
                </div>
                <div class="clearfix"></div>
            </div><!-- .tab-container -->
            
        <form name="form_patients" method="post" action="new_patient_history.php" class="form-inline" role="form">

            <?php if($_GET['view'] == 'exercise') {
                echo '<div class="notice double-gap-bottom popover-examples">
                <div class="col-sm-1 notice-icon"><img src="'.WEBSITE_URL.'/img/info.png" class="wiggle-me" width="25"></div>
                <div class="col-sm-11"><span class="notice-name">NOTE:</span>
                Treatment information that can be emailed to patients is stored here.</div>
                <div class="clearfix"></div>
                </div>';
				include('exercise_plan.php');
			} else {
				/* Pagination Counting */
				$rowsPerPage = 25;
				$pageNum = 1;

				if(isset($_GET['page'])) {
					$pageNum = $_GET['page'];
				}

				$offset = ($pageNum - 1) * $rowsPerPage;

				$master = '';
				$private = '';
				if('master' == $_GET['view']) {
                    echo '<div class="notice double-gap-bottom popover-examples">
                    <div class="col-sm-1 notice-icon"><img src="'.WEBSITE_URL.'/img/info.png" class="wiggle-me" width="25"></div>
                    <div class="col-sm-11"><span class="notice-name">NOTE:</span>
                    Displays all company exercise programs grouped by category.</div>
                    <div class="clearfix"></div>
                    </div>';

					$query_check_credentials = "SELECT * FROM exercise_config WHERE type='Common' AND deleted=0 ORDER BY category LIMIT $offset, $rowsPerPage";
					$query = "SELECT count(*) as numrows FROM exercise_config WHERE type='Common' AND deleted=0 ORDER BY category";
				}
				if('private' == $_GET['view']) {
                    echo '<div class="notice double-gap-bottom popover-examples">
                    <div class="col-sm-1 notice-icon"><img src="'.WEBSITE_URL.'/img/info.png" class="wiggle-me" width="25"></div>
                    <div class="col-sm-11"><span class="notice-name">NOTE:</span>
                    Allows staff to store exercises that they want to use on a regular basis, and does not display to other staff. Your Exercise Programs can be fully edited or deleted from here.</div>
                    <div class="clearfix"></div>
                    </div>';

					$contactid = $_SESSION['contactid'];
					$query_check_credentials = "SELECT * FROM exercise_config WHERE type='$contactid' AND deleted=0 ORDER BY category LIMIT $offset, $rowsPerPage";
					$query = "SELECT count(*) as numrows FROM exercise_config WHERE type='$contactid' AND deleted=0 ORDER BY category";
				}
				$result = mysqli_query($dbc, $query_check_credentials);
                
				echo '<a href="add_exercise_config.php?type='.$_GET['view'].'" class="btn brand-btn pull-right">Add New Exercise</a>';

				$num_rows = mysqli_num_rows($result);
				if($num_rows > 0) {

					// Added Pagination //
					echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
					// Pagination Finish //

				}
                
				$cat = '';
				while($row = mysqli_fetch_array( $result ))
				{
                    $exerciseid = $row['exerciseid'];

                    $type = $row['type'];
                    if($type != 'Common') {
                        $type = 'My Library';
                    }

					if($row['category'] != $cat) {
						echo "<table border='2' cellpadding='10' class='table'>";
						echo "<tr>
						<th>Category</th>
						<th>Title</th>
                        <th>Link(s)</th>
						<th>Function</th>
						</tr>";
						$cat = $row['category'];
						echo '<h3>'.$row['category'].'</h3>';
					}
					echo "<tr>";
					echo '<td>' . $row['category'] . '</td>';
					echo '<td>' . $row['title'] . '</td>';

                    echo '<td>';
                    $get_doc = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(exlibraryuploadid) AS total_id FROM exercise_library_upload WHERE type='document' AND exerciseid='$exerciseid'"));

                    if($get_doc['total_id'] > 0) {
                        $result1 = mysqli_query($dbc, "SELECT upload, exlibraryuploadid FROM exercise_library_upload WHERE type='document' AND exerciseid='$exerciseid'");

                        echo '<ul>';
                        $i=0;
                        while($row1 = mysqli_fetch_array($result1)) {
                            $document = $row1['upload'];
                            if($document != '') {
                                echo '<li><a href="Download/'.$document.'" target="_blank">'.$document.'</a></li>';
                            }
                        }
                        echo '</ul>';
                    }

                    $get_doc = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(exlibraryuploadid) AS total_id FROM exercise_library_upload WHERE type='link' AND exerciseid='$exerciseid'"));

                    if($get_doc['total_id'] > 0) {
                        $result2 = mysqli_query($dbc, "SELECT upload, exlibraryuploadid FROM exercise_library_upload WHERE type='link' AND exerciseid='$exerciseid'");

                        echo '<ul>';
                        $i=0;
                        while($row2 = mysqli_fetch_array($result2)) {
                            $link = $row2['upload'];
                            if($link != '') {
                                echo '<li><a href="'.$link.'" target="_blank">'.$link.'</a></li>';
                            }
                        }
                        echo '</ul>';
                    }

                    $get_doc = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(exlibraryuploadid) AS total_id FROM exercise_library_upload WHERE type='video' AND exerciseid='$exerciseid'"));

                    if($get_doc['total_id'] > 0) {
                        $result3 = mysqli_query($dbc, "SELECT upload, exlibraryuploadid FROM exercise_library_upload WHERE type='video' AND exerciseid='$exerciseid'");

                        echo '<ul>';
                        $i=0;
                        while($row3 = mysqli_fetch_array($result3)) {
                            $video = $row3['upload'];
                            if($video != '') {
                                echo '<li><a href="Download/'.$video.'" target="_blank">'.$video.'</a></li>';
                            }
                        }
                        echo '</ul>';
                    }

                    echo '</td>';

					echo '<td><a href=\'add_exercise_config.php?exerciseid='.$exerciseid.'\'>Edit</a> | ';
					echo '<a href=\''.WEBSITE_URL.'/delete_restore.php?action=delete&exerciseid='.$exerciseid.'&view='.$_GET['view'].'\' onclick="return confirm(\'Are you sure?\')">Delete</a>
					</td>';
					echo "</tr>";
				}

				echo '</table></div>';

				// Added Pagination //
				echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
				echo "<br><br>";
				// Pagination Finish //

				echo '<a href="add_exercise_config.php" class="btn brand-btn pull-right">Add New Exercise</a>';
			}
            ?>
        </form>
            <a href="<?php echo WEBSITE_URL;?>/home.php" class="btn brand-btn">Back</a>

        </div>

    </div>

<?php include ('../footer.php'); ?>
