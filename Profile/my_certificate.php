<?php
/*
Customer Listing
*/
if(!isset($_GET['mobile_view'])) {
    include_once ('../include.php');
} else {
    include_once ('../database_connection.php');
    include_once ('../global.php');
    include_once ('../function.php');
    include_once ('../output_functions.php');
    include_once ('../email.php');
    include_once ('../user_font_settings.php');
}
error_reporting(0);

if (isset($_POST['profile_info']) && $_GET['edit_contact'] == 'true') {

	if($_POST['new_certificate'] != '') {
		$certificate_type = filter_var($_POST['new_certificate'],FILTER_SANITIZE_STRING);
	} else {
		$certificate_type = filter_var($_POST['certificate_type'],FILTER_SANITIZE_STRING);
	}

    $description = filter_var(htmlentities($_POST['description']),FILTER_SANITIZE_STRING);
    $date_completion = filter_var($_POST['date_completion'],FILTER_SANITIZE_STRING);
    $expiry_date = filter_var($_POST['expiry_date'],FILTER_SANITIZE_STRING);
    $followup_date = filter_var($_POST['followup_date'],FILTER_SANITIZE_STRING);

	if (!file_exists('download')) {
		mkdir('download', 0777, true);
	}

    if($_FILES["upload_document"]["name"] != '') {
        $upload_document = implode('#$#', $_FILES["upload_document"]["name"]);
    } else {
        $upload_document = '';
    }

	$upload_document = htmlspecialchars($upload_document, ENT_QUOTES);

    for($i = 0; $i < count($_FILES['upload_document']['name']); $i++) {
        move_uploaded_file($_FILES["upload_document"]["tmp_name"][$i], "../Certificate/download/".$_FILES["upload_document"]["name"][$i]) ;
    }

    $contactid = $_SESSION['contactid'];
    $query_insert_staff = "INSERT INTO `certificate` (`contactid`, `certificate_type`, `description`, `issue_date`, `expiry_date`, `reminder_date`) VALUES ('$contactid', '$certificate_type', '$description', '$date_completion', '$expiry_date', '$followup_date')";
    $result_insert_staff = mysqli_query($dbc, $query_insert_staff);echo '<script> console.log("'.$query_insert_staff.'"); </script>';
	mysqli_query($dbc, "INSERT INTO `certificate_uploads` (`certificateid`,`type`,`document_link`) VALUES ('".mysqli_insert_id($dbc)."','Document','$upload_document')");
}
if(!empty($_POST['subtab']) && $_POST['subtab'] != 'certificates') {
	$action_page = 'my_profile.php?edit_contact='.$_GET['edit_contact'];
	if($_POST['subtab'] == 'goals') {
		$action_page = 'gao_goal.php?edit_contact='.$_GET['edit_contact'];
	}
    if($_POST['subtab'] == 'daysheet') {
        $action_page = 'daysheet.php?edit_contact='.$_GET['edit_contact'];
    }
    if($_POST['subtab'] == 'schedule') {
        $action_page = 'staff_schedule.php';
    }

	?>
	<form action="<?php echo $action_page; ?>" method="post" id="change_page">
		<input type="hidden" name="subtab" value="<?php echo $_POST['subtab']; ?>">
	</form>
	<script type="text/javascript"> document.getElementById('change_page').submit(); </script>
<?php } ?>
<script type="text/javascript">
$(document).ready(function() {
    $("#certificate_type").change(function() {
        if($("#certificate_type option:selected").text() == 'New Certificate') {
                $( "#new_certificate" ).show();
        } else {
            $( "#new_certificate" ).hide();
        }
    });
});
</script>
</head>
<script type="text/javascript" src="profile.js"></script>
<?php include_once ('edit_contact_access.php') ?>
<body>
<?php if(!isset($_GET['mobile_view'])) { include_once ('../navigation.php'); }
checkAuthorised();
$subtab = 'certificates';
if (!empty($_POST['subtab'])) {
    $subtab = $_POST['subtab'];
}
?>

