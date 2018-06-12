<?php
if(empty($_GET['contactid'])) {
	$tile_employee = "'".trim(htmlspecialchars_decode($tile_employee, ENT_QUOTES), "'")."'";
    if($tile_employee == "'All Active Employee'") {
        $get_user = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `deleted`=0 AND `status`=1");
        while($row_user = mysqli_fetch_array($get_user)) {
            echo '<div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="project_workflow_dashboard.php?tile='.$tile.'&tab='.$tab.'&contactid='.$row_user['contactid'].'" >'.get_contact($dbc, $row_user['contactid']).'</a></div>';
        }
    } else {
        $get_user = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND self_identification IN ($tile_employee) AND `deleted`=0 AND `status`=1");
        while($row_user = mysqli_fetch_array($get_user)) {
            echo '<div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="project_workflow_dashboard.php?tile='.$tile.'&tab='.$tab.'&contactid='.$row_user['contactid'].'" >'.get_contact($dbc, $row_user['contactid']).'</a></div>';
        }
    }
}

if(!empty($_GET['contactid'])) {
    echo '<a href="project_workflow_dashboard.php?tile='.$tile.'&tab='.$tab.'" class="btn brand-btn pull-right">Employee List</a><br>';

    $contactid = $_GET['contactid'];
    //$query_check_credentials = "SELECT * FROM project_manage WHERE tile='$tile' AND tab='$tile_data' ORDER BY projectmanageid DESC";

    $query_check_credentials = "SELECT * FROM project_manage WHERE tab='$tile_data' AND status='Approved' ORDER BY projectmanageid DESC";
    $result = mysqli_query($dbc, $query_check_credentials);

    while($row = mysqli_fetch_array( $result )) {
        $projectmanageid = $row['projectmanageid'];

        $get_timer = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT timer_type FROM project_manage_assign_to_timer WHERE projectmanageid='$projectmanageid' AND created_by='$contactid' AND DATE(NOW()) = DATE(created_date) AND end_time IS NULL"));
        if($get_timer['timer_type'] == 'Work') {
            $timer = '#start_timer';
        } else if($get_timer['timer_type'] == 'Break') {
            $timer = '#break_timer';
        } else {
            $timer = '';
        }

        echo '<div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href=\'add_project_manage.php?tile='.$tile.'&tab='.$tab.'&projectmanageid='.$projectmanageid.'&tab_from_tile_view='.$tab.'&contactid='.$contactid.$timer.'\' >W-'.$row['unique_id'].'<br>'.$row['heading'].'</a></div>';
    }
}
?>
