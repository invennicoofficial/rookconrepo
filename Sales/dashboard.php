<script>
$(document).ready(function() {
	$('.main-screen-white').sortable({
		items: '.info-block-detail',
		handle: '.lead-handle',
		update: function(event, element) {
			$.ajax({
				url: 'sales_ajax_all.php?fill=changeLeadStatus&salesid='+element.item.data('id')+'&status='+element.item.closest('.info-block').data('status'),
				success: function() {
					window.location.reload();
				}
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

function openProjectDialog(sel) {
    var salesid = sel.id;
    $('#dialog_choose_project').dialog({
        resizable: false,
        height: "auto",
        width: ($(window).width() <= 500 ? $(window).width() : 500),
        modal: true,
        buttons: {
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
</script>
<!-- Dialog -->
<div id="dialog_choose_project" title="Select <?= PROJECT_NOUN ?> to Assign" style="display:none;">
    <div class="form-group">
        <label class="col-sm-4 control-label"><?= PROJECT_TILE ?>:</label>
        <div class="col-sm-8">
            <select name="projectid" data-placeholder="Select <?= PROJECT_NOUN ?>" class="chosen-select-deselect form-control">
                <option></option><?php
                $get_projects = mysqli_query($dbc, "SELECT projectid, project_name FROM project WHERE project_name<>'' AND deleted=0 ORDER BY project_name");
                if ($get_projects->num_rows>0) {
                    while ($row_project=mysqli_fetch_assoc($get_projects)) { ?>
                        <option value="<?=$row_project['projectid']?>"><?=$row_project['project_name']?></option><?php
                    }
                } ?>
            </select>
        </div>
    </div>
</div>
<!-- Sales Dashboard -->
<div class="main-screen-white horizontal-scroll no-overflow-y dashboard-container" style="height:95%"><?php
	$project_security = get_security($dbc, 'project');
	$estimates_active = tile_enabled($dbc, 'estimate')['user_enabled'];
    foreach ( explode(',', $statuses) as $status ) { ?>
        <div class="col-xs-12 col-sm-6 col-md-4 gap-top info-block-container">
            <div class="info-block" data-status="<?= $status ?>">
                <a href="?p=filter&s=<?= $status ?>"><div class="info-block-header">
                    <h4><?= $status; ?></h4><?php
                    $count = mysqli_fetch_assoc ( mysqli_query($dbc, "SELECT COUNT(`status`) AS `count` FROM `sales` WHERE `status`='{$status}' AND `deleted`=0" . $query_mod) );
                    echo '<div class="info-block-small">' . $count['count'] . '</div>'; ?>
                </div></a>
                <div class="info-block-details padded"><?php
                    $result = mysqli_query($dbc, "SELECT * FROM `sales` WHERE `status`='{$status}' AND `deleted`=0" . $query_mod);
					$lead_count = 0;
                    if ( $result->num_rows > 0 ) {
                        while ( $row=mysqli_fetch_assoc($result) ) {
							$lead_count++; ?>
                            <div class="info-block-detail" data-id="<?= $row['salesid'] ?>" style="<?= $lead_count > 10 ? 'display: none;' : '' ?>" data-searchable="<?= get_client($dbc, $row['businessid']); ?> <?= get_contact($dbc, $row['contactid']); ?>">
                                <a href="sale.php?p=preview&id=<?= $row['salesid'] ?>"><div class="row set-row-height">
                                    <div class="col-sm-12"><?= get_client($dbc, $row['businessid']); ?><img class="inline-img" src="../img/icons/ROOK-edit-icon.png">
										<b class="pull-right"><?= '$' . ($row['lead_value'] > 0) ? number_format($row['lead_value'], 2) : '0:00' ; ?></b></div>
                                </div>
                                
                                <div class="row set-row-height">
                                    <div class="col-sm-12"><?php
                                        $contacts = '';
                                        foreach ( explode(',', $row['contactid']) as $contact ) {
                                            if ( get_contact($dbc, $contact) != '-' ) {
                                                $contacts .= get_contact($dbc, $contact) . ', ';
                                            }
                                        }
                                        echo rtrim($contacts, ', '); ?>
										<img src="../img/icons/drag_handle.png" class="inline-img pull-right lead-handle" />
                                    </div>
                                </div></a>
                                
                                <div class="row set-row-height">
                                    <div class="col-sm-5">Status:</div>
                                    <div class="col-sm-7">
										<?php if($approvals > 0 || $status != 'Pending') { ?>
											<select name="status" class="chosen-select-deselect form-control" id="ssid_<?= $row['salesid'] ?>">
												<option value=""></option><?php
												foreach ( explode(',', $statuses) as $status_list ) {
													$selected = ($status_list==$status) ? 'selected="selected"' : '';
													echo '<option '. $selected .' value="'. $status_list .'">'. $status_list .'</li>';
												} ?>
											</select>
										<?php } else {
											echo $status;
										} ?>
                                    </div>
                                </div>
                                
                                <div class="row set-row-height">
                                    <div class="col-sm-5">Next Action:</div>
                                    <div class="col-sm-7">
                                        <select name="next_action" class="chosen-select-deselect form-control" id="nsid_<?= $row['salesid'] ?>">
                                            <option value=""></option><?php
                                            foreach ( explode(',', $next_actions) as $next_action ) {
                                                $selected = ($next_action==$row['next_action']) ? 'selected="selected"' : '';
                                                echo '<option '. $selected .' value="'. $next_action .'">'. $next_action .'</li>';
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row set-row-height">
                                    <div class="col-sm-5">Follow Up:</div>
                                    <div class="col-sm-7"><input type="text" name="follow_up" value="<?= $row['new_reminder'] ?>" class="form-control datepicker" onchange="changeLeadFollowUpDate(this);" id="fsid_<?= $row['salesid'] ?>" /></div>
                                </div>
                                
                                <input type="text" class="form-control gap-top" name="notes" id="notes" value="" style="display:none;" data-table="sales_notes" data-salesid="<?= $row['salesid']; ?>" onkeypress="javascript:if(event.keyCode==13){ saveNote(this); $(this).val('').hide(); };" onblur="saveNote(this); $(this).val('').hide();">
                                <div class="double-gap-top action-icons">
                                    <a href="<?= WEBSITE_URL; ?>/Sales/sale.php?p=details"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-add-icon.png" class="inline-img" title="Add New Sales Lead" /></a><?php
                                    if($project_security['edit'] > 0) { ?>
                                        <img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-add-icon.png" class="cursor-hand inline-img" title="Create <?= PROJECT_NOUN ?>" id="<?=$row['salesid']?>" data-contactid="<?=$row['contactid']?>" onclick="createProject(this); return false;" />
                                        <img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-add-icon.png" class="cursor-hand inline-img" title="Assign To A <?= PROJECT_NOUN ?>" id="<?=$row['salesid']?>" onclick="openProjectDialog(this); return false;" /><?php
                                    } ?>
									<?php if($estimates_active > 0) { ?>
										<a href="<?= WEBSITE_URL; ?>/Sales/sale.php?p=details&id=<?= $row['salesid'] ?>&a=estimate#estimate"><img src="<?= WEBSITE_URL; ?>/img/icons/create_project.png" class="inline-img black-color" title="Add Estimate" /></a>
									<?php } ?>
                                    <a href="Add Note" onclick="$(this).closest('.info-block-detail').find('[name=notes]').show().focus(); return false;"><img src="<?= WEBSITE_URL; ?>/img/notepad-icon-blue.png" class="inline-img black-color" title="Add Note" /></a>
                                    <a href="#" id="sales_<?= $row['salesid']; ?>" data-salesid="<?= $row['salesid']; ?>" onclick="archive_sales_lead(this); $(this).closest('.info-block-detail').hide(); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-trash-icon.png" class="inline-img" title="Archive the Sales Lead" /></a>
                                </div>
                            </div><?php
                        } ?>
                    <?php } else { ?>
                        <div class="info-block-detail">No <?= strtolower($status); ?> sales leads.</div><?php
                    } ?>
                </div>
            </div>
        </div><?php
    } ?>
    <div class="clearfix"></div>
</div><!-- .main-screen-white -->