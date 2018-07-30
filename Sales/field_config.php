<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('sales');

//$map_array = array("Leads"=>"Leads","Opportunities"=>"Opportunities","In_Negotiations"=>"In Negotiations","Closed_Successfully"=>"Closed Successfully","Lost_Abandoned"=>"Lost Abandoned","Pending"=>"Pending","Prospect"=>"Prospect","Qualification"=>"Qualification","Needs Analysis"=>"Needs Analysis","Propose Quote"=>"Propose Quote","Negotiations"=>"Negotiations","Won"=>"Won","Lost"=>"Lost","Abandoned"=>"Abandoned","Future_Review"=>"Future Review");

$sales_tile = SALES_TILE;
$sales_noun = SALES_NOUN;

switch($_GET['tab']) {
	case 'dashboards':
        $page_title = SALES_TILE." Dashboards";
        $include_file = 'field_config_dashboards.php';
		break;
    case 'actions':
        $_GET['tab'] = 'actions';
        $page_title = "Quick Action Flags";
        $include_file = '../Ticket/field_config_flags.php';
		break;
    case 'general':
    default:
        $_GET['tab'] = 'general';
        $page_title = SALES_TILE." Settings";
        $include_file = 'field_config_general.php';
}
?>
<script type="text/javascript">
$(document).ready(function(){
    /* if($(window).width() > 767) {
        resizeScreen();
        $(window).resize(function() {
            resizeScreen();
        });
    } */
    $(window).resize(function() {
        var available_height = window.innerHeight - $('footer:visible').outerHeight() - $('#sales_div .tile-container').offset().top - 1;
        if(available_height > 200) {
            $('#sales_div .tile-sidebar, #sales_div .scale-to-fill').height(available_height);
        }
    }).resize();
});

function resizeScreen() {
    /* var view_height = $(window).height() > 500 ? $(window).height() : 500;
    $('#sales_div .scale-to-fill, #sales_div .tile-sidebar').height($('#sales_div').height() - $('.tile-header').height() + 15); */
}
</script>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div id="sales_div" class="container">
    <div class="row">
        <div class="main-screen"><?php
            include('tile_header.php'); ?>

            <div class="tile-container">
                <div class="standard-collapsible tile-sidebar tile-sidebar-noleftpad hide-on-mobile">
                    <ul>
                        <a href="index.php"><li>Back to Dashboard</li></a>
                        <a href="?tab=general"><li <?= $_GET['tab'] == 'general' ? 'class="active"' : '' ?>><?= SALES_TILE ?> Settings</li></a>
                        <a href="?tab=actions"><li <?= $_GET['tab'] == 'actions' ? 'class="active"' : '' ?>>Quick Action Icons</li></a>
                        <a href="?tab=dashboards"><li <?= $_GET['tab'] == 'dashboards' ? 'class="active"' : '' ?>><?= SALES_TILE ?> Dashboards</li></a>
                    </ul>
                </div>

                <div class="scale-to-fill" style="background-color: #fff">
                    <div class="main-screen-white standard-body" style="padding-left: 0; padding-right: 0; border: none;">
                        <div class="standard-body-title">
                            <h3><?= $page_title ?></h3>
                        </div>
                        <div class="standard-body-content pad-10">
                            <?php include($include_file); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include ('../footer.php'); ?>
