<?php
/*
Configuration - Choose which functionality you want for your software. Config email subject and body part for each functionality. Config Email Send Before days/month for patient treatment/booking confirmation and reminder.
*/
include ('../include.php');
checkAuthorised('crm');
error_reporting(0);

if (isset($_POST['dashboard'])) {
    $crm_dashboard = implode(',',$_POST['crm_dashboard']);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='crm_dashboard'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$crm_dashboard' WHERE name='crm_dashboard'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('crm_dashboard', '$crm_dashboard')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $category=$_POST['category'];
    echo '<script type="text/javascript"> window.location.replace("config_crm.php?category='.$category.'"); </script>';

}

if (isset($_POST['referral'])) {
    $referral_promotions = filter_var($_POST['referral_promotions'],FILTER_SANITIZE_STRING);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='referral_promotions'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$referral_promotions' WHERE name='referral_promotions'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('referral_promotions', '$referral_promotions')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $referral_promo_email_subject = filter_var($_POST['referral_promo_email_subject'],FILTER_SANITIZE_STRING);

    $email = htmlentities($_POST['referral_promo_email_body']);
    $referral_promo_email_body = filter_var($email,FILTER_SANITIZE_STRING);

    //Birthday & Promo Email
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='referral_promo_email_subject'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$referral_promo_email_subject' WHERE name='referral_promo_email_subject'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('referral_promo_email_subject', '$referral_promo_email_subject')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='referral_promo_email_body'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$referral_promo_email_body' WHERE name='referral_promo_email_body'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('referral_promo_email_body', '$referral_promo_email_body')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //Birthday & Promo Email
    $category=$_POST['category'];
    echo '<script type="text/javascript"> window.location.replace("config_crm.php?category='.$category.'"); </script>';
}

