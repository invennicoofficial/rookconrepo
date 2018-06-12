<?php
/*
Customer Listing
*/
include ('../include.php');
?>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('medication');
?>

<div class="container triple-pad-bottom">
    <div class="row">
		<div class="col-md-12">

        <h1 class="">Medication Dashboard
        <?php
        if(config_visible_function($dbc, 'medication') == 1) {
            echo '<a href="field_config_medication.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>';
        }
        ?>
        </h1>

		<?php $from_url = 'medication.php';
		include('medication_list.php'); ?>

        </div>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
