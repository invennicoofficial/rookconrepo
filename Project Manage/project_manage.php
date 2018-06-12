<?php

/*
Equipment Listing
*/
include ('../include.php');
?>
</head>
<body>

<?php include_once ('../navigation.php');
checkAuthorised();
?>
<div class="container">
    <div class="row">

    <h1 class="single-pad-bottom">Project/Job Management
        <a href="field_config_project_manage.php?type=tab" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>
    </h1>

    <form name="form_sites" method="post" action="" class="form-inline" role="form">

    <?php
    //if(vuaed_visible_function($dbc, 'project_manage') == 1) {
        echo '<a href="add_project_manage.php" class="btn brand-btn mobile-block gap-bottom pull-right">Add Project Management</a>';
    //} ?>

    <div id="no-more-tables"> <?php

    $query_check_credentials = "SELECT * FROM project_manage";
    $result = mysqli_query($dbc, $query_check_credentials);
    $num_rows = mysqli_num_rows($result);

    if($num_rows > 0) {

        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT project_manage_dashboard FROM field_config_project_manage WHERE project_manage_dashboard IS NOT NULL"));
        $value_config = ','.$get_field_config['project_manage_dashboard'].',';

        echo "<table class='table table-bordered'>";
        echo "<tr class='hidden-xs hidden-sm'>";
        if (strpos($value_config, ','."Business".',') !== FALSE) {
            echo '<th>Business</th>';
        }
        if (strpos($value_config, ','."Contact".',') !== FALSE) {
            echo '<th>Contact</th>';
        }
        if (strpos($value_config, ','."Rate Card".',') !== FALSE) {
            echo '<th>Rate Card</th>';
        }
        if (strpos($value_config, ','."Short Name".',') !== FALSE) {
            echo '<th>Short Name</th>';
        }
        if (strpos($value_config, ','."Piece Work".',') !== FALSE) {
            echo '<th>Piece Work</th>';
        }
        if (strpos($value_config, ','."Heading".',') !== FALSE) {
            echo '<th>Heading</th>';
        }
        if (strpos($value_config, ','."Location".',') !== FALSE) {
            echo '<th>Location</th>';
        }
        if (strpos($value_config, ','."Job number".',') !== FALSE) {
            echo '<th>Job number</th>';
        }
        if (strpos($value_config, ','."AFE number".',') !== FALSE) {
            echo '<th>AFE number</th>';
        }
        if (strpos($value_config, ','."Staff(Assign To)".',') !== FALSE) {
            echo '<th>Staff(Assign To)</th>';
        }
        if (strpos($value_config, ','."Created Date".',') !== FALSE) {
            echo '<th>Created Date</th>';
        }
        if (strpos($value_config, ','."Start Date".',') !== FALSE) {
            echo '<th>Start Date</th>';
        }
        if (strpos($value_config, ','."Estimated Completion Date".',') !== FALSE) {
            echo '<th>Estimated Completion Date</th>';
        }
        if (strpos($value_config, ','."Work performed".',') !== FALSE) {
            echo '<th>Work performed</th>';
        }
        if (strpos($value_config, ','."Path".',') !== FALSE) {
            echo '<th>Path</th>';
        }
        if (strpos($value_config, ','."Milestone & Timeline".',') !== FALSE) {
            echo '<th>Milestone & Timeline</th>';
        }
        if (strpos($value_config, ','."Service Type".',') !== FALSE) {
            echo '<th>Service Type</th>';
        }
        if (strpos($value_config, ','."Service Category".',') !== FALSE) {
            echo '<th>Service Category</th>';
        }
        if (strpos($value_config, ','."Service Heading".',') !== FALSE) {
            echo '<th>Service Heading</th>';
        }
        if (strpos($value_config, ','."Support Documents".',') !== FALSE) {
            echo '<th>Support Documents</th>';
        }
        if (strpos($value_config, ','."Support Links".',') !== FALSE) {
            echo '<th>Support Links</th>';
        }
        if (strpos($value_config, ','."Review Documents".',') !== FALSE) {
            echo '<th>Review Documents</th>';
        }
        if (strpos($value_config, ','."Review Links".',') !== FALSE) {
            echo '<th>Review Links</th>';
        }
        if (strpos($value_config, ','."Description".',') !== FALSE) {
            echo '<th>Description</th>';
        }
        if (strpos($value_config, ','."Notes".',') !== FALSE) {
            echo '<th>Notes</th>';
        }
        if (strpos($value_config, ','."Status".',') !== FALSE) {
            echo '<th>Status</th>';
        }
        if (strpos($value_config, ','."Doing Start and End Date".',') !== FALSE) {
            echo '<th>Doing Start and End Date</th>';
        }
        if (strpos($value_config, ','."Internal QA Date".',') !== FALSE) {
            echo '<th>Internal QA Date</th>';
        }
        if (strpos($value_config, ','."Client QA/Deliverable Date".',') !== FALSE) {
            echo '<th>Client QA/Deliverable Date</th>';
        }
        if (strpos($value_config, ','."Doing Assign To".',') !== FALSE) {
            echo '<th>Doing Assign To</th>';
        }
        if (strpos($value_config, ','."Internal QA Assign To".',') !== FALSE) {
            echo '<th>Internal QA Assign To</th>';
        }
        if (strpos($value_config, ','."Client QA/Deliverable Assign To".',') !== FALSE) {
            echo '<th>Client QA/Deliverable Assign To</th>';
        }
        if (strpos($value_config, ','."TO DO Date".',') !== FALSE) {
            echo '<th>TO DO Date</th>';
        }
        if (strpos($value_config, ','."Deliverable Date".',') !== FALSE) {
            echo '<th>Deliverable Date</th>';
        }
        if (strpos($value_config, ','."Estimated Time to Complete Work".',') !== FALSE) {
            echo '<th>Estimated Time to Complete Work</th>';
        }
            echo '<th>Function</th>';
            echo "</tr>";
        } else {
            echo "<h2>No Record Found.</h2>";
        }

        while($row = mysqli_fetch_array( $result ))
	{

            $projectmanageid = $row['projectmanageid'];

			$project_manage_detail =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM	project_manage_detail WHERE	projectmanageid='$projectmanageid'"));

            if (strpos($value_config, ','."Business".',') !== FALSE) {
                echo '<td data-title="Notes">' . get_contact($dbc, $row['businessid'], 'name') . '</td>';
            }
            if (strpos($value_config, ','."Contact".',') !== FALSE) {
                echo '<td data-title="Notes">' . get_staff($dbc, $row['contactid']) . '</td>';
            }
            if (strpos($value_config, ','."Rate Card".',') !== FALSE) {
                echo '<td data-title="Notes">' . get_rate_card($dbc, $row['ratecardid'], 'rate_card_name') . '</td>';
            }
            if (strpos($value_config, ','."Short Name".',') !== FALSE) {
                echo '<td data-title="Notes">' . $row['short_name'] . '</td>';
            }
            if (strpos($value_config, ','."Piece Work".',') !== FALSE) {
                echo '<td data-title="Notes">' . $row['piece_work'] . '</td>';
            }
            if (strpos($value_config, ','."Heading".',') !== FALSE) {
                echo '<td data-title="Notes">' . $row['heading'] . '</td>';
            }
            if (strpos($value_config, ','."Location".',') !== FALSE) {
                echo '<td data-title="Notes">' . $row['location'] . '</td>';
            }
            if (strpos($value_config, ','."Job number".',') !== FALSE) {
                echo '<td data-title="Notes">' . $row['job_number'] . '</td>';
            }
            if (strpos($value_config, ','."AFE number".',') !== FALSE) {
                echo '<td data-title="Notes">' . $row['afe_number'] . '</td>';
            }

            if (strpos($value_config, ','."Staff(Assign To)".',') !== FALSE) {
                echo '<td data-title="Notes">' . get_multiple_contact($dbc, $row['assign_to']) . '</td>';
            }

            if (strpos($value_config, ','."Created Date".',') !== FALSE) {
                echo '<td data-title="Notes">' . $row['created_date'] . '</td>';
            }
            if (strpos($value_config, ','."Start Date".',') !== FALSE) {
                echo '<td data-title="Notes">' . $row['start_date'] . '</td>';
            }
            if (strpos($value_config, ','."Estimated Completion Date".',') !== FALSE) {
                echo '<td data-title="Notes">' . $row['estimated_completion_date'] . '</td>';
            }
            if (strpos($value_config, ','."Work performed".',') !== FALSE) {
                echo '<td data-title="Notes">' . $row['work_performed_date'] . '</td>';
            }
            if (strpos($value_config, ','."Path".',') !== FALSE) {
                echo '<td data-title="Notes">' . get_project_path_milestone($dbc, $row['project_path'], 'project_path') . '</td>';
            }
            if (strpos($value_config, ','."Milestone & Timeline".',') !== FALSE) {
                echo '<td data-title="Notes">' . $row['milestone_timeline'] . '</td>';
            }
            if (strpos($value_config, ','."Service Type".',') !== FALSE) {
                echo '<td data-title="Notes">' . $row['service_type'] . '</td>';
            }
            if (strpos($value_config, ','."Service Category".',') !== FALSE) {
                echo '<td data-title="Notes">' . $row['service_category'] . '</td>';
            }
            if (strpos($value_config, ','."Service Heading".',') !== FALSE) {
                echo '<td data-title="Notes">' . $row['service_heading'] . '</td>';
            }

            if (strpos($value_config, ','."Support Documents".',') !== FALSE) {
                echo '<td data-title="Schedule">';
                $doc1 = mysqli_query($dbc, "SELECT * FROM project_manage_document_link WHERE projectmanageid='$projectmanageid' AND type='Support Document' ORDER BY doclinkid DESC");
                while($row_doc1 = mysqli_fetch_array($doc1)) {
                    echo '-<a href="download/'.$row_doc1['document'].'" target="_blank">'.$row_doc1['document'].'</a><br>';
                }
                echo '</td>';
            }
            if (strpos($value_config, ','."Support Links".',') !== FALSE) {
                echo '<td data-title="Schedule">';
                $doc2 = mysqli_query($dbc, "SELECT * FROM project_manage_document_link WHERE projectmanageid='$projectmanageid' AND type='Support Link' ORDER BY doclinkid DESC");
                while($row_doc2 = mysqli_fetch_array($doc2)) {
                    echo '-<a target="_blank" href=\''.$row_doc2['link'].'\'">Link</a><br>';
                }
                echo '</td>';
            }
            if (strpos($value_config, ','."Review Documents".',') !== FALSE) {
                echo '<td data-title="Schedule">';
                $doc3 = mysqli_query($dbc, "SELECT * FROM project_manage_document_link WHERE projectmanageid='$projectmanageid' AND type='Review Document' ORDER BY doclinkid DESC");
                while($row_doc3 = mysqli_fetch_array($doc3)) {
                    echo '-<a href="download/'.$row_doc3['document'].'" target="_blank">'.$row_doc3['document'].'</a><br>';
                }
                echo '</td>';
            }
            if (strpos($value_config, ','."Review Links".',') !== FALSE) {
                echo '<td data-title="Schedule">';
                $doc4 = mysqli_query($dbc, "SELECT * FROM project_manage_document_link WHERE projectmanageid='$projectmanageid' AND type='Review Link' ORDER BY doclinkid DESC");
                while($row_doc4 = mysqli_fetch_array($doc4)) {
                    echo '-<a target="_blank" href=\''.$row_doc4['link'].'\'">Link</a><br>';
                }
                echo '</td>';
            }
            if (strpos($value_config, ','."Description".',') !== FALSE) {
                echo '<td data-title="Quote Description">' . html_entity_decode($project_manage_detail['description']) . '</td>';
            }
            if (strpos($value_config, ','."Notes".',') !== FALSE) {
                echo '<td data-title="Quote Description">' . html_entity_decode($project_manage_detail['notes']) . '</td>';
            }
            if (strpos($value_config, ','."Status".',') !== FALSE) {
                echo '<td data-title="Notes">' . $row['status'] . '</td>';
            }
            if (strpos($value_config, ','."Doing Start and End Date".',') !== FALSE) {
                echo '<td data-title="Notes">' . $row['doing_start_date'].' - '.$row['doing_end_date'] . '</td>';
            }
            if (strpos($value_config, ','."Internal QA Date".',') !== FALSE) {
                echo '<td data-title="Notes">' . $row['internal_qa_date'] . '</td>';
            }
            if (strpos($value_config, ','."Client QA/Deliverable Date".',') !== FALSE) {
                echo '<td data-title="Notes">' . $row['client_qa_date'] . '</td>';
            }
            if (strpos($value_config, ','."Doing Assign To".',') !== FALSE) {
                echo '<td data-title="Notes">' . get_staff($dbc, $row['doing_assign_to']) . '</td>';
            }
            if (strpos($value_config, ','."Internal QA Assign To".',') !== FALSE) {
                echo '<td data-title="Notes">' . get_staff($dbc, $row['internal_qa_assign_to']) . '</td>';
            }
            if (strpos($value_config, ','."Client QA/Deliverable Assign To".',') !== FALSE) {
                echo '<td data-title="Notes">' . get_staff($dbc, $row['client_qa_assign_to']) . '</td>';
            }
            if (strpos($value_config, ','."TO DO Date".',') !== FALSE) {
                echo '<td data-title="Notes">' . $row['to_do_date'] . '</td>';
            }
            if (strpos($value_config, ','."Deliverable Date".',') !== FALSE) {
                echo '<td data-title="Notes">' . $row['deliverable_date'] . '</td>';
            }
            if (strpos($value_config, ','."Estimated Time to Complete Work".',') !== FALSE) {
                echo '<td data-title="Notes">' . $row['start_time'] . '</td>';
            }

            echo '<td data-title="Function">';
            $contactid_timer = $_SESSION['contactid'];
            $get_timer = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT timer_type FROM project_manage_assign_to_timer WHERE projectmanageid='$projectmanageid' AND created_by='$contactid_timer' AND DATE(NOW()) = DATE(created_date) AND end_time IS NULL"));
            if($get_timer['timer_type'] == 'Work') {
                $timer = '#start_timer';
            } else if($get_timer['timer_type'] == 'Break') {
                $timer = '#break_timer';
            } else {
                $timer = '';
            }

            //if(vuaed_visible_function($dbc, 'project_manage') == 1) {
                echo '<a href=\'add_project_manage.php?projectmanageid='.$row['projectmanageid'].$timer.'\'>Edit</a>'; //echo '<a href=\''.WEBSITE_URL.'/delete_restore.php?action=delete&projectmanageid='.$row['projectmanageid'].'\' onclick="return confirm(\'Are you sure?\')">Delete</a>';
            //}
            echo '</td>';

            echo "</tr>";
        }
        echo '</table>';

        //if(vuaed_visible_function($dbc, 'project_manage') == 1) {
        //    echo '<a href="add_project_manage.php?category='.$category.'" class="btn brand-btn mobile-block gap-bottom pull-right">Add Product</a>';
//
        //}

    ?>

</div>
</div>
</div>


<?php include ('../footer.php'); ?>