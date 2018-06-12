<?php
/*
Inventory Listing
*/
include ('../include.php');
include_once('../tcpdf/tcpdf.php');
error_reporting(0);
if (isset($_POST['export_pdf'])) {
    $checklistid = $_POST['checklistid'];
	include('checklist_pdf.php');
}
?>
<script type="text/javascript" src="checklist.js"></script>
<style type='text/css'>
.display-field {
  display: inline-block;
  /* padding-left: 50px; */
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

	$("#businessid").change(function() {
        var businessid = $("#businessid").val();
		$.ajax({
			type: "GET",
			url: "checklist_ajax.php?fill=projectname&businessid="+businessid,
			dataType: "html",   //expect html to be returned
			success: function(response){
				$('#projectid').html(response);
				$("#projectid").trigger("change.select2");
			}
		});
	});

});
function choose_user(target, type, id) {
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
			if($(this).closest('body').find('select').val() != '' && confirm('Are you sure you want to send the '+target+' to the selected user?')) {
				if(target == 'alert') {
					$.ajax({
						method: 'POST',
						url: '../Project/checklist_ajax.php?fill=checklistalert',
						data: { id: id, type: type, user: $(this).closest('body').find('select').val() },
						complete: function(result) { console.log(result.responseText); }
					});
				}
				else if(target == 'email') {
					$.ajax({
						method: 'POST',
						url: '../Project/checklist_ajax.php?fill=checklistemail',
						data: { id: id, type: type, user: $(this).closest('body').find('select').val() },
						complete: function(result) { console.log(result.responseText); }
					});
				}
				else if(target == 'reminder') {
					$.ajax({
						method: 'POST',
						url: '../Project/checklist_ajax.php?fill=checklistreminder',
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
function send_alert(checklist) {
	checklist_id = $(checklist).parents('span').data('checklist');
	var type = 'checklist';
	if(checklist_id.toString().substring(0,5) == 'BOARD') {
		var type = 'checklist board';
		checklist_id = checklist_id.substring(5);
	}
	choose_user('alert', type, checklist_id);
}
function send_email(checklist) {
	checklist_id = $(checklist).parents('span').data('checklist');
	var type = 'checklist';
	if(checklist_id.toString().substring(0,5) == 'BOARD') {
		var type = 'checklist board';
		checklist_id = checklist_id.substring(5);
	}
	choose_user('email', type, checklist_id);
}
function send_reminder(checklist) {
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
			choose_user('reminder', type, checklist_id, date);
		}
	});
}
function send_reply(checklist) {
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
				url: '../Project/checklist_ajax.php?fill=checklistreply',
				data: { id: checklist_id, reply: save_reply },
				complete: function(result) { window.location.reload(); }
			})
		}
	});
}
function attach_file(checklist) {
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
			url: "../Project/checklist_ajax.php?fill=checklist_upload&type="+type+"&id="+checklist_id,
			data: fileData,
			complete: function(result) {
				console.log(result.responseText);
				window.location.reload();
				//alert('Your file has been uploaded.');
			}
		});
	});
	$('[name='+file_id+']').click();
}
function flag_item(checklist) {
	checklist_id = $(checklist).parents('span').data('checklist');
	var type = 'checklist';
	if(checklist_id.toString().substring(0,5) == 'BOARD') {
		var type = 'checklist_board';
		checklist_id = checklist_id.substring(5);
	}
	$.ajax({
		method: "POST",
		url: "../Project/checklist_ajax.php?fill=checklistflag",
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
function archive(checklist) {
	checklist_id = $(checklist).parents('span').data('checklist');
	var type = 'checklist';
	if(checklist_id.toString().substring(0,5) == 'BOARD') {
		var type = 'checklist board';
		checklist_id = checklist_id.substring(5);
	}
	if(type == 'checklist' && confirm("Are you sure you want to archive this item?")) {
		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "../Project/checklist_ajax.php?fill=delete_checklist&checklistid="+checklist_id,
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
</script>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('checklist');
$tab_config = get_config($dbc, 'checklist_tabs_' . $_SESSION['contactid']);
?>
<div class="container">
	<div class="iframe_holder" style="display:none;">
		<img src="<?php echo WEBSITE_URL; ?>/img/icons/close.png" class="close_iframer" width="45px" style="position:relative; right:10px; float:right; top:58px; cursor:pointer;">
		<span class="iframe_title" style="color:white; font-weight:bold; position:relative; top:58px; left:20px; font-size:30px;"></span>
		<iframe id="iframe_instead_of_window" style="width:100%; overflow:hidden; height:200px; border:0;" src=""></iframe>
	</div>
	<div class="row hide_on_iframe">

    <h1 class="single-pad-bottom pull-left">Project Checklists</h1>

    <div class="clearfix"></div>
    <div class="notice double-gap-bottom popover-examples">
        <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
        <div class="col-sm-11"><span class="notice-name">NOTE:</span>
        You must add a checklist in order to see your checklists in each sub tab. To do so, click Add Checklist and fill in all of the fields. Once you have hit Submit, you will see the checklist in the specific sub tab you have assigned it to.</div>
        <div class="clearfix"></div>
    </div>

	<?php
	if(config_visible_function($dbc, 'checklist') == 1) {
		echo '<div class="pull-right">';
			echo '<span class="popover-examples list-inline" style="margin:0 7px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes will appear on your dashboard."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
			echo '<a href="field_config.php" class="mobile-block"><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
		echo '</div>';
	}

    //echo '<br><a href="add_task_board.php" class="pull-right"><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';

	echo '<div class="tab-container">';

    $subtabid = $_GET['subtabid'];

	$query_retrieve_subtabs = mysqli_query($dbc, "SELECT * FROM `checklist_subtab` WHERE (`created_by` = ".$_SESSION['contactid']." OR `shared` LIKE '%,".$_SESSION['contactid'].",%') AND `deleted`=0");

	while ($row = mysqli_fetch_array($query_retrieve_subtabs)) {
		if (strpos($tab_config, $row['subtabid'] . '_') !== false) {
			$subtabid_row = $row['subtabid'];
			$active_subtab = '';
			if ($subtabid == $subtabid_row) {
				$active_subtab = ' active_tab';
				$subtab_shared = $row['shared'];
			}
			$subtab_name = $row['name'];
			$query_retrieve_count = mysqli_fetch_array(mysqli_query($dbc, "SELECT SUM(IF(`subtabid`='$subtabid_row',1,0)) subtabcount FROM `checklist` WHERE `deleted` = 0"));
			$subtab_count = $query_retrieve_count['subtabcount'];

			echo "
				<div class='pull-left tab tab-nomargin'>
					<a href='checklist.php?subtabid=$subtabid_row'><button type='button' class='btn brand-btn mobile-block mobile-100 $active_subtab'>$subtab_name ($subtab_count)</button></a>
				</div>";
		}
	}

	//echo "<a href='tasks.php?category=All'><button type='button' class='btn brand-btn mobile-block'>Community Checklist</button></a>";

	if(strpos($tab_config, 'project_tab') !== false) {
		echo "
			<div class='pull-left tab tab-nomargin'>
				<span class='popover-examples list-inline'><a data-toggle='tooltip' data-placement='top' title='Click here to see Checklists from Project Tickets.'><img src='" . WEBSITE_URL . "/img/info.png' width='20'></a></span>
				<a href='project_checklist.php'><button type='button' class='btn brand-btn mobile-block active_tab mobile-100'>Project Checklists</button></a>
			</div>";
	}
	if(strpos($tab_config, 'reporting') !== false) {
		echo "
			<div class='pull-left tab tab-nomargin'>
				<span class='popover-examples list-inline'><a data-toggle='tooltip' data-placement='top' title='Click here to see all Checklist activity.'><img src='" . WEBSITE_URL . "/img/info.png' width='20'></a></span>
				<a href='checklist_report.php'><button type='button' class='btn brand-btn mobile-block mobile-100'>Reporting</button></a>
			</div>";
	}

	echo '</div><div class="clearfix"></div><br />';

    $type = $_GET['type'];

    /*
    echo '
		<div class="mobile-100-container">
			<a href="add_checklist.php" class="btn brand-btn mobile-block gap-bottom pull-right">Add Checklist</a>
			<span class="popover-examples list-inline pull-right" style="margin:5px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add a Checklist."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>
		</div>';
        */
    ?>

	<br><br><br>

    <form name="form_sites" method="post" action="" class="form-horizontal" role="form">
        <?php
            $businessid = '';
            $projectid = '';

            if(isset($_POST['search_user_submit'])) {
                $businessid = $_POST['businessid'];
                $projectid = $_POST['projectid'];
            }

        ?>

        <div class="form-group">
            <label for="first_name" class="col-sm-1 control-label">Business:</label>
            <div class="col-sm-8" style="width:20%;">
                <select name="businessid" id="businessid" data-placeholder="Select a Business..." class="chosen-select-deselect form-control" width="380">
                    <option value=''></option><?php
                    $query = mysqli_query($dbc, "SELECT distinct(businessid) FROM `tickets`");
                    while($row = mysqli_fetch_array($query)) {
                        if ($businessid== $row['businessid']) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value='". $row['businessid']."'>".get_client($dbc, $row['businessid']).'</option>';
                    } ?>
                </select>
            </div>

          <label for="site_name" class="col-sm-1 control-label">Project Name:</label>
		  <div class="col-sm-8" style="width:30%;">
			<select data-placeholder="Select a Project..." name="projectid" id="projectid"  class="chosen-select-deselect form-control" width="380">
			  <option value=""></option>
			  <?php
                if($businessid != '') {
                    $query = mysqli_query($dbc, "SELECT distinct(projectid) FROM `tickets` WHERE businessid='$businessid' AND projectid != '' AND projectid != 0");
                } else {
                    $query = mysqli_query($dbc, "SELECT distinct(projectid) FROM `tickets` WHERE (projectid != '' AND projectid != 0) OR (client_projectid != '' AND client_projectid != 0)");
                }

                while($row = mysqli_fetch_array($query)) {
                    if ($projectid== $row['projectid']) {
                        $selected = 'selected="selected"';
                    } else {
                        $selected = '';
                    }
                    echo "<option ".$selected." value='". $row['projectid']."'>".get_project($dbc, $row['projectid'], 'project_name').'</option>';
                }
			  ?>
			</select>
		  </div>

        <button type="submit" name="search_user_submit" value="Search" class="btn brand-btn mobile-block">Submit</button>
		</div>
	    <br><br>

        <?php
        if($projectid != '') {

            echo '<ul id="sortable'.$i.'" class="connectedChecklist">
            <li class="ui-state-default ui-state-disabled" style="cursor:pointer; font-size: 30px;">Tickets</li>';

            $result = mysqli_query($dbc, "SELECT t.*, c.name FROM tickets t, contacts c WHERE t.businessid = c.contactid AND projectid='$projectid' ORDER BY ticketid DESC");

            while($row = mysqli_fetch_array( $result )) {
                    $checked = '';
                    if($row['status'] == 'Archive') {
                        $checked = ' checked';
                    }
                    echo '<li id="'.$row['ticketid'].'" class="ui-state-default"'.($row['flag_colour'] == '' ? '' : 'style="background-color: #'.$row['flag_colour'].';"').'>';

                    echo '<span style="cursor:pointer; font-size: 25px;"><input type="checkbox" '.$checked.' disabled value="'.$row['ticketid'].'" style="height: 30px; width: 30px;" name="checklistnameid[]">';
                    echo '<span class="pull-right" style="display:inline-block; width:calc(100% - 2em);" data-checklist="'.$row['ticketid'].'">';
                    echo '<span style="display:inline-block; text-align:center; width:12.5%;" title="Flag This!" onclick="flag_item(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-flag-icon.png" style="height:1.5em;" onclick="return false;"></span>';
                    echo '<span style="display:inline-block; text-align:center; width:12.5%;" title="Send Alert" onclick="send_alert(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-alert-icon.png" style="height:1.5em;" onclick="return false;"></span>';
                    echo '<span style="display:inline-block; text-align:center; width:12.5%;" title="Send Email" onclick="send_email(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-email-icon.png" style="height:1.5em;" onclick="return false;"></span>';
                    echo '<span style="display:inline-block; text-align:center; width:12.5%;" title="Schedule Reminder" onclick="send_reminder(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-reminder-icon.png" style="height:1.5em;" onclick="return false;"></span>';
                    echo '<span style="display:inline-block; text-align:center; width:12.5%;" title="Attach File" onclick="attach_file(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-attachment-icon.png" style="height:1.5em;" onclick="return false;"></span>';
                    echo '<span style="display:inline-block; text-align:center; width:12.5%;" title="Archive Item" onclick="archive(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-trash-icon.png" style="height:1.5em;" onclick="return false;"></span>';
                    echo '</span>';
                    echo '<input type="text" name="reply_'.$row['ticketid'].'" style="display:none; margin-top: 2em;" class="form-control" />';
                    echo '<input type="text" name="checklist_time_'.$row['ticketid'].'" style="display:none; margin-top: 2em;" class="form-control timepicker" />';
                    echo '<input type="text" name="reminder_'.$row['ticketid'].'" style="display:none; margin-top: 2em;" class="form-control datepicker" />';
                    echo '<input type="file" name="attach_'.$row['ticketid'].'" style="display:none;" class="form-control" />';
                    echo '<br /><span class="display-field">#'.$row['ticketid'].' : '.$row['service_type'].' : '.$row['heading'].' : '.$row['status'].'</span>&nbsp;&nbsp;';
                    echo '<img class="drag_handle pull-right" src="'.WEBSITE_URL.'/img/icons/hold.png" style="height:1.5em; width:1.5em;" /></span>';
                    $documents = mysqli_query($dbc, "SELECT * FROM ticket_document WHERE ticketid='".$row['ticketid']."'");
                    while($doc = mysqli_fetch_array($documents)) {
                        echo '<br /><a href="download/'.$doc['document'].'">'.$doc['document'].' (Uploaded by '.get_staff($dbc, $doc['created_by']).' on '.$doc['created_date'].')</a>';
                    }

                    echo '</li>';

                }

            echo '</ul>';
            $i++;
        }

            ?>
        </div>


		</form>
	</div>
</div>

<?php include ('../footer.php'); ?>
