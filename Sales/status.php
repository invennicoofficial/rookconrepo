<!-- Sales Status --><?php error_reporting(0);
include_once('../include.php');
checkAuthorised('sales');
$statuses = get_config($dbc, 'sales_lead_status');
$next_actions = get_config($dbc, 'sales_next_action');
$approvals = approval_visible_function($dbc, 'sales');
$project_security = get_security($dbc, 'project');
$estimates_active = tile_enabled($dbc, 'estimate')['user_enabled'];
$quick_actions = explode(',',get_config($dbc, 'quick_action_icons'));
$flag_colours = explode(',', get_config($dbc, "ticket_colour_flags"));
$flag_labels = explode('#*#', get_config($dbc, "ticket_colour_flag_names"));
$filter = "`deleted` = 0 ";
if(isset($_GET['s'])) {
	$filter .= " AND `status`='".filter_var($_GET['s'],FILTER_SANITIZE_STRING)."'";
    $page_title = $_GET['s'];
} else if(isset($_GET['r'])) {
	$filter .= " AND `region`='".filter_var($_GET['r'],FILTER_SANITIZE_STRING)."'";
    $page_title = $_GET['r'];
} else if(isset($_GET['l'])) {
	$filter .= " AND `location`='".filter_var($_GET['l'],FILTER_SANITIZE_STRING)."'";
    $page_title = $_GET['l'];
} else if(isset($_GET['c'])) {
	$filter .= " AND `classification`='".filter_var($_GET['c'],FILTER_SANITIZE_STRING)."'";
    $page_title = $_GET['c'];
} else if(isset($_GET['contactid'])) {
    $filter .= " AND (CONCAT(',',`share_lead`,',') LIKE '%,".filter_var($_GET['contactid'],FILTER_SANITIZE_STRING).",%' OR `primary_staff` = '".filter_var($_GET['contactid'],FILTER_SANITIZE_STRING)."')";
    $page_title = get_contact($dbc, $_GET['contactid']);
} ?>
<script type="text/javascript">
$(document).on('change', 'select[name="status"]', function() { changeLeadStatus(this); });
$(document).on('change', 'select[name="next_action"]', function() { changeLeadNextAction(this); });
</script><script>
$(document).ready(function() {
    $('.track_time .start').on('click', function() {
        $(this).closest('.track_time').find('.timer').timer({
            editable: true
        });
        $(this).hide();
        $(this).next('.stop').show();
    });

    $('.track_time .stop').on('click', function() {
		var item = $(this).closest('.info-block-detail');
		item.find('.timer').timer('stop');
		$(this).hide();
		$(this).prev('.start').show();
        var timer_value = item.find('.timer').val();
		item.find('.timer').timer('remove');
        item.find('.track_time').toggle();
		if ( timer_value != '' ) {
			$.ajax({
				method: 'POST',
				url: 'sales_ajax_all.php?action=lead_time',
				data: { id: item.data('id'), time: timer_value }
			});
        }
	});
});
$(document).on('change', 'select[name="status"]', function() { changeLeadStatus(this); });
$(document).on('change', 'select[name="next_action"]', function() { changeLeadNextAction(this); });

function archive_sales_lead(sel) {
    var id = sel.id;
    var arr = id.split('_');
    var salesid = arr[1];
    $.ajax({
        url: 'sales_ajax_all.php?fill=archive_sales_lead&salesid='+salesid,
        type: "GET",
        success: function() {
            $(id).closest('.info-block-detail').hide();
        }
    });
}

function saveNote(sel) {
    var salesid = $(sel).data('salesid');
    var note = sel.value;
    if (note!='') {
        $.ajax({
            url: 'sales_ajax_all.php?fill=saveNote&salesid='+salesid+'&note='+note,
            type: "GET",
            success: function(response) {
                alert("Note saved.");
            }
        });
    }
}

function flagLead(sel) {
	var item = $(sel).closest('.info-block-detail');
	$.ajax({
		url: 'sales_ajax_all.php?action=flag_colour',
		method: 'POST',
		data: {
			field: 'flag_colour',
			value: item.data('colour'),
			table: item.data('table'),
			id: item.data('id'),
			id_field: item.data('id-field')
		},
		success: function(response) {
			item.data('colour',response.substr(0,6));
			item.css('background-color','#'+response.substr(0,6));
			item.find('.flag-label').html(response.substr(6));
		}
	});
}

