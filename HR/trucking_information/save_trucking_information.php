 <?php
    $today_date = date('Y-m-d');
    $contactid = $_SESSION['contactid'];

    $fields = '';
    for($i=0; $i<=24; $i++) {
        $fields .= $_POST['fields_'.$i].'**FFM**';
    }

    $fields = filter_var(htmlentities($fields),FILTER_SANITIZE_STRING);
    $desc = filter_var(htmlentities($_POST['desc']),FILTER_SANITIZE_STRING);
    $desc1 = filter_var(htmlentities($_POST['desc1']),FILTER_SANITIZE_STRING);

    if(empty($_POST['fieldlevelriskid'])) {
        $query_insert_site = "INSERT INTO `hr_trucking_information` (`hrid`, `today_date`, `contactid`, `desc`, `desc1`, `fields`) VALUES	('$hrid', '$today_date', '$contactid', '$desc', '$desc1', '$fields')";
        $result_insert_site	= mysqli_query($dbc, $query_insert_site);
        $fieldlevelriskid = mysqli_insert_id($dbc);
    } else {
        $fieldlevelriskid = $_POST['fieldlevelriskid'];
        $query_update_employee = "UPDATE `hr_trucking_information` SET `desc` = '$desc', `desc1` = '$desc1', `fields` = '$fields' WHERE fieldlevelriskid='$fieldlevelriskid'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    }

    $img = sigJsonToImage($_POST['output']);
    imagepng($img, 'trucking_information/download/hr_'.$_SESSION['contactid'].'.png');

    $tab = get_hr($dbc, $hrid, 'tab');
    if($tab == 'Form') {
        $assign_staff = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);

        $query_insert_upload = "INSERT INTO `hr_attendance` (`hrid`, `fieldlevelriskid`, `assign_staff`, `done`, `assign_staffid`) VALUES ('$hrid', '$fieldlevelriskid', '$assign_staff', 1, '$contactid')";
        $result_insert_upload = mysqli_query($dbc, $query_insert_upload);

        include ('trucking_information_pdf.php');
        echo trucking_information_pdf($dbc,$hrid, $fieldlevelriskid);
        $url_redirect = '?reports=view&tile_name='.$tile;
    }

    if($url_redirect == '') {
        $url_redirect = '?tile_name='.$tile.'&hr='.$hrid;
    }

    if($field_level_hazard == 'field_level_hazard_save') {
        echo '<script type="text/javascript">  window.location.replace("?tab='.config_safe_str($get_hr['category']).'"); </script>';
    } else {
        echo '<script type="text/javascript">
        window.location.replace("'.$url_redirect.'"); </script>';
    }