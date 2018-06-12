<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('safety');
error_reporting(0);

	$contactide = $_SESSION['contactid'];
	$get_table_orient = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM contacts WHERE contactid='$contactide'"));
	$accordion = $get_table_orient['safety_manual_view'];

	if($accordion == 'on') {
		include ('manual_checklist_accordion.php');
	} else {
		include ('manual_checklist.php');
	}

if(!empty($_GET['type'])) {
if($_GET['type'] == 'delete') {
    $uploadid = $_GET['uploadid'];

    $doc = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM safety_upload WHERE uploadid = '$uploadid'"));

    $type = $doc['type'];
    $upload = $doc['upload'];
    $safetyid = $doc['safetyid'];

    unlink('download/'.$upload);

    $result_delete_doc = mysqli_query($dbc, "DELETE FROM `safety_upload` WHERE `uploadid` = '$uploadid'");
    header('Location: add_manual.php?safetyid='.$safetyid.'&action=view&formid=0');
}
}
	if($accordion == 'on') {
?>
<script>
$(document).ready(function() {
	$('.accordion-show').on('click', function() {
		$('.hider').hide();
		$(this).next().toggle();
	});
		$('h4.tbl-orient').hide();
	$('h3.tbl-orient').on('click', function() {
		$('table.tbl-orient').hide();
		$('h4.tbl-orient').removeClass('showhideh4');
		$('h4.tbl-orient').hide();
		$(this).nextUntil( 'h3', 'h4' ).show();
		if($(this).next().is(":hidden")) {
			$(this).next().show();
			$(this).next().next().show();
		} else {
			$(this).next().toggle();
			$(this).next().next().toggle();
		}

		if($(this).hasClass('showhideh3')) {
			$(this).next().hide();
			$(this).next().next().hide();
			$(this).removeClass('showhideh3');
			$('h4.tbl-orient').removeClass('showhideh4');
			$(this).nextUntil( 'h3', 'h4' ).hide();
		} else {
			$('h3.tbl-orient').removeClass('showhideh3');
			$(this).addClass('showhideh3');
		}
	});

	$('h4.tbl-orient').on('click', function() {
		$('.subheading').hide();
		$(this).next().toggle();
		if($(this).hasClass('showhideh4')) {
			$(this).next().hide();
			$(this).removeClass('showhideh4');
		} else {
			$('h4.tbl-orient').removeClass('showhideh4');
			$(this).addClass('showhideh4');
		}
	});


	$('.show-thirdacc').on('click', function() {
		$('.hide_third_acc').hide();
	});
});
</script>
<?php
	}
?>
	<style>
	<?php  if($accordion == 'on') {
	?>
	.mobile-100 {
		width:80%;
		margin:auto;
		margin-bottom:5px;
	}
	.mobile-100-container {
		text-align:center;
		margin-top:5px;
		width:100%;
	}
	table.tbl-orient {
		display:none;
	}
	.hideshower {
		display:none;
	}
	.dropdowndiv .mobile-100 {
		width:95%;
	}
	@media(min-width:768px) {
		.dropdowndiv {
			position:relative;
			left:-3px;
		}
		.td_container {
			background-color:lightgrey;
			min-height:50px;
			display:none;
			border:1px solid black;
			padding:0;
			margin:0;
		}
		.td_divs {
			background-color:lightgrey;

			left:0px;
			top:0px;
			position:relative;
			width:24%;
			display:inline-block;
			vertical-align:top;
			height:100%;

			word-break: break;
			border:2px solid black;
			border:1px solid black;

		}
	}
	@media (max-width:768px) {
		.hideonmobile {
			display:none;
		}
		.td_container {
			display:block;
		}
	}


		<?php
}
?>

h3.tbl-orient {
	cursor:pointer;
	min-height:40px;
	height:auto !important;
	text-align:left;
	}
	h4.tbl-orient {
	cursor:pointer;
	min-height:40px;
	height:auto !important;
	text-align:left;
	}
	@media(max-width:991px) {
		.tbl-orient td {
			padding:0px;
		}
	}
	@media(max-width:510px) {
		.tbl-orient td {
			padding: 0px;
			display: block;
			width: 270px;
			height:auto;
		}
		h3.tbl-orient {
			height:auto !important;
			width:270px;
		}
		h4.tbl-orient {
			height:auto !important;
			width:270px;
		}
		.tbl-orient tr {
			padding: 0px;
			display: table-row;
			width: 270px;
			height:auto;
			/* max-width: 200px; */
			/* width: 100px; */
			border-bottom:3px solid black;
		}
	}
	</style>

