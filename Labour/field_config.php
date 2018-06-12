<?php include_once ('../include.php');
checkAuthorised('labour');
if(empty($_GET['settings'])) {
    $_GET['settings'] = 'dashboard';
}
switch($_GET['settings']) {
    case 'dashboard':
        $field_title = 'Dashboard Fields';
        break;
    case 'fields':
        $field_title = 'Fields';
        break;
} ?>

<script type="text/javascript">
$(document).ready(function() {
    $('#mobile_tabs .panel-heading').click(loadPanel);
});
function loadPanel() {
    var panel = $(this).closest('.panel').find('.panel-body');
    panel.html('Loading...');
    $.ajax({
        url: panel.data('file-name'),
        method: 'POST',
        response: 'html',
        success: function(response) {
            panel.html(response);
        }
    });
}
</script>

<div class="show-on-mob panel-group block-panels col-xs-12 form-horizontal" id="mobile_tabs">
    <div class="panel panel-default">
        <div class="panel-heading mobile_load">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#mobile_tabs" href="#collapse_dashboard">
                    Dashboard Fields<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_dashboard" class="panel-collapse collapse">
            <div class="panel-body" data-file-name="field_config_dashboard.php">
                Loading...
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading mobile_load">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#mobile_tabs" href="#collapse_fields">
                    Fields<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_fields" class="panel-collapse collapse">
            <div class="panel-body" data-file-name="field_config_fields.php">
                Loading...
            </div>
        </div>
    </div>
</div>

<div class="sidebar standard-collapsible tile-sidebar hide-titles-mob">
    <ul>
        <a href="?"><li>Back to Dashboard</li></a>
        <a href="?settings=dashboard"><li <?= $_GET['settings'] == 'dashboard' ? 'class="active"' : '' ?>>Dashboard Fields</li></a>
        <a href="?settings=fields"><li <?= $_GET['settings'] == 'fields' ? 'class="active"' : '' ?>>Fields</li></a>
    </ul>
</div>

<div class="scale-to-fill has-main-screen hide-titles-mob">
    <div class="main-screen standard-body form-horizontal">
        <div class="standard-body-title">
            <h3><?= $field_title ?></h3>
        </div>
        <div class="standard-body-content" style="padding: 1em;">
            <?php if($_GET['settings'] == 'fields') {
                include('field_config_fields.php');
            } else {
                include('field_config_dashboard.php');
            } ?>
        </div><!-- .main-screen -->
    </div><!-- .row -->
</div><!-- .container -->