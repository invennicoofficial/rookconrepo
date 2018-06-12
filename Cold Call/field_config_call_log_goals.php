<?php
/*
Dashboard
*/
include ('../include.php');
error_reporting(1);
checkAuthorised('calllog');

if (isset($_POST['submit'])) {
    $calllog_goal_heading = filter_var($_POST['heading'],FILTER_SANITIZE_STRING);
    $calllog_goal_desc = filter_var($_POST['description'],FILTER_SANITIZE_STRING);
    $calllog_goal_setter = filter_var($_POST['goal_setter'],FILTER_SANITIZE_STRING);
    $calllog_goal_set = filter_var($_POST['goal_set'],FILTER_SANITIZE_STRING);
    $calllog_goal_timeline = filter_var($_POST['goal_timeline'],FILTER_SANITIZE_STRING);
    $calllog_goal_startdate = filter_var($_POST['goal_startdate'],FILTER_SANITIZE_STRING);
    $calllog_goal_enddate = filter_var($_POST['goal_enddate'],FILTER_SANITIZE_STRING);
    $calllog_goal_reminder = filter_var($_POST['goal_reminder'],FILTER_SANITIZE_STRING);
    $calllog_goal_new_lead = filter_var($_POST['new_lead'],FILTER_SANITIZE_STRING);
    $calllog_goal_move_lead = filter_var($_POST['move_lead'],FILTER_SANITIZE_STRING);
    $calllog_goal_missed_call = filter_var($_POST['missed_call'],FILTER_SANITIZE_STRING);
    $calllog_goal_passed_due = filter_var($_POST['passed_due'],FILTER_SANITIZE_STRING);
    $calllog_goal_abn_lead = filter_var($_POST['abandoned_lead'],FILTER_SANITIZE_STRING);

    //Document
    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }
    for($i = 0; $i < count($_FILES['upload_document']['name']); $i++) {
        $document = $_FILES["upload_document"]["name"][$i];

        move_uploaded_file($_FILES["upload_document"]["tmp_name"][$i], "download/".$_FILES["upload_document"]["name"][$i]) ;

        if($document != '') {
            $documents[] = htmlspecialchars($document, ENT_QUOTES);
        }
    }

    $insertDoc = implode(",", $documents);

    $query_insert_config = "INSERT INTO `calllog_goals` (`goal_setter`, `goal_set`, `goal_timeline`, `goal_startdate`,`goal_enddate`,`goal_reminder`,`heading`,`new_lead`,`move_lead`,`missed_call`,`passed_due`,`abandoned_lead`,`description`,`doc_upload`)
    VALUES ('$calllog_goal_setter','$calllog_goal_set','$calllog_goal_timeline','$calllog_goal_startdate', '$calllog_goal_enddate', '$calllog_goal_reminder', '$calllog_goal_heading', '$calllog_goal_new_lead', '$calllog_goal_move_lead', '$calllog_goal_missed_call'
    , '$calllog_goal_passed_due', '$calllog_goal_abn_lead', '$calllog_goal_desc', '$document')";

    $result_insert_config = mysqli_query($dbc, $query_insert_config);
	$goal_id = mysqli_insert_id($dbc);
	
	if($calllog_goal_reminder != '' && $calllog_goal_reminder != '0000-00-00') {
		$body = filter_var(htmlentities('This is a reminder of a goal that has been set for you.<br />Click <a href="'.WEBSITE_URL.'/Cold Call/field_config_call_log_goals.php?status='.$calllog_goal_timeline.'&calllog_goal='.$goal_id.'">here</a> to review the goal.'), FILTER_SANITIZE_STRING);
        mysqli_query($dbc, "UPDATE `reminders` SET `done` = 1 WHERE `contactid` = '$calllog_goal_set' AND `src_table` = 'calllog_goals' AND `src_tableid` = '$goal_id'");
		$reminder_result = mysqli_query($dbc, "INSERT INTO `reminders` (`contactid`, `reminder_date`, `reminder_type`, `subject`, `body`, `src_table`, `src_tableid`)
			VALUES ('$calllog_goal_set', '$calllog_goal_reminder', 'Goal Reminder', 'Reminder of Goal', '$body', 'calllog_goals', '$goal_id')");
		$body = filter_var(htmlentities('This is a reminder of a goal that you have set.<br />Click <a href="'.WEBSITE_URL.'/Cold Call/field_config_call_log_goals.php?status='.$calllog_goal_timeline.'&calllog_goal='.$goal_id.'">here</a> to review the goal.'), FILTER_SANITIZE_STRING);
        mysqli_query($dbc, "UPDATE `reminders` SET `done` = 1 WHERE `contactid` = '$calllog_goal_setter' AND `src_table` = 'calllog_goals' AND `src_tableid` = '$goal_id'");
		$reminder_result = mysqli_query($dbc, "INSERT INTO `reminders` (`contactid`, `reminder_date`, `reminder_type`, `subject`, `body`, `src_table`, `src_tableid`)
			VALUES ('$calllog_goal_setter', '$calllog_goal_reminder', 'Goal Reminder', 'Reminder of Goal', '$body', 'calllog_goals', '$goal_id')");
	}
    echo '<script type="text/javascript"> window.location.replace("field_config_call_log_goals.php"); </script>';

}
?>
<script>
$(document).ready(function(){
    $("#selectall").change(function(){
      $(".all_check").prop('checked', $(this).prop("checked"));
    });
});
</script>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<div class="pad-left gap-top"><a href="call_log.php?maintype=goals" class="btn config-btn">Back to Dashboard</a>
<br><br>
</div>
<!--<a href="#" class="btn config-btn" onclick="history.go(-1);return false;">Back</a>-->
<br><br>
<?php
if($_GET['calllog_goal'] != '') {
    $query_check_credentials = "SELECT * FROM calllog_goals WHERE calllog_goals_id = " . $_GET['calllog_goal'];
    $result = mysqli_fetch_assoc(mysqli_query($dbc, $query_check_credentials));
    $goal_setter = $result['goal_setter'];
    $goal_set = $result['goal_set'];
    $goal_timeline = $result['goal_timeline'];
    $goal_startdate = $result['goal_startdate'];
    $goal_enddate = $result['goal_enddate'];
    $goal_reminder = $result['goal_reminder'];
    $heading = $result['heading'];
    $new_lead = $result['new_lead'];
    $move_lead = $result['move_lead'];
    $missed_call = $result['missed_call'];
    $passed_due = $result['passed_due'];
    $abandoned_lead = $result['abandoned_lead'];
    $description = $result['description'];
}
else {
    $goal_setter = '';
    $goal_set = '';
    $goal_timeline = '';
    $goal_startdate = '';
    $goal_enddate = '';
    $goal_reminder = '';
    $heading = '';
    $new_lead = '';
    $move_lead = '';
    $missed_call = '';
    $passed_due = '';
    $abandoned_lead = '';
    $description = '';
}
?>
<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<div class="panel-group" id="accordion2">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_accordionna" >
                    Goals<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>
        <div id="collapse_accordionna" class="panel-collapse">
            <div class="panel-body">
                <div class="form-group">
                    <label for="heading" class="col-sm-4 control-label">Goal Setter:</label>
                    <div class="col-sm-8">
                      <select data-placeholder="Choose a Client..." id="goal_setter" name="goal_setter" class="chosen-select-deselect form-control1" width="380">
                          <option value=""></option>
                          <?php
                            $setterid = $goal_setter;
                            $querycontacts = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE `deleted`=0 AND `status`=1 AND (`first_name` != '' || `last_name` != '')"),MYSQLI_ASSOC));
							foreach($querycontacts as $queryid) {
								echo "<option ".($setterid == $queryid ? 'selected' : '')." value='".$queryid."'>".get_contact($dbc, $queryid).'</option>';
                            }
                          ?>
                        </select>
                    </div>
                    <label for="heading" class="col-sm-4 control-label">Goal Set For:</label>
                    <div class="col-sm-8">
                      <select data-placeholder="Choose a Client..." id="goal_set" name="goal_set" class="chosen-select-deselect form-control1" width="380">
                          <option value=""></option>
                          <?php
                            $setid = $goal_set;
							foreach($querycontacts as $queryid) {
								echo "<option ".($setid == $queryid ? 'selected' : '')." value='".$queryid."'>".get_contact($dbc, $queryid).'</option>';
                            }
                          ?>
                        </select>
                    </div>
                    <label for="heading" class="col-sm-4 control-label">Goal Timeline:</label>
                    <div class="col-sm-8">
                      <select data-placeholder="Choose a Client..." id="goal_timeline" name="goal_timeline" class="chosen-select-deselect form-control1" width="380">
                          <option value=""></option>
                          <?php
                            $goalsTime = array('Daily','Weekly','Bi-Monthly','Monthly','Quarterly','Semi Annually','Yearly');
                            foreach($goalsTime as $goal) {
                                if ($goal_timeline == $goal) {
                                    $selected = 'selected="selected"';
                                } else {
                                    $selected = '';
                                }
                                echo "<option ".$selected." value='".$goal."'>".$goal.'</option>';
                            }
                          ?>
                        </select>
                    </div>
                    <label for="text_editor" class="col-sm-4 control-label">Goal Start Date:</label>
                        <div class="col-sm-8">
                            <input name="goal_startdate" value="<?php echo $goal_reminder; ?>" type="text" class="datepicker">
                        </div>
                    <label for="text_editor" class="col-sm-4 control-label">Goal End Date:</label>
                        <div class="col-sm-8">
                            <input name="goal_enddate" value="<?php echo $goal_enddate; ?>" type="text" class="datepicker">
                        </div>
                    <label for="text_editor" class="col-sm-4 control-label">Reminder/Follow Up:<br /><em>Setting this date will schedule an email to be sent to the Goal Setter and the person for whom the Goal was set on that date.</em></label>
                        <div class="col-sm-8">
                            <input name="goal_reminder" value="<?php echo $goal_startdate; ?>" type="text" class="datepicker">
                        </div>
					<div class="clearfix"></div>
                    <label for="text_editor" class="col-sm-4 control-label">Heading:</label>
                        <div class="col-sm-8">
                            <input name="heading" type="text" value="<?php echo $heading ?>" <?php echo $disabled; ?> class="form-control">
                        </div>
                    <label for="text_editor" class="col-sm-4 control-label"># of New Leads Entered Into Software:</label>
                        <div class="col-sm-8">
                            <select data-placeholder="Choose a Lead..." id="new_lead" name="new_lead" class="chosen-select-deselect form-control1" width="380">
                              <option value=""></option>
                              <?php
                                for($i=0;$i < 100; $i++) {
                                    if ($new_lead == $i) {
                                        $selected = 'selected="selected"';
                                    } else {
                                        $selected = '';
                                    }
                                    echo "<option ".$selected." value='".$i."'>".$i.'</option>';
                                }
                              ?>
                            </select>
                        </div>
                    <label for="text_editor" class="col-sm-4 control-label"># of Leads Moved to Sales Pipeline:</label>
                        <div class="col-sm-8">
                            <select data-placeholder="Choose a Lead..." id="move_lead" name="move_lead" class="chosen-select-deselect form-control1" width="380">
                              <option value=""></option>
                              <?php
                                for($i=0;$i < 100; $i++) {
                                    if ($move_lead == $i) {
                                        $selected = 'selected="selected"';
                                    } else {
                                        $selected = '';
                                    }
                                    echo "<option ".$selected." value='".$i."'>".$i.'</option>';
                                }
                              ?>
                            </select>
                        </div>
                    <label for="text_editor" class="col-sm-4 control-label">Maximum # of Missed Calls:</label>
                        <div class="col-sm-8">
                            <select data-placeholder="Choose a Lead..." id="missed_call" name="missed_call" class="chosen-select-deselect form-control1" width="380">
                              <option value=""></option>
                              <?php
                                for($i=0;$i < 100; $i++) {
                                    if ($missed_call == $i) {
                                        $selected = 'selected="selected"';
                                    } else {
                                        $selected = '';
                                    }
                                    echo "<option ".$selected." value='".$i."'>".$i.'</option>';
                                }
                              ?>
                            </select>
                        </div>
                    <label for="text_editor" class="col-sm-4 control-label">Maximum # of Past Due Calls:</label>
                        <div class="col-sm-8">
                            <select data-placeholder="Choose a Lead..." id="passed_due" name="passed_due" class="chosen-select-deselect form-control1" width="380">
                              <option value=""></option>
                              <?php
                                for($i=0;$i < 100; $i++) {
                                    if ($passed_due == $i) {
                                        $selected = 'selected="selected"';
                                    } else {
                                        $selected = '';
                                    }
                                    echo "<option ".$selected." value='".$i."'>".$i.'</option>';
                                }
                              ?>
                            </select>
                        </div>
                    <label for="text_editor" class="col-sm-4 control-label">Maximum # of Abandoned Leads:</label>
                        <div class="col-sm-8">
                            <select data-placeholder="Choose a New Lead..." id="abandoned_lead" name="abandoned_lead" class="chosen-select-deselect form-control1" width="380">
                              <option value=""></option>
                              <?php
                                for($i=0;$i < 100; $i++) {
                                    if ($abandoned_lead == $i) {
                                        $selected = 'selected="selected"';
                                    } else {
                                        $selected = '';
                                    }
                                    echo "<option ".$selected." value='".$i."'>".$i.'</option>';
                                }
                              ?>
                            </select>
                        </div>

                    <label for="text_editor" class="col-sm-4 control-label">Description:</label>
                    <div class="col-sm-8">
                      <textarea name="description" <?php echo $disabled; ?> rows="3" cols="50" class="form-control"><?php echo $description; ?></textarea>
                    </div>
                    <label for="additional_note" class="col-sm-4 control-label">Upload Document(s):
                            <span class="popover-examples list-inline">&nbsp;
                            <a href="#job_file" data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas"><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
                            </span>
                    </label>
                    <div class="col-sm-8">
                    <?php if($_GET['calllog_goal'] == ''): ?>
                            <div class="enter_cost additional_doc clearfix">
                                <div class="clearfix"></div>

                                <div class="form-group clearfix">
                                    <div class="col-sm-5">
                                        <input name="upload_document[]" multiple type="file" data-filename-placement="inside" class="form-control" />
                                    </div>
                                </div>

                            </div>

                            <div id="add_here_new_doc"></div>

                            <div class="form-group triple-gapped clearfix">
                                <div class="col-sm-offset-4 col-sm-8">
                                    <button id="add_row_doc" class="btn brand-btn pull-left">Add Another Document</button>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php $uploadedDocs = explode(',',  $result['doc_upload']); ?>
                            <div class="enter_cost additional_doc clearfix">
                                <div class="clearfix"></div>
                                <div class="form-group clearfix">
                                    <div class="col-sm-5">
                                        <?php foreach($uploadedDocs as $uploadedDoc): ?>
                                        <a href='<?php echo WEBSITE_URL . '/Cold Call/Download/'.$uploadedDoc ?>'><?php echo $uploadedDoc; ?></a><br>
                                    <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-4 clearfix">
        <a href="call_log.php?maintype=goals&status=<?php echo $_GET['status'] ?>" class="btn config-btn pull-right">Back</a>
		<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
    </div>
    <?php if($_GET['calllog_goal'] == ''): ?>
        <div class="col-sm-8">
            <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
        </div>
    <?php endif; ?>
</div>

</form>
</div>
</div>

<script type="text/javascript">
$('#add_row_doc').on( 'click', function () {
    var clone = $('.additional_doc').clone();
    clone.find('.form-control').val('');
    clone.removeClass("additional_doc");
    $('#add_here_new_doc').append(clone);
    return false;
});
</script>
<?php include ('../footer.php'); ?>
