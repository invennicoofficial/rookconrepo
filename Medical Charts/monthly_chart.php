<?php
include ('../include.php');
?>
</head>

<body>
<?php include_once ('../navigation.php');
checkAuthorised('charts');

$type = $_GET['type'];
if(!empty($_POST['search_clientid'])) {
    $_GET['edit'] = $_POST['search_clientid'];
} else if(!empty($_POST['search_businessid'])) {
    $_GET['edit'] = $_POST['search_businessid'];
}
if(empty($type)) {
    $type = 'bowel_movement';
}
switch($type) {
    case 'bowel_movement':
        $header_text = 'Bowel Movement';
        $include_file = '../Medical Charts/bowel_movement_chart.php';
        $from_url = '../Medical Charts/bowel_movement.php';
        break;
    case 'seizure_record':
        $header_text = 'Seizure Record';
        $include_file = '../Medical Charts/seizure_record_chart.php';
        $from_url = '../Medical Charts/seizure_record.php';
        break;
    case 'daily_water_temp':
        $header_text = 'Daily Water Temp (Client)';
        $include_file = '../Medical Charts/daily_water_temp_chart.php';
        $from_url = '../Medical Charts/daily_water_temp.php';
        break;
    case 'blood_glucose':
        $header_text = 'Blood Glucose';
        $include_file = '../Medical Charts/blood_glucose_chart.php';
        $from_url = '../Medical Charts/blood_glucose.php';
        break;
    case 'daily_water_temp_bus':
        $header_text = 'Daily Water Temp (Business)';
        $include_file = '../Medical Charts/daily_water_temp_bus_chart.php';
        $from_url = '../Medical Charts/daily_water_temp_bus.php';
        break;
    case 'daily_fridge_temp':
        $header_text = 'Daily Fridge Temp';
        $include_file = '../Medical Charts/daily_fridge_temp_chart.php';
        $from_url = '../Medical Charts/daily_fridge_temp.php';
        break;
    case 'daily_freezer_temp':
        $header_text = 'Daily Freezer Temp';
        $include_file = '../Medical Charts/daily_freezer_temp_chart.php';
        $from_url = '../Medical Charts/daily_freezer_temp.php';
        break;
    case 'daily_dishwasher_temp':
        $header_text = 'Daily Dishwasher Temp';
        $include_file = '../Medical Charts/daily_dishwasher_temp_chart.php';
        $from_url = '../Medical Charts/daily_dishwasher_temp.php';
        break;
}
?>
<div class="container">
  <div class="row">

    <h1><?= $header_text ?> - <?= !empty(get_client($dbc, $_GET['edit'])) ? get_client($dbc, $_GET['edit']) : get_contact($dbc, $_GET['edit']) ?></h1>
	<div class="pad-left gap-top double-gap-bottom">
        <a href="<?= $from_url ?>" class="btn config-btn">Back to Dashboard</a>
    </div>

    <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
        <?php 
            include($include_file);
        ?>
    </form>

  </div>
</div>
<?php include ('../footer.php'); ?>
