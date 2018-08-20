<!-- Sales Lead Details / Add/Edit Sales Lead -->
<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form"><?php
    $lead_created_by        = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);
    $primary_staff          = $_SESSION['contactid'];
    $share_lead             = '';
    $businessid             = '';
    $contactid              = '';
    $primary_number         = '';
    $email_address          = '';
    $lead_value             = '';
    $estimated_close_date   = '';
    $serviceid              = '';
    $productid              = '';
    $marketingmaterialid    = '';
    $lead_source            = '';
    $next_action            = '';
    $new_reminder           = '';
    $status                 = '';
    $flag_colour = $flag_label = '';
    $flag_colours = explode(',', get_config($dbc, "ticket_colour_flags"));
    $flag_labels = explode('#*#', get_config($dbc, "ticket_colour_flag_names"));

    if ( !empty($_GET['businessid']) ) {
        $businessid = $_GET['businessid'];
    }
    
    if ( !empty($salesid) ) {
        $get_contact = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `sales` WHERE `salesid`='{$salesid}'"));

        $lead_created_by        = $get_contact['lead_created_by'];
        $primary_staff          = $get_contact['primary_staff'];
        $share_lead             = $get_contact['share_lead'];
        $businessid             = $get_contact['businessid'];
        $contactid              = $get_contact['contactid'];
        $primary_number         = $get_contact['primary_number'];
        $email_address          = $get_contact['email_address'];
        $lead_value             = $get_contact['lead_value'];
        $estimated_close_date   = $get_contact['estimated_close_date'];
        $serviceid              = $get_contact['serviceid'];
        $productid              = $get_contact['productid'];
        $marketingmaterialid    = $get_contact['marketingmaterialid'];
        $lead_source            = $get_contact['lead_source'];
        $next_action            = $get_contact['next_action'];
        $new_reminder           = $get_contact['new_reminder'];
        $status                 = $get_contact['status'];
        $region                 = $get_contact['region'];
        $location               = $get_contact['location'];
        $classification         = $get_contact['classification'];
		if(!empty($get_contact['flag_label'])) {
			$flag_colour = $get_contact['flag_colour'];
			$flag_label = $get_contact['flag_label'];
		} else if(!empty($get_contact['flag_colour'])) {
			$flag_colour = $get_contact['flag_colour'];
			$flag_label = $flag_labels[array_search($get_contact['flag_colour'], $flag_colours)];
		}
        
        $get_lead_source = mysqli_fetch_array(mysqli_query($dbc, "SELECT `contactid`, `businessid`, `referred_contactid` FROM `contacts` WHERE (`referred_contactid` IN ($contactid) OR `referred_contactid` IN ($businessid))"));
        $lead_source_cid = $get_lead_source['contactid'];
        $lead_source_bid = $get_lead_source['businessid'];
    } ?>
    <input type="hidden" id="salesid" name="salesid" value="<?= $salesid ?>" />
    <script>
    $(document).ready(function() {
        init_page();
    });
    function init_page() {
        destroyInputs();
        $('[data-table]').off('change',saveField).change(saveField);
        initInputs();
    }
    function flagLead(sel) {
        $.ajax({
            url: 'sales_ajax_all.php?action=flag_colour',
            method: 'POST',
            data: {
                field: 'flag_colour',
                value: $('.flag-label').data('colour'),
                table: 'sales',
                id: '<?= $salesid ?>',
                id_field: 'salesid'
            },
            success: function(response) {
                $('.flag-label').data('colour',response.substr(0,6));
                $('.standard-body-title').css('background-color','#'+response.substr(0,6));
                $('.flag-label').html(response.substr(6));
            }
        });
    }

    function flagLeadManual(sel) {
        $('.flag_field_labels,[name=label],[name=colour],[name=flag_it],[name=flag_cancel],[name=flag_off],[name=flag_start],[name=flag_end]').show();
        $('[name=flag_cancel]').off('click').click(function() {
            $('.flag_field_labels,[name=label],[name=colour],[name=flag_it],[name=flag_cancel],[name=flag_off],[name=flag_start],[name=flag_end]').hide();
            return false;
        });
        $('[name=flag_off]').off('click').click(function() {
            $('[name=colour]').val('');
            $('[name=label]').val('');
            $('[name=flag_start]').val('');
            $('[name=flag_end]').val('');
            $('[name=flag_it]').click();
            return false;
        });
        $('[name=flag_it]').off('click').click(function() {
            $.ajax({
                url: 'sales_ajax_all.php?action=manual_flag_colour',
                method: 'POST',
                data: {
                    field: 'manual_flag_colour',
                    value: $('[name=colour]').val(),
                    table: 'sales',
                    label: $('[name=label]').val(),
                    start: $('[name=flag_start]').val(),
                    end: $('[name=flag_end]').val(),
                    id: '<?= $salesid ?>',
                    id_field: 'salesid'
                }
            });
            $('.flag_field_labels,[name=label],[name=colour],[name=flag_it],[name=flag_cancel],[name=flag_off],[name=flag_start],[name=flag_end]').hide();
            $('.flag_label').data('colour',$('[name=colour]').val());
            $('.standard-body-title').css('background-color','#'+$('[name=colour]').val());
            $('.flag-label').text($('[name=label]').val());
            return false;
        });
    }

    function setReminder(sel) {
        $('.reminders').show();
        $('.send').click(function() {
            $.post('sales_ajax_all.php?action=set_reminder', {
                    user: $('.reminders select').val().join(','),
                    date: $('.reminders input.datepicker').val(),
                    id: '<?= $salesid ?>'
                });
            $('.reminders').hide();
            $('.reminders input.datepicker').val('');
            $('.reminders option').removeAttr('selected');
            $('.reminders select').trigger('change.select2');
        });
        $('.cancel').click(function() {
            $('.reminders').hide();
            $('.reminders input').val('');
            $('.reminders select').val('').trigger('change.select2');
        });
    }

    function sendEmail(sel) {
        var salesid = '<?= $salesid ?>';
        overlayIFrameSlider('<?= WEBSITE_URL ?>/quick_action_email.php?tile=sales&salesid='+salesid,'auto',false,true)
    }
    </script>

    <div class="main-screen-white standard-body" style="padding-left: 0; padding-right: 0; border: none;">
        <div class="standard-body-title" style="<?= empty($flag_colour) ? '' : 'background-color:#'.$flag_colour.';' ?> ">
            <h3><?= ( !empty($salesid) ) ? 'Edit' : 'Add'; ?> <?= SALES_NOUN ?> #<?= $salesid ?>
                <?php $quick_actions = explode(',',get_config($dbc, 'quick_action_icons')); ?>
                <?php if(in_array('reminder',$quick_actions)) { ?>
                    <a href="Schedule Reminder" onclick="setReminder(this); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-reminder-icon.png" class="inline-img pull-right black-color" title="Schedule Reminder" /></a>
                <?php } ?>
                <?php if(in_array('email',$quick_actions)) { ?>
                    <a href="Send Email" onclick="sendEmail(this); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-email-icon.png" class="inline-img pull-right black-color" title="Send Email" /></a>
                <?php } ?>
                <?php if(in_array('flag_manual',$quick_actions)) { ?>
                    <a href="Flag This!" onclick="flagLeadManual(this); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-flag-icon.png" class="inline-img pull-right black-color" title="Flag This!" /></a>
                <?php } ?>
                <?php if(!in_array('flag_manual',$quick_actions) && in_array('flag',$quick_actions)) { ?>
                    <a href="Flag This!" onclick="flagLead(this); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-flag-icon.png" class="inline-img pull-right black-color" title="Flag This!" /></a>
                <?php } ?>
                <div class="clearfix"></div>
                <?php if(in_array('flag_manual',$quick_actions)) {
                    $colours = $flag_colours; ?>
                    <span class="col-sm-3 text-center flag_field_labels" style="display:none;">Label</span><span class="col-sm-3 text-center flag_field_labels" style="display:none;">Colour</span><span class="col-sm-3 text-center flag_field_labels" style="display:none;">Start Date</span><span class="col-sm-3 text-center flag_field_labels" style="display:none;">End Date</span>
                    <div class="col-sm-3"><input type='text' name='label' value='<?= $flag_label ?>' class="form-control" style="display:none;"></div>
                    <div class="col-sm-3"><select name='colour' class="form-control" style="display:none;background-color:#<?= $ticket['flag_colour'] ?>;font-weight:bold;" onchange="$(this).css('background-color','#'+$(this).find('option:selected').val());">
                            <option value="" style="background-color:#FFFFFF;">No Flag</option>
                            <?php foreach($colours as $colour) { ?>
                                <option <?= $flag_colour == $colour ? 'selected' : '' ?> value="<?= $colour ?>" style="background-color:#<?= $colour ?>;"></option>
                            <?php } ?>
                        </select></div>
                    <div class="col-sm-3"><input type='text' name='flag_start' value='<?= $ticket['flag_start'] ?>' class="form-control datepicker" style="display:none;"></div>
                    <div class="col-sm-3"><input type='text' name='flag_end' value='<?= $ticket['flag_end'] ?>' class="form-control datepicker" style="display:none;"></div>
                    <button class="btn brand-btn pull-right" name="flag_it" onclick="return false;" style="display:none;">Flag This</button>
                    <button class="btn brand-btn pull-right" name="flag_cancel" onclick="return false;" style="display:none;">Cancel</button>
                    <button class="btn brand-btn pull-right" name="flag_off" onclick="return false;" style="display:none;">Remove Flag</button>
                    <div class="clearfix"></div>
                <?php } ?>
                <div class="reminders" style="display:none;">
                    <select data-placeholder="Select Staff" multiple class="chosen-select-deselect"><option></option>
                    <?php foreach(sort_contacts_query($dbc->query("SELECT contactid, first_name, last_name FROM contacts WHERE deleted=0 AND status>0 AND category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY."")) as $staff) { ?>
                        <option value="<?= $staff['contactid'] ?>"><?= $staff['full_name'] ?></option>
                    <?php } ?>
                    </select>
                    <input type="text" class="datepicker form-control">
                    <button class="btn brand-btn pull-right send">Submit</button>
                    <button class="btn brand-btn pull-right cancel">Cancel</button>
                    <div class="clearfix"></div>
                </div>
                <span class="flag-label" data-colour="<?= $flag_colour ?>"><?= $flag_label ?></span>
            </h3>
        </div>

        <div class="standard-body-content"><?php
            
            if (strpos($value_config, ',Staff Information,') !== false) {
				echo "<div>";
                include('details_staff_info.php');
				echo "</div><hr>";
            }
            if (strpos($value_config, ',Lead Information,') !== false) {
				echo "<div>";
                include('details_lead_info.php');
				echo "</div><hr>";
            }
            if (strpos($value_config, ',Service,') !== false) {
				echo "<div>";
                include('details_services.php');
                echo "</div><hr>";
            }
            if (strpos($value_config, ',Products,') !== false) { 
				echo "<div>";
                include('details_products.php');
				echo "</div><hr>";
            }
            if (strpos($value_config, ',Lead Source,') !== false) { 
				echo "<div>";
                include('details_lead_source.php');
				echo "</div><hr>";
            }
            if (strpos($value_config, ',Reference Documents,') !== false) { 
				echo "<div>";
                include('details_ref_docs.php');
				echo "</div><hr>";
            }
            if (strpos($value_config, ',Marketing Material,') !== false) { 
				echo "<div>";
                include('details_marketing.php');
				echo "</div><hr>";
            }
            if (strpos($value_config, ',Information Gathering,') !== false) { 
				echo "<div>";
                include('details_info_gathering.php');
				echo "</div><hr>";
            }
            if (strpos($value_config, ',Estimate,') !== false) { 
				echo "<div>";
                include('details_estimate.php');
				echo "</div><hr>";
            }
            if (strpos($value_config, ',Next Action,') !== false) { 
				echo "<div>";
                include('details_next_action.php');
				echo "</div><hr>";
            }
            if (strpos($value_config, ',Lead Status,') !== false) { 
				echo "<div>";
                include('details_lead_status.php');
				echo "</div><hr>";
            }
            if (strpos($value_config, ',Lead Notes,') !== false) { 
				echo "<div>";
                include('details_lead_notes.php');
				echo "</div><hr>";
            }
            if (strpos($value_config, ',Tasks,') !== false) {
				echo "<div>"; 
                include('details_tasks.php');
				echo "</div><hr>";
            }
            if (strpos($value_config, ',Time,') !== false) { 
				echo "<div>";
                include('details_time.php');
				echo "</div><hr>";
            }
            if (strpos($value_config, ',History,') !== false) { 
				echo "<div>";
                include('details_history.php');
                echo "</div>";
            } ?>
            
            <div class="pull-right gap-top gap-right gap-bottom">
                <a href="index.php" class="btn brand-btn">Cancel</a>
                <button type="submit" name="add_sales" value="Submit" class="btn brand-btn">Save</button>
            </div>
        </div><!-- .preview-block-container -->
    </div><!-- .main-screen-white -->
    
    <div class="clearfix"></div>
    
</form>