<div class="container">
    <div class="row">
        <!-- <div id="no-more-tables" class="main-screen contacts-list"> -->
        <div class="main-screen">
            <!-- Tile Header -->
            <div class="tile-header standard-header">
                <div class="col-xs-12 col-sm-4">
                    <h1>
                        <span class="pull-left" style="margin-top: -5px;"><a href="my_profile.php" class="default-color">My Profile</a></span>
                        <span class="clearfix"></span>
                    </h1>
                </div>
                <div class="col-xs-12 col-sm-8 text-right settings-block">
                    <form action="?<?= $_GET['edit_contact'] != 'true' ? 'edit_contact=true' : '' ?>" method="post" id="edit_contact">
                        <button name="subtab" value="<?= $subtab ?>" onclick="$('#edit_contact').submit();" class="btn brand-btn pull-right"><?= $_GET['edit_contact'] != 'true' ? 'Edit' : 'View' ?></button>
                    </form>
                    <a href="<?= WEBSITE_URL ?>/Daysheet/daysheet.php" class="btn brand-btn pull-right">Planner</a>
                </div>
                <div class="clearfix"></div>
            </div><!-- .tile-header -->


            <form id="form1" name="form1" method="post" action="my_certificate.php?edit_contact=<?= $_GET['edit_contact'] ?>" enctype="multipart/form-data" class="form-horizontal" role="form">

                <!-- Sidebar -->
                <div class="standard-collapsible tile-sidebar set-section-height">
                    <?php include('tile_sidebar.php'); ?>
                </div><!-- .tile-sidebar -->

                <!-- Main Screen -->
                <div class="has-main-screen scale-to-fill tile-content set-section-height" style="padding: 0; overflow-y: auto;">
                    <div class="main-screen-details main-screen override-main-screen <?= $subtab != 'id_card' ? 'standard-body' : '' ?>" style="height: inherit;">
                        <?php if($subtab != 'id_card') { ?>
                            <div class='standard-body-title'>
                                <h3>Certificates & Certifications</h3>
                            </div>
                        <?php } ?>
                        <div class='standard-body-dashboard-content pad-top pad-left pad-right'>
                            <h4>Certificates</h4>
                            <div class="form-group">
                                <label for="company_name" class="col-sm-4 control-label">Certificate Type<span class="hp-red">*</span>:</label>
                                <div class="col-sm-8">
                                    <select id="certificate_type" name="certificate_type" class="chosen-select-deselect form-control" width="380">
                                        <option value=''></option>
                                        <?php
                                        $query = mysqli_query($dbc,"SELECT distinct(certificate_type) FROM certificate order by certificate_type");
                                        while($row = mysqli_fetch_array($query)) {
                                            echo "<option value='". $row['certificate_type']."'>".$row['certificate_type'].'</option>';
                                        }
                                        echo "<option value = 'Other'>New Certificate</option>";
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" id="new_certificate" style="display: none;">
                                <label for="travel_task" class="col-sm-4 control-label">New Certificate Name:
                                </label>
                                <div class="col-sm-8">
                                    <input name="new_certificate" type="text" class="form-control" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="first_name[]" class="col-sm-4 control-label">Description:</label>
                                <div class="col-sm-8">
                                    <textarea name="description" rows="5" cols="50" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="form-group clearfix completion_date">
                                <label for="first_name" class="col-sm-4 control-label text-right">Date Document Was Completed:</label>
                                <div class="col-sm-8">
                                    <input name="date_completion" value="" type="text" class="datepicker"></p>
                                </div>
                            </div>

                            <div class="form-group clearfix completion_date">
                                <label for="first_name" class="col-sm-4 control-label text-right">Expiry Date:</label>
                                <div class="col-sm-8">
                                    <input name="expiry_date" value="" type="text" class="datepicker"></p>
                                </div>
                            </div>

                            <div class="form-group clearfix completion_date">
                                <label for="first_name" class="col-sm-4 control-label text-right">Next Follow Up Date:</label>
                                <div class="col-sm-8">
                                    <input name="followup_date" value="" type="text" class="datepicker"></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="file" class="col-sm-4 control-label">Document:
                                    <span class="popover-examples list-inline">&nbsp;
                                        <a href="#job_file" data-toggle="tooltip" data-placement="top" title="File name cannot contain commas or apostrophes"><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
                                    </span>
                                </label>
                                <div class="col-sm-8">
                                    <input name="upload_document[]" multiple type="file" id="file" data-filename-placement="inside" class="form-control" />
                                </div>
                            </div>

                            <h4>List of Certificates</h4>

                            <?php
                                $result = mysqli_query($dbc, "SELECT * FROM certificate WHERE contactid='$contactid' ORDER BY expiry_date");
                                $num_rows = mysqli_num_rows($result);
                                if($num_rows > 0) {
                                    echo "<table class='table table-bordered'>";
                                    echo "<tr class='hidden-xs hidden-sm'>";
                                            echo '<th>Certificate Type</th>
                                            <th>Description</th>
                                            <th>Date Document Was Completed</th>
                                            <th>Expiry Date</th>
                                            <th>Next Follow Date</th>
                                            <th>Document(s)</th>
                                            ';
                                    echo "</tr>";
                                } else {
                                    echo "<h2>No Record Found.</h2>";
                                }
                                while($row = mysqli_fetch_array( $result ))
                                {
                                    echo "<tr>";
                                    echo '<td data-title="Service Type">' . $row['certificate_type'] . '</td>';
                                    echo '<td data-title="Comment">' . html_entity_decode($row['description']) . '</td>';
                                    echo '<td data-title="Service Type">' . $row['issue_date'] . '</td>';
                                    echo '<td data-title="Service Type">' . $row['expiry_date'] . '</td>';
                                    echo '<td data-title="Service Type">' . $row['reminder_date'] . '</td>';
                                    echo '<td>';
									$documents = mysqli_query($dbc, "SELECT * FROM `certificate_uploads` WHERE `certificateid`='".$row['certificateid']."' AND `deleted`=0");
									while($doc = mysqli_fetch_assoc($documents)) {
										if($doc['document_link'] != '' && $doc['type'] == 'Document') {
											if(file_exists('../Certificate/download/'.$doc['document_link'])) {
												echo "<a href='../Certificate/download/".$doc['document_link']."'>".$doc['document_link']."</a><br />";
											}
										} else if($doc['document_link'] != '' & $doc['type'] == 'Link') {
											echo "<a href='http://".$doc['document_link']."'>".$doc['document_link']."</a><br />";
										}
									}
                                    echo '</td>';
                                    echo "</tr>";
                                }

                                echo '</table>';
                            ?>
                        <button type='submit' name='profile_info' value='<?php echo $contactid; ?>' class="btn brand-btn pull-right">Submit</button>
                        <a href='<?php echo WEBSITE_URL; ?>/home.php' class="btn brand-btn pull-right">Back</a>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include ('../footer.php'); ?>
