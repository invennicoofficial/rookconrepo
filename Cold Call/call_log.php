<?php
/*
Cold Caller Listing
*/
include ('../include.php');
error_reporting(0);
?>
<script type="text/javascript">
function selectStatus(sel) {
	var status = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
    var fname = $("#fname").val();
    var lname = $("#lname").val();
    var contactid = $("#session_contactid").val();

	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "call_log_ajax_all.php?fill=sales_status&salesid="+arr[1]+'&status='+status,
		dataType: "html",   //expect html to be returned
		success: function(response){
            if(status == 'Won') {
                alert("Lead Won");
            }
            if(status == 'Lost') {
                alert("Lead Lost and Removed from Cold Call.");
            }
			    location.reload();
		}
	});
}

function selectAction(sel) {
	var action = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "call_log_ajax_all.php?fill=sales_action&salesid="+arr[1]+'&action='+action,
		dataType: "html",   //expect html to be returned
		success: function(response){
    	    location.reload();
		}
	});
}
function followupDate(sel) {
	var reminder = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "call_log_ajax_all.php?fill=sales_reminder&salesid="+arr[1]+'&reminder='+reminder,
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
		}
	});
}
</script>
</head>
<script type="text/javascript" src="call_log.js"></script>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('calllog');
?>
<div class="container">
    <div class="row">
        <!-- <div id="no-more-tables" class="main-screen contacts-list"> -->
        <div class="main-screen contacts-list">
            <!-- Tile Header -->
            <div class="tile-header">
                <div class="col-xs-12 col-sm-4">
                    <h1>
                        <span class="pull-left" style="margin-top: -5px;"><a href="call_log.php" class="default-color">Cold Call</a></span>
                        <span class="clearfix"></span>
                    </h1>
                </div>
                <div class="col-xs-12 col-sm-8 text-right settings-block">
                    <?php if ( config_visible_function ( $dbc, 'Cold Call' ) == 1 ) {
                        if($_GET['maintype'] == 'preparation')
                            $setting_file = 'field_config_call_log_prep.php';
                        elseif($_GET['maintype'] == 'goals') {
                            $setting_file= 'field_config_call_log_goals.php';
                        }
                        else {
                            $setting_file= 'field_config_call_log.php';
                        } ?>
                        <div class="pull-right gap-left top-settings">
                            <a href="<?= $setting_file ?>" class="mobile-block pull-right "><img title="Tile Settings" src="<?= WEBSITE_URL; ?>/img/icons/settings-4.png" class="settings-classic wiggle-me" width="30"></a>
                            <span class="popover-examples list-inline pull-right" style="margin:5px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                        </div><?php
                    } ?>
                    <?php if(vuaed_visible_function($dbc, 'calllog') == 1) { ?>
                        <a href="add_call_log.php" class="btn brand-btn pull-right">New Cold Call</a>
                        <span class="popover-examples list-inline pull-right" style="margin:5px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Add Cold Call details here."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                    <?php } ?>
                </div>
                <div class="clearfix"></div>
            </div><!-- .tile-header -->

            <div class="tile-container">

                <div class="form-horizontal">

                    <!-- Sidebar -->
                    <div class="collapsible tile-sidebar set-section-height">
                        <?php include('tile_sidebar.php'); ?>
                    </div><!-- .tile-sidebar -->

                    <!-- Main Screen -->
                    <div class="fill-to-gap tile-content set-section-height" style="padding: 0;">
                        <div class="main-screen-details">
                        <?php
                            if($_GET['maintype'] == 'calllogpipeline' || empty($_GET['maintype'])) {
                                include('call_log_pipeline.php');
                            }
                            if($_GET['maintype'] == 'schedule') {
                                include('call_log_schedule.php');
                            }
                            if($_GET['maintype'] == 'preparation') {
                                include('call_log_preparation.php');
                            }
                            if($_GET['maintype'] == 'howto') {
                                include('call_log_howto.php');
                            }
                            if($_GET['maintype'] == 'leadbank') {
                                include('call_log_leadbank.php');
                            }
                            if($_GET['maintype'] == 'goals') {
                                include('call_log_goals.php');
                            }
                            if($_GET['maintype'] == 'reporting') {
                                include('call_log_reporting.php');
                            }
                        ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function submitForm(thisForm) {
        if (!$('input[name="search_user_submit"]').length) {
            var input = $("<input>")
                        .attr("type", "hidden")
                        .attr("name", "search_user_submit").val("1");
            $('[name=form_sites]').append($(input));
        }

        $('[name=form_sites]').submit();
    }
</script>
<?php include ('../footer.php'); ?>
