<?php

/*
Equipment Listing
*/
include ('../include.php');
error_reporting(0);
?>
</head>

<body>
<?php include_once ('../navigation.php');
checkAuthorised('email_communication');
?>
<div class="container">
    <div class="row">

    <div class="col-sm-10">
		<h1>Email Communication</h1>
	</div>
	<div class="col-sm-2 double-gap-top">
		<?php
			if(config_visible_function($dbc, 'email_communication') == 1) {
				echo '<a href="field_config.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
			}
		?>
	</div>
	
	<div class="clearfix double-gap-bottom"></div>
	
	<?php
    $internal_active_tab = '';
    $external_active_tab = '';
    if($_GET['type'] == 'Internal') {
        $internal_active_tab = 'active_tab';
    }
    if($_GET['type'] == 'External') {
        $external_active_tab = 'active_tab';
    } ?>
	
	<div class="tab-container mobile-100-container">
		<?php if ( check_subtab_persmission($dbc, 'email_communication', ROLE, 'internal') === TRUE ) { ?>
			<a href="email_communication.php?maintype=comm&type=Internal"><button type="button" class="mobile-100 btn brand-btn mobile-block <?php echo $internal_active_tab; ?>">Internal</button></a>&nbsp;&nbsp;
		<?php } else { ?>
			<button type="button" class="btn disabled-btn mobile-block mobile-100">Internal</button>&nbsp;&nbsp;
		<?php } ?>
		
		<?php if ( check_subtab_persmission($dbc, 'email_communication', ROLE, 'external') === TRUE ) { ?>
			<a href="email_communication.php?maintype=comm&type=External"><button type="button" class="mobile-100 btn brand-btn mobile-block <?php echo $external_active_tab; ?>">External</button></a>&nbsp;&nbsp;
		<?php } else { ?>
			<button type="button" class="btn disabled-btn mobile-block mobile-100">External</button>&nbsp;&nbsp;
		<?php } ?>
		
		<?php if ( check_subtab_persmission($dbc, 'email_communication', ROLE, 'log') === TRUE ) { ?>
			<a href="log_email_communication.php"><button type="button" class="btn mobile-100 brand-btn mobile-block">Log</button></a>&nbsp;&nbsp;
		<?php } else { ?>
			<button type="button" class="btn disabled-btn mobile-block mobile-100">Log</button>&nbsp;&nbsp;
		<?php } ?>
	</div>

    <?php include('email_list.php'); ?>
</div>
</div>


<?php include ('../footer.php'); ?>
