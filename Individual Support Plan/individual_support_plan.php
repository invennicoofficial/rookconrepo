<?php
/*
Customer Listing
*/
include ('../include.php');

$default_config = 'Day Program Support Team Primary Contact,Day Program Support Team Lead,Day Program Support Team Key Supports,Residential Support Team Primary Contact,Residential Support Team Lead,Residential Support Team Key Supports,Guardian Primary Contact,Guardian Secondary Contact,Guardian Alternates,Emergency Contacts,ISP Start Date,ISP Review Date,ISP End Date,Quality of Life Outcomes,Goals,Assessed Service Needs,Support Strategies,Support Objectives,SIS Activity Areas,Who is Responsible,Updates,ISP Notes,Service Individual,Day Program Support Team,Residential Support Team,Guardian,Dates & Timelines,ISP Details';
$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT individual_support_plan FROM field_config"));
if(empty($get_field_config) && empty($get_field_config['individual_support_plan'])) {
    mysqli_query($dbc, "INSERT INTO `field_config` (`individual_support_plan`) VALUES ('$default_config')");
} else if(empty($get_field_config['individual_support_plan'])) {
    mysqli_query($dbc, "UPDATE `field_config` SET `individual_support_plan` = '$default_config'");
}
?>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('individual_support_plan');
?>

<div class="container triple-pad-bottom">
    <div class="row">
		<div class="col-md-12">

        <h1 class="">Individual Service Plan Dashboard
        <?php
        if(config_visible_function($dbc, 'individual_support_plan') == 1) {
            echo '<a href="field_config_support_plan.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>';
        }
        ?>
        </h1>

		<?php $from_url = 'individual_support_plan.php';
		include('support_plan_list.php'); ?>

        </div>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
