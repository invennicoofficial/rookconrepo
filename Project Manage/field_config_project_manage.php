<?php
/*
Dashboard
*/
include ('../include.php');

error_reporting(0);

if (isset($_POST['inv_dashboard'])) {
    $project_manage_dashboard = implode(',',$_POST['project_manage_dashboard']);

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configpmid) AS configpmid FROM field_config_project_manage WHERE accordion IS NULL"));
    if($get_field_config['configpmid'] > 0) {
        $query_update_employee = "UPDATE `field_config_project_manage` SET project_manage_dashboard = '$project_manage_dashboard'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config_project_manage` (`project_manage_dashboard`) VALUES ('$project_manage_dashboard')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    echo '<script type="text/javascript"> window.location.replace("field_config_project_manage.php?type=dashboard"); </script>';
}

if (isset($_POST['inv_field'])) {
    $accordion = filter_var($_POST['accordion'],FILTER_SANITIZE_STRING);
    $project_manage = implode(',',$_POST['project_manage']);
    $order = $_POST['order'];

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configpmid) AS configpmid FROM field_config_project_manage WHERE accordion='$accordion'"));
    if($get_field_config['configpmid'] > 0) {
        $query_update_employee = "UPDATE `field_config_project_manage` SET project_manage = '$project_manage' WHERE accordion='$accordion'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config_project_manage` (`accordion`, `project_manage`, `order`) VALUES ('$accordion', '$project_manage', '$order')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    echo '<script type="text/javascript"> window.location.replace("field_config_project_manage.php?type=field&accr='.$accordion.'"); </script>';
}

?>
<script type="text/javascript">
$(document).ready(function() {
	$("#acc").change(function() {
        window.location = 'field_config_project_manage.php?type=field&accr='+this.value;
	});
});
</script>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
                <!--<a href="project_manage.php?category=Top" class="btn config-btn pull-right">Back</a>-->
				<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>
