 <?php
    $today_date = date('Y-m-d');
    $contactid = $_SESSION['contactid'];

    $fields = '';
    $fields = $_POST['fields_0'].'**FFM**';

    $fields = filter_var(htmlentities($fields),FILTER_SANITIZE_STRING);

    $query_insert_site = "INSERT INTO `hr_employment_agreement` (`hrid`, `today_date`, `contactid`, `fields`) VALUES	('$hrid', '$today_date', '$contactid', '$fields')";
    $result_insert_site	= mysqli_query($dbc, $query_insert_site);
    $fieldlevelriskid = mysqli_insert_id($dbc);

    $img = sigJsonToImage($_POST['output']);
    imagepng($img, 'employment_agreement/download/hr_'.$_SESSION['contactid'].'.png');

	$img1 = sigJsonToImage($_POST['sign2']);
    imagepng($img1, 'employment_agreement/download/hr_'.$_SESSION['contactid'].'_sign2_.png');

    $tab = get_hr($dbc, $hrid, 'tab');
    if($tab == 'Form') {
        $assign_staff = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);

        $query_insert_upload = "INSERT INTO `hr_attendance` (`hrid`, `fieldlevelriskid`, `assign_staff`, `done`, `assign_staffid`) VALUES ('$hrid', '$fieldlevelriskid', '$assign_staff', 1, '$contactid')";
        $result_insert_upload = mysqli_query($dbc, $query_insert_upload);

        include ('employment_agreement_pdf.php');
        echo employment_agreement_pdf($dbc,$hrid, $fieldlevelriskid);
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