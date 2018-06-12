 <?php
    $today_date = $_POST['today_date'];
    $contactid = $_SESSION['contactid'];

    $location = filter_var($_POST['location'],FILTER_SANITIZE_STRING);
    $hazard_rating = filter_var($_POST['hazard_rating'],FILTER_SANITIZE_STRING);
    $action_timeline = filter_var($_POST['action_timeline'],FILTER_SANITIZE_STRING);
    $description = filter_var(htmlentities($_POST['description']),FILTER_SANITIZE_STRING);
    $action = filter_var(htmlentities($_POST['action']),FILTER_SANITIZE_STRING);
    $action_to = filter_var($_POST['action_to'],FILTER_SANITIZE_STRING);
    $est_comp = filter_var($_POST['est_comp'],FILTER_SANITIZE_STRING);
    $date_comp = filter_var($_POST['date_comp'],FILTER_SANITIZE_STRING);
    $analysis_to = filter_var($_POST['analysis_to'],FILTER_SANITIZE_STRING);

    $attendance_staff = implode(',',$_POST['attendance_staff']);
    $attendance_extra = $_POST['attendance_extra'];

    $url_redirect = '';

    if(empty($_POST['fieldlevelriskid'])) {
        $query_insert_site = "INSERT INTO `hr_avs_near_miss` (`hrid`, `today_date`, `contactid`, `location`, `hazard_rating`, `action_timeline`, `description`, `action`, `action_to`, `est_comp`, `date_comp`, `analysis_to`, `attendance_staff`, `attendance_extra`) VALUES	('$hrid', '$today_date', '$contactid', '$location', '$hazard_rating', '$action_timeline', '$description', '$action', '$action_to', '$est_comp', '$date_comp', '$analysis_to', '$attendance_staff', '$attendance_extra')";
        $result_insert_site	= mysqli_query($dbc, $query_insert_site);
        $fieldlevelriskid = mysqli_insert_id($dbc);

        $attendance_staff_each = $_POST['attendance_staff'];
        for($i = 0; $i < count($_POST['attendance_staff']); $i++) {
            $query_insert_upload = "INSERT INTO `hr_attendance` (`hrid`, `fieldlevelriskid`, `assign_staff`) VALUES ('$hrid', '$fieldlevelriskid', '$attendance_staff_each[$i]')";
            $result_insert_upload = mysqli_query($dbc, $query_insert_upload);
        }

        for($i=1;$i<=$attendance_extra;$i++) {
            $att_ex = 'Extra '.$i;
            $query_insert_upload = "INSERT INTO `hr_attendance` (`hrid`, `fieldlevelriskid`, `assign_staff`) VALUES ('$hrid', '$fieldlevelriskid', '$att_ex')";
            $result_insert_upload = mysqli_query($dbc, $query_insert_upload);
        }

        $tab = get_hr($dbc, $hrid, 'tab');
        if($tab == 'Form') {
            $assign_staff = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);

            $query_insert_upload = "INSERT INTO `hr_attendance` (`hrid`, `fieldlevelriskid`, `assign_staff`, `done`, `assign_staffid`) VALUES ('$hrid', '$fieldlevelriskid', '$assign_staff', 1, '$contactid')";
            $result_insert_upload = mysqli_query($dbc, $query_insert_upload);

            include ('avs_near_miss_pdf.php');
            echo avs_near_miss_pdf($dbc,$hrid, $fieldlevelriskid);
            $url_redirect = '?reports=view&tile_name='.$tile;
        }

    } else {
        $fieldlevelriskid = $_POST['fieldlevelriskid'];
        $query_update_employee = "UPDATE `hr_avs_near_miss` SET `location` = '$location', `hazard_rating` = '$hazard_rating', `action_timeline` = '$action_timeline', `description` = '$description', `contactid` = '$contactid', `action` = '$action', `action_to` = '$action_to', `est_comp` = '$est_comp', `date_comp` = '$date_comp', `analysis_to` = '$analysis_to' WHERE fieldlevelriskid='$fieldlevelriskid'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);

    	$sa = mysqli_query($dbc, "SELECT hrattid FROM hr_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND hrid='$hrid' AND done=0");
        while($row_sa = mysqli_fetch_array( $sa )) {
            $assign_staff_id = $row_sa['hrattid'];

            if($_POST['sign_'.$assign_staff_id] != '') {
                $sign = $_POST['sign_'.$assign_staff_id];
                $staffcheck = implode('*#*',$_POST['staffcheck_'.$assign_staff_id]);

                $img = sigJsonToImage($sign);
                imagepng($img, 'avs_near_miss/download/hr_'.$assign_staff_id.'.png');

                $assign_staff = filter_var($_POST['assign_staff_'.$assign_staff_id],FILTER_SANITIZE_STRING);

                if($assign_staff != '') {
                    $query_update_employee = "UPDATE `hr_attendance` SET `assign_staff` = '$assign_staff', `staffcheck` = '$staffcheck', `done` = 1 WHERE hrattid='$assign_staff_id'";
                    $result_update_employee = mysqli_query($dbc, $query_update_employee);
                } else {
                    $query_update_employee = "UPDATE `hr_attendance` SET `staffcheck` = '$staffcheck', `done` = 1 WHERE hrattid='$assign_staff_id'";
                    $result_update_employee = mysqli_query($dbc, $query_update_employee);
                }
            }
        }

        $get_total_notdone = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(hrattid) AS total_notdone FROM hr_attendance WHERE	fieldlevelriskid='$fieldlevelriskid' AND hrid='$hrid' AND done=0"));
        if($get_total_notdone['total_notdone'] == 0) {
            include ('avs_near_miss_pdf.php');
            echo avs_near_miss_pdf($dbc,$hrid, $fieldlevelriskid);
            $url_redirect = '?reports=view&tile_name='.$tile;
        }
    }

    if($url_redirect == '') {
        $url_redirect = '?tile_name='.$tile.'&hr='.$hrid;
    }

    if($field_level_hazard == 'field_level_hazard_save') {
        echo '<script type="text/javascript">  window.location.replace("?tab='.config_safe_str($get_hr['category']).'"); </script>';
    } else {
        echo '<script type="text/javascript"> window.location.replace("'.$url_redirect.'"); </script>';
    }