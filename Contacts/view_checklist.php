<?php
if(isset($_GET['category']) && isset($_GET['edit'])) {
    $url = WEBSITE_URL.'/'.FOLDER_URL.'/contacts_inbox.php?category='.$_GET['category'].'&edit='.$_GET['edit'];
}
?>
<script type="text/javascript" src="../Contacts/checklist.js"></script>
<script>
setTimeout(function() {

var maxWidth = Math.max.apply( null, $( '.ui-sortable' ).map( function () {
    return $( this ).outerWidth( true );
}).get() );

var maxHeight = -1;

$('.ui-sortable').each(function() {
  maxHeight = maxHeight > $(this).height() ? maxHeight : $(this).height();

});

$(function() {
  $(".connectedChecklist").width(maxWidth).height(maxHeight);
});
$( '.connectedChecklist' ).each(function () {
    this.style.setProperty( 'height', maxHeight, 'important' );
	this.style.setProperty( 'width', maxWidth, 'important' );

	<?php if($check_table_orient == 1) { ?>
		$(this).attr('style', 'height:'+maxHeight+'px !important; width:'+maxWidth+'px !important');
	<?php } else { ?>
		$(this).attr('style', 'height:'+maxHeight+'px !important;');
	<?PHP } ?>
});

}, 200);

