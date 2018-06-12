<?php
include('../include.php');
error_reporting(0);
?>
</head>
<body>
<?php 
include_once ('../navigation.php');
checkAuthorised('social_story');
include 'config.php';
?>

<div class="container triple-pad-bottom">
    <div class="row">
        <div class="col-md-12">

        <h1 class="">Social Stories: Learning Techniques Dashboard
        <?php
        if(config_visible_function_social($dbc)) {
            echo '<a href="field_config.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>';
        }
        ?>
        </h1>

        <form id="form1" name="form1" method="post" enctype="multipart/form-data" class="form-horizontal" role="form">
            <?php echo get_tabs_social('Learning Techniques'); ?>
            <h3>Coming Soon...</h3>
        </form>


        </div>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
