<?php
/*
See Patient's Feedback result for Survery we sent.
*/
$guest_access = true;
error_reporting(0);
include ('../include.php');
checkAuthorised('crm');

if (isset($_POST['survey'])) {
    $surveyresultid = $_POST['surveyresultid'];
    $surveyid = $_POST['surveyid'];
    $therapistid = $_POST['therapistid'];
    $patientid = $_POST['patientid'];

    $tr = htmlentities($_POST['testimonial_request']);
    $testimonial_request = filter_var($tr,FILTER_SANITIZE_STRING);

    $today_date = filter_var($_POST['today_date'],FILTER_SANITIZE_STRING);
    $public_permission = $_POST['public_permission'];

    $survey = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM crm_feedback_survey_form WHERE surveyid='$surveyid'"));
    if($survey['field_set1'] == 'Checkbox') {
        $a1 = implode('*#*',$_POST['field1']);
        $answer1 = filter_var($a1,FILTER_SANITIZE_STRING);
    } else if($survey['field_set1'] == 'Textarea') {
        $f1 = htmlentities($_POST['field1']);
        $answer1 = filter_var($f1,FILTER_SANITIZE_STRING);
    } else {
        $answer1 = filter_var($_POST['field1'],FILTER_SANITIZE_STRING);
    }

    if($survey['field_set2'] == 'Checkbox') {
        $a2 = implode('*#*',$_POST['field2']);
        $answer2 = filter_var($a2,FILTER_SANITIZE_STRING);
    } else if($survey['field_set2'] == 'Textarea') {
        $f2 = htmlentities($_POST['field2']);
        $answer2 = filter_var($f2,FILTER_SANITIZE_STRING);
    } else {
        $answer2 = filter_var($_POST['field2'],FILTER_SANITIZE_STRING);
    }

    if($survey['field_set3'] == 'Checkbox') {
        $a3 = implode('*#*',$_POST['field3']);
        $answer3 = filter_var($a3,FILTER_SANITIZE_STRING);
    } else if($survey['field_set3'] == 'Textarea') {
        $f3 = htmlentities($_POST['field3']);
        $answer3 = filter_var($f3,FILTER_SANITIZE_STRING);
    } else {
        $answer3 = filter_var($_POST['field3'],FILTER_SANITIZE_STRING);
    }

    if($survey['field_set4'] == 'Checkbox') {
        $a4 = implode('*#*',$_POST['field4']);
        $answer4 = filter_var($a4,FILTER_SANITIZE_STRING);
    } else if($survey['field_set4'] == 'Textarea') {
        $f4 = htmlentities($_POST['field4']);
        $answer4 = filter_var($f4,FILTER_SANITIZE_STRING);
    } else {
        $answer4 = filter_var($_POST['field4'],FILTER_SANITIZE_STRING);
    }

    if($survey['field_set5'] == 'Checkbox') {
        $a5 = implode('*#*',$_POST['field5']);
        $answer5 = filter_var($a5,FILTER_SANITIZE_STRING);
    } else if($survey['field_set5'] == 'Textarea') {
        $f5 = htmlentities($_POST['field5']);
        $answer5 = filter_var($f5,FILTER_SANITIZE_STRING);
    } else {
        $answer5 = filter_var($_POST['field5'],FILTER_SANITIZE_STRING);
    }

    $referral_request = '';
    for($i=0; $i<count($_POST['recommend_name']); $i++) {
        if($_POST['recommend_name'][$i] != '') {
            $type = 'Client';
            $referrer_name = get_contact($dbc, $patientid);
            $referral_name = $_POST['recommend_name'][$i];
            $referral_email = $_POST['recommend_email'][$i];
            $query_insert_inventory = "INSERT INTO `crm_referrals` (`patientid`, `referrer_name`, `type`, `referral_name`, `referral_email`, `referral_date`) VALUES ('$patientid', '$referrer_name', '$type', '$referral_name', '$referral_email', '$today_date')";
            $result_insert_inventory = mysqli_query($dbc, $query_insert_inventory);

            $referral_request .= filter_var($_POST['recommend_name'][$i],FILTER_SANITIZE_STRING);
            $referral_request .= '*#*';
            $referral_request .= filter_var($_POST['recommend_email'][$i],FILTER_SANITIZE_STRING);
            $referral_request .= '*FFM*';
        }
    }

    $query_insert_inventory = "UPDATE `crm_feedback_survey_result` SET `answer1` = '$answer1', `answer2` = '$answer2', `answer3` = '$answer3', `answer4` = '$answer4', `answer5` = '$answer5', `referral_request` = '$referral_request', `testimonial_request` = '$testimonial_request', `fill_date` = '$today_date', `public_permission` = '$public_permission' WHERE `surveyresultid` = '$surveyresultid'";
    $result_insert_inventory = mysqli_query($dbc, $query_insert_inventory);

    echo '<script type="text/javascript"> alert("Thank you For your Feedback."); window.location.replace("feedback_survey_thankyou.php"); </script>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
    <link href="<?php echo WEBSITE_URL;?>/img/favicon.ico" rel="shortcut icon">
    <link href="<?php echo WEBSITE_URL;?>/img/apple-touch-icon.png" rel="apple-touch-icon-precomposed">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,600' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="<?php echo WEBSITE_URL;?>/css/style.css" type="text/css">
    <script type="text/javascript">
    $(document).ready(function() {
        $('#add_row').on( 'click', function () {
            var clone = $('.additional_detail').clone();
            clone.find('.form-control').val('');
            clone.removeClass("additional_detail");
            $('#add_here_new_detail').append(clone);
            return false;
        });
    });

    function showValue(scale) {
        var newValue = scale.value;
        var typeId = scale.id;
        document.getElementById("scale_"+typeId).innerHTML=newValue;
    }
    </script>
</head>
<body>

    <div class="login">
        <div class="middle">
            <form role="form" action="" method="post" class="registration_form survey_form triple-padded" style="width: 1080px; margin-top: 0px;">
                <div class="row">
                    <div class="col-lg-12 double-pad-bottom">
                        <img src="<?php echo WEBSITE_URL;?>/img/Clinic-Ace-Logo-Final-500px.png" alt="Clinic Ace" class="center-block" width="300">
                    </div>
                </div>
                <div class="row triple-pad-top">
                    <ul class="list-inline text-center">
                        <li><a href="https://www.facebook.com/" class="social-icon facebook hide-text" target="_blank">Facebook</a></li>
                        <li><a href="https://www.linkedin.com/" class="social-icon linkedin hide-text" target="_blank">LinkedIn</a></li>
                        <li><a href="https://twitter.com/" class="social-icon twitter hide-text" target="_blank">Twitter</a></li>
                        <li><a href="https://plus.google.com/" class="social-icon google hide-text" target="_blank">Google+</a></li>
                    </ul>
                </div>

                <center><h3 class="double-pad-bottom">Feedback Form</h3></center>
                <?php
                $today_date = date('Y-m-d');

                if(!empty($_GET['s'])) {
                    $sid = $_GET['s'];
                    $get_invoice = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM crm_feedback_survey_result WHERE surveyresultid='$sid'"));
                    $surveyid = $get_invoice['surveyid'];

                    echo '<input type="hidden" id="surveyresultid" name="surveyresultid" value="'.$sid.'" />';
                }

                $testimonial_request = '';
                $referral_request = '';
                $answer1 = '';
                $answer2 = '';
                $answer3 = '';
                $answer4 = '';
                $answer5 = '';

                if(!empty($_GET['surveyresultid'])) {
                    $surveyresultid = $_GET['surveyresultid'];
                    $survey_result = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM crm_feedback_survey_result WHERE surveyresultid='$surveyresultid'"));

                    $testimonial_request = $survey_result['testimonial_request'];
                    $referral_request = $survey_result['referral_request'];
                    $surveyid = $survey_result['surveyid'];
                    $today_date = $survey_result['fill_date'];
                }
                ?>
                 <?php if(empty($_GET['surveyresultid'])) { ?>
              <div class="form-group clearfix">
                <label for="first_name" class="col-sm-4 control-label text-right">Name:</label>
                <div class="col-sm-8">
                  <input name="name" readonly type="text" value="<?php echo get_contact($dbc, $get_invoice['patientid']);?>" class="form-control">
                </div>
              </div>
              <?php } ?>

              <div class="form-group clearfix completion_date">
                <label for="first_name" class="col-sm-4 control-label text-right">Date :</label>
                <div class="col-sm-8">
                    <?php if(empty($_GET['surveyresultid'])) { ?>
                    <input name="today_date" placeholder="Click for Datepicker" value="<?php echo $today_date; ?>" readonly type="text"></p>
                    <?php } else { ?>
                    <?php echo $today_date; ?>
                    <?php } ?>
                </div>
              </div>

              <?php if(empty($_GET['surveyresultid'])) { ?>
              <div class="form-group clearfix">
                <label for="first_name" class="col-sm-4 control-label text-right">Provider :</label>
                <div class="col-sm-8">
                  <input name="provider" readonly type="text" value="<?php echo get_contact($dbc, $get_invoice['therapistid']);?>" class="form-control">
                </div>
              </div>
              <?php } ?>

                <?php
                    //$surveyid = $_GET['surveyid'];
                    $survey = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM crm_feedback_survey_form WHERE surveyid='$surveyid'"));
                    $answer = '';
                    for($i=1;$i<=5;$i++) {
                        $field_set = $survey['field_set'.$i];
                        $id = $survey['id'.$i];
                        $question = $survey['question'.$i];
                        $option = $survey['option'.$i];
                        $answer = $survey_result['answer'.$i];

                        if($field_set != '') {
                            if($field_set == 'Textbox') { ?>
                              <div class="form-group clearfix">
                                <label for="first_name" class="col-sm-4 control-label text-right"><?php echo $question; ?> :</label>
                                <div class="col-sm-8">
                                  <?php if(empty($_GET['surveyresultid'])) { ?>
                                    <input name="<?php echo $id; ?>" value="<?php echo $answer;?>" type="text" class="form-control">
                                  <?php } else { ?>
                                    <?php echo $answer;?>
                                  <?php } ?>
                                </div>
                              </div>
                            <?php }
                            if($field_set == 'Dropdown') { ?>
                                <div class="form-group clearfix">
                                    <label for="company_name" class="col-sm-4 control-label text-right"><?php echo $question; ?> :</label>
                                    <div class="col-sm-8">
                                      <?php if(empty($_GET['surveyresultid'])) { ?>
                                        <select name="<?php echo $id; ?>" data-placeholder="Choose a Value..." class="chosen-select-deselect form-control" width="380">
                                        <option value=""></option>
                                        <?php
                                        $pieces = explode('*#*',$option);
                                        foreach($pieces as $element) { ?>
                                            <option <?php if ($answer == $element) { echo " selected"; } ?> value="<?php echo $element; ?>" ><?php echo $element; ?></option>
                                        <?php }
                                        ?>
                                        </select>
                                      <?php } else { ?>
                                        <?php echo $answer;?>
                                      <?php } ?>
                                    </div>
                                </div>
                            <?php }
                            if($field_set == 'Datepicker') { ?>
                                <div class="form-group clearfix">
                                    <label for="first_name" class="col-sm-4 control-label text-right"><?php echo $question; ?> :</label>
                                    <div class="col-sm-8">
                                      <?php if(empty($_GET['surveyresultid'])) { ?>
                                        <input name="<?php echo $id; ?>" value="<?php echo $answer;?>"  type="text" class="datepicker"></p>
                                      <?php } else { ?>
                                        <?php echo $answer;?>
                                      <?php } ?>
                                    </div>
                                </div>
                            <?php }
                            if($field_set == 'Scale') {
                                $scale_value = explode('-',$option);
                                ?>
                                <div class="form-group clearfix">
                                    <label for="first_name" class="col-sm-4 control-label text-right"><?php echo $question; ?> :</label>
                                    <div class="col-sm-8">
                                      <?php if(empty($_GET['surveyresultid'])) { ?>
                                        <input type="range" list="volsettings" min="<?php echo $scale_value[0]; ?>" max="<?php echo $scale_value[1]; ?>" value="<?php echo $answer;?>" step="1" name="<?php echo $id; ?>" id="<?php echo $i; ?>" onchange="showValue(this)"/>
                                        <span id="scale_<?php echo $i; ?>"><?php echo $answer;?></span>
                                      <?php } else { ?>
                                        <?php echo $answer;?>
                                      <?php } ?>
                                    </div>
                                </div>
                                <datalist id="volsettings">
                                    <option>0</option>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                    <option>6</option>
                                    <option>7</option>
                                    <option>8</option>
                                    <option>9</option>
                                    <option>10</option>
                                </datalist>
                            <?php }
                            if($field_set == 'Textarea') { ?>
                              <div class="form-group clearfix">
                                <label for="first_name[]" class="col-sm-4 control-label text-right"><?php echo $question; ?> :</label>
                                <div class="col-sm-8">
                                  <?php if(empty($_GET['surveyresultid'])) { ?>
                                    <textarea name="<?php echo $id; ?>" rows="5" cols="50" class="form-control"><?php echo $answer;?></textarea>
                                  <?php } else {
                                    echo html_entity_decode($answer);
                                    } ?>
                                </div>
                              </div>
                            <?php }
                            if($field_set == 'Options') { ?>
                                <div class="form-group clearfix">
                                    <label for="company_name" class="col-sm-4 control-label text-right"><?php echo $question;?> :</label>
                                    <div class="col-sm-8">
                                      <?php if(empty($_GET['surveyresultid'])) {
                                        $pieces = explode('*#*',$option);
                                        foreach($pieces as $element) { ?>
                                            <input type="radio" <?php if ($answer == $element) { echo " checked"; } ?> value="<?php echo $element; ?>" name="<?php echo $id; ?>" ><?php echo $element; ?>&nbsp;&nbsp;
                                        <?php }
                                        ?>
                                      <?php } else { ?>
                                        <?php echo $answer;?>
                                      <?php } ?>
                                    </div>
                                </div>
                            <?php }
                            if($field_set == 'Checkbox') { ?>
                                <div class="form-group clearfix">
                                    <label for="company_name" class="col-sm-4 control-label text-right"><?php echo $question; ?> :</label>
                                    <div class="col-sm-8">
                                      <?php if(empty($_GET['surveyresultid'])) {
                                        $pieces = explode('*#*',$option);
                                        foreach($pieces as $element) { ?>
                                            <input type="checkbox" <?php if (strpos($answer, $element) !== false) { echo  'checked="checked"'; } ?> value="<?php echo $element; ?>" name="<?php echo $id; ?>[]" ><?php echo $element; ?>&nbsp;&nbsp;
                                        <?php }
                                         } else {
                                             echo str_replace("*#*","<br>",$answer);
                                             //echo $answer;
                                         } ?>
                                    </div>
                                </div>
                            <?php }
                            ?>
                        <?php
                        }
                    }

                    if($survey['referral_request'] == 'Yes') {
                    if(!empty($_GET['surveyresultid'])) { ?>

                      <div class="form-group clearfix">
                        <label for="first_name" class="col-sm-4 control-label text-right">Referral :</label>
                        <div class="col-sm-8">
                           <?php
                            $rr = str_replace("*#*"," : ",$referral_request);
                            echo str_replace("*FFM*","<br>",$rr);
                            ?>
                        </div>
                      </div>

                    <?php } else {
                    ?>
                   <div class="form-group">
                        <label for="first_name" class="col-sm-4 control-label">Would you recommend a Friend/Collegue?</label>
                        <div class="col-sm-8">
                            <div class="form-group clearfix">
                                <label class="col-sm-5 text-center">Name</label>
                                <label class="col-sm-7 text-center">Email</label>
                            </div>

                            <div class="enter_cost additional_detail clearfix">
                                <div class="clearfix"></div>
                                <div class="form-group clearfix">
                                    <div class="col-sm-5">
                                        <input name="recommend_name[]" type="text" class="form-control office_zip" />
                                    </div>
                                    <div class="col-sm-7">
                                        <input name="recommend_email[]" type="text" class="form-control office_zip" />
                                    </div>
                                </div>

                            </div>

                            <div id="add_here_new_detail"></div>

                            <!-- <div class="form-group triple-gapped clearfix">
                                <div class="col-sm-offset-4 col-sm-8">
                                    <button id="add_row" class="btn brand-btn pull-left">Add More</button>
                                </div>
                            </div>
                            -->

                        </div>
                    </div>
                <?php } }

                if($survey['testimonial_request'] == 'Yes') { ?>
                  <div class="form-group clearfix">
                    <label for="company_name" class="col-sm-4 control-label text-right">Would you like to take a moment to leave a few remarks about your experince?:</label>
                    <div class="col-sm-8">
                      <?php if(empty($_GET['surveyresultid'])) { ?>
                        <textarea name="testimonial_request" rows="5" cols="50" class="form-control"><?php echo $testimonial_request; ?></textarea>
                      <?php } else {
                        echo html_entity_decode($testimonial_request);
                        } ?>
                    </div>
                  </div>

                <div class="form-group">
                    <label for="site_name" class="col-sm-4 control-label text-right">Public Permission:</label>
                    <div class="col-sm-8">
                      <?php if(empty($_GET['surveyresultid'])) { ?>
                          <div class="radio">
                            <label class="pad-right"><input type="radio" checked name="public_permission" value="Yes">Yes, I give my permission to post to web, social media.</label>
                            <label class="pad-right"><input type="radio" name="public_permission" value="No">No, I do not want this information to be posted publicly.</label>
                          </div>
                      <?php } else {
                        echo $survey_result['public_permission'];
                        } ?>
                    </div>
                </div>

                  <?php if(empty($_GET['surveyresultid'])) { ?>
                  <div class="form-group clearfix">
                    <label for="company_name" class="col-sm-4 control-label text-right"></label>
                    <div class="col-sm-8">
                      Click <a href="https://www.google.com" target="_blank">Here</a> to Leave a Review on Google.
                    </div>
                  </div>
                  <?php } ?>

                <?php }
                ?>
                <input type="hidden" id="surveyid" name="surveyid" value="<?php echo $surveyid ?>" />
                <input type="hidden" id="patientid" name="patientid" value="<?php echo $_GET['pid'] ?>" />
                <input type="hidden" id="therapistid" name="therapistid" value="<?php echo $_GET['tid'] ?>" />

                <?php if(empty($_GET['surveyresultid'])) { ?>
                <div class="form-group">
                        <button type="submit" name="survey" value="Submit" class="btn brand-btn pull-right">Submit</button>
                </div>
                <?php } ?>

            </form>
        </div>
    </div>
</body>
</html>