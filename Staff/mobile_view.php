<script type="text/javascript">
$(document).ready(function() {
    $('#profile_accordions .panel-heading').click(function() {
        setTimeout(loadPanel, 1000);
    });
});
function loadPanel() {
    $('#profile_accordions .panel-body').not(':visible').each(function() {
        $(this).html('Loading...');
    });
    $('#profile_accordions .panel-body:visible').each(function() {
        var panel = this;
        query_string = '';
        loadPanelAjax(query_string, panel);
    });
}
function loadPanelAnchor(sel) {
    var panel = $(sel).closest('.panel-body');
    var query_string = $(sel).attr('href').replace('?','&');
    loadPanelAjax(query_string, panel);
}
function changeDailyDateMobile(sel) {
    var daily_date = sel.value;
    var side_content = $('#side_content').val();
    var weekly_date = $('#weekly_date').val();
    var panel = $(sel).closest('.panel-body');
    var query_string = '&daily_date='+daily_date+'&side_content='+side_content+'&weekly_date='+weekly_date;
    var panel = $(sel).closest('.panel-body');
    loadPanelAjax(query_string, panel);
}
function changeGaoType(sel) {
    var goal_type = $(sel).val();
    var panel = $(sel).closest('.panel-body');
    var query_string = '&status='+goal_type
    loadPanelAjax(query_string, panel);
}
function changeScheduleDateMobile(sel) {
    var date = $(sel).val();
    var panel = $(sel).closest('.panel-body');
    var query_string = '&view=daily&date='+date;
    loadPanelAjax(query_string, panel);
}
function shiftChangeMobile(sel) {
    var panel = $(sel).closest('.panel-body');
    var query_string = '&view=daily&date='+$('[name="calendar_date"]').val();
    if($(sel).val() == 'NEW') {
        query_string += '&shiftid=NEW';
    } else {
        query_string += '&shiftid='+$(sel).val();
    }
    loadPanelAjax(query_string, panel);
}
function loadPanelAjax(query_string, panel) {
    var url_string = $(panel).data('url');
    var subtab = $(panel).data('subtab');
    $(panel).html('Loading...');
    $.ajax({
        url: '../Staff/'+url_string+'?contactid=<?= $_GET['contactid'] ?>&mobile_view=true&edit_contact=<?= $_GET['edit_contact'] ?>'+query_string,
        data: { subtab: subtab },
        method: 'POST',
        response: 'html',
        success: function(response) {
            $(panel).html(response);
            $(panel).find('.hide-titles-mob').removeClass('hide-titles-mob');
            $(panel).find('.tile-content').removeClass('tile-content');
            $(panel).find('.tile-sidebar,.tile-header,footer,header,#nav').hide();
            $(panel).find('.main-screen').css('height', '100%');
            $(panel).find('#shiftform').attr('action', '../Calendar/shifts.php?from_url=<?= $_SERVER['REQUEST_URI'] ?>');
            $(panel).find('[name="shiftid"]').removeAttr('onchange');
            $(panel).find('[name="shiftid"]').change(function() {
                shiftChangeMobile(this);
                return false;
            });
            $(panel).find('.set_date').removeAttr('onchange');
            $(panel).find('.set_date').change(function() {
                changeScheduleDateMobile(this);
                return false;
            });
            $(panel).find('[name="daily_date"]').removeAttr('onchange');
            $(panel).find('[name="daily_date"]').change(function() {
                changeDailyDateMobile(this);
                return false;
            });
            $(panel).find('.mobile-anchor').click(function() {
                setTimeout(loadPanelAnchor(this), 1000);
                return false;
            });
        }
    });
}
</script>
<?php
$field_tabs = ','.get_config($dbc, 'staff_field_subtabs').',';
$field_tabs .= 'ID Card,Software ID,Ticket,Project,History,';
 $mobile_fields = [
    'id_card' => ['ID Card','ID Card','id_card','staff_edit.php'],
    'staff_information' => ['Staff Information','Staff Information','staff_info','staff_edit.php'],
    'staff_address' => ['Staff Address','Staff Address','staff_address','staff_edit.php'],
    'employee_information' => ['Employee Information','Employee Information','employee','staff_edit.php'],
    'driver_information' => ['Driver Information','Driver Information','driver','staff_edit.php'],
    'direct_deposit_information' => ['Direct Deposit Information','Direct Deposit Information','direct_deposit','staff_edit.php'],
    'software_id' => ['Software ID','Software ID','software_access','staff_edit.php'],
    'social_media' => ['Social Media','Social Media','social','staff_edit.php'],
    'emergency' => ['Emergency','Emergency','emergency','staff_edit.php'],
    'health' => ['Health','Health & Safety','health','staff_edit.php'],
    'schedule' => ['Schedule','Staff Schedule','schedule','staff_schedule.php'],
    'hr' => ['HR','HR Record','hr_record','staff_edit.php'],
    'certificates' => ['Certificates','Certificates & Certifications','certificates','certificate.php'],
    'history' => ['History','History','history','staff_history.php']
];
?>
<div class="row show-on-mob" style="width: 100%;">
    <h2 class="show-on-mob" style="margin-top: 0;"><a href="staff.php">Staff</a>: <?= $contactid > 0 ? get_contact($dbc, $contactid) : 'Add New' ?></h2>
    <div class="main-screen" style="background-color: #fff">
        <?php if(!isset($_GET['view_only']) && !(vuaed_visible_function($dbc, 'staff') > 0) && $contactid == $_SESSION['contactid']) { ?><a href='<?= WEBSITE_URL ?>/Profile/my_profile.php?edit_contact=true&from_staff_tile=true' class="btn brand-btn pull-right gap-top gap-right">Edit My Profile</a><div class="clearfix"></div><?php } ?>
        <div id='profile_accordions' class='sidebar show-on-mob panel-group block-panels gap-top gap-left' style="width: 95%; padding: 0;">
            <?php foreach ($mobile_fields as $key => $field) {
                if ((strpos($field_tabs, ','.$field[0].',') !== FALSE || empty($field[0])) && (check_subtab_persmission($dbc, 'staff', ROLE, $field[2]) === TRUE || empty($field[2])) && !in_array($field[0], $subtabs_hidden)) { ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#profile_accordions" href="#collapse_<?= $key ?>">
                                    <?= $field[1] ?><span class="glyphicon glyphicon-plus"></span>
                                </a>
                            </h4>
                        </div>

                        <div id="collapse_<?= $key ?>" class="panel-collapse collapse">
                            <div class="panel-body" data-subtab="<?= $key ?>" data-url="<?= $field[3] ?>">
                                Loading...
                            </div>
                        </div>
                    </div>
                <?php }
            } ?>
        </div>
    </div>
</div>