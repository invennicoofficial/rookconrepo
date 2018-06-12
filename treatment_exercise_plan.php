<?php
include ('database_connection.php');
include ('header.php');
include ('footer.php');
include ('function.php');
error_reporting(0);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
    <link href="<?php echo WEBSITE_URL;?>/img/favicon.ico" rel="shortcut icon">
    <link href="<?php echo WEBSITE_URL;?>/img/apple-touch-icon.png" rel="apple-touch-icon-precomposed">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,600' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>

    <div class="login">
        <div class="middle">
                <div class="row">
                    <div class="col-lg-12 double-pad-bottom">
                        <img src="<?php echo WEBSITE_URL;?>/img/Clinic-Ace-Logo-Final-500px.png" alt="Clinic Ace" class="center-block" width="300">
                    </div>
                </div>
                <div class="row triple-pad-top">
                    <ul class="list-inline text-center">
                        <li><a href="https://www.facebook.com/" class="social-icon facebook hide-text" target="_blank">Facebook</a></li>
                        <li><a href="https://www.linkedin.com/" class="social-icon linkedin hide-text" target="_blank">LinkedIn</a></li>
                        <li><a href="https://twitter.com/" class="social-icon twitter hide-text" target="_blank">Twitter</a></li>
                        <li><a href="https://plus.google.com/" class="social-icon google hide-text" target="_blank">Google+</a></li>
                    </ul>
                </div>

<div class="container">
  <div class="row">
        <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

                <?php
                $treatmentexerciseid = $_GET['id'];
                $get_treatment =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM	treatment_exercise_plan WHERE	treatmentexerciseid='$treatmentexerciseid'"));
                $patientid = $get_treatment['patientid'];
                $therapistsid = $get_treatment['therapistsid'];
                $injuryid = $get_treatment['injuryid'];
                $exerciseid_all = $get_treatment['exerciseid'];
                ?>
                <h1 class="triple-pad-bottom">Exercise Plan</h1>

                <div class="panel-group" id="accordion">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info" >
                                    Information<span class="glyphicon glyphicon-plus"></span>
                                </a>
                            </h4>
                        </div>

                        <div id="collapse_info" class="panel-collapse collapse">
                            <div class="panel-body">

                              <div class="form-group">
                                <label for="site_name" class="col-sm-4 control-label">Patient:</label>
                                <div class="col-sm-8">
                                    <?php echo get_contact($dbc, $patientid); ?>
                                </div>
                              </div>

                              <div class="form-group">
                                <label for="site_name" class="col-sm-4 control-label">Injury:</label>
                                <div class="col-sm-8">
                                    <?php echo get_all_from_injury($dbc, $injuryid, 'injury_name').' - '.                  get_all_from_injury($dbc, $injuryid, 'injury_type').' : '.
                                        get_all_from_injury($dbc, $injuryid, 'injury_date'); ?>
                                </div>
                              </div>

                            </div>

                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_2" >
                                    Exercise Plan<span class="glyphicon glyphicon-plus"></span>
                                </a>
                            </h4>
                        </div>

                        <div id="collapse_2" class="panel-collapse collapse">
                            <div class="panel-body">

                            <?php
                            echo "<table border='2' cellpadding='10' class='table'>";
                            echo "<tr>
                            <th>Category</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Document(s)</th>
                            <th>Link(s)</th>
                            <th>Video(s)</th>
                            </tr>";

                            $exeid = explode(',', $exerciseid_all);

                            foreach($exeid as $exerciseid) {
                                $exercise_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM exercise_config WHERE exerciseid='$exerciseid'"));
                                echo "<tr>";
                                echo '<td>' . $exercise_config['category'] . '</td>';
                                echo '<td>' . $exercise_config['title'] . '</td>';
                                echo '<td>' . $exercise_config['description'] . '</td>';

                                $result = mysqli_query($dbc, "SELECT upload, exlibraryuploadid FROM exercise_library_upload WHERE type='document' AND exerciseid='$exerciseid'");

                                echo '<td><ul>';
                                $i=0;
                                while($row = mysqli_fetch_array($result)) {
                                    $document = $row['upload'];
                                    if($document != '') {
                                        echo '<li><a href="Exercise Plan/Download/'.$document.'" target="_blank">'.$document.'</a></li>';
                                    }
                                }
                                echo '</ul></td>';

                                $result = mysqli_query($dbc, "SELECT upload, exlibraryuploadid FROM exercise_library_upload WHERE type='link' AND exerciseid='$exerciseid'");

                                echo '<td><ul>';
                                $i=0;
                                while($row = mysqli_fetch_array($result)) {
                                    $link = $row['upload'];
                                    if($link != '') {
                                        echo '<li><a href="'.$link.'" target="_blank">'.$link.'</a></li>';
                                    }
                                }
                                echo '</ul></td>';

                                $result = mysqli_query($dbc, "SELECT upload, exlibraryuploadid FROM exercise_library_upload WHERE type='video' AND exerciseid='$exerciseid'");

                                echo '<td><ul>';
                                $i=0;
                                while($row = mysqli_fetch_array($result)) {
                                    $video = $row['upload'];
                                    if($video != '') {
                                        echo '<li><a href="Exercise Plan/Download/'.$video.'" target="_blank">'.$video.'</a></li>';
                                    }
                                }
                                echo '</ul></td>';
                                echo "</tr>";
                            }
                            echo '</table>';
                            ?>

                            </div>
                        </div>
                    </div>

                </div>
    </form>
</div>
</div>

        </div>
    </div>
</body>
</html>