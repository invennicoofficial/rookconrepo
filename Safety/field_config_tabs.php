<?php
include ('../include.php');
checkAuthorised('safety');
error_reporting(0);

if ( isset($_GET['tab']) && !empty($_GET['tab']) ) {
    $tab = $_GET['tab'];
} else {
    $tab = '';
}

if (isset($_POST['submit'])) {
	// Enable Tabs
    $safety_subtabs = ',' . implode(',', $_POST['subtabs']) . ',';
    if (mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(`configid`) AS `configid` FROM `general_configuration` WHERE `name`='safety_dashboard'"))['configid'] > 0) {
        $query_update = "UPDATE `general_configuration` SET `value`='$safety_subtabs' WHERE `name`='safety_dashboard'";
        $result_update = mysqli_query($dbc, $query_update);
    } else {
        $query_insert = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('safety_dashboard', '$safety_subtabs')";
        $result_insert = mysqli_query($dbc, $query_insert);
    }
	
	// Set tabs to bypass list and go straight to first form
    $safety_bypass_list = ',' . implode(',', $_POST['safety_bypass_list']) . ',';
    if (mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(`configid`) AS `configid` FROM `general_configuration` WHERE `name`='safety_bypass_list'"))['configid'] > 0) {
        $query_update = "UPDATE `general_configuration` SET `value`='$safety_bypass_list' WHERE `name`='safety_bypass_list'";
        $result_update = mysqli_query($dbc, $query_update);
    } else {
        $query_insert = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('safety_bypass_list', '$safety_bypass_list')";
        $result_insert = mysqli_query($dbc, $query_insert);
    }

    echo '<script type="text/javascript">window.location.replace("field_config_tabs.php");</script>';
}
?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<h1>Safety</h1>
<div class="gap-top double-gap-bottom"><a href="safety.php?tab=<?= $tab; ?>" class="btn config-btn">Back to Dashboard</a></div>

<div class="tab-container">
    <div class="tab pull-left"><a href="field_config_tabs.php"><button class="btn brand-btn active_tab" name="tabs">Tabs</button></a></div>
    <div class="tab pull-left"><a href="field_config_safety.php"><button class="btn brand-btn" name="safety">Safety</button></a></div>
</div>

<div class="clearfix double-gap-bottom"></div>

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form"><?php
    $value_config = get_config($dbc, 'safety_dashboard');
    $bypass_config = get_config($dbc, 'safety_bypass_list'); ?>
    
	<div class="panel-group" id="accordion2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field" >
                        Choose Sub Tabs<span class="glyphicon glyphicon-minus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_field" class="panel-collapse collapse in">
                <div class="panel-body">
					<label class="form-checkbox"><input type="checkbox" <?= (strpos($value_config, ',Driving Log,') !== false) ? ' checked' : ''; ?> value="Driving Log" name="subtabs[]"> Driving Log</label>
					<label class="form-checkbox"><input type="checkbox" <?= (strpos($value_config, ',FLHA,') !== false) ? ' checked' : ''; ?> value="FLHA" name="subtabs[]"> FLHA</label>
					<label class="form-checkbox"><input type="checkbox" <?= (strpos($bypass_config, ',FLHA,') !== false) ? ' checked' : ''; ?> value="FLHA" name="safety_bypass_list[]"> Bypass FLHA List</label>
					<label class="form-checkbox"><input type="checkbox" <?= (strpos($value_config, ',Toolbox,') !== false) ? ' checked' : ''; ?> value="Toolbox" name="subtabs[]"> Toolbox</label>
					<label class="form-checkbox"><input type="checkbox" <?= (strpos($bypass_config, ',Toolbox,') !== false) ? ' checked' : ''; ?> value="Toolbox" name="safety_bypass_list[]"> Bypass Toolbox List</label>
					<label class="form-checkbox"><input type="checkbox" <?= (strpos($value_config, ',Tailgate,') !== false) ? ' checked' : ''; ?> value="Tailgate" name="subtabs[]"> Tailgate</label>
					<label class="form-checkbox"><input type="checkbox" <?= (strpos($bypass_config, ',Tailgate,') !== false) ? ' checked' : ''; ?> value="Tailgate" name="safety_bypass_list[]"> Bypass Tailgate List</label>
					<label class="form-checkbox"><input type="checkbox" <?= (strpos($value_config, ',Forms,') !== false) ? ' checked' : ''; ?> value="Forms" name="subtabs[]"> Forms</label>
					<label class="form-checkbox"><input type="checkbox" <?= (strpos($bypass_config, ',Forms,') !== false) ? ' checked' : ''; ?> value="Forms" name="safety_bypass_list[]"> Bypass Forms List</label>
					<label class="form-checkbox"><input type="checkbox" <?= (strpos($value_config, ',Manuals,') !== false) ? ' checked' : ''; ?> value="Manuals" name="subtabs[]"> Manuals</label>
					<label class="form-checkbox"><input type="checkbox" <?= (strpos($value_config, ',Incident Reports,') !== false) ? ' checked' : ''; ?> value="Incident Reports" name="subtabs[]"> Incident Reports</label>
                </div>
            </div>
        </div>
    </div>
        
    <div class="form-group">
        <div class="col-sm-6"><a href="safety.php?tab=<?= $tab; ?>" class="btn config-btn btn-lg">Back</a></div>
        <div class="col-sm-6"><button type="submit" name="submit" value="Submit" class="btn config-btn btn-lg pull-right">Submit</button></div>
    </div>
</form>
</div>
</div>

<?php include ('../footer.php'); ?>