 <?php
    $today_date = date('Y-m-d');

    $business_name = filter_var(htmlentities($_POST['business_name']),FILTER_SANITIZE_STRING);
    $business_services = filter_var(htmlentities($_POST['business_services']),FILTER_SANITIZE_STRING);
    $business_products = filter_var(htmlentities($_POST['business_products']),FILTER_SANITIZE_STRING);
    $business_vision = filter_var(htmlentities($_POST['business_vision']),FILTER_SANITIZE_STRING);
    $business_goals = filter_var(htmlentities($_POST['business_goals']),FILTER_SANITIZE_STRING);
    $target_markets = filter_var(htmlentities($_POST['target_markets']),FILTER_SANITIZE_STRING);
    $competitors = filter_var(htmlentities($_POST['competitors']),FILTER_SANITIZE_STRING);
    $current_areas_of_concern = filter_var(htmlentities($_POST['current_areas_of_concern']),FILTER_SANITIZE_STRING);

    $estimated_project_timeline_budget = filter_var(htmlentities($_POST['estimated_project_timeline_budget']),FILTER_SANITIZE_STRING);
    $communication_expectations_methods = filter_var(htmlentities($_POST['communication_expectations_methods']),FILTER_SANITIZE_STRING);

    if(empty($_POST['fieldlevelriskid'])) {
        $query_insert_site = "INSERT INTO `info_client_business_introduction` (`infogatheringid`, `today_date`, `business_name`, `business_services`, `business_products`, `business_vision`, `business_goals`, `target_markets`, `competitors`, `current_areas_of_concern`, `estimated_project_timeline_budget`, `communication_expectations_methods`) VALUES	('$infogatheringid', '$today_date', '$business_name', '$business_services', '$business_products', '$business_vision', '$business_goals', '$target_markets', '$competitors', '$current_areas_of_concern', '$estimated_project_timeline_budget', '$communication_expectations_methods')";
        $result_insert_site	= mysqli_query($dbc, $query_insert_site);
        $fieldlevelriskid = mysqli_insert_id($dbc);

        $query_insert_site = "INSERT INTO `infogathering_pdf` (`infogatheringid`, `fieldlevelriskid`) VALUES	('$infogatheringid', '$fieldlevelriskid')";
        $result_insert_site	= mysqli_query($dbc, $query_insert_site);

    } else {
        $fieldlevelriskid = $_POST['fieldlevelriskid'];
        $query_update_employee = "UPDATE `info_client_business_introduction` SET `business_name` = '$business_name', `business_services` = '$business_services',`business_products` = '$business_products',`business_vision` = '$business_vision',`business_goals` = '$business_goals',`target_markets` = '$target_markets',`competitors` = '$competitors',`current_areas_of_concern` = '$current_areas_of_concern',`estimated_project_timeline_budget` = '$estimated_project_timeline_budget',`communication_expectations_methods` = '$communication_expectations_methods' WHERE fieldlevelriskid='$fieldlevelriskid'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    }

    include ('client_business_introduction_pdf.php');
    echo client_business_introduction_pdf($dbc,$infogatheringid, $fieldlevelriskid);

    echo '<script type="text/javascript">         window.location.replace("manual_reporting.php?type=infogathering"); </script>';
