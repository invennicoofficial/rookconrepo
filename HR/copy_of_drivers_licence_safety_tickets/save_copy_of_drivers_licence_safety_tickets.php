 <?php
    $today_date = date('Y-m-d');
    $contactid = $_SESSION['contactid'];

    $fields = '';
    for($i=0; $i<=3; $i++) {
        $field = implode('*#*',$_POST['fields_'.$i]);
        $fields .= $field.'**FFM**';
    }

    $fields = filter_var(htmlentities($fields),FILTER_SANITIZE_STRING);
	$desc = filter_var(htmlentities(implode('**FFM**',$_POST['desc'])),FILTER_SANITIZE_STRING);
    $document = '';

    if(!file_exists('copy_of_drivers_licence_safety_tickets/download/')) {
        mkdir('copy_of_drivers_licence_safety_tickets/download', 0777, true);
    }

    for ($i = 0; $i < count($_FILES['doc_upload']['name']); $i++) {
        $filename = '';
        if (!empty($_FILES['doc_upload']['tmp_name'][$i])) {
            $filename = $basename = preg_replace('/[^a-z0-9.]*/','',strtolower($_FILES['doc_upload']['name'][$i]));
            $j = 0;
            while(file_exists('copy_of_drivers_licence_safety_tickets/download/'.$filename)) {
                $filename = preg_replace('/(\.[a-z0-9]*)/', ' ('.++$j.')$1', $basename);
            }

            move_uploaded_file($_FILES["doc_upload"]["tmp_name"][$i],"copy_of_drivers_licence_safety_tickets/download/" . $filename);
        }
        $document .= htmlspecialchars($filename, ENT_QUOTES) . '**FFM**';
    }

    //if(empty($_POST['fieldlevelriskid'])) {
        $query_insert_site = "INSERT INTO `hr_copy_of_drivers_licence_safety_tickets` (`hrid`, `today_date`, `contactid`, `fields`, `desc`, `document`) VALUES	('$hrid', '$today_date', '$contactid', '$fields', '$desc', '$document')";
        $result_insert_site	= mysqli_query($dbc, $query_insert_site);
        $fieldlevelriskid = mysqli_insert_id($dbc);
    //} else {
    //    $fieldlevelriskid = $_POST['fieldlevelriskid'];
    //    $query_update_employee = "UPDATE `hr_copy_of_drivers_licence_safety_tickets` SET `fields` = '$fields', `desc` = '$desc', `document` = '$document' WHERE fieldlevelriskid='$fieldlevelriskid'";
    //    $result_update_employee = mysqli_query($dbc, $query_update_employee);
    //}

    //$img = sigJsonToImage($_POST['output']);
    //imagepng($img, 'copy_of_drivers_licence_safety_tickets/download/hr_'.$_SESSION['contactid'].'.png');

    $tab = get_hr($dbc, $hrid, 'tab');
    if($tab == 'Form') {
        $assign_staff = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);

        $query_insert_upload = "INSERT INTO `hr_attendance` (`hrid`, `fieldlevelriskid`, `assign_staff`, `done`, `assign_staffid`) VALUES ('$hrid', '$fieldlevelriskid', '$assign_staff', 1, '$contactid')";
        $result_insert_upload = mysqli_query($dbc, $query_insert_upload);

        include ('copy_of_drivers_licence_safety_tickets_pdf.php');
        echo copy_of_drivers_licence_safety_tickets_pdf($dbc,$hrid, $fieldlevelriskid);
        $url_redirect = '?reports=view&tile_name='.$tile;
    }

    if($url_redirect == '') {
        $url_redirect = '?tile_name='.$tile.'&hr='.$hrid;
    }

    if($field_level_hazard == 'field_level_hazard_save') {
        echo '<script type="text/javascript"> window.location.replace("?tab='.config_safe_str($get_hr['category']).'"); </script>';
    } else {
        echo '<script type="text/javascript"> window.location.replace("'.$url_redirect.'"); </script>';
    }