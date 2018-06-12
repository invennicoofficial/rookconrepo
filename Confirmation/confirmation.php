<?php
/*
Dashboard
*/
include_once ('../include.php');
checkAuthorised('confirmation');
error_reporting(0);
?>

</head>
<body>

<?php include_once ('../navigation.php');
?>
<?php
$active_pp = '';
$active_om = '';
if(empty($_GET['maintype'])) {
    $_GET['maintype'] = '1month';
}
if($_GET['maintype'] == '1month') {
	$active_1month = 'active_tab';
}
if($_GET['maintype'] == '48hours') {
	$active_48hours = 'active_tab';
}
?>
<div class="container">
	<div class='iframe_holder' style='display:none;'>
		<img src='<?php echo WEBSITE_URL; ?>/img/icons/close.png' class='close_iframer' width="45px" style='position:relative; right: 10px; float:right;top:58px; cursor:pointer;'>
		<span class='iframe_title' style='color:white; font-weight:bold; position: relative; left: 20px; font-size: 30px;'></span>
		<iframe id="iframe_instead_of_window" style='width: 100%;' height="1000px; border:0;" src=""></iframe>
    </div>
    <div class="row">
		<div class="col-md-12">

		<div class="clearfix"></div><br />

		<span class="popover-examples list-inline" style="margin:10px 10px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Displays whether the appointment is confirmed or unconfirmed."><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
        <a href="<?php echo addOrUpdateUrlParam('maintype','1month'); ?>"><button class="btn brand-btn mobile-block mobile-100 <?php echo $active_1month; ?>" type="button">1 Month Confirmation Email</button></a>
		<span class="popover-examples list-inline" style="margin:10px 10px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Displays whether the appointment is confirmed or unconfirmed."><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
		<a href="<?php echo addOrUpdateUrlParam('maintype','48hours'); ?>"><button class="btn brand-btn mobile-block mobile-100 <?php echo $active_48hours; ?>" type="button">48 Hour Confirmation Email</button></a>
		<?php
            if($_GET['maintype'] == '1month' || empty($_GET['maintype'])) {
                include('../CRM/confirmation_email.php');
            }
            if($_GET['maintype'] == '48hours') {
                include('../CRM/reminder_email.php');
            }
        ?>
        </div>
    </div>
</div>