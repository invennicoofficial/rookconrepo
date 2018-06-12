<?php
include('../include.php');
?>
</head>
<body>
<?php 
include_once ('../navigation.php');
checkAuthorised('timesheet');
include 'config.php';

$value = $config['settings']['Choose Fields for Pay Period Dashboard'];

?>

<div class="container triple-pad-bottom">
    <div class="row">
        <div class="col-md-12">

        <h1 class="">Pay Period Dashboard
        <?php
        if(config_visible_function_custom($dbc)) {
            echo '<a href="field_config.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>';
        }
        ?>
        </h1>

        <form id="form1" name="form1" method="get" enctype="multipart/form-data" class="form-horizontal" role="form">

         <?php echo get_tabs('Pay Period', 'Last Month',  array('db' => $dbc, 'field' => $value['config_field'])); ?>
        <br><br>
         <h2>Coming Soon..</h2>

            <div id="no-more-tables">


        </div>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