$(document).ready(function() {
	$('.close_iframer').click(function(){
		$('.iframe_holder').hide();
		$('.hide_on_iframe').show();
	});
});
function checklist_choose_user(target, type, id, date) {
	var title	= 'Choose a User';
	$('iframe').load(function() {
		this.contentWindow.document.body.style.overflow = 'hidden';
		this.contentWindow.document.body.style.minHeight = '0';
		this.contentWindow.document.body.style.paddingBottom = '5em';
		var height = $(this).contents().find('option').length * $(this).contents().find('select').height();
		$(this).contents().find('select').data({type: type, id: id});
		this.style.height = (height + this.contentWindow.document.body.offsetHeight + 180) + 'px';
		$(this).contents().find('.btn').off();
		$(this).contents().find('.btn').click(function() {
			if($(this).closest('body').find('select').val() != '' && confirm('Are you sure you want to send the '+target+' to the selected user(s)?')) {
				if(target == 'alert') {
					$.ajax({
						method: 'POST',
						url: '../Checklist/checklist_ajax.php?fill=checklistalert',
						data: { id: id, type: type, user: $(this).closest('body').find('select').val() },
						complete: function(result) { console.log(result.responseText); }
					});
				}
				else if(target == 'email') {
					$.ajax({
						method: 'POST',
						url: '../Checklist/checklist_ajax.php?fill=checklistemail',
						data: { id: id, type: type, user: $(this).closest('body').find('select').val() },
						complete: function(result) { console.log(result.responseText); }
					});
				}
				else if(target == 'reminder') {
					$.ajax({
						method: 'POST',
						url: '../Checklist/checklist_ajax.php?fill=checklistreminder',
						data: { id: id, type: type, schedule: date, user: $(this).closest('body').find('select').val() },
						complete: function(result) { console.log(result.responseText); }
					});
				}
				$(this).closest('body').find('select').val('');
				$('.close_iframer').click();
			}
			else if($(this).closest('body').find('select').val() == '') {
				$('.close_iframer').click();
			}
		});
	});
	$('#iframe_instead_of_window').attr('src', '<?php echo WEBSITE_URL; ?>/Staff/select_staff.php?target='+target+'&multiple=true');
	$('.iframe_title').text(title);
	$('.iframe_holder').show();
	$('.hide_on_iframe').hide();
}
function checklist_send_alert(checklist) {
	checklist_id = $(checklist).parents('span').data('checklist');
	var type = 'checklist';
	if(checklist_id.toString().substring(0,5) == 'BOARD') {
		var type = 'checklist board';
		checklist_id = checklist_id.substring(5);
	}
	checklist_choose_user('alert', type, checklist_id);
}
function checklist_send_email(checklist) {
	checklist_id = $(checklist).parents('span').data('checklist');
	var type = 'checklist';
	if(checklist_id.toString().substring(0,5) == 'BOARD') {
		var type = 'checklist board';
		checklist_id = checklist_id.substring(5);
	}
	checklist_choose_user('email', type, checklist_id);
}
function checklist_send_reminder(checklist) {
	checklist_id = $(checklist).parents('span').data('checklist');
	var type = 'checklist';
	if(checklist_id.toString().substring(0,5) == 'BOARD') {
		var type = 'checklist board';
		checklist_id = checklist_id.substring(5);
	}
	var name_id = (type == 'checklist board' ? 'board_' : '');
	$('[name=reminder_'+name_id+checklist_id+']').show().focus();
	$('[name=reminder_'+name_id+checklist_id+']').keyup(function(e) {
		if(e.which == 13) {
			$(this).blur();
		}
	});
	$('[name=reminder_'+name_id+checklist_id+']').change(function() {
		$(this).hide();
		var date = $(this).val().trim();
		$(this).val('');
		if(date != '') {
			checklist_choose_user('reminder', type, checklist_id, date);
		}
	});
}
function checklist_send_reply(checklist) {
	checklist_id = $(checklist).parents('span').data('checklist');
	var type = 'checklist';
	if(checklist_id.toString().substring(0,5) == 'BOARD') {
		var type = 'checklist board';
		checklist_id = checklist_id.substring(5);
	}
	$('[name=reply_'+checklist_id+']').show().focus();
	$('[name=reply_'+checklist_id+']').keyup(function(e) {
		if(e.which == 13) {
			$(this).blur();
		}
	});
	$('[name=reply_'+checklist_id+']').blur(function() {
		$(this).hide();
		var reply = $(this).val().trim();
		$(this).val('');
		if(reply != '') {
			var today = new Date();
			var save_reply = reply + " (Reply added by <?php echo decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']); ?> at "+today.toLocaleString()+")";
			$.ajax({
				method: 'POST',
				url: '../Checklist/checklist_ajax.php?fill=checklistreply',
				data: { id: checklist_id, reply: save_reply },
				complete: function(result) { window.location.reload(); }
			})
		}
	});
}
function checklist_edit_item(checklist) {
	checklist_id = $(checklist).parents('span').data('checklist');
	var type = 'checklist';
	if(checklist_id.toString().substring(0,5) == 'BOARD') {
		var type = 'checklist board';
		checklist_id = checklist_id.substring(5);
	}
	$('[name=edit_'+checklist_id+']').show().focus();
	$('[name=edit_'+checklist_id+']').keyup(function(e) {
		if(e.which == 13) {
			$(this).blur();
		}
	});
	$('[name=edit_'+checklist_id+']').blur(function() {
		$(this).hide();
		var line = $(this).val().trim();
		$.ajax({
			method: 'POST',
			url: '../Checklist/checklist_ajax.php?fill=checklistedit',
			data: { id: checklist_id, checklist: line },
			complete: function(result) { window.location.reload(); }
		})
	});
}
function checklist_add_time(checklist) {
	checklist_id = $(checklist).parents('span').data('checklist');
	$('[name=checklist_time_'+checklist_id+']').show();
	$('[name=checklist_time_'+checklist_id+']').timepicker('option', 'onClose', function(time) {
		var time = $(this).val();
		$('[name=checklist_time_'+checklist_id+']').hide();
		$(this).val('00:00');
		if(time != '' && time != '00:00') {
			$.ajax({
				method: 'POST',
				url: '../Checklist/checklist_ajax.php?fill=checklist_quick_time',
				data: { id: checklist_id, time: time+':00' },
				complete: function(result) { console.log(result.responseText); }
			})
		}
	});
	$('[name=checklist_time_'+checklist_id+']').timepicker('show');
}
function checklist_attach_file(checklist) {
	checklist_id = $(checklist).parents('span').data('checklist');
	var type = 'checklist';
	if(checklist_id.toString().substring(0,5) == 'BOARD') {
		var type = 'checklist_board';
		checklist_id = checklist_id.substring(5);
	}
	var file_id = 'attach_'+(type == 'checklist' ? '' : 'board_')+checklist_id;
	$('[name='+file_id+']').change(function() {
		var fileData = new FormData();
		fileData.append('file',$('[name='+file_id+']')[0].files[0]);
		$.ajax({
			contentType: false,
			processData: false,
			type: "POST",
			url: "../Checklist/checklist_ajax.php?fill=checklist_upload&type="+type+"&id="+checklist_id,
			data: fileData,
			complete: function(result) {
				console.log(result.responseText);
				window.location.reload();
			}
		});
	});
	$('[name='+file_id+']').click();
}
function checklist_flag_item(checklist) {
	checklist_id = $(checklist).parents('span').data('checklist');
	var type = 'checklist';
	if(checklist_id.toString().substring(0,5) == 'BOARD') {
		var type = 'checklist_board';
		checklist_id = checklist_id.substring(5);
	}
	$.ajax({
		method: "POST",
		url: "../Checklist/checklist_ajax.php?fill=checklistflag",
		data: { type: type, id: checklist_id },
		complete: function(result) {
			console.log(result.responseText);
			if(type == 'checklist') {
				$(checklist).closest('li').css('background-color',(result.responseText == '' ? '' : '#'+result.responseText));
			} else {
				$(checklist).closest('form').css('background-color',(result.responseText == '' ? '' : '#'+result.responseText));
			}
		}
	});
}
function checklist_archive(checklist) {
	checklist_id = $(checklist).parents('span').data('checklist');
	var type = 'checklist';
	if(checklist_id.toString().substring(0,5) == 'BOARD') {
		var type = 'checklist board';
		checklist_id = checklist_id.substring(5);
	}
	if(type == 'checklist' && confirm("Are you sure you want to archive this item?")) {
		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "../Checklist/checklist_ajax.php?fill=delete_checklist&checklistid="+checklist_id,
			dataType: "html",   //expect html to be returned
			success: function(response){
				window.location.reload();
				console.log(response.responseText);
			}
		});
	}
	else if(confirm("Are you sure you want to archive this checklist?")) {
		window.location = "<?php echo WEBSITE_URL; ?>/delete_restore.php?action=delete&remove_checklist=all&checklistid=" + checklist_id;
	}
}
function checklist_export_pdf(checklist) {
	checklist_id = $(checklist).parents('span').data('checklist').substring(5);
	var type = 'checklist board';
	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "../Checklist/checklist_ajax.php?fill=export_pdf&checklistid="+checklist_id,
		dataType: "html",   //expect html to be returned
		success: function(response){
			if(response != 'NO ID') {
				window.open('<?= WEBSITE_URL ?>/Checklist/download/Checklist_'+checklist_id+'.pdf', '_blank');
			}
		}
	});
}
function checklist_mark_favourite(button) {
	if(button.src.includes('filled')) {
		button.src = '<?= WEBSITE_URL ?>/img/blank_star.png';
	} else {
		button.src = '<?= WEBSITE_URL ?>/img/filled_star.png';
	}
	$.ajax({
		type: "GET",
		url: "../Checklist/checklist_ajax.php?fill=mark_favourite&checklistid="+$(button).data('id')+"&status="+button.src.includes('filled'),
		dataType: "html",
		success: function(response) {
			console.log(response);
		}
	});
}
</script>
<style type='text/css'>
.display-field {
  display: inline-block;
  margin-top: 0.5em;
  text-indent: 2px;
  vertical-align: top;
  width: calc(100% - 2.5em);
}
.popped-field {
	width: calc(100% + 1em);
}
.popped-field .display-field {
	color: black;
	font-size: 1.2em;
}
</style>
<?php $checklistid = $_GET['view_checklist'];
$checklist = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `checklist` WHERE `checklistid`='$checklistid'"));
if(!in_array($_GET['edit'],explode(',',$checklist['assign_staff']))) {
	die("<script> window.location.replace('?'); </script>");
} ?>
<form name="form_sites1" method="post" action="" class="form-inline" role="form" <?= ($checklist['flag_colour'] == '' ? '' : 'style="background-color: #'.$checklist['flag_colour'].';"') ?>>
<h2><img src="<?= WEBSITE_URL ?>/img/<?= (in_array($checklist['checklistid'],explode(',',$user_settings['checklist_fav'])) ? 'filled' : 'blank') ?>_star.png" onclick="checklist_mark_favourite(this);" data-id="<?= $checklist['checklistid'] ?>"> <?= $checklist['checklist_name'] ?>
<?php foreach(array_filter(array_unique(explode(',',$checklist['assign_staff']))) as $assigned_staff) {
	profile_id($dbc, $assigned_staff);
} ?></h2>
<?php
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