function flagLeadManual(sel) {
	var item = $(sel).closest('.info-block-detail');
	item.find('.flag_field_labels,[name=label],[name=colour],[name=flag_it],[name=flag_cancel],[name=flag_off],[name=flag_start],[name=flag_end]').show();
	item.find('[name=flag_cancel]').off('click').click(function() {
		item.find('.flag_field_labels,[name=label],[name=colour],[name=flag_it],[name=flag_cancel],[name=flag_off],[name=flag_start],[name=flag_end]').hide();
		return false;
	});
	item.find('[name=flag_off]').off('click').click(function() {
		item.find('[name=colour]').val('FFFFFF');
		item.find('[name=label]').val('');
		item.find('[name=flag_start]').val('');
		item.find('[name=flag_end]').val('');
		item.find('[name=flag_it]').click();
		return false;
	});
	item.find('[name=flag_it]').off('click').click(function() {
		$.ajax({
			url: 'sales_ajax_all.php?action=manual_flag_colour',
			method: 'POST',
			data: {
				field: 'manual_flag_colour',
				value: item.find('[name=colour]').val(),
				table: item.data('table'),
				label: item.find('[name=label]').val(),
				start: item.find('[name=flag_start]').val(),
				end: item.find('[name=flag_end]').val(),
				id: item.data('id'),
				id_field: item.data('id-field')
			}
		});
		item.find('.flag_field_labels,[name=label],[name=colour],[name=flag_it],[name=flag_cancel],[name=flag_off],[name=flag_start],[name=flag_end]').hide();
		item.data('colour',item.find('[name=colour]').val());
		item.css('background-color','#'+item.find('[name=colour]').val());
		item.find('.flag-label').text(item.find('[name=label]').val());
		return false;
	});
}

function addDocument(sel) {
	var item = $(sel).closest('.info-block-detail');
	item.find('[type=file]').off('change').change(function() {
		var fileData = new FormData();
		fileData.append('file',$(this)[0].files[0]);
		fileData.append('id',item.data('id'));
		$.ajax({
			contentType: false,
			processData: false,
			method: "POST",
			url: "sales_ajax_all.php?action=add_document",
			data: fileData
		});
	}).click();
}

function sendEmail(sel) {
	var item = $(sel).closest('.info-block-detail');
	var salesid = item.data('id');
	overlayIFrameSlider('<?= WEBSITE_URL ?>/quick_action_email.php?tile=sales&salesid='+salesid,'auto',false,true)
}
function email_action() {
	var item = $(this).closest('.info-block-detail');
	$.ajax({
		url: 'sales_ajax_all.php?action=send_email',
		method: 'POST',
		data: {
			value: item.find('.select_users select').val(),
			id: item.data('id')
		}
	});
	item.find('.select_users').hide();
	item.find('.select_users select').val('').trigger('change.select2');
}

function createProject(sel) {
    var salesid = sel.id;
    $.ajax({
        url: 'sales_ajax_all.php?fill=changeCustCat&salesid='+salesid,
        type: "GET",
        success: function(response) {
            location.replace('../Project/projects.php?edit=0&type=favourite&salesid='+salesid);
        }
    });
}

function setReminder(sel) {
	var item = $(sel).closest('.info-block-detail');
	item.find('.reminders').show();
	item.find('.send').click(function() {
		$.post('sales_ajax_all.php?action=set_reminder', {
				user: item.find('.reminders select').val().join(','),
				date: item.find('.reminders input.datepicker').val(),
				id: item.data('id')
			});
		item.find('.reminders').hide();
		item.find('.reminders input.datepicker').val('');
		item.find('.reminders option').removeAttr('selected');
		item.find('.reminders select').trigger('change.select2');
	});
	item.find('.cancel').click(function() {
		item.find('.reminders').hide();
		item.find('.reminders input').val('');
		item.find('.reminders select').val('').trigger('change.select2');
	});
}

