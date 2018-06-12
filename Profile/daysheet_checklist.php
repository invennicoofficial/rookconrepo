<?php
$checklistid = $checklist['checklistid'];
$reset_time = date('H:i:s', strtotime($checklist['reset_time']));
$reset_date = '';
if($checklist['checklist_type'] != 'ongoing') {
	$reset_date = '';
	if($reset_time > date('h:i:s')) {
		$reset = 'past';
	} else {
		$reset = 'last';
	}
	switch($checklist['checklist_type']) {
	case 'daily':
		$reset_date = date('Y-m-').($reset == 'past' ? date('d') : date('d') - 1).' '.$reset_time;
		break;
	case 'weekly':
		$current_day_of_week = date('w');
		if($current_day_of_week == $checklist['reset_day'] && $reset == 'past') {
			$reset_date = date('Y-m-d ').$reset_time;
		} else {
			$weekdays = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
			$reset_date = date('Y-m-d ', strtotime('Last '.$weekdays[$checklist['reset_day']])).$reset_time;
		}
		break;
	case 'monthly':
		if(date('d') == $checklist['reset_day'] && $reset == 'past') {
			$reset_date = date('Y-m-d ').$reset_time;
		} else {
			$day = date('d');
			$month = date('m');
			if($day < $checklist['reset_day']) {
				$month--;
			}
			$reset_date = date('Y-m-d ', strtotime(date("Y-$month-$day"))).$reset_time;
		}
		break;
	}
	mysqli_query($dbc, "UPDATE `checklist_name` SET `checked`=0 WHERE `time_checked` < '$reset_date' AND `checklistid` = '$checklistid' AND `deleted`=0");
}
?>
<div class="tab-container">
    <input type="file" name="attach_board_<?= $checklistid; ?>" style="display:none;" />
    <input type="hidden" name="checklistid" value="'.$checklistid.'" /><?php

    echo '<ul id="sortable'.$i.'" class="connectedChecklist">';
        echo '<li><b>'.$checklist['checklist_name'].'</b></li>';
        echo '<li class="new_task_box no-sort">
            <div class="col-sm-1"></div>
            <div class="col-sm-10"><input onChange="changeEndAme(this)" name="add_checklist" placeholder="Add new task..." id="add_new_task '.$checklistid.'" type="text" class="form-control" /></div>
            <div class="clearfix"></div>
        </li>';

        $result = mysqli_query($dbc, "SELECT * FROM checklist_name WHERE checklistid='$checklistid' AND checked = 0 AND deleted = 0 ORDER BY priority");

        $first_class = 1;
        while($row = mysqli_fetch_array( $result )) {
            echo '<li id="'.$row['checklistnameid'].'" class="ui-state-default '. ($first_class==1 ? 'ui-state-default-first' : '') .'" '.($row['flag_colour'] == '' ? '' : 'style="background-color: #'.$row['flag_colour'].';"').'>';
            $first_class=0;
                echo '<span class="">
                    <div class="col-sm-1 col-xs-2 middle-valign text-center"><input title="Complete" type="checkbox" onclick="checklistChange(this);" value="'.$row['checklistnameid'].'" name="checklistnameid[]" /></div>';
                    echo '<span class="col-sm-10 middle-valign" data-checklist="'.$row['checklistnameid'].'">';
                        echo '<span class="content-icon" title="Edit" onclick="edit_item(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-edit-icon.png" style="height:100%;" onclick="return false;"></span>';
                        echo '<span class="content-icon" title="Flag This!" onclick="flag_item(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-flag-icon.png" style="height:100%;" onclick="return false;"></span>';
                        echo '<span class="content-icon" title="Reply" onclick="send_reply(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-reply-icon.png" style="height:100%;" onclick="return false;"></span>';
                        echo '<span class="content-icon" title="Attach File" onclick="attach_file(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-attachment-icon.png" style="height:100%;" onclick="return false;"></span>';
                        echo '<span class="content-icon" title="Send Alert" onclick="send_alert(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-alert-icon.png" style="height:100%;" onclick="return false;"></span>';
                        echo '<span class="content-icon" title="Send Email" onclick="send_email(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-email-icon.png" style="height:100%;" onclick="return false;"></span>';
                        echo '<span class="content-icon" title="Schedule Reminder" onclick="send_reminder(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-reminder-icon.png" style="height:100%;" onclick="return false;"></span>';
                        echo '<span class="content-icon" title="Add Time" onclick="add_time(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-timer-icon.png" style="height:100%;" onclick="return false;"></span>';
                        echo '<span class="content-icon" title="Archive Item" onclick="archive(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-trash-icon.png" style="height:100%;" onclick="return false;"></span>';
                        echo '<input type="text" name="reply_'.$row['checklistnameid'].'" style="display:none; margin-top: 2em;" class="form-control" />';
                        echo '<input type="text" name="checklist_time_'.$row['checklistnameid'].'" style="display:none; margin-top: 2em;" class="form-control timepicker" />';
                        echo '<input type="text" name="reminder_'.$row['checklistnameid'].'" style="display:none; margin-top: 2em;" class="form-control datepicker" />';
                        echo '<input type="file" name="attach_'.$row['checklistnameid'].'" style="display:none;" class="form-control" /><br />';
                        echo '<span class="display-field"><input type="text" name="edit_'.$row['checklistnameid'].'" style="display:none;" class="form-control" value="'.explode('<p>',html_entity_decode($row['checklist']))[0].'" />';
                        echo html_entity_decode($row['checklist']).'</span>&nbsp;&nbsp;';

                        $documents = mysqli_query($dbc, "SELECT * FROM checklist_name_document WHERE checklistnameid='".$row['checklistnameid']."' AND `deleted`=0");
                        while($doc = mysqli_fetch_array($documents)) {
                            echo '<br /><a href="'.WEBSITE_URL.'/Checklist/download/'.$doc['document'].'">'.$doc['document'].' (Uploaded by '.get_staff($dbc, $doc['created_by']).' on '.$doc['created_date'].')</a>';
                            echo '</span>';
                        }
                    echo '</span>';
                echo '<div class="col-sm-1 middle-valign text-center drag_handle-container"><img class="drag_handle" src="'.WEBSITE_URL.'/img/icons/drag_handle.png" style="margin:0.25em; height:1.25em; width:1.25em;" /></div>';
            echo '</li>';
        }

        echo '<li class="new_task_box no-sort" style="border-bottom: 1px solid #ccc; margin: 0;">
            <div class="col-sm-1"></div>
            <div class="col-sm-10"><input onChange="changeEndAme(this)" name="add_checklist" placeholder="Add new task..." id="add_new_task '.$checklistid.'" type="text" class="form-control" /></div>
        </li>';

    // echo '</ul>';


    $result = mysqli_query($dbc, "SELECT * FROM checklist_name WHERE checklistid='$checklistid' AND checked = 1 AND deleted = 0 ORDER BY `time_checked`");
    if ( $result->num_rows > 0 ) {
        // echo '<div class="clearfix double-gap-top"></div>';
        echo '<li><b>Complete</b></li>';

        // echo '<h4 class="connectedChecklistTitle">Completed</h4>';
        // echo '<ul class="connectedChecklist border-bottom-none">';
            while($row = mysqli_fetch_array( $result )) {
                $info = ' : '.$row['updated_date']. ' : '.$row['updated_by'];
                echo '<li id="'.$row['checklistnameid'].'" title="Incomplete" class="ui-state-default no-sort">';
                    echo '<div class="col-sm-1 col-xs-2 middle-valign text-center"><input type="checkbox" onclick="checklistChange(this);" checked value="'.$row['checklistnameid'].'" name="checklistnameid[]"></div>';
                    echo '<div class="col-sm-11 middle-valign">'. html_entity_decode($row['checklist']) . $info;
                        $documents = mysqli_query($dbc, "SELECT * FROM checklist_name_document WHERE checklistnameid='".$row['checklistnameid']."' AND `deleted`=0");
                        while($doc = mysqli_fetch_array($documents)) {
                            echo '<br /><a href="'.WEBSITE_URL.'/Checklist/download/'.$doc['document'].'">'.$doc['document'].' (Uploaded by '.get_staff($dbc, $doc['created_by']).' on '.$doc['created_date'].')</a>';
                        }
                    echo '</div>';
                echo '</li>';
            }
    }
	echo '</ul>'; ?>
</div>