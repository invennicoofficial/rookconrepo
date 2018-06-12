<?php
    $tmp = $contactid;
    $contactid = $_GET['edit'];
    if (empty($_GET['view_checklist']) && empty($_GET['edit_checklist'])) {
    echo '<div class="pull-right not_filter">
            <a href="'.$_SERVER['REQUEST_URI'].'&edit_checklist=NEW" class="btn brand-btn mobile-block gap-bottom pull-right">Add Checklist</a>
            <span class="popover-examples list-inline pull-right" style="margin:5px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add a Checklist."><img src="'.WEBSITE_URL.'/img/info.png" width="20"></a></span>
        </div>';
    }
    echo '<div class="main-screen col-sm-9">';
    if (!empty($_GET['view_checklist'])) {
        include_once('view_checklist.php');
    } else if (!empty($_GET['edit_checklist'])) {
        include_once('edit_checklist.php');
    } else {
        include_once('list_checklists.php');
    }
    echo '</div>';
    $contactid = $tmp;
?>