function addTime(sel) {
	var item = $(sel).closest('.info-block-detail');
	item.find('[name=time_add]').timepicker('option', 'onClose', function(time) {
		var time = $(this).val();
		$(this).val('00:00');
		if(time != '' && time != '00:00') {
			$.ajax({
				method: 'POST',
				url: 'sales_ajax_all.php?action=lead_time',
				data: { id: item.data('id'), time: time+':00' }
			});
		}
	});
	item.find('[name=time_add]').timepicker('show');
}

function trackTime(sel) {
	var item = $(sel).closest('.info-block-detail');
	item.find('.track_time').toggle();
}

function openProjectDialog(sel) {
    var salesid = sel.id;
    $('#dialog_choose_project').dialog({
        resizable: false,
        height: "auto",
        width: ($(window).width() <= 500 ? $(window).width() : 500),
        modal: true,
        buttons: {
			'Create New <?= PROJECT_NOUN ?>': function() {
				$.ajax({
					url: 'sales_ajax_all.php?fill=changeCustCat&salesid='+salesid,
					type: "GET",
					success: function(response) {
						location.replace('../Project/projects.php?edit=0&type=favourite&salesid='+salesid);
					}
				});
			},
            'Assign': function() {
                var projectid = $('select[name="projectid"] option:selected').val();
                $.ajax({
                    url: 'sales_ajax_all.php?fill=changeCustCat&salesid='+salesid,
                    type: "GET",
                    success: function(response) {
                        location.replace('../Project/projects.php?edit='+projectid+'&tab=info&salesid='+salesid);
                    }
                });
            },
            Cancel: function() {
                $(this).dialog('close');
            }
        }
    });
}
function businessFilter(sel) {
    var business = sel.value;
    var dialog = $(sel).closest('.dialog');
    dialog.find('[name=clientid] option').each(function() {
        if($(this).data('business') != business && business > 0) {
            $(this).hide();
        } else {
            $(this).show();
        }
    });
    dialog.find('[name=clientid]').trigger('change.select2');
    dialog.find('[name=projectid] option').each(function() {
        if($(this).data('business') != business && business > 0) {
            $(this).hide();
        } else {
            $(this).show();
        }
    });
    dialog.find('[name=clientid]').trigger('change.select2');
}
$(document).on('change', '.dialog select[name=businessid]', function() { businessFilter(this); });
function contactFilter(sel) {
    var dialog = $(sel).closest('.dialog');
    var business = $(sel).find('option:selected').data('business');
    var contact = sel.value;
    dialog.find('[name=businessid]').val(business).trigger('change.select2');
    dialog.find('[name=projectid] option').each(function() {
        if($(this).data('client') != undefined && $(this).data('client').indexOf(','+contact+',') < 0 && contact > 0) {
            $(this).hide();
        } else {
            $(this).show();
        }
    });
    dialog.find('[name=clientid]').trigger('change.select2');
}
$(document).on('change', '.dialog select[name=clientid]', function() { contactFilter(this); });
</script>
<!-- Dialog -->
<div id="dialog_choose_project" title="Select <?= PROJECT_NOUN ?> to Assign" class="dialog" style="display:none;">
    <?php $project_fields = ','.mysqli_fetch_array(mysqli_query($dbc,"SELECT `config_fields` FROM field_config_project WHERE type='ALL'"))[0].',';
    $project_configs = mysqli_query($dbc,"SELECT `config_fields` FROM field_config_project");
    while($project_config = mysqli_fetch_array($project_configs)[0]) {
        $project_fields .= $project_config.',';
    }
    $project_fields = explode(',',$project_fields);
    if(in_array('Information Business', $project_fields)) { ?>
        <div class="form-group">
            <label class="col-sm-4 control-label">Filter <?= PROJECT_TILE ?> by <?= BUSINESS_CAT ?>:</label>
            <div class="col-sm-8">
                <select name="businessid" data-placeholder="Select <?= BUSINESS_CAT ?>" class="chosen-select-deselect form-control"><option />
                    <?php foreach(sort_contacts_query($dbc->query("SELECT `contacts`.`contactid`, `contacts`.`name`, `contacts`.`first_name`, `contacts`.`last_name` FROM `contacts` LEFT JOIN `project` ON `contacts`.`contactid`=`project`.`businessid` WHERE `contacts`.`deleted`=0 AND `contacts`.`status`=1 AND `project`.`deleted`=0 GROUP BY `contacts`.`contactid`")) as $bus_row) { ?>
                        <option value="<?= $bus_row['contactid'] ?>"><?= $bus_row['full_name'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="clearfix"></div>
    <?php }
    if(in_array('Information Contact', $project_fields)) { ?>
        <div class="form-group">
            <label class="col-sm-4 control-label">Filter <?= PROJECT_TILE ?> by <?= CONTACTS_NOUN ?>:</label>
            <div class="col-sm-8">
                <select name="clientid" data-placeholder="Select <?= CONTACTS_NOUN ?>" class="chosen-select-deselect form-control"><option />
                    <?php foreach(sort_contacts_query($dbc->query("SELECT `contacts`.`contactid`, `contacts`.`businessid`, `contacts`.`name`, `contacts`.`first_name`, `contacts`.`last_name` FROM `contacts` LEFT JOIN `project` ON CONCAT(',',`project`.`clientid`,',') LIKE CONCAT('%,',`contacts`.`contactid`,',%') WHERE `contacts`.`deleted`=0 AND `contacts`.`status`=1 AND `project`.`deleted`=0 GROUP BY `contacts`.`contactid`")) as $cont_row) { ?>
                        <option data-business="<?= $cont_row['businessid'] ?>" value="<?= $cont_row['contactid'] ?>"><?= $cont_row['full_name'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="clearfix"></div>
    <?php } ?>
    <div class="form-group">
        <label class="col-sm-4 control-label"><?= PROJECT_TILE ?>:</label>
        <div class="col-sm-8">
            <select name="projectid" data-placeholder="Select <?= PROJECT_NOUN ?>" class="chosen-select-deselect form-control">
                <option></option><?php
                $get_projects = mysqli_query($dbc, "SELECT `projectid`, `businessid`, `clientid`, `project_name` FROM `project` WHERE project_name<>'' AND deleted=0 ORDER BY project_name");
                if ($get_projects->num_rows>0) {
                    while ($row_project=mysqli_fetch_assoc($get_projects)) { ?>
                        <option data-business="<?= $row_project['businessid'] ?>" data-client=",<?= $row_project['clientid'] ?>," value="<?=$row_project['projectid']?>"><?=$row_project['project_name']?></option><?php
                    }
                } ?>
            </select>
        </div>
    </div>
</div>
<?php
$leads  = mysqli_query($dbc, "SELECT * FROM `sales` WHERE ".$filter.$query_mod);
echo '<div class="main-screen-white horizontal-scroll standard-dashboard-body" style="border: none; background: none;">';
echo '<div class="standard-dashboard-body-title"><h3>'.$page_title.'</h3></div>';
if ( $leads->num_rows > 0 ) {
    $i = 1;
    while ( $row=mysqli_fetch_assoc($leads) ) {
		$flag_colour = $flag_label = '';
		if(!empty($row['flag_label'])) {
			$flag_colour = $row['flag_colour'];
			$flag_label = $row['flag_label'];
		} else if(!empty($row['flag_colour'])) {
			$flag_colour = $row['flag_colour'];
			$flag_label = $flag_labels[array_search($row['flag_colour'], $flag_colours)];
		} ?>
        <div class="main-screen-white silver-border gap-bottom info-block-detail" data-id="<?= $row['salesid'] ?>" style="height:auto; <?= empty($flag_colour) ? '' : 'background-color: #'.$flag_colour ?>" data-searchable="<?= get_client($dbc, $row['businessid']); ?> <?= get_contact($dbc, $row['contactid']); ?>">
            <div class="col-xs-12 gap-top horizontal-block-container">
                <div class="horizontal-block">
                    <div class="horizontal-block-header">
						<span class="flag-label"><?= $flag_label ?></span>
                        <h4 class="col-md-6"><a href="sale.php?p=preview&id=<?= $row['salesid'] ?>">Sales Lead <?= $i; ?> <img class="inline-img" src="../img/icons/ROOK-edit-icon.png"></a></h4>
                        <div class="col-md-6"></div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
					<input type="text" class="form-control gap-top" name="notes" id="notes" value="" style="display:none;" data-table="sales_notes" data-salesid="<?= $row['salesid']; ?>" onkeypress="javascript:if(event.keyCode==13){ saveNote(this); $(this).val('').hide(); };" onblur="saveNote(this); $(this).val('').hide();">
					<?php if(in_array('flag_manual',$quick_actions)) {
						$colours = $flag_colours; ?>
						<span class="col-sm-3 text-center flag_field_labels" style="display:none;">Label</span><span class="col-sm-3 text-center flag_field_labels" style="display:none;">Colour</span><span class="col-sm-3 text-center flag_field_labels" style="display:none;">Start Date</span><span class="col-sm-3 text-center flag_field_labels" style="display:none;">End Date</span>
						<div class="col-sm-3"><input type='text' name='label' value='<?= $flag_label ?>' class="form-control" style="display:none;"></div>
						<div class="col-sm-3"><select name='colour' class="form-control" style="display:none;background-color:#<?= $ticket['flag_colour'] ?>;font-weight:bold;" onchange="$(this).css('background-color','#'+$(this).find('option:selected').val());">
								<option value="FFFFFF" style="background-color:#FFFFFF;">No Flag</option>
								<?php foreach($colours as $flag_colour) { ?>
									<option <?= $row['flag_colour'] == $flag_colour ? 'selected' : '' ?> value="<?= $flag_colour ?>" style="background-color:#<?= $flag_colour ?>;"></option>
								<?php } ?>
							</select></div>
						<div class="col-sm-3"><input type='text' name='flag_start' value='<?= $ticket['flag_start'] ?>' class="form-control datepicker" style="display:none;"></div>
						<div class="col-sm-3"><input type='text' name='flag_end' value='<?= $ticket['flag_end'] ?>' class="form-control datepicker" style="display:none;"></div>
						<button class="btn brand-btn pull-right" name="flag_it" onclick="return false;" style="display:none;">Flag This</button>
						<button class="btn brand-btn pull-right" name="flag_cancel" onclick="return false;" style="display:none;">Cancel</button>
						<button class="btn brand-btn pull-right" name="flag_off" onclick="return false;" style="display:none;">Remove Flag</button>
						<div class="clearfix"></div>
					<?php } ?>
					<input type='file' name='document' value='' style="display:none;">
					<div class="select_users" style="display:none;">
						<select data-placeholder="Select Staff" multiple class="chosen-select-deselect"><option></option>
						<?php foreach($staff_list as $staff) { ?>
							<option value="<?= $staff['contactid'] ?>"><?= $staff['full_name'] ?></option>
						<?php } ?>
						</select>
						<button class="btn brand-btn pull-right send">Submit</button>
						<button class="btn brand-btn pull-right cancel">Cancel</button>
						<div class="clearfix"></div>
					</div>
					<div class="reminders" style="display:none;">
						<select data-placeholder="Select Staff" multiple class="chosen-select-deselect"><option></option>
						<?php foreach($staff_list as $staff) { ?>
							<option value="<?= $staff['contactid'] ?>"><?= $staff['full_name'] ?></option>
						<?php } ?>
						</select>
						<input type="text" class="datepicker form-control">
						<button class="btn brand-btn pull-right send">Submit</button>
						<button class="btn brand-btn pull-right cancel">Cancel</button>
						<div class="clearfix"></div>
					</div>
					<div class="track_time" style="display:none;">
						<input type="text" name="timer" style="float:left;" class="form-control timer" placeholder="0 sec" />
						<button class="btn brand-btn pull-right start">Start</button>
						<button class="btn brand-btn pull-right stop" style="display:none;">Stop</button>
						<div class="clearfix"></div>
					</div>
					<input type="text" name="time_add" style="display:none; margin-top: 2em;" class="form-control timepicker">
					<input type="text" name="time_track" class="datetimepicker form-control" style="display:none;">
					<div class="gap-bottom action-icons">
						<?php if($project_security['edit'] > 0) { ?>
							<img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-add-icon.png" class="cursor-hand inline-img" title="Assign To A <?= PROJECT_NOUN ?>" id="<?=$row['salesid']?>" onclick="openProjectDialog(this); return false;" /><?php
						} ?>
						<?php if($estimates_active > 0) { ?>
							<a href="<?= WEBSITE_URL; ?>/Sales/sale.php?p=details&id=<?= $row['salesid'] ?>&a=estimate#estimate"><img src="<?= WEBSITE_URL; ?>/img/icons/create_project.png" class="inline-img black-color" title="Add Estimate" /></a>
						<?php } ?>
						<?php if(!in_array('flag_manual',$quick_actions) && in_array('flag',$quick_actions)) { ?>
							<a href="Flag This!" onclick="flagLead(this); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-flag-icon.png" class="inline-img black-color" title="Flag This!" /></a>
						<?php } ?>
						<?php if(in_array('flag_manual',$quick_actions)) { ?>
							<a href="Flag This!" onclick="flagLeadManual(this); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-flag-icon.png" class="inline-img black-color" title="Flag This!" /></a>
						<?php } ?>
						<?php if(in_array('attach',$quick_actions)) { ?>
							<a href="Attach File" onclick="addDocument(this); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-attachment-icon.png" class="inline-img black-color" title="Attach File" /></a>
						<?php } ?>
						<?php if(in_array('reply',$quick_actions)) { ?>
							<a href="Add Note" onclick="$(this).closest('.info-block-detail').find('[name=notes]').show().focus(); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-reply-icon.png" class="inline-img black-color" title="Add Note" /></a>
						<?php } ?>
						<?php if(in_array('email',$quick_actions)) { ?>
							<a href="Send Email" onclick="sendEmail(this); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-email-icon.png" class="inline-img black-color" title="Send Email" /></a>
						<?php } ?>
						<?php if(in_array('reminder',$quick_actions)) { ?>
							<a href="Schedule Reminder" onclick="setReminder(this); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-reminder-icon.png" class="inline-img black-color" title="Schedule Reminder" /></a>
						<?php } ?>
						<?php if(in_array('time',$quick_actions)) { ?>
							<a href="Add Time" onclick="addTime(this); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-timer-icon.png" class="inline-img black-color" title="Add Time" /></a>
						<?php } ?>
						<?php if(in_array('timer',$quick_actions)) { ?>
							<a href="Track Time" onclick="trackTime(this); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-timer2-icon.png" class="inline-img black-color" title="Track Time" /></a>
						<?php } ?>
						<?php if(in_array('archive',$quick_actions)) { ?>
							<a href="#" id="sales_<?= $row['salesid']; ?>" data-salesid="<?= $row['salesid']; ?>" onclick="archive_sales_lead(this); $(this).closest('.info-block-detail').hide(); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-trash-icon.png" class="inline-img" title="Archive the Sales Lead" /></a>
						<?php } ?>
					</div>
                    <div class="clearfix"></div>
                    <div class="horizontal-block-details">
                        <div class="col-xs-12 col-md-4">
                            <div class="col-xs-6 default-color">Business:</div>
                            <div class="col-xs-6"><?= get_client($dbc, $row['businessid']); ?></div>
                            <div class="clearfix"></div>
                            <div class="col-xs-6 default-color">Contact:</div>
                            <div class="col-xs-6"><?= get_contact($dbc, $row['contactid']); ?></div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="col-xs-6 default-color">Lead Status:</div>
                            <div class="col-xs-6">
								<?php if($approvals > 0 || $_GET['s'] != 'Pending') { ?>
									<select id="status_<?= $row['salesid']; ?>" data-placeholder="Select a Status" name="status" class="form-control chosen-select-deselect">
										<option value=""></option><?php
										foreach ( explode(',', $statuses) as $status_list ) {
											$selected = ($status_list==$_GET['s']) ? 'selected="selected"' : '';
											echo '<option '. $selected .' value="'. $status_list .'">'. $status_list .'</li>';
										} ?>
									</select>
								<?php } else {
									echo $_GET['s'];
								} ?>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-xs-6 default-color">Next Action:</div>
                            <div class="col-xs-6">
                                <select id="action_<?= $row['salesid']; ?>" data-placeholder="Select Next Action" name="next_action" class="form-control chosen-select-deselect">
                                    <option value=""></option><?php
                                    foreach ( explode(',', $next_actions) as $next_action ) {
                                        $selected = ($next_action==$row['next_action']) ? 'selected="selected"' : '';
                                        echo '<option '. $selected .' value="'. $next_action .'">'. $next_action .'</li>';
                                    } ?>
                                </select>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="col-xs-6 default-color">Total Due:</div>
                            <div class="col-xs-6">$<?= ( $row['lead_value'] > 0 ) ? number_format($row['lead_value']) : '0.00'; ?></div>
                            <div class="clearfix"></div>
                            <div class="col-xs-6 default-color">Date:</div>
                            <div class="col-xs-6"><input onchange="changeLeadFollowUpDate(this)" type="text" id="date_<?= $row['salesid']; ?>" name="date" class="datepicker form-control" value="<?= ( $row['new_reminder']!='0000-00-00' || !empty($row['new_reminder']) ) ? $row['new_reminder'] : 'YYYY-MM-DD'; ?>" /></div>
                            <div class="clearfix"></div>
                        </div>

						<input type="text" class="form-control gap-top" name="notes" id="notes" value="" style="display:none;" data-table="sales_notes" data-salesid="<?= $row['salesid']; ?>" onkeypress="javascript:if(event.keyCode==13){ saveNote(this); $(this).val('').hide(); };" onblur="saveNote(this); $(this).val('').hide();">
						<?php if(in_array('flag_manual',$quick_actions)) {
							$colours = $flag_colours; ?>
							<span class="col-sm-3 text-center flag_field_labels" style="display:none;">Label</span><span class="col-sm-3 text-center flag_field_labels" style="display:none;">Colour</span><span class="col-sm-3 text-center flag_field_labels" style="display:none;">Start Date</span><span class="col-sm-3 text-center flag_field_labels" style="display:none;">End Date</span>
							<div class="col-sm-3"><input type='text' name='label' value='<?= $flag_label ?>' class="form-control" style="display:none;"></div>
							<div class="col-sm-3"><select name='colour' class="form-control" style="display:none;background-color:#<?= $ticket['flag_colour'] ?>;font-weight:bold;" onchange="$(this).css('background-color','#'+$(this).find('option:selected').val());">
									<option value="FFFFFF" style="background-color:#FFFFFF;">No Flag</option>
									<?php foreach($colours as $flag_colour) { ?>
										<option <?= $row['flag_colour'] == $flag_colour ? 'selected' : '' ?> value="<?= $flag_colour ?>" style="background-color:#<?= $flag_colour ?>;"></option>
									<?php } ?>
								</select></div>
							<div class="col-sm-3"><input type='text' name='flag_start' value='<?= $ticket['flag_start'] ?>' class="form-control datepicker" style="display:none;"></div>
							<div class="col-sm-3"><input type='text' name='flag_end' value='<?= $ticket['flag_end'] ?>' class="form-control datepicker" style="display:none;"></div>
							<button class="btn brand-btn pull-right" name="flag_it" onclick="return false;" style="display:none;">Flag This</button>
							<button class="btn brand-btn pull-right" name="flag_cancel" onclick="return false;" style="display:none;">Cancel</button>
							<button class="btn brand-btn pull-right" name="flag_off" onclick="return false;" style="display:none;">Remove Flag</button>
							<div class="clearfix"></div>
						<?php } ?>
						<input type='file' name='document' value='' style="display:none;">
						<div class="select_users" style="display:none;">
							<select data-placeholder="Select Staff" multiple class="chosen-select-deselect"><option></option>
							<?php foreach($staff_list as $staff) { ?>
								<option value="<?= $staff['contactid'] ?>"><?= $staff['full_name'] ?></option>
							<?php } ?>
							</select>
							<button class="btn brand-btn pull-right send">Submit</button>
							<button class="btn brand-btn pull-right cancel">Cancel</button>
							<div class="clearfix"></div>
						</div>
						<div class="reminders" style="display:none;">
							<select data-placeholder="Select Staff" multiple class="chosen-select-deselect"><option></option>
							<?php foreach($staff_list as $staff) { ?>
								<option value="<?= $staff['contactid'] ?>"><?= $staff['full_name'] ?></option>
							<?php } ?>
							</select>
							<input type="text" class="datepicker form-control">
							<button class="btn brand-btn pull-right send">Submit</button>
							<button class="btn brand-btn pull-right cancel">Cancel</button>
							<div class="clearfix"></div>
						</div>
						<div class="track_time" style="display:none;">
							<input type="text" name="timer" style="float:left;" class="form-control timer" placeholder="0 sec" />
							<button class="btn brand-btn pull-right start">Start</button>
							<button class="btn brand-btn pull-right stop" style="display:none;">Stop</button>
							<div class="clearfix"></div>
						</div>
						<input type="text" name="time_add" style="display:none; margin-top: 2em;" class="form-control timepicker">
						<input type="text" name="time_track" class="datetimepicker form-control" style="display:none;">
						<div class="double-gap-top action-icons">
							<?php if($project_security['edit'] > 0) { ?>
								<img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-add-icon.png" class="cursor-hand inline-img" title="Assign To A <?= PROJECT_NOUN ?>" id="<?=$row['salesid']?>" onclick="openProjectDialog(this); return false;" /><?php
							} ?>
							<?php if($estimates_active > 0) { ?>
								<a href="<?= WEBSITE_URL; ?>/Sales/sale.php?p=details&id=<?= $row['salesid'] ?>&a=estimate#estimate"><img src="<?= WEBSITE_URL; ?>/img/icons/create_project.png" class="inline-img black-color" title="Add Estimate" /></a>
							<?php } ?>
							<?php if(!in_array('flag_manual',$quick_actions) && in_array('flag',$quick_actions)) { ?>
								<a href="Flag This!" onclick="flagLead(this); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-flag-icon.png" class="inline-img black-color" title="Flag This!" /></a>
							<?php } ?>
							<?php if(in_array('flag_manual',$quick_actions)) { ?>
								<a href="Flag This!" onclick="flagLeadManual(this); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-flag-icon.png" class="inline-img black-color" title="Flag This!" /></a>
							<?php } ?>
							<?php if(in_array('attach',$quick_actions)) { ?>
								<a href="Attach File" onclick="addDocument(this); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-attachment-icon.png" class="inline-img black-color" title="Attach File" /></a>
							<?php } ?>
							<?php if(in_array('reply',$quick_actions)) { ?>
								<a href="Add Note" onclick="$(this).closest('.info-block-detail').find('[name=notes]').show().focus(); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-reply-icon.png" class="inline-img black-color" title="Add Note" /></a>
							<?php } ?>
							<?php if(in_array('email',$quick_actions)) { ?>
								<a href="Send Email" onclick="sendEmail(this); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-email-icon.png" class="inline-img black-color" title="Send Email" /></a>
							<?php } ?>
							<?php if(in_array('reminder',$quick_actions)) { ?>
								<a href="Schedule Reminder" onclick="setReminder(this); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-reminder-icon.png" class="inline-img black-color" title="Schedule Reminder" /></a>
							<?php } ?>
							<?php if(in_array('time',$quick_actions)) { ?>
								<a href="Add Time" onclick="addTime(this); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-timer-icon.png" class="inline-img black-color" title="Add Time" /></a>
							<?php } ?>
							<?php if(in_array('timer',$quick_actions)) { ?>
								<a href="Track Time" onclick="trackTime(this); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-timer2-icon.png" class="inline-img black-color" title="Track Time" /></a>
							<?php } ?>
							<?php if(in_array('archive',$quick_actions)) { ?>
								<a href="#" id="sales_<?= $row['salesid']; ?>" data-salesid="<?= $row['salesid']; ?>" onclick="archive_sales_lead(this); $(this).closest('.info-block-detail').hide(); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-trash-icon.png" class="inline-img" title="Archive the Sales Lead" /></a>
							<?php } ?>
						</div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div><!-- .horizontal-block-container -->
            <div class="clearfix"></div>
        </div><!-- .main-screen-white --><?php
                
        $i++;
    } ?><?php

} else { ?>
    <div class="standard-dashboard-body-content">
        <h4>No Records Found.</h4>
    </div><?php
} ?>

</div>
<div class="clearfix"></div>

<?php if(basename($_SERVER['SCRIPT_FILENAME']) == 'status.php') { ?>
	<div style="display:none;"><?php include('../footer.php'); ?></div>
<?php } ?>