echo '<div class="clearfix"></div>';

$query_check_credentials = "SELECT * FROM checklist_document WHERE checklistid='$checklistid' ORDER BY checklistdocid DESC";
$result = mysqli_query($dbc, $query_check_credentials);
$num_rows = mysqli_num_rows($result);
if($num_rows > 0) {
	echo "<table class='table table-bordered' style='width:100%;'>
	<tr class='hidden-xs hidden-sm'>
	<th>Document</th>
	<th>Date</th>
	<th>Uploaded By</th>
	</tr>";
	while($row = mysqli_fetch_array($result)) {
		echo '<tr>';
		$by = $row['created_by'];
		echo '<td data-title="Document"><a href="download/'.$row['document'].'" target="_blank">'.$row['document'].'</a></td>';
		echo '<td data-title="Date">'.$row['created_date'].'</td>';
		echo '<td data-title="Uploaded By">'.get_staff($dbc, $by).'</td>';
		echo '</tr>';
	}
	echo '</table>';
}

echo '<div class="tab-container">'; ?>
<span class="pull-right" style="cursor: pointer;" data-checklist="BOARD<?php echo $checklistid; ?>">
	<span style="padding: 0.25em 0.5em;" title="Flag This!" onclick="checklist_flag_item(this); return false;"><img src="<?php echo WEBSITE_URL; ?>/img/icons/ROOK-flag-icon.png" style="height:2.5em;"></span>
	<span style="padding: 0.25em 0.5em;" title="Send Alert" onclick="checklist_send_alert(this); return false;"><img src="<?php echo WEBSITE_URL; ?>/img/icons/ROOK-alert-icon.png" style="height:2.5em;"></span>
	<span style="padding: 0.25em 0.5em;" title="Send Email" onclick="checklist_send_email(this); return false;"><img src="<?php echo WEBSITE_URL; ?>/img/icons/ROOK-email-icon.png" style="height:2.5em;"></span>
	<span style="padding: 0.25em 0.5em;" title="Schedule Reminder" onclick="checklist_send_reminder(this); return false;"><img src="<?php echo WEBSITE_URL; ?>/img/icons/ROOK-reminder-icon.png" style="height:2.5em;"></span>
	<span style="padding: 0.25em 0.5em;" title="Attach File" onclick="checklist_attach_file(this); return false;"><img src="<?php echo WEBSITE_URL; ?>/img/icons/ROOK-attachment-icon.png" style="height:2.5em;"></span>
	<span style="padding: 0.25em 0.5em;" title="Download Checklist" onclick="checklist_export_pdf(this); return false;"><img src="<?php echo WEBSITE_URL; ?>/img/icons/ROOK-download-icon.png" style="height:2.5em;"></span>
	<span style="padding: 0.25em 0.5em;" title="Archive Checklist" onclick="checklist_archive(this); return false;"><img src="<?php echo WEBSITE_URL; ?>/img/icons/ROOK-trash-icon.png" style="height:2.5em;"></span>
	<?php if(trim($checklist['assign_staff'],',') == $_GET['edit']) { ?>
		<span class="block-label">Private</span>
	<?php } ?>
	<a style="padding: 0.25em 0.5em;" title="Edit Checklist" href="?category=<?= $_GET['category'] ?>&edit=<?= $_GET['edit'] ?>&edit_checklist=<?= $_GET['view_checklist'] ?>"><img src="<?php echo WEBSITE_URL; ?>/img/icons/ROOK-edit-icon.png" style="height:2.5em;"></a>
	<br /><input type="text" name="reminder_board_<?php echo $checklistid; ?>" style="display:none; margin-top: 2em;" class="form-control datepicker" />
