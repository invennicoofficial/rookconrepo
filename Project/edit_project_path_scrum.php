<?php 
if(!isset($security)) {
	$security = get_security($dbc, $tile);
	$strict_view = strictview_visible_function($dbc, 'project');
	if($strict_view > 0) {
		$security['edit'] = 0;
		$security['config'] = 0;
	}
}
$staff_list = sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `deleted`=0 AND `status` > 0"));
$project = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `project` WHERE `projectid`='$projectid'")); ?>
<script>
$(document).ready(function() {
	setActions();
	$(window).resize(function() {
		$('.double-scroller div').width($('.dashboard-container').get(0).scrollWidth);
		$('.double-scroller').off('scroll',doubleScroll).scroll(doubleScroll);
		$('.dashboard-container').off('scroll',setDoubleScroll).scroll(setDoubleScroll);
		if($(window).width() > 767 && $(window).innerHeight() - $($('ul.dashboard-list').first()).offset().top - 68 - ($('.dashboard-container').innerHeight() - $('.dashboard-container').prop('clientHeight')) > 250) {
			$('ul.dashboard-list').outerHeight($(window).innerHeight() - $($('ul.dashboard-list').first()).offset().top - 68 - ($('.dashboard-container').innerHeight() - $('.dashboard-container').prop('clientHeight')));
		} else {
			var height = 0;
			$('ul.dashboard-list').each(function() {
				height = $(this).height() > height ? $(this).height() : height;
			});
			$('ul.dashboard-list').outerHeight(height);
		}
	}).resize();
	$('select.path_select_onchange').change(function() {
		window.location.replace('?edit=<?= $_GET['edit'] ?>&tab=path&pathid='+this.value);
	});
});
function doubleScroll() {
	$('.dashboard-container').scrollLeft(this.scrollLeft);
}
function setDoubleScroll() {
	$('.double-scroller').scrollLeft(this.scrollLeft);
}
var keep_scrolling = '';
function setActions() {
	$('input,select,textarea').filter('[data-table]').off('change',saveField).change(saveField);
	$('.dashboard-list').sortable({
		connectWith: '.dashboard-list',
		sort: function(event) {
			var end_distance = window.innerWidth - event.clientX;
			var start_distance = event.clientX - $('.dashboard-container').offset().left;
			clearInterval(keep_scrolling);
			if(end_distance < 20) {
				keep_scrolling = setInterval(function() { $('.dashboard-container').scrollLeft($('.dashboard-container').scrollLeft() + 10); }, 10);
			} else if(start_distance < 20) {
				keep_scrolling = setInterval(function() { $('.dashboard-container').scrollLeft($('.dashboard-container').scrollLeft() - 10); }, 10);
			}
		},
		handle: '.milestone-handle',
		items: '.dashboard-item',
		update: function(event, element) {
			$.ajax({
				url: 'projects_ajax.php?action=project_fields',
				method: 'POST',
				data: {
					field: element.item.data('name'),
					value: element.item.closest('.dashboard-list').data('status'),
					table: element.item.data('table'),
					id: element.item.data('id'),
					id_field: element.item.data('id-field')
				}
			});
		}
	});

	$('.reply-icon').off('click').click(function() {
		var item = $(this).closest('.dashboard-item');
		item.find('[name=reply]').off('change').off('blur').show().focus().blur(function() {
			$(this).off('blur');
			$.ajax({
				url: 'projects_ajax.php?action=project_fields',
				method: 'POST',
				data: {
					mode: 'append',
					field: $(this).data('name'),
					value: this.value,
					table: item.data('table'),
					id: item.data('id'),
					id_field: item.data('id-field')
				},
				success: function(response) {
					item.find('h4').append(response);
				}
			});
			$(this).hide().val('');
		}).keyup(function(e) {
			if(e.which == 13) {
				$(this).blur();
			} else if(e.which == 27) {
				$(this).off('blur').hide();
			}
		});
	});
	$('.archive-icon').off('click').click(function() {
		var item = $(this).closest('.dashboard-item');
		$.ajax({
			url: 'projects_ajax.php?action=project_fields',
			method: 'POST',
			data: {
				field: 'deleted',
				value: 1,
				table: item.data('table'),
				id: item.data('id'),
				id_field: item.data('id-field')
			}
		});
		item.hide();
	});
	$('.flag-icon').off('click').click(function() {
		var item = $(this).closest('.dashboard-item');
		$.ajax({
			url: 'projects_ajax.php?action=project_actions',
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
	});
	$('.assign-icon').off('click').click(function() {
		var item = $(this).closest('.dashboard-item');
		item.find('.assign_milestone').show().find('select').off('change').change(function() {
			item.find('.assign_milestone').hide();
			$.ajax({
				url: 'projects_ajax.php?action=project_actions',
				method: 'POST',
				data: {
					field: 'external',
					value: this.value,
					table: item.data('table'),
					id: item.data('id'),
					id_field: item.data('id-field')
				},
				success: function(response) {
					item.find('h4').append(response);
				}
			});
		});
	});
	$('.time-icon').off('click').click(function() {
		var item = $(this).closest('.dashboard-item');
		item.find('.time-field').timepicker('option','onClose',function() {
			if(this.value != '') {
				$.ajax({
					url: 'projects_ajax.php?action=project_actions',
					method: 'POST',
					data: {
						field: this.name,
						value: this.value,
						table: $(this).data('table'),
						ref: item.data('table'),
						ref_id: item.data('id'),
						ref_id_field: item.data('id-field')
					},
					success: function(response) {
						item.find('h4').append(response);
					}
				});
				$(this).hide().val('');
			}
		}).focus();
	});
	$('.attach-icon').off('click').click(function() {
		var item = $(this).closest('.dashboard-item');
		item.find('[type=file]').off('change').change(function() {
			var fileData = new FormData();
			fileData.append('file',$(this)[0].files[0]);
			fileData.append('field','document');
			fileData.append('table',$(this).data('table'));
			fileData.append('folder',$(this).data('folder'));
			fileData.append('id',item.data('id'));
			fileData.append('id_field',item.data('id-field'));
			$.ajax({
				contentType: false,
				processData: false,
				method: "POST",
				url: "projects_ajax.php?action=project_actions",
				data: fileData,
				success: function(response) {
					var target = item.find('h4,p').last().after(response);
				}
			});
		}).click();
	});
	$('.reminder-icon').off('click').click(function() {
		var item = $(this).closest('.dashboard-item');
		item.find('[name=reminder]').change(function() {
			var reminder = $(this).val();
			item.find('iframe').off('load').load(function() {
				var iframe = $(this);
				iframe.show().height('18em');
				iframe.contents().find('.btn').click(function() {
					if($(this).closest('body').find('select').val() != '' && confirm('Are you sure you want to schedule reminders for the selected user(s)?')) {
						var users = [];
						$(this).closest('body').find('select option:selected').each(function() {
							users.push(this.value);
						});
						$.ajax({
							method: 'POST',
							url: 'projects_ajax.php?action=project_actions',
							data: {
								id: item.data('id'),
								id_field: item.data('id-field'),
								table: item.data('table'),
								field: 'reminder',
								value: reminder,
								users: users,
								ref_id: item.data('id'),
								ref_id_field: item.data('id-field')
							},
							success: function(result) {
								item.find('h4').append(result);
							}
						});
					}
					iframe.off('load').html('').hide();
					return false;
				});
			}).attr('src','../Staff/select_staff.php?target=reminder&multiple=true');
		}).focus();
	});
	$('.alert-icon').off('click').click(function() {
		var item = $(this).closest('.dashboard-item');
		item.find('iframe').off('load').load(function() {
			var iframe = $(this);
			iframe.show().height('18em');
			iframe.contents().find('.btn').click(function() {
				if($(this).closest('body').find('select').val() != '' && confirm('Are you sure you want to enable alerts for the selected user(s)?')) {
					var users = [];
					$(this).closest('body').find('select option:selected').each(function() {
						users.push(this.value);
					});
					$.ajax({
						method: 'POST',
						url: 'projects_ajax.php?action=project_actions',
						data: {
							id: item.data('id'),
							id_field: item.data('id-field'),
							table: item.data('table'),
							field: 'alert',
							value: users
						},
						success: function(result) { console.log(result); }
					});
				}
				iframe.off('load').html('').hide();
				return false;
			});
		}).attr('src','../Staff/select_staff.php?target=alert&multiple=true');
	});
	$('.email-icon').off('click').click(function() {
		var item = $(this).closest('.dashboard-item');
		item.find('iframe').off('load').load(function() {
			var iframe = $(this);
			iframe.show().height('18em');
			iframe.contents().find('.btn').click(function() {
				if($(this).closest('body').find('select').val() != '' && confirm('Are you sure you want to send an e-mail to the selected user(s)?')) {
					var users = [];
					$(this).closest('body').find('select option:selected').each(function() {
						users.push(this.value);
					});
					$.ajax({
						method: 'POST',
						url: 'projects_ajax.php?action=project_actions',
						data: {
							id: item.data('id'),
							id_field: item.data('id-field'),
							table: item.data('table'),
							field: 'email',
							value: users
						},
						success: function(result) { console.log(result.responseText); }
					});
				}
				iframe.off('load').html('').hide();
				return false;
			});
		}).attr('src','../Staff/select_staff.php?target=email&multiple=true');
	});
	$('.new_task').off('keyup').keyup(function(e) {
		if(e.which == 13) {
			$(this).blur();
		}
	});
}
</script>
<h3 class="pad-horizontal action-icons"><span class="pull-left"><?= PROJECT_NOUN ?> Scrum Board</span>
<?php if(in_array($_GET['tab'],['path','path_external_path','scrum_board']) && $security['edit'] > 0 && $pathid != 'AllSB') { ?>
	<div class="col-sm-4 pull-right path_select smaller" style="display:none;"><select class="chosen-select-deselect path_select_onchange" data-placeholder="Select <?= PROJECT_NOUN ?> Path">
		<option></option>
		<?php if(in_array('Scrum Board',$tab_config)) { ?><option <?= $_GET['tab'] == 'scrum_board' ? 'selected' : '' ?> value="SB">Scrum Board</option><?php } ?>
		<?php $paths = mysqli_query($dbc, "SELECT `project_path`, `project_path_milestone` FROM `project_path_milestone` WHERE `project_path` != '' AND `project_path_milestone` IN (".$project['project_path'].") ORDER BY `project_path`");
		while($path = mysqli_fetch_array($paths)) { ?>
			<option <?= $path['project_path_milestone'] == $pathid && $_GET['tab'] == 'path' ? 'selected' : '' ?> value="I|<?= $path['project_path_milestone'] ?>"><?= $path['project_path'] ?></option>
		<?php }
		$external_paths = mysqli_query($dbc, "SELECT `project_path`, `project_path_milestone` FROM `project_path_milestone` WHERE `project_path` != '' AND `project_path_milestone` IN (".$project['external_path'].") ORDER BY `project_path`");
		while($path = mysqli_fetch_array($external_paths)) { ?>
			<option <?= $path['project_path_milestone'] == $pathid && $_GET['tab'] == 'path_external_path' ? 'selected' : '' ?> value="E|<?= $path['project_path_milestone'] ?>">External: <?= $path['project_path'] ?></option>
		<?php } ?>
	</select></div>
	<img class="inline-img pull-right no-toggle black-color small" src="../img/project-path.png" title="Select the <?= PROJECT_NOUN ?> Path" onclick="$('.path_select').show(); $(this).hide();">
	<img class="inline-img pull-right no-toggle black-color small" src="../img/icons/ROOK-add-icon.png" title="Add / Remove <?= ($_GET['tab'] == 'path_external_path' ? 'External ' : '').PROJECT_NOUN ?> Path" onclick="overlayIFrameSlider('edit_project_path_select.php?projectid=<?= $projectid ?>&path=<?= $_GET['tab'] == 'path' ? 'project_path' : 'external_path' ?>','75%',true)">
<?php } ?></h3>
<div class="clearfix"></div>
<div class="double-scroller"><div></div></div>
<div class="has-dashboard form-horizontal dashboard-container" style="<?= $pathid == 'AllSB' ? 'overflow-y:hidden;' : '' ?>">
	<?php $status_list = explode(',',get_config($dbc, 'ticket_status'));
	$add_action = '';
	$action_title = '';
	if(in_array('Tickets',$tab_config)) {
		$add_action = "window.location.href='../Ticket/index.php?edit=0&projectid=".$projectid."&milestone_timeline=".urlencode($milestone)."&from=".urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI'])."';";
		$action_title = 'Add '.TICKET_NOUN;
	} else if(in_array('Tasks',$tab_config)) {
		$add_action = "overlayIFrameSlider('../Tasks/add_task.php?projectid=$projectid','50%',false);";
		$action_title = 'Add Task';
	}
	foreach($status_list as $i => $status) {
		$sql = "SELECT 'Ticket', `ticketid` FROM tickets WHERE projectid='$projectid' AND `deleted`=0 AND `status` != 'Archive' AND `status`='$status'";
		$milestone_items = mysqli_query($dbc, $sql); ?>
		<div class="<?= $pathid == 'AllSB' ? 'item-list' : 'dashboard-list' ?>" style="margin-bottom: -10px;">
			<a href="?edit=<?= $projectid ?>&tab=<?= $tab_id ?>" <?= $pathid == 'AllSB' ? 'onclick="return false;"' : '' ?>><div class="info-block-header"><h4><?= $status ?>
				<?= $add_action != '' && $security['edit'] > 0 && $pathid != 'AllSB' ? '<a href=""><img class="no-margin black-color inline-img pull-right" src="../img/icons/ROOK-add-icon.png" title="'.$action_title.'" onclick="'.$add_action.'return false;"></a>' : '' ?></h4>
			<div class="small"><?= mysqli_num_rows($milestone_items) ?></div></div></a>
			<ul class="<?= $pathid == 'AllSB' ? 'connectedChecklist full-width' : 'dashboard-list' ?>" data-status="<?= $status ?>">
				<?php while($item = mysqli_fetch_array($milestone_items)) {
					$type = $item[0];
					$label = $date = $link = $contents = $li_class = $flag_label = $item_external = '';
					$item = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `tickets` WHERE `ticketid`='".$item[1]."'"));
					$data = 'data-id="'.$item['ticketid'].'" data-table="tickets" data-name="status" data-id-field="ticketid"';
					$colour = $item['flag_colour'];
					$flag_label = $ticket_flag_names[$colour];
					$doc_table = "ticket_document";
					$doc_folder = "../Ticket/download/";
					$actions = (in_array('flag',$quick_actions) ? '<img src="'.WEBSITE_URL.'/img/icons/ROOK-flag-icon.png" class="inline-img flag-icon" title="Flag This!">' : '').
						(in_array('alert',$quick_actions) ? '<img src="'.WEBSITE_URL.'/img/icons/ROOK-alert-icon.png" class="inline-img alert-icon" title="Enable Alerts">' : '').
						(in_array('email',$quick_actions) ? '<img src="'.WEBSITE_URL.'/img/icons/ROOK-email-icon.png" class="inline-img email-icon" title="Send Email">' : '').
						(in_array('reminder',$quick_actions) ? '<img src="'.WEBSITE_URL.'/img/icons/ROOK-reminder-icon.png" class="inline-img reminder-icon" title="Schedule Reminder">' : '').
						(in_array('attach',$quick_actions) ? '<img src="'.WEBSITE_URL.'/img/icons/ROOK-attachment-icon.png" class="inline-img attach-icon" title="Attach File">' : '').
						(in_array('archive',$quick_actions) ? '<img src="'.WEBSITE_URL.'/img/icons/ROOK-trash-icon.png" class="inline-img archive-icon" title="Archive">' : '');
					$label = '<a href="../Ticket/index.php?edit='.$item['ticketid'].'&from='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).
						'">'.get_ticket_label($dbc, $item).'</a>';
					$date = $item['to_do_date'];
					$date_name = 'to_do_date';
					if($item['status'] == 'Internal QA') {
						$date = $item['internal_qa_date'];
						$date_name = 'internal_qa_date';
					} else if($item['status'] == 'Customer QA') {
						$date = $item['deliverable_date'];
						$date_name = 'deliverable_date';
					}
					$contents = '<div class="form-group">
							<label class="col-sm-4">Status:</label>
							<div class="col-sm-8 '.(!($security['edit'] > 0) ? 'readonly-block' : '').'">
								<select name="status" data-table="tickets" data-id="'.$item['ticketid'].'" data-id-field="ticketid" class="chosen-select-deselect" data-placeholder="Select a Status">
									<option></option>';
						foreach($status_list as $ticket_status) {
							$contents .= '<option '.($ticket_status == $item['status'] ? 'selected' : '').' value="'.$ticket_status.'">'.$ticket_status.'</option>';
						}
					$contents .= '</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4">Assigned:</label>
							<div class="col-sm-8 '.(!($security['edit'] > 0) ? 'readonly-block' : '').'">
								<input type="text" class="datepicker form-control" data-table="tickets" data-id-field="ticketid" data-id="'.$item['ticketid'].'" name="'.$date_name.'" value="'.$date.'">'.
								($date_name == 'to_do_date' ? '<input type="hidden" name="to_do_end_date" data-table="tickets" data-id-field="ticketid" data-id="'.$item['ticketid'].'">' : '').
							'</div>
						</div>';
					$contents .= '<div class="form-group">
							<label class="col-sm-4">Staff:</label>
							<div class="col-sm-8 '.(!($security['edit'] > 0) ? 'readonly-block' : '').'">
								<select name="contactid[]" multiple data-concat="," data-table="tickets" data-id="'.$item['ticketid'].'" data-id-field="ticketid" class="chosen-select-deselect" data-placeholder="Select Staff">
									<option></option>';
						foreach($staff_list as $staff) {
							$contents .= '<option '.(in_array($staff['contactid'],explode(',',$item['contactid'])) ? 'selected' : '').' value="'.$staff['contactid'].'">'.$staff['first_name'].' '.$staff['last_name'].'</option>';
						}
					$contents .= '</select></div>';
					foreach(array_unique(explode(',',$item['contactid'].','.$item['internal_qa_contactid'].','.$item['deliverable_contactid'])) as $assignid) {
						if($assignid > 0) {
							$contents .= '<span class="pull-left small col-sm-12">';
							$contents .= profile_id($dbc, $assignid, false).' Assigned to '.get_contact($dbc, $assignid);
							$contents .= '</span>';
						}
					}
					$contents .= '</div>'; ?>
					<li class="dashboard-item <?= $li_class ?>" <?= $data ?> data-colour="<?= $colour ?>" style="<?= $colour != '' ? 'background-color: #'.$colour.';' : '' ?>"><span class="flag-label"><?= $flag_label ?></span>
						<?php if($security['edit'] > 0) { ?>
							<div class="action-icons"><?= $actions ?><img class="pull-right milestone-handle" src="../img/icons/drag_handle.png" style="height: 1em; margin-top: 0.5em;"></div>
						<?php } ?>
						<h4><?= $label ?></h4>
						<?= $contents ?>
						<input type='text' name='reply' value='' class="form-control" style="display:none;">
						<input type='text' name='reminder' value='' class="form-control datepicker" style="border:0;height:0;margin:0;padding:0;width:0;">
						<div style="display:none;" class="assign_milestone"><select class="chosen-select-deselect"><option value="unassign">Unassigned</option>
							<?php foreach($external_path as $external_milestone) { ?>
								<option <?= $external_milestone == $item_external ? 'selected' : '' ?> value="<?= $external_milestone ?>"><?= $external_milestone ?></option>
							<?php } ?></select></div>
						<iframe style="display:none; width:100%;" src=""></iframe>
						<input type='file' name='document' value='' data-table="<?= $doc_table ?>" data-folder="<?= $doc_folder ?>" style="display:none;">
						<div class="clearfix"></div>
					</li>
				<?php } ?>
			</ul>
		</div>
	<?php } ?>
</div>
<div class="clearfix"></div>
