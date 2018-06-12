 <?php
    $today_date = date('Y-m-d');
    $contactid = $_SESSION['contactid'];

    $fields = '';
    for($i=0; $i<=3; $i++) {
        $fields .= $_POST['fields_'.$i].'**FFM**';
    }

    $fields = filter_var(htmlentities($fields),FILTER_SANITIZE_STRING);
    $desc = filter_var(htmlentities($_POST['desc']),FILTER_SANITIZE_STRING);
    $desc1 = filter_var(htmlentities($_POST['desc1']),FILTER_SANITIZE_STRING);
    $desc2 = filter_var(htmlentities($_POST['desc2']),FILTER_SANITIZE_STRING);
    $desc3 = filter_var(htmlentities($_POST['desc3']),FILTER_SANITIZE_STRING);
    $desc4 = filter_var(htmlentities($_POST['desc4']),FILTER_SANITIZE_STRING);
    $desc5 = filter_var(htmlentities($_POST['desc5']),FILTER_SANITIZE_STRING);
    $desc6 = filter_var(htmlentities($_POST['desc6']),FILTER_SANITIZE_STRING);
    $desc7 = filter_var(htmlentities($_POST['desc7']),FILTER_SANITIZE_STRING);
    $desc8 = filter_var(htmlentities($_POST['desc8']),FILTER_SANITIZE_STRING);
    $desc9 = filter_var(htmlentities($_POST['desc9']),FILTER_SANITIZE_STRING);
    $desc10 = filter_var(htmlentities($_POST['desc10']),FILTER_SANITIZE_STRING);

    if(empty($_POST['fieldlevelriskid'])) {
        $query_insert_site = "INSERT INTO `hr_employee_self_evaluation` (`hrid`, `today_date`, `contactid`, `fields`, `desc`, `desc1`, `desc2`, `desc3`, `desc4`, `desc5`, `desc6`, `desc7`, `desc8`, `desc9`, `desc10`) VALUES	('$hrid', '$today_date', '$contactid', '$fields', '$desc', '$desc1', '$desc2', '$desc3', '$desc4', '$desc5', '$desc6', '$desc7', '$desc8', '$desc9', '$desc10')";
        $result_insert_site	= mysqli_query($dbc, $query_insert_site);
        $fieldlevelriskid = mysqli_insert_id($dbc);
    } else {
        $fieldlevelriskid = $_POST['fieldlevelriskid'];
        $query_update_employee = "UPDATE `hr_employee_self_evaluation` SET `fields` = '$fields', `desc` = '$desc', `desc1` = '$desc1', `desc2` = '$desc2', `desc3` = '$desc3', `desc4` = '$desc4', `desc5` = '$desc5', `desc6` = '$desc6', `desc7` = '$desc7', `desc8` = '$desc8', `desc9` = '$desc9', `desc10` = '$desc10' WHERE fieldlevelriskid='$fieldlevelriskid'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    }

    $img = sigJsonToImage($_POST['output']);
    imagepng($img, 'employee_self_evaluation/download/hr_'.$_SESSION['contactid'].'.png');

    $tab = get_hr($dbc, $hrid, 'tab');
    if($tab == 'Form') {
        $assign_staff = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);

        $query_insert_upload = "INSERT INTO `hr_attendance` (`hrid`, `fieldlevelriskid`, `assign_staff`, `done`, `assign_staffid`) VALUES ('$hrid', '$fieldlevelriskid', '$assign_staff', 1, '$contactid')";
        $result_insert_upload = mysqli_query($dbc, $query_insert_upload);

        include ('employee_self_evaluation_pdf.php');
        echo employee_self_evaluation_pdf($dbc,$hrid, $fieldlevelriskid);
        $url_redirect = '?reports=view&tile_name='.$tile;
    }

    if($url_redirect == '') {
        $url_redirect = '?tile_name='.$tile.'&hr='.$hrid;
    }

    if($field_level_hazard == 'field_level_hazard_save') {
        echo '<script type="text/javascript"> window.location.replace("?tab='.config_safe_str($get_hr['category']).'"); </script>';
    } else {
        echo '<script type="text/javascript">
        window.location.replace("'.$url_redirect.'"); </script>';
    }