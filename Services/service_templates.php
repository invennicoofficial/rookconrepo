<?php
/*
 * Services Tile Main Page
 */
error_reporting(0);
include ('../include.php');
?>
<script type="text/javascript">
$(document).ready(function(){
    if($(window).width() > 767) {
        resizeScreen();
        $(window).resize(function() {
            resizeScreen();
        });
    }
});

function resizeScreen() {
    $('#services_div .tile-sidebar, #services_div .scale-to-fill').height($('#services_div').height() - $('#services_div .tile-header').height() + 15);
    $('#services_div .main-screen-white').height($('#services_div .tile-content').height() - 10);
}
</script>
</head>

<body>
<?php
	include_once ('../navigation.php');
checkAuthorised('services');

    $templateid = $_GET['templateid'];
    if(empty($templateid)) {
        $templateid = 'new';
    }
?>

<div id="services_div" class="container">
    <div class="row">
		<div class="main-screen"><?php
            include('tile_header.php'); ?>
            
            <div class="tile-container" style="height: 100%;">
                <div class="standard-collapsible tile-sidebar">
                    <ul>
                        <li <?= $templateid == 'new' ? 'class="active"' : '' ?>><a href="?templateid=new">Create New Template</a></li>
                        <?php
                        $template_list = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `services_service_templates` WHERE `deleted` = 0 AND `contactid` = 0 ORDER BY `name`"),MYSQLI_ASSOC);
                        foreach ($template_list as $template) {
                            echo '<li '.($templateid == $template['templateid'] ? 'class="active"' : '').'><a href="?templateid='.$template['templateid'].'">'.$template['name'].'</a></li>';
                        } ?>
                    </ul>
                </div>

                <div class="scale-to-fill tile-content set-section-height" style="overflow: auto;">
                    <?php include('service_template_edit.php'); ?>
                </div>

                <div class="clearfix"></div>
            </div><!-- .tile-container -->

        </div><!-- .main-screen -->
    </div><!-- .row -->
</div><!-- .container -->

<?php include ('../footer.php'); ?>