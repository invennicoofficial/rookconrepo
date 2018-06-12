<?php
    //Survey
    $follow_up_assessment_email = $_POST['follow_up_assessment_email'];

    $email_body = "Dear Valued Client,<br><br><br>";

    if($follow_up_assessment_email == 'Physiotherapy') {
        $email_body .= "The center of our attention is to consistently provide quality therapy, and our reputation is built on taking extra care of your health and well-being.<br><br>
        Your customized treatment plan was setup to optimize your results and minimize the chance of reinjury.  We truly care about our clients and when they fail to finish their program we become concerned.  We haven't seen you in the clinic for over a week and as experience has taught us, although you may be pain free and feeling better, failing to totally complete your rehab program will not give you the ideal long term results.<br><br>
        We hope you will make your healing a priority, and work with us to complete your program.  If you are pain free and feel you are ready for graduation, give us a call so we can assess and make sure you are ready to return to activity. This will prevent re-injury and set you up for success.  We will facilitate your total recovery and allow you to get back to the activities you love without fear of relapse. <br><br>";
    }

    if($follow_up_assessment_email == 'Massage') {
        $email_body .= "The center of our attention is to consistently provide quality therapy, and our reputation is built on our ability to not only meet, but exceed your expectations. <br><br>
        Your customized massage plan was setup to optimize your results and minimize pain and discomfort.  We truly care about our patients and we hope you are feeling your best. We make your healing a priority, and we hope you’ll continue with us in the future. <br><br>
        We will facilitate your total recovery and allow you to get back to the activities you love without fear of injury.";
    }

    $email_body .= "We hope to hear from you soon.<br><br>
    Please e-mail or call us at 403-295-8590.<br><br>
    Warmest regards,<br>
    Your Nose Creek Sport Physical Therapy<br>
    and Massage Therapy Team
    ";

    //Mail
    $email = get_email($dbc, $get_invoice['patientid']);
    $subject = 'Follow Up Email From Nose Creek Sport Physical Therapy';

    send_email('', $email, '', '', $subject, $email_body, '');
    //Mail
    //Survey