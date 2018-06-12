<?php
/*
NEW PATIENT HISTORY FORM
*/
include ('../include.php');
checkAuthorised('crm');
error_reporting(0);

if (isset($_POST['submit'])) {
  if (empty($_POST['surveyresultid'])) {
    $surveyid = filter_var($_POST['surveyid'], FILTER_SANITIZE_STRING);
    $patientid = filter_var($_POST['patientid'], FILTER_SANITIZE_STRING);
    $therapistid = filter_var($_POST['therapistid'], FILTER_SANITIZE_STRING);
    $answer1 = filter_var($_POST['field1'], FILTER_SANITIZE_STRING);
    $answer2 = filter_var($_POST['field2'], FILTER_SANITIZE_STRING);
    $answer3 = filter_var($_POST['field3'], FILTER_SANITIZE_STRING);
    $answer4 = filter_var($_POST['field4'], FILTER_SANITIZE_STRING);
    $answer5 = filter_var($_POST['field5'], FILTER_SANITIZE_STRING);
    $referral_request = filter_var($_POST['referral_request'], FILTER_SANITIZE_STRING);
    $testimonial_request = filter_var($_POST['testimonial_request'], FILTER_SANITIZE_STRING);
    $public_permission = filter_var($_POST['public_permission'], FILTER_SANITIZE_STRING);
    $send_date = filter_var($_POST['send_date'], FILTER_SANITIZE_STRING);
    $fill_date = filter_var($_POST['fill_date'], FILTER_SANITIZE_STRING);

    $query_insert_testimonial = "INSERT INTO `crm_feedback_survey_result` (`surveyid`, `patientid`, `therapistid`, `answer1`, `answer2`, `answer3`, `answer4`, `answer5`, `referral_request`, `testimonial_request`, `public_permission`, `send_date`, `fill_date`, `testimonial_promo`) VALUES ('$surveyid', '$patientid', '$therapistid', '$answer1', '$answer2', '$answer3', '$answer4', '$answer5', '$referral_request', '$testimonial_request', '$public_permission', '$send_date', '$fill_date', '')";
    $result_insert_testimonial = mysqli_query($dbc, $query_insert_testimonial);
    $surveyresultid = mysqli_insert_id($dbc);

    echo '<script type="text/javascript"> window.location.replace("testimonials.php"); </script>';
  }
}

if (isset($_GET['surveyid'])) {
  $surveyid = $_GET['surveyid'];

  $query_survey = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM crm_feedback_survey_form WHERE surveyid = '$surveyid' AND deleted = 0"));
    
  $surveyname = $query_survey['name'];
  $service = $query_survey['service'];

  $field_set1 = $query_survey['field_set1'];
  $id1 = $query_survey['id1'];
  $question1 = $query_survey['question1'];
  $option1 = $query_survey['option1'];

  $field_set2 = $query_survey['field_set2'];
  $id2 = $query_survey['id2'];
  $question2 = $query_survey['question2'];
  $option2 = $query_survey['option2'];

  $field_set3 = $query_survey['field_set3'];
  $id3 = $query_survey['id3'];
  $question3 = $query_survey['question3'];
  $option3 = $query_survey['option3'];

  $field_set4 = $query_survey['field_set4'];
  $id4 = $query_survey['id4'];
  $question4 = $query_survey['question4'];
  $option4 = $query_survey['option4'];

  $field_set5 = $query_survey['field_set5'];
  $id5 = $query_survey['id5'];
  $question5 = $query_survey['question5'];
  $option5 = $query_survey['option5'];
}
if (isset($_GET['patientid'])) {
  $patientid = $_GET['patientid'];
}
if (isset($_GET['therapistid'])) {
  $therapistid = $_GET['therapistid'];
}


?>
<script type="text/javascript">
$(document).ready(function() {

});
$(document).on('change', 'select[name="surveyid"]', function() { loadSurvey(this); });

function loadSurvey(sel) {
  var surveyid = $(sel).val();
  var patientid = $("#patientid").val();
  var therapistid = $("#therapistid").val();
  window.location.href = window.location.href + "?surveyid=" + surveyid + "&patientid=" + patientid + "&therapistid=" + therapistid;
}

function showValue(scale) {
    var newValue = scale.value;
    var typeId = scale.id;
    $("#scale_"+typeId).html(newValue);
}

