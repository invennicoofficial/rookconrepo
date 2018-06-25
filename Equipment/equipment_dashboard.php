<?php include_once('../include.php');
checkAuthorised('equipment');
$security = get_security($dbc, 'equipment');

switch($_GET['tab']) {
	case 'inspections':
		$title = 'Inspections';
		$include_file = 'dashboard_inspections.php';
		break;
	case 'assign_equipment':
		$title = 'Assigned Equipment';
		$include_file = 'dashboard_assign_equipment.php';
		break;
	case 'work_orders':
		$title = 'Work Orders';
		$include_file = 'dashboard_work_orders.php';
		break;
	case 'expenses':
		$title = 'Expenses';
		$include_file = 'dashboard_expenses.php';
		break;
	case 'balance':
		$title = 'Balance Sheets';
		$include_file = 'dashboard_balance.php';
		break;
	case 'service_schedules':
		$title = 'Service Schedules';
		$include_file = 'dashboard_service_schedules.php';
		break;
	case 'service_request':
		$title = 'Service Requests';
		$include_file = 'dashboard_service_request.php';
		break;
	case 'service_record':
		$title = 'Service Records';
		$include_file = 'dashboard_service_record.php';
		break;
	case 'equipment_checklist':
		$title = 'Checklist';
		$include_file = 'dashboard_equipment_checklist.php';
		break;
	default:
		$_GET['tab'] = 'equipment';
		$title = ($_GET['status'] == 'Inactive' ? 'Inactive ' : 'Active ').'Equipment';
		$include_file = 'dashboard_equipment.php';
		break;
}
$equipment_main_tabs = explode(',',get_config($dbc, 'equipment_main_tabs'));
// Add Default Configurations for the default Tabs if nothing is configured
$inspection_list = filter_var(implode('#*#', ["Oil","Coolant - Rad","Coolant Overflow","Hydraulic Oil","Hydraulic Oil - Leaks","Transmission Oil","Air Filters","Belts","Track SAG","Brake Emergency","Planetaries","Brake Pedal","Hydraulic Brake Fluid","Parking Brake","Defroster & Heaters","Emergency Equipment","Engine","Exhaust System","Fire Extinguisher","Fuel System","Generator/Alternator","Horn","Lights & Reflectors","Head - Stop Lights","Tail - Dash Lights","Blade","Bucket","Body Damage","Doors","Mirrors (Adjustment & Condition)","Oil Pressure","Radiator","Driver's Seat Belt & Seat Security","Cutting Edges","Ripper Teeth","Towing & Coupling Devices","Windshield & Windows","Windshield Washer & Wipers"]),FILTER_SANITIZE_STRING);
mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`,`value`) SELECT 'equipment_tabs', 'Truck,Trailer' FROM (SELECT COUNT(*) rows FROM `general_configuration` WHERE `name`='equipment_tabs') num WHERE num.rows=0");
mysqli_query($dbc, "INSERT INTO `field_config_equipment` (`tab`, `equipment_dashboard`, `inspection_list`) SELECT 'Truck', 'Make,Model,Unit #,Service History Link,Registration Renewal date,Insurance Renewal Date,Service History Link,Status', '$inspection_list' FROM (SELECT COUNT(*) rows FROM `field_config_equipment` WHERE `equipment_dashboard` IS NOT NULL AND `tab`='Truck') num WHERE num.rows=0");
mysqli_query($dbc, "INSERT INTO `field_config_equipment` (`tab`, `accordion`, `order`, `equipment`) SELECT `tab`, `accordion`, `sort`, `field_names` FROM (SELECT 'Truck' `tab`, 'Equipment Information' `accordion`, '1' `sort`, 'Category,Make,Model,Model Year,Color,Unit #,VIN #' `field_names` UNION SELECT 'Truck', 'Registration', '2', 'Licence Plate,Registration Card,Registration Renewal date,Registration Reminder' UNION SELECT 'Truck', 'Insurance', '3', 'Insurance,Insurance Contact,Insurance Phone,Insurance Card,Insurance Renewal Date,Insurance Reminder' UNION SELECT 'Truck', 'Status', '4', 'Status') defaults INNER JOIN (SELECT COUNT(*) rows FROM `field_config_equipment` WHERE `equipment` IS NOT NULL AND `tab`='Truck') num WHERE num.rows=0");
mysqli_query($dbc, "INSERT INTO `field_config_equipment` (`tab`, `equipment_dashboard`, `inspection_list`) SELECT 'Trailer', 'Make,Model,Unit #,Registration Renewal date,Insurance Renewal Date,Service History Link,Status', '$inspection_list' FROM (SELECT COUNT(*) rows FROM `field_config_equipment` WHERE `equipment_dashboard` IS NOT NULL AND `tab`='Trailer') num WHERE num.rows=0");
mysqli_query($dbc, "INSERT INTO `field_config_equipment` (`tab`, `accordion`, `order`, `equipment`) SELECT `tab`, `accordion`, `sort`, `field_names` FROM (SELECT 'Trailer' `tab`, 'Equipment Information' `accordion`, '1' `sort`, 'Category,Make,Model,Model Year,Color,Unit #,VIN #' `field_names` UNION SELECT 'Trailer', 'Registration', '2', 'Licence Plate,Registration Card,Registration Renewal date,Registration Reminder' UNION SELECT 'Trailer', 'Insurance', '3', 'Insurance,Insurance Contact,Insurance Phone,Insurance Card,Insurance Renewal Date,Insurance Reminder' UNION SELECT 'Trailer', 'Status', '4', 'Status') defaults INNER JOIN (SELECT COUNT(*) rows FROM `field_config_equipment` WHERE `equipment` IS NOT NULL AND `tab`='Trailer') num WHERE num.rows=0");
?>

<div class="tile-sidebar sidebar hide-titles-mob standard-collapsible">
	<ul>
		<form action="" method="POST">
			<li class="standard-sidebar-searchbox">
				<input type="text" name="search_query" class="form-control" placeholder="Search Equipment" value="<?= $_POST['search_query'] ?>">
	            <input type="submit" value="Search" class="btn brand-btn" name="search_submit" style="display:none;" />
	        </li>
	    </form>
		<?php if ( in_array('Equipment',$equipment_main_tabs) && check_subtab_persmission($dbc, 'equipment', ROLE, 'equipment') === TRUE ) { ?>
		    <a href="?category=Top&status=Active"><li class="<?= $_GET['tab'] == 'equipment' && $_GET['status'] != 'Inactive' ? 'active blue' : '' ?>">Active Equipment</li></a>
		<?php } ?>
		<?php if ( in_array('Equipment',$equipment_main_tabs) && check_subtab_persmission($dbc, 'equipment', ROLE, 'equipment') === TRUE ) { ?>
		    <a href="?category=Top&status=Inactive"><li class="<?= $_GET['tab'] == 'equipment' && $_GET['status'] == 'Inactive' ? 'active blue' : '' ?>">Inactive Equipment</li></a>
		<?php } ?>
		<?php if ( in_array('Inspection',$equipment_main_tabs) && check_subtab_persmission($dbc, 'equipment', ROLE, 'inspection') === TRUE ) { ?>
		    <a href="?tab=inspections"><li class="<?= $_GET['tab'] == 'inspections' ? 'active blue' : '' ?>">Inspections</li></a>
		<?php } ?>
		<?php if ( in_array('Assign',$equipment_main_tabs) && check_subtab_persmission($dbc, 'equipment', ROLE, 'assign') === TRUE ) { ?>
		    <a href="?tab=assign_equipment"><li class="<?= $_GET['tab'] == 'assign_equipment' ? 'active blue' : '' ?>">Assigned Equipment</li></a>
		<?php } ?>
		<?php if ( in_array('Work Order',$equipment_main_tabs) && check_subtab_persmission($dbc, 'equipment', ROLE, 'work_orders') === TRUE ) { ?>
		    <a href="?tab=work_orders"><li class="<?= $_GET['tab'] == 'work_orders' ? 'active blue' : '' ?>">Work Orders</li></a>
		<?php } ?>
		<?php if ( in_array('Expenses',$equipment_main_tabs) && check_subtab_persmission($dbc, 'equipment', ROLE, 'expenses') === TRUE ) { ?>
		    <a href="?tab=expenses"><li class="<?= $_GET['tab'] == 'expenses' ? 'active blue' : '' ?>">Expenses</li></a>
		<?php } ?>
		<?php if ( in_array('Expenses',$equipment_main_tabs) && check_subtab_persmission($dbc, 'equipment', ROLE, 'expenses') === TRUE ) { ?>
		    <a href="?tab=balance"><li class="<?= $_GET['tab'] == 'balance' ? 'active blue' : '' ?>">Balance Sheets</li></a>
        <?php } ?>
		<?php if ( in_array('Schedules',$equipment_main_tabs) && check_subtab_persmission($dbc, 'equipment', ROLE, 'schedules') === TRUE ) { ?>
		    <a href="?tab=service_schedules"><li class="<?= $_GET['tab'] == 'service_schedules' ? 'active blue' : '' ?>">Service Schedules</li></a>
		<?php } ?>
		<?php if ( in_array('Requests',$equipment_main_tabs) && check_subtab_persmission($dbc, 'equipment', ROLE, 'requests') === TRUE ) { ?>
		    <a href="?tab=service_request"><li class="<?= $_GET['tab'] == 'service_request' ? 'active blue' : '' ?>">Service Requests</li></a>
		<?php } ?>
		<?php if ( in_array('Records',$equipment_main_tabs) && check_subtab_persmission($dbc, 'equipment', ROLE, 'records') === TRUE ) { ?>
		    <a href="?tab=service_record"><li class="<?= $_GET['tab'] == 'service_record' ? 'active blue' : '' ?>">Service Records</li></a>
		<?php } ?>
		<?php if ( in_array('Checklists',$equipment_main_tabs) && check_subtab_persmission($dbc, 'equipment', ROLE, 'checklist') === TRUE ) { ?>
		    <a href="?tab=equipment_checklist"><li class="<?= $_GET['tab'] == 'equipment_checklist' ? 'active blue' : '' ?>">Checklists</li></a>
		<?php } ?>
	</ul>
</div>

<div class="scale-to-fill has-main-screen hide-titles-mob">
	<div class="main-screen standard-body form-horizontal">
		<div class="standard-body-title">
			<h3><?= $title ?></h3>
		</div>

		<div class="standard-body-content" style="padding: 1em;">
			<?php include($include_file); ?>
		</div>
	</div>
</div>