<?php
/*
Dashboard
*/
include ('../include.php');

error_reporting(0);
checkAuthorised('client_info');

$cat_page = $_GET[ 'category' ];
$tab_name = FOLDER_NAME.'_tabs';

if (isset($_POST['add_tab'])) {
	$contacts_tabs = filter_var($_POST['contacts_tabs'],FILTER_SANITIZE_STRING);
    $contacts_tabs = trim(str_replace(',,',',',str_replace('Staff','',$contacts_tabs)),',');
	if(strpos($contacts_tabs,'Staff') !== false) {
		echo "<script>alert('Staff is no longer available through the Contacts tile. Please use the Staff tile.');</script>";
	}

	$get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='".FOLDER_NAME."_tabs'"));

	if($get_config['configid'] > 0) {
		$query_update_employee = "UPDATE `general_configuration` SET value = '$contacts_tabs' WHERE name='".FOLDER_NAME."_tabs'";
		$result_update_employee = mysqli_query($dbc, $query_update_employee);
	} else {
		$query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('".FOLDER_NAME."_tabs', '$contacts_tabs')";
		$result_insert_config = mysqli_query($dbc, $query_insert_config);
	}

	echo '<script type="text/javascript">window.location.replace("field_config_contacts.php?type=tab"); </script>';
}

if (isset($_POST['add_general'])) {
	$contacts_classification = filter_var($_POST['contacts_classification'],FILTER_SANITIZE_STRING);
	$get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='".FOLDER_NAME."_classification'"));
	if($get_config['configid'] > 0) {
		$query_update_employee = "UPDATE `general_configuration` SET value = '$contacts_classification' WHERE name='".FOLDER_NAME."_classification'";
		$result_update_employee = mysqli_query($dbc, $query_update_employee);
	} else {
		$query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('".FOLDER_NAME."_classification', '$contacts_classification')";
		$result_insert_config = mysqli_query($dbc, $query_insert_config);
	}

	$contacts_region = filter_var($_POST['contacts_region'], FILTER_SANITIZE_STRING);
	$get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='".FOLDER_NAME."_region'"));
	if($get_config['configid'] > 0) {
		$query_update_employee = "UPDATE `general_configuration` SET value = '$contacts_region' WHERE name='".FOLDER_NAME."_region'";
		$result_update_employee = mysqli_query($dbc, $query_update_employee);
	} else {
		$query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('".FOLDER_NAME."_region', '$contacts_region')";
		$result_insert_config = mysqli_query($dbc, $query_insert_config);
	}

    /*
	$company_name = filter_var($_POST['company_name'],FILTER_SANITIZE_STRING);
	$get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='company_name'"));
	if($get_config['configid'] > 0) {
		$query_update_employee = "UPDATE `general_configuration` SET value = '$company_name' WHERE name='company_name'";
		$result_update_employee = mysqli_query($dbc, $query_update_employee);
	} else {
		$query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('company_name', '$company_name')";
		$result_insert_config = mysqli_query($dbc, $query_insert_config);
	}
    */

	$field_tabs = filter_var($_POST['contact_field_subtabs'],FILTER_SANITIZE_STRING);
	$get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='".FOLDER_NAME."_field_subtabs'"));
	if($get_config['configid'] > 0) {
		$query_update_employee = "UPDATE `general_configuration` SET value = '$field_tabs' WHERE name='".FOLDER_NAME."_field_subtabs'";
		$result_update_employee = mysqli_query($dbc, $query_update_employee);
	} else {
		$query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('".FOLDER_NAME."_field_subtabs', '$field_tabs')";
		$result_insert_config = mysqli_query($dbc, $query_insert_config);
	}

	echo '<script type="text/javascript"> window.location.replace("field_config_contacts.php?type=general"); </script>';
}

if (isset($_POST['inv_dashboard'])) {
	$tab_dashboard = filter_var($_POST['tab_dashboard'],FILTER_SANITIZE_STRING);
	$contacts_dashboard = implode(',',$_POST['contacts_dashboard']);
	if (strpos(','.$contacts_dashboard.',',','.'Category'.',') === false) {
		$contacts_dashboard = 'Category,'.$contacts_dashboard;
	}

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configcontactid) AS configcontactid FROM field_config_contacts WHERE tile_name = '".FOLDER_NAME."' AND tab='$tab_dashboard' AND accordion IS NULL"));
	if($get_field_config['configcontactid'] > 0) {
		$query_update_employee = "UPDATE `field_config_contacts` SET contacts_dashboard = '$contacts_dashboard' WHERE tab='$tab_dashboard' AND tile_name = '".FOLDER_NAME."'";
		$result_update_employee = mysqli_query($dbc, $query_update_employee);
	} else {
		$query_insert_config = "INSERT INTO `field_config_contacts` (`tab`, `contacts_dashboard`, `tile_name`) VALUES ('$tab_dashboard', '$contacts_dashboard', '".FOLDER_NAME."')";
		$result_insert_config = mysqli_query($dbc, $query_insert_config);
	}
	echo '<script type="text/javascript"> window.location.replace("field_config_contacts.php?type=dashboard&tab='.$tab_dashboard.'"); </script>';
}

if (isset($_POST['inv_field'])) {
	$tab_field = filter_var($_POST['tab_field'],FILTER_SANITIZE_STRING);
	$subtab_field = filter_var($_POST['subtab_field'],FILTER_SANITIZE_STRING);
	$accordion = filter_var($_POST['accordion'],FILTER_SANITIZE_STRING);
	$order = filter_var($_POST['order'],FILTER_SANITIZE_STRING);

	$add_order = ( $order != '' ) ? ", `order` = '$order'" : "";
	$add_subtab = ( $subtab_field != '' ) ? ", `subtab` = '$subtab_field'" : "";

	$contacts = implode(',',$_POST['contacts']);

	if (strpos(','.$contacts.',',','.'Category'.',') === false) {
		$contacts = 'Category,'.$contacts;
	}

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configcontactid) AS configcontactid FROM field_config_contacts WHERE tile_name = '".FOLDER_NAME."' AND tab='$tab_field' AND accordion='$accordion'"));
	if($get_field_config['configcontactid'] > 0) {
		$query_update_employee = "UPDATE `field_config_contacts` SET `contacts` = '$contacts' $add_order $add_subtab WHERE tile_name = '".FOLDER_NAME."' AND tab='$tab_field' AND accordion='$accordion'";
		$result_update_employee = mysqli_query($dbc, $query_update_employee);
	} else {
		$query_insert_config = "INSERT INTO `field_config_contacts` (`tab`, `subtab`, `accordion`, `contacts`, `order`, `tile_name`) VALUES ('$tab_field', '$subtab_field', '$accordion', '$contacts', '$order', '".FOLDER_NAME."')";
		$result_insert_config = mysqli_query($dbc, $query_insert_config);
	}

	echo '<script type="text/javascript"> window.location.replace("field_config_contacts.php?type=field&tab='.$tab_field.'&subtab='.$subtab_field.'&accr='.$accordion.'"); </script>';
}

