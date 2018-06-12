<?php
include ('../include.php');
checkAuthorised('tasks');
include_once ('../navigation.php');
?>
<script type="text/javascript" src="tasks.js"></script>
<style type='text/css'>
.ui-state-disabled  { pointer-events: none !important; }
</style>
</head>
<body>
<?php
$table_row_style = '';
$table_style = '';

$rowsPerPage = 10;
$pageNum = 1;

if(isset($_GET['page'])) {
    $pageNum = $_GET['page'];
}

$offset = ($pageNum - 1) * $rowsPerPage;

$result = mysqli_query($dbc,"SELECT * FROM tasklist ORDER BY tasklistid DESC LIMIT $offset, $rowsPerPage");
$query = "SELECT count(t.tasklistid) as numrows FROM tasklist t";

$num_rows = mysqli_num_rows($result);
?>
<div class="container">
    <h1 class="single-pad-bottom pull-left">My Tasks</h1>
    <?php
    if(config_visible_function($dbc, 'contact') == 1) {
        //echo '<a href="field_config_tasks.php?type=tab" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br>';
    }
    echo '<br><a href="field_config_project_manage.php?category=how_to" class="pull-right"><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
    echo '<span class="popover-examples list-inline"><a class="pull-right" style="margin:-5px 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here to add/remove your task boards."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';

    echo '<div class="clearfix"></div>';

    if (strpos($_SERVER['REQUEST_URI'], '/tasks.php') !== false) {
        $active = 'active_tab';
    }
    else {
        $active = '';
    }

    echo '<div class="tab-container">
		<div class="pull-left tab">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to see your personal task board for the day."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
			if ( check_subtab_persmission($dbc, 'tasks', ROLE, 'my') === TRUE ) {
				echo "<a href='tasks.php?category=All'><button type='button' class='btn brand-btn mobile-block ".$active."'>My Tasks</button></a>";
			} else {
				echo "<button type='button' class='btn disabled-btn mobile-block'>My Tasks</button>";
			}
		echo '</div>';

    $get_field_task_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT task_dashboard_tile FROM task_dashboard"));
    $tasks_name = explode(',' , $get_field_task_config['task_dashboard_tile']);
    foreach($tasks_name as $task_name) {
        $task_file_path = str_replace(" ","_",strtolower($task_name));
        if (strpos($_SERVER['REQUEST_URI'], $task_file_path) !== false) {
            $active = 'active_tab';
        }
        else {
            $active = '';
        }
		
		switch($task_file_path) {
			case 'company_tasks':
				$info = "Click here to see everyone in your company.";
				$display = ( check_subtab_persmission($dbc, 'tasks', ROLE, 'company') === TRUE ) ? 1 : 0;
				break;
			case 'community_tasks':
				$info = "Click here to see everyone in the ROOK community.";
				$display = ( check_subtab_persmission($dbc, 'tasks', ROLE, 'community') === TRUE ) ? 1 : 0;
				break;
			case 'business_tasks':
				$info = "Click here to view all business tasks.";
				$display = ( check_subtab_persmission($dbc, 'tasks', ROLE, 'business') === TRUE ) ? 1 : 0;
				break;
			case 'client_tasks':
				$info = "Click here to view all client tasks.";
				$display = ( check_subtab_persmission($dbc, 'tasks', ROLE, 'client') === TRUE ) ? 1 : 0;
				break;
			case 'reporting':
				$info = "Click here to see all task activity.";
				$display = ( check_subtab_persmission($dbc, 'tasks', ROLE, 'reporting') === TRUE ) ? 1 : 0;
				break;
			default:
				$info = "Unknown Tab";
				break;
		}

        echo '<div class="pull-left tab"><span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="' . $info . '"><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
			if ( $display == 1 ) {
				echo "<a href='" . $task_file_path . ".php?category=All'><button type='button' class='btn brand-btn mobile-block ".$active."'>" . $task_name . "</button></a>";
			} else {
				echo "<button type='button' class='btn disabled-btn mobile-block'>" . $task_name . "</button>";
			}
		echo '</div>';
    }

    echo '<br/><br/><br>';

    echo '<div class="clearfix"></div>';

    if($num_rows > 0) {
        echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
        echo "<table class='table table-bordered'>";
        echo "<tr class='hidden-xs hidden-sm'>";

        echo '<th>Staff Name</th>';
        echo '<th>Date Created</th>';
        echo '<th>Task</th>';
        echo '<th>Task Start Date</th>';
        echo '<th>Task Status</th>';

        echo "</tr>";
    } else {
        echo "<h2>No Record Found.</h2>";
    }


    while($row = mysqli_fetch_array( $result ))
    {
            echo "<tr>";
            $staffid = $row['contactid'];
            $staff = mysqli_fetch_array(mysqli_query($dbc,"SELECT first_name,last_name FROM contacts WHERE contactid='$staffid'"));
            if(($staff['first_name'] != null && $staff['first_name'] !='') || ($staff['last_name'] != null && $staff['last_name'] != ''))
                echo '<td data-title="staff-name">'.decryptIt($staff['first_name']).' '.decryptIt($staff['last_name']).'</td>';
            else
                echo '<td data-title="staff-name"></td>';

            if($row['created_date'] != null && $row['created_date'] != '')
                echo '<td data-title="task">'.$row['created_date'].'</td>';
            else
                echo '<td data-title="task"></td>';

            if($row['heading'] != null && $row['heading'] != '')
                echo '<td data-title="task">'.$row['heading'].'</td>';
            else
                echo '<td data-title="task"></td>';

            if($row['task_tododate'] != null && $row['task_tododate'] != '')
                echo '<td data-title="task_tododate">'.$row['task_tododate'].'</td>';
            else
                echo '<td data-title="task_tododate"></td>';

            if($row['task_milestone_timeline'] != null && $row['task_milestone_timeline'] != '')
                echo '<td data-title="status">'.$row['task_milestone_timeline'].'</td>';
            else if($row['status'] != null && $row['status'] != '')
                echo '<td data-title="status">'.$row['status'].'</td>';
            else
                echo '<td data-title="status"></td>';
            echo "</tr>";
    }

    echo '</table>';
    echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
    ?>
</div>
<?php include ('../footer.php'); ?>

