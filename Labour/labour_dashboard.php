<?php include_once ('../include.php');
checkAuthorised('labour');
$current_cat = $_GET['category'];
?>
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
    <div class="hide_on_iframe_overlay">
        <div class="panel panel-default">
            <div class="panel-heading mobile_load">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#mobile_tabs" href="#collapse_all">
                        All Labour<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_all" class="panel-collapse collapse">
                <div class="panel-body" data-file-name="labour_dashboard_inc.php">
                    Loading...
                </div>
            </div>
        </div>

        <?php $each_tab = mysqli_fetch_all(mysqli_query($dbc, "SELECT DISTINCT `labour_type` FROM `labour` WHERE `deleted` = 0 AND IFNULL(`labour_type`,'') != '' ORDER BY `labour_type`"),MYSQLI_ASSOC);
        $collapse_i = 0;
        foreach ($each_tab as $cat_tab) { ?>
            <div class="panel panel-default">
                <div class="panel-heading mobile_load">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#mobile_tabs" href="#collapse_<?= $collapse_i ?>">
                            <?= $cat_tab['labour_type'] ?><span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_<?= $collapse_i ?>" class="panel-collapse collapse">
                    <div class="panel-body" data-file-name="labour_dashboard_inc.php?category=<?= $cat_tab['labour_type'] ?>">
                        Loading...
                    </div>
                </div>
            </div>
            <?php $collapse_i++;
        } ?>
    </div>
</div>

<div class="tile-sidebar sidebar hide-titles-mob standard-collapsible">
    <?php include('../Labour/tile_sidebar.php'); ?>
</div>

<div class="scale-to-fill has-main-screen hide-titles-mob">
    <div class="main-screen standard-body form-horizontal">

        <div class="standard-body-title">
            <h3><?= empty($current_cat) ? 'All Labour' : $current_cat ?></h3>
        </div>

        <div class="standard-body-content" style="padding: 1em;">
            <?php include('../Labour/labour_dashboard_inc.php'); ?>
        </div>
    </div>
</div>