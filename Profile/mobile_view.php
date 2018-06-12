<script type="text/javascript">
$(document).ready(function() {
    $('#profile_accordions .panel-body').html('Loading...');
    $('#profile_accordions .panel-heading').on('touchstart',loadPanel).click(loadPanel);
});
function loadPanel() {
	loadPanelAjax('', $(this).closest('.panel').find('.panel-body'));
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
        url: '../Profile/'+url_string+'?mobile_view=true&edit_contact=<?= $_GET['edit_contact'] ?>'+query_string,
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
				loadPanelAnchor(this);
                return false;
            });
        }
    });
}
</script>
<?php
$field_tabs = ','.get_config($dbc, 'staff_field_subtabs').',';
if(strpos($field_tabs,',Software ID,' === FALSE)) {
    $field_tabs .= 'Software ID,';
}
 $mobile_fields = [
    'staff_information' => ['Staff Information','Staff Information','staff_information','my_profile.php'],
    'staff_address' => ['Staff Address','Staff Address','staff_address','my_profile.php'],
    'employee_information' => ['Employee Information','Employee Information','employee_information','my_profile.php'],
    'driver_information' => ['Driver Information','Driver Information','driver_information','my_profile.php'],
    'direct_deposit_information' => ['Direct Deposit Information','Direct Deposit Information','direct_deposit_information','my_profile.php'],
    'software_id' => ['','Software ID','','my_profile.php'],
    'social_media' => ['Social Media','Social Media','social_media','my_profile.php'],
    'emergency' => ['Emergency','Emergency','emergency','my_profile.php'],
    'health' => ['Health','Health & Safety','health','my_profile.php'],
    'schedule' => ['Schedule','Staff Schedule','schedule','staff_schedule.php'],
    'hr' => ['HR','HR Record','hr','my_profile.php'],
    'certificates' => ['Certificates','Certificates & Certifications','certificates','my_certificate.php'],
    'goals' => ['Goals and Objectives','Goals & Objectives','goals','gao_goal.php']
];
?>
<div class="row show-on-mob" style="width: 100%;">
    <h2 class="show-on-mob" style="margin-top: 0;">My Profile</h2>
    <a href="?edit_contact=<?= $_GET['edit_contact'] == 'true' ? '' : 'true' ?>" class="btn brand-btn pull-right"><?= $_GET['edit_contact'] == 'true' ? 'View' : 'Edit' ?></a>
	<a href="?end_day=end" class="btn brand-btn pull-right">End Day</a>
    <div class="main-screen" style="background-color: #fff">
        <div id='profile_accordions' class='sidebar show-on-mob panel-group block-panels gap-top gap-left' style="width: 95%; padding: 0;">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#profile_accordions" href="#collapse_id_card">
                            ID Card<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_id_card" class="panel-collapse collapse">
                    <div class="panel-body" data-subtab="id_card" data-url="my_profile.php">
                        Loading...
                    </div>
                </div>
            </div>
            <?php foreach ($mobile_fields as $key => $field) {
                if ((strpos($field_tabs, ','.$field[0].',') !== FALSE || empty($field[0])) && (check_subtab_persmission($dbc, 'profile', ROLE, $field[2]) === TRUE || empty($field[2]))) { ?>
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