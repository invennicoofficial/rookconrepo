<?php
include ('../database_connection.php');
include ('../global.php');
include ('../function.php');

if ($_GET['fill'] == "uploadimage") {
    $basename = $filename = htmlspecialchars($_FILES["newimage"]["name"], ENT_QUOTES);
    $file = htmlspecialchars($_FILES["newimage"]["tmp_name"], ENT_QUOTES);

    if ($_GET['name'] == "referral_promo_email") {
        if (!file_exists('download/referral_promo_email')) {
            mkdir('download/referral_promo_email', 0777, true);
        }

        $folderpath = "download/referral_promo_email/";
    }
    if ($_GET['name'] == "crm_recommend") {
        if (!file_exists('download/crm_recommend')) {
            mkdir('download/crm_recommend', 0777, true);
        }

        $folderpath = "download/crm_recommend/";
    }
    if ($_GET['name'] == "birthday_email") {
        if (!file_exists('download/birthday_email')) {
            mkdir('download/birthday_email', 0777, true);
        }

        $folderpath = "download/birthday_email/";
    }
    if ($_GET['name'] == "birthday_promo_email") {
        if (!file_exists('download/birthday_promo_email')) {
            mkdir('download/birthday_promo_email', 0777, true);
        }

        $folderpath = "download/birthday_promo_email/";
    }
    if ($_GET['name'] == "testimonial_promo_email") {
        if (!file_exists('download/testimonial_promo_email')) {
            mkdir('download/testimonial_promo_email', 0777, true);
        }

        $folderpath = "download/testimonial_promo_email/";
    }
    if ($_GET['name'] == "feedback_survey_email") {
        if (!file_exists('download/feedback_survey_email')) {
            mkdir('download/feedback_survey_email', 0777, true);
        }

        $folderpath = "download/feedback_survey_email/";
    }
    if ($_GET['name'] == "month6_follow_up") {
        if (!file_exists('download/month6_follow_up')) {
            mkdir('download/month6_follow_up', 0777, true);
        }

        $folderpath = "download/month6_follow_up/";
    }
    if ($_GET['name'] == "confirmation_email") {
        if (!file_exists('download/confirmation_email')) {
            mkdir('download/confirmation_email', 0777, true);
        }

        $folderpath = "download/confirmation_email/";
    }
    if ($_GET['name'] == "reminder_email") {
        if (!file_exists('download/reminder_email')) {
            mkdir('download/reminder_email', 0777, true);
        }

        $folderpath = "download/reminder_email/";
    }
        
    $j = 0;
    while (file_exists($folderpath . $filename)) {
        $filename = preg_replace('/(\.[A-Za-z0-9]*)/', ' ('.++$j.')$1', $basename);
    }

    $allowed_filetypes = array('.jpg','.gif','.bmp','.png');
    $ext = substr($filename, strpos($filename,'.'), strlen($filename)-1);

    if (in_array($ext, $allowed_filetypes)) {
        if (move_uploaded_file($file, $folderpath . $filename)) {
            $body_html = '<img src="'.$folderpath.$filename.'">';
            echo $body_html;
        }
    }
}
?>