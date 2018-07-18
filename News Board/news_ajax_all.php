<?php
include ('../database_connection.php');
include ('../database_connection_htg.php');
include ('../function.php');
include ('../global.php');
error_reporting(0);


if($_GET['fill'] == 'cross_software_approval') {
	$id = $_GET['status'];
	$dbc_conn = $_GET['dbc'];
	$dbc_cross = ${'dbc_cross_'.$dbc_conn}; 
	if(isset($_GET['disapprove'])) {
		$message = $_GET['name'];
		mysqli_query($dbc_cross,"UPDATE `newsboard` SET cross_software_approval = 'disapproved' WHERE newsboardid='$id'") or die(mysqli_error($dbc_cross)); 
	} else {
		mysqli_query($dbc_cross,"UPDATE `newsboard` SET cross_software_approval = '1' WHERE newsboardid='$id'") or die(mysqli_error($dbc_cross)); 
	}
}

if($_GET['fill'] == 'comment_reply') {
    $newsboardid = $_POST['newsboardid'];
    $contactid = $_POST['contactid'];
    $software_name = $_POST['software_name'];
    $title = $_POST['title'];
    $created_date = date('Y-m-d');
    $comment = $_POST['comment'];
    
    if ( !empty($software_name) ) {
        //Softwarewide
        $query = "INSERT INTO `newsboard_comments` (`newsboardid`, `contactid`, `software_name`, `created_date`, `comment`) VALUES('$newsboardid', '$contactid', '$software_name', '$created_date', '$comment')";
        mysqli_query($dbc_htg, $query);
        
        $subject = 'New Comment Added - '. $software_name;
        $body = '<h3>'.$subject.'</h3>
        Title: '. $title .'<br />
        Date: '. $created_date .'<br />
        Comment: '. $comment .'<br />';
        $error = '';
        $ffm_recepient = mysqli_fetch_assoc(mysqli_query($dbc_htg, "SELECT `comment_reply_recepient_email` FROM `newsboard_config`"))['comment_reply_recepient_email'];
        
        if ( empty($ffm_recepient) ) {
            $ffm_recepient = 'info@rookconnect.com';
        }
        
        try {
            send_email('', $ffm_recepient, '', '', $subject, html_entity_decode($body), '');
        } catch (Exception $e) {
            $error .= "Unable to send email: ".$e->getMessage()."\n";
        }
    
    } else {
        //Local
        $query = "INSERT INTO `newsboard_comments` (`newsboardid`, `contactid`, `created_date`, `comment`) VALUES('$newsboardid', '$contactid', '$created_date', '$comment')";
        mysqli_query($dbc, $query);
    }
}
?>