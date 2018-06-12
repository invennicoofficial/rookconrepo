<?php include('../include.php');
$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT project_manage_dashboard, dashboard_view, tile_data, tile_employee FROM field_config_project_manage WHERE tile='Shop Work Orders' AND tab='Payroll' AND project_manage_dashboard IS NOT NULL"));
$value_config = ','.$get_field_config['project_manage_dashboard'].',';

$dropdownworkdate = $_GET['start'];
$dropdownworkenddate = $_GET['end'];
$dropdownstaff = $_GET['staff'];
$dropdownworkorder = $_GET['workorder'];

$filename = "download/".date('Y-m-d')." Shop Payroll.csv";
include('shop_time_sheets.php');