?>
<script type="text/javascript">
$(document).ready(function() {
	$("#tab_dashboard").change(function() {
		window.location = 'field_config_contacts.php?type=dashboard&tab='+this.value;
	});
	$("#tab_field").change(function() {
		window.location = 'field_config_contacts.php?type=field&tab='+this.value;
	});
	$("#subtab_field").change(function() {
		//window.location = 'field_config_contacts.php?type=field&tab='+$("#tab_field").val()+'&subtab='+this.value;
	});

	$("#acc").change(function() {
		var subtab = $("#subtab_field").val();
		if(subtab == undefined) {
			subtab = '';
		}
		window.location = 'field_config_contacts.php?type=field&tab='+$("#tab_field").val()+'&subtab='+subtab+'&accr='+this.value;
	});

	$('input.show_impexp_contact').on('change', function() {
		if($(this).prop('checked') == true){
			var value = $(this).attr('value');
		} else { var value = ''; }
		$.ajax({	//create an ajax request to load_page.php
		type: "GET",
		url: "../ajax_all.php?fill=show_impexp_contact&value="+value,
		dataType: "html",   //expect html to be returned
		success: function(response){
		}
		});
	});
	var selected = $('[name=accordion]').val();
	var options = $('[name=accordion]').find('option');
	options.sort(function(a,b) {
		if(b.text == '')
			return 0;
		if(a.text.substring(0,1) == ':')
			return 0;

		aValue = parseInt(a.text.split(':')[0].trim());
		bValue = parseInt(b.text.split(':')[0].trim());
		if(aValue > bValue) {
			return 1;
		} else {
			return -1;
		}
		return 0;
	});
	$('[name=accordion]').empty().append(options);
	$('[name=accordion]').val(selected).trigger('change.select2');

	if($('#acc').val() == '') {
		$('input[type=checkbox]').attr('disabled','disabled');
		$('button[type=submit]').attr('disabled','disabled');
	}
});
</script>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<h1>Client Information Settings</h1>
<a href="contacts.php?category=<?= $cat_page; ?>&filter=Top" class="gap-top btn config-btn">Back to Dashboard</a>
<!--<a href="#" class="btn config-btn" onclick="history.go(-1);return false;">Back</a>-->

<form id="form1" name="form1" method="post"	action="field_config_contacts.php" enctype="multipart/form-data" class="form-horizontal" role="form">

