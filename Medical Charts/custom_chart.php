<?php
include ('../include.php');
?>
</head>

<body>
<?php include_once ('../navigation.php');
checkAuthorised('charts');
include 'config.php';

$chart_type = $_GET['type'];
?>
<div id="custom_chart_div" class="container">
    <div class="iframe_overlay" style="display:none; margin-top: -20px;margin-left:-15px;">
        <div class="iframe">
            <div class="iframe_loading">Loading...</div>
            <iframe name="custom_chart_overlay" src=""></iframe>
        </div>
    </div>
    <div class="row">

        <h1>
            <?php
            echo $chart_type;
            if(config_visible_function_custom($dbc)) {
                echo '<a href="field_config.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
            }
            ?>
        </h1>
        <?php echo get_tabs(); ?>

        <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
            <?php 
                include('custom_chart_inc.php');
            ?>
        </form>

    </div>
</div>
<?php include ('../footer.php'); ?>