</span>
<input type="file" name="attach_board_<?php echo $checklistid; ?>" style="display:none;" />
<?php echo '<div class="clearfix"></div><br />';
echo '<input type="hidden" name="checklistid" value="'.$checklistid.'" />';

echo '<ul id="sortable'.$i.'" class="connectedChecklist">';
echo '<li class="new_task_box no-sort"><input onChange="changeEndAme(this)" name="add_checklist" placeholder="Add new task..." id="add_new_task '.$checklistid.'" type="text" class="form-control" /></li>';

$result = mysqli_query($dbc, "SELECT * FROM checklist_name WHERE checklistid='$checklistid' AND checked = 0 AND deleted = 0 ORDER BY priority");

while($row = mysqli_fetch_array( $result )) {
	echo '<li id="'.$row['checklistnameid'].'" class="ui-state-default" '.($row['flag_colour'] == '' ? '' : 'style="background-color: #'.$row['flag_colour'].';"').'>';
	echo '<span style="cursor:pointer; font-size: 1.5em;"><input title="Complete" type="checkbox" onclick="checklistChange(this);" value="'.$row['checklistnameid'].'" style="height: 1.25em; width: 1.25em;" name="checklistnameid[]">';
	echo '<img class="drag_handle pull-right" src="'.WEBSITE_URL.'/img/icons/drag_handle.png" style="margin:0.25em; height:1.25em; width:1.25em;" />';
	echo '<span class="pull-right" style="display:inline-block; width:calc(100% - 4em);" data-checklist="'.$row['checklistnameid'].'">';
	echo '<span style="display:inline-block; text-align:center; width:11.1%;" title="Edit" onclick="checklist_edit_item(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-edit-icon.png" style="height:1.5em;" onclick="return false;"></span>';
	echo '<span style="display:inline-block; text-align:center; width:11.1%;" title="Flag This!" onclick="checklist_flag_item(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-flag-icon.png" style="height:1.5em;" onclick="return false;"></span>';
	echo '<span style="display:inline-block; text-align:center; width:11.1%;" title="Reply" onclick="checklist_send_reply(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-reply-icon.png" style="height:1.5em;" onclick="return false;"></span>';
	echo '<span style="display:inline-block; text-align:center; width:11.1%;" title="Attach File" onclick="checklist_attach_file(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-attachment-icon.png" style="height:1.5em;" onclick="return false;"></span>';
	echo '<span style="display:inline-block; text-align:center; width:11.1%;" title="Send Alert" onclick="checklist_send_alert(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-alert-icon.png" style="height:1.5em;" onclick="return false;"></span>';
	echo '<span style="display:inline-block; text-align:center; width:11.1%;" title="Send Email" onclick="checklist_send_email(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-email-icon.png" style="height:1.5em;" onclick="return false;"></span>';
	echo '<span style="display:inline-block; text-align:center; width:11.1%;" title="Schedule Reminder" onclick="checklist_send_reminder(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-reminder-icon.png" style="height:1.5em;" onclick="return false;"></span>';
	echo '<span style="display:inline-block; text-align:center; width:11.1%;" title="Add Time" onclick="checklist_add_time(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-timer-icon.png" style="height:1.5em;" onclick="return false;"></span>';
	echo '<span style="display:inline-block; text-align:center; width:11.1%;" title="Archive Item" onclick="checklist_archive(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-trash-icon.png" style="height:1.5em;" onclick="return false;"></span>';
	echo '<input type="text" name="reply_'.$row['checklistnameid'].'" style="display:none; margin-top: 2em;" class="form-control" />';
	echo '<input type="text" name="checklist_time_'.$row['checklistnameid'].'" style="display:none; margin-top: 2em;" class="form-control timepicker" />';
	echo '<input type="text" name="reminder_'.$row['checklistnameid'].'" style="display:none; margin-top: 2em;" class="form-control datepicker" />';
	echo '<input type="file" name="attach_'.$row['checklistnameid'].'" style="display:none;" class="form-control" /><br />';
	echo '<span class="display-field"><input type="text" name="edit_'.$row['checklistnameid'].'" style="display:none;" class="form-control" value="'.explode('<p>',html_entity_decode($row['checklist']))[0].'" />';
	echo '#'.$row['checklistnameid'].': '.html_entity_decode($row['checklist']).'</span>&nbsp;&nbsp;';
	$documents = mysqli_query($dbc, "SELECT * FROM checklist_name_document WHERE checklistnameid='".$row['checklistnameid']."'");
	while($doc = mysqli_fetch_array($documents)) {
		echo '<br /><a href="../Checklist/download/'.$doc['document'].'" target="_blank">'.$doc['document'].' (Uploaded by '.get_staff($dbc, $doc['created_by']).' on '.$doc['created_date'].')</a>';
	echo '</span>';
	}
	echo '</span></li>';
}

