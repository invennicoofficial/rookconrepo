<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('project_workflow');
error_reporting(0);

if (isset($_POST['inv_dashboard'])) {
    $tab = $_POST['tab'];
    $project_manage_dashboard = implode(',',$_POST['project_manage_dashboard']);
    $tile = $_POST['tile'];

    $tile_data = $_POST['tile_data'];
    $tile_employee = "'".implode("','", $_POST['tile_employee'])."'";
	if($itle_employee == "''") {
		$tile_employee = "'All Active Employee'";
	}
	$tile_employee = filter_var($tile_employee, FILTER_SANITIZE_STRING);

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configpmid) AS configpmid FROM field_config_project_manage WHERE tile = '$tile' AND tab = '$tab'"));
    if($get_field_config['configpmid'] > 0) {
        $query_update_employee = "UPDATE `field_config_project_manage` SET project_manage_dashboard = '$project_manage_dashboard', tile_data = '$tile_data', tile_employee = '$tile_employee' WHERE tile = '$tile' AND tab='$tab' AND accordion IS NULL";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config_project_manage` (`project_manage_dashboard`, `tab`, `tile`, `tile_data`, `tile_employee`) VALUES ('$project_manage_dashboard', '$tab', '$tile', '$tile_data', '$tile_employee')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    echo '<script type="text/javascript"> window.location.replace("field_config_project_manage.php?type=dashboard&tab='.$tab.'&tile='.$tile.'"); </script>';
}

if (isset($_POST['inv_field'])) {
    $accordion = filter_var($_POST['accordion'],FILTER_SANITIZE_STRING);
    $project_manage = implode(',',$_POST['project_manage']);
    $order = $_POST['order'];
    $tab = $_POST['tab'];
    $tile = $_POST['tile'];
    $status = filter_var($_POST['status'],FILTER_SANITIZE_STRING);
    $task = filter_var($_POST['task'],FILTER_SANITIZE_STRING);
    $unique_id_start = $_POST['unique_id_start'];

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configpmid) AS configpmid FROM field_config_project_manage WHERE tile = '$tile' AND tab = '$tab' AND accordion='$accordion'"));
    if($get_field_config['configpmid'] > 0) {
        $query_update_employee = "UPDATE `field_config_project_manage` SET `project_manage` = '$project_manage', `status`='$status', `task`='$task', `order`='$order', `unique_id_start` = '$unique_id_start' WHERE tile = '$tile' AND tab = '$tab' AND accordion='$accordion'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config_project_manage` (`accordion`, `project_manage`, `order`, `tab`, `tile`, `status`,`task`, `unique_id_start`) VALUES ('$accordion', '$project_manage', '$order', '$tab', '$tile', '$status', '$task', '$unique_id_start')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    echo '<script type="text/javascript"> window.location.replace("field_config_project_manage.php?type=field&accr='.$accordion.'&tab='.$tab.'&tile='.$tile.'"); </script>';
}

if (isset($_POST['inv_pdf'])) {
    $tab = $_POST['tab'];
    $tile = $_POST['tile'];

	if (!file_exists('download')) {
		mkdir('download', 0777, true);
	}
    $pdf_logo = htmlspecialchars($_FILES["pdf_logo"]["name"], ENT_QUOTES);
    $pdf_footer_logo = $_FILES["pdf_footer_logo"]["name"];
    $pdf_header = filter_var(htmlentities($_POST['pdf_header']),FILTER_SANITIZE_STRING);
    $pdf_footer = filter_var(htmlentities($_POST['pdf_footer']),FILTER_SANITIZE_STRING);

    $send_pdf_client_subject = filter_var($_POST['send_pdf_client_subject'],FILTER_SANITIZE_STRING);
    $send_pdf_client_body = htmlentities($_POST['send_pdf_client_body']);
    $send_pdf_client_body = filter_var($send_pdf_client_body,FILTER_SANITIZE_STRING);
    $pdf_payment_term = filter_var($_POST['pdf_payment_term'],FILTER_SANITIZE_STRING);
    $pdf_term_condition = filter_var($_POST['pdf_term_condition'],FILTER_SANITIZE_STRING);
    $pdf_due_period = filter_var($_POST['pdf_due_period'],FILTER_SANITIZE_STRING);

    $pdf_tax = '';
    for($i = 0; $i < count($_POST['quote_tax_name']); $i++) {
        if($_POST['quote_tax_name'][$i] != '') {
            $pdf_tax .= filter_var($_POST['quote_tax_name'][$i],FILTER_SANITIZE_STRING).'**'.$_POST['quote_tax_rate'][$i].'**'.$_POST['quote_tax_number'][$i].'**'.$_POST['quote_tax_exemption_'.$i].'*#*';
        }
    }

    $pdf_tax = rtrim($pdf_tax, "*#*'");

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configpmid) AS configpmid FROM field_config_project_manage WHERE tile = '$tile' AND tab = '$tab' AND accordion IS NULL"));
    if($get_field_config['configpmid'] > 0) {

		if($pdf_logo == '') {
			$logo_update = $_POST['logo_file'];
		} else {
			$logo_update = $pdf_logo;
		}
		move_uploaded_file($_FILES["pdf_logo"]["tmp_name"],"download/" . $logo_update);
		if($pdf_footer_logo == '') {
			$pdf_footer_logo_update = $_POST['pdf_footer_logo_file'];
		} else {
			$pdf_footer_logo_update = $pdf_footer_logo;
		}
		move_uploaded_file($_FILES["pdf_footer_logo"]["tmp_name"],"download/" . $pdf_footer_logo_update);

		if($tile == 'Shop Work Orders' && ($tab == 'Shop Work Order' || $tab == 'Pending Work Order')) {
			$tab_match = "`tab` IN ('Shop Work Order', 'Pending Work Order')";
		}
		else {
			$tab_match = "`tab`='$tab'";
		}
        $query_update_employee = "UPDATE `field_config_project_manage` SET pdf_logo = '$logo_update', pdf_header = '$pdf_header', pdf_footer = '$pdf_footer', pdf_footer_logo = '$pdf_footer_logo_update', send_pdf_client_subject = '$send_pdf_client_subject', send_pdf_client_body = '$send_pdf_client_body', pdf_payment_term = '$pdf_payment_term', pdf_term_condition = '$pdf_term_condition', pdf_due_period = '$pdf_due_period', pdf_tax = '$pdf_tax' WHERE tile = '$tile' AND $tab_match AND accordion IS NULL";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {

		move_uploaded_file($_FILES["pdf_logo"]["tmp_name"], "download/" . $_FILES["pdf_logo"]["name"]) ;
		move_uploaded_file($_FILES["pdf_footer_logo"]["tmp_name"], "download/" . $_FILES["pdf_footer_logo"]["name"]) ;

        $query_insert_config = "INSERT INTO `field_config_project_manage` (`pdf_logo`, `pdf_header`, `pdf_footer`, `pdf_footer_logo`, `send_pdf_client_subject`, `send_pdf_client_body`, `pdf_payment_term`, `pdf_term_condition`, `pdf_due_period`, `pdf_tax`) VALUES ('$pdf_logo', '$pdf_header', '$pdf_footer', '$pdf_footer_logo', '$send_pdf_client_subject', '$send_pdf_client_body', '$pdf_payment_term', '$pdf_term_condition', '$pdf_due_period', '$pdf_tax')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    echo '<script type="text/javascript"> window.location.replace("field_config_project_manage.php?type=pdf&tab='.$tab.'&tile='.$tile.'"); </script>';
}
?>
<script type="text/javascript">
$(document).ready(function() {
	$("#acc").change(function() {
        var tab = $("#tab").val();
        var tile = $("#tile").val();
        window.location = 'field_config_project_manage.php?type=field&accr='+this.value+'&tab='+tab+'&tile='+tile;
	});
});
function dashboardViewConfig(sel) {
    var dash_view = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
    var tile = arr[1];
    var tab = arr[2];

    $.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "project_manage_ajax_all.php?fill=dashboard_view&tile="+tile+"&tab="+tab+"&dash_view="+dash_view,
        dataType: "html",   //expect html to be returned
        success: function(response){
            location.reload();
        }
    });
}
</script>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<h2><?php echo $_GET['tab']; ?> Settings</h2>
<div class="pad-left double-gap-top double-gap-bottom"><a href="project_workflow_dashboard.php?tile=<?php echo $_GET['tile'];?>&tab=<?php echo $_GET['tab']; ?>" class="btn config-btn">Back to Dashboard</a></div>

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<input type="hidden" name="tab" id="tab" value="<?php echo $_GET['tab']; ?>" />
<input type="hidden" name="tile" id="tile" value="<?php echo $_GET['tile']; ?>" />