</script>
</head>

<body>
<?php include_once ('../navigation.php');
?>
<div class="container">
  <div class="row">

        <h1 class="triple-pad-bottom">Testimonial</h1>

		<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

            <div class="form-group">
              <label for="ship_zip" class="col-sm-4 control-label">Patient:</label>
              <div class="col-sm-8">
                <select data-placeholder="Select Patient..." name="patientid" id="patientid" class="chosen-select-deselect form-control" width="380">
                  <option value=''></option>
                  <?php $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT contactid, first_name, last_name FROM contacts WHERE category='Patient' AND deleted = 0 AND status = 1"),MYSQLI_ASSOC));
                    foreach($query as $rowid) {
                      $selected = "";
                      if ($rowid == $patientid) {
                        $selected = "selected";
                      }
                      echo "<option ".$selected." value='$rowid'>".get_contact($dbc, $rowid)."</option>";
                    } ?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="ship_zip" class="col-sm-4 control-label">Staff:</label>
              <div class="col-sm-8">
                <select data-placeholder="Select Staff..." name="therapistid" id="therapistid" class="chosen-select-deselect form-control" width="380">
                  <option value=''></option>
                    <?php foreach(sort_contacts_query(mysqli_query($dbc, "SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted = 0 AND status = 1")) as $rowid) {
                      echo "<option ".($rowid['contactid'] == $therapistid ? 'selected' : '')." value='".$rowid['contactid']."'>".$rowid['first_name'].' '.$rowid['last_name']."</option>";
                    } ?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="ship_zip" class="col-sm-4 control-label">Survey:</label>
              <div class="col-sm-8">
                <select data-placeholder="Select Survey..." name="surveyid" id="surveyid" class="chosen-select-deselect form-control" width="380">
                  <option value=''></option>
                    <?php $query = mysqli_query($dbc, "SELECT * FROM crm_feedback_survey_form WHERE deleted = 0 ORDER BY name");
                    while ($row = mysqli_fetch_array($query)) {
                      $selected = "";
                      if ($row['surveyid'] == $surveyid) {
                        $selected = "selected";
                      }
                      echo "<option ".$selected." value='".$row['surveyid']."'>".$row['name']."</option>";
                    } ?>
                </select>
              </div>
            </div>

            <?php
              if (!empty($field_set1)) {
                echo generateQuestion($field_set1, $id1, $question1, $option1);
                  }
              if (!empty($field_set2)) {
                echo generateQuestion($field_set2, $id2, $question2, $option2);
              }
              if (!empty($field_set3)) {
                echo generateQuestion($field_set3, $id3, $question3, $option3);
              }
              if (!empty($field_set4)) {
                echo generateQuestion($field_set4, $id4, $question4, $option4);
              }
              if (!empty($field_set5)) {
                echo generateQuestion($field_set5, $id5, $question5, $option5);
              }
            ?>

            <?php if (!empty($surveyid)) { ?>
              <div class="form-group">
                  <label for="ship_zip" class="col-sm-4 control-label">Referral Request:</label>
                  <div class="col-sm-8">
                      <input type="text" name="referral_request" id="referral_request" class="form-control" />
                  </div>
              </div>

              <div class="form-group">
                  <label for="ship_zip" class="col-sm-4 control-label">Testimonial Request:</label>
                  <div class="col-sm-8">
                      <input type="text" name="testimonial_request" id="testimonial_request" class="form-control" />
                  </div>
              </div>

              <div class="form-group">
                  <label for="ship_zip" class="col-sm-4 control-label">Public Permission:</label>
                  <div class="col-sm-8">
                      <input type="text" name="public_permission" id="public_permission" class="form-control" />
                  </div>
              </div>

              <div class="form-group">
                  <label for="ship_zip" class="col-sm-4 control-label">Send Date:</label>
                  <div class="col-sm-8">
                      <input type="datepicker" name="send_date" id="send_date" class="datepicker" />
                  </div>
              </div>

              <div class="form-group">
                  <label for="ship_zip" class="col-sm-4 control-label">Fill Date:</label>
                  <div class="col-sm-8">
                      <input type="datepicker" name="fill_date" id="fill_date" class="datepicker" />
                  </div>
              </div>
            <?php } ?>

         <div class="form-group">
            <div class="col-sm-4 clearfix">
                <a href="testimonials.php" class="btn brand-btn pull-right">Back</a>
            </div>
            <div class="col-sm-8">
                <button type="submit" name="submit" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
            </div>
        </div>

        

        </form>

    </div>
  </div>