echo '<li class="new_task_box no-sort"><input onChange="changeEndAme(this)" name="add_checklist" placeholder="Add new task..." id="add_new_task '.$checklistid.'" type="text" class="form-control" /></li>';

$result = mysqli_query($dbc, "SELECT * FROM checklist_name WHERE checklistid='$checklistid' AND checked = 1 AND deleted = 0");
while($row = mysqli_fetch_array( $result )) {
	$info = ' : '.$row['updated_date']. ' : '.$row['updated_by'];
	echo '<li id="'.$row['checklistnameid'].'" title="Incomplete" class="ui-state-default no-sort"><span style="cursor:pointer; font-size: 20px;"><input type="checkbox" onclick="checklistChange(this);" checked value="'.$row['checklistnameid'].'" style="height: 30px; width: 30px;" name="checklistnameid[]">';

	echo '&nbsp;&nbsp;'.html_entity_decode($row['checklist']).$info;
	$documents = mysqli_query($dbc, "SELECT * FROM checklist_name_document WHERE checklistnameid='".$row['checklistnameid']."'");
	while($doc = mysqli_fetch_array($documents)) {
		echo '<br /><a href="download/'.$doc['document'].'">'.$doc['document'].' (Uploaded by '.get_staff($dbc, $doc['created_by']).' on '.$doc['created_date'].')</a>';
	}
	echo '</span>';

	echo '</li>';
}

echo '</ul>'; ?>
</div>

<div class="clearfix"></div>
<div class="form-group clearfix">
    <div class="col-sm-6">
		<span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="If you click this, the current Checklist will not be saved."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
		<a href="<?= $url ?>&list_checklists=1" class="btn brand-btn btn-lg">Back</a>
	</div>
</div>

</form>