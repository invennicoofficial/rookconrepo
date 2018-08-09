<?php
include_once('../include.php');
$taskboardid = $_GET['taskboardid'];
$label = $_GET['label'];
echo '<div class="clearfix gap-top gap-left">History For Tasklists for ' . $label . '</div>';
if($label == 'Tasks') {
  $label = 'To Do';
}
$taskboard_query = mysqli_query($dbc, "SELECT `tasklistid` FROM `tasklist` WHERE `task_board`='$taskboardid' and task_milestone_timeline = '$label'");
while($tasklists = mysqli_fetch_assoc($taskboard_query)) {
$tasklistid = $tasklists['tasklistid'];
$documents = mysqli_query($dbc, "SELECT `created_by`, `created_date`, `document` FROM `task_document` WHERE `tasklistid`='$tasklistid' ORDER BY `taskdocid` DESC");
if ( $documents->num_rows > 0 ) { ?>
    <?php $heading = $row_doc['heading']; ?>
    <div class="form-group clearfix full-width"> For Tasklist <?php echo $tasklistid; ?>
        <div class="updates_<?= $row['tasklistid'] ?> col-sm-12"><?php
            while ( $row_doc=mysqli_fetch_assoc($documents) ) { ?>
                <div class="note_block row">
                    <div class="col-xs-1"><?= profile_id($dbc, $row_doc['created_by']); ?></div>
                    <div class="col-xs-11" style="<?= $style_strikethrough ?>">
                        <div><a href="../Tasks/download/<?= $row_doc['document'] ?>"><?= $row_doc['document'] ?></a></div>
                        <div><em>Added by <?= get_contact($dbc, $row_doc['created_by']); ?> on <?= $row_doc['created_date']; ?></em></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <hr class="margin-vertical" /><?php
            } ?>
        </div>
        <div class="clearfix"></div>
    </div><?php
}
$comments = mysqli_query($dbc, "SELECT `created_by`, `created_date`, `comment` FROM `task_comments` WHERE `tasklistid`='$tasklistid' AND `deleted`=0 ORDER BY `taskcommid` DESC");
if ( $comments->num_rows > 0 ) { ?>
    <div class="form-group clearfix full-width"> For Tasklist <?php echo $tasklistid; ?>
        <div class="updates_<?= $row['tasklistid'] ?> col-sm-12"><?php
            while ( $row_comment=mysqli_fetch_assoc($comments) ) { ?>
                <div class="note_block row">
                    <div class="col-xs-1"><?= profile_id($dbc, $row_comment['created_by']); ?></div>
                    <div class="col-xs-11" style="<?= $style_strikethrough ?>">
                        <div><?= html_entity_decode($row_comment['comment']); ?></div>
                        <div><em>Added by <?= get_contact($dbc, $row_comment['created_by']); ?> on <?= $row_comment['created_date']; ?></em></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <hr class="margin-vertical" /><?php
            } ?>
        </div>
        <div class="clearfix"></div>
    </div><?php
}
}
?>
