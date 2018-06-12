<?php
/*
Inventory Listing
*/
include ('../include.php');
error_reporting(0);
?>
<script type="text/javascript" src="tasks.js"></script>
<style type='text/css'>
.ui-state-disabled  { pointer-events: none !important; }
</style>
</head>
<body>
<?php
$contactide = $_SESSION['contactid'];
$get_table_orient = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM contacts WHERE contactid='$contactide'"));
$check_table_orient = $get_table_orient['horizontal_communication'];
?>
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
  $(".connectedSortable").width(maxWidth).height(maxHeight);
});
$( '.connectedSortable' ).each(function () {
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
});

</script>
<?php include_once ('../navigation.php');
checkAuthorised('tasks');
?>
<div class="container">
	<div class="row hide_on_iframe">

    <h1 class="single-pad-bottom pull-left">My Tasks</h1>
    <?php
    if(config_visible_function($dbc, 'contact') == 1) {
        //echo '<a href="field_config_tasks.php?type=tab" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br>';
    }
    echo '<br><a href="field_config_project_manage.php?category=how_to" class="pull-right"><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
	echo '<span class="popover-examples list-inline"><a class="pull-right" style="margin:-5px 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here to add/remove your task boards."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';

	echo '<div class="clearfix"></div>';

    echo '<div class="tab-container"><div class="pull-left tab"><span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to see your personal task board for the day."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
	echo "<a href='tasks.php?category=All'><button type='button' class='btn brand-btn mobile-block'>My Tasks</button></a></div>";

	echo '<div class="pull-left tab"><span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to see everyone\'s tasks for the day."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
    echo "<a href='scrum_tasks.php?category=All'><button type='button' class='btn brand-btn mobile-block active_tab'>Company Tasks</button></a></div>";

	echo '<div class="pull-left tab"><span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to see a specific community."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
	echo "<a href='community_tasks.php?category=All'><button type='button' class='btn brand-btn mobile-block'>Community Tasks</button></a></div></div>";

	echo '<div class="clearfix"></div>';

    echo '<br><br><div class="mobile-100-container"><a href="add_tasklist.php" class="adder btn brand-btn pull-right mobile-100-pull-right" style="width:auto;">Add Task</a></div>';

	echo '<div class="pull-left tab"><span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="1 of the task from this taskboard have not assigned todo date or to do date gone witout task done."><img class="pull-righ1t" src="'.WEBSITE_URL.'/img/alert.png" border="0" alt="">&nbsp;&nbsp;<img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>
    <span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Task do not have to do date or to do date gone witout task done."><img src="'.WEBSITE_URL.'/img/icons/thumb_down.png" border="0" alt="">&nbsp;&nbsp;<img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>
    <span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Task have to do date or its done."><img src="'.WEBSITE_URL.'/img/icons/thumb_up.png" border="0" alt="">&nbsp;&nbsp;<img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>
    ';
    echo "</div><br><br>";

    ?>

	<input type='hidden' value='<?php echo $contactide; ?>' class='contacterid'>
	<div class="col-sm-2">
		<span style='padding:5px; font-weight:bold;'>Vertical View: </span><input onclick="handleClick(this);" type='radio' style='width:20px; height:20px;' <?php if($check_table_orient !== 1) { echo 'checked'; } ?> name='horizo_vert' class='horizo_vert' value=''>
	</div>
	<div class="col-sm-3">
		<span style='padding:5px; font-weight:bold;'>Horizontal View (Mobile): </span><input onclick="handleClick(this);" <?php if($check_table_orient == 1) { echo 'checked'; } ?> type='radio' style='width:20px; height:20px;' name='horizo_vert' class='horizo_vert' value='1'>
	</div><br><br>
	<div class="clearfix"></div>
	<div class="clearfix"></div>

    <form name="form_sites" method="post" action="" class="form-inline" role="form">
        <?php

        $category = $_GET['category'];
        $contactid = $_SESSION['contactid'];
		echo '<div class="mobile-100-container">';
        $result = mysqli_query($dbc, "SELECT * FROM task_board WHERE board_security='Company' AND company_staff_sharing LIKE '%," . $contactid . ",%'");
        while($row = mysqli_fetch_array($result)) {
            $active_daily = '';
            if((!empty($_GET['category'])) && ($_GET['category'] == $row['taskboardid'])) {
                $active_daily = 'active_tab';
            }

            $tid = $row['taskboardid'];
            $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(tasklistid) AS total_unread FROM tasklist WHERE task_board = '$tid' AND (DATE(`archived_date`) >= (DATE(NOW() - INTERVAL 3 DAY)) OR archived_date IS NULL OR archived_date = '0000-00-00') AND (task_tododate IS NULL OR task_tododate = '0000-00-00' OR (task_tododate< DATE(NOW()) AND status != 'Done'))"));
            $alert = '';
            if($get_config['total_unread'] > 0) {
                $alert = '<img class="pull-righ1t" src="'.WEBSITE_URL.'/img/alert.png" border="0" alt="">&nbsp;&nbsp;';
            }

            echo "<a href='scrum_tasks.php?category=".$row['taskboardid']."'><button type='button' class='mobile-100 btn brand-btn mobile-block ".$active_daily."' >".$alert.$row['board_name']."</button></a>&nbsp;&nbsp;";
        }

        ?>
        <br><br>
		</div>

        <div class="scrum_tickets" id="scrum_tickets">
        <?php
        if($_GET['category'] != 'All') {
            $taskboardid = $_GET['category'];
            $task_path = get_task_board($dbc, $taskboardid, 'task_path');

            $each_tab = explode('#*#', get_project_path_milestone($dbc, $task_path, 'milestone'));
            $timeline = explode('#*#', get_project_path_milestone($dbc, $task_path, 'timeline'));
            $i=0;
            foreach ($each_tab as $cat_tab) {
                if($cat_tab != '') {
                    $result = mysqli_query($dbc, "SELECT * FROM tasklist WHERE task_path='$task_path' AND task_board = '$taskboardid' AND task_milestone_timeline='$cat_tab' ORDER BY task_path ASC, tasklistid DESC");
                    $status = $cat_tab;
                    $status = str_replace("&","FFMEND",$status);
                    $status = str_replace(" ","FFMSPACE",$status);
                    $status = str_replace("#","FFMHASH",$status);

                    $class_on = '';
                    if($check_table_orient == '1') {
                        $class_on = 'horizontal-on';
                        $class_on_2 = 'horizontal-on-title';
                    } else {
                        $class_on = '';
                        $class_on_2 = '';
                    }

                    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(tasklistid) AS total_unread FROM tasklist WHERE task_path='$task_path' AND task_milestone_timeline='$cat_tab' AND task_board = '$taskboardid' AND (DATE(`archived_date`) >= (DATE(NOW() - INTERVAL 3 DAY)) OR archived_date IS NULL OR archived_date = '0000-00-00') AND (task_tododate IS NULL OR task_tododate = '0000-00-00' OR (task_tododate< DATE(NOW()) AND status != 'Done'))"));
                    $alert = '';
                    if($get_config['total_unread'] > 0) {
                        $alert = '<img class="pull-right" src="'.WEBSITE_URL.'/img/alert.png" border="0" alt="">';
                    }

                    echo '<ul id="sortable'.$i.'" class="connectedSortable '.$status.' '.$class_on.'"><li class="ui-state-default ui-state-disabled '.$class_on_2.'">'.$alert.$cat_tab.'<br>'.$timeline[$i].'</li>';

                    while($row = mysqli_fetch_array( $result )) {
                        echo '<li id="'.$row['tasklistid'].'" class="ui-state-default '.$class_on.'"><a href="add_tasklist.php?type='.$row['status'].'&tasklistid='.$row['tasklistid'].'">'.limit_text($row['heading'], 5 ). '</a>';

                        $past = 0;

                        $date = new DateTime($row['task_tododate']);
                        $now = new DateTime();

                        if($date < $now && $row['status'] != 'Done') {
                            $past = 1;
                        }

                        if($row['task_tododate'] == '' || $row['task_tododate'] == '0000-00-00' || $past == 1) {
                            echo '<img class="pull-right" src="'.WEBSITE_URL.'/img/icons/thumb_down.png" border="0" alt="">';
                        } else {
                            echo '<img class="pull-right" src="'.WEBSITE_URL.'/img/icons/thumb_up.png" border="0" alt="">';
                        }

                        echo '<span class="pull-right">';
						profile_id($dbc, $row['contactid']);
						echo '</span></li>';
                    }
                    echo '<li class="new_task_box"><input onChange="changeEndAme(this)" name="add_task" id="add_new_task '.$status.' '.$task_path.' '.$taskboardid.'" type="text" class="form-control" /></li>';

                    echo '<li class=""><a href="add_tasklist.php?task_milestone_timeline='.$status.'&task_path='.$task_path.'&task_board='.$taskboardid.'" class="btn brand-btn pull-right">Add Task</a></li>';

                    echo '</ul>';
                    $i++;
                }
            }
        }
        ?>
        </div>

		</form>
	</div>
</div>

<?php include ('../footer.php'); ?>