<script>
function handleClick(sel) {

    var stagee = sel.value;
	var contactide = $('.contacterid').val();

	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "manual_ajax_all.php?fill=accordionview&contactid="+contactide+"&value="+stagee,
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
		}
	});

}
</script>
</head>
<body>

<?php include_once ('../navigation.php');
$search_access = search_visible_function($dbc, 'safety');
?>

<div class="scale-to-fill has-main-screen">
	<div class="main-screen form-horizontal">
		<input type='hidden' value='<?php echo $contactide; ?>' class='contacterid' />
        <div class="form-group triple-pad-bottom clearfix location">
            <form name="form_sites" method="post" action="" class="form-inline double-gap-top" role="form">
                <center>
                    <div class="form-group">
                        <label for="site_name" class="col-sm-5 control-label pad-5">Search By Any:</label>
                        <div class="col-sm-7">
                            <?php if(isset($_POST['search_vendor_submit'])) { ?>
                                <input type="text" name="search_vendor" class="form-control" value="<?php echo $_POST['search_vendor']?>" />
                            <?php } else { ?>
                                <input type="text" name="search_vendor" class="form-control" />
                            <?php } ?>
                        </div>
                    </div>
                    <button type="submit" name="search_vendor_submit" value="Search" class="btn brand-btn mobile-block mobile-100 gap-left">Search</button>
                    <button type="submit" name="display_all_vendor" value="Display All" class="btn brand-btn mobile-block  mobile-100">Display All</button>
                </center>

                <?php
                    // if(vuaed_visible_function($dbc, 'incident_report') == 1) {
                        echo '<a href="../Incident Report/add_incident_report.php?from=safety_tile" class="btn brand-btn mobile-block pull-right mobile-100-pull-right double-gap-top">Add Incident Report</a>';
                    // }
                ?>
                
                <div class="clearfix"></div>

                <div id="no-more-tables"><?php
                    //Search
                    $vendor = '';
                    if (isset($_POST['search_vendor_submit'])) {
                        if (isset($_POST['search_vendor'])) {
                            $vendor = $_POST['search_vendor'];
                        }
                    }
                    if (isset($_POST['display_all_vendor'])) {
                        $vendor = '';
                    }

                    /* Pagination Counting */
                    $rowsPerPage = 25;
                    $pageNum = 1;

                    if(isset($_GET['page'])) {
                        $pageNum = $_GET['page'];
                    }

                    $offset = ($pageNum - 1) * $rowsPerPage;

					$search_limit = '';
					if($search_access == 0) {
						$search_limit = " AND CONCAT(',',`contactid`,',',`clientid`,',',`workerid`,',') LIKE '%,".$_SESSION['contactid'].",%'";
					}
                    if($vendor != '') {
                        $query_check_credentials = "SELECT * FROM incident_report WHERE (status = 'Done' OR status IS NULL) AND (type = '$vendor') $search_limit LIMIT $offset, $rowsPerPage";
                        $query = "SELECT count(*) as numrows FROM incident_report WHERE (status = 'Done' OR status IS NULL) AND (type = '$vendor') $search_limit";
                    } else {
                        $query_check_credentials = "SELECT * FROM incident_report WHERE (status = 'Done' OR status IS NULL) $search_limit ORDER BY incidentreportid DESC LIMIT $offset, $rowsPerPage";
                        $query = "SELECT count(*) as numrows FROM incident_report WHERE (status = 'Done' OR status IS NULL) $search_limit ORDER BY incidentreportid DESC";
                    }

                    $result = mysqli_query($dbc, $query_check_credentials);

                    $num_rows = mysqli_num_rows($result);
                    if($num_rows > 0) {

                        // Added Pagination //
                        echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
                        // Pagination Finish //

                        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT incident_report_dashboard FROM field_config_incident_report"));
                        $value_config = ','.$get_field_config['incident_report_dashboard'].',';

                        echo "<table class='table table-bordered'>";
                        echo "<tr class='hidden-xs hidden-sm'>";
                            if (strpos($value_config, ','."Client".',') !== FALSE) {
                                echo '<th>Client</th>';
                            }
                            if (strpos($value_config, ','."Type".',') !== FALSE) {
                                echo '<th>Type</th>';
                            }
                            if (strpos($value_config, ','."Staff".',') !== FALSE) {
                                echo '<th>Staff</th>';
                            }
                            if (strpos($value_config, ','."Follow Up".',') !== FALSE) {
                                echo '<th>Follow Up</th>';
                            }
                            if (strpos($value_config, ','."Created Date".',') !== FALSE) {
                                echo '<th>Created Date</th>';
                            }
                            if (strpos($value_config, ','."PDF".',') !== FALSE) {
                                echo '<th>View</th>';
                            }
                            echo '<th>Function</th>';
                            echo "</tr>";
                    } else {
                        echo "<h2>No Record Found.</h2>";
                    }

                    while($row = mysqli_fetch_array( $result )) {
                        $contact_list = [];
                        if ($row['contactid'] != '') {
                            $contact_list[$row['contactid']] = get_staff($dbc, $row['contactid']);
                        }
                        $attendance_list = [];
                        if ($row['attendance_staff'] != '') {
                            $attendance_list = explode(',', $row['attendance_staff']);
                        }
                        foreach($attendance_list as $attendee) {
                            $contact_list[] = $attendee;
                        }
                        $contact_list = array_unique($contact_list);

                        foreach($contact_list as $contact_name) {
                            echo "<tr>";

                            if (strpos($value_config, ','."Client".',') !== FALSE) {
                                echo '<td data-title="Client">' . get_client($dbc, $row['clientid']) . '</td>';
                            }
                            if (strpos($value_config, ','."Type".',') !== FALSE) {
                                echo '<td data-title="Type">' . $row['type'] . '</td>';
                            }
                            if (strpos($value_config, ','."Staff".',') !== FALSE) {
                                echo '<td data-title="Staff">' . $contact_name . '</td>';
                            }
                            if (strpos($value_config, ','."Follow Up".',') !== FALSE) {
                                if($row['type'] == 'Near Miss') {
                                    echo '<td data-title="Follow Up">N/A</td>';
                                } else {
                                    echo '<td data-title="Follow Up">' . $row['ir14'] . '</td>';
                                }
                            }
                            if (strpos($value_config, ','."Created Date".',') !== FALSE) {
                                echo '<td data-title="Created Date">' . $row['today_date'] . '</td>';
                            }
                            if (strpos($value_config, ','."PDF".',') !== FALSE) {
                                $name_of_file = 'incident_report_'.$row['incidentreportid'].'.pdf';
                                echo '<td data-title="PDF"><a href="../Incident Report/download/'.$name_of_file.'" target="_blank" ><img src="'.WEBSITE_URL.'/img/pdf.png" width="16" height="16" border="0" alt="View">View</a>';
                                if ($row['revision_number'] > 0) {
                                    $revision_dates = explode('*#*', $row['revision_date']);
                                    for ($i = 0; $i < $row['revision_number']; $i++) {
                                        $name_of_file = 'incident_report_'.$row['incidentreportid'].'_'.($i+1).'.pdf';
                                        echo '<br /><a href="../Incident Report/download/'.$name_of_file.'" target="_blank" ><img src="'.WEBSITE_URL.'/img/pdf.png" width="16" height="16" border="0" alt="view">View R'.($i+1).': '.$revision_dates[$i].'</a>';
                                    }
                                }
                                echo '</td>';
                            }
                            echo '<td data-title="Function">';
                            if(vuaed_visible_function($dbc, 'incident_report') == 1) {
                                echo '<a href="../Incident Report/add_incident_report.php?type='.$row['type'].'&incidentreportid='.$row['incidentreportid'].'&from=safety_tile">Edit</a>';
                            }

                            //echo '<a href=\'delete_restore.php?action=delete&incidentreportid='.$row['incidentreportid'].'\' onclick="return confirm(\'Are you sure?\')">Delete</a>';
                            echo '</td>';

                            echo "</tr>";
                        }
                    }

                    echo '</table>'; ?>
                </div><!-- .no-more-tables --><?php
                
                // Add Pagination
                echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
                
                // if(vuaed_visible_function($dbc, 'incident_report') == 1) {
                    echo '<a href="../Incident Report/add_incident_report.php?from=safety_tile" class="btn brand-btn mobile-block pull-right">Add Incident Report</a>';
                // } ?>
            </form>
        </div>
		<?php //} ?>

    </div>
</div>

<?php include ('../footer.php'); ?>