<div class="panel-group" id="accordion2">

    <?php
    $accr = $_GET['accr'];
    $type = $_GET['type'];
    $tab = $_GET['tab'];
    $tile = $_GET['tile'];

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT project_manage, status, task, unique_id_start FROM field_config_project_manage WHERE tile='$tile' AND tab='$tab' AND accordion='$accr'"));
    $project_manage_config = ','.$get_field_config['project_manage'].',';
    $status = $get_field_config['status'];
    $task = $get_field_config['task'];
    $unique_id_start = $get_field_config['unique_id_start'];

    $get_field_config_project_manage = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_project_manage WHERE tile='$tile' AND tab='$tab' AND accordion IS NULL"));
    $project_manage_dashboard_config = ','.$get_field_config_project_manage['project_manage_dashboard'].',';
    $dashboard_view = $get_field_config_project_manage['dashboard_view'];
    $tile_data = $get_field_config_project_manage['tile_data'];
	$tile_employee = "'".trim(htmlspecialchars_decode($get_field_config_project_manage['tile_employee'], ENT_QUOTES), "'")."'";
    $pdf_logo = $get_field_config_project_manage['pdf_logo'];
    $pdf_header = $get_field_config_project_manage['pdf_header'];
    $pdf_footer = $get_field_config_project_manage['pdf_footer'];
    $pdf_footer_logo = $get_field_config_project_manage['pdf_footer_logo'];
    $send_pdf_client_body = $get_field_config_project_manage['send_pdf_client_body'];
    $send_pdf_client_subject = $get_field_config_project_manage['send_pdf_client_subject'];
    $pdf_payment_term = $get_field_config_project_manage['pdf_payment_term'];
    $pdf_due_period = $get_field_config_project_manage['pdf_due_period'];
    $pdf_tax = $get_field_config_project_manage['pdf_tax'];
    $pdf_term_condition = $get_field_config_project_manage['pdf_term_condition'];

    $get_field_order = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT GROUP_CONCAT(`order` SEPARATOR ',') AS all_order FROM field_config_project_manage WHERE tile='$tile' AND tab='$tab'"));

    $active_field = '';
    $active_dashboard = '';
    $active_pdf = '';

    if($_GET['type'] == 'field') {
        $active_field = 'active_tab';
    }
    if($_GET['type'] == 'dashboard') {
        $active_dashboard = 'active_tab';
    }
    if($_GET['type'] == 'pdf') {
        $active_pdf = 'active_tab';
    }
    echo "<a href='field_config_project_manage.php?type=field&tab=".$tab."&tile=".$tile."'><button type='button' class='btn brand-btn mobile-block ".$active_field."' >Fields</button></a>&nbsp;&nbsp;";
    echo "<a href='field_config_project_manage.php?type=dashboard&tab=".$tab."&tile=".$tile."'><button type='button' class='btn brand-btn mobile-block ".$active_dashboard."' >Dashboard</button></a>&nbsp;&nbsp;";
    echo "<a href='field_config_project_manage.php?type=pdf&tab=".$tab."&tile=".$tile."'><button type='button' class='btn brand-btn mobile-block ".$active_pdf."' >PDF</button></a>&nbsp;&nbsp;";

    echo '<br><br><Br>';

    if($_GET['type'] == 'field') {
        ?>
        <div class="form-group">
            <label for="fax_number"	class="col-sm-4	control-label">Accordion:</label>
            <div class="col-sm-8">
                <select data-placeholder="Choose a Accordion..." id="acc" name="accordion" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <option <?php if ($accr == "Information") { echo " selected"; } ?> value="Information"><?php echo get_field_config_project_manage($dbc, 'Information', 'order',$tile,$tab); ?> : Information</option>
                  <option <?php if ($accr == "Business") { echo " selected"; } ?> value="Business"><?php echo get_field_config_project_manage($dbc, 'Business', 'order',$tile,$tab); ?> : Business</option>
                  <option <?php if ($accr == "Rate Card") { echo " selected"; } ?> value="Rate Card"><?php echo get_field_config_project_manage($dbc, 'Rate Card', 'order',$tile,$tab); ?> : Rate Card</option>
                  <option <?php if ($accr == "Staff(Assign To)") { echo " selected"; } ?> value="Staff(Assign To)"><?php echo get_field_config_project_manage($dbc, 'Staff(Assign To)', 'order',$tile,$tab); ?> : Staff (Assign To)</option>
                  <option <?php if ($accr == "Dates") { echo " selected"; } ?> value="Dates"><?php echo get_field_config_project_manage($dbc, 'Dates', 'order',$tile,$tab); ?> : Dates</option>
                  <option <?php if ($accr == "Documents") { echo " selected"; } ?> value="Documents"><?php echo get_field_config_project_manage($dbc, 'Documents', 'order',$tile,$tab); ?> : Documents</option>
                  <option <?php if ($accr == "Service Information") { echo " selected"; } ?> value="Service Information"><?php echo get_field_config_project_manage($dbc, 'Service Information', 'order',$tile,$tab); ?> : Service Information</option>
                  <option <?php if ($accr == "Deliverables") { echo " selected"; } ?> value="Deliverables"><?php echo get_field_config_project_manage($dbc, 'Deliverables', 'order',$tile,$tab); ?> : Deliverables</option>
                  <option <?php if ($accr == "Details") { echo " selected"; } ?> value="Details"><?php echo get_field_config_project_manage($dbc, 'Details', 'order',$tile,$tab); ?> : Details</option>
                  <option <?php if ($accr == "General Description") { echo " selected"; } ?> value="General Description"><?php echo get_field_config_project_manage($dbc, 'General Description', 'order',$tile,$tab); ?> : General Description</option>
                  <option <?php if ($accr == "Package") { echo " selected"; } ?> value="Package"><?php echo get_field_config_project_manage($dbc, 'Package', 'order',$tile,$tab); ?> : Package</option>
                  <option <?php if ($accr == "Promotion") { echo " selected"; } ?> value="Promotion"><?php echo get_field_config_project_manage($dbc, 'Promotion', 'order',$tile,$tab); ?> : Promotion</option>
                  <option <?php if ($accr == "Custom") { echo " selected"; } ?> value="Custom"><?php echo get_field_config_project_manage($dbc, 'Custom', 'order',$tile,$tab); ?> : Custom</option>
                  <option <?php if ($accr == "Material") { echo " selected"; } ?> value="Material"><?php echo get_field_config_project_manage($dbc, 'Material', 'order',$tile,$tab); ?> : Material</option>
                  <option <?php if ($accr == "Services") { echo " selected"; } ?> value="Services"><?php echo get_field_config_project_manage($dbc, 'Services', 'order',$tile,$tab); ?> : Services</option>
                  <option <?php if ($accr == "Products") { echo " selected"; } ?> value="Products"><?php echo get_field_config_project_manage($dbc, 'Products', 'order',$tile,$tab); ?> : Products</option>
                  <!-- <option <?php if ($accr == "SR&ED") { echo " selected"; } ?> value="SR&ED"><?php echo get_field_config_project_manage($dbc, 'SR&ED', 'order',$tile,$tab); ?> : SR&ED</option> -->
                  <option <?php if ($accr == "Staff") { echo " selected"; } ?> value="Staff"><?php echo get_field_config_project_manage($dbc, 'Staff', 'order',$tile,$tab); ?> : Staff</option>
                  <option <?php if ($accr == "Contractor") { echo " selected"; } ?> value="Contractor"><?php echo get_field_config_project_manage($dbc, 'Contractor', 'order',$tile,$tab); ?> : Contractor</option>
                  <option <?php if ($accr == "Clients") { echo " selected"; } ?> value="Clients"><?php echo get_field_config_project_manage($dbc, 'Clients', 'order',$tile,$tab); ?> : Clients</option>
                  <option <?php if ($accr == "Vendor Pricelist") { echo " selected"; } ?> value="Vendor Pricelist"><?php echo get_field_config_project_manage($dbc, 'Vendor Pricelist', 'order',$tile,$tab); ?> : Vendor Pricelist</option>
                  <option <?php if ($accr == "Customer") { echo " selected"; } ?> value="Customer"><?php echo get_field_config_project_manage($dbc, 'Customer', 'order',$tile,$tab); ?> : Customer</option>
                  <option <?php if ($accr == "Inventory") { echo " selected"; } ?> value="Inventory"><?php echo get_field_config_project_manage($dbc, 'Inventory', 'order',$tile,$tab); ?> : Inventory</option>
                  <option <?php if ($accr == "Equipment") { echo " selected"; } ?> value="Equipment"><?php echo get_field_config_project_manage($dbc, 'Equipment', 'order',$tile,$tab); ?> : Equipment</option>
                  <option <?php if ($accr == "Labour") { echo " selected"; } ?> value="Labour"><?php echo get_field_config_project_manage($dbc, 'Labour', 'order',$tile,$tab); ?> : Labour</option>
                  <option <?php if ($accr == "Budget Info") { echo " selected"; } ?> value="Budget Info"><?php echo get_field_config_project_manage($dbc, 'Budget Info', 'order',$tile,$tab); ?> : Budget Info</option>
                  <option <?php if ($accr == "Path-Milestone") { echo " selected"; } ?> value="Path-Milestone"><?php echo get_field_config_project_manage($dbc, 'Path-Milestone', 'order',$tile,$tab); ?> : Path-Milestone</option>
                  <option <?php if ($accr == "Labour") { echo " selected"; } ?> value="Labour"><?php echo get_field_config_project_manage($dbc, 'Labour', 'order',$tile,$tab); ?> : Labour</option>
                  <option <?php if ($accr == "Crew") { echo " selected"; } ?> value="Crew"><?php echo get_field_config_project_manage($dbc, 'Crew', 'order',$tile,$tab); ?> : Crew</option>
                  <option <?php if ($accr == "Notes") { echo " selected"; } ?> value="Notes"><?php echo get_field_config_project_manage($dbc, 'Notes', 'order',$tile,$tab); ?> : Notes</option>
                  <option <?php if ($accr == "Timer") { echo " selected"; } ?> value="Timer"><?php echo get_field_config_project_manage($dbc, 'Timer', 'order',$tile,$tab); ?> : Timer</option>
                </select>
             </div>
        </div>
        <div class="form-group">
            <?php //if (get_field_config_project_manage($dbc, $accr, 'order') == $m) { echo  'selected="selected"'; }
            //echo get_field_config_project_manage($dbc, $accr, 'order');
            ?>
            <label for="fax_number"	class="col-sm-4	control-label">Accordion Order:</label>
            <div class="col-sm-8">
                <select data-placeholder="Choose a order..." name="order" class="chosen-select-deselect form-control" width="380">
                    <option value=""></option>
                    <?php
                    for($m=1;$m<=30;$m++) {
                        if(get_field_config_project_manage($dbc, $accr, 'order',$tile,$tab) == $m) {
                            $selected = ' selected';
                        } else {
                            $selected = '';
                        }
                        if (strpos(','.$get_field_order['all_order'].',', ','.$m.',') !== FALSE) {
                            $disabled = ' readonly';
                        } else {
                            $disabled = '';
                        }
                        ?>
                        <option <?php echo $selected.' '.$disabled; ?> value="<?php echo $m;?>"><?php echo $m;?></option>
                    <?php }
                    ?>
                </select>
            </div>
        </div>

        <h3>Fields</h3>
        <div class="panel-group" id="accordion2">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_1" >
                            Information<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_1" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Business".',') !== FALSE) { echo " checked"; } ?> value="Business" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Business
                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Contact".',') !== FALSE) { echo " checked"; } ?> value="Contact" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Contact
                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Rate Card".',') !== FALSE) { echo " checked"; } ?> value="Rate Card" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Rate Card
                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Short Name".',') !== FALSE) { echo " checked"; } ?> value="Short Name" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Short Name
                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Piece Work".',') !== FALSE) { echo " checked"; } ?> value="Piece Work" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Piece Work
                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Add to Helpdesk".',') !== FALSE) { echo " checked"; } ?> value="Add to Helpdesk" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Add to Helpdesk
                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Heading".',') !== FALSE) { echo " checked"; } ?> value="Heading" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Heading

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Location".',') !== FALSE) { echo " checked"; } ?> value="Location" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Location
                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Job number".',') !== FALSE) { echo " checked"; } ?> value="Job number" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Job #
                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Customer PO/AFE#".',') !== FALSE) { echo " checked"; } ?> value="Customer PO/AFE#" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Customer PO/AFE#


                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_2" >
                            Staff (Assign To)<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_2" class="panel-collapse collapse">
                    <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Staff(Assign To)".',') !== FALSE) { echo " checked"; } ?> value="Staff(Assign To)" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Staff (Assign To)

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_3" >
                            Dates<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_3" class="panel-collapse collapse">
                    <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Created Date".',') !== FALSE) { echo " checked"; } ?> value="Created Date" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Created Date
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Start Date".',') !== FALSE) { echo " checked"; } ?> value="Start Date" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Start Date
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Estimated Completion Date".',') !== FALSE) { echo " checked"; } ?> value="Estimated Completion Date" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Estimated Completion Date
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Work performed".',') !== FALSE) { echo " checked"; } ?> value="Work performed" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Work Performed
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Expiration Date".',') !== FALSE) { echo " checked"; } ?> value="Expiration Date" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp; Expiration Date
					<input type="checkbox" <?php if (strpos($project_manage_config, ','."Effective Date".',') !== FALSE) { echo " checked"; } ?> value="Effective Date" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp; Effective Date
					<input type="checkbox" <?php if (strpos($project_manage_config, ','."Task Start Date".',') !== FALSE) { echo " checked"; } ?> value="Task Start Date" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp; Task Start Date
					<input type="checkbox" <?php if (strpos($project_manage_config, ','."Time Clock Start Date".',') !== FALSE) { echo " checked"; } ?> value="Time Clock Start Date" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp; Time Clock Start Date
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_4" >
                            Budget Info<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_4" class="panel-collapse collapse">
                    <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Total Package".',') !== FALSE) { echo " checked"; } ?> value="Total Package" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Total Package
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Total Promotion".',') !== FALSE) { echo " checked"; } ?> value="Total Promotion" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Total Promotion
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Total Custom".',') !== FALSE) { echo " checked"; } ?> value="Total Custom" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Total Custom
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Total Material".',') !== FALSE) { echo " checked"; } ?> value="Total Material" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Total Material
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Total Labour".',') !== FALSE) { echo " checked"; } ?> value="Total Labour" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Total Labour
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Total Services".',') !== FALSE) { echo " checked"; } ?> value="Total Services" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Total Services

                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Total Products".',') !== FALSE) { echo " checked"; } ?> value="Total Products" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Total Products
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Total Staff".',') !== FALSE) { echo " checked"; } ?> value="Total Staff" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Total Staff
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Total Contractor".',') !== FALSE) { echo " checked"; } ?> value="Total Contractor" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Total Contractor
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Total Clients".',') !== FALSE) { echo " checked"; } ?> value="Total Clients" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Total Clients
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Total Vendor Pricelist".',') !== FALSE) { echo " checked"; } ?> value="Total Vendor Pricelist" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Total Vendor Pricelist
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Total Customer".',') !== FALSE) { echo " checked"; } ?> value="Total Customer" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Total Customer
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Total Inventory".',') !== FALSE) { echo " checked"; } ?> value="Total Inventory" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Total Inventory
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Total Equipment".',') !== FALSE) { echo " checked"; } ?> value="Total Equipment" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Total Equipment
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Total Budget Dollars".',') !== FALSE) { echo " checked"; } ?> value="Total Budget Dollars" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Total Budget Dollars

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_5" >
                            Path-Milestone<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_5" class="panel-collapse collapse">
                    <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Path".',') !== FALSE) { echo " checked"; } ?> value="Path" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Path
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Milestone & Timeline".',') !== FALSE) { echo " checked"; } ?> value="Milestone & Timeline" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Milestone & Timeline

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_6" >
                            Details<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_6" class="panel-collapse collapse">
                    <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Issue".',') !== FALSE) { echo " checked"; } ?> value="Issue" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Issue
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Problem".',') !== FALSE) { echo " checked"; } ?> value="Problem" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Problem
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."GAP".',') !== FALSE) { echo " checked"; } ?> value="GAP" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;GAP
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Technical Uncertainty".',') !== FALSE) { echo " checked"; } ?> value="Technical Uncertainty" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Technical Uncertainty
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Base Knowledge".',') !== FALSE) { echo " checked"; } ?> value="Base Knowledge" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Base Knowledge
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Do".',') !== FALSE) { echo " checked"; } ?> value="Do" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Do
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Already Known".',') !== FALSE) { echo " checked"; } ?> value="Already Known" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Already Known
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Sources".',') !== FALSE) { echo " checked"; } ?> value="Sources" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Sources
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Current Designs".',') !== FALSE) { echo " checked"; } ?> value="Current Designs" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Current Designs
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Known Techniques".',') !== FALSE) { echo " checked"; } ?> value="Known Techniques" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Known Techniques
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Review Needed".',') !== FALSE) { echo " checked"; } ?> value="Review Needed" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Review Needed
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Already known".',') !== FALSE) { echo " checked"; } ?> value="Already known" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Already Known
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Looking to Achieve".',') !== FALSE) { echo " checked"; } ?> value="Looking to Achieve" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Looking to Achieve


                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Plan".',') !== FALSE) { echo " checked"; } ?> value="Plan" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Plan
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Next Steps".',') !== FALSE) { echo " checked"; } ?> value="Next Steps" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Next Steps
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Learnt".',') !== FALSE) { echo " checked"; } ?> value="Learnt" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Learned
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Discovered".',') !== FALSE) { echo " checked"; } ?> value="Discovered" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Discovered
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Tech Advancements".',') !== FALSE) { echo " checked"; } ?> value="Tech Advancements" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Tech Advancements
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Work".',') !== FALSE) { echo " checked"; } ?> value="Work" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Work
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Adjustments Needed".',') !== FALSE) { echo " checked"; } ?> value="Adjustments Needed" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Adjustments Needed

                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Future Designs".',') !== FALSE) { echo " checked"; } ?> value="Future Designs" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Future Designs
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Targets".',') !== FALSE) { echo " checked"; } ?> value="Targets" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Targets
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Audience".',') !== FALSE) { echo " checked"; } ?> value="Audience" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Audience
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Strategy".',') !== FALSE) { echo " checked"; } ?> value="Strategy" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Strategy
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Desired Outcome".',') !== FALSE) { echo " checked"; } ?> value="Desired Outcome" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Desired Outcome
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Actual Outcome".',') !== FALSE) { echo " checked"; } ?> value="Actual Outcome" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Actual Outcome
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Check".',') !== FALSE) { echo " checked"; } ?> value="Check" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Check
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Objective".',') !== FALSE) { echo " checked"; } ?> value="Objective" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Objective

					<input type="checkbox" <?php if (strpos($project_manage_config, ','."Work Order".',') !== FALSE) { echo " checked"; } ?> value="Work Order" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Work Order#
					<input type="checkbox" <?php if (strpos($project_manage_config, ','."Procedure ID".',') !== FALSE) { echo " checked"; } ?> value="Procedure ID" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Procedure ID
					<input type="checkbox" <?php if (strpos($project_manage_config, ','."Quote".',') !== FALSE) { echo " checked"; } ?> value="Quote" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Quote#
					<input type="checkbox" <?php if (strpos($project_manage_config, ','."DWG".',') !== FALSE) { echo " checked"; } ?> value="DWG" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;DWG#
					<input type="checkbox" <?php if (strpos($project_manage_config, ','."Quantity".',') !== FALSE) { echo " checked"; } ?> value="Quantity" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Quantity
					<input type="checkbox" <?php if (strpos($project_manage_config, ','."S/N".',') !== FALSE) { echo " checked"; } ?> value="S/N" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;S/N
					<input type="checkbox" <?php if (strpos($project_manage_config, ','."Total Project Budget".',') !== FALSE) { echo " checked"; } ?> value="Total Project Budget" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Total Project Budget

                    <br>

                    <div class="form-group">
                        <label for="sn" class="col-sm-4 control-label text-right">Start Work Order# at:</label>
                        <div class="col-sm-8">
                            <input name="unique_id_start" value="<?php echo $unique_id_start; ?>" type="text" class="form-control"></p>
                        </div>
                    </div>

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_7" >
                            Service Information<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_7" class="panel-collapse collapse">
                    <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Service Type".',') !== FALSE) { echo " checked"; } ?> value="Service Type" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Service Type
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Service Category".',') !== FALSE) { echo " checked"; } ?> value="Service Category" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Service Category
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Service Heading".',') !== FALSE) { echo " checked"; } ?> value="Service Heading" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Service Heading

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_82" >
                            Documents<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_82" class="panel-collapse collapse">
                    <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Support Documents".',') !== FALSE) { echo " checked"; } ?> value="Support Documents" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Support Documents
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Support Links".',') !== FALSE) { echo " checked"; } ?> value="Support Links" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Support Links
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Review Documents".',') !== FALSE) { echo " checked"; } ?> value="Review Documents" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Review Documents
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Review Links".',') !== FALSE) { echo " checked"; } ?> value="Review Links" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Review Links

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_8" >
                            General Description<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_8" class="panel-collapse collapse">
                    <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Description

                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."General description".',') !== FALSE) { echo " checked"; } ?> value="General description" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;General Description
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Fabrication".',') !== FALSE) { echo " checked"; } ?> value="Fabrication" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Fabrication
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Paint".',') !== FALSE) { echo " checked"; } ?> value="Paint" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Paint
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Structure".',') !== FALSE) { echo " checked"; } ?> value="Structure" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Structure
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Rigging".',') !== FALSE) { echo " checked"; } ?> value="Rigging" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Rigging

                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Sandblast".',') !== FALSE) { echo " checked"; } ?> value="Sandblast" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Sandblast
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Primer".',') !== FALSE) { echo " checked"; } ?> value="Primer" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Primer
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Foam".',') !== FALSE) { echo " checked"; } ?> value="Foam" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Foam
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Rockguard".',') !== FALSE) { echo " checked"; } ?> value="Rockguard" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Rockguard

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_81" >
                            Notes<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_81" class="panel-collapse collapse">
                    <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Notes".',') !== FALSE) { echo " checked"; } ?> value="Notes" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Notes

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_9" >
                            Deliverables<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_9" class="panel-collapse collapse">
                    <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Status".',') !== FALSE) { echo " checked"; } ?> value="Status" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Status

                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Task".',') !== FALSE) { echo " checked"; } ?> value="Task" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Task

                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Doing Start and End Date".',') !== FALSE) { echo " checked"; } ?> value="Doing Start and End Date" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Doing Start and End Date
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Internal QA Date".',') !== FALSE) { echo " checked"; } ?> value="Internal QA Date" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Internal QA Date
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Client QA/Deliverable Date".',') !== FALSE) { echo " checked"; } ?> value="Client QA/Deliverable Date" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Client QA/Deliverable Date

                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Doing Assign To".',') !== FALSE) { echo " checked"; } ?> value="Doing Assign To" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Doing Assign To
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Internal QA Assign To".',') !== FALSE) { echo " checked"; } ?> value="Internal QA Assign To" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Internal QA Assign To
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Client QA/Deliverable Assign To".',') !== FALSE) { echo " checked"; } ?> value="Client QA/Deliverable Assign To" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Client QA/Deliverable Assign To
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."TO DO Date".',') !== FALSE) { echo " checked"; } ?> value="TO DO Date" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;TO DO Date
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Deliverable Date".',') !== FALSE) { echo " checked"; } ?> value="Deliverable Date" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Deliverable Date
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Estimated Time to Complete Work".',') !== FALSE) { echo " checked"; } ?> value="Estimated Time to Complete Work" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Estimated Time to Complete Work

                    <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Status Names:<br>(add names separated by a comma with no spaces)</label>
                        <div class="col-sm-8">
                          <input name="status" type="text" value="<?php echo $status; ?>" class="form-control"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Task Names:<br>(add names separated by a comma with no spaces)</label>
                        <div class="col-sm-8">
                          <input name="task" type="text" value="<?php echo $task; ?>" class="form-control"/>
                        </div>
                    </div>

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_10" >
                            Timer<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_10" class="panel-collapse collapse">
                    <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Small Timer".',') !== FALSE) { echo " checked"; } ?> value="Small Timer" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Small Timer
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Big Box Timer".',') !== FALSE) { echo " checked"; } ?> value="Big Box Timer" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Large Timer
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Regular Hours".',') !== FALSE) { echo " checked"; } ?> value="Regular Hours" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Regular Hours
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Overtime Hours".',') !== FALSE) { echo " checked"; } ?> value="Overtime Hours" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Overtime Hours
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Travel Hours".',') !== FALSE) { echo " checked"; } ?> value="Travel Hours" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Travel Hours
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Subsist Hours".',') !== FALSE) { echo " checked"; } ?> value="Subsist Hours" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Subsistence Hours

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Package" >
                            Package<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_Package" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Package".',') !== FALSE) { echo " checked"; } ?> value="Package" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Package
                        <br><br>

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Package Service Type".',') !== FALSE) { echo " checked"; } ?> value="Package Service Type" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Service Type&nbsp;&nbsp;
                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Package Category".',') !== FALSE) { echo " checked"; } ?> value="Package Category" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Category&nbsp;&nbsp;
                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Package Heading".',') !== FALSE) { echo " checked"; } ?> value="Package Heading" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Heading&nbsp;&nbsp;

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Promotion" >
                            Promotion<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_Promotion" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Promotion".',') !== FALSE) { echo " checked"; } ?> value="Promotion" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Promotion
                        <br><br>

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Promotion Service Type".',') !== FALSE) { echo " checked"; } ?> value="Promotion Service Type" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Service Type&nbsp;&nbsp;
                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Promotion Category".',') !== FALSE) { echo " checked"; } ?> value="Promotion Category" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Category&nbsp;&nbsp;
                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Promotion Heading".',') !== FALSE) { echo " checked"; } ?> value="Promotion Heading" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Heading&nbsp;&nbsp;

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Custom" >
                            Custom<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_Custom" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Custom".',') !== FALSE) { echo " checked"; } ?> value="Custom" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Custom
                        <br><br>

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Custom Service Type".',') !== FALSE) { echo " checked"; } ?> value="Custom Service Type" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Service Type&nbsp;&nbsp;
                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Custom Category".',') !== FALSE) { echo " checked"; } ?> value="Custom Category" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Category&nbsp;&nbsp;
                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Custom Heading".',') !== FALSE) { echo " checked"; } ?> value="Custom Heading" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Heading&nbsp;&nbsp;

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Material" >
                            Material<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_Material" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Material".',') !== FALSE) { echo " checked"; } ?> value="Material" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Material
                        <br><br>

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Material Code".',') !== FALSE) { echo " checked"; } ?> value="Material Code" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Code&nbsp;&nbsp;

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Services" >
                            Services<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_Services" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Services".',') !== FALSE) { echo " checked"; } ?> value="Services" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Services
                        <br><br>

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Services Service Type".',') !== FALSE) { echo " checked"; } ?> value="Services Service Type" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Service Type&nbsp;&nbsp;
                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Services Category".',') !== FALSE) { echo " checked"; } ?> value="Services Category" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Category&nbsp;&nbsp;
                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Services Heading".',') !== FALSE) { echo " checked"; } ?> value="Services Heading" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Heading&nbsp;&nbsp;

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Products" >
                            Products<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_Products" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Products".',') !== FALSE) { echo " checked"; } ?> value="Products" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Products
                        <br><br>

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Products Product Type".',') !== FALSE) { echo " checked"; } ?> value="Products Product Type" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Product Type&nbsp;&nbsp;
                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Products Category".',') !== FALSE) { echo " checked"; } ?> value="Products Category" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Category&nbsp;&nbsp;
                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Products Heading".',') !== FALSE) { echo " checked"; } ?> value="Products Heading" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Heading&nbsp;&nbsp;

                     </div>
                </div>
            </div>

            <!--
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_sred" >
                            SR&ED<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_sred" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."SRED".',') !== FALSE) { echo " checked"; } ?> value="SRED" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;SR&ED
                        <br><br>

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."SRED SRED Type".',') !== FALSE) { echo " checked"; } ?> value="SRED SRED Type" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;SR&ED Type&nbsp;&nbsp;
                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."SRED Category".',') !== FALSE) { echo " checked"; } ?> value="SRED Category" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Category&nbsp;&nbsp;
                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."SRED Heading".',') !== FALSE) { echo " checked"; } ?> value="SRED Heading" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Heading&nbsp;&nbsp;
                    </div>
                </div>
            </div>
            -->

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Staff" >
                            Staff<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_Staff" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Staff".',') !== FALSE) { echo " checked"; } ?> value="Staff" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Staff
                        <br><br>

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Staff Contact Person".',') !== FALSE) { echo " checked"; } ?> value="Staff Contact Person" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Contact Person&nbsp;&nbsp;

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Contractor" >
                            Contractor<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_Contractor" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Contractor".',') !== FALSE) { echo " checked"; } ?> value="Contractor" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Contractor
                        <br><br>

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Contractor Contact Person".',') !== FALSE) { echo " checked"; } ?> value="Contractor Contact Person" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Contact Person&nbsp;&nbsp;

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Clients" >
                            Clients<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_Clients" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Clients".',') !== FALSE) { echo " checked"; } ?> value="Clients" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Clients
                        <br><br>

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Clients Client Name".',') !== FALSE) { echo " checked"; } ?> value="Clients Client Name" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Client Name&nbsp;&nbsp;

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Clients Contact Person".',') !== FALSE) { echo " checked"; } ?> value="Clients Contact Person" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Contact Person&nbsp;&nbsp;

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_pl" >
                            Vendor Pricelist<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_pl" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Vendor Pricelist".',') !== FALSE) { echo " checked"; } ?> value="Vendor Pricelist" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Vendor Pricelist
                        <br><br>

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Vendor Pricelist Vendor".',') !== FALSE) { echo " checked"; } ?> value="Vendor Pricelist Vendor" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Vendor&nbsp;&nbsp;
                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Vendor Pricelist Price List".',') !== FALSE) { echo " checked"; } ?> value="Vendor Pricelist Price List" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Price List&nbsp;&nbsp;
                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Vendor Pricelist Category".',') !== FALSE) { echo " checked"; } ?> value="Vendor Pricelist Category" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Category&nbsp;&nbsp;
                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Vendor Pricelist Product".',') !== FALSE) { echo " checked"; } ?> value="Vendor Pricelist Product" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Product&nbsp;&nbsp;

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Customer" >
                            Customer<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_Customer" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Customer".',') !== FALSE) { echo " checked"; } ?> value="Customer" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Customer
                        <br><br>

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Customer Customer Name".',') !== FALSE) { echo " checked"; } ?> value="Customer Customer Name" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Customer Name&nbsp;&nbsp;
                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Customer Contact Person".',') !== FALSE) { echo " checked"; } ?> value="Customer Contact Person" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Contact Person&nbsp;&nbsp;

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Inventory" >
                            Inventory<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_Inventory" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Inventory".',') !== FALSE) { echo " checked"; } ?> value="Inventory" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Inventory
                        <br><br>

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Inventory Category".',') !== FALSE) { echo " checked"; } ?> value="Inventory Category" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Category&nbsp;&nbsp;
						<input type="checkbox" <?php if (strpos($project_manage_config, ','."Inventory Part Number".',') !== FALSE) { echo " checked"; } ?> value="Inventory Part Number" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Part Number
						&nbsp;&nbsp;
                        <input disabled checked type="checkbox" <?php if (strpos($project_manage_config, ','."Inventory Product Name".',') !== FALSE) { echo " checked"; } ?> value="Inventory Product Name" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Product Name&nbsp;&nbsp;

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Equipment" >
                            Equipment<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_Equipment" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Equipment".',') !== FALSE) { echo " checked"; } ?> value="Equipment" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Equipment
                        <br><br>

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Equipment Category".',') !== FALSE) { echo " checked"; } ?> value="Equipment Category" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Category&nbsp;&nbsp;
                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Equipment Unit/Serial Number".',') !== FALSE) { echo " checked"; } ?> value="Equipment Unit/Serial Number" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Unit/Serial Number&nbsp;&nbsp;
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Labour" >
                            Labour<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_Labour" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Labour".',') !== FALSE) { echo " checked"; } ?> value="Labour" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Labour
                        <br><br>

                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Labour Type".',') !== FALSE) { echo " checked"; } ?> value="Labour Type" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Type&nbsp;&nbsp;
                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Labour Heading".',') !== FALSE) { echo " checked"; } ?> value="Labour Heading" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Heading&nbsp;&nbsp;

                    </div>
                </div>
            </div>

        </div>

        <div class="form-group">
            <div class="col-sm-6">
                <a href="project_workflow_dashboard.php?tile=<?php echo $_GET['tile'];?>&tab=<?php echo $_GET['tab']; ?>" class="btn config-btn btn-lg">Back</a>
            </div>
            <div class="col-sm-6">
                <button	type="submit" name="inv_field"	value="inv_field" class="btn config-btn btn-lg	pull-right">Submit</button>
            </div>
			<div class="clearfix"></div>
        </div>

    <?php }
    ?>

    <?php if($_GET['type'] == 'dashboard') { ?>
        <h3>Dashboard
        <br>
        <span class="pull-right">
        <input type="radio"  onchange="dashboardViewConfig(this)" id="dash_<?php echo $tile.'_'.$tab; ?>" style="height: 20px; width: 25px;" <?php if($dashboard_view == 'table_view') { echo 'checked'; } ?> value="table_view" name="dashboard_view">Table View
        <input type="radio"  onchange="dashboardViewConfig(this)" id="dash_<?php echo $tile.'_'.$tab; ?>" style="height: 20px; width: 25px;" <?php if($dashboard_view == 'tile_view') { echo 'checked'; } ?> value="tile_view" name="dashboard_view">Tile View
        </span><br>
        </h3>
        <div class="panel-group" id="accordion2">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_0" >
                            Tile View<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_0" class="panel-collapse collapse">
                    <div class="panel-body">
                    Select Data From:

                    <?php
                    //$get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT project_path FROM project_workflow WHERE tile_name='$tile'"));

                    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT project_path FROM project_workflow"));

                    $project_path = $get_config['project_path'];

                    $to = explode(',', $project_path);
                    foreach($to as $current_tab)  {
                        if($tab != $current_tab) {
                            if($tile_data == $current_tab) {
                                $active = ' checked';
                            } else {
                                $active = '';
                            }
                            echo "<input type='radio' ".$active." name='tile_data' style='width:20px; height:20px;' value='".$current_tab."'>".$current_tab."&nbsp;&nbsp;";
                        }
                    }
                    ?>

                    <br>Display Tiles For Employee Name With Identification:
                    <?php
                    if ($tile_employee == "'All Active Employee'") {
                        $checked = 'checked';
                    } else {
                        $checked = '';
                    }
                    echo "<input type='checkbox' ".$checked." name='tile_employee[]' style='width:20px; height:20px;' value='All Active Employee'> All Active Employees&nbsp;&nbsp;";
                    $query = mysqli_query($dbc,"SELECT distinct(self_identification) FROM contacts");
                    while($row = mysqli_fetch_array($query)) {
                        if($row['self_identification'] != '') {
                            if (strpos($tile_employee, "'".$row['self_identification']."'") !== FALSE) {
                                $selected = 'checked';
                            } else {
                                $selected = '';
                            }
                            echo "<input type='checkbox' ".$selected." name='tile_employee[]' style='width:20px; height:20px;' value='". $row['self_identification']."'> ". $row['self_identification']."&nbsp;&nbsp;";
                        }
                    }
                    ?>

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_00" >
                            Search By<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_00" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Search By Staff".',') !== FALSE) { echo " checked"; } ?> value="Search By Staff" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Search By Staff
                        <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Search By Work Order".',') !== FALSE) { echo " checked"; } ?> value="Search By Work Order" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Search By Work Order
                        <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Search By Date".',') !== FALSE) { echo " checked"; } ?> value="Search By Date" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Search By Date

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_status" >
                            Display With Status<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_status" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Pending Status".',') !== FALSE) { echo " checked"; } ?> value="Pending Status" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Pending Status
                        <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Approved Status".',') !== FALSE) { echo " checked"; } ?> value="Approved Status" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Approved Status

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_1" >
                            Information<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_1" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Business".',') !== FALSE) { echo " checked"; } ?> value="Business" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Business
                        <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Contact".',') !== FALSE) { echo " checked"; } ?> value="Contact" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Contact
                        <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Rate Card".',') !== FALSE) { echo " checked"; } ?> value="Rate Card" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Rate Card
                        <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Short Name".',') !== FALSE) { echo " checked"; } ?> value="Short Name" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Short Name
                        <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Piece Work".',') !== FALSE) { echo " checked"; } ?> value="Piece Work" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Piece Work
                        <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Heading".',') !== FALSE) { echo " checked"; } ?> value="Heading" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Heading

                        <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Location".',') !== FALSE) { echo " checked"; } ?> value="Location" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Location
                        <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Job number".',') !== FALSE) { echo " checked"; } ?> value="Job number" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Job #
                        <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Customer PO/AFE#".',') !== FALSE) { echo " checked"; } ?> value="Customer PO/AFE#" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Customer PO/AFE#
                        <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Staff Name".',') !== FALSE) { echo " checked"; } ?> value="Staff Name" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Staff Name


                    </div>
                </div>
            </div>

			<div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_details" >
                            Details<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_details" class="panel-collapse collapse">
                    <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Work Order".',') !== FALSE) { echo " checked"; } ?> value="Work Order" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Work Order#
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Procedure ID".',') !== FALSE) { echo " checked"; } ?> value="Procedure ID" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Procedure ID
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Quote".',') !== FALSE) { echo " checked"; } ?> value="Quote" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Quote#
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."DWG".',') !== FALSE) { echo " checked"; } ?> value="DWG" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;DWG#
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Quantity".',') !== FALSE) { echo " checked"; } ?> value="Quantity" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp; Quantity
					<input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."S/N".',') !== FALSE) { echo " checked"; } ?> value="S/N" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp; S/N
					<input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Total Project Budget".',') !== FALSE) { echo " checked"; } ?> value="Total Project Budget" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp; Total Project Budget
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_2" >
                            Staff (Assign To)<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_2" class="panel-collapse collapse">
                    <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Staff(Assign To)".',') !== FALSE) { echo " checked"; } ?> value="Staff(Assign To)" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Staff (Assign To)

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_3" >
                            Dates<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_3" class="panel-collapse collapse">
                    <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Created Date".',') !== FALSE) { echo " checked"; } ?> value="Created Date" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Created Date
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Start Date".',') !== FALSE) { echo " checked"; } ?> value="Start Date" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Start Date
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Estimated Completion Date".',') !== FALSE) { echo " checked"; } ?> value="Estimated Completion Date" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Estimated Completion Date
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Work performed".',') !== FALSE) { echo " checked"; } ?> value="Work performed" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Work Performed
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Expiration Date".',') !== FALSE) { echo " checked"; } ?> value="Expiration Date" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp; Expiration Date
					<input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Effective Date".',') !== FALSE) { echo " checked"; } ?> value="Effective Date" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp; Effective Date
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Task Start Date".',') !== FALSE) { echo " checked"; } ?> value="Task Start Date" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp; Task Start Date
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Time Clock Start Date".',') !== FALSE) { echo " checked"; } ?> value="Time Clock Start Date" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp; Time Clock Start Date

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_5" >
                            Path-Milestone<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_5" class="panel-collapse collapse">
                    <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Path".',') !== FALSE) { echo " checked"; } ?> value="Path" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Path
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Milestone & Timeline".',') !== FALSE) { echo " checked"; } ?> value="Milestone & Timeline" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Milestone & Timeline

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_7" >
                            Service Information<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_7" class="panel-collapse collapse">
                    <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Service Type".',') !== FALSE) { echo " checked"; } ?> value="Service Type" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Service Type
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Service Category".',') !== FALSE) { echo " checked"; } ?> value="Service Category" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Service Category
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Service Heading".',') !== FALSE) { echo " checked"; } ?> value="Service Heading" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Service Heading

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_82" >
                            Documents<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_82" class="panel-collapse collapse">
                    <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Support Documents".',') !== FALSE) { echo " checked"; } ?> value="Support Documents" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Support Documents
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Support Links".',') !== FALSE) { echo " checked"; } ?> value="Support Links" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Support Links
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Review Documents".',') !== FALSE) { echo " checked"; } ?> value="Review Documents" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Review Documents
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Review Links".',') !== FALSE) { echo " checked"; } ?> value="Review Links" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Review Links

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_8" >
                            General Description<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_8" class="panel-collapse collapse">
                    <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Description

                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."General description".',') !== FALSE) { echo " checked"; } ?> value="General description" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;General Description
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Fabrication".',') !== FALSE) { echo " checked"; } ?> value="Fabrication" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Fabrication
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Paint".',') !== FALSE) { echo " checked"; } ?> value="Paint" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Paint
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Structure".',') !== FALSE) { echo " checked"; } ?> value="Structure" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Structure
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Rigging".',') !== FALSE) { echo " checked"; } ?> value="Rigging" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Rigging

                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Sandblast".',') !== FALSE) { echo " checked"; } ?> value="Sandblast" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Sandblast
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Primer".',') !== FALSE) { echo " checked"; } ?> value="Primer" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Primer
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Foam".',') !== FALSE) { echo " checked"; } ?> value="Foam" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Foam
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Rockguard".',') !== FALSE) { echo " checked"; } ?> value="Rockguard" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Rockguard

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_81" >
                            Notes<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_81" class="panel-collapse collapse">
                    <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Notes".',') !== FALSE) { echo " checked"; } ?> value="Notes" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Notes

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_9" >
                            Deliverables<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_9" class="panel-collapse collapse">
                    <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Status".',') !== FALSE) { echo " checked"; } ?> value="Status" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Status
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Doing Start and End Date".',') !== FALSE) { echo " checked"; } ?> value="Doing Start and End Date" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Doing Start and End Date
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Internal QA Date".',') !== FALSE) { echo " checked"; } ?> value="Internal QA Date" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Internal QA Date
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Client QA/Deliverable Date".',') !== FALSE) { echo " checked"; } ?> value="Client QA/Deliverable Date" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Client QA/Deliverable Date

                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Doing Assign To".',') !== FALSE) { echo " checked"; } ?> value="Doing Assign To" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Doing Assign To
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Internal QA Assign To".',') !== FALSE) { echo " checked"; } ?> value="Internal QA Assign To" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Internal QA Assign To
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Client QA/Deliverable Assign To".',') !== FALSE) { echo " checked"; } ?> value="Client QA/Deliverable Assign To" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Client QA/Deliverable Assign To                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."TO DO Date".',') !== FALSE) { echo " checked"; } ?> value="TO DO Date" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;TO DO Date
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Deliverable Date".',') !== FALSE) { echo " checked"; } ?> value="Deliverable Date" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Deliverable Date
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Estimated Time to Complete Work".',') !== FALSE) { echo " checked"; } ?> value="Estimated Time to Complete Work" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Estimated Time to Complete Work
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Task".',') !== FALSE) { echo " checked"; } ?> value="Task" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp; Task
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Task Start Time".',') !== FALSE) { echo " checked"; } ?> value="Task Start Time" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp; Task Start Time
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Task End Time".',') !== FALSE) { echo " checked"; } ?> value="Task End Time" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp; Task End Time
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Total Task Time".',') !== FALSE) { echo " checked"; } ?> value="Total Task Time" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp; Total Task Time
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Regular Hours".',') !== FALSE) { echo " checked"; } ?> value="Regular Hours" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp; Regular Hours
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Overtime Hours".',') !== FALSE) { echo " checked"; } ?> value="Overtime Hours" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp; Overtime Hours
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Travel Hours".',') !== FALSE) { echo " checked"; } ?> value="Travel Hours" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp; Travel Hours
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Subsist Hours".',') !== FALSE) { echo " checked"; } ?> value="Subsist Hours" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp; Subsistence Hours
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Task Status".',') !== FALSE) { echo " checked"; } ?> value="Task Status" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp; Task Status
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Task Approval".',') !== FALSE) { echo " checked"; } ?> value="Task Approval" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp; Task Approval
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_10" >
                            PDF<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_10" class="panel-collapse collapse">
                    <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Project Summary".',') !== FALSE) { echo " checked"; } ?> value="Project Summary" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Project Summary
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Review PDF".',') !== FALSE) { echo " checked"; } ?> value="Review PDF" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp; Review PDF

                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Front/Last Pages".',') !== FALSE) { echo " checked"; } ?> value="Front/Last Pages" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Front/Last Pages

                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Generate PDF".',') !== FALSE) { echo " checked"; } ?> value="Generate PDF" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Generate PDF

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_history" >
                            History<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_history" class="panel-collapse collapse">
                    <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."History".',') !== FALSE) { echo " checked"; } ?> value="History" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;History

                    </div>
                </div>
            </div>

			<div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_11" >
                            Approve/Reject<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_11" class="panel-collapse collapse">
                    <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Approve".',') !== FALSE) { echo " checked"; } ?> value="Approve" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Approve
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Email Approval".',') !== FALSE) { echo " checked"; } ?> value="Email Approval" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Email Approval

                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Reject".',') !== FALSE) { echo " checked"; } ?> value="Reject" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Reject
					<input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Delete".',') !== FALSE) { echo " checked"; } ?> value="Delete" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Delete

                    </div>
                </div>
            </div>

        </div>

        <br>

        <div class="form-group">
            <div class="col-sm-6">
                <a href="project_workflow_dashboard.php?tile=<?php echo $_GET['tile'];?>&tab=<?php echo $_GET['tab']; ?>" class="btn config-btn btn-lg">Back</a>
            </div>
            <div class="col-sm-6">
                <button	type="submit" name="inv_dashboard"	value="inv_dashboard" class="btn config-btn btn-lg	pull-right">Submit</button>
            </div>
			<div class="clearfix"></div>
        </div>

    <?php } ?>

    <?php if($_GET['type'] == 'pdf') {
        include ('field_config_quote.php');
    } ?>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>
