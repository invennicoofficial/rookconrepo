<?php
/*
Cold Caller Listing
*/
error_reporting(0);
include ('../include.php');
?>
<script type="text/javascript">
$(document).ready(function() {

	$('.iframe_open').click(function(){
			var id = $(this).attr('id');
		   $('#iframe_instead_of_window').attr('src', 'budget_history.php?budgetid='+id);
		   $('.iframe_title').text('Budget History');
		   $('.iframe_holder').show();
		   $('.hide_on_iframe').hide();
	});

	$('.close_iframer').click(function(){
		var result = confirm("Are you sure you want to close this window?");
		if (result) {
			$('.iframe_holder').hide();
			$('.hide_on_iframe').show();
		}
	});

});

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
<body>
<?php include_once ('../navigation.php');
checkAuthorised('budget');
?>
<?php
$active_howto = '';
$active_pending_budget = '';
$active_active_budget = '';
$active_expense_tracking = '';
if(empty($_GET['maintype'])) {
    $_GET['maintype'] = 'howto';
}
if($_GET['maintype'] == 'howto') {
	$active_howto = 'active_tab';
}
if($_GET['maintype'] == 'pending_budget') {
	$pending_budget = 'active_tab';
}
if($_GET['maintype'] == 'active_budget') {
	$active_budget = 'active_tab';
}
if($_GET['maintype'] == 'expense_tracking') {
	$expense_tracking = 'active_tab';
}
?>
<div class="container triple-pad-bottom">
	<div class='iframe_holder' style='display:none;'>
		<img src='<?php echo WEBSITE_URL; ?>/img/icons/close.png' class='close_iframer' width="45px" style='position:relative; right: 10px; float:right;top:58px; cursor:pointer;'>
		<span class='iframe_title' style='color:white; font-weight:bold; position: relative; left: 20px; font-size: 30px;'></span>
		<iframe id="iframe_instead_of_window" style='width: 100%;' height="1000px; border:0;" src=""></iframe>
    </div>
    <div class="row">
		<div class="col-md-12">
            <h1 class="single-pad-bottom pull-left">Budget Dashboard</h1>
            <div class="pull-right">
                <?php if(config_visible_function($dbc, 'budget') == 1) {
                    echo '<a href="field_config.php?tab='.$_GET['maintype'].'" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
                } ?>
            </div>

            <div class="clearfix"></div>

            <div class="tab-container gap-top">
                <div class="tab pull-left">
                    <span class="popover-examples list-inline" style="margin:10px 0 0 0;"><a data-toggle="tooltip" data-placement="top" title="Use this guide to walk through the process of creating a budget."><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                    if ( check_subtab_persmission( $dbc, 'budget', ROLE, 'how_to_guide' ) === true ) { ?>
                        <a href="<?php echo addOrUpdateUrlParam('maintype','howto'); ?>"><button class="btn brand-btn mobile-block mobile-100 <?php echo $active_howto; ?>" type="button">How To Guide</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">How To Guide</button></a><?php
                    } ?>
                </div>
                <div class="tab pull-left">
                    <span class="popover-examples list-inline" style="margin:10px 0 0 0;"><a data-toggle="tooltip" data-placement="top" title="Budgets you have created but not yet approved."><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                    if ( check_subtab_persmission( $dbc, 'budget', ROLE, 'pending_budget' ) === true ) { ?>
                        <a href="<?php echo addOrUpdateUrlParam('maintype','pending_budget'); ?>"><button class="btn brand-btn mobile-block mobile-100 <?php echo $pending_budget; ?>" type="button">Pending Budgets</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">Pending Budgets</button></a><?php
                    } ?>
                </div>
                <div class="tab pull-left">
                    <span class="popover-examples list-inline" style="margin:10px 0 0 0;"><a data-toggle="tooltip" data-placement="top" title="Budgets that have been approved and are currently active."><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                    if ( check_subtab_persmission( $dbc, 'budget', ROLE, 'active_budget' ) === true ) { ?>
                        <a href="<?php echo addOrUpdateUrlParam('maintype','active_budget'); ?>"><button class="btn brand-btn mobile-block mobile-100 <?php echo $active_budget; ?>" type="button">Active Budgets</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">Active Budgets</button></a><?php
                    } ?>
                </div>
                <div class="tab pull-left">
                    <span class="popover-examples list-inline" style="margin:10px 0 0 0;"><a data-toggle="tooltip" data-placement="top" title="View budgeted vs. actual expenses."><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                    if ( check_subtab_persmission( $dbc, 'budget', ROLE, 'expense_tracking' ) === true ) { ?>
                        <a href="<?php echo addOrUpdateUrlParam('maintype','expense_tracking'); ?>"><button class="btn brand-btn mobile-block mobile-100 <?php echo $expense_tracking; ?>" type="button">Budget Expense Tracking</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">Budget Expense Tracking</button></a><?php
                    } ?>
                </div>
            </div><!-- .tab-container --><?php
            
            if($_GET['maintype'] == 'howto' || empty($_GET['maintype'])) {
                include('budget_howto.php');
            }
            if($_GET['maintype'] == 'pending_budget') {
                include('budget_pending.php');
            }
            if($_GET['maintype'] == 'active_budget') {
                include('budget_active.php');
            }
            if($_GET['maintype'] == 'expense_tracking') {
                include('expense_tracking.php');
            } ?>
            
            <div class="clearfix"></div>
            
        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