<br><br>
<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<div class="panel-group" id="accordion2">

    <?php
    $accr = $_GET['accr'];
    $type = $_GET['type'];

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT project_manage FROM field_config_project_manage WHERE accordion='$accr'"));
    $project_manage_config = ','.$get_field_config['project_manage'].',';

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT project_manage_dashboard FROM field_config_project_manage WHERE accordion IS NULL"));
    $project_manage_dashboard_config = ','.$get_field_config['project_manage_dashboard'].',';

    $get_field_order = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT GROUP_CONCAT(`order` SEPARATOR ',') AS all_order FROM field_config_project_manage"));

    $active_field = '';
    $active_dashboard = '';

    if($_GET['type'] == 'field') {
        $active_field = 'active_tab';
    }
    if($_GET['type'] == 'dashboard') {
        $active_dashboard = 'active_tab';
    }

    echo "<a href='field_config_project_manage.php?type=field'><button type='button' class='btn brand-btn mobile-block ".$active_field."' >Fields</button></a>&nbsp;&nbsp;";
    echo "<a href='field_config_project_manage.php?type=dashboard'><button type='button' class='btn brand-btn mobile-block ".$active_dashboard."' >Dashboard</button></a>&nbsp;&nbsp;";

    echo '<br><br><Br>';

    if($_GET['type'] == 'field') {
        ?>
        <div class="form-group">
            <label for="fax_number"	class="col-sm-4	control-label">Accordion:</label>
            <div class="col-sm-8">
                <select data-placeholder="Choose a Accordion..." id="acc" name="accordion" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <option <?php if ($accr == "Information") { echo " selected"; } ?> value="Information"><?php echo get_field_config_project_manage($dbc, 'Information', 'order'); ?> : Information</option>
                  <option <?php if ($accr == "Business") { echo " selected"; } ?> value="Business"><?php echo get_field_config_project_manage($dbc, 'Business', 'order'); ?> : Business</option>
                  <option <?php if ($accr == "Rate Card") { echo " selected"; } ?> value="Rate Card"><?php echo get_field_config_project_manage($dbc, 'Rate Card', 'order'); ?> : Rate Card</option>
                  <option <?php if ($accr == "Staff(Assign To)") { echo " selected"; } ?> value="Staff(Assign To)"><?php echo get_field_config_project_manage($dbc, 'Staff(Assign To)', 'order'); ?> : Staff(Assign To)</option>
                  <option <?php if ($accr == "Dates") { echo " selected"; } ?> value="Dates"><?php echo get_field_config_project_manage($dbc, 'Dates', 'order'); ?> : Dates</option>
                  <option <?php if ($accr == "Documents") { echo " selected"; } ?> value="Documents"><?php echo get_field_config_project_manage($dbc, 'Documents', 'order'); ?> : Documents</option>
                  <option <?php if ($accr == "Service Information") { echo " selected"; } ?> value="Service Information"><?php echo get_field_config_project_manage($dbc, 'Service Information', 'order'); ?> : Service Information</option>
                  <option <?php if ($accr == "Deliverables") { echo " selected"; } ?> value="Deliverables"><?php echo get_field_config_project_manage($dbc, 'Deliverables', 'order'); ?> : Deliverables</option>
                  <option <?php if ($accr == "Details") { echo " selected"; } ?> value="Details"><?php echo get_field_config_project_manage($dbc, 'Details', 'order'); ?> : Details</option>
                  <option <?php if ($accr == "Description") { echo " selected"; } ?> value="Description"><?php echo get_field_config_project_manage($dbc, 'Description', 'order'); ?> : Description</option>
                  <option <?php if ($accr == "Package") { echo " selected"; } ?> value="Package"><?php echo get_field_config_project_manage($dbc, 'Package', 'order'); ?> : Package</option>
                  <option <?php if ($accr == "Promotion") { echo " selected"; } ?> value="Promotion"><?php echo get_field_config_project_manage($dbc, 'Promotion', 'order'); ?> : Promotion</option>
                  <option <?php if ($accr == "Custom") { echo " selected"; } ?> value="Custom"><?php echo get_field_config_project_manage($dbc, 'Custom', 'order'); ?> : Custom</option>
                  <option <?php if ($accr == "Material") { echo " selected"; } ?> value="Material"><?php echo get_field_config_project_manage($dbc, 'Material', 'order'); ?> : Material</option>
                  <option <?php if ($accr == "Services") { echo " selected"; } ?> value="Services"><?php echo get_field_config_project_manage($dbc, 'Services', 'order'); ?> : Services</option>
                  <option <?php if ($accr == "Products") { echo " selected"; } ?> value="Products"><?php echo get_field_config_project_manage($dbc, 'Products', 'order'); ?> : Products</option>
                  <!-- <option <?php if ($accr == "SR&ED") { echo " selected"; } ?> value="SR&ED"><?php echo get_field_config_project_manage($dbc, 'SR&ED', 'order'); ?> : SR&ED</option> -->
                  <option <?php if ($accr == "Staff") { echo " selected"; } ?> value="Staff"><?php echo get_field_config_project_manage($dbc, 'Staff', 'order'); ?> : Staff</option>
                  <option <?php if ($accr == "Contractor") { echo " selected"; } ?> value="Contractor"><?php echo get_field_config_project_manage($dbc, 'Contractor', 'order'); ?> : Contractor</option>
                  <option <?php if ($accr == "Clients") { echo " selected"; } ?> value="Clients"><?php echo get_field_config_project_manage($dbc, 'Clients', 'order'); ?> : Clients</option>
                  <option <?php if ($accr == "Vendor Pricelist") { echo " selected"; } ?> value="Vendor Pricelist"><?php echo get_field_config_project_manage($dbc, 'Vendor Pricelist', 'order'); ?> : Vendor Pricelist</option>
                  <option <?php if ($accr == "Customer") { echo " selected"; } ?> value="Customer"><?php echo get_field_config_project_manage($dbc, 'Customer', 'order'); ?> : Customer</option>
                  <option <?php if ($accr == "Inventory") { echo " selected"; } ?> value="Inventory"><?php echo get_field_config_project_manage($dbc, 'Inventory', 'order'); ?> : Inventory</option>
                  <option <?php if ($accr == "Equipment") { echo " selected"; } ?> value="Equipment"><?php echo get_field_config_project_manage($dbc, 'Equipment', 'order'); ?> : Equipment</option>
                  <option <?php if ($accr == "Labour") { echo " selected"; } ?> value="Labour"><?php echo get_field_config_project_manage($dbc, 'Labour', 'order'); ?> : Labour</option>
                  <option <?php if ($accr == "Budget Info") { echo " selected"; } ?> value="Budget Info"><?php echo get_field_config_project_manage($dbc, 'Budget Info', 'order'); ?> : Budget Info</option>
                  <option <?php if ($accr == "Path-Milestone") { echo " selected"; } ?> value="Path-Milestone"><?php echo get_field_config_project_manage($dbc, 'Path-Milestone', 'order'); ?> : Path-Milestone</option>
                  <option <?php if ($accr == "Labour") { echo " selected"; } ?> value="Labour"><?php echo get_field_config_project_manage($dbc, 'Labour', 'order'); ?> : Labour</option>
                  <option <?php if ($accr == "Crew") { echo " selected"; } ?> value="Crew"><?php echo get_field_config_project_manage($dbc, 'Crew', 'order'); ?> : Crew</option>
                  <option <?php if ($accr == "Notes") { echo " selected"; } ?> value="Notes"><?php echo get_field_config_project_manage($dbc, 'Notes', 'order'); ?> : Notes</option>
                  <option <?php if ($accr == "Timer") { echo " selected"; } ?> value="Timer"><?php echo get_field_config_project_manage($dbc, 'Timer', 'order'); ?> : Timer</option>
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
                        if(get_field_config_project_manage($dbc, $accr, 'order') == $m) {
                            $selected = ' selected';
                        } else {
                            $selected = '';
                        }
                        if (strpos(','.$get_field_order['all_order'].',', ','.$m.',') !== FALSE) {
                            $disabled = ' disabled';
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
                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."Job number".',') !== FALSE) { echo " checked"; } ?> value="Job number" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Job number
                        <input type="checkbox" <?php if (strpos($project_manage_config, ','."AFE number".',') !== FALSE) { echo " checked"; } ?> value="AFE number" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;AFE number


                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_2" >
                            Staff(Assign To)<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_2" class="panel-collapse collapse">
                    <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Staff(Assign To)".',') !== FALSE) { echo " checked"; } ?> value="Staff(Assign To)" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Staff(Assign To)

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
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Work performed".',') !== FALSE) { echo " checked"; } ?> value="Work performed" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Work performed

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
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Already known".',') !== FALSE) { echo " checked"; } ?> value="Already known" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Already known
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Looking to Achieve".',') !== FALSE) { echo " checked"; } ?> value="Looking to Achieve" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Looking to Achieve


                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Plan".',') !== FALSE) { echo " checked"; } ?> value="Plan" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Plan
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Next Steps".',') !== FALSE) { echo " checked"; } ?> value="Next Steps" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Next Steps
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Learnt".',') !== FALSE) { echo " checked"; } ?> value="Learnt" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Learnt
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
                            Description<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_8" class="panel-collapse collapse">
                    <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Description

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
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Doing Start and End Date".',') !== FALSE) { echo " checked"; } ?> value="Doing Start and End Date" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Doing Start and End Date
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Internal QA Date".',') !== FALSE) { echo " checked"; } ?> value="Internal QA Date" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Internal QA Date
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Client QA/Deliverable Date".',') !== FALSE) { echo " checked"; } ?> value="Client QA/Deliverable Date" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Client QA/Deliverable Date

                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Doing Assign To".',') !== FALSE) { echo " checked"; } ?> value="Doing Assign To" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Doing Assign To
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Internal QA Assign To".',') !== FALSE) { echo " checked"; } ?> value="Internal QA Assign To" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Internal QA Assign To
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Client QA/Deliverable Assign To".',') !== FALSE) { echo " checked"; } ?> value="Client QA/Deliverable Assign To" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Client QA/Deliverable Assign To
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."TO DO Date".',') !== FALSE) { echo " checked"; } ?> value="TO DO Date" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;TO DO Date
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Deliverable Date".',') !== FALSE) { echo " checked"; } ?> value="Deliverable Date" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Deliverable Date
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Estimated Time to Complete Work".',') !== FALSE) { echo " checked"; } ?> value="Estimated Time to Complete Work" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Estimated Time to Complete Work

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
                    <input type="checkbox" <?php if (strpos($project_manage_config, ','."Big Box Timer".',') !== FALSE) { echo " checked"; } ?> value="Big Box Timer" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Big Box Timer

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
                        <input checked disabled type="checkbox" <?php if (strpos($project_manage_config, ','."Inventory Product Name".',') !== FALSE) { echo " checked"; } ?> value="Inventory Product Name" style="height: 20px; width: 20px;" name="project_manage[]">&nbsp;&nbsp;Product Name&nbsp;&nbsp;

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
            <div class="col-sm-4 clearfix">
                <!--<a href="project_manage.php?category=Top" class="btn config-btn pull-right">Back</a>-->
				<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>
            </div>
            <div class="col-sm-8">
                <button	type="submit" name="inv_field"	value="inv_field" class="btn config-btn btn-lg	pull-right">Submit</button>
            </div>
        </div>

    <?php }
    ?>

    <?php if($_GET['type'] == 'dashboard') { ?>
        <h3>Dashboard</h3>
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

                        <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Business".',') !== FALSE) { echo " checked"; } ?> value="Business" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Business
                        <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Contact".',') !== FALSE) { echo " checked"; } ?> value="Contact" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Contact
                        <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Rate Card".',') !== FALSE) { echo " checked"; } ?> value="Rate Card" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Rate Card
                        <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Short Name".',') !== FALSE) { echo " checked"; } ?> value="Short Name" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Short Name
                        <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Piece Work".',') !== FALSE) { echo " checked"; } ?> value="Piece Work" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Piece Work
                        <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Heading".',') !== FALSE) { echo " checked"; } ?> value="Heading" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Heading

                        <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Location".',') !== FALSE) { echo " checked"; } ?> value="Location" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Location
                        <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Job number".',') !== FALSE) { echo " checked"; } ?> value="Job number" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Job number
                        <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."AFE number".',') !== FALSE) { echo " checked"; } ?> value="AFE number" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;AFE number


                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_2" >
                            Staff(Assign To)<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_2" class="panel-collapse collapse">
                    <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Staff(Assign To)".',') !== FALSE) { echo " checked"; } ?> value="Staff(Assign To)" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Staff(Assign To)

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
                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Work performed".',') !== FALSE) { echo " checked"; } ?> value="Work performed" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Work performed

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
                            Description<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_8" class="panel-collapse collapse">
                    <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($project_manage_dashboard_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="project_manage_dashboard[]">&nbsp;&nbsp;Description

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
                    </div>
                </div>
            </div>

        </div>

        <br>

        <div class="form-group">
            <div class="col-sm-4 clearfix">
                <!--<a href="project_manage.php?category=Top" class="btn config-btn pull-right">Back</a>-->
				<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>
            </div>
            <div class="col-sm-8">
                <button	type="submit" name="inv_dashboard"	value="inv_dashboard" class="btn config-btn btn-lg	pull-right">Submit</button>
            </div>
        </div>

    <?php } ?>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>