<?php
/*
Users Listing
*/
include ('../include.php');
checkAuthorised('routine');

if (!empty($_POST)) {
    if($_POST['routine_form_submit'] == 'yes') {
        $selected_radio = $_POST['routine_radio'];

        $explode_radio = explode("_", $selected_radio);
        $voting = $explode_radio[0];
        $routineid = $explode_radio[1];

        $created_by = $_SESSION['contactid'];
        $created_date = date('Y-m-d');

        $voting_add = mysqli_query($dbc,"SELECT * FROM routine_voting WHERE created_by='$created_by' AND routineid = '$routineid'");

        $num_rows_voting = mysqli_num_rows($voting_add);
        if($num_rows_voting == 0) {
            $query_insert_voting = "INSERT INTO `routine_voting` (`routineid`, `voting`, `created_by`, `created_date`) VALUES ('$routineid', '$voting', '$created_by', '$created_date')";
            $result_insert_voting = mysqli_query($dbc, $query_insert_voting);
        }
    } else {

    }
    echo '<script type="text/javascript"> window.location.replace("routine.php"); </script>';

}

?>
<script src="js/jquery.cookie.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".routine_function").change(function () {
            $("#routine_function").submit();
        });
    });
</script>

</head>
<body>
<?php include_once ('../navigation.php'); ?>

<div class="container">

    <div class="row">

        <h1 class="double-pad-bottom">Routine Creator</h1>

        <div class="table-responsive">

            <form name="routine_function" id="routine_function" method="post" action="" enctype="multipart/form-data"  class="form-horizontal" role="form">
            <input type="hidden" name="routine_form_submit" value='yes'>
            <?php
                echo '</table><a href="add_routine.php" class="btn brand-btn mobile-block pull-right">Add Routine</a>';

                $query_check_credentials = "SELECT * FROM routine";

                $result = mysqli_query($dbc, $query_check_credentials);

                $num_rows = mysqli_num_rows($result);
                if($num_rows > 0) {
                    echo "<table border='2' cellpadding='' class='table'>";
                    echo "<tr>
                    <th>Task</th>
                    <th>Image/Video</th>
                    <th>Mood</th>
                    </tr>";
                } else {
                    echo "<h2>No Record Found.</h2>";
                }

                while($row = mysqli_fetch_array( $result )) {
                    $routineid = $row['routineid'];
                    echo '<td>' . $row['task'] . '</td>';
                    echo '<td><a href="download/'.$row['upload_video'].'" target="_blank"><img src="download/'.$row['upload_image'].'" height="100" border="0" alt=""></a></td>';

                    $created_by = $_SESSION['contactid'];
                    $from_name = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM routine_voting WHERE created_by='$created_by' AND routineid = '$routineid'"));

                    echo '<td>';
                    if($from_name['voting'] != '') {
                            echo '<img src="../img/'.$from_name['voting'].'" border="0" alt="">';
                    } else {
                        echo '<input type="radio" style="width: 7%; height: 35%;" class="routine_function" name="routine_radio"     value="happy.png_'.$routineid.'"><img src="../img/happy.png" width="128" height="128" border="0" alt=""><input type="radio" style="width: 7%; height: 35%;" class="routine_function" name="routine_radio" value="sad.png_'.$routineid.'"><img src="../img/sad.png" width="128" height="128" border="0" alt="">';
                    }

                    echo '</td>';

                    echo "</tr>";
                }

                echo '</table><a href="add_routine.php" class="btn brand-btn mobile-block pull-right">Add Routine</a>';

                ?>
            </form>
        </div>

    </div>

</div>

<?php include ('../footer.php'); ?>