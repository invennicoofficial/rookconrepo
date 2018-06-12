 <?php
	$tab = $_POST['tab'];
	if($_POST['new_category'] != '') {
		$category = filter_var($_POST['new_category'],FILTER_SANITIZE_STRING);
	} else {
		$category = filter_var($_POST['category'],FILTER_SANITIZE_STRING);
	}
	if($_POST['new_heading'] != '') {
		$heading = filter_var($_POST['new_heading'],FILTER_SANITIZE_STRING);
	} else {
		$heading = filter_var($_POST['heading'],FILTER_SANITIZE_STRING);
	}

	if($_POST['new_heading_number'] != '') {
		$heading_number = filter_var($_POST['new_heading_number'],FILTER_SANITIZE_STRING);
	} else {
		$heading_number = filter_var($_POST['heading_number'],FILTER_SANITIZE_STRING);
	}

    //$heading_number = filter_var($_POST['heading_number'],FILTER_SANITIZE_STRING);
    $sub_heading_number = filter_var($_POST['sub_heading_number'],FILTER_SANITIZE_STRING);
    $sub_heading = filter_var($_POST['sub_heading'],FILTER_SANITIZE_STRING);

    $third_heading = filter_var($_POST['third_heading'],FILTER_SANITIZE_STRING);
    $third_heading_number = filter_var($_POST['third_heading_number'],FILTER_SANITIZE_STRING);

    $description = filter_var(htmlentities($_POST['description']),FILTER_SANITIZE_STRING);
    $assign_staff = ','.implode(',',$_POST['assign_staff']).',';
    $deadline = filter_var($_POST['deadline'],FILTER_SANITIZE_STRING);

    $form_name = implode(',',$_POST['form_name']);

    $form = filter_var($_POST['form_name'],FILTER_SANITIZE_STRING);

    $user_form_id = '';
    $is_user_form = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(*) as num_rows FROM `user_forms` WHERE `form_id` = '$form'"));
    if ($is_user_form['num_rows'] > 0) {
        $query = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `user_forms` WHERE `form_id` = '$form'"));
        $user_form_id = $query['form_id'];
        $form = $query['name'];
    }

    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }

    $last_edited = date('Y-m-d');
    if(empty($_POST['patientformid'])) {
        $query_insert_vendor = "INSERT INTO `patientform` (`form`, `tab`, `category`, `heading_number`, `heading`, `sub_heading_number`, `sub_heading`, `third_heading`, `third_heading_number`, `form_name`, `description`, `assign_staff`, `deadline`, `last_edited`, `user_form_id`) VALUES ('$form', '$tab', '$category', '$heading_number', '$heading', '$sub_heading_number', '$sub_heading', '$third_heading', '$third_heading_number', '$form_name', '$description', '$assign_staff', '$deadline', '$last_edited', '$user_form_id')";
        $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
        $patientformid = mysqli_insert_id($dbc);

        $url = 'Added';
    } else {
        $patientformid = $_POST['patientformid'];

        $get_manual = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT description, last_edited FROM patientform WHERE patientformid='$patientformid'"));
        $db_desc = $get_manual['description'];

        if($db_desc == $description) {
            $last_edited = $get_manual['last_edited'];
        } else {
            $last_edited = date('Y-m-d');
        }

        $query_update_vendor = "UPDATE `patientform` SET `form` = '$form', `tab` = '$tab', `category` = '$category', `heading_number` = '$heading_number', `heading` = '$heading', `sub_heading_number` = '$sub_heading_number', `sub_heading` = '$sub_heading', `third_heading_number` = '$third_heading_number', `third_heading` = '$third_heading', `form_name` = '$form_name', `description` = '$description', `assign_staff` = '$assign_staff', `deadline` = '$deadline', `last_edited` = '$last_edited', `user_form_id` = '$user_form_id' WHERE `patientformid` = '$patientformid'";
        $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
        $url = 'Updated';
    }

    $assign_staff = $_POST['assign_staff'];
    for($i = 0; $i < count($_POST['assign_staff']); $i++) {
        if($assign_staff[$i] != '') {
            $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(patientformstaffid) AS total_id FROM patientform_staff WHERE patientformid='$patientformid' AND staffid='$assign_staff[$i]' AND done=0"));
            if($get_staff['total_id'] == 0) {

                //Mail
                $to = get_email($dbc, $assign_staff[$i]);
                $subject = 'Information Gathering Assigned to you for Review';
                $message = "Please login with software and click on below link. If you have any question or concern then add comment to it. And don't forget to Sign that manual to complete process of manual.<br><br>";
                $message .= 'Information Gathering : <a target="_blank" href="'.WEBSITE_URL.'/Staff/add_manual.php?patientformid='.$patientformid.'&type='.$manual_type.'&action=view">Click Here</a><br>';

                send_email('', $to, '', '', $subject, $message, '');

                //Mail

                $query_insert_upload = "INSERT INTO `patientform_staff` (`patientformid`, `staffid`) VALUES ('$patientformid', '$assign_staff[$i]')";
                $result_insert_upload = mysqli_query($dbc, $query_insert_upload);
            }
        }
    }

    $last_edited = date('Y-m-d');
    $document = htmlspecialchars($_FILES["document"]["name"], ENT_QUOTES);
    for($i = 0; $i < count($_FILES['document']['name']); $i++) {
        if($document[$i] != '') {
            move_uploaded_file($_FILES["document"]["tmp_name"][$i], "download/" . $_FILES["document"]["name"][$i]) ;
            $query_insert_upload = "INSERT INTO `patientform_upload` (`patientformid`, `type`, `upload`) VALUES ('$patientformid', 'document', '$document[$i]')";
            $result_insert_upload = mysqli_query($dbc, $query_insert_upload);

            if($url == 'Updated') {
                $result_update_manual = mysqli_query($dbc, "UPDATE `patientform` SET `last_edited` = '$last_edited' WHERE `patientformid` = '$patientformid'");
            }
        }
    }

    $link = $_POST['link'];
    for($i = 0; $i < count($_POST['link']); $i++) {
        if($link[$i] != '') {
            $query_insert_upload = "INSERT INTO `patientform_upload` (`patientformid`, `type`, `upload`) VALUES ('$patientformid', 'link', '$link[$i]')";
            $result_insert_upload = mysqli_query($dbc, $query_insert_upload);

            if($url == 'Updated') {
                $result_update_manual = mysqli_query($dbc, "UPDATE `patientform` SET `last_edited` = '$last_edited' WHERE `patientformid` = '$patientformid'");
            }
        }
    }

    $video = $_FILES["video"]["name"];
    for($i = 0; $i < count($_FILES['video']['name']); $i++) {
        if($video[$i] != '') {
            move_uploaded_file($_FILES["video"]["tmp_name"][$i], "download/" . $_FILES["video"]["name"][$i]) ;

            $query_insert_upload = "INSERT INTO `patientform_upload` (`patientformid`, `type`, `upload`) VALUES ('$patientformid', 'video', '$video[$i]')";
            $result_insert_upload = mysqli_query($dbc, $query_insert_upload);

            if($url == 'Updated') {
                $result_update_manual = mysqli_query($dbc, "UPDATE `patientform` SET `last_edited` = '$last_edited' WHERE `patientformid` = '$patientformid'");
            }
        }
    }

	$redirect = isset($_GET['from_url']) ? urldecode($_GET['from_url']) : 'patientform.php?category='.$category;
    echo '<script type="text/javascript"> window.location.replace("'.$redirect.'"); </script>';

 //   mysqli_close($dbc);//Close the DB Connection