<?php include ('../footer.php'); ?>

<?php

function generateQuestion($field_set, $id, $question, $option) {
    switch ($field_set) {
        case "Textbox":
            return generateQuestionTextbox($id, $question, $option);
            break;

        case "Dropdown":
            return generateQuestionDropdown($id, $question, $option);
            break;

        case "Datepicker":
            return generateQuestionDatepicker($id, $question, $option);
            break;

        case "Scale":
            return generateQuestionScale($id, $question, $option);
            break;

        case "Textarea":
            return generateQuestionTextarea($id, $question, $option);
            break;

        case "Options":
            return generateQuestionOptions($id, $question, $option);
            break;

        case "Checkbox":
            return generateQuestionCheckbox($id, $question, $option);
            break;
    }
}

function generateQuestionTextbox($id, $question, $option) {
    $html = '
        <div class="form-group survey-question">
            <label for="ship_zip" class="col-sm-4 control-label">'.$question.'</label>
            <div class="col-sm-8">
                <input type="text" name="'.$id.'" id="'.$id.'" class="form-control" />
            </div>
        </div>';

    return $html;
}

function generateQuestionDropdown($id, $question, $option) {
    $options = explode('*#*', $option);

    $html = '
        <div class="form-group survey-question">
            <label for="ship_zip" class="col-sm-4 control-label">'.$question.'</label>
            <div class="col-sm-8">
                <select name="'.$id.'" id="'.$id.'" class="chosen-select-deselect form-control" width="380"';
                    foreach ($options as $row) {
                        $html .= '<option value="'.$row.'">'.$row.'</option>';
                    }
    $html .= '
                </select>
            </div>
        </div>';

    return $html;
}

function generateQuestionDatepicker($id, $question, $option) {
    $html = '
        <div class="form-group survey-question">
            <label for="ship_zip" class="col-sm-4 control-label">'.$question.'</label>
            <div class="col-sm-8">
                <input type="datepicker" name="'.$id.'" id="'.$id.'" class="datepicker" />
            </div>
        </div>';
    return $html;
}

function generateQuestionScale($id, $question, $option) {
    $options = explode('-', $option);
    $min = $options[0];
    $max = $options[1];

    $html = '
        <div class="form-group survey-question">
            <label for="ship_zip" class="col-sm-4 control-label">'.$question.'</label>
            <div class="col-sm-8">
                <input type="range" list="'.$id.'" min="'.$min.'" max="'.$max.'" step="1" name="'.$id.'" id="'.$id.'" onchange="showValue(this)" />
                <span id="scale_'.$id.'"></span>
            </div>
        </div>';

    return $html;
}

function generateQuestionTextarea($id, $question, $option) {
    $html = '
        <div class="form-group survey-question">
            <label for="ship_zip" class="col-sm-4 control-label">'.$question.'</label>
            <div class="col-sm-8">
                <textarea name="'.$id.'" id="'.$id.'" rows="5" cols="50" class="form-control"></textarea>
            </div>
        </div>';

    return $html;
}

function generateQuestionOptions($id, $question, $option) {
    $options = explode('*#*', $option);

    $min = $options[0];
    $max = $options[1];

    $html = '
        <div class="form-group survey-question">
            <label for="ship_zip" class="col-sm-4 control-label">'.$question.'</label>
            <div class="col-sm-8">';
                foreach ($options as $row) {
                    $html .= '<input type="radio" name="'.$id.'" value="'.$row.'" class="form">'.$row.'&nbsp;&nbsp;';
                }
    $html .= '
            </div>
        </div>';

    return $html;
}

function generateQuestionCheckbox($id, $question, $option) {
    $options = explode('*#*', $option);

    $html = '
        <div class="form-group survey-question">
            <label for="ship_zip" class="col-sm-4 control-label">'.$question.'</label>
            <div class="col-sm-8">';
                foreach ($options as $row) {
                    $html .= '<input type="checkbox" name="'.$id.'" value="'.$row.'">'.$row.'&nbsp;&nbsp;';
                }
    $html .= '
            </div>
        </div>';

    return $html;
}
?>