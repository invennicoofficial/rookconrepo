<?php
/*
Dashboard
*/
include_once ('../include.php');
checkAuthorised('manual');
?>

</head>
<body>

<?php include_once ('../navigation.php');
?>
<?php
$active_pp = '';
$active_om = '';
$active_eh = '';
$active_htg = '';
$heading = '';
if(empty($_GET['maintype'])) {
    $_GET['maintype'] = 'pp';
}
if($_GET['maintype'] == 'pp') {
	$active_pp = 'active_tab';
    $heading = 'Policies & Procedures';
}
if($_GET['maintype'] == 'om') {
	$active_om = 'active_tab';
    $heading = 'Operations Manual';
}
if($_GET['maintype'] == 'eh') {
	$active_eh = 'active_tab';
    $heading = 'Employee Handbook';
}
if($_GET['maintype'] == 'htg') {
	$active_htg = 'active_tab';
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

        <?php
        if(isset($_GET['maintype'])) {
            echo '<h2>'.$heading.'</h2>';
        }
        ?>

		<div class="clearfix"></div><br />

		<span class="popover-examples list-inline" style="margin:10px 10px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Policies & Procedures allows Admin users to create new policies and procedures and assign them to staff."><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
        <a href="<?php echo addOrUpdateUrlParam('maintype','pp'); ?>"><button class="btn brand-btn mobile-block mobile-100 <?php echo $active_pp; ?>" type="button">Policies & Procedures</button></a>
		<span class="popover-examples list-inline" style="margin:10px 10px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Pertains to the operations of the company. Allows Admin users to create new operation manuals or procedures and assign them to staff."><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
        <a href="<?php echo addOrUpdateUrlParam('maintype','om'); ?>"><button class="btn brand-btn mobile-block mobile-100 <?php echo $active_om; ?>" type="button">Operations Manual</button></a>
		<span class="popover-examples list-inline" style="margin:10px 10px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Contains anything the company deems to be part of their requirements for staff."><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
        <a href="<?php echo addOrUpdateUrlParam('maintype','eh'); ?>"><button class="btn brand-btn mobile-block mobile-100 <?php echo $active_eh; ?>" type="button">Employee Handbook</button></a>
		<!-- <span class="popover-examples list-inline" style="margin:10px 10px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Contains the How To Guide for the software."><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span> -->
        <!--
        <a href="<?php echo addOrUpdateUrlParam('maintype','htg'); ?>"><button class="btn brand-btn mobile-block mobile-100 <?php echo $active_htg; ?>" type="button">How To Guide</button></a>
        -->
		<?php
			$_GET['from_manual'] = '1';
			if(!isset($_GET['category'])) {
				$_GET['category'] = '0';
			}
            if($_GET['maintype'] == 'pp' || empty($_GET['maintype'])) {
                include('policy_procedures.php');
            }
            if($_GET['maintype'] == 'om') {
                include('operations_manual.php');
            }
            if($_GET['maintype'] == 'eh') {
                include('emp_handbook.php');
            }
            if($_GET['maintype'] == 'htg') {
                include('../How To Guide/guides_dashboard.php');
            }
        ?>
        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>