<div class="panel-group" id="accordion2">

	<?php

	$contype = $_GET['tab'];
	$accr = $_GET['accr'];
	$type = $_GET['type'];

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT contacts FROM field_config_contacts WHERE tile_name = '".FOLDER_NAME."' AND tab='$contype' AND accordion='$accr'"));
	$contacts_config = ','.$get_field_config['contacts'].',';

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT contacts_dashboard FROM field_config_contacts WHERE tile_name = '".FOLDER_NAME."' AND tab='$contype' AND accordion IS NULL"));
	$contacts_dashboard_config = ','.$get_field_config['contacts_dashboard'].',';
	$get_field_order = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT GROUP_CONCAT(`order` SEPARATOR ',') AS all_order FROM field_config_contacts WHERE tile_name = '".FOLDER_NAME."' AND tab='$contype'"));

	$active_tab = '';
	$active_field = '';
	$active_dashboard = '';
	$active_general = '';

	if($_GET['type'] == 'tab') {
		$active_tab = 'active_tab';
	}
	if($_GET['type'] == 'field') {
		$active_field = 'active_tab';
	}
	if($_GET['type'] == 'dashboard') {
		$active_dashboard = 'active_tab';
	}
	if($_GET['type'] == 'general') {
		$active_general = 'active_tab';
	}
	if($_GET['type'] == 'impexp') {
		$impexp = 'active_tab';
	}
	?>

	<div class="tab-container"><?php
		echo "<div class='pull-left tab'><span class='popover-examples list-inline'><a data-toggle='tooltip' data-placement='top' title='Remember to select the tab you would like to make changes to.'><img src='". WEBSITE_URL ."/img/info.png' width='20'></a></span><a href='field_config_contacts.php?type=tab'><button type='button' class='btn brand-btn mobile-block ".$active_tab."' >Tabs</button></a></div>";

		echo "<div class='pull-left tab'><span class='popover-examples list-inline'><a data-toggle='tooltip' data-placement='top' title='Click this to add the sub tabs in Contacts. This will help to organize your contacts by a specific business.'><img src='". WEBSITE_URL ."/img/info.png' width='20'></a></span><a href='field_config_contacts.php?type=general'><button type='button' class='btn brand-btn mobile-block ".$active_general."' >Sub Tabs</button></a></div>";

		echo "<div class='pull-left tab'><span class='popover-examples list-inline'><a data-toggle='tooltip' data-placement='top' title='Click this to add or remove so you can organize your own tabs.'><img src='". WEBSITE_URL ."/img/info.png' width='20'></a></span><a href='field_config_contacts.php?type=dashboard'><button type='button' class='btn brand-btn mobile-block ".$active_dashboard."' >Dashboard</button></a></div>";

		echo "<div class='pull-left tab'><span class='popover-examples list-inline'><a data-toggle='tooltip' data-placement='top' title='Click to add fields within the tabs, you must add a tab before adding fields. These determine which fields you want when creating a new type of contact.'><img src='". WEBSITE_URL ."/img/info.png' width='20'></a></span><a href='field_config_contacts.php?type=field'><button type='button' class='btn brand-btn mobile-block ".$active_field."' >Fields</button></a></div>";

		echo "<div class='pull-left tab'><span class='popover-examples list-inline'><a data-toggle='tooltip' data-placement='top' title='Click this to enable/disable the ability to export a spreadsheet of your Contacts. This functionality also allows you to edit/add multiple contacts at a time by importing a spreadsheet into the software.'><img src='". WEBSITE_URL ."/img/info.png' width='20'></a></span><a href='field_config_contacts.php?type=impexp'><button type='button' class='btn brand-btn mobile-block ".$impexp."' >Import/Export</button></a></div>";
		?>
	</div>

	<div class="clearfix"></div><?php

	if($_GET['type'] == 'tab') { ?>
		<h3>Tabs</h3>
		<div class="panel-group" id="accordion_tabs">
			<!-- How To -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here first for the Step by Step guide on how to create tabs."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<a data-toggle="collapse" data-parent="#accordion_tabs" href="#collapse_1">
							How To<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>
				<div id="collapse_1" class="panel-collapse collapse">
					<div class="panel-body">
						<strong>Step 1:</strong><br />
						Think of the main headings that your business separates all of your contacts into. What would be the main categories used to organize your contacts? Here are a list of headings to get you started: Business,Staff,Customers,Vendors,Labor.<br />
						<br />
						<strong>Step 2:</strong><br />
						Highlight the suggestions and copy them into the Add Tabs bar (located below this section).<br />
						<br />
						<strong>Step 3:</strong><br />
						If you are not using the above suggestions, simply click to the next Add Tabs section to fill out which tabs will be displayed on the Contacts Dashboard.<br />
						<br />
						<strong>Step 4:</strong><br />
						Click Submit to make sure your changes are captured. If you click Back, it will not save your changes.<br />
						<br />
						<strong>Reminder:</strong><br />
						Separate these tabs with a comma just as shown above in the suggestions, with no spaces between the comma and the next entry. The order in which you enter them into the Add Tabs bar will be the order they appear in your Contacts Dashboard.
					</div>
				</div>
			</div><!-- .panel .panel-default -->

			<!-- Add Tabs -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to add your own tabs."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<a data-toggle="collapse" data-parent="#accordion_tabs" href="#collapse_2">
							Add Tabs<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>
				<div id="collapse_2" class="panel-collapse collapse">
					<div class="panel-body">
						Add tabs separated by a comma in the order you want them on the dashboard:<br />
						<br />
						<input name="contacts_tabs" type="text" value="<?php echo str_replace(',,',',',str_replace('Staff','',get_config($dbc, FOLDER_NAME.'_tabs'))); ?>" class="form-control"/><br />
						<div class="form-group">
							<div class="col-sm-6">
								<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
							</div>
							<div class="col-sm-6">
								<button	type="submit" name="add_tab" value="add_tab" class="btn config-btn btn-lg pull-right">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</div><!-- .panel .panel-default -->

		</div><!-- #accordion_tabs --><?php
	}

	if($_GET['type'] == 'field') {
		?>
		<div class="form-group triple-pad-top">
			<label for="fax_number"	class="col-sm-4	control-label">Tabs:</label>
			<div class="col-sm-8">
				<select data-placeholder="Choose a tab..." id="tab_field" name="tab_field" class="chosen-select-deselect form-control" width="380">
				  <option value=""></option>
				  <?php
					$tabs = str_replace(',,',',',str_replace('Staff','',get_config($dbc, FOLDER_NAME.'_tabs')));
					$each_tab = explode(',', $tabs);
					foreach ($each_tab as $cat_tab) {
						if ($contype == $cat_tab) {
							$selected = 'selected="selected"';
						} else {
							$selected = '';
						}
						echo "<option ".$selected." value='". $cat_tab."'>".$cat_tab.'</option>';
					}
				  ?>
				</select>
			</div>
		</div>

		<div class="form-group">
			<label for="fax_number"	class="col-sm-4	control-label"><span class="popover-examples list-inline" style="margin:0 5px 0 0"><a data-toggle="tooltip" data-placement="top" title="This is the vertically stacked list of items seen below. Click to determine which list of items you would like to make changes to. This must be selected before you can select fields."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>Accordion:</label>
			<div class="col-sm-8">
				<select data-placeholder="Choose an Accordion..." id="acc" name="accordion" class="chosen-select-deselect form-control" width="380">
					<?php include('../Contacts/config_accordion_list.php'); ?>
				</select>
			</div>
			<?php $subtab_config = get_config($dbc, FOLDER_NAME.'_field_subtabs');
			if($subtab_config != '') { ?>
				<label for="fax_number"	class="col-sm-4	control-label">Field Sub Tab:</label>
				<div class="col-sm-8">
					<select data-placeholder="Select a Sub Tab..." id="subtab_field" name="subtab_field" class="chosen-select-deselect form-control" width="380">
						<option></option>
						<?php
						if($subtab == '') {
							$subtab = get_field_config_contacts($dbc, $accr, 'subtab', $contype);
						}
						$subtabs = explode(',',$subtab_config);
						foreach($subtabs as $this_tab) {
							echo "<option ".($this_tab == $subtab ? 'selected' : '')." value='$this_tab'>$this_tab</option>";
						}
						?>
					</select>
				</div>
			<?php } else {
				$sql_clear_subtabs = "UPDATE `field_config_contacts` SET `subtab`='' WHERE tile_name = '".FOLDER_NAME."' AND `tab` NOT IN ('Staff','Profile')";
				mysqli_query($dbc, $sql_clear_subtabs);
			} ?>
			<label for="fax_number"	class="col-sm-4	control-label">Sort Order:</label>
			<div class="col-sm-8">
				<select data-placeholder="Choose an Order..." name="order" class="chosen-select-deselect form-control" width="380">
					<option value=""></option>
					<?php
					$accr_order = get_field_config_contacts($dbc, $accr, 'order', $contype, $subtab);
					for($m=1;$m<=40;$m++) { ?>
						<option <?php if ($accr_order == $m) { echo  'selected="selected"'; } else if (strpos(','.$get_field_order['all_order'].',', ','.$m.',') !== FALSE) { echo " disabled"; } ?>
							value="<?php echo $m;?>"><?php echo $m;?></option>
					<?php }
					?>
				</select>
			</div>
		</div>

		<h3>Fields</h3>
		<div class="panel-group" id="accordion2">
			<!-- How To -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here first for the Step by Step guide on how to personalize fields within your Add Contacts view."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_0">
							How To<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>
				<div id="collapse_0" class="panel-collapse collapse">
					<div class="panel-body">
						<strong>Step 1:</strong><br />
						Make sure you click on the dropdown menu beside Tabs. This dropdown menu is populated from the tabs you have created.<br />
						<br />
						<strong>Step 2:</strong><br />
						Once you are in the desired tab, click on the next drop down menu labelled Accordion. This allows to to add whichever Accordion you would like to the selected tab.<br />
						<br />
						<strong>Step 3:</strong><br />
						You can then choose the order in which it appears in the Choose an Order (drop down menu).<br />
						<br />
						<strong>Step 4:</strong><br />
						Go through each Accordion heading to check off which fields you would like to appear in that tab.<br />
						<br />
						<strong>Step 5:</strong><br />
						Click Submit to make sure your changes are captured. If you click Back, it will not save your changes.
					</div>
				</div>
			</div><!-- .panel .panel-default -->

			<?php include('../Contacts/config_field_list.php'); ?>

		</div>

		<div class="form-group">
			<div class="col-sm-6 clearfix">
				<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
				<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
			</div>
			<div class="col-sm-6">
				<button	type="submit" name="inv_field"	value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
			</div>
		</div>

	<?php }
	?>

	<?php if($_GET['type'] == 'dashboard') { ?>
		<div class="form-group triple-pad-top">
			<label for="fax_number"	class="col-sm-4	control-label">Tabs:</label>
			<div class="col-sm-8">
				<select data-placeholder="Choose tabs..." id="tab_dashboard" name="tab_dashboard" class="chosen-select-deselect form-control" width="380">
				  <option value=""></option>
				  <?php
					$tabs = get_config($dbc, FOLDER_NAME.'_tabs');
					$each_tab = explode(',', $tabs);
					foreach ($each_tab as $cat_tab) {
						if ($contype == $cat_tab) {
							$selected = 'selected="selected"';
						} else {
							$selected = '';
						}
						echo "<option ".$selected." value='". $cat_tab."'>".$cat_tab.'</option>';
					}
				  ?>
				</select>
			</div>
		</div>

		<h3>Dashboard</h3>
		<div class="panel-group" id="accordion2">
			<!-- How To -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here first for the Step by Step guide on how to personalize fields shown on the Contacts Dashboard view."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_0">
							How To<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>
				<div id="collapse_0" class="panel-collapse collapse">
					<div class="panel-body">
						<strong>Step 1:</strong><br />
						Make sure you click on the dropdown menu beside Tabs. This allows you to add or remove fields in the tab. This dropdown menu is populated from the tabs you have created.<br />
						<br />
						<strong>Step 2:</strong><br />
						Once you are in the desired tab, go through each accordion heading to check off which fields you would like to appear in that tab.<br />
						<br />
						<strong>Step 3:</strong><br />
						Click Submit to make sure your changes are captured. If you click Back, it will not save your changes.
					</div>
				</div>
			</div><!-- .panel .panel-default -->

			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Contact Descriptions."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_1" >
							Contact Description<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>

				<div id="collapse_1" class="panel-collapse collapse">
					<div class="panel-body">
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Employee ID".',') !== FALSE) { echo " checked"; } ?> value="Employee ID" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Employee ID
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Business".',') !== FALSE) { echo " checked"; } ?> value="Business" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Business
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Name".',') !== FALSE) { echo " checked"; } ?> value="Name" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Name
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."First Name".',') !== FALSE) { echo " checked"; } ?> value="First Name" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;First Name
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Last Name".',') !== FALSE) { echo " checked"; } ?> value="Last Name" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Last Name
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Assigned Staff".',') !== FALSE) { echo " checked"; } ?> value="Assigned Staff" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Assigned Staff

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Role".',') !== FALSE) { echo " checked"; } ?> value="Role" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Role

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Division".',') !== FALSE) { echo " checked"; } ?> value="Division" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Division

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Name on Account".',') !== FALSE) { echo " checked"; } ?> value="Name on Account" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Name on Account
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Business Contacts".',') !== FALSE) { echo " checked"; } ?> value="Business Contacts" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Business Contacts
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Operating As".',') !== FALSE) { echo " checked"; } ?> value="Operating As" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Operating As
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Emergency Contact".',') !== FALSE) { echo " checked"; } ?> value="Emergency Contact" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Emergency Contact

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Occupation".',') !== FALSE) { echo " checked"; } ?> value="Occupation" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Occupation
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Office Phone".',') !== FALSE) { echo " checked"; } ?> value="Office Phone" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Office Phone
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Cell Phone".',') !== FALSE) { echo " checked"; } ?> value="Cell Phone" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Cell Phone
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Home Phone".',') !== FALSE) { echo " checked"; } ?> value="Home Phone" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Home Phone
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Fax".',') !== FALSE) { echo " checked"; } ?> value="Fax" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Fax
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Email Address".',') !== FALSE) { echo " checked"; } ?> value="Email Address" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Email Address
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Website".',') !== FALSE) { echo " checked"; } ?> value="Website" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Website
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Customer Address".',') !== FALSE) { echo " checked"; } ?> value="Customer Address" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Customer Address
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Application".',') !== FALSE) { echo " checked"; } ?> value="Application" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Application
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Contact Image".',') !== FALSE) { echo " checked"; } ?> value="Contact Image" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Contact Image
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Description
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Contact Since".',') !== FALSE) { echo " checked"; } ?> value="Contact Since" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Contact Since
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Date of Last Contact".',') !== FALSE) { echo " checked"; } ?> value="Date of Last Contact" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Date of Last Contact
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Referred By".',') !== FALSE) { echo " checked"; } ?> value="Referred By" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Referred By
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Company".',') !== FALSE) { echo " checked"; } ?> value="Company" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Company
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Position".',') !== FALSE) { echo " checked"; } ?> value="Position" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Position
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Title".',') !== FALSE) { echo " checked"; } ?> value="Title" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Title

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Client Tax Exemption".',') !== FALSE) { echo " checked"; } ?> value="Client Tax Exemption" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Client Tax Exemption
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Tax Exemption Number".',') !== FALSE) { echo " checked"; } ?> value="Tax Exemption Number" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Tax Exemption #
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."DUNS".',') !== FALSE) { echo " checked"; } ?> value="DUNS" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;DUNS
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."CAGE".',') !== FALSE) { echo " checked"; } ?> value="CAGE" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;CAGE
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Self Identification".',') !== FALSE) { echo " checked"; } ?> value="Self Identification" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Self Identification
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Credit Card on File".',') !== FALSE) { echo " checked"; } ?> value="Credit Card on File" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Credit Card on File
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Intake Form".',') !== FALSE) { echo " checked"; } ?> value="Intake Form" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Intake Form

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Rating".',') !== FALSE) { echo " checked"; } ?> value="Rating" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Rating

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Nick Name".',') !== FALSE) { echo " checked"; } ?> value="Nick Name" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Nickname
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Profile Link".',') !== FALSE) { echo " checked"; } ?> value="Profile Link" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Profile Link

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Contact Category".',') !== FALSE) { echo " checked"; } ?> value="Contact Category" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Contact Category
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Gender".',') !== FALSE) { echo " checked"; } ?> value="Gender" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Gender
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."License".',') !== FALSE) { echo " checked"; } ?> value="License" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;License
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Credentials".',') !== FALSE) { echo " checked"; } ?> value="Credentials" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Credentials
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Alberta Health Care No".',') !== FALSE) { echo " checked"; } ?> value="Alberta Health Care No" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Alberta Health Care #
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Invoice".',') !== FALSE) { echo " checked"; } ?> value="Invoice" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Invoice

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."MVA".',') !== FALSE) { echo " checked"; } ?> value="MVA" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;MVA

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Maintenance Patient".',') !== FALSE) { echo " checked"; } ?> value="Maintenance Patient" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Maintenance Patient

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Correspondence Language".',') !== FALSE) { echo " checked"; } ?> value="Correspondence Language" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Correspondence Language

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Amount To Bill".',') !== FALSE) { echo " checked"; } ?> value="Amount To Bill" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Amount To Bill
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Amount Owing".',') !== FALSE) { echo " checked"; } ?> value="Amount Owing" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Amount Owing
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Amount Credit".',') !== FALSE) { echo " checked"; } ?> value="Amount Credit" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Amount To Credit

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Account Balance".',') !== FALSE) { echo " checked"; } ?> value="Account Balance" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Account Balance
						<br><br>
						<div class="form-group">
							<div class="col-sm-6">
								<!--<a href="inventory.php?category=All" class="btn config-btn pull-right">Back</a>-->
								<a href="#" class="btn config-btn btn-lg" onclick="history.go(-1);return false;">Back</a>
							</div>
							<div class="col-sm-6">
								<button	type="submit" name="inv_dashboard" value="inv_dashboard" class="btn config-btn btn-lg pull-right">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</div>


			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Site Information."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_site" >
							Site Information<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>

				<div id="collapse_site" class="panel-collapse collapse">
					<div class="panel-body">

			            <input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Customer(Client/Customer/Business)".',') !== FALSE) { echo " checked"; } ?> value="Customer(Client/Customer/Business)" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Customer(Client/Customer/Business)

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Site Name (Location)".',') !== FALSE) { echo " checked"; } ?> value="Site Name (Location)" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Site Name (Location)

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Display Name".',') !== FALSE) { echo " checked"; } ?> value="Display Name" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Display Name

						<br><br>
						<div class="form-group">
							<div class="col-sm-6">
								<!--<a href="inventory.php?category=All" class="btn config-btn pull-right">Back</a>-->
								<a href="#" class="btn config-btn btn-lg" onclick="history.go(-1);return false;">Back</a>
							</div>
							<div class="col-sm-6">
								<button	type="submit" name="inv_dashboard" value="inv_dashboard" class="btn config-btn btn-lg pull-right">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Vehicle Descriptions."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_2" >
						   Vehicle Description<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>

				<div id="collapse_2" class="panel-collapse collapse">
					<div class="panel-body">

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."License Plate #".',') !== FALSE) { echo " checked"; } ?> value="License Plate #" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;License Plate #
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Upload License Plate".',') !== FALSE) { echo " checked"; } ?> value="Upload License Plate" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Upload License Plate
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."CARFAX".',') !== FALSE) { echo " checked"; } ?> value="CARFAX" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;CARFAX

						<br><br>
						<div class="form-group">
							<div class="col-sm-6">
								<!--<a href="inventory.php?category=All" class="btn config-btn pull-right">Back</a>-->
								<a href="#" class="btn config-btn btn-lg" onclick="history.go(-1);return false;">Back</a>
							</div>
							<div class="col-sm-6">
								<button	type="submit" name="inv_dashboard" value="inv_dashboard" class="btn config-btn btn-lg pull-right">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Location Descriptions."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_3" >
							Location<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>

				<div id="collapse_3" class="panel-collapse collapse">
					<div class="panel-body">
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Address".',') !== FALSE) { echo " checked"; } ?> value="Address" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Address
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Mailing Address".',') !== FALSE) { echo " checked"; } ?> value="Mailing Address" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Mailing Address
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Business Address".',') !== FALSE) { echo " checked"; } ?> value="Business Address" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Business Address

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Ship To Address".',') !== FALSE) { echo " checked"; } ?> value="Ship To Address" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Ship To Address
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Postal Code".',') !== FALSE) { echo " checked"; } ?> value="Postal Code" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Postal Code
						<input type="checkbox" <?php if (strpos($contacts_config, ','."Zip Code".',') !== FALSE) { echo " checked"; } ?> value="Zip Code" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Zip/Postal Code
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."City".',') !== FALSE) { echo " checked"; } ?> value="City" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;City
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Province".',') !== FALSE) { echo " checked"; } ?> value="Province" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Province
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."State".',') !== FALSE) { echo " checked"; } ?> value="State" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;State
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Country".',') !== FALSE) { echo " checked"; } ?> value="Country" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Country

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Ship Country".',') !== FALSE) { echo " checked"; } ?> value="Ship Country" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Ship Country
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Ship City".',') !== FALSE) { echo " checked"; } ?> value="Ship City" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Ship City
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Ship State".',') !== FALSE) { echo " checked"; } ?> value="Ship State" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Ship State
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Ship Zip".',') !== FALSE) { echo " checked"; } ?> value="Ship Zip" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Ship Zip

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Google Maps Address".',') !== FALSE) { echo " checked"; } ?> value="Google Maps Address" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Google Maps Address
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."City Part".',') !== FALSE) { echo " checked"; } ?> value="City Part" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;City Part

						<br><br>
						<div class="form-group">
							<div class="col-sm-6">
								<!--<a href="inventory.php?category=All" class="btn config-btn pull-right">Back</a>-->
								<a href="#" class="btn config-btn btn-lg" onclick="history.go(-1);return false;">Back</a>
							</div>
							<div class="col-sm-6">
								<button	type="submit" name="inv_dashboard" value="inv_dashboard" class="btn config-btn btn-lg pull-right">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Payment Descriptions."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_4" >
							Payment Description<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>

				<div id="collapse_4" class="panel-collapse collapse">
					<div class="panel-body">

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Account Number".',') !== FALSE) { echo " checked"; } ?> value="Account Number" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Account Number
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Payment Type".',') !== FALSE) { echo " checked"; } ?> value="Payment Type" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Payment Type
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Payment Name".',') !== FALSE) { echo " checked"; } ?> value="Payment Name" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Payment Name
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Payment Address".',') !== FALSE) { echo " checked"; } ?> value="Payment Address" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Payment Address
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Payment City".',') !== FALSE) { echo " checked"; } ?> value="Payment City" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Payment City
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Payment State".',') !== FALSE) { echo " checked"; } ?> value="Payment State" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Payment State
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Payment Postal Code".',') !== FALSE) { echo " checked"; } ?> value="Payment Postal Code" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Payment Postal Code
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Payment Zip Code".',') !== FALSE) { echo " checked"; } ?> value="Payment Zip Code" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Payment Zip/Postal Code
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."GST #".',') !== FALSE) { echo " checked"; } ?> value="GST #" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;GST #
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."PST #".',') !== FALSE) { echo " checked"; } ?> value="PST #" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;PST #
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Vendor GST #".',') !== FALSE) { echo " checked"; } ?> value="Vendor GST #" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Vendor GST #
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Payment Information".',') !== FALSE) { echo " checked"; } ?> value="Payment Information" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Payment Information
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Account Number".',') !== FALSE) { echo " checked"; } ?> value="Account Number" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Account Number
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Total Monthly Rate".',') !== FALSE) { echo " checked"; } ?> value="Total Monthly Rate" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Total Monthly Rate
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Total Annual Rate".',') !== FALSE) { echo " checked"; } ?> value="Total Annual Rate" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Total Annual Rate
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Condo Fees".',') !== FALSE) { echo " checked"; } ?> value="Condo Fees" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Condo Fees
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Deposit".',') !== FALSE) { echo " checked"; } ?> value="Deposit" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Deposit
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Damage Deposit".',') !== FALSE) { echo " checked"; } ?> value="Damage Deposit" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Damage Deposit
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Quote Description" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Quote Description
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Pricing Level".',') !== FALSE) { echo " checked"; } ?> value="Pricing Level" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Pricing Level
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Cost".',') !== FALSE) { echo " checked"; } ?> value="Cost" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Cost
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Final Retail Price".',') !== FALSE) { echo " checked"; } ?> value="Final Retail Price" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Final Retail Price
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Admin Price".',') !== FALSE) { echo " checked"; } ?> value="Admin Price" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Admin Price
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Wholesale Price".',') !== FALSE) { echo " checked"; } ?> value="Wholesale Price" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Wholesale Price
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Commercial Price".',') !== FALSE) { echo " checked"; } ?> value="Commercial Price" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Commercial Price
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Client Price".',') !== FALSE) { echo " checked"; } ?> value="Client Price" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Client Price
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Minimum Billable".',') !== FALSE) { echo " checked"; } ?> value="Minimum Billable" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Minimum Billable
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Estimated Hours".',') !== FALSE) { echo " checked"; } ?> value="Estimated Hours" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Estimated Hours
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Actual Hours".',') !== FALSE) { echo " checked"; } ?> value="Actual Hours" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Actual Hours
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."MSRP".',') !== FALSE) { echo " checked"; } ?> value="MSRP" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;MSRP
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Hourly Rate".',') !== FALSE) { echo " checked"; } ?> value="Hourly Rate" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Hourly Rate
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Monthly Rate".',') !== FALSE) { echo " checked"; } ?> value="Monthly Rate" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Monthly Rate
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Semi Monthly Rate".',') !== FALSE) { echo " checked"; } ?> value="Semi Monthly Rate" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Semi Monthly Rate
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Daily Rate".',') !== FALSE) { echo " checked"; } ?> value="Daily Rate" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Daily Rate
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."HR Rate Work".',') !== FALSE) { echo " checked"; } ?> value="HR Rate Work" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;HR Rate Work
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."HR Rate Travel".',') !== FALSE) { echo " checked"; } ?> value="HR Rate Travel" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;HR Rate Travel
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Field Day Cost".',') !== FALSE) { echo " checked"; } ?> value="Field Day Cost" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Field Day Cost
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Field Day Billable".',') !== FALSE) { echo " checked"; } ?> value="Field Day Billable" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Field Day Billable

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Probation Pay Rate".',') !== FALSE) { echo " checked"; } ?> value="Probation Pay Rate" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Probation Pay Rate
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Base Pay".',') !== FALSE) { echo " checked"; } ?> value="Base Pay" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Base Pay
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Performance Pay".',') !== FALSE) { echo " checked"; } ?> value="Performance Pay" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Performance Pay

						<br><br>
						<div class="form-group">
							<div class="col-sm-6">
								<!--<a href="inventory.php?category=All" class="btn config-btn pull-right">Back</a>-->
								<a href="#" class="btn config-btn btn-lg" onclick="history.go(-1);return false;">Back</a>
							</div>
							<div class="col-sm-6">
								<button	type="submit" name="inv_dashboard" value="inv_dashboard" class="btn config-btn btn-lg pull-right">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Property Descriptions."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_5" >
							Property Description<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>

				<div id="collapse_5" class="panel-collapse collapse">
					<div class="panel-body">

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Property Information".',') !== FALSE) { echo " checked"; } ?> value="Property Information" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Property Information
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Upload Property Information".',') !== FALSE) { echo " checked"; } ?> value="Upload Property Information" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Upload Property Information
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Unit #".',') !== FALSE) { echo " checked"; } ?> value="Unit #" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Unit #
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Condo Fees".',') !== FALSE) { echo " checked"; } ?> value="Condo Fees" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Condo Fees
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Base Rent".',') !== FALSE) { echo " checked"; } ?> value="Base Rent" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Base Rent
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Base Rent/Sq. Ft.".',') !== FALSE) { echo " checked"; } ?> value="Base Rent/Sq. Ft." style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Base Rent/Sq. Ft.
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."CAC".',') !== FALSE) { echo " checked"; } ?> value="CAC" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;CAC
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."CAC/Sq. Ft.".',') !== FALSE) { echo " checked"; } ?> value="CAC/Sq. Ft." style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;CAC/Sq. Ft.
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Property Tax".',') !== FALSE) { echo " checked"; } ?> value="Property Tax" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Property Tax
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Property Tax/Sq. Ft.".',') !== FALSE) { echo " checked"; } ?> value="Property Tax/Sq. Ft." style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Property Tax/Sq. Ft.
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Upload Inspection".',') !== FALSE) { echo " checked"; } ?> value="Upload Inspection" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Upload Inspection
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Bay #".',') !== FALSE) { echo " checked"; } ?> value="Bay #" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Bay #

						<br><br>
						<div class="form-group">
							<div class="col-sm-6">
								<!--<a href="inventory.php?category=All" class="btn config-btn pull-right">Back</a>-->
								<a href="#" class="btn config-btn btn-lg" onclick="history.go(-1);return false;">Back</a>
							</div>
							<div class="col-sm-6">
								<button	type="submit" name="inv_dashboard" value="inv_dashboard" class="btn config-btn btn-lg pull-right">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Contract/Form Information."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_6" >
							Contract/Form Info<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>

				<div id="collapse_6" class="panel-collapse collapse">
					<div class="panel-body">

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Upload Letter of Intent".',') !== FALSE) { echo " checked"; } ?> value="Upload Letter of Intent" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Upload Letter of Intent
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Upload Vendor Documents".',') !== FALSE) { echo " checked"; } ?> value="Upload Vendor Documents" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Upload Vendor Documents
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Upload Marketing Material".',') !== FALSE) { echo " checked"; } ?> value="Upload Marketing Material" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Upload Marketing Material
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Upload Purchase Contract".',') !== FALSE) { echo " checked"; } ?> value="Upload Purchase Contract" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Upload Purchase Contract
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Upload Support Contract".',') !== FALSE) { echo " checked"; } ?> value="Upload Support Contract" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Upload Support Contract
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Upload Support Terms".',') !== FALSE) { echo " checked"; } ?> value="Upload Support Terms" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Upload Support Terms
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Upload Rental Contract".',') !== FALSE) { echo " checked"; } ?> value="Upload Rental Contract" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Upload Rental Contract
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Upload Management Contract".',') !== FALSE) { echo " checked"; } ?> value="Upload Management Contract" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Upload Management Contract
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Upload Articles of Incorporation".',') !== FALSE) { echo " checked"; } ?> value="Upload Articles of Incorporation" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Upload Articles of Incorporation
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Option to Renew".',') !== FALSE) { echo " checked"; } ?> value="Option to Renew" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Option to Renew

						<br><br>
						<div class="form-group">
							<div class="col-sm-6">
								<!--<a href="inventory.php?category=All" class="btn config-btn pull-right">Back</a>-->
								<a href="#" class="btn config-btn btn-lg" onclick="history.go(-1);return false;">Back</a>
							</div>
							<div class="col-sm-6">
								<button	type="submit" name="inv_dashboard" value="inv_dashboard" class="btn config-btn btn-lg pull-right">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Dates."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_7" >
							Dates<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>

				<div id="collapse_7" class="panel-collapse collapse">
					<div class="panel-body">

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Start Date".',') !== FALSE) { echo " checked"; } ?> value="Start Date" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Start Date
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Expiry Date".',') !== FALSE) { echo " checked"; } ?> value="Expiry Date" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Expiry Date
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Renewal Date".',') !== FALSE) { echo " checked"; } ?> value="Renewal Date" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Renewal Date
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Lease Term Date".',') !== FALSE) { echo " checked"; } ?> value="Lease Term Date" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Lease Term Date
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Lease Term - # of years".',') !== FALSE) { echo " checked"; } ?> value="Lease Term - # of years" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Lease Term - # of years
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Date Contract Signed".',') !== FALSE) { echo " checked"; } ?> value="Date Contract Signed" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Date Contract Signed
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Option to Renew Date".',') !== FALSE) { echo " checked"; } ?> value="Option to Renew Date" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Option to Renew Date
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Rate Increase Date".',') !== FALSE) { echo " checked"; } ?> value="Rate Increase Date" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Rate Increase Date
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Insurance Expiry Date".',') !== FALSE) { echo " checked"; } ?> value="Insurance Expiry Date" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Insurance Expiry Date
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Account Expiry Date".',') !== FALSE) { echo " checked"; } ?> value="Account Expiry Date" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Account Expiry Date

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Hire Date".',') !== FALSE) { echo " checked"; } ?> value="Hire Date" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Hire Date
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Probation End Date".',') !== FALSE) { echo " checked"; } ?> value="Probation End Date" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Probation End Date
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Probation Expiry Reminder Date".',') !== FALSE) { echo " checked"; } ?> value="Probation Expiry Reminder Date" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Probation Expiry Reminder Date
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Birth Date".',') !== FALSE) { echo " checked"; } ?> value="Birth Date" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Birth Date

						<br><br>
						<div class="form-group">
							<div class="col-sm-6">
								<!--<a href="inventory.php?category=All" class="btn config-btn pull-right">Back</a>-->
								<a href="#" class="btn config-btn btn-lg" onclick="history.go(-1);return false;">Back</a>
							</div>
							<div class="col-sm-6">
								<button	type="submit" name="inv_dashboard" value="inv_dashboard" class="btn config-btn btn-lg pull-right">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Insurance."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_8" >
							Insurance<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>

				<div id="collapse_8" class="panel-collapse collapse">
					<div class="panel-body">

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Upload Commercial Insurance".',') !== FALSE) { echo " checked"; } ?> value="Upload Commercial Insurance" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Upload Commercial Insurance
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Commercial Insurer".',') !== FALSE) { echo " checked"; } ?> value="Commercial Insurer" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Commercial Insurer
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Upload Residential Insurance".',') !== FALSE) { echo " checked"; } ?> value="Upload Residential Insurance" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Upload Residential Insurance
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Residential Insurer".',') !== FALSE) { echo " checked"; } ?> value="Residential Insurer" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Residential Insurer
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."WCB #".',') !== FALSE) { echo " checked"; } ?> value="WCB #" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;WCB #
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Upload WCB".',') !== FALSE) { echo " checked"; } ?> value="Upload WCB" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Upload WCB

						<br><br>
						<div class="form-group">
							<div class="col-sm-6">
								<!--<a href="inventory.php?category=All" class="btn config-btn pull-right">Back</a>-->
								<a href="#" class="btn config-btn btn-lg" onclick="history.go(-1);return false;">Back</a>
							</div>
							<div class="col-sm-6">
								<button	type="submit" name="inv_dashboard" value="inv_dashboard" class="btn config-btn btn-lg pull-right">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Comments."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_9" >
							Comments<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>

				<div id="collapse_9" class="panel-collapse collapse">
					<div class="panel-body">

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."General Comments".',') !== FALSE) { echo " checked"; } ?> value="General Comments" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;General Comments
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Comments".',') !== FALSE) { echo " checked"; } ?> value="Comments" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Comments
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Notes".',') !== FALSE) { echo " checked"; } ?> value="Notes" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Notes

						<br><br>
						<div class="form-group">
							<div class="col-sm-6">
								<!--<a href="inventory.php?category=All" class="btn config-btn pull-right">Back</a>-->
								<a href="#" class="btn config-btn btn-lg" onclick="history.go(-1);return false;">Back</a>
							</div>
							<div class="col-sm-6">
								<button	type="submit" name="inv_dashboard" value="inv_dashboard" class="btn config-btn btn-lg pull-right">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Status."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_15" >
							Status<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>

				<div id="collapse_15" class="panel-collapse collapse">
					<div class="panel-body">

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Status".',') !== FALSE) { echo " checked"; } ?> value="Status" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Status

						<br><br>
						<div class="form-group">
							<div class="col-sm-6">
								<!--<a href="inventory.php?category=All" class="btn config-btn pull-right">Back</a>-->
								<a href="#" class="btn config-btn btn-lg" onclick="history.go(-1);return false;">Back</a>
							</div>
							<div class="col-sm-6">
								<button	type="submit" name="inv_dashboard" value="inv_dashboard" class="btn config-btn btn-lg pull-right">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose Social Media Links."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_sm" >
							Social Media Links<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>

				<div id="collapse_sm" class="panel-collapse collapse">
					<div class="panel-body">

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."LinkedIn".',') !== FALSE) { echo " checked"; } ?> value="LinkedIn" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;LinkedIn
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Twitter".',') !== FALSE) { echo " checked"; } ?> value="Twitter" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Twitter

						<br><br>
						<div class="form-group">
							<div class="col-sm-6">
								<!--<a href="inventory.php?category=All" class="btn config-btn pull-right">Back</a>-->
								<a href="#" class="btn config-btn btn-lg" onclick="history.go(-1);return false;">Back</a>
							</div>
							<div class="col-sm-6">
								<button	type="submit" name="inv_dashboard" value="inv_dashboard" class="btn config-btn btn-lg pull-right">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Login Information."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_16" >
							Login Information<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>

				<div id="collapse_16" class="panel-collapse collapse">
					<div class="panel-body">

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."User Name".',') !== FALSE) { echo " checked"; } ?> value="User Name" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Username

						<br><br>
						<div class="form-group">
							<div class="col-sm-6">
								<!--<a href="inventory.php?category=All" class="btn config-btn pull-right">Back</a>-->
								<a href="#" class="btn config-btn btn-lg" onclick="history.go(-1);return false;">Back</a>
							</div>
							<div class="col-sm-6">
								<button	type="submit" name="inv_dashboard" value="inv_dashboard" class="btn config-btn btn-lg pull-right">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to count pop ups."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_count" >
							Count Pop Ups<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>

				<div id="collapse_count" class="panel-collapse collapse">
					<div class="panel-body">

						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Total Sites".',') !== FALSE) { echo " checked"; } ?> value="Total Sites" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Total Sites
						<input type="checkbox" <?php if (strpos($contacts_dashboard_config, ','."Total Customers".',') !== FALSE) { echo " checked"; } ?> value="Total Customers" style="height: 20px; width: 20px;" name="contacts_dashboard[]">&nbsp;&nbsp;Total Customers

						<br><br>
						<div class="form-group">
							<div class="col-sm-6">
								<!--<a href="inventory.php?category=All" class="btn config-btn pull-right">Back</a>-->
								<a href="#" class="btn config-btn btn-lg" onclick="history.go(-1);return false;">Back</a>
							</div>
							<div class="col-sm-6">
								<button	type="submit" name="inv_dashboard" value="inv_dashboard" class="btn config-btn btn-lg pull-right">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>

		<br>

		<div class="form-group">
			<div class="col-sm-6 clearfix">
				<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
				<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
			</div>
			<div class="col-sm-6">
				<button	type="submit" name="inv_dashboard"	value="inv_dashboard" class="btn config-btn btn-lg pull-right">Submit</button>
			</div>
		</div>

	<?php }

	if($_GET['type'] == 'general') { ?>
		<h3>Tabs</h3>
		<div class="panel-group" id="accordion_tabs">
			<!-- How To -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here first for the Step by Step Guide on how to organize which classifications you would like to use for each sub tab."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<a data-toggle="collapse" data-parent="#accordion_tabs" href="#collapse_1">
							How To<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>
				<div id="collapse_1" class="panel-collapse collapse">
					<div class="panel-body">
						<strong>Step 1:</strong><br />
						Think of the sub tabs you would like to organize your Contacts Dashboard. If you do not wish to add any sub tabs at this point, simply skip this section.<br />
						<br />
						<strong>Step 2:</strong><br />
						Simply click to the next Add sub tabs section to fill out which sub tabs will be displayed on the Contacts Dashboard.<br />
						<br />
						<strong>Step 3:</strong><br />
						Click Submit to make sure your changes are captured. If you click Back, it will not save your changes.<br />
						<br />
						<strong>Reminder:</strong><br />
						Separate these tabs with a comma, with no spaces between the comma and the next entry. The order in which you enter them into the Add Divisions bar will be the order they appear in your Contacts Dashboard.
					</div>
				</div>
			</div><!-- .panel .panel-default -->

			<!-- Add Regions -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to add your own regions."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<a data-toggle="collapse" data-parent="#accordion_tabs" href="#collapse_region">
							Add Regions<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>
				<div id="collapse_region" class="panel-collapse collapse">
					<div class="panel-body">
						Add Region separated by a comma in the order you want them on the dashboard:<br />
						<br />
						<input name="contacts_region" type="text" value="<?php echo get_config($dbc, FOLDER_NAME.'_region'); ?>" class="form-control"/><br />

						<div class="form-group">
							<div class="col-sm-6">
								<!--<a href="inventory.php?category=All" class="btn config-btn pull-right">Back</a>-->
								<a href="#" class="btn config-btn btn-lg" onclick="history.go(-1);return false;">Back</a>
							</div>
							<div class="col-sm-6">
								<button	type="submit" name="add_general" value="add_general" class="btn config-btn btn-lg pull-right">Submit</button>
							</div>
						</div>

						<!--
                        <div class="form-group">
							<label for="fax_number"	class="col-sm-4	control-label">Company Name:</label>
							<div class="col-sm-8">
								<input name="company_name" type="text" value="<?php echo get_config($dbc, 'company_name'); ?>" class="form-control"/>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-6">
								<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg pull-left">Back</a>
							</div>
							<div class="col-sm-6">
								<button	type="submit" name="add_general" value="add_general" class="btn config-btn btn-lg pull-right">Submit</button>
							</div>
						</div>
                        -->

					</div>
				</div>
			</div><!-- .panel .panel-default -->

			<!-- Add Divisions -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to add your own sub tabs."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<a data-toggle="collapse" data-parent="#accordion_tabs" href="#collapse_2">
							Add Divisions<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>
				<div id="collapse_2" class="panel-collapse collapse">
					<div class="panel-body">
						Add Division separated by a comma in the order you want them on the dashboard:<br />
						<br />
						<input name="contacts_classification" type="text" value="<?php echo get_config($dbc, FOLDER_NAME.'_classification'); ?>" class="form-control"/><br />

						<div class="form-group">
							<div class="col-sm-6">
								<!--<a href="inventory.php?category=All" class="btn config-btn pull-right">Back</a>-->
								<a href="#" class="btn config-btn btn-lg" onclick="history.go(-1);return false;">Back</a>
							</div>
							<div class="col-sm-6">
								<button	type="submit" name="add_general" value="add_general" class="btn config-btn btn-lg pull-right">Submit</button>
							</div>
						</div>

						<!--
                        <div class="form-group">
							<label for="fax_number"	class="col-sm-4	control-label">Company Name:</label>
							<div class="col-sm-8">
								<input name="company_name" type="text" value="<?php echo get_config($dbc, 'company_name'); ?>" class="form-control"/>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-6">
								<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg pull-left">Back</a>
							</div>
							<div class="col-sm-6">
								<button	type="submit" name="add_general" value="add_general" class="btn config-btn btn-lg pull-right">Submit</button>
							</div>
						</div>
                        -->

					</div>
				</div>
			</div><!-- .panel .panel-default -->

			<!-- Add Contact Subtabs -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to add your own sub tabs."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<a data-toggle="collapse" data-parent="#accordion_tabs" href="#collapse_3">
							Add Contact Field Tabs<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>
				<div id="collapse_3" class="panel-collapse collapse">
					<div class="panel-body">
						Add Field sub tab separated by a comma in the order you want them on the Edit page:<br />
						<small><em>Unused tabs will not show up, you will need to apply these tabs to accordions before they will appear. This will control the order of the selected tabs.</em></small>
						<br />
						<input name="contact_field_subtabs" type="text" value="<?php echo get_config($dbc, FOLDER_NAME.'_field_subtabs'); ?>" class="form-control"/><br />

						<div class="form-group">
							<div class="col-sm-6">
								<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg pull-left">Back</a>
							</div>
							<div class="col-sm-6">
								<button	type="submit" name="add_general" value="add_general" class="btn config-btn btn-lg pull-right">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</div><!-- .panel .panel-default -->

		</div><!-- #accordion_tabs --><?php
	}


	if($_GET['type'] == 'impexp') { ?>
	<br><br>
		<div class="form-group">
			<label for="fax_number"	class="col-sm-4	control-label"><span class="popover-examples list-inline"><a class="" style="margin:7px 5px 0 0;" data-toggle="tooltip" data-placement="top" title="The Import/Export functionality allows users to export a full spreadsheet of the tile's data, as well as add or edit multiple row items at once."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>Enable Import/Export:</label>
			<div class="col-sm-8">
			<?php
			$checked = '';
			$get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='show_impexp_contact'"));
			if($get_config['configid'] > 0) {
				$get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT value FROM general_configuration WHERE name='show_impexp_contact'"));
				if($get_config['value'] == '1') {
					$checked = 'checked';
				}
			}
			?>
			  <input type='checkbox' style='width:20px; height:20px;' <?php echo $checked; ?>  name='' class='show_impexp_contact' value='1'>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-6">
				<!--<a href="inventory.php?category=All" class="btn config-btn pull-right">Back</a>-->
				<a href="#" class="btn config-btn btn-lg" onclick="history.go(-1);return false;">Back</a>
			</div>
			<div class="col-sm-6">
				<button	type="submit" name="imp_exp_en" value="" class="btn config-btn btn-lg pull-right">Submit</button>
			</div>
		</div>

<?php } ?>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>