if (isset($_POST['recommend'])) {
    $crm_recommend_address = filter_var($_POST['crm_recommend_address'],FILTER_SANITIZE_STRING);
    $crm_recommend_subject = filter_var($_POST['crm_recommend_subject'],FILTER_SANITIZE_STRING);
    $crm_recommend_body = filter_var(htmlentities($_POST['crm_recommend_body']),FILTER_SANITIZE_STRING);

	//Add the CRM Recommendations Sending Address if it doesn't exist, then update it
	mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`) SELECT 'crm_recommend_address' FROM (SELECT COUNT(*) rows FROM `general_configuration` WHERE `name`='crm_recommend_address') num WHERE num.rows=0");
	mysqli_query($dbc, "UPDATE `general_configuration` SET `value`='$crm_recommend_address' WHERE `name`='crm_recommend_address'");
	
	//Add the CRM Recommendations Subject if it doesn't exist, then update it
	mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`) SELECT 'crm_recommend_subject' FROM (SELECT COUNT(*) rows FROM `general_configuration` WHERE `name`='crm_recommend_subject') num WHERE num.rows=0");
	mysqli_query($dbc, "UPDATE `general_configuration` SET `value`='$crm_recommend_subject' WHERE `name`='crm_recommend_subject'");
	
	//Add the CRM Recommendations E-mail Body if it doesn't exist, then update it
	mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`) SELECT 'crm_recommend_body' FROM (SELECT COUNT(*) rows FROM `general_configuration` WHERE `name`='crm_recommend_body') num WHERE num.rows=0");
	mysqli_query($dbc, "UPDATE `general_configuration` SET `value`='$crm_recommend_body' WHERE `name`='crm_recommend_body'");
	
    $category=$_POST['category'];
    echo '<script type="text/javascript"> window.location.replace("config_crm.php?category='.$category.'"); </script>';
}

if (isset($_POST['birthday'])) {
    $birthday_promotions = filter_var($_POST['birthday_promotions'],FILTER_SANITIZE_STRING);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='birthday_promotions'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$birthday_promotions' WHERE name='birthday_promotions'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('birthday_promotions', '$birthday_promotions')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

   //Birthday Email
    $birthday_email_subject = filter_var($_POST['birthday_email_subject'],FILTER_SANITIZE_STRING);
    $birthday_promo_email_subject = filter_var($_POST['birthday_promo_email_subject'],FILTER_SANITIZE_STRING);

    $bdayemail = htmlentities($_POST['birthday_email_body']);
    $birthday_email_body = filter_var($bdayemail,FILTER_SANITIZE_STRING);
    $email = htmlentities($_POST['birthday_promo_email_body']);
    $birthday_promo_email_body = filter_var($email,FILTER_SANITIZE_STRING);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='birthday_email_subject'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$birthday_email_subject' WHERE name='birthday_email_subject'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('birthday_email_subject', '$birthday_email_subject')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='birthday_email_body'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$birthday_email_body' WHERE name='birthday_email_body'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('birthday_email_body', '$birthday_email_body')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //Birthday Email

    //Birthday & Promo Email
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='birthday_promo_email_subject'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$birthday_promo_email_subject' WHERE name='birthday_promo_email_subject'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('birthday_promo_email_subject', '$birthday_promo_email_subject')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='birthday_promo_email_body'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$birthday_promo_email_body' WHERE name='birthday_promo_email_body'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('birthday_promo_email_body', '$birthday_promo_email_body')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //Birthday & Promo Email
    $category=$_POST['category'];
    echo '<script type="text/javascript"> window.location.replace("config_crm.php?category='.$category.'"); </script>';

}
if (isset($_POST['testimonial'])) {
    //Testimonial Promotion

    $testimonial_promo_email_subject = filter_var($_POST['testimonial_promo_email_subject'],FILTER_SANITIZE_STRING);
    $test_promo = htmlentities($_POST['testimonial_promo_email_body']);
    $testimonial_promo_email_body = filter_var($test_promo,FILTER_SANITIZE_STRING);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='testimonial_promo_email_subject'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$testimonial_promo_email_subject' WHERE name='testimonial_promo_email_subject'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('testimonial_promo_email_subject', '$testimonial_promo_email_subject')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='testimonial_promo_email_body'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$testimonial_promo_email_body' WHERE name='testimonial_promo_email_body'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('testimonial_promo_email_body', '$testimonial_promo_email_body')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //Testimonial Promotion
    $category=$_POST['category'];
    echo '<script type="text/javascript"> window.location.replace("config_crm.php?category='.$category.'"); </script>';

}
if (isset($_POST['month'])) {
    //6 Month Follow Up Email
    $month6_follow_up_subject = filter_var($_POST['month6_follow_up_subject'],FILTER_SANITIZE_STRING);
    $followup = htmlentities($_POST['month6_follow_up_body']);
    $month6_follow_up_body = filter_var($followup,FILTER_SANITIZE_STRING);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='month6_follow_up_subject'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$month6_follow_up_subject' WHERE name='month6_follow_up_subject'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('month6_follow_up_subject', '$month6_follow_up_subject')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='month6_follow_up_body'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$month6_follow_up_body' WHERE name='month6_follow_up_body'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('month6_follow_up_body', '$month6_follow_up_body')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //6 Month Follow Up Email

    $category=$_POST['category'];
    echo '<script type="text/javascript"> window.location.replace("config_crm.php?category='.$category.'"); </script>';

}
if (isset($_POST['confirmation'])) {
    //confirmation email
    $confirmation_email_subject = filter_var($_POST['confirmation_email_subject'],FILTER_SANITIZE_STRING);
    $confirmation_email_send_before = filter_var($_POST['confirmation_email_send_before'],FILTER_SANITIZE_STRING);
    $confirmation = htmlentities($_POST['confirmation_email_body']);
    $confirmation_email_body = filter_var($confirmation,FILTER_SANITIZE_STRING);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='confirmation_email_send_before'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$confirmation_email_send_before' WHERE name='confirmation_email_send_before'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('confirmation_email_send_before', '$confirmation_email_send_before')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='confirmation_email_subject'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$confirmation_email_subject' WHERE name='confirmation_email_subject'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('confirmation_email_subject', '$confirmation_email_subject')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='confirmation_email_body'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$confirmation_email_body' WHERE name='confirmation_email_body'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('confirmation_email_body', '$confirmation_email_body')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //confirmation email

    $category=$_POST['category'];
    echo '<script type="text/javascript"> window.location.replace("config_crm.php?category='.$category.'"); </script>';

}
if (isset($_POST['reminder'])) {
    //reminder email
    $reminder_email_subject = filter_var($_POST['reminder_email_subject'],FILTER_SANITIZE_STRING);
    $reminder_email_send_before = filter_var($_POST['reminder_email_send_before'],FILTER_SANITIZE_STRING);
    $reminder = htmlentities($_POST['reminder_email_body']);
    $reminder_email_body = filter_var($reminder,FILTER_SANITIZE_STRING);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='reminder_email_send_before'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$reminder_email_send_before' WHERE name='reminder_email_send_before'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('reminder_email_send_before', '$reminder_email_send_before')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='reminder_email_subject'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$reminder_email_subject' WHERE name='reminder_email_subject'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('reminder_email_subject', '$reminder_email_subject')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='reminder_email_body'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$reminder_email_body' WHERE name='reminder_email_body'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('reminder_email_body', '$reminder_email_body')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //reminder email

    $category=$_POST['category'];
    echo '<script type="text/javascript"> window.location.replace("config_crm.php?category='.$category.'"); </script>';

}
if (isset($_POST['survey'])) {
    //Survey Email

    $feedback_survey_email_subject = filter_var($_POST['feedback_survey_email_subject'],FILTER_SANITIZE_STRING);
    $survey = htmlentities($_POST['feedback_survey_email_body']);
    $feedback_survey_email_body = filter_var($survey,FILTER_SANITIZE_STRING);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='feedback_survey_email_subject'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$feedback_survey_email_subject' WHERE name='feedback_survey_email_subject'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('feedback_survey_email_subject', '$feedback_survey_email_subject')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='feedback_survey_email_body'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$feedback_survey_email_body' WHERE name='feedback_survey_email_body'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('feedback_survey_email_body', '$feedback_survey_email_body')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //Survey Email

    $category=$_POST['category'];
    echo '<script type="text/javascript"> window.location.replace("config_crm.php?category='.$category.'"); </script>';

}

if (isset($_POST['add_survey'])) {
    $field_set1 =	$_POST['field_set1'];
    $name = filter_var($_POST['name'],FILTER_SANITIZE_STRING);

	if($_POST['new_service'] != '') {
		$service = filter_var($_POST['new_service'],FILTER_SANITIZE_STRING);
	} else {
		$service = filter_var($_POST['service'],FILTER_SANITIZE_STRING);
	}

    $id1 = filter_var($_POST['id1'],FILTER_SANITIZE_STRING);
    $question1 =	filter_var($_POST['question1'],FILTER_SANITIZE_STRING);
    $o11 = implode('*#*',$_POST['option1']);
    $option1 = filter_var($o11,FILTER_SANITIZE_STRING);

    $field_set2 =	$_POST['field_set2'];
    $id2 = filter_var($_POST['id2'],FILTER_SANITIZE_STRING);
    $question2 =	filter_var($_POST['question2'],FILTER_SANITIZE_STRING);
    $o12 = implode('*#*',$_POST['option2']);
    $option2 = filter_var($o12,FILTER_SANITIZE_STRING);

    $field_set3 =	$_POST['field_set3'];
    $id3 = filter_var($_POST['id3'],FILTER_SANITIZE_STRING);
    $question3 =	filter_var($_POST['question3'],FILTER_SANITIZE_STRING);
    $o13 = implode('*#*',$_POST['option3']);
    $option3 = filter_var($o13,FILTER_SANITIZE_STRING);

    $field_set4 =	$_POST['field_set4'];
    $id4 = filter_var($_POST['id4'],FILTER_SANITIZE_STRING);
    $question4 =	filter_var($_POST['question4'],FILTER_SANITIZE_STRING);
    $o14 = implode('*#*',$_POST['option4']);
    $option4 = filter_var($o14,FILTER_SANITIZE_STRING);

    $field_set5 =	$_POST['field_set5'];
    $id5 = filter_var($_POST['id5'],FILTER_SANITIZE_STRING);
    $question5 =	filter_var($_POST['question5'],FILTER_SANITIZE_STRING);
    $o15 = implode('*#*',$_POST['option5']);
    $option5 = filter_var($o15,FILTER_SANITIZE_STRING);

    $referral_request = $_POST['referral_request'];
    $testimonial_request = $_POST['testimonial_request'];

    $query_insert_inventory = "INSERT INTO `crm_feedback_survey_form` (`name`, `service`, `field_set1`, `id1`, `question1`, `option1`, `field_set2`, `id2`, `question2`, `option2`, `field_set3`, `id3`, `question3`, `option3`, `field_set4`, `id4`, `question4`, `option4`, `field_set5`, `id5`, `question5`, `option5`, `referral_request`, `testimonial_request`) VALUES	('$name', '$service', '$field_set1', '$id1', '$question1', '$option1',	'$field_set2', '$id2', '$question2', '$option2', '$field_set3', '$id3', '$question3', '$option3', '$field_set4', '$id4', '$question4', '$option4', '$field_set5', '$id5', '$question5', '$option5', '$referral_request', '$testimonial_request')";
    $result_insert_inventory = mysqli_query($dbc, $query_insert_inventory);

    echo '<script type="text/javascript"> window.location.replace("config_crm.php?category=survey"); </script>';

   // mysqli_close($dbc); //Close the DB Connection
}
?>

<script type="text/javascript">
    $(document).ready(function () {
        var i;
        for(i=1;i<=5;i++) {
            $('.option_hideshow'+i).hide();
            $('#add_row_option'+i).on( 'click', function () {
                var id = this.id;
                var lastChar = id.substr(id.length - 1);
                $(".hide_show_option"+lastChar).show();
                var clone = $('.additional_option'+lastChar).clone();
                clone.find('.form-control').val('');
                clone.removeClass("additional_option"+lastChar);
                $('#add_here_new_option'+lastChar).append(clone);
                return false;
            });
        }

        $("#service").change(function() {
            if($("#service option:selected").text() == 'New Service') {
                    $( "#new_service" ).show();
            } else {
                $( "#new_service" ).hide();
            }
        });

        // Referral promo email
        $("[name=referral_promo_email_image_button]").click (function() {
            $("[name=referral_promo_email_image]").trigger ("click");
        });
        $("[name=referral_promo_email_image").change (function () {
            uplo

        // CRM reccommendations email
        $("[name=crm_recommend_image_button]").click (function() {
            $("[name=crm_recommend_image]").trigger ("click");
        });
        $("[name=crm_recommend_image").change (function () {
            uploadImage($(this), "crm_recommend");
        });adImage($(this), "referral_promo_email");
        });

        // Birthday email
        $("[name=birthday_email_image_button]").click (function() {
            $("[name=birthday_email_image]").trigger ("click");
        });
        $("[name=birthday_email_image").change (function () {
            uploadImage($(this), "birthday_email");
        });

        // Birthday promo email
        $("[name=birthday_promo_email_image_button]").click (function() {
            $("[name=birthday_promo_email_image]").trigger ("click");
        });
        $("[name=birthday_promo_email_image").change (function () {
            uploadImage($(this), "birthday_promo_email");
        });

        // Testimonial promo email
        $("[name=testimonial_promo_email_image_button]").click (function() {
            $("[name=testimonial_promo_email_image]").trigger ("click");
        });
        $("[name=testimonial_promo_email_image").change (function () {
            uploadImage($(this), "testimonial_promo_email");
        });

        // Feedback survey email
        $("[name=feedback_survey_email_image_button]").click (function() {
            $("[name=feedback_survey_email_image]").trigger ("click");
        });
        $("[name=feedback_survey_email_image").change (function () {
            uploadImage($(this), "feedback_survey_email");
        });

        // 6 month follow up email
        $("[name=month6_follow_up_image_button]").click (function() {
            $("[name=month6_follow_up_image]").trigger ("click");
        });
        $("[name=month6_follow_up_image").change (function () {
            uploadImage($(this), "month6_follow_up");
        });

        // Confirmation email
        $("[name=confirmation_email_image_button]").click (function() {
            $("[name=confirmation_email_image]").trigger ("click");
        });
        $("[name=confirmation_email_image").change (function () {
            uploadImage($(this), "confirmation_email");
        });

        // Reminder email
        $("[name=reminder_email_image_button]").click (function() {
            $("[name=reminder_email_image]").trigger ("click");
        });
        $("[name=reminder_email_image").change (function () {
            uploadImage($(this), "reminder_email");
        });
    });
    
    $(document).on('change.select2', 'select.field_set', function() { selectField(this); });

    function selectField(sel) {
        var status = sel.value;
        var typeId = sel.id;
        var arr = typeId.split('_');
        if(status == 'Dropdown' || status == 'Options' || status == 'Checkbox' || status == 'Scale') {
            $('.option_hideshow'+arr[1]).show();
        }
        if(status == 'Scale') {
            $(".option_value"+arr[1]).html('Start-End Scale<br><em>(Ex:1-5 or 1-10)</e>');
            $("#add_row_option"+arr[1]).hide();
        } else {
            $(".option_value"+arr[1]).html('Option');
        }
    }

    function surveyConfig(sel) {
        var name = sel.name;
        var value = sel.value;
		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "<?php echo WEBSITE_URL;?>/ajax_all.php?fill=survey&name="+name+"&value="+value,
			dataType: "html",   //expect html to be returned
			success: function(response){
                location.reload();
			}
		});
    }

    function uploadImage(sel, email_name) {
        var files = new FormData();
        files.append("newimage", sel[0].files[0]);

        $.ajax({
            type: "POST",
            url: "crm_ajax.php?fill=uploadimage&name=" + email_name,
            data: files,
            processData: false,
            contentType: false,
            dataType: "html",
            success: function(response) {
                switch(email_name) {
                    case "referral_promo_email":
                        $("[name=referral_promo_email_body]").append(response);
                        tinyMCE.get('referral_promo_email_body').execCommand('mceInsertContent', false, response);
                        break;

                    case "crm_recommend":
                        $("[name=crm_recommend_body]").append(response);
                        tinyMCE.get('crm_recommend_body').execCommand('mceInsertContent', false, response);
                        break;

                    case "birthday_email":
                        $("[name=birthday_email_body]").append(response);
                        tinyMCE.get('birthday_email_body').execCommand('mceInsertContent', false, response);
                        break;

                    case "birthday_promo_email":
                        $("[name=birthday_promo_email_body]").append(response);
                        tinyMCE.get('birthday_promo_email_body').execCommand('mceInsertContent', false, response);
                        break;

                    case "testimonial_promo_email":
                        $("[name=testimonial_promo_email_body]").append(response);
                        tinyMCE.get('testimonial_promo_email_body').execCommand('mceInsertContent', false, response);
                        break;

                    case "feedback_survey_email":
                        $("[name=feedback_survey_email_body]").append(response);
                        tinyMCE.get('feedback_survey_email_body').execCommand('mceInsertContent', false, response);
                        break;

                    case "month6_follow_up":
                        $("[name=month6_follow_up_body]").append(response);
                        tinyMCE.get('month6_follow_up_body').execCommand('mceInsertContent', false, response);
                        break;

                    case "confirmation_email":
                        $("[name=confirmation_email_body]").append(response);
                        tinyMCE.get('confirmation_email_body').execCommand('mceInsertContent', false, response);
                        break;

                    case "reminder_email":
                        $("[name=reminder_email_body]").append(response);
                        tinyMCE.get('reminder_email_body').execCommand('mceInsertContent', false, response);
                        break;
                }
            }
        });
    }
</script>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">


<div class="gap-top double-gap-bottom"><a href="referral.php" class="btn config-btn">Back to Dashboard</a></div>

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
        <?php
        $category = $_GET['category'];
        $a1 = '';
        $a2 = '';
        $a3 = '';
        $a4 = '';
        $a5 = '';
        $a6 = '';
        $a7 = '';
        $a8 = '';
        if($category == 'dashboard') {
            $a1 = ' active_tab';
        }
        if($category == 'birthday') {
            $a2 = ' active_tab';
        }
        if($category == 'testimonial') {
            $a3 = ' active_tab';
        }
        if($category == 'survey') {
            $a4 = ' active_tab';
        }
        if($category == 'month') {
            $a5 = ' active_tab';
        }
        if($category == 'confirmation') {
            $a6 = ' active_tab';
        }
        if($category == 'reminder') {
            $a7 = ' active_tab';
        }
        if($category == 'referrals') {
            $a8 = ' active_tab';
        }
        if($category == 'recommend') {
            $a9 = ' active_tab';
        }
		if($category == 'newsletter') {
            $a10 = ' active_tab';
        }
		if($category == 'reminders') {
            $a11 = ' active_tab';
        }
        ?>
        <input type="hidden" name="category" value="<?php echo $category; ?>">

        <a href='config_crm.php?category=dashboard'><button type='button' class='btn brand-btn mobile-block <?php echo $a1; ?>' >Dashboard</button></a>&nbsp;&nbsp;
        <a href='config_crm.php?category=referrals'><button type='button' class='btn brand-btn mobile-block <?php echo $a8; ?>' >Referrals</button></a>&nbsp;&nbsp;
        <a href='config_crm.php?category=recommend'><button type='button' class='btn brand-btn mobile-block <?php echo $a9; ?>' >Recommendations</button></a>&nbsp;&nbsp;
        <a href='config_crm.php?category=birthday'><button type='button' class='btn brand-btn mobile-block <?php echo $a2; ?>' >Birthday & Promotion</button></a>&nbsp;&nbsp;
        <a href='config_crm.php?category=testimonial'><button type='button' class='btn brand-btn mobile-block <?php echo $a3; ?>' >Testimonial Promotion</button></a>&nbsp;&nbsp;
        <a href='config_crm.php?category=survey'><button type='button' class='btn brand-btn mobile-block <?php echo $a4; ?>' >Survey</button></a>&nbsp;&nbsp;
        <a href='config_crm.php?category=month'><button type='button' class='btn brand-btn mobile-block <?php echo $a5; ?>' >6 Month Follow Up Email</button></a>&nbsp;&nbsp;
        <a href='config_crm.php?category=confirmation'><button type='button' class='btn brand-btn mobile-block <?php echo $a6; ?>' >Confirmation Email</button></a>&nbsp;&nbsp;
        <a href='config_crm.php?category=reminder'><button type='button' class='btn brand-btn mobile-block <?php echo $a7; ?>' >Reminder Email</button></a>&nbsp;&nbsp;
		<a href='config_crm.php?category=reminder'><button type='button' class='btn brand-btn mobile-block <?php echo $a7; ?>' >Newsletter</button></a>&nbsp;&nbsp;
		<a href='config_crm.php?category=reminder'><button type='button' class='btn brand-btn mobile-block <?php echo $a7; ?>' >Reminders</button></a>&nbsp;&nbsp;
        <br><br>
        <div class="panel-group" id="accordion">

            <?php
            if($category == 'dashboard') { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_dashboard" >
                            Dashboard Setting<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_dashboard" class="panel-collapse collapse">
                    <div class="panel-body">

                       <?php
                        $value_config = ','.get_config($dbc, 'crm_dashboard').',';
                       ?>

                      <input type="checkbox" <?php if (strpos($value_config, ','."Referrals".',') !== FALSE) { echo " checked"; } ?> value="Referrals" style="height: 20px; width: 20px;" name="crm_dashboard[]">&nbsp;&nbsp;
                      <span class="triple-pad-right control-label">Referrals</span>

                      <input type="checkbox" <?php if (strpos($value_config, ','."Recommendations".',') !== FALSE) { echo " checked"; } ?> value="Recommendations" style="height: 20px; width: 20px;" name="crm_dashboard[]">&nbsp;&nbsp;
                      <span class="triple-pad-right control-label">Recommendations</span>

                      <input type="checkbox" <?php if (strpos($value_config, ','."Surveys".',') !== FALSE) { echo " checked"; } ?> value="Surveys" style="height: 20px; width: 20px;" name="crm_dashboard[]">&nbsp;&nbsp;
                      <span class="triple-pad-right control-label">Surveys</span>

                      <input type="checkbox" <?php if (strpos($value_config, ','."Testimonials".',') !== FALSE) { echo " checked"; } ?> value="Testimonials" style="height: 20px; width: 20px;" name="crm_dashboard[]">&nbsp;&nbsp;
                      <span class="triple-pad-right control-label">Testimonials</span>

                      <input type="checkbox" <?php if (strpos($value_config, ','."Birthday & Promotion".',') !== FALSE) { echo " checked"; } ?> value="Birthday & Promotion" style="height: 20px; width: 20px;" name="crm_dashboard[]">&nbsp;&nbsp;
                      <span class="triple-pad-right control-label">Birthdays & Promotions</span>

                      <input type="checkbox" <?php if (strpos($value_config, ','."6 Month Follow Up Email".',') !== FALSE) { echo " checked"; } ?> value="6 Month Follow Up Email" style="height: 20px; width: 20px;" name="crm_dashboard[]">&nbsp;&nbsp;
                      <span class="triple-pad-right control-label">6 Month Follow Up Email</span>

                      <input type="checkbox" <?php if (strpos($value_config, ','."Confirmation Email".',') !== FALSE) { echo " checked"; } ?> value="Confirmation Email" style="height: 20px; width: 20px;" name="crm_dashboard[]">&nbsp;&nbsp;
                      <span class="triple-pad-right control-label">Confirmation Email</span>

                      <input type="checkbox" <?php if (strpos($value_config, ','."Reminder Email".',') !== FALSE) { echo " checked"; } ?> value="Reminder Email" style="height: 20px; width: 20px;" name="crm_dashboard[]">&nbsp;&nbsp;
                      <span class="triple-pad-right control-label">Reminder Email</span>

					  <input type="checkbox" <?php if (strpos($value_config, ','."Newsletter".',') !== FALSE) { echo " checked"; } ?> value="Newsletter" style="height: 20px; width: 20px;" name="crm_dashboard[]">&nbsp;&nbsp;
                      <span class="triple-pad-right control-label">Newsletter</span>

					  <input type="checkbox" <?php if (strpos($value_config, ','."Reminders".',') !== FALSE) { echo " checked"; } ?> value="Reminders" style="height: 20px; width: 20px;" name="crm_dashboard[]">&nbsp;&nbsp;
                      <span class="triple-pad-right control-label">Reminders</span>

                      <br><br>

                        <div class="form-group">
                            <div class="col-sm-4 clearfix">
                                <a href="referral.php" class="btn config-btn pull-right">Back</a>
                            </div>
                            <div class="col-sm-8">
                                <button	type="submit" name="dashboard"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <?php } ?>

            <?php
            $value_config = ','.get_config($dbc, 'crm_dashboard').',';
            ?>
            <?php if($category == 'referrals') { ?>
            <?php if (strpos($value_config, ','."Referrals".',') !== FALSE) { ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_name1" >
                            Referrals Promotions<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_name1" class="panel-collapse collapse">
                    <div class="panel-body">

                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Promotion Names:<br><em>[comma seperated]</em></label>
                        <div class="col-sm-8">
                            <input name="referral_promotions" type="text" value = "<?php echo get_config($dbc, 'referral_promotions'); ?>" class="form-control">
                        </div>
                      </div>

                        <div class="form-group">
                            <div class="col-sm-4 clearfix">
                                <a href="referral.php" class="btn config-btn pull-right">Back</a>
                            </div>
                            <div class="col-sm-8">
                                <button	type="submit" name="referral"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_name" >
                            Email for Referrals<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_name" class="panel-collapse collapse">
                    <div class="panel-body">
                       <?php
                        $referral_promo_email_body = html_entity_decode(get_config($dbc, 'referral_promo_email_body'));

                        $referral_promo_email_subject = get_config($dbc, 'referral_promo_email_subject');
                       ?>

                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Referrals Email Subject:</label>
                        <div class="col-sm-8">
                            <input name="referral_promo_email_subject" type="text" value = "<?php echo $referral_promo_email_subject; ?>" class="form-control">
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Referrals Email Body:<br>Use Below tags <br> [Referrer Name] <br>[Referral Name] <br>[Expiry Date]<br> [Promotion Name]</label>
                        <div class="col-sm-8">
                            <textarea name="referral_promo_email_body" rows="5" cols="50" class="form-control"><?php echo $referral_promo_email_body; ?></textarea>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-12">
                            <input type="button" class="pull-right" name="referral_promo_email_image_button" value="Upload Image" />
                            <input type="file" name="referral_promo_email_image" style="display: none;" />
                        </div>
                      </div>

                        <div class="form-group">
                            <div class="col-sm-4 clearfix">
                                <a href="referral.php" class="btn config-btn pull-right">Back</a>
                            </div>
                            <div class="col-sm-8">
                                <button	type="submit" name="referral"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <?php } ?>
            <?php } ?>

            <?php if($category == 'recommend') { ?>
            <?php if (strpos($value_config, ','."Recommendations".',') !== FALSE) { ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_name" >
                            Email for Recommendations<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_name" class="panel-collapse collapse">
                    <div class="panel-body">
                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Recommendations Email Sender:</label>
                        <div class="col-sm-8">
                            <input name="crm_recommend_address" type="text" value = "<?= get_config($dbc, 'crm_recommend_address') ?>" class="form-control">
                        </div>
                      </div>
					  
                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Recommendations Email Subject:</label>
                        <div class="col-sm-8">
                            <input name="crm_recommend_subject" type="text" value = "<?= get_config($dbc, 'crm_recommend_subject') ?>" class="form-control">
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Recommendations Email Body:<br>Use Below tags<br>[Customer Name]<br>[Link]</label>
                        <div class="col-sm-8">
                            <textarea name="crm_recommend_body" rows="5" cols="50" class="form-control"><?= html_entity_decode(get_config($dbc, 'crm_recommend_body')) ?></textarea>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-12">
                            <input type="button" class="pull-right" name="crm_recommend_image_button" value="Upload Image" />
                            <input type="file" name="crm_recommend_image" style="display: none;" />
                        </div>
                      </div>

                        <div class="form-group">
                            <div class="col-sm-4 clearfix">
                                <a href="recommendations.php" class="btn config-btn pull-right">Back</a>
                            </div>
                            <div class="col-sm-8">
                                <button	type="submit" name="recommend"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <?php } ?>
            <?php } ?>

            <?php if($category == 'birthday') { ?>
            <?php if (strpos($value_config, ','."Birthday & Promotion".',') !== FALSE) { ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_name1" >
                            Promotions<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_name1" class="panel-collapse collapse">
                    <div class="panel-body">

                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Promotion Names:<br><em>(separate multiple promotions using a comma with no spaces.)</em></label>
                        <div class="col-sm-8">
                            <input name="birthday_promotions" type="text" value = "<?php echo get_config($dbc, 'birthday_promotions'); ?>" class="form-control">
                        </div>
                      </div>

                        <div class="form-group">
                            <div class="col-sm-4 clearfix">
                                <a href="birthday_promo.php" class="btn config-btn pull-right">Back</a>
                            </div>
                            <div class="col-sm-8">
                                <button	type="submit" name="birthday"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_name" >
                            Email for Birthdays & Promotions<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_name" class="panel-collapse collapse">
                    <div class="panel-body">
                       <?php
                        $birthday_email_body = html_entity_decode(get_config($dbc, 'birthday_email_body'));
                        $birthday_promo_email_body = html_entity_decode(get_config($dbc, 'birthday_promo_email_body'));

                        $birthday_email_subject = get_config($dbc, 'birthday_email_subject');
                        $birthday_promo_email_subject = get_config($dbc, 'birthday_promo_email_subject');
                       ?>

                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Birthday Email Subject:</label>
                        <div class="col-sm-8">
                            <input name="birthday_email_subject" type="text" value = "<?php echo $birthday_email_subject; ?>" class="form-control">
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Birthday Email Body:<br>Use Below tags <br> [Customer Name]</label>
                        <div class="col-sm-8">
                            <textarea name="birthday_email_body" rows="5" cols="50" class="form-control"><?php echo $birthday_email_body; ?></textarea>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-12">
                            <input type="button" class="pull-right" name="birthday_email_image_button" value="Upload Image" />
                            <input type="file" name="birthday_email_image" style="display: none;" />
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Birthday & Promotion Email Subject:</label>
                        <div class="col-sm-8">
                            <input name="birthday_promo_email_subject" type="text" value = "<?php echo $birthday_promo_email_subject; ?>" class="form-control">
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Birthday & Promotion Email Body:<br>Use Below tags <br> [Customer Name] <br>[Expiry Date]<br> [Promotion Name]</label>
                        <div class="col-sm-8">
                            <textarea name="birthday_promo_email_body" rows="5" cols="50" class="form-control"><?php echo $birthday_promo_email_body; ?></textarea>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-12">
                            <input type="button" class="pull-right" name="birthday_promo_email_image_button" value="Upload Image" />
                            <input type="file" name="birthday_promo_email_image" style="display: none;" />
                        </div>
                      </div>

                        <div class="form-group">
                            <div class="col-sm-4 clearfix">
                                <a href="birthday_promo.php" class="btn config-btn pull-right">Back</a>
                            </div>
                            <div class="col-sm-8">
                                <button	type="submit" name="birthday"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <?php } ?>
            <?php } ?>

            <?php if($category == 'testimonial') { ?>
            <?php if (strpos($value_config, ','."Testimonials".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_test" >
                            Email for Testimonial Promotion<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_test" class="panel-collapse collapse">
                    <div class="panel-body">

                       <?php
                        $testimonial_promo_email_body = html_entity_decode(get_config($dbc, 'testimonial_promo_email_body'));
                        $testimonial_promo_email_subject = get_config($dbc, 'testimonial_promo_email_subject');
                       ?>

                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Email Subject:</label>
                        <div class="col-sm-8">
                            <input name="testimonial_promo_email_subject" type="text" value = "<?php echo $testimonial_promo_email_subject; ?>" class="form-control">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Email Body:<br>Use Below tags <br> [Customer Name]</label>
                        <div class="col-sm-8">
                            <textarea name="testimonial_promo_email_body" rows="5" cols="50" class="form-control"><?php echo $testimonial_promo_email_body; ?></textarea>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-12">
                            <input type="button" class="pull-right" name="testimonial_promo_email_image_button" value="Upload Image" />
                            <input type="file" name="testimonial_promo_email_image" style="display: none;" />
                        </div>
                      </div>

                        <div class="form-group">
                            <div class="col-sm-4 clearfix">
                                <a href="testimonials.php" class="btn config-btn pull-right">Back</a>
                            </div>
                            <div class="col-sm-8">
                                <button	type="submit" name="testimonial"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <?php } ?>
            <?php } ?>

            <?php if($category == 'survey') { ?>
            <?php if (strpos($value_config, ','."Surveys".',') !== FALSE) { ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_survey1" >
                            Survey List<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_survey1" class="panel-collapse collapse">
                    <div class="panel-body">

                    <?php
                    $query_check_credentials = "SELECT * FROM crm_feedback_survey_form WHERE deleted != 1 ORDER BY service, surveyid DESC";
                    $result = mysqli_query($dbc, $query_check_credentials);

                    $num_rows = mysqli_num_rows($result);
                    if($num_rows > 0) {
                        echo "<table class='table table-bordered'>";
                        echo "<tr class='hidden-xs hidden-sm'>
                        <th>ID#</th>
                        <th>Service</th>
                        <th>Name</th>
                        <th>On/Off Status</th>";
                        echo "</tr>";
                    } else {
                        echo "<h2>No Survey Found.</h2>";
                    }

                    while($row = mysqli_fetch_array($result))
                    {
                        echo "<tr>";
                        echo '<td data-title="Contact Person">'.$row['surveyid'].'</td>';
                        echo '<td data-title="Contact Person">'.$row['service'].'</td>';
                        echo '<td data-title="Contact Person">'.$row['name'].'</td>';
                        ?>

                        <td data-title="Unit Number"><input type="radio" <?php if ($row['deleted'] == 0) {
                        echo " checked"; } ?> onchange="surveyConfig(this)" name="<?php echo $row['surveyid']; ?>" value="0" style="height:20px;width:20px;">On &nbsp;&nbsp;&nbsp;
                        <input type="radio" <?php if ($row['deleted'] == 2) {
                            echo " checked"; } ?> onchange="surveyConfig(this)" name="<?php echo $row['surveyid']; ?>" value="2" style="height:20px;width:20px;">Off
                        </td>


                        <?php
                        //echo '<td><a href=\'delete_restore.php?action=delete&surveyid='.$row['surveyid'].'\' onclick="return confirm(\'Are you sure?\')">Delete</a>';

                        //echo '</td>';

                        echo "</tr>";
                    }

                    echo '</table>';
                    ?>

                        <div class="form-group">
                            <div class="col-sm-4 clearfix">
                                <a href="survey.php" class="btn config-btn pull-right">Back</a>
                            </div>
                            <div class="col-sm-8">
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_survey2" >
                            Add Survey<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_survey2" class="panel-collapse collapse">
                    <div class="panel-body">

                            <div class="form-group">
                                <label for="company_name" class="col-sm-4 control-label">Name:</label>
                                <div class="col-sm-8">
                                    <input name="name" type="text" class="form-control">
                                </div>
                            </div>

                          <div class="form-group">
                            <label for="position[]" class="col-sm-4 control-label">Service:</label>
                            <div class="col-sm-8">
                                <select data-placeholder="Select a Service..." id="service" name="service" class="chosen-select-deselect form-control" width="380">
                                  <option value=""></option>
                                  <?php
                                    $query = mysqli_query($dbc,"SELECT distinct(service) FROM crm_feedback_survey_form");
                                    while($row = mysqli_fetch_array($query)) {
                                        echo "<option value='". $row['service']."'>".$row['service'].'</option>';
                                    }
                                    echo "<option value = 'Other'>New Service</option>";
                                  ?>
                                </select>
                            </div>
                          </div>

                           <div class="form-group" id="new_service" style="display: none;">
                            <label for="travel_task" class="col-sm-4 control-label">New Service:</label>
                            <div class="col-sm-8">
                                <input name="new_service" type="text" class="form-control"/>
                            </div>
                          </div>


                            <?php for($i=1;$i<=5;$i++) { ?>
                            Question <?php echo $i;?>

                            <div class="form-group">
                                <label for="first_name" class="col-sm-4 control-label text-right">Field Set:</label>
                                <div class="col-sm-8">
                                    <select name="field_set<?php echo $i;?>" data-placeholder="Select a Field Set..." id="field_<?php echo $i; ?>" class="chosen-select-deselect form-control field_set" width="380">
                                        <option value=''></option>
                                        <option value='Textbox'>Textbox</option>
                                        <option value='Dropdown'>Dropdown</option>
                                        <option value='Datepicker'>Datepicker</option>
                                        <option value='Scale'>Scale</option>
                                        <option value='Textarea'>Text Area</option>
                                        <option value='Options'>Options</option>
                                        <option value='Checkbox'>Checkbox</option>
                                    </select>
                                </div>
                            </div>

                          <div class="form-group">
                            <label for="company_name" class="col-sm-4 control-label">ID:</label>
                            <div class="col-sm-8">
                              <input name="id<?php echo $i;?>" readonly value="field<?php echo $i;?>" type="text" class="form-control">
                            </div>
                          </div>

                          <div class="form-group">
                            <label for="company_name" class="col-sm-4 control-label">Question:</label>
                            <div class="col-sm-8">
                              <input name="question<?php echo $i;?>" type="text" class="form-control">
                            </div>
                          </div>

                          <span class="option_hideshow<?php echo $i;?>">
                          <div class="form-group additional_option<?php echo $i;?>">
                            <label for="company_name" class="col-sm-4 control-label option_value<?php echo $i;?>">Option:</label>
                            <div class="col-sm-8">
                              <input name="option<?php echo $i;?>[]" type="text" class="form-control">
                            </div>
                          </div>

                            <div id="add_here_new_option<?php echo $i;?>"></div>

                            <div class="form-group triple-gapped clearfix">
                                <div class="col-sm-offset-4 col-sm-8">
                                    <button id="add_row_option<?php echo $i;?>" class="btn brand-btn pull-left">Add Option</button>
                                </div>
                            </div>
                            </span>

                            <?php } ?>

                        <div class="form-group">
                            <label for="company_name" class="col-sm-4 control-label">Referral Request:</label>
                            <div class="col-sm-8">
                              <input type="radio" checked="checked" name="referral_request" value="Yes"> Yes &nbsp;&nbsp;
                              <input type="radio" name="referral_request" value="No"> No
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="company_name" class="col-sm-4 control-label">Testimonial Request:</label>
                            <div class="col-sm-8">
                              <input type="radio" checked="checked" name="testimonial_request" value="Yes"> Yes &nbsp;&nbsp;
                              <input type="radio" name="testimonial_request" value="No"> No
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6 clearfix">
                                <a href="survey.php" class="btn brand-btn btn-lg">Back</a>
                            </div>
                          <div class="col-sm-6">
                            <button type="submit" name="add_survey" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
                          </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_survey" >
                            Email for Survey<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_survey" class="panel-collapse collapse">
                    <div class="panel-body">

                       <?php
                        $feedback_survey_email_body = html_entity_decode(get_config($dbc, 'feedback_survey_email_body'));
                        $feedback_survey_email_subject = get_config($dbc, 'feedback_survey_email_subject');
                       ?>

                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Email Subject:</label>
                        <div class="col-sm-8">
                            <input name="feedback_survey_email_subject" type="text" value = "<?php echo $feedback_survey_email_subject; ?>" class="form-control">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Email Body:<br>Use Below tags <br> [Customer Name] <br>[Survey Link]</label>
                        <div class="col-sm-8">
                            <textarea name="feedback_survey_email_body" rows="5" cols="50" class="form-control"><?php echo $feedback_survey_email_body; ?></textarea>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-12">
                            <input type="button" class="pull-right" name="feedback_survey_email_image_button" value="Upload Image" />
                            <input type="file" name="feedback_survey_email_image" style="display: none;" />
                        </div>
                      </div>

                        <div class="form-group">
                            <div class="col-sm-4 clearfix">
                                <a href="survey.php" class="btn config-btn pull-right">Back</a>
                            </div>
                            <div class="col-sm-8">
                                <button	type="submit" name="survey"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <?php } ?>
            <?php } ?>

            <?php if($category == 'month') { ?>
            <?php if (strpos($value_config, ','."6 Month Follow Up Email".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_followup" >
                            Email for 6 Month Follow Up Email<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_followup" class="panel-collapse collapse">
                    <div class="panel-body">

                       <?php
                        $month6_follow_up_body = html_entity_decode(get_config($dbc, 'month6_follow_up_body'));
                        $month6_follow_up_subject = get_config($dbc, 'month6_follow_up_subject');
                       ?>

                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Email Subject:</label>
                        <div class="col-sm-8">
                            <input name="month6_follow_up_subject" type="text" value = "<?php echo $month6_follow_up_subject; ?>" class="form-control">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Email Body:<br>Use Below tags <br> [Customer Name] <br>[Last Appointment Date]</label>
                        <div class="col-sm-8">
                            <textarea name="month6_follow_up_body" rows="5" cols="50" class="form-control"><?php echo $month6_follow_up_body; ?></textarea>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-12">
                            <input type="button" class="pull-right" name="month6_follow_up_image_button" value="Upload Image" />
                            <input type="file" name="month6_follow_up_image" style="display: none;" />
                        </div>
                      </div>

                        <div class="form-group">
                            <div class="col-sm-4 clearfix">
                                <a href="6month_follow_up_email.php" class="btn config-btn pull-right">Back</a>
                            </div>
                            <div class="col-sm-8">
                                <button	type="submit" name="month"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <?php } ?>
            <?php } ?>

            <?php if($category == 'confirmation') { ?>
            <?php if (strpos($value_config, ','."Confirmation Email".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_confirm" >
                            Confirmation Email<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_confirm" class="panel-collapse collapse">
                    <div class="panel-body">

                       <?php
                        $confirmation_email_body = html_entity_decode(get_config($dbc, 'confirmation_email_body'));
                        $confirmation_email_subject = get_config($dbc, 'confirmation_email_subject');
                        $confirmation_email_send_before = get_config($dbc, 'confirmation_email_send_before');
                       ?>

                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Send Email Before<br><em>(e.g. 2 days, 1 month, etc.)</em>:</label>
                        <div class="col-sm-8">
                            <input name="confirmation_email_send_before" type="text" value = "<?php echo $confirmation_email_send_before; ?>" class="form-control">
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Email Subject:</label>
                        <div class="col-sm-8">
                            <input name="confirmation_email_subject" type="text" value = "<?php echo $confirmation_email_subject; ?>" class="form-control">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Email Body:<br>Use Below tags <br> [Customer Name] <br>[Appointment Date]<br>[Confirmation Link]</label>
                        <div class="col-sm-8">
                            <textarea name="confirmation_email_body" rows="5" cols="50" class="form-control"><?php echo $confirmation_email_body; ?></textarea>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-12">
                            <input type="button" class="pull-right" name="confirmation_email_image_button" value="Upload Image" />
                            <input type="file" name="confirmation_email_image" style="display: none;" />
                        </div>
                      </div>

                        <div class="form-group">
                            <div class="col-sm-4 clearfix">
                                <a href="referral.php" class="btn config-btn pull-right">Back</a>
                            </div>
                            <div class="col-sm-8">
                                <button	type="submit" name="confirmation"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <?php } ?>
            <?php } ?>

            <?php if($category == 'reminder') { ?>
            <?php if (strpos($value_config, ','."Reminder Email".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_reminder" >
                            Reminder Email<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_reminder" class="panel-collapse collapse">
                    <div class="panel-body">

                       <?php
                        $reminder_email_body = html_entity_decode(get_config($dbc, 'reminder_email_body'));
                        $reminder_email_subject = get_config($dbc, 'reminder_email_subject');
                        $reminder_email_send_before = get_config($dbc, 'reminder_email_send_before');
                       ?>

                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Send Email Before<br><em>(e.g. 2 days, 1 month, etc.)</em>:</label>
                        <div class="col-sm-8">
                            <input name="reminder_email_send_before" type="text" value = "<?php echo $reminder_email_send_before; ?>" class="form-control">
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Email Subject:</label>
                        <div class="col-sm-8">
                            <input name="reminder_email_subject" type="text" value = "<?php echo $reminder_email_subject; ?>" class="form-control">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Email Body:<br>Use Below tags <br> [Customer Name] <br>[Appointment Date]</label>
                        <div class="col-sm-8">
                            <textarea name="reminder_email_body" rows="5" cols="50" class="form-control"><?php echo $reminder_email_body; ?></textarea>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-12">
                            <input type="button" class="pull-right" name="reminder_email_image_button" value="Upload Image" />
                            <input type="file" name="reminder_email_image" style="display: none;" />
                        </div>
                      </div>

                        <div class="form-group">
                            <div class="col-sm-4 clearfix">
                                <a href="referral.php" class="btn config-btn pull-right">Back</a>
                            </div>
                            <div class="col-sm-8">
                                <button	type="submit" name="reminder"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <?php } ?>
            <?php } ?>

        </div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>