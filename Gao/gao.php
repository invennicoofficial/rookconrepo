<?php
/*
Call Loger Listing
*/
error_reporting(0);
include ('../include.php');
?>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('gao');
?>
<?php
$active_company = '';
$active_department = '';
$active_my = '';
if(empty($_GET['maintype'])) {
    $_GET['maintype'] = 'company';
}
if($_GET['maintype'] == 'company') {
	$active_company = 'active_tab';
}
if($_GET['maintype'] == 'department') {
	$active_department = 'active_tab';
}
if($_GET['maintype'] == 'my') {
	$active_my = 'active_tab';
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
        <h1 class="single-pad-bottom pull-left">Goals & Objectives</h1>
        
		<div class="clearfix"></div><br />

		<?php //echo check_subtab_persmission($dbc, 'gao', ROLE, 'company'); exit; ?>
		<?php if(check_subtab_persmission($dbc, 'gao', ROLE, 'company') == true): ?>
			<span class="popover-examples list-inline" style="margin-left:20px;"><a data-toggle="tooltip" Title='Goals relating to the company.' data-placement="top" title=""><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a href="<?php echo addOrUpdateUrlParam('maintype','company'); ?>"><button class="btn brand-btn mobile-block mobile-100 <?php echo $active_company; ?>" type="button">The Company</button></a>
		<?php endif; ?>
		<?php if(check_subtab_persmission($dbc, 'gao', ROLE, 'department') == true): ?>
			<span class="popover-examples list-inline" style="margin-left:20px;"><a data-toggle="tooltip" Title='Goals relating to specific departments.' data-placement="top" title=""><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a href="<?php echo addOrUpdateUrlParam('maintype','department'); ?>"><button class="btn brand-btn mobile-block mobile-100 <?php echo $active_department; ?>" type="button">My Department Goals</button></a>
		<?php endif; ?>
		<?php if(check_subtab_persmission($dbc, 'gao', ROLE, 'my') == true): ?>
			<span class="popover-examples list-inline" style="margin-left:20px;"><a data-toggle="tooltip" Title='Your specific Goals.' data-placement="top" title=""><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a href="<?php echo addOrUpdateUrlParam('maintype','my'); ?>"><button class="btn brand-btn mobile-block mobile-100 <?php echo $active_my; ?>" type="button">My Goals</button></a>
		<?php endif; ?>
		<?php
            if(($_GET['maintype'] == 'company' || empty($_GET['maintype'])) && check_subtab_persmission($dbc, 'gao', ROLE, 'company') == true) {
                include('gao_company.php');
            }
            if($_GET['maintype'] == 'department' && check_subtab_persmission($dbc, 'gao', ROLE, 'department') == true) {
                include('gao_department.php');
            }
            if($_GET['maintype'] == 'my' && check_subtab_persmission($dbc, 'gao', ROLE, 'my') == true) {
                include('gao_goal.php');
            }
        ?>
        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
