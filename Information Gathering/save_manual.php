 <?php
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
    if(empty($_POST['infogatheringid'])) {
        $query_insert_vendor = "INSERT INTO `infogathering` (`form`, `category`, `heading_number`, `heading`, `sub_heading_number`, `sub_heading`, `third_heading`, `third_heading_number`, `form_name`, `description`, `assign_staff`, `deadline`, `last_edited`, `user_form_id`) VALUES ('$form', '$category', '$heading_number', '$heading', '$sub_heading_number', '$sub_heading', '$third_heading', '$third_heading_number', '$form_name', '$description', '$assign_staff', '$deadline', '$last_edited', '$user_form_id')";
        $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
        $infogatheringid = mysqli_insert_id($dbc);

        $url = 'Added';
    } else {
        $infogatheringid = $_POST['infogatheringid'];

        $get_manual = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT description, last_edited FROM infogathering WHERE infogatheringid='$infogatheringid'"));
        $db_desc = $get_manual['description'];

        if($db_desc == $description) {
            $last_edited = $get_manual['last_edited'];
        } else {
            $last_edited = date('Y-m-d');
        }

        $query_update_vendor = "UPDATE `infogathering` SET `form` = '$form', `category` = '$category', `heading_number` = '$heading_number', `heading` = '$heading', `sub_heading_number` = '$sub_heading_number', `sub_heading` = '$sub_heading', `third_heading_number` = '$third_heading_number', `third_heading` = '$third_heading', `form_name` = '$form_name', `description` = '$description', `assign_staff` = '$assign_staff', `deadline` = '$deadline', `last_edited` = '$last_edited', `user_form_id` = '$user_form_id' WHERE `infogatheringid` = '$infogatheringid'";
        $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
        $url = 'Updated';
    }

    $assign_staff = $_POST['assign_staff'];
    for($i = 0; $i < count($_POST['assign_staff']); $i++) {
        if($assign_staff[$i] != '') {
            $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(infogatheringstaffid) AS total_id FROM infogathering_staff WHERE infogatheringid='$infogatheringid' AND staffid='$assign_staff[$i]' AND done=0"));
            if($get_staff['total_id'] == 0) {

                //Mail
                $to = get_email($dbc, $assign_staff[$i]);;
				$sender = (!empty($_POST['email_sender']) ? $_POST['email_sender'] : '');
				$subject = $_POST['email_subject'];
				$email_body = str_replace(['[INFOID]','[MANUALTYPE]'], [$infogatheringid,$manual_type], $_POST['email_body']);

				if($to != '') {
					try {
						send_email($sender, $to, '', '', $subject, $email_body, '');
					} catch(Exception $e) {
						echo "<script>alert('Unable to send email. Please try again later.');</script>";
					}
				}
                send_email('', $to, '', '', $subject, $message, '');

                //Mail

                $query_insert_upload = "INSERT INTO `infogathering_staff` (`infogatheringid`, `staffid`) VALUES ('$infogatheringid', '$assign_staff[$i]')";
                $result_insert_upload = mysqli_query($dbc, $query_insert_upload);
            }
        }
    }

    $last_edited = date('Y-m-d');
    $document = htmlspecialchars($_FILES["document"]["name"], ENT_QUOTES);
    for($i = 0; $i < count($_FILES['document']['name']); $i++) {
        if($document[$i] != '') {
            move_uploaded_file($_FILES["document"]["tmp_name"][$i], "download/" . $_FILES["document"]["name"][$i]) ;
            $query_insert_upload = "INSERT INTO `infogathering_upload` (`infogatheringid`, `type`, `upload`) VALUES ('$infogatheringid', 'document', '$document[$i]')";
            $result_insert_upload = mysqli_query($dbc, $query_insert_upload);

            if($url == 'Updated') {
                $result_update_manual = mysqli_query($dbc, "UPDATE `infogathering` SET `last_edited` = '$last_edited' WHERE `infogatheringid` = '$infogatheringid'");
            }
        }
    }

    $link = $_POST['link'];
    for($i = 0; $i < count($_POST['link']); $i++) {
        if($link[$i] != '') {
            $query_insert_upload = "INSERT INTO `infogathering_upload` (`infogatheringid`, `type`, `upload`) VALUES ('$infogatheringid', 'link', '$link[$i]')";
            $result_insert_upload = mysqli_query($dbc, $query_insert_upload);

            if($url == 'Updated') {
                $result_update_manual = mysqli_query($dbc, "UPDATE `infogathering` SET `last_edited` = '$last_edited' WHERE `infogatheringid` = '$infogatheringid'");
            }
        }
    }

    $video = htmlspecialchars($_FILES["video"]["name"], ENT_QUOTES);
    for($i = 0; $i < count($_FILES['video']['name']); $i++) {
        if($video[$i] != '') {
            move_uploaded_file($_FILES["video"]["tmp_name"][$i], "download/" . $_FILES["video"]["name"][$i]) ;

            $query_insert_upload = "INSERT INTO `infogathering_upload` (`infogatheringid`, `type`, `upload`) VALUES ('$infogatheringid', 'video', '$video[$i]')";
            $result_insert_upload = mysqli_query($dbc, $query_insert_upload);

            if($url == 'Updated') {
                $result_update_manual = mysqli_query($dbc, "UPDATE `infogathering` SET `last_edited` = '$last_edited' WHERE `infogatheringid` = '$infogatheringid'");
            }
        }
    }

    echo '<script type="text/javascript"> window.location.replace("infogathering.php?category='.$category.'"); </script>';

 //   mysqli_close($dbc);